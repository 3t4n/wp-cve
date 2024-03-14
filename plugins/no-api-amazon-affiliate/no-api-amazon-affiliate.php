<?php
/**
 * @package no-api-amazon-affiliate
 * @version 4.4.0
 */
/*
Plugin Name: No API Amazon Affiliate
Plugin URI: https://altanic.com/afiliacion-amazon-sin-api-y-gratis/
Description: Plugin de <strong>Afiliados de Amazon, SIN API</strong>. Crea bonitas cajas de productos de Amazon con tu Id de afiliación.
Author: Altanic
Version: 4.4.0
Author URI: https://altanic.com/
Text Domain: no-api-amazon-affiliate
Domain Path: /languages
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// constantes
if (!defined('NAAA_PLUGIN_VERSION'))
    define('NAAA_PLUGIN_VERSION', '4.4.0');
	
define('NAAA_NAME', 'No API Amazon Affiliate');
define('NAAA_SLUG', plugin_basename( __DIR__  ));
define('NAAA_SLUG_ADMIN', NAAA_SLUG.'/admin/');

define('NAAA_PATH', plugin_dir_path( __FILE__ ));
define('NAAA_PATH_ADMIN', NAAA_PATH.'admin/');
define('NAAA_PATH_INC', NAAA_PATH.'includes/');
define('NAAA_PATH_LIB', NAAA_PATH.'includes/lib/');

define('NAAA_URL_JS', plugin_dir_url(__FILE__).'assets/js/');
define('NAAA_URL_CSS', plugin_dir_url(__FILE__).'assets/css/');
define('NAAA_URL_IMG', plugin_dir_url(__FILE__).'assets/images/');

// Import library simple_html_dom, change name functions for protect if its load in other pluging
require_once(NAAA_PATH_LIB . 'simplehtmldom_1_9_1/simple_html_dom.php');

require_once(NAAA_PATH_INC . 'naaa-html.php');
require_once(NAAA_PATH_INC . 'naaa-functions.php');


function naaa_shortcode($atributos) {
	extract(
		shortcode_atts( 
			array(
				'asin' => '',
				'button_text' => esc_attr(get_option('naaa_button_text')),
				'precio_text' => esc_attr(get_option('naaa_precio_text')),
				'market' => esc_attr(get_option('naaa_amazon_country','es')),
				'template' => 'card',
				'heading'  => esc_attr(get_option('naaa_heading_level',0)),
				'bestseller' => '',
				'max' => 999
			),
			$atributos 
		)
	);

	return naaa_get_html_grid($asin, $button_text, $precio_text, $market, $template, $heading, $bestseller, $max);
}
add_shortcode( 'naaa', 'naaa_shortcode' );


//MENU ADMINISTRACION
function naaa_menu(){
	$img_menu = 'data:image/png;base64,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';
	//add_menu_page( NAAA_NAME, NAAA_NAME, 'manage_options', NAAA_PATH_ADMIN.'options.php', null, 'dashicons-amazon', 9999);
	add_menu_page( NAAA_NAME, NAAA_NAME, 'manage_options', NAAA_PATH_ADMIN.'options.php', null, 'div', 9999);
	add_submenu_page( NAAA_PATH_ADMIN.'options.php', __('Ajustes No API Amazon Affiliate', 'no-api-amazon-affiliate'), __('Ajustes', 'no-api-amazon-affiliate'), 'manage_options', NAAA_PATH_ADMIN.'options.php', null);
	add_submenu_page( NAAA_PATH_ADMIN.'options.php', __('Apariencia No API Amazon Affiliate', 'no-api-amazon-affiliate'), __('Apariencia', 'no-api-amazon-affiliate'), 'manage_options', NAAA_PATH_ADMIN.'options2.php', null);
	add_submenu_page( NAAA_PATH_ADMIN.'options.php', __('Productos Amazon', 'no-api-amazon-affiliate'), __('Productos Amazon', 'no-api-amazon-affiliate'), 'manage_options', NAAA_PATH_ADMIN.'items_amazon.php', null);
	add_submenu_page( NAAA_PATH_ADMIN.'options.php', __('Ayuda', 'no-api-amazon-affiliate'), __('Ayuda', 'no-api-amazon-affiliate'), 'manage_options', NAAA_PATH_ADMIN.'ayuda.php', null);	
}
add_action('admin_menu', 'naaa_menu');

function naaa_settings(){
	register_setting( 'naaa-amazon-options', 'naaa_amazon_country', array('type' => 'string', 'default' => 'ES', 'sanitize_callback' => 'sanitize_key'));
	register_setting( 'naaa-amazon-options', 'naaa_amazon_tag_br', array('type' => 'string', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options', 'naaa_amazon_tag_mx', array('type' => 'string', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options', 'naaa_amazon_tag_us', array('type' => 'string', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options', 'naaa_amazon_tag_gb', array('type' => 'string', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options', 'naaa_amazon_tag_es', array('type' => 'string', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options', 'naaa_amazon_tag_jp', array('type' => 'string', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options', 'naaa_amazon_tag_it', array('type' => 'string', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options', 'naaa_amazon_tag_in', array('type' => 'string', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options', 'naaa_amazon_tag_de', array('type' => 'string', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options', 'naaa_amazon_tag_fr', array('type' => 'string', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options', 'naaa_amazon_tag_cn', array('type' => 'string', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options', 'naaa_amazon_tag_ca', array('type' => 'string', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options', 'naaa_time_update', array('type' => 'integer', 'default' => 86400));

	register_setting( 'naaa-amazon-options2', 'naaa_num_items_row', array('type' => 'integer', 'default' => 3, 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_responsive', array('type' => 'integer', 'default' => 1, 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_min_width_gridbox', array('type' => 'integer', 'default' => 145, 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_bg_color', array('type' => 'string', 'default' => '#ffffff', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_border_size', array('type' => 'integer', 'default' => 1, 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_border_color', array('type' => 'string', 'default' => '#dad8d8', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_button_text', array('type' => 'string', 'default' => 'Ver más', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_precio_text', array('type' => 'string', 'default' => '', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_precio_new_show', array('type' => 'integer', 'default' => 1, 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_precio_old_show', array('type' => 'integer', 'default' => 1, 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_heading_level', array('type' => 'integer', 'default' => 0, 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_num_lines_title', array('type' => 'integer', 'default' => 2, 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_button_bg_color', array('type' => 'string', 'default' => '#f7dfa5', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_button_bg_color2_show',  array('type' => 'integer', 'default' => 1, 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_button_bg_color2', array('type' => 'string', 'default' => '#f0c14b', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_product_color_show',  array('type' => 'integer', 'default' => 0, 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_product_color', array('type' => 'string', 'default' => '#a94207', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_button_border_show', array('type' => 'integer', 'default' => 1, 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_button_shadow_show', array('type' => 'integer', 'default' => 1, 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_button_bg_color_shadow', array('type' => 'string', 'default' => '#999', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_button_text_color', array('type' => 'string', 'default' => '#000000', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_valoracion_show', array('type' => 'integer', 'default' => 1, 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_valoracion_desc_show', array('type' => 'integer', 'default' => 0, 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_comentarios_show', array('type' => 'integer', 'default' => 0, 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_comentarios_text', array('type' => 'string', 'default' => '', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_discount_show', array('type' => 'integer', 'default' => 1, 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_discount_bg_color', array('type' => 'string', 'default' => '#d9534f', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_discount_text_color', array('type' => 'string', 'default' => '#ffffff', 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_prime_show', array('type' => 'integer', 'default' => 1, 'sanitize_callback' => 'sanitize_text_field'));
	register_setting( 'naaa-amazon-options2', 'naaa_corner', array('type' => 'integer', 'default' => 5, 'sanitize_callback' => 'sanitize_text_field'));
}
add_action('admin_init', 'naaa_settings');


//LOAD JS y CSS
function naaa_load_var_css() {
	/* Initialize var */
	$naaa_num_items_row = esc_attr(get_option('naaa_num_items_row',3));
	
	//95 algunos temas meten margin y no es posible ajustarlo al 100%
	$naaa_gridbox_width = intdiv(95, $naaa_num_items_row);

	$naaa_num_lines_title = esc_attr(get_option('naaa_num_lines_title',2));
	$naaa_height_title = $naaa_num_lines_title*3;

	$naaa_bg_color = esc_attr(get_option('naaa_bg_color','#ffffff'));
	$naaa_border_size = esc_attr(get_option('naaa_border_size', 1));
	$naaa_border_color = esc_attr(get_option('naaa_border_color', '#dad8d8'));
	
	$naaa_button_bg_color = esc_attr(get_option('naaa_button_bg_color', '#f7dfa5'));
	if (esc_attr(get_option('naaa_button_bg_color2_show',1))){
		$naaa_button_bg_color2 = esc_attr(get_option('naaa_button_bg_color2', '#f0c14b'));
	}else{
		$naaa_button_bg_color2 = esc_attr(get_option('naaa_button_bg_color2', '#f7dfa5'));
	}

	if (esc_attr(get_option('naaa_product_color_show',1))){
		$naaa_product_color = esc_attr(get_option('naaa_product_color', '#a94207'));
	}else{
		$naaa_product_color = '';
	}

	$naaa_button_shadow = '0 6px 8px -4px '.esc_attr(get_option('naaa_button_bg_color_shadow','#999')); 
	if (!esc_attr(get_option('naaa_button_shadow_show', 1))){
		$naaa_button_shadow = 'none';
	}
	
	$naaa_button_text_color = esc_attr(get_option('naaa_button_text_color','#000000'));

	$naaa_discount_bg_color = esc_attr(get_option('naaa_discount_bg_color','#d9534f'));
	$naaa_discount_text_color = esc_attr(get_option('naaa_discount_text_color','#ffffff'));

	$naaa_corner = esc_attr(get_option( 'naaa_corner', 5));
	if ($naaa_corner == 100){
		$naaa_corner .= '%';
	}else{
		$naaa_corner .= 'px';
	}

	$naaa_min_width_gridbox = esc_attr(get_option( 'naaa_min_width_gridbox', 145));

	//TODO: RADIUS FOR CARD PRODUCTO EXAMPLE:border-radius: var(--naaa-corner);

	echo "<style type='text/css'>
	.naaa-gridbox {
		--naaa-bg-color: ".$naaa_bg_color.";
		--naaa-border-size: ".$naaa_border_size."px;
		--naaa-border-color: ".$naaa_border_color.";
		--naaa-gridbox-width: ".$naaa_gridbox_width."%;
		--naaa-num-lines-title: ".$naaa_num_lines_title.";
		--naaa-height-title: ".$naaa_height_title."ex;
		--naaa-button-bg-color: ".$naaa_button_bg_color.";
		--naaa-button-bg-color2: ".$naaa_button_bg_color2.";
		--naaa-product-color: ".$naaa_product_color.";
		--naaa-button-shadow-color: ".$naaa_button_shadow.";
		--naaa-button-text-color: ".$naaa_button_text_color.";
		--naaa-discount-bg-color: ".$naaa_discount_bg_color.";
		--naaa-discount-text-color: ".$naaa_discount_text_color.";
		--naaa-corner: ".$naaa_corner.";
		--naaa-min-width-gridbox: ".$naaa_min_width_gridbox."px;
	}
	.naaa-gridbox-h {
		--naaa-bg-color: ".$naaa_bg_color.";
		--naaa-border-size: ".$naaa_border_size."px;
		--naaa-border-color: ".$naaa_border_color.";
		--naaa-num-lines-title: ".$naaa_num_lines_title.";
		--naaa-height-title: ".$naaa_height_title."ex;
		--naaa-button-bg-color: ".$naaa_button_bg_color.";
		--naaa-button-bg-color2: ".$naaa_button_bg_color2.";
		--naaa-product-color: ".$naaa_product_color.";
		--naaa-button-shadow-color: ".$naaa_button_shadow.";
		--naaa-button-text-color: ".$naaa_button_text_color.";
		--naaa-discount-bg-color: ".$naaa_discount_bg_color.";
		--naaa-discount-text-color: ".$naaa_discount_text_color.";
		--naaa-corner: ".$naaa_corner.";
	}
	</style>";
}

