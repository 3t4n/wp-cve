<?php
// Get PMPro triggers
function adfoin_paidmembershippro_get_triggers( $form_provider ) {
    if( $form_provider !== 'paidmembershippro' ) {
        return;
    }
    $triggers = array(
        'membershipLevelAssigned' => __( 'Membership level assigned to a user', 'advanced-form-integration' ),
        'membershipCanceled'      => __( 'User cancels a membership', 'advanced-form-integration' ),
        'membershipPurchased'     => __( 'User purchases a membership', 'advanced-form-integration' ),
        'membershipExpired'        => __( 'Subscription to a membership expires', 'advanced-form-integration' )
    );
    
    return $triggers;
}

// Get PMPro fields
function adfoin_paidmembershippro_get_form_fields( $form_provider, $trigger ) {
    if( $form_provider !== 'paidmembershippro' ) {
        return;
    }
    $fields = array();

    if( in_array( $trigger, array( 'membershipLevelAssigned', 'membershipCanceled', 'membershipPurchased', 'membershipExpired' ) ) ) {
        $fields['membershipId'] = __( 'Membership ID', 'advanced-form-integration' );
        $fields['membershipName'] = __( 'Membership Name', 'advanced-form-integration' );
        $fields['description'] = __( 'Description', 'advanced-form-integration' );
        $fields['confirmation'] = __( 'Confirmation', 'advanced-form-integration' );
        $fields['initialPayment'] = __( 'Initial Payment', 'advanced-form-integration' );
        $fields['billingAmount'] = __( 'Billing Amount', 'advanced-form-integration' );
    }

    $fields['user_id'] = __( 'User ID', 'advanced-form-integration' );
    $fields['first_name'] = __( 'First Name', 'advanced-form-integration' );
    $fields['last_name'] = __( 'Last Name', 'advanced-form-integration' );
    $fields['nickname'] = __( 'Nick Name', 'advanced-form-integration' );
    $fields['avatar_url'] = __( 'Avatar URL', 'advanced-form-integration' );
    $fields['user_email'] = __( 'Email', 'advanced-form-integration' );

    return $fields;
}

function adfoin_paidmembershippro_get_userdata( $user_id ) {
    $user_data = array();
    $user      = get_userdata($user_id);

    if ($user) {
        $user_data["user_id"]    = $user->ID;
        $user_data["first_name"] = $user->first_name;
        $user_data["last_name"]  = $user->last_name;
        $user_data["nickname"]   = $user->nickname;
        $user_data["avatar_url"] = get_avatar_url($user->ID);
        $user_data["email"] = $user->user_email;
    }

    return $user_data;
}

// Send data
function adfoin_paidmembershippro_send_data( $saved_records, $posted_data ) {
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

add_action( 'pmpro_after_change_membership_level', 'adfoin_paidmembershippro_change_membership_level', 10, 3 );

function adfoin_paidmembershippro_change_membership_level( $level_id, $user_id, $cancel_level ) {
    if( $level_id == 0 ) {
        return;
    }

    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'paidmembershippro', 'membershipLevelAssigned' );

    if ( empty( $saved_records ) ) {
        return;
    }

    $level = pmpro_getSpecificMembershipLevelForUser( $user_id, $level_id );

    $data = array(
        'membershipId'   => $level->id,
        'membershipName' => $level->name,
        'description'    => $level->description,
        'confirmation'   => $level->confirmation,
        'initialPayment' => $level->initial_payment,
        'billingAmount'  => $level->billing_amount
    );

    $user_data = adfoin_paidmembershippro_get_userdata( $user_id );
    $data = array_merge( $data, $user_data );

    adfoin_paidmembershippro_send_data( $saved_records, $data );
}

add_action( 'pmpro_after_change_membership_level', 'adfoin_paidmembershippro_cancel_membership_level', 10, 3 );

function adfoin_paidmembershippro_cancel_membership_level( $level_id, $user_id, $cancel_level ) {
    if( 0 !== absint( $level_id ) ) {
        return;
    }

    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'paidmembershippro', 'membershipCanceled' );

    if ( empty( $saved_records ) ) {
        return;
    }

    $level = pmpro_getSpecificMembershipLevelForUser( $user_id, $level_id );

    $data = array(
        'membershipId'   => $level->id,
        'membershipName' => $level->name,
        'description'    => $level->description,
        'confirmation'   => $level->confirmation,
        'initialPayment' => $level->initial_payment,
        'billingAmount'  => $level->billing_amount
    );

    $user_data = adfoin_paidmembershippro_get_userdata( $user_id );
    $data = array_merge( $data, $user_data );

    adfoin_paidmembershippro_send_data( $saved_records, $data );
}

add_action( 'pmpro_after_checkout', 'adfoin_paidmembershippro_purchase_membership_level', 10, 2 );

function adfoin_paidmembershippro_purchase_membership_level( $user_id, $order ) {
    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'paidmembershippro', 'membershipPurchased' );

    if ( empty( $saved_records ) ) {
        return;
    }

    $user = $order->getUser();
    $membership = $order->getMembershipLevel();
    $user_id = $user->ID;
    $membership_id = $membership->id;

    $level = pmpro_getSpecificMembershipLevelForUser( $user_id, $membership_id );

    $data = array(
        'membershipId'   => $level->id,
        'membershipName' => $level->name,
        'description'    => $level->description,
        'confirmation'   => $level->confirmation,
        'initialPayment' => $level->initial_payment,
        'billingAmount'  => $level->billing_amount
    );

    $user_data = adfoin_paidmembershippro_get_userdata( $user_id );
    $data = array_merge( $data, $user_data );

    adfoin_paidmembershippro_send_data( $saved_records, $data );
}

add_action( 'pmpro_membership_post_membership_expiry', 'adfoin_paidmembershippro_membership_expiry', 10, 2 );

function adfoin_paidmembershippro_membership_expiry( $user_id, $membership_id ) {
    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'paidmembershippro', 'membershipExpired' );

    if ( empty( $saved_records ) ) {
        return;
    }

    $level = pmpro_getSpecificMembershipLevelForUser( $user_id, $membership_id );

    $data = array(
        'membershipId'   => $level->id,
        'membershipName' => $level->name,
        'description'    => $level->description,
        'confirmation'   => $level->confirmation,
        'initialPayment' => $level->initial_payment,
        'billingAmount'  => $level->billing_amount
    );

    $user_data = adfoin_paidmembershippro_get_userdata( $user_id );
    $data = array_merge( $data, $user_data );

    adfoin_paidmembershippro_send_data( $saved_records, $data );
}

