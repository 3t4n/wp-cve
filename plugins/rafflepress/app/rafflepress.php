<?php
/*
 * Get Giveaway Comment Multiple Urls data for logged user.
 */
function rafflepress_lite_giveaway_comment() {

	global $wpdb;
	$contestant          = array();
	$mutiple_comment_url = array();

	if ( ! empty( $_POST['id'] ) && $_POST['id'] != 'undefined' && $_POST['id'] != 'null' ) {

			$contestant_id    = $_POST['id'];
			$contestant_email = $_POST['email'];
			$contestant_hash  = $_POST['id'] . '|' . $_POST['email'];

			$comment_label = '%' . sanitize_text_field( 'Leave a Comment' ) . '%';

			// Get completed actions
			$tablename         = $wpdb->prefix . 'rafflepress_entries';
			$sql               = "SELECT created_at , action_id, meta , CONVERT_TZ(`created_at`, @@session.time_zone, '+00:00') AS `utc_datetime` FROM $tablename WHERE contestant_id = %d  AND meta like %s AND action_id IS NOT NULL AND giveaway_id = %d  GROUP BY created_at ORDER BY created_at DESC";
			$safe_sql          = $wpdb->prepare( $sql, absint( $contestant_id ), $comment_label, absint( $_POST['giveaway_id'] ) );
			$completed_entries = $wpdb->get_results( $safe_sql );

		if ( $completed_entries > 0 ) {
			foreach ( $completed_entries as $t => $value ) {
				
				$meta_val = json_decode( $value->meta );
				
				$mutiple_comment_url[ $t ] = $meta_val->url;
			}
		}
	}

	wp_send_json( $mutiple_comment_url );

}

/*
 * Enter Giveaway
 */

