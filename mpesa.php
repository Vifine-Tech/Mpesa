<?php
/**
 * 
 */
class mpesa
{


	public $consumer_key;
	public $consumer_secret;
	public $passkey;
	public $headoffice;
	public $shortcode;
	public $type = 4;
	public $initiator;
	public $password;
	public $reference = '';
	public $signature;
	public $token;
	private $url;
	
	function __construct($data)
	{
		$this->consumer_key 	=  $data['consumer_key'];
		$this->consumer_secret 	=  $data['consumer_secret'];
		$this->passkey 			=  $data['passkey'];
		$this->headoffice 		=  $data['headoffice'];
		$this->shortcode 		=  $data['shortcode'];
		$this->type 			=  ($data['shortcode'] == '174379' || $data['shortcode'] == $data['headoffice']) ?  4 :  2 ;
		$this->initiator 		=  $data['initiator'];
		$this->password 		=  $data['password'];
		$this->reference 		=  $data['reference'];
		$this->url  			= ($data['shortcode'] == '174379' || $data['shortcode'] == '600000' || $data['shortcode'] == '600998') ?  'https://api.safaricom.co.ke' : 'https://sandbox.safaricom.co.ke';
	}


	//  Mpesa Express send Request
	public function MPesaExpressSTK()
	{

		$phone     = '254' . substr($phone, -9);
		$timestamp = date('YmdHis');
		$password  = base64_encode($this->headoffice . $this->passkey . $timestamp);
		$post_data = array(
		    'BusinessShortCode' => $this->headoffice,
		    'Password'          => $password,
		    'Timestamp'         => $timestamp,
		    'TransactionType'   => ($this->type == 4) ? 'CustomerPayBillOnline' : 'CustomerBuyGoodsOnline',
		    'Amount'            => round($amount),
		    'PartyA'            => $phone,
		    'PartyB'            => $this->shortcode,
		    'PhoneNumber'       => $phone,
		    'CallBackURL'       => =$this->CallBackURL,
		    'AccountReference'  => empty($this->reference) ? $reference : $this->reference,
		    'TransactionDesc'   => $trx_desc,
		    'Remark'            => $remark,
		);

		$data_string = json_encode($post_data);

		$response = $this->sendCurlRequest('/mpesa/stkpush/v1/processrequest', 'POST', true, $body);

		return $response;
	}


	// Mpesa Express STK Request Status 

	public function MPesaExpressSTKStatus()
	{
		$body = {    
				   "BusinessShortCode":"174379",    
				   "Password": "MTc0Mzc5YmZiMjc5TliZGJjZjE1OGU5N2RkNzFhNDY3Y2QyZTBjODkzMDU5YjEwZjc4ZTZiNzJhZGExZWQyYzkxOTIwMTYwMjE2MTY1NjI3",    
				   "Timestamp":"20160216165627",    
				   "CheckoutRequestID": "ws_CO_260520211133524545",    
				}    
	}


	// Mpesa B2C 

	public function B2C()
	{

		$url = '/mpesa/b2c/v3/paymentrequest';
		$b2c = {    
				   "OriginatorConversationID": "feb5e3f2-fbbc-4745-844c-ee37b546f627",
				   "InitiatorName": "testapi",
				   "SecurityCredential":"EsJocK7+NjqZPC3I3EO+TbvS+xVb9TymWwaKABoaZr/Z/n0UysSs..",
				   "CommandID":"BusinessPayment",
				   "Amount":"10"
				   "PartyA":"600996",
				   "PartyB":"254728762287"
				   "Remarks":"here are my remarks",
				   "QueueTimeOutURL":"https://mydomain.com/b2c/queue",
				   "ResultURL":"https://mydomain.com/b2c/result",
				   "Occassion":"Christmas"
				}
		
	}

	// Dynamic QR

	public function dynamicQR()
	{
		$url = '/mpesa/qrcode/v1/generate';

		$body = {    
					   "MerchantName":"TEST SUPERMARKET",
					   "RefNo":"Invoice Test",
					   "Amount":1,
					   "TrxCode":"BG",
					   "CPI":"373132",
					   "Size":"300"
					}
	}


