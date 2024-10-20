<?php 
namespace VICTORSDK\Entities;
/**
 * 
 */
class RegisterURL extends Mpesa
{
	// Register URL
	public function registerURL($confirmationURL, $validationURL, $responseType = 'Completed')
	{
	    $post_data = [
	        'ShortCode' => $this->shortcode,
	        'ResponseType' => $responseType,
	        'ConfirmationURL' => $confirmationURL,
	        'ValidationURL' => $validationURL
	    ];

	    return $this->send($this->url . '/mpesa/c2b/v1/registerurl', 'POST', true, $post_data);
	}
}