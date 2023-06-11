<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\Helpers\Helper;
use App\Models\Addon;
use App\Models\FleetEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\ArrayToXml\ArrayToXml;

const TIRA_SUCCESS_CODE = 'TIRA001';
const TIRA_SUCCESS_DESC = 'Successful';

class CoverNoteController extends Controller
{
    private $notifier;

    public function __construct(NotificationService $notifier)
    {
        $this->notifier = $notifier;
    }

    public function index()
    {
        return Transaction::all();
    }

    public function getTransaction($id)
    {
        return Transaction::findOrFail($id);
    }

    public function comprensiveCount($id){
        return 30;
    }

    public function coverNoteRefReq($id): JsonResponse
    {
        $trans = $this->getTransaction($id);
        if($trans->insurance_type_id != 2){
        $trans->request_id = generateRequestID();
        //checking if transaction is successful
        if($trans->acknowledgement_status_code == "TIRA001" && $trans->acknowledgement_status_desc == "Successful" && $trans->response_status_code == "TIRA001" && $trans->response_status_desc == "Successful"){
            return response()->json($trans);
        }else{

            if(date_format(date_create($trans->covernote_start_date), 'Y-m-d') == date('Y-m-d'))
            {
                $date = date_format(date_create($trans->covernote_start_date), 'Y-m-d');
                $time = date('H:i:s');
                $date_time = "$date"." "."$time";
                $date_time_cr = date_create($date_time);
                $add_minutes_start_date = date_add($date_time_cr,date_interval_create_from_date_string("1 minutes"));
                $startdate = date_format($add_minutes_start_date, 'Y-m-d H:i:s');

                $CoverNoteStartDate = formatDate($startdate);
            }else{
                $date = date_format(date_create($trans->covernote_start_date), 'Y-m-d');
                $date_time = "$date"." "."00:00:00";
                $date_time_cr = date_create($date_time);
                $add_minutes_start_date = date_add($date_time_cr,date_interval_create_from_date_string("1 minutes"));
                $startdate = date_format($add_minutes_start_date, 'Y-m-d H:i:s');

                $CoverNoteStartDate = formatDate($startdate);
            }

            $content = [
                'CoverNoteHdr' => $this->coverNoteHdr($trans),
                'CoverNoteDtl' => [
                    'CoverNoteNumber' => $trans->covernote_number,
                    'PrevCoverNoteReferenceNumber' => $trans->prev_covernote_reference_number,
                    'SalePointCode' => $trans->sales_point_code,//env('SALES_POINT_CODE')
                    'CoverNoteStartDate' => $CoverNoteStartDate,
                    'CoverNoteEndDate' => formatDate($trans->covernote_end_date),
                    'CoverNoteDesc' => $trans->covernote_desc,
                    'OperativeClause' => $trans->operative_clause,
                    'PaymentMode' => $trans->payment_mode,
                    'CurrencyCode' => $trans->currency_code,
                    'ExchangeRate' => $trans->exchange_rate,
                    'TotalPremiumExcludingTax' => $trans->total_premium_excluding_tax,
                    'TotalPremiumIncludingTax' => $trans->total_premium_including_tax,
                    'CommisionPaid' => $trans->commission_paid,
                    'CommisionRate' => $trans->commission_rate,
                    'OfficerName' => "{$trans->user->first_name} {$trans->user->last_name}",
                    'OfficerTitle' => 'Agent',
                    'ProductCode' => $trans->insuranceProduct->code,
                    'EndorsementType' => $trans->endorsement_type,
                    'EndorsementReason' => $trans->endorsement_reason,
                    'EndorsementPremiumEarned' => $trans->endorsement_premium_earned,
                    'RisksCovered' => $this->risksCovered($trans),
                    'SubjectMattersCovered' => $this->subjectMattersCovered($trans),
                    'CoverNoteAddons' => $this->coverNoteAddons($id),
                    'PolicyHolders' => $this->policyHolders($trans),
                ],
            ];

            $content = parseArrayToXML($content, 'CoverNoteRefReq');

            tiramisSentSave($trans->request_id, $content, $trans->customer->full_name);

            $response = APIClient($content, env('OTHER_COVERNOTE_URL'));

            $trans->acknowledgement_status_code = $response['CoverNoteRefReqAck']['AcknowledgementStatusCode'];
            $trans->acknowledgement_status_desc = $response['CoverNoteRefReqAck']['AcknowledgementStatusDesc'];
            $trans->response_status_code="";
            $trans->response_status_desc="";

            if ($trans->acknowledgement_status_code !== STATUS_CODE){
                $trans->request_id = generateRequestID();
                $trans->covernote_number=rand(1,10).time().date('dHsi');
            }

            if($trans->covernote_type != 3){
                $trans->created_at = date('Y-m-d H:i:s');
                $trans->covernote_start_original_date = $CoverNoteStartDate;
            }
            
            $trans->covernote_start_date = $CoverNoteStartDate;

            $trans->save();

            return response()->json($response);
        
       }
       }
       else
       {
        return 0;
       }
    }

