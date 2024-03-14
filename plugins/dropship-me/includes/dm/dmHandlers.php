<?php
/**
 * Created by PhpStorm.
 * User: axelk
 * Date: 25.02.2018
 * Time: 17:32
 */

namespace dm;


class dmHandlers {
	/**
	 * @uses action_page_license()
	 * @uses action_save_page_license()
	 * @uses action_page_package()
	 * @uses action_save_page_package()
	 */

	/**
	 * @param array $post
	 *
	 * @return array
	 */
	public function actions( $post ) {

		if ( isset( $post[ 'dm_action' ] ) && current_user_can( 'activate_plugins' ) ) {

			$dm_actions = 'action_' . $post[ 'dm_action' ];
			$args        = $post[ 'args' ];
			$data        = [];

			if( is_array( $args ) ) {
				$data = $args;
			} else {
				parse_str( $args, $data );
			}

			if( method_exists( $this, $dm_actions ) ) {
				return $this->$dm_actions( $data );
			}
		}
		
		return [ 'error' => __( 'Undefined action', 'dm' ) ];
	}

	/**
	 * Get License Key for plugin
	 */
	private function action_page_license() {

		return [
			'dm_licensekey' => get_option( 'dm-license', '' )
		];
	}

	/**
	 * Save License Key for plugin
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	private function action_save_page_license( $data ) {

		if( ! isset( $data[ 'dm_licensekey' ] ) || ! wp_verify_nonce( $data[ 'dm_license' ], 'dm_setting_action' ) )
			return [ 'error' => __( 'Undefined form data', 'dm' ) ];

		$license = trim( $data[ 'dm_licensekey' ] );

		update_option( 'dm-license', $license );

		if( $license != '' ) {

			$response = wp_remote_post( 'https://dropship.me/?rest', [
				'method'    => 'POST',
				'timeout'   => 45,
				'sslverify' => false,
				'body'      => [ 'key' => $license, 'site' => get_bloginfo( 'url' ) ],
			] );

			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				return [
					'error' => __( 'Could not connect', 'dm' ) . ' ' . $error_message
				];
			} else {
				$foo = json_decode( $response[ 'body' ] );
				
				if( isset( $foo->signature ) ) {
					
					$uri        = get_bloginfo( 'url' ) . '/';
					$handlers_p = md5( $uri );
					$vendor     = md5( md5( $license . $uri ) . md5( $uri ) );
					
					if ( md5( $foo->signature . $handlers_p ) == $vendor ) {
						update_option( '_random_hash_dm', $vendor );
					} else {
						return [
							'error' => __( 'Error: Go to Settings->General and check your Address URLs.', 'dm' )
						];
					}
				}
				
				return [
					'message'       => $foo->msg,
					'dm_licensekey' => get_option( 'dm-license', '' )
				];
			}
		}

		return [ 'error' => __( 'License key field is empty', 'dm' ) ];
	}
	
	/**
	 * Package page init
	 */
	private function action_page_package() {
		
		return [
			'dm_packagekey' => ''
		];
	}
	
	/**
	 * Package page count init
	 */
	private function action_page_package_count() {
		
		$obj = new dmApi();
		
		return $obj->getDeposit();
	}
	
	/**
	 * Activate package
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	private function action_save_page_package( $data ) {

		if( ! isset( $data[ 'dm_packagekey' ] ) || ! wp_verify_nonce( $data[ 'dm_package' ], 'dm_setting_action' ) )
			return [ 'error' => __( 'Undefined form data', 'dm' ) ];

		$package = trim( $data[ 'dm_packagekey' ] );
		$license = get_option( 'dm-license' );
		
		if( $package != '' ) {

			$response = wp_remote_post( 'https://dropship.me/?rest_package', [
				'method'    => 'POST',
				'timeout'   => 45,
				'sslverify' => false,
				'body'      => [
					'key'  => $license,
					'site' => get_bloginfo( 'url' ),
					'hash' => $package,
				],
			] );

			if ( is_wp_error( $response ) ) {
				
				return [ 'error' => __( 'Could not connect', 'dm' ) . ' ' . $response->get_error_message() ];
			} else {
				
				$foo = json_decode( $response[ 'body' ] );
				
				return [
					'message' => $foo->msg,
					'deposit' => isset( $foo->deposit ) ? $foo->deposit : false
				];
			}
		}

		return [ 'error' => __( 'Package key field is empty', 'dm' ) ];
	}
}