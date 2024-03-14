<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem;


use rnpdfimporter\JPDFGenerator\JSONItem\Streams\StreamJSONItemBase;
use rnpdfimporter\JPDFGenerator\Utils\Chars;

class IndirectObjectJsonItem extends JSONItemBase
{
    /** @var JSONItemBase */
    public $Object;

    public function __construct($generator, $parent,$data)
    {
        parent::__construct($generator, $parent,$data);
        if($data instanceof JSONItemBase)
            $this->Object=$data;
        else
            $this->Object=JSONFactory::GetItem($generator, $this,$this->GetFromData('Object',null));
    }


    public function SetObjectNumber($value){
         $this->SetFromData('ON',$value);
    }
    public function GetObjectNumber(){

        return $this->GetFromData('ON','');
    }


    public function GetGenerationNumber(){
        return $this->GetFromData('GN','');
    }



    public function InternalGetText()
    {
        $str='';
        $str.=$this->GetFromData('ON',0).' ';
        $str.=$this->GetFromData('GN',0);
        $str.=" obj\n";

        $str.= $this->Object->GetText(0);
        $str.="\nendobj\n\n";


        return $str;
    }
/*
    public function CalculateLength($str)
    {
        if($this->Object instanceof StreamJSONItemBase)
        {
            return \strlen($str);
        }else{
            return \mb_strlen($str);
        }
    }*/
    public function SetGenerationNumber($value)
    {
        $this->SetFromData('GN',$value);

    }

}