    public function motorCoverNoteRefReq($id): JsonResponse
    {
        $trans = $this->getTransaction($id);
        if($trans->insurance_type_id == 2){
        $trans->request_id = generateRequestID();
        //checking if transaction is successful
         if($trans->acknowledgement_status_code == "TIRA001" && $trans->acknowledgement_status_desc == "Successful"){
            return response()->json($trans);
         }else{


                if(date_format(date_create($trans->covernote_start_date), 'Y-m-d') == date('Y-m-d'))
                {
                    $date = date_format(date_create($trans->covernote_start_date), 'Y-m-d');
                    $time = date('H:i:s');
                    $date_time = "$date"." "."$time";
                    $date_time_cr = date_create($date_time);
                    $add_minutes_start_date = date_add($date_time_cr,date_interval_create_from_date_string("1 minutes"));
                    $startdate = date_format($add_minutes_start_date, 'Y-m-d H:i:s');

                    $CoverNoteStartDate = formatDate($startdate);
                }else{
                    $date = date_format(date_create($trans->covernote_start_date), 'Y-m-d');
                    $date_time = "$date"." "."00:00:00";
                    $date_time_cr = date_create($date_time);
                    $add_minutes_start_date = date_add($date_time_cr,date_interval_create_from_date_string("1 minutes"));
                    $startdate = date_format($add_minutes_start_date, 'Y-m-d H:i:s');

                    $CoverNoteStartDate = formatDate($startdate);
                }

                $content = [
                    'CoverNoteHdr' => $this->coverNoteHdr($trans),
                    'CoverNoteDtl' => [
                        'CoverNoteNumber' => $trans->covernote_number,
                        'PrevCoverNoteReferenceNumber' => $trans->prev_covernote_reference_number,
                        'SalePointCode' => $trans->sales_point_code,//env('SALES_POINT_CODE'),
                        'CoverNoteStartDate' => $CoverNoteStartDate,
                        'CoverNoteEndDate' => formatDate($trans->covernote_end_date),
                        'CoverNoteDesc' => $trans->covernote_desc,
                        'OperativeClause' => $trans->operative_clause,
                        'PaymentMode' => $trans->payment_mode,
                        'CurrencyCode' => $trans->currency_code,
                        'ExchangeRate' => $trans->exchange_rate,
                        'TotalPremiumExcludingTax' => $trans->total_premium_excluding_tax,
                        'TotalPremiumIncludingTax' => $trans->total_premium_including_tax,
                        'CommisionPaid' => $trans->commission_paid,
                        'CommisionRate' => $trans->commission_rate,
                        'OfficerName' => "{$trans->user->first_name} {$trans->user->last_name}",
                        'OfficerTitle' => 'Agent',
                        'ProductCode' => $trans->insuranceProduct->code,
                        'EndorsementType' => $trans->endorsement_type,
                        'EndorsementReason' => $trans->endorsement_reason,
                        'EndorsementPremiumEarned' => $trans->endorsement_premium_earned,
                        'RisksCovered' => $this->risksCovered($trans),
                        'SubjectMattersCovered' => $this->subjectMattersCovered($trans),
                        'CoverNoteAddons' => $this->coverNoteAddons($id),
                        'PolicyHolders' => $this->policyHolders($trans),
                        'MotorDtl' => $this->motorDtl($trans),
                    ],
                ];

                $content = parseArrayToXML($content, 'MotorCoverNoteRefReq');

                tiramisSentSave($trans->request_id, $content, $trans->customer->full_name.'-'.$trans->registration_number);

                $response = APIClient($content, env('MOTOR_COVERNOTE_URL'));

                $trans->acknowledgement_status_code = $response['MotorCoverNoteRefReqAck']['AcknowledgementStatusCode'];
                $trans->acknowledgement_status_desc = $response['MotorCoverNoteRefReqAck']['AcknowledgementStatusDesc'];
                $trans->response_status_code="";
                $trans->response_status_desc="";
                
                if ($trans->acknowledgement_status_code !== STATUS_CODE){
                    $trans->request_id = generateRequestID();
                    $trans->covernote_number=rand(1,10).time().date('dHsi');
                }

                if($trans->covernote_type != 3){
                    $trans->created_at = date('Y-m-d H:i:s');
                    $trans->covernote_start_original_date = $CoverNoteStartDate;
                }

                $trans->covernote_start_date = $CoverNoteStartDate;

                $trans->save();
                return response()->json($response);
        }
        }
        else
        {
            return 0;
        }
    }



