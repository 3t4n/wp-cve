<?php

/**
 * The class the helps with different static methods
 *
 * Class Free_Comments_For_Wordpress_Vuukle_Helper
 *
 * @since 5.0
 */
class Free_Comments_For_Wordpress_Vuukle_Helper {

	/**
	 * Checks weather the request comes from admin|ajax|cron|public
	 *
	 * @param  null|string  $type
	 *
	 * @return bool
	 */
	public static function is_request( $type = null ) {
		$is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
		switch ( $type ) {
			case 'admin' :
				return is_admin() && ! $is_ajax;
			case 'ajax' :
				return $is_ajax;
			case 'cron' :
				return ( defined( 'DOING_CRON' ) && DOING_CRON );
			case 'public' :
				return ( ! is_admin() && ! $is_ajax );
		}

		return false;
	}

	/**
	 * Default settings
	 *
	 * @return array
	 */
	public static function getDefaultSettings() {
		return array(
			'sso'                         => 'false',
			'div_id'                      => '',
			'div_class'                   => '',
			'div_id_powerbar'             => '',
			'div_id_emotes'               => '',
			'div_class_powerbar'          => '',
			'div_class_powerbar2'         => '',
			'div_class_emotes'            => '',
			'lang'                        => '',
			'emote'                       => 'true',
			'save_comments'               => '0',
			'enabled_comments'            => 'true',
			'amount_comments'             => 1000,
			'embed_comments'              => '2',
			'embed_emotes'                => '0',
			'mobile_type'                 => 'vertical',
			'desktop_type'                => 'vertical',
			'embed_powerbar'              => '0',
			'embed_powerbar_vertical'     => '0',
			'div_class_powerbar_vertical' => '0',
			'email_for_export_comments'   => 'support@vuukle.com',
			'start_date_comments'         => gmdate( 'Y-m-d', strtotime( '-30 days' ) ),
			'end_date_comments'           => gmdate( 'Y-m-d' ),
			'non_article_pages'           => 'on',
			'web_push_notifications'      => 'off',
			'embed_emotes_amp'            => 'off',
			'share'                       => '1',
			'enable_h_v'                  => 'no',
			'share_position'              => '1',
			'share_position2'             => '1',
			'checkboxTextEnabled'         => false,
			'share_type'                  => 'horizontal',
			'share_type_vertical'         => 'vertical',
			'share_vertical_styles'       => 'position:fixed;z-index: 10001;width: 60px;max-width: 60px;left: 10px;top: 160px;',
			'emote_widget_width'          => '600',
			'post_exceptions'             => '',
			'category_exceptions'         => '',
			'post_type_exceptions'        => '',
			'post_type_by_url_exceptions' => '',
			'hide_chat'                   => 'true',
		);
	}

	/**
	 * This function ensures quick registration.
	 *
	 * @param  string  $app_id_setting_name
	 * @param  string  $log_dir
	 *
	 * @return mixed|null
	 */
	public static function quickRegister( $app_id_setting_name, $log_dir ) {
		$site_url = get_site_url();
		$args     = array(
			'name'     => str_replace( array( 'www.', 'http://', 'https://' ), '', $site_url ),
			'host'     => str_replace( array( 'www.', 'http://', 'https://' ), '', $site_url ),
			'avatar'   => '',
			'email'    => get_option( 'admin_email' ),
			'password' => rand( 0, 1000000 ),
		);
		$body     = json_encode( $args );
		$url      = 'https://api.vuukle.com/api/v1/Publishers/registerWP';
		$response = wp_remote_post( $url, array(
			'timeout' => 30,
			'headers' => array(
				'Content-Type'   => 'application/json',
				'Content-Length' => strlen( $body )
			),
			'body'    => $body, // request parameters in an array
		) );
		// Check log dir
		if ( ! is_dir( $log_dir ) ) {
			wp_mkdir_p( $log_dir );
		}
		if ( empty( $response ) || $response instanceof WP_Error ) {
			// Error
			if ( ! empty( $response ) && file_exists( $log_dir ) ) {
				file_put_contents( $log_dir . 'api_response.log', print_r( $response, true ), FILE_APPEND );
			}
		}
		$body_received_json = wp_remote_retrieve_body( $response );
		if ( empty( $body_received_json ) ) {
			// Error
			if ( ! empty( $response ) && file_exists( $log_dir ) ) {
				file_put_contents( $log_dir . 'api_response.log', print_r( $response, true ), FILE_APPEND );
			}
		}
		$output         = @json_decode( $body_received_json, true );
		$responseApiKey = ! empty( $output['data'] ) && ! empty( $output['data']['apiKey'] ) ? $output['data']['apiKey'] : null;
		if ( ! empty( $responseApiKey ) ) {
			update_option( $app_id_setting_name, $responseApiKey );
		} else {
			$url = 'https://api.vuukle.com/api/v1/WP/alertSupport?subjectLine=' . str_replace( array(
					'www.',
					'http://',
					'https://'
				), '', $site_url );
			// Proceed remote get
			$response = wp_remote_get( $url, array(
				'timeout' => 30,
				'headers' => array(
					'Content-Type' => 'application/json'
				)
			) );
			if ( empty( $response ) || $response instanceof WP_Error ) {
				// Error
				if ( ! empty( $response ) && file_exists( $log_dir ) ) {
					file_put_contents( $log_dir . 'api_response.log', print_r( $response, true ), FILE_APPEND );
				}
			}
			$body_received_json = wp_remote_retrieve_body( $response );
			if ( empty( $body_received_json ) ) {
				// Error
				if ( ! empty( $response ) && file_exists( $log_dir ) ) {
					file_put_contents( $log_dir . 'api_response.log', print_r( $response, true ), FILE_APPEND );
				}
			}
			$output = @json_decode( $body_received_json, true );
			// Log
			if ( ! empty( $output ) && file_exists( $log_dir ) ) {
				file_put_contents( $log_dir . 'api_response.log', print_r( $output, true ), FILE_APPEND );
			}
		}

		return $responseApiKey;
	}

