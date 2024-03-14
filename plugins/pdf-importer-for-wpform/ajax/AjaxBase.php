<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/1/2019
 * Time: 6:32 AM
 */

namespace rnpdfimporter\ajax;


use Exception;
use rnpdfimporter\core\Loader;
use rnpdfimporter\core\Utils\JSONSanitizer;

abstract class AjaxBase
{
    public $data=null;
    public $prefix;
    public $defaultNonce;
    private $privatePrefix;
    private $publicPrefix;
    private $ReturnType;
    private $returnResult;


    /** @var Loader */
    public $Loader;

    public function __construct($loader,$returnResult=false)
    {
        $this->returnResult=$returnResult;
        $this->defaultNonce=$this->GetDefaultNonce();
        $this->Loader=$loader;
        $this->prefix=$this->Loader->Prefix;
        $this->privatePrefix='wp_ajax_'.$this->Loader->Prefix.'_';
        $this->publicPrefix='wp_ajax_nopriv_'.$this->Loader->Prefix.'_';
        $this->RegisterHooks();

    }



    abstract function GetDefaultNonce();


    protected function RegisterPrivate($name,$methodName,$capability='administrator', $validateNonce=true,$nonceName=false)
    {
        if($nonceName==false)
            $nonceName=$this->defaultNonce;

        \add_action($this->privatePrefix.$name,function() use($methodName,$validateNonce,$nonceName,$capability){

            if($validateNonce)
                $this->VerifyNonce($nonceName);
            if($capability!='')
                if(!\current_user_can($capability))
                    $this->SendErrorMessage('Forbidden');
            $this->$methodName();
            die();
        });
    }

    protected function RegisterPublic($name,$methodName, $validateNonce=true,$nonceName=false)
    {
        if($nonceName==false)
            $nonceName=$this->defaultNonce;
        $this->RegisterPrivate($name,$methodName,'',$validateNonce,$nonceName);
        \add_action($this->publicPrefix.$name,function() use($methodName,$validateNonce,$nonceName){

            if($validateNonce)
                $this->VerifyNonce($nonceName);
            $this->$methodName();
            die();
        });
    }

    public function ProcessRequest($dictionary)
    {
        if($this->data==null)
            if(isset($_POST['data']))
            {
                $this->data = JSONSanitizer::Sanitize(json_decode(stripslashes($_POST['data'])),$dictionary);

            }
            else
                if(isset($_GET['data']))
                {
                    $this->data = JSONSanitizer::Sanitize(json_decode(stripslashes($_GET['data'])),$dictionary);
                }
                else
                    $this->SendErrorMessage('Invalid operation, data does not exists');
        return $this->data;

    }

    public function GetData(){
        if($this->data==null)
            $this->ProcessRequest(null);
       return $this->data;
    }

    public function GetOptional($propertyName,$defaultValue='')
    {
        if(!isset($_POST['data'])&&!isset($_GET['data']))
            return $defaultValue;
        if(!property_exists($this->GetData(),$propertyName))
        {
            return $defaultValue;
        }

        return $this->data->$propertyName;
    }

    public function GetRequired($propertyName)
    {
        if(!property_exists($this->GetData(),$propertyName))
        {
            $this->SendErrorMessage('Property '.$propertyName. ' does not exists');
        }

        return $this->data->$propertyName;
    }

    public function SendErrorMessage($message){

        echo json_encode(array(
            'success'=>false,
            'errorMessage'=>$message
        ));
        die();
    }

    /**
     * @param $e Exception|FriendlyException
     */
    public function SendException($e,$prefixLogDetails='',$debugModeEnabled=false){
        $friendlyMessage=__("An error occurred");

        if($e instanceof FriendlyException)
            $friendlyMessage=$e->GetFriendlyException();

        $message=$prefixLogDetails;
        if($message!='')
            $message.=' ';


        $message.=$e->getMessage().' - '.$e->getTraceAsString();

        $detail='';
        if($debugModeEnabled)
            $detail=$message;
        LogManager::Log(LogManager::TYPE_ERROR,$message);
        echo json_encode(array(
            'success'=>false,
            'errorMessage'=>$friendlyMessage,
            'detail'=>$detail
        ));
        die();
    }

    public function SendSuccessMessage($data='')
    {
        if($this->returnResult)
        {
            return $data;
        }


        echo json_encode(array(
            'success'=>true,
            'result'=>$data
        ));
        die();


    }



    abstract protected function RegisterHooks();

    private function VerifyNonce($nonceName)
    {
        $nonce='';
        if(isset($_GET['_nonce']))
            $nonce=\strval($_GET['_nonce']);
        if(isset($_POST['_nonce']))
            $nonce=\strval($_POST['_nonce']);

        if($nonce==''||!\wp_verify_nonce($nonce, $this->prefix.'_'.$nonceName))
            $this->SendErrorMessage(_('Invalid request, please refresh the screen and try again'));
    }
}