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

use Prokerala\Astrology\Vendor\Symfony\Component\Cache\Adapter\RedisAdapter;
use Prokerala\Astrology\Vendor\Symfony\Component\Cache\Marshaller\MarshallerInterface;
use Prokerala\Astrology\Vendor\Symfony\Component\Cache\Traits\RedisClusterProxy;
use Prokerala\Astrology\Vendor\Symfony\Component\Cache\Traits\RedisProxy;
use Prokerala\Astrology\Vendor\Symfony\Component\Cache\Traits\RedisTrait;
use Prokerala\Astrology\Vendor\Symfony\Contracts\Cache\CacheInterface;
@\trigger_error(\sprintf('The "%s" class is deprecated since Symfony 4.3, use "%s" and type-hint for "%s" instead.', RedisCache::class, RedisAdapter::class, CacheInterface::class), \E_USER_DEPRECATED);
/**
 * @deprecated since Symfony 4.3, use RedisAdapter and type-hint for CacheInterface instead.
 */
class RedisCache extends AbstractCache
{
    use RedisTrait;
    /**
     * @param \Redis|\RedisArray|\RedisCluster|\Predis\ClientInterface|RedisProxy|RedisClusterProxy $redis
     */
    public function __construct($redis, string $namespace = '', int $defaultLifetime = 0, MarshallerInterface $marshaller = null)
    {
        $this->init($redis, $namespace, $defaultLifetime, $marshaller);
    }
}
