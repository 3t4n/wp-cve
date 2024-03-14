<?php


namespace rnpdfimporter\PDFLib\core\operators;


use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\objects\PDFObject;
use rnpdfimporter\PDFLib\core\syntax\CharCodes;
use rnpdfimporter\PDFLib\utils\strings;

class PDFOperator
{
    public $name;
    public $args;

    public function __construct($name, $args)
    {
        $this->name = $name;
        $this->args = $args;
    }

    public static function of($name, $args=null)
    {
        return new PDFOperator($name, $args);
    }

    public function _clone($context)
    {
        $args = ReferenceArray::withSize(count($this->args));
        for ($idx = 0, $len = count($args); $idx < $len; $idx++)
        {
            $arg = $this->args[$idx];
            $args[$idx] = $arg instanceof PDFObject ? $arg->_clone($context) : $arg;
        }
        return PDFOperator::of($this->name, $args);
    }

    public function __toString()
    {
        $value = '';
        for ($idx = 0, $len = count($this->args); $idx < $len; $idx++)
        {
            $value .= \strval($this->args[$idx]) . ' ';
        }
        $value .= $this->name;
        return $value;
    }


    public function sizeInBytes()
    {
        $size = 0;
        for ($idx = 0, $len = count($this->args); $idx < $len; $idx++)
        {
            $arg = $this->args[$idx];
            $size += ($arg instanceof PDFObject ? $arg->sizeInBytes() : count($arg)) + 1;
        }
        $size += count($this->name);
        return $size;
    }

    public function copyBytesInto($buffer, $offset)
    {
        $initialOffset = $offset;

        for ($idx = 0, $len = count($this->args); $idx < $len; $idx++)
        {
            $arg = $this->args[$idx];
            if ($arg instanceof PDFObject)
            {
                $offset += $arg->copyBytesInto($buffer, $offset);
            } else
            {
                $offset += strings::copyStringIntoBuffer($arg, $buffer, $offset);
            }
            $buffer[$offset++] = CharCodes::Space;
        }

        $offset += strings::copyStringIntoBuffer($this->name, $buffer, $offset);

        return $offset - $initialOffset;
    }


}