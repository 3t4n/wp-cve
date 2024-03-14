<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$xyz_lbx_cache_enable=get_option("xyz_lbx_cache_enable");
$xyz_lbx_enable=get_option("xyz_lbx_enable");
$lbx_page_option=get_option('xyz_lbx_page_option');
if($xyz_lbx_enable==1)
{
	if($xyz_lbx_cache_enable==1)
	{
		add_shortcode( 'xyz_lbx_default_code', 'xyz_lbx_shortcode' );
	}
	else 
	{
		if($lbx_page_option==3)
		   add_shortcode( 'xyz_lbx_default_code', 'xyz_lbx_display' );
	}		
}
function xyz_lbx_shortcode()
{
	return "<span id='xyz_lbx_shortcode'></span>";
}
?>