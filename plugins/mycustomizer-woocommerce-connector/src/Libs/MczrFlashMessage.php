<?php

namespace MyCustomizer\WooCommerce\Connector\Libs;

use Symfony\Component\HttpFoundation\Session\Session;

class MczrFlashMessage {

	const TYPE_ERROR   = 'error';
	const TYPE_SUCCESS = 'success';
	const TYPE_WARNING = 'warning';
	const TYPE_INFO    = 'info';

	public $messages = array();

	public function __construct() {
		$this->session = new Session();
	}

	public function init() {
		add_action( 'admin_notices', array( $this, 'display' ) );
	}

	public function add( $type = self::TYPE_INFO, $message = '' ) {
		$this->session->getFlashBag()->add( $type, $message );
		return $this;
	}

	public function display() {
		if (!session_start()) {
			return '';
		}
		try {
			$all = $this->session->getFlashBag()->all();
		} catch (Exception $e) {
			return '';
		}
		if ( empty( $all ) ) {
			return '';
		}
		foreach ( $all as $type => $messages ) {
			foreach ( $messages as $message ) {
				echo '<div class="notice notice-' . esc_attr( $type ) . ' is-dismissible"><p>' . esc_attr( $message ) . '</p></div>';
			}
		}
	}
}
