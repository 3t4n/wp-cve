<?php
/*
 * Plugin Name: Supplier Data Fields for WooCommerce
 * Version: 1.0.5
 * Description: Adds supplier fields to the product data section of each product.
 * Author: Jeff Sherk
 * Author URI: https://wordpress.org/support/supplier-data-fields-for-wc/
 * Plugin URI: https://wordpress.org/plugins/supplier-data-fields-for-wc/
 * Donate link: https://www.paypal.me/jsherk/10usd
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
 */

//NOTE:
//  Do a search and replace for   jdsbtp_   and replace it with   jdssomethingunique_
//  Then set the plugin_admin_name and plugin_admin_link below.
$jdssdf_plugin_admin_name = "Supplier Data Fields for WC";
$jdssdf_plugin_admin_link = "supplier-data-fields-for-wc";
$jdssdf_plugin_admin_donate_url = "https://www.paypal.me/jsherk/10usd";
$jdssdf_plugin_admin_review_url = "https://wordpress.org/support/plugin/".$jdssdf_plugin_admin_link."/reviews/#new-post";
 
/* ****************************************************************************** */
function jdssdf_add_links_to_admin_plugins_page($links) {

	global $jdssdf_plugin_admin_name;
	global $jdssdf_plugin_admin_link;
	global $jdssdf_plugin_admin_donate_url;
	global $jdssdf_plugin_admin_review_url;

	$review_url = $jdssdf_plugin_admin_review_url;
	$review_url = esc_url($review_url);
	$review_link = '<a href="'.$review_url.'">Leave a REVIEW</a>';
	array_unshift( $links, $review_link );

	$donate_url = $jdssdf_plugin_admin_donate_url;
	$donate_url = esc_url($donate_url);
	$donate_link = '<a href="'.$donate_url.'">DONATE</a>'; //DONATE
	array_unshift( $links, $donate_link ); //DONATE

	$url = get_admin_url() . 'options-general.php?page='.$jdssdf_plugin_admin_link;
	$url = esc_url($url);
	$settings_link = '<a href="'.$url.'">Settings</a>';
	array_unshift( $links, $settings_link );

	return $links;
}
add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'jdssdf_add_links_to_admin_plugins_page' );


/* ****************************************************************************** */
function jdssdf_add_meta_to_admin_plugins_page( $links, $file ) {

	global $jdssdf_plugin_admin_name;
	global $jdssdf_plugin_admin_link;
	global $jdssdf_plugin_admin_donate_url;
	global $jdssdf_plugin_admin_review_url;

	if ( strpos( $file, plugin_basename(__FILE__) ) !== false ) {

		$review_url = $jdssdf_plugin_admin_review_url;
		$review_url = esc_url($review_url);

		$donate_url = $jdssdf_plugin_admin_donate_url;
		$donate_url = esc_url($donate_url);

		$url = get_admin_url() . 'options-general.php?page='.$jdssdf_plugin_admin_link;
		$url = esc_url($url);

		$donate_button = plugins_url( 'dbut-small.png', __FILE__ );
		$review_stars = plugins_url( 'stars-small.png', __FILE__ );
		$new_links = array('<a href="'.$url.'">Settings</a>', '<a href="'.$review_url.'">Leave a REVIEW <img style="vertical-align:text-top;" height="12" src="'.$review_stars.'"></a>', '<a href="'.$donate_url.'">Thanks for supporting me! <img style="vertical-align:bottom;" height="20" src="'.$donate_button.'"></a>'); //REVIEW & DONATE

		$links = array_merge( $links, $new_links );

	}
	
	return $links;
}
add_filter( 'plugin_row_meta', 'jdssdf_add_meta_to_admin_plugins_page', 10, 2 );


/* ****************************************************************************** */
function jdssdf_add_admin_settings_menu() {
	global $jdssdf_plugin_admin_name;
	global $jdssdf_plugin_admin_link;
	add_options_page( $jdssdf_plugin_admin_name.' by Jeff Sherk', $jdssdf_plugin_admin_name, 'activate_plugins', $jdssdf_plugin_admin_link, 'jdssdf_show_plugin_options' );
	//Add additional option pages as necessary
	//add_options_page( $jdssdf_plugin_admin_name.' by Jeff Sherk', $jdssdf_plugin_admin_name.' Extras', 'activate_plugins', $jdssdf_plugin_admin_link.'-extra', 'jdssdf_show_plugin_options_extra' );
}
add_action( 'admin_menu', 'jdssdf_add_admin_settings_menu' );


