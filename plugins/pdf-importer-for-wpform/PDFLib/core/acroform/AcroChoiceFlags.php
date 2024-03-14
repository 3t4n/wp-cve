<?php


namespace rnpdfimporter\PDFLib\core\acroform;


class AcroChoiceFlags
{
    public static $Combo;
    public static $Edit;
    public static $Sort;
    public static $MultiSelect;
    public static $DoNotSpellCheck;
    public static $CommitOnSelChange;
}

AcroChoiceFlags::$Combo=flags::flag(18-1);
AcroChoiceFlags::$Edit=flags::flag(19-1);
AcroChoiceFlags::$Sort=flags::flag(20-1);
AcroChoiceFlags::$MultiSelect=flags::flag(22-1);
AcroChoiceFlags::$DoNotSpellCheck=flags::flag(23-1);
AcroChoiceFlags::$CommitOnSelChange=flags::flag(27-1);