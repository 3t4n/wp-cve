<?php
defined('WPINC') or die;

class Universal_Voice_Search_Plugin extends WP_Stack_Plugin2
{

    /**
     * @var self
     */
    public static $plugin_directory_path = null;
    public static $uvs_ios = false;
    public static $uvs_url = "";
    public static $is_chrome = false;
    public static $uvs_license_key = "";
    public static $uvs_api_access_key = null;
    public static $uvs_admin_notice_logo = "";
    public static $uvs_selected_language = "en-US";
    public static $uvs_floating_mic_position = "Middle Right";
    //public static $uvs_file_type  = '.min';
    public static $uvs_file_type = ''; // For debugging
    public static $uvs_settings_updated_ts = null;

    /**
     * This map of language name as value (Eg: English) maps to value being saved to DB for plugin language option on settings page
     *
     * With additional 130 language support feature this fallback is needed to preserve plugin language while upgrading/updating existing plugin on their site
     */
    public static $uvs_fallback_lang_map = array(
        'en-US' => 'English',
        'en-GB' => 'British English',
        'de-DE' => 'German',
        'pt-PT' => 'Portuguese',
        'zh-CN' => 'Chinese',
        'zh-TW' => 'Chinese',
        'fr-FR' => 'French',
        'ja-JP' => 'Japanese',
        'ko-KR' => 'Korean',
        'es-ES' => 'Spanish'
    );

    // For access keys
    public static $uvs_voice_services_access_keys = array(
        'api_url' => "https://yjonpgjqs9.execute-api.us-east-1.amazonaws.com/V2",
        'db_col_name' => 'uvs_navigation_voice_services_access_keys',
        'value' => array(
            'g_stt_key' => null,
            'g_tts_key' => null,
            'synched_at' => null
        )
    );

    /**
     * Plugin version.
     */
    const UVS_VERSION = '3.1.2';

    /**
     * Constructs the object, hooks in to `plugins_loaded`.
     */
    protected function __construct()
    {
        // Get database values
        self::$uvs_license_key = get_option(Universal_Voice_Search_Settings_Page::BASIC_CONFIG_OPTION_NAMES['license_key'], null);
        self::$uvs_license_key = self::uvs_sanitize_variable_for_local_script(self::$uvs_license_key);

        // Get API access key.
        self::$uvs_api_access_key = get_option('uvs_api_system_key', null);
        self::$uvs_api_access_key = self::uvs_sanitize_variable_for_local_script(self::$uvs_api_access_key);

        self::$uvs_selected_language = get_option(Universal_Voice_Search_Settings_Page::BASIC_CONFIG_OPTION_NAMES['selected_language'], 'en-US');
        self::$uvs_selected_language = self::uvs_sanitize_variable_for_local_script(self::$uvs_selected_language);

        // Detect OS by user agent
        $iPod = sanitize_text_field(stripos($_SERVER['HTTP_USER_AGENT'], "iPod"));
        $iPhone = sanitize_text_field(stripos($_SERVER['HTTP_USER_AGENT'], "iPhone"));
        $iPad = sanitize_text_field(stripos($_SERVER['HTTP_USER_AGENT'], "iPad"));
        $chrome_browser = sanitize_text_field(stripos($_SERVER['HTTP_USER_AGENT'], "Chrome"));

        if (!($iPod == false && $iPhone == false && $iPad == false)) { /*self::$uvs_ios = true;*/
        }

        if ($chrome_browser != false) { /*self::$is_chrome = true;*/
        }

        $this->hook('plugins_loaded', 'add_hooks');
    }

    /**
     * Static method to get third party voice services access keys
     *
     */
    public static function uvs_get_access_keys_from_db()
    {
        $temp_access_keys_from_db = get_option(self::$uvs_voice_services_access_keys['db_col_name'], null);

        if (!!$temp_access_keys_from_db && is_array($temp_access_keys_from_db)) {
            if (array_key_exists('g_stt_key', $temp_access_keys_from_db)) {
                self::$uvs_voice_services_access_keys['value']['g_stt_key'] = $temp_access_keys_from_db['g_stt_key'];
            }

            if (array_key_exists('g_tts_key', $temp_access_keys_from_db)) {
                self::$uvs_voice_services_access_keys['value']['g_tts_key'] = $temp_access_keys_from_db['g_tts_key'];
            }

            if (array_key_exists('synched_at', $temp_access_keys_from_db)) {
                self::$uvs_voice_services_access_keys['value']['synched_at'] = $temp_access_keys_from_db['synched_at'];
            }

            unset($temp_access_keys_from_db);
        }
    }


