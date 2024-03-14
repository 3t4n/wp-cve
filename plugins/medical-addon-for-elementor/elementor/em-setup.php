<?php
/*
 * All Elementor Init
 * Author & Copyright: NicheAddon
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists('Medical_Elementor_Addon_Core_Elementor_init') ){
	class Medical_Elementor_Addon_Core_Elementor_init{

		/*
		 * Minimum Elementor Version
		*/
		const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

		/*
		 * Minimum PHP Version
		*/
		const MINIMUM_PHP_VERSION = '5.6';

    /*
	   * Instance
	  */
		private static $instance;

		/*
		 * Main Medical Addon for Elementor plugin Class Constructor
		*/
		public function __construct(){
			add_action( 'plugins_loaded', [ $this, 'init' ] );

			add_action( 'elementor/editor/before_enqueue_scripts', function() {
			   wp_enqueue_style('namedical-ele-editor-linea', NAMEP_PLUGIN_URL . 'assets/css/linea.min.css', [], '1.0.0');
			   wp_enqueue_style('namedical-ele-editor-themify', NAMEP_PLUGIN_URL . 'assets/css/themify-icons.min.css', [], '1.0.0');
			} );

			// Js Enqueue
			add_action( 'elementor/frontend/after_enqueue_scripts', function() {

   			wp_enqueue_script( 'namedical-chartjs', NAMEP_PLUGIN_URL . 'assets/js/Chart.min.js', array( 'jquery' ), '2.9.3', true );
				wp_enqueue_script( 'namedical-elementor', NAMEP_PLUGIN_URL . 'elementor/js/namedical-elementor.js', [ 'jquery' ], false, true );
			} );

		}

		/*
		 * Class instance
		*/
		public static function getInstance(){
			if (null === self::$instance) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/*
		 * Initialize the plugin
		*/
		public function init() {

			// Check for required Elementor version
			if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
				return;
			}

			// Check for required PHP version
			if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
				return;
			}

			// elementor Custom Group Controls Include
			self::controls_helper();

			// elementor categories
			add_action( 'elementor/elements/categories_registered', [ $this, 'basic_widget_categories' ] );
			add_action( 'elementor/elements/categories_registered', [ $this, 'namedical_unique_widget_categories' ] );
			add_action( 'elementor/elements/categories_registered', [ $this, 'namedical_EA_widget_categories' ] );

			// Elementor Widgets Registered
			 add_action( 'elementor/widgets/widgets_registered', [ $this, 'namedical_basic_widgets_registered' ] );
			 add_action( 'elementor/widgets/widgets_registered', [ $this, 'namedical_widgets_registered' ] );
			 add_action( 'elementor/widgets/widgets_registered', [ $this, 'namedical_unique_widgets_registered' ] );

		}

		/*
		 * Admin notice
		 * Warning when the site doesn't have a minimum required Elementor version.
		*/
		public function admin_notice_minimum_elementor_version() {
			if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
			$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'medical-addon-for-elementor' ),
				'<strong>' . esc_html__( 'Medical Addon for Elementor', 'medical-addon-for-elementor' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'medical-addon-for-elementor' ) . '</strong>',
				 self::MINIMUM_ELEMENTOR_VERSION
			);
			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
		}

		/*
		 * Admin notice
		 * Warning when the site doesn't have a minimum required PHP version.
		*/
		public function admin_notice_minimum_php_version() {
			if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
			$message = sprintf(
				/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'medical-addon-for-elementor' ),
				'<strong>' . esc_html__( 'Medical Addon for Elementor', 'medical-addon-for-elementor' ) . '</strong>',
				'<strong>' . esc_html__( 'PHP', 'medical-addon-for-elementor' ) . '</strong>',
				 self::MINIMUM_PHP_VERSION
			);
			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
		}

		/*
		 * Class Group Controls
		*/
		public static function controls_helper(){
			$group_controls = ['lib'];
			foreach($group_controls as $control){
				if ( file_exists( NAMEP_EM_SHORTCODE_BASE_PATH . '/lib/'.$control.'.php' ) ){
					require_once( NAMEP_EM_SHORTCODE_BASE_PATH . '/lib/'.$control.'.php' );
				}
			}
		}

		/*
		 * Widgets elements categories
		*/
		public function basic_widget_categories($elements_manager){
			$elements_manager->add_category(
				'namedical-basic-category',
				[
					'title' => __( 'Medical Basic Elements : By Niche Addons', 'medical-addon-for-elementor' ),
				]
			);
		}
		public function namedical_unique_widget_categories($elements_manager){
			$elements_manager->add_category(
				'namedical-unique-category',
				[
					'title' => __( 'Unique Medical Elements : By Niche Addons', 'medical-addon-for-elementor' ),
				]
			);
		}
		public function namedical_EA_widget_categories($elements_manager){
			$elements_manager->add_category(
				'namedical-ea-category',
				[
					'title' => __( 'Appointment Elements : By Niche Addons', 'medical-addon-for-elementor' ),
				]
			);
		}

		/*
		 * Widgets registered
		*/
		public function namedical_basic_widgets_registered(){
			// init widgets
			$basic_dir = NAMEP_EM_BASIC_SHORTCODE_PATH;
			// Open a directory, and read its contents
			if (is_dir($basic_dir)){
			  $basic_dh = opendir($basic_dir);
		    while (($basic_file = readdir($basic_dh)) !== false){
		    	if (!in_array(trim($basic_file), ['.', '..'])) {
						$basic_template_file = NAMEP_EM_SHORTCODE_BASE_PATH . 'widgets/basic/'.$basic_file;
						if ( $basic_template_file && is_readable( $basic_template_file ) ) {
							include_once $basic_template_file;
						}
			    }
		    }
		    closedir($basic_dh);
			}
		}

		public function namedical_widgets_registered(){
			// init widgets
			$dir = NAMEP_EM_SHORTCODE_PATH;
			// Open a directory, and read its contents
			if (is_dir($dir)){
			  $dh = opendir($dir);
		    while (($file = readdir($dh)) !== false){
		    	if (!in_array(trim($file), ['.', '..'])) {
						$template_file = NAMEP_EM_SHORTCODE_BASE_PATH . 'widgets/medical/'.$file;
						if ( $template_file && is_readable( $template_file ) ) {
							include_once $template_file;
						}
			    }
		    }
		    closedir($dh);
			}
		}

		public function namedical_unique_widgets_registered(){
			// init widgets
			$unique_dir = NAMEP_EM_UNIQUE_SHORTCODE_PATH;
			// Open a directory, and read its contents
			if (is_dir($unique_dir)){
			  $unique_dh = opendir($unique_dir);
		    while (($unique_file = readdir($unique_dh)) !== false){
		    	if (!in_array(trim($unique_file), ['.', '..'])) {
						$unique_template_file = NAMEP_EM_SHORTCODE_BASE_PATH . 'widgets/medical-unique/'.$unique_file;
						if ( $unique_template_file && is_readable( $unique_template_file ) ) {
							include_once $unique_template_file;
						}
			    }
		    }
		    closedir($unique_dh);
			}
		}

	} //end class

	if (class_exists('Medical_Elementor_Addon_Core_Elementor_init')){
		Medical_Elementor_Addon_Core_Elementor_init::getInstance();
	}

}

