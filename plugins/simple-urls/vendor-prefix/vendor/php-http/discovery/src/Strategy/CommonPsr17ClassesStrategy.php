<?php

namespace LassoLiteVendor\Http\Discovery\Strategy;

use LassoLiteVendor\Psr\Http\Message\RequestFactoryInterface;
use LassoLiteVendor\Psr\Http\Message\ResponseFactoryInterface;
use LassoLiteVendor\Psr\Http\Message\ServerRequestFactoryInterface;
use LassoLiteVendor\Psr\Http\Message\StreamFactoryInterface;
use LassoLiteVendor\Psr\Http\Message\UploadedFileFactoryInterface;
use LassoLiteVendor\Psr\Http\Message\UriFactoryInterface;
/**
 * @internal
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class CommonPsr17ClassesStrategy implements DiscoveryStrategy
{
    /**
     * @var array
     */
    private static $classes = [RequestFactoryInterface::class => ['LassoLiteVendor\\Phalcon\\Http\\Message\\RequestFactory', 'LassoLiteVendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'LassoLiteVendor\\Zend\\Diactoros\\RequestFactory', 'LassoLiteVendor\\GuzzleHttp\\Psr7\\HttpFactory', 'LassoLiteVendor\\Http\\Factory\\Diactoros\\RequestFactory', 'LassoLiteVendor\\Http\\Factory\\Guzzle\\RequestFactory', 'LassoLiteVendor\\Http\\Factory\\Slim\\RequestFactory', 'LassoLiteVendor\\Laminas\\Diactoros\\RequestFactory', 'LassoLiteVendor\\Slim\\Psr7\\Factory\\RequestFactory'], ResponseFactoryInterface::class => ['LassoLiteVendor\\Phalcon\\Http\\Message\\ResponseFactory', 'LassoLiteVendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'LassoLiteVendor\\Zend\\Diactoros\\ResponseFactory', 'LassoLiteVendor\\GuzzleHttp\\Psr7\\HttpFactory', 'LassoLiteVendor\\Http\\Factory\\Diactoros\\ResponseFactory', 'LassoLiteVendor\\Http\\Factory\\Guzzle\\ResponseFactory', 'LassoLiteVendor\\Http\\Factory\\Slim\\ResponseFactory', 'LassoLiteVendor\\Laminas\\Diactoros\\ResponseFactory', 'LassoLiteVendor\\Slim\\Psr7\\Factory\\ResponseFactory'], ServerRequestFactoryInterface::class => ['LassoLiteVendor\\Phalcon\\Http\\Message\\ServerRequestFactory', 'LassoLiteVendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'LassoLiteVendor\\Zend\\Diactoros\\ServerRequestFactory', 'LassoLiteVendor\\GuzzleHttp\\Psr7\\HttpFactory', 'LassoLiteVendor\\Http\\Factory\\Diactoros\\ServerRequestFactory', 'LassoLiteVendor\\Http\\Factory\\Guzzle\\ServerRequestFactory', 'LassoLiteVendor\\Http\\Factory\\Slim\\ServerRequestFactory', 'LassoLiteVendor\\Laminas\\Diactoros\\ServerRequestFactory', 'LassoLiteVendor\\Slim\\Psr7\\Factory\\ServerRequestFactory'], StreamFactoryInterface::class => ['LassoLiteVendor\\Phalcon\\Http\\Message\\StreamFactory', 'LassoLiteVendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'LassoLiteVendor\\Zend\\Diactoros\\StreamFactory', 'LassoLiteVendor\\GuzzleHttp\\Psr7\\HttpFactory', 'LassoLiteVendor\\Http\\Factory\\Diactoros\\StreamFactory', 'LassoLiteVendor\\Http\\Factory\\Guzzle\\StreamFactory', 'LassoLiteVendor\\Http\\Factory\\Slim\\StreamFactory', 'LassoLiteVendor\\Laminas\\Diactoros\\StreamFactory', 'LassoLiteVendor\\Slim\\Psr7\\Factory\\StreamFactory'], UploadedFileFactoryInterface::class => ['LassoLiteVendor\\Phalcon\\Http\\Message\\UploadedFileFactory', 'LassoLiteVendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'LassoLiteVendor\\Zend\\Diactoros\\UploadedFileFactory', 'LassoLiteVendor\\GuzzleHttp\\Psr7\\HttpFactory', 'LassoLiteVendor\\Http\\Factory\\Diactoros\\UploadedFileFactory', 'LassoLiteVendor\\Http\\Factory\\Guzzle\\UploadedFileFactory', 'LassoLiteVendor\\Http\\Factory\\Slim\\UploadedFileFactory', 'LassoLiteVendor\\Laminas\\Diactoros\\UploadedFileFactory', 'LassoLiteVendor\\Slim\\Psr7\\Factory\\UploadedFileFactory'], UriFactoryInterface::class => ['LassoLiteVendor\\Phalcon\\Http\\Message\\UriFactory', 'LassoLiteVendor\\Nyholm\\Psr7\\Factory\\Psr17Factory', 'LassoLiteVendor\\Zend\\Diactoros\\UriFactory', 'LassoLiteVendor\\GuzzleHttp\\Psr7\\HttpFactory', 'LassoLiteVendor\\Http\\Factory\\Diactoros\\UriFactory', 'LassoLiteVendor\\Http\\Factory\\Guzzle\\UriFactory', 'LassoLiteVendor\\Http\\Factory\\Slim\\UriFactory', 'LassoLiteVendor\\Laminas\\Diactoros\\UriFactory', 'LassoLiteVendor\\Slim\\Psr7\\Factory\\UriFactory']];
    /**
     * {@inheritdoc}
     */
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
