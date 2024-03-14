<?php screen_icon(); ?>
<h2 style="font-weight: normal;">Page In Page Settings</h2>           

<?php include TWL_PIP_TEMPLATES . '/messages.php'; ?>

<form action="" method="post" id="twl-pip-settings">
	<div class="description">
		In order to insert your Facebook page's posts and Twitter page's tweets in your wordpress pages using the Page-In-Page plugin, you will need to create a 
		Facebook Application and Twitter Application respectively. The credentials you will obtain after creating an application should be filled in the form below.
		<p> To create a Facebook Application, <a href="https://developers.facebook.com/apps" target="_blank">go here</a>. </p>
		<p> To create a Twitter Application, <a href="https://dev.twitter.com/apps" target="_blank">go here</a>. </p>
	</div>
	
	<table class="twl-social-settings">
		<thead>
			<tr>
				<th colspan="2">Facebook Settings</th>
			</tr>
		</thead>
		<tr>
			<td>Page ID</td>
			<td>
				<input name="facebook[page_id]" class="twl-pip-input" value="<?php twl_pip_set('page_id', $settings['facebook']); ?>" />
				<br /><i>Enter the ID of your Facebook page</i>
			</td>
		</tr>
		<tr>
			<td>App ID</td>
			<td>
				<input name="facebook[app_id]" class="twl-pip-input" value="<?php twl_pip_set('app_id', $settings['facebook']); ?>" />
				<br /><i>Enter your Facebook Application ID</i>
			</td>
		</tr>
		<tr>
			<td>App Secret</td>
			<td>
				<input name="facebook[app_secret]" class="twl-pip-input" value="<?php twl_pip_set('app_secret', $settings['facebook']); ?>" />
				<br /><i>Enter your Facebook Application secret</i>
			</td>
		</tr>
		<tr class="section"><td colspan="2"></td></tr>
		<tr>
			<td>Alert SDK Errors?</td>
			<td>
				<label><input type="radio" name="facebook[alert_sdk_errors]" value="1" <?php twl_pip_set('alert_sdk_errors', $settings['facebook'], 'radio', 1) ?>> Yes </label>
				<label><input type="radio" name="facebook[alert_sdk_errors]" value="0" <?php twl_pip_set('alert_sdk_errors', $settings['facebook'], 'radio', 0) ?>> No </label>
				<i>Should errors from the Facebook SDK be printed out?</i>
			</td>
		</tr>
	</table>

	<table class="twl-social-settings">
		<thead>
			<tr>
				<th colspan="2">Twitter Settings</th>
			</tr>
		</thead>
		<tr>
			<td>Screen Name</td>
			<td>
				<input name="twitter[screen_name]" class="twl-pip-input" value="<?php twl_pip_set('screen_name', $settings['twitter']); ?>" />
				<br /><i>Enter your Twitter username</i>
			</td>
		</tr>
		<tr>
			<td>App Customer Key</td>
			<td>
				<input name="twitter[customer_key]" class="twl-pip-input" value="<?php twl_pip_set('customer_key', $settings['twitter']); ?>" />
				<br /><i>Enter your Twitter application customer key</i>
			</td>
		</tr>
		<tr>
			<td>App Customer Secret</td>
			<td>
				<input name="twitter[customer_secret]" class="twl-pip-input" value="<?php twl_pip_set('customer_secret', $settings['twitter']); ?>" />
				<br /><i>Enter your Twitter application customer secret</i>
			</td>
		</tr>
		<tr class="section"><td colspan="2"></td></tr>
		<tr>
			<td>App Access Token</td>
			<td>
				<input name="twitter[access_token]" class="twl-pip-input" value="<?php twl_pip_set('access_token', $settings['twitter']); ?>" />
				<br /><i>Enter your Twitter application read only access token</i>
			</td>
		</tr>
		<tr>
			<td>App Access Token Secret</td>
			<td>
				<input name="twitter[access_token_secret]" class="twl-pip-input" value="<?php twl_pip_set('access_token_secret', $settings['twitter']); ?>" />
				<br /><i>Enter your Twitter application read only access token secret</i>
			</td>
		</tr>
		<tr class="section"><td colspan="2"></td></tr>
		<tr>
			<td>Alert SDK Errors?</td>
			<td>
				<label><input type="radio" name="twitter[alert_sdk_errors]" value="1" <?php twl_pip_set('alert_sdk_errors', $settings['twitter'], 'radio', 1) ?>> Yes </label>
				<label><input type="radio" name="twitter[alert_sdk_errors]" value="0" <?php twl_pip_set('alert_sdk_errors', $settings['twitter'], 'radio', 0) ?>> No </label>
				<i>Should errors from the Twitter SDK be printed out?</i>
			</td>
		</tr>
	</table>
	
	<table class="twl-social-settings">
		<thead>
			<tr>
				<th colspan="2">Cache Setting</th>
			</tr>
		</thead>
		<tr>
			<td>Cache feeds</td>
			<td>
				<input name="cache_feeds" class="twl-pip-input" value="<?php twl_pip_set('cache_feeds', $settings); ?>" style="width: 30px;" /> <b>Minutes</b>
				<br /><i>Enter <b>number of minutes</b> to cache feeds in WordPress.</i>
			</td>
		</tr>
	</table>

	<input type="hidden" name="wp-pip-admin-settings-save" value="Agnes" />
	<?php wp_nonce_field(TWL_PIP_ROOT, 'twl_pip_nonce'); ?>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Settings"></p>
</form>