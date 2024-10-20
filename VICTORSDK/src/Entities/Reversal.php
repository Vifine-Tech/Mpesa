<?php 
namespace VICTORSDK\Entities;

/**
 * 
 */
class Reversal extends Mpesa
{
	// Reversal Transaction
	public function request($transactionId, $amount, $receiverParty, $remarks = 'Reversal')
	{
	    $post_data = [
	        'Initiator' => $this->initiator,
	        'SecurityCredential' => $this->password,
	        'CommandID' => 'TransactionReversal',
	        'TransactionID' => $transactionId,
	        'Amount' => $amount,
	        'ReceiverParty' => $receiverParty,
	        'RecieverIdentifierType' => '11',
	        'ResultURL' => $this->reversal_result_url,
	        'QueueTimeOutURL' => $this->reversal_queutimeout_url,
	        'Remarks' => $remarks,
	        'Occasion' => 'N/A'
	    ];

	    return $this->send($this->url . '/mpesa/reversal/v1/request', 'POST', true, $post_data);
	}
}