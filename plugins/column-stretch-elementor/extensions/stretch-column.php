<?php

namespace ElementorStretchColumn\Extensions;

use ElementorStretchColumn\Base\Extension_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Sticky Extension
 *
 * Adds sticky on scroll capability to widgets and sections
 *
 * @since 0.1.0
 */
class Extension_Stretch_Column extends Extension_Base {

	/**
	 * Is Common Extension
	 *
	 * Defines if the current extension is common for all element types or not
	 *
	 * @since 1.8.0
	 * @access protected
	 *
	 * @var bool
	 */
	protected $is_common = true;

	/**
	 * A list of scripts that the widgets is depended in
	 *
	 * @since 1.8.0
	 **/
	public function get_script_depends() {
		return [
			'elementor-sc-js',
		];
	}

	/**
	 * The description of the current extension
	 *
	 * @since 1.8.0
	 **/
	public static function get_description() {

		$message = '<div class="notice notice-warning inline"><p>';

		$message .= __( 'Adds an option to strecth the column to left or right. Can be found under Advanced &rarr; Advanced &rarr; Stretch Column.', 'column-stretch-elementor' );
		
		$message .= '</p></div>';

		return $message;
	}

	/**
	 * Add common sections
	 *
	 * @since 1.8.0
	 *
	 * @access protected
	 */
	protected function add_common_sections_actions() {
		
		add_action( 'elementor/element/column/section_advanced/after_section_end', function( $element, $args ) {

			$this->add_common_sections( $element, $args );

		}, 10, 2 );
		
		add_action( 'elementor/element/container/section_layout/after_section_end', function( $element, $args ) {

			$this->add_common_sections( $element, $args );

		}, 10, 2 );

	}

	/**
	 * Add Controls
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	private function add_controls( $element, $args ) {

		$element->add_control( 'stretch_column', [
			'label'					=> __( 'Stretch Column', 'column-stretch-elementor' ),
			'description'			=> '',
			'type'					=> Controls_Manager::SWITCHER,
			'default'				=> '',
			'label_on'				=> __( 'Yes', 'column-stretch-elementor' ),
			'label_off'				=> __( 'No', 'column-stretch-elementor' ),
			'return_value'			=> 'yes',
		]);

		$element->add_control(
			'stretch_column_direction', [
				'label'				=> __( 'Stretch Direction', 'column-stretch-elementor' ),
				'type'				=> Controls_Manager::SELECT,
				'default'			=> '',
				'options'			=> [
					''		=> __( 'None', 'column-stretch-elementor' ),
					'left'	=> __( 'Left', 'column-stretch-elementor' ),
					'right'	=> __( 'Right', 'column-stretch-elementor' ),
				],
				'prefix_class'		=> 'elementor-stretch-column-',
				'render_type'		=> 'template',
				'condition'			=> [
					'stretch_column!' => '',
				],
			]
		);

		$element->add_control(
			'stretch_column_info', [
				'label'				=> '',
				'type'				=> Controls_Manager::RAW_HTML,
				'raw'				=> __( 'Please refresh the page to check the effect.', 'column-stretch-elementor' ),
				'content_classes'	=> 'elementor-sc-info',
				'condition'			=> [
					'stretch_column!' => '',
					'stretch_column_direction!' => '',
				],
			]
		);

	}

	/**
	 * Add Actions
	 *
	 * @since 0.1.0
	 *
	 * @access protected
	 */
	protected function add_actions() {

		// Activate controls for columns
		add_action( 'elementor/element/column/section_column_stretch_elementor_controls/before_section_end', function( $element, $args ) {

			$this->add_controls( $element, $args );

		}, 10, 2 );

		// Activate controls for container
		add_action( 'elementor/element/container/section_column_stretch_elementor_controls/before_section_end', function( $element, $args ) {

			$this->add_controls( $element, $args );

		}, 10, 2 );
	}

}