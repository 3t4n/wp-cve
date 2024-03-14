<?php
/* *
* Author: zetamatic
* @package https://zetamatic.com/
*/

class WC_CheckoutAddressAutocomplete {

  protected $option_name = 'wcaf_options';

  public function __construct() {
	//adding filters
	add_filter( "plugin_action_links_". WCGAAW_BASE, array( $this, 'wcaf_settings_link' ) );
  if (get_option('wc_af_prohibit_address_clear') != '1') {
    add_filter('woocommerce_checkout_get_value', array( $this, 'clear_checkout_fields' ));
  }

	//adding actions
	add_action( 'admin_menu', array( $this, 'wc_af_admin_menu' ) );
	add_action( 'admin_init', array( $this, 'wc_af_plugin_settings' ) );
	add_action( 'wp_enqueue_scripts', array( $this, 'wc_af_enqueue_script' ) );
	add_action( 'admin_enqueue_scripts', array( $this, 'wc_af_admin_script' ) );

	if( get_option( 'wc_af_show_below_for_bill' ) == '1' ) :
	  add_action( 'woocommerce_after_checkout_billing_form', array( $this, 'wcaf_custom_checkout_field' ) );
	else :
	  add_action( 'woocommerce_before_checkout_billing_form', array( $this, 'wcaf_custom_checkout_field' ) );
	endif;


	if( get_option( 'wc_af_show_below_for_ship' ) == '1' ) :
	  add_action( 'woocommerce_after_checkout_shipping_form', array( $this, 'wcaf_custom_checkout_field_for_shipping_form' ) );
	else :
	  add_action( 'woocommerce_before_checkout_shipping_form', array( $this, 'wcaf_custom_checkout_field_for_shipping_form' ) );
	endif;

	add_action( 'update_option_wc_af_enable_use_location', array( $this, 'wcaf_save_image' ) );

  }


/**
  * Creating custom field and icon for autocomplete
  *
  * @param mixed
  * @return empty
  *
  */
  public function wcaf_custom_checkout_field_for_shipping_form( $checkout ) {

	$label = __( 'Enter your ship address', 'checkout_address_autofill_for_woocommerce' );

	if( ! empty( get_option( 'wc_af_label_for_ship_field' ) ) ) :
	  $label = get_option( 'wc_af_label_for_ship_field' );
	endif;

	$height = ! empty( get_option( 'wc_af_image_height' ) ) ? get_option( 'wc_af_image_height' ).'px' : '50px';
	$width = ! empty( get_option( 'wc_af_image_width' ) ) ? get_option( 'wc_af_image_width' ).'px' : '50px';

	if( get_option( 'wc_af_enable_for_shipping' ) == '1' ) {
	  //checking for autocomplete option is enable or not
	  $html = '<div id="wcaf_custom_checkout_field">';

	  if( get_option( 'wc_af_enable_use_location' ) == '1' ) {

		//checking for current location option is enable or not
		$html .= '<img class="locimg" src="'.get_option( 'wc_af_location_image' ).'" onClick="shipping_geolocate()" style="width:'.$width.';height:'.$height.';">';//creating icon for using current location
	  }

	  $html .= woocommerce_form_field( 'shipping_autofill_checkout_field', array(
		  'type'          => 'text',
		  'class'         => array( 'ship-autofill-field form-row-wide' ),
		  'label'         => $label,
		  'placeholder'   => __( 'Search to Autocomplete', 'checkout_address_autofill_for_woocommerce' )
		), $checkout->get_value( 'shipping_autofill_checkout_field' ) );


	  $html .= '</div>';

	  echo $html;
	}
  }



  /**
  * Setting link on plugin page
  *
  * @param array
  * @return array
  *
  */
  public function wcaf_settings_link( $links ) {
	$settings_link = '<a href="'.admin_url( 'options-general.php?page=wc-af-options' ).'">Settings</a>';
	array_unshift( $links, $settings_link );
	return $links;
  }

  /**
  * Creating custom field and icon for autocomplete
  *
  * @param mixed
  * @return empty
  *
  */
  public function wcaf_custom_checkout_field( $checkout ) {

	$label = __( 'Enter your Billing address', 'checkout_address_autofill_for_woocommerce' );

	if( ! empty( get_option( 'wc_af_label_for_bill_field' ) ) ) :
	  $label = get_option( 'wc_af_label_for_bill_field' );
	endif;

	$height = ! empty( get_option( 'wc_af_image_height' ) ) ? get_option( 'wc_af_image_height' ).'px' : '50px';
	$width = ! empty( get_option( 'wc_af_image_width' ) ) ? get_option( 'wc_af_image_width' ).'px' : '50px';

	if( get_option( 'wc_af_enable_for_billing' ) == '1' ) {
	  //checking for autocomplete option is enable or not
	  $html = '<div id="wcaf_custom_checkout_field">';

	  if( get_option( 'wc_af_enable_use_location' ) == '1' ) {

		//checking for current location option is enable or not
		$html .= '<img class="locimg" src="'.get_option( 'wc_af_location_image' ).'" onClick="billing_geolocate()" style="width:'.$width.';height:'.$height.';">';//creating icon for using current location
	  }

	  $html .= woocommerce_form_field( 'autofill_checkout_field', array(
		  'type'          => 'text',
		  'class'         => array( 'my-field-class form-row-wide' ),
		  'label'         => $label,
		  'placeholder'   => __( 'Search to Autocomplete', 'checkout_address_autofill_for_woocommerce' )
		), $checkout->get_value( 'autofill_checkout_field' ) );


	  $html .= '</div>';

	  echo $html;
	}



  }

