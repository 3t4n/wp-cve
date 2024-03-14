<?php
/**
 * Handles Notification Setting metabox HTML
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div id="paoc_notification_sett" class="paoc-vtab-cnt paoc-pro-feature paoc-notification-sett paoc-clearfix">

	<div class="paoc-tab-info-wrap">
		<div class="paoc-tab-title"><?php esc_html_e('Notification Settings', 'popup-anything-on-click'); ?></div>
		<span class="paoc-tab-desc"><?php esc_html_e('Choose popup notification settings.', 'popup-anything-on-click'); ?></span>
	</div>

	<table class="form-table paoc-tbl">
		<tbody>
			<tr>
				<th>
					<label for="paoc-enable-email"><?php esc_html_e('Enable', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="checkbox" name="" value="1" class="paoc-checkbox paoc-enable-email" id="paoc-enable-email" disabled="disabled" /><br />
					<span class="description"><?php esc_html_e('Check this box to enable admin email notification.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="paoc-email-to"><?php esc_html_e('Mail To', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="text" name="" value="" class="paoc-text large-text paoc-email-to" id="paoc-email-to" disabled="disabled" />
					<span class="description"><?php esc_html_e('Enter notification email address to send which one.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="paoc-email-subject"><?php esc_html_e('Subject', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="text" name="" value="" class="paoc-text large-text paoc-email-subject" id="paoc-email-subject" disabled="disabled" />
					<span class="description"><?php esc_html_e('Enter notification admin email subject. Available template tags are', 'popup-anything-on-click'); ?></span><br/>
					<div class="paoc-code-tag-wrap">
						<code class="paoc-copy-clipboard">{ID}</code> - <span class="description"><?php esc_html_e('Display popup ID.', 'popup-anything-on-click'); ?></span>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for="paoc-email-heading"><?php esc_html_e('Heading', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="text" name="" value="" class="paoc-text large-text paoc-email-heading" id="paoc-email-heading" disabled="disabled" />
					<span class="description"><?php esc_html_e('Enter notification admin email heading.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="paoc-email-msg"><?php esc_html_e('Message', 'popup-anything-on-click'); ?></label>
				</th>
				<td class="paoc-pro-disabled">
					<?php wp_editor( '', 'paoc-email-msg', array('textarea_name' => '', 'textarea_rows' => 8, 'media_buttons' => true, 'class' => 'paoc-email-msg') ); ?>
					<span class="description"><?php echo sprintf( esc_html__('Enter notification admin email message. Available template tags are %s.', 'popup-anything-on-click'), '<a href="javascript:void(0)" class="paoc-show-popup-tags" title="'.esc_attr__('Popup Tags', 'popup-anything-on-click').'" data-tags="notification">'.esc_html__('here', 'popup-anything-on-click').'</a>' ); ?></span>
				</td>
			</tr>

			<tr>
				<th colspan="2">
					<div class="paoc-sub-sett-title"><i class="dashicons dashicons-admin-generic"></i> <?php esc_html_e('User Email Settings', 'popup-anything-on-click'); ?></div>
				</th>
			</tr>

			<tr>
				<th>
					<label for="paoc-enable-user-email"><?php esc_html_e('Enable', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="checkbox" name="" value="1" class="paoc-checkbox paoc-enable-user-email" id="paoc-enable-user-email" disabled="disabled" /><br />
					<span class="description"><?php esc_html_e('Check this box to enable user email notification.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="paoc-user-email-subject"><?php esc_html_e('Subject', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="text" name="" value="" class="paoc-text large-text paoc-user-email-subject" id="paoc-user-email-subject" disabled="disabled" />
					<span class="description"><?php esc_html_e('Enter notification user email subject. Available template tags are', 'popup-anything-on-click'); ?></span><br/>
					<div class="paoc-code-tag-wrap">
						<code class="paoc-copy-clipboard">{name}</code> - <span class="description"><?php esc_html_e('Display user full name.', 'popup-anything-on-click'); ?></span>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for="paoc-user-email-heading"><?php esc_html_e('Heading', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="text" name="" value="" class="paoc-text large-text paoc-user-email-heading" id="paoc-user-email-heading" disabled="disabled" />
					<span class="description"><?php esc_html_e('Enter notification user email heading.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="paoc-user-email-msg"><?php esc_html_e('Message', 'popup-anything-on-click'); ?></label>
				</th>
				<td class="paoc-pro-disabled">
					<?php wp_editor( '', 'paoc-user-email-msg', array('textarea_name' => '', 'textarea_rows' => 8, 'media_buttons' => true, 'class' => 'paoc-user-email-msg') ); ?>
					<span class="description"><?php echo sprintf( esc_html__('Enter notification user email message. Available template tags are %s.', 'popup-anything-on-click'), '<a href="javascript:void(0)" class="paoc-show-popup-tags" title="'.esc_attr__('Popup Tags', 'popup-anything-on-click').'" data-tags="notification">'.esc_html__('here', 'popup-anything-on-click').'</a>' ); ?></span>
				</td>
			</tr>
		</tbody>
	</table>
</div><!-- end .paoc-notification-sett -->