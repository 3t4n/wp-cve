<?php


namespace rnpdfimporter\PDFLib\core\objects;


use Exception;
use rnpdfimporter\PDFLib\core\integration\ArrayIntegration;
use rnpdfimporter\PDFLib\core\integration\Map;
use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\integration\StringIntegration;
use rnpdfimporter\PDFLib\core\syntax\CharCodes;
use rnpdfimporter\PDFLib\core\syntax\Irregular;
use rnpdfimporter\PDFLib\utils\strings;

class PDFName extends PDFObject
{

    public static $FlateDecode;
    public static $Resources;
    public static $Font;
    public static $XObject;
    public static $ExtGState;
    public static $Contents;
    public static $Type;
    public static $Parent;
    public static $MediaBox;
    public static $Page;
    public static $Annots;
    public static $TrimBox;
    public static $ArtBox;
    public static $BleedBox;
    public static $CropBox;
    public static $Rotate;
    public static $Title;
    public static $Author;
    public static $Subject;
    public static $Creator;
    public static $Keywords;
    public static $Producer;
    public static $CreationDate;
    public static $ModDate;

    /** @var Map */
    public static $pool;
    public $encodedName;
    public static $Length;
    public static function decodeName($name)
    {

        $matches = array();
        while (preg_match('/#([\dABCDEF]{2})/', $name, $matches))
        {
            $name = str_replace($matches[0], strings::charFromHexCode($matches[1]), $name);
        }

        return $name;
    }

    public static function isRegularChar($charCode)
    {
        return $charCode >= CharCodes::ExclamationPoint &&
            $charCode <= CharCodes::Tilde &&
            !Irregular::$IsIrregular[$charCode];

    }

    public static function of($name)
    {
        $decodedValue = PDFName::decodeName($name);

        $instance = PDFName::$pool->get($decodedValue);
        if (!$instance)
        {
            $instance = new PDFName("{}", $decodedValue);
            PDFName::$pool->set($decodedValue, $instance);
        }

        return $instance;
    }

    public function __construct($enforcer, $name)
    {
        if ($enforcer != '{}')
            throw new Exception('Private constructor error');

        $encodedName = '/';
        for ($idx = 0, $len = \strlen($name); $idx < $len; $idx++)
        {
            $character = $name[$idx];
            $code = strings::charCode($character);
            $encodedName .= PDFName::isRegularChar($code) ? $character : '#' . strings::toHexString($code);
        }

        $this->encodedName = $encodedName;
    }

    public function asBytes()
    {
        $bytes = new ReferenceArray();

        $hex = '';
        $escaped = false;

        $pushByte = function ($byte) use (&$bytes, &$escaped) {
            if ($byte !== null) $bytes[] = $byte;
            $escaped = false;
        };

        for ($idx = 1, $len = \strlen($this->encodedName); $idx < $len; $idx++)
        {
            $char = $this->encodedName[$idx];
            $byte = strings::charCode($char);
            $nextChar = $this->encodedName[$idx + 1];
            if (!$escaped)
            {
                if ($byte === CharCodes::Hash) $escaped = true;
                else $pushByte($byte);
            } else
            {
                if (
                    ($byte >= CharCodes::Zero && $byte <= CharCodes::Nine) ||
                    ($byte >= CharCodes::a && $byte <= CharCodes::f) ||
                    ($byte >= CharCodes::A && $byte <= CharCodes::F)
                )
                {
                    $hex .= $char;
                    if (
                        \strlen($hex) === 2 ||
                        !(
                            ($nextChar >= '0' && $nextChar <= '9') ||
                            ($nextChar >= 'a' && $nextChar <= 'f') ||
                            ($nextChar >= 'A' && $nextChar <= 'F')
                        )
                    )
                    {
                        $pushByte(\intval($hex, 16));
                        $hex = '';
                    }
                } else
                {
                    $pushByte($byte);
                }
            }
        }

        return ReferenceArray::createFromArray($bytes);
    }

    public function decodeText()
    {
        $bytes = $this->asBytes();
        return StringIntegration::fromCharCode($bytes);
    }

    public function asString()
    {
        return $this->encodedName;
    }

    public function value()
    {
        return $this->encodedName;
    }

    public function _clone($context)
    {
        return $this;
    }

    public function __toString()
    {
        return $this->encodedName;
    }

    public function sizeInBytes()
    {
        return \strlen($this->encodedName);
    }

    public function copyBytesInto($burffer, $offset)
    {
        $offset.=strings::copyStringIntoBuffer($this->encodedName,$burffer,$offset);
        return \strlen($this.$this->encodedName);
    }


}

PDFName::$pool = new Map();

PDFName::$Length=PDFName::of('Length');
PDFName::$FlateDecode = PDFName::of('FlateDecode');
PDFName::$Resources = PDFName::of('Resources');
PDFName::$Font = PDFName::of('Font');
PDFName::$XObject = PDFName::of('XObject');
PDFName::$ExtGState = PDFName::of('ExtGState');
PDFName::$Contents = PDFName::of('Contents');
PDFName::$Type = PDFName::of('Type');
PDFName::$Parent = PDFName::of('Parent');
PDFName::$MediaBox = PDFName::of('MediaBox');
PDFName::$Page = PDFName::of('Page');
PDFName::$Annots = PDFName::of('Annots');
PDFName::$TrimBox = PDFName::of('TrimBox');
PDFName::$ArtBox = PDFName::of('ArtBox');
PDFName::$BleedBox = PDFName::of('BleedBox');
PDFName::$CropBox = PDFName::of('CropBox');
PDFName::$Rotate = PDFName::of('Rotate');
PDFName::$Title = PDFName::of('Title');
PDFName::$Author = PDFName::of('Author');
PDFName::$Subject = PDFName::of('Subject');
PDFName::$Creator = PDFName::of('Creator');
PDFName::$Keywords = PDFName::of('Keywords');
PDFName::$Producer = PDFName::of('Producer');
PDFName::$CreationDate = PDFName::of('CreationDate');
PDFName::$ModDate = PDFName::of('ModDate');
