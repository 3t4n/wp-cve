<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Lion_Badge_Settings_Page {

    private $options;

    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        add_submenu_page(
        	'edit.php?post_type=lion_badge',
            __( 'Settings', 'lionplugins' ),
            __( 'Settings', 'lionplugins' ), 
            'manage_options', 
            'lion_badges_settings', 
            array( $this, 'create_settings_page' )
        );
    }

    public function create_settings_page() {

        $this->options = get_option( 'lion_badges' );

        ?>
        <div class="wrap">
            <h1><?php _e('Settings', 'lionplugins'); ?></h1>
            <form method="post" action="options.php">
            <?php
                settings_fields( 'badges_option_group' );
                do_settings_sections( 'badges-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init() {        
        register_setting(
            'badges_option_group',
            'lion_badges',
            array( $this, 'sanitize' )
        );

        add_settings_section(
            'setting_section',
            __('General', 'lionplugins'),
            array( $this, 'print_section_info' ),
            'badges-setting-admin'
        );  

        add_settings_field(
            'hide_default_wc_badge',
            __('Hide default WooCommerce sale badge', 'lionplugins'),
            array( $this, 'hide_default_sale_badge_cb_callback' ),
            'badges-setting-admin',
            'setting_section'    
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input ) {
        $new_input = array();
        if( isset( $input['hide_default_wc_badge'] ) )
            $new_input['hide_default_wc_badge'] = absint( $input['hide_default_wc_badge'] );

        return $new_input;
    }

    public function print_section_info() {  
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function hide_default_sale_badge_cb_callback() {
        echo '<input type="checkbox" id="hide_default_wc_badge" name="lion_badges[hide_default_wc_badge]" value="1" ' . checked( $this->options['hide_default_wc_badge'], 1, false ) . ' />';
    }
}

new Lion_Badge_Settings_Page();