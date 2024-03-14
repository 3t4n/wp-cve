<?php

if ( ! class_exists( 'ContentAd__Includes__API' ) ) {

	class ContentAd__Includes__API {

		private static
			$api_key = false,
			$installation_key = false,
			$base_url = false;

		private static function set_properties() {
			if( false == self::$api_key || false == self::$base_url ) {
				self::$api_key = self::get_api_key();
				self::$installation_key = self::get_installation_key();
				self::$base_url = CONTENTAD_API_URL;
			}
		}

		private static function http_request( $url, $method = 'get' ) {
			contentAd_append_to_log( 'METHOD: ' . $method );
			contentAd_append_to_log( '    URL: ' . $url );

			$args = array('timeout'=>'30','sslverify' => false);
			if( 'post' == $method ) {
				$response = wp_remote_post( $url, $args );
			} else {
				$response = wp_remote_get( $url, $args );
			}
			if( is_wp_error( $response ) ) {
				contentAd_append_to_log( '    ERROR RESPONSE: ' . $response->get_error_message() );
			}
			$code = wp_remote_retrieve_response_code( $response );
			contentAd_append_to_log( '    RESPONSE CODE: ' . $code );
			if( 200 == $code ) {
				$body = wp_remote_retrieve_body( $response );
				contentAd_append_to_log( '    MESSAGE: ' . $body );
				$message = (array) json_decode( $body );
				$success = array( 'success', 'Active', 'Pending' );
				if( isset( $message['result'] ) && in_array( $message['result'], $success ) ) {
					return $message;
				}
			}
			contentAd_append_to_log( $contentad_log );
			return false;
		}

		public static function get_api_key() {
			return get_option( 'contentad_api_key' );
		}

		public static function get_domains() {
			self::set_properties();
			$url = self::$base_url . '/Domains/' . self::$api_key;
			$response = self::http_request( $url );
			if( is_array( $response ) ) {
				return $response['domains'];
			}
			return false;
		}

		public static function validate_api_key( $api_key ) {
			self::set_properties();
			if( !isset( self::$installation_key ) ) {
				self::$installation_key = self::get_installation_key();
			}
			if( self::$api_key ) {
				$url = self::$base_url . '/Affiliate/' . $api_key;
				$response = self::http_request( $url );
				if( is_array( $response ) ) {
					if( empty( $response['installation_key'] ) ) {
						self::update_installation_key();
					}
					self::set_api_key( $api_key );
					return true;
				}
			}
			return false;
		}

		public static function get_ad_units() {
			self::set_properties();
			if( self::$api_key ) {
				$query = http_build_query( array(
					'domain' => home_url(),
				) );
				$url = self::$base_url . '/AdUnits/' . self::$api_key . '?' . $query;
				$response = self::http_request( $url );
				if( is_array( $response ) ) {
					return $response['adunits'];
				}
				return false;
			} else {  // Delete ad widgets if no API key is present
				return array();
			}
		}

		public static function get_ads( $unit_id ) {
			self::set_properties();
			$url = self::$base_url . '/Ads/' . self::$api_key . '/' . $unit_id;
			$response = self::http_request( $url );
			if( is_array( $response ) ) {
				return $response['ads'];
			}
			return false;
		}
		
		public static function delete_ad( $ad_id ) {
			self::set_properties();
			contentAd_append_to_log( 'DELETING REMOTE WIDGET: ' . $ad_id );
			$query = http_build_query( array(
				'adunit_name' => 'newname',
				'status' => 'Deleted'
			) );
			$url = self::$base_url . '/AdUnit/' . self::$api_key . '/' . $ad_id . '/?' . $query;
			$response = self::http_request( $url );
			return self::http_request( $url, 'post' );
		}

		public static function get_installation_key() {
			$install_key = get_option('contentad_install_key');
            if( ! $install_key ) {
                $install_key = md5( home_url() );
				self::set_install_key( $install_key );
            }
			return $install_key;
		}

		public static function update_installation_key() {
			self::set_properties();
			contentAd_append_to_log( 'UPDATING INSTALLATION KEY' );
			$query = http_build_query( array(
				'installkey' => self::get_installation_key(),
			) );
			$url = self::$base_url . '/Affiliate/' . self::$api_key . '?' . $query;
			$response = self::http_request( $url, 'post' );
			if( is_array( $response ) ) {
				contentAd_append_to_log( 'INSTALLATION KEY SUCCESSFULLY UPDATED ON REMOTE SERVER' );
				return true;
			}
			return false;
		}

		public static function set_install_key( $install_key ) {
			contentAd_append_to_log( 'SAVING INSTALL KEY: ' . $install_key );
			update_option( 'contentad_install_key', $install_key );
			self::$installation_key = $install_key;
		}

		public static function validate_installation_key() {
			self::set_properties();
			if( self::$api_key ) {
				return self::$api_key;
			} else {
				$url = self::$base_url . '/Validate/' . self::get_installation_key();
				$response = self::http_request( $url );
				if( is_array( $response ) && isset( $response['apikey'] ) ) {
					contentAd_append_to_log( 'INSTALLATION KEY IS VALID' );
					return self::set_api_key( $response['apikey'] );
				}
				return false;
			}
		}
		
		public static function set_api_key( $api_key ) {
			contentAd_append_to_log( 'SAVING API KEY: ' . $api_key );
			self::$api_key = $api_key;
			return update_option( 'contentad_api_key', $api_key );
		}

		public static function get_ad_code( $args = array() ) {
			$ad_code = '';
			$placement = 'after_post_content';
			/**
			 * If we have a valid API key, then fetch code to display
			 */
			if( $api_key = ContentAd__Includes__API::get_api_key() ) {
				/**
				 * Fetch local contentAd widgets by placement
				 */
				if( count($args) < 1 ) {
					$args = array( 'meta_query' => array( 'placement' => array( 'key' => 'placement', 'value' => $placement ) ) );
				} else {
					$widget_placement = $args['meta_query']['placement']['value'];
					if( $widget_placement ) {
						$placement = $widget_placement;
					}
				}
				$local = ContentAd__Includes__Init::get_local_widgets( $args );
				/**
				 * If there are ads that match this placement, loop through and display them
				 */
				if ( $local ) {
					contentAd_append_to_log( 'LOCAL WIDGETS AVAILABLE FOR DISPLAY:' );
					foreach ( $local as $widget ) {
						/**
						 * Add widget code to output
						 */
						$ad_code .= self::get_code_for_single_ad( $widget->ID, $widget->placement );
						contentAd_append_to_log( 'OUTPUT AD CODE: ' . $ad_code . PHP_EOL );
					}
				}
			}
			return $ad_code;
		}

		public static function get_code_for_single_ad( $post_id, $placement = '' ) {
			if( ( $post = get_post( $post_id ) ) && 'content_ad_widget' == get_post_type( $post ) ) {
				contentAd_append_to_log( '    WIDGET ID: ' . $post->ID );

				/**
				 * Don't show ads whose placement does not match the database
				 */
				if( $placement != get_post_meta( $post->ID, 'placement', true ) ) {
					contentAd_append_to_log( '        WIDGET NOT DISPLAYED BECAUSE THE PLACEMENT IS ' . $placement . ' AND NOT ' . get_post_meta( $post->ID, 'placement', true ) );
					return false;
				}

				/**
				 * Never show inactive ContentAd widgets
				 */
				if( get_post_meta( $post->ID, '_ca_widget_inactive', true ) ) {
					contentAd_append_to_log( '        WIDGET NOT DISPLAYED BECAUSE IT IS INACTIVE' );
					return false;
				}

				/**
				 * Don't show ads that aren't set to display on category and tag pages
				 */
				if( ( is_category() || is_tag() ) && ! get_post_meta( $post->ID, '_ca_display_cat_tag', true ) ) {
					contentAd_append_to_log( '        WIDGET NOT DISPLAYED DUE TO CAT/TAG DISPLAY NOT SET' );
					return false;
				}

				/**
				 * Don't show ads that aren't set to display on the homepage
				 */
				if( is_home() && ! get_post_meta( $post->ID, '_ca_display_home', true ) ) {
					contentAd_append_to_log( '        WIDGET NOT DISPLAYED DUE TO HOMEPAGE DISPLAY NOT SET' );
					return false;
				}

				/**
				 * Don't show ads when we are on excluded categories
				 */
				$excluded_categories = get_post_meta( $post->ID, '_excluded_categories', true );
				if( $excluded_categories && is_array( $excluded_categories ) ) {
					if( in_category( $excluded_categories ) ) {
						contentAd_append_to_log( '        WIDGET NOT DISPLAYED DUE TO CATEGORY EXCLUSION' );
						return false;
					}
				}

				/**
				 * Don't show ads when we are on excluded tags
				 */
				$excluded_tags = get_post_meta( $post->ID, '_excluded_tags', true );
				if( $excluded_tags ) {
					if( has_term( explode(',', preg_replace( '/, /', ',', strip_tags( $excluded_tags ) ) ), 'post_tag' ) ) {
						contentAd_append_to_log( '        WIDGET NOT DISPLAYED DUE TO TAG EXCLUSION' );
						return false;
					}

				}
				/**
				 * Prepare the query string
				 */
				$query = array(
					'id' => get_post_meta( $post->ID, '_widget_guid', true ),
					'd' => base64_encode( preg_replace( '/https?:\/\/(www.)?/i', '', home_url() ) ),
					'wid' => get_post_meta( $post->ID, '_widget_id', true ),
					'ct' => 'wordpress',
					'cb' => round((microtime(true)*1000))
				);
				/**
				 * Add array parameters if widget is an exit pop
				 */
				if( $placement == 'in_exit_pop' ) {
					$query += array(
						"exitPop" => 'true'
					);
				} elseif( $placement == 'in_mobile_exit_pop' ) {
					$query += array(
						"exitPopMobile" => 'true'
					);
				}
				/**
				 * Exlclude current widget 
				 */
				if( is_singular() && ! is_attachment() ) {
					//$query['pubdate'] = $post->post_date;
				} else {
					$query['serve'] = 0;
				}
				$query_string = http_build_query( $query );

				/**
				 * Return the generated code
				 */
				wp_register_script('contentad-script-'.$query['wid'],'//api.content.ad/Scripts/widget2.aspx?'.$query_string,array(),CONTENTAD_VERSION,true);
				wp_enqueue_script('contentad-script-'.$query['wid']);
				return "\r\n".'<div id="contentad'.$query['wid'].'"></div>'."\r\n";
			}
			return false;

		}

	}

}