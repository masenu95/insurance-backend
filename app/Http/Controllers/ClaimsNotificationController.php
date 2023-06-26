<?php

namespace App\Http\Controllers;

use App\Models\ClaimNotification;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClaimsNotificationController extends Controller
{

    public function index($id = null, $covernote = null, $transactionid = null){
        if(Auth::user()->role=="admin"){

            $regions = DB::select('SELECT * FROM regions ORDER BY name ASC');

            if($id==null && $covernote==null && $transactionid==null){
                return view("admin.claim-notification");
            }elseif($id != null && $covernote == null && $transactionid == null){

                $claimdetails = DB::table("claim_notifications")
                ->join('transactions', 'transactions.id', '=', 'claim_notifications.transaction_id')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->select('claim_notifications.*', 'transactions.covernote_reference_number', 'customers.full_name', 'transactions.insurance_type_id', 'transactions.registration_number', 'transactions.chassis_number','transactions.id as transaction_id')
                ->where("claim_notifications.id", "$id")
                ->get();

                if(count($claimdetails) > 0){
                   return view("admin.claim-notification", compact("claimdetails", "id", "regions"));
                }else{
                    return view("admin.claim-notification", "regions");
                }


            }elseif($id != null && $covernote != null && $transactionid != null){
                return view("admin.claim-notification", compact("transactionid", "covernote", "id", "regions"));
            }
            else{
                return view("admin.claim-notification", "regions");
            }

        }elseif(Auth::user()->role=="agent"){

            $regions = DB::select('SELECT * FROM regions ORDER BY name ASC');

            if($id==null && $covernote==null && $transactionid==null){
           return response()->json('success', 200,);

            }elseif($id != null && $covernote == null && $transactionid == null){

                $claimdetails = DB::table("claim_notifications")
                ->join('transactions', 'transactions.id', '=', 'claim_notifications.transaction_id')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->select('claim_notifications.*', 'transactions.covernote_reference_number','customers.full_name', 'transactions.insurance_type_id', 'transactions.registration_number', 'transactions.chassis_number', 'transactions.id as transaction_id')
                ->where("claim_notifications.id", "$id")
                ->get();

                if(count($claimdetails) > 0){

                        return response()->json($claimdetails[0],200);

                }else{

                    return response()->json( 'Error', 400);

                }

            }elseif($id != null && $covernote != null && $transactionid != null){

                return response()->json( 'Error', 400);

            }
            else{
                return response()->json( 'Error', 400);
            }

        }
    }

    public function create(Request $request){

        $regions = DB::select('SELECT * FROM regions ORDER BY name ASC');
        $datetime = $request->input('lossdate')." ".$request->input('losstime');

        $claim_notification= new ClaimNotification;

        $claim_notification->request_id = "CRN".time();
        $claim_notification->transaction_id = $request->input('id');
        $claim_notification->notification_number = "CNN".time();
        $claim_notification->form_dully_filled = $request->input('dully');
        $claim_notification->loss_date = $datetime;
        $claim_notification->report_date = Carbon::now()->subMinutes(25);
        $claim_notification->loss_nature = $request->input('lossnature');
        $claim_notification->loss_type = $request->input('losstype');
        $claim_notification->loss_location = $request->input('losslocation');
        $claim_notification->officer_name = "".Auth::user()->first_name ." ".Auth::user()->last_name."";
        $claim_notification->officer_title =Auth::user()->permission;
        $claim_notification->agent_id =Auth::user()->user_id;
        $claim_notification->user_id =Auth::user()->id;
        $claim_notification->save();

        $data = Transaction::where('id', $request->input('id'))->first();
        $nature = $request->input('lossnature');
        $names = "".Auth::user()->first_name ." ".Auth::user()->last_name."";
        $loss_location = $request->input('losslocation');
        $loss_type = $request->input('losstype');

       // sendNotificationEmailClaim($request->input('id'));

        if(Auth::user()->role=="admin"){
            if($claim_notification==true){
                $id = $claim_notification->id;
                return redirect()->route('admin.claim-notification', ["$id"]);
            }else{
                return view('admin.claim-notification', compact("covernote", "transactionid", "regions"));
            }
        }elseif(Auth::user()->role=="agent"){
            if($claim_notification==true){
                $id = $claim_notification->id;

                    return $this->index($id);

            }else{

                return response()->json( 'Error', 500);

            }
        }


    }

    public function findTransaction(Request $request){
        $covernote = $request->input("covernoterefnumber");

        $trans=Transaction::where("covernote_reference_number", "$covernote")->get();
        $transactionid="";

        if(Auth::user()->role=="admin"){
            if(count($trans) > 0){

                foreach($trans as $data){
                    $transactionid="$data->id";
                }

                return redirect()->route('admin.claim-notification', [0, "$covernote", "$transactionid"]);
            }else {
                 return redirect()->route('admin.claims');
            }
        }elseif(Auth::user()->role=="agent"){

            if(count($trans) > 0){

                foreach($trans as $data){
                    $transactionid="$data->id";
                }
                return response()->json( [
                        'covernote_reference_number' => $covernote,
                        'transaction_id' => $transactionid
                    ],200);

            }else {

                    return response()->json( 'This cover note reference number does not exist',500);

            }

        }
    }

    public function findClaim($id){

        $regions = DB::select('SELECT * FROM regions ORDER BY name ASC');

        $claimdetails = DB::table("claim_notifications")
           ->join('transactions', 'transactions.id', '=', 'claim_notifications.transaction_id')
           ->select('claim_notifications.*', 'transactions.covernote_reference_number')
           ->where("claim_notifications.id", "$id")
           ->get();

        dd($claimdetails);
        return view('admin.claim-notification', compact("claimdetails", "id", "regions"));
    }


    public function update(Request $request){

        $datetime = $request->input('lossdate')." ".$request->input('losstime');
        $reportdatetime = $request->input('reportdate')." ".$request->input('reporttime');

        if(Auth::user()->role=="admin" || Auth::user()->role=="agent"){

            $claim_notification= ClaimNotification::where('id',$request->input('id'))
            ->update([
                'form_dully_filled' => $request->input('dully'),
                'loss_date' => $datetime,
                'report_date' => $reportdatetime,
                'loss_nature' => $request->input('lossnature'),
                'loss_type' => $request->input('losstype'),
                'loss_location' => $request->input('losslocation')
            ]);


                return $this->index($request->input('id'));


        }
    }


}
