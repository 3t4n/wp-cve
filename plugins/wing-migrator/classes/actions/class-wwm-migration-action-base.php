<?php

abstract class Wwm_Migration_Action {
	public static $action_key = null;

	public static function get_action_info() {
		return array(
			'action_key' => static::$action_key,
			'class_name' => get_called_class()
		);
	}

	protected function get_parameter( $param_name, $require ) {
		if ( ! isset( $_GET[ $param_name ] ) || $_GET[ $param_name ] === '' ) {
			if ( $require ) {
				Wwm_Migration_Response::create_error_response( 'parameter ' . $param_name . ' is missing.', 400 );
			}
			return null;
		}
		return $_GET[ $param_name ];
	}

	abstract public function do_action();
}
