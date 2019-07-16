<?php

namespace Osen\Telkom;

use Osen\Telkom\Service;

class Airtime extends Service
{
	/**
	 * This API is used to perform an Airtime Purchase by the API consumer and receives an acknowledgement from the API gateway with a referenceCode which is used to query back the status of the transaction using the Transaction Status API. Note that this is not a call-back, but rather the API consumer polling for transaction status
	 * username String API application username
		password String Password of the API application user
		msisdn String The mobile number for which you are purchasing airtime. The number format can
		include Country Code without + (254) or National Destination Code (077).
		Number
		amount
		Transaction amount for the initiated transaction
		KShs. 10 – 10,000
		brandId
		Number
		Internal identifier for type of the transaction, to be shared at integration time. For
		testing use 933
	 * 
	 * @return array
	 */
    public static function assync(string $phone, integer $amount, integer $brandId = 933)
    {
        $token 		= parent::token();

		$env        = parent::$config->env;

        $endpoint 	= ($env == 'live')
			? 'https://prod.gw.mfs-tkl.com/tkash/airtimerequest/v3/atpAsync'
			: 'https://preprod.gw.mfs-tkl.com/tkash/airtimerequest/v3/atpAsync';

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
        	"atpRequest" => array(
				"username" 	=> parent::$config->username,
				"password"  => parent::$password,
				"msisdn" 	=> $phone,
				"amount"    => $amount,
				"brandId" 	=> $brandId
			)
	   	);
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $response = curl_exec($curl);
		
		return json_decode($response, true);

		// {
		// "atpAsyncResponse": {
		// "referenceCode": "e11893c5-b033-4e99-9473-a43d66b65fbb",
		// "transactionType": "ATP"
		// }
		// }
    }

    public static function sync(string $phone, integer $amount, integer $brandId = 933)
    {
        $token 		= parent::token();

		$env        = parent::$config->env;

        $endpoint 	= ($env == 'live')
			? 'https://prod.gw.mfs-tkl.com/tkash/airtimerequest/v3/atp'
			: 'https://preprod.gw.mfs-tkl.com/tkash/airtimerequest/v3/atp';

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
        	"atpRequest" => array(
				"username" 	=> parent::$config->username,
				"password"  => parent::$password,
				"msisdn" 	=> $phone,
				"amount"    => $amount,
				"brandId" 	=> $brandId
			)
	   	);
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $response = curl_exec($curl);
		
		return json_decode($response, true);

		// {
		// "atpSyncResponse": {
		// "resultCode": "string",
		// "message": "string",
		// "tklReference": "string",
		// "originalAmount": 0,
		// "finalAmount": 0,
		// "activationDate": 0,
		// "salesOrderNo": "string",
		// "newBalance": 0,
		// "paymentTotal": "string",
		// "trnxFee": 0,
		// "destinationMSISDN": "string"
		// }
		// }
    }

    //This API is used to perform an Airtime Purchase by the API consumer in a synchronous manner, and waits for a response received from E-jaze.
    public static function ejaze(string $phone, integer $amount, integer $brandId = 933)
    {
        $token 		= parent::token();

		$env        = parent::$config->env;

        $endpoint 	= ($env == 'live')
			? 'https://prod.gw.mfs-tkl.com/ejaze/v1/ejazeAtp'
			: 'https://preprod.gw.mfs-tkl.com/ejaze/v1/ejazeAtp';

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
			"airtimeRequest" => array(
				"loginId" 	=> parent::$config->id,
				"password" 	=> parent::$config->password,
				"pin": "string",
				"code": "string",
				"sourceMsisdn": $source,
				"destMsisdn": $phone,
				"amount": $amount,
				"extrefnum": "string"
			)
	   	);
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $response = curl_exec($curl);
		
		return json_decode($response, true);

		// {
		// "airtimeResponse": {
		// "date": "string",
		// "txnStatus": "string",
		// "extrefnum": "string",
		// "message": "string",
		// "transactionId": "string"
		// }
		// }
    }
    
}