<?php
/*
 * Get GiveWP triggers
 */
function adfoin_givewp_get_forms( $form_provider ) {
    if ( $form_provider != 'givewp' ) {
        return;
    }

    $triggers = array(
        'donationViaForm'    => __( 'User makes donation via form', 'advanced-form-integration' ),
        'cancelRecurViaForm' => __( 'User cancels recurring donation via form', 'advanced-form-integration' ),
        'subscriptionCreated' => __( 'Subscription created', 'advanced-form-integration' ),
        'subscriptionUpdated' => __( 'Subscription updated', 'advanced-form-integration' ),
        // 'continueRecur'      => __( 'User continues recurring donation', 'advanced-form-integration' ),
    );

    return $triggers;
}

/*
 * Get GiveWP fields
 */
function adfoin_givewp_get_form_fields( $form_provider, $form_id ) {
    if( $form_provider != 'givewp' ) {
        return;
    }

    $fields = array();

    if( in_array( $form_id, array( 'donationViaForm' ) ) ) {
        $fields['title']       = __( 'Title', 'advanced-form-integration' );
        $fields['first_name']  = __( 'First Name', 'advanced-form-integration' );
        $fields['last_name']   = __( 'Last Name', 'advanced-form-integration' );
        $fields['email']       = __( 'Email', 'advanced-form-integration' );
        $fields['donor_id']    = __( 'Donor ID', 'advanced-form-integration' );
        $fields['amount']      = __( 'Amount', 'advanced-form-integration' );
        $fields['currency']    = __( 'Currency', 'advanced-form-integration' );
        $fields['address1']    = __( 'Address 1', 'advanced-form-integration' );
        $fields['address2']    = __( 'Address 2', 'advanced-form-integration' );
        $fields['city']        = __( 'City', 'advanced-form-integration' );
        $fields['state']       = __( 'State', 'advanced-form-integration' );
        $fields['zip']         = __( 'Zip', 'advanced-form-integration' );
        $fields['country']     = __( 'Country', 'advanced-form-integration' );
        $fields['form_id']     = __( 'Form ID', 'advanced-form-integration' );
        $fields['form_title']  = __( 'Form Title', 'advanced-form-integration' );
        $fields['price_id']    = __( 'Price ID', 'advanced-form-integration' );
        $fields['comment']     = __( 'Comment', 'advanced-form-integration' );
        $fields['payment_mode'] = __( 'Payment Mode', 'advanced-form-integration' );
    }

    if( in_array( $form_id, array( 'subscriptionCreated' ) ) ) {
        $fields['sub_id']      = __( 'Subscription ID', 'advanced-form-integration' );
        $fields['form_id']     = __( 'Form ID', 'advanced-form-integration' );
        $fields['period']      = __( 'Period', 'advanced-form-integration' );
        $fields['frequency']   = __( 'Frequency', 'advanced-form-integration' );
        $fields['initial_amount'] = __( 'Initial Amount', 'advanced-form-integration' );
        $fields['recurring_amount'] = __( 'Recurring Amount', 'advanced-form-integration' );
        $fields['recurring_fee_amount'] = __( 'Recurring Fee Amount', 'advanced-form-integration' );
        $fields['bill_times']  = __( 'Bill Times', 'advanced-form-integration' );
        $fields['payment_mode'] = __( 'Payment Mode', 'advanced-form-integration' );
        $fields['created']     = __( 'Created', 'advanced-form-integration' );
        $fields['expiration']  = __( 'Expiration', 'advanced-form-integration' );
        $fields['status']      = __( 'Status', 'advanced-form-integration' );
        $fields['customer_id'] = __( 'Customer ID', 'advanced-form-integration' );
        $fields['first_name']  = __( 'First Name', 'advanced-form-integration' );
        $fields['last_name']   = __( 'Last Name', 'advanced-form-integration' );
        $fields['email']       = __( 'Email', 'advanced-form-integration' );
    }

    if( in_array( $form_id, array( 'cancelRecurViaForm' ) ) ) {
        $fields['sub_id']      = __( 'Subscription ID', 'advanced-form-integration' );
        $fields['form_id']     = __( 'Form ID', 'advanced-form-integration' );
        $fields['period']      = __( 'Period', 'advanced-form-integration' );
        $fields['frequency']   = __( 'Frequency', 'advanced-form-integration' );
        $fields['initial_amount'] = __( 'Initial Amount', 'advanced-form-integration' );
        $fields['recurring_amount'] = __( 'Recurring Amount', 'advanced-form-integration' );
        $fields['recurring_fee_amount'] = __( 'Recurring Fee Amount', 'advanced-form-integration' );
        $fields['bill_times']  = __( 'Bill Times', 'advanced-form-integration' );
        $fields['payment_mode'] = __( 'Payment Mode', 'advanced-form-integration' );
        $fields['created']     = __( 'Created', 'advanced-form-integration' );
        $fields['expiration']  = __( 'Expiration', 'advanced-form-integration' );
        $fields['status']      = __( 'Status', 'advanced-form-integration' );
        $fields['customer_id'] = __( 'Customer ID', 'advanced-form-integration' );
        $fields['first_name']  = __( 'First Name', 'advanced-form-integration' );
        $fields['last_name']   = __( 'Last Name', 'advanced-form-integration' );
        $fields['email']       = __( 'Email', 'advanced-form-integration' );
    }

    if( in_array( $form_id, array( 'subscriptionUpdated' ) ) ) {
        $fields['form_id']       = __( 'Form ID', 'advanced-form-integration' );
        $fields['amount']        = __( 'Amount', 'advanced-form-integration' );
        $fields['total_payment'] = __( 'Total Payment', 'advanced-form-integration' );
        $fields['donor']         = __( 'Donor', 'advanced-form-integration' );
        $fields['user_id']       = __( 'User ID', 'advanced-form-integration' );
    }

    return $fields;
}

