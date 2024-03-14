<?php

namespace LassoLiteVendor\Http\Message\StreamFactory;

use LassoLiteVendor\Http\Message\StreamFactory;
use LassoLiteVendor\Laminas\Diactoros\Stream as LaminasStream;
use LassoLiteVendor\Psr\Http\Message\StreamInterface;
use LassoLiteVendor\Zend\Diactoros\Stream as ZendStream;
/**
 * Creates Diactoros streams.
 *
 * @author Михаил Красильников <m.krasilnikov@yandex.ru>
 *
 * @deprecated This will be removed in php-http/message2.0. Consider using the official Diactoros PSR-17 factory
 */
final class DiactorosStreamFactory implements StreamFactory
{
    /**
     * {@inheritdoc}
     */
    public function createStream($body = null)
    {
        if ($body instanceof StreamInterface) {
            return $body;
        }
        if (\is_resource($body)) {
            if (\class_exists(LaminasStream::class)) {
                return new LaminasStream($body);
            }
            return new ZendStream($body);
        }
        if (\class_exists(LaminasStream::class)) {
            $stream = new LaminasStream('php://memory', 'rw');
        } else {
            $stream = new ZendStream('php://memory', 'rw');
        }
        if (null !== $body && '' !== $body) {
            $stream->write((string) $body);
        }
        return $stream;
    }
}
