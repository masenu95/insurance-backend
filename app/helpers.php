<?php


use App\Models\CountMessage;
use App\Models\District;
use App\Models\Region;
use App\Models\TiramisResponse;
use App\Models\User;
use App\Services\EncryptionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\ArrayToXml\ArrayToXml;
use \Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\InsuranceCoverage;
use App\Models\Transaction;
use App\Models\MobilePayment;
use App\Models\BankPayments;
use App\Models\AddonProduct;
use App\Models\InsuranceProduct;
use App\Models\Addon;
use Illuminate\Support\Facades\Mail;

define('STATUS_CODE', 'TIRA001');
define('STATUS_DESCRIPTION', 'Successful');

function parseArrayToXML($data, $root_tag, $pretty = false): string
{
    try {
        return $pretty ? (new ArrayToXml($data, $root_tag))->dropXmlDeclaration()->prettify()->toXml() : (new ArrayToXml($data, $root_tag))->dropXmlDeclaration()->toXml();
    } catch (DOMException $e) {
        report($e);
    }
}

function parseXMLtoArray($data): array
{
    $json = json_encode((array)simplexml_load_string($data));
    return json_decode($json, 1);
}

function logToCustom($data)
{
    Log::channel('tiramis')->info(json_encode($data, JSON_PRETTY_PRINT));
}

function formatDate($date): string
{
    return (new Carbon($date))->format('Y-m-d\\TH:i:s');
}

function wrapPayload($content): string
{
    return "<TiraMsg>{$content}<MsgSignature>" . EncryptionService::generateDigitalSignature($content) . "</MsgSignature></TiraMsg>";
}

function xmlResponseHandler($content)
{
    return response(wrapPayload($content), 200, ['Content-Type' => 'application/xml']);
}

function APIClient($content, $url): array
{
    $data = wrapPayload($content);

    logToCustom(parseXMLtoArray($data));

    $response = Http::withOptions(['verify' => false])->withHeaders(['ClientCode' => env('CLIENT_CODE_N'), 'ClientKey' => env('CLIENT_KEY_N')])
        ->withBody($data, 'application/xml')
        ->post($url)
        ->body();

    $response = parseXMLtoArray($response);

    logToCustom($response);

    return $response;
}

function discordBot($content, $type = 'json'): Response
{
    switch ($type) {
        case 'json':
            $content = json_encode($content, JSON_PRETTY_PRINT);
            $content = "```json\n${content}```";
            break;
        case 'xml':
            $content = "```xml\n${content}```";
            break;
    }
    return Http::post(env('DISCORD_WEBHOOK_URL'), ['content' => $content]);
}

function generateRequestID(): string
{
    return 'TRN'.rand(10,100).time();
}

function generateAcknowledgementID(): string
{
    return 'ACK' . time();
}

function apiReq()
{
    return request()->is('api/*');
}

function apiResponse($data = null, $message = null, $code = 200)
{
    return response()->json([
        'code' => $code,
        'message' => $message,
        'data' => $data
    ], $code);
}

function insertedDataLog($data){
    try{
        $contents = json_encode($data, JSON_PRETTY_PRINT);
        $file = Storage::disk('local')->append("inserted-data.txt", "-------------------- CREATED AT [ ".date('M d, Y H:i:s')." ] ----------------- \n".$contents."\n----------------------- END -------------------------- \n\n");

        //return $file

    }catch(\Exception $e){
       //return error message
    }
}

function exceptionLog($data){
    try{
        $file = Storage::disk('local')->append("exception-data.txt", "-------------------- CREATED AT [ ".date('M d, Y H:i:s')." ] ----------------- \n".$data."\n----------------------- END -------------------------- \n\n");

        //return $file

    }catch(\Exception $e){
       //return error message
    }
}

function exceptions($data){
    try{
        $file = Storage::disk('local')->append("exceptions.txt", "-------------------- CREATED AT [ ".date('M d, Y H:i:s')." ] ----------------- \n".$data."\n----------------------- END -------------------------- \n\n");

        //return $file

    }catch(\Exception $e){
       //return error message
    }
}


