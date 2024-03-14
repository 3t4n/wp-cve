<?php

namespace DynamicContentForElementor\Extensions;

use DynamicContentForElementor\Extensions\Extension_Prototype;
use Elementor\Controls_Manager;
use DynamicContentForElementor\Group_Control_AnimationElement;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Parallax extenstion
 *
 * Adds parallax on widgets and columns
 *
 * @since 0.5.5
 */
class Extension_Animations extends Extension_Prototype {

	/**
	 * Is Common Extension
	 *
	 * Defines if the current extension is common for all element types or not
	 *
	 * @since 0.5.4
	 * @access private
	 *
	 * @var bool
	 */
	protected $is_common = true;

	/**
	 * A list of scripts that the widgets is depended in
	 *
	 * @since 0.5.4
	 **/
	public function get_script_depends() {
		return [
			/*'parallax-element',*/
		];
	}

	/**
	 * The description of the current extension
	 *
	 * @since 0.5.4
	 **/
	public static function get_description() {
		return __( 'Predefined CSS-Animations with keyframe. By Poglie.com' );
	}

	/**
	 * Add common sections
	 *
	 * @since 0.5.4
	 *
	 * @access protected
	 */
	protected function add_common_sections_actions() {

		// Activate sections for widgets
		add_action( 'elementor/element/common/_section_style/after_section_end', function( $element, $args ) {

			$this->add_common_sections( $element, $args );

		}, 10, 2 );

		// Activate sections for columns
		/*add_action( 'elementor/element/column/section_advanced/after_section_end', function( $element, $args ) {

			$this->add_common_sections( $element, $args );

		}, 10, 2 );*/

		// Activate sections for sections
		/*add_action( 'elementor/element/section/section_advanced/after_section_end', function( $element, $args ) {

			$this->add_common_sections( $element, $args );

		}, 10, 2 );*/

	}

	/**
	 * Add Actions
	 *
	 * @since 0.5.5
	 *
	 * @access private
	 */
	private function add_controls( $element, $args ) {

		$element_type = $element->get_type();


		 // se volessi filtrare i campi in base al tipo
		/*if ( $element->get_name() === 'section' ) {

		}*/
		$element->add_group_control(
            Group_Control_AnimationElement::get_type(), [
                'name' => 'animate_image',
                'selector' => '{{WRAPPER}} .elementor-widget-container',
            ]
        );
	}

	/**
	 * Add Actions
	 *
	 * @since 0.5.5
	 *
	 * @access private
	 */
	protected function add_actions() {

		// Activate controls for widgets
		add_action( 'elementor/element/common/section_DynamicContentForElementor_advanced/before_section_end', function( $element, $args ) {

			$this->add_controls( $element, $args );

		}, 10, 2 );

		// Activate controls for columns
		/*add_action( 'elementor/element/column/section_DynamicContentForElementor_advanced/before_section_end', function( $element, $args ) {

			$this->add_controls( $element, $args );

		}, 10, 2 );*/

		// Activate controls for sections
		/*add_action( 'elementor/element/section/section_DynamicContentForElementor_advanced/before_section_end', function( $element, $args ) {

			$this->add_controls( $element, $args );

		}, 10, 2 );*/

	}

}