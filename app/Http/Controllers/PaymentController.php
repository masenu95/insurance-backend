<?php

namespace App\Http\Controllers;

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

        if(Auth::user()->role=="admin" || Auth::user()->role=="agent"){

            try{

            $api_key = $_ENV['SELCOM_API_KEY'];
            $api_secret = $_ENV['SELCOM_API_SECRET'];

            $timestamp = gmdate("Y-m-d H:i:s");

            $transid = $request->input("id");
            $amount = trim($request->input("premium"));

            $api_digest = md5($timestamp . $api_secret) . sha1(sha1($timestamp . $api_key . $api_secret, true));

            $res = Http::withOptions(['verify' => false])->withHeaders([
                'api_key' => "$api_key",
                'digest' => "$api_digest",
                'request_timestamp' => "$timestamp"
            ])->post('https://api.selcommobile.com/v1/paymentCreate', [
                "order_id" => "zdfdf$transid",
                "amount" => "$amount",
                "currency" => "TZS",
                "payer_remarks" => "",
                "merchant_remarks" => "",
                "payer_email" => "",
                "payer_phone" => ""
            ]);

            $resp = json_decode($res);

            if($resp->result == "SUCCESS"){

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

                $mobile_payment->save();//save payment
            }

                //save transaction details for reference
                $trax = DB::table('transactions')->where('id', $request->input("id"))->get();
                insertedDataLog($trax);

            if($request->input("type") == "endorsement"){
                if(!apiReq()){
                    return redirect()->back()->with("success", "Payment successful added");
                }else{
                    return apiResponse([], 'Success');
                }
            }else{

                $updatetrans = DB::update("UPDATE transactions SET payment_mode=? WHERE id=?", [3, $request->input("id")]);

                if(!apiReq()){
                    return redirect()->back()->with("success", "Payment successful added");
                }else{
                    $mobile = MobilePayment::where('order_id', $transid)->first();
                    return response()->json($mobile,200);
                }
            }


            }catch(\Exception $ex){
                if(!apiReq()){
                    return redirect()->back()->with("message-error", "Something went wrong, please try again");
                }else{
                    return apiResponse(null, 'Error', 500);
                }
            }
        }

    }


    public function bank(Request $request){

        try{

        if(Auth::user()->role=="admin" || Auth::user()->role=="agent"){
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

            sendNotification("255719696023", "Quotation with Bank payment is created successful it need payment approved to request cover TIRA, Insured name $insured_name", "Quotation with Bank payment is created successful it need payment approved to request cover TIRA, Insured name $insured_name");

            if($request->input("type") == "endorsement"){
                return redirect()->back()->with("success", "Payment successful added");
            }else{

                $updatetrans = DB::update("UPDATE transactions SET payment_mode=? WHERE id=?", [2, $request->input("id")]);

                return redirect()->back()->with("success", "Payment successful added");
            }

        }
    }catch(\Exception $e){
        return redirect()->back()->with("success", "Fail please try again,".$e->getMessage());
    }

    }


    public function fleetBankPayment(Request $request)
    {
        $updatetrans = DB::update("UPDATE transactions SET payment_mode=? WHERE id=?", [2, $request->input('id')]);

        BankPayments::where('order_id', $request->input('id'))->delete();

        $bankpayment = new BankPayments;
        $bankpayment->order_id=$request->input('id');
        $bankpayment->amount=$request->input('amount');
        $bankpayment->paid_amount="0.00";
        $bankpayment->payment_date=$request->input("paymentdate");
        $bankpayment->remain_amount=$request->input('amount');
        $bankpayment->bank_name=$request->input('bank');
        $bankpayment->reference_number=$request->input('chequenumber');
        $bankpayment->status="Pending";

        $bankpayment->save();

        if(Auth::user()->role=="admin"){
            return redirect()->route('admin.fleet-status');
        }elseif(Auth::user()->role=="agent"){
            return redirect()->route('admin.fleet-status');
        }

    }



    public function collection(Request $request){

        $receipt = DB::table('tra_dc')->orderBy('id', 'desc')->limit(1000)->get();

        if($request->has('type'))
        {
            if($request->input('type') == "bank")
            {

                $bank = DB::table('transactions as T')
                    ->join('users as U', 'U.id', '=', 'T.user_id')
                    ->join('bank_payments as B', 'B.order_id', '=', 'T.id')
                    ->join('customers as C', 'C.id', '=', 'T.customer_id')
                    ->leftJoin('users as A', 'A.id', '=', 'B.user_id')
                    ->where('T.payment_mode', 2)
                    ->whereDate('T.created_at','>=', $request->input('min'))
                    ->whereDate('T.created_at','<=', $request->input('max'))
                    ->select('T.*', 'U.first_name', 'U.last_name', 'B.id as bank_id', 'B.amount as bank_amount', 'B.paid_amount as bank_paid_amount', 'B.remain_amount as bank_remain_amount', 'B.bank_name as bank_bank_name', 'B.description as bank_desc', 'B.reference_number as bank_reference', 'B.payment_date as bank_p_date', 'B.currency as bank_currency', 'B.receipt as bank_receipt', 'B.status as bank_status','A.first_name as approved_first_name', 'A.last_name as approved_last_name', 'C.full_name', 'B.updated_at as paymentapproveddate')
                    ->orderByDesc('T.id')
                    ->get();

                $bank_count = DB::table('transactions')->where('payment_mode', 2)->whereDate('created_at','>=', $request->input('min'))->whereDate('created_at','<=', $request->input('max'))->count();
            }
            else
            {

                $bank = DB::table('transactions as T')
                ->join('users as U', 'U.id', '=', 'T.user_id')
                ->join('bank_payments as B', 'B.order_id', '=', 'T.id')
                ->join('customers as C', 'C.id', '=', 'T.customer_id')
                ->leftJoin('users as A', 'A.id', '=', 'B.user_id')
                ->where('T.payment_mode', 2)
                ->select('T.*', 'U.first_name', 'U.last_name', 'B.id as bank_id', 'B.amount as bank_amount', 'B.paid_amount as bank_paid_amount', 'B.remain_amount as bank_remain_amount', 'B.bank_name as bank_bank_name', 'B.description as bank_desc', 'B.reference_number as bank_reference', 'B.payment_date as bank_p_date', 'B.currency as bank_currency', 'B.receipt as bank_receipt', 'B.status as bank_status','A.first_name as approved_first_name', 'A.last_name as approved_last_name', 'C.full_name', 'B.updated_at as paymentapproveddate')
                ->orderByDesc('T.id')
                ->limit(500)
                ->get();

                $bank_count = DB::table('transactions')->where('payment_mode', 2)->count();
            }

            if($request->input('type') == "mobile")
            {

                $mobile = DB::table('transactions as T')
                        ->join('users as U', 'U.id', '=', 'T.user_id')
                        ->join('mobile_payments as M', 'M.order_id', '=', 'T.id')
                        ->join('customers as C', 'C.id', '=', 'T.customer_id')
                        ->where('T.payment_mode', 3)
                        ->whereDate('T.created_at','>=', $request->input('min'))
                        ->whereDate('T.created_at','<=', $request->input('max'))
                        ->select('T.*', 'U.first_name', 'U.last_name','M.id as m_id', 'M.currency as m_currency', 'M.amount as m_amount', 'M.paid_amount as m_paid_amount', 'M.remain_amount as m_remain_amount', 'M.payment_status as m_payment_status', 'M.payment_token as m_payment_token', 'M.qr as m_qr', 'M.channel as m_channel', 'M.phone as m_phone', 'M.transid as m_transid', 'M.reference as m_reference', 'M.status as m_status', 'C.full_name', 'M.receipt_uploaded')
                        ->orderByDesc('T.id')
                        ->get();

                $mobile_count = DB::table('transactions')->where('payment_mode', 3)->whereDate('created_at','>=', $request->input('min'))->whereDate('created_at','<=', $request->input('max'))->count();
            }
            else
            {
                $mobile = DB::table('transactions as T')
                ->join('users as U', 'U.id', '=', 'T.user_id')
                ->join('mobile_payments as M', 'M.order_id', '=', 'T.id')
                ->join('customers as C', 'C.id', '=', 'T.customer_id')
                ->where('T.payment_mode', 3)
                ->select('T.*', 'U.first_name', 'U.last_name','M.id as m_id', 'M.currency as m_currency', 'M.amount as m_amount', 'M.paid_amount as m_paid_amount', 'M.remain_amount as m_remain_amount', 'M.payment_status as m_payment_status', 'M.payment_token as m_payment_token', 'M.qr as m_qr', 'M.channel as m_channel', 'M.phone as m_phone', 'M.transid as m_transid', 'M.reference as m_reference', 'M.status as m_status', 'C.full_name', 'M.receipt_uploaded')
                ->orderByDesc('T.id')
                ->limit(500)
                ->get();
                $mobile_count = DB::table('transactions')->where('payment_mode', 3)->count();
            }

            if($request->input('type') == "cash")
            {
                $cash = DB::table('transactions as T')
                        ->join('users as U', 'U.id', '=', 'T.user_id')
                        ->join('customers as C', 'C.id', '=', 'T.customer_id')
                        ->where('T.payment_mode', 1)
                        ->whereDate('T.created_at','>=', $request->input('min'))
                        ->whereDate('T.created_at','<=', $request->input('max'))
                        ->select('T.*', 'U.first_name', 'U.last_name', 'C.full_name')
                        ->orderByDesc('T.id')
                        ->get();

                $cash_count = DB::table('transactions')->where('payment_mode', 1)->whereDate('created_at','>=', $request->input('min'))->whereDate('created_at','<=', $request->input('max'))->count();
            }
            else
            {
                $cash = DB::table('transactions as T')
                ->join('users as U', 'U.id', '=', 'T.user_id')
                ->join('customers as C', 'C.id', '=', 'T.customer_id')
                ->where('T.payment_mode', 1)
                ->select('T.*', 'U.first_name', 'U.last_name', 'C.full_name')
                ->orderByDesc('T.id')
                ->limit(500)
                ->get();
                $cash_count = DB::table('transactions')->where('payment_mode', 1)->count();
            }
            $type = $request->input('type');
        }
        else
        {
            $cash = DB::table('transactions as T')
                    ->join('users as U', 'U.id', '=', 'T.user_id')
                    ->join('customers as C', 'C.id', '=', 'T.customer_id')
                    ->where('T.payment_mode', 1)
                    ->select('T.*', 'U.first_name', 'U.last_name', 'C.full_name')
                    ->orderByDesc('T.id')
                    ->limit(500)
                    ->get();

            $mobile = DB::table('transactions as T')
                    ->join('users as U', 'U.id', '=', 'T.user_id')
                    ->join('mobile_payments as M', 'M.order_id', '=', 'T.id')
                    ->join('customers as C', 'C.id', '=', 'T.customer_id')
                    ->where('T.payment_mode', 3)
                    ->select('T.*', 'U.first_name', 'U.last_name','M.id as m_id', 'M.currency as m_currency', 'M.amount as m_amount', 'M.paid_amount as m_paid_amount', 'M.remain_amount as m_remain_amount', 'M.payment_status as m_payment_status', 'M.payment_token as m_payment_token', 'M.qr as m_qr', 'M.channel as m_channel', 'M.phone as m_phone', 'M.transid as m_transid', 'M.reference as m_reference', 'M.status as m_status', 'C.full_name', 'M.receipt_uploaded')
                    ->orderByDesc('T.id')
                    ->limit(500)
                    ->get();

            $bank = DB::table('transactions as T')
                    ->join('users as U', 'U.id', '=', 'T.user_id')
                    ->join('bank_payments as B', 'B.order_id', '=', 'T.id')
                    ->join('customers as C', 'C.id', '=', 'T.customer_id')
                    ->leftJoin('users as A', 'A.id', '=', 'B.user_id')
                    ->where('T.payment_mode', 2)
                    ->select('T.*', 'U.first_name', 'U.last_name', 'B.id as bank_id', 'B.amount as bank_amount', 'B.paid_amount as bank_paid_amount', 'B.remain_amount as bank_remain_amount', 'B.bank_name as bank_bank_name', 'B.description as bank_desc', 'B.reference_number as bank_reference', 'B.payment_date as bank_p_date', 'B.currency as bank_currency', 'B.receipt as bank_receipt', 'B.status as bank_status','A.first_name as approved_first_name', 'A.last_name as approved_last_name', 'C.full_name', 'B.updated_at as paymentapproveddate')
                    ->orderByDesc('T.id')
                    ->limit(500)
                    ->get();

            $type = "NAN";
            $bank_count = DB::table('transactions')->where('payment_mode', 2)->count();
            $mobile_count = DB::table('transactions')->where('payment_mode', 3)->count();
            $cash_count = DB::table('transactions')->where('payment_mode', 1)->count();
        }

        return view('admin.payment-collection', compact('bank_count','mobile_count', 'cash_count','cash','mobile','bank', 'type'));

    }

}
