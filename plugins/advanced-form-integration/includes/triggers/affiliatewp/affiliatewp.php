<?php

// Get AffiliateWP Triggers
function adfoin_affiliatewp_get_forms( $form_provider ) {
    if( $form_provider != 'affiliatewp' ) {
        return;
    }

    $triggers = array(
        'affiliation_approved' => 'Affiliation Approved',
        'user_becomes_affiliate' => 'User Becomes Affiliate',
        'affiliate_makes_referral' => 'Affiliate Makes Referral',
        'referral_rejected' => 'Referral Rejected',
        'referral_paid' => 'Referral Paid',
    );

    return $triggers;
}

// Get AffiliateWP fields
function adfoin_affiliatewp_get_form_fields( $form_provider, $form_id ) {
    if( $form_provider != 'affiliatewp' ) {
        return;
    }

    $fields = array();

    if( in_array( $form_id, array( 'affiliation_approved', 'user_becomes_affiliate' ) ) ) {
        $fields['status'] = __( 'Status', 'advanced-form-integration' );
        $fields['flat_rate_basis'] = __( 'Flat Rate Basis', 'advanced-form-integration' );
        $fields['payment_email'] = __( 'Payment Email', 'advanced-form-integration' );
        $fields['rate_type'] = __( 'Rate Type', 'advanced-form-integration' );
        $fields['affiliate_note'] = __( 'Affiliate Note', 'advanced-form-integration' );
        $fields['old_status'] = __( 'Old Status', 'advanced-form-integration' );
    } elseif( in_array( $form_id, array( 'affiliate_makes_referral' ) ) ) {
        $fields['affiliate_id'] = __( 'Affiliate ID', 'advanced-form-integration' );
        $fields['affiliate_url'] = __( 'Affiliate URL', 'advanced-form-integration' );
        $fields['referral_description'] = __( 'Referral Description', 'advanced-form-integration' );
        $fields['amount'] = __( 'Amount', 'advanced-form-integration' );
        $fields['context'] = __( 'Context', 'advanced-form-integration' );
        $fields['reference'] = __( 'Reference', 'advanced-form-integration' );
        $fields['campaign'] = __( 'Campaign', 'advanced-form-integration' );
        $fields['flat_rate_basis'] = __( 'Flat Rate Basis', 'advanced-form-integration' );
        $fields['account_email'] = __( 'Account Email', 'advanced-form-integration' );
        $fields['payment_email'] = __( 'Payment Email', 'advanced-form-integration' );
        $fields['rate_type'] = __( 'Rate Type', 'advanced-form-integration' );
        $fields['affiliate_note'] = __( 'Affiliate Note', 'advanced-form-integration' );
    } elseif( in_array( $form_id, array( 'referral_rejected', 'referral_paid' ) ) ) {
        $fields['affiliate_id'] = __( 'Affiliate ID', 'advanced-form-integration' );
        $fields['affiliate_url'] = __( 'Affiliate URL', 'advanced-form-integration' );
        $fields['referral_description'] = __( 'Referral Description', 'advanced-form-integration' );
        $fields['amount'] = __( 'Amount', 'advanced-form-integration' );
        $fields['context'] = __( 'Context', 'advanced-form-integration' );
        $fields['reference'] = __( 'Reference', 'advanced-form-integration' );
        $fields['campaign'] = __( 'Campaign', 'advanced-form-integration' );
        $fields['status'] = __( 'Status', 'advanced-form-integration' );
        $fields['flat_rate_basis'] = __( 'Flat Rate Basis', 'advanced-form-integration' );
        $fields['account_email'] = __( 'Account Email', 'advanced-form-integration' );
        $fields['payment_email'] = __( 'Payment Email', 'advanced-form-integration' );
        $fields['rate_type'] = __( 'Rate Type', 'advanced-form-integration' );
        $fields['affiliate_note'] = __( 'Affiliate Note', 'advanced-form-integration' );
        $fields['old_status'] = __( 'Old Status', 'advanced-form-integration' );
    }

    return $fields;
}

