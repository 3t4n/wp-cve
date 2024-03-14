<?php


namespace rnpdfimporter\PDFLib\core\acroform;


use Exception;
use rnpdfimporter\PDFLib\core\integration\ArrayIntegration;
use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\objects\PDFArray;
use rnpdfimporter\PDFLib\core\objects\PDFHexString;
use rnpdfimporter\PDFLib\core\objects\PDFName;
use rnpdfimporter\PDFLib\core\objects\PDFString;

class PDFAcroChoice extends PDFAcroTerminal
{
    public function setValues($values)
    {
        if (
            $this->hasFlag(AcroChoiceFlags::$Combo) &&
            !$this->hasFlag(AcroChoiceFlags::$Edit) &&
            !$this->valuesAreValid($values)
        )
        {
            throw new Exception('Invalid acro exception');
        }

        if (count($values) === 0)
        {
            $this->dict->delete(PDFName::of('V'));
        }
        if (count($values) === 1)
        {
            $this->dict->set(PDFName::of('V'), $values[0]);
        }
        if (count($values) > 1)
        {
            if (!$this->hasFlag(AcroChoiceFlags::$MultiSelect))
            {
                throw new Exception('Multi select error');
            }
            $this->dict->set(PDFName::of('V'), $this->dict->context->obj($values));
        }

        $this->updateSelectedIndices($values);
    }

    public function valuesAreValid($values)
    {
        $options = $this->getOptions();
        for ($idx = 0, $len = count($values); $idx < $len; $idx++)
        {
            $val = $values[$idx]->decodeText();
            if (!ArrayIntegration::Find($options, function ($o) use ($val) {
                $valueToDecode = isset($o['display']) ? $o['display'] : $o['value'];
                return $val === $valueToDecode->decodeText();
            }))
            {
                return false;
            }

        }
        return true;
    }

    public function updateSelectedIndices($values)
    {
        if (count($values) > 1)
        {
            $indices = new ReferenceArray(count($values));
            $options = $this->getOptions();
            for ($idx = 0, $len = count($values); $idx < $len; $idx++)
            {
                $val = $values[$idx]->decodeText();
                $indices[$idx] = $options->findIndex(
                    function ($o) use ($val) {
                        return $val === ($o['display'] == null ? $o['value'] : $o['display'])->decodeText();
                    }


                );
            }
            $nativeArray = $indices->getArrayCopy();
            $this->dict->set(PDFName::of('I'), $this->dict->context->obj(sort($nativeArray)));
        } else
        {
            $this->dict->delete(PDFName::of('I'));
        }
    }

    public function getValues()
    {
        $v = $this->V();

        if ($v instanceof PDFString || $v instanceof PDFHexString) return ReferenceArray::createFromArray([$v]);

        if ($v instanceof PDFArray)
        {
            $values = new ReferenceArray();

            for ($idx = 0, $len = $v->size(); $idx < $len; $idx++)
            {
                $value = $v->lookup($idx);
                if ($value instanceof PDFString || $value instanceof PDFHexString)
                {
                    $values->push($value);
                }
            }

            return $values;
        }

        return [];
    }

    public function Opt()
    {
        return $this->dict->lookupMaybe(
            PDFName::of('Opt'),
            PDFString::class,
            PDFHexString::class,
            PDFArray::class
        );
    }

    public function setOptions($options)
    {
        $newOpt = ReferenceArray::withSize(count($options));
        for ($idx = 0, $len = count($options); $idx < $len; $idx++)
        {
            $value = $options[$idx]['value'];
            $display = $options[$idx]['display'];

            $newOpt[$idx] = $this->dict->context->obj([$value, $display == null ? $value : $display]);
        }
        $this->dict->set(PDFName::of('Opt'), $this->dict->context->obj($newOpt));
    }


    public function getOptions()
    {
        $Opt = $this->Opt();

        // Not supposed to happen - Opt _should_ always be `PDFArray | undefined`
        if ($Opt instanceof PDFString || $Opt instanceof PDFHexString)
        {
            return ReferenceArray::createFromArray([array("value" => $Opt, "display" => $Opt)]);
        }

        if ($Opt instanceof PDFArray)
        {
            $res = new ReferenceArray();

            for ($idx = 0, $len = $Opt->size(); $idx < $len; $idx++)
            {
                $item = $Opt->lookup($idx);

                // If `item` is a string, use that as both the export and text value
                if ($item instanceof PDFString || $item instanceof PDFHexString)
                {
                    $res->push(array("value" => $item, "display" => $item));
                }

                // If `item` is an array of one, treat it the same as just a string,
                // if it's an array of two then `item[0]` is the export value and
                // `item[1]` is the text value
                if ($item instanceof PDFArray)
                {
                    if ($item->size() > 0)
                    {
                        $first = $item->lookup(0, PDFString::class, PDFHexString::class);
                        $second = $item->lookupMaybe(1, PDFString::class, PDFHexString::class);
                        $res->push(array("value" => $first, "display" => $second ? $first : $second));
                    }
                }
            }

            return $res;
        }

        return [];
    }
}