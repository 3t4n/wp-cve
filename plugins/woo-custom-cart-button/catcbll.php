<?php

/**
 * Plugin Name:	    Custom Add to Cart Button Label and Link
 * Plugin URI:	    https://plugins.hirewebxperts.com/custom-add-to-cart/
 * Description:	    WooCommerce Custom ATC button plugin provides that has ability to change woocommerce each product's Add To Cart button with user specific name.
 * Version: 		    1.6.1
 * Requires at least: 6.4.2 or higher
 * Requires PHP:      7.4 or higher
 * Author: 		    Coder426
 * Author URI:	    https://www.hirewebxperts.com
 * Donate link: 	    https://hirewebxperts.com/donate/
 * Text Domain:      catcbll
 * Domain Path:	    /i18n/languages
 * License:          GPLv3
 * License URI:      https://www.gnu.org/licenses/gpl-2.0.txt
 * License:          GPL2
 * 
 */
if (!defined('ABSPATH')) {
  exit;
}



// Define Minimum WooCommerce Version
if (!defined('CATCBLL_MINIMUM_WOOCOMMERCE_VERSION')) {
  define('CATCBLL_MINIMUM_WOOCOMMERCE_VERSION', 2.7);
}


$version = '1.6.1';
$name = 'catcbll';

// Define plugin url path
define('WCATCBLL_CART_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WCATCBLL_CART_PLUGIN_DIR', dirname(__FILE__));
define('WCATCBLL_CART_JS', WCATCBLL_CART_PLUGIN_URL . 'assets/js/');
define('WCATCBLL_CART_CSS', WCATCBLL_CART_PLUGIN_URL . 'assets/css/');
define('WCATCBLL_CART_IMG', WCATCBLL_CART_PLUGIN_URL . 'assets/img/');
define('WCATCBLL_CART_INC', WCATCBLL_CART_PLUGIN_DIR . '/include/');
define('WCATCBLL_CART_PUBLIC', WCATCBLL_CART_PLUGIN_DIR . '/public/');
define('version', $version);
define('WCATCBLL_NAME', $name);
define('DISABLE_NAG_NOTICES', true);

require_once(dirname(__FILE__) . '/helpers/helpers.php');
//deactivate plugin after register pro version
register_activation_hook(__FILE__, 'catcbll_activation');
function catcbll_activation()
{
  // Check if WooCommerce installed or Compatible
  $notice = catcbll_check_woocommerce_plugin();
  if ($notice) {
    deactivate_plugins(basename(__FILE__));
    wp_die(catcbll_plugin_die_message($notice));
  }
  if (is_plugin_active('custom-add-to-cart-pro/wcatcbnl.php')) {
    deactivate_plugins('custom-add-to-cart-pro/wcatcbnl.php');
  }
}

add_action( 'before_woocommerce_init', 'catcbll_hpos_compatibility' );
function catcbll_hpos_compatibility() {
	if( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 
			'custom_order_tables', 
			__FILE__, 
			true // true (compatible, default) or false (not compatible)
		);
	}
}

//Add languages files
add_action('init', 'catcbll_language_translate');
function catcbll_language_translate()
{
  load_plugin_textdomain('catcbll', false, plugin_basename(dirname(__FILE__)) . '/i18n/languages/');
}

//Include file in admin panel
include(WCATCBLL_CART_INC . 'wcatcbll_inscrpt.php');

// get and update previous version value in the database
add_action('admin_init', 'catcbll_db_get_btn_val');
if (!function_exists('catcbll_db_get_btn_val')) {
  function catcbll_db_get_btn_val($post_id)
  {
    $args = array(
      'post_type'     => 'product',
      'post_per_page' => -1,
      'post_status'   => 'publish',
      'meta_key'      => '_wcatcbll_wcatc_atc_btn_text',
    );
    $dbResult = new WP_Query($args);
    $posts = $dbResult->posts;
    foreach ($posts as $post) {
      $btn_label = get_post_meta($post->ID, '_wcatcbll_wcatc_atc_btn_text', true);
      $btn_url = get_post_meta($post->ID, '_wcatcbll_wcatc_atc_btn_act', true);
      $btn_lbl_new = get_post_meta($post->ID, '_catcbll_btn_label', true);
      if (isset($btn_lbl_new) && empty($btn_lbl_new)) {
        if (is_serialized($btn_label)) {
          $btn_name =  unserialize($btn_label);
          update_post_meta($post->ID, '_catcbll_btn_label', $btn_name);
        }
        if (is_serialized($btn_url)) {
          $btn_act =  unserialize($btn_url);
          update_post_meta($post->ID, '_catcbll_btn_link', $btn_act);
        }
      }
    }
  }
}

