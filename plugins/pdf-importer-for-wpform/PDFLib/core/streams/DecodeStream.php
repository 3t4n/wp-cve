<?php


namespace rnpdfimporter\PDFLib\core\streams;


use Exception;
use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\integration\Unit8ClampedArray;

class DecodeStream implements StreamType
{
    protected $bufferLength;
    protected $buffer;
    protected $eof;
    private $pos;
    private $minBufferLength;
    public static $emptyBuffer;

    public function __construct($maybeMinBufferLength = false)
    {
        $this->pos = 0;
        $this->bufferLength = 0;
        $this->eof = false;
        $this->buffer = DecodeStream::$emptyBuffer;
        $this->minBufferLength = 512;
        if ($maybeMinBufferLength)
        {
            // Compute the first power of two that is as big as maybeMinBufferLength.
            while ($this->minBufferLength < $maybeMinBufferLength)
            {
                $this->minBufferLength *= 2;
            }
        }
    }

    public function isEmpty()
    {
        while (!$this->eof && $this->bufferLength === 0)
        {
            $this->readBlock();
        }
        return $this->bufferLength === 0;
    }

    public function getByte()
    {
        $pos = $this->pos;
        while ($this->bufferLength <= $pos)
        {
            if ($this->eof)
            {
                return -1;
            }
            $this->readBlock();
        }
        return $this->buffer[$this->pos++];
    }

    public function getUint16()
    {
        $b0 = $this->getByte();
        $b1 = $this->getByte();
        if ($b0 === -1 || $b1 === -1)
        {
            return -1;
        }
        return ($b0 << 8) + $b1;
    }

    public function getInt32()
    {
        $b0 = $this->getByte();
        $b1 = $this->getByte();
        $b2 = $this->getByte();
        $b3 = $this->getByte();
        return ($b0 << 24) + ($b1 << 16) + ($b2 << 8) + $b3;
    }

    public function getBytes($length, $forceClamped)
    {
        $end = 0;
        $pos = $this->pos;

        if ($length)
        {
            $this->ensureBuffer($pos + $length);
            $end = $pos + $length;

            while (!$this->eof && $this->bufferLength < $end)
            {
                $this->readBlock();
            }
            $bufEnd = $this->bufferLength;
            if ($end > $bufEnd)
            {
                $end = $bufEnd;
            }
        } else
        {
            while (!$this->eof)
            {
                $this->readBlock();
            }
            $end = $this->bufferLength;
        }

        $this->pos = $end;
        $subarray = $this->buffer->subarray($pos, $end);
        // `this.buffer` is either a `Uint8Array` or `Uint8ClampedArray` here.
        return $forceClamped && !($subarray instanceof Unit8ClampedArray)
            ? new Unit8ClampedArray($subarray)
            : $subarray;
    }

    public function peekByte()
    {
        $peekedByte = $this->getByte();
        $this->pos--;
        return $peekedByte;
    }

    public function peekBytes($length, $forceClamped)
    {
        $bytes = $this->getBytes($length, $forceClamped);
        $this->pos -= $bytes->length();
        return $bytes;
    }

    public function skip($n)
    {
        if (!$n)
        {
            $n = 1;
        }
        $this->pos += $n;
    }

    public function reset()
    {
        $this->pos = 0;
    }

    public function makeSubStream($start, $length)
    {
        $end = $start + $length;
        while ($this->bufferLength <= $end && !$this->eof)
        {
            $this->readBlock();
        }
        return new Stream($this->buffer, $start, $length /* dict */);
    }

    public function decode()
    {
        while (!$this->eof) $this->readBlock();
        return $this->buffer->subarray(0, $this->bufferLength);
    }

    protected function readBlock()
    {
        throw new Exception('Method not implemented ');
    }

    protected function ensureBuffer($requested)
    {
        $buffer = $this->buffer;
        if ($requested <= $buffer->byteLength)
        {
            return $buffer;
        }
        $size = $this->minBufferLength;
        while ($size < $requested)
        {
            $size *= 2;
        }
        $buffer2 = ReferenceArray::withSize($size);
        $buffer2->set($buffer);
        return ($this->buffer = $buffer2);
    }
}

DecodeStream::$emptyBuffer = new ReferenceArray();