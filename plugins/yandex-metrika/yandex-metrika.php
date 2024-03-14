<?php
/**
 * Plugin Name: Yandex.Metrika
 * Description: Enable Yandex.Metrika website analytics on your WordPress site.
 * Author: Konstantin Kovshenin
 * Version: 0.8.4
 * License: GPLv2
 * Text Domain: metrika
 * Domain Path: /languages
 */

class Yandex_Metrika_Plugin {
	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'wp_footer', array( $this, 'wp_footer' ) );
	}

	function init() {
		$this->options = array_merge( array(
			'counter-code' => '',
		), (array) get_option( 'yandex-metrika', array() ) );

		load_plugin_textdomain( 'yandex-metrika', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	function admin_init() {
		register_setting( 'yandex-metrika', 'yandex-metrika', array( $this, 'sanitize' ) );
		add_settings_section( 'general', '', '', 'yandex-metrika' );
		add_settings_field( 'counter-code', __( 'Counter code', 'yandex-metrika' ), array( $this, 'field_counter_code' ), 'yandex-metrika', 'general' );
	}

	function sanitize( $input ) {
		$output = array();

		if ( isset( $input['counter-code'] ) )
			$output['counter-code'] = ( current_user_can( 'unfiltered_html' ) ) ? $input['counter-code'] : wp_kses_post( $input['counter-code'] );

		return $output;
	}

	function field_counter_code() {
		?>
		<textarea name="yandex-metrika[counter-code]" class="code large-text" rows="10"><?php echo esc_textarea( $this->options['counter-code'] ); ?></textarea>
		<p class="description"><?php _e( 'If you do not have a counter code, you can <a href="http://metrika.yandex.ru/">request one</a>.', 'yandex-metrika' ); ?>
		<?php
	}

	function admin_menu() {
		add_options_page( __( 'Yandex Metrika', 'yandex-metrika' ), __( 'Yandex Metrika', 'yandex-metrika' ), 'manage_options', 'yandex-metrika', array( $this, 'render_options' ) );
	}

	function render_options() {
		?>
		<div class="wrap">
	        <h2><?php _e( 'Yandex Metrika', 'yandex-metrika' ); ?></h2>
	        <p><?php _e( 'Please enter your Yandex Metrika counter code in the field below and click Save Changes.', 'yandex-metrika' ); ?>
	        <form action="options.php" method="POST">
	            <?php settings_fields( 'yandex-metrika' ); ?>
	            <?php do_settings_sections( 'yandex-metrika' ); ?>
	            <?php submit_button(); ?>
	        </form>
	    </div>
		<?php
	}

	function wp_footer() {
		if ( ! empty( $this->options['counter-code'] ) )
			echo $this->options['counter-code'];
	}
}
$GLOBALS['yandex_metrika_plugin'] = new Yandex_Metrika_Plugin;
