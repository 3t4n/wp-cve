<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists( 'Wp_vcbaic_Usage_Feedback') ) {
	
	class Wp_vcbaic_Usage_Feedback {
		
		private $wpbot_version = '1.2.0';
		private $home_url = '';
		private $plugin_file = '';
		private $plugin_name = '';
		private $options = array();
		private $require_optin = true;
		private $include_goodbye_form = true;

		
		/**
		 * Class constructor
		 *
		 * @param $_home_url				The URL to the site we're sending data to
		 * @param $_plugin_file				The file path for this plugin
		 * @param $_options					Plugin options to track
		 * @param $_require_optin			Whether user opt-in is required (always required on WordPress.org)
		 * @param $_include_goodbye_form	Whether to include a form when the user deactivates
		 * @param $_marketing				Marketing method:
		 *									0: Don't collect email addresses
		 *									1: Request permission same time as tracking opt-in
		 *									2: Request permission after opt-in
		 */
		public function __construct( 
			$_plugin_file,
			$_home_url,
			
			$_require_optin=true,
			$_include_goodbye_form=true) {

			$this->plugin_file = $_plugin_file;
			$this->home_url = 'webbuilders03@gmail.com';
			$this->plugin_name = basename( $this->plugin_file, '.php' );

			$this->require_optin = $_require_optin;
			$this->include_goodbye_form = $_include_goodbye_form;


			// Deactivation hook
			register_deactivation_hook( $this->plugin_file, array( $this, 'deactivate_this_plugin' ) );
			
			// Get it going
			$this->init();
			
		}
		
		public function init() {
			
			// Deactivation
			add_filter( 'plugin_action_links_' . plugin_basename( $this->plugin_file ), array( $this, 'filter_action_links' ) );
			add_action( 'admin_footer-plugins.php', array( $this, 'goodbye_ajax' ) );
			add_action( 'wp_ajax_goodbye_form', array( $this, 'goodbye_form_callback' ) );
			add_action('admin_enqueue_scripts',array($this, 'admin_enqueue_scripts'));

		}
		
		
		public function admin_enqueue_scripts(){
			wp_register_style( 'qcopd-custom-admin-css', WB_VC_BAIC_URL . '/assets/css/admin-style.css');
			wp_enqueue_style( 'qcopd-custom-admin-css' );

			$css='';
			$css .= '.button.qcvcbaic-promo-link {
		      color: #ff0000;
		      font-weight: normal;
		      margin-left: 0;
		      margin-top: 1px !important;
		    }
		    .clear{ clear: both; }';
			
			$css .= ".wpb-form-active .wpb-goodbye-form-bg{z-index:99;background:rgba(0,0,0,.5);position:fixed;top:0;left:0;width:100%;height:100%}.wpb-goodbye-form-wrapper{position:relative;z-index:999;display:none}.wpb-form-active .wpb-goodbye-form-wrapper{display:block}.wpb-goodbye-form{display:none}.wpb-form-active .wpb-goodbye-form{position:fixed;max-width:400px;background:#fff;white-space:normal;z-index:99;top:50%;left:50%;transform:translate(-50%,-50%);border-radius:5px}.wpb-goodbye-form-head{background:#7a00aa;color:#fff;padding:8px 18px;text-align:center;border-radius:5px 5px 0 0}.wpb-goodbye-form-body{padding:8px 18px;color:#444}.deactivating-spinner{display:none}.deactivating-spinner .spinner{float:none;margin:4px 4px 0 18px;vertical-align:bottom;visibility:visible}.wpb-goodbye-form-footer{padding:8px 18px}";

			wp_add_inline_style( 'qcopd-custom-admin-css', $css );
		}


		// In theme's functions.php or plug-in code:

		function set_content_type(){
			return "text/html";
		}
		
		
		/**
		 * Send the data to the home site
		 *
		 * @since 1.0.0
		 */
		public function send_data( $body ) {
			$message = '';
			foreach($body as $key=>$value){
				
				if($key=='active_plugins'){
					$message .='<p> <b>'.$key.'</b>: '.(implode(', ',$value)).' </p>';
				}
				elseif($key=='inactive_plugins'){
					$message .='<p> <b>'.$key.'</b>: '.(implode(', ',$value)).' </p>';
				}else{
					$message .='<p> <b>'.$key.'</b>: '.$value.' </p>';
				}
				
			}
			
			    $title   = esc_html('Visual Composer Before After Slider Plugin Deactivation Notice');
				$headers = array('From: WPBakery Before After <mailer@just-a-fake-from-address.com>');
				
				add_filter( 'wp_mail_content_type', array($this, 'set_content_type') );
				$email = wp_mail($this->home_url, $title, $message, $headers);
				remove_filter('wp_mail_content_type', array($this, 'set_content_type'));

				return $email;

		}
		
		/**
		 * Here we collect most of the data
		 * 
		 * @since 1.0.0
		 */
		public function get_data() {
	
			// Use this to pass error messages back if necessary
			$body['message'] = '';
	
			// Use this array to send data back
			$body = array();


	
			/**
			 * Get our plugin data
			 * Currently we grab plugin name and version
			 * Or, return a message if the plugin data is not available
			 * @since 1.0.0
			 */
			$plugin = $this->plugin_data();
			if( empty( $plugin ) ) {
				// We can't find the plugin data
				// Send a message back to our home site
				$body['message'] .= esc_html__( 'We can\'t detect any plugin information. This is most probably because you have not included the code in the plugin main file.', 'wpchatbot' );
				$body['status'] = esc_html('Data not found'); // Never translated
			} else {
				if( isset( $plugin['Name'] ) ) {
					$body['plugin'] = sanitize_text_field( $plugin['Name'] );
				}
				if( isset( $plugin['Version'] ) ) {
					$body['version'] = sanitize_text_field( $plugin['Version'] );
				}

			}

			// Return the data
			return $body;
	
		}
		
		/**
		 * Return plugin data
		 * @since 1.0.0
		 */
		public function plugin_data() {
			// Being cautious here
			if( ! function_exists( 'get_plugin_data' ) ) {
				include ABSPATH . '/wp-admin/includes/plugin.php';
			}
			// Retrieve current plugin information
			$plugin = get_plugin_data( $this->plugin_file );
			return $plugin;
		}

		/**
		 * Deactivating plugin
		 * @since 1.0.0
		 */
		public function deactivate_this_plugin() {

			$body = $this->get_data();
			$body['status'] = 'Deactivated'; // Never translated
			$body['deactivated_date'] = date('Y-m-d');
			$body['url'] = home_url();
			$body['contact1'] = get_option( 'admin_email' );
			
			// Add deactivation form data
			if( false !== get_option( 'vcbaic_deactivation_reason_' . $this->plugin_name ) ) {
				$body['deactivation_reason'] = get_option( 'vcbaic_deactivation_reason_' . $this->plugin_name );
				delete_option('vcbaic_deactivation_reason_' . $this->plugin_name);
			}
			if( false !== get_option( 'vcbaic_deactivation_details_' . $this->plugin_name ) ) {
				$body['deactivation_details'] = get_option( 'vcbaic_deactivation_details_' . $this->plugin_name );
				delete_option('vcbaic_deactivation_details_' . $this->plugin_name);
			}

			if( false !== get_option( 'vcbaic_deactivation_email_' . $this->plugin_name ) ) {
				$body['deactivation_email'] = get_option( 'vcbaic_deactivation_email_' . $this->plugin_name );
				delete_option('vcbaic_deactivation_email_' . $this->plugin_name);
			}

			if( false !== get_option( 'vcbaic_deactivation_main_reason_' . $this->plugin_name ) ) {
				$body['deactivation_main_reason'] = get_option( 'vcbaic_deactivation_main_reason_' . $this->plugin_name );
				delete_option('vcbaic_deactivation_main_reason_' . $this->plugin_name);
			}
			
			if(isset($body['deactivation_reason']) or isset($body['deactivation_details']))
				$this->send_data( $body );
			

		}
		
		/**
		 * Filter the deactivation link to allow us to present a form when the user deactivates the plugin
		 * @since 1.0.0
		 */
		public function filter_action_links( $links ) {

			if( isset( $links['deactivate'] ) && $this->include_goodbye_form ) {
				$deactivation_link = $links['deactivate'];
				// Insert an onClick action to allow form before deactivating
				$deactivation_link = str_replace( '<a ', '<div class="wpb-goodbye-form-wrapper"><span class="wpb-goodbye-form" id="wpb-goodbye-form-' . esc_attr( $this->plugin_name ) . '"></span></div><a onclick="javascript:event.preventDefault();" id="wpb-goodbye-link-vcbaic-' . esc_attr( $this->plugin_name ) . '" ', $deactivation_link );
				$links['deactivate'] = $deactivation_link;
			}
			return $links;
		}
		
		/*
		 * Form text strings
		 * These are non-filterable and used as fallback in case filtered strings aren't set correctly
		 * @since 1.0.0
		 */
		public function form_default_text() {
			$form = array();
			$form['heading'] = __( 'Sorry to see you go', 'wpchatbot' );
			$form['body'] = __( 'Before you deactivate the plugin, would you quickly give us your reason for doing so?', 'wpchatbot' );
			$form['options'] = array(
				__( 'Found a Bug', 'wpchatbot' ),
				__( 'Need More Features', 'wpchatbot' ),
				__( 'I found a different plugin that I like better.', 'wpchatbot' ),
				__( ' It does not do what I need.', 'wpchatbot' ),
				__( 'Deactivating Temporarily', 'wpchatbot' ),

			);
			$form['email'] = __( 'Please provide your email so we can contact you if needed.', 'wpchatbot' );
			$form['details'] = __( 'Please provide some details so we can improve the plugin', 'wpchatbot' );
			return $form;
		}
		
		/**
		 * Form text strings
		 * These can be filtered
		 * The filter hook must be unique to the plugin
		 * @since 1.0.0
		 */
		public function form_filterable_text() {
			$form = $this->form_default_text();
			return apply_filters( 'wpbot_form_text_' . esc_attr( $this->plugin_name ), $form );
		}
		
		/**
		 * Form text strings
		 * These can be filtered
		 * @since 1.0.0
		 */
		public function goodbye_ajax() {
			
			// Get our strings for the form
			$form = $this->form_filterable_text();
			if( ! isset( $form['heading'] ) || ! isset( $form['body'] ) || ! isset( $form['options'] ) || ! is_array( $form['options'] ) || ! isset( $form['details'] ) ) {
				// If the form hasn't been filtered correctly, we revert to the default form
				$form = $this->form_default_text();
			}
			// Build the HTML to go in the form
			$html = '<div class="wpb-goodbye-form-head"><strong>' . esc_html( $form['heading'] ) . '</strong></div>';
			$html .= '<div class="wpb-goodbye-form-body"><p>' . esc_html( $form['body'] ) . '</p>';
			if( is_array( $form['options'] ) ) {
				$html .= '<div class="wpb-goodbye-options"><p>';
				foreach( $form['options'] as $option ) {
					$html .= '<input type="radio" name="wpb-goodbye-options" id="' . str_replace( " ", "", esc_attr( $option ) ) . '" value="' . esc_attr( $option ) . '"> <label for="' . str_replace( " ", "", esc_attr( $option ) ) . '">' . esc_attr( $option ) . '</label><br>';
				}
				$html .= '</p><div id="wpb_additional_content" style="display:none;"><label for="wpb-goodbye-reasons">' . esc_html( $form['email'] ) .'</label><br><input type="email" name="wpb-goodbye-email" id="wpb-goodbye-email" value="'.get_option('admin_email').'" /> (Optional)';
				
				$html .= '<br><label for="wpb-goodbye-reasons">' . esc_html( $form['details'] ) .'</label><textarea name="wpb-goodbye-reasons" id="wpb-goodbye-reasons" rows="2" style="width:100%"></textarea></div>';
				$html .= '</div><!-- .wpb-goodbye-options -->';
			}
			$html .= '</div><!-- .wpb-goodbye-form-body -->';
			$html .= '<p class="deactivating-spinner"><span class="spinner"></span> ' . __( 'Submitting form', 'wpbot-plugin' ) . '</p>';

			?>
			<div class="wpb-goodbye-form-bg"></div>


			<script>
				jQuery(document).ready(function($){
					 $('input[type=radio]').on('change', function() { 
						if($(this).val()=='Deactivating Temporarily' || $(this).val()=='Upgrading to Pro'){
							$('#wpb_additional_content').hide();
						}else{
							$('#wpb_additional_content').show();
						}
						
					 });

					$('#wpb-goodbye-link-vcbaic-<?php echo esc_attr( $this->plugin_name ); ?>').on('click',function(){
						var url = document.getElementById('wpb-goodbye-link-vcbaic-<?php echo esc_attr( $this->plugin_name ); ?>');
						$('body').toggleClass('wpb-form-active');
						$('#wpb-goodbye-form-<?php echo esc_attr( $this->plugin_name ); ?>').fadeIn();
						$('#wpb-goodbye-form-<?php echo esc_attr( $this->plugin_name ); ?>').html( '<?php echo $html; ?>' + '<div class="wpb-goodbye-form-footer"><p><a id=\'wpb-submit-form\' class=\'button button-primary\' href=\'#\'>Submit and Deactivate</a>&nbsp;<a class=\'secondary button\' href=\''+url+'\'>Just Deactivate</a></p></div>');
						$('#wpb-submit-form').on('click', function(e){
							
							$('#wpb-goodbye-form-<?php echo esc_attr( $this->plugin_name ); ?> .wpb-goodbye-form-body').fadeOut();
							$('#wpb-goodbye-form-<?php echo esc_attr( $this->plugin_name ); ?> .wpb-goodbye-form-footer').fadeOut();
							
							$('#wpb-goodbye-form-<?php echo esc_attr( $this->plugin_name ); ?> .deactivating-spinner').fadeIn();
							e.preventDefault();
							var values = new Array();
							$.each($('input[name=\'wpb-goodbye-options[]\']:checked'), function(){
								values.push($(this).val());
							});
							var email = $('#wpb-goodbye-email').val();
							var details = $('#wpb-goodbye-reasons').val();
							var deactivate_main_reason = $('input[name="wpb-goodbye-options"]:checked').val();
							var data = {
								'action': 'goodbye_form',
								'values': values,
								'main_reason': deactivate_main_reason,
								'details': details,
								'email': email,
								'security': '<?php echo wp_create_nonce ( 'wpbot_goodbye_form' ); ?>',
								'dataType': 'json'
							}
							
							$.post(
								ajaxurl,
								data,
								function(response){
									
									window.location.href = url;
								}
							);
						});
						
						$('.wpb-goodbye-form-bg').on('click',function(){
							$('#wpb-goodbye-form-<?php echo esc_attr( $this->plugin_name ); ?>').fadeOut();
							$('body').removeClass('wpb-form-active');
						});
					});
				});
			</script>
		<?php }
		
		/**
		 * AJAX callback when the form is submitted
		 * @since 1.0.0
		 */
		public function goodbye_form_callback() {
			check_ajax_referer( 'wpbot_goodbye_form', 'security' );
			if( isset( $_POST['values'] ) ) {
				$values = json_encode( wp_unslash( $_POST['values'] ) );
				update_option( 'vcbaic_deactivation_reason_' . $this->plugin_name, $values );
			}
			if( isset( $_POST['details'] ) ) {
				$details = sanitize_text_field( $_POST['details'] );
				update_option( 'vcbaic_deactivation_details_' . $this->plugin_name, $details );
			}

			if( isset( $_POST['email'] ) ) {
				$email = sanitize_text_field( $_POST['email'] );
				update_option( 'vcbaic_deactivation_email_' . $this->plugin_name, $email );
			}

			if( isset( $_POST['main_reason'] ) ) {
				$main_reason = sanitize_text_field( $_POST['main_reason'] );
				update_option( 'vcbaic_deactivation_main_reason_' . $this->plugin_name, $main_reason );
			}

			echo 'success';
			wp_die();
		}
		
	}
	
}


