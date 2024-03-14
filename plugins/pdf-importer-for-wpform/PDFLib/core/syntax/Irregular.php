<?php


namespace rnpdfimporter\PDFLib\core\syntax;


class Irregular
{
    public static $IsIrregular;
}

Irregular::$IsIrregular=array();

for($i=0;$i<256;$i++)
{
    Irregular::$IsIrregular[]=0;
}

for ($idx = 0, $len = 256; $idx < $len; $idx++) {
    Irregular::$IsIrregular[$idx] =WhiteSpace::$IsWhiteSpace[$idx] ||  Delimiters::$IsDelimiters[$idx] ? 1 : 0;
}
Irregular::$IsIrregular[CharCodes::Hash] = 1;
