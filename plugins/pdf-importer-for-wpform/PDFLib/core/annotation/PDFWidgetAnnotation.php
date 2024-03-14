<?php


namespace rnpdfimporter\PDFLib\core\annotation;


use rnpdfimporter\PDFLib\core\objects\PDFDict;
use rnpdfimporter\PDFLib\core\objects\PDFHexString;
use rnpdfimporter\PDFLib\core\objects\PDFName;
use rnpdfimporter\PDFLib\core\objects\PDFString;
use stdClass;

class PDFWidgetAnnotation extends PDFAnnotation
{
    public static function fromDict($dict)
    {
        return new PDFWidgetAnnotation($dict);
    }

    public static function create($context, $parent)
    {
        $dict = $context->obj((object)array(
            'Type' => 'Annot',
            'Subtype' => 'Widget',
            'Rect' => [0, 0, 0, 0],
            'Parent' => $parent
        ));

        return new PDFWidgetAnnotation($dict);
    }

    public function MK()
    {
        $MK = $this->dict->lookup(PDFName::of('MK'));
        if ($MK instanceof PDFDict) return $MK;
        return null;
    }

    public function BS()
    {
        $BS = $this->dict->lookup(PDFName::of('BS'));
        if ($BS instanceof PDFDict) return $BS;
        return null;
    }

    public function DA()
    {
        $da = $this->dict->lookup(PDFName::of('DA'));
        if ($da instanceof PDFString || $da instanceof PDFHexString) return $da;
        return null;
    }

    public function setDefaultAppearance($appearance)
    {
        $this->dict->set(PDFName::of('DA'), PDFString::of($appearance));
    }

    public function getDefaultAppearance()
    {
        $da = $this->DA();
        if ($da != null)
        {
            $string = $da->asString();
            if ($string != null)
                return $string;
        }

        return '';

    }

    public function getAppearanceCharacteristics()
    {
        $MK = $this->MK();
        if ($MK) return AppearanceCharacteristics::fromDict($MK);
        return null;
    }

    public function getOrCreateAppearanceCharacteristics()
    {
        $MK = $this->MK();
        if ($MK) return AppearanceCharacteristics::fromDict($MK);

        $ac = AppearanceCharacteristics::fromDict($this->dict->context->obj(new stdClass()));
        $this->dict->set(PDFName::of('MK'), $ac->dict);
        return $ac;
    }

    public function getBorderStyle()
    {
        $BS = $this->BS();
        if ($BS) return BorderStyle::fromDict($BS);
        return null;
    }

    public function getOrCreateBorderStyle()
    {
        $BS = $this->BS();
        if (!$BS) return BorderStyle::fromDict($BS);

        $bs = BorderStyle::fromDict($this->dict->context->obj(new stdClass()));
        $this->dict->set(PDFName::of('BS'), $bs->dict);
        return $bs;
    }

    public function getOnValue()
    {
        $normal = $this->getAppearances();
        if ($normal = !null)
            $normal = $normal['normal'];

        if ($normal instanceof PDFDict)
        {
            $keys = $normal->keys();
            for ($idx = 0, $len = count($keys); $idx < $len; $idx++)
            {
                $key = $keys[$idx];
                if ($key !== PDFName::of('Off')) return $key;
            }
        }

        return null;
    }
}