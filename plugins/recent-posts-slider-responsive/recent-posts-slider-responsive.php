<?php
/**
 * Plugin Name: Recent Posts Slider Responsive
 * Plugin URI: http://anitamourya.com
 * Description: This plugin displays recent post slider responsive.
 * Version: 1.0.1
 * Author: Anita Mourya
 * Author URI: http://anitamourya.com
 * License: GPL2
 */

register_activation_hook( __FILE__, 'rpf_activate' );
add_action('admin_menu', 'rpf_admin_actions');
add_action('admin_print_styles', 'rpf_admin_style');
add_action('wp_print_styles', 'rpf_add_style');
add_action('wp_head', 'rpf_add_custom_style');
add_action('init', 'rpf_add_script');
add_shortcode('rpf', 'rpf_display_slider_shortcode');
add_action('widgets_init', create_function('', 'return register_widget("RpfWidget");'));

function rpf_activate() {
	$post_per_slide = get_option('rpf_post_per_slide');
	if ( empty($post_per_slide) ) {
		$post_per_slide = '2';
		update_option('rpf_post_per_slide', $post_per_slide);
	}
	
	$total_posts = get_option('rpf_total_posts');
	if ( empty($total_posts) ) {
		$total_posts = '6';
		update_option('rpf_total_posts', $total_posts);
	}
	
	$slider_image_size = get_option('rpf_slider_image_size');
	if ( empty($slider_image_size) ) {
		$slider_image_size = '1';
		update_option('rpf_slider_image_size', $slider_image_size);
	}
}

function rpf_admin_actions() {
    add_options_page(__('Recent Posts Slider Responsive', 'rpf'), __('Recent Posts Slider Responsive', 'rpf'), 'manage_options', 'recent-posts-slider-responsive', 'rpf_admin');
}

function rpf_admin() {
    if ( !current_user_can('manage_options') )
    	wp_die( __('You do not have sufficient permissions to access this page.','rpf') );
	include('recent-posts-slider-responsive-admin.php');
}

function rpf_admin_style() {	
	wp_enqueue_style('rpf-admin-style', WP_PLUGIN_URL.'/recent-posts-slider-responsive/css/rpf-admin-style.css');
}

function rpf_add_style() {
	wp_enqueue_style('rpf-style', WP_PLUGIN_URL.'/recent-posts-slider-responsive/css/style.css');
}

function rpf_add_custom_style() {
	echo "<style type=\"text/css\" media=\"screen\">" . stripslashes(get_option('rpf_custom_css')) . "</style>";
}

function rpf_add_script() {
	if ( !is_admin() ){
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'rpf-flexisel', WP_PLUGIN_URL.'/recent-posts-slider-responsive/js/jquery.flexisel.js');
	}	
	if ( is_admin() ){
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_style( 'jquery-ui', WP_PLUGIN_URL.'/recent-posts-slider-responsive/css/jquery-ui.css' );
	}
}

function rpf_display_slider_shortcode($rpf_atts) {
	
	extract(shortcode_atts(array(
		'category_ids' => '',
		'total_posts' => '',
		'post_per_slide' => '',
		'post_include_ids' => '',
		'post_exclude_ids' => '',
		'slider_id' => '',
	), $rpf_atts));
	
	return rpf_display_slider( $category_ids, $total_posts, $post_per_slide, $post_include_ids, $post_exclude_ids, $slider_id);
}

