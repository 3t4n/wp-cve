<?php

// dirname that's compatible with PHP 5.6
function wpc_dirname_r($path, $count=1){
    if ($count > 1){
       return dirname(wpc_dirname_r($path, --$count));
    }else{
       return dirname($path);
    }
}

function wpc_course_id_to_url($url, $course_id) {
    if(strpos($url, '?')) {
		$url = $url . '&course_id=' . $course_id;
	} else {
		$url = $url . '?course_id=' . $course_id;
	}

	return $url;
}

function wpc_get_unit($str, $default = '%'){
	if(strpos($str, '%') !== false){
		return '%';
	} elseif(strpos($str, 'px') !== false){
		return 'px';
	} elseif(strpos($str, 'em') !== false){
		return 'em';
	} else {
		return $default;
	}
}

function wpc_get_max_slider_value($str, $default = 100){
	$str = (int) preg_replace("/[^0-9.]/", "", $str);
	if($str >= $default){
		return $str;
	} else {
		return $default;
	}
}

function wpc_esc_unit($str, $default = '%'){
	$unit = $default;
	if(empty($str) || $str == 0) {
		return $str;
	}
	if(strpos($str, '%') !== false){
		$unit = '%';
	} elseif(strpos($str, 'px') !== false){
		$unit = 'px';
	} elseif(strpos($str, 'em') !== false){
		$unit = 'em';
	}
	$str = (int) preg_replace("/[^0-9.]/", "", $str);
	return $str . $unit;
}

function wpc_restrict_content($post_id, $content, $post_name){

    $restriction = get_post_meta( $post_id, 'wpc-lesson-restriction', true );
    $custom_logged_out_message = get_option('wpc_logged_out_message');
    $login_url = wp_login_url( get_permalink() );
    $register_url = wp_registration_url();

    $restricted_message = '<p class="wpc-alert-message wpc-content-restricted wpc-free-account-required">';
    $restricted_message .= !empty($custom_logged_out_message) ? $custom_logged_out_message : '<a href="' . $login_url . '">' . __('Login', 'wp-courses') . ' </a> or <a href="' . $register_url . '">' . __('Register', 'wp-courses') . '</a> to view this ' . $post_name;
    $restricted_message .= '</p>';

    if( $restriction == 'free-account' && !is_user_logged_in() ){
        return wp_kses($restricted_message, 'post');
    } else {
        return $content;
    }

}

/** 
* @param int $post_id The post ID
* @return bool Whether or not someone is allowed to view content based on it's restriction and their logged in, membership and purchase status
*/

function wpc_is_restricted($post_id){

	$restriction = get_post_meta( $post_id, 'wpc-lesson-restriction', true );
	$restricted = false;

	if($restriction === 'free-account' && !is_user_logged_in() ) {
		$restricted = true;
	}

	if($restriction === 'woo-paid' ){

		if(function_exists('wpc_woo_has_bought')) {
			$restricted = wpc_woo_has_bought( $post_id ) === false ? true : false;
		}

	}

	if($restriction === 'membership'){

		if(function_exists('pmpro_hasMembershipLevel')) {

			$page_membership_levels = wpc_pmpro_get_page_levels($post_id);
			$has_membership = pmpro_hasMembershipLevel($page_membership_levels);

			$restricted = $has_membership === true ? false : true;

		}

	}

	return $restricted;

}

function wpc_has_attachments($lesson_id){
    $attachment1 = get_post_meta($lesson_id, 'wpc-media-sections-1', true);
    $attachment2 = get_post_meta($lesson_id, 'wpc-media-sections-2', true);
    $attachment3 = get_post_meta($lesson_id, 'wpc-media-sections-3', true);

    if(wpc_is_restricted($lesson_id) === true){
    	$has_attachments = false;
    } elseif(!empty($attachment1) || !empty($attachment2) || !empty($attachment3)) {
    	$has_attachments = true;
    } else {
    	$has_attachments = false;
    }

    return $has_attachments;
}

