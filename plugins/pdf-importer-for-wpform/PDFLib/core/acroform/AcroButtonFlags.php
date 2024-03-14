<?php


namespace rnpdfimporter\PDFLib\core\acroform;


class AcroButtonFlags
{
    public static $NoToggleToOff;
    public static $Radio;
    public static $PushButton;
    public static $RadiosInUnison;
}

AcroButtonFlags::$NoToggleToOff=flags::flag(15-1);
AcroButtonFlags::$Radio=flags::flag(16-1);
AcroButtonFlags::$PushButton=flags::flag(17-1);
AcroButtonFlags::$RadiosInUnison=flags::flag(26-1);