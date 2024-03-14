<?php





interface INSA_WP_Plugin
{
    /**
     * Load required assets and set up WordPress Hooks and filters
     */
    public function init();
    
    /**
     * Read Settings into property members
     */
    public function load_settings();
    
}




/**
 * NSA_WP_Plugin short summary.
 *
 * NSA_WP_Plugin description.
 *
 * @version 1.0
 * @author Jake.Hulse
 */
abstract class NSA_WP_Plugin implements INSA_WP_Plugin
{

    public $plugin_id = '';
    public $plugin_name = '';
    public $plugin_version = '';
    public $plugin_root_dir_url = '';
    public $plugin_root_dir = '';
    public $notification_url = null;
    public $firstInstall = null;
    public $settings = null;
    public $tabs;

    /**
     * Loads asset files or displays an error message on the administrator page if asset cannot be found.
     * @param string $file Full path and file name to asset.  Omit leading '/'
     */
    protected function LoadAsset($file, $showWarning = true) {
        if ( file_exists( dirname( __FILE__ ).'/'.$file ) ) {
            require_once $file;

        } else {
            //Do not use nsau_AdminNotice because it may be the file which is missing
            if($showWarning) {
                add_action( 'admin_notices', function() use ($file) { 
                    global $WPFacebookPixel;
                    echo('<div class="error"><p><strong>'
                        .__($WPFacebookPixel->plugin_name.' Error: ', $WPFacebookPixel->plugin_id ).'</strong>'
                        .__('Missing file '.$file.'.  Please contact Night Shift Apps for assistance or reinstall the plugin.', $WPFacebookPixel->plugin_id).'</p></div>');
                });
            }

        }
    }



