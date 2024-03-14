<?php

if( !defined( 'ABSPATH') ) exit();

// Construct backend
// ======================================================

class Hybrid_Gallery_Admin
{
    function __construct()
    {
        add_action('admin_init', array($this, 'init_plugin'));
        add_action('media_buttons', array($this, 'media_button') , 15);

        $this->include_functions();
    }


    public function init_plugin()
    {
        add_image_size('hybrid-gallery-portrait', 1280, 9999);
        add_image_size('hybrid-gallery-landscape', 9999, 1280);        

        add_action('admin_enqueue_scripts', array($this, 'admin_script'));

        add_action('admin_footer', array($this, 'panel_code'));
        add_action('admin_print_footer_scripts', array($this, 'editor_template')); 

        add_filter('mce_css', array($this, 'mce_css'));
    }


    public function mce_css( $mce_css ) {
	    if ( ! empty( $mce_css ) )
		    $mce_css .= ',';

	    $mce_css .= plugin_dir_url(__FILE__) . 'assets/css/hybrid.editor.css?ver=' . HYBRID_GALLERY_VER;
            $mce_css .= ',';
	    $mce_css .= HYBRID_GALLERY_DIR_URL . 'libs/assets/css/libs.global.pack.min.css?ver=' . HYBRID_GALLERY_VER;

	    return $mce_css;
    }


    public function admin_script()
    {
        wp_enqueue_style('hybrig-gallery-libs-global-pack');
        wp_enqueue_style('hybrid-gallery-admin-panel', plugin_dir_url(__FILE__) . 'assets/css/hybrid.panel.css', array(), HYBRID_GALLERY_VER, 'all');
        wp_enqueue_style('hybrid-gallery-admin-trial', plugin_dir_url(__FILE__) . 'assets/css/hybrid.trial.css', array(), HYBRID_GALLERY_VER, 'all');
        
        wp_enqueue_media();
        wp_enqueue_style( 'wp-color-picker' ); 
        
        wp_enqueue_script('hybrid-gallery-sc-panel-editor', plugin_dir_url(__FILE__) . 'assets/js/hybrid.panel.js', array(
            'shortcode',
            'wp-util',
            'wp-color-picker',
            'jquery',
        ), HYBRID_GALLERY_VER, true);

        $localize = array(
            'ajaxUrl' => admin_url('admin-ajax.php') ,
            'nonce' => wp_create_nonce('hybrig_gallery_ajax_nonce'),
            'copyText' => esc_js(__('The gallery code was successfully copied!', 'hybrid-gallery'))
        );
        wp_localize_script('hybrid-gallery-sc-panel-editor', 'hybrid_gallery_ajax_request', $localize);
    }


    // Add Button to Media Button
    // ======================================================

    public function media_button()
    {
        echo '<button type="button" class="hybgl-media-button button"><span></span>Hybrid Gallery</button>';
    }


    // Include panel code in admin page
    // ======================================================

    public function panel_code()
    {
        $output = '';
        $output .= '<div class="hybgl-shortcode-prop">';
        $output .= '</div>';
        $output .= '<div class="hybgl-popup-mask"></div>';
        $output .= '<div class="hybgl-popup-loader">';
            $output .= '<div class="hybgl-prl-bounce-loader">';
                $output .= '<div class="hybgl-prl-spinner">';
                    $output .= '<div class="hybgl-prl-double-bounce1"></div>';
                    $output .= '<div class="hybgl-prl-double-bounce2"></div>';
                $output .= '</div>';
            $output .= '</div>';
        $output .= '</div>';
        $output .= '<div class="hybgl-popup">';
            $output .= '<div class="hybgl-popup-inner">';
                $output .= '<div class="hybgl-popup-editor" style="display:none;"></div>';
                $output .= '<div class="hybgl-popup-top">';
                    $output .= '<div class="hybgl-popup-top-left">';
                        $output .= '<h3 class="hybgl-popup-title">Hybrid Gallery</h3>';
                        $output .= '<div class="hybgl-tmpl-title">';
                            $output .= '<span class="hybgl-tmpl-title-button">( <i class="fa fa fa-database"></i> <span class="hybgl-tmpl-title-info">' . esc_html__('Templates', 'hybrid-gallery') . '</span> )</span>';
                            $output .= '<span class="hybgl-tmpl-title-loader"><i class="fa fa-spinner fa-spin"></i></span>';
                            $output .= '<div class="hybgl-sc-tmpl-box"></div>';
                            $output .= '</div>';
                         $output .= '</div>';
                     $output .= '<span class="hybgl-popup-close"></span>';
                $output .= '</div>';
                $output .= '<div class="hybgl-popup-pro">';
                    $output .= 'This is a trial version. Upgrage to <a href="https://hybrid-gallery.com/?utm_source=panel&utm_medium=upgrade&utm_campaign=hybgl" target="_blank">PRO</a>';
                $output .= '</div>';
                $output .= '<div class="hybgl-popup-content"></div>';
            $output .= '</div>';
        $output .= '</div>';

        echo $output;
    }


    // Create custom template (Slider)
    // ======================================================

