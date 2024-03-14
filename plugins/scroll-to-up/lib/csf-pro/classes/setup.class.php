<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Setup Framework Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'CSF' ) ) {
  class CSF {

    /**
     *
     * instance
     * @access private
     * @var class
     *
     */
    private static $instance = null;

    public function __construct() {

      $this->constants();
      $this->includes();
      $this->textdomain();

    }

    // instance
    public static function instance() {
      if ( is_null( self::$instance ) ) {
        self::$instance = new self();
      }
      return self::$instance;
    }

    public static function locate_template( $template, $load = true ) {

      $located  = '';
      $override = apply_filters( 'csf/override/framework', 'csf-override' );

      if( file_exists( get_stylesheet_directory() .'/'. $override .'/'. $template ) ) {
        $located = get_stylesheet_directory() .'/'. $override .'/'. $template;
      } elseif ( file_exists( get_template_directory() .'/'. $override .'/'. $template ) ) {
        $located = get_template_directory() .'/'. $override .'/'. $template;
      } elseif ( file_exists( CSF_PLUGIN_DIR .'/'. $template ) ) {
        $located = CSF_PLUGIN_DIR .'/'. $template;
      }

      if( $load && ! empty( $located ) ) {

        global $wp_query;

        if( is_object( $wp_query ) && function_exists( 'load_template' ) ) {
          load_template( $located, true );
        } else {
          require_once( $located );
        }

      }

      if( ! $load ) {
        return CSF_PLUGIN_DIR .'/'. $template;
      }

    }

    // Define constants
    public function constants() {

      $dirname        = wp_normalize_path( dirname( dirname( __FILE__ ) ) );
      $theme_dir      = wp_normalize_path( get_template_directory() );
      $plugin_dir     = wp_normalize_path( WP_PLUGIN_DIR );
      $located_plugin = ( preg_match( '#'. sanitize_file_name( $plugin_dir ) .'#', sanitize_file_name( $dirname ) ) ) ? true : false;
      $directory      = ( $located_plugin ) ? $plugin_dir : $theme_dir;
      $directory_uri  = ( $located_plugin ) ? WP_PLUGIN_URL : get_template_directory_uri();
      $foldername     = str_replace( $directory, '', $dirname );

      defined( 'CSF_PLUGIN_DIR' ) or define( 'CSF_PLUGIN_DIR', $directory . $foldername );
      defined( 'CSF_PLUGIN_URL' ) or define( 'CSF_PLUGIN_URL', $directory_uri . $foldername );

    }

    // Includes framework files
    public function includes() {

      // includes helpers
      $this->locate_template( 'functions/deprecated.php' );
      $this->locate_template( 'functions/fallback.php' );
      $this->locate_template( 'functions/helpers.php' );
      $this->locate_template( 'functions/actions.php' );
      $this->locate_template( 'functions/enqueue.php' );
      $this->locate_template( 'functions/sanitize.php' );
      $this->locate_template( 'functions/validate.php' );

      // includes classes
      $this->locate_template( 'classes/abstract.class.php' );
      $this->locate_template( 'classes/fields.class.php' );
      $this->locate_template( 'classes/framework.class.php' );
      $this->locate_template( 'classes/metabox.class.php' );
      $this->locate_template( 'classes/taxonomy.class.php' );
      $this->locate_template( 'classes/shortcode.class.php' );
      $this->locate_template( 'classes/customize.class.php' );

      do_action( 'csf/includes' );

    }

    // Load textdomain
    public function textdomain() {
      load_textdomain( 'csf', CSF_PLUGIN_DIR .'/languages/'. get_locale() .'.mo' );
    }

  }

  CSF::instance();
}
