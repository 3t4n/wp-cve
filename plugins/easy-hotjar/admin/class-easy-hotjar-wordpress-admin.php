<?php

/**
 * The Easy hotjar WordPress plugin helps you to set up hotjar on your site.
 *
 * @package EHW
 */


class Easy_Hotjar_WordPress_Admin {

    /**
     * A reference to the version of the plugin that is passed to this class from the caller.
     *
     * @access private
     * @var    string    $version    The current version of the plugin.
     */
    private $version;

    /**
     * Initializes this class and stores the current version of this plugin.
     *
     * @param    string    $version    The current version of this plugin.
     */
    public function __construct( $version ) {
        $this->version = $version;
    }

    /**
     * Enqueues the style sheet responsible for styling the contents of this
     * meta box.
     */
    public function enqueue_styles() {
        $currentScreen = get_current_screen();
        if( $currentScreen->base === "toplevel_page_options_ehw_page" ) {
            // Run some code, only on the admin widgehw page
            wp_enqueue_style(
                'bootstrap',
                plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css',
                array(),
                $this->version,
                FALSE
            );
        }
    }

    /**
     *
     * Registers the options page that will be used to set the timeout length.
     */
    public function create_theme_ehw_page() {
        add_menu_page('Hotjar WordPress', 'Hotjar WordPress', 'manage_options', 'options_ehw_page',
            'Easy_Hotjar_WordPress_Admin::build_ehw_options_page','dashicons-networking');
    }
    /**
     * Requires the file that is used to display the user interface of the post meta box.
     */
    public static function build_ehw_options_page() {
        require_once plugin_dir_path( __FILE__ ) . 'partials/easy-hotjar-wordpress.php';
    }

    /**
     * Registers the settings of the options page previously defined.
     */
    public function register_ehw_mysettings() {
        //register our settings
        register_setting( 'ehw', 'ehw');
    }

    /**
     * Registers the filter for the cookie expiration timing (authentication).
     */
    public function ehw_add_script() {
        $ehw = get_option('ehw');
        echo "\n\n<!-- Begins Hotjar Tracking Code Using Easy Hotjar WordPress Plugin -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:".$ehw['num'].",hjsv:5};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
</script>
<!-- Ends Hotjar Tracking Code Using Easy Hotjar WordPress Plugin -->\n\n";

    }

    /**
     * Registers the filter for adding the settings page link to the plugin list
     */
    public function ehw_settings_link($links) {
        $settings_link = '<a href="admin.php?page=options_ehw_page">Settings</a>';
        array_push($links, $settings_link);
        return $links;
    }

}