    public function motorCoverNoteFleetRefReq($fleetid)
    {
        $fleet = DB::table('transactions')->where('fleet_id_entry', $fleetid)->get();
        foreach($fleet as $fleet_data){
            $trans = $this->getTransaction($fleet_data->id);
            $trans->request_id = generateRequestID();
            //checking if transaction is successful
            if($trans->acknowledgement_status_code != "TIRA001" && $trans->acknowledgement_status_desc != "Successful" && $trans->response_status_code != "TIRA001" && $trans->response_status_desc != "Successful"){

                    if(date_format(date_create($trans->covernote_start_date), 'Y-m-d') == date('Y-m-d'))
                    {
                        $date = date_format(date_create($trans->covernote_start_date), 'Y-m-d');
                        $time = date('H:i:s');
                        $date_time = "$date"." "."$time";
                        $date_time_cr = date_create($date_time);
                        $add_minutes_start_date = date_add($date_time_cr,date_interval_create_from_date_string("1 minutes"));
                        $startdate = date_format($add_minutes_start_date, 'Y-m-d H:i:s');

                        $CoverNoteStartDate = formatDate($startdate);
                    }else{
                        $date = date_format(date_create($trans->covernote_start_date), 'Y-m-d');
                        $date_time = "$date"." "."00:00:00";
                        $date_time_cr = date_create($date_time);
                        $add_minutes_start_date = date_add($date_time_cr,date_interval_create_from_date_string("1 minutes"));
                        $startdates = date_format($add_minutes_start_date, 'Y-m-d H:i:s');

                        $CoverNoteStartDate = formatDate($startdates);
                    }

                    $content = [
                        'CoverNoteHdr' => $this->coverNoteHdr($trans),
                        'CoverNoteDtl' => [
                            'CoverNoteNumber' => $trans->covernote_number,
                            'PrevCoverNoteReferenceNumber' => $trans->prev_covernote_reference_number,
                            'SalePointCode' => $trans->sales_point_code,//env('SALES_POINT_CODE'),
                            'CoverNoteStartDate' => $CoverNoteStartDate,
                            'CoverNoteEndDate' => formatDate($trans->covernote_end_date),
                            'CoverNoteDesc' => $trans->covernote_desc,
                            'OperativeClause' => $trans->operative_clause,
                            'PaymentMode' => $trans->payment_mode,
                            'CurrencyCode' => $trans->currency_code,
                            'ExchangeRate' => $trans->exchange_rate,
                            'TotalPremiumExcludingTax' => $trans->total_premium_excluding_tax,
                            'TotalPremiumIncludingTax' => $trans->total_premium_including_tax,
                            'CommisionPaid' => $trans->commission_paid,
                            'CommisionRate' => $trans->commission_rate,
                            'OfficerName' => "{$trans->user->first_name} {$trans->user->last_name}",
                            'OfficerTitle' => 'Agent',
                            'ProductCode' => $trans->insuranceProduct->code,
                            'EndorsementType' => $trans->endorsement_type,
                            'EndorsementReason' => $trans->endorsement_reason,
                            'EndorsementPremiumEarned' => $trans->endorsement_premium_earned,
                            'RisksCovered' => $this->risksCovered($trans),
                            'SubjectMattersCovered' => $this->subjectMattersCovered($trans),
                            'CoverNoteAddons' => $this->coverNoteAddons($fleet_data->id),
                            'PolicyHolders' => $this->policyHolders($trans),
                            'MotorDtl' => $this->motorDtl($trans),
                        ],
                    ];

                    $content = parseArrayToXML($content, 'MotorCoverNoteRefReq');

                    tiramisSentSave($trans->request_id, $content, $trans->customer->full_name.'-'.$trans->registration_number);

                    $response = APIClient($content, env('MOTOR_COVERNOTE_URL'));

                    
                    $trans->acknowledgement_status_code = $response['MotorCoverNoteRefReqAck']['AcknowledgementStatusCode'];
                    $trans->acknowledgement_status_desc = $response['MotorCoverNoteRefReqAck']['AcknowledgementStatusDesc'];
                    $trans->response_status_code="";
                    $trans->response_status_desc="";
                    
                    if ($trans->acknowledgement_status_code !== STATUS_CODE){
                        $trans->request_id = generateRequestID();
                        $trans->covernote_number=rand(1,10).time().date('dHsi');
                    }
                    
                    if($trans->covernote_type != 3){
                        $trans->created_at = date('Y-m-d H:i:s');
                        $trans->covernote_start_original_date = $CoverNoteStartDate;
                    }
                    $trans->covernote_start_date = $CoverNoteStartDate;

                    $trans->save();

                    logFleetResponse($response, $fleetid);
                    
            }
        }
        return 0;
    }



