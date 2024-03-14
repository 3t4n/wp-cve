<?php

/**
 * Plugin Name:       Content Blocks Builder
 * Plugin URI:        https://contentblocksbuilder.com?utm_source=CBB&utm_campaign=CBB+visit+site&utm_medium=link&utm_content=Plugin+URI
 * Description:       Design your website directly on the Gutenberg Block Editor without coding using core blocks and block themes.
 * Requires at least: 6.3
 * Requires PHP:      7.0
 * Version:           2.5.1
 * Author:            CBB Team
 * Author URI:        https://contentblocksbuilder.com?utm_source=CBB&utm_campaign=CBB+visit+site&utm_medium=link&utm_content=Author+URI
 *
 * @package           BoldBlocks
 * @copyright         Copyright(c) 2022, Phi Phan
 *
 */
namespace BoldBlocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( function_exists( __NAMESPACE__ . '\\cbb_fs' ) ) {
    cbb_fs()->set_basename( false, __FILE__ );
    return;
}

// Include Freemius functions.
require_once dirname( __FILE__ ) . '/freemius.php';

if ( !class_exists( ContentBlocksBuilder::class ) ) {
    /**
     * The main class
     */
    class ContentBlocksBuilder
    {
        /**
         * Plugin version
         *
         * @var String
         */
        protected  $version = '2.5.1' ;
        /**
         * Components
         *
         * @var Array
         */
        protected  $components = array() ;
        /**
         * The suffix for scripts
         *
         * @var string
         */
        protected  $script_suffix = '' ;
        /**
         * Plugin instance
         *
         * @var ContentBlocksBuilder
         */
        private static  $instance ;
        /**
         * A dummy constructor
         */
        private function __construct()
        {
        }
        
        /**
         * Initialize the instance.
         *
         * @return ContentBlocksBuilder
         */
        public static function get_instance()
        {
            
            if ( !isset( self::$instance ) ) {
                self::$instance = new ContentBlocksBuilder();
                self::$instance->initialize();
            }
            
            return self::$instance;
        }
        
        /**
         * Kick start function.
         * Define constants, load dependencies
         *
         * @return void
         */
        public function initialize()
        {
            // Setup constants.
            $this->setup_constants();
            // Load dependencies.
            $this->load_dependencies();
            // Register core components.
            $this->register_core_components();
            // Run hooks.
            $this->run();
        }
        
        /**
         * Setup constants
         *
         * @return void
         */
        public function setup_constants()
        {
            $this->define_constant( 'BOLDBLOCKS_CBB', true );
            $this->define_constant( 'BOLDBLOCKS_CBB_ROOT_FILE', __FILE__ );
            $this->define_constant( 'BOLDBLOCKS_CBB_VERSION', $this->version );
            $this->define_constant( 'BOLDBLOCKS_CBB_PATH', trailingslashit( plugin_dir_path( BOLDBLOCKS_CBB_ROOT_FILE ) ) );
            $this->define_constant( 'BOLDBLOCKS_CBB_URL', trailingslashit( plugin_dir_url( BOLDBLOCKS_CBB_ROOT_FILE ) ) );
            $this->define_constant( 'BOLDBLOCKS_CBB_EDITOR_SCRIPTS_HANDLE', 'boldblocks-editor-scripts' );
            $this->define_constant( 'BOLDBLOCKS_CBB_EDITOR_STYLE_HANDLE', 'boldblocks-editor-style' );
            $this->define_constant( 'BOLDBLOCKS_CBB_FRONTEND_STYLE_HANDLE', 'boldblocks-frontend-style' );
        }
        
        /**
         * Load core components
         *
         * @return void
         */
        public function register_core_components()
        {
            // Load & register core components: custom blocks, patterns, variations.
            $this->include_file( 'includes/custom-blocks.php' );
            $this->include_file( 'includes/variations.php' );
            $this->include_file( 'includes/patterns.php' );
            $this->include_file( 'includes/copy-post.php' );
            $this->include_file( 'includes/custom-style.php' );
            $this->include_file( 'includes/meta-revisioning.php' );
            $this->include_file( 'includes/freemius-config.php' );
            $this->include_file( 'includes/settings.php' );
            $this->include_file( 'includes/theme.php' );
            $this->include_file( 'includes/icon-library.php' );
            $this->include_file( 'includes/typography.php' );
            $this->include_file( 'includes/library.php' );
            $this->include_file( 'includes/maintenance.php' );
            $core_components = [
                CustomBlocks::class,
                Variations::class,
                Patterns::class,
                CopyPost::class,
                CustomStyle::class,
                MetaRevisioning::class,
                Settings::class,
                FreemiusConfig::class,
                Theme::class,
                IconLibrary::class,
                Typography::class,
                Library::class,
                Maintenance::class
            ];
            foreach ( $core_components as $component ) {
                $this->register_component( $component );
            }
        }
        
        /**
         * Load dependencies
         *
         * @return void
         */
        public function load_dependencies()
        {
            // Load core component.
            $this->include_file( 'includes/core-component.php' );
            // Load post type helper.
            $this->include_file( 'includes/post-type.php' );
        }
        
        /**
         * Run main hooks
         *
         * @return void
         */
        public function run()
        {
            // Load translations.
            add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );
            // Main hooks.
            add_action( 'init', [ $this, 'init' ], 5 );
            // Enqueue scripts for editor.
            add_action( 'enqueue_block_assets', [ $this, 'enqueue_block_assets' ] );
            // Save version and trigger upgraded hook.
            add_action( 'admin_menu', [ $this, 'version_upgrade' ], 1 );
            // Run all components.
            foreach ( $this->components as $component ) {
                $component->run();
            }
        }
        
        /**
         * Process on init hook
         *
         * @return void
         */
        public function init()
        {
            // Run a hook when the plugin initialized.
            do_action( 'boldblocks/init' );
        }
        
        /**
         * Enqueue editor assets
         *
         * @return void
         */
        public function enqueue_block_assets()
        {
            // Only load in the admin side.
            if ( !is_admin() ) {
                return;
            }
            // Index access file.
            $index_asset = $this->include_file( 'build/index.asset.php' );
            // Scripts.
            wp_enqueue_script(
                BOLDBLOCKS_CBB_EDITOR_SCRIPTS_HANDLE,
                $this->get_file_uri( 'build/index.js' ),
                $index_asset['dependencies'] ?? [],
                $this->get_script_version( $index_asset ),
                true
            );
            // For debuging.
            $this->enqueue_debug_information( BOLDBLOCKS_CBB_EDITOR_SCRIPTS_HANDLE );
            // Styles.
            wp_enqueue_style(
                BOLDBLOCKS_CBB_EDITOR_STYLE_HANDLE,
                $this->get_file_uri( 'build/index.css' ),
                [],
                $this->get_script_version( $index_asset )
            );
        }
        
        /**
         * Save version and trigger an upgrade hook
         *
         * @return void
         */
        public function version_upgrade()
        {
            
            if ( get_option( 'cbb_current_version' ) !== $this->version ) {
                do_action( 'cbb_version_upgraded', get_option( 'cbb_current_version' ), $this->version );
                update_option( 'cbb_current_version', $this->version );
            }
        
        }
        
        /**
         * Load text domain
         *
         * @return void
         */
        public function load_textdomain()
        {
            load_plugin_textdomain( 'content-blocks-builder', false, plugin_basename( realpath( __DIR__ . '/languages' ) ) );
        }
        
        /**
         * Register component
         *
         * @param string $classname The class name of the component.
         * @return void
         */
        public function register_component( $classname )
        {
            $this->components[$classname] = new $classname( $this );
        }
        
        /**
         * Get a component by class name
         *
         * @param string $classname The class name of the component.
         * @return mixed
         */
        public function get_component( $classname )
        {
            return $this->components[$classname] ?? false;
        }
        
        /**
         * Define constant
         *
         * @param string $name The name of the constant.
         * @param mixed  $value The value of the constant.
         * @return void
         */
        public function define_constant( $name, $value )
        {
            if ( !defined( $name ) ) {
                define( $name, $value );
            }
        }
        
        /**
         * Retrn file path for file or folder.
         *
         * @param string $path file path.
         * @return string
         */
        public function get_file_path( $path )
        {
            return BOLDBLOCKS_CBB_PATH . $path;
        }
        
        /**
         * Include file path.
         *
         * @param string $path file path.
         * @return mixed
         */
        public function include_file( $path )
        {
            return include_once $this->get_file_path( $path );
        }
        
        /**
         * Get file uri by file path.
         *
         * @param string $path file path.
         * @return string
         */
        public function get_file_uri( $path )
        {
            return BOLDBLOCKS_CBB_URL . $path;
        }
        
        /**
         * Create version for scripts/styles
         *
         * @param array $asset_file
         * @return string
         */
        public function get_script_version( $asset_file )
        {
            return ( wp_get_environment_type() !== 'production' ? $asset_file['version'] ?? BOLDBLOCKS_CBB_VERSION : BOLDBLOCKS_CBB_VERSION );
        }
        
        /**
         * Get the suffix for the scripts
         *
         * @return string
         */
        public function get_script_suffix()
        {
            return $this->script_suffix;
        }
        
        /**
         * Get the plugin version
         *
         * @return string
         */
        public function get_plugin_version()
        {
            return $this->version;
        }
        
        /**
         * Is Debugging CBB
         *
         * @return boolean
         */
        public function is_debug_mode()
        {
            return defined( 'CBB_DEBUG' ) && CBB_DEBUG || 'development' === wp_get_environment_type();
        }
        
        /**
         * Enqueue debug log information
         *
         * @param string $handle
         * @return void
         */
        public function enqueue_debug_information( $handle )
        {
            wp_add_inline_script( $handle, 'var BBLOG=' . wp_json_encode( [
                'environmentType' => ( $this->is_debug_mode() ? 'development' : wp_get_environment_type() ),
            ] ), 'before' );
        }
    
    }
    /**
     * Kick start
     *
     * @return ContentBlocksBuilder instance
     */
    function boldblocks_cbb_get_instance()
    {
        return ContentBlocksBuilder::get_instance();
    }
    
    // Instantiate.
    boldblocks_cbb_get_instance();
}


if ( !function_exists( __NAMESPACE__ . '\\content_block_builder_activate' ) ) {
    /**
     * Trigger an action when the plugin is activated.
     *
     * @return void
     */
    function content_block_builder_activate()
    {
        do_action( 'content_block_builder_activate' );
    }
    
    register_activation_hook( __FILE__, __NAMESPACE__ . '\\content_block_builder_activate' );
}
