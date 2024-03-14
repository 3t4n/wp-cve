<?php

namespace WC_BPost_Shipping\Label;

use Bpost\BpostApiClient\Bpost\Label;
use Bpost\BpostApiClient\Exception\BpostApiResponseException\BpostCurlException;
use Bpost\BpostApiClient\Exception\BpostApiResponseException\BpostInvalidResponseException;
use Bpost\BpostApiClient\Exception\BpostApiResponseException\BpostInvalidSelectionException;
use Bpost\BpostApiClient\Exception\XmlException\BpostXmlNoReferenceFoundException;
use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;
use WC_BPost_Shipping\Api\WC_BPost_Shipping_Api_Factory;
use WC_BPost_Shipping\Api\WC_BPost_Shipping_Api_Label;
use WC_BPost_Shipping\Label\Exception\WC_BPost_Shipping_Label_Exception_Not_Found;
use WC_BPost_Shipping\Options\WC_BPost_Shipping_Options_Label;
use WC_BPost_Shipping\WC_Bpost_Shipping_Container as Container;
use WC_BPost_Shipping_Logger;

class WC_BPost_Shipping_Label_Retriever {

	/** @var WC_BPost_Shipping_Adapter_Woocommerce */
	private $adapter;

	/** @var WC_BPost_Shipping_Api_Label */
	private $api_label;
	/** @var WC_BPost_Shipping_Label_Url_Generator */
	private $url_generator;
	/** @var WC_BPost_Shipping_Label_Path_Resolver */
	private $label_path_resolver;
	/** @var WC_BPost_Shipping_Options_Label */
	private $options_label;
	/** @var WC_BPost_Shipping_Logger */
	private $logger;

	public function __construct(
		WC_BPost_Shipping_Adapter_Woocommerce $adapter,
		WC_BPost_Shipping_Api_Factory $api_factory,
		WC_BPost_Shipping_Label_Url_Generator $url_generator,
		WC_BPost_Shipping_Label_Path_Resolver $label_path_resolver,
		WC_BPost_Shipping_Options_Label $options_label
	) {
		$this->adapter             = $adapter;
		$this->api_label           = $api_factory->get_label();
		$this->url_generator       = $url_generator;
		$this->label_path_resolver = $label_path_resolver;
		$this->options_label       = $options_label;

		$this->logger = Container::get_logger();
	}


	/**
	 * @param string $filepath
	 * @param WC_BPost_Shipping_Label_Post $post
	 *
	 * @throws BpostCurlException
	 * @throws BpostInvalidResponseException
	 * @throws BpostInvalidSelectionException
	 * @throws BpostXmlNoReferenceFoundException
	 */
	public function get_label_as_file( $filepath, WC_BPost_Shipping_Label_Post $post ) {
		if ( ! $post->get_order_reference() ) {
			$this->logger->warning( 'The order does not contain order reference', array( 'order_id' => $post->get_post_id() ) );

			return;
		}
		if ( file_exists( $filepath ) && filesize( $filepath ) > 0 ) {
			return;
		}
		$format             = $this->options_label->get_label_format();
		$with_return_labels = $this->options_label->is_return_label_enabled( $post );
		$label              = $this->api_label->get_label( $post->get_order_reference(), $format, $with_return_labels );

		if ( ! $label ) {
			throw new WC_BPost_Shipping_Label_Exception_Not_Found( bpost__( 'This label is not available for print.' ) );
		}
		$this->save_label( $filepath, $label );
	}

	/**
	 * Save into temp file (/tmp/xxx or whatever a label provided as attachment)
	 *
	 * @param string $filepath
	 * @param Label $label_retrieved
	 */
	private function save_label( $filepath, Label $label_retrieved ) {
		$handle = fopen( $filepath, 'w' );
		fwrite( $handle, $label_retrieved->getBytes() );
		fclose( $handle );
		clearstatcache();
	}

	/**
	 * @param array $post_ids
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function get_labels_contents( array $post_ids ) {
		$contents = array();

		$are_labels_as_files = $this->options_label->are_labels_as_files();

		foreach ( $post_ids as $post_id ) {
			if ( $are_labels_as_files ) {
				$url = $this->get_label_file_url( $post_id );
			} else {
				$url = $this->get_label_attachment_url( $post_id );
			}

			$contents[ $this->label_path_resolver->get_basename( $url ) ] = $this->label_path_resolver->get_content( $url );
		}

		return $contents;
	}

	/**
	 * @param int $post_id
	 *
	 * @return false|string
	 * @throws \Exception
	 */
	private function get_label_attachment_url( $post_id ) {
		$label_attach = new WC_BPost_Shipping_Label_Attachment(
			$this->adapter,
			$this->options_label,
			$this->url_generator,
			$this,
			$this->label_path_resolver,
			$this->get_label_post( $post_id )
		);

		return $label_attach->get_url();
	}

	/**
	 * @param int $post_id
	 *
	 * @return false|string
	 * @throws \Exception
	 */
	private function get_label_file_url( $post_id ) {
		$post = $this->get_label_post( $post_id );
		$url  = $this->label_path_resolver->get_storage_file_path( $post );
		$this->get_label_as_file( $url, $post );

		return $url;
	}

	private function get_label_post( $post_id ) {
		$meta_handler = new \WC_BPost_Shipping_Meta_Handler(
			$this->adapter,
			new \WC_BPost_Shipping_Meta_Type( $this->adapter ),
			$post_id
		);

		return new WC_BPost_Shipping_Label_Post( $meta_handler, new \WC_Order( $post_id ) );

	}
}
