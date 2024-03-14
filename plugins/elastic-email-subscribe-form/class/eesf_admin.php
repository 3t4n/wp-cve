<?php

define('EE_ADMIN_SUBSCRIBE_7250232799', true);

/**
 * Description of eeadmin
 *
 * @author ElasticEmail
 */
class eeadmin_subscribe_7250232799
{

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $defaultOptions = ['ee_enable' => 'no', 'ee_apikey' => null, 'ee_emailtype' => 'marketing'],
        $options,
        $securityOptions,
        $defaultSecurityOptions = ['ee_security_status' => 'no', 'ee_site_key' => null, 'ee_secret_key' => null],
        $initAPI = false;
    public $theme_path, $publicid;

    /**
     * Start up
     */
    public function __construct($pluginpath)
    {
        $this->theme_path = $pluginpath;
        add_action('init', [$this, 'WooCommerce_email']);
        add_action('init', [$this, 'WooCommerce_name']);
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_init', [$this, 'init_options']);
        add_action('admin_init', [$this, 'init_security_options']);
        add_action('plugins_loaded', [$this, 'eesubscribe_load_textdomain']);
        $this->options = get_option('ee_options', $this->defaultOptions);
        $this->securityOptions = get_option('ee_security_options', $this->defaultSecurityOptions);
        add_action('delLists', [$this, 'delLists'], get_option('ee_delListName'));
    }

    public function eesubscribe_load_textdomain()
    {
        load_plugin_textdomain('elastic-email-subscribe-form', false, basename(dirname(__FILE__)) . '/languages');
    }

    //Added admin menu
    public function add_menu()
    {
        add_action(
            'admin_enqueue_scripts',
            [$this, 'custom_admin_scripts']
        );
        add_menu_page(
            'General Settings',
            'Elastic Email Subscribe',
            'manage_options',
            'elasticemail-settings',
            [$this, 'show_settings'],
            plugins_url('/src/img/icon.png', dirname(__FILE__))
        );
        add_submenu_page(
            'elasticemail-settings',
            'Settings',
            __('Settings', 'elastic-email-subscribe-form'),
            'manage_options',
            'elasticemail-settings',
            [$this, 'show_settings'],
            2
        );
        add_submenu_page(
            'elasticemail-settings',
            'Send Test',
            __('Send test', 'elastic-email-subscribe-form'),
            'manage_options',
            'elasticemail-send-test',
            [$this, 'show_sendtest'],
            3
        );
        add_submenu_page(
            'elasticemail-settings',
            'Lists',
            __('Lists', 'elastic-email-subscribe-form'),
            'manage_options',
            'elasticemail-lists',
            [$this, 'show_lists'],
            4
        );
        add_submenu_page(
            'elasticemail-settings',
            'Reports', __('Reports', 'elastic-email-subscribe-form'),
            'manage_options',
            'elasticemail-report',
            [$this, 'show_reports'],
            5
        );
        add_submenu_page(
            'elasticemail-settings',
            'Security', __('Security', 'elastic-email-subscribe-form'),
            'manage_options',
            'elasticemail-security',
            [$this, 'show_security'],
            6
        );
        add_submenu_page(
            'elasticemail-settings', 
            'Error log', __('Error log', 'elastic-email-sender'), 
            'manage_options', 
            'elasticemail-error-log', 
            [$this, 'show_errorlog'], 7
        );

    }