    /**
     * Sets standard plugin properties and generates the NSA_WP_Plugin_Settings instance
     * @param string $id Plugin's unique Id
     * @param string $name Public name of Plugin
     * @param string $version Current Plugin version
     * @param string $settings_roles_allowed Roles allowed to access plugin settings
     */
    public function __construct($id, $name, $version, $settings_roles_allowed = 'administrator', $notification_url = null ) {
        $this->plugin_id = $id;
        $this->plugin_name = $name;
        $this->plugin_version = $version;
        $this->plugin_root_dir_url = plugin_dir_url(__FILE__);
        $this->plugin_root_dir = dirname( __FILE__ ).'/';
        $this->notification_url = $notification_url;
        $this->firstInstall = get_option($this->PLUGIN_ID.'_first_activate_date', null);

        $this->settings = new NSA_WP_Plugin_Settings($this, $settings_roles_allowed);

        $this->LoadAsset('inc/NSAUtilities.php');
        $this->LoadAsset('inc/NSANotifications/NSANotification.php');
        $this->LoadAsset('inc/CMB2/init.php');
        $this->LoadAsset('framework/NSA_CMB2_Types.php');
        $this->LoadAsset('framework/PluginOptions.php');
        $this->LoadAsset('framework/MetaBox.php');
        $this->LoadAsset('pro_assets/'.$id.'-pro.php', false);

        $NSA_CMB2_Types = new NSA_CMB2_Types($this);
        if(class_exists("nsa_wpfbp_pro")) $nsa_wpfbp_pro = nsa_wpfbp_pro::instance($this);//new nsa_wpfbp_pro($this);


        register_activation_hook($this->plugin_file, function(){
            global $WPFacebookPixel;
            $current_first_install_date = get_option($WPFacebookPixel->PLUGIN_ID.'_first_activate_date', null);
            if(!isset($current_first_install_date)) {
                update_option($WPFacebookPixel->PLUGIN_ID.'_first_activate_date', time());
            }
            $WPFacebookPixel->firstInstall = get_option($WPFacebookPixel->PLUGIN_ID.'_first_activate_date', null);
        });


        add_action('init', array( $this, 'init'));
        add_action('cmb2_init', array( $this, 'load_settings'), 2);
        add_action('cmb2_admin_init', array( $this, 'load_settings'), 2);
        //add_action('update_option_'.$this->plugin_id.'_settings', array( $this, 'load_settings'));

        //NOTIFICATION SUPPORT
        add_action('admin_head', array($this, 'GetNotifications'));
        add_action('wp_ajax_nopriv_nsa_'.$this->plugin_id.'_dismiss_notification', array($this, 'AJAX_DismissNotification' ));
        add_action('wp_ajax_nsa_'.$this->plugin_id.'_dismiss_notification', array($this, 'AJAX_DismissNotification' ));
        
        //Add Notification Tab to settings
        add_filter($this->plugin_id.'_settings_tabs', 
            function ($tabs) { 
                global $WPFacebookPixel;
                $id = 'notifications';
                $name = __('Notifications', $WPFacebookPixel->plugin_id );
                $description = __("This page shows all of your {$WPFacebookPixel->plugin_name} notifications, even if you have dismissed them.", $WPFacebookPixel->plugin_id );
                $tabs[] = $WPFacebookPixel->settings->create_tab($id, 'page', $name, $description, null, function() {
                    global $WPFacebookPixel;
                    $notices = array();
                    if($WPFacebookPixel->notification_url != null)
                        $notices = NSANotification::getNotifications($WPFacebookPixel->plugin_id, $WPFacebookPixel->notification_url, true, false);


                    if(is_array($notices)) {
                        wp_enqueue_style('NSANotification', plugins_url('inc/NSANotifications/NSANotification.css', __FILE__), false, false, false);
                        foreach ($notices as $notice)
                        {
                            $class = isset($notice->class) ? $notice->class : '';
                            echo "<div class='nsa_notice {$class}'>
                                <div><b>{$notice->title}:</b></div>
                                <div class='nsa_notice_header_border'></div>
                                <div>{$notice->message}</div>
                            </div>";
                        }
                    }
                });
                return $tabs;
            }
        );

        if ( is_admin() ) {
			// Check for external connection blocking
			add_filter( $this->plugin_id.'_Notifications', function ($notifications) {
                global $WPFacebookPixel;
		        // show notice if external requests are blocked through the WP_HTTP_BLOCK_EXTERNAL constant
		        if( defined( 'WP_HTTP_BLOCK_EXTERNAL' ) && WP_HTTP_BLOCK_EXTERNAL === true ) {
			        // check if our API endpoint is in the allowed hosts
			        $host = parse_url( $WPFacebookPixel->NOTIFICATION_URL, PHP_URL_HOST );
			        if( ! defined( 'WP_ACCESSIBLE_HOSTS' ) || stristr( WP_ACCESSIBLE_HOSTS, $host ) === false ) {
                        $notification = new stdClass();
                        $notification->id = 'WP_ACCESSIBLE_HOSTS';
                        $notification->message = "You're blocking external requests which means you won't be able to get updates. Please add <code>$host</code> to <code>WP_ACCESSIBLE_HOSTS</code>.  Read how <a href='http://nightshiftapps.com/add-site-to-wp_accessible_hosts/' target='_blank'>here</a>.";
                        $notification->class = 'error';

                        $notifications[] = $notification;
			        }
		        }
                return $notifications;
	        });
        }
    }



    function Activate() {
        $firstInstall = get_option($this->plugin_id.'_install_date', null);
        if($firstInstall == null) {
            $firstInstall = time();
            update_option($this->plugin_id.'_install_date', $firstInstall);
        }
        $this->firstInstall = $firstInstall;
    }


    public function GetNotifications() {
        $s = get_current_screen();
        if($s->base == "settings_page_{$this->plugin_id}_settings" && ((isset($_GET['tab']) ? $_GET['tab'] : '') == "{$this->plugin_id}_notifications")) return;

        $notices = NSANotification::getNotifications($this->plugin_id, $this->notification_url, false, true);

        if(is_array($notices)) {
            foreach ($notices as $notice)
            {
                $class = isset($notice->class) ? $notice->class : '';
                nsau_AdminNotice($this->plugin_name, $notice->title, $notice->message, $this->plugin_id, $class, $notice->dismissible, $notice->id);
            }
        }
    }



    function AJAX_DismissNotification() {
        $pluginid = $_GET['pluginid'];
        $notificationid = $_GET['notificationid'];
        
        update_user_option( get_current_user_id(), "{$pluginid}_hide_note_{$notificationid}", true);
                
        die();
    }
    



}




class NSA_WP_Plugin_Settings {
    
    public $plugin = null;
    public $key = '';
    public static $tabs = array();
    public $capabilities = '';
    protected $options_page = '';


