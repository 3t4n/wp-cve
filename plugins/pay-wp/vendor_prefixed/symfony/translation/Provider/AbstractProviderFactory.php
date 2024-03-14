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

use WPPayVendor\Symfony\Component\Translation\Exception\IncompleteDsnException;
abstract class AbstractProviderFactory implements \WPPayVendor\Symfony\Component\Translation\Provider\ProviderFactoryInterface
{
    public function supports(\WPPayVendor\Symfony\Component\Translation\Provider\Dsn $dsn) : bool
    {
        return \in_array($dsn->getScheme(), $this->getSupportedSchemes(), \true);
    }
    /**
     * @return string[]
     */
    protected abstract function getSupportedSchemes() : array;
    protected function getUser(\WPPayVendor\Symfony\Component\Translation\Provider\Dsn $dsn) : string
    {
        if (null === ($user = $dsn->getUser())) {
            throw new \WPPayVendor\Symfony\Component\Translation\Exception\IncompleteDsnException('User is not set.', $dsn->getScheme() . '://' . $dsn->getHost());
        }
        return $user;
    }
    protected function getPassword(\WPPayVendor\Symfony\Component\Translation\Provider\Dsn $dsn) : string
    {
        if (null === ($password = $dsn->getPassword())) {
            throw new \WPPayVendor\Symfony\Component\Translation\Exception\IncompleteDsnException('Password is not set.', $dsn->getOriginalDsn());
        }
        return $password;
    }
}