function adfoin_givewp_get_userdata( $user_id ) {
    $user_data = array();
    $user      = get_userdata($user_id);

    if ($user) {
        $user_data["user_id"]    = $user->ID;
        $user_data["first_name"] = $user->first_name;
        $user_data["last_name"]  = $user->last_name;
        $user_data["email"] = $user->user_email;
    }

    return $user_data;
}

function adfoin_givewp_send_data( $saved_records, $posted_data ) {
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

add_action( 'give_update_payment_status', 'adfoin_update_payment_status', 10, 3 );

function adfoin_update_payment_status( $payment_id, $status, $old_status ) {

    if ( $status !== 'publish' ) {
        return;
    }
        
    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'givewp', 'donationViaForm' );

    if ( empty( $saved_records ) ) {
        return;
    }

    $payment = new Give_Payment( $payment_id );

    if ( empty( $payment ) || !isset( $payment->ID ) ) {
        return;
    }

    $form_id = $payment->form_id;

    $posted_data = json_decode( wp_json_encode( $payment ), true );
    $user_info   = give_get_payment_meta_user_info( $payment_id );

    if ( $user_info ) {
        $posted_data['title'] = $user_info['title'];
        $posted_data['first_name'] = $user_info['first_name'];
        $posted_data['last_name'] = $user_info['last_name'];
        $posted_data['email'] = $user_info['email'];
        $posted_data['address1'] = $user_info['address']['line1'];
        $posted_data['address2'] = $user_info['address']['line2'];
        $posted_data['city'] = $user_info['address']['city'];
        $posted_data['state'] = $user_info['address']['state'];
        $posted_data['zip'] = $user_info['address']['zip'];
        $posted_data['country'] = $user_info['address']['country'];
        $posted_data['donor_id'] = $user_info['donor_id'];
    }

    $posted_data['form_id'] = $form_id;
    $posted_data['form_title'] = $payment->form_title;
    $posted_data['currency'] = $payment->currency;
    $posted_data['price_id'] = $payment->price_id;
    $posted_data['amount'] = $payment->total;
    $posted_data['comment'] = $_REQUEST['give_comment'] ? sanitize_text_field( $_REQUEST['give_comment'] ) : '';
    $posted_data['payment_mode'] = $_REQUEST['payment-mode'] ? sanitize_text_field( $_REQUEST['payment-mode'] ) : '';

    adfoin_givewp_send_data( $saved_records, $posted_data );
}

add_action('give_subscription_cancelled', 'adfoin_givewp_subscription_cancelled', 10, 2 );

