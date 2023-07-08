<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\BankPayments;
use App\Models\BanMotor;
use App\Models\Branch;
use App\Models\Currency;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\FleetEntry;
use App\Models\InsuranceCoverage;
use App\Models\MobilePayment;
use App\Models\User;
use DateTime;
use Google\Service\ChromeUXReport\Key;
use Illuminate\Support\Facades\Auth;

class FleetController extends Controller
{



    public function duration(){
        $currency = DB::table('currencies')->where('is_deleted', 0)->get();
        if(Auth::user()->role == "admin"){
            return view('admin.fleet-duration', compact('currency'));
        }elseif(Auth::user()->role == "agent"){
            return view('agent.fleet-duration', compact('currency'));
        }
    }

    public function customer(){

        $regions = DB::table('regions')->where('is_deleted', 0)->get();
        if(Auth::user()->role == "admin"){
            $customer = DB::table('customers')->get();
            return view('admin.fleet-customer', compact('regions', 'customer'));
        }elseif(Auth::user()->role == "agent"){
            $customer = DB::table('customers')
            ->join('users', 'users.id', '=', 'customers.user_id')
            ->where('users.role', 'agent')
            ->where('users.user_id', Auth::user()->user_id)
            ->select('customers.*')
            ->get();
            return view('agent.fleet-customer', compact('regions', 'customer'));
        }

    }

    public function motor(Request $request){

        $fleet_entries = DB::table('transactions as T')->join('insurance_coverages as C', 'C.id', '=', 'T.insurance_coverage_id')->where('T.fleet_id_entry', $request->session()->get("fleet_id_entry"))->select('T.*', 'C.name as coverage_names')->get();
        $type = DB::table('insurance_products')->where('insurance_type_id', 2)->get();

        $coverage = DB::table('insurance_coverages as C')->join('insurance_products as P', 'P.id', '=', 'C.insurance_product_id')->where('P.insurance_type_id', 2)->select('C.*')->get();

        if(Auth::user()->role == "admin"){
            return view('admin.fleet-motor', compact('type', 'fleet_entries', 'coverage'));
        }elseif(Auth::user()->role == "agent"){
            return view('agent.fleet-motor', compact('type', 'fleet_entries', 'coverage'));
        }


    }


    public function fleetDuration(Request $request){

        //create start and end-date
        $salepoint = $this->getCode("sales");
        $companycode = $this->getCode("company");

        $start_dates = $request->input('startdate')." ".$request->input('starttime');
        $end_dates = $request->input('startdate')." 23:59:59";

        $date_s=date_create($start_dates);
        $date_e=date_create($end_dates);

        $duration = $request->input('duration');

        $add_minutes_start_date = date_add($date_s,date_interval_create_from_date_string("10 minutes"));
        $end_d = date_add($date_e,date_interval_create_from_date_string("$duration months"));
        $end_date = date_sub($end_d,date_interval_create_from_date_string("1 days"));
        $enddate = date_format($end_date, 'Y-m-d H:i:s');
        $startdate = date_format($add_minutes_start_date, 'Y-m-d H:i:s');


        $currency_status = 0;
        $currency_title = "";
        $currency_rate = 0;
        $currency_id = $request->input('currencyvalue');

        //set currency
        if($currency_id !=0)
        {
            $currency_fetch = Currency::where('id', $currency_id)->first();

            $currency_status = 1;
            $currency_title = $currency_fetch->from_title;
            $currency_rate = $currency_fetch->to_amount;
        }

        $request->session()->put("currencystatus", $currency_status);
        $request->session()->put("currencyid", $currency_id);
        $request->session()->put("currencytitle", $currency_title);
        $request->session()->put("currencyrate", $currency_rate);


        //assign fleet date and init value
        $request->session()->put("fleet_start_date", $startdate);
        $request->session()->put("fleet_end_date", $enddate);
        $request->session()->put("fleet_duration", $duration);
        $request->session()->put("fleet_sales_point", $salepoint);
        $request->session()->put("fleet_company_code", $companycode);

        $trans = 1;

        if($trans > 0){
            $request->session()->put("current_fleet_id", $trans);
            if(Auth::user()->role == "admin"){
                return redirect()->route('admin.fleet-customer');
            }elseif(Auth::user()->role == "agent"){
                return redirect()->route('agent.fleet-customer');
            }

        }else{
            return redirect()->back()
            ->with('message-error', 'Something went wrong, please try again');
        }
    }


    public function updateFleetDuration(Request $request){
        $start_dates = $request->input('startdate')." ".$request->input('starttime');
        $end_dates = $request->input('startdate')." 23:59:59";

        $date_s=date_create($start_dates);
        $date_e=date_create($end_dates);

        $duration = $request->input('duration');
        $request->session()->put("fleet_duration", $duration);

        $add_minutes_start_date = date_add($date_s,date_interval_create_from_date_string("10 minutes"));
        $end_d = date_add($date_e,date_interval_create_from_date_string("$duration months"));
        $end_date = date_sub($end_d,date_interval_create_from_date_string("1 days"));
        $enddate = date_format($end_date, 'Y-m-d H:i:s');
        $startdate = date_format($add_minutes_start_date, 'Y-m-d H:i:s');

        $update = Transaction::where('fleet_id_entry', $request->input('id'))->update([
            'covernote_start_date' => $startdate,
            'covernote_end_date' => $enddate
        ]);

        return redirect()->back()
            ->with('success', 'You have successful update covernote start date and covernote end date');
    }