function phoneInsLog($data, $type){
    try{
        $file = Storage::disk('local')->append("phoneins.log", "---------  $type LOG --- CREATED AT [ ".date('M d, Y H:i:s')." ] ------------- \n".json_encode($data, JSON_PRETTY_PRINT)."\n----------------------- END -------------------------- \n\n");

        //return $file

    }catch(\Exception $e){
       //return error message
    }
}

function phoneInsLogs($data, $type){
    try{
        $file = Storage::disk('local')->append("phoneins.txt", "---------  $type LOG --- CREATED AT [ ".date('M d, Y H:i:s')." ] ------------- \n".json_encode($data, JSON_PRETTY_PRINT)."\n----------------------- END -------------------------- \n\n");

        //return $file

    }catch(\Exception $e){
       //return error message
    }
}

function countMessageUsed($to, $contents){
    $msgcount = new CountMessage;
    $msgcount->message = $contents;
    $msgcount->to = $to;
    $msgcount->save();
}

function sendSimpleClientSMS($message, $phone){
    $response = Http::withOptions(['verify' => false])->post(env('SMS_URL'), [
        'channel' => [
            'channel' => env('SMS_CHANNEL'),
            'password' => env('SMS_PASSWORD')
        ],
        'messages' => [
            [
                'text' => $message,
                'msisdn' => $phone,
                'source' => env('SMS_SOURCE')
            ]
        ]
    ]);
}

function sendVushaSmsNewCount($phone, $message, $protected, $re, $total){
    $total = DB::table('count_messages')->where('request', $re)->count();
    if($total < 2){
        try{
            $body = [
                'to' => $phone,
                'text' => $message,
                'from' => 'SanlamVaps'

            ];

            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env('SMS_PASSWORD_VUSHA'),
            ];

            $client = new Client();

            $params['headers'] = $headers;
            $params['json'] = $body;

            $response = $client->post(env('SMS_URL_VUSHA'), $params)
                        ->getBody()
                        ->getContents();

            Log::channel('sms')->info(json_encode($response, JSON_PRETTY_PRINT));

            $msgcount = new CountMessage;
            $msgcount->message = $protected;
            $msgcount->to = $phone;
            $msgcount->request  = $re;
            $msgcount->count  = $total;
            $msgcount->save();

            return $response;

        }catch(\Exception $e){
            Log::channel('smserror')->info($e);
            exceptions($e);
            $res = Http::withOptions(['verify' => false])->post(env('SMS_URL'), [
                'channel' => [
                    'channel' => env('SMS_CHANNEL'),
                    'password' => env('SMS_PASSWORD')
                ],
                'messages' => [
                    [
                        'text' => $message,
                        'msisdn' => $phone,
                        'source' => env('SMS_SOURCE')
                    ]
                ]
            ]);

            $msgcount = new CountMessage;
            $msgcount->message = $protected;
            $msgcount->to = $phone;
            $msgcount->request  = $re;
            $msgcount->count  = $total;
            $msgcount->save();

            return array("error","0001");
        }
    }else{
        return array("error","0001");
    }

}


function sendVushaSmsNew($phone, $message, $protected, $re){
    $total = DB::table('count_messages')->where('request', $re)->count();
    if($total < 2){
        try{
            $body = [
                'to' => $phone,
                'text' => $message,
                'from' => 'SanlamVaps'

            ];

            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env('SMS_PASSWORD_VUSHA'),
            ];

            $client = new Client();

            $params['headers'] = $headers;
            $params['json'] = $body;

            $response = $client->post(env('SMS_URL_VUSHA'), $params)
                        ->getBody()
                        ->getContents();

            Log::channel('sms')->info(json_encode($response, JSON_PRETTY_PRINT));

            $msgcount = new CountMessage;
            $msgcount->message = $protected;
            $msgcount->to = $phone;
            $msgcount->request  = $re;
            $msgcount->save();

            return $response;

        }catch(\Exception $e){
            Log::channel('smserror')->info($e);
            exceptions($e);
            $res = Http::withOptions(['verify' => false])->post(env('SMS_URL'), [
                'channel' => [
                    'channel' => env('SMS_CHANNEL'),
                    'password' => env('SMS_PASSWORD')
                ],
                'messages' => [
                    [
                        'text' => $message,
                        'msisdn' => $phone,
                        'source' => env('SMS_SOURCE')
                    ]
                ]
            ]);

            $msgcount = new CountMessage;
            $msgcount->message = $protected;
            $msgcount->to = $phone;
            $msgcount->request  = $re;
            $msgcount->save();

            return array("error","0001");
        }
    }else{
        return array("error","0001");
    }

}

