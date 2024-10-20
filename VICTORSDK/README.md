
# VICTOR SDK

This SDK is designed to integrate with M-Pesa APIs.

## Installation

1. Clone the repository.
2. Run `composer install` to install the dependencies.

## Usage

### Example for M-Pesa Transactions
	
###	To create STK Push

```	php
require 'VICTORSDK\Entities\MPesaExpress.php';

use VICTORSDK\Entities\MPesaExpress;

$data = array(
    'consumer_key' => 'MPESA_CONSUEMER_KEY',
    'consumer_secret' => 'MPESA_CONSUMER_SECRET',
    'passkey' => 'MPESA_PASSKEY',
    'headoffice' => 'MPESA_SHORTCODE',
    'shortcode' => 'MPESA_SHORTCODE',
    'initiator' => 'MPESAB2C_USERNAME',
    'password' => 'MPESAB2C_PASSWORD',
    'stk_callback_url' => 'https://'
);

$mpesa = new Mpesa($data);

// Create stk Push using this function
return $mpesa->stk($amount, $phone, $orderreference);

return  $mpesa->ratiba($StandingOrderName, $StartDate, $EndDate, $Amount, $phonenumber, $AccountReference, $TransactionDesc, $Frequency);


return  $mpesa->reverse($transactionId, $amount, $receiverParty, $remarks = 'Reversal');


return  $mpesa->remittance($amount, $reff, $remarks);


return  $mpesa->status($transactionId, $remarks = 'Transaction Status Check', $occasion = 'N/A');


return  $mpesa->qrcode($merchantName, $refNo, $amount, $trxCode, $cpi, $size);


return  $mpesa->businesspaybill($amount, $recievingshortcode, $AccountReference, $remarks = 'OK');


return  $mpesa->businessbuygoods($amount, $recievingshortcode, $AccountReference, $remarks = 'OK');


return  $mpesa->billmanager($condition = null, $data = []);


return  $mpesa->b2c($amount, $receiver, $remarks = 'B2C Payment');


return  $mpesa->topupb2c($amount, $recievingshortcode, $AccountReference, $remarks = 'OK', $Requester = '');


return  $mpesa->b2bexpresscheckout($receiverShortCode, $amount, $paymentRef, $partnerName);


return  $mpesa->accountballance($remarks = 'Account Balance Check');

```



### SDK Structure

1. **Entities**: Contains the main logic for handling M-Pesa and BillManager operations.
2. **Helpers**: Contains helper classes for common functions like cURL requests.
3. **Interfaces**: Contains interfaces that define the structure of the SDK classes.

## Adding New Entities

To add a new entity, create a new PHP file under `src/Entities` and extend the `Mpesa` class if necessary.

## License

This project is licensed under the MIT License.
