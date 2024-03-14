<?php


namespace rnpdfimporter\PDFLib\core\acroform;


class AcroFieldFlags
{
    public static $ReadOnly;
    public static $Required;
    public static $NoExport;
}

AcroFieldFlags::$ReadOnly=flags::flag(1-1);
AcroFieldFlags::$Required=flags::flag(2-1);
AcroFieldFlags::$NoExport=flags::flag(3-1);

