<?php

class EIC_Assets {

    private $url;

    public function __construct()
    {
        $this->url = EasyImageCollage::get()->coreUrl;

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin' ) );
        add_action( 'wp_head', array( $this, 'pinterest_css' ) );
        add_action( 'wp_head', array( $this, 'captions_css' ) );
        add_action( 'wp_head', array( $this, 'custom_css' ), 20 );
        add_action( 'enqueue_block_editor_assets', array( $this, 'block_assets' ) );

        add_filter( 'mce_external_plugins', array( $this, 'tinymce_plugin' ) );
    }

    public function block_assets() {
        wp_enqueue_style( 'eic-blocks', EasyImageCollage::get()->coreUrl . '/dist/blocks.css', array(), EIC_VERSION, 'all' );
		wp_enqueue_script( 'eic-blocks', EasyImageCollage::get()->coreUrl . '/dist/blocks.js', array( 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-data', 'wp-edit-post' ), EIC_VERSION );
	}

    public function enqueue_public()
    {
        wp_enqueue_style( 'eic_public', $this->url . '/css/public.css', array(), EIC_VERSION, 'screen' );
        wp_enqueue_script( 'eic_public', $this->url . '/js/public.js', array( 'jquery' ), EIC_VERSION, true );

        if( EasyImageCollage::option( 'pinterest_enable', '0' ) == '1' ) {
            wp_enqueue_script( 'eic_pinterest', '//assets.pinterest.com/js/pinit.js', array(), EIC_VERSION, true );
        }

        // Pass on data
        $data = array(
            'responsive_breakpoint' => EasyImageCollage::option( 'responsive_breakpoint', '300' ),
            'responsive_layout' => EasyImageCollage::option( 'responsive_layout', '' ),
        );
        wp_localize_script( 'eic_public', 'eic_public', $data );
    }

    public function enqueue_admin()
    {
        $screen = get_current_screen();

        if( $screen->base == 'post' ) {
            // Vendor assets
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_style( 'font-awesome', $this->url . '/vendor/font-awesome/css/font-awesome.min.css', array(), EIC_VERSION, 'screen' );
            wp_enqueue_style( 'simple-slider', $this->url . '/vendor/loopj-jquery-simple-slider/css/simple-slider.css', array(), EIC_VERSION, 'screen' );
            wp_enqueue_script( 'simple-slider', $this->url . '/vendor/loopj-jquery-simple-slider/js/simple-slider.min.js', array( 'jquery' ), EIC_VERSION, true );
            wp_enqueue_script( 'featherlight', $this->url . '/vendor/featherlight/featherlight.min.js', array( 'jquery' ), EIC_VERSION, true );

            // Plugin assets
            wp_enqueue_style( 'eic_admin', $this->url . '/css/admin.css', array(), EIC_VERSION, 'screen' );
            wp_enqueue_script( 'eic_admin', $this->url . '/js/admin.js', array( 'jquery', 'simple-slider', 'featherlight', 'wp-color-picker' ), EIC_VERSION, true );

            // Pass on data
            $data = array(
                'ajaxurl' => EasyImageCollage::get()->helper('ajax')->url(),
                'nonce' => wp_create_nonce( 'eic_image_collage' ),
                'shortcode_image' => $this->url . '/img/eic_shortcode.png',
                'default_link_new_tab' => EasyImageCollage::option( 'custom_link_new_tab', '0' ) == '1' ? true : false,
                'default_link_nofollow' => EasyImageCollage::option( 'custom_link_nofollow', '0' ) == '1' ? true : false,
                'text_link_new_tab' => __( 'Open in New Tab', 'easy-image-collage' ),
                'text_link_nofollow' => __( 'Use Nofollow', 'easy-image-collage' ),
                'captions_autofill' => EasyImageCollage::option( 'captions_autofill', 'disabled' ),
            );
            wp_localize_script( 'eic_admin', 'eic_admin', $data );
        }
    }

    public function pinterest_css()
    {
        if( EasyImageCollage::option( 'pinterest_enable', '0' ) == '1' ) {
            echo '<style type="text/css">';
            echo '.eic-image [data-pin-log="button_pinit"] {';
            echo 'display: none;';
            echo 'position: absolute;';

            switch( EasyImageCollage::option( 'pinterest_location', 'top_left' ) ) {
                case 'top_left':
                    echo 'top: 5px;';
                    echo 'left: 5px;';
                    break;
                case 'top_right':
                    echo 'top: 5px;';
                    echo 'right: 5px;';
                    break;
                case 'bottom_left':
                    echo 'bottom: 5px;';
                    echo 'left: 5px;';
                    break;
                case 'bottom_right':
                    echo 'bottom: 5px;';
                    echo 'right: 5px;';
                    break;
            }

            echo '}';
            echo '</style>';
        }
    }

    public function captions_css()
    {
        echo '<style type="text/css">';
        echo '.eic-image .eic-image-caption {';

        switch( EasyImageCollage::option( 'captions_location', 'bottom' ) ) {
            case 'bottom':
                echo 'bottom: 0;';
                echo 'left: 0;';
                echo 'right: 0;';
                break;
            case 'top':
                echo 'top: 0;';
                echo 'left: 0;';
                echo 'right: 0;';
                break;
        }

        echo 'text-align: ' . EasyImageCollage::option( 'captions_text_alignment', 'left' ) . ';';
        echo 'font-size: ' . intval( EasyImageCollage::option( 'captions_font_size', 12 ) )  . 'px;';
        echo 'color: ' . EasyImageCollage::option( 'captions_text_color', 'rgba(255,255,255,1)' )  . ';';
        echo 'background-color: ' . EasyImageCollage::option( 'captions_background_color', 'rgba(0,0,0,0.7)' )  . ';';

        echo '}';

        // Hide on mobile.
        if ( EasyImageCollage::option( 'responsive_hide_captions', '' ) == '1' ) {
            echo ' .eic-container-mobile .eic-image .eic-image-caption { display: none; }';
        }

        echo '</style>';
    }

    public function custom_css()
    {
        if( EasyImageCollage::option( 'custom_code_public_css', '' ) !== '' ) {
            echo '<style type="text/css">';
            echo EasyImageCollage::option( 'custom_code_public_css', '' );
            echo '</style>';
        }
    }

    public function tinymce_plugin( $plugin_array )
    {
        $plugin_array['easyimagecollage'] = $this->url . '/js/tinymce_shortcode_preview.js';
        return $plugin_array;
    }
}