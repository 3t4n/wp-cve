<?php
/* [cwmp_order_name] */
function cwmp_order_name($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
		if($cwmp_order_info->get_billing_first_name()){
			return $cwmp_order_info->get_billing_first_name();
		}
    }
    else
    {
		if(isset($_GET['cwmp_order']))
		{
			$cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
			if($cwmp_order_info->get_billing_first_name()){
				return $cwmp_order_info->get_billing_first_name();
			}
		}
    }
    
}
add_shortcode('cwmp_order_name', 'cwmp_order_name');

function cwmp_order_lastname($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
		if($cwmp_order_info->get_billing_first_name()){
			return $cwmp_order_info->get_billing_last_name();
		}
    }
    else
    {
		if(isset($_GET['cwmp_order']))
		{
			$cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
			if($cwmp_order_info->get_billing_first_name()){
				return $cwmp_order_info->get_billing_last_name();
			}
		}
    }
    
}
add_shortcode('cwmp_order_lastname', 'cwmp_order_lastname');


/* [cwmp_order_phone] */
function cwmp_order_phone($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
		if($cwmp_order_info->get_billing_phone()){
			return $cwmp_order_info->get_billing_phone();
		}
    }
    else
    {
		if(isset($_GET['cwmp_order']))
		{
			$cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
			if($cwmp_order_info->get_billing_phone()){
				return $cwmp_order_info->get_billing_phone();
			}
		}
    }
}
add_shortcode('cwmp_order_phone', 'cwmp_order_phone');


/* [cwmp_order_cellphone] */
function cwmp_order_cellphone($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
		if(get_post_meta($cwmp_order_info->get_ID() , '_billing_cellphone', true)){
		return get_post_meta($cwmp_order_info->get_ID() , '_billing_cellphone', true);
		}
    }
    else
    {
		if(isset($_GET['cwmp_order']))
		{
			$cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
			if(get_post_meta($cwmp_order_info->get_ID() , '_billing_cellphone', true)){
			return get_post_meta($cwmp_order_info->get_ID() , '_billing_cellphone', true);
			}
		}
    }
    
}
add_shortcode('cwmp_order_cellphone', 'cwmp_order_cellphone');


/* [cwmp_order_email] */
function cwmp_order_email($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
		if($cwmp_order_info->get_billing_email()){
			return $cwmp_order_info->get_billing_email();
		}
    }
    else
    {
		if(isset($_GET['cwmp_order']))
		{
			$cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
			if($cwmp_order_info->get_billing_email()){
				return $cwmp_order_info->get_billing_email();
			}
		}
    }
}
add_shortcode('cwmp_order_email', 'cwmp_order_email');


/* [cwmp_order_cpf] */
function cwmp_order_cpf($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
		return $cwmp_order_info->get_meta( '_billing_cpf' );

    }
    else
    {
		if(isset($_GET['cwmp_order']))
		{
			$cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
			return $cwmp_order_info->get_meta( '_billing_cpf' );
		}
    }
}
add_shortcode('cwmp_order_cpf', 'cwmp_order_cpf');


/* [cwmp_order_cnpj] */
function cwmp_order_cnpj($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
		if(get_post_meta($cwmp_order_info->get_ID() , '_billing_cnpj', true)){
			return get_post_meta($cwmp_order_info->get_ID() , '_billing_cnpj', true);
		}
    }
    else
    {
		if(isset($_GET['cwmp_order']))
		{
			$cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
			if(get_post_meta($cwmp_order_info->get_ID() , '_billing_cnpj', true)){
				return get_post_meta($cwmp_order_info->get_ID() , '_billing_cnpj', true);
			}
		}
    }
}
add_shortcode('cwmp_order_cnpj', 'cwmp_order_cnpj');