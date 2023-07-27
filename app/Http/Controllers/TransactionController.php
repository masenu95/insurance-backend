<?php

namespace App\Http\Controllers;

use App\Models\InsuranceCoverage;
use App\Models\InsuranceProduct;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class TransactionController extends Controller
{
    public function index(Request $request){
        $data = Transaction::with('user')->where('total_premium_including_tax','>',5000)->with('customer')->get();



                return response()->json($data);

    }


    public function pending(Request $request){
        $data = Transaction::with('user')->where('total_premium_including_tax','>',5000)->where('status','Pending')->with('customer')->get();



                return response()->json($data);

    }


    public function success(Request $request){
        $data = Transaction::with('user')->where('total_premium_including_tax','>',5000)->where('status','Success')->with('customer')->get();



                return response()->json($data);

    }



    public function expiring(Request $request){

        $currentDate = Carbon::now();
$fiveDaysFromNow = $currentDate->copy()->addDays(5);
$nearExpiryPolicies = Transaction::where('covernote_end_date', '>', $currentDate)
->where('covernote_end_date', '<=', $fiveDaysFromNow)
->get();

// Filter expired policies
$expiredPolicies = Transaction::where('covernote_end_date', '<=', $currentDate)
->get();



                return response()->json($nearExpiryPolicies);

    }


    public function cancelled(Request $request){
        if($request->input('filter')=='filter'){

            if(Auth::user()->role=="admin"){

                      $transactions_b = DB::table('transactions')
                        ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                        ->join('regions', 'regions.id', '=', 'customers.region_id')
                        ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                        ->join('users', 'users.id', '=', 'transactions.user_id')

                        ->join('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                        ->join('insurance_coverages', 'insurance_coverages.id', '=', 'transactions.insurance_coverage_id')
                        ->where('users.role', 'admin')
                        ->where('transactions.is_deleted',0)
                        ->where('transactions.status', '=','Success')->where('transactions.endorsement_type', '=','4')
                        ->whereDate('transactions.created_at','>=', $request->input('min'))
                        ->whereDate('transactions.created_at','<=', $request->input('max'))
                        ->where('transactions.fleet_status_entry', '!=','FLEET')
                        ->select('transactions.*', 'insurance_coverages.name as coverage_names', 'tra_dc.rctvnum', 'tra_dc.rctnum', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames',  'users.first_name', 'users.last_name')
                        ->orderBy('transactions.id', 'DESC')->get();

                      $transaction = DB::table('transactions')
                        ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                        ->join('regions', 'regions.id', '=', 'customers.region_id')
                        ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                        ->join('users', 'users.id', '=', 'transactions.user_id')
                        ->join('agents', 'agents.id', '=', 'users.user_id')
                        ->join('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                        ->join('insurance_coverages', 'insurance_coverages.id', '=', 'transactions.insurance_coverage_id')
                        ->where('users.role', 'agent')
                        ->where('transactions.is_deleted', 0)
                        ->where('transactions.status', '=','Success')->where('transactions.endorsement_type', '=','4')
                        ->whereDate('transactions.created_at','>=', $request->input('min'))
                        ->whereDate('transactions.created_at','<=', $request->input('max'))
                        ->where('transactions.fleet_status_entry', '!=','FLEET')
                        ->select('transactions.*', 'insurance_coverages.name as coverage_names', 'tra_dc.rctvnum', 'tra_dc.rctnum', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name')
                        ->orderBy('transactions.id', 'DESC')->get();

                      $transactions_c = DB::table('transactions')
                        ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                        ->join('regions', 'regions.id', '=', 'customers.region_id')
                        ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                        ->join('users', 'users.id', '=', 'transactions.user_id')
                        ->join('branches', 'branches.id', '=', 'users.user_id')
                        ->join('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                        ->join('insurance_coverages', 'insurance_coverages.id', '=', 'transactions.insurance_coverage_id')
                        ->where('users.role', 'admin')
                        ->where('transactions.is_deleted',0)
                        ->where('transactions.status', '=','Success')->where('transactions.endorsement_type', '=','4')
                        ->whereDate('transactions.created_at','>=', $request->input('min'))
                        ->whereDate('transactions.created_at','<=', $request->input('max'))
                        ->where('transactions.fleet_status_entry', '=','FLEET')
                        ->select('transactions.*', 'insurance_coverages.name as coverage_names', 'tra_dc.rctvnum', 'tra_dc.rctnum', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'users.first_name', 'users.last_name')
                        ->orderBy('transactions.id', 'DESC')->groupBy('transactions.fleet_id_entry')->get();

                      $transactions_d = DB::table('transactions')
                        ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                        ->join('regions', 'regions.id', '=', 'customers.region_id')
                        ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                        ->join('users', 'users.id', '=', 'transactions.user_id')
                        ->join('agents', 'agents.id', '=', 'users.user_id')
                        ->join('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                        ->join('insurance_coverages', 'insurance_coverages.id', '=', 'transactions.insurance_coverage_id')
                        ->where('users.role', 'agent')
                        ->where('transactions.is_deleted',0)
                        ->where('transactions.status', '=','Success')->where('transactions.endorsement_type', '=','4')
                        ->whereDate('transactions.created_at','>=', $request->input('min'))
                        ->whereDate('transactions.created_at','<=', $request->input('max'))
                        ->where('transactions.fleet_status_entry', '=','FLEET')
                        ->select('transactions.*', 'insurance_coverages.name as coverage_names', 'tra_dc.rctvnum', 'tra_dc.rctnum', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name')
                        ->orderBy('transactions.id', 'DESC')->groupBy('transactions.fleet_id_entry')->get();

                      $trans_a=collect($transactions_b)->merge($transaction);
                      $trans_b=collect($transactions_c)->merge($transactions_d);
                      $transactions=collect($trans_a)->merge($trans_b);

                      return view('admin.cancelled-transaction', compact('transactions'));

            }elseif(Auth::user()->role=="agent"){

              $id=Auth::user()->user_id;
              $transactions = [];

            if(Auth::user()->permission=="owner"){

                $transactions_a = DB::table('transactions')
                    ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                    ->join('regions', 'regions.id', '=', 'customers.region_id')
                    ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                    ->join('users', 'users.id', '=', 'transactions.user_id')
                    ->join('agents', 'agents.id', '=', 'users.user_id')
                    ->join('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                    ->join('insurance_coverages', 'insurance_coverages.id', '=', 'transactions.insurance_coverage_id')
                    ->where('users.role', 'agent')
                    ->where('transactions.status', '=','Success')->where('transactions.endorsement_type', '=','4')
                    ->where('transactions.is_deleted',0)
                    ->where('agents.id', $id)
                    ->whereDate('transactions.created_at','>=', $request->input('min'))
                    ->whereDate('transactions.created_at','<=', $request->input('max'))
                    ->where('transactions.fleet_status_entry', '!=','FLEET')
                    ->select('transactions.*', 'insurance_coverages.name as coverage_names', 'tra_dc.rctvnum', 'tra_dc.rctnum', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name')
                    ->orderBy('transactions.id', 'DESC')->get();

                $transactions_b = DB::table('transactions')
                    ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                    ->join('regions', 'regions.id', '=', 'customers.region_id')
                    ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                    ->join('users', 'users.id', '=', 'transactions.user_id')
                    ->join('agents', 'agents.id', '=', 'users.user_id')
                    ->join('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                    ->join('insurance_coverages', 'insurance_coverages.id', '=', 'transactions.insurance_coverage_id')
                    ->where('users.role', 'agent')
                    ->where('transactions.status', '=','Success')->where('transactions.endorsement_type', '=','4')
                    ->where('transactions.is_deleted',0)
                    ->where('agents.id', $id)
                    ->whereDate('transactions.created_at','>=', $request->input('min'))
                    ->whereDate('transactions.created_at','<=', $request->input('max'))
                    ->where('transactions.fleet_status_entry', '=','FLEET')
                    ->select('transactions.*', 'insurance_coverages.name as coverage_names', 'tra_dc.rctvnum', 'tra_dc.rctnum', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name')
                    ->orderBy('transactions.id', 'DESC')->groupBy('transactions.fleet_id_entry')->get();

                $transactions=collect($transactions_a)->merge($transactions_b);

            }elseif(Auth::user()->permission=="user"){

                $transactions_a = DB::table('transactions')
                    ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                    ->join('regions', 'regions.id', '=', 'customers.region_id')
                    ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                    ->join('users', 'users.id', '=', 'transactions.user_id')
                    ->join('agents', 'agents.id', '=', 'users.user_id')
                    ->join('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                    ->join('insurance_coverages', 'insurance_coverages.id', '=', 'transactions.insurance_coverage_id')
                    ->where('users.role', 'agent')
                    ->where('transactions.status', '=','Success')->where('transactions.endorsement_type', '=','4')
                    ->where('transactions.is_deleted',0)
                    ->where('agents.id', $id)
                    ->where('users.id', Auth::user()->id)
                    ->whereDate('transactions.created_at','>=', $request->input('min'))
                    ->whereDate('transactions.created_at','<=', $request->input('max'))
                    ->where('transactions.fleet_status_entry', '!=','FLEET')
                    ->select('transactions.*', 'insurance_coverages.name as coverage_names', 'tra_dc.rctvnum', 'tra_dc.rctnum', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name')
                    ->orderBy('transactions.id', 'DESC')->get();

                $transactions_b = DB::table('transactions')
                    ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                    ->join('regions', 'regions.id', '=', 'customers.region_id')
                    ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                    ->join('users', 'users.id', '=', 'transactions.user_id')
                    ->join('agents', 'agents.id', '=', 'users.user_id')
                    ->join('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                    ->join('insurance_coverages', 'insurance_coverages.id', '=', 'transactions.insurance_coverage_id')
                    ->where('users.role', 'agent')
                    ->where('transactions.status', '=','Success')->where('transactions.endorsement_type', '=','4')
                    ->where('transactions.is_deleted',0)
                    ->where('agents.id', $id)
                    ->where('users.id', Auth::user()->id)
                    ->whereDate('transactions.created_at','>=', $request->input('min'))
                    ->whereDate('transactions.created_at','<=', $request->input('max'))
                    ->where('transactions.fleet_status_entry', '=','FLEET')
                    ->select('transactions.*', 'insurance_coverages.name as coverage_names', 'tra_dc.rctvnum', 'tra_dc.rctnum', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name')
                    ->orderBy('transactions.id', 'DESC')->groupBy('transactions.fleet_id_entry')->get();

                $transactions=collect($transactions_a)->merge($transactions_b);
            }


                return response()->json($transactions);

            }

            }else{
            if(Auth::user()->role=="admin"){

                    $transactions_b = DB::table('transactions')
                        ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                        ->join('regions', 'regions.id', '=', 'customers.region_id')
                        ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                        ->join('users', 'users.id', '=', 'transactions.user_id')
                        ->join('branches', 'branches.id', '=', 'users.user_id')
                        ->join('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                        ->join('insurance_coverages', 'insurance_coverages.id', '=', 'transactions.insurance_coverage_id')
                        ->where('users.role', 'admin')
                        ->where('transactions.status', '=','Success')->where('transactions.endorsement_type', '=','4')
                        ->where('transactions.is_deleted',0)
                        ->select('transactions.*', 'insurance_coverages.name as coverage_names', 'tra_dc.rctvnum', 'tra_dc.rctnum', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'branches.name AS baname', 'users.first_name', 'users.last_name')
                        ->orderBy('transactions.id', 'DESC')
                        ->limit(200)
                        ->get();

                    $transaction = DB::table('transactions')
                        ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                        ->join('regions', 'regions.id', '=', 'customers.region_id')
                        ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                        ->join('users', 'users.id', '=', 'transactions.user_id')
                        ->join('agents', 'agents.id', '=', 'users.user_id')
                        ->join('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                        ->join('insurance_coverages', 'insurance_coverages.id', '=', 'transactions.insurance_coverage_id')
                        ->where('users.role', 'agent')
                        ->where('transactions.status', '=','Success')->where('transactions.endorsement_type', '=','4')
                        ->where('transactions.is_deleted',0)
                        ->select('transactions.*', 'insurance_coverages.name as coverage_names', 'tra_dc.rctvnum', 'tra_dc.rctnum', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name')
                        ->orderBy('transactions.id', 'DESC')
                        ->limit(200)
                        ->get();

                      $transactions=collect($transactions_b)->merge($transaction);
                      return view('admin.cancelled-transaction', compact('transactions'));

            }elseif(Auth::user()->role=="agent"){

              $id=Auth::user()->user_id;
              $transactions = [];

            if(Auth::user()->permission=="owner"){

                $transactions = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->join('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                ->join('insurance_coverages', 'insurance_coverages.id', '=', 'transactions.insurance_coverage_id')
                ->where('users.role', 'agent')
                ->where('transactions.status', '=','Success')->where('transactions.endorsement_type', '=','4')
                ->where('transactions.is_deleted',0)
                ->where('agents.id', $id)
                ->select('transactions.*', 'insurance_coverages.name as coverage_names', 'tra_dc.rctvnum', 'tra_dc.rctnum', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name')
                ->orderBy('transactions.id', 'DESC')->get();

            }elseif(Auth::user()->permission=="user"){

                $transactions = DB::table('transactions')
                    ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                    ->join('regions', 'regions.id', '=', 'customers.region_id')
                    ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                    ->join('users', 'users.id', '=', 'transactions.user_id')
                    ->join('agents', 'agents.id', '=', 'users.user_id')
                    ->join('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                    ->join('insurance_coverages', 'insurance_coverages.id', '=', 'transactions.insurance_coverage_id')
                    ->where('users.role', 'agent')
                    ->where('transactions.status', '=','Success')->where('transactions.endorsement_type', '=','4')
                    ->where('transactions.is_deleted',0)
                    ->where('agents.id', $id)
                    ->where('users.id', Auth::user()->id)
                    ->select('transactions.*', 'insurance_coverages.name as coverage_names', 'tra_dc.rctvnum', 'tra_dc.rctnum', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name')
                    ->orderBy('transactions.id', 'DESC')->get();

            }

            if(apiReq()){
                return response()->json($transactions);
            } else {
                return view('agent.cancelled-transaction', compact('transactions'));
            }
            }
            }
    }

    public function riskNote(Request $request){
        if($request->input('filter')=='filter'){

            if(Auth::user()->role=="admin"){
                if(Auth::user()->user_id == 3){

                      $transactions_b = DB::table('transactions')
                      ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                      ->join('regions', 'regions.id', '=', 'customers.region_id')
                      ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                      ->join('users', 'users.id', '=', 'transactions.user_id')
                      ->join('branches', 'branches.id', '=', 'users.user_id')
                      ->where('users.role', 'admin')
                      ->where('transactions.is_deleted',0)
                      ->where('transactions.status', '=','Success')
                      ->whereDate('transactions.created_at','>=', $request->input('min'))
                      ->whereDate('transactions.created_at','<=', $request->input('max'))
                      ->where('transactions.fleet_status_entry', '!=','FLEET')
                      ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'branches.name AS baname', 'users.first_name', 'users.last_name')
                      ->orderBy('transactions.id', 'DESC')->get();

                      $transaction = DB::table('transactions')
                      ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                      ->join('regions', 'regions.id', '=', 'customers.region_id')
                      ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                      ->join('users', 'users.id', '=', 'transactions.user_id')
                      ->join('agents', 'agents.id', '=', 'users.user_id')
                      ->where('users.role', 'agent')
                      ->where('transactions.is_deleted',0)
                      ->where('transactions.status', '=','Success')
                      ->whereDate('transactions.created_at','>=', $request->input('min'))
                      ->whereDate('transactions.created_at','<=', $request->input('max'))
                      ->where('transactions.fleet_status_entry', '!=','FLEET')
                      ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name')
                      ->orderBy('transactions.id', 'DESC')->get();
//
                      $transactions_c = DB::table('transactions')
                      ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                      ->join('regions', 'regions.id', '=', 'customers.region_id')
                      ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                      ->join('users', 'users.id', '=', 'transactions.user_id')
                      ->join('branches', 'branches.id', '=', 'users.user_id')
                      ->where('users.role', 'admin')
                      ->where('transactions.is_deleted',0)
                      ->where('transactions.status', '=','Success')
                      ->whereDate('transactions.created_at','>=', $request->input('min'))
                      ->whereDate('transactions.created_at','<=', $request->input('max'))
                      ->where('transactions.fleet_status_entry', '=','FLEET')
                      ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'branches.name AS baname', 'users.first_name', 'users.last_name', DB::raw('sum(transactions.total_premium_excluding_tax) as fleet_premium'), DB::raw('sum(transactions.total_premium_excluding_tax) as fleet_premium'))
                      ->orderBy('transactions.id', 'DESC')->groupBy('transactions.fleet_id_entry')->get();

                      $transactions_d = DB::table('transactions')
                      ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                      ->join('regions', 'regions.id', '=', 'customers.region_id')
                      ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                      ->join('users', 'users.id', '=', 'transactions.user_id')
                      ->join('agents', 'agents.id', '=', 'users.user_id')
                      ->where('users.role', 'agent')
                      ->where('transactions.is_deleted',0)
                      ->where('transactions.status', '=','Success')
                      ->whereDate('transactions.created_at','>=', $request->input('min'))
                      ->whereDate('transactions.created_at','<=', $request->input('max'))
                      ->where('transactions.fleet_status_entry', '=','FLEET')
                      ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name', DB::raw('sum(transactions.total_premium_excluding_tax) as fleet_premium'), DB::raw('sum(transactions.total_premium_excluding_tax) as fleet_premium'))
                      ->orderBy('transactions.id', 'DESC')->groupBy('transactions.fleet_id_entry')->get();

                      $trans_a=collect($transactions_b)->merge($transaction);
                      $trans_b=collect($transactions_c)->merge($transactions_d);
                      $transactions=collect($trans_a)->merge($trans_b);

                      return view('admin.risk-note', compact('transactions'));

                }elseif(Auth::user()->user_id != 3){

                      $branchid = Auth::user()->user_id;

                      $transactions_b = DB::table('transactions')
                      ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                      ->join('regions', 'regions.id', '=', 'customers.region_id')
                      ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                      ->join('users', 'users.id', '=', 'transactions.user_id')
                      ->join('branches', 'branches.id', '=', 'users.user_id')
                      ->where('users.role', 'admin')
                      ->where('transactions.status', '=','Success')

                      ->where('transactions.is_deleted',0)
                      ->whereDate('transactions.created_at','>=', $request->input('min'))
                      ->whereDate('transactions.created_at','<=', $request->input('max'))
                      ->where('transactions.fleet_status_entry', '!=','FLEET')
                      ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'branches.name AS baname', 'users.first_name', 'users.last_name')
                      ->orderBy('transactions.id', 'DESC')->get();

                      $transaction = DB::table('transactions')
                      ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                      ->join('regions', 'regions.id', '=', 'customers.region_id')
                      ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                      ->join('users', 'users.id', '=', 'transactions.user_id')
                      ->join('agents', 'agents.id', '=', 'users.user_id')
                      ->where('users.role', 'agent')
                      ->where('transactions.status', '=','Success')
                      ->where('transactions.is_deleted',0)
                      ->where('users.branch_id', $branchid)
                      ->whereDate('transactions.created_at','>=', $request->input('min'))
                      ->whereDate('transactions.created_at','<=', $request->input('max'))
                      ->where('transactions.fleet_status_entry', '!=','FLEET')
                      ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name')
                      ->orderBy('transactions.id', 'DESC')->get();

                      $transactions_c = DB::table('transactions')
                      ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                      ->join('regions', 'regions.id', '=', 'customers.region_id')
                      ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                      ->join('users', 'users.id', '=', 'transactions.user_id')
                      ->join('branches', 'branches.id', '=', 'users.user_id')
                      ->where('users.role', 'admin')
                      ->where('transactions.status', '=','Success')

                      ->where('transactions.is_deleted',0)
                      ->whereDate('transactions.created_at','>=', $request->input('min'))
                      ->whereDate('transactions.created_at','<=', $request->input('max'))
                      ->where('transactions.fleet_status_entry', '=','FLEET')
                      ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'branches.name AS baname', 'users.first_name', 'users.last_name', DB::raw('sum(transactions.total_premium_excluding_tax) as fleet_premium'), DB::raw('sum(transactions.total_premium_excluding_tax) as fleet_premium'))
                      ->orderBy('transactions.id', 'DESC')->groupBy('transactions.fleet_id_entry')->get();

                      $transactions_d = DB::table('transactions')
                      ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                      ->join('regions', 'regions.id', '=', 'customers.region_id')
                      ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                      ->join('users', 'users.id', '=', 'transactions.user_id')
                      ->join('agents', 'agents.id', '=', 'users.user_id')
                      ->where('users.role', 'agent')
                      ->where('transactions.status', '=','Success')
                      ->where('transactions.is_deleted',0)
                      ->where('users.branch_id', $branchid)
                      ->whereDate('transactions.created_at','>=', $request->input('min'))
                      ->whereDate('transactions.created_at','<=', $request->input('max'))
                      ->where('transactions.fleet_status_entry', '=','FLEET')
                      ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name', DB::raw('sum(transactions.total_premium_excluding_tax) as fleet_premium'), DB::raw('sum(transactions.total_premium_excluding_tax) as fleet_premium'))
                      ->orderBy('transactions.id', 'DESC')->groupBy('transactions.fleet_id_entry')->get();

                      $trans_a=collect($transactions_b)->merge($transaction);
                      $trans_b=collect($transactions_c)->merge($transactions_d);
                      $transactions=collect($trans_b)->merge($trans_a);

                      return view('admin.risk-note', compact('transactions'));

                }
            }elseif(Auth::user()->role=="agent"){

              $id=Auth::user()->user_id;
              $transactions = [];

            if(Auth::user()->permission=="owner"){

                $trans_a = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->where('users.role', 'agent')
                ->where('transactions.status', '=','Success')
                ->where('transactions.is_deleted',0)
                ->where('agents.id', $id)
                ->whereDate('transactions.created_at','>=', $request->input('min'))
                ->whereDate('transactions.created_at','<=', $request->input('max'))
                ->where('transactions.fleet_status_entry', '!=','FLEET')
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name')
                ->orderBy('transactions.id', 'DESC')->get();

                $trans_b = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->where('users.role', 'agent')
                ->where('transactions.status', '=','Success')
                ->where('transactions.is_deleted',0)
                ->where('agents.id', $id)
                ->whereDate('transactions.created_at','>=', $request->input('min'))
                ->whereDate('transactions.created_at','<=', $request->input('max'))
                ->where('transactions.fleet_status_entry', '=','FLEET')
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name', DB::raw('sum(transactions.total_premium_excluding_tax) as fleet_premium'), DB::raw('sum(transactions.total_premium_excluding_tax) as fleet_premium'))
                ->orderBy('transactions.id', 'DESC')->groupBy('transactions.fleet_id_entry')->get();

                $transactions=collect($trans_b)->merge($trans_a);

            }elseif(Auth::user()->permission=="user"){

                $trans_a = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->where('users.role', 'agent')
                ->where('transactions.status', '=','Success')
                ->where('transactions.is_deleted',0)
                ->where('agents.id', $id)
                ->where('users.id', Auth::user()->id)
                ->whereDate('transactions.created_at','>=', $request->input('min'))
                ->whereDate('transactions.created_at','<=', $request->input('max'))
                ->where('transactions.fleet_status_entry', '!=','FLEET')
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name')
                ->orderBy('transactions.id', 'DESC')->get();

                $trans_b = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->where('users.role', 'agent')
                ->where('transactions.status', '=','Success')
                ->where('transactions.is_deleted',0)
                ->where('agents.id', $id)
                ->where('users.id', Auth::user()->id)
                ->whereDate('transactions.created_at','>=', $request->input('min'))
                ->whereDate('transactions.created_at','<=', $request->input('max'))
                ->where('transactions.fleet_status_entry', '=','FLEET')
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name', DB::raw('sum(transactions.total_premium_excluding_tax) as fleet_premium'), DB::raw('sum(transactions.total_premium_excluding_tax) as fleet_premium'))
                ->orderBy('transactions.id', 'DESC')->groupBy('transactions.fleet_id_entry')->get();

                $transactions=collect($trans_b)->merge($trans_a);

            }

            if(apiReq()){
                return response()->json($transactions);
            } else {
              return view('agent.risk-note', compact('transactions'));
            }
            }

            }else{
            if(Auth::user()->role=="admin"){

                if(Auth::user()->user_id == 3){

                    $transactions_b = DB::table('transactions')
                    ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                    ->join('regions', 'regions.id', '=', 'customers.region_id')
                    ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                    ->join('users', 'users.id', '=', 'transactions.user_id')
                    ->join('branches', 'branches.id', '=', 'users.user_id')
                    ->where('users.role', 'admin')
                    ->where('transactions.status', '=','Success')
                    ->where('transactions.is_deleted',0)
                    ->whereDate('transactions.created_at', Carbon::now()->today())
                    ->where('transactions.fleet_status_entry', '!=','FLEET')
                    ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'branches.name AS baname', 'users.first_name', 'users.last_name')
                    ->orderBy('transactions.id', 'DESC')
                    ->limit(5000)
                    ->get();

                    $transaction = DB::table('transactions')
                    ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                    ->join('regions', 'regions.id', '=', 'customers.region_id')
                    ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                    ->join('users', 'users.id', '=', 'transactions.user_id')
                    ->join('agents', 'agents.id', '=', 'users.user_id')
                    ->where('users.role', 'agent')
                    ->where('transactions.status', '=','Success')
                    ->where('transactions.is_deleted',0)
                    ->whereDate('transactions.created_at', Carbon::now()->today())
                    ->where('transactions.fleet_status_entry', '!=','FLEET')
                    ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name')
                    ->orderBy('transactions.id', 'DESC')
                    ->limit(5000)
                    ->get();

                    $transactions_c = DB::table('transactions')
                    ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                    ->join('regions', 'regions.id', '=', 'customers.region_id')
                    ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                    ->join('users', 'users.id', '=', 'transactions.user_id')
                    ->join('branches', 'branches.id', '=', 'users.user_id')
                    ->where('users.role', 'admin')
                    ->where('transactions.status', '=','Success')
                    ->where('transactions.is_deleted',0)
                    ->whereDate('transactions.created_at', Carbon::now()->today())
                    ->where('transactions.fleet_status_entry', '=','FLEET')
                    ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'branches.name AS baname', 'users.first_name', 'users.last_name', DB::raw('sum(transactions.total_premium_excluding_tax) as fleet_premium'), DB::raw('sum(transactions.total_premium_excluding_tax) as fleet_premium'))
                    ->orderBy('transactions.id', 'DESC')
                    ->groupBy('transactions.fleet_id_entry')
                    ->limit(5000)
                    ->get();

                    $transactions_d = DB::table('transactions')
                    ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                    ->join('regions', 'regions.id', '=', 'customers.region_id')
                    ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                    ->join('users', 'users.id', '=', 'transactions.user_id')
                    ->join('agents', 'agents.id', '=', 'users.user_id')
                    ->where('users.role', 'agent')
                    ->where('transactions.status', '=','Success')
                    ->where('transactions.is_deleted',0)
                    ->whereDate('transactions.created_at', Carbon::now()->today())
                    ->where('transactions.fleet_status_entry', '=','FLEET')
                    ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name', DB::raw('sum(transactions.total_premium_excluding_tax) as fleet_premium'), DB::raw('sum(transactions.total_premium_excluding_tax) as fleet_premium'))
                    ->orderBy('transactions.id', 'DESC')
                    ->groupBy('transactions.fleet_id_entry')
                    ->limit(5000)
                    ->get();

                      $trans_a=collect($transactions_b)->merge($transaction);
                      $trans_b=collect($transactions_c)->merge($transactions_d);
                      $transactions=collect($trans_a)->merge($trans_b);

                      return view('admin.risk-note', compact('transactions'));

              }elseif(Auth::user()->user_id != 3){

                      $branchid = Auth::user()->user_id;

                      $transactions_b = DB::table('transactions')
                      ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                      ->join('regions', 'regions.id', '=', 'customers.region_id')
                      ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                      ->join('users', 'users.id', '=', 'transactions.user_id')
                      ->join('branches', 'branches.id', '=', 'users.user_id')
                      ->where('users.role', 'admin')
                      ->where('transactions.status', '=','Success')

                      ->where('transactions.is_deleted',0)
                      ->whereDate('transactions.created_at', Carbon::now()->today())
                      ->where('transactions.fleet_status_entry', '!=','FLEET')
                      ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'branches.name AS baname', 'users.first_name', 'users.last_name')
                      ->orderBy('transactions.id', 'DESC')
                      ->limit(5000)
                      ->get();

                      $transaction = DB::table('transactions')
                      ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                      ->join('regions', 'regions.id', '=', 'customers.region_id')
                      ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                      ->join('users', 'users.id', '=', 'transactions.user_id')
                      ->join('agents', 'agents.id', '=', 'users.user_id')
                      ->where('users.role', 'agent')
                      ->where('transactions.status', '=','Success')
                      ->where('users.branch_id', $branchid)
                      ->where('transactions.is_deleted',0)
                      ->whereDate('transactions.created_at', Carbon::now()->today())
                      ->where('transactions.fleet_status_entry', '!=','FLEET')
                      ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name')
                      ->orderBy('transactions.id', 'DESC')
                      ->limit(5000)
                      ->get();

                      $transactions_c = DB::table('transactions')
                      ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                      ->join('regions', 'regions.id', '=', 'customers.region_id')
                      ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                      ->join('users', 'users.id', '=', 'transactions.user_id')
                      ->join('branches', 'branches.id', '=', 'users.user_id')
                      ->where('users.role', 'admin')
                      ->where('transactions.status', '=','Success')

                      ->where('transactions.is_deleted',0)
                      ->whereDate('transactions.created_at', Carbon::now()->today())
                      ->where('transactions.fleet_status_entry', '=','FLEET')
                      ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'branches.name AS baname', 'users.first_name', 'users.last_name', DB::raw('sum(transactions.total_premium_excluding_tax) as fleet_premium'))
                      ->orderBy('transactions.id', 'DESC')
                      ->groupBy('transactions.fleet_id_entry')
                      ->limit(5000)
                      ->get();

                      $transactions_d = DB::table('transactions')
                      ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                      ->join('regions', 'regions.id', '=', 'customers.region_id')
                      ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                      ->join('users', 'users.id', '=', 'transactions.user_id')
                      ->join('agents', 'agents.id', '=', 'users.user_id')
                      ->where('users.role', 'agent')
                      ->where('transactions.status', '=','Success')
                      ->where('users.branch_id', $branchid)
                      ->where('transactions.is_deleted',0)
                      ->whereDate('transactions.created_at', Carbon::now()->today())
                      ->where('transactions.fleet_status_entry', '=','FLEET')
                      ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name', DB::raw('sum(transactions.total_premium_excluding_tax) as fleet_premium'))
                      ->orderBy('transactions.id', 'DESC')
                      ->groupBy('transactions.fleet_id_entry')
                      ->limit(5000)
                      ->get();

                      $trans_a=collect($transactions_b)->merge($transaction);
                      $trans_b=collect($transactions_c)->merge($transactions_d);
                      $transactions=collect($trans_a)->merge($trans_b);

                      return view('admin.risk-note', compact('transactions'));

              }
            }elseif(Auth::user()->role=="agent"){

              $id=Auth::user()->user_id;
              $transactions = [];

            if(Auth::user()->permission=="owner"){

                $transaction_a = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->where('users.role', 'agent')
                ->where('transactions.status', '=','Success')
                ->where('transactions.is_deleted',0)
                ->where('agents.id', $id)
                ->whereDate('transactions.created_at', Carbon::now()->today())
                ->where('transactions.fleet_status_entry', '!=','FLEET')
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name')
                ->orderBy('transactions.id', 'DESC')->get();

                $transaction_b = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->where('users.role', 'agent')
                ->where('transactions.status', '=','Success')
                ->where('transactions.is_deleted',0)
                ->where('agents.id', $id)
                ->whereDate('transactions.created_at', Carbon::now()->today())
                ->where('transactions.fleet_status_entry', '=','FLEET')
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name', DB::raw('sum(transactions.total_premium_excluding_tax) as fleet_premium'))
                ->orderBy('transactions.id', 'DESC')->groupBy('transactions.fleet_id_entry')->get();

                $transactions=collect($transaction_a)->merge($transaction_b);

            }elseif(Auth::user()->permission=="user"){

                $transaction_a = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->where('users.role', 'agent')
                ->where('transactions.status', '=','Success')
                ->where('transactions.is_deleted',0)
                ->where('agents.id', $id)
                ->where('users.id', Auth::user()->id)
                ->whereDate('transactions.created_at', Carbon::now()->today())
                ->where('transactions.fleet_status_entry', '!=','FLEET')
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name')
                ->orderBy('transactions.id', 'DESC')->get();

                $transaction_b = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->where('users.role', 'agent')
                ->where('transactions.status', '=','Success')
                ->where('transactions.is_deleted',0)
                ->where('agents.id', $id)
                ->where('users.id', Auth::user()->id)
                ->whereDate('transactions.created_at', Carbon::now()->today())
                ->where('transactions.fleet_status_entry', '=','FLEET')
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname', 'users.first_name', 'users.last_name', DB::raw('sum(transactions.total_premium_excluding_tax) as fleet_premium'))
                ->orderBy('transactions.id', 'DESC')->groupBy('transactions.fleet_id_entry')->get();

                $transactions=collect($transaction_a)->merge($transaction_b);

            }

            if(apiReq()){
                return response()->json($transactions);
            } else {
                return view('agent.risk-note', compact('transactions'));
            }
            }
            }
    }

    public function todayTrans(){
        if(Auth::user()->role=="admin"){
            if(Auth::user()->user_id == 3){
                $transactions_b = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('branches', 'branches.id', '=', 'users.user_id')
                ->where('users.role', 'admin')
                ->where('transactions.is_deleted',0)
                ->whereDate('transactions.created_at', Carbon::now()->today())
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'branches.name AS baname')
                ->orderBy('transactions.id', 'DESC')
                ->limit(5000)
                ->get();

                $transaction = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->whereDate('transactions.created_at', Carbon::now()->today())
                ->where('users.role', 'agent')
                ->where('transactions.is_deleted',0)
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname')
                ->orderBy('transactions.id', 'DESC')
                ->limit(5000)
                ->get();

                  $transactions=collect($transactions_b)->merge($transaction);
                  return view('admin.today-transaction', compact('transactions'));

          }elseif(Auth::user()->user_id != 3){

                  $branchid = Auth::user()->user_id;

                  $transactions_b = DB::table('transactions')
                  ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                  ->join('regions', 'regions.id', '=', 'customers.region_id')
                  ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                  ->join('users', 'users.id', '=', 'transactions.user_id')
                  ->join('branches', 'branches.id', '=', 'users.user_id')
                  ->where('users.role', 'admin')

                  ->whereDate('transactions.created_at', Carbon::now()->today())
                  ->where('transactions.is_deleted',0)
                  ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'branches.name AS baname')
                  ->orderBy('transactions.id', 'DESC')
                  ->limit(5000)
                  ->get();

                  $transaction = DB::table('transactions')
                  ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                  ->join('regions', 'regions.id', '=', 'customers.region_id')
                  ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                  ->join('users', 'users.id', '=', 'transactions.user_id')
                  ->join('agents', 'agents.id', '=', 'users.user_id')
                  ->where('users.role', 'agent')
                  ->where('users.branch_id', $branchid)
                  ->whereDate('transactions.created_at', Carbon::now()->today())
                  ->where('transactions.is_deleted',0)
                  ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname')
                  ->orderBy('transactions.id', 'DESC')
                  ->limit(5000)
                  ->get();

                  $transactions=collect($transactions_b)->merge($transaction);
                  return view('admin.today-transaction', compact('transactions'));
            }
        }
    }
    public function todaySuccess(){
        if(Auth::user()->role=="admin"){
            if(Auth::user()->user_id == 3){
                $transactions_b = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('branches', 'branches.id', '=', 'users.user_id')
                ->where('users.role', 'admin')
                ->where('transactions.status', '=','Success')
                ->where('transactions.is_deleted',0)
                ->whereDate('transactions.created_at', Carbon::now()->today())
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'branches.name AS baname')
                ->orderBy('transactions.id', 'DESC')
                ->limit(5000)
                ->get();

                $transaction = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->where('users.role', 'agent')
                ->where('transactions.status', '=','Success')
                ->where('transactions.is_deleted',0)
                ->whereDate('transactions.created_at', Carbon::now()->today())
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname')
                ->orderBy('transactions.id', 'DESC')
                ->limit(5000)
                ->get();

                  $transactions=collect($transactions_b)->merge($transaction);
                  return view('admin.today-success', compact('transactions'));

          }elseif(Auth::user()->user_id != 3){

                  $branchid = Auth::user()->user_id;

                  $transactions_b = DB::table('transactions')
                  ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                  ->join('regions', 'regions.id', '=', 'customers.region_id')
                  ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                  ->join('users', 'users.id', '=', 'transactions.user_id')
                  ->join('branches', 'branches.id', '=', 'users.user_id')
                  ->where('users.role', 'admin')
                  ->where('transactions.status', '=','Success')

                  ->whereDate('transactions.created_at', Carbon::now()->today())
                  ->where('transactions.is_deleted',0)
                  ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'branches.name AS baname')
                  ->orderBy('transactions.id', 'DESC')
                  ->limit(5000)
                  ->get();

                  $transaction = DB::table('transactions')
                  ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                  ->join('regions', 'regions.id', '=', 'customers.region_id')
                  ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                  ->join('users', 'users.id', '=', 'transactions.user_id')
                  ->join('agents', 'agents.id', '=', 'users.user_id')
                  ->where('users.role', 'agent')
                  ->where('transactions.status', '=','Success')
                  ->whereDate('transactions.created_at', Carbon::now()->today())
                  ->where('transactions.is_deleted',0)
                  ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname')
                  ->orderBy('transactions.id', 'DESC')
                  ->limit(5000)
                  ->get();

                  $transactions=collect($transactions_b)->merge($transaction);
                  return view('admin.today-success', compact('transactions'));
            }
        }
    }

    public function transactionInfo($id){

        $transaction = Transaction::where('id',$id)->first();
        $regions = DB::select('SELECT * FROM regions ORDER BY name ASC');

        $addons = DB::table('addons')->where('transaction_id', $id)->get();



        $customerid="";
        $paymentmode=0;
        $imagereference="";
        $product_types_id=0;
        $types_id=0;
        $coverage_type = "";
        $products = [];
        $user =0;
        $roles = "";
        $fleetids = '';
        $cash_id = 0;

        $trans = $transaction;
            $customerid=$trans->customer_id;
            $paymentmode=$trans->paymentmodes;
            $paymentmode=$trans->paymentmodes;
            $imagereference=$trans->image_reference;
            $product_types_id=$trans->insurance_product_id;
            $coverage_type = $trans->coverage_type;
            $user = $trans->user_id;
            $types_id = $trans->insurance_type_id;
            $fleetids = $trans->fleet_id;


        $ins_types = DB::table('insurance_products')->where('insurance_type_id', $types_id)->where('is_deleted', 0)->get();
        $ins_coverage = DB::table('insurance_coverages')->where('insurance_product_id', $product_types_id)->where('is_deleted', 0)->get();

        if($coverage_type != "Comprehensive"){ //check if coverage is comprehensive
            $products = InsuranceCoverage::where('insurance_product_id', $product_types_id)
            ->get();
        }

        $customer = DB::table('customers')
        ->join('regions', 'regions.id', '=', 'customers.region_id')
        ->join('districts', 'districts.id', '=', 'customers.district_id')
        ->where('customers.id', $customerid)
        ->select('customers.*', 'regions.name as rname', 'districts.name as dname', 'regions.id as rid', 'districts.id as did')
        ->get();

        if($imagereference != "" || $imagereference != null){
        $documents = DB::select("SELECT id, transaction_id, name, image_type, created_at, updated_at FROM motor_galleries WHERE transaction_id=?", [$imagereference]);
        }else{
        $documents = DB::select("SELECT id, transaction_id, name, image_type, created_at, updated_at FROM motor_galleries WHERE transaction_id=?", [$id]);
        }

      /*  $endupgrade = DB::table('endorsement_upgrades')
                           ->join('insurance_coverages', 'insurance_coverages.id', '=', 'endorsement_upgrades.to_coverage_id')
                           ->where('endorsement_upgrades.transaction_id', $id)
                           ->select('endorsement_upgrades.*', 'insurance_coverages.name as coverage_name')
                           ->get();

        $endchangerequest = DB::table('endorsement_details')
                            ->where('transaction_id', $id)
                            ->get();*/



        if(Auth::user()->role=="admin"){

            $mobilestaff = [];

            if($paymentmode==3 || $paymentmode=="3"){
                $mobilepayment=DB::select("SELECT * FROM mobile_payments WHERE order_id=? or order_id=?",["$id", "$fleetids"]);

                $mpid = 0;
                foreach($mobilepayment as $datam)
                {
                    $mpid = $datam->approved_by;
                }

                $mobilestaff = User::where('id', $mpid)->first();

                return response()->json([
                    'transaction' => $transaction ? $transaction : null,
                    'customer' => count($customer) ? $customer[0] : null,
                    'documents' => $documents,
                    'mobile_payment' => count($mobilepayment) ? $mobilepayment[0] : null,
                    'regions' => $regions,
                    'products' => $products,

                    'addons' => $addons
                ]);

            }else if($paymentmode==2 || $paymentmode=="2"){

                $bankpayment=DB::select("SELECT * FROM bank_payments WHERE order_id=? or order_id=?",["$id", "$fleetids"]);

                $payment_approved_by = [];
                foreach($bankpayment as $data_bankpayment){
                    $payment_approved_by = User::where('id', $data_bankpayment->user_id)->get();
                    break;
                }

                return response()->json([
                    'transaction' => $transaction ? $transaction : null,
                    'customer' => count($customer) ? $customer[0] : null,
                    'documents' => $documents,
                    'bank_payment' => count($bankpayment) ? $bankpayment[0] : null,
                    'regions' => $regions,
                    'products' => $products,

                    'addons' => $addons
                ]);
            }else{
                return response()->json([
                    'transaction' => $transaction ? $transaction : null,
                    'customer' => count($customer) ? $customer[0] : null,
                    'documents' => $documents,
                    'regions' => $regions,
                    'products' => $products,

                    'addons' => $addons
                ]);
            }
        }elseif(Auth::user()->role=="agent"){

            if($paymentmode==3 || $paymentmode=="3"){
                $mobilepayment=DB::select("SELECT * FROM mobile_payments WHERE order_id=? or order_id = ?",["$id", "$fleetids"]);

                    return response()->json([
                        'transaction' => $transaction ? $transaction : null,
                        'customer' => count($customer) ? $customer[0] : null,
                        'documents' => $documents,
                        'mobile_payment' => count($mobilepayment) ? $mobilepayment[0] : null,
                        'regions' => $regions,
                        'products' => $products,

                        'addons' => $addons
                    ]);


            }else if($paymentmode==2 || $paymentmode=="2"){

                $bankpayment=DB::select("SELECT * FROM bank_payments WHERE order_id=? or order_id=?",["$id", "$fleetids"]);

                $payment_approved_by = [];
                foreach($bankpayment as $data_bankpayment){
                    $payment_approved_by = User::where('id', $data_bankpayment->user_id)->get();
                    break;
                }


                  return response()->json([
                        'transaction' => $transaction ? $transaction : null,
                        'customer' => count($customer) ? $customer[0] : null,
                        'documents' => $documents,
                        'bank_payment' => count($bankpayment) ? $bankpayment[0] : null,
                        'regions' => $regions,
                        'products' => $products,

                        'addons' => $addons
                    ]);


            }else{

                       return response()->json([
                        'transaction' => $transaction ? $transaction : null,
                        'customer' => count($customer) ? $customer[0] : null,
                        'documents' => $documents,
                        'regions' => $regions,
                        'products' => $products,

                        'addons' => $addons
                    ]);
            }
        }
    }




    public function monthTransaction(Request $request){
        $current = date('m');
        $branchid = Auth::user()->user_id;

        if($request->input('filter') == 'filter'){
          if(Auth::user()->role=="admin"){
            if(Auth::user()->user_id == 3){

                $transactions_b = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('branches', 'branches.id', '=', 'users.user_id')
                ->where('users.role', 'admin')
                ->where('transactions.is_deleted',0)
                ->whereDate('transactions.created_at','>=', $request->input('min'))
                ->whereDate('transactions.created_at','<=', $request->input('max'))
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'branches.name AS baname')
                ->orderBy('transactions.id', 'DESC')->get();

                $transaction = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->where('users.role', 'agent')
                ->where('transactions.is_deleted',0)
                ->whereDate('transactions.created_at','>=', $request->input('min'))
                ->whereDate('transactions.created_at','<=', $request->input('max'))
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname')
                ->orderBy('transactions.id', 'DESC')->get();

                $transactions=collect($transactions_b)->merge($transaction);
                return view('admin.month-transaction', compact('transactions'));

            }elseif(Auth::user()->user_id != 3){

                $branchid = Auth::user()->user_id;

                $transactions_b = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('branches', 'branches.id', '=', 'users.user_id')
                ->where('users.role', 'admin')

                ->where('transactions.is_deleted',0)
                ->whereDate('transactions.created_at','>=', $request->input('min'))
                ->whereDate('transactions.created_at','<=', $request->input('max'))
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'branches.name AS baname')
                ->orderBy('transactions.id', 'DESC')->get();

                $transaction = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->where('users.role', 'agent')
                ->where('transactions.is_deleted',0)
                ->where('users.branch_id', $branchid)
                ->whereDate('transactions.created_at','>=', $request->input('min'))
                ->whereDate('transactions.created_at','<=', $request->input('max'))
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname')
                ->orderBy('transactions.id', 'DESC')->get();

                $transactions=collect($transactions_b)->merge($transaction);
                return view('admin.month-transaction', compact('transactions'));
            }

        }elseif(Auth::user()->role=="agent"){

            $transactions = [];
            if(Auth::user()->permission=="owner"){

                $transactions = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->where('users.role', 'agent')
                ->where('transactions.is_deleted',0)
                ->where('agents.id', $branchid)
                ->whereYear('transactions.created_at', Carbon::now()->year)
                ->whereMonth('transactions.created_at', $current)
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname')
                ->orderBy('transactions.id', 'DESC')->get();

            }elseif(Auth::user()->permission=="user"){

                $transactions = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->where('users.role', 'agent')
                ->where('transactions.is_deleted',0)
                ->where('agents.id', $branchid)
                ->whereYear('transactions.created_at', Carbon::now()->year)
                ->whereMonth('transactions.created_at', $current)
                ->where('users.id', Auth::user()->id)
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname')
                ->orderBy('transactions.id', 'DESC')->get();
            }

            if(!apiReq()){
                return view('agent.month-transaction', compact('transactions'));
            }else{
                return response()->json($transactions);
            }
        }
       }else{

        if(Auth::user()->role=="admin"){
            if(Auth::user()->user_id == 3){

                $transactions_b = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('branches', 'branches.id', '=', 'users.user_id')
                ->where('users.role', 'admin')
                ->where('transactions.is_deleted',0)
                ->whereYear('transactions.created_at', Carbon::now()->year)
                ->whereMonth('transactions.created_at', $current)
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'branches.name AS baname')
                ->orderBy('transactions.id', 'DESC')->get();

                $transaction = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->where('users.role', 'agent')
                ->where('transactions.is_deleted',0)
                ->whereYear('transactions.created_at', Carbon::now()->year)
                ->whereMonth('transactions.created_at', $current)
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname')
                ->orderBy('transactions.id', 'DESC')->get();

                $transactions=collect($transactions_b)->merge($transaction);
                return view('admin.month-transaction', compact('transactions'));

            }elseif(Auth::user()->user_id != 3){

                $branchid = Auth::user()->user_id;

                $transactions_b = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('branches', 'branches.id', '=', 'users.user_id')
                ->where('users.role', 'admin')

                ->where('transactions.is_deleted',0)
                ->whereYear('transactions.created_at', Carbon::now()->year)
                ->whereMonth('transactions.created_at', $current)
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'branches.name AS baname')
                ->orderBy('transactions.id', 'DESC')->get();

                $transaction = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->where('users.role', 'agent')
                ->where('transactions.is_deleted',0)
                ->where('users.branch_id', $branchid)
                ->whereYear('transactions.created_at', Carbon::now()->year)
                ->whereMonth('transactions.created_at', $current)
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname')
                ->orderBy('transactions.id', 'DESC')->get();

                $transactions=collect($transactions_b)->merge($transaction);
                return view('admin.month-transaction', compact('transactions'));
            }

        }elseif(Auth::user()->role=="agent"){

            $transactions = [];

            if(Auth::user()->permission=="owner"){

                $transactions = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->where('users.role', 'agent')
                ->where('transactions.is_deleted',0)
                ->where('agents.id', $branchid)
                ->whereYear('transactions.created_at', Carbon::now()->year)
                ->whereMonth('transactions.created_at', $current)
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname')
                ->orderBy('transactions.id', 'DESC')->get();

            }elseif(Auth::user()->permission=="user"){

                $transactions = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('regions', 'regions.id', '=', 'customers.region_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->where('users.role', 'agent')
                ->where('transactions.is_deleted',0)
                ->where('agents.id', $branchid)
                ->whereYear('transactions.created_at', Carbon::now()->year)
                ->whereMonth('transactions.created_at', $current)
                ->where('users.id', Auth::user()->id)
                ->select('transactions.*', 'customers.full_name AS customername', 'regions.name AS region', 'insurance_types.name as typenames', 'agents.name AS baname')
                ->orderBy('transactions.id', 'DESC')->get();
            }


            if(!apiReq()){
                return view('agent.month-transaction', compact('transactions'));
            }else{
                return response()->json($transactions);
            }
        }

       }
    }


    public function updateDuration(Request $request){

        $startdate = $request->input('startdate');
        $starttime = $request->input('starttime');
        $duration = $request->input('duration');
        $covernotedesc = $request->input('covernotedesc');
        $operativeclause = $request->input('operativeclause');
        $coveringdetails = $request->input('coveringdetails');
        $firstloss = $request->input('firstloss');

        $start_dates="$startdate"." ".$starttime;
        $end_dates="$startdate"." 23:59:59";

        $date_s=date_create($start_dates);
        $date_e=date_create($end_dates);

        $enddates=$startdate="";

        //get start and end date
        if($duration >= 12){
            $add_minutes_start_date = date_add($date_s,date_interval_create_from_date_string("5 minutes"));
            $end_d = date_add($date_e,date_interval_create_from_date_string("$duration months"));
            $end_date = date_sub($end_d,date_interval_create_from_date_string("1 days"));
            $enddates = date_format($end_date, 'Y-m-d H:i:s');
            $startdate = date_format($add_minutes_start_date, 'Y-m-d H:i:s');
        }else{
            $get_days = $duration*30;
            $add_minutes_start_date = date_add($date_s,date_interval_create_from_date_string("5 minutes"));
            $end_d = date_add($date_e,date_interval_create_from_date_string("$get_days days"));
            $enddates = date_format($end_d, 'Y-m-d H:i:s');
            $startdate = date_format($add_minutes_start_date, 'Y-m-d H:i:s');
        }

        $update = Transaction::where('id', $request->input("transactionid"))
            ->update([
                'covernote_start_date' => $startdate,
                'covernote_end_date' => $enddates,
                'covernote_desc' => $covernotedesc,
                'operative_clause' => $operativeclause,
                'covering_details' => $coveringdetails,
                'covernote_number' => rand(100,10000).time(),
                'first_loss' => $firstloss
            ]);

        if(!apiReq()){
            return redirect()->back()->with('success', 'Successful update start and end date');
        } else {
            return response()->json(null, 'Success');
    }
    }

    public function sendTiraRe($requestid){
        $response=Http::post("api/covernote/motor/$requestid");
        return $response->json();

    }

    public function approveTran(Request $request){

        if(Auth::user()->role=="admin"){

            $updatetrans = DB::update("UPDATE transactions SET is_approved=?, approval_desc=? WHERE id=?", [$request->input("status"), $request->input("desc"), $request->input("id")]);
            return redirect()->back()->with("success", "Transaction successful saved");

        }
    }

    public function incompleteMove(Request $request){

        if(Auth::user()->permission == 'root' || Auth::user()->permission == 'manager'){
            $upd = DB::update("UPDATE transactions SET status='Incomplete' WHERE id=?", [$request->input("id")]);
            if($upd == true){
                return redirect()->back()->with("success", "Transaction successful moved to Incomplete");
            }else{
                return redirect()->back()->with("message-error", "Transaction move to Incomplete fail");
            }
        }
        else{
            return redirect()->back()->with("message-error", "Transaction move to Incomplete fail");
        }
    }

    public function markAsRecorded(Request $request){
        if(Auth::user()->permission == "manager" || Auth::user()->permission == "root" || Auth::user()->permission == "finance" || Auth::user()->permission == "commercial"){
            $upd = DB::update("UPDATE transactions SET recorded=1 WHERE id=?", [$request->input("id")]);
            if($upd == true){
                return redirect()->back()->with("success", "Transaction successful marked as recorded");
            }else{
                return redirect()->back()->with("message-error", "Fail to mark transaction as recorded to Meticulous System");
            }
        }
        else{
            return redirect()->back()->with("message-error", "Fail to mark transaction as recorded to Meticulous System");
        }
    }

    public function resetPayments(Request $request){
        if(Auth::check()){

            $to = "RESET:".time().":".$request->input("id");

            $upd_a = DB::update("UPDATE transactions SET payment_mode=null WHERE id=?", [$request->input("id")]);
            $upd_b = DB::update("UPDATE bank_payments SET order_id='$to' WHERE order_id=?", [$request->input("id")]);
            $upd_c = DB::update("UPDATE mobile_payments SET order_id='$to' WHERE order_id=?", [$request->input("id")]);

            if($upd_a == true){
                return redirect()->back()->with("success", "Payment of this transaction reseted successful");
            }else{
                return redirect()->back()->with("message-error", "Payment reset fail");
            }
        }
        else{
            return redirect()->back()->with("message-error", "Payment reset fail");
        }
    }

    public function cancelTransaction(Request $request){
        if(Auth::user()->permission == "root" || Auth::user()->permission == "manager" || Auth::user()->permission == "commercial" || Auth::user()->permission == "finance" ){

            $startdate = date('Y-m-d');
            $startdate="$startdate"." 23:58:30";

            $tra = DB::table('transactions')->where('id', $request->input('id'))->get();

            $days=$before=$premium=$product=$duration=0;

            foreach($tra as $it){

                $re_date = Carbon::parse($it->covernote_start_date);
                $re_now = Carbon::now();
                $days = $re_date->diffInDays($re_now);
                $before = $it->total_premium_including_tax;
                $product = $it->insurance_type_id;
                $duration = $it->durations;

            }
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

            $msm = "Days :".$days." Premium: ".$premium." before: $before";

            $update = Transaction::where('id', $request->input('id'))
            ->update([
                'covernote_start_date' => $startdate,
                "covernote_type" => 3,
                'covernote_number' => time().rand(1000,100),
                "endorsement_type" => 4,
                "endorsement_reason" => $request->input('reason'),
                "acknowledgement_status_code" => null,
                "acknowledgement_status_desc" => null,
                "response_status_code" => null,
                "response_status_desc" => null,
                "endorsement_premium_earned" => $premium
            ]);

            return redirect()->back()->with('success', "Send Cover Cancellation to TIRA $msm");
        }
    }


    public function clearTransactionError($id){

        if(Auth::user()->role == "admin" || Auth::user()->role == "agent"){

            $clear = Transaction::where('id', $id)
            ->update([
                "request_id" => "TRM".rand(10,100).time(),
                "covernote_number" => rand(1,10).time().date('dHsi'),
                "acknowledgement_status_code" => null,
                "acknowledgement_status_desc" => null,
                "response_status_code" => null,
                "response_status_desc" => null
            ]);

            return $clear;

        }
    }


    public function deleteTrans($id){
        if(Auth::check()){
            $count = DB::table('tra_dc')->where('trans_id', $id)->count();
            if($count > 0){
                return "Sorry you can not delete this quotation at this time is already have TRA Invoice";
            }else{
                Transaction::where('id', $id)->update([
                    'is_deleted' => 1
                ]);
                return "Quotation is successfuly removed, this quotation will remain in trash for 15 days after that will be deleted permanently, to return the quotation with 15 days send request to tech team.";
            }
        }else{
            return "Please login to delete quotation";
        }


    }


    public function updateEndDate(Request $request){
        if(Auth::user()->permission == "root"){

            $end_dates= $request->input('enddateonly')." 23:59:59";
            $date_e=date_create($end_dates);
            $startdate = date_format($date_e, 'Y-m-d H:i:s');

            $excludingtax = $request->input('premium');
            $tax_amount = ($excludingtax*18)/100;
            $includingtax = $excludingtax+$tax_amount;

            $transaction = Transaction::where('id', $request->input('id'))->update([
                "covernote_end_date" => $startdate,
                'total_premium_excluding_tax' => $excludingtax,
                'premium_excluding_tax_equivalent' => $excludingtax,
                'premium_before_discount' => $excludingtax,
                'premium_after_discount' => $excludingtax,
                'premium_excluding_tax' => $excludingtax,
                'tax_amount' => $tax_amount,
                'total_premium_including_tax' => $includingtax,
                'premium_including_tax' => $includingtax,
            ]);

            return redirect()->back()->with("success", "End Date and Premium successful updated");

        }else{

            return redirect()->back()->with("success", "Please login to update");

        }
    }



    public function changeCoverage(Request $request){
        $tax_code = $tax_rate = 0;
        //get tax code and rate
        $tax_code ="VAT-MAINLAND"; $tax_rate = 0.18;

        $trans = Transaction::where('id', $request->input('id'))->first();
        $users = User::where('id', $trans->user_id)->first();

        //from user login
        $commisionrate = 0;
        $commisionpaid = 0;

        if($users->role == 'agent'){

            $commisionrate = 0.125;
            $commisionpaid = $commisionrate * $request->input("premiumexcl");

        }else{
            $commisionrate = 0;
            $commisionpaid = 0;
        }

        $coverage = InsuranceCoverage::where('id', $request->input('insurancecover'))->first();

        $fleet_entry = Transaction::where('id', $request->input('id'))->update([

            'commission_paid' => $commisionpaid,
            'commission_rate' => $commisionrate,
            'total_premium_excluding_tax' => $request->input("premiumexcl"),
            'total_premium_including_tax' => $request->input("premiuminclvat"),
            'sum_insured' => $request->input("insurancesum"),
            'sum_insured_equivalent' => $request->input("insurancesum"),
            'premium_before_discount' => $request->input("premiumexcl"),
            'premium_after_discount' => $request->input("premiumexcl"),
            'premium_excluding_tax_equivalent' => $request->input("premiumexcl"),
            'premium_including_tax' => $request->input("premiuminclvat"),
            'tax_code' => "$tax_code",
            'tax_rate' => "$tax_rate",
            'tax_amount' => $request->input("premiumvat"),
            'premium_excluding_tax' => $request->input("premiumexcl"),
            'insurance_coverage_id' => $request->input('insurancecover'),
            'insurance_product_id' => $request->input('insurancepro'),
            'premium_rate' => $coverage->rate,
        ]);

        if($fleet_entry == true){
            return redirect()->back()->with('success', 'Successful update insurance product coverage');
        }else{
            return redirect()->back()->with('error', "Please fill valid details");
        }

    }



    public function updateCoveragePremium(Request $request){

        $product = InsuranceProduct::where('id', $request->input('insurancepro'))->first();

        $commisionrate = 0;
        $commisionpaid = 0;

        if(Auth::user()->role == 'agent'){
            switch($product->insurance_type_id )
            {
                case 1:
                    $commisionrate = 0.175;
                    $commisionpaid = $commisionrate * $request->input("premiumexcl");
                    break;
                case 3:
                    $commisionrate = 0.175;
                    $commisionpaid = $commisionrate * $request->input("premiumexcl");
                    break;
                case 4:
                    $commisionrate = 0.20;
                    $commisionpaid = $commisionrate * $request->input("premiumexcl");
                    break;
                case 5:
                    $commisionrate = 0.25;
                    $commisionpaid = $commisionrate * $request->input("premiumexcl");
                    break;
                case 6:
                    $commisionrate = 0.175;
                    $commisionpaid = $commisionrate * $request->input("premiumexcl");
                    break;
                default:
                    $commisionrate = 0.175;
                    $commisionpaid = $commisionrate * $request->input("premiumexcl");
                    break;
            }
        }

        $fleet_entry = Transaction::where('id', $request->input('id'))->update([

            'commission_paid' => $commisionpaid,
            'commission_rate' => $commisionrate,
            'total_premium_excluding_tax' => $request->input("premiumexcl"),
            'total_premium_including_tax' => $request->input("premiuminclvat"),
            'sum_insured' => $request->input("insurancesum"),
            'sum_insured_equivalent' => $request->input("insurancesum"),
            'premium_before_discount' => $request->input("premiumexcl"),
            'premium_after_discount' => $request->input("premiumexcl"),
            'premium_excluding_tax_equivalent' => $request->input("premiumexcl"),
            'premium_including_tax' => $request->input("premiuminclvat"),
            'tax_amount' => $request->input("premiumvat"),
            'premium_excluding_tax' => $request->input("premiumexcl"),
            'insurance_coverage_id' => $request->input('insurancecover'),
            'insurance_product_id' => $request->input('insurancepro'),
            'adjusted_premium' => $request->input('selectedcoverpremiumoverridedis') == 0 ? null : $request->input('selectedcoverpremiumoverridedis'),
            'override_rate' => $request->input('selectedcoverrateoverridedis') == 0 ? null : $request->input('selectedcoverrateoverridedis'),
            'premium_rate' => $request->input('selectedcoverrate')
        ]);

        if($fleet_entry == true){
            return redirect()->back()->with('success', 'Successful update insurance product coverage');
        }else{
            return redirect()->back()->with('error', "Please fill valid details");
        }

    }



    public function reminderCovernote(Request $request)
    {

        $reminder = Transaction::where('id', $request->input('id'))->update([
            'staff_notes' => $request->input('remindernote'),
            'reminder_date' => $request->input('reminder'),
            'reminder_status' => 1
        ]);

        if($reminder == true){
            return redirect()->back()->with('success', 'Successful set covernote reminder');
        }else{
            return redirect()->back()->with('error', "fail try again please");
        }
    }


}
