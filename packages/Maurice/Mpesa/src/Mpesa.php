<?php

namespace Maurice\Mpesa;

use Carbon\Carbon;
use Illuminate\Contracts\Config\Repository;
use function GuzzleHttp\json_encode;
class Mpesa
{
    /**
     * @var ConfigurationStore
     */
    public $status;

    public $url;

    public $config;

    public $paybill;

    public $initiator;

    public $callback;

    public $auth;
    /**
     * Create a new Mpesa instance
     */
    public function __construct(Repository $config,Auth $auth)
    {
        $this->config=$config;
        $this->auth = $auth;
        $this->load();
    }


    private function load(){
        $this->status= $this->config->get('mpesa.status');

        $this->paybill = $this->config->get('mpesa.short_code');
        $this->initiator = $this->config->get('mpesa.initiator');  //stk_callback

        $this->callback = $this->config->get('mpesa.stk_callback');


        if($this->status === 'sandbox'){
            $this->url = $this->config->get('mpesa.sandbox_url');
        }else{
          $this->url = $this->config->get('mpesa.live_url');
        }
    }

    private  function getTimestamp(){
        return Carbon::now()->format('YmdHis');;
    }

    private function getPassword(){
        return \base64_encode($this->paybill.$this->auth->getSecretKey().$this->getTimestamp());
    }