function rafflepress_lite_giveaway_api() {
	$method = sanitize_text_field( $_POST['method'] );
	if ( $method == 'return' ) {
		// Vars
		global $wpdb;
		$status     = 'false';
		$errors     = array();
		$contestant = array();
		$msg        = '';
		$hash       = explode( '|', urldecode( $_POST['hash'] ) );

		// Get contestant
		if ( ! empty( $_POST['confirm'] ) && ! empty( $_POST['id'] ) && $_POST['confirm'] != 'undefined' && $_POST['id'] != 'undefined' && $_POST['confirm'] != 'null' && $_POST['id'] != 'null' ) {
			$tablename      = $wpdb->prefix . 'rafflepress_contestants';
			$sql            = "SELECT * FROM $tablename WHERE token = %s AND giveaway_id = %d AND id = %d";
			$safe_sql       = $wpdb->prepare( $sql, sanitize_text_field( $_POST['confirm'] ), absint( $_POST['giveaway_id'] ), absint( $_POST['id'] ) );
			$contestant_raw = $wpdb->get_row( $safe_sql );

			// confirm contestant
			if ( ! empty( $contestant_raw ) ) {
				$tablename            = $wpdb->prefix . 'rafflepress_contestants';
				$contestant_confirmed = $wpdb->update(
					$tablename,
					array(
						'status' => 'confirmed',
					),
					array( 'id' => absint( $contestant_raw->id ) ),
					array(
						'%s',
					),
					array( '%d' )
				);

				if ( $contestant_confirmed ) {
					$contestant_raw->status = 'confirmed';
					$msg                    = __( 'Your email has been confirmed.', 'rafflepress' );
				}
			}
		} elseif ( is_numeric( $hash[0] ) ) {
			$tablename      = $wpdb->prefix . 'rafflepress_contestants';
			$sql            = "SELECT * FROM $tablename WHERE email = %s AND giveaway_id = %d AND id = %d";
			$safe_sql       = $wpdb->prepare( $sql, sanitize_email( $hash[1] ), absint( $_POST['giveaway_id'] ), absint( $hash[0] ) );
			$contestant_raw = $wpdb->get_row( $safe_sql );
		}

		if ( ! empty( $contestant_raw ) ) {
			$status              = 'true';
			$contestant['id']    = $contestant_raw->id;
			$contestant['email'] = $contestant_raw->email;
			$contestant['hash']  = $contestant_raw->id . '|' . $contestant_raw->email;
			// get total entries count
			$tablename     = $wpdb->prefix . 'rafflepress_entries';
			$sql           = "SELECT count(id) FROM $tablename WHERE contestant_id = %d AND deleted_at IS NULL";
			$safe_sql      = $wpdb->prepare( $sql, absint( $contestant_raw->id ) );
			$total_entries = $wpdb->get_var( $safe_sql );

			// Get completed actions
			$tablename         = $wpdb->prefix . 'rafflepress_entries';
			$sql               = "SELECT DISTINCT action_id,count(id) as count, created_at, CONVERT_TZ(`created_at`, @@session.time_zone, '+00:00') AS `utc_datetime` FROM $tablename WHERE contestant_id = %d AND action_id IS NOT NULL AND giveaway_id = %d  GROUP BY action_id ORDER BY created_at DESC";
			$safe_sql          = $wpdb->prepare( $sql, absint( $contestant_raw->id ), absint( $_POST['giveaway_id'] ) );
			$completed_entries = $wpdb->get_results( $safe_sql );


			$contestant['total_entries']     = (int) $total_entries;
			$contestant['completed_entries'] = $completed_entries;
			if ( $contestant_raw->status == 'confirmed' ) {
				$contestant['confirmed'] = true;
			} else {
				$contestant['confirmed'] = false;
			}
		}

		// Return errors if not empty
		if ( empty( $errors ) ) {
			$response = array(
				'status'     => $status,
				'errors'     => '',
				'msg'        => $msg,
				'contestant' => $contestant,
			);
			wp_send_json( $response );
		}
	} elseif ( $method == 'enter' ) {
		if ( ! class_exists( 'rafflepress_FullNameParser' ) ) {
			require_once RAFFLEPRESS_PLUGIN_PATH . 'app/vendor/parser.php';
		}
		// Vars
		global $wpdb;
		$status     = 'false';
		$errors     = array();
		$contestant = array();

		$giveaway_id   = absint( $_POST['giveaway_id'] );
		$email         = sanitize_email( $_POST['email'] );
		$name          = sanitize_text_field( $_POST['name'] );
		$terms_consent = 0;
		if ( ! empty( $_POST['terms_consent'] ) ) {
			$terms_consent = 1;
		}
		$giveaway_return = array();

		if ( ! empty( $name ) ) {
			$parser      = new rafflepress_FullNameParser();
			$parsed_name = $parser->parse_name( $name );
		} else {
			$parsed_name['fname'] = null;
			$parsed_name['lname'] = null;
		}

		$referrer_id = null;
		if ( ! empty( $_COOKIE[ 'rafflepress_ref_' . $giveaway_id ] ) ) {
			$referrer_id = absint( $_COOKIE[ 'rafflepress_ref_' . $giveaway_id ] );
		}

		// Validate data, check name email, if ip is blocked, recaptcha

		if ( empty( $giveaway_id ) ) {
			$errors[] = __( 'Invalid Giveaway.', 'rafflepress' );
		}

		if ( empty( $email ) || is_email( $email ) == false ) {
			$errors[] = __( 'Please enter a valid email.', 'rafflepress' );
		}

		if ( empty( $name ) ) {
			$errors[] = __( 'Please enter a name.', 'rafflepress' );
		}

		if ( ! empty( $giveaway_id ) && ! empty( $email ) ) {
			// Check if it's running
			$tablename  = $wpdb->prefix . 'rafflepress_giveaways';
			$sql        = "SELECT starts,ends FROM $tablename WHERE id = %d";
			$safe_sql   = $wpdb->prepare( $sql, $giveaway_id );
			$is_running = $wpdb->get_row( $safe_sql );

			if ( time() > strtotime( $is_running->starts ) && time() < strtotime( $is_running->ends ) ) {
			} else {
				$errors[] = __( 'This giveaway is not currently running.', 'rafflepress' );
			}
		}

		// Get settings and entry actions
		$tablename     = $wpdb->prefix . 'rafflepress_giveaways';
		$sql           = "SELECT * FROM $tablename WHERE id = %d";
		$safe_sql      = $wpdb->prepare( $sql, $giveaway_id );
		$giveaway      = $wpdb->get_row( $safe_sql );
		$settings      = json_decode( $giveaway->settings );
		$entry_options = $settings->entry_options;

		// check recaptcha if enabled
		if ( ! empty( $settings->enable_recaptcha ) && ! empty( $settings->recaptcha_site_key ) && ! empty( $settings->recaptcha_secret_key ) ) {
			$response = wp_remote_post(
				'https://www.google.com/recaptcha/api/siteverify',
				array(
					'body' => array(
						'secret'   => $settings->recaptcha_secret_key,
						'response' => $_POST['g_recaptcha_response'],
					),
				)
			);

			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				$errors[]      = $error_message;
			} else {
				$body = json_decode( wp_remote_retrieve_body( $response ) );
			}

			if ( $body->success === false ) {
				$errors[] = __( 'Invalid Recaptcha', 'rafflepress' );
			}
		}

		// Return errors if not empty
		if ( ! empty( $errors ) ) {
			$response = array(
				'status'     => $status,
				'errors'     => implode( '<br>', $errors ),
				'contestant' => $contestant,
			);
			wp_send_json( $response );
		}

		// Add contestant to the db if not exists
		$tablename          = $wpdb->prefix . 'rafflepress_contestants';
		$sql                = "SELECT id FROM $tablename WHERE email = %s AND giveaway_id = %d";
		$safe_sql           = $wpdb->prepare( $sql, $email, $giveaway_id );
		$contestant_id      = $wpdb->get_var( $safe_sql );
		$conversion_scripts = '';

		if ( empty( $contestant_id ) ) {

			// Check number of sign ups
			if(!isset($settings->only_allow_remote_addr)){
				$settings->only_allow_remote_addr = false;
			}

			if ( ! empty( $settings->limit_signup_by_id ) ) {
				// check how many sign ups a contestant has
				$ip                = rafflepress_lite_get_ip($settings->only_allow_remote_addr);
				$tablename         = $wpdb->prefix . 'rafflepress_contestants';
				$sql               = "SELECT count(id) FROM $tablename WHERE ip = %s AND giveaway_id = %d";
				$safe_sql          = $wpdb->prepare( $sql, $ip, $giveaway_id );
				$number_of_signups = $wpdb->get_var( $safe_sql );
				if ( ! empty( $number_of_signups ) && $number_of_signups >= 3 ) {
					$errors[] = __( 'You have reached the maximum number of sign ups.', 'rafflepress' );
				}
			}

			// Return errors if not empty
			if ( ! empty( $errors ) ) {
				$response = array(
					'status'     => $status,
					'errors'     => implode( '<br>', $errors ),
					'contestant' => $contestant,
				);
				wp_send_json( $response );
			}

			$token     = strtolower( wp_generate_password( 16, false, false ) );
			$tablename = $wpdb->prefix . 'rafflepress_contestants';
			$wpdb->insert(
				$tablename,
				array(
					'giveaway_id'   => $giveaway_id,
					'email'         => $email,
					'fname'         => $parsed_name['fname'],
					'lname'         => $parsed_name['lname'],
					'referrer_id'   => $referrer_id,
					'token'         => $token,
					'ip'            => rafflepress_lite_get_ip($settings->only_allow_remote_addr),
					'terms_consent' => $terms_consent,
				),
				array(
					'%d',
					'%s',
					'%s',
					'%s',
					'%d',
					'%s',
					'%s',
					'%d',
				)
			);

			if ( ! empty( $wpdb->insert_id ) ) {
				$status = 'true';
			}

			$contestant['id']                 = absint( $wpdb->insert_id );
			$contestant['hash']               = $contestant['id'] . '|' . $email;
			$contestant['total_entries']      = 0;
			$contestant['completed_entries']  = array();
			$giveaway_return['total_entries'] = '';

			// check if auto entry action exists
			$giveaway_has_auto_entry       = false;
			$giveaway_auto_entry_value     = 0;
			$giveaway_auto_entry_action_id = null;
			foreach ( $entry_options as $k => $v ) {
				if ( ! empty( $v->type ) && $v->type == 'automatic-entry' ) {
					$giveaway_has_auto_entry       = true;
					$giveaway_auto_entry_action_id = $v->id;
					if ( ! empty( $v->value ) ) {
						$giveaway_auto_entry_value = $v->value;
					}
					break;
				}
			}

			// If giveaway has automatic entry give user the entries if they do not already have them
			if ( $giveaway_has_auto_entry ) {
				// assign entering entries if enabled
				$entries_tablename = $wpdb->prefix . 'rafflepress_entries';
				$insert_arrays     = array();
				for ( $i = 1; $i <= $giveaway_auto_entry_value; $i++ ) {
					$insert_arrays[] = array(
						'giveaway_id'   => $giveaway_id,
						'contestant_id' => $contestant['id'],
						'action_id'     => $giveaway_auto_entry_action_id,
						'meta'          => '{"action":"Entering Giveaway"}',
					);
				}
				rafflepress_lite_wp_insert_rows( $insert_arrays, $entries_tablename );

				do_action(
					'rafflepress_post_entry_add',
					array(
						'giveaway_id'       => $giveaway_id,
						'contestant_id'     => $contestant['id'],
						'action_id'         => $giveaway_auto_entry_action_id,
						'entry_option_meta' => '{"action":"Entering Giveaway"}',
					)
				);
				// end assign entering entries if enabled
			}

			// check for auto newslettery entires
			$giveaway_has_auto_trigger = false;
			foreach ( $entry_options as $k => $v ) {
				if ( ! empty( $v->type ) && $v->type == 'join-newsletter' ) {
					if ( ! empty( $v->auto_trigger ) ) {
						// Send to remote queue
						rafflepress_lite_process_email(
							array(
								'contestant_id'        => $contestant['id'],
								'action_id'            => $v->id,
								'email_integration_id' => $v->email_integration_id,
								'giveaway_id'          => $giveaway_id,
							)
						);

						// record the entry
						$entries_tablename    = $wpdb->prefix . 'rafflepress_entries';
						$insert_arrays        = array();
						$entry_meta['action'] = $v->name;
						for ( $i = 1; $i <= $v->value; $i++ ) {
							$insert_arrays[] = array(
								'giveaway_id'   => $giveaway_id,
								'contestant_id' => $contestant['id'],
								'action_id'     => $v->id,
								'meta'          => wp_json_encode( $entry_meta ),
							);
						}
						rafflepress_lite_wp_insert_rows( $insert_arrays, $entries_tablename );

						do_action(
							'rafflepress_post_entry_add',
							array(
								'giveaway_id'       => $giveaway_id,
								'contestant_id'     => $contestant['id'],
								'action_id'         => $v->id,
								'entry_option_meta' => wp_json_encode( $entry_meta ),
							)
						);

						$giveaway_has_auto_trigger = true;
					}
				}
			}

			if ( $giveaway_has_auto_trigger || $giveaway_has_auto_entry ) {
				// get total entries count
				$tablename                   = $wpdb->prefix . 'rafflepress_entries';
				$sql                         = "SELECT count(id) FROM $tablename WHERE contestant_id = %d AND deleted_at IS NULL";
				$safe_sql                    = $wpdb->prepare( $sql, $contestant['id'] );
				$total_entries               = $wpdb->get_var( $safe_sql );
				$contestant['total_entries'] = $total_entries;

				// Get completed actions

				$tablename                       = $wpdb->prefix . 'rafflepress_entries';
				$sql                             = "SELECT DISTINCT action_id,count(id) as count, created_at, CONVERT_TZ(`created_at`, @@session.time_zone, '+00:00') AS `utc_datetime` FROM $tablename WHERE contestant_id = %d AND action_id IS NOT NULL AND giveaway_id = %d  GROUP BY action_id ORDER BY created_at DESC";
				$safe_sql                        = $wpdb->prepare( $sql, $contestant['id'], $giveaway_id );
				$completed_entries               = $wpdb->get_results( $safe_sql );
				$contestant['completed_entries'] = $completed_entries;

				// get total entries count
				$tablename              = $wpdb->prefix . 'rafflepress_entries';
				$sql                    = "SELECT count(id) FROM $tablename WHERE giveaway_id = %d";
				$safe_sql               = $wpdb->prepare( $sql, $giveaway_id );
				$giveaway_total_entries = $wpdb->get_var( $safe_sql );

				$giveaway_return['total_entries'] = (int) $giveaway_total_entries;
			}

		} else {
			// login hash
			$status             = 'true';
			$contestant['id']   = $contestant_id;
			$contestant['hash'] = $contestant_id . '|' . $email;
			// get total entries count
			$tablename     = $wpdb->prefix . 'rafflepress_entries';
			$sql           = "SELECT count(id) FROM $tablename WHERE contestant_id = %d AND deleted_at IS NULL";
			$safe_sql      = $wpdb->prepare( $sql, $contestant_id );
			$total_entries = $wpdb->get_var( $safe_sql );

			// Get completed actions
			$tablename = $wpdb->prefix . 'rafflepress_entries';
			// $sql = "SELECT DISTINCT action_id,count(id) as count FROM $tablename WHERE contestant_id = %d AND action_id IS NOT NULL GROUP BY action_id";
			$sql               = "SELECT DISTINCT action_id,count(id) as count, created_at, CONVERT_TZ(`created_at`, @@session.time_zone, '+00:00') AS `utc_datetime` FROM $tablename WHERE contestant_id = %d AND action_id IS NOT NULL AND giveaway_id = %d  GROUP BY action_id ORDER BY created_at DESC";
			$safe_sql          = $wpdb->prepare( $sql, $contestant_id, $giveaway_id );
			$completed_entries = $wpdb->get_results( $safe_sql );


			$contestant['total_entries']     = (int) $total_entries;
			$contestant['completed_entries'] = $completed_entries;
		}

		// response
		$response = array(
			'status'             => $status,
			'errors'             => implode( '<br>', $errors ),
			'contestant'         => $contestant,
			'giveaway'           => $giveaway_return,
			'conversion_scripts' => $conversion_scripts,
		);
		wp_send_json( $response );
	} elseif ( $method == 'action' ) {
		// Vars
		global $wpdb;
		$status     = 'false';
		$errors     = array();
		$contestant = array();
		$entry_meta = null;

		$giveaway_id   = absint( $_POST['giveaway_id'] );
		$contestant_id = absint( $_POST['contestant_id'] );
		$action_id     = sanitize_text_field( $_POST['action_id'] );
		$entry_option  = $_POST['eo'];
		array_walk_recursive( $entry_option, 'sanitize_text_field' );
		array_walk_recursive( $entry_option, 'rafflepress_lite_convert_string_to_boolean' );


		// Check if it's running
		$tablename  = $wpdb->prefix . 'rafflepress_giveaways';
		$sql        = "SELECT starts,ends FROM $tablename WHERE id = %d";
		$safe_sql   = $wpdb->prepare( $sql, $giveaway_id );
		$is_running = $wpdb->get_row( $safe_sql );

		if ( time() > strtotime( $is_running->starts ) && time() < strtotime( $is_running->ends ) ) {
		} else {
			$errors[] = __( 'This giveaway is not currently running.', 'rafflepress' );
		}

		// Return errors if not empty
		if ( ! empty( $errors ) ) {
			$response = array(
				'status'     => $status,
				'errors'     => implode( '<br>', $errors ),
				'contestant' => $contestant,
			);
			wp_send_json( $response );
		}

		// Get entry actions
		$tablename     = $wpdb->prefix . 'rafflepress_giveaways';
		$sql           = "SELECT settings FROM $tablename WHERE id = %d";
		$safe_sql      = $wpdb->prepare( $sql, $giveaway_id );
		$settings      = $wpdb->get_var( $safe_sql );
		$settings      = json_decode( $settings );
		$entry_options = $settings->entry_options;

		// See if they have already earned these entries, exclude daily entries from this check
		$tablename = $wpdb->prefix . 'rafflepress_entries';
		// $sql = "SELECT count(id)FROM $tablename WHERE action_id = %s AND giveaway_id = %d AND contestant_id = %d";
		$sql      = "SELECT DISTINCT action_id,count(id) as count, created_at, CONVERT_TZ(`created_at`, @@session.time_zone, '+00:00') AS `utc_datetime`, UNIX_TIMESTAMP(created_at) AS `utc_timestamp` FROM $tablename WHERE contestant_id = %d AND action_id = %s AND giveaway_id = %d  GROUP BY action_id ORDER BY created_at DESC";
		$safe_sql = $wpdb->prepare( $sql, $contestant_id, $action_id, $giveaway_id );
		$entry    = $wpdb->get_row( $safe_sql );
		if ( ! empty( $entry->count ) ) {
			$entry_count = $entry->count;
		}


		if ( empty( $entry_count ) ) {
			$entry_value = 0;
			foreach ( $entry_options as $v ) {
				if ( $action_id == $v->id ) {
					$entry_value       = $v->value;
					$entry_option_meta = $v;
					break;
				}
			}

			$entry_meta = array(
				'action' => $entry_option['name'],
			);

			// Run any logic to confirm or complete action

			if ( $v->type == 'pinterest-follow' ) {
				$entry_meta['username'] = $_POST['eo']['source_pinterest_username'];
			}

			if ( $v->type == 'tiktok-follow' ) {
				$entry_meta['username'] = $_POST['eo']['source_tiktok_username'];
			}

			if ( $v->type == 'linkedin-follow' ) {
				$entry_meta['username'] = $_POST['eo']['source_linkedin_username'];
			}

			if ( $v->type == 'tweet' ) {
				$entry_meta['url'] = $_POST['eo']['source_tweet_url'];
			}

			if ( $v->type == 'linkedin-share' ) {
				$entry_meta['url'] = $_POST['eo']['source_linkedinshare_url'];
			}

			if ( $v->type == 'twitch-follow' ) {
				$entry_meta['username'] = $_POST['eo']['source_twitch_username'];
			}

			// Twitter Follow
			if ( $v->type == 'twitter-follow' ) {
				if ( RAFFLEPRESS_BUILD == 'pro' ) {
					$api_token = get_option( 'rafflepress_api_token' );
					$source    = '';
					if ( ! empty( $_POST['eo']['source_twitter_username'] ) ) {
						$source = str_replace( '@', '', sanitize_text_field( $_POST['eo']['source_twitter_username'] ) );
					}
					$target = '';
					if ( ! empty( $_POST['eo']['twitter_username'] ) ) {
						$target = str_replace( '@', '', sanitize_text_field( $_POST['eo']['twitter_username'] ) );
					}
					$proceed = true;
					if ( $source == $target ) {
						$proceed  = false;
						$errors[] = __( 'Something appears to be wrong. Make sure you have entered your Twitter Username and that it is not the username of the Twitter account to Follow.', 'rafflepress' );
					}

					if ( ! empty( $api_token ) && ! empty( $source ) && ! empty( $target ) && $proceed ) {
						// check it the user follows
						$response = wp_remote_get( RAFFLEPRESS_API_URL . 'twitter-follow-check?source=' . $source . '&target=' . $target . '&api_token=' . $api_token );

						if ( is_array( $response ) && ! is_wp_error( $response ) ) {
							$code = wp_remote_retrieve_response_code( $response );
							if ( $code === 200 ) {
								$headers = $response['headers']; // array of http header lines
								$body    = $response['body']; // use the content
								$body    = json_decode( $body );
								if ( isset( $body->errors ) ) {
									$errors[] = $body->errors[0]->message;
								} elseif ( ! isset( $body->relationship->source->following ) ) {
								} elseif ( $body->relationship->source->following === true ) {
								} else {
									$errors[] = __( 'It appears you are not following the Twitter User. Click the Twitter Follow Button and then Follow the User.', 'rafflepress' );
								}
							}
						}
					} else {
						if ( $proceed ) {
							$errors[] = __( 'Something appears to be wrong. Make sure you have entered your Twitter Username', 'rafflepress' );
						}
					}
					// Return errors if not empty
					if ( ! empty( $errors ) ) {
						$response = array(
							'status'     => $status,
							'errors'     => implode( '<br>', $errors ),
							'contestant' => $contestant,
						);
						wp_send_json( $response );
					}
				} else {
					$source = '';
					if ( ! empty( $_POST['eo']['source_twitter_username'] ) ) {
						$source = str_replace( '@', '', sanitize_text_field( $_POST['eo']['source_twitter_username'] ) );
					}
				}

				$entry_meta['username'] = '@' . $source;
			}


			$tablename     = $wpdb->prefix . 'rafflepress_entries';
			$insert_arrays = array();
			for ( $i = 1; $i <= $entry_value; $i++ ) {
				$insert_arrays[] = array(
					'giveaway_id'   => $giveaway_id,
					'contestant_id' => $contestant_id,
					'action_id'     => $action_id,
					'meta'          => wp_json_encode( $entry_meta ),
				);
			}

			// add entries
			$r = rafflepress_lite_wp_insert_rows( $insert_arrays, $tablename );

			do_action(
				'rafflepress_post_entry_add',
				array(
					'giveaway_id'       => $giveaway_id,
					'contestant_id'     => $contestant_id,
					'action_id'         => $action_id,
					'entry_option_meta' => $entry_option_meta,
				)
			);

			if ( $r ) {
				$status = 'true';
			}
		}

		// get total entries count
		$tablename              = $wpdb->prefix . 'rafflepress_entries';
		$sql                    = "SELECT count(id) FROM $tablename WHERE giveaway_id = %d";
		$safe_sql               = $wpdb->prepare( $sql, $giveaway_id );
		$giveaway_total_entries = $wpdb->get_var( $safe_sql );

		// get contestant total entries count
		$tablename     = $wpdb->prefix . 'rafflepress_entries';
		$sql           = "SELECT count(id) FROM $tablename WHERE contestant_id = %d";
		$safe_sql      = $wpdb->prepare( $sql, $contestant_id );
		$total_entries = $wpdb->get_var( $safe_sql );

		// Get completed actions
		$tablename = $wpdb->prefix . 'rafflepress_entries';
		// $sql = "SELECT DISTINCT action_id,count(id) as count FROM $tablename WHERE contestant_id = %d AND action_id IS NOT NULL GROUP BY action_id";
		$sql               = "SELECT DISTINCT action_id,count(id) as count, created_at, CONVERT_TZ(`created_at`, @@session.time_zone, '+00:00') AS `utc_datetime` FROM $tablename WHERE contestant_id = %d AND action_id IS NOT NULL AND giveaway_id = %d  GROUP BY action_id ORDER BY created_at DESC";
		$safe_sql          = $wpdb->prepare( $sql, $contestant_id, $giveaway_id );
		$completed_entries = $wpdb->get_results( $safe_sql );


		$contestant['id']                = $contestant_id;
		$contestant['total_entries']     = (int) $total_entries;
		$contestant['completed_entries'] = $completed_entries;
		$giveaway['total_entries']       = (int) $giveaway_total_entries;

		// response
		$response = array(
			'status'     => $status,
			'errors'     => $errors,
			'contestant' => $contestant,
			'giveaway'   => $giveaway,
		);
		wp_send_json( $response );
	} elseif ( $method == 'file-upload' ) {
		global $wpdb;
		// make sure giveaway is running and an contestant exists based off cookie to accepts uploads
		$giveaway_id = absint( $_POST['giveaway_id'] );
		// Check if it's running
		$tablename  = $wpdb->prefix . 'rafflepress_giveaways';
		$sql        = "SELECT starts,ends FROM $tablename WHERE id = %d";
		$safe_sql   = $wpdb->prepare( $sql, $giveaway_id );
		$is_running = $wpdb->get_row( $safe_sql );

		if ( time() > strtotime( $is_running->starts ) && time() < strtotime( $is_running->ends ) ) {
		} else {
			// $errors[] = __('This giveaway is not currently running.', 'rafflepress');
			wp_die();
		}

		$hash = $_COOKIE[ 'rafflepress_hash_' . $giveaway_id ];
		$hash = explode( '|', urldecode( $hash ) );

		// does user exists
		$tablename      = $wpdb->prefix . 'rafflepress_contestants';
		$sql            = "SELECT * FROM $tablename WHERE email = %s AND giveaway_id = %d AND id = %d";
		$safe_sql       = $wpdb->prepare( $sql, sanitize_email( $hash[1] ), absint( $giveaway_id ), absint( $hash[0] ) );
		$contestant_raw = $wpdb->get_row( $safe_sql );
		if ( empty( $contestant_raw ) ) {
			wp_die();
		}

		if ( empty( $_FILES['file'] ) ) {
			wp_die();
		}

		$url = false;

		// sanatize
		if ( ! @getimagesize( $_FILES['file']['tmp_name'] ) ) {
			$errors[] = __( 'Invalid Image', 'rafflepress' );
		} elseif ( $_FILES['file']['size'] > 5242880 ) { // 5 MB (size is also in bytes)
			$errors[] = __( 'Image too large. Must be under 5MB', 'rafflepress' );
		} else {
			$errors        = null;
			$allowed_mimes = array(
				'jpg|jpeg|jpe' => 'image/jpeg',
				'gif'          => 'image/gif',
				'png'          => 'image/png',
			);

			$file_info = wp_check_filetype( basename( $_FILES['file']['name'] ), $allowed_mimes );

			if ( ! empty( $file_info['type'] ) ) {
				add_filter( 'upload_dir', 'rafflepress_lite_custom_upload_dir' );
				$_FILES['file']['name'] = $giveaway_id . '-' . sanitize_title( $hash[1] ) . '-' . $_FILES['file']['name'];
				$upload_info            = wp_handle_upload(
					$_FILES['file'],
					array(
						'test_form' => false,
						'mimes'     => $allowed_mimes,
					)
				);
				remove_filter( 'upload_dir', 'rafflepress_lite_custom_upload_dir' );
				if ( ! empty( $upload_info ) ) {
					$status = true;
					$url    = $upload_info['url'];
				}
			} else {
				$errors[] = __( 'Invalid Image', 'raffelpress-pro' );
			}
		}

		// file upload
		$response = array(
			'status' => $status,
			'errors' => $errors,
			'url'    => $url,
			// 'giveaway' => $giveaway,
		);
		wp_send_json( $response );
	}
}