    public function nonmotorCoverNoteFleetRefReq($fleetid)
    {
        $fleet = DB::table('transactions')->where('fleet_id_entry', $fleetid)->get();
        foreach($fleet as $fleet_data){
            $trans = $this->getTransaction($fleet_data->id);
            $trans->request_id = generateRequestID();
            //checking if transaction is successful
            if($trans->acknowledgement_status_code != "TIRA001" && $trans->acknowledgement_status_desc != "Successful" && $trans->response_status_code != "TIRA001" && $trans->response_status_desc != "Successful"){

                    if(date_format(date_create($trans->covernote_start_date), 'Y-m-d') == date('Y-m-d'))
                    {
                        $date = date_format(date_create($trans->covernote_start_date), 'Y-m-d');
                        $time = date('H:i:s');
                        $date_time = "$date"." "."$time";
                        $date_time_cr = date_create($date_time);
                        $add_minutes_start_date = date_add($date_time_cr,date_interval_create_from_date_string("1 minutes"));
                        $startdate = date_format($add_minutes_start_date, 'Y-m-d H:i:s');

                        $CoverNoteStartDate = formatDate($startdate);
                    }else{
                        $date = date_format(date_create($trans->covernote_start_date), 'Y-m-d');
                        $date_time = "$date"." "."00:00:00";
                        $date_time_cr = date_create($date_time);
                        $add_minutes_start_date = date_add($date_time_cr,date_interval_create_from_date_string("1 minutes"));
                        $startdates = date_format($add_minutes_start_date, 'Y-m-d H:i:s');

                        $CoverNoteStartDate = formatDate($startdates);
                    }

                    $content = [
                        'CoverNoteHdr' => $this->coverNoteHdr($trans),
                        'CoverNoteDtl' => [
                            'CoverNoteNumber' => $trans->covernote_number,
                            'PrevCoverNoteReferenceNumber' => $trans->prev_covernote_reference_number,
                            'SalePointCode' => $trans->sales_point_code,//env('SALES_POINT_CODE')
                            'CoverNoteStartDate' => $CoverNoteStartDate,
                            'CoverNoteEndDate' => formatDate($trans->covernote_end_date),
                            'CoverNoteDesc' => $trans->covernote_desc,
                            'OperativeClause' => $trans->operative_clause,
                            'PaymentMode' => $trans->payment_mode,
                            'CurrencyCode' => $trans->currency_code,
                            'ExchangeRate' => $trans->exchange_rate,
                            'TotalPremiumExcludingTax' => $trans->total_premium_excluding_tax,
                            'TotalPremiumIncludingTax' => $trans->total_premium_including_tax,
                            'CommisionPaid' => $trans->commission_paid,
                            'CommisionRate' => $trans->commission_rate,
                            'OfficerName' => "{$trans->user->first_name} {$trans->user->last_name}",
                            'OfficerTitle' => 'Agent',
                            'ProductCode' => $trans->insuranceProduct->code,
                            'EndorsementType' => $trans->endorsement_type,
                            'EndorsementReason' => $trans->endorsement_reason,
                            'EndorsementPremiumEarned' => $trans->endorsement_premium_earned,
                            'RisksCovered' => $this->risksCovered($trans),
                            'SubjectMattersCovered' => $this->subjectMattersCovered($trans),
                            'CoverNoteAddons' => $this->coverNoteAddons($fleet_data->id),
                            'PolicyHolders' => $this->policyHolders($trans),
                        ],
                    ];
        
                    $content = parseArrayToXML($content, 'CoverNoteRefReq');

                    tiramisSentSave($trans->request_id, $content, $trans->customer->full_name.'-'.$trans->registration_number);
        
                    $response = APIClient($content, env('OTHER_COVERNOTE_URL'));
        
                    $trans->acknowledgement_status_code = $response['CoverNoteRefReqAck']['AcknowledgementStatusCode'];
                    $trans->acknowledgement_status_desc = $response['CoverNoteRefReqAck']['AcknowledgementStatusDesc'];
                    $trans->response_status_code="";
                    $trans->response_status_desc="";
        
                    if ($trans->acknowledgement_status_code !== STATUS_CODE){
                        $trans->request_id = generateRequestID();
                        $trans->covernote_number=rand(1,10).time().date('dHsi');
                    }
                    
                    if($trans->covernote_type != 3){
                        $trans->created_at = date('Y-m-d H:i:s');
                        $trans->covernote_start_original_date = $CoverNoteStartDate;
                    }
                    
                    $trans->covernote_start_date = $CoverNoteStartDate;
        
                    $trans->save();
        
                   // return response()->json($response);

                    logFleetResponse($response, $fleetid);
                    
            }
        }
        return 0;
    }


