<?php

/**
 * Get Available Social Providers.
 */
function youzify_get_social_login_box( $attrs = null ) {

	// Check if social login is enabled and there's at least one network available.
	if ( ! youzify_is_social_login_enabled() ) {
		return false;
	}

    // Get Providers.
    $providers = apply_filters( 'youzify_forms_social_login_providers', youzify_get_social_login_providers() );

    // Get Buttons Type
    $btns_type = isset( $attrs['social_button_type'] ) ? $attrs['social_button_type'] : youzify_option( 'youzify_social_btns_type', 'form-only-icons' );

    // Action
	do_action( 'youzify_membership_social_box_style' );

	?>

	<div class="<?php echo youzify_membership_get_social_box_class( $attrs ); ?>">
		<ul>
			<?php foreach( $providers as $provider ) : ?>

				<?php
					// Hide Not Available Networks.
					if ( ! youzify_is_social_login_provider_available( $provider ) ) {
						continue;
					}
				?>

				<?php $provider_url = apply_filters( 'youzify_social_login_icon_link', home_url( '/youzify-auth/social-login/' . $provider ) ); ?>
				<?php $provider_data = youzify_get_social_login_provider_data( $provider ); ?>
				<?php $provider_name = strtolower( $provider ); ?>

				<li class="youzify-membership-<?php echo $provider_name; ?>-btn">
					<a href="<?php echo $provider_url; ?>">
						<div class="youzify-membership-button-icon">
							<i class="<?php echo $provider_data['icon']; ?>"></i>
						</div>
						<?php if ( 'form-only-icons' != $btns_type ) : ?>
							<span class="youzify-membership-button-title">
							<?php echo sprintf( __( 'Connect with %s', 'youzify' ), $provider );?>
							</span>
						<?php endif; ?>
					</a>
				</li>

			<?php endforeach; ?>
			<?php do_action( 'youzify_after_login_social_icons' ); ?>
		</ul>
		<div class="youzify-membership-social-title"><span><?php _e( 'or', 'youzify' ); ?></span></div>
	</div>

	<?php
}

add_action( 'youzify_before_login_fields', 'youzify_get_social_login_box' );
add_action( 'bp_before_account_details_fields', 'youzify_get_social_login_box' );

/**
 * Get Social Buttons Class
 */
function youzify_membership_get_social_box_class( $attrs = null ) {

	// Init Array();
	$class = array();

	// Get Main Class.
	$class[] = 'youzify-membership-social-buttons';

	// Get Button Type
	$class[] = isset( $attrs['social_button_type'] ) ? $attrs['social_button_type'] : youzify_option( 'youzify_social_btns_type', 'form-only-icons' );

	// Get Button Border Type
	$class[] = youzify_option( 'youzify_social_btns_format', 'form-border-radius' );

	// Get Button Icons Position.
	$class[] = youzify_option( 'youzify_social_btns_icons_position', 'form-icons-left' );

	// Filter Class
	$class = apply_filters( 'youzify_social_box_class', $class );

	return implode( ' ', $class );
}

/**
 * Check if Social login is enabled.
 */
function youzify_is_social_login_enabled() {

	// init Vars.
	$available_network = false;
	$is_social_login_enabled = youzify_option( 'youzify_enable_social_login', 'on' );

	if ( 'off' == $is_social_login_enabled ) {
		return false;
	}

	// Get Providers
	$providers = youzify_get_social_login_providers();

	if ( empty( $providers ) ) {
		return false;
	}

	foreach( $providers as $provider ) {
		if ( youzify_is_social_login_provider_available( $provider ) ) {
			$available_network = true;
			break;
		}
	}

	// Check if there's at least one available network.
	if ( ! $available_network ) {
		return false;
	}


	return true;
}

/**
 * Check if Provider is available
 */
function youzify_is_social_login_provider_available( $provider ) {

	$network = strtolower( $provider );

	if ( 'off' == youzify_option( 'youzify_' . $network . '_app_status' ) ) {
		return false;
	}

	$app_key = youzify_option( 'youzify_' . $network . '_app_key' );
	$app_secret = youzify_option( 'youzify_' . $network . '_app_secret' );

	if ( empty( $app_key ) || empty( $app_secret ) ) {
		return false;
	}

	return true;
}

/**
 * Get/Set User Session Data
 */