/**
 * Skip iframe from being lazy loadded by wp-smushit plugin
 */
add_filter( 'smush_skip_iframe_from_lazy_load', 'rafflepress_lite_exclude_recaptcha_iframe', 99, 2 );
function rafflepress_lite_exclude_recaptcha_iframe( $skip, $src ) {
	if ( false !== strpos( $src, 'rafflepress' ) ) {
		$skip = true;
	}
	return $skip;
}


/**
 * Display Giveaway
 */


add_shortcode( 'rafflepress', 'rafflepress_lite_display_shortcode' );
function rafflepress_lite_display_shortcode( $atts ) {
	wp_enqueue_script( 'rafflepress-iframeresizer-frontend' );

	$a = shortcode_atts(
		array(
			'id'         => '0',
			'min_height' => '',
			'giframe'    => 'false',
		),
		$atts
	);

	// Sanitize input.
	$a['id']         = sanitize_text_field( wp_unslash( $a['id'] ) );
	$a['min_height'] = sanitize_text_field( wp_unslash( $a['min_height'] ) );
	$a['giframe']    = sanitize_text_field( wp_unslash( $a['giframe'] ) );

	global $wpdb;

	$id = absint( $a['id'] );

	// Get Giveaway
	$tablename = $wpdb->prefix . 'rafflepress_giveaways';
	$sql       = "SELECT active FROM $tablename WHERE id = %d";
	$safe_sql  = $wpdb->prepare( $sql, $id );
	$active    = $wpdb->get_var( $safe_sql );

	$ref = '';
	if ( ! empty( $_GET['rpr'] ) ) {
		$ref = $_GET['rpr'];
	}
	$parent_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) . "://$_SERVER[HTTP_HOST]" . strtok( $_SERVER['REQUEST_URI'], '?' );
	if ( ! get_option( 'permalink_structure' ) ) {
		$parent_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) . "://$_SERVER[HTTP_HOST]" . $_SERVER['REQUEST_URI'];
	}

	ob_start();

	?>

	<?php
	// wp_print_scripts('rafflepress-if-shortcode');
	?>



