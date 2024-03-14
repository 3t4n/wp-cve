<?php


namespace rnpdfimporter\PDFLib\core\syntax;


class Delimiters
{
    public static $IsDelimiters;
}

Delimiters::$IsDelimiters=[];

for($i=0;$i<256;$i++)
{
    Delimiters::$IsDelimiters[]=0;
}

Delimiters::$IsDelimiters[CharCodes::LeftParen]=1;
Delimiters::$IsDelimiters[CharCodes::RightParen]=1;
Delimiters::$IsDelimiters[CharCodes::LessThan]=1;
Delimiters::$IsDelimiters[CharCodes::GreaterThan]=1;
Delimiters::$IsDelimiters[CharCodes::LeftSquareBracket]=1;
Delimiters::$IsDelimiters[CharCodes::RightSquareBracket]=1;
Delimiters::$IsDelimiters[CharCodes::LeftCurly]=1;
Delimiters::$IsDelimiters[CharCodes::RightCurly]=1;
Delimiters::$IsDelimiters[CharCodes::ForwardSlash]=1;
Delimiters::$IsDelimiters[CharCodes::Percent]=1;