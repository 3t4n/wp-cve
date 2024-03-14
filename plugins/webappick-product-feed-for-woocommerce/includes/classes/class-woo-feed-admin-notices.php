<?php
/**
 * A class definition responsible for processing and mapping product according to feed rules and make the feed
 *
 * @link       https://webappick.com/
 * @since      1.0.0
 *
 * @package    Woo_Feed
 * @subpackage Woo_Feed/includes
 * @author     Nashir Uddin <nasir.webappick@gmail.com>
 */

use CTXFeed\V5\Notice\Notices;

class Woo_Feed_Notices {

	/**
	 * @var Woo_Feed_Notices
	 */
	protected static $instance;


	/**
	 * Holds Notices Message
	 *
	 * @var array
	 */
	protected static $notices_message = array();

	public static $attributesTypeArray = array( "woo_feed_attributes", "woo_feed_category_mapping","woo_feed_dynamic_attributes", "woo_feed_attribute_mapping", "woo_feed_wp_options" );

	/**
	 * Holds Notices
	 *
	 * @var array
	 */
	private $notices = array();

	/**
	 * The prefix we'll be using for the option/user-meta.
	 *
	 * @access public static
	 * @var string
	 */
	public static $prefix = 'wf_dismissed';

	/**
	 * Get Woo_Feed_Notices Singleton Instance
	 *
	 * @return Woo_Feed_Notices
	 */
	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Woo_Feed_Notices constructor.
	 * Initialize default messages and notices
	 *
	 * @return void
	 */
	private function __construct() {
		self::set_notice_messages();
		$this->woo_feed_upload_directory_check();
		$this->set_woo_feed_notices();

	}

	private static function set_notice_messages() {
		self::$notices_message = [
			'woo_feed_attributes'       => array(
				'title'   => false,
				'message' => '<div class="ctx-notification-clear"><span class="dashicons dashicons-bell"></span> <strong>You have updated product attributes. Please clear the CTX Feed cache to get the attribute on the new feed.</strong><div class="loadingio-spinner-reload-5t7io14g51q"><div class="ldio-phfj8q2ogom">
                        <div><div></div><div></div><div></div></div>
                        </div></div><span data-id="'.wp_create_nonce('clean_cache_nonce').'" id="woo_feed_attributes" class="ctx-notice-cache-clear">Clear Cache</span></div>',
			),
			'woo_feed_product_count'    => array(
				'title'   => false,
				'message' => '<span class="dashicons dashicons-bell"></span> <strong>You have published WooCommerce products more than 50K.</strong>',
			),
			'include_hidden_products_from_feed'    => array(
				'title'   => false,
				'message' => '<span class="dashicons dashicons-bell"></span>Your WooCommerce store contains hidden products. To include the hidden products to your feed, <strong> go to the filter tab and Select Yes to include hidden products.</strong>',
			),
			'enable_multi_currency'     => array(
				'title'   => '<strong>Currency</strong>',
				'message' => '<strong>You have updated Woocommerce WPML currency set to Geolocation.</strong>',
			),
			'base_conversion_rate'      => array(
				'title'   => '<strong>Conversion</strong>',
				'message' => '<strong>The base conversion rate is not set for each currency.</strong>',
			),
			'upload_dicrotory_writable' => array(
				'title'   => '<strong>Writable</strong>',
				'message' => '<strong>Please allow to write permission of wordpress upload directory .</strong>',
			),
			'woo_feed_category_mapping'       => array(
				'title'   => false,
				'message' => '<div class="ctx-notification-clear"><span class="dashicons dashicons-bell"></span> You have added a new &nbsp; <strong> Category Mapping </strong>. Please clear the &nbsp; <strong> CTX Feed </strong> &nbsp; cache to get the &nbsp; <strong> Category Mapping</strong> &nbsp; on the new feed.<div class="loadingio-spinner-reload-5t7io14g51q"><div class="ldio-phfj8q2ogom">
                        <div><div></div><div></div><div></div></div>
                        </div></div><span data-id="'.wp_create_nonce('clean_cache_nonce').'" id="woo_feed_category_mapping" class="ctx-notice-cache-clear">Clear Cache</span></div>',
			),
			'woo_feed_dynamic_attributes'       => array(
				'title'   => false,
				'message' => '<div class="ctx-notification-clear"><span class="dashicons dashicons-bell"></span> You have added a new &nbsp; <strong> Dynamic Attribute</strong>. Please clear the &nbsp; <strong>CTX Feed </strong> &nbsp; cache to get the &nbsp; <strong>Dynamic Attribute</strong> &nbsp; on the new feed.<div class="loadingio-spinner-reload-5t7io14g51q"><div class="ldio-phfj8q2ogom">
                        <div><div></div><div></div><div></div></div>
                        </div></div><span data-id="'.wp_create_nonce('clean_cache_nonce').'" id="woo_feed_dynamic_attributes" class="ctx-notice-cache-clear">Clear Cache</span></div>',
			),
			'woo_feed_attribute_mapping'       => array(
				'title'   => false,
				'message' => '<div  class="ctx-notification-clear"><span class="dashicons dashicons-bell"></span> You have added a new &nbsp; <strong>Attribute Mapping</strong>. Please clear the &nbsp; <strong>CTX Feed</strong> &nbsp; cache to get the &nbsp; <strong>Attribute Mapping</strong> &nbsp; on the new feed.<div class="loadingio-spinner-reload-5t7io14g51q"><div class="ldio-phfj8q2ogom">
                        <div><div></div><div></div><div></div></div>
                        </div></div><span data-id="'.wp_create_nonce('clean_cache_nonce').'" id="woo_feed_attribute_mapping" class="ctx-notice-cache-clear">Clear Cache</span></div>',
			),
			'woo_feed_wp_options'       => array(
				'title'   => false,
				'message' => '<div class="ctx-notification-clear"><span class="dashicons dashicons-bell"></span> You have added a new &nbsp;<strong>WP options</strong>. Please clear the &nbsp; <strong>CTX Feed</strong> &nbsp; cache to get the options on the new feed.</strong><div class="loadingio-spinner-reload-5t7io14g51q"><div class="ldio-phfj8q2ogom">
                        <div><div></div><div></div><div></div></div>
                        </div></div><span data-id="'.wp_create_nonce('clean_cache_nonce').'" id="woo_feed_wp_options" class="ctx-notice-cache-clear">Clear Cache</span></div>',
			),
		];
	}