<style>
.rafflepress-giveaway-iframe-wrapper iframe {
	width: 1px;
	min-width: 100%;
	*width: 100%;
	<?php if ( ! empty( $_GET['context'] ) && $_GET['context'] == 'edit' ) { ?>
	height: 600px;
	<?php } else { ?>
	height: 600px;
	<?php } ?>
}

.rafflepress_iframe_loading {
	background-image: url('data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==') !important;
	background-repeat: no-repeat !important;
	background-position: center 100px !important;
	height: 100%;
}
</style>

	<?php $iframe_uid = mt_rand( 10000000, 99999999 ); ?>
<div id="rafflepress-giveaway-iframe-wrapper-<?php echo $iframe_uid; ?>" class="rafflepress-giveaway-iframe-wrapper rafflepress_iframe_loading"></div>

<script>
function rafflepress_getParameterByName(name, url) {
	  if (!url) url = window.location.href;
	  name = name.replace(/[\[\]]/g, "\\$&");
	  var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
		results = regex.exec(url);
	  if (!results) return "";
	  if (!results[2]) return "";
	  return decodeURIComponent(results[2].replace(/\+/g, " "));
}
function insertIframe( ID, src, minHeight) {
	var wrapperID = 'rafflepress-giveaway-iframe-wrapper-'+ID;
	var iframe = document.createElement('iframe');

	iframe.setAttribute('id', 'rafflepress-'+ID);
	iframe.setAttribute('class', 'rafflepress-iframe');
	iframe.setAttribute('src', src);
	iframe.setAttribute('frameborder', '0');
	iframe.setAttribute('scrolling', 'no');
	iframe.setAttribute('allowtransparency', 'true');
	if (minHeight) {
		iframe.setAttribute('style', 'min-height:'+minHeight);
	}
	iframe.setAttribute('onload', 'rafflepress_resize_iframe_'+ID+'(this)');

	document.getElementById(wrapperID).appendChild(iframe);
}

