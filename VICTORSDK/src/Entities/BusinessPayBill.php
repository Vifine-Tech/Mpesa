<?php 

	namespace VICTORSDK\Entities;
/**
 * 
 */
class BusinessPayBill extends Mpesa
{
	public function request($amount, $recievingshortcode, $AccountReference, $remarks = 'OK')
	{
	    $url = $this->url . '/mpesa/b2b/v1/paymentrequest';

	    $body = [
	        "Initiator" => $this->initiator,
	        "SecurityCredential" => $this->password,
	        "CommandID" => "BusinessPayBill",
	        "SenderIdentifierType" => $this->SenderIdentifierType,
	        "RecieverIdentifierType" => $this->RecieverIdentifierType,
	        "Amount" => $amount,
	        "PartyA" => $this->shortcode,
	        "PartyB" => $recievingshortcode,
	        "AccountReference" => $AccountReference,
	        "Remarks" => $remarks,
	        "QueueTimeOutURL" => $this->busineesspaybill_queuetimeout_url,
	        "ResultURL" => $this->busineesspaybill_result_url
	    ];

	    return $this->send($url, 'POST', true, $body);
	}
}