<?php
    defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

    unregister_setting( 'general', "dadata_api_key" );
    unregister_setting( 'general', "dadata_woo_off" );
    unregister_setting( 'general', "dadata_use_mask" );
    unregister_setting( 'general', "dadata_locations" );
    unregister_setting( 'general', "dadata_count_r" );
    unregister_setting( 'general', "dadata_hint" );
    unregister_setting( 'general', "dadata_minchars" );
    delete_option('dadata_api_key'); 
    delete_option('dadata_woo_off');
    delete_option('dadata_count_r');
    delete_option('dadata_hint');
    delete_option('dadata_minchars');
    delete_option('dadata_use_mask');
    delete_option('dadata_locations');

?>