<?php


namespace rnpdfimporter\PDFLib\core\parser;


use Exception;
use rnpdfimporter\PDFLib\core\document\PDFCrossRefSection;
use rnpdfimporter\PDFLib\core\document\PDFHeader;
use rnpdfimporter\PDFLib\core\document\PDFTrailer;
use rnpdfimporter\PDFLib\core\integration\ObjectIntegration;
use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\objects\PDFDict;
use rnpdfimporter\PDFLib\core\objects\PDFInvalidObject;
use rnpdfimporter\PDFLib\core\objects\PDFName;
use rnpdfimporter\PDFLib\core\objects\PDFRawStream;
use rnpdfimporter\PDFLib\core\objects\PDFRef;
use rnpdfimporter\PDFLib\core\PDFContext;
use rnpdfimporter\PDFLib\core\syntax\CharCodes;
use rnpdfimporter\PDFLib\core\syntax\Keywords;
use rnpdfimporter\PDFLib\core\syntax\Numeric;
use rnpdfimporter\PDFLib\core\trailerInfoClass;
use rnpdfimporter\PDFLib\utils\waitForTick;

class PDFParser extends PDFObjectParser
{

    private $shouldWaitForTick;
    private $alreadyParsed=false;
    private $parsedObjects=0;
    /**
     * @param $bytes ReferenceArray
     * @param int $objectsPerTick
     * @param false $throwOnInvalidObject
     * @param false $capNumbers
     */
    public static function forBytesWithOptions($bytes, $objectsPerTick = 100, $throwOnInvalidObject = false, $capNumbers = false)
    {
        return new PDFParser($bytes, $objectsPerTick, $throwOnInvalidObject, $capNumbers);
    }

    public function __construct($pdfBytes, $objectsPerTick, $throwOnInvalidObject, $capNumbers)
    {
        parent::__construct(ByteStream::of($pdfBytes), PDFContext::create(), $capNumbers);

        $this->objectsPerTick = $objectsPerTick;
        $this->throwOnInvalidObject = $throwOnInvalidObject;
        $me =$this;
        $this->shouldWaitForTick = function ()use(&$me) {
            return false;
        };
    }

    public function parseDocument()
    {
        if ($this->alreadyParsed)
        {
            throw new Exception('Reparse error');
        }
        $this->alreadyParsed = true;

        $this->context->header = $this->parseHeader();

        $prevOffset = null;
        while (!$this->bytes->done())
        {
            $this->parseDocumentSection();
            $offset = $this->bytes->offset();
            if ($offset === $prevOffset)
            {
                throw new Exception('Stalled parse error');
            }
            $prevOffset = $offset;
        }

        $this->maybeRecoverRoot();

        if ($this->context->lookup(PDFRef::of(0)))
        {

            $this->context->delete(PDFRef::of(0));
        }

        return $this->context;
    }

    private function maybeRecoverRoot()
    {
        $isValidCatalog = function (&$obj) {


            $obj instanceof PDFDict &&
            $obj->lookup(PDFName::of('Type')) === PDFName::of('Catalog');
        };

        $catalog = $this->context->lookup($this->context->trailerInfo->Root);

        if (!$isValidCatalog($catalog))
        {
            $indirectObjects = $this->context->enumerateIndirectObjects();
            for ($idx = 0, $len = $indirectObjects->length; $idx < $len; $idx++)
            {
                $obj = $indirectObjects[$idx];
                $ref = $indirectObjects[0];
                $object = $indirectObjects[1];

                if ($isValidCatalog($object))
                {
                    $this->context->trailerInfo->Root = $ref;
                }
            }
        }
    }

    public function parseHeader()
    {
        while (!$this->bytes->done())
        {
            if ($this->matchKeyword(Keywords::$header))
            {
                $major = $this->parseRawInt();
                $this->bytes->assertNext(CharCodes::Period);
                $minor = $this->parseRawInt();
                $header = PDFHeader::forVersion($major, $minor);
                $this->skipBinaryHeaderComment();
                return $header;
            }
            $this->bytes->next();
        }

        throw new Exception('Missing pdf header');
    }


    private function parseIndirectObjectHeader()
    {
        $this->skipWhitespaceAndComments();
        $objectNumber = $this->parseRawInt();

        $this->skipWhitespaceAndComments();
        $generationNumber = $this->parseRawInt();

        $this->skipWhitespaceAndComments();
        if (!$this->matchKeyword(Keywords::$obj))
        {
            throw new Exception('Missing keywords');
        }

        return PDFRef::of($objectNumber, $generationNumber);
    }


    private function matchIndirectObjectHeader()
    {
        $initialOffset = $this->bytes->offset();
        try
        {
            $this->parseIndirectObjectHeader();
            return true;
        } catch (Exception $e)
        {
            $this->bytes->moveTo($initialOffset);
            return false;
        }
    }

    private function parseIndirectObject()
    {
        $ref = $this->parseIndirectObjectHeader();

        $this->skipWhitespaceAndComments();
        $object = $this->parseObject();

        $this->skipWhitespaceAndComments();
        // if (!this.matchKeyword(Keywords.endobj)) {
        // throw new MissingKeywordError(this.bytes.position(), Keywords.endobj);
        // }

        // TODO: Log a warning if this fails...
        $this->matchKeyword(Keywords::$endobj);

        if (
            $object instanceof PDFRawStream &&
            $object->dict->lookup(PDFName::of('Type')) === PDFName::of('ObjStm')
        )
        {
            PDFObjectStreamParser::forStream(
                $object,
                $this->shouldWaitForTick
            )->parseIntoContext();
        } else if (
            $object instanceof PDFRawStream &&
            $object->dict->lookup(PDFName::of('Type')) === PDFName::of('XRef')
        )
        {
            PDFXRefStreamParser::forStream($object)->parseIntoContext();
        } else
        {
            $this->context->assign($ref, $object);
        }

        return $ref;
    }

