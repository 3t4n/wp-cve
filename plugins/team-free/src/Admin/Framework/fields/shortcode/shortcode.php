<?php
/**
 * Framework Shortcode field file.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package team-free
 * @subpackage team-free/framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'TEAMFW_Field_shortcode' ) ) {
	/**
	 *
	 * Field: Shortcode
	 *
	 * @since 2.0
	 * @version 2.0
	 */
	class TEAMFW_Field_shortcode extends TEAMFW_Fields {

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
		 * Render field
		 *
		 * @return statement
		 */
		public function render() {
			$post_id = get_the_ID();

			if ( empty( $post_id ) ) {
				return 'Post ID not found.';
			}
			echo ( ! empty( $post_id ) ) ? '<div class="sptp-scode-wrap"><p>To display the Team, copy and paste this shortcode into your post, page, custom post, or block editor. <a href="https://getwpteam.com/docs/how-to-show-the-team-on-my-homepage-or-header-php-or-other-php-files/" target="_blank">Learn how</a> to include it in your template file.</p><span class="sptp-shortcode-selectable">[wpteam id="' . esc_attr( $post_id ) . '"]</span></div><div class="sptp-after-copy-text"><i class="fa fa-check-circle"></i> Shortcode Copied to Clipboard! </div>' : '';
		}
	}

}
