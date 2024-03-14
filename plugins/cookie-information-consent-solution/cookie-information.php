<?php
/**
 * Plugin Name: Cookie Information - Consent Solution
 * Plugin URI: https://cookieinformation.com/extension/wordpress/
 * Description: Intergrate a Profesional Cookie Consent Solution from Cookie Information, and comply with GDPR. For more information visit: https://cookieinformation.com/extension/wordpress/
 * Version: 1.1.5
 * Author: Cookie Information A/S
 * Author URI: https://cookieinformation.com/
 **/


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


class CookieInformationPlugin {

    public function ci_activation (){
        update_option('enable-popup', 1);
        update_option('disable-logged-in-users', 0);
        update_option('enable-tcf', 0);
        update_option('enable-gcm', 0);
        update_option('enable-video-sdk', 0);
        update_option('video-sdk-category', 'cookie_cat_marketing');
        update_option('dismiss_notice', false);
    }

    public function ci_deactivate () {
        // Do nothing
    }

    public function ci_register_settings () {
        register_setting('ci_settings', 'enable-popup');
        register_setting('ci_settings', 'disable-logged-in-users');
        register_setting('ci_settings', 'enable-tcf');
        register_setting('ci_settings', 'enable-gcm');
        register_setting('ci_settings', 'gcm-code-snippet');
        register_setting('ci_settings', 'enable-video-sdk');
        register_setting('ci_settings', 'video-sdk-category');
        register_setting('ci_settings', 'placeholder-text');
        register_setting('ci_settings', 'placeholder-class');

        //add_action( 'admin_enqueue_scripts', array( $this, 'add_notice_handler_script' ) );

        /*if( get_option( 'dismiss_notice' ) != true ) {
         add_action( 'admin_notices', array( $this, 'registrationNotice' ) );
        }*/
    }

    public function ci_delete_settings ( $setting ) {
        delete_option( $setting );
    }

    public function add_notice_handler_script() {
        wp_register_script( 'notice-update', plugins_url('js/notice-handler.js', __FILE__ ),'','1.0', false );

        wp_localize_script( 'notice-update', 'notice_params', array(
            'ajaxurl' => get_admin_url() . 'admin-ajax.php',
        ));

        wp_enqueue_script(  'notice-update' );
    }

    public function register_menus () {
        $icon = 'dashicons-admin-site';
        add_menu_page('Cookie Information - Consent Solution', 'Cookie Information', 'manage_options', 'cookie-information', array( $this, 'pluginMainPage' ), $icon, 99);
        add_submenu_page('cookie-information', 'Plugin Settings', 'Settings', 'manage_options', 'cookie-information-settings', array( $this, 'pluginSettingsPage' ), 1);
    }

    public function registrationNotice () {

        $screen = get_current_screen();
        if ($screen->id === 'toplevel_page_cookie-information') {
            ?>
            <div class="notice notice-warning registration-notice">
                <p><?php _e( 'You need to register <b>'.$this->getRootDomain().'</b> in order to use this plugin. <a class="cookie-register" href="https://cookieinformation.com/registration" target="_blank">Register Here</a>', 'cookie-information' ); ?></p>
            </div>

            <?php
        }
    }

    public function dismiss_notice() {
        update_option( 'dismiss_notice', true );
    }

    private function getRootDomain () {
        $rootDomain = $_SERVER['HTTP_HOST'];
        if($rootDomain === 'localhost' || '127.0.0.1'){
            $rootDomain = 'Your public domain';
        }

        return $rootDomain;
    }


    /**
     * Contructor
     */

    public function __construct() {

        /* Adding Menu item to admin panel */
        add_action('admin_menu', array( $this, 'register_menus' ));

        /* Activation Hook */
        register_activation_hook(__FILE__, array( $this, 'ci_activation' ) );

        /* Deactivation Hook */
        register_deactivation_hook(__FILE__, array( $this, 'ci_deactivate' ) );

        /* Register Plugin settings */
        add_action('admin_init', array( $this, 'ci_register_settings' ) );


        if(!is_admin()) {

            /* Action for Consent popup */
            if( get_option( 'enable-popup', true ) ){
                add_action( 'wp_head', array( $this,'addConsentPopupScript' ), 1 );
            }
        }

        /* Action for shortcodes */
        add_shortcode( 'cookiepolicy', array( $this,'addCookiePolicy' ) );
        add_shortcode( 'privacycontrols', array( $this,'addPrivacyControls' ) );

        if( get_option( 'enable-video-sdk' ) ){

            /* Place SDK attibutes on Youtube & Vimeo iframes */
            add_filter('the_content', array($this,'autoBlockingVideos'));

            /* Place SDK attibutes on Youtube & Vimeo iframes IF Elementor is Installed */


            if( strpos($_SERVER['REQUEST_URI'], 'elementor') !== false ){
                sleep(3);
                add_action( 'elementor/frontend/the_content', array($this,'autoBlockingVideos') );
            }
            /* Add Placeholder Tags for blocked Videoes */
            add_action('wp_footer', array($this, 'addPlaceholderTextForBlockedVideoes'),99);
        }

    }

