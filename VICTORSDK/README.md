
# VICTOR SDK

This SDK is designed to integrate with M-Pesa APIs.

## Installation

1. Clone the repository.
2. Run `composer install` to install the dependencies.

## Usage

### Example for M-Pesa Transactions
	
###	To create STK Push

```	php
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

$mpesa = new MPesaExpress($data);

return $mpesa->MPesaExpressSTK(100, '254718258849', 'ORDER_4354');

```



### SDK Structure

1. **Entities**: Contains the main logic for handling M-Pesa and BillManager operations.
2. **Helpers**: Contains helper classes for common functions like cURL requests.
3. **Interfaces**: Contains interfaces that define the structure of the SDK classes.

## Adding New Entities

To add a new entity, create a new PHP file under `src/Entities` and extend the `Mpesa` class if necessary.

## License

This project is licensed under the MIT License.
