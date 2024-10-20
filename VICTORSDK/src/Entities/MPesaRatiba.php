<?php 

namespace VICTORSDK\Entities;
/**
 * 
 */
class MPesaRatiba extends Mpesa
{
	public function request($StandingOrderName, $StartDate, $EndDate, $Amount, $phonenumber, $AccountReference, $TransactionDesc, $Frequency)
	{
	    // 1 - One Off
	    // 2 - Daily
	    // 3 - Weekly
	    // 4 - Monthly
	    // 5 - Bi-Monthly
	    // 6 - Quarterly
	    // 7 - Half Year
	    // 8 - Yearly
	    $url = $this->url . '/standingorder/v1/createStandingOrderExternal';

	    $body = [
	        "StandingOrderName" => $StandingOrderName,
	        "StartDate" => $StartDate,
	        "EndDate" => $EndDate,
	        "BusinessShortCode" => $this->shortcode,
	        "TransactionType" => ($this->shortcode == $this->headoffice) ? 'Standing Order Customer Pay Bill' : 'Standing Order Customer Pay Merchant',
	        "ReceiverPartyIdentifierType" => 4,
	        "Amount" => $Amount,
	        "PartyA" => $phonenumber,
	        "CallBackURL" => $this->ratiba_callBackURL,
	        "AccountReference" => $AccountReference,
	        "TransactionDesc" => $TransactionDesc,
	        "Frequency" => $Frequency
	    ];

	    return $this->send($url, 'POST', true, $body);
	}
}