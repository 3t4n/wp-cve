<?php
/**
 * Restrict access to certificates
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'LearnDash_PowerPack_Restrict_Access_To_Certificates', false ) ) {
	/**
	 * LearnDash_PowerPack_Restrict_Access_To_Certificates Class.
	 */
	class LearnDash_PowerPack_Restrict_Access_To_Certificates {
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
				add_action( 'template_redirect', [ $this, 'template_redirect_func' ], 4 );
			}
		}

		/**
		 * Redirects to home if the user is not logged in.
		 */
		public function template_redirect_func() {
			if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
				remove_action( 'template_redirect', 'learndash_certificate_display', 5 );

				if ( ! is_singular( 'sfwd-certificates' ) ) {
					return;
				}

				esc_html_e( 'Access to certificate page is disallowed.', 'learndash-powerpack' );
				exit;
			}
		}

		/**
		 * Add class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'certificate', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Restrict Access to Certificates', 'learndash-powerpack' );
			$class_description = esc_html__( 'only allow admin ( users with manage_options capability ) to access certificates.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Restrict_Access_To_Certificates();
}

