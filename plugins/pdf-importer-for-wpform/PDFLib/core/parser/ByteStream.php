<?php


namespace rnpdfimporter\PDFLib\core\parser;


use Exception;
use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\objects\PDFRawStream;
use rnpdfimporter\PDFLib\core\streams\decode;
use rnpdfimporter\PDFLib\core\syntax\CharCodes;

class ByteStream
{
    /** @var ReferenceArray */
    public $bytes;
    public $length;

    public $idx;
    private $line;
    private $column;

    public function __construct($bytes)
    {
        $this->bytes=$bytes;
        $this->length=count($bytes);

        $this->idx=0;
        $this->line=0;
        $this->column=0;
    }

    /**
     * @param $rawStream PDFRawStream
     * @return ByteStream
     * @throws Exception
     */
    public  static function fromPDFRawStream($rawStream)
    {
        return ByteStream::of(decode::decodePDFRawStream($rawStream->dict,$rawStream->contents)->decode());

    }

    public static function of($bytes)
    {
        return new ByteStream($bytes);
    }

    public function moveTo($offset)
    {
        $this->idx=$offset;
    }

    public function next(){
        $byte=$this->bytes[$this->idx++];

        if($byte==CharCodes::Newline)
        {
            $this->line+=1;
            $this->column=0;
        }else{
            $this->column+=1;
        }

        return $byte;
    }

    public function assertNext($expected) {
        if ($this->peek() !== $expected) {
          throw new Exception('Invalid next byte assertion');
        }
        return $this->next();
    }

    public function  peek(){
        return $this->bytes[$this->idx];
    }

    public function peekAhead($steps) {
        return $this->bytes[$this->idx + $steps];
    }

    public function  peekAt($offset) {
        return $this->bytes[$offset];
    }

    public function done(){
        return $this->idx >= $this->length;
    }

    public function offset(){
        return $this->idx;
    }

    public function slice($start, $end) {

        return \array_slice((array)$this->bytes,$start,$end-$start);
    }

    public function position() {
        return (object)array( "line"=> $this->line, "column"=> $this->column, "offset"=> $this->idx );
    }
}