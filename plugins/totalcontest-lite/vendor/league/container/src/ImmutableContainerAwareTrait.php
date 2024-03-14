<?php

namespace TotalContestVendors\League\Container;

use TotalContestVendors\Interop\Container\ContainerInterface as InteropContainerInterface;

trait ImmutableContainerAwareTrait
{
    /**
     * @var \TotalContestVendors\Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * Set a container.
     *
     * @param  \TotalContestVendors\Interop\Container\ContainerInterface $container
     * @return $this
     */
    public function setContainer(InteropContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Get the container.
     *
     * @return \TotalContestVendors\League\Container\ImmutableContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}
