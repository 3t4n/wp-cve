<?php


namespace rnpdfimporter\PDFLib\core\acroform;


use Exception;
use rnpdfimporter\PDFLib\core\integration\ObjectIntegration;
use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\objects\PDFArray;
use rnpdfimporter\PDFLib\core\objects\PDFHexString;
use rnpdfimporter\PDFLib\core\objects\PDFName;
use rnpdfimporter\PDFLib\core\objects\PDFString;

class PDFAcroButton extends PDFAcroTerminal
{
    public function Opt()
    {
        return $this->dict->lookupMaybe(PDFName::of('Opt'), PDFString::class, PDFHexString::class, PDFArray::class);
    }

    public function setOpt($opt)
    {
        $this->dict->set(PDFName::of('Opt'), $this->dict->context->obj($opt));
    }

    public function getExportValues()
    {
        $opt = $this->Opt();

        if (!$opt) return null;

        if ($opt instanceof PDFString || $opt instanceof PDFHexString)
        {
            return [$opt];
        }

        $values = [];
        for ($idx = 0, $len = $opt->size(); $idx < $len; $idx++)
        {
            $value = $opt->lookup($idx);
            if ($value instanceof PDFString || $value instanceof PDFHexString)
            {
                $values[] = $value;
            }
        }

        return $values;
    }

    public function removeExportValue($idx)
    {
        $opt = $this->Opt();

        if (!$opt) return;

        if ($opt instanceof PDFString || $opt instanceof PDFHexString)
        {
            if ($idx !== 0) throw new Exception('Index out of bound');
            $this->setOpt([]);
        } else
        {
            if ($idx < 0 || $idx > $opt->size())
            {
                throw new Exception('Index out of bound');
            }
            $opt->remove($idx);
        }
    }

    public function normalizeExportValues()
    {
        $exportValues = $this->getExportValues();
        if ($exportValues == null)
            $exportValues = [];

        $Opt = [];

        $widgets = $this->getWidgets();
        for ($idx = 0, $len = count($widgets); $idx < $len; $idx++)
        {
            $widget = $widgets[$idx];
            $exportVal = $widgets[$idx];
            if ($exportVal == null)
            {
                $stringVal = '';
                $value = $widget->getOnValue();
                if ($value != null)
                    $stringVal = $value->decodeText();

                $exportVal = PDFHexString::fromText($stringVal);
            }
            $Opt[] = $exportVal;
        }

        $this->setOpt($Opt);
    }


    public function addOpt($opt, $useExistingOptIdx)
    {
        $this->normalizeExportValues();

        $optText = $opt->decodeText();

        $existingIdx = null;
        if ($useExistingOptIdx)
        {
            $exportValues = ObjectIntegration::Coalesce($this->getExportValues(), new ReferenceArray());
            for ($idx = 0, $len = $exportValues->length; $idx < $len; $idx++)
            {
                $exportVal = $exportValues[$idx];
                if ($exportVal->decodeText() === $optText) $existingIdx = $idx;
            }
        }

        $Opt = $this->Opt();
        $Opt->push($opt);

        return ObjectIntegration::Coalesce($existingIdx, $Opt->size() - 1);
    }

}