    private function curlExec($curl_post_data,$url){
        dd('bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919',$this->auth->getSecretKey(),$this->getPassword(),$this->getTimestamp());
        dd($this->auth->getToken(),$curl_post_data,$url);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$this->auth->getToken()));
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $curl_response = curl_exec($curl);

        if(curl_error($curl))
        {
            return curl_error($curl);
        }
        return $curl_response;
    }
    /**
     * Use this function to initiate a reversal request
     * @param $CommandID | Takes only 'TransactionReversal' Command id
     * @param $Initiator | The name of Initiator to initiating  the request
     * @param $SecurityCredential |     Encrypted Credential of user getting transaction amount
     * @param $TransactionID | Organization Receiving the funds
     * @param $Amount | Amount
     * @param $ReceiverParty | Organization /MSISDN sending the transaction
     * @param $RecieverIdentifierType | Type of organization receiving the transaction
     * @param $ResultURL | The path that stores information of transaction
     * @param $QueueTimeOutURL | The path that stores information of time out transaction
     * @param $Remarks | Comments that are sent along with the transaction.
     * @param $Occasion |   Optional Parameter
     * @return mixed|string
     */
    public function reversal($Initiator, $SecurityCredential, $TransactionID, $Amount, $ReceiverParty, $RecieverIdentifierType, $ResultURL, $QueueTimeOutURL, $Remarks, $Occasion){
        $url = $this->url.'/reversal/v1/request';
        $curl_post_data = array(
            'CommandID' => 'TransactionReversal',
            'Initiator' => $Initiator,
            'SecurityCredential' => $SecurityCredential,
            'TransactionID' => $TransactionID,
            'Amount' => $Amount,
            'ReceiverParty' => $ReceiverParty,
            'RecieverIdentifierType' => $RecieverIdentifierType,
            'ResultURL' => $ResultURL,
            'QueueTimeOutURL' => $QueueTimeOutURL,
            'Remarks' => $Remarks,
            'Occasion' => $Occasion
        );
        $curl_response = $this->curlExec($curl_post_data,$url);

        return $curl_response;
    }
    /**
     * @param $InitiatorName |  This is the credential/username used to authenticate the transaction request.
     * @param $SecurityCredential | Base64 encoded string of the B2C short code and password, which is encrypted using M-Pesa public key and validates the transaction on M-Pesa Core system.
     * @param $CommandID | Unique command for each transaction type e.g. SalaryPayment, BusinessPayment, PromotionPayment
     * @param $Amount | The amount being transacted
     * @param $PartyA | Organization’s shortcode initiating the transaction.
     * @param $PartyB | Phone number receiving the transaction
     * @param $Remarks | Comments that are sent along with the transaction.
     * @param $QueueTimeOutURL | The timeout end-point that receives a timeout response.
     * @param $ResultURL | The end-point that receives the response of the transaction
     * @param $Occasion |   Optional
     * @return string
     */
    public function b2c($InitiatorName, $SecurityCredential, $CommandID, $Amount, $PartyA, $PartyB, $Remarks, $QueueTimeOutURL, $ResultURL, $Occasion){
        $url = $this->url.'/b2c/v1/paymentrequest';
        $curl_post_data = array(
            'InitiatorName' => $InitiatorName,
            'SecurityCredential' => $SecurityCredential,
            'CommandID' => $CommandID ,
            'Amount' => $Amount,
            'PartyA' => $PartyA ,
            'PartyB' => $PartyB,
            'Remarks' => $Remarks,
            'QueueTimeOutURL' => $QueueTimeOutURL,
            'ResultURL' => $ResultURL,
            'Occasion' => $Occasion
        );
        $curl_response = $this->curlExec($curl_post_data,$url);

        return $curl_response;
    }
    /**
     * Use this function to initiate a B2C transaction
     * @param $ShortCode | 6 digit M-Pesa Till Number or PayBill Number
     * @param $CommandID | Unique command for each transaction type.
     * @param $Amount | The amount been transacted.
     * @param $Msisdn | MSISDN (phone number) sending the transaction, start with country code without the plus(+) sign.
     * @param $BillRefNumber |  Bill Reference Number (Optional).
     * @return mixed|string
     */
    public   function  c2b($CommandID, $Amount, $Msisdn, $BillRefNumber ){
        $url = $this->url.'/c2b/v1/simulate';

        $curl_post_data = array(
            'ShortCode' => $this->paybill,
            'CommandID' => $CommandID,
            'Amount' => $Amount,
            'Msisdn' => $Msisdn,
            'BillRefNumber' => $BillRefNumber
        );
        $curl_response = $this->curlExec($curl_post_data,$url);

        return $curl_response;
    }
    /**
     * Use this to initiate a balance inquiry request
     * @param $CommandID | A unique command passed to the M-Pesa system.
     * @param $Initiator |  This is the credential/username used to authenticate the transaction request.
     * @param $SecurityCredential | Base64 encoded string of the M-Pesa short code and password, which is encrypted using M-Pesa public key and validates the transaction on M-Pesa Core system.
     * @param $PartyA | Type of organization receiving the transaction
     * @param $IdentifierType |Type of organization receiving the transaction
     * @param $Remarks | Comments that are sent along with the transaction.
     * @param $QueueTimeOutURL | The path that stores information of time out transaction
     * @param $ResultURL |  The path that stores information of transaction
     * @return mixed|string
     */
    public function accountBalance($CommandID, $Initiator, $SecurityCredential, $PartyA, $IdentifierType, $Remarks, $QueueTimeOutURL, $ResultURL){
        $url = $this->url.'/accountbalance/v1/query';
        $curl_post_data = array(
            'CommandID' => $CommandID,
            'Initiator' => $Initiator,
            'SecurityCredential' => $SecurityCredential,
            'PartyA' => $PartyA,
            'IdentifierType' => $IdentifierType,
            'Remarks' => $Remarks,
            'QueueTimeOutURL' => $QueueTimeOutURL,
            'ResultURL' => $ResultURL
        );
        $curl_response = $this->curlExec($curl_post_data,$url);

        return $curl_response;
    }
    /**
     * Use this function to make a transaction status request
     * @param $Initiator | The name of Initiator to initiating the request.
     * @param $SecurityCredential |     Base64 encoded string of the M-Pesa short code and password, which is encrypted using M-Pesa public key and validates the transaction on M-Pesa Core system.
     * @param $CommandID | Unique command for each transaction type, possible values are: TransactionStatusQuery.
     * @param $TransactionID | Organization Receiving the funds.
     * @param $PartyA | Organization/MSISDN sending the transaction
     * @param $IdentifierType | Type of organization receiving the transaction
     * @param $ResultURL | The path that stores information of transaction
     * @param $QueueTimeOutURL | The path that stores information of time out transaction
     * @param $Remarks |    Comments that are sent along with the transaction
     * @param $Occasion |   Optional Parameter
     * @return mixed|string
     */
    public function transactionStatus($TransactionID,$Remarks = "TransactionStatusQuery", $Occasion="TransactionStatusQuery"){
        $url = $this->url.'/transactionstatus/v1/query';
        $curl_post_data = array(
            'Initiator' => $this->initiator,
            'SecurityCredential' => $this->getPassword(),
            'CommandID' => 'TransactionStatusQuery',
            'TransactionID' => $TransactionID,
            'PartyA' =>$this->paybill,
            'IdentifierType' => '1',
            'ResultURL' => $this->callback,
            'QueueTimeOutURL' => $this->callback,
            'Remarks' => $Remarks,
            'Occasion' => $Occasion
        );
       $curl_response = $this->curlExec($curl_post_data,$url);
        dd($curl_response);
        return $curl_response;
    }
    /**
     * Use this function to initiate a B2B request
     * @param $Initiator | This is the credential/username used to authenticate the transaction request.
     * @param $SecurityCredential | Base64 encoded string of the B2B short code and password, which is encrypted using M-Pesa public key and validates the transaction on M-Pesa Core system.
     * @param $Amount | Base64 encoded string of the B2B short code and password, which is encrypted using M-Pesa public key and validates the transaction on M-Pesa Core system.
     * @param $PartyA | Organization’s short code initiating the transaction.
     * @param $PartyB | Organization’s short code receiving the funds being transacted.
     * @param $Remarks | Comments that are sent along with the transaction.
     * @param $QueueTimeOutURL | The path that stores information of time out transactions.it should be properly validated to make sure that it contains the port, URI and domain name or publicly available IP.
     * @param $ResultURL | The path that receives results from M-Pesa it should be properly validated to make sure that it contains the port, URI and domain name or publicly available IP.
     * @param $AccountReference | Account Reference mandatory for “BusinessPaybill” CommandID.
     * @param $commandID | Unique command for each transaction type, possible values are: BusinessPayBill, MerchantToMerchantTransfer, MerchantTransferFromMerchantToWorking, MerchantServicesMMFAccountTransfer, AgencyFloatAdvance
     * @param $SenderIdentifierType | Type of organization sending the transaction.
     * @param $RecieverIdentifierType | Type of organization receiving the funds being transacted.
     * @return mixed|string
     */
    public function b2b($Initiator, $SecurityCredential, $Amount, $PartyA, $PartyB, $Remarks, $QueueTimeOutURL, $ResultURL, $AccountReference, $commandID, $SenderIdentifierType, $RecieverIdentifierType){

        $url = $this->url.'/b2b/v1/paymentrequest';
        $curl_post_data = array(
            'Initiator' => $Initiator,
            'SecurityCredential' => $SecurityCredential,
            'CommandID' => $commandID,
            'SenderIdentifierType' => $SenderIdentifierType,
            'RecieverIdentifierType' => $RecieverIdentifierType,
            'Amount' => $Amount,
            'PartyA' => $PartyA,
            'PartyB' => $PartyB,
            'AccountReference' => $AccountReference,
            'Remarks' => $Remarks,
            'QueueTimeOutURL' => $QueueTimeOutURL,
            'ResultURL' => $ResultURL
        );
        $curl_response = $this->curlExec($curl_post_data,$url);

        return $curl_response;
    }
    /**
     * Use this function to initiate an STKPush Simulation
     * @param $BusinessShortCode | The organization shortcode used to receive the transaction.
     * @param $LipaNaMpesaPasskey | The password for encrypting the request. This is generated by base64 encoding BusinessShortcode, Passkey and Timestamp.
     * @param $TransactionType | The transaction type to be used for this request. Only CustomerPayBillOnline is supported.
     * @param $Amount | The amount to be transacted.
     * @param $PartyA | The MSISDN sending the funds.
     * @param $PartyB | The organization shortcode receiving the funds
     * @param $PhoneNumber | The MSISDN sending the funds.
     * @param $CallBackURL | The url to where responses from M-Pesa will be sent to.
     * @param $AccountReference | Used with M-Pesa PayBills.
     * @param $TransactionDesc | A description of the transaction.
     * @param $Remark | Remarks
     * @return mixed|string
     */
    public function STKPushSimulation( $PhoneNumber,$Amount, $AccountReference, $TransactionDesc = 'CustomerPayBillOnline', $Remark='STK Simulation'){
         $url = $this->url.'/stkpush/v1/processrequest';

        $curl_post_data = array(
            'BusinessShortCode' => $this->paybill,
            'Password' => $this->getPassword(),
            'Timestamp' => $this->getTimestamp(),
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $Amount,
            'PartyA' => $PhoneNumber,
            'PartyB' => $this->paybill,
            'PhoneNumber' => $PhoneNumber,
            'CallBackURL' => $this->callback,
            'AccountReference' => $AccountReference,
            'TransactionDesc' => $TransactionDesc,
            'Remark'=> $Remark
        );

        $curl_response = $this->curlExec($curl_post_data,$url);

       dd($curl_response);

        return $curl_response;
    }
    /**
     * Use this function to initiate an STKPush Status Query request.
     * @param $checkoutRequestID | Checkout RequestID
     * @param $businessShortCode | Business Short Code
     * @param $password | Password
     * @param $timestamp | Timestamp
     * @return mixed|string
     */
    public  function STKPushQuery($checkoutRequestID){
        $url = $this->url.'/stkpushquery/v1/query';

        $curl_post_data = array(
            'BusinessShortCode' => $this->paybill,
            'Password' => $this->getPassword(),
            'Timestamp' => $this->getTimestamp(),
            'CheckoutRequestID' => $checkoutRequestID
        );

        $curl_response = $this->curlExec($curl_post_data,$url);

        dd($curl_response);
        return $curl_response;
    }


    /**
     *Use this function to confirm all transactions in callback routes
     */
    public function finishTransaction(){
        $resultArray=[
            "ResultDesc"=>"Confirmation Service request accepted successfully",
            "ResultCode"=>"0"
        ];
        header('Content-Type: application/json');
        echo json_encode($resultArray);
    }
    /**
     *Use this function to get callback data posted in callback routes
     */
    public function getDataFromCallback(){
        $callbackJSONData=file_get_contents('php://input');
        return $callbackJSONData;
    }
}
