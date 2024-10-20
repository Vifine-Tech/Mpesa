<?php 

namespace VICTORSDK\Entities;

/**
 * 
 */
class BusinessBuyGoods extends Mpesa
{
	public function BusinessBuyGoods($amount, $recievingshortcode, $AccountReference, $remarks = 'OK')
	{
	    $url = $this->url . '/mpesa/b2b/v1/paymentrequest';

	    $body = [
	        "Initiator" => $this->initiator,
	        "SecurityCredential" => $this->password,
	        "CommandID" => "BusinessBuyGoods",
	        "SenderIdentifierType" => $this->SenderIdentifierType,
	        "RecieverIdentifierType" => $this->RecieverIdentifierType,
	        "Amount" => $amount,
	        "PartyA" => $this->shortcode,
	        "PartyB" => $recievingshortcode,
	        "AccountReference" => $AccountReference,
	        "Remarks" => $remarks,
	        "QueueTimeOutURL" => $this->business_buygoods_queuetimeout_url,
	        "ResultURL" => $this->business_buygoods_result_url
	    ];

	    return $this->send($url, 'POST', true, $body);
	}
}