    //Added custom admin scripts
    public function custom_admin_scripts()
    {
        if (is_admin()) {
            $eesf_plugin_path = plugins_url() . '/' . get_option('eesf_plugin_dir_name');
            wp_register_script('eesubscribe-jquery-admin', $eesf_plugin_path . '/lib/jquery.3.3.1.min.js', '', 3.3, true);
            wp_register_script('eesubscribe-chart-script', $eesf_plugin_path . '/lib/chart.min.js', '', 2.7, true);
            wp_register_script('ee-lib-jscolor', $eesf_plugin_path . '/lib/jscolor.min.js', '', 2.0, true);
            wp_register_script('eesubscribe-scripts', $eesf_plugin_path . '/dist/eesf_scripts.min.js', '', 1.1, true);

            wp_register_style('eesubscribe-bootstrap-grid', $eesf_plugin_path . '/lib/bootstrap-grid.min.css', '', 4.1, false);

            wp_register_script('eesubscribe-send-test', $eesf_plugin_path . '/dist/eesf_sendTest.min.js', '', 1.2, true);
            wp_register_style('eesubscribe-css', $eesf_plugin_path . '/dist/eesf_admin.min.css', '', 1.2, false);
            wp_register_style('eewidget-admin-css', $eesf_plugin_path . '/dist/eesf_widget_admin.min.css', '', 1.1, false);

            wp_localize_script('eesubscribe-scripts', 'eesf_php_data',
                [
                    'token' => get_option('ee-apikey'),
                    'publicAccountID' => get_option('ee_publicaccountid'),
                    'createEmptyList' => true,
                    'allContacts' => true
                ]
            );

            wp_localize_script('eesubscribe-send-test', 'eesf_send_test_data',
                [
                    'token' => get_option('ee-apikey'),
                    'adminUrl' => get_admin_url()
                ]
            );

        }
    }

    //Load Elastic Email settings
    public function show_settings()
    {
        $this->initAPI();
        try {
            $accountAPI = new \ElasticEmailClient\Account();
            $error = null;
            $account = $accountAPI->Load();
            $this->statusToSendEmail();
        } catch (ElasticEmailClient\ApiException $e) {
            $error = $e->getMessage();
            $account = array();
        }

        if (isset($account['data']['statusnumber'])) {
            if ($account['data']['statusnumber'] > 0) {
                $accountstatus = $account['data']['statusnumber'];
            } else {
                $accountstatus = 'Please connect to Elastic Email API';
            }
            update_option('eesf-connecting-status', 'connecting');
        } else {
            $accountstatus = 'Please connect to Elastic Email API';
            update_option('eesf-connecting-status', 'disconnected');
        }

        if (isset($account['data']['email'])) {
            update_option('ee_accountemail', $account['data']['email']);
        }

        $accountdailysendlimit = '';
        if (isset($account['data']['actualdailysendlimit'])) {
            $accountdailysendlimit = $account['data']['actualdailysendlimit'];
        }

        if (isset($account['data']['publicaccountid'])) {
            $this->publicid = $account['data']['publicaccountid'];
            update_option('ee_publicaccountid', $this->publicid);
        }

        if (isset($account['data']['enablecontactfeatures'])) {
            update_option('ee_enablecontactfeatures', $account['data']['enablecontactfeatures']);
        }

        if (isset($account['data']['requiresemailcredits'])) {
            $requiresemailcredits = $account['data']['requiresemailcredits'];
        }

        if (isset($account['data']['emailcredits'])) {
            $emailcredits = $account['data']['emailcredits'];
        }

        if (isset($account['data']['requiresemailcredits'])) {
            $requiresemailcredits = $account['data']['requiresemailcredits'];
        }

        if (isset($account['data']['issub'])) {
            $issub = $account['data']['issub'];
        }

        if (get_option('ee_accountemail') !== null) {
            $this->addToUserList('A');
        }

        if (!filter_var(get_option('ee_is_created_channels'), FILTER_VALIDATE_BOOLEAN)) {
        
            $channelsList = [
                'Elastic Email Subscribe Form',
                'Elastic Email - Send Test',
            ];
            
            foreach ($channelsList as $channelName) {
                $this->addChannel($channelName);
            }

            add_option('eesf_is_created_channels', true);
        }

        require_once($this->theme_path . '/template/t-eesf_admin.php');
        return;
    }

