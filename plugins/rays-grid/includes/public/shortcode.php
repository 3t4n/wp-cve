<?php
// if called directly, abort.
if (!defined('WPINC')) { die; }

function raysgrid_Shortcode( $alias ) {
    
    global $wpdb, $post;
    $configs    = new raysgrid_Config();
    $base       = new raysgrid_Base();
    $confArr    = $configs->rsgd_configs();
    $result     = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".RSGD_TBL." WHERE alias=%s", $alias), ARRAY_A);
    $output     = '';
    
    if ( $result ) {
    
        foreach ($confArr as $value){
            if ($result && $value['name'] !== 'oldalias'){
				${$value['name']} = $result[0][$value['name']];
            }
        }
        
        $base->rsgd_colors($main_color);
        
         $shonums = $witbg = $mainbg = '';
        
        $count_post  = [];
        $rt          = ( $rtl == '1' ) ? ' rtl' : '';
        $post_type   = explode(',', $post_type);
        $taxs        = explode(',', $select_taxonomy);
        $ratio_x     = explode('|', $img_ratio);
        $ratio_y     = substr($img_ratio, strpos($img_ratio, "|") + 1);
        
        $class = 'raysgrid';
        $class .= ( $rtl == '1' ) ? ' rtl' : '';
        $class .= ( $grid_layout !== 'onecolumn' ) ? ' '.esc_attr($choose_skin) : '';
        $class .= ( $grid_layout !== 'onecolumn' && $grid_layout !== 'slider' ) ? ' isoto p-' . esc_attr($number_of_columns) . '-cols ' . esc_attr($grid_layout) : '';
        $class .= ( $grid_layout == 'onecolumn' ) ? '  p-1-col' : '';
        $class .= ( $grid_layout == 'slider' ) ? ' rsgd_' . esc_attr($slider_type) . '-slider' : '';
        
        $contClass = 'portfolio-container';
        $contClass .= ( $extra_class !== '' ) ? ' '.esc_attr($extra_class) : "";
        $contClass .= ( $pagination_type == 'smart' ) ? ' smart_pg' : "";
        
        $datacols       = ( $grid_layout !== 'onecolumn' && $grid_layout !== 'slider' ) ? ' data-cols="' . esc_attr($number_of_columns) . '"' : '';
        $slider_atts    = ($grid_layout == 'slider') ? 'data-slidesnum="' . esc_attr($slide_to_show) . '" data-scamount="' . esc_attr($slide_to_scroll) . '" 
        data-fade="' . esc_attr($fade) . '" data-speed="' . esc_attr($slide_speed) . '" data-arrows="' . esc_attr($show_arrows) . '" data-infinite="' . esc_attr($infinite) . '" 
        data-dots="' . esc_attr($show_bullets) . '" data-auto="' . esc_attr($auto_play) . '"' : "";
        
        $itmClass = 'portfolio-item ';
                
        if ( $alias == $alias ) {
            $output .= '<div class="rsgd_container">';
            if ( $maximum_entries !== 0 ) {
                
                if ( $grid_layout !== 'slider' && $grid_layout !== 'onecolumn' ) {                
                    
                    // Grids pre-loader...
                    if( $preloader !== '-1' ){
                        $output .= '<div class="loader-port">';
                            $output .= '<div class="cp-spinner ' . esc_attr( $preloader ) . '">';
                            $output .= '</div>';
                        $output .= '</div>';    
                    }

                    $output .= '<div class=" '.esc_attr( $contClass ).'">';
                }

                // Nav filter...
                if ( $taxs && $nav_filter !== 'none' && $grid_layout !== 'slider' && $grid_layout !== 'onecolumn' ) {
                    $filselect = ( $nav_layout == 'dropdown' ) ? ' filter_select' : '';
                    $output .= '<div class="filter-by ' . esc_attr( $nav_filter . $filselect . $rt ) . '">';
                        if( $nav_layout == 'inline' ){
                            $output .= '<ul id="filters">';
                            if ( $show_all == '1' ) {
                                $output .= '<li class="selected"><a href="#" class="hov_eff filter" data-filter="*"><span>' . esc_html($all_text) . '</span></a></li>';
                            }
                                                    
                            foreach ( $taxs as $tt ){
                                $tar = explode( '||',$tt );
                                
                                if( !empty ( $tt ) ){
                                    if( $num_style == 'inline' ){
                                        $inl = 'inline';    
                                    } else {
                                        $inl = 'popup';    
                                    }
                                    if( $els_num == '1' ){
                                        $shonums = '<b class="'.esc_attr( $inl ).'">' . esc_attr( $tar[3] ) . '</b>';    
                                    }
                                    $output .= '<li><a href="#" class="hov_eff filter" data-filter=".' . esc_attr( $tar[1] ) . '"><span>' . esc_attr( $tar[2] ) . $shonums . '</span></a></li>';    
                                }
                                
                            }
                            
                            $output .= '</ul>';
                        } else {
                            $output .= '<select id="filters">';
                            if ($show_all == '1') {
                                $output .= '<option value="*" selected="selected">' . esc_html( $all_text ) . '</option>';
                            }
                                                    
                            foreach ($taxs as $tt){   
                                $tar = explode('||',$tt);
                                $output .= '<option value=".' . esc_attr( $tar[1] ) . '">' . esc_html( $tar[2] ) . ' ['. esc_html( $tar[3] ) . ']</option>';
                            }
                            
                            $output .= '</select>';
                        }
                    $output .= '</div>';
                }
                
                $mx_rows = $items_per_page * $number_of_columns;           

                if ( $pagination_type == 'enable' && ( $grid_layout == 'grid' || $grid_layout == 'masonry' ) ) {
                    if( $maximum_entries == '-1' ){
                        $ppp = $mx_rows;
                    } else {
                        if( $mx_rows >= $maximum_entries ){
                            $ppp = $maximum_entries;   
                        } else {
                            $ppp = $mx_rows;  
                        }
                    }
                } else if ( $grid_layout == 'slider' ) {
                    $ppp = $maximum_entries; 
                } else {
                    $ppp = $items_start;
                }
                                                        
                $output .= '<div class="'.esc_attr($class).'" '.$datacols . $slider_atts . ' data-spacing="' . esc_attr($item_spacing) . '" id="raysgrid_' . esc_attr($id) . '" data-layout="' . esc_attr($grid_layout) . '" data-num="' . esc_attr($ppp) . '">';

                if ( get_query_var('page') > 1 ) {
                    $paged = get_query_var('page');
                } elseif ( get_query_var('paged') > 1 ) {
                    $paged = get_query_var( 'paged' );
                } else {
                    $paged = 1;
                }                                

                wp_reset_query();                    
                $custom_args = [
                    'post_type'                 => $post_type,
                    'tax_query'                 => array(),
                    'posts_per_page'            => $ppp,
                    'orderby'                   => "$order_by",
                    'order'                     => "$order_type",
                    'update_post_term_cache'    => false,
                    'update_post_meta_cache'    => false,
                    'paged'                     => $paged,
                    'post_status'               => 'publish',
                    'ignore_sticky_posts'       => true,
                ];
                
                $custom_args['tax_query'] = [
                    'relation' => 'OR',
                ];
                
                foreach ( $taxs as $tx ) {
                    $tt = explode('||',$tx);
                    if ( !empty ( $tx ) ) {
                        $tax = $tt[0];
                        $ter = $tt[1];
                        $custom_args['tax_query'][]=  [
                            'taxonomy' => $tax,
                            'terms' => $ter,
                            'field' => 'slug',
                            'include_children' => true,
                            'operator' => 'IN'
                        ];
                    }
                    
                }    
                
                $query = new WP_Query( $custom_args );
                
                if ( $choose_skin !== 'ivy' ) {
                    $witbg  = ' rsgd_white-bg';
                    $mainbg = ' rsgd_main-bg';
                }
                
                $arr = array();

                foreach( $taxs as $txx ){
                    $arr[] = explode('||',$txx);
                }

                for ( $i = 0; $i < sizeof($arr); $i++ ) {
                    
                    $taxo = $arr[$i][0];                
                    
                    if ( $query->have_posts() ) {
                        
                        while ( $query->have_posts() ) { 
                            
                            $query->the_post();
                            $terms = get_the_terms( get_the_ID(), $taxo );
                            
                            if ( $terms && ! is_wp_error( $terms ) ) {
                                if ( !in_array( $post->ID, $count_post ) ) {
                                    
                                    array_push( $count_post, $post->ID );
                                    $feat_image = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );

                                    $termArr = $termTax = [];

                                    foreach ( $terms as $term ) {
                                        $term_link = get_term_link( $term );
                                        $termArr[] = $term->slug;
                                        $termTax[] = '<a href="' . esc_url( $term_link ) . '">' . $term->name . '</a>';
                                    }

                                    $output .= '<div class="' . esc_attr($itmClass) . implode ( ' ', $termArr ) . '" data-ratio-x="'.esc_attr( $ratio_x[0] ).'" data-ratio-y="'.esc_attr( $ratio_y ).'">';
                                    if ($grid_layout == 'onecolumn') {
                                        if (has_post_thumbnail()) {
                                            $output .= '<div class="img-holder">';
                                                $output .= '<a href="' . get_the_permalink() . '">';
                                                    $output .= get_the_post_thumbnail( null, $image_source, '' );
                                                $output .= '</a>';
                                            $output .= '</div>';
                                        } else {
                                            $output .= '<div class="img-holder">';
                                                $output .= rsgd_post_media( get_the_content() );
                                            $output .= '</div>';
                                        }
                                        $output .= '<div class="name-holder">';
                                            $output .= '<h4><a class="main-color" href="' . get_the_permalink() . '">';
                                                if ( $show_title == '1' ) {
                                                    $output .= get_the_title();
                                                }
                                            $output .= '</a></h4>';
                                            $output .= '<div class="description">';
                                                if ( has_excerpt() ) {
                                                    $output .= get_the_excerpt();
                                                } else {
                                                    $content = get_the_content( '', false, '' );
                                                    $output .= apply_filters( 'the_content', $content );
                                                }
                                            $output .='</div>';
                                            $output .= '<div class="meta">';
                                                $output .= '<ul class="list">';
                                                    
                                                    if ($show_categories == '1') {
                                                        $output .= '<li><i class="fa fa-folder-open-o main-color"></i> <strong>' . esc_html__( 'Categories', 'raysgrid' ) . ': </strong>';
                                                            $output .= implode ( ' , ', $termTax );
                                                        $output .= '</li>';
                                                    }
                                                    
                                                    $output .= '<li><i class="fa fa-user main-color"></i> <strong>' . esc_html__('By', 'raysgrid' ) . ' :</strong> 
                                                    <a href="' . esc_url( get_author_posts_url( get_the_author_meta('ID') ) ) . '">' . get_the_author_meta( 'display_name' ) . '</a></li>';
                                                    
                                                    $output .= '<li><i class="fa fa-clock-o main-color"></i> <strong>' . esc_html__( 'Created on', 'raysgrid' ) . ': </strong> ' . get_the_date() . '</li>';
                                                    
                                                $output .= '</ul>';
                                            $output .= '</div>';
                                        $output .= '</div>';

                                    } else {
                                        
                                        $output .= '<div class="port-container">';
                                            
                                            if ( has_post_thumbnail() ) {
                                                $output .= '<div class="port-img">';
                                                    $output .= get_the_post_thumbnail( null, $image_source, '' );
                                                $output .= '</div>';
                                            } else {
                                                $output .= '<div class="media-cont">';
                                                    $output .= rsgd_post_media( get_the_content() );
                                                $output .= '</div>';    
                                            }
                                            
                                            $output .= '<div class="icon-links">';
                                                if ($show_link_to_post == '1') {
                                                    $output .= '<a href="' . esc_url(get_the_permalink()) . '" class="rsgd_link' . esc_attr( $witbg ) . '"><i class="fa fa-link"></i></a>';
                                                }
                                                if ($show_zoom_image == '1') {
                                                    $output .= '<a href="' . esc_url($feat_image) . '" class="rsgd_zoom' . esc_attr( $mainbg ) . '" title="' . get_the_title() . '"><i class="fa fa-search"></i></a>';
                                                }
                                            $output .= '</div>';    
                                                                                    
                                            $output .= '<div class="port-captions">';
                                                if ($show_title == '1') {
                                                    $output .= '<h4><a href="' . get_the_permalink() . '">';
                                                        $output .= get_the_title();
                                                    $output .= '</a></h4>';
                                                }
                                                
                                                if ($show_categories == '1') {
                                                    $output .= '<p class="description">';
                                                        $output .= implode ( ' , ', $termTax );
                                                    $output .= '</p>';
                                                }
                                                
                                                if ($show_excerpt == '1') {
                                                    $output .= '<div class="port-excerpt">';
                                                        $output .= rsgd_summary( 20 );
                                                    $output .= '</div>';
                                                }
                                            $output .= '</div>';
                                        $output .= '</div>';
                                        
                                    }
                                    
                                    $output .= '</div>';
                                    
                                }
                            }
                            wp_reset_query();
                        }  
                    }
                
                }    
                
                $output .= '</div>';

                if ( $grid_layout !== 'slider' && $pagination_type == 'smart' ) {
                    $num    = $query->post_count;
                    $mx     = $num * $query->max_num_pages;
                    $totals = $mx - $items_start; 
                    if ($pagination_style == 'loadmore') {

                        $output .='<div class="rsgd_load_more ' . esc_attr($pagination_alignment) . '">';
                            $output .='<a href="#" class="rsgd_load_more_btn">' . esc_attr($load_more_button_text) . '<span class="rsgd_pager_load"><i class="icmon-spinner9"></i></span></a>';
                            $output .= '<div class="hidden pgnum">' . $query->max_num_pages . '</div>';
                        $output .='</div>';
                        
                    } else if ($pagination_style == 'infinite') {
                        
                        $output .='<div class="rsgd_pager ' . esc_attr($pagination_alignment) . '">';
                        $output .= '<div class="hidden pgnum">' . $query->max_num_pages . '</div>';
                            $output .='<span class="rsgd_load_more_scrl" data-num="2">' . esc_html__('Loading More ','raysgrid') . '<span class="rem_items">'. $totals .'</span>'. esc_html__(' Items','raysgrid') .'
                            <span class="rsgd_pager_load"><i class="icmon-spinner9"></i></span></span>';
                        $output .= '</div>';
                        
                    }
                }

                $output .= ($grid_layout !== 'slider' && $grid_layout !== 'onecolumn') ? '</div>' : '';
                
            } else {
                $output .= '<div class="alert alert-warning t-center m-b-0">' . esc_html__('No Posts To Show!!', 'raysgrid') . '</div>';
            }
            $output .= '</div>';
            wp_reset_query();
            
        } else {
            $output .= '<div class="alert alert-danger t-center m-b-0">' . esc_html__('No grid with alias ( ' . esc_attr($alias) . ' ) were found!, Please Create it first.', 'raysgrid' ) . '</div>';
        }
        
    } else {
        $output .= '<div class="alert alert-danger t-center m-b-0">' . esc_html__('No grid with alias ( ' . esc_attr($alias) . ' ) were found!, Please Create it first.', 'raysgrid' ) . '</div>';
    }

    return $output;
}
