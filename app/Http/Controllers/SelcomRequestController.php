<?php

namespace App\Http\Controllers;

use App\Models\MobilePayment;
use App\Models\SelcomResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CoverNoteController;
use App\Models\Transaction;

class SelcomRequestController extends Controller
{
    public function __construct(CoverNoteController $auto_issue_cover)
    {
        $this->auto_issue_cover = $auto_issue_cover;
    }

    public function getDigest($timestamp, $api_key, $api_secret)
    {
        return md5($timestamp . $api_secret) . sha1(sha1($timestamp . $api_key . $api_secret, true));
    }

    protected function sendRequest()
    {

    }

    public function cashPayment(Request $request){
        if(Auth::user()->permission == "root"){
            $transid = $request->session()->get("currenttransactionid");
            $updatetrans = DB::update("UPDATE transactions SET payment_mode=?, cash_id=? WHERE id=?", ["1", Auth::user()->id, "$transid"]);

            $trans = DB::select("SELECT * FROM transactions WHERE id=?", ["$transid"]);

            if (Auth::user()->role == "admin") {
                return view("admin.insurance-status", compact('trans'));
            } else {
                return view("agent.insurance-status", compact('trans'));
            }
        }else{
            return redirect()->back()->with("success", "Fail, please contact the right person to approve or choose other payments");
        }

    }

    public function cashPaymentAdmin(Request $request){

        if (Auth::user()->permission == "root") {
            $transid = $request->input("id");
            $updatetrans = DB::update("UPDATE transactions SET payment_mode=?, cash_id=? WHERE id=?", ["1", Auth::user()->id, "$transid"]);

            $trans = DB::select("SELECT * FROM transactions WHERE id=?", ["$transid"]);

            return redirect()->back()->with('success', "Payment for this transaction is successful");
        }
        else{
            return redirect()->back()->with("success", "Fail, please contact the right person to approve or choose other payments");
        }

    }

    public function cashPaymentNonMotor(Request $request){
        if (Auth::user()->permission == "root") {
        $transid = $request->session()->get("currenttransactionid");
        $updatetrans = DB::update("UPDATE transactions SET payment_mode=?, cash_id=? WHERE id=?", ["1", Auth::user()->id, "$transid"]);

        $trans = DB::select("SELECT * FROM transactions WHERE id=?", ["$transid"]);

        if (Auth::user()->role == "admin") {
            return redirect()->route('admin.non-motor-finish');
        } else {

        }
        }
        else{
            return redirect()->back()->with("success", "Fail, please contact the right person to approve or choose other payments");
        }
    }

