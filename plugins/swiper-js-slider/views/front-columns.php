<?php 

$post_type = "swiper_js_slides";

add_filter( 'manage_'. $post_type .'_posts_columns', 'sjs_set_carousel_columns' );
add_action( 'manage_'. $post_type .'_posts_custom_column', 'sjs_carousel_custom_column', 10, 2 );

// Set Custom Columns
function sjs_set_carousel_columns( $columns){
	#unset($columns['date']);
	$addColumns = array();
	$addColumns['cb'] = '<input type="checkbox" />';
	$addColumns['image'] = 'Total Slides';
	$addColumns['title'] = 'Sliders';
	$addColumns['shortcode'] = 'Shortcode';
	$addColumns['author'] =  'Author';
	$addColumns['date'] = 'Date';
	return $addColumns;
}

//  Create Custom Columns
function sjs_carousel_custom_column( $column, $post_id ){
	
	global $post;
	$post_id = absint( $post_id );
	$meta_prefix = '_s_s_m_';

    switch( $column ){
        case 'shortcode': ?>
	        <input type="text" readonly="readonly" value='[swiper-js-slider id="<?php echo $post->ID ?>"]' class="large-text code">
			<a href="#" class="copyData">Copy to clipboard</a>
		<?php
		break;
		
		case 'image':
			$ids = get_post_meta( $post_id, $meta_prefix.'gallery_id', true );
			
			if ( ! empty( $ids ) && is_array( $ids ) ) {
				$image = wp_get_attachment_image_src($ids[0]);
				$total = count($ids);
				echo '<img src="' . esc_url($image[0]) . '" width="75" height="50">';
				echo '<span class="imgCount">';
				echo ($total == 1) ? $total . " Slide" : $total . " Slides";
				echo '</span>';
			}
		break;
    }
}