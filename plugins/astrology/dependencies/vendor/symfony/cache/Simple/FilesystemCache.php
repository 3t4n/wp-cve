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

use Prokerala\Astrology\Vendor\Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Prokerala\Astrology\Vendor\Symfony\Component\Cache\Marshaller\DefaultMarshaller;
use Prokerala\Astrology\Vendor\Symfony\Component\Cache\Marshaller\MarshallerInterface;
use Prokerala\Astrology\Vendor\Symfony\Component\Cache\PruneableInterface;
use Prokerala\Astrology\Vendor\Symfony\Component\Cache\Traits\FilesystemTrait;
use Prokerala\Astrology\Vendor\Symfony\Contracts\Cache\CacheInterface;
@\trigger_error(\sprintf('The "%s" class is deprecated since Symfony 4.3, use "%s" and type-hint for "%s" instead.', FilesystemCache::class, FilesystemAdapter::class, CacheInterface::class), \E_USER_DEPRECATED);
/**
 * @deprecated since Symfony 4.3, use FilesystemAdapter and type-hint for CacheInterface instead.
 */
class FilesystemCache extends AbstractCache implements PruneableInterface
{
    use FilesystemTrait;
    public function __construct(string $namespace = '', int $defaultLifetime = 0, string $directory = null, MarshallerInterface $marshaller = null)
    {
        $this->marshaller = $marshaller ?? new DefaultMarshaller();
        parent::__construct('', $defaultLifetime);
        $this->init($namespace, $directory);
    }
}