function naaa_css_js($hook){
	//REGISTRAR
	wp_register_style('naaa_css_frontend', NAAA_URL_CSS.'naaa-estilos.css');
	wp_register_style('naaa_css_foot-awesome', NAAA_URL_CSS.'font-awesome.min.css'); //stars

	//ENCOLAR
	naaa_load_var_css();
	wp_enqueue_style('naaa_css_frontend');
	wp_enqueue_style('naaa_css_foot-awesome');

}
add_action( 'wp_enqueue_scripts',  'naaa_css_js');

function naaa_admin_css_js($hook){
	//propios
	wp_register_style('naaa_css_backend', NAAA_URL_CSS.'naaa-estilos-backend.css');
	wp_enqueue_style('naaa_css_backend');

	//Only load in admin items_amazon.php plugin page
	if (strpos($hook, NAAA_SLUG_ADMIN.'items_amazon.php') !== false){
		//bootstrap
		wp_enqueue_style('naaa_bootstrapCSS_5_0_0', NAAA_URL_CSS.'bootstrap.min.css');
		wp_enqueue_script( 'naaa_bootstrapJS_5_0_0',NAAA_URL_JS.'bootstrap.bundle.min.js', array( 'jquery' ),'',true );
		
		wp_enqueue_script('naaa_js_admin_items', NAAA_URL_JS.'admin_items_amazon.js', array( 'jquery' ),'', true );
		wp_localize_script('naaa_js_admin_items', 'ajax_object', [
			'url' => admin_url('admin-ajax.php'),
			'seguridad' => wp_create_nonce( 'seg' )
		]);
	}
	if (strpos($hook, NAAA_SLUG_ADMIN.'options2.php') !== false){
	    wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script('naaa_js_admin_options', NAAA_URL_JS.'admin_options.js', array( 'wp-color-picker' ), false, true );
	}
}
add_action( 'admin_enqueue_scripts',  'naaa_admin_css_js');

