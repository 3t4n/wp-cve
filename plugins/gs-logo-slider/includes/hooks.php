<?php
namespace GSLOGO;

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class Hooks {

    public function __construct() {
        add_action( 'admin_init', [ $this, 'maybe_redirect' ] );
        add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );
        add_action( 'plugins_loaded', [ $this, 'plugin_loaded' ] );
        add_action( 'in_admin_header', [ $this, 'disable_admin_notices' ], 1000 );
        add_filter( 'jetpack_content_options_featured_image_exclude_cpt', [$this, 'jetpack__featured_image_exclude_cpt']);
        add_filter( 'use_block_editor_for_post_type', [$this, 'disable_gutenberg'], 10, 2 );
    }

    function disable_gutenberg( $current_status, $post_type ) {
        if ( $post_type === 'gs-logo-slider' ) return false;
        return $current_status;
    }

    function jetpack__featured_image_exclude_cpt( $excluded_post_types ) {
        return array_merge( $excluded_post_types, ['gs-logo-slider'] );
    }

    /**
     * Redirect to options page
     *
     * @since v1.0.0
     */
    public function maybe_redirect() {

        if ( get_option('gslogo_activation_redirect', false) ) {

            delete_option('gslogo_activation_redirect');

            if ( !isset($_GET['activate-multi']) ) {
                wp_redirect("edit.php?post_type=gs-logo-slider&page=gs-logo-plugins-help");
            }
        }
    }

    public function plugin_row_meta( $meta_fields, $file ) {
  
        if ( strpos($file, basename(__FILE__)) === false ) return $meta_fields;
        
        echo "<style>.gslogo-rate-stars { display: inline-block; color: #ffb900; position: relative; top: 3px; }.gslogo-rate-stars svg{ fill:#ffb900; } .gslogo-rate-stars svg:hover{ fill:#ffb900 } .gslogo-rate-stars svg:hover ~ svg{ fill:none; } </style>";

        $plugin_rate   = "https://wordpress.org/support/plugin/gs-logo-slider/reviews/?rate=5#new-post";
        $plugin_filter = "https://wordpress.org/support/plugin/gs-logo-slider/reviews/?filter=5";
        $svg_xmlns     = "https://www.w3.org/2000/svg";
        $svg_icon      = '';

        for ( $i = 0; $i < 5; $i++ ) {
            $svg_icon .= "<svg xmlns='" . esc_url( $svg_xmlns ) . "' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>";
        }

        // Set icon for thumbsup.
        $meta_fields[] = '<a href="' . esc_url( $plugin_filter ) . '" target="_blank"><span class="dashicons dashicons-thumbs-up"></span>' . __( 'Vote!', 'gscs' ) . '</a>';

        // Set icon for 5-star reviews. v1.1.22
        $meta_fields[] = "<a href='" . esc_url( $plugin_rate ) . "' target='_blank' title='" . esc_html__( 'Rate', 'gscs' ) . "'><i class='gslogo-rate-stars'>" . $svg_icon . "</i></a>";

        return $meta_fields;

    }

    public function plugin_loaded() {
        gs_update_plugin_version();
        plugin()->builder->maybe_create_shortcodes_table();
    }

    public function disable_admin_notices() {

        global $parent_file;
    
        if ( $parent_file != 'edit.php?post_type=gs-logo-slider' ) return;
        
        remove_all_actions( 'network_admin_notices' );
        remove_all_actions( 'user_admin_notices' );
        remove_all_actions( 'admin_notices' );
        remove_all_actions( 'all_admin_notices' );
    
    }

}
