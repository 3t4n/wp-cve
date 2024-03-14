<?php

/**
 *  Get IP
 */
function rafflepress_lite_get_ip($only_allow_remote_addr = false) {
	 $ip = '';
	if($only_allow_remote_addr == false){
	if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) and strlen( $_SERVER['HTTP_X_FORWARDED_FOR'] ) > 6 ) {
		$ip = strip_tags( $_SERVER['HTTP_X_FORWARDED_FOR'] );
	} elseif ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) and strlen( $_SERVER['HTTP_CLIENT_IP'] ) > 6 ) {
		$ip = strip_tags( $_SERVER['HTTP_CLIENT_IP'] );
	} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) and strlen( $_SERVER['REMOTE_ADDR'] ) > 6 ) {
		$ip = strip_tags( $_SERVER['REMOTE_ADDR'] );
	}
	}else{
		if ( ! empty( $_SERVER['REMOTE_ADDR'] ) and strlen( $_SERVER['REMOTE_ADDR'] ) > 6 ) {
			$ip = strip_tags( $_SERVER['REMOTE_ADDR'] );
		}
	}
	if ( ! $ip ) {
		$ip = '127.0.0.1';
	}
	return strip_tags( $ip );
}

/**
 *  Get IP
 */
function rafflepress_lite_convert_string_to_boolean( &$value, $key ) {
	if ( $value == 'false' || $value == 'true' ) {
		$value = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}
}

/**
 * Get Enviroment
 */
function rafflepress_lite_is_localhost() {
	// $localhost = array('127.0.0.1','::1');

	// $is_localhost = false;
	// if (in_array($_SERVER['REMOTE_ADDR'], $localhost) || !empty($_GET['debug'])) {
	// $is_localhost = true;
	// }
	$is_localhost = false;
	if ( defined( 'RAFFLEPRESS_LOCAL_JS' ) ) {
		$is_localhost = true;
	}

	return $is_localhost;
}

/**
 * Entry Options
 */
