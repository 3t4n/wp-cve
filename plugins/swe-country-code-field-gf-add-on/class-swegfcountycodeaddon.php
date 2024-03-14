<?php

GFForms::include_addon_framework();

class SWEGFCountryCodeAddOn extends GFAddOn {

	protected $_version = SWE_GF_COUNTRY_CODE_ADDON_VERSION;
	protected $_min_gravityforms_version = '1.9';
	protected $_slug = 'swecountrycodeaddon';
	protected $_path = 'swecountrycodeaddon/swecountrycodeaddon.php';
	protected $_full_path = __FILE__;
	protected $_title = 'SWE Country Code Field Gravity Forms Add-On';
	protected $_short_title = 'SWE Field Add-On';

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
			require_once( 'includes/class-swe-country-code-gf-field.php' );
		}
	}

	public function init_admin() {
		parent::init_admin();

		add_filter( 'gform_tooltips', array( $this, 'tooltips' ) );
		add_action( 'gform_field_appearance_settings', array( $this, 'field_appearance_settings' ), 10, 2 );
	}


	// # SCRIPTS & STYLES -----------------------------------------------------------------------------------------------

	/**
	 * Include swe_gf_countrycode_script.js when the form contains a 'SWE Country Code' type field.
	 *
	 * @return array
	 */
	public function scripts() {
		$scripts = array(
			array(
				'handle'  => 'swe_gfcountrycode_script',
				'src'     => $this->get_base_url() . '/js/swe_gf_countrycode_script.js',
				'version' => $this->_version,
				'deps'    => array( 'jquery' ),
				'in_footer' => true,
				'enqueue' => array( 
				 array( $this, 'Phone with Country Code')
				 ),
			),

		);

		return array_merge( parent::scripts(), $scripts );
	}

	/**
	 * Include swe_gf_countrycode_script.css when the form contains a 'Phone with Country Code' type field.
	 *
	 * @return array
	 */
	public function styles() {
		$styles = array(
			array(
				'handle'  => 'swe_gf_countrycode_script_css',
				'src'     => $this->get_base_url() . '/css/swe_gf_countrycode_style.css',
				'version' => $this->_version,
				'enqueue' => array(
					array( $this, 'Phone with Country Code')
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
		$swe_gffield_tooltips = array(
			'input_class_setting' => sprintf( '<h6>%s</h6>%s', esc_html__( 'Input CSS Classes', 'swegffieldaddon' ), esc_html__( 'The CSS Class names to be added to the field input.', 'swegffieldaddon' ) ),
		);

		return array_merge( $tooltips, $swe_gffield_tooltips );
	}

	/**
	 * Add the custom setting for the Phone Country Code field to the Appearance tab.
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
					<?php esc_html_e( 'Input CSS Classes', 'swegffieldaddon' ); ?>
					<?php gform_tooltip( 'input_class_setting' ) ?>
				</label>
				<input id="input_class_setting" type="text" class="fieldwidth-1" onkeyup="SetInputClassSetting(jQuery(this).val());" onchange="SetInputClassSetting(jQuery(this).val());"/>
			</li>

			<?php
		}
	}

}