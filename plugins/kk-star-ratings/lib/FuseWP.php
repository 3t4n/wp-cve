<?php

class KKSTAR_FuseWP
{
    const SLUG = 'kkstar-fusewp';

    private $config = array(
        'lite_plugin'       => 'fusewp/fusewp.php',
        'lite_download_url' => 'https://downloads.wordpress.org/plugin/fusewp.latest-stable.zip',
        'fusewp_settings'   => 'admin.php?page=fusewp-sync',
    );

    private $output_data = array();

    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_settings_page']);

        add_action('wp_ajax_kkstar_activate_plugin', [$this, 'kkstar_activate_plugin']);
        add_action('wp_ajax_kkstar_install_plugin', [$this, 'kkstar_install_plugin']);

        if (wp_doing_ajax()) {
            add_action('wp_ajax_kkstar_fusewp_page_check_plugin_status', array($this, 'ajax_check_plugin_status'));
        }

        // Check what page we are on.
        $page = isset($_GET['page']) ? sanitize_key(wp_unslash($_GET['page'])) : '';

        if (self::SLUG !== $page) return;

        add_action('admin_init', array($this, 'redirect_to_fusewp_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    public function kkstar_install_plugin()
    {
        // Run a security check.
        check_ajax_referer('kkstar-admin-nonce', 'nonce');

        $generic_error = esc_html__('There was an error while performing your request.', 'kk-star-ratings');
        $type          = !empty($_POST['type']) ? sanitize_key($_POST['type']) : 'plugin';

        if (!current_user_can('install_plugins')) {
            wp_send_json_error($generic_error);
        }

        // Determine whether file modifications are allowed.
        if (!wp_is_file_mod_allowed('kkstar_can_install')) {
            wp_send_json_error($generic_error);
        }

        $error = $type === 'plugin' ? esc_html__('Could not install plugin. Please download and install manually.', 'kk-star-ratings') : esc_html__('Could not install addon. Please download from wpforms.com and install manually.', 'kk-star-ratings');

        if (empty($_POST['plugin'])) {
            wp_send_json_error($error);
        }

        // Set the current screen to avoid undefined notices.
        set_current_screen('kk-star-ratings_page_kkstar-fusewp');

        // Prepare variables.
        $url = esc_url_raw(
            add_query_arg(
                ['page' => 'kk-star-ratings'],
                admin_url('admin.php')
            )
        );

        ob_start();
        $creds = request_filesystem_credentials($url, '', false, false, null);

        // Hide the filesystem credentials form.
        ob_end_clean();

        // Check for file system permissions.
        if ($creds === false) {
            wp_send_json_error($error);
        }

        if (!WP_Filesystem($creds)) {
            wp_send_json_error($error);
        }

        /*
         * We do not need any extra credentials if we have gotten this far, so let's install the plugin.
         */

        // Do not allow WordPress to search/download translations, as this will break JS output.
        remove_action('upgrader_process_complete', ['Language_Pack_Upgrader', 'async_upgrade'], 20);

        // Create the plugin upgrader with our custom skin.
        $installer = new KKStar_PluginSilentUpgrader(new KKStar_Install_Skin());

        // Error check.
        if (!method_exists($installer, 'install') || empty($_POST['plugin'])) {
            wp_send_json_error($error);
        }

        $installer->install($_POST['plugin']); // phpcs:ignore

        // Flush the cache and return the newly installed plugin basename.
        wp_cache_flush();

        $plugin_basename = $installer->plugin_info();

        if (empty($plugin_basename)) {
            wp_send_json_error($error);
        }

        $result = [
            'msg'          => $generic_error,
            'is_activated' => false,
            'basename'     => $plugin_basename,
        ];

        // Check for permissions.
        if (!current_user_can('activate_plugins')) {
            $result['msg'] = $type === 'plugin' ? esc_html__('Plugin installed.', 'kk-star-ratings') : esc_html__('Addon installed.', 'kk-star-ratings');

            wp_send_json_success($result);
        }

        // Activate the plugin silently.
        $activated = activate_plugin($plugin_basename);

        if (!is_wp_error($activated)) {
            $result['is_activated'] = true;
            $result['msg']          = $type === 'plugin' ? esc_html__('Plugin installed & activated.', 'kk-star-ratings') : esc_html__('Addon installed & activated.', 'kk-star-ratings');

            wp_send_json_success($result);
        }

        // Fallback error just in case.
        wp_send_json_error($result);
    }

    public function kkstar_activate_plugin()
    {
        // Run a security check.
        check_ajax_referer('kkstar-admin-nonce', 'nonce');

        // Check for permissions.
        if (!current_user_can('activate_plugins')) {
            wp_send_json_error(esc_html__('Plugin activation is disabled for you on this site.', 'kk-star-ratings'));
        }

        if (isset($_POST['plugin'])) {

            $plugin   = sanitize_text_field(wp_unslash($_POST['plugin']));
            $activate = activate_plugins($plugin);

            if (!is_wp_error($activate)) {
                wp_send_json_success(esc_html__('Plugin activated.', 'kk-star-ratings'));
            }
        }

        wp_send_json_error(esc_html__('Could not activate plugin. Please activate from the Plugins page.', 'kk-star-ratings'));
    }

    public function register_settings_page()
    {
        add_submenu_page(
            'kk-star-ratings',
            esc_html__('User Sync', 'kk-star-ratings'),
            esc_html__('User Sync', 'kk-star-ratings') . sprintf(
                '<span style="color: #f18200;vertical-align: super;font-size: 9px">&nbsp;%s</span>',
                __('NEW', 'kk-star-ratings')
            ),
            'manage_options',
            self::SLUG,
            [$this, 'output']
        );
    }

    public function enqueue_assets()
    {
        wp_enqueue_script(
            'kkstar-admin-page-fusewp',
            KK_STAR_ASSETS_URL . "js/fusewp.js",
            array('jquery'),
            false,
            true
        );

        wp_localize_script(
            'kkstar-admin-page-fusewp',
            'kkstar_pluginlanding',
            $this->get_js_strings()
        );

        wp_localize_script('kkstar-admin-page-fusewp', 'kkstar_installer_globals', [
            'nonce' => wp_create_nonce('kkstar-admin-nonce')
        ]);
    }

    /**
     * JS Strings.
     */
    protected function get_js_strings()
    {
        $error_could_not_install = sprintf(
            wp_kses( /* translators: %s - Lite plugin download URL. */
                __('Could not install plugin. Please <a href="%s">download</a> and install manually.', 'kk-star-ratings'),
                array(
                    'a' => array(
                        'href' => true,
                    ),
                )
            ),
            esc_url_raw($this->config['lite_download_url'])
        );

        $error_could_not_activate = sprintf(
            wp_kses( /* translators: %s - Lite plugin download URL. */
                __('Could not activate plugin. Please activate from the <a href="%s">Plugins page</a>.', 'kk-star-ratings'),
                array(
                    'a' => array(
                        'href' => true,
                    ),
                )
            ),
            esc_url_raw(admin_url('plugins.php'))
        );

        return array(
            'installing'               => esc_html__('Installing...', 'kk-star-ratings'),
            'activating'               => esc_html__('Activating...', 'kk-star-ratings'),
            'activated'                => esc_html__('FuseWP Installed & Activated', 'kk-star-ratings'),
            'install_now'              => esc_html__('Install Now', 'kk-star-ratings'),
            'activate_now'             => esc_html__('Activate Now', 'kk-star-ratings'),
            'download_now'             => esc_html__('Download Now', 'kk-star-ratings'),
            'plugins_page'             => esc_html__('Go to Plugins page', 'kk-star-ratings'),
            'error_could_not_install'  => $error_could_not_install,
            'error_could_not_activate' => $error_could_not_activate,
            'manual_install_url'       => $this->config['lite_download_url'],
            'manual_activate_url'      => admin_url('plugins.php'),
            'fusewp_settings_button'   => esc_html__('Go to FuseWP Settings', 'kk-star-ratings'),
        );
    }

    /**
     * Generate and output page HTML.
     */
    public function output()
    {
?>
        <style>
            #kkstar-admin-fusewp {
                width: 700px;
                margin: 0 auto;
            }

            #kkstar-admin-fusewp .notice,
            #kkstar-admin-fusewp .error {
                display: none
            }

            #kkstar-admin-fusewp *,
            #kkstar-admin-fusewp *::before,
            #kkstar-admin-fusewp *::after {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }

            #kkstar-admin-fusewp section {
                margin: 50px 0;
                text-align: left;
                clear: both;
            }

