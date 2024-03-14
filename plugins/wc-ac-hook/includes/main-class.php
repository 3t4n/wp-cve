<?php
namespace mtreherne\WC_AC_Hook;

use WC_Order;
use WC_Logger;

if (!defined('ABSPATH')) exit();

if (!class_exists(__NAMESPACE__ . '\WC_AC_Hook') && ( in_array( 'woocommerce/woocommerce.php', apply_filters('active_plugins', (array) get_option( 'active_plugins' ) ) ) || array_key_exists ( 'woocommerce/woocommerce.php', (array) get_site_option( 'active_sitewide_plugins' ) ) ) ) :

class WC_AC_Hook {

	const OPTION_NAME = 'woocommerce_wc-ac-hook_settings';
	private $basename;

	public function __construct() {
		$this->basename = $GLOBALS['WC_AC_Hook_basename'];
		if (is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) ) {
			// Add settings fields for this plugin to the WooCommerce Settings Integration tab
			add_action( 'plugins_loaded', array( $this, 'init_integration' ) );
			// Add the settings link to the plugins page
			add_filter( 'plugin_action_links_'.$this->basename, array( $this, 'settings_link' ) );
			// Add custom 'tag' field to the Advanced Product data section of WooCommerce
			add_action( 'woocommerce_product_options_advanced', array( $this, 'product_advanced_field' ) );
			add_action( 'woocommerce_process_product_meta', array( $this, 'custom_product_fields_save' ) ); 
		}
		// Call the ActiveCampaign API whenever an order status is changed
		add_action('woocommerce_order_status_changed', array( $this, 'order_status_change' ), 10, 3);
		add_action('woocommerce_checkout_update_order_meta', array( $this, 'order_created' ), 10, 1);
		add_action('init', array( $this, 'plugin_load_textdomain' ) );
		add_action('woocommerce_after_order_notes', array( $this, 'marketing_checkout_field' ) );
	}

	public function plugin_load_textdomain() {
		load_plugin_textdomain( 'wc-ac-hook', false, dirname($this->basename) . '/languages' );
	}

	public function init_integration() {
		if ( class_exists( 'WC_Integration' ) )
			add_filter( 'woocommerce_integrations', array( $this, 'add_integration' ) );
	}

	public function add_integration( $integrations ) {
		include_once 'settings.php';
		$integrations[] = __NAMESPACE__ .'\WC_AC_Hook_Integration';
		return $integrations;
	}
	
	public function settings_link($links) {
		array_unshift($links, '<a href="admin.php?page=wc-settings&tab=integration&section=wc-ac-hook">Settings</a>');
		return $links;
	}
	
	public function product_advanced_field() {
		// Could modify to check only for simple product
		echo '<div class="options_group">';
		woocommerce_wp_text_input(array(
			'id' 			=> 'activecampaign_tag',
			'label' 		=> __( 'ActiveCampaign Tag', 'wc-ac-hook' ),
			'desc_tip' 		=> 'true',
			'description' 	=> __( 'Contact will be given this tag on ActiveCampaign when ordered', 'wc-ac-hook' )));
		echo '</div>';
	}

	public function custom_product_fields_save( $post_id ){
		$woocommerce_text_field = $_POST['activecampaign_tag'];
		if( isset( $woocommerce_text_field ) )
			update_post_meta( $post_id, 'activecampaign_tag', sanitize_text_field( $woocommerce_text_field ) );
	}

	// This function is called whenever a WooCommerce order is created at checkout
	public function order_created ($order_id) {
		$order = wc_get_order( $order_id );
		if (isset($_POST['wc_ac_marketing_checkbox'])) {
			$order->update_meta_data( 'wc_ac_marketing_checkbox', sanitize_text_field ($_POST['wc_ac_marketing_checkbox']) );
			$order->save();
		}
		$this->order_status_change ($order_id, null, method_exists($order,'get_status') ? $order->get_status() : $order->status);
	}
	
	// This function is called whenever a WooCommerce order status is changed
	public function order_status_change ($order_id, $old_status, $new_status) {
		$log_message = array();
		// Get the plugin settings
		$options = get_option( self::OPTION_NAME, null );
		$logging_enabled = $options['wc_ac_notification'] ?? null;
		$add_on_processing = $options['wc_ac_addonprocessing'] ?? null;
		$order_tracking = $options['wc_ac_ordertracking'] ?? null;
		$valid_status = array('pending', 'failed','processing','on-hold','cancelled','completed');
		// Only signup when new order created at checkout
		$form_signup = isset($_POST['wc_ac_marketing_checkbox']) && !$old_status;
		$sync_contact = false;
		if (($new_status == 'completed' && $add_on_processing != 'yes') || ($new_status == 'processing' && $add_on_processing == 'yes') || (in_array($new_status,$valid_status) && $order_tracking == 'yes')) $sync_contact = true;
		if (!$form_signup && !$sync_contact) return;
	
		// Get order deails and validate
		$valid_order = true;
		$order = new WC_Order( $order_id );
		$order_billing_first_name = method_exists($order,'get_billing_first_name') ? $order->get_billing_first_name() : $order->billing_first_name;
		$order_billing_last_name = method_exists($order,'get_billing_last_name') ? $order->get_billing_last_name() : $order->billing_last_name;
		$order_billing_email = method_exists($order,'get_billing_email') ? $order->get_billing_email() : $order->billing_email;
		$order_billing_phone = method_exists($order,'get_billing_phone') ? $order->get_billing_phone() : $order->billing_phone;
		// eMail is the key on ActiveCampaign so should be validated
		if (!is_email ($order_billing_email)) {
			$valid_order = false;
			$log_message[] = sprintf( __( 'Error: Invalid customer (billing) email address = %s', 'wc-ac-hook' ), $order_billing_email);
		}
		if ($valid_order) {
			include_once 'sync-contact.php';
			$api = new WC_AC_Hook_Sync($options);
		}
		
		if ($valid_order && $sync_contact) {
			$default_tags = implode(',',array_map('trim', explode(',', $options['ac_default_tag'] ?? null)));
			$tag_array = explode(',',$default_tags);
			$last_default_tag = end($tag_array);
			// Add the product tags for any of the items on the order
			$items = $order->get_items();
			if ($order_tracking == 'yes') {
				$order_tracking_tag = ($new_status != 'completed') ? ' ('.$new_status.')' : null;
				$tags = $last_default_tag ? $default_tags.' ('.$new_status.')' : null;
			}
			else {
				$tags = $default_tags;
				$order_tracking_tag = '';
			}
			foreach ($items as $item) {
				$product_tag = get_post_meta( $item['product_id'], 'activecampaign_tag', true );
				$product_tag = implode(',',array_map('trim', explode(',', $product_tag)));
				$tag_array = explode(',',$product_tag);
				$last_product_tag = end($tag_array);
				if ($product_tag) $tags .= ','.(($last_product_tag) ? $product_tag.$order_tracking_tag : $product_tag);
			}
			// The order details are used to make a call using the ActiveCampaign API to add/update a customer contact
			$contact = array(
				'email' 				=> $order_billing_email,
				'first_name'		=> $order_billing_first_name,
				'last_name' 		=> $order_billing_last_name,
				'tags' 					=> $tags,
				'phone' 				=> $order_billing_phone);
			$api->sync_contact($contact);
			$tags_to_remove = [];
			if ($order_tracking == 'yes') {
				if ($last_default_tag) {
					foreach ($valid_status as $status) {
						if ($status==$new_status) continue;
						$tags_to_remove[] = $last_default_tag.' ('.$status.')';
					}
				}
				foreach ($items as $item) {
					$product_tag = get_post_meta( $item['product_id'], 'activecampaign_tag', true );
					$tag_array = explode(',',$product_tag);
					$last_product_tag = trim(end($tag_array));
					if ($last_product_tag) {
						foreach ($valid_status as $status) {
							if (($status==$new_status) || ($status=='completed')) continue;
							$tags_to_remove[] = $last_product_tag.' ('.$status.')';
						}
					}
				}
				if ($tags_to_remove) {
					$contact_tag_remove = array(
						'email'			=> $order_billing_email,
						'tags' 			=> $tags_to_remove);
					$api->remove_tags($contact_tag_remove);
				}
			}
		}

		if ($valid_order && $form_signup) {
			$contact = array(
				'email' 				=> $order_billing_email,
				'first_name'		=> $order_billing_first_name,
				'last_name' 		=> $order_billing_last_name,
				'phone' 				=> $order_billing_phone);
			$api->form_subscribe($contact);
		}
		
		if ($valid_order) $log_message = $api->log_message;
		if ($logging_enabled != 'no') {
			$log = new WC_Logger();
			$log_string = sprintf( __( 'Order ID = %s (Status = %s).', 'wc-ac-hook' ), $order_id, $new_status);
			foreach ($log_message as $value) $log_string .= ' '.$value;
			$log->add( 'wc-ac-hook', $log_string);
		}
		
	}
	
	public function marketing_checkout_field($checkout) {
		$options = get_option( self::OPTION_NAME, null );
		$marketing_checkout = $options['wc_ac_marketing'] ?? null;
		if ($marketing_checkout!='opt_in' && $marketing_checkout!='opt_out') return;
		woocommerce_form_field( 'wc_ac_marketing_checkbox', array(
			'type'	=> 'checkbox',
			'label'	=> __($options['wc_ac_marketing_label']),
			'default'	=> $marketing_checkout == 'opt_out' ? 1 : null,
		), $checkout->get_value('wc_ac_marketing_checkbox'));
	}

}

new WC_AC_Hook();

endif;
?>