<?php
use platy\etsy\NoSuchListingException;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       inon_kaplan
 * @since      1.0.0
 *
 * @package    Platy_Syncer_Etsy
 * @subpackage Platy_Syncer_Etsy/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Platy_Syncer_Etsy
 * @subpackage Platy_Syncer_Etsy/public
 * @author     Inon Kaplan <inonkp@gmail.com>
 */
class Platy_Syncer_Etsy_Public {

	private const POWERED_BY_LINKS =[
		"<a href='https://platycorp.com'>Etsy Woocommerce Integration</a> by &copy;PlatyCorp",
		"<a href='https://platycorp.com'>Woocommerce Etsy Integration</a> by &copy;PlatyCorp",
		"<a href='https://platycorp.com'>Etsy Woocommerce</a> Integration by &copy;PlatyCorp",
		"<a href='https://platycorp.com'>Woocommerce Etsy</a> Integration by &copy;PlatyCorp",
	];

	private $syncer;

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
	public function __construct( $plugin_name, $version, $syncer ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->syncer = $syncer;
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
		 * defined in Platy_Syncer_Etsy_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Platy_Syncer_Etsy_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/platy-syncer-etsy-public.css', array(), $this->version, 'all' );

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
		 * defined in Platy_Syncer_Etsy_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Platy_Syncer_Etsy_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/platy-syncer-etsy-public.js', array( 'jquery' ), $this->version, false );

	} 

	public function add_powered_by_footer() {
		global $product;
		$id = $product->get_id();
		$link = self::POWERED_BY_LINKS[$id % sizeof(self::POWERED_BY_LINKS)];
		echo "<div class='platycorp-reference'><i>$link</i></div>";
	}

	public function add_etsy_link_to_description( $content ) { 
		global $post;
		$post_id = $post->ID;
		$listing_id = 0;
		try {
			$listing_id = $this->syncer->get_etsy_product_data($post_id)['etsy_id'];
		}catch(NoSuchListingException $e) {
			return $content;
		}
		$template = $this->syncer->get_option('etsy_public_product_link_template', "");
		$etsy_link = "https://www.etsy.com/listing/$listing_id/";
		
        $etsy_logo_url = PLATY_SYNCER_ETSY_DIR_URL . 'assets/images/etsy_full_logo.png';
        $logo = "<a href='$etsy_link' target='_blank'><img src=$etsy_logo_url></a>";
		$html = str_replace('%etsy-logo%', $logo, $template);
		$html = str_replace('%etsy-url%', $etsy_link, $html);
		$content.= "<br>$html";
		return $content;
	}

}
