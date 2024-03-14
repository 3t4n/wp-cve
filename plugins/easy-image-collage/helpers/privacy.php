<?php

class EIC_Privacy
{
    public function __construct()
    {
        add_action('admin_init', array($this, 'privacy_policy') );
    }

    public function privacy_policy()
    {
        if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
            return;
        }
     
        $content = __( 'Easy Image Collage does not collect any data. When enabling the Pinterest Pin-It button their external code will be embedded. You will be agreeing to their Terms of Use and Privacy Policy.',
            'easy-image-collage' );
     
        wp_add_privacy_policy_content(
            'Easy Image Collage',
            wp_kses_post( wpautop( $content, false ) )
        );
    }
}