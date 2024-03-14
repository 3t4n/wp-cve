<?php
/**
 * Adsense specific features.
 */

/**
 * Add inline script to hide the notice on clicking dismiss button.
 */
add_action(
	'admin_enqueue_scripts',
	function () {
		if ( current_user_can( 'manage_options' ) ) {
			wp_localize_script(
				'jquery',
				'quick_adsense_adstxt_adsense',
				[
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'quick-adsense-adstxt-adsense-nonce' ),
				]
			);
			wp_add_inline_script(
				'jquery',
				quick_adsense_load_file( 'templates/js/script-admin-notice.php' )
			);
		}
	}
);

/**
 * This function checks for and displays the admin notice when needed.
 *
 * @param boolean $is_ajax Specific where the function is being called during an ajax call.
 */
function quick_adsense_adstxt_adsense_admin_notice( $is_ajax = false ) {
	if ( current_user_can( 'manage_options' ) ) {
		if ( ! get_option( 'quick_adsense_adstxt_adsense_admin_notice_dismissed' ) ) {
			$adstxt_new_adsense_entries = get_transient( 'quick_adsense_adstxt_adsense_autocheck_content' );
			if ( 'CHECKED' !== $adstxt_new_adsense_entries ) {
				if ( ! isset( $adstxt_new_adsense_entries ) || ( false === $adstxt_new_adsense_entries ) ) {
					$adstxt_new_adsense_entries = quick_adsense_adstxt_adsense_get_status();
				}
				if ( ( false !== $adstxt_new_adsense_entries ) && is_array( $adstxt_new_adsense_entries ) ) {
					set_transient( 'quick_adsense_adstxt_adsense_autocheck_content', $adstxt_new_adsense_entries, DAY_IN_SECONDS );
					$screen = get_current_screen();
					quick_adsense_load_file(
						'templates/block-adsense-adstxt-notice.php',
						[
							'screen_id'                  => $screen->id,
							'is_ajax'                    => $is_ajax,
							'adstxt_new_adsense_entries' => implode( '<br />', $adstxt_new_adsense_entries ),
						],
						true
					);
				} else {
					set_transient( 'quick_adsense_adstxt_adsense_autocheck_content', 'CHECKED', DAY_IN_SECONDS );
				}
			}
		}
	}
}
add_action( 'admin_notices', 'quick_adsense_adstxt_adsense_admin_notice' );

/**
 * This function checks whether new entries need to be added to ads.txt based on the adcodes added.
 *
 * @return mixed False is no changes are needed, New ads.txt lines as an array if changes are required.
 */
function quick_adsense_adstxt_adsense_get_status() {
	$file_handler = new QuickAdsense\FileHandler( 'ads.txt' );
	if ( $file_handler->exists() ) {
		$adsense_publisher_ids = quick_adsense_adstxt_adsense_get_publisherids();
		$adstxt_content        = $file_handler->read();
		if ( false !== $adstxt_content ) {
			$adstxt_content_data = array_filter( explode( "\n", trim( $adstxt_content ) ), 'trim' );
			if ( is_array( $adstxt_content_data ) ) {
				$adstxt_existing_adsense_entries = [];
				foreach ( $adstxt_content_data as $line ) {
					if ( strpos( $line, 'google.com' ) !== false ) {
						$adstxt_existing_adsense_entries[] = $line;
					}
				}

				$adstxt_new_adsense_entries = [];
				if ( count( $adstxt_existing_adsense_entries ) === 0 ) {
					if ( is_array( $adsense_publisher_ids ) && ( count( $adsense_publisher_ids ) > 0 ) ) {
						foreach ( $adsense_publisher_ids as $adsense_publisher_id ) {
							$adstxt_new_adsense_entries[] = 'google.com, ' . $adsense_publisher_id . ', DIRECT, f08c47fec0942fa0';
						}
					}
				} else {
					if ( is_array( $adsense_publisher_ids ) && ( count( $adsense_publisher_ids ) > 0 ) ) {
						foreach ( $adsense_publisher_ids as $adsense_publisher_id ) {
							$entry_exists = false;
							foreach ( $adstxt_existing_adsense_entries as $adstxt_existing_adsense_entry ) {
								if ( strpos( $adstxt_existing_adsense_entry, $adsense_publisher_id ) !== false ) {
									$entry_exists = true;
								}
							}
							if ( false === $entry_exists ) {
								$adstxt_new_adsense_entries[] = 'google.com, ' . $adsense_publisher_id . ', DIRECT, f08c47fec0942fa0';
							}
						}
					}
				}
			}
		}
	}
	if ( isset( $adstxt_new_adsense_entries ) && count( $adstxt_new_adsense_entries ) > 0 ) {
		return $adstxt_new_adsense_entries;
	}
	return false;
}

/**
 * Function to extract publisher Ids from settings.
 *
 * @return mixed False if no publisher ids found, Array of publisher ids if publisher ids found.
 */