function adfoin_givewp_subscription_cancelled( $sub_id, $subscription ) {

    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'givewp', 'cancelRecurViaForm' );

    if (empty($saved_records)) {
        return;
    }

    $customer_id = $subscription['customer_id'] ?? 0;
    $form_id = $subscription['form_id'] ?? 0;
    $period = $subscription['period'] ?? '';
    $frequency = $subscription['frequency'] ?? 0;
    $initial_amount = $subscription['initial_amount']->formatToDecimal();
    $recurring_amount = $subscription['recurring_amount']->formatToDecimal();
    $recurring_fee_amount = $subscription['recurring_fee_amount'] ?? 0;
    $bill_times = $subscription['bill_times'] ?? 0;
    $payment_mode = $subscription['payment_mode'] ?? '';
    $created = $subscription['created'] ?? '';
    $expiration = $subscription['expiration'] ?? '';
    $status = $subscription['status'] ?? '';

    // get donor info using $customer_id
    $donor = new Give_Donor( $customer_id );

    $posted_data = array(
        'sub_id'     => $sub_id,
        'form_id'    => $form_id,
        'period'     => $period,
        'frequency'  => $frequency,
        'initial_amount' => $initial_amount,
        'recurring_amount' => $recurring_amount,
        'recurring_fee_amount' => $recurring_fee_amount,
        'bill_times' => $bill_times,
        'payment_mode' => $payment_mode,
        'created' => $created,
        'expiration' => $expiration,
        'status' => $status,
        'customer_id' => $customer_id,
        'first_name' => $donor->first_name,
        'last_name'  => $donor->last_name,
        'email'      => $donor->email,
    );

    adfoin_givewp_send_data( $saved_records, $posted_data );
}

add_action( 'give_subscription_inserted', 'adfoin_givewp_subscription_inserted', 10, 2 );

function adfoin_givewp_subscription_inserted( $sub_id, $subscription ) {

    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'givewp', 'subscriptionCreated' );

    if (empty($saved_records)) {
        return;
    }

    $customer_id = $subscription['customer_id'] ?? 0;
    $form_id = $subscription['form_id'] ?? 0;
    $period = $subscription['period'] ?? '';
    $frequency = $subscription['frequency'] ?? 0;
    $initial_amount = $subscription['initial_amount']->formatToDecimal();
    $recurring_amount = $subscription['recurring_amount']->formatToDecimal();
    $recurring_fee_amount = $subscription['recurring_fee_amount'] ?? 0;
    $bill_times = $subscription['bill_times'] ?? 0;
    $payment_mode = $subscription['payment_mode'] ?? '';
    $created = $subscription['created'] ?? '';
    $expiration = $subscription['expiration'] ?? '';
    $status = $subscription['status'] ?? '';
    $donor = new Give_Donor( $customer_id );

    $posted_data = array(
        'sub_id'     => $sub_id,
        'form_id'    => $form_id,
        'period'     => $period,
        'frequency'  => $frequency,
        'initial_amount' => $initial_amount,
        'recurring_amount' => $recurring_amount,
        'recurring_fee_amount' => $recurring_fee_amount,
        'bill_times' => $bill_times,
        'payment_mode' => $payment_mode,
        'created' => $created,
        'expiration' => $expiration,
        'status' => $status,
        'customer_id' => $customer_id,
        'first_name' => $donor->first_name,
        'last_name'  => $donor->last_name,
        'email'      => $donor->email,
    );

    adfoin_givewp_send_data( $saved_records, $posted_data );
}

add_action('give_subscription_updated', 'adfoin_givewp_subscription_updated', 10, 4 );

function adfoin_givewp_subscription_updated( $status, $subscription_id, $data, $where ) {

    $integration   = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'givewp', 'subscriptionUpdated' );

    if (empty($saved_records)) {
        return;
    }

    $subscription = new Give_Subscription( $subscription_id );
    $amount = $subscription->recurring_amount;
    $form_id = $subscription->form_id;
    $total_payment = $subscription->get_total_payments();
    $donor = $subscription->donor;

    $posted_data = array(
        'form_id' => $form_id,
        'amount' => $amount,
        'total_payment' => $total_payment,
        'donor' => $donor,
        'user_id' => $donor->user_id,
        'first_name' => $donor->first_name,
        'last_name' => $donor->last_name,
        'email' => $donor->email
    );

    adfoin_givewp_send_data( $saved_records, $posted_data );
}