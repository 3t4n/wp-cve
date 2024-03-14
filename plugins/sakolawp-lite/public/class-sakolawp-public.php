<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://themesawesome.com/
 * @since      1.0.0
 *
 * @package    Sakolawp
 * @subpackage Sakolawp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sakolawp
 * @subpackage Sakolawp/public
 * @author     Themes Awesome <themesawesome@gmail.com>
 */
class Sakolawp_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

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

		add_action('wp_ajax_sakolawp_select_section',        'sakolawp_select_section_f');
		add_action('wp_ajax_nopriv_sakolawp_select_section', 'sakolawp_select_section_f');
		
		add_action( 'wp_ajax_sakolawp_select_subject_teacher', 'sakolawp_select_subject_teacher_f' );    // If called from admin panel
		add_action( 'wp_ajax_nopriv_sakolawp_select_subject_teacher', 'sakolawp_select_subject_teacher_f' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sakolawp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sakolawp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'datatablesstyle', plugin_dir_url( __FILE__ ) . 'css/datatables.min.css', array());
		wp_enqueue_style( 'daterangepicker', plugin_dir_url( __FILE__ ) . 'css/daterangepicker.css', array());
		wp_enqueue_style( 'clockpicker', plugin_dir_url( __FILE__ ) . 'css/clockpicker.min.css', array());
		wp_enqueue_style( 'fonts', plugin_dir_url( __FILE__ ) . 'css/fonts.css', array());
		wp_enqueue_style( $this->plugin_name.'-rtl', plugin_dir_url( __FILE__ ) . 'css/sakolawp-public-rtl.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sakolawp-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-responsive', plugin_dir_url( __FILE__ ) . 'css/sakolawp-responsive.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sakolawp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sakolawp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		global $wp;

		if($wp->request !== "exam") {
			wp_enqueue_script( 'bootstrap', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), false );
			wp_enqueue_script( 'daterange', plugin_dir_url( __FILE__ ) . 'js/daterange.js', array( 'jquery' ), false );
			wp_enqueue_script( 'modal', plugin_dir_url( __FILE__ ) . 'js/modal.js', array( 'jquery' ), false );
		}
		wp_enqueue_script( 'clockpicker', plugin_dir_url( __FILE__ ) . 'js/clockpicker.min.js', array( 'jquery' ), false );
		wp_enqueue_script( 'datatables', plugin_dir_url( __FILE__ ) . 'js/dataTables.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'datatables-checkbox', plugin_dir_url( __FILE__ ) . 'js/dataTables.checkboxes.min.js', array( 'jquery' ), false, true );

		wp_enqueue_script( 'skwp-custom', plugin_dir_url( __FILE__ ) .'js/skwp-custom.js', array(), '1.0.0', true );
		wp_localize_script( 'skwp-custom', 'skwp_ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		
		if($wp->request === "exam") {
			wp_enqueue_script( 'cookie-js', plugin_dir_url( __FILE__ ) . 'js/js.cookie.js', array( 'jquery' ), false );
			wp_enqueue_script( 'jquery-simple-pagination-plugin', plugin_dir_url( __FILE__ ) . 'js/jquery-simple-pagination-plugin.js', array( 'jquery' ), false );
			wp_enqueue_script( 'sakolawp-validations', plugin_dir_url( __FILE__ ) . 'js/validations.js', array( 'jquery' ), false );
		}
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sakolawp-public.js', array( 'masonry', 'jquery' ), $this->version, false );
	}

	function sakolawp_select_section_f() {
		global $wpdb;
		$class_id = sanitize_text_field($_REQUEST['class_id']);
		$sections = $wpdb->get_results( "SELECT section_id, name FROM {$wpdb->prefix}sakolawp_section WHERE class_id = '$class_id'", ARRAY_A );
		echo '<option value="">'.esc_html__('Select', 'sakolawp').'</option>';
		foreach ($sections as $row) 
		{
			echo '<option value="' . esc_attr($row['section_id']) . '">' . esc_html($row['name']) . '</option>';
		}

		exit();
	}

	function sakolawp_select_subject_teacher_f() {
		global $wpdb;
		$section_id = sanitize_text_field($_REQUEST['section_id']);
		$teacher_id = sanitize_text_field($_REQUEST['teacher_id']);
		$subjects = $wpdb->get_results( "SELECT subject_id, name FROM {$wpdb->prefix}sakolawp_subject WHERE section_id = '$section_id' AND teacher_id = '$teacher_id'", ARRAY_A );
		echo '<option value="">'.esc_html__('Select', 'sakolawp').'</option>';
		foreach ($subjects as $row) 
		{
			echo '<option value="' . esc_attr($row['subject_id']) . '">' . esc_html($row['name']) . '</option>';
		}

		exit();
	}

}
