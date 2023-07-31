<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Agent;
use App\Models\District;
use App\Models\InsuranceCoverage;
use App\Models\InsuranceProduct;
use App\Models\InsuranceType;
use App\Models\Region;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InsuranceController extends Controller
{
    //

    public function getInsuranceType(){
        $data = InsuranceType::get();
        return response()->json($data, 200);
    }



    public function getInsuranceProduct($id){
        $data = InsuranceProduct::where('insurance_type_id',$id)->get();
        return response()->json($data, 200);
    }




    public function getInsuranceCoverage($id){
        $data = InsuranceCoverage::where('insurance_product_id',$id)->get();
        return response()->json($data, 200);
    }


    public function getRegion(){
        $data = Region::get();
        return response()->json($data, 200);
    }


    public function district($id){
        $data = District::where('region_id',$id)->get();
        return response()->json($data, 200);
    }

    public function getInsuranceValue(Request $request)
    {


        $validated = $request->validate([
            'startdate' =>'required',
            'starttime'=>'required',
            'enddate'=>'required',
            'duration'=>'required',
            'suminsured'=>'required',
            'covernotedesc'=>'required',
            'operativeclause'=>'required',
            'motorusage'=>'required',
            'riskcode'=>'required',
            'sittingcapacity'=>'required',
            'requestid'=>'required',
            'premiumrate'=>'required',
            'minimumamount'=>'required',
            'coveragetype'=>'required',
            'isperset'=>'required',
            'persetamount'=>'required',
            'plustpp'=>'required',
            'tppamount'=>'required',
            'additionalamount'=>'required',
            'regioncode'=>'required',
            'fixed_duration_months'=>'required',
            'insurancetypeid'=>'required',
            'insuranceproductid'=>'required',
            'coverageid'=>'required',
            'customer_id'=>'required',
            'currenttransactionid'=>'required'
        ]);
        $startdate = $validated['startdate'];
        $starttime = $validated['starttime'];
        $enddate = $validated['enddate'];
        $endtime = $request->endtime;
        $duration = $validated['duration'];
        $suminsured = $validated['suminsured'];
        $covernotedesc = $validated['covernotedesc'];
        $operativeclause = 500;
        $insurer = $request->insurer;
        $expirydate  = $request->expirydate;
        $suminsuredequivalent = $suminsured;

        $currency_status = 0;
        $currency_title = "";
        $currency_rate = 0;


        //check if user chooce other currency than TZ

            $sess_motorusage = $validated['motorusage'];
            $sess_riskcode = $validated['riskcode'];

            $sittingcapacities = $validated['sittingcapacity'];

            $requestids = $validated['requestid'];
            $premiumrate = $validated['premiumrate'];
            $minimumamount = $validated['minimumamount'];
            $coveragetype =  $validated['coveragetype'];

            $isperset = $validated['isperset'];
            $persetamount =  $validated['persetamount'];
            $plustpp = $validated['plustpp'];
            $tppamount = $validated['tppamount'];
            $additionalamount = $validated['additionalamount'];

            $regioncode = $validated['regioncode'];
            $fixeddurationmonths = $validated['fixed_duration_months'];
            $insurancetypeid = $validated['insurancetypeid'];

            //get data from prev selection and action
            $product_code = $validated['insuranceproductid'];
            $risk_code = $validated['coverageid'];
            $insurance_type_id = $insurancetypeid;
            $customerid = $validated['customer_id'];
            $imagereference=$request->input("imagereference");

        // 3 and 2 wheelers check if it's private(1) or commercial(2)
        if($sess_riskcode == "SP014002000001" || $sess_riskcode == "SP014002000002" || $sess_riskcode == "SP014002000005" ||$sess_riskcode == "SP014002000007"){
            if($sess_motorusage==2){
                $minimumamount = $minimumamount+15000;
            }
        }elseif($sess_riskcode == "SP014002000003" || $sess_riskcode == "SP014002000004" || $sess_riskcode == "SP014002000006" || $sess_riskcode == "SP014002000008"){
            if($sess_motorusage==2){
                $minimumamount = $minimumamount+45000;
            }
        }

        $taxcode = "";
        $taxrate = $taxamount = 0;
        if ($regioncode == 26 || $regioncode == 27 || $regioncode == 28 || $regioncode == 29 || $regioncode == 30) {
            $taxcode = "VAT-ZANZIBAR";
            $taxrate = 0.15;
        } else {
            $taxcode = "VAT-MAINLAND";
            $taxrate = 0.18;
        }

        $subpremium = 0;
        $subamount = 0;



        //check if non-motor or not
        if($insurancetypeid==2){



            if($isperset == "Yes") {

                if($plustpp == "Yes"){

                    if($premiumrate == 0) {
                        $subamount = $additionalamount + $tppamount + ($sittingcapacities * $persetamount);
                        if($subamount > $minimumamount){
                            $subpremium = $subamount;
                        }else{
                            $subpremium = $minimumamount;
                        }
                    } else {
                        $subamount = ($suminsured * $premiumrate) / 100 + $additionalamount + $tppamount + ($sittingcapacities * $persetamount);
                        if ($subamount > $minimumamount) {
                            $subpremium = $subamount;
                        } else {
                            $subpremium = $minimumamount;
                        }
                    }
                }else if($plustpp == "No"){
                    if ($premiumrate == 0) {
                        $subamount = $additionalamount + ($sittingcapacities * $persetamount);
                        if($subamount > $minimumamount){
                            $subpremium = $subamount;
                        }else{
                            $subpremium = $minimumamount;
                        }
                    }else {
                        $subamount = ($suminsured * $premiumrate) / 100 + $additionalamount + ($sittingcapacities * $persetamount);
                        if ($subamount > $minimumamount) {
                            $subpremium = $subamount;
                        } else {
                            $subpremium = $minimumamount;
                        }
                    }
                }
            }else if ($isperset == "No") {
                if ($plustpp == "Yes") {

                    if ($premiumrate == 0) {
                        // $subpremium = $minimumamount + $additionalamount + $tppamount;
                        $subamount = $additionalamount + $tppamount;
                        if($subamount > $minimumamount){
                            $subpremium = $subamount;
                        }else{
                            $subpremium = $minimumamount;
                        }
                    } else {
                        $subamount = ($suminsured * $premiumrate) / 100 + $additionalamount + $tppamount;
                        if ($subamount > $minimumamount) {
                            $subpremium = $subamount;
                        } else {
                            $subpremium = $minimumamount;
                        }
                    }
                } else if ($plustpp == "No") {
                    if ($premiumrate == 0) {
                        $subpremium = $minimumamount + $additionalamount;

                    } else {
                        $subamount = ($suminsured * $premiumrate) / 100 + $additionalamount;
                        if ($subamount > $minimumamount) {
                            $subpremium = $subamount;

                        } else {
                            $subpremium = $minimumamount;
                        }
                    }

                }
            }

            $grandpremiumvalue = 0;
            $pvalues = 0;

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
                        }elseif($duration==9){$pvalues= ($subpremium*0.85);}
                    }else{
                        $pvalues = $subpremium;
                    }
                }else{
                    $pvalues = $subpremium;
                }

            //$pvalues = $subpremium;

            $totalpremiumexcludingtax = $pvalues;
            $premiumexcludingtaxequivalent = $pvalues;
            $premiumbeforediscount = $pvalues;
            $premiumdiscount = 0;
            $premiumafterdiscount = $pvalues;
            $premiumexcludingtax = $pvalues;


            $taxamount = $pvalues * $taxrate;

            $totalpremiumincludingtax = $taxamount + $pvalues;
            $premiumincludingtax = $taxamount + $pvalues;

            $currenttransactionid =   $validated["currenttransactionid"];

            $commisionpaid = 0;
            $commisionrate = 0;
            $vunapoint = 0;

            $start_dates="$startdate"." ".$starttime;
            $end_dates="$startdate"." 23:59:59";

            $date_s=date_create($start_dates);
            $date_e=date_create($end_dates);

            $enddate=$startdate="";

            //get start and end date
            if($duration >= 12){
                $add_minutes_start_date = date_add($date_s,date_interval_create_from_date_string("5 minutes"));
                $end_d = date_add($date_e,date_interval_create_from_date_string("$duration months"));
                $end_date = date_sub($end_d,date_interval_create_from_date_string("1 days"));
                $enddate = date_format($end_date, 'Y-m-d H:i:s');
                $startdate = date_format($add_minutes_start_date, 'Y-m-d H:i:s');
            }else{
                $get_days = $duration*30;
                $add_minutes_start_date = date_add($date_s,date_interval_create_from_date_string("5 minutes"));
                $end_d = date_add($date_e,date_interval_create_from_date_string("$get_days days"));
                //$end_date = date_sub($end_d,date_interval_create_from_date_string("1 days"));
                $enddate = date_format($end_d, 'Y-m-d H:i:s');
                $startdate = date_format($add_minutes_start_date, 'Y-m-d H:i:s');
            }

            // addons calculations
            $get_total_addons_premium_including_tax = $get_total_addons_premium_excluding_tax  = 0;
            if($request->has('addons')){
                count($request->input('addons')) < 1 ? $addons=[] : $addons = DB::table('addon_products')->whereIn('id', $request->input('addons'))->get();

                // get details of selected addon product
                $addon_reference_number = 1;
                $currenttransactionid =   $validated["currenttransactionid"];
                foreach($addons as $addons_data){

                    $addons_premium_including_tax = $addons_premium_excluding_tax = $addon_amounts = $addons_tax_amount = 0;

                    $addons_data->amount != 0 ? $addon_amounts = $addons_data->amount : $addon_amounts = $premiumexcludingtax;
                    $addons_data->rate == 0 ? $addons_premium_excluding_tax = $addon_amounts : $addons_premium_excluding_tax = ($addon_amounts * $addons_data->rate);

                    if($addons_data->id == 3){
                        if($addons_premium_excluding_tax < 49999){
                            $addons_premium_excluding_tax = 50000;
                        }
                    }

                    $addons_tax_amount = ($addons_premium_excluding_tax * $taxrate);


                    $addons_premium_including_tax = $addons_tax_amount + $addons_premium_excluding_tax;

                    $get_total_addons_premium_including_tax = $get_total_addons_premium_including_tax + $addons_premium_including_tax;
                    $get_total_addons_premium_excluding_tax = $get_total_addons_premium_excluding_tax + $addons_premium_excluding_tax;

                    $currenttransactionid =   $validated["currenttransactionid"];

                    $check_addons_av = Addon::where('transaction_id', $currenttransactionid)->where('addon_product_id', $addons_data->id)->count();

                    if($check_addons_av == 0)
                    {
                        $addons_ins = new Addon;
                        $addons_ins->transaction_id = $currenttransactionid;
                        $addons_ins->addon_product_id = $addons_data->id;
                        $addons_ins->addon_reference = $addon_reference_number;
                        $addons_ins->addon_desc = $addons_data->name;
                        $addons_ins->addon_amount = $addon_amounts;
                        $addons_ins->addon_rate = $addons_data->rate;
                        $addons_ins->premium_excluding_tax = $addons_premium_excluding_tax;
                        $addons_ins->premium_excluding_tax_equivalent = $addons_premium_excluding_tax;
                        $addons_ins->premium_including_tax = $addons_premium_including_tax;
                        $addons_ins->tax_code = $taxcode;
                        $addons_ins->tax_rate = $taxrate;
                        $addons_ins->tax_amount =  $addons_tax_amount;

                        $addons_ins->save();
                    }
                    else
                    {
                        $addons_ins = Addon::where('transaction_id', $currenttransactionid)->where('addon_product_id', $addons_data->id)->update([
                            'transaction_id' => $currenttransactionid,
                            'addon_product_id' => $addons_data->id,
                            'addon_reference' => $addon_reference_number,
                            'addon_desc' => $addons_data->name,
                            'addon_amount' => $addon_amounts,
                            'addon_rate' => $addons_data->rate,
                            'premium_excluding_tax' => $addons_premium_excluding_tax,
                            'premium_excluding_tax_equivalent' => $addons_premium_excluding_tax,
                            'premium_including_tax' => $addons_premium_including_tax,
                            'tax_code' => $taxcode,
                            'tax_rate' => $taxrate,
                            'tax_amount' =>  $addons_tax_amount
                        ]);
                    }

                    $addon_reference_number++;

                }
            }
            //end addons calculation

            //get total premiums
            $totalpremiumincludingtax = $get_total_addons_premium_including_tax + $totalpremiumincludingtax;
            $totalpremiumexcludingtax = $totalpremiumexcludingtax + $get_total_addons_premium_excluding_tax;

            //get commission and vuna points for agent only

                $commisionpaid = 0;
                $commisionrate = 0.125;

                $commisionpaid = $totalpremiumexcludingtax * $commisionrate;


            //save data to session

                $response_data = [
                    'tax_code' => $taxcode,
                    'tax_rate' => $taxrate,
                    'total_premium_excluding_tax' => $totalpremiumexcludingtax,
                    'premium_excluding_tax_equivalent' => $premiumexcludingtaxequivalent,
                    'premium_before_discount' => $premiumbeforediscount,
                    'premium_discount' => $premiumdiscount,
                    'premium_after_discount' => $premiumafterdiscount,
                    'premium_excluding_tax' => $premiumexcludingtax,
                    'tax_amount' => $taxamount,
                    'total_premium_including_tax' => $totalpremiumincludingtax,
                    'premium_including_tax' => $premiumincludingtax,
                    'commission_paid' => $commisionpaid
                ];






                //return $currenttransactionid;


            //update transaction
            $trans = Transaction::find($currenttransactionid);
            $trans->update([
                'covernote_start_date'=>$startdate,
                'covernote_end_date'=>$enddate,
                'covernote_desc'=>$covernotedesc,
                'operative_clause'=>$operativeclause,
                'total_premium_excluding_tax'=>$totalpremiumexcludingtax,
                'total_premium_including_tax'=>$totalpremiumincludingtax,
                'sum_insured'=>$suminsured,
                'sum_insured_equivalent'=>$suminsuredequivalent,
                'premium_rate'=>$premiumrate,
                 'premium_before_discount'=>$premiumbeforediscount,
                 'premium_discount'=>$premiumdiscount,
                 'premium_after_discount'=>$premiumafterdiscount,
                  'premium_excluding_tax_equivalent'=>$premiumexcludingtaxequivalent,
                  'premium_including_tax'=>$premiumexcludingtax,
                  'tax_code'=>$taxcode,
                   'tax_rate'=>$taxrate,
                   'tax_amount'=>$taxamount,
                   'premium_excluding_tax'=>$premiumexcludingtax,
                   'durations'=>$duration,
                    'commission_paid'=>$commisionpaid,
                    'commission_rate'=>$commisionrate,
                    'status'=>'Pending',
                    'currency_status'=>$currency_status,
                    'currency_title'=>$currency_title,
                    'currency_rate'=>$currency_rate,
                    'insurer'=>$insurer,
                     'expiry_date'=>$expirydate,
                     'customer_id'=>$customerid,
            ]);

    }else{
        if($premiumrate==0) {
            $subpremium = $minimumamount;
        }else{
            $subamount=($suminsured * $premiumrate) / 100;
            if($subamount > $minimumamount){
                $subpremium = $subamount;
            }else{
                $subpremium = $minimumamount;
            }
        }

        $grandpremiumvalue = 0;
        $pvalues = 0;


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

        $totalpremiumexcludingtax = $pvalues;
        $premiumexcludingtaxequivalent = $pvalues;
        $premiumbeforediscount = $pvalues;
        $premiumdiscount = 0;
        $premiumafterdiscount = $pvalues;
        $premiumexcludingtax = $pvalues;

        $taxamount = $pvalues * $taxrate;

        $totalpremiumincludingtax = $taxamount + $pvalues;
        $premiumincludingtax = $taxamount + $pvalues;

        $commisionpaid=0;
        $commisionrate=0;
        $commrate=0;
        $vunapoint = 0;

        if(Auth::user()->role=="admin"){
            $commisionpaid=0;
            $commisionrate=0;
            $commrate=0;


            if($insurancetypeid==1){//goods in transist commission rate
                $commisionrate =0;
                $commrate=0;
            }else if($insurancetypeid==3){
                $commisionrate =0;
                $commrate=0;
            }else if($insurancetypeid==4){
                $commisionrate =0;
                $commrate=0;
            }else if($insurancetypeid==5){//fire commission rate
                $commisionrate =0;
                $commrate=0;
            }else if($insurancetypeid==6){//marine commission rate
                $commisionrate =0;
                $commrate=0;
            }else{
                $commisionrate =0;
                $commrate=0;
            }
            $commisionpaid =0;
        }elseif(Auth::user()->role=="agent"){

            if($insurancetypeid==1){//goods in transist commission rate
                $commisionrate =0.175;
                $commrate="0.175";
            }else if($insurancetypeid==3){
                $commisionrate =0.175;
                $commrate="0.175";
            }else if($insurancetypeid==4){
                $commisionrate =0.20;
                $commrate="0.20";
            }else if($insurancetypeid==5){//fire commission rate
                $commisionrate =0.25;
                $commrate="0.25";
            }else if($insurancetypeid==6){//marine commission rate
                $commisionrate =0.175;
                $commrate="0.175";
            }else{
                $commisionrate =0.125;
                $commrate=0.125;
            }

            $commisionpaid = $premiumexcludingtax*$commisionrate;
            $vunapoint_c = $premiumexcludingtax/100000;
            $vunapoint = intval($vunapoint_c);

        }


            $response_data = [
                'tax_code' => $taxcode,
                'tax_rate' => $taxrate,
                'total_premium_excluding_tax' => $totalpremiumexcludingtax,
                'premium_excluding_tax_equivalent' => $premiumexcludingtaxequivalent,
                'premium_before_discount' => $premiumbeforediscount,
                'premium_discount' => $premiumdiscount,
                'premium_after_discount' => $premiumafterdiscount,
                'premium_excluding_tax' => $premiumexcludingtax,
                'tax_amount' => $taxamount,
                'total_premium_including_tax' => $totalpremiumincludingtax,
                'premium_including_tax' => $premiumincludingtax,
                'commission_paid' => $commisionpaid
            ];


        $enddate=$startdate="";
        //get start and end date
        if($duration >= 12){

            $start_dates="$startdate"." ".$starttime;
            $end_dates="$startdate"." 23:59:59";
            $date_s=date_create($start_dates);
            $date_e=date_create($end_dates);
            $add_minutes_start_date = date_add($date_s,date_interval_create_from_date_string("5 minutes"));
            $end_d = date_add($date_e,date_interval_create_from_date_string("$duration months"));
            $end_date = date_sub($end_d,date_interval_create_from_date_string("1 days"));
            $enddate = date_format($end_date, 'Y-m-d H:i:s');
            $startdate = date_format($add_minutes_start_date, 'Y-m-d H:i:s');
        }else{
            $get_days = $duration*30;
            $start_dates="$startdate"." ".$starttime;
            $end_dates="$startdate"." 23:59:59";
            $date_s=date_create($start_dates);
            $date_e=date_create($end_dates);
            $add_minutes_start_date = date_add($date_s,date_interval_create_from_date_string("5 minutes"));
            $end_d = date_add($date_e,date_interval_create_from_date_string("$get_days days"));
            //$end_date = date_sub($end_d,date_interval_create_from_date_string("1 days"));
            $enddate = date_format($end_d, 'Y-m-d H:i:s');
            $startdate = date_format($add_minutes_start_date, 'Y-m-d H:i:s');
        }


        $commisionrate = $commrate;

        //other information
        $systemcode = "SYS0003";
        $callbackurl = "http://bimakwik.com/api/covernoteref/resp";
        $insurercompanycode = "IC001";
        $trancompanycode = "IC001";
        $covernotetype = 1;

        $salepoint = $this->getCode("sales");
        $companycode = $this->getCode("company");

        $subjectmatterreference = "HSB001";
        $subjectmatterdesc = "";

        //from user login
        $officername = "";
        if(Auth::user()->role == 'agent'){
            $agents_info = Agent::where('id', Auth::user()->user_id)->first();
            $agents_info_owner = json_decode($agents_info->principal_owner, true);
            $officername = $agents_info_owner['ownerfname'] . " " . $agents_info_owner['ownerlname'];
        }else{
            $officername = "" . Auth::user()->first_name . " ". Auth::user()->last_name . "";
        }
        $officertitle = Auth::user()->role;
        $isfleet = "N";
        //auth

        $created_by = 3; //created by
        $issuing_by = Auth::user()->id;
        $status = "Pending";
        $covernotenmb= rand(1,10).time().date('dHsi');

        //$request->session()->put("currenttransactionid")
        //update transaction
        $updatetransaction =DB::table('transactions')->insertGetId([
            'request_id' => "$requestids",
            'company_code' => "$companycode",
            'system_code' => "$systemcode",
            'callback_url' => "$callbackurl",
            'insurer_company_code' => "$insurercompanycode",
            'tran_company_code' => "$trancompanycode",
            'covernote_type' => $covernotetype,
            'sales_point_code' => "$salepoint",
            'officer_name' => "$officername",
            'officer_title' => "$officertitle",
            'subject_matter_reference' => "$subjectmatterreference",
            'subject_matter_desc' => "$subjectmatterdesc",
            'addon_amount' => "0.00",
            'addon_premium_rate' => "0.00",
            'is_feet' => "$isfleet",
            'insurance_product_id' => $product_code,
            'insurance_coverage_id' => $risk_code,
            'insurance_type_id' => $insurance_type_id,
            'customer_id' => $customerid,
            'branch_id' => $created_by,
            'user_id' => $issuing_by,
            'status' => "$status",
            'commission_paid' => $commisionpaid,
            'commission_rate' => $commisionrate,
            'covernote_start_date' => "$startdate",
            'covernote_end_date' => "$enddate",
            'covernote_desc' => "$covernotedesc",
            'operative_clause' => "$operativeclause",
            'total_premium_excluding_tax' => "$totalpremiumexcludingtax",
            'total_premium_including_tax' => "$totalpremiumincludingtax",
            'sum_insured' => "$suminsured",
            'sum_insured_equivalent' => "$suminsured",
            'premium_rate' => "$premiumrate",
            'premium_before_discount' => "$premiumbeforediscount",
            'premium_discount' => "$premiumdiscount",
            'premium_after_discount' => "$premiumafterdiscount",
            'premium_excluding_tax_equivalent' => "$premiumexcludingtaxequivalent",
            'premium_including_tax' => "$premiumincludingtax",
            'tax_code' => "$taxcode",
            'tax_rate' => "$taxrate",
            'tax_amount' => "$taxamount",
            'premium_excluding_tax' => "$premiumexcludingtax",
            'covernote_number' => "$covernotenmb",
            'durations' => "$duration",
            'image_reference'=>"$imagereference",
            'currency_status' => "$currency_status",
            'currency_title' => "$currency_title",
            'currency_rate' => "$currency_rate",
            'insurer' => $insurer,
            'expiry_date' => $expirydate,
        ]);


                $response_data['transaction_id'] = $updatetransaction;



        }



                $summary = json_decode($this->summaryInsuranceInformation($request)->getContent(), true);
                $response_data['addons'] = @$summary['code'] == 200 ? @$summary['data']['addons'] : [];
                return response()->json($response_data);


    }



    public function summaryInsuranceInformation(Request $request)
    {
        $currenttransactionid =  $request->input("currenttransactionid");

        $addons = DB::table('addons')->where('transaction_id', $currenttransactionid)->get();


        return  response()->json([
                    'addons' => $addons
                ]);
            }


}
