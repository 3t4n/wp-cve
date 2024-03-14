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
use WPPayVendor\Symfony\Component\Translation\Exception\UnsupportedSchemeException;
interface ProviderFactoryInterface
{
    /**
     * @throws UnsupportedSchemeException
     * @throws IncompleteDsnException
     */
    public function create(\WPPayVendor\Symfony\Component\Translation\Provider\Dsn $dsn) : \WPPayVendor\Symfony\Component\Translation\Provider\ProviderInterface;
    public function supports(\WPPayVendor\Symfony\Component\Translation\Provider\Dsn $dsn) : bool;
}
