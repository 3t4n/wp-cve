<div id="insert_r34ics">
	<div id="insert_r34ics_overlay"></div>
	<div id="insert_r34ics_window">

			<div id="insert_r34ics_header">
				<strong><?php printf(__('Add %1$s', 'r34ics'), 'ICS Calendar'); ?></strong>
				<div id="insert_r34ics_close" title="<?php esc_attr_e('Close', 'r34ics'); ?>">&times;</div>
			</div>

			<div id="insert_r34ics_content">
				<form action="#" method="get" id="insert_r34ics_form">
				
					<?php do_action('r34ics_admin_add_calendar_settings_html'); ?>
					
					<p class="field-block">
						<label for="insert_r34ics_url"><?php _e('ICS Feed URL:', 'r34ics'); ?></label><br />
						<input id="insert_r34ics_url" name="insert_r34ics_url" type="text" style="width: 100%;" /><br />
						<em><small><?php printf(__('Be sure you are using a %1$ssubscribe%2$s URL, not a %3$sweb view%4$s URL.%5$s (Entering the URL directly in your web browser should download an %6$s file.)', 'r34ics'), '<strong>', '</strong>', '<strong>', '</strong>', '<br />', '<code>.ics</code>'); ?></small></em>
					</p>
					
					<p class="field-block">
						<label for="insert_r34ics_title"><?php _e('Calendar Title:', 'r34ics'); ?></label><br />
						<input id="insert_r34ics_title" name="insert_r34ics_title" type="text" style="width: 100%;" /><br />
						<em><small><?php printf(__('Leave empty to use calendar&rsquo;s default title. Enter %1$s to omit title altogether.', 'r34ics'), '<code>none</code>'); ?></small></em>
					</p>
					
					<p class="field-block">
						<label for="insert_r34ics_description"><?php _e('Calendar Description:', 'r34ics'); ?></label><br />
						<input id="insert_r34ics_description" name="insert_r34ics_description" type="text" style="width: 100%;" /><br />
						<em><small><?php printf(__('Leave empty to use calendar&rsquo;s default description. Enter %1$s to omit description altogether.', 'r34ics'), '<code>none</code>'); ?></small></em>
					</p>
					
					<p class="field-block">
						<label for="insert_r34ics_view"><?php _e('View:', 'r34ics'); ?></label><br />
						<select id="insert_r34ics_view" name="insert_r34ics_view" onchange="if (jQuery(this).val() == 'list') { jQuery('#r34ics_list_view_options').show(); } else { jQuery('#r34ics_list_view_options').hide(); }">
							<option value="month"><?php _e('month', 'r34ics'); ?></option>
							<option value="list"><?php _e('list', 'r34ics'); ?></option>
							<option value="week"><?php _e('week', 'r34ics'); ?></option>
						</select><br />
					</p>
					
					<p class="field-block" id="r34ics_list_view_options" style="display: none;">
						<label for="insert_r34ics_count"><?php _e('Count:', 'r34ics'); ?></label>
						<input id="insert_r34ics_count" name="insert_r34ics_count" type="number" min="1" step="1" />
						&nbsp;&nbsp;
						<label for="insert_r34ics_format"><?php _e('Format:', 'r34ics'); ?></label>
						<input id="insert_r34ics_format" name="insert_r34ics_format" type="text" value="l, F j" /><br />
						<em><small><?php printf(__('Leave %1$s blank to include all upcoming events. %2$s must be a standard %3$sPHP date format string%4$s.', 'r34ics'), '<strong>' . __('Count:', 'r34ics') . '</strong>', '<strong>' . __('Format:', 'r34ics') . '</strong>', '<a href="https://secure.php.net/manual/en/function.date.php" target="_blank">', '</a>'); ?></small></em>
					</p>
					
					<p class="field-block">
						<input id="insert_r34ics_eventdesc" name="insert_r34ics_eventdesc" type="checkbox" onchange="if (this.checked) { jQuery('#insert_r34ics_toggle_wrapper').show(); } else if (!this.checked && !jQuery('#insert_r34ics_organizer').prop('checked') && !jQuery('#insert_r34ics_location').prop('checked')) { jQuery('#insert_r34ics_toggle_wrapper').hide(); }" />
						<label for="insert_r34ics_eventdesc"><?php printf(__('Show event descriptions %1$s(change to a number in inserted shortcode to set word limit)%2$s', 'r34ics'), '<em><small>', '</small></em>'); ?></label>
					</p>
				
					<p class="field-block">
						<input id="insert_r34ics_location" name="insert_r34ics_location" type="checkbox" onchange="if (this.checked) { jQuery('#insert_r34ics_toggle_wrapper').show(); } else if (!this.checked && !jQuery('#insert_r34ics_organizer').prop('checked') && !jQuery('#insert_r34ics_eventdesc').prop('checked')) { jQuery('#insert_r34ics_toggle_wrapper').hide(); }" />
						<label for="insert_r34ics_location"><?php printf(__('Show event locations %1$s(if available)%2$s', 'r34ics'), '<em><small>', '</small></em>'); ?></label>
					</p>
					
					<p class="field-block">
						<input id="insert_r34ics_organizer" name="insert_r34ics_organizer" type="checkbox" onchange="if (this.checked) { jQuery('#insert_r34ics_toggle_wrapper').show(); } else if (!this.checked && !jQuery('#insert_r34ics_location').prop('checked') && !jQuery('#insert_r34ics_eventdesc').prop('checked')) { jQuery('#insert_r34ics_toggle_wrapper').hide(); }" />
						<label for="insert_r34ics_organizer"><?php printf(__('Show event organizers %1$s(if available)%2$s', 'r34ics'), '<em><small>', '</small></em>'); ?></label>
					</p>
					
					<p class="field-block">
						<small><?php printf(__('%1$sNote:%2$s Additional %3$sdisplay options%4$s are available by manually editing the shortcode after insertion.', 'r34ics'), '<strong>', '</strong>', '<a href="admin.php?page=ics-calendar#event-display-options" target="_blank">', '</a>'); ?></small>
					</p>
					
					<p style="text-align: right;">
						<input name="insert" type="submit" class="button button-primary button-large" value="<?php echo esc_attr(sprintf(__('Insert %1$s', 'r34ics'), 'ICS Calendar')); ?>" />
					</p>

				</form>
			</div>

	</div>
</div>
