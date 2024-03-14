<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !function_exists( 'bv_gravity_ip_process_bulk_import' ) ) {

	/**
	 * @param $ips
	 */
	function bv_gravity_ip_process_bulk_import( $ips, $nonce ) {
		if ( wp_verify_nonce( $nonce, 'bv-bulk-ip-import' ) ) {
			// Nonce is valid, process the form data here
           
			$buffer = explode( "\n", $ips );
			foreach ( $buffer as $ip ) {
				$ip = trim( $ip );
				if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
					$args = array(
						'post_type'  => 'ip',
						'meta_key'   => '_gravity_ips_ip',
						'meta_value' => $ip,
					);
					$query = new WP_Query( $args );

					if ( !$query->have_posts() ) {
						// Create post object
						$my_post = array(
							'post_title'   => $ip,
							'post_content' => '',
							'post_status'  => 'publish',
							'meta_input'   => array(
								'_gravity_ips_ip' => $ip,
							),
							'post_type'    => 'ip',
						);

						// Insert the post into the database
						wp_insert_post( $my_post );
					}
				}

			}
		}

		flush_rewrite_rules();

	}
}
