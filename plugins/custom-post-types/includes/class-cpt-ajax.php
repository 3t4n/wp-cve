<?php

defined( 'ABSPATH' ) || exit;

final class CPT_Ajax extends CPT_Component {
	/**
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'init', array( $this, 'init_ajax' ) );
	}

	/**
	 * @return void
	 */
	public function init_ajax() {
		$ajax_actions = apply_filters( 'cpt_ajax_actions_register', array() );
		foreach ( $ajax_actions as $action => $args ) {
			add_action(
				'wp_ajax_' . $action,
				function () use ( $args ) {
					if ( empty( $_SERVER['REQUEST_METHOD'] ) ) {
						wp_send_json_error();
					}
					$data  = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
					$nonce = ! empty( $data['nonce'] ) && wp_verify_nonce( $data['nonce'], CPT_NONCE_KEY );
					if ( ! $nonce ) {
						wp_send_json_error();
					}
					foreach ( $args['required'] as $param ) {
						if ( empty( $data[ $param ] ) ) {
							wp_send_json_error();
						}
					}
					if ( empty( $args['callback'] ) || ! is_callable( $args['callback'] ) ) {
						wp_send_json_error();
					}
					$result = $args['callback']( $data );
					wp_send_json_success( $result );
				}
			);
		}
	}
}
