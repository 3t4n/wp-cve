<?php
/**
 * The elementor-facing functionality of the plugin.
 *
 * @link       elementorplus.net
 * @since      1.0.0
 *
 * @package    Kitpack_Lite
 * @subpackage Kitpack_Lite/elementor
 */

class Kitpack_Lite_elementor {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of this plugin.
	 */
	protected $version;



	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the elementor-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Kitpack_Lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kitpack_Lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/css/kitpack-lite-elementor.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the elementor-editor-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles_editor() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Kitpack_Lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kitpack_Lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/css/kitpack-lite-elementor-editor.css', array(), $this->version, 'all' );
		//wp_enqueue_style( 'kpe-elementor', plugins_url( 'assets/css/kpe_editor-elementor.css', __FILE__ ) );

	}
	/**
	 * Register the stylesheets for the elementor-preview-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles_preview() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Kitpack_Lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kitpack_Lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/css/kitpack-lite-elementor-preview.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the elementor-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Kitpack_Lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kitpack_Lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

//		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/js/kitpack-lite-elementor.js', array( 'jquery' ), $this->version, false );

	}
	public function kitpack_elementor_template() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Kitpack_Lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kitpack_Lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name ,plugin_dir_url( __FILE__ ) . 'assets/js/kitpack-lite-editor.js', array( 'jquery' ), $this->version, false );
	}

}
