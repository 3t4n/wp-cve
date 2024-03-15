<?php
class MPFE_Elt_Template_Shortcode {
	public function __construct() {
		add_filter('manage_elementor_library_posts_columns', array($this, 'mpfe_add_shortcode_column'));
		add_action('manage_elementor_library_posts_custom_column' , array($this, 'mpfe_fill_shortcode_column'), 10, 2);
		add_shortcode('mpfe_shortcode', array($this, 'mpfe_elt_templ_shortcode'));
	}	

	public function mpfe_add_shortcode_column($columns) {
    	$columns['mpfe_shortcode'] = __('Shortcode', 'music-player-for-elementor');
	
		return $columns;
	}

	public function mpfe_fill_shortcode_column($column, $post_id) {
	    switch ($column) {
	        case 'mpfe_shortcode' :
	            echo '[mpfe_shortcode id="'.$post_id.'"]';
	        break;
	    }
	}

	function mpfe_elt_templ_shortcode($atts) {
		if (!class_exists("\\Elementor\\Plugin")) {
			return '';
		}

	    ob_start();

        $elementorInstance = \Elementor\Plugin::instance();
        $templateContent = $elementorInstance->frontend->get_builder_content($atts['id']);
        echo apply_filters('the_content', $templateContent);

	    return ob_get_clean();
	}	
}

$mpfe_elt_templ_shortcode = new MPFE_Elt_Template_Shortcode();