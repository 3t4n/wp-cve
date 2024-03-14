<?php

/**
 * Ask for a review.
 *
 * @since 1.35.0
 */
class Advanced_Form_Integration_Review {

    /**
     * Primary class constructor.
     *
     * @since 1.35.0
     */
    public function __construct() {

        add_action( 'admin_notices', array( $this, 'review_request' ) );
        add_action( 'wp_ajax_adfoin_review_dismiss', array( $this, 'review_dismiss' ) );
        add_filter( 'admin_footer_text', array( $this, 'admin_footer' ), 1, 2 );
    }

    /**
     * Add admin notices.
     *
     * @since 1.35.0
     */
    public function review_request() {

        if ( ! is_super_admin() ) {
            return;
        }

        global $wpdb;

        // Verify that we can do a check for reviews.
        $review = get_option( 'adfoin_review' );
        $time   = time();
        $load   = false;

        if ( ! $review ) {
            $review = array(
                'time'      => $time,
                'dismissed' => false,
            );
            update_option( 'adfoin_review', $review );
        } else {
            // Check if it has been dismissed or not.
            if ( ( isset( $review['dismissed'] ) && ! $review['dismissed'] ) && ( isset( $review['time'] ) && ( ( $review['time'] + DAY_IN_SECONDS ) <= $time ) ) ) {
                $load = true;
            }
        }

        // If we cannot load, return early.
        if ( ! $load ) {
            return;
        }

        $oldest_active = $wpdb->get_row( "SELECT *, DATE_FORMAT(time, '%m/%d/%Y') FROM {$wpdb->prefix}adfoin_integration WHERE status = 1 AND DATE(time) < CURDATE() - INTERVAL 30 DAY ORDER BY ID DESC", ARRAY_A );

        if ( $oldest_active ) {
            $this->review();
        }
    }

    /**
     * Show review request.
     *
     * @since 1.35.0
     */
    public function review() {

        // Review html
        ?>
        <div class="notice notice-info is-dismissible adfoin-review-notice">
            <p><?php esc_html_e( 'Hey, I noticed you are using Advanced Form Integration plugin for a few days - that’s awesome! Could you please do us a BIG favor and give it a 5-star rating on WordPress to boost our motivation?', 'advanced-form-integration' ); ?></p>
            <p><strong><?php echo wp_kses( __( '~ Nasir Ahmed<br>Developer of Advanced Form Integration', 'adfoin-lite' ), array( 'br' => array() ) ); ?></strong></p>
            <p>
                <a href="https://wordpress.org/support/plugin/advanced-form-integration/reviews/?filter=5#new-post" class="adfoin-dismiss-review-notice adfoin-review-out" target="_blank" rel="noopener"><?php esc_html_e( 'Ok, you deserve it', 'advanced-form-integration' ); ?></a><br>
                <a href="#" class="adfoin-dismiss-review-notice" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Nope, maybe later', 'advanced-form-integration' ); ?></a><br>
                <a href="#" class="adfoin-dismiss-review-notice" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'I already did', 'advanced-form-integration' ); ?></a>
            </p>
        </div>
        <script type="text/javascript">
            jQuery( function ( $ ) {
                $( document ).on( 'click', '.adfoin-dismiss-review-notice, .adfoin-review-notice button', function ( event ) {
                    if ( ! $( this ).hasClass( 'adfoin-review-out' ) ) {
                        event.preventDefault();
                    }
                    $.post( ajaxurl, {
                        action: 'adfoin_review_dismiss'
                    } );
                    $( '.adfoin-review-notice' ).remove();
                } );
            } );
        </script>
        <?php
    }

    /**
     * Dismiss the review admin notice
     *
     * @since 1.35.0
     */
    public function review_dismiss() {

        $review              = get_option( 'adfoin_review', array() );
        $review['time']      = time();
        $review['dismissed'] = true;

        update_option( 'adfoin_review', $review );
        die;
    }

    /**
     * Display footer text
     *
     * @since 1.35.0
     *
     * @param string $text
     *
     * @return string
     */
    public function admin_footer( $text ) {

        global $current_screen;

        if ( ! empty( $current_screen->id ) && strpos( $current_screen->id, 'advanced-form-integration' ) !== false ) {
            $url  = 'https://wordpress.org/support/plugin/advanced-form-integration/reviews/?filter=5#new-post';
            $text = sprintf(
                wp_kses(
                /* translators: $1$s - WPForms plugin name; $2$s - WP.org review link; $3$s - WP.org review link. */
                    __( 'Thank you for using %1$s. Please rate us <a href="%2$s" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%3$s" target="_blank" rel="noopener">WordPress.org</a> to boost our motivation.', 'advanced-form-integration' ),
                    array(
                        'a' => array(
                            'href'   => array(),
                            'target' => array(),
                            'rel'    => array(),
                        ),
                    )
                ),
                '<strong>Advanced Form Integration</strong>',
                $url,
                $url
            );
        }

        return $text;
    }

}

new Advanced_Form_Integration_Review;
