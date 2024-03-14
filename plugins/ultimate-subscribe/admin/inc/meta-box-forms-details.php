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
 * Ultimate_Subscribe_Forms_Details Class.
 */
class Ultimate_Subscribe_Forms_Details {

	/**
	 * Output the metabox.
	 *
	 * @param WP_Post $post
	 */
	public static function output( $post ) {

		$details 			= get_post_meta($post->ID, 'ultimate_subscribe_form_details', true);
		$design_id 			= (isset($details['design_id']) && !empty($details['design_id']))?$details['design_id']:1;
		
		wp_nonce_field('ultimate_subscribe_save_meta_data_nonce', 'ultimate_subscribe_nonce');
		?>
		<div id="form-details-container" class="ultimate-subscribe-meta-fields">
			<div class="meta-heading" style="text-align:center;"><?php _e('Select Form Style', 'ultimate-subscribe');?></div>
			<div class="meta-forms">
				<label>
					<input type="radio" name="design_id" value="1" <?php checked(absint($design_id), '1', true); ?> id="design_id_1" class="from-field form-details-radio">
					<img src="<?php echo esc_url(ULTIMATE_SUBSCRIBE_URI.'/admin/assets/images/form-template-1.png'); ?>">
				</label>
				<label>
					<input type="radio" name="design_id" value="2" <?php checked(absint($design_id), '2', true); ?> id="design_id_2" class="from-field form-details-radio">
					<img src="<?php echo esc_url(ULTIMATE_SUBSCRIBE_URI.'/admin/assets/images/form-template-2.png'); ?>">
				</label>
			</div>
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
		$details['design_id']				= absint($_POST['design_id']);
		update_post_meta( $post_id, 'ultimate_subscribe_form_details', $details );
	}
}