//FUNCIONES AJAX
function naaa_update_by_asin(){
	$nonce = $_POST['nonce'];
	if(!wp_verify_nonce( $nonce, 'seg')){
		wp_die('No tiene permisos para ejecutar funcion');
	}

	//Get data and sanitize
	$naaa_asin_item = sanitize_text_field($_POST['asin']);
	$naaa_market = strtolower(sanitize_key($_POST['market']));

	//Valid data
	if (naaa_is_valid_asin_item($naaa_asin_item) && naaa_is_valid_market($naaa_market)){
		//find element info, and save if is new.
		$itemNew = naaa_get_item_data_ws($naaa_asin_item, $naaa_market);
		naaa_force_update($naaa_asin_item, $naaa_market, $itemNew);
		echo $naaa_asin_item.__(' Actualizado.', 'no-api-amazon-affiliate');
	}else{
		echo $naaa_asin_item.__(' NO se puede actualizar.', 'no-api-amazon-affiliate');
	}

	wp_die();
}
add_action('wp_ajax_naaa_update_by_asin', 'naaa_update_by_asin');

function naaa_delete_by_asin(){
	$nonce = $_POST['nonce'];
	if(!wp_verify_nonce( $nonce, 'seg')){
		wp_die('No tiene permisos para ejecutar funcion');
	}

	//Get data and sanitize
	$naaa_asin_item = sanitize_text_field($_POST['asin']);
	$naaa_market = sanitize_key($_POST['market']);

	//Valid data
	if (naaa_is_valid_asin_item($naaa_asin_item) && naaa_is_valid_market($naaa_market)){
		//find element info, and save if is new.
		naaa_delete_item_db($naaa_asin_item, $naaa_market);
		echo $naaa_asin_item.__(' Eliminado.', 'no-api-amazon-affiliate');
	}else{
		echo $naaa_asin_item.__(' NO se puede eliminar.', 'no-api-amazon-affiliate');
	}

	wp_die();
}
add_action('wp_ajax_naaa_delete_by_asin', 'naaa_delete_by_asin');



