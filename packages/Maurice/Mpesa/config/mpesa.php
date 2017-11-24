<?php

return [
    'status' => 'sandbox',
    'live_url' => 'https://api.safaricom.co.ke/mpesa',
    'sandbox_url' => 'https://sandbox.safaricom.co.ke/mpesa',
    
    'token_url' =>'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials',
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
    | Callback Method
    |--------------------------------------------------------------------------
    |
    | This is the request method to be used on the Callback URL on communication
    | with your server.
    |
    | e.g. GET | POST
    |
    */

    'callback_method' => 'POST',

];
