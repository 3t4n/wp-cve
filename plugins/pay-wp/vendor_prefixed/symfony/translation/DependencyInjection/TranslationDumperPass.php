<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\Translation\DependencyInjection;

use WPPayVendor\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use WPPayVendor\Symfony\Component\DependencyInjection\ContainerBuilder;
use WPPayVendor\Symfony\Component\DependencyInjection\Reference;
/**
 * Adds tagged translation.formatter services to translation writer.
 */
class TranslationDumperPass implements \WPPayVendor\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface
{
    private $writerServiceId;
    private $dumperTag;
    public function __construct(string $writerServiceId = 'translation.writer', string $dumperTag = 'translation.dumper')
    {
        if (1 < \func_num_args()) {
            trigger_deprecation('symfony/translation', '5.3', 'Configuring "%s" is deprecated.', __CLASS__);
        }
        $this->writerServiceId = $writerServiceId;
        $this->dumperTag = $dumperTag;
    }
    public function process(\WPPayVendor\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->writerServiceId)) {
            return;
        }
        $definition = $container->getDefinition($this->writerServiceId);
        foreach ($container->findTaggedServiceIds($this->dumperTag, \true) as $id => $attributes) {
            $definition->addMethodCall('addDumper', [$attributes[0]['alias'], new \WPPayVendor\Symfony\Component\DependencyInjection\Reference($id)]);
        }
    }
}
