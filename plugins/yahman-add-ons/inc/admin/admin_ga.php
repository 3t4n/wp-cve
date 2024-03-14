<?php
defined( 'ABSPATH' ) || exit;
/**
 * Admin Google Analytics Page
 *
 * @package YAHMAN Add-ons
 */

function yahman_addons_admin_ga($option,$option_key,$option_checkbox){

	foreach ($option_key['ga'] as $key => $value  ) {
		$ga[$key] = $option['ga'][$key];
	}
	foreach ($option_checkbox['ga'] as $key => $value  ) {
		$ga[$key] = isset($option['ga'][$key]) ? true: false;
	}



  //$ga['api_json'] = isset($option['ga']['api_json']) ? $option['ga']['api_json'] : '';
  //$ga['view_id'] = isset($option['ga']['view_id']) ? $option['ga']['view_id'] : '';
  //$widget['ga_pp'] = isset($option['widget']['ga_pp']) ? true: false;

	?>

	<div id="ya_ga_content" class="tab_content ya_box_design">
		<h2><?php esc_html_e('Google Analytics','yahman-add-ons'); ?></h2>

		<div class="ya_hr"></div>

		<div class="ya_setting_content">
			<div class="ya_tooltip_wrap">
				<label for="ga_enable">
					<?php esc_html_e('Enable','yahman-add-ons'); ?>
				</label>
				<div class="ya_tooltip"><?php esc_html_e('Use Google Analytics.','yahman-add-ons'); ?></div>
			</div>
			<div class="ya_checkbox">
				<input name="yahman_addons[ga][enable]" type="checkbox" id="ga_enable"<?php checked(true, $ga['enable']); ?> class="ya_checkbox" />
				<label for="ga_enable"></label>
			</div>
		</div>

		<div class="ya_hr"></div>

		<div class="ya_setting_content">
			<div class="ya_tooltip_wrap">
				<label for="ga_id">
					<?php esc_html_e('Measurement ID','yahman-add-ons'); ?>
				</label>
				<div class="ya_tooltip">
					<?php esc_html_e('Enter your Google Analytics measurement ID.', 'yahman-add-ons'); ?><br>
					<?php echo esc_html__('e.g.&nbsp;', 'yahman-add-ons').esc_html('G-XXXXXXXXXX'); ?><br>
					<a href="<?php echo esc_url('https://support.google.com/analytics/answer/12270356?hl=' . get_locale() ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('Google Help', 'yahman-add-ons'); ?></a>
				</div>
			</div>
			<div class="ya_flex ya_ai_c">
				<label for="ga_id" class="ya_mr8"><?php esc_html_e('Measurement ID','yahman-add-ons'); ?></label>
				<input name="yahman_addons[ga][id]" type="text" id="ga_id" value="<?php echo esc_attr($ga['id']); ?>" class="ya_textbox ya_flex1" />
			</div>
		</div>

		<div class="ya_hr"></div>

		<div class="ya_setting_content">
			<div class="ya_tooltip_wrap">
				<label for="ga_verification">
					<?php esc_html_e('Google Site Verification','yahman-add-ons'); ?>
				</label>
				<div class="ya_tooltip">
					<?php esc_html_e('Verify your site ownership.', 'yahman-add-ons'); ?><br>
					<?php echo esc_html__('e.g.&nbsp;', 'yahman-add-ons').esc_html('XXXXXXXXXXXXXXXXXXXXXXXXXX-ZZZZZZZZZZZZZZZZ'); ?><br>
					<a href="<?php echo esc_url('https://support.google.com/webmasters/answer/9008080?hl=' . get_locale() ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('Google Help', 'yahman-add-ons'); ?></a>
				</div>
			</div>
			<div class="ya_flex ya_ai_c">
				<label for="ga_verification" class="ya_mr8"><?php esc_html_e('ID','yahman-add-ons'); ?></label>
				<input name="yahman_addons[ga][verification]" type="text" id="ga_verification" value="<?php echo esc_attr($ga['verification']); ?>" class="widefat ya_textbox ya_flex1" />
			</div>
		</div>
<?php /*
    <tr valign="top">
      <th scope="row" colspan="2"><h3 style="background: #000; color:#fff; padding: 5px 10px; margin-bottom: 0;"><?php echo sprintf(esc_html__('%s setting', 'yahman-add-ons'),esc_html__('Popular Post', 'yahman-add-ons')); ?></h3></th>
    </tr>
    <tr valign="top">
      <th scope="row"><label for="widget_ga_pp"><?php esc_html_e('Add Widget of Popular Post','yahman-add-ons'); ?></label></th>
      <td><input name="yahman_addons[widget][ga_pp]" type="checkbox" id="widget_ga_pp"<?php checked(true, $widget['ga_pp']); ?> class="ya_checkbox" /></td>
    </tr>
    <tr valign="top">
      <th scope="row"><label for="ga_api_json"><?php esc_html_e('JSON code of API','yahman-add-ons'); ?></label>
      </th>
      <td><textarea name="yahman_addons[ga][api_json]" rows="4" cols="48" id="ga_api_json" class="ya_textbox" /><?php echo $ga['api_json']; ?></textarea>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row"><label for="ga_view_id"><?php esc_html_e('View ID','yahman-add-ons'); ?></label><br />
      </th>
      <td><input name="yahman_addons[ga][view_id]" type="text" id="ga_view_id" value="<?php echo esc_attr($ga['view_id']); ?>" class="ya_textbox" /></td>
    </tr>
*/
    ?>


</div>




<?php
}
