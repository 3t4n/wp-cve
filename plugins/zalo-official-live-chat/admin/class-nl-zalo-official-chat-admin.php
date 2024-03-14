<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    NL_Zalo_Official_Chat
 * @subpackage NL_Zalo_Official_Chat/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    NL_Zalo_Official_Chat
 * @subpackage NL_Zalo_Official_Chat/admin
 * @author     Luu Trong Nghia <luutrongnghia38@gmail.com>
 */
class NL_Zalo_Official_Chat_Admin {

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
	 * 
	 */
	private $data;

	/**
	 * The unique instance of the plugin.
	 * 
	 * @var NL_Zalo_Official_Chat_Admin
	 */
	private static $instance;

	/**
	 * Gets an instance of our plugin
	 * 
	 * @return NL_Zalo_Official_Chat_Admin
	 */
	public static function get_instance()
	{
		if(null === $self::$instance)
		{
			$self::$instance = new $self();
		}

		return $self::$instance;
	}

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

		add_action('admin_menu', [$this, 'admin_menu']);
		$default = array(
			'zalo_oa_id' => '', 
			'zalo_hello_message' => 'Rất vui khi được hỗ trợ bạn!',
			'zalo_popup_time' => 0,
			'zalo_data_height' => 420,
			'zalo_data_width' => 350,
		);
		$this->data = get_option('zalo-oa-chat', $default);
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
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nl-zalo-official-chat-admin.css', array(), $this->version, 'all' );

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
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nl-zalo-official-chat-admin.js', array( 'jquery' ), $this->version, false );

	}

	function admin_menu() {
		add_options_page(
			'Zalo OA Chat - Setup',
			'Zalo OA Chat',
			'manage_options',
			'zalo-oa-chat',
			array(
				$this,
				'settings_page'
			)
		);
	}

	function settings_page() {
		require plugin_dir_path( __FILE__ ) . 'partials/nl-zalo-official-chat-admin-display.php';
	}

	public function get_setting($name) {
		return $this->data[$name];
	}

	public function save_setting() {
		update_option( 'zalo-oa-chat', $this->data );
	}
}
