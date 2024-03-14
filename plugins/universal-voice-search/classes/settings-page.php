<?php
if (!defined('ABSPATH'))
    exit;

class Universal_Voice_Search_Settings_Page
{
    // Database field name map
    const BASIC_CONFIG_OPTION_NAMES = array(
        'license_key' => 'uvs_license_key',
        'mic_listening_timeout' => 'uvs_mic_listening_timeout',
        'selected_language' => 'uvs_selected_language',
        'floating_mic' => 'uvs_floating_mic',
        'floating_mic_position' => 'uvs_floating_mic_position',
        'mute_audio_phrases' => 'uvs_mute_audio_phrases',
        'single_click' => 'uvs_single_click',
        'elementor_mic' => 'uvs_elementor',
        'keyboard_mic_switch' => 'uvs_keyboard_mic_switch',
        'keyboard_special_key' => 'uvs_keyboard_special_key'
    );

    private $uvs_license_key = '';
    private $uvs_mic_listening_timeout = null;
    private $uvs_selected_language = 'en-US';
    private $uvs_floating_mic = null;
    private $uvs_floating_mic_position = 'Middle Right';
    private $uvs_all_languages = array();
    private $uvs_mute_audio_phrases = null;
    private $uvs_single_click = null;
    private $uvs_elementor = null;
    private $uvs_keyboard_mic_switch = '';
    private $uvs_keyboard_special_key = 'OtherKey';

    /**
     * Start up
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'uvs_add_plugin_page'));
        add_action('admin_init', array($this, 'uvs_page_init'));

        //### THIS FILTERS HOOK INTO A PROCESS BEFORE OPTION GETTING STORED TO DB
        // Register filters for basic config options
        foreach (self::BASIC_CONFIG_OPTION_NAMES as $key => $option) {
            add_filter('pre_update_option_' . $option, array($this, 'uvs_pre_update_basic_config'), 10, 3);
        }

        // Register callback to hook into post create and update (License key) option action
        add_action('add_option_' . self::BASIC_CONFIG_OPTION_NAMES['license_key'], array($this, 'uvs_post_adding_license_key'), 10, 2);
        add_action('update_option_' . self::BASIC_CONFIG_OPTION_NAMES['license_key'], array($this, 'uvs_post_update_license_key'), 10, 2);
    }

    /**
     * Static method to get timestamp from and set timestamp to DB (Timestamp of setting option update)
     *
     * @param $action - string : 'get' or 'set'
     * 
     * $returns $uvs_modified_timestamp - string : Time as a Unix timestamp
     */
    public static function uvs_settings_modified_timestamp($action = null)
    {
        $uvs_modified_timestamp = null;

        try {
            if (empty($action))
                return $uvs_modified_timestamp;

            if ($action == 'get') {
                $uvs_modified_timestamp = get_option('uvs_settings_updated_timestamp', null);
            } else if ($action == 'set') {
                $vdn_timestamp = time();
                update_option('uvs_settings_updated_timestamp', $vdn_timestamp);
                $uvs_modified_timestamp = $vdn_timestamp;
            }
        } catch (\Exception $ex) {
            $uvs_modified_timestamp = null;
        }

        return $uvs_modified_timestamp;
    }


    /**
     * Method as callback to handle basic config options data before storing to DB
     *
     * @param $old_value - string : Existing Option value from database
     * @param $new_value - string : New Option value to be stored in database
     * @param $option_name - string : Name of the option
     */
    public static function uvs_pre_update_basic_config($new_value, $old_value, $option_name)
    {
        /**
         * Comparing two string values to check if option data modified.
         *
         * Preserve settings updated timestamp 
         */
        if ($old_value != $new_value) {
            $uvs_setting_update_ts = self::uvs_settings_modified_timestamp('set');
            unset($uvs_setting_update_ts);
        }
        if ($option_name == self::BASIC_CONFIG_OPTION_NAMES['selected_language']) {
            self::uvs_inject_short_audio_phrases(trim($new_value));
        }

        return $new_value;
    }

