<?php
namespace ACFWF\Models\Objects;

/**
 * Vite app factory.
 *
 * @since 4.5.8
 */
class Vite_App {

    /**
     * Holds the app script handle.
     *
     * @since 4.5.8
     * @var string The script handle.
     */
    protected $script_handle;

    /**
     * Holds the base URL.
     *
     * @since 4.5.8
     * @var string The plugin dir base URL.
     */
    protected $base_url;

    /**
     * Holds whether we are in development mode or production.
     *
     * @since 4.5.8
     * @var bool Whether to enqueue the app in hot module replacement context or the current build script.
     */
    protected $is_hmr;

    /**
     * Holds the file path to the script.
     *
     * @since 4.5.8
     * @var string The script source URL.
     */
    protected $entry_file_path;

    /**
     * Holds the array of script dependencies.
     *
     * @since 4.5.8
     * @var string[] Array of script handle dependencies.
     */
    protected $dependencies;

    /**
     * Holds the localization data for the script.
     *
     * @since 4.5.8
     * @var array Array of script localizations.
     */
    protected $l10n;

    /**
     * Holds the script manifest data.
     *
     * @since 4.5.8
     * @var array The manifest data.
     */
    protected $manifest;

    /**
     * Holds the script stylesheet dependencies.
     *
     * @since 4.5.8
     * @var array Array of style handle dependencies.
     */
    protected $style_dependencies;

    /**
     * Holds the environment variables.
     *
     * @since 4.5.8
     * @var array Array of local environment variables from plugin .env file.
     */
    protected $env;

    /**
     * Holds a boolean value whether the app to load is a regular app or a Gutenberg block.
     *
     * @var bool
     */
    protected $is_gutenberg;

    /**
     * Holds a boolean value whether to only register the scripts and styles.
     *
     * @var bool
     */
    protected $is_register;

    /**
     * Setups Vue app script files. After instantiation, call the enqueue() method to enqueue the script.
     *
     * @param string $script_handle      The script handle to use for the app.
     * @param string $entry_file_path    The path for the main/index script relative from plugin root
     *                                   directory. E.g.
     *                                   <strong>"src/apps/admin/settings/index.tsx"</strong>.
     * @param array  $dependencies       The script dependencies.
     * @param array  $style_dependencies The style dependencies.
     * @param array  $l10n               The script localization.
     *
     * @since 4.5.8
     */
    public function __construct(
        $script_handle,
        $entry_file_path,
        $dependencies = array(),
        $style_dependencies = array(),
        $l10n = array()
    ) {

        /***************************************************************************
         * Set script handle
         ***************************************************************************
         *
         * The script handle that is used to identify the script in WordPress.
         */
        $this->script_handle = $script_handle;

        /***************************************************************************
         * Hot Module Replacement
         ***************************************************************************
         *
         * Whether to enqueue the app in hot module replacement context or the
         * current build script.
         */
        $this->is_hmr = defined( 'HMR_DEV' ) && 'acfwf' === HMR_DEV;

        /***************************************************************************
         * Check if Vue App is a Gutenberg app
         ***************************************************************************
         *
         * We check if the entry file path contains the "gutenberg" directory. If
         * it does, we assume that the app is a Gutenberg block.
         */
        $this->is_gutenberg = str_contains( $entry_file_path, '/gutenberg/' );

        /***************************************************************************
         * Standard HTTP protocol
         ***************************************************************************
         *
         * We determine if the current request is using the HTTPS protocol or not.
         */
        $protocol = is_ssl() ? 'https:' : 'http:';

        /***************************************************************************
         * App script host
         ***************************************************************************
         *
         * The host for the app script. This is usually the same as the site URL.
         * However, if the app is being served in hot module replacement context,
         * then the host is the HMR server host which is usually localhost but can
         * be change in .env file with VITE_DEV_SERVER_HOST key.
         *
         * This is only ever used in development mode. In production mode, the
         * host is always the same as the site URL.
         */
        $host = 'localhost';

        /***************************************************************************
         * App script port
         ***************************************************************************
         *
         * The port for the app script. Defaults to port 3000. It's generally not
         * recommended to change this port. However, if you do, you can change it
         * in .env file with VITE_DEV_SERVER_PORT key.
         */
        $port = 3000;
        if ( $this->is_hmr ) {
            $this->parse_env();
            $protocol = ! empty( $this->env['VITE_DEV_SERVER_HTTPS_KEY'] ) && $this->env['VITE_DEV_SERVER_HTTPS_CERT']
                ? 'https:'
                : 'http:';
            $host     = $this->env['VITE_DEV_SERVER_HOST'] ?? $host;
            $port     = $this->env['VITE_DEV_SERVER_PORT'] ?? $port;
        }
        $this->base_url = $this->is_hmr
            ? "$protocol//$host:$port/"
            : plugins_url( 'dist/', ACFWF()->Plugin_Constants->MAIN_PLUGIN_FILE_PATH );

        /***************************************************************************
         * App script entry file path
         ***************************************************************************
         *
         * The entry file path for the app script. This is usually the main/index
         * script for the app. This is relative from the plugin root directory.
         */
        $this->entry_file_path = $entry_file_path;

        /***************************************************************************
         * App script dependencies
         ***************************************************************************
         *
         * We merge default Gutenberg script dependencies with the passed
         * dependencies. This is only done if the app is a Gutenberg block.
         * Otherwise, we just use the passed dependencies.
         */
        $this->dependencies = is_admin() && $this->is_gutenberg
            ? array_merge(
                $dependencies,
                array(
                    'wp-blocks',
                    'wp-components',
                    'wp-element',
                    'wp-i18n',
                    'wp-url',
                    'wp-data',
                    'wp-plugins',
                    'wp-edit-post',
                    'wp-hooks',
                )
            )
            : $dependencies;

        /***************************************************************************
         * App script localization
         ***************************************************************************
         *
         * The script localization. This is usually the data that is required by the
         * app to run.
         */
        $this->l10n = $l10n;

        /**************************************************************************
         * App style dependencies
         ***************************************************************************
         *
         * If the app has any style dependencies which the app style depends on.
         */
        $this->style_dependencies = is_admin() && $this->is_gutenberg
            ? array_merge(
                $style_dependencies,
                array(
                    'wp-edit-blocks',
                )
            )
            : $style_dependencies;
    }

