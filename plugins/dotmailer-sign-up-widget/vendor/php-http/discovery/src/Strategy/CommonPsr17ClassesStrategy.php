<?php

namespace Dotdigital_WordPress_Vendor\Http\Discovery\Strategy;

use Dotdigital_WordPress_Vendor\Psr\Http\Message\RequestFactoryInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\ResponseFactoryInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\ServerRequestFactoryInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\StreamFactoryInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\UploadedFileFactoryInterface;
use Dotdigital_WordPress_Vendor\Psr\Http\Message\UriFactoryInterface;
/**
 * @internal
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * Don't miss updating src/Composer/Plugin.php when adding a new supported class.
 */
final class CommonPsr17ClassesStrategy implements DiscoveryStrategy
{
    /**
     * @var array
     */
    private static $classes = [RequestFactoryInterface::class => ['Dotdigital_WordPress_Vendor\\Phalcon\\Http\\Message\\RequestFactory', 'Dotdigital_WordPress_Vendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'Dotdigital_WordPress_Vendor\\GuzzleHttp\\Psr7\\HttpFactory', 'Dotdigital_WordPress_Vendor\\Http\\Factory\\Diactoros\\RequestFactory', 'Dotdigital_WordPress_Vendor\\Http\\Factory\\Guzzle\\RequestFactory', 'Dotdigital_WordPress_Vendor\\Http\\Factory\\Slim\\RequestFactory', 'Dotdigital_WordPress_Vendor\\Laminas\\Diactoros\\RequestFactory', 'Dotdigital_WordPress_Vendor\\Slim\\Psr7\\Factory\\RequestFactory', 'Dotdigital_WordPress_Vendor\\HttpSoft\\Message\\RequestFactory'], ResponseFactoryInterface::class => ['Dotdigital_WordPress_Vendor\\Phalcon\\Http\\Message\\ResponseFactory', 'Dotdigital_WordPress_Vendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'Dotdigital_WordPress_Vendor\\GuzzleHttp\\Psr7\\HttpFactory', 'Dotdigital_WordPress_Vendor\\Http\\Factory\\Diactoros\\ResponseFactory', 'Dotdigital_WordPress_Vendor\\Http\\Factory\\Guzzle\\ResponseFactory', 'Dotdigital_WordPress_Vendor\\Http\\Factory\\Slim\\ResponseFactory', 'Dotdigital_WordPress_Vendor\\Laminas\\Diactoros\\ResponseFactory', 'Dotdigital_WordPress_Vendor\\Slim\\Psr7\\Factory\\ResponseFactory', 'Dotdigital_WordPress_Vendor\\HttpSoft\\Message\\ResponseFactory'], ServerRequestFactoryInterface::class => ['Dotdigital_WordPress_Vendor\\Phalcon\\Http\\Message\\ServerRequestFactory', 'Dotdigital_WordPress_Vendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'Dotdigital_WordPress_Vendor\\GuzzleHttp\\Psr7\\HttpFactory', 'Dotdigital_WordPress_Vendor\\Http\\Factory\\Diactoros\\ServerRequestFactory', 'Dotdigital_WordPress_Vendor\\Http\\Factory\\Guzzle\\ServerRequestFactory', 'Dotdigital_WordPress_Vendor\\Http\\Factory\\Slim\\ServerRequestFactory', 'Dotdigital_WordPress_Vendor\\Laminas\\Diactoros\\ServerRequestFactory', 'Dotdigital_WordPress_Vendor\\Slim\\Psr7\\Factory\\ServerRequestFactory', 'Dotdigital_WordPress_Vendor\\HttpSoft\\Message\\ServerRequestFactory'], StreamFactoryInterface::class => ['Dotdigital_WordPress_Vendor\\Phalcon\\Http\\Message\\StreamFactory', 'Dotdigital_WordPress_Vendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'Dotdigital_WordPress_Vendor\\GuzzleHttp\\Psr7\\HttpFactory', 'Dotdigital_WordPress_Vendor\\Http\\Factory\\Diactoros\\StreamFactory', 'Dotdigital_WordPress_Vendor\\Http\\Factory\\Guzzle\\StreamFactory', 'Dotdigital_WordPress_Vendor\\Http\\Factory\\Slim\\StreamFactory', 'Dotdigital_WordPress_Vendor\\Laminas\\Diactoros\\StreamFactory', 'Dotdigital_WordPress_Vendor\\Slim\\Psr7\\Factory\\StreamFactory', 'Dotdigital_WordPress_Vendor\\HttpSoft\\Message\\StreamFactory'], UploadedFileFactoryInterface::class => ['Dotdigital_WordPress_Vendor\\Phalcon\\Http\\Message\\UploadedFileFactory', 'Dotdigital_WordPress_Vendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'Dotdigital_WordPress_Vendor\\GuzzleHttp\\Psr7\\HttpFactory', 'Dotdigital_WordPress_Vendor\\Http\\Factory\\Diactoros\\UploadedFileFactory', 'Dotdigital_WordPress_Vendor\\Http\\Factory\\Guzzle\\UploadedFileFactory', 'Dotdigital_WordPress_Vendor\\Http\\Factory\\Slim\\UploadedFileFactory', 'Dotdigital_WordPress_Vendor\\Laminas\\Diactoros\\UploadedFileFactory', 'Dotdigital_WordPress_Vendor\\Slim\\Psr7\\Factory\\UploadedFileFactory', 'Dotdigital_WordPress_Vendor\\HttpSoft\\Message\\UploadedFileFactory'], UriFactoryInterface::class => ['Dotdigital_WordPress_Vendor\\Phalcon\\Http\\Message\\UriFactory', 'Dotdigital_WordPress_Vendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'Dotdigital_WordPress_Vendor\\GuzzleHttp\\Psr7\\HttpFactory', 'Dotdigital_WordPress_Vendor\\Http\\Factory\\Diactoros\\UriFactory', 'Dotdigital_WordPress_Vendor\\Http\\Factory\\Guzzle\\UriFactory', 'Dotdigital_WordPress_Vendor\\Http\\Factory\\Slim\\UriFactory', 'Dotdigital_WordPress_Vendor\\Laminas\\Diactoros\\UriFactory', 'Dotdigital_WordPress_Vendor\\Slim\\Psr7\\Factory\\UriFactory', 'Dotdigital_WordPress_Vendor\\HttpSoft\\Message\\UriFactory']];
    public static function getCandidates($type)
    {
        $candidates = [];
        if (isset(self::$classes[$type])) {
            foreach (self::$classes[$type] as $class) {
                $candidates[] = ['class' => $class, 'condition' => [$class]];
            }
        }
        return $candidates;
    }
}