    // --------------------------- Transaction Parameters ------------------------------- //
    public function coverNoteHdr(Transaction $trans): array
    {
        return [
            'RequestId' => $trans->request_id,
            'CompanyCode' => env('COMPANY_CODE'),
            'SystemCode' => env('SYSTEM_CODE'),
            'CallBackUrl' => env('TIRAMIS_CALLBACK_URL'),
            'InsurerCompanyCode' => env('INSURER_COMPANY_CODE'),
            'TranCompanyCode' => $trans->company_code,//env('TRAN_COMPANY_CODE'),
            'CoverNoteType' => $trans->covernote_type,
        ];
    }

    public function risksCovered(Transaction $trans): array
    {
        return [
            'RiskCovered' => [
                [
                    'RiskCode' => $trans->insuranceCoverage->code,
                    'SumInsured' => $trans->sum_insured,
                    'SumInsuredEquivalent' => $trans->sum_insured_equivalent,
                    'PremiumRate' => $trans->insuranceCoverage->rate == 0 ? $trans->insuranceCoverage->rate : $trans->insuranceCoverage->rate/100,
                    'PremiumBeforeDiscount' => $trans->premium_before_discount,
                    'PremiumAfterDiscount' => $trans->premium_after_discount,
                    'PremiumExcludingTaxEquivalent' => $trans->premium_excluding_tax_equivalent,
                    'PremiumIncludingTax' => $trans->premium_including_tax,
                    'DiscountsOffered' => $this->coverNoteDiscount($trans),
                    'TaxesCharged' => [
                        'TaxCharged' => [
                            'TaxCode' => $trans->tax_code,
                            'IsTaxExempted' => $trans->is_tax_exempted,
                            'TaxExemptionType' => $trans->tax_exemption_type,
                            'TaxExemptionReference' => $trans->tax_exemption_reference,
                            'TaxRate' => $trans->tax_rate,
                            'TaxAmount' => $trans->tax_amount,
                        ],
                    ],
                ],
            ]
        ];
    }

    public function subjectMattersCovered(Transaction $trans): array
    {
        return [
            'SubjectMatter' => [
                'SubjectMatterReference' => 'HSB001',
                'SubjectMatterDesc' => 'On contents including Domestic items',
            ],
        ];
    }

    public function coverNoteAddons($id): array
    {
        $addons = Addon::where('transaction_id', $id)->get();

        if ($addons->count() > 0) {
            foreach ($addons as $data) {
                $addons_arr[] = [
                        'AddonReference' => $data->addon_reference,
                        'AddonDesc' => $data->addon_desc,
                        'AddonAmount' => $data->addon_amount,
                        'AddonPremiumRate' => $data->addon_rate,
                        'PremiumExcludingTax' => $data->premium_excluding_tax,
                        'PremiumExcludingTaxEquivalent' => $data->premium_excluding_tax_equivalent,
                        'PremiumIncludingTax' => $data->premium_including_tax,
                        'TaxesCharged' => [
                            'TaxCharged' => [
                                'TaxCode' => $data->tax_code,
                                'IsTaxExempted' => $data->is_tax_exempted,
                                'TaxExemptionType' => $data->tax_exemption_type,
                                'TaxExemptionReference' => $data->tax_exemption_reference,
                                'TaxRate' => $data->tax_rate,
                                'TaxAmount' => $data->tax_amount,
                            ],
                        ],
                ];
            }
            return ['CoverNoteAddon' => $addons_arr];
        } else {
            return [];
        }
    }


    //17,336.30

    public function coverNoteDiscount(Transaction $trans): array
    {
        if ($trans->premium_discount > 100) {
                $discount_arr[] = [
                    'DiscountType' => $trans->discount_type,
                    'DiscountRate' => 0.00,
                    'DiscountAmount' => $trans->premium_discount,
                ];
            
            return ['DiscountOffered' => $discount_arr];

        } else {
            return [];
        }
    }

    public function policyHolders(Transaction $trans): array
    {
        return [
            'PolicyHolder' => [
                'PolicyHolderName' => $trans->insurance_type_id == 2 ? $trans->owner_name : $trans->customer->full_name,
                'PolicyHolderBirthDate' => $trans->customer->birth_date,
                'PolicyHolderType' => $trans->customer->customer_type,
                'PolicyHolderIdNumber' => $trans->customer->id_number,
                'PolicyHolderIdType' => $trans->customer->id_type,
                'Gender' => $trans->customer->gender,
                'CountryCode' => $trans->customer->country_code,
                'Region' => $trans->customer->region->name,
                'District' => $trans->customer->district->name,
                'Street' => $trans->customer->street,
                'PolicyHolderPhoneNumber' => $trans->customer->phone_number,
                'PolicyHolderFax' => "",
                'PostalAddress' => $trans->customer->postal_address,
                'EmailAddress' => $trans->customer->email,
            ],
        ];
    }

