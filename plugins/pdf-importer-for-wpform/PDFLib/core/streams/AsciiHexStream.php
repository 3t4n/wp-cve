<?php


namespace rnpdfimporter\PDFLib\core\streams;


class AsciiHexStream extends DecodeStream
{
    private $stream;
    private $firstDigit;

    public function __construct($stream, $maybeMinBufferLength = false)
    {
        parent::__construct($maybeMinBufferLength);
        $this->stream = $stream;
        $this->firstDigit = -1;
        if ($maybeMinBufferLength)
            $maybeMinBufferLength = .5 * $maybeMinBufferLength;
    }

    protected function readBlock()
    {
        $UPSTREAM_BLOCK_SIZE = 8000;
        $bytes = $this->stream->getBytes($UPSTREAM_BLOCK_SIZE);
        if (!$bytes->length)
        {
            $this->eof = true;
            return;
        }

        $maxDecodeLength = ($bytes->length + 1) >> 1;
        $buffer = $this->ensureBuffer($this->bufferLength + $maxDecodeLength);
        $bufferLength = $this->bufferLength;

        $firstDigit = $this->firstDigit;
        for ($i = 0, $ii = $bytes->length(); $i < $ii; $i++)
        {
            $ch = $bytes[$i];
            $digit = null;
            if ($ch >= 0x30 && $ch <= 0x39)
            {
                // '0'-'9'
                $digit = $ch & 0x0f;
            } else if (($ch >= 0x41 && $ch <= 0x46) || ($ch >= 0x61 && $ch <= 0x66))
            {
                // 'A'-'Z', 'a'-'z'
                $digit = ($ch & 0x0f) + 9;
            } else if ($ch === 0x3e)
            {
                // '>'
                $this->eof = true;
                break;
            } else
            {
                // probably whitespace
                continue; // ignoring
            }
            if ($firstDigit < 0)
            {
                $firstDigit = $digit;
            } else
            {
                $buffer[$bufferLength++] = ($firstDigit << 4) | $digit;
                $firstDigit = -1;
            }
        }
        if ($firstDigit >= 0 && $this->eof)
        {
            // incomplete byte
            $buffer[$bufferLength++] = $firstDigit << 4;
            $firstDigit = -1;
        }
        $this->firstDigit = $firstDigit;
        $this->bufferLength = $bufferLength;
    }


}