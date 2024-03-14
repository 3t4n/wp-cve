<?php


namespace rnpdfimporter\PDFLib\core\acroform;


class flags
{
    public static function flag($bitIndex)
    {
        return 1<<$bitIndex;
    }
}