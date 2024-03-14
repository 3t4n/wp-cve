<?php

include_once('class-account-information.php');
include_once('class-form-setup.php');
include_once('class-form-custom.php');
include_once('class-help.php');

class Emma_Settings {

    private static $_plugin_options_key = 'emma_plugin_options';
    private $_plugin_settings_tabs = array();

    function __construct() {

        // make sure the admin menu gets hooked up in the admin menu
        add_action( 'admin_menu', array( &$this, 'add_admin_menus' ) );

        // add action link
        add_filter( 'plugin_action_links', array( &$this, 'add_action_links' ), 10, 2 );

        // Register settings
        $this->register_settings();

        // Add admin stylesheet
        // add_action( 'admin_init', array( &$this, 'admin_stylesheet_init' ) );

    }


    function register_settings() {

        $this->_plugin_settings_tabs[ Account_Information::$key ]   = 'Account Information';
        $this->_plugin_settings_tabs[ Form_Setup::$key ]            = 'Form Setup';
        $this->_plugin_settings_tabs[ Form_Custom::$key ]           = 'Form Customization';
        $this->_plugin_settings_tabs[ Advanced_Settings::$key ]   	= 'Advanced Settings';
        $this->_plugin_settings_tabs[ Help::$key ]                  = 'Help';

        $account_information = new Account_Information();
        $form_setup = new Form_Setup();
        $form_custom = new Form_Custom();
        $advanced_settings = new Advanced_Settings();
        $help = new Help();

    }

    function admin_stylesheet_init() {
        // Register our stylesheet.
        // wp_register_style( 'emma-form-styles', plugins_url('class-style.php', __FILE__) );
        // wp_enqueue_style( 'emma-form-styles' );
    }

    /**
     * Add action links to installed plugins page
     * @param $links
     * @param $file
     * @return array
     */
    function add_action_links($links, $file) {
        static $this_plugin;
        if (!$this_plugin) $this_plugin = EMMA_EMARKETING_FILE;
        if ($file == $this_plugin) {
            /**
             * The "page" query string value must be equal to the slug
             * of the Settings admin page we defined earlier,
             * the $_plugin_options_key property of this class which in
             * this case equals "emma_plugin_options".
             */
            $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=' . self::$_plugin_options_key . '">Settings</a>';
            array_unshift($links, $settings_link);
        }
        return $links;
    }

    /*
      * Called during admin_menu, adds an options
      * page under Settings called Emma Emarketing Settings, rendered
      * using the plugin_options_page method.
      */
    function add_admin_menus() {
        // add_options_page( $page_title, $menu_title, $capability, $menu_slug, $callback );
        add_options_page( 'Emma for WordPress', 'Emma for WordPress', 'manage_options', self::$_plugin_options_key, array( &$this, 'plugin_options_page' ) );

        // enqueue stylesheet for form preview only on our plugin settings page, not entire admin area.
        // Using registered $menu_slug from add_options_page handle to hook stylesheet loading
        // no love this time. get_option() not available. should've gone w/ the value object
        // add_action( 'admin_print_styles-' . self::$_plugin_options_key, 'emma-form-styles' );

    }

    /*
      * Plugin Options page rendering goes here, checks
      * for active tab and replaces key with the related
      * settings key. Uses the plugin_options_tabs method
      * to render the tabs.
      */
    function plugin_options_page() {
        $tab = isset( $_GET['tab'] ) ? $_GET['tab'] : Account_Information::$key;
        ?>
        <div class="wrap">
            <?php $this->plugin_options_tabs(); ?>
            <form method="post" action="options.php">
                <?php
                wp_nonce_field( 'update-options' );
                settings_fields( $tab );
                do_settings_sections( $tab );
                // don't do the submit button on the help tab...
                if ( $tab !== 'emma_help' ) {
                    echo '<p class="submit emma-submit">';
                    submit_button( 'Save', 'primary', $tab . '[submit]', false, array( 'id' => 'submit' ) );
                    submit_button( 'Reset', 'primary', $tab . '[reset]', false, array( 'id' => 'reset' ) );
                    echo '</p>';
                }
                ?>
            </form>
            <?php if ( $tab !== 'emma_help' ) {
            echo '
                <h3>DISPLAYING THE FORM ON YOUR SITE</h3>
                <p>
                    To insert the form as a <strong>widget</strong> on your sidebar, go to Appearance -> Widgets and then move
                    the “Emma for Wordpress Subscription Form” to the widget area where you want the form to appear.
                </p>
                <p>
                    To insert the form as a <strong>shortcode</strong> within your site, insert [emma_form] within your text editor
                    where you want the form to appear.
                </p>
            ';
            } ?>
        </div>
    <?php }

    /*
      * Renders our tabs in the plugin options page,
      * walks through the object's tabs array and prints
      * them one by one. Provides the heading for the
      * plugin_options_page method.
      */

    function plugin_options_tabs() {
        $current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : Account_Information::$key;
        screen_icon();
        echo '<img class="emma-settings-logo" style="margin:3px 30px 0 0; float: left; width:170px;height:auto;" src="' . EMMA_EMARKETING_ASSETS . '/images/Emma_Logo_Horizontal.png"/>';
        echo '<h2 class="nav-tab-wrapper" style="padding-top:40px">';
        foreach ( $this->_plugin_settings_tabs as $tab_key => $tab_caption ) {
            $active = $current_tab == $tab_key ? 'nav-tab-active' : '';
            echo '<a class="nav-tab ' . $active . '" href="?page=' . self::$_plugin_options_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
        }
        echo '</h2>';
        // a little output buffering to keep the settings errors from jumping to the top of the page.
        ob_start();
        //settings_errors();
        $errors = ob_get_contents();
        ob_end_clean();
        echo $errors;
    }


}
