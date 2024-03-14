<?php

/**
 * Define the admin class
 *
 * @since   0.2.6
 *
 * @package Easy_Marijuana_Age_Verify\Admin
 */
// Don't allow this file to be accessed directly.
if ( !defined( 'WPINC' ) ) {
    die;
}
/**
 * The admin class.
 *
 * @since 0.2.6
 */
final class Easy_Marijuana_Age_Verify_Admin
{
    /**
     * The only instance of this class.
     *
     * @since  0.2.6
     * @access protected
     */
    protected static  $instance = null ;
    /**
     * Construct the class!
     *
     * @return void
     * @since 0.2.6
     *
     */
    public function __construct()
    {
        global  $emav_fs ;
        $this->version = Easy_Marijuana_Age_Verify::VERSION;
        /**
         * The settings callbacks.
         */
        require EMAV_PLUGIN_DIR_PATH . 'includes/admin/settings.php';
        // default stock age option
        $optionVerify1 = '_emav_user_age_verify_option';
        // $optionVerify1 = $optionVerify2 = '';
        if ( empty(get_option( $optionVerify1 )) ) {
            //update_option( $optionVerify1, 1 );
        }
        // set disable verification on initial install
        $optionVerify2 = '_emav_always_verify';
        if ( empty(get_option( $optionVerify2 )) ) {
            update_option( $optionVerify2, 'disabled' );
        }
        // Add the settings page.
        add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        // Add and register the settings sections and fields.
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_init', array( $this, 'default_settings' ) );
        // Add the "Settings" link to the plugin row.
        add_filter(
            'plugin_action',
            array( $this, 'add_settings_link' ),
            10,
            2
        );
        
        if ( $emav_fs->is_not_paying() ) {
            add_action( 'admin_enqueue_scripts', array( $this, 'emav_beacon_header_free' ) );
            // Add Helpscout Free Beacon code
        }
        
        // Enqueue the script.
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        emav_load_plugin_textdomain();
        // Only load with post-specific stuff if enabled.
        
        if ( 'content' == get_option( '_emav_require_for' ) ) {
            // Add a "restrict" checkbox to individual posts/pages.
            add_action( 'post_submitbox_misc_actions', array( $this, 'add_submitbox_checkbox' ) );
            // Save the "restrict" checkbox value.
            add_action( 'save_post', array( $this, 'save_post' ) );
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
     * Add a direct link to the Age Verify settings page from the plugins page.
     *
     * @param array $actions The links beneath the plugin's name.
     * @param       $plugin_file
     *
     * @return array
     * @since 0.2.6
     *
     */
    public static function add_settings_link( $actions, $plugin_file )
    {
        static  $plugin ;
        if ( !isset( $plugin ) ) {
            $plugin = plugin_basename( EMAV_PLUGIN_FILE );
        }
        
        if ( $plugin == $plugin_file ) {
            $settings_link = sprintf( '<a href="%s">%s</a>', $link = esc_url( add_query_arg( 'page', 'easy-marijuana-age-verify', admin_url( 'admin.php' ) ) ), $menu_text = __( 'Settings', 'easy-marijuana-age-verify' ) );
            array_unshift( $actions, $settings_link );
        }
        
        return $actions;
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
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'easy-marijuana-age-verify' ), $this->version );
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
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'easy-marijuana-age-verify' ), $this->version );
    }
    
    /**
     * Add to the settings page.
     *
     * @return void
     * @since 0.2.6
     *
     */
    public function add_settings_page()
    {
        add_menu_page(
            'Marijuana Age Verify',
            'Marijuana Age Verify',
            'manage_options',
            'easy-marijuana-age-verify',
            'emav_settings_page',
            'dashicons-shield-alt'
        );
    }
    
    /**
     * Add and register the settings sections and fields.
     *
     * @return void
     * @since 0.2.6
     *
     */
    public function register_settings()
    {
        // General Section
        add_settings_section(
            'emav_settings_general',
            null,
            'emav_settings_callback_section_general',
            'easy-marijuana-age-verify'
        );
        // Set to Disabled or Who to verify (not logged in or all)
        add_settings_field(
            '_emav_always_verify',
            __( 'Enable Verification', 'easy-marijuana-age-verify' ) . ' <span class="dashicons dashicons-info emavoptionshovertip" title="' . __( 'Popup will not show when disabled. Use Testing Mode during setup, then show when it\'s ready.', 'easy-marijuana-age-verify' ) . '"></span>',
            'emav_settings_callback_always_verify_field',
            'easy-marijuana-age-verify',
            'emav_settings_general'
        );
        register_setting( 'easy-marijuana-age-verify', '_emav_always_verify', 'esc_attr' );
        // AJAX Section
        add_settings_field(
            '_emav_settings_ajax',
            __( 'Realtime Settings Check', 'easy-marijuana-age-verify' ) . ' <span class="dashicons dashicons-info emavoptionshovertip" title="' . __( 'May help caching issues, adds delay to popup.', 'easy-marijuana-age-verify' ) . '"></span>' . $this->small( '(<a href="https://support.5starplugins.com/article/202-realtime-settings-check" target="_blank">Learn more</a>)' ),
            'emav_settings_callback_ajax_check',
            'easy-marijuana-age-verify',
            'emav_settings_general'
        );
        register_setting( 'easy-marijuana-age-verify', '_emav_ajax_check', 'esc_attr' );
        // Ask visitors to be over certain age
        add_settings_field(
            '_emav_user_age_verify_option',
            __( 'Verify For', 'easy-marijuana-age-verify' ) . ' <span class="dashicons dashicons-info emavoptionshovertip" title="' . __( 'Select a turnkey setting. Upgrade to Premium to edit text and background.', 'easy-marijuana-age-verify' ) . '"></span>',
            'emav_settings_callback_ask_visitors_field',
            'easy-marijuana-age-verify',
            'emav_settings_general'
        );
        register_setting( 'easy-marijuana-age-verify', '_emav_user_age_verify_option', 'esc_attr' );
        // Disclaimer
        add_settings_field(
            '_emav_disclaimer',
            sprintf( '<label for="_emav_disclaimer">%s</label>', __( 'Disclaimer Text' . ' <span class="dashicons dashicons-info emavoptionshovertip" title="' . __( 'Optional small print takes HTML link.', 'easy-marijuana-age-verify' ) . '"></span>' . $this->small( '(max 250 characters)' ), 'easy-marijuana-age-verify' ) ),
            'emav_settings_callback_disclaimer_field',
            'easy-marijuana-age-verify',
            'emav_settings_general'
        );
        register_setting( 'easy-marijuana-age-verify', '_emav_disclaimer', 'esc_attr' );
        // Hook into premium settings if Premium version is active. Function does not exist in free version.
        if ( function_exists( 'emav_premium_settings' ) ) {
            emav_premium_settings();
        }
        do_action( 'emav_register_settings' );
    }
    
    /**
     * Adds default plugin settings
     */
    public function default_settings()
    {
        $defaults = array(
            '_emav_always_verify' => 'disabled',
        );
        $options = wp_parse_args( get_option( 'easy-marijuana-age-verify' ), $defaults );
    }
    
    /**
     * Enqueue the scripts.
     *
     * @param string $page The current admin page.
     *
     * @return void
     * @since 0.2.6
     *
     */
    public function enqueue_scripts( $page )
    {
        // toplevel_page_easy-marijuana-age-verify
        if ( 'toplevel_page_easy-marijuana-age-verify' != $page ) {
            return;
        }
        add_thickbox();
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style(
            'emav-admin-styles',
            plugin_dir_url( __FILE__ ) . 'assets/styles.css',
            array(),
            filemtime( plugin_dir_path( __FILE__ ) . 'assets/styles.css' )
        );
        wp_enqueue_script(
            'emav-admin-scripts',
            plugin_dir_url( __FILE__ ) . 'assets/scripts.js',
            array( 'jquery', 'wp-color-picker' ),
            filemtime( plugin_dir_path( __FILE__ ) . 'assets/scripts.js' )
        );
    }
    
    public function emav_beacon_header_prem( $page )
    {
        if ( 'toplevel_page_easy-marijuana-age-verify' != $page ) {
            return;
        }
        $beacon_html = '<script type="text/javascript">!function(e,t,n){function a(){var e=t.getElementsByTagName("script")[0],n=t.createElement("script");n.type="text/javascript",n.async=!0,n.src="https://beacon-v2.helpscout.net",e.parentNode.insertBefore(n,e)}if(e.Beacon=n=function(t,n,a){e.Beacon.readyQueue.push({method:t,options:n,data:a})},n.readyQueue=[],"complete"===t.readyState)return a();e.attachEvent?e.attachEvent("onload",a):e.addEventListener("load",a,!1)}(window,document,window.Beacon||function(){});';
        $beacon_html .= "window.Beacon('init', '304f587e-8cad-4115-a0c6-96cc9d69a6a2');</script>";
        echo  $beacon_html ;
    }
    
    public function emav_beacon_header_free( $page )
    {
        if ( 'toplevel_page_easy-marijuana-age-verify' != $page ) {
            return;
        }
        $beacon_html = '<script type="text/javascript">!function(e,t,n){function a(){var e=t.getElementsByTagName("script")[0],n=t.createElement("script");n.type="text/javascript",n.async=!0,n.src="https://beacon-v2.helpscout.net",e.parentNode.insertBefore(n,e)}if(e.Beacon=n=function(t,n,a){e.Beacon.readyQueue.push({method:t,options:n,data:a})},n.readyQueue=[],"complete"===t.readyState)return a();e.attachEvent?e.attachEvent("onload",a):e.addEventListener("load",a,!1)}(window,document,window.Beacon||function(){});';
        $beacon_html .= "window.Beacon('init', 'a95e134f-35d9-41a6-b065-9b914940bbf5');</script>";
        echo  $beacon_html ;
    }
    
    /**
     * Add a "restrict" checkbox to individual posts/pages.
     *
     * @return void
     * @since 0.2.6
     *
     */
    public function add_submitbox_checkbox()
    {
        ?>

        <div class="misc-pub-section verify-age">

			<?php 
        wp_nonce_field( 'emav_save_post', 'emav_nonce' );
        ?>

            <input type="checkbox" name="_emav_needs_verify" id="_emav_needs_verify" value="1" <?php 
        checked( 1, get_post_meta( get_the_ID(), '_emav_needs_verify', true ) );
        ?> />
            <label for="_emav_needs_verify" class="selectit">
				<?php 
        esc_html_e( 'Require age verification for this content', 'easy-marijuana-age-verify' );
        ?>
            </label>

        </div><!-- .misc-pub-section -->

	<?php 
    }
    
    /**
     * Save the "restrict" checkbox value.
     *
     * @param int $post_id The current post ID.
     *
     * @return void
     * @since 0.2.6
     *
     */
    public function save_post( $post_id )
    {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        $nonce = ( isset( $_POST['emav_nonce'] ) ? $_POST['emav_nonce'] : '' );
        if ( !wp_verify_nonce( $nonce, 'emav_save_post' ) ) {
            return;
        }
        $needs_verify = ( isset( $_POST['_emav_needs_verify'] ) ? (int) $_POST['_emav_needs_verify'] : 0 );
        update_post_meta( $post_id, '_emav_needs_verify', $needs_verify );
    }
    
    /**
     * Prints small label description
     * @param $string
     *
     * @return string
     */
    public function small( $string ) : string
    {
        return sprintf( '<br><small>%s</small>', $string );
    }

}