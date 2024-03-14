<?php

namespace WC_BPost_Shipping\Label;

use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;
use WC_BPost_Shipping\Controller\WC_BPost_Shipping_Controller_Base;
use WC_BPost_Shipping\Options\WC_BPost_Shipping_Options_Base;
use WC_BPost_Shipping\Options\WC_BPost_Shipping_Options_Label;
use WC_BPost_Shipping\Zip\WC_BPost_Shipping_Zip_Archiver;
use WC_BPost_Shipping\Zip\WC_BPost_Shipping_Zip_Filename;

/**
 * Class WC_BPost_Shipping_Label_Controller
 * @package WC_BPost_Shipping\Label
 */
class WC_BPost_Shipping_Label_Controller extends WC_BPost_Shipping_Controller_Base {
	const ORDER_REFERENCE_KEY = 'order_reference';
	const ATTACHMENT_ID_KEY   = 'attachment_id';

	/** @var WC_BPost_Shipping_Label_Retriever */
	private $label_retriever;
	/** @var WC_BPost_Shipping_Options_Base */
	private $options_label;
	/** @var WC_BPost_Shipping_Zip_Filename */
	private $zip_filename;
	/** @var array */
	private $external_data;

	/**
	 * WC_BPost_Shipping_Label_Controller constructor.
	 *
	 * @param WC_BPost_Shipping_Adapter_Woocommerce $adapter
	 * @param WC_BPost_Shipping_Options_Label $options_label
	 * @param WC_BPost_Shipping_Label_Retriever $label_retriever
	 * @param WC_BPost_Shipping_Zip_Filename $zip_filename
	 * @param array $external_data
	 */
	public function __construct(
		WC_BPost_Shipping_Adapter_Woocommerce $adapter,
		WC_BPost_Shipping_Options_Label $options_label,
		WC_BPost_Shipping_Label_Retriever $label_retriever,
		WC_BPost_Shipping_Zip_Filename $zip_filename,
		array $external_data
	) {
		parent::__construct( $adapter );

		$this->options_label   = $options_label;
		$this->label_retriever = $label_retriever;
		$this->zip_filename    = $zip_filename;
		$this->external_data   = $external_data;
	}

	public function verify_access() {
		if ( ! empty( $this->external_data['wp_once'] ) ) {
			if ( ! wp_verify_nonce( $this->external_data['wp_once'] ) ) {
				wp_die( bpost__( 'Invalid wp_once' ), '', 403 );
			}

			return;
		}

		if (
			! empty( trim( $this->options_label->get_label_api_key() ) )
			&& ! empty( trim( $this->external_data['bpost_key'] ) )
		) {
			if ( strval( $this->external_data['bpost_key'] ) !== $this->options_label->get_label_api_key() ) {
				wp_die( bpost__( 'Invalid bpost_key' ), '', 403 );
			}

			return;
		}

		wp_die( bpost__( 'Permission denied' ), '', 403 );
	}

	/**
	 * This function provides a contract to use to load a template using controller.
	 * @throws \Exception
	 */
	public function load_template() {
		$this->verify_access();

		$post_ids = filter_var( $this->external_data['post_ids'], FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );

		$contents = $this->label_retriever->get_labels_contents( $post_ids );

		if ( count( $contents ) === 1 ) {
			$filename = key( $contents );
			$content  = $contents[ $filename ];

			header( 'Content-Type: application/pdf' );
			header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
			echo $content; // outputting binary pdf content, we cannot escape that
			die();
		}

		$zip_archiver = new WC_BPost_Shipping_Zip_Archiver(
			$this->adapter,
			new \ZipArchive(),
			new WC_BPost_Shipping_Label_Path_Resolver( $this->options_label )
		);
		$zip_archiver->build_archive( $contents );
		$zip_archiver->send_archive( $this->zip_filename->get_filename() );
		die();
	}
}
