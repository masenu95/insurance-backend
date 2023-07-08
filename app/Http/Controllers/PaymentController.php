<?php

namespace App\Http\Controllers;

use App\ApigwClient\Client;
use App\Models\BankPayments;
use App\Models\MobilePayment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function mobile(Request $request){


            //try{

            $api_key = $_ENV['SELCOM_API_KEY'];
            $api_secret = $_ENV['SELCOM_API_SECRET'];

            $baseUrl = "https://apigw.selcommobile.com";

            date_default_timezone_set('Africa/Dar_es_Salaam');


            $timestamp = date('c');

            //return [$timestamp , $api_key , $api_secret];
            $transid = $request->input("id");
            $amount = trim($request->input("premium"));

            $authToken = "SELCOM" .' '.base64_encode($api_key);
            $data = "timestamp=$timestamp";
            $digest = base64_encode(hash_hmac('sha256', $data, $api_secret, true));

            $res = Http::withOptions(['verify' => false])->withHeaders([
                'Authorization' => $authToken,
                'digest' => "$digest",
                'Digest-Method'=> 'HS256',
                'Timestamp' => "$timestamp"
            ])->post('https://apigw.selcommobile.com/v1/checkout/create-order-minimal', [
                "order_id" => $transid,
                "amount" => $amount,
                "vendor" => env('SELCOM_API_VENDOR'),
                "currency" => "TZS",
                "merchant_remarks" => "",
                "buyer_name"=>'masenu',
                "buyer_phone" => "255713497596",
                "buyer_email"=> "john@example.com",
                "buyer_name"=> "John Joh",
                "buyer_phone"=> "255xxxxxxxxx",
                "redirect_url"=>"aHR0cHM6Ly9leGFtcGxlLmNvbS8=",
                "cancel_url"=>"aHR0cHM6Ly9leGFtcGxlLmNvbS8=",
                "webhook"=>"aHR0cHM6Ly9leGFtcGxlLmNvbS8=",

                "no_of_items"=>  3
            ]);


            $push = Http::withOptions(['verify'=>false])->withHeaders([
                'Authorization' => $authToken,
                'digest' => "$digest",
                'Digest-Method'=> 'HS256',
                'Timestamp' => "$timestamp"
            ])->post('https://apigw.selcommobile.com/v1/checkout/wallet-payment',[
                "transid"=> $transid,
                "order_id"=>$transid,
                "msisdn"=>$request->phone,
            ]);



          //  $client = new Client($baseUrl, $api_key, $api_secret);

            $resp = json_decode($res);

            $pu = json_decode($push);


            return $resp;



            if($resp->result == "SUCCESS"){

                MobilePayment::where('order_id', $transid)->delete();

                $mobile_payment = new MobilePayment;

                $mobile_payment->order_id = $transid;
                $mobile_payment->currency = "TZS";
                $mobile_payment->amount = $amount;
                $mobile_payment->paid_amount = "0.00";
                $mobile_payment->remain_amount = $amount;
                $mobile_payment->payment_status = "PENDING";
                $mobile_payment->payment_token = $resp->data[0]['payment_token'];
                $mobile_payment->reference = $resp->reference;
                $mobile_payment->qr = "$resp->qr";
                $mobile_payment->status = "PENDING";

                $mobile_payment->save();//save payment
            }

                //save transaction details for reference
                $trax = DB::table('transactions')->where('id', $request->input("id"))->get();
                //insertedDataLog($trax);

            if($request->input("type") == "endorsement"){

                   return response()->json([], 200);

            }else{

                $updatetrans = DB::update("UPDATE transactions SET payment_mode=? WHERE id=?", [3, $request->input("id")]);


                    $mobile = MobilePayment::where('order_id', $transid)->first();
                    return response()->json($mobile,200);

            }


            //}catch(\Exception $ex){

                  // return response()->json('Error', 500);

           // }


    }


    public function bank(Request $request){

        try{


            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
            ]);

            $image = $request->file('file');
            $newname = time().rand(10,100) . '.' . $image->extension();
            $image->move('vehicle/', $newname);

            BankPayments::where('order_id', $request->input('id'))->delete();

            $bankpayment = new BankPayments();
            $bankpayment->order_id=$request->input("id");
            $bankpayment->amount=$request->input("premium");
            $bankpayment->paid_amount=0.00;
            $bankpayment->payment_date=$request->input("paymentdate");
            $bankpayment->remain_amount=$request->input("premium");
            $bankpayment->bank_name=$request->input("bank");
            $bankpayment->reference_number=$request->input("chequenumber");
            $bankpayment->currency=$request->input("currency");
            $bankpayment->receipt=$newname;
            $bankpayment->status="Pending";

            $bankpayment->save();

            $issued_by = Transaction::findOrFail($request->input("id"));
            $insured_name = $issued_by->customer->full_name;

            //sendNotification("255719696023", "Quotation with Bank payment is created successful it need payment approved to request cover TIRA, Insured name $insured_name", "Quotation with Bank payment is created successful it need payment approved to request cover TIRA, Insured name $insured_name");

            if($request->input("type") == "endorsement"){
                return redirect()->back()->with("success", "Payment successful added");
            }else{

                $updatetrans = DB::update("UPDATE transactions SET payment_mode=? WHERE id=?", [2, $request->input("id")]);

                return redirect()->back()->with("success", "Payment successful added");
            }


    }catch(\Exception $e){
        return redirect()->back()->with("success", "Fail please try again,".$e->getMessage());
    }

    }






}
