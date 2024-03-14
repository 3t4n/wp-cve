<?php

namespace ShopWP;

if (!defined('ABSPATH')) {
    exit();
}

class Deactivator
{
    public function __construct($Compatibility)
    {
        $this->Compatibility = $Compatibility;
    }

    public function on_plugin_deactivate()
    {
        unregister_post_type(SHOPWP_PRODUCTS_POST_TYPE_SLUG);
        unregister_post_type(SHOPWP_COLLECTIONS_POST_TYPE_SLUG);

        $this->Compatibility->delete_compatibility_mu();

    }

    public function init()
    {
        add_action('shopwp_on_plugin_deactivate', [$this, 'on_plugin_deactivate']);
    }
}
