<?php
function cwmp_order_billing_logradouro($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val'])){
        $cwmp_order_info = wc_get_order($number_id['val']);
		return $cwmp_order_info->get_billing_address_1();
    } else {
		if(isset($_GET['cwmp_order'])){
			$cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
			return $cwmp_order_info->get_billing_address_1();
		}
    }
}
add_shortcode('cwmp_order_billing_logradouro', 'cwmp_order_billing_logradouro');


function cwmp_order_billing_numero($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
		return $cwmp_order_info->get_meta( '_billing_number' );
    }
    else
    {
		if(isset($_GET['cwmp_order']))
		{
			$cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
			return $cwmp_order_info->get_meta( '_billing_number' );
		}
    }
}
add_shortcode('cwmp_order_billing_numero', 'cwmp_order_billing_numero');


function cwmp_order_billing_complemento($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
		return $cwmp_order_info->get_billing_address_2();
    }
    else
    {
		if(isset($_GET['cwmp_order']))
		{
			$cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
			return $cwmp_order_info->get_billing_address_2();
		}
    }
}
add_shortcode('cwmp_order_billing_complemento', 'cwmp_order_billing_complemento');


function cwmp_order_billing_bairro($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
		return $cwmp_order_info->get_meta( '_billing_neighborhood' );
    }
    else
    {
		if(isset($_GET['cwmp_order']))
		{
			$cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
			return $cwmp_order_info->get_meta( '_billing_neighborhood' );
		}
    }
}
add_shortcode('cwmp_order_billing_bairro', 'cwmp_order_billing_bairro');


function cwmp_order_billing_cidade($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
		return $cwmp_order_info->get_billing_city();
    }
    else
    {
		if(isset($_GET['cwmp_order']))
		{
			$cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
			return $cwmp_order_info->get_billing_city();
		}
    }
    
}
add_shortcode('cwmp_order_billing_cidade', 'cwmp_order_billing_cidade');


function cwmp_order_billing_estado($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
		return $cwmp_order_info->get_billing_state();
    }
    else
    {
		if(isset($_GET['cwmp_order']))
		{
			$cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
			return $cwmp_order_info->get_billing_state();
		}
    }
    
}
add_shortcode('cwmp_order_billing_estado', 'cwmp_order_billing_estado');


function cwmp_order_billing_cep($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
		return $cwmp_order_info->get_billing_postcode();
    }
    else
    {
		if(isset($_GET['cwmp_order']))
		{
			$cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
			return $cwmp_order_info->get_billing_postcode();
		}
    }
    
}
add_shortcode('cwmp_order_billing_cep', 'cwmp_order_billing_cep');

/*END SHIPPING ADDRESS*/

add_shortcode('cwmp_order_shipping_logradouro', 'cwmp_order_billing_logradouro');
add_shortcode('cwmp_order_shipping_numero', 'cwmp_order_billing_numero');
add_shortcode('cwmp_order_shipping_complemento', 'cwmp_order_billing_complemento');
add_shortcode('cwmp_order_shipping_bairro', 'cwmp_order_billing_bairro');
add_shortcode('cwmp_order_shipping_cidade', 'cwmp_order_billing_cidade');
add_shortcode('cwmp_order_shipping_estado', 'cwmp_order_billing_estado');
add_shortcode('cwmp_order_shipping_cep', 'cwmp_order_billing_cep');



function cwmp_code_shipping_link($number_id)
{
    global $wp;
    global $wpdb;
    global $woocommerce;
    global $table_prefix;
    if (isset($number_id['val'])){
        $cwmp_order_info = wc_get_order($number_id['val']);
    }else{
		if(isset($_GET['cwmp_order'])){
        $cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
		}
    }
	if(isset($cwmp_order_info)){
		$get_campanha = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}cwmp_transportadoras WHERE id LIKE %s",
				get_post_meta($cwmp_order_info->get_ID(), '_cwmp_codigo_transportadora_slug', true)
			)
		);
		if(isset($get_campanha[0]->estrutura)){
			return str_replace("{track}", get_post_meta($cwmp_order_info->get_id(), '_cwmp_codigo_rastreio_slug', true),str_replace("{{track}}", get_post_meta($cwmp_order_info->get_id(), '_cwmp_codigo_rastreio_slug', true),$get_campanha[0]->estrutura));
		}
	}
}
add_shortcode('cwmp_code_shipping_link', 'cwmp_code_shipping_link');
function cwmp_code_shipping($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
		return get_post_meta($cwmp_order_info->get_ID() , '_cwmp_codigo_rastreio_slug', true);
    }
    else
    {
		if(isset($_GET['cwmp_order'])){
			$cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
			return get_post_meta($cwmp_order_info->get_ID() , '_cwmp_codigo_rastreio_slug', true);
		}
    }
}
add_shortcode('cwmp_code_shipping', 'cwmp_code_shipping');


function cwmp_shipping_name($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
		$cwmp_order_info = wc_get_order($number_id['val']);
		$order = $cwmp_order_info->get_items( 'shipping' );
		foreach( $order as $item_id => $item ){
			return $item->get_name();
		}
    }
    else
    {
		if(isset($_GET['cwmp_order'])){
			$cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
			$order = $cwmp_order_info->get_items( 'shipping' );
			foreach( $order as $item_id => $item ){
				return $item->get_name();
			}
		}
    }
}
add_shortcode('cwmp_shipping_name', 'cwmp_shipping_name');

function cwmp_shipping_total($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
		$cwmp_order_info = wc_get_order($number_id['val']);
		$order = $cwmp_order_info->get_items( 'shipping' );
		foreach( $order as $item_id => $item ){
			return "R$".$item->get_total();
		}
    }
    else
    {
		if(isset($_GET['cwmp_order'])){
			$cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
			$order = $cwmp_order_info->get_items( 'shipping' );
			foreach( $order as $item_id => $item ){
				return "R$".$item->get_total();
			}
		}
    }
}
add_shortcode('cwmp_shipping_total', 'cwmp_shipping_total');