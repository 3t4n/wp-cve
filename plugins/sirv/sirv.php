<?php

/**
 * Plugin Name: Sirv
 * Plugin URI: http://sirv.com
 * Description: Fully-automatic image optimization, next-gen formats (WebP), responsive resizing, lazy loading and CDN delivery. Every best-practice your website needs. Use "Add Sirv Media" button to embed images, galleries, zooms, 360 spins and streaming videos in posts / pages. Stunning media viewer for WooCommerce. Watermarks, text titles... every WordPress site deserves this plugin! <a href="admin.php?page=sirv/data/options.php">Settings</a>
 * Version:           7.2.4
 * Requires PHP:      5.6
 * Requires at least: 3.0.1
 * Author:            sirv.com
 * Author URI:        sirv.com
 * License:           GPLv2
 */

defined('ABSPATH') or die('No script kiddies please!');


define('SIRV_PLUGIN_VERSION', '7.2.4');
define('SIRV_PLUGIN_DIR', 'sirv');
define('SIRV_PLUGIN_SUBDIR', 'plugdata');
/// var/www/html/wordpress/wp-content/plugins/sirv/
define('SIRV_PLUGIN_PATH', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . SIRV_PLUGIN_DIR . DIRECTORY_SEPARATOR);
// /var/www/html/wordpress/wp-content/plugins/sirv/plugdata/
define('SIRV_PLUGIN_SUBDIR_PATH', SIRV_PLUGIN_PATH . SIRV_PLUGIN_SUBDIR . DIRECTORY_SEPARATOR);
// sirv/plugdata/
define('SIRV_PLUGIN_RELATIVE_SUBDIR_PATH', SIRV_PLUGIN_DIR . DIRECTORY_SEPARATOR . SIRV_PLUGIN_SUBDIR . DIRECTORY_SEPARATOR);
// http://localhost:8080/wordpress/wp-content/plugins/sirv/
define('SIRV_PLUGIN_DIR_URL_PATH', plugins_url() . DIRECTORY_SEPARATOR . SIRV_PLUGIN_DIR . DIRECTORY_SEPARATOR);
// http://localhost:8080/wordpress/wp-content/plugins/sirv/plugdata/
define('SIRV_PLUGIN_SUBDIR_URL_PATH', SIRV_PLUGIN_DIR_URL_PATH . SIRV_PLUGIN_SUBDIR . DIRECTORY_SEPARATOR);
define("IS_DEBUG", false);

require_once(SIRV_PLUGIN_SUBDIR_PATH . 'includes/classes/utils.class.php');
require_once(SIRV_PLUGIN_SUBDIR_PATH . 'includes/classes/error.class.php');
require_once(SIRV_PLUGIN_SUBDIR_PATH . 'includes/classes/woo.class.php');
require_once(SIRV_PLUGIN_SUBDIR_PATH . 'includes/classes/wc.product.helper.class.php');
require_once(SIRV_PLUGIN_SUBDIR_PATH . 'includes/classes/options-service.class.php');
require_once(SIRV_PLUGIN_SUBDIR_PATH . 'includes/classes/exclude.class.php');
require_once(SIRV_PLUGIN_SUBDIR_PATH . 'includes/classes/resize.class.php');
require_once(SIRV_PLUGIN_SUBDIR_PATH . 'includes/classes/logger.class.php');
require_once(SIRV_PLUGIN_SUBDIR_PATH . 'shortcodes.php');

global $APIClient;
global $foldersData;
global $syncData;
global $pathsData;
global $isLocalHost;
global $isLoggedInAccount;
global $isAdmin;
global $isFetchUpload;
global $isFetchUrl;
global $base_prefix;
global $pagenow;
global $sirv_woo_is_enable;
global $sirv_woo_cat_is_enable;
global $sirv_cdn_url;
global $isAjax;
global $profiles;
global $logger;
global $sirv_ob_lvl;
global $sirv_is_rest_rejected;
global $overheadLimit;

$logger = new SirvLogger(SIRV_PLUGIN_PATH, ABSPATH);
$APIClient = false;
$syncData = array();
$pathsData = array();
$isLocalHost = sirv_is_local_host();
$isLoggedInAccount = (get_option('SIRV_ACCOUNT_NAME') !== '' && get_option('SIRV_CDN_URL') !== '') ? true : false;
$isAdmin = sirv_isAdmin();
$isFetchUpload = true;
$isFetchUrl = false;
$base_prefix = sirv_get_base_prefix();
$isAjax = false;
$sirv_ob_lvl = -1;
$sirv_is_rest_rejected = false;
$overheadLimit = 5000;


//add_action( 'wp_head', 'get_enqueued_scripts', 1000 );
function get_enqueued_scripts(){
  $scripts = wp_scripts();
  var_dump(array_keys($scripts->groups));
}

//add_action('wp_enqueue_scripts', 'tstss', PHP_INT_MAX - 100);
function tstss(){
  $scripts = wp_scripts();
  sirv_debug_msg($scripts->queue);
}

add_action('admin_head', 'sirv_global_logo_fix');
function sirv_global_logo_fix(){
  echo '
  <style>
    a[href*="page='. SIRV_PLUGIN_RELATIVE_SUBDIR_PATH .'options.php"] img {
      padding-top:7px !important;
    }
  </style>';
}


/*---------------------------------WooCommerce--------------------------------*/
$sirv_woo_is_enable_option = get_option('SIRV_WOO_IS_ENABLE');
$sirv_woo_cat_is_enable_option = get_option('SIRV_WOO_CAT_IS_ENABLE');
$sirv_woo_is_enable = !empty($sirv_woo_is_enable_option) && $sirv_woo_is_enable_option == '2' ? true : false;
$sirv_woo_cat_is_enable = !empty($sirv_woo_cat_is_enable_option) && $sirv_woo_cat_is_enable_option == 'enabled' ? true : false;

if (in_array($pagenow, array('post-new.php', 'post.php'))) {
  $woo = new Woo;
}


add_filter('upload_mimes', 'sirv_mime_types', 10, 1);
function sirv_mime_types($mimes){
  $mimes['svg']  = 'image/svg+xml';
  $mimes['spin']  = 'sirv/spin';

  return $mimes;
}


add_action('woocommerce_init', 'sirv_wc_init');
function sirv_wc_init(){
  global $sirv_woo_is_enable;
  global $sirv_woo_cat_is_enable;

  if(get_option('SIRV_WOO_SHOW_SIRV_GALLERY') == 'show'){
    add_action('woocommerce_product_after_variable_attributes', array('Woo', 'render_variation_gallery'), 10, 3);
    add_action('woocommerce_save_product_variation', array('Woo', 'save_sirv_variation_data'), 10, 2);
  }

  if ( $sirv_woo_is_enable ) {
    //remove filter that conflict with sirv
    remove_filter('wc_get_template', 'wvg_gallery_template_override', 30, 2);
    remove_filter('wc_get_template_part', 'wvg_gallery_template_part_override', 30, 2);

    //disable blocksy product image gallery
    add_filter('blocksy:woocommerce:product-view:use-default', 'sirv_disable_blocksy_product_gallery');

    add_filter('wc_get_template_part', 'sirv_woo_template_part_override', 30, 3);
    add_filter('wc_get_template', 'sirv_woo_template_override', 30, 3);

    add_filter('get_attached_file', 'sirv_replace_attached_file', 10, 2);
    add_filter('woocommerce_product_get_image', 'set_sirv_product_image', 10, 5);

    //add_filter('post_thumbnail_html', 'sirv_post_thumbnail_html', 10, 5);

    //cart& mini cart filter
    add_filter('woocommerce_cart_item_thumbnail', 'sirv_woocommerce_cart_item_thumbnail_filter', 10, 3);
    //email order
    add_filter('woocommerce_order_item_thumbnail', 'sirv_woocommerce_order_item_thumbnail', 10, 2);

    //ajax mini cart
    //add_filter('woocommerce_add_to_cart_fragments', 'sirv_woocommerce_add_to_cart_fragments');


    // (optional) Force display item image on emails
    /* add_filter('woocommerce_email_order_items_args', 'sirv_show_image_on_email_notifications');
    function sirv_show_image_on_email_notifications($args){
      $args['show_image'] = true;

      return $args;
    } */


    add_filter('posts_where', 'sirv_query_attachments');

    if( $sirv_woo_cat_is_enable ){
      remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
      add_action('woocommerce_before_shop_loop_item_title', 'sirv_woocommerce_template_loop_product_thumbnail_override', 10);

      //fix for jet-woo-builder widget for elementor
      add_filter('jet-woo-builder/template-functions/placeholder-thumbnail', 'sirv_jet_woo_builder_cats_html', 10, 6);
      add_filter('jet-woo-builder/template-functions/product-thumbnail', 'sirv_jet_woo_builder_cats_html', 10, 6);
    }
  }
}


function sirv_jet_woo_builder_cats_html($thumb_html, $image_size, $use_thumb_effect, $attr, $jetFunctionsClass){
  global $product;

  if (!is_a($product, 'WC_Product')) {
    return $thumb_html;
  }

  $woo = new Woo($product->get_id());

  $thumb_html = $woo->get_woo_cat_gallery_html();

  return $thumb_html;
}


function sirv_query_attachments($where){
  global $wpdb;
  if ( (isset($_POST['action']) && ($_POST['action'] == 'query-attachments' || $_POST['action'] == 'get-attachment')) ){
    $where .= ' AND ' . $wpdb->prefix . 'posts.post_author <> ' . SirvProdImageHelper::SIRV_AUTHOR . ' ';
  }

  return $where;
}


function sirv_post_thumbnail_html($html, $post_id, $post_thumbnail_id, $size, $attr = null){

  sirv_debug_msg($html);

  return $html;

}


function sirv_replace_attached_file($file, $attachment_id){
  if (!$attachment_id) return $file;

  if (!metadata_exists('post', $attachment_id, 'sirv_woo_product_image_attachment')) return $file;

  $sirv_prod_image = get_post_meta($attachment_id, 'sirv_woo_product_image_attachment', true);

  if (empty($sirv_prod_image)) return $file;

  return $sirv_prod_image;
}


function sirv_disable_blocksy_product_gallery($current_value) {
  return true;
}


function sirv_woocommerce_order_item_thumbnail($image_html, $item){
  $product_id = $item->get_product_id();
  $variaition_id = $item->get_variation_id();

  return sirv_variation_image_html($image_html, $product_id, $variaition_id);
}


function sirv_woocommerce_cart_item_thumbnail_filter($image_html, $cart_item, $cart_item_key){
  $product_id = $cart_item['product_id'];
  $variaition_id = $cart_item['variation_id'];


  return sirv_variation_image_html($image_html, $product_id, $variaition_id);
}


//TODO: Add posibillity to get size from html
function sirv_variation_image_html($image_html, $product_id, $variaition_id){
  $modified_image_html = $image_html;
  $sirv_url = '';

  if ($variaition_id > 0) {
    $woo = new Woo($product_id);

    $wc_variation = $woo->parse_wc_variation($variaition_id, $product_id, true);

    if (!empty($wc_variation)) {
      if ($wc_variation[0]->provider == 'sirv') {
        $sirv_url = $wc_variation[0]->url;
        $sirv_parametrized_url = sirv_get_parametrized_url($sirv_url, array(250, 250), true);
        $modified_image_html = preg_replace('/(src=".*?")/', "src=\"$sirv_parametrized_url\"", $image_html);
      } else {
        return $image_html;
      }
    } else {
      $variation_data = $woo->get_variation_data($variaition_id);

      foreach ($variation_data as $variation_item) {
        if ($variation_item->type === 'model') continue;

        $sirv_url = $variation_item->url;
        break;
      }

      if (!empty($sirv_url)) {
        $sirv_parametrized_url = sirv_get_parametrized_url($sirv_url, array(250, 250), true);
        $modified_image_html = preg_replace('/(src=".*?")/', "src=\"$sirv_parametrized_url\"", $image_html);
      }
    }
  }

  return $modified_image_html;
}


  function set_sirv_product_image($imageHTML, $product=null, $size=null, $attr=null, $placeholder=null){
    global $post;

    if( !isset($post->ID) ) return $imageHTML;

    $sirv_item_url = Woo::get_post_sirv_data($post->ID, 'sirv_woo_product_image', false, false);
    //TODO: add correct srcset
    if( ! empty($sirv_item_url) ){

      $size = null;

      if( is_null($size) ) {
        preg_match_all('/\s(\w*)=\"([^"]*)\"/ims', $imageHTML, $matches_img_attrs, PREG_SET_ORDER);
        $img_attrs = sirv_convert_matches_to_assoc_array($matches_img_attrs);

        if( isset($img_attrs['width']) && isset($img_attrs['height']) ) {
          $size = array($img_attrs['width'], $img_attrs['height']);
        }
      }

      $scaled_url = sirv_get_parametrized_url($sirv_item_url, $size);
      $url_with_profile = sirv_get_parametrized_url($sirv_item_url);
      $patterns = array(
        '/(src=".*?")/',
        '/(data-src=".*?")/',
        '/(srcset=".*?")/',
        '/(sizes=".*?")/'
      );
      $replacments = array(
        "src=\"{$scaled_url}\"",
        "data-src=\"{$url_with_profile}\"",
        '',
        ''
      );
      $imageHTML = preg_replace($patterns, $replacments, $imageHTML);
    }

    return $imageHTML;
  }


  function sirv_get_scale_pattern_from_wp_size($sirv_url, $wp_size){
    $get_param_symbol = (stripos($sirv_url, '?') === false) ? '?' : '&';

    if( is_string($wp_size) && $wp_size == 'full' ) return sirv_add_profile($sirv_url);

    $image = sirv_get_correct_item_size($wp_size);

    if( $image['error']) return sirv_add_profile($sirv_url);

    $url = $sirv_url . sirv_get_scale_pattern($image['width'], $image['height'], $image['cropType'], "",  $get_param_symbol);

    return sirv_add_profile($url);
  }

/*
* $wp_size array: array(1024, 768) or string: wp_thumbnail
*/
  function sirv_get_correct_item_size($wp_size, $isCrop=false){
    $size_data = array(
      "width" => 0,
      "height" => 0,
      "cropType" => 'none',
      "error" => true,
    );

    if( is_null($wp_size) ) return $size_data;

    $sizes = sirv_get_image_sizes(false);

    if( is_array($wp_size)){
      if($wp_size[0] == 0 && $wp_size[1] == 0) return $size_data;

      if( !$isCrop && $wp_size[0] === $wp_size[1] ){
        $isCrop = true;
      }

      $size_data['width'] = $wp_size[0];
      $size_data['height'] = $wp_size[1];
      $size_data['cropType'] = sirv_get_crop_type($wp_size, $sizes, (bool) $isCrop);
      $size_data['error'] = false;
    }else{
      if (!empty($sizes)  && in_array($wp_size, array_keys($sizes))) {
        $size_data['width'] = $sizes[$wp_size]['width'];
        $size_data['height'] = $sizes[$wp_size]['height'];

        $isCrop = isset($sizes[$wp_size]['crop']) ? (bool) $sizes[$wp_size]['crop'] : false;

        $size_data['cropType'] = sirv_get_crop_type($wp_size, $sizes, $isCrop);
        $size_data['error'] = false;
      } else {
        return $size_data;
      }
    }

    return $size_data;
  }


function sirv_woocommerce_template_loop_product_thumbnail_override(){
  require(SIRV_PLUGIN_SUBDIR_PATH . 'woo_templates/woo-category-template.php');
}


function sirv_woo_template_part_override($template, $slug, $name){
  global $post;

  if(isset($post->post_type) && $post->post_type !== 'product') return $template;

  $path = '';
  if ($slug == 'single-product/product-image') {
    $path = untrailingslashit(SIRV_PLUGIN_SUBDIR_PATH . 'woo_templates/woo-product-template.php');
  }

  if($slug == 'content' && $name == 'product'){
    //$path = untrailingslashit(SIRV_PLUGIN_SUBDIR_PATH . 'woo_templates/woo-category-template.php';
  }

  return file_exists($path) ? $path : $template;
}


function sirv_woo_template_override($template, $template_name, $template_path){
  global $post;

  if(isset($post->post_type) && $post->post_type !== 'product') return $template;

  $path = '';

  if ($template_name == 'single-product/product-image.php') {
    $path = untrailingslashit(SIRV_PLUGIN_SUBDIR_PATH . 'woo_templates/woo-product-template.php');
  }

  /* if ( $template_name == 'single-product/product-thumbnails.php' ) {
    $path = untrailingslashit(SIRV_PLUGIN_SUBDIR_PATH . 'woo-template-thumbs.php';
  } */

  return file_exists($path) ? $path : $template;
}
/*-------------------------------WooCommerce END--------------------------------*/

/*-------------------------------Fusion Builder---------------------------------*/
add_action('fusion_builder_before_init', 'sirv_avada_element');
function sirv_avada_element()
{
  if( function_exists('fusion_builder_map') ){
    fusion_builder_map(
      array(
        'name'            => esc_attr__('Sirv shortcode', 'sirv-shortcode-element'),
        'shortcode'       => 'sirv-gallery',
        'icon'            => 'fusiona-images',
        //'preview'         => PLUGIN_DIR . 'js/previews/fusion-text-preview.php',
        //'preview_id'      => 'sirv-test-element',
        'allow_generator' => true,
        'params'          => array(
          array(
            'type'        => 'textfield',
            'heading'     => esc_attr__('Shortcode ID', 'sirv-shortcode-element'),
            'description' => __('Enter Sirv shortcode ID.<br><a target="blank" href="admin.php?page=' . SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'shortcodes-view.php">Browse or create Sirv shortcodes <i class="fusion-module-icon fusiona-external-link"></i></a>', 'sirv-shortcode-element'),
            'param_name'  => 'id',
            'value'       => '',
          ),
        ),
      )
    );
  }
}
/*---------------------------Fusion Builder END---------------------------------*/

/* -------------------------------DIVI theme fix------------------------------ */
function sirv_fix_et_disable_emojis_dns_prefetch($urls, $relation_type){
    if ('dns-prefetch' === $relation_type) {
      $emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
      foreach ($urls as $key => $url) {
        if ( is_array( $url ) ) {
          if ( isset( $url['href'] ) ) {
            if ( strpos( $url['href'], $emoji_svg_url_bit ) !== false ) {
              unset( $urls[ $key ]['href'] );
            }
          } else {
            continue;
          }
        }else{
          if ( strpos( $url, $emoji_svg_url_bit ) !== false ) {
            unset( $urls[ $key ] );
          }
        }
      }
    }

    return $urls;
}

/* ------------------------------DIVI theme fix END--------------------------- */


function sirv_is_local_host(){
  $is_remote_addr = sirv_is_remote_addr();
  $is_server_name = false;
  $is_preg_match_url = false;

  /* if(isset($_SERVER['REMOTE_ADDR'])){
    $is_remote_addr =  in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'));
  } */

  if(isset($_SERVER['SERVER_NAME'])){
    $is_server_name = $_SERVER['SERVER_NAME'] == 'localhost';
  }

  if(!empty(get_site_url())){
    $is_preg_match_url = preg_match('/\/\/(localhost|127.0.0.1)/ims', get_site_url());
  }
  //return (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')) || $_SERVER['SERVER_NAME'] == 'localhost' || preg_match('/\/\/(localhost|127.0.0.1)/ims', get_site_url()));

  return ($is_remote_addr || $is_server_name || $is_preg_match_url);
}


function sirv_is_remote_addr(){
  $ip = false;
  if (isset($_SERVER['HTTP_X_REAL_IP'])){
    $ip = $_SERVER['HTTP_X_REAL_IP'];
  } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } elseif(isset($_SERVER['REMOTE_ADDR'])) {
    $ip = $_SERVER['REMOTE_ADDR'];
  }

  if($ip){
    return in_array($ip, array('127.0.0.1', '::1'));
  }

  return $ip;
}


function sirv_isAdmin(){
  $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

  return sirv_is_admin_url($request_uri);
}


function sirv_is_admin_url($url){
    $pattern = '/wp-admin/';

    return (bool) preg_match($pattern, $url);
}


function sirv_debug_msg($msg, $isBoolVar = false){
  $path = realpath(dirname(__FILE__));
  $fn = fopen($path . DIRECTORY_SEPARATOR . 'debug.txt', 'a+');
  //fwrite( $fn, print_r(debug_backtrace(), true) . PHP_EOL);
  if (is_array($msg)) {
    fwrite($fn, print_r($msg, true) . PHP_EOL);
  } else if (is_object($msg)) {
    fwrite($fn, print_r(json_decode(json_encode($msg), true), true) . PHP_EOL);
  } else {
    if ($isBoolVar) {
      $data = var_export($msg, true);
      fwrite($fn, $data . PHP_EOL);
    } else {
      fwrite($fn, $msg . PHP_EOL);
    }
  }

  fclose($fn);
}


function sirv_qdebug($debug_msg, $var_name = "", $mode = 'a+'){
  global $logger;

  $logger->qdebug($debug_msg, $var_name, $mode, 3);
}


function sirv_get_base_prefix(){
  global $wpdb;

  $prefix = $wpdb->prefix;

  if (is_multisite()) $prefix = $wpdb->get_blog_prefix(0);

  return $prefix;
}


add_action('wp_insert_site', 'sirv_added_new_blog', 10);

function sirv_added_new_blog($new_site){
  global $wpdb;

  if (!function_exists('is_plugin_active_for_network')) {
    require_once(ABSPATH . '/wp-admin/includes/plugin.php');
  }

  if (is_plugin_active_for_network( SIRV_PLUGIN_DIR . '/sirv.php')) {
    $current_blog = $wpdb->blogid;
    switch_to_blog($new_site->blog_id);

    sirv_create_plugin_tables();
    sirv_update_options();

    switch_to_blog($current_blog);
  }
}


//create shortcode's table on plugin activate
register_activation_hook(__FILE__, 'sirv_activation_callback');

function sirv_activation_callback($networkwide){
  sirv_register_settings();

  if (function_exists('is_multisite') && is_multisite()) {
    if ($networkwide) {
      update_site_option('SIRV_WP_NETWORK_WIDE', '1');
      global $wpdb;
      $current_blog = $wpdb->blogid;
      $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
      foreach ($blogids as $blog_id) {
        switch_to_blog($blog_id);
        sirv_create_plugin_tables();
      }
      switch_to_blog($current_blog);
    } else {
      update_site_option('SIRV_WP_NETWORK_WIDE', '');
      sirv_create_plugin_tables();
    }
  } else {
    sirv_create_plugin_tables();
  }

  set_transient('isSirvActivated', true, 30);
  //migrations
  sirv_upgrade_plugin();
  sirv_congrat_notice();
}


add_action('plugins_loaded', 'sirv_upgrade_plugin');
function sirv_upgrade_plugin(){
  $sirv_plugin_version_installed = get_option('SIRV_VERSION_PLUGIN_INSTALLED');


  if (empty($sirv_plugin_version_installed) || $sirv_plugin_version_installed != SIRV_PLUGIN_VERSION) {

    //4.1.1
    if (function_exists('is_multisite') && is_multisite()) {
      if (get_site_option('SIRV_WP_NETWORK_WIDE')) {
        global $wpdb;
        $current_blog = $wpdb->blogid;
        $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
        foreach ($blogids as $blog_id) {
          switch_to_blog($blog_id);

          sirv_update_options();
          update_option('SIRV_VERSION_PLUGIN_INSTALLED', SIRV_PLUGIN_VERSION);
        }
        switch_to_blog($current_blog);
      } else {
        sirv_update_options();
        update_option('SIRV_VERSION_PLUGIN_INSTALLED', SIRV_PLUGIN_VERSION);
      }
    } else {
      sirv_update_options();
      update_option('SIRV_VERSION_PLUGIN_INSTALLED', SIRV_PLUGIN_VERSION);
    }

    global $base_prefix;
    global $wpdb;

    $shortcodes_t = $base_prefix . 'sirv_shortcodes';

    $t_structure = $wpdb->get_results("DESCRIBE $shortcodes_t", ARRAY_A);
    $t_fields = sirv_get_field_names($t_structure);

    if (!in_array('shortcode_options', $t_fields)) {
      $wpdb->query("ALTER TABLE $shortcodes_t ADD COLUMN shortcode_options TEXT NOT NULL after images");
    }

    if (!in_array('timestamp', $t_fields)) {
      $wpdb->query("ALTER TABLE $shortcodes_t ADD COLUMN timestamp DATETIME NULL DEFAULT NULL AFTER shortcode_options");
    }

    if (!sirv_is_unique_field('attachment_id')) {
      sirv_set_unique_field('attachment_id');
    }

    sirv_fix_db();

    //5.0
    require_once(SIRV_PLUGIN_SUBDIR_PATH . 'includes/classes/options/options.helper.class.php');
    OptionsHelper::prepareOptionsData();
    OptionsHelper::register_settings();


    //5.7.1
    sirv_remove_autoload();

    //6.5.0
    if (empty(get_option('SIRV_USE_SIRV_RESPONSIVE'))) update_option('SIRV_USE_SIRV_RESPONSIVE', '2');


    if(SIRV_PLUGIN_VERSION == '7.1.0'){
      //SIRV_JS_MODULES
      $js_modules = get_option('SIRV_JS_MODULES');
      $js_modules_arr = explode(',', $js_modules);
      if(! in_array('gallery', $js_modules_arr)){
        $js_modules_arr[] = 'gallery';
      }

      if (!in_array('model', $js_modules_arr)) {
        $js_modules_arr[] = 'model';
      }

      update_option('SIRV_JS_MODULES', implode(',', $js_modules_arr));

      $pins_json = get_option('SIRV_WOO_PIN');
      $encoded_pins = json_decode($pins_json, true);
      $encoded_pins['model'] = 'no';

      update_option('SIRV_WOO_PIN', json_encode($encoded_pins));
    }

    if(SIRV_PLUGIN_VERSION == '7.1.0'){
      if(get_option('SIRV_ENABLE_CDN') == '2'){
        update_option('SIRV_PARSE_STATIC_IMAGES', '2');
        update_option('SIRV_PARSE_VIDEOS', 'off');
      }
    }

    //if (SIRV_PLUGIN_VERSION == '7.0.3'){
      $placeholder_type = get_option('SIRV_RESPONSIVE_PLACEHOLDER');
      $new_type = "image";
      switch ($placeholder_type) {
        case '1':
          $new_type = "blurred";
          break;
        case '2':
          $new_type = "grey_shape";
          break;
        case '3':
          $new_type = "image";
          break;
        default:
          $new_type = "image";
          break;
      }

      update_option('SIRV_RESPONSIVE_PLACEHOLDER', $new_type);
    //}

  }
}


function sirv_remove_autoload(){
//SIRV_CLIENT_ID SIRV_CLIENT_SECRET SIRV_TOKEN SIRV_TOKEN_EXPIRE_TIME SIRV_MUTE SIRV_STAT SIRV_FOLDERS_DATA
$client_id = get_option('SIRV_CLIENT_ID');
$client_secret = get_option('SIRV_CLIENT_SECRET');
$token = get_option('SIRV_TOKEN');
$token_expire_time = get_option('SIRV_TOKEN_EXPIRE_TIME');
$mute = get_option('SIRV_MUTE');
$stat = get_option('SIRV_STAT');
$folders_data = get_option('SIRV_FOLDERS_DATA');

update_option('SIRV_CLIENT_ID', $client_id, 'no');
update_option('SIRV_CLIENT_SECRET', $client_secret, 'no');
update_option('SIRV_TOKEN', $token, 'no');
update_option('SIRV_TOKEN_EXPIRE_TIME', $token_expire_time, 'no');
update_option('SIRV_MUTE', $mute, 'no');
update_option('SIRV_STAT', $stat, 'no');
update_option('SIRV_FOLDERS_DATA', $folders_data, 'no');
}


function sirv_get_default_crop(){
  $crop_data = array();
  $wp_sizes = sirv_get_image_sizes(false);

  ksort($wp_sizes);

  foreach ($wp_sizes as $size_name => $size) {
    $cropMethod = (bool) $size['crop'] ? 'wp_crop' : 'none';
    $crop_data[$size_name] = $cropMethod;
  }

  return json_encode($crop_data, ENT_QUOTES);
}


function sirv_get_default_prevent_thumbs(){
  $prevent_sizes = array();
  $wp_sizes = sirv_get_image_sizes(false);

  ksort($wp_sizes);

  foreach ($wp_sizes as $size_name => $size) {
    $prevent_sizes[$size_name] = array('size' => $size, 'status' => '0');
  }

  return json_encode($prevent_sizes, ENT_QUOTES);
}



