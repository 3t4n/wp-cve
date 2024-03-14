<?php

/**
 * Live Code Editor Final Class
 *
 * @link       http://www.ozanwp.com
 * @since      1.0.0
 *
 * @package    Live_Code_Editor
 * @subpackage Live_Code_Editor/includes
 * @author     Ozan Canakli <ozan@ozanwp.com>
 */

final class Live_Code_Editor {

	/**
     * Register customizer controls.
     *
     * @since    1.0.0
     */
    static public function customizer_register( $wp_customize )
    {
        require_once LIVE_CODE_EDITOR_DIR . '/includes/class-live-code-editor-customizer-control.php';
        require_once LIVE_CODE_EDITOR_DIR . '/includes/class-live-code-editor-customizer-panel.php';
        
    }

    /**
     * Register the stylesheets for the customizer.
     *
     * @since    1.0.0
     */
    static public function customizer_enqueue_scripts()
    {
        wp_enqueue_style( 'live-code-customizer', LIVE_CODE_EDITOR_URL . 'assets/css/customizer.css', array(), LIVE_CODE_EDITOR_VER );
        wp_enqueue_script( 'ace', LIVE_CODE_EDITOR_URL . 'assets/js/ace/ace.js', array(), LIVE_CODE_EDITOR_VER, true );
        wp_enqueue_script( 'ace-language-tools', LIVE_CODE_EDITOR_URL . 'assets/js/ace/ext-language_tools.js', array(), LIVE_CODE_EDITOR_VER, true );
        wp_enqueue_script( 'live-code-customizer', LIVE_CODE_EDITOR_URL . 'assets/js/customizer.js', array(), LIVE_CODE_EDITOR_VER, true );
    }

    /**
     * Register the stylesheets for the public-facing site.
     *
     * @since    1.0.0
     */
    static public function public_enqueue_scripts()
    {
        wp_enqueue_script( 'live-code-customizer-public', LIVE_CODE_EDITOR_URL . 'assets/js/customizer-public.js', array(), LIVE_CODE_EDITOR_VER, true );
    }

    /**
     * Renders wp_header codes
     *
     * @since    1.0.0
     */
    static public function head_codes() {

        $css      = get_option( 'live_code_css_field' );
        $js       = get_option( 'live_code_js_field' );
        $header   = get_option( 'live_code_header_field' );
        $admincss = get_option( 'live_code_admin_css_field' );

        // CSS
        if( ! empty($css) ) {
            echo '<style id="live-code-editor-css">' . "\n" . $css . ' '. "\n" .'</style>' . "\n";
        }

        // JS
        if( ! empty($js) ) {
            echo '<script id="live-code-editor-js">' . "\n" . $js . ' '. "\n" .'</script>' . "\n";
        }

        // HEADER Code
        if( ! empty($header) ) {
            echo $header . "\n";
        }

    }

    /**
     * Renders admin_head codes
     *
     * @since    1.0.5
     */
    static public function admin_head_codes() {

        $admincss = get_option( 'live_code_admin_css_field' );
        $adminjs  = get_option( 'live_code_admin_js_field' );

        // Admin CSS
        if( ! empty($admincss) ) {
            echo '<style id="live-code-editor-admin-css">' . "\n" . $admincss . ' '. "\n" .'</style>' . "\n";
        }
            
        // Admin JS
        if( ! empty($adminjs) ) {
            echo '<script id="live-code-editor-admin-js">' . "\n" . $adminjs . ' '. "\n" .'</script>' . "\n";
        }

    }

    /**
     * Renders wp_footer codes
     *
     * @since    1.0.0
     */
    static public function footer_codes() {

        $footer   = get_option( 'live_code_footer_field' );

        // FOOTER Code
        if( ! empty($footer) ) {
            echo $footer . "\n";
        }
        
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    static public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'live-css-js-code-editor',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}

}