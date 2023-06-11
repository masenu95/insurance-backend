<?php

namespace App\Services;

use App\Models\Agent;
use App\Models\CountMessage;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\SlackLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function customMessage($recipient, $message): array
    {
        return $this->client($message, $recipient);
    }

    public function responseMessage($transaction): array
    {
        try{
            $startdate = date_format(date_create($transaction->covernote_start_date), 'd M Y');
            $enddate = date_format(date_create($transaction->covernote_end_date), 'd M Y');
            $message="";

            $premium = number_format($transaction->total_premium_including_tax,2);

            $info = User::where('id', $transaction->user_id)->first();
            if($info->role == "admin"){
                if($transaction->insurance_type_id==2){
                    $message = "Asante. Sticker: {$transaction->sticker_number}, Cover Note:{$transaction->covernote_reference_number} for {$transaction->registration_number}, Amount TZS {$premium} from {$startdate} to {$enddate} - Meticulous General Insurance";
                }else{
                    $message = "Asante. Cover Note:{$transaction->covernote_reference_number}, Amount TZS {$premium} from {$startdate} to {$enddate} - Meticulous General Insurance";
                }
            }else{

                $agent_name = Agent::where('id', $info->user_id)->first();
                if($transaction->insurance_type_id==2){
                    $message = "Asante. Sticker: {$transaction->sticker_number}, Cover Note:{$transaction->covernote_reference_number} for {$transaction->registration_number}, Amount TZS {$premium} from {$startdate} to {$enddate}, Agency name: $agent_name->name, Insurer: Meticulous General Insurance";
                }else{
                    $message = "Asante. Cover Note:{$transaction->covernote_reference_number}, Amount TZS {$premium} from {$startdate} to {$enddate}, Agency name: $agent_name->name, Insurer: Meticulous General Insurance";
                }
            }
          
            $this->countMessage($transaction->customer->phone_number, $message);

            return $this->client($message, $transaction->customer->phone_number);
        }catch(\Exception $e){

        }


    }

    public function errorMessage($transaction): array
    {
        $status = $transaction->response_status_code ? $transaction->response_status_code : $transaction->acknowledgement_status_code;
        $desc = $transaction->response_status_desc ? $transaction->response_status_desc : $transaction->acknowledgement_status_desc;

        $message = "An error has occurred with STATUS -> {$status} and DESCRIPTION -> {$desc}";
        return $this->client($message, 255742701011);
    }

    public function slackLogger(Transaction $trans)
    {
        $trans->notify(new SlackLogger());
    }

    private function client($message, $recipient): array
    {
        $response = Http::withOptions(['verify' => false])->post(env('SMS_URL'), [
            'channel' => [
                'channel' => env('SMS_CHANNEL'),
                'password' => env('SMS_PASSWORD')
            ],
            'messages' => [
                [
                    'text' => $message,
                    'msisdn' => $recipient,
                    'source' => env('SMS_SOURCE')
                ]
            ]
        ]);

        return $response->json();
    }


    public function countMessage($to, $contents){
        $msgcount = new CountMessage();
        $msgcount->message = $contents;
        $msgcount->to = $to;
        $msgcount->save();
    }
}
