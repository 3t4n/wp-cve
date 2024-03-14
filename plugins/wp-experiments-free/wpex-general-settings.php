<div class='wrap'>
	<div id="icon-edit-pages" class="icon32 icon32-posts-page"><br></div>
	<h2>Title Experiments Settings</h2>

	<form method="post">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="use_js">Use JavaScript</label></th>
					<td>
						<input name="use_js" type="checkbox" value="1" <?php if($use_js): ?>checked<?php endif; ?>> Use JavaScript to set titles
						<p class="description">Use JavaScript to set the titles. Use this if your site uses heavy caching.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="hide_body">Hide Body until tests are loaded</label></th>
					<td>
						<input name="hide_body" type="checkbox" value="1" <?php if($hide_body): ?>checked<?php endif; ?>> Hide body until tests are loaded
						<p class="description">When using JavaScript to set the titles, you may see your blanks spaces until your titles load.<br/>This hides the whole body to prevent seeing the title holes.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="search_engines">Search Engines should see the</label></th>
					<td>
						<select name="search_engines">
							<option value="first" <?php if($search_engines == 'first'): ?>selected<?php endif; ?>>first title</option>
							<option value="best" <?php if($search_engines == 'best'): ?>selected<?php endif; ?>>best performing title</option>
							<option value="experiment" <?php if($search_engines == 'experiment'): ?>selected<?php endif; ?>>experiment</option>
						</select>
						<p class="description">Which title should search engines find?</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="ignore_users">Ignore logged in users</label></th>
					<td>
						<input name="ignore_users" type="checkbox" value="1" <?php if($ignore_users): ?>checked<?php endif; ?>> Ignore logged in users
						<p class="description">This settings ignores logged in users (except Subscribers) from experimentation.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="best_feed">Best title in feed</label></th>
					<td>
						<input name="best_feed" type="checkbox" value="1" <?php if($best_feed): ?>checked<?php endif; ?>> Use the best performing title in feeds
						<p class="description">Use the best performing title in feeds instead of the default title.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="adjust_every">Recalculate every</label></th>
					<td>
						<select name="adjust_every">
							<option value="0" <?php if($adjust_every == 0): ?>selected<?php endif; ?>>Instantly</option>
							<option value="300" <?php if($adjust_every == 300): ?>selected<?php endif; ?>>5 minutes</option>
							<option value="1800" <?php if($adjust_every == 1800): ?>selected<?php endif; ?>>30 minutes</option>
							<option value="3600" <?php if($adjust_every == 3600): ?>selected<?php endif; ?>>1 hour</option>
							<option value="7200" <?php if($adjust_every == 7200): ?>selected<?php endif; ?>>2 hours</option>
							<option value="14400" <?php if($adjust_every == 14400): ?>selected<?php endif; ?>>4 hours</option>
							<option value="28800" <?php if($adjust_every == 28800): ?>selected<?php endif; ?>>8 hours</option>
							<option value="-1" <?php if($adjust_every == -1): ?>selected<?php endif; ?>>Never</option>
						</select>
						<p class="description">Recalculate title display probabilities every so often. Doing it too often can slow down high traffic sites.</p>
					</td>
				</tr>
				<?php if($titleEx): ?>
					<?php echo esc_attr($titleEx->settings()); ?>
				<?php endif; ?>
				<tr valign="top">
					<th scope="row"><label for="skip_pages">Skip pages</label></th>
					<td>
						<textarea name="skip_pages" class="large-text code"><?php echo esc_attr($skip_pages); ?></textarea>
						<p class="description">Pages on which to skip probability calculations. Use the path after the domain name, such as <b>/archives</b>.
							One page per line. Use this only if the plugin is causing problems on a certain page.</p>
					</td>
				</tr>
				<tr>
					<th></th>
					<td>
						<input type="submit" class="button-primary" name="save" value="Save Settings" />
						<a style="float: right;" href="#" id="wpexClearStats">[clear all statistics]</a>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
	<p>For more information, visit <a href='https://wpexperiments.com/title-experiments/'>wpexperiments.com/title-experiments/</a></p>
</div>
<script type='text/javascript'>
	jQuery("#wpexClearStats").click(function() {
		if(confirm("This will clear all statistics for all the alternate titles. Are you sure you want to do this?")) {
			var data = {
				'action': 'wpex_clear_stats'
			};
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function(response) {
				window.location.reload();
			});
		}
		return false;
	});

</script>