    public function addFleetMotorInformation(Request $request)
    {

        $tax_code = $tax_rate = 0;
        //get tax code and rate
        if($request->session()->get('regioncode') < 26){$tax_code ="VAT-MAINLAND"; $tax_rate = 0.18;}else{$tax_code ="VAT-ZANZIBAR"; $tax_rate = 0.15;}

        $count_banned_motor = DB::table('ban_motors')->where('registration_number', $request->input("registrationnumber"))->count();
        if($count_banned_motor < 1){

        //get data from prev selection and action
        $systemcode = "SYS0003";
        $callbackurl = "http://metvaps.vusha.co.tz/api/covernoteref/resp";
        $insurercompanycode = "IC001";
        $trancompanycode = "IC001";

        //from user login
        $officername = "";
        $commisionrate = 0;
        $commisionpaid = 0;

        if(Auth::user()->role == 'agent'){
            $agents_info = Agent::where('id', Auth::user()->user_id)->first();
            $agents_info_owner = json_decode($agents_info->principal_owner, true);
            $officername = $agents_info_owner['ownerfname'] . " " . $agents_info_owner['ownerlname'];

            $commisionrate = 0.125;
            $commisionpaid = $commisionrate * $request->input("premiumexcl");

        }else{
            $officername = "" . Auth::user()->first_name . " ". Auth::user()->last_name . "";
            $commisionrate = 0;
            $commisionpaid = 0;
        }

        $fleet_entry = DB::table('transactions')->insertGetId([
            'request_id'=> "TRM".rand(10,100).time(),
            'company_code' => $request->session()->get("fleet_company_code"),
            'system_code' => "$systemcode",
            'callback_url' => "$callbackurl",
            'insurer_company_code' => "$insurercompanycode",
            'tran_company_code' => "$trancompanycode",
            'covernote_type' => 1,
            'sales_point_code' => $request->session()->get("fleet_sales_point"),
            'officer_name' => "$officername",
            'officer_title' => Auth::user()->role,
            'subject_matter_reference' =>"HSB001",
            'subject_matter_desc' => "",
            'addon_amount' => "0.00",
            'addon_premium_rate' => "0.00",

            'currency_status' =>$request->session()->get("currencystatus"),
            'currency_rate' =>$request->session()->get("currencyrate"),
            'currency_title' =>  $request->session()->get("currencytitle"),

            'commission_paid' => $commisionpaid,
            'commission_rate' => $commisionrate,
            'covernote_start_date' => $request->session()->get("fleet_start_date"),
            'covernote_end_date' => $request->session()->get("fleet_end_date"),
            'covernote_desc' => $request->input("covernotedesc"),
            'operative_clause' => $request->input("operativeclause"),
            'total_premium_excluding_tax' => $request->input("premiumexcl"),
            'total_premium_including_tax' => $request->input("premiuminclvat"),
            'sum_insured' => $request->input("valueofvehicle"),
            'sum_insured_equivalent' => $request->input("valueofvehicle"),
            'premium_rate' => 0,
            'premium_before_discount' => $request->input("premiumexcl"),
            'premium_discount' => 0.00,
            'premium_after_discount' => $request->input("premiumexcl"),
            'premium_excluding_tax_equivalent' => $request->input("premiumexcl"),
            'premium_including_tax' => $request->input("premiuminclvat"),
            'tax_code' => "$tax_code",
            'tax_rate' => "$tax_rate",
            'tax_amount' => $request->input("premiumvat"),
            'premium_excluding_tax' => $request->input("premiumexcl"),
            'durations' => $request->session()->get("fleet_duration"),

            'insurer' => $request->input("insurer"),
            'expiry_date' => $request->input("expirydate"),

            'is_feet' => "N",
            'motor_category' => $request->input("motorcategory"),
            'motor_type' => $request->input("motortype"),
            'registration_number' => $request->input("registrationnumber"),
            'Chassis_number' => $request->input("chassisnumber"),
            'make' => $request->input("make"),
            'model' => $request->input("model"),
            'model_number' => $request->input("modelnumber"),
            'body_type' => $request->input("bodytype"),
            'color' => $request->input("color"),
            'engine_number' => $request->input("enginenumber"),
            'engine_capacity' => $request->input("enginecapacity"),
            'fuel_used' => $request->input("fuelused"),
            'number_Of_axles' => $request->input("numberofaxles"),
            'axle_distance' => $request->input("axledistance"),
            'Sitting_capacity' => $request->input("sittingcapacity"),
            'year_Of_manufacture' => $request->input("yearofmanufacture"),
            'tare_weight' => $request->input("tareweight"),
            'gross_weight' => $request->input("grossweight"),
            'motor_usage' => $request->input("motorusage"),
            'owner_name' => $request->input("ownername"),
            'owner_category' => $request->input("ownercategory"),
            'owner_address' => $request->input("owneraddress"),
            'insurance_product_id' => $request->input('instypes'),
            'insurance_coverage_id' => $request->input('coverage'),
            'insurance_type_id'=>  2,
            'customer_id'=> $request->session()->get("cid"),
            'branch_id'=> 3,
            'user_id'=> Auth::user()->id,
            'status'=> "Pending",
            'covernote_number'=> rand(1,10).time().date('dHsi'),
            'image_reference'=> "REF".rand(100, 10000).time(),
            'fleet_id_entry'=> $request->session()->get("fleet_id_entry"),
            'fleet_status_entry'=> 'FLEET',
            'first_loss' => $request->input("firstloss"),
        ]);

        if($fleet_entry == true){
            return redirect()->back()->with("success", "Motor successful added in fleet");
        }else{
            return redirect()->back()->with('error', "Please fill valid details of motor");
        }

    }else{

        $banned_motor = BanMotor::where('registration_number', $request->input("registrationnumber"))->first();
        return redirect()->back()->with('error', "Sorry you can not issue cover with that motor, Bimakwik Insurance banned the motor to be issued on Metvaps, reason to be ban is $banned_motor->reason");
    }

    }