	public function woo_feed_upload_directory_check() {
		$upload_dir = wp_upload_dir();
		$type       = 'upload_dicrotory_writable';
		if ( ! is_writable( dirname( $upload_dir['basedir'] ) ) ) {

			$notice_data = $this->get_woo_feed_notice_data();
			if ( empty( $notice_data ) ) {

				$this->add_woo_feed_notice_data( $type, 1 );

			} else {
				$this->update_woo_feed_notice_data( $type, $notice_data, 1 );
				$this->update_woo_feed_notice_dismiss( $type, false );
			}
		} else {
			self::update_woo_feed_notice_dismiss( $type, true );
		}
	}

	/**
	 * Declare the notices array with title and message.
	 *
	 * @access public
	 * @return void
	 */

	public function get_woo_feed_notices_message( $key, $value ) {
		if ( $value === 'title' ) {
			return self::$notices_message[ $key ]['title'];
		} else {
			return self::$notices_message[ $key ]['message'];
		}
	}

	/**
	 * Get the notices array from database.
	 *
	 * @access public
	 * @return array
	 */

	public static function get_woo_feed_notice_data() {
		return get_option( 'woo_feed_notices' );
	}

	/**
	 * Add the notices array into database.
	 *
	 * @access public
	 * @return void
	 */

	public static function add_woo_feed_notice_data( $type, $value ) {
		$notice_data[ $type ] = $value;
		add_option( 'woo_feed_notices', $notice_data, '', 'no' );
	}

	/**
	 * Update the notices array .
	 *
	 * @access public
	 * @return void
	 */

	public static function update_woo_feed_notice_data( $type, $notice_data, $value ) {

		if ( ! array_key_exists( $type, $notice_data ) ) {
			$new_data[ $type ] = $value;
			$notice_all_data   = array_merge( $notice_data, $new_data );
			update_option( 'woo_feed_notices', $notice_all_data, '', 'no' );

		}
	}

	/**
	 * Update  dismiss status into database.
	 *
	 * @access public
	 * @return void
	 */

	public static function update_woo_feed_notice_dismiss( $type, $dismiss ) {
		update_option( self::$prefix . '_' . $type, $dismiss, false );
	}

	/**
	 * Set the notices data .
	 *
	 * @access private
	 * @return void
	 */