    /**
     * Parse local plugin .env file.
     *
     * @since 4.5.8
     * @return void
     */
    protected function parse_env() {

        /**************************************************************************
         * Check environment variables
         ***************************************************************************
         *
         * Let's check if we can find environment variables from a `.env` file
         */
        if ( ! file_exists( ACFWF()->Plugin_Constants->PLUGIN_DIR_PATH . '.env' ) ) {
            return;
        }

        //phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
        $env = file_get_contents( ACFWF()->Plugin_Constants->PLUGIN_DIR_PATH . '.env' );
        if ( $env ) {
            $env = preg_split( '/\\r\\n|\\r|\\n/', $env );
            $env = is_array( $env ) ? array_filter( $env ) : array();
            foreach ( $env as $line ) {
                $line = explode( '=', $line );
                if ( 2 === count( $line ) ) {
                    $this->env[ $line[0] ] = $line[1];
                }
            }
        }
    }

    /**
     * Enqueue scripts. Call this method to add the action hooks.
     *
     * @since 4.5.8
     * @return void
     */
    public function enqueue() {

        /**************************************************************************
         * Parse the production manifest file
         ***************************************************************************
         *
         * Here we parse the production manifest file which contains the hashed
         * file names for the production build. This is only used in production
         * mode and bails out immediately if the app is being served in hot module
         * replacement context.
         */
        $this->parse_manifest_file();

        $this->enqueue_scripts();
        add_action( 'wp_head', array( $this, 'preload_imports' ), 20 );
    }

    /**
     * Register scripts. Call this method to add the action hooks.
     *
     * @since 4.5.8
     * @return void
     */
    public function register() {
        $this->is_register = true;
        $this->enqueue();
    }

    /**
     * Parse the manifest file.
     *
     * @since 4.5.8
     * @return void
     */
    protected function parse_manifest_file() {

        /**************************************************************************
         * Check if we are in hot module replacement context
         ***************************************************************************
         *
         * We check if the app is being served in hot module replacement context and
         * bail out immediately if it is.
         */
        if ( $this->is_hmr ) {
            return;
        }

        /**************************************************************************
         * Parse the manifest file
         ***************************************************************************
         *
         * In production mode, the manifest file should exist as it is required for
         * the production build script to load properly. If it doesn't exist, then
         * we write to error log file if WP_DEBUG is true.
         */
        $manifest_path = ACFWF()->Plugin_Constants->PLUGIN_DIR_PATH . 'dist/manifest.json';
        if ( file_exists( $manifest_path ) ) {
            //phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
            $this->manifest = json_decode( file_get_contents( $manifest_path ), true );
        } else {
            _doing_it_wrong(
                __METHOD__,
                esc_html__(
                    'Manifest file not found. Did you run the build script from package.json file?',
                    'advanced-coupons-for-woocommerce-free'
                ),
                '3.0'
            );
        }
    }

