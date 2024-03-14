<?php
/**
 * Framework license fields file.
 *
 * @link https://shapedplugin.com
 * @since 3.0.0
 *
 * @package team-free
 * @subpackage team-free/framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'TEAMFW_Field_license' ) ) {
	/**
	 *
	 * Field: license
	 *
	 * @since 3.3.16
	 * @version 3.3.16
	 */
	class TEAMFW_Field_license extends TEAMFW_Fields {

		/**
		 * Field constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {

			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * Render
		 *
		 * @return void
		 */
		public function render() {
			echo wp_kses_post( $this->field_before() );
			?>
			<div class="sp-team-lite-license text-center">
				<h3><?php esc_html_e( 'You\'re using WP Team Lite - No License Needed. Enjoy', 'team-free' ); ?>! 🙂</h3>
				<p><?php esc_html_e( 'Upgrade to WP Team Pro and unlock all the features.', 'team-free' ); ?></p>
				<div class="sp-team-lite-license-area">
					<div class="sp-team-upgrade-button">
					<b><a href="https://getwpteam.com/pricing/?ref=1" target="_blank"><?php esc_html_e( 'Upgrade To Pro Now', 'team-free' ); ?></a></b>
					</div>
				</div>
			</div>
			<?php
			echo wp_kses_post( $this->field_after() );
		}

	}
}
