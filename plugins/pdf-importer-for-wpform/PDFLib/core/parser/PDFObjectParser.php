<?php


namespace rnpdfimporter\PDFLib\core\parser;


use Error;
use Exception;
use rnpdfimporter\PDFLib\core\integration\Map;
use rnpdfimporter\PDFLib\core\objects\PDFArray;
use rnpdfimporter\PDFLib\core\objects\PDFBool;
use rnpdfimporter\PDFLib\core\objects\PDFDict;
use rnpdfimporter\PDFLib\core\objects\PDFHexString;
use rnpdfimporter\PDFLib\core\objects\PDFName;
use rnpdfimporter\PDFLib\core\objects\PDFNull;
use rnpdfimporter\PDFLib\core\objects\PDFNumber;
use rnpdfimporter\PDFLib\core\objects\PDFRawStream;
use rnpdfimporter\PDFLib\core\objects\PDFRef;
use rnpdfimporter\PDFLib\core\objects\PDFString;
use rnpdfimporter\PDFLib\core\PDFContext;
use rnpdfimporter\PDFLib\core\structures\PDFCatalog;
use rnpdfimporter\PDFLib\core\structures\PDFPageLeaf;
use rnpdfimporter\PDFLib\core\structures\PDFPageTree;
use rnpdfimporter\PDFLib\core\syntax\CharCodes;
use rnpdfimporter\PDFLib\core\syntax\Delimiters;
use rnpdfimporter\PDFLib\core\syntax\Keywords;
use rnpdfimporter\PDFLib\core\syntax\Numeric;
use rnpdfimporter\PDFLib\core\syntax\WhiteSpace;
use rnpdfimporter\PDFLib\utils\strings;

class PDFObjectParser extends BaseParser
{
    /** @var PDFContext */
    public $context;

    public static function forBytes($bytes, $context, $capNumbers)
    {
        return new PDFObjectParser(ByteStream::of($bytes), $context, $capNumbers);
    }

    public static function forByteStream($byteStream, $context, $capNumbers)
    {
        return new PDFObjectParser($byteStream, $context, $capNumbers);
    }

    public function __construct($bytes, $context, $capNumbers = false)
    {
        parent::__construct($bytes, $capNumbers);
        $this->context = $context;
    }

    public function parseObject()
    {
        $this->skipWhitespaceAndComments();

        if ($this->matchKeyword(Keywords::$true)) return PDFBool::$True;
        if ($this->matchKeyword(Keywords::$false)) return PDFBool::$False;
        if ($this->matchKeyword(Keywords::$null)) return PDFNull::$Instance;

        $byte = $this->bytes->peek();

        if (
            $byte === CharCodes::LessThan &&
            $this->bytes->peekAhead(1) === CharCodes::LessThan
        )
        {
            return $this->parseDictOrStream();
        }
        if ($byte === CharCodes::LessThan) return $this->parseHexString();
        if ($byte === CharCodes::LeftParen) return $this->parseString();
        if ($byte === CharCodes::ForwardSlash) return $this->parseName();
        if ($byte === CharCodes::LeftSquareBracket) return $this->parseArray();
        if (Numeric::$IsNumeric[$byte]) return $this->parseNumberOrRef();

        throw new Error('Object parse error');
    }

    protected function parseNumberOrRef()
    {
        $firstNum = $this->parseRawNumber();
        $this->skipWhitespaceAndComments();

        $lookaheadStart = $this->bytes->offset();
        if (Numeric::$IsDigit[$this->bytes->peek()])
        {
            $secondNum = $this->parseRawNumber();
            $this->skipWhitespaceAndComments();
            if ($this->bytes->peek() === CharCodes::R)
            {
                $this->bytes->assertNext(CharCodes::R);
                return PDFRef::of($firstNum, $secondNum);
            }
        }

        $this->bytes->moveTo($lookaheadStart);
        return PDFNumber::of($firstNum);
    }


    protected function parseHexString()
    {
        $value = '';

        $this->bytes->assertNext(CharCodes::LessThan);
        while (!$this->bytes->done() && $this->bytes->peek() !== CharCodes::GreaterThan)
        {
            $value .= strings::charFromCode($this->bytes->next());
        }
        $this->bytes->assertNext(CharCodes::GreaterThan);

        return PDFHexString::of($value);
    }

    public function parseString()
    {
        $nestingLvl = 0;
        $isEscaped = false;
        $value = '';

        while (!$this->bytes->done())
        {
            $byte = $this->bytes->next();
            $value .= strings::charFromCode($byte);

            // Check for unescaped parenthesis
            if (!$isEscaped)
            {
                if ($byte === CharCodes::LeftParen) $nestingLvl += 1;
                if ($byte === CharCodes::RightParen) $nestingLvl -= 1;
            }

// Track whether current character is being escaped or not
            if ($byte === CharCodes::BackSlash)
            {
                $isEscaped = !$isEscaped;
            } else if ($isEscaped)
            {
                $isEscaped = false;
            }

// Once (if) the unescaped parenthesis balance out, return their contents
            if ($nestingLvl === 0)
            {
                // Remove the outer parens so they aren't part of the contents
                return PDFString::of(\substr($value, 1, \strlen($value) - 1));
            }
        }

        throw new Exception('Unbalanced parenthesis');
    }

