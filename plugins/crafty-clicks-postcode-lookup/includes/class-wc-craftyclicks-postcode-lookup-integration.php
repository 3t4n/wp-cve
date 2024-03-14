<?php
/**
 * Crafty Clicks UK Postcode Lookup Integration
 *
 * @package  Crafty Clicks UK Postcode Lookup Integration
 * @category Integration
 * @author   Crafty Clicks
 */

if ( ! class_exists( 'WC_CraftyClicks_Postcode_Lookup_Integration' ) ) :

class WC_CraftyClicks_Postcode_Lookup_Integration extends WC_Integration {

	/**
	 * Init and hook in the integration.
	 */
	public function __construct() {
		global $woocommerce;

		$this->id				 = 'craftyclicks_postcode_lookup';
		$this->method_title	   = __( 'Crafty Clicks Postcode Lookup', 'woocommerce-craftyclicks-postcode-lookup' );
		$this->method_description = __( 'Adds CraftyClicks\' UK Postcode Lookup to WooCommerce checkout pages.', 'woocommerce-craftyclicks-postcode-lookup' );

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->config = (object) [
			'enabled_checkout' => $this->get_option( 'enabled_checkout' ),
			'enabled_orders' => $this->get_option( 'enabled_orders' ),
			'enabled_users' => $this->get_option( 'enabled_users' ),
			'access_token' => $this->get_option( 'access_token' ),
			'button_text' => $this->get_option( 'button_text' ),
			'button_css' => $this->get_option( 'button_css' ),
			'counties' => (int) $this->get_option( 'counties' ),
			'hide_fields' => (int) $this->get_option( 'hide_fields' ),
			'hide_result' => (int) $this->get_option( 'hide_result' ),
			'res_autoselect' => (int) $this->get_option( 'res_autoselect' ),
			'msg1' => $this->get_option( 'msg1' ),
			'err_msg1' => $this->get_option( 'err_msg1' ),
			'err_msg2' => $this->get_option( 'err_msg2' ),
			'err_msg3' => $this->get_option( 'err_msg3' ),
			'err_msg4' => $this->get_option( 'err_msg4' )
		];

		// Actions.
		add_action( 'woocommerce_update_options_integration_' .  $this->id, array( $this, 'process_admin_options' ) );
		if($this->config->enabled_checkout){
			add_action( 'woocommerce_checkout_billing', array( $this, 'addCheckoutJs' ) );
			add_action( 'woocommerce_before_edit_account_address_form', array( $this, 'addCheckoutJs' ) );
		}
		if($this->config->enabled_users){
			add_action( 'edit_user_profile', array( $this, 'addUsersJs' ) );
			add_action( 'profile_personal_options', array( $this, 'addUsersJs' ) );
		}
		if($this->config->enabled_orders){
			add_action( 'dbx_post_advanced', array( $this, 'addOrdersJs' ) );
		}
		// Filters.
		add_filter( 'woocommerce_settings_api_sanitized_fields_' . $this->id, array( $this, 'sanitize_settings' ) );
	}

	public function addJs($type){
		if($this->config->access_token != ''){
			$cc_script_handle = 'craftyclicks-postcode-lookup-js-' . $type;
			wp_enqueue_script($cc_script_handle, plugins_url( '../js/'.$type.'.js', __FILE__ ));
			wp_add_inline_script($cc_script_handle, 'var _cp_config = ' . json_encode($this->config) . ';', 'before');
			wp_add_inline_script($cc_script_handle, 'var _cp_busy_img_url = "' . plugins_url( '../crafty_postcode_busy.gif', __FILE__ ) . '";', 'before');
		}
	}

	public function addCheckoutJs(){ $this->addJs('checkout'); }

	public function addUsersJs(){ $this->addJs('users'); }

	public function addOrdersJs(){ $this->addJs('orders'); }