if ( ! function_exists( 'namedical_elementor_default_typo_color_active' ) ) {
	function namedical_elementor_default_typo_color_active() {
		update_option( 'elementor_disable_color_schemes', 'yes' );
		update_option( 'elementor_disable_typography_schemes', 'yes' );
	}
	add_action( 'after_switch_theme', 'namedical_elementor_default_typo_color_active' );
}

if ( ! function_exists( 'namedical_elementor_default_typo_color_active_after' ) ) {
	function namedical_elementor_default_typo_color_active_after() {
		update_option( 'elementor_disable_color_schemes', 'yes' );
		update_option( 'elementor_disable_typography_schemes', 'yes' );
	}
	add_action( 'pt-ocdi/after_content_import_execution', 'namedical_elementor_default_typo_color_active_after' );
}

/* Add Featured Image support in event organizer */
add_post_type_support( 'tribe_organizer', 'thumbnail' );

/* Excerpt Length */
class Medical_Elementor_Addon_Excerpt {
  public static $length = 55;
  public static $types = array(
    'short' => 25,
    'regular' => 55,
    'long' => 100
  );
  public static function length($new_length = 55) {
    Medical_Elementor_Addon_Excerpt::$length = $new_length;
    add_filter('excerpt_length', 'Medical_Elementor_Addon_Excerpt::new_length');
    Medical_Elementor_Addon_Excerpt::output();
  }
  public static function new_length() {
    if ( isset(Medical_Elementor_Addon_Excerpt::$types[Medical_Elementor_Addon_Excerpt::$length]) )
      return Medical_Elementor_Addon_Excerpt::$types[Medical_Elementor_Addon_Excerpt::$length];
    else
      return Medical_Elementor_Addon_Excerpt::$length;
  }
  public static function output() {
    the_excerpt();
  }
}

// Custom Excerpt Length
if ( ! function_exists( 'namedical_excerpt' ) ) {
  function namedical_excerpt($length = 55) {
    Medical_Elementor_Addon_Excerpt::length($length);
  }
}

if ( ! function_exists( 'namedical_new_excerpt_more' ) ) {
  function namedical_new_excerpt_more( $more ) {
    return '...';
  }
  add_filter('excerpt_more', 'namedical_new_excerpt_more');
}

if ( ! function_exists( 'namedical_paging_nav' ) ) {
  function namedical_paging_nav($numpages = '', $pagerange = '', $paged='') {

      if (empty($pagerange)) {
        $pagerange = 2;
      }
      if (empty($paged)) {
        $paged = 1;
      } else {
        $paged = $paged;
      }
      if ($numpages == '') {
        global $wp_query;
        $numpages = $wp_query->max_num_pages;
        if (!$numpages) {
          $numpages = 1;
        }
      }
      global $wp_query;
      $big = 999999999;
      if ($wp_query->max_num_pages != '1' ) { ?>
      <div class="namep-pagination">
        <?php echo paginate_links( array(
          'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
          'format' => '?paged=%#%',
          'prev_text' => '<i class="fa fa-angle-double-left" aria-hidden="true"></i>',
          'next_text' => '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
          'current' => $paged,
          'total' => $numpages,
          'type' => 'list'
        )); ?>
      </div>
    <?php }
  }
}

if ( ! function_exists( 'namedical_clean_string' ) ) {
	function namedical_clean_string($string) {
	  $string = str_replace(' ', '', $string);
	  return preg_replace('/[^\da-z ]/i', '', $string);
	}
}

/* Validate px entered in field */
if ( ! function_exists( 'namedical_core_check_px' ) ) {
  function namedical_core_check_px( $num ) {
    return ( is_numeric( $num ) ) ? $num . 'px' : $num;
  }
}