// phpcs:disable
insertIframe( '<?php echo $iframe_uid; ?>','<?php echo trailingslashit( home_url() ) . '?rafflepress_page=rafflepress_render&rafflepress_id=' . urlencode($id) . '&iframe=1&giframe=' . urlencode($a['giframe']) . '&rpr=' . urlencode($ref) . '&parent_url=' . urlencode( $parent_url ); ?>&<?php echo mt_rand( 1, 99999 ); ?>&rp-email='+rafflepress_getParameterByName('rp-email',location.href)+'&rp-name='+rafflepress_getParameterByName('rp-name',location.href),'<?php echo esc_html( $a['min_height'] ); ?>' );
// phpcs:enable
</script>

<script>
function rafflepress_resize_iframe_<?php echo $iframe_uid; ?>(){
	iFrameResize({
		log: false,
		onMessage: function(messageData) {
			if (messageData.message == 'rafflepress_loaded') {
				var el = document.getElementById('rafflepress-giveaway-iframe-wrapper-<?php echo $iframe_uid; ?>');
				var className = "rafflepress_iframe_loading";
				if (el.classList)
					el.classList.remove(className);
				else
					el.className = el.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') +
						'(\\b|$)', 'gi'), ' ');
			}
		}
	}, '#rafflepress-<?php echo $iframe_uid; ?>');
};