    /**
     * Static method to fetch short audio phrases from 'speak2web.com' and create local audio file for it.
     *
     * @param String  $uvs_lang_code  Language code (eg: en-US)
     *
     */
    public static function uvs_inject_short_audio_phrases($uvs_lang_code)
    {

        $uvs_lang_file_path = $uvs_lang_code . '/' . $uvs_lang_code;
        $uvs_general = UVS_PLUGIN['ABS_PATH'] . UVS_PLUGIN['SHORT_PHRASES']['root'] . UVS_PLUGIN['SHORT_PHRASES']['general'];
        $uvs_random = UVS_PLUGIN['ABS_PATH'] . UVS_PLUGIN['SHORT_PHRASES']['root'] . UVS_PLUGIN['SHORT_PHRASES']['random'];

        // Create 'general' folder
        if (!file_exists($uvs_general . $uvs_lang_code)) {
            $oldmask = umask(0);
            mkdir($uvs_general . $uvs_lang_code, 0777, true);
            umask($oldmask);
        }

        if (!file_exists($uvs_general . $uvs_lang_code . '/lang_mismatch.txt')) {
            touch($uvs_general . $uvs_lang_code . '/lang_mismatch.txt');
        }

        $uvs_general_lang_mismatch = false;

        if (file_exists($uvs_general . $uvs_lang_code) && file_exists($uvs_general . $uvs_lang_code . '/lang_mismatch.txt')) {
            $uvs_general_lang_mismatch = true;
        }

        // Check folder exist with language name in 'general' folder
        if ($uvs_general_lang_mismatch === true) {

            $uvs_general_file_names = array(
                '_basic',
                '_mic_connect',
                '_not_audible',
                '_unavailable'
            );

            $uvs_lang_mismatch = false;

            for ($i = 0; $i < count($uvs_general_file_names); $i++) {
                $uvs_file_name = $uvs_general_file_names[$i];
                $uvs_file_exist = file_exists($uvs_general . $uvs_lang_file_path . $uvs_file_name . '.mp3');
                if (!$uvs_file_exist) {
                    $request = $uvs_general_lang_mismatch === true || !$uvs_file_exist ? wp_remote_get('https://speak2web.com/' . UVS_PLUGIN['SHORT_PHRASES']['root'] . UVS_PLUGIN['SHORT_PHRASES']['general'] . $uvs_lang_file_path . $uvs_file_name . '.mp3') : false;

                    if (is_wp_error($request)) {
                        continue;
                    }

                    $uvs_file_data = wp_remote_retrieve_body($request);
                    if ($uvs_file_data !== false) {
                        if ($uvs_file_exist) {
                            unlink($uvs_general . $uvs_lang_file_path . $uvs_file_name . '.mp3');
                        }

                        $uvs_local_file = fopen($uvs_general . $uvs_lang_file_path . $uvs_file_name . '.mp3', "w");

                        if ($uvs_local_file) {
                            // Write contents to the file
                            fwrite($uvs_local_file, $uvs_file_data);

                            // Close the file
                            fclose($uvs_local_file);
                        }
                    } else if (!$uvs_file_exist) {
                        $uvs_src_file = $uvs_general . 'en-US/en-US' . $uvs_file_name . '.mp3';
                        $uvs_dest_file = $uvs_general . $uvs_lang_file_path . $uvs_file_name . '.mp3';
                        copy($uvs_src_file, $uvs_dest_file);

                        if ($uvs_lang_mismatch !== true) {
                            $uvs_lang_mismatch = true;
                        }
                    } else {
                        if ($uvs_lang_mismatch !== true) {
                            $uvs_lang_mismatch = true;
                        }
                    }
                }
            }

            if ($uvs_lang_mismatch === true) {
                $uvs_lang_mismatch = false;

                if ($uvs_general_lang_mismatch === false) {
                    touch($uvs_general . $uvs_lang_code . '/lang_mismatch.txt');
                }
            } else {
                if ($uvs_general_lang_mismatch === true) {
                    unlink($uvs_general . $uvs_lang_code . '/lang_mismatch.txt');
                }
            }
        }

        // Create 'random' folder
        if (!file_exists($uvs_random . $uvs_lang_code)) {
            $oldmask = umask(0);
            mkdir($uvs_random . $uvs_lang_code, 0777, true);
            umask($oldmask);
        }

        if (!file_exists($uvs_random . $uvs_lang_code . '/lang_mismatch.txt')) {
            touch($uvs_random . $uvs_lang_code . '/lang_mismatch.txt');
        }

        $uvs_random_lang_mismatch = false;

        if (file_exists($uvs_random . $uvs_lang_code) && file_exists($uvs_random . $uvs_lang_code . '/lang_mismatch.txt')) {
            $uvs_random_lang_mismatch = true;
        }

        // Check folder exist with language name in 'random' folder
        if ($uvs_random_lang_mismatch === true) {

            $uvs_lang_mismatch = false;

            for ($j = 0; $j < 10; $j++) {
                $uvs_file_name = '_' . $j;
                $uvs_file_exist = file_exists($uvs_random . $uvs_lang_file_path . $uvs_file_name . '.mp3');
                if (!$uvs_file_exist) {
                    $request = $uvs_random_lang_mismatch === true || !$uvs_file_exist ? wp_remote_get('https://speak2web.com/' . UVS_PLUGIN['SHORT_PHRASES']['root'] . UVS_PLUGIN['SHORT_PHRASES']['random'] . $uvs_lang_file_path . $uvs_file_name . '.mp3') : false;

                    if (is_wp_error($request)) {
                        continue;
                    }
                    $uvs_file_data = wp_remote_retrieve_body($request);

                    if ($uvs_file_data !== false) {
                        if ($uvs_file_exist) {
                            unlink($uvs_random . $uvs_lang_file_path . $uvs_file_name . '.mp3');
                        }

                        $uvs_local_file = fopen($uvs_random . $uvs_lang_file_path . $uvs_file_name . '.mp3', "w");

                        if ($uvs_local_file) {
                            // Write contents to the file
                            fwrite($uvs_local_file, $uvs_file_data);

                            // Close the file
                            fclose($uvs_local_file);
                        }
                    } else if (!$uvs_file_exist) {
                        $uvs_src_file = $uvs_random . 'en-US/en-US' . $uvs_file_name . '.mp3';
                        $uvs_dest_file = $uvs_random . $uvs_lang_file_path . $uvs_file_name . '.mp3';
                        copy($uvs_src_file, $uvs_dest_file);

                        if ($uvs_lang_mismatch !== true) {
                            $uvs_lang_mismatch = true;
                        }
                    } else {
                        if ($uvs_lang_mismatch !== true) {
                            $uvs_lang_mismatch = true;
                        }
                    }
                }
            }

            if ($uvs_lang_mismatch === true) {
                $uvs_lang_mismatch = false;

                if ($uvs_random_lang_mismatch === false) {
                    touch($uvs_random . $uvs_lang_code . '/lang_mismatch.txt');
                }
            } else {
                if ($uvs_random_lang_mismatch === true) {
                    unlink($uvs_random . $uvs_lang_code . '/lang_mismatch.txt');
                }
            }
        }
    }

