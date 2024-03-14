<?php

class Wsl_Admin{
    
    private $page_title = 'Lead Generated Settings';
    
    private $option_name = 'wsl_settings';
    
    public function __construct() {
        add_action( 'admin_init', array($this,'page_init') );
        add_action( 'admin_menu', array( $this, 'add_page' ) );
    }
    
    public function add_page(){
        // This page will be under "Settings"
        add_options_page(
            $this->page_title, 
            $this->page_title, 
            'manage_options', 
            'lead-management-system-settings',
            array( $this, 'create_admin_page' )
        );
    }
    
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( $this->option_name );
        ?>
        <div class="wrap">
            <h1><?php echo $this->page_title; ?></h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'lgcrm_options_group' );
                do_settings_sections( 'wsl-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }
    
    public function page_init()
    {        
        register_setting(
            'lgcrm_options_group', // Option group
            $this->option_name, // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'lgcrm_section', // ID
            '', // Title
            array( $this, 'print_section_info' ), // Callback
            'wsl-setting-admin' // Page
        );  

        add_settings_field(
            'api_key', // ID
            'API Key', // Title 
            array( $this, 'api_key_callback' ), // Callback
            'wsl-setting-admin', // Page
            'lgcrm_section' // Section           
        );
        
        add_settings_field(
            'send_to_crm', // ID
            'Send To CRM', // Title 
            array( $this, 'sent_to_crm_callback' ), // Callback
            'wsl-setting-admin', // Page
            'lgcrm_section' // Section           
        );
        
        add_settings_field(
            'send_to_company', // ID
            'Send To Company', // Title 
            array( $this, 'sent_to_company_callback' ), // Callback
            'wsl-setting-admin', // Page
            'lgcrm_section' // Section           
        ); 
    }
    
     /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input ){
        return $input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        //print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function api_key_callback()
    {
        printf(
            '<input type="text" id="api_key" name="wsl_settings[api_key]" class="regular-text" value="%s" />',
            isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key']) : ''
        );
    }
    
    public function sent_to_crm_callback(){
        echo '<label>';
        echo '<input '. checked(lgcrm_get_setting('send_to_crm'), 1, false).' type="checkbox" id="send_to_crm" name="wsl_settings[send_to_crm]" class="regular-text" value="1" />';
        echo 'Enable sending to CRM';
        echo '</label>';
        echo '<p class="description">Set default setting for contact forms. You can overwrite this setting for a specific contact form as well by editing the contact form.</p>';
    }
    
    public function sent_to_company_callback(){
        $api_obj = new Wsl_Api();
        $company = $api_obj->get_companies();
        $meta = lgcrm_get_settings();
        include_once LGCRM_ADMIN_DIR.'/partials/send_to_company_field.php';
    }
    
}

