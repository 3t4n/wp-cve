<?php
/**
 * Icon-link control class
 *
 * @package AbsoluteAddons
 */

namespace AbsoluteAddons\Controls;

use Elementor\Group_Control_Base;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

class Group_Control_ABSP_link_Icon extends Group_Control_Base {

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
	 * Retrieve the control type, in this case `absp_text_color`.
	 *
	 * @return string Control type.
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 */
	public static function get_type() {
		return 'link-icon';
	}

	/**
	 * Init fields.
	 *
	 * Initialize background control fields.
	 *
	 * @return array Control fields.
	 * @since 1.2.2
	 * @access public
	 *
	 */
	public function init_fields() {
		$fields = [];

		$fields['icons'] = [
			'label'       => _x( 'Icon', 'Add Icon', 'absolute-addons' ),
			'type'        => Controls_Manager::ICONS,
			'label_block' => false,
			'render_type' => 'ui',
			'default'     => 'classic',
		];

		$fields['link_text'] = [
			'label'   => _x( 'Link Text', 'Type Site Name', 'absolute-addons' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Contact',
			'title'   => _x( 'Link Text', 'Type Site Name', 'absolute-addons' ),
		];

		$fields['link_url'] = [
			'label'   => _x( 'Link Url', 'Type Site Link', 'absolute-addons' ),
			'type'    => Controls_Manager::URL,
			'default' => '#',
			'title'   => _x( 'Link Url', 'Type Site Link', 'absolute-addons' ),
		];

		return $fields;
	}


	/**
	 * Filter fields.
	 *
	 * Filter which controls to display, using `include`, `exclude`, `condition`
	 * and `of_type` arguments.
	 *
	 * @return array Control fields.
	 * @since 1.2.2
	 * @access protected
	 *
	 */
	protected function filter_fields() {
		$fields = parent::filter_fields();

		$args = $this->get_args();

		foreach ( $fields as &$field ) {
			if ( isset( $field['of_type'] ) && ! in_array( $field['of_type'], $args['types'] ) ) {
				unset( $field );
			}
		}

		return $fields;
	}

	/**
	 * Get default options.
	 *
	 * Retrieve the default options of the background control. Used to return the
	 * default options while initializing the background control.
	 *
	 * @return array Default background control options.
	 * @since 1.9.0
	 * @access protected
	 *
	 */
	protected function get_default_options() {
		return [
			'popover' => false,
		];
	}
}