//FUNCIONES INSTALL UNINSTALL
function naaa_activar(){
	global $wpdb;

	$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}naaa_item_amazon(
	`id_naaa_item_amazon` INT NOT NULL AUTO_INCREMENT,
	`asin` VARCHAR(45) NOT NULL,
	`titulo` VARCHAR(255) NULL,
	`precio` DECIMAL(13,2) NULL,
	`precio_anterior` DECIMAL(13,2) NULL,
	`imagen_url` VARCHAR(255) NULL,
	`valoracion` DECIMAL(3,1) NULL,
	`opiniones` INT NULL,
	`prime` TINYINT(1) NULL,
	`mercado` VARCHAR(4) NULL,
	`fecha_alta` DATETIME NULL,
	`fecha_ultimo_update` DATETIME NULL,
	PRIMARY KEY (`id_naaa_item_amazon`),
	UNIQUE INDEX `id_naaa_item_amazon_UNIQUE` (`id_naaa_item_amazon` ASC),
	UNIQUE INDEX `asin_mercado_UNIQUE` (`asin` ASC, `mercado` ASC));";

	$wpdb->query($sql);

	//update 4.1.0
	$row = $wpdb->get_results("SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '{$wpdb->prefix}naaa_item_amazon' AND column_name = 'titulo_manual'"  );
	if(empty($row)){
   		$wpdb->query("ALTER TABLE {$wpdb->prefix}naaa_item_amazon ADD titulo_manual VARCHAR(255) NULL");
	}

	//update 4.2.0
	$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}naaa_bestselleer_amazon(
		`id_naaa_bestseller_amazon` INT NOT NULL AUTO_INCREMENT,
		`bestseller_hash` VARCHAR(64) NOT NULL,
		`bestseller_text` VARCHAR(512) NULL,
		`mercado` VARCHAR(4) NULL,
		`asin_list` VARCHAR(512) NULL,
		`fecha_alta` DATETIME NULL,
		`fecha_ultimo_update` DATETIME NULL,
		PRIMARY KEY (`id_naaa_bestseller_amazon`),
		UNIQUE INDEX `id_naaa_bestseller_amazon_UNIQUE` (`id_naaa_bestseller_amazon` ASC),
		UNIQUE INDEX `bestseller_hash_UNIQUE` (`bestseller_hash` ASC));";
	$wpdb->query($sql);

	//update 4.3.0
	$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}naaa_other_link(
		`id_naaa_other_link` INT NOT NULL AUTO_INCREMENT,
		`fk_naaa_item_amazon` INT NOT NULL,
		`other_affiliate_link` VARCHAR(2000) NOT NULL,
		`other_affiliate_button` INT NOT NULL,
		`fecha_alta` DATETIME NULL,
		PRIMARY KEY (`id_naaa_other_link`),
		UNIQUE INDEX `id_naaa_other_link_UNIQUE` (`id_naaa_other_link` ASC));";
	$wpdb->query($sql);

	//update 4.4.0
	$row = $wpdb->get_results("SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '{$wpdb->prefix}naaa_item_amazon' AND column_name = 'alt_manual'"  );
	if(empty($row)){
			$wpdb->query("ALTER TABLE {$wpdb->prefix}naaa_item_amazon ADD alt_manual VARCHAR(255) NULL");
	}

	//Load last version in db
	update_option('naaa_plugin_version', NAAA_PLUGIN_VERSION);
	

}
register_activation_hook( __FILE__, 'naaa_activar' );


function naaa_desactivar(){

}
register_deactivation_hook( __FILE__, 'naaa_desactivar' );

function naaa_check_last_version() {
	if (NAAA_PLUGIN_VERSION !== esc_attr(get_option('naaa_plugin_version')))
		naaa_activar();
}
add_action('plugins_loaded', 'naaa_check_last_version');

