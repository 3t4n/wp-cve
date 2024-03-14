<?php
/**
 * Defines the admin core plugin class.
 * 
 * Handles the admin-specific hooks and functions.
 * 
 */
if ( !class_exists( 'Wpmagazine_modules_Lite_Admin' ) ) :
    
    class Wpmagazine_modules_Lite_Admin {
        /**
         * Instance
         *
         * @access private
         * @static
         *
         * @var Wpmagazine_modules_Lite_Admin The single instance of the class.
         */
        private static $_instance = null;

        /**
         * Ensures only one instance of the class is loaded or can be loaded.
         *
         * @access public
         * @static
         *
         * @return Wpmagazine_modules_Lite_Admin An instance of the class.
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Set the plugin name and the plugin version that can be used throughout the plugin.
         * Load the dependencies, define the locale, and set the hooks for the admin area of the site.
         */
        public function __construct() {
            if ( !is_admin() ) {
                return;
            }

            add_action( 'admin_menu', array( $this, 'add_admin_menu_register' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
            add_action( 'wp_ajax_wpmagazine_modules_lite_submit_form', array( $this, 'submit_form_ajax_call' ) );
            add_action( 'admin_init', array( $this, 'review_notice_set_option' ) );
            add_action( 'admin_notices', array( $this, 'review_admin_notice' ) );
            add_action( 'admin_notices', array( $this, 'upgrade_admin_notice' ) );
        }

        /**
         * load scripts.
         */
        public function admin_enqueue_scripts( $hook_suffix ) {
            wp_enqueue_style( 'wpmagazine-modules-lite-admin-notice-style', plugins_url( 'css/admin-notice.css', __FILE__ ), array(), WPMAGAZINE_MODULES_LITE_VERSION, 'all' );

            if ( $hook_suffix !== 'toplevel_page_wpmagazine-modules-lite' ) {
                return;
            }
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_style( 'wpmagazine-modules-lite-admin-style', plugins_url( 'css/admin.css', __FILE__ ), array(), WPMAGAZINE_MODULES_LITE_VERSION, 'all' );
            wp_enqueue_style( 'wpmagazine-modules-lite-icons-style', esc_url( WPMAGAZINE_MODULES_LITE_INCLUDES_URL . '/assets/cvmm-icons/style.css' ), array(), WPMAGAZINE_MODULES_LITE_VERSION, 'all' );

            wp_enqueue_script( 'wpmagazine-modules-lite-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), WPMAGAZINE_MODULES_LITE_VERSION, true );
            
            wp_localize_script( 'wpmagazine-modules-lite-admin-script', 'WpmagazineObject', array(
                'ajax_url'  => admin_url( 'admin-ajax.php' ),
                '_wpnonce'  => wp_create_nonce( 'wpmagazine_admin_nonce' )   
            ));
        }

        /**
         * Add admin page for the wp-magazine-modules.
         */
        public function add_admin_menu_register() {
            $admin_icon = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
            <style type="text/css">.st0{fill:#666666;}</style><g id="Layer_1"></g><g id="Layer_2"><g><rect class="st0" width="27" height="29"/></g><g><rect y="33" class="st0" width="14" height="17"/></g><g><rect x="36" y="33" class="st0" width="14" height="17"/></g><g><rect x="18" y="33" class="st0" width="14" height="17"/></g><path class="st0" d="M32.4,4h16.2C49.4,4,50,3.4,50,2.6V1.4C50,0.6,49.4,0,48.6,0L32.4,0C31.6,0,31,0.6,31,1.4v1.2C31,3.4,31.6,4,32.4,4z"/><path class="st0" d="M32.4,22h16.2c0.8,0,1.4-0.6,1.4-1.4v-1.2c0-0.8-0.6-1.4-1.4-1.4H32.4c-0.8,0-1.4,0.6-1.4,1.4v1.2C31,21.4,31.6,22,32.4,22z"/><path class="st0" d="M32.4,10h16.2c0.8,0,1.4-0.6,1.4-1.4V7.4C50,6.6,49.4,6,48.6,6H32.4C31.6,6,31,6.6,31,7.4v1.2C31,9.4,31.6,10,32.4,10z"/><path class="st0" d="M32.4,16h16.2c0.8,0,1.4-0.6,1.4-1.4v-1.2c0-0.8-0.6-1.4-1.4-1.4H32.4c-0.8,0-1.4,0.6-1.4,1.4v1.2C31,15.4,31.6,16,32.4,16z"/><path class="st0" d="M32.4,28h16.2c0.8,0,1.4-0.6,1.4-1.4v-1.2c0-0.8-0.6-1.4-1.4-1.4H32.4c-0.8,0-1.4,0.6-1.4,1.4v1.2C31,27.4,31.6,28,32.4,28z"/></g></svg>';
            
            add_menu_page(
                'wpmagazine-modules-lite',
                esc_html__( 'WP Magazine Modules Lite', 'wp-magazine-modules-lite' ),
                'manage_options',
                'wpmagazine-modules-lite',
                array( $this, 'admin_menu_callback' ),
                'data:image/svg+xml;base64,' . base64_encode( $admin_icon ),
                20
            );
        }

        /**
         * Callback function for wp-magazine-modules admin page.
         * 
         */
        public function admin_menu_callback() {
        ?>
            <div id="wpmagazine-modules-lite-admin" class="cvmm-admin-block-wrapper cvmm-clearfix">
                <header id="cvmm-main-header" class="cvmm-tab-block-wrapper">
                    <img src="<?php echo esc_url( WPMAGAZINE_MODULES_LITE_INCLUDES_URL . '/assets/images/logo.png' ); ?>" />
                    <div class="admin-main-menu nav-tab-wrapper cvmm-nav-tab-wrapper">
                        <ul>
                        <?php
                            $header_titles = array(
                                "dashboard" => array( "desc" => "Get started!!", "icon" => "cvicon-item cvicon-dashboard" ),
                                "options"   => array( "desc" => "Manage options", "icon" => "cvicon-item cvicon-options" ),
                                "help"      => array( "desc" => "Have an issue?", "icon" => "cvicon-item cvicon-support" ),
                                "review"    => array( "desc" => "Review our product", "icon" => "cvicon-item cvicon-review" )
                            );
                            foreach( $header_titles as $header_title => $header_title_val ) {
                        ?>
                                <li class="nav-tab cvmm-nav-tab <?php echo esc_html( 'cvmm-'.$header_title ); if ( $header_title == 'dashboard' ){ echo esc_html( ' isActive' ); } ?>">
                                    <a href="<?php echo '#cvmm-'.$header_title; ?>"><?php echo str_replace( '-', ' ', $header_title ); ?>
                                        <span class="cvmm-nav-sub-title"><?php echo esc_html( $header_title_val['desc'] ); ?></span>
                                        <i class="<?php echo esc_html( $header_title_val['icon'] ); ?>"></i>
                                    </a>
                                </li>
                        <?php
                            }
                        ?>
                        </ul>
                    </div>
                </header>

                <div id="cvmm-main-content" class="cvmm-content-block-wrapper">
                    <?php
                        foreach( $header_titles as $header_title => $header_title_desc ) {
                            include( plugin_dir_path( __FILE__ ) .'partials/content-'.$header_title.'.php' );
                        }
                    ?>
                </div><!-- #cvmm-main-content -->
                <footer id="cvmm-main-footer" class="cvmm-promo-sidebar">
                    <div class="footer-content cvmm-promo-block">
                        <h2 class="cvmm-admin-title"><?php echo esc_html__( 'Go Premium', 'wp-magazine-modules-lite' ); ?></h2>
                        <div class="cvmm-admin-sub-title"><?php esc_html_e( 'Features', 'wp-magazine-modules-lite' ); ?></div>
                        <ul class="cvmm-footer-list">
                            <li><?php esc_html_e( '10+ total magazine blocks/widget', 'wp-magazine-modules-lite' ); ?></li>
                            <li><?php esc_html_e( '50+ total magazine layouts', 'wp-magazine-modules-lite' ); ?></li>
                            <li><?php esc_html_e( '5 layout variations in each layout block/widget', 'wp-magazine-modules-lite' ); ?></li>
                            <li><?php esc_html_e( 'Supports Custom Post Type', 'wp-magazine-modules-lite' ); ?></li>
                            <li><?php esc_html_e( 'More Attractive Designs', 'wp-magazine-modules-lite' ); ?></li>
                            <li><?php esc_html_e( 'Responsive Design', 'wp-magazine-modules-lite' ); ?></li>
                            <li><?php esc_html_e( 'Animation Effects', 'wp-magazine-modules-lite' ); ?></li>
                            <li><?php esc_html_e( '5 Block Columns', 'wp-magazine-modules-lite' ); ?></li>
                            <li><?php esc_html_e( '5 Block Title Layout', 'wp-magazine-modules-lite' ); ?></li>
                            <li><?php esc_html_e( 'Category Color Options', 'wp-magazine-modules-lite' ); ?></li>
                            <li><?php esc_html_e( 'Color Options', 'wp-magazine-modules-lite' ); ?></li>
                            <li><?php esc_html_e( 'Pagination Settings', 'wp-magazine-modules-lite' ); ?></li>
                            <li><?php esc_html_e( 'Fallback Image Option', 'wp-magazine-modules-lite' ); ?></li>
                            <li><?php esc_html_e( 'Numerous Google Fonts', 'wp-magazine-modules-lite' ); ?></li>
                            <li><?php esc_html_e( 'Advanced Typography Options', 'wp-magazine-modules-lite' ); ?></li>
                            <li><?php esc_html_e( 'Show/Hide meta', 'wp-magazine-modules-lite' ); ?></li>
                            <li><?php esc_html_e( 'Show/Hide before/after icons', 'wp-magazine-modules-lite' ); ?></li>
                            <li><?php esc_html_e( 'Show/Hide content', 'wp-magazine-modules-lite' ); ?></li>
                            <li><?php esc_html_e( 'Features added in every updates ', 'wp-magazine-modules-lite' ); ?></li>
                        </ul>
                        <a href="<?php echo esc_url( 'https://codevibrant.com/wp-plugin/wp-magazine-modules-for-gutenberg-elementor/' ); ?>" target="_blank" class="button button-primary"><?php esc_html_e( 'Upgrade Now', 'wp-magazine-modules-lite' ); ?></a>
                    </div><!-- .footer-content -->
                    <div class="footer-content cvmm-wpall-block">
                        <?php
                            esc_html_e( 'We have completely free online WordPress resources offers genuine and useful content helps to build your WordPress knowledge', 'wp-magazine-modules-lite' );
                        ?>
                        <a href="<?php echo esc_url( 'https://wpallresources.com/' ); ?>" target="_blank"><?php echo esc_url( 'wpallresources.com' ); ?></a>
                    </div><!-- .footer-content -->
                </footer><!-- #cvmm-main-footer -->
            </div> <!-- .cvmm-admin-block-wrapper -->
        <?php
        }

        /**
         * Ajax update category color form value
         * 
         */
        public function submit_form_ajax_call() {
            if ( !wp_verify_nonce( $_POST['_wpnonce'], "wpmagazine_admin_nonce")) {
                wp_die( "No kiddies!!");
            }

            $sanitized_form_datas = array();
            $dynamic_allcss_class = new Wpmagazine_Modules_Lite_Dynamic_AllCss();
            $defaults = $dynamic_allcss_class->get_defaults();
            parse_str( ( $_POST[ 'data' ] ), $form_datas );

            foreach( $form_datas as $key  => $value) {
                $sanitized_form_datas[ $key ]  = sanitize_hex_color( $value );
            }
            $parsed_options = wp_parse_args( $form_datas, $defaults );
            set_theme_mod( "wpmagazine_modules_lite_category_options", $parsed_options );
            $status = true;
            wp_send_json( $status );
            wp_die();
        }

        /**
         * Plugin review admin notice after 15 days of plugin activation.
         */
        public function review_admin_notice() {
            global $current_user;
            $user_id = $current_user->ID;

            $wpmagazine_modules_lite_activated_time = get_option( 'wpmagazine_modules_lite_activated_time' );
            $wpmagazine_modules_lite_ignore_review_notice_partially = get_user_meta( $user_id, 'wpmagazine_modules_lite_ignore_review_notice_partially', true );
            $wpmagazine_modules_lite_ignore_theme_review_notice = get_user_meta( $user_id, 'wpmagazine_modules_lite_ignore_theme_review_notice', true );

            /**
             * if plugin activation time is more than 15 days
             * if plugin review notice is partially ignored and is not 7days.
             * if plugin review is already done.
             * 
             * @return null
             */
            if ( ( $wpmagazine_modules_lite_activated_time > strtotime( '- 15 days' ) ) || ( $wpmagazine_modules_lite_ignore_review_notice_partially > strtotime( '- 7 days' ) ) || $wpmagazine_modules_lite_ignore_theme_review_notice ) {
                return;
            }
        ?>
            <div id="cvmm-plugin-admin-notice" class="notice updated is-dismissible">
                <div class="cvmm-plugin-message">
                    <?php esc_html_e( 'Hey, '.esc_html( $current_user->display_name ).'! Having great experience using WP Magazine Modules Lite? We hope you are happy with everything that the plugin has to offer. If you can spare a minute, please help us leaving a 5-star review on wordpress.org. By spreading love, we continue to develop new amazing features in the future, for free!', 'wp-magazine-modules-lite' ); ?>
                </div>
                <div class="links">
                    <a href="<?php echo esc_url( 'https://wordpress.org/support/plugin/wp-magazine-modules-lite/reviews/#new-post' ); ?>" class="btn button-primary" target="_blank">
                        <span class="dashicons dashicons-thumbs-up"></span>
                        <span><?php esc_html_e( 'Sure', 'wp-magazine-modules-lite' ); ?></span>
                    </a>
                    <a href="<?php echo wp_nonce_url( add_query_arg( 'wpmagazine_modules_lite_ignore_review_notice_partially', true ), 'wpmagazine_modules_lite_nonce' ); ?>" class="btn button-secondary">
                        <span class="dashicons dashicons-calendar"></span>
                        <span><?php esc_html_e( 'Maybe later', 'wp-magazine-modules-lite' ); ?></span>
                    </a>

                    <a href="<?php echo wp_nonce_url( add_query_arg( 'wpmagazine_modules_lite_ignore_theme_review_notice', true ), 'wpmagazine_modules_lite_nonce' ); ?>" class="btn button-secondary">
                        <span class="dashicons dashicons-smiley"></span>
                        <span><?php esc_html_e( 'I already did', 'wp-magazine-modules-lite' ); ?></span>
                    </a>

                    <a href="<?php echo esc_url( 'https://wordpress.org/support/plugin/wp-magazine-modules-lite/' ); ?>" class="btn button-secondary" target="_blank">
                        <span class="dashicons dashicons-edit"></span>
                        <span><?php esc_html_e( 'Get plugin support question?', 'wp-magazine-modules-lite' ); ?></span>
                    </a>
                </div>
            </div>
        <?php
        }

        /**
         * Plugin upgrade to premium notice
         */
        public function upgrade_admin_notice() {
            $wpmagazine_modules_lite_upgrade_premium = get_option( 'wpmagazine_modules_lite_upgrade_premium' );
            if ( $wpmagazine_modules_lite_upgrade_premium > strtotime( '- 7 days' ) ) {
                return;
            }
            ?>
                <div id="cvmm-plugin-admin-notice" class="notice updated is-dismissible">
                    <div class="cvmm-plugin-message">
                        <?php esc_html_e( 'Looking for extending more features in WP Magazine Modules Lite? Unlock more layouts, advanced features with custom post type support and many other options in premium version.', 'wp-magazine-modules-lite' ); ?>
                    </div>
                    <div class="cvmm-plugin-message">
                        <?php esc_html_e( 'Frequent updates available with quick issue handling and get every updates with required features added', 'wp-magazine-modules-lite' ); ?>
                    </div>
                    <div class="links">
                        <a href="<?php echo esc_url( 'https://codevibrant.com/pricing/?product_id=14400' ); ?>" class="btn button-primary" target="_blank">
                            <span class="dashicons dashicons-upload"></span>
                            <span><?php esc_html_e( 'Upgrade To Premium', 'wp-magazine-modules-lite' ); ?></span>
                        </a>
                        <a href="<?php echo wp_nonce_url( add_query_arg( 'wpmagazine_modules_lite_upgrade_premium', true ), 'wpmagazine_modules_lite_nonce' ); ?>" class="btn button-secondary">
                            <span class="dashicons dashicons-no"></span>
                            <span><?php esc_html_e( 'Dismiss this notice', 'wp-magazine-modules-lite' ); ?></span>
                        </a>
                    </div><!-- .links -->
                </div><!-- #cvmm-plugin-admin-notice -->
            <?php
        }

        /**
         * Set plugin admin plugin review notice option
         */
        public function review_notice_set_option() {
            global $current_user;
            $user_id = $current_user->ID;

            if ( isset( $_GET[ 'wpmagazine_modules_lite_upgrade_premium' ] ) && wp_verify_nonce( $_GET['_wpnonce'], 'wpmagazine_modules_lite_nonce' ) ) {
                update_option( 'wpmagazine_modules_lite_upgrade_premium', time() );
            }

            if ( isset( $_GET[ 'wpmagazine_modules_lite_ignore_review_notice_partially' ] ) && wp_verify_nonce( $_GET['_wpnonce'], 'wpmagazine_modules_lite_nonce' ) ) {
                update_user_meta( $user_id, 'wpmagazine_modules_lite_ignore_review_notice_partially', time() );
            }

            if ( isset( $_GET[ 'wpmagazine_modules_lite_ignore_theme_review_notice' ] ) && wp_verify_nonce( $_GET['_wpnonce'], 'wpmagazine_modules_lite_nonce' ) ) {
                update_user_meta( $user_id, 'wpmagazine_modules_lite_ignore_theme_review_notice', true );
            }
        }
    }
    
    Wpmagazine_modules_Lite_Admin::instance();

endif;