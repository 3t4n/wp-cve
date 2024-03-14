<?php
// Get GamiPress triggers
function adfoin_gamipress_get_forms( $form_provider ) {
    if( $form_provider != 'gamipress' ) {
        return;
    }

    $triggers = array(
        'rank_eanred' => __( 'Rank Earned', 'advanced-form-integration' ),
        'achievement_gained' => __( 'Achievement Gained', 'advanced-form-integration' ),
        'achievement_revoked' => __( 'Achievement Revoked', 'advanced-form-integration' ),
        'points_earned' => __( 'Points Earned', 'advanced-form-integration' )
    );

    return $triggers;
}

// Get GamiPress fields
function adfoin_gamipress_get_form_fields( $form_provider, $form_id ) {
    if( $form_provider != 'gamipress' ) {
        return;
    }

    $fields = array(
        'first_name' => __( 'First Name', 'advanced-form-integration' ),
        'last_name' => __( 'Last Name', 'advanced-form-integration' ),
        'user_email' => __( 'User Email', 'advanced-form-integration' ),
        'display_name' => __( 'Display Name', 'advanced-form-integration' )
    );

    if( $form_id == 'rank_eanred' ) {
        $fields['rank_type'] = __( 'Rank Type', 'advanced-form-integration' );
        $fields['rank'] = __( 'Rank', 'advanced-form-integration' );
    }

    if( $form_id == 'achievement_gained' ) {
        $fields['achievement_type'] = __( 'Achievement Type', 'advanced-form-integration' );
        $fields['award'] = __( 'Award', 'advanced-form-integration' );
    }

    if( $form_id == 'achievement_revoked' ) {
        $fields['post_id'] = __( 'Post ID', 'advanced-form-integration' );
        $fields['post_title'] = __( 'Post Title', 'advanced-form-integration' );
        $fields['post_url'] = __( 'Post URL', 'advanced-form-integration' );
        $fields['post_type'] = __( 'Post Type', 'advanced-form-integration' );
        $fields['post_author_id'] = __( 'Post Author ID', 'advanced-form-integration' );
        $fields['post_content'] = __( 'Post Content', 'advanced-form-integration' );
        $fields['post_parent_id'] = __( 'Post Parent ID', 'advanced-form-integration' );
    }

    if( $form_id == 'points_earned' ) {
        $fields['total_points'] = __( 'Total Points', 'advanced-form-integration' );
        $fields['points_type'] = __( 'Points Type', 'advanced-form-integration' );
        $fields['new_points'] = __( 'New Points', 'advanced-form-integration' );
    }

    return $fields;
}

// Get User data
function adfoin_gamipress_get_userdata( $user_id ) {
    $user_data = array();
    $user      = get_userdata( $user_id );

    if( $user ) {
        $user_data['first_name'] = $user->first_name;
        $user_data['last_name']  = $user->last_name;
        $user_data['avatar_url'] = get_avatar_url($user_id);
        $user_data['user_email'] = $user->user_email;
        $user_data['user_id']    = $user_id;
        $user_data['display_name'] = $user->display_name;
    }

    return $user_data;
}

// Send data
function adfoin_gamipress_send_data( $saved_records, $posted_data ) {
    $job_queue = get_option( 'adfoin_general_settings_job_queue' );

    foreach ($saved_records as $record) {
        $action_provider = $record['action_provider'];
        if ($job_queue) {
            as_enqueue_async_action( "adfoin_{$action_provider}_job_queue", array(
                'data' => array(
                    'record' => $record,
                    'posted_data' => $posted_data
                )
            ) );
        } else {
            call_user_func("adfoin_{$action_provider}_send_data", $record, $posted_data);
        }
    }
}

add_action( 'gamipress_update_user_rank', 'adfoin_gamipress_update_user_rank', 10, 5 );

function adfoin_gamipress_update_user_rank( $user_id, $new_rank, $old_rank, $admin_id, $achievement_id ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'gamipress', 'rank_eanred' );

    if( empty( $saved_records ) ) {
        return;
    }

    $user_data = adfoin_gamipress_get_userdata( $user_id );

    $posted_data = array(
        'user_id' => $user_id,
        'rank_type' => $new_rank->post_type,
        'rank' => $new_rank->post_title,
        'first_name' => $user_data['first_name'],
        'last_name' => $user_data['last_name'],
        'user_email' => $user_data['user_email'],
        'display_name' => $user_data['display_name']
    );

    adfoin_gamipress_send_data( $saved_records, $posted_data );

}

add_action( 'gamipress_award_achievement', 'adfoin_gamipress_award_achievement', 10, 5 );

function adfoin_gamipress_award_achievement( $user_id, $achievement_id, $trigger, $site_id, $args ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'gamipress', 'achievement_gained' );

    if( empty( $saved_records ) ) {
        return;
    }

    $user_data = adfoin_gamipress_get_userdata( $user_id );

    global $wpdb;

    $awards = $wpdb->get_results(
        "SELECT ID, post_name, post_title, post_type FROM wp_posts where id = {$achievement_id}"
    );

    $achievement_type = $awards[0]->post_type ? $awards[0]->post_type : '';
    $award = $awards[0]->post_name ? $awards[0]->post_name : '';

    $posted_data = array(
        'user_id'          => $user_id,
        'achievement_type' => $achievement_type,
        'award'            => $award,
        'first_name'       => $user_data['first_name'],
        'last_name'        => $user_data['last_name'],
        'user_email'       => $user_data['user_email'],
        'display_name'     => $user_data['display_name']
    );

    adfoin_gamipress_send_data( $saved_records, $posted_data );

}

add_action( 'gamipress_revoke_achievement_to_user', 'adfoin_gamipress_revoke_achievement_to_user', 10, 3 );

function adfoin_gamipress_revoke_achievement_to_user( $user_id, $achievement_id, $earning_id ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'gamipress', 'achievement_revoked' );

    if( empty( $saved_records ) ) {
        return;
    }

    $user_data = adfoin_gamipress_get_userdata( $user_id );
    $post_data = get_post( $achievement_id );
    $data = get_post( $post_data->post_parent );

    $posted_data = array(
        'user_id'          => $user_id,
        'post_id'          => $achievement_id,
        'post_title'       => $data->post_title,
        'post_url'         => get_permalink( $data->ID ),
        'post_type'        => $data->post_type,
        'post_author_id'   => $data->post_author,
        'post_content'     => $data->post_content,
        'post_parent_id'   => $data->post_parent,
        'first_name'       => $user_data['first_name'],
        'last_name'        => $user_data['last_name'],
        'user_email'       => $user_data['user_email'],
        'display_name'     => $user_data['display_name']
    );

    adfoin_gamipress_send_data( $saved_records, $posted_data );

}

add_action( 'gamipress_update_user_points', 'adfoin_gamipress_update_user_points', 10, 8 );

function adfoin_gamipress_update_user_points( $user_id, $new_points, $total_points, $admin_id, $achievement_id, $points_type, $reason, $log_type ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'gamipress', 'points_earned' );

    if( empty( $saved_records ) ) {
        return;
    }

    $user_data = adfoin_gamipress_get_userdata( $user_id );

    $posted_data = array(
        'user_id'      => $user_id,
        'total_points' => $total_points,
        'points_type'  => $points_type,
        'new_points'   => $new_points,
        'first_name'   => $user_data['first_name'],
        'last_name'    => $user_data['last_name'],
        'user_email'   => $user_data['user_email'],
        'display_name' => $user_data['display_name']
    );

    adfoin_gamipress_send_data( $saved_records, $posted_data );

}