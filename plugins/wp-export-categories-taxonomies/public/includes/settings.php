<?php
/**
 * Get Settings
 *
 * Retrieves all plugin settings
 *
 * @since 1.0
 * @return array PREFIX settings
 */
function ect_get_settings() {

        $settings = get_option( 'ect_settings' );

        return apply_filters( 'ect_get_settings', $settings );
}