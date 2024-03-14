<?php

if (!defined('WEBTOTEM_INIT') || WEBTOTEM_INIT !== true) {
	if (!headers_sent()) {
		header('HTTP/1.1 403 Forbidden');
	}
	die("Protected By WebTotem!");
}

/**
 * Plugin initializer.
 *
 */
class WebTotemInterface extends WebTotem {

	/**
	 * Execute pre-checks before every page.
	 *
	 * @return void
	 */
	public static function startupChecks() {

        /** Hide or show WP version */
		if (WebTotemOption::getPluginSettings('hide_wp_version')) {
			WebTotemOption::hideWPVersion();

            add_filter('style_loader_src', 'WebTotemOption::replaceVersion');
            add_filter('script_loader_src', 'WebTotemOption::replaceVersion');

		}

		$_page = WebTotemRequest::get('page');
		if(strpos($_page, 'wtotem') === 0 ) {
			$composer_autoload = WEBTOTEM_PLUGIN_PATH . '/vendor/autoload.php';
			if ( file_exists( $composer_autoload ) ) {
				require_once $composer_autoload;
			}
		}

		$_page = WebTotemRequest::get('page');
		if(strpos($_page, 'wtotem') === 0){

			if(!WebTotemOption::isActivated()){
				// Checking the old version of options.
				WebTotemOption::checkOldOptions();
			}

			WebTotemOption::multisiteCheck();

			if(!WebTotemOption::isActivated() and $_page !== 'wtotem_activation') {
				// If the plugin is not activated by the API key, then redirect to the activation page.
				wp_safe_redirect( WebTotem::adminURL('admin.php?page=wtotem_activation') );
				exit;
			}
			elseif (WebTotemOption::isActivated() and ($_page === 'wtotem_activation' or $_page === 'wtotem')){
				// If the plugin is activated by the API key, then redirect to the main page.
				if(self::isMultiSite() and is_super_admin()){
					// Main page is all sites page.
					wp_safe_redirect( WebTotem::adminURL('admin.php?page=wtotem_all_sites') );
				} else {
					// Main page is dashboard page.
					wp_safe_redirect( WebTotem::adminURL('admin.php?page=wtotem_dashboard') );
				}
				exit;
			}
			elseif(WebTotemOption::isActivated()) {
				// Checking whether agents are installed, if they are not installed, then install.
				self::checkAgents();
			}
		}

		// Check if the plugin version has changed.
		WebTotemAgentManager::checkVersion();

		$sapi = @php_sapi_name();
		if( $sapi != "cli" ) {
			if ($waf = WebTotemOption::getOption("waf_file")) {
				$include_waf_file = ABSPATH . '/_include_' . $waf;

				if (is_file($include_waf_file) && is_readable($include_waf_file)) {
					include_once $include_waf_file;
				}
			}
		}
	}

	/**
	 * Checking whether agents are installed, if they are not installed, then install.
	 */
	private static function checkAgents(){

		$api_key = WebTotemOption::getOption('api_key');

		$host = WebTotemAPI::siteInfo();

		if ($api_key && array_key_exists('id', $host)) {

			// Install Agent Manager if it was not previously installed.
			$am_installed = WebTotemAgentManager::checkInstalledService('am');
			if (!$am_installed['file_status']) {

				$am_was_installed = WebTotemAgentManager::amInstall();

				if (!$am_was_installed) {
					WebTotemOption::setOptions(['am_installed' => FALSE]);
				}
			}

		}
	}

	/**
	 * When adding a new site, add it to the WebTotem platform.
	 */
	public static function addNewSite($new_site){
        $domain = untrailingslashit($new_site->domain . $new_site->path);

        WebTotemAPI::addMultiSiteNewSites([$domain]);
	}

	/**
	 * Verify the nonce of the previous page after a form submission.
	 *
	 * @return bool True if the nonce is valid, false otherwise.
	 */
	public static function checkNonce() {
		if (!empty($_POST)) {
			$name = 'wtotem_page_nonce';
			$value = WebTotemRequest::post($name);

			if (!$value || !wp_verify_nonce($value, $name)) {
				WebTotemOption::setNotification('error', __('The WordPress CSRF check failed. The submitted form is missing an important unique code. Go back and try again.', 'wtotem'));
				return false;
			}
		}

		return true;
	}

