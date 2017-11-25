<?php

namespace Maurice\Mpesa;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
class Auth
{
    public $config;

    public $cache;
    /**
     * Create a new Mpesa instance
     */
    public function __construct(Repository $config, CacheRepository $cache)
    {
        $this->config = $config;
        $this->cache = $cache;
    }

    public function getToken(){
        // if($token = $this->cache->get('token')){
        //     return $token;
        // }

        $token = $this->setToken($this->generateToken());

       // dd($token);

        return $token;
    }

    /**
     * This is used to generate tokens for the live environment
     * @return mixed
     */
    public function generateToken(){
        if($this->config->get('mpesa.status')==='sandbox'){
           $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        }else{
             $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        }

        //dd($url);

        return $this->execToken($url);
    }

    private function execToken($url){
        //dd($url,$this->getSecretKey());
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$this->getSecretKey())); //setting a custom header
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $curl_response = curl_exec($curl);

        if(curl_error($curl))
        {
            return \Exception(json_decode(curl_error($curl)));
        }

        return json_decode($curl_response);
    }

    /**
     * Set token to cache
     *
     * @param [type] $body
     * @return void
     */
    public function setToken($body){
        //dd($body);

        $minutes = ($body->expires_in / 60) - 2;

        $this->cache->put('token', $body->access_token, $minutes);

        return $body->access_token;

    }

    public function getSecretKey(){

        return \base64_encode($this->config->get('mpesa.consumer_key').':'.$this->config->get('mpesa.consumer_secret'));
        //return 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
    }
}