function wpc_count_posts($post_type = 'course', $status = array('publish')){

	$args = array(
		'post_type'			=> $post_type,
		'post_status'		=> $status,
		'posts_per_page'	=> -1,
		'paged'				=> false,
	);

	$query = new WP_Query($args);

	return $query->post_count;

}

/** 
* @param int $results_count The total number of results without pagination
* @param int $posts_per_page The number of results to display per page
* @return An array with page numbers
*/

function wpc_get_pages($results_count, $posts_per_page){

	if($results_count === 0){
		return false;
	}

	if($posts_per_page > $results_count){
		return false;
	} else {
		$num_pages = $results_count / $posts_per_page;

		$pages = array();

		for($i = 1; $i < $num_pages + 1; $i++) {
			$pages[] = $i;
		}

		return $pages;
	}

}

/**
 * Slightly modified WP function that renders the layout config to the block wrapper.
 * Needed for rendering Gutenberg blocks when fetched via AJAX because the_content() and get_the_content() do not return certain block inline styles.
 *
 * @param string $block_content Rendered block content.
 * @param array  $block         Block object.
 * @return string Filtered block content and styling.
 */

function wpc_render_layout_support_flag( $block_content, $block ) {
	$block_type     = WP_Block_Type_Registry::get_instance()->get_registered( $block['blockName'] );
	$support_layout = block_has_support( $block_type, array( '__experimentalLayout' ), false );

	if ( ! $support_layout ) {
		return $block_content;
	}

	$block_gap             = wp_get_global_settings( array( 'spacing', 'blockGap' ) );
	$default_layout        = wp_get_global_settings( array( 'layout' ) );
	$has_block_gap_support = isset( $block_gap ) ? null !== $block_gap : false;
	$default_block_layout  = _wp_array_get( $block_type->supports, array( '__experimentalLayout', 'default' ), array() );
	$used_layout           = isset( $block['attrs']['layout'] ) ? $block['attrs']['layout'] : $default_block_layout;
	if ( isset( $used_layout['inherit'] ) && $used_layout['inherit'] ) {
		if ( ! $default_layout ) {
			return $block_content;
		}
		$used_layout = $default_layout;
	}

	$class_name = wp_unique_id( 'wp-container-' );
	$gap_value  = _wp_array_get( $block, array( 'attrs', 'style', 'spacing', 'blockGap' ) );
	// Skip if gap value contains unsupported characters.
	// Regex for CSS value borrowed from `safecss_filter_attr`, and used here
	// because we only want to match against the value, not the CSS attribute.
	if ( is_array( $gap_value ) ) {
		foreach ( $gap_value as $key => $value ) {
			$gap_value[ $key ] = $value && preg_match( '%[\\\(&=}]|/\*%', $value ) ? null : $value;
		}
	} else {
		$gap_value = $gap_value && preg_match( '%[\\\(&=}]|/\*%', $gap_value ) ? null : $gap_value;
	}

	$fallback_gap_value = _wp_array_get( $block_type->supports, array( 'spacing', 'blockGap', '__experimentalDefault' ), '0.5em' );

	// If a block's block.json skips serialization for spacing or spacing.blockGap,
	// don't apply the user-defined value to the styles.
	$should_skip_gap_serialization = wp_should_skip_block_supports_serialization( $block_type, 'spacing', 'blockGap' );
	$style                         = wp_get_layout_style( ".$class_name", $used_layout, $has_block_gap_support, $gap_value, $should_skip_gap_serialization, $fallback_gap_value );
	// This assumes the hook only applies to blocks with a single wrapper.
	// I think this is a reasonable limitation for that particular hook.
	$content = preg_replace(
		'/' . preg_quote( 'class="', '/' ) . '/',
		'class="' . esc_attr( $class_name ) . ' ',
		$block_content,
		1
	);

	return '<style>' . $style . '</style>' . $content;
}

?>