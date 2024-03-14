<?php
/**
 * Handles Advance Setting metabox HTML
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking some variable
$popup_time_data	= popupaoc_time_options();
$cookie_expire		= isset( $advance['cookie_expire'] )	? $advance['cookie_expire']	: '';
$cookie_unit		= ! empty( $advance['cookie_unit'] )	? 'day'						: 'day';
$show_credit		= ! empty( $advance['show_credit'] )	? 1	: 0;
?>

<div id="paoc_advance_sett" class="paoc-vtab-cnt paoc-advance-sett paoc-clearfix">

	<div class="paoc-tab-info-wrap">
		<div class="paoc-tab-title"><?php esc_html_e('Advance Settings', 'popup-anything-on-click'); ?></div>
		<span class="paoc-tab-desc"><?php esc_html_e('Choose Popup advance settings.', 'popup-anything-on-click'); ?></span>
	</div>

	<table class="form-table paoc-tbl">
		<tbody>
			<tr>
				<th>
					<label for="paoc-show-credit"><?php esc_html_e('Show Credit', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="checkbox" name="<?php echo esc_attr( $prefix ); ?>advance[show_credit]" value="1" <?php checked( $show_credit, 1 ); ?> class="paoc-checkbox paoc-show-credit" id="paoc-show-credit" /><br/>
					<span class="description"><?php esc_html_e('Check this box to show credit of our work A huge thanks in advance :)', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="paoc-cookie-expire"><?php esc_html_e('Cookie Expiry Time', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="text" name="<?php echo esc_attr( $prefix ); ?>advance[cookie_expire]" value="<?php echo esc_attr( $cookie_expire ); ?>" class="paoc-medium-text paoc-text paoc-cookie-expire" id="paoc-cookie-expire" />
					<select name="<?php echo esc_attr( $prefix ); ?>advance[cookie_unit]" class="paoc-select" style="vertical-align: top;">
						<?php if( ! empty( $popup_time_data ) ) {
							foreach ( $popup_time_data as $popup_time_key => $popup_time_val ) { ?>
								<option value="<?php echo esc_attr( $popup_time_key ); ?>" <?php selected( $cookie_unit, $popup_time_key ); ?> <?php if( $popup_time_key != 'day' ) { echo 'disabled="disabled"'; } ?>><?php echo esc_html( $popup_time_val ); ?></option>
							<?php }
						} ?>
					</select><br />
					<span class="description"><?php esc_html_e('Enter cookie expiry time after how many days user can see popup again. Some values are.', 'popup-anything-on-click'); ?></span>
					<div class="paoc-code-tag-wrap">
						<code><?php esc_html_e('Each Page Load', 'popup-anything-on-click') ?></code> - <span class="description"><?php esc_html_e('Leave it blank to display popup on each page load.', 'popup-anything-on-click') ?></span><br/>
						<code><?php esc_html_e('Once Per Session', 'popup-anything-on-click') ?></code> - <span class="description"><?php esc_html_e('Enter 0 to display popup once per browser session.', 'popup-anything-on-click') ?></span><br/>
						<code><?php esc_html_e('After X times', 'popup-anything-on-click') ?></code> - <span class="description"><?php esc_html_e('Enter cookie expiry time after how many times user can see popup again.', 'popup-anything-on-click') ?></span>
					</div>
				</td>
			</tr>

			<!-- Pro Feature - Start -->
			<tr class="paoc-pro-feature">
				<td colspan="2" class="paoc-no-padding">
					<table class="form-table">
						<tbody>
							<tr>
								<th colspan="3">
									<div class="paoc-sub-sett-title"><i class="dashicons dashicons-admin-generic"></i> <?php esc_html_e('Additional Features', 'popup-anything-on-click'); ?> <span class="paoc-pro-tag"><?php esc_html_e('PRO', 'popup-anything-on-click');?></span><em><?php esc_html_e(' Utilize these Premium Features with Risk-Free 30 days money back guarantee.', 'popup-anything-on-click'); ?></em></div>
								</th>
							</tr>

							<tr>
								<th>
									<label for="paoc-show-for"><?php esc_html_e('Show For', 'popup-anything-on-click'); ?></label>
								</th>
								<td>
									<select name="" class="paoc-select paoc-show-hide paoc-show-for" id="paoc-show-for" data-prefix="showfor" disabled="disabled" />
										<option value=""><?php esc_html_e('All', 'popup-anything-on-click'); ?></option>
									</select><br/>
									<span class="description"><?php esc_html_e('Choose popup visibility for users.', 'popup-anything-on-click'); ?></span>
								</td>
							</tr>
							<tr>
								<th>
									<label for="paoc-display-on"><?php esc_html_e('Display On', 'popup-anything-on-click'); ?></label>
								</th>
								<td>
									<select name="" id="paoc-display-on" class="paoc-select paoc-display-on" disabled="disabled">
										<option value=""><?php esc_html_e('Every Device', 'popup-anything-on-click'); ?></option>
									</select><br/>
									<span class="description"><?php esc_html_e('Select device on which popup will be display.', 'popup-anything-on-click'); ?></span>
								</td>
							</tr>
							<tr>
								<th>
									<label for="paoc-adblocker"><?php esc_html_e('Adblocker Popup', 'popup-anything-on-click'); ?></label>
								</th>
								<td>
									<select name="" id="paoc-adblocker" class="paoc-select paoc-adblocker" disabled="disabled">
										<option value=""><?php esc_html_e('No Detection', 'popup-anything-on-click'); ?></option>
									</select><br/>
									<span class="description"><?php esc_html_e('Enable adblocker popup. Popup will be displaye when browser is blocking ads.', 'popup-anything-on-click'); ?></span>
								</td>
							</tr>
							<tr>
								<th>
									<label for="paoc-store-no-views"><?php esc_html_e('Do not Store Impression or Clicks Data', 'popup-anything-on-click'); ?></label>
								</th>
								<td>
									<input type="checkbox" name="" value="1" class="paoc-checkbox paoc-store-no-views" id="paoc-store-no-views" disabled="disabled" /><br/>
									<span class="description">
										<?php esc_html_e('Check this box if you do not want to store popup impressions or clicks data in database.', 'popup-anything-on-click'); ?>
									</span>
								</td>
							</tr>
							<tr class="paoc-show-hide-row-goal paoc-show-if-goal-email-lists" style="<?php if( $popup_goal != 'email-lists' ) { echo 'display: none;'; } ?>">
								<th>
									<label for="paoc-store-no-data"><?php esc_html_e('Do not Store Form Submission Data', 'popup-anything-on-click'); ?></label>
								</th>
								<td>
									<input type="checkbox" name="" value="1" class="paoc-checkbox paoc-store-no-data" id="paoc-store-no-data" disabled="disabled" /><br/>
									<span class="description"><?php esc_html_e('Check this box if you do not want to store `Collect Lead` popup form submission data in database.', 'popup-anything-on-click'); ?></span>
								</td>
							</tr>

							<tr>
								<th colspan="3">
									<div class="paoc-sub-sett-title"><i class="dashicons dashicons-admin-generic"></i> <?php esc_html_e('Popup Schedule Settings', 'popup-anything-on-click'); ?> <span class="paoc-pro-tag"><?php esc_html_e('PRO','popup-anything-on-click');?></span><em><?php esc_html_e(' Utilize these Premium Features with Risk-Free 30 days money back guarantee.', 'popup-anything-on-click'); ?></em></div>
								</th>
							</tr>

							<tr>
								<th>
									<label for="paoc-schedule-start"><?php esc_html_e('Start Time', 'popup-anything-on-click'); ?></label>
								</th>
								<td>
									<input type="text" name="" value="" class="paoc-medium-text paoc-schedule-start" id="paoc-schedule-start" disabled="disabled" /><br/>
									<span class="description"><?php esc_html_e('Set popup start time.', 'popup-anything-on-click'); ?></span>
								</td>
							</tr>
							<tr>
								<th>
									<label for="paoc-schedule-end"><?php esc_html_e('End Time', 'popup-anything-on-click'); ?></label>
								</th>
								<td>
									<input type="text" name="" value="" class="paoc-medium-text paoc-schedule-end" id="paoc-schedule-end" disabled="disabled" /><br/>
									<span class="description"><?php esc_html_e('Set popup end time.', 'popup-anything-on-click'); ?></span>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</div><!-- end .paoc-advance-sett -->