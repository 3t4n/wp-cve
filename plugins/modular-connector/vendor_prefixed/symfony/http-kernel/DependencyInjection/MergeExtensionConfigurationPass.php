<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Modular\ConnectorDependencies\Symfony\Component\HttpKernel\DependencyInjection;

use Modular\ConnectorDependencies\Symfony\Component\DependencyInjection\Compiler\MergeExtensionConfigurationPass as BaseMergeExtensionConfigurationPass;
use Modular\ConnectorDependencies\Symfony\Component\DependencyInjection\ContainerBuilder;
/**
 * Ensures certain extensions are always loaded.
 *
 * @author Kris Wallsmith <kris@symfony.com>
 * @internal
 */
class MergeExtensionConfigurationPass extends BaseMergeExtensionConfigurationPass
{
    private $extensions;
    /**
     * @param string[] $extensions
     */
    public function __construct(array $extensions)
    {
        $this->extensions = $extensions;
    }
    public function process(ContainerBuilder $container)
    {
        foreach ($this->extensions as $extension) {
            if (!\count($container->getExtensionConfig($extension))) {
                $container->loadFromExtension($extension, []);
            }
        }
        parent::process($container);
    }
}
