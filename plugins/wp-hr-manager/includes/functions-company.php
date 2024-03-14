<?php

/**
 * Remove a company locaiton
 *
 * @param  int  $location_id
 *
 * @return bool
 */
function wphr_company_location_delete( $location_id ) {
    global $wpdb;

    do_action( 'wphr_company_location_delete', $location_id );

    return $wpdb->delete( $wpdb->prefix . 'wphr_company_locations', array( 'id' => $location_id ) );
}

/**
 * Get a companies locations
 *
 * @param int $company_id
 *
 * @return array
 */
function wphr_company_get_locations() {
    global $wpdb;

    $cache_key = 'wphr_company-locations';
    $locations = wp_cache_get( $cache_key, 'wphr' );

    if ( ! $locations ) {
        $locations = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wphr_company_locations" );

        $company = new \WPHR\HR_MANAGER\Company();

        $main_location = (object) [
            'id' => -1,
            'company_id' => null,
            'name' => wphr_get_company_default_location_name(),
            'address_1' => $company->address['address_1'],
            'address_2' => $company->address['address_2'],
            'city' => $company->address['city'],
            'state' => $company->address['state'],
            'zip' => isset( $company->address['zip'] ) ? $company->address['zip'] : $company->address['postcode'],
            'country' => $company->address['country'],
            'fax' => $company->fax,
            'phone' => $company->phone,
            'created_at' => null,
            'updated_at' => null,
        ];

        array_unshift( $locations , $main_location );

        wp_cache_set( $cache_key, $locations, 'wphr' );
    }

    return $locations;
}

/**
 * Get a company location prepared for dropdown
 *
 * @param int     $company_id
 * @param string  $select_label pass any string to be as the first element
 *
 * @return array
 */
function wphr_company_get_location_dropdown_raw( $select_label = null ) {
    $locations = wphr_company_get_locations();
    $dropdown  = [];

    if ( $select_label ) {
        $dropdown    = array( '-1' => $select_label );
    }

    foreach ( $locations as $location ) {
        $dropdown[ $location->id ] = $location->name;
    }

    return $dropdown;
}

/**
 * Get working days of a company
 *
 * @return array
 */
function wphr_company_get_working_days() {
    $default = array(
        'mon' => 8,
        'tue' => 8,
        'wed' => 8,
        'thu' => 8,
        'fri' => 8,
        'sat' => 0,
        'sun' => 0
    );

    $option_key = 'wphr_hr_work_days';
    $saved      = get_option( $option_key, $default );

    if ( ! is_array( $saved ) || count( $saved ) < 7 ) {
        return $default;
    }

    return array_map( 'absint', $saved );
}

/**
 * Company's default location name
 *
 * You can filter this and change it to "Head Office" or something like that
 *
 * @since 1.1.12
 *
 * @return string
 */
function wphr_get_company_default_location_name() {
    return apply_filters( 'wphr-company-default-name', __( 'Main Location', 'wphr' ) );
}
