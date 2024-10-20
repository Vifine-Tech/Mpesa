<?php

class mpesa
{
    public $consumer_key;
    public $consumer_secret;
    public $passkey;
    public $headoffice;
    public $shortcode;
    public $type = 4;
    public $initiator;
    public $password;
    public $reference = '';
    public $signature;
    public $token;
    private $url;

    function __construct($data)
    {
        $this->consumer_key = $data['consumer_key'];
        $this->consumer_secret = $data['consumer_secret'];
        $this->passkey = $data['passkey'];
        $this->headoffice = $data['headoffice'];
        $this->shortcode = $data['shortcode'];
        $this->type = ($data['shortcode'] == '174379' || $data['shortcode'] == $data['headoffice']) ? 4 : 2;
        $this->initiator = $data['initiator'];
        $this->password = $data['password'];
        $this->reference = $data['reference'];
        $this->url = ($data['shortcode'] == '174379' || $data['shortcode'] == '600000' || $data['shortcode'] == '600998') ? 'https://api.safaricom.co.ke' : 'https://sandbox.safaricom.co.ke';
    }

    // Generate Access Token
    public function accesstoken()
    {
        $credentials = base64_encode($this->consumer_key . ':' . $this->consumer_secret);
        $response = $this->sendCurlRequest($this->url . '/oauth/v1/generate?grant_type=client_credentials', 'GET', false, null, $credentials);

        if ($response['success'] && isset($response['response']['access_token'])) {
            $this->token = $response['response']['access_token'];
            return $this->token;
        }
        return false;
    }

    // Send a cURL Request
    public function sendCurlRequest($url, $method = 'GET', $header = false, $body = null, $credentials = null)
    {
        $headers = [];
        if ($header) {
            $accessToken = $this->accesstoken();
            $headers = array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken
            );
        } elseif ($credentials) {
            $headers = array(
                'Authorization: Basic ' . $credentials
            );
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if (!empty($body) && strtoupper($method) !== 'GET') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false) {
            return ['success' => false, 'error' => curl_error($ch)];
        }

        return [
            'success' => true,
            'response' => json_decode($response, true),
            'httpCode' => $httpCode
        ];
    }

    // M-Pesa STK Push
    public function MPesaExpressSTK($amount, $phone, $reference, $trx_desc = "Transaction", $remark = "Transaction")
    {
        $phone = '254' . substr($phone, -9); // Ensure phone format is correct
        $timestamp = date('YmdHis');
        $password = base64_encode($this->headoffice . $this->passkey . $timestamp);

        $post_data = array(
            'BusinessShortCode' => $this->headoffice,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => ($this->type == 4) ? 'CustomerPayBillOnline' : 'CustomerBuyGoodsOnline',
            'Amount' => round($amount),
            'PartyA' => $phone,
            'PartyB' => $this->shortcode,
            'PhoneNumber' => $phone,
            'CallBackURL' => $this->callback_url,
            'AccountReference' => empty($this->reference) ? $reference : $this->reference,
            'TransactionDesc' => $trx_desc,
            'Remark' => $remark,
        );

        $response = $this->sendCurlRequest($this->url . '/mpesa/stkpush/v1/processrequest', 'POST', true, $post_data);
        return $response;
    }

    // M-Pesa Transaction Status Query
    public function transactionStatus($transactionId, $remarks = 'Transaction Status Check', $occasion = 'N/A')
    {
        $post_data = [
            'Initiator' => $this->initiator,
            'SecurityCredential' => $this->password,
            'CommandID' => 'TransactionStatusQuery',
            'TransactionID' => $transactionId,
            'PartyA' => $this->shortcode,
            'IdentifierType' => $this->type,
            'ResultURL' => $this->callback_url,
            'QueueTimeOutURL' => $this->callback_url,
            'Remarks' => $remarks,
            'Occasion' => $occasion
        ];

        return $this->sendCurlRequest($this->url . '/mpesa/transactionstatus/v1/query', 'POST', true, $post_data);
    }

    // M-Pesa Account Balance
    public function accountBalance($remarks = 'Account Balance Check')
    {
        $post_data = [
            'Initiator' => $this->initiator,
            'SecurityCredential' => $this->password,
            'CommandID' => 'AccountBalance',
            'PartyA' => $this->shortcode,
            'IdentifierType' => $this->type,
            'Remarks' => $remarks,
            'QueueTimeOutURL' => $this->callback_url,
            'ResultURL' => $this->callback_url
        ];

        return $this->sendCurlRequest($this->url . '/mpesa/accountbalance/v1/query', 'POST', true, $post_data);
    }

    // M-Pesa B2C Payment Request
    public function B2CPayment($amount, $receiver, $remarks = 'B2C Payment')
    {
        $post_data = [
            'InitiatorName' => $this->initiator,
            'SecurityCredential' => $this->password,
            'CommandID' => 'BusinessPayment',
            'Amount' => $amount,
            'PartyA' => $this->shortcode,
            'PartyB' => $receiver,
            'Remarks' => $remarks,
            'QueueTimeOutURL' => $this->callback_url,
            'ResultURL' => $this->callback_url,
            'Occasion' => 'N/A'
        ];

        return $this->sendCurlRequest($this->url . '/mpesa/b2c/v1/paymentrequest', 'POST', true, $post_data);
    }

    // Reversal Transaction
    public function reversal($transactionId, $amount, $receiverParty, $remarks = 'Reversal')
    {
        $post_data = [
            'Initiator' => $this->initiator,
            'SecurityCredential' => $this->password,
            'CommandID' => 'TransactionReversal',
            'TransactionID' => $transactionId,
            'Amount' => $amount,
            'ReceiverParty' => $receiverParty,
            'RecieverIdentifierType' => '11',
            'ResultURL' => $this->callback_url,
            'QueueTimeOutURL' => $this->callback_url,
            'Remarks' => $remarks,
            'Occasion' => 'N/A'
        ];

        return $this->sendCurlRequest($this->url . '/mpesa/reversal/v1/request', 'POST', true, $post_data);
    }

    // Register URL
    public function registerURL($confirmationURL, $validationURL, $responseType = 'Completed')
    {
        $post_data = [
            'ShortCode' => $this->shortcode,
            'ResponseType' => $responseType,
            'ConfirmationURL' => $confirmationURL,
            'ValidationURL' => $validationURL
        ];

        return $this->sendCurlRequest($this->url . '/mpesa/c2b/v1/registerurl', 'POST', true, $post_data);
    }

    // Dynamic QR Generation
    public function dynamicQR($merchantName, $refNo, $amount, $trxCode, $cpi, $size)
    {
        $post_data = [
            'MerchantName' => $merchantName,
            'RefNo' => $refNo,
            'Amount' => $amount,
            'TrxCode' => $trxCode,
            'CPI' => $cpi,
            'Size' => $size
        ];

        return $this->sendCurlRequest($this->url . '/mpesa/qrcode/v1/generate', 'POST', true, $post_data);
    }
}
