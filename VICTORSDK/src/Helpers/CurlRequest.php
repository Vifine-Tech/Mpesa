<?php
namespace Modules\EsPayments\Entities\src\Helpers;

class CurlRequest
{
   public static function send($url, $method = 'GET', $header = false, $body = null, $credentials = null)
   {
       $headers = [];
       if ($header) {
           $accessToken = $credentials;
           $headers = [
               'Content-Type: application/json',
               'Authorization: Bearer ' . $accessToken
           ];
       } elseif ($credentials) {
           $headers = [
               'Authorization: Basic ' . $credentials
           ];
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
}