// Get User Data
function adfoin_affiliatewp_get_userdata( $user_id ) {
    $user_data = array();
    $user      = get_userdata($user_id);

    if( $user ) {
        $user_data['first_name'] = $user->first_name;
        $user_data['last_name']  = $user->last_name;
        $user_data['nickname']   = $user->nickname;
        $user_data['avatar_url'] = get_avatar_url($user_id);
        $user_data['user_email'] = $user->user_email;
        $user_data['user_id']    = $user_id;
    }

    return $user_data;
}

// Send data
function adfoin_affiliatewp_send_data( $saved_records, $posted_data ) {
    $job_queue = get_option( 'adfoin_general_settings_job_queue' );

    foreach ($saved_records as $record) {
        $action_provider = $record['action_provider'];
        if ( $job_queue ) {
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

add_action( 'affwp_set_affiliate_status', 'adfoin_affiliatewp_new_affiliate_approved', 10, 3 );

function adfoin_affiliatewp_new_affiliate_approved( $affiliate_id, $status, $old_status ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'affiliatewp', 'affiliation_approved' );

    if( empty( $saved_records ) ) {
        return;
    }

    $user_id = affwp_get_affiliate_user_id( $affiliate_id );

    if( !$user_id || $status == 'pending' ) {
        return;
    }

    $affiliate = affwp_get_affiliate( $affiliate_id );
    $posted_data = array();

    $posted_data['status'] = $status;
    $posted_data['flat_rate_basis'] = $affiliate->flat_rate_basis;
    $posted_data['payment_email'] = $affiliate->payment_email;
    $posted_data['rate_type'] = $affiliate->rate_type;
    $posted_data['affiliate_note'] = $affiliate->affiliate_note;
    $posted_data['old_status'] = $old_status;

    adfoin_affiliatewp_send_data( $saved_records, $posted_data );
}

add_action( 'affwp_set_affiliate_status', 'adfoin_affiliatewp_user_becomes_affiliate', 10, 3 );

function adfoin_affiliatewp_user_becomes_affiliate( $affiliate_id, $status, $old_status ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'affiliatewp', 'user_becomes_affiliate' );

    if( empty( $saved_records ) ) {
        return;
    }

    $user_id = affwp_get_affiliate_user_id( $affiliate_id );

    if( !$user_id ) {
        return;
    }

    $affiliate = affwp_get_affiliate( $affiliate_id );
    $posted_data = array();

    $posted_data['status'] = $status;
    $posted_data['flat_rate_basis'] = $affiliate->flat_rate_basis;
    $posted_data['payment_email'] = $affiliate->payment_email;
    $posted_data['rate_type'] = $affiliate->rate_type;
    $posted_data['affiliate_note'] = $affiliate->affiliate_note;
    $posted_data['old_status'] = $old_status;

    adfoin_affiliatewp_send_data( $saved_records, $posted_data );
}

add_action( 'affwp_insert_referral', 'adfoin_affiliatewp_affiliate_makes_referral', 20, 1 );

function adfoin_affiliatewp_affiliate_makes_referral( $referral_id ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'affiliatewp', 'affiliate_makes_referral' );

    if( empty( $saved_records ) ) {
        return;
    }

    $referral = affwp_get_referral( $referral_id );
    $affiliate = affwp_get_affiliate( $referral->affiliate_id );
    $user_id = affwp_get_affiliate_user_id( $referral->affiliate_id );
    $affilliate_note = json_encode( affwp_get_affiliate_meta( $affiliate->affiliate_id, 'notes', true ) );
    $user = adfoin_affiliatewp_get_userdata( $user_id );
    $posted_data = array();

    $posted_data['affiliate_id'] = $referral->affiliate_id;
    $posted_data['affiliate_url'] = json_encode(affwp_get_affiliate_referral_url(array( 'affiliate_id' => $referral->affiliate_id )));
    $posted_data['referral_description'] = $referral->description;
    $posted_data['amount'] = $referral->amount;
    $posted_data['context'] = $referral->context;
    $posted_data['reference'] = $referral->reference;
    $posted_data['campaign'] = $referral->campaign;
    $posted_data['flat_rate_basis'] = $affiliate->flat_rate_basis;
    $posted_data['account_email'] = $user['user_email'];
    $posted_data['payment_email'] = $affiliate->payment_email;
    $posted_data['rate_type'] = $affiliate->rate_type;
    $posted_data['affiliate_note'] = $affilliate_note;

    adfoin_affiliatewp_send_data( $saved_records, $posted_data );
}