function rpf_display_slider( $category_ids=null, $total_posts=null, $post_per_slide=null, $post_include_ids=null, $post_exclude_ids=null, $slider_id=1 ) {
	if( empty($post_per_slide) ) $post_per_slide = get_option('rpf_post_per_slide');
	if( empty($total_posts) ) $total_posts = get_option('rpf_total_posts');
	$slider_image_size = get_option('rpf_slider_image_size');
	if( empty($category_ids) ) $category_ids = get_option('rpf_category_ids');
	if( empty($post_include_ids) ) $post_include_ids = get_option('rpf_post_include_ids');
	if( empty($post_exclude_ids) ) $post_exclude_ids = get_option('rpf_post_exclude_ids');
	$post_title_color = get_option('rpf_post_title_color');
	$post_title_bg_color = get_option('rpf_post_title_bg_color');
	$slider_speed = get_option('rpf_slider_speed');
	$rps_automatic = get_option('rps_automatic');
	
	if ( empty($slider_speed) ) {
		$slider_speed = 3000;
	}else{
		$slider_speed = $slider_speed * 1000;
	}

	if ( empty($post_title_color) ){
		$post_title_color = "#fff";
	}

	$post_title_bg_color_js = "";
	if ( !empty($post_title_bg_color) ){
		$post_title_bg_color_js = $post_title_bg_color;
	}

	if ( empty($rps_automatic) ){
		$rps_automatic = "true";
	}

	$post_details = NULL;
	$args = array(
			'numberposts'     => $total_posts,
			'offset'          => 0,
			'category'        => $category_ids,
			'orderby'         => 'post_date',
			'order'           => 'DESC',
			'include'         => $post_include_ids,
			'exclude'         => $post_exclude_ids,
			'post_type'       => 'post',
			'post_status'     => 'publish' );
	$recent_posts = get_posts( $args );
	
	if ( count($recent_posts)< $total_posts ) {
		$total_posts	= count($recent_posts);
	}
	
	foreach ( $recent_posts as $key=>$val ) {
		$post_details[$key]['post_title'] = $val->post_title;
		$post_details[$key]['post_permalink'] = get_permalink($val->ID);
		$post_details[$key]['post_id'] = $val->ID;
	}
	
	$output .='<script type="text/javascript">
	$j = jQuery.noConflict();
	$j(document).ready(function() {';

	$output .= '$j("#flexiselDemo'.$slider_id.'").flexisel({
			visibleItems: '.$post_per_slide.',
			animationSpeed: 1000,
			autoPlay: '.$rps_automatic.',
			autoPlaySpeed: '.$slider_speed.',
			pauseOnHover: true,
			enableResponsiveBreakpoints: true,
	    	responsiveBreakpoints: {
	    		portrait: { 
	    			changePoint:480,
	    			visibleItems: 1
	    		}, 
	    		landscape: { 
	    			changePoint:640,
	    			visibleItems: 2
	    		},
	    		tablet: { 
	    			changePoint:768,
	    			visibleItems: 3
	    		}
	    	}
	    });
	});
	</script>';

	$output .='<style>.rpf-title a{
		background: #'.$post_title_bg_color.';
		color: #'.$post_title_color.';
	}
	.rpf-slider img{
		background: #'.$post_title_color.';
	}</style>';

	$output .= '<ul id="flexiselDemo'.$slider_id.'">';
			$p=0;
			for ( $i = 1; $i <= $total_posts; $i+=$post_per_slide ) {
				for ( $j = 1; $j <= $post_per_slide; $j++ ) {
					if ( $slider_image_size == 1 ){
						$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_details[$p]['post_id']), 'thumbnail' );
					}elseif ( $slider_image_size == 2 ){
						$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_details[$p]['post_id']), 'medium' );
					}elseif ( $slider_image_size == 3 ){
						$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_details[$p]['post_id']), 'large' );
					}elseif ( $slider_image_size == 4 ){
						$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_details[$p]['post_id']), 'full' );
					}
					$url = $thumb['0'];
					$output .= '<li class="main-div">';
						$output .= '<div class="rpf-main-div">';
						if(!empty($url)){
							$output .= '<div class="rpf-slider"><a href="'.$post_details[$p]['post_permalink'].'"><img src="'.$url.'" alt="'.__($post_details[$p]['post_title'], 'rpf').'" /></a></div>';
						}
						$output .= '<h3 class="rpf-title"><a href="'.$post_details[$p]['post_permalink'].'">'.__($post_details[$p]['post_title'], 'rpf').'</a></h3>';
						$output .= '</div>';
					$p++;
					if ( $p == $total_posts )
						break;
				}
				$output .= '</li>';
			}
			$output .= '</ul>';
		return $output;
	}

class RpfWidget extends WP_Widget {
    function RpfWidget() {
        parent::WP_Widget(false, $name = __('Recent Posts Slider Responsive','rpf'), array( 'description' => __( 'Get your responsive recent posts slider','rpf') ));
    }

    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        $category_ids = apply_filters('widget_title', $instance['category_ids']);
        $total_posts = apply_filters('widget_title', $instance['total_posts']);
        $post_include_ids = apply_filters('widget_title', $instance['post_include_ids']);
        $post_exclude_ids = apply_filters('widget_title', $instance['post_exclude_ids']);
        $slider_id = apply_filters('widget_title', $instance['slider_id']);
		echo $before_widget;
        if ( $title )
			echo $before_title . $title . $after_title;
		if (function_exists('rpf_display_slider'))
			echo rpf_display_slider($category_ids, $total_posts, $post_per_slide, $post_include_ids, $post_exclude_ids, $slider_id);
		echo $after_widget;
    }

    function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['category_ids'] = strip_tags($new_instance['category_ids']);
		$instance['total_posts'] = strip_tags($new_instance['total_posts']);
		$instance['post_include_ids'] = strip_tags($new_instance['post_include_ids']);
		$instance['post_exclude_ids'] = strip_tags($new_instance['post_exclude_ids']);
		$instance['slider_id'] = strip_tags($new_instance['slider_id']);
        return $instance;
    }

    function form($instance) {
        $title = esc_attr($instance['title']);
        $category_ids = esc_attr($instance['category_ids']);
        $total_posts = esc_attr($instance['total_posts']);
        $post_include_ids = esc_attr($instance['post_include_ids']);
        $post_exclude_ids = esc_attr($instance['post_exclude_ids']);
        $slider_id = esc_attr($instance['slider_id']);
    ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','rpf'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('category_ids'); ?>"><?php _e('Category Ids (Comma seperated):','rpf'); ?> <input class="widefat" id="<?php echo $this->get_field_id('category_ids'); ?>" name="<?php echo $this->get_field_name('category_ids'); ?>" type="text" value="<?php echo $category_ids; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('total_posts'); ?>"><?php _e('Total Posts:','rpf'); ?> <input class="widefat" id="<?php echo $this->get_field_id('total_posts'); ?>" name="<?php echo $this->get_field_name('total_posts'); ?>" type="text" value="<?php echo $total_posts; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('post_include_ids'); ?>"><?php _e('Posts to include (Comma seperated):','rpf'); ?> <input class="widefat" id="<?php echo $this->get_field_id('post_include_ids'); ?>" name="<?php echo $this->get_field_name('post_include_ids'); ?>" type="text" value="<?php echo $post_include_ids; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('post_exclude_ids'); ?>"><?php _e('Posts to exclude (Comma seperated):','rpf'); ?> <input class="widefat" id="<?php echo $this->get_field_id('post_exclude_ids'); ?>" name="<?php echo $this->get_field_name('post_exclude_ids'); ?>" type="text" value="<?php echo $post_exclude_ids; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('post_exclude_ids'); ?>"><?php _e('Slider CSS ID:','rpf'); ?> <input class="widefat" id="<?php echo $this->get_field_id('slider_id'); ?>" name="<?php echo $this->get_field_name('slider_id'); ?>" type="text" value="<?php echo $slider_id; ?>" /></label></p>
    <?php 
    }
}
?>