    /**
     * Prepare languages for privacy widgets
     */

    public function getDataCulture () {
        $my_current_lang = apply_filters( 'wpml_current_language', NULL );
        $dataCulture = 'data-culture="'. $my_current_lang .'"';

        if($my_current_lang == ''){
            $langcode = substr( get_locale(), 0, 2 );
            $dataCulture = 'data-culture="'. $langcode .'"';
        }else if(strlen($my_current_lang) > 2){
            $dataCulture = '';
        }else if($my_current_lang === 'no'){
            $dataCulture = 'data-culture="nb"';
        }

        return $dataCulture;
    }

    /**
     * Function for Adding the Cookie Consent Popup to the <head> tag og the website
     */
    public function addConsentPopupScript () {
        $gcmCodeSnippet = get_option('gcm-code-snippet');

        echo (get_option( 'enable-gcm', false ) == 1 ? $gcmCodeSnippet : '')
            .'<script id="CookieConsent" src="https://policy.app.cookieinformation.com/uc.js" '
            .$this->getDataCulture()
            .' '
            .(get_option( 'enable-tcf', false ) == 1 ? 'data-tcf-v2-enabled="true" data-tcf-global-scope="false"' : '')
            .' '
            .(get_option( 'enable-gcm', false ) == 1 ? 'data-gcm-version="2.0"' : 'data-gcm-enabled="false"')
            .'></script>';
    }

    /**
     * Function for Adding the [Privacy Policy] as a shortcode
     */
    public function addPrivacyControls () {
        return '<div id="cicc-template"></div>';
    }

    /**
     * Function for Adding the [Cookie Policy] as a shortcode
     */
    public function addCookiePolicy () {
        return '<script id="CookiePolicy" src="https://policy.app.cookieinformation.com/cid.js" '. $this->getDataCulture() .' type="text/javascript"></script>';
    }


    public function autoBlockingVideos ( $videoes ) {
        $regex_array = array('/<iframe[^>]*src=\"[^\"]*youtu[.]?be.*<\/iframe>/mi','/<iframe[^>]*src=\"[^\"]*vimeo.*<\/iframe>/mi');


        foreach( $regex_array as $regex ){

            preg_match_all( $regex, $videoes, $matches );
            foreach( $matches as $video ){
                $category_attr = 'data-category-consent="'. get_option( 'video-sdk-category', true ) .'"';
                $src = $category_attr.' src="about:blank" data-consent-src';

                $replace = str_replace('src',$src,$video);
                $videoes = str_replace($video, $replace, $videoes);
            }
        }
        return $videoes;
    }

    public function addPlaceholderTextForBlockedVideoes () {
        $sdkCategory = get_option('video-sdk-category');
        $text = get_option('placeholder-text');
        $ClassName = get_option('placeholder-class');

        $scriptTags = '
            <script type="text/javascript">
                if(window.CookieInformation){
                    window.CookieInformation.enableYoutubeNotVisibleDescription = true;
                    window.CookieInformation.youtubeCategorySdk = "'. $sdkCategory .'";
                    window.CookieInformation.youtubeNotVisibleDescription = "'. $text .'";
                    window.CookieInformation.youtubeBlockedCSSClassName = "'. $ClassName .'";
                }
            </script>
        ';

        echo $scriptTags;
    }

