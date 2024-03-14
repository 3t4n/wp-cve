<?php

/**
 * Define the main plugin class
 *
 * @since   0.2.6
 *
 * @package Easy_Marijuana_Age_Verify
 */
// Don't allow this file to be accessed directly.
if ( !defined( 'WPINC' ) ) {
    die;
}
/**
 * The main class.
 *
 * @since 0.1.0
 */
final class Easy_Marijuana_Age_Verify
{
    /**
     * The plugin version.
     *
     * @since 0.2.6
     */
    const  VERSION = '1.5.2' ;
    /**
     * The only instance of this class.
     *
     * @since  0.2.6
     * @access protected
     */
    protected static  $instance = null ;
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected  $plugin_name ;
    protected  $slug ;
    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected  $version ;
    /**
     * Construct the class!
     *
     * @return void
     * @since 0.1.0
     *
     */
    public function __construct()
    {
        $this->file = $this->file();
        $this->version = self::VERSION;
        $this->plugin_name = 'easy-marijuana-age-verify';
        $this->slug = 'emav';
        /**
         * Require the necessary files.
         */
        $this->require_files();
        /**
         * Add the necessary action hooks.
         */
        $this->add_actions();
    }

    private function file()
    {
        return EMAV_PLUGIN_DIR_PATH;
    }

    /**
     * Require the necessary files.
     *
     * @return void
     * @since 0.1.0
     *
     */
    private function require_files()
    {
        /**
         * The helper functions.
         */
        require EMAV_PLUGIN_DIR_PATH . 'includes/functions.php';
    }

