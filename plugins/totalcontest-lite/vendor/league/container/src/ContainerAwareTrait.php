<?php

namespace TotalContestVendors\League\Container;

trait ContainerAwareTrait
{
    /**
     * @var \TotalContestVendors\League\Container\ContainerInterface
     */
    protected $container;

    /**
     * Set a container.
     *
     * @param  \TotalContestVendors\League\Container\ContainerInterface $container
     * @return $this
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Get the container.
     *
     * @return \TotalContestVendors\League\Container\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}
