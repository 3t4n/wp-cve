<?php

/**
 * Check is Youzify Account Page.
 */
function youzify_is_account_page() {

    if ( bp_is_current_component( 'profile' ) || bp_is_current_component( 'settings' ) || bp_is_current_component( 'widgets' ) ) {
        return true;
    }

    return false;
}

/**
 * Get All Fields.
 */
function youzify_get_all_profile_fields() {

    // Merge All Fields
    $all_fields = youzify_array_merge( youzify_get_bp_profile_fields(), youzify_get_youzify_profile_fields() );

    return apply_filters( 'youzify_get_all_profile_fields', $all_fields );

}

/**
 * Get Youzify Fields
 */
function youzify_get_youzify_profile_fields() {

    // Init Data
    $fields = array(
        array(
            'id'   => 'full_location',
            'name' => __( 'Country, City', 'youzify' ),
        )
    );

    // Filter
    return apply_filters( 'youzify_get_youzify_profile_fields', $fields );
}

/**
 * Get Youzify Xprofile Fields
 */
function youzify_get_youzify_xprofile_fields() {

    // Get Profile Fields.
    $profile_fields = youzify_option( 'youzify_xprofile_contact_info_group_ids' );
    $contact_fields = youzify_option( 'youzify_xprofile_profile_info_group_ids' );

    $all_fields = (array) $contact_fields + (array) $profile_fields;

    if ( isset( $all_fields['group_id'] ) ) {
        unset( $all_fields['group_id'] );
    }

    return apply_filters( 'youzify_get_youzify_xprofile_fields', $all_fields );
}

/**
 * Get Youzify Xprofile Field Value
 */
function youzify_get_xprofile_field_value( $field_name, $user_id = null ) {

    // Field Value
    $field_value = null;

    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

    // Get Field ID.
    $field_id = youzify_get_xprofile_field_id( $field_name );

    if ( ! empty( $field_id ) ) {
        $field_value = youzify_get_user_field_data( $field_id, $user_id );
    }

    return apply_filters( 'youzify_get_xprofile_field_value' , $field_value, $field_id, $user_id );

}


/**
 * Get Youzify Xprofile Field
 */
function youzify_get_xprofile_field_id( $field_name ) {

    // Get Field ID.
    $field_id = null;

    // Get Profile Fields.
    $fields = youzify_get_youzify_xprofile_fields();

    if ( isset( $fields[ $field_name ] ) ) {
        $field_id = $fields[ $field_name ];
    }

    return apply_filters( 'youzify_get_xprofile_field_id' , $field_id, $field_name );

}

/**
 * Get User Statistics Details.
 */
function youzify_get_user_statistics_details( $user_id ) {

    $profile =  bp_members_get_user_url( $user_id );

    $statistics = array(
        'posts'     => array(
            'title' => __( 'Posts', 'youzify' ),
            'link'  => $profile . 'posts',
        ),
        'comments'  => array(
            'title' => __( 'Comments', 'youzify'),
            'link'  => $profile . 'comments',
        ),
        'views'     => array(
            'title' => __( 'Views', 'youzify' ),
            'link'  => $profile,
        ),
        'ratings'   => array(
            'title' => __( 'Ratings', 'youzify' ),
            'link'  => $profile . 'reviews',
        ),
        'followers' => array(
            'title' => __( 'Followers', 'youzify' ),
            'link'  => $profile . 'follows/followers',
        ),
        'following' => array(
            'title' => __( 'Following', 'youzify' ),
            'link'  => $profile . 'follows',
        ),
        'points'    => array(
            'title' => __( 'Points', 'youzify' ),
            'link'  => $profile,
        )
    );

    return apply_filters( 'youzify_get_user_statistics_details', $statistics );

}

/**
 * Sync WP & BP Fields.
 */
function youzify_sync_bp_and_wp_fields( $field_id, $value ) {

    // Get User ID
    $user_id = bp_displayed_user_id();

    // Sync Fields
    $fields = youzify_get_youzify_xprofile_fields();

    // Get Field Key.
    $field_key = array_search( $field_id, $fields, true );

    if ( ! empty( $field_key ) ) {
        wp_update_user( array( 'ID' => $user_id, $field_key => $value ) );
    }

}

add_action( 'xprofile_profile_field_data_updated', 'youzify_sync_bp_and_wp_fields', 10, 2 );


/**
 * Get Settings Url.
 */
// function youzify_get_settings_url( $slug = false, $user_id = null ) {

//     if ( ! bp_is_active( 'settings' ) ) {
//         return false;
//     }

//     // Get User ID.
//     $user_id =! empty( $user_id ) ? $user_id :  bp_displayed_user_id();

//     // Get User Settings Page Url.
//     $url = bp_members_get_user_url( $user_id ) . bp_get_settings_slug() . '/';

//     if ( $slug ) {
//         $url = $url . $slug;
//     }

//     return $url;
// }

/**
 * Get Profile Url.
 */
function youzify_get_profile_settings_url( $slug = false, $user_id = null ) {

    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

    // Get User Profile Settings Page Url.
    $url = bp_members_get_user_url( $user_id ) . bp_get_profile_slug() . '/';

    if ( ! empty( $slug ) ) {
        $url = $url . $slug;
    } else {
        $url .= apply_filters( 'youzify_profile_settings_default_tab', 'edit/group/1' );
    }

    return $url;
}

/**
 * Get Widgets Settings Url.
 */
function youzify_get_widgets_settings_url( $slug = false, $user_id = null ) {

    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

    // Get User Widgets Settings Page Url.
    $url = bp_members_get_user_url( $user_id ) . 'widgets/';

    if ( $slug ) {
        $url = $url . $slug;
    }

    return $url;
}