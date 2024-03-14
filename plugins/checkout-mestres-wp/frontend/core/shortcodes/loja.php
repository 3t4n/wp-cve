<?php
/* [cwmp_order_loja_name] */
function cwmp_order_loja_name($number_id)
{
    global $wp;
	if(get_option('blogname'))
	{
		return get_option('blogname');
	}
}
add_shortcode('cwmp_order_loja_name', 'cwmp_order_loja_name');


/* [cwmp_order_loja_url] */
function cwmp_order_loja_url($number_id)
{
    global $wp;
	if(get_option('siteurl'))
	{
		return get_option('siteurl');
	}
}
add_shortcode('cwmp_order_loja_url', 'cwmp_order_loja_url');

/* [cwmp_order_loja_email] */
function cwmp_order_loja_email($number_id)
{
    global $wp;
	if(get_option('admin_email'))
	{
		return get_option('admin_email');
	}
}
add_shortcode('cwmp_order_loja_email', 'cwmp_order_loja_email');