	/**
	 * Method for cleaning directory
	 *
	 * @param $dir
	 */
	public static function cleanDir( $dir ) {
		$files = glob( $dir . '/*' );
		if ( count( $files ) > 0 ) {
			foreach ( $files as $file ) {
				if ( file_exists( $file ) ) {
					unlink( $file );
				}
			}
		}
	}

	/**
	 * Get AMP src
	 * in case AMP is available
	 *
	 * @param $settings
	 * @param $post
	 * @param $app_id
	 *
	 * @return string|null
	 */
	public static function getAmpSrcUrl( $settings, $post, $app_id ) {
		if ( self::checkAmpAvailability( $settings ) ) {
			$src_url    = "https://cdn.vuukle.com/amp.html?";
			$post_image = "";
			if ( has_post_thumbnail( $post->ID ) ) {
				$thumb_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
				$post_image  = isset( $thumb_image[0] ) ? $thumb_image[0] : '';
			}
			$post_tags_array = get_the_tags();
			$post_tags       = array();
			if ( $post_tags_array ) {
				foreach ( $post_tags_array as $taged ) {
					$post_tags[] = $taged->name;
				}
				$post_tags = implode( ',', $post_tags );
			} else {
				$post_tags = '';
			}
			$src_url_query = array(
				"url"    => wp_get_canonical_url( $post->ID ),
				"host"   => wp_parse_url( get_site_url() )['host'],
				"id"     => $post->ID,
				"apiKey" => $app_id,
				"img"    => $post_image,
				"title"  => urlencode( $post->post_title ),
				"tags"   => urlencode( $post_tags ),
			);
			$src_url_query = http_build_query( $src_url_query );
			$src_url       .= $src_url_query;

			return $src_url;
		} else {
			return null;
		}

	}

	/**
	 * Check AMP availability
	 *
	 * @param $settings
	 *
	 * @return bool
	 */
	public static function checkAmpAvailability( $settings ) {
		$amp_function = function_exists( 'amp_is_request' ) ? 'amp_is_request' : ( function_exists( 'is_amp_endpoint' ) ? 'is_amp_endpoint' : null );

		return ! empty( $amp_function ) && call_user_func( $amp_function ) && $settings['embed_emotes_amp'] != 'off';
	}

	/**
	 * Live checks weather app id field is empty
	 * and also app id field exists in other settings array , then move to separate field
	 *
	 * @since 5.0.3
	 */
	public static function upgradeLiveCheck() {
		$app_id = get_option( 'Vuukle_App_Id' );
		if ( $app_id == null ) {
			$settings = get_option( 'Vuukle' );
			if ( ! empty( $settings ) && is_array( $settings ) && ! empty( $settings['AppId'] ) ) {
				// Move to another row with option name Vuukle_App_Id
				add_option( 'Vuukle_App_Id', $settings['AppId'] );
				// Remove from main options array
				unset( $settings['AppId'] );
				update_option( 'Vuukle', $settings );
			}
		}
	}

}
