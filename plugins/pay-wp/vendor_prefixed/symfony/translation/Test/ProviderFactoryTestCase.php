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
use WPPayVendor\Symfony\Component\Translation\Exception\IncompleteDsnException;
use WPPayVendor\Symfony\Component\Translation\Exception\UnsupportedSchemeException;
use WPPayVendor\Symfony\Component\Translation\Loader\LoaderInterface;
use WPPayVendor\Symfony\Component\Translation\Provider\Dsn;
use WPPayVendor\Symfony\Component\Translation\Provider\ProviderFactoryInterface;
use WPPayVendor\Symfony\Contracts\HttpClient\HttpClientInterface;
/**
 * A test case to ease testing a translation provider factory.
 *
 * @author Mathieu Santostefano <msantostefano@protonmail.com>
 *
 * @internal
 */
abstract class ProviderFactoryTestCase extends \WPPayVendor\PHPUnit\Framework\TestCase
{
    protected $client;
    protected $logger;
    protected $defaultLocale;
    protected $loader;
    protected $xliffFileDumper;
    public abstract function createFactory() : \WPPayVendor\Symfony\Component\Translation\Provider\ProviderFactoryInterface;
    /**
     * @return iterable<array{0: bool, 1: string}>
     */
    public static abstract function supportsProvider() : iterable;
    /**
     * @return iterable<array{0: string, 1: string}>
     */
    public static abstract function createProvider() : iterable;
    /**
     * @return iterable<array{0: string, 1: string|null}>
     */
    public static function unsupportedSchemeProvider() : iterable
    {
        return [];
    }
    /**
     * @return iterable<array{0: string, 1: string|null}>
     */
    public static function incompleteDsnProvider() : iterable
    {
        return [];
    }
    /**
     * @dataProvider supportsProvider
     */
    public function testSupports(bool $expected, string $dsn)
    {
        $factory = $this->createFactory();
        $this->assertSame($expected, $factory->supports(new \WPPayVendor\Symfony\Component\Translation\Provider\Dsn($dsn)));
    }
    /**
     * @dataProvider createProvider
     */
    public function testCreate(string $expected, string $dsn)
    {
        $factory = $this->createFactory();
        $provider = $factory->create(new \WPPayVendor\Symfony\Component\Translation\Provider\Dsn($dsn));
        $this->assertSame($expected, (string) $provider);
    }
    /**
     * @dataProvider unsupportedSchemeProvider
     */
    public function testUnsupportedSchemeException(string $dsn, ?string $message = null)
    {
        $factory = $this->createFactory();
        $dsn = new \WPPayVendor\Symfony\Component\Translation\Provider\Dsn($dsn);
        $this->expectException(\WPPayVendor\Symfony\Component\Translation\Exception\UnsupportedSchemeException::class);
        if (null !== $message) {
            $this->expectExceptionMessage($message);
        }
        $factory->create($dsn);
    }
    /**
     * @dataProvider incompleteDsnProvider
     */
    public function testIncompleteDsnException(string $dsn, ?string $message = null)
    {
        $factory = $this->createFactory();
        $dsn = new \WPPayVendor\Symfony\Component\Translation\Provider\Dsn($dsn);
        $this->expectException(\WPPayVendor\Symfony\Component\Translation\Exception\IncompleteDsnException::class);
        if (null !== $message) {
            $this->expectExceptionMessage($message);
        }
        $factory->create($dsn);
    }
    protected function getClient() : \WPPayVendor\Symfony\Contracts\HttpClient\HttpClientInterface
    {
        return $this->client ?? ($this->client = new \WPPayVendor\Symfony\Component\HttpClient\MockHttpClient());
    }
    protected function getLogger() : \WPPayVendor\Psr\Log\LoggerInterface
    {
        return $this->logger ?? ($this->logger = $this->createMock(\WPPayVendor\Psr\Log\LoggerInterface::class));
    }
    protected function getDefaultLocale() : string
    {
        return $this->defaultLocale ?? ($this->defaultLocale = 'en');
    }
    protected function getLoader() : \WPPayVendor\Symfony\Component\Translation\Loader\LoaderInterface
    {
        return $this->loader ?? ($this->loader = $this->createMock(\WPPayVendor\Symfony\Component\Translation\Loader\LoaderInterface::class));
    }
    protected function getXliffFileDumper() : \WPPayVendor\Symfony\Component\Translation\Dumper\XliffFileDumper
    {
        return $this->xliffFileDumper ?? ($this->xliffFileDumper = $this->createMock(\WPPayVendor\Symfony\Component\Translation\Dumper\XliffFileDumper::class));
    }
}