    public function addFleetMotorInformationInside(Request $request)
    {

        $tax_code = $tax_rate = 0;
        //get tax code and rate
        $tax_code ="VAT-MAINLAND"; $tax_rate = 0.18;

        $count_banned_motor = DB::table('ban_motors')->where('registration_number', $request->input("registrationnumber"))->count();
        if($count_banned_motor < 1){

        //get data from prev selection and action
        $systemcode = "SYS0003";
        $callbackurl = "http://metvaps.vusha.co.tz/api/covernoteref/resp";
        $insurercompanycode = "IC001";
        $trancompanycode = "IC001";

        //from user login
        $officername = "";
        $commisionrate = 0;
        $commisionpaid = 0;

        if(Auth::user()->role == 'agent'){
            $agents_info = Agent::where('id', Auth::user()->user_id)->first();
            $agents_info_owner = json_decode($agents_info->principal_owner, true);
            $officername = $agents_info_owner['ownerfname'] . " " . $agents_info_owner['ownerlname'];

            $commisionrate = 0.125;
            $commisionpaid = $commisionrate * $request->input("premiumexcl");

        }else{
            $officername = "" . Auth::user()->first_name . " ". Auth::user()->last_name . "";
            $commisionrate = 0;
            $commisionpaid = 0;
        }

        $fleet_entry = DB::table('transactions')->insertGetId([
            'request_id'=> "TRM".rand(10,100).time(),
            'company_code' => $request->input("fleet_company_code"),
            'system_code' => "$systemcode",
            'callback_url' => "$callbackurl",
            'insurer_company_code' => "$insurercompanycode",
            'tran_company_code' => "$trancompanycode",
            'covernote_type' => 1,
            'sales_point_code' => $request->input("fleet_sales_point"),
            'officer_name' => "$officername",
            'officer_title' => Auth::user()->role,
            'subject_matter_reference' =>"HSB001",
            'subject_matter_desc' => "",
            'addon_amount' => "0.00",
            'addon_premium_rate' => "0.00",

            'commission_paid' => $commisionpaid,
            'commission_rate' => $commisionrate,
            'covernote_start_date' => $request->input("fleet_start_date"),
            'covernote_end_date' => $request->input("fleet_end_date"),
            'covernote_desc' => $request->input("covernotedesc"),
            'operative_clause' => $request->input("operativeclause"),
            'total_premium_excluding_tax' => $request->input("premiumexcl"),
            'total_premium_including_tax' => $request->input("premiuminclvat"),
            'sum_insured' => $request->input("valueofvehicle"),
            'sum_insured_equivalent' => $request->input("valueofvehicle"),
            'premium_rate' => 0,
            'premium_before_discount' => $request->input("premiumexcl"),
            'premium_discount' => 0.00,
            'premium_after_discount' => $request->input("premiumexcl"),
            'premium_excluding_tax_equivalent' => $request->input("premiumexcl"),
            'premium_including_tax' => $request->input("premiuminclvat"),
            'tax_code' => "$tax_code",
            'tax_rate' => "$tax_rate",
            'tax_amount' => $request->input("premiumvat"),
            'premium_excluding_tax' => $request->input("premiumexcl"),
            'durations' => 12,

            'insurer' => $request->input("insurer"),
            'expiry_date' => $request->input("expirydate"),

            'is_feet' => "N",
            'motor_category' => $request->input("motorcategory"),
            'motor_type' => $request->input("motortype"),
            'registration_number' => $request->input("registrationnumber"),
            'Chassis_number' => $request->input("chassisnumber"),
            'make' => $request->input("make"),
            'model' => $request->input("model"),
            'model_number' => $request->input("modelnumber"),
            'body_type' => $request->input("bodytype"),
            'color' => $request->input("color"),
            'engine_number' => $request->input("enginenumber"),
            'engine_capacity' => $request->input("enginecapacity"),
            'fuel_used' => $request->input("fuelused"),
            'number_Of_axles' => $request->input("numberofaxles"),
            'axle_distance' => $request->input("axledistance"),
            'Sitting_capacity' => $request->input("sittingcapacity"),
            'year_Of_manufacture' => $request->input("yearofmanufacture"),
            'tare_weight' => $request->input("tareweight"),
            'gross_weight' => $request->input("grossweight"),
            'motor_usage' => $request->input("motorusage"),
            'owner_name' => $request->input("ownername"),
            'owner_category' => $request->input("ownercategory"),
            'owner_address' => $request->input("owneraddress"),
            'insurance_product_id' => $request->input('instypes'),
            'insurance_coverage_id' => $request->input('coverage'),
            'insurance_type_id'=> 2,
            'customer_id'=> $request->input("cid"),
            'branch_id'=> 3,
            'user_id'=> Auth::user()->id,
            'status'=> "Pending",
            'covernote_number'=> rand(1,10).time().date('dHsi'),
            'image_reference'=> $request->input('image_reference'),
            'fleet_id_entry'=> $request->input("fleet_id_entry"),
            'fleet_status_entry'=> 'FLEET',
            'first_loss'=> $request->input("firstloss"),
        ]);

        if($fleet_entry == true){
            return redirect()->back()->with("success", "Motor successful added in fleet");
        }else{
            return redirect()->back()->with('error', "Please fill valid details of motor");
        }

    }else{

        $banned_motor = BanMotor::where('registration_number', $request->input("registrationnumber"))->first();
        return redirect()->back()->with('error', "Sorry you can not issue cover with that motor, Bimakwik Insurance banned the motor to be issued on Metvaps, reason to be ban is $banned_motor->reason");
    }

    }





