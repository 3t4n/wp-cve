<?php

if( !defined( 'ABSPATH') ) exit();

class Hybrid_Gallery_Panel_AJAX
{
    function __construct() {
        add_action('admin_init', array($this, 'init'));
        add_action('admin_init', array($this, 'options'));
    }

    public function init() {
        add_action('wp_ajax_hybrid_gallery_sc_attachments', array($this, 'attach'));
        add_action('wp_ajax_hybrid_gallery_sc_add', array($this, 'add'));
        add_action('wp_ajax_hybrid_gallery_sc_tmpl_set', array($this, 'tmpl_set'));
        add_action('wp_ajax_hybrid_gallery_sc_tmpl_save', array($this, 'save'));
        add_action('wp_ajax_hybrid_gallery_sc_tmpl_del', array($this, 'delete'));
        add_action('wp_ajax_hybrid_gallery_sc_tmpl_list', array($this, 'templates'));
        add_action('wp_ajax_hybrid_gallery_sc_layouts', array($this, 'layouts'));
    }


    // Check Security
    // ======================================================
    
    private function security() {
        // Check Nonce Security
        check_ajax_referer( 'hybrig_gallery_ajax_nonce', 'nonce' );    
    }


    // Register options
    // ======================================================

    public function options()
    {
        register_setting( 'hybrid-galery-options', 'hybrid_gallery_shortcode_tmpl' );
    }


    // Get ajax attachments (Shortcode)
    // ======================================================

    public function attach() 
    {
        $this->security();

        $ids = esc_attr($_POST['ids']); 
        $id_array = explode(',', $ids);

        if (is_array($id_array)) {
            foreach ($id_array as $id) {                
                $thumbnail = wp_get_attachment_image_src($id, 'thumbnail', false);
                if ($thumbnail[0]) {
                    echo '<div class="hybgl-media-visual-image-thumb" style="background-image:url(' . $thumbnail[0] . ');"></div>';
                }
            }
        }

        if (!wp_get_attachment_image_src($id_array[0], 'thumbnail', false)) {
            esc_html_e('No Images', 'hybrid-gallery');
        }
 
        wp_die();
    }
    
    
    // Get ajax add (Shortcode)
    // ======================================================

    public function add()
    {
        $this->security();
        
        $atts = array();
        $atts['layout'] = $_POST['layout'];

        if ( $_POST['panel'] == "slider" ) {
            echo Hybrid_Gallery_Panel::slider( $atts );
        } elseif ( $_POST['panel'] == "carousel" ) { 
            echo Hybrid_Gallery_Panel::carousel( $atts );
        } else {
            echo Hybrid_Gallery_Panel::grid( $atts );
        }

        wp_die();
    }


    // Set Template from List
    // ======================================================

    public function tmpl_set()
    {
        $this->security();

        if ( $_POST['panel'] == "slider" ) {
            echo Hybrid_Gallery_Panel::slider( $_POST['atts'] );
        } elseif ( $_POST['panel'] == "carousel" ) { 
            echo Hybrid_Gallery_Panel::carousel( $_POST['atts'] );
        } else {
            echo Hybrid_Gallery_Panel::grid( $_POST['atts'] );
        }

        wp_die();
    }


    // Save Shortcode Template
    // ======================================================

    public function save()
    {
        $this->security();

        $option_name = 'hybrid_gallery_shortcode_tmpl';

        // generate ID
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $id = '';
        for ($i = 0; $i < 12; $i++) {
            $id.= $characters[rand(0, $charactersLength - 1)];
        }

        $name = '__';
        if ( $_POST['name'] ) {
            $name = esc_attr($_POST['name']);    
        }

        $tmpl = array(
            'id' => $id,
            'name' => $name,
            'shortcode' => esc_attr($_POST['shortcode']),
            'layout' => esc_attr($_POST['layout']),
            'value' => esc_attr($_POST['value'])
        );

        if ( get_option( $option_name ) !== false ) {
            $tmpl_old = get_option($option_name); 

            // get template list
            $tmpl_new = array();

            // push new template
            array_push($tmpl_new, $tmpl);

            // push old template
            if ( count($tmpl_old) > 0 ) {
                if ( is_array($tmpl_old[0]) ) {
                    foreach ($tmpl_old as $x) {
                        array_push($tmpl_new, $x);
                    }
                } else {
                    array_push($tmpl_new, $tmpl_old);
                }
            }

            // update template list
            update_option( $option_name, $tmpl_new );
        } else {
            add_option( $option_name, $tmpl, null, 'no' );
        }

        wp_die();
    }

    // Delete Shortcode Template
    // ======================================================

    public function delete()
    {
        $this->security();

        $option_name = 'hybrid_gallery_shortcode_tmpl';

        $id = esc_attr($_POST['id']);

        $tmpl_new = array();
        $tmpl_list = get_option($option_name);

        foreach ($tmpl_list as $index => $tmpl) {
            if ($tmpl['id'] == $id) {
                unset($tmpl_list[$index]);
            } else {
                array_push($tmpl_new, $tmpl);
            }
        }

        // update template list
        update_option( $option_name, $tmpl_new );
    }

    // Get Shortcodes Template List
    // ======================================================

