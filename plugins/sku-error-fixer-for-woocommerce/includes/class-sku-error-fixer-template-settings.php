<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SKU_Error_Fixer_Template_Settings {

	/**
	 * The single instance of SKU_Error_Fixer_Template_Settings.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 * @var 	object
	 * @access  public
	 * @since 	1.0.0
	 */
	public $parent = null;

	/**
	 * Prefix for plugin settings.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $base = '';

	/**
	 * Available settings for plugin.
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = array();

	public function __construct ( $parent ) {
		$this->parent = $parent;

		$this->base = 'sef_';

		// Initialise settings
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register plugin settings
		add_action( 'admin_init' , array( $this, 'register_settings' ) );

		// Add settings page to menu
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );

		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->parent->file ) , array( $this, 'add_settings_link' ) );
	}

	/**
	 * Initialise settings
	 * @return void
	 */
	public function init_settings () {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Add settings page to admin menu
	 * @return void
	 */
	public function add_menu_item () {
		add_submenu_page( 'woocommerce', 'SKU Error Fixer for WooCommerce', __( 'SKU Error Fixer' ), 'manage_options' , $this->parent->_token . '_settings' ,  array( $this, 'settings_page' ) );
	}

	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_settings_link ( $links ) {
		$settings_link = '<a href="admin.php?page=' . $this->parent->_token . '_settings">' . __( 'Settings' ) . '</a>';
  		array_unshift( $links, $settings_link );
  		return $links;
	}

	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields () {

		$settings['main'] = array(
			'title' => 'Automatically fix unique SKU error',
			'description' => '',
			'fields' => array(
				array(
					'id' => 'auto_clean',
					'label' => 'When an entered SKU number coincides to the SKU of old variations:',
					'description' => __( '' ),
					'type' => 'radio',
					'options' => array(
						'auto_del_sku' => 'Automatically clears matches SKU fields of old variations',
						'auto_del_fully' => 'Automatically removes old variations with this SKU nubmer',
						'default' => 'Not clean',
					),
					'default' => 'auto_del_sku',
				),
			),

		);

		$settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

		return $settings;
	}

	/**
	 * Register plugin settings
	 * @return void
	 */
	public function register_settings () {
		if ( is_array( $this->settings ) ) {

			foreach ( $this->settings as $section => $data ) {

			// Add section to page
			add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->parent->_token . '_settings' );

			foreach ( $data['fields'] as $field ) {

					// Validation callback for field
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field
					$option_name = $this->base . $field['id'];
					register_setting( $this->parent->_token . '_settings', $option_name, $validation );

					// Add field to page
					add_settings_field( $field['id'], $field['label'], array( $this->parent->admin, 'display_field' ), $this->parent->_token . '_settings', $section, array( 'field' => $field, 'prefix' => $this->base ) );
				}
			}
		}
	}

	public function settings_section ( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>';
		echo $html;
	}

	/**
	 * Load settings page content
	 * @return void
	 */
	public function settings_page () {

		// Build page HTML
		$html = '<div class="wrap" id="' . $this->parent->_token . '_settings">';

		$html .= '<h2 class="dashicons-before dashicons-admin-generic options-icon">SKU Error Fixer for WooCommerce</h2>';

		// Update message
		if ( isset( $_REQUEST['settings-updated'] ) ) {
			$html .= '<div class="updated"><p><strong>' . __( 'Options successfully updated!' ) . '</strong></p></div>';
		}

		$html .= '<form method="post" action="options.php" enctype="multipart/form-data" class="wc-sku-cleaner">';

		$html .= '<table class="search-delete-section">';

		$html .= '<tr>
			<td scope="row" class="search-td">
				<h2>' . __( 'Search old product variations' ) . '</h2>
				<p class="description">' . __( 'Click Start search button to find old variations.' ) . '</p>
				<a href="" class="button button-primary search-vars">' . __( 'Start to search old variations' ) . '</a>
				<img src="' . ALIO_VAR_FIXER_PLUGIN_PATH . '/assets/img/loader.gif" class="loader-img" alt="loading...">
				<div class="search-result"></div>
			</td>
			<td rowspan="3" class="automatically-settings-section"><div>';
				// Get settings fields
			ob_start();
			settings_fields( $this->parent->_token . '_settings' );
			do_settings_sections( $this->parent->_token . '_settings' );
			$html .= ob_get_clean();
			$html .= '<p class="submit">';
				$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings'  ) ) . '" />';
			$html .= '</p>';
			$html .= '</div>
				<div class="like-plugin">
					<h2>Do you like this plugin?</h2>
					<p><a href="https://wordpress.org/support/plugin/sku-error-fixer-for-woocommerce/reviews/#new-post" target="_blank">Please give it a 5 star rating</a> on WordPress.org.</p>
				</div>
			</td>
		</tr>';

		$html .= '<tr>
			<td scope="row">
				<h2>' . __( 'Bulk delete' ) . '</h2>
				<p class="description">' . __( 'A simultaneously removal of all the old variations.' ) . '</p>
			</td>
		</tr>';

		$html .= '<tr>
			<td>
				<a href="" class="button button-primary clean-sku">' . __( 'Delete only SKU fields of old variations' ) . '</a>
				<img src="' . ALIO_VAR_FIXER_PLUGIN_PATH . '/assets/img/loader.gif" class="loader-img" alt="loading...">
				<div class="clean-result"></div>
				<hr class="cleaner-divider">
				<a href="" class="button button-primary removal-vars">' . __( 'Delete old variations fully' ) . '</a><img src="' . ALIO_VAR_FIXER_PLUGIN_PATH . '/assets/img/loader.gif" class="loader-img" alt="loading...">
				<p class="description">' . __( 'This operation cannot be cancel. Please backup your database if you are not sure.' ) . '</p>
				<div class="removal-result"></div>
			</td>
		</tr>';

		$html .= '</table>';
		$html .= '</form>';
		$html .= '</div>';

		echo $html;
	}

	/**
	 * Main SKU_Error_Fixer_Template_Settings Instance
	 *
	 * Ensures only one instance of SKU_Error_Fixer_Template_Settings is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Main SKU_Error_Fixer_Template_Settings instance
	 */
	public static function instance ( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __wakeup()

}