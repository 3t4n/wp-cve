<?php
namespace rednaoformpdfbuilder\Utils;

class HttpPostProcessor{
    public $data;
    public function __construct()
    {
        $this->data=\json_decode(stripslashes($_POST['data']));
    }

    public function GetRequired($propertyName)
    {
        if(!property_exists($this->data,$propertyName))
        {
            $this->SendErrorMessage('Property '.$propertyName. ' does not exists');
        }

        return $this->data->$propertyName;
    }

    public function SendErrorMessage($message){
        echo \json_encode(array(
            'success'=>false,
            'errorMessage'=>$message
        ));
        die();
    }

    public function SendSuccessMessage($data='')
    {
        echo \json_encode(array(
            'success'=>true,
            'result'=>$data
        ));
        die();
    }
}