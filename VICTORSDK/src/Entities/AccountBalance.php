<?php 
	namespace VICTORSDK\Entities;

	/**
	 * 
	 */
	class AccountBalance extends Mpesa
	{
		
		// M-Pesa Account Balance
		public function request($remarks = 'Account Balance Check')
		{
		    $post_data = [
		        'Initiator' => $this->initiator,
		        'SecurityCredential' => $this->password,
		        'CommandID' => 'AccountBalance',
		        'PartyA' => $this->shortcode,
		        'IdentifierType' => $this->type,
		        'Remarks' => $remarks,
		        'QueueTimeOutURL' => $this->account_ballance_queuetimeout_url,
		        'ResultURL' => $this->account_ballance_result_ur
		    ];

		    return $this->send($this->url . '/mpesa/accountbalance/v1/query', 'POST', true, $post_data);
		}
		
	}

 ?>