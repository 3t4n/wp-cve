<?php
/**
 * This file contains the class for managing any plugin's settings.
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
 * This class processes an array of settings and makes them available to WordPress.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 *
 * @SuppressWarnings( PHPMD.CyclomaticComplexity )
 * @SuppressWarnings( PHPMD.ExcessiveClassComplexity )
 */
abstract class Nelio_Content_Abstract_Settings {

	/**
	 * The name that identifies Nelio Content's Settings
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string
	 */
	private $name;

	/**
	 * An array of settings that have been requested and where not found in the associated get_option entry.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    array
	 */
	private $default_values;

	/**
	 * An array with the tabs
	 *
	 * Each item in this array looks like this:
	 *
	 * `
	 * array (
	 *    'name'   => a String that identifies the setting.
	 *    'label'  => the UI label of the tab.
	 *    'fields' => an array with all the fields contained in the tab.
	 * )
	 * `
	 *
	 * or this:
	 *
	 * `
	 * array (
	 *    'name'   => a String that identifies the setting.
	 *    'label'  => the UI label of the tab.
	 *    'custom' => the UI will be rendered by somebody else.
	 * )
	 * `
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    array
	 */
	private $tabs;

	/**
	 * The name of the tab we're about to print.
	 *
	 * This is an aux var for enclosing all fields within a tab.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string
	 */
	private $current_tab_name = false;

