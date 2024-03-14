<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class ATR_random_sku_for_Woocommerce_Settings {

	/**
	 * The single instance of ATR_random_sku_for_Woocommerce_Settings.
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

		$this->base = 'atr_';

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
		$page = add_options_page( __( 'ATR rand sku Woo', 'atr-random-sku-for-woocommerce' ) , __( 'ATR rand sku Woo', 'atr-random-sku-for-woocommerce' ) , 'manage_options' , $this->parent->_token . '_settings' ,  array( $this, 'settings_page' ) );
	}



	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_settings_link ( $links ) {
		$settings_link = '<a href="options-general.php?page=' . $this->parent->_token . '_settings">' . __( 'Settings', 'atr-random-sku-for-woocommerce' ) . '</a>';
  		array_push( $links, $settings_link );
  		return $links;
	}

	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields () {

		$settings['standard'] = array(
			'title'					=> __( 'Options', 'atr-random-sku-for-woocommerce' ),
			'description'			=> __( 'Set your preferences', 'atr-random-sku-for-woocommerce' ),
			'fields'				=> array(
				array( // since v 2.0.0 
					'id' 			=> 'prefix_sku',
					'label'			=> __( 'Add prefix to SKU' , 'wordpress-plugin-template' ),
					'description'           => __( 'Prefix each SKU with this string (leave empty to avoid prefix)', 'wordpress-plugin-template' ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'           => __( '', 'wordpress-plugin-template' )
				),	
				array(
					'id' 			=> 'force_sku_on_empty',
					'label'			=> __( 'Add auto SKU to empty product' , 'wordpress-plugin-template' ),
					'description'           => __( 'Check this to fill auto SKU when field is empty (also for new products)', 'wordpress-plugin-template' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				), 				
 				array(
					'id' 			=> 'select_sku_format',
					'label'			=> __( 'Format for SKU', 'wordpress-plugin-template' ),
					'description'           => __( 'Select SKU format as a number or as a string. Set the relevant options in the following fields.', 'wordpress-plugin-template' ),
					'type'			=> 'radio',
					'options'		=> array( 'maxminsku' => 'Use max min', 'charactersforsku' => 'Use string', 'increment' => 'Use Incremental' ),
					'default'		=> 'charactersforsku'
				),  
				
				array(
					'id' 			=> 'min_number_for_number',
					'label'			=> __( 'Write min number' , 'wordpress-plugin-template' ),
					'description'           => __( 'Use this min number for SKU. <br /><span style="color:blue">If you leave it empty, the default 100000000 will be used!</span>', 'wordpress-plugin-template' ),
					'type'			=> 'number',
					'default'		=> '',
					'placeholder'           => __( '', 'wordpress-plugin-template' )
				),                             
				array(
					'id' 			=> 'max_number_for_number',
					'label'			=> __( 'Write max number' , 'wordpress-plugin-template' ),
					'description'           => __( 'Use this max number for SKU. <br /><span style="color:blue">If you leave it empty, the default 999999999 will be used!</span>', 'wordpress-plugin-template' ),
					'type'			=> 'number',
					'default'		=> '',
					'placeholder'           => __( '', 'wordpress-plugin-template' )
				),                             
  
				array(
					'id' 			=> 'characters_for_SKU',
					'label'			=> __( 'Characters for SKU' , 'wordpress-plugin-template' ),
					'description'           => __( 'The SKU contains only these characters.', 'wordpress-plugin-template' ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'           => __( 'abcdefghijklmnopqrstuvwxyz0123456789', 'wordpress-plugin-template' )
				), 
				array(
					'id' 			=> 'sku_length',
					'label'			=> __( 'SKU length' , 'wordpress-plugin-template' ),
					'description'           => __( 'Use this length for SKU. <br /><span style="color:blue">If you leave it empty, the default length 8 will be used!</span>', 'wordpress-plugin-template' ),
					'type'			=> 'number',
					'default'		=> '', // since v 1.0.2 - set empty since the place holder was seen like having a number
					'placeholder'           => __( '', 'wordpress-plugin-template' )
				),                             
				array( // since v 2.0.0 
					'id' 			=> 'incremental_sku_start',
					'label'			=> __( 'Start at' , 'wordpress-plugin-template' ),
					'description'           => __( 'Start incremnt from this number.<br /><span style="color:maroon">Please note: This number is dynamic. It is used for the SKU of next product and incrementing by 1 on every saved product while incrementing option is selected.</span><br /><span style="color:green">If you change it, the new value will be used for next generated product SKU and keep incrementing!</span><span style="color:blue">If you leave it empty, the default start 1 will be used!</span>', 'wordpress-plugin-template' ),
					'type'			=> 'number',
					'default'		=> '', 
					'placeholder'           => __( '', 'wordpress-plugin-template' )
				),					
				array( // since v 2.0.0 
					'id' 			=> 'incremental_sku_min_num_digits',
					'label'			=> __( 'Number of digits' , 'wordpress-plugin-template' ),
					'description'           => __( '<br />Left pad with 0 (zero) to set this number of digits. Example: 6 digits will produce 000001 etc.<br /><span style="color:blue">If you leave it empty, no padding digits will be used!</span>', 'wordpress-plugin-template' ),
					'type'			=> 'number',
					'default'		=> '', 
					'placeholder'           => __( '', 'wordpress-plugin-template' )
				),                          

			)
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

			// Check posted/selected tab
			$current_section = '';
			foreach ( $this->settings as $section => $data ) {

				if ( $current_section && $current_section != $section ) continue;

				// Add section to page
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->parent->_token . '_settings' );

				foreach ( $data['fields'] as $field ) {

					// Validation callback for field
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}
					//$validation = $this->sanitize($field);
					// Register field
					$option_name = $this->base . $field['id'];
					if ( isset( $field['type'] ) ) {
						if ($field['type'] === 'text'){
							register_setting( $this->parent->_token . '_settings', $option_name, 'sanitize_text_field' );		
						}
						else {
							register_setting( $this->parent->_token . '_settings', $option_name, $validation );
						}
											
					}				
					

					// Add field to page
					add_settings_field( $field['id'], $field['label'], array( $this->parent->admin, 'display_field' ), $this->parent->_token . '_settings', $section, array( 'field' => $field, 'prefix' => $this->base ) );

				}

				if ( ! $current_section ) break;
			}
		}
	}

	public function settings_section ( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		echo $html;
	}

	/**
	 * Load settings page content
	 * @return void
	 */
	public function settings_page () {

		// Build page HTML
		$html = '<div class="atr-random-sku-settings-wrap" id="' . $this->parent->_token . '_settings">' . "\n";
			$html .= '<h2>' . __( 'ATR random SKU for Woocommerce Settings' , 'atr-random-sku-for-woocommerce' ) . '</h2>' . "\n";

			$tab = '';
			if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
				$tab .= $_GET['tab'];
			}

			// Show page tabs
			if ( is_array( $this->settings ) && 1 < count( $this->settings ) ) {

				$html .= '<h2 class="nav-tab-wrapper">' . "\n";

				$c = 0;
				foreach ( $this->settings as $section => $data ) {

					// Set tab class
					$class = 'nav-tab';
					if ( ! isset( $_GET['tab'] ) ) {
						if ( 0 == $c ) {
							$class .= ' nav-tab-active';
						}
					} else {
						if ( isset( $_GET['tab'] ) && $section == $_GET['tab'] ) {
							$class .= ' nav-tab-active';
						}
					}

					// Set tab link
					$tab_link = add_query_arg( array( 'tab' => $section ) );
					if ( isset( $_GET['settings-updated'] ) ) {
						$tab_link = remove_query_arg( 'settings-updated', $tab_link );
					}

					// Output tab
					$html .= '<a href="' . $tab_link . '" class="' . esc_attr( $class ) . '">' . esc_html( $data['title'] ) . '</a>' . "\n";

					++$c;
				}

				$html .= '</h2>' . "\n";
			}

			$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

				// Get settings fields
				ob_start();
				settings_fields( $this->parent->_token . '_settings' );
				do_settings_sections( $this->parent->_token . '_settings' );
				$html .= ob_get_clean();
				$html .= '<p class="submit">' . "\n";
					$html .= '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";
					$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings' , 'atr-random-sku-for-woocommerce' ) ) . '" />' . "\n";
				$html .= '</p>' . "\n";
			$html .= '</form>' . "\n";
		$html .= '</div>' . "\n";

		echo $html;
	}

	/**
	 * Main ATR_random_sku_for_Woocommerce_Settings Instance
	 *
	 * Ensures only one instance of ATR_random_sku_for_Woocommerce_Settings is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see ATR_random_sku_for_Woocommerce()
	 * @return Main ATR_random_sku_for_Woocommerce_Settings instance
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