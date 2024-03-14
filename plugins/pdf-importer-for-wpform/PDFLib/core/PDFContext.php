<?php


namespace rnpdfimporter\PDFLib\core;


use Exception;
use rnpdfimporter\core\Utils\ArrayUtils;
use rnpdfimporter\PDFLib\core\document\PDFHeader;
use rnpdfimporter\PDFLib\core\integration\ArrayIntegration;
use rnpdfimporter\PDFLib\core\integration\Map;
use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\objects\PDFArray;
use rnpdfimporter\PDFLib\core\objects\PDFDict;
use rnpdfimporter\PDFLib\core\objects\PDFName;
use rnpdfimporter\PDFLib\core\objects\PDFNull;
use rnpdfimporter\PDFLib\core\objects\PDFNumber;
use rnpdfimporter\PDFLib\core\objects\PDFObject;
use rnpdfimporter\PDFLib\core\objects\PDFRawStream;
use rnpdfimporter\PDFLib\core\objects\PDFRef;
use rnpdfimporter\PDFLib\core\operators\PDFOperator;
use rnpdfimporter\PDFLib\core\operators\PDFOperatorNames;
use rnpdfimporter\PDFLib\core\structures\PDFContentStream;
use rnpdfimporter\PDFLib\utils\arrays;
use stdClass;

class PDFContext
{
    public $largestObjectNumber;
    /** @var PDFHeader */
    public $header;
    /** @var $trailerInfo */
    public $trailerInfo;
    /** @var Map */
    public $indirectObjects;
    /** @var PDFRef */
    private $pushGraphicsStateContentStreamRef;
    /** @var PDFRef */
    private $popGraphicsStateContentStreamRef;

    public static function create(){
        return new PDFContext();
    }

    public function __construct()
    {
        $this->largestObjectNumber = 0;
        $this->header = PDFHeader::forVersion(1, 7);
        $this->trailerInfo = new trailerInfoClass();
        $this->indirectObjects = new Map();

    }

    /**
     * @param $ref PDFRef
     * @param $object PDFObject
     */
    public function assign($ref, $object)
    {
        $this->indirectObjects->set($ref, $object);
        if ($ref->objectNumber > $this->largestObjectNumber)
        {
            $this->largestObjectNumber = $ref->objectNumber;
        }

    }


    public function nextRef()
    {
        $this->largestObjectNumber += 1;
        return PDFRef::of($this->largestObjectNumber);
    }

    public function register($object)
    {
        $ref = $this->nextRef();
        $this->assign($ref, $object);
        return $ref;
    }

    public function delete($ref)
    {
        return $this->indirectObjects->delete($ref);
    }

    public function lookupMaybe($ref, ...$types)
    {
        $preservePDFNull = \in_array((PDFNull::class), $types);
        $result = $ref instanceof PDFRef ? $this->indirectObjects->get($ref) : $ref;
        if (!$result || ($result == PDFNull::$Instance && !$preservePDFNull))
            return null;

        for ($idx = 0, $len = count($types); $idx < $len; $idx++)
        {
            $type = $types[$idx];
            if ($type === PDFNull::class)
            {
                if ($result === PDFNull::$Instance) return $result;
            } else
            {
                if (\get_class($result) == $type) return $result;
            }
        }
        throw new Exception('Uexpected object type');

    }

    public function lookup($ref, ...$types)
    {
        $result = $ref instanceof PDFRef ? $this->indirectObjects->get($ref) : $ref;

        if (count($types) === 0) return $result;

        for ($idx = 0, $len = count($types); $idx < $len; $idx++)
        {
            $type = $types[$idx];
            if ($type === PDFNull::class)
            {
                if ($result === PDFNull::$Instance) return $result;
            } else
            {
                if (\get_class($result) == $type) return $result;
            }
        }

        throw new Exception('Unexpected object');
    }

    /**
     * @return ReferenceArray
     */
    public function enumerateIndirectObjects()
    {
        $array = ArrayIntegration::From($this->indirectObjects->entries());
        usort($array, function ($a, $b) {
            return $a->objectNumber - $b->objectNumber;
        });

        return ReferenceArray::createFromArray($array);
    }

    public function obj($literal)
    {
        if ($literal instanceof PDFObject)
        {
            return $literal;
        }

        if ($literal == null)
        {
            return PDFNull::$Instance;
        }

        if (\is_string($literal))
        {
            return PDFName::of($literal);
        }

        if (\is_numeric($literal))
        {
            return PDFNumber::of($literal);
        }

        if (\is_array($literal))
        {
            $array = PDFArray::withContext($this);
            for ($idx = 0, $len = count($literal); $idx < $len; $idx++)
            {
                $array->push($this->obj($literal[$idx]));
            }
            return $array;
        }

        if($literal instanceof stdClass)
        {
            $literal=(array)$literal;
        }

        $dict = PDFDict::withContext($this);
        $keys = array_keys($literal);
        for ($idx = 0, $len = count($keys); $idx < $len; $idx++)
        {
            $key = $keys[$idx];
            $value = $literal[$key];
            if ($value !== null) $dict->set(PDFName::of($key), $this->obj($value));
        }
        return $dict;


    }

    public function stream($contents, $dic)
    {
        return PDFRawStream::of($this->obj($dic), arrays::typedArrayFor($contents));
    }

    public function flateStream($contents, $dict)
    {
        $newDict = $dict;
        $newDict['Filter'] = 'FlateDecode';
        return $this->stream(\gzdeflate(implode(arrays::typedArrayFor($contents))), $newDict);
    }

    public function contentStream($operators, $dict)
    {
        return PDFContentStream::of($this->obj($dict), $operators);
    }

    /**
     * @param $operators PDFOperator
     * @param $dict
     */
    public function formXObject($operators, $dict)
    {
        $array = \array_merge($dict, array(
            "BBox" => $this->obj([0, 0, 0, 0]),
            "Matrix" => $this->obj([1, 0, 0, 1, 0, 0]),
            "Type" => '/XObject',
            "Subtype" => '/Form',
        ));
        return $this->contentStream($operators, new ReferenceArray($array));
    }

    public function getPushGraphicsStateContentStream()
    {
        if ($this->pushGraphicsStateContentStreamRef)
        {
            return $this->pushGraphicsStateContentStreamRef;
        }
        $dict = $this->obj(new stdClass());
        $op = PDFOperator::of(PDFOperatorNames::$PushGraphicsState);
        $stream = PDFContentStream::of($dict, [$op]);
        $this->pushGraphicsStateContentStreamRef = $this->register($stream);
        return $this->pushGraphicsStateContentStreamRef;
    }

    public function getPopGraphicsStateContentStream()
    {
        if ($this->popGraphicsStateContentStreamRef)
        {
            return $this->popGraphicsStateContentStreamRef;
        }
        $dict = $this->obj(new stdClass());
        $op = PDFOperator::of(PDFOperatorNames::$PopGraphicsState);
        $stream = PDFContentStream::of($dict, [$op]);
        $this->popGraphicsStateContentStreamRef = $this->register($stream);
        return $this->popGraphicsStateContentStreamRef;
    }


}


class trailerInfoClass
{
    /** @var PDFObject */
    public $Root;
    /** @var PDFObject */
    public $Encrypt;
    /** @var PDFObject */
    public $Info;
    /** @var PDFObject */
    public $ID;

    /**
     * trailerInfoClass constructor.
     */
    public function __construct()
    {
        $this->Root = null;
        $this->Encrypt = null;
        $this->Info = null;
        $this->ID = null;
    }


}