    /**
     * Enqueue imports from entry file
     *
     * @since 4.5.8
     * @return void
     */
    public function preload_imports() {

        if ( $this->is_hmr ) {
            return;
        }

        /**************************************************************************
         * Load the preload imports
         ***************************************************************************
         *
         * We load the preload imports referenced from the manifest file.
         */
        $imports = $this->manifest[ $this->entry_file_path ]['imports'] ?? null;
        if ( ! empty( $imports ) ) {
            foreach ( $imports as $import ) {
                $file_url = $this->base_url . "{$this->manifest[$import]['file']}";

                printf( '<link rel="modulepreload" href="%s" />', esc_url( $file_url ) );

                /***************************************************************************
                 * Enqueue styles of directly imported components
                 ***************************************************************************
                 *
                 * We manually enqueue the styles of the components that (were generated
                 * from their `<style>` tag) are directly imported into another component.
                 * This is because the styles are not automatically enqueued/loaded at
                 * runtime unlike the dynamically imported components.
                 */
                $import_styles = $this->manifest[ $import ]['css'] ?? null;
                if ( ! empty( $import_styles ) ) {
                    foreach ( $import_styles as $import_style ) {
                        $sanitized_key = sanitize_title_with_dashes( basename( $import_style ) );
                        $css_url       = $this->base_url . $import_style;
                        wp_enqueue_style( "$this->script_handle-import-$sanitized_key", $css_url, array(), filemtime( ACFWF()->Plugin_Constants->MAIN_PLUGIN_FILE_PATH ) );
                    }
                }
            }
        }
    }

    /**
     * Enqueue scripts and styles including dependencies.
     *
     * @param string|null $hook_suffix The current admin page hook suffix.
     *
     * @since 4.5.8
     * @return void
     */
    public function enqueue_scripts( $hook_suffix = null ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found

        /**************************************************************************
         * Enqueue scripts and styles
         ***************************************************************************
         *
         * Enqueue scripts and styles including dependencies.
         */
        add_filter( 'script_loader_tag', array( $this, 'add_script_tag_attributes' ), 10, 2 );
        add_filter( 'style_loader_tag', array( $this, 'add_style_tag_attributes' ), 10, 3 );

        $this->enqueue_hmr_vite_client();

        $this->enqueue_main_script();
    }

    /**
     * Enqueues Vite client for HMR
     *
     * @since 4.5.8
     * @return void
     */
    protected function enqueue_hmr_vite_client() {

        if ( ! $this->is_hmr ) {
            return;
        }

        /**************************************************************************
         * Enqueue hot module replacement Vite client
         ***************************************************************************
         */
        wp_enqueue_script(
            "$this->script_handle-vite-client",
            "$this->base_url@vite/client",
            array(),
            filemtime( ACFWF()->Plugin_Constants->MAIN_PLUGIN_FILE_PATH ),
            false
        );

        // Set vite plugin react preamble installed flag as true to enable HMR.
        wp_add_inline_script(
            "$this->script_handle-vite-client",
            'window.__vite_plugin_react_preamble_installed__ = true',
            'before'
        );
    }

    /**
     * Enqueues main/index script
     *
     * @since 4.5.8
     * @return void
     */
    protected function enqueue_main_script() {

        /**************************************************************************
         * Get script version
         ***************************************************************************
         *
         * We use the filemtime of the file as the version number in production mode
         * and the current time in development mode.
         */
        $script_version = $this->is_hmr
            ? time()
            : filemtime(
                ACFWF()->Plugin_Constants->PLUGIN_DIR_PATH .
                "dist/{$this->manifest[ $this->entry_file_path ]['file']}"
            );

        /**************************************************************************
         * Full url to main app script file
         ***************************************************************************
         *
         * We decide whether to use the development or production version of the
         * script file based on the current environment.
         */
        $entry_file_url = $this->is_hmr
            ? $this->base_url . $this->entry_file_path
            : $this->base_url . $this->manifest[ $this->entry_file_path ]['file'];

        /**************************************************************************
         * Enqueue main app script
         ***************************************************************************
         *
         * Actually enqueue/register the main app script.
         */
        if ( $this->is_register ) {
            wp_register_script(
                $this->script_handle,
                $entry_file_url,
                $this->dependencies,
                $script_version,
                true
            );
        } else {
            wp_enqueue_script(
                $this->script_handle,
                $entry_file_url,
                $this->dependencies,
                $script_version,
                true
            );
        }

        /**************************************************************************
         * Localize app script
         ***************************************************************************
         *
         * Add localized data to the main app script.
         */
        wp_localize_script(
            $this->script_handle,
            'acfwfObj',
            array_merge(
                array(
                    'pluginDirUrl' => plugins_url( '', ACFWF()->Plugin_Constants->MAIN_PLUGIN_FILE_PATH ),
                    'adminAjaxUrl' => admin_url( 'admin-ajax.php' ),
                ),
                $this->l10n
            )
        );

        /**************************************************************************
         * Enable gettext functions in our script
         ***************************************************************************
         *
         * We tell WordPress to load translations directly in our script if we have
         * wp-i18n as a dependency.
         */
        if ( in_array( 'wp-i18n', $this->dependencies, true ) ) {
            wp_set_script_translations(
                $this->script_handle,
                'woocommerce-wholesale-order-form',
                ACFWF()->Plugin_Constants->PLUGIN_DIR_PATH . 'languages'
            );
        }

        /**************************************************************************
         * Load main app style
         ***************************************************************************
         *
         * If we are not in hot module replacement mode, we load the main app style
         * referenced from the manifest file.
         */
        if ( ! $this->is_hmr ) {
            $styles = $this->manifest[ $this->entry_file_path ]['css'] ?? array();

            if ( ! empty( $styles ) ) {
                foreach ( $styles as $style ) {
                    wp_enqueue_style(
                        $this->script_handle,
                        plugins_url( "dist/$style", ACFWF()->Plugin_Constants->MAIN_PLUGIN_FILE_PATH ),
                        $this->style_dependencies,
                        filemtime( ACFWF()->Plugin_Constants->PLUGIN_DIR_PATH . "dist/$style" )
                    );
                }
            }
        } elseif ( ! empty( $this->style_dependencies ) ) {
            foreach ( $this->style_dependencies as $style_dependency ) {
                wp_enqueue_style( $style_dependency );
            }
        }
    }