    /**
     * Adds hooks.
     */
    public function add_hooks()
    {
        self::$uvs_settings_updated_ts = Universal_Voice_Search_Settings_Page::uvs_settings_modified_timestamp('set');

        $this->hook('init');
        $this->hook('admin_enqueue_scripts', 'enqueue_admin_scripts');

        if (
            (!empty(self::$uvs_license_key) && !empty(self::$uvs_api_access_key)) ||
            (UVS_CLIENT['chrome'] === true && UvsLanguage::gcp_supported(self::$uvs_selected_language))
        ) {
            $this->hook('wp_enqueue_scripts', 'enqueue_frontend_scripts');
        }

        // Register action to hook into admin_notices to display dashboard notice for non-HTTPS site
        if (is_ssl() == false) {
            add_action('admin_notices', function () {
                ?>
                <div class="notice notice-error is-dismissible">
                    <p>
                        <?php echo wp_kses_post(self::$uvs_admin_notice_logo); ?>
                        <br />
                        <?php echo wp_kses_post(UVS_LANGUAGE_LIBRARY['other']['nonHttpsNotice']); ?>
                    </p>
                </div>
                <?php
            });
        }

        // Generate mp3 files on version change
        Universal_Voice_Search_Settings_Page::uvs_generate_short_phrases_on_update(self::$uvs_selected_language);

        // Register the STT service call action
        add_action('wp_ajax_' . 'uvs_log_service_call', array($this, 'uvs_log_service_call'));
        add_action('wp_ajax_nopriv_' . 'uvs_log_service_call', array($this, 'uvs_log_service_call'));

        // Register the action for HTTP Ajax request to refresh voice services token and keys
        add_action('wp_ajax_nopriv_' . 'uvs_refresh_access_keys', array($this, 'uvs_refresh_access_keys'));
        add_action('wp_ajax_' . 'uvs_refresh_access_keys', array($this, 'uvs_refresh_access_keys'));

        // Register action to hook into admin_notices to display dahsboard notices when license key is missing or invalid
        if ((empty(self::$uvs_license_key) || empty(self::$uvs_api_access_key)) && UVS_CLIENT['chrome'] === false) {
            add_action('admin_notices', array($this, 'notice_non_chrome'));
        }
    }

    /**
     * Method as action to invoke when license key is missing and browser is non chrome
     */
    public function notice_non_chrome()
    {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p>
        <?php echo wp_kses_post(self::$uvs_admin_notice_logo); ?>
        <br />
        <?php echo wp_kses_post("<b>" . UVS_LANGUAGE_LIBRARY['other']['nonChromeNotice']['warning'] . "</b>" . UVS_LANGUAGE_LIBRARY['other']['nonChromeNotice']['thisPlugin']); ?>
        <a target="blank" href="https://speak2web.com/plugin/#plan">
            <?php echo wp_kses_post(UVS_LANGUAGE_LIBRARY['other']['nonChromeNotice']['goPro']); ?>
        </a>
        <?php echo wp_kses_post(UVS_LANGUAGE_LIBRARY['other']['nonChromeNotice']['supportMoreBrowsers']); ?>
        </p>
    </div>
<?php
    }

    /**
     * Initializes the plugin, registers textdomain, etc.
     * Most of WP is loaded at this stage, and the user is authenticated
     */
    public function init()
    {
        self::$uvs_url = $this->get_url();
        self::$uvs_admin_notice_logo = "<img style='margin-left: -7px;vertical-align:middle;width:110px; height: 36px;' src='" . self::$uvs_url . "images/speak2web_logo.png'/>|<b> Universal Voice Search</b>";

        // Get plugin directory path and add trailing slash if needed (For browser compatibility)
        self::$plugin_directory_path = plugin_dir_path(__DIR__);
        $trailing_slash = substr(self::$plugin_directory_path, -1);

        if ($trailing_slash != '/') {
            self::$plugin_directory_path .= '/';
        }

        if (isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] == 'plugins.php') {
            add_filter('plugin_row_meta', array(&$this, 'custom_plugin_row_meta'), 10, 2);
        }

        $this->load_textdomain('universal-voice-search', '/languages');

        // To enable floating mic by default (only when 'uvs_floating_mic' option is missing from DB)
        $is_uvs_floating_mic_exist = get_option(Universal_Voice_Search_Settings_Page::BASIC_CONFIG_OPTION_NAMES['floating_mic']);

