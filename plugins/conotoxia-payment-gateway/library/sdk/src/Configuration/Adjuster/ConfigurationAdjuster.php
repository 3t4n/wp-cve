<?php

declare(strict_types=1);

namespace CKPL\Pay\Configuration\Adjuster;

use CKPL\Pay\Configuration\ConfigurationInterface;
use function substr;

/**
 * Class ConfigurationAdjuster.
 *
 * @package CKPL\Pay\Configuration\Adjuster
 */
class ConfigurationAdjuster implements ConfigurationAdjusterInterface
{
    /**
     * @param array $configuration
     */
    public function adjust(array &$configuration): void
    {
        $configuration[ConfigurationInterface::HOST] = $this
            ->urlLastSlash($configuration[ConfigurationInterface::HOST]);

        $configuration[ConfigurationInterface::OIDC] = $this
            ->urlLastSlash($configuration[ConfigurationInterface::OIDC]);

        $configuration[ConfigurationInterface::SIGN_ALGORITHM] = $this
            ->signAlgorithm($configuration[ConfigurationInterface::SIGN_ALGORITHM]);
    }

    /**
     * @param string $url
     *
     * @return string
     */
    protected function urlLastSlash(string $url): string
    {
        return '/' === substr($url, -1)
            ? $url
            : $url . '/';
    }

    /**
     * @param string $signAlgorithm
     *
     * @return int
     */
    protected function signAlgorithm(string $signAlgorithm): int
    {
        switch ($signAlgorithm) {
            case 'SHA512':
                $result = OPENSSL_ALGO_SHA512;
                break;
            case 'SHA384':
                $result = OPENSSL_ALGO_SHA384;
                break;
            default:
                $result = OPENSSL_ALGO_SHA256;
        }

        return $result;
    }
}
