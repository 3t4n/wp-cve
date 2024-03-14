<?php
/*  
 * Security Antivirus Firewall (wpTools S.A.F.)
 * http://wptools.co/wordpress-security-antivirus-firewall
 * Version:           	2.3.5
 * Build:             	77229
 * Author:            	WpTools
 * Author URI:        	http://wptools.co
 * License:           	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Date:              	Sat, 01 Dec 2018 19:09:28 GMT
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ) exit;

class wptsafExtensionGoogleCaptcha extends wptsafAbstractExtension{	
	protected static $instance;
	protected $blogSettings;

	public function __construct(){
		$this->name = 'google-captcha';
		$this->title = __('Google Captcha (reCaptcha)', 'wptsaf_security');
		$this->description = __("Very effective module to protect your website against spammers and bots. Implemented with multi site mode support. With this tools you get reliable protection of your admin section from bots and spammers. Google reCaptcha uses advanced risk analysis engine and adaptive CAPTCHAs to keep bots from engaging in abusive activities on your site.", 'wptsaf_security');
		$this->blogSettings = new wptsafExtensionGoogleCaptchaBlogSettings($this);

		parent::__construct();
	}

	public static function getInstance(){
		if (!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function init(){
		parent::init();
		add_action('init', array($this, 'actionInit'));
	}


	public function actionInit(){
		$blogId = get_current_blog_id();
		$blogSettings = $this->getBlogSettings()->getRowByBlogId($blogId);

		if (is_wp_error($blogSettings)) {
			return;
		}

		if (!isset($blogSettings['is_active']) || !$blogSettings['is_active']) {
			return;
		}

		add_action('login_enqueue_scripts', array($this, 'gglcptchAddStyles'));
		add_action('login_form', array($this, 'gglcptchLoginDisplay'));
		add_action('authenticate', array($this, 'gglcptchLoginCheck'), 21, 1);
	}

	public function getBlogSettings(){
		return $this->blogSettings;
	}

	public function gglcptchAddStyles() {
		wp_enqueue_style(
			'wptsaf-gglcptch',
			WPTSAF_URL . $this->getExtensionDir() . 'assets/css/gglcptch.css',
			false,
			WPTSAF_VERSION
		);
	}

	public function gglcptchLoginDisplay() {
		?>
			<style type="text/css" media="screen">
				#loginform,
				#lostpasswordform,
				#registerform {
					width: 302px !important;
				}
				#login_error,
				.message {
					width: 322px !important;
				}
				#loginform .gglcptch,
				#lostpasswordform .gglcptch,
				#registerform .gglcptch {
					margin-bottom: 10px;
				}
			</style>
		<?php

		echo $this->gglcptchDisplay();

		return true;
	}

	public function gglcptchDisplay() {
		$blogId = get_current_blog_id();
		$blogSettings = $this->getBlogSettings()->getRowByBlogId($blogId);
		$publickey  = $blogSettings['key'];
		$privatekey = $blogSettings['secret_key'];
		$content = '';

		if (!$privatekey || !$publickey) {
			return $content;
		}

		$content .= '<div class="gglcptch gglcptch_v2">';
		$content .= '<div id="gglcptch_recaptcha_login" class="gglcptch_recaptcha"></div>
		<noscript>
			<div style="width: 302px;">
				<div style="width: 302px; height: 422px; position: relative;">
					<div style="width: 302px; height: 422px; position: absolute;">
						<iframe src="https://www.google.com/recaptcha/api/fallback?k=' . $publickey . '" frameborder="0" scrolling="no" style="width: 302px; height:422px; border-style: none;"></iframe>
					</div>
				</div>
				<div style="border-style: none; bottom: 12px; left: 25px; margin: 0px; padding: 0px; right: 25px; background: #f9f9f9; border: 1px solid #c1c1c1; border-radius: 3px; height: 60px; width: 300px;">
					<textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px !important; height: 40px !important; border: 1px solid #c1c1c1 !important; margin: 10px 25px !important; padding: 0px !important; resize: none !important;"></textarea>
				</div>
			</div>
		</noscript>';
		$content .= '</div>';

		/* register reCAPTCHA script */
		wp_register_script('wptsaf_gglcptch_api','https://www.google.com/recaptcha/api.js', false, false, true );
		add_action( 'login_footer', array($this, 'gglcptchAddScripts'));

		return $content;
	}

	public function gglcptchAddScripts() {
		$blogId = get_current_blog_id();
		$blogSettings = $this->getBlogSettings()->getRowByBlogId($blogId);
		
		$this->gglcptchRemoveDublicateScripts();

		wp_enqueue_script(
			'wptsaf_gglcptch_script',
			WPTSAF_URL . $this->getExtensionDir() . 'assets/js/script.js',
			array( 'jquery', 'wptsaf_gglcptch_api' ),
			false,
			true
		);

		wp_localize_script( 'wptsaf_gglcptch_script', 'gglcptch', array(
			'options' => array(
				'version' => 'v2',
				'sitekey' => $blogSettings['key'],
				'theme'   => 'light',
				'error'   => "<strong>" . __('Warning', 'wptsaf_security') . ":</strong>&nbsp;" . __('It has been found more than one reCAPTCHA in current form. In this case reCAPTCHA will not work properly. Please remove all unnecessary reCAPTCHA blocks.', 'wptsaf_security')
			),
			'vars' => array(
				'ajaxurl'   => admin_url( 'admin-ajax.php' ),
				'error_msg' => __('Error: You have entered an incorrect reCAPTCHA value.', 'wptsaf_security'),
				'nonce'     => wp_create_nonce('gglcptch_recaptcha_nonce')
			)
		) );
	}

	public function gglcptchRemoveDublicateScripts() {
		global $wp_scripts;

		if ( ! is_object( $wp_scripts ) || empty( $wp_scripts ) )
			return false;

		foreach ( $wp_scripts->registered as $script_name => $args ) {
			if ( preg_match( "|google\.com/recaptcha/api\.js|", $args->src ) && 'wptsaf_gglcptch_api' != $script_name )
				/* remove a previously enqueued script */
				wp_dequeue_script( $script_name );
		}
	}

	public function gglcptchLoginCheck($user) {

		$result = $this->gglcptchCheck();

		$this->getLog()->insertRow(array(
			'user_id' => ($user && $user instanceof WP_User) ? $user->ID : 0,
			'status' => $result['response']
				? wptsafExtensionGoogleCaptchaLog::STATUS_SUCCESS
				: wptsafExtensionGoogleCaptchaLog::STATUS_FAILED,
			'reason' => $result['reason']
		));

		if ( ! $result['response'] ) {
			if ( $result['reason'] == 'ERROR_NO_KEYS' ) {
				return $user;
			}

			$error_message = sprintf( '<strong>%s</strong>: %s', __('Error', 'wptsaf_security'), __( 'You have entered an incorrect reCAPTCHA value.', 'wptsaf_security'));

			if ( $result['reason'] == 'VERIFICATION_FAILED' ) {
				wp_clear_auth_cookie();
				return new WP_Error( 'gglcptch_error', $error_message );
			}

			if ( $result['reason'] == 'RECAPTCHA_EMPTY_RESPONSE' ) {
				wp_clear_auth_cookie();
				return new WP_Error( 'gglcptch_error', $error_message );
			}

			if ( isset( $_REQUEST['log'] ) && isset( $_REQUEST['pwd'] ) ) {
				return new WP_Error( 'gglcptch_error', $error_message );
			} else {
				return $user;
			}
		} else {
			return $user;
		}
	}

	public function gglcptchCheck( $debug = false ) {
		$blogId = get_current_blog_id();
		$blogSettings = $this->getBlogSettings()->getRowByBlogId($blogId);
		$publickey	=	$blogSettings['key'];
		$privatekey	=	$blogSettings['secret_key'];

		if ( ! $privatekey || ! $publickey ) {
			return array(
				'response' => false,
				'reason'   => 'ERROR_NO_KEYS'
			);
		}

		$gglcptch_remote_addr = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP );

		if ( ! isset( $_POST["g-recaptcha-response"] ) ) {
			return array(
				'response' => false,
				'reason'   => 'RECAPTCHA_NO_RESPONSE'
			);
		} elseif ( empty( $_POST["g-recaptcha-response"] ) ) {
			return array(
				'response' => false,
				'reason'   => 'RECAPTCHA_EMPTY_RESPONSE'
			);
		}

		$response = $this->gglcptchGetResponse( $privatekey, $gglcptch_remote_addr );

		if ( isset( $response['success'] ) && !! $response['success'] ) {
			return array(
				'response' => true,
				'reason' => ''
			);
		} else {
			return array(
				'response' => false,
				'reason' => $debug ? $response['error-codes'] : 'VERIFICATION_FAILED'
			);
		}
	}

	public function gglcptchGetResponse( $privatekey, $remote_ip ) {
		$args = array(
			'body' => array(
				'secret'   => $privatekey,
				'response' => stripslashes( esc_html( $_POST["g-recaptcha-response"] ) ),
				'remoteip' => $remote_ip,
			),
			'sslverify' => false
		);
		$resp = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', $args );
		return json_decode( wp_remote_retrieve_body( $resp ), true );
	}

	public function activate(){
		parent::activate();
		$this->getBlogSettings()->createTable();
		$this->getBlogSettings()->init();
	}

	public function uninstall(){
		parent::uninstall();
		$this->getBlogSettings()->dropTable();
	}
}
