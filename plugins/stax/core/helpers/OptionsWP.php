<?php

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class OptionsWP {
	/**
	 * @var null
	 */
	public static $instance = null;

	/**
	 * @return null|OptionsWP
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @return array
	 */
	public function getAttachedHeaders() {
		global $wpdb;

		$options = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}options WHERE `option_name` LIKE 'stax_item_%'" );

		$attachedHeaders = [];

		foreach ( $options as $option ) {
			$attachedHeaders[] = [
				'header_id' => intval( $option->option_value ),
				'item_id'   => intval( filter_var( $option->option_name, FILTER_SANITIZE_NUMBER_INT ) )
			];
		}

		return $attachedHeaders;
	}
}
