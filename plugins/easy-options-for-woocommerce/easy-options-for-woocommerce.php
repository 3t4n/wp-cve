<?php
/*
 * Plugin Name: Easy Options for WooCommerce
 * Version: 1.6.2
 * Description: Access hidden WooCommerce options such as: Disable Confirm Logout, Hide Password Strength Meter, Hide Categories from Shop Pages and Widgets, Show Empty Product Categories, Hide Product Category Name/Count, Sort Shipping Methods from Least Expensive to Most Expensive, Hide Related Products on product page, Force Local Pickup Shipping Method to be last choice, Disable New User Registration from wp-login.php screen.
 * Author: Jeff Sherk
 * Author URI: https://wordpress.org/support/plugin/easy-options-for-woocommerce/
 * Plugin URI: https://wordpress.org/plugins/easy-options-for-woocommerce/
 * Donate link: https://www.paypal.me/jsherk/10usd
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 */

$jdseowc_plugin_admin_name = "Easy Options for WooCommerce";
$jdseowc_plugin_admin_link = "easy-options-for-woocommerce";
$jdseowc_plugin_admin_donate_url = "https://www.paypal.me/jsherk/10usd";
$jdseowc_plugin_admin_review_url = "https://wordpress.org/support/plugin/".$jdseowc_plugin_admin_link."/reviews/#new-post";
 
/* ****************************************************************************** */
function jdseowc_add_links_to_admin_plugins_page($links) {

	global $jdseowc_plugin_admin_name;
	global $jdseowc_plugin_admin_link;
	global $jdseowc_plugin_admin_donate_url;
	global $jdseowc_plugin_admin_review_url;

	$review_url = $jdseowc_plugin_admin_review_url;
	$review_url = esc_url($review_url);
	$review_link = '<a href="'.$review_url.'">Leave a REVIEW</a>';
	array_unshift( $links, $review_link );

	$donate_url = $jdseowc_plugin_admin_donate_url;
	$donate_url = esc_url($donate_url);
	$donate_link = '<a href="'.$donate_url.'">DONATE</a>'; //DONATE
	array_unshift( $links, $donate_link ); //DONATE

	$url = get_admin_url() . 'options-general.php?page='.$jdseowc_plugin_admin_link;
	$url = esc_url($url);
	$settings_link = '<a href="'.$url.'">Settings</a>';
	array_unshift( $links, $settings_link );

	return $links;
}
add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'jdseowc_add_links_to_admin_plugins_page' );


/* ****************************************************************************** */
function jdseowc_add_meta_to_admin_plugins_page( $links, $file ) {

	global $jdseowc_plugin_admin_name;
	global $jdseowc_plugin_admin_link;
	global $jdseowc_plugin_admin_donate_url;
	global $jdseowc_plugin_admin_review_url;

	if ( strpos( $file, plugin_basename(__FILE__) ) !== false ) {

		$review_url = $jdseowc_plugin_admin_review_url;
		$review_url = esc_url($review_url);

		$donate_url = $jdseowc_plugin_admin_donate_url;
		$donate_url = esc_url($donate_url);

		$url = get_admin_url() . 'options-general.php?page='.$jdseowc_plugin_admin_link;
		$url = esc_url($url);
		
		//$pluginbut = plugins_url( 'dbut.png', __FILE__ );
		//$new_links = array('<a href="'.$url.'">Settings</a>', '<a href="'.$review_url.'">Leave a 5-star REVIEW</a>', '<a href="'.$donate_url.'">Thanks for supporting me! <img style="vertical-align:bottom;" height="30" src="'.$pluginbut.'"></a>'); //REVIEW & DONATE
		$donate_button = plugins_url( 'dbut-small.png', __FILE__ );
		$review_stars = plugins_url( 'stars-small.png', __FILE__ );
		$new_links = array('<a href="'.$url.'">Settings</a>', '<a href="'.$review_url.'">Leave a REVIEW <img style="vertical-align:text-top;" height="12" src="'.$review_stars.'"></a>', '<a href="'.$donate_url.'">Thanks for supporting me! <img style="vertical-align:bottom;" height="20" src="'.$donate_button.'"></a>'); //REVIEW & DONATE

		$links = array_merge( $links, $new_links );

	}
	
	return $links;
}
add_filter( 'plugin_row_meta', 'jdseowc_add_meta_to_admin_plugins_page', 10, 2 );


