<?php
/**
 * Holds SocialFlow useful methods
 * This class is extended by SocialFlow class
 *
 * @package SocialFlow
 */

/**
 * Plugin Methods class
 *
 * This class holds useful methods commonly used. And is a parent class for SocialFlow class
 *
 * @since 0.1
 */
class SocialFlow_Methods {

	/**
	 * Check if user has authorized plugin
	 *
	 * @since 2.1
	 * @access public
	 *
	 * @return bool authorized or not
	 */
	public function is_authorized() {
		global $socialflow;


		return (bool) $socialflow->options->get( 'access_token', false );
	}

	/**
	 * Get SocialFlow api object, if necessary create new api object
	 * Arguments are not required, and if nothing is passed key and secret will be retrieved from options
	 *
	 * @since 2.1
	 * @access public
	 *
	 * @param string $token oauth token key.
	 * @param string $secret oauth token secret.
	 * @return object ( WP_SocialFlow | WP_Error ) return WP_SocialFlow object on success and WP_Error on failure
	 */
	public function get_api( $token = '', $secret = '' ) {
		global $socialflow;
		// Maybe create new api object.
		if ( ! isset( $socialflow->api ) ) {
			// Include api library.
			require_once SF_ABSPATH . '/libs/class-wp-socialflow.php';

			if ( $socialflow->options->get( 'access_token' ) && ( empty( $token ) || empty( $secret ) ) ) {
				$tokens = $socialflow->options->get( 'access_token' );
				$token  = $tokens['oauth_token'];
				$secret = $tokens['oauth_token_secret'];

			}

			// Catch error.

			$socialflow->api = new WP_SocialFlow( SF_KEY, SF_SECRET, $token, $secret );

		}


		return $socialflow->api;
	}

	/**
	 * Get new view object
	 *
	 * @since 2.0
	 * @access public
	 *
	 * @param string  $file file name.
	 * @param array   $data of global data available in template.
	 * @param boolean $renderer .
	 * @return object Plugin_View
	 */
	public function get_view( $file = null, array $data = null, $renderer = false ) {
		$view = new SF_Plugin_View( $file, $data );

		// Set directories.
		$view->set_abspath( SF_ABSPATH . '/' );
		$view->set_views_dirname( 'views' );
		$view->render( $renderer );

		return $view;
	}

	/**
	 * Get new view object
	 *
	 * @since 2.0
	 * @access public
	 *
	 * @param string $file file name.
	 * @param array  $data of global data available in template.
	 */
	public function render_view( $file = null, array $data = null ) {
		$this->get_view( $file, $data, true );
	}

	/**
	 * Parse statuses and return one WP_Error object of statuses
	 *
	 * @since 2.0
	 * @access public
	 *
	 * @param string $statuses serialized statuses.
	 * @return object WP_Error with all statuses
	 */
	public function parse_status( $statuses = '' ) {
		$return = new WP_Error();

		if ( empty( $statuses ) ) {
			return $return;
		}

		$statuses = maybe_unserialize( $statuses );
		if ( ! is_array( $statuses ) ) {
			return $return;
		}

		// Loop throug all statuses and explode each.
		foreach ( $statuses as $code => $status ) {

			// Add Error messages.
			$messages = is_array( $status[0] ) ? array_map( 'base64_decode', $status[0] ) : array();
			foreach ( $messages as $message ) {
				$return->add( $code, $message );
			}

			// Add Error data if needed.
			if ( $status[1] ) {
				$return->add_data( is_array( $status[1] ) ? array_map( 'base64_decode', $status[1] ) : base64_decode( $status[1] ), $code );
			}
		}
		return $return;
	}

	/**
	 * Compress WP_Error object to string
	 *
	 * @since 2.0
	 * @access public
	 *
	 * @param object $statuses WP_Error $statuses.
	 * @return string compressed statuses
	 */
	public function compress_status( $statuses = '' ) {
		if ( ! is_wp_error( $statuses ) ) {
			return '';
		}
		$return = array();
		$codes  = $statuses->get_error_codes();
		foreach ( $codes as $code ) {
			// Get all messages.
			$messages = $statuses->get_error_messages( $code ) ? array_map( 'base64_encode', $statuses->get_error_messages( $code ) ) : '';
			// Get all data.
			$data = '';
			if ( $statuses->get_error_data( $code ) ) {
				$data = is_array( $statuses->get_error_data( $code ) ) ? array_map( 'base64_encode', $statuses->get_error_data( $code ) ) : base64_encode( $statuses->get_error_data( $code ) );
			}
			// compress data and messages.
			$return[ $code ] = array( $messages, $data );
		}
		return maybe_serialize( $return );
	}

