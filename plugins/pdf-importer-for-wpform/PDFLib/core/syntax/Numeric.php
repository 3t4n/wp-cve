<?php


namespace rnpdfimporter\PDFLib\core\syntax;


class Numeric
{
    public static $IsNumeric;
    public static $IsDigit;
    public static $IsNumericPrefix;

}


Numeric::$IsNumericPrefix=array();

for($i=0;$i<256;$i++)
{
    Numeric::$IsNumericPrefix[]=0;
}
Numeric::$IsNumericPrefix[CharCodes::Period]=1;
Numeric::$IsNumericPrefix[CharCodes::Plus]=1;
Numeric::$IsNumericPrefix[CharCodes::Minus]=1;


Numeric::$IsNumeric=array();

for($i=0;$i<256;$i++)
{
    Numeric::$IsNumeric[]=0;
}

Numeric::$IsDigit=array();

for($i=0;$i<256;$i++)
{
    Numeric::$IsDigit[]=0;
}


Numeric::$IsDigit[CharCodes::Zero]=1;
Numeric::$IsDigit[CharCodes::One]=1;
Numeric::$IsDigit[CharCodes::Two]=1;
Numeric::$IsDigit[CharCodes::Three]=1;
Numeric::$IsDigit[CharCodes::Four]=1;
Numeric::$IsDigit[CharCodes::Five]=1;
Numeric::$IsDigit[CharCodes::Six]=1;
Numeric::$IsDigit[CharCodes::Seven]=1;
Numeric::$IsDigit[CharCodes::Eight]=1;
Numeric::$IsDigit[CharCodes::Nine]=1;

for ($idx = 0, $len = 256; $idx < $len; $idx++) {
    Numeric::$IsNumeric[$idx] = Numeric::$IsDigit[$idx] ||  Numeric::$IsNumericPrefix[$idx] ? 1 : 0;
}
