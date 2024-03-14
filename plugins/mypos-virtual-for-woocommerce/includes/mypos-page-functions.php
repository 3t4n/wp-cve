<?php
defined( 'ABSPATH' ) || exit;

/**
 * Get endpoint URL.
 *
 * Gets the URL for an endpoint, which varies depending on permalink settings.
 *
 * @param string $endpoint Endpoint slug.
 * @param string $value Query param value.
 * @param string $permalink Permalink.
 *
 * @return string
 */
function mypos_get_endpoint_url(string $endpoint, string $value = '', string $permalink = '')
{
    if ( ! $permalink ) {
        $permalink = get_permalink();
    }

    if ( get_option( 'permalink_structure' ) ) {
        if (str_contains($permalink, '?')) {
            $query_string = '?' . wp_parse_url( $permalink, PHP_URL_QUERY );
            $permalink    = current( explode( '?', $permalink ) );
        } else {
            $query_string = '';
        }
        $url = trailingslashit($permalink);

        if ( $value ) {
            $url .= trailingslashit( $endpoint ) . user_trailingslashit( $value );
        } else {
            $url .= user_trailingslashit( $endpoint );
        }

        $url .= $query_string;
    } else {
        $url = add_query_arg($endpoint, $value, $permalink );
    }

    return apply_filters( 'mypos_get_endpoint_url', $url, $endpoint, $value, $permalink );
}
