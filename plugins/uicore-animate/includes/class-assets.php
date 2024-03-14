<?php
namespace UiCoreAnimate;

/**
 * Scripts and Styles Class
 */
class Assets {

    function __construct() {
        if ( is_admin() ) {
            add_action( 'admin_enqueue_scripts', [ $this, 'register' ], 5 );
        } else {
            add_action( 'wp_enqueue_scripts', [ $this, 'register' ], 5 );
        }
        add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'register' ], 1 );
    }

    /**
     * Register our app scripts and styles
     *
     * @return void
     */
    public function register() {
        $this->register_scripts( $this->get_scripts() );
        $this->register_styles( $this->get_styles() );
        //add animations if is elementor editor
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
            ?>
            <script>
                var uicore_animations_list = <?php echo wp_json_encode( \Elementor\Control_Animation::get_animations() ); ?>;
                var uicore_split_animations_list = <?php echo wp_json_encode( Helper::get_split_animations_list() ); ?>;
            </script>
            <?php
        }
    }

    /**
     * Register scripts
     *
     * @param  array $scripts
     *
     * @return void
     */
    private function register_scripts( $scripts ) {
        foreach ( $scripts as $handle => $script ) {
            $deps      = isset( $script['deps'] ) ? $script['deps'] : false;
            $in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : false;
            $version   = isset( $script['version'] ) ? $script['version'] : UICORE_ANIMATE_VERSION;

            wp_register_script( $handle, $script['src'], $deps, $version, $in_footer );
        }
    }

    /**
     * Register styles
     *
     * @param  array $styles
     *
     * @return void
     */
    public function register_styles( $styles ) {
        foreach ( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;

            wp_register_style( $handle, $style['src'], $deps, UICORE_ANIMATE_VERSION );
        }
    }

    /**
     * Get all registered scripts
     *
     * @return array
     */
    public function get_scripts() {
        $prefix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

        $scripts = [
            'uicore_animate-vendor' => [
                'src'       => UICORE_ANIMATE_ASSETS . '/js/vendor'.$prefix.'.js',
                'version'   => UICORE_ANIMATE_VERSION,
                'in_footer' => true
            ],
            'uicore_animate-settings' => [
                'src'       => UICORE_ANIMATE_ASSETS . '/js/settings'.$prefix.'.js',
                'deps'      => [ 'jquery', 'uicore_animate-vendor' ],
                'version'   => UICORE_ANIMATE_VERSION,
                'in_footer' => true
            ],
            'uicore_animate-admin' => [
                'src'       => UICORE_ANIMATE_ASSETS . '/js/admin'.$prefix.'.js',
                'deps'      => [ 'jquery', 'uicore_animate-vendor' ],
                'version'   => UICORE_ANIMATE_VERSION,
                'in_footer' => true
            ]
        ];

        return $scripts;
    }

    /**
     * Get registered styles
     *
     * @return array
     */
    public function get_styles() {

        $styles = [
            'uicore_animate-settings' => [
                'src' =>  UICORE_ANIMATE_ASSETS . '/css/settings.css'
            ],
            'uicore_animate-admin' => [
                'src' =>  UICORE_ANIMATE_ASSETS . '/css/admin.css'
            ],
        ];

        return $styles;
    }

}
