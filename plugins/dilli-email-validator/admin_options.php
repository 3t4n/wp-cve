<?php
if( !class_exists( 'Email_Validation_Dilli_Admin' ) )
{
	class Email_Validation_Dilli_Admin
	{
		private $options = NULL;

		public function __construct()
		{
			$this->options = get_option( 'dilli_labs_email_validator' );

			add_action( 'admin_menu' , array( &$this, 'plugin_menu' ) );
			add_action( 'admin_init' , array( &$this, 'plugin_settings' ) );
			add_action( 'admin_notices' , array( &$this, 'admin_messages' ) );
		}

		//Display admin notices
		public function admin_messages()
		{
			global $email_validation_dilli;
			//Displayed if no API key is entered
			if( !isset( $this->options['dilli_pubkey_api'] ) || empty( $this->options['dilli_pubkey_api'] ) )
				echo '<div class="updated"><p>' . sprintf( __( 'The %s will not work until a %s is entered. Please configure it %s.', $email_validation_dilli->slug ), '<a href="'.admin_url( 'options-general.php?page=' . $email_validation_dilli->slug ).'">Dilli Email Validator plugin</a>', 'Dilli Email Validation API key', '<a href="'.admin_url( 'options-general.php?page=' . $email_validation_dilli->slug ).'">here</a>' ) . '</p></div>';
		}
		
		public function settings_link( $links )
		{
			global $email_validation_dilli;
			array_unshift( $links, '<a href="'.admin_url( 'options-general.php?page=' . $email_validation_dilli->slug ).'">' . __( 'Settings', $email_validation_dilli->slug ) . '</a>' );			
			return $links;
		}

		//Hook in and create a menu
		public function plugin_menu()
		{
			global $email_validation_dilli;
			add_filter( 'plugin_action_links_' . $email_validation_dilli->basename, array( &$this, 'settings_link' ) );
			$plugin_page = add_options_page( __( 'Email Validation Settings', $email_validation_dilli->slug ), __( 'Email Validation', $email_validation_dilli->slug ), 'manage_options', $email_validation_dilli->slug, array( &$this, 'plugin_options' ) );
			add_action( 'admin_head-' . $plugin_page, array( &$this, 'plugin_panel_styles' ) );
			add_action( 'admin_footer-' . $plugin_page, array( &$this, 'plugin_panel_scripts' ) ); //Add AJAX to the footer of the options page
		}

		//Create the options page
		public function plugin_settings()
		{
			add_action( 'wp_ajax_dilli_api', array( &$this, 'dilli_api_ajax_callback') ); //AJAX to verify the API key
			add_action( 'wp_ajax_test_email', array( &$this, 'test_email_ajax_callback') ); //AJAX for demo email validation

			global $email_validation_dilli;
			register_setting( $email_validation_dilli->slug.'_options', 'dilli_labs_email_validator', array( &$this, 'sanitize_input' ) );
			add_settings_section( $email_validation_dilli->slug.'_settings', '', array( &$this, 'dummy_cb'), $email_validation_dilli->slug);
			add_settings_field('dilli_pubkey_api','Dilli Email Validation API key', array( &$this, 'api_field' ), $email_validation_dilli->slug, $email_validation_dilli->slug.'_settings', array( 'label_for' => 'dilli_pubkey_api' ) ); //Public API key field
			add_settings_field('dilli_whitelist','Whitelist', array( &$this, 'whitelist_field' ), $email_validation_dilli->slug, $email_validation_dilli->slug.'_settings', array( 'label_for' => 'dilli_whitelist' ) ); //Whitelist field
		}

		public function plugin_panel_styles()
		{
			global $email_validation_dilli;
			echo '<style type="text/css">#icon-'.$email_validation_dilli->slug.'{background:transparent url(\'' . plugin_dir_url( __FILE__ ) . 'screen-icon.png\') no-repeat;}</style>';
		}

		//Add AJAX to the footer
		public function plugin_panel_scripts()
		{
			global $email_validation_dilli;
?>
<script type="text/javascript">
jQuery(document).ready(
	jQuery('#dilli_api_verify').click (function($) 
	{
		if (jQuery.trim(jQuery('#dilli_pubkey_api').val()).length == 0) {
			jQuery('#api_output').html('<?php _e( 'This field cannot be empty', $email_validation_dilli->slug ); ?>');
			return;
		}

		var data = {
			action: 'dilli_api',
			api: jQuery('#dilli_pubkey_api').val()
		};

		jQuery('#api_output').html('<?php _e( 'Checking', $email_validation_dilli->slug ); ?>...');
		jQuery('#api_output').css("cursor","wait");
		jQuery('#dilli_api_verify').attr("disabled","disabled");
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#api_output').html(response);
			jQuery('#api_output').css("cursor","default");
			jQuery('#dilli_api_verify').removeAttr("disabled");
		}
		);
	}
));

jQuery(document).ready(
	jQuery('#validate_email').click (function($)
	{
		if (jQuery.trim(jQuery('#sample_email').val()).length == 0) {
			jQuery('#email_output').html('<?php _e( 'Please enter an email address to validate', $email_validation_dilli->slug ); ?>');
			return;
		}

		var data = {
			action: 'test_email',
			email_id: jQuery('#sample_email').val()
		};
		jQuery('#email_output').html('<?php _e( 'Checking', $email_validation_dilli->slug ); ?>...');
		jQuery('#email_output').css("cursor","wait");
		jQuery('#validate_email').attr("disabled","disabled");
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#email_output').html(response);
			jQuery('#email_output').css("cursor","default");
			jQuery('#validate_email').removeAttr("disabled");
		}
		);
	}
));
</script>
<?php	}

		//AJAX Callback function for validating the Public API key
		public function dilli_api_ajax_callback()
		{
			global $email_validation_dilli;

			$args = array(
				'sslverify' => false,
				'timeout' => 30
			);

			//We are using a static email here as only the API is validated
			$response = wp_remote_request( "https://deva.dillilabs.com/api/".$_POST['api']."/email/info%40dillilabs.com", $args );

			//A Network error has occurred
			if( is_wp_error($response) )
				echo '<span style="color:red">' . $response->get_error_message() . '</span>';
			
			elseif( isset($response->errors['http_request_failed']) )
			{
				echo '<span style="color:red">' . __( 'The following error occurred when validating the key.', $email_validation_dilli->slug ) . '<br />';
				foreach($response->errors['http_request_failed'] as $http_errors)
					echo $http_errors;
				echo '</span>';
			}

			elseif( '200' == $response['response']['code'] )
				echo '<span style="color:green">' . __( 'API Key is valid. Click Save Changes button below.', $email_validation_dilli->slug ) . '</span>';

			//Invalid API as Dilli returned 401 Unauthorized
			elseif( '401' == $response['response']['code'] )
				echo '<span style="color:red">' . sprintf( __( 'Invalid API Key. Error code: %s %s', $email_validation_dilli->slug ), $response['response']['code'], $response['response']['message'] ) . '</span>';

			//A HTTP error other than 401 has occurred
			else
				echo '<span style="color:red">' . sprintf( __( 'A HTTP error occurred when validating the API. Error code: %s %s', $email_validation_dilli->slug ), $response['response']['code'], $response['response']['message'] ) . '</span>';

			die();
		}

		//AJAX Callback function for demo email validation
		public function test_email_ajax_callback()
		{
			global $email_validation_dilli;

			if( !filter_var( $_POST['email_id'], FILTER_VALIDATE_EMAIL ) )
			{
				echo '<span style="color:red">' . __( 'The format of the email address is invalid.', $email_validation_dilli->slug ) . '</span>';
				die();
			}

			//Someone tries validating without entering the Public API key
			if( !isset( $this->options['dilli_pubkey_api'] ) || empty( $this->options['dilli_pubkey_api'] ) )
			{
				echo '<span style="color:red">' . __( 'Please enter your Dilli Email Validation API key and click Save Settings.', $email_validation_dilli->slug ) . '</span>';
				die();
			}

			// handle whitelist
            if(isset($this->options['dilli_whitelist'])){
                $whitelist_comma_separated_str = $this->options['dilli_whitelist'];
                //ensure its not empty                
                if(!empty($whitelist_comma_separated_str)){
                    // convert to array
                    $whitelist_arr = explode (",", $whitelist_comma_separated_str);
                    if(in_array($_POST['email_id'], $whitelist_arr)){
                        echo '<span style="color:green">' . __( 'Address is valid', $email_validation_dilli->slug ) . '</span>';
						die();
                    }
                }
            }

			$args = array(
				'sslverify' => false				
			);
			$response = wp_remote_request( "https://deva.dillilabs.com/api/".$this->options['dilli_pubkey_api']."/email/" . urlencode( $_POST['email_id'] ), $args );

			if( is_wp_error($response) )
			{
				echo '<span style="color:red">' . $response->get_error_message() . '</span>';
				die();
			} elseif( '200' == $response['response']['code'] ) {
				if(is_array($response) && $response["body"] ==  "false") {
					echo '<span style="color:red">' . __( 'Address is invalid', $email_validation_dilli->slug ) . '</span>';
				} else {
					echo '<span style="color:green">' . __( 'Address is valid', $email_validation_dilli->slug ) . '</span>';
				}
			}
			//API key is invalid so email couldn't be verified
			elseif( '401' == $response['response']['code'] )
				echo '<span style="color:red">Invalid API Key.</span>';
			die();
		}

		//Validate user input in the admin panel
		public function sanitize_input( $input )
		{		
			if( !empty( $input['dilli_pubkey_api'] ) )
			{
				$input['dilli_pubkey_api'] = trim( $input['dilli_pubkey_api'] );
				preg_match_all( '/[0-9a-zA-Z-]/', $input['dilli_pubkey_api'], $matches );
				$input['dilli_pubkey_api'] = implode( $matches[0] );
			}

			if( !empty( $input['dilli_whitelist'] ) )
			{
				$input['dilli_whitelist'] = trim( $input['dilli_whitelist'] );
			}

			return $input;
		}

		//Create the Public API field
		public function api_field()
		{
			$api_key = ( (isset($this->options['dilli_pubkey_api']) && !empty($this->options['dilli_pubkey_api'])) ? $this->options['dilli_pubkey_api'] : '' );
			echo '<input class="regular_text code" id="dilli_pubkey_api" name="dilli_labs_email_validator[dilli_pubkey_api]" size="40" type="text" value="'.$api_key.'" required />
				<input id="dilli_api_verify" type="button" value="Verify API Key" /><br />
				<div id="api_output"></div>
				<p class="description">' . sprintf( __( 'Enter your Dilli Email Validation API Key. Sign-up <a href="https://deva.dillilabs.com/register" target="_blank">here</a> to get it instantly.')) . '</p>';
		}

		//Create the Public API field
		public function whitelist_field()
		{
			$whitelist = ( (isset($this->options['dilli_whitelist']) && !empty($this->options['dilli_whitelist'])) ? $this->options['dilli_whitelist'] : '' );
			echo '<input class="regular_text code" id="dilli_whitelist" name="dilli_labs_email_validator[dilli_whitelist]" size="100" type="text" value="'.$whitelist.'" placeholder="Ex: job@example.com,jane@example.com" />				
				<p class="description">' . sprintf( __( 'Comma-seperated list of email addresses that are considered valid.')) . '</p>';
		}

		//HTML of the plugin options page
		public function plugin_options()
		{
			global $email_validation_dilli;
		?>
			<div class="wrap">
			<h2><?php _e( 'Email Validation Settings', $email_validation_dilli->slug); ?></h2>
			<p><?php printf( __( 'This plugin requires a sign up with Dilli Email Validation API (DEVA) service. %sLearn More.%s', $email_validation_dilli->slug ), '<a href="https://www.dillilabs.com/products/email-validation-api/" target="_blank">', '</a>' ); ?></p>
			<form method="post" action="options.php">
			<?php settings_fields( $email_validation_dilli->slug . '_options' );
			do_settings_sections( $email_validation_dilli->slug );
			submit_button(); ?>
			</form>
			<?php if( isset( $this->options['dilli_pubkey_api'] ) && !empty( $this->options['dilli_pubkey_api'] ) ): ?>
			<h2 class="title"><?php _e( 'Email Validation Demo', $email_validation_dilli->slug ); ?></h2>
			<p><?php _e( 'You can use this form to see how Dilli validates email addresses.', $email_validation_dilli->slug ); ?></p>
			<label for="sample_email">Email:</label><input style="margin-left: 20px" class="regular_text code" type="text" id="sample_email" size="40"/>
			<input type="button" id="validate_email" value="Validate Email" />
			<div id="email_output" style="font-size:20px;padding:10px 0 0 50px"></div>
			<h2 class="title"><?php _e( 'Tracking Dashboard', $email_validation_dilli->slug ); ?></h2>
			<p><?php printf( __( 'Track ongoing validations from DEVA dashboard. Click %s to go to DEVA dashboard.', $email_validation_dilli->slug ), '<a href="https://deva.dillilabs.com" target="_blank">here</a>' ); ?></p>
			<div><p style="font-size:24px"><?php printf( __( 'If you find this plugin useful please consider giving it a %sfive star%s rating.', $email_validation_dilli->slug ), '<a href="https://wordpress.org/support/plugin/'.$email_validation_dilli->slug.'/reviews/#new-post" target="_blank">', '</a>' ); ?></p></div>
			<?php endif; ?>
			</div>
		<?php
		}

		public function dummy_cb() {} //Empty callback for the add_settings_section() function
	}
	
	$email_validation_dilli_admin = new Email_Validation_Dilli_Admin();
}
