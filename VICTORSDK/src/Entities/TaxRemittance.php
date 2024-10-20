<?php 
namespace VICTORSDK\Entities;
/**
 * 
 */
class TaxRemittance extends Mpesa
{
	// Tax Remittance
	public function TaxRemittance($amount, $reff, $remarks)
	{
	    $url = '/mpesa/b2b/v1/remittax';

	    $post_data = [
	        'Initiator' => $this->initiator,
	        'SecurityCredential' => $this->password,
	        'SenderIdentifierType' => $this->SenderIdentifierType,
	        'RecieverIdentifierType' => $this->RecieverIdentifierType,
	        'Amount' => $amount,
	        'PartyA' => $this->shortcode,
	        'PartyB' => '572572',
	        'AccountReference' => $reff,
	        'Remarks' => $remarks,
	        'QueueTimeOutURL' => $this->TaxRemittance_queuetimeout_url,
	        'ResultURL' => $this->TaxRemittance_result_url,
	        'Command ID' => 'PayTaxToKRA'
	    ];

	    return $this->send($this->url . '/mpesa/b2b/v1/remittax', 'POST', true, $post_data);
	}
}