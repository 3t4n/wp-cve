<?php 

if( !defined( 'ABSPATH') ) exit();

class Hybrid_Gallery_Shortcode_Grid
{
    function __construct()
    {
        add_shortcode('hybrid_gallery_grid', array(
            $this,
            'main'
        ));
    }


    // ======================================================
    // stripFilter
    // ======================================================

    public function stripFilter($string) {
        // Lower case everything
        $string = strtolower($string);

        // clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);

        // convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);

        $cyr = [
            'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
            'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
            'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
            'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
        ];
        
        $lat = [
            'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
            'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
            'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
            'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'
        ];

        // convert cyr to latin
        $string = str_replace($cyr, $lat, $string);

        // remove special characters
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

        return $string;
    }


    // ======================================================
    // Item
    // ======================================================

    public function item($atts = '', $id = '')
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


        // Filter
        // ======================================================

        $filter = '';
        $filter_array = array();
        $filter_class = '';

        $filter = get_post_meta($id, '_hybrig_gallery_attach_filter', true);
        $filter = explode(', ', $filter);

        if (is_array($filter)) {
            foreach( $filter as $filter_value ) {
                $filter_array[] = 'hybgl-filter-' .$this->stripFilter($filter_value);
            }
        }

        if ( $atts['filter'] == "true" ) {
            $filter_class = ' ' . implode(' ', $filter_array);
        }


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
        if ( $atts['click_action'] == "lb" || $atts['click_action'] == "lb_link" ) {
            if ( $atts['lightbox'] == "mp" ) {
                if ( $field_type == "video" ) {
                    $lightbox_href_class = ' mfp-iframe';
                } else {
                    $lightbox_href_class = ' mfp-image'; 
                }
            } elseif ( $atts['lightbox'] == "fyb" ) {
                if ( $field_type == "video" ) {
                    $lightbox_href_class = ' fancybox-media';
                }
            } elseif ( $atts['lightbox'] == "cb" ) {
                if ( $field_type == "video" ) {
                    $lightbox_href_class = ' colorbox-media';
                } else {
                    $lightbox_href_class = ' colorbox-image';
                }
            }
        }


        // Popup URL
        // ======================================================

