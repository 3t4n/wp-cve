<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://zeitwesentech.com
 * @since      1.0.0
 *
 * @package    Zwt_wp_linkpreviewer
 * @subpackage Zwt_wp_linkpreviewer/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Zwt_wp_linkpreviewer
 * @subpackage Zwt_wp_linkpreviewer/public
 * @author     zeitwesentech <sayhi@zeitwesentech.com>
 */
class Zwt_wp_linkpreviewer_Public {

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
		 * defined in Zwt_wp_linkpreviewer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Zwt_wp_linkpreviewer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/zwt_wp_linkpreviewer-public.css', array(), $this->version, 'all' );

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
		 * defined in Zwt_wp_linkpreviewer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Zwt_wp_linkpreviewer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/zwt_wp_linkpreviewer-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Hide any blocks from output if the plugin is deactivated
	 *
	 * @since    1.0.0
	 */
	public function zwt_hide_deactivated_plugin_content( $content ) {
		if (Zwt_wp_linkpreviewer_Utils::getOptionValue(Zwt_wp_linkpreviewer_Constants::$KEY_ENABLED) != 1 && strlen($content) > 0) {
			$dom = new DOMDocument;
			$dom->loadHTML($content);
			$xpath = new DOMXpath($dom);
			$nodes = $xpath->query("//div[contains(@class, 'wp-block-zwt-beautiful-link-preview')]");
			foreach($nodes as $div) {
				$div->parentNode->removeChild($div);
			}

			return $dom->saveHTML();
		}

		return $content;
	}


}