  /**
  * Adding admin script
  *
  * @param empty
  * @return mixed
  *
  */
  public function wc_af_admin_script() {
	if( isset( $_GET['page'] ) && $_GET['page'] == 'wc-af-options' ) {

      // for testing google autofields
      $key = get_option('wc_af_api_key');

      wp_enqueue_script('wc-af-api-testing', "https://maps.googleapis.com/maps/api/js?key=$key&libraries=places", '', true);


	  wp_enqueue_style( 'caa-stylesheet', plugins_url( 'assets/css/autofill-address-settings.css', __FILE__ ) );
	  wp_enqueue_style( 'font-awesome',"https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" );

	  //zeta-plugins style 
	  wp_enqueue_style( 'zeta-explore-stylesheet', plugins_url( 'assets/css/zeta-explore.css', __FILE__ ) );

	  //Add select2 for country selection
	  wp_enqueue_style( 'select2-style', plugins_url( 'assets/css/select2.css', __FILE__ ) );
	  wp_enqueue_script( 'wcgaa-select2', plugins_url( 'assets/js/select2.min.js', __FILE__ ) , array( 'jquery' ), '1.0.0', true );

	  $url = plugin_dir_url( __FILE__ ).'/assets/js/autofill.js';
	  wp_register_script( 'wc-af-main', $url, array( 'jquery', 'wcgaa-select2' ), WCGAAW_PLUGIN_VERSION, true );
	  wp_enqueue_script( 'wc-af-main' );
	  wp_enqueue_media();
	}
  }

  /**
  * Function for including scripts and style
  *
  * @param empty
  * @return mixed
  *
  */
  public function wc_af_enqueue_script() {
	$url = plugin_dir_url( __FILE__ ).'/assets/js/autofill.js';

	// getting api key from database
	$key = get_option( 'wc_af_api_key' );

	$language= '';
	if( ! empty( get_option( 'wc_af_languages_for_google_autofill' ) ) ) :
	  $autofill_language = get_option('wc_af_languages_for_google_autofill');
	  $language = '&language='.$autofill_language;

	endif;

	// adding scripts
	wp_register_script( 'wc-af-main', $url, array( 'jquery' ), WCGAAW_PLUGIN_VERSION, true );
	wp_register_script( 'wc-af-api', "https://maps.googleapis.com/maps/api/js?key=$key&libraries=places&callback=initAutocomplete$language", array(), '', true );

	wp_enqueue_script( 'wc-af-main' );
	wp_enqueue_script( 'wc-af-api' );


	wp_localize_script( 'wc-af-main', 'wcaf',
	  array(
		'autofill_for_shipping'         => get_option( 'wc_af_enable_for_shipping' ),
		'selectedCountry'               => get_option( 'wc_af_country' ),
		'enable_billing_company_name'   => get_option( 'wc_af_enable_company_name_for_bill' ),
		'enable_shipping_company_name'  => get_option( 'wc_af_enable_company_name_for_ship' ),
		'enable_billing_phone'          => get_option( 'wc_af_enable_phone_number_for_bill' ),
		'locationImage'                 => 'Location Image',
		'uploadImage'                   => 'Upload Image',
	  )
	);

	//adding style
	if( get_option( 'wc_af_enable_hover' ) ) {
	  wp_enqueue_style( 'auto-fill-css',  plugin_dir_url( __FILE__ ) . 'assets/css/autofill.css', WCGAAW_PLUGIN_VERSION );
	}

  }

  /**
  * function for clearing all the values of checkout fields
  *
  * @param string
  * @return string
  *
  */
  public function clear_checkout_fields( $input ) {
	return ''; // return blank field
  }

  /**
  * Function  for creating setting page in admin
  *
  * @param empty
  * @return empty
  *
  */
  public function wc_af_admin_menu() {
	add_options_page( __( 'Google Address Autocomplete for Woocommerce', 'checkout_address_autofill_for_woocommerce' ), __( 'Google Autocomplete', 'checkout_address_autofill_for_woocommerce' ), 'manage_options', 'wc-af-options', array( $this, 'wc_af_admin_options' ) );
  }

