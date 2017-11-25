<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
   //Mpesa::STKPushQuery('ws_CO_25112017110238906');
   //Mpesa::transactionStatus('ws_CO_25112017110238906');

    Mpesa::STKPushSimulation('254714692255',10,'bd0904979873');
    // "MerchantRequestID":"13760-120003-1",\n
    // "CheckoutRequestID":"ws_CO_25112017110238906",\n
    // "ResponseCode": "0",\n
    // "ResponseDescription":"Success. Request accepted for processing",\n
    // "CustomerMessage":"Success. Request accepted for processing"\n


   // Mpesa::c2b('B4687326OOOL',10,'254714692255','bd0904979873');
});
