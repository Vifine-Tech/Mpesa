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
}
