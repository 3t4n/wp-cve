<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VICACA_DATA {
	private $params;
	protected static $instance = null;

	/**
	 * VICACA_DATA constructor.
	 */
	public function __construct() {
		global $vicaca_settings;
		if ( ! $vicaca_settings ) {
			$vicaca_settings = get_option( 'vicaca_params', array() );
		}

		$this->params = apply_filters( 'vicaca_params', wp_parse_args( $vicaca_settings,
			array(
				'clear_by_cache_size'    => 0,
				'cache_size'             => 400,
				'cache_size_unit'        => 'mb',
				'clear_by_time_interval' => 0,
				'cache_interval'         => 1,
				'cache_interval_unit'    => 'day',
				'execution_link'         => '',
				'execution_link_secret'  => md5( time() ),
			)
		) );
	}

	public function get_params( $name = '' ) {
		if ( ! $name ) {
			return $this->params;
		} elseif ( isset( $this->params[ $name ] ) ) {
			return apply_filters( 'vicaca_params_' . $name, $this->params[ $name ] );
		} else {
			return false;
		}
	}

	public static function get_instance( $new = false ) {
		if ( $new || null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}