    public function templates()
    {
        $this->security();

        $option_name = 'hybrid_gallery_shortcode_tmpl';

        $tmpl_list = get_option($option_name);

        if ( is_array($tmpl_list[0]) ) {
            echo '<div class="hybgl-sc-tmpl-list hybgl-sc-tmpl-body"><ul>';
            foreach ( $tmpl_list as $item ) {
                if ( is_array($item) ) {
                    echo '<li>';
                        echo '<span class="hybgl-sc-tmpl-name">' . html_entity_decode(stripslashes($item['name'])) . '</span> <span class="hybgl-sc-tmpl-del" data-id="' . $item['id'] . '"><i class="fa fa-trash"></i></span>';
                        echo '<div class="hybgl-sc-tmpl-code" data-panel="' . $item['shortcode'] . '">' . html_entity_decode($item['value']) . '</div>';
                    echo '</li>';
                }
            }
            echo '</ul></div>';
        } else {
            echo '<div class="hybgl-sc-tmpl-body">' . esc_html__('No Templates', 'hybrid-gallery') . '</div>';
        }

        wp_die();
    }

    // Get Shortcode Layouts (PRE)
    // ======================================================
 
    public function layouts()
    {
        $this->security();
 
        $output = '';
        $output.= '<div class="hybgl-layouts">';
            $output.= '<ul class="hybgl-clearfix">';
                $output.= '<li id="hybrid-gallery-layout-grid">';
                    $output.= '<div class="hybgl-layout hybgl-layout-grid"></div>';
                    $output.= '<span>' . esc_html__("Grid", "hybrid-gallery") . '</span>';
                $output.= '</li>';
                $output.= '<li id="hybrid-gallery-layout-masonry">';
                    $output.= '<div class="hybgl-layout hybgl-layout-masonry"></div>';
                    $output.= '<span>' . esc_html__("Masonry", "hybrid-gallery") . '</span>';
                $output.= '</li>';
                $output.= '<li id="hybrid-gallery-layout-metro">';
                    $output.= '<div class="hybgl-layout hybgl-layout-metro"></div>';
                    $output.= '<span>' . esc_html__("Metro", "hybrid-gallery") . '</span>';
                $output.= '</li>';
                $output.= '<li id="hybrid-gallery-layout-justified">';
                    $output.= '<div class="hybgl-layout hybgl-layout-justified"></div>';
                    $output.= '<span>' . esc_html__("Justified", "hybrid-gallery") . '</span>';
                $output.= '</li>';
            $output.= '</ul>';
            $output.= '<ul class="hybgl-clearfix">';
                $output.= '<li id="hybrid-gallery-layout-slider">';
                    $output.= '<div class="hybgl-layout hybgl-layout-slider"></div>';
                    $output.= '<span>' . esc_html__("Slider", "hybrid-gallery") . '</span>';
                $output.= '</li>';
                $output.= '<li id="hybrid-gallery-layout-slider-horizontal">';
                    $output.= '<div class="hybgl-layout hybgl-layout-slider2"></div>';
                    $output.= '<span>' . esc_html__("Thumbnails: Horizontal", "hybrid-gallery") . '</span>';
                $output.= '</li>';
                $output.= '<li id="hybrid-gallery-layout-slider-vertical">';
                    $output.= '<div class="hybgl-layout hybgl-layout-slider3"></div>';
                    $output.= '<span>' . esc_html__("Thumbnails: Vertical", "hybrid-gallery") . '</span>';
                $output.= '</li>';
                $output.= '<li id="hybrid-gallery-layout-carousel">';
                    $output.= '<div class="hybgl-layout hybgl-layout-carousel"></div>';
                    $output.= '<span>' . esc_html__("Carousel", "hybrid-gallery") . '</span>';
                $output.= '</li>';
            $output.= '</ul>';
        $output.= '</div>';
        
        echo $output;
    ?>        

        <script>
        (function($) {
            $(document).ready(function(){
                $('#hybrid-gallery-layout-grid').find('.hybgl-layout').on( 'click', function() {
                    wp.mce.hybrid_gallery_grid.popup_window('sc-add');
                });

                $('#hybrid-gallery-layout-masonry').find('.hybgl-layout').on( 'click', function() {
                    wp.mce.hybrid_gallery_grid.popup_window('sc-add', 'masonry');
                });

                $('#hybrid-gallery-layout-metro').find('.hybgl-layout').on( 'click', function() {
                    wp.mce.hybrid_gallery_grid.popup_window('sc-add', 'metro');
                });

                $('#hybrid-gallery-layout-justified').find('.hybgl-layout').on( 'click', function() {
                    wp.mce.hybrid_gallery_grid.popup_window('sc-add', 'justified');
                });

                $('#hybrid-gallery-layout-slider').find('.hybgl-layout').on( 'click', function() {
                    wp.mce.hybrid_gallery_slider.popup_window('sc-add');
                });

                $('#hybrid-gallery-layout-slider-horizontal').find('.hybgl-layout').on( 'click', function() {
                    wp.mce.hybrid_gallery_slider.popup_window('sc-add', 2);
                });

                $('#hybrid-gallery-layout-slider-vertical').find('.hybgl-layout').on( 'click', function() {
                    wp.mce.hybrid_gallery_slider.popup_window('sc-add', '3');
                });

                $('#hybrid-gallery-layout-carousel').find('.hybgl-layout').on( 'click', function() {
                    wp.mce.hybrid_gallery_carousel.popup_window('sc-add');
                });
            });
        })(jQuery);
        </script>
    
    <?php
        wp_die();
    }
}

new Hybrid_Gallery_Panel_AJAX;