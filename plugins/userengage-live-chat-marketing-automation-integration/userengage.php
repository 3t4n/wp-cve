<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/*
  Plugin Name: User.com Plugin
  Description: User.com Plugin for Wordpress.
  Version: 1.3.5.0
  Author: UserEngage
  Author URI: https://user.com/en-us/
  License: GPLv2 or later
  Text Domain: userengage
 */

/*  Copyright 2015-2018 UserEngage

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */

define( 'UE_PLUGIN_URL', plugins_url( '/' , __FILE__ ) );

function usercom_admin_style($hook) {
	if ( isset( $_GET['page'] ) == 'userengage-live-chat-marketing-automation-integration' ) {
		wp_enqueue_style( 'usercom_admin_css', plugin_dir_url( __FILE__ ) . 'assets/css/style.css');
	}
}
add_action( 'admin_enqueue_scripts', 'usercom_admin_style' );

function usercom_widget( $meta ) {
	if ( isset( $_GET["key"] ) ) {
		$order_id         = wc_get_order_id_by_order_key( $_GET["key"] );
		$order            = new WC_Order( $order_id );
		$order_meta       = get_post_meta( $order_id );
		$billing_address  = $order->get_billing_address_1();
		$shipping_address = $order->get_shipping_address_1();
		$billing_address  = explode( '<br/>', $billing_address );
		$shipping_address = explode( '<br/>', $shipping_address );

		$attribs = '';
		$data    = array();
		$x       = 0;
		foreach ( $shipping_address as $addr ) {
			$name          = 'billing_' . $x;
			$data[ $name ] = $addr;
			$x ++;
		}
		$x = 0;
		foreach ( $billing_address as $addr ) {
			$name          = 'shipping_' . $x;
			$data[ $name ] = $addr;
			$x ++;
		}
		foreach ( $data as $key => $dat ) {
			$attribs .= '"' . $key . '": "' . $dat . '",';
		}
		$attribs .= '"email": "' . $order_meta["_billing_email"]["0"] . '",';
	}

	$current_user = wp_get_current_user();
	$name         = null;
	if ( 0 == $current_user->ID ) {
		$name = "";
	} else {
		if ( strlen( $current_user->user_firstname ) > 0 && strlen( $current_user->user_lastname ) > 0 ) {
			$name = $current_user->user_firstname . ' ' . $current_user->user_lastname;
		} else if ( strlen( $current_user->user_firstname ) > 0 ) {
			$name = $current_user->user_firstname;
		} else if ( strlen( $current_user->user_lastname ) > 0 ) {
			$name = $current_user->user_lastname;
		}
	}
	if ( isset( $_GET["key"] ) && $order_id ) {
		$name   = $order_meta["_billing_first_name"]["0"] . ' ' . $order_meta["_billing_last_name"]["0"];
		$output = "<script type='text/javascript' data-cfasync='false'>
window.civchat = {
    apiKey: \"$meta\",
    name: \"$name\",
    email: \"$current_user->user_email\",
    " . $attribs . "
    phone: '" . $order_meta["_billing_phone"]["0"] . "'
};
</script>";
		echo $output;
	} else {
		$output = "<script type='text/javascript' data-cfasync='false'>window.civchat = {";
		$output .= "apiKey: \"$meta\",";
		if ( strlen( $name ) > 0 ) {
			$output .= "name: \"$name\",";
		}
		if ( strlen( $current_user->user_email ) > 0 ) {
			$output .= "email: \"$current_user->user_email\"";
		}
		$output .= "};</script>";

		echo $output;
	}
}

add_action( 'wp_head', 'usercom_hook_js' );

function usercom_hook_js() {
	$output = '<script type="text/javascript">';
	$output .= 'var userID = "";var userName = "";var userEmail = "";';
	if ( isset( $_SESSION["user"] ) ) {
		$obj_user = get_user_by( 'id', $_SESSION["user"] );

		$output .= 'var userID = ' . $obj_user->data->ID . ';';
		$output .= 'var userName = "' . $obj_user->data->display_name . '";';
		$output .= 'var userEmail = "' . $obj_user->data->user_email . '";';
	}
	$output .= '</script>';

	echo $output;
	unset( $_SESSION["user"] );
}

add_action( 'woocommerce_after_add_to_cart_button', 'usercom_custome_add_to_cart' );

