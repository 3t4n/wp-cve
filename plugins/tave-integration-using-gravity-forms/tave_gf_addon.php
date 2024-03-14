<?php

/* @see https://github.com/gravityforms/simplefeedaddon */
GFForms::include_feed_addon_framework();

class TaveGFAddOn extends GFFeedAddOn
{
    protected $_version = TAVE_GF_ADDON_VERSION;
    protected $_min_gravityforms_version = '1.9.16';
    protected $_slug = 'tave_gf';
    protected $_path = 'tave_gf/tave_gf.php';
    protected $_full_path = __FILE__;
    protected $_title = 'Gravity Forms T&aacute;ve';
    protected $_short_title = 'T&aacute;ve';
    protected $_multiple_feeds = false;

    private static $_instance = null;

    /**
     * minimum gravity forms version supported
     * @var string
     */
    protected static $min_gravityforms_version = '1.9.16';


    /**
     * Get an instance of the class
     *
     * @return TaveGFAddOn
     */
    public static function get_instance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }


    /**
     * Plugin starting point. Handles hooks, loading of language files and PayPal delayed payment support.
     */
    public function init()
    {
        parent::init();
    }


    // # FEED PROCESSING -----------------------------------------------------------------------------------------------
    /**
     * Process the feed e.g. subscribe the user to a list.
     *
     * @param array $feed The feed object to be processed.
     * @param array $entry The entry object currently being processed.
     * @param array $form The form object currently being processed.
     *
     * @return bool|void
     */
    public function process_feed($feed, $entry, $form)
    {
        $settings = $this->get_plugin_settings();
        $debug = !empty($settings['debug_enabled']);
        $debugUrl = $settings['debug_url'];
        $convertData = function_exists('mb_convert_encoding'); // used for converting form data to UTF-8

        // Retrieve the name => value pairs for all fields mapped in the 'taveFieldMapping' field map.
        $map = $this->get_field_map_fields($feed, 'taveFieldMapping');

        // Loop through the fields from the field map setting building an array of values to be passed to the third-party service.
        $data = array();
        foreach ($map as $name => $field) {
            // Get the field value for the specified field id
            $fieldValue = trim($this->get_field_value($form, $entry, $field));
            if (!strlen($fieldValue)) {
                continue;
            }

            $data[$name] = $convertData ? mb_convert_encoding($fieldValue, 'HTML-ENTITIES', 'UTF-8') : $fieldValue;
        }

        $data['SecretKey'] = trim($settings['secret_key']);

        $headers = array();
        if (!empty($settings['send_email'])) {
            $headers['X-Tave-No-Email-Notification'] = 'true';
        }

        // Build the request to send to Táve
        $request = array(
            'timeout' => 20,
            'redirection' => 3,
            'blocking' => true,
            'headers' => $headers,
            'body' => $data,
        );

        // check the curl version to determine the domain
        $curl_version_info = curl_version();
        $domain = version_compare($curl_version_info['version'], '7.18.1', '>=') ? /* SNI supported */ 'tave.com' : /* SNI NOT supported */ 'legacy-ssl.tave.com';
        $url = $debug && !empty($debugUrl) ? $debugUrl : "https://$domain/app/webservice/create-lead";
        $url = add_query_arg(array(
            'X-Tave-PHP' => phpversion(),
            'X-Tave-Curl' => $curl_version_info['version'],
            'X-Tave-WP' => get_bloginfo('version'),
            'X-Tave-GF' => GFForms::$version,
            'X-Tave-GFTaveAddon' => $this->_version,
        ), rtrim($url, '/') . '/' . trim($settings['access_key']));

        // send the data to tave.com
        $response = wp_remote_post($url, $request);
        $responseCode = wp_remote_retrieve_response_code($response);
        $responseBody = $response ? trim(wp_remote_retrieve_body($response)) : '';

        if (empty($response)) {
            $errors = 'response is empty';
        } elseif (is_wp_error($response)) {
            $errors = array(
                'errors' => $response->errors,
                'error_data' => $response->error_data,
            );
        } elseif ($responseCode == 200 && $responseBody == 'OK') {
            return $entry;
        } else {
            $errors = 'unhandled error encountered';
        }

        $this->add_feed_error("Failed to send data to T&aacute;ve with error response code: $responseCode", $feed, $entry, $form);

        if ($debug) {
            $this->log_debug('ERROR: Unable to send data to T&aacute;ve with error response code: ' . $responseCode);

            if (is_array($errors)) {
                $this->log_debug('Debug (Errors): ' . esc_html__(var_export($errors, true)));
            } else {
                $this->log_debug('Debug (Error): ' . esc_html__($errors));
            }

            $this->log_debug('Debug (URL): ' . esc_html__($url));
            $this->log_debug('Debug (Body): ' . esc_html__($responseBody));
        }

        return $entry;
    }


    // # ADMIN FUNCTIONS -----------------------------------------------------------------------------------------------

    /**
     * Configures the settings which should be rendered on the add-on settings tab.
     *
     * @return array
     */
    public function plugin_settings_fields()
    {
        $fields = array(
            array(
                'description' => 'The values for these settings are located inside <a href="https://tave.com/app/settings/new-lead-api" target="_blank">T&aacute;ve New Lead API Settings</a>',
                'fields' => array(
                    array(
                        'name'    => 'access_key',
                        'tooltip' => esc_html__('T&aacute;ve Studio ID of your T&aacute;ve account.', $this->_slug),
                        'label'   => esc_html__('T&aacute;ve Studio ID', $this->_slug),
                        'type'    => 'text',
                        'class'   => 'small',
                    ),
                    array(
                        'name'    => 'secret_key',
                        'tooltip' => esc_html__('Enter the Secret Key associated with your T&aacute;ve account.', $this->_slug),
                        'label'   => esc_html__('T&aacute;ve Secret Key', $this->_slug),
                        'type'    => 'text',
                        'class'   => 'medium',
                    ),
                    array(
                        'name'    => 'send_email',
                        'tooltip' => '',
                        'label'   => '',
                        'type'    => 'checkbox',
                        'choices' => array(
                            array(
                                'label' => esc_html__('Turn off T&aacute;ve New Lead email.', $this->_slug),
                                'name' => 'send_email',
                            ),
                        )
                    ),
                ),
            ),
            array(
                'title' => 'Debug Mode',
                'fields' => array(
                    array(
                        'name'    => 'debug_enabled',
                        'tooltip' => 'Enable debugging mode.',
                        'label'   => esc_html__('Enable Debug Mode', $this->_slug),
                        'type'    => 'checkbox',
                        'choices' => array(
                            array(
                                'label' => 'Send additional debugging information to T&aacute;ve.',
                                'name' => 'debug_enabled',
                            ),
                        )
                    ),
                    array(
                        'name' => 'debug_url',
                        'label' => esc_html__('Debug URL', $this->_slug),
                        'tooltip' => 'Send the form information to an alternate endpoint.',
                        'type' => 'text',
                        'class' => 'medium',
                    ),
                ),
            ),
        );

        return $fields;
    }


    /**
     * Configures the settings which should be rendered on the feed edit page in the Form Settings > Táve Add-On area.
     *
     * @return array
     */
    public function feed_settings_fields()
    {
        $settings = $this->get_plugin_settings();
        $debug = !empty($settings['debug_enabled']);
        $debugUrl = $debug && !empty($settings['debug_url']) ? $settings['debug_url'] : null;

        $settings['access_key'] = isset($settings['access_key']) ? trim($settings['access_key']) : null;
        $settings['secret_key'] = isset($settings['secret_key']) ? trim($settings['secret_key']) : null;

        $fieldListUrl = add_query_arg(array(
            'list-fields' => 'true'
        ), strlen($debugUrl) ? $debugUrl : 'https://tave.com/app/webservice/create-lead');

        $fieldListArgs = array(
            'httpversion' => '1.1',
            'timeout' => 5,
        );

        if (strlen($settings['access_key']) && strlen($settings['secret_key'])) {
            // Fetch Custom Fields
            $fieldListUrl = add_query_arg(array(
                'StudioID' => $settings['access_key'],
                'SecretKey' => $settings['secret_key']
            ), $fieldListUrl);
        }

        $fieldListResponse = wp_remote_get($fieldListUrl, $fieldListArgs);
        $fieldListResponseCode = wp_remote_retrieve_response_code($fieldListResponse);
        $fieldListResponseBody = wp_remote_retrieve_body($fieldListResponse);
        $fieldList = strlen($fieldListResponseBody) ? json_decode($fieldListResponseBody, true) : null;

        if ($fieldListResponseCode != 200 || empty($fieldList)) {
            GFCommon::add_error_message('ERROR: Unable to retrieve field list from T&aacute;ve with error response code: ' . $fieldListResponseCode);
            if ($debug) {
                GFCommon::add_error_message('Debug (URL): ' . esc_html__($fieldListUrl));
                GFCommon::add_error_message('Debug (Body): ' . esc_html__($fieldListResponseBody));
            }

            GFCommon::display_admin_message();
            return array();
        }

        $taveFieldMap = array();
        foreach ($fieldList as $taveField) {
            if (in_array($taveField['name'], array('SecretKey'))) {
                continue;
            }

            $fieldMappingEntry = array(
                'name' => $taveField['name'],
                'label' => esc_html__($taveField['label']),
                'required' => !empty($taveField['required']),
            );

            if (!empty($taveField['desc'])) {
                $fieldMappingEntry['tooltip'] = esc_html__($taveField['desc']);
            }

            if ($taveField['name'] == 'Email') {
                $fieldMappingEntry['field_type'] = array('email','text');
            } elseif (strpos($taveField['name'], 'Phone')) {
                $fieldMappingEntry['field_type'] = array('phone','text');
            } elseif (strpos($taveField['name'], 'Name')) {
                $fieldMappingEntry['field_type'] = array('name','text');
            } elseif (in_array($taveField['name'], array('JobRole', 'JobType', 'Source'))) {
                $fieldMappingEntry['field_type'] = array('radio','select','text');
            } elseif ($taveField['name'] == 'EventDate') {
                $fieldMappingEntry['field_type'] = array('date','text');
            } elseif ($taveField['name'] == 'Message') {
                $fieldMappingEntry['field_type'] = array('text','textarea');
            } elseif (strpos($taveField['name'], 'CF-') === 0) {
                if ($taveField['type'] == 'Numeric') {
                    $fieldMappingEntry['field_type'] = array('number','radio','text');
                } elseif ($taveField['type'] == 'Date') {
                    $fieldMappingEntry['field_type'] = array('date','text');
                } else {
                    $fieldMappingEntry['field_type'] = array('email','phone','text','textarea','website','select','radio');
                }
            } else {
                $fieldMappingEntry['field_type'] = array('text','select','radio');
            }

            $taveFieldMap[] = $fieldMappingEntry;
        }



        $fields = array(
            array(
                'title'  => esc_html__('Map Form Fields to T&aacute;ve Fields', $this->_slug),
                'fields' => array(
                    array(
                        'name'      => 'taveFieldMapping',
                        'type'      => 'field_map',
                        'field_map' => $taveFieldMap,
                    ),
                ),
            ),
        );

        return $fields;
    }


    /**
     * Prevent feeds being listed or created if an api key isn't valid.
     *
     * @return bool
     */
    public function can_create_feed()
    {
        // Get the plugin settings.
        $settings = $this->get_plugin_settings();

        // make sure we have an access key and secret key
        return strlen(rgar($settings, 'access_key')) && strlen(rgar($settings, 'secret_key'));
    }

    public function feed_settings_title()
    {
        return 'T&aacute;ve Field Mappings';
    }
}
