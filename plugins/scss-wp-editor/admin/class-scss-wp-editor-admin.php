<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.itpathsolutions.com/
 * @since      1.0.0
 *
 * @package    Scss_Wp_Editor
 * @subpackage Scss_Wp_Editor/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Scss_Wp_Editor
 * @subpackage Scss_Wp_Editor/admin
 * @author     IT Path Solutions <info@itpathsolutions.com>
 */

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/scssphp/scss.inc.php';

use ScssPhp\ScssPhp\Compiler;

class Scss_Wp_Editor_Admin {

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
		 * defined in Scss_Wp_Editor_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Scss_Wp_Editor_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/scss-wp-editor-admin.css', array(), $this->version, 'all' );

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
		 * defined in Scss_Wp_Editor_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Scss_Wp_Editor_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/scss-wp-editor-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Enqueue SCSS editor block.
	 *
	 * @since    1.0.0
	 */
	public function swe_code_editor_enqueue_scripts( $hook ) {
	    $cm_settings['codeEditor'] = wp_enqueue_code_editor( array( 'type' => 'text/css' ) );

	    if ( false === $cm_settings['codeEditor'] ) {
	        return;
	    }

	    wp_localize_script( 'jquery', 'cm_settings', $cm_settings );
	    wp_enqueue_style( 'wp-codemirror' );
	}

	/**
	 * Created Admin Menu.
	 *
	 * @since    1.0.0
	 */
	public function swe_admin_menu() {
	    add_options_page(
	        __( 'WP SCSS Editor', 'scss-wp-editor' ),
	        __( 'WP SCSS', 'scss-wp-editor' ),
	        'manage_options',
	        'scss-wp-editor',
	        array( &$this, 'swe_editor_page' )
	    );
	}

	/**
	 * Admin Menu Page Content.
	 *
	 * @since    1.0.0
	 */
	public function swe_editor_page() {

	    if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

			$scssVal = sanitize_textarea_field( stripslashes( $_POST['scss-val'] ) );
			update_option( 'swe_scss_box_value', $scssVal );

			$compiledCss = $this->swe_scss_compiler( $scssVal );
			$compiledCss = html_entity_decode( $compiledCss );

			$uploadsDir = trailingslashit( wp_upload_dir()['basedir'] ) . 'scss-wp';

			if ( ! file_exists( $uploadsDir ) ) {
				wp_mkdir_p( $uploadsDir );
			}

        	$upload = wp_get_upload_dir();
			$uploadFile = $upload['basedir'] . '/scss-wp/compiled-scss.css';

			if ( is_object( $compiledCss ) ) {

				update_option( 'swe_box_value_status', 'fail' );

			    file_put_contents( $uploadFile, '' );

			} else { 

				// Success
				update_option( 'swe_box_value_status', 'success' );
				file_put_contents( $uploadFile, html_entity_decode( $compiledCss, ENT_QUOTES, 'cp1251') );
				
				/* Success Notice */
				$this->swe_notice( 'SCSS Compiled & Saved Successfully.', 'success' );

			}

		}

		$val = get_option( 'swe_scss_box_value' ) ? get_option( 'swe_scss_box_value' ) : ''; ?>

		<div class="wrap">
		    <h3>SCSS WP Editor Options: </h3>
		    <div class="scss-block-wrap">
		    	<div class="scss-block-left">
				    <form action="" method="post">
				    	<div class="swp-tabs">
							<ul id="tabs">
								<li data-tab="#tab-01" class="current">SCSS</li>
								<li data-tab="#tab-02">Support</li>
							</ul>
							<div class="tab-content current" id="tab-01">
								<div class="swp-editor-block">
									<h4>Add Your SCSS/CSS Here: <span class="dashicons dashicons-editor-code"></span></h4>
									<textarea id="fancy-textarea" name="scss-val"><?php echo esc_textarea( $val ); ?></textarea>
								</div>
							</div>
							<div class="tab-content" id="tab-02">
								<table class="form-table">
									<tbody>
										<tr>
											<th scope="row">Give us a review:</th>
											<td>
												<p class="description">
													If you like the plugin and helps you, give us a <a href="https://wordpress.org/support/plugin/scss-wp-editor/reviews/#new-post" target="_blank">review</a>
													<?php
														for ( $i = 1; $i <= 5 ; $i++ ) { 
															echo '<span class="dashicons dashicons-star-filled"></span>';
														}
													?>
												</p>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					    <input type="submit" name="submit" value="Save Changes" class="button-primary">
				    </form>
			    </div>
			    <div class="scss-block-right">
			    	<?php require_once plugin_dir_path( __FILE__ ) . 'partials/scss-wp-editor-admin-sidebar.php'; ?>
			    </div>
		    </div>
	    </div>

		<?php
	}

	/**
	 * Notice.
	 *
	 * @since    1.0.1
	 */
	public function swe_notice( $noticeText, $type ) {
		echo '<div class="wrap">
				<div class="notice notice-' . esc_html( $type ) . ' is-dismissible">
					<p>' . esc_html( $noticeText ) . '</p>
				</div>
			</div>';
	}

	/**
	 * Minified the CSS.
	 *
	 * @since    1.0.0
	 * @return 	 string Minimize CSS	
	 */
	public function swe_minimize_css( $css ) {
		$css = preg_replace('/\/\*((?!\*\/).)*\*\//', '', $css);
		$css = preg_replace('/\s{2,}/', ' ', $css);
		$css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
		$css = preg_replace('/;}/', '}', $css);

		return trim( $css );
	}

	/**
	 * SCSS Compiler Function.
	 *
	 * @since    1.0.0
	 * @return   Minimize CSS or Error
	 */
	public function swe_scss_compiler( $scss ) {
		$log_file = plugin_dir_path( __DIR__ ) . 'logs/error_log.log';
		$compiler = new Compiler();

		try {
			$compiler->compileString($scss)->getCss();
			update_option( 'swe_box_value_status', 'success' );

			$compiledCss =  $compiler->compileString( $scss )->getCss();
			$minifiedCss = $this->swe_minimize_css( $compiledCss );
			return $minifiedCss;

		} catch (\Exception $e) {

			$errorMessage = $this->access_protected( $e, 'message' );
			$this->swe_notice( $errorMessage, 'error' );

		    return $e;
		}
	}

	/**
	 * Access Protected Value.
	 *
	 * @since    1.0.1
	 */
	public function access_protected( $obj, $prop ) {
		$reflection = new ReflectionClass( $obj );
		$property = $reflection->getProperty( $prop );
		$property->setAccessible( true );

		return $property->getValue( $obj );
	}

	/**
	 * Add Setting Page.
	 *
	 * @since    1.0.0
	 * @return   array | Settings Link
	 */
	public function swe_add_setting_page_link( $links ) {
		$links[] = '<a href="' .
	        admin_url( 'options-general.php?page=scss-wp-editor' ) .
	        '">' . __('Settings') . '</a>';

	    return $links;
	}

}