function usercom_custome_add_to_cart( $product_id ) {
	global $woocommerce;
	global $product;
	$product_id = $product->id;
	$_product   = new WC_Product( $product_id );
	$attributes = $_product->get_attributes();
	$ptitle     = $_product->get_title();
	$pprice     = $_product->get_price();
	$pimage     = $_product->get_image( $size = 'shop_thumbnail' );
	$attribs    = '';
	$thumb_id   = get_post_thumbnail_id();
	$thumb_url  = wp_get_attachment_image_src( $thumb_id, 'medium', true );

	$attributes = $_product->get_attributes();
	$attribs    = "'name': '" . $ptitle . "','productid': '" . $product_id . "','sku': '" . $_product->get_sku() . "','price': '" . $pprice . "',";
	foreach ( $attributes as $attrib ) {
		$attribs .= "'" . $attrib["name"] . "': '" . $attrib["value"] . "',";
	}

	$output = '<script type="text/javascript"> ';
	$output .= 'var timecheck = setInterval(function() { if (typeof userengage == "function") { document.querySelector(".single_add_to_cart_button").onclick = function() {';
	$output .= "userengage('event.AddToCart', {'pid': '" . $product_id . "','title': '" . $ptitle . "','image_url': '" . $thumb_url[0] . "'," . $attribs . "'price': '" . $pprice . "' });";
	$output .= ' clearInterval(timecheck);} }},500);';
	$output .= '</script>';
	echo $output;
}

add_action( 'user_register', 'usercom_registration_save', 10, 1 );

add_action( 'woocommerce_thankyou', 'usercom_send', 10, 1 );

function usercom_send( $order_id ) {
	$order      = new WC_Order( $order_id );
	$order_meta = get_post_meta( $order_id );
	echo '<pre>';

	$items = $order->get_items();
	foreach ( $items as $item ) {
		$product_name         = $item['name'];
		$product_id           = $item['product_id'];
		$_product             = new WC_Product( $product_id );
		$pprice               = $_product->get_price();
		$product_variation_id = $item['variation_id'];
		$attributes           = $_product->get_attributes();
		$attribs              = '"name": "' . $product_name . '","sku": "' . $_product->get_sku() . '","productid": "' . $product_id . '","variationid": "' . $product_variation_id . '","price": "' . $pprice . '",';
		foreach ( $attributes as $attrib ) {
			$attribs .= '"' . $attrib["name"] . '": "' . $attrib["value"] . '",';
		}
	}
	$output = '<script type="text/javascript"  data-cfasync="false">';
	$output .= 'jQuery(document).ready(function($) { ';
	$output .= 'var timecheck =  setInterval(function() { if (typeof userengage == "function") { ';
	$output .= "userengage('event.NewOrder', {'orderId': '" . $order->post->ID . "','paymentType': '" . $order->payment_method_title . "','orderTotal': '" . $order->get_total() . "'," . $attribs . "'email': '" . $order_meta["_billing_email"]["0"] . "'  });";
	$output .= ' clearInterval(timecheck);} },500);';
	$output .= '}); </script>';
	echo $output;

}

function usercom_registration_save( $user_id ) {
	if ( $user_id ) {
		$_SESSION["user"] = $user_id;
	}
}

add_action( 'wp_head', 'usercom_registration_save' );

function usercom_widget_js() {
	if ( get_option( 'UserEngageScript_domain' ) ) {
		wp_enqueue_script( 'script_widget', 'https://'. esc_html( get_option( 'UserEngageScript_domain' ) ) .'/widget.js#cfasync', array(), null );
		wp_enqueue_script( 'script_ue', plugin_dir_url( __FILE__ ) . 'assets/js/ue.js', array( 'jquery' ), 1.1, true );
	} else {
		wp_enqueue_script( 'script_widget', 'https://app.userengage.com/widget.js#cfasync', array(), null );
		wp_enqueue_script( 'script_ue', plugin_dir_url( __FILE__ ) . 'assets/js/ue.js', array( 'jquery' ), 1.1, true );
	}
 }

function usercom_data_fasync($url) {
    if ( strpos( $url, '#cfasync') === false )
        return $url;
    else if ( is_admin() )
        return str_replace( '#cfasync', '', $url );
    else
	return str_replace( '#cfasync', '', $url )."' data-cfasync='false";
}
add_filter( 'clean_url', 'usercom_data_fasync', 11, 1 );