	// Customer To Business Register URL

	public function registerURL()
	{
		$body = {    
		   "ShortCode": "601426",
		   "ResponseType":"[Cancelled/Completed]",
		   "ConfirmationURL":"[confirmation URL]",
		   "ValidationURL":"[validation URL]"
		}	
	}


	// Transaction Status

	public function transactionstatus()
	{
		$url = '/mpesa/transactionstatus/v1/query';
		$body = {    
				   "Initiator":"testapiuser",
				   "SecurityCredential":"ClONZiMYBpc65lmpJ7nvnrDmUe0WvHvA5QbOsPjEo92B6IGFwDdvdeJIFL0kgwsEKWu6SQKG4ZZUxjC",
				   "Command ID": "TransactionStatusQuery",
				   "Transaction ID": "NEF61H8J60",
				   "OriginatorConversationID":"AG_20190826_0000777ab7d848b9e721",
				   "PartyA":"600782",
				   "IdentifierType":"4",
				   "ResultURL":"http://myservice:8080/transactionstatus/result",
				   "QueueTimeOutURL":"http://myservice:8080/timeout",
				   "Remarks":"OK",
				   "Occasion":"OK",
				}
	}

	// Account Balance

	public function accountBallance()
	{
		$url = '/mpesa/accountbalance/v1/query';
		$body = {    
			   "Initiator":"testapiuser",
			   "SecurityCredential":"SAFVNChNHfVtXEZMBuVo+a1Hwr+DtrUVN3zVg==",
			   "Command ID": "AccountBalance",
			   "PartyA": "600000",
			   "IdentifierType":"4",
			   "Remarks":"ok",
			   "QueueTimeOutURL":"http://myservice:8080/queuetimeouturl",
			   "ResultURL":"http://myservice:8080/result",
			}
	}

	// Reversals

	public function Reversals()
	{
		$url = '/mpesa/reversal/v1/request';
		$body = {    
				   "Initiator":"TestInit610",    
				   "SecurityCredential": "[encrypted password]",    
				   "CommandID":"TransactionReversal",    
				   "TransactionID": "[original trans_id]",    
				   "Amount":"[trans_amount]",    
				   "ReceiverParty":"600610",    
				   "RecieverIdentifierType":"11",    
				   "ResultURL":"https://ip:port/",    
				   "QueueTimeOutURL":"https://ip:port/",    
				   "Remarks":"Test",    
				   "Occasion":"work"
				}
		
	}

	// Tax Remittance

	public function TaxRemittance()
	{
		$url = '/mpesa/b2b/v1/remittax';

		$body = {    
				   "Initiator":"TaxPayer",
				   "SecurityCredential":"FKXl/KPzT8hFOnozI+unz7mXDgTRbrlrZ+C1Vblxpbz7jliLAFa0E/…../uO4gzUkABQuCxAeq+0Hd0A==",
				   "Command ID": "PayTaxToKRA",
				   "SenderIdentifierType": "4",
				   "RecieverIdentifierType":"4",
				   "Amount":"239",
				   "PartyA":"888880",
				   "PartyB":"572572",
				   "AccountReference":"353353",
				   "Remarks":"OK",
				   "QueueTimeOutURL":"https://mydomain.com/b2b/remittax/queue/",
				   "ResultURL":"https://mydomain.com/b2b/remittax/result/",
				}
	}

	// Business Pay Bill

	public function BusinessPayBill()
	{
		$url = '/mpesa/b2b/v1/paymentrequest';
		$body = {    
			   "Initiator":"API_Usename",
			   "SecurityCredential":"FKXl/KPzT8hFOnozI+unz7mXDgTRbrlrZ+C1Vblxpbz7jliLAFa0E/…../uO4gzUkABQuCxAeq+0Hd0A==",
			   "Command ID": "BusinessPayBill",
			   "SenderIdentifierType": "4",
			   "RecieverIdentifierType":"4",
			   "Amount":"239",
			   "PartyA":"123456",
			   "PartyB":"000000",
			   "AccountReference":"353353",
			   "Requester":"254700000000",
			   "Remarks":"OK",
			   "QueueTimeOutURL":"http://0.0.0.0:0000/ResultsListener.php",
			   "ResultURL":"http://0.0.0.0:8888/TimeOutListener.php",
			}
	}