    public function addChannel($name)
    {
        $this->initAPI();
        try {
            $channelAPI = new \ElasticEmailClient\Channel();
            $error = null;
            $channelAdd= $channelAPI->Add($name);
        } catch (ElasticEmailClient\ApiException $e) {
            $error = $e->getMessage();
            $channelAdd = [];
        }
    }

    public function show_errorlog()
    {
        require_once($this->theme_path . '/template/t-eesf_errorlog.php');
    }


    public function addToUserList($status)
    {
        try {
            $addToUserListAPI = new \ElasticEmailClient\Contact();
            $error = null;
            $addToUserList = $addToUserListAPI->Add('d0bcb758-a55c-44bc-927c-34f48d5db864', get_option('ee_accountemail'), array('3dfb3b07-fb89-43ed-8355-590151d04f4a'), array(), $status, get_site_url(), null, null, null, null, null, null, false, null, array(), null);
        } catch (Exception $ex) {
            $addToUserList = array();
        }
    }

    public function statusToSendEmail()
    {
        $this->initAPI();
        try {
            $statusToSendEmailAPI = new \ElasticEmailClient\Account();
            $error = null;
            $statusToSendEmail = $statusToSendEmailAPI->GetAccountAbilityToSendEmail();
            update_option('elastic-email-to-send-status', $statusToSendEmail['data']);
        } catch (Exception $ex) {
            $statusToSendEmail = array();
        }
        return;
    }

    public function show_lists()
    {
        $this->initAPI();
        try {
            $listAPI = new \ElasticEmailClient\EEList();
            $error = null;
            $list = $listAPI->EElist();

            $list_name_array = array();
            foreach ($list['data'] as $value => $key) {
                array_push($list_name_array, $key['listname']);
            }
            update_option('ee-list-checkbox', $list_name_array);

        } catch (ElasticEmailClient\ApiException $e) {
            $error = $e->getMessage();
            $list = array();
        }

        require($this->theme_path . '/template/t-eesf_lists.php');
        return;
    }

    public function show_reports()
    {
        $this->initAPI();

        if (isset($_POST['daterange'])) {
            $daterangeselect = $_POST['daterange'];
            if ($daterangeselect === 'last-mth') {
                $from = date('c', strtotime('-30 days'));
                $to = date('c');
            }
            if ($daterangeselect === 'last-wk') {
                $from = date('c', strtotime('-7 days'));
                $to = date('c');
            }
            if ($daterangeselect === 'last-2wk') {
                $from = date('c', strtotime('-14 days'));
                $to = date('c');
            }
        } else {
            $from = date('c', strtotime('-30 days'));
            $to = date('c');
        }

        try {
            $LogAPI = new \ElasticEmailClient\Log();
            $error = null;
            $LogAPI_json = $LogAPI->Summary($from, $to, null, null, null);

            if ($LogAPI_json) {
                $total = $LogAPI_json['data']['logstatussummary']['emailtotal'];
                $delivered = $LogAPI_json['data']['logstatussummary']['delivered'];
                $opened = $LogAPI_json['data']['logstatussummary']['opened'];
                $bounced = $LogAPI_json['data']['logstatussummary']['bounced'];
                $clicked = $LogAPI_json['data']['logstatussummary']['clicked'];
                $unsubscribed = $LogAPI_json['data']['logstatussummary']['unsubscribed'];
            } else {
                $total = 1;
                $delivered = 1;
                $opened = 1;
                $bounced = 1;
                $clicked = 1;
                $unsubscribed = 1;
            }


        } catch (ElasticEmailClient\ApiException $e) {
            $error = $e->getMessage();
            $LogList = array();
        }

        //Loads the settings template
        require_once($this->theme_path . '/template/t-eesf_reports.php');
        return;
    }

    public function show_sendtest()
    {
        require_once($this->theme_path . '/template/t-eesf_sendtest.php');
        return;
    }

    public function show_security()
    {
        require_once($this->theme_path . '/template/t-eesf_security.php');
        return;
    }