</script>


	<?php
	$output = ob_get_clean();
	if ( empty( $active ) ) {
		return '';
	} else {
		return $output;
	}
}


/**
 * Display Latest Giveaway
 */
add_shortcode( 'rafflepress_latest_giveaway', 'rafflepress_lite_display_shortcode_latest' );

function rafflepress_lite_display_shortcode_latest( $atts ) {
	global $wpdb;
	// Get Giveaway
	$tablename = $wpdb->prefix . 'rafflepress_giveaways';
	$sql       = 'SELECT id FROM wp_rafflepress_giveaways WHERE active=1 order by created_at desc limit 1';
	$id        = $wpdb->get_var( $sql );
	$atts      = array( 'id' => $id );
	$output    = rafflepress_lite_display_shortcode( $atts );
	return $output;
}

/**
 * Display Giveaway
 */

add_shortcode( 'rafflepress_gutenberg', 'rafflepress_lite_display_gutenberg_shortcode' );
function rafflepress_lite_display_gutenberg_shortcode( $atts ) {
	wp_enqueue_script( 'rafflepress-iframeresizer-frontend' );

	$a = shortcode_atts(
		array(
			'id'         => '0',
			'min_height' => '',
			'giframe'    => 'false',
		),
		$atts
	);

	// Sanitize input.
	$a['id']         = sanitize_text_field( wp_unslash( $a['id'] ) );
	$a['min_height'] = sanitize_text_field( wp_unslash( $a['min_height'] ) );
	$a['giframe']    = sanitize_text_field( wp_unslash( $a['giframe'] ) );

	global $wpdb;

	$id = absint( $a['id'] );

	// Get Giveaway
	$tablename = $wpdb->prefix . 'rafflepress_giveaways';
	$sql       = "SELECT active FROM $tablename WHERE id = %d";
	$safe_sql  = $wpdb->prepare( $sql, $id );
	$active    = $wpdb->get_var( $safe_sql );

	$ref = '';
	if ( ! empty( $_GET['rpr'] ) ) {
		$ref = $_GET['rpr'];
	}

	$parent_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) . "://$_SERVER[HTTP_HOST]" . strtok( $_SERVER['REQUEST_URI'], '?' );
	ob_start();

	$style = '';
	if ( ! empty( $a['min_height'] ) ) {
		$style = 'style="min-height:' . esc_html( $a['min_height'] ) . '"';
	}
	?>

	<?php
	// wp_print_scripts('rafflepress-if-shortcode');
	?>


