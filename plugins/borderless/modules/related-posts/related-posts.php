<?php

// don't load directly
defined( 'ABSPATH' ) || exit;


/*-----------------------------------------------------------------------------------*/
/*  *.  Borderless Related Posts
/*-----------------------------------------------------------------------------------*/

function related_posts_after_post_content($content){
    
    if ( !is_admin() && is_singular( 'post' ) ) {
        
        global $post;
        $options = get_option('borderless');
        $related_post_background_color = isset( $options['related_post_background_color'] ) ?: '#FFFFFF';
        $related_post_background_color_hover = isset( $options['related_post_background_color_hover'] ) ?: '#F5F5F5';
        $related_post_title = isset( $options['related_post_title'] ) ?: '#3365c3';
        $related_post_title_hover = isset( $options['related_post_title_hover'] ) ?: '#3365c3';
        $related_post_content = isset( $options['related_post_content'] ) ?: '#000';
        $related_post_content_hover = isset( $options['related_post_content_hover'] ) ?: '#000';
        $related_posts_heading = isset( $options['related_posts_heading'] ) ?: 'Recommended For You';
        $related_posts_image = isset( $options['related_posts_image'] ) ?: true;
        $related_posts_excerpt = isset( $options['related_posts_excerpt'] ) ?: true;
        $related_posts_image_resolution = isset( $options['related_posts_image_resolution'] ) ?: 'medium';
        $related_posts_value = isset( $options['related_posts_value'] ) ?: '6';
        
        $content .= '<style type="text/css">
            .borderless-related-post {background-color:'. $related_post_background_color .'; }
            .borderless-related-post:hover {background-color:'. $related_post_background_color_hover .'; }
            .borderless-related-post:hover .borderless-related-post-body-title {color:'. $related_post_title_hover .'; }
            .borderless-related-post:hover .borderless-related-post-body-content {color:'. $related_post_content_hover .'; } 
        </style>';
        
        $content .= '<div class="borderless-related-posts">';
        
        $content .= '<h3 class="borderless-related-posts-title">'.$related_posts_heading.'</h3>';
        
        function get_excerpt(){
            $options = get_option('borderless');
            $related_posts_excerpt_value = isset( $options['related_posts_excerpt_value'] ) ?: '60';
            $excerpt = get_the_content();
            $excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
            $excerpt = strip_shortcodes($excerpt);
            $excerpt = strip_tags($excerpt);
            $excerpt = substr($excerpt, 0, $related_posts_excerpt_value);
            return $excerpt;
        }
        
        $related = get_posts( 
            array( 
                'category__in' => wp_get_post_categories(get_the_ID()), 
                'numberposts' => $related_posts_value, 
                'post__not_in' => array(get_the_ID()) 
                ) 
            );
            
            if( $related )
            
            $content .= '<div class="borderless-related-posts-grid">';
            
            foreach( $related as $post ) {
                
                setup_postdata($post);
                
                if ($related_posts_image == true && has_post_thumbnail()) { 
                    $img = get_the_post_thumbnail( get_the_ID(), $related_posts_image_resolution, array( 'class' => 'borderless-related-post-body-image' ) ); 
                } else {
                    $img = '';
                }
                
                $content .= '<div class="borderless-related-post">';
                $content .= '<a rel="bookmark" href="'.get_the_permalink().'">';
                
                $content .= $img;
                
                $content .= '<div class="borderless-related-post-body">';
                $content .= '<h6 class="borderless-related-post-body-title">'. get_the_title() .'</h6>';
                if ($related_posts_excerpt == true) { 
                    $content .= '<p class="borderless-related-post-body-content">'. get_excerpt() .'</p>'; 
                }
                $content .= '</div>';
                
                $content .= '</a>';
                $content .= '</div>';
            }
            
            $content .= '</div>';
            
            wp_reset_postdata();
            
            return $content;
            
        } else {
            
            return $content;
            
        }
    }
    
    add_filter( "the_content", "related_posts_after_post_content" ); 
    
    ?>