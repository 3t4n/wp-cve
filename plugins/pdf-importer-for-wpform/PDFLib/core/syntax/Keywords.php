<?php


namespace rnpdfimporter\PDFLib\core\syntax;



class Keywords
{
    public static $header = [
        CharCodes::Percent,
        CharCodes::P,
        CharCodes::D,
        CharCodes::F,
        CharCodes::Dash,
    ];
    public static $eof = [
        CharCodes::Percent,
        CharCodes::Percent,
        CharCodes::E,
        CharCodes::O,
        CharCodes::F,
    ];

    public static $obj = [CharCodes::o, CharCodes::b, CharCodes::j];
    public static $endobj = [
        CharCodes::e,
        CharCodes::n,
        CharCodes::d,
        CharCodes::o,
        CharCodes::b,
        CharCodes::j,
    ];

    public static $xref = [CharCodes::x, CharCodes::r, CharCodes::e, CharCodes::f];
    public static $trailer = [
        CharCodes::t,
        CharCodes::r,
        CharCodes::a,
        CharCodes::i,
        CharCodes::l,
        CharCodes::e,
        CharCodes::r,
    ];

    public static $startxref = [
        CharCodes::s,
        CharCodes::t,
        CharCodes::a,
        CharCodes::r,
        CharCodes::t,
        CharCodes::x,
        CharCodes::r,
        CharCodes::e,
        CharCodes::f
    ];

    public static $true = [CharCodes::t, CharCodes::r, CharCodes::u, CharCodes::e];
    public static $false = [CharCodes::f, CharCodes::a, CharCodes::l, CharCodes::s, CharCodes::e];
    public static $null = [CharCodes::n, CharCodes::u, CharCodes::l, CharCodes::l];
    public static $stream=[
        CharCodes::s,
        CharCodes::t,
        CharCodes::r,
        CharCodes::e,
        CharCodes::a,
        CharCodes::m,
    ];
    public static $streamEOF1;
    public static $streamEOF2;
    public static $streamEOF3;
    public static $streamEOF4;
    public static $endStream=[
        CharCodes::e,
        CharCodes::n,
        CharCodes::d,
        CharCodes::s,
        CharCodes::t,
        CharCodes::r,
        CharCodes::e,
        CharCodes::a,
        CharCodes::m
    ];
    public static $EOF1endstream;
    public static $EOF2endstream;
    public static $EOF3endstream;



}


Keywords::$streamEOF1 = \array_merge(Keywords::$stream, array(CharCodes::Space, CharCodes::CarriageReturn, CharCodes::Newline));
Keywords::$streamEOF2 = \array_merge(Keywords::$stream, array(CharCodes::CarriageReturn, CharCodes::Newline));
Keywords::$streamEOF3 = \array_merge(Keywords::$stream, array(CharCodes::CarriageReturn));
Keywords::$streamEOF4 = Keywords::$stream;

Keywords::$EOF1endstream = \array_merge(array(CharCodes::CarriageReturn, CharCodes::Newline), Keywords::$endStream);
Keywords::$EOF2endstream = \array_merge(array(CharCodes::CarriageReturn), Keywords::$endStream);
Keywords::$EOF3endstream = \array_merge(array(CharCodes::Newline), Keywords::$endStream);

