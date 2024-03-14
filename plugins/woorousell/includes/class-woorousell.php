<?php
/**
 * WoorouSell Core Class
 *
 * @author 		MojofyWP
 * @package 	includes
 * 
 */

if ( !class_exists('WoorouSell') ) :

class WoorouSell {

	/**
	 * Class instance
	 *
	 * @access private
	 * @var object
	 */
	private static $_instance = null;

	/**
	 * Absolute url to plugin's directory 
	 *
	 * @access private
	 * @var string
	 */
	private $_plugin_url = null;

	/**
	 * Absolute path to plugin's directory 
	 *
	 * @access private
	 * @var string
	 */
	private $_plugin_path = null;

	/**
	 * Plugin hook prefix ( for filter & action hooks )
	 *
	 * @access private
	 * @var string
	 */
	private $_plugin_hook = null;

	/**
	 * Plugin metabox prefix
	 *
	 * @access private
	 * @var string
	 */
	private $_plugin_meta_prefix = null;

	/**
	 * Plugin option id
	 *
	 * @access private
	 * @var string
	 */
	private $_plugin_options = null;

	/**
	 * Plugin labels
	 *
	 * @access private
	 * @var array
	 */
	private $_labels = null;

	/**
	 * Get class instance
	 *
	 * @access public
	 * @return object
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new WoorouSell();

		return self::$_instance;
	}

	/**
	 * Retrieve plugin path
	 *
	 * @access public
	 * @param string $path (optional) - appended into plugin directory
	 * @return string
	 */
	public function plugin_path( $path = '' ) {
		return $this->_plugin_path . ltrim($path, '/');
	}

	/**
	 * Retrieve plugin url
	 *
	 * @access public
	 * @param string $url (optional) - appended into plugin directory
	 * @return string
	 */
	public function plugin_url( $url = '' ) {
		return $this->_plugin_url . ltrim($url, '/');
	}

	/**
	 * Get plugin hook (for filter & action hooks )
	 *
	 * @access public
	 * @return string
	 */
	public function plugin_hook() {
		return $this->_plugin_hook;
	}

	/**
	 * Get plugin metabox prefix
	 *
	 * @access public
	 * @return string
	 */
	public function plugin_meta_prefix() {
		return $this->_plugin_meta_prefix;
	}

	/**
	 * Get plugin option id
	 *
	 * @access public
	 * @return string
	 */
	public function plugin_options() {
		return $this->_plugin_options;
	}

	/**
	 * Get plugin labels
	 *
	 * @access public
	 * @return array
	 */
	public function plugin_labels() {
		return apply_filters( 'wrsl_plugin_labels' , array(
			'woorousell' => __( 'Woorousell' , WRSL_SLUG )
		) );
	}
	
	/**
	 * Class Constructor
	 *
	 * @access private
	 */
    function __construct() {

    	// setup variables
		$this->_plugin_path = WRSL_PATH;
		$this->_plugin_url = WRSL_URL;
		$this->_plugin_hook = 'wrsl_';
		$this->_plugin_meta_prefix = '_wrsl_';
		$this->_plugin_options = 'woorousell';
		$this->_labels = $this->plugin_labels();

		// include required files
		add_action( 'init' , array(&$this, 'includes') , 0 );

		// enqueue main css & scripts
		add_action( 'wp_enqueue_scripts', array(&$this, 'register_css') , 49 );
		add_action( 'admin_enqueue_scripts', array(&$this, 'register_backend_scripts') , 49 );

    }

	/**
	 * Enqueue css
	 *
	 * @access public
	 */
	public function register_css() {
		wp_enqueue_style('font-awesome', $this->plugin_url('assets/css/font-awesome.min.css'), null , '4.7.0' );
		wp_enqueue_style('woorousell', $this->plugin_url('assets/css/core.css'), null , WRSL_VERSION );
	}