        if ($is_uvs_floating_mic_exist === false) {
            update_option(Universal_Voice_Search_Settings_Page::BASIC_CONFIG_OPTION_NAMES['floating_mic'], 'yes');
        }

        // Get floating mic position from DB
        self::$uvs_floating_mic_position = get_option(Universal_Voice_Search_Settings_Page::BASIC_CONFIG_OPTION_NAMES['floating_mic_position']);

        if (self::$uvs_floating_mic_position === false) {
            update_option(Universal_Voice_Search_Settings_Page::BASIC_CONFIG_OPTION_NAMES['floating_mic_position'], 'Middle Right');
            self::$uvs_floating_mic_position = 'Middle Right';
        }

        // load access keys of third party voice services from local DB
        self::uvs_get_access_keys_from_db();

        // Obtain third party voice services token and keys from api
        self::uvs_synch_voice_access_keys();
    }

    /**
     * Static method to get data related to file
     *
     * @param $intent - string : 'url' or 'timestamp'
     * @param $partial_file_path - string : Path of file (Partial and mostly relative path)
     * @param $file_extension - string : 'js' or 'css'
     *
     * $returns $uvs_file_data - string : Time as a Unix timestamp or absolute url to the file
     */
    public static function uvs_get_file_meta_data($intent = "", $partial_file_path = "", $file_extension = "")
    {
        $uvs_file_data = "";

        try {
            if (empty($file_extension) || empty($partial_file_path) || empty($intent))
                throw new Exception("VDN: Error while getting file data.", 1);

            $intent = strtolower(trim($intent));
            $file_ext = '.' . str_replace(".", "", trim($file_extension));
            $partial_file_path = trim($partial_file_path);

            if ($intent == 'timestamp') {
                if (!empty(self::$uvs_settings_updated_ts)) {
                    $uvs_file_data = self::$uvs_settings_updated_ts;
                } else {
                    $uvs_file_data = filemtime(UVS_PLUGIN['ABS_PATH'] . $partial_file_path . self::$uvs_file_type . $file_ext);
                }
            } else if ($intent == 'url') {
                $uvs_file_data = UVS_PLUGIN['ABS_URL'] . $partial_file_path . self::$uvs_file_type . $file_ext;
            }
        } catch (\Exception $ex) {
            $uvs_file_data = "";
        }

        return $uvs_file_data;
    }

    /**
     * Method to enqueue JS scripts and CSS of Admin for loading 
     */
    public function enqueue_admin_scripts()
    {
        // Enqueue CSS: uvs-settings.css
        wp_enqueue_style(
            'uvs_settings_css',
            self::uvs_get_file_meta_data('url', 'css/settings/uvs-settings', 'css'),
            array(),
            self::uvs_get_file_meta_data('timestamp', 'css/settings/uvs-settings', 'css'),
            'screen'
        );

        // Enqueue JS: uvs-settings.js
        wp_enqueue_script(
            'uvs-settings',
            self::uvs_get_file_meta_data('url', 'js/settings/uvs-settings', 'js'),
            array(),
            self::uvs_get_file_meta_data('timestamp', 'js/settings/uvs-settings', 'js'),
            true
        );

    }

    /**
     * Method to enqueue JS scripts and CSS for loading at Front end
     */
    public function enqueue_frontend_scripts()
    {
        //################################################################################
        //
        // Enqueue 'universal-voice-search' CSS file to load at front end
        //
        //################################################################################
        wp_enqueue_style(
            'universal-voice-search',
            self::uvs_get_file_meta_data('url', 'css/universal-voice-search', 'css'),
            array(),
            self::uvs_get_file_meta_data('timestamp', 'css/universal-voice-search', 'css'),
            'screen'
        );

        //################################################################################
        //
        // Enqueue 'uvs.text-library' javasctipt file to load at front end
        //
        //################################################################################        
        wp_enqueue_script(
            'uvs.text-library',
            self::uvs_get_file_meta_data('url', 'js/uvs.text-library', 'js'),
            array(),
            self::uvs_get_file_meta_data('timestamp', 'js/uvs.text-library', 'js'),
            true
        );

        //##################################################################################################################
        // Determine STT language context for plugin
        //##################################################################################################################
        $uvs_stt_language_context = array(
            'gcp' => array(
                'stt' => 'N',
                'langCode' => null,
                'endPoint' => null,
                'key' => null,
                'qs' => array('key' => null)
            )
        );

        $uvs_gcp_supported = UvsLanguage::gcp_supported(self::$uvs_selected_language);
        $uvs_lang_not_supported_by_vendors = false;

        if (UVS_CLIENT['chrome'] === true) {
            if ($uvs_gcp_supported === true) {
                $uvs_stt_language_context['gcp']['stt'] = 'Y';
            } else {
                $uvs_stt_language_context['gcp']['stt'] = 'Y';
                $uvs_lang_not_supported_by_vendors = true;
            }
        } else {
            if ($uvs_gcp_supported === true) {
                $uvs_stt_language_context['gcp']['stt'] = 'Y';
            }
        }

        if ($uvs_lang_not_supported_by_vendors === true) {
            self::$uvs_selected_language = 'en-US';
            update_option(Universal_Voice_Search_Settings_Page::BASIC_CONFIG_OPTION_NAMES['selected_language'], self::$uvs_selected_language);
        }

        if ($uvs_stt_language_context['gcp']['stt'] == 'Y') {
            $uvs_gcp_lang_code = UvsLanguage::$gcp_language_set[self::$uvs_selected_language][UvsLanguage::LANG_CODE];
            $uvs_gcp_key = self::$uvs_voice_services_access_keys['value']['g_stt_key'];

            $uvs_stt_language_context['gcp']['endPoint'] = 'https://speech.googleapis.com/v1/speech:recognize';
            $uvs_stt_language_context['gcp']['langCode'] = $uvs_gcp_lang_code;
            $uvs_stt_language_context['gcp']['key'] = $uvs_gcp_key;
            $uvs_stt_language_context['gcp']['qs']['key'] = '?key=';
        }

        wp_localize_script('uvs.text-library', '_uvsSttLanguageContext', $uvs_stt_language_context);
        wp_localize_script('uvs.text-library', '_uvsTextPhrases', UvsLanguage::$textual_phrases[self::$uvs_selected_language]);

        wp_add_inline_script('uvs.text-library', 'uvsWorkerPath =' . json_encode($this->get_url() . 'js/recorderjs/uvs.audio-recorder-worker' . self::$uvs_file_type . '.js'));

        $count_nonce = wp_create_nonce('service_call_count');
        $uvs_keys_refresh_nonce = wp_create_nonce('keys_refresh');

        wp_localize_script(
            'uvs.text-library',
            'uvsAjaxObj',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => $count_nonce,
                'keys_nonce' => $uvs_keys_refresh_nonce
            )
        );

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['SERVER_NAME'];

        $uvs_floating_mic = get_option(Universal_Voice_Search_Settings_Page::BASIC_CONFIG_OPTION_NAMES['floating_mic'], null);
        $uvs_floating_mic = self::uvs_sanitize_variable_for_local_script($uvs_floating_mic);

        wp_localize_script(
            'uvs.text-library',
            'universal_voice_search',
            array(
                'button_message' => __('Speech Input', 'universal-voice-search'),
                'talk_message' => __('Start Talking…', 'universal-voice-search'),
            )
        );

        $uvs_mic_listening_timeout = get_option(Universal_Voice_Search_Settings_Page::BASIC_CONFIG_OPTION_NAMES['mic_listening_timeout'], null);
        $uvs_mic_listening_timeout = self::uvs_sanitize_variable_for_local_script($uvs_mic_listening_timeout);

        if (is_null($uvs_mic_listening_timeout)) {
            update_option(Universal_Voice_Search_Settings_Page::BASIC_CONFIG_OPTION_NAMES['mic_listening_timeout'], '8');
            $uvs_mic_listening_timeout = '8';
        }

        $uvs_current_value = get_option('uvs_current_value', "0");
        $uvs_last_value = get_option('uvs_last_value', "0");
        $uvs_last_value_updated_at = get_option('uvs_last_value_updated_at', null);
        $uvs_last_value_updated_at = self::uvs_sanitize_variable_for_local_script($uvs_last_value_updated_at);

        $uvs_service_logs = array(
            'updatedAt' => $uvs_last_value_updated_at,
            'currentValue' => $uvs_current_value,
            'lastValue' => $uvs_last_value,
        );

        wp_localize_script('uvs.text-library', 'uvsServiceLogs', $uvs_service_logs);

        $uvs_mute_audio_phrases = get_option(Universal_Voice_Search_Settings_Page::BASIC_CONFIG_OPTION_NAMES['mute_audio_phrases'], null);
        $uvs_mute_audio_phrases = self::uvs_sanitize_variable_for_local_script($uvs_mute_audio_phrases);

        $uvs_single_click = get_option(Universal_Voice_Search_Settings_Page::BASIC_CONFIG_OPTION_NAMES['single_click'], null);
        $uvs_single_click = self::uvs_sanitize_variable_for_local_script($uvs_single_click);

        $uvs_elementor = get_option(Universal_Voice_Search_Settings_Page::BASIC_CONFIG_OPTION_NAMES['elementor_mic'], null);
        $uvs_elementor = self::uvs_sanitize_variable_for_local_script($uvs_elementor);

        // Localizes a registered script with JS variable for Keyboard Mic Switch
        $uvs_keyboard_mic_switch = get_option(Universal_Voice_Search_Settings_Page::BASIC_CONFIG_OPTION_NAMES['keyboard_mic_switch'], '');
        $uvs_keyboard_mic_switch = self::uvs_sanitize_variable_for_local_script($uvs_keyboard_mic_switch);

        // Localizes a registered script with JS variable for Special keys Keyboard Mic Switch
        $uvs_keyboard_special_key = get_option(Universal_Voice_Search_Settings_Page::BASIC_CONFIG_OPTION_NAMES['keyboard_special_key'], 'OtherKey');
        $uvs_keyboard_special_key = self::uvs_sanitize_variable_for_local_script($uvs_keyboard_special_key);

        $uvs_localizer = array(
            '_uvsSingleClick' => $uvs_single_click,
            '_uvsElementor' => $uvs_elementor,
            '_uvsMuteAudioPhrases' => $uvs_mute_audio_phrases,
            'uvsMicListenTimeoutDuration' => $uvs_mic_listening_timeout,
            'uvsXApiKey' => self::$uvs_api_access_key,
            'uvsFloatingMic' => $uvs_floating_mic,
            'uvsSelectedMicPosition' => self::$uvs_floating_mic_position,
            'uvsCurrentHostName' => $protocol . $domainName,
            'uvsSelectedLanguage' => self::$uvs_selected_language,
            'uvsImagesPath' => self::$uvs_url . 'images/',
            '_uvsPath' => UVS_PLUGIN['ABS_URL'],
            'uvsKeyboardMicSwitch' => $uvs_keyboard_mic_switch,
            'uvsKeyboardSpecialKey' => $uvs_keyboard_special_key
        );

        wp_localize_script('uvs.text-library', 'vs', $uvs_localizer);

        //################################################################################
        //
        // Enqueue 'uvs.speech-handler' javasctipt file to load at front end
        //
        //################################################################################
        wp_enqueue_script(
            'uvs.speech-handler',
            self::uvs_get_file_meta_data('url', 'js/uvs.speech-handler', 'js'),
            array(),
            self::uvs_get_file_meta_data('timestamp', 'js/uvs.speech-handler', 'js'),
            true
        );

        //################################################################################
        //
        // Enqueue 'uvs.audio-input-handler' javasctipt file to load at front end
        //
        //################################################################################
        wp_enqueue_script(
            'uvs.audio-input-handler',
            self::uvs_get_file_meta_data('url', 'js/uvs.audio-input-handler', 'js'),
            array(),
            self::uvs_get_file_meta_data('timestamp', 'js/uvs.audio-input-handler', 'js'),
            true
        );

        //################################################################################
        //
        // Enqueue 'vdn.audio-recorder' javasctipt file to load at front end
        //
        //################################################################################
        wp_enqueue_script(
            'uvs.audio-recorder',
            self::uvs_get_file_meta_data('url', 'js/recorderjs/uvs.audio-recorder', 'js'),
            array(),
            self::uvs_get_file_meta_data('timestamp', 'js/recorderjs/uvs.audio-recorder', 'js'),
            true
        );

        //################################################################################
        //
        // Enqueue 'universal-voice-search' javasctipt file to load at front end
        //
        //################################################################################
        wp_enqueue_script(
            'universal-voice-search',
            self::uvs_get_file_meta_data('url', 'js/universal-voice-search', 'js'),
            array(),
            self::uvs_get_file_meta_data('timestamp', 'js/universal-voice-search', 'js'),
            true
        );
    }

    /**
     * Method to add additional link to settings page below plugin on the plugins page.
     */
    function custom_plugin_row_meta($links, $file)
    {
        if (strpos($file, 'universal-voice-search.php') !== false) {
            $new_links = array('settings' => '<a href="' . site_url() . '/wp-admin/admin.php?page=universal-voice-search-settings" title="Universal Voice Search">Settings</a>');
            $links = array_merge($links, $new_links);
        }

        return $links;
    }

    /**
     * Class method to get REST API access key ('x-api-key') against license key instate to avail plugin (Universal Voice Search) service
     *
     * @param $convertable_license_key - String : License key customer posses
     */
    public static function uvs_get_api_key_from_license_key($convertable_license_key = null, $license_key_field_changed = false)
    {
        $result = array();

        try {
            // Throw exception when license key is blank or unavailable
            if (
                !(isset($convertable_license_key) && is_null($convertable_license_key) == false
                    && trim($convertable_license_key) != '')
            ) {
                update_option('uvs_api_system_key', '');
                throw new Exception("Error: License key is unavailable or invalid.");
            }

            $uvs_api_system_key = get_option('uvs_api_system_key', null);
            $uvs_api_system_key = isset($uvs_api_system_key) ? trim($uvs_api_system_key) : null;

            if (!empty($uvs_api_system_key) && $license_key_field_changed === false) {
                self::$uvs_api_access_key = $uvs_api_system_key;
            } else {
                $body = array('license' => trim($convertable_license_key));
                $args = array(
                    'body' => json_encode($body),
                    'timeout' => '60',
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'x-api-key' => 'jEODHPKy2z7GEIuerFBWk7a0LqVRJ7ER3aDExmbK'
                    )
                );

                $response = wp_remote_post('https://1kosjp937k.execute-api.us-east-1.amazonaws.com/V2', $args);

                // Check the response code
                $response_code = wp_remote_retrieve_response_code($response);

                if ((int) $response_code == 200) {
                    $response_body = wp_remote_retrieve_body($response);
                    $result = @json_decode($response_body, true);

                    if (!empty($result) && is_array($result)) {
                        if (array_key_exists('errorMessage', $result)) {
                            update_option('uvs_api_system_key', '');
                        } else {
                            $conversion_status_code = !empty($result['statusCode']) ? trim($result['statusCode']) : null;
                            ;
                            $conversion_status = !empty($result['status']) ? trim($result['status']) : null;

                            if (
                                !is_null($conversion_status_code) && !is_null($conversion_status)
                                && (int) $conversion_status_code == 200 && strtolower(trim($conversion_status)) == 'success'
                            ) {
                                self::$uvs_api_access_key = !empty($result['key']) ? trim($result['key']) : null;

                                if (self::$uvs_api_access_key !== null) {
                                    update_option('uvs_api_system_key', self::$uvs_api_access_key);
                                } else {
                                    update_option('uvs_api_system_key', '');
                                }
                            } else {
                                update_option('uvs_api_system_key', '');
                            }
                        }
                    }
                }
            }
        } catch (\Exception $ex) {
            self::$uvs_api_access_key = null;
        }

        self::$uvs_api_access_key = self::uvs_sanitize_variable_for_local_script(self::$uvs_api_access_key);
    }

    /**
     * Class method to sanitize empty variables
     *
     * @param $uvs_var - String : Variable to sanitize
     * @return 
     */
    public static function uvs_sanitize_variable_for_local_script($uvs_var = null)
    {
        if (empty($uvs_var)) {
            return null;
        } else {
            return $uvs_var;
        }
    }

    /**
     * Method to log STT service call count to local DB and Cloud
     *
     * @return JSON response obj
     */
    public function uvs_log_service_call()
    {
        check_ajax_referer('service_call_count');

        // Get values from database, HTTP request
        $uvs_do_update_last_value = isset($_REQUEST['updateLastValue']) ? (int) $_REQUEST['updateLastValue'] : 0;
        $uvs_current_value = (int) get_option('uvs_current_value', 0);
        $uvs_last_value = (int) get_option('uvs_last_value', 0);
        $uvs_last_value_updated_at = get_option('uvs_last_value_updated_at', null);
        $uvs_current_value_to_log = ($uvs_do_update_last_value == 1) ? $uvs_current_value : $uvs_current_value + 1;
        $uvs_temp_last_value = get_option('uvs_last_value', null); // To check if we are making initial service log call
        $uvs_log_result = array(
            'uvsSttAccess' => 'allowed',
            'updatedAt' => $uvs_last_value_updated_at,
            'currentValue' => $uvs_current_value,
            'lastValue' => $uvs_last_value
        );

        try {
            // We need to reset current value count to 0 if current count log exceeds 25000
            if ($uvs_current_value_to_log > 25000) {
                update_option('uvs_current_value', 0);
            }

            // Log service count by calling cloud API if last update was before 24 hours or current count is +50 of last count
            if (is_null($uvs_temp_last_value) || $uvs_do_update_last_value === 1 || ($uvs_current_value_to_log > ($uvs_last_value + 50))) {
                $uvs_body = array(
                    'license' => trim(self::$uvs_license_key),
                    'action' => "logCalls",
                    'currentValue' => $uvs_current_value_to_log,
                    'lastValue' => $uvs_last_value,
                );

                $uvs_args = array(
                    'body' => json_encode($uvs_body),
                    'timeout' => '60',
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'x-api-key' => 'jEODHPKy2z7GEIuerFBWk7a0LqVRJ7ER3aDExmbK'
                    )
                );

                $uvs_response = wp_remote_post('https://1kosjp937k.execute-api.us-east-1.amazonaws.com/V2', $uvs_args);

                // Check the response code
                $uvs_response_code = wp_remote_retrieve_response_code($uvs_response);


                if ($uvs_response_code == 200) {
                    $uvs_response_body = wp_remote_retrieve_body($uvs_response);
                    $uvs_result = @json_decode($uvs_response_body, true);

                    if (!empty($uvs_result) && is_array($uvs_result)) {
                        $log_status = array_key_exists("status", $uvs_result) ? strtolower($uvs_result['status']) : 'failed';
                        $actual_current_value = array_key_exists("current Value", $uvs_result) ? strtolower($uvs_result['current Value']) : null;
                        $uvs_error = array_key_exists("errorMessage", $uvs_result) ? true : false;

                        if ($log_status == 'success' && is_null($actual_current_value) === false && $uvs_error === false) {
                            // Store updated values to database
                            $uvs_current_timestamp = time(); // epoc 
                            update_option('uvs_current_value', $actual_current_value);
                            update_option('uvs_last_value', $actual_current_value);
                            update_option('uvs_last_value_updated_at', $uvs_current_timestamp);

                            // Prepare response 
                            $uvs_log_result['updatedAt'] = $uvs_current_timestamp;
                            $uvs_log_result['currentValue'] = $actual_current_value;
                            $uvs_log_result['lastValue'] = $actual_current_value;
                            $uvs_log_result['cloud'] = true;
                        }
                    }
                }
            } else {
                // Increase current count
                update_option('uvs_current_value', $uvs_current_value_to_log);

                // Prepare response
                $uvs_log_result['currentValue'] = $uvs_current_value_to_log;
                $uvs_log_result['local'] = true;
            }
        } catch (\Exception $ex) {
            // Prepare response 
            $uvs_log_result['uvsSttAccess'] = 'restricted';
        }

        wp_send_json($uvs_log_result);
    }

    /**
     * Method to register plugin for the first time
     *
     */
    public static function uvs_register_plugin()
    {
        try {
            // Get plugin first activation status and license key from DB 
            $uvs_license_key = get_option('uvs_license_key', null);
            $uvs_first_activation = get_option('uvs_first_activation', null);
            $uvs_site_name = sanitize_text_field(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']);

            if (empty($uvs_first_activation) && empty(trim($uvs_license_key))) {
                // Mark first activation activity flag in local DB 
                update_option('uvs_first_activation', true); // Store first activation flag in DB

                // Detect site language and set the plugin language
                $uvs_site_language_code = get_locale();
                $uvs_site_language_code = str_replace('_', '-', $uvs_site_language_code);

                if (!empty($uvs_site_language_code) && array_key_exists($uvs_site_language_code, UvsLanguage::get_all_languages())) {
                    update_option(Universal_Voice_Search_Settings_Page::BASIC_CONFIG_OPTION_NAMES['selected_language'], $uvs_site_language_code);
                }

                // Generate UUID and store in DB
                $uvs_new_uuid = wp_generate_uuid4();
                update_option('uvs_uuid', $uvs_new_uuid);

                $uvs_body = array(
                    'action' => 'regUVS',
                    'url' => $uvs_site_name . '_' . $uvs_new_uuid,
                );

                $uvs_args = array(
                    'body' => json_encode($uvs_body),
                    'timeout' => '60',
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'x-api-key' => 'jEODHPKy2z7GEIuerFBWk7a0LqVRJ7ER3aDExmbK'
                    )
                );

                $uvs_response = wp_remote_post('https://1kosjp937k.execute-api.us-east-1.amazonaws.com/V2', $uvs_args);

                // Check the response body
                $uvs_response_body = wp_remote_retrieve_body($uvs_response);
                $uvs_result = @json_decode($uvs_response_body, true);

                if (!empty($uvs_result) && is_array($uvs_result)) {
                    $log_status = array_key_exists('status', $uvs_result) ? strtolower(trim($uvs_result['status'])) : null;

                    if ($log_status == '200 success') {
                        // Do nothing for now                       
                    }
                }
            }
        } catch (\Exception $ex) {
            // Do nothing for now               
        }
    }

    /**
     * Method as HTTP request handler to obtain refreshed voice services token and keys
     *
     * @return JSON $uvs_refreshed_keys Containing IBM Watson STT token for now.
     *
     */
    public function uvs_refresh_access_keys()
    {
        check_ajax_referer('keys_refresh');

        self::uvs_synch_voice_access_keys(true);

        $uvs_refreshed_keys = array(
            'gStt' => self::$uvs_voice_services_access_keys['value']['g_stt_key']
        );

        wp_send_json($uvs_refreshed_keys);
    }

    /**
     * Static method to obtain access keys for Google STT & TTS and IBN Watson token
     *
     * @param boolean $forced_synch To by-pass validation to obtain token and keys from API
     *
     */
    public static function uvs_synch_voice_access_keys($forced_synch = false)
    {
        try {
            $uvs_do_synch = false;
            $uvs_g_stt_key = self::$uvs_voice_services_access_keys['value']['g_stt_key'];
            $uvs_g_tts_key = self::$uvs_voice_services_access_keys['value']['g_tts_key'];
            $uvs_synched_at = self::$uvs_voice_services_access_keys['value']['synched_at'];

            if (
                empty($uvs_g_stt_key) ||
                empty($uvs_g_tts_key) ||
                empty($uvs_synched_at) ||
                $forced_synch === true
            ) {
                $uvs_do_synch = true;
            }

            if (!!$uvs_synched_at && $uvs_do_synch === false) {
                $uvs_synched_at_threshold = $uvs_synched_at + (60 * 60 * 6);
                $uvs_current_time = time();

                if ($uvs_current_time > $uvs_synched_at_threshold) {
                    $uvs_do_synch = true;
                }
            }

            if ($uvs_do_synch === false)
                return;

            $uvs_args = array(
                'timeout' => '90',
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'x-api-key' => self::$uvs_api_access_key
                )
            );

            $uvs_response = wp_remote_get(self::$uvs_voice_services_access_keys['api_url'], $uvs_args);

            // Check the response code
            $response_code = wp_remote_retrieve_response_code($uvs_response);

            if ($response_code == 200) {
                $response_body = wp_remote_retrieve_body($uvs_response);
                $uvs_result = @json_decode($response_body, true);

                $uvs_google_stt_key = array_key_exists('gSTT', $uvs_result) ? $uvs_result['gSTT'] : null;
                $uvs_google_tts_key = array_key_exists('TTS', $uvs_result) ? $uvs_result['TTS'] : null;

                /**
                 * Deliberate separation of if blocks, do not merge them for optimization as 
                 * it would ruin the flexibility and independency of response values (none of them depend on each other anyway).
                 *
                 */
                $uvs_synchable_local_keys = 0;

                if (!!$uvs_google_stt_key) {
                    self::$uvs_voice_services_access_keys['value']['g_stt_key'] = $uvs_google_stt_key;
                    $uvs_synchable_local_keys += 1;
                }

                if (!!$uvs_google_tts_key) {
                    self::$uvs_voice_services_access_keys['value']['g_tts_key'] = $uvs_google_tts_key;
                    $uvs_synchable_local_keys += 1;
                }

                if ($uvs_synchable_local_keys > 0) {
                    self::$uvs_voice_services_access_keys['value']['synched_at'] = time();
                    update_option(self::$uvs_voice_services_access_keys['db_col_name'], self::$uvs_voice_services_access_keys['value']);
                }
            }
        } catch (\Exception $ex) {
            // Nullify keys
            self::$uvs_voice_services_access_keys['value']['g_stt_key'] = null;
            self::$uvs_voice_services_access_keys['value']['g_tts_key'] = null;
            self::$uvs_voice_services_access_keys['value']['synched_at'] = null;
        }
    }

}
