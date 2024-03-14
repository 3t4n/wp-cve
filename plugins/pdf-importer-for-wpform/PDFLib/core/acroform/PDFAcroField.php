<?php


namespace rnpdfimporter\PDFLib\core\acroform;


use rnpdfimporter\PDFLib\core\objects\PDFDict;
use rnpdfimporter\PDFLib\core\objects\PDFHexString;
use rnpdfimporter\PDFLib\core\objects\PDFName;
use rnpdfimporter\PDFLib\core\objects\PDFNumber;
use rnpdfimporter\PDFLib\core\objects\PDFRef;
use rnpdfimporter\PDFLib\core\objects\PDFString;
use rnpdfimporter\PDFLib\core\objects\PDFArray;

class PDFAcroField
{
    /** @var PDFDict */
    public $dict;
    /** @var PDFRef */
    public $ref;

    public function __construct($dict, $ref)
    {
        $this->dict = $dict;
        $this->ref = $ref;
    }


    /**
     * @return PDFString|PDFHexString
     */
    public function T()
    {

        return $this->dict->lookupMaybe(PDFName::of('T'), PDFString::class, PDFHexString::class);
    }

    public function Ff()
    {
        $numberOrRef = $this->getInheritableAttribute(PDFName::of('Ff'));
        return $this->dict->context->lookupMaybe($numberOrRef, PDFNumber::class);
    }

    public function V()
    {
        $valueOrRef = $this->getInheritableAttribute(PDFName::of('V'));
        return $this->dict->context->lookup($valueOrRef);
    }

    public function Kids()
    {
        return $this->dict->lookupMaybe(PDFName::of('Kids'), PDFArray::class);
    }

    public function DA()
    {
        $da = $this->dict->lookup(PDFName::of('DA'));
        if ($da instanceof PDFString || $da instanceof PDFHexString) return $da;
        return null;
    }

    public function setKids($kids)
    {
        $this->dict->set(PDFName::of('Kids'), $this->dict->context->obj($kids));
    }


    public function getParent()
    {
        // const parent = this.Parent();
        // if (!parent) return undefined;
        // return new PDFAcroField(parent);

        $parentRef = $this->dict->get(PDFName::of('Parent'));
        if ($parentRef instanceof PDFRef)
        {
            $parent = $this->dict->lookup(PDFName::of('Parent'), PDFDict::class);
            return new PDFAcroField($parent, $parentRef);
        }

        return null;
    }

    public function setParent($parent)
    {
        if (!$parent) $this->dict->delete(PDFName::of('Parent'));
        else $this->dict->set(PDFName::of('Parent'), $parent);
    }

    public function getFullyQualifiedName()
    {
        $parent = $this->getParent();
        if (!$parent) return $this->getPartialName();
        return $parent->getFullyQualifiedName() . '.' . $this->getPartialName();
    }

    public function getPartialName()
    {
        $t = $this->T();
        if ($t == null)
            return null;

        return $t->decodeText();
    }

    public function setPartialName($partialName)
    {
        if (!$partialName) $this->dict->delete(PDFName::of('T'));
        else $this->dict->set(PDFName::of('T'), PDFHexString::fromText($partialName));
    }


    public function setDefaultAppearance($appearance)
    {
        $this->dict->set(PDFName::of('DA'), PDFString::of($appearance));
    }

    public function getDefaultAppearance()
    {
        $da = $this->DA();
        if ($da == null)
            return '';

        $string = $da->asString();
        if ($string == null)
            return '';

        return $string;
    }

    public function getFlags()
    {
        $ff = $this->Ff();
        if ($ff == null)
            return 0;

        $number = $ff->asNumber();
        if ($number == null)
            return 0;

        return $number;
    }


    public function setFlags($flags)
    {
        $this->dict->set(PDFName::of('Ff'), PDFNumber::of($flags));
    }

    public function hasFlag($flag)
    {
        $flags = $this->getFlags();
        return ($flags & $flag) !== 0;
    }

    public function setFlag($flag)
    {
        $flags = $this->getFlags();
        $this->setFlags($flags | $flag);
    }

    public function clearFlag($flag)
    {
        $flags = $this->getFlags();
        $this->setFlags($flags & ~$flag);
    }

    public function setFlagTo($flag, $enable)
    {
        if ($enable) $this->setFlag($flag);
        else $this->clearFlag($flag);
    }

    public function getInheritableAttribute($name)
    {
        $attribute = null;
        $this->ascend(function ($node) use (&$attribute, &$name) {
            if (!$attribute) $attribute = $node->dict->get($name);
        });
        return $attribute;
    }

    public function ascend($visitor)
    {
        $visitor($this);
        $parent = $this->getParent();
        if ($parent) $parent->ascend($visitor);
    }

}