    /**
     * Add 2fa to the profile form.
     *
     * @return void
     */
    public static function add2faProfileForm(){

        if(!WebTotemLogin::isTwoFactorEnabled()){ return; }

        if ( isset( $_GET['user_id'] ) ) {
            if( !current_user_can( 'manage_options' ) ){
                return;
            }
            $user_id = (int) $_GET['user_id'];
            $user = get_user_by( 'id', $user_id );
        } else {
            $user = wp_get_current_user();
        }

        $current_user = wp_get_current_user();

        if ( ! is_a( $user, '\WP_User' ) || ! is_a( $current_user, '\WP_User' ) ) {
            return;
        }

        $composer_autoload = WEBTOTEM_PLUGIN_PATH . '/vendor/autoload.php';
        if ( file_exists( $composer_autoload ) ) {
            require_once $composer_autoload;
        }

        $template = new WebTotemTemplate();

        $build[] = [
            'template' => 'two_factor_user_profile_modal',
            'variables' => [
                'two_factor' => WebTotemLogin::getTwoFactorData($user),
                'user_id' => $user_id ?? $user->ID,
                'can_manage_options' => current_user_can( 'manage_options' )
            ],
        ];

        $page_content = $template->arrayRender($build);
        echo $page_content;
    }

	/**
	 * Authentication.
	 *
	 * @return mixed
	 */
	public static function wt_authenticate($user, $username = null, $password = null) {

		if(WebTotemCaptcha::isEnabled()) {
			if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
				return $user;
			}
			$token = WebTotemCaptcha::get_token();
			$score = WebTotemCaptcha::score($token, WebTotemOption::getPluginSettings('recaptcha_v3_secret'));
      if($score < 0.5) {
         return new \WP_Error('authentication_failed', __('<strong>ERROR</strong>&nbsp;: Please check the ReCaptcha box or try to reload page.','wtotem'));
			}
		}

		if(isset($_POST['wtotem-token']) && is_string($_POST['wtotem-token']) && !empty($_POST['wtotem-token'])){
			if ( is_object( $user ) && ( $user instanceof \WP_User ) ) {
				if ( WebTotemLogin::hasUser2faActivated( $user ) ) {
          $check2faCode = WebTotemLogin::check2faCode( $user, $_POST['wtotem-token']);

					if ( ! $check2faCode )  {
              return new \WP_Error( 'wtotem_two_factor_failed', wp_kses( __( '<strong>CODE INVALID</strong>: The 2FA code provided is either expired or invalid. Please try again.', 'wtotem' ), array( 'strong' => array() ) ) );
          }
				}
			}
		}

