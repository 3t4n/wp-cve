<?php
/** This file contains utility functions.
 *
 * @package miniorange-login-security/handler/twofa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'Momls_Utility' ) ) {
	/**
	 * This library is miniOrange Authentication Service.
	 * Contains Request Calls to Customer service.
	 **/
	class Momls_Utility {
		/**
		 * Checking empty or not.
		 *
		 * @param string $value It will return the val .
		 * @return boolean
		 */
		public static function momls_check_empty_or_null( $value ) {
			if ( ! isset( $value ) || empty( $value ) ) {
				return true;
			}
			return false;
		}
		/**
		 * Return the installed plugin name.
		 *
		 * @return void
		 */
		public static function momls_get_all_plugins_installed() {
			$all_plugins     = get_plugins();
			$plugins         = array();
			$form            = '';
			$plugins['None'] = 'None';
			foreach ( $all_plugins as $plugin_name => $plugin_details ) {
				$plugins[ $plugin_name ] = $plugin_details['Name'];
			}
			unset( $plugins['miniorange-login-security/miniorange-2-factor-settings.php'] );
			echo '<div class="mo2f_plugin_select">Please select the plugin<br>
			<select name="mo2f_plugin_selected" id="mo2f-plugin-selected">';
			foreach ( $plugins as $identifier => $name ) {
				echo '<option value="' . esc_attr( $identifier ) . '">' . esc_attr( $name ) . '</option>';
			}
			echo '</select></div>';
		}
		/**
		 * It will check the length of the number.
		 *
		 * @param string $token It will carry the token.
		 * @return boolean
		 */
		public static function momls_check_number_length( $token ) {
			if ( is_numeric( $token ) ) {
				if ( strlen( $token ) >= 4 && strlen( $token ) <= 8 ) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
		/**
		 * Checking the device name
		 *
		 * @param string $useragent It will carry the user agent .
		 * @return boolean
		 */
		public static function momls_check_if_request_is_from_mobile_device( $useragent ) {
			if ( preg_match( '/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent ) || preg_match( '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr( $useragent, 0, 4 ) ) ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * This function will set the user value.
		 *
		 * @param string $user_session_id It will carry the session .
		 * @param string $variable It will carry the variable .
		 * @param string $value It will carry the value .
		 * @return void
		 */
		public static function momls_set_user_values( $user_session_id, $variable, $value ) {
			global $momlsdb_queries;
			$key         = get_site_option( 'mo2f_encryption_key' );
			$data_option = get_site_option( 'mo2f_data_storage' );
			if ( empty( $data_option ) ) {
				$_SESSION[ $variable ] = $value;

				// setting cookie values.
				if ( is_array( $value ) ) {
					if ( 'mo_2_factor_kba_questions' === $variable ) {
						self::momls_set_cookie_values( 'kba_question1', $value[0] );
						self::momls_set_cookie_values( 'kba_question2', $value[1] );
					}
				} else {
					self::momls_set_cookie_values( $variable, $value );
				}

				// setting values in database.
				$user_session_id = self::momls_decrypt_data( $user_session_id, $key );
				if ( is_array( $value ) ) {
					$string_value = maybe_serialize( $value );
					$momlsdb_queries->save_user_login_details( $user_session_id, array( $variable => $string_value ) );
				} else {
					$momlsdb_queries->save_user_login_details( $user_session_id, array( $variable => $value ) );
				}
			} elseif ( ! empty( $data_option ) && 'sessions' === $data_option ) {

				$_SESSION[ $variable ] = $value;

			} elseif ( ! empty( $data_option ) && 'cookies' === $data_option ) {

				if ( is_array( $value ) ) {
					if ( 'mo_2_factor_kba_questions' === $variable ) {
						self::momls_set_cookie_values( 'kba_question1', $value[0] );
						self::momls_set_cookie_values( 'kba_question2', $value[1] );
					}
				} else {
					self::momls_set_cookie_values( $variable, $value );
				}
			} elseif ( ! empty( $data_option ) && 'tables' === $data_option ) {
				$user_session_id = self::momls_decrypt_data( $user_session_id, $key );
				if ( is_array( $value ) ) {
					$string_value = maybe_serialize( $value );
					$momlsdb_queries->save_user_login_details( $user_session_id, array( $variable => $string_value ) );
				} else {
					$momlsdb_queries->save_user_login_details( $user_session_id, array( $variable => $value ) );
				}
			}
		}
		/**
		 * This function will help to decrypt the data .
		 *
		 * @param string $data It will carry the data .
		 * @param string $key It will carry the key .
		 * @return string
		 */
		public static function momls_decrypt_data( $data, $key ) {
			$c                  = base64_decode( $data ); //phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode -- Not using for obfuscation
			$cipher             = 'AES-128-CBC';
			$ivlen              = openssl_cipher_iv_length( $cipher );
			$iv                 = substr( $c, 0, $ivlen );
			$hmac               = substr( $c, $ivlen, $sha2len = 32 );
			$ciphertext_raw     = substr( $c, $ivlen + $sha2len );
			$original_plaintext = openssl_decrypt( $ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv );
			$calcmac            = hash_hmac( 'sha256', $ciphertext_raw, $key, $as_binary = true );
			$decrypted_text     = '';
			if ( is_string( $hmac ) && is_string( $calcmac ) ) {
				if ( hash_equals( $hmac, $calcmac ) ) {
					$decrypted_text = $original_plaintext;
				}
			}
			return $decrypted_text;
		}
		/**
		 * This function to generate the random string .
		 *
		 * @param string $length It will carry the length .
		 * @param string $keyspace It will carry thye key .
		 * @return string
		 */
		public static function momls_random_str( $length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' ) {
			$momls_random_string = '';
			$characters_length   = strlen( $keyspace );
			$keyspace            = $keyspace . microtime( true );
			$keyspace            = str_shuffle( $keyspace );
			for ( $i = 0; $i < $length; $i ++ ) {
				$momls_random_string .= $keyspace[ wp_rand( 0, $characters_length - 1 ) ];
			}

			return $momls_random_string;

		}

		/**
		 * It is to retrieve the user temp value.
		 *
		 * @param string $variable .
		 * @param string $session_id It will get the session id .
		 * @return string
		 */
		public static function momls_retrieve_user_temp_values( $variable, $session_id = null ) {
			global $momlsdb_queries;
			$data_option = get_site_option( 'mo2f_data_storage' );
			if ( empty( $data_option ) ) {
				if ( isset( $_SESSION[ $variable ] ) && ! empty( $_SESSION[ $variable ] ) ) {
					update_site_option( 'mo2f_data_storage', 'sessions' );
					return $_SESSION[ $variable ];
				} else {
					$key          = get_site_option( 'mo2f_encryption_key' );
					$cookie_value = false;
					if ( 'mo_2_factor_kba_questions' === $variable ) {
						if ( isset( $_COOKIE['kba_question1'] ) && ! empty( $_COOKIE['kba_question1'] ) ) {
							$kba_question1 = self::momls_get_cookie_values( 'kba_question1' );
							$kba_question2 = self::momls_get_cookie_values( 'kba_question2' );
							$cookie_value  = array( $kba_question1, $kba_question2 );
						}
					} else {
						$cookie_value = self::momls_get_cookie_values( $variable );
					}
					if ( $cookie_value ) {
						update_site_option( 'mo2f_data_storage', 'cookies' );
						return $cookie_value;
					} else {
						$session_id = self::momls_decrypt_data( $session_id, $key );
						$db_value   = $momlsdb_queries->get_user_login_details( $variable, $session_id );
						if ( in_array( $variable, array( 'mo2f_rba_status', 'mo_2_factor_kba_questions' ), true ) ) {
							$db_value = maybe_unserialize( $db_value );
						}
						update_site_option( 'mo2f_data_storage', 'tables' );
						return $db_value;
					}
				}
			} elseif ( ! empty( $data_option ) && 'sessions' === $data_option ) {
				if ( isset( $_SESSION[ $variable ] ) && ! empty( $_SESSION[ $variable ] ) ) {
					return $_SESSION[ $variable ];
				}
			} elseif ( ! empty( $data_option ) && 'cookies' === $data_option ) {
				$key          = get_site_option( 'mo2f_encryption_key' );
				$cookie_value = false;

				if ( 'mo_2_factor_kba_questions' === $variable ) {

					if ( isset( $_COOKIE['kba_question1'] ) && ! empty( $_COOKIE['kba_question1'] ) ) {
						$kba_question1 = self::momls_get_cookie_values( 'kba_question1' );
						$kba_question2 = self::momls_get_cookie_values( 'kba_question2' );
						$cookie_value  = array( $kba_question1, $kba_question2 );
					}
				} else {
					$cookie_value = self::momls_get_cookie_values( $variable );
				}

				if ( $cookie_value ) {
					return $cookie_value;
				}
			} elseif ( ! empty( $data_option ) && 'tables' === $data_option ) {
				$key        = get_site_option( 'mo2f_encryption_key' );
				$session_id = self::momls_decrypt_data( $session_id, $key );
				$db_value   = $momlsdb_queries->get_user_login_details( $variable, $session_id );
				if ( in_array( $variable, array( 'mo2f_rba_status', 'mo_2_factor_kba_questions' ), true ) ) {
					$db_value = maybe_unserialize( $db_value );
				}
				return $db_value;
			}
		}

		/**
		 * The function gets the cookie value after decoding and decryption.
		 *
		 * @param string $cookiename - It will carry the cookie name .
		 *
		 * @return string
		 */
		public static function momls_get_cookie_values( $cookiename ) {

			$key = get_site_option( 'mo2f_encryption_key' );
			if ( isset( $_COOKIE[ $cookiename ] ) ) {
				$decrypted_data = self::momls_decrypt_data( base64_decode( sanitize_key( wp_unslash( $_COOKIE[ $cookiename ] ) ) ), $key ); //phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode -- Not using for obfuscation
				if ( $decrypted_data ) {
					$decrypted_data_array = explode( '&', $decrypted_data );
					$cookie_value         = $decrypted_data_array[0];
					$cookie_creation_time = new DateTime( $decrypted_data_array[1] );
					$current_time         = new DateTime( 'now' );
					$interval             = $cookie_creation_time->diff( $current_time );
					$minutes              = $interval->format( '%i' );
					$is_cookie_valid      = $minutes <= 5 ? true : false;
					return $is_cookie_valid ? $cookie_value : false;

				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		/**
		 * The function sets the cookie value after encryption and encoding.
		 *
		 * @param string $cookiename - It will store the cookie name .
		 * @param string $cookievalue - the cookie value to be set .
		 *
		 * @return void
		 */
		public static function momls_set_cookie_values( $cookiename, $cookievalue ) {
			$key = get_option( 'mo2f_encryption_key' );

			$current_time = new DateTime( 'now' );
			$current_time = $current_time->format( 'Y-m-d H:i:sP' );
			$cookievalue  = $cookievalue . '&' . $current_time;

			$cookievalue_encrypted  = self::momls_encrypt_data( $cookievalue, $key );
			$_COOKIE[ $cookiename ] = base64_encode( $cookievalue_encrypted ); //phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- Not using for obfuscation
		}

		/**
		 * It will help to encrypt the data in aes
		 *
		 * @param string $data It will pass the data of the value .
		 * @param string $key  It will pass the key of the value .
		 * @return string .
		 */
		public static function momls_encrypt_data( $data, $key ) {
			$plaintext      = $data;
			$cipher         = 'AES-128-CBC';
			$ivlen          = openssl_cipher_iv_length( $cipher );
			$iv             = openssl_random_pseudo_bytes( $ivlen );
			$ciphertext_raw = openssl_encrypt( $plaintext, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv );
			$hmac           = hash_hmac( 'sha256', $ciphertext_raw, $key, $as_binary = true );
			$ciphertext     = base64_encode( $iv . $hmac . $ciphertext_raw ); //phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- Not using for obfuscation
			return $ciphertext;
		}

		/**
		 * It will unset the session value
		 *
		 * @param object $variables .
		 * @return void
		 */
		public static function momls_unset_session_variables( $variables ) {
			if ( gettype( $variables ) === 'array' ) {
				foreach ( $variables as $variable ) {
					if ( isset( $_SESSION[ $variable ] ) ) {
						unset( $_SESSION[ $variable ] );
					}
				}
			} else {
				if ( isset( $_SESSION[ $variables ] ) ) {
					unset( $_SESSION[ $variables ] );
				}
			}
		}

		/**
		 * This function is invoke to unset the cookie variable .
		 *
		 * @param mixed $variables .
		 * @return void
		 */
		public static function momls_unset_cookie_variables( $variables ) {

			if ( gettype( $variables ) === 'array' ) {
				foreach ( $variables as $variable ) {
					if ( isset( $_COOKIE[ $variable ] ) ) {
						setcookie( $variable, '', time() - 3600, null, null, null, true );
					}
				}
			} else {
				if ( isset( $_COOKIE[ $variables ] ) ) {
					setcookie( $variables, '', time() - 3600, null, null, null, true );
				}
			}

		}

		/**
		 * This function is invoke to unset the temporaray user detail
		 *
		 * @param string $variables .
		 * @param string $session_id It will carry the session id .
		 * @param string $command It will carry the command message .
		 * @return void
		 */
		public static function momls_unset_temp_user_details_in_table( $variables, $session_id, $command = '' ) {
			global $momlsdb_queries;
			$key        = get_site_option( 'mo2f_encryption_key' );
			$session_id = self::momls_decrypt_data( $session_id, $key );
			if ( 'destroy' === $command ) {
				$momlsdb_queries->delete_user_login_sessions( $session_id );
			} else {
				$momlsdb_queries->save_user_login_details( $session_id, array( $variables => '' ) );
			}
		}
		/**
		 * This function is invoke to decode the 2 factor method
		 *
		 * @param string $selected_2_factor_method It will carry the selected two factor method .
		 * @param string $decode_type It will carry the decode type .
		 * @return string
		 */
		public static function momls_decode_2_factor( $selected_2_factor_method, $decode_type ) {

			if ( 'NONE' === $selected_2_factor_method ) {
				return $selected_2_factor_method;
			}

			$wpdb_2fa_methods = array(
				'GoogleAuthenticator' => 'Google Authenticator',
				'AuthyAuthenticator'  => 'Authy Authenticator',
				'SecurityQuestions'   => 'Security Questions',
			);

			$server_2fa_methods = array(
				'Google Authenticator' => 'GOOGLE AUTHENTICATOR',
				'Authy Authenticator'  => 'GOOGLE AUTHENTICATOR',
				'Security Questions'   => 'KBA',
			);

			$server_to_wpdb_2fa_methods = array(
				'GOOGLE AUTHENTICATOR' => 'Google Authenticator',
				'KBA'                  => 'Security Questions',
			);

			if ( 'wpdb' === $decode_type ) {
				return $wpdb_2fa_methods[ $selected_2_factor_method ];
			} elseif ( 'server' === $decode_type ) {
				return $server_2fa_methods[ $selected_2_factor_method ];
			} else {
				return $server_to_wpdb_2fa_methods[ $selected_2_factor_method ];
			}

		}
		/**
		 * Get plugin name by identifier
		 *
		 * @param string $plugin_identitifier .
		 * @return string .
		 */
		public static function momls_get_plugin_name_by_identifier( $plugin_identitifier ) {
			$all_plugins    = get_plugins();
			$plugin_details = $all_plugins[ $plugin_identitifier ];

			return $plugin_details['Name'] ? $plugin_details['Name'] : 'No Plugin selected';
		}

	}
}