function sendVushaSms($phone, $message, $protected){

   try{
        $body = [
            'to' => $phone,
            'text' => $message,
            'from' => 'SanlamVaps'

        ];

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('SMS_PASSWORD_VUSHA'),

        ];

        $client = new Client();

        $params['verify'] = false;
        $params['headers'] = $headers;
        $params['json'] = $body;

        $response = $client->post(env('SMS_URL_VUSHA'), $params)
                    ->getBody()
                    ->getContents();

        Log::channel('sms')->info(json_encode($response, JSON_PRETTY_PRINT));

        $msgcount = new CountMessage;
        $msgcount->message = $protected;
        $msgcount->to = $phone;
        $msgcount->save();

        return $response;

    }catch(\Exception $e){
        Log::channel('smserror')->info($e);
        $res = Http::withOptions(['verify' => false])->post(env('SMS_URL'), [
            'channel' => [
                'channel' => env('SMS_CHANNEL'),
                'password' => env('SMS_PASSWORD')
            ],
            'messages' => [
                [
                    'text' => $message,
                    'msisdn' => $phone,
                    'source' => env('SMS_SOURCE')
                ]
            ]
        ]);

        $msgcount = new CountMessage;
        $msgcount->message = $protected;
        $msgcount->to = $phone;
        $msgcount->save();

        return $res;
    }

}


function addons($id){
    return DB::table('addons')->where('transaction_id', $id)->get();
}

function getCoverage($id){
    return DB::table('insurance_coverages')->where('insurance_product_id', $id)->get();
}









function getCode($type){
    if(Auth::user()->role=="admin"){
        $code = DB::table('branches')->where('id', Auth::user()->user_id)->get();
        $company_code=$sale_code="null";

        foreach($code as $get_code){
            $company_code = $get_code->company_code;
            $sale_code = $get_code->sales_point_code;
        }
        if($sale_code =="null" && $company_code=="null"){
            if($type == "company"){
                return "ICC101";
            }elseif($type == "sales"){
                return "SP588";
            }
        }elseif($sale_code =="" && $company_code==""){
            if($type == "company"){
                return "ICC101";
            }elseif($type == "sales"){
                return "SP588";
            }
        }else{
            if($type == "company"){
                return $company_code;
            }elseif($type == "sales"){
                return $sale_code;
            }
        }
    }elseif(Auth::user()->role=="agent"){
        $code = DB::table('agents')->where('id', Auth::user()->user_id)->get();

        $company_code=$sale_code="null";

        foreach($code as $get_code){
            $company_code = $get_code->company_code;
            $sale_code = $get_code->sales_point_code;
        }
        if($sale_code =="null" && $company_code=="null"){
            if($type == "company"){
                return "ICC101";
            }elseif($type == "sales"){
                return "SP588";
            }
        }elseif($sale_code =="" && $company_code==""){
            if($type == "company"){
                return "ICC101";
            }elseif($type == "sales"){
                return "SP588";
            }
        }else{
            if($type == "company"){
                return $company_code;
            }elseif($type == "sales"){
                return $sale_code;
            }
        }
    }
}





function tiramisSentSave($req, $sent, $un)
{
    try{
        $ins = new TiramisResponse;
        $ins->request_id = $req;
        $ins->unique_field = $un;
        $ins->sent = $sent;
        $ins->save();
        return 0;
    }
    catch(\Exception $e)
    {
        return 0;
    }
}

