<?php

if(!defined('WP_UNINSTALL_PLUGIN')){
    die();
}

global $wpdb;
$table_name = $wpdb->prefix . 'naaa_item_amazon';
$wpdb->query( "DROP TABLE IF EXISTS $table_name" );

$table_name = $wpdb->prefix . 'naaa_bestselleer_amazon';
$wpdb->query( "DROP TABLE IF EXISTS $table_name" );

delete_option('naaa_amazon_country');
delete_option('naaa_amazon_tag_br');
delete_option('naaa_amazon_tag_mx');
delete_option('naaa_amazon_tag_us');
delete_option('naaa_amazon_tag_gb');
delete_option('naaa_amazon_tag_es');
delete_option('naaa_amazon_tag_jp');
delete_option('naaa_amazon_tag_it');
delete_option('naaa_amazon_tag_in');
delete_option('naaa_amazon_tag_de');
delete_option('naaa_amazon_tag_fr');
delete_option('naaa_amazon_tag_cn');
delete_option('naaa_amazon_tag_ca');
delete_option('naaa_time_update');

delete_option('naaa_num_items_row');
delete_option('naaa_responsive');
delete_option('naaa_min_width_gridbox');
delete_option('naaa_bg_color');
delete_option('naaa_border_size');
delete_option('naaa_border_color');
delete_option('naaa_button_text');
delete_option('naaa_precio_text');
delete_option('naaa_precio_new_show');
delete_option('naaa_precio_old_show');
delete_option('naaa_heading_level');
delete_option('naaa_num_lines_title');
delete_option('naaa_button_bg_color');
delete_option('naaa_button_bg_color2_show');
delete_option('naaa_button_bg_color2');
delete_option('naaa_product_color_show');
delete_option('naaa_product_color');
delete_option('naaa_button_border_show');
delete_option('naaa_button_shadow_show');
delete_option('naaa_button_bg_color_shadow');
delete_option('naaa_button_text_color');
delete_option('naaa_valoracion_show');
delete_option('naaa_valoracion_desc_show');
delete_option('naaa_comentarios_show');
delete_option('naaa_comentarios_text');
delete_option('naaa_discount_show');
delete_option('naaa_discount_bg_color');
delete_option('naaa_discount_text_color');
delete_option('naaa_prime_show');
delete_option('naaa_corner');