/* ****************************************************************************** */
function jdssdf_hide_options_page() {
	global $jdssdf_plugin_admin_link;
	//Hide additional option pages from the Settings menu as necessary
	//remove_submenu_page('options-general.php', $jdssdf_plugin_admin_link.'-extra');
}
add_action('admin_menu', 'jdssdf_hide_options_page', 999);


/* ****************************************************************************** */
// Show custom supplier fields in General tab of product data
function jdssdf_custom_supplier_field_cost() {
	
	$my_options = get_option('jdssdf_options');
	
	if ($my_options['option_supplier_cost'] == 'on') {
		$args = array(
			'id' => '_supplier_cost',
			'label' => __( 'Supplier Cost'),
			'desc_tip' => false,
		);
		woocommerce_wp_text_input($args);
	}
	
	if ($my_options['option_supplier_min_price'] == 'on') {
		$args = array(
			'id' => '_supplier_min_price',
			'label' => __( 'Supplier Minimum Price'),
			'desc_tip' => false,
		);
		woocommerce_wp_text_input($args);
	}
	
	if ($my_options['option_supplier_barcode'] == 'on') {
		$args = array(
			'id' => '_supplier_barcode',
			'label' => __( 'Supplier Barcode'),
			'desc_tip' => false,
		);
		woocommerce_wp_text_input($args);
	}
	
	if ($my_options['option_supplier_sku'] == 'on') {
		$args = array(
			'id' => '_supplier_sku',
			'label' => __( 'Supplier SKU'),
			'desc_tip' => false,
		);
		woocommerce_wp_text_input($args);
	}
	
	if ($my_options['option_supplier_description'] == 'on') {
		$args = array(
			'id' => '_supplier_description',
			'label' => __( 'Supplier Description'),
			'desc_tip' => false,
		);
		woocommerce_wp_text_input($args);
	}
	
	if ($my_options['option_supplier_name'] == 'on') {
		$args = array(
			'id' => '_supplier_name',
			'label' => __( 'Supplier Name'),
			'desc_tip' => false,
		);
		woocommerce_wp_text_input($args);
	}
	
	if ($my_options['option_supplier_address'] == 'on') {
		$args = array(
			'id' => '_supplier_address',
			'label' => __( 'Supplier Address'),
			'desc_tip' => false,
		);
		woocommerce_wp_textarea_input($args);
	}
	
	if ($my_options['option_supplier_phone'] == 'on') {
		$args = array(
			'id' => '_supplier_phone',
			'label' => __( 'Supplier Phone'),
			'desc_tip' => false,
		);
		woocommerce_wp_text_input($args);
	}
	
	if ($my_options['option_supplier_email'] == 'on') {
		$args = array(
			'id' => '_supplier_email',
			'label' => __( 'Supplier Email'),
			'desc_tip' => false,
		);
		woocommerce_wp_text_input($args);
	}
	
	if ($my_options['option_supplier_notes'] == 'on') {
		$args = array(
			'id' => '_supplier_notes',
			'label' => __( 'Supplier Notes'),
			'desc_tip' => false,
		);
		woocommerce_wp_textarea_input($args);
	}
	
	if ($my_options['option_supplier_location'] == 'on') {
		$args = array(
			'id' => '_supplier_location',
			'label' => __( 'Supplier Location'),
			'desc_tip' => false,
		);
		woocommerce_wp_text_input($args);
	}
	
}
add_action( 'woocommerce_product_options_general_product_data', 'jdssdf_custom_supplier_field_cost' );