function tiramisResponseSave($req, $res)
{
    try{
        if(TiramisResponse::where('request_id', $req)->count() == 1)
        {
            $up = TiramisResponse::where('request_id', $req)->first();
            TiramisResponse::where('request_id', $req)->update([
                'response' => $up->response .'-'. $res
            ]);
        }
        else
        {
            $ins = new TiramisResponse;
            $ins->request_id = $req;
            $ins->response = $res;
            $ins->save();
            return 0;
        }
        return 0;
    }
    catch(\Exception $e)
    {
        return $e;
    }
}



function vunaPointCal($amount, $id)
{
    try {
        $cov = InsuranceCoverage::where('id', $id)->first();
        $point = 0;

        if($cov->coverage_type == "Comprehensive" && $cov->rate > 0)
        {$point = ($amount*1.5)/100000;}
        elseif($cov->coverage_type == "Third Party" && $cov->rate == 0)
        {$point = ($amount*1)/125000;}
        elseif($cov->rate > 0)
        {$point = ($amount*1.5)/100000;}
        else
        {$point = $amount/100000;}
        return round($point,2);
    }catch (\Exception $e)
    {
        return 0;
    }
}







function claimEmailNotification($fault, $user_id, $transaction)
{
    try
    {
        $user = User::where('id', $user_id)->first();
        $cover = Transaction::where('id', $transaction)->first();
        return 0;
    }
    catch (\Exception $e)
    {
        return 0;
    }
}



function transactionDetails($id)
{
    try {
        return Transaction::where('id', $id)->first();
    }
    catch (\Exception $e)
    {
        return [];
    }
}

function mobilePayment($id)
{
    try {
        return MobilePayment::where('order_id', $id)->first();
    }
    catch (\Exception $e)
    {
        return [];
    }
}

function bankPayment($id)
{
    try {
        return BankPayments::where('order_id', $id)->first();
    }
    catch (\Exception $e)
    {
        return [];
    }
}





function getReportDate($input)
{
    $output = 0;
    $b = 0;

    if($input == "TODAY")
    {
        $output = Carbon::today();
    }
    elseif($input == "YESTERDAY")
    {
        $output = Carbon::yesterday();
    }
    elseif($input == "TMONTH")
    {
        $output = Carbon::now()->month;
    }
    elseif($input == "LMONTH")
    {
        $output = Carbon::now()->subMonth()->month;
    }
    elseif($input == "TYEAR")
    {
        $output = Carbon::now()->year;
    }
    elseif($input == "LYEAR")
    {
        $output = Carbon::now()->subYear()->year;
    }
    elseif($input == "TWEEK")
    {
        $a = Carbon::now()->startOfWeek();
        $b = Carbon::now()->endOfWeek();
        $output = $a;
    }
    elseif($input == "LWEEK")
    {
        $a = Carbon::now()->subWeek()->startOfWeek();
        $b = Carbon::now()->subWeek()->endOfWeek();
        $output = $a;
    }

    return array("output" => $output, "output_b" => $b);
}

function checkForRenew($data)
{
    try {
        $trans = Transaction::where('registration_number', $data)->where('response_status_code','!=', 'TIRA214')->where('status', 'Success')->orderByDesc('id')->limit(1)->first();

        $diffInDays = Carbon::now()->diffInDays(Carbon::parse($trans->covernote_end_date));
        if($diffInDays < 100)
        {
            return array("type" => 2, "reference"=> $trans->covernote_reference_number);
        }
        else
        {
            return array("type" => 1, "reference"=> null);
        }
    }
    catch (\Exception $e)
    {
        return array("type" => 1, "reference"=> null);
    }
}