/* ****************************************************************************** */
function jdseowc_add_admin_settings_menu() {
	global $jdseowc_plugin_admin_name;
	global $jdseowc_plugin_admin_link;
	add_options_page( $jdseowc_plugin_admin_name.' by Jeff Sherk', $jdseowc_plugin_admin_name, 'activate_plugins', $jdseowc_plugin_admin_link, 'jdseowc_show_plugin_options' );
}
add_action( 'admin_menu', 'jdseowc_add_admin_settings_menu' );


/* ****************************************************************************** */
function jdseowc_disable_confirm_logout() {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if (is_plugin_active("woocommerce/woocommerce.php")) {

		global $wp;
		if (get_option('jdseowc_option_disable_confirm_logout')) {
			if ( isset( $wp->query_vars['customer-logout'] ) ) {
				wp_redirect( str_replace( '&amp;', '&', wp_logout_url( wc_get_page_permalink( 'myaccount' ) ) ) );
				exit;
			}
		}

	}
}
add_action( 'template_redirect', 'jdseowc_disable_confirm_logout' );


/* ****************************************************************************** */
function jdseowc_show_empty_categories ( $hide_empty ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if (is_plugin_active("woocommerce/woocommerce.php")) {

		$show_enabled = get_option('jdseowc_show_empty_categories_enabled', false);
		if ($show_enabled == "on") {
			$hide_empty  =  false;
		} else {
			$hide_empty  =  true;
		}
		return $hide_empty;

	}
}
add_filter( 'woocommerce_product_subcategories_hide_empty', 'jdseowc_show_empty_categories', 10, 1 );


/* ****************************************************************************** */
function jdseowc_set_min_password_strength($strength) {

	return $strength;
	// ---- Will not get past this line ever --- //
	//NOTE: This feature is currently DISABLED because password strength does not seem to work at all.
	// Have left feature of turning the the password strength meter on/off only.
	
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if (is_plugin_active("woocommerce/woocommerce.php")) {

		$password_strength = get_option("jdseowc_min_password_strength");
		if ($password_strength != "0" && $password_strength != "1" && $password_strength != "2" && $password_strength != "3" && $password_strength != "4") {
			return $strength; //Make sure there is valid password strength setting option saved, otherwise return the default
		}
		return $password_strength;

	}
}
add_filter( 'woocommerce_min_password_strength', 'jdseowc_set_min_password_strength' );


/* ****************************************************************************** */
function jdseowc_set_min_password_strength_disable_meter() {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if (is_plugin_active("woocommerce/woocommerce.php")) {

		//Always disable the meter when setting is Stength 0 = Very Weak Anything
		if (get_option("jdseowc_min_password_strength") == "0") { 
			if ( wp_script_is( 'wc-password-strength-meter', 'enqueued' ) ) {
				wp_dequeue_script( 'wc-password-strength-meter' );
			}
		}

	}
}
add_action( 'wp_print_scripts', 'jdseowc_set_min_password_strength_disable_meter', 100 );


/* ****************************************************************************** */
function jdseowc_product_category_css_settings() {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if (is_plugin_active("woocommerce/woocommerce.php")) {

		//Hide the text/title under each Product Category in WooCommerce on the Shop/category pages
		$hide_title = get_option('jdseowc_hide_product_category_title');
		if ($hide_title) {
			?>
				<style>
					h2.woocommerce-loop-category__title {
						display:none !important;
					}
				</style>
			<?php
		}

		//Hide the "total count" for each Product Category in WooCommerce on the Shop/category pages
		$hide_count = get_option('jdseowc_hide_product_category_count');
		if ($hide_count) {
			?>
				<style>
					ul.products .count {
						display:none !important;
					}
				</style>
			<?php
		}

	}
}
add_action('wp_head', 'jdseowc_product_category_css_settings');


