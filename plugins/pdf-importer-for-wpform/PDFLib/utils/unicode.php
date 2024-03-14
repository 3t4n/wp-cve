<?php


namespace rnpdfimporter\PDFLib\utils;


use Exception;
use rnpdfimporter\PDFLib\core\integration\ReferenceArray;

class unicode
{
    public static $BigEndian = 'BigEndian';
    public static $LittleEndian = 'LittleEndian';

    public static function utf16Encode($input, $byteOrderMark = true)
    {
        $encoded = new ReferenceArray();
        if ($byteOrderMark) $encoded[] = (0xfeff);

        for ($idx = 0, $len = \strlen($input); $idx < $len;)
        {
            $codePoint = self::_uniord($input[$idx]);

            // Two byte encoding
            if ($codePoint < 0x010000)
            {
                $encoded[] = $codePoint;
                $idx += 1;
            } // Four byte encoding (surrogate pair)
            else if ($codePoint < 0x110000)
            {
                $encoded[] = self::highSurrogate($codePoint);
                $encoded[] = self::lowSurrogate($codePoint);
                $idx += 2;
            } else throw new Exception('Invalid code point: 0x' . strings::toHexString($codePoint));
        }

        return new $encoded;
    }

    public static function isHighSurrogate($codePoint)
    {
        return $codePoint >= 0xd800 && $codePoint <= 0xdbff;
    }

    public static function isLowSurrogate($codePoint)
    {
        return $codePoint >= 0xdc00 && $codePoint <= 0xdfff;
    }

    public static function utf16Decode($input, $byteOrderMark = true)
    {
        $REPLACEMENT = self::_uniord('�');
        if (count($input) <= 1) return self::fromCodePoint($REPLACEMENT);

        $byteOrder = $byteOrderMark ? self::readBOM($input) : self::$BigEndian;

        // Skip byte order mark if needed
        $idx = $byteOrderMark ? 2 : 0;

        $codePoints = new ReferenceArray();

        while (count($input) - $idx >= 2)
        {
            $first = self::decodeValues($input[$idx++], $input[$idx++], $byteOrder);

            if (self::isHighSurrogate($first))
            {
                if (count($input) - $idx < 2)
                {
                    // Need at least 2 bytes left for the low surrogate that is required
                    $codePoints[] = $REPLACEMENT;
                } else
                {
                    $second = self::decodeValues($input[$idx++], $input[$idx++], $byteOrder);
                    if (self::isLowSurrogate($second))
                    {
                        $codePoints[] = $first;
                        $codePoints[] = $second;
                    } else
                    {
                        // Low surrogates should always follow high surrogates
                        $codePoints[] = $REPLACEMENT;
                    }
                }
            } else if (self::isLowSurrogate($first))
            {
                // High surrogates should always come first since `decodeValues()`
                // accounts for the byte ordering
                $idx += 2;
                $codePoints[] = $REPLACEMENT;
            } else
            {
                $codePoints[] = $first;
            }
        }

        // There shouldn't be extra byte(s) left over
        if ($idx < count($input)) $codePoints[] = $REPLACEMENT;

        return self::fromCodePoint(...$codePoints);
    }

    public static function decodeValues($first, $second, $byteOrder)
    {
        if ($byteOrder === self::$LittleEndian) return ($second << 8) | $first;
        if ($byteOrder === self::$BigEndian) return ($first << 8) | $second;
        throw new Exception(`Invalid byteOrder: ` . $byteOrder);
    }

    public static function _uniord($c)
    {
        if (ord($c{0}) >= 0 && ord($c{0}) <= 127)
            return ord($c{0});
        if (ord($c{0}) >= 192 && ord($c{0}) <= 223)
            return (ord($c{0}) - 192) * 64 + (ord($c{1}) - 128);
        if (ord($c{0}) >= 224 && ord($c{0}) <= 239)
            return (ord($c{0}) - 224) * 4096 + (ord($c{1}) - 128) * 64 + (ord($c{2}) - 128);
        if (ord($c{0}) >= 240 && ord($c{0}) <= 247)
            return (ord($c{0}) - 240) * 262144 + (ord($c{1}) - 128) * 4096 + (ord($c{2}) - 128) * 64 + (ord($c{3}) - 128);
        if (ord($c{0}) >= 248 && ord($c{0}) <= 251)
            return (ord($c{0}) - 248) * 16777216 + (ord($c{1}) - 128) * 262144 + (ord($c{2}) - 128) * 4096 + (ord($c{3}) - 128) * 64 + (ord($c{4}) - 128);
        if (ord($c{0}) >= 252 && ord($c{0}) <= 253)
            return (ord($c{0}) - 252) * 1073741824 + (ord($c{1}) - 128) * 16777216 + (ord($c{2}) - 128) * 262144 + (ord($c{3}) - 128) * 4096 + (ord($c{4}) - 128) * 64 + (ord($c{5}) - 128);
        if (ord($c{0}) >= 254 && ord($c{0}) <= 255)    //  error
            return FALSE;
        return 0;
    }

    public static function readBOM($bytes)
    {
        return self::hasUtf16BigEndianBOM($bytes) ? self::$BigEndian
            : self::hasUtf16LittleEndianBOM($bytes) ? self::$LittleEndian
                : self::$BigEndian;
    }

    public static function fromCodePoint(...$char)
    {
        $MAX_SIZE = 0x4000;
        $codeUnits = new ReferenceArray();
        $highSurrogate = null;
        $lowSurrogate = null;
        $index = -1;
        $length = count($char);

        $result = '';
        while (++$index < $length)
        {
            $codePoint = \intval($char[$index]);
            if (
                !\is_numeric($char[$index]) ||
                $codePoint < 0 || $codePoint > 0x10FFFF // not a valid Unicode code point
            )
            {
                throw new Exception('Invalid code point: ' . $codePoint);
            }
            if ($codePoint <= 0xFFFF)
            { // BMP code point
                $codeUnits[] = $codePoint;
            } else
            { // Astral code point; split in surrogate halves
                // https://mathiasbynens.be/notes/javascript-encoding#surrogate-formulae
                $codePoint -= 0x10000;
                $highSurrogate = ($codePoint >> 10) + 0xD800;
                $lowSurrogate = ($codePoint % 0x400) + 0xDC00;
                $codeUnits[] = $highSurrogate;
                $codeUnits[] = $lowSurrogate;
            }
            if ($index + 1 == $length || count($codeUnits) > $MAX_SIZE)
            {
                $result += strings::charCode($codeUnits);
                $codeUnits = new ReferenceArray();
            }
        }
        return $result;
    }

    public static function highSurrogate($codePoint)
    {
        return floor(($codePoint - 0x10000) / 0x400) + 0xd800;
    }

    public static function lowSurrogate($codePoint)
    {
        return (($codePoint - 0x10000) % 0x400) + 0xdc00;
    }

    public static function hasUtf16BigEndianBOM($bytes)
    {
        return $bytes[0] === 0xfe && $bytes[1] === 0xff;
    }

    public static function hasUtf16LittleEndianBOM($bytes)
    {
        return $bytes[0] === 0xff && $bytes[1] === 0xfe;
    }


    public static function hasUtf16BOM($bytes)
    {
        return self::hasUtf16BigEndianBOM($bytes) || self::hasUtf16LittleEndianBOM($bytes);
    }
}