	/**
	 * Enqueue backend css & js
	 *
	 * @access public
	 */
	public function register_backend_scripts() {

		// wp styles & scripts
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('iris');
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_script('thickbox');  
		wp_enqueue_style('thickbox');  
		wp_enqueue_script('media-upload');
		wp_enqueue_media();
		wp_enqueue_script( 'jquery-ui-sortable' );

		wp_enqueue_style( 'font-open-sans' , ( is_ssl() ? 'https' : 'http' ).'://fonts.googleapis.com/css?family=Open+Sans:400,300italic,300,400italic,600,600italic,700,700italic,800,800italic' , null, '1.0.0');
		wp_enqueue_style('font-awesome', $this->plugin_url('assets/css/font-awesome.min.css'), null , '4.7.0' );
		wp_enqueue_style('jquery-magnific-popup', $this->plugin_url('assets/css/magnific-popup.css'), null , '1.1.0' );
		wp_enqueue_style('wrsl-backend', $this->plugin_url('assets/css/backend.min.css'), null , WRSL_VERSION );
		wp_enqueue_script('wrsl-accordion', $this->plugin_url('assets/js/jquery.accordion.js'), array('jquery') , '0.0.1' );
		wp_enqueue_script('jquery-magnific-popup', $this->plugin_url('assets/js/jquery.magnific-popup.min.js'), array('jquery') , '1.1.0' );
		wp_enqueue_script('wrsl-backend-js', $this->plugin_url('assets/js/backend.js'), array('jquery') , WRSL_VERSION );
		wp_localize_script( 'wrsl-backend-js' , 'WRSLB_AJAX' , apply_filters( $this->plugin_hook() . 'backend_localize_args' , array( 
					'ajaxUrl' 	=> admin_url( 'admin-ajax.php', ( is_ssl() ? 'https': 'http') ),
					'loadingModal' => '
						<div class="wrslb-modal-container">
							<div class="wrslb-modal-loading">
								' . wrsl_loader() . __( 'Loading...' , WRSL_SLUG ) . '
							</div>
						</div>',
					'processingModal' => '<div class="wrslb-modal-loading"><i class="fa fa-spinner fa-pulse"></i>' . __( 'Processing...' , WRSL_SLUG ) . '</div>',
					'savingModal' => '
						<div class="wrslb-modal-container">
							<div class="wrslb-modal-saving">
								'.wrsl_loader().'<span class="wrslb-modal-msg">' . __( 'Updating...' , WRSL_SLUG ) . '</span>
							</div>
						</div>',
					'loadingBtn' => '<i class="fa fa-spinner fa-pulse"></i>' . __( 'Loading...' , WRSL_SLUG ),
					'deleteContent' => __( "Are you sure you want to remove this content?" , WRSL_SLUG ),
				) ) 
			);
	}

	/**
	 * Include required files
	 *
	 * @access public
	 */
	public function includes() {

		// Include all products mvc files
		require_once $this->plugin_path('includes/products/controller.php');
		require_once $this->plugin_path('includes/products/model.php');
		require_once $this->plugin_path('includes/products/view.php');

		// Include builder files ( if use builder )
		require_once $this->plugin_path('builder/builder-helpers.php');
		require_once $this->plugin_path('builder/helpers.php');

		// Include all builder mvc files
		require_once $this->plugin_path('builder/builder/model.php');
		require_once $this->plugin_path('builder/builder/view.php');
		require_once $this->plugin_path('builder/builder/controller.php');

		if ( is_admin() ) {
			// Include all settings page mvc files
			require_once $this->plugin_path('builder/settings-page/model.php');
			require_once $this->plugin_path('builder/settings-page/view.php');
			require_once $this->plugin_path('builder/settings-page/controller.php');

			// add help page
			require_once $this->plugin_path('includes/help.php');
		}

	}

	/* END
	------------------------------------------------------------------- */

} // end - class WoorouSell

endif; // end - !class_exists('WoorouSell')