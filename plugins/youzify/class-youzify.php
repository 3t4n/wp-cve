<?php

if ( ! class_exists( 'Youzify' ) ) :

/**
 * Main Youzify Class.
 */
class Youzify {

    /**
     * Init Vars
     */
    private static $instance;
    public $reviews;

    /**
     * Main Youzify Instance.
     */
    public static function instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Youzify ) ) {

            self::$instance = new Youzify;

            // Setup Constants.
            self::$instance->setup_constants();

            // Add Social Login Rewrite Role.
            add_action( 'init', array( self::$instance, 'add_rewrite_rule' ) );

            // Add Social Login Query Var.
            add_filter( 'query_vars', array( self::$instance, 'set_query_varaible' ) );

            // Init Plugins Files.
            add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
            add_action( 'init', array( self::$instance, 'init' ), 5 );
            add_action( 'bp_init', array( self::$instance, 'buddypress_init' ) );
            add_action( 'bbp_init', array( self::$instance, 'bbpress_init' ) );
            add_action( 'woocommerce_init', array( self::$instance, 'woocommerce_init' ) );

            // Include General Functions
            include YOUZIFY_CORE . 'functions/general/youzify-general-functions.php';
            include YOUZIFY_CORE . 'functions/general/youzify-account-functions.php';
            include YOUZIFY_CORE . 'functions/general/youzify-profile-functions.php';
            include YOUZIFY_CORE . 'functions/general/youzify-admin-functions.php';
            include YOUZIFY_CORE . 'functions/general/youzify-scripts-functions.php';
            include YOUZIFY_CORE . 'functions/general/youzify-wall-functions.php';
            include YOUZIFY_CORE . 'woocommerce/youzify-woocommerce-functions.php';
            include YOUZIFY_CORE . 'functions/youzify-export-functions.php';

            // Include Classes.
            include YOUZIFY_CORE . 'class-youzify-styling.php';
            include YOUZIFY_CORE . 'class-youzify-media.php';

            if ( wp_doing_ajax() || ! is_admin() ) {

                include YOUZIFY_CORE . 'functions/youzify-general-functions.php';
                include YOUZIFY_CORE . 'functions/youzify-scripts-functions.php';
                include YOUZIFY_CORE . 'functions/youzify-xprofile-functions.php';
                include YOUZIFY_CORE . 'functions/youzify-profile-functions.php';

                self::$instance->includes();

                if ( wp_doing_ajax() ) {
                    include YOUZIFY_CORE . 'class-youzify-ajax.php';
                }

            }

            // Init Admin
            if ( is_admin() && ! class_exists( 'Youzify_Admin' ) ) {
                include YOUZIFY_PATH . 'includes/admin/class-youzify-admin.php';
            }

            // Include Membership System.
            if ( youzify_is_membership_system_active() ) {
                include YOUZIFY_CORE . 'class-youzify-membership.php';
            }

            // Setup Globals.
            self::$instance->globals();

            // Setup Actions.
            self::$instance->setup_actions();

        }

        return self::$instance;
    }

    // Include Files.
    function init() {

        // Include Notifications Files.
        if ( bp_is_active( 'notifications' ) ) {
            require YOUZIFY_CORE . 'functions/youzify-notifications-functions.php';
        }

    }

    /**
     * Buddypress Init
     **/
    function buddypress_init() {

        include YOUZIFY_CORE . 'class-youzify-tabs.php';
        include YOUZIFY_CORE . 'class-youzify-fields.php';
        include YOUZIFY_CORE . 'class-youzify-attachments.php';

        if ( youzify_is_bpfollowers_active() ) {
            require YOUZIFY_CORE . 'functions/youzify-buddypress-followers-integration.php';
        }

        //learndash integration
        // require YOUZIFY_CORE . 'class-youzify-learndash-integration.php';

        //tutor integration
        require YOUZIFY_CORE . 'class-youzify-tutor-integration.php';

        $doing_ajax = wp_doing_ajax();

        // if ( is_buddypress() || $doing_ajax ) {

            // Init Groups
            // if ( bp_is_groups_component() ) {

                include YOUZIFY_CORE . 'class-youzify-header.php';
                // require_once YOUZIFY_CORE . 'class-youzify-header.php';
                require_once YOUZIFY_CORE . 'class-youzify-groups.php';

                // if ( bp_is_group_activity() ) {
                    // $this->include_activity_files();
                // }

            // }

            // if ( bp_is_activity_component() || $doing_ajax ) {
                $this->include_activity_files();
            // }

            // if ( bp_is_user() ) {

                include YOUZIFY_CORE . 'class-youzify-widgets.php';

                // Account Functions.
                // if ( youzify_is_account_page() ) {
                    include YOUZIFY_CORE . 'class-youzify-account.php';
                // } else {
                    include YOUZIFY_CORE . 'functions/youzify-navbar-functions.php';
                    include YOUZIFY_CORE . 'class-youzify-profile.php';
                    include YOUZIFY_CORE . 'class-youzify-author.php';
                    // include YOUZIFY_CORE . 'class-youzify-header.php';
                // }

            // }

           include YOUZIFY_CORE . 'class-youzify-user.php';

            // if ( bp_is_messages_component() ) {
                require_once YOUZIFY_CORE . 'class-youzify-messages.php';
            // }

            if ( youzify_option( 'youzify_lazy_load', 'on' ) == 'on' ) {
                require YOUZIFY_CORE . 'functions/youzify-lazy-loading-functions.php';
            }

        // } else {

            if ( is_404() ) {
                require_once YOUZIFY_CORE . 'functions/youzify-404profile-functions.php';
            }

        // }

    }

    /**
     * bbPress Init
     */
    function bbpress_init() {

        if ( youzify_is_bbpress_active() ) {
            require_once YOUZIFY_CORE . 'functions/youzify-bbpress-functions.php';
        }

    }

    /**
     * WooCommerce Init
     */
    function woocommerce_init() {

        if ( youzify_is_woocommerce_active() ) {

            // Include Functions
            require YOUZIFY_CORE . 'functions/youzify-woocommerce-functions.php';

            // Init Actions.
            add_action( 'bp_init', array( $this, 'is_cart_page' ) );

            // Include WooCommerce Files.
            require YOUZIFY_CORE . 'woocommerce/class-youzify-wc-templates.php';
            require YOUZIFY_CORE . 'woocommerce/class-youzify-wc-redirects.php';
            require YOUZIFY_CORE . 'woocommerce/class-youzify-woocommerce.php';
            require YOUZIFY_CORE . 'woocommerce/class-youzify-wc-activity.php';

        }

    }

    /**
     * Set "Is Cart Page".
     */
    function is_cart_page() {

        if ( is_user_logged_in() && ! defined( 'WOOCOMMERCE_CART' ) && youzify_is_woocommerce_tab( 'cart' ) && youzify_wc_is_sub_tab_exist( 'cart' ) ) {
            define( 'WOOCOMMERCE_CART', true );
        }

    }

    /**
     * Include Activity.
     */
    function include_activity_files() {
        require_once YOUZIFY_CORE . 'functions/wall/youzify-wall-general-functions.php';
        require_once YOUZIFY_CORE . 'class-youzify-wall.php';
    }

    /**
     * Setup plugin constants.
     */
    private function setup_constants() {

        // Templates Path.
        define( 'YOUZIFY_TEMPLATE', YOUZIFY_PATH . 'includes/public/templates/' );

        // Public & Admin Core Path's
        define( 'YOUZIFY_CORE', YOUZIFY_PATH. 'includes/public/core/' );
        define( 'YOUZIFY_ADMIN_CORE', YOUZIFY_PATH . 'includes/admin/core/' );

        // Assets.
        define( 'YOUZIFY_ASSETS', plugin_dir_url( __FILE__ ) . 'includes/public/assets/' );
        define( 'YOUZIFY_ADMIN_ASSETS', plugin_dir_url( __FILE__ ) . 'includes/admin/assets/' );

        
        // Define Buddypress Avatars Dimensions.
        if ( ! defined( 'BP_AVATAR_THUMB_WIDTH' ) ) {
            define( 'BP_AVATAR_THUMB_WIDTH', 50 );
        }

        if ( ! defined( 'BP_AVATAR_THUMB_HEIGHT' ) ) {
            define( 'BP_AVATAR_THUMB_HEIGHT', 50 );
        }

        if ( ! defined( 'BP_AVATAR_FULL_WIDTH' ) ) {
            define( 'BP_AVATAR_FULL_WIDTH', 150 );
        }

        if ( ! defined( 'BP_AVATAR_FULL_HEIGHT' ) ) {
            define( 'BP_AVATAR_FULL_HEIGHT', 150 );
        }

    }

    /**
     * Load Youzify Text Domain!
     */
    public function load_textdomain() {

        $domain = 'youzify';
        $mofile_custom = trailingslashit( WP_LANG_DIR ) . sprintf( '%s-%s.mo', $domain, get_locale() );

        if ( is_readable( $mofile_custom ) ) {
            return load_textdomain( $domain, $mofile_custom );
        } else {
            return load_plugin_textdomain( $domain, FALSE, dirname( YOUZIFY_BASENAME ) . '/languages/' );
        }
    }

    /**
     * Add Social Login Rewrite Role.
     */
    function add_rewrite_rule() {
        // CHANGE THIS LATER
        // add_rewrite_rule( '^yz-auth/([^/]+)/([^/]+)/?', 'index.php?yz-authentication=$matches[1]&yz-provider=$matches[2]','top' );
        add_rewrite_rule( '^youzify-auth/([^/]+)/([^/]+)/?', 'index.php?youzify-authentication=$matches[1]&youzify-provider=$matches[2]','top' );
    }

    /**
     * Add Social Login Query Var.
     */
    function set_query_varaible( $query_vars ) {
        $query_vars[] = 'youzify-authentication';
        $query_vars[] = 'youzify-provider';
        // CHANGE THIS LATER
        $query_vars[] = 'yz-authentication';
        $query_vars[] = 'yz-provider';
        return $query_vars;
    }

    /**
     * Include required files.
     */
    private function includes() {

        // Youzify General Functions.
        require YOUZIFY_CORE . 'functions/youzify-buddypress-functions.php';
        require YOUZIFY_CORE . 'functions/youzify-groups-functions.php';
        require YOUZIFY_CORE . 'functions/youzify-user-functions.php';
        require YOUZIFY_CORE . 'functions/youzify-messages-functions.php';
        require YOUZIFY_CORE . 'functions/youzify-mailchimp-functions.php';
        require YOUZIFY_CORE . 'functions/youzify-mailster-functions.php';
        require YOUZIFY_CORE . 'functions/youzify-account-verification-functions.php';
        require YOUZIFY_CORE . 'functions/youzify-authentication-functions.php';
        require YOUZIFY_CORE . 'functions/youzify-member-types-functions.php';

        if ( youzify_is_mycred_active() ) {
            require YOUZIFY_CORE . 'mycred/youzify-mycred-functions.php';
        }
        

        /*if ( youzify_is_gamipress_active() ) {
            require YOUZIFY_CORE . 'gamipress/youzify-gamipress-functions.php';
        }*/

        // Directory Functions.
        require YOUZIFY_CORE . 'functions/directories/youzify-members-directory-functions.php';
        require YOUZIFY_CORE . 'functions/directories/youzify-groups-directory-functions.php';
        require YOUZIFY_CORE . 'functions/directories/youzify-blogs-directory-functions.php';

        // Integrations
        if ( defined( 'RTMEDIA_VERSION' ) ) {
            require YOUZIFY_CORE . 'functions/youzify-rtmedia-functions.php';
        }

    }

    /**
     * Youzify Global Variables .
     */
    private function globals() {

        global $wpdb, $Youzify_upload_url, $Youzify_upload_dir, $Youzify_bookmark_table, $Youzify_reviews_table, $Youzify_upload_folder, $Youzify_media_table, $Youzify_albums_table;

        // Get Uploads Directory Path.
        $upload_dir = wp_upload_dir();

        // Get Uploads Directory.
        $Youzify_upload_folder = apply_filters( 'youzify_upload_folder', 'youzify' );
        $Youzify_upload_url = apply_filters( 'youzify_upload_url', $upload_dir['baseurl'] . '/'. $Youzify_upload_folder . '/', $upload_dir['baseurl'] );
        $Youzify_upload_dir = apply_filters( 'youzify_upload_dir', $upload_dir['basedir'] . '/' . $Youzify_upload_folder  . '/' , $upload_dir['basedir'] );

        // Get Table Names.
        $Youzify_bookmark_table = $wpdb->prefix . 'youzify_bookmarks';
        $Youzify_reviews_table = $wpdb->prefix . 'youzify_reviews';
        $Youzify_media_table = $wpdb->prefix . 'youzify_media';
        $Youzify_albums_table = $wpdb->prefix . 'youzify_albums';

    }

    /**
     * Set up the default hooks and actions.
     */
    private function setup_actions() {

        /**
         * Fires after the setup of all BuddyPress actions.
         *
         * Includes bbp-core-hooks.php.
         *
         * @since 1.7.0
         *
         * @param BuddyPress $this. Current BuddyPress instance. Passed by reference.
         */
        do_action_ref_array( 'youzify_after_setup_actions', array( &$this ) );
    }

}

endif;