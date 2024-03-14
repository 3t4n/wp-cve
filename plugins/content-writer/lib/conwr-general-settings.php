<div class='wrap'>
	<div id="icon-edit-pages" class="icon32 icon32-posts-page"><br></div>
	<h2>Content Writer Settings</h2>
	<br>
	<?php if(!$sc_connected) { ?>
	<div style="width: 97%; padding: 20px 20px; background-color: #fdfcfc; border-left: 4px solid #2983E2;"><h4>Please wait up to 48 hours for your plugin installation to be verified.</h4></div>
	<?php } ?>
	<?php if($sc_connected) { ?>
		<div style="width: 97%; padding: 20px 20px; background-color: #fdfcfc; border-left: 4px solid #2983E2;"><h4>Your plugin is verified.</h4></div>
	<?php } ?>
	<div id="stcon-tabs-container" style="display:none !important">
		<ul class="stcon-tabs-menu"> 
			<li class="current"><span id="settings">Settings</a></li> 

			<li><span id="titles">Titles</span></li> 
		</ul> 
		<div class="stcon-tab">
			<div id="divsettings" class="stcon-tab-content">
				<form method="post">
					
					<div class="conwr-settings" style='display:none;'>
						<?php if(!$sc_connected) { ?>
						<h4>Please wait up to 48 hours for your plugin installation to be verified</h4>
						<?php } else { ?>
						<h4>You are now connected!</h4>
						<?php } ?>
						
						<table class="conwr-table">
							<tbody>
								<?php if(!$sc_connected) { ?>
									<tr>
										<th scope="row" style="width: 80px;"><label for="sc_email">Email:</label></th>
										<td>
											<input name="sc_email" type="text" value="<?php echo esc_attr($sc_email); ?>">
										</td>
									</tr>
									<tr>
										<th scope="row" style="width: 0px;"><label for="sc_password">Password:</label></th>
										<td>
											<input name="sc_password" type="Password" value="<?php echo esc_attr($sc_password); ?>">
										</td>
									</tr>
								<?php } else { ?>
									<tr>
										<th scope="row" style="width: 100px;"><label for="sc_api_key">Your API Key:</label></th>
										<td>
											<input name="sc_api_key" type="text" value="<?php echo esc_attr($sc_api_key); ?>" disabled>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php if(!$sc_connected) { ?>
							<div class="stcon-settings-submit">
								<input type="submit" class="button-primary" name="save_settings" value="Connect" />
								<div style="padding-top: 10px;"><a href="http://steadycontent.com" target="_blank"><i>Need Awesome Content? Click HERE!</i></a></div>
							</div>
						<?php } else { ?>
							<div class="stcon-settings-submit">
								<input type="submit" class="button-primary" name="disconnect" value="Disconnect" />
							</div>
						<?php } ?>
					</div>
				</form>
			</div>
			<div id="divtitles" class="stcon-tab-content" style="display:none !important;">
				<form method="post">
					<div class="conwr-settings">
						<table class="conwr-table">
							<tbody>
								<tr>
									<th scope="row"><label for="use_js">Use JavaScript to set titles:</label></th>
									<td>
										<input name="use_js" type="checkbox" value="1" <?php if($use_js): ?>checked<?php endif; ?>> <span class="setting-description">(Use this if your site uses heavy caching)</span>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="hide_body">Hide Body until tests are loaded:</label></th>
									<td>
										<input name="hide_body" type="checkbox" value="1" <?php if($hide_body): ?>checked<?php endif; ?>> <span class="setting-description">(When using JavaScript to set the titles, you may see your blanks spaces until your titles load. This hides the whole body to prevent seeing the title holes)</span>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="search_engines">Search Engines should see:</label></th>
									<td>
										<select name="search_engines" style="width: 250px;">
											<option value="first" <?php if(esc_attr($search_engines) == 'first'): ?>selected<?php endif; ?>>first title</option>
											<option value="best" <?php if(esc_attr($search_engines) == 'best'): ?>selected<?php endif; ?>>best performing title</option>
											<option value="experiment" <?php if(esc_attr($search_engines) == 'experiment'): ?>selected<?php endif; ?>>experiment</option>
										</select> <span class="setting-description">(Which title should search engines find?)</span>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="ignore_users">Ignore logged in users:</label></th>
									<td>
										<input name="ignore_users" type="checkbox" value="1" <?php if($ignore_users): ?>checked<?php endif; ?>> <span class="setting-description">(This settings ignores logged in users (except Subscribers) from experimentation)</span>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="best_feed">Best title in feed:</label></th>
									<td>
										<input name="best_feed" type="checkbox" value="1" <?php if($best_feed): ?>checked<?php endif; ?>> <span class="setting-description">(Use the best performing title in feeds instead of the default title)</span>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="adjust_every">Recalculate every:</label></th>
									<td>
										<select name="adjust_every" style="width: 250px;">
											<option value="0" <?php if(intval($adjust_every) == 0): ?>selected<?php endif; ?>>Instantly</option>
											<option value="300" <?php if(intval($adjust_every) == 300): ?>selected<?php endif; ?>>5 minutes</option>
											<option value="1800" <?php if(intval($adjust_every) == 1800): ?>selected<?php endif; ?>>30 minutes</option>
											<option value="3600" <?php if(intval($adjust_every) == 3600): ?>selected<?php endif; ?>>1 hour</option>
											<option value="7200" <?php if(intval($adjust_every) == 7200): ?>selected<?php endif; ?>>2 hours</option>
											<option value="14400" <?php if(intval($adjust_every) == 14400): ?>selected<?php endif; ?>>4 hours</option>
											<option value="28800" <?php if(intval($adjust_every) == 28800): ?>selected<?php endif; ?>>8 hours</option>
											<option value="-1" <?php if(intval($adjust_every) == -1): ?>selected<?php endif; ?>>Never</option>
										</select> <span class="setting-description">(Recalculate title display probabilities every so often. Doing it too often can slow down high traffic sites)</span>
									</td>
								</tr>
								<tr>
									<th scope="row" style="vertical-align: top;"><label for="skip_pages">Skip pages:</label></th>
									<td>
										<textarea name="skip_pages" class="large-text code" style="width: 250px; height: 80px;"><?php echo esc_textarea($skip_pages); ?></textarea> <span class="setting-description" style="vertical-align: top; display: inline-block; width: 650px;">(Pages on which to skip probability calculations. Use the path after the domain name, such as "/archives". One page per line. Use this only if the plugin is causing problems on a certain page)</span>
									</td>
								</tr>
								<tr>
									<th><input type="submit" class="button-primary" name="save" value="Save" /></th>
									<td>							
										<a style="float: right;" href="#" id="conwrClearStats">[clear all statistics]</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type='text/javascript'>
	jQuery("#conwrClearStats").click(function() {
		if(confirm("This will clear all statistics for all the alternate titles. Are you sure you want to do this?")) {
			var data = {
				'action': 'conwr_clear_stats'
			};
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function(response) {
				window.location.reload();
			});
		}
		return false;
	});

</script>
