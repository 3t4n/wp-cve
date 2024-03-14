<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://jorcus.com/
 * @since      1.1.1
 *
 * @package    Lazyload_clarity
 * @subpackage Lazyload_clarity/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Lazyload_clarity
 * @subpackage Lazyload_clarity/admin
 * @author     Jorcus <support@jorcus.com>
 */
class Lazyload_clarity_Admin {

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

function lazyload_clarity_add_settings_page() {
    add_options_page('Lazy Load Clarity', 'Lazy Load Clarity', 'manage_options', 'lazyload-clarity-plugin', 'lazyload_clarity_plugin_settings_page');
}
add_action('admin_menu', 'lazyload_clarity_add_settings_page');


// Register WP_OPTIONS
function register_lazyload_clarity_settings() { 
    register_setting('lazyload_clarity_options', 'clarity_id');
}
add_action('admin_init', 'register_lazyload_clarity_settings');


function lazyload_clarity_plugin_settings_page() {
?>
    <h1>Lazy Load Clarity by <a target="_blank" title="Jorcus - Best Place for remote workers and digital nomads" href="https://jorcus.com/">Jorcus</a></h1>
	<h2>Welcome to Lazy Load Clarity Plugin Settings Page</h2>
	<p>Before you can start using our plugin to place Microsoft Clarity script, we need to take a few more steps.</p>
	<h2>Instructions</h2>
	<ol>
        <li>If you don't already have an clarity account, create an account <a target="_blank" title="Microsoft Clarity" href="https://clarity.microsoft.com/">here</a>.</li>
        <li>Once you logged into your account, you can "add new project" and "open the project".</li>
        <li>Your current URL should be "https://clarity.microsoft.com/projects/view/YOUR_CLARITY_ID/dashboard".</li>
        <li>You will see your own "YOUR_CLARITY_ID" from the URL, copy and paste into the input box below.</li>
    </ol>

    <form action="options.php" method="post">
			<?php
				settings_fields('lazyload_clarity_options');
				do_settings_sections('lazyload_clarity_options');
			?>
		<table class="form-table">
			<tr valign="top">
			<th scope="row">Microsoft Clarity ID:</th>
			<td><input type="text" name="clarity_id" placeholder="PLACE YOUR CLARITY ID" value="<?php echo esc_attr( get_option('clarity_id') ); ?>" /></td>
			</tr>
		</table>
		<span>Note: Leave it blank to disable Microsoft Clarity.</span>
		<?php submit_button(); ?>
    </form>

	<h3>Love the Plugin?</h3>
	<ol>
		<li>Leave a <a href="https://wordpress.org/support/plugin/lazy-load-clarity/reviews/#new-post"><b>5 STARS - ⭐⭐⭐⭐⭐ Review</b></a> to us!
        <li>You can always <a href="https://jorcus.com/product/buy-me-a-coffee/">buy me a coffee!</a></li>
		<li>Thanks for your support, please consider hiring us to maintain your WP site through our <a href="https://jorcus.com/product/wp-care-plans/">WP Care Plans</a>.</li>
		<li>Hope you enjoyed the plugin!</li>
    </ol>
    <?php
}	
?>