<?php
/*
 * Get ARMember triggers
 */
function adfoin_armember_get_forms( $form_provider ) {
    if ( $form_provider != 'armember' ) {
        return;
    }

    $triggers = array(
        // 'userRegisterForm' => __( 'User Registered by ARForm', 'advanced-form-integration' ),
        // 'userUpdateForm' => __( 'User Updated by ARForm', 'advanced-form-integration' ),
        // 'userAddByAdmin' => __( 'User Added by Admin', 'advanced-form-integration' ),
        'userCancelSubscription' => __( 'User Cancelled Subscription', 'advanced-form-integration' ),
        'userChangePlanByAdmin' => __( 'User Plan Changed by Admin', 'advanced-form-integration' ),
        'userRenewPlan' => __( 'User Renewed Plan', 'advanced-form-integration' ),
    );

    return $triggers;
}

/*
 * Get ARMember fields
 */
function adfoin_armember_get_form_fields( $form_provider, $form_id ) {
    if ( $form_provider != 'armember' ) {
        return;
    }

    $fields = array();

    switch ( $form_id ) {
        case 'userRegisterForm':
        case 'userUpdateForm':
        case 'userAddByAdmin':
            $fields['user_id'] = __( 'User ID', 'advanced-form-integration' );
            $fields['first_name'] = __( 'First Name', 'advanced-form-integration' );
            $fields['last_name'] = __( 'Last Name', 'advanced-form-integration' );
            $fields['nickname'] = __( 'Nick Name', 'advanced-form-integration' );
            $fields['avatar_url'] = __( 'Avatar URL', 'advanced-form-integration' );
            $fields['user_email'] = __( 'Email', 'advanced-form-integration' );
            break;

        case 'userCancelSubscription':
        case 'userChangePlanByAdmin':
        case 'userRenewPlan':
            $fields['user_id'] = __( 'User ID', 'advanced-form-integration' );
            $fields['arm_user_nicename'] = __( 'User Nick Name', 'advanced-form-integration' );
            $fields['arm_user_email'] = __( 'User Email', 'advanced-form-integration' );
            $fields['arm_display_name'] = __( 'Display Name', 'advanced-form-integration' );
            $fields['arm_subscription_plan'] = __( 'Subscription Plan', 'advanced-form-integration' );
            $fields['arm_subscription_plan_id'] = __( 'Subscription Plan ID', 'advanced-form-integration' );
            $fields['access_type'] = __( 'Access Type', 'advanced-form-integration' );
            $fields['payment_type'] = __( 'Payment Type', 'advanced-form-integration' );
            $fields['price_text'] = __( 'Price Text', 'advanced-form-integration' );
            $fields['arm_subscription_plan_amount'] = __( 'Subscription Plan Amount', 'advanced-form-integration' );
            $fields['arm_subscription_plan_description'] = __( 'Subscription Plan Description', 'advanced-form-integration' );
            $fields['arm_subscription_plan_role'] = __( 'Subscription Plan Role', 'advanced-form-integration' );
            break;
    }

    return $fields;
}

function adfoin_armember_get_userdata( $user_id ) {
    $user_data = array();
    $user      = get_userdata( $user_id );

    if ( $user ) {
        $user_data["user_id"]    = $user->ID;
        $user_data["first_name"] = $user->first_name;
        $user_data["last_name"]  = $user->last_name;
        $user_data["nickname"]   = $user->nickname;
        $user_data["avatar_url"] = get_avatar_url( $user->ID );
        $user_data["email"]      = $user->user_email;
    }

    return $user_data;
}

function adfoin_armember_get_plan( $plan_id ) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'arm_subscription_plans';
    $plan = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE `arm_subscription_plan_id` = %d", $plan_id ) );

    if ( empty( $plan ) ) {
        return;
    }

    $fields = array();
    $keys = array( 'arm_subscription_plan_id', 'arm_subscription_plan_name', 'arm_subscription_plan_options', 'arm_subscription_plan_description', 'arm_subscription_plan_amount', 'arm_subscription_plan_role' );

    foreach ( $keys as $key ) {
        if ( isset( $plan->$key ) ) {
            if ( $key === 'arm_subscription_plan_options' ) {
                $value = maybe_unserialize( $plan->$key );
                $fields = array_merge( $fields, $value );
            } else {
                $fields[$key] = $plan->$key;
            }
        }
    }

    return $fields;
}

// Send data
function adfoin_armember_send_data( $saved_records, $posted_data ) {
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

add_action( 'userCancelSubscription', 'adfoin_armember_user_cancel_subscription', 10, 2 );
add_action( 'arm_after_user_plan_change_by_admin', 'adfoin_armember_user_change_plan_by_admin', 10, 2 );
add_action( 'arm_after_user_plan_renew', 'adfoin_armember_user_renew_plan', 10, 2 );

function adfoin_armember_user_cancel_subscription( $user_id, $posted_data ) {
    adfoin_armember_process_user_action( $user_id, $posted_data, 'userCancelSubscription' );
}

function adfoin_armember_user_change_plan_by_admin( $user_id, $posted_data ) {
    adfoin_armember_process_user_action( $user_id, $posted_data, 'userChangePlanByAdmin' );
}

function adfoin_armember_user_renew_plan( $user_id, $posted_data ) {
    adfoin_armember_process_user_action( $user_id, $posted_data, 'userRenewPlan' );
}

function adfoin_armember_process_user_action( $user_id, $posted_data, $trigger ) {
    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'armember', $trigger );

    if ( empty( $saved_records ) ) {
        return;
    }

    $user_data = adfoin_armember_get_userdata( $user_id );
    $plan_data = adfoin_armember_get_plan( $posted_data['arm_subscription_plan'] );

    $data = array(
        'user_id' => $user_data['user_id'],
        'first_name' => $user_data['first_name'],
        'last_name' => $user_data['last_name'],
        'nickname' => $user_data['nickname'],
        'avatar_url' => $user_data['avatar_url'],
        'user_email' => $user_data['email'],
        'arm_user_nicename' => $posted_data['arm_user_nicename'],
        'arm_user_email' => $posted_data['arm_user_email'],
        'arm_display_name' => $posted_data['arm_display_name'],
        'arm_subscription_plan' => $plan_data['arm_subscription_plan_name'],
        'arm_subscription_plan_id' => $plan_data['arm_subscription_plan_id'],
        'access_type' => $posted_data['access_type'],
        'payment_type' => $posted_data['payment_type'],
        'price_text' => $posted_data['price_text'],
        'arm_subscription_plan_amount' => $plan_data['arm_subscription_plan_amount'],
        'arm_subscription_plan_description' => $plan_data['arm_subscription_plan_description'],
        'arm_subscription_plan_role' => $plan_data['arm_subscription_plan_role'],
    );

    adfoin_armember_send_data( $saved_records, $data );
}