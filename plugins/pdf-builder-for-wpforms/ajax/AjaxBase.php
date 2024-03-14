<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/1/2019
 * Time: 6:32 AM
 */

namespace rednaoformpdfbuilder\ajax;


use rednaoformpdfbuilder\core\Loader;

abstract class AjaxBase
{
    public $data=null;
    public $prefix;
    public $defaultNonce;
    private $privatePrefix;
    private $publicPrefix;

    /** @var Loader */
    public $Loader;

    public function __construct($loader,$prefix,$defaultNonce)
    {
        $this->defaultNonce=$defaultNonce;
        $this->Loader=$loader;
        $this->prefix=$prefix;
        $this->privatePrefix='wp_ajax_'.$prefix.'_';
        $this->publicPrefix='wp_ajax_nopriv_'.$prefix.'_';
        $this->RegisterHooks();

    }


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


    public function GetData(){
        if($this->data==null)
            if(isset($_POST['data']))
                $this->data=json_decode(stripslashes($_POST['data']));
            else
                if(isset($_GET['data']))
                    $this->data=json_decode(stripslashes($_GET['data']));
                else
                $this->SendErrorMessage('Invalid operation, data does not exists');
        return $this->data;
    }

    public function GetRequired($propertyName)
    {
        if(!property_exists($this->GetData(),$propertyName))
        {
            $this->SendErrorMessage('Property '.$propertyName. ' does not exists');
        }

        return $this->data->$propertyName;
    }

    public function GetOptional($propertyName,$defaultValue='')
    {
        if(!isset($_POST['data']))
            return $defaultValue;
        if(!property_exists($this->GetData(),$propertyName))
        {
            return $defaultValue;
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

    public function SendSuccessMessage($data='')
    {
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
            $nonce=$_GET['_nonce'];
        if(isset($_POST['_nonce']))
            $nonce=$_POST['_nonce'];

        if($nonce==''||!\wp_verify_nonce($nonce, $this->prefix.'_'.$nonceName))
            $this->SendErrorMessage(_('Invalid request, please refresh the screen and try again'));
    }
}