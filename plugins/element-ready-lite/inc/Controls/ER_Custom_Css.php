<?php

namespace Element_Ready\Controls;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Core\Files\CSS\Post;
use Elementor\Core\DynamicTags\Dynamic_CSS;

if (!defined('ABSPATH')) {
	exit;
}

class ER_Custom_Css {
	/**
	 * Add Action hook
	 */
	public function register() {

		if( defined('ELEMENTOR_PRO_VERSION') ){
			return;
		}
	    
        try{
			
            add_action('elementor/element/after_section_end', [__CLASS__, 'add_controls_section'], 10, 3);
            add_action('elementor/element/parse_css', [$this, 'add_post_css'], 10, 2);
            add_action( 'elementor/css-file/post/parse', [ $this, 'add_page_settings_css' ] );
            add_action( 'elementor/editor/after_enqueue_scripts', [$this, 'enqueue_editor_scripts']);
        
		} catch (\Exception $e) {
         
            return;
        }
	
	}

	/**
	 * Replace Pro Custom CSS Control
	 */
	public static function add_controls_section($element, $section_id, $args) {

		if ($section_id == 'section_custom_css_pro') {

			$element->remove_control('section_custom_css_pro');

			$element->start_controls_section(
				'er_section_custom_css',
				[
					'label' => esc_html__( 'Custom CSS (ER)', 'element-ready-lite' ),
					'tab'   => Controls_Manager::TAB_ADVANCED,
				]
			);

			$element->add_control(
				'er_custom_css_title',
				[
					'raw'  => esc_html__( 'Add your own custom CSS here (element ready)', 'element-ready-lite' ),
					'type' => Controls_Manager::RAW_HTML,
				]
			);

			$element->add_control(
				'er_custom_css',
				[
					'type'        => Controls_Manager::CODE,
					'label'       => esc_html__( 'Custom CSS', 'element-ready-lite' ),
					'language'    => 'css',
					'render_type' => 'ui',
					'show_label'  => false,
					'separator'   => 'none',
				]
			);
	

			$element->add_control(
				'er_custom_css_description',
				[
					'raw' => esc_html__( 'Use "selector" to target wrapper element. Examples:<br>selector {color: red;} // For main element<br>selector .child-element {margin: 10px;} // For child element<br>.my-class {text-align: center;} // Or use any custom selector', 'element-ready-lite' ),
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => ' elementor-descriptor',
				]
			);

			$element->end_controls_section();
		}
	}

	/**
	 * @param $post_css Post
	 * @param $element  Element_Base
	 */
	public function add_post_css($post_css, $element) {

		if ($post_css instanceof Dynamic_CSS) {
			return;
		}

		$element_settings = $element->get_settings();

		if (empty($element_settings['er_custom_css'])) {
			return;
		}

		$css = trim($element_settings['er_custom_css']);

		if (empty($css)) {
			return;
		}

		$css = str_replace('selector', $post_css->get_element_unique_selector($element), $css);

		// Add a css comment
		$css = sprintf('/* Start custom CSS for %s, class: %s */', $element->get_name(), $element->get_unique_selector()) . $css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css($css);
	}

	/**
	 * @param $post_css Post
	 */
	public function add_page_settings_css( $post_css ) {

		$document = \Elementor\Plugin::$instance->documents->get( $post_css->get_post_id() );
		$custom_css = $document->get_settings( 'er_custom_css' );
		$custom_css = trim( $custom_css );

		if ( empty( $custom_css ) ) {
			return;
		}

		$custom_css = str_replace( 'selector', $document->get_css_wrapper_selector(), $custom_css );

		// Add a css comment
		$custom_css = '/* Start custom CSS for page-settings */' . $custom_css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css( $custom_css );
	}

	/**
	 * Enqueue Editor Script
	 */
	public function enqueue_editor_scripts() {
		wp_enqueue_script('editor-support-js', esc_url(ELEMENT_READY_ROOT_JS . 'editor-support.js'), array('jquery',''), '', true);
	}

}
