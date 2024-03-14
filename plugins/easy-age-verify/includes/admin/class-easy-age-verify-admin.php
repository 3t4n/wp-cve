<?php

/**
 * Define the admin class
 *
 * @since   0.2.6
 *
 * @package Easy_Age_Verify\Admin
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
final class Easy_Age_Verify_Admin
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
        global  $evav_fs ;
        $this->version = Easy_Age_Verify::VERSION;
        /**
         * The settings callbacks.
         */
        require EVAV_PLUGIN_DIR_PATH . 'includes/admin/settings.php';
        // default stock age option
        $optionVerify1 = '_evav_user_age_verify_option';
        if ( get_option( $optionVerify1 ) == '' ) {
            update_option( $optionVerify1, 1 );
        }
        // set disable verification on initial install
        $optionVerify2 = '_evav_always_verify';
        if ( get_option( $optionVerify2 ) == '' ) {
            update_option( $optionVerify2, 'disabled' );
        }
        
        if ( $evav_fs->is_not_paying() ) {
            add_action( 'admin_enqueue_scripts', array( $this, 'eav_beacon_header_free' ) );
            // Add Helpscout Free Beacon code
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
        // Enqueue the script.
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        // evav_load_plugin_textdomain();
        // Only load with post-specific stuff if enabled.
        
        if ( 'content' == get_option( '_evav_require_for' ) ) {
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
            $plugin = plugin_basename( PLUGIN_FILE );
        }
        
        if ( $plugin == $plugin_file ) {
            $settings_link = sprintf( '<a href="%s">%s</a>', $link = esc_url( add_query_arg( 'page', 'easy-age-verify', admin_url( 'admin.php' ) ) ), $menu_text = __( 'Settings', 'easy-age-verify' ) );
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
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'easy-age-verify' ), $this->version );
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
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'easy-age-verify' ), $this->version );
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
            'Easy Age Verify',
            'Easy Age Verify',
            'manage_options',
            'easy-age-verify',
            'evav_settings_page',
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
            'evav_settings_general',
            null,
            'evav_settings_callback_section_general',
            'easy-age-verify'
        );
        // Set to Disabled or Who to verify (not logged in or all)
        add_settings_field(
            '_evav_always_verify',
            __( 'Enable Verification', 'easy-age-verify' ) . ' <span class="dashicons dashicons-info evavoptionshovertip" title="' . __( 'Popup will not show when disabled. Use Testing Mode during setup, then show when it\'s ready.', 'easy-age-verify' ) . '"></span>',
            'evav_settings_callback_always_verify_field',
            'easy-age-verify',
            'evav_settings_general'
        );
        register_setting( 'easy-age-verify', '_evav_always_verify', 'esc_attr' );
        // AJAX Section
        // Set to Disabled or Who to verify (not logged in or all)
        add_settings_field(
            '_evav_settings_ajax',
            __( 'Realtime Settings Check' . ' <span class="dashicons dashicons-info evavoptionshovertip" title="' . __( 'May help caching issues, adds delay to popup.', 'easy-age-verify' ) . '"></span>' . $this->small( '(<a href="https://support.5starplugins.com/article/202-realtime-settings-check" target="_blank">Learn more</a>)' ), 'easy-age-verify' ),
            'evav_settings_callback_ajax_check',
            'easy-age-verify',
            'evav_settings_general'
        );
        register_setting( 'easy-age-verify', '_evav_ajax_check', 'esc_attr' );
        // Choose Adult Type
        add_settings_field(
            '_evav_adult_type',
            __( 'Verify For', 'easy-age-verify' ) . ' <span class="dashicons dashicons-info evavoptionshovertip" title="' . __( 'Select a turnkey setting. Upgrade to Premium to edit text and background.', 'easy-age-verify' ) . '"></span>',
            'evav_settings_callback_adult_type_field',
            'easy-age-verify',
            'evav_settings_general'
        );
        register_setting( 'easy-age-verify', '_evav_adult_type', 'esc_attr' );
        // Option Title
        add_settings_field(
            '_evav_user_age_verify_option_title',
            '',
            function () {
            printf( '<div class="evav-note" style="%s">%s</div>', 'display:block; width: 100%;', __( 'Upgrade to edit button and error message text. Premium unlocks editing of all text for translation.', 'easy-age-verify' ) );
        },
            'easy-age-verify',
            'evav_settings_general'
        );
        register_setting( 'easy-age-verify', '_evav_user_age_verify_option_title', 'esc_attr' );
        // Heading
        add_settings_field(
            '_evav_heading',
            '<label for="_evav_heading">' . __( 'Age Prompt' . ' <span class="dashicons dashicons-info evavoptionshovertip" title="' . __( 'Required question.', 'easy-age-verify' ) . '"></span>' . $this->small( '(max 50 characters)' ), 'easy-age-verify' ) . '</label>',
            'evav_settings_callback_heading_field',
            'easy-age-verify',
            'evav_settings_general'
        );
        register_setting( 'easy-age-verify', '_evav_heading', 'esc_attr' );
        // Disclaimer
        add_settings_field(
            '_evav_disclaimer',
            sprintf( '<label for="evav_disclaimer">%s</label>', __( 'Disclaimer' . ' <span class="dashicons dashicons-info evavoptionshovertip" title="' . __( 'Optional small print takes HTML link.', 'easy-age-verify' ) . '"></span>' . $this->small( '(max 400 characters)' ) ), 'easy-age-verify' ),
            'evav_settings_callback_disclaimer_field',
            'easy-age-verify',
            'evav_settings_general'
        );
        register_setting( 'easy-age-verify', '_evav_disclaimer', 'esc_attr' );
        // Hook into premium settings if Premium version is active. Function does not exist in free version.
        // Call Premium Settings if premium settings are active
        if ( function_exists( 'evav_premium_settings' ) ) {
            evav_premium_settings();
        }
        do_action( 'evav_register_settings' );
    }
    
    /**
     * Adds default plugin settings
     */
    public function default_settings()
    {
        $defaults = array(
            '_evav_always_verify' => 'disabled',
        );
        $options = wp_parse_args( get_option( 'easy-age-verify' ), $defaults );
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
        // toplevel_page_easy-age-verify
        if ( 'toplevel_page_easy-age-verify' != $page ) {
            return;
        }
        add_thickbox();
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style(
            'evav-admin-styles',
            plugin_dir_url( __FILE__ ) . 'assets/styles.css',
            array(),
            filemtime( plugin_dir_path( __FILE__ ) . 'assets/styles.css' )
        );
        wp_enqueue_script(
            'evav-admin-scripts',
            plugin_dir_url( __FILE__ ) . 'assets/scripts.js',
            array( 'jquery', 'wp-color-picker', 'jquery-migrate' ),
            filemtime( plugin_dir_path( __FILE__ ) . 'assets/scripts.js' )
        );
    }
    
    public function eav_beacon_header_prem( $page )
    {
        if ( 'toplevel_page_easy-age-verify' != $page ) {
            return;
        }
        $beacon_html = '<script type="text/javascript">!function(e,t,n){function a(){var e=t.getElementsByTagName("script")[0],n=t.createElement("script");n.type="text/javascript",n.async=!0,n.src="https://beacon-v2.helpscout.net",e.parentNode.insertBefore(n,e)}if(e.Beacon=n=function(t,n,a){e.Beacon.readyQueue.push({method:t,options:n,data:a})},n.readyQueue=[],"complete"===t.readyState)return a();e.attachEvent?e.attachEvent("onload",a):e.addEventListener("load",a,!1)}(window,document,window.Beacon||function(){});';
        $beacon_html .= "window.Beacon('init', '3609f19f-d16d-4d32-9c11-c1bbc18755c7');</script>";
        echo  $beacon_html ;
    }
    
    public function eav_beacon_header_free( $page )
    {
        if ( 'toplevel_page_easy-age-verify' != $page ) {
            return;
        }
        $beacon_html = '<script type="text/javascript">!function(e,t,n){function a(){var e=t.getElementsByTagName("script")[0],n=t.createElement("script");n.type="text/javascript",n.async=!0,n.src="https://beacon-v2.helpscout.net",e.parentNode.insertBefore(n,e)}if(e.Beacon=n=function(t,n,a){e.Beacon.readyQueue.push({method:t,options:n,data:a})},n.readyQueue=[],"complete"===t.readyState)return a();e.attachEvent?e.attachEvent("onload",a):e.addEventListener("load",a,!1)}(window,document,window.Beacon||function(){});';
        $beacon_html .= "window.Beacon('init', '5f0612a9-1eec-4279-b398-c513cebdc6c3');</script>";
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
        wp_nonce_field( 'evav_save_post', 'evav_nonce' );
        ?>

            <input type="checkbox" name="_evav_needs_verify" id="_evav_needs_verify" value="1" <?php 
        checked( 1, get_post_meta( get_the_ID(), '_evav_needs_verify', true ) );
        ?> />
            <label for="_evav_needs_verify" class="selectit">
				<?php 
        esc_html_e( 'Require age verification for this content', 'easy-age-verify' );
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
        $nonce = ( isset( $_POST['evav_nonce'] ) ? $_POST['evav_nonce'] : '' );
        if ( !wp_verify_nonce( $nonce, 'evav_save_post' ) ) {
            return;
        }
        $needs_verify = ( isset( $_POST['_evav_needs_verify'] ) ? (int) $_POST['_evav_needs_verify'] : 0 );
        update_post_meta( $post_id, '_evav_needs_verify', $needs_verify );
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