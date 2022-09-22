<?php


namespace Esijafari2012\ParsianPay;

/**
 * Class ParsianIPG
 * @package Esijafari2012\ParsianPay
 */
class ParsianIPG
{

    protected $PIN;
    private   $service;
    private   $BASE_TARGET_URL   = "https://pec.shaparak.ir/pecpaymentgateway/default.aspx";




    public function startPayment($transactionId, $amount, $callbackUrl) {

        $req = new PinPaymentRequest();

        $req->amount      = $amount;
        $req->orderId     = $transactionId;
        $req->pin         = $this->PIN;
        $req->callbackUrl = $callbackUrl;
        $req->authority   = 0;
        $req->status      = 1;

        try {
            $result = $this->service->PinPaymentRequest($req);
//            print_r($result);
        } catch (Exception $e) {
            $result         = new PinPaymentRequestResponse();
            $result->status = -1;
        }

        $status    = $result->status;
        $authority = $result->authority;

        $payment = new PaymentResponse();
        $payment->setTransactionId($transactionId);
        $this->errorCode = $status;
        if ($status === 0 && $authority != -1) {
            // Successful
            $payment->setIsSuccessful(TRUE);
            $payment->setReferenceId($authority);
            $payment->setTargetUrl($this->BASE_TARGET_URL . "?au={$authority}");
        } else {
            $payment->setIsSuccessful(FALSE);
        }

        return $payment;
    }

    public function isPaymentValid($request) {
        $this->errorCode = $request['rs'];

        $isValid = $request['rs'] === 0 && $request['au'] != -1;
        $res     = new ValidationResponse();
        $res->setValid($isValid);
        $res->setReferenceId($request['au']);

        return $res;
    }

    public function verify($transactionId, $referenceId) {
        $req = new PaymentEnquiry();


        $req->pin           = $this->PIN;
        $req->authority     = $referenceId;
        $req->status        = 1;
        $req->invoiceNumber = 0;

        try {
            $result = $this->service->PaymentEnquiry($req);
        } catch (Exception $e) {
            $result         = new PinPaymentEnquiryResponse();
            $result->status = -1;
        }
        $this->errorCode = $result->status;

        $status = $result->status === 0 && $result->invoiceNumber != -1;

        $res = new VerificationResponse();
        $res->setSuccessful($status);
        $res->setStatus($result->status);
        $res->setInvoiceNumber($result->invoiceNumber);

        return $res;

    }
}