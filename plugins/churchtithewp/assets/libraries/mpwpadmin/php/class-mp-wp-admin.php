<?php
/**
 * MPWPADMIN
 *
 * @package     MPWPADMIN
 * @subpackage  Classes
 * @copyright   Copyright (c) 2018, MPWPADMIN
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * MP_WP_Admin Class
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'MP_WP_Admin' ) ) {
	/**
	 * MP_WP_Admin Class
	 *
	 * @since 1.0.0
	 */
	class MP_WP_Admin {

		/**
		 * The unique arguments that create the admin component
		 *
		 * @since 1.0.0
		 * @var array
		 */
		private $the_args = null;

		/**
		 * The settings and views array which is output to the JS.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		private $settings_and_views = null;

		/**
		 * Get things going
		 *
		 * @since      1.0.0
		 * @param array $args The args unique to this mpwpadmin.
		 */
		public function __construct( $args ) {

			$this->the_args = $args;

			// The admin menu button for this admin component.
			add_action( 'admin_menu', array( $this, 'menu_button' ) );

			// Enqueue the admin scripts for mpwpadmin.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'admin_init', array( $this, 'admin_initializer' ) );

		}

		/**
		 * Create the menu button and page in wp-admin for this mpwpadmin
		 *
		 * @since    1.0.0
		 * @return   void
		 */
		public function menu_button() {

			$svg_icon = $this->the_args['svg_icon'];

			$icon_url = 'data:image/svg+xml;base64,' . base64_encode( $svg_icon ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode

			add_menu_page(
				$this->the_args['visual_name'],
				$this->the_args['visual_name'],
				$this->the_args['required_permissions'],
				sanitize_title( $this->the_args['visual_name'] ),
				array( &$this, 'page_output' ),
				$icon_url,
				$this->the_args['priority']
			);

		}

		/**
		 * The vallback function passed to add_menu_page in the menu_button method of this class
		 *
		 * @since    1.0.0
		 * @return   void
		 */
		public function page_output() {

			$class_name = 'mpwpadmin-' . sanitize_title( $this->the_args['visual_name'] ) . '-admin-component';

			// This is all we output from PHP. The rest is handled through React JS.
			?><div class="wrap">
				<div class="mpwpadmin-settings-title-area">
					<span class="mpwpadmin-settings-icon">
						<img src="<?php echo esc_attr( esc_url( $this->settings_and_views['general_config']['default_icon'] ) ); ?>" />
					</span>
					<span class="mpwpadmin-settings-title"><h2><?php echo esc_textarea( $this->the_args['visual_name'] ); ?></h2></span>
				</div>
				<div class="<?php echo esc_attr( $class_name ); ?>"><?php echo esc_textarea( $this->the_args['loading_text'] ); ?></div>
			</div>
			<?php
		}

		/**
		 * Enqueue the scripts for mpwpadmin in the wp-admin area
		 *
		 * @since    1.0.0
		 * @return   array
		 */
		public function admin_enqueue_scripts() {

			// If we are not on the page in question, don't enqueue the mpwpadmin scripts.
			if ( ! isset( $_GET['page'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return;
			}

			if ( sanitize_title( $this->the_args['visual_name'] ) !== $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return;
			}

			// MPWPAdmin pages do not use admin_notices for anything. Remove them all on this page.
			remove_all_actions( 'admin_notices' );

			wp_enqueue_media();

			// Use minified libraries if SCRIPT_DEBUG is turned off.
			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '.development' : '.production.min';

			$required_js_files = array(
				'react',
				'react-dom',
			);

			// Validation Functions.
			$validation_functions_slug = 'mpwpadmin_' . str_replace( '-', '_', sanitize_title( $this->the_args['visual_name'] ) ) . '_validation_functions';
			wp_enqueue_script( $validation_functions_slug, $this->the_args['validation_functions'], $required_js_files, $this->the_args['version'], true );
			$required_js_files[] = $validation_functions_slug;

			// Main Admin React Component.
			wp_enqueue_script( 'mpwpadmin_main', $this->the_args['plugin_url'] . 'assets/libraries/mpwpadmin/js/build/mpwpadmin.js', $required_js_files, $this->the_args['version'], true );
			$required_js_files[] = 'mpwpadmin_main';

			// Action hook to enqueue additional scripts that the initializer will require.
			do_action( 'mpwpadmin_enqueue_scripts', $required_js_files );

			// Filter hook to modify the files required.
			$required_js_files = apply_filters( 'mpwpadmin_required_js_files', $required_js_files );

			$url_for_intializer = add_query_arg(
				array(
					'mpwpadmin-' . sanitize_title( $this->the_args['visual_name'] ) => 1,
				),
				admin_url()
			);

			$initialize_js_slug = 'mpwpadmin_' . str_replace( '-', '_', sanitize_title( $this->the_args['visual_name'] ) ) . '_initializer';

			// The script which initializes the main react component. The admin_initializer() method in this class handles the output of the JS file.
			wp_enqueue_script(
				$initialize_js_slug,
				$url_for_intializer,
				$required_js_files,
				$this->the_args['version'],
				true
			);
			$required_js_files[] = $initialize_js_slug;

			$this->settings_and_views = $this->the_args['settings_and_views']();

			wp_localize_script( $initialize_js_slug, $initialize_js_slug . '_vars', $this->settings_and_views );

			// Load the styles for the admin settings.
			wp_enqueue_style( 'mpwpadmin_skin', $this->the_args['plugin_url'] . 'assets/libraries/mpwpadmin/css/style.css', false, $this->the_args['version'] );

		}

		/**
		 * Create a JS file on-the-fly which initializes the react component for mpwpadmin
		 *
		 * @since    1.0.0
		 * @return   array
		 */
		public function admin_initializer() {

			$dashed_slug = 'mpwpadmin-' . sanitize_title( $this->the_args['visual_name'] );

			// If the page being loaded is NOT the js needed to initialize the react admin component for this class.
			if ( ! isset( $_GET[ $dashed_slug ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return false;
			}

			$lowercase_slug                           = str_replace( '-', '_', $dashed_slug );
			$class_name                               = '.' . $dashed_slug . '-admin-component';
			$exists_variable_name                     = $lowercase_slug . '_admin_exists';
			$all_relevant_html_elements_variable_name = $lowercase_slug . '_components';
			$initialize_js_slug                       = 'mpwpadmin_' . str_replace( '-', '_', sanitize_title( $this->the_args['visual_name'] ) ) . '_initializer';

			header( 'Content-Type: text/javascript' );

			ob_get_clean();
			ob_start();
			?>

			var settings_and_views = <?php echo esc_attr( $initialize_js_slug ) . '_vars'; ?>;

			window.<?php echo esc_attr( $lowercase_slug ); ?>_refresh_all_admins = function <?php echo esc_attr( $lowercase_slug ); ?>_refresh_all_admins(){
				var <?php echo esc_attr( $exists_variable_name ); ?> = document.querySelector( '<?php echo esc_attr( $class_name ); ?>' );
				if ( <?php echo esc_attr( $exists_variable_name ); ?> ) {

					var <?php echo esc_attr( $all_relevant_html_elements_variable_name ); ?> = document.querySelectorAll( '<?php echo esc_attr( $class_name ); ?>' );

					<?php echo esc_attr( $all_relevant_html_elements_variable_name ); ?>.forEach(function( element_in_question ) {

						ReactDOM.render(
							React.createElement(MP_WP_Admin, {
								data: settings_and_views,
							}, null),
							element_in_question
						);

					});

				}
			}

			<?php echo esc_attr( $lowercase_slug ); ?>_refresh_all_admins();
			<?php

			// Output the js code, which is what will initialize the React Component for mpwpadmin.
			// Since all of the code is generated here without filters, it is known what the output will be.
			echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			die();
		}

	}
}

if ( ! function_exists( 'mpwpadmin_get_current_visual_state_of_mpwpadmin' ) ) {
	/**
	 * Check the URL variables and set up the array containing the current visual state of mpwpadmin
	 *
	 * @since    1.0.0
	 * @return   array
	 */
	function mpwpadmin_get_current_visual_state_of_mpwpadmin() {

		$mpwpadin_url_variables = array();

		// If this is a bookmarked URL. Nonce check not needed here as this is not a user-submitted form.
		foreach ( $_GET as $url_variable => $url_variable_value ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			// Skip any URL vars that aren't relevant to mpwpadmin.
			if ( false === strpos( $url_variable, 'mpwpadmin' ) || true === strpos( $url_variable, 'mpwpadmin_lightbox' ) ) {
				continue;
			}

			$visual_state_key                            = sanitize_text_field( $url_variable );
			$visual_state_value                          = sanitize_text_field( $url_variable_value );
			$mpwpadin_url_variables[ $visual_state_key ] = $visual_state_value;

		}

		// Level 1 - Eventually we'll make this more robust, but for now 3 levels is as deep as has been needed.
		if ( isset( $mpwpadin_url_variables['mpwpadmin1'] ) ) {
			$all_current_visual_states = array();
			$all_current_visual_states[ $mpwpadin_url_variables['mpwpadmin1'] ] = array();
			// Level 2.
			if ( isset( $mpwpadin_url_variables['mpwpadmin2'] ) ) {
				$all_current_visual_states[ $mpwpadin_url_variables['mpwpadmin1'] ][ $mpwpadin_url_variables['mpwpadmin2'] ] = array();
				// Level 3.
				if ( isset( $mpwpadin_url_variables['mpwpadmin3'] ) ) {
					$all_current_visual_states[ $mpwpadin_url_variables['mpwpadmin1'] ][ $mpwpadin_url_variables['mpwpadmin2'] ][ $mpwpadin_url_variables['mpwpadmin3'] ] = array();
				}
			}
		} else {
			$all_current_visual_states = array(
				'welcome' => array(),
			);
		}

		return $all_current_visual_states;

	}
}

if ( ! function_exists( 'mpwpadmin_set_lightbox_visual_state_of_mpwpadmin' ) ) {
	/**
	 * Check the URL variables and set up the array containing the current visual state of the lightbox for mpwpadmin
	 *
	 * @since    1.0.0
	 * @return   array
	 */
	function mpwpadmin_set_lightbox_visual_state_of_mpwpadmin() {

		// Sanitize the possible lightbox data in the URL.
		if ( isset( $_GET['mpwpadmin_lightbox'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$lightbox_slug = sanitize_text_field( wp_unslash( $_GET['mpwpadmin_lightbox'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			// Pass the URL lightbox vars to the React vars, resulting in a pre-opened lightbox.
			$lightbox_visual_state = array(
				$lightbox_slug => array(),
			);

			// If there's no lightbox data in the URL, set the lightbox visual state to be off for the react vars.
		} else {
			$lightbox_visual_state = false;
		}

		return $lightbox_visual_state;

	}
}
