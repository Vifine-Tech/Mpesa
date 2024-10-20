<?php
namespace VICTORSDK\Entities;

/**
 * MPesaExpress to create mpesa STK Push
 */
class MPesaExpress extends Mpesa
{
	
	// M-Pesa STK Push
	public function MPesaExpressSTK($amount, $phone, $reference, $trx_desc = "Transaction", $remark = "Transaction")
	{
	    $phone = '254' . substr($phone, -9);
	    $timestamp = date('YmdHis');
	    $password = base64_encode($this->headoffice . $this->passkey . $timestamp);

	    $post_data = array(
	        'BusinessShortCode' => $this->headoffice,
	        'Password' => $password,
	        'Timestamp' => $timestamp,
	        'TransactionType' => ($this->type == 4) ? 'CustomerPayBillOnline' : 'CustomerBuyGoodsOnline',
	        'Amount' => round($amount),
	        'PartyA' => $phone,
	        'PartyB' => $this->shortcode,
	        'PhoneNumber' => $phone,
	        'CallBackURL' => $this->stk_callback_url,
	        'AccountReference' => empty($this->reference) ? $reference : $this->reference,
	        'TransactionDesc' => $trx_desc,
	        'Remark' => $remark,
	    );

	    return $this->send($this->url . '/mpesa/stkpush/v1/processrequest', 'POST', true, $post_data);
	    
	}

}