    public function payment(Request $request){

        $fleet_entries = DB::table('transactions')->where('fleet_id_entry', $request->session()->get("fleet_id_entry"))->get();
        if(Auth::user()->role == "admin"){
            return view('admin.fleet-payment', compact('fleet_entries'));
        }elseif(Auth::user()->role == "agent"){
            return view('agent.fleet-payment', compact('fleet_entries'));
        }


    }

    public function status(Request $request){
        $mobile = [];
        $payment_mode =  $request->session()->get('payment_fleet');

        try{
            if($payment_mode == 3){
                $mobile = MobilePayment::where('order_id', $request->session()->get("fleet_id_entry"))->first();
            }
        }catch(\Exception $e){}

        if(Auth::user()->role == "admin"){
            return view('admin.fleet-status', compact('payment_mode', 'mobile'));
        }else{
            return view('agent.fleet-status', compact('payment_mode', 'mobile'));
        }

    }


    public function getCode($type){
        if(Auth::user()->role=="admin"){
            $code = DB::table('branches')->where('id', Auth::user()->user_id)->get();

            $company_code=$sale_code="null";

            foreach($code as $get_code){
                $company_code = $get_code->company_code;
                $sale_code = $get_code->sales_point_code;
            }
            if($sale_code =="null" && $company_code=="null"){
                if($type == "company"){
                    return env('INSURER_COMPANY_CODE');
                }elseif($type == "sales"){
                    return env('SALES_POINT_CODE');
                }
            }elseif($sale_code =="" && $company_code==""){
                if($type == "company"){
                    return env('INSURER_COMPANY_CODE');
                }elseif($type == "sales"){
                    return env('SALES_POINT_CODE');
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
                    return env('INSURER_COMPANY_CODE');
                }elseif($type == "sales"){
                    return env('SALES_POINT_CODE');
                }
            }elseif($sale_code =="" && $company_code==""){
                if($type == "company"){
                    return env('INSURER_COMPANY_CODE');
                }elseif($type == "sales"){
                    return env('SALES_POINT_CODE');
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

    public function motorSession($request){
        $request->session()->put("motorcategory", $request->input("motorcategory"));
        $request->session()->put("registrationnumber", $request->input("registrationnumber"));
        $request->session()->put("motortype", $request->input("motortype"));
        $request->session()->put("chassisnumber", $request->input("chassisnumber"));
        $request->session()->put("make", $request->input("make"));
        $request->session()->put("model", $request->input("model"));
        $request->session()->put("modelnumber", $request->input("modelnumber"));
        $request->session()->put("bodytype", $request->input("bodytype"));
        $request->session()->put("color", $request->input("color"));
        $request->session()->put("enginenumber", $request->input("enginenumber"));
        $request->session()->put("enginecapacity", $request->input("enginecapacity"));
        $request->session()->put("fuelused", $request->input("fuelused"));
        $request->session()->put("numberofaxles", $request->input("numberofaxles"));
        $request->session()->put("axledistance", $request->input("axledistance"));
        $request->session()->put("sittingcapacity", $request->input("sittingcapacity"));
        $request->session()->put("yearofmanufacture", $request->input("yearofmanufacture"));
        $request->session()->put("tareweigh", $request->input("tareweight"));
        $request->session()->put("grossweight", $request->input("grossweight"));
        $request->session()->put("motorusage",  $request->input("motorusage"));
        $request->session()->put("ownername", $request->input("ownername"));
        $request->session()->put("ownercategory", $request->input("ownercategory"));
        $request->session()->put("owneraddress", $request->input("owneraddress"));
        $request->session()->put("motordesc", $request->input("covernotedesc"));
        $request->session()->put("operativeclause", $request->input("operativeclause"));
        $request->session()->put("suminsured", $request->input('valueofvehicle'));
    }

    public function motorSessionForget($request){
        $request->session()->forget("motorcategory");
        $request->session()->forget("registrationnumber");
        $request->session()->forget("motortype");
        $request->session()->forget("chassisnumber");
        $request->session()->forget("make");
        $request->session()->forget("model");
        $request->session()->forget("modelnumber");
        $request->session()->forget("bodytype");
        $request->session()->forget("color");
        $request->session()->forget("enginenumber");
        $request->session()->forget("enginecapacity");
        $request->session()->forget("fuelused");
        $request->session()->forget("numberofaxles");
        $request->session()->forget("axledistance");
        $request->session()->forget("sittingcapacity");
        $request->session()->forget("yearofmanufacture");
        $request->session()->forget("tareweigh");
        $request->session()->forget("grossweight");
        $request->session()->forget("motorusage");
        $request->session()->forget("ownername");
        $request->session()->forget("ownercategory");
        $request->session()->forget("owneraddress");
        $request->session()->forget("motordesc");
        $request->session()->forget("operativeclause");
        $request->session()->forget("suminsured");
    }


    public function fleetTransaction($id){

        $trax = DB::table('transactions')->where('fleet_id_entry', $id)->limit(1)->get();
        $fleet_entry = DB::table('fleet_entries as F')
                       ->join('insurance_coverages as I', 'I.id', '=', 'F.insurance_coverage_id')
                       ->select('F.*', 'I.name as insurance')
                       ->where('F.transaction_id', $id)->get();

        $customer = DB::table('customers as C')
                    ->join('transactions as T', 'T.customer_id', '=', 'C.id')
                    ->join('regions as R', 'R.id', '=', 'C.region_id')
                    ->join('districts as D', 'D.id', '=', 'C.district_id')
                    ->where('T.id', $id)
                    ->select('C.*', 'R.name as rname', 'D.name as dname', 'R.id as rid', 'D.id as did')
                    ->get();

        $regions = DB::table('regions')->where('is_deleted', 0)->get();

        $type = DB::table('insurance_products')->where('insurance_type_id', 2)->get();

        //payment
        $bank_payment=DB::select("SELECT * FROM bank_payments WHERE order_id=?",["$id"]);

        $payment_approved_by = [];
        foreach($bank_payment as $data_bankpayment){
            $payment_approved_by = User::where('id', $data_bankpayment->user_id)->get();
            break;
        }

        if(Auth::user()->role == "admin"){
            return view('admin.fleet-transaction', compact('trax', 'fleet_entry', 'customer', 'regions', 'bank_payment', 'payment_approved_by', 'id', 'type'));
        }else{
            return view('agent.fleet-transaction', compact('trax', 'fleet_entry', 'customer', 'regions', 'bank_payment', 'payment_approved_by', 'id', 'type'));
        }


    }


    public function updateMotor(Request $request){

        $fleet_motor = FleetEntry::where('id', $request->input('id'))->update([
            'desc' => $request->input("covernotedesc"),
            'operative_clause' => $request->input("operativeclause"),
            'motor_type' => $request->input("motortype"),
            'registration_number' => $request->input("registrationnumber"),
            'chassis_number' => $request->input("chassisnumber"),
            'make' => $request->input("make"),
            'model' => $request->input("model"),
            'model_number' => $request->input("modelnumber"),
            'body_type' => $request->input("bodytype"),
            'color' => $request->input("color"),
            'engine_number' => $request->input("enginenumber"),
            'engine_capacity' => $request->input("enginecapacity"),
            'fuel_used' => $request->input("fuelused"),
            'number_of_axles' => $request->input("numberofaxles"),
            'axle_distance' => $request->input("axledistance"),
            'sitting_capacity' => $request->input("sittingcapacity"),
            'year_of_manufacture' => $request->input("yearofmanufacture"),
            'tare_weight' => $request->input("tareweight"),
            'gross_weight' => $request->input("grossweight"),
            'motor_usage' => $request->input("motorusage"),
            'owner_name' => $request->input("ownername"),
            'owner_category' => $request->input("ownercategory"),
            'owner_address' => $request->input("owneraddress"),
        ]);

        if($fleet_motor == true){
            return redirect()->back()->with('success', 'Successful update motor details');
        }else{
            return redirect()->back()->with('message-error', 'Fail to update motor details');
        }

    }



    public function updateMotorCoverage(Request $request, $id){
        $tax_code = $tax_rate = 0;
        //get tax code and rate
        $tax_code ="VAT-MAINLAND"; $tax_rate = 0.18;

        //from user login
        $commisionrate = 0;
        $commisionpaid = 0;

        if(Auth::user()->role == 'agent'){

            $commisionrate = 0.125;
            $commisionpaid = $commisionrate * $request->input("premiumexcl");

        }else{
            $commisionrate = 0;
            $commisionpaid = 0;
        }

        $fleet_entry = Transaction::where('id', $request->input('id'))->update([

            'commission_paid' => $commisionpaid,
            'commission_rate' => $commisionrate,
            'total_premium_excluding_tax' => $request->input("premiumexcl"),
            'total_premium_including_tax' => $request->input("premiuminclvat"),
            'sum_insured' => $request->input("valueofvehicle"),
            'sum_insured_equivalent' => $request->input("valueofvehicle"),
            'premium_before_discount' => $request->input("premiumexcl"),
            'premium_after_discount' => $request->input("premiumexcl"),
            'premium_excluding_tax_equivalent' => $request->input("premiumexcl"),
            'premium_including_tax' => $request->input("premiuminclvat"),
            'tax_code' => "$tax_code",
            'tax_rate' => "$tax_rate",
            'tax_amount' => $request->input("premiumvat"),
            'premium_excluding_tax' => $request->input("premiumexcl"),
            'insurance_coverage_id' => $request->input('coverage'),
            'insurance_product_id' => $request->input('instypes')

        ]);

        if($fleet_entry == true){
            return redirect()->back()->with("success", "Motor successful updated coverage");
        }else{
            return redirect()->back()->with('error', "Please fill valid details");
        }
    }


    public function calculatePremium($coverage, $sumins, $seat, Request $request){
        try{
            $tax_code = $tax_rate = 0;

            //get tax code and rate
            //if($request->session()->get('regioncode') < 26){$tax_code ="VAT-MAINLAND"; $tax_rate = 0.18;}else{$tax_code ="VAT-ZANZIBAR"; $tax_rate = 0.15;}
            $premium_including_tax = $tax_amount = $premium_excl_tax = 0;
            $tax_code ="VAT-MAINLAND"; $tax_rate = 0.18;

            $cov = DB::table('insurance_coverages')->where('id', $coverage)->get();

            $minimum_amount = $tpp_amount = $perset_amount = $additional_amount = $premium_before_discount = $premium_after_discount = $premium_excluding_tax_equivalent = $premium_including_tax = $tax_amount = 0;
            $is_plus_tpp = $is_perset = $coveragetype = '';

            foreach($cov as $cov_data){

                $minimum_amount = $cov_data->minimum_amount;
                $premium_rate = $cov_data->rate;
                $risk_code = $cov_data->code;

                $parameters = json_decode($cov_data->parameters, true);

                $is_perset = $parameters['isperset'];
                $perset_amount = $parameters['persetamount'];
                $is_plus_tpp = $parameters['plustpp'];
                $tpp_amount = $parameters['tppamount'];
                $additional_amount = $parameters['additionalamount'];
                $coveragetype = $cov_data->coverage_type;

            }

            $subpremium = 0;
            $subamount = 0;

            if($is_perset == "Yes") {
                if($is_plus_tpp == "Yes"){
                    if($premium_rate == 0) {
                        $subamount = $additional_amount + $tpp_amount + ($seat*$perset_amount);
                        if($subamount > $minimum_amount){
                            $subpremium = $subamount;
                        }else{
                            $subpremium = $minimum_amount;
                        }
                    } else {
                        $subamount = ($sumins * $premium_rate) / 100 + $additional_amount + $tpp_amount + ($seat * $perset_amount);
                        if ($subamount > $minimum_amount) {
                            $subpremium = $subamount;
                        } else {
                            $subpremium = $minimum_amount;
                        }
                    }
                }else if($is_plus_tpp == "No"){
                    if ($premium_rate == 0) {
                        $subamount = $additional_amount + ($seat * $perset_amount);
                        if($subamount > $minimum_amount){
                            $subpremium = $subamount;
                        }else{
                            $subpremium = $minimum_amount;
                        }
                    }else {
                        $subamount = ($sumins * $premium_rate) / 100 + $additional_amount + ($seat * $perset_amount);
                        if ($subamount > $minimum_amount) {
                            $subpremium = $subamount;
                        } else {
                            $subpremium = $minimum_amount;
                        }
                    }
                }
            }else if ($is_perset == "No") {
                if ($is_plus_tpp == "Yes") {

                    if ($premium_rate == 0) {
                        $subamount = $additional_amount + $tpp_amount;
                        if($subamount > $minimum_amount){
                            $subpremium = $subamount;
                        }else{
                            $subpremium = $minimum_amount;
                        }
                    } else {
                        $subamount = ($sumins * $premium_rate) / 100 + $additional_amount + $tpp_amount;
                        if ($subamount > $minimum_amount) {
                            $subpremium = $subamount;
                        } else {
                            $subpremium = $minimum_amount;
                        }
                    }
                } else if ($is_plus_tpp == "No") {
                    if ($premium_rate == 0) {
                        $subpremium = $minimum_amount + $additional_amount;
                    } else {
                        $subamount = ($sumins * $premium_rate) / 100 + $additional_amount;
                        if ($subamount > $minimum_amount) {
                            $subpremium = $subamount;
                        } else {
                            $subpremium = $minimum_amount;
                        }
                    }
                }
            }

            $pvalues = 0;
            $duration = $request->session()->get("fleet_duration");
            if($coveragetype=="Comprehensive"){
                if($duration<12){
                    if($duration==1){$pvalues = ($subpremium*0.2);
                    }elseif($duration==2){$pvalues = ($subpremium*0.3);
                    }elseif($duration==3){$pvalues = ($subpremium*0.4);
                    }elseif($duration==4){$pvalues= ($subpremium*0.5);
                    }elseif($duration==5){$pvalues= ($subpremium*0.6);
                    }elseif($duration==6){$pvalues= ($subpremium*0.7);
                    }elseif($duration==7){$pvalues= ($subpremium*0.8);
                    }elseif($duration==8){$pvalues= ($subpremium*0.9);
                    }elseif($duration==9){$pvalues= ($subpremium*0.85);
                    }
                }else{
                    $pvalues = $subpremium;
                }
            }else{
                $pvalues = $subpremium;
            }

            $premium_excl_tax = $pvalues;
            $tax_amount = $tax_rate * $premium_excl_tax;
            $premium_including_tax =  $tax_amount+$premium_excl_tax;

            return response()->json(array('ack_code' => '0', 'premium' => $premium_excl_tax, 'premium_in' => $premium_including_tax, 'vat' => $tax_amount, 'rates'=> $premium_rate));

        }catch(\Exception $e){

            return response()->json(array('ack_code' => '1'));
        }
    }

    public function calculateNewPremium($coverage, $sumins, $seat, $id){
        try{
            $tax_code = $tax_rate = 0;

            //get tax code and rate
            $premium_including_tax = $tax_amount = $premium_excl_tax = $premium_rate = 0;

            $cov = DB::table('insurance_coverages')->where('id', $coverage)->get();
            $trans = Transaction::where('id', $id)->first();

            $minimum_amount = $tpp_amount = $perset_amount = $additional_amount = $premium_before_discount = $premium_after_discount = $premium_excluding_tax_equivalent = $premium_including_tax = $tax_amount = 0;
            $is_plus_tpp = $is_perset = $coveragetype = '';

            foreach($cov as $cov_data){

                $minimum_amount = $cov_data->minimum_amount;
                $premium_rate = $cov_data->rate;
                $risk_code = $cov_data->code;

                $parameters = json_decode($cov_data->parameters, true);

                $is_perset = $parameters['isperset'];
                $perset_amount = $parameters['persetamount'];
                $is_plus_tpp = $parameters['plustpp'];
                $tpp_amount = $parameters['tppamount'];
                $additional_amount = $parameters['additionalamount'];
                $coveragetype = $cov_data->coverage_type;

            }

            $subpremium = 0;
            $subamount = 0;

            if($is_perset == "Yes") {
                if($is_plus_tpp == "Yes"){
                    if($premium_rate == 0) {
                        $subamount = $additional_amount + $tpp_amount + ($seat*$perset_amount);
                        if($subamount > $minimum_amount){
                            $subpremium = $subamount;
                        }else{
                            $subpremium = $minimum_amount;
                        }
                    } else {
                        $subamount = ($sumins * $premium_rate) / 100 + $additional_amount + $tpp_amount + ($seat * $perset_amount);
                        if ($subamount > $minimum_amount) {
                            $subpremium = $subamount;
                        } else {
                            $subpremium = $minimum_amount;
                        }
                    }
                }else if($is_plus_tpp == "No"){
                    if ($premium_rate == 0) {
                        $subamount = $additional_amount + ($seat * $perset_amount);
                        if($subamount > $minimum_amount){
                            $subpremium = $subamount;
                        }else{
                            $subpremium = $minimum_amount;
                        }
                    }else {
                        $subamount = ($sumins * $premium_rate) / 100 + $additional_amount + ($seat * $perset_amount);
                        if ($subamount > $minimum_amount) {
                            $subpremium = $subamount;
                        } else {
                            $subpremium = $minimum_amount;
                        }
                    }
                }
            }else if ($is_perset == "No") {
                if ($is_plus_tpp == "Yes") {

                    if ($premium_rate == 0) {
                        $subamount = $additional_amount + $tpp_amount;
                        if($subamount > $minimum_amount){
                            $subpremium = $subamount;
                        }else{
                            $subpremium = $minimum_amount;
                        }
                    } else {
                        $subamount = ($sumins * $premium_rate) / 100 + $additional_amount + $tpp_amount;
                        if ($subamount > $minimum_amount) {
                            $subpremium = $subamount;
                        } else {
                            $subpremium = $minimum_amount;
                        }
                    }
                } else if ($is_plus_tpp == "No") {
                    if ($premium_rate == 0) {
                        $subpremium = $minimum_amount + $additional_amount;
                    } else {
                        $subamount = ($sumins * $premium_rate) / 100 + $additional_amount;
                        if ($subamount > $minimum_amount) {
                            $subpremium = $subamount;
                        } else {
                            $subpremium = $minimum_amount;
                        }
                    }
                }
            }

            $pvalues = 0;
            $duration = $trans->durations;
            $tax_rate = $trans->tax_rate;
            if($coveragetype=="Comprehensive"){
                if($duration<12 && $duration != 0){
                    if($duration==1){$pvalues = ($subpremium*0.2);
                    }elseif($duration==2){$pvalues = ($subpremium*0.3);
                    }elseif($duration==3){$pvalues = ($subpremium*0.4);
                    }elseif($duration==4){$pvalues= ($subpremium*0.5);
                    }elseif($duration==5){$pvalues= ($subpremium*0.6);
                    }elseif($duration==6){$pvalues= ($subpremium*0.7);
                    }elseif($duration==7){$pvalues= ($subpremium*0.8);
                    }elseif($duration==8){$pvalues= ($subpremium*0.9);
                    }elseif($duration==9){$pvalues= ($subpremium*0.85);
                    }
                }else{
                    $pvalues = $subpremium;
                }
            }else{
                $pvalues = $subpremium;
            }

            $premium_excl_tax = $pvalues;
            $tax_amount = $tax_rate * $premium_excl_tax;
            $premium_including_tax =  $tax_amount+$premium_excl_tax;

            return response()->json(array('ack_code' => '0', 'premium' => $premium_excl_tax, 'premium_in' => $premium_including_tax, 'vat' => $tax_amount, 'rates'=> $premium_rate));

        }catch(\Exception $e){

            return response()->json(array('ack_code' => '1', "message"=>$e->getMessage()));
        }
    }

    public function removeFleet($id){
        $rem = Transaction::where('id', $id)->delete();
        if($rem == true){
            return 0;
        }else{
            return 1;
        }
    }


    public function updateFleetEntryDetail(Request $request, $id){

        $fleet_entry = Transaction::where('id', $request->input("fleetid"))->update([

            'covernote_desc' => $request->input("covernotedesc"),
            'operative_clause' => $request->input("operativeclause"),
            'insurer' => $request->input("insurer"),
            'expiry_date' => $request->input("expirydate"),
            'motor_category' => $request->input("motorcategory"),
            'motor_type' => $request->input("motortype"),
            'registration_number' => $request->input("registrationnumber"),
            'Chassis_number' => $request->input("chassisnumber"),
            'make' => $request->input("make"),
            'model' => $request->input("model"),
            'model_number' => $request->input("modelnumber"),
            'body_type' => $request->input("bodytype"),
            'color' => $request->input("color"),
            'engine_number' => $request->input("enginenumber"),
            'engine_capacity' => $request->input("enginecapacity"),
            'fuel_used' => $request->input("fuelused"),
            'number_Of_axles' => $request->input("numberofaxles"),
            'axle_distance' => $request->input("axledistance"),
            'Sitting_capacity' => $request->input("sittingcapacity"),
            'year_Of_manufacture' => $request->input("yearofmanufacture"),
            'tare_weight' => $request->input("tareweight"),
            'gross_weight' => $request->input("grossweight"),
            'motor_usage' => $request->input("motorusage"),
            'owner_name' => $request->input("ownername"),
            'owner_category' => $request->input("ownercategory"),
            'owner_address' => $request->input("owneraddress"),
            'first_loss' => $request->input("firstloss"),

        ]);


        if($fleet_entry == true){
            return redirect()->back()->with('success', 'Successful update fleet entry');
        }else{
            return redirect()->back()->with('error', 'Fail to update fleet entry');
        }
    }




    public function fleetDetails($id, Request $request){
        try{
            if(Auth::check()){

                $trans = Transaction::where('fleet_id_entry', $id)->first();
                $cashstaff = User::where('id', $trans->cash_id)->first();
                $mobilestaff = [];

                $idnumber = $trans->id;

                $request->session()->put("fleet_duration", $trans->durations);

                $customer = Customer::where('id', $trans->customer_id)->first();
                $mobile_payment = $bank_payment = $payment_approved_by_data = [];
                $documents = DB::select("SELECT id, transaction_id, name, image_type, created_at, updated_at FROM motor_galleries WHERE transaction_id=?", [$trans->fleet_id_entry]);
                $type = DB::table('insurance_products')->where('insurance_type_id', 2)->get();
                if($trans->payment_mode == 3){
                    $mobile_payment = MobilePayment::where('order_id', $trans->fleet_id_entry)->first();
                    $mobilestaff = User::where('id', $mobile_payment->approved_by)->first();
                }elseif($trans->payment_mode == 2){
                    $bank_payment = BankPayments::where('order_id', $trans->fleet_id_entry)->first();
                    $payment_approved_by_data = User::where('id', $bank_payment->user_id)->first();
                }

                $fleet_entries = DB::table('transactions')->where('fleet_id_entry', $trans->fleet_id_entry)->get();
                $issued = User::where('id', $trans->user_id)->first();

                $branch_agent_info = [];
                if($issued->role == 'agent'){

                    $branch_agent_info = Agent::where('id', $issued->user_id)->first();

                }elseif($issued->role == 'admin'){
                    $branch_agent_info =  Branch::where('id', $issued->user_id)->first();
                }

                $coverage = DB::table('insurance_coverages')->where('insurance_product_id', $trans->insurance_product_id)->get();

                if(Auth::user()->role == "admin"){
                    return view('admin.fleet-transaction', compact('customer', 'cashstaff', 'mobilestaff', 'trans', 'fleet_entries', 'mobile_payment', 'payment_approved_by_data', 'bank_payment', 'issued', 'branch_agent_info', 'coverage', 'documents', 'type', 'idnumber'));
                }else{
                    return view('agent.fleet-transaction', compact('customer', 'cashstaff', 'mobilestaff', 'trans', 'fleet_entries', 'mobile_payment', 'payment_approved_by_data', 'bank_payment', 'issued', 'branch_agent_info', 'coverage', 'documents', 'type', 'idnumber'));
                }

            }
        }catch(\Exception $e){
            if(Auth::user()->role == "admin"){
                return redirect()->route('admin.transaction');
            }else{
                return redirect()->route('agent.transaction');
            }
        }
    }



    public function changePaymentMode($id){

        $to = "RESET:".time().":$id";

        Transaction::where('fleet_id_entry', $id)->update([
            "payment_mode" => null
        ]);

        MobilePayment::where('order_id', $id)->update([
            'order_id' => $to
        ]);

        BankPayments::where('order_id', $id)->update([
            'order_id' => $to
        ]);

        return redirect()->back()->with('success', 'Successful reset payment method, please choose other payment method');

    }



}