    public function motorDtl(Transaction $trans): array
    {
        return [
            'MotorCategory' => $trans->motor_category,
            'MotorType' => $trans->motor_type,
            'RegistrationNumber' => $trans->registration_number,
            'ChassisNumber' => $trans->chassis_number,
            'Make' => $trans->make,
            'Model' => $trans->model,
            'ModelNumber' => $trans->model_number,
            'BodyType' => $trans->body_type,
            'Color' => $trans->color,
            'EngineNumber' => $trans->engine_number,
            'EngineCapacity' => $trans->engine_capacity,
            'FuelUsed' => $trans->fuel_used,
            'NumberOfAxles' => $trans->number_of_axles,
            'AxleDistance' => $trans->axle_distance,
            'SittingCapacity' => $trans->sitting_capacity,
            'YearOfManufacture' => $trans->year_of_manufacture,
            'TareWeight' => $trans->tare_weight,
            'GrossWeight' => $trans->gross_weight,
            'MotorUsage' => $trans->motor_usage,
            'OwnerName' => $trans->owner_name,
            'OwnerCategory' => $trans->owner_category,
            'OwnerAddress' => $trans->owner_address,
        ];
    }

    // -------------------------- The rest of APIs ------------------------------------- //
    public function reinsuranceReq(): JsonResponse
    {
        $content = [
            'ReinsuranceHdr' => [
                'RequestId' => generateRequestID(),
                'CompanyCode' => env('COMPANY_CODE'),
                'SystemCode' => env('SYSTEM_CODE'),
                'CallBackUrl' => env('TIRAMIS_CALLBACK_URL'),
                'InsurerCompanyCode' => env('INSURER_COMPANY_CODE'),
                'CoverNoteReferenceNumber' => '10821-05700-39318',
                'PremiumIncludingTax' => 118000.0,
                'CurrencyCode' => 'TZS',
                'ExchangeRate' => 1.00,
                'AuthorizingOfficerName' => 'Maclean Mushi',
                'AuthorizingOfficerTitle' => 'Underwriter',
                'ReinsuranceCategory' => '1',
            ],
            'ReinsuranceDtl' => [
                [
                    'ParticipantCode' => env('INSURER_COMPANY_CODE'),
                    'ParticipantType' => '1',
                    'ReinsuranceForm' => '1',
                    'ReinsuranceType' => '1',
                    'ReBrokerCode' => env('INSURER_COMPANY_CODE'),
                    'BrokerageCommission' => '1',
                    'ReinsuranceCommission' => '0.17',
                    'PremiumShare' => 20060,
                    'ParticipationDate' => formatDate(Carbon::now()->endOfDay()),
                ]
            ],
        ];

        $content = parseArrayToXML($content, 'ReinsuranceReq');

        $response = APIClient($content, env('REINSURANCE_URL'));

        return response()->json($response);
    }

    public function policyReq(): JsonResponse
    {
        $content = [
            'PolicyHdr' => [
                'RequestId' => generateRequestID(),
                'CompanyCode' => env('COMPANY_CODE'),
                'SystemCode' => env('SYSTEM_CODE'),
                'CallBackUrl' => env('TIRAMIS_CALLBACK_URL'),
                'InsurerCompanyCode' => env('INSURER_COMPANY_CODE'),
            ],
            'PolicyDtl' => [
                'PolicyNumber' => 'P/01/2021/1001/0000012',
                'PolicyOperativeClause' => 'THE MOTOR VEHICLE INSURANCE ACT 1961 (CAP 169 RE 2002) (SECTION-7) AND THE MOTOR VEHICLES THIRD PARTY RISKS DECREE 1953 (ZANZIBAR) - SECTION 6',
                'SpecialConditions' => 'VALIDITY OF THIS RISK NOTE IS SUBJECT TO RECEIPT OF PREMIUM BY INSURER PRIOR TO INCEPTION OF RISK & SUBJECT TO REALIZATION OF CHEQUE, WHEREVER APPLICABLE',
                'Exclusions' => 'Depreciation wear and tear mechanical or electrical breakdowns failures or breakages',
                'AppliedCoverNotes' => [
                    'CoverNoteReferenceNumber' => [
                        // '10821-05600-37816',
                        // '10821-05600-37917',
                        '10821-05700-38915'
                    ],
                ],
            ],
        ];

        $content = parseArrayToXML($content, 'PolicyReq');

        $response = APIClient($content, env('POLICY_URL'));

        return response()->json($response);
    }