    public function __construct($plugin, $capabilities) {
        $this->plugin = $plugin;
        $this->key = $this->plugin->plugin_id.'_settings';
        $this->capabilities = $capabilities;


        //Get Settings
        add_action('init', array($this, 'get_tabs'));
        add_action('admin_init', function() { 
            global $WPFacebookPixel;
            register_setting($WPFacebookPixel->plugin_id.'_settings', $WPFacebookPixel->plugin_id.'_settings'); }
        );
        add_action('cmb2_admin_init', array($this, 'init_settings'), 1);

        add_action('admin_menu', array($this, 'add_options_page'));

        //Get Values
        add_filter('cmb2_override_option_get_'. $this->key, array( $this, 'get_override' ), 10, 2);
        add_filter('cmb2_override_option_save_'. $this->key, array( $this, 'update_override' ), 10, 2);

    }

    /**
     * Run filter [plugin_id]_settings_tabs to get all tabs and fields for this plugin.
     */
    public function get_tabs() {
        do_action($this->plugin->plugin_id.'_init_settings_hooks');
        $tabs = apply_filters($this->plugin->plugin_id.'_settings_tabs', array());
        self::$tabs = $tabs;
        
        

        ////Add Options Page
        //add_action('admin_init', function() { 
        //    //register_setting($this->key, $this->key);

        //    foreach (self::$tabs as $tab)
        //    {
        //        if($tab->sanitize_callback != null) {
        //            register_setting($tab->id, $tab->id, $tab->sanitize_callback);

        //            add_settings_section( $tab->id, $tab->name, function(){ }, $this->key );
        //            foreach ($tab->fields as $field)
        //            {
        //                add_settings_field($field['id'], $field['id'], $field['display_callback'], $field['page'], $field['section']);
        //            }
                    
        //                //add_settings_field( 'status', __( 'API License Key Status', nsa_wpfbp_pro()->text_domain ), array( $this, 'wc_am_api_key_status' ), nsa_wpfbp_pro()->ame_activation_tab_key, nsa_wpfbp_pro()->ame_api_key );
        //                //add_settings_field( nsa_wpfbp_pro()->ame_api_key, __( 'API License Key', nsa_wpfbp_pro()->text_domain ), array( $this, 'wc_am_api_key_field' ), nsa_wpfbp_pro()->ame_activation_tab_key, nsa_wpfbp_pro()->ame_api_key );
        //                //add_settings_field( nsa_wpfbp_pro()->ame_activation_email, __( 'API License email', nsa_wpfbp_pro()->text_domain ), array( $this, 'wc_am_api_email_field' ), nsa_wpfbp_pro()->ame_activation_tab_key, nsa_wpfbp_pro()->ame_api_key );
        //        }
        //    }
            
        //});

        return $tabs;
    }


    function setProField($field) {
        $proField = isset($field['ispro']) ? $field['ispro'] : false;
        if($proField) {
            $field['row_classes'] = 'nsa_plugin_pro';
            if(!$this->plugin->ProEnabled)
                $field['attributes'] = array(
                    'readonly' => 'readonly',
                    'disabled' => 'disabled',
                );
        }
        return $field;
    }

    
    public function init_settings() {
        $tabs = $this->get_tabs(); 
        
        foreach ($tabs as $tab)
        {
            // hook in our save notices
            add_action( "cmb2_save_options-page_fields_{$tab->id}", array( $this, 'settings_notices' ), 10, 2 );

            if($tab->type == 'cmb') {
                $cmb = new_cmb2_box($tab->cmb['box']);

                $fields = apply_filters($tab->id.'_fields', $tab->cmb['fields']);

                if(isset($fields)) {
                    foreach( $fields as $field) {
                        
                        $field = $this->setProField($field);
                        $g = $cmb->add_field( $field );
                        if($field['type']=='group') {
                            $groupFields = apply_filters($field['id'].'_group_fields', $field['group_fields']);
                            foreach($groupFields as $groupfield) {
                                $cmb->add_group_field( $g, $this->setProField($groupfield));
                            }
                        }
                    }
                }
            }
        }
        
        

        
    }



