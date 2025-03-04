<?php

GFForms::include_addon_framework();

class GFDateTimeField extends GFAddOn {

	protected $_version = GF_DATE_TIME_FIELDS_VERSION;
	protected $_min_gravityforms_version = '1.9';
	protected $_slug = 'datetimefield';
	protected $_path = 'datetimefield/datetimefield.php';
	protected $_full_path = __FILE__;
	protected $_title = 'Date/Time Fields for GravityForms';
	protected $_short_title = 'Date/Time Field';

	/**
	 * @var object $_instance If available, contains an instance of this class.
	 */
	private static $_instance = null;

	/**
	 * Returns an instance of this class, and stores it in the $_instance property.
	 *
	 * @return object $_instance An instance of this class.
	 */
	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Include the field early so it is available when entry exports are being performed.
	 */
	public function pre_init() {
		parent::pre_init();

		if ( $this->is_gravityforms_supported() && class_exists( 'GF_Field' ) ) {
			require_once( 'includes/class-simple-gf-field.php' );
		}
	}

	public function init_admin() {
		parent::init_admin();

		add_filter( 'gform_tooltips', array( $this, 'tooltips' ) );
		add_action( 'gform_field_appearance_settings', array( $this, 'field_appearance_settings' ), 10, 2 );
	}


	// # SCRIPTS & STYLES -----------------------------------------------------------------------------------------------

	/**
	 * Include my_script.js when the form contains a 'simple' type field.
	 *
	 * @return array
	 */
	public function scripts() {
		$scripts = array(
			array(
				'handle'  => 'my_script_js',
				'src'     => $this->get_base_url() . '/js/my_script.js',
				'version' => $this->_version,
				'deps'    => array( 'jquery' ),
				'enqueue' => array(
					array( 'field_types' => array( 'simple' ) ),
				),
			),
            array(
                'handle'  => 'jquery_simple_dtpicker_js',
                'src'     => $this->get_base_url() . '/js/jquery.simple-dtpicker.js',
                'version' => $this->_version,
                'deps'    => array( 'jquery' ),
                'enqueue' => array(
                    array( 'field_types' => array( 'simple' ) ),
                ),
            ),

		);

		return array_merge( parent::scripts(), $scripts );
	}

	/**
	 * Include my_styles.css when the form contains a 'simple' type field.
	 *
	 * @return array
	 */
	public function styles() {
		$styles = array(

            array(
                'handle'  => 'jquery_simple_dtpicker',
                'src'     => $this->get_base_url() . '/css/jquery.simple-dtpicker.css',
                'version' => $this->_version,
                'enqueue' => array(
                    array( 'field_types' => array( 'simple' ) )
                )
            )
		);

		return array_merge( parent::styles(), $styles );
	}


	// # FIELD SETTINGS -------------------------------------------------------------------------------------------------

	/**
	 * Add the tooltips for the field.
	 *
	 * @param array $tooltips An associative array of tooltips where the key is the tooltip name and the value is the tooltip.
	 *
	 * @return array
	 */
	public function tooltips( $tooltips ) {
		$simple_tooltips = array(
			'input_class_setting' => sprintf( '<h6>%s</h6>%s', esc_html__( 'Input CSS Classes', 'datetimefield' ), esc_html__( 'The CSS Class names to be added to the field input.', 'datetimefield' ) ),
		);

		return array_merge( $tooltips, $simple_tooltips );
	}

	/**
	 * Add the custom setting for the Simple field to the Appearance tab.
	 *
	 * @param int $position The position the settings should be located at.
	 * @param int $form_id The ID of the form currently being edited.
	 */
	public function field_appearance_settings( $position, $form_id ) {
		// Add our custom setting just before the 'Custom CSS Class' setting.
		if ( $position == 250 ) {
			?>
			<li class="input_class_setting field_setting">
				<label for="input_class_setting">
					<?php esc_html_e( 'Input CSS Classes', 'datetimefield' ); ?>
					<?php gform_tooltip( 'input_class_setting' ) ?>
				</label>
				<input id="input_class_setting" type="text" class="fieldwidth-1" onkeyup="SetInputClassSetting(jQuery(this).val());" onchange="SetInputClassSetting(jQuery(this).val());"/>
			</li>
			<?php
		}
	}

}