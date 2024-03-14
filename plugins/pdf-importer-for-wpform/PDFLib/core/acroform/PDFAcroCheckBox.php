<?php


namespace rnpdfimporter\PDFLib\core\acroform;


use Exception;
use rnpdfimporter\PDFLib\core\integration\ObjectIntegration;
use rnpdfimporter\PDFLib\core\objects\PDFName;
use rnpdfimporter\PDFLib\core\PDFContext;

class PDFAcroCheckBox extends PDFAcroButton
{
    public static function fromDict($dict, $ref)
    {
        return new PDFAcroCheckBox($dict, $ref);
    }

    /**
     * @param $context PDFContext
     */
    public static function create($context)
    {
        $dict = $context->obj((object)array(
            "FT" => 'Btn',
            "Kids" => array()
        ));

        $ref = $context->register($dict);
        return new PDFAcroCheckBox($dict, $ref);
    }

    public function setValue($value)
    {
        $onValue = ObjectIntegration::Coalesce($this->getOnValue(), PDFName::of('Yes'));
        if ($value !== $onValue && $value !== PDFName::of('Off'))
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

    public function getOnValue()
    {
        $widget = ObjectIntegration::ExtractPropertyFromObject($this->getWidgets(), 'widget');
        if ($widget == null)
            return null;
        return $widget->getOnValue();
    }

}