	private function set_woo_feed_notices() {


		if ( isset( $_GET['page'] ) && preg_match( '/^webappick\W+/', $_GET['page'] ) && ($_GET['page'] !="webappick-manage-feeds" && $_GET['page'] !="webappick-new-feed") ) { // After React UI finish it will be applied
//		if ( isset( $_GET['page'] ) && preg_match( '/^webappick\W+/', $_GET['page'] ) && ($_GET['page'] !="webappick-new-feed") ) {
//			array_shift(self::$notices_message);
                unset(self::$notices_message["woo_feed_attributes"]);
                unset(self::$notices_message["woo_feed_category_mapping"]);
                unset(self::$notices_message["woo_feed_dynamic_attributes"]);
                unset(self::$notices_message["woo_feed_attribute_mapping"]);
                unset(self::$notices_message["woo_feed_wp_options"]);
//			    unset(self::$notices_message["include_hidden_products_from_feed"]);
		}

		if ( isset( $_GET['page'] )  &&  $_GET['page'] !== "webappick-new-feed" ) {
			// TODO: This condition should be improved
			if( $_GET['page'] === "webappick-manage-feeds"  && (   isset( $_GET['action'] )  && $_GET['action']  === 'edit-feed' ) ){
				$need_block = false;
			}else{
				unset(self::$notices_message["include_hidden_products_from_feed"]);
			}
		}


		$wf_v5_notices = new Notices();
		$this->notices = get_option( 'woo_feed_notices' );

//		$options = array(
//			'option_prefix' => self::$prefix,   // Change the user-meta prefix.
//		);
		if ( is_array( $this->notices ) ) {
			foreach ( $this->notices as $key => $data ) {
				if ( array_key_exists( $key, self::$notices_message ) ) {
					if( $key == 'include_hidden_products_from_feed'){
						$options = array(
							'option_prefix' => self::$prefix,
							'scope' => 'user'
						);
					} else {
						$options = array(
							'option_prefix' => self::$prefix,   // Change the user-meta prefix.
						);
					}

					$wf_v5_notices->add( $key, __( $this->get_woo_feed_notices_message( $key, 'title' ), 'woo_feed' ), __( $this->get_woo_feed_notices_message( $key, 'message' ), 'woo_feed' ), $options );

					if ( isset( $_GET['page'] ) && preg_match( '/^webappick\W+/', $_GET['page'] ) ) {
						$wf_v5_notices->boot();
					}

				}
			}
		}

	}
	/**
	 * Add or update the notices data to databse.
	 *
	 * @access public
	 * @return void
	 */
	public static function add_update_woo_feed_notice_data( $type, $notice_data ) {
		if ( empty( $notice_data ) ) {
			self::add_woo_feed_notice_data( $type, 1 );
		} else {
			self::update_woo_feed_notice_data( $type, $notice_data, 1 );
			self::update_woo_feed_notice_dismiss( $type, false );
		}
	}

	public static function woo_feed_remove_previous_generated_relevant_notice( $type, $notificationtypes ){

		if ( ( $key = array_search( $type, $notificationtypes ) ) !== false) {
			unset( $notificationtypes[$key] );
		}
		$types = $notificationtypes;
		foreach ( $types as $arraytype ){
			self::update_woo_feed_notice_dismiss( $arraytype, true );
		}

	}

    public static function woo_feed_saved_category_mapping_notice_data() {

        $notice_data = self::get_woo_feed_notice_data();
        $type        = 'woo_feed_category_mapping';
        self::add_update_woo_feed_notice_data( $type, $notice_data );
		self::woo_feed_remove_previous_generated_relevant_notice( $type, self::$attributesTypeArray );
    }
    public static function woo_feed_saved_dynamic_attributes_notice_data() {

        $notice_data = self::get_woo_feed_notice_data();
        $type        = 'woo_feed_dynamic_attributes';
        self::add_update_woo_feed_notice_data( $type, $notice_data );
		self::woo_feed_remove_previous_generated_relevant_notice( $type, self::$attributesTypeArray );
    }

    public static function woo_feed_saved_attribute_mapping_notice_data() {

        $notice_data = self::get_woo_feed_notice_data();
        $type        = 'woo_feed_attribute_mapping';
        self::add_update_woo_feed_notice_data( $type, $notice_data );
		self::woo_feed_remove_previous_generated_relevant_notice( $type, self::$attributesTypeArray );
    }

    public static function woo_feed_newly_added_wp_options_notice_data() {

        $notice_data = self::get_woo_feed_notice_data();
        $type        = 'woo_feed_wp_options';
        self::add_update_woo_feed_notice_data( $type, $notice_data );
		self::woo_feed_remove_previous_generated_relevant_notice( $type, self::$attributesTypeArray );
    }

}