/* ****************************************************************************** */
function jdseowc_disable_admin_new_user_notification_email($result = array("to"=>"", "subject"=>"", "message"=>"", "headers"=>"", "attachments"=>"") ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if (is_plugin_active("woocommerce/woocommerce.php")) {
		if ( is_array($result) ) {
			extract($result); //Array KEY name becomes variable name and KEY value becomes variable value. Should create $to, $subject, $message, $headers, $attachments
		} else {
			// If its not an array then something is wrong do just set everything empty
			$to = '';
			$subject = '';
			$message = '';
			$headers = '';
			$attachments = array ();
		}
		
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
		$admin_email = get_option('admin_email');

		if (get_option("jdseowc_new_user_registration_admin_email_enabled") == "off") {
			if (strpos($to, $admin_email) !== false) {
				if (strstr(sprintf(__('[%s] New User Registration'), $blogname), $subject)) {
					$to = '';
					$subject = '';
					$message = '';
					$headers = '';
					$attachments = array ();
					return compact('to', 'subject', 'message', 'headers', 'attachments');
				}
			}
		}

		if (get_option("jdseowc_user_password_lost_changed_admin_email_enabled") == "off") {
			if (strpos($to, $admin_email) !== false) {
				if ( strstr(sprintf(__('[%s] Password Lost/Changed'), $blogname), $subject) || strstr(sprintf(__('[%s] Password Changed'), $blogname), $subject) ) {
					$to = '';
					$subject = '';
					$message = '';
					$headers = '';
					$attachments = array ();
					return compact('to', 'subject', 'message', 'headers', 'attachments');
				}
			}
		}

		//Change the message the user receives from "set your password" to "change your password"
		if (get_option("jdseowc_new_user_email_text_change") == true) {
			if (strpos($subject, "Your username and password info") !== false) {
				if (strpos($message, "To set your password, visit the following address") !== false) {
					$message = str_replace("To set your password, visit the following address", "To change your password, visit the following address", $message); //Change the message
					return compact('to', 'subject', 'message', 'headers', 'attachments');
				}
			}
		}

		return $result;

	} else {
		return $result;
		
	}
}
add_filter('wp_mail', 'jdseowc_disable_admin_new_user_notification_email');


/* ****************************************************************************** */
function jdseowc_disable_wplogin_registration_page($value) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if (is_plugin_active("woocommerce/woocommerce.php")) {

		if (get_option('jdseowc_disable_wplogin_registration_page')) {
			$script = basename(parse_url($_SERVER['SCRIPT_NAME'], PHP_URL_PATH));
			if ($script == 'wp-login.php') {
				$value = false;
			}
		}
		return $value;

	}
}
add_filter('option_users_can_register', 'jdseowc_disable_wplogin_registration_page');


/* ****************************************************************************** */
function jdseowc_sort_woocommerce_available_shipping_methods( $rates, $package ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if (is_plugin_active("woocommerce/woocommerce.php")) {

		if ( get_option('jdseowc_sort_woocommerce_shipping_methods_least_to_most') == "on" || get_option('jdseowc_sort_woocommerce_shipping_methods_local_pickup_last') == "on" ) {

			//  if there are no rates don't do anything
			if ( ! $rates ) {
				return;
			}
			
			//SORT shipping methods from LEAST EXPENSIVE to MOST EXPENSIVE
			if ( get_option('jdseowc_sort_woocommerce_shipping_methods_least_to_most') == "on" ) {
				// get an array of prices
				$prices = array();
				foreach( $rates as $rate ) {
					$prices[] = $rate->cost;
				}
				// use the prices to sort the rates
				array_multisort( $prices, SORT_ASC, $rates ); //SORT_ASC is default/assumed and can actually be omitted from this command. Use SORT_DESC for other way.
			}

			//If LOCAL PICKUP is an option, then move to the bottom so it is last choice
			if ( get_option('jdseowc_sort_woocommerce_shipping_methods_local_pickup_last') == "on" ) {
				$method = array();
				$local_pickup = false;
				$count_position = 0;
				//Check if Local Pickup is an option
				foreach( $rates as $key => $rate ) {
					$method[] = $rate->method_id;
					if ($rate->method_id == "local_pickup") {
						$local_pickup = $key;
					}
					$count_position++;
				}
				//If Local Pickup was an option then pull it out and put it on the end
				if ($local_pickup !== false) {
					$temp = array();
					$temp = $rates[$local_pickup];
					unset($rates[$local_pickup]);
					$rates[$local_pickup] = $temp;
				}
			}

		}
	}

	return $rates;
}
add_filter( 'woocommerce_package_rates' , 'jdseowc_sort_woocommerce_available_shipping_methods', 10, 2 );


/* ****************************************************************************** */
function jdseowc_remove_related_product() {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if (is_plugin_active("woocommerce/woocommerce.php")) {
		if (get_option('jdseowc_hide_related_products') == "on") {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
		}
	}
} 
add_action( 'init', 'jdseowc_remove_related_product');


