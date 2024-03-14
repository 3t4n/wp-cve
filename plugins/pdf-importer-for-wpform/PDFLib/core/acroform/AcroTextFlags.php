<?php


namespace rnpdfimporter\PDFLib\core\acroform;


class AcroTextFlags
{
    public static $Multiline;
    public static $Password;
    public static $FileSelect;
    public static $DoNotSpellCheck;
    public static $DoNotScroll;
    public static $Comb;
    public static $RichText;
}

AcroTextFlags::$Multiline=flags::flag(13-1);
AcroTextFlags::$Password=flags::flag(14-1);
AcroTextFlags::$FileSelect=flags::flag(21-1);
AcroTextFlags::$DoNotSpellCheck=flags::flag(23-1);
AcroTextFlags::$DoNotScroll=flags::flag(24-1);
AcroTextFlags::$Comb=flags::flag(25-1);
AcroTextFlags::$RichText=flags::flag(26-1);
