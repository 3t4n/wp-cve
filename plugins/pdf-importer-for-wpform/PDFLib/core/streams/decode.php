<?php


namespace rnpdfimporter\PDFLib\core\streams;


use Exception;
use rnpdfimporter\PDFLib\core\objects\PDFArray;
use rnpdfimporter\PDFLib\core\objects\PDFDict;
use rnpdfimporter\PDFLib\core\objects\PDFName;
use rnpdfimporter\PDFLib\core\objects\PDFNumber;

class decode
{
    public static function decodeStream($stream, $encoding, $params)
    {
        if ($encoding === PDFName::of('FlateDecode'))
        {
            return new FlateStream($stream);
        }
        if ($encoding === PDFName::of('LZWDecode'))
        {
            $earlyChange = 1;
            if ($params instanceof PDFDict)
            {
                $EarlyChange = $params->lookup(PDFName::of('EarlyChange'));
                if ($EarlyChange instanceof PDFNumber)
                {
                    $earlyChange = $EarlyChange->asNumber();
                }
            }
            return new LZWStream($stream, null, $earlyChange);
        }
        if ($encoding === PDFName::of('ASCII85Decode'))
        {
            return new Ascii85Stream($stream);
        }
        if ($encoding === PDFName::of('ASCIIHexDecode'))
        {
            return new AsciiHexStream($stream);
        }
        if ($encoding === PDFName::of('RunLengthDecode'))
        {
            return new RunLengthStream($stream);
        }

        throw new Exception('Unsupported');
    }

    public static function decodePDFRawStream($dict, $contents)
    {
        $stream = new Stream($contents, 0, 0);

        $Filter = $dict->lookup(PDFName::of('Filter'));
        $DecodeParms = $dict->lookup(PDFName::of('DecodeParms'));

        if ($Filter instanceof PDFName)
        {
            $stream = self::decodeStream(
                $stream,
                $Filter,
                $DecodeParms
            );
        } else if ($Filter instanceof PDFArray)
        {
            for ($idx = 0, $len = $Filter->size(); $idx < $len; $idx++)
            {
                $stream = self::decodeStream(
                    $stream,
                    $Filter->lookup($idx, PDFName::class),
                    $DecodeParms && ($DecodeParms)->lookupMaybe($idx, PDFDict::class)
                );
            }
        } else if (!!$Filter)
        {
            throw new Exception('Unexpected object');
        }

        return $stream;
    }

}