	/**
	 * Initialize integration settings form fields.
	 *
	 * @return void
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'basic_settings_options' =>	array(
				'title' => __( 'Main Settings', 'woocommerce' ),
				'type'  => 'title',
				'css' => '',
				'id'    => 'basic_settings_options',
				'description'  => __( 'Essential settings and basic customisation for our module.', 'woocommerce' ),
			),
			'enabled_checkout' => array(
				'title'			 => __( 'Enabled - Frontend', 'woocommerce-craftyclicks-postcode-lookup' ),
				'type'			  => 'select',
				'description'	   => __( 'Enable Postcode Lookup on the checkout and account pages', 'woocommerce-craftyclicks-postcode-lookup' ),
				'desc_tip'		  => true,
				'default'		   => 1,
				'options'     => array(
					0 => __('No', 'woocommerce' ),
					1 => __('Yes', 'woocommerce' )
				)
			),
			'enabled_orders' => array(
				'title'			 => __( 'Enabled - Backend (Orders Page)', 'woocommerce-craftyclicks-postcode-lookup' ),
				'type'			  => 'select',
				'description'	   => __( 'Enable Postcode Lookup on the orders page (admin panel)', 'woocommerce-craftyclicks-postcode-lookup' ),
				'desc_tip'		  => true,
				'default'		   => 1,
				'options'     => array(
					0 => __('No', 'woocommerce' ),
					1 => __('Yes', 'woocommerce' )
				)
			),
			'enabled_users' => array(
				'title'			 => __( 'Enabled - Backend (Users Page)', 'woocommerce-craftyclicks-postcode-lookup' ),
				'type'			  => 'select',
				'description'	   => __( 'Enable Postcode Lookup on the users page (admin panel)', 'woocommerce-craftyclicks-postcode-lookup' ),
				'desc_tip'		  => true,
				'default'		   => 1,
				'options'     => array(
					0 => __('No', 'woocommerce' ),
					1 => __('Yes', 'woocommerce' )
				)
			),
			'access_token' => array(
				'title'			 => __( 'Access Token', 'woocommerce-craftyclicks-postcode-lookup' ),
				'type'			  => 'text',
				'description'	   => __( 'Enter your access token here', 'woocommerce-craftyclicks-postcode-lookup' ),
				'desc_tip'		  => true,
				'default'		   => 'xxxxx-xxxxx-xxxxx-xxxxx'
			),
			'counties' => array(
				'title'			 => __( 'Counties', 'woocommerce-craftyclicks-postcode-lookup' ),
				'type'			  => 'select',
				'description'	   => __( 'Choose options for filling county field', 'woocommerce-craftyclicks-postcode-lookup' ),
				'desc_tip'		  => true,
				'default'		   => 1,
				'options'     => array(
					2 => __('Do not fill county', 'woocommerce' ),
					0 => __('Use postal counties', 'woocommerce' ),
					1 => __('Use traditional counties', 'woocommerce' )
				)
			),
			'hide_fields' => array(
				'title'			 => __( 'Hide Address Fields', 'woocommerce-craftyclicks-postcode-lookup' ),
				'type'			  => 'select',
				'description'	   => __( 'Hide the address fields until a search result is selected', 'woocommerce-craftyclicks-postcode-lookup' ),
				'desc_tip'		  => true,
				'default'		   => 1,
				'options'     => array(
					0 => __('No', 'woocommerce' ),
					1 => __('Yes', 'woocommerce' )
				)
			),
			'hide_result' => array(
				'title'			 => __( 'Hide Results', 'woocommerce-craftyclicks-postcode-lookup' ),
				'type'			  => 'select',
				'description'	   => __( 'Hide results box when a result is selected', 'woocommerce-craftyclicks-postcode-lookup' ),
				'desc_tip'		  => true,
				'default'		   => 1,
				'options'     => array(
					0 => __('No', 'woocommerce' ),
					1 => __('Yes', 'woocommerce' )
				)
			),
			'res_autoselect' => array(
				'title'			 => __( 'Auto-select Result', 'woocommerce-craftyclicks-postcode-lookup' ),
				'type'			  => 'select',
				'description'	   => __( 'Auto-select the first result', 'woocommerce-craftyclicks-postcode-lookup' ),
				'desc_tip'		  => true,
				'default'		   => 0,
				'options'     => array(
					0 => __('No', 'woocommerce' ),
					1 => __('Yes', 'woocommerce' )
				)
			),
			'text_options' =>	array(
				'title' => __( 'Text Settings', 'woocommerce-craftyclicks-postcode-lookup' ),
				'type'  => 'title',
				'id'    => 'text_options',
				'description'  => __( 'Change the button text, and customise the error messages.', 'woocommerce-craftyclicks-postcode-lookup' ),
			),
			'button_text' => array(
				'title'			 => __( 'Button Text', 'woocommerce-craftyclicks-postcode-lookup' ),
				'type'			  => 'text',
				'description'	   => __( 'Text for search button', 'woocommerce-craftyclicks-postcode-lookup' ),
				'desc_tip'		  => true,
				'default'		   => 'Find Address'
			),
			'msg1' => array(
				'title'			 => __( 'Busy Image Message', 'woocommerce-craftyclicks-postcode-lookup' ),
				'type'			  => 'text',
				'description'	   => __( 'Message to attach as title to busy image', 'woocommerce-craftyclicks-postcode-lookup' ),
				'desc_tip'		  => true,
				'default'		   => 'Please wait while we find the address'
			),
			'err_msg1' => array(
				'title'			 => __( 'Error Message 1', 'woocommerce-craftyclicks-postcode-lookup' ),
				'type'			  => 'text',
				'description'	   => __( 'Error message if postcode does not exist', 'woocommerce-craftyclicks-postcode-lookup' ),
				'desc_tip'		  => true,
				'default'		   => 'This postcode could not be found, please try again or enter your address manually'
			),
			'err_msg2' => array(
				'title'			 => __( 'Error Message 2', 'woocommerce-craftyclicks-postcode-lookup' ),
				'type'			  => 'text',
				'description'	   => __( 'Error message if postcode incorrectly formatted', 'woocommerce-craftyclicks-postcode-lookup' ),
				'desc_tip'		  => true,
				'default'		   => 'This postcode is not valid, please try again or enter your address manually'
			),
			'err_msg3' => array(
				'title'			 => __( 'Error Message 3', 'woocommerce-craftyclicks-postcode-lookup' ),
				'type'			  => 'text',
				'description'	   => __( 'Error message if there is network problem', 'woocommerce-craftyclicks-postcode-lookup' ),
				'desc_tip'		  => true,
				'default'		   => 'Unable to connect to address lookup server, please enter your address manually'
			),
			'err_msg4' => array(
				'title'			 => __( 'Error Message 4', 'woocommerce-craftyclicks-postcode-lookup' ),
				'type'			  => 'text',
				'description'	   => __( 'Error message to cover any other problem', 'woocommerce-craftyclicks-postcode-lookup' ),
				'desc_tip'		  => true,
				'default'		   => 'An unexpected error occurred, please enter your address manually'
			),
			'advanced_options' =>	array(
				'title' => __( 'Advanced Settings', 'woocommerce-craftyclicks-postcode-lookup' ),
				'type'  => 'title',
				'id'    => 'advanced_options',
				'description'  => __( 'Advanced options for developers only.', 'woocommerce-craftyclicks-postcode-lookup' ),
			),
			'button_css' => array(
				'title'			 => __( 'Button CSS', 'woocommerce-craftyclicks-postcode-lookup' ),
				'type'			  => 'textarea',
				'description'	   => __( 'CSS for the search button, just add the properties separated with ;. Example: padding-left: 20px; font-size: 15px;', 'woocommerce-craftyclicks-postcode-lookup' ),
				'desc_tip'		  => true,
				'css'      => 'min-width: 27%; max-width: 27%; height: 75px;',
				'placeholder' => ''
			)
		);
	}

	/**
	 * Santize our settings
	 * @see process_admin_options()
	 */
	public function sanitize_settings( $settings ) {
		if ( isset( $settings ) &&
			 isset( $settings['api_key'] ) ) {
			$settings['api_key'] = strtoupper( $settings['api_key'] );
		}
		return $settings;
	}

}

endif;