    protected function createPayment(Request $request)
    {

        $paymentmode = $request->input("paymentmode");
        $mobilenumber = trim($request->input("mnumber"));


            $transid = $request->input("currenttransactionid");
            $amount = $request->input('totalpremiumincludingtax');



        if ($paymentmode == 3) { //check if is mobile payment

            !apiReq() ? $request->session()->put("payment", "mobile") : null;

            $api_key = $_ENV['SELCOM_API_KEY'];
            $api_secret = $_ENV['SELCOM_API_SECRET'];

            $timestamp = gmdate("Y-m-d H:i:s");

            $api_digest = md5($timestamp . $api_secret) . sha1(sha1($timestamp . $api_key . $api_secret, true));

            $res = Http::withOptions(['verify' => false])->withHeaders([
                'api_key' => "$api_key",
                'digest' => "$api_digest",
                'request_timestamp' => "$timestamp"
            ])->post('https://api.selcommobile.com/v1/paymentCreate', [
                "order_id" => "$transid",
                "amount" => "$amount",
                "currency" => "TZS",
                "payer_remarks" => "",
                "merchant_remarks" => "",
                "payer_email" => "",
                "payer_phone" => ""
            ]);

            $resp = json_decode($res);

            if ($resp->result == "SUCCESS") {

                MobilePayment::where('order_id', $transid)->delete();

                $mobile_payment = new MobilePayment;

                $mobile_payment->order_id = "$transid";
                $mobile_payment->currency = "TZS";
                $mobile_payment->amount = "$amount";
                $mobile_payment->paid_amount = "0.00";
                $mobile_payment->remain_amount = "$amount";
                $mobile_payment->payment_status = "PENDING";
                $mobile_payment->payment_token = "$resp->payment_token";
                $mobile_payment->qr = "$resp->qr";
                $mobile_payment->status = "PENDING";

                $mobile_payment->save(); //save payment

                $updatetrans = DB::update("UPDATE transactions SET payment_mode=?, payment_number=?, payment_network=? WHERE id=?", ["$paymentmode", "$resp->payment_token", "$resp->qr",  "$transid"]);

                $mobilepayment = DB::select("SELECT id, order_id, currency, amount, paid_amount, remain_amount, payment_status, payment_token, qr, channel, phone, transid, reference, status, is_deleted, created_at, updated_at FROM mobile_payments WHERE order_id=?", ["$transid"]);

                $trans = DB::select("SELECT * FROM transactions WHERE id=?", ["$transid"]);

                if(!apiReq()){
                    $request->session()->put("insstepstatus", "104");

                    if (Auth::user()->role == "admin") {
                        return view("admin.insurance-status", compact('mobilepayment', 'trans'));
                    } else {
                        return view("agent.insurance-status", compact('mobilepayment', 'trans'));
                    }
                } else {
                    return apiResponse(['payment_number' => $resp->payment_token], 'Success');
                }
            } else {
                if(!apiReq()){
                    if (Auth::user()->role == "admin") {
                        return redirect()->route('admin.payment');
                    } else {
                        return redirect()->route('agent.payment');
                    }
                } else {
                    return apiResponse([], 'Failed', 500);
                }
            }
        } else {
            if(!apiReq()){
                if (Auth::user()->role == "admin") {
                    return redirect()->route('admin.payment');
                } else {
                    return redirect()->route('agent.payment');
                }
            }else {
                return apiResponse([]);
            }
        }
    }


    protected function createPaymentNonMotor(Request $request)
    {

        $paymentmode = $request->input("paymentmode");
        $mobilenumber = trim($request->input("mnumber"));

        if(!apiReq()){
            $transid = $request->session()->get("currenttransactionid");
            $amount = $request->session()->get('totalpremiumincludingtax');
        } else {
            $transid = $request->input("currenttransactionid");
            $amount = $request->input('totalpremiumincludingtax');
        }


        if ($paymentmode == 3) { //check if is mobile payment

            !apiReq() ? $request->session()->put("payment", "mobile") : null;

            $api_key = $_ENV['SELCOM_API_KEY'];
            $api_secret = $_ENV['SELCOM_API_SECRET'];

            $timestamp = gmdate("Y-m-d H:i:s");

            $api_digest = md5($timestamp . $api_secret) . sha1(sha1($timestamp . $api_key . $api_secret, true));

            $res = Http::withOptions(['verify' => false])->withHeaders([
                'api_key' => "$api_key",
                'digest' => "$api_digest",
                'request_timestamp' => "$timestamp"
            ])->post('https://api.selcommobile.com/v1/paymentCreate', [
                "order_id" => "$transid",
                "amount" => "$amount",
                "currency" => "TZS",
                "payer_remarks" => "",
                "merchant_remarks" => "",
                "payer_email" => "",
                "payer_phone" => ""
            ]);

            $resp = json_decode($res);

            if ($resp->result == "SUCCESS") {

                MobilePayment::where('order_id', $transid)->delete();

                $mobile_payment = new MobilePayment;

                $mobile_payment->order_id = "$transid";
                $mobile_payment->currency = "TZS";
                $mobile_payment->amount = "$amount";
                $mobile_payment->paid_amount = "0.00";
                $mobile_payment->remain_amount = "$amount";
                $mobile_payment->payment_status = "PENDING";
                $mobile_payment->payment_token = "$resp->payment_token";
                $mobile_payment->qr = "$resp->qr";
                $mobile_payment->status = "PENDING";

                $mobile_payment->save(); //save payment

                $updatetrans = DB::update("UPDATE transactions SET payment_mode=?, payment_number=?, payment_network=? WHERE id=?", ["$paymentmode", "$resp->payment_token", "$resp->qr",  "$transid"]);

                $mobilepayment = DB::select("SELECT id, order_id, currency, amount, paid_amount, remain_amount, payment_status, payment_token, qr, channel, phone, transid, reference, status, is_deleted, created_at, updated_at FROM mobile_payments WHERE order_id=?", ["$transid"]);

                $trans = DB::select("SELECT * FROM transactions WHERE id=?", ["$transid"]);

                if(!apiReq()){
                    $request->session()->put("insstepstatus", "104");

                    if (Auth::user()->role == "admin") {
                        return redirect()->route('admin.non-motor-finish');
                    } else {
                        return redirect()->route('agent.non-motor-finish');
                    }
                } else {
                    return apiResponse(['payment_number' => $resp->payment_token], 'Success');
                }
            } else {
                if(!apiReq()){
                    if (Auth::user()->role == "admin") {
                        return redirect()->route('admin.non-motor-payment');
                    } else {
                        return redirect()->route('agent.non-motor-payment');
                    }
                } else {
                    return apiResponse([], 'Failed', 500);
                }
            }
        } else {
            if(!apiReq()){
                if (Auth::user()->role == "admin") {
                    return redirect()->route('admin.non-motor-payment');
                } else {
                    return redirect()->route('agent.non-motor-payment');
                }
            }else {
                return apiResponse([]);
            }
        }
    }



