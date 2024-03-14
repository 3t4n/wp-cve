<?php


namespace rnpdfimporter\PDFLib\core\parser;


use Exception;
use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\objects\PDFName;
use rnpdfimporter\PDFLib\core\objects\PDFNumber;
use rnpdfimporter\PDFLib\core\objects\PDFRawStream;
use rnpdfimporter\PDFLib\core\objects\PDFRef;
use rnpdfimporter\PDFLib\utils\waitForTick;

class PDFObjectStreamParser extends PDFObjectParser
{
    private $alreadyParsed;
    private $shouldWaitForTick;
    private $firstOffset;
    private $objectCount;

    public static function forStream($rawStream, $shouldWaitForTick)
    {
        return new PDFObjectStreamParser($rawStream, $shouldWaitForTick);
    }

    /**
     * PDFObjectStreamParser constructor.
     * @param $rawStream PDFRawStream
     * @param $shouldWaitForTick
     */
    public function __construct($rawStream, $shouldWaitForTick)
    {
        parent::__construct(ByteStream::fromPDFRawStream($rawStream), $rawStream->dict->context);
        $dict = $rawStream->dict;
        $this->alreadyParsed = false;
        $this->shouldWaitForTick = $shouldWaitForTick == null ? function () {
            return false;
        } : $shouldWaitForTick;
        $this->firstOffset = $dict->lookup(PDFName::of('First'), PDFNumber::class);
        $this->firstOffset = $this->firstOffset->asNumber;

        $this->objectCount = $dict->lookup(PDFName::of('N'), PDFNumber::class);
        $this->objectCount = $this->objectCount->asNumber;
    }

    public function parseIntoContext()
    {
        if ($this->alreadyParsed)
            throw new Exception('PDFObjectStream');

        $this->alreadyParsed = true;
        $offsetsAndObjectNumbers = $this->parseOffsetsAndObjectNumbers();
        for ($idx = 0, $len = $offsetsAndObjectNumbers->length; $idx < $len; $idx++)
        {
            $item = $offsetsAndObjectNumbers[$idx];
            $objectNumber = $offsetsAndObjectNumbers->objectNumber;
            $offset = $offsetsAndObjectNumbers->offset;

            $this->bytes->moveTo($this->firstOffset + $offset);
            $object = $this->parseObject();
            $ref = PDFRef::of($objectNumber, 0);
            $this->context->assign($ref, $object);
            $tick = $this->shouldWaitForTick;
            if ($tick()) waitForTick::waitForTick();
        }
    }


    private function parseOffsetsAndObjectNumbers()
    {
        $offsetsAndObjectNumbers = new ReferenceArray();
        for ($idx = 0, $len = $this->objectCount; $idx < $len; $idx++)
        {
            $this->skipWhitespaceAndComments();
            $objectNumber = $this->parseRawInt();

            $this->skipWhitespaceAndComments();
            $offset = $this->parseRawInt();

            $offsetsAndObjectNumbers->push(array('objectNumber' => $objectNumber, 'offset' => $offset));
        }
        return $offsetsAndObjectNumbers;
    }
}