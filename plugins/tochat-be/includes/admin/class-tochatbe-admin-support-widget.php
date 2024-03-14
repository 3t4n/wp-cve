<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Admin support widget class.
 * 
 * @since 1.2.3
 */
class TOCHATBE_Admin_Support_Widget {

    /**
     * Class constructor.
     * 
     * @since 1.2.3
     */
    public function __construct() {
        add_action( 'admin_footer', array( $this, 'admin_support_widget' ) );
    }

    /**
     * Admin support widget.
     * 
     * @since 1.2.3
     */
    public function admin_support_widget() {
        $current_screen = get_current_screen();

        if ( ! $current_screen || ! $current_screen->parent_base || 'to-chat-be-whatsapp' !== $current_screen->parent_base ) {
            return;
        }

        ?>
        <script defer src="https://widget.tochat.be/bundle.js?key=63c429f3-d5c3-4603-b7bb-513349758c47"></script>
        <?php
    }
}

return new TOCHATBE_Admin_Support_Widget();