<?php
/*
[cwmp_cart_logo]
[cwmp_cart_loja_name]
[cwmp_cart_loja_url]
[cwmp_cart_loja_email]
[cwmp_recovery_link]
 */
function cwmp_cart_logo($number_id)
{
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$custom_logo_data = wp_get_attachment_image_src( $custom_logo_id , 'full' );
	$custom_logo_url = $custom_logo_data[0];
	if($custom_logo_url){
	return $custom_logo_url;
	}
}
add_shortcode('cwmp_cart_logo', 'cwmp_cart_logo');
/* [cwmp_cart_loja_name] */
function cwmp_cart_loja_name($number_id)
{
    global $wp;
	if(get_option('blogname'))
	{
		return get_option('blogname');
	}
}
add_shortcode('cwmp_cart_loja_name', 'cwmp_cart_loja_name');


/* [cwmp_cart_loja_url] */
function cwmp_cart_loja_url($number_id)
{
    global $wp;
	if(get_option('siteurl'))
	{
		return get_option('siteurl');
	}
}
add_shortcode('cwmp_cart_loja_url', 'cwmp_cart_loja_url');

/* [cwmp_cart_loja_email] */
function cwmp_cart_loja_email($number_id)
{
    global $wp;
	if(get_option('admin_email'))
	{
		return get_option('admin_email');
	}
}
add_shortcode('cwmp_cart_loja_email', 'cwmp_cart_loja_email');