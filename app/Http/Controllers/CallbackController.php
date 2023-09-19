<?php

namespace App\Http\Controllers;

use App\Models\ClaimAssessment;
use App\Models\ClaimIntimation;
use App\Models\ClaimNotification;
use App\Models\ClaimPayment;
use App\Models\ClaimRejection;
use App\Models\DischargeVoucher;
use App\Models\Transaction;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CallbackController extends Controller
{
    private $notifier;

    public function __construct(NotificationService $notifier)
    {
        $this->notifier = $notifier;
    }

    public function __invoke(Request $request)
    {
        $res = parseXMLtoArray($request->getContent());
        unset($res['MsgSignature']);

        logToCustom($res);

        //discordBot($res);

        switch (array_key_first($res)) {
            case 'CoverNoteRefRes':
                return $this->coverNoteRefRes($res);
            case 'MotorCoverNoteRefRes':
                return $this->motorCoverNoteRefRes($res);
            case 'ReinsuranceRes':
                return $this->reinsuranceRes($res);
            case 'PolicyRes':
                return $this->policyRes($res);
            case 'MotorVerificationRes':
                return $this->motorVerificationRes($res);
            case 'ClaimNotificationRefRes':
                return $this->claimNotificationRefRes($res);
            case 'ClaimIntimationRes':
                return $this->claimIntimationRes($res);
            case 'ClaimAssessmentRes':
                return $this->claimAssessmentReq($res);
            case 'DischargeVoucherRes':
                return $this->dischargeVoucherRes($res);
            case 'ClaimPaymentRes':
                return $this->claimPaymentRes($res);
            case 'ClaimRejectionRes':
                return $this->claimRejectionRes($res);
            default:
                break;
        }
    }

    public function coverNoteRefRes($res)
    {
        $trans = Transaction::whereRequestId($res['CoverNoteRefRes']['RequestId'])->firstOrFail();

        switch ($res['CoverNoteRefRes']['ResponseStatusCode']) {
            case 'TIRA001': // New Covernote
                $trans->response_status_code = $res['CoverNoteRefRes']['ResponseStatusCode'];
                $trans->response_status_desc = $res['CoverNoteRefRes']['ResponseStatusDesc'];
                $trans->covernote_reference_number = $res['CoverNoteRefRes']['CoverNoteReferenceNumber'];
                $trans->prev_covernote_reference_number = $res['CoverNoteRefRes']['CoverNoteReferenceNumber'];
                $trans->status = "Success";
                $this->notifier->responseMessage($trans);
                break;
            case 'TIRA090' :
                if($trans->response_status_code != "TIRA001"){
                        $trans->response_status_code = $res['MotorCoverNoteRefRes']['ResponseStatusCode'];
                        $trans->response_status_desc = $res['MotorCoverNoteRefRes']['ResponseStatusDesc'];
                }
                break;
    
            case 'TIRA024' :
                if($trans->response_status_code != "TIRA001"){
                        $trans->response_status_code = $res['MotorCoverNoteRefRes']['ResponseStatusCode'];
                        $trans->response_status_desc = $res['MotorCoverNoteRefRes']['ResponseStatusDesc'];
                }
                break;

            default:
                // $this->notifier->slackLogger($trans);
                $trans->response_status_code = $res['CoverNoteRefRes']['ResponseStatusCode'];
                $trans->response_status_desc = $res['CoverNoteRefRes']['ResponseStatusDesc'];
                $trans->request_id = generateRequestID();
                break;
        }

        $trans->save();

        return $this->resAck($res['CoverNoteRefRes']['ResponseId'], 'CoverNoteRefResAck');
    }

    public function motorCoverNoteRefRes($res)
    {
        $trans = Transaction::whereRequestId($res['MotorCoverNoteRefRes']['RequestId'])->firstOrFail();
        switch ($res['MotorCoverNoteRefRes']['ResponseStatusCode']) {
            case 'TIRA001' :
                $trans->response_status_code = $res['MotorCoverNoteRefRes']['ResponseStatusCode'];
                $trans->response_status_desc = $res['MotorCoverNoteRefRes']['ResponseStatusDesc'];
                $trans->covernote_reference_number = $res['MotorCoverNoteRefRes']['CoverNoteReferenceNumber'];
                $trans->prev_covernote_reference_number = $res['MotorCoverNoteRefRes']['CoverNoteReferenceNumber'];
                $trans->sticker_number = $res['MotorCoverNoteRefRes']['StickerNumber'];
                $trans->status = "Success";

                $this->notifier->responseMessage($trans);
                break;

            case 'TIRA090' :
                if($trans->response_status_code != "TIRA001"){
                    $trans->response_status_code = $res['MotorCoverNoteRefRes']['ResponseStatusCode'];
                    $trans->response_status_desc = $res['MotorCoverNoteRefRes']['ResponseStatusDesc'];
                }
                break;

            case 'TIRA024' :
                if($trans->response_status_code != "TIRA001"){
                    $trans->response_status_code = $res['MotorCoverNoteRefRes']['ResponseStatusCode'];
                    $trans->response_status_desc = $res['MotorCoverNoteRefRes']['ResponseStatusDesc'];
                }
                break;

            default:
                 $trans->response_status_code = $res['MotorCoverNoteRefRes']['ResponseStatusCode'];
                 $trans->response_status_desc = $res['MotorCoverNoteRefRes']['ResponseStatusDesc'];
                 $trans->request_id = generateRequestID();
                break;
        }

        $trans->save();

        return $this->resAck($res['MotorCoverNoteRefRes']['ResponseId'], 'MotorCoverNoteRefResAck');

    }

    public function reinsuranceRes($res)
    {
        $res['ReinsuranceRes']['RequestId'];
        $res['ReinsuranceRes']['ResponseStatusCode'];
        $res['ReinsuranceRes']['ResponseStatusDesc'];

        return $this->resAck($res['ReinsuranceRes']['ResponseId'], 'ReinsuranceResAck');
    }

    public function policyRes($res)
    {
        $res['PolicyRes']['RequestId'];
        $res['PolicyRes']['ResponseStatusCode'];
        $res['PolicyRes']['ResponseStatusDesc'];

        return $this->resAck($res['PolicyRes']['ResponseId'], 'PolicyResAck');
    }

    public function motorVerificationRes($res)
    {
        $res['MotorVerificationRes']['VerificationHdr']['RequestId'];
        $res['MotorVerificationRes']['VerificationHdr']['ResponseStatusCode'];
        $res['MotorVerificationRes']['VerificationHdr']['ResponseStatusDesc'];

        $res['MotorVerificationRes']['VerificationDtl'];
        // VerificationDtl
        [
            'MotorCategory' => '1',
            'RegistrationNumber' => 'T241QWA',
            'ChassisNumber' => 'NCP314345436334',
            'Make' => 'Toyota',
            'Model' => 'IST',
            'ModelNumber' => 'TA232353455',
            'BodyType' => 'Station Wagon',
            'Color' => 'Blue',
            'EngineNumber' => '2423253535',
            'EngineCapacity' => '2300',
            'FuelUsed' => 'Petrol',
            'NumberOfAxles' => '3',
            'AxleDistance' => '',
            'SittingCapacity' => '',
            'YearOfManufacture' => '2001',
            'TareWeight' => '2000',
            'GrossWeight' => '2000',
            'MotorUsage' => 'Private',
            'MotorDesc' => 'Light Passenger Vehicle (Less than 12 persons)',
            'OwnerName' => 'Juma Wamugamba',
            'OwnerCategory' => '1',
        ];

        return $this->resAck($res['MotorVerificationRes']['VerificationHdr']['ResponseId'], 'MotorVerificationResAck');
    }

    public function claimNotificationRefRes($res)
    {
        $claim_notification = ClaimNotification::whereRequestId($res['ClaimNotificationRefRes']['RequestId'])->firstOrFail();
        $claim_notification->response_status_code = $res['ClaimNotificationRefRes']['ResponseStatusCode'];
        $claim_notification->response_status_desc = $res['ClaimNotificationRefRes']['ResponseStatusDesc'];

        if ($res['ClaimNotificationRefRes']['ResponseStatusCode'] === STATUS_CODE) {
            $claim_notification->reference_number = $res['ClaimNotificationRefRes']['ClaimReferenceNumber'];
        }
        else {
             $claim_notification->request_id = generateRequestID();
        }

        $claim_notification->save();

        return $this->resAck($res['ClaimNotificationRefRes']['ResponseId'], 'ClaimNotificationRefResAck');
    }

    public function claimIntimationRes($res)
    {
        $claim_intimation = ClaimIntimation::whereRequestId($res['ClaimIntimationRes']['RequestId'])->firstOrFail();
        $claim_intimation->response_status_code = $res['ClaimIntimationRes']['ResponseStatusCode'];
        $claim_intimation->response_status_desc = $res['ClaimIntimationRes']['ResponseStatusDesc'];

    if($res['ClaimIntimationRes']['ResponseStatusCode'] !== STATUS_CODE) $claim_intimation->request_id = generateRequestID();

        $claim_intimation->save();

        return $this->resAck($res['ClaimIntimationRes']['ResponseId'], 'ClaimIntimationResAck');
    }

    public function claimAssessmentReq($res)
    {
        $claim_assessment = ClaimAssessment::whereRequestId($res['ClaimAssessmentRes']['RequestId'])->firstOrFail();
        $claim_assessment->response_status_code = $res['ClaimAssessmentRes']['ResponseStatusCode'];
        $claim_assessment->response_status_desc = $res['ClaimAssessmentRes']['ResponseStatusDesc'];

        if ($res['ClaimAssessmentRes']['ResponseStatusCode'] !== STATUS_CODE) $claim_assessment->request_id = generateRequestID();

        $claim_assessment->save();

        return $this->resAck($res['ClaimAssessmentRes']['ResponseId'], 'ClaimAssessmentResAck');
    }

    public function dischargeVoucherRes($res)
    {
        $discharge_voucher = DischargeVoucher::whereRequestId($res['DischargeVoucherRes']['RequestId'])->firstOrFail();
        $discharge_voucher->response_status_code = $res['DischargeVoucherRes']['ResponseStatusCode'];
        $discharge_voucher->response_status_desc = $res['DischargeVoucherRes']['ResponseStatusDesc'];

        if($res['DischargeVoucherRes']['ResponseStatusCode'] !== STATUS_CODE) $discharge_voucher->request_id = generateRequestID();

        $discharge_voucher->save();

        return $this->resAck($res['DischargeVoucherRes']['ResponseId'], 'DischargeVoucherResAck');
    }

    public function claimPaymentRes($res)
    {
        $claim_payment = ClaimPayment::whereRequestId($res['ClaimPaymentRes']['RequestId'])->firstOrFail();
        $claim_payment->response_status_code = $res['ClaimPaymentRes']['ResponseStatusCode'];
        $claim_payment->response_status_desc = $res['ClaimPaymentRes']['ResponseStatusDesc'];

        if($res['ClaimPaymentRes']['ResponseStatusCode'] !== STATUS_CODE) $claim_payment->request_id = generateRequestID();

        $claim_payment->save();

        return $this->resAck($res['ClaimPaymentRes']['ResponseId'], 'ClaimPaymentResAck');
    }

    public function claimRejectionRes($res)
    {
        $claim_rejection = ClaimRejection::whereRequestId($res['ClaimRejectionRes']['RequestId'])->firstOrFail();
        $claim_rejection->response_status_code = $res['ClaimRejectionRes']['ResponseStatusCode'];
        $claim_rejection->response_status_desc = $res['ClaimRejectionRes']['ResponseStatusDesc'];

        if($res['ClaimRejectionRes']['ResponseStatusCode'] !== STATUS_CODE) $claim_rejection->request_id = generateRequestID();

        $claim_rejection->save();

        return $this->resAck($res['ClaimRejectionRes']['ResponseId'], 'ClaimRejectionResAck');
    }

    public function resAck($response_id, $tag)
    {
        $content = [
            'AcknowledgementId' => generateAcknowledgementID(),
            'ResponseId' => $response_id,
            'AcknowledgementStatusCode' => STATUS_CODE,
            'AcknowledgementStatusDesc' => STATUS_DESCRIPTION,
        ];

        $content = parseArrayToXML($content, $tag);

        return xmlResponseHandler($content);
    }

    // public function sendEmailCover($id, $name, $type, $trans){
    //     {

    //        Mail::send('mail.send-cover-note', ['id' => $id, "name" => $name, "type" => $type], function ($m) use ($trans) {
    //             $m->from('sanlamagentportal@vusha.co.tz', 'Sanlam General Insurance Agent Portal');
    
    //             $m->to($trans->email, $trans->username)->subject('Sanlam General Insurance Agent Portal Login Credential');
    //         });
    //     }
    // }

}
