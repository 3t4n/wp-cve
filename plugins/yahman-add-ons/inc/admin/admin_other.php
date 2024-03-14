<?php
defined( 'ABSPATH' ) || exit;
/**
 * Admin Other Page
 *
 * @package YAHMAN Add-ons
 */
function yahman_addons_admin_other($option,$option_key,$option_checkbox){
	foreach ($option_key['other'] as $key => $value  ) {
		$other[$key] = $option['other'][$key];
	}
	foreach ($option_checkbox['other'] as $key => $value  ) {
		$other[$key] = isset($option['other'][$key]) ? true: false;
	}
	?>

	<div id="ya_other_content" class="tab_content ya_box_design">
		<h2><?php esc_html_e('Other','yahman-add-ons'); ?></h2>

		<div class="ya_setting_content">
			<div class="ya_tooltip_wrap">
				<label for="select_other_no_image">
					<?php esc_html_e('Substitute for eye catch','yahman-add-ons'); ?>
				</label>
				<div class="ya_tooltip">
					<?php esc_html_e('YAHMAN Add-ons use this image when no eye catch as necessary.','yahman-add-ons'); ?>
				</div>
			</div>
			<div class="other_no_image" style="width: 100%; max-width:320px; height:auto;">
				<div class="other_no_image_id_placeholder" style="width: 100%; position: relative; text-align: center; cursor: default;border: 1px dashed #b4b9be;box-sizing: border-box;padding: 9px 0;line-height: 20px; margin: 10px 0;<?php if( !empty( $other['no_image'] ) ){echo 'display:none;';} ?>"><?php esc_html_e( 'No image selected', 'yahman-add-ons' ); ?></div>
				<img class="other_no_image_id_media_image custom_media_image" src="<?php if( !empty( $other['no_image'] ) ){echo esc_url($other['no_image']);} ?>" style="width: 100%; max-width: 180px; height:auto; margin-bottom: 10px;" />

			</div>
			<input type="hidden" type="text" class="other_no_image_id_media_id custom_media_id" name="yahman_addons[other][no_image_id]" id="other_no_image_id" value="<?php echo esc_attr($other['no_image_id']); ?>" />

			<input type="hidden" type="text" class="other_no_image_id_media_url custom_media_url" name="yahman_addons[other][no_image]" id="other_no_image_url" value="<?php echo esc_url($other['no_image']); ?>" >
			<input type="button" value="<?php esc_html_e( 'Clear Image', 'yahman-add-ons' ); ?>" class="button other_no_image_id_remove-button custom_media_clear" data-media_clear="other_no_image_id" style="<?php if( !empty( $other['no_image'] ) ){echo 'display:inline-block;';}else{echo 'display:none;';} ?>" />
			<input id="select_other_no_image" type="button" value="<?php esc_html_e( 'Select Image', 'yahman-add-ons' ); ?>" class="button upload-button custom_media_upload" data-media_select="other_no_image_id"/>
		</div>

		<div class="ya_hr"></div>

		<div class="ya_setting_content">
			<div class="ya_tooltip_wrap">
				<label for="other_user_timing_api">
					<?php esc_html_e('User Timing API','yahman-add-ons'); ?>
				</label>
				<div class="ya_tooltip">
					<?php esc_html_e('It doesn\'t make much sense.','yahman-add-ons'); ?>
				</div>
			</div>
			<div class="ya_checkbox">
				<input name="yahman_addons[other][user_timing_api]" type="checkbox" id="other_user_timing_api"<?php checked(true, $other['user_timing_api']); ?> class="ya_checkbox" />
				<label for="other_user_timing_api"></label>
			</div>
		</div>

		<div class="ya_hr"></div>

		<div class="ya_setting_content">
			<div class="ya_tooltip_wrap">
				<label for="other_delete_all">
					<?php esc_html_e('Delete all data for YAHMAN Add-ons when uninstall','yahman-add-ons'); ?>
				</label>
				<div class="ya_tooltip">
					<?php esc_html_e('Good bye!','yahman-add-ons'); ?>
					<br>
					<?php esc_html_e('Till we meet again.','yahman-add-ons'); ?>
				</div>
			</div>
			<div class="ya_checkbox">
				<input name="yahman_addons[other][delete_all]" type="checkbox" id="other_delete_all"<?php checked(true, $other['delete_all']); ?> class="ya_checkbox" />
				<label for="other_delete_all"></label>
			</div>
		</div>


	</div>




	<?php
}