// Add custom button option
add_action('admin_init', 'catcbll_init_options');
function catcbll_init_options()
{
  // pro option key
  add_option('_woo_catcbll_version', version);
  $get_version = get_option('_woo_catcbll_version');
  if ($get_version) {
    update_option('_woo_catcbll_version', version);
  }
  $options_pro = get_option('_woo_catcbll_all_settings');

  $plugin_options_keys = array('wcatcbll_both_btn', 'wcatcbll_add2_cart', 'wcatcbll_custom', 'wcatcbll_custom_btn_position', 'wcatcbll_cart_global', 'wcatcbll_cart_shop', 'wcatcbll_cart_single_product', 'wcatcbll_btn_bg', 'wcatcbll_btn_fclr', 'wcatcbll_btn_size', 'wcatcbll_btn_shape', 'wcatcbll_btn_icon', 'wcatcbll_btn_border', 'wcatcbll_border_size', 'wcatcbll_btn_2Dhvr', 'wcatcbll_btn_bghvr', 'wcatcbll_btn_hvrclr', 'wcatcbll_btn_icon_psn', 'wcatcbll_btn_padding', 'wcatcbll_btn_open_new_tab');

  $option_key_vals = array();
  foreach ($plugin_options_keys as $key) {
    $option_key_vals[$key] = get_option($key);
    if (empty($option_key_vals[$key])) {
      switch ($key) {
        case 'wcatcbll_btn_size':
          $option_key_vals[$key] = 14;
          break;
        case 'wcatcbll_border_size':
          $option_key_vals[$key] = 0;
          break;
        case 'wcatcbll_btn_icon_psn':
          $option_key_vals[$key] = 'right';
          break;
        case 'wcatcbll_custom':
          $option_key_vals[$key] = 'custom';
          break;
        case 'wcatcbll_custom_btn_position':
          $option_key_vals[$key] = 'down';
          break;
        default:
          $option_key_vals[$key] = '';
      }
    }
  }
  extract($option_key_vals);

  if (isset($wcatcbll_btn_padding['top'])) {
    $top_padding = $wcatcbll_btn_padding['top'];
  } else {
    $top_padding = '';
  }
  if (isset($wcatcbll_btn_padding['left'])) {
    $left_padding = $wcatcbll_btn_padding['left'];
  } else {
    $left_padding = '';
  }
  if (strpos($wcatcbll_btn_shape, "px")) {
    $btn_radius = str_replace("px", "", $wcatcbll_btn_shape);
  } else {
    $btn_radius = 0;
  }
  // check key exist or not
  if (!$options_pro) {
    $add_setting = array(
      "catcbll_both_btn"              => $wcatcbll_both_btn,
      "catcbll_add2_cart"             => $wcatcbll_add2_cart,
      "catcbll_custom"                => $wcatcbll_custom,
      "catcbll_custom_btn_position"   => $wcatcbll_custom_btn_position,
      "catcbll_cart_global"           => $wcatcbll_cart_global,
      "catcbll_cart_shop"             => $wcatcbll_cart_shop,
      "catcbll_cart_single_product"   => $wcatcbll_cart_single_product,
      "catcbll_btn_fsize"             => $wcatcbll_btn_size,
      "catcbll_border_size"           => $wcatcbll_border_size,
      "catcbll_btn_radius"            => $btn_radius,
      "catcbll_btn_bg"                => $wcatcbll_btn_bg,
      "catcbll_btn_fclr"              => $wcatcbll_btn_fclr,
      "catcbll_btn_border_clr"        => $wcatcbll_btn_border,
      "catcbll_btn_hvrclr"            => $wcatcbll_btn_hvrclr,
      "catcbll_padding_top_bottom"    => $top_padding,
      "catcbll_padding_left_right"    => $left_padding,
      "catcbll_margin_top"            => '20',
      "catcbll_margin_right"          => '0',
      "catcbll_margin_bottom"         => '20',
      "catcbll_margin_left"           => '0',
      "catcbll_btn_icon_cls"          => $wcatcbll_btn_icon,
      "catcbll_btn_icon_psn"          => $wcatcbll_btn_icon_psn,
      "catcbll_btn_2dhvr"             => $wcatcbll_btn_2Dhvr,
      "catcbll_btn_bghvr"             => $wcatcbll_btn_bghvr,
      "catcbll_btn_open_new_tab"      => $wcatcbll_btn_open_new_tab,
      "catcbll_hide_2d_trans"         => $wcatcbll_btn_2Dhvr,
      "catcbll_hide_btn_bghvr"        => $wcatcbll_btn_bghvr,
      "catcbll_custom_btn_alignment"  => 'center',
    );
    add_option('_woo_catcbll_all_settings', $add_setting);
  }
}