function youzify_membership_user_session_data( $operation, $data = null ) {

	// Get User IP
	$user_ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR']: false;

	// Get Option Name.
	$transient_name = 'youzify_membership_user_data_' . $user_ip;

	if ( 'get' == $operation ) {
		return get_transient( $transient_name );
	} elseif ( 'set' == $operation ) {
		set_transient( $transient_name, json_encode( $data ), HOUR_IN_SECONDS );
	} elseif ( 'delete' == $operation ) {
		return delete_transient( $transient_name );
	}

	return false;
}

/**
 * Get/Set User Profile Data Temporarily
 */
function youzify_membership_user_profile_data( $operation, $data = null ) {

	// Get User IP
	$user_ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR']: false;

	// Get Option Name.
	$transient_name = 'youzify_membership_user_profile_' . $user_ip;

	if ( 'get' == $operation ) {
		return get_transient( $transient_name );
	} elseif ( 'set' == $operation ) {
		set_transient( $transient_name, json_encode( $data ), HOUR_IN_SECONDS );
	} elseif ( 'delete' == $operation ) {
		return delete_transient( $transient_name );
	}

	return false;
}

/**
 * Update User Social Profile Data
 */
function youzify_update_user_profile_meta( $user_id, $profile = null ) {

	global $Youzify_Membership;

	if ( empty( $profile ) ) {
		return false;
	}

 	// User Meta
 	$user_meta = array(
 		'first_name' 	=> $profile->firstName,
 		'last_name' 	=> $profile->lastName,
 		'description' 	=> $profile->description,
	 	'user_url' 		=> esc_url( $profile->webSiteURL )
 	);

 	// Update User Meta.
 	foreach ( $user_meta as $key => $value ) {

 		if ( empty( $value ) ) {
 			continue;
 		}

	 	// Update User Url.
	 	wp_update_user(
	 		array(
	 			'ID' => $user_id,
	 			$key => $value
	 		)
	 	);

 	}

}

/**
 * Update User Social Profile Data.
 */
function youzify_update_user_profile_meta_after_confirmation( $user_id ) {

	global $Youzify_Membership;

	// Update WPMU Social Data User ID
	if ( is_multisite() ) {
		$activated_user_id = get_userdata( $user_id );
		$a_user_email = $activated_user_id->user_email;
		$a_social_data = youzify_wpmu_update_user_social_data( $a_user_email, $user_id );
	}


	$profile = youzify_get_user_stored_social_data( $user_id );

	if ( empty( $profile ) ) {
		return false;
	}

 	// User Meta
 	$user_meta = array(
 		'first_name' 	=> $profile->firstname,
 		'last_name' 	=> $profile->lastname,
 		'description' 	=> $profile->description,
 		'youzify_social_avatar' 	=> $profile->photourl,
	 	'user_url' 		=> esc_url( $profile->websiteurl )
 	);

	$display_name = $Youzify_Membership->social->get_display_name( null, $profile->firstname, $profile->lastname );

 	if ( ! empty( $display_name ) && bp_is_active( 'xprofile' ) ) {
 		xprofile_set_field_data( 1, $user_id, $display_name );
 	}

 	// Update User Meta.
 	foreach ( $user_meta as $key => $value ) {

 		if ( empty( $value ) ) {
 			continue;
 		}

	 	// Update User Url.
	 	if( is_multisite() ) {
	 		update_user_option( $user_id, $key, $value );
	 	} else {

		 	wp_update_user(
		 		array(
		 			'ID' => $user_id,
		 			$key => $value
		 		)
		 	);

	 	}
 	}

}

add_action( 'bp_core_activated_user', 'youzify_update_user_profile_meta_after_confirmation' );

/**
 * Get User By Email.
 */
function youzify_wpmu_update_user_social_data( $email, $user_id ) {

	global $wpdb;

	// Update User ID
	$result = $wpdb->update( $wpdb->prefix . 'youzify_social_login_users', array( 'user_id' => $user_id ), array( 'email' => $email ) );
	// Return Result.
	return $result;
}

/**
 * Get User Stored Data
 */
function youzify_get_user_stored_social_data( $user_id ) {

	global $wpdb;

	// Get SQL.
	$sql = "SELECT * FROM " . $wpdb->prefix . "youzify_social_login_users WHERE user_id = %d";

	// Get Result
	$result = $wpdb->get_results( $wpdb->prepare( $sql, $user_id ) );
	$result = isset( $result[0] ) ? $result[0] : null;

	return $result;
}