function rafflepress_lite_entry_options() {
	$entry_options = array(
		'visit-fb'            => array(
			'name'   => __( 'Visit us on Facebook', 'rafflepress' ),
			'is_pro' => false,
			'cat'    => 'subscribers',
		),
		'facebook-like-share' => array(
			'name'   => __( 'Like our Page', 'rafflepress' ),
			'is_pro' => false,
			'cat'    => 'subscribers',
		),

		'twitter-follow'      => array(
			'name'   => __( 'Follow us on X ( Twitter )', 'rafflepress' ),
			'is_pro' => false,
			'cat'    => 'subscribers',
		),
		'instagram-follow'    => array(
			'name'   => __( 'Visit us on Instagram', 'rafflepress' ),
			'is_pro' => false,
			'cat'    => 'subscribers',
		),
		'invent-your-own'     => array(
			'name'   => __( 'Invent Your Own', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'traffic',
		),
		'join-newsletter'     => array(
			'name'   => __( 'Join an Email Newsletter', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'subscribers',
		),
		'question'            => array(
			'name'   => __( 'Answer a Question', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'traffic',
		),
		'submit-image'        => array(
			'name'   => __( 'Submit an Image', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'traffic',
		),
		'refer-a-friend'      => array(
			'name'   => __( 'Refer a Friend - Viral', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'engagement',
		),
		'youtube-follow'      => array(
			'name'   => __( 'Visit a YouTube Channel', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'subscribers',
		),
		'watch-a-video'       => array(
			'name'   => __( 'Watch a Video', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'engagement',
		),
		'tiktok-follow'       => array(
			'name'   => __( 'Follow us on TikTok', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'subscribers',
		),
		'tiktok-videos'       => array(
			'name'   => __( 'View TikTok Post / Video', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'engagement',
		),
		'pinterest-follow'    => array(
			'name'   => __( 'Follow us on Pinterest', 'rafflepress' ),
			'is_pro' => false,
			'cat'    => 'subscribers',
		),
		'linkedin-follow'     => array(
			'name'   => __( 'Follow us on LinkedIn', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'subscribers',
		),

		'twitch-follow'       => array(
			'name'   => __( 'Follow us on Twitch', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'subscribers',
		),



		'fb-page-post'        => array(
			'name'   => __( 'View Facebook Post / Video', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'engagement',
		),
		'tiktok-videos'       => array(
			'name'   => __( 'View TikTok Post / Video', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'engagement',
		),
		'instagram-page-post' => array(
			'name'   => __( 'View Instagram Post / Video', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'engagement',
		),
		'automatic-entry'     => array(
			'name'   => __( 'Automatic Entry', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'engagement',
		),		
		'g2-follow'    => array(
			'name'   => __( 'Visit us on G2', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'engagement',
		),
		'capterra-follow'    => array(
			'name'   => __( 'Visit us on Capterra', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'engagement',
		),
		'trustpilot-follow'    => array(
			'name'   => __( 'Visit us on Trustpilot', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'engagement',
		),
		'tweet'               => array(
			'name'   => __( 'Tweet a Message', 'rafflepress' ),
			'is_pro' => false,
			'cat'    => 'traffic',
		),
		'facebook-share'      => array(
			'name'   => __( 'Share on Facebook', 'rafflepress' ),
			'is_pro' => false,
			'cat'    => 'traffic',
		),
		'visit-a-page'        => array(
			'name'   => __( 'Visit a Page', 'rafflepress' ),
			'is_pro' => false,
			'cat'    => 'traffic',
		),
		'linkedin-share'      => array(
			'name'   => __( 'Share on LinkedIn', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'traffic',
		),

		'polls-surveys'       => array(
			'name'   => __( 'Polls & Surveys', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'traffic',
		),
		'comment'             => array(
			'name'   => __( 'Leave a Comment', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'traffic',
		),
		'blogpost'            => array(
			'name'   => __( 'Write a Blog Post', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'traffic',
		),
		'rss'                 => array(
			'name'   => __( 'Subscribe to RSS Feed', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'traffic',
		),
		'podcast'             => array(
			'name'   => __( 'Subscribe to Podcast', 'rafflepress' ),
			'is_pro' => true,
			'cat'    => 'traffic',
		),
	);

	return $entry_options;
}


/**
 * Get times
 */
function rafflepress_lite_get_default_timezone() {
	$general_settings = get_option( 'rafflepress_settings' );
	$timezone         = 'UTC';

	if ( ! empty( $general_settings ) ) {
		$general_settings = json_decode( $general_settings );
		if ( ! empty( $general_settings->default_timezone ) ) {
			$timezone = $general_settings->default_timezone;
		}
	}

	return $timezone;
}



/**
 * Get times
 */
function rafflepress_lite_get_times() {
	$times   = array();
	$times[] = array(
		'v' => '',
		'l' => __( 'Select Time', 'rafflepress' ),
	);
	$times[] = array(
		'v' => '00:00',
		'l' => '12:00 A.M.',
	);
	$times[] = array(
		'v' => '00:30',
		'l' => '12:30 A.M.',
	);
	$times[] = array(
		'v' => '01:00',
		'l' => '1:00 A.M.',
	);
	$times[] = array(
		'v' => '01:30',
		'l' => '1:30 A.M.',
	);
	$times[] = array(
		'v' => '02:00',
		'l' => '2:00 A.M.',
	);
	$times[] = array(
		'v' => '02:30',
		'l' => '2:30 A.M.',
	);
	$times[] = array(
		'v' => '03:00',
		'l' => '3:00 A.M.',
	);
	$times[] = array(
		'v' => '03:30',
		'l' => '3:30 A.M.',
	);
	$times[] = array(
		'v' => '04:00',
		'l' => '4:00 A.M.',
	);
	$times[] = array(
		'v' => '04:30',
		'l' => '4:30 A.M.',
	);
	$times[] = array(
		'v' => '05:00',
		'l' => '5:00 A.M.',
	);
	$times[] = array(
		'v' => '05:30',
		'l' => '5:30 A.M.',
	);
	$times[] = array(
		'v' => '06:00',
		'l' => '6:00 A.M.',
	);
	$times[] = array(
		'v' => '06:30',
		'l' => '6:30 A.M.',
	);
	$times[] = array(
		'v' => '07:00',
		'l' => '7:00 A.M.',
	);
	$times[] = array(
		'v' => '07:30',
		'l' => '7:30 A.M.',
	);
	$times[] = array(
		'v' => '08:00',
		'l' => '8:00 A.M.',
	);
	$times[] = array(
		'v' => '08:30',
		'l' => '8:30 A.M.',
	);
	$times[] = array(
		'v' => '09:00',
		'l' => '9:00 A.M.',
	);
	$times[] = array(
		'v' => '09:30',
		'l' => '9:30 A.M.',
	);
	$times[] = array(
		'v' => '10:00',
		'l' => '10:00 A.M.',
	);
	$times[] = array(
		'v' => '10:30',
		'l' => '10:30 A.M.',
	);
	$times[] = array(
		'v' => '11:00',
		'l' => '11:00 A.M.',
	);
	$times[] = array(
		'v' => '11:30',
		'l' => '11:30 A.M.',
	);
	$times[] = array(
		'v' => '12:00',
		'l' => '12:00 P.M.',
	);
	$times[] = array(
		'v' => '12:30',
		'l' => '12:30 P.M.',
	);
	$times[] = array(
		'v' => '13:00',
		'l' => '1:00 P.M.',
	);
	$times[] = array(
		'v' => '13:30',
		'l' => '1:30 P.M.',
	);
	$times[] = array(
		'v' => '14:00',
		'l' => '2:00 P.M.',
	);
	$times[] = array(
		'v' => '14:30',
		'l' => '2:30 P.M.',
	);
	$times[] = array(
		'v' => '15:00',
		'l' => '3:00 P.M.',
	);
	$times[] = array(
		'v' => '15:30',
		'l' => '3:30 P.M.',
	);
	$times[] = array(
		'v' => '16:00',
		'l' => '4:00 P.M.',
	);
	$times[] = array(
		'v' => '16:30',
		'l' => '4:30 P.M.',
	);
	$times[] = array(
		'v' => '17:00',
		'l' => '5:00 P.M.',
	);
	$times[] = array(
		'v' => '17:30',
		'l' => '5:30 P.M.',
	);
	$times[] = array(
		'v' => '18:00',
		'l' => '6:00 P.M.',
	);
	$times[] = array(
		'v' => '18:30',
		'l' => '6:30 P.M.',
	);
	$times[] = array(
		'v' => '19:00',
		'l' => '7:00 P.M.',
	);
	$times[] = array(
		'v' => '19:30',
		'l' => '7:30 P.M.',
	);
	$times[] = array(
		'v' => '20:00',
		'l' => '8:00 P.M.',
	);
	$times[] = array(
		'v' => '20:30',
		'l' => '8:30 P.M.',
	);
	$times[] = array(
		'v' => '21:00',
		'l' => '9:00 P.M.',
	);
	$times[] = array(
		'v' => '21:30',
		'l' => '9:30 P.M.',
	);
	$times[] = array(
		'v' => '22:00',
		'l' => '10:00 P.M.',
	);
	$times[] = array(
		'v' => '22:30',
		'l' => '10:30 P.M.',
	);
	$times[] = array(
		'v' => '23:00',
		'l' => '11:00 P.M.',
	);
	$times[] = array(
		'v' => '23:30',
		'l' => '11:30 P.M.',
	);

	return $times;
}

/**
 * Check per
 */
function rafflepress_lite_get_api_key() {
	$rafflepress_api_key = '';

	if ( defined( 'RAFFLEPRESS_API_KEY' ) ) {
		$rafflepress_api_key = RAFFLEPRESS_API_KEY;
	}

	if ( empty( $rafflepress_api_key ) ) {
		$rafflepress_api_key = get_option( 'rafflepress_api_key ' );
	}

	return $rafflepress_api_key;
}

/**
 * Get timezones
 */
function rafflepress_lite_get_timezones() {
	// timezones
	$zonen      = array();
	$continents = array( 'Africa', 'America', 'Antarctica', 'Arctic', 'Asia', 'Atlantic', 'Australia', 'Europe', 'Indian', 'Pacific' );

	foreach ( timezone_identifiers_list() as $zone ) {
		$zone = explode( '/', $zone );
		if ( ! in_array( $zone[0], $continents ) ) {
			continue;
		}

		// This determines what gets set and translated - we don't translate Etc/* strings here, they are done later
		$exists    = array(
			0 => ( isset( $zone[0] ) && $zone[0] ),
			1 => ( isset( $zone[1] ) && $zone[1] ),
			2 => ( isset( $zone[2] ) && $zone[2] ),
		);
		$exists[3] = ( $exists[0] && 'Etc' !== $zone[0] );
		$exists[4] = ( $exists[1] && $exists[3] );
		$exists[5] = ( $exists[2] && $exists[3] );

		$zonen[] = array(
			'continent'   => ( $exists[0] ? $zone[0] : '' ),
			'city'        => ( $exists[1] ? $zone[1] : '' ),
			'subcity'     => ( $exists[2] ? $zone[2] : '' ),
			't_continent' => ( $exists[3] ? translate( str_replace( '_', ' ', $zone[0] ), 'continents-cities' ) : '' ),
			't_city'      => ( $exists[4] ? translate( str_replace( '_', ' ', $zone[1] ), 'continents-cities' ) : '' ),
			't_subcity'   => ( $exists[5] ? translate( str_replace( '_', ' ', $zone[2] ), 'continents-cities' ) : '' ),
		);
	}
	usort( $zonen, '_wp_timezone_choice_usort_callback' );

	$structure = array();

	foreach ( $zonen as $key => $zone ) {
		// Build value in an array to join later
		$value = array( $zone['continent'] );

		if ( empty( $zone['city'] ) ) {
			// It's at the continent level (generally won't happen)
			$display = $zone['t_continent'];
		} else {
			// It's inside a continent group

			// Continent optgroup
			if ( ! isset( $zonen[ $key - 1 ] ) || $zonen[ $key - 1 ]['continent'] !== $zone['continent'] ) {
				$label = $zone['t_continent'];
				// $structure[] = $label ;
			}

			// Add the city to the value
			$value[] = $zone['city'];

			// get offset
			// $timezone = $label.'/'.str_replace(' ', '_', $zone['t_city']);
			// $time = new \DateTime('now', new DateTimeZone($timezone));
			// $timezoneOffset = $time->format('P');

			$display = $zone['t_city'];
			if ( ! empty( $zone['subcity'] ) ) {
				// Add the subcity to the value
				$value[]  = $zone['subcity'];
				$display .= ' - ' . $zone['t_subcity'];
			}
		}

		// Build the value
		$value = join( '/', $value );

		// get offset
		$time                  = new \DateTime( 'now', new DateTimeZone( $value ) );
		$timezoneOffset        = $time->format( 'P' );
		$structure[ $label ][] = array(
			'v' => $value,
			'l' => $display . ' (' . $timezoneOffset . ' GMT)',
		);
	}

	$structure['UTC'][] = array(
		'v' => 'UTC',
		'l' => 'UTC',
	);

	return $structure;
}

/**
 * Get Giveaway Slug
 */
function rafflepress_lite_get_slug() {
	$settings = get_option( 'rafflepress_settings' );
	$slug     = 'rp';
	if ( ! empty( $settings ) ) {
		$settings = json_decode( $settings );
		if ( ! empty( $settings->slug ) && $settings->slug != 'rafflepress' ) {
			$slug = $settings->slug;
		}
	}

	return $slug;
}

/**
 * Add to array if value does not exist
 */
function rafflepress_lite_array_add( $arr, $key, $value ) {
	if ( ! array_key_exists( $key, $arr ) ) {
		$arr[ $key ] = $value;
	}
	return $arr;
}


/**
 * Multiple inserts
 */
function rafflepress_lite_wp_insert_rows( $row_arrays = array(), $wp_table_name = null, $update = false, $primary_key = null ) {
	global $wpdb;
	$wp_table_name = esc_sql( $wp_table_name );
	// Setup arrays for Actual Values, and Placeholders
	$values        = array();
	$place_holders = array();
	$query         = '';
	$query_columns = '';

	$query .= "INSERT INTO `{$wp_table_name}` (";
	foreach ( $row_arrays as $count => $row_array ) {
		foreach ( $row_array as $key => $value ) {
			if ( $count == 0 ) {
				if ( $query_columns ) {
					$query_columns .= ', ' . $key . '';
				} else {
					$query_columns .= '' . $key . '';
				}
			}

			$values[] = $value;

			$symbol = '%s';
			if ( is_numeric( $value ) && $key != 'action_id' ) {
				if ( is_float( $value ) ) {
					$symbol = '%f';
				} else {
					$symbol = '%d';
				}
			}
			if ( isset( $place_holders[ $count ] ) ) {
				$place_holders[ $count ] .= ", '$symbol'";
			} else {
				$place_holders[ $count ] = "( '$symbol'";
			}
		}
		// mind closing the GAP
		$place_holders[ $count ] .= ')';
	}

	$query .= " $query_columns ) VALUES ";

	$query .= implode( ', ', $place_holders );

	if ( $update ) {
		$update = " ON DUPLICATE KEY UPDATE $primary_key=VALUES( $primary_key ),";
		$cnt    = 0;
		foreach ( $row_arrays[0] as $key => $value ) {
			if ( $cnt == 0 ) {
				$update .= "$key=VALUES($key)";
				$cnt     = 1;
			} else {
				$update .= ", $key=VALUES($key)";
			}
		}
		$query .= $update;
	}

	$sql = $wpdb->prepare( $query, $values );
	if ( $wpdb->query( $sql ) ) {
		return true;
	} else {
		return false;
	}
}


/**
 * Check per
 */
function rafflepress_lite_cu( $rper = null ) {
	if ( ! empty( $rper ) ) {
		$uper = explode( ',', get_option( 'rafflepress_per' ) );
		if ( in_array( $rper, $uper ) ) {
			return true;
		} else {
			return false;
		}
	} else {
		$a = get_option( 'rafflepress_a' );
		if ( $a ) {
			return true;
		} else {
			return false;
		}
	}
}


function rafflepress_lite_upgrade_link( $medium = 'link' ) {
	return apply_filters( 'rafflepress_lite_upgrade_link', 'https://rafflepress.com/lite-upgrade/?utm_source=WordPress&utm_campaign=liteplugin&utm_medium=' . sanitize_key( apply_filters( 'rafflepress_lite_upgrade_link_medium', $medium ) ) );
}


function rafflepress_lite_disable_admin_notices() {
	global $wp_filter;
	if ( is_user_admin() ) {
		if ( isset( $wp_filter['user_admin_notices'] ) ) {
			unset( $wp_filter['user_admin_notices'] );
		}
	} elseif ( isset( $wp_filter['admin_notices'] ) ) {
		unset( $wp_filter['admin_notices'] );
	}
	if ( isset( $wp_filter['all_admin_notices'] ) ) {
		unset( $wp_filter['all_admin_notices'] );
	}
}
if ( ! empty( $_GET['page'] ) && strpos( $_GET['page'], 'rafflepress' ) !== false ) {
	add_action( 'admin_print_scripts', 'rafflepress_lite_disable_admin_notices' );
}


/**
 * Install addon.
 *
 * @since 1.0.0
 */
function rafflepress_lite_install_addon() {
	// Run a security check.
	check_ajax_referer( 'rafflepress_lite_install_addon', 'nonce' );

	// Check for permissions.
	if ( ! current_user_can( apply_filters( 'rafflepress_install_plugins_capability', 'install_plugins' ) ) ) {
		wp_send_json_error();
	}

	// Check DISALLOW_FILE_MODS constant
	if ( defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS === true ) {
		wp_send_json_error();
	}

	// Install the addon.
	if ( isset( $_POST['plugin'] ) ) {
		$download_url = $_POST['plugin'];

		global $hook_suffix;

		// Set the current screen to avoid undefined notices.
		set_current_screen();

		// Prepare variables.
		$method = '';
		$url    = add_query_arg(
			array(
				'page' => 'rafflepress_lite',
			),
			admin_url( 'admin.php' )
		);
		$url    = esc_url( $url );

		// Start output bufferring to catch the filesystem form if credentials are needed.
		ob_start();
		if ( false === ( $creds = request_filesystem_credentials( $url, $method, false, false, null ) ) ) {
			$form = ob_get_clean();
			echo json_encode( array( 'form' => $form ) );
			wp_die();
		}

		// If we are not authenticated, make it happen now.
		if ( ! WP_Filesystem( $creds ) ) {
			ob_start();
			request_filesystem_credentials( $url, $method, true, false, null );
			$form = ob_get_clean();
			echo json_encode( array( 'form' => $form ) );
			wp_die();
		}
		global $wp_version; 
		// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		if ( version_compare( $wp_version, '5.3.0' ) >= 0 ) {
			require_once RAFFLEPRESS_PLUGIN_PATH . 'app/includes/skin53.php';
		} else {
			require_once RAFFLEPRESS_PLUGIN_PATH . 'app/includes/skin.php';
		}

		// Create the plugin upgrader with our custom skin.
		$installer = new Plugin_Upgrader( $skin = new RafflePress_Skin() );
		$installer->install( $download_url );

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();
		if ( $installer->plugin_info() ) {
			$plugin_basename = $installer->plugin_info();
			echo json_encode( array( 'plugin' => $plugin_basename ) );
			wp_die();
		}
	}

	// Send back a response.
	echo json_encode( true );
	wp_die();
}


/**
 * Deactivate addon.
 *
 * @since 1.0.0
 */
function rafflepress_lite_deactivate_addon() {
	// Run a security check.
	check_ajax_referer( 'rafflepress_lite_deactivate_addon', 'nonce' );

	// Check for permissions.
	if ( ! current_user_can( apply_filters( 'rafflepress_activate_plugins_capability', 'activate_plugins' ) ) ) {
		wp_send_json_error();
	}

	$type = 'addon';
	if ( ! empty( $_POST['type'] ) ) {
		$type = sanitize_key( $_POST['type'] );
	}

	if ( isset( $_POST['plugin'] ) ) {
		deactivate_plugins( $_POST['plugin'] );

		if ( 'plugin' === $type ) {
			wp_send_json_success( esc_html__( 'Plugin deactivated.', 'rafflepress' ) );
		} else {
			wp_send_json_success( esc_html__( 'Addon deactivated.', 'rafflepress' ) );
		}
	}

	wp_send_json_error( esc_html__( 'Could not deactivate the addon. Please deactivate from the Plugins page.', 'rafflepress' ) );
}


/**
 * Activate addon.
 *
 * @since 1.0.0
 */
function rafflepress_lite_activate_addon() {
	// Run a security check.
	check_ajax_referer( 'rafflepress_lite_activate_addon', 'nonce' );

	// Check for permissions.
	if ( ! current_user_can( apply_filters( 'rafflepress_activate_plugins_capability', 'activate_plugins' ) ) ) {
		wp_send_json_error();
	}

	if ( isset( $_POST['plugin'] ) ) {
		$type = 'addon';
		if ( ! empty( $_POST['type'] ) ) {
			$type = sanitize_key( $_POST['type'] );
		}

		$activate = activate_plugins( $_POST['plugin'] );

		if ( ! is_wp_error( $activate ) ) {
			if ( 'plugin' === $type ) {
				wp_send_json_success( esc_html__( 'Plugin activated.', 'rafflepress' ) );
			} else {
				wp_send_json_success( esc_html__( 'Addon activated.', 'rafflepress' ) );
			}
		}
	}

	wp_send_json_error( esc_html__( 'Could not activate addon. Please activate from the Plugins page.', 'rafflepress' ) );
}

function rafflepress_lite_get_plugins_list() {
	check_ajax_referer( 'rafflepress_lite_get_plugins_list', 'nonce' );

	$am_plugins  = array(
		'google-analytics-for-wordpress/googleanalytics.php' => 'monsterinsights',
		'google-analytics-premium/googleanalytics-premium.php' => 'monsterinsights-pro',
		'optinmonster/optin-monster-wp-api.php'            => 'optinmonster',
		'wp-mail-smtp/wp_mail_smtp.php'                    => 'wpmailsmtp',
		'wp-mail-smtp-pro/wp_mail_smtp.php'                => 'wpmailsmtp-pro',
		'wpforms-lite/wpforms.php'                         => 'wpforms',
		'wpforms/wpforms.php'                              => 'wpforms-pro',
		'coming-soon/coming-soon.php'                      => 'coming-soon',
		'seedprod-coming-soon-pro-5/seedprod-coming-soon-pro-5.php'  => 'seedprod-pro',
		'trustpulse-api/trustpulse.php'                    => 'trustpulse',
		'google-analytics-dashboard-for-wp/gadwp.php'      => 'exactmetrics',
		'exactmetrics-premium/exactmetrics-premium.php'    => 'exactmetrics-pro',
		'all-in-one-seo-pack/all_in_one_seo_pack.php'      => 'all-in-one',
		'all-in-one-seo-pack-pro/all_in_one_seo_pack.php'  => 'all-in-one-pro',
		'seo-by-rank-math/rank-math.php'                   => 'rank-math',
		'wordpress-seo/wp-seo.php'                         => 'yoast',
		'autodescription/autodescription.php'              => 'seo-framework',
		'instagram-feed/instagram-feed.php'                => 'instagramfeed',
		'instagram-feed-pro/instagram-feed.php'            => 'instagramfeed-pro',
		'custom-facebook-feed/custom-facebook-feed.php'    => 'customfacebookfeed',
		'custom-facebook-feed-pro/custom-facebook-feed.php' => 'customfacebookfeed-pro',
		'custom-twitter-feeds/custom-twitter-feed.php'     => 'customtwitterfeeds',
		'custom-twitter-feeds-pro/custom-twitter-feed.php' => 'customtwitterfeeds-pro',
		'feeds-for-youtube/youtube-feed.php'               => 'feedsforyoutube',
		'youtube-feed-pro/youtube-feed.php'                => 'feedsforyoutube-pro',
		'pushengage/main.php'                              => 'pushengage',
		'sugar-calendar-lite/sugar-calendar-lite.php'      => 'sugarcalendar',
		'sugar-calendar/sugar-calendar.php'                => 'sugarcalendar-pro',
		'stripe/stripe-checkout.php'                       => 'wpsimplepay',
		'wp-simple-pay-pro-3/simple-pay.php'               => 'wpsimplepay-pro',
		'easy-digital-downloads/easy-digital-downloads.php' => 'easydigitaldownloads',
		'searchwp/index.php'                               => 'searchwp',
		'affiliate-wp/affiliate-wp.php'                    => 'affiliatewp',
		'uncanny-automator/uncanny-automator.php' 		   => 'uncanny-automator',
		'uncanny-automator-pro/uncanny-automator-pro.php'  => 'uncanny-automator-pro',
	);
	$all_plugins = get_plugins();

	$response = array();

	foreach ( $am_plugins as $slug => $label ) {
		if ( array_key_exists( $slug, $all_plugins ) ) {
			if ( is_plugin_active( $slug ) ) {
				$response[ $label ] = array(
					'label'  => __( 'Active', 'seedprod-pro' ),
					'status' => 1,
				);
			} else {
				$response[ $label ] = array(
					'label'  => __( 'Inactive', 'seedprod-pro' ),
					'status' => 2,
				);
			}
		} else {
			$response[ $label ] = array(
				'label'  => __( 'Not Installed', 'seedprod-pro' ),
				'status' => 0,
			);
		}
	}

	wp_send_json( $response );
}

function rafflepress_lite_plugin_nonce() {
	check_ajax_referer( 'rafflepress_lite_plugin_nonce', 'nonce' );

	if ( ! current_user_can( apply_filters( 'rafflepress_install_plugins_capability', 'install_plugins' ) ) ) {
		wp_send_json_error();
	}

	$install_plugin_nonce = wp_create_nonce( 'install-plugin_' . sanitize_text_field( $_POST['plugin'] ) );

	wp_send_json( $install_plugin_nonce );
}

function rafflepress_lite_is_dev_url( $url = '' ) {
	$is_local_url = false;
	// Trim it up
	$url = strtolower( trim( $url ) );
	// Need to get the host...so let's add the scheme so we can use parse_url
	if ( false === strpos( $url, 'http://' ) && false === strpos( $url, 'https://' ) ) {
		$url = 'http://' . $url;
	}
	$url_parts = parse_url( $url );
	$host      = ! empty( $url_parts['host'] ) ? $url_parts['host'] : false;
	if ( ! empty( $url ) && ! empty( $host ) ) {
		if ( false !== ip2long( $host ) ) {
			if ( ! filter_var( $host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) ) {
				$is_local_url = true;
			}
		} elseif ( 'localhost' === $host ) {
			$is_local_url = true;
		}

		$tlds_to_check = array( '.local', ':8888', ':8080', ':8081', '.invalid', '.example', '.test' );
		foreach ( $tlds_to_check as $tld ) {
			if ( false !== strpos( $host, $tld ) ) {
				$is_local_url = true;
				break;
			}
		}
		if ( substr_count( $host, '.' ) > 1 ) {
			$subdomains_to_check = array( 'dev.', '*.staging.', 'beta.', 'test.' );
			foreach ( $subdomains_to_check as $subdomain ) {
				$subdomain = str_replace( '.', '(.)', $subdomain );
				$subdomain = str_replace( array( '*', '(.)' ), '(.*)', $subdomain );
				if ( preg_match( '/^(' . $subdomain . ')/', $host ) ) {
					$is_local_url = true;
					break;
				}
			}
		}
	}
	return $is_local_url;
}


if ( ! empty( $_GET['rafflepress-preview'] ) || ! empty( $_GET['iframe'] ) || ( ! empty( $_GET['context'] ) && $_GET['context'] == 'edit' ) ) {
	add_action( 'init', 'rafflepress_lite_remove_ngg_print_scripts' );
}
function rafflepress_lite_remove_ngg_print_scripts() {
	if ( class_exists( 'C_Photocrati_Resource_Manager' ) ) {
		remove_all_actions( 'wp_print_footer_scripts', 1 );
	}
}

function rafflepress_lite_custom_upload_dir( $dir ) {
	// $dir_data already you might want to use
	$custom_dir = 'custom';
	return array(
		'path'   => $dir['basedir'] . '/rafflepress',
		'url'    => $dir['baseurl'] . '/rafflepress',
		'subdir' => '/rafflepress',
	) + $dir;
}

