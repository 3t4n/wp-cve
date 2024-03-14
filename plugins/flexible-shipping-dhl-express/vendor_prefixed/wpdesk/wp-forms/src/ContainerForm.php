<?php

namespace DhlVendor\WPDesk\Forms;

use DhlVendor\Psr\Container\ContainerInterface;
use DhlVendor\WPDesk\Persistence\PersistentContainer;
/**
 * Persistent container support for forms.
 *
 * @package WPDesk\Forms
 */
interface ContainerForm
{
    /**
     * @param ContainerInterface $data
     *
     * @return void
     */
    public function set_data(\DhlVendor\Psr\Container\ContainerInterface $data);
    /**
     * Put data from form into a container.
     *
     * @return void
     */
    public function put_data(\DhlVendor\WPDesk\Persistence\PersistentContainer $container);
}