    public function createPaymentMasterpassNormal(Request $request){
        try{
                $paid = MobilePayment::where('order_id', $request->input('tid'))->first();

                $paid->remain_amount = $paid->remain_amount-$request->input('paid');
                $paid->transid = $request->input('transid');
                $paid->reference = $request->input('reference');
                $paid->channel = $request->input('channel');
                $paid->paid_amount =$request->input('paid');
                $paid->status = "COMPLETE";
                $paid->payment_status = "COMPLETE";
                $paid->approved_by = Auth::user()->id;
                $paid->save();

                if($paid == true){
                return redirect()->back()->with('success', "Payment for this transaction is successful completed confirmed");
                }else{
                    return redirect()->back()->with('success', "fail confirm payments");
                }

        }catch(\Exception $e){
                return redirect()->back()->with('success', "fail confirm payment");
        }



    }

    public function createPaymentMasterpassIns(Request $request){
        try{

            $transid = $request->input('id');
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
            ]);

            $image = $request->file('file');
            $newname = time().rand(10,100) . '.' . $image->extension();
            $image->move('vehicle/', $newname);

            MobilePayment::where('order_id', $transid)->delete();

            $mobile_payment = new MobilePayment;

            $mobile_payment->order_id = $request->input('id');
            $mobile_payment->currency = "TZS";
            $mobile_payment->amount = $request->input('amount');
            $mobile_payment->paid_amount = "0.00";
            $mobile_payment->remain_amount = $request->input('amount');
            $mobile_payment->payment_status = "PENDING";
            $mobile_payment->payment_token = "Direct";
            $mobile_payment->qr = "Direct";
            $mobile_payment->channel = $request->input("channel");
            $mobile_payment->phone = $request->input("phone");
            $mobile_payment->payment_date = $request->input("paymentdate");
            $mobile_payment->transid = $request->input("transid");
            $mobile_payment->reference = $request->input("reference");
            $mobile_payment->receipt_uploaded = $newname;
            $mobile_payment->types = "Direct";
            $mobile_payment->status = "PENDING";
            $mobile_payment->save(); //save payment

            $updatetrans = DB::update("UPDATE transactions SET payment_mode=? WHERE id=?", ["3", "$transid"]);

            $issued_by = Transaction::findOrFail($request->input("id"));
            $insured_name = $issued_by->customer->full_name;