    /**
     * Register settings notices for display
     *
     * @since  0.1.0
     * @param  int   $object_id Option key
     * @param  array $updated   Array of updated fields
     * @return void
     */
    public function settings_notices($object_id, $updated) {
        if ($object_id !== $this->key || empty($updated)) {
            return;
        }
        add_settings_error($this->key.'-notices', '', __('Settings updated.', $this->plugin->plugin_id ), 'updated');
        settings_errors($this->key.'-notices');
    }




    public function add_options_page() {
        $this->options_page = add_options_page( $this->plugin->plugin_name.' Settings', $this->plugin->plugin_name, $this->capabilities, $this->plugin->plugin_id.'_settings', array( $this, 'admin_page_display' ) );

        // add_action( "admin_head-{$this->options_page}", array( $this, 'enqueue_js' ) );  //allows addition of js to options page
        add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
    }




    /**
     * Admin page markup. Mostly handled by CMB2
     * @since  0.1.0
     */
    public function admin_page_display() {
        //$tabs = self::$tabs;    //apply_filters($this->plugin->plugin_id.'_settings_tabs', array('general' => 'General', 'pro' => 'Pro'));
        //$tabs = apply_filters($this->plugin->plugin_id.'_settings_tabs', array());
        $tabs = $this->get_tabs();

        $current = isset($_GET['tab']) ? $_GET['tab'] : $tabs[0]->id;


        //Display Title
        echo "<div class='wrap cmb2-options-page $this->key'>
            <h2>".esc_html(get_admin_page_title())."</h2>";
        

        //Display Tabs
        if(count($tabs) > 1) {
            echo '<h2 class="nav-tab-wrapper">';
            foreach( $tabs as $tab ){
                $class = ( $tab->id == $current ) ? ' nav-tab-active' : '';
                echo "<a class='nav-tab$class' href='?page=".$this->plugin->plugin_id.'_settings'."&tab=$tab->id'>$tab->name</a>";
            }
            echo '</h2>';
        }


        //Display Current Tab's Form
        foreach ($tabs as $tab)
        {
            if($tab->id == $current) {
                if(count($tabs) == 1) { echo "<h3>$tab->name</h3>"; }
                echo "<p>$tab->description</p>";

                //Save CMB if needed
                if($tab->type == 'cmb') {
                    $cmb = cmb2_get_metabox($tab->cmb['box']['id'], $this->key);
                    $args = array(
		                'form_format' => '<form class="cmb-form" method="post" id="%1$s" enctype="multipart/form-data" encoding="multipart/form-data"><input type="hidden" name="object_id" value="%2$s">%3$s<input type="submit" name="submit-cmb" value="%4$s" class="button-primary"></form>',
		                'save_button' => __( 'Save', 'cmb2' ),
		                'object_type' => $cmb->mb_object_type(),
		                'cmb_styles'  => $cmb->prop( 'cmb_styles' ),
		                'enqueue_js'  => $cmb->prop( 'enqueue_js' ),
	                );
                    $cmb->object_type( $args['object_type'] );
                    if (
		                $cmb->prop( 'save_fields' )
		                // check nonce
		                && isset( $_POST['submit-cmb'], $_POST['object_id'], $_POST[ $cmb->nonce() ] )
		                && wp_verify_nonce( $_POST[ $cmb->nonce() ], $cmb->nonce() )
		                && $this->key && $_POST['object_id'] == $this->key
	                ) {
                        $cmb->save_fields( $this->key, $cmb->object_type(), $_POST );
                        $_POST['submit-cmb'] = null;
                    }
                }
                
                do_action('nsawp_pre_tab_form_'.$tab->id);
                $do_show_form = apply_filters('nsawp_do_show_form_'.$tab->id, true);
                if ($do_show_form) {
                    switch ($tab->type)
                    {
                        case 'cmb':
                            echo cmb2_metabox_form($tab->cmb['box']['id'], $this->key );
                            break;

                        case 'form':
                            echo "<form action='options.php' method='post'><div class='main'>";
                            settings_fields( $tab->id );
                            do_settings_sections( $this->key );
                            submit_button( __( 'Save Changes', $this->plugin->plugin_id ) );
                            echo "</div></form>";
                            break;

                        case 'page':
                            echo "<div class='nsa_option_page'>";
                            call_user_func($tab->sanitize_callback);
                            echo "</div>";
                            break;
                    }
                }
                do_action('nsawp_post_tab_form_'.$tab->id);
                
                
            }
        }
        echo "</div>";
    }


