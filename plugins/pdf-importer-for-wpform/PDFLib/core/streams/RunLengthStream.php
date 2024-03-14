<?php


namespace rnpdfimporter\PDFLib\core\streams;


class RunLengthStream extends DecodeStream
{
    private $stream;

    public function __construct($stream, $maybeMinBufferLength = false)
    {
        parent::__construct($maybeMinBufferLength);
        $this->stream = $stream;
    }


    public function readBlock()
    {
        // The repeatHeader has following format. The first byte defines type of run
        // and amount of bytes to repeat/copy: n = 0 through 127 - copy next n bytes
        // (in addition to the second byte from the header), n = 129 through 255 -
        // duplicate the second byte from the header (257 - n) times, n = 128 - end.
        $repeatHeader = $this->stream->getBytes(2);
        if (!$repeatHeader || $repeatHeader->length < 2 || $repeatHeader[0] === 128)
        {
            $this->eof = true;
            return;
        }

        $buffer = null;
        $bufferLength = $this->bufferLength;
        $n = $repeatHeader[0];
        if ($n < 128)
        {
            // copy n bytes
            $buffer = $this->ensureBuffer($bufferLength + $n + 1);
            $buffer[$bufferLength++] = $repeatHeader[1];
            if ($n > 0)
            {
                $source = $this->stream->getBytes($n);
                $buffer->set($source, $bufferLength);
                $bufferLength += $n;
            }
        } else
        {
            $n = 257 - $n;
            $b = $repeatHeader[1];
            $buffer = $this->ensureBuffer($bufferLength + $n + 1);
            for ($i = 0; $i < $n; $i++)
            {
                $buffer[$bufferLength++] = $b;
            }
        }
        $this->bufferLength = $bufferLength;
    }

}