	/**
	 * Initialize the class, set its properties, and add the proper hooks.
	 *
	 * @param string $name The name of this options group.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function __construct( $name ) {

		$this->default_values = array();
		$this->tabs           = array();
		$this->name           = $name;

	}//end __construct()

	/**
	 * Add proper hooks.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function init() {

		add_action( 'plugins_loaded', array( $this, 'set_tabs' ), 1 );

		add_action( 'admin_init', array( $this, 'register' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );

	}//end init()

	/**
	 * This function has to be implemented by the subclass and specifies which tabs
	 * are defined in the settings page.
	 *
	 * See `do_set_tabs`.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	abstract public function set_tabs();

	/**
	 * This function sets the real tabs.
	 *
	 * @param array $tabs An array with the available tabs and the fields within each tab.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function do_set_tabs( $tabs ) {

		$this->tabs = $tabs;

		foreach ( $this->tabs as $key => $tab ) {

			if ( ! isset( $this->tabs[ $key ]['fields'] ) ) {

				$this->tabs[ $key ]['fields'] = array();

			}//end if

			if ( count( $this->tabs[ $key ]['fields'] ) > 0 ) {

				$tab_name = $tab['name'];

				/**
				 * Filters the sections and fields of the given tab.
				 *
				 * @param array $fields The fields (and sections) of the given tab in the settings screen.
				 *
				 * @since 1.0.0
				 */
				$this->tabs[ $key ]['fields'] = apply_filters( "nelio_content_{$tab_name}_settings", $tab['fields'] );

			}//end if
		}//end foreach

	}//end do_set_tabs()

	/**
	 * Returns the value of the given setting.
	 *
	 * @param string $name  The name of the parameter whose value we want to obtain.
	 * @param object $value Optional. Default value if the setting is not found and
	 *                      the setting didn't define a default value already.
	 *                      Default: `false`.
	 *
	 * @return object The concrete value of the specified parameter.
	 *                If the setting has never been saved and it registered no
	 *                default value (during the construction of `Nelio_Content_Settings`),
	 *                then the parameter `$value` will be returned instead.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @throws Exception If settings are called before `plugins_loaded`.
	 */
	public function get( $name, $value = false ) {

		if ( ! $this->are_ready() ) {
			throw new Exception( _x( 'Nelio Content settings should be used after plugins_loaded.', 'error', 'nelio-content' ) );
		}//end if

		$settings = get_option( $this->get_name(), array() );
		if ( isset( $settings[ $name ] ) ) {
			return $settings[ $name ];
		}//end if

		$this->maybe_set_default_value( $name );
		if ( isset( $this->default_values[ $name ] ) ) {
			return $this->default_values[ $name ];
		} else {
			return $value;
		}//end if

	}//end get()

	/**
	 * Looks for the default value of $name (if any) and saves it in the default values array.
	 *
	 * @param string $name The name of the field whose default value we want to obtain.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function maybe_set_default_value( $name ) {

		$field = false;

		foreach ( $this->tabs as $tab ) {
			foreach ( $tab['fields'] as $f ) {
				switch ( $f['type'] ) {
					case 'section':
						break;
					case 'custom':
						if ( $f['name'] === $name ) {
							$field = $f;
						}//end if
						break;
					case 'checkboxes':
						foreach ( $f['options'] as $option ) {
							if ( $option['name'] === $name ) {
								$field = $f;
							}//end if
						}//end foreach
						break;
					default:
						if ( $f['name'] === $name ) {
							$field = $f;
						}//end if
				}//end switch
			}//end foreach
		}//end foreach

		if ( $field && isset( $field['default'] ) ) {
			$this->default_values[ $name ] = $field['default'];
		}//end if

	}//end maybe_set_default_value()

	/**
	 * Registers all settings in WordPress using the Settings API.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register() {

		foreach ( $this->tabs as $tab ) {
			$this->register_tab( $tab );
		}//end foreach

	}//end register()

	/**
	 * Returns the "name" of the settings script (as used in `wp_register_script`).
	 *
	 * @return string the "name" of the settings script (as used in `wp_register_script`).
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_generic_script_name() {

		return $this->name . '-abstract-settings-js';

	}//end get_generic_script_name()

	/**
	 * Enqueues all required scripts.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_scripts() {

		wp_register_script(
			$this->get_generic_script_name(),
			nelio_content()->plugin_url . '/includes/lib/settings/assets/js/settings.js',
			array(),
			nelio_content()->plugin_version,
			true
		);

	}//end register_scripts()

	/**
	 * Registers the given tab in the Settings page.
	 *
	 * @param array $tab A list with all fields.
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @SuppressWarnings( PHPMD.ExcessiveMethodLength )
	 */
	private function register_tab( $tab ) {

		// Create a default section (which will also be used for enclosing all
		// fields within the current tab).
		$section = 'nelio-content-' . $tab['name'] . '-opening-section';
		add_settings_section(
			$section,
			'',
			function() use ( $tab ) {
				$this->open_tab_content( $tab );
			},
			$this->get_settings_page_name()
		);

		if ( isset( $tab['custom'] ) && $tab['custom'] ) {
			$section = 'nelio-content-' . $tab['name'] . '-tab-content';
			add_settings_section(
				$section,
				'',
				'__return_false',
				$this->get_settings_page_name()
			);
		}//end if

		foreach ( $tab['fields'] as $field ) {

			$defaults = array(
				'desc' => '',
				'more' => '',
			);

			$field = wp_parse_args( $field, $defaults );

			switch ( $field['type'] ) {

				case 'section':
					$section = $field['name'];
					add_settings_section(
						$field['name'],
						$field['label'],
						'',
						$this->get_settings_page_name()
					);
					break;

				case 'textarea':
					$field = wp_parse_args( $field, array( 'placeholder' => '' ) );

					$setting = new Nelio_Content_Text_Area_Setting(
						$field['name'],
						$field['desc'],
						$field['more'],
						$field['placeholder']
					);

					$value = $this->get( $field['name'] );
					$setting->set_value( $value );

					$setting->register(
						$field['label'],
						$this->get_settings_page_name(),
						$section,
						$this->get_option_group(),
						$this->get_name()
					);
					break;

				case 'email':
				case 'number':
				case 'password':
				case 'text':
					$field = wp_parse_args( $field, array( 'placeholder' => '' ) );

					$setting = new Nelio_Content_Input_Setting(
						$field['name'],
						$field['desc'],
						$field['more'],
						$field['type'],
						$field['placeholder']
					);

					$value = $this->get( $field['name'] );
					$setting->set_value( $value );

					$setting->register(
						$field['label'],
						$this->get_settings_page_name(),
						$section,
						$this->get_option_group(),
						$this->get_name()
					);
					break;

				case 'checkbox':
					$setting = new Nelio_Content_Checkbox_Setting(
						$field['name'],
						$field['desc'],
						$field['more']
					);

					$value = $this->get( $field['name'] );
					$setting->set_value( $value );

					$setting->register(
						$field['label'],
						$this->get_settings_page_name(),
						$section,
						$this->get_option_group(),
						$this->get_name()
					);
					break;

				case 'checkboxes':
					$setting = new Nelio_Content_Checkbox_Set_Setting( $field['options'] );

					foreach ( $field['options'] as $cb ) {
						$tuple = array(
							'name'  => $cb['name'],
							'value' => $value,
						);
						$setting->set_value( $tuple );
					}//end foreach

					$setting->register(
						$field['label'],
						$this->get_settings_page_name(),
						$section,
						$this->get_option_group(),
						$this->get_name()
					);
					break;

				case 'range':
					$setting = new Nelio_Content_Range_Setting(
						$field['name'],
						$field['desc'],
						$field['more'],
						$field['args']
					);

					$value = $this->get( $field['name'] );
					$setting->set_value( $value );

					$setting->register(
						$field['label'],
						$this->get_settings_page_name(),
						$section,
						$this->get_option_group(),
						$this->get_name()
					);
					break;

				case 'radio':
					$setting = new Nelio_Content_Radio_Setting(
						$field['name'],
						$field['desc'],
						$field['more'],
						$field['options']
					);

					$value = $this->get( $field['name'] );
					$setting->set_value( $value );

					$setting->register(
						$field['label'],
						$this->get_settings_page_name(),
						$section,
						$this->get_option_group(),
						$this->get_name()
					);
					break;

				case 'select':
					$setting = new Nelio_Content_Select_Setting(
						$field['name'],
						$field['desc'],
						$field['more'],
						$field['options']
					);

					$value = $this->get( $field['name'] );
					$setting->set_value( $value );

					$setting->register(
						$field['label'],
						$this->get_settings_page_name(),
						$section,
						$this->get_option_group(),
						$this->get_name()
					);
					break;

				case 'custom':
					$setting = $field['instance'];

					$value = $this->get( $setting->get_name() );
					$setting->set_value( $value );

					$setting->register(
						$field['label'],
						$this->get_settings_page_name(),
						$section,
						$this->get_option_group(),
						$this->get_name()
					);
					break;

				default:
					trigger_error( esc_html( "Undefined Nelio_Content_Setting field type `{$field['type']}'" ) ); // phpcs:ignore

			}//end switch
		}//end foreach

		// Close tab.
		$section = 'nelio-content-' . $tab['name'] . '-closing-section';
		add_settings_section(
			$section,
			'',
			array( $this, 'close_tab_content' ),
			$this->get_settings_page_name()
		);

	}//end register_tab()

	/**
	 * Opens a DIV tag for enclosing the contents of a tab.
	 *
	 * If the tab we're opening is the first one, we also print the actual tabs.
	 *
	 * @param array $tab the tab to open.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.UnusedLocalVariable )
	 */
	public function open_tab_content( $tab ) {

		// Print the actual tabs (if there's more than one tab).
		if ( count( $this->tabs ) > 1 && $this->tabs[0]['name'] === $tab['name'] ) {
			$tabs = $this->tabs;
			include nelio_content()->plugin_path . '/includes/lib/settings/partials/nelio-content-tabs.php';
		}//end if

		// And now group all the fields under.
		echo '<div id="' . esc_attr( $this->name ) . '-' . esc_attr( $tab['name'] ) . '-tab-content" class="tab-content" style="display:none;">';

	}//end open_tab_content()

	/**
	 * Closes a tab div.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function close_tab_content() {

		echo '</div>';

	}//end close_tab_content()

	/**
	 * Get the name of the option group.
	 *
	 * @return string the name of the settings.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_name() {
		return $this->name . '_settings';
	}//end get_name()

	/**
	 * Get the name of the option group.
	 *
	 * @return string the name of the option group.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_option_group() {
		return $this->name . '_group';
	}//end get_option_group()

	/**
	 * Get the name of the option group.
	 *
	 * @return string the name of the option group.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_settings_page_name() {
		return $this->name . '-settings-page';
	}//end get_settings_page_name()

	/**
	 * Returns whether the settings are ready to be used or not.
	 *
	 * @since  2.0.5
	 * @access public
	 */
	public function are_ready() {
		return 0 < count( $this->tabs );
	}//end are_ready()

}//end class
