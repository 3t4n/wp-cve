<?php


namespace rnpdfimporter\PDFLib\core\parser;


use Exception;
use rnpdfimporter\PDFLib\core\syntax\CharCodes;
use rnpdfimporter\PDFLib\core\syntax\Numeric;
use rnpdfimporter\PDFLib\core\syntax\WhiteSpace;
use rnpdfimporter\PDFLib\utils\strings;

class BaseParser
{
    /** @var ByteStream */
    public $bytes;
    public $capNumbers;
    public function __construct($bytes,$capNumbers)
    {
        $this->bytes=$bytes;
        $this->capNumbers=$capNumbers;
    }

    protected function parseRawInt() {
        $value = '';

        while (!$this->bytes->done()) {
          $byte = $this->bytes->peek();
          if (!Numeric::$IsDigit[$byte]) break;
          $value .= strings::charFromCode($this->bytes->next());
        }

        $numberValue = \intval($value);

        if ($value===false||$value===null || \is_numeric($value)===false) {
            throw new Exception('Number parsing error');
        }

        return \floatval($numberValue);
    }

    protected function parseRawNumber() {
        $value = '';

        // Parse integer-part, the leading (+ | - | . | 0-9)
        while (!$this->bytes->done()) {
          $byte = $this->bytes->peek();
          if (!Numeric::$IsNumeric[$byte]) break;
          $value .= strings::charFromCode($this->bytes->next());
          if ($byte === CharCodes::Period) break;
        }

        // Parse decimal-part, the trailing (0-9)
        while (!$this->bytes->done()) {
            $byte = $this->bytes->peek();
            if (!Numeric::$IsDigit[$byte]) break;
            $value .= strings::charFromCode($this->bytes->next());
        }

        $numberValue = \floatval($value);

        if ($value===false||$value===null || \is_numeric($value)===false) {
            throw new Exception("Number parse error");
        }

        if ($numberValue > PHP_INT_MAX ) {
            if ($this->capNumbers) {
                $msg = `Parsed number that is too large for some PDF readers: ${value}, using Number.MAX_SAFE_INTEGER instead.`;
                throw new Exception($msg);
                //return Number.MAX_SAFE_INTEGER;
            } else {
                throw new Exception(`Parsed number that is too large for some PDF readers: ${value}, not capping.`);
            }
        }

        return $numberValue;
    }

    public function skipWhitespace() {
        while (!$this->bytes->done() && WhiteSpace::$IsWhiteSpace[$this->bytes->peek()]) {
            $this->bytes->next();
        }
    }

    protected function skipLine() {
        while (!$this->bytes->done()) {
            $byte = $this->bytes->peek();
            if ($byte === CharCodes::Newline || $byte === CharCodes::CarriageReturn) return;
            $this->bytes->next();
        }
    }

    protected function skipComment(){
        if ($this->bytes->peek() !== CharCodes::Percent) return false;
        while (!$this->bytes->done()) {
            $byte = $this->bytes->peek();
            if ($byte === CharCodes::Newline || $byte === CharCodes::CarriageReturn) return true;
            $this->bytes->next();
        }
        return true;
    }


    protected function skipWhitespaceAndComments(){
        $this->skipWhitespace();
        while ($this->skipComment()) $this->skipWhitespace();
      }

    protected function matchKeyword($keyword){
        $initialOffset = $this->bytes->offset();
        for ($idx = 0, $len = \count($keyword); $idx < $len; $idx++) {
          if ($this->bytes->done() || $this->bytes->next() !== $keyword[$idx]) {
            $this->bytes->moveTo($initialOffset);
            return false;
          }
        }
        return true;
    }

}