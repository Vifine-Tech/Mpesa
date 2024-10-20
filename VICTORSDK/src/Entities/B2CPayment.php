<?php 
namespace VICTORSDK\Entities;
/**
 * 
 */
class B2CPayment extends Mpesa
{
	// M-Pesa B2C Payment Request
	public function request($amount, $receiver, $remarks = 'B2C Payment')
	{
	    $post_data = [
	        'InitiatorName' => $this->initiator,
	        'SecurityCredential' => $this->password,
	        'CommandID' => 'BusinessPayment',
	        'Amount' => $amount,
	        'PartyA' => $this->shortcode,
	        'PartyB' => $receiver,
	        'Remarks' => $remarks,
	        'QueueTimeOutURL' => $this->b2c_queuetimeout_url,
	        'ResultURL' => $this->b2c_result_url,
	        'Occasion' => 'N/A'
	    ];

	    return $this->send($this->url . '/mpesa/b2c/v1/paymentrequest', 'POST', true, $post_data);
	}
}