            sendNotification("255719696023", "Quotation with Masterpass payment is created successful it need payment approved to request cover TIRA, Insured name $insured_name", "Quotation with Masterpass payment is created successful it need payment approved to request cover TIRA, Insured name $insured_name");

            return redirect()->back()->with('success', "successful upload payment details");

        }catch(\Exception $e){
            return redirect()->back()->with('success', "fail upload payment details");
        }
    }

    public function createPaymentMasterpass(Request $request){
        try{
            $paymentmode = $request->input("paymentmode");
            $mobilenumber = trim($request->input("mnumber"));

            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
            ]);

            $image = $request->file('file');
            $newname = time().rand(10,100) . '.' . $image->extension();
            $image->move('vehicle/', $newname);

            if(!apiReq()){
                $transid = $request->session()->get("currenttransactionid");
                $amount = $request->session()->get('totalpremiumincludingtax');
            } else {
                $transid = $request->input("currenttransactionid");
                $amount = $request->input('totalpremiumincludingtax');
            }

            MobilePayment::where('order_id', $transid)->delete();

            $mobile_payment = new MobilePayment;

            $mobile_payment->order_id = "$transid";
            $mobile_payment->currency = "TZS";
            $mobile_payment->amount = "$amount";
            $mobile_payment->paid_amount = "0.00";
            $mobile_payment->remain_amount = "$amount";
            $mobile_payment->payment_status = "PENDING";
            $mobile_payment->payment_token = "Direct";
            $mobile_payment->qr = "Direct";
            $mobile_payment->channel = $request->input("channel");
            $mobile_payment->phone = $request->input("phone");
            $mobile_payment->payment_date = $request->input("paymentdate");
            $mobile_payment->transid = $request->input("transid");
            $mobile_payment->reference = $request->input("reference");
            $mobile_payment->receipt_uploaded = $newname;
            $mobile_payment->types = "Direct";
            $mobile_payment->status = "PENDING";
            $mobile_payment->save(); //save payment

            $updatetrans = DB::update("UPDATE transactions SET payment_mode=? WHERE id=?", ["$paymentmode", "$transid"]);

            $mobilepayment = DB::select("SELECT * FROM mobile_payments WHERE order_id=?", ["$transid"]);

            $trans = DB::select("SELECT * FROM transactions WHERE id=?", ["$transid"]);

            $request->session()->put("insstepstatus", "104");

            if (Auth::user()->role == "admin") {
                return view("admin.insurance-status", compact('mobilepayment', 'trans'));
            } else {
                $issued_by = Transaction::findOrFail($request->input("id"));
                $insured_name = $issued_by->customer->full_name;

                sendNotification("255719696023", "Quotation with Masterpass payment is created successful it need payment approved to request cover TIRA, Insured name $insured_name", "Quotation with Masterpass payment is created successful it need payment approved to request cover TIRA, Insured name $insured_name");

                return view("agent.insurance-status", compact('mobilepayment', 'trans'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with('success', "fail upload payment details");
        }

    }



    public function createPaymentMasterpassNonMotor(Request $request){
        try{
            $paymentmode = $request->input("paymentmode");
            $mobilenumber = trim($request->input("mnumber"));

            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
            ]);

            $image = $request->file('file');
            $newname = time().rand(10,100) . '.' . $image->extension();
            $image->move('vehicle/', $newname);

            if(!apiReq()){
                $transid = $request->session()->get("currenttransactionid");
                $amount = $request->session()->get('totalpremiumincludingtax');
            } else {
                $transid = $request->input("currenttransactionid");
                $amount = $request->input('totalpremiumincludingtax');
            }

            MobilePayment::where('order_id', $transid)->delete();

            $mobile_payment = new MobilePayment;

            $mobile_payment->order_id = "$transid";
            $mobile_payment->currency = "TZS";
            $mobile_payment->amount = "$amount";
            $mobile_payment->paid_amount = "0.00";
            $mobile_payment->remain_amount = "$amount";
            $mobile_payment->payment_status = "PENDING";
            $mobile_payment->payment_token = "Direct";
            $mobile_payment->qr = "Direct";
            $mobile_payment->channel = $request->input("channel");
            $mobile_payment->phone = $request->input("phone");
            $mobile_payment->payment_date = $request->input("paymentdate");
            $mobile_payment->transid = $request->input("transid");
            $mobile_payment->reference = $request->input("reference");
            $mobile_payment->receipt_uploaded = $newname;
            $mobile_payment->types = "Direct";
            $mobile_payment->status = "PENDING";
            $mobile_payment->save(); //save payment

            $updatetrans = DB::update("UPDATE transactions SET payment_mode=? WHERE id=?", ["$paymentmode", "$transid"]);

            $mobilepayment = DB::select("SELECT * FROM mobile_payments WHERE order_id=?", ["$transid"]);

            $trans = DB::select("SELECT * FROM transactions WHERE id=?", ["$transid"]);

            $request->session()->put("insstepstatus", "104");

            if (Auth::user()->role == "admin") {
                return redirect()->route('admin.non-motor-finish');
            } else {
                $issued_by = Transaction::findOrFail($request->input("id"));
                $insured_name = $issued_by->customer->full_name;

                sendNotification("255719696023", "Quotation with Masterpass payment is created successful it need payment approved to request cover TIRA, Insured name $insured_name", "Quotation with Masterpass payment is created successful it need payment approved to request cover TIRA, Insured name $insured_name");
                return redirect()->route('agent.non-motor-finish');
            }
        }catch(\Exception $e){
            return redirect()->back()->with('success', "fail upload payment details");
        }

    }

    protected function paymentCallBack(Request $request)
    {
        try{
            $contents = $request->getContent();
            if ($contents) {
                $data = json_decode($contents);

                $selcom_res = new SelcomResult;

                $selcom_res->result = $data->result;
                $selcom_res->order_id = $data->order_id;
                $selcom_res->amount = $data->amount;
                $selcom_res->payment_status = $data->payment_status;
                $selcom_res->payment_token = $data->reference;
                $selcom_res->channel = $data->channel;
                $selcom_res->phone = $data->phone;
                $selcom_res->transid = $data->transid;
                $selcom_res->reference = $data->reference;

                $selcom_res->save();

                $tran = MobilePayment::where('order_id', $data->order_id)->first();

                $status_py = "";
                $remain_amount = 0;
                $paid_amount = 0;

                //$paymentrec->order_id;
                $remain_amount = $tran->remain_amount - $data->amount;
                $paid_amount = $tran->paid_amount + $data->amount;

                if ($paid_amount == $tran->amount) {
                    $status_py = "COMPLETE";
                    if(substr($data->order_id, 0, 2) != 'FE'){
                      try{ $cover = $this->auto_issue_cover->motorCoverNoteRefReq($data->order_id);}catch(\Exception $e){};
                    }
                } else {
                    $status_py = "DUE";
                }


                $update = MobilePayment::where('order_id', "$data->order_id")
                    ->update([
                        'paid_amount' => "$paid_amount",
                        'remain_amount' => "$remain_amount",
                        'payment_status' => "$status_py",
                        'channel' => "$data->channel",
                        'phone' => "$data->phone",
                        'transid' => "$data->transid",
                        'reference' => "$data->reference",
                        'status' => "$status_py"
                    ]);

                    //auto request cover

                return json_encode(array('response' => 'OK'));
            } else {
                return json_encode(array('response' => 'Fail'));
            }
        }catch(\Exception $e){
            return json_encode(array('response' => 'Fail'));
        }
    }

    public function checkPayment(Request $request)
    {

        $request_id = $request->session()->get("currenttransactionid");
        $paymentrec = DB::select("SELECT * FROM mobile_payments WHERE order_id=?", ["$request_id"]);

        return $paymentrec;
    }


    public function paymentStatus($id){

        $api_key = $_ENV['SELCOM_API_KEY'];
        $api_secret = $_ENV['SELCOM_API_SECRET'];

        $timestamp = gmdate("Y-m-d H:i:s");

        $api_digest = md5($timestamp . $api_secret) . sha1(sha1($timestamp . $api_key . $api_secret, true));

        $res = Http::withOptions(['verify' => false])->withHeaders([
            'api_key' => "$api_key",
            'digest' => "$api_digest",
            'request_timestamp' => "$timestamp"
        ])->post("https://api.selcommobile.com/v1/paymentStatus/$id");

        $resp = json_decode($res);

        if($resp->result == "SUCCESS" && $resp->payment_status == "COMPLETED"){

            $m_payment = DB::select("SELECT * FROM mobile_payments WHERE order_id=?", ["$resp->order_id"]);

            $remain_amount = 0;
            $paid_amount = 0;

            foreach ($m_payment as $tran) {

                $remain_amount = $tran->remain_amount - $resp->amount;
                $paid_amount = $tran->paid_amount + $resp->amount;

            }

            $update = MobilePayment::where('order_id', "$resp->order_id")
                ->update([
                    'paid_amount' => "$paid_amount",
                    'remain_amount' => "$remain_amount",
                    'payment_status' => "$resp->payment_status",
                    'channel' => "$resp->channel",
                    'phone' => "$resp->phone",
                    'transid' => "$resp->transid",
                    'reference' => "$resp->reference",
                    'status' => "$resp->payment_status"
                ]);

            return redirect()->back()
                ->with('success', "Payment for this transaction is successful completed, amount paid is  $resp->amount");

        }elseif($resp->result == "SUCCESS" && $resp->payment_status == "PENDING"){

            return redirect()->back()
            ->with('message-error', 'Payment status is Pending');

        }else{

            return redirect()->back()
            ->with('message-error', 'Payment request for this transaction is not available');

        }
    }


    protected function fleetMobilePayment(Request $request)
    {

            $api_key = $_ENV['SELCOM_API_KEY'];
            $api_secret = $_ENV['SELCOM_API_SECRET'];
            $timestamp = gmdate("Y-m-d H:i:s");

            $api_digest = md5($timestamp . $api_secret) . sha1(sha1($timestamp . $api_key . $api_secret, true));

            $request_paynumber = Http::withOptions(['verify' => false])->withHeaders([
                'api_key' => "$api_key",
                'digest' => "$api_digest",
                'request_timestamp' => "$timestamp"
            ])->post('https://api.selcommobile.com/v1/paymentCreate', [
                "order_id" => $request->input('id'),
                "amount" => $request->input('amount'),
                "currency" => "TZS",
                "payer_remarks" => "",
                "merchant_remarks" => "",
                "payer_email" => "",
                "payer_phone" => ""
            ]);

            //selcom payment request response
            $selcom_response = json_decode($request_paynumber);

            if ($selcom_response->result == "SUCCESS") {

                MobilePayment::where('order_id', $request->input('id'))->delete();

                $mobile_payment = new MobilePayment;

                $mobile_payment->order_id = $request->input('id');
                $mobile_payment->currency = "TZS";
                $mobile_payment->amount = $request->input('amount');
                $mobile_payment->paid_amount = "0.00";
                $mobile_payment->remain_amount = $request->input('amount');
                $mobile_payment->payment_status = "PENDING";
                $mobile_payment->payment_token = $selcom_response->payment_token;
                $mobile_payment->qr = $selcom_response->qr;
                $mobile_payment->status = "PENDING";

                $mobile_payment->save(); //save payment

                $updatetrans = DB::update("UPDATE transactions SET payment_mode=? WHERE id=?", [3, $request->input('id')]);

                if($mobile_payment == true){
                    if(Auth::user()->role == "admin") {
                        return redirect()->route("admin.fleet-status");
                    }else{
                        return redirect()->route("admin.fleet-status");
                    }
                }else{
                    return redirect()->back()
                    ->with('message-error', 'Payment request for this transaction is fail, Please try again');
                }
            }else{
                return redirect()->back()
                ->with('message-error', 'Payment request for this transaction is fail, Please try again');
            }
    }
}
