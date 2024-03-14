<?php

/**
 * Get Group Page Class.
 */
function youzify_group_page_class() {

    // New Array
    $class = array( 'youzify-horizontal-layout', 'youzify-page youzify-group', 'youzify-wild-content' );

    // Get Tabs List Icons Style
    $class[] = youzify_option( 'youzify_tabs_list_icons_style', 'youzify-tabs-list-gradient' );

    // Get Page Buttons Style
    $class[] = 'youzify-page-btns-border-' . youzify_option( 'youzify_buttons_border_style', 'oval' );

    // Get Elements Border Style.
    $class[] = 'youzify-wg-border-' . youzify_option( 'youzify_wgs_border_style', 'radius' );

    $class = apply_filters( 'youzify_group_class', $class );

    return youzify_generate_class( $class );
}


/**
 * Get Friends List To invite
 */
function youzify_get_new_group_invite_friend_list( $items, $r ) {

	// Setup empty items array.
	$items = array();

	// Get user's friends who are not in this group already.
	$friends = friends_get_friends_invite_list( $r['user_id'], $r['group_id'] );

	if ( ! empty( $friends ) ) {

		// Get already invited users.
		$invites = groups_get_invites_for_group( $r['user_id'], $r['group_id'] );

		for ( $i = 0, $count = count( $friends ); $i < $count; ++$i ) {

			// Get Friend ID.
			$friend_id = $friends[ $i ]['id'];

			// Get Friend Avatar
			$friend_avatar = bp_core_fetch_avatar(
				array(
					'item_id' => $friend_id,
					'type' => 'thumb',
					'width' => '35px',
					'height' => '35px'
				)
			);

			// Check if Friend is already Invited.
			$checked = in_array( (int) $friends[ $i ]['id'], (array) $invites );

			// Get Code.
			$items[] = '<' . $r['separator'] . '><label class="youzify-cs-checkbox-field" for="f-' . esc_attr( $friend_id ) . '"><input' . checked( $checked, true, false ) . ' type="checkbox" name="friends[]" id="f-' . esc_attr( $friend_id ) . '" value="' . esc_attr( $friend_id ) . '"> ' . $friend_avatar . esc_html( $friends[ $i ]['full_name'] ) . '<div class="youzify_field_indication"></div></label></' . $r['separator'] . '>';

		}
	}

	return $items;
}

add_filter( 'bp_get_new_group_invite_friend_list', 'youzify_get_new_group_invite_friend_list', 10,  2 );

/**
 * Get Widget Default Settings
 */
function youzify_get_widget_defaults_settings( $widget_id ) {

	global $wp_widget_factory;

	// Get Widgets List.
	$wp_widgets = $wp_widget_factory->widgets;

	if ( isset( $wp_widgets[ $widget_id ] ) ) {
		return $wp_widgets[ $widget_id ]->default_options;
	}

    return false;
}

/**
 * Get Group Status
 */
function youzify_get_group_status( $group_status ) {

	// Get Group Status Data
	switch ( $group_status ) {

		case 'public':
			$icon = 'fas fa-globe-asia';
			$type = __( 'Public Group', 'youzify' );
			break;

		case 'private':
			$icon = 'fas fa-lock';
			$type = __( 'Private Group', 'youzify' );
			break;

		case 'hidden':
			$icon = 'fas fa-user-secret';
			$type = __( 'Hidden Group', 'youzify' );
			break;
	}

	// Print Status
	return '<i class="' . $icon . '"></i><span>' . $type . '</span>';
}

/**
 * Call Groups Sidebar
 */
function youzify_get_groups_sidebar() {
  	// Display Widgets.
	if ( is_active_sidebar( 'youzify-groups-sidebar' ) ) {
		dynamic_sidebar( 'youzify-groups-sidebar' );
	}
}

add_action( 'youzify_group_sidebar', 'youzify_get_groups_sidebar' );

/**
 * Get Group Total Posts Number.
 */
function youzify_get_group_total_posts_count( $group_id ) {

 	if ( ! bp_is_active( 'activity' ) ) {
 		return 0;
 	}

	global $bp,$wpdb;

	$total_updates = $wpdb->get_var( "SELECT COUNT(*) FROM {$bp->activity->table_name} WHERE component = 'groups' AND item_id = '$group_id' AND type NOT IN ( 'created_group', 'joined_group' ) ");

	return $total_updates;
}

/**
 * Check if Group Have Social Networks Accounts.
 */