    public function pluginMainPage() {
        wp_enqueue_style( 'cookie-information-plugin-styles', plugins_url('css/styles.css',__FILE__), array(), null, 'all' );
        ?>

        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <div class="cookie-description">
                <p><strong>Remember to register ( <?php echo $this->getRootDomain() ?> ) in order to use this plugin. <a target="_blank" href="https://cookieinformation.com/registration" rel="noopener">Register here</a></strong></p>
                <p>When you have completed the registration and set up your solution on our platform, then the Cookie-popup will automatically show up</p>
                <p>This plugin will automatically detect the currect language set by <a href="https://wpml.org/" target="_blank">WPML plugin</a> for the consent solution, for use on multilanguage sites</p>
                <ul class="weblinks">
                    <li>Website: <a href="https://cookieinformation.com/" target="_blank">cookieinformation.com</a></li>
                    <li>Platform login: <a href="https://app.cookieinformation.com/#/login" target="_blank">Login here</a></li>
                    <li>Visit our: <a href="https://support.cookieinformation.com/" target="_blank">Help Center</a></li>
                </ul>
            </div>
            <div class="cookie-logo" style="margin:20px 0px">
                <a href="https://cookieinformation.com/" target="_blank">
                    <img src="<?php echo plugin_dir_url(__FILE__). 'image/ci-logo.png' ?>" width="250">
                </a>
            </div>
        </div>

        <?php

    }

