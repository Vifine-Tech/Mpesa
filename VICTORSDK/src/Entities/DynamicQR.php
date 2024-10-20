<?php 

namespace VICTORSDK\Entities;
/**
 * 
 */
class DynamicQR extends Mpesa
{
	// Dynamic QR Generation
	public function dynamicQR($merchantName, $refNo, $amount, $trxCode, $cpi, $size)
	{
	    $post_data = [
	        'MerchantName' => $merchantName,
	        'RefNo' => $refNo,
	        'Amount' => $amount,
	        'TrxCode' => $trxCode,
	        'CPI' => $cpi,
	        'Size' => $size
	    ];

	    return $this->send($this->url . '/mpesa/qrcode/v1/generate', 'POST', true, $post_data);
	}
}