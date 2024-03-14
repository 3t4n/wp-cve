<p class="r34ics-input">
	<label for="r34ics_display_add_calendar_button_false"><input type="checkbox" name="display_add_calendar_button_false" id="r34ics_display_add_calendar_button_false"<?php if (get_option('r34ics_display_add_calendar_button_false')) { echo ' checked="checked"'; } ?> /> <strong><?php _e('Remove "Add ICS Calendar" button in Classic Editor', 'r34ics'); ?></strong></label>
	<span class="description"><small class="r34ics-help"><span class="help_content"><?php _e('By default, ICS Calendar adds an "Add ICS Calendar" button to the toolbar above the WYSIWYG editor when using Classic Editor. Check this box to remove the button. Has no effect on sites using the Block Editor (Gutenberg).', 'r34ics'); ?></span></small></span>
</p>

<p class="r34ics-input">
	<label for="r34ics_use_new_defaults_10_6"><input type="checkbox" name="use_new_defaults_10_6" id="r34ics_use_new_defaults_10_6"<?php if (get_option('r34ics_use_new_defaults_10_6')) { echo ' checked="checked"'; } ?> /> <strong><?php _e('Use new parameter defaults (v.10.6)', 'r34ics'); ?></strong></label>
	<span class="description"><small class="r34ics-help"><span class="help_content"><?php printf(__('%1$s version 10.6 introduced new default values for several shortcode parameters. New installations automatically use the new default values, but upgraded installations will continue to use the old default values, unless this box is checked. Read more about these changes on %2$sour blog%3$s.', 'r34ics'), 'ICS Calendar', '<a href="https://icscalendar.com/updated-parameter-defaults-in-ics-calendar-10-6/" target="_blank">', '</a>'); ?></span></small></span>
</p>

<p class="r34ics-input">
	<label for="r34ics-admin-options-transients_expiration"><strong><?php _e('Transient (cache) expiration', 'r34ics'); ?>:</strong> <input type="number" name="transients_expiration" id="r34ics-admin-options-transients_expiration" value="<?php echo esc_attr(get_option('r34ics_transients_expiration') ? get_option('r34ics_transients_expiration') : 3600); ?>" min="0" max="86400" style="width: 100px;" /> <?php _e('seconds', 'r34ics'); ?></label>
	<span class="description"><small class="r34ics-help"><span class="help_content"><?php _e('Sets how long calendar feed data should be cached on the server (WordPress transients) before reloading. Default is 3600 (1 hour).', 'r34ics'); ?></span></small></span>
</p>

