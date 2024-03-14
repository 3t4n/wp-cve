<?php
defined( 'ABSPATH' ) || exit;

class TOCHATBE_Share_Agent {

    public function __construct() {
        add_action( 'template_redirect', array( $this, 'share_agent' ) );
    }

    public function share_agent() {
        if ( ! isset( $_GET['tochatbe_agent_share'] ) || empty( $_GET['tochatbe_agent_share'] ) ) {
            return;
        }

        $agent_number = sanitize_text_field( wp_unslash( $_GET['tochatbe_agent_share'] ) );

        if ( ! is_numeric( $agent_number ) ) {
            wp_die( 'Invalid agent WhatsApp number!' );
        }

        $agent_number = absint( $agent_number );
        $agent        = get_posts( array(
            'post_type'      => 'tochatbe_agent',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            'meta_query'     => array(
                array(
                    'key'     => 'agent_number',
                    'value'   => $agent_number,
                    'compare' => '=',
                )
            ),
        ) );

        if ( ! $agent ) {
            wp_die( 'No agent found!' );
        }

        $agent = $agent[0];
        
        TOCHATBE_Log::log( array(
            'contacted_to' => $agent_number,
            'referral'     => 'Agent shared'
        ) );

        wp_redirect( 'https://wa.me/' . $agent_number );
        exit;
    }
}

return new TOCHATBE_Share_Agent();