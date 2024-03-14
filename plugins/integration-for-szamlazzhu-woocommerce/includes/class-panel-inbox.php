<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Automattic\Jetpack\Constants;
use \Automattic\WooCommerce\Admin\Notes\WC_Admin_Note;
use \Automattic\WooCommerce\Admin\Notes\WC_Admin_Notes;

class WC_Szamlazz_Admin_Panel_Inbox {

	//Constructor
	public function __construct() {

		// Not using Jetpack\Constants here as it can run before 'plugin_loaded' is done.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX
			|| defined( 'DOING_CRON' ) && DOING_CRON
			|| ! is_admin() ) {
			return;
		}

		//Do this daily
		add_action( 'wc_admin_daily', array( $this, 'do_wc_admin_daily' ) );

	}

	public static function do_wc_admin_daily() {
		self::maybe_show_rating_note();
		self::maybe_show_pro_upgrade_note();
	}

	//Create a single note
	public static function create_note($id, $args = array(), $data = array()) {

		//Init data store
		$data_store = WC_Data_Store::load( 'admin-note' );

		//Setup default parameters
		$args = wp_parse_args($args,
			array(
				'duplicate' => true,
				'type' => 'marketing',
				'title' => '',
				'content' => '',
				'actions' => array(),
				'layout' => 'plain',
				'image' => ''
			)
		);

		//See if we've already created this kind of note so we don't do it again if duplicates are disabled
		if(!$args['duplicate']) {
			$note_ids = $data_store->get_notes_with_name( 'wc-szamlazz-'.$id );
			if ( ! empty( $note_ids ) ) {
				return;
			}
		}

		//Create the note
		$note = new WC_Admin_Note();

		//Set note type
		if(defined('WC_Admin_Note::E_WC_ADMIN_NOTE_MARKETING')) {
			$note->set_type( WC_Admin_Note::E_WC_ADMIN_NOTE_MARKETING );
			$note->set_layout( $args['layout'] );
		} else {
			$note->set_type( WC_Admin_Note::E_WC_ADMIN_NOTE_INFORMATIONAL );
		}
		if($args['type'] == 'error') $note->set_type( WC_Admin_Note::E_WC_ADMIN_NOTE_ERROR );
		if($args['type'] == 'warning') $note->set_type( WC_Admin_Note::E_WC_ADMIN_NOTE_WARNING );
		if($args['type'] == 'update') $note->set_type( WC_Admin_Note::E_WC_ADMIN_NOTE_UPDATE );

		//Set default stuff that applies to all notes
		$note->set_content_data( (object) $data );
		$note->set_source( 'wc-szamlazz' );
		$note->set_name( 'wc-szamlazz-'.$id );

		//Set custom stuff based on attributes
		$note->set_title( $args['title'] );
		$note->set_content( $args['content'] );

		//Set image if exists
		if($args['image']) {
			$note->set_image( $args['image'] );
		}

		//Set actions
		foreach ($args['actions'] as $action_id => $action) {
			$note->add_action('wc-szamlazz-'.$id.'-action-'.$action_id, $action[0], $action[1], $action[2], $action[3]);
		}

		//Save to create the note
		$note->save();

	}

	//Removes a single note this plugin created
	public static function remove_note($id) {
		WC_Admin_Notes::delete_notes_with_name( 'wc-szamlazz-'.$id );
	}

	//Removes all notes this plugin created.
	public static function remove_notes() {
		$available_notes = ['welcome', 'rating', 'rating', 'invoice_error'];
		foreach ($available_notes as $note_id) {
			WC_Admin_Notes::delete_notes_with_name( 'wc-szamlazz-'.$note_id );
		}
	}

	//Helper function to check how long WC szamlazz has been active for
	public static function wc_szamlazz_active_for( $seconds ) {
		$wc_szamlazz_installed = get_option( 'wc_szamlazz_install_timestamp', false );
		if ( false === $wc_szamlazz_installed ) {
			update_option( 'wc_szamlazz_install_timestamp', time() );
			return false;
		}
		return ( ( time() - $wc_szamlazz_installed ) >= $seconds );
	}

	public static function create_welcome_note() {
		self::create_note('welcome', array(
			'type' => 'update',
			'title' => __('WooCommerce + Szamlazz.hu', 'wc-szamlazz'),
			'content' => __( "Thank you for using this extension. To get started, go to the settings page and enter your invoicing details. If you are not aware of it, there's a PRO version of this extension, which offers a lot more features compared to the free version, for example, automatic invoice and receipt generation, bulk actions, and a lot more.", 'wc-szamlazz' ),
			'actions' => array(
				array(__( 'Upgrade to PRO', 'wc-szamlazz' ), 'https://visztpeter.me/woocommerce-szamlazz-hu/', 'actioned', true),
				array(__( 'Settings', 'wc-szamlazz' ), '?page=wc-settings&tab=integration&section=wc_szamlazz&welcome=1', 'actioned', false),
				array(__( 'Hide', 'wc-szamlazz' ), '', 'actioned', false)
			)
		));
	}

	public static function maybe_show_rating_note() {

		// We want to show this note after day 5.
		$days_in_seconds = 5 * DAY_IN_SECONDS;
		if ( ! self::wc_szamlazz_active_for( $days_in_seconds ) ) {
			return;
		}

		self::create_note('rating', array(
			'duplicate' => false,
			'title' => __('WooCommerce + Szamlazz.hu rating', 'wc-szamlazz'),
			'content' => '<p>⭐️ ⭐️ ⭐️ ⭐️ ⭐️</p>'.__( 'Enjoyed <strong>WooCommerce Számlázz.hu</strong>? Please leave us a ★★★★★ rating. We appreciate your support!', 'wc-szamlazz' ),
			'actions' => array(
				array(__( 'Leave a review', 'wc-szamlazz' ), 'https://wordpress.org/support/plugin/integration-for-szamlazzhu-woocommerce/reviews/?filter=5#new-post', 'actioned', true),
			)
		));

	}

	public static function maybe_show_pro_upgrade_note() {

		// We want to show this note after day 30.
		$days_in_seconds = 30 * DAY_IN_SECONDS;
		if ( ! self::wc_szamlazz_active_for( $days_in_seconds ) ) {
			return;
		}

		// Only if the PRO version is not active yet
		if(WC_Szamlazz_Pro::is_pro_enabled()) {
			return;
		}

		self::create_note('pro_upgrade', array(
			'duplicate' => false,
			'title' => __('WooCommerce + Szamlazz.hu PRO version', 'wc-szamlazz'),
			'content' => __( "Hi, looks like you using <b>WooCommerce Szamlazz.hu</b> for some time and I hope this software helped you with your business. This is just a reminder, that there's a PRO version of this extension that offers more features :)", 'wc-szamlazz' ),
			'actions' => array(
				array(__( 'More info', 'wc-szamlazz' ), 'https://visztpeter.me/woocommerce-szamlazz-hu/', 'actioned', true),
			)
		));

	}

	public static function create_error_note($order_id) {
		$order = wc_get_order($order_id);
		if(!$order) return;

		$order_number = $order->get_order_number();
		$order_link = $order->get_edit_order_url();

		self::create_note('invoice_error', array(
			'duplicate' => true,
			'type' => 'error',
			'title' => __('Invoice generation error', 'wc-szamlazz'),
			'content' => sprintf( esc_html__( 'For some reason, the extension was unable to generate an invoice for this order: #%s. You can see more details in the order notes.', 'wc-szamlazz' ), esc_html($order_number) ),
			'actions' => array(
				array(__( 'Order details', 'wc-szamlazz' ), $order_link, 'actioned', true),
				array(__( 'Close', 'wc-szamlazz' ), '', 'actioned', false),
			)
		));

	}

}
