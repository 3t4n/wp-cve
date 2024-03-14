<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\Translation\Test;

use WPPayVendor\PHPUnit\Framework\TestCase;
use WPPayVendor\Psr\Log\LoggerInterface;
use WPPayVendor\Symfony\Component\HttpClient\MockHttpClient;
use WPPayVendor\Symfony\Component\Translation\Dumper\XliffFileDumper;
use WPPayVendor\Symfony\Component\Translation\Loader\LoaderInterface;
use WPPayVendor\Symfony\Component\Translation\Provider\ProviderInterface;
use WPPayVendor\Symfony\Contracts\HttpClient\HttpClientInterface;
/**
 * A test case to ease testing a translation provider.
 *
 * @author Mathieu Santostefano <msantostefano@protonmail.com>
 *
 * @internal
 */
abstract class ProviderTestCase extends \WPPayVendor\PHPUnit\Framework\TestCase
{
    protected $client;
    protected $logger;
    protected $defaultLocale;
    protected $loader;
    protected $xliffFileDumper;
    public static abstract function createProvider(\WPPayVendor\Symfony\Contracts\HttpClient\HttpClientInterface $client, \WPPayVendor\Symfony\Component\Translation\Loader\LoaderInterface $loader, \WPPayVendor\Psr\Log\LoggerInterface $logger, string $defaultLocale, string $endpoint) : \WPPayVendor\Symfony\Component\Translation\Provider\ProviderInterface;
    /**
     * @return iterable<array{0: ProviderInterface, 1: string}>
     */
    public static abstract function toStringProvider() : iterable;
    /**
     * @dataProvider toStringProvider
     */
    public function testToString(\WPPayVendor\Symfony\Component\Translation\Provider\ProviderInterface $provider, string $expected)
    {
        $this->assertSame($expected, (string) $provider);
    }
    protected function getClient() : \WPPayVendor\Symfony\Component\HttpClient\MockHttpClient
    {
        return $this->client ?? ($this->client = new \WPPayVendor\Symfony\Component\HttpClient\MockHttpClient());
    }
    protected function getLoader() : \WPPayVendor\Symfony\Component\Translation\Loader\LoaderInterface
    {
        return $this->loader ?? ($this->loader = $this->createMock(\WPPayVendor\Symfony\Component\Translation\Loader\LoaderInterface::class));
    }
    protected function getLogger() : \WPPayVendor\Psr\Log\LoggerInterface
    {
        return $this->logger ?? ($this->logger = $this->createMock(\WPPayVendor\Psr\Log\LoggerInterface::class));
    }
    protected function getDefaultLocale() : string
    {
        return $this->defaultLocale ?? ($this->defaultLocale = 'en');
    }
    protected function getXliffFileDumper() : \WPPayVendor\Symfony\Component\Translation\Dumper\XliffFileDumper
    {
        return $this->xliffFileDumper ?? ($this->xliffFileDumper = $this->createMock(\WPPayVendor\Symfony\Component\Translation\Dumper\XliffFileDumper::class));
    }
}
