<?php
namespace WC_BPost_Shipping\Adapter;

/**
 * Gateway to WordPress classes and functions
 * Class WC_BPost_Shipping_Adapter_Woocommerce
 * @package WC_BPost_Shipping\Adapter
 */
class WC_BPost_Shipping_Adapter_Woocommerce {
	static $adapter;

	/**
	 * Must use singleton pattern :-(
	 * @return WC_BPost_Shipping_Adapter_Woocommerce
	 */
	public static function get_instance() {
		if ( ! self::$adapter ) {
			self::$adapter = new self();
		}

		return self::$adapter;
	}

	/**
	 * @param string $wc_version
	 *
	 * @return bool
	 */
	public function is_wc_version_equal_or_greater_than( $wc_version ) {
		return defined( 'WC_VERSION' ) && WC_VERSION && version_compare( WC_VERSION, $wc_version, '>=' );
	}

	/**
	 * @return array
	 * @see WC()->countries->get_shipping_countries()
	 */
	public function get_shipping_countries() {
		return WC()->countries->get_shipping_countries();
	}

	/**
	 * @param string $message
	 * @param string $notice_type
	 *
	 * @see wc_add_notice()
	 */
	public function add_notice( $message, $notice_type = 'success' ) {
		wc_add_notice( $message, $notice_type );
	}

	/**
	 * @param string $text
	 *
	 * @see \WC_Admin_Settings::add_error()
	 */
	public function settings_add_error( $text ) {
		\WC_Admin_Settings::add_error( $text );
	}

	/**
	 * @param string $text
	 *
	 * @see \WC_Admin_Settings::add_message
	 */
	public function settings_add_message( $text ) {
		\WC_Admin_Settings::add_message( $text );
	}

	/**
	 * @see \WC_Admin_Settings::show_messages()
	 */
	public function settings_show_messages() {
		\WC_Admin_Settings::show_messages();
	}

	/**
	 * @see wp_kses_post
	 *
	 * @param $data
	 *
	 * @return string
	 */
	public function wordpress_kses_post( $data ) {
		return wp_kses_post( $data );
	}

	public function wp_tempnam( $file = '', $dir = '' ) {
		if ( ! function_exists( 'wp_tempnam' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		return wp_tempnam( $file, $dir );
	}

	/**
	 * @param $file_array
	 * @param $post_id
	 * @param null $desc
	 * @param array $post_data
	 *
	 * @return int|\WP_Error
	 */
	public function media_handle_sideload( $file_array, $post_id, $desc = null, $post_data = array() ) {
		if ( ! function_exists( 'media_handle_sideload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
		}

		return media_handle_sideload( $file_array, $post_id, $desc, $post_data );
	}

	public function admin_url( $path = '', $sheme = 'admin' ) {
		return admin_url( $path, $sheme );
	}

	public function get_posts( $args = null ) {
		return get_posts( $args );
	}

	public function wp_set_post_tags( $post_id = 0, $tags = '', $append = false ) {
		return wp_set_post_tags( $post_id, $tags, $append );
	}

	public function wp_delete_attachment( $postid = 0, $force_delete = false ) {
		return wp_delete_attachment( $postid, $force_delete );
	}

	/**
	 * @see is_order_received_page()
	 * @return bool
	 */
	public function is_order_received_page() {
		return is_order_received_page();
	}

	/**
	 * @see is_checkout()
	 * @return bool
	 */
	public function is_checkout() {
		return is_checkout();
	}

	/**
	 * @see is_admin()
	 * @return bool
	 */
	public function is_admin() {
		return is_admin();
	}

	/**
	 * Returns Woocommerce > Settings > General > Base Location option value
	 * @return string[]
	 */
	public function wc_get_base_location() {
		return wc_get_base_location();
	}

	/**
	 * @see wp_upload_dir
	 *
	 * @param string $time
	 *
	 * @return array
	 */
	public function wp_upload_dir( $time = null ) {
		return wp_upload_dir( $time );
	}

	/**
	 * @see date_i18n
	 *
	 * @param string $dateformatstring
	 * @param bool $unixtimestamp
	 * @param bool $gmt
	 *
	 * @return string
	 */
	public function date_i18n( $dateformatstring, $unixtimestamp = false, $gmt = false ) {
		return date_i18n( $dateformatstring, $unixtimestamp, $gmt );
	}

	/**
	 * @see get_post_meta
	 *
	 * @param int $post_id
	 * @param string $key
	 * @param bool $single
	 *
	 * @return mixed
	 */
	public function get_post_meta( $post_id, $key = '', $single = false ) {
		return get_post_meta( $post_id, $key, $single );
	}

	/**
	 * @see add_post_meta
	 *
	 * @param int $post_id
	 * @param string $meta_key
	 * @param string $meta_value
	 * @param bool $unique
	 *
	 * @return int|false Meta ID on success, false on failure.
	 */
	public function add_post_meta( $post_id, $meta_key, $meta_value, $unique = false ) {
		return add_post_meta( $post_id, $meta_key, $meta_value, $unique );
	}

	/**
	 * @see wp_create_nonce()
	 *
	 * @param int $action
	 *
	 * @return string nonce
	 */
	public function wp_create_nonce( $action = - 1 ) {
		return wp_create_nonce( $action );
	}

	/**
	 * @see build_query()
	 *
	 * @param $data
	 *
	 * @return string url
	 */
	public function build_query( $data ) {
		return build_query( $data );
	}

	/**
	 * @see wc_get_template
	 *
	 * @param string $template_name
	 * @param array $args
	 * @param string $template_path
	 * @param string $default_path
	 */
	public function wc_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
		wc_get_template( $template_name, $args, $template_path, $default_path );
	}

	/**
	 * @see get_locale
	 * @return string
	 */
	public function get_locale() {
		return get_locale();
	}
}
