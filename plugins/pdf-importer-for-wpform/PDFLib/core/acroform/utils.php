<?php


namespace rnpdfimporter\PDFLib\core\acroform;


use rnpdfimporter\js\src\lib\PDFLib\core\acroform\PDFAcroListBox;
use rnpdfimporter\PDFLib\core\integration\ObjectIntegration;
use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\objects\PDFArray;
use rnpdfimporter\PDFLib\core\objects\PDFDict;
use rnpdfimporter\PDFLib\core\objects\PDFName;
use rnpdfimporter\PDFLib\core\objects\PDFNumber;
use rnpdfimporter\PDFLib\core\objects\PDFRef;

class utils
{


    public static function createPDFAcroFields($kidDicts)
    {
        if (!$kidDicts) return new ReferenceArray();

        $kids = new ReferenceArray();
        for ($idx = 0, $len = $kidDicts->size(); $idx < $len; $idx++)
        {
            $ref = $kidDicts->get($idx);
            $dict = $kidDicts->lookup($idx);
            // if (dict instanceof PDFDict) kids.push(PDFAcroField.fromDict(dict));
            if ($ref instanceof PDFRef && $dict instanceof PDFDict)
            {
                $kids->push(self::createPDFAcroField($dict, $ref));
                $kids->push($ref);
            }
        }

        return $kids;
    }

    public static function createPDFAcroField($dict, $ref)
    {
        $isNonTerminal = self::isNonTerminalAcroField($dict);
        if ($isNonTerminal) return PDFAcroNonTerminal::fromDict($dict, $ref);
        return self::createPDFAcroTerminal($dict, $ref);
    }

    /**
     *
     * @param $dict PDFDict
     * @return bool
     */
    public static function isNonTerminalAcroField($dict)
    {
        $kids = $dict->lookup(PDFName::of('Kids'));

        if ($kids instanceof PDFArray)
        {
            for ($idx = 0, $len = $kids->size(); $idx < $len; $idx++)
            {
                $kid = $kids->lookup($idx);
                $kidIsField = $kid instanceof PDFDict && $kid->has(PDFName::of('T'));
                if ($kidIsField) return true;
            }
        }

        return false;
    }

    public static function createPDFAcroTerminal($dict, $ref)
    {
        $ftNameOrRef = self::getInheritableAttribute($dict, PDFName::of('FT'));
        $type = $dict->context->lookup($ftNameOrRef, PDFName::class);

        if ($type === PDFName::of('Btn')) return self::createPDFAcroButton($dict, $ref);
        if ($type === PDFName::of('Ch')) return self::createPDFAcroChoice($dict, $ref);
        if ($type === PDFName::of('Tx')) return PDFAcroText::fromDict($dict, $ref);
        if ($type === PDFName::of('Sig')) return PDFAcroSignature::fromDict($dict, $ref);

        // We should never reach this line. But there are a lot of weird PDFs out
        // there. So, just to be safe, we'll try to handle things gracefully instead
        // of throwing an error.
        return PDFAcroTerminal::fromDict($dict, $ref);
    }

    public static function getInheritableAttribute($startNode, $name)
    {
        $attribute = null;
        self::ascend($startNode, function ($node) use (&$attribute, $name) {
            if (!$attribute) $attribute = $node->get($name);
        });
        return $attribute;
    }

    public static function ascend($startNode, $visitor)
    {
        $visitor($startNode);
        $Parent = $startNode->lookupMaybe(PDFName::of('Parent'), PDFDict::class);
        if ($Parent) self::ascend($Parent, $visitor);
    }

    public static function createPDFAcroButton($dict, $ref)
    {
        $ffNumberOrRef = self::getInheritableAttribute($dict, PDFName::of('Ff'));
        $ffNumber = $dict->context->lookupMaybe($ffNumberOrRef, PDFNumber::class);
        $flags = $ffNumber == null ? 0 : $ffNumber->asNumber();

        if (self::flagIsSet($flags, AcroButtonFlags::$PushButton))
        {
            return PDFAcroPushButton::fromDict($dict, $ref);
        } else if (self::flagIsSet($flags, AcroButtonFlags::$Radio))
        {
            return PDFAcroRadioButton::fromDict($dict, $ref);
        } else
        {
            return PDFAcroCheckBox::fromDict($dict, $ref);
        }
    }

    public static function flagIsSet($flags, $flag)
    {
        return $flags & $flag !== 0;
    }

    public static function createPDFAcroChoice($dict, $ref)
    {
        $ffNumberOrRef = self::getInheritableAttribute($dict, PDFName::of('Ff'));
        $ffNumber = $dict->context->lookupMaybe($ffNumberOrRef, PDFNumber::class);
        $flags = ObjectIntegration::Coalesce($ffNumber == null ? 0 : $ffNumber->asNumber(), 0);

        if (self::flagIsSet($flags, AcroChoiceFlags::$Combo))
        {
            return PDFAcroComboBox::fromDict($dict, $ref);
        } else
        {
            return PDFAcroListBox::fromDict($dict, $ref);
        }
    }

}