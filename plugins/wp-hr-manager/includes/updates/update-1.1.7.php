<?php

/**
 * Update contact_owner meta in people and user meta table
 *
 * @since 1.1.7
 *
 * @return void
 **/
function wphr_update_poeple_meta_1_1_7() {
    \WPHR\ORM\WP\UserMeta::where( 'meta_key', '_assign_crm_agent' )->update( [ 'meta_key' => 'contact_owner' ] );
    \WPHR\HR_MANAGER\Framework\Models\Peoplemeta::where( 'meta_key', '_assign_crm_agent' )->update( [ 'meta_key' => 'contact_owner' ] );
}

/**
 * Sync people with wp user
 *
 * @since 1.1.7
 *
 * @return void
 **/
function wphr_update_poeples_1_1_7() {

    $people = \WPHR\HR_MANAGER\Framework\Models\People::whereNotNull( 'user_id' )->where( 'user_id', '!=', 0 )->get();

    $people->each( function ( $contact ) {
        $meta        = $contact->meta()->availableMeta()->lists( 'meta_value', 'meta_key' );
        $main_fields = $contact->toArray();

        $all_fields = array_merge( $main_fields, $meta );

        wphr_insert_people( $all_fields );
    } );
}

// Run udpater functions
wphr_update_poeple_meta_1_1_7();
wphr_update_poeples_1_1_7();