    /**
     * Add the necessary action hooks.
     *
     * @return void
     * @since 0.1.0
     *
     */
    private function add_actions()
    {
        // Load the text domain for i18n.
        add_action( 'init', array( $this, 'load_textdomain' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_required_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_head', array( $this, 'custom_styles' ) );
        // Maybe display the overlay.
        add_action( 'wp_footer', array( $this, 'verify_overlay' ) );
        // Maybe hide the content of a restricted content type.
        add_action( 'the_content', array( $this, 'restrict_content' ) );
        // Verify the visitor's input.
        add_action( 'template_redirect', array( $this, 'verify' ) );
        // If checked in the settings, add to the registration form.

        if ( emav_confirmation_required() ) {
            add_action( 'register_form', 'emav_register_form' );
            add_action(
                'register_post',
                'emav_register_check',
                10,
                3
            );
        }

    }

    /**
     * Get the only instance of this class.
     *
     * @return object $instance The only instance of this class.
     * @since 0.2.6
     *
     */
    public static function get_instance()
    {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Prevent cloning of this class.
     *
     * @return void
     * @since 0.2.6
     *
     */
    public function __clone()
    {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', $this->plugin_name ), self::VERSION );
    }

    /**
     * Prevent unserializing of this class.
     *
     * @return void
     * @since 0.2.6
     *
     */
    public function __wakeup()
    {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', $this->plugin_name ), self::VERSION );
    }

    /**
     * Load the text domain.
     *
     * Based on the bbPress implementation.
     * @description returns The textdomain or false on failure.
     * @return string|false
     * @since       0.1.0
     *
     */
    public function load_textdomain()
    {
        $locale = get_locale();
        $locale = apply_filters( 'plugin_locale', $locale, $this->plugin_name );
        $mofile = sprintf( $this->plugin_name . '-%s.mo', $locale );
        $mofile_local = plugin_dir_path( dirname( __FILE__ ) ) . 'languages/' . $mofile;
        $mofile_global = WP_LANG_DIR . '/' . $this->plugin_name . '/' . $mofile;
        if ( file_exists( $mofile_local ) ) {
            return load_textdomain( $this->plugin_name, $mofile_local );
        }
        if ( file_exists( $mofile_global ) ) {
            return load_textdomain( $this->plugin_name, $mofile_global );
        }
        load_plugin_textdomain( $this->plugin_name );
        return false;
    }

    /**
     * Load required scripts for
     */
    public static function enqueue_required_scripts()
    {
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-migrate' );
    }

    /**
     * Enqueue the styles.
     *
     * @return void
     * @since 0.1.0
     *
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(
            'emav-styles',
            plugin_dir_url( __FILE__ ) . 'assets/styles.css',
            array(),
            filemtime( plugin_dir_path( __FILE__ ) . 'assets/styles.css' )
        );
    }

    public function enqueue_scripts()
    {
        global  $emav_fs, $scriptsHandle ;
        $scriptsHandle = 'emav-scripts';
        wp_register_script(
            $scriptsHandle,
            plugin_dir_url( __FILE__ ) . 'assets/scripts.js',
            array(),
            filemtime( plugin_dir_path( __FILE__ ) . 'assets/scripts.js' )
        );
        wp_localize_script( $scriptsHandle, 'WPURLS', array(
            'siteurl' => get_option( 'siteurl' ),
            'path'    => $_SERVER['REQUEST_URI'],
        ) );
        wp_localize_script( $scriptsHandle, 'emav_ajax_object', array(
            'ajax_url'            => admin_url( 'admin-ajax.php' ),
            'verification_status' => get_option( '_emav_always_verify' ),
        ) );
        wp_enqueue_script( $scriptsHandle );
    }

    /**
     * Print the custom colors, as defined in the admin.
     *
     * @return void
     * @since 0.1.0
     *
     */
    public function custom_styles()
    {
        ?>
<style type="text/css">
#emav-overlay-wrap {
    background: rgba(0, 0, 0, .85);
	display:none;
}
</style>
<?php
        /**
         * Trigger action after setting the custom color styles.
         */
        do_action( 'emav_custom_styles' );
    }

    /**
     * Print the actual overlay if the visitor needs verification.
     *
     * @return void
     * @since 0.1.0
     *
     */
    public function verify_overlay()
    {
        // If set to Not Verify, skip outputting the overlay completely. We should add JS and CSS too...
        if ( get_option( '_emav_always_verify', 'disabled' ) == 'disabled' ) {
            return;
        }
        $overlay_style = 'style="display:none;background: rgba(00, 00, 00, 0.85);"';

        if ( function_exists( 'emav_premium_overlay_style' ) ) {
            $bg_clr = ltrim( emav_get_overlay_color(), '#' );
            $split_bg_clr = str_split( $bg_clr, 2 );
            $r_bg_clr = hexdec( $split_bg_clr[0] );
            $g_bg_clr = hexdec( $split_bg_clr[1] );
            $b_bg_clr = hexdec( $split_bg_clr[2] );
            $overlay_style = 'style="display:none;background:rgba(' . $r_bg_clr . ', ' . $g_bg_clr . ', ' . $b_bg_clr . ', ' . emav_get_transparency() . ' );"';
        }

        ?>

        <div id="emav-overlay-wrap" <?php
        echo  $overlay_style ;
        ?>>
			<?php
        do_action( 'emav_before_modal' );
        ?>
            <div id="emav-overlay">
				<?php
        do_action( 'emav_before_form' );
        ?>
				<?php
        emav_verify_form();
        ?>
				<?php
        do_action( 'emav_after_form' );
        ?>
            </div>
			<?php
        do_action( 'emav_after_modal' );
        ?>
        </div>
	<?php
    }

    /**
     * Hide the content if it is age restricted.
     *
     * @param string $content The object content.
     *
     * @return string $content The object content or an age-restricted message if needed.
     * @since 0.2.0
     *
     */
    public function restrict_content( $content )
    {
        if ( !emav_only_content_restricted() ) {
            return $content;
        }
        if ( is_singular() ) {
            return $content;
        }
        if ( !emav_content_is_restricted() ) {
            return $content;
        }
        return sprintf( apply_filters( 'emav_restricted_content_message', __( 'You must be %1s years old to view this content.', $this->plugin_name ) . ' <a href="%2s">' . __( 'Please verify your age', $this->plugin_name, $this->plugin_name ) . '</a>.' ), esc_html( emav_get_minimum_age() ), esc_url( get_permalink( get_the_ID() ) ) );
    }

    /**
     * Verify the visitor if the form was submitted.
     *
     * @return void
     * @since 0.1.0
     *
     */
    public function verify()
    {
    }

}