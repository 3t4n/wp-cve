<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Houzez Property Feed Redirect Functions
 */
class Houzez_Property_Feed_Redirect {

	public function __construct() {

        // Redirects
        add_action( 'init', array( $this, 'check_jupix_redirect' ) );
        add_action( 'init', array( $this, 'check_import_redirect' ) );
	}

    public function check_jupix_redirect()
    {
        if ( isset($_GET['profileID']) )
        {
            $args = array(
                'post_type' => 'property',
                'meta_compare_key' => 'LIKE',
                'meta_key'     => '_imported_ref_',
                'meta_value'   => sanitize_text_field($_GET['profileID'])
            );
            $my_query = new WP_Query( $args );

            if ( $my_query->have_posts() )
            {
                while ( $my_query->have_posts() )
                {
                    $my_query->the_post();

                    header("HTTP/1.1 301 Moved Permanently");
                    header("Location: " . get_permalink(get_the_ID()));

                    exit();
                }

            }
            wp_reset_postdata();
        }
    }

    public function check_import_redirect()
    {
        if ( isset($_GET['imported_id']) )
        {
            $args = array(
                'post_type' => 'property',
                'meta_compare_key' => 'LIKE',
                'meta_key'     => '_imported_ref_',
                'meta_value'   => sanitize_text_field($_GET['imported_id'])
            );
            $my_query = new WP_Query( $args );

            if ( $my_query->have_posts() )
            {
                while ( $my_query->have_posts() )
                {
                    $my_query->the_post();

                    header("HTTP/1.1 301 Moved Permanently");
                    header("Location: " . get_permalink(get_the_ID()));

                    exit();
                }

            }
            wp_reset_postdata();
        }
    }

}

new Houzez_Property_Feed_Redirect();