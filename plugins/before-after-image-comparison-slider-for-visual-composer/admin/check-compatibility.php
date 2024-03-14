<?php
/**
 * Plugin Compatibility Class
 *
 * plugin compability class that checks the compatibility
 *
 * @since 1.0.0
 */

class WB_VC_BAIC_Check_Compatibility {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
    const VERSION = '2.0.0';

    /**
     * Minimum Visual Composer Version
     *
     * @since 1.0.0
     *
     * @var string Minimum Visual Composer version required to run the plugin.
     */
    const MINIMUM_VC_VERSION = '5.4.7';

    /**
     * Minimum PHP Version
     *
     * @since 1.0.0
     *
     * @var string Minimum PHP version required to run the plugin.
     */
    const MINIMUM_PHP_VERSION = '5.5';

    /**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
    function __construct() {
        // We safely integrate with VC with this hook
        add_action( 'plugins_loaded', array( $this, 'init' ) );
    }


    /**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Visual Composer (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by 'plugins_loaded' action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
    public function init() {
      // Check if Visual Composer is installed
      if ( ! defined( 'WPB_VC_VERSION' ) ) {
          // Display notice that Visual Compser is required
          add_action('admin_notices', array( $this, 'admin_notice_missing_main_plugin' ));
          return;
      }

      // Check for required Visual Composer version
  		if ( ! version_compare( WPB_VC_VERSION, self::MINIMUM_VC_VERSION, '>=' ) ) {
  			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_vc_version' ) );
  			return;
  		}

  		// Check for required PHP version
  		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
  			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
  			return;
  		}

    }


    /**
     * Admin notice
     *
     * Warning when the site doesn't have Visual Composer installed or activated.
     *
     * @access public
     *
     */
    public function admin_notice_missing_main_plugin() {

        if ( isset( $_GET['activate'] ) ){ unset( $_GET['activate'] ); }

        $message = sprintf(
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'before-after-image-comparison-slider-for-visual-composer' ),
            '<strong>' . esc_html__( 'Before After Image Comparison Slider for Visual Composer', 'before-after-image-comparison-slider-for-visual-composer' ) . '</strong>',
            '<strong>' . esc_html__( 'Visual Composer', 'before-after-image-comparison-slider-for-visual-composer' ) . '</strong>'
        );

        printf( '<div class="error"><p>%1$s</p></div>', $message );

    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Visual Composer version.
     *
     * @access public
     *
     */
    public function admin_notice_minimum_vc_version(){
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            esc_html( '"%1$s" requires "%2$s" version %3$s or greater.', 'before-after-image-comparison-slider-for-visual-composer' ),
            '<strong>' . esc_html( 'Before After Image Comparison Slider for Visual Composer', 'before-after-image-comparison-slider-for-visual-composer' ) . '</strong>',
            '<strong>' . esc_html( 'Visual Composer', 'before-after-image-comparison-slider-for-visual-composer' ) . '</strong>',
             self::MINIMUM_VC_VERSION
        );

        printf( '<div class="error"><p>%1$s</p></div>', $message );
    }


    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     *
     * @access public
     *
     */
    public function admin_notice_minimum_php_version() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            esc_html( '"%1$s" requires "%2$s" version %3$s or greater.', 'before-after-image-comparison-slider-for-visual-composer' ),
            '<strong>' . esc_html( 'Before After Image Comparison Slider for Visual Composer', 'before-after-image-comparison-slider-for-visual-composer' ) . '</strong>',
            '<strong>' . esc_html( 'PHP', 'before-after-image-comparison-slider-for-visual-composer' ) . '</strong>',
             self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="error"><p>%1$s</p></div>', $message );

    }

}