function checkProductValidation($make, $body_type, $model, $model_number,$weight, $coverage)
{
    try
    {
        $data = InsuranceCoverage::where('id', $coverage)->first();
       if(Str::contains($model_number, ['Tanker', 'tanker', 'Tankers', 'TANKER', 'TANKERS']) || Str::contains($model, ['Tanker', 'tanker', 'Tankers', 'TANKER', 'TANKERS']) || Str::contains($make, ['Tanker', 'tanker', 'Tankers', 'TANKER', 'TANKERS']) || Str::contains($body_type, ['Tanker', 'tanker', 'Tankers', 'TANKER', 'TANKERS']))
       {
           if(Str::contains($data->name, ['Tanker', 'tanker', 'Tankers', 'TANKER', 'TANKERS'])) { return 0; }
           else { return 1; }
       }
       elseif($data->min_weight && $data->max_weight > 0)
       {
           if($weight >= $data->min_weight && $weight <= $data->max_weight) { return 0; }
           else { return 1; }
       }
       else { return 0; }
    }
    catch(\Exception $e)
    { return $e->getMessage(); }
}

function getRegionName($id)
{
    return Region::where('id', $id)->first()->name;
}

function getDistrictName($id)
{
    return District::where('id', $id)->first()->name;
}


function getAddonsList($type, $id)
{
    try{
        if($type == "ALL"){
            return AddonProduct::get();
        }
        elseif($type == "SINGLE")
        {
            return [];
        } else{ return [];}
    }
    catch (\Exception $e)
    {return [];}
}

function getInsuranceProduct($id)
{
    try{
        return InsuranceProduct::where('id', $id)->first();
    }
    catch (\Exception $e)
    {return [];}
}

function getInsuranceCoverage($id)
{
    try{
        return InsuranceCoverage::where('id', $id)->first();
    }
    catch (\Exception $e)
    {return ["coverage_type" => 0];}
}


function checkTransAddonsAvailability($trans_id, $addons_id)
{
    try{
        $check = Addon::where('transaction_id', $trans_id)->where('addon_product_id', $addons_id)->count();
        if($check > 0)
        {
            return 1;
        }
        else{
            return 0;
        }
    }
    catch (\Exception $e)
    {
        return 0;
    }
}

function getAllInsuranceProduct($id)
{
    return InsuranceProduct::where('insurance_type_id', $id)->get();
}

function getAllInsuranceCoverage($id)
{
    return InsuranceCoverage::where('insurance_product_id', $id)->get();
}

function testEmailNotification($email)
{
    try{
        $transaction = [
            "test"=> 4
        ];
        Mail::send('mail.test', ['transaction' => $transaction], function ($m) use ($transaction) {
            $m->from('support@vusha.co.tz', 'Sanlam General Insurance VAPS');
            $m->to('isihakakiwory@gmail.com')->subject('Test Email Notification');
        });
        return 1;
    }
    catch (\Exception $e)
    { return 0; }
}


function claimsEmailNotification()
{
    try{
        $transaction = [
            "test"=> 4
        ];
        Mail::send('mail.claim', ['transaction' => $transaction], function ($m) use ($transaction) {
            $m->from('support@vusha.co.tz', 'Sanlam General Insurance VAPS');
            $m->to('isihakakiwory@gmail.com')->cc('isihakakiwory@ottimale.co.tz')->subject('Claim Email Notification');
        });
        return 1;
    }
    catch (\Exception $e)
    { return 0; }
}




function cancellationAdvices($id)
{
    $startdate = date('Y-m-d');
    $startdate="$startdate"." 23:58:30";

    $transaction = Transaction::where('id', $id)->first();
    $days=$before=$premium=$product=$duration=0;

    $re_date = Carbon::parse($transaction->covernote_start_date);
    $re_now = Carbon::now();
    $days = $re_date->diffInDays($re_now);
    $before = $transaction->total_premium_including_tax;
    $product = $transaction->insurance_type_id;
    $duration = $transaction->durations;

    if($duration < 12){
        $cover_days = $duration*30;
        $premium = ($days/$cover_days)*$before;
    }else{
        if($product==2){
            if($days < 1){$premium = null;}else{$premium = ($days/365)*$before;}
        }else{
            if($days < 1){$premium = null;}else{$premium = ($days/366)*$before;}
        }
    }

    return "Days covered :$days, Endorsement Premium Earned: ".number_format($premium,2).", Premium Inclusive: ".number_format($before,2);

}
