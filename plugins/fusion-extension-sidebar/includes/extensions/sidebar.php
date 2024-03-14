<?php
/**
 * @package Fusion_Extension_Sidebar
 */

/**
 * Sidebar Extension.
 *
 * Function for adding a Sidebar element to the Fusion Engine
 *
 * @since 1.0.0
 */

/**
 * Map Shortcode
 */

add_action('init', 'fsn_init_sidebar', 12);
function fsn_init_sidebar() {

	if (function_exists('fsn_map')) {	
		global $wp_registered_sidebars;
		
		$sidebars_array = array();
		$sidebars_array[''] = __('Choose sidebar.', 'fusion-extension-sidebar');
		if (!empty($wp_registered_sidebars)) {
			foreach($wp_registered_sidebars as $key => $value) {
				$sidebars_array[$key] = $value['name'];
			}
		}
	
		fsn_map(array(
			'name' => __('Sidebar', 'fusion-extension-sidebar'),
			'shortcode_tag' => 'fsn_sidebar',
			'description' => __('Add sidebar. Displays the WordPress Widgets set inside of a sidebar. Sidebars can be customized under Appearance > Widgets in the Dashboard.', 'fusion-extension-sidebar'),
			'icon' => 'view_quilt',
			'params' => array(
				array(
					'type' => 'select',
					'options' => $sidebars_array,
					'param_name' => 'sidebar_id',
					'label' => __('Sidebar', 'fusion-extension-sidebar')
				)
			)
		));
	}
}

/**
 * Output Shortcode
 */

function fsn_sidebar_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'sidebar_id' => ''
	), $atts ) );
	
	if (!empty($sidebar_id)) {
		$output = '<div class="fsn-sidebar '. fsn_style_params_class($atts) .'">';
			ob_start();
			if ( is_active_sidebar($sidebar_id) ) : ?>
				<?php dynamic_sidebar($sidebar_id); ?>
			<?php endif;
			$output .= ob_get_clean();
		$output .= '</div>';
	}
	
	return $output;
}
add_shortcode('fsn_sidebar', 'fsn_sidebar_shortcode');
 
?>