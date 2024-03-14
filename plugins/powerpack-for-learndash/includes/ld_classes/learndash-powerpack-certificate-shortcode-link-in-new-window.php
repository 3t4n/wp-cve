<?php
/**
 * Certificate Shortcode Link in New Window
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Certificate_Shortcode_Link_In_New_Window', false ) ) {
	/**
	 * LearnDash_PowerPack_Certificate_Shortcode_Link_In_New_Window Class.
	 */
	class LearnDash_PowerPack_Certificate_Shortcode_Link_In_New_Window {
		/**
		 * Current class name
		 *
		 * @var string
		 */
		public $current_class = '';

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->current_class = get_class( $this );

			if ( learndash_powerpack_is_current_class_active( $this->current_class ) === 'active' ) {
				add_filter( 'learndash_certificate_html', [ $this, 'learndash_certificate_html_func' ] );
			}
		}

		/**
		 * Creates the HTML for the certificate button.
		 *
		 * @param string $cert_button_html Certificate button HTML.
		 *
		 * @return string Certificate button HTML.
		 */
		public function learndash_certificate_html_func( $cert_button_html ) {
			$find    = '<a href=';
			$replace = '<a target="_blank" href=';

			$cert_button_html = str_replace( $find, $replace, $cert_button_html );

			return $cert_button_html;
		}

		/**
		 * Add class details.
		 *
		 * @return array Class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'certificate', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Certificate shortcode link to open in new window', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option to open certificate shortcode link in new window', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Certificate_Shortcode_Link_In_New_Window();
}
