<?php

namespace Woo_MP\Controllers;

defined( 'ABSPATH' ) || die;

/**
 * Controller for the rating request.
 */
class Rating_Request_Controller {

    /**
     * Append the rating request HTML to the Payments meta box title.
     *
     * @param  string $title The title.
     * @return string        The modified title.
     */
    public function append_rating_request( $title ) {
        if ( ! get_site_option( 'woo_mp_rated' ) ) {
            $title .= sprintf(
                '<span class="woo-mp-rating-request" style="display: none;"><a href="%s" target="_blank">Rate this Plugin</a></span>',
                'https://wordpress.org/support/plugin/woo-mp/reviews/?rate=5#new-topic-0'
            );
        }

        return $title;
    }

    /**
     * Set an option indicating that the plugin has been rated.
     *
     * @return void
     */
    public function woo_mp_rated() {
        if ( ! current_user_can( 'edit_shop_orders' ) ) {
            wp_die( 'Sorry, you are not allowed to complete this operation.', 403 );
        }

        update_site_option( 'woo_mp_rated', true );

        die;
    }

}