	/**
	 * Join array of statuses into one status
	 *
	 * @since 2.0
	 * @access public
	 *
	 * @param array                        $statuses of WP_Errors objects   $statuses.
	 * @param ( object | array of object ) $join_status second status to join may be single WP_Error object or array of WP_Error objects.
	 * @param string                       $sep .
	 * @return object WP_Error
	 */
	public function join_errors( $statuses = array(), $join_status = null, $sep = '' ) {
		$return = new WP_Error();

		// If multiple arguments were passed join different wp errors.
		if ( ! empty( $join_status ) ) {
			if ( is_array( $statuses ) ) {
				$statuses[] = $join_status;
			} else {
				$statuses = array( $statuses, $join_status );
			}
		}

		if ( empty( $statuses ) ) {
			return $return;
		}

		// Loop through statuses.
		foreach ( $statuses as $status ) :
			// Skip empty statuses.
			if ( ! is_wp_error( $status ) || ! $status->get_error_codes() ) {
				continue;
			}

			foreach ( $status->get_error_codes() as $code ) :
				// Add messages first.
				$messages = $status->get_error_messages( $code );

				// we need only unique messages.
				if ( in_array( $code, $return->get_error_codes(), true ) ) {
					$messages = array_diff( $messages, $return->get_error_messages( $code ) );
				}
				// add messages if they present.
				if ( ! empty( $messages ) ) {
					foreach ( $messages as $message ) {
						$return->add( $code, $message );
					}
				}

				// Add code data.
				$data = $status->get_error_data( $code );

				// Join return data and our data.
				if ( ! empty( $data ) && $return->get_error_data( $code ) ) {
					// add new data according to return data type.
					if ( is_array( $return->get_error_data( $code ) ) ) {
						// passed data is array.
						$data = array_merge( $data, $return->get_error_data( $code ) );
					} elseif ( is_array( $data ) ) {
						$data[] = $return->get_error_data( $code );
					} elseif ( is_array( $return->get_error_data( $code ) ) ) {
						$data = array_push( $return->get_error_data( $code ), $data );
					} elseif ( is_string( $data ) && is_string( $return->get_error_data( $code ) ) ) {
						$data = $return->get_error_data( $code ) . $sep . $data;
					}
				}

				if ( ! empty( $data ) ) {
					$return->add_data( $data, $code );
				}

			endforeach; // Loop for each code inside status.

		endforeach; // Loop for each passed statuses.

		return $return;
	}

	/**
	 * Merges arrays recursively, replacing duplicate string keys
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return array
	 */
	public function array_merge_recursive() {
		$args = func_get_args();

		$result = array_shift( $args );

		foreach ( $args as $arg ) {
			foreach ( $arg as $key => $value ) {
				// Renumber numeric keys as array_merge() does.
				if ( is_numeric( $key ) ) {
					if ( ! in_array( $value, $result, true ) ) {
						$result[] = $value;
					}
				} // Recurse only when both values are arrays.
				elseif ( array_key_exists( $key, $result ) && is_array( $result[ $key ] ) && is_array( $value ) ) {
					$result[ $key ] = self::array_merge_recursive( $result[ $key ], $value );
				} // Otherwise, use the latter value.
				else {
					$result[ $key ] = $value;
				}
			}
		}
		return $result;
	}

	/**
	 * Save Errors
	 *
	 * @param object $key wp_error object to save.
	 * @param string $error error key.
	 * @return object $error wp_error
	 */
	public function save_errors( $key, $error ) {
		global $socialflow;

		$socialflow->errors[ $key ] = $error;

		// Remove previous errors for this key.
		$this->clear_errors( $key );

		// Save transient.
		$status = set_transient( "sf_error_{$key}", $error, 60 * 5 );

		return $error;
	}

	/**
	 * Clear errors
	 *
	 * @param string $key .
	 */
	public function clear_errors( $key ) {
		delete_transient( "sf_error_{$key}" );
	}

	/**
	 * Retrieve error by key
	 *
	 * @param string $key error key.
	 * @return mixed ( object WP_Error | false )
	 */
	public function get_errors( $key = '' ) {
		global $socialflow;

		if ( isset( $socialflow->errors[ $key ] ) ) {
			return $socialflow->errors[ $key ];
		} else {
			// Try to get error from transient.
			$error = get_transient( 'sf_error_' . $key );
			if ( $error ) {
				$socialflow->errors[ $key ] = $error;
				return $error;
			}
		}

		return false;
	}

	/**
	 * Check if we are on passed SocialFlow settings page
	 *
	 * @since 2.0
	 * @access public
	 *
	 * @param string $pagename page name to check for.
	 * @return bool is target page
	 */
	public function is_page( $pagename ) {
		global $current_screen;
		$socialflow_params_get  = filter_input_array( INPUT_GET );
		$socialflow_params_post = filter_input_array( INPUT_POST );
		$cur_page               = '';

		if ( isset( $socialflow_params_post ) && isset( $socialflow_params_post['socialflow-page'] ) ) {
			$cur_page = $socialflow_params_post['socialflow-page'];
		} elseif ( isset( $socialflow_params_get ) && isset( $socialflow_params_get['socialflow'] ) ) {
			$cur_page = $socialflow_params_get['page'];
		} elseif ( strpos( $current_screen->id, 'socialflow_page_' ) === 0 ) {
			$cur_page = substr( $current_screen->id, strlen( 'socialflow_page_' ) );
		}

		return $cur_page === $pagename;
	}

