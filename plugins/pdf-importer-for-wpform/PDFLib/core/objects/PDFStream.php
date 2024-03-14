<?php


namespace rnpdfimporter\PDFLib\core\objects;


use Exception;
use rnpdfimporter\PDFLib\core\syntax\CharCodes;

class PDFStream extends PDFObject
{
    /** @var PDFDict */
    public $dict;


    public function __construct($dict)
    {
        $this->dict=$dict;
    }

    public function _clone($context)
    {
        throw new Exception('Method not implemented');
    }

    public function getContentsString(){
        throw new Exception('Method not implemented');
    }

    /**
     * @throws Exception
     * @return array()
     */
    public function getContents(){
        throw new Exception('Method not implemented');
    }

    public function getContentsSize(){
        throw new Exception('Method not implemented');
    }

    public function updateDict(){
        $contentsSize=$this->getContentsSize();
        $this->dict->set(PDFName::$Length,PDFNumber::of($contentsSize));
    }

    public function sizeInBytes(){
        $this->updateDict();
        return $this->dict->sizeInBytes()+$this->getContentsSize()+18;
    }

    public function __toString()
    {
        $this->updateDict();
        $streamString=$this->dict->__toString();
        $streamString.='\nstream\n';
        $streamString.=$this->getContentsString();
        $streamString .= '\nendstream';
        return $streamString;
    }

    public function copyBytesInto($buffer, $offset) {
        $this->updateDict();
        $initialOffset = $offset;

        $offset += $this->dict->copyBytesInto($buffer, $offset);
        $buffer[$offset++] = CharCodes::Newline;

        $buffer[$offset++] = CharCodes::s;
        $buffer[$offset++] = CharCodes::t;
        $buffer[$offset++] = CharCodes::r;
        $buffer[$offset++] = CharCodes::e;
        $buffer[$offset++] = CharCodes::a;
        $buffer[$offset++] = CharCodes::m;
        $buffer[$offset++] = CharCodes::Newline;

        $contents = $this->getContents();
        for ($idx = 0, $len = \count($contents); $idx < $len; $idx++) {
          $buffer[$offset++] = $contents[$idx];
        }

        $buffer[$offset++] = CharCodes::Newline;
        $buffer[$offset++] = CharCodes::e;
        $buffer[$offset++] = CharCodes::n;
        $buffer[$offset++] = CharCodes::d;
        $buffer[$offset++] = CharCodes::s;
        $buffer[$offset++] = CharCodes::t;
        $buffer[$offset++] = CharCodes::r;
        $buffer[$offset++] = CharCodes::e;
        $buffer[$offset++] = CharCodes::a;
        $buffer[$offset++] = CharCodes::m;

        return $offset - $initialOffset;
    }


}