  /**
  * register admin settings
  *
  * @param empty
  * @return empty
  *
  */
  public function wc_af_plugin_settings() {
	register_setting( 'wc-af-settings-group', 'wc_af_api_key' );

  }

  public function validate( $input ) {
	$valid        = array();
	$output_array = array();

	if( isset( $_POST['wc_af_country'] ) ) {
	  foreach( $_POST['wc_af_country'] as $key => $post_arr ) {
		array_push( $output_array, sanitize_text_field( $post_arr ) );
	  }
	}

	$valid['wc_af_country'] = $output_array;
	return $valid;
  }

	/**
	 * Admin option page form
	*
	* @param empty
	* @return empty
	*
	*/
	public function wc_af_admin_options() {
		if ( ! current_user_can( 'manage_options' ) )  { // Checking user can manage or not
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'gaafw' ) );
		}
	?>
		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">

			<div id="icon-themes" class="icon32"></div>
			<h1><?php echo __( 'Google Address Autocomplete for Woocommerce', 'checkout_address_autofill_for_woocommerce' ); ?></h1>
			<?php settings_errors(); ?>
			<form method="post" action="options.php" id="checkout-address-autocomplete-form">

				<?php settings_fields('wc-af-settings-group'); ?>
				<?php do_settings_sections('wc-af-settings-group'); ?>
				<table class="form-table">
					<tr valign="top">
						<div class="zetamatic_support" style = "background: #ffffff94; border: 1px solid #ccc; border-radius: 2px; padding: 0 0.6rem;">
							<h3 style = "margin-bottom: 0 ;"> <a href="https://zetamatic.com" target = "_blank" style = "text-decoration:none; color:black;"class="dashicons  dashicons-editor-help" title = "<?php echo __('Need help?', 'checkout_address_autofill_for_woocommerce'); ?>" ></a>
							<a href="https://zetamatic.com/" style = "text-decoration:none; color:black;" target = "_blank" title = "<?php echo __('Need help? We are happy to help!', 'checkout_address_autofill_for_woocommerce'); ?>"><?php echo __('Need help?', 'checkout_address_autofill_for_woocommerce'); ?></a></h3>

							<div class = "zetamatic_support_container" style = "display:flex;">
								<div class = "zetamatic_support_setup_directions" style = "margin-right: 0.625rem;">
								<h4>
									<a href="https://zetamatic.com/docs/checkout-address-autofill-for-woocommerce/setup" target = "_blank" style = "text-decoration:none; color:black;"class="dashicons dashicons-admin-generic" title = "<?php echo __('Setup Directions', 'checkout_address_autofill_for_woocommerce'); ?>" ></a>
									<a href="https://zetamatic.com/docs/checkout-address-autofill-for-woocommerce/setup" target = "_blank" style = "text-decoration:none;" title = "<?php echo __('A step-by-step guide on how to setup and use the plugin.', 'checkout_address_autofill_for_woocommerce'); ?>"><?php echo __(' Setup Directions', 'checkout_address_autofill_for_woocommerce'); ?></a>
								</h4>
								</div>
								
								<div class = "zetamatic_support_docs" style = "margin-right: 0.625rem;">
								<h4 style = "margin-bottom:2;">
									<a href="https://zetamatic.com/docs/checkout-address-autofill-for-woocommerce/" target = "_blank" style = "text-decoration:none; color:black;"class="dashicons  dashicons-media-document" title = "<?php echo __('Documentation', 'checkout_address_autofill_for_woocommerce'); ?>" ></a>
									<a href="https://zetamatic.com/docs/checkout-address-autofill-for-woocommerce/" target = "_blank" style = "text-decoration:none;" title = "<?php echo __('View our expansive library of documentation to help solve your problem as quickly as possible.', 'checkout_address_autofill_for_woocommerce'); ?>"><?php echo __(' Documentation', 'checkout_address_autofill_for_woocommerce'); ?></a>
								</h4>
								
								</div>

								<div class = "zetamatic_support_faqs" style = "margin-right: 0.625rem;">
								<h4 style = "margin-bottom:2;">
									
									</a> <a href = "https://zetamatic.com/docs/checkout-address-autofill-for-woocommerce/faqs/" target = "_blank"  style = "text-decoration: none;" title = "<?php echo __(' FAQs', 'checkout_address_autofill_for_woocommerce'); ?>"><img style = "height: 14px; width: 17px;" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDQ4IDQ4IiBoZWlnaHQ9IjQ4cHgiIGlkPSJMYXllcl8zIiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCA0OCA0OCIgd2lkdGg9IjQ4cHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxwYXRoIGQ9Ik0yNCwwLjEyNUMxMC44MTQsMC4xMjUsMC4xMjUsMTAuODE0LDAuMTI1LDI0YzAsMTMuMTg3LDEwLjY4OSwyMy44NzUsMjMuODc1LDIzLjg3NSAgYzEzLjE4NiwwLDIzLjg3NS0xMC42ODgsMjMuODc1LTIzLjg3NUM0Ny44NzUsMTAuODE0LDM3LjE4NiwwLjEyNSwyNCwwLjEyNXogTTIzLjQ0OSwxNC4xNjJjMC4wOTgtMC41NzYsMC4zODctMS4wNjIsMC44NjEtMS40NjYgIGMwLjQ2OS0wLjQwNywwLjk5My0wLjYwOSwxLjU2OS0wLjYwOWMwLjU3MywwLDEuMDM3LDAuMjAyLDEuMzkyLDAuNjA5YzAuMzU2LDAuNDAzLDAuNDc4LDAuODksMC4zOCwxLjQ2NiAgYy0wLjEwNCwwLjU3Mi0wLjM5MSwxLjA2Mi0wLjg2LDEuNDY3Yy0wLjQ3MywwLjQwMy0wLjk5OCwwLjYwNS0xLjU3LDAuNjA1Yy0wLjU3MiwwLTEuMDI5LTAuMjAyLTEuMzY3LTAuNjA1ICBDMjMuNTE1LDE1LjIyNSwyMy4zNzgsMTQuNzM0LDIzLjQ0OSwxNC4xNjJ6IE0zMC4wODQsMzEuNDQ1YzAsMC0wLjAyLDAuMDI5LTAuMDUsMC4wNzJjLTAuMDQsMC4wNTItMC4wODIsMC4xMDgtMC4xMzEsMC4xNjcgIGMtMC41NTEsMC42OTUtMi4zNzQsMi43NzItNS4xNDYsMy44MDRjLTAuMDk3LDAuMDM3LTAuMTk1LDAuMDc0LTAuMjkyLDAuMTA1Yy0wLjAzOSwwLjAxNC0wLjA3NiwwLjAyOS0wLjExNiwwLjA0MyAgYy0wLjAwNC0wLjAwMi0wLjAwNi0wLjAwNC0wLjAxLTAuMDA0Yy0wLjU4NiwwLjE4MS0xLjE2LDAuMjgxLTEuNzE2LDAuMjgxYy0xLjIyOCwwLTEuODQzLTAuNTYyLTEuODQzLTEuNjg1ICBjMC0wLjQ3NiwwLjI1Mi0xLjkzNywwLjc1Ni00LjM3OWwwLjk4Ni00LjcyOGwwLjI1MS0xLjE5NWwwLjIwMi0wLjk1N2MwLjA4NS0wLjQwNiwwLjEyNy0wLjc0NSwwLjEyNy0xLjAyMyAgYzAtMC4zMDktMC4wOS0wLjUwNi0wLjIyNy0wLjY0NmMtMC4xOS0wLjE1Ny0wLjQ4OC0wLjEzLTAuNjQ1LTAuMDk0Yy0wLjMzNywwLjA4OS0wLjcyMSwwLjI0Ny0wLjg3MiwwLjMxMiAgYy0xLjk5NiwwLjk5OC0zLjE4NSwyLjUzMS0zLjE4NSwyLjUzMWMtMC4zMTYtMC4xOTYtMC40NzYtMC4zOTQtMC40NzYtMC41OWMwLTAuMjUxLDAuMTI0LTAuNTMzLDAuMzYtMC44NDNsLTAuMDAyLDAuMDAxICBsMC4wMDktMC4wMTFjMC4xODItMC4yMzcsMC40MjYtMC40OSwwLjc0NS0wLjc2MmMwLjQxNS0wLjM4MywxLjAxMy0wLjg1OCwxLjc2Mi0xLjMzMmMwLjAzNy0wLjAyNCwwLjA2MS0wLjA0MywwLjEwMS0wLjA2OSAgYzIuNzY2LTEuODIzLDQuNjMzLTEuNjgyLDQuNjMzLTEuNjgybC0wLjAwNSwwLjAwOGMwLjI4NCwwLjAyMywwLjY5NSwwLjEyNywxLjEzNywwLjQ4N2MwLjA0MiwwLjAzNCwwLjA1OCwwLjA2NCwwLjA4OCwwLjA5OCAgYzAuMDI4LDAuMDI4LDAuMDU2LDAuMDU4LDAuMDgyLDAuMDg5YzAuMDI5LDAuMDQyLDAuMDUxLDAuMDgyLDAuMDY2LDAuMTIyYzAuMTc3LDAuMjc0LDAuMjg3LDAuNjExLDAuMjg3LDEuMDU1ICBjMCwwLjIzMi0wLjA2OCwwLjcwOC0wLjIwMSwxLjQyN2wtMC4yMjYsMS4xNjJjLTAuMDIxLDAuMDkzLTAuMTEsMC41NjItMC4yNzgsMS40MDhsLTEuMDM1LDQuOTY5bC0wLjI1MywxLjExMSAgYy0wLjIyMSwxLjEwMi0wLjMzLDEuODM0LTAuMzMsMi4yMDVjMCwwLjQ0OSwwLjE4NCwwLjY4NywwLjUyMywwLjczYzAuMTItMC4wMSwwLjQ5Mi0wLjA0NiwwLjg5MS0wLjE4OCAgYzAuMTM1LTAuMDYyLDAuMjcxLTAuMTE0LDAuNDAxLTAuMTg1YzAuMDA2LTAuMDA2LDAuMDE1LTAuMDEsMC4wMjQtMC4wMTRjMS41MTEtMC44MzcsMi42NzItMi4yMzYsMi45ODQtMi42MzIgIGMwLjA1Mi0wLjA3NiwwLjEwNC0wLjE0MSwwLjE1Ni0wLjIxNmMwLjQ0NiwwLjE4NywwLjY3NywwLjM2NSwwLjY3NywwLjU0YzAsMC4xMjgtMC4wNzYsMC4yOTktMC4yMTcsMC41MDVIMzAuMDg0eiIgZmlsbD0iIzI0MUYyMCIvPjwvc3ZnPg==" alt="Need Help?"></a>

									<a href="https://zetamatic.com/docs/checkout-address-autofill-for-woocommerce/faqs/" target = "_blank" style = "text-decoration:none;" title = "<?php echo __('Please browse the Frequently Asked Questions to see if your query has already been answered.', 'checkout_address_autofill_for_woocommerce'); ?>"><?php echo __(' FAQs', 'checkout_address_autofill_for_woocommerce'); ?></a>
								</h4>
								
								</div>

								<div class = "zetamatic_support_livechat" style = "margin-right: 0.625rem;">
								<h4 style = "margin-bottom:2;">
									</a> <a href = "https://zetamatic.com/" target = "_blank"  style = "text-decoration: none;" title = "<?php echo __(' Live Chat', 'checkout_address_autofill_for_woocommerce'); ?>"><img style = "height: 14px; width: 17px;" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDUwIDUwIiBoZWlnaHQ9IjUwcHgiIGlkPSJMYXllcl8xIiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCA1MCA1MCIgd2lkdGg9IjUwcHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxyZWN0IGZpbGw9Im5vbmUiIGhlaWdodD0iNTAiIHdpZHRoPSI1MCIvPjxwYXRoIGQ9Ik00NCwyMGMwLTEuMTA0LTAuODk2LTItMi0ycy0yLDAuODk2LTIsMiAgYzAsMC40NzYsMCwxNC41MjQsMCwxNWMwLDEuMTA0LDAuODk2LDIsMiwyczItMC44OTYsMi0yQzQ0LDM0LjUyNCw0NCwyMC40NzYsNDQsMjB6IiBmaWxsPSJub25lIiBzdHJva2U9IiMwMDAwMDAiIHN0cm9rZS1taXRlcmxpbWl0PSIxMCIgc3Ryb2tlLXdpZHRoPSIyIi8+PHBhdGggZD0iTTI4LDQ3YzEuMTA0LDAsMi0wLjg5NiwyLTJzLTAuODk2LTItMi0yICBjLTAuNDc2LDAtNC41MjQsMC01LDBjLTEuMTA0LDAtMiwwLjg5Ni0yLDJzMC44OTYsMiwyLDJDMjMuNDc2LDQ3LDI3LjUyNCw0NywyOCw0N3oiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzAwMDAwMCIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiBzdHJva2Utd2lkdGg9IjIiLz48cGF0aCBkPSJNOCwxOUM4LDkuNjExLDE1LjYxMSwyLDI1LDJzMTcsNy42MTEsMTcsMTciIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzAwMDAwMCIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiBzdHJva2Utd2lkdGg9IjIiLz48cGF0aCBkPSJNNDQsMjBjMi43NjIsMCw1LDMuMzU3LDUsNy41ICBjMCw0LjE0MS0yLjIzOCw3LjUtNSw3LjUiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzAwMDAwMCIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiBzdHJva2Utd2lkdGg9IjIiLz48cGF0aCBkPSJNNiwyMGMwLTEuMTA0LDAuODk2LTIsMi0yczIsMC44OTYsMiwyICBjMCwwLjQ3NiwwLDE0LjUyNCwwLDE1YzAsMS4xMDQtMC44OTYsMi0yLDJzLTItMC44OTYtMi0yQzYsMzQuNTI0LDYsMjAuNDc2LDYsMjB6IiBmaWxsPSJub25lIiBzdHJva2U9IiMwMDAwMDAiIHN0cm9rZS1taXRlcmxpbWl0PSIxMCIgc3Ryb2tlLXdpZHRoPSIyIi8+PHBhdGggZD0iTTYsMjBjLTIuNzYxLDAtNSwzLjM1Ny01LDcuNSAgQzEsMzEuNjQxLDMuMjM5LDM1LDYsMzUiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzAwMDAwMCIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiBzdHJva2Utd2lkdGg9IjIiLz48cGF0aCBkPSJNNDIsMzdjMCw1LTMsOC04LDhoLTQiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzAwMDAwMCIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiBzdHJva2Utd2lkdGg9IjIiLz48L3N2Zz4=" alt="LiveChat"></a>
									<a href="https://zetamatic.com/" target = "_blank" style = "text-decoration:none;" title = "<?php echo __('Chat with our expert team for any help or queries.', 'checkout_address_autofill_for_woocommerce'); ?>"><?php echo __(' Live Chat', 'checkout_address_autofill_for_woocommerce'); ?></a>
								</h4>
								
								</div>

								<div class = "zetamatic_support_livechat" style = "margin-right: 0.625rem;">
								<h4 style = "margin-bottom:2;">
									</a> <a href = "https://wordpress.org/plugins/checkout-address-autofill-for-woocommerce/" target = "_blank"  style = "text-decoration: none;" title = "<?php echo __(' Open a Ticket', 'checkout_address_autofill_for_woocommerce'); ?>"><img style = "height: 14px; width: 17px;" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDY0IDY0IiBoZWlnaHQ9IjY0cHgiIGlkPSJMYXllcl8xIiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCA2NCA2NCIgd2lkdGg9IjY0cHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxwYXRoIGQ9Ik02Mi45ODEsMjAuNTQ5bC0zLjU1Ny0zLjU3MWMtMS40MzEtMS40MzctMy42MzYtMC4yNjUtMy42MzYtMC4yNjVsLTAuMDA2LTAuMDA0ICBjLTIuMjk4LDAuOTU3LTUuMDQ0LDAuNTA2LTYuOTEtMS4zNjdjLTEuODY3LTEuODc2LTIuMzA4LTQuNjI4LTEuMzM3LTYuOTIzYzAuMDA5LTAuMDIyLDAuMDA5LTAuMDM2LDAuMDE5LTAuMDU5ICBjMC44My0yLjE5Ni0wLjM1LTMuNjM3LTAuNjk4LTQuMDAxbC0zLjU3NC0zLjU4OGMtMS41NjEtMS41NjctMy4yNTYtMC4xNDItMy42NzEsMC4yNTdMMS4wMzUsMzkuNDUgIGMtMi4xNDMsMi4xMzItMC4zNzYsMy44MzUtMC4zNzYsMy44MzVsNC4xMzksNC4xNTVjMCwwLDEuMDIxLDEuMTA0LDMuMDAzLDAuMzA0YzAuMDYzLTAuMDI1LDAuMTExLTAuMDM2LDAuMTY2LTAuMDUzICBjMi4zNTYtMS4xMzUsNS4yNjEtMC43NDQsNy4yMSwxLjIxMmMxLjg1NiwxLjg2NSwyLjMwNyw0LjU5NCwxLjM1OSw2Ljg4MmgwLjAwM2MwLDAtMS4yMDQsMi4yMzUsMC4yNDQsMy42ODhsMy42MjUsMy42NCAgYzAuMjU4LDAuMjU0LDIuMTYzLDEuOTY1LDQuNTQxLTAuNDAxbDM4LjMyOS0zOC4xNzNDNjMuODU2LDIzLjg2Niw2NC43NTgsMjIuMzMzLDYyLjk4MSwyMC41NDl6IE0yMy40NTYsNDUuMTk5bC0wLjMyOSwwLjMyNiAgYy0wLjM2OSwwLjM2OC0wLjc0NywwLjEzMi0wLjkzOS0wLjAzOWwtNC4yNDktNC4yNjdsLTEuMDcsMS4wNjdjLTAuNTQyLDAuNTQxLTEuMDA1LTAuMDE3LTEuMDA1LTAuMDE3bC0wLjE0Mi0wLjE0MyAgYzAsMC0wLjM4NS0wLjM0NiwwLjIwMS0wLjkyOGwzLjI1NC0zLjI0MWMwLDAsMC41MTItMC41NjYsMS0wLjA3OGwwLjExMSwwLjExMWMwLjM2NSwwLjM4OCwwLjEzNywwLjczOC0wLjAyLDAuOTA4bC0xLjEwNCwxLjEwMiAgbDQuMDg1LDQuMTAzQzIzLjc1Niw0NC42MTEsMjMuNjEzLDQ0Ljk5MywyMy40NTYsNDUuMTk5eiBNMjcuMDczLDQxLjU5NmwtMC4yNjYsMC4yNjVjLTAuNDE0LDAuNDEzLTAuOTU2LTAuMDYzLTEuMDg4LTAuMTg5ICBsLTQuNDg3LTQuNTA3YzAsMC0wLjYxOS0wLjYzNy0wLjExMS0xLjE0MWwwLjE4OS0wLjE4OGMwLDAsMC41MzgtMC41MDEsMS4yNzgsMC4yNDNsNC40MDUsNC40MjIgIEMyNy40OCw0MC45OTEsMjcuMjM1LDQxLjQwOSwyNy4wNzMsNDEuNTk2eiBNMzIuNTQ3LDM1Ljg3M2MtMC4yNzEsMC41MzUtMC42MiwxLjA0My0xLjA5NywxLjUyICBjLTAuODQsMC44MzctMS43MzYsMS4yMjEtMi42ODgsMS4xNTRjLTAuOTUzLTAuMDY0LTEuOTA0LTAuNTc0LTIuODU0LTEuNTI3Yy0wLjU5Ny0wLjYwMS0xLjAxMi0xLjIzMy0xLjI0My0xLjkwNSAgYy0wLjIzMS0wLjY2OS0wLjI2Mi0xLjMyNi0wLjA5Mi0xLjk3NnMwLjUyOS0xLjI0NiwxLjA3OS0xLjc5NWMwLjQ0LTAuNDM4LDAuOTkyLTAuNzcyLDEuNjAzLTEuMDQ1ICBjMC4yMDMtMC4wMzMsMC41NzctMC4wMzYsMC43NTUsMC4zNzlsMC4wNjksMC4xNjJjMC4zMDQsMC43MS0wLjU0NiwwLjk0Ny0wLjU0NiwwLjk0N2wwLjAwMSwwLjAwMyAgYy0wLjAzNSwwLjAxNy0wLjA3NCwwLjAzMS0wLjExLDAuMDQ3Yy0wLjI5NCwwLjEzOS0wLjU0NiwwLjMxNS0wLjc1NywwLjUyN2MtMC40NjEsMC40NTctMC42NDUsMC45ODctMC41NTMsMS41ODUgIGMwLjA5MiwwLjU5NywwLjQ0NiwxLjIwNCwxLjA2MiwxLjgyM2MxLjI4MiwxLjI4NiwyLjQwOCwxLjQ1LDMuMzc0LDAuNDg3YzAuMjgxLTAuMjc4LDAuNTUzLTAuNjg2LDAuODItMS4xNDYgIGMwLjAwMywwLDAuMDIsMC4wMDYsMC4wMiwwLjAwNnMwLjM1Mi0wLjYwNywwLjY4My0wLjI3NmwwLjIwNSwwLjIwN0MzMi42MjUsMzUuMzcxLDMyLjYwNCwzNS42ODEsMzIuNTQ3LDM1Ljg3M3ogTTM4LjM4OCwzMC4zMjYgIGwtMC41ODUsMC41ODNjLTAuMzY0LDAuMzYyLTAuOTM2LDAuMjI2LTAuOTM2LDAuMjI2bC0zLjM2Mi0wLjc5MmwtMC4xNDYsMC44ODZsMS4zODgsMS4zOTRjMC40OTgsMC40OTksMC4zODcsMC44ODQsMC4yMywxLjEwNCAgbC0wLjMwMywwLjI5OGMtMC40MjYsMC40MjctMC45MDcsMC4wNTQtMS4wODgtMC4xMTlsLTQuNTE3LTQuNTM1YzAsMC0wLjYzNi0wLjYzMi0wLjE3Ny0xLjA4N2wwLjI4Mi0wLjI4MSAgYzAuMTc3LTAuMTQ4LDAuNTk4LTAuMzc0LDEuMTUxLDAuMTgxbDEuOTY4LDEuOTc2TDMyLjA5NywyOWwtMC4zNC0zLjA2YzAsMC0wLjA1My0wLjQ1NywwLjI5NC0wLjgwM2wwLjQ1OC0wLjQ1NyAgYzAsMCwwLjU1NS0wLjU2LDAuNjQ3LDAuMjQ1YzAsMCwwLjAwMiwwLDAuMDAyLDAuMDAzbDAuNDM4LDMuNzZsNC41MywxLjAzN0MzOC4zNjgsMjkuNzkxLDM4Ljc1MywyOS45NjEsMzguMzg4LDMwLjMyNnogICBNNDIuNTI4LDI2LjMzbC0yLjIxMywyLjIwNWMtMC41NzYsMC41NzQtMS4xMTMsMC4xMzctMS4yNjYtMC4wMTFsLTQuNDY4LTQuNDg0YzAsMC0wLjU1NS0wLjc0NCwwLjAyOS0xLjMyN2wyLjE2NC0yLjE1NCAgYzAsMCwwLjQ1Ni0wLjUxLDAuODMzLTAuMTMxbDAuMTczLDAuMTcyYzAsMCwwLjUzMSwwLjQyNy0wLjAwNywwLjk2MmwtMS4xNzksMS4xNzNjLTAuNDUxLDAuNDUxLTAuMDAzLDAuODc1LTAuMDAzLDAuODc1ICBsMC4zNDgsMC4zNDZjMCwwLDAuNDcxLDAuNDkzLDEuMDA3LTAuMDQybDAuOTQ4LTAuOTQ1YzAsMCwwLjQ3MS0wLjQ4NSwwLjgzMy0wLjEyM2wwLjE0MSwwLjE0MiAgYzAuMzk2LDAuNDMyLDAuMjE5LDAuNzY3LDAuMDg0LDAuOTI1bC0wLjkxMiwwLjkwOWMtMC41NzEsMC41NjgtMC4yNDksMC45ODktMC4xNDcsMS4wOTVsMC42MjQsMC42MjcgIGMwLjE4OCwwLjE0NywwLjQ5NSwwLjI3NiwwLjgyMi0wLjA1bDEuMTY0LTEuMTZjMCwwLDAuNDc4LTAuNTE2LDAuNzkxLTAuMjAzbDAuMTk4LDAuMTk4QzQyLjkxMywyNS43NjQsNDIuNjg3LDI2LjE0OCw0Mi41MjgsMjYuMzMgIHogTTQ1LjcwNCwyMy4wNDFsLTAuMTkyLDAuMTg5Yy0wLjM4MSwwLjM3OS0wLjg1LTAuMDE3LTEuMDAyLTAuMTY1bC0zLjYyNS0zLjYzOWMtMC40Ny0wLjQ3MS0wLjg3NS0wLjE4LTEuMDE2LTAuMDQ5bC0wLjY1NywwLjY1NiAgYy0wLjQ1OCwwLjQ1Ny0wLjg4Ni0wLjAzOS0wLjg4Ni0wLjAzOWwtMC4xNzQtMC4xNzZjMCwwLTAuMzgyLTAuMzgsMC4wNzYtMC44MzdsMy4zNjktMy4zNTRjMC4yMDMtMC4xODMsMC42MTItMC40NTYsMC45NjMtMC4xMDQgIGwwLjEwOSwwLjExMWMwLjM4OSwwLjQxLDAuMTY1LDAuNzcyLDAuMDI0LDAuOTMxbC0wLjY3OCwwLjY3NGMtMC4wOTEsMC4wOTUtMC4zODksMC40ODIsMC4xNTYsMS4wMzFsMy41NjQsMy41NzkgIEM0NS45MzIsMjIuMDcxLDQ2LjIxMywyMi41NDIsNDUuNzA0LDIzLjA0MXoiIGZpbGw9IiMyNDFGMjAiLz48L3N2Zz4=" alt="LiveChat"></a>
									<a href="https://wordpress.org/support/plugin/checkout-address-autofill-for-woocommerce/" target = "_blank" style = "text-decoration:none;" title = "<?php echo __('Still need help? Submit a ticket and one of our support experts will get back to you as soon as possible.', 'checkout_address_autofill_for_woocommerce'); ?>"><?php echo __(' Open a Ticket', 'checkout_address_autofill_for_woocommerce'); ?></a>
								</h4>
								
								</div>
              				</div>
            			</div>
          			</tr>
					<!-- Google Api key -->
					<tr valign="top">

						<th scope="row">
							<?php echo __('Enter Your Google API Key', 'checkout_address_autofill_for_woocommerce'); ?>
						</th>

						<td>
							<input type="text" name="wc_af_api_key" value="<?php echo(get_option('wc_af_api_key')); ?>">
							<a href="https://zetamatic.com/docs/checkout-address-autofill-for-woocommerce/setup/" style="font-size:12px;" target="_blank"><?php echo __('Google API Key Setup Guide', 'checkout_address_autofill_for_woocommerce'); ?></a>
						</td>

					</tr>
				</table>
				<?php submit_button(); ?>

			</form>

			<?php
				global $active_tab;
				if( isset( $_GET[ 'tab' ] ) ) {
					$active_tab = $_GET[ 'tab' ];
				} // end if
				$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'wcaf-google-api-key-setting';
			?>

			<h2 class="nav-tab-wrapper">
				<?php do_action( 'wcaf_settings_tab_heading' ); ?>
			</h2>

			<form method="post" action="options.php" id="checkout-address-autocomplete-form">

				<?php do_action( 'wcaf_settings_tab_content' ); ?>

				<div class = "wcaf_settings_tab_content_save_button">
					<?php submit_button(); ?>

				</div>

			</form>

		</div><!-- /.wrap -->

		<?php
  	}
}
