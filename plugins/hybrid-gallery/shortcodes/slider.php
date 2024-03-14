<?php

if( !defined( 'ABSPATH') ) exit();

class Hybrid_Gallery_Shortcode_Slider
{
    function __construct()
    {
        add_shortcode('hybrid_gallery_slider', array(
            $this,
            'main'
        ));
    }
    

    // ======================================================
    // Item Main
    // ======================================================
    
    public function item_main($atts = '', $id = '')
    {   
        $output = '';
        $item_data = '';
        $target = '';
        $attachment = get_post($id);

        // item parts
        $item_start = '';
        $item_data = '';
        $item_data_end = '';
        $item_end = '';


        // Image Sizes
        // ======================================================

        $img_src_full = wp_get_attachment_image_src($id, 'full', false);
        $img_src_def = wp_get_attachment_image_src($id, 'hybrid-gallery-portrait', false);
        $img_src_thumb = wp_get_attachment_image_src($id, 'thumbnail', false);

        $w = $img_src_full[1];
        $h = $img_src_full[2];


        // Fields
        // ======================================================

        $field_link = get_post_meta($id, '_hybrig_gallery_attach_link', true);
        $field_video = get_post_meta($id, '_hybrig_gallery_attach_video', true);


        // Field Type
        // ======================================================

        if ( get_post_meta($id, '_hybrig_gallery_attach_type', true) ) {
            $field_type = get_post_meta($id, '_hybrig_gallery_attach_type', true);
        } else {
            $field_type = 'image';
        }


        // Get Video ID From URL
        // ======================================================

        $url_id = '';
        $url_type = '';

        if(strpos($field_video, 'youtu') > 0) {
            $url_type = 'youtube';
            preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $field_video, $url_id);
        } elseif(strpos($field_video, 'vimeo') > 0) {
            $url_type = 'vimeo';
            preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=vimeo.com/)[^&\n]+#", $field_video, $url_id);
        }


        // Popup Link
        // ======================================================

        $lightbox_href_class = '';
        if ( $atts['lightbox'] == "true" ) {
            if ( $atts['lb_type'] == "mp" ) {
                if ( $field_type == "video" ) {
                    $lightbox_href_class = ' mfp-iframe';
                } else {
                    $lightbox_href_class = ' mfp-image'; 
                }
            } elseif ( $atts['lb_type'] == "fyb" ) {
                if ( $field_type == "video" ) {
                    $lightbox_href_class = ' fancybox-media';
                }
            } elseif ( $atts['lb_type'] == "cb" ) {
                if ( $field_type == "video" ) {
                    $lightbox_href_class = ' colorbox-media';
                } else {
                    $lightbox_href_class = ' colorbox-image';
                }
            }
        }

        if ( $field_type == "video" ) {
            if ( $atts['lightbox'] == "true" ) {
                if ( $atts['lb_type'] == "cb" ) {
                    if ( $url_type == 'youtube' ) {
                        $popup_url = '//www.youtube.com/embed/' . $url_id[0] . '?version=3';
                    } elseif ( $url_type == 'vimeo' ) {
                        $popup_url = '//player.vimeo.com/video/' . $url_id[0] . '';
                    }
                } elseif ( $atts['lb_type'] == "lc" ) {
                    if ( $url_type == 'youtube' ) {
                        $popup_url = '//www.youtube.com/embed/' . $url_id[0] . '?version=3';
                    } elseif ( $url_type == 'vimeo' ) {
                        $popup_url = '//player.vimeo.com/video/' . $url_id[0] . '';
                    }
                } elseif ( $atts['lb_type'] == "ilb" ) {
                    if ( $url_type == 'youtube' ) {
                        $popup_url = '//www.youtube.com/embed/' . $url_id[0] . '?version=3';
                    } elseif ( $url_type == 'vimeo' ) {
                        $popup_url = '//player.vimeo.com/video/' . $url_id[0] . '';
                    }
                } else {
                    $popup_url = esc_url($field_video);
                }
            } else {
                $popup_url = esc_url($field_video);
            }
        } else {
            $popup_url = $img_src_full[0];
        }
        
        
        // Link Data
        // ======================================================      

        if ( $atts['lightbox'] == "true" ) {
            if ($attachment->post_content) {
                $data_descr = '<p>' . $attachment->post_content . '</p>';
            } else {
                $data_descr = '';
            }

            if ( $atts['lb_type'] == "lg" ) {
                $link_data = ' href="' . $popup_url . '" title="' . $attachment->post_title . '" data-src="' . $img_src_def[0] . '" data-sub-html="<h4>' . $attachment->post_title . '</h4>' . $data_descr . '"';
            } elseif ( $atts['lb_type'] == "fyb" ) {
                if ( $field_type == "video" ) {
                    $link_data = 'data-fancybox-title="<h4>' . $attachment->post_title . '</h4>' . $data_descr . '" data-src="' . $img_src_thumb[0] . '" data-fancybox-type="iframe"';
                } else {
                    $link_data = 'data-fancybox-title="<h4>' . $attachment->post_title . '</h4>' . $data_descr . '" data-src="' . $img_src_thumb[0] . '" data-fancybox-type="image"';
                }
            } elseif ( $atts['lb_type'] == "ilb" ) {
                if ( $field_type == "video" ) {
                    $link_data = 'data-type="iframe" data-options="thumbnail:\'' . $img_src_def[0] . '\', width:1280, height:720" data-title="' . $attachment->post_title . '" data-caption="' . $attachment->post_content . '"';
                } else {
                    $link_data = 'data-options="thumbnail:\'' . $img_src_def[0] . '\'" data-title="' . $attachment->post_title . '" data-caption="' . $attachment->post_content . '"';
               }
            } elseif ( $atts['lb_type'] == "cb" ) {
                $link_data = 'data-descr="' . $attachment->post_content . '"';
            }
        } else {
            $link_data = 'data-descr="' . $attachment->post_content . '"';
        }
        
        
        // Link Prop
        // ======================================================        

        $link_prop = ' href="' . $popup_url . '" title="' . $attachment->post_title . '" ' . $link_data . '';  


        // Start Wrapper
        // ======================================================

        $item_start .= '<div class="hybgl-item hybgl-main-item" data-w="' . $w . '" data-h="' . $h . '">';
            if ( $atts['formats'] == "true" && $field_type == "video" ) {             
                if ( $url_type == 'vimeo' ) {                           
                    $item_start .= '<div class="hybgl-item-inner"><iframe src="https://player.vimeo.com/video/' . $url_id[0] .'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
                } elseif ( $url_type == 'youtube' ) {
                    $item_start .= '<div class="hybgl-item-inner"><iframe src="https://www.youtube.com/embed/' . $url_id[0] . '?version=3&wmode=opaque" frameborder="0" allowfullscreen></iframe></div>';
                }    
            } else {
                $item_start .= '<div class="hybgl-item-inner" style="background-image:url(' . $img_src_def[0] . ');"></div>';
            }
        $item_end .= '</div>';


        // if lightbox 
        if ( $atts['lightbox'] == "true" && ( $atts['formats'] != "true" || ( $atts['formats'] == "true" && $field_type != "video" ) ) ) {
            if ($atts['lb_pos'] == 2) {
                $item_data.= '<a class="hybgl-button-action-zoom hybgl-button-zoom-square' . $lightbox_href_class . '"' . $link_prop . '><i class="fa fa-search-plus"></i></a>';
            } else {
                $item_data.= '<a class="hybgl-button-action-zoom hybgl-button-zoom-full' . $lightbox_href_class . '"' . $link_prop . '></a>';
            }
        }


        // Meta | Style #1
        // ======================================================

        if ( $atts['meta_title'] == "true" || $atts['meta_descr'] == "true" ) {
            $item_data .= '<div class="hybgl-item-meta">';
        }
            if ( $atts['meta_title'] == "true" && $attachment->post_title ) {
                $item_data .= '<div class="hybgl-item-meta-title">' . $attachment->post_title . '</div>';
            }
            if ( $atts['meta_descr'] == "true" && $attachment->post_content ) {
                $item_data .= '<div class="hybgl-item-meta-descr">' . $attachment->post_content . '</div>';
            }
        if ( $atts['meta_title'] == "true" || $atts['meta_descr'] == "true" ) {
            $item_data .= '</div>';
        }

        $output = $item_start . $item_data . $item_end;

        // output all
        return $output;
    }


    // ======================================================
    // Item Child
    // ======================================================
    
    public function item_child($atts = '', $id = '')
    {   
        $output = '';


        // Image Sizes
        // ======================================================

        $img_src_full = wp_get_attachment_image_src($id, 'full', false);
        $img_src_thumb = wp_get_attachment_image_src($id, 'thumbnail', false);

        $w = $img_src_full[1];
        $h = $img_src_full[2];


        // Imem Output
        // ======================================================

        $output .= '<div class="hybgl-item hybgl-child-item" data-w="' . $w . '" data-h="' . $h . '">';
            $output .= '<div class="hybgl-item-inner" style="background-image:url(' . $img_src_thumb[0] . ');"></div>';
        $output .= '</div>';

        return $output;
    }
    
    
    // ======================================================
    // Styles
    // ======================================================
    
    public function styles($atts = '', $class = '')
    {
        $output = '';
        $class = '.' . $class;
        $color = $atts['color'];

        $r = $g = $b = '';
        list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");

        $color_rgba = 'rgba(' . $r . ',' . $g . ',' . $b . ',0.5' . ')';

        if ($color == '#e0bb75') {
            return false;
        }

        ob_start();
?>

<style>
<?php echo $class; ?> .hybgl-prl1-double-bounce1,
<?php echo $class; ?> .hybgl-prl1-double-bounce2,
<?php echo $class; ?> .hybgl-prl2-spinner,
<?php echo $class; ?> .hybgl-prl3-spinner > div,
<?php echo $class; ?> .hybgl-prl4-sk-folding-cube .hybgl-prl4-sk-cube:before,
<?php echo $class; ?> .hybgl-prl5-cube1, .hybgl-prl5-cube2,
<?php echo $class; ?> .hybgl-prl6-dot1, .hybgl-prl6-dot2,
<?php echo $class; ?> .hybgl-prl7-spinner > div,
<?php echo $class; ?> .hybgl-prl8-sk-circle .hybgl-prl8-sk-child:before,
<?php echo $class; ?> .hybgl-prl9-sk-cube-grid .hybgl-prl9-sk-cube,
<?php echo $class; ?> .hybgl-prl10-pacman>div:nth-child(3),
<?php echo $class; ?> .hybgl-prl10-pacman>div:nth-child(4),
<?php echo $class; ?> .hybgl-prl10-pacman>div:nth-child(5),
<?php echo $class; ?> .hybgl-prl10-pacman>div:nth-child(6),
<?php echo $class; ?>.hybgl-size-fixed.hybgl-controls-nav-pos-1 .hybgl-navigation span,
<?php echo $class; ?>.hybgl-size-fixed .hybgl-nav-pos-2 .hybgl-navigation span,
<?php echo $class; ?>.hybgl-size-fixed .hybgl-controls-topbar,
<?php echo $class; ?> .hybgl-controls-topbar .hybgl-navigation span,
<?php echo $class; ?>.hybgl-size-fixed .hybgl-button-zoom-square,
<?php echo $class; ?> .hybgl-dots .hybgl-dot-bullet {
    background: <?php echo $color; ?>;
}

<?php echo $class; ?>.hybgl-style-1 .hybgl-item-meta {
    background: <?php echo $color; ?>;
    background: <?php echo $color_rgba; ?>;
}

<?php echo $class; ?> .hybgl-prl10-pacman>div:first-of-type,
<?php echo $class; ?> .hybgl-prl10-pacman>div:nth-child(2) {
    border-top-color: <?php echo $color; ?>;
    border-left-color: <?php echo $color; ?>;
    border-bottom-color: <?php echo $color; ?>;
}

<?php echo $class; ?>.hybgl-controls-nav-pos-1 .hybgl-navigation span,
<?php echo $class; ?> .hybgl-nav-pos-2 .hybgl-navigation span,
<?php echo $class; ?>.hybgl-size-fixed .hybgl-controls-topbar .hybgl-navigation span,
<?php echo $class; ?> .hybgl-item .hybgl-button-zoom-square {
    color: <?php echo $color; ?>;
}

<?php echo $class; ?>.hybgl-size-fixed.hybgl-controls-nav-pos-1 .hybgl-navigation span,
<?php echo $class; ?>.hybgl-size-fixed .hybgl-nav-pos-2 .hybgl-navigation span,
<?php echo $class; ?>.hybgl-size-fixed .hybgl-button-zoom-square {
    color: #000;   
}

<?php echo $class; ?>.hybgl-size-fixed .hybgl-controls-topbar .hybgl-navigation span {
    background: #000;
}
</style>

<?php 
        $styles = ob_get_clean();
        return $styles;
    }
    
    
    // ======================================================
    // Main
    // ======================================================
    
    public function main($atts)
    {
        global $post;
        
        // variables
        static $instance = 0;
        $instance++;
        
        // generate ID
        $characters = '012345678';
        $charactersLength = strlen($characters);
        $id = '';
        for ($i = 0; $i < 10; $i++) {
            $id.= $characters[rand(0, $charactersLength - 1)];
        }

        $p_id          = $post ? $post->ID : $id;
        $output        = '';
        $gallery_class = '';
        $gallery_data  = '';
        $gallery_style = '';


        // Shortcode Default Atts
        // ======================================================

        $atts = shortcode_atts(array(
            'ids' => '',
            'layout' => 1,
            'size' => 'fixed',
            'ratio_w' => 2,
            'ratio_h' => 1,
            'nav' => false,
            'nav_pos' => 1,
            'dots' => false,
            'dots_pos' => 1,
            'thumbs_w' => 80,
            'thumbs_h' => 80,
            'thumbs_gap' => 10,
            'formats' => 'false',
            'color' => '#b90000',
            'lightbox' => 'false',
            'lb_pos' => 1,
            'lb_type' => 'mp',
            'meta_title' => 'false',
            'meta_descr' => 'false',
            'meta_animation' => 'slideInUp',
            'animation_main' => 'fadeIn',
            'animation_child' => 'zoomIn',
            'preloader' => 1,
            'loader_delay' => 300,
            'ct_w_vl' => 100,
            'ct_w_un' => 'pc',
            'ct_align' => 'none',
            'custom_class' => '',
            'custom_id' => '',
            'res' => 'false'
        ), $atts, 'hybrid_gallery_slider');


        // Child Container (CLASS)
        // ======================================================

        if ( $atts['layout'] == 2 ) { 
            $gallery_class .= ' hybgl-width-child hybgl-child-horizontal';        
        } elseif ( $atts['layout'] == 3 ) { 
            $gallery_class .= ' hybgl-width-child hybgl-child-vertical';        
        } else {
            $gallery_class .= ' hybgl-no-child';
        }


        // Mode (CLASS)
        // ======================================================

        if ($atts['size'] == 'adaptive') {
            $gallery_class .= ' hybgl-size-adaptive';
        } elseif ($atts['size'] == 'equal') {
            $gallery_class .= ' hybgl-size-equal';
        } else {
            $gallery_class .= ' hybgl-size-fixed';
        }


        // Meta (CLASS)
        // ======================================================
        
        $gallery_class .= ' hybgl-style-1';


        // Lightbox (CLASS)
        // ====================================================== 
        
        if ($atts['lightbox'] == "true") {
            if ($atts['lb_type'] == "mp") {
                wp_enqueue_script("hybrid-gallery-lightbox-magnific-popup");
                wp_enqueue_style("hybrid-gallery-lightbox-magnific-popup");
                
                $gallery_class .= " hybgl-lightbox hybgl-lightbox-lib-mp";
            } elseif ($atts['lb_type'] == "fyb") {
                wp_enqueue_script("hybrid-gallery-lightbox-fancybox");
                wp_enqueue_style("hybrid-gallery-lightbox-fancybox");
                
                $gallery_class .= " hybgl-lightbox hybgl-lightbox-lib-fyb";
            } elseif ($atts['lb_type'] == "ilb") {
                wp_enqueue_script("hybrid-gallery-lightbox-ilightbox");
                wp_enqueue_style("hybrid-gallery-lightbox-ilightbox");
                
                $gallery_class .= " hybgl-lightbox hybgl-lightbox-lib-ilb";
            } elseif ($atts['lb_type'] == "cb") {
                wp_enqueue_script("hybrid-gallery-lightbox-colorbox");
                wp_enqueue_style("hybrid-gallery-lightbox-colorbox");
                
                $gallery_class .= " hybgl-lightbox hybgl-lightbox-lib-cb";
            } elseif ($atts['lb_type'] == "lg") {
                wp_enqueue_script("hybrid-gallery-lightbox-lightgallery");
                wp_enqueue_style("hybrid-gallery-lightbox-lightgallery");
                
                $gallery_class .= " hybgl-lightbox hybgl-lightbox-lib-lg";
            } elseif ($atts['lb_type'] == "pp") {
                wp_enqueue_script("hybrid-gallery-lightbox-prettyphoto");
                wp_enqueue_style("hybrid-gallery-lightbox-prettyphoto");
                
                $gallery_class .= " hybgl-lightbox hybgl-lightbox-lib-pp";
            } elseif ($atts['lb_type'] == "lc") {
                wp_enqueue_script("hybrid-gallery-lightbox-lightcase");
                wp_enqueue_style("hybrid-gallery-lightbox-lightcase");
                
                $gallery_class .= " hybgl-lightbox hybgl-lightbox-lib-lc";
            }
        } else {
            $gallery_class .= ' hybgl-lightbox-off';
        }


        // Animation Main (CLASS)
        // ======================================================

        if ( $atts['animation_main'] && $atts['animation_main'] != "false" ) {
            $gallery_class .= ' hybgl-extra-animation';
        }


        // Animation Child (CLASS)
        // ======================================================

        if ( $atts['animation_child'] && $atts['animation_child'] != "false" ) {
            $gallery_class .= ' hybgl-extra-child-animation';
        }


        // Nav Position (CLASS)
        // ======================================================

        if ($atts['nav'] == "true") {
            $gallery_class .= ' hybgl-controls-nav-pos-' . $atts['nav_pos'];
        }


        // Dots Position (CLASS)
        // ======================================================

        if ($atts['dots'] == "true") {
            $gallery_class .= ' hybgl-controls-dots-pos-' . $atts['dots_pos'];
        }


        // Align (CLASS)
        // ======================================================

        if ($atts['ct_align'] != 'none') {
            $gallery_class .= ' hybgl-box-align-' . $atts['ct_align'];
        }


        // ID (DATA)
        // ======================================================   
        
        $gallery_data .= ' data-id="' . $p_id . '"';


        // Selector (DATA)
        // ======================================================   
        
        $gallery_data .= ' data-selector="hybgl-slider-' . $p_id . '-' . $instance . '"';


        // Thumbs (DATA)
        // ======================================================  
        
        if ($atts['layout'] == 2 || $atts['layout'] == 3) {
            $gallery_data .= ' data-thumbs-width="' . $atts['thumbs_w'] . '"';
            $gallery_data .= ' data-thumbs-height="' . $atts['thumbs_h'] . '"';
            $gallery_data .= ' data-thumbs-gap="' . $atts['thumbs_gap'] . '"';
        }


        // Mode (DATA)
        // ======================================================
        
        $gallery_data .= ' data-mode="' . $atts['size'] . '"';


        // Thumbs & Thumbs Direction (DATA)
        // ======================================================

        if ( $atts['layout'] == 2 ) {
            $gallery_data .= ' data-thumbs="true" data-thumbs-vertical="false"';
        } elseif ( $atts['layout'] == 3 ) {
            $gallery_data .= ' data-thumbs="true" data-thumbs-vertical="true"';
        } else {
            $gallery_data .= ' data-thumbs="false"';
        }


        // Ratio (DATA)
        // ======================================================
        
        $gallery_data .= ' data-ratio="' . $atts['ratio_w'] . '-' . $atts['ratio_h'] . '"';


        // Navigation (DATA)
        // ======================================================
        
        $gallery_data .= ' data-nav="' . $atts['nav'] . '"';
        
        
        // Dots (DATA)
        // ======================================================
        
        $gallery_data .= ' data-dots="' . $atts['dots'] . '"';


        // Meta Animation (DATA)
        // ======================================================
        
        if ($atts['meta_title'] != "false" || $atts['meta_descr'] != "false") {
            $gallery_data .= ' data-meta-animation="' . $atts['meta_animation'] . '"';
        }


        // Lightbox (DATA)
        // ======================================================  
        
        if ($atts['lightbox'] == "true") {
            $gallery_data .= ' data-lightbox="' . $atts['lb_type'] . '"';
        }


        // Animation Main (DATA)
        // ======================================================  
        
        $gallery_data .= ' data-animation-main="' . $atts['animation_main'] . '"';


        // Animation Main Child (DATA)
        // ======================================================  
        
        $gallery_data .= ' data-animation-child="' . $atts['animation_child'] . '"';
		
		
        // Loader Delay (DATA)
        // ======================================================  
        
        $gallery_data .= ' data-loader-delay="' . $atts['loader_delay'] . '"';	


        // Design (DATA)
        // ====================================================== 
        
        $gallery_data .= ' data-design="{' . '#w_vl#:#' . $atts['ct_w_vl'] . '#,#w_un#:#' . $atts['ct_w_un'] . '#,#align#:#' . $atts['ct_align'] . '#}"';

        
        // Responsiveness (DATA)
        // ====================================================== 
        
        $gallery_data .= ' data-res="' . $atts['res'] . '"';


        // Align (Style)
        // ====================================================== 
        
        if ( $atts['ct_w_vl'] == 100 && $atts['ct_w_un'] == 'pc' ) {} else {
            $unit = ($atts['ct_w_un'] == 'pc')  ? '%' : 'px';

            $gallery_style .= ' style="width:' . $atts['ct_w_vl'] . $unit . ';"';            
        }


        // Unique Class for Gallery
        // ====================================================== 
        
        $hybgl_unique_class = 'hybgl-slider-' . $p_id . '-' . $instance;


        // Custom Class for Gallery
        // ====================================================== 
        
        $hybgl_custom_class = 'hybgl-slider-' . $p_id . '-' . $instance;


        // Custom Class for Gallery
        // ====================================================== 
        
        if ($atts['custom_class']) {
            $hybgl_custom_class = ' ' . esc_attr($atts['custom_class']);
        } else {
            $hybgl_custom_class = '';
        }
        

        // Custom ID
        // ====================================================== 

        if ($atts['custom_id']) {
            $gallery_id = ' id="' . esc_attr($atts['custom_id']) . '"';
        } else {
            $gallery_id = '';
        }

        
        // Include Styles
        // ====================================================== 
        
        $output .= $this->styles($atts, $hybgl_unique_class);


        // Start Gallery
        // ====================================================== 

        $output .= '<!-- Start Hybrid Gallery -->';
        $output.= '<div' . $gallery_id . ' class="hybgl-box hybgl-mode-slider ' . $gallery_class . ' ' . $hybgl_unique_class . $hybgl_custom_class . ' hybgl-clearfix"' . $gallery_data . $gallery_style . '>';
        
        
            // Loader
            // ======================================================

            $loader = 'hybgl-loader hybgl-loader-center hybgl-clearfix';

            if ( $atts['preloader'] == 2 ) {
                $output .= '<div class="' . $loader . ' hybgl-prl2-spinner"></div>';
            } else if ( $atts['preloader'] == 3 ) {
                $output .= '<div class="' . $loader . ' hybgl-prl3-spinner">';
                    $output .= '<div class="hybgl-prl3-rect1"></div>';
                    $output .= '<div class="hybgl-prl3-rect2"></div>';
                    $output .= '<div class="hybgl-prl3-rect3"></div>';
                    $output .= '<div class="hybgl-prl3-rect4"></div>';
                    $output .= '<div class="hybgl-prl3-rect5"></div>';
                $output .= '</div>';
            } else if ( $atts['preloader'] == 4 ) {
                $output .= '<div class="' . $loader . ' hybgl-prl4-sk-folding-cube">';
                    $output .= '<div class="hybgl-prl4-sk-cube1 hybgl-prl4-sk-cube"></div>';
                    $output .= '<div class="hybgl-prl4-sk-cube2 hybgl-prl4-sk-cube"></div>';
                    $output .= '<div class="hybgl-prl4-sk-cube4 hybgl-prl4-sk-cube"></div>';
                    $output .= '<div class="hybgl-prl4-sk-cube3 hybgl-prl4-sk-cube"></div>';
                $output .= '</div>';
            } else if ( $atts['preloader'] == 5 ) {
                $output .= '<div class="' . $loader . ' hybgl-prl5-spinner">';
                    $output .= '<div class="hybgl-prl5-cube1"></div>';
                    $output .= '<div class="hybgl-prl5-cube2"></div>';
                $output .= '</div>';
            } else if ( $atts['preloader'] == 6 ) {
                $output .= '<div class="' . $loader . ' hybgl-prl6-spinner">';
                    $output .= '<div class="hybgl-prl6-dot1"></div>';
                    $output .= '<div class="hybgl-prl6-dot2"></div>';
                $output .= '</div>';
            } else if ( $atts['preloader'] == 7 ) {
                $output .= '<div class="' . $loader . ' hybgl-prl7-spinner">';
                    $output .= '<div class="hybgl-prl7-bounce1"></div>';
                    $output .= '<div class="hybgl-prl7-bounce2"></div>';
                    $output .= '<div class="hybgl-prl7-bounce3"></div>';
                $output .= '</div>';
            } else if ( $atts['preloader'] == 8 ) {
                $output .= '<div class="' . $loader . ' hybgl-prl8-sk-circle">';
                    $output .= '<div class="hybgl-prl8-sk-circle1 hybgl-prl8-sk-child"></div>';
                    $output .= '<div class="hybgl-prl8-sk-circle2 hybgl-prl8-sk-child"></div>';
                    $output .= '<div class="hybgl-prl8-sk-circle3 hybgl-prl8-sk-child"></div>';
                    $output .= '<div class="hybgl-prl8-sk-circle4 hybgl-prl8-sk-child"></div>';
                    $output .= '<div class="hybgl-prl8-sk-circle5 hybgl-prl8-sk-child"></div>';
                    $output .= '<div class="hybgl-prl8-sk-circle6 hybgl-prl8-sk-child"></div>';
                    $output .= '<div class="hybgl-prl8-sk-circle7 hybgl-prl8-sk-child"></div>';
                    $output .= '<div class="hybgl-prl8-sk-circle8 hybgl-prl8-sk-child"></div>';
                    $output .= '<div class="hybgl-prl8-sk-circle9 hybgl-prl8-sk-child"></div>';
                    $output .= '<div class="hybgl-prl8-sk-circle10 hybgl-prl8-sk-child"></div>';
                    $output .= '<div class="hybgl-prl8-sk-circle11 hybgl-prl8-sk-child"></div>';
                    $output .= '<div class="hybgl-prl8-sk-circle12 hybgl-prl8-sk-child"></div>';
                $output .= '</div>';
            } else if ( $atts['preloader'] == 9 ) {
                $output .= '<div class="' . $loader . ' hybgl-prl9-sk-cube-grid">';
                    $output .= '<div class="hybgl-prl9-sk-cube hybgl-prl9-sk-cube1"></div>';
                    $output .= '<div class="hybgl-prl9-sk-cube hybgl-prl9-sk-cube2"></div>';
                    $output .= '<div class="hybgl-prl9-sk-cube hybgl-prl9-sk-cube3"></div>';
                    $output .= '<div class="hybgl-prl9-sk-cube hybgl-prl9-sk-cube4"></div>';
                    $output .= '<div class="hybgl-prl9-sk-cube hybgl-prl9-sk-cube5"></div>';
                    $output .= '<div class="hybgl-prl9-sk-cube hybgl-prl9-sk-cube6"></div>';
                    $output .= '<div class="hybgl-prl9-sk-cube hybgl-prl9-sk-cube7"></div>';
                    $output .= '<div class="hybgl-prl9-sk-cube hybgl-prl9-sk-cube8"></div>';
                    $output .= '<div class="hybgl-prl9-sk-cube hybgl-prl9-sk-cube9"></div>';
                $output .= '</div>';
            } else if ( $atts['preloader'] == 10 ) {
                $output .= '<div class="' . $loader . ' hybgl-prl10-pacman">';
                    $output .= '<div></div>';
                    $output .= '<div></div>';
                    $output .= '<div></div>';
                    $output .= '<div></div>';
                    $output .= '<div></div>';
                $output .= '</div>';
            } else {
                $output .= '<div class="' . $loader . ' hybgl-prl1-bounce-loader">';
                    $output .= '<div class="hybgl-prl1-spinner">';
                        $output .= '<div class="hybgl-prl1-double-bounce1"></div>';
                        $output .= '<div class="hybgl-prl1-double-bounce2"></div>';
                    $output .= '</div>';
                $output .= '</div>';
            }


            // Navigation - Controls Type #3
            // ======================================================

            if (($atts['nav'] == "true" && $atts['nav_pos'] == 3) || ($atts['dots'] == "true" && $atts['dots_pos'] == 3)) {
                $output.= '<div class="hybgl-controls-topbar hybgl-clearfix">';
            }
                if ($atts['nav'] == "true" && $atts['nav_pos'] == 3) {
                    $output.= '<div class="hybgl-navigation">';
                        $output.= '<span class="hybgl-arrows-left"><i class="fa fa-chevron-left"></i></span>';
                        $output.= '<span class="hybgl-arrows-right"><i class="fa fa-chevron-right"></i></span>';
                    $output.= '</div>';
                }
            if (($atts['nav'] == "true" && $atts['nav_pos'] == 3) || ($atts['dots'] == "true" && $atts['dots_pos'] == 3)) {
                $output.= '</div>';
            }


            // Wrapper 
            // ====================================================== 

            $output .= '<div class="hybgl-wrapper hybgl-clearfix">';

                // create array from ids
                if ($atts['ids']) {
                    $aid_array = explode(',', $atts['ids']);
                } else {
                    $aid_array = '';
                }


                // Navigation - Controls Type #2
                // ======================================================

                if ($atts['nav'] == "true" && $atts['nav_pos'] == 2) {
                    $output.= '<div class="hybgl-nav-pos-2 hybgl-clearfix">';
                        $output.= '<div class="hybgl-navigation">';
                            $output.= '<span class="hybgl-arrows-left"><i class="fa fa-chevron-left"></i></span>';
                            $output.= '<span class="hybgl-arrows-right"><i class="fa fa-chevron-right"></i></span>';
                        $output.= '</div>';
                    $output.= '</div>';
                }


                // Container Main
                // ======================================================

                $output.= '<div class="hybgl-container">';


                    // Container Main Inner
                    // ======================================================

                    $output.= '<div class="hybgl-container-inner">';

                        if (is_array($aid_array)) {
                            foreach($aid_array as $aid) {
                                $output.= $this->item_main( $atts, $aid );
                            }
                        }

                    $output.= '</div>';

                    // ======================================================   
                    // End Container Main Inner


                    // Navigation - Controls Type #1
                    // ======================================================

                    if ($atts['nav'] == "true" && $atts['nav_pos'] == 1) {
                        $output.= '<div class="hybgl-navigation">';
                            $output.= '<span class="hybgl-arrows-left"><i class="fa fa-chevron-left"></i></span>';
                            $output.= '<span class="hybgl-arrows-right"><i class="fa fa-chevron-right"></i></span>';
                        $output.= '</div>';
                    }


                $output.= '</div>';
				
                // ======================================================   
                // End Container Main


                // Container Main
                // ======================================================
                 
                if ( $atts['layout'] == 2 || $atts['layout'] == 3 ) {
                    $output.= '<div class="hybgl-child-container">';


                        // Container Child Inner
                        // ======================================================

                        $output.= '<div class="hybgl-child-container-inner">';

                            if (is_array($aid_array)) {
                                foreach( $aid_array as $aid ) {
                                    $output.= $this->item_child( $atts, $aid );
                                }
                            }

                        $output.= '</div>';

                        // ======================================================   
                        // End Container Child Inner


                    $output.= '</div>';
                }

                // ======================================================   
                // End Container Child


        $output .= '</div>';

        // ======================================================   
        // End Wrapper


        $output .= '</div>';
        $output .= '<!-- End Hybrid Gallery -->';
        return $output;
    }
}

new Hybrid_Gallery_Shortcode_Slider;