function youzify_is_group_have_networks( $group_id = null ) {

	// This will be activated in coming updates.
	return false;

    // Get Group ID.
    $group_id = ! empty( $group_id ) ? $group_id : null;

    // Get Social Networks
    $social_networks = youzify_option( 'youzify_group_social_networks' );

    // Unserialize data
    if ( is_serialized( $social_networks ) ) {
        $social_networks = unserialize( $social_networks );
    }

    // Check if there's URL related to the icons.
    foreach ( $social_networks as $network => $data ) {
        $network = youzify_get_user_meta( $network, $user_id );
        if ( ! empty( $network ) ) {
            return true;
        }
    }

    return false;
}

/**
 * Set Default Groups Avatar.
 */
function youzify_set_group_default_avatar( $avatar, $params ) {

    // Get Default Avatar.
    $default_avatar = youzify_option( 'youzify_default_groups_avatar' );

    if ( empty( $default_avatar ) ) {
        return $avatar;
    }

    return $default_avatar;
}

add_filter( 'bp_core_default_avatar_group', 'youzify_set_group_default_avatar', 10, 2 );

/**
 * Add Groups Open Graph Support.
 */
function youzify_groups_open_graph() {

    if ( ! bp_is_group() ) {
        return false;
    }

   	global $bp;

   	// Get Current Group Data.
	$group = $bp->groups->current_group;

    // Get Group Cover Image
    $group_img = bp_attachments_get_attachment(
    	'url', array( 'item_id' => $group->id, 'object_dir' => 'groups' )
    );

    // Get Avatar if Cover Not found.
    if ( empty( $group_img ) ) {
        $group_avatar = bp_core_fetch_avatar( array(
			'avatar_dir' => 'group-avatars',
			'item_id'    => $group->id,
			'object' 	 => 'group',
			'type'	  	 => 'full',
			'html' 	  	 => false
			)
		);

        $group_img = apply_filters( 'youzify_og_group_default_thumbnail', $group_avatar );

    }

    // Get Group Link.
    $url = bp_get_group_url( $group );

    // Get Group Description.
    $group_description = esc_html( $group->description );

    youzify_get_open_graph_tags( 'profile', $url, $group->name, $group_description, $group_img );

}

add_action( 'wp_head', 'youzify_groups_open_graph' );

/**
 * Add group header Tools
 */
function youzify_get_group_header_tools() {

   	global $bp;

   	// Get Current Group Data.
	$group = $bp->groups->current_group;

	if ( ! $group ) {
		return false;
	}

	youzify_get_group_tools( $group->id, 'full-btns' );

}

add_action( 'youzify_group_header', 'youzify_get_group_header_tools' );

/**
 * Get Group Tools
 */
function youzify_get_group_tools( $group_id = null, $icons = null ) {

	$icons = ! empty( $icons ) ? $icons : 'only-icons';

	// Get Ajax Nonce
	$ajax_nonce = wp_create_nonce( 'youzify-tools-nonce-' . $group_id );

	?>

	<div class="youzify-tools youzify-group-tools youzify-tools-<?php echo $icons; ?>" data-nonce="<?php echo $ajax_nonce; ?>" data-group-id="<?php echo $group_id; ?>" data-component="group">
		<?php do_action( 'youzify_group_tools', $group_id, $icons ); ?>
	</div>

	<?php
}

/**
 * Get User total groups.
 */

function youzify_get_group_total_for_member( $user_id = null ) {

    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

    $user_groups = get_transient( 'youzify_count_user_groups_' . $user_id );

    if ( false === $user_groups ) {

        $user_groups = bp_get_group_total_for_member( $user_id );

        set_transient( 'youzify_count_user_groups_' . $user_id, $user_groups, 12 * HOUR_IN_SECONDS );
    }

    return $user_groups;
}

/**
 * Delete Groups Count.
 */
function youzify_user_groups_count_transient( $user_id = null  ) {
	// Delete Transient.
	delete_transient( 'youzify_count_user_groups_' . $user_id );
}

add_action( 'groups_ban_member', 'youzify_user_groups_count_transient', 10, 1 );
add_action( 'groups_leave_group', 'youzify_user_groups_count_transient', 10, 1 );
add_action( 'groups_remove_member', 'youzify_user_groups_count_transient', 10, 1 );
add_action( 'groups_membership_accepted', 'youzify_user_groups_count_transient', 10, 1 );

/**
 * Check is Groups
 **/
function youzify_is_groups_directory() {
	return apply_filters( 'youzify_is_groups_directory', bp_is_groups_directory() );
}