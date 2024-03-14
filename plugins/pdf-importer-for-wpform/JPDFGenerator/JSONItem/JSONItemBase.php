<?php


namespace rnpdfimporter\JPDFGenerator\JSONItem;


use rnpdfimporter\JPDFGenerator\JPDFGenerator;
use rnpdfimporter\PDFLib\utils\strings;

abstract class JSONItemBase
{
    /** @var JSONItemBase */
    public $Parent;
    public $data;
    /** @var JPDFGenerator */
    public $Generator;
    public $Offset=0;
    public $Length=0;
    public function __construct($generator, $parent,$data)
    {
        $this->Generator=$generator;
        $this->Parent=$parent;
        $this->data=$data;
    }


    public function SetFromData($path,$value)
    {
        if(!\is_array($path))
            $path=explode('/',$path);

        if($this->data==null)
            $this->data=new \stdClass();

        $obj=$this->data;
        while(count($path)>0)
        {
            $currentPath=\array_shift($path);
            if(!isset($obj->$currentPath))
            {
                if(count($path)>0)
                    $obj->$currentPath=new \stdClass();
                else
                    $obj->$currentPath=$value;
            }

            $obj=$obj->$currentPath;
        }


    }

    public function GetFromData($path,$defaultValue=null)
    {
        if(!\is_array($path))
            $path=[$path];

        $value=null;
        $obj=$this->data;
        while(count($path)>0)
        {
            $currentPath=\array_shift($path);
            if(!isset($obj->$currentPath))
                return $defaultValue;

            $obj=$obj->$currentPath;
        }

        return $obj;
    }

    public function CalculateLength($str){
        return \strlen($str);
    }

    public function GetText($offset=0){
        $str=$this->InternalGetText();
        $this->Length=$this->CalculateLength($str);
        $this->Offset=$offset;
        return $str;
    }


    public function IsReference(){
        $val=trim($this->GetText());
        return strlen($val)>0&&$val[strlen($val)-1]=='R';
    }

    public function MaybeGetDictionary(){
        $objectToCheck=$this;
        if($this instanceof IndirectObjectJsonItem)
            $objectToCheck=$this->Object;

        if($objectToCheck instanceof DictionaryObjectItem)
            return $objectToCheck;




        if($objectToCheck->IsReference())
        {
            $field=$objectToCheck->Generator->GetObjectByTag($objectToCheck->GetText());
            if($field==null)
                return null;
            return $field->MaybeGetDictionary();
        }
    }


    public function SetValue($key,$value)
    {
        $dictionary=$this->MaybeGetDictionary();
        if($dictionary!=null)
            return $dictionary->SetValue($key,$value);
    }


    public function GetValue($key)
    {
        $dictionary=$this->MaybeGetDictionary();
        if($dictionary!=null)
            return $dictionary->GetValue($key);

        return null;


    }

    public function RemoveKey($key)
    {
        $dictionary=$this->MaybeGetDictionary();
        if($dictionary!=null)
            return $dictionary->RemoveKey($key);

        return null;

    }

    public function GetNumberValue($key)
    {

        $dictionary=$this->MaybeGetDictionary();
        if($dictionary!=null)
            return $dictionary->GetNumberValue($key);

        return 0;
    }

    public function GetRef(){
        if($this instanceof IndirectObjectJsonItem)
            return $this->GetObjectNumber().' '.$this->GetGenerationNumber().' '.'R';

        $parent=$this->Parent;
        if($parent!=null)
            return $parent->GetRef();

        return '';




    }

    /**
     * @return String
     */
    public abstract function InternalGetText();
}