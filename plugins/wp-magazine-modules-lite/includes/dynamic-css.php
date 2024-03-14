<?php
/**
 * Block dynamic css.
 * 
 * @package WP Magazine Modules Lite
 * @since 1.0.0
 * 
 */
if ( !class_exists( 'Wpmagazine_Modules_Lite_Dynamic_Css' ) ) :

    class Wpmagazine_Modules_Lite_Dynamic_Css {
        
        /**
         * Instance
         *
         * @access private
         * @static
         *
         * @var Wpmagazine_Modules_Lite_Dynamic_Css The single instance of the class.
         */
        private static $_instance = null;

        /**
         * Ensures only one instance of the class is loaded or can be loaded.
         *
         * @access public
         * @static
         *
         * @return Wpmagazine_Modules_Lite_Dynamic_Css An instance of the class.
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        
        /**
         * Run hooks necessary for dynamic css.
         *
         * @since    1.0.0
         * @access   public
         *
         * @return void
         */
        public function __construct() {
            add_action( 'wp_enqueue_scripts', array( $this, "save_dynamic_css"  ) );
        }

        /**
         * Store dynamic css to upload directory
         * 
         */
        public function save_dynamic_css() {
            global $post;
            if ( !$post ) {
                return;
            }
            $post_id = $post->ID;
            if ( !has_blocks( $post_id ) ) {
                return;
            }
            $post_css = '';
            $cv_blocks = parse_blocks( $post->post_content);
            foreach( $cv_blocks as $cv_block ) {
                if ( isset( $cv_block['attrs']['blockDynamicCss'] ) ) {
                    $post_css .= apply_filters( 'wpmagazine_modules_lite_block_dynamic_css', esc_html( $cv_block['attrs']['blockDynamicCss'] ) );
                }
            }
            if ( empty( $post_css ) ) {
                return;
            }
            global $wp_filesystem;
            if (!$wp_filesystem) {
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
            }
            $upload_dir = wp_upload_dir();
            $dir = trailingslashit( $upload_dir['basedir'] ) . 'wpmagazine-modules/';

            WP_Filesystem();
            if ( !$wp_filesystem->is_dir( $dir )) {
                $wp_filesystem->mkdir( $dir );
            }
            if ( $wp_filesystem->put_contents( $dir . 'p-'.$post->ID.'.css', $post_css, 0644 ) ) {
                wp_enqueue_style( 'wpmagazine-modules-lite-post-' . $post->ID, $upload_dir["baseurl"] . '/wpmagazine-modules/p-'.$post->ID.'.css', array(), WPMAGAZINE_MODULES_LITE_VERSION, 'all' );
            }
        }
    }

    new Wpmagazine_Modules_Lite_Dynamic_Css();

endif;