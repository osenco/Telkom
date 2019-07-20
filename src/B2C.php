<?php

namespace Osen\Telkom;

use Osen\Telkom\Telkom;

class B2C extends Telkom
{
    /**
     * $username string API username
     * PasswordStringAPI user password
     * MsisdnStringDestination subscriber‟s MSISDN
     * AmountStringTransaction amount for the initiated transaction
     * brandIdStringTransaction type identifier -a numeric value to be shared at the time of account setup. May be different for test and production environments. For testing use 898for bank to wallet, 930every other disbursement paymentinfo1StringSender Institution/Bank/BusinessName (Additional information associated with the transaction)-optionalinfo2StringSender Bank Account Name (Additional information associated with the transaction) -optionalinfo3StringSender unique ID/bank account number (Additional information associated with the transaction) -optionalexternalRefStringExternal reference ID
     */
    public static function nameLookUp($phone)
    {
        $token 		= parent::token();

		$env        = parent::$config->env;

        $endpoint 	= 'https://'.$env.'.gw.mfs-tkl.com/tkash/b2c/v3/namelookup';

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
			"nameLookupRequest" => array(
                "username" 		=> parent::$config->password,
                "password" 		=> parent::$config->password,
                "msisdn" 		=> $phone,
                "amount" 		=> 0,
                "brandId" 		=> $brandId,
                "info1" 		=> $info1,
                "info2" 		=> $info2,
                "info3" 		=> $info3,
                "externalRef" 	=> $ref
			)
	   	);
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $response = curl_exec($curl);
		
		return json_decode($response, true);

		// "nameLookupResponse": {"resultCode": "string","destinationMSISDN": "string","amount": number,"transactionFee": number,"firstName": " string","lastName": " string","info1": "string","info2": "string","info3": "string","externalRef": "string"
    }


    public static function sync($phone)
    {
        $token 		= parent::token();

		$env        = parent::$config->env;

        $endpoint 	= 'https://'.$env.'.gw.mfs-tkl.com/tkash/b2c/v3/sync';

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
            //"creditSyncRequest": {"username": "string","password": "string","msisdn": "254772071999","amount": 10,"brandId": "string","info1": "Institution/business name","info2": "string","info3": "string","externalRef": "string"
			"nameLookupRequest" => array(
                "username" 		=> parent::$config->password,
                "password" 		=> parent::$config->password,
                "msisdn" 		=> $phone,
                "amount" 		=> 0,
                "brandId" 		=> $brandId,
                "info1" 		=> $info1,
                "info2" 		=> $info2,
                "info3" 		=> $info3,
                "externalRef" 	=> $ref
			)
	   	);
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $response = curl_exec($curl);
		
		return json_decode($response, true);

		/**
         * {"creditSyncResponse":{"resultCode":"0","message":"transaction message","tklReference":"45134","originalAmount":10,"finalAmount":10,"trnxFee":1,"externalRef":"ext001","salesOrderNo":"549425","newBalance":99893,"destinationMSISDN":"254772071999","networkName":"business name","info":"001001001"}}400 –Bad Reque
         */
    }
    
}