<style>
.rafflepress-giveaway-iframe-wrapper iframe {
	width: 1px;
	min-width: 100%;
	*width: 100%;
	height: 600px;
}

.rafflepress_iframe_loading {
	background-image: url('data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==') !important;
	background-repeat: no-repeat !important;
	background-position: center 100px !important;
	height: 100%;
}
</style>

	<?php $iframe_uid = mt_rand( 10000000, 99999999 ); ?>
<div id="rafflepress-giveaway-iframe-wrapper-<?php echo $iframe_uid; ?>" class="rafflepress-giveaway-iframe-wrapper rafflepress_iframe_loading">

	<?php
		// Iframe is inserted with insertIframe() on front end to avoid 3rd-party scripts from lazy-loading.
		// However, echoing the iframe is needed to render preview in the blocks editor
		$is_gb_editor = defined( 'REST_REQUEST' ) && REST_REQUEST && ! empty( $_REQUEST['context'] ) && 'edit' === $_REQUEST['context'];
	if ( $is_gb_editor ) {
		$iframe = '<iframe id="rafflepress-' . $iframe_uid . '" ' .
			'src="' . home_url() . '/rafflepress/' . $id . '?iframe=1&giframe=' . $a['giframe'] .
			'&rpr=' . $ref . '&parent_url=' . urlencode( $parent_url ) . '&' . mt_rand( 1, 99999 ) . '" ' .
			'frameborder="0" scrolling="no" allowtransparency="true" ' . $style . ' ' .
			// 'onload="rafflepress_resize_iframe_' . $iframe_uid . '(this)"' . // causes error & unnecessary due to overlay
			'></iframe>';
		echo $iframe;
	}
	?>