    /**
     * Replaces get_option with get_site_option
     * @since  0.1.0
     */
    public function get_override( $test, $default = false ) {
        return get_site_option( $this->key, $default );
    }



    /**
     * Replaces update_option with update_site_option
     * @since  0.1.0
     */
    public function update_override( $test, $option_value ) {
        do_action($this->plugin->plugin_id.'_update_settings', $option_value);
        return update_site_option( $this->key, $option_value );
    }



    /**
     * Updates the Tab, CMB and Field IDs to ensure no duplicates exist and return a new NSA_WP_Plugin_Settings_Tab
     * @param string $id Identifier for the Tab
     * @param string $name Display Name of the Tab
     * @param string $description Tab description
     * @param array $cmb Array defining CMB with 'box' and 'fields'
     * @param mixed $sanitize_callback
     * 
     * @return NSA_WP_Plugin_Settings_Tab
     */
    public function create_tab($id, $type, $name, $description, $cmb, $sanitize_callback = null, $fields = null) {
        $cmb['box']['id'] = $this->plugin->plugin_id.'_'.$id;

        if($type == 'cmb') {
            for ($i = 0; $i < count($cmb['fields']); $i++)
            {
                $cmb['fields'][$i]['id'] = $this->plugin->plugin_id.'_'.$id.'_'.$cmb['fields'][$i]['id'];
            }
        }
        
        return new NSA_WP_Plugin_Settings_Tab($this->plugin->plugin_id.'_'.$id, $type, $name, $description, $cmb, $sanitize_callback, $fields);
    }

    


    /**
     * Returns the value of a tab's field.  
     * Returns false if the tab or field does not exist or has not been initialized yet.
     * @param string $tab Tab on which the field appears
     * @param string $field Field you need the value from
     * @return string
     */
    public function get_value($tab, $field) {

        //$value = cmb2_get_option( $this->key, $this->plugin->plugin_id.'_'.$tab.'_'.$field );
        $values = $this->get_override(false, false);
        $value = is_array($values) ? array_key_exists( $this->plugin->plugin_id.'_'.$tab.'_'.$field, $values ) ? $values[$this->plugin->plugin_id.'_'.$tab.'_'.$field] : false : false;
        
        
        //Convert string to boolean
        if($value === 'true') return true;
        if($value === 'false') return false;


        ///Get Default
        if($value === false) {
            foreach (self::$tabs as $t)
            {
                if($t->id == $this->get_tab_id($tab)) {
                    foreach($t->cmb['fields'] as $f) {
                        //NOT GOOD. MOVING ON
                        //if($group !== null && $f['type'] == 'group' && $f['id'] == $this->get_field_id($tab, $group)) {
                        //    foreach ($f['group_fields'] as $groupField)
                        //    {
                        //        if(isset($groupField['default'])) return $groupField['default'];
                        //    }
                        //}

                        if($f['id'] == $this->get_field_id($tab, $field)) {
                            if(isset($f['default'])) return $f['default'];
                            
                        }
                    }
                    break;
                }

            }
        }
        

        return $value;
    }
    public function get_tab_id($tab) { return $this->plugin->plugin_id.'_'.$tab; }
    public function get_field_id($tab, $field) { return $this->plugin->plugin_id.'_'.$tab.'_'.$field; }


}

class NSA_WP_Plugin_Settings_Tab {
    public $id;
    public $type;
    public $name;
    public $description;
    public $cmb;
    public $sanitize_callback;
    public $fields;

    public function __construct($id, $type, $name, $description, $cmb, $sanitize_callback = null, $fields = null) {
        $this->id                   = $id;
        $this->type                 = $type;
        $this->name                 = $name;
        $this->description          = $description;
        $this->cmb                  = $cmb;
        $this->sanitize_callback    = $sanitize_callback;
        $this->fields               = $fields;
    }

}


    //function nsa_wpfbpActivate() {
    //    $firstInstall = get_option($this->plugin_id.'_install_date', null);
    //    if($firstInstall == null) {
    //        $firstInstall = getdate();
    //        update_option($this->plugin_id.'_install_date', $firstInstall);
    //    }
    //    $this->firstInstall = $firstInstall;
    //}
