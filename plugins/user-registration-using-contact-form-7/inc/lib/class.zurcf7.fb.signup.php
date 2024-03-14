<?php
/**
 * ZURCF7_Facebook_Signup Class
 *
 * Handles the plugin functionality.
 *
 * @package WordPress
 * @subpackage User Registration using Contact Form 7 PRO
 * @since 1.4
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

require_once( ZURCF7_DIR . '/inc/lib/zurcf7-fb-lib/src/Facebook/autoload.php' );

use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

if ( !class_exists( 'ZURCF7_Facebook_Signup' ) ) {

	/**
	 * The ZURCF7_Facebook_Signup class
	 */

	class ZURCF7_Facebook_Signup {

		function __construct() {
			
			// Facebook Signup Login Backend Tag
			add_action( 'wpcf7_admin_init', array( $this, 'action__wpcf7_admin_init' ), 15, 0 );

			// Facebook signup Login Frontend Show
			
			add_action( 'wpcf7_init', array( $this, 'action__wpcf7_init' ), 10, 0 );
			
			// Facebook Data Save Show
			add_action( 'init', array($this,'save_facebook_signup_data' ));

		}

		function password_generate($chars) {
			$data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
			return substr(str_shuffle($data), 0, $chars);
		}
		

		/**
		 * Facebook Signup Login Backend Tag
		 *
		 * @param [type] Facebook Signup Login Backend Tag
		 * @return void
		 */

		function action__wpcf7_admin_init() {
			$tag_generator = WPCF7_TagGenerator::get_instance();
			$tag_generator->add(
				'facebooksignup',
				__( 'Facebook Signup', 'facebook-sign-form' ),
				array( $this, 'wpcf7_tag_generator_facebook_sign' )
			);
		}

		/**
		 * Facebook Signup Login Backend Tag callback funcation
		 *
		 * @param [type]
		 * @return void
		 */
		function wpcf7_tag_generator_facebook_sign( $contact_form, $args = '' ) {

			$args = wp_parse_args( $args, array() );
			$type = $args['id'];
			$description = __( "Generate a form-tag for to display Facebook Signup form", 'facebook-sign-form' ); ?>
			
			<div class="control-box">
				<fieldset>
					<legend><?php echo esc_html( $description ); ?></legend>
					<table class="form-table">
						<tbody>
							<tr>
							<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'facebook-sign-form' ) ); ?></label></th>
							<td>
								<legend class="screen-reader-text"><input type="checkbox" name="required" value="on" checked="checked" /></legend>
								<input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
							</tr>
						</tbody>
					</table>
				</fieldset>
			</div>
			<div class="insert-box">
				<input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />
					<div class="submitbox">
						<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'facebook-sign-form' ) ); ?>" />
					</div>
				<br class="clear" />
			</div>
			<?php

		}

		/**
		 * Facebook Signup Login Backend Tag
		 *
		 * @param [type] Facebook Signup Login Backend Tag
		 * @return void
		 */
		function action__wpcf7_init() {
			wpcf7_add_form_tag(
				array( 'facebooksignup', 'facebooksignup*' ),
				array( $this, 'wpcf7_add_form_tag_facebook_signup' ),
				array( 'name-attr' => true )
			);
		}

		/**
		 * Facebook signup Login Frontend callback funcation
		 *
		 * @param [type] Facebook singup form.
		 * @return void
		 */
		
		function wpcf7_add_form_tag_facebook_signup( $tag ) {
			$current_form_id = $form_id = $output = '';
			$current_form_id = WPCF7_ContactForm::get_current();
			$form_id = $current_form_id->id();

			$zurcf7_fb_signup_app_id = (get_option('zurcf7_fb_signup_app_id')) ? get_option('zurcf7_fb_signup_app_id') : ' ';
			$zurcf7_fb_app_secret = (get_option('zurcf7_fb_app_secret')) ? get_option('zurcf7_fb_app_secret') : ' ';
			$callback_facebook = '?socialsignup=facebook';
            $site_callback_facebook = get_site_url().$callback_facebook;
			// Call Facebook API
			$fb = new Facebook(array(
				'app_id' => $zurcf7_fb_signup_app_id,
				'app_secret' => $zurcf7_fb_app_secret,
				'default_graph_version' => 'v3.2',
			));
			// Get redirect login helper
			$helper = $fb->getRedirectLoginHelper();
			
			// Try to get access token
			$permissions = ['email']; // Optional permissions
    		$loginURL = $helper->getLoginUrl($site_callback_facebook, $permissions);
			//$facebook_signup_image = wp_get_attachment_image_url( get_option('facebook_signup_logo'),'medium' ); 
			if (isset($_SERVER['HTTPS'])) {
				$output = '<a href="'.htmlspecialchars($loginURL).'&form_id='.$form_id.'"> <img src="'.ZURCF7_URL.'/assets/images/zurcf7-facebook.png" alt=""/> </a>'; 
			}
			return $output; 
		}

		/**
		 * Save Data Google Drive
		 *
		 * @param [type] Save Data Google Drive.
		 * @return void
		 */

		 function save_facebook_signup_data() {

			if ( ! isset( $_REQUEST['socialsignup'] ) || 'facebook' == $_REQUEST['socialsignup'] ){
				global $wpdb;
				$current_form_id = '';
				if(isset($_GET['form_id'])) {
					$current_form_id = $_GET['form_id'];
				}
				$site_url = get_site_url();
				$zurcf7_successurl_field = (get_option('zurcf7_successurl_field')) ? get_option('zurcf7_successurl_field') : get_option('zurcf7_successurl_field',"");
				$booking_url = get_permalink($zurcf7_successurl_field);
				$form_title = get_the_title($current_form_id);
				$zurcf7_userrole_field = (get_option('zurcf7_userrole_field')) ? get_option('zurcf7_userrole_field') : get_option('zurcf7_userrole_field',"");
				$zurcf7_skipcf7_email = get_option('zurcf7_skipcf7_email');
				$zurcf7_enable_sent_login_url = get_option( 'zurcf7_enable_sent_login_url');
				$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
				$login_url = get_site_url();
				
				$zurcf7_fb_signup_app_id = (get_option( 'zurcf7_fb_signup_app_id')) ? get_option( 'zurcf7_fb_signup_app_id') : " ";
				$zurcf7_fb_app_secret = (get_option( 'zurcf7_fb_app_secret')) ? get_option( 'zurcf7_fb_app_secret') : " ";
				$callback_facebook = '?socialsignup=facebook';
            	$site_callback_facebook = $site_url.$callback_facebook;
				$fb = new Facebook(array(
					'app_id' => $zurcf7_fb_signup_app_id,
					'app_secret' => $zurcf7_fb_app_secret,
					'default_graph_version' => 'v2.3',
					
					
				));
				$helper = $fb->getRedirectLoginHelper();

				if (isset($_GET['state'])) {
					$helper->getPersistentDataHandler()->set('state', $_GET['state']);
				}

				try {
					if(isset($_SESSION['facebook_access_token_news'])){
						$accessToken = $_SESSION['facebook_access_token_news'];
					}else{
						  $accessToken = $fb->getRedirectLoginHelper()->getAccessToken($site_callback_facebook);
					}
				} catch(FacebookResponseException $e) {
					$e->getMessage();
				} catch(FacebookSDKException $e) {
					$e->getMessage();
				}

				if(isset($accessToken)) {

					if(isset($_SESSION['facebook_access_token_news'])) {
						$fb->setDefaultAccessToken($_SESSION['facebook_access_token_news']);
					}else{
						// Put short-lived access token in session
						$_SESSION['facebook_access_token_news'] = (string) $accessToken;
						
						  // OAuth 2.0 client handler helps to manage access tokens
						$oAuth2Client = $fb->getOAuth2Client();
						
						// Exchanges a short-lived access token for a long-lived one
						$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token_news']);
						$_SESSION['facebook_access_token_news'] = (string) $longLivedAccessToken;
						
						// Set default access token to be used in script
						$fb->setDefaultAccessToken($_SESSION['facebook_access_token_news']);
					}
					
					// Redirect the user back to the same page if url has "code" parameter in query string
					
					try {
						$graphResponse = $fb->get('/me?fields=email');
						$fb_User = $graphResponse->getGraphUser();
					} catch(FacebookResponseException $e) {
						echo 'Graph returned an error: ' . $e->getMessage();
						session_destroy();
						exit;
					} catch(FacebookSDKException $e) {
						echo 'Facebook SDK returned an error: ' . $e->getMessage();
						exit;
					}
					$generate_password = $this->password_generate(7);
					$fb_email = !empty($fb_User['email']) ? $fb_User['email'] : '';
					$userdata = array(
						'user_login'	=>  wp_slash($fb_email),
						'user_pass'		=>  $generate_password,
						'user_email'	=>  wp_slash($fb_email),
						'role' => $zurcf7_userrole_field,
					);
					$user_id = wp_insert_user( $userdata );

					// Check if post already exist
					$query = $wpdb->prepare(
						'SELECT ID FROM ' . $wpdb->posts . '
						WHERE post_title = %s
						AND post_type = \''.ZURCF7_POST_TYPE.'\'',
						$fb_email
					);
					$wpdb->query( $query );

					if ( $wpdb->num_rows == 0 ) {
						$zurcf_post_id_post = wp_insert_post( array (
							'post_type'      => ZURCF7_POST_TYPE,
							'post_title'     => $fb_email, // email
							'post_status'    => 'publish',
							'comment_status' => 'closed',
							'ping_status'    => 'closed',
							'post_author'    => $user_id,
						) );
						
						add_post_meta( $zurcf_post_id_post, ZURCF7_META_PREFIX.'user_id', $user_id, true);
						add_post_meta( $zurcf_post_id_post, ZURCF7_META_PREFIX.'form_id', $current_form_id, true);
						add_post_meta( $zurcf_post_id_post, ZURCF7_META_PREFIX.'form_title', $form_title, true);
						add_post_meta( $zurcf_post_id_post, ZURCF7_META_PREFIX.'user_login', wp_slash($fb_email), true);
						add_post_meta( $zurcf_post_id_post, ZURCF7_META_PREFIX.'user_pass', wp_hash_password($generate_password), true);
						add_post_meta( $zurcf_post_id_post, ZURCF7_META_PREFIX.'user_email', wp_slash($fb_email), true);
						add_post_meta( $zurcf_post_id_post, ZURCF7_META_PREFIX.'role', $zurcf7_userrole_field, true);
						add_post_meta( $zurcf_post_id_post, ZURCF7_META_PREFIX.'type', 'Facebook', true);
						add_user_meta( $user_id, ZURCF7_META_PREFIX.'type', 'Facebook', true);
						
						if(!empty($zurcf7_enable_sent_login_url)) {
							// Email login details to user
							$login_url = wp_login_url();
							$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
							$message = "Welcome! Your login details are as follows:" . "\r\n";
							$message .= sprintf(__('Username: %s'), $fb_email) . "\r\n";
							$message .= sprintf(__('Password: %s'), $generate_password) . "\r\n";
							$message .= $login_url . "\r\n";
							wp_mail($fb_email, sprintf(__('[%s] Your username and password'), $blogname), $message);
							
						}
					}
					$creds = array(
						'user_login'    => $fb_email,
						'user_password' => $generate_password,
						'remember'      => true
					);
					$booking_base_url = (!empty($booking_url)) ? $booking_url : $site_url;
					wp_redirect($booking_base_url);
					exit();
					
				}
			}
		}
	}
	add_action( 'plugins_loaded', function() {
		ZURCF7()->lib = new ZURCF7_Facebook_Signup;
	} );
}