        return WebTotemBFProtection::checkBruteForceAttempts($user, $username);
	}

    /**
     * Password brute force protection.
     *
     * @return mixed
     */
    public static function wt_lost_password($errors = null, $user_data = null) {
        return WebTotemBFProtection::lostPassword($errors);
    }

	/**
	 * Restore and then hide the readme file when updating the WordPress.
	 *
	 * @param string $string
	 * @return string
	 */
	public static function restoreReadmeWhenUpdating($string) {
		static $didRun;
		if (!isset($didRun)) {
			$didRun = true;
			WebTotemOption::restoreReadme();
			register_shutdown_function('WebTotemOption::hideReadme');
		}

		return $string;
	}

	/**
	 * Login Page
	 */
	public static function loginEnqueueScripts() {

		$recaptcha_enabled = WebTotemCaptcha::isEnabled();
		if ($recaptcha_enabled) {
			$recaptcha_site_key = WebTotemOption::getPluginSettings('recaptcha_v3_site_key');
			wp_enqueue_script('wtotem_recaptcha', 'https://www.google.com/recaptcha/api.js?render=' . $recaptcha_site_key);
		}

		wp_register_script(
			'wtotem_login',
			WEBTOTEM_URL . '/includes/js/login.js',
			['jquery'],
			WebTotem::fileVersion('includes/js/login.js'),
			false
		);
		wp_enqueue_script('wtotem_login');

		wp_register_style(
			'wtotem_login',
			WEBTOTEM_URL . '/includes/css/login.css',
			[],
			WebTotem::fileVersion('includes/css/login.css')
		);
		wp_enqueue_style('wtotem_login');

		wp_localize_script('wtotem_login', 'wtotem_login_vars', [
			'recaptcha_site_key' => WebTotemCaptcha::_siteKey(),
			'recaptcha_is_enabled' => $recaptcha_enabled,
			'ajaxurl' => admin_url('admin-ajax.php', 'relative'),
            'two_factor_is_enabled' => WebTotemLogin::isTwoFactorEnabled() and WebTotemLogin::anyTwoFactorActivated(),
		]);
	}

	/**
	 * Added a pop-up window to the plugins page
	 */
	public static function registerDeletePrompt() {
		wp_register_style(
			'wtotem_prompt_css',
			WEBTOTEM_URL . '/includes/css/prompt.css',
			[],
			WebTotem::fileVersion('includes/css/prompt.css')
		);
		wp_enqueue_style('wtotem_prompt_css');

		$composer_autoload = WEBTOTEM_PLUGIN_PATH . '/vendor/autoload.php';
		if ( file_exists( $composer_autoload ) ) {
			require_once $composer_autoload;
		}

		$template = new WebTotemTemplate();
		$build[] = [
			'variables' => [
				'message' => __('Are you sure you want to deactivate the plugin?<br>Don\'t worry, even after removing the plugin, our system will continue to protect your site: <ul class="confirmation-dialog__list"><li>the current host will remain in the account</li><li>agents will stay on the current site</li><li>accumulated history, monitoring, agent management and all other functions are available in the account on the <a href="https://wtotem.com" target="_blank">site</a></li></ul>', 'wtotem'),
				'action' => 'reinstall_agents',
				'page_nonce' => wp_create_nonce('wtotem_page_nonce'),
			],
			'template' => 'prompt',
		];

		echo $template->arrayRender($build);
	}

	/**
	 * A safe way to add JavaScript and css files to a WordPress-managed page
	 *
	 * @return void
	 */
	public static function enqueueScripts() {

        // Adding CSS files.
        wp_register_style(
            'wtotem_flatpickr',
            WEBTOTEM_URL . '/includes/css/flatpickr.min.css',
            [],
            WebTotem::fileVersion('includes/css/flatpickr.min.css')
        );
        wp_enqueue_style('wtotem_flatpickr');

        wp_register_style(
            'wtotem_toastr_css',
            WEBTOTEM_URL . '/includes/css/toastr.min.css',
            [],
            WebTotem::fileVersion('includes/css/toastr.min.css')
        );
        wp_enqueue_style('wtotem_toastr_css');

        wp_register_style(
            'wtotem_main_css',
            WEBTOTEM_URL . '/includes/css/main.css',
            [],
            WebTotem::fileVersion('includes/css/main.css')
        );
        wp_enqueue_style('wtotem_main_css');

        // Adding JS files.
        wp_register_script(
            'wtotem_amplitude',
            WEBTOTEM_URL . '/includes/js/amplitude.js',
            [ 'jquery' ],
            WebTotem::fileVersion('includes/js/amplitude.js'),
            false
        );
        wp_enqueue_script('wtotem_amplitude');

        wp_register_script(
            'wtotem_d3',
            WEBTOTEM_URL . '/includes/js/d3.v4.js',
            ['jquery'],
            WebTotem::fileVersion('includes/js/d3.v4.js'),
            true
        );
        wp_enqueue_script('wtotem_d3');

        wp_register_script(
            'wtotem_chart',
            WEBTOTEM_URL . '/includes/js/chart.js',
            ['jquery', 'wtotem_d3', 'wtotem_jsdelivr'],
            WebTotem::fileVersion('includes/js/chart.js'),
            true
        );
        wp_enqueue_script('wtotem_chart');

        wp_register_script(
            'wtotem_flatpickr_js',
            WEBTOTEM_URL . '/includes/js/flatpickr.js',
            [ 'jquery', 'wp-i18n' ],
            WebTotem::fileVersion('includes/js/flatpickr.js'),
            true
        );
        wp_set_script_translations( 'wtotem_flatpickr_js', 'wtotem', WEBTOTEM_PLUGIN_PATH . '/lang/');
        wp_enqueue_script('wtotem_flatpickr_js');

        wp_register_script(
            'wtotem_jsdelivr',
            WEBTOTEM_URL . '/includes/js/jsdelivr_chart.js',
            [ 'jquery' ],
            WebTotem::fileVersion('includes/js/jsdelivr_chart.js'),
            true
        );
        wp_enqueue_script('wtotem_jsdelivr');

        wp_register_script(
            'wtotem_jquery_qrcode',
            WEBTOTEM_URL . '/includes/js/jquery.qrcode.min.js',
            [ 'jquery' ],
            WebTotem::fileVersion('includes/js/jquery.qrcode.min.js'),
            true
        );
        wp_enqueue_script('wtotem_jquery_qrcode');

        wp_register_script(
            'wtotem_progress_bar',
            WEBTOTEM_URL . '/includes/js/progress_bar.js',
            [],
            WebTotem::fileVersion('includes/js/progress_bar.js'),
            true
        );
        wp_enqueue_script('wtotem_progress_bar');

        wp_register_script(
            'wtotem_toastr',
            WEBTOTEM_URL . '/includes/js/toastr.min.js',
            [],
            WebTotem::fileVersion('includes/js/toastr.min.js'),
            true
        );
        wp_enqueue_script('wtotem_toastr');

        $_page = WebTotemRequest::get('page');
        if($_page === 'wtotem_settings'){
            wp_register_script(
                'wtotem_country_blocking',
                WEBTOTEM_URL . '/includes/js/country-blocking.js',
                ['wp-i18n'],
                WebTotem::fileVersion('includes/js/country-blocking.js'),
                true
            );
            wp_set_script_translations( 'wtotem_country_blocking', 'wtotem' , WEBTOTEM_PLUGIN_PATH . '/lang/');
            wp_enqueue_script('wtotem_country_blocking');
        }

        wp_register_script(
            'wtotem_main',
            WEBTOTEM_URL . '/includes/js/main.js',
            ['jquery'],
            WebTotem::fileVersion('includes/js/main.js'),
            true
        );
        wp_enqueue_script('wtotem_main');
    }
}