	// Bill Manager

	public function BusinessBuyGoods()
	{
		Request body 
		$url = '/mpesa/b2b/v1/paymentrequest';

		{    
		   "Initiator":"API_Usename",
		   "SecurityCredential":"FKXl/KPzT8hFOnozI+unz7mXDgTRbrlrZ+C1Vblxpbz7jliLAFa0E/…../uO4gzUkABQuCxAeq+0Hd0A==",
		   "Command ID": "BusinessBuyGoods",
		   "SenderIdentifierType": "4",
		   "RecieverIdentifierType":"4",
		   "Amount":"239",
		   "PartyA":"123456",
		   "PartyB":"000000",
		   "AccountReference":"353353",
		   "Requester":"254700000000",
		   "Remarks":"OK",
		   "QueueTimeOutURL":"https://mydomain.com/b2b/businessbuygoods/queue/",
		   "ResultURL":"https://mydomain.com/b2b/businessbuygoods/result/",
		}
	}


	public function BillManager()
	{
		if ($option) {
			$url = 'https://api.safaricom.co.ke/v1/billmanager-invoice/optin';

			$body = {  
						  "shortcode":"718003",
						  "email":"youremail@gmail.com",
						  "officialContact":"0710XXXXXX",
						  "sendReminders":"1",
						  "logo":"image",
						  "callbackurl":"http://my.server.com/bar/callback"
						}
		}elseif (condition) {
			$url = 'https://sandbox.safaricom.co.ke/v1/billmanager-invoice/change-optin-details';	

			$body = {
						  "shortcode":"718003",    
						  "email":"youremail@gmail.com",    
						  "officialContact":"0710XXXXXX",    
						  "sendReminders":1,    
						  "shortcode":"718003",    
						  "logo": "image",
						  "callbackurl": "/api.example.com/payments?callbackURL=http://my.server.com/bar"
						}
		}elseif (condition) {
			$url = 'https://api.safaricom.co.ke/v1/billmanager-invoice/reconciliation';	

			$body = {
				  "transactionId":"{trandID}", 
				  "paidAmount":"{50}", 
				  "msisdn":"254710119383", 
				  "dateCreated":"2021-09-15", 
				  "accountReference":"LGHJIO789", 
				  "shortCode":"349350555"
				}
		}elseif (condition) {
			$url = 'https://api.safaricom.co.ke/v1/billmanager-invoice/bulk-invoicing';	

			use for each
			$body = 
							// first invoice
							  {
							  {
							  "externalReference": "1107",
							  "billedFullName": "John Doe",
							  "billedPhoneNumber": "0722000000",
							  "billedPeriod": "August 2021",
							  "invoiceName": "Jentrys ",
							  "dueDate":"2021-09-15 00:00:00.00",
							  "accountReference": "A1",
							  "amount":"2000",
							  "invoiceItems": [
							     {
							       "itemName": "food",
							       "amount": "1000"
							     },
							     {
							      "itemName": "water",
							      "amount": "1000"
							     }
							   ]
							  },
							 // second invoice

							}

							
		}elseif (condition) {
			$url = 'https://api.safaricom.co.ke/v1/billmanager-invoice/single-invoicing';	

			$body = {
						"externalReference": "#9932340",
						 "billedFullName":  "John Doe",
						 "billedPhoneNumber":  "07XXXXXXXX",
						 "billedPeriod":"August 2021",
						 "invoiceName":"Jentrys",
						 "dueDate":"2021-10-12",
						 "accountReference":"1ASD678H"
						 "amount":"800",
						 "invoiceItems":[
						    {
						     "itemName":"food",
						     "amount":"700"
						    },
						    {
						     "itemName":"water",
						     "amount":"100"
						    }
						   ]
						  }
						 }
						}
		}

	}


	// B2B Express CheckOut

	public function B2BExpressCheckOut()
	{
		$url = '/v1/ussdpush/get-msisdn';

		$body = {    
					   "primaryShortCode":"000001",
					   "receiverShortCode":"000002",
					   "amount":"100",
					   "paymentRef":"paymentRef",
					   "callbackUrl":"http://..../result",
					   "partnerName":"Vendor",
					   "RequestRefID":"{{random Unique Identifer For Each Request}}",
					}
	}

	// B2C Account Top Up

	public function B2CAccountTopUp()
	{
		$url = '/mpesa/b2b/v1/paymentrequest';
		$body = {    
				   "Initiator":"testapi",
				   "SecurityCredential":"IAJVUHDGj0yDU3aop/WI9oSPhkW3DVlh7EAt3iRyymTZhljpzCNnI/xFKZNooOf8PUFgjmEOihUnB24adZDOv3Ri0Citk60LgMQnib0gjsoc9WnkHmGYqGtNivWE20jyIDUtEKLlPr3snV4d/H54uwSRVcsATEQPNl5n3+EGgJFIKQzZbhxDaftMnxQNGoIHF9+77tfIFzvhYQen352F4D0SmiqQ91TbVc2Jdfx/wd4HEdTBU7S6ALWfuCCqWICHMqCnpCi+Y/ow2JRjGYHdfgmcY8pP5oyH25uQk1RpWV744aj2UROjDrxTnE7a6tDN6G/dA21MXKaIsWJT/JyyXg==",
				   "CommandID":"BusinessPayToBulk",
				   "SenderIdentifierType":"4",
				   "RecieverIdentifierType":"4",
				   "Amount":"239",
				   "PartyA":"600979",
				   "PartyB":"600000",
				   "AccountReference":"353353",
				   "Requester":"254708374149",
				   "Remarks":"OK",
				   "QueueTimeOutURL":"https://mydomain/path/timeout",
				   "ResultURL":"https://mydomain/path/result"
				}
	}

	// M-Pesa Ratiba

	public function MPesaRatiba()
	{
		$url = '/standingorder/v1/createStandingOrderExternal';
		$body = {    
			   "StandingOrderName":"Test Standing Order",
			   "StartDate":"20240905",
			   "EndDate":"20230905",
			   "BusinessShortCode":"174379",
			   "TransactionType":"Standing Order Customer Pay Bill",
			   "ReceiverPartyIdentifierType":"4",
			   "Amount":"4500",
			   "PartyA":"254708374149",
			   "CallBackURL":"https://mydomain.com/pat",
			   "AccountReference":"Test",
			   "TransactionDesc":"Test",
			   "Frequency":"2",
			}
	}

	public function accesstoken()
	{
		$credentials = base64_encode($this->consumer_key.':'.$this->consumer_secret);

		$response = $this->sendCurlRequest('/oauth/v1/generate?grant_type=client_credentials');

		if ( $response != '' && is_array($response) &&  array_key_exists('access_token', $response)) {
				return $response['access_token'];

		}
			return FALSE;
		
		}
	}


	public function sendCurlRequest($url, $method = 'GET', $header = false, $body = null) {
	    // Initialize cURL session

	    $headers = [];

	    if ($header) {
	    	$accesstoken = $this->accesstoken();
	    			$headers = array(
	    			    'Content-Type'  => 'application/json',
	    			    'Authorization' => 'Bearer ' . $accesstoken,
	    			);
	    }
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
	    if (!empty($headers)) {
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    }
	    if (!empty($body) && strtoupper($method) !== 'GET') {
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
	    }
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	   
	    $response = curl_exec($ch);
	    
	    if ($response === false) {
	        $error = curl_error($ch);
	        curl_close($ch);
	        return ['success' => false, 'error' => $error];
	    }
	    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);
	    return ['success' => true, 'response' => $response, 'httpCode' => $httpCode];
	}



}