    public function pluginSettingsPage(){
        wp_enqueue_style( 'cookie-information-plugin-styles', plugins_url('css/styles.css',__FILE__), array(), null, 'all' );
        wp_enqueue_script( 'cookie-information-plugin-script', plugins_url('js/script.js',__FILE__), array(), null , true );
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

            <?php settings_errors(); ?>

            <div class="settings-wrapper">
                <form method="post" action="options.php">
                    <table class="form-table coi-table">
                        <?php settings_fields('ci_settings'); ?>
                        <tr>
                            <th scope="row"><label for="enable-popup">Enable Consent Pop-up</label></th>
                            <td>
                                <div class="coi-checkboxes">
                                    <input id="enable-popup" name="enable-popup" class="coi__checkbox" type="checkbox" value="1" <?php checked( get_option( 'enable-popup', true ), 1, true ); ?> />
                                    <label for="enable-popup"></label>
                                </div>
                            </td>
                        </tr>
                        <!--<tr>
                                <th scope="row"><label for="disable-logged-in-users">Remove pop-up for logged in users</label></th>
                                <td>
                                    <div class="coi-checkboxes">
                                        <input id="disable-logged-in-users" name="disable-logged-in-users" class="coi__checkbox" type="checkbox" value="1" <?php checked( get_option( 'disable-logged-in-users' ), 1 ); ?> />
                                        <label for="disable-logged-in-users"></label>
                                    </div>
                                </td>
                            </tr>-->
                        <tr>
                            <th scope="row"><label for="cookiepolicy">Cookie Policy Shortcode</label></th>
                            <td>
                                <input id="cookiepolicy" type="text" name="cookiepolicy" value="<?php echo '[cookiepolicy]'; ?>" readonly class="regular-text" />
                                <div onClick="javascript:copyToClipboard('cookiepolicy');" class="copy-paste"><span class="dashicons dashicons-clipboard"></span></div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="privacycontrols">Privacy Controls Shortcode</label></th>
                            <td>
                                <input id="privacycontrols" type="text" name="privacycontrols" value="<?php echo '[privacycontrols]'; ?>" readonly class="regular-text" />
                                <div onClick="javascript:copyToClipboard('privacycontrols');" class="copy-paste"><span class="dashicons dashicons-clipboard"></span></div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="enable-tcf">Enable IAB TCF v2</label></th>
                            <td>
                                <div class="coi-checkboxes">
                                    <input id="enable-tcf" name="enable-tcf" class="coi__checkbox" type="checkbox" value="1" <?php checked( get_option( 'enable-tcf', false ), 1, true ); ?> />
                                    <label for="enable-tcf"></label>
                                </div>
                                <p class="helper">You need to use the correct template for the IAB to work. You can switch to the IAB template within the platform.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="enable-tcf">Enable Google Consent Mode v2</label></th>
                            <td>
                                <div class="coi-checkboxes">
                                    <input id="enable-gcm" name="enable-gcm" onclick="toggleContainer()" class="coi__checkbox collapsable" type="checkbox" value="1" <?php checked( get_option( 'enable-gcm', false ), 1, true ); ?> />
                                    <label for="enable-gcm"></label>
                                </div>
                                <p class="helper">The plugin already injected the GCM config snippet as shown in the <a target="_blank" href="https://support.cookieinformation.com/en/articles/5411279-google-consent-mode-implementation" rel="noopener">documentation</a>, but you can change it below according to your needs.</p>
                            </td>
                        </tr>
                    </table>
                    <table id="enable-gcm-container" class="form-table coi-table">
                        <tr>
                            <th scope="row"><label for="enable-tcf">GCM Code Snippet</label></th>
                            <td>
                                        <textarea id="gcm-code-snippet" type="text" name="gcm-code-snippet" class="regular-text"><?php
                                            $gcmCodeSnippet = '&lt;script&gt;
                                          window.dataLayer = window.dataLayer || [];

                                          function gtag() {
                                            dataLayer.push(arguments);
                                          }

                                          gtag("consent", "default", {
                                               ad_storage: "denied",
                                               ad_user_data: "denied",
                                               ad_personalization: "denied",
                                               analytics_storage: "denied",
                                               functionality_storage: "denied",
                                               personalization_storage: "denied",
                                               security_storage: "denied",
                                               wait_for_update: 500,
                                          });
                                          gtag("set", "ads_data_redaction", true);
                                            &lt;/script&gt;';
                                            echo !empty(get_option('gcm-code-snippet'))
                                                ? get_option('gcm-code-snippet')
                                                : preg_replace('/\s+/S', ' ',trim($gcmCodeSnippet));
                                            ?></textarea>
                            </td>
                        </tr>
                    </table>
                    <table class="form-table coi-table">
                        <tr>
                            <th scope="row"><label for="enable-video-sdk">Enable Autoblocking of Youtube &amp; Vimeo</label></th>
                            <td>
                                <div class="coi-checkboxes">
                                    <input id="enable-video-sdk" name="enable-video-sdk" onclick="toggleContainer()" class="coi__checkbox collapsable" type="checkbox" value="1" <?php checked( get_option( 'enable-video-sdk' ), 1 ); ?> />
                                    <label for="enable-video-sdk"></label>
                                </div>
                                <p class="helper">
                                    When this feature is anabled. Youtube &amp; Vimeo iframe will automatically get blocked before consent.
                                </p>
                            </td>
                        </tr>
                    </table>
                    <table id="enable-video-sdk-container" class="form-table coi-table">
                        <tr>
                            <th scope="row"><label for="video-sdk-category">SDK Category</label></th>
                            <td>
                                <?php $selectedOption = get_option('video-sdk-category'); ?>

                                <select class="regular-text" id="video-sdk-category" name="video-sdk-category">
                                    <option value="cookie_cat_necessary" <?php selected( $selectedOption, 'cookie_cat_necessary' ) ?> >Functional</option>
                                    <option value="cookie_cat_functional" <?php selected( $selectedOption, 'cookie_cat_functional' ) ?> >Functional</option>
                                    <option value="cookie_cat_statistic" <?php selected( $selectedOption, 'cookie_cat_statistic' ) ?> >Statistic</option>
                                    <option value="cookie_cat_marketing" <?php selected( $selectedOption, 'cookie_cat_marketing' ) ?> >Marketing</option>
                                    <option value="cookie_cat_unclassified" <?php selected( $selectedOption, 'cookie_cat_unclassified' ) ?> >Unclassified</option>
                                </select>
                                <p class="helper">
                                    Choose which SDK category to use for the blocked videoes.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="placeholder-text">Placeholder Text</label></th>
                            <td>
                                        <textarea id="placeholder-text" type="text" name="placeholder-text" class="regular-text"><?php echo !empty(get_option('placeholder-text'))
                                                ? get_option('placeholder-text')
                                                : 'You have to consent to statistic cookies to see this content.<br /><span>Click here to renew consent</span>';
                                            ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="placeholder-class">Placeholder Class</label></th>
                            <td>
                                <input id="placeholder-class" type="text" name="placeholder-class" value="<?php echo !empty(get_option('placeholder-class')) ? get_option('placeholder-class') : 'placeholder-consent-text'; ?>" class="regular-text" />
                            </td>
                        </tr>
                    </table>
                    <table class="form-table coi-table">
                        <?php do_settings_sections('ci_settings'); ?>
                        <tr>
                            <th scope="row"></th>
                            <td><?php submit_button(); ?></td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="cookie-logo" style="margin:20px 0px">
                <a href="https://cookieinformation.com/" target="_blank"><img src="<?php echo plugin_dir_url(__FILE__). 'image/ci-logo.png' ?>" width="250"></a>
            </div>
        </div>
        <?php
    }
}

$cookieInformationPlg = new CookieInformationPlugin();

//$cookieInformationPlg->ci_delete_settings('dismiss_notice');
