<?php


namespace rnpdfimporter\PDFLib\core\objects;


use rnpdfimporter\PDFLib\core\integration\ArrayIntegration;
use rnpdfimporter\PDFLib\core\integration\Map;
use rnpdfimporter\PDFLib\core\PDFContext;

class PDFDict extends PDFObject
{
    /** @var PDFContext */
    public $context;
    /** @var Map */
    public $dic;
    public static $Instance;

    public function __construct($map, $context)
    {
        $this->context = $context;
        $this->dic = $map;
    }

    public static function withContext($context)
    {
        return new PDFDict(new Map(), $context);
    }

    public static function fromMapWithContext($map, $context)
    {
        return new PDFDict($map, $context);
    }

    public function keys()
    {
        return array_keys($this->dic->dictionary);
    }

    public function values()
    {
        return \array_values($this->dic->dictionary);
    }

    public function entries()
    {
        return ArrayIntegration::From($this->dic->entries());
    }

    public function set($key, $value)
    {
        $this->dic->set($key, $value);
    }

    public function get($key, $preservePDFNull=null)
    {
        $value = $this->dic->get($key);
        if ($value == PDFNull::$Instance && !$preservePDFNull) return null;
        return $value;
    }

    public function has($key)
    {
        $value = $this->dic->get($key);
        return $value !== null && $value !== PDFNull::$Instance;
    }

    public function lookupMaybe($key, ...$types)
    {

        $preservePDFNull = \in_array(PDFNull::class, $types);

        $value = $this->context->lookupMaybe(
            $this->get($key, $preservePDFNull),
            // @ts-ignore
            ...$types
        );

        if ($value === PDFNull::$Instance && !$preservePDFNull) return null;

        return $value;
    }

    public function lookup($key, ...$types)
    {
        $preservePDFNull = \in_array(PDFNull::class, $types);

        $value = $this->context->lookup(
            $this->get($key, $preservePDFNull),
            // @ts-ignore
            ...$types
        );

        if ($value === PDFNull::$Instance && !$preservePDFNull) return null;

        return $value;
    }


    public function delete($key)
    {
        return $this->dic->delete($key);
    }

    public function asMap() {
        return $this->dic->_clone();
    }

    public function _clone($context)
    {
        $clone = PDFDict::withContext($context || $this->context);
        $entries = $this->entries();
        for ($idx = 0, $len =count($entries); $idx < $len; $idx++) {
            $entry=$entries[$idx];
            $clone->set($entry[0],$entry[1]);
        }
        return $clone;
    }

    public function __toString()
    {
        $dictString = '<<\n';
        $entries = $this->entries();
        for ($idx = 0, $len = count($entries); $idx < $len; $idx++) {
            $entry = $entries[$idx];
            $dictString .= \strval($entry[0]) . ' ' .\strval($entry['value']). '\n';
        }
        $dictString .= '>>';
        return $dictString;
    }

    public function sizeInBytes()
    {
        $size = 5;
        $entries = $this->entries();
        for ($idx = 0, $len = count($entries); $idx < $len; $idx++) {
            $entry = $entries[$idx];
            $size += $entry[0]->sizeInBytes() + $entry[1]->sizeInBytes() + 2;
        }
        return $size;
    }

    public function copyBytesInto($buffer, $offset)
    {
        $initialOffset = $offset;

        $buffer[$offset++] = CharCodes::LessThan;
        $buffer[$offset++] = CharCodes::LessThan;
        $buffer[$offset++] = CharCodes::Newline;

        $entries = $this->entries();
        for ($idx = 0, $len = count($entries); $idx < $len; $idx++) {
          $entry = $entries[$idx];
          $offset += $entry[0]->copyBytesInto($buffer, $offset);
          $buffer[$offset++] = CharCodes::Space;
          $offset += $entry[1]->copyBytesInto($buffer, $offset);
          $buffer[$offset++] = CharCodes::Newline;
        }

        $buffer[$offset++] = CharCodes::GreaterThan;
        $buffer[$offset++] = CharCodes::GreaterThan;

        return $offset - $initialOffset;
    }


}

PDFDict::$Instance=new PDFDict(null,null);