<?php

declare (strict_types=1);
namespace DhlVendor\GuzzleHttp\Psr7;

use DhlVendor\Psr\Http\Message\UriInterface;
/**
 * Provides methods to determine if a modified URL should be considered cross-origin.
 *
 * @author Graham Campbell
 */
final class UriComparator
{
    /**
     * Determines if a modified URL should be considered cross-origin with
     * respect to an original URL.
     */
    public static function isCrossOrigin(\DhlVendor\Psr\Http\Message\UriInterface $original, \DhlVendor\Psr\Http\Message\UriInterface $modified) : bool
    {
        if (\strcasecmp($original->getHost(), $modified->getHost()) !== 0) {
            return \true;
        }
        if ($original->getScheme() !== $modified->getScheme()) {
            return \true;
        }
        if (self::computePort($original) !== self::computePort($modified)) {
            return \true;
        }
        return \false;
    }
    private static function computePort(\DhlVendor\Psr\Http\Message\UriInterface $uri) : int
    {
        $port = $uri->getPort();
        if (null !== $port) {
            return $port;
        }
        return 'https' === $uri->getScheme() ? 443 : 80;
    }
    private function __construct()
    {
        // cannot be instantiated
    }
}