            #kkstar-admin-fusewp section.screenshot {
                text-align: center;
            }

            #kkstar-admin-fusewp .top {
                text-align: center;
            }

            #kkstar-admin-fusewp .top img {
                margin-bottom: 38px;
                width: 480px;
                height: auto;
            }

            #kkstar-admin-fusewp .top h1 {
                font-size: 26px;
                font-weight: 600;
                margin-bottom: 0;
                padding: 0;
            }

            #kkstar-admin-fusewp .top p {
                font-size: 17px;
                color: #777777;
                margin-top: .5em;
            }

            #kkstar-admin-fusewp p {
                font-size: 15px;
            }

            #kkstar-admin-fusewp .cont {
                display: inline-block;
                position: relative;
                width: 100%;
                padding: 5px;
                background-color: #ffffff;
                -webkit-box-shadow: 0px 2px 5px 0px rgb(0 0 0 / 5%);
                -moz-box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.05);
                box-shadow: 0px 2px 5px 0px rgb(0 0 0 / 5%);
                border-radius: 3px;
                box-sizing: border-box;
            }

            #kkstar-admin-fusewp .screenshot>* {
                vertical-align: middle;
            }

            #kkstar-admin-fusewp .screenshot .cont img {
                max-width: 100%;
                display: block;
            }

            #kkstar-admin-fusewp .screenshot ul {
                display: inline-block;
                margin: 0 0 0 30px;
                list-style-type: none;
                max-width: 100%;
            }

            #kkstar-admin-fusewp .screenshot li {
                margin: 16px 0;
                padding: 0 0 0 24px;
                font-size: 15px;
                color: #777777;
            }

            #kkstar-admin-fusewp .step {
                background-color: #F9F9F9;
                -webkit-box-shadow: 0px 2px 5px 0px rgb(0 0 0 / 5%);
                -moz-box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.05);
                box-shadow: 0px 2px 5px 0px rgb(0 0 0 / 5%);
                border: 1px solid #E5E5E5;
                margin: 0 0 25px 0;
            }

            #kkstar-admin-fusewp .step .num {
                display: inline-block;
                position: relative;
                width: 100px;
                height: 50px;
                text-align: center;
            }

            .kkstar-admin-plugin-landing .loader {
                margin: 0 auto;
                position: relative;
                text-indent: -9999em;
                border-top: 4px solid #969696;
                border-right: 4px solid #969696;
                border-bottom: 4px solid #969696;
                border-left: 4px solid #404040;
                -webkit-transform: translateZ(0);
                -ms-transform: translateZ(0);
                transform: translateZ(0);
                -webkit-animation: load8 1.1s infinite linear;
                animation: load8 1.1s infinite linear;
                background-color: transparent;
            }

            .kkstar-admin-plugin-landing .loader,
            .kkstar-admin-plugin-landing .loader:after {
                display: block;
                border-radius: 50%;
                width: 50px;
                height: 50px
            }

            @-webkit-keyframes load8 {
                0% {
                    -webkit-transform: rotate(0deg);
                    transform: rotate(0deg)
                }

                100% {
                    -webkit-transform: rotate(360deg);
                    transform: rotate(360deg)
                }
            }

            @keyframes load8 {
                0% {
                    -webkit-transform: rotate(0deg);
                    transform: rotate(0deg)
                }

                100% {
                    -webkit-transform: rotate(360deg);
                    transform: rotate(360deg)
                }
            }

            #kkstar-admin-fusewp .step .loader {
                margin-top: -54px;
                transition: all .3s;
                opacity: 1;
            }

            #kkstar-admin-fusewp .step .hidden {
                opacity: 0;
                transition: all .3s;
            }

            #kkstar-admin-fusewp .step div {
                display: inline-block;
                width: calc(100% - 104px);
                background-color: #ffffff;
                padding: 30px;
                border-left: 1px solid #eeeeee;
            }

            #kkstar-admin-fusewp .step h2 {
                font-size: 24px;
                line-height: 22px;
                margin-top: 0;
                margin-bottom: 15px;
            }

            #kkstar-admin-fusewp .step p {
                font-size: 16px;
                color: #777777;
            }


            #kkstar-admin-fusewp .step .button {
                font-weight: 500;
                box-shadow: none;
                padding: 12px;
                min-width: 200px;
                height: auto;
                line-height: 13px;
                text-align: center;
                font-size: 15px;
                transition: all .3s;
            }

            #kkstar-admin-fusewp .grey {
                opacity: 0.5;
            }
        </style>
