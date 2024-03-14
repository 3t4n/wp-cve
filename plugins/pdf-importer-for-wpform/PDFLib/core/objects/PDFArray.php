<?php


namespace rnpdfimporter\PDFLib\core\objects;


use Error;
use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\PDFContext;
use rnpdfimporter\PDFLib\core\syntax\CharCodes;

class PDFArray extends PDFObject
{
    /** @var PDFObject[] */
    public $array;
    /** @var PDFContext */
    public $context;

    /**
     * PDFArray constructor.
     */
    public function __construct($context)
    {
        $this->array=new ReferenceArray();
        $this->context=$context;
    }

    public static function withContext($context)
    {
        return new PDFArray($context);

    }

    public function size(){
        return count($this->array);
    }

    public function push($object)
    {
        $this->array[]=$object;
    }

    public function insert($index,$object)
    {
        \array_splice($this->array,$index,0,$object);
    }

    public function remove($index){
        unset($this->array[$index]);
        $this->array=\array_values($this->array);
    }

    public function set($indx,$object)
    {
        $this->array[$indx]=$object;
    }

    public function get($index){
        return $this->array[$index];
    }

    public function  lookupMaybe($index, ...$types) {
        return $this->context->lookupMaybe(
          $this->get($index),
          ...$types
        );
    }

    public function lookup($index,...$types)
    {
        return $this->context->lookup($this->get($index),...$types);
    }


    public function asRectangle(){
        if ($this->size() !== 4) throw new Error('Array is not a rectangle');

        $lowerLeftX = $this->lookup(0, PDFNumber::class)->asNumber();
        $lowerLeftY = $this->lookup(1, PDFNumber::class)->asNumber();
        $upperRightX = $this->lookup(2,  PDFNumber::class)->asNumber();
        $upperRightY = $this->lookup(3,  PDFNumber::class)->asNumber();

        $x = $lowerLeftX;
        $y = $lowerLeftY;
        $width = $upperRightX - $lowerLeftX;
        $height = $upperRightY - $lowerLeftY;

        return array('x'=>$x,'y'=>$y,'width'=>$width,'height'=>$height);
    }

    public function asArray(){
        return $this->array;
    }

    public function _clone($context)
    {
        $clone = PDFArray::withContext($context || $this->context);
        for ($idx = 0, $len = $this->size(); $idx < $len; $idx++) {
            $clone[]=$this->array[$idx];
        }
        return $clone;
    }

    public function __toString()
    {
        $arrayString = '[ ';
        for ($idx = 0, $len = $this->size(); $idx < $len; $idx++) {
            $arrayString .= $this->get($idx)->__toString();
            $arrayString .= ' ';
        }
        $arrayString .= ']';
        return $arrayString;
    }

    public function sizeInBytes()
    {
        $size = 3;
        for ($idx = 0, $len = $this->size(); $idx < $len; $idx++) {
            $size += $this->get($idx)->sizeInBytes() + 1;
        }
        return $size;
    }

    public function copyBytesInto($buffer, $offset)
    {
        $initialOffset = $offset;

        $buffer[$offset++] =CharCodes::LeftSquareBracket;
        $buffer[$offset++] = CharCodes::Space;
        for ($idx = 0, $len = $this->size(); $idx < $len; $idx++) {
            $offset += $this->get($idx)->copyBytesInto($buffer, $offset);
            $buffer[$offset++] = CharCodes::Space;
        }
        $buffer[$offset++] = CharCodes::RightSquareBracket;

        return $offset - $initialOffset;
    }


}

