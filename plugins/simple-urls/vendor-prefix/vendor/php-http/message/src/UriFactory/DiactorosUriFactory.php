<?php

namespace LassoLiteVendor\Http\Message\UriFactory;

use LassoLiteVendor\Http\Message\UriFactory;
use LassoLiteVendor\Laminas\Diactoros\Uri as LaminasUri;
use LassoLiteVendor\Psr\Http\Message\UriInterface;
use LassoLiteVendor\Zend\Diactoros\Uri as ZendUri;
/**
 * Creates Diactoros URI.
 *
 * @author David de Boer <david@ddeboer.nl>
 *
 * @deprecated This will be removed in php-http/message2.0. Consider using the official Diactoros PSR-17 factory
 */
final class DiactorosUriFactory implements UriFactory
{
    /**
     * {@inheritdoc}
     */
    public function createUri($uri)
    {
        if ($uri instanceof UriInterface) {
            return $uri;
        } elseif (\is_string($uri)) {
            if (\class_exists(LaminasUri::class)) {
                return new LaminasUri($uri);
            }
            return new ZendUri($uri);
        }
        throw new \InvalidArgumentException('URI must be a string or UriInterface');
    }
}
