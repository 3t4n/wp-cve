<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Prokerala\Astrology\Vendor\Symfony\Component\Cache\DependencyInjection;

use Prokerala\Astrology\Vendor\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Prokerala\Astrology\Vendor\Symfony\Component\DependencyInjection\ContainerBuilder;
use Prokerala\Astrology\Vendor\Symfony\Component\DependencyInjection\Reference;
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class CachePoolClearerPass implements CompilerPassInterface
{
    private $cachePoolClearerTag;
    public function __construct(string $cachePoolClearerTag = 'cache.pool.clearer')
    {
        $this->cachePoolClearerTag = $cachePoolClearerTag;
    }
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $container->getParameterBag()->remove('cache.prefix.seed');
        foreach ($container->findTaggedServiceIds($this->cachePoolClearerTag) as $id => $attr) {
            $clearer = $container->getDefinition($id);
            $pools = [];
            foreach ($clearer->getArgument(0) as $name => $ref) {
                if ($container->hasDefinition($ref)) {
                    $pools[$name] = new Reference($ref);
                }
            }
            $clearer->replaceArgument(0, $pools);
        }
    }
}
