<?php

namespace WC_BPost_Shipping\Label;

use Bpost\BpostApiClient\Exception\BpostApiResponseException\BpostCurlException;
use Bpost\BpostApiClient\Exception\BpostApiResponseException\BpostInvalidResponseException;
use Bpost\BpostApiClient\Exception\BpostApiResponseException\BpostInvalidSelectionException;
use Bpost\BpostApiClient\Exception\XmlException\BpostXmlNoReferenceFoundException;
use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;
use WC_BPost_Shipping\Label\Exception\WC_BPost_Shipping_Label_Exception_Temporary_File;
use WC_BPost_Shipping\Options\WC_BPost_Shipping_Options_Base;
use WC_BPost_Shipping\Options\WC_BPost_Shipping_Options_Label;

/**
 * Class WC_BPost_Shipping_Label_Attachment
 * @package WC_BPost_Shipping\Label
 */
class WC_BPost_Shipping_Label_Attachment {

	/** @var WC_BPost_Shipping_Options_Base */
	private $options_label;
	/** @var WC_BPost_Shipping_Label_Post */
	private $post;
	/** @var WC_BPost_Shipping_Label_Url_Generator */
	private $url_generator;
	/** @var WC_BPost_Shipping_Label_Retriever */
	private $label_retriever;
	/** @var WC_BPost_Shipping_Adapter_Woocommerce */
	private $adapter;
	/** @var WC_BPost_Shipping_Label_Path_Resolver */
	private $label_path_resolver;

	/**
	 * WC_BPost_Shipping_Label_Attachments constructor.
	 *
	 * @param WC_BPost_Shipping_Adapter_Woocommerce $adapter
	 * @param WC_BPost_Shipping_Options_Label $options_label
	 * @param WC_BPost_Shipping_Label_Url_Generator $url_generator
	 * @param WC_BPost_Shipping_Label_Retriever $label_retriever
	 * @param WC_BPost_Shipping_Label_Path_Resolver $label_path_resolver
	 * @param WC_BPost_Shipping_Label_Post $post
	 */
	public function __construct(
		WC_BPost_Shipping_Adapter_Woocommerce $adapter,
		WC_BPost_Shipping_Options_Label $options_label,
		WC_BPost_Shipping_Label_Url_Generator $url_generator,
		WC_BPost_Shipping_Label_Retriever $label_retriever,
		WC_BPost_Shipping_Label_Path_Resolver $label_path_resolver,
		WC_BPost_Shipping_Label_Post $post
	) {
		$this->adapter             = $adapter;
		$this->options_label       = $options_label;
		$this->post                = $post;
		$this->label_retriever     = $label_retriever;
		$this->url_generator       = $url_generator;
		$this->label_path_resolver = $label_path_resolver;
	}

	/**
	 * @param $filepath
	 *
	 * @return int|\WP_Error
	 * @throws BpostCurlException
	 * @throws BpostInvalidResponseException
	 * @throws BpostInvalidSelectionException
	 * @throws BpostXmlNoReferenceFoundException
	 * @throws \Exception
	 */
	public function create_attachment( $filepath ) {
		$this->label_retriever->get_label_as_file( $filepath, $this->post );

		$desc       = $this->post->get_order_reference();
		$file_array = array();

		// Set variables for storage
		// fix file filename for query strings
		$file_array['name']     = $this->label_path_resolver->get_filename( $this->post );
		$file_array['tmp_name'] = $filepath;

		// do the validation and storage stuff
		$attach_id = $this->adapter->media_handle_sideload( $file_array, $this->post->get_post_id(), $desc );

		if ( is_wp_error( $attach_id ) ) {
			throw new \Exception( $attach_id->get_error_message() );
		}

		$this->adapter->wp_set_post_tags( $attach_id, array( 'bpost' ) );

		return $attach_id;
	}

	/**
	 * @return bool
	 */
	public function has_attachment() {
		return (bool) $this->get_post();
	}

	/**
	 * @return false|string
	 * @throws \Exception
	 */
	public function get_url() {
		if ( $post = $this->get_post() ) {
			return wp_get_attachment_url( $post->ID );
		}

		$temp_filename = $this->adapter->wp_tempnam();
		if ( ! $temp_filename ) {
			throw new WC_BPost_Shipping_Label_Exception_Temporary_File( bpost__( 'Could not create Temporary file.' ) );
		}

		$attach_id = $this->create_attachment( $temp_filename );

		return wp_get_attachment_url( $attach_id );
	}

	/**
	 * @return string
	 */
	public function get_generate_url() {
		return $this->url_generator->get_generate_url( array( $this->post->get_post_id() ) );
	}

	/**
	 * @return array
	 */
	public function build_request_params() {
		return array(
			'post_type'   => 'attachment',
			'numberposts' => 1,
			'post_status' => 'any',
			'post_parent' => $this->post->get_post_id(),
		);
	}

	/**
	 * @return \WP_Post|null
	 */
	private function get_post() {
		$post_attachments = get_posts( $this->build_request_params() );

		if ( ! $post_attachments ) {
			return null;
		}

		return $post_attachments[0];
	}

	/**
	 * @return string
	 */
	public function get_order_reference() {
		return $this->post->get_order_reference();
	}

	/**
	 * @return \DateTime
	 * @throws \Exception
	 */
	public function get_retrieved_date() {
		$post = $this->get_post();

		return new \DateTime( $post->post_date );
	}

	/**
	 * @return string
	 */
	public function get_shipping_postal_code() {
		return $this->post->get_order()->get_shipping_postcode();
	}
}
