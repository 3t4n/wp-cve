<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://jorcus.com/
 * @since      1.2.1
 *
 * @package    Lazyload_adsense
 * @subpackage Lazyload_adsense/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Lazyload_adsense
 * @subpackage Lazyload_adsense/admin
 * @author     Jorcus <support@jorcus.com>
 */
class Lazyload_adsense_Admin {

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
}

function lazyload_adsense_add_settings_page() {
    add_options_page('Lazy Load Adsense', 'Lazy Load Adsense', 'manage_options', 'lazyload-adsense-plugin', 'lazyload_adsense_plugin_settings_page');
}
add_action('admin_menu', 'lazyload_adsense_add_settings_page');


// Register WP_OPTIONS
function register_lazyload_adsense_settings() { 
    register_setting('lazyload_adsense_options', 'adsense_id');
}
add_action('admin_init', 'register_lazyload_adsense_settings');


function lazyload_adsense_plugin_settings_page() {
?>
    <h1>Lazy Load AdSense by <a target="_blank" title="Jorcus - Best Place for remote workers and digital nomads" href="https://jorcus.com/">Jorcus</a></h1>
	<h2>Welcome to Lazy Load AdSense Plugin Settings Page</h2>
	<p>Before you can start using our plugin to place Google AdSense script, we need to take a few more steps.</p>
	<h2>Instructions</h2>
	<ol>
        <li>If you don't already have an AdSense account, create an account <a target="_blank" title="Google AdSense" href="https://www.google.com/adsense/start/">here</a>.</li>
        <li>Once you logged into your account, go to "Sites -> Add site", make sure the domain of the site where you want to place the ad has been added and approved. (It might take you about a few days to a few weeks to get your AdSense approved.)</li>
        <li>If your AdSense is approved and shown "Ready". Go to "Ads -> Overview -> By site". Click on a link called "Get code".</li>
        <li>You will see the "data-ad-client", copy the "ca-pub-XXXXXXXXXXXXXXXX", then paste into the input box below. (Without the Quotation mark)</li>
    </ol>
    <form action="options.php" method="post">
		<?php
			settings_fields('lazyload_adsense_options');
			do_settings_sections('lazyload_adsense_options');
		?>

		<table class="form-table">
			<tr valign="top">
			<th scope="row">Google Adsense ID:</th>
			<td><input type="text" name="adsense_id" placeholder="ca-pub-0123456789123456" value="<?php echo esc_attr( get_option('adsense_id') ); ?>" /></td>
			</tr>
		</table>
		<span>Note: Leave it blank to disable Google AdSense.</span>

		<?php submit_button(); ?>
    </form>

	<h3>Love the Plugin?</h3>
	<ol>
        <li>Leave a <a href="https://wordpress.org/support/plugin/lazy-load-adsense/reviews/#new-post"><b>5 STARS - ⭐⭐⭐⭐⭐ Review</b></a> to us!
        <li>You can always <a href="https://jorcus.com/product/buy-me-a-coffee/">buy me a coffee!</a></li>
		<li>Thanks for your support, please consider hiring us to maintain your WP site through our <a href="https://jorcus.com/product/wp-care-plans/">WP Care Plans</a>.</li>
		<li>Hope you enjoyed the plugin!</li>
    </ol>
    <?php
}	
?>