    /**
     * Method for generate files when plugin update
     * 
     * @param string $language
     */
    public static function uvs_generate_short_phrases_on_update($language)
    {
        $plugin_data = get_file_data(UVS_PLUGIN['ABS_PATH'] . '/universal-voice-search.php', [
            'Version' => 'Version'
        ], 'plugin');
        $uvs_version = get_option('uvs_version');
        $uvs_new_version = Universal_Voice_Search_Plugin::UVS_VERSION !== $plugin_data['Version'] ? $plugin_data['Version'] : Universal_Voice_Search_Plugin::UVS_VERSION;
        if ($uvs_version !== $uvs_new_version || $uvs_version === null) {
            update_option('uvs_version', $uvs_new_version);
            self::uvs_inject_short_audio_phrases($language);
        }
    }

    /**
     * Method as callback post to license key option creation in DB
     *
     * @param $option_name - string : Option name
     * @param $option_value - string : Option value
     */
    public function uvs_post_adding_license_key($option_name, $option_value)
    {
        try {
            Universal_Voice_Search_Plugin::uvs_get_api_key_from_license_key(trim($option_value), true);

            $uvs_setting_update_ts = self::uvs_settings_modified_timestamp('set');
            unset($uvs_setting_update_ts);
        } catch (\Exception $ex) {
            // Do nothing for now
        }
    }

    /**
     * Method as callback post to license key option update in DB
     *
     * @param $old_value - string : Option value before update
     * @param $new_value - string : Updated Option value
     */
    public function uvs_post_update_license_key($old_value, $new_value)
    {
        try {
            $option_value = strip_tags(stripslashes($new_value));

            if ($old_value != trim($option_value)) {
                Universal_Voice_Search_Plugin::uvs_get_api_key_from_license_key(trim($option_value), true);

                $uvs_setting_update_ts = self::uvs_settings_modified_timestamp('set');
                unset($uvs_setting_update_ts);
            }
        } catch (\Exception $ex) {
            // Do nothing for now
        }
    }

