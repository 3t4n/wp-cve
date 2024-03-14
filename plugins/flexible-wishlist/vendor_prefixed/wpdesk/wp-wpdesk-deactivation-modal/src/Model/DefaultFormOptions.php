<?php

namespace FlexibleWishlistVendor\WPDesk\DeactivationModal\Model;

use FlexibleWishlistVendor\WPDesk\DeactivationModal\Exception\DuplicatedFormOptionKeyException;
/**
 * Default list of plugin deactivation reason for plugins using older libraries.
 */
class DefaultFormOptions extends \FlexibleWishlistVendor\WPDesk\DeactivationModal\Model\FormOptions
{
    /**
     * @throws DuplicatedFormOptionKeyException
     */
    public function __construct()
    {
        $this->set_option(new \FlexibleWishlistVendor\WPDesk\DeactivationModal\Model\FormOption('plugin_stopped_working', 10, \__('The plugin suddenly stopped working', 'flexible-wishlist')));
        $this->set_option(new \FlexibleWishlistVendor\WPDesk\DeactivationModal\Model\FormOption('broke_my_site', 20, \__('The plugin broke my site', 'flexible-wishlist')));
        $this->set_option(new \FlexibleWishlistVendor\WPDesk\DeactivationModal\Model\FormOption('found_better_plugin', 30, \__('I found a better plugin', 'flexible-wishlist'), null, \__('What\'s the plugin\'s name?', 'flexible-wishlist')));
        $this->set_option(new \FlexibleWishlistVendor\WPDesk\DeactivationModal\Model\FormOption('plugin_for_short_period', 40, \__('I only needed the plugin for a short period', 'flexible-wishlist')));
        $this->set_option(new \FlexibleWishlistVendor\WPDesk\DeactivationModal\Model\FormOption('no_longer_need', 50, \__('I no longer need the plugin', 'flexible-wishlist')));
        $this->set_option(new \FlexibleWishlistVendor\WPDesk\DeactivationModal\Model\FormOption('temporary_deactivation', 60, \__('It\'s a temporary deactivation (I\'m just debugging an issue)', 'flexible-wishlist')));
        $this->set_option(new \FlexibleWishlistVendor\WPDesk\DeactivationModal\Model\FormOption('other', 70, \__('Other', 'flexible-wishlist'), null, \__('Kindly tell us the reason so we can improve', 'flexible-wishlist')));
    }
}
