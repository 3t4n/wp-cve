<?php

add_action('gform_loaded', array('CSR_Feed_AddOn', 'load'), 5);

class CSR_Feed_AddOn {

    public static function load() {

        if (!method_exists('GFForms', 'include_feed_addon_framework')) {
            return;
        }

        require_once( 'class-csr-feedaddon.php' );

        GFAddOn::register('CSR_FeedAddon_Class');
    }

}

function csr_gf_feed_addon() {
    return CSR_FeedAddon_Class::get_instance();
}