</div>


<script>
function insertIframe( ID, src, minHeight) {
	var wrapperID = 'rafflepress-giveaway-iframe-wrapper-'+ID;
	var iframe = document.createElement('iframe');

	iframe.setAttribute('id', 'rafflepress-'+ID);
	iframe.setAttribute('class', 'rafflepress-iframe');
	iframe.setAttribute('src', src);
	iframe.setAttribute('frameborder', '0');
	iframe.setAttribute('scrolling', 'no');
	iframe.setAttribute('allowtransparency', 'true');
	if (minHeight) {
		iframe.setAttribute('style', 'min-height:'+minHeight);
	}
	iframe.setAttribute('onload', 'rafflepress_resize_iframe_'+ID+'(this)');

	document.getElementById(wrapperID).appendChild(iframe);
}

// phpcs:disable
insertIframe( '<?php echo $iframe_uid; ?>','<?php echo trailingslashit( home_url() ) . '?rafflepress_page=rafflepress_render&rafflepress_id=' . urlencode($id) . '&iframe=1&giframe=' . urlencode($a['giframe']) . '&rpr=' . urlencode($ref) . '&parent_url=' . urlencode( $parent_url ); ?>&<?php echo mt_rand( 1, 99999 ); ?>','<?php echo esc_html( $a['min_height'] ); ?>' );
// phpcs:enable
</script>

<script>
function rafflepress_resize_iframe_<?php echo $iframe_uid; ?>(){
	iFrameResize({
		log: false,
		onMessage: function(messageData) {
			if (messageData.message == 'rafflepress_loaded') {
				var el = document.getElementById('rafflepress-giveaway-iframe-wrapper-<?php echo $iframe_uid; ?>');
				var className = "rafflepress_iframe_loading";
				if (el.classList)
					el.classList.remove(className);
				else
					el.className = el.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') +
						'(\\b|$)', 'gi'), ' ');
			}
		}
	}, '#rafflepress-<?php echo $iframe_uid; ?>');
};

</script>


	<?php
	$output = ob_get_clean();
	if ( empty( $active ) ) {
		return '';
	} else {
		return $output;
	}
}



function rafflepress_lite_generate_font_output( $id ) {
}



function rafflepress_lite_get_font() {
}
