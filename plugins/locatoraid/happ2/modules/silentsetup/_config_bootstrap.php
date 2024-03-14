<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['bootstrap'][] = function( $app )
{
	$setup = $app->db->table_exists('migrations');
	if( ! $setup ){
		$app->migration->init();
		if( ! $app->migration->current()){
			hc_show_error( $app->migration->error_string());
		}
	}
};