/* ****************************************************************************** */
// Hide woocommerce category ids from widget list and widget dropdown
function jdseowc_hide_categories_from_widget($args) {
	$get_option = get_option('jdseowc_hide_categories_from_widgets'); // get the option
	if ($get_option !== false && $get_option !== "") { // if it is not empty/blank then continue
		$args['exclude'] = $get_option; // options were validated when saved, so no longe need code below
		/*
		$explode = explode (",", $get_option); // split the option by comma
		$first_id = true;
		foreach ($explode as $id) {
			$id = trim($id); // remove any white space
			if ( is_numeric($id) !== false) { // is it a number already
				$id_float = floatval($id); // convert string to number
				$id_floor = floor($id_float); // round it down to integer value
				if ( $id_float == $id_floor ) {
					$id = intval($id); // grab the integer value
					if (is_integer($id) && $id > 0) { // is it an integer greater than 0
						if ($first_id) {
							$first_id = false;
							$args['exclude'] = "$id"; // first id has no comma
						} else {
							$args['exclude'] .= ",$id"; // add comma before each id
						}
					}
				}
			}
		}
		*/
	}
	return $args;
}
add_filter( 'woocommerce_product_categories_widget_dropdown_args', 'jdseowc_hide_categories_from_widget');
add_filter( 'woocommerce_product_categories_widget_args', 'jdseowc_hide_categories_from_widget');


/* ****************************************************************************** */
// Hide woocommerce category ids from shop page
function jdseowc_hide_these_categories_from_shop_page( $terms ) {
	if ( is_shop() ) {
		$new_terms = array(); // new_terms will have all terms, except the excluded/hiddent ones
		$get_option = get_option('jdseowc_hide_categories_from_shop_page'); // get the option
		$exclude_ids = "";
		if ($get_option !== false && $get_option !== "") { // if it is not empty/blank then continue
			$exclude_ids = $get_option; // options were validated when saved, so no longe need code below
			/*
			$explode = explode (",", $get_option); // split the option by comma
			$first_id = true;
			foreach ($explode as $id) {
				$id = trim($id); // remove any white space
				if ( is_numeric($id) !== false) { // is it a number already
					$id_float = floatval($id); // convert string to number
					$id_floor = floor($id_float); // round it down to integer value
					if ( $id_float == $id_floor ) {
						$id = intval($id); // grab the integer value
						if (is_integer($id) && $id > 0) { // is it an integer greater than 0
							if ($first_id) {
								$first_id = false;
								$exclude_ids = "$id"; // first id has no comma
							} else {
								$exclude_ids .= ",$id"; // add comma before each id
							}
						}
					}
				}
			}
			*/
		}

		// if there are any ids, then see if they need to be excluded
		if ($exclude_ids !== "") {
			$explode_ids = explode(",", $exclude_ids);
			foreach ($terms as $term) {
				$id_matched = false;
				$matched_id = 0;
				foreach ($explode_ids as $id_to_match) { // check if any of the excluded cat ids match the term_taxonomy_id
					if ($term->term_taxonomy_id == $id_to_match) {
						$id_matched = true;
						$matched_id = $id_to_match;
					}
				}
				if ($term->taxonomy == "product_cat" && $term->term_taxonomy_id == $matched_id && $id_matched && ! is_admin() ) {
					// this is an exlcuded/hidden id, so do nothing (do NOT add it back to new_terms).
				} else {
					$new_terms[] = $term; // add this term back into new terms array since it is not excluded
				}
			}
			if (count($new_terms) != count($terms)) {
				$terms = $new_terms; // if $new_terms count is different than $terms count then a category was found
			}
		}
	}
	
	return $terms;
}
add_filter( 'get_terms', 'jdseowc_hide_these_categories_from_shop_page', 10, 1 );


