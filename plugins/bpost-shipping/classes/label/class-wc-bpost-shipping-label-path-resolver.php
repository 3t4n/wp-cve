<?php

namespace WC_BPost_Shipping\Label;

use WC_BPost_Shipping\Options\WC_BPost_Shipping_Options_Label;

/**
 * Class WC_BPost_Shipping_Label_Path_Resolver builds pat hof an attachment
 * @package WC_BPost_Shipping\Label
 */
class WC_BPost_Shipping_Label_Path_Resolver {
	/**
	 * @var string
	 */
	private $storage_path;

	/** @var WC_BPost_Shipping_Options_Label */
	private $options_label;

	/**
	 * WC_BPost_Shipping_Label_Url_Generator constructor.
	 *
	 * @param WC_BPost_Shipping_Options_Label $options_label
	 */
	public function __construct( WC_BPost_Shipping_Options_Label $options_label ) {
		$this->options_label = $options_label;
	}

	/**
	 * @return string
	 */
	private function get_storage_path() {
		if ( $this->storage_path === null ) {
			$this->storage_path = $this->options_label->get_storage_path();
		}

		return $this->storage_path;
	}

	/**
	 * Provide filename using overlap
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	public function get_path( $url ) {
		$url = parse_url( $url );

		if ( ! isset( $url['scheme'] ) || $url['scheme'] === 'file' ) {
			return $url['path'];
		}

		return $this->merge_overlap( $this->get_storage_path(), $url['path'] );
	}

	/**
	 * Search and use the common part to do an overlap with string.
	 * Check Unit tests to see how it's work
	 *
	 * @param string $left
	 * @param string $right
	 *
	 * @return string
	 */
	private function merge_overlap( $left, $right ) {
		$left_parts     = explode( '/', $left );
		$left_last_part = end( $left_parts );

		$pos  = strpos( $right, '/' . $left_last_part . '/' );
		$pos += strlen( '/' . $left_last_part . '/' );

		$right_part = substr( $right, $pos );
		if ( $left_last_part && $right_part ) {
			return $left . '/' . $right_part;
		}

		return '';
	}

	public function get_basename( $url ) {
		return basename( $url );
	}

	public function get_content( $url ) {
		return file_get_contents( $this->get_path( $url ) );
	}

	/**
	 * @param WC_BPost_Shipping_Label_Post $post
	 *
	 * @return string
	 */
	public function get_filename( WC_BPost_Shipping_Label_Post $post ) {
		if ( $this->options_label->are_labels_as_files() ) {
			return sprintf(
				'%d-%s-%s.pdf',
				$post->get_post_id(),
				strtolower( $this->options_label->get_label_format() ),
				$this->options_label->is_return_label_enabled( $post ) ? 'return' : 'noReturn'
			);
		}

		return sprintf(
			'bpost-%s-%07d-%s-%s.pdf',
			$post->get_order_reference(),
			$post->get_post_id(),
			strtolower( $this->options_label->get_label_format() ),
			$this->options_label->is_return_label_enabled( $post ) ? 'return' : 'noReturn'
		);

	}

	public function get_storage_file_path( WC_BPost_Shipping_Label_Post $post ) {
		return $this->get_storage_path() . '/' . $this->get_filename( $post );
	}
}
