<?php

namespace App\Http\Controllers;

use App\User;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use Osen\Telkom\STK;

class TelkomController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        STK::init(
            array(
                'env'               => 'sandbox',
                'type'              => 4,
                'shortcode'         => '173527',
                'headoffice'          => '173527',
                'key'               => 'Your Consumer Key',
                'secret'            => 'Your Consumer Secret',
                'passkey'           => 'Your Online Passkey',
                'validation_url'    => url('telkom/validate'),
                'confirmation_url'  => url('telkom/confirm'),
                'callback_url'      => url('telkom/reconcile'),
                'results_url'       => url('telkom/timeout'),
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pay(Request $request)
    {
        $data = $request->all();

        try {
            $res = STK($request->phone, $request->amount, $request->reference);

            if(!isset($res['errorCode'])){
                $data['ref']            = $res->MerchantRequestID;
                $payment                = Payment::create($data);
        
                if($payment){
                    return array('msg' => 'saved' );
                } else {
                    return array('msg' => 'failed' );
                }

                return Redirect::back();
            }
        } catch (\Exception $e) {
            return array('msg' => $e->getMessage() );
            return Redirect::back();
        }
    }

    public function reconcile(Request $request, $method = 'telkom')
    {
        if ($method == 'telkom') {
            $response = STK::reconcile(function ($data)
            {
                $payment = Payment::where('telkom', $data['MerchantRequestID'])->first();
                $payment->status = 'Paid';

                return $payment->save();
            });
        }
    }

    public function validation()
    {
        return STK::validate();
    }

    public function confirmation()
    {
        return STK::confirm();
    }

    public function results()
    {
        return STK::results();
    }

    public function timeout()
    {
        return STK::timeout();
    }
}
