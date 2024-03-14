<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Forms;

use OctolizeShippingNoticesVendor\Psr\Container\ContainerInterface;
use OctolizeShippingNoticesVendor\WPDesk\Persistence\PersistentContainer;
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
    public function set_data(\OctolizeShippingNoticesVendor\Psr\Container\ContainerInterface $data);
    /**
     * Put data from form into a container.
     *
     * @return void
     */
    public function put_data(\OctolizeShippingNoticesVendor\WPDesk\Persistence\PersistentContainer $container);
}
