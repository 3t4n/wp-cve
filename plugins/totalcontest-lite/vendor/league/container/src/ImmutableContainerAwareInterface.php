<?php

namespace TotalContestVendors\League\Container;

use TotalContestVendors\Interop\Container\ContainerInterface as InteropContainerInterface;

interface ImmutableContainerAwareInterface
{
    /**
     * Set a container
     *
     * @param \TotalContestVendors\Interop\Container\ContainerInterface $container
     */
    public function setContainer(InteropContainerInterface $container);

    /**
     * Get the container
     *
     * @return \TotalContestVendors\League\Container\ImmutableContainerInterface
     */
    public function getContainer();
}