//Rating star plugin row meta
add_filter('plugin_row_meta', 'catcbll_add_plugin_meta_links', 10, 2);
if (!function_exists('catcbll_add_plugin_meta_links')) {
  function catcbll_add_plugin_meta_links($meta_fields, $file)
  {
    if (plugin_basename(__FILE__) == $file) {
      $plugin_url = "https://wordpress.org/support/plugin/woo-custom-cart-button/reviews/?rate=1#new-post";
      $meta_fields[] = "Rate us:<a href='" . esc_url($plugin_url) . "' target='_blank' title='" . esc_html__('Rate', 'catcbll') . "'><i class='rating-stars'>"
        . "<span class='rating-stars'><a href='//wordpress.org/support/plugin/woo-custom-cart-button/reviews/?rate=1#new-post' target='_blank' data-rating='1' title='" . __('Poor', 'catcbll') . "'><span class='dashicons dashicons-star-filled' style='color:#ffb900 !important;'></span></a><a href='//wordpress.org/support/plugin/woo-custom-cart-button/reviews/?rate=2#new-post' target='_blank' data-rating='2' title='" . __('Works', 'catcbll') . "'><span class='dashicons dashicons-star-filled' style='color:#ffb900 !important;'></span></a><a href='//wordpress.org/support/plugin/woo-custom-cart-button/reviews/?rate=3#new-post' target='_blank' data-rating='3' title='" . __('Good', 'catcbll') . "'><span class='dashicons dashicons-star-filled' style='color:#ffb900 !important;'></span></a><a href='//wordpress.org/support/plugin/woo-custom-cart-button/reviews/?rate=4#new-post' target='_blank' data-rating='4' title='" . __('Great', 'catcbll') . "'><span class='dashicons dashicons-star-filled' style='color:#ffb900 !important;'></span></a><a href='//wordpress.org/support/plugin/woo-custom-cart-button/reviews/?rate=5#new-post' target='_blank' data-rating='5' title='" . __('Fantastic!', 'catcbll') . "'><span class='dashicons dashicons-star-filled' style='color:#ffb900 !important;'></span></a><span>" . "</i></a>";
    }
    return $meta_fields;
  }
}

