<?php

namespace UpsFreeVendor\WPDesk\Forms;

use UpsFreeVendor\Psr\Container\ContainerInterface;
use UpsFreeVendor\WPDesk\Persistence\PersistentContainer;
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
    public function set_data(\UpsFreeVendor\Psr\Container\ContainerInterface $data);
    /**
     * Put data from form into a container.
     *
     * @return void
     */
    public function put_data(\UpsFreeVendor\WPDesk\Persistence\PersistentContainer $container);
}
