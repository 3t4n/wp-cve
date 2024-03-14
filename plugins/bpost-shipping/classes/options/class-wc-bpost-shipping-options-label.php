<?php

namespace WC_BPost_Shipping\Options;

use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;
use WC_BPost_Shipping\Label\WC_BPost_Shipping_Label_Post;

/**
 * Class WC_BPost_Shipping_Options_Decorator adds functions to Options without touching Options class
 * @package WC_BPost_Shipping\Decorator
 */
class WC_BPost_Shipping_Options_Label extends WC_BPost_Shipping_Options_Base {
	/** @var WC_BPost_Shipping_Adapter_Woocommerce */
	private $adapter_woocommerce;

	/**
	 * WC_BPost_Shipping_Options_Decorator constructor.
	 *
	 * @param WC_BPost_Shipping_Adapter_Woocommerce $adapter_woocommerce
	 */
	public function __construct( WC_BPost_Shipping_Adapter_Woocommerce $adapter_woocommerce ) {
		$this->adapter_woocommerce = $adapter_woocommerce;
	}

	/**
	 * Returns if the shop is defined in Belgium or not
	 * @return bool true if it, false if not
	 */
	public function is_local_shop() {
		$base_location = $this->adapter_woocommerce->wc_get_base_location();
		if ( ! array_key_exists( 'country', $base_location ) ) {
			return false;
		}

		return 'BE' === $base_location['country'];
	}


	/**
	 * @param WC_BPost_Shipping_Label_Post|null $post
	 *
	 * @return bool
	 */
	public function is_return_label_enabled( WC_BPost_Shipping_Label_Post $post = null ) {

		if ( $post !== null && $this->is_local_shop() && $post->get_order_country() !== 'BE' ) {
			return false;
		}

		return $this->get_option( 'label_return' ) === 'yes';
	}

	/**
	 * @return string A4 or A6
	 */
	public function get_label_format() {
		return $this->get_option( 'label_format' ) ?: 'A6';
	}

	/**
	 * @return bool
	 */
	public function are_labels_as_files() {
		return $this->get_option( 'label_storage_as_files' ) === 'yes';
	}

	/**
	 * @return string
	 */
	public function get_storage_path() {
		if ( ! $this->are_labels_as_files() ) {
			$upload = $this->adapter_woocommerce->wp_upload_dir();

			return $upload['basedir'];
		}

		$path = rtrim( $this->get_option( 'label_storage_path' ), '/' );
		if ( empty( $path ) && $this->are_labels_as_files() ) {
			throw new \LogicException( 'Please, check option bpost "Use default storage path (upload dir)" or enter an alternative path' );
		}

		if ( ! is_dir( $path ) ) {
			mkdir( $path, 0755, true );
		}

		return $path;
	}
}