    public function parseName()
    {
        $this->bytes->assertNext(CharCodes::ForwardSlash);

        $name = '';
        while (!$this->bytes->done())
        {
            $byte = $this->bytes->peek();

            if (Whitespace::$IsWhiteSpace[$byte] || Delimiters::$IsDelimiters[$byte]) break;
            $name .= strings::charFromCode($byte);
            $this->bytes->next();
        }

        return PDFName::of($name);
    }


    protected function parseArray()
    {
        $this->bytes->assertNext(CharCodes::LeftSquareBracket);
        $this->skipWhitespaceAndComments();

        $pdfArray = PDFArray::withContext($this->context);
        while ($this->bytes->peek() !== CharCodes::RightSquareBracket)
        {
            $element = $this->parseObject();
            $pdfArray->push($element);
            $this->skipWhitespaceAndComments();
        }
        $this->bytes->assertNext(CharCodes::RightSquareBracket);
        return $pdfArray;
    }


    protected function parseDict()
    {
        $this->bytes->assertNext(CharCodes::LessThan);
        $this->bytes->assertNext(CharCodes::LessThan);
        $this->skipWhitespaceAndComments();

        $dict = new Map();

        while (
            !$this->bytes->done() &&
            $this->bytes->peek() !== CharCodes::GreaterThan &&
            $this->bytes->peekAhead(1) !== CharCodes::GreaterThan
        )
        {
            $key = $this->parseName();
            $value = $this->parseObject();
            $dict->set($key, $value);
            $this->skipWhitespaceAndComments();
        }

        $this->skipWhitespaceAndComments();
        $this->bytes->assertNext(CharCodes::GreaterThan);
        $this->bytes->assertNext(CharCodes::GreaterThan);

        $Type = $dict->get(PDFName::of('Type'));

        if ($Type === PDFName::of('Catalog'))
        {
            return PDFCatalog::fromMapWithContext($dict, $this->context);
        } else if ($Type === PDFName::of('Pages'))
        {
            return PDFPageTree::fromMapWithContext($dict, $this->context);
        } else if ($Type === PDFName::of('Page'))
        {
            return PDFPageLeaf::fromMapWithContext($dict, $this->context);
        } else
        {
            return PDFDict::fromMapWithContext($dict, $this->context);
        }
    }

    protected function parseDictOrStream()
    {
        $startPos = $this->bytes->position();

        $dict = $this->parseDict();

        $this->skipWhitespaceAndComments();

        if (
            !$this->matchKeyword(Keywords::$streamEOF1) &&
            !$this->matchKeyword(Keywords::$streamEOF2) &&
            !$this->matchKeyword(Keywords::$streamEOF3) &&
            !$this->matchKeyword(Keywords::$streamEOF4) &&
            !$this->matchKeyword(Keywords::$stream)
        )
        {
            return $dict;
        }

        $start = $this->bytes->offset();
        $end = 0;

        $Length = $dict->get(PDFName::of('Length'));
        if ($Length instanceof PDFNumber)
        {
            $end = $start + $Length->asNumber();
            $this->bytes->moveTo($end);
            $this->skipWhitespaceAndComments();
            if (!$this->matchKeyword(Keywords::$endStream))
            {
                $this->bytes->moveTo($start);
                $end = $this->findEndOfStreamFallback($startPos);
            }
        } else
        {
            $end = $this->findEndOfStreamFallback($startPos);
        }

        $contents = $this->bytes->slice($start, $end);

        return PDFRawStream::of($dict, $contents);
    }

    public function findEndOfStreamFallback($startPos)
    {
        // Move to end of stream, while handling nested streams
        $nestingLvl = 1;
        $end = $this->bytes->offset();

        while (!$this->bytes->done())
        {
            $end = $this->bytes->offset();

            if ($this->matchKeyword(Keywords::$stream))
            {
                $nestingLvl += 1;
            } else if (
                $this->matchKeyword(Keywords::$EOF1endstream) ||
                $this->matchKeyword(Keywords::$EOF2endstream) ||
                $this->matchKeyword(Keywords::$EOF3endstream) ||
                $this->matchKeyword(Keywords::$endStream)
            )
            {
                $nestingLvl -= 1;
            } else
            {
                $this->bytes->next();
            }

            if ($nestingLvl === 0) break;
        }

        if ($nestingLvl !== 0) throw new Exception('PDF stream parsing');

        return $end;
    }
}