/**
 * Multi Site Social Login
 */
function youzify_mu_store_social_login_data( $user_id = null ) {

	global $Youzify_Membership;

	// Get Provider.
	$provider = $Youzify_Membership->social->get_provider();

    // Inculue Files.
	if ( ! class_exists( 'Hybridauth', false ) ) {
		require_once( YOUZIFY_CORE . "hybridauth/autoload.php" );
		require_once( YOUZIFY_CORE . "hybridauth/Hybridauth.php" );
	}

	// Create an Instance with The Config Data.
	$class = "Hybridauth\\Provider\\$provider";
    $hybridauth = new $class( $Youzify_Membership->social->get_config( $provider ) );

	if ( empty( $provider ) ) {
		return;
	}

	// // Get User Data.
	$user_data = $Youzify_Membership->social->get_user_data( $hybridauth, $provider );

	if ( isset( $user_data['user_profile'] ) && ! empty( $user_data['user_profile'] ) ) {
		// Store Data.
		youzify_wpmu_store_user_data( $provider, $user_data['user_profile'] );
	}

}

// add_action( 'after_signup_user', 'youzify_mu_store_social_login_data' );

/**
 * Disable E-mail Confirmation For Social Login.
 */
function youzify_disable_account_confirmation( $user_id, $user_login, $user_password, $user_email, $usermeta ) {

	if ( youzify_social_registration_needs_activation() ) {
		return;
	}

	// Get User Meta.
	$user_meta = maybe_unserialize( $usermeta );
	if ( ! isset( $user_meta['social_login'] ) ) {
		return;
	}

	global $wpdb;

	// Get User Activation Code.
	$activation_key = get_user_meta( $user_id, 'activation_key', true );

	// Activate User Account.
	$activate = apply_filters( 'bp_core_activate_account', bp_core_activate_signup( $activation_key ) );

	// Mark Account As Validated.
	BP_Signup::validate( $activation_key );

	// Change User Status.
	$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->users SET user_status = 0 WHERE ID = %d", $user_id ) );

	bp_delete_user_meta( $user_id, 'activation_key' );
}

// Disable Confirmation For Social Login.
add_action( 'bp_core_signup_user', 'youzify_disable_account_confirmation', 10, 5 );

/**
 * WPMU - Store User Data Into Database.
 */
function youzify_wpmu_store_user_data( $provider, $profile ) {

	$new_hash = sha1( serialize( $profile ) );

	// Get Table Data.
	$table_data = array(
		'id' => null,
		'user_id' => 0,
		'provider' => $provider,
		'profile_hash' => $new_hash
	);

	// Get Table Fields.
	$fields = array(
		'identifier',
		'profileurl',
		'websiteurl',
		'photourl',
		'displayname',
		'description',
		'firstname',
		'lastname',
		'gender',
		'language',
		'age',
		'birthday',
		'birthmonth',
		'birthyear',
		'email',
		'emailverified',
		'phone',
		'address',
		'country',
		'region',
		'city',
		'zip'
	);

	foreach( $profile as $key => $value ) {
		// Transform Key To LowerCase.
		$key = strtolower( $key );
		// Get Table Data.
		if ( in_array( $key, $fields ) ) {
			$table_data[ $key ] = (string) $value;
		}
	}

	global $wpdb;

	// Replace Data.
	$wpdb->replace( $wpdb->prefix . 'youzify_social_login_users', $table_data );

	return false;
}

/**
 * Disable Sending An activation Key For Social Login.
 */
function youzify_disable_send_activation_key_for_social_login( $activate, $user_id = null, $user_email= null, $activation_key= null, $user_meta = null ) {

	if ( youzify_social_registration_needs_activation() ) {
		return $activate;
	}

	// Get User Meta.
	$user_meta = maybe_unserialize( $user_meta );

	if ( isset( $user_meta['social_login'] ) ) {
		return false;
	}

	return $activate;
}

add_filter( 'bp_core_signup_send_activation_key', 'youzify_disable_send_activation_key_for_social_login', 10, 5 );

/**
 * Get Social Confirmation status.
 */
function youzify_social_registration_needs_activation() {

	// Get Confirmation status.
	return 'on' == youzify_option( 'youzify_enable_social_login_email_confirmation', 'on' ) ? true : false;

}