<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.shost.vn
 * @since      1.0
 *
 * @package    AIO_Tools
 * @subpackage AIO_Tools/admin
 */
// If this file is called directly, abort.
use AIOTools\W2W_Notice_Manager;
use AIOTools\W2W_Utils;


if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    AIO_Tools
 * @subpackage AIO_Tools/admin
 * @author     W2W Corp <info@shost.vn>
 */
class AIO_Tools_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->load_dependencies_frameworks();
		
		add_filter('plugin_action_links_'. W2W_BASE_NAME, [ $this,'w2w_add_plugin_page_settings_link'], 10, 2);
		
		add_action( 'admin_bar_menu', [ $this, 'w2w_add_admin_bar_links' ], 90 );

		//add_action( 'init', [ $this, 'w2w_set_notices' ] );

		$this->create_settings_page();

		add_action( 'admin_enqueue_scripts', 'add_thickbox' );
		
		add_action('wp_ajax_w2wSmtpCheckHandler', [ $this,'w2wSmtpCheckHandler' ]);
		add_action('wp_ajax_nopriv_w2wSmtpCheckHandler', [ $this,'w2wSmtpCheckHandler' ]);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, W2W_URL . 'admin/assets/css/aio-tools-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'mobile', W2W_URL . 'admin/assets/css/responsive.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, W2W_URL . 'admin/assets/js/aio-tools-admin.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name , 'w2w_smtp_param', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'message' => array(
				'sending' => __('Sending...','w2wvn'),
				'success' => __('Success! Please check your email inbox.','w2wvn'),
				'error' => __('The following error occured: ','w2wvn'),
				'alertdata' => __('You have entered an invalid email address!','w2wvn'),
			),
		) );
	}

	public function load_dependencies_frameworks() {
		require_once W2W_LIB_PATH . 'codestar-framework/codestar-framework.php';		
		require_once W2W_LIB_PATH . 'announce4wp/announce4wp-client.php';		
	}

	public function create_settings_page() {		
		// Check core class for avoid errors
		if ( class_exists( 'CSF' ) ) {
			
			require_once W2W_ADMIN_PATH . 'partials/w2w-create-options.php';
			//require_once W2W_ADMIN_PATH . 'partials/w2w-dashboard-section.php';
			require_once W2W_ADMIN_PATH . 'partials/w2w-contact-button-section.php';
			//require_once W2W_ADMIN_PATH . 'partials/w2w-smtp-section.php';
			require_once W2W_ADMIN_PATH . 'partials/w2w-woocommerce-section.php';
			require_once W2W_ADMIN_PATH . 'partials/w2w-header-footer-section.php';
			require_once W2W_ADMIN_PATH . 'partials/w2w-tools-section.php';

			/* BEGIN Section: Flatsome Manager */
			//if(w2w_check_active_theme('flatsome')){}
			/* END Section: Flatsome Manager */
		}
	}

	public function w2w_add_admin_bar_links( WP_Admin_Bar $admin_bar ) {

		if ( current_user_can( 'manage_options' ) ) {

			$admin_bar->add_menu( [
				'id'    => 'aio-tools',
				'title' => 'AIO Tools',
				'href'  => admin_url( 'admin.php?page=w2w-settings' ),
				'meta'  => [
					'target' => '_self',
					'html'   => '<style>#wpadminbar #wp-admin-bar-aio-tools .ab-item{background:url("' . W2W_URL . 'admin/assets/images/favicon.png") no-repeat 5px center;padding-left:30px;}#wpadminbar #wp-admin-bar-aio-tools .ab-item:hover{color:yellow;}</style>',
				],
			] );
			
		}
	}
	
	public function w2w_set_notices() {		
		if ( W2W_Utils::is_plugin_active( 'wp-mail-smtp/wp_mail_smtp.php' ) ) {
			W2W_Notice_Manager::display_notice( 'smtp_plugin', '<p>' . sprintf( __( 'It looks like <strong>WP Mail SMTP</strong> is active on your site. <strong>WP Mail SMTP</strong> feature may cause conflict with %1$s.', 'w2wvn' ), W2W_PLUGIN_NAME ) . '</p>', 'warning' );
		}
		
	}
	function w2w_add_plugin_page_settings_link( $links ) {
		$w2w_links[] = '<a href="' .
			admin_url( 'admin.php?page=w2w-settings' ) .
			'">' . __('Settings') . '</a>';

		return $w2w_links + $links;
	}
	function w2wSmtpCheckHandler(){
		$recipient = sanitize_text_field($_POST['your_email']);
		$subject = __('AIO Tool: HTML Test email to ', 'w2wvn' ) . $recipient;
		$headers = array('Content-Type: text/html; charset=UTF-8');
		ob_start();
		$message = $this->w2w_get_email_message_html();
		ob_end_clean();
		try {
			$result = wp_mail($recipient, $subject, $message, $headers);
		} catch (Exception $e) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		}
		$smtp_debug = ob_get_clean();		
		wp_die();
	}
	private function w2w_get_email_message_html() {

		ob_start();
		?>
		<!doctype html>
		<html lang="en">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width">
			<title><?php echo W2W_OWNER_NAME ?> SMTP Test Email</title>
			<style type="text/css">@media only screen and (max-width: 599px) {table.body .container {width: 95% !important;}.header {padding: 15px 15px 12px 15px !important;}.header img {width: 200px !important;height: auto !important;}.content, .aside {padding: 30px 40px 20px 40px !important;}}</style>
		</head>
		<body style="height: 100% !important; width: 100% !important; min-width: 100%; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; -webkit-font-smoothing: antialiased !important; -moz-osx-font-smoothing: grayscale !important; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; padding: 0; margin: 0; Margin: 0; font-size: 14px; mso-line-height-rule: exactly; line-height: 140%; background-color: #f1f1f1; text-align: center;">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" class="body" style="border-collapse: collapse; border-spacing: 0; vertical-align: top; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; height: 100% !important; width: 100% !important; min-width: 100%; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; -webkit-font-smoothing: antialiased !important; -moz-osx-font-smoothing: grayscale !important; background-color: #f1f1f1; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; padding: 0; margin: 0; Margin: 0; text-align: left; font-size: 14px; mso-line-height-rule: exactly; line-height: 140%;">
			<tr style="padding: 0; vertical-align: top; text-align: left;">
				<td align="center" valign="top" class="body-inner wp-mail-smtp" style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; padding: 0; margin: 0; Margin: 0; font-size: 14px; mso-line-height-rule: exactly; line-height: 140%; text-align: center;">
					<!-- Container -->
					<table border="0" cellpadding="0" cellspacing="0" class="container" style="border-collapse: collapse; border-spacing: 0; padding: 0; vertical-align: top; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; width: 600px; margin: 0 auto 30px auto; Margin: 0 auto 30px auto; text-align: inherit;">
						<!-- Header -->
						<tr style="padding: 0; vertical-align: top; text-align: left;">
							<td align="center" valign="middle" class="header" style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; margin: 0; Margin: 0; font-size: 14px; mso-line-height-rule: exactly; line-height: 140%; text-align: center; padding: 30px 30px 22px 30px;">
								<img src="<?php echo W2W_URL; ?>/public/assets/images/aio-tools.png" width="250" alt="WP Mail SMTP Logo" style="outline: none; text-decoration: none; max-width: 100%; clear: both; -ms-interpolation-mode: bicubic; display: inline-block !important; width: 250px;">
							</td>
						</tr>
						<!-- Content -->
						<tr style="padding: 0; vertical-align: top; text-align: left;">
							<td align="left" valign="top" class="content" style="word-wrap: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; hyphens: auto; border-collapse: collapse !important; vertical-align: top; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; margin: 0; Margin: 0; text-align: left; font-size: 14px; mso-line-height-rule: exactly; line-height: 140%; background-color: #ffffff; padding: 60px 75px 45px 75px; border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; border-left: 1px solid #ddd; border-top: 3px solid #28f;">
								<div class="success" style="text-align: center;">
									<p class="check" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; padding: 0; font-size: 14px; mso-line-height-rule: exactly; line-height: 140%; margin: 0 auto 16px auto; Margin: 0 auto 16px auto; text-align: center;">
										<img src="<?php echo W2W_URL; ?>/public/assets/images/icon-check.png" width="70" alt="Success" style="outline: none; text-decoration: none; max-width: 100%; clear: both; -ms-interpolation-mode: bicubic; display: block; margin: 0 auto 0 auto; Margin: 0 auto 0 auto; width: 50px;">
									</p>
									<p class="text-extra-large text-center congrats" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; padding: 0; mso-line-height-rule: exactly; line-height: 140%; font-size: 20px; text-align: center; margin: 0 0 20px 0; Margin: 0 0 20px 0;">
										Congrats, test email was sent successfully!
									</p>
									<p class="text-large" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; padding: 0; text-align: left; mso-line-height-rule: exactly; line-height: 140%; margin: 0 0 15px 0; Margin: 0 0 15px 0; font-size: 16px;">
										Thank you for trying out <?php echo W2W_OWNER_NAME ?>. We're on a mission to make sure that your emails actually get delivered.
									</p>
									
									<p class="signature" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; padding: 0; font-size: 14px; mso-line-height-rule: exactly; line-height: 140%; text-align: left; margin: 20px 0 0 0; Margin: 20px 0 0 0;">
										Thanks,
									</p>
									<p style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #444; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-weight: normal; padding: 0; text-align: left; font-size: 14px; mso-line-height-rule: exactly; line-height: 140%; margin: 0 0 15px 0; Margin: 0 0 15px 0;">
										<strong><?php echo W2W_OWNER_NAME ?>.</strong>
									</p>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		</body>
		</html>

		<?php
		$message = ob_get_clean();

		return $message;
	}
}	