function quick_adsense_adstxt_adsense_get_publisherids() {
	$adsense_publisher_ids = [];

	$settings = get_option( 'quick_adsense_settings' );
	if ( isset( $settings ) && is_array( $settings ) ) {
		for ( $i = 1; $i <= 10; $i++ ) {
			if ( isset( $settings[ 'onpost_ad_' . $i . '_content' ] ) && ( '' !== $settings[ 'onpost_ad_' . $i . '_content' ] ) ) {
				$temp = quick_adsense_adstxt_adsense_extract_publisherids( $settings[ 'onpost_ad_' . $i . '_content' ] );
				if ( false !== $temp ) {
					$adsense_publisher_ids = array_merge( $adsense_publisher_ids, $temp );
				}
			}

			if ( isset( $settings[ 'widget_ad_' . $i . '_content' ] ) && ( '' !== $settings[ 'widget_ad_' . $i . '_content' ] ) ) {
				$temp = quick_adsense_adstxt_adsense_extract_publisherids( $settings[ 'widget_ad_' . $i . '_content' ] );
				if ( false !== $temp ) {
					$adsense_publisher_ids = array_merge( $adsense_publisher_ids, $temp );
				}
			}

			if ( isset( $settings['header_embed_code'] ) && ( '' !== $settings['header_embed_code'] ) ) {
				$temp = quick_adsense_adstxt_adsense_extract_publisherids( $settings['header_embed_code'] );
				if ( false !== $temp ) {
					$adsense_publisher_ids = array_merge( $adsense_publisher_ids, $temp );
				}
			}

			if ( isset( $settings['footer_embed_code'] ) && ( '' !== $settings['footer_embed_code'] ) ) {
				$temp = quick_adsense_adstxt_adsense_extract_publisherids( $settings['footer_embed_code'] );
				if ( false !== $temp ) {
					$adsense_publisher_ids = array_merge( $adsense_publisher_ids, $temp );
				}
			}
		}
	}
	$adsense_publisher_ids = array_unique( $adsense_publisher_ids );

	if ( count( $adsense_publisher_ids ) > 0 ) {
		return $adsense_publisher_ids;
	}
	return false;
}

/**
 * Function to extract publisher Ids from provided adcode.
 *
 * @param string $ad_code The ad code.
 *
 * @return mixed False if no publisher ids found, Array of publisher ids if publisher ids found.
 */
function quick_adsense_adstxt_adsense_extract_publisherids( $ad_code ) {
	$publisher_ids = [];
	if ( isset( $ad_code ) && ( '' !== $ad_code ) ) {
		if ( preg_match( '/googlesyndication.com/', $ad_code ) ) {
			if ( preg_match( '/data-ad-client=/', $ad_code ) ) {
				// ASYNC AD CODE.
				$ad_code_parts = explode( 'data-ad-client', $ad_code );
			} elseif ( preg_match( '/client=ca-pub-/', $ad_code ) ) {
				// NEW ASYNC AD CODE.
				$ad_code_parts = explode( 'ca-pub-', str_replace( 'ca-pub-', 'ca-pub-"', $ad_code ) );
			} else {
				// ORDINARY AD CODE.
				$ad_code_parts = explode( 'google_ad_client', $ad_code );
			}
			if ( isset( $ad_code_parts[1] ) && ( '' !== $ad_code_parts[1] ) ) {
				preg_match( '#"([a-zA-Z0-9-\s]+)"#', stripslashes( $ad_code_parts[1] ), $matches );
				if ( isset( $matches[1] ) && ( '' !== $matches[1] ) ) {
					$publisher_ids[] = str_replace( [ '"', ' ', 'ca-' ], [ '' ], $matches[1] );
				}
			}
		}
	}

	if ( count( $publisher_ids ) > 0 ) {
		return $publisher_ids;
	}
	return false;
}

/**
 * This action dismisses the Ads.txt admin notice permanently.
 */
add_action(
	'wp_ajax_quick_adsense_adstxt_adsense_admin_notice_dismiss',
	function () {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'quick-adsense-adstxt-adsense-nonce' ) && ( current_user_can( 'manage_options' ) ) ) {
			update_option( 'quick_adsense_adstxt_adsense_admin_notice_dismissed', 'true' );
			wp_send_json_success();
		}
		wp_send_json_error();
	}
);

/**
 * This action handles the Ajax trigerred Ads.txt update.
 */
add_action(
	'wp_ajax_quick_adsense_adstxt_adsense_auto_update',
	function () {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'quick-adsense-adstxt-adsense-nonce' ) && ( current_user_can( 'manage_options' ) ) ) {
			$adstxt_new_adsense_entries = quick_adsense_adstxt_adsense_get_status();
			$file_handler               = new QuickAdsense\FileHandler( 'ads.txt' );
			if ( false !== $adstxt_new_adsense_entries ) {
				$adstxt_content         = $file_handler->read();
				$adstxt_content_data    = array_filter( explode( "\n", trim( $adstxt_content ) ), 'trim' );
				$adstxt_updated_content = array_filter( array_merge( $adstxt_content_data, $adstxt_new_adsense_entries ), 'trim' );
			}
			if ( isset( $adstxt_updated_content ) && is_array( $adstxt_updated_content ) && ( count( $adstxt_updated_content ) > 0 ) ) {
				$adstxt_updated_content = implode( "\n", $adstxt_updated_content );
				if ( $file_handler->write( $adstxt_updated_content ) ) {
					wp_send_json_success();
				} else {
					wp_send_json_error(
						quick_adsense_load_file(
							'templates/block-adsense-adstxt-update-failed.php',
							[
								'content' => $adstxt_updated_content,
							]
						)
					);
				}
			}
		}
		wp_send_json_error();
	}
);
