<?php

/**
 * Check if Account Verification Enabled.
 */
function youzify_is_account_verification_enabled() {
	return 'on' == youzify_option( 'youzify_enable_account_verification', 'on' ) ? true : false;
}

/**
 * Check is User Can Verify Account.
 */
function youzify_is_user_can_verify_accounts() {

	if ( ! is_user_logged_in() || ! youzify_is_account_verification_enabled() ) {
		return false;
	}

	// Get Current User Data.
	$user = wp_get_current_user();

	// Allowed Verifiers Roles
	$allowed_roles = array( 'administrator' );

	// Filter Allowed Roles.
	$allowed_roles = apply_filters( 'youzify_allowed_roles_to_verify_accounts', $allowed_roles );

	foreach ( $allowed_roles as $role ) {
		if ( in_array( $role, (array) $user->roles ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Check is User Account Verified.
 */
function youzify_is_user_account_verified( $user_id ) {

	// Check if verification is enabled.
	if ( ! youzify_is_account_verification_enabled() ) {
		return false;
	}

	$status = false;

	if ( 'on' == get_user_meta( $user_id, 'youzify_account_verified', true ) ) {
		$status = true;
	}

	return apply_filters( 'youzify_is_user_account_verified', $status, $user_id );
}

/**
 * Get User Tools
 */
function youzify_get_user_tools( $user_id = null, $icons = null ) {

	if ( ! $user_id ) {
		return false;
	}

	$icons = ! empty( $icons ) ? $icons : 'only-icons';

	?>

	<div class="youzify-tools youzify-user-tools youzify-tools-<?php echo $icons; ?>" data-nonce="<?php echo wp_create_nonce( 'youzify-tools-nonce-' . $user_id ); ?>" data-user-id="<?php echo $user_id; ?>" data-component="profile">
		<?php do_action( 'youzify_user_tools', $user_id, $icons ); ?>
	</div>

	<?php
}

/**
 * Get Verification User Tool.
 */
function youzify_get_user_verification_tool( $user_id = null, $icons = null ) {

	if ( ! youzify_is_user_can_verify_accounts() ) {
		return false;
	}

	// Get User Verification Value.
	$is_account_verified = youzify_is_user_account_verified( $user_id );

	// Get Button Title.
	$button_title = $is_account_verified ? __( 'Unverify Account', 'youzify' ) : __( 'Verify Account', 'youzify' );

	?>

	<div class="youzify-tool-btn youzify-verify-btn" <?php if ( 'only-icons' == $icons ) { ?> data-youzify-tooltip="<?php echo $button_title; ?>"<?php } ?> data-nonce="<?php echo wp_create_nonce( 'youzify-account-verification-' . $user_id ); ?>" data-action="<?php echo $is_account_verified ? 'unverify' : 'verify'; ?>" data-user-id="<?php echo $user_id; ?>"><div class="youzify-tool-icon"><i class="<?php echo $is_account_verified ? 'fas fa-times' : 'fas fa-check'; ?>"></i></div><?php if ( 'full-btns' == $icons ) : ?><div class="youzify-tool-name"><?php echo $button_title; ?></div><?php endif; ?>
	</div>

	<?php
}

add_action( 'youzify_user_tools', 'youzify_get_user_verification_tool', 10, 2 );

/**
 * Add Profile header Tools
 */
function youzify_get_profile_header_tools() {

	// Get Profile Layout.
	$profile_layout = youzify_get_profile_layout();

	if ( 'youzify-vertical-layout' == $profile_layout ) {
		return false;
	}

	// Get Displayed User ID
	$user_id = bp_displayed_user_id();

	if ( ! $user_id ) {
		return false;
	}

	youzify_get_user_tools( $user_id, 'full-btns' );
}

add_action( 'youzify_profile_header', 'youzify_get_profile_header_tools' );

/**
 * Get User Verification Icon.
 */
function youzify_the_user_verification_icon( $user_id = null, $size = null ) {
	echo youzify_get_user_verification_icon( $user_id, $size );
}

/**
 * Get User Verification Icon.
 */
function youzify_get_user_verification_icon( $user_id = null, $size = null ) {

	// Check if verification is enabled.
	if ( ! youzify_is_account_verification_enabled() ) {
		return false;
	}

	// Get User Verification Status
	$is_account_verified = youzify_is_user_account_verified( $user_id );

	if ( ! $is_account_verified ) {
		return false;
	}

	// Get Icon.
	$icon = youzify_account_verified_button( $size );

	return $icon;
}

/**
 * Verified Account Button.
 */
function youzify_account_verified_button( $size = null ) {

	// Get Icon Size.
	$size = ! empty( $size ) ? $size : 'small';

	// Icon Class.
	$class = array();

	// Add Icon Class.
	$class[] = 'fas fa-check youzify-account-verified';

	// Add Icon size.
	$class[] = "youzify-$size-verified-icon";

	// Get Full Class
	$classes = youzify_generate_class( $class );

	return "<i class='$classes'></i>";
}

/**
 * Add Verification icon to profile username
 */
function youzify_add_username_verification_icon( $username ) {

	// Get Displayes User Profile ID.
	$user_id = bp_displayed_user_id();

	// Get User Verification Status
	$is_account_verified = youzify_is_user_account_verified( $user_id );

	if ( ! $is_account_verified ) {
		return $username;
	}

	// Verified Icon.
	$icon = youzify_account_verified_button( 'big' );

	return $username . $icon;
}

add_filter( 'youzify_user_profile_username', 'youzify_add_username_verification_icon' );

/**
 * Add verification icon after username link
 */
function youzify_add_user_link_verification_icon( $html, $user_id ) {

	// Get User Verification Status
	if ( ! youzify_is_user_account_verified( $user_id ) ) {
		return $html;
	}

	// Return;
	return '<a href="' . bp_members_get_user_url( $user_id ) . '">' . bp_core_get_user_displayname( $user_id ) . youzify_account_verified_button() . '</a>' ;
}

add_filter( 'bp_core_get_userlink', 'youzify_add_user_link_verification_icon', 10, 2 );

/**
 * Add verification icon after member name
 */
function youzify_add_member_name_verification_icon( $member_name ) {

	global $members_template, $activities_template;

	// Get User Id.
	if ( isset( $members_template->member->id ) ) {
		$user_id = $members_template->member->id;
	} elseif ( isset( $activities_template->activity->current_comment->user_id ) ) {
		$user_id = $activities_template->activity->current_comment->user_id;
	} else {
		$user_id = false;
	}

	// Get User Verification Status
	$is_account_verified = youzify_is_user_account_verified( $user_id );

	if ( ! $is_account_verified ) {
		return $member_name;
	}

	// Verified Icon.
	$verified_icon = youzify_account_verified_button();

	// Return Name With Icon;
	return $member_name . $verified_icon;
}

add_filter( 'bp_member_name', 'youzify_add_member_name_verification_icon', 99 );
add_filter( 'bp_activity_comment_name', 'youzify_add_member_name_verification_icon', 99 );

/**
 * Get Verified Account.
 */
function youzify_get_verified_users( $args = null ) {

	global $wpdb, $Youzify_reviews_table;

	// Get Verification Value.
	$verified = isset( $args['verified'] ) && $args['verified'] == true ? 'on' : 'off';

	// Request.
	$request = "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = 'youzify_account_verified' AND meta_value = '$verified'";

	$order_by = isset( $args['order_by'] ) && $args['order_by'] == 'random' ? 'RAND()' : $args['order_by'];

	if ( isset( $args['order_by'] ) ) {
		$request .= " ORDER BY $order_by";
	}

	if ( isset( $args['limit'] ) ) {
		$request .= " LIMIT {$args['limit']}";
	}

	// Get Result
	$users = $wpdb->get_col( $request );

	return apply_filters( 'youzify_get_verified_users', $users );

}

/**
 * Verified Users shortcode
 */
function youzify_verified_users_shortcode( $atts = null ) {

	// Get Args.
	$args = shortcode_atts(
		array(
			'limit' => 5,
			'order_by' => 'user_id',
			'verified' => true,
		), $atts, 'youzify_verified_users' );

	// Get Users List.
	$verified_users = youzify_get_verified_users( $args );

	ob_start();

	// Get List.
	youzify_get_users_list( $verified_users, $args );

    return ob_get_clean();
}

add_shortcode( 'youzify_verified_users', 'youzify_verified_users_shortcode' );