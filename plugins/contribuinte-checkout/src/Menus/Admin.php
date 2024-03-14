<?php

namespace Checkout\Contribuinte\Menus;

class Admin
{
    /**
     * This class parent
     * @var
     */
    public $parent;

    /**
     * Admin constructor.
     * @param $parent
     */
    public function __construct($parent)
    {
        $this->parent = $parent;
        //Set priority high (100) so that this sub-menu is the last one under WooCommerce menu
        add_action('admin_menu', [$this, 'admin_menu'],100);
    }

    /**
     * Adds an entry in the settings tab
     */
    public function admin_menu()
    {
        if (current_user_can('manage_woocommerce')) {
            //add submenu under woocommerce
            add_submenu_page('woocommerce',__('Contribuinte Checkout', 'contribuinte-checkout'), __('VAT', 'contribuinte-checkout'), 'manage_woocommerce', 'contribuintecheckout', [$this->parent, 'settingsPage']);
        }
    }
}