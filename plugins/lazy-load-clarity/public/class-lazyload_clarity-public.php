<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://jorcus.com/
 * @since      1.1.1
 *
 * @package    Lazyload_clarity
 * @subpackage Lazyload_clarity/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Lazyload_clarity
 * @subpackage Lazyload_clarity/public
 * @author     Jorcus <support@jorcus.com>
 */
class Lazyload_clarity_Public {

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
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if (get_option('clarity_id')){
			add_action('wp_footer', 'clarity_script'); 
		}
		

		function clarity_script() { 
			?>
			<script type="text/javascript">
				!function(){let t=0;function e(){var t,e,n,o,c;t=window,e=document,c="script",t[n="clarity"]=t[n]||function(){(t[n].q=t[n].q||[]).push(arguments)},(o=e.createElement(c)).async=1,o.src="https://www.clarity.ms/tag/<?php echo esc_attr( get_option('clarity_id') ); ?>",(c=e.getElementsByTagName(c)[0]).parentNode.insertBefore(o,c)}document.addEventListener("mousemove",function(){1==++t&&e()}),window.onscroll=function(){1==++t&&e()},setTimeout(function(){0==t&&e()},5e3)}();
			</script>
			<?php
			;
		}
	}

}