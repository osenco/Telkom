<?php

namespace Osen\Telkom;

use Osen\Telkom\Telkom;

class C2B extends Telkom
{
    public static function registerUrls($callback = null)
    {
        $token      = parent::token();

        $endpoint   = (parent::$config->env == 'live') ? 
            'https://prod.gw.mfs-tkl.com/consumer/v3/registerurl' : 
            'https://preprod.gw.mfs-tkl.com/consumer/v3/registerurl';

        $curl       = curl_init();
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
            "registerUrlRequest" => array(
                "consumerId"            => parent::$config->id,
                "notificationUrl"       => parent::$config->confirmation_url,
                "notificationUrlType"   => 'REST',
                "validationUrl"         => parent::$config->validation_url,
                "validationUrlType"     => 'REST',
                "creationDate"          => "13-JUN-2018T12:15:00".date('d-M-YTH:i:s').date('Y-m-d\TH:i:sO')
            )
        );
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $response   = curl_exec($curl);

        $content    = json_decode($response, true);
        
        if(is_null($callback)){
            $status     = ($response || isset($content['ResponseDescription'])) 
                ? $content['ResponseDescription'] 
                : 'Sorry could not connect to Daraja. Check your connection/configuration and try again.';
            return array('Registration status' => $status);
        } else {
            return \call_user_func_array($callback, $content);
        }
    }

    public static function updateRegister($service, $callback = null)
    {
        $token      = parent::token();

        $endpoint   = (parent::$config->env == 'live') ? 
            'https://prod.gw.mfs-tkl.com/update-consumer/v3/updateUrl' : 
            'https://preprod.gw.mfs-tkl.com/update-consumer/v3/updateUrl';

        $curl       = curl_init();
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
            "updateUrlRequest" => array(
                "consumerId"            => parent::$config->id,
                "notificationUrl"       => parent::$config->confirmation_url,
                "notificationUrlType"   => 'REST',
                "validationUrl"         => parent::$config->validation_url,
                "validationUrlType"     => 'REST',
                "creationDate"          => "13-JUN-2018T12:15:00".date('d-M-YTH:i:s')
            )
        );
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $response   = curl_exec($curl);

        $content    = json_decode($response, true);
        
        if(is_null($callback)){
            $status     = ($response || isset($content['updatedUrlResponse'])) 
                ? $content['updatedUrlResponse'] 
                : 'Sorry could not connect to Telkom. Check your connection/configuration and try again.';
            return array('Registration status' => $status);
        } else {
            return \call_user_func_array($callback, $content);
        }
    }

    /**
     * This API shall perform registration 3rd party URL for USSD or sms services which are ONDEMAND, SUBSCRIPTION or BULK. If type is ONDEMAND or SUBSCRIPTION, the parameter serviceId is required. If type is ‘USSD’the parameter ussdLevel is required. If type is ‘ONDEMAND’ or ‘SUBSCRIPTION’ ‘serviceId’ is required.
     */
    public static function registerTelkom($service, $type = 'USSD', $callback = null)
    {
        $token      = parent::token();

        $endpoint   = (parent::$config->env == 'live') ? 
            'https://prod.gw.mfs-tkl.com/serviceRegistration/v3/register' : 
            'https://preprod.gw.mfs-tkl.com/serviceRegistration/v3/register';

        $curl       = curl_init();
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
            "registrationRequest"   => array(
                "shortCode"         => parent::$config->shortcode,
                "url"               => parent::$config->result_url,
                "type"              => $type,
                "serviceId"         => $service,
                "ussdLevel"         => "Basic",
                "email"             => "string"
            )
        );
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $response   = curl_exec($curl);

        $content    = json_decode($response, true);
        
        if(is_null($callback)){
            $status     = ($response || isset($content['updatedUrlResponse'])) 
                ? $content['updatedUrlResponse'] 
                : 'Sorry could not connect to Telkom. Check your connection/configuration and try again.';
            return array('Registration status' => $status);
        } else {
            return \call_user_func_array($callback, $content);
        }

        // Sample Response
        // {
        // "result": "string",
        // the HTTP codes)
        // "referenceId": "string"
        // }
        // 400 – Bad Request
        // 409 – Conflict
        // 500 – Internal Server Error
    }
    
}
