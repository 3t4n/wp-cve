<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Modular\ConnectorDependencies\Symfony\Component\HttpKernel\Bundle;

use Modular\ConnectorDependencies\Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Modular\ConnectorDependencies\Symfony\Component\DependencyInjection\ContainerBuilder;
use Modular\ConnectorDependencies\Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
/**
 * BundleInterface.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @internal
 */
interface BundleInterface extends ContainerAwareInterface
{
    /**
     * Boots the Bundle.
     */
    public function boot();
    /**
     * Shutdowns the Bundle.
     */
    public function shutdown();
    /**
     * Builds the bundle.
     *
     * It is only ever called once when the cache is empty.
     */
    public function build(ContainerBuilder $container);
    /**
     * Returns the container extension that should be implicitly loaded.
     *
     * @return ExtensionInterface|null
     */
    public function getContainerExtension();
    /**
     * Returns the bundle name (the class short name).
     *
     * @return string
     */
    public function getName();
    /**
     * Gets the Bundle namespace.
     *
     * @return string
     */
    public function getNamespace();
    /**
     * Gets the Bundle directory path.
     *
     * The path should always be returned as a Unix path (with /).
     *
     * @return string
     */
    public function getPath();
}
