<?php


namespace rnpdfimporter\PDFLib\core\parser;


use Exception;
use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\objects\PDFArray;
use rnpdfimporter\PDFLib\core\objects\PDFDict;
use rnpdfimporter\PDFLib\core\objects\PDFName;
use rnpdfimporter\PDFLib\core\objects\PDFNumber;
use rnpdfimporter\PDFLib\core\objects\PDFRef;
use rnpdfimporter\PDFLib\core\PDFContext;
use rnpdfimporter\PDFLib\core\trailerInfoClass;

class PDFXRefStreamParser
{
    public $alreadyParsed;
    /** @var PDFDict */
    public $dict;
    /** @var PDFContext */
    public $context;
    /** @var ByteStream */
    public $bytes;
    public $subsections;
    private $byteWidths;

    public static function forStream($rawStream)
    {
        return new PDFXRefStreamParser($rawStream);
    }

    public function __construct($rawStream)
    {
        $this->alreadyParsed = false;
        $this->dict = $rawStream->dict;
        $this->bytes = ByteStream::fromPDFRawStream($rawStream);
        $this->context = $this->dict->context;
        $Size = $this->dict->lookup(PDFName::of('size'), PDFNumber::class);

        $Index = $this->dict->lookup(PDFName::of('Index'));
        if ($Index instanceof PDFArray)
        {
            $this->subsections = new ReferenceArray();
            for ($idx = 0, $len = $Index->size(); $idx < $len; $idx += 2)
            {
                $firstObjectNumber = $Index->lookup($idx + 0, PDFNumber::class)->asNumber();
                $length = $Index->lookup($idx + 1, PDFNumber::class)->asNumber();
                $this->subsections->push(array("firstObjectNumber" => $firstObjectNumber, "length" => $length));
            }
        } else
        {
            $this->subsections = [array("firstObjectNumber" => 0, "length" => $Size->asNumber())];
        }

        $W = $this->dict->lookup(PDFName::of('W'), PDFArray::class);
        $this->byteWidths = [-1, -1, -1];
        for ($idx = 0, $len = $W->size(); $idx < $len; $idx++)
        {
            $this->byteWidths[$idx] = $W->lookup($idx, PDFNumber::class)->asNumber();
        }
    }

    public function parseIntoContext()
    {
        if ($this->alreadyParsed)
        {
            throw new Exception('Repeater error');
        }
        $this->alreadyParsed = true;

        $this->context->trailerInfo = new trailerInfoClass();
        $this->context->trailerInfo->Root = $this->dict->get(PDFName::of('Root'));
        $this->context->trailerInfo->Encrypt = $this->dict->get(PDFName::of('Encrypt'));
        $this->context->trailerInfo->Info = $this->dict->get(PDFName::of('Info'));
        $this->context->trailerInfo->ID = $this->dict->get(PDFName::of('ID'));


        $entries = $this->parseEntries();

        // for (let idx = 0, len = entries.length; idx < len; idx++) {
        // const entry = entries[idx];
        // if (entry.deleted) this.context.delete(entry.ref);
        // }

        return $entries;
    }


    private function parseEntries()
    {
        $entries = new ReferenceArray();
        $typeFieldWidth = $this->byteWidths[0];
        $offsetFieldWidth = $this->byteWidths[1];
        $genFieldWidth = $this->byteWidths[2];


        for (
            $subsectionIdx = 0, $subsectionLen = $this->subsections->length();
            $subsectionIdx < $subsectionLen;
            $subsectionIdx++
        )
        {
            $firstObjectNumber = $this->subsections[$subsectionIdx]["firstObjectNumber"];
            $length = $this->subsections[$subsectionIdx]["length"];


            for ($objIdx = 0; $objIdx < $length; $objIdx++)
            {
                $type = 0;
                for ($idx = 0, $len = $typeFieldWidth; $idx < $len; $idx++)
                {
                    $type = ($type << 8) | $this->bytes->next();
                }

                $offset = 0;
                for ($idx = 0, $len = $offsetFieldWidth; $idx < $len; $idx++)
                {
                    $offset = ($offset << 8) | $this->bytes->next();
                }

                $generationNumber = 0;
                for ($idx = 0, $len = $genFieldWidth; $idx < $len; $idx++)
                {
                    $generationNumber = ($generationNumber << 8) | $this->bytes->next();
                }

                // When the `type` field is absent, it defaults to 1
                if ($typeFieldWidth === 0) $type = 1;

                $objectNumber = $firstObjectNumber + $objIdx;
                $entry = array(
                    'ref' => PDFRef::of($objectNumber, $generationNumber),
                    'offset' => $offset,
                    'deleted' => $type === 0,
                    'inObjectStream' => $type === 2
                );

                $entries->push($entry);
            }
        }

        return $entries;
    }

}