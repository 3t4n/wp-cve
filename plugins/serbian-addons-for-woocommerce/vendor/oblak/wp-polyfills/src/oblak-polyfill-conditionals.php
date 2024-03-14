<?php
/**
 * Various conditional functions
 *
 * ! This file intentionally left without namespace
 *
 * @package WP Polyfills
 * @subpackage Conditional functions
 */

if ( ! function_exists( 'wp_is_request' ) ) :
    /**
     * What kind of request is this?
     *
     * @param  string $type Type of request.
     */
    function wp_is_request( string $type ): bool {
        return match ( $type ) {
            'admin'    => is_admin(),
            'ajax'     => defined( 'DOING_AJAX' ) && DOING_AJAX,
            'cron'     => defined( 'DOING_CRON' ) && DOING_CRON,
            'rest'     => wp_is_rest_request(),
            'frontend' => ( ! is_admin() || defined( 'DOING_AJAX' ) ) &&
                ! defined( 'DOING_CRON' ) &&
                ! wp_is_rest_request(),
        };
    }
endif;

if ( ! function_exists( 'wp_is_rest_request' ) ) :
    /**
     * Determines whether the current request is a WordPress REST API request.
     */
    function wp_is_rest_request(): bool {
        return defined( 'REST_REQUEST' ) && REST_REQUEST;
    }
endif;
