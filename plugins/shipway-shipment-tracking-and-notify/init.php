<?php
/*
Plugin Name: WooCommerce Shipway Courier Tracking & notify
Plugin URI: http://shipway.in/plugins/shipway-order-tracking-notify/
Description: Shipway courier tracking - Track your shipment status (multi-courier) at one place.
Version: 3.0
Author: Onjection
Author URI: http://shipway.in/
License: GPL
@author Onjection
@package WooCommerce Shipway Tracking
@version 3.0
*/

/*  Copyright 2015  Onjection Solutions  (email : contact@Onjection.com)

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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

function ship_wst_install_woocommerce_admin_notice() {
	?>
	<div class="error">
		<p><?php _e( 'WooCommerce_Shipping_Tracking is enabled but not effective. It requires Woocommerce in order to work.', 'yit' ); ?></p>
	</div>
<?php
}

function ship_wst_install_free_admin_notice() {
	?>
	
<?php
}

if ( ! function_exists( 'ship_plugin_registration_hook' ) ) {
	//do nothing for now
}
// register_activation_hook( __FILE__, 'ship_plugin_registration_hook' );

//region    ****    Define constants
if ( ! defined( 'ship_wst_FREE_INIT' ) ) {
	define( 'ship_wst_FREE_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'ship_wst_VERSION' ) ) {
	define( 'ship_wst_VERSION', '1.0.3' );
}

if ( ! defined( 'ship_wst_FILE' ) ) {
	define( 'ship_wst_FILE', __FILE__ );
}

if ( ! defined( 'ship_wst_DIR' ) ) {
	define( 'ship_wst_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'ship_wst_URL' ) ) {
	define( 'ship_wst_URL', plugins_url( '/', __FILE__ ) );
}

if ( ! defined( 'ship_wst_ASSETS_URL' ) ) {
	define( 'ship_wst_ASSETS_URL', ship_wst_URL . 'assets' );
}

if ( ! defined( 'ship_wst_TEMPLATE_PATH' ) ) {
	define( 'ship_wst_TEMPLATE_PATH', ship_wst_DIR . 'templates' );
}

if ( ! defined( 'ship_wst_ASSETS_IMAGES_URL' ) ) {
	define( 'ship_wst_ASSETS_IMAGES_URL', ship_wst_ASSETS_URL . '/images/' );
}
//endregion

function ship_wst_init() {
	// Load required classes and functions
	require_once( ship_wst_DIR . 'class.woocommerce-shipping-tracking.php' );

	global $wst_Instance;
	$wst_Instance = new WooCommerce_Shipping_Tracking();
}

add_action( 'ship_wst_init', 'ship_wst_init' );
add_action('woocommerce_view_order', 'track_order');



global $jal_db_version;
$jal_db_version = '1.0';




function track_order($order_id){
  if ( is_plugin_active( 'woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers.php' ) ) {
				$order_id = wc_seq_order_number_pro()->find_order_by_order_number( $order_id );

				} else 
				{
				$order_id = $order_id;

				}
					$data = get_post_custom($order_id);

					$order_tracking_code = isset( $data['ship_tracking_code'][0] ) ? $data['ship_tracking_code'][0] : '';
					$order_carrier_name  = isset( $data['ship_courier_name'][0] ) ? $data['ship_courier_name'][0] : '';
					$a =  get_option('my_option_name'); 
				
					$username         	= $a['id_number'];
					$password     	= $a['title'];
					$url = "http://shipway.in/api/getawbresult";
					$data_string = array(
					"username" 		=> $username,
					"password" 		=> $password,
					"carrier_id" 	=> $order_carrier_name,
					"awb" 			=> $order_tracking_code,
					"order_id"		=> $order_id
					);

					$data_string = json_encode($data_string);
					$curl        = curl_init();
					curl_setopt($curl, CURLOPT_HTTPHEADER, array(
					'Content-Type:application/json'
					));
					curl_setopt($curl, CURLOPT_URL, $url);
					curl_setopt($curl, CURLOPT_POST, true);
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
					$output = curl_exec($curl);
					curl_close($curl);
					echo $output; 
  }



function ship_wst_install() {

	if ( ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'ship_wst_install_woocommerce_admin_notice' );
	} elseif ( defined( 'ship_wst_PREMIUM' ) ) {
		add_action( 'admin_notices', 'ship_wst_install_free_admin_notice' );
		deactivate_plugins( plugin_basename( __FILE__ ) );
	} else {
		do_action( 'ship_wst_init' );
	}
	}
	
	global $wpdb;
	global $jal_db_version;

	$table_name = $wpdb->prefix . 'sw_couriers';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		 `courier_id` int(11) NOT NULL,
						  `name` varchar(255) NOT NULL,
						  status int(2) NOT NULL,
						  PRIMARY KEY (`courier_id`)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'jal_db_version', $jal_db_version );

	add_action( 'plugins_loaded', 'ship_wst_install', 11 );

	add_shortcode('shipway', 'generate_content');
              function generate_content() { 
			if(!isset($_REQUEST['_locale'])){

			?>
				
				
				<form id="shipway" class="shipway" action="" method="POST">
			    <fieldset>
				<p>
					<label for="shipway"><?php _e('Order Id'); ?></label>
				<input style="width: 100%" type="text" name="order_id" id="order_id"
					       placeholder="Order Id"
					       value=""/ required="required">
				</p>
			
				<p><input type="submit" value="<?php _e('Track'); ?>"/>
				</p>
			</fieldset>
		</form>
				</br>
				
			<?php 	
			}
			if(isset($_POST['order_id'])){
			
				if ( is_plugin_active( 'woocommerce-sequential-order-numbers-pro/woocommerce-sequential-order-numbers.php' ) ) {
				$order_id = wc_seq_order_number_pro()->find_order_by_order_number( $_POST['order_id'] );

				} else 
				{
				$order_id = $_POST['order_id'];

				}

					$data                = get_post_custom($order_id);
					$order_tracking_code = isset( $data['ship_tracking_code'][0] ) ? $data['ship_tracking_code'][0] : '';
					$order_carrier_name  = isset( $data['ship_courier_name'][0] ) ? $data['ship_courier_name'][0] : '';
					$a =  get_option('my_option_name'); 
					$username         	= $a['id_number'];
					$password     	= $a['title'];



					$url         = "http://shipway.in/api/getawbresult";
					$data_string = array(
					"username" 		=> $username,
					"password" 		=> $password,
					"carrier_id" 	=> $order_carrier_name,
					"awb" 			=> $order_tracking_code,
					"order_id"		=> $order_id
					);

					$data_string = json_encode($data_string);
					$curl        = curl_init();
					curl_setopt($curl, CURLOPT_HTTPHEADER, array(
					'Content-Type:application/json'
					));
					curl_setopt($curl, CURLOPT_URL, $url);
					curl_setopt($curl, CURLOPT_POST, true);
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
					$output = curl_exec($curl);
					curl_close($curl);
					return $output;      ?>
		
				
			<?php  }
			}?>
