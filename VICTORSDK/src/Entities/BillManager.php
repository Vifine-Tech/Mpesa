<?php 

namespace VICTORSDK\Entities;
/**
 * 
 */
class BillManager extends Mpesa
{
	public function BillManager($condition = null, $data = [])
	{
	    $url = '';
	    $body = [];

	    // Opt-in request
	    if ($condition === 'optin') {
	        $url = 'https://api.safaricom.co.ke/v1/billmanager-invoice/optin';
	        $body = [
	            "shortcode" => $data['shortcode'],
	            "email" => $data['email'],
	            "officialContact" => $data['officialContact'],
	            "sendReminders" => $data['sendReminders'],
	            "logo" => $data['logo'],
	            "callbackurl" => $this->optin_callbackurl
	        ];

	    // Change Opt-in Details
	    } elseif ($condition === 'changeDetails') {
	        $url = 'https://sandbox.safaricom.co.ke/v1/billmanager-invoice/change-optin-details';
	        $body = [
	            "shortcode" => $data['shortcode'],
	            "email" => $data['email'],
	            "officialContact" => $data['officialContact'],
	            "sendReminders" => $data['sendReminders'],
	            "logo" => $data['logo'],
	            "callbackurl" => $this->changeDetails_callbackurl
	        ];

	    // Reconciliation
	    } elseif ($condition === 'reconciliation') {
	        $url = 'https://api.safaricom.co.ke/v1/billmanager-invoice/reconciliation';
	        $body = [
	            "transactionId" => $data['transactionId'],
	            "paidAmount" => $data['paidAmount'],
	            "msisdn" => $data['msisdn'],
	            "dateCreated" => $data['dateCreated'],
	            "accountReference" => $data['accountReference'],
	            "shortCode" => $data['shortCode']
	        ];

	    // Bulk Invoicing
	    } elseif ($condition === 'bulkInvoicing') {
	        $url = 'https://api.safaricom.co.ke/v1/billmanager-invoice/bulk-invoicing';

	        // Iterate over the array of invoices
	        $body = [];
	        foreach ($data['invoices'] as $invoice) {
	            $body[] = [
	                "externalReference" => $invoice['externalReference'],
	                "billedFullName" => $invoice['billedFullName'],
	                "billedPhoneNumber" => $invoice['billedPhoneNumber'],
	                "billedPeriod" => $invoice['billedPeriod'],
	                "invoiceName" => $invoice['invoiceName'],
	                "dueDate" => $invoice['dueDate'],
	                "accountReference" => $invoice['accountReference'],
	                "amount" => $invoice['amount'],
	                "invoiceItems" => $invoice['invoiceItems']
	            ];
	        }

	    // Single Invoicing
	    } elseif ($condition === 'singleInvoicing') {
	        $url = 'https://api.safaricom.co.ke/v1/billmanager-invoice/single-invoicing';
	        $body = [
	            "externalReference" => $data['externalReference'],
	            "billedFullName" => $data['billedFullName'],
	            "billedPhoneNumber" => $data['billedPhoneNumber'],
	            "billedPeriod" => $data['billedPeriod'],
	            "invoiceName" => $data['invoiceName'],
	            "dueDate" => $data['dueDate'],
	            "accountReference" => $data['accountReference'],
	            "amount" => $data['amount'],
	            "invoiceItems" => $data['invoiceItems']
	        ];
	    }

	    // Send the request using a cURL request
	    return $this->send($url, 'POST', true, $body);
	}
}