<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\Translation\Provider;

use WPPayVendor\Symfony\Component\Translation\Exception\UnsupportedSchemeException;
/**
 * @author Mathieu Santostefano <msantostefano@protonmail.com>
 */
class TranslationProviderCollectionFactory
{
    private $factories;
    private $enabledLocales;
    /**
     * @param iterable<mixed, ProviderFactoryInterface> $factories
     */
    public function __construct(iterable $factories, array $enabledLocales)
    {
        $this->factories = $factories;
        $this->enabledLocales = $enabledLocales;
    }
    public function fromConfig(array $config) : \WPPayVendor\Symfony\Component\Translation\Provider\TranslationProviderCollection
    {
        $providers = [];
        foreach ($config as $name => $currentConfig) {
            $providers[$name] = $this->fromDsnObject(new \WPPayVendor\Symfony\Component\Translation\Provider\Dsn($currentConfig['dsn']), !$currentConfig['locales'] ? $this->enabledLocales : $currentConfig['locales'], !$currentConfig['domains'] ? [] : $currentConfig['domains']);
        }
        return new \WPPayVendor\Symfony\Component\Translation\Provider\TranslationProviderCollection($providers);
    }
    public function fromDsnObject(\WPPayVendor\Symfony\Component\Translation\Provider\Dsn $dsn, array $locales, array $domains = []) : \WPPayVendor\Symfony\Component\Translation\Provider\ProviderInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($dsn)) {
                return new \WPPayVendor\Symfony\Component\Translation\Provider\FilteringProvider($factory->create($dsn), $locales, $domains);
            }
        }
        throw new \WPPayVendor\Symfony\Component\Translation\Exception\UnsupportedSchemeException($dsn);
    }
}