function sirv_update_options(){
  if (get_option('WP_USE_SIRV_CDN') && !get_option('SIRV_ENABLE_CDN')) update_option('SIRV_ENABLE_CDN', get_option('WP_USE_SIRV_CDN'));
  if (get_option('WP_SIRV_SHORTCODES_PROFILES') && !get_option('SIRV_SHORTCODES_PROFILES')) update_option('SIRV_SHORTCODES_PROFILES', get_option('WP_SIRV_SHORTCODES_PROFILES'));
  if (get_option('WP_SIRV_CDN_PROFILES') && !get_option('SIRV_CDN_PROFILES')) update_option('SIRV_CDN_PROFILES', get_option('WP_SIRV_CDN_PROFILES'));
  if (get_option('WP_USE_SIRV_RESPONSIVE') && !get_option('SIRV_USE_SIRV_RESPONSIVE')) update_option('SIRV_USE_SIRV_RESPONSIVE', get_option('WP_USE_SIRV_RESPONSIVE'));
  if (get_option('WP_SIRV_JS') && !get_option('SIRV_JS')) update_option('SIRV_JS', get_option('WP_SIRV_JS'));
  if (get_option('WP_FOLDER_ON_SIRV')) {
    update_option('SIRV_FOLDER', get_option('WP_FOLDER_ON_SIRV'));
    delete_option('WP_FOLDER_ON_SIRV');
  }

  sirv_fill_empty_options();
}


function sirv_fill_empty_options(){
  if (!get_option('SIRV_CLIENT_ID')) update_option('SIRV_CLIENT_ID', '', 'no');
  if (!get_option('SIRV_CLIENT_SECRET')) update_option('SIRV_CLIENT_SECRET', '', 'no');
  if (!get_option('SIRV_TOKEN')) update_option('SIRV_TOKEN', '', 'no');
  if (!get_option('SIRV_TOKEN_EXPIRE_TIME')) update_option('SIRV_TOKEN_EXPIRE_TIME', '', 'no');
  if (!get_option('SIRV_MUTE')) update_option('SIRV_MUTE', '', 'no');
  if (!get_option('SIRV_MUTE_ERROR_MESSAGE')) update_option('SIRV_MUTE_ERROR_MESSAGE', '', 'no');
  if (!get_option('SIRV_ACCOUNT_EMAIL')) update_option('SIRV_ACCOUNT_EMAIL', '');
  if (!get_option('SIRV_ACCOUNT_NAME')){
    if( !empty(get_option("SIRV_AWS_BUCKET")) ){
      update_option('SIRV_ACCOUNT_NAME', get_option("SIRV_AWS_BUCKET"));
    }else{
      update_option('SIRV_ACCOUNT_NAME', '');
    }
  }
  if (!get_option('SIRV_CDN_URL')) update_option('SIRV_CDN_URL', '');
  if (!get_option('SIRV_STAT')) update_option('SIRV_STAT', '', 'no');
  if (!get_option('SIRV_FETCH_MAX_FILE_SIZE')) update_option('SIRV_FETCH_MAX_FILE_SIZE', '');
  if (!get_option('SIRV_CSS_BACKGROUND_IMAGES')) update_option('SIRV_CSS_BACKGROUND_IMAGES', '');
  if (!get_option('SIRV_CSS_BACKGROUND_IMAGES_SYNC_DATA')) update_option('SIRV_CSS_BACKGROUND_IMAGES_SYNC_DATA', json_encode(array(
    'scan_type'         => 'theme',
    'theme'             => 'No scans yet',
    'custom_path'       => '',
    'last_sync'         => '',
    'last_sync_str'     => 'No scans yet',
    'img_domain'        => 'No scans yet',
    'img_count'         => 'No scans yet',
    'status'            => 'stop',
    'msg'               => '',
    'error'             => '',
    'css_path'          => '',
    'css_files_count'   => '',
    'skipped_images'    => array(),
  )), 'no');

  if (!get_option('SIRV_DELETE_FILE_ON_SIRV')) update_option('SIRV_DELETE_FILE_ON_SIRV', '2');
  if (!get_option('SIRV_SYNC_ON_UPLOAD')) update_option('SIRV_SYNC_ON_UPLOAD', 'off');

  if (!get_option('SIRV_EXCLUDE_FILES')) update_option('SIRV_EXCLUDE_FILES', '');
  if (!get_option('SIRV_EXCLUDE_PAGES')) update_option('SIRV_EXCLUDE_PAGES', '');
  if (!get_option('SIRV_EXCLUDE_RESPONSIVE_FILES')) update_option('SIRV_EXCLUDE_RESPONSIVE_FILES', '');

  if (!get_option('SIRV_NETWORK_TYPE')) update_option('SIRV_NETWORK_TYPE', '2');
  if (!get_option('SIRV_PARSE_STATIC_IMAGES')) update_option('SIRV_PARSE_STATIC_IMAGES', '1');
  if (!get_option('SIRV_PARSE_VIDEOS')) update_option('SIRV_PARSE_VIDEOS', 'off');
  if (!get_option('SIRV_USE_SIRV_RESPONSIVE') || empty(get_option('SIRV_USE_SIRV_RESPONSIVE'))) update_option('SIRV_USE_SIRV_RESPONSIVE', '2');
  if (!get_option('SIRV_ENABLE_CDN')) update_option('SIRV_ENABLE_CDN', '2');
  if (!get_option('SIRV_JS')) update_option('SIRV_JS', '2');
  if (!get_option('SIRV_JS_MODULES')) update_option('SIRV_JS_MODULES', 'lazyimage,zoom,spin,hotspots,video,gallery,model');
  if (!get_option('SIRV_CUSTOM_CSS')) update_option('SIRV_CUSTOM_CSS', '');
  if (!get_option('SIRV_CUSTOM_SMV_SH_OPTIONS')) update_option('SIRV_CUSTOM_SMV_SH_OPTIONS', '');

  if (!get_option('SIRV_CROP_SIZES')) update_option('SIRV_CROP_SIZES', sirv_get_default_crop());
  if (!get_option('SIRV_RESPONSIVE_PLACEHOLDER')) update_option('SIRV_RESPONSIVE_PLACEHOLDER', 'image');


  $domain = empty($_SERVER['HTTP_HOST']) ? 'MediaLibrary' : $_SERVER['HTTP_HOST'];
  if (!get_option('SIRV_FOLDER')) update_option('SIRV_FOLDER', 'WP_' . $domain);

  if (!get_site_option('SIRV_WP_NETWORK_WIDE')) update_site_option('SIRV_WP_NETWORK_WIDE', '');

  if(!get_option('SIRV_PREVENT_CREATE_WP_THUMBS')) update_option('SIRV_PREVENT_CREATE_WP_THUMBS', 'off');
  if(!get_option('SIRV_PREVENTED_SIZES')) update_option('SIRV_PREVENTED_SIZES', json_encode(array(), JSON_FORCE_OBJECT));

  if(!get_option('SIRV_THUMBS_DATA')) update_option('SIRV_THUMBS_DATA', json_encode(array(
    'status' => 'start', //processing, finished
    'type' => '', //deleting, regenerating
    'last_id' => 0,
    'files_count' => 0,
    'files_size' => 0,
    'ids_count' => sirv_get_synced_count(),
    'processed_ids' => 0,
    'percent_finished' => 0
  )), 'no');

  if (!get_option('SIRV_HTTP_AUTH_CHECK')) update_option('SIRV_HTTP_AUTH_CHECK', '0');
  if (!get_option('SIRV_HTTP_AUTH_USER')) update_option('SIRV_HTTP_AUTH_USER', '');
  if (!get_option('SIRV_HTTP_AUTH_PASS')) update_option('SIRV_HTTP_AUTH_PASS', '');
}


function sirv_fix_db(){
  global $wpdb;
  global $base_prefix;
  $wpdb->show_errors();
  $t_images = $wpdb->prefix . 'sirv_images';
  $t_errors = $base_prefix . 'sirv_fetching_errors';

  if (sirv_is_db_field_exists('sirv_images', 'sirvpath')) {
    $result = $wpdb->query("ALTER TABLE $t_images CHANGE `wp_path` `img_path` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL");
    $result = $wpdb->query("ALTER TABLE $t_images
                  DROP `sirvpath`,
                  DROP `sirv_image_url`,
                  DROP `sirv_folder`");
    $result = $wpdb->query("ALTER TABLE $t_images ADD `checks` TINYINT UNSIGNED NULL DEFAULT 0 AFTER `timestamp_synced`");
    $result = $wpdb->query("ALTER TABLE $t_images ADD `timestamp_checks` INT NULL DEFAULT NULL AFTER `checks`");
    $result = $wpdb->query("ALTER TABLE $t_images ADD `status` enum('NEW', 'PROCESSING', 'SYNCED', 'FAILED') DEFAULT NULL AFTER `size`");
    $result = $wpdb->query("ALTER TABLE $t_images ADD `error_type` TINYINT UNSIGNED NULL DEFAULT NULL AFTER `status`");
    $result = $wpdb->query("ALTER TABLE $t_images ADD `sirv_path` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `img_path`");
    $result = $wpdb->query("UPDATE $t_images SET status = 'SYNCED'");
    //$delete = $wpdb->query("TRUNCATE TABLE $t_images");
  }

  if (!sirv_is_db_field_exists('sirv_images', 'error_type')) {
    $result = $wpdb->query("ALTER TABLE $t_images ADD `error_type` TINYINT UNSIGNED NULL DEFAULT NULL AFTER `status`");
  }

  if (!sirv_is_db_field_exists('sirv_images', 'sirv_path')) {
    $result = $wpdb->query("ALTER TABLE $t_images ADD `sirv_path` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' AFTER `img_path`");
  }

  if (sirv_is_db_field_exists('sirv_images', 'timestamp_checks')) {
    $result = $wpdb->query("ALTER TABLE $t_images CHANGE COLUMN `timestamp_checks` `timestamp_checks` INT NULL DEFAULT NULL");
  }

  if (empty($wpdb->get_results("SHOW TABLES LIKE '$t_errors'", ARRAY_N))) {
    $sql_errors = "CREATE TABLE $t_errors (
      id int unsigned NOT NULL auto_increment,
      error_msg varchar(255) DEFAULT '',
      PRIMARY KEY  (id))
      ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_errors);
    sirv_fill_err_table($t_errors);
  } else {
    if( $wpdb->query("TRUNCATE TABLE $t_errors") ){
      $wpdb->delete($t_images, array('status' => 'FAILED'));
      sirv_fill_err_table($t_errors);
    }
  }

  //6.7.2 extend sirv_path and img_path size
  $result = $wpdb->query("ALTER TABLE $t_images CHANGE `sirv_path` `sirv_path` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL");
  $result = $wpdb->query("ALTER TABLE $t_images CHANGE `img_path` `img_path` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL");
}


function sirv_fill_err_table($t_errors){
  global $wpdb;

  foreach (FetchError::get_errors() as $error_msg) {
    $wpdb->insert($t_errors, array('error_msg' => $error_msg));
  }
}


function sirv_is_db_field_exists($table, $field){
  global $wpdb;
  $table_name = $wpdb->prefix . $table;

  return !empty($wpdb->get_results("SHOW COLUMNS FROM $table_name LIKE '$field'", ARRAY_A));
}


function sirv_get_field_names($data)
{
  $tmp_arr = array();

  foreach ($data as $key => $field_data) {
    $tmp_arr[] = $field_data['Field'];
  }

  return $tmp_arr;
}


//add_action('wp_head', 'sirv_meta_head', 0);

function sirv_meta_head(){

  $sirv_url = sirv_get_sirv_path();

  echo '<link rel="preconnect" href="' . $sirv_url . '" crossorigin>' . PHP_EOL;
  echo '<link rel="dns-prefetch" href="' . $sirv_url . '">' . PHP_EOL;

  echo '<link rel="preconnect" href="https://scripts.sirv.com" crossorigin>' . PHP_EOL;
  echo '<link rel="dns-prefetch" href="https://scripts.sirv.com">' . PHP_EOL;
}

add_filter('wp_resource_hints', 'sirv_preconnect' , 0, 2);

function sirv_preconnect($urls, $relation_type){
  $sirv_url = sirv_get_sirv_path();

  $type = array('dns-prefetch', 'preconnect');

  $crossorigin = $relation_type === 'preconnect' ? 'crossorigin' : '';

  if(in_array($relation_type, $type)){
    $urls[] = array(
      'href' => $sirv_url,
      $crossorigin
    );

    $urls[] = array(
      'href' => 'https://scripts.sirv.com',
      $crossorigin
    );
  }


  return $urls;
}


//gutenberg includes
if (function_exists('register_block_type')) {
  if (!function_exists('sirv_addmedia_block')) {
    function sirv_addmedia_block()
    {

      wp_register_script(
        'sirv-addmedia-block-editor-js',
        SIRV_PLUGIN_SUBDIR_URL_PATH . 'gutenberg/addmedia-block/editor-script.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-i18n', 'sirv_modal', 'sirv_logic', 'sirv_modal-logic', 'sirv_logic-md5', 'jquery'),
        false,
        true
      );

      /*wp_register_style(
        'sirv-addmedia-block-css',
        SIRV_PLUGIN_SUBDIR_URL_PATH  . 'gutenberg/addmedia-block/style.css',
        array( 'wp-edit-blocks' ),
        filemtime( SIRV_PLUGIN_SUBDIR_URL_PATH  . 'gutenberg/addmedia-block/style.css' )
      );*/

      wp_register_style(
        'sirv-addmedia-block-editor-css',
        SIRV_PLUGIN_SUBDIR_URL_PATH . '/gutenberg/addmedia-block/editor-style.css',
        array('wp-edit-blocks'),
        filemtime(SIRV_PLUGIN_SUBDIR_PATH  . 'gutenberg/addmedia-block/editor-style.css')
      );

      register_block_type('sirv/addmedia-block', array(
        'editor_script' => 'sirv-addmedia-block-editor-js',
        'editor_style'  => 'sirv-addmedia-block-editor-css',
        //'style'         => 'sirv-addmedia-block-css'
      ));
    }

    add_action('init', 'sirv_addmedia_block');
  }
}


//show message on plugin activation
add_action('admin_notices', 'sirv_admin_notices');

function sirv_admin_notices(){
  if ($notices = get_option('sirv_admin_notices')) {
    foreach ($notices as $notice) {
      echo "<div class='updated'><p>$notice</p></div>";
    }
    delete_option('sirv_admin_notices');
  }

  sirv_review_notice();
  sirv_empty_logins_notice();
  sirv_oversize_storage_notice();
}


function sirv_oversize_storage_notice(){
  $notice_id = 'sirv_oversize_storage';

  $storage_data = sirv_getStorageInfo();
  $notice = '';
  $notice_type = 'warning';
  $dismiss_type = 'noticed';
  $is_renew_status = false;

  $used_persent = floatval($storage_data['storage']['used_percent']);

  $allowance = Utils::getFormatedFileSize($storage_data['storage']['allowance']);
  $used = $storage_data['storage']['used_text'];

  if($used_persent < 95) return;

  if($used_persent >= 95 && $used_persent < 100){
    $notice = '<h3>Sirv</h3><p><strong>Storage low</strong> - you are using '. $used .' of your '. $allowance . ' storage allowance. For more storage, <a target="_blank" href="https://my.sirv.com/#/account/billing">upgrade your Sirv plan.</a></p>';
    $dismiss_type = 'noticed';
  }

  if($used_persent >= 100){
    $notice = '<h3>Sirv</h3><p><strong>Storage exceeds 100%</strong> - you are using ' . $used . ' of your ' . $allowance . ' storage allowance. Please, <a target="_blank" href="https://my.sirv.com/#/account/billing">upgrade your Sirv plan</a> to get more storage space.</p>';
    $notice_type = 'error';
    $dismiss_type = 'option_pages';
    $is_renew_status = true;
  }

  echo sirv_get_wp_notice($notice, $notice_id, $notice_type, true, $dismiss_type, $is_renew_status);
}


function sirv_congrat_notice(){
  $notices = get_option('sirv_admin_notices', array());
  $notices[] = 'Congratulations, you\'ve just installed Sirv for WordPress! Now <a href="admin.php?page=' . SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'submenu_pages/account.php">configure the Sirv plugin</a> to start using it.';

  update_option('sirv_admin_notices', $notices);
}


function sirv_depreceted_v2_notice(){
  $use_version = '3';

  if( $use_version === '3' ) return;

  $notice_id = 'sirv_deprecated_v2';
  $notice_status = get_option($notice_id);

  if( !$notice_status || $notice_status != 'dismiss'){
    $notice = '<p><b>Sirv update coming</b> - in August 2021, the new sirv.js version will replace the original sirv.js version. We recommend you switch to the new version soon - it\'s fast, elegant and gives you more options for making beautiful galleries.</p>
      <p>Simply go to your <a href="admin.php?page=' . SIRV_PLUGIN_RELATIVE_SUBDIR_PATH  . 'options.php">Sirv settings page</a> and set "Sirv JS version" to "Sirv JS v3". Then check that your website galleries look great. <a href="admin.php?page=' . SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'submenu_pages/feedback.php">Contact us</a> if you need any help. We hope you\'ll love it!</p>';

    echo sirv_get_wp_notice($notice, $notice_id, 'warning', true);
  }
}


function sirv_empty_logins_notice(){
  $page = SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'submenu_pages/account.php';
  $notice_id = 'sirv_empty_logins';

  if( isset($_GET['page']) && $_GET['page'] == $page ) return;

  $sirvAPIClient = sirv_getAPIClient();
  $sirvStatus = $sirvAPIClient->preOperationCheck();
  $isMuted = $sirvAPIClient->isMuted();

  if (!$sirvStatus && !$isMuted) {

    $notice = '<p>Please <a href="admin.php?page=' . SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'submenu_pages/account.php">configure the Sirv plugin</a> to start using it.</p>';
    echo sirv_get_wp_notice($notice, $notice_id, 'warning', false);
  }
}


//TODO: logic with cyclic events like message about ovverriding storage.

// data-dismiss-notice-type: "dismiss", "noticed", "option_pages", "current_day", "day", array("dismiss_type" => 'custom', "custom_time" => time in seconds )
// dismiss - no logic, show on every page load
// noticed - does not show message ever
// option_pages - show message only on options page
// current_day - don't show message during current day
// day - does not show message 24 hours from time when message closed
// custom array - does not show message for some period of time
//
//$notice_type: error, warning, success, info
function sirv_get_wp_notice($msg, $notice_id, $notice_type = 'info', $is_dismissible = true, $dismiss_notice_type = 'dismiss', $is_renew_status = false){
  $custom_time = 0;
  $option_pages = array(
    SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'options.php',
    SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'shortcodes-view.php',
    SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'media_library.php',
    SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'submenu_pages/account.php',
    SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'submenu_pages/help.php',
    SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'submenu_pages/feedback.php',
  );
  $trainsient_statuses = array('current_day', 'day', 'custom');

  $style = "
    <style>
      .sirv-admin-notice h3 {
        margin-bottom: 0px;
        margin-top: 10px;
      }
    </style>
  " . PHP_EOL;
  $current_status = get_option($notice_id);

  if( is_array($dismiss_notice_type) ){
    $custom_time = $dismiss_notice_type['custom_time'];
    $dismiss_notice_type = $dismiss_notice_type['dismiss_type'];
  }

  $current_status = empty($current_status) ? 'dismiss' : $current_status;

  if( !$is_renew_status ){
    if ($current_status == 'noticed') return;

    if ($current_status == 'option_pages') {
      $is_page = isset($_GET['page']);
      if (!$is_page || ($is_page && !in_array($_GET['page'], $option_pages))) return;
    }

    if (in_array($current_status, $trainsient_statuses)) {
      $trainsient_status = get_transient($notice_id);
      if ($trainsient_status) return;
    }
  }


  if( $is_dismissible ){

    wp_register_script('sirv_review', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/wp-sirv-dismiss-notice.js', array('jquery'), '1.0.0', true);
    wp_localize_script('sirv_review', 'sirv_dismiss_ajax_object', array(
      'ajaxnonce' => wp_create_nonce('sirv_rewiew_ajax_validation_nonce'),),
    );

    wp_enqueue_script('sirv_review');
  }


  $dismissible = $is_dismissible ? 'is-dismissible' : '';
  $dismiss_type_attr = $is_dismissible ? ' data-sirv-dismiss-type="'. $dismiss_notice_type .'" ' : '';
  $custom_time_attr = $dismiss_notice_type == 'custom' ? ' data-sirv-custom-time="'. $custom_time .'" ' : '';

  $notice = '<div data-sirv-notice-id="'. $notice_id .'" class="sirv-admin-notice notice notice-'. $notice_type .' '. $dismissible .'"'. $dismiss_type_attr . $custom_time_attr .'>'. $msg .'</div>';
  return $style . $notice;
}


function sirv_review_notice(){
  $notice_id = 'sirv_review_notice';
  $sirv_review_notice = get_option($notice_id);

  if($sirv_review_notice == 'noticed') return;

  if (!$sirv_review_notice) {
    update_option('sirv_review_notice', time());
    $sirv_review_notice = NULL;
  }
  if (is_numeric($sirv_review_notice)) {
    $noticed_time = (int) $sirv_review_notice;
    $fire_time = $noticed_time + (14 * 24 * 60 * 60);
    if (time() >= $fire_time) {
      $notice = '<p>We noticed you\'ve been using Sirv for some time now - we hope you love it! We\'d be thrilled if you could <a target="_blank" href="https://wordpress.org/support/plugin/sirv/reviews/">give us a 5-star rating on WordPress.org!</a></p>
      <p>As a thank you, we\'ll give you 1GB extra free storage (regardless of the rating you choose).</p>
      <p>If you need help with the Sirv plugin, please <a href="admin.php?page=' . SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'submenu_pages/feedback.php">contact our team</a> and we\'ll reply ASAP.</p>';

      echo sirv_get_wp_notice($notice, $notice_id, 'info', true);
    }
  }
}


function sirv_create_plugin_tables(){
  global $base_prefix;
  global $wpdb;

  $t_shortcodes = $base_prefix . 'sirv_shortcodes';
  $t_images = $wpdb->prefix . 'sirv_images';
  $t_errors = $base_prefix . 'sirv_fetching_errors';

  $sql_shortcodes = "CREATE TABLE $t_shortcodes (
      id int unsigned NOT NULL auto_increment,
      width varchar(20) DEFAULT 'auto',
      thumbs_height varchar(20) DEFAULT NULL,
      gallery_styles varchar(255) DEFAULT NULL,
      align varchar(30) DEFAULT '',
      profile varchar(100) DEFAULT 'false',
      link_image varchar(10) DEFAULT 'false',
      show_caption varchar(10) DEFAULT 'false',
      use_as_gallery varchar(10) DEFAULT 'false',
      use_sirv_zoom varchar(10) DEFAULT 'false',
      images text DEFAULT NULL,
      shortcode_options text NOT NULL,
      timestamp datetime DEFAULT NULL,
      PRIMARY KEY  (id))
      ENGINE=InnoDB DEFAULT CHARSET=utf8;";

  $sql_sirv_images = "CREATE TABLE $t_images (
      id int unsigned NOT NULL auto_increment,
      attachment_id int(11) NOT NULL,
      img_path varchar(500) DEFAULT NULL,
      sirv_path varchar(1000) DEFAULT NULL,
      size int(10) DEFAULT NULL,
      status enum('NEW', 'PROCESSING', 'SYNCED', 'FAILED') DEFAULT NULL,
      error_type TINYINT UNSIGNED NULL DEFAULT NULL,
      timestamp datetime DEFAULT NULL,
      timestamp_synced datetime DEFAULT NULL,
      checks TINYINT UNSIGNED NULL DEFAULT 0,
      timestamp_checks INT DEFAULT NULL,
      PRIMARY KEY  (id),
      UNIQUE KEY `unique_key` (attachment_id)
      )ENGINE=InnoDB DEFAULT CHARSET=utf8;";

  $sql_errors = "CREATE TABLE $t_errors (
      id int unsigned NOT NULL auto_increment,
      error_msg varchar(255) DEFAULT '',
      PRIMARY KEY  (id))
      ENGINE=InnoDB DEFAULT CHARSET=utf8;";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

  $is_sirv_images_exists = $wpdb->get_results("SHOW TABLES LIKE '$t_images'", ARRAY_N);
  $is_sirv_shortcodes_exists = $wpdb->get_results("SHOW TABLES LIKE '$t_shortcodes'", ARRAY_N);
  $is_sirv_errors_exists = $wpdb->get_results("SHOW TABLES LIKE '$t_errors'", ARRAY_N);

  if (empty($is_sirv_shortcodes_exists)) dbDelta($sql_shortcodes);
  if (empty($is_sirv_images_exists)) dbDelta($sql_sirv_images);
  if (empty($is_sirv_errors_exists)) {
    dbDelta($sql_errors);
    foreach (FetchError::get_errors() as $error_msg) {
      $wpdb->insert($t_errors, array('error_msg' => $error_msg));
    }
  }
}

register_deactivation_hook(__FILE__, 'sirv_deactivation_callback');

function sirv_deactivation_callback(){
  //some code here
}


$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'sirv_plugin_settings_link');

