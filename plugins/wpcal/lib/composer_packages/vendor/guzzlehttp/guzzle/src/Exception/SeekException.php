<?php

namespace WPCal\ComposerPackages\GuzzleHttp\Exception;

use WPCal\ComposerPackages\Psr\Http\Message\StreamInterface;
/**
 * Exception thrown when a seek fails on a stream.
 */
class SeekException extends \RuntimeException implements \WPCal\ComposerPackages\GuzzleHttp\Exception\GuzzleException
{
    private $stream;
    public function __construct(\WPCal\ComposerPackages\Psr\Http\Message\StreamInterface $stream, $pos = 0, $msg = '')
    {
        $this->stream = $stream;
        $msg = $msg ?: 'Could not seek the stream to position ' . $pos;
        parent::__construct($msg);
    }
    /**
     * @return StreamInterface
     */
    public function getStream()
    {
        return $this->stream;
    }
}
