<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.mailmunch.com
 * @since      2.0.0
 *
 * @package    Mailmunch
 * @subpackage Mailmunch/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mailmunch
 * @subpackage Mailmunch/admin
 * @author     MailMunch <info@mailmunch.com>
 */
class Mailmunch_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The ID of this plugin's 3rd party integration.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $integration_name    The ID of this plugin's 3rd party integration.
	 */
	private $integration_name;	

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The MailMunch Api object.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $mailmunch_api    The MailMunch Api object.
	 */
	private $mailmunch_api;


	public function __construct( $plugin_name, $integration_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->integration_name = $integration_name;		
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mailmunch_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mailmunch_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mailmunch-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mailmunch_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mailmunch_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mailmunch-admin.js', array( 'jquery' ), $this->version, false );

		// Register the new script
		wp_register_script( 'mailchimp_mailmunch_script', plugin_dir_url( __FILE__ ) . 'js/mailmunch-admin.js', array( 'jquery' ), $this->version, false );
		// enqueue it
    wp_enqueue_script( 'mailchimp_mailmunch_script' );
		// localize it for ajax calls
		wp_localize_script( 'mailchimp_mailmunch_script', 'mailmunch_nonces', array(
			'delete_widget' => wp_create_nonce('mailmunch_delete_widget'),
			'change_email_status' => wp_create_nonce('mailmunch_change_email_status'),
			'delete_email' => wp_create_nonce('mailmunch_delete_email'),
		));

	}

	public function sign_up() {
		$this->initiate_api();
		$email = $_POST['email'];
		$password = $_POST['password'];
		echo json_encode($this->mailmunch_api->signUpUser($email, $password, $_POST['site_name'], $_POST['site_url']));
		exit;
	}

	public function sign_in() {
		$this->initiate_api();
		$email = $_POST['email'];
		$password = $_POST['password'];
		echo json_encode($this->mailmunch_api->signInUser($email, $password));
		exit;
	}

	public function delete_widget() {
		// Check if nonce is set and valid and if the current user has 'manage_options' capability (typically administrators).
    if ( isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'mailmunch_delete_widget') && current_user_can('manage_options') ) {
			$this->initiate_api();
			echo json_encode($this->mailmunch_api->deleteWidget($_POST['widget_id']));
    } else {
			echo json_encode(array('error' => 'Permission denied.')); // Optionally, you can return an error message.
    }
    exit;
	}

	public function change_email_status() {
		// Check if nonce is set and valid and if the current user has 'manage_options' capability (typically administrators).
		if ( isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'mailmunch_change_email_status') && current_user_can('manage_options') ) {
			$this->initiate_api();
			echo json_encode($this->mailmunch_api->changeEmailStatus($_POST['email_id'], $_POST['email_status']));
		} else {
			echo json_encode(array('error' => 'Permission denied.')); // Optionally, you can return an error message.
		}
		exit;
	}

	public function delete_email() {
		// Check if nonce is set and valid and if the current user has 'manage_options' capability (typically administrators).
		if ( isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'mailmunch_delete_email') && current_user_can('manage_options') ) {
			$this->initiate_api();
			echo json_encode($this->mailmunch_api->deleteEmail($_POST['email_id']));
		} else {
			echo json_encode(array('error' => 'Permission denied.')); // Optionally, you can return an error message.
		}
		exit;
	}

	/**
	 * Register menu for the admin area
	 *
	 * @since    2.0.0
	 */
	public function menu() {
		add_options_page( $this->integration_name, $this->integration_name, 'manage_options', MAILMUNCH_SLUG, array($this, 'get_dashboard_html'));
		add_menu_page( $this->integration_name, $this->integration_name, 'manage_options', MAILMUNCH_SLUG, array($this, 'get_dashboard_html'), plugins_url( 'img/icon.png', __FILE__ ), 105.786);

		add_submenu_page( MAILMUNCH_SLUG, $this->integration_name, 'Forms', 'manage_options', MAILMUNCH_SLUG, array($this, 'get_dashboard_html') );
		
		$landingPagesEnabled = get_option(MAILMUNCH_PREFIX. '_landing_pages_enabled');
		if (empty($landingPagesEnabled) || $landingPagesEnabled == 'yes') {
			add_submenu_page( MAILMUNCH_SLUG, $this->integration_name. ' Landing Pages', 'Landing Pages', 'manage_options', 'edit.php?post_type='. MAILMUNCH_POST_TYPE );
		}
		
		add_submenu_page( MAILMUNCH_SLUG, $this->integration_name. ' Autoresponders', 'Autoresponders', 'manage_options', MAILMUNCH_SLUG. '-autoresponders', array($this, 'autoresponders_page') );
		add_submenu_page( MAILMUNCH_SLUG, $this->integration_name. ' Settings', 'Settings', 'manage_options', MAILMUNCH_SLUG. '-settings', array($this, 'settings_page') );
	}

	/**
	 * Activation redirect for admin area
	 *
	 * @since    2.0.2
	 */
	public function activation_redirect() {
		if (get_option(MAILMUNCH_PREFIX. '_activation_redirect', 'true') == 'true') {
			update_option(MAILMUNCH_PREFIX. '_activation_redirect', 'false');
			wp_redirect(esc_url(admin_url('admin.php?page='. MAILMUNCH_SLUG)));
			exit();
		}
	}

	/**
	 * Check and store installation/activation date
	 *
	 * @since    2.0.2
	 */
	public function check_installation_date() {
		$activation_date = get_option( MAILMUNCH_PREFIX. '_activation_date' );
		if (!$activation_date) {
			add_option( MAILMUNCH_PREFIX. '_activation_date', strtotime( "now" ) );
		}
	}

	/**
	 * Review notice after two weeks of usage
	 *
	 * @since    2.0.2
	 */
	public function review_us_notice() {
		$show_notice = true;
		$past_date = strtotime( '-14 days' );
		$activation_date = get_option( MAILMUNCH_PREFIX. '_activation_date' );
		$notice_dismissed = get_option( MAILMUNCH_PREFIX. '_dismiss_review_notice' );
		if ($notice_dismissed == 'true') {
			$show_notice = false;
		} elseif (!in_array(get_current_screen()->base , array( 'dashboard' , 'post' , 'edit' )) && strpos(get_current_screen()->base , MAILMUNCH_SLUG) == false) {
			$show_notice = false;
		} elseif (!current_user_can( 'install_plugins' )) {
			$show_notice = false;
		} elseif ( !$activation_date || $past_date < $activation_date ) {
			$show_notice = false;
		}
		if ($show_notice) {
			$review_url = 'https://wordpress.org/support/plugin/'. MAILMUNCH_PLUGIN_DIRECTORY. '/reviews/#new-post';
			$dismiss_url = esc_url_raw( add_query_arg( MAILMUNCH_PREFIX. '_dismiss_review_notice', '1', admin_url() ) );
			$review_message = '<div class="mailmunch-review-logo"><img src="'.plugins_url( 'admin/img/logo.png', dirname(__FILE__) ) .'" /></div>';
			$review_message .= sprintf( __( "You have been using <strong>%s</strong> for a few weeks now. We hope you are enjoying the features. Please consider leaving us a nice review. Reviews help people find our plugin and lets you provide us with useful feedback which helps us improve." , MAILMUNCH_SLUG ), $this->plugin_name );
			$review_message .= "<div class='mailmunch-buttons'>";
			$review_message .= sprintf( "<a href='%s' target='_blank' class='button-secondary'><span class='dashicons dashicons-star-filled'></span>" . __( "Leave a Review" , MAILMUNCH_SLUG ) . "</a>", $review_url );
			$review_message .= sprintf( "<a href='%s' class='button-secondary'><span class='dashicons dashicons-no-alt'></span>" . __( "Dismiss" , MAILMUNCH_SLUG ) . "</a>", $dismiss_url );
			$review_message .= "</div>";
?>
			<div class="mailmunch-review-notice">
				<?php echo $review_message; ?>
			</div>
<?php
		}
	}

	/**
	 * Dismiss review notice
	 *
	 * @since    2.0.2
	 */
	public function dismiss_review_notice() {
		if ( isset( $_GET[MAILMUNCH_PREFIX. '_dismiss_review_notice'] ) ) {
			add_option( MAILMUNCH_PREFIX. '_dismiss_review_notice', 'true' );
		}
	}

	/**
	 * Adds settings link for plugin
	 *
	 * @since    2.0.0
	 */
	public function settings_link($links) {
	  $settings_link = '<a href="admin.php?page='.MAILMUNCH_SLUG.'">Settings</a>';
	  array_unshift($links, $settings_link);
	  return $links;
	}

	public function initiate_api() {
		if (empty($this->mailmunch_api)) {
			$this->mailmunch_api = new Mailmunch_Api();
		}
		return $this->mailmunch_api;
	}

	/**
	 * Settings Page
	 *
	 * @since    2.0.8
	 */
	public function settings_page() {
    $this->initiate_api();

    // Add nonce check
    if (isset($_POST['mailmunch_settings_nonce']) && wp_verify_nonce($_POST['mailmunch_settings_nonce'], 'mailmunch_settings_action')) {
			// Nonce is valid; process the form data
			if (isset($_POST['auto_embed'])) {
				$this->mailmunch_api->setSetting('auto_embed', $_POST['auto_embed']);
			}
			if (isset($_POST['landing_pages_enabled'])) {
				$this->mailmunch_api->setSetting('landing_pages_enabled', $_POST['landing_pages_enabled']);
			}
    }

    require_once(plugin_dir_path(__FILE__) . 'partials/mailmunch-settings.php');
	}

	public function init() {
		$step = isset($_GET['step']) ? $_GET['step'] : '';
		if ($step == 'sign_out') {
			$this->initiate_api();
			$this->mailmunch_api->signOutUser();
			$url = admin_url('admin.php?page='. MAILMUNCH_SLUG);
			wp_redirect($url);
		}
	}
	
	/**
	 * Autoresponders Page
	 *
	 * @since    3.0.7
	 */
	public function autoresponders_page() {
		$this->initiate_api();
		$currentStep = 'autoresponders';
		require_once(plugin_dir_path( __FILE__ ) . 'partials/mailmunch-tabs.php');
		require_once(plugin_dir_path(__FILE__) . 'partials/mailmunch-autoresponders.php');
	}
	
	/**
	 * Dashboard Widget
	 *
	 * @since    3.0.0
	 */
	public function dashboard_setup() {
		add_meta_box( 'mailmunch_dashboard_widget', 'MailMunch', array($this , 'dashboard_widget'), 'dashboard', 'normal', 'high');
	}
	
	/**
	 * Dashboard Widget
	 *
	 * @since    3.0.0
	 */
	public function dashboard_widget() {
		$landingPagesEnabled = get_option(MAILMUNCH_PREFIX. '_landing_pages_enabled');
		$html = '<div class="mailmunch-dash-widget">';
		$html .= '<div class="mailmunch-dash-widget-inner">';
		$html .= '<p>Welcome to MailMunch! The #1 plugin to grow your email list.</p>';
		$html .= '<div>';
		$html .= '<a class="mailmunch-dash-option" href="'. admin_url('admin.php?page='.MAILMUNCH_SLUG) .'"><span class="dashicons dashicons-editor-table"></span><span class="mailmunch-dash-text">Forms & Popups</span></a>';
		if (empty($landingPagesEnabled) || $landingPagesEnabled == 'yes') {
			$html .= '<a class="mailmunch-dash-option" href="'. admin_url('edit.php?post_type='.MAILMUNCH_POST_TYPE) .'"><span class="dashicons dashicons-admin-page"></span><span class="mailmunch-dash-text">Landing Pages</span></a>';
		}
		$html .= '<a class="mailmunch-dash-option" href="'. admin_url('admin.php?page='.MAILMUNCH_SLUG.'-autoresponders') .'"><span class="dashicons dashicons-email-alt"></span><span class="mailmunch-dash-text">Autoresponders</span></a>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		echo $html;
	}

	/**
	 * Get Dashboard HTML
	 *
	 * @since    2.0.0
	 */
	public function get_dashboard_html() {
		$this->initiate_api();
		$currentStep = 'forms';
		require_once(plugin_dir_path( __FILE__ ) . 'partials/mailmunch-tabs.php');
		require_once(plugin_dir_path( __FILE__ ) . 'partials/mailmunch-admin-display.php');
		require_once(plugin_dir_path( __FILE__ ) . 'partials/mailmunch-modals.php');
	}

}
