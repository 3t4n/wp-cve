<?php


namespace rnpdfimporter\PDFLib\core\streams;


use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\integration\Unit8ClampedArray;

class Stream implements StreamType
{
    private $bytes;
    private $start;
    private $pos;
    private $end;

    /**
     * Stream constructor.
     */
    public function __construct($buffer, $start, $length)
    {
        $this->bytes = $buffer;
        $this->start = $start == null ? 0 : $start;
        $this->pos = $this->start;
        $this->end = !!$start && !!$length ? $start + $length : $this->bytes->length;
    }

    public function length()
    {
        return $this->end - $this->start;
    }

    public function isEmpty()
    {
        return $this->length() === 0;
    }

    public function getByte()
    {
        if ($this->pos >= $this->end)
        {
            return -1;
        }
        return $this->bytes[$this->pos++];
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
        $bytes = $this->bytes;
        $pos = $this->pos;
        $strEnd = $this->end;

        if (!$length)
        {
            $subarray = $bytes->subarray($pos, $strEnd);
            // `this.bytes` is always a `Uint8Array` here.
            return $forceClamped ? new Unit8ClampedArray($subarray) : $subarray;
        } else
        {
            $end = $pos + $length;
            if ($end > $strEnd)
            {
                $end = $strEnd;
            }
            $this->pos = $end;
            $subarray = $bytes->subarray($pos, $end);
            // `this.bytes` is always a `Uint8Array` here.
            return $forceClamped ? new Unit8ClampedArray($subarray) : $subarray;
        }
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
        $this->pos -= $bytes->length;
        return $bytes;
    }

    public function skip($n)
    {
        if (!$n) {
            $n = 1;
        }
        $this->pos += $n;
    }

    public function reset()
    {
        $this->pos=$this->start;
    }
    public function moveStart() {
        $this->start = $this->pos;
    }

    public function makeSubStream($start, $length)
    {
        return new Stream($this->bytes,$start,$length);
    }

    public function decode()
    {
        return $this->bytes;
    }
}