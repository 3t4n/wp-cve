<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://jorcus.com/
 * @since      1.2.0
 *
 * @package    Lazyload_adsense
 * @subpackage Lazyload_adsense/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Lazyload_adsense
 * @subpackage Lazyload_adsense/public
 * @author     Jorcus <support@jorcus.com>
 */
class Lazyload_adsense_Public {

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
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.1.0
	 */
	public function enqueue_scripts() {
		if (get_option('adsense_id')){
			add_action('wp_footer', 'adsense_script'); 
		}
		

		function adsense_script() { 
			?>
			<script>
				!function(){const e=document.createElement("script");e.async=!0,e.setAttribute("crossorigin","anonymous"),e.src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=<?php echo esc_attr( get_option('adsense_id') ); ?>";let t=0;document.addEventListener("mousemove",function(){1==++t&&document.getElementsByTagName("HEAD").item(0).appendChild(e)}),window.onscroll=function(n){1==++t&&document.getElementsByTagName("HEAD").item(0).appendChild(e)},setTimeout(function(){0===t&&(t++,document.getElementsByTagName("HEAD").item(0).appendChild(e))},5e3)}();
			</script>
			<?php
			;
		}
	}

}