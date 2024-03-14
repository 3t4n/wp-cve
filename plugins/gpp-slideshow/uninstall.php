<?php
if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
    exit();

delete_option( 'gpp_gallery' );
delete_option( 'widget_gpp-gallery-widget' );