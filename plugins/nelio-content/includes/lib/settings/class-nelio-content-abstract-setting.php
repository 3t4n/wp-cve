<?php
/**
 * Abstract class that implements the `register` method of the `Nelio_Content_Setting` interface.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * A class that represents a Nelio_Content Setting.
 *
 * It only implements the `register` method, which will be common among all
 * Nelio Content Testing's settings.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
abstract class Nelio_Content_Abstract_Setting implements Nelio_Content_Setting {

	/**
	 * The label associated to this setting.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $label;

	/**
	 * The name that identifies this setting.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $name;

	/**
	 * A text that describes this field.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $desc;

	/**
	 * A link pointing to more information about this field.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $more;

	/**
	 * The option name in which this setting will be stored.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $option_name;

	/**
	 * Creates a new instance of this class.
	 *
	 * @param string $name The name that identifies this setting.
	 * @param string $desc Optional. A text that describes this field.
	 *                     Default: the empty string.
	 * @param string $more Optional. A link pointing to more information about this field.
	 *                     Default: the empty string.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct( $name, $desc = '', $more = '' ) {

		$this->name = $name;
		$this->desc = $desc;
		$this->more = $more;

	}//end __construct()

	/**
	 * Returns the name that identifies this setting.
	 *
	 * @return string The name that identifies this setting.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_name() {
		return $this->name;
	}//end get_name()

	// @Implements
	public function register( $label, $page, $section, $option_group, $option_name ) { // phpcs:ignore

		$this->label       = $label;
		$this->option_name = $option_name;

		register_setting(
			$option_group,
			$option_name,
			array( $this, 'sanitize' ) // Sanitization function.
		);

		$label = $this->generate_label();
		add_settings_field(
			$this->name,  // The ID of the settings field.
			$label,       // The name of the field of setting(s).
			array( $this, 'display' ),
			$page,
			$section
		);

	}//end register()

	/**
	 * This function generates a label for this field.
	 *
	 * In particular, it adds the `label` tag and a help icon (if a description
	 * was provided).
	 *
	 * @return string the label for this field.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function generate_label() {

		$label = '<label for="' . $this->option_name . '">' . $this->label . '</label>';

		if ( ! empty( $this->desc ) ) {
			$img    = nelio_content()->plugin_url . '/includes/lib/settings/assets/images/help.png';
			$label .= '<img class="nelio-content-help" style="float:right;margin-right:-15px;cursor:pointer;" src="' . $img . '" height="16" width="16" />';
		}//end if

		return $label;

	}//end generate_label()

}//end class
