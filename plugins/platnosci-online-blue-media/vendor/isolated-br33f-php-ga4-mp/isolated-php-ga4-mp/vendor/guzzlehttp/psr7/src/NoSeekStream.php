<?php

declare (strict_types=1);
namespace Isolated\Blue_Media\Isolated_Php_ga4_mp\GuzzleHttp\Psr7;

use Isolated\Blue_Media\Isolated_Php_ga4_mp\Psr\Http\Message\StreamInterface;
/**
 * Stream decorator that prevents a stream from being seeked.
 */
final class NoSeekStream implements StreamInterface
{
    use StreamDecoratorTrait;
    /** @var StreamInterface */
    private $stream;
    public function seek($offset, $whence = \SEEK_SET) : void
    {
        throw new \RuntimeException('Cannot seek a NoSeekStream');
    }
    public function isSeekable() : bool
    {
        return \false;
    }
}
