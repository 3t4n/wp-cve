<?php


namespace rnpdfimporter\PDFLib\core\streams;


use rnpdfimporter\PDFLib\core\integration\ReferenceArray;

class LZWStream extends DecodeStream
{
    private $stream;
    private $cachedData;
    private $bitsCached;
    /** @var LzwState */
    private $lzwState;

    public function __construct($stream, $maybeMinBufferLength, $earlyChange)
    {
        parent::__construct($maybeMinBufferLength);
        $this->stream = $stream;
        $this->cachedData = 0;
        $this->bitsCached = 0;

        $maxLzwDictionarySize = 4096;
        $lzwState = new LzwState();
        $lzwState->earlyChange = $earlyChange;
        $lzwState->codeLength = 9;
        $lzwState->nextCode = 258;
        $lzwState->dictionaryValues = ReferenceArray::withSize($maxLzwDictionarySize);
        $lzwState->dictionaryLengths = ReferenceArray::withSize($maxLzwDictionarySize);
        $lzwState->dictionaryPrevCodes = ReferenceArray::withSize($maxLzwDictionarySize);
        $lzwState->currentSequence = ReferenceArray::withSize($maxLzwDictionarySize);
        $lzwState->currentSequenceLength = 0;

        for ($i = 0; $i < 256; ++$i)
        {
            $lzwState->dictionaryValues[$i] = $i;
            $lzwState->dictionaryLengths[$i] = 1;
        }
        $this->lzwState = $lzwState;
    }

    public function readBlock()
    {
        $blockSize = 512;

        $estimatedDecodedSize = $blockSize * 2;
        $decodedSizeDelta = $blockSize;
        $i = null;
        $j = null;
        $q = null;

        $lzwState = $this->lzwState;
        if (!$lzwState)
        {
            return; // eof was found
        }

        $earlyChange = $lzwState->earlyChange;
        $nextCode = $lzwState->nextCode;
        $dictionaryValues = $lzwState->dictionaryValues;
        $dictionaryLengths = $lzwState->dictionaryLengths;
        $dictionaryPrevCodes = $lzwState->dictionaryPrevCodes;
        $codeLength = $lzwState->codeLength;
        $prevCode = $lzwState->prevCode;
        $currentSequence = $lzwState->currentSequence;
        $currentSequenceLength = $lzwState->currentSequenceLength;

        $decodedLength = 0;
        $currentBufferLength = $this->bufferLength;
        $buffer = $this->ensureBuffer($this->bufferLength + $estimatedDecodedSize);

        for ($i = 0; $i < $blockSize; $i++)
        {
            $code = $this->readBits($codeLength);
            $hasPrev = $currentSequenceLength > 0;
            if (!$code || $code < 256)
            {
                $currentSequence[0] = $code;
                $currentSequenceLength = 1;
            } else if ($code >= 258)
            {
                if ($code < $nextCode)
                {
                    $currentSequenceLength = $dictionaryLengths[$code];
                    for ($j = $currentSequenceLength - 1, $q = $code; $j >= 0; $j--)
                    {
                        $currentSequence[$j] = $dictionaryValues[$q];
                        $q = $dictionaryPrevCodes[$q];
                    }
                } else
                {
                    $currentSequence[$currentSequenceLength++] = $currentSequence[0];
                }
            } else if ($code === 256)
            {
                $codeLength = 9;
                $nextCode = 258;
                $currentSequenceLength = 0;
                continue;
            } else
            {
                $this->eof = true;
                $this->lzwState = null;
                break;
            }

            if ($hasPrev)
            {
                $dictionaryPrevCodes[$nextCode] = $prevCode;
                $dictionaryLengths[$nextCode] = $dictionaryLengths[$prevCode] + 1;
                $dictionaryValues[$nextCode] = $currentSequence[0];
                $nextCode++;
                $codeLength =
                    ($nextCode + $earlyChange) & ($nextCode + $earlyChange - 1)
                        ? $codeLength
                        : min(
                            log($nextCode + $earlyChange) / 0.6931471805599453 + 1,
                            12
                        ) | 0;

                if ($codeLength === null)
                    $codeLength = 0;
            }
            $prevCode = $code;

            $decodedLength += $currentSequenceLength;
            if ($estimatedDecodedSize < $decodedLength)
            {
                do
                {
                    $estimatedDecodedSize += $decodedSizeDelta;
                } while ($estimatedDecodedSize < $decodedLength);
                $buffer = $this->ensureBuffer($this->bufferLength + $estimatedDecodedSize);
            }
            for ($j = 0; $j < $currentSequenceLength; $j++)
            {
                $buffer[$currentBufferLength++] = $currentSequence[$j];
            }
        }
        $lzwState->nextCode = $nextCode;
        $lzwState->codeLength = $codeLength;
        $lzwState->prevCode = $prevCode;
        $lzwState->currentSequenceLength = $currentSequenceLength;

        $this->bufferLength = $currentBufferLength;
    }

    private function readBits($n)
    {
        $bitsCached = $this->bitsCached;
        $cachedData = $this->cachedData;
        while ($bitsCached < $n)
        {
            $c = $this->stream->getByte();
            if ($c === -1)
            {
                $this->eof = true;
                return null;
            }
            $cachedData = ($cachedData << 8) | $c;
            $bitsCached += 8;
        }
        $this->bitsCached = $bitsCached -= $n;
        $this->cachedData = $cachedData;
        return ($this->uRShift($cachedData, $bitsCached)) & ((1 << $n) - 1);
    }


    function uRShift($a, $b)
    {
        if($b == 0) return $a;
        return ($a >> $b) & ~(1<<(8*PHP_INT_SIZE-1)>>($b-1));
    }
}

class LzwState
{
    public $earlyChange;
    public $codeLength;
    public $nextCode;
    public $dictionaryValues;
    public $dictionaryLengths;
    public $dictionaryPrevCodes;
    public $currentSequence;
    public $currentSequenceLength;
    public $prevCode;
}