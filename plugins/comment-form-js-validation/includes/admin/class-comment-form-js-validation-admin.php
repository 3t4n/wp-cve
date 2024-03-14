<?php
class CommentFormJsValidationSetting
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    
    /**
     * Start up
     */
    public function __construct()
    {
        /*delete_option( 'nv_comment_form_jv' );
        delete_option( 'nv_comment_form_jv_captch' );*/
        add_action( 'admin_menu', array( $this, 'nv_add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'nv_page_init' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'nv_wp_enqueue_admin_style' )  );
    }


    public function nv_wp_enqueue_admin_style(){
        wp_enqueue_style( 'nv-validation-admin-style',  NV_CFJV_DIR_URL.'includes/admin/css/nv-validation-admin.css');
        wp_enqueue_style( 'nv-validation-admin-style' );
    }

    /**
     * Add options page
     */
    public function nv_add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Comment form js validation Settings', 
            'Comment form js validation', 
            'manage_options', 
            'comment-form-jv-setting', 
            array( $this, 'nv_create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function nv_create_admin_page()
    {
        $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'nv_cfjv_message';  
    ?>  
        <div class="wrap">
            <h1 class="wp-heading-inline">Comment form js validation Settings</h1>
            <!-- <a href="http://10.16.16.129/wp-dev/wp-admin/post-new.php" class="page-title-action">Reset Settings</a> -->

            <h2 class="nav-tab-wrapper">  
                <a href="?page=comment-form-jv-setting&tab=nv_cfjv_message" class="nav-tab <?php echo $active_tab == 'nv_cfjv_message' ? 'nav-tab-active' : ''; ?>">Validation Message</a>  
                <a href="?page=comment-form-jv-setting&tab=nv_cfjv_captch" class="nav-tab <?php echo $active_tab == 'nv_cfjv_captch' ? 'nav-tab-active' : ''; ?>">Google Captcha</a>
                 <a href="?page=comment-form-jv-setting&tab=nv_cfjv_help" class="nav-tab <?php echo $active_tab == 'nv_cfjv_help' ? 'nav-tab-active' : ''; ?>">Help & Info</a>
                 
            </h2> 

            <form method="post" action="options.php">
            <?php
                
                if( $active_tab == 'nv_cfjv_message' ) {

                    $this->options = get_option( 'nv_comment_form_jv' );
                    settings_fields( 'nv_comment_form_jv_group_1' );
                    do_settings_sections( 'comment-form-jv-setting-1' );
                    submit_button();
                } else if($active_tab == 'nv_cfjv_captch'){
                    
                    $this->options = get_option( 'nv_comment_form_jv_captch' );
                    settings_fields( 'nv_comment_form_jv_group_2' );
                    do_settings_sections( 'comment-form-jv-setting-2' );
                    submit_button();
                } else if($active_tab == 'nv_cfjv_help'){
                    $this->nv_cfjv_help_and_info();
                }
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function nv_page_init()
    {        
        register_setting(
            'nv_comment_form_jv_group_1', // Option group
            'nv_comment_form_jv', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        // add_settings_field( $id, $title, $callback, $page, $section, $args )
        add_settings_section(
            'nv_comment_form_jv_section_id', // ID
            'Custom Validation Message', // Title
            array( $this, 'nv_print_section_info' ), // Callback
            'comment-form-jv-setting-1' // Page
        );

        add_settings_field(
            'comment_validation_msg', // ID
            'Comment', // Title 
            array( $this, 'comment_comment_callback' ), // Callback
            'comment-form-jv-setting-1', // Page
            'nv_comment_form_jv_section_id' // Section           
        );      

        add_settings_field(
            'name_validation_msg', 
            'Name', 
            array( $this, 'comment_name_callback' ), 
            'comment-form-jv-setting-1', 
            'nv_comment_form_jv_section_id'
        );

        add_settings_field(
            'email_validation_msg', 
            'Email', 
            array( $this, 'comment_email_callback' ), 
            'comment-form-jv-setting-1', 
            'nv_comment_form_jv_section_id'
        ); 

        /*add_settings_field(
            'website_validation_msg', 
            'Webite', 
            array( $this, 'comment_website_callback' ), 
            'comment-form-jv-setting', 
            'nv_comment_form_jv_section_id'
        );*/

       
        //Captcha Section Start
        register_setting(
            'nv_comment_form_jv_group_2', // Option group
            'nv_comment_form_jv_captch', // Option name
            array( $this, 'nv_google_captcha_section_input' ) // Sanitize
        );

        add_settings_section(
            'nv_comment_form_jv_captch_section', // ID
            'Authentication', // Title
            array( $this, 'nv_print_captcha_section_info' ), // Callback
            'comment-form-jv-setting-2' // Page
        );

         add_settings_field(
            'enable_google_captcha', 
            'Enable Captcha', 
            array( $this, 'comment_enable_google_captcha_callback' ), 
            'comment-form-jv-setting-2', 
            'nv_comment_form_jv_captch_section'
        );

        add_settings_field(
            'google_captcha_site_key', 
            'Site Key', 
            array( $this, 'nv_cfjv_form_google_captcha_site_key_callback' ), 
            'comment-form-jv-setting-2', 
            'nv_comment_form_jv_captch_section'
        );

        add_settings_field(
            'google_captcha_secret_key', 
            'Secret Key', 
            array( $this, 'nv_cfjv_form_google_captcha_secret_key_callback' ), 
            'comment-form-jv-setting-2', 
            'nv_comment_form_jv_captch_section'
        );


    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['comment_comment_msg'] ) )
            $new_input['comment_comment_msg'] = sanitize_text_field( $input['comment_comment_msg'] );

        if( isset( $input['comment_name_msg'] ) )
            $new_input['comment_name_msg'] = sanitize_text_field( $input['comment_name_msg'] );

        if( isset( $input['comment_email_msg'] ) )
            $new_input['comment_email_msg'] = sanitize_text_field( $input['comment_email_msg'] );

        /*if( isset( $input['comment_website_msg'] ) )
            $new_input['comment_website_msg'] = sanitize_text_field( $input['comment_website_msg'] );*/

        return $new_input;
    }


    public function nv_google_captcha_section_input( $input ){

        $new_input = array();
        if( isset( $input['comment_enable_google_captcha'] ) ){
            $new_input['comment_enable_google_captcha'] = sanitize_text_field( $input['comment_enable_google_captcha'] );
        } else {
            $new_input['comment_enable_google_captcha'] = 0;
        }

        if( isset( $input['google_captcha_site_key'] ) )
            $new_input['google_captcha_site_key'] = sanitize_text_field( $input['google_captcha_site_key'] );

        if( isset( $input['google_captcha_secret_key'] ) )
            $new_input['google_captcha_secret_key'] = sanitize_text_field( $input['google_captcha_secret_key'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function nv_print_section_info()
    {
        print 'Enter the message, if you want set your own validation message.';
    }

    public function nv_print_captcha_section_info(){
        print 'Register your website with Google to get required API keys and enter theme below. <a href="'.NV_CFJV_RECAPTCHA_SITE.'" target="_blank">Get the API keys</a>';
            /*<br /><a href="">How to create API keys?</a>*/
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function comment_comment_callback()
    {
        printf(
            '<input type="text" class="regular-text" id="comment_comment_msg" name="nv_comment_form_jv[comment_comment_msg]" value="%s" />',
            isset( $this->options['comment_comment_msg'] ) ? esc_attr( $this->options['comment_comment_msg']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function comment_name_callback()
    {
        printf(
            '<input type="text" class="regular-text" id="comment_name_msg" name="nv_comment_form_jv[comment_name_msg]" value="%s" />',
            isset( $this->options['comment_name_msg'] ) ? esc_attr( $this->options['comment_name_msg']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function comment_email_callback()
    {
        printf(
            '<input type="text" class="regular-text" id="comment_email_msg" name="nv_comment_form_jv[comment_email_msg]" value="%s" />',
            isset( $this->options['comment_email_msg'] ) ? esc_attr( $this->options['comment_email_msg']) : ''
        );
    }


    /** 
     * Get the settings option array and print one of its values
     */
    /*public function comment_website_callback()
    {
        printf(
            '<input type="text" class="regular-text" id="comment_website_msg" name="nv_comment_form_jv[comment_website_msg]" value="%s" />',
            isset( $this->options['comment_website_msg'] ) ? esc_attr( $this->options['comment_website_msg']) : ''
        );
    }*/

    public function comment_enable_google_captcha_callback(){
        
        printf(
            '<input type="checkbox" class="regular-text" id="comment_google_captcha" name="nv_comment_form_jv_captch[comment_enable_google_captcha]" value="%s" %s/>',
            1, isset( $this->options['comment_enable_google_captcha'] ) &&  $this->options['comment_enable_google_captcha'] == 1 ? 'checked="checked"' : "" 
        );   
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function nv_cfjv_form_google_captcha_site_key_callback()
    {
        printf(
            '<input type="text" class="regular-text" id="comment_email_msg" name="nv_comment_form_jv_captch[google_captcha_site_key]" value="%s" />',
            isset( $this->options['google_captcha_site_key'] ) ? esc_attr( $this->options['google_captcha_site_key']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function nv_cfjv_form_google_captcha_secret_key_callback()
    {
        printf(
            '<input type="text" class="regular-text" id="comment_email_msg" name="nv_comment_form_jv_captch[google_captcha_secret_key]" value="%s" />',
            isset( $this->options['google_captcha_secret_key'] ) ? esc_attr( $this->options['google_captcha_secret_key']) : ''
        );
    }

    public function nv_cfjv_help_and_info(){
        ?>
        <h1>Obtaining your Google reCAPTCHA API Keys pair</h1>
       <ol class="nv-cfjv-custom-counter">
        <li>
            <p>Visit the <a href="<?php echo NV_CFJV_RECAPTCHA_SITE; ?>" target="_blank">Google reCAPTCHA official site</a> and press the <em>Admin console</em> button.</p>
            <p>Afterwards, perhaps you have to log in if you are not identified with your Google Account (typically your Gmail username and password).</p>
            <p class="image"><img src="<?php echo NV_CFJV_DIR_URL.'/images/step1.png'; ?>"></p>
        </li>
        <li>
            <p>Creating a new <strong>Google reCAPTCHA</strong>.</p>
            <p>Go to a <strong>Register a New Site</strong> section where first, you have to create a new <em>Label</em> for the new reCAPTCHA and, in the second field, you have to choose the option <strong>reCAPTCHA v2</strong> as its <em>Type</em>.</p>
            <p>At this point, a third field called <strong>Domains</strong> will appear on the screen where you have to write the list of the web <em>Domains</em> that will be able to use this new reCAPTCHA. In our case, you have to write the <strong>domain name of your WordPress site</strong>. Finally, remember to accept the terms of services marking the next check box.</p>
            <p class="image"><img src="<?php echo NV_CFJV_DIR_URL.'/images/step2.png'; ?>"></p>
        </li>
        <li>
            <p>Press the <em>Submit</em> button for saving the new <strong>Google reCAPTCHA</strong>.</p>
            <p>Once you've registered the new Google reCAPTCHA, you can see the <span class="warning"><strong>Google reCAPTCHA API Keys pair</strong></span>.</p>
            <p class="image"><img src="<?php echo NV_CFJV_DIR_URL.'/images/step3.png'; ?>"></p>
        </li>
    </ol>
        <?php
    }
}
?>