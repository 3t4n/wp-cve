<?php


namespace rnpdfimporter\PDFLib\core\acroform;


use Exception;
use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\objects\PDFName;
use rnpdfimporter\PDFLib\core\PDFContext;

class PDFAcroRadioButton extends PDFAcroButton
{
    public static function fromDict($dict, $ref)
    {
        return new PDFAcroRadioButton($dict, $ref);
    }

    /**
     * @param $context PDFContext
     */
    public static function create($context)
    {
        $dict = $context->obj((object)array(
            'FT' => 'Btn',
            'Ff' => AcroButtonFlags::$Radio,
            'Kids' => new ReferenceArray()
        ));

        $ref = $context->register($dict);
        return new PDFAcroRadioButton($dict, $ref);
    }

    public function setValue($value)
    {
        $onValues = $this->getOnValues();
        if (!$onValues->includes($value) && $value !== PDFName::of('Off'))
        {
            throw new Exception('Invalid acro field');
        }

        $this->dict->set(PDFName::of('V'), $value);

        $widgets = $this->getWidgets();
        for ($idx = 0, $len = count($widgets); $idx < $len; $idx++)
        {
            $widget = $widgets[$idx];
            $state = $widget->getOnValue() === $value ? $value : PDFName::of('Off');
            $widget->setAppearanceState($state);
        }
    }

    public function getValue()
    {
        $v = $this->V();
        if ($v instanceof PDFName) return $v;
        return PDFName::of('Off');
    }

    public function getOnValues()
    {
        $widgets = $this->getWidgets();

        $onValues = new ReferenceArray();
        for ($idx = 0, $len = $widgets->length(); $idx < $len; $idx++)
        {
            $onValue = $widgets[$idx]->getOnValue();
            if ($onValue) $onValues->push($onValue);
        }

        return $onValues;
    }

}