    /**
     * Modify script tag to include attributes.
     *
     * @param string $tag    The script tag.
     * @param string $handle The script handle.
     *
     * @since 4.5.8
     * @return string
     */
    public function add_script_tag_attributes( $tag, $handle ) {

        /**************************************************************************
         * Convert script tag to module
         ***************************************************************************
         *
         * We modify the script tag to include the type, crossorigin and integrity
         * attributes in production mode. Otherwise, we add the type module attribute.
         */
        $handles = array(
            $this->script_handle,
            "$this->script_handle-vite-client",
        );
        if ( in_array( $handle, $handles, true ) ) {
            if ( $this->is_hmr ) {
                $tag = str_replace(
                    ' id',
                    ' type="module" id',
                    $tag
                );
            } else {
                $integrity = '';
                if ( apply_filters( 'acfw_enable_subresource_integrity_check', false ) ) {
                    $integrity = sprintf(
                        'crossorigin="anonymous" integrity="%s" ',
                        $this->get_file_hash(
                            ACFWF()->Plugin_Constants->PLUGIN_DIR_PATH . "dist/{$this->manifest[ $this->entry_file_path ]['file']}"
                        )
                    );
                }
                // Set or replace the type attribute with `module` value and maybe add the $integrity attributes.
                $tag = str_replace(
                    array( ' src', 'type="text/javascript"', "type='text/javascript'" ),
                    array( sprintf( ' type="module" %ssrc', $integrity ), '', '' ),
                    $tag
                );
            }
        }

        return $tag;
    }

    /**
     * Get file hash.
     *
     * @param string $file The path to the target file.
     *
     * @since 4.5.8
     * @return string
     */
    protected function get_file_hash( $file ) {

        $algo = 'sha256';

        return "$algo-" .
            //phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
            base64_encode(
                openssl_digest(
                //phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
                    file_get_contents( $file ),
                    $algo,
                    true
                )
            );
    }

    /**
     * Modify style tag to include attributes.
     *
     * @param string $tag    The style tag.
     * @param string $handle The style handle.
     * @param string $href   The style URL.
     *
     * @since 4.5.8
     * @return string
     */
    public function add_style_tag_attributes( $tag, $handle, $href ) {

        if ( str_contains( $handle, $this->script_handle ) &&
            ! empty( $this->manifest[ $this->entry_file_path ]['css'] ) ) {
            /**************************************************************************
             * Add crossorigin and integrity attributes
             ***************************************************************************
             *
             * We modify our target style tag to include the crossorigin and integrity
             * attributes in production mode.
             */
            foreach ( $this->manifest[ $this->entry_file_path ]['css'] as $style ) {
                if ( false !== strpos( $href, $style ) ) {
                    $integrity = '';
                    if ( apply_filters( 'acfw_enable_subresource_integrity_check', false ) ) {
                        $integrity = sprintf(
                            'crossorigin="anonymous" integrity="%s" ',
                            $this->get_file_hash(
                                ACFWF()->Plugin_Constants->PLUGIN_DIR_PATH . "dist/$style"
                            )
                        );
                    }

                    $tag = str_replace(
                        ' href',
                        sprintf( ' %shref', $integrity ),
                        $tag
                    );
                }
            }
        }

        return $tag;
    }
}
