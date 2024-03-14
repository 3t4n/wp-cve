<?php

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RenderStatus {
	/**
	 * @var null
	 */
	public static $instance = null;

	/**
	 * @return null|RenderStatus
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @return bool
	 */
	public function getStatus() {
		return get_option( 'stax-render-status' );
	}

	/**
	 * @return bool
	 */
	public function toggleStatus() {
		if ( $this->getStatus() ) {
			update_option( 'stax-render-status', false );
		} else {
			update_option( 'stax-render-status', true );
		}

		return $this->getStatus();
	}
}
