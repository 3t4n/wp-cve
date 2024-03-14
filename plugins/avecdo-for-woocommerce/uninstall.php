<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// Delete all options assigned by the Avecdo plugin
delete_option('avecdo_plugin_activated');
delete_option('avecdo_public_key');
delete_option('avecdo_private_key');
delete_option('avecdo_use_description');
delete_option('avecdo_language');
delete_option('avecdo_currency');
delete_option('avecdo_multi_lang_props');
delete_option('avecdo_activation_key');
delete_option('avecdo_v2_plugin_activated');
delete_option('avecdo_v2_public_key');
delete_option('avecdo_v2_private_key');
delete_option('avecdo_v2_use_description');
delete_option('avecdo_v2_language');
delete_option('avecdo_v2_currency');
delete_option('avecdo_v2_multi_lang_props');
delete_option('avecdo_v2_activation_key');
delete_option('avecdo_version');