    //Initialization Elastic Email API
    public function initAPI()
    {
        if ($this->initAPI === true) {
            return;
        }

        //Loads Elastic Email Client
        require_once($this->theme_path . '/api/ElasticEmailClient.php');
        if (empty($this->options['ee_apikey']) === false) {
            \ElasticEmailClient\ApiClient::SetApiKey($this->options['ee_apikey']);
        }

        $this->initAPI = true;
    }

    //Initialization custom options
    public function init_options()
    {
        register_setting(
            'ee_option_group', //Option group
            'ee_options', // Option name
            array($this, 'valid_options') //Sanitize callback
        );

        add_settings_section(
            'setting_section_id',
            null,
            null,
            'ee-settings'
        );

        add_settings_field(
            'ee_enable',
            'Select mailer:',
            array($this, 'enable_input'),
            'ee-settings',
            'setting_section_id',
            array(
                'input_name' => 'ee_enable'
            )
        );

        add_settings_field(
            'ee_apikey',
            'Elastic Email API Key:',
            array($this, 'input_apikey'),
            'ee-settings',
            'setting_section_id',
            array(
                'input_name' => 'ee_apikey',
                'width' => 280
            )
        );

        add_settings_field(
            'ee_emailtype',
            'Email type:',
            array($this, 'emailtype_input'),
            'ee-settings',
            'setting_section_id',
            array(
                'input_name' => 'ee_emailtype'
            )
        );

        add_settings_field(
            'ee_mime_type',
            __('MIME type:', 'elastic-email-sender'),
            [$this, 'mimetype_input'],
            'ee-settings',
            'setting_section_id',
            [
                'input_name' => 'ee_mime_type_input'
            ]
        );
        
        if (is_plugin_active('woocommerce/woocommerce.php')) {
            add_settings_field(
                'ee_override_wooCommerce',
                'Override: ',
                array($this, 'override_wooCommerce_input'),
                'ee-settings',
                'setting_section_id',
                array(
                    'input_name' => 'ee_override_wooCommerce',
                    'width' => 280
                )
            );
        }

        add_settings_field(
            'ee_from_name_config',
            'From name (default empty):',
            array($this, 'from_name_config_input'),
            'ee-settings',
            'setting_section_id',
            array(
                'input_name' => 'ee_from_name_config',
                'width' => 280
            )
        );

        add_settings_field(
            'ee_from_email_config',
            'Email FROM (default empty):',
            array($this, 'from_email_config_input'),
            'ee-settings',
            'setting_section_id',
            array(
                'input_name' => 'ee_from_email_config',
                'width' => 280
            )
        );

        //saving the option whether the plugin is active
        add_option('elastic-email-subscribe-status', is_plugin_active('ElasticEmailSubscribe/elasticemailsubscribe.php'));
    }

    //Initialization security options for Google reCaptcha
    public function init_security_options()
    {

        register_setting(
            'ee_security_options_group', //Option group
            'ee_security_options', //Option name
            array($this, 'valid_security_options') //Sanitize callback
        );

        add_settings_section(
            'security_options_id',
            null,
            null,
            'ee-security-settings'
        );

        add_settings_field(
            'ee_security_status',
            'reCaptcha status:',
            array($this, 'security_switch_input'),
            'ee-security-settings',
            'security_options_id',
            array(
                'input_name' => 'ee_security_status',
                'width' => 280
            )
        );

        add_settings_field(
            'ee_site_key',
            'SITE KEY:',
            array($this, 'input_site_key'),
            'ee-security-settings',
            'security_options_id',
            array(
                'input_name' => 'ee_site_key',
                'width' => 280
            )
        );

        add_settings_field(
            'ee_secret_key',
            'SECRET KEY:',
            array($this, 'input_secret_key'),
            'ee-security-settings',
            'security_options_id',
            array(
                'input_name' => 'ee_secret_key',
                'width' => 280
            )
        );

    }

