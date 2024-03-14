<?php


namespace rnpdfimporter\PDFLib\core\annotation;


use rnpdfimporter\PDFLib\core\objects\PDFArray;
use rnpdfimporter\PDFLib\core\objects\PDFDict;
use rnpdfimporter\PDFLib\core\objects\PDFName;
use rnpdfimporter\PDFLib\core\objects\PDFNumber;
use rnpdfimporter\PDFLib\core\objects\PDFStream;
use stdClass;

class PDFAnnotation
{
    /** @var PDFDict */
    public $dict;

    public static function fromDict($dict)
    {
        return new PDFAnnotation($dict);
    }

    public function __construct($dict)
    {
        $this->dict = $dict;
    }

    /**
     * @return PDFArray
     */
    public function Rect()
    {
        return $this->dict->lookup(PDFName::of('Rect'), PDFArray::class);
    }

    public function AP()
    {
        return $this->dict->lookupMaybe(PDFName::of('AP'), PDFDict::class);
    }

    /**
     * @return PDFNumber
     * @throws \Exception
     */
    public function F()
    {
        $numberOfRef = $this->dict->lookup(PDFName::of('F'));
        return $this->dict->context->lookupMaybe($numberOfRef, PDFNumber::class);
    }

    public function getRectangle()
    {
        $Rect = $this->Rect();
        $asRectangle = null;

        if ($Rect != null)
            $asRectangle = $Rect->asRectangle();
        if ($Rect == null || $asRectangle == null)
            return array(
                'x' => 0,
                'y' => 0,
                'width' => 0,
                'height' => 0
            );

        return $Rect->asRectangle();
    }

    public function setRectangle($rect)
    {
        $Rect = $this->dict->context->obj([$rect['x'], $rect['y'], $rect['x'] + $rect['width'], $rect['y'] + $rect['height']]);
        $this->dict->set(PDFName::of('Rect'), $Rect);
    }

    public function setAppearanceState($state)
    {
        $this->dict->set(PDFName::of('AS'), $state);
    }


    public function setAppearances($appearances)
    {
        $this->dict->set(PDFName::of('AP'), $appearances);
    }

    public function ensureAP()
    {
        $AP = $this->AP();
        if (!$AP)
        {
            $AP = $this->dict->context->obj(new stdClass());
            $this->dict->set(PDFName::of('AP'), $AP);
        }
        return $AP;
    }

    public function setNormalAppearance($appearance)
    {
        $AP = $this->ensureAP();
        $AP->set(PDFName::of('N'), $appearance);
    }

    public function setRolloverAppearance($appearance)
    {
        $AP = $this->ensureAP();
        $AP->set(PDFName::of('R'), $appearance);
    }

    public function setDownAppearance($appearance)
    {
        $AP = $this->ensureAP();
        $AP->set(PDFName::of('D'), $appearance);
    }

    public function removeRolloverAppearance()
    {
        $AP = $this->AP();
        if ($AP != null)
        {
            $AP->delete(PDFName::of('R'));
        }
    }

    public function removeDownAppearance()
    {
        $AP = $this->AP();
        if ($AP != null)
        {
            $AP->delete(PDFName::of('D'));
        }
    }

    public function getAppearances()
    {
        $AP = $this->AP();

        if (!$AP) return null;

        $N = $AP->lookup(PDFName::of('N'), PDFDict::class, PDFStream::class);
        $R = $AP->lookupMaybe(PDFName::of('R'), PDFDict::class, PDFStream::class);
        $D = $AP->lookupMaybe(PDFName::of('D'), PDFDict::class, PDFStream::class);

        return array("normal" => $N, "rollover" => $R, "down" => $D);
    }

    public function getFlags()
    {
        $f = $this->F();
        $number = 0;
        if ($f != null)
            $number = $f->asNumber();

        return $number;
    }

    public function setFlags($flags)
    {
        $this->dict->set(PDFName::of('F'), PDFNumber::of($flags));
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
}