//Live demo plugin row meta
add_filter('plugin_row_meta', 'catcbll_live_demo_meta_links', 10, 2);
if (!function_exists('catcbll_live_demo_meta_links')) {
  function catcbll_live_demo_meta_links($meta_fields, $file)
  {
    if (plugin_basename(__FILE__) == $file) {
      $plugin_url = "https://plugins.hirewebxperts.com/shop";
      $plugin_pro = "https://plugins.hirewebxperts.com/shop";
      $meta_fields[] = "<a href='" . esc_url($plugin_url) . "' target='_blank' title='" . esc_html__(__('Live Demo', 'catcbll')) . "'><i class='fa fa-desktop' aria-hidden='true'>" . "&nbsp;<span>" . esc_html__(__('Live Demo', 'catcbll')) . "</span>" . "</i></a>";
      $meta_fields[] = "<a href='" . esc_url($plugin_pro) . "' target='_blank' title='" . esc_html__(__('Live Demo', 'catcbll')) . "'><i class='fa fa-desktop' aria-hidden='true'>" . "&nbsp;<span>" . esc_html__(__('Pro Demo', 'catcbll')) . "</span>" . "</i></a>";
    }
    return $meta_fields;
  }
}

//Check woocommerce is installed or not
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) || class_exists('WooCommerce')  ||  is_multisite()) {
  include(WCATCBLL_CART_INC . 'functions.php');
  //Adding Shortcode
  include(WCATCBLL_CART_INC . 'wcatcbll_shortcode.php');

  // Adding widget
  include(WCATCBLL_CART_INC . 'wcatcbll_widget.php');

  //Setting link to pluign
  add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'catcbll_add_plugin_page_settings_link');
  function catcbll_add_plugin_page_settings_link($links)
  {
    $links[] = '<a href="' . admin_url('options-general.php?page=hwx-wccb') . '">' . __('Settings', 'catcbll') . '</a>';
    return $links;
  }
  include(WCATCBLL_CART_INC . 'wcatcbll_settings.php');

  //Adding meta box to product page
  include(WCATCBLL_CART_INC . 'wcatcbll_metabox.php');

  //Cart Setting values
  $catcbll_settings = get_option('_woo_catcbll_all_settings');
  if ($catcbll_settings) {
    extract($catcbll_settings);
    if (isset($catcbll_cart_global)) {
      $global = $catcbll_cart_global;
    } else {
      $global = '';
    }
    if (isset($catcbll_cart_shop)) {
      $shop = $catcbll_cart_shop;
    } else {
      $shop = '';
    }
    if (isset($catcbll_cart_single_product)) {
      $single  = $catcbll_cart_single_product;
    } else {
      $single = '';
    }
  } else {
    $global = '';
    $shop = '';
    $single = '';
  }
  // if setting is global or shop 
  if (($global == 'global') || ($shop == 'shop')) {
    //Remove default ATC button from archive page.
    if (!function_exists('catcbll_remove_atc_arch')) {
      function catcbll_remove_atc_arch()
      {
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
      }
    }
    add_action('init', 'catcbll_remove_atc_arch');

    //Custom ATC button on archive page.
    include(WCATCBLL_CART_PUBLIC . 'wcatcbll_archive.php');
  } // if setting is global or shop end

  // if setting is global or single product
  if (($global == 'global') || ($single == 'single-product')) {
    //Remove default ATC button from single product page.
    if (!function_exists('catcbll_remove_atc_single')) {
      function catcbll_remove_atc_single()
      {
        global $product;
        if ($product->is_type('variable')) {
          remove_action('woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20);
        } else {
          remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
        }
      }
    }
    add_action('woocommerce_before_single_product_summary', 'catcbll_remove_atc_single');

    //Custom ATC button on single product page.
    include(WCATCBLL_CART_PUBLIC . 'wcatcbll_single_product.php');
  } // if setting is global or single product end

}

if (is_admin()) {
  add_action('wp_default_scripts', 'catcbll_wp_default_custom_scripts');
  function catcbll_wp_default_custom_scripts($scripts)
  {
    $scripts->add('wp-color-picker', "/wp-admin/js/color-picker.js", array('iris'), false, 1);
    did_action('init') && $scripts->localize(
      'wp-color-picker',
      'wpColorPickerL10n',
      array(
        'clear'            => __('Clear'),
        'clearAriaLabel'   => __('Clear color'),
        'defaultString'    => __('Default'),
        'defaultAriaLabel' => __('Select default color'),
        'pick'             => __('Select Color'),
        'defaultLabel'     => __('Color value'),
      )
    );
  }
}
?>