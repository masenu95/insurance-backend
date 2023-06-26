<?php

namespace App\Http\Controllers;

use App\Models\BankPayments;
use App\Models\MobilePayment;
use App\Models\Transaction;
use App\View\Components\Transaction\BankPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class FleetPaymentController extends Controller
{
    public function fleetPaymentBank(Request $request){
        try{

            if(Auth::check()){
                $request->validate([
                    'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
                ]);

                BankPayments::where('order_id', $request->input("fleetids"))->delete();
        
                $image = $request->file('file');
                $newname = time().rand(10,100) . '.' . $image->extension();
                $image->move('vehicle/', $newname);

                $bankpayment = new BankPayments();
                $bankpayment->order_id=$request->input("fleetids");
                $bankpayment->amount=$request->input("fullamount");
                $bankpayment->paid_amount=0.00;
                $bankpayment->payment_date=$request->input("paymentdate");
                $bankpayment->remain_amount=$request->input("fullamount");
                $bankpayment->bank_name=$request->input("bank");
                $bankpayment->reference_number=$request->input("chequenumber");
                $bankpayment->currency=$request->input("currency");
                $bankpayment->receipt=$newname;
                $bankpayment->status="Pending";
    
                $bankpayment->save();

                if($bankpayment == true){
                    Transaction::where('fleet_id_entry', $request->input("fleetids"))->update(["payment_mode" => 2]);
                    $request->session()->put('payment_fleet', 2);

                    $issued_by = Auth::user()->first_name ." ".Auth::user()->last_name;

                    sendNotification("255719696023", "Fleet with Bank payment is created successful it need payment approved to request cover TIRA, payment issued by $issued_by", "Fleet with Bank payment is created successful it need payment approved to request cover TIRA, payment issued by $issued_by");

                    if($request->input('featured') == "outside"){
                        if(Auth::user()->role == "admin"){
                            return redirect()->route('admin.fleet-status');
                        }elseif(Auth::user()->role == "agent"){
                            return redirect()->route('agent.fleet-status');
                        }
                    }elseif($request->input('featured') == "inside"){
                        return redirect()->back()->with("success", "Bank Payment successful added");
                    }else{
                        return redirect()->back()->with("success", "Fail please try again with valid details");
                    }
                }else{
                    return redirect()->back()->with("success", "Fail please try again with valid details");
                }
                   
            }
        }catch(\Exception $e){
            return redirect()->back()->with("success", "Fail please try again");
        }
    }


    public function fleetPaymentCash(Request $request){
        try{

            if(Auth::check()){
               if(Auth::user()->permission == 'root'){
                    Transaction::where('fleet_id_entry', $request->input("fleetids"))->update(["payment_mode" => 1, 'cash_id'=>Auth::user()->id]);
                    $request->session()->put('payment_fleet', 1);

                    if($request->input('featured') == "outside"){
                        if(Auth::user()->role == "admin"){
                            return redirect()->route('admin.fleet-status');
                        }elseif(Auth::user()->role == "agent"){
                            return redirect()->route('agent.fleet-status');
                        }
                    }elseif($request->input('featured') == "inside"){
                        return redirect()->back()->with("success", "Cash Payment successful added");
                    }else{
                        return redirect()->back()->with("success", "Cash Payment successful added");
                    }
                }
                else
                {
                    return redirect()->back()->with("success", "Fail, please contact the right person to approve or choose other payments");
                }
            }else{
                return redirect()->back()->with("success", "Fail please try again with valid details");
            }
                   
        }catch(\Exception $e){
            return redirect()->back()->with("success", "Fail please try again");
        }
    }



    public function fleetPaymentDirect(Request $request){
        try{
            $paymentmode = $request->input("paymentmode");
            $mobilenumber = trim($request->input("mnumber"));

            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
            ]);

            MobilePayment::where('order_id', $request->input("fleetids"))->delete();

            $image = $request->file('file');
            $newname = time().rand(10,100) . '.' . $image->extension();
            $image->move('vehicle/', $newname);

            $mobile_payment = new MobilePayment;

            $mobile_payment->order_id = $request->input("fleetids");
            $mobile_payment->currency = "TZS";
            $mobile_payment->amount = $request->input("fullamount");
            $mobile_payment->paid_amount = "0.00";
            $mobile_payment->remain_amount = $request->input("fullamount");
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

            $issued_by = Auth::user()->first_name ." ".Auth::user()->last_name;

            Transaction::where('fleet_id_entry', $request->input("fleetids"))->update(["payment_mode" => 3]);
            $request->session()->put('payment_fleet', 4);
            
            sendNotification("255719696023", "Fleet with Masterpass payment is created successful it need payment approved to request cover TIRA, payment issued by $issued_by", "Fleet with Masterpass payment is created successful it need payment approved to request cover TIRA, payment issued by $issued_by");

            if($request->input('featured') == "outside"){

                if(Auth::user()->role == "admin"){
                    return redirect()->route('admin.fleet-status');
                }elseif(Auth::user()->role == "agent"){
                    return redirect()->route('agent.fleet-status');
                }
            }elseif($request->input('featured') == "inside"){
                return redirect()->back()->with("success", "Direct Payment successful added");
            }else{
                return redirect()->back()->with("success", "Direct Payment successful added");
            }
        }catch(\Exception $e){
            return redirect()->back()->with('success', "fail upload payment details");
        }
    }



    public function fleetPaymentMobile(Request $request){
        if(Auth::check()){
            try{
            $api_key = $_ENV['SELCOM_API_KEY'];
            $api_secret = $_ENV['SELCOM_API_SECRET'];
    
            $timestamp = gmdate("Y-m-d H:i:s");

            $transid = $request->input("fleetids");
            $amount = trim($request->input("fullamount"));

            

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

            if($resp->result == "SUCCESS"){

                MobilePayment::where('order_id', $request->input("fleetids"))->delete();
                
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

                $mobile_payment->save();//save payment
            }
            
            Transaction::where('fleet_id_entry', $request->input("fleetids"))->update(["payment_mode" => 3]);
            $request->session()->put('payment_fleet', 3);

            if($request->input('featured') == "outside"){
                if(Auth::user()->role == "admin"){
                    return redirect()->route('admin.fleet-status');
                }elseif(Auth::user()->role == "agent"){
                    return redirect()->route('agent.fleet-status');
                }
            }elseif($request->input('featured') == "inside"){
                return redirect()->back()->with("success", "Mobile Payment successful added");
            }else{
                return redirect()->back()->with("success", "Mobile Payment successful added");
            }

            }catch(\Exception $ex){
                return redirect()->back()->with("error", "Something went wrong, please try again");
            }
        }
    }
}
