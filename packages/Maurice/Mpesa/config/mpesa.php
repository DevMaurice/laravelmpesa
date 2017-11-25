<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    |
    | The app ststu
    */
    'status' => 'sandbox',
     /*
    |--------------------------------------------------------------------------
    | Live url
    |--------------------------------------------------------------------------
    |
    | The url used by the application for live transactions. (All transaction request will be appendede here)
    */
    'live_url' => 'https://api.safaricom.co.ke/mpesa',
     /*
    |--------------------------------------------------------------------------
    | sandbox url
    |--------------------------------------------------------------------------
    |
    | The url used by the application for Demo transactions. (All transaction request will be appendede here)
    */
    'sandbox_url' => 'https://sandbox.safaricom.co.ke/mpesa',

     /*
    |--------------------------------------------------------------------------
    | Initiator
    |--------------------------------------------------------------------------
    |
    | Identifies an entity on the Mpesa side.
    */
    'initiator' => 'testapi',

    /*
    |--------------------------------------------------------------------------
    | Credentials
    |--------------------------------------------------------------------------
    |
    | These are the credentials to be used to transact with the M-Pesa API
    */

    'consumer_key' => 'GO40ml9nyLoxyfGy6CAQWpA0d91gARyc',

    'consumer_secret' => '7Sa9J5JGX6hPdGRZ',

    /*
    |--------------------------------------------------------------------------
    | Paybill Number
    |--------------------------------------------------------------------------
    |
    | This is a registered Paybill Number that will be used as the Merchant ID
    | on every transaction. This is also the account to be debited.
    |
    |
    |
    */
    'short_code' => 174379,

     /*
    |--------------------------------------------------------------------------
    | STK Callback URL
    |--------------------------------------------------------------------------
    |
    | This is a fully qualified endpoint that will be be queried by Safaricom's
    | API on completion or failure of the transaction.
    |
    */

    'stk_callback' => 'http://1a88ca0f.ngrok.io:80/api/mpesa/callback',

    /*
    |--------------------------------------------------------------------------
    | Identity Validation Callback URL
    |--------------------------------------------------------------------------
    |
    | This is a fully qualified endpoint that will be be queried by Safaricom's
    | API on completion or failure of the transaction.
    |
    */

    'id_validation_callback' => 'http://1a88ca0f.ngrok.io:80/api/mpesa/callback',

    /*
    |--------------------------------------------------------------------------
    | Secret Key
    |--------------------------------------------------------------------------
    |
    |Key is generate from a base64.
    |
    |
    */

    'key' => 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919',

];