/* ****************************************************************************** */
// Save the data entered in into the custom supplier fields
function jdssdf_save_custom_supplier_fields( $post_id ) {
	
	$my_options = get_option('jdssdf_options');
	
	$product = wc_get_product( $post_id );
	
	if ($my_options['option_supplier_cost'] == 'on') {
		$data = isset( $_POST['_supplier_cost'] ) ? sanitize_text_field($_POST['_supplier_cost']) : '';
		$product->update_meta_data( '_supplier_cost', $data);
		$product->save();
	}
	
	if ($my_options['option_supplier_min_price'] == 'on') {
		$data = isset( $_POST['_supplier_min_price'] ) ? sanitize_text_field($_POST['_supplier_min_price']) : '';
		$product->update_meta_data( '_supplier_min_price', $data);
		$product->save();
	}
	
	if ($my_options['option_supplier_barcode'] == 'on') {
		$data = isset( $_POST['_supplier_barcode'] ) ? sanitize_text_field($_POST['_supplier_barcode']) : '';
		$product->update_meta_data( '_supplier_barcode', $data);
		$product->save();
	}
	
	if ($my_options['option_supplier_sku'] == 'on') {
		$data = isset( $_POST['_supplier_sku'] ) ? sanitize_text_field($_POST['_supplier_sku']) : '';
		$product->update_meta_data( '_supplier_sku', $data);
		$product->save();
	}
	
	if ($my_options['option_supplier_description'] == 'on') {
		$data = isset( $_POST['_supplier_description'] ) ? sanitize_text_field($_POST['_supplier_description']) : '';
		$product->update_meta_data( '_supplier_description', $data);
		$product->save();
	}
	
	if ($my_options['option_supplier_name'] == 'on') {
		$data = isset( $_POST['_supplier_name'] ) ? sanitize_text_field($_POST['_supplier_name']) : '';
		$product->update_meta_data( '_supplier_name', $data);
		$product->save();
	}
	
	if ($my_options['option_supplier_address'] == 'on') {
		$data = isset( $_POST['_supplier_address'] ) ? sanitize_text_field($_POST['_supplier_address']) : '';
		$product->update_meta_data( '_supplier_address', $data);
		$product->save();
	}
	
	if ($my_options['option_supplier_phone'] == 'on') {
		$data = isset( $_POST['_supplier_phone'] ) ? sanitize_text_field($_POST['_supplier_phone']) : '';
		$product->update_meta_data( '_supplier_phone', $data);
		$product->save();
	}
	
	if ($my_options['option_supplier_email'] == 'on') {
		$data = isset( $_POST['_supplier_email'] ) ? sanitize_text_field($_POST['_supplier_email']) : '';
		$product->update_meta_data( '_supplier_email', $data);
		$product->save();
	}
	
	if ($my_options['option_supplier_notes'] == 'on') {
		$data = isset( $_POST['_supplier_notes'] ) ? sanitize_text_field($_POST['_supplier_notes']) : '';
		$product->update_meta_data( '_supplier_notes', $data);
		$product->save();
	}
	
	if ($my_options['option_supplier_location'] == 'on') {
		$data = isset( $_POST['_supplier_location'] ) ? sanitize_text_field($_POST['_supplier_location']) : '';
		$product->update_meta_data( '_supplier_location', $data);
		$product->save();
	}
	
}
add_action( 'woocommerce_process_product_meta', 'jdssdf_save_custom_supplier_fields' );


/* ****************************************************************************** */
function jdssdf_show_plugin_options_extra() {
	//echo "Blank Test Plugin extra options page";
}


