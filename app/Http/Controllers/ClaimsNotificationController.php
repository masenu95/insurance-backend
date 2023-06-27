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


    public function show($id){
        $data = ClaimNotification::find($id);

        return response()->json($data, 200);
    }

    public function create(Request $request){


        $validated =$request->validate([
           'id'=>'required',
         'dully'=>'required',
         'lossDate'=>'required',
         'lossTime'=>'required',
         'lossNature'=>'required',
         'lossType'=>'required',
         'lossLocation'=>'required',

        ]);

        $datetime = $validated['lossDate']." ".$validated['lossTime'];




        $notification = ClaimNotification::create([
            'request_id'=>"CRN".time(),
            'transaction_id'=>$validated['id'],
            'notification_number'=>"CNN".time(),
            'report_date'=>Carbon::now()->subMinutes(25),
            'form_dully_filled'=>$validated['dully'],
            'loss_date'=>$datetime,
            'loss_nature'=>$validated['lossNature'],
            'loss_type'=>$validated['lossType'],
            'loss_location'=>$validated['lossLocation'],
            'officer_name'=>"".Auth::user()->first_name ." ".Auth::user()->last_name."",
            'officer_title'=>'Agent',
        ]);




       // sendNotificationEmailClaim($request->input('id'));

        if($notification){
            return response()->json($notification, 200);

            }else{

                return response()->json( 'Error', 500);

            }



    }

    public function findTransaction(Request $request){
        $covernote = $request->input("covernoterefnumber");

        $trans=Transaction::where("covernote_reference_number", "$covernote")->first();
        return response()->json($trans, 200);


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
