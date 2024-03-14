<?php


namespace rnpdfimporter\PDFLib\core\objects;


use Exception;
use rnpdfimporter\PDFLib\core\integration\Map;
use rnpdfimporter\PDFLib\utils\strings;

class PDFRef extends PDFObject
{
    /** @var Map */
    static $pool;
    public $objectNumber;
    public $generationNumber;
    public $tag;

    public static function of($objectNumber,$generationNumber=0)
    {
        $tag=$objectNumber.' '.$generationNumber.' R';
        $instance=PDFRef::$pool->get($tag);

        if(!$instance)
        {
            $instance=new PDFRef("{}",$objectNumber,$generationNumber);
            self::$pool->set($tag,$instance);
        }
        return $instance;
    }

    public function __construct($enforcer,$objectNumber,$generationNumber)
    {
        if($enforcer!=="{}")
            throw new Exception('Private constructor error');

        $this->objectNumber=$objectNumber;
        $this->generationNumber=$generationNumber;
        $this->tag=$this->objectNumber.' '.$this->generationNumber.' R';

    }

    public function _clone($_context)
    {
        return $this;
    }

    public function __toString()
    {
        return $this->tag;
    }

    public function sizeInBytes()
    {
        return \strlen($this->tag);
    }

    public function copyBytesInto($burffer, $offset)
    {
        $offset+=strings::copyStringIntoBuffer($this->tag,$burffer,$offset);
        return \strlen($this->tag);
    }


}
PDFRef::$pool=new Map();
$Enforcer=array();