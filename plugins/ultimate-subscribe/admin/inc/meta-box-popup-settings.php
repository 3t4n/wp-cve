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
 * Ultimate_Subscribe_Popup_Settings Class.
 */
class Ultimate_Subscribe_Popup_Settings {

	/**
	 * Output the metabox.
	 *
	 * @param WP_Post $post
	 */
	public static function output( $post ) {

		$popup_enable		= get_post_meta($post->ID, 'ultimate_subscribe_form_popup_enable', true);
		$settings			= get_post_meta($post->ID, 'ultimate_subscribe_form_popup_settings', true);
		$popup_delay 		= isset($settings['popup_delay'])?$settings['popup_delay']:5;
		wp_nonce_field('ultimate_subscribe_save_meta_data_nonce', 'ultimate_subscribe_nonce');

		?>
		<div id="form-settings-container" class="ultimate-subscribe-meta-fields">
			<div class="meta-heading" style="text-align:center;"><?php _e('Popup Settings', 'ultimate-subscribe');?></div>
			<div class="meta-forms">
				<div class="field-row">
					<div class="field-label"> <?php _e('On/Off PopUp', 'ultimate-subscribe'); ?> : </div>
					<div class="field-data">
						<div class="checkbox">
							<input type="checkbox" value="1" id="popup_enable" name="popup_enable" <?php checked($popup_enable, 1, true); ?>/>
							<label for="popup_enable"></label>
						</div>
						<span class="description"><?php _e('Enable/Disable Subcribe PopUp', 'ultimate-subscribe'); ?></span>
					</div>
				</div>
				<div class="field-row">
					<div class="field-label"> <?php _e('PopUp Open Delay', 'ultimate-subscribe'); ?> </div>
					<div class="field-data">
						<input type="number" value="<?php echo esc_attr($popup_delay); ?>" id="popup_delay" name="popup_delay" /> <?php _e('Seconds', 'ultimate-subscribe'); ?>
					</div>
				</div>
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
		$popup_enable				= isset($_POST['popup_enable'])?absint($_POST['popup_enable']):0;
		$settings['popup_delay'] 		= absint($_POST['popup_delay']);
		$settings['popup_width'] 		= absint($_POST['popup_width']);
		update_post_meta( $post_id, 'ultimate_subscribe_form_popup_enable', $popup_enable);
		update_post_meta( $post_id, 'ultimate_subscribe_form_popup_settings', $settings);
	}
}
