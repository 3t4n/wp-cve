<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://werkaandemuur.nl/
 * @since      1.0.0
 *
 * @package    Wadm
 * @subpackage Wadm/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wadm
 * @subpackage Wadm/admin
 * @author     Sander van Leeuwen <sander@werkaandemuur.nl>
 */
class Wadm_Admin {

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
	 * Available admin settings for this plugin
	 */
	protected $_settings = array(
		'artist_id' => array(
			'name' => 'Artist ID',
			'description' => 'Your artist ID as found in your dashboard.',
			'validation' => array(
				'type' => 'number',
				'required' => true,
				'maxlength' => 10,
			),
		),
		'api_key' => array(
			'name' => 'API Key',
			'description' => 'Your 40 character API key.',
			'validation' => array(
				'type' => 'text',
				'required' => true,
				'minlength' => 40,
				'maxlength' => 40,
				'pattern' => '[a-f0-9]{40}'
			),
		),
	);

	/**
	 * The API key as stored in the database
	 */
	protected $_apiKey;

	/**
	 * The Artist ID as stored in the database
	 */
	protected $_artistId;


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

		// Always test if configuration is correct; show notice on every admin page when it's not
		$this->testConfiguration();
	}

	public function getPluginName()
	{
		return $this->plugin_name;
	}

	/**
	 * Test if the plugin configuration is 'correct'
	 */
	public function testConfiguration()
	{
		$apiKey = $this->_getApiKey();

		if ($apiKey && strlen($apiKey) == 40 && $this->_getArtistId())
			return true;

		$notice = new Wadm_Admin_Notice(__('<strong>Note:</strong> Please finish the <a href="options-general.php?page=wadm">Werk aan de Muur plugin configuration</a> by adding your Artist ID and API key.', Wadm::TEXT_DOMAIN), 'warning');
		$notice->add();

		return false;
	}

	/**
	 * Test both connection and authentication
	 */
	public function testConnectionAndAuthentication()
	{
		if (!$this->testConnection())
			return false;

		return $this->testAuthentication();
	}

	/**
	 * Test if the Wordpress install is able to connect to the WADM server. Uses api method which doens't
	 * require authentication
	 */
	public function testConnection()
	{
		if (!$this->_getApiKey() || !$this->_getArtistId())
			return false;

		$connectionTest = new Wadm_Feed_Abstract();
		$connectionTest->addUrlParameter('action', 'connectiontest');
		$connectionTest->setNotCacheable();

		try
		{
			$connectionTest->getData();
		}
		catch (Exception $e)
		{
			$notice = new Wadm_Admin_Notice(sprintf(__('<strong>Important: </strong> The Werk aan de Muur plugin can\'t connect to the outside world. Please make sure that your server is able to reach "%s".', Wadm::TEXT_DOMAIN), $connectionTest->getUrl()), 'error');
			$notice->add();

			return false;
		}

		return true;
	}

	/**
	 * Test if credentials are correct
	 */
	public function testAuthentication()
	{
		$authenticationTest = new Wadm_Feed_Abstract();
		$authenticationTest->addUrlParameter('action', 'authenticationtest');
		$authenticationTest->setNotCacheable();

		try
		{
			$authenticationTest->getData();
		}
		catch (Exception $e)
		{
			$notice = new Wadm_Admin_Notice(__('<strong>Important: </strong> Please check if your API key and Artist ID are correct. It looks like one of them is incorrect!', Wadm::TEXT_DOMAIN), 'error');
			$notice->add();

			return false;
		}

		return true;
	}

	/**
	 * Fetch API key from database or use result present
	 */
	protected function _getApiKey()
	{
		if (!isset($this->_apiKey))
			$this->_apiKey = get_option('wadm_api_key');

		return $this->_apiKey;
	}

	/**
	 * Fetch artist id from database or use result present
	 */
	protected function _getArtistId()
	{
		if (!isset($this->_artistId))
			$this->_artistId = get_option('wadm_artist_id');

		return $this->_artistId;
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
		 * defined in Wadm_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wadm_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wadm-admin.css', array(), $this->version, 'all' );

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
		 * defined in Wadm_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wadm_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wadm-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Create admin page
	 */
	public function add_options_page()
	{
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Werk aan de Muur settings', Wadm::TEXT_DOMAIN ),
			__( 'Werk aan de Muur', Wadm::TEXT_DOMAIN ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);
	}

	/**
	 * Actually print the admin page
	 */
	public function display_options_page()
	{
		include_once 'partials/wadm-admin-display.php';
	}

	/**
	 * Register settings to be added on admin page
	 */
	public function register_setting()
	{
		$generalSection = new Wadm_Admin_Section($this->plugin_name, 'connection_settings', __('Connection settings', Wadm::TEXT_DOMAIN));
		$generalSection->setDescription(__('Your personal API key and artist ID can be found on your <a href="http://www.werkaandemuur.nl/nl/Wordpress-plugin/474">Werk aan de Muur dashboard</a>. Please note you`ll have to be logged in to open this page.', Wadm::TEXT_DOMAIN));

		foreach ($this->_settings as $key => $options)
		{
			$generalSection->createSetting(__($options['name'], Wadm::TEXT_DOMAIN), $key, __($options['description'], Wadm::TEXT_DOMAIN), $options['validation']);
		}
	}
}