/* ****************************************************************************** */
function jdseowc_show_plugin_options() {

	global $jdseowc_plugin_admin_name;
	global $jdseowc_plugin_admin_link;
	global $jdseowc_plugin_admin_donate_url;
	global $jdseowc_plugin_admin_review_url;

	$donate_url = $jdseowc_plugin_admin_donate_url;
	$review_url = $jdseowc_plugin_admin_review_url;

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); //Required for Front End users but not for Admin area
	if (is_plugin_inactive("woocommerce/woocommerce.php")) {

		?>
		<div class="wrap">
			<h1><?php _e( $jdseowc_plugin_admin_name ); ?></h1>
			<h3><i style='color:red;'>NOTICE: WooCommerce must be installed and activated to use this plugin.</i></h3>
		</div>
		<?php

	} else {

		?>
		<div class="wrap">
			<h1><?php _e( $jdseowc_plugin_admin_name ); ?></h1>
		</div>
		<?php $pluginbut = plugins_url( 'dbut.png', __FILE__ ); ?>
		<br>How much is this plugin worth to you? A suggested <a href="<?php echo $donate_url; ?>">donation of $10 or more</a> will help me feed my kids, pay my bills and keep this plugin updated!<br><a href="<?php echo $donate_url; ?>"><img width="175" src="<?php echo $pluginbut; ?>"></a>
			<br>Reviews are also very hepful. Please consider leaving a <a href="<?php echo $review_url; ?>">postive REVIEW by cllicking here</a>.<hr>
		<?php

		//Setup any checkbox options that need a default different than blank and should be set to either ON or OFF
		if (get_option('jdseowc_show_empty_categories_enabled') != "on" && get_option('jdseowc_show_empty_categories_enabled') != "off") {
			update_option( 'jdseowc_show_empty_categories_enabled', "off", true ); //If option not set then set to OFF
		}
		if (get_option('jdseowc_new_user_registration_admin_email_enabled') != "on" && get_option('jdseowc_new_user_registration_admin_email_enabled') != "off") {
			update_option( 'jdseowc_new_user_registration_admin_email_enabled', "on", true ); //if option not set then set to ON
		}
		if (get_option('jdseowc_user_password_lost_changed_admin_email_enabled') != "on" && get_option('jdseowc_user_password_lost_changed_admin_email_enabled') != "off") {
			update_option('jdseowc_user_password_lost_changed_admin_email_enabled', "on", true ); //If option not set then set to ON
		}
		if (get_option('jdseowc_sort_woocommerce_shipping_methods_least_to_most') != "on" && get_option('jdseowc_sort_woocommerce_shipping_methods_least_to_most') != "off") {
			update_option( 'jdseowc_sort_woocommerce_shipping_methods_least_to_most', "off", true ); //If option not set then set to OFF
		}
		if (get_option('jdseowc_sort_woocommerce_shipping_methods_local_pickup_last') != "on" && get_option('jdseowc_sort_woocommerce_shipping_methods_local_pickup_last') != "off") {
			update_option( 'jdseowc_sort_woocommerce_shipping_methods_local_pickup_last', "off", true ); //If option not set then set to OFF
		}
		if (get_option('jdseowc_hide_related_products') != "on" && get_option('jdseowc_hide_related_products') != "off") {
			update_option( 'jdseowc_hide_related_products', "off", true ); //If option not set then set to OFF
		}
		
		// Set text boxes to blank/empty
		if (get_option('jdseowc_hide_categories_from_shop_page') === false) {
			update_option( 'jdseowc_hide_categories_from_shop_page', "", true ); // set text field to blank if it does not exist
		}
		if (get_option('jdseowc_hide_categories_from_widgets') === false) {
			update_option( 'jdseowc_hide_categories_from_widgets', "", true ); // set text field to blank if it does not exist
		}

		$settings_saved = false;

		if ( isset( $_POST[ 'buttonSaveChanges' ] ) && current_user_can('activate_plugins') && check_admin_referer('save-changes-to-form') ) { //Check if nonce is valid using check_admin_referer or wp_verify_nonce

			$post_data = sanitize_text_field($_POST[ 'jdseowc_option_disable_confirm_logout' ]);
			update_option( 'jdseowc_option_disable_confirm_logout', $post_data, true );

			$post_data = sanitize_text_field($_POST[ 'jdseowc_show_empty_categories_enabled' ]);
			update_option( 'jdseowc_show_empty_categories_enabled', $post_data, true );

			$post_data = sanitize_text_field($_POST[ 'jdseowc_hide_categories_from_shop_page' ]);
			// we need to check this field and only allow integers seperated by commas, so remove everything that is invalid
			$post_ids = "";
			if ($post_data !== false && $post_data !== "") { // if it is not empty/blank then continue
				$explode = explode (",", $post_data); // split the option by comma
				$first_id = true;
				foreach ($explode as $id) {
					$id = trim($id); // remove any white space
					if ( is_numeric($id) !== false) { // is it a number already
						$id_float = floatval($id); // convert string to number
						$id_floor = floor($id_float); // round it down to integer value
						if ( $id_float == $id_floor ) {
							$id = intval($id); // grab the integer value
							if (is_integer($id) && $id > 0) { // is it an integer greater than 0
								if ($first_id) {
									$first_id = false;
									$post_ids = "$id"; // first id has no comma
								} else {
									$post_ids .= ",$id"; // add comma before each id
								}
							}
						}
					}
				}
			}
			update_option( 'jdseowc_hide_categories_from_shop_page', $post_ids, true );

			$post_data = sanitize_text_field($_POST[ 'jdseowc_hide_categories_from_widgets' ]);
			// we need to check this field and only allow integers seperated by commas, so remove everything that is invalid
			$post_ids = "";
			if ($post_data !== false && $post_data !== "") { // if it is not empty/blank then continue
				$explode = explode (",", $post_data); // split the option by comma
				$first_id = true;
				foreach ($explode as $id) {
					$id = trim($id); // remove any white space
					if ( is_numeric($id) !== false) { // is it a number already
						$id_float = floatval($id); // convert string to number
						$id_floor = floor($id_float); // round it down to integer value
						if ( $id_float == $id_floor ) {
							$id = intval($id); // grab the integer value
							if (is_integer($id) && $id > 0) { // is it an integer greater than 0
								if ($first_id) {
									$first_id = false;
									$post_ids = "$id"; // first id has no comma
								} else {
									$post_ids .= ",$id"; // add comma before each id
								}
							}
						}
					}
				}
			}
			update_option( 'jdseowc_hide_categories_from_widgets', $post_ids, true );

			$post_data = sanitize_text_field($_POST[ 'jdseowc_min_password_strength' ]);
			update_option( 'jdseowc_min_password_strength', $post_data, true );

			$post_data = sanitize_text_field($_POST[ 'jdseowc_hide_product_category_title' ]);
			update_option( 'jdseowc_hide_product_category_title', $post_data, true );

			$post_data = sanitize_text_field($_POST[ 'jdseowc_hide_product_category_count' ]);
			update_option( 'jdseowc_hide_product_category_count', $post_data, true );

			$post_data = sanitize_text_field($_POST[ 'jdseowc_new_user_registration_admin_email_enabled' ]);
			update_option( 'jdseowc_new_user_registration_admin_email_enabled', $post_data, true );

			$post_data = sanitize_text_field($_POST[ 'jdseowc_user_password_lost_changed_admin_email_enabled' ]);
			update_option( 'jdseowc_user_password_lost_changed_admin_email_enabled', $post_data, true );

			$post_data = sanitize_text_field($_POST[ 'jdseowc_new_user_email_text_change' ]);
			update_option( 'jdseowc_new_user_email_text_change', $post_data, true );

			$post_data = sanitize_text_field($_POST[ 'jdseowc_disable_wplogin_registration_page' ]);
			update_option( 'jdseowc_disable_wplogin_registration_page', $post_data, true );

			$post_data = sanitize_text_field($_POST[ 'jdseowc_sort_woocommerce_shipping_methods_least_to_most' ]);
			update_option( 'jdseowc_sort_woocommerce_shipping_methods_least_to_most', $post_data, true );

			$post_data = sanitize_text_field($_POST[ 'jdseowc_sort_woocommerce_shipping_methods_local_pickup_last' ]);
			update_option( 'jdseowc_sort_woocommerce_shipping_methods_local_pickup_last', $post_data, true );

			$post_data = sanitize_text_field($_POST[ 'jdseowc_hide_related_products' ]);
			update_option( 'jdseowc_hide_related_products', $post_data, true );
			
			$settings_saved = true;

		}
		?>

		<script>
			jQuery(document).ready(function($){
				$('#buttonSaveChanges').click(function(){ $(this).prop('value', '.....  SAVING  CHANGES  .....'); }); //Change Save Changes button text and disable button when clicked
			});
		</script>
		<?php if ( $settings_saved ) : ?>
		<script>
			jQuery(document).ready(function($){
				$('.fadeoutChangesSaved').click(function(){ $(this).fadeOut('fast'); }); //fadeout Changes Saved message if clicked
				setTimeout(function(){ $('.fadeoutChangesSaved').fadeOut("slow"); } ,2000); //fadeout Changes Saved message after 5 seconds
			});
		</script>
		<div id="message" class="updated fadeoutChangesSaved">
			<p><strong><?php _e( 'Changes saved.' ) ?></strong></p>
		</div>
				
		<?php endif ?>

		<form method="post" action="">

			<p>
				<?php
				$this_option_name = "jdseowc_option_disable_confirm_logout";
				$checked = "";
				if (get_option($this_option_name)) {
					$checked = "CHECKED";
				}
				?>
				<h2><u>WC CONFIRM LOGOUT</u></h2>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?>>
				<label for="<?php echo $this_option_name; ?>"><?php _e( 'Check to DISABLE the "Confirm Logout" message on the WooCommerce My Account page' ) ?></label>
			</p>

			<p>
				<br><h2><u>WC PRODUCTS</u></h2>
				<?php
				$this_option_name = "jdseowc_hide_product_category_title";
				$checked = "";
				if (get_option($this_option_name)) {
					$checked = "CHECKED";
				}
				?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?>>
				<label for="<?php echo $this_option_name; ?>"><?php _e( 'Check to HIDE the title/text under each Product Category on Shop pages' ) ?></label>

				<br><br>
				<?php
				$this_option_name = "jdseowc_hide_product_category_count";
				$checked = "";
				if (get_option($this_option_name)) {
					$checked = "CHECKED";
				}
				?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?>>
				<label for="<?php echo $this_option_name; ?>"><?php _e( 'Check to HIDE the total count number under each Product Category on Shop pages' ) ?></label>

				<br><br>
				<?php
				$this_option_name = "jdseowc_show_empty_categories_enabled";
				$checked = "";
				if (get_option($this_option_name) == "on") {
					$checked = "CHECKED";
				}
				?>
				<input type="hidden" name="<?php echo $this_option_name; ?>" value="off" >
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?> value="on" >
				<label for="<?php echo $this_option_name; ?>"><?php _e( 'Check to SHOW empty Product Categories on Shop pages' ) ?></label>
				<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 85%; font-style: italic;">NOTE: You may need to change Shop Page Display to include Categories and/or change Default Category Display to include Subcategories. You can find these settings at Appearance > Customize > WooCommerce > Product Catalog</span>

				<br><br>
				<?php
				$this_option_name = "jdseowc_hide_related_products";
				$checked = "";
				if (get_option($this_option_name) == "on") {
					$checked = "CHECKED";
				}
				?>
				<input type="hidden" name="<?php echo $this_option_name; ?>" value="off" >
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?>>
				<label for="<?php echo $this_option_name; ?>"><?php _e( 'Check to HIDE Related Products section on single product pages.' ) ?></label>
				
				<br><br>
				<?php
				$this_option_name = "jdseowc_hide_categories_from_shop_page";
				$this_option_value = get_option($this_option_name);
				?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="<?php echo $this_option_name; ?>"><?php _e( 'Hide these Category IDs from main Shop Page:' ) ?></label>
				<input type="textbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" value="<?php echo $this_option_value; ?>" size="50">
				<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 85%; font-style: italic;">NOTE: Enter a comma seperated list of Category IDs that you want to hide from Shop Page. Leave blank to disable.</span>
				
				<br><br>
				<?php
				$this_option_name = "jdseowc_hide_categories_from_widgets";
				$this_option_value = get_option($this_option_name);
				?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="<?php echo $this_option_name; ?>"><?php _e( 'Hide these Category IDs from Widget list/dropdown:' ) ?></label>
				<input type="textbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" value="<?php echo $this_option_value; ?>" size="50">
				<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 85%; font-style: italic;">NOTE: Enter a comma seperated list of Category IDs that you want to hide from Widget lists/dropdowns. Leave blank to disable.</span>
			</p>

		<p>
			<br><h2><u>WC SHIPPING</u></h2>
			<?php
			$this_option_name = "jdseowc_sort_woocommerce_shipping_methods_least_to_most";
			$checked = "";
			if (get_option($this_option_name) == "on") {
				$checked = "CHECKED";
			}
			?>
			<input type="hidden" name="<?php echo $this_option_name; ?>" value="off" >
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?> value="on">
			<label for="<?php echo $this_option_name; ?>"><?php _e( 'Check to sort shipping methods from LEAST EXPENSIVE to MOST EXPENSIVE.' ) ?></label>
			<br><br>
			<?php
			$this_option_name = "jdseowc_sort_woocommerce_shipping_methods_local_pickup_last";
			$checked = "";
			if (get_option($this_option_name) == "on") {
				$checked = "CHECKED";
			}
			?>
			<input type="hidden" name="<?php echo $this_option_name; ?>" value="off" >
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?> value="on">
			<label for="<?php echo $this_option_name; ?>"><?php _e( 'Check to move LOCAL PICKUP to bottom of list and force it to be last choice.' ) ?></label>
		</p>

			<p>
				<?php
				$this_option_name = "jdseowc_min_password_strength";
				$pass_select0 = "";
				$pass_select1 = "";
				$pass_select2 = "";
				$pass_select3 = "";
				$pass_select4 = "";
	
				$pass_strength = get_option($this_option_name);
				if ($pass_strength != "0" && $pass_strength != "1" && $pass_strength != "2" && $pass_strength != "3" && $pass_strength != "4") {
					$pass_strength = "3";
				}
	
				if ($pass_strength == "0") {
					$pass_select0 = "selected='selected'";
				} else if ($pass_strength == "1") {
					$pass_select1 = "selected='selected'";
				} else if ($pass_strength == "2") {
					$pass_select2 = "selected='selected'";
				} else if ($pass_strength == "4") {
					$pass_select4 = "selected='selected'";
				} else {
					$pass_select3 = "selected='selected'";
				}
				?>
				<br><h2><u>WC PASSWORD STRENGTH METER DISPLAY</u></h2>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="<?php echo $this_option_name; ?>"><?php _e( 'Choose:' ) ?></label>&nbsp;<select id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>">
					<option value="3" <?php echo $pass_select3; ?>>Show Password Strength Meter (default)</option>
					<option value="0" <?php echo $pass_select0; ?>>Hide Password Strength Meter</option>
				</select>
				<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 85%; font-style: italic;"></span>
			</p>

		<p>
			<br><h2><u>WP-LOGIN REGISTRATION PAGE and EMAILS</u></h2>
			<?php
			$this_option_name = "jdseowc_disable_wplogin_registration_page";
			$checked = "";
			if (get_option($this_option_name)) {
				$checked = "CHECKED";
			}
			?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?> >
			<label for="<?php echo $this_option_name; ?>"><?php _e( 'Check to DISABLE new user registration on the wp-login.php?action=register page' ) ?></label>
			<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 85%; font-style: italic;">NOTE: This option does not affect WooCommerce new user registration pages.</span>

			<br><br>
			<?php
			$this_option_name = "jdseowc_new_user_email_text_change";
			$checked = "";
			if (get_option($this_option_name)) {
				$checked = "CHECKED";
			}
			?>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?> >
			<label for="<?php echo $this_option_name; ?>"><?php _e( 'Check to change the text in <u>New User Welcome email</u> from "set your password" to "change your password" ' ) ?></label>
			<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 85%; font-style: italic;">NOTE: This option only applies to the wp-login.php?action=register page and does not affect WooCommerce new user registration pages. This option only works for English language.</span>

			<br><br>
			<?php
			$this_option_name = "jdseowc_new_user_registration_admin_email_enabled";
			$checked = "";
			if (get_option($this_option_name) == "on") {
				$checked = "CHECKED";
			}
			?>
			<input type="hidden" name="<?php echo $this_option_name; ?>" value="off" >
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?> value="on" >
			<label for="<?php echo $this_option_name; ?>"><?php _e( 'Check to ENABLE admin notification email of new user registrations' ) ?></label>
			<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 85%; font-style: italic;">NOTE: This option only applies to the wp-login.php?action=register page and does not affect WooCommerce new user registration pages.</span>

			<br><br>
			<?php
			$this_option_name = "jdseowc_user_password_lost_changed_admin_email_enabled";
			$checked = "";
			if (get_option($this_option_name) == "on") {
				$checked = "CHECKED";
			}
			?>
			<input type="hidden" name="<?php echo $this_option_name; ?>" value="off" >
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?> value="on" >
			<label for="<?php echo $this_option_name; ?>"><?php _e( 'Check to ENABLE admin notification email of users lost/changed passwords' ) ?></label>
			<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 85%; font-style: italic;">NOTE: This option only applies to the wp-login.php?action=register page and does not affect WooCommerce new user registration pages.</span>
		</p>

<!-- ------------------------------------------------------------------------- -->
			<?php $pluginbut = plugins_url( 'dbut.png', __FILE__ ); ?>
			<br><hr>How much is this plugin worth to you? A suggested <a href="<?php echo $donate_url; ?>">donation of $10 or more</a> will help me feed my kids, pay my bills and keep this plugin updated!<br><a href="<?php echo $donate_url; ?>"><img width="175" src="<?php echo $pluginbut; ?>"></a>
			<br>Reviews are also very hepful. Please consider leaving a <a href="<?php echo $review_url; ?>">postive REVIEW by cllicking here</a>.<hr>

			<?php
				wp_nonce_field('save-changes-to-form'); //Add nonce hidden fields
			?>
			<p class="submit">
				<input class="button-primary buttonSaveChanges" id="buttonSaveChanges" name="buttonSaveChanges" type="submit" value="<?php _e( 'Save Changes' ) ?>" />
			</p>

		</form>

			<?php
    }

}
?>