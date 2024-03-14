<?php

if( !defined( 'ABSPATH') ) exit();

/**
 * Advanced pagination
 * 
 * @package WordPress
 * @subpackage Fenomen
 * @since Hybrid Gallery 1.0
 */

if (!function_exists('hybrid_gallery_pagination')) {
    function hybrid_gallery_pagination($pages = '', $type = 'classic', $mode = '', $range = 1)
    {
        global $paged;
        
        $output     = '';
        $link_class = '';
        
        $showitems = ($range * 2) + 1;
        if (empty($paged))
            $paged = 1;
        if ($pages == '') {
            global $pg_query;
            $pages = $pg_query->max_num_pages;
            if (!$pages) {
                $pages = 1;
            }
        }
        
        if (1 != $pages) {
            if ($type == "more") {
                $pagin_type = ' hybgl-pagin-type-more';
            } elseif ($type == "num") {
                $pagin_type = ' hybgl-pagin-type-num';
            } elseif ($type == "classic") {
                $pagin_type = ' hybgl-pagin-type-classic';
            } elseif ($type == "scroll") {
                $pagin_type = ' hybgl-pagin-type-scroll';
            }
            
            $output .= '<div class="hybgl-pagination' . esc_attr($pagin_type) . ' hybgl-clearfix">';
            
                if ($type == "more" or $type == "scroll") {
                    $link_class = 'hybgl-infinite-button';
                
                    if ($paged < $pages && $showitems < $pages) {
                        $output .= '<a class="' . $link_class . '" href="' . esc_attr(get_pagenum_link($paged + 1)) . '"><span class="hybgl-pagin-more-text">' . esc_html__('Show More', 'hybrid-gallery') . '<i class="fa fa-spinner fa-spin hybgl-pagin-more-icon"></i></span></a>';
                    }
                } elseif ($type == "classic") {
                    if ($mode == 'ajax') {
                        $link_tag = 'span';
                    } else {
                        $link_tag = 'a';
                    }

                    if ($pages > 1) {
                        $output .= '<div class="hybgl-pagination-container clearfix">';
                    
                        if ($paged > 1 && $showitems < $pages) {
                            if ($mode == 'ajax') {
                                $link_url = 'data-ajax-url="' . esc_url(get_pagenum_link($paged - 1)) . '"';
                            } else {
                                $link_url = 'href="' . esc_url(get_pagenum_link($paged - 1)) . '"';
                            }

                            $output .= '<' . $link_tag . ' class="hybgl-pagin-link hybgl-pagin-cs-page hybgl-pagin-page-next" ' . $link_url . '>' . esc_html__('Go Next', 'fenomen') . '</' . $link_tag . '>';
                        }
                    
                    if ($paged < $pages && $showitems < $pages) {
                        if ($mode == 'ajax') {
                            $link_url = 'data-ajax-url="' . esc_url(get_pagenum_link($paged + 1)) . '"';
                        } else {
                            $link_url = 'href="' . esc_url(get_pagenum_link($paged + 1)) . '"';
                        }

                        $output .= '<' . $link_tag . ' class="hybgl-pagin-link hybgl-pagin-cs-page hybgl-pagin-page-prev" ' . $link_url . '>' . esc_html__('Go Previous', 'fenomen') . '</' . $link_tag . '>';
                    }
                    
                        $output .= '</div>';
                    }
                } else {
                    $output .= '<div class="hybgl-pagin-num-pages">';
                        if ($mode == 'ajax') {
                            $link_tag = 'span';
                        } else {
                            $link_tag = 'a';
                        }

                        if ($paged > 2 && $paged > $range + 1 && $showitems < $pages) {
                            if ($mode == 'ajax') {
                                $link_url = 'data-ajax-url="' . esc_attr(get_pagenum_link(1)) . '"';
                            } else {
                                $link_url = 'href="' . esc_attr(get_pagenum_link(1)) . '"';
                            }

                            $output .= '<' . $link_tag . ' class="hybgl-pagin-link hybgl-pagin-num-page" ' . $link_url . '><i class="fa fa-angle-left"></i></' . $link_tag . '>';
                        }
                
                        if ($paged > 1 && $showitems < $pages) {
                            if ($mode == 'ajax') {
                                $link_url = 'data-ajax-url="' . esc_attr(get_pagenum_link($paged - 1)) . '"';
                            } else {
                                $link_url = 'href="' . esc_attr(get_pagenum_link($paged - 1)) . '"';
                            }

                            $output .= '<' . $link_tag . ' class="hybgl-pagin-link hybgl-pagin-num-page" ' . $link_url . '><i class="fa fa-angle-double-left"></i></' . $link_tag . '>';
                        }
                
                        for ($i = 1; $i <= $pages; $i++) {
                            if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                                if ($mode == 'ajax') {
                                    $link_url = 'data-ajax-url="' . esc_url(get_pagenum_link($i)) . '"';
                                } else {
                                    $link_url = 'href="' . esc_url(get_pagenum_link($i)) . '"';
                                }

                                $output .= ($paged == $i) ? '<span class="hybgl-pagin-num-page hybgl-pagin-page-current">' . esc_attr($i) . '</span>' : '<' . $link_tag . ' class="hybgl-pagin-link hybgl-pagin-num-page hybgl-pagin-page-inactive" ' . $link_url . '>' . esc_html($i) . '</' . $link_tag . '>';
                            }
                        }
                
                        if ($paged < $pages && $showitems < $pages) {
                            if ($mode == 'ajax') {
                                $link_url = 'data-ajax-url="' . esc_url(get_pagenum_link($paged + 1)) . '"';
                            } else {
                                $link_url = 'href="' . esc_url(get_pagenum_link($paged + 1)) . '"';
                            }

                            $output .= '<' . $link_tag . ' class="hybgl-pagin-link hybgl-pagin-num-page" ' . $link_url . '><i class="fa fa-angle-right"></i></' . $link_tag . '>';
                        }
                
                        if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages) {
                            if ($mode == 'ajax') {
                                $link_url = 'data-ajax-url="' . esc_url(get_pagenum_link($pages)) . '"';
                            } else {
                                $link_url = 'href="' . esc_url(get_pagenum_link($pages)) . '"';
                            }

                            $output .= '<' . $link_tag . ' class="hybgl-pagin-link hybgl-pagin-num-page" ' . $link_url . '><i class="fa fa-angle-double-right"></i></' . $link_tag . '>';
                        }
                
                    $output .= '</div>';
                    $output .= '<div class="hybgl-pagin-num-info"><span>' . sprintf(esc_html__('Page %1$s of %2$s', 'hybrid-gallery'), $paged, $pages) . '</span></div>';
                }
            $output .= '</div>';
        }
        
        return $output;
    }
}