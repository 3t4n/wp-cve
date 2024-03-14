<?php

if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') ) 
	exit();

$hpt_option = 'hpt_headertitle';
delete_post_meta_by_key( $hpt_option );
?>