<?php


namespace rnpdfimporter\PDFLib\core\streams;


use rnpdfimporter\PDFLib\core\integration\ReferenceArray;

class Ascii85Stream extends DecodeStream
{
    public static $isSpace;
    private $stream;
    private $input;

    public function __construct($stream, $maybeMinBufferLength = false)
    {
        parent::__construct($maybeMinBufferLength);
        $this->stream = $stream;
        $this->input = ReferenceArray::withSize(5);
    }

    protected function readBlock()
    {
        $TILDA_CHAR = 0x7e; // '~'
        $Z_LOWER_CHAR = 0x7a; // 'z'
        $EOF = -1;

        $stream = $this->stream;

        $c = $stream->getByte();
        $intIsSpace=self::$isSpace;
        while ($intIsSpace($c))
        {
            $c = $stream->getByte();
        }

        if ($c === $EOF || $c === $TILDA_CHAR)
        {
            $this->eof = true;
            return;
        }

        $bufferLength = $this->bufferLength;
        $buffer = null;
        $i = null;

        // special code for z
        if ($c === $Z_LOWER_CHAR)
        {
            $buffer = $this->ensureBuffer($bufferLength + 4);
            for ($i = 0; $i < 4; ++$i)
            {
                $buffer[$bufferLength + $i] = 0;
            }
            $this->bufferLength += 4;
        } else
        {
            $input = $this->input;
            $input[0] = $c;
            for ($i = 1; $i < 5; ++$i)
            {
                $c = $stream->getByte();
                $intIsSpace=self::$isSpace;
                while ($intIsSpace($c))
                {
                    $c = $stream->getByte();
                }

                $input[$i] = $c;

                if ($c === $EOF || $c === $TILDA_CHAR)
                {
                    break;
                }
            }
            $buffer = $this->ensureBuffer($bufferLength + $i - 1);
            $this->bufferLength += $i - 1;

            // partial ending;
            if ($i < 5)
            {
                for (; $i < 5; ++$i)
                {
                    $input[$i] = 0x21 + 84;
                }
                $this->eof = true;
            }
            $t = 0;
            for ($i = 0; $i < 5; ++$i)
            {
                $t = $t * 85 + ($input[$i] - 0x21);
            }

            for ($i = 3; $i >= 0; --$i)
            {
                $buffer[$bufferLength + $i] = $t & 0xff;
                $t >>= 8;
            }
        }
    }


}

Ascii85Stream::$isSpace = function ($ch) {
    return $ch === 0x20 || $ch === 0x09 || $ch === 0x0d || $ch === 0x0a;
};