    /**
     * Add options page
     */
    public function uvs_add_plugin_page()
    {
        // This page will be under "Settings"
        add_submenu_page(
            'options-general.php',
            // Parent menu as 'settings'
            'Universal Voice Search',
            'Universal Voice Search',
            'manage_options',
            'universal-voice-search-settings',
            // Slug for page
            array($this, 'uvs_settings_create_page') // View 
        );
    }

    /**
     * Options/Settings page callback to create view/html of settings page
     */
    public function uvs_settings_create_page()
    {
        // For license key
        $this->uvs_license_key = strip_tags(stripslashes(get_option(self::BASIC_CONFIG_OPTION_NAMES['license_key'], '')));
        $this->uvs_license_key = !empty($this->uvs_license_key) ? $this->uvs_license_key : '';

        if (empty($this->uvs_license_key)) {
            update_option('uvs_api_system_key', '');
        }

        // For Mic listening auto timeout
        $this->uvs_mic_listening_timeout = strip_tags(stripslashes(get_option(self::BASIC_CONFIG_OPTION_NAMES['mic_listening_timeout'], null)));

        // if voice type is blank then always store voice type as male
        if (empty($this->uvs_mic_listening_timeout) || $this->uvs_mic_listening_timeout < 8) {
            update_option(self::BASIC_CONFIG_OPTION_NAMES['mic_listening_timeout'], 8);
            $this->uvs_mic_listening_timeout = 8;
        } elseif ($this->uvs_mic_listening_timeout > 20) {
            update_option(self::BASIC_CONFIG_OPTION_NAMES['mic_listening_timeout'], 20);
            $this->uvs_mic_listening_timeout = 20;
        }

        // For language
        $this->uvs_selected_language = strip_tags(
            stripslashes(
                get_option(
                    self::BASIC_CONFIG_OPTION_NAMES['selected_language'],
                    'en-US'
                )
            )
        );

        // For floating mic
        $this->uvs_floating_mic = strip_tags(stripslashes(get_option(self::BASIC_CONFIG_OPTION_NAMES['floating_mic'], null)));

        // For Keyboard Mic Switch
        $this->uvs_keyboard_mic_switch = strip_tags(
            stripslashes(
                get_option(
                    self::BASIC_CONFIG_OPTION_NAMES['keyboard_mic_switch'],
                    ''
                )
            )
        );

        // For Special keys Keyboard Mic Switch
        $this->uvs_keyboard_special_key = strip_tags(
            stripslashes(
                get_option(
                    self::BASIC_CONFIG_OPTION_NAMES['keyboard_special_key'],
                    'OtherKey'
                )
            )
        );

        // For Mic Position
        $this->uvs_floating_mic_position = strip_tags(
            stripslashes(
                get_option(
                    self::BASIC_CONFIG_OPTION_NAMES['floating_mic_position'],
                    'Middle Right'
                )
            )
        );

        $this->uvs_all_languages = UvsLanguage::get_all_languages();
        $this->uvs_all_languages = isset($this->uvs_all_languages) ? $this->uvs_all_languages : array('en-US' => array(UvsLanguage::NAME => 'English (United States)', UvsLanguage::LANG_CODE => 'en-US'));

        // For mute audio phrases
        $this->uvs_mute_audio_phrases = strip_tags(stripslashes(get_option(self::BASIC_CONFIG_OPTION_NAMES['mute_audio_phrases'], null)));
        // For single click
        $this->uvs_single_click = strip_tags(stripslashes(get_option(self::BASIC_CONFIG_OPTION_NAMES['single_click'], null)));
        // For Elementor
        $this->uvs_elementor = strip_tags(stripslashes(get_option(self::BASIC_CONFIG_OPTION_NAMES['elementor_mic'], null)));
?>
        <div class="wrap">
            <div id="uvsavigationSettingsWrapper">
                <div id="uvsavigationSettingsHeader" class="uvs-row">
                    <div class="uvs-setting-header-column-1"><br>
                        <span id="uvsavigationSettingsPageHeading">Universal Voice Search Setup</span>
                    </div>
                    <div class="uvs-setting-header-column-2">
                        <a title="Wordpress Plugin - speak2web" target="blank" href="https://speak2web.com/plugin/">
                            <img id="uvsavigationSettingsPageHeaderLogo" src="<?php echo esc_url(dirname(plugin_dir_url(__FILE__)) . '/images/speak2web_logo.png') ?>">
                        </a>
                    </div>
                </div>

                <form id="uvsavigationBasicConfigForm" method="post" action="options.php">
                    <?php
                    // This prints out all hidden setting fields
                    settings_fields('uvs-basic-config-settings-group');
                    do_settings_sections('uvs-settings');

                    // To display errors
                    settings_errors('uvs-settings', true, true);
                    ?>
                    <div id="uvsavigationBasicConfigSection" class='uvs-row uvs-card'>
                        <div id="uvsBasicConfHeaderSection" class="uvs-setting-basic-config-column-1 uvs-basic-config-section-title">
                            <table id="uvsavigationBasicConfHeaderTable">
                                <tr>
                                    <th>
                                        <h4><u>
                                                <?php echo wp_kses_post(UVS_LANGUAGE_LIBRARY['basicConfig']['basicConfiguration']); ?>
                                            </u>
                                        </h4>
                                    </th>
                                </tr>
                            </table>
                        </div>
                        <div class="uvs-setting-basic-config-column-2">
                            <div class="uvs-basic-config-sub-row">
                                <div>
                                    <?php echo wp_kses_post(UVS_LANGUAGE_LIBRARY['basicConfig']['selectLanguage']); ?>
                                    <select id="uvsLanguage" class="uvs-language" name="<?php echo esc_attr(self::BASIC_CONFIG_OPTION_NAMES['selected_language']); ?>">
                                        <?php
                                        foreach ($this->uvs_all_languages as $langCode => $lang) {
                                        ?>
                                            <option <?php selected($langCode, $this->uvs_selected_language); ?> value=<?php echo esc_attr($langCode); ?>><?php echo esc_attr($lang[UvsLanguage::NAME]); ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="uvs-basic-config-sub-row">
                                <div id='uvsSubscribe'>
                                    <?php echo wp_kses_post(UVS_LANGUAGE_LIBRARY['basicConfig']['subscribe']); ?><a href="https://speak2web.com/voice-search-for-wordpress-plugin" target="_blank">https://speak2web.com/voice-search-for-wordpress-plugin</a>
                                </div>
                                <div class="uvs-basic-config-attached-label-column">License Key</div>
                                <div class="uvs-basic-config-attached-input-column">
                                    <input type="text" name="<?php echo esc_attr(self::BASIC_CONFIG_OPTION_NAMES['license_key']); ?>" id="uvsavigationLicenseKey" placeholder="<?php echo esc_attr(UVS_LANGUAGE_LIBRARY['basicConfig']['copyYourLicenseKey']); ?>" value="<?php echo esc_attr($this->uvs_license_key); ?>" />
                                </div>
                                <?php if (strlen($this->uvs_license_key) == 32)
                                    echo "<script type=\"text/javascript\">
                                    var subscribe_bar = document.getElementById('uvsSubscribe'); 
                                    subscribe_bar.style.display = 'none';
                                    </script>
                                ";
                                ?>
                            </div>
                            <div class="uvs-basic-config-sub-row">
                                <span class="uvs-autotimeout-label">
                                    <label for="uvsAutotimeoutMic">
                                        <?php echo esc_attr(UVS_LANGUAGE_LIBRARY['basicConfig']['autoTimeoutDuration']); ?>
                                        <input id="uvsAutotimeoutMic" class="uvs-autotimeout-mic" type='number' name="<?php echo esc_attr(self::BASIC_CONFIG_OPTION_NAMES['mic_listening_timeout']); ?>" min="8" max="20" step="1" onKeyup="uvsResetTimeoutDefaultValue(this, event)" onKeydown="uvsValidateTimeoutValue(this, event)" value="<?php echo esc_attr($this->uvs_mic_listening_timeout); ?>" />
                                    </label>
                                </span>
                            </div>
                            <div class="uvs-basic-config-sub-row">
                                <label for="uvsMuteAudioPhrases">
                                    <input id="uvsMuteAudioPhrases" type='checkbox' name="<?php echo esc_attr(self::BASIC_CONFIG_OPTION_NAMES['mute_audio_phrases']); ?>" value="yes" <?php checked('yes', $this->uvs_mute_audio_phrases); ?>> <?php echo esc_attr(UVS_LANGUAGE_LIBRARY['basicConfig']['muteAudioPhrases']); ?>
                                </label>
                            </div>
                            <!-- Floating Mic Position -->
                            <div class="uvs-basic-config-sub-row">
                                <div class="uvs-dotted-border">
                                    <b>
                                        <?php echo wp_kses_post(UVS_LANGUAGE_LIBRARY['basicConfig']['floatingMicOptions']); ?>
                                    </b>
                                    <hr>
                                    <div class="uvs-basic-config-sub-row">
                                        <label for="uvsFloatingMicPosition">
                                            <?php echo wp_kses_post(UVS_LANGUAGE_LIBRARY['basicConfig']['selectFloatingMicPosition']); ?>
                                            <select id="uvsFloatingMicPosition" name="<?php echo self::BASIC_CONFIG_OPTION_NAMES['floating_mic_position']; ?>">
                                                <option value="Middle Right" <?php selected('Middle Right', $this->uvs_floating_mic_position); ?>>Middle Right</option>
                                                <option value="Middle Left" <?php selected('Middle Left', $this->uvs_floating_mic_position); ?>>Middle Left</option>
                                                <option value="Top Right" <?php selected('Top Right', $this->uvs_floating_mic_position); ?>>Top Right</option>
                                                <option value="Top Left" <?php selected('Top Left', $this->uvs_floating_mic_position); ?>>Top Left</option>
                                                <option value="Bottom Right" <?php selected('Bottom Right', $this->uvs_floating_mic_position); ?>>Bottom Right</option>
                                                <option value="Bottom Left" <?php selected('Bottom Left', $this->uvs_floating_mic_position); ?>>Bottom Left</option>
                                            </select>
                                        </label>
                                    </div>
                                    <div class="uvs-basic-config-sub-row">
                                        <label for="uvsFloatingMic">
                                            <input id="uvsFloatingMic" type='checkbox' name="<?php echo esc_attr(self::BASIC_CONFIG_OPTION_NAMES['floating_mic']); ?>" value="yes" <?php checked('yes', $this->uvs_floating_mic); ?>> <?php echo esc_attr(UVS_LANGUAGE_LIBRARY['basicConfig']['floatingMic']); ?>
                                        </label>
                                    </div>
                                    <div class="uvs-basic-config-sub-row">
                                        <label for="uvsSingleClick">
                                            <input id="uvsSingleClick" type='checkbox' name="<?php echo wp_kses_post(self::BASIC_CONFIG_OPTION_NAMES['single_click']); ?>" value="yes" <?php checked('yes', $this->uvs_single_click); ?>> Enable single
                                            click transcription.
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- END Floating Mic Position -->
                            <div class="uvs-basic-config-sub-row">
                                <div class="uvs-dotted-border">
                                    <strong>Trigger STT Mic Using Key</strong><br>
                                    <hr>
                                    <p style="color: gray;"><b style="color: blue;">&#x2139; </b>To trigger STT mic, press
                                        selected key two times.</p>
                                    <label for="uvsSpecialKeyAlt" style="margin-right: 8px; margin-top: 5px;">
                                        <input type="radio" id="uvsSpecialKeyAlt" name="<?php echo esc_attr(self::BASIC_CONFIG_OPTION_NAMES['keyboard_special_key']); ?>" value="Alt" onclick="uvstoggleInputFieldOtherKey('Alt')" <?php checked('Alt', $this->uvs_keyboard_special_key); ?>>
                                        Alt
                                    </label>
                                    <label for="uvsSpecialKeyCtrl" style="margin-right: 8px;">
                                        <input type="radio" id="uvsSpecialKeyCtrl" name="<?php echo esc_attr(self::BASIC_CONFIG_OPTION_NAMES['keyboard_special_key']); ?>" value="Control" onclick="uvstoggleInputFieldOtherKey('Control')" <?php checked('Control', $this->uvs_keyboard_special_key); ?>>
                                        Ctrl
                                    </label>
                                    <label for="uvsSpecialKeyShift" style="margin-right: 8px;">
                                        <input type="radio" id="uvsSpecialKeyShift" name="<?php echo esc_attr(self::BASIC_CONFIG_OPTION_NAMES['keyboard_special_key']); ?>" value="Shift" onclick="uvstoggleInputFieldOtherKey('Shift')" <?php checked('Shift', $this->uvs_keyboard_special_key); ?>>
                                        Shift
                                    </label>
                                    <label for="uvsSpecialKeySpace" style="margin-right: 8px;">
                                        <input type="radio" id="uvsSpecialKeySpace" name="<?php echo esc_attr(self::BASIC_CONFIG_OPTION_NAMES['keyboard_special_key']); ?>" value="Space" onclick="uvstoggleInputFieldOtherKey('Space')" <?php checked('Space', $this->uvs_keyboard_special_key); ?>>
                                        Space
                                    </label>
                                    <label for="uvsSpecialKeyOtherKey">
                                        <input type="radio" id="uvsSpecialKeyOtherKey" name="<?php echo esc_attr(self::BASIC_CONFIG_OPTION_NAMES['keyboard_special_key']); ?>" value="OtherKey" onclick="uvstoggleInputFieldOtherKey('OtherKey')" <?php checked('OtherKey', $this->uvs_keyboard_special_key); ?>>
                                        OtherKey
                                    </label>
                                    <label for="uvsKeyBoardSwitch" class="uvsShowOtherInput uvs-hide"><br><br>
                                        <b>Key<span class="uvs-important">*</span> :</b>
                                        <input style="width: 6.2%;" type="text" maxlength="1" placeholder="a - z" oninput="uvsValidateValueForOtherKey(this, event)" name="<?php echo esc_attr(self::BASIC_CONFIG_OPTION_NAMES['keyboard_mic_switch']); ?>" id="uvsKeyBoardSwitch" value="<?php echo esc_attr($this->uvs_keyboard_mic_switch); ?>">
                                    </label>
                                    <div class="uvsWarningInputKey"></div>
                                </div>
                            </div>
                            <div class="uvs-basic-config-sub-row">
                                <div class="uvs-dotted-border">
                                    <strong>Elementor Settings</strong>
                                    <hr>
                                    <div>
                                        <label for="uvsElementorSettings">
                                            <input id="uvsElementorSettings" type="checkbox" name="<?php echo esc_attr(self::BASIC_CONFIG_OPTION_NAMES['elementor_mic']); ?>" value="yes" <?php checked('yes', $this->uvs_elementor); ?>> Enable Elementor
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="uvs-setting-basic-config-column-3 uvs-basic-config-sub-row">
                            <?php
                            $other_attributes = array('id' => 'uvsavigationBasicConfigSettingsSave');
                            submit_button(
                                UVS_LANGUAGE_LIBRARY['basicConfig']['saveSettings'],
                                'primary',
                                'uvs-basic-config-settings-save',
                                false,
                                $other_attributes
                            );
                            ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
<?php
    }

    /**
     * Register and add settings
     */
    public function uvs_page_init()
    {
        // Register settings for feilds of 'Basic Configuration' section
        register_setting('uvs-basic-config-settings-group', self::BASIC_CONFIG_OPTION_NAMES['license_key']);
        register_setting('uvs-basic-config-settings-group', self::BASIC_CONFIG_OPTION_NAMES['mic_listening_timeout']);
        register_setting('uvs-basic-config-settings-group', self::BASIC_CONFIG_OPTION_NAMES['selected_language']);
        register_setting('uvs-basic-config-settings-group', self::BASIC_CONFIG_OPTION_NAMES['floating_mic']);
        register_setting('uvs-basic-config-settings-group', self::BASIC_CONFIG_OPTION_NAMES['floating_mic_position']);
        register_setting('uvs-basic-config-settings-group', self::BASIC_CONFIG_OPTION_NAMES['mute_audio_phrases']);
        register_setting('uvs-basic-config-settings-group', self::BASIC_CONFIG_OPTION_NAMES['single_click']);
        register_setting('uvs-basic-config-settings-group', self::BASIC_CONFIG_OPTION_NAMES['elementor_mic']);
        register_setting('uvs-basic-config-settings-group', self::BASIC_CONFIG_OPTION_NAMES['keyboard_special_key']);
        register_setting('uvs-basic-config-settings-group', self::BASIC_CONFIG_OPTION_NAMES['keyboard_mic_switch']);
    }
}

// check user capabilities and hook into 'init' to initialize 'Universal Voice Search' settings object
add_action('init', 'initialize_uvs_settings_object');

/**
 * Initialize 'Universal Voice Search' settings object when 'pluggable' files are loaded from '/wp-includes/pluggable'
 * Which contains 'current_user_can' function.
 */
function initialize_uvs_settings_object()
{
    if (!current_user_can('manage_options'))
        return;

    $universal_voice_search_settings_page = new Universal_Voice_Search_Settings_Page();
}
