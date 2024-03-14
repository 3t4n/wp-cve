<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Prokerala\Astrology\Vendor\Symfony\Component\Cache\Adapter;

use Prokerala\Astrology\Vendor\Doctrine\Common\Cache\CacheProvider;
use Prokerala\Astrology\Vendor\Symfony\Component\Cache\Traits\DoctrineTrait;
class DoctrineAdapter extends AbstractAdapter
{
    use DoctrineTrait;
    public function __construct(CacheProvider $provider, string $namespace = '', int $defaultLifetime = 0)
    {
        parent::__construct('', $defaultLifetime);
        $this->provider = $provider;
        $provider->setNamespace($namespace);
    }
}
