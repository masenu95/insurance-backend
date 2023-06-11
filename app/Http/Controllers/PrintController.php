<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\TRADC;
use App\Models\Transaction;
use App\Models\TRARegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class PrintController extends Controller
{

    public function getTransaction($id)
    {
        return Transaction::findOrFail($id);
    }
    public function printNonMotorCover($id)
    {

        $trans = DB::select("SELECT T.id, T.request_id AS requestid, T.company_code, T.system_code, T.callback_url, T.insurer_company_code, T.tran_company_code, T.covernote_type, T.covernote_number, T.prev_covernote_reference_number, T.sales_point_code, date_format(T.covernote_start_date,'%b %d, %Y') AS startdate, date_format(T.covernote_end_date,'%b %d, %Y') AS enddate, T.covernote_desc, T.operative_clause, T.covering_details, T.payment_mode AS paymentmodes, T.currency_code, T.exchange_rate, T.total_premium_excluding_tax, T.total_premium_including_tax AS totalpremiums, T.commission_paid, T.commission_rate, T.officer_name, T.officer_title, T.endorsement_type, T.endorsement_reason, T.sum_insured AS suminsured, T.sum_insured_equivalent, T.premium_rate, T.premium_before_discount, T.premium_discount, T.discount_type, T.premium_after_discount, T.premium_excluding_tax_equivalent, T.premium_including_tax, T.tax_code, T.tax_rate, T.tax_amount, T.subject_matter_reference, T.subject_matter_desc, T.addon_reference, T.addon_desc, T.addon_amount, T.addon_premium_rate, T.premium_excluding_tax, T.is_feet, T.fleet_id, T.fleet_size, T.comprehensive_insured, T.fleet_entry, T.motor_category, T.registration_number, T.Chassis_number, T.make, T.model, T.model_number, T.body_type, T.color, T.engine_number, T.engine_capacity, T.fuel_used, T.number_Of_axles, T.axle_distance, T.Sitting_capacity, T.year_Of_manufacture, T.tare_weight, T.gross_weight, T.motor_usage, T.owner_name, T.owner_category, T.owner_address, T.covernote_reference_number, T.sticker_number, T.acknowledgement_status_code, T.acknowledgement_status_desc, T.response_status_code, T.response_status_desc, T.status AS status, T.cheque_number, T.bank, T.payment_number, T.payment_network, T.insurance_product_id, T.insurance_coverage_id, T.insurance_type_id, T.customer_id, T.branch_id, T.user_id, T.is_deleted, T.created_at, T.updated_at, C.full_name AS customername, C.phone_number as cphone, R.name AS region, I.name as insurancename, S.name as riskname, D.name AS dname, S.code AS riskcode, P.code AS pcode, T.user_id, U.role as roles, U.first_name, U.last_name, T.currency_status, T.currency_rate, T.currency_title, C.loss_payee, C.postal_address, Q.gc, H.name as branchnameuser, U.user_code, T.first_loss FROM transactions T INNER JOIN customers C ON C.id = T.customer_id INNER JOIN regions R ON R.id=C.region_id INNER JOIN districts D ON D.id=C.district_id INNER JOIN insurance_types I ON I.id=T.insurance_type_id INNER JOIN insurance_coverages S ON T.insurance_coverage_id=S.id INNER JOIN users U ON U.id=T.user_id INNER JOIN insurance_products P ON P.id=T.insurance_product_id LEFT JOIN tra_dc as Q ON Q.trans_id = T.id LEFT JOIN branches as H ON H.id = U.branch_id WHERE T.id=? ORDER BY T.request_id DESC", [$id]);

        $pdfname = time()."-".$id;
        $customername = "";

        $other = [];
        foreach($trans as $data) {
            $customername = $data->customername;
            if ($data->roles == 'agent'){
                $other = DB::table('users')
                    ->join('agents', 'agents.id', '=', 'users.user_id')
                    ->where('users.id', $data->user_id)
                    ->select('agents.*')
                    ->get();
            }elseif($data->roles == 'admin'){
                $other = DB::table('users')
                    ->join('branches', 'branches.id', '=', 'users.user_id')
                    ->where('users.id', $data->user_id)
                    ->select('branches.*')
                    ->get();
            }
        }

        $pdf = PDF::loadView('print-non-motor', compact('trans', 'other'));
        return $pdf->download("$customername.pdf");
    }

    public function printMotorCover($id)
    {

        $trans = DB::select("SELECT T.id, T.request_id AS requestid, T.company_code, T.system_code, T.callback_url, T.insurer_company_code, T.tran_company_code, T.covernote_type, T.covernote_number, T.prev_covernote_reference_number, T.sales_point_code, date_format(T.covernote_start_date,'%b %d, %Y') AS startdate, date_format(T.covernote_end_date,'%b %d, %Y') AS enddate, T.covernote_desc, T.operative_clause, T.covering_details, T.payment_mode AS paymentmodes, T.currency_code, T.exchange_rate, T.total_premium_excluding_tax, T.total_premium_including_tax AS totalpremiums, T.commission_paid, T.commission_rate, T.officer_name, T.officer_title, T.endorsement_type, T.endorsement_reason, T.sum_insured AS suminsured, T.sum_insured_equivalent, T.premium_rate, T.premium_before_discount, T.premium_discount, T.discount_type, T.premium_after_discount, T.premium_excluding_tax_equivalent, T.premium_including_tax, T.tax_code, T.tax_rate, T.tax_amount, T.subject_matter_reference, T.subject_matter_desc, T.addon_reference, T.addon_desc, T.addon_amount, T.addon_premium_rate, T.premium_excluding_tax, T.is_feet, T.fleet_id, T.fleet_size, T.comprehensive_insured, T.fleet_entry, T.motor_category, T.registration_number, T.Chassis_number, T.make, T.model, T.model_number, T.body_type, T.color, T.engine_number, T.engine_capacity, T.fuel_used, T.number_Of_axles, T.axle_distance, T.Sitting_capacity, T.year_Of_manufacture, T.tare_weight, T.gross_weight, T.motor_usage, T.owner_name, T.motor_type, T.owner_category, T.owner_address, T.covernote_reference_number, T.sticker_number, T.acknowledgement_status_code, T.acknowledgement_status_desc, T.response_status_code, T.response_status_desc, T.status AS status, T.cheque_number, T.bank, T.payment_number, T.payment_network, T.insurance_product_id, T.insurance_coverage_id, T.insurance_type_id, T.customer_id, T.branch_id, T.user_id, T.is_deleted, T.created_at, T.updated_at, C.full_name AS customername, C.phone_number as cphone, R.name AS region, I.name as insurancename, S.name as riskname, D.name AS dname, U.role as roles, S.code AS riskcode, P.code AS pcode, C.id_type, C.id_number, C.birth_date, U.first_name, U.last_name, T.currency_status, T.currency_rate, T.currency_title, C.loss_payee, C.postal_address, C.id as customerid, Q.gc, H.name as branchnameuser, U.user_code, T.first_loss, T.gps_tracking FROM transactions T INNER JOIN customers C ON C.id = T.customer_id INNER JOIN regions R ON R.id=C.region_id INNER JOIN districts D ON D.id=C.district_id INNER JOIN insurance_types I ON I.id=T.insurance_type_id INNER JOIN insurance_coverages S ON T.insurance_coverage_id=S.id INNER JOIN users U ON U.id=T.user_id INNER JOIN insurance_products P ON P.id=T.insurance_product_id LEFT JOIN tra_dc as Q ON Q.trans_id = T.id LEFT JOIN branches as H ON H.id = U.branch_id WHERE T.id=? ORDER BY T.request_id DESC", [$id]);

        $customername = "";

        $addons = DB::table('addons')->where('transaction_id', $id)->get();

        $pdfname = time()."-".$id;

        $branch_agent = [];
        $ba_id = 0;
        $bank = [];
        $mobile = [];
        foreach($trans as $data) {
            $customername = $data->customername;
            if ($data->roles == 'agent'){
                $branch_agent = DB::table('users')
                    ->join('agents', 'agents.id', '=', 'users.user_id')
                    ->where('users.id', $data->user_id)
                    ->select('agents.*')
                    ->get();
            }elseif($data->roles == 'admin'){
                $branch_agent = DB::table('users')
                    ->join('branches', 'branches.id', '=', 'users.user_id')
                    ->where('users.id', $data->user_id)
                    ->select('branches.*')
                    ->get();
            }

            if($data->paymentmodes == 2){
                $bank = DB::table('bank_payments')
                    ->where('order_id', $id)
                    ->get();

            }elseif($data->paymentmodes == 3){
                $mobile = DB::table('mobile_payments')
                ->where('order_id', $id)
                ->get();
            }
        }

        $pdf = PDF::loadView('print-motor', compact('trans', 'branch_agent', 'mobile', 'bank', 'addons'));
        return $pdf->download("$customername.pdf");
    }

    public function sendCovetNote($id = null)
    {
        return view('mail.send-cover-note', compact(''));
    }

    public function downloadCover(Request $request){
       
        $data=[];
        $agent = Agent::where('is_deleted', 0)->get();

        $filter_sales = $filter_types = 0;
        $operater_sales = $operater_types = "";
        $sales = $request->input('sales');
        $types = $request->input('types');
        $date = $request->input('dates');

        if($request->input('sales') == "0")
        {
            if($request->input('types') == "0"){

                $data_a = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('branches', 'branches.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'admin')
                ->whereDate('transactions.created_at', $request->input('dates'))
                ->select('transactions.*', 'customers.*', 'branches.name AS bname','branches.address as b_address','branches.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role')
                ->get();
    
                $data_b = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'agent')
                ->whereDate('transactions.created_at', $request->input('dates'))
                ->select('transactions.*', 'customers.*', 'agents.name AS bname','agents.cover_desc as b_address','agents.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role')
                ->get();

            }elseif($request->input('types') == "1"){

                $data_a = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('branches', 'branches.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'admin')
                ->whereDate('transactions.created_at', $request->input('dates'))
                ->where(function($query) {
                    $query->where('transactions.endorsement_type', '!=', '4')
                                ->orWhere('transactions.endorsement_type', null);
                 })
                ->select('transactions.*', 'customers.*', 'branches.name AS bname','branches.address as b_address','branches.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role')
                ->get();
    
                $data_b = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'agent')
                ->whereDate('transactions.created_at', $request->input('dates'))
                ->where(function($query) {
                    $query->where('transactions.endorsement_type', '!=', '4')
                                ->orWhere('transactions.endorsement_type', null);
                 })
                ->select('transactions.*', 'customers.*', 'agents.name AS bname','agents.cover_desc as b_address','agents.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role')
                ->get();

            }elseif($request->input('types') == "2"){

                $data_a = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('branches', 'branches.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'admin')
                ->where('transactions.endorsement_type', '4')
                ->whereDate('transactions.created_at', $request->input('dates'))
                ->select('transactions.*', 'customers.*', 'branches.name AS bname','branches.address as b_address','branches.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role')
                ->get();
    
                $data_b = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'agent')
                ->whereDate('transactions.created_at', $request->input('dates'))
                ->where('transactions.endorsement_type', '4')
                ->select('transactions.*', 'customers.*', 'agents.name AS bname','agents.cover_desc as b_address','agents.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role')
                ->get();

            }
            $data = collect($data_a)->merge($data_b);
        }
        elseif($request->input('sales') == "met")
        {
            if($request->input('types') == "0"){
                $data = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('branches', 'branches.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'admin')
                ->whereDate('transactions.created_at', $request->input('dates'))
                ->select('transactions.*', 'customers.*', 'branches.name AS bname','branches.address as b_address','branches.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role')
                ->get();
    
            }elseif($request->input('types') == "1"){
                $data = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('branches', 'branches.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'admin')
                ->whereDate('transactions.created_at', $request->input('dates'))
                ->where(function($query) {
                    $query->where('transactions.endorsement_type', '!=', '4')
                                ->orWhere('transactions.endorsement_type', null);
                 })
                ->select('transactions.*', 'customers.*', 'branches.name AS bname','branches.address as b_address','branches.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role')
                ->get();

            }elseif($request->input('types') == "2"){
                $data = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('branches', 'branches.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'admin')
                ->where('transactions.endorsement_type', '4')
                ->whereDate('transactions.created_at', $request->input('dates'))
                ->select('transactions.*', 'customers.*', 'branches.name AS bname','branches.address as b_address','branches.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role')
                ->get();

            }
        }
        elseif($request->input('sales') != "met" && $request->input('sales') != "0")
        {

            if($request->input('types') == "0"){
    
                $data = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'agent')
                ->whereDate('transactions.created_at', $request->input('dates'))
                ->where('agents.id', $request->input('sales'))
                ->select('transactions.*', 'customers.*', 'agents.name AS bname','agents.cover_desc as b_address','agents.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role')
                ->get();

            }elseif($request->input('types') == "1"){
    
                $data = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'agent')
                ->whereDate('transactions.created_at', $request->input('dates'))
                ->where('agents.id', $request->input('sales'))
                ->where(function($query) {
                    $query->where('transactions.endorsement_type', '!=', '4')
                                ->orWhere('transactions.endorsement_type', null);
                 })
                ->select('transactions.*', 'customers.*', 'agents.name AS bname','agents.cover_desc as b_address','agents.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role')
                ->get();

            }elseif($request->input('types') == "2"){
    
                $data = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'agent')
                ->whereDate('transactions.created_at', $request->input('dates'))
                ->where('agents.id', $request->input('sales'))
                ->where('transactions.endorsement_type', '4')
                ->select('transactions.*', 'customers.*', 'agents.name AS bname','agents.cover_desc as b_address','agents.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role')
                ->get();
            }
        }
       
        return view('admin.download-cover', compact('date', 'data', 'agent','types', 'sales'));
    }

    public function downloadCoverNote($date, $sales, $recorded, $types){

        $data = [];

        if($sales == "0")
        {
            if($types == "0"){

                $data_a = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('branches', 'branches.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->leftJoin('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                ->leftJoin('branches as H', 'H.id', '=', 'users.branch_id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'admin')
                ->whereDate('transactions.created_at', $date)
                ->select('transactions.*', 'customers.*', 'branches.name AS bname','branches.address as b_address','branches.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role', 'tra_dc.gc', 'users.user_code', 'H.name as branchnameuser')
                ->get();
    
                $data_b = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->leftJoin('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                ->leftJoin('branches as H', 'H.id', '=', 'users.branch_id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'agent')
                ->whereDate('transactions.created_at', $date)
                ->select('transactions.*', 'customers.*', 'agents.name AS bname','agents.cover_desc as b_address','agents.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role', 'tra_dc.gc','users.user_code', 'H.name as branchnameuser')
                ->get();

            }elseif($types == "1"){

                $data_a = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('branches', 'branches.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->leftJoin('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                ->leftJoin('branches as H', 'H.id', '=', 'users.branch_id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'admin')
                ->whereDate('transactions.created_at', $date)
                ->where(function($query) {
                    $query->where('transactions.endorsement_type', '!=', '4')
                                ->orWhere('transactions.endorsement_type', null);
                 })
                ->select('transactions.*', 'customers.*', 'branches.name AS bname','branches.address as b_address','branches.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role', 'tra_dc.gc','users.user_code', 'H.name as branchnameuser')
                ->get();
    
                $data_b = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->leftJoin('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                ->leftJoin('branches as H', 'H.id', '=', 'users.branch_id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'agent')
                ->whereDate('transactions.created_at', $date)
                ->where(function($query) {
                    $query->where('transactions.endorsement_type', '!=', '4')
                                ->orWhere('transactions.endorsement_type', null);
                 })
                ->select('transactions.*', 'customers.*', 'agents.name AS bname','agents.cover_desc as b_address','agents.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role', 'tra_dc.gc', 'users.user_code', 'H.name as branchnameuser')
                ->get();

            }elseif($types == "2"){

                $data_a = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('branches', 'branches.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->leftJoin('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                ->leftJoin('branches as H', 'H.id', '=', 'users.branch_id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'admin')
                ->where('transactions.endorsement_type', '4')
                ->whereDate('transactions.created_at', $date)
                ->select('transactions.*', 'customers.*', 'branches.name AS bname','branches.address as b_address','branches.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role', 'tra_dc.gc', 'users.user_code', 'H.name as branchnameuser')
                ->get();
    
                $data_b = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->leftJoin('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                ->leftJoin('branches as H', 'H.id', '=', 'users.branch_id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'agent')
                ->whereDate('transactions.created_at', $date)
                ->where('transactions.endorsement_type', '4')
                ->select('transactions.*', 'customers.*', 'agents.name AS bname','agents.cover_desc as b_address','agents.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role', 'tra_dc.gc','users.user_code', 'H.name as branchnameuser')
                ->get();

            }
            $data = collect($data_a)->merge($data_b);
        }
        elseif($sales == "met")
        {
            if($types == "0"){
                $data = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('branches', 'branches.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->leftJoin('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                ->leftJoin('branches as H', 'H.id', '=', 'users.branch_id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'admin')
                ->whereDate('transactions.created_at', $date)
                ->select('transactions.*', 'customers.*', 'branches.name AS bname','branches.address as b_address','branches.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role', 'tra_dc.gc','users.user_code', 'H.name as branchnameuser')
                ->get();
    
            }elseif($types == "1"){
                $data = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('branches', 'branches.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->leftJoin('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                ->leftJoin('branches as H', 'H.id', '=', 'users.branch_id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'admin')
                ->whereDate('transactions.created_at', $date)
                ->where(function($query) {
                    $query->where('transactions.endorsement_type', '!=', '4')
                                ->orWhere('transactions.endorsement_type', null);
                 })
                ->select('transactions.*', 'customers.*', 'branches.name AS bname','branches.address as b_address','branches.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role', 'tra_dc.gc','users.user_code', 'H.name as branchnameuser')
                ->get();

            }elseif($types == "2"){
                $data = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('branches', 'branches.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->leftJoin('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                ->leftJoin('branches as H', 'H.id', '=', 'users.branch_id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'admin')
                ->where('transactions.endorsement_type', '4')
                ->whereDate('transactions.created_at', $date)
                ->select('transactions.*', 'customers.*', 'branches.name AS bname','branches.address as b_address','branches.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role', 'tra_dc.gc','users.user_code', 'H.name as branchnameuser')
                ->get();

            }

    
            
        }
        elseif($sales != "met" && $sales != "0")
        {

            if($types == "0"){
    
                $data = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->leftJoin('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                ->leftJoin('branches as H', 'H.id', '=', 'users.branch_id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'agent')
                ->whereDate('transactions.created_at', $date)
                ->where('agents.id', $sales)
                ->select('transactions.*', 'customers.*', 'agents.name AS bname','agents.cover_desc as b_address','agents.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role', 'tra_dc.gc','users.user_code', 'H.name as branchnameuser')
                ->get();

            }elseif($types == "1"){
    
                $data = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->leftJoin('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                ->leftJoin('branches as H', 'H.id', '=', 'users.branch_id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'agent')
                ->whereDate('transactions.created_at', $date)
                ->where('agents.id', $sales)
                ->where(function($query) {
                    $query->where('transactions.endorsement_type', '!=', '4')
                                ->orWhere('transactions.endorsement_type', null);
                 })
                ->select('transactions.*', 'customers.*', 'agents.name AS bname','agents.cover_desc as b_address','agents.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role', 'tra_dc.gc','users.user_code', 'H.name as branchnameuser')
                ->get();

            }elseif($types == "2"){
    
                $data = DB::table('transactions')
                ->join('customers', 'customers.id', '=', 'transactions.customer_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->join('agents', 'agents.id', '=', 'users.user_id')
                ->join('insurance_types', 'insurance_types.id', '=', 'transactions.insurance_type_id')
                ->join('insurance_coverages', 'insurance_coverages.id','=', 'transactions.insurance_coverage_id')
                ->join('insurance_products', 'insurance_products.id','=', 'transactions.insurance_product_id')
                ->join('regions', 'regions.id','=', 'customers.region_id')
                ->join('districts', 'districts.id','=', 'customers.district_id')
                ->leftJoin('bank_payments', 'bank_payments.order_id', '=', 'transactions.id')
                ->leftJoin('mobile_payments', 'mobile_payments.order_id', '=', 'transactions.id')
                ->leftJoin('tra_dc', 'tra_dc.trans_id', '=', 'transactions.id')
                ->leftJoin('branches as H', 'H.id', '=', 'users.branch_id')
                ->where('transactions.status', 'success')
                ->where('users.role', 'agent')
                ->whereDate('transactions.created_at', $date)
                ->where('agents.id', $sales)
                ->where('transactions.endorsement_type', '4')
                ->select('transactions.*', 'customers.*', 'agents.name AS bname','agents.cover_desc as b_address','agents.logo as logo', 'bank_payments.bank_name as bankname', 'bank_payments.payment_date', 'insurance_types.name AS ins_type_name', 'insurance_coverages.name AS ins_coverage_name', 'mobile_payments.transid', 'mobile_payments.reference', 'mobile_payments.channel', 'mobile_payments.payment_token', 'mobile_payments.updated_at AS mobile_payment_date', 'bank_payments.order_id', 'bank_payments.reference_number', 'districts.name AS dname', 'regions.name AS rname','transactions.created_at AS transaction_created', 'users.role', 'tra_dc.gc','users.user_code', 'H.name as branchnameuser')
                ->get();
            }
        }
        

        $pdfname = count($data)."-covernote-for-date-".$date;
        
        $pdf = PDF::loadView('download', compact('data'));
        return $pdf->download("$pdfname.pdf");

    }


    public function printFleetMotorCover($id){
                $trans = DB::select("SELECT T.id, T.request_id AS requestid, T.company_code, T.system_code, T.callback_url, T.insurer_company_code, T.tran_company_code, T.covernote_type, T.covernote_number, T.prev_covernote_reference_number, T.sales_point_code, date_format(T.covernote_start_date,'%b %d, %Y') AS startdate, date_format(T.covernote_end_date,'%b %d, %Y') AS enddate, T.covernote_desc, T.operative_clause, T.covering_details, T.payment_mode AS paymentmodes, T.currency_code, T.exchange_rate, T.total_premium_excluding_tax, T.total_premium_including_tax AS totalpremiums, T.commission_paid, T.commission_rate, T.officer_name, T.officer_title, T.endorsement_type, T.endorsement_reason, T.sum_insured AS suminsured, T.sum_insured_equivalent, T.premium_rate, T.premium_before_discount, T.premium_discount, T.discount_type, T.premium_after_discount, T.premium_excluding_tax_equivalent, T.premium_including_tax, T.tax_code, T.tax_rate, T.tax_amount, T.subject_matter_reference, T.subject_matter_desc, T.addon_reference, T.addon_desc, T.addon_amount, T.addon_premium_rate, T.premium_excluding_tax, T.is_feet, T.fleet_id, T.fleet_size, T.comprehensive_insured, T.fleet_entry, T.motor_category, T.registration_number, T.Chassis_number, T.make, T.model, T.model_number, T.body_type, T.color, T.engine_number, T.engine_capacity, T.fuel_used, T.number_Of_axles, T.axle_distance, T.Sitting_capacity, T.year_Of_manufacture, T.tare_weight, T.gross_weight, T.motor_usage, T.owner_name, T.motor_type, T.owner_category, T.owner_address, T.covernote_reference_number, T.sticker_number, T.acknowledgement_status_code, T.acknowledgement_status_desc, T.response_status_code, T.response_status_desc, T.status AS status, T.cheque_number, T.bank, T.payment_number, T.payment_network, T.insurance_product_id, T.insurance_coverage_id, T.insurance_type_id, T.customer_id, T.branch_id, T.user_id, T.is_deleted, T.created_at, T.updated_at, C.full_name AS customername, C.phone_number as cphone, R.name AS region, I.name as insurancename, S.name as riskname, D.name AS dname, U.role as roles, S.code AS riskcode, P.code AS pcode, C.id_type, C.id_number, C.birth_date, U.first_name, U.last_name, T.currency_status, T.currency_rate, T.currency_title, C.loss_payee, C.postal_address, C.id as customerid, Q.gc, H.name as branchnameuser, U.user_code, T.first_loss, T.gps_tracking FROM transactions T INNER JOIN customers C ON C.id = T.customer_id INNER JOIN regions R ON R.id=C.region_id INNER JOIN districts D ON D.id=C.district_id INNER JOIN insurance_types I ON I.id=T.insurance_type_id INNER JOIN insurance_coverages S ON T.insurance_coverage_id=S.id INNER JOIN users U ON U.id=T.user_id INNER JOIN insurance_products P ON P.id=T.insurance_product_id LEFT JOIN tra_dc as Q ON Q.trans_id = T.fleet_id_entry LEFT JOIN branches as H ON H.id = U.branch_id WHERE T.fleet_id_entry=? ORDER BY T.request_id DESC", [$id]);

        $customername = "";

        $addons = DB::table('addons')->where('transaction_id', $id)->get();

        $pdfname = time()."-".$id;

        $branch_agent = [];
        $ba_id = 0;
        $bank = [];
        $mobile = [];
        foreach($trans as $data) {
            $customername = $data->customername;
            if ($data->roles == 'agent'){
                $branch_agent = DB::table('users')
                    ->join('agents', 'agents.id', '=', 'users.user_id')
                    ->where('users.id', $data->user_id)
                    ->select('agents.*')
                    ->get();
            }elseif($data->roles == 'admin'){
                $branch_agent = DB::table('users')
                    ->join('branches', 'branches.id', '=', 'users.user_id')
                    ->where('users.id', $data->user_id)
                    ->select('branches.*')
                    ->get();
            }

            if($data->paymentmodes == 2){
                $bank = DB::table('bank_payments')
                    ->where('order_id', $id)
                    ->get();

            }elseif($data->paymentmodes == 3){
                $mobile = DB::table('mobile_payments')
                ->where('order_id', $id)
                ->get();
            }
            break;
        }

        $pdf = PDF::loadView('print-fleet-motor', compact('trans', 'branch_agent', 'mobile', 'bank', 'addons'));
        return $pdf->download("$customername.pdf");
    }


    public function printFleetNonMotorCover($id){
        $trans = DB::select("SELECT T.id, T.request_id AS requestid, T.company_code, T.system_code, T.callback_url, T.insurer_company_code, T.tran_company_code, T.covernote_type, T.covernote_number, T.prev_covernote_reference_number, T.sales_point_code, date_format(T.covernote_start_date,'%b %d, %Y') AS startdate, date_format(T.covernote_end_date,'%b %d, %Y') AS enddate, T.covernote_desc, T.operative_clause, T.covering_details, T.payment_mode AS paymentmodes, T.currency_code, T.exchange_rate, T.total_premium_excluding_tax, T.total_premium_including_tax AS totalpremiums, T.commission_paid, T.commission_rate, T.officer_name, T.officer_title, T.endorsement_type, T.endorsement_reason, T.sum_insured AS suminsured, T.sum_insured_equivalent, T.premium_rate, T.premium_before_discount, T.premium_discount, T.discount_type, T.premium_after_discount, T.premium_excluding_tax_equivalent, T.premium_including_tax, T.tax_code, T.tax_rate, T.tax_amount, T.subject_matter_reference, T.subject_matter_desc, T.addon_reference, T.addon_desc, T.addon_amount, T.addon_premium_rate, T.premium_excluding_tax, T.is_feet, T.fleet_id, T.fleet_size, T.comprehensive_insured, T.fleet_entry, T.motor_category, T.registration_number, T.Chassis_number, T.make, T.model, T.model_number, T.body_type, T.color, T.engine_number, T.engine_capacity, T.fuel_used, T.number_Of_axles, T.axle_distance, T.Sitting_capacity, T.year_Of_manufacture, T.tare_weight, T.gross_weight, T.motor_usage, T.owner_name, T.motor_type, T.owner_category, T.owner_address, T.covernote_reference_number, T.sticker_number, T.acknowledgement_status_code, T.acknowledgement_status_desc, T.response_status_code, T.response_status_desc, T.status AS status, T.cheque_number, T.bank, T.payment_number, T.payment_network, T.insurance_product_id, T.insurance_coverage_id, T.insurance_type_id, T.customer_id, T.branch_id, T.user_id, T.is_deleted, T.created_at, T.updated_at, C.full_name AS customername, C.phone_number as cphone, R.name AS region, I.name as insurancename, S.name as riskname, D.name AS dname, U.role as roles, S.code AS riskcode, P.code AS pcode, C.id_type, C.id_number, C.birth_date, U.first_name, U.last_name, T.currency_status, T.currency_rate, T.currency_title, C.loss_payee, C.postal_address, C.id as customerid, Q.gc, H.name as branchnameuser, U.user_code, T.first_loss, T.gps_tracking FROM transactions T INNER JOIN customers C ON C.id = T.customer_id INNER JOIN regions R ON R.id=C.region_id INNER JOIN districts D ON D.id=C.district_id INNER JOIN insurance_types I ON I.id=T.insurance_type_id INNER JOIN insurance_coverages S ON T.insurance_coverage_id=S.id INNER JOIN users U ON U.id=T.user_id INNER JOIN insurance_products P ON P.id=T.insurance_product_id LEFT JOIN tra_dc as Q ON Q.trans_id = T.fleet_id_entry LEFT JOIN branches as H ON H.id = U.branch_id WHERE T.fleet_id_entry=? ORDER BY T.request_id DESC", [$id]);

$customername = "";

$addons = DB::table('addons')->where('transaction_id', $id)->get();

$pdfname = time()."-".$id;

$branch_agent = [];
$ba_id = 0;
$bank = [];
$mobile = [];
foreach($trans as $data) {
    $customername = $data->customername;
    if ($data->roles == 'agent'){
        $branch_agent = DB::table('users')
            ->join('agents', 'agents.id', '=', 'users.user_id')
            ->where('users.id', $data->user_id)
            ->select('agents.*')
            ->get();
    }elseif($data->roles == 'admin'){
        $branch_agent = DB::table('users')
            ->join('branches', 'branches.id', '=', 'users.user_id')
            ->where('users.id', $data->user_id)
            ->select('branches.*')
            ->get();
    }

    if($data->paymentmodes == 2){
        $bank = DB::table('bank_payments')
            ->where('order_id', $id)
            ->get();

    }elseif($data->paymentmodes == 3){
        $mobile = DB::table('mobile_payments')
        ->where('order_id', $id)
        ->get();
    }
    break;
}

$pdf = PDF::loadView('fleet-non-motor', compact('trans', 'branch_agent', 'mobile', 'bank', 'addons'));
return $pdf->download("$customername.pdf");
}


    public function printQuotation($id){

        $trans = DB::table('transactions as T')
                 ->join('customers as C', 'C.id', '=', 'T.customer_id')
                 ->join('insurance_coverages as R', 'R.id', '=', 'T.insurance_coverage_id')
                 ->join('users as U', 'U.id', '=', 'T.user_id')
                 ->where('T.id', $id)
                 ->select('T.*', 'R.name as rname', 'C.full_name as cname','C.id_type', 'C.id_number', 'C.postal_address', 'U.first_name', 'U.last_name', 'U.role', 'U.user_id')->get();

        $addons = DB::table('addons')->where('transaction_id', $id)->get();

        $role = "";
        $agent_id = 0;
        $agent = [];
        foreach($trans as $data){
            if( $data->role == "agent"){
                $role = $data->role;
                $agent = Agent::where('id', $data->user_id)->first();
            }
        }

        $pdfname = "Quotation-".time();
        $pdf = PDF::loadView('quotation', compact('trans', 'addons', 'agent', 'role'));
        return $pdf->download("$pdfname.pdf");
    }


    public function printFleetQuotation($id){

        $trans = DB::table('transactions as T')
                 ->join('customers as C', 'C.id', '=', 'T.customer_id')
                 ->join('insurance_coverages as R', 'R.id', '=', 'T.insurance_coverage_id')
                 ->join('users as U', 'U.id', '=', 'T.user_id')
                 ->where('T.id', $id)
                 ->select('T.*', 'R.name as rname', 'C.full_name as cname','C.id_type', 'C.id_number', 'C.postal_address', 'U.first_name', 'U.last_name', 'U.role', 'U.user_id')->get();

        $addons = DB::table('addons')->where('transaction_id', $id)->get();

        $role = "";
        $agent_id = 0;
        $agent = [];
        $fleet_id = 0;
        foreach($trans as $data){
            if( $data->role == "agent"){
                $role = $data->role;
                $agent = Agent::where('id', $data->user_id)->first();
            }
            $fleet_id = $data->fleet_id_entry;
        }

        $fleet = DB::table('transactions as T')
        ->join('customers as C', 'C.id', '=', 'T.customer_id')
        ->join('insurance_coverages as R', 'R.id', '=', 'T.insurance_coverage_id')
        ->join('users as U', 'U.id', '=', 'T.user_id')
        ->where('T.fleet_id_entry', $fleet_id)
        ->select('T.*', 'R.name as rname', 'C.full_name as cname','C.id_type', 'C.id_number', 'C.postal_address', 'U.first_name', 'U.last_name', 'U.role', 'U.user_id')->get();

        $pdfname = "Quotation-".time();
        $pdf = PDF::loadView('fleet-quotation', compact('trans', 'addons', 'agent', 'role', 'fleet'));
        return $pdf->download("$pdfname.pdf");
    }

    public function printFleetQuotationNon($id){

        $trans = DB::table('transactions as T')
                 ->join('customers as C', 'C.id', '=', 'T.customer_id')
                 ->join('insurance_coverages as R', 'R.id', '=', 'T.insurance_coverage_id')
                 ->join('users as U', 'U.id', '=', 'T.user_id')
                 ->where('T.id', $id)
                 ->select('T.*', 'R.name as rname', 'C.full_name as cname','C.id_type', 'C.id_number', 'C.postal_address', 'U.first_name', 'U.last_name', 'U.role', 'U.user_id')->get();

        $addons = DB::table('addons')->where('transaction_id', $id)->get();

        $role = "";
        $agent_id = 0;
        $agent = [];
        $fleet_id = 0;
        foreach($trans as $data){
            if( $data->role == "agent"){
                $role = $data->role;
                $agent = Agent::where('id', $data->user_id)->first();
            }
            $fleet_id = $data->fleet_id_entry;
        }

        $fleet = DB::table('transactions as T')
        ->join('customers as C', 'C.id', '=', 'T.customer_id')
        ->join('insurance_coverages as R', 'R.id', '=', 'T.insurance_coverage_id')
        ->join('users as U', 'U.id', '=', 'T.user_id')
        ->where('T.fleet_id_entry', $fleet_id)
        ->select('T.*', 'R.name as rname', 'C.full_name as cname','C.id_type', 'C.id_number', 'C.postal_address', 'U.first_name', 'U.last_name', 'U.role', 'U.user_id')->get();

        $pdfname = "Quotation-".time();
        $pdf = PDF::loadView('non-motor-fleet-quotation', compact('trans', 'addons', 'agent', 'role', 'fleet'));
        return $pdf->download("$pdfname.pdf");
    }

    public function printMetRec($id){

        $trans = DB::table('transactions as T')
                 ->join('customers as C', 'C.id', '=', 'T.customer_id')
                 ->join('insurance_coverages as R', 'R.id', '=', 'T.insurance_coverage_id')
                 ->join('users as U', 'U.id', '=', 'T.user_id')
                 ->where('T.id', $id)
                 ->select('T.*', 'R.name as rname', 'C.full_name as cname', 'C.vrn', 'C.postal_address', 'U.first_name', 'U.last_name')->get();

        $addons = DB::table('addons')->where('transaction_id', $id)->get();

        $tran = Transaction::where('id', $id)->firstOrFail();
        $payment =[];

        if($tran->payment_mode == 2)
        {
            $payment = DB::table('bank_payments')->where('order_id', $id)->get();
        }
        if($tran->payment_mode == 3){
            $payment = DB::table('mobile_payments')->where('order_id', $id)->get();
        }

        $pdfname = "met receipt -".time();
        $pdf = PDF::loadView('met-receipt', compact('trans', 'addons', 'payment', 'tran'));
        return $pdf->download("$pdfname.pdf");

    }

    public function  printMetRecFleet($fleetid, $id){

        $trans = DB::table('transactions as T')
                 ->join('customers as C', 'C.id', '=', 'T.customer_id')
                 ->join('insurance_coverages as R', 'R.id', '=', 'T.insurance_coverage_id')
                 ->join('users as U', 'U.id', '=', 'T.user_id')
                 ->where('T.id', $id)
                 ->select('T.*', 'R.name as rname', 'C.full_name as cname', 'C.vrn', 'C.postal_address', 'U.first_name', 'U.last_name')->get();

        $fleet = DB::table('transactions as T')
                ->join('insurance_coverages as C', 'C.id', '=', 'T.insurance_coverage_id')
                ->where('T.fleet_id_entry', $fleetid)
                ->select('T.*', 'C.name as name_cover', 'C.code as code_cover')
                ->get();

        $addons = DB::table('addons')->where('transaction_id', $id)->get();

        $tran = Transaction::where('id', $id)->firstOrFail();
        $payment =[];

        if($tran->payment_mode == 2)
        {
            $payment = DB::table('bank_payments')->where('order_id', $id)->get();
        }
        if($tran->payment_mode == 3){
            $payment = DB::table('mobile_payments')->where('order_id', $id)->get();
        }

        $pdfname = "met receipt -".time();
        $pdf = PDF::loadView('met-receipt-fleet', compact('trans', 'addons', 'fleet', 'payment', 'tran'));
        return $pdf->download("$pdfname.pdf");

    }
  

    public function printReceipt($id){
        try{
            $details = TRADC::where('trans_id', $id)->firstOrFail();
            $trans = Transaction::where('id', $id)->firstOrFail();

            $payment =[];

            if($trans->payment_mode == 2)
            {
                $payment = DB::table('bank_payments')->where('order_id', $id)->get();
            }
            if($trans->payment_mode == 3){
                $payment = DB::table('mobile_payments')->where('order_id', $id)->get();
            }

            $pdfname = "receipt-$id".time();
            
            $pdf = PDF::loadView('receipt', compact('details','trans', 'payment'));
            return $pdf->download("$pdfname.pdf");
            
        }catch(\Exception $e){
            return $e;
        }
    }

    public function printTaxInvoice($id){
        $trans = $this->getTransaction($id);
        $array = TRARegistration::where('id', 1)->firstOrFail();
        $detail = DB::table('tra_dc')->where('trans_id', $id)->get();
        $addons_products = DB::table('addons')->where('transaction_id', $id)->get();
        $details = TRADC::where('trans_id', $id)->firstOrFail();
        $pdfname = "tax-invoice-".time();

        $payment = [];

        if($trans->payment_mode == 2)
        {
            $payment = DB::table('bank_payments')->where('order_id', $id)->get();
        }
        if($trans->payment_mode == 3){
            $payment = DB::table('mobile_payments')->where('order_id', $id)->get();
        }
        
        $pdf = PDF::loadView('tax-invoice', compact('details','trans', 'array', 'addons_products', 'payment'));
        return $pdf->download("$pdfname.pdf");
    }


    public function printCancellationCover($id){
        try{
            if(Auth::check()){

                $trans = Transaction::findOrFail($id);

                $re_date = Carbon::parse($trans->covernote_start_original_date);
                $re_now = Carbon::parse($trans->covernote_start_date);
                $days = $re_date->diffInDays($re_now);
                $before = $trans->total_premium_including_tax;
                $product = $trans->insurance_type_id;
                $duration = $trans->durations;
                $premium = 0;

                if($trans->durations < 12){
                    $cover_days = $trans->durations*30;
                    $premium = ($days/$cover_days)*$before;

                }else{
                    if($product==2){
                        if($days < 1){$premium = null;}else{$premium = ($days/365)*$before;}
                    }else{
                        if($days < 1){$premium = null;}else{$premium = ($days/366)*$before;}
                    }
                }

                $cover_name = time();
                $pdf = PDF::loadView('cancellation', compact('trans', 'days', 'premium'));
                return $pdf->download("$cover_name.pdf");

            }
        }catch(\Exception $e){
            return $e;
        }
    }
}