	/**
	 * Before output errors
	 *
	 * @since 2.0
	 * @access public
	 *
	 * @param object $errors - WP_Error.
	 * @return object - filtered errors
	 */
	public function filter_errors( $errors ) {
		global $socialflow;

		if ( ! is_wp_error( $errors ) ) {
			return $errors;
		}

		if ( ! $errors->get_error_messages() ) {
			return $errors;
		}

		$filtered_errors = new WP_Error();

		// loop through passed errors object codes filter messages and add them to the new object.
		foreach ( $errors->get_error_codes() as $code ) {

			foreach ( $errors->get_error_messages( $code ) as $message ) {
				if ( 'http_request_failed' === $code ) {
					$filtered_errors->add( $code, __( '<b>Error:</b> Server connection timed out. Please, try again.', 'socialflow' ) );
					break;
				}

				$data = $errors->get_error_data( $code );

				// Check if there is some error data.
				if ( empty( $data ) ) {
					$filtered_errors->add( $code, $message );
					continue;
				}
				$name     = $socialflow->accounts->get_display_name( absint( $data ) );
				$accounts = $socialflow->accounts->get( $data );
				// Error data may contain accounts ids.
				if ( is_array( $data ) && $accounts ) {

					// Get string with accounts display names.
					$names = array();
					foreach ( $accounts as $account ) {
						$names[] = $socialflow->accounts->get_display_name( $account );
					}

					// Add formatted error message.
					if ( strpos( $message, '%s' ) ) {
						$filtered_errors->add( $code, sprintf( $message, implode( ', ', $names ) ) );
					} else {
						$filtered_errors->add( $code, $message . ' (' . implode( ', ', $names ) . ') ' );
					}
				} elseif ( absint( $data ) && $name ) {

					if ( strpos( $message, '%s' ) ) {
						$filtered_errors->add( $code, sprintf( $message, $name ) );
					} else {
						$filtered_errors->add( $code, $message . ' (' . $name . ') ' );
					}
				} else {
					if ( strpos( $message, '%s' ) ) {
						$filtered_errors->add( $code, sprintf( $message, $data ) );
					} else {
						// Add as it is.
						$filtered_errors->add( $code, $message, $data );
					}
				} // Not accounts data

			} // Messages loop

		} // Error codes loop

		return $filtered_errors;
	}

	/**
	 * Cut Strings (detects words)
	 *
	 * @param string $string .
	 * @param int    $max_length .
	 * @return string
	 */
	public function trim_chars( $string, $max_length = 5000 ) {
		$string = strip_tags( $string );
		if ( strlen( $string ) > $max_length ) {
			$string = substr( $string, 0, $max_length );
			$pos    = strrpos( $string, ' ' );
			if ( false === $pos ) {
					return substr( $string, 0, $max_length ) . '...';
			}
				return substr( $string, 0, $pos ) . '...';
		} else {
			return $string;
		}
	}

	/**
	 * Add local images url query
	 *
	 * @param string $url .
	 * @return string
	 * @since 2.7
	 */
	public function get_output_image_url( $url = '' ) {
		global $socialflow;

		if ( ! $url ) {
			return $url;
		}

		$query = $socialflow->options->get( 'image_url_query' );

		if ( ! $query ) {
			return $url;
		}

		$upload_dir = wp_upload_dir();

		if ( false === strpos( $url, $upload_dir['baseurl'] ) ) {
			return $url;
		}

		parse_str( $query, $arr );

		return add_query_arg( $arr, $url );
	}

	/**
	 * Get image id by image url
	 *
	 * @param string $url url to image.
	 * @return int
	 */
	public function get_image_id_by_url( $url = '' ) {
		global $wpdb;

		// checck if image is in upload dir.
		$uploads = wp_upload_dir();

		if ( false === strpos( $url, $uploads['baseurl'] ) ) {
			return 0;
		}

		// remove home url from image url.
		$url = substr( $url, strlen( home_url() ), ( strlen( $url ) - strlen( home_url() ) ) );

		// search for image id.
		$id = wp_cache_get( $url );
		if ( ! $id ) {
			$id = $wpdb->get_var( $wpdb->prepare( "SELECT ID $wpdb->posts FROM  WHERE guid LIKE %d LIMIT 1", [ "%$url%" ] ) );
			wp_cache_set( $url, $id );
		}

		return absint( $id );
	}

	/**
	 * Get image id by image url
	 *
	 * @return bool
	 */
	public function is_ajax() {
		return ( defined( 'DOING_AJAX' ) && DOING_AJAX );
	}

	/**
	 * Check localhost for debug
	 */
	public function is_localhost() {
		$socialflow_params = filter_input_array( INPUT_SERVER );
		if ( 'localhost' === $socialflow_params['HTTP_HOST'] ) {
			return true;
		}

		if ( isset( $socialflow_params['MYSQL_HOME'] ) ) {
			if ( false !== strpos( $socialflow_params['MYSQL_HOME'], 'xampp' ) ) {
				return true;
			}
		}

		return false;
	}
}
