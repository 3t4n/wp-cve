<?php
/*
Plugin Name: Quantity Buttons for WooCommerce
Description: Add Quantity buttons to WooCommerce Cart and Products.
Author: RLDD
Version: 1.1.8
Text Domain: woo-quantity-buttons
Author URI: https://richardlerma.com/plugins/
Copyright: (c) 2019-2023 - rldd.net - All Rights Reserved
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
WC requires at least: 5.0
WC tested up to: 8.6
*/

global $wqb_version; $wqb_version='1.1.8';
if(!defined('ABSPATH')) exit;

function wqb_error() {file_put_contents(dirname(__file__).'/install_log.txt', ob_get_contents());}
if(defined('WP_DEBUG') && true===WP_DEBUG) add_action('activated_plugin','wqb_error');

function wqb_adminMenu() {
  if(current_user_can('manage_options')) {
    add_options_page('Quantity Buttons','Quantity Buttons','manage_options','woo-quantity-buttons','wqb_admin');
  }
}
//add_action('admin_menu','wqb_adminMenu');

function wqb_admin() { // Future admin settings
  global $wpdb;
  global $wqb_version;
  wp_enqueue_style('wqb_style',plugins_url('assets/wqb_min.css?v='.$wqb_version,__FILE__));
}
//add_shortcode('wp_admin','wqb_admin');

function wqb_activate($upgrade) {
  global $wpdb;
  global $wqb_version;
  require_once(ABSPATH.basename(get_admin_url()).'/includes/upgrade.php');
  update_option('wqb_db_version',$wqb_version,'no');
  if(function_exists('wqb_pro_ping'))wqb_pro_ping(2);
}
register_activation_hook(__FILE__,'wqb_activate');
function wqb_shh() { ?><style type='text/css'>div.error{display:none!important}</style><?php }
if(wqb_is_path(basename(get_admin_url()).'/plugins.php') && wqb_is_path('plugin=woo-quantity-buttons')) add_action('admin_head','wqb_shh'); 

function wqb_add_action_links($links) {
  $settings_url=get_admin_url(null,'admin.php?page=woo-quantity-buttons');
  $support_url='https://richardlerma.com/plugins/';
  $links[]='<a href="'.$support_url.'">Support</a>';
  //array_push($links,'<a href="'.$settings_url.'">Settings</a>');
  return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__),'wqb_add_action_links');

function wqb_uninstall() {delete_option('wqb_db_version');}
register_uninstall_hook(__FILE__,'wqb_uninstall');

add_action('before_woocommerce_init',function() {
	if(class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class )) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables',__FILE__,true);
	}
});

function wqb_is_path($pages) {
  $page_array=explode(',',$pages);
  $current_page=strtolower($_SERVER['REQUEST_URI']);
  foreach($page_array as $page) {
    if(strpos($current_page,strtolower($page))!==false) return true;
  }
  return false;
}

add_action('before_woocommerce_init',function() {
	if(class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) { \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__,true);}
});

function wqb_product() { echo "
  <div id='wqb_btn'>
    <button type='button' title='Decrease quantity' class='minus qty' onclick='wqb_action(0,-1)'>-</button>
    <button type='button' title='Increase quantity' class='plus qty' onclick='wqb_action(0,1)'>+</button>
  </div>
  <script type='text/javascript'>
    function wqb_init_prd() {
      var qty=document.getElementsByName('quantity'); if(!qty) return;
      if(qty[0].type=='hidden') document.getElementById('wqb_btn').style.display='none';
    }
    wqb_init_prd();
  </script>";
}
add_action('woocommerce_after_add_to_cart_quantity','wqb_product');


function wqb_cart() { echo "
  <script type='text/javascript'>
    function wqb_init_cart() {
      if(document.getElementsByClassName('minus').length>0) return;
      var qty=document.getElementsByClassName('quantity');
      if(!qty) return;
      for(i=0; i<qty.length; i++) {
        qty[i].innerHTML+=\"<div><button type='button' title='Decrease quantity' class='minus qty' onclick='wqb_action(\"+i+\",-1)'>-</button><button type='button' title='Increase quantity' class='plus qty' onclick='wqb_action(\"+i+\",1)'>+</button></div>\";
      }
    }
    wqb_init_cart();
    setInterval(function(){wqb_init_cart();},3000);
  </script>";
}
add_action('woocommerce_after_cart','wqb_cart');
//add_action('woocommerce_review_order_after_cart_contents','wqb_cart');


function wqb_assets() { echo "
  <style>
    div.quantity div{display:inline;white-space:nowrap}
    .quantity button.qty{padding:.3em 1em;user-select:none;cursor:pointer!important}
    button.qty{display:inline-block!important;font-weight:bold;outline:none;-webkit-transition:all .3s;transition:all .3s}
    button.qty:hover{box-shadow:-1px 0px #ccc;-webkit-filter:brightness(95%);filter:brightness(95%);transform:scale(1.05)}
    button.qty:active{transform:scale(1.15)}
    button.qty.minus{border-right:1px solid #ccc;-webkit-filter:brightness(95%);filter:brightness(95%)}
    button.button.update_cart{background:#080!important;color:#fff!important}
    button[name=add-to-cart],.single_add_to_cart_button{margin-top:.8em}
    @media (min-width:769px) {.quantity button.qty{max-width:1.85em!important}}
  </style>
  <script type='text/javascript'>
    function wqb_action(iteration,q) {
      var qty=[].slice.call(document.querySelectorAll('div.quantity'));
      if(!qty) return;
      for(i=0; i<qty.length; i++) if(i==iteration) {
        v=qty[i].childNodes[3];
        if(!v) return;
        if(v.value<=1 && q<1) return;
        v.value=parseInt(v.value)+parseInt(q);
      }
      if(document.getElementsByName('update_cart').length>0) {
        update_cart=document.getElementsByName('update_cart')[0];
        update_cart.disabled=false;
        update_cart.classList.add('update_cart');
      } else {
        var ev=new Event('change',{bubbles:true});
        v.dispatchEvent(ev);
      }
    }
  </script>";
}
add_action('woocommerce_after_cart_contents','wqb_assets');
add_action('woocommerce_after_add_to_cart_quantity','wqb_assets');
//add_action('woocommerce_review_order_after_cart_contents','wqb_assets');