    public function tryToParseInvalidIndirectObject()
    {
        $startPos = $this->bytes->position();


        $ref = $this->parseIndirectObjectHeader();


        $this->skipWhitespaceAndComments();
        $start = $this->bytes->offset();

        $failed = true;
        while (!$this->bytes->done())
        {
            if ($this->matchKeyword(Keywords::$endobj))
            {
                $failed = false;
            }
            if (!$failed) break;
            $this->bytes->next();
        }

        if ($failed) throw new Exception('Invalid object');

        $end = $this->bytes->offset() - count(Keywords::$endobj);

        $object = PDFInvalidObject::of($this->bytes->slice($start, $end));
        $this->context->assign($ref, $object);

        return $ref;
    }

    private function parseIndirectObjects()
    {
        $this->skipWhitespaceAndComments();

        while (!$this->bytes->done() && Numeric::$IsDigit[$this->bytes->peek()])
        {
            $initialOffset = $this->bytes->offset();

            try
            {
                $this->parseIndirectObject();
            } catch (Exception $e)
            {
                // TODO: Add tracing/logging mechanism to track when this happens!
                $this->bytes->moveTo($initialOffset);
                $this->tryToParseInvalidIndirectObject();
            }
            $this->skipWhitespaceAndComments();

// TODO: Can this be done only when needed, to avoid harming performance?
            $this->skipJibberish();
            $shouldWaitForTick=$this->shouldWaitForTick;
            if ($shouldWaitForTick()) waitForTick::waitForTick();
        }
    }


    private function maybeParseCrossRefSection()
    {
        $this->skipWhitespaceAndComments();
        if (!$this->matchKeyword(Keywords::$xref)) return null;
        $this->skipWhitespaceAndComments();

        $objectNumber = -1;
        $xref = PDFCrossRefSection::createEmpty();


        while (!$this->bytes->done() && Numeric::$IsDigit[$this->bytes->peek()])
        {
            $firstInt = $this->parseRawInt();
            $this->skipWhitespaceAndComments();

            $secondInt = $this->parseRawInt();
            $this->skipWhitespaceAndComments();

            $byte = $this->bytes->peek();
            if ($byte === CharCodes::n || $byte === CharCodes::f)
            {
                $ref = PDFRef::of($objectNumber, $secondInt);
                if ($this->bytes->next() === CharCodes::n)
                {
                    $xref->addEntry($ref, $firstInt);
                } else
                {
                    // this.context.delete(ref);
                    $xref->addDeletedEntry($ref, $firstInt);
                }
                $objectNumber += 1;
            } else
            {
                $objectNumber = $firstInt;
            }
            $this->skipWhitespaceAndComments();
        }

        return $xref;
    }


    private function maybeParseTrailerDict()
    {
        $this->skipWhitespaceAndComments();
        if (!$this->matchKeyword(Keywords::$trailer)) return;
        $this->skipWhitespaceAndComments();

        $dict = $this->parseDict();

        $context = $this->context;
        $context->trailerInfo = new trailerInfoClass();
        $context->trailerInfo->Root = ObjectIntegration::FirstNonEmpty($dict->get(PDFName::of('Root')), $context->trailerInfo->Root);
        $context->trailerInfo->Info = ObjectIntegration::FirstNonEmpty($dict->get(PDFName::of('Info')), $context->trailerInfo->Info);
        $context->trailerInfo->ID = ObjectIntegration::FirstNonEmpty($dict->get(PDFName::of('ID')), $context->trailerInfo->ID);
    }

    private function maybeParseTrailer()
    {
        $this->skipWhitespaceAndComments();
        if (!$this->matchKeyword(Keywords::$startxref)) return null;
        $this->skipWhitespaceAndComments();

        $offset = $this->parseRawInt();

        $this->skipWhitespace();
        $this->matchKeyword(Keywords::$eof);
        $this->skipWhitespaceAndComments();
        $this->matchKeyword(Keywords::$eof);
        $this->skipWhitespaceAndComments();

        return PDFTrailer::forLastCrossRefSectionOffset($offset);
    }

    public function parseDocumentSection()
    {
        $this->parseIndirectObjects();
        $this->maybeParseCrossRefSection();
        $this->maybeParseTrailerDict();
        $this->maybeParseTrailer();
        $this->skipJibberish();
    }

    private function skipJibberish()
    {
        $this->skipWhitespaceAndComments();
        while (!$this->bytes->done())
        {
            $initialOffset = $this->bytes->offset();
            $byte = $this->bytes->peek();
            $isAlphaNumeric = $byte >= CharCodes::Space && $byte <= CharCodes::Tilde;
            if ($isAlphaNumeric)
            {
                if (
                    $this->matchKeyword(Keywords::$xref) ||
                    $this->matchKeyword(Keywords::$trailer) ||
                    $this->matchKeyword(Keywords::$startxref) ||
                    $this->matchIndirectObjectHeader()
                )
                {
                    $this->bytes->moveTo($initialOffset);
                    break;
                }
            }
            $this->bytes->next();
        }
    }


    private function skipBinaryHeaderComment()
    {
        $this->skipWhitespaceAndComments();
        try
        {
            $initialOffset = $this->bytes->offset();
            $this->parseIndirectObjectHeader();
            $this->bytes->moveTo($initialOffset);
        } catch (Exception $e)
        {
            $this->bytes->next();
            $this->skipWhitespaceAndComments();
        }
    }
}