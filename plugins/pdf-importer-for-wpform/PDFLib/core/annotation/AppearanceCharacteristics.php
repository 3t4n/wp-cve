<?php


namespace rnpdfimporter\PDFLib\core\annotation;


use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\objects\PDFArray;
use rnpdfimporter\PDFLib\core\objects\PDFDict;
use rnpdfimporter\PDFLib\core\objects\PDFHexString;
use rnpdfimporter\PDFLib\core\objects\PDFName;
use rnpdfimporter\PDFLib\core\objects\PDFNumber;
use rnpdfimporter\PDFLib\core\objects\PDFString;

class AppearanceCharacteristics
{
    /** @var PDFDict */
    public $dict;

    public static function fromDict($dict)
    {
        return new AppearanceCharacteristics($dict);
    }

    public function __construct($dict)
    {
        $this->dict = $dict;
    }

    public function R()
    {
        $R = $this->dict->lookup(PDFName::of('R'));
        if ($R instanceof PDFNumber) return $R;
        return null;
    }

    public function BC()
    {
        $BC = $this->dict->lookup(PDFName::of('BC'));
        if ($BC instanceof PDFArray) return $BC;
        return null;
    }

    public function BG()
    {
        $BG = $this->dict->lookup(PDFName::of('BG'));
        if ($BG instanceof PDFArray) return $BG;
        return null;
    }

    public function CA()
    {
        $CA = $this->dict->lookup(PDFName::of('CA'));
        if ($CA instanceof PDFHexString || $CA instanceof PDFString) return $CA;
        return null;
    }

    public function RC()
    {
        $RC = $this->dict->lookup(PDFName::of('RC'));
        if ($RC instanceof PDFHexString || $RC instanceof PDFString) return $RC;
        return null;
    }

    public function AC()
    {
        $AC = $this->dict->lookup(PDFName::of('AC'));
        if ($AC instanceof PDFHexString || $AC instanceof PDFString) return $AC;
        return null;
    }

    public function getRotation()
    {
        $r = $this->R();
        if ($r == null)
            return null;
        return $r->asNumber();
    }

    public function getBorderColor()
    {
        $BC = $this->BC();

        if (!$BC) return null;

        $components = new ReferenceArray();
        $count = 0;
        if ($BC != null)
            $count = $BC->size();
        for ($idx = 0, $len = $count; $idx < $len; $idx++)
        {
            $component = $BC->get($idx);
            if ($component instanceof PDFNumber) $components->push($component->asNumber());
        }

        return $components;
    }

    public function getBackgroundColor()
    {
        $BG = $this->BG();

        if (!$BG) return null;

        $components = new ReferenceArray();
        $count = 0;
        if ($BG != -null)
            $count = $BG->size();
        for ($idx = 0, $len = $count; $idx < $len; $idx++)
        {
            $component = $BG->get($idx);
            if ($component instanceof PDFNumber) $components->push($component->asNumber());
        }

        return $components;
    }

    public function getCaptions()
    {
        $CA = $this->CA();
        $RC = $this->RC();
        $AC = $this->AC();

        return array(
            "normal" => $CA == null ? null : $CA->decodeText(),
            "rollover" => $RC == null ? null : $RC->decodeText(),
            "down" => $AC == null ? null : $AC->decodeText(),
        );
    }

    public function setRotation($rotation)
    {
        $R = $this->dict->context->obj($rotation);
        $this->dict->set(PDFName::of('R'), $R);
    }

    public function setBorderColor($color)
    {
        $BC = $this->dict->context->obj($color);
        $this->dict->set(PDFName::of('BC'), $BC);
    }

    public function setBackgroundColor($color)
    {
        $BG = $this->dict->context->obj($color);
        $this->dict->set(PDFName::of('BG'), $BG);
    }

    public function setCaptions($captions)
    {
        $CA = PDFHexString::fromText($captions['normal']);
        $this->dict->set(PDFName::of('CA'), $CA);

        if ($captions['rollover'])
        {
            $RC = PDFHexString::fromText($captions['rollover']);
            $this->dict->set(PDFName::of('RC'), $RC);
        } else
        {
            $this->dict->delete(PDFName::of('RC'));
        }

        if ($captions['down'])
        {
            $AC = PDFHexString::fromText($captions['down']);
            $this->dict->set(PDFName::of('AC'), $AC);
        } else
        {
            $this->dict->delete(PDFName::of('AC'));
        }
    }

}