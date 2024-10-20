<?php
namespace VICTORSDK\Entities;

use VICTORSDK\Helpers\CurlRequest;

class Mpesa
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
    public $url;
    public $stk_callback_url;
    public $transaction_status_result_url;
    public $transaction_status_quetimeouturl_url;
    public $account_ballance_queuetimeout_url;
    public $account_ballance_result_ur;
    public $b2c_queuetimeout_url;
    public $b2c_result_url;
    public $reversal_result_url;
    public $reversal_queutimeout_url;
    public $SenderIdentifierType;
    public $RecieverIdentifierType;
    public $TaxRemittance_queuetimeout_url;
    public $TaxRemittance_result_url;
    public $busineesspaybill_queuetimeout_url;
    public $busineesspaybill_result_url;
    public $business_buygoods_queuetimeout_url;
    public $business_buygoods_result_url;
    public $b2c_top_up_queuetimeout_url;
    public $b2c_top_up_result_url;
    public $optin_callbackurl;
    public $changeDetails_callbackurl;
    public $ratiba_callBackURL;
    public $b2b_express_callbackUrl;
    public $cred;
    public $initiator_password;

    public function __construct($data)
    {

        $propertyMap = [
            'consumer_key', 'consumer_secret', 'passkey', 'headoffice', 'shortcode',
            'initiator', 'password', 'reference', 'stk_callback_url',
            'transaction_status_result_url', 'transaction_status_quetimeouturl_url',
            'account_ballance_queuetimeout_url', 'account_ballance_result_ur',
            'b2c_queuetimeout_url', 'b2c_result_url', 'reversal_result_url',
            'reversal_queutimeout_url', 'SenderIdentifierType', 'RecieverIdentifierType',
            'TaxRemittance_queuetimeout_url', 'TaxRemittance_result_url',
            'busineesspaybill_queuetimeout_url', 'busineesspaybill_result_url',
            'business_buygoods_queuetimeout_url', 'business_buygoods_result_url',
            'b2c_top_up_queuetimeout_url', 'b2c_top_up_result_url', 'optin_callbackurl',
            'changeDetails_callbackurl', 'ratiba_callBackURL', 'b2b_express_callbackUrl',
            'initiator_password'
        ];

        foreach ($propertyMap as $property) {
            if (isset($data[$property])) {
                $this->$property = $data[$property];
            }
        }

        $this->type = (isset($data['shortcode']) && ($data['shortcode'] == '174379' || $data['shortcode'] == $data['headoffice'])) ? 4 : 2;
        $this->url = (isset($data['shortcode']) && in_array($data['shortcode'], ['174379', '600000', '600998'])) ? 'https://sandbox.safaricom.co.ke' : 'https://api.safaricom.co.ke';
        $this->setCred();

        $this->accesstoken();
    }

    public function accesstoken()
    {
        $credentials = base64_encode($this->consumer_key . ':' . $this->consumer_secret);
        $response = CurlRequest::send($this->url . '/oauth/v1/generate?grant_type=client_credentials', 'GET', false, null, $credentials);

        if ($response['success'] && isset($response['response']['access_token'])) {
            $this->token = $response['response']['access_token'];
            return $this->token;
        }
        return false;
    }

    public function send($url, $method = 'GET', $header = true, $body = null)
    {
        if (!$this->token) {
            $this->accesstoken();
        }
        
        return CurlRequest::send($url, $method, $header, $body, $this->token);
    }


    public function setCred(){

        if($data['shortcode'] == '174379'){

            $pubkey=File::get(__DIR__.'/cert/sandbox.cer');
        }else{
            $pubkey=File::get(__DIR__.'/cert/production.cer');

        }

        openssl_public_encrypt($this->initiator_password, $output, $pubkey, OPENSSL_PKCS1_PADDING);

        $this->password = base64_encode($output);
    }

    public function stk($amount, $phone, $reference, $trx_desc = "Transaction", $remark = "Transaction")
    {
        $MPesaExpress = new MPesaExpress();
        $response = $MPesaExpress->request($amount, $phone, $reference, $trx_desc, $remark);

        $finalresponse = [];

        if ($response['status']) {
            $finalresponse = $response['response'];
        }else{
            $finalresponse = $response['response'];
        }

        return $finalresponse;

    }


    public function ratiba($StandingOrderName, $StartDate, $EndDate, $Amount, $phonenumber, $AccountReference, $TransactionDesc, $Frequency)
    {
        $MPesaRatiba = new MPesaRatiba();
        $response = $MPesaRatiba->request($StandingOrderName, $StartDate, $EndDate, $Amount, $phonenumber, $AccountReference, $TransactionDesc, $Frequency);

       $finalresponse = [];

       if ($response['status']) {
           $finalresponse = $response['response'];
       }else{
           $finalresponse = $response['response'];
       }

       return $finalresponse;

    }



    public function reverse($transactionId, $amount, $receiverParty, $remarks = 'Reversal')
    {
        $Reversal = new Reversal();
        $response = $Reversal->request($transactionId, $amount, $receiverParty, $remarks);

        $finalresponse = [];

        if ($response['status']) {
            $finalresponse = $response['response'];
        }else{
            $finalresponse = $response['response'];
        }

        return $finalresponse;

    }


    public function remittance($amount, $reff, $remarks)
    {
        $TaxRemittance = new TaxRemittance();
        $response = $TaxRemittance->request($amount, $reff, $remarks);

        $finalresponse = [];

        if ($response['status']) {
            $finalresponse = $response['response'];
        }else{
            $finalresponse = $response['response'];
        }

        return $finalresponse;

    }


    public function status($transactionId, $remarks = 'Transaction Status Check', $occasion = 'N/A')
    {
        $TransactionStatus = new TransactionStatus();
        $response = $TransactionStatus->request($transactionId, $remarks, $occasion);

        $finalresponse = [];

        if ($response['status']) {
            $finalresponse = $response['response'];
        }else{
            $finalresponse = $response['response'];
        }

        return $finalresponse;

    }

    public function qrcode($merchantName, $refNo, $amount, $trxCode, $cpi, $size)
    {
        $DynamicQR = new DynamicQR();
        $response = $DynamicQR->request($merchantName, $refNo, $amount, $trxCode, $cpi, $size);

        $finalresponse = [];

        if ($response['status']) {
            $finalresponse = $response['response'];
        }else{
            $finalresponse = $response['response'];
        }

        return $finalresponse;

    }

    public function businesspaybill($amount, $recievingshortcode, $AccountReference, $remarks = 'OK')
    {
        $BusinessPayBill = new BusinessPayBill();
        $response = $BusinessPayBill->request($amount, $recievingshortcode, $AccountReference, $remarks);

        $finalresponse = [];

        if ($response['status']) {
            $finalresponse = $response['response'];
        }else{
            $finalresponse = $response['response'];
        }

        return $finalresponse;

    }

    public function businessbuygoods($amount, $recievingshortcode, $AccountReference, $remarks = 'OK')
    {
        $BusinessBuyGoods = new BusinessBuyGoods();
        $response = $BusinessBuyGoods->request($amount, $phone, $reference, $trx_desc, $remark);

        return $BusinessBuyGoods;

    }


    public function billmanager($condition = null, $data = [])
    {
        $BillManager = new BillManager();
        $response = $BillManager->request($condition, $data);

        $finalresponse = [];

        if ($response['status']) {
            $finalresponse = $response['response'];
        }else{
            $finalresponse = $response['response'];
        }

        return $finalresponse;

    }

    public function b2c($amount, $receiver, $remarks = 'B2C Payment')
    {
        $B2CPayment = new B2CPayment();
        $response = $B2CPayment->request($amount, $receiver, $remarks);

        $finalresponse = [];

        if ($response['status']) {
            $finalresponse = $response['response'];
        }else{
            $finalresponse = $response['response'];
        }

        return $finalresponse;

    }

    public function topupb2c($amount, $recievingshortcode, $AccountReference, $remarks = 'OK', $Requester = '')
    {
        $B2CAccountTopUp = new B2CAccountTopUp();
        $response = $B2CAccountTopUp->request($amount, $recievingshortcode, $AccountReference, $remarks, $Requester);

        $finalresponse = [];

        if ($response['status']) {
            $finalresponse = $response['response'];
        }else{
            $finalresponse = $response['response'];
        }

        return $finalresponse;

    }

    public function b2bexpresscheckout($receiverShortCode, $amount, $paymentRef, $partnerName)
    {
        $B2BExpressCheckOut = new B2BExpressCheckOut();
        $response = $B2BExpressCheckOut->request($receiverShortCode, $amount, $paymentRef, $partnerName);

        $finalresponse = [];

        if ($response['status']) {
            $finalresponse = $response['response'];
        }else{
            $finalresponse = $response['response'];
        }

        return $finalresponse;

    }


    public function accountballance($remarks = 'Account Balance Check')
    {
        $AccountBalance = new AccountBalance();
        $response = $AccountBalance->request($remarks = 'Account Balance Check');

        $finalresponse = [];

        if ($response['status']) {
            $finalresponse = $response['response'];
        }else{
            $finalresponse = $response['response'];
        }

        return $finalresponse;

    }


   public function process_stk_callback_url($response)
   {
      return $this->flattenArray($response);
   } 
   public function process_transaction_status_result_url($response)
   {
      return $this->flattenArray($response);
   } 
   public function process_transaction_status_quetimeouturl_url($response)
   {
      return $this->flattenArray($response);
   } 
   public function process_account_ballance_queuetimeout_url($response)
   {
      return $this->flattenArray($response);
   } 
   public function process_account_ballance_result_ur($response)
   {
      return $this->flattenArray($response);
   } 
   public function process_b2c_queuetimeout_url($response)
   {
      return $this->flattenArray($response);
   } 
   public function process_b2c_result_url($response)
   {
      return $this->flattenArray($response);
   } 
   public function process_reversal_result_url($response)
   {
      return $this->flattenArray($response);
   } 
   public function process_reversal_queutimeout_url($response)
   {
      return $this->flattenArray($response);
   } 
   public function process_TaxRemittance_queuetimeout_url($response)
   {
      return $this->flattenArray($response);
   } 
   public function process_TaxRemittance_result_url($response)
   {
      return $this->flattenArray($response);
   } 
   public function process_busineesspaybill_queuetimeout_url($response)
   {
      return $this->flattenArray($response);
   } 
   public function process_busineesspaybill_result_url($response)
   {
      return $this->flattenArray($response);
   } 
   public function process_business_buygoods_queuetimeout_url($response)
   {
      return $this->flattenArray($response);
   } 
   public function process_business_buygoods_result_url($response)
   {
      return $this->flattenArray($response);
   } 
   public function process_b2c_top_up_queuetimeout_url($response)
   {
      return $this->flattenArray($response);
   } 
   public function process_b2c_top_up_result_url($response)
   {
      return $this->flattenArray($response);
   } 
   public function process_optin_callbackurl($response)
   {
      return $this->flattenArray($response);
   } 
   public function process_changeDetails_callbackurl($response)
   {
      return $this->flattenArray($response);
   } 
   public function process_ratiba_callBackURL($response)
   {
        return $this->flattenArray($response);
   } 
   public function process_b2b_express_callbackUrl($response)
   {
      return $this->flattenArray($response);
   } 

  public function flattenArray($response)
  {
      if (!is_null($response)) {
          if (is_array($response)) {
              $result = [];
              array_walk_recursive($response, function ($value) use (&$result) {
                  $result[] = $value;
              });
              return $result;
          }
          if ($response instanceof \stdClass) {
              return $this->flattenArray((array)$response);
          }
          if (is_string($response)) {
              $decoded = json_decode($response, true);
              if (is_array($decoded)) {
                  return $this->flattenArray($decoded);
              }
              return [$response];
          }
      }
      return false;
  }


}
