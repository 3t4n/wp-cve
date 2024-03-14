<?php

class EIC_Post_Type
{

    public function __construct()
    {
        add_action('init', array($this, 'register_post_type'), 1);
    }

    public function register_post_type()
    {
        $args = apply_filters('eic_register_post_type',
            array(
                'public' => false,
                'has_archive' => false,
            )
        );

        register_post_type( EIC_POST_TYPE, $args );
    }
}