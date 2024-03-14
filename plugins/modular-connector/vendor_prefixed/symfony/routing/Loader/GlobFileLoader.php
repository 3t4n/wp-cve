<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Modular\ConnectorDependencies\Symfony\Component\Routing\Loader;

use Modular\ConnectorDependencies\Symfony\Component\Config\Loader\FileLoader;
use Modular\ConnectorDependencies\Symfony\Component\Routing\RouteCollection;
/**
 * GlobFileLoader loads files from a glob pattern.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 * @internal
 */
class GlobFileLoader extends FileLoader
{
    /**
     * {@inheritdoc}
     */
    public function load($resource, ?string $type = null)
    {
        $collection = new RouteCollection();
        foreach ($this->glob($resource, \false, $globResource) as $path => $info) {
            $collection->addCollection($this->import($path));
        }
        $collection->addResource($globResource);
        return $collection;
    }
    /**
     * {@inheritdoc}
     */
    public function supports($resource, ?string $type = null)
    {
        return 'glob' === $type;
    }
}
