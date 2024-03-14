<?php
/**
 * Form Details
 *
 * Display the subscribe Form details meta box.
 *
 * @author      ThemeFarmer
 * @category    Admin
 * @package     UltimateSubscribe/Admin/Meta Boxes
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Ultimate_Subscribe_Forms_Info Class.
 */
class Ultimate_Subscribe_Forms_Info {

	/**
	 * Output the metabox.
	 *
	 * @param WP_Post $post
	 */
	public static function output( $post ) {

		?>
		<div id="form-info-container" class="ultimate-subscribe-meta-fields">
			<div class="meta-heading" style="text-align:center;"><?php _e('Shortcode', 'ultimate-subscribe');?></div>
			<div class="u-shortcoad-col"> [ultimate_subscribe_from id="<?php echo absint($post->ID); ?>"] </div>
		</div>
		<?php
	}

	/**
	 * Save meta box data.
	 *
	 * @param int $post_id
	 * @param WP_Post $post
	 */
	public static function save( $post_id, $post ) {
		
	}
}
