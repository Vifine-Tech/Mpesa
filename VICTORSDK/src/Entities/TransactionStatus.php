<?php 

namespace VICTORSDK\Entities;

/**
 * 
 */
class TransactionStatus extends Mpesa
{
	public function request($transactionId, $remarks = 'Transaction Status Check', $occasion = 'N/A')
	{
	    $post_data = [
	        'Initiator' => $this->initiator,
	        'SecurityCredential' => $this->password,
	        'CommandID' => 'TransactionStatusQuery',
	        'TransactionID' => $transactionId,
	        'PartyA' => $this->shortcode,
	        'IdentifierType' => $this->type,
	        'ResultURL' => $this->transaction_status_result_url,
	        'QueueTimeOutURL' => $this->transaction_status_quetimeouturl_url,
	        'Remarks' => $remarks,
	        'Occasion' => $occasion
	    ];

	    return $this->send($this->url . '/mpesa/transactionstatus/v1/query', 'POST', true, $post_data);
	}
}