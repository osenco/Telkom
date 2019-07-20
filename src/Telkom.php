<?php

namespace Osen\Telkom;

class Telkom
{
	/**
	 * @var object $config Configuration options
	 */
    public static $config;
    public static $urls = array();

    public static function init(array $configs = [])
    {
		$defaults = array(
			'env'               => 'preprod',
			'id'               	=> '',
			'type'              => 4,
			'shortcode'         => '174379',
			'key'               => 'Your Consumer Key',
			'secret'            => 'Your Consumer Secret',
			'username'          => 'apitest',
			'password'          => 'apitest',
			'validation_url'    => '/telkom/validate',
			'confirmation_url'  => '/telkom/confirm',
			'callback_url'      => '/telkom/reconcile',
			'timeout_url'       => '/telkom/timeout'
		);

		$parsed = array_merge($defaults, $configs);
	
        self::$config 	= (object)$parsed;
    }

	/**
	 * @return string Access token
	 */
    public static function token()
    {
        $endpoint = 'https://'.self::$config->env.'.gw.mfs-tkl.com/token?grant_type=password&username='.self::$config->username.'&password='.self::$config->password;

		$credentials = base64_encode(self::$config->key.':'.self::$config->secret);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $endpoint);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials));
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $curl_response = curl_exec($curl);

		$result = json_decode($curl_response);
        
		return isset($result->access_token) ? $result->access_token : '';
    }

    public function password($cert = 'certs/php_telepinuatrsa.cer')
    {
		try {
			$file_size 			= filesize($cert);
			$cert_path_handle 	= fopen(self::$cert, "r");
			$cert_data 			= fread($cert_path_handle, $file_size);
			fclose($cert_path_handle);
			
			$cert_data 			= openssl_x509_read($cert_data);
			$public_key 		= openssl_get_publickey($cert_data);
			openssl_public_encrypt(self::$config->password, $encrypted_text, $public_key);

			return(base64_encode($encrypted_text));
		} catch (\Throwable $th) {
			throw $th;
		}
    }

	/**
	 * @param int $transaction
	 * @param string $command
	 * @param string $remarks
	 * @param string $occassion\
	 * 
	 * @return array Response
	 */
    public static function checkStatus(int $transaction, string $type = 'ATP-B2C-C2B-B2B')
    {

		$token = self::token();
      	$endpoint = 'https://'.self::$config->env.'.gw.mfs-tkl.com/tkash/transactionstatus/v3/getstatus?referenceCode='.$transaction.'&transactionType='.$type;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $endpoint);
        curl_setopt(
        	$curl, 
        	CURLOPT_HTTPHEADER, 
        	array(
        		'Content-Type:application/json',
        		'Authorization:Bearer '.$token 
        	) 
      	);
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $response = curl_exec($curl);
		
		return json_decode($response, true);
    }

	/**
	 * @param int $transaction
	 * @param int $amount
	 * @param string $receiver
	 * @param int $receiver_type
	 * @param string $remarks
	 * @param string $occassion
	 * 
	 * @return array Response
	 */
    public static function reverse(int $transaction, int $amount, string $receiver, int $receiver_type = 3, string $remarks = 'Transaction Reversal', string $occassion = '')
    {

        $token = self::token();
    	$endpoint = (self::$config->env == 'live') ? 
			'https://api.safaricom.co.ke/telkom/reversal/v1/request' : 
			'https://sandbox.safaricom.co.ke/telkom/reversal/v1/request';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $endpoint);
        curl_setopt(
        	$curl, 
        	CURLOPT_HTTPHEADER, 
        	array(
        		'Content-Type:application/json',
        		'Authorization:Bearer '.$token 
        	) 
      	);
        $curl_post_data = array(
	        'CommandID'               => 'TransactionReversal',
	        'Initiator'               => self::$config->business,
	        'SecurityCredential'      => self::$config->credentials,
	        'TransactionID'           => $transaction,
	        'Amount'                  => $amount,
	        'ReceiverParty'           => $receiver,
	        'RecieverIdentifierType'  => $reciever_type,
	        'ResultURL'               => self::$config->results_url,
	        'QueueTimeOutURL'         => self::$config->timeout_url,
	        'Remarks'                 => $remarks,
	        'Occasion'                => $occasion
	  	);
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $response = curl_exec($curl);
		
		return json_decode($response);
    }

	/**
	 * @param string $command
	 * @param string $remarks
	 * @param string $occassion
	 * 
	 * @return array Response
	 */
    public static function balance(string $command, string $remarks = 'Balance Query', string $occassion = '')
    {
        $token = self::token();
      	
        $endpoint = 'https://'.self::$config->env.'.gw.mfs-tkl.com/ejaze/v1/ejazeBalance';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $endpoint);
        curl_setopt(
        	$curl, 
        	CURLOPT_HTTPHEADER, 
        	array(
        		'Content-Type:application/json',
        		'Authorization:Bearer '.$token 
        	) 
      	);
		
        $curl_post_data = array(
	        'CommandID'           => $command,
	        'Initiator'           => self::$config->username,
	        'SecurityCredential'  => self::$config->credentials,
	        'PartyA'              => self::$config->shortcode,
	        'IdentifierType'      => self::$config->type,
	        'Remarks'             => $remarks,
	        'QueueTimeOutURL'     => self::$config->timeout_url,
	        'ResultURL'           => self::$config->results_url
	  	);
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $response = curl_exec($curl);
		
		return json_decode($response);
    }
	
	/**
	 * @param callable $callback Defined function or closure to process data and return true/false
	 * 
	 * @return array
	 */
    public static function validate($callback = null)
	{

		$data = json_decode(file_get_contents('php://input'), true);

	    if(is_null($callback)){
		    return array('ResponseCode' => 0, 'ResponseDesc' => 'Success');
	    } else {
	        return call_user_func_array($callback, array($data)) 
				? array('ResponseCode' => 0, 'ResponseDesc' => 'Success') 
				: array('ResponseCode' => 1, 'ResponseDesc' => 'Failed');
	    }
    }
	
	/**
	 * @param callable $callback Defined function or closure to process data and return true/false
	 * 
	 * @return array
	 */
    public static function confirm($callback = null)
	{

		$data = json_decode(file_get_contents('php://input'), true);

	    if(is_null($callback)){
		    return array('ResponseCode' => 0, 'ResponseDesc' => 'Success');
	    } else {
	        return call_user_func_array($callback, array($data)) 
				? array('ResponseCode' => 0, 'ResponseDesc' => 'Success') 
				: array('ResponseCode' => 1, 'ResponseDesc' => 'Failed');
	    }
	}
	
	/**
	 * @param callable $callback Defined function or closure to process data and return true/false
	 * 
	 * @return array
	 */    
	public static function reconcile(callable $callback = null)
	{
		$response = json_decode(file_get_contents('php://input'), true);
	    
        if(is_null($callback)){
			return array('resultCode' => 0, 'resultDesc' => 'Telkom request successful');
		 } else {
			return call_user_func_array($callback, array($response))
				? array('resultCode' => 0, 'resultDesc' => 'Telkom request successful') 
				: array('resultCode' => 1, 'resultDesc' => 'Telkom request failed');
		 }
	}
	
	/**
	 * @param callable $callback Defined function or closure to process data and return true/false
	 * 
	 * @return array
	 */
	public static function results(callable $callback = null)
	{
		$response = json_decode(file_get_contents('php://input'), true);
	    
        if(is_null($callback)){
			return array('resultCode' => 0, 'resultDesc' => 'Telkom request successful');
		 } else {
			return call_user_func_array($callback, array($response))
				? array('resultCode' => 0, 'resultDesc' => 'Telkom request successful')
				: array('resultCode' => 1, 'resultDesc' => 'Telkom request failed');
		 }
	}
	
	/**
	 * @param callable $callback Defined function or closure to process data and return true/false
	 * 
	 * @return array
	 */
	public static function timeout(callable $callback = null)
	{
		$response = json_decode(file_get_contents('php://input'), true);
	    
        if(is_null($callback)){
			return array('resultCode' => 0, 'resultDesc' => 'Telkom request successful');
		 } else {
			return call_user_func_array($callback, array($response))
				? array('resultCode' => 0, 'resultDesc' => 'Telkom request successful')
				: array('resultCode' => 1, 'resultDesc' => 'Telkom request failed');
		 }
	}

}