<?php
        echo '<div id="kkstar-admin-fusewp" class="wrap kkstar-admin-wrap kkstar-admin-plugin-landing">';

        $this->output_section_heading();
        $this->output_section_screenshot();
        $this->output_section_step_install();
        $this->output_section_step_setup();

        echo '</div>';
    }

    /**
     * Generate and output heading section HTML.
     */
    protected function output_section_heading()
    {
        // Heading section.
        printf(
            '<section class="top">
				<h1>%1$s</h1>
				<p>%2$s</p>
			</section>',
            esc_html__('WordPress User Sync & Automation Plugin', 'wp-user-avatar'),
            esc_html__('FuseWP connect WordPress to your email marketing platform and CRM so you can automatically sync users & profile updates to your email list.', 'wp-user-avatar')
        );
    }

    /**
     * Generate and output screenshot section HTML.
     */
    protected function output_section_screenshot()
    {
        printf(
            '<section class="screenshot">
				<div class="cont">
					<img src="%1$s" alt="%2$s"/>
				</div>	
			</section>',
            KK_STAR_ASSETS_URL . 'images/fusewp-user-sync-edit-screen.png',
            esc_attr__('FuseWP screenshot', 'wp-user-avatar')
        );
    }

    /**
     * Generate and output step 'Install' section HTML.
     */
    protected function output_section_step_install()
    {
        $step = $this->get_data_step_install();

        if (empty($step)) {
            return;
        }

        printf(
            '<section class="step step-install">
				<aside class="num">
					<img src="%1$s" alt="%2$s" />
					<i class="loader hidden"></i>
				</aside>
				<div>
					<h2>%3$s</h2>
					<p>%4$s</p>
					<button class="button %5$s" data-plugin="%6$s" data-action="%7$s">%8$s</button>
				</div>		
			</section>',
            esc_url(KK_STAR_ASSETS_URL . 'images/' . $step['icon']),
            esc_attr__('Step 1', 'kk-star-ratings'),
            esc_html__('Install and Activate FuseWP', 'kk-star-ratings'),
            esc_html__('Install FuseWP from the WordPress.org plugin repository.', 'kk-star-ratings'),
            esc_attr($step['button_class']),
            esc_attr($step['plugin']),
            esc_attr($step['button_action']),
            esc_html($step['button_text'])
        );
    }

    /**
     * Generate and output step 'Setup' section HTML.
     */
    protected function output_section_step_setup()
    {
        $step = $this->get_data_step_setup();

        if (empty($step)) {
            return;
        }

        printf(
            '<section class="step step-setup %1$s">
				<aside class="num">
					<img src="%2$s" alt="%3$s" />
					<i class="loader hidden"></i>
				</aside>
				<div>
					<h2>%4$s</h2>
					<p>%5$s</p>
					<button class="button %6$s" data-url="%7$s">%8$s</button>
				</div>		
			</section>',
            esc_attr($step['section_class']),
            esc_url(KK_STAR_ASSETS_URL . 'images/' . $step['icon']),
            esc_attr__('Step 2', 'kk-star-ratings'),
            esc_html__('Set Up FuseWP', 'kk-star-ratings'),
            esc_html__('Configure and create your first login form.', 'kk-star-ratings'),
            esc_attr($step['button_class']),
            esc_url(admin_url($this->config['fusewp_settings'])),
            esc_html($step['button_text'])
        );
    }

    /**
     * Step 'Install' data.
     */
    protected function get_data_step_install()
    {
        $step = array();

        $this->output_data['all_plugins']      = get_plugins();
        $this->output_data['plugin_installed'] = array_key_exists($this->config['lite_plugin'], $this->output_data['all_plugins']);
        $this->output_data['plugin_activated'] = false;
        $this->output_data['plugin_setup']     = false;

        if (!$this->output_data['plugin_installed']) {
            $step['icon']          = 'step-1.svg';
            $step['button_text']   = esc_html__('Install FuseWP', 'kk-star-ratings');
            $step['button_class']  = '';
            $step['button_action'] = 'install';
            $step['plugin']        = $this->config['lite_download_url'];
        } else {
            $this->output_data['plugin_activated'] = $this->is_activated();
            $this->output_data['plugin_setup']     = $this->is_configured();
            $step['icon']                          = $this->output_data['plugin_activated'] ? 'step-complete.svg' : 'step-1.svg';
            $step['button_text']                   = $this->output_data['plugin_activated'] ? esc_html__('FuseWP Installed & Activated', 'kk-star-ratings') : esc_html__('Activate FuseWP', 'kk-star-ratings');
            $step['button_class']                  = $this->output_data['plugin_activated'] ? 'grey disabled' : '';
            $step['button_action']                 = $this->output_data['plugin_activated'] ? '' : 'activate';
            $step['plugin']                        = $this->config['lite_plugin'];
        }

        return $step;
    }

    /**
     * Step 'Setup' data.
     */
    protected function get_data_step_setup()
    {
        $step = array();

        $step['icon']          = 'step-2.svg';
        $step['section_class'] = $this->output_data['plugin_activated'] ? '' : 'grey';
        $step['button_text']   = esc_html__('Start Setup', 'kk-star-ratings');
        $step['button_class']  = 'grey disabled';

        if ($this->output_data['plugin_setup']) {
            $step['icon']          = 'step-complete.svg';
            $step['section_class'] = '';
            $step['button_text']   = esc_html__('Go to FuseWP settings', 'kk-star-ratings');
        } else {
            $step['button_class'] = $this->output_data['plugin_activated'] ? '' : 'grey disabled';
        }

        return $step;
    }

    /**
     * Ajax endpoint. Check plugin setup status.
     * Used to properly init step 'Setup' section after completing step 'Install'.
     */
    public function ajax_check_plugin_status()
    {
        // Security checks.
        if (
            !check_ajax_referer('kkstar-admin-nonce', 'nonce', false) ||
            !current_user_can('activate_plugins')
        ) {
            wp_send_json_error(
                array(
                    'error' => esc_html__('You do not have permission.', 'kk-star-ratings'),
                )
            );
        }

        $result = array();

        if (!$this->is_activated()) {
            wp_send_json_error(
                array(
                    'error' => esc_html__('Plugin unavailable.', 'kk-star-ratings'),
                )
            );
        }

        $result['setup_status'] = (int)$this->is_configured();

        wp_send_json_success($result);
    }

    /**
     * Whether FuseWP plugin configured or not.
     */
    protected function is_configured()
    {
        return $this->is_activated();
    }

    /**
     * Whether FuseWP plugin active or not.
     */
    protected function is_activated()
    {
        return class_exists('\FuseWP\Core\Base');
    }

    public function redirect_to_fusewp_settings()
    {
        if ($this->is_configured()) {
            wp_safe_redirect(admin_url($this->config['fusewp_settings']));
            exit;
        }
    }

    /**
     * @return self
     */
    public static function get_instance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}
