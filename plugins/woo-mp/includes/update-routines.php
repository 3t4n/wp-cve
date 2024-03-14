<?php

namespace Woo_MP;

defined( 'ABSPATH' ) || die;

/**
 * Perform actions needed to update to new versions.
 */
class Update_Routines {

    /**
     * A list of all update routine method names categorized by the versions they are needed for.
     *
     * @var array
     */
    private $update_routines = [
        '1.9.0' => [
            'v_190_prefix_order_status',
        ],
    ];

    /**
     * Run all needed update routines.
     *
     * @return void
     */
    public function run_routines() {
        $current_data_version = get_option( 'woo_mp_data_version', '1.0.0' );

        foreach ( $this->update_routines as $version => $routines ) {
            if ( version_compare( $current_data_version, $version, '<' ) ) {
                foreach ( $routines as $routine ) {
                    $this->$routine();
                }
            }
        }

        update_option( 'woo_mp_data_version', $this->get_latest_data_version() );
    }

    /**
     * Get the latest data version.
     *
     * @return string The version.
     */
    public function get_latest_data_version() {
        $versions = array_keys( $this->update_routines );

        return end( $versions );
    }

    /**
     * Previously, the "Update Order Status To" option values were unprefixed (ommiting 'wc-').
     * Since version 1.9.0, the options for that setting are now dynamically retrieved via
     * the 'wc_get_order_statuses' function. That function returns core statuses prefixed with 'wc-'.
     * So now we need to update the current value of that setting to include the
     * prefix (if the current value is an unprefixed core status).
     *
     * @return void
     */
    private function v_190_prefix_order_status() {
        $update_order_status_to = get_option( 'woo_mp_update_order_status_to' );

        if ( in_array( 'wc-' . $update_order_status_to, array_keys( wc_get_order_statuses() ), true ) ) {
            update_option( 'woo_mp_update_order_status_to', 'wc-' . $update_order_status_to );
        }
    }

}
