<?php 

namespace VICTORSDK\Entities;

/**
 * 
 */
class B2CAccountTopUp extends Mpesa
{
	public function request($amount, $recievingshortcode, $AccountReference, $remarks = 'OK', $Requester = '')
	{
	    $url = $this->url . '/mpesa/b2b/v1/paymentrequest';

	    $body = [
	        "Initiator" => $this->initiator,
	        "SecurityCredential" => $this->password,
	        "CommandID" => "BusinessPayToBulk",
	        "SenderIdentifierType" => $this->SenderIdentifierType,
	        "RecieverIdentifierType" => $this->RecieverIdentifierType,
	        "Amount" => $amount,
	        "PartyA" => $this->shortcode,
	        "PartyB" => $recievingshortcode,
	        "AccountReference" => $AccountReference,
	        "Requester" => $Requester,
	        "Remarks" => $remarks,
	        "QueueTimeOutURL" => $this->b2c_top_up_queuetimeout_url,
	        "ResultURL" => $this->b2c_top_up_result_url
	    ];

	    return $this->send($url, 'POST', true, $body);
	}
}