<?php
/**
 * This is Class dive helper.
 *
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 * SMSAlertDivi Class.
 */

class SMSAlertDivi
{

    /**
     * Construct
     *
     * @return bool
     */
    public function __construct()
    {
        $this->load();
        $this->allowLoad();
     
    }
    /**
     * Load integration
     *
     * @return bool
     */
    public function allowLoad()
    {

        if (function_exists('et_divi_builder_init_plugin') ) {
            return true;
        }

        $allow_themes = [ 'Divi', 'Extra' ];
        $theme        = wp_get_theme();
        $theme_name   = $theme->get_template();
        $theme_parent = $theme->parent();

        return (bool) array_intersect([ $theme_name, $theme_parent ], $allow_themes);
    }

    /**
     * Load integration
     *
     * @return bool
     */
    public function load()
    {
        $this->hooks();
    }

    /**
     * Hooks.
     *
     * @return bool
     */
    public function hooks()
    {
        
        add_action('et_builder_ready', [ $this, 'registerModule' ]);
        add_action('wp_enqueue_scripts', [ $this, 'frontendStyles' ], 12);

        if (wp_doing_ajax() ) {
            add_action('wp_ajax_smsalert_divi_preview', [ $this, 'preview' ]);
        }

        if ($this->_isDiviBuilder() ) {
            add_action('wp_enqueue_scripts', [ $this, 'builderScripts' ]);
        
        }

    }

    /**
     * Check is div
     *
     * @return bool
     */
    private function _isDiviBuilder()
    {
        return ! empty($_GET['et_fb']); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    }


    /**
     * Get current style name.
     * Overwrite st
     *
     * @return string
     */
    public function get_current_styles_name()
    {

        $disable_css ='disable-css';
        if (1 === $disable_css ) {
            return 'full';
        }
        if (2 === $disable_css ) {
            return 'base';
        }

        return '';
    }

    /**
     * Is the Divi 
     *
     * @return bool
     */
    protected function isDiviPluginLoaded()
    {

        if (! is_singular() ) {
            return false;
        }

        return function_exists('et_is_builder_plugin_active');
    }

    /**
     * Register frontend styles
     *
     * @return bool
     */
    public function frontendStyles()
    {

        if (! $this->isDiviPluginLoaded() ) {
            return;
        }
    
    }

    /**
     * Load scripts
     *
     * @return bool
     */
    public function builderScripts()
    {
        wp_enqueue_script('smsalert-divi', SA_MOV_URL . 'js/divi.js', [ 'react', 'react-dom' ], SmsAlertConstants::SA_VERSION, true);

        wp_localize_script(
            'smsalert-divi',
            'smsalert_divi_builder',
            [
            'ajax_url'          => admin_url('admin-ajax.php'),
            'nonce'             => wp_create_nonce('smsalert_divi_builder'),
            'placeholder'       => '',
            'placeholder_title' => esc_html__('SMSAlert', 'sms-alert'),
            ]
        );
    }

    /**
     * Register mod
     *
     * @return bool
     */
    public function registerModule()
    {
        if (! class_exists('ET_Builder_Module') ) {
            return;
        }
        include_once plugin_dir_path(__FILE__)."class-divimodule.php";
        new SMSAlertSelector();

    }
    
    /**
     * Ajax handler
     *
     * @return bool
     */
    public function preview()
    {

        check_ajax_referer('smsalert_divi_builder', 'nonce');

        $form_id    = filter_input(INPUT_POST, 'form_id', FILTER_SANITIZE_STRING);

        add_action(
            'smsalert_frontend_output',
            function () {
                echo '<fieldset disabled>';
            },
            3
        );
        add_action(
            'smsalert_frontend_output',
            function () {

                echo '</fieldset>';
            },
            30
        );
        if ($form_id!='') {
              $shortcode = ($form_id==1)?'[sa_signupwithmobile]':(($form_id==2)?'[sa_loginwithotp]':'[sa_sharecart]');
            wp_send_json_success(
                do_shortcode($shortcode)
            );
        }
    }
}
new SMSAlertDivi();