function sirv_plugin_settings_link($links){
  $settings_link = '<a href="admin.php?page=' . SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'options.php">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}


//add button Sirv Media near Add Media
add_action('media_buttons', 'sirv_button', 11);

function sirv_button($editor_id = 'content'){

  if( !is_admin() ) return;

  global $post;

  $post_type = isset($post) && isset($post->post_type) ? $post->post_type : 'post';

  if(  $post_type == 'product' && get_option('SIRV_WOO_SHOW_ADD_MEDIA_BUTTON') == 'hide' ) return;

  wp_enqueue_style('fontAwesome', "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css", array());

  wp_register_style('sirv_toast_style', SIRV_PLUGIN_SUBDIR_URL_PATH . 'css/vendor/toastr.css');
  wp_enqueue_style('sirv_toast_style');
  wp_enqueue_script('sirv_toast_js', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/vendor/toastr.min.js', array('jquery'), false);

  wp_register_style('sirv_style', SIRV_PLUGIN_SUBDIR_URL_PATH . 'css/wp-sirv.css');
  wp_enqueue_style('sirv_style');
  wp_register_style('sirv_mce_style', SIRV_PLUGIN_SUBDIR_URL_PATH . 'css/wp-sirv-shortcode-view.css');
  wp_enqueue_style('sirv_mce_style');

  wp_register_script('sirv_logic', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/wp-sirv.js', array('jquery', 'jquery-ui-sortable', 'sirv_toast_js'), false);
  wp_localize_script('sirv_logic', 'sirv_ajax_object', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'ajaxnonce' => wp_create_nonce('sirv_logic_ajax_validation_nonce'),
    'assets_path' => SIRV_PLUGIN_SUBDIR_URL_PATH . 'assets',
    'plugin_subdir_path' => SIRV_PLUGIN_RELATIVE_SUBDIR_PATH),
  );
  wp_enqueue_script('sirv_logic');

  wp_enqueue_script('sirv_logic-md5', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/vendor/wp-sirv-md5.min.js', array(), false);
  wp_enqueue_script('sirv_modal', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/vendor/wp-sirv-bpopup.min.js', array('jquery'), false);
  wp_enqueue_script('sirv_modal-logic', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/wp-sirv-modal.js', array('jquery', 'sirv_modal', 'sirv_logic-md5'), false);

  $isNotEmptySirvOptions = sirv_check_empty_options_on_backend();
  wp_localize_script('sirv_modal-logic', 'modal_object', array(
    'media_add_url' =>  SIRV_PLUGIN_SUBDIR_URL_PATH . 'templates/media_add.html',
    'login_error_url' => SIRV_PLUGIN_SUBDIR_URL_PATH . 'templates/login_error.html',
    'featured_image_url' => SIRV_PLUGIN_SUBDIR_URL_PATH . 'templates/featured_image.html',
    'woo_set_product_image_url' => SIRV_PLUGIN_SUBDIR_URL_PATH . '/templates/woo_set_product_image.html',
    'isNotEmptySirvOptions' => $isNotEmptySirvOptions
  ));
  wp_enqueue_script('sirv-shortcodes-page', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/wp-sirv-shortcodes-page.js', array('jquery'), false);

  echo '<a href="#" class="button sirv-modal-click" title="Sirv add/insert images"><span class="dashicons dashicons-format-gallery" style="padding-top: 2px;"></span> Add Sirv Media</a><div class="sirv-modal"><div class="modal-content"></div></div>';
}


function sirv_check_empty_options_on_backend(){
  $account_name = getValue::getOption('SIRV_ACCOUNT_NAME');
  $cdn_url = getValue::getOption('SIRV_CDN_URL');

  if (empty($account_name) || empty($cdn_url)) {
    return false;
  } else {
    return true;
  }
}


//create menu for wp plugin and register settings
add_action("admin_menu", "sirv_create_menu", 0);

function sirv_create_menu(){
  $settings_item = DIRECTORY_SEPARATOR . SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'options.php';
  $library_item = DIRECTORY_SEPARATOR . SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'media_library.php';
  $shortcodes_view_item = DIRECTORY_SEPARATOR . SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'shortcodes-view.php';
  $account_item = DIRECTORY_SEPARATOR . SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'submenu_pages/account.php';
  $help_item = DIRECTORY_SEPARATOR . SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'submenu_pages/help.php';
  $feedback_item = DIRECTORY_SEPARATOR . SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'submenu_pages/feedback.php';

  add_menu_page('Sirv Menu', 'Sirv', 'manage_options', $settings_item, NULL, SIRV_PLUGIN_SUBDIR_URL_PATH . 'assets/menu-icon.svg');
  add_submenu_page($settings_item, 'Sirv Settings', 'Settings', 'manage_options', $settings_item);
  add_submenu_page($settings_item, 'Sirv Account', 'Account', 'manage_options', $account_item);
  add_submenu_page($settings_item, 'Sirv Shortcodes', 'Shortcodes', 'manage_options', $shortcodes_view_item);
  add_submenu_page($settings_item, 'Sirv Media Library', 'Media Library', 'manage_options', $library_item);
  add_submenu_page($settings_item, 'Sirv Help', 'Help', 'manage_options', $help_item);
  add_submenu_page($settings_item, 'Sirv Feedback', 'Feedback', 'manage_options', $feedback_item);
}


add_action('admin_enqueue_scripts', 'sirv_admin_scripts', 20);
function sirv_admin_scripts(){
  //if(!is_admin() && !(isset($_GET['page'] && $_GET['page'])) return;
  if (!is_admin()) return;

  $option_page = SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'options.php';
  $account_page = SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'submenu_pages/account.php';
  $help_page = SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'submenu_pages/help.php';
  $feedback_page = SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'submenu_pages/feedback.php';

  global $pagenow;

  //check if this is post or new post page or categories
  if (in_array($pagenow, array('post-new.php', 'post.php', 'edit-tags.php'))) {
    //check if gutenberg is active or it is categories page
    if (function_exists('register_block_type') || $pagenow == 'edit-tags.php') {
      wp_enqueue_style('fontAwesome', "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css", array());

      wp_register_style('sirv_toast_style', SIRV_PLUGIN_SUBDIR_URL_PATH . 'css/vendor/toastr.css');
      wp_enqueue_style('sirv_toast_style');
      wp_enqueue_script('sirv_toast_js', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/vendor/toastr.min.js', array('jquery'), false);

      wp_register_style('sirv_style', SIRV_PLUGIN_SUBDIR_URL_PATH . 'css/wp-sirv.css');
      wp_enqueue_style('sirv_style');
      wp_register_style('sirv_mce_style', SIRV_PLUGIN_SUBDIR_URL_PATH . 'css/wp-sirv-shortcode-view.css');
      wp_enqueue_style('sirv_mce_style');
      wp_register_script('sirv_logic', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/wp-sirv.js', array('jquery', 'jquery-ui-sortable', 'sirv_toast_js'), '1.1.0');
      wp_localize_script('sirv_logic', 'sirv_ajax_object', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'ajaxnonce' => wp_create_nonce('sirv_logic_ajax_validation_nonce'),
        'assets_path' => SIRV_PLUGIN_SUBDIR_URL_PATH . 'assets',
        'plugin_subdir_path' => SIRV_PLUGIN_RELATIVE_SUBDIR_PATH,
        'sirv_cdn_url' => get_option('SIRV_CDN_URL'))
      );
      wp_enqueue_script('sirv_logic');

      wp_enqueue_script('sirv_logic-md5', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/vendor/wp-sirv-md5.min.js', array(), '1.0.0');
      wp_enqueue_script('sirv_modal', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/vendor/wp-sirv-bpopup.min.js', array('jquery'), '1.0.0');
      wp_enqueue_script('sirv_modal-logic', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/wp-sirv-modal.js', array('jquery', 'sirv_modal', 'sirv_logic-md5'), false);

      $isNotEmptySirvOptions = sirv_check_empty_options_on_backend();
      wp_localize_script(
        'sirv_modal-logic',
        'modal_object',
        array(
          'media_add_url' =>  SIRV_PLUGIN_SUBDIR_URL_PATH . 'templates/media_add.html',
          'login_error_url' => SIRV_PLUGIN_SUBDIR_URL_PATH . 'templates/login_error.html',
          'featured_image_url' => SIRV_PLUGIN_SUBDIR_URL_PATH . 'templates/featured_image.html',
          'woo_media_add_url' => SIRV_PLUGIN_SUBDIR_URL_PATH . 'templates/woo_media_add.html',
          'woo_set_product_image_url' => SIRV_PLUGIN_SUBDIR_URL_PATH . '/templates/woo_set_product_image.html',
          'isNotEmptySirvOptions' => $isNotEmptySirvOptions
        )
      );
      wp_enqueue_script('sirv-shortcodes-page', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/wp-sirv-shortcodes-page.js', array('jquery'), false);
    }
  }

  if ( isset($_GET['page']) && ( $_GET['page'] == $option_page || $_GET['page'] == $help_page ) ) {
    wp_register_style('sirv_options_style', SIRV_PLUGIN_SUBDIR_URL_PATH . 'css/wp-options.css');
    wp_enqueue_style('sirv_options_style');
    wp_enqueue_script('sirv_scrollspy', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/scrollspy.js', array('jquery'), '1.0.0');

    //ui
    wp_register_style('sirv-ui-css', SIRV_PLUGIN_SUBDIR_URL_PATH . 'css/wp-sirv-ui.css');
    wp_enqueue_style('sirv-ui-css');
    wp_enqueue_script('sirv_modal', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/vendor/wp-sirv-bpopup.min.js', array('jquery'), '1.0.0');
    wp_enqueue_script('sirv_ui-js', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/wp-sirv-ui.js', array('jquery', 'jquery-ui-sortable', 'sirv_modal'), false);

    wp_enqueue_script('sirv_options', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/wp-options.js', array('jquery', 'jquery-ui-sortable', 'sirv_ui-js'), false, true);
    wp_localize_script('sirv_options', 'sirv_options_data', array(
      'ajaxurl' => admin_url('admin-ajax.php'),
      'ajaxnonce' => wp_create_nonce('ajax_validation_nonce'),
    ));
  }

  if ( isset($_GET['page']) && ( $_GET['page'] == $feedback_page || $_GET['page'] == $account_page) ) {
    wp_register_style('sirv_options_style', SIRV_PLUGIN_SUBDIR_URL_PATH . 'css/wp-options.css');
    wp_enqueue_style('sirv_options_style');
    wp_enqueue_script('sirv_options', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/wp-options.js', array('jquery', 'jquery-ui-sortable'), false, true);
    wp_localize_script('sirv_options', 'sirv_options_data', array(
      'ajaxurl' => admin_url('admin-ajax.php'),
      'ajaxnonce' => wp_create_nonce('ajax_validation_nonce'),
    ));
  }

  if (isset($_GET['page']) && $_GET['page'] == SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'shortcodes-view.php') {
    wp_enqueue_style('fontAwesome', "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css", array());

    wp_register_style('sirv_toast_style', SIRV_PLUGIN_SUBDIR_URL_PATH . 'css/vendor/toastr.css');
    wp_enqueue_style('sirv_toast_style');
    wp_enqueue_script('sirv_toast_js', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/vendor/toastr.min.js', array('jquery'), false);

    wp_register_style('sirv_style', SIRV_PLUGIN_SUBDIR_URL_PATH . 'css/wp-sirv.css');
    wp_enqueue_style('sirv_style');
    wp_enqueue_script('sirv_logic', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/wp-sirv.js', array('jquery', 'jquery-ui-sortable', 'sirv_toast_js'), false);
    wp_localize_script('sirv_logic', 'sirv_ajax_object', array(
      'ajaxurl' => admin_url('admin-ajax.php'),
        'ajaxnonce' => wp_create_nonce('sirv_logic_ajax_validation_nonce'),
      'assets_path' => SIRV_PLUGIN_SUBDIR_URL_PATH . 'assets')
    );
    wp_enqueue_script('sirv_logic-md5', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/vendor/wp-sirv-md5.min.js', array(), '1.0.0');
    wp_enqueue_script('sirv_modal', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/vendor/wp-sirv-bpopup.min.js', array('jquery'), '1.0.0');

    wp_localize_script('sirv_modal', 'modal_object', array(
      'media_add_url' =>  SIRV_PLUGIN_SUBDIR_URL_PATH . 'templates/media_add.html',
      'login_error_url' => SIRV_PLUGIN_SUBDIR_URL_PATH . 'templates/login_error.html',
      'featured_image_url' => SIRV_PLUGIN_SUBDIR_URL_PATH . 'templates/featured_image.html',
      'woo_set_product_image_url' => SIRV_PLUGIN_SUBDIR_URL_PATH . '/templates/woo_set_product_image.html',
    ));

    wp_register_script('sirv-shortcodes-page', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/wp-sirv-shortcodes-page.js', array('jquery'), false);
    wp_enqueue_script('sirv-shortcodes-page');
    wp_localize_script('sirv-shortcodes-page', 'sirvShortcodeObject', array('isShortcodesPage' => true));
  }
}




//load sirv widget for elementor builder
add_action('plugins_loaded', 'sirv_elementor_widget', 10);
function sirv_elementor_widget(){
  if (did_action('elementor/loaded')) {
    require_once(SIRV_PLUGIN_SUBDIR_PATH . 'htmlBuilders/elementor/Plugin.php');
    \SirvElementorWidget\Plugin::instance();
  }
}


//include plugin for tinyMCE to show sirv gallery shortcode in visual mode
add_filter('mce_external_plugins', 'sirv_tinyMCE_plugin_shortcode_view');

function sirv_tinyMCE_plugin_shortcode_view(){
  return array('sirvgallery' => SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/wp-sirv-shortcode-view.js');
}


//add_filter( 'script_loader_tag', 'sirv_add_defer_to_js', 10, 2 );

function sirv_add_defer_to_js($tag, $handle){
  /* print('<br>-------------------<br>');
  print_r($handle);
  print('<br>-------------------<br>'); */

  //sirv_debug_msg($handle);

  //global $wp_scripts;
  //sirv_debug_msg($wp_scripts);

  if ('sirv-js' !== $handle) {
    return $tag;
  }

  return $tag;
}


add_action('admin_init', 'sirv_admin_init');
function sirv_admin_init(){
  //sirv_register_settings();

  sirv_tinyMCE_plugin_shortcode_view_styles();
  sirv_redirect_to_options();
}


//add styles for tinyMCE plugin
function sirv_tinyMCE_plugin_shortcode_view_styles(){
  add_editor_style(SIRV_PLUGIN_SUBDIR_URL_PATH . 'css/wp-sirv-shortcode-view.css');
}

//redirect to options after activate plugin
function sirv_redirect_to_options(){
  // Bail if no activation redirect
  if (!get_transient('isSirvActivated')) {
    return;
  }

  // Delete the redirect transient
  delete_transient('isSirvActivated');

  // Bail if activating from network, or bulk
  if (is_network_admin() || isset($_GET['activate-multi'])) {
    return;
  }

  if ( get_option('SIRV_ACCOUNT_NAME') == '' || get_option('SIRV_CDN_URL') == '' ) {
    // Redirect to bbPress about page
    wp_safe_redirect(add_query_arg(array('page' => SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'submenu_pages/account.php'), admin_url('admin.php')));
  }
}


function sirv_register_settings(){
  register_setting('sirv-settings-group', 'SIRV_FOLDER');
  register_setting('sirv-settings-group', 'SIRV_ENABLE_CDN');
  register_setting('sirv-settings-group', 'SIRV_NETWORK_TYPE');
  register_setting('sirv-settings-group', 'SIRV_PARSE_STATIC_IMAGES');
  register_setting('sirv-settings-group', 'SIRV_PARSE_VIDEOS');
  register_setting('sirv-settings-group', 'SIRV_CLIENT_ID');
  register_setting('sirv-settings-group', 'SIRV_CLIENT_SECRET');
  register_setting('sirv-settings-group', 'SIRV_TOKEN');
  register_setting('sirv-settings-group', 'SIRV_TOKEN_EXPIRE_TIME');
  register_setting('sirv-settings-group', 'SIRV_MUTE');
  register_setting('sirv-settings-group', 'SIRV_MUTE_ERROR_MESSAGE');
  register_setting('sirv-settings-group', 'SIRV_ACCOUNT_EMAIL');
  register_setting('sirv-settings-group', 'SIRV_ACCOUNT_NAME');
  register_setting('sirv-settings-group', 'SIRV_CDN_URL');
  register_setting('sirv-settings-group', 'SIRV_STAT');
  register_setting('sirv-settings-group', 'SIRV_FETCH_MAX_FILE_SIZE');
  register_setting('sirv-settings-group', 'SIRV_CSS_BACKGROUND_IMAGES');
  register_setting('sirv-settings-group', 'SIRV_CSS_BACKGROUND_IMAGES_SYNC_DATA');

  register_setting('sirv-settings-group', 'SIRV_DELETE_FILE_ON_SIRV');
  register_setting('sirv-settings-group', 'SIRV_SYNC_ON_UPLOAD');

  register_setting('sirv-settings-group', 'SIRV_EXCLUDE_FILES');
  register_setting('sirv-settings-group', 'SIRV_EXCLUDE_PAGES');
  register_setting('sirv-settings-group', 'SIRV_EXCLUDE_RESPONSIVE_FILES');

  register_setting('sirv-settings-group', 'SIRV_SHORTCODES_PROFILES');
  register_setting('sirv-settings-group', 'SIRV_CDN_PROFILES');
  register_setting('sirv-settings-group', 'SIRV_USE_SIRV_RESPONSIVE');

  register_setting('sirv-settings-group', 'SIRV_VERSION_PLUGIN_INSTALLED');
  register_setting('sirv-settings-group', 'SIRV_JS');
  register_setting('sirv-settings-group', 'SIRV_JS_MODULES');
  register_setting('sirv-settings-group', 'SIRV_CUSTOM_CSS');
  register_setting('sirv-settings-group', 'SIRV_CUSTOM_SMV_SH_OPTIONS');

  register_setting('sirv-settings-group', 'SIRV_CROP_SIZES');
  register_setting('sirv-settings-group', 'SIRV_RESPONSIVE_PLACEHOLDER');

  register_setting('sirv-settings-group', 'SIRV_WP_NETWORK_WIDE');


  register_setting('sirv-settings-group', 'SIRV_PREVENT_CREATE_WP_THUMBS');
  register_setting('sirv-settings-group', 'SIRV_PREVENTED_SIZES');

  register_setting('sirv-settings-group', 'SIRV_THUMBS_DATA');


  register_setting('sirv-settings-group', 'SIRV_HTTP_AUTH_CHECK');
  register_setting('sirv-settings-group', 'SIRV_HTTP_AUTH_USER');
  register_setting('sirv-settings-group', 'SIRV_HTTP_AUTH_PASS');

  sirv_fill_empty_options();

  require_once (SIRV_PLUGIN_SUBDIR_PATH . 'includes/classes/options/options.helper.class.php');
  OptionsHelper::prepareOptionsData();
  OptionsHelper::register_settings();
}


add_action('update_option_SIRV_FOLDER', 'sirv_set_folder_config', 10, 2);
function sirv_set_folder_config($old_value, $new_value){
  if ($old_value !== $new_value) {
    $isCreated = false;

    $sirvAPIClient = sirv_getAPIClient();
    $isRenamed = $sirvAPIClient->renameFile('/' . $old_value, '/' . $new_value);

    if(!$isRenamed){
      $isCreated = $sirvAPIClient->createFolder($new_value . '/');
    }


    if($isRenamed || $isCreated){
      $sirvAPIClient->setFolderOptions($new_value, array('scanSpins' => false));

      global $wpdb;
      $images_t = $wpdb->prefix . 'sirv_images';
      $delete = $wpdb->query("TRUNCATE TABLE $images_t");
    }
  }
}

//clear all cache if disable ttl
add_action('update_option_SIRV_WOO_TTL', 'sirv_set_woo_ttl', 10, 2);
function sirv_set_woo_ttl($old_value, $new_value){
  if ($old_value !== $new_value) {
    if((int) $new_value === 1){
      global $wpdb;
      $post_meta_table = $wpdb->postmeta;
      $result = $wpdb->query("DELETE FROM $post_meta_table WHERE meta_key IN ('_sirv_woo_viewf_data', '_sirv_woo_viewf_status')");
    }
    update_option('SIRV_WOO_MV_CUSTOM_OPTIONS', $new_value);
  }
}


add_action('update_option_SIRV_WOO_MV_CUSTOM_OPTIONS', 'sirv_set_woo_mv_custom_js', 10, 2);
function sirv_set_woo_mv_custom_js($old_value, $new_value){
  if ($old_value !== $new_value) {
    update_option('SIRV_WOO_MV_CUSTOM_OPTIONS', sirv_remove_tag($new_value, 'script'));
  }
}


add_action('update_option_SIRV_CUSTOM_SMV_SH_OPTIONS', 'sirv_set_sh_smv_custom_js', 10, 2);
function sirv_set_sh_smv_custom_js($old_value, $new_value){
  if ($old_value !== $new_value) {
    update_option('SIRV_CUSTOM_SMV_SH_OPTIONS', sirv_remove_tag($new_value, 'script'));
  }
}


add_action('update_option_SIRV_WOO_MV_CUSTOM_CSS', 'sirv_set_woo_mv_custom_css', 10, 2);
function sirv_set_woo_mv_custom_css($old_value, $new_value)
{
  if ($old_value !== $new_value) {
    update_option('SIRV_WOO_MV_CUSTOM_CSS', sirv_remove_tag($new_value, 'style'));
  }
}


add_action('update_option_SIRV_EXCLUDE_FILES', 'sirv_set_exclude_files', 10, 2);
function sirv_set_exclude_files($old_value, $new_value){
  if ($old_value !== $new_value) {
    update_option('SIRV_EXCLUDE_FILES', sirv_parse_exclude_data($new_value));
  }
}


add_action('update_option_SIRV_EXCLUDE_RESPONSIVE_FILES', 'sirv_set_exclude_responsive_files', 10, 2);
function sirv_set_exclude_responsive_files($old_value, $new_value){
  if ($old_value !== $new_value) {
    update_option('SIRV_EXCLUDE_RESPONSIVE_FILES', sirv_parse_exclude_data($new_value));
  }
}


add_action('update_option_SIRV_EXCLUDE_PAGES', 'sirv_set_exclude_pages', 10, 2);
function sirv_set_exclude_pages($old_value, $new_value){
  if ($old_value !== $new_value) {
    update_option('SIRV_EXCLUDE_PAGES', sirv_parse_exclude_data($new_value));
  }
}


function sirv_parse_exclude_data($new_data){
  $exclude_str = '';

  if(!empty($new_data)){
    $data = Exclude::parseExcludePaths($new_data);
    $home_url = home_url();

    foreach ($data as $explode_item) {
      $exclude_str .= str_replace($home_url, '', $explode_item) . PHP_EOL;
    }
  }

  return $exclude_str;
}


function sirv_remove_tag($data, $tag){
  return trim(preg_replace('/<(\/)*'. $tag .'.*?>/im', '', $data));
}


function sirv_is_unique_field($field){
  global $wpdb;
  $sirv_images_t = $wpdb->prefix . 'sirv_images';

  $check_data = $wpdb->get_results("SHOW INDEXES FROM $sirv_images_t WHERE Column_name='$field' AND NOT Non_unique", ARRAY_A);

  if (empty($check_data) || $check_data[0]['Non_unique'] == 1) return false;
  else return true;
}


function sirv_set_unique_field($field){
  global $wpdb;
  $sirv_images_t = $wpdb->prefix . 'sirv_images';
  $duplicated_ids = array();

  $duplicates_count = $wpdb->get_results("
    SELECT COUNT(t1.id) AS count FROM $sirv_images_t t1
    INNER JOIN $sirv_images_t t2
    WHERE t1.id > t2.id AND t1.$field = t2.$field
    ", ARRAY_A);

  $counter = intval($duplicates_count[0]['count']) >= 1000 ? 1000 : intval($duplicates_count[0]['count']);

  do {
    $duplicated_ids = $wpdb->get_results("
    SELECT t1.id FROM $sirv_images_t t1
    INNER JOIN $sirv_images_t t2
    WHERE t1.id > t2.id AND t1.$field = t2.$field
    LIMIT 1000
    ", ARRAY_A);

    if (!empty($duplicated_ids)) {
      $ids = implode("','", array_values(array_unique(sirv_flattern_array($duplicated_ids, true, 'id'))));
      $wpdb->query("DELETE FROM $sirv_images_t WHERE id IN ('$ids')");
    }

    if ($counter >= intval($duplicates_count[0]['count'])) break;
    else $counter += 1000;
  } while (!empty($duplicated_ids));

  $wpdb->query("ALTER TABLE $sirv_images_t ADD UNIQUE ($field)");
}


if (get_option('SIRV_JS') === '1') {
  add_action('wp_enqueue_scripts', 'sirv_add_sirv_js', 0);
}


function sirv_add_sirv_js(){
  $sirv_js_path = getValue::getOption('SIRV_JS_FILE');

  wp_register_script('sirv-js', $sirv_js_path, array(), false, false);
  wp_enqueue_script('sirv-js');
}


function sirv_buffer_start(){
  global $sirv_ob_lvl;

  ob_start("sirv_check_responsive");

  $sirv_ob_lvl = ob_get_level();
}


function sirv_buffer_end(){
  if (!empty($GLOBALS['sirv_wp_foot'])) return;

  global $sirv_ob_lvl;

  $GLOBALS['sirv_wp_foot'] = true;

  if( $sirv_ob_lvl == ob_get_level() ){
    ob_end_flush();
    $sirv_ob_lvl = -1;
  }

  sirv_processFetchQueue();
}


function sirv_check_responsive($content){

  if (is_admin()) return $content;

  if (get_option('SIRV_JS') === '2') {
    $pattern = '/class=(("|\')|("|\')([^"\']*)\s)Sirv(("|\')|\s([^"\']*)("|\'))/is';
    $sirvjs_pattern = '/(<script.*?src=[\"\']https:\/\/scripts\.sirv\.com\/.*?sirv(\.full)?\.js.*?[\"\'].*?>)/is';
    $link_prefetch_pattern = '/(<link.*?href=[\"\']https:\/\/scripts\.sirv\.com[\"\'].*?rel=[\"\']preconnect[\"\'].*?>)/is';

    if (preg_match($pattern, $content) === 1) {
      if (preg_match($sirvjs_pattern, $content) == 0) {
        $sirv_js_path = getValue::getOption('SIRV_JS_FILE');

        if(preg_match($link_prefetch_pattern, $content) === 1){
          $content = preg_replace($link_prefetch_pattern, '$1<script src="' . $sirv_js_path . '"></script>', $content, 1);
        }else{
          $content = preg_replace('/(<\/head>)/is', '<script src="' . $sirv_js_path . '"></script>$1', $content, 1);
        }

      }

      $sirv_custom_css = get_option('SIRV_CUSTOM_CSS');
      if (!empty($sirv_custom_css)) {
        $content = preg_replace('/(<\/head>)/is', '<style id="sirv-custom-css">' . $sirv_custom_css . '</style>$1', $content, 1);
      }
    }
  }

  //remove BOM symbol
  $content = str_replace("\xEF\xBB\xBF", '', $content);

  //if cdn on parse  non wp proccessing images and return cdn version
  if (get_option('SIRV_ENABLE_CDN') === '1' && ( get_option('SIRV_PARSE_STATIC_IMAGES') == '1' || get_option("SIRV_PARSE_VIDEOS") == 'on') ) {
    $content = sirv_the_content($content, 'content');
  }

  return $content;
}


if (!function_exists("sirv_fix_envision_url")) {
  function sirv_fix_envision_url($url, $w, $h, $crop = true)
  {
    $clsUrl = (stripos($url, '?') === false) ? $url : preg_replace('/\?.*/is', '', $url);
    $mdfyUrl = '';
    if ($crop) {
      $mdfyUrl = "$clsUrl?w=$w&h=$h&scale.option=fill&cw=$w&ch=$h&cx=center&cy=center";
    } else {
      $mdfyUrl = "$clsUrl?w=$w&h=$h";
    }

    return $mdfyUrl;
  }
}


add_filter('fl_builder_render_css', 'sirv_builder_render_css', 10, 3);
function sirv_builder_render_css($css, $nodes, $global_settings){
  return sirv_the_content($css, 'css');
}


add_filter('rest_request_before_callbacks', 'sirv_rest_request_before_callbacks', 10, 4);
function sirv_rest_request_before_callbacks($response, $handler, $request){
  global $sirv_is_rest_rejected;

  if(Utils::startsWith($request->get_route(), '/wp/v2/media')){
    $referer = $request->get_header_as_array('referer');

    if( !empty($referer) && sirv_is_admin_url($referer[0]) ){
      $sirv_is_rest_rejected = true;
    }
  }else{
    $sirv_is_rest_rejected = false;
  }

  return $response;
}


add_action('init', 'sirv_init', 20);
function sirv_init(){
  global $isAdmin;
  global $isLoggedInAccount;

  remove_filter('wp_resource_hints', 'et_disable_emojis_dns_prefetch', 10, 2);
  add_filter('wp_resource_hints', 'sirv_fix_et_disable_emojis_dns_prefetch', 10, 2);

  $prevent_thumbs = get_option('SIRV_PREVENT_CREATE_WP_THUMBS');

  if (isset($prevent_thumbs) && $prevent_thumbs === 'on' ) {
    resizeHelper::addPreventWPResizeOnUploadFilter();
  }

  $isExclude = Exclude::excludeSirvContent($_SERVER['REQUEST_URI'], 'SIRV_EXCLUDE_PAGES');

  if (is_admin() || $isAdmin) return;


  if (get_option('SIRV_ENABLE_CDN') === '1' && $isLoggedInAccount && !$isExclude) {
    add_filter('wp_get_attachment_image_src', 'sirv_wp_get_attachment_image_src', 10000, 4);
    //add_filter('image_downsize', "sirv_image_downsize", 10000, 3);
    add_filter('wp_get_attachment_url', 'sirv_wp_get_attachment_url', 10000, 2);
    add_filter('wp_calculate_image_srcset', 'sirv_add_custom_image_srcset', 10, 5);
    add_filter('vc_wpb_getimagesize', 'sirv_vc_wpb_filter', 10000, 3);
    add_filter('envira_gallery_image_src', 'sirv_envira_crop', 10000, 4);
    add_filter('wp_prepare_attachment_for_js', 'sirv_wp_prepare_attachment_for_js', 10000, 3);

    if (get_option('SIRV_USE_SIRV_RESPONSIVE') === '1') {
      add_filter('wp_get_attachment_image_attributes', 'sirv_do_responsive_images', 99, 3);
    }
  }

  //diff hooks to parse non standard content
  if (get_option('SIRV_ENABLE_CDN') === '1' && get_option('SIRV_PARSE_STATIC_IMAGES') == '1') {
    //add_filter('the_content', 'sirv_parse_non_standard_content', PHP_INT_MAX - 10);
    //add_filter('tve_thrive_shortcodes', 'sirv_parse_non_standard_content', PHP_INT_MAX - 10);
    //parse inline css from smart slider 3
    add_filter('wordpress_prepare_output', 'sirv_parse_non_standard_content', PHP_INT_MAX - 10);
  }

  //add_action('wp_head', 'sirv_buffer_start', 0);
  add_action('wp_loaded', 'sirv_buffer_start', 0);
  add_action('wp_footer', 'sirv_buffer_end', PHP_INT_MAX - 10);

  add_action('wp_enqueue_scripts', 'sirv_enqueue_frontend_scripts', PHP_INT_MAX - 100);

  $sirv_custom_css = get_option('SIRV_CUSTOM_CSS');
  if (!empty($sirv_custom_css)) {
    wp_register_style('sirv-custom-css', false);
    wp_enqueue_style('sirv-custom-css');

    wp_add_inline_style('sirv-custom-css', $sirv_custom_css);
  }
}


function sirv_parse_non_standard_content($content){
  return sirv_the_content($content, 'content');
}


//as filter wp_get_attachment_thumb_url doesn't work, need use filter image_downsize to get correct links with resized images from SIRV
function sirv_image_downsize($downsize, $attachment_id, $size){

  if (empty($downsize)) return false;

  $wp_sizes = sirv_get_image_sizes();
  $img_sizes = array();
  $image = wp_get_attachment_url($attachment_id);

  $isExclude = Exclude::excludeSirvContent($image, 'SIRV_EXCLUDE_FILES');
  if($isExclude) return $downsize;

  if (empty($image) || empty($size) || $size == 'full' || (is_array($size) && empty($size[0]) && empty($size[1]))) {
    return false;
  }

  if (is_string($size) && !empty($size)) {
    if (!empty($wp_sizes) && in_array($size, array_keys($wp_sizes))) {
      $img_sizes['width'] = $wp_sizes[$size]['width'];
      $img_sizes['height'] = $wp_sizes[$size]['height'];
      $img_sizes['isCrop'] = (bool) $wp_sizes[$size]['crop'];
    }
  } elseif (is_array($size)) {
    $img_sizes['width'] = $size[0];
    $img_sizes['height'] = $size[1];
    $img_sizes['isCrop'] = $size[0] === $size[1] ? true : false;
  }

  if (empty($img_sizes)) return false;

  $scaled_img = $image . sirv_get_scale_pattern($img_sizes['width'], $img_sizes['height'], $img_sizes['isCrop']);

  return array($scaled_img, $img_sizes['width'], $img_sizes['height']);
}


function sirv_wp_get_attachment_thumb_url($url, $post_id){
  return $url;
}


function sirv_envira_crop($resized_image, $id, $item, $data){

  if (is_admin()) return $resized_image;

  if (sirv_is_sirv_item($resized_image)) {
    preg_match('/(^http.*)-(\d{2,4})x(\d{2,4})(_[a-z]{1,2})?(\..*)/is', $resized_image, $m);

    $orig_url = '';
    $w = 0;
    $h = 0;
    $isCrop = false;

    if (!empty($m)) {
      $orig_url = $m[1] . $m[5];
      $w = $m[2];
      $h = $m[3];
      $isCrop = $m[4] !== '' ? true : false;
    }

    if ($orig_url !== '' && $isCrop) {
      $crop_direction = sirv_crop_direction($m[4]);
      $pattern_crop = '?w=' . $w . '&h=' . $h . '&scale.option=fill&canvas.width=' . $w . '&canvas.height=' . $h;
      $resized_image = $orig_url . $pattern_crop . $crop_direction;
    }
  }
  return $resized_image;
}

function sirv_is_sirv_item($url){
    if( empty($url) ) return false;
    //$sirv_cdn_url = get_option('SIRV_CDN_URL');
    $sirv_cdn_url = sirv_get_cached_cdn_url();
    $sirv_url = empty($sirv_cdn_url) ? 'sirv.com' : $sirv_cdn_url;
    return stripos($url, $sirv_url) !== false;
  }


function sirv_crop_direction($type){
  $param_crop_coords = '';

  switch ($type) {
    case '_c':
      $param_crop_coords = '&canvas.position=center';
      break;
    case '_tl':
      $param_crop_coords = '&canvas.position=northeast';
      break;
    case '_tr':
      $param_crop_coords = '&canvas.position=northwest';
      break;
    case '_bl':
      $param_crop_coords = '&canvas.position=southwest';
      break;
    case '_br':
      $param_crop_coords = '&canvas.position=southeast';
      break;
  }

  return $param_crop_coords;
}


function sirv_enqueue_frontend_scripts(){
  global $isLoggedInAccount;
  //wp_enqueue_style('sirv_frontend_style', SIRV_PLUGIN_SUBDIR_URL_PATH . 'css/sirv-responsive-frontend.css');

  add_action('wp_print_styles', 'sirv_print_front_styles');
  add_action('wp_print_footer_scripts', 'sirv_print_front_scripts', PHP_INT_MAX - 1000);

  if (get_option('SIRV_ENABLE_CDN') === '1' && $isLoggedInAccount){
      //wp_add_inline_style('sirv_frontend_style', $css_images_styles);
      add_action('wp_print_styles', 'sirv_print_css_images');
  }
  //wp_enqueue_script('sirv_miscellaneous', SIRV_PLUGIN_SUBDIR_URL_PATH . 'js/wp-sirv-diff.js', array('jquery'), '1.0.0', true);
}


function sirv_print_front_styles(){
  sirv_add_file_to_inline_code(SIRV_PLUGIN_SUBDIR_PATH . 'css/sirv-responsive-frontend.css', false, 'style');
}


function sirv_print_css_images(){
  $css_images_styles = get_option('SIRV_CSS_BACKGROUND_IMAGES');
  if (isset($css_images_styles) && !empty($css_images_styles)) {
    sirv_add_file_to_inline_code(false, $css_images_styles, 'style');
  }
}


function sirv_print_front_scripts(){
  sirv_add_file_to_inline_code(SIRV_PLUGIN_SUBDIR_PATH . 'js/wp-sirv-diff.js', false, 'script');
}


function sirv_add_file_to_inline_code($abs_path, $data, $tag){
  echo "<{$tag}>" . PHP_EOL;
  if( $abs_path ){
    include($abs_path);
  }else{
    echo $data;
  }
  echo "</{$tag}>"  . PHP_EOL;
}


function sirv_get_cached_cdn_url(){
  global $sirv_cdn_url;

  if (!isset($sirv_cdn_url)) {
    $sirv_cdn_url = get_option('SIRV_CDN_URL');
  }
  return $sirv_cdn_url;
}


function sirv_do_responsive_images($attr, $attachment, $size){

  if( is_admin() ||  !sirv_is_sirv_item($attr['src']) ) return $attr;

  $isExclude = Exclude::excludeSirvContent($attr['src'], 'SIRV_EXCLUDE_FILES');

  //get img src like sirv url and need convert it to relative disc path
  $attrsToCheck = $attr;
  $attrsToCheck['src'] = str_replace(home_url(), '', $attachment->guid);
  $isResponsiveExclude = Exclude::excludeSirvContent($attrsToCheck, 'SIRV_EXCLUDE_RESPONSIVE_FILES');

  if ($isResponsiveExclude || $isExclude) return $attr;

  $placeholder_type = get_option('SIRV_RESPONSIVE_PLACEHOLDER');
  $img_size = sirv_get_responsive_size($attr['src'], $size);

  $url = sirv_get_parametrized_url($attr['src']);
  $plchldr_data  = sirv_prepare_placeholder_data($url, $img_size, $placeholder_type);

  $attr['class'] = isset($attr['class']) ? $attr['class'] . ' ' . $plchldr_data['classes'] : $plchldr_data['classes'];
  $attr['data-src'] = $url;

  if ($plchldr_data['url']) {
    $attr['src'] = $plchldr_data['url'];
  }

  unset($attr['srcset']);
  unset($attr['sizes']);

  return $attr;
}


function sirv_get_responsive_size($url, $size){
  $calc_size = array("width" => 0, "height" => 0);

  $size = array();

  if( isset($size) && !empty($size) ){
    $wp_sizes = sirv_get_image_sizes(false);

    if (is_array($size)) {
      $calc_size['width'] = $size[0];
      $calc_size['height'] = $size[1];
    } else {
      if ( isset($wp_sizes[$size]) ) {
        if($wp_sizes[$size]['width'] != 0) $calc_size['width'] = $wp_sizes[$size]['width'];
        if($wp_sizes[$size]['height'] != 0) $calc_size['height'] = $wp_sizes[$size]['height'];
      }
    }
  }

  if( $calc_size['width'] == 0 && $calc_size['height'] == 0 ){
    $url_query = '';
    $url_params = array();

    $url_query = parse_url($url, PHP_URL_QUERY);

    if( !empty($url_query) ) parse_str($url_query, $url_params);

    if( isset($url_params['w']) ) $calc_size['width'] = $url_params['w'];
    if( isset($url_params['h']) ) $calc_size['height'] = $url_params['h'];
  }

  return $calc_size;
}


function sirv_prepare_placeholder_data($url, $size, $placeholder_type){
  $placeholder_data = array('url' => '', 'width' => '', 'classes' => 'Sirv');


  if ($size['width'] != 0) {
    $placeholder_data['width'] = $size['width'];
  }

  if ($placeholder_data['width']) {
    $placeholder_data['url'] = sirv_get_placehodler_url($placeholder_type, $url, $placeholder_data['width']);

    if($placeholder_type == 'blurred'){
      $placeholder_data['classes'] .= ' placeholder-blurred';
    }
  }

  return $placeholder_data;
}


function sirv_get_placehodler_url($placeholder_type, $url, $width){
  //$svg_placehodler = "data:image/gif;base64, R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7";
  //$svg_placehodler_grey = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAAAAAA6fptVAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAKSURBVAgdY3gPAADxAPAXl1qaAAAAAElFTkSuQmCC";
  $placeholder_grey_params = '?q=1&w=10&colorize.color=efefef';

  $placeholder_url = '';

  $has_url_params = stripos($url, '?') !== false ? true : false;
  $delimiter = $has_url_params ? '&' : '?';

  switch ($placeholder_type) {
    case 'image':
      $placeholder_url = $url . $delimiter . 'w=' . sirv_get_scaled_image_width($width, 35) . '&q=30';
      break;
    case 'grey_shape':
      $placeholder_url= $url . $placeholder_grey_params;
      break;
    case 'blurred':
      $size = $width < 50 ? $width : intval($width / 10);
      $placeholder_url= $url . $delimiter . 'w=' . $size . '&q=20';
      break;
  }

  return $placeholder_url;
}


//scale in persents(1-100) related to original image. Scale equal to 30% means that we get image width less on 70%;
function sirv_get_scaled_image_width($width, $scale = 30){
  if( $width > 0 ){
    return intval($width * $scale / 100);
  }

  return $width;
}

//-----------------------------------------------------------------------------------------------------
function sirv_the_content($content, $type='content'){
  //TODO: add cache for files;
  //TODO: support for relative images like "img/image.jpg" or "../img/image.png"?
  //TODO: create? DB cache for pages to prevent request to DB to find image. Store sirv urls or ids if count of parsed images equal count of cache then use cache.
  //DONE: change $m to more comfortable array use flag PREG_SET_ORDER ?
  //DONE: catch all images and not only scaled;
  //DONE: on modify image save new img path
  //TODO: prevent create thumbnails or modify image;
  //TODO: get exclude content once and then check it in loop?
  //TODO: run sync func sirv_cache_sync_data instead of wp_get_attachment_image_src? using wp_get_attachment_image_src because filtered size
  //TODO: now parsing only img tags and does not parse videos or urls from css.
  //TODO: try find attachment id in sirv table if not try to find in posts table and save in sirv table. Also need remove postifx -scaled from image if exists.

  if (is_admin()) return $content;

  //global $logger;
  //$logger->time_start("sirv_the_content");

  global $wpdb;

  $uploads_dir = wp_get_upload_dir();
  $root_url_images_path = $uploads_dir['baseurl'];
  $root_disc_images_path = $uploads_dir['basedir'];

  $quoted_base_url = preg_replace('/https?\\\:/ims', '(?:https?\:)?', preg_quote($root_url_images_path, '/'));

  $ext_types = array();

  if( get_option("SIRV_PARSE_VIDEOS") == 'on'){
    $ext_types[] = "video";
  }

  if( get_option("SIRV_PARSE_STATIC_IMAGES") == '1'){
    $ext_types[] = "image";
  }

  $file_exts = sirv_get_file_extensions_by_type($ext_types);
  $file_exts_str = implode("|", $file_exts);

  /* $image_pattern = '/<img[^<]+?(' . $quoted_base_url . '\/([^\s]*?)(\-[0-9]{1,}(?:x|&#215;)[0-9]{1,})?\.((?:' . $file_exts_str . ')))[^>]+?>/ims'; */
  $image_short_pattern = '/<img[^<]+?('. $quoted_base_url .'\/[^\s]*?\.(?:'. $file_exts_str .'))[^>]+?>/ims';
  $all_pattern = '/' . $quoted_base_url . '\/([^\s]*?)(\-[0-9]{1,}(?:x|&#215;)[0-9]{1,})?\.((?:' . $file_exts_str . '))/ims';

  $images_metadata = array();

  switch ($type) {
    case 'content':
      /* <img[^<]+?((?:https?\:)?\/\/eynna\-hair\.ba\/wp\-content\/uploads\/([^\s]*?)(\-[0-9]{1,}(?:x|&#215;)[0-9]{1,})?\.((?:tif|tiff|bmp|jpg|jpeg|gif|png|apng|svg|webp|heif|avif|ico)))[^>]+?> */
      /* preg_match_all('/<img[^<]+?(' . $quoted_base_url . '\/([^\s]*?)(\-[0-9]{1,}(?:x|&#215;)[0-9]{1,})?\.((?:' . $file_exts_str . ')))[^>]+?>/ims', $content, $m, PREG_SET_ORDER); */
      preg_match_all($all_pattern, $content, $m, PREG_SET_ORDER);
      preg_match_all($image_short_pattern, $content, $images_html, PREG_SET_ORDER);

      $images_metadata = sirv_get_images_metadata($images_html);
      break;
    case 'css':
      preg_match_all($all_pattern, $content, $m);
      break;
  }

  if (!empty($m)) {
    foreach ($m as $item) {
      list($full_url, $relative_path_without_ext, $size_str, $ext) = $item;

      $isExclude = Exclude::excludeSirvContent($full_url, 'SIRV_EXCLUDE_FILES');
      if ( $isExclude ) continue;

      $img_attrs = array();

      if($type = 'content'){
        if( isset($images_metadata[$full_url]) ){
          $img_attrs = $images_metadata[$full_url];
        }
      }

      $relative_filepath = $relative_path_without_ext . '.' . $ext;
      $scaled_relative_path = $relative_path_without_ext . '-scaled.' . $ext;

      $is_video = in_array($ext, sirv_get_file_extensions_by_type("video"));

      //TODO: find img data in sirv_images instead of get_post_meta. Maybe it will be quiqlier? but to mach work
      //TODO: add indexes to the img_path and sirv_path to find needed img faster? Can be issue with insert new rows.
      /* $sirv_images_table = $wpdb->prefix . 'sirv_images';
      $attachment = $wpdb->get_row(
        $wpdb->prepare(
          "SELECT * FROM $sirv_images_table WHERE img_path = '%s' OR img_path = '%s'",
          array('/'. $relative_filepath, '/'. $scaled_relative_path)
        ),
        ARRAY_A
      ); */

      $attachment = $wpdb->get_row(
        $wpdb->prepare(
         "SELECT * FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value = '%s' OR meta_value = '%s'",
        array($relative_filepath, $scaled_relative_path))
      , ARRAY_A);

      if ( !empty($attachment) && !empty($attachment['post_id']) ) {
        $file_url = '';
        $file_disc_path = wp_normalize_path($root_disc_images_path .'/'. $relative_filepath);

        if( $is_video ){
          $file_url = sirv_cache_sync_data($attachment['post_id'], false);
        }else{
          //check if image without size non exists than we parsed original image without size in his name but with something that looks like WP size in name. In this case we should return original image instead of cropped with incorrect size.
          $has_size = !empty($size_str) || (isset($img_attrs['width']) || isset($img_attrs['height']));
          if ( !file_exists($file_disc_path) || !$has_size ) {
            $resized = wp_get_attachment_image_src($attachment['post_id'], 'full');
            $file_url = $resized[0];
          } else {
            $w = 0;
            $h = 0;

            if( isset($img_attrs['width']) || isset($img_attrs['height']) ){
              $w = isset($img_attrs['width']) ? $img_attrs['width'] : 0;
              $h = isset($img_attrs['height']) ? $img_attrs['height'] : 0;
            }else{
              list($w, $h) = explode('x', str_replace('-', '', str_replace('&#215;', 'x', $size_str)));
              $img_attrs['width'] = $w;
              $img_attrs['height'] = $h;
            }
            try {
                $resized = wp_get_attachment_image_src($attachment['post_id'], array($w, $h));
                $file_url = $resized[0];
            } catch (Exception $e) {
              if (IS_DEBUG) {
                global $logger;

                $logger->error($e, 'func sirv_the_content')->filename('error.log')->write();
              }
              $file_url = '';
            }
          }
        }

        if ($file_url != '') {
            $content = str_replace($full_url, $file_url, $content);
        }
      }
    }
  }

  //$logger->time_end("sirv_the_content");
  return $content;
}


function sirv_get_images_metadata($html_data){
  $images_metadata = array();

  if ( ! empty($html_data) ) {
    for ($i=0; $i < count($html_data); $i++) {
      list($img_html, $img_url) = $html_data[$i];
      preg_match_all('/\s(\w*)=\"([^"]*)\"/ims', $img_html, $matches_img_attrs, PREG_SET_ORDER);
      $images_metadata[$img_url] = sirv_convert_matches_to_assoc_array($matches_img_attrs);
    }
  }

  return $images_metadata;
}


function sirv_convert_matches_to_assoc_array($matches){
  $assoc_array = array();

  for ($i=0; $i < count($matches); $i++) {
    $assoc_array[$matches[$i][1]] = $matches[$i][2];
  }

  return $assoc_array;
}


function sirv_prepare_responsive_data($img_url, $attrs, $placeholder_type){
  $responsive_attrs = $attrs;

  $placeholder_url = sirv_get_placehodler_url($placeholder_type, $img_url, $attrs['width']);

  $img_classes = isset($responsive_attrs['class']) ? $responsive_attrs['class'] : '';
  $responsive_classes = $placeholder_type === 'blurred' ? 'Sirv placeholder-blurred' : 'Sirv';
  $additional_space = empty($img_classes) ? '' : ' ';

  $responsive_attrs['class'] = $img_classes . $additional_space . $responsive_classes;
  $responsive_attrs['data-src'] = $img_url;
  $responsive_attrs['src'] = $placeholder_url;
  unset($responsive_attrs['srcset']);
  unset($responsive_attrs['sizes']);

  return $responsive_attrs;
}


function sirv_img_attrs_to_str($attrs){
  $attr_str = '';

  foreach ($attrs as $attr_name => $attr_value) {
    $attr_str .= "$attr_name=\"$attr_value\" ";
  }

  return $attr_str;
}


function sirv_render_img_tag($attrs){
  $attr_str = sirv_img_attrs_to_str($attrs);

  return "<img $attr_str>";
}


//------------------------------------------------------------------------------------------------------------------
/* add_filter( 'wp_embed_handler_video', 'sirv_test_video');
function sirv_test_video(string $video, array $attr, string $url, array $rawattr){

  sirv_debug_msg($video);
  sirv_debug_msg($attr);
  sirv_debug_msg($url);
  sirv_debug_msg($rawattr);

  return $video;
} */


//------------------------------------------------------------------------------------------------------------------
function sirv_wp_prepare_attachment_for_js($response, $attachment, $meta){
  if (!empty($response['sizes'])) {
    if (preg_match('/^image/ims', $response['type'])) {
      foreach ($response['sizes'] as $size => $image) {
        $response['sizes'][$size]['url'] = preg_replace('/(.*)(?:\-[0-9]{1,}x[0-9]{1,}(\.[a-z]{1,})$)/ims', '$1$2?w=' . $image['width'] . '&h=' . $image['height'], $image['url']);
      }
    }
  }
  return $response;
}


function sirv_is_rest(){
  if ( defined('REST_REQUEST') && REST_REQUEST || isset($_GET['rest_route']) && strpos($_GET['rest_route'], '/', 0) === 0 ){
    return true;
  }

  return false;
}


function sirv_is_rest_rejected(){
  global $sirv_is_rest_rejected;

  if(sirv_is_rest() && $sirv_is_rest_rejected){
    return true;
  }

  return false;
}


function sirv_wp_get_attachment_image_src($image, $attachment_id, $size, $icon){
  global $isAjax;

  if( sirv_is_rest_rejected() ) return $image;

  if ( (is_admin() && !$isAjax) || !is_array($image) || empty($attachment_id) || empty($image[0]) || !sirv_is_allowed_ext($image[0])) return $image;

  if( sirv_is_sirv_item($image[0])){
    $post = get_post($attachment_id);
    if ( isset($post->post_author) && (int) $post->post_author === 5197000 ) {
      $isCrop = isset($image[3]) ? (bool) $image[3] : false;
      $image[0] = sirv_get_parametrized_url($image[0], $size, $isCrop, $attachment_id);

      return $image;
    }
  }

  $isExclude = Exclude::excludeSirvContent($image[0], 'SIRV_EXCLUDE_FILES');

  if ( $isExclude ) return $image;

  $paths = sirv_get_cached_wp_img_file_path($attachment_id);

  if ( empty($paths) || isset($paths['wrong_file']) || !isset($paths['img_file_path']) ) return $image;

  $root_url_images_path = $paths['url_images_path'];

  //check if get_option('siteurl') return http or https
  if (stripos(get_option('siteurl'), 'https://') === 0) {
    $root_url_images_path = str_replace('http:', 'https:', $root_url_images_path);
  }

  list(, $image_width, $image_height) = $image;
  $isCrop = isset($image[3]) ? (bool) $image[3] : false;

  $cdn_image_url = sirv_cache_sync_data($attachment_id, false);
  if (!empty($cdn_image_url)) {
    $wp_sizes = sirv_get_image_sizes();

    $image[0] = sirv_scale_image($cdn_image_url, $image_width, $image_height, $size, $wp_sizes, $paths['img_file_path'], $isCrop);
  }

  return $image;
}


function sirv_get_parametrized_url($sirv_url, $size = null, $isCrop=false){
  $uploads_dir_info = wp_get_upload_dir();
  $url_images_path = $uploads_dir_info['baseurl'] . '/';
  //$sirv_url = preg_replace('/(^[^\s]*?)\-([0-9]{1,}(?:x|&#215;)[0-9]{1,})(\.[a-z]{3,4})/i', "$1$3", $sirv_url);
  $sirv_image = str_replace($url_images_path, '', $sirv_url);

  $sirv_image = sirv_clean_get_params($sirv_image);
  $sirv_item_type_data = SirvProdImageHelper::get_sirv_item_type($sirv_image);

  if( empty($size)){
    if( $sirv_item_type_data['sirv_type'] == 'video' ) return sirv_add_profile($sirv_image . "?thumbnail=500");
    if( $sirv_item_type_data['sirv_type'] == 'spin' ) return sirv_add_profile($sirv_image . "?thumb");

    return sirv_add_profile($sirv_image);
  }

  $item_show_pattern = '';

  if( $sirv_item_type_data['sirv_type'] == 'video' ){
    $image = sirv_get_correct_item_size($size, $isCrop);
    $url = $sirv_image . "?thumbnail=" . $image['width'];
    return sirv_add_profile($url);

  }else if( $sirv_item_type_data['sirv_type'] == 'spin' ){
    $item_show_pattern = "?thumb";
  }

  $sirv_image_with_params = sirv_get_scale_pattern_from_wp_size($sirv_image . $item_show_pattern, $size);

  return sirv_add_profile($sirv_image_with_params);
}


function sirv_get_file_extensions_by_type($ext_types){
  $extensions_by_type = Utils::get_file_extensions();

  $extensions_keys = array_keys($extensions_by_type);

  $exts = array();

  if(empty($ext_types)) return $exts;

  if(is_string($ext_types)){
    if(in_array($ext_types, $extensions_keys)) return $extensions_by_type[$ext_types];
    return $exts;
  }

  if(count($ext_types) === 1) return $extensions_by_type[$ext_types[0]];

  foreach ($ext_types as $ext_type) {
    if(! in_array($ext_type, $extensions_keys)) continue;

    $exts = array_merge($exts, $extensions_by_type[$ext_type]);
  }

  return $exts;
}


function sirv_is_allowed_ext($url){
  if(empty($url)) return false;

  try{
    $ext_types = array('image');

    if ( get_option("SIRV_PARSE_VIDEOS") == 'on') {
      $ext_types[] = 'video';
    }

    $accessible_ext = sirv_get_file_extensions_by_type($ext_types);
    $url_path = parse_url($url, PHP_URL_PATH);
    $current_ext = pathinfo($url_path, PATHINFO_EXTENSION);
  } catch (Exception $e) {
    return false;
  }

  return in_array($current_ext, $accessible_ext);
}


function sirv_combine_allowed_ext($exts_list){
  $combined_ext = array();

  for ($i=0; $i < count($exts_list); $i++) {
    $combined_ext = array_merge($combined_ext, $exts_list[$i]);
  }

  return $combined_ext;
}


function sirv_clean_get_params($url){
  return (stripos($url, '?') === false) ? $url : preg_replace('/\?.*/is', '', $url);
}


function clean_protocol($url){
  return preg_replace('/^https?/is', '', $url);
}


function sirv_wp_get_attachment_url($url, $attachment_id){
  global $isAjax;
  $isExclude = Exclude::excludeSirvContent($url, 'SIRV_EXCLUDE_FILES');

  if( (is_admin() && !$isAjax) || $isExclude || !sirv_is_allowed_ext($url) || sirv_is_rest_rejected() ) return $url;
  //if( (is_admin() && !$isAjax) || $isExclude ) return $url;

  if (sirv_is_sirv_item($url)) {
    $post = get_post($attachment_id);
    if ( isset($post->post_author) && (int) $post->post_author === 5197000 ) {
      $url = sirv_get_parametrized_url($url);

      return $url;
    }
  }

  $cdn_image_url = sirv_cache_sync_data($attachment_id, false);

  if (!empty($cdn_image_url)) {
    $url = sirv_add_profile($cdn_image_url);
  }

  return $url;
}


function sirv_calculate_image_sizes($sizes, $size, $image_src, $image_meta, $attachment_id){
  return $sizes;
}


function sirv_add_custom_image_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id){
  global $isAjax;

  /*
    function that get masterImageSize from $image_meta, if can't get then return null
  */

  if( (is_admin() && !$isAjax) || !is_array($sources) || empty($attachment_id) || !sirv_is_allowed_ext($image_src) ) return $sources;

  if (sirv_is_sirv_item($image_src)) {

    $post = get_post($attachment_id);
    if ( isset($post->post_author) && (int) $post->post_author === 5197000 ) {
      foreach ($image_meta["sizes"] as $size_name => $size_data) {
        $w_size = $size_data['width'];
        $h_size = $size_data['height'];

        $wp_size = array($w_size, $h_size);

        if( isset($sources[$w_size]) ) $sources[$w_size]['url'] = sirv_get_parametrized_url($image_src, $wp_size);
      }

      $max_size = $image_meta["width"];
      if (isset($sources[$max_size])) $sources[$max_size]['url'] = sirv_get_parametrized_url($image_src);

      return $sources;
    }
  }

  $isExclude = isset($image_src) ? Exclude::excludeSirvContent($image_src, 'SIRV_EXCLUDE_FILES') : false;
  if ( $isExclude ) return $sources;

  $paths = sirv_get_cached_wp_img_file_path($attachment_id);

  if (empty($paths) || isset($paths['wrong_file'])) return $sources;

  $image = sirv_cache_sync_data($attachment_id);

  if ( $image ) {

    $wp_sizes = sirv_get_image_sizes();

    $image_exts = sirv_get_file_extensions_by_type('image');
    $image_exts_str = implode("|", $image_exts);

    $regexp_size_pattern = '/^[^\s]*?\-([0-9]{1,})(?:x|&#215;)([0-9]{1,})\.(' . $image_exts_str . ')/i';

    $master_image_size = ( isset($image_meta['sizes']) && !empty($image_meta['sizes']) ) ? sirv_get_master_image_size($image_meta["sizes"], $size_array, $wp_sizes) : null;

    $original_image_path = $paths['img_file_path'];
    $image_sizes = array_keys($sources);
    $image_width = '';
    $image_height = '';
    $size_name = null;

    $max_size = $size_array[0];

    if (is_numeric($max_size) && $max_size > 0) {
      if (!array_key_exists($max_size, $sources)) {
        $sources[$max_size] = array('url' => $image_src, 'descriptor' => 'w', 'value' => $max_size);
      }
    }

    foreach ($image_sizes as $size) {
      preg_match($regexp_size_pattern, $sources[$size]['url'], $m);
      if(!empty($m)){
        list(, $image_width, $image_height) = $m;
        $size_name = array($image_width, $image_height);
      }else{
        if ($image_meta['width'] == $size && is_numeric($image_meta['height'])) {
          $image_width = $image_meta['width'];
          $image_height = $image_meta['height'];
        } else {
          $size_name = sirv_get_size_name($size, $image_meta['sizes']);
          if (isset($size_name) && !is_null($size_name)) {
            $image_width = $image_meta['sizes'][$size_name]['width'];
            $image_height = $image_meta['sizes'][$size_name]['height'];
          } else {
            $image_width = $size;
            $image_height = $size;
          }
        }
      }

      $sources[$size]['url'] = sirv_scale_image($image, $image_width, $image_height, $size_name, $wp_sizes, $original_image_path, true, $master_image_size);
    }
  }
  return $sources;
}

//find first equal size. Can be an issue when few sizes have the same height and width
function sirv_get_master_image_size($meta_sizes, $size_array, $wp_sizes){
  $master_image_size = null;

  $master_image_size = sirv_find_size_name_size_array($meta_sizes, $size_array);

  if( !is_null($master_image_size) ){
    $size_names = array_keys($wp_sizes);
    if( !in_array($master_image_size, $size_names) ){
      $master_image_size = sirv_find_size_name_size_array($wp_sizes, $size_array);
    }
  }

  return $master_image_size;
}


function sirv_find_size_name_size_array($sizes, $size_array){
  $size_name = null;

  list($master_width, $master_height) = $size_array;

  foreach ($sizes as $size_name => $size) {
    if ($master_width == $size["width"] && $master_height == $size["height"]) {
      $size_name = $size_name;
      break;
    }
  }

  return $size_name;


}


function sirv_vc_wpb_filter($img, $img_id, $attributes){

  if (is_admin()) return $img;

  if ($attributes['thumb_size'] == 'full' || in_array($attributes['thumb_size'], array_values(get_intermediate_image_sizes()))) return $img;

  require_once(ABSPATH . 'wp-admin/includes/file.php');

  $sirv_folder = get_option('SIRV_FOLDER');

  $uploads_dir = wp_get_upload_dir();
  $root_images_path = $uploads_dir['basedir'];
  $sirv_root_path = sirv_get_sirv_path($sirv_folder);

  preg_match('/(\d{2,4})\s?x\s?(\d{2,4})/is', $attributes['thumb_size'], $sizes);
  $img_sizes = array();
  $img_sizes['width'] = $sizes[1];
  $img_sizes['height'] = $sizes[2];

  $original_image_url = preg_replace('/\?scale.*/is', '', $img['p_img_large'][0]);
  $original_image_path =  str_replace($sirv_root_path, $root_images_path, $original_image_url);

  $scale_pattern = sirv_get_scale_pattern($img_sizes['width'], $img_sizes['height'], true, $original_image_path);
  $size_pattern = $sizes[1] . 'x' . $sizes[2];
  $img['thumbnail'] = preg_replace('/-' . $size_pattern . '(\.[jpg|jpeg|png|gif]*)/is', '$1' . $scale_pattern, $img['thumbnail']);
  $img['p_img_large'][0] = $original_image_url;

  return $img;
}


function sirv_get_image_size($size){
  $sizes = array();
  $sizes['width'] = get_option("{$size}_size_w'");
  $sizes['heigh'] = get_option("{$size}_size_h'");
  $sizes['crop'] = (bool)get_option("{$size}_crop'");
}

function sirv_get_image_sizes($isRemoveZeroSizes = true){

  $_wp_additional_image_sizes = wp_get_additional_image_sizes();

  $sizes = array();

  foreach (get_intermediate_image_sizes() as $_size) {
    if (in_array($_size, array('thumbnail', 'medium', 'medium_large', 'large'))) {
      $sizes[$_size]['width']  = get_option("{$_size}_size_w");
      $sizes[$_size]['height'] = get_option("{$_size}_size_h");
      $sizes[$_size]['crop']   = (bool) get_option("{$_size}_crop");
    } elseif (isset($_wp_additional_image_sizes[$_size])) {
      $sizes[$_size] = array(
        'width'  => $_wp_additional_image_sizes[$_size]['width'],
        'height' => $_wp_additional_image_sizes[$_size]['height'],
        'crop'   => (bool)$_wp_additional_image_sizes[$_size]['crop'],
      );
    }

    if($isRemoveZeroSizes){
      //wp has sizes where width or height can be 0. This is correct behavior.
      //skip only sizes with both 0
      if( ($sizes[ $_size ]['width'] == 0) && ($sizes[ $_size ]['height'] == 0) ) unset( $sizes[ $_size ] );
    }

  }

  return $sizes;
}


/* function sirv_get_original_image($image_url, $paths){
    $sirv_root_path = $paths['sirv_root_path'];
    $root_images_path = $paths['root_images_path'];

    $pattern = '/(.*?)[-|-]\d{1,4}x\d{1,4}(\.[a-zA-Z]{2,5})/is';
    $tested_image = preg_replace($pattern, '$1$2', $image_url);
    $image_path_on_disc = str_replace($sirv_root_path, $root_images_path, $tested_image);
    $orig_image = array();
    if(file_exists($image_path_on_disc)){
        $orig_image['original_image_url'] = $tested_image;
        $orig_image['original_image_path'] = $image_path_on_disc;

    }else{
        $orig_image['original_image_url'] = $image_url;
        $orig_image['original_image_path'] = str_replace($sirv_root_path, $root_images_path, $image_url);
    }
    return $orig_image;
} */


function sirv_get_original_sizes($original_image_path){
  $sizes = array('width' => 0, 'height' => 0);

  if ($original_image_path && file_exists($original_image_path)) {
    $image_dimensions = @getimagesize($original_image_path);
    if( !empty($image_dimensions) && is_array($image_dimensions) ){
      $sizes['width'] = $image_dimensions[0];
      $sizes['height'] = $image_dimensions[1];
    }
  }

  return $sizes;
}


function sirv_scale_image($image_url, $image_width, $image_height, $size, $wp_sizes, $original_image_path, $isCrop = false, $master_image_size=null){

  //TODO:? make func cachable
  //$wp_sizes = sirv_get_image_sizes();

  $image_url = sirv_clean_get_params($image_url);

  $get_param_symbol = (stripos($image_url, '?') === false) ? '?' : '&';

  //fix if width or height received from sirv_wp_get_attachment_image_src == 0
  if ($image_width == 0 || $image_height == 0 || $image_width >= 3000 || $image_height >= 3000) {
    if (!empty($wp_sizes) && !is_null($size) && in_array($size, array_keys($wp_sizes))) {
      $image_width = $wp_sizes[$size]['width'];
      $image_height = $wp_sizes[$size]['height'];
    }
  }

  if(!is_null($master_image_size)){
    $cropType = sirv_get_crop_type($master_image_size, $wp_sizes, $isCrop);
  }else{
    $cropType = sirv_get_crop_type($size, $wp_sizes, $isCrop);
  }

  $url = $image_url . sirv_get_scale_pattern($image_width, $image_height, $cropType, $original_image_path,  $get_param_symbol);

  return sirv_add_profile($url);
}


function sirv_get_crop_type($size, $sizes, $isCrop){
  //cropType: none | wp_crop | sirv_crop
  $cropType = 'none';

  if ($size == 'full' || empty($size)) return $cropType;

  $crop_data = json_decode(get_option('SIRV_CROP_SIZES'), true);

  if (is_array($size)) {
    foreach ($sizes as $size_name => $sz) {
      if ($sz['width'] == $size[0] && $sz['height'] == $size[1]) {
        if(!isset($crop_data[$size_name])) break;
        $cropType = $crop_data[$size_name];
        break;
      }
    }
  } else {
    if (isset($crop_data[$size]))
      $cropType = $crop_data[$size];
  }

  if($cropType == 'none' && $isCrop) $cropType = 'wp_crop';

  return $cropType;
}


function sirv_get_scale_pattern($image_width, $image_height, $cropType, $original_image_path = '', $get_param_symbol = '?'){
  $sw = empty($image_width) ? '' : 'w=' . $image_width;
  $sh = empty($image_height) ? '' : 'h=' . $image_height;
  $size_params = array($sw, $sh);

  $wp_crop = sirv_get_params($get_param_symbol, $size_params) . '&scale.option=fill&cw=' . $image_width . '&ch=' . $image_height . '&cx=center&cy=center';
  $sirv_crop = sirv_get_params($get_param_symbol, $size_params) . '&scale.option=fit&canvas.width=' . $image_width . '&canvas.height=' . $image_height . '&cx=center&cy=center';
  $pattern_scale = sirv_get_params($get_param_symbol, $size_params);
  $scale_width = sirv_get_params($get_param_symbol, array($sw));
  $scale_height = sirv_get_params($get_param_symbol, array($sh));
  $original = '';
  $usedPattern = '';


  //sometimes wp has strange giant image sizes
  if ($image_width > 3000) return $scale_height;
  if ($image_height > 3000) return $scale_width;
  if ($image_height > 3000 && $image_width > 3000) return $original;
  if (empty($image_width) && empty($image_height)) return $original;

  $original_image_sizes = sirv_get_original_sizes($original_image_path);
  if ($original_image_sizes['width'] == $image_width && $original_image_sizes['height'] == $image_height) return $original;

  if ($cropType && $cropType != 'none') {
    $usedPattern = $cropType == 'wp_crop' ? $wp_crop : $sirv_crop;
  } else {
    $usedPattern = $pattern_scale;
  }

  return $usedPattern;
}


function sirv_get_params($param_start, $params){
  $params_str = '';
  foreach ($params as $index => $param) {
    if (!empty($param)) {
      $params_str .= $index == 0 ? $param : "&$param";
    } else {
      $params_str .= '';
    }
  }

  if (empty($params_str)) return '';

  return $param_start . $params_str;
}


function sirv_test_orientation($sizes){
  if ($sizes['width'] > $sizes['height']) return 'landsape';
  if ($sizes['width'] < $sizes['height']) return 'portrait';
  if ($sizes['width'] == $sizes['height']) return 'square';
}


function sirv_get_sirv_path($path = ''){
  $sirv_path = '';
  $cdn_url = get_option('SIRV_CDN_URL');
  $subpath = $path ? "/$path" : '';

  if(!empty($cdn_url)){
    $sirv_path = "https://$cdn_url" . $subpath;
  }else{
    $account_name = get_option('SIRV_ACCOUNT_NAME');
    $sirv_path = "https://$account_name.sirv.com" . $subpath;
  }

  return $sirv_path;
}


function sirv_add_profile($url){
  if (stripos($url, 'profile') !== false) {
    return $url;
  }

  $profile = get_option('SIRV_CDN_PROFILES');

  if (!empty($profile)) {
    $encoded_profle = rawurlencode($profile);
    $url .= (stripos($url, '?') === false) ? '?profile=' . $encoded_profle : '&profile=' . $encoded_profle;
  }

  return $url;
}


function sirv_convert_to_corrected_link($image_url){
  $site_url = get_site_url();

  if (stripos($image_url, $site_url) === false) {
    if (stripos($image_url, '/wp-content') === 0) {
      $image_url = $site_url . $image_url;
    }
  }

  return $image_url;
}


function sirv_get_size_name($size, $array_of_sizes){
  foreach ($array_of_sizes as $size_name_key => $size_name_value) {
    if ($size_name_value['width'] == $size) return $size_name_key;
  }

  return null;
}


function encode_spaces($string){
  return str_replace(' ', '%20', $string);
}


function sirv_cache_sync_data($attachment_id, $wait = false){
  global $syncData;

  if (!isset($syncData[$attachment_id])) {
    $syncData[$attachment_id] = sirv_get_cdn_image($attachment_id, $wait);
  }

  return $syncData[$attachment_id];
}


function sirv_get_full_sirv_url_path($sirv_url_path, $image){

  $sirv_rel_path = empty($image['sirv_path']) ? $image['img_path'] : $image['sirv_path'];

  return $sirv_url_path . $sirv_rel_path;
}


function sirv_set_db_failed($wpdb, $table, $attachment_id, $paths, $error_type = 1){
  $img_path = isset($paths['image_rel_path']) ? $paths['image_rel_path'] : $paths['wrong_file'];
  $data = array(
    'attachment_id' => $attachment_id,
    'img_path' => $img_path,
    'status' => 'FAILED',
    'error_type' => $error_type,
  );
  $wpdb->replace($table, $data);
}


function sirv_get_cdn_image($attachment_id, $wait = false, $is_synchronious = false){
  global $wpdb;
  global $isFetchUpload;
  global $isFetchUrl;

  $sirv_images_t = $wpdb->prefix . 'sirv_images';

  $sirv_folder = get_option('SIRV_FOLDER');
  $sirv_url_path = sirv_get_sirv_path($sirv_folder);


  $image = $wpdb->get_row("
    SELECT * FROM $sirv_images_t
    WHERE attachment_id = $attachment_id
  ", ARRAY_A);

  $paths = sirv_get_paths_info($attachment_id);

  if ( $image && $image['status'] == 'SYNCED' ) {

    if( $image['img_path'] == 'sirv_item' ){
      return sirv_get_sirv_path() . $image['sirv_path'];
    }

    $stored_mtime = $image['timestamp'];
    $stored_mtime_timestamp = strtotime($stored_mtime);

    $file_current_mtime = @filemtime($paths['img_file_path']);
    //date('Y-m-d H:i:s', $current_mtime);

    if( !$file_current_mtime || ($file_current_mtime && ($file_current_mtime == $stored_mtime_timestamp)) || $image['img_path'] == $paths['image_rel_path'] ){
      return sirv_get_full_sirv_url_path($sirv_url_path, $image);
    }else{
      $data = array();

      $data['img_path'] = $paths['image_rel_path'];
      $data['size'] = filesize($paths['img_file_path']);
      $data['status'] = 'NEW';
      $data['error_type'] = NULL;
      $data['timestamp'] = date("Y-m-d H:i:s", filemtime($paths['img_file_path']));
      $data['timestamp_synced'] = NULL;
      $data['checks'] = 0;
      $data['timestamp_checks'] = NULL;

      $result = $wpdb->update($sirv_images_t, $data, array('attachment_id' => $attachment_id));
      if($result == 1){
        $image['size'] = $data['size'];
        $image['status'] = 'NEW';
      }else{
        //error boundary here
      }
    }
  }


  if (!$image) {
    $headers = array();

    if ( !isset($paths['img_file_path']) ) {
      if( isset($paths['sirv_item']) ){
        return sirv_set_sirv_item_to_db($paths['sirv_item'], $wpdb, $sirv_images_t, $attachment_id);
      }else {
        sirv_set_db_failed($wpdb, $sirv_images_t, $attachment_id, $paths);
        return '';
      }
    } else {
      if ( !file_exists($paths['img_file_path']) ) {
        $headers = @get_headers($paths['image_full_url'], 1);
        if (!isset($headers['Content-Length'])) {
          sirv_set_db_failed($wpdb, $sirv_images_t, $attachment_id, $paths);
          return '';
        } else {
          $isFetchUrl = true;
        }
      } else {
        if ( is_dir($paths['img_file_path']) ) {
          sirv_set_db_failed($wpdb, $sirv_images_t, $attachment_id, $paths);
          return '';
        } else {
          $isFetchUrl = false;
        }
      }
    }

    $image_size = empty($headers) ? filesize($paths['img_file_path']) : (int) $headers['Content-Length'];

    if( !empty($image_size) && $image_size > 32000000 ){
      $data = array(
        'attachment_id' => $attachment_id,
        'img_path' => $paths['image_rel_path'],
        'status' => 'FAILED',
        'error_type' => 2,
      );

      $result = $wpdb->insert($sirv_images_t, $data);

      return '';
    }

    $image_created_timestamp = empty($headers)
      ? date("Y-m-d H:i:s", filemtime($paths['img_file_path']))
      : date("Y-m-d H:i:s", strtotime($headers['Last-Modified']));

    $data = array();
    $data['attachment_id'] = $attachment_id;
    $data['img_path'] = $paths['image_rel_path'];
    $data['sirv_path'] = $paths['sirv_rel_path_encoded'];
    $data['size'] = $image_size;
    $data['status'] = 'NEW';
    $data['error_type'] = NULL;
    $data['timestamp'] = $image_created_timestamp;
    $data['timestamp_synced'] = NULL;
    $data['checks'] = 0;
    $data['timestamp_checks'] = NULL;

    $result = $wpdb->insert($sirv_images_t, $data);

    if ($result) {
      $image = $data;
      $image['img_file_path'] = $paths['img_file_path'];
      $image['sirv_full_path'] = $paths['sirv_full_path'];
      $image['sirv_full_path_encoded'] = $paths['sirv_full_path_encoded'];
      $image['image_full_url'] = $paths['image_full_url'];
    }
  }


  if ($image && $image['status'] == 'NEW') {

    if (!isset($image['img_file_path'])) {
      /* $paths = sirv_get_cached_wp_img_file_path($attachment_id);

      $image['img_file_path'] = $paths['img_file_path'];
      $image['sirv_full_path'] = $sirv_folder . $image['sirv_path'];
      $image['image_full_url'] = $paths['url_images_path'] . $image['img_path']; */

      $image['img_file_path'] = $paths['img_file_path'];
      $image['sirv_full_path'] = $paths['sirv_full_path'];
      $image['sirv_full_path_encoded'] = $paths['sirv_full_path_encoded'];
      $image['image_full_url'] = $paths['image_full_url'];
    }


    $img_data = array(
      'id'            => $image['attachment_id'],
      'imgPath'       => $image['img_file_path'],
    );

    $fetch_max_file_size = empty((int)get_option('SIRV_FETCH_MAX_FILE_SIZE')) ? 1000000000 : (int)get_option('SIRV_FETCH_MAX_FILE_SIZE');
    $isFetchUpload = (int) $image['size'] < $fetch_max_file_size ? true : false;
    $isFetchUpload = $isFetchUrl ? true : $isFetchUpload;

    $file = sirv_uploadFile($image['sirv_full_path'], $image['sirv_full_path_encoded'], $image['img_file_path'], $img_data, $image['image_full_url'], $wait, $is_synchronious);

    if ( is_array($file) ) {
      if ($file['upload_status'] == 'uploaded') {
        $wpdb->update($sirv_images_t, array(
          'timestamp_synced' => date("Y-m-d H:i:s"),
          'status' => 'SYNCED'
        ), array('attachment_id' => $attachment_id));

        sirv_set_image_meta('/' . $paths['sirv_full_path_encoded'], $image['attachment_id']);

        return sirv_get_full_sirv_url_path($sirv_url_path, $image);
      } else {
        $wpdb->update($sirv_images_t, array(
          'status' => 'FAILED',
          'error_type' => $file['error_type'],
        ), array('attachment_id' => $attachment_id));

        return '';
      }
    } else {
      return '';
    }
  }

  if ($image && $image['status'] == 'PROCESSING') {
    $checks_count = 6;

    if (sirv_time_checks($image, $checks_count)) {
      if (sirv_checkIfImageExists($paths['sirv_full_path_encoded'])) {
        $wpdb->update($sirv_images_t, array(
          'timestamp_synced' => date("Y-m-d H:i:s"),
          'status' => 'SYNCED',
          'checks' => (int) $image['checks'] + 1,
        ), array('attachment_id' => $attachment_id));

        sirv_set_image_meta('/' . $paths['sirv_full_path_encoded'], $attachment_id);

        return sirv_get_full_sirv_url_path($sirv_url_path, $image);
      } else {
        $wpdb->update($sirv_images_t, array(
          'checks' => (int) $image['checks'] + 1,
          'timestamp_checks' => time()
        ), array('attachment_id' => $attachment_id));

        return '';
      }
    } else if ((int) $image['checks'] >= $checks_count) {
      $wpdb->update($sirv_images_t, array(
        'status' => 'FAILED',
        'error_type' => 7
      ), array('attachment_id' => $attachment_id));

      return '';
    }
  }
}


function sirv_set_sirv_item_to_db($url, $wpdb, $sirv_images_t, $attachment_id){
  list($response, $data) = sirv_generate_sirv_item_db_data($url, $attachment_id);

  $wpdb->replace($sirv_images_t, $data);

  return $response;
}


function sirv_generate_sirv_item_db_data($url, $attachment_id){
  $response = '';
  $data = array(
    'attachment_id' => $attachment_id,
    'img_path' => 'sirv_item',
    'sirv_path' => sirv_get_sirv_path_from_url($url),
    'timestamp' => date("Y-m-d H:i:s"),
  );

  $headers = Utils::get_head_request($url);

  if (sirv_is_sirv_item_http_status_ok($headers)) {
    $data['status'] = 'SYNCED';
    $data['timestamp_synced'] = date("Y-m-d H:i:s");
    $data['size'] = $headers['Content-Length'] ? $headers['Content-Length'] : NULL;

    $response = $url;
  } else {
    $data['status'] = 'FAILED';
    $data['error_type'] = 7;
  }

  return array($response, $data);
}


function sirv_get_sirv_path_from_url($url){
  return parse_url($url, PHP_URL_PATH);
}


function sirv_is_sirv_item_http_status_ok($headers){
  return ( empty($headers) || !isset($headers['HTTP_code']) || isset($headers['error']) ) ? false : (int) $headers['HTTP_code'] === 200;
}


function sirv_time_checks($image, $count = 5){
  $times_checks = array(10, 30, 70, 150, 310, 630, 1270, 2550, 5110);
  $check_num = (int) $image['checks'];

  return ($check_num <= $count && ($image['timestamp_checks'] == 'NULL' ||  time() - (int) $image['timestamp_checks'] >= $times_checks[$check_num]));
}


function sirv_get_cached_wp_img_file_path($attachment_id){
  global $pathsData;

  if (!isset($pathsData[$attachment_id])) {
    $pathsData[$attachment_id] = sirv_get_wp_img_file_path($attachment_id);
  }

  return $pathsData[$attachment_id];
}


function sirv_get_wp_img_file_path($attachment_id){
  require_once(ABSPATH . 'wp-admin/includes/file.php');

  $uploads_dir_info = wp_get_upload_dir();
  $root_images_path = $uploads_dir_info['basedir'];
  $url_images_path = $uploads_dir_info['baseurl'];

  $img_file_path = get_attached_file($attachment_id);

  if (!$img_file_path) return array('wrong_file' => 'File name/path missing from WordPress media library');

  if(sirv_is_sirv_item($img_file_path)){
    $exclude_path = $url_images_path . '/';
    $sirv_path = str_replace($exclude_path, '', $img_file_path);
    return array(
      'sirv_item' => $sirv_path,
    );
  }

  if (stripos($img_file_path, $root_images_path) === false) {
    if (file_exists($img_file_path)) {
      if (stripos($img_file_path, '/wp-content/uploads/') !== false) {
        $root_images_path = preg_replace('/(.*?\/wp-content\/uploads)\/.*/im', '$1', $img_file_path);
      } else return array('wrong_file' => $img_file_path);
    } else {
      return array('wrong_file' => $img_file_path);
    }
  }

  return array(
    'root_images_path' => $root_images_path,
    'url_images_path' => $url_images_path,
    'img_file_path' => $img_file_path
  );
}


function sirv_get_paths_info($attachment_id){

  if (empty($attachment_id)) return array('wrong_file' => 'Empty attachment');

  //$wp_img_path_data = sirv_get_wp_img_file_path($attachment_id);
  $wp_img_path_data = sirv_get_cached_wp_img_file_path($attachment_id);
  if (isset($wp_img_path_data['wrong_file']) || isset($wp_img_path_data['sirv_item']) ) return $wp_img_path_data;

  $sirv_folder = get_option('SIRV_FOLDER');

  $paths = array(
    'root_images_path' => $wp_img_path_data['root_images_path'],
    'url_images_path' => $wp_img_path_data['url_images_path'],
    'sirv_base_url_path' => sirv_get_sirv_path($sirv_folder),
  );

  $paths['img_file_path'] = $wp_img_path_data['img_file_path'];
  $paths['image_basename'] = sirv_basename($paths['img_file_path']);

  $paths['image_rel_path'] = str_replace($paths['root_images_path'], '', $paths['img_file_path']);
  $encoded_image_basename = urlencode($paths['image_basename']);
  $paths['image_base_path'] = str_replace(sirv_basename($paths['image_rel_path']), '', $paths['image_rel_path']);
  /* $dispersion_sirv_path = sirv_get_path_strategy($paths['image_base_path'], $encoded_image_basename); */
  $dispersion_sirv_path = sirv_get_path_strategy($paths['image_base_path'], $encoded_image_basename);
  //$modified_sirv_path = $dispersion_sirv_path . $encoded_image_basename;
  $filepath_with_original_disp = $dispersion_sirv_path['original'] . $paths['image_basename'];
  $filepath_with_encoded_disp = $dispersion_sirv_path['encoded'] . $encoded_image_basename;

  $paths['sirv_url_path'] = $paths['sirv_base_url_path'] . $paths['image_base_path'] . $dispersion_sirv_path['encoded'];
  $paths['sirv_full_url_path'] = $paths['sirv_url_path'] . $encoded_image_basename;
  $paths['sirv_rel_path'] = $paths['image_base_path'] . $filepath_with_original_disp;
  $paths['sirv_rel_path_encoded'] = $paths['image_base_path'] . $filepath_with_encoded_disp;
  $paths['sirv_full_path'] = $sirv_folder . $paths['image_base_path'] . $filepath_with_original_disp;
  $paths['sirv_full_path_encoded'] = $sirv_folder . $paths['image_base_path'] . $filepath_with_encoded_disp;
  $paths['image_full_url'] = $paths['url_images_path'] . encode_spaces($paths['image_rel_path']);

  return $paths;
}


function sirv_basename($filepath){
  $lines = explode('/', $filepath);

  return end($lines);
}


function sirv_get_correct_filename($filename, $filepath){
  $filename = preg_replace('/[^a-z0-9_\\-\\.]+/i', '_', $filename);
  $fileInfo = pathinfo($filename);
  if (preg_match('/^_+$/', $fileInfo['filename'])) {
    $filename = sirv_get_file_md5($filepath) . '.' . $fileInfo['extension'];
  }


  return $filename;
}


function sirv_get_dispersion_path($filename){
  //$filename = pathinfo($filename)['filename'];
  $filename = pathinfo($filename, PATHINFO_FILENAME);
  $char = 0;
  $dispertionPath = array("original" => "", "encoded" => "");

  while ($char <= 2 && $char < strlen($filename)) {
    if (empty($dispertionPath['original'])) {
      $dispertionPath['original'] = ('.' == $filename[$char]) ? '_' : $filename[$char];
      $dispertionPath['encoded'] = ('.' == $filename[$char]) ? '_' : urlencode($filename[$char]);
    } else {
      if ($char == 2) $char = strlen($filename) - 1;
      $dispertionPath['original'] = sirv_add_dir_separator($dispertionPath['original']) . ('.' == $filename[$char] ? '_' : $filename[$char]);
      $dispertionPath['encoded'] = sirv_add_dir_separator($dispertionPath['encoded']) . ('.' == $filename[$char] ? '_' : urlencode($filename[$char]));
    }
    $char++;
  }

  $dispertionPath['original'] .= '/';
  $dispertionPath['encoded'] .= '/';

  return $dispertionPath;
}


function sirv_get_path_strategy($folder_path, $filename){
  global $foldersData;
  global $overheadLimit;

  $folders_data = sirv_get_data_images_per_folder($overheadLimit);
  $path = array("original" => "", "encoded" => "");

  if ($folders_data['isOverheadImgCount']) {
    if (isset($folders_data['cached_folders_data'][$folder_path]) && $folders_data['cached_folders_data'][$folder_path] >= $overheadLimit) {
      $path = sirv_get_dispersion_path($filename);
    } else {
      if (array_key_exists($folder_path, $foldersData['cached_folders_data'])) {
        $foldersData['cached_folders_data'][$folder_path] += 1;
      } else {
        $foldersData['cached_folders_data'][$folder_path] = 1;
      }

      update_option('SIRV_FOLDERS_DATA', json_encode($foldersData), 'no');
    }
  }

  return $path;
}


function sirv_add_dir_separator($dir){
  if (substr($dir, -1) != '/') {
    $dir .= '/';
  }
  return $dir;
}


function sirv_get_file_md5($file_path){

  return substr(md5_file($file_path), 0, 12);
}


//return array with images using in posts
function sirv_get_all_images(){
  $query_images_args = array(
    'post_type'      => 'attachment',
    'post_mime_type' => 'image',
    'post_status'    => 'inherit',
    'posts_per_page' => -1,
  );

  $query_images = new WP_Query($query_images_args);

  $images = array();
  $images['count'] = count($query_images->posts);
  $tmp_images = array();

  foreach ($query_images->posts as $image) {
    $tmp_images[] = array('image_url' => wp_get_attachment_url($image->ID), 'attachment_id' => $image->ID);
  }

  $images['images'] = $tmp_images;

  return $images;
}


function sirv_get_unsynced_images($limit = 100){

  global $wpdb;
  $sirv_images_t = $wpdb->prefix . 'sirv_images';
  $posts_t = $wpdb->prefix . 'posts';

  $image_query = "$posts_t.post_mime_type LIKE 'image/%'";
  $video_query = "$posts_t.post_mime_type LIKE 'video/%'";

  $query = get_option('SIRV_PARSE_VIDEOS') === 'on' ? "$image_query OR $video_query" : "$image_query";

  return $wpdb->get_results("
      SELECT $posts_t.ID as attachment_id, $posts_t.guid as image_url FROM $posts_t
      WHERE $posts_t.ID NOT IN (SELECT attachment_id FROM $sirv_images_t)
      AND $posts_t.post_type = 'attachment'
      AND ($query)
      AND ($posts_t.post_status = 'inherit')
      ORDER BY $posts_t.post_date DESC LIMIT $limit
      ", ARRAY_A);
}


function sirv_get_all_post_images_count(){
  global $wpdb;
  $posts_t = $wpdb->prefix . 'posts';

  $image_query = "$posts_t.post_mime_type LIKE 'image/%'";
  $video_query = "$posts_t.post_mime_type LIKE 'video/%'";

  $query = get_option('SIRV_PARSE_VIDEOS') === 'on' ? "$image_query OR $video_query" : "$image_query";

  return $wpdb->get_var("
        SELECT count(*) FROM $posts_t WHERE ($query)
        AND $posts_t.post_type = 'attachment'
        AND $posts_t.post_status = 'inherit'
      ");
}


function sirv_get_synced_count(){
  global $wpdb;
  $images_t = $wpdb->prefix . 'sirv_images';

  return (int) $wpdb->get_var("SELECT count(*) FROM $images_t WHERE status = 'SYNCED'");
}


function sirv_get_unsynced_images_count(){
  global $wpdb;
  $sirv_images_t = $wpdb->prefix . 'sirv_images';
  $posts_t = $wpdb->prefix . 'posts';

  $image_query = "$posts_t.post_mime_type LIKE 'image/%'";
  $video_query = "$posts_t.post_mime_type LIKE 'video/%'";

  $query = get_option('SIRV_PARSE_VIDEOS') === 'on' ? "$image_query OR $video_query" : "$image_query";


  return $wpdb->get_var("
      SELECT count(*) FROM $posts_t WHERE $posts_t.ID NOT IN (SELECT attachment_id FROM $sirv_images_t)
      AND ($query)
      AND $posts_t.post_type = 'attachment'
      AND $posts_t.post_status = 'inherit'
      ", ARRAY_A);
}


function sirv_get_uncached_images($post_images){
  global $wpdb;
  $table_name = $wpdb->prefix . 'sirv_images';

  //cached images
  $sql_result = $wpdb->get_results("SELECT attachment_id FROM " . $table_name, ARRAY_N);

  $uncached_ids = array_values(array_diff(sirv_flattern_array($post_images, true, 'attachment_id'), sirv_flattern_array($sql_result)));

  return sirv_get_unique_items($post_images, $uncached_ids);
}


function sirv_get_unique_items($search_array, $unique_items){
  $tmp_arr = array();
  foreach ($search_array as $item) {
    if (in_array($item['attachment_id'], $unique_items)) array_push($tmp_arr, $item);
  }

  return $tmp_arr;
}


function sirv_flattern_array($array, $isAssociativeArray = false, $associativeField = ''){
  $tmp_arr = array();
  foreach ($array as $item) {
    if ($isAssociativeArray) {
      if ($associativeField !== '') {
        array_push($tmp_arr, intval($item[$associativeField]));
      } else return array();
    } else array_push($tmp_arr, intval($item[0]));
  }

  return $tmp_arr;
}


function sirv_calc_images_per_folder($overheadLimit){
  global $foldersData;

  $isOverheadImgCount = (int) sirv_get_all_post_images_count() >= $overheadLimit;

  $foldersData = array(
    'time' => time(),
    'isOverheadImgCount' => $isOverheadImgCount,
    'cached_folders_data' => array(),
  );

  if ($isOverheadImgCount) {
    $foldersData['cached_folders_data'] = sirv_calc_images_per_folder_in_cache();
  }

  update_option('SIRV_FOLDERS_DATA', json_encode($foldersData), 'no');

  return $foldersData;
}


function sirv_calc_images_per_folder_in_cache(){
  global $wpdb;
  $images = $wpdb->prefix . 'sirv_images';

  $img_count = array();

  $results = $wpdb->get_results("SELECT REPLACE(sirv_path, SUBSTRING_INDEX(sirv_path, '/', -1), '') as img_path, count(*) as count FROM $images WHERE `status` != 'FAILED' GROUP BY REPLACE(sirv_path, SUBSTRING_INDEX(sirv_path, '/', -1), '')", ARRAY_A);

  if ($results) {
    foreach ($results as $result) {
      $path = $result['img_path'];
      $count = $result['count'];

      $img_count[$path] = (int) $count;
    }
  }

  return $img_count;
}


function sirv_get_data_images_per_folder($overheadLimit=5000, $isForsed = false){
  global $foldersData;

  if ($isForsed) {
    $foldersData = sirv_calc_images_per_folder($overheadLimit);
  } else {
    if (empty($foldersData)) {
      $foldersData = json_decode(get_option('SIRV_FOLDERS_DATA'), true);
    }

    if (empty($foldersData) || time() - (int)$foldersData['time'] > 60 * 20) $foldersData = sirv_calc_images_per_folder($overheadLimit);
  }

  return $foldersData;
}


add_action('wp_ajax_sirv_tst', 'sirv_tst');
function sirv_tst(){
  if (!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  echo json_encode(array('done' => true));

  wp_die();
}


//---------------------------------------------YOAST SEO fixes for og images-----------------------------------------------------------------------//

add_filter('wpseo_opengraph_image', 'sirv_wpseo_opengraph_image', 10, 1);
add_filter('wpseo_twitter_image', 'sirv_wpseo_opengraph_image', 10, 1);


function sirv_wpseo_opengraph_image($img){
  if (stripos($img, '-cdn.sirv') != false) $img = str_replace('-cdn', '', $img);

  return $img;
}

//---------------------------------------------YOAST SEO meta fixes for og images END ------------------------------------------------------------------//


//-------------------------------------------------------------Ajax requests-------------------------------------------------------------------------//
function sirv_getAPIClient(){
  global $APIClient;
  if ($APIClient) {
    return $APIClient;
  } else {
    require_once(SIRV_PLUGIN_SUBDIR_PATH . 'includes/classes/sirv.api.class.php');
    return $APIClient = new SirvAPIClient(
      get_option('SIRV_CLIENT_ID'),
      get_option('SIRV_CLIENT_SECRET'),
      get_option('SIRV_TOKEN'),
      get_option('SIRV_TOKEN_EXPIRE_TIME'),
      'Sirv/Wordpress'
    );
  }
}


function sirv_uploadFile($sirv_path, $sirv_path_encoded, $image_path, $img_data, $imgURL = '', $wait = false, $is_synchronious = false){
  if( sirv_isMuted() ) return false;

  global $isLocalHost;
  global $isFetchUpload;
  $APIClient = sirv_getAPIClient();

  if ($isLocalHost || !$isFetchUpload) {
    $response = $APIClient->uploadImage($image_path, $sirv_path_encoded);

    if( $response['upload_status'] == 'failed' ){
      $response['error_type'] = 6;
    }

    return $response;
  } else {
    $fetch_data = array(
      'imgURL'        => $imgURL,
      'sirvFileName'  => '/' . $sirv_path,
      'sirvFileNameEncoded'  => '/' . $sirv_path_encoded,
      'data'          => $img_data,
      'wait'          => $wait,
      'auth'          => sirv_get_http_auth_credentials(),
    );

    if( $is_synchronious ){
      return sirv_proccess_synchronious_fetch($fetch_data);
    }else{
      $GLOBALS['sirv_fetch_queue'][$imgURL] = $fetch_data;
      return false;
    }
  }
}


function sirv_get_http_auth_credentials(){
  $data = array();
  $isChecked = get_option('SIRV_HTTP_AUTH_CHECK');

  if(empty($isChecked) || $isChecked == '0') return $data;

  $username = get_option('SIRV_HTTP_AUTH_USER');
  $password = get_option('SIRV_HTTP_AUTH_PASS');

  if( !empty($username) && !empty($password) ){
    $data = array(
      'username' => $username,
      'password' => $password,
    );
  }

  return $data;
}

function sirv_proccess_synchronious_fetch($image){
  //$uploaded_status => failed, uploaded
  $response = array("upload_status" => 'failed');

  $APIClient = sirv_getAPIClient();

  $fetch_data = array(
    'url'       =>  $image['imgURL'],
    'filename'  =>  $image['sirvFileName'],
    'wait'      =>  (bool) $image['wait'],
  );

  if (isset($image['auth']) && !empty($image['auth'])) {
    $fetch_data['auth'] = $image['auth'];
  }

  $res = $APIClient->fetchImage($fetch_data);

  if( $res ){
    if ( !empty($res->result) && is_array($res->result) ) {
      list($status, $error_type) = array_values(sirv_parse_fetch_data($res->result[0], $image['wait'], $APIClient));

      if ( $status == 'SYNCED' ) {
        $response['upload_status'] = 'uploaded';
      }

      $response['status'] = $status;
      $response['error_type'] = $error_type;
    }
  }

  return $response;
}


function sirv_processFetchQueue(){
  if (empty($GLOBALS['sirv_fetch_queue']) || sirv_isMuted()) {
    return;
  }

  $APIClient = sirv_getAPIClient();
  global $wpdb;
  $table_name = $wpdb->prefix . 'sirv_images';


  $images2fetch = array_chunk($GLOBALS['sirv_fetch_queue'], 5);
  foreach ($images2fetch as $images) {
    $imgs = $imgs_data = array();
    foreach ($images as $image) {
      $imgs_data[$image['sirvFileName']] = $image;
      $img_data = array(
        'url'       =>  $image['imgURL'],
        'filename'  =>  $image['sirvFileName'],
        'wait'      =>  !empty($image['wait']) ? true : false
      );

      if(isset($image['auth']) && !empty($image['auth'])){
        $img_data['auth'] = $image['auth'];
      }

      $imgs[] = $img_data;
    }

    $res = $APIClient->fetchImage($imgs);
    if ($res) {
      if (!empty($res->result) && is_array($res->result)) {
        foreach ($res->result as $result) {
          $image = $imgs_data[$result->filename];
          list($status, $error_type) = array_values(sirv_parse_fetch_data($result, $image['wait'], $APIClient));

          if ( $status == 'SYNCED' ) {
            sirv_set_image_meta($image['sirvFileNameEncoded'], $image['data']['id']);
          }

          $wpdb->update($table_name, array(
            'timestamp_synced'  => date("Y-m-d H:i:s"),
            'status'            => $status,
            'error_type'        => $error_type,
          ), array('attachment_id' => $image['data']['id']));
          /* if (!empty($result->success)) {
                //code here
              } */
        }
      }
    }
  }
  unset($GLOBALS['sirv_fetch_queue']);
}


function sirv_parse_fetch_data($res, $wait, $APIClient){
  $arr = array('status' => 'NEW', 'error_code' => NULL);
  if (isset($res->success) && $res->success) {
    $arr['status'] = 'SYNCED';
  } else {
    if ($wait) {
      try {
        if (is_array($res->attempts)) {
          $attempt = end($res->attempts);
          if (!empty($attempt->error)) {
            if (isset($attempt->error->httpCode) && $attempt->error->httpCode == 429) {
              preg_match('/Retry after ([0-9]{4}\-[0-9]{2}\-[0-9]{2}.*?\([a-z]{1,}\))/ims', $attempt->error->message, $m);
              $time = strtotime($m[1]);
              $APIClient->muteRequests($time);
              $arr['error_code'] = 5;
            } else {
              $error_msg = $attempt->error->message;
              $arr['error_code'] = FetchError::get_error_code($error_msg);
            }
          } else {
            $arr['error_code'] = 4;
          }
        } else {
          $arr['error_code'] = 4;
        }
      } catch (Exception $e) {
        global $logger;

        $logger->error($e)->write();
        $arr['error_code'] = 4;
      }
      $arr['status'] = 'FAILED';
    } else {
      $arr['status'] = 'PROCESSING';
    }
  }

  return $arr;
}


function sirv_checkIfImageExists($filename){
  $APIClient = sirv_getAPIClient();

  $stat = $APIClient->getFileStat($filename);

  return ($stat && !empty($stat->size));
}


function sirv_isMuted(){
  return ((int) get_option('SIRV_MUTE') > time());
}


function sirv_get_attachment_meta($attachment_id){
  $attachment = get_post($attachment_id);

  return array(
    'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
    'caption' => $attachment->post_excerpt,
    'description' => $attachment->post_content,
    'href' => get_permalink($attachment->ID),
    'src' => $attachment->guid,
    'title' => $attachment->post_title
  );
}


function sirv_get_formated_number($num){
  return number_format($num);
}


function sirv_getCacheInfo(){
  global $wpdb;
  $images_t = $wpdb->prefix . 'sirv_images';
  $posts_t = $wpdb->prefix . 'posts';

  $total_count = (int) sirv_get_all_post_images_count();

  $stat = array(
    'NEW' => array('count' => 0, 'count_s' => '0', 'size' => 0, 'size_s' => '-'),
    'PROCESSING' => array('count' => 0, 'count_s' => '0', 'size' => 0, 'size_s' => '-'),
    'SYNCED' => array('count' => 0, 'count_s' => '0', 'size' => 0, 'size_s' => '-'),
    'FAILED' => array('count' => 0, 'count_s' => '0', 'size' => 0, 'size_s' => '-'),
    'q' => 0,
    'q_s' => '0',
    'size' => 0,
    'size_s' => '-',
    'total_count' => $total_count,
    'total_count_s' => sirv_get_formated_number($total_count),
    'garbage_count' => 0,
    'queued' => 0,
    'queued_s' => '0',
    'progress' => 0,
    'progress_complited' => 0,
    'progress_queued' => 0,
    'progress_failed' => 0,
  );

  $results = $wpdb->get_results("SELECT status, count(*) as `count`, SUM(size) as size FROM $images_t GROUP BY status", ARRAY_A);
  if ($results) {
    foreach ($results as $row) {
      $stat[$row['status']] = array(
        'count' => (int) $row['count'],
        'count_s' => sirv_get_formated_number((int) $row['count']),
        'size' => (int) $row['size'],
        'size_s' => Utils::getFormatedFileSize((int) $row['size']),
      );
    }


    $stat['size'] = (int) $stat['SYNCED']['size'];
    $stat['size_s'] = $stat['SYNCED']['size_s'];
    $stat['q'] = ( ((int) $stat['SYNCED']['count']) > $stat['total_count'] ) ? $stat['total_count']: (int) $stat['SYNCED']['count'];
    $stat['q_s'] = sirv_get_formated_number($stat['q']);

    $oldCache = (int) $wpdb->get_var("
          SELECT count(attachment_id) FROM $images_t WHERE attachment_id NOT IN (SELECT $posts_t.ID FROM $posts_t)
      ");

    $stat['garbage_count'] = $oldCache;
    $stat['garbage_count_s'] = sirv_get_formated_number($oldCache);

    $queued = $stat['total_count'] - $stat['q'] - $stat['FAILED']['count'];
    $stat['queued'] = $queued < 0 ? 0 : $queued;
    $stat['queued_s'] = sirv_get_formated_number($stat['queued']);

    $progress_complited = $stat['total_count'] != 0 ? ($stat['q']) / $stat['total_count'] * 100 : 0;
    $progress_queued = $stat['total_count'] != 0 ? $stat['queued'] / $stat['total_count'] * 100 : 0;
    $progress_failed = $stat['total_count'] != 0 ? $stat['FAILED']['count'] / $stat['total_count'] * 100 : 0;

    $stat['progress'] = (int) $progress_complited;
    $stat['progress_complited'] = $progress_complited;
    $stat['progress_queued'] = $progress_queued;
    $stat['progress_failed'] = $progress_failed;
  }

  return $stat;
}


function sirv_getGarbage(){
  global $wpdb;
  $sirv_images_t = $wpdb->prefix . 'sirv_images';
  $posts_t = $wpdb->prefix . 'posts';

  $t = (int) $wpdb->get_var("
      SELECT count(attachment_id) FROM $sirv_images_t WHERE attachment_id NOT IN (SELECT $posts_t.ID FROM $posts_t)
  ");

  return array($t > 0, $t);
}


add_action('wp_ajax_sirv_get_errors_info', 'sirv_getErrorsInfo');
function sirv_getErrorsInfo(){

  if (!(defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  $errors = FetchError::get_errors_from_db();
  //$file_size_fetch_limit = empty((int) get_option('SIRV_FETCH_MAX_FILE_SIZE')) ?  '' : ' (' . Utils::getFormatedFileSize(get_option('SIRV_FETCH_MAX_FILE_SIZE')) . ')';
  //$file_size_fetch_limit = ' (' . Utils::getFormatedFileSize(32000000) . ')';
  $file_size_fetch_limit = ' (32 MB)';
  $errData = array();

  global $wpdb;

  $t_error = $wpdb->prefix . 'sirv_images';

  $errors_desc = FetchError::get_errors_desc();



  foreach ($errors as $error) {
    if ((int)$error['id'] == 2) {
      $error['error_msg'] .= $file_size_fetch_limit;
    }
    $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $t_error WHERE status = 'FAILED' AND error_type = %d", $error['id']));
    $errData[$error['error_msg']]['count'] =  (int)$count;
    $errData[$error['error_msg']]['error_id'] =  (int)$error['id'];
    try {
      $errData[$error['error_msg']]['error_desc'] = $errors_desc[(int) $error['id']];
    } catch (Exception $e) {
      continue;
    }
  }

  echo json_encode($errData);
  wp_die();
}


function sirv_getStorageInfo($force_update = false){
  $hided_apies = array("images:realtime", "zips2:create", "s3:global", "s3:PUT", "s3:GET", "s3:DELETE", "ftp:global", "ftp:STOR", "ftp:RETR", "ftp:DELE");

  $cached_stat = get_option('SIRV_STAT');

  if (!empty($cached_stat) && !$force_update) {
    $storageInfo = @unserialize($cached_stat);
    if (is_array($storageInfo) && time() - $storageInfo['time'] < 60 * 60) {
      $storageInfo['data']['lastUpdate'] = date("H:i:s e", $storageInfo['time']);

      $storageInfo["data"]["limits"] = sirv_filter_limits_info($storageInfo["data"]["limits"], $hided_apies);

      return $storageInfo['data'];
    }
  }

  $sirvAPIClient = sirv_getAPIClient();

  $storageInfo = $sirvAPIClient->getStorageInfo();

  $lastUpdateTime = time();

  $storageInfo['lastUpdate'] = date("H:i:s e",  $lastUpdateTime);

  //remove hided apies
  $storageInfo["limits"] = sirv_filter_limits_info($storageInfo["limits"], $hided_apies);

  update_option('SIRV_STAT', serialize(array(
    'time'  => $lastUpdateTime,
    'data'  => $storageInfo
  )),
  'no');

  return $storageInfo;
}


function sirv_filter_limits_info($limits, $excluded_limits){
  if(! function_exists('limitsFilterFunc')){
    $limitsFilterFunc = function ($limit) use ($excluded_limits) {
      return !in_array($limit["type"], $excluded_limits);
    };

  }
  return array_filter($limits, $limitsFilterFunc);
}


function sirv_decode_chunk($data){
  $data = explode(';base64,', $data);

  if (!is_array($data) || !isset($data[1])) {
    return false;
  }

  $data = base64_decode($data[1]);
  if (!$data) {
    return false;
  }

  return $data;
}


function sirv_checkAndCreatekDir($dir){
  if (!is_dir($dir)) {
    mkdir($dir);
  }
  chmod($dir, 0777);
}


//use ajax request to get php ini variables data
add_action('wp_ajax_sirv_get_php_ini_data', 'sirv_get_php_ini_data_callback');
function sirv_get_php_ini_data_callback(){
  if (!(is_array($_POST) && isset($_POST['sirv_get_php_ini_data']) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  $sirvAPIClient = sirv_getAPIClient();
  //$accountInfo = json_decode($sirvAPIClient->getAccountInfo(), true);
  $accountInfo = $sirvAPIClient->getAccountInfo();

  $fileSizeLimit = isset($accountInfo->fileSizeLimit) ? $accountInfo->fileSizeLimit : 33554432;

  $php_ini_data = array();
  $php_ini_data['post_max_size'] = ini_get('post_max_size');
  $php_ini_data['max_file_uploads'] = ini_get('max_file_uploads');
  $php_ini_data['max_file_size'] = ini_get('upload_max_filesize');
  $php_ini_data['sirv_file_size_limit'] = $fileSizeLimit;

  echo json_encode($php_ini_data);

  wp_die();
}


//use ajax to clean 30 rows in table. For test purpose.
add_action('wp_ajax_sirv_delete_thirty_rows', 'sirv_delete_thirty_rows_callback');
function sirv_delete_thirty_rows_callback(){
  if (!(is_array($_POST) && isset($_POST['sirv_delete_thirty_rows']) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }
  global $wpdb;

  $table_name = $wpdb->prefix . 'sirv_images';
  $result = $wpdb->query("DELETE FROM $table_name WHERE id > 0 LIMIT 30");

  echo $result;


  wp_die();
}


add_action('wp_ajax_sirv_initialize_process_sync_images', 'sirv_initialize_process_sync_images');
function sirv_initialize_process_sync_images(){
  if (!(is_array($_POST) && isset($_POST['sirv_initialize_sync']) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  global $overheadLimit;

  sirv_get_data_images_per_folder($overheadLimit, true);

  echo json_encode(array('folders_calc_finished' => true));

  wp_die();
}


add_action('wp_ajax_sirv_process_sync_images', 'sirv_process_sync_images');
function sirv_process_sync_images(){
  if (!(is_array($_POST) && isset($_POST['sirv_sync_uncached_images']) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  global $isLocalHost;
  global $isFetchUpload;
  global $wpdb;
  $table_name = $wpdb->prefix . 'sirv_images';

  if (sirv_isMuted()) {
    sirv_return_limit_error();
    wp_die();
  }

  $sql = "SELECT * FROM $table_name
          WHERE status != 'FAILED'  AND status != 'SYNCED'
          ORDER BY IF(status='NEW',0,1), IF(status='PROCESSING', checks , 10) LIMIT 10";
  $results = $wpdb->get_results($sql, ARRAY_A);

  if (empty($results) || count($results) == 0) {
    $results = sirv_get_unsynced_images(10);
  }

  ini_set('max_execution_time', ($isLocalHost || !$isFetchUpload) ? 30 : 20);

  $maxExecutionTime = (int) ini_get('max_execution_time');

  if ($maxExecutionTime == 0) {
    $maxExecutionTime = 10;
  }

  $startTime = time();

  if (!empty($results)) {
    foreach ($results as $image_data) {
      sirv_get_cdn_image($image_data['attachment_id'], true);

      if ($maxExecutionTime && (time() - $startTime > $maxExecutionTime - 1)) {
        break;
      }
    }
  }

  try {
    sirv_processFetchQueue();
  } catch (Exception $e) {
    if (sirv_isMuted()) {
      sirv_return_limit_error();
      wp_die();
    }
  }

  echo json_encode(sirv_getCacheInfo());

  wp_die();
}


function sirv_return_limit_error(){
  $sirvAPIClient = sirv_getAPIClient();
  $errorMsg = $sirvAPIClient->getMuteError();
  $cachedInfo = sirv_getCacheInfo();

  $cachedInfo['status'] = array(
    'isStopSync' => true,
    'errorMsg' => $errorMsg
  );

  echo json_encode($cachedInfo);
}


function sirv_ProcessSirvFillTable(){
  global $wpdb;
  //global $isLocalHost;
  $table_name = $wpdb->prefix . 'sirv_images';

  $unsynced_images = sirv_get_unsynced_images();

  if ($unsynced_images) {
    foreach ($unsynced_images as $image) {
      $paths = sirv_get_paths_info($image['attachment_id']);

      if (empty($paths) || !file_exists($paths['img_file_path']) || is_dir($paths['img_file_path'])) {
        $img_path = isset($paths['image_rel_path']) ? $paths['image_rel_path'] : $paths['wrong_file'];
        $data = array(
          'attachment_id' => $image['attachment_id'],
          'img_path' => $img_path,
          'status' => 'FAILED',
          'error_type' => 1,
        );
        $wpdb->replace($table_name, $data);
      } else {
        $image_size = filesize($paths['img_file_path']);
        $image_created_timestamp = date("Y-m-d H:i:s", filemtime($paths['img_file_path']));

        $data = array();
        $data['attachment_id'] = $image['attachment_id'];
        $data['img_path'] = $paths['image_rel_path'];
        $data['sirv_path'] = $paths['sirv_rel_path_encoded'];
        $data['size'] = $image_size;
        $data['status'] = 'NEW';
        $data['error_type'] = NULL;
        $data['timestamp'] = $image_created_timestamp;
        $data['timestamp_synced'] = NULL;
        $data['checks'] = 0;
        $data['timestamp_checks'] = NULL;

        $result = $wpdb->insert($table_name, $data);
      }
    }
  }
}


add_action('wp_ajax_sirv_refresh_stats', 'sirv_refresh_stats');
function sirv_refresh_stats(){
  if (!(defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  echo json_encode(sirv_getStorageInfo(true));
  wp_die();
}


//ajax request to clear image cache
add_action('wp_ajax_sirv_clear_cache', 'sirv_clear_cache_callback');
function sirv_clear_cache_callback(){
  if (!(is_array($_POST) && isset($_POST['clean_cache_type']) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  $clean_cache_type = $_POST['clean_cache_type'];

  global $wpdb;
  $images_t = $wpdb->prefix . 'sirv_images';
  $posts_t = $wpdb->prefix . 'posts';

  if ($clean_cache_type == 'failed') {
    $result = $wpdb->delete($images_t, array('status' => 'FAILED'));
  } else if ($clean_cache_type == 'synced') {
    $result = $wpdb->delete($images_t, array('status' => 'SYNCED'));
  } else if ($clean_cache_type == 'garbage') {
    $atch_ids = $wpdb->get_results("SELECT attachment_id as attachment_id
                              FROM $images_t
                              WHERE attachment_id
                              NOT IN (SELECT $posts_t.ID FROM $posts_t)
      ", ARRAY_N);

    //$ids = implode( ",", sirv_flattern_array($a_ids));
    $ids_chunks = array_chunk(sirv_flattern_array($atch_ids), 500);

    foreach ($ids_chunks as $ids) {
      $ids_str = implode(",", $ids);
      $result = $wpdb->query("DELETE FROM $images_t WHERE attachment_id IN ($ids_str)");
    }
  } else if ($clean_cache_type == 'all') {

    $delete = $wpdb->query("TRUNCATE TABLE $images_t");
  }

  echo json_encode(sirv_getCacheInfo());

  wp_die();
}


//use ajax request to show data from sirv
add_action('wp_ajax_sirv_get_content', 'sirv_get_content');
function sirv_get_content(){
  if (!(is_array($_POST) && isset($_POST['path']) && defined('DOING_AJAX') && DOING_AJAX)) {
    echo json_encode(array('error' => 'Action denied'));
    wp_die();
  }

  if (!sirv_is_allow_ajax_connect('sirv_logic_ajax_validation_nonce', 'edit_posts')) {
    echo json_encode(array('error' => 'Access to the requested resource is forbidden'));
    wp_die();
  }

  $sirv_path = empty($_POST['path']) ? '/' : $_POST['path'];
  $continuation = '';

  $sirv_path = stripcslashes($sirv_path);

  $sirvAPIClient = sirv_getAPIClient();

  $content = array(
    'sirv_url' => get_option('SIRV_CDN_URL'),
    'current_dir' => rawurldecode($sirv_path),
    'content' => array('images' => array(), 'dirs' => array(), 'spins' => array(), 'files' => array(), 'videos' => array(), 'audio' => array(), 'models' => array()),
    'continuation' => ''
  );

  $data = array();

  do {
    $result = $sirvAPIClient->getContent($sirv_path, $continuation);
    $continuation = '';
    if ($result) {
      $data = array_merge($data, $result->contents);
      if (isset($result->continuation)) $continuation = $result->continuation;
    }
  } while ($continuation);

  $content['content'] = sirv_sort_content_data($data);

  echo json_encode($content);

  wp_die();
}


function sirv_sort_content_data($data){
  $restricted_folders = array('.Trash', '.processed', '.well-known', 'Shared', 'Profiles');
  $content = array('images' => array(), 'dirs' => array(), 'spins' => array(), 'files' => array(), 'videos' => array(), 'audio' => array(), 'models' => array());
  $files = array();

  foreach ($data as $file) {
    if ($file->isDirectory) {
      if ( !in_array($file->filename, $restricted_folders) ) $content['dirs'][] = $file;
    } else {
      $files[] = $file;
    }
  }

  foreach ($files as $file) {
    $ext = pathinfo($file->filename, PATHINFO_EXTENSION);
    $f_type = sirv_get_file_type($file->contentType);

    if ($f_type['type'] == 'image') {
      $content['images'][] = $file;
    } else if ($ext == 'spin') {
      $content['spins'][] = $file;
    } else if ($f_type['type'] == 'video') {
      $content['videos'][] = $file;
    }else if($f_type['type'] == 'model'){
      $content['models'][] = $file;
    } else if ($f_type['type'] == 'audio') {
      $content['audio'][] = $file;
    }else {
      $content['files'][] = $file;
    }
  }


  $content = sirv_usort_obj_content($content, 'dirs');
  $content = sirv_usort_obj_content($content, 'spins');
  $content = sirv_usort_obj_content($content, 'images');
  $content = sirv_usort_obj_content($content, 'videos');
  $content = sirv_usort_obj_content($content, 'models');
  $content = sirv_usort_obj_content($content, 'audio');
  $content = sirv_usort_obj_content($content, 'files');

  return $content;
}


function sirv_get_file_type($type){
  $tmp_t = explode('/', $type);

  return array('type' => $tmp_t[0], 'subtype' => $tmp_t[1]);
}


function sirv_usort_obj_content($data, $type){
  usort($data[$type], function ($a, $b) {
    return strnatcasecmp($a->filename, $b->filename);
  });

  return $data;
}


function sirv_remove_dirs($dirs, $dirs_to_remove){
  $tmp_arr = array();
  foreach ($dirs as $key => $dir) {
    if (!in_array($dir['Prefix'], $dirs_to_remove)) {
      $tmp_arr[] = $dir;
    }
  }
  return $tmp_arr;
}


//use ajax to upload images on sirv.com
add_action('wp_ajax_sirv_upload_files', 'sirv_upload_files_callback');

function sirv_upload_files_callback(){

  if (!(is_array($_POST) && is_array($_FILES) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  if (!sirv_is_allow_ajax_connect('sirv_logic_ajax_validation_nonce', 'edit_posts')) {
    echo json_encode(array('error' => 'Access to the requested resource is forbidden'));
    wp_die();
  }


  $imagePaths =  json_decode(stripslashes($_POST['imagePaths']), true);

  $current_dir = stripslashes($_POST['current_dir']);
  $current_dir = $current_dir == '/' ? '' : $current_dir;
  $total = intval($_POST['totalFiles']);
  $totalPart = count($_FILES);
  $arr_content = array();

  $APIClient = sirv_getAPIClient();

  for ($i = 0; $i < $totalPart; $i++) {

    $file_path = Utils::startsWith('/', $imagePaths[$i]) ? substr($imagePaths[$i], 1) : $imagePaths[$i];
    //$sirv_path = $current_dir . urlencode($file_path);
    $sirv_path = urlencode($current_dir . $file_path);
    $file = $_FILES[$i]["tmp_name"];

    $result = $APIClient->uploadImage($file, $sirv_path);

    session_id('image-uploading-status');
    session_start();

    $image_num = isset($_SESSION['uploadingStatus']['processedImage']) ? $_SESSION['uploadingStatus']['processedImage'] + 1 : 1;

    $arr_content['percent'] = intval($image_num / $total * 100);
    $arr_content['processedImage'] = $image_num;
    $arr_content['count'] = $total;

    $image_num++;

    $_SESSION['uploadingStatus'] = $arr_content;
    session_write_close();

    if (!empty($result)) echo json_encode($result);
  }

  wp_die();
}


//upload big file by chanks
add_action('wp_ajax_sirv_upload_file_by_chanks', 'sirv_upload_file_by_chanks_callback');

function sirv_upload_file_by_chanks_callback(){
  if (!(is_array($_POST) && isset($_POST['binPart']) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  if (!sirv_is_allow_ajax_connect('sirv_logic_ajax_validation_nonce', 'edit_posts')) {
    echo json_encode(array('error' => 'Access to the requested resource is forbidden'));
    wp_die();
  }

  $arr_content = array();

  $uploads_dir = wp_get_upload_dir();
  $wp_uploads_dir = $uploads_dir['basedir'];

  $APIClient = sirv_getAPIClient();

  $tmp_dir = $wp_uploads_dir . '/tmp_sirv_chunk_uploads/';
  sirv_checkAndCreatekDir($tmp_dir);

  $current_dir = stripslashes($_POST['currentDir']);
  $current_dir = $current_dir == '/' ? '' : $current_dir;

  $filename = $_POST['partFileName'];
  $fileRelativePath = Utils::startsWith('/', $_POST['partFilePath']) ? substr($_POST['partFilePath'], 1) : $_POST['partFilePath'];
  $binPart = sirv_decode_chunk($_POST['binPart']);
  $partNum = $_POST['partNum'];
  $totalParts = $_POST['totalParts'];
  $totalOverSizedFiles =  intval($_POST['totalFiles']);

  $filePath = $tmp_dir . $filename;
  $sirv_path = urlencode($current_dir . $fileRelativePath);

  file_put_contents($filePath, $binPart, FILE_APPEND);
  chmod($filePath, 0777);


  if ($partNum == 1) {
    session_id("image-uploading-status");
    session_start();
    $_SESSION['uploadingStatus']['isPartFileUploading'] = true;
    $_SESSION['uploadingStatus']['percent'] = isset($_SESSION['uploadingStatus']['percent']) ? $_SESSION['uploadingStatus']['percent'] : null;
    $_SESSION['uploadingStatus']['processedImage'] = isset($_SESSION['uploadingStatus']['processedImage']) ? $_SESSION['uploadingStatus']['processedImage'] : null;
    $_SESSION['uploadingStatus']['count'] = isset($_SESSION['uploadingStatus']['count']) ? $_SESSION['uploadingStatus']['count'] : null;
    session_write_close();
  }

  if($partNum < $totalParts){
    echo json_encode(array('status' => 'processing', 'stop' => false));

  }

  if ($partNum == $totalParts) {

    $APIClient = sirv_getAPIClient();

    $result = $APIClient->uploadImage($filePath, $sirv_path);

    unlink($filePath);

    session_id("image-uploading-status");
    session_start();

    $arr_content['processedImage'] = empty($_SESSION['uploadingStatus']['processedImage']) ? 1 : $_SESSION['uploadingStatus']['processedImage'] + 1;
    $arr_content['count'] = empty($_SESSION['uploadingStatus']['count']) ? $totalOverSizedFiles : $_SESSION['uploadingStatus']['count'];
    $arr_content['percent'] = intval($arr_content['processedImage'] / intval($arr_content['count']) * 100);

    $_SESSION['uploadingStatus'] = $arr_content;
    session_write_close();

    if ($arr_content['processedImage'] == $arr_content['count']){
      echo json_encode(array('stop' => true));
    }else{
      echo json_encode(array('status' => 'processing', 'stop' => false));
    }
  }

  wp_die();
}


add_action('wp_ajax_sirv_migrate_wai_data', 'sirv_migrate_wai_data');
function sirv_migrate_wai_data(){
  if (!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)) {
    echo json_encode(array("error" => "Ajax action does not possible or missed data."));
    wp_die();
  }

  if (!sirv_is_allow_ajax_connect('ajax_validation_nonce', 'manage_options')) {
    echo json_encode(array('error' => 'Access to the requested resource is forbidden'));
    wp_die();
  }

  require_once(SIRV_PLUGIN_SUBDIR_PATH . 'includes/classes/woo.additional.images.migrate.class.php');

  $result = WooAdditionalImagesMigrate::migrate(1);

  echo json_encode($result);
  wp_die();
}


//monitoring status for creating sirv cache
add_action('wp_ajax_sirv_get_image_uploading_status', 'sirv_get_image_uploading_status_callback');
function sirv_get_image_uploading_status_callback(){

  if (!(is_array($_POST) && isset($_POST['sirv_get_image_uploading_status']) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  session_id('image-uploading-status');
  session_start();
  $session_data = isset($_SESSION['uploadingStatus']) ? $_SESSION['uploadingStatus'] : array();

  if (!empty($session_data)) {
    if (intval($session_data['percent']) >= 100) {
      echo json_encode($session_data);
      session_destroy();
    } else {
      echo json_encode($session_data);
      session_write_close();
    }
  } else {
    session_write_close();
    echo json_encode(array("percent" => null, "processedImage" => null, 'count' => null));
  }

  wp_die();
}


//use ajax to store gallery shortcode in DB
add_action('wp_ajax_sirv_save_shortcode_in_db', 'sirv_save_shortcode_in_db');

function sirv_save_shortcode_in_db(){

  if (!(is_array($_POST) && isset($_POST['shortcode_data']) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  global $base_prefix;
  global $wpdb;

  $table_name = $base_prefix . 'sirv_shortcodes';



  $data = $_POST['shortcode_data'];
  $data['images'] = serialize($data['images']);
  $data['shortcode_options'] = serialize($data['shortcode_options']);
  $data['timestamp'] = date("Y-m-d H:i:s");

  unset($data['isAltCaption']);

  $wpdb->insert($table_name, $data);

  echo $wpdb->insert_id;


  wp_die();
}


//use ajax to get data from DB by id
add_action('wp_ajax_sirv_get_row_by_id', 'sirv_get_row_by_id');

function sirv_get_row_by_id(){

  if (!(is_array($_POST) && isset($_POST['row_id']) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  global $base_prefix;
  global $wpdb;

  $table_name = $base_prefix . 'sirv_shortcodes';

  $id = intval($_POST['row_id']);

  $row =  $wpdb->get_row("SELECT * FROM $table_name WHERE id = $id", ARRAY_A);

  $row['images'] = unserialize($row['images']);
  $row['shortcode_options'] = unserialize($row['shortcode_options']);

  echo json_encode($row);

  //echo json_encode(unserialize($row['images']));


  wp_die();
}


//use ajax to get data from DB for shortcodes page
add_action('wp_ajax_sirv_get_shortcodes_data', 'sirv_get_shortcodes_data');

function sirv_get_shortcodes_data(){

  if (!(is_array($_POST) && isset($_POST['shortcodes_page']) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  $limit = $_POST['itemsPerPage'] ? intval($_POST['itemsPerPage']) : 10;
  $sh_page = intval($_POST['shortcodes_page']);

  global $base_prefix;
  global $wpdb;

  $sh_table = $base_prefix . 'sirv_shortcodes';

  $sh_count = $wpdb->get_row("SELECT COUNT(*) AS count FROM $sh_table", ARRAY_A);
  $sh_pages = ceil(intval($sh_count['count']) / $limit);
  $sh_pages = $sh_pages === 0 ? 1 : $sh_pages;

  if ($sh_page > $sh_pages) $sh_page = $sh_pages;

  $offset =  ($sh_page - 1) * $limit;
  $offset = $offset < 0 ? 0 : $offset;

  $shortcodes =  $wpdb->get_results("
                SELECT *
                FROM $sh_table
                ORDER BY $sh_table.id
                DESC
                LIMIT $limit
                OFFSET $offset
            ", ARRAY_A);

  foreach ($shortcodes as $index => $shortcode) {
    $shortcodes[$index]['images'] = unserialize($shortcode['images']);
    $shortcodes[$index]['shortcode_options'] = unserialize($shortcode['shortcode_options']);
  }

  $tmp_arr = array('count' => $sh_count['count'], 'shortcodes' => $shortcodes);

  echo json_encode($tmp_arr);


  wp_die();
}


//use ajax to get data from DB for shortcodes page
add_action('wp_ajax_sirv_duplicate_shortcodes_data', 'sirv_duplicate_shortcodes_data');

function sirv_duplicate_shortcodes_data(){
  if (!(is_array($_POST) && isset($_POST['shortcode_id']) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  $sh_id = intval($_POST['shortcode_id']);

  global $base_prefix;
  global $wpdb;
  $sh_table = $base_prefix . 'sirv_shortcodes';

  $data = $wpdb->get_row("
                          SELECT *
                          FROM $sh_table
                          WHERE $sh_table.id = $sh_id
                            ", ARRAY_A);

  unset($data['id']);

  $result = $wpdb->insert($sh_table, $data);

  if ($result === 1) {
    echo 'Shortcode ID=> ' . $sh_id . ' was duplicated';
  } else {
    echo 'Duplication was failed';
  }


  wp_die();
}


//use ajax to delete shortcodes
add_action('wp_ajax_sirv_delete_shortcodes', 'sirv_delete_shortcodes');

function sirv_delete_shortcodes(){
  if (!(is_array($_POST) && isset($_POST['shortcode_ids']) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  global $base_prefix;
  global $wpdb;

  $sh_table = $base_prefix . 'sirv_shortcodes';

  $shortcode_ids = json_decode($_POST['shortcode_ids']);

  function clean_ids($id)
  {
    return intval($id);
  }

  if (!empty($shortcode_ids)) {
    $ids = implode(',', array_map('clean_ids', $shortcode_ids));

    $result = $wpdb->query("DELETE FROM $sh_table WHERE ID IN($ids)");

    $msg = $result > 0 ? "Shortcodes were successful delete" : "Something went wrong during deleting shortcodes";
    echo $msg;
  } else {
    echo "Nothing to delete";
  }

  wp_die();
}


//use ajax to save edited shortcode
add_action('wp_ajax_sirv_update_sc', 'sirv_update_sc');

function sirv_update_sc(){

  if (!(is_array($_POST) && isset($_POST['row_id']) && isset($_POST['shortcode_data']) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  global $base_prefix;
  global $wpdb;

  $table_name = $base_prefix . 'sirv_shortcodes';

  $id = intval($_POST['row_id']);
  $data = $_POST['shortcode_data'];

  unset($data['isAltCaption']);

  $data['images'] = serialize($data['images']);
  $data['shortcode_options'] = serialize($data['shortcode_options']);


  $row =  $wpdb->update($table_name, $data, array('ID' => $id));

  echo $row;


  wp_die();
}


//use ajax to add new folder in sirv
add_action('wp_ajax_sirv_add_folder', 'sirv_add_folder');

function sirv_add_folder(){

  if (!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  if (!sirv_is_allow_ajax_connect('sirv_logic_ajax_validation_nonce', 'edit_posts')) {
    echo json_encode(array('error' => 'Access to the requested resource is forbidden'));
    wp_die();
  }


  $path = $_POST['current_dir'] . $_POST['new_dir'];

  $APIClient = sirv_getAPIClient();
  $res = $APIClient->createFolder($path);

  echo json_encode(array('isNewDirCreated' => $res));

  wp_die();
}


//use ajax to check customer login details
add_action('wp_ajax_sirv_check_connection', 'sirv_check_connection', 10, 1);
function sirv_check_connection(){

  if (!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  $msg_ok = "Connection: OK";
  $msg_failed = 'Connection failed. Please check if you logged correctly here <a href="admin.php?page=' . SIRV_PLUGIN_RELATIVE_SUBDIR_PATH . 'submenu_pages/account.php" target="_blank">Account settings</a>.';

  $APIClient = sirv_getAPIClient();
  $isSirvConnection = $APIClient->checkCredentials();

  $message = $isSirvConnection ? $msg_ok : $msg_failed;

  echo $message;


  wp_die();
}


//use ajax to remove sirv notice
add_action('wp_ajax_sirv_dismiss_notice', 'sirv_dismiss_notice', 10);
function sirv_dismiss_notice(){
  if (!(is_array($_POST) && isset($_POST['notice_id']) && defined('DOING_AJAX') && DOING_AJAX)) {
    wp_die();
  }

  if (!sirv_is_allow_ajax_connect('sirv_rewiew_ajax_validation_nonce', 'edit_options')) {
    echo json_encode(array('error' => 'Access to the requested resource is forbidden'));
    wp_die();
  }

  $notice_id = $_POST['notice_id'];
  $dismiss_type = $_POST['dismiss_type'];
  $custom_time = intval($_POST['custom_time']);

  if (in_array($dismiss_type, array('current_day', 'day', 'custom'))){
    if ($dismiss_type == 'current_day') {
      $start_datetime = new DateTimeImmutable();
      $end_datetime = new DateTimeImmutable('tomorrow');
      $diff = $end_datetime->getTimestamp() - $start_datetime->getTimestamp();
      set_transient($notice_id, true, $diff);
    }

    if ($dismiss_type == 'day') {
      set_transient($notice_id, true, DAY_IN_SECONDS);
    }

    if ($dismiss_type == 'custom'){
      set_transient($notice_id, true, $custom_time);
    }
  }

  update_option($notice_id, $dismiss_type);

  echo 'Notice ' . $notice_id . ' set status to ' . $dismiss_type;

  wp_die();
}


//use ajax to delete files
add_action('wp_ajax_sirv_delete_files', 'sirv_delete_files');
function sirv_delete_files(){
  if (!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  if (!sirv_is_allow_ajax_connect('sirv_logic_ajax_validation_nonce', 'edit_posts')) {
    echo json_encode(array('error' => 'Access to the requested resource is forbidden'));
    wp_die();
  }

  $filenames = $_POST['filenames'];

  $APIClient = sirv_getAPIClient();

  $result = $APIClient->deleteFiles($filenames);

  echo json_encode($result);

  wp_die();
}


//use ajax to check if options is empty or not
add_action('wp_ajax_sirv_check_empty_options', 'sirv_check_empty_options');
function sirv_check_empty_options(){
  $account_name = getValue::getOption('SIRV_ACCOUNT_NAME');
  $cdn_url = getValue::getOption('SIRV_CDN_URL');

  if (empty($account_name) || empty($cdn_url)) {
    echo false;
  } else {
    echo true;
  }

  wp_die();
}


//use ajax to get sirv profiles
add_action('wp_ajax_sirv_get_profiles', 'sirv_get_profiles');
function sirv_get_profiles(){

  $profiles = sirv_getProfilesList();
  echo sirv_renderProfilesOptopns($profiles);

  wp_die();
}


function sirv_getProfilesList(){
  global $profiles;

  if( !isset($profiles) ){
    $APIClient = sirv_getAPIClient();
    $profiles = $APIClient->getProfiles();
    if ($profiles && !empty($profiles->contents) && is_array($profiles->contents)) {
      $profilesList = array();
      foreach ($profiles->contents as $profile) {
        if (preg_match('/\.profile$/ims', $profile->filename) && $profile->filename != 'Default.profile') {
          $profilesList[] = preg_replace('/(.*?)\.profile$/ims', '$1', $profile->filename);
        }
      }
      sort($profilesList);
      $profiles = $profilesList;
      return $profiles;
    }
    return array();
  }else{
    return $profiles;
  }
}


function sirv_renderProfilesOptopns($profiles){
  $profiles_tpl = '';

  if (!empty($profiles)) {
    $profiles_tpl .= '<option disabled>Choose profile</option><option value="">-</option>';
    foreach ($profiles as $profile) {
      $profiles_tpl .= "<option value='{$profile}'>{$profile}</option>";
    }
  }

  return $profiles_tpl;
}


//use ajax to send message from sirv plugin
add_action('wp_ajax_sirv_send_message', 'sirv_send_message');

function sirv_send_message(){
  if (!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  $summary = stripcslashes($_POST['summary']);
  $text = stripcslashes($_POST['text']);
  $name = $_POST['name'];
  $emailFrom = $_POST['emailFrom'];

  $account_name = get_option('SIRV_ACCOUNT_NAME');
  $storageInfo = sirv_getStorageInfo();

  $text .= PHP_EOL . 'Account name: ' . $account_name;
  $text .= PHP_EOL . 'Plan: ' . $storageInfo['plan']['name'];


  $headers = array(
    'From:' . $name . ' <' . $emailFrom . '>'
  );

  echo wp_mail('support@sirv.com', $summary, $text, $headers);

  wp_die();
}


//use ajax to account connect
add_action('wp_ajax_sirv_init_account', 'sirv_init_account');
function sirv_init_account(){
  if (!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  $response = array();

  $email = trim(strtolower($_POST['email']));
  $pass = trim(stripslashes($_POST['pass']));
  $f_name = $_POST['firstName'];
  $l_name = $_POST['lastName'];
  $alias = $_POST['accountName'];
  $is_new_account = (bool)$_POST['isNewAccount'];

  $sirvAPIClient = sirv_getAPIClient();

  if (!empty($is_new_account) && $is_new_account) {
    $account = $sirvAPIClient->registerAccount(
      $email,
      $pass,
      trim(strtolower($f_name)),
      trim(strtolower($l_name)),
      trim(strtolower($alias))
    );
    if (!$account) {
      $lastResp = $sirvAPIClient->getLastResponse();
      if (
        $lastResp->result->message == 'Supplied data is not valid' &&
        !empty($lastResp->result->validationErrors) &&
        preg_match('/AccountAlias/ims', $lastResp->result->validationErrors[0]->message)
      ) {
        $lastResp->result->message = 'Wrong value for account name. Please fix it.';
      }

      if ($lastResp->result->message == 'Duplicate entry') {
        $lastResp->result->message = 'That email address is already registered. Please login instead.';
      }

      $response['error'] = $lastResp->result->message;

      echo json_encode($response);
      wp_die();
    }
  }

  $response = sirv_get_users_list_data($email, $pass);

  echo json_encode($response);

  wp_die();
}


add_action('wp_ajax_sirv_get_users_list', 'sirv_get_users_list');
function sirv_get_users_list(){
  if (!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  $response = array();

  $email = trim(strtolower($_POST['email']));
  $pass = trim(stripslashes($_POST['pass']));
  $otpToken = trim($_POST['otpToken']);

  $response = sirv_get_users_list_data($email, $pass, $otpToken);

  echo json_encode($response);
  wp_die();
}


function sirv_get_users_list_data($email, $pass, $otpToken = NULL){
  $user_role_msg = "User $email does not have <a target=\"_blank\" href=\"https://sirv.com/help/articles/users-roles-permissions/\">permission</a> to connect this plugin. Ask for your role to be changed to Admin or Owner.";
  $error_credentials_msg =
    'That email or password is incorrect. Please check and try again. (' .
    '<a href="https://my.sirv.com/#/password/forgot" target="_blank">' .
    'Forgot your password' . '</a>?)';
  $error_otp_token_msg = "Authentification code is incorrect. Please try again";

  $response = array();
  $allowRoles = ['owner', 'primaryOwner', 'admin'];
  $sirvAPIClient = sirv_getAPIClient();

  $users = $sirvAPIClient->getUsersList($email, $pass, $otpToken);

  if(isset($users['isOtpToken']) && (bool)$users['isOtpToken']){
    $response['isOtpToken'] = true;
    return $response;
  }

  if (empty($users) || !is_array($users)) {
    $lastResp = $sirvAPIClient->getLastResponse();
    if ($lastResp->result->message == 'Forbidden') {
      $lastResp->result->message = empty($otpToken) ? $error_credentials_msg : $error_otp_token_msg;
    }
    $error = empty($lastResp->result->message) ? $lastResp->error : $lastResp->result->message;
    $error = empty($error) && $lastResp->http_code === 0 ? 'Plugin cannot connect to the Sirv server. Possible issues: absent internet connection, restrict rules for the firewall, disabled CURL functions.' : $error;
    $error = empty($error) && $lastResp->http_code !== 200 ? "Server returned code {$lastResp->http_code}. {$lastResp->http_code_text}" . '<br><br>Returned response:<pre><code>' . htmlentities($lastResp->result_txt) . '</code></pre>'  : $error;
    $error = empty($error) && empty($users) ? $user_role_msg : $error;
    $error = empty($error) ? 'Unknown error during request to Sirv API' : $error;

    $response['error'] = $error;
  } else {
    $users = sirv_filterUsersByStatus($users);

    $response = array(
        "allow_users" => sirv_filterUsersByRoles($users, $allowRoles),
        "deny_users" => sirv_filterUsersByRoles($users, $allowRoles, true)
      );
  }

  return $response;
}


function sirv_filterUsersByRoles($users, $filtered_roles, $isExclude=false){

  if(! function_exists('sirv_roles_filter')){
    $sirv_roles_filter = function($user) use ($filtered_roles, $isExclude){
      return $isExclude ? !in_array($user->role, $filtered_roles) : in_array($user->role, $filtered_roles);
    };
  }

  return array_values(array_filter($users, $sirv_roles_filter));
}


function sirv_filterUsersByStatus($users){

  if(! function_exists('sirv_status_filter')){
    function sirv_status_filter ($user){
      return (bool) $user->active === true;
    };
  }

  return array_values(array_filter($users, 'sirv_status_filter'));
}


add_action('wp_ajax_sirv_setup_credentials', 'sirv_setup_credentials');
function sirv_setup_credentials(){
  if (!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  $email = trim(strtolower($_POST['email']));
  $alias = $_POST['sirv_account'];

  $sirvAPIClient = sirv_getAPIClient();

  if (!empty($alias)) {
    $res = $sirvAPIClient->setupClientCredentials($alias);
    if ($res) {
      update_option('SIRV_ACCOUNT_EMAIL', sanitize_email($email));
      $res = $sirvAPIClient->setupS3Credentials($email);
      if ($res) {
        $sirv_folder = get_option('SIRV_FOLDER');

        $sirvAPIClient->createFolder('/' . $sirv_folder);
        $sirvAPIClient->setFolderOptions($sirv_folder, array('scanSpins' => false));

        echo json_encode(
          array('connected' => '1')
        );
        wp_die();
      }
    }
    echo json_encode(
      array('error' => 'An error occurred.')
    );
    wp_die();
  }

  echo json_encode(
    array('error' => 'An error occurred.')
  );

  wp_die();
}

add_action('wp_ajax_sirv_disconnect', 'sirv_disconnect');
function sirv_disconnect(){
  if (!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  if( !sirv_is_allow_ajax_connect('ajax_validation_nonce', 'manage_options') ){
    echo json_encode(array('error' => 'Access to the requested resource is forbidden'));
    wp_die();
  }

  update_option('SIRV_CLIENT_ID', '', 'no');
  update_option('SIRV_CLIENT_SECRET', '', 'no');
  update_option('SIRV_TOKEN', '', 'no');
  update_option('SIRV_TOKEN_EXPIRE_TIME', '', 'no');
  update_option('SIRV_MUTE', '', 'no');
  update_option('SIRV_ACCOUNT_EMAIL', '');
  update_option('SIRV_ACCOUNT_NAME', '');
  update_option('SIRV_STAT', '', 'no');
  update_option('SIRV_CDN_URL', '');

  echo json_encode(array('disconnected' => 1));

  wp_die();
}


//for nonce need use field '_ajax_nonce', or '_wpnonce'
//manage_options - super admin and admin
//edit_posts for manage access to the sirv add sirv media endpoints
function sirv_is_allow_ajax_connect($ajax_nonce, $user_can_cap){
  return (check_ajax_referer($ajax_nonce) !== false && current_user_can($user_can_cap));
}


add_action('wp_ajax_sirv_get_error_data', 'sirv_get_error_data');
function sirv_get_error_data(){
  if (!(is_array($_POST) && isset($_POST['error_id']) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  global $wpdb;
  $table_name = $wpdb->prefix . 'sirv_images';
  $error_id = intval($_POST['error_id']);
  $report_type = $_POST['report_type'];

  $results = $wpdb->get_results("SELECT  img_path, checks, timestamp_synced, timestamp_checks, size, attachment_id FROM $table_name WHERE status = 'FAILED' AND error_type = $error_id ORDER BY attachment_id", ARRAY_A);

  $uploads_dir = wp_get_upload_dir();
  $url_images_path = $uploads_dir['baseurl'];

  $err_msgs = array('File name/path missing from WordPress media library', 'Empty attachment');

  if ($results) {
    require_once(SIRV_PLUGIN_SUBDIR_PATH . 'includes/classes/report.class.php');

    $fields = array('Image URL', 'Attempts', 'Last attempt', 'File size', 'WP Attachment ID');
    $fimages = array();

    foreach ($results as $row) {
/*       $row['error'] = in_array($row['img_path'], $err_msgs) ? true : false;
      $row['img_path'] = ( $row['error'] || stripos($row['img_path'], 'http') !== false ) ? $row['img_path'] : $url_images_path . $row['img_path'];
      $size = Utils::getFormatedFileSize((int) $row['size']);
      $row['size'] = $size == '-' ? '' : $size;
      //$row['timestamp_checks'] = !is_null($row['timestamp_cheks']) ? date('F j, Y, h:i A', (int)$row['timestamp_checks']) : 'Not available';
      $row['timestamp_checks'] = sirv_get_failed_image_date($row['timestamp_synced'], $row['timestamp_checks']); */

      $isError = in_array($row['img_path'], $err_msgs) ? true : false;
      $size = Utils::getFormatedFileSize((int) $row['size']);
      $full_path = $url_images_path . $row['img_path'];

      $record = array();

      $record['img_path'] = ($isError || stripos($row['img_path'], 'http') !== false) ? "{$row['img_path']}" : "<a href=\"{$full_path}\" target=\"_blank\">{$full_path}</a>";
      $record['attempts'] = $row['checks'];
      $record['last_attempt_date'] = sirv_get_failed_image_date($row['timestamp_synced'], $row['timestamp_checks']);
      $record['filesize'] = $size == '-' ? '' : $size;
      $record['attachment_id'] = $row['attachment_id'];
      $fimages[] = $record;
    }

    if ($report_type == 'html') {
      array_unshift($fields, '#');
      $data = array('fields' => $fields, 'data' => $fimages);
      echo Report::generateFailedImagesHTMLReport($data, $error_id);
    } else {
      array_unshift($fimages, $fields);
      echo Report::generateFailedImagesCSVReport($fimages);
    }
  } else {
    echo '';
  }

  wp_die();
}


function sirv_get_failed_image_date($timestamp_synced, $timestamp_checks){
  if (! is_null($timestamp_checks)) {
    return date('F j, Y, h:i A', (int) $timestamp_checks);
  }

  if (! is_null($timestamp_synced)) {
    return $timestamp_synced;
  }

  return 'Not available';
}


add_action('wp_ajax_sirv_get_search_data', 'sirv_get_search_data');

function sirv_get_search_data(){
  if (!(is_array($_POST) && isset($_POST['search_query']) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  if (!sirv_is_allow_ajax_connect('sirv_logic_ajax_validation_nonce', 'edit_posts')) {
    echo json_encode(array('error' => 'Access to the requested resource is forbidden'));
    wp_die();
  }

  require_once(SIRV_PLUGIN_SUBDIR_PATH . 'includes/classes/query-string.class.php');

  $c_query = new QueryString($_POST['search_query']);
  $from = $_POST['from'];
  $dir = isset($_POST['dir']) ? $_POST['dir'] : '';

  $sirvAPIClient = sirv_getAPIClient();

  if(!empty($dir)){
    $res = $sirvAPIClient->search($c_query->getCompiledCurrentDirSearch($dir), $from);
  }else{
    $res = $sirvAPIClient->search($c_query->getCompiledGlobalSearch(), $from);
  }



  if ($res) {
    $res->sirv_url = get_option('SIRV_CDN_URL');
    echo json_encode($res);
  } else echo json_encode(array());

  wp_die();
}


function sirv_remove_first_slash($path){
  return stripos($path[0], "/") === 0 ? substr($path[0], 1) : $path[0];
}


add_action('wp_ajax_sirv_copy_file', 'sirv_copy_file');

function sirv_copy_file(){
  if (!(is_array($_POST) && isset($_POST['copyPath']) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  if (!sirv_is_allow_ajax_connect('sirv_logic_ajax_validation_nonce', 'edit_posts')) {
    echo json_encode(array('error' => 'Access to the requested resource is forbidden'));
    wp_die();
  }

  $file_path = stripslashes($_POST['filePath']);
  $copy_path = stripslashes($_POST['copyPath']);

  $sirvAPIClient = sirv_getAPIClient();
  $result = $sirvAPIClient->copyFile($file_path, $copy_path);

  echo json_encode(array('duplicated' => $result));

  wp_die();
}


add_action('wp_ajax_sirv_rename_file', 'sirv_rename_file');

function sirv_rename_file(){
  if (!(is_array($_POST) && isset($_POST['filePath']) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  if (!sirv_is_allow_ajax_connect('sirv_logic_ajax_validation_nonce', 'edit_posts')) {
    echo json_encode(array('error' => 'Access to the requested resource is forbidden'));
    wp_die();
  }

  $file_path = $_POST['filePath'];
  $new_file_path = $_POST['newFilePath'];

  $sirvAPIClient = sirv_getAPIClient();
  $result = $sirvAPIClient->renameFile($file_path, $new_file_path);


  echo json_encode(array('renamed' => $result));

  wp_die();
}


add_action('wp_ajax_sirv_empty_view_cache', 'sirv_empty_view_cache');

function sirv_empty_view_cache(){
  if (!(is_array($_POST) && isset($_POST['type']) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  $clean_type = $_POST['type'];

  global $wpdb;
  $postmeta_t = $wpdb->prefix . 'postmeta';

  if ($clean_type == "all") {
    $result = $wpdb->query(
      "DELETE FROM $postmeta_t
        WHERE post_id IN (
          SELECT tmp.post_id FROM (
            SELECT post_id FROM $postmeta_t WHERE meta_key = '_sirv_woo_viewf_status')
          as `tmp`)
          AND meta_key IN ('_sirv_woo_viewf_data', '_sirv_woo_viewf_status')"
    );
  } else if ($clean_type == "empty") {
    $result = $result = $wpdb->query(
      "DELETE FROM $postmeta_t
        WHERE post_id IN (
          SELECT tmp.post_id FROM (
            SELECT post_id FROM $postmeta_t WHERE meta_key = '_sirv_woo_viewf_status' AND meta_value = 'EMPTY')
          as `tmp`)
        AND meta_key IN ('_sirv_woo_viewf_data', '_sirv_woo_viewf_status')"
    );
  } else if ($clean_type == "missing") {
    $result = $result = $wpdb->query(
      "DELETE FROM $postmeta_t
        WHERE post_id IN (
          SELECT tmp.post_id FROM (
            SELECT post_id FROM $postmeta_t WHERE meta_key = '_sirv_woo_viewf_status' AND meta_value = 'FAILED')
          as `tmp`)
        AND meta_key IN ('_sirv_woo_viewf_data', '_sirv_woo_viewf_status')"
    );
  } else if($clean_type == "with_prods"){
    $result = $result = $wpdb->query(
      "DELETE FROM $postmeta_t
        WHERE post_id IN (
          SELECT tmp.post_id FROM (
            SELECT post_id FROM $postmeta_t WHERE meta_key = '_sirv_woo_viewf_status' AND NOT(meta_value = 'FAILED' OR meta_value = 'EMPTY'))
          as `tmp`)
        AND meta_key IN ('_sirv_woo_viewf_data', '_sirv_woo_viewf_status')"
    );

  } else if ($clean_type == "without_prods") {
    $result = $result = $wpdb->query(
      "DELETE FROM $postmeta_t
        WHERE post_id IN (
          SELECT tmp.post_id FROM (
            SELECT post_id FROM $postmeta_t WHERE meta_key = '_sirv_woo_viewf_status' AND (meta_value = 'FAILED' OR meta_value = 'EMPTY'))
          as `tmp`)
        AND meta_key IN ('_sirv_woo_viewf_data', '_sirv_woo_viewf_status')"
    );
  }

  echo json_encode(array('result' => $result, 'cache_data' => sirv_get_view_cache_info()));
  wp_die();
}


function sirv_get_view_cache_info(){
  global $wpdb;
  $postmeta_t = $wpdb->prefix . 'postmeta';

  $cache_info = array('all' => 'no data', 'empty' => 'no data', 'missing' => 'no data');

  $query_all = "SELECT COUNT(*) FROM $postmeta_t WHERE meta_key = '_sirv_woo_viewf_status'";
  $query_empty = "SELECT COUNT(*) FROM $postmeta_t WHERE meta_key = '_sirv_woo_viewf_status' AND meta_value = 'EMPTY'";
  $query_missing = "SELECT COUNT(*) FROM $postmeta_t WHERE meta_key = '_sirv_woo_viewf_status' AND meta_value = 'FAILED'";

  $cache_info['all'] = $wpdb->get_var($query_all);
  $cache_info['empty'] = $wpdb->get_var($query_empty);
  $cache_info['missing'] = $wpdb->get_var($query_missing);


  return $cache_info;
}


function sirv_set_image_meta($filename, $attachment_id){
  $res_title = '';
  $res_description = '';

  $meta = sirv_get_attachment_meta($attachment_id);

  if (!empty($meta['title']) || (isset($meta['alt']) && !empty($meta['alt']))) {
    $sirvAPIClient = sirv_getAPIClient();

    if (!empty($meta['title'])) $res_title = $sirvAPIClient->setMetaTitle($filename, $meta['title']);
    if (!empty($meta['description'])) $res_description = $sirvAPIClient->setMetaDescription($filename, $meta['description']);
  }

  return $res_title && $res_description;
}

add_action('wp_ajax_sirv_images_storage_size', 'sirv_images_storage_size');
function sirv_images_storage_size(){
  $start_time = time();
  $start_microtime = microtime(true);

  $upload_dir     = wp_upload_dir();
  $upload_space   = sirv_foldersize( $upload_dir['basedir'] );
  $post_images_count = sirv_get_all_post_images_count();

  $ops_time = time() - $start_time;
  $ops_microtime = microtime(true) - $start_microtime;

    echo json_encode(
      array(
        'time' => $ops_time,
        'microtime_start' => $start_microtime,
        'microtime_end' => microtime(true),
        'microtime' => round($ops_microtime * 1000),
        'size' => Utils::getFormatedFileSize($upload_space),
        'count' => $post_images_count
      )
    );

  wp_die();
}


function sirv_foldersize($path){
  $total_size = 0;
  $files = scandir($path);
  $cleanPath = rtrim($path, '/') . '/';

  foreach ($files as $t) {
    if ('.' != $t && '..' != $t) {
      $currentFile = $cleanPath . $t;
      if (is_dir($currentFile)) {
        $size = sirv_foldersize($currentFile);
        $total_size += $size;
      } else {
        $size = filesize($currentFile);
        $total_size += $size;
      }
    }
  }

  return $total_size;
}


function sirv_get_active_theme_name(){
  $theme = wp_get_theme();
  return $theme->get('Name');
}


add_action('wp_ajax_sirv_css_images_processing', 'sirv_css_images_processing');
function sirv_css_images_processing(){
  //echo sirv_get_css_backimgs_sync_data();
  echo json_encode(sirv_get_session_data('sirv-css-sync-images', 'css_sync_data'));

  wp_die();
}


add_action('wp_ajax_sirv_css_images_get_data', 'sirv_css_images_get_data');
function sirv_css_images_get_data(){
  echo json_encode(array('css_data' => get_option('SIRV_CSS_BACKGROUND_IMAGES')));

  wp_die();
}


add_action('wp_ajax_sirv_css_images_prepare_process', 'sirv_css_images_prepare_process');
function sirv_css_images_prepare_process(){
  if (!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  $isCssPath = isset($_POST['custom_path']) && !empty($_POST['custom_path']);
  $css_path = $isCssPath ? wp_normalize_path(ABSPATH) . $_POST['custom_path'] : get_template_directory();
  $theme = $isCssPath ? $css_path : sirv_get_active_theme_name();

  $isRootCssPath = $css_path == wp_normalize_path(ABSPATH) ? true : false;

  $status = $isRootCssPath ? 'stop' : 'sync';
  $msg = $isRootCssPath ? 'Root site path does not accepted. Please choose more specific folder' : 'Preparing process...';
  $error = $isRootCssPath ? 'Root site path does not accepted. Please choose more specific folder' : '';

  $previous_css_sync_data = json_decode(get_option('SIRV_CSS_BACKGROUND_IMAGES_SYNC_DATA'), true);
  $custom_path = $isCssPath ? $_POST['custom_path'] : $previous_css_sync_data['custom_path'];

  $t = time();
  $css_sync_data = array(
    'scan_type'     => $isCssPath ? 'custom' : 'theme',
    'custom_path'   => $custom_path,
    'theme'         => $theme,
    'last_sync'     => $t,
    'last_sync_str' => date("g:i a e, F jS, Y", (int) $t),
    'img_domain'    => parse_url(home_url(), PHP_URL_HOST),
    'img_count'     => '0',
    'status'        => $status,
    'msg'           => $msg,
    'error'         => $error,
    'css_path'      => wp_normalize_path($css_path),
    'css_files_count' => '0',
    'skipped_images'    => array(),
  );


  sirv_set_session_data('sirv-css-sync-images', 'css_sync_data', $css_sync_data);
  sirv_update_db_option('SIRV_CSS_BACKGROUND_IMAGES_SYNC_DATA', $css_sync_data, 'no');

  echo json_encode($css_sync_data);

  wp_die();
}


add_action('wp_ajax_sirv_css_images_proccess', 'sirv_css_images_proccess');
function sirv_css_images_proccess(){

  $css_sync_data = sirv_get_session_data('sirv-css-sync-images', 'css_sync_data');
  $css_sync_data['msg'] = 'Starting process...';

  sirv_set_session_data('sirv-css-sync-images', 'css_sync_data', $css_sync_data);
  sirv_update_db_option('SIRV_CSS_BACKGROUND_IMAGES_SYNC_DATA', $css_sync_data, 'no');

  $css_path = $css_sync_data['css_path'];

  update_option('SIRV_CSS_BACKGROUND_IMAGES', '');

  $css_files_data = sirv_search_css_files($css_path, $css_sync_data);

  if( empty($css_files_data['css_files']) ){
    echo json_encode($css_files_data['css_sync_data']);
    wp_die();
  }

  $css_images_data = sirv_parse_css_images($css_files_data);

  if( empty($css_images_data['css_images']) ){
    echo json_encode($css_images_data['css_sync_data']);
    wp_die();
  }

  $css_rendered_data = sirv_upload_css_images($css_images_data);

  echo json_encode($css_rendered_data['css_sync_data']);

  wp_die();
}


function sirv_search_css_files($css_path, $css_sync_data){

  try {
    $css_paths = sirv_flatten_css_files_array(sirv_rsearch($css_path, '/.*\.css/'));
    if (!empty($css_paths)) {
      $css_sync_data['msg'] = "Found " . count($css_paths) . ' CSS files...';
      $css_sync_data['css_files_count'] = count($css_paths);
    } else {
      $css_sync_data['error'] = 'Did not find css files.';
      $css_sync_data['status'] = 'stop';
    }
  } catch (Exception $e) {
    $css_paths = array();
    $css_sync_data['error'] = 'Could not find folder. Please check folder path is correct.';
    $css_sync_data['status'] = 'stop';
  }

  sirv_set_session_data('sirv-css-sync-images', 'css_sync_data', $css_sync_data);
  sirv_update_db_option('SIRV_CSS_BACKGROUND_IMAGES_SYNC_DATA', $css_sync_data, 'no');

  return array('css_files' => $css_paths, 'css_sync_data' => $css_sync_data);
}


function sirv_parse_css_images($data){
  $pattern = '/}([^}]*?){(?:[^{])*?(background(?:-image)?:\s?url\([\'\"]?(.*?)[\'\"]?\).*?)\;/is';
  $parsed_items = array();

  $css_sync_data = $data['css_sync_data'];

  foreach ($data['css_files'] as $css_file) {
    $content = @file_get_contents($css_file);
    $is_finded = preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
    if($is_finded){
      foreach ($matches as $item) {
        if(!Utils::startsWith($item[3], 'data:image/')){
          $parsed_items[] = array('class' => trim($item[1]), 'style' => $item[2], 'img_url' => $item[3], 'file_path' => pathinfo($css_file, PATHINFO_DIRNAME) . '/' );
        }
      }
    }
  }

  if(!empty($parsed_items) ){
    $css_sync_data['msg'] = 'Parsed ' . count($parsed_items) . ' images...';
  }else{
    $css_sync_data['error'] = 'No CSS images found.';
    $css_sync_data['status'] = 'stop';
  }

  sirv_set_session_data('sirv-css-sync-images', 'css_sync_data', $css_sync_data);
  sirv_update_db_option('SIRV_CSS_BACKGROUND_IMAGES_SYNC_DATA', $css_sync_data, 'no');

  return array('css_images' => $parsed_items, 'css_sync_data' => $css_sync_data);
}


function sirv_upload_css_images($data){
  define('CSS_PATH', '/CSS_images/');
  $img_cache = array();

  $css_sync_data = $data['css_sync_data'];
  $parsed_items = $data['css_images'];
  $rendered_css = array();
  $sirv_folder = get_option('SIRV_FOLDER');
  $full_css_path = $sirv_folder . CSS_PATH;
  $full_css_img_path = sirv_get_sirv_path($full_css_path);

  $APIClient = sirv_getAPIClient();

  $error_count = 0;
  $error_upload_count = 0;


  foreach ($parsed_items as $item) {
    $img_full_path = sirv_clean_get_params(sirv_getImageURLDiskPath($item));

    if (file_exists($img_full_path)) {
      $img_name = basename($img_full_path);

      if (in_array($img_full_path, $img_cache)) {
        $rendered_css[] = sirv_render_sirv_class($item, $full_css_img_path . $img_name);
        $css_sync_data['msg'] = 'Uploaded ' . count($rendered_css) . ' images...';

        sirv_set_session_data('sirv-css-sync-images', 'css_sync_data', $css_sync_data);
      } else {
        $result = $APIClient->uploadImage($img_full_path, $full_css_path . $img_name);
        if ($result['upload_status'] == 'uploaded') {
          $img_cache[] = $img_full_path;
          $rendered_css[] = sirv_render_sirv_class($item, $full_css_img_path . $img_name);
          $css_sync_data['msg'] = 'Uploaded ' . count($rendered_css) . ' images...';
          $css_sync_data['img_count'] = count($rendered_css);

          sirv_set_session_data('sirv-css-sync-images', 'css_sync_data', $css_sync_data);
        } else {
          $error_upload_count += 1;
          $css_sync_data['skipped_images'][] = $item['img_url'];
        }
      }
    } else {
      $error_count += 1;
      $css_sync_data['skipped_images'][] = $item['img_url'];
    }
  }

  $img_count = count($rendered_css);
  $css_sync_data['img_count'] = $img_count;
  $rendered_css_str = stripcslashes(implode('\n\n', $rendered_css));
  update_option('SIRV_CSS_BACKGROUND_IMAGES', $rendered_css_str);

  $error_msg_not_exists = $error_count > 0 ? $error_count . ' images skipped. ' : '';
  $error_msg_not_upload = $error_upload_count > 0 ? $error_count . ' images did not upload to Sirv. ' : '';

  if( !empty($rendered_css) ){
    $css_sync_data['msg'] = 'Uploaded ' . $img_count . ' images. ' . $error_msg_not_exists;
  }else{
    //$css_sync_data['error'] = $error_msg_not_exists . 'Css images did not upload to the sirv.';
    $css_sync_data['error'] = $error_msg_not_exists . $error_msg_not_upload;
  }

  $css_sync_data['status'] = 'stop';
  sirv_set_session_data('sirv-css-sync-images', 'css_sync_data', $css_sync_data);
  sirv_update_db_option('SIRV_CSS_BACKGROUND_IMAGES_SYNC_DATA', $css_sync_data, 'no');

  return array('css_rendered' => $rendered_css, 'css_sync_data' => $css_sync_data);
}


function sirv_show_css_images_info($css_sync_data){
  $data = array('sync_data' => $css_sync_data['img_count'], 'skip_data' => '');

  if (!isset($css_sync_data['css_files_count']) && !isset($css_sync_data['skipped_images'])) return $data;

  $css_count = (int) $css_sync_data['css_files_count'];
  $synced_images_count = (int) $css_sync_data['img_count'];
  $skipped_images_count = count($css_sync_data['skipped_images']);

  $msg = array(
    'no_css' => 'No CSS files found',
    'one_css' => ' CSS file',
    'few_css' => ' CSS files',
    'one_synced' => ' image synced',
    'few_synced' => ' images synced',
    'one_skipped' => ' image skipped',
    'few_skipped' => ' images skipped',
  );

  if ($css_count == 0){
    $data['sync_data'] = $msg['no_css'];

    return $data;
  } ;

  $css_text = $css_count > 1 ? $css_count . $msg['few_css'] : $css_count . $msg['one_css'];
  $sync_text = $synced_images_count > 1 || $synced_images_count == 0 ? $synced_images_count . $msg['few_synced'] : $synced_images_count . $msg['one_synced'];
  $skipped_text = $skipped_images_count > 1 || $skipped_images_count == 0 ? $skipped_images_count . $msg['few_skipped'] : $skipped_images_count . $msg['one_skipped'];

  $data['sync_data'] =  $sync_text . ', from ' . $css_text;
  $data['skip_data'] = $skipped_images_count > 0 ? $skipped_text : '';

  return $data;
}


function sirv_skipped_images_to_str($css_sync_data){
  if (!isset($css_sync_data['skipped_images'])) return '';

  return implode(PHP_EOL, $css_sync_data['skipped_images']);
}


function sirv_get_css_backimgs_sync_data(){
  //return get_option('SIRV_CSS_BACKGROUND_IMAGES_SYNC_DATA');
}


function sirv_update_db_option($option, $data, $autoload = 'yes', $isJSON = true){
  $newData = $isJSON ? json_encode($data) : $data;
  update_option($option, $newData, $autoload);
}


function sirv_set_session_data($session_id, $session_key, $session_data){
  session_id($session_id);
  session_start();


  $_SESSION[$session_key] = $session_data;
  session_write_close();
}


function sirv_get_session_data($session_id, $session_key){
  session_id($session_id);
  session_start();

  $session_data = $_SESSION[$session_key];

  session_write_close();

  return $session_data;
}


function sirv_rsearch($folder, $pattern){
  $dir = new RecursiveDirectoryIterator($folder);
  $ite = new RecursiveIteratorIterator($dir);
  $files = new RegexIterator($ite, $pattern, RegexIterator::ALL_MATCHES);
  $fileList = array();
  foreach ($files as $file) {
    $fileList = array_merge($fileList, $file);
  }
  return $fileList;
}


function sirv_hasImageUrlSameSiteDomain($image_url){
  $home_url = home_url();

  return stripos($image_url, $home_url) !== false;
}


function sirv_getImageURLDiskPath($css_item){
  $root_path = wp_normalize_path(ABSPATH);
  $home_url = home_url();
  $home_url_host = parse_url($home_url, PHP_URL_HOST);
  //$home_url_host = sirv_get_home_url_host($home_url);
  $pattern = '/(https?:)?\/\/' . $home_url_host . '/is';
  if(sirv_isRelativePath($css_item['img_url'], $pattern)){
    $full_img_path = realpath($css_item['file_path'] . $css_item['img_url']);
  }else{
    $full_img_path = str_replace($home_url . '/', $root_path, $css_item['img_url']);
  }

  return wp_normalize_path($full_img_path);
}


function sirv_get_home_url_host($home_url){
  $parsed_url = parse_url($home_url);
  $port = isset($parsed_url["port"]) ? ":{$parsed_url['port']}" : '';

  return $parsed_url['host'] . $port;
}


function sirv_isRelativePath($image_url, $pattern){
  return !preg_match($pattern, $image_url);
}


function sirv_render_sirv_class($css_item, $sirv_url){
  $end_brace = stripos($css_item['class'], '@media') !== false ? '}}' : '}';
  $important = ' !important;';

  return trim($css_item['class']) . "{" . str_replace($css_item['img_url'], $sirv_url, $css_item['style']) . $important . $end_brace;
}




function sirv_flatten_css_files_array($arr){
  $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($arr));
  return iterator_to_array($it, false);
}


add_action('admin_init', 'sirv_monitoring_nopriv_ajax');
function sirv_monitoring_nopriv_ajax(){
  //if (is_admin() || $isAdmin) return;

  if (defined('DOING_AJAX') && DOING_AJAX) {
    $action = '';
    $post_action = isset($_POST['action']) ? $_POST['action'] : '';
    if (!empty($post_action)) {
      $action = $post_action;
    } else {
      $action = isset($_GET['action']) ? $_GET['action'] : '';
    }

    if (!empty($action) && sirv_is_frontend_ajax($action)) {
      //global $isAdmin;
      global $isLoggedInAccount;
      global $isAjax;
      $isAjax = true;

      if (get_option('SIRV_ENABLE_CDN') === '1' && $isLoggedInAccount) {
        add_filter('wp_get_attachment_image_src', 'sirv_wp_get_attachment_image_src', 10000, 4);
        //add_filter('image_downsize', "sirv_image_downsize", 10000, 3);
        add_filter('wp_get_attachment_url', 'sirv_wp_get_attachment_url', 10000, 2);
        add_filter('wp_calculate_image_srcset', 'sirv_add_custom_image_srcset', 10, 5);
        //add_filter('vc_wpb_getimagesize', 'sirv_vc_wpb_filter', 10000, 3);
        //add_filter('envira_gallery_image_src', 'sirv_envira_crop', 10000, 4);
        //add_filter('wp_prepare_attachment_for_js', 'sirv_wp_prepare_attachment_for_js', 10000, 3);

        if (get_option('SIRV_USE_SIRV_RESPONSIVE') === '1') {
          add_filter('wp_get_attachment_image_attributes', 'sirv_do_responsive_images', 99, 3);
        }
      }
    }
  }
}


function sirv_is_frontend_ajax($action){
  global $wp_filter;

  return isset($wp_filter["wp_ajax_nopriv_{$action}"]);
}


add_action('wp_ajax_sirv_update_smv_cache_data', 'sirv_update_smv_cache_data', 10);
add_action('wp_ajax_nopriv_sirv_update_smv_cache_data', 'sirv_update_smv_cache_data', 10);

function sirv_update_smv_cache_data(){
  if (!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)) {
    echo json_encode(array('error' => 'empty POST or is not ajax action'));
    wp_die();
  }

  $ids = $_POST['ids'];
  $mainID = $_POST['mainID'];


  if(!empty($ids)){
    $woo = new Woo($mainID);
    $woo->update_smv_cache_data($ids);
  }

  echo json_encode(array('status' => 'updated'));
  wp_die();
}


add_action('delete_attachment', 'sirv_delete_image_from_sirv', 10 , 2);
function sirv_delete_image_from_sirv($post_id, $post){

  if(get_option('SIRV_DELETE_FILE_ON_SIRV') == '2') return;

  if(isset($post_id) && isset($post) && sirv_is_allowed_ext($post->guid)){
    global $wpdb;
    $images_t = $wpdb->prefix . 'sirv_images';
    $sirv_img_data_from_cache = $wpdb->get_row($wpdb->prepare("SELECT * FROM $images_t WHERE attachment_id = %d", $post_id), ARRAY_A);

    if(!$sirv_img_data_from_cache) return;

    $result = $wpdb->delete($images_t, ['id' => $sirv_img_data_from_cache['id']]);
    if($result){
      $sirv_folder = get_option('SIRV_FOLDER');
      $sirvAPIClient = sirv_getAPIClient();
      $r_result = $sirvAPIClient->deleteFile($sirv_folder . $sirv_img_data_from_cache['sirv_path']);
    }
  }
}


add_action('add_attachment', 'sirv_sync_on_image_upload', 10);
function sirv_sync_on_image_upload($post_id){

  if(isset($post_id) && !empty($post_id) ){
    $isOn = get_option('SIRV_SYNC_ON_UPLOAD') == 'on';
    if($isOn){
      $res = sirv_get_cdn_image($post_id);
      sirv_processFetchQueue();
    }
  }
}


//ajax request to delete or regenerate thumbs
add_action('wp_ajax_sirv_thumbs_process', 'sirv_thumbs_process', 10);
function sirv_thumbs_process(){

  if (!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  $isPause = $_POST['pause'] == 'true';
  $thumbs_data = json_decode(get_option('SIRV_THUMBS_DATA'), true);

  if($isPause && ($thumbs_data['ids_count'] !== $thumbs_data['processed_ids'])){
    if($thumbs_data['status'] !== 'pause'){
      $thumbs_data['status'] = 'pause';
      sirv_update_db_option('SIRV_THUMBS_DATA', $thumbs_data, 'no');
    }

    echo json_encode($thumbs_data);
    wp_die();
  }

  $type = $thumbs_data['status'] == 'processing' ? $thumbs_data['type'] : $_POST['type'];
  $thumbs_data['type'] = $type;

  $limit = ($type == 'delete') ? 15 : 2;

  $thumbs_data['status'] = 'processing';

  global $wpdb;
  $images_t = $wpdb->prefix . 'sirv_images';

  $results = $wpdb->get_results($wpdb->prepare("SELECT id, attachment_id FROM $images_t WHERE id > %d AND status = 'SYNCED' ORDER BY id ASC LIMIT $limit", $thumbs_data['last_id']), ARRAY_A);

  $synced_cache_count = sirv_get_synced_count();

  $thumbs_data['ids_count'] = $synced_cache_count;

  if(!empty($results)){
    foreach ($results as $item) {
      $thumbs_data['processed_ids'] = (int) $thumbs_data['processed_ids'] + 1;
      $thumbs_data['last_id'] = (int) $item['id'];
      if($type == 'delete'){
        list($filescount, $filessize) = resizeHelper::deleteThumbs((int) $item['attachment_id']);
        $thumbs_data['files_count'] += $filescount;
        $thumbs_data['files_size'] += $filessize;
      }

      if($type == 'regenerate'){
        $thumbs_data['files_count'] += resizeHelper::regenerateThumbs((int) $item['attachment_id']);
      }
    }

    $thumbs_data['percent_finished'] = (int) (((int) $thumbs_data['processed_ids'] / $synced_cache_count ) * 100);

    sirv_update_db_option('SIRV_THUMBS_DATA', $thumbs_data, 'no');

  }else{
    $default_thumbs_data = array(
      'status' => 'finished',
      'type' => '',
      'last_id' => 0,
      'files_count' => 0,
      'files_size' => 0,
      'ids_count' => $synced_cache_count,
      'processed_ids' => 0,
      'percent_finished' => 0,
    );
    sirv_update_db_option('SIRV_THUMBS_DATA', $default_thumbs_data, 'no');

    $thumbs_data['status'] = 'finished';
  }

  echo json_encode($thumbs_data);
  wp_die();
}

add_action('wp_ajax_sirv_cancel_thumbs_process', 'sirv_cancel_thumbs_process', 10);
function sirv_cancel_thumbs_process(){
  if (!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  $response = array('error' => '', 'status' => '', 'type' => '');

  $thumbs_data = json_decode(get_option('SIRV_THUMBS_DATA'), true);
  $type = $thumbs_data['type'];

  $thumbs_data = array(
    'status' => 'start',
    'type' => '',
    'last_id' => 0,
    'files_count' => 0,
    'files_size' => 0,
    'ids_count' => sirv_get_synced_count(),
    'processed_ids' => 0,
    'percent_finished' => 0,
  );

  sirv_update_db_option('SIRV_THUMBS_DATA', $thumbs_data, 'no');

  $response['status'] = 'canceled';
  $response['type'] = $type;

  echo json_encode($response);
  wp_die();
}


add_action('wp_ajax_sirv_save_prevented_sizes', 'sirv_save_prevented_sizes', 10);
function sirv_save_prevented_sizes(){
  if (!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)) {
    return;
  }

  $prevented_sizes = stripslashes($_POST['sizes']);

  sirv_update_db_option('SIRV_PREVENTED_SIZES', $prevented_sizes, 'no', false);

  echo json_encode( array('status' => 'saved') );
  wp_die();
}


add_action('wp_ajax_sirv_get_js_module_size', 'sirv_get_js_module_size', 10);
function sirv_get_js_module_size(){
  if (!(is_array($_POST) && defined('DOING_AJAX') && DOING_AJAX)) {
    echo json_encode(array('error' => 'Action denied'));
    wp_die();
  }

  if (!sirv_is_allow_ajax_connect('ajax_validation_nonce', 'manage_options')) {
    echo json_encode(array('error' => 'Access to the requested resource is forbidden'));
    wp_die();
  }

  $modules = $_POST['modules'];
  $url = getValue::getJsFileUrl();
  $sizes = sirv_get_js_compressed_size("$url?modules=$modules");

  if(is_null($sizes['compressed']) && is_null($sizes['error'])){
    $sizes['error'] = 'Cannot calculate js bundle size';
  }
  echo json_encode($sizes);
  wp_die();

}


function sirv_get_js_compressed_size($url){
  $sizes = array("compressed" => null, "uncompressed" => null, 'error' => null);

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_ENCODING, '');
  curl_setopt($ch, CURLINFO_HEADER_OUT, true);

  try {
    curl_exec($ch);

    $data = curl_exec($ch);
    if (extension_loaded('mbstring')) {
      $sizes['uncompressed'] = mb_strlen($data, 'utf-8');
      $sizes['uncompressed_s'] = Utils::getFormatedFileSize($sizes['uncompressed']);
    } else {
      $sizes['uncompressed'] = sirv_get_js_uncomressed_size($url, true);
      $sizes['uncompressed_s'] = Utils::getFormatedFileSize($sizes['uncompressed']);
    }

    $sizes['compressed'] = (int) curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
    $sizes['compressed_s'] = Utils::getFormatedFileSize($sizes['compressed']);

  } catch (Exception $e) {
    $sizes['error'] = $e;
  }finally{
    curl_close($ch);
    return $sizes;
  }


}

function sirv_get_js_uncomressed_size($url){
  $headers_data = get_headers($url, true);

  return $headers_data['Content-Length'];
}

?>