/* ****************************************************************************** */
function jdssdf_show_plugin_options() {

	global $jdssdf_plugin_admin_name;
	global $jdssdf_plugin_admin_link;
	global $jdssdf_plugin_admin_donate_url;
	global $jdssdf_plugin_admin_review_url;

	$donate_url = $jdssdf_plugin_admin_donate_url;
	$review_url = $jdssdf_plugin_admin_review_url;

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); //Required for Front End users but not for Admin area
	/* if (is_plugin_inactive("woocommerce/woocommerce.php")) { //check if specific plugin is active

		?>
		<div class="wrap">
			<h1><?php _e( $jdssdf_plugin_admin_name ); ?></h1>
			<h3><i style='color:red;'>NOTICE: WooCommerce must be installed and activated to use this plugin.</i></h3>
		</div>
		<?php

	} else { */

	?>
	<div class="wrap">
		<h1><?php _e( $jdssdf_plugin_admin_name ); ?></h1>
	</div>
	<?php

	//CHECK IF OPTIONS WERE EVER SET OR NOT
	$update_options_firstrun = false;
	$my_options = get_option('jdssdf_options');

	$this_option = "option_supplier_name";
	if ( !is_array($my_options) || !array_key_exists($this_option, $my_options) ) {
		$my_options[$this_option] = "off"; //Set default CHECKBOX to either on or off
		$update_options_firstrun = true;
	}

	$this_option = "option_supplier_address";
	if ( !is_array($my_options) || !array_key_exists($this_option, $my_options) ) {
		$my_options[$this_option] = "off"; //Set default CHECKBOX to either on or off
		$update_options_firstrun = true;
	}
	
	$this_option = "option_supplier_phone";
	if ( !is_array($my_options) || !array_key_exists($this_option, $my_options) ) {
		$my_options[$this_option] = "off"; //Set default CHECKBOX to either on or off
		$update_options_firstrun = true;
	}
	
	$this_option = "option_supplier_email";
	if ( !is_array($my_options) || !array_key_exists($this_option, $my_options) ) {
		$my_options[$this_option] = "off"; //Set default CHECKBOX to either on or off
		$update_options_firstrun = true;
	}
	
	$this_option = "option_supplier_cost";
	if ( !is_array($my_options) || !array_key_exists($this_option, $my_options) ) {
		$my_options[$this_option] = "off"; //Set default CHECKBOX to either on or off
		$update_options_firstrun = true;
	}
	
	$this_option = "option_supplier_min_price";
	if ( !is_array($my_options) || !array_key_exists($this_option, $my_options) ) {
		$my_options[$this_option] = "off"; //Set default CHECKBOX to either on or off
		$update_options_firstrun = true;
	}
	
	$this_option = "option_supplier_barcode";
	if ( !is_array($my_options) || !array_key_exists($this_option, $my_options) ) {
		$my_options[$this_option] = "off"; //Set default CHECKBOX to either on or off
		$update_options_firstrun = true;
	}
	
	$this_option = "option_supplier_sku";
	if ( !is_array($my_options) || !array_key_exists($this_option, $my_options) ) {
		$my_options[$this_option] = "off"; //Set default CHECKBOX to either on or off
		$update_options_firstrun = true;
	}
	
	$this_option = "option_supplier_description";
	if ( !is_array($my_options) || !array_key_exists($this_option, $my_options) ) {
		$my_options[$this_option] = "off"; //Set default CHECKBOX to either on or off
		$update_options_firstrun = true;
	}
	
	$this_option = "option_supplier_notes";
	if ( !is_array($my_options) || !array_key_exists($this_option, $my_options) ) {
		$my_options[$this_option] = "off"; //Set default CHECKBOX to either on or off
		$update_options_firstrun = true;
	}
	
	$this_option = "option_supplier_location";
	if ( !is_array($my_options) || !array_key_exists($this_option, $my_options) ) {
		$my_options[$this_option] = "off"; //Set default CHECKBOX to either on or off
		$update_options_firstrun = true;
	}

	//Create the options on firstrun if they did not exist
	if ($update_options_firstrun) {
		update_option( 'jdssdf_options', $my_options, true );
		$my_options = get_option('jdssdf_options'); //Make sure we have most up to date copy of options
	}

	//SAVE NEW SETTINGS
	$settings_saved = false;
	if ( isset( $_POST[ 'buttonSaveChanges' ] ) && current_user_can('activate_plugins') && check_admin_referer('save-changes-to-form') ) { //Check if nonce is valid using check_admin_referer or wp_verify_nonce

		$this_option = "option_supplier_name";
		$post_data[$this_option] = sanitize_text_field($_POST[$this_option]);

		$this_option = "option_supplier_address";
		$post_data[$this_option] = sanitize_text_field($_POST[$this_option]);
		
		$this_option = "option_supplier_phone";
		$post_data[$this_option] = sanitize_text_field($_POST[$this_option]);
		
		$this_option = "option_supplier_email";
		$post_data[$this_option] = sanitize_text_field($_POST[$this_option]);
		
		$this_option = "option_supplier_cost";
		$post_data[$this_option] = sanitize_text_field($_POST[$this_option]);
		
		$this_option = "option_supplier_min_price";
		$post_data[$this_option] = sanitize_text_field($_POST[$this_option]);
		
		$this_option = "option_supplier_barcode";
		$post_data[$this_option] = sanitize_text_field($_POST[$this_option]);
		
		$this_option = "option_supplier_sku";
		$post_data[$this_option] = sanitize_text_field($_POST[$this_option]);
		
		$this_option = "option_supplier_description";
		$post_data[$this_option] = sanitize_text_field($_POST[$this_option]);
		
		$this_option = "option_supplier_notes";
		$post_data[$this_option] = sanitize_text_field($_POST[$this_option]);
		
		$this_option = "option_supplier_location";
		$post_data[$this_option] = sanitize_text_field($_POST[$this_option]);
		
		update_option( 'jdssdf_options', $post_data, true );
		$my_options = get_option('jdssdf_options'); //Make sure we have most up to date copy of options

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
			<span style="font-size:120%;">Check to show/enable the display of each field on the Product Data page.</span>
		</p>
		
		<p>
			<?php
			$this_option_name = "option_supplier_cost";
			$checked = "";
			if ($my_options[$this_option_name] == "on") {
				$checked = "CHECKED";
			}
			?>
			<input type="hidden" name="<?php echo $this_option_name; ?>" value="off" >
			<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?> value="on" >
			<label for="<?php echo $this_option_name; ?>"><?php _e( 'Supplier Cost field' ) ?></label>
			<br><span style="font-size: 85%; font-style: italic;">NOTE: Supplier Cost.</span>
		</p>
		
		<p>
			<?php
			$this_option_name = "option_supplier_min_price";
			$checked = "";
			if ($my_options[$this_option_name] == "on") {
				$checked = "CHECKED";
			}
			?>
			<input type="hidden" name="<?php echo $this_option_name; ?>" value="off" >
			<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?> value="on" >
			<label for="<?php echo $this_option_name; ?>"><?php _e( 'Supplier Minimum Price field' ) ?></label>
			<br><span style="font-size: 85%; font-style: italic;">NOTE: Supplier Minimum Price.</span>
		</p>
		
		<p>
			<?php
			$this_option_name = "option_supplier_barcode";
			$checked = "";
			if ($my_options[$this_option_name] == "on") {
				$checked = "CHECKED";
			}
			?>
			<input type="hidden" name="<?php echo $this_option_name; ?>" value="off" >
			<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?> value="on" >
			<label for="<?php echo $this_option_name; ?>"><?php _e( 'Supplier Barcode field' ) ?></label>
			<br><span style="font-size: 85%; font-style: italic;">NOTE: Supplier Barcode.</span>
		</p>
		
		<p>
			<?php
			$this_option_name = "option_supplier_sku";
			$checked = "";
			if ($my_options[$this_option_name] == "on") {
				$checked = "CHECKED";
			}
			?>
			<input type="hidden" name="<?php echo $this_option_name; ?>" value="off" >
			<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?> value="on" >
			<label for="<?php echo $this_option_name; ?>"><?php _e( 'Supplier SKU field' ) ?></label>
			<br><span style="font-size: 85%; font-style: italic;">NOTE: Supplier SKU.</span>
		</p>
		
		<p>
			<?php
			$this_option_name = "option_supplier_description";
			$checked = "";
			if ($my_options[$this_option_name] == "on") {
				$checked = "CHECKED";
			}
			?>
			<input type="hidden" name="<?php echo $this_option_name; ?>" value="off" >
			<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?> value="on" >
			<label for="<?php echo $this_option_name; ?>"><?php _e( 'Supplier Description field' ) ?></label>
			<br><span style="font-size: 85%; font-style: italic;">NOTE: Supplier Description.</span>
		</p>
		
		<p>
			<?php
			$this_option_name = "option_supplier_name";
			$checked = "";
			if ($my_options[$this_option_name] == "on") {
				$checked = "CHECKED";
			}
			?>
			<input type="hidden" name="<?php echo $this_option_name; ?>" value="off" >
			<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?> value="on" >
			<label for="<?php echo $this_option_name; ?>"><?php _e( 'Supplier Name field' ) ?></label>
			<br><span style="font-size: 85%; font-style: italic;">NOTE: Supplier Name.</span>
		</p>
		
		<p>
			<?php
			$this_option_name = "option_supplier_address";
			$checked = "";
			if ($my_options[$this_option_name] == "on") {
				$checked = "CHECKED";
			}
			?>
			<input type="hidden" name="<?php echo $this_option_name; ?>" value="off" >
			<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?> value="on" >
			<label for="<?php echo $this_option_name; ?>"><?php _e( 'Supplier Address field' ) ?></label>
			<br><span style="font-size: 85%; font-style: italic;">NOTE: Supplier Address.</span>
		</p>
		
		<p>
			<?php
			$this_option_name = "option_supplier_phone";
			$checked = "";
			if ($my_options[$this_option_name] == "on") {
				$checked = "CHECKED";
			}
			?>
			<input type="hidden" name="<?php echo $this_option_name; ?>" value="off" >
			<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?> value="on" >
			<label for="<?php echo $this_option_name; ?>"><?php _e( 'Supplier Phone field' ) ?></label>
			<br><span style="font-size: 85%; font-style: italic;">NOTE: Supplier Phone.</span>
		</p>
		
		<p>
			<?php
			$this_option_name = "option_supplier_email";
			$checked = "";
			if ($my_options[$this_option_name] == "on") {
				$checked = "CHECKED";
			}
			?>
			<input type="hidden" name="<?php echo $this_option_name; ?>" value="off" >
			<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?> value="on" >
			<label for="<?php echo $this_option_name; ?>"><?php _e( 'Supplier Email field' ) ?></label>
			<br><span style="font-size: 85%; font-style: italic;">NOTE: Supplier Email.</span>
		</p>
		
		<p>
			<?php
			$this_option_name = "option_supplier_notes";
			$checked = "";
			if ($my_options[$this_option_name] == "on") {
				$checked = "CHECKED";
			}
			?>
			<input type="hidden" name="<?php echo $this_option_name; ?>" value="off" >
			<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?> value="on" >
			<label for="<?php echo $this_option_name; ?>"><?php _e( 'Supplier Notes field' ) ?></label>
			<br><span style="font-size: 85%; font-style: italic;">NOTE: Supplier Notes.</span>
		</p>
		
		<p>
			<?php
			$this_option_name = "option_supplier_location";
			$checked = "";
			if ($my_options[$this_option_name] == "on") {
				$checked = "CHECKED";
			}
			?>
			<input type="hidden" name="<?php echo $this_option_name; ?>" value="off" >
			<input type="checkbox" id="<?php echo $this_option_name; ?>" name="<?php echo $this_option_name; ?>" <?php echo $checked; ?> value="on" >
			<label for="<?php echo $this_option_name; ?>"><?php _e( 'Supplier Location field' ) ?></label>
			<br><span style="font-size: 85%; font-style: italic;">NOTE: Supplier Notes.</span>
		</p>





<!-- ------------------------------------------------------------------------- -->
		<?php $pluginbut = plugins_url( 'dbut.png', __FILE__ ); $review_stars = plugins_url( 'stars-small.png', __FILE__ ); ?>
		<br><hr>How much is this plugin worth to you? A suggested <a href="<?php echo $donate_url; ?>">donation of $10</a> will help motivate me to keep this plugin updated!<br><a href="<?php echo $donate_url; ?>"><img width="175" src="<?php echo $pluginbut; ?>"></a>
		<br>Reviews are also very hepful. Please consider leaving a <a href="<?php echo $review_url; ?>">postive REVIEW by cllicking here <img style="vertical-align:text-top;" height="12" src="<?php echo $review_stars; ?>"></a><hr>

		<?php
			wp_nonce_field('save-changes-to-form'); //Add nonce hidden fields
		?>
		<p class="submit">
			<input class="button-primary buttonSaveChanges" id="buttonSaveChanges" name="buttonSaveChanges" type="submit" value="<?php _e( 'Save Changes' ) ?>" />
		</p>

	</form>

		<?php
    /* } //check if specific plugin is active*/

}
?>