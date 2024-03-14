<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

    while ($query->have_posts()) : $query->the_post();

	    global $post;

        // Get data
	    $post_id = get_the_ID();
	    $title   = get_the_title($post_id);
	    $link    = get_the_permalink($post_id);
	    $class_popup = ( $ymc_popup_status === 'off' ) ? '' : 'ymc-popup';
	    $post_date_format = apply_filters('ymc_post_date_format_'.$filter_id.'_'.$target_id, 'd, M Y');
		//$image_post = '<img class="dummy-image" src="'. YMC_SMART_FILTER_URL . '/includes/assets/images/dummy-Image.svg" alt="Dummy image">';
	    $image_post = null;

	    if( has_post_thumbnail($post_id) ) {
		    $image_post = get_the_post_thumbnail($post_id, 'full');
        }

	    $content = $post->post_excerpt;
	    if( empty($content) ) {
		    $content = $post->post_content;
	    }

	    $content  = preg_replace('#\[[^\]]+\]#', '', $content);
	    $c_length = apply_filters('ymc_post_excerpt_length_'.$filter_id.'_'.$target_id, 40);
	    $content  = wp_trim_words($content, $c_length);

	    $read_more = apply_filters('ymc_post_read_more_'.$filter_id.'_'.$target_id, __('Read More','ymc-smart-filter'));
	    $target = "target=" . $ymc_link_target . "";

	    $list_categories = '';

	    if( is_array($taxonomy) && count($taxonomy) > 0 ) {

		    foreach ( $taxonomy as $tax ) {

			    $term_list = get_the_terms($post_id, $tax);

			    if( $term_list ) {
				    foreach($term_list as $term_single) {
					    $list_categories .= '<span class="cat-inner">'. esc_html($term_single->name) .'</span>';
				    }
			    }
		    }
	    }


        echo '<article class="ymc-'.esc_attr($post_layout).' post-'.$post_id.' post-item '.esc_attr($class_animation).'">';

		echo '<div class="ymc-col ymc-col-1">';
	    if( !empty($image_post) ) :
	    echo '<figure class="media">'. wp_kses_post($image_post) .'</figure>';
	    endif;
		echo '</div>';

		echo '<div class="ymc-col ymc-col-2">';
	    echo '<header class="title">'. esc_html($title) .'</header>';

	    if( !empty($list_categories) ) :
		    echo '<div class="category">'. wp_kses_post($list_categories) .'</div>';
	    endif;

	    echo '<div class="excerpt">'. wp_kses_post($content) .'</div>';
	    echo '<div class="read-more"><a class="btn btn-read-more '.esc_attr($class_popup).'" '. esc_attr($target) .' data-postid="'.esc_attr($post_id).'" href="'. esc_url($link) .'">'.
	         esc_html($read_more) .'</a></div>';
		echo '</div>';

        echo '</article>';

    endwhile;