add_action( 'wp_enqueue_scripts', 'usercom_widget_js' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
if ( ! class_exists( 'UserCom_Scripts' ) ) {

	class UserCom_Scripts {

		function __construct() {
			add_action( 'admin_init', array(
				&$this,
				'usercom_admin_init'
			) );
			add_action( 'admin_menu', array(
				&$this,
				'usercom_admin_menu'
			) );
			add_action( 'wp_head', array(
				&$this,
				'usercom_head'
			) );
		}

		function usercom_admin_init() {
			register_setting( 'UserEngageScript-apiKey', 'UserEngageScript__apiKey' );
			register_setting( 'UserEngageScript-apiKey', 'UserEngageScript_toggle_version' );
			register_setting( 'UserEngageScript-apiKey', 'UserEngageScript_domain' );
		}

		function usercom_admin_menu() {
			add_menu_page('User.com', 'User.com', 'manage_options', __FILE__, array( &$this, 'usercom_panel'), 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDIyLjEuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiCgkgdmlld0JveD0iMCAwIDE4MCAxODAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDE4MCAxODA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4KCS5zdDB7ZmlsbDojRkZGRkZGO30KPC9zdHlsZT4KPGcgaWQ9IkxheWVyXzEiPgoJPHBhdGggaWQ9ImxvZ28iIGNsYXNzPSJzdDAiIGQ9Ik05MCwwQzQwLjMsMCwwLDM5LjgsMCw4OXYyYzAsNDkuMiw0MC4zLDg5LDkwLDg5czkwLTM5LjgsOTAtODlWMEg5MHogTTk5LDEzLjhjNSwwLDksNCw5LDguOQoJCWMwLDQuOS00LDguOS05LDguOXMtOS00LTktOC45QzkwLDE3LjgsOTQsMTMuOCw5OSwxMy44eiBNMTMzLjQsMTMwLjNjLTEzLjcsMTUuOC0yOC4zLDIzLjgtNDMuNSwyMy44Yy0wLjYsMC0xLjMsMC0xLjksMAoJCWMtMjQuOC0xLjEtNDEuMS0yMy4zLTQxLjgtMjQuMmMtMi4xLTIuOS0xLjQtNi45LDEuNS05YzIuOS0yLjEsNy0xLjQsOS4xLDEuNWMwLjIsMC4zLDEzLjQsMTguMSwzMS45LDE4LjgKCQljMTEuNywwLjUsMjMuNC02LDM0LjktMTkuM2MyLjMtMi43LDYuNC0zLDkuMi0wLjdDMTM1LjUsMTIzLjUsMTM1LjgsMTI3LjYsMTMzLjQsMTMwLjN6IE0xMjgsMzEuNmMtNSwwLTktNC05LTguOQoJCWMwLTQuOSw0LTguOSw5LTguOXM5LDQsOSw4LjlDMTM3LDI3LjcsMTMzLDMxLjYsMTI4LDMxLjZ6IE0xNTcsODljLTUsMC05LTQtOS04LjljMC00LjksNC04LjksOS04LjlzOSw0LDksOC45CgkJQzE2Niw4NSwxNjIsODksMTU3LDg5eiBNMTU3LDYwLjNjLTUsMC05LTQtOS04LjljMC00LjksNC04LjksOS04LjlzOSw0LDksOC45QzE2Niw1Ni4zLDE2Miw2MC4zLDE1Nyw2MC4zeiBNMTU3LDMxLjYKCQljLTUsMC05LTQtOS04LjljMC00LjksNC04LjksOS04LjlzOSw0LDksOC45QzE2NiwyNy43LDE2MiwzMS42LDE1NywzMS42eiIvPgo8L2c+CjxnIGlkPSJMYXllcl8yIj4KPC9nPgo8L3N2Zz4K');
		}

		function usercom_head() {
			$meta = get_option( 'UserEngageScript__apiKey', '' );
			if ( $meta != '' ) {
				usercom_widget( $meta );
			}
		}

		function usercom_panel() {
			?>
			<?php settings_errors(); ?>
          <div class="wrap ue_wrap">
            <h2>User.com Plugin - Options</h2>
            <hr/>
            <div class="ue container UserEngageScript__wrap">
                <a href="//user.com">
                    <img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/static/logo.svg'; ?>" class="userengage-logo" alt="User.com logo" height="16">
                </a>
              <form name="dofollow" action="options.php" method="post">
				  <?php settings_fields( 'UserEngageScript-apiKey' ); ?>
                <div class="ue row">
                  <div class="ue col">
                      <fieldset class="form-group">
                          <label for="apiKey">API Key</label>
                          <input type="text" id="apiKey"
                                 name="UserEngageScript__apiKey"
                                 class="ue input"
                                 placeholder="xxxxxx"
                                 value="<?php echo esc_html( get_option( 'UserEngageScript__apiKey' ) ); ?>"
                                 maxlength="6"
                                 required>
                      </fieldset>

                      <fieldset class="form-group">
                          <label for="domain">Subdomain</label>
                          <small>This field accepts domains in format eg.: subdomain.user.com (without https:// and following slash)</small>
                          <input type="text" id="domain"
                                 name="UserEngageScript_domain"
                                 class="ue input"
                                 placeholder="your_app_subdomain.user.com"
                                 value="<?php echo esc_html( get_option( 'UserEngageScript_domain' ) ); ?>">
                      </fieldset>
											<p>Please enter your application key and subdomain. The API Key is a 6 letter and number
	                      string, you can find your API Key and domain click <a
	                        href="https://user.com/en/integrations/custom-script/"
	                        target="_blank">here</a>.</p>
                  </div>
                </div>
                <div class="ue row one">
                  <div class="ue col">
                    <input class="ue button info rounded block button-large"
                           type="submit" name="Submit" value="Save"/>
                  </div>
                </div>
              </form>
            </div>
          </div>
			<?php
		}

	}

	$userengage_scripts = new UserCom_Scripts();
}
