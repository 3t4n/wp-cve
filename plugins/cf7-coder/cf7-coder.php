<?php
/**
 * Plugin Name:       CF7 HTML Editor
 * Plugin URI:        https://wordpress.org/cf7-coder
 * Description:       Add HTML editor to Contact Form 7.
 * Version:           0.1
 * Author:            Wow-Company
 * Author URI:        https://wow-estore.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cf7-coder
 * Domain Path:       /languages
 *
 * PHP version 5.6.0
 *
 * @category    Wordpress_Plugin
 * @package     Wow_Plugin
 * @author      Dmytro Lobov <i@lobov.dev>
 * @copyright   2021 Wow-Company
 * @license     GNU Public License
 * @version     0.1
 */

if ( ! defined( "ABSPATH" ) ) {
	exit();
}

class CF7_Coder {
	public function __construct() {
		add_action( 'plugins_loaded', [ $this, "text_domain" ] );
		add_action( "admin_enqueue_scripts", [ $this, "style_script" ] );
		add_action( 'wpcf7_admin_footer', [ $this, 'add_sidebar' ] );
		add_action( 'wpcf7_admin_misc_pub_section', [ $this, 'wpcf7_add_test_mode' ] );
		add_filter( 'wpcf7_contact_form_properties', [ $this, 'wpcf7_add_properties' ] );
		add_action( 'wpcf7_save_contact_form', [ $this, 'wpcf7_save' ] );
		add_filter( 'do_shortcode_tag', [ $this, 'wpcf7_frontend' ], 10, 4 );
	}

	// Download the folder with languages
	public function text_domain() {
		$languages_folder = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
		load_plugin_textdomain( 'cf7-coder', false, $languages_folder );
	}

	// Include script and style in CF7 page
	public function style_script( $hook ) {
		$page_new = 'contact_page_wpcf7-new';
		$page     = 'toplevel_page_wpcf7';


		if ( $page == $hook || $page_new == $hook ) {

			$version = '0.1';

			wp_enqueue_script( 'code-editor' );
			wp_enqueue_style( 'code-editor' );
			wp_enqueue_script( 'htmlhint' );


			$url_style = plugin_dir_url( __FILE__ ) . 'assets/style.css';
			wp_enqueue_style( "coder-wpcf7", $url_style );

			$url_script = plugin_dir_url( __FILE__ ) . 'assets/script.js';
			wp_enqueue_script( "coder-wpcf7", $url_script, [ "jquery" ], $version, false );
		}

	}

	function add_sidebar() {
		?>
        <div id="informationdiv_coder" class="postbox" style="display:none">
            <h3>Some helpful information!</h3>
            <div class="inside">
                <p> You can use the next classes for change the Contact Form 7 style:</p>
                <ol>
                    <li><b>wpcf7</b> - for style of the form wrapper</li>
                    <li><b>wpcf7-form</b> - for form style</li>
                    <li><b>wpcf7-not-valid-tip</b> - field validation text</li>
                    <li><b>wpcf7-response-output</b> - send status message</li>

                </ol>
                <p><a href="<?php echo esc_url( wp_customize_url() ); ?>" class="button is-primary">Customizing CSS</a>
                </p>

            </div>
        </div>
		<?php
	}

	// Add checkbox 'Test Mode' in sidebar
	public function wpcf7_add_test_mode() {
		$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : '-1';
		$checked = get_post_meta( $post_id, '_wpcf7_test_mode', true );
		?>
        <div class="misc-pub-section">
            <label class="wpcf7-test-mode">
                <input value="1" type="checkbox" name="wpcf7-test-mode" <?php checked( $checked ); ?>>
				<?php esc_html_e( 'Test Mode', 'cf7-coder' ); ?>
            </label>
        </div>
		<?php
		$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : '-1';
		$checked = get_post_meta( $post_id, '_wpcf7_remove_auto_tags', true );
		?>
        <div class="misc-pub-section">
            <label class="wpcf7-remove-auto-tags">
                <input value="1" type="checkbox" name="wpcf7-remove-auto-tags" <?php checked( $checked ); ?>>
				<?php esc_html_e( 'Remove Auto tags p and br', 'cf7-coder' ); ?>
            </label>
        </div>
		<?php
	}

	// Add properties for form
	function wpcf7_add_properties( $properties ) {
		$more_properties = array(
			'wpcf7_test_mode'        => '',
			'wpcf7_remove_auto_tags' => '',
		);

		return array_merge( $more_properties, $properties );

	}

	// Save custom properties
	function wpcf7_save( $contact_form ) {

		$properties = $contact_form->get_properties();

		$properties['wpcf7_test_mode']        = isset( $_POST['wpcf7-test-mode'] ) ? '1' : '';
		$properties['wpcf7_remove_auto_tags'] = isset( $_POST['wpcf7-remove-auto-tags'] ) ? '1' : '';


		$contact_form->set_properties( $properties );

	}

	// Work with Frontend
	function wpcf7_frontend( $output, $tag, $atts, $m ) {

		if ( $tag === 'contact-form-7' ) {
			$remove_tags = get_post_meta( $atts['id'], '_wpcf7_remove_auto_tags', true );
			if ( ! empty( $remove_tags ) ) {
				$output = str_replace( array( '<p>', '</p>', '<br/>' ), '', $output );;
			}

			$test_mode = get_post_meta( $atts['id'], '_wpcf7_test_mode', true );
			if ( ! empty( $test_mode ) && ! current_user_can( 'administrator' ) ) {
				$output = '';
			}
		}

		return $output;
	}

}

add_action( 'plugins_loaded', 'wpcf7_coder_load', 999 );
function wpcf7_coder_load() {
	if ( ! class_exists( 'WPCF7' ) ) {
		require_once 'class.wpcf7coder-extension-activation.php';
		$activation = new wpcf7_Coder_Extension_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
		$activation = $activation->run();
		deactivate_plugins( plugin_basename( __FILE__ ) );
	} else {
		new CF7_Coder();
	}
}
