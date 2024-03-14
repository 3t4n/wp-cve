<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.simb.co
 * @since      1.0.0
 *
 * @package    Wp_Manychat
 * @subpackage Wp_Manychat/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Manychat
 * @subpackage Wp_Manychat/admin
 * @author     SimBCo <info@simb.co>
 */
class Wp_Manychat_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-manychat-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-manychat-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function register_settings()
	{
		add_option( 'wpmc_fb_page_id');
   		register_setting( 'wpmc_options_group', 'wpmc_fb_page_id');
	}

	function register_options_page() {
  		add_options_page('Block Editor for Manychat', 'Block Editor for Manychat', 'manage_options', 'wp-manychat', array($this, 'options_page'));
	}

	function options_page()
	{
		require plugin_dir_path( __FILE__ ) . 'partials/wp-manychat-options-page.php';
	}

	function add_settings_sections(){
		add_settings_section(
			'wp-manychat-settings-section',                   // Section ID
			'',  // Section title
			'__return_false', // Section callback function
			'wp-manychat'                          // Settings page slug
		);
	}

	function add_settings_fields(){
		add_settings_field(
			'wp-manychat-page-id',       // Field ID
			'Page Id',       // Field title 
			array($this, 'pageid_field_callback'), // Field callback function
			'wp-manychat',                    // Settings page slug
			'wp-manychat-settings-section'               // Section ID
		);
	}
 
	/* Settings Field Callback */
	function pageid_field_callback(){
		?>
		<label for="fb-page-id">
			<input id="fb-page-id" type="text" value="<?php echo get_option( 'wpmc_fb_page_id', '' ); ?>" name="wpmc_fb_page_id" />
		</label>
		<?php
	}

	function gutenberg_manychat_block() {
		wp_enqueue_script(
			'gutenberg-manychat-block-active-editor',
			plugins_url( 'js/gutenberg.js', __FILE__ ),
			array( 'wp-blocks', 'wp-element', 'wp-data' )
		);
		
		wp_enqueue_style(
			'gutenberg-manychat-block-active-editor',
			plugins_url( 'css/gutenberg.css', __FILE__ ),
			array()
		);
	}

	function register_gutenberg_blocks()
	{
		register_block_type('wp-manychat/manychat-embed');
	}

}
