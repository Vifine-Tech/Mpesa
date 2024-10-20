<?php 

	namespace VICTORSDK\Entities;

	/**
	 * 
	 */
	class B2BExpressCheckOut extends Mpesa
	{
		
		
		public function B2BExpressCheckOut($receiverShortCode, $amount, $paymentRef, $partnerName)
		{
		    $url = $this->url . '/v1/ussdpush/get-msisdn';

		    $body = [
		        "primaryShortCode" => $this->shortcode,
		        "receiverShortCode" => $receiverShortCode,
		        "amount" => $amount,
		        "paymentRef" => $paymentRef,
		        "callbackUrl" => $this->b2b_express_callbackUrl,
		        "partnerName" => $partnerName,
		        "RequestRefID" => uniqid('Ref-', true)
		    ];

		    return $this->send($url, 'POST', true, $body);
		}

		
	}