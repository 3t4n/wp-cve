<?php

/**
 * Initialize Plugin
 *
 * @package Copy the Code
 * @since 1.0.0
 */
use  CopyTheCode\Elementor\Blocks ;

if ( !class_exists( 'Copy_The_Code' ) ) {
    /**
     * Copy the Code
     *
     * @since 1.0.0
     */
    class Copy_The_Code
    {
        /**
         * Instance
         *
         * @access private
         * @var object Class Instance.
         * @since 1.0.0
         */
        private static  $instance ;
        /**
         * Initiator
         *
         * @since 1.0.0
         * @return object initialized object of class.
         */
        public static function get_instance()
        {
            if ( !isset( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }
        
        /**
         * Constructor
         */
        public function __construct()
        {
            require_once COPY_THE_CODE_DIR . 'classes/class-copy-the-code-update.php';
            require_once COPY_THE_CODE_DIR . 'classes/class-copy-the-code-page.php';
            require_once COPY_THE_CODE_DIR . 'classes/class-copy-the-code-dashboard.php';
            require_once COPY_THE_CODE_DIR . 'classes/class-copy-the-code-shortcode.php';
            require_once COPY_THE_CODE_DIR . 'classes/opt-in/class-copy-the-code-opt-in.php';
            require_once COPY_THE_CODE_DIR . 'classes/elementor/class-blocks.php';
            require_once COPY_THE_CODE_DIR . 'classes/class-helpers.php';
            new Blocks();
            $this->create_cron_jobs();
            if ( $this->is_request( 'cron' ) ) {
                require_once COPY_THE_CODE_DIR . 'classes/class-copy-the-code-analytics.php';
            }
            add_action( 'plugins_loaded', array( $this, 'localization_setup' ), 9 );
        }
        
        /**
         * Create cron jobs (clear them first).
         */
        private function create_cron_jobs()
        {
            wp_clear_scheduled_hook( 'copy_the_code_tracker_send_event' );
            wp_schedule_event( time() + 10, apply_filters( 'copy_the_code_tracker_event_recurrence', 'weekly' ), 'copy_the_code_tracker_send_event' );
        }
        
        /**
         * What type of request is this?
         *
         * @param  string $type admin, ajax, cron or frontend.
         * @return bool
         */
        private function is_request( $type )
        {
            switch ( $type ) {
                case 'admin':
                    return is_admin();
                case 'ajax':
                    return defined( 'DOING_AJAX' );
                case 'cron':
                    return defined( 'DOING_CRON' );
            }
        }
        
        /**
         * Initialize plugin for localization.
         *
         * Note: the first-loaded translation file overrides any following ones if the same translation is present.
         *
         * Locales found in:
         *     - WP_LANG_DIR/copy-the-code/copy-the-code-LOCALE.mo
         *     - WP_LANG_DIR/plugins/copy-the-code-LOCALE.mo
         */
        public function localization_setup()
        {
            $locale = ( is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale() );
            $locale = apply_filters( 'plugin_locale', $locale, 'copy-the-code' );
            // phpcs:ignore
            unload_textdomain( 'copy-the-code' );
            if ( false === load_textdomain( 'copy-the-code', WP_LANG_DIR . '/plugins/copy-the-code-' . $locale . '.mo' ) ) {
                load_textdomain( 'copy-the-code', WP_LANG_DIR . '/copy-the-code/copy-the-code-' . $locale . '.mo' );
            }
            load_plugin_textdomain( 'copy-the-code', false, COPY_THE_CODE_DIR . 'languages/' );
        }
    
    }
    /**
     * Kicking this off by calling 'get_instance()' method
     */
    Copy_The_Code::get_instance();
}
