<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Prokerala\Astrology\Vendor\Symfony\Component\Cache\Simple;

use Prokerala\Astrology\Vendor\Doctrine\Common\Cache\CacheProvider;
use Prokerala\Astrology\Vendor\Symfony\Component\Cache\Adapter\DoctrineAdapter;
use Prokerala\Astrology\Vendor\Symfony\Component\Cache\Traits\DoctrineTrait;
use Prokerala\Astrology\Vendor\Symfony\Contracts\Cache\CacheInterface;
@\trigger_error(\sprintf('The "%s" class is deprecated since Symfony 4.3, use "%s" and type-hint for "%s" instead.', DoctrineCache::class, DoctrineAdapter::class, CacheInterface::class), \E_USER_DEPRECATED);
/**
 * @deprecated since Symfony 4.3, use DoctrineAdapter and type-hint for CacheInterface instead.
 */
class DoctrineCache extends AbstractCache
{
    use DoctrineTrait;
    public function __construct(CacheProvider $provider, string $namespace = '', int $defaultLifetime = 0)
    {
        parent::__construct('', $defaultLifetime);
        $this->provider = $provider;
        $provider->setNamespace($namespace);
    }
}