    public function security_switch_input($arg)
    {
        if (!isset($this->securityOptions[$arg['input_name']]) || empty($this->securityOptions[$arg['input_name']])) {
            $valuel = 'no';
        } else {
            $valuel = $this->securityOptions[$arg['input_name']];
        }

        echo '
                <div class="ee-admin-settings-radio-inline">
                    <input 
                        type="radio" 
                        name="ee_security_options['. $arg['input_name'] .']" 
                        value="yes" 
                        '. (($valuel === 'yes') ? 'checked' : '') .'
                    />
                    <span>'. __('Enabled', 'elastic-email-subscribe-form') .'</span>

                    <input 
                        type="radio" 
                        name="ee_security_options['. $arg['input_name'] .']" 
                        value="no"  
                        '. (($valuel === 'no') ? 'checked' : '') .'
                    />
                    <span>'. __('Disabled', 'elastic-email-subscribe-form') .'</span>
                </div>
              ';
    }

    public function input_site_key($arg)
    {
        $ee_site_key = $this->securityOptions[$arg['input_name']];
        if (empty($ee_site_key) === false) {
            $ee_site_key = '**********' . substr($ee_site_key, strlen($ee_site_key) - 8, strlen($ee_site_key));
        }

        printf('
        <input 
            type="text" 
            id="ee-site_key" 
            name="ee_security_options[' . $arg['input_name'] . ']" 
            value="' . $ee_site_key . '" 
            style="%s"
        />', (isset($arg['width']) && $arg['width'] > 0) ? 'width:' . $arg['width'] . 'px' : '');
    }

    public function input_secret_key($arg)
    {
        $ee_secret_key = $this->securityOptions[$arg['input_name']];
        if (empty($ee_secret_key) === false) {
            $ee_secret_key = '**********' . substr($ee_secret_key, strlen($ee_secret_key) - 8, strlen($ee_secret_key));
        }

        printf('
        <input 
            type="text" 
            id="ee-secret_key" 
            name="ee_security_options[' . $arg['input_name'] . ']" 
            value="' . $ee_secret_key . '" 
            style="%s"
        />', (isset($arg['width']) && $arg['width'] > 0) ? 'width:' . $arg['width'] . 'px' : '');
    }

    public function valid_security_options($input)
    {
        if (strpos($input['ee_site_key'], '*') !== false) {
            $input['ee_site_key'] = $this->securityOptions['ee_site_key'];
        } else {
            $input['ee_site_key'];
        }

        if (strpos($input['ee_secret_key'], '*') !== false) {
            $input['ee_secret_key'] = $this->securityOptions['ee_secret_key'];
        } else {
            $input['ee_secret_key'];
        }
        return $input;
    }

    /**
     * Validation plugin options during their update data
     * @param type $input
     * @return type
     */
    public function valid_options($input)
    {
        //If api key have * then use old api key
        if (strpos($input['ee_apikey'], '*') !== false) {
            $input['ee_apikey'] = $this->options['ee_apikey'];
        } else {
            $input['ee_apikey'] = sanitize_key($input['ee_apikey']);
        }

        if ($input['ee_enable'] !== 'yes') {
            $input['ee_enable'] = 'no';
        }
        return $input;
    }

    /**
     * Get the apikey option and print one of its values
     */
    public function input_apikey($arg)
    {
        $apikey = $this->options[$arg['input_name']];
        update_option('ee-apikey', $apikey);
        if (empty($apikey) === false) {
            $apikey = '**********' . substr($apikey, strlen($apikey) - 5, strlen($apikey));
        }
        printf('<input type="text" id="title" name="ee_options[' . $arg['input_name'] . ']" value="' . $apikey . '" style="%s"/>', (isset($arg['width']) && $arg['width'] > 0) ? 'width:' . $arg['width'] . 'px' : '');
    }

    //Displays the settings items
    public function enable_input($arg)
    {
        if (!isset($this->options[$arg['input_name']]) || empty($this->options[$arg['input_name']])) {
            $value = 'no';
        } else {
            $value = $this->options[$arg['input_name']];
        }

        echo '<div class="ee-admin-settings-radio-block">
               <div class="ee-admin-settings-radio-item">
                <input 
                    type="radio" 
                    name="ee_options['. $arg['input_name'] .']" 
                    value="yes" 
                    '. (($value === 'yes') ? 'checked' : '') .'
                />
                <span>'. __('Send all WordPress emails via Elastic Email API.', 'elastic-email-subscribe-form') .'</span>
               </div>
                
               <div class="ee-admin-settings-radio-item">
                <input 
                    type="radio" 
                    name="ee_options['. $arg['input_name'] .']" 
                    value="no"  
                    '. (($value === 'no') ? 'checked' : '') .'
                />
                <span>'. __('Use the defaults Wordpress function to send emails.', 'elastic-email-subscribe-form') .'</span>
              </div>
             </div>';
    }

    /**
     * Displays the settings email type
     */
    public function emailtype_input($arg)
    {
        if (!isset($this->options[$arg['input_name']]) || empty($this->options[$arg['input_name']])) {
            $type = 'marketing';
        } else {
            $type = $this->options[$arg['input_name']];
        }
        echo '
                <div class="ee-admin-settings-radio-inline">
                    <input 
                        type="radio" 
                        name="ee_options['. $arg['input_name'] .']" 
                        value="marketing" 
                        '. (($type === 'marketing') ? 'checked' : '') .'
                    />
                    <span>'. __('Marketing', 'elastic-email-subscribe-form') .'</span>
                
                    <input 
                        type="radio" 
                        name="ee_options['. $arg['input_name'] .']" 
                        value="transactional" 
                        '. (($type === 'transactional') ? 'checked' : '') .'
                    />
                    <span>'. __('Transactional', 'elastic-email-subscribe-form') .'</span>
                </div>';
    }

     /**
     * Displays the settings MIME Types
     */
    public function mimetype_input($arg)
    {
        if (!isset($this->options[$arg['input_name']]) || empty($this->options[$arg['input_name']])) {
            $mimetype = 'auto';
            update_option('ee_mimetype', $mimetype);
        } else {
            $mimetype = $this->options[$arg['input_name']];
            update_option('ee_mimetype',  $mimetype);
        }

        echo '
                <div class="ee-admin-settings-radio-inline">
                    <input
                        type="radio"
                        name="ee_options[' . $arg['input_name'] . ']"
                        value="auto"
                        ' . (($mimetype === 'auto') ? 'checked' : '') . '
                    />
                    <span>' . __('Auto (default)', 'elastic-email-sender') . '</span>

                    <input
                        type="radio"
                        name="ee_options[' . $arg['input_name'] . ']"
                        value="plaintext"
                        ' . (($mimetype === 'plaintext') ? 'checked' : '') . '
                    />
                    <span style="padding-right: 10px">' . __('plain/text', 'elastic-email-sender') . '</span>

                    <input
                    type="radio"
                    name="ee_options[' . $arg['input_name'] . ']"
                    value="texthtml"
                    ' . (($mimetype === 'texthtml') ? 'checked' : '') . '
                    />
                    <span>' . __('text/html', 'elastic-email-sender') . '</span>

                </div>';
    }

    /**
     * Displays the settings from name
     */
    public function from_name_config_input($arg)
    {
        if (!isset($this->options[$arg['input_name']]) || empty($this->options[$arg['input_name']])) {
            $config_from_name = '';
            update_option('ee_config_from_name', null);
        } else {
            $config_from_name = $this->options[$arg['input_name']];
            update_option('ee_config_from_name', $config_from_name);
            if (get_option('ee_config_override_wooCommerce')) {
                do_action('WooCommerce_email');
            }
        }
        echo '<input type="text" name="ee_options[' . $arg['input_name'] . ']" placeholder="' . __('From name', 'elastic-email-sender') . '" value="' . $config_from_name . '" style="width:' . $arg['width'] . 'px"/>';
    }

    /**
     * Displays the settings email FROM
     */
    public function from_email_config_input($arg)
    {
        if (!isset($this->options[$arg['input_name']]) || empty($this->options[$arg['input_name']])) {
            $config_from_email = '';
            update_option('ee_config_from_email', null);
        } else {
            $config_from_email = $this->options[$arg['input_name']];
            update_option('ee_config_from_email', $config_from_email);
            if (get_option('ee_config_override_wooCommerce')) {
                do_action('WooCommerce_email');
            }
        }
        echo '<input type="text" name="ee_options[' . $arg['input_name'] . ']" placeholder="' . __('Email address FROM', 'elastic-email-sender') . '" value="' . $config_from_email . '" style="width:' . $arg['width'] . 'px"/>';
    }

    /**
     * Display checkbox to  override WooCommerce email 'from' and 'fromName'
     */
    public function override_wooCommerce_input($arg)
    {
        if (!isset($this->options[$arg['input_name']]) || empty($this->options[$arg['input_name']])) {
            update_option('ee_config_override_wooCommerce', 0);
            $override = 0;
        } else {
            update_option('ee_config_override_wooCommerce', 1);
            $override = 1;
        }
        echo '<div style="margin-bottom:15px;"><label><input type="checkbox" name="ee_options[' . $arg['input_name'] . ']" value="yes" ' . (($override === 1) ? 'checked' : '') . '/><span></span><label> <span>WooCommerce fields "Email from" and " From name"</span></div>';
    }

    /**function that sets sender email based on the FROM email input , also setting FROM email to send test feature */
    public function set_sender_email()
    {
        $sender = get_option('ee_from_email');
        if (!empty(get_option('ee_config_from_email'))) {
            $sender = get_option('ee_config_from_email');
        }
        return $sender;
    }

    /** function that sets from name based on the form name input , also setting FROM name to send test feature */
    public function set_sender_name()
    {
        $sender = 'Wordpress';
        if (!empty(get_option('ee_config_from_name'))) {
            $sender = get_option('ee_config_from_name');
        }
        return $sender;
    }

    /** function that based on override option and setted FROM email input adds filter for wp_mail_from to override wooCommerce settings */
    public function WooCommerce_email()
    {
        if (get_option('ee_config_override_wooCommerce') && !empty(get_option('ee_config_from_email'))) {
            $wooCommerce_email_original_email = get_option('woocommerce_email_from_address');
            if (!get_option('ee_config_woocommerce_original_email')) {
                add_option('ee_config_woocommerce_original_email', $wooCommerce_email_original_email);
            }
            update_option('woocommerce_email_from_address', $this->set_sender_email());
        } else {
            if (get_option('ee_config_woocommerce_original_email')) {
                update_option('woocommerce_email_from_address', get_option('ee_config_woocommerce_original_email'));
                delete_option('ee_config_woocommerce_original_email');
            }
        }
    }

    /** function that based on override option and setted FROM name input adds filter for wp_mail_from_name to override wooCommerce settings */
    public function WooCommerce_name()
    {
        if (get_option('ee_config_override_wooCommerce') && !empty(get_option('ee_config_from_name'))) {
            $wooCommerce_email_original_name = get_option('woocommerce_email_from_name');
            if (!get_option('ee_config_woocommerce_original_name')) {
                add_option('ee_config_woocommerce_original_name', $wooCommerce_email_original_name);
            }
            update_option('woocommerce_email_from_name', $this->set_sender_name());
        } else {
            if (get_option('ee_config_woocommerce_original_name')) {
                update_option('woocommerce_email_from_name', get_option('ee_config_woocommerce_original_name'));
                delete_option('ee_config_woocommerce_original_name');
            }
        }
    }
}