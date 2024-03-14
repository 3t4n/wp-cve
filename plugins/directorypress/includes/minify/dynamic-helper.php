<?php
function directorypress_head_hook(){
	global $directorypress_shortcode_order, $is_header_shortcode_added;

}

add_action('wp_head', 'directorypress_head_hook', 1);



/**
 * Collect Shortcode dynamic styles and using javascript inject them to <head>
 */
if (!function_exists('directorypress_dynamic_styles')) {
    function directorypress_dynamic_styles() {
	global $directorypress_app_dynamic_styles;
	
	$post_id = directorypress_global_get_post_id();

	$saved_styles = get_post_meta($post_id, '_dynamic_styles', true);
	
	$saved_styles_build = get_post_meta($post_id, '_directorypress_options_build', true);
	$theme_option_build = get_option(DIRECTORYPRESS_OPTIONS_BUILD);

	$styles =  unserialize(base64_decode(get_post_meta($post_id, '_dynamic_styles', true)));

	if (empty($styles)) {
		$css = '';
		if(is_array($directorypress_app_dynamic_styles) && !empty($directorypress_app_dynamic_styles)) {
	        foreach ($directorypress_app_dynamic_styles as $style) {
	            $css .= $style['inject'];
	        }
    	}
        $css = preg_replace('/\r|\n|\t/', '', $css);
		if(!empty($css)){
			echo "<style>" . $css . "</style>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
    }

	if(empty($saved_styles) || $saved_styles_build != $theme_option_build) {
		update_post_meta($post_id, '_dynamic_styles', base64_encode(serialize(($directorypress_app_dynamic_styles))));
		update_post_meta($post_id, '_directorypress_options_build', $theme_option_build);
	}
    }
    
    //Apply custom styles before runing other javascripts as they might be based on those styles as well. So setting priority as one!
    add_action('wp_footer', 'directorypress_dynamic_styles', 1);
}