add_action( 'affwp_set_referral_status', 'adfoin_affiliatewp_affiliates_referral_specific_type_rejected', 99, 3 );

function adfoin_affiliatewp_affiliates_referral_specific_type_rejected( $referral_id, $status, $old_status ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'affiliatewp', 'referral_rejected' );

    if( empty( $saved_records ) ) {
        return;
    }

    $referral = affwp_get_referral( $referral_id );
    $affiliate = affwp_get_affiliate( $referral->affiliate_id );
    $user_id = affwp_get_affiliate_user_id( $referral->affiliate_id );
    $affilliate_note = json_encode( affwp_get_affiliate_meta( $affiliate->affiliate_id, 'notes', true ) );
    $user = adfoin_affiliatewp_get_userdata( $user_id );
    $posted_data = array();

    $posted_data['affiliate_id'] = $referral->affiliate_id;
    $posted_data['affiliate_url'] = json_encode(affwp_get_affiliate_referral_url(array( 'affiliate_id' => $referral->affiliate_id )));
    $posted_data['referral_description'] = $referral->description;
    $posted_data['amount'] = $referral->amount;
    $posted_data['context'] = $referral->context;
    $posted_data['reference'] = $referral->reference;
    $posted_data['campaign'] = $referral->campaign;
    $posted_data['status'] = $status;
    $posted_data['flat_rate_basis'] = $affiliate->flat_rate_basis;
    $posted_data['account_email'] = $user['user_email'];
    $posted_data['payment_email'] = $affiliate->payment_email;
    $posted_data['rate_type'] = $affiliate->rate_type;
    $posted_data['affiliate_note'] = $affilliate_note;
    $posted_data['old_status'] = $old_status;

    adfoin_affiliatewp_send_data( $saved_records, $posted_data );
}

add_action( 'affwp_set_referral_status', 'adfoin_affiliatewp_affiliates_referral_specific_type_paid', 99, 3 );

function adfoin_affiliatewp_affiliates_referral_specific_type_paid( $referral_id, $status, $old_status ) {
    $integration = new Advanced_Form_Integration_Integration();
    $saved_records = $integration->get_by_trigger( 'affiliatewp', 'referral_paid' );

    if( empty( $saved_records ) ) {
        return;
    }

    $referral = affwp_get_referral( $referral_id );
    $affiliate = affwp_get_affiliate( $referral->affiliate_id );
    $user_id = affwp_get_affiliate_user_id( $referral->affiliate_id );
    $affilliate_note = json_encode( affwp_get_affiliate_meta( $affiliate->affiliate_id, 'notes', true ) );
    $user = adfoin_affiliatewp_get_userdata( $user_id );
    $posted_data = array();

    $posted_data['affiliate_id'] = $referral->affiliate_id;
    $posted_data['affiliate_url'] = json_encode(affwp_get_affiliate_referral_url(array( 'affiliate_id' => $referral->affiliate_id )));
    $posted_data['referral_description'] = $referral->description;
    $posted_data['amount'] = $referral->amount;
    $posted_data['context'] = $referral->context;
    $posted_data['reference'] = $referral->reference;
    $posted_data['campaign'] = $referral->campaign;
    $posted_data['status'] = $status;
    $posted_data['flat_rate_basis'] = $affiliate->flat_rate_basis;
    $posted_data['account_email'] = $user['user_email'];
    $posted_data['payment_email'] = $affiliate->payment_email;
    $posted_data['rate_type'] = $affiliate->rate_type;
    $posted_data['affiliate_note'] = $affilliate_note;
    $posted_data['old_status'] = $old_status;

    adfoin_affiliatewp_send_data( $saved_records, $posted_data );
}