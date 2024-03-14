<?php
/**
 * Text stroke control class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Controls;

use Elementor\Group_Control_Base;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

class Group_Control_Text_Stroke extends Group_Control_Base {

	/**
	 * Fields.
	 *
	 * Holds all the background control fields.
	 *
	 * @access protected
	 * @static
	 *
	 * @var array Background control fields.
	 */
	protected static $fields;

	/**
	 * Get background control type.
	 *
	 * Retrieve the control type, in this case `skt_addons_elementor_text_color`.
	 *
	 * @since 1.0
	 * @access public
	 * @static
	 *
	 * @return string Control type.
	 */
	public static function get_type() {
		return 'skt-text-stroke';
	}

	/**
	 * Init fields.
	 *
	 * Initialize background control fields.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return array Control fields.
	 */
	public function init_fields() {
		$fields = [];

		$fields['color'] = [
			'label'       => _x( 'Stroke Color', 'Text Stroke', 'skt-addons-elementor' ),
			'type'        => Controls_Manager::COLOR,
			'render_type' => 'ui',
		];

		$fields['width'] = [
			'label'      => _x( 'Stroke Width', 'Text Stroke', 'skt-addons-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'responsive' => true,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [
				'{{SELECTOR}}' => '-webkit-text-stroke: {{SIZE}}{{UNIT}} {{color.VALUE}}; text-stroke: {{SIZE}}{{UNIT}} {{color.VALUE}};',
			],
		];

		return $fields;
	}

	/**
	 * Get default options.
	 *
	 * Retrieve the default options of the background control. Used to return the
	 * default options while initializing the background control.
	 *
	 * @since 1.0
	 * @access protected
	 *
	 * @return array Default background control options.
	 */
	protected function get_default_options() {
		return [
			'popover' => [
				'starter_name' => 'skt-text-stroke',
				'starter_title' => _x( 'Text Stroke ', 'Text Stroke', 'skt-addons-elementor' ) . '&nbsp;<i style="color: #d5dadf;" class="skti skti-sktaddonselementor"></i>',
				'settings' => [
					'render_type' => 'ui',
				],
			],
		];
	}
}