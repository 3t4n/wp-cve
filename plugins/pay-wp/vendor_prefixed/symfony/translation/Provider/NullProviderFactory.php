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
final class NullProviderFactory extends \WPPayVendor\Symfony\Component\Translation\Provider\AbstractProviderFactory
{
    public function create(\WPPayVendor\Symfony\Component\Translation\Provider\Dsn $dsn) : \WPPayVendor\Symfony\Component\Translation\Provider\ProviderInterface
    {
        if ('null' === $dsn->getScheme()) {
            return new \WPPayVendor\Symfony\Component\Translation\Provider\NullProvider();
        }
        throw new \WPPayVendor\Symfony\Component\Translation\Exception\UnsupportedSchemeException($dsn, 'null', $this->getSupportedSchemes());
    }
    protected function getSupportedSchemes() : array
    {
        return ['null'];
    }
}
