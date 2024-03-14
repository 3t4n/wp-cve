<?php


namespace rnpdfimporter\PDFLib\utils;


class Cache
{
    public $populate;
    public $value;
    public function __construct($populate)
    {
        $this->populate=$populate;
        $this->value=null;
    }

    static function populatedBy($populate){
        return new Cache($populate);
    }

    public function getValue(){
        return $this->value;
    }

    public function access(){
        if(!$this->value)$this->value=$this->populate();

        return $this->value;

    }

    public function invalidate(){
        $this->value=null;
    }


}