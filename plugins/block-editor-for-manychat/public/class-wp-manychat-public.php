<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.simb.co
 * @since      1.0.0
 *
 * @package    Wp_Manychat
 * @subpackage Wp_Manychat/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Manychat
 * @subpackage Wp_Manychat/public
 * @author     SimBCo <info@simb.co>
 */
class Wp_Manychat_Public {

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
		 * defined in Wp_Manychat_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Manychat_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-manychat-public.css', array(), $this->version, 'all' );

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
		 * defined in Wp_Manychat_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Manychat_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-manychat-public.js', array( 'jquery' ), $this->version, false );

	}

	public function add_manychat_snippet()
	{
		$pageId = get_option('wpmc_fb_page_id', '');
		if ($pageId === '') {
			return;
		}
		?>
        <script src="//widget.manychat.com/<?php echo $pageId; ?>.js" async="async"></script>
    	<?php
	}

	function gutenberg_manychat_block_frontend() {
		
		wp_enqueue_style(
			'gutenberg-manychat-block-editor',
			plugins_url( 'css/gutenberg.css', __FILE__ ),
			array()
		);
	}

}