    public function motorVerificationReq(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'motor_category' => 'required',
            'motor_registration_number' => 'required',
        ]);

        $content = [
            'VerificationHdr' => [
                'RequestId' => generateRequestID(),
                'CompanyCode' => env('COMPANY_CODE'),
                'SystemCode' => env('SYSTEM_CODE')
            ],
            'VerificationDtl' => [
                'MotorCategory' => $validated['motor_category'],
                'MotorRegistrationNumber' => $validated['motor_registration_number'],
                'MotorChassisNumber' => $validated['motor_category'] == 1 ? null : $validated['motor_chassis_number']
            ]
        ];

        $content = parseArrayToXML($content, 'MotorVerificationReq');

        $response = APIClient($content, env('MOTOR_VERIFICATION_URL'));

        return response()->json($response);
    }





    //fleet function
    public function motorCoverNoteRefReqWithFleet($id)
    {
        $trans = $this->getTransaction($id);
        $fleet_entry = FleetEntry::where('transaction_id', $id)->get();

        $content = [
            'CoverNoteHdr' => [
                'RequestId' => $trans->request_id,
                'CompanyCode' => env('COMPANY_CODE'),
                'SystemCode' => env('SYSTEM_CODE'),
                'CallBackUrl' => env('FLEET_CALLBACK_URL'),
                'InsurerCompanyCode' => env('INSURER_COMPANY_CODE'),
                'TranCompanyCode' => env('TRAN_COMPANY_CODE'),
                'CoverNoteType' => $trans->	covernote_type,
            ],
            'CoverNoteDtl' => [
                'FleetHdr' => [
                    'FleetId' => $trans->fleet_id,
                    'FleetType' => $trans->fleet_type,
                    'FleetSize' => $trans->fleet_size,
                    'ComprehensiveInsured' => $this->comprensiveCount($id),
                    'SalePointCode' => $trans->sales_point_code,
                    'CoverNoteStartDate' => formatDate($trans->covernote_start_date),
                    'CoverNoteEndDate' => formatDate($trans->covernote_end_date),
                    'PaymentMode' => $trans->payment_mode,
                    'CurrencyCode' => $trans->currency_code,
                    'ExchangeRate' => $trans->exchange_rate,
                    'TotalPremiumExcludingTax' => $trans->total_premium_excluding_tax,
                    'TotalPremiumIncludingTax' => $trans->total_premium_including_tax,
                    'CommisionPaid' => $trans->commission_paid,
                    'CommisionRate' => $trans->commission_rate,
                    'OfficerName' => $trans->officer_name,
                    'OfficerTitle' => $trans->officer_title,
                    'ProductCode' => $trans->insuranceProduct->code,
                    'PolicyHolders' => $this->policyHolders($trans),
                ],
                'FleetDtl' => $this->fleetDtl($fleet_entry),
            ]
        ];

        $content = parseArrayToXML($content, 'MotorCoverNoteRefReq');

        $response = APIClient($content, env('FLEET_COVER_NOTE_URL'));

        $trans->acknowledgement_status_code = $response['MotorCoverNoteRefReqAck']['AcknowledgementStatusCode'];
        $trans->acknowledgement_status_desc = $response['MotorCoverNoteRefReqAck']['AcknowledgementStatusDesc'];
        $trans->response_status_code="";
        $trans->response_status_desc="";
        
        if ($trans->acknowledgement_status_code !== STATUS_CODE){
             $trans->request_id = generateRequestID();
             $trans->covernote_number=rand(1,10).time().date('dHsi');
        }
        $trans->save();
        return response()->json($response);

    }


    public function fleetDtl($fleet)
    {
        $results = $fleet->map(function ($item, $key) use ($fleet) {
            
            $item->entry = $key + 1;
            $item->save();

            return [
                'FleetEntry' => $item->entry,
                'CoverNoteNumber' => $item->cover_note_number,
                'PrevCoverNoteReferenceNumber' => $item->prev_reference_number,
                'CoverNoteDesc' => $item->desc,
                'OperativeClause' => $item->operative_clause,
                'EndorsementType' => $item->endorsement_type,
                'EndorsementReason' => $item->endorsement_reason,
                'EndorsementPremiumEarned' => $item->endorsement_premium_earned,
                'RisksCovered' => $this->fleetRisksCovered($item),
                'SubjectMattersCovered' => $this->fleetSubjectMatter($item),
                'CoverNoteAddons' => [],
                'MotorDtl' => $this->fleetMotorDetail($item),
            ];
        });

        return $results->toArray();
    }


    public function fleetMotorDetail($motor_detail)
    {
        return [
            'MotorCategory' => $motor_detail->motor_category,
            'MotorType' => $motor_detail->motor_type,
            'RegistrationNumber' => $motor_detail->registration_number,
            'ChassisNumber' => $motor_detail->chassis_number,
            'Make' => $motor_detail->make,
            'Model' => $motor_detail->model,
            'ModelNumber' => $motor_detail->model_number,
            'BodyType' => $motor_detail->body_type,
            'Color' => $motor_detail->color,
            'EngineNumber' => $motor_detail->engine_number,
            'EngineCapacity' => $motor_detail->engine_capacity,
            'FuelUsed' => $motor_detail->fuel_used,
            'NumberOfAxles' => $motor_detail->number_of_axles,
            'AxleDistance' => $motor_detail->axle_distance,
            'SittingCapacity' => $motor_detail->sitting_capacity,
            'YearOfManufacture' => $motor_detail->year_of_manufacture,
            'TareWeight' => $motor_detail->tare_weight,
            'GrossWeight' => $motor_detail->gross_weight,
            'MotorUsage' => $motor_detail->motor_usage,
            'OwnerName' => $motor_detail->owner_name,
            'OwnerCategory' => $motor_detail->owner_category,
            'OwnerAddress' => $motor_detail->owner_address,
        ];
    }

    public function fleetRisksCovered($cover_note)
    {
        return [
            'RiskCovered' => [
                [
                    'RiskCode' => $cover_note->risk_code,
                    'SumInsured' => $cover_note->sum_insured,
                    'SumInsuredEquivalent' => $cover_note->sum_insured_equivalent,
                    'PremiumRate' => $cover_note->premium_rate == 0 ? $cover_note->premium_rate : $cover_note->premium_rate/100,
                    'PremiumBeforeDiscount' => $cover_note->premium_before_discount,
                    'PremiumAfterDiscount' => $cover_note->premium_after_discount,
                    'PremiumExcludingTaxEquivalent' => $cover_note->premium_excluding_tax_equivalent,
                    'PremiumIncludingTax' => $cover_note->premium_including_tax,
                    'DiscountsOffered' => [],
                    'TaxesCharged' => [
                        'TaxCharged' => [
                            'TaxCode' => $cover_note->tax_code,
                            'IsTaxExempted' => $cover_note->is_tax_exempted,
                            'TaxExemptionType' => $cover_note->tax_exemption_type,
                            'TaxExemptionReference' => $cover_note->tax_exemption_reference,
                            'TaxRate' => $cover_note->tax_rate,
                            'TaxAmount' => $cover_note->tax_amount,
                        ]
                    ]
                ]
            ],
        ];
    }

    public function fleetSubjectMatter($cover_note)
    {
        return [
            'SubjectMatter' => [
                'SubjectMatterReference' => $cover_note->subject_matter_reference,
                'SubjectMatterDesc' => $cover_note->subject_matter_desc,
            ]
        ];
    }


    public function processFleetCallback(Request $request)
    {
        $callback = $this->parseXMLtoArray($request->getContent());

        if ($callback['MotorCoverNoteRefRes']['FleetResHdr']['FleetStatusCode'] == TIRA_SUCCESS_CODE) {
            foreach ($callback['MotorCoverNoteRefRes']['FleetResDtl'] as $item) {
                $fleet_entry = FleetEntry::firstWhere('cover_note_number', $item['CoverNoteNumber']);
        
                if ($fleet_entry) {
                    $fleet_entry->response_id = $callback['MotorCoverNoteRefRes']['FleetResHdr']['ResponseId'];
                    $fleet_entry->response_status_code = $item['ResponseStatusCode'];
                    $fleet_entry->response_status_desc = $item['ResponseStatusDesc'];
                    $fleet_entry->response_timestamp = now();
        
                    switch ($item['ResponseStatusCode']) {
                        case TIRA_SUCCESS_CODE :
                            $fleet_entry->reference_number = $item['CoverNoteReferenceNumber'];
                            $fleet_entry->prev_reference_number = $item['CoverNoteReferenceNumber'];
                            $fleet_entry->sticker_number = $item['StickerNumber'];
                            break;
                        default:
                            break;
                    }
                    $fleet_entry->save();
                }
            }
        
        }

        unset($callback['MsgSignature']);
        unset($callback['MotorCoverNoteRefRes']['FleetResDtl']);

        $acknowledgement = [
            'AcknowledgementId' => "ACK" . time() . "OTT",
            'ResponseId' => $callback['MotorCoverNoteRefRes']['FleetResHdr']['ResponseId'],
            'AcknowledgementStatusCode' => TIRA_SUCCESS_CODE,
            'AcknowledgementStatusDesc' => TIRA_SUCCESS_DESC,
        ];

        $acknowledgement = $this->parseArrayToXML($acknowledgement, "MotorCoverNoteRefResAck");
        $acknowledgement = "<TiraMsg>{$acknowledgement}<MsgSignature>" . $this->sign($acknowledgement) . "</MsgSignature></TiraMsg>";

        return response($acknowledgement, 200, ['Content-Type' => 'application/xml']);
    }


    public function parseArrayToXML(array $data, string $rootElement): string
    {
        try {
            return (new ArrayToXml($data, $rootElement))->dropXmlDeclaration()->prettify()->toXml();
        } catch (\DOMException $e) {
            return false;
        }
    }

    public function parseXMLToArray($data)
    {
        return json_decode(json_encode((array)simplexml_load_string($data)), 1);
    }
}