    function editor_template()
    {
        $output = '';
        $output .= '<script type="text/html" id="tmpl-editor-hybrid-gallery-template-slider">';
            $output .= '<div class="hybgl-media-visual hybgl-clearfix<# if ( data.ct_align == "left" ) { #> hybgl-media-visual-left<# } else if ( data.ct_align == "right" ) { #> hybgl-media-visual-right<# } else if ( data.ct_align == "center" ) { #> hybgl-media-visual-center<# } #>" style="width:{{data.ct_w_vl}}<# if ( data.ct_w_un == "px" ) { #>px<# } else { #>%<# } #>;">';
                $output .= '<div class="hybgl-media-visual-buttons hybgl-clearfix">';
                    $output .= '<span class="hybgl-media-visual-button-edit"><i class="fa fa-pencil"></i></span>';
                    $output .= '<span class="hybgl-media-visual-button-copy"><i class="fa fa-files-o"></i></span>';
                    $output .= '<span class="hybgl-media-visual-button-remove"><i class="fa fa-times"></i></span>';
                $output .= '</div>';
                $output .= '<div class="hybgl-media-visual-content hybgl-clearfix">';
                    $output .= '<div class="hybgl-media-visual-content-inner <# if ( data.layout == 2 ) { #>hybgl-media-visual-layout-slider2<# } else if ( data.layout == 3 ) { #>hybgl-media-visual-layout-slider3<# } else { #> hybgl-media-visual-layout-slider<# } #>">';
                    $output .= '</div>';
                $output .= '</div>';
                $output .= '<div class="hybgl-media-visual-images hybgl-clearfix">';
                    $output .= '<i class="fa fa-spinner fa-spin"></i>';
                $output .= '</div>';
            $output .= '</div>';
        $output .= '</script>';

        $output .= '<script type="text/html" id="tmpl-editor-hybrid-gallery-template-carousel">';
            $output .= '<div class="hybgl-media-visual hybgl-clearfix<# if ( data.ct_align == "left" ) { #> hybgl-media-visual-left<# } else if ( data.ct_align == "right" ) { #> hybgl-media-visual-right<# } else if ( data.ct_align == "center" ) { #> hybgl-media-visual-center<# } #>" style="width:{{data.ct_w_vl}}<# if ( data.ct_w_un == "px" ) { #>px<# } else { #>%<# } #>;">';
                $output .= '<div class="hybgl-media-visual-buttons hybgl-clearfix">';
                    $output .= '<span class="hybgl-media-visual-button-edit"><i class="fa fa-pencil"></i></span>';
                    $output .= '<span class="hybgl-media-visual-button-copy"><i class="fa fa-files-o"></i></span>';
                    $output .= '<span class="hybgl-media-visual-button-remove"><i class="fa fa-times"></i></span>';
                $output .= '</div>';
                $output .= '<div class="hybgl-media-visual-content hybgl-clearfix">';
                    $output .= '<div class="hybgl-media-visual-content-inner hybgl-media-visual-layout-carousel"></div>';
                $output .= '</div>';
                $output .= '<div class="hybgl-media-visual-images hybgl-clearfix">';
                    $output .= '<i class="fa fa-spinner fa-spin"></i>';
                $output .= '</div>';
            $output .= '</div>';
        $output .= '</script>';

        $output .= '<script type="text/html" id="tmpl-editor-hybrid-gallery-template-grid">';
            $output .= '<div class="hybgl-media-visual hybgl-clearfix<# if ( data.ct_align == "left" ) { #> hybgl-media-visual-left<# } else if ( data.ct_align == "right" ) { #> hybgl-media-visual-right<# } else if ( data.ct_align == "center" ) { #> hybgl-media-visual-center<# } #>" style="width:{{data.ct_w_vl}}<# if ( data.ct_w_un == "px" ) { #>px<# } else { #>%<# } #>;">';
                $output .= '<div class="hybgl-media-visual-buttons hybgl-clearfix">';
                    $output .= '<span class="hybgl-media-visual-button-edit"><i class="fa fa-pencil"></i></span>';
                    $output .= '<span class="hybgl-media-visual-button-copy"><i class="fa fa-files-o"></i></span>';
                    $output .= '<span class="hybgl-media-visual-button-remove"><i class="fa fa-times"></i></span>';
                $output .= '</div>';
                $output .= '<div class="hybgl-media-visual-content hybgl-clearfix">';
                    $output .= '<div class="hybgl-media-visual-content-inner <# if ( data.layout == "masonry" ) { #>hybgl-media-visual-layout-masonry<# } else if ( data.layout == "metro" ) { #>hybgl-media-visual-layout-metro<# } else if ( data.layout == "justified" ) { #>hybgl-media-visual-layout-justified<# } else { #> hybgl-media-visual-layout-grid<# } #>">';
                    $output .= '</div>';
                $output .= '</div>';
                $output .= '<div class="hybgl-media-visual-images hybgl-clearfix">';
                    $output .= '<i class="fa fa-spinner fa-spin"></i>';
                $output .= '</div>';
            $output .= '</div>';
        $output .= '</script>';

        echo $output;
   }


    // Inlcude AJAX Requests
    // ======================================================

    public function include_functions() {
        // ajax resuests
        require_once(plugin_dir_path( __FILE__ ) . 'functions/ajax.php');

        // panel options
        require_once(plugin_dir_path( __FILE__ ) . 'functions/panel.php');

        // panel framework
        require_once(plugin_dir_path( __FILE__ ) . 'functions/framework.php');

        // attachment csutom fields
        require_once(plugin_dir_path( __FILE__ ) . 'functions/attach.php');
    }
}

new Hybrid_Gallery_Admin;