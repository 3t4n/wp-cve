<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://atikul99.github.io/atikul
 * @since      1.0.0
 *
 * @package    Ai_Preloader
 * @subpackage Ai_Preloader/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ai_Preloader
 * @subpackage Ai_Preloader/public
 * @author     Atikul Islam <atikulislam94@gmail.com>
 */
class Ai_Preloader_Public {

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

		add_action( 'wp_head', array( $this, 'ai_preloader_display' ) );

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
		 * defined in Ai_Preloader_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ai_Preloader_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ai-preloader-public.css', array(), $this->version, 'all' );

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
		 * defined in Ai_Preloader_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ai_Preloader_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ai-preloader-public.js', array( 'jquery' ), $this->version, false );

	}

	public function ai_preloader_display(){
		
		$setting = get_option('demo-select');
		$bg_color = get_option('loader_bg');
		$primary_color = get_option('primary_color');
		$loader_img = get_option('loader_image');

		if(
			get_option( 'display_on' ) == 'full'
			or get_option( 'display_on' ) == 'homepage' and is_home()
			or get_option( 'display_on' ) == 'frontpage' and is_front_page()
			or get_option( 'display_on' ) == 'posts' and is_single()
			or get_option( 'display_on' ) == 'pages' and is_page()
			or get_option( 'display_on' ) == 'cats' and is_category()
			or get_option( 'display_on' ) == 'tags' and is_tag()
			or get_option( 'display_on' ) == 'attachment' and is_attachment()
			or get_option( 'display_on' ) == '404error' and is_404()
		){

			if( !empty($loader_img) ){

				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'templates/style0.php';

			}elseif( $setting == 'style1' ){

				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'templates/style1.php';

			}elseif($setting == 'style2'){

				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'templates/style2.php';

			}elseif($setting == 'style3'){

				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'templates/style3.php';

			}elseif($setting == 'style4'){

				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'templates/style4.php';

			}elseif($setting == 'style5'){
				
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'templates/style5.php';

			}elseif($setting == 'style6'){

				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'templates/style6.php';

			}elseif($setting == 'style7'){

				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'templates/style7.php';
			}

		} ?>

		<style>
			:root{
				--primary-color: <?php if(!empty($primary_color)){echo esc_html($primary_color);}else{echo "#EE4040";} ?>;
			}
			.preloader{
				background: <?php echo esc_html($bg_color); ?>;
			}
		</style>

		<?php
	}

}
