<?php


namespace rnpdfimporter\PDFLib\core\syntax;


class WhiteSpace
{
    public static $IsWhiteSpace;
}

for($i=0;$i<256;$i++)
{
    WhiteSpace::$IsWhiteSpace[]=0;
}

WhiteSpace::$IsWhiteSpace[CharCodes::Null]=1;
WhiteSpace::$IsWhiteSpace[CharCodes::Tab]=1;
WhiteSpace::$IsWhiteSpace[CharCodes::Newline]=1;
WhiteSpace::$IsWhiteSpace[CharCodes::FormFeed]=1;
WhiteSpace::$IsWhiteSpace[CharCodes::CarriageReturn]=1;
WhiteSpace::$IsWhiteSpace[CharCodes::Space]=1;