        if ( $field_type == "video" ) {
            if ( $atts['click_action'] == "lb" || $atts['click_action'] == "lb_link" ) {
                if ( $atts['lightbox'] == "cb" ) {
                    if ( $url_type == 'youtube' ) {
                        $popup_url = '//www.youtube.com/embed/' . $url_id[0] . '?version=3';
                    } elseif ( $url_type == 'vimeo' ) {
                        $popup_url = '//player.vimeo.com/video/' . $url_id[0] . '';
                    }
                } elseif ( $atts['lightbox'] == "lc" ) {
                    if ( $url_type == 'youtube' ) {
                        $popup_url = '//www.youtube.com/embed/' . $url_id[0] . '?version=3';
                    } elseif ( $url_type == 'vimeo' ) {
                        $popup_url = '//player.vimeo.com/video/' . $url_id[0] . '';
                    }
                } elseif ( $atts['lightbox'] == "ilb" ) {
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


        // Link Target
        // ======================================================

        if ( $atts['link_tg'] == "new" ) {
            $target = ' target="_blank"';
        }


        // Link Data
        // ======================================================      

        if ($attachment->post_content) {
            $data_descr = '<p>' . $attachment->post_content . '</p>';
        } else {
            $data_descr = '';
        }

        if ( $atts['lightbox'] == "lg" ) {
            $url_lb_data  = ' href="' . $popup_url . '" title="' . $attachment->post_title . '" data-src="' . $img_src_def[0] . '" data-sub-html="<h4>' . $attachment->post_title . '</h4>' . $data_descr . '"';
        } elseif ( $atts['lightbox'] == "fyb" ) {
            if ( $field_type == "video" ) {
                $url_lb_data = 'data-fancybox-title="<h4>' . $attachment->post_title . '</h4>' . $data_descr . '" data-src="' . $img_src_thumb[0] . '" data-fancybox-type="iframe"';
            } else {
                $url_lb_data = 'data-fancybox-title="<h4>' . $attachment->post_title . '</h4>' . $data_descr . '" data-src="' . $img_src_thumb[0] . '" data-fancybox-type="image"';
            }
        } elseif ( $atts['lightbox'] == "ilb" ) {
            if ( $field_type == "video" ) {
                $url_lb_data = 'data-type="iframe" data-options="thumbnail:\'' . $img_src_def[0] . '\', width:1280, height:720" data-title="' . $attachment->post_title . '" data-caption="' . $attachment->post_content . '"';
            } else {
                $url_lb_data = 'data-options="thumbnail:\'' . $img_src_def[0] . '\'" data-title="' . $attachment->post_title . '" data-caption="' . $attachment->post_content . '"';
            }
        } elseif ( $atts['lightbox'] == "cb" ) {
            $url_lb_data = 'data-descr="' . $attachment->post_content . '"';
        } else {
            $url_lb_data = 'data-descr="' . $attachment->post_content . '"';
        }
        
        
        // URL Lightbox Prop
        // ======================================================        

        $url_lb_prop = ' href="' . $popup_url . '" title="' . $attachment->post_title . '" ' . $url_lb_data . '';


        // URL Link Prop
        // ======================================================  

        $url_link_prop = ' href="' . $field_link . '"' . $target . ' title="' . $attachment->post_title . '"';


        // Hover Effects
        // ======================================================  

        $img_hover_effect = '';
        if ( $atts['img_hover'] != 'false' ) {
            $img_hover_effect = ' hybgl-item-img-hover' . $atts['img_hover'];
        }


        // Image Filters
        // ======================================================  

        $img_filter = '';
        if ( $atts['img_filter'] != 'false' ) {
            $img_filter = ' hybgl-item-img-filter' . $atts['img_filter'];
        }


        // Start Wrapper
        // ======================================================

        $item_start .= '<div class="hybgl-item' . $filter_class . '" data-w="' . $w . '" data-h="' . $h . '">';
            $item_start .= '<div class="hybgl-item-wrapper">';
                $item_start .= '<div class="hybgl-item-overlay' . $img_hover_effect . $img_filter . '">';
                    $item_start .= '<div class="hybgl-item-overlay-inner">';
                        if ( $atts['formats'] == "true" && $field_type == "video" ) {
                            if ( $url_type == 'vimeo' ) {                           
                                $item_start .= '<div class="hybgl-item-inner"><iframe src="https://player.vimeo.com/video/' . $url_id[0] .'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
                            } elseif ( $url_type == 'youtube' ) {
                                $item_start .= '<div class="hybgl-item-inner"><iframe src="https://www.youtube.com/embed/' . $url_id[0] . '?version=3&wmode=opaque" frameborder="0" allowfullscreen></iframe></div>'; 
                            }       
                        } else {
                            $item_start .= '<div class="hybgl-item-inner" style="background-image:url(' . $img_src_def[0] . ');"></div>';
                        }
                    $item_start .= '</div>';
                $item_start .= '</div>';
            if ( $atts['style'] == 2 ) {
                $item_data_end .= '</div>';
            } elseif ( $atts['style'] == 1 || $atts['style'] == 3 || $atts['style'] == 4 ) {
                    $item_end .= '</div>';
            }
        $item_end .= '</div>';


        // Style #1
        // ======================================================

        if ( $atts['style'] == 1 && ( $atts['formats'] != "true" || ( $atts['formats'] == "true" && $field_type != "video" ) ) ) {
            if ( $atts['click_action'] == 'lb' ) {
                if ( $atts['buttons'] == "true" ) {
                    $item_data .= '<a class="hybgl-button hybgl-button-action-zoom hybgl-button-style-full hybgl-button-style-icon' . $lightbox_href_class . '"' . $url_lb_prop . '><i class="fa fa-search-plus"></i></a>';
                } else {
                    $item_data .= '<a class="hybgl-button hybgl-button-action-zoom hybgl-button-style-full' . $lightbox_href_class . '"' . $url_lb_prop . '></a>';
                }
            } elseif ( $atts['click_action'] == 'link' ) {
                if ( $atts['buttons'] == "true" ) {
                    $item_data .= '<a class="hybgl-button hybgl-button-action-link hybgl-button-style-full hybgl-button-style-icon' . $lightbox_href_class . '"' . $url_link_prop . '><i class="fa fa-link"></i></a>';
                } else {
                    $item_data .= '<a class="hybgl-button hybgl-button-action-link hybgl-button-style-full' . $lightbox_href_class . '"' . $url_link_prop . '></a>';
                }
            } elseif ( $atts['click_action'] == 'lb_link' ) {
                $item_data .= '<a class="hybgl-button hybgl-button-action-zoom hybgl-button-style-icon' . $lightbox_href_class . '"' . $url_lb_prop . '><i class="fa fa-search-plus"></i></a>';
                $item_data .= '<a class="hybgl-button hybgl-button-action-link hybgl-button-style-icon' . $lightbox_href_class . '"' . $url_link_prop . '><i class="fa fa-link"></i></a>';
            } 

            if ($atts['meta_title'] == "true" || $atts['meta_descr'] == "true") {
                $item_data_end .= '<div class="hybgl-item-meta">';
                    if ( $attachment->post_title ) {
                        $item_data_end .= '<div class="hybgl-item-meta-title">' . $attachment->post_title . '</div>';
                    }
                    if ( $atts['meta_descr'] == "true" && $attachment->post_content ) {
                        $item_data_end .= '<div class="hybgl-item-meta-descr">' . $attachment->post_content . '</div>';
                    }
                $item_data_end .= '</div>';
            }
        }


        // Style #2
        // ======================================================

        if ( $atts['style'] == 2 ) {
            if ( $atts['formats'] != "true" || ( $atts['formats'] == "true" && $field_type != "video" ) ) {
                if ( $atts['click_action'] == 'lb' ) {
                    if ( $atts['buttons'] == "true" ) {
                        $item_data .= '<a class="hybgl-button hybgl-button-action-zoom hybgl-button-style-full hybgl-button-style-icon' . $lightbox_href_class . '"' . $url_lb_prop . '><i class="fa fa-search-plus"></i></a>';
                    } else {
                        $item_data .= '<a class="hybgl-button hybgl-button-action-zoom hybgl-button-style-full' . $lightbox_href_class . '"' . $url_lb_prop . '></a>';
                    }
                } elseif ( $atts['click_action'] == 'link' ) {
                    if ( $atts['buttons'] == "true" ) {
                        $item_data .= '<a class="hybgl-button hybgl-button-action-link hybgl-button-style-full hybgl-button-style-icon' . $lightbox_href_class . '"' . $url_link_prop . '><i class="fa fa-link"></i></a>';
                    } else {
                        $item_data .= '<a class="hybgl-button hybgl-button-action-link hybgl-button-style-full' . $lightbox_href_class . '"' . $url_link_prop . '></a>';
                    }
                } elseif ( $atts['click_action'] == 'lb_link' ) {
                    $item_data .= '<a class="hybgl-button hybgl-button-action-zoom hybgl-button-style-icon' . $lightbox_href_class . '"' . $url_lb_prop . '><i class="fa fa-search-plus"></i></a>';
                    $item_data .= '<a class="hybgl-button hybgl-button-action-link hybgl-button-style-icon' . $lightbox_href_class . '"' . $url_link_prop . '><i class="fa fa-link"></i></a>';
                }
            }

            if ($atts['layout'] != "justified" && ( $atts['meta_title'] == "true" || $atts['meta_descr'] == "true" )) {
                $item_data_end .= '<div class="hybgl-item-meta">';
                    if ( $attachment->post_title ) {
                        $item_data_end .= '<div class="hybgl-item-meta-title">' . $attachment->post_title . '</div>';
                    }
                    if ( $atts['meta_descr'] == "true" && $attachment->post_content ) {
                        $item_data_end .= '<div class="hybgl-item-meta-descr">' . $attachment->post_content . '</div>';
                    }
                $item_data_end .= '</div>';
            }
        }


        // Style #3
        // ======================================================

        if ( $atts['style'] == 3 && ( $atts['formats'] != "true" || ( $atts['formats'] == "true" && $field_type != "video" ) ) ) {
            if ( $atts['click_action'] == 'lb' ) {
                $item_data .= '<a class="hybgl-button hybgl-button-action-zoom hybgl-button-style-full' . $lightbox_href_class . '"' . $url_lb_prop . '></a>';          
            } elseif ( $atts['click_action'] == 'link' ) { 
                $item_data .= '<a class="hybgl-button hybgl-button-action-link hybgl-button-style-full' . $lightbox_href_class . '"' . $url_link_prop . '></a>';       
            }

            $item_data .= '<div class="hybgl-item-content">';
                if ( (( $atts['click_action'] == 'lb' || $atts['click_action'] == 'link' ) && $atts['buttons'] == 'true') || $atts['click_action'] == 'lb_link' ) {
                    $item_data .= '<div class="hybgl-item-buttons hybgl-clearfix">';    
                }              
                    if ( $atts['click_action'] == 'lb' ) {
                        if ( $atts['buttons'] == "true" ) {
                            $item_data .= '<i class="fa fa-search-plus"></i>';
                        }
                    } elseif ( $atts['click_action'] == 'link' ) {
                        if ( $atts['buttons'] == "true" ) {
                            $item_data .= '<i class="fa fa-link"></i>';
                        }
                    } elseif ( $atts['click_action'] == 'lb_link' ) {
                        $item_data .= '<a class="hybgl-button hybgl-button-action-zoom hybgl-button-style-icon' . $lightbox_href_class . '"' . $url_lb_prop . '><i class="fa fa-search-plus"></i></a>';
                        $item_data .= '<a class="hybgl-button hybgl-button-action-link hybgl-button-style-icon' . $lightbox_href_class . '"' . $url_link_prop . '><i class="fa fa-link"></i></a>';
                    } 
                if ( (( $atts['click_action'] == 'lb' || $atts['click_action'] == 'link' ) && $atts['buttons'] == 'true') || $atts['click_action'] == 'lb_link' ) {
                    $item_data .= '</div>';    
                }     

                if ($atts['meta_title'] == "true" || $atts['meta_descr'] == "true") {
                    $item_data_end .= '<div class="hybgl-item-meta">';
                        if ( $attachment->post_title ) {
                            $item_data_end .= '<div class="hybgl-item-meta-title">' . $attachment->post_title . '</div>';
                        }
                        if ( $atts['meta_descr'] == "true" && $attachment->post_content ) {
                            $item_data_end .= '<div class="hybgl-item-meta-descr">' . $attachment->post_content . '</div>';
                        }
                    $item_data_end .= '</div>';
                }
            $item_data_end .= '</div>';
        }


        // Style #4
        // ======================================================

        if ( $atts['style'] == 4 && ( $atts['formats'] != "true" || ( $atts['formats'] == "true" && $field_type != "video" ) ) ) {
            $item_data .= '<div class="hybgl-item-content">';
                if ( ( $atts['click_action'] == 'lb' || $atts['click_action'] == 'link' ) && $atts['buttons'] == 'true' ) {
                    $item_data .= '<div class="hybgl-item-buttons hybgl-clearfix">';    
                }              
                    if ( $atts['click_action'] == 'lb' ) {
                        if ( $atts['buttons'] == "true" ) {
                            $item_data .= '<a class="hybgl-button hybgl-button-action-zoom hybgl-button-style-full hybgl-button-style-icon' . $lightbox_href_class . '"' . $url_lb_prop . '><i class="fa fa-search-plus"></i></a>';
                        } else {
                            $item_data .= '<a class="hybgl-button hybgl-button-action-zoom hybgl-button-style-full' . $lightbox_href_class . '"' . $url_lb_prop . '></a>';
                        }
                    } elseif ( $atts['click_action'] == 'link' ) {
                        if ( $atts['buttons'] == "true" ) {
                            $item_data .= '<a class="hybgl-button hybgl-button-action-link hybgl-button-style-full hybgl-button-style-icon' . $lightbox_href_class . '"' . $url_link_prop . '><i class="fa fa-link"></i></a>';
                        } else {
                            $item_data .= '<a class="hybgl-button hybgl-button-action-link hybgl-button-style-full' . $lightbox_href_class . '"' . $url_link_prop . '></a>';
                        }
                    } elseif ( $atts['click_action'] == 'lb_link' ) {
                        $item_data .= '<a class="hybgl-button hybgl-button-action-zoom hybgl-button-style-icon' . $lightbox_href_class . '"' . $url_lb_prop . '><i class="fa fa-search-plus"></i></a>';
                        $item_data .= '<a class="hybgl-button hybgl-button-action-link hybgl-button-style-icon' . $lightbox_href_class . '"' . $url_link_prop . '><i class="fa fa-link"></i></a>';
                    } 
                if ( ( $atts['click_action'] == 'lb' || $atts['click_action'] == 'link' ) && $atts['buttons'] == 'true' ) {
                    $item_data .= '</div>';    
                }             

                if ($atts['meta_title'] == "true" || $atts['meta_descr'] == "true") {
                    $item_data_end .= '<div class="hybgl-item-meta">';
                        if ( $attachment->post_title ) {
                            $item_data_end .= '<div class="hybgl-item-meta-title">' . $attachment->post_title . '</div>';
                        }
                        if ( $atts['meta_descr'] == "true" && $attachment->post_content ) {
                            $item_data_end .= '<div class="hybgl-item-meta-descr">' . $attachment->post_content . '</div>';
                        }
                    $item_data_end .= '</div>';
                }
            $item_data_end .= '</div>';
        }

        $output = $item_start . $item_data . $item_data_end . $item_end;
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
<?php echo $class; ?>.hybgl-style-1 .hybgl-button i,
<?php echo $class; ?>.hybgl-style-1 .hybgl-item-meta,
<?php echo $class; ?>.hybgl-style-3 .hybgl-item-inner:before,
<?php echo $class; ?> .hybgl-filter-label:hover,
<?php echo $class; ?> .hybgl-filter-label-active,
<?php echo $class; ?> .hybgl-filter-label:before {
    background: <?php echo $color; ?>;
}

<?php echo $class; ?> .hybgl-pagin-num-page:hover,
<?php echo $class; ?> .hybgl-pagin-cs-page:hover,
<?php echo $class; ?>.hybgl-style-2.hybgl-buttons-show .hybgl-item-inner:before {
    background: <?php echo $color; ?>;
    background: <?php echo $color_rgba; ?>;
}

<?php echo $class; ?> .hybgl-prl10-pacman>div:first-of-type,
<?php echo $class; ?> .hybgl-prl10-pacman>div:nth-child(2) {
    border-top-color: <?php echo $color; ?>;
    border-left-color: <?php echo $color; ?>;
    border-bottom-color: <?php echo $color; ?>;
}

<?php echo $class; ?> .hybgl-filter-label,
<?php echo $class; ?> .hybgl-pagin-num-page:hover,
<?php echo $class; ?> .hybgl-pagin-cs-page:hover {
    border-color: <?php echo $color; ?>;
}

<?php echo $class; ?> .hybgl-filter-label,
<?php echo $class; ?>.hybgl-style-2.hybgl-click-action-lb-link .hybgl-button i:hover,
<?php echo $class; ?>.hybgl-style-3 .hybgl-button i,
<?php echo $class; ?>.hybgl-style-4 .hybgl-button i,
<?php echo $class; ?>.hybgl-style-4 .hybgl-item-meta .hybgl-item-meta-title {
    color: <?php echo $color; ?>;
}

<?php echo $class; ?> .hybgl-filter-label:hover,
<?php echo $class; ?> .hybgl-filter-label-active {
    color: #fff;
}

<?php echo $class; ?> .hybgl-filter-label-active:before {
    background: #fff;
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
            'layout' => 'grid',
            'size' => 'equal',
            'metro_style' => 1,
            'ratio_w' => 1,
            'ratio_h' => 1,
            'cols' => 3,
            'gap_x' => 10,
            'gap_y' => 10,
            'rowheight' => 150,
            'max_rowheight' => 'false',
            'lastrow' => 'nojustify',
            'img_hover' => 'false',
            'img_filter' => 'false',
            'formats' => 'false',
            'color' => '#e0bb75',
            'style' => 1,
            'meta_title' => 'false',
            'meta_descr' => 'false',
            'click_action' => 'lb',
            'buttons' => 'false',
            'link_tg' => 'same',
            'lightbox' => 'mp',
            'pagination' => 'true',
            'pg_type' => 'more',
            'pg_ajax' => 'false',
            'pg_posts' => 10,
            'filter' => 'false',
            'animation' => 'fadeInUp',
            'preloader' => 1,
            'loader_delay' => 300,
            'ct_w_vl' => 100,
            'ct_w_un' => 'pc',
            'ct_align' => 'none',
            'custom_class' => '',
            'custom_id' => '',
            'res' => 'false'
        ), $atts, 'hybrid_gallery_grid');
                 
        
        // Layout (CLASS)
        // ======================================================
        
        if ($atts['layout'] == "justified") {
            $gallery_class .= " hybgl-mode-grid hybgl-layout-justified";
            $mode_id = "justified";
        } elseif ($atts['layout'] == "masonry") {
            $gallery_class .= " hybgl-mode-grid hybgl-layout-masonry";
            $mode_id = "masonry";
        } elseif ($atts['layout'] == "metro") {
            $gallery_class .= " hybgl-mode-grid hybgl-layout-metro";
            $mode_id = "metro";
        } else {
            $gallery_class .= " hybgl-mode-grid hybgl-layout-grid";
            $mode_id = "grid";
        }
        

        // Size (CLASS)
        // ======================================================
        
        if ($atts['size'] == 'fixed') {
            $gallery_class .= ' hybgl-size-fixed';
        } else {
            $gallery_class .= ' hybgl-size-equal';
        }

        
        // Lightbox (CLASS)
        // ====================================================== 
        
        if ($atts['click_action'] == "lb" || $atts['click_action'] == "lb_link") {
            if ($atts['lightbox'] == "mp") {
                wp_enqueue_script("hybrid-gallery-lightbox-magnific-popup");
                wp_enqueue_style("hybrid-gallery-lightbox-magnific-popup");
                
                $gallery_class .= " hybgl-lightbox hybgl-lightbox-lib-mp";
            } elseif ($atts['lightbox'] == "fyb") {
                wp_enqueue_script("hybrid-gallery-lightbox-fancybox");
                wp_enqueue_style("hybrid-gallery-lightbox-fancybox");
                
                $gallery_class .= " hybgl-lightbox hybgl-lightbox-lib-fyb";
            } elseif ($atts['lightbox'] == "ilb") {
                wp_enqueue_script("hybrid-gallery-lightbox-ilightbox");
                wp_enqueue_style("hybrid-gallery-lightbox-ilightbox");
                
                $gallery_class .= " hybgl-lightbox hybgl-lightbox-lib-ilb";
            } elseif ($atts['lightbox'] == "cb") {
                wp_enqueue_script("hybrid-gallery-lightbox-colorbox");
                wp_enqueue_style("hybrid-gallery-lightbox-colorbox");
                
                $gallery_class .= " hybgl-lightbox hybgl-lightbox-lib-cb";
            } elseif ($atts['lightbox'] == "lg") {
                wp_enqueue_script("hybrid-gallery-lightbox-lightgallery");
                wp_enqueue_style("hybrid-gallery-lightbox-lightgallery");
                
                $gallery_class .= " hybgl-lightbox hybgl-lightbox-lib-lg";
            } elseif ($atts['lightbox'] == "pp") {
                wp_enqueue_script("hybrid-gallery-lightbox-prettyphoto");
                wp_enqueue_style("hybrid-gallery-lightbox-prettyphoto");
                
                $gallery_class .= " hybgl-lightbox hybgl-lightbox-lib-pp";
            } elseif ($atts['lightbox'] == "lc") {
                wp_enqueue_script("hybrid-gallery-lightbox-lightcase");
                wp_enqueue_style("hybrid-gallery-lightbox-lightcase");
                
                $gallery_class .= " hybgl-lightbox hybgl-lightbox-lib-lc";
            }
        } else {
            $gallery_class .= ' hybgl-lightbox-off';
        }
        
        
        // Click Action (CLASS)
        // ======================================================

        // class for click action
        if ($atts['click_action'] == "lb") {
            $gallery_class .= ' hybgl-click-action-lb';                
        } elseif ($atts['click_action'] == "link") {
            $gallery_class .= ' hybgl-click-action-link';  
        } elseif ($atts['click_action'] == "lb_link") {
            $gallery_class .= ' hybgl-click-action-lb-link';  
        } else {
            $gallery_class .= ' hybgl-click-action-off';  
        }


        // Style (CLASS)
        // ======================================================

        $gallery_class .= ' hybgl-style-' . $atts['style'];
        
        // class for buttons
        if ((($atts['click_action'] == "lb" || $atts['click_action'] == "link") && $atts['buttons'] == "true") || $atts['click_action'] == "lb_link") {
            $gallery_class .= ' hybgl-buttons-show';                
        } else {
            $gallery_class .= ' hybgl-buttons-hide';  
        }


        // Meta (CLASS)
        // ======================================================

        if ($atts['meta_title'] == "true" || $atts['meta_descr'] == "true") {
            $gallery_class .= ' hybgl-with-meta';             
        }
        
        
        // Filter (CLASS)
        // ======================================================
        
        if ($atts['filter'] == "true") {
            $gallery_class .= ' hybgl-extra-filter';
        }
        
        
        // Pagination (CLASS)
        // ======================================================
        
        if ($atts['pagination'] == "true" && ($atts['pg_type'] == "more" || $atts['pg_type'] == "scroll")) {
            $gallery_class .= ' hybgl-extra-infinite';
        }
        
        
        // Animation (CLASS)
        // ======================================================
        
        if ($atts['animation'] && $atts['animation'] != "false") {
            $gallery_class .= ' hybgl-extra-animation';
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
        
        $gallery_data .= ' data-selector="hybgl-' . $mode_id . '-' . $p_id . '-' . $instance . '"';

        
        // Mode (DATA)
        // ======================================================   
        
        if ($atts['layout'] != 'justified') {
            $gallery_data .= ' data-mode="isotope"';
        } else {
            $gallery_data .= ' data-mode="justified"';
        }
        
        
        // Layout (DATA)
        // ====================================================== 
        
        $gallery_data .= ' data-layout="' . $atts['layout'] . '"';
        
        
        // Metro Style (DATA)
        // ====================================================== 
        
        if ($atts['layout'] == "metro") {
            $gallery_data .= ' data-metro-style="' . $atts['metro_style'] . '"';
        }


        // Size (DATA)
        // ======================================================
        
        $gallery_data .= ' data-size="' . $atts['size'] . '"';
        
        
        // Cols (DATA)
        // ====================================================== 
        
        $gallery_data .= ' data-cols="' . $atts['cols'] . '"';
        
        
        // Gap (DATA)
        // ====================================================== 
        
        $gallery_data .= ' data-gap-x="' . $atts['gap_x'] . '"';
        $gallery_data .= ' data-gap-y="' . $atts['gap_y'] . '"';
        
        
        // Ratio (DATA)
        // ====================================================== 
        
        $atts['ratio_w'] = intval($atts['ratio_w']);
        $atts['ratio_h'] = intval($atts['ratio_h']);
        
        $gallery_data .= ' data-ratio="' . $atts['ratio_w'] . '-' . $atts['ratio_h'] . '"';
        
        
        // rowHeight (DATA)
        // ======================================================   
        
        if ($atts['layout'] == "justified") {
            $gallery_data .= ' data-rowheight="' . $atts['rowheight'] . '"';
        }
        
        
        // maxRowHeight (DATA)
        // ======================================================  
        
        if ($atts['layout'] == "justified") {
            if ( $atts['max_rowheight'] == "-1" ) {
                $atts['max_rowheight'] = "false";
            }
            $gallery_data .= ' data-max-rowheight="' . $atts['max_rowheight'] . '"';
        }


        // lastRow (DATA)
        // ======================================================  
        
        if ($atts['layout'] == "justified") {
            if ( $atts['lastrow'] == "-1" ) {
                $atts['lastrow'] = "false";
            }
            $gallery_data .= ' data-lastrow="' . $atts['lastrow'] . '"';
        }
        
        
        // Lightbox (DATA)
        // ======================================================  
        
        if ($atts['click_action'] == "lb" || $atts['click_action'] == "lb_link") {
            $gallery_data .= ' data-lightbox="' . $atts['lightbox'] . '"';
        }
        
        
        // Style (DATA)
        // ====================================================== 
        
        $gallery_data .= ' data-style="' . $atts['style'] . '"';
        
        
        // Pagination Type (DATA)
        // ====================================================== 
        
        if ($atts['pagination'] == "true") {
            if ($atts['pg_type'] == "more") {
                $gallery_data .= ' data-pagin="more"';
            } elseif ($atts['pg_type'] == "scroll") {
                $gallery_data .= ' data-pagin="scroll"';
            } elseif ($atts['pg_type'] == "num") {
                $gallery_data .= ' data-pagin="num"';
            } elseif ($atts['pg_type'] == "classic") {
                $gallery_data .= ' data-pagin="classic"';
            } else {
                $gallery_data .= ' data-pagin="false"';
            }
            
            if (($atts['pg_type'] == "num" || $atts['pg_type'] == "classic") && $atts['pg_ajax'] == "true") {
                $gallery_data .= ' data-pagin-ajax="true"';
            } else {
                $gallery_data .= ' data-pagin-ajax="false"';
            }
        }
        

        // Animation (DATA)
        // ======================================================  
        
        $gallery_data .= ' data-animation="' . $atts['animation'] . '"';
		
		
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
        
        $hybgl_unique_class = 'hybgl-' . $mode_id . '-' . $p_id . '-' . $instance;


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
        $output .= '<div' . $gallery_id . ' class="hybgl-box' . $gallery_class . ' ' . $hybgl_unique_class . $hybgl_custom_class . ' hybgl-clearfix"' . $gallery_data . $gallery_style . '>';
        
        
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
        
        
            // Wrapper 
            // ====================================================== 
        
            $output .= '<div class="hybgl-wrapper hybgl-clearfix">';
        
        
                // Filter
                // ====================================================== 
        
                // create array from ids
                if ($atts['ids']) {
                    $aid_array = explode(',', $atts['ids']);
                } else {
                    $aid_array = '';
                }
        
                // Create filter from attachment field
                $xfilter = array();

                if (is_array($aid_array)) {
                    foreach($aid_array as $aid) {
                        $xfilter_field = get_post_meta($aid, '_hybrig_gallery_attach_filter', true);
                        $xfilter_field = explode(', ', $xfilter_field);
            
                        if (is_array($xfilter_field)) {
                            foreach ($xfilter_field as $xfilter_field_value) {
                                $xfilter[] = $xfilter_field_value;
                            }
                        }
                    }
                }
        
                $xfilter = array_unique($xfilter);
        
                if ($atts['filter'] == "true") {
                    $output .= '<div class="hybgl-filter-items hybgl-clearfix">';
                        $output .= '<div class="hybgl-filter-items-wrapper">';
                            $output .= '<div class="hybgl-filter-label hybgl-filter-label-active" data-filter="*">All</div>';

                                if (is_array($xfilter)) {
                                    foreach ($xfilter as $filter) {
                                        if ($filter) {
                                            $output .= '<div class="hybgl-filter-label" data-filter=".hybgl-filter-' . $this->stripFilter($filter) . '">' . ucfirst($filter) . '</div>';
                                        }
                                    }
                                }

                        $output .= '</div>';
                    $output .= '</div>';
                }
        
        
                // Container
                // ====================================================== 
        
                $output .= '<div class="hybgl-container hybgl-clearfix">';
        
        
                    // Start Loop
                    // ====================================================== 
        
                    if ($atts['pagination'] == "true") {
                        global $paged;

                        if (is_front_page()) {
                            $paged = (get_query_var('page')) ? get_query_var('page') : 1;
                        } else {
                            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        }
            
                        $pg_args = array(
                            'paged' => $paged,
                            'posts_per_page' => intval($atts['pg_posts']),
                            'order' => 'DESC',
                            'orderby' => 'post__in',
                            'post_type' => 'attachment',
                            'post_status' => 'inherit',
                            'post_mime_type' => 'image',
                            'post__in' => $aid_array
                        );
            
                        global $pg_query;
                        $pg_query = new WP_Query($pg_args);
            
                        if ($pg_query->have_posts()) {
                            while ($pg_query->have_posts()) {
                                $pg_query->the_post();
                    
                                // loading loop	    
                                $output .= $this->item($atts, get_the_ID());
                            }
                        }

                        wp_reset_postdata();
                        wp_reset_query();
                    } else {
                        $aid_index = 0;

                        if (is_array($aid_array)) {
                            foreach($aid_array as $aid) {
                                $aid_index++;
                
                                $output .= $this->item($atts, $aid);
                            }
                        }
                    }
        
                $output .= '</div>';

                // ======================================================   
                // End Container
      

                // Start Pagination
                // ====================================================== 
        
                if ($atts['pagination'] == "true") {
                    if ($atts['pg_type'] == "num") {
                        if ($atts['pg_ajax'] == "true") {
                            $output .= hybrid_gallery_pagination($pg_query->max_num_pages, 'num', 'ajax', 1);
                        } else {
                            $output .= hybrid_gallery_pagination($pg_query->max_num_pages, 'num', '', 1);
                        }
                    } elseif ($atts['pg_type'] == "classic") {
                        if ($atts['pg_ajax'] == "true") {
                            $output .= hybrid_gallery_pagination($pg_query->max_num_pages, 'classic', 'ajax', '');
                        } else {
                            $output .= hybrid_gallery_pagination($pg_query->max_num_pages, 'classic', '', '');
                        }
                    } elseif ($atts['pg_type'] == "scroll") {
                        $output .= hybrid_gallery_pagination($pg_query->max_num_pages, 'scroll', '', '');
                    } else {
                        $output .= hybrid_gallery_pagination($pg_query->max_num_pages, 'more', '', '');
                    }
                }


            $output .= '</div>';

            // ======================================================   
            // End Wrapper


        $output .= '</div>';
        $output .= '<!-- End Hybrid Gallery -->';
        return $output;
    }
}

new Hybrid_Gallery_Shortcode_Grid;