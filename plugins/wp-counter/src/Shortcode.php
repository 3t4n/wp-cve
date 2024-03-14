<?php
/**
 * Shortcode Register
 *
 * @package Haruncpi\WpCounter
 * @author Harun<harun.cox@gmail.com>
 * @link https://learn24bd.com
 * @since 1.2
 */

namespace Haruncpi\WpCounter;

/**
 * Shortcode Class
 *
 * @since 1.2
 */
class Shortcode {

	/**
	 * Register hooks
	 *
	 * @since 1.2
	 *
	 * @return void
	 */
	public function __construct() {
		add_shortcode( 'wpcounter', array( $this, 'render_wpcounter_shortcode' ) );
	}

	/**
	 * Register wpcounter shortcode
	 *
	 * @since 1.2
	 *
	 * @param array $atts attributes.
	 *
	 * @return void
	 */
	public function render_wpcounter_shortcode( $atts ) {
		$options = shortcode_atts( array( 'headline' => '' ), $atts );
		$data    = DB::get_visitor_data();

		ob_start();
		Utils::load_view(
			'shortcodes/wpcounter.php',
			array(
				'data'    => $data,
				'options' => $options,
			)
		);
		return ob_get_clean();
	}

}
