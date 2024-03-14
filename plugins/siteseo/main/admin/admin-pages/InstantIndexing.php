<?php

defined('ABSPATH') or exit('Please don&rsquo;t call the plugin directly. Thanks :)');

function siteseo_instant_indexing_google_engine_callback(){
	$options = get_option('siteseo_instant_indexing_option_name');

	$search_engines = [
		'google' => 'Google',
		'bing'=> 'Bing'
	];

	if (!empty($search_engines)) {
		foreach ($search_engines as $key => $value) {
			$check = isset($options['engines'][$key]);
			?>
			<div class="siteseo_wrap_single_cpt">
				<label
					for="siteseo_instant_indexing_engines_<?php echo esc_attr($key); ?>">
					<input
						id="siteseo_instant_indexing_engines_<?php echo esc_attr($key); ?>"
						name="siteseo_instant_indexing_option_name[engines][<?php echo esc_attr($key); ?>]"
						type="checkbox" <?php checked($check, '1') ?>
					value="1"/>
					<?php echo esc_html($value); ?>
				</label>
			</div>
		<?php
		}
	}
}

function siteseo_instant_indexing_google_action_callback(){
	$options = get_option('siteseo_instant_indexing_option_name');

	$actions = [
		'URL_UPDATED' => esc_html__('Update URLs', 'siteseo'),
		'URL_DELETED' => esc_attr__('Remove URLs (URL must return a 404 or 410 status code or the page contains <meta name="robots" content="noindex" /> meta tag)', 'siteseo'),
	];

	foreach ($actions as $key => $value) { ?>
<div class="siteseo_wrap_single_cpt">

	<?php if (isset($options['instant_indexing_google_action'])) {
		$check = $options['instant_indexing_google_action'];
	} else {
		$check = 'URL_UPDATED';
	} ?>

	<label
		for="siteseo_instant_indexing_google_action_include_<?php echo esc_attr($key); ?>">
		<input
			id="siteseo_instant_indexing_google_action_include_<?php echo esc_attr($key); ?>"
			name="siteseo_instant_indexing_option_name[instant_indexing_google_action]" type="radio" <?php if ($key == $check) { ?>
		checked="yes"
		<?php } ?>
		value="<?php echo esc_attr($key); ?>"/>

		<?php echo esc_html($value); ?>
	</label>
</div>
<?php }
}

function siteseo_instant_indexing_manual_batch_callback(){
	require_once SITESEO_DIR_PATH . '/vendor/autoload.php';
	$options = get_option('siteseo_instant_indexing_option_name');
	$log = get_option('siteseo_instant_indexing_log_option_name');
	$check = isset($options['instant_indexing_manual_batch']) ? esc_attr($options['instant_indexing_manual_batch']) : null;

	//URLs
	$urls = isset($log['log']['urls']) ? $log['log']['urls'] : null;
	$date = isset($log['log']['date']) ? $log['log']['date'] : null;

	//General errors
	$error = isset($log['error']) ? $log['error'] : null;

	//Bing
	$bing_response = isset($log['bing']['response']) ? $log['bing']['response'] : null;

	//Google
	$google_response = isset($log['google']['response']) ? $log['google']['response'] : null;

	printf(
'<textarea id="siteseo_instant_indexing_manual_batch" name="siteseo_instant_indexing_option_name[instant_indexing_manual_batch]" rows="20" placeholder="' . esc_html__('Enter one URL per line to submit them to search engines (max 100 URLs)', 'siteseo') . '" aria-label="' . esc_html__('Enter one URL per line to submit them to search engines (max 100 URLs)', 'siteseo') . '">%s</textarea>',
esc_html($check));
?>

<div class="wrap-siteseo-progress">
	<div class="siteseo-progress" style="margin:0">
		<div id="siteseo_instant_indexing_url_progress" class="siteseo-progress-bar" role="progressbar" style="width: 1%;" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100">1%</div>
	</div>
	<div class="wrap-siteseo-counters">
		<div id="siteseo_instant_indexing_url_count"></div>
		<strong><?php esc_html_e(' / 100 URLs', 'siteseo'); ?></strong>
	</div>
</div>

<p>
	<br>
	<button type="button" class="siteseo-instant-indexing-batch btn btnPrimary">
		<?php esc_html_e('Submit URLs to Google & Bing', 'siteseo'); ?>
	</button>

	<span class="spinner"></span>
</p>

<h3><?php esc_html_e('Latest indexing request','siteseo'); ?></h3>
<p><em><?php echo esc_html($date); ?></em></p>

<?php
if (!empty($error)) { ?>
	<span class="indexing-log indexing-failed"></span><?php echo wp_kses_post($error); ?>
<?php }
if (!empty($bing_response['response'])) {
	switch ($bing_response['response']['code']) {
		case 200:
			$msg = esc_html__('URLs submitted successfully', 'siteseo');
			break;
		case 202:
			$msg = esc_html__('URL received. IndexNow key validation pending.', 'siteseo');
			break;
		case 400:
			$msg = esc_html__('Bad request: Invalid format', 'siteseo');
			break;
		case 403:
			$msg = esc_html__('Forbidden: In case of key not valid (e.g. key not found, file found but key not in the file)', 'siteseo');
			break;
		case 422:
			$msg = esc_html__('Unprocessable Entity: In case of URLs don’t belong to the host or the key is not matching the schema in the protocol', 'siteseo');
			break;
		case 429:
			$msg = esc_html__('Too Many Requests: Too Many Requests (potential Spam)', 'siteseo');
			break;
		default:
			$msg = esc_html__('Something went wrong', 'siteseo');
	} ?>
	<div class="wrap-bing-response">
		<h4><?php esc_html_e('Bing Response','siteseo'); ?></h4>

		<?php if ($bing_response['response']['code'] == 200 || $bing_response['response']['code'] == 202) { ?>
			<span class="indexing-log indexing-done"></span>
		<?php } else { ?>
			<span class="indexing-log indexing-failed"></span>
		<?php } ?>
		<code><?php echo esc_html($msg); ?></code>
	</div>
<?php }

	if (is_array($google_response) && !empty($google_response)) { ?>
		<div class="wrap-google-response">
			<h4><?php esc_html_e('Google Response','siteseo'); ?></h4>

			<?php
			$google_exception = $google_response[siteseo_array_key_first($google_response)];
			if ( is_a( $google_exception, 'Google\Service\Exception' ) ) {
				$error = json_decode($google_exception->getMessage(), true);
				echo '<span class="indexing-log indexing-failed"></span><code>' . esc_html($error['error']['code']) . ' - ' . esc_html($error['error']['message']) . '</code>';
			} elseif (!empty($google_response['error'])) {
				echo '<span class="indexing-log indexing-failed"></span><code>' . esc_html($google_response['error']['code']) . ' - ' . esc_html($google_response['error']['message']) . '</code>';
			} else { ?>
				<p><span class="indexing-log indexing-done"></span><code><?php esc_html_e('URLs submitted successfully', 'siteseo'); ?></code></p>
				<ul>
					<?php foreach($google_response as $result) {
						if ($result) {
							if (!empty($result->urlNotificationMetadata->latestUpdate["url"]) || !empty($result->urlNotificationMetadata->latestUpdate["type"])) {
								echo '<li>';
							}
							if (!empty($result->urlNotificationMetadata->latestUpdate["url"])) {
								echo esc_url($result->urlNotificationMetadata->latestUpdate["url"]);
							}
							if (!empty($result->urlNotificationMetadata->latestUpdate["type"])) {
								echo ' - ';
								echo '<code>' . esc_html($result->urlNotificationMetadata->latestUpdate["type"]) . '</code>';
							}
							if (!empty($result->urlNotificationMetadata->latestUpdate["url"]) || !empty($result->urlNotificationMetadata->latestUpdate["type"])) {
								echo '</li>';
							}
							if (!empty($result->urlNotificationMetadata->latestRemove["url"]) || !empty($result->urlNotificationMetadata->latestRemove["type"])) {
								echo '<li>';
							}
							if (!empty($result->urlNotificationMetadata->latestRemove["url"])) {
								echo esc_url($result->urlNotificationMetadata->latestRemove["url"]);
							}
							if (!empty($result->urlNotificationMetadata->latestRemove["type"])) {
								echo ' - ';
								echo '<code>' . esc_html($result->urlNotificationMetadata->latestRemove["type"]) . '</code>';
							}
							if (!empty($result->urlNotificationMetadata->latestRemove["url"]) || !empty($result->urlNotificationMetadata->latestRemove["type"])) {
								echo '</li>';
							}
						}
					} ?>
				</ul>
			<?php } ?>
		</div>
	<?php }
	?>

	<h4><?php esc_html_e('Latest URLs submitted','siteseo'); ?></h4>
	<?php if (!empty($urls[0])) { ?>
		<ul>
		<?php foreach($urls as $url) { ?>
			<li>
				<?php echo esc_url($url); ?>
			</li>
		<?php } ?>
		</ul>
	<?php } else {
		esc_html_e('None', 'siteseo');
	}
}

function siteseo_instant_indexing_google_api_key_callback(){
	$docs = function_exists('siteseo_get_docs_links') ? siteseo_get_docs_links() : '';
	$options = get_option('siteseo_instant_indexing_option_name');
	$check = isset($options['instant_indexing_google_api_key']) ? esc_attr($options['instant_indexing_google_api_key']) : null;

	printf(
'<textarea id="instant_indexing_google_api_key" name="siteseo_instant_indexing_option_name[instant_indexing_google_api_key]" rows="12" placeholder="' . esc_html__('Paste your Google JSON key file here', 'siteseo') . '" aria-label="' . esc_html__('Paste your Google JSON key file here', 'siteseo') . '">%s</textarea>',
esc_html($check)); ?>

<p class="siteseo-help description"><?php printf(wp_kses_post(__('To use the <span class="dashicons dashicons-external"></span><a href="%1$s" target="_blank">Google Indexing API</a> and generate your JSON key file, please <span class="dashicons dashicons-external"></span><a href="%2$s" target="_blank">follow our guide.')), esc_url($docs['indexing_api']['api']), esc_url($docs['indexing_api']['google'])); ?></p>

<?php
}

function siteseo_instant_indexing_bing_api_key_callback(){
	$options = get_option('siteseo_instant_indexing_option_name');
	$check = isset($options['instant_indexing_bing_api_key']) ? esc_attr($options['instant_indexing_bing_api_key']) : null; ?>

	<input type="text" id="siteseo_instant_indexing_bing_api_key" name="siteseo_instant_indexing_option_name[instant_indexing_bing_api_key]"
	placeholder="<?php esc_html_e('Enter your Bing Instant Indexing API', 'siteseo'); ?>"
	aria-label="<?php esc_html_e('Enter your Bing Instant Indexing API', 'siteseo'); ?>"
	value="<?php echo esc_attr($check); ?>" />

	<button type="button" class="siteseo-instant-indexing-refresh-api-key btn btnSecondary"><?php esc_html_e('Generate key','siteseo'); ?></button>

	<p class="description"><?php esc_html_e('The Bing Indexing API key is automatically generated. Click Generate key if you want to recreate it, or if it\'s missing.') ?></p>
	<p class="description"><?php esc_html_e('A key should look like this: ', 'siteseo'); ?>ZjA2NWI3ZWM3MmNhNDRkODliYmY0YjljMzg5YTk2NGE=</p>
<?php
}

function siteseo_instant_indexing_automate_submission_callback(){
	$options = get_option('siteseo_instant_indexing_option_name');

	$check = isset($options['instant_indexing_automate_submission']); ?>

	<label for="siteseo_instant_indexing_automate_submission">
		<input id="siteseo_instant_indexing_automate_submission" name="siteseo_instant_indexing_option_name[instant_indexing_automate_submission]" type="checkbox"
		<?php checked($check, '1') ?>
		value="1"/>
		<?php esc_html_e('Enable automatic URL submission for IndexNow API', 'siteseo'); ?>
	</label>

	<p class="description">
		<?php esc_html_e('Notify search engines using IndexNow protocol (currently Bing and Yandex) whenever a post is created, updated or deleted.', 'siteseo'); ?>
	</p>

<?php
}

function siteseo_print_section_instant_indexing_general() {
	$docs = function_exists('siteseo_get_docs_links') ? siteseo_get_docs_links() : ''; ?>
<div class="siteseo-section-header">
	<h2>
		<?php esc_html_e('Instant Indexing', 'siteseo'); ?>
	</h2>
</div>

<p><?php esc_html_e('You can use the Indexing API to tell Google & Bing to update or remove pages from the Google / Bing index. The process can takes few minutes. You can submit your URLs in batches of 100 (max 200 request per day for Google).','siteseo'); ?></p>

<p class="siteseo-help">
	<span class="dashicons dashicons-external"></span>
	<a href="<?php echo esc_attr($docs['indexing_api']['google']); ?>" target="_blank"><?php esc_html_e('401 / 403 error?','siteseo'); ?></a>
</p>

<div class="siteseo-notice">
	<span class="dashicons dashicons-info"></span>
	<div>
		<h3><?php esc_html_e('How does this work?', 'siteseo'); ?></h3>
		<ol>
			<li><?php echo wp_kses_post(__('Setup your Google / Bing API keys from the <strong>Settings</strong> tab', 'siteseo')); ?></li>
			<li><?php echo wp_kses_post(__('<strong>Enter your URLs</strong> to index to the field below', 'siteseo')); ?></li>
			<li><?php echo wp_kses_post(__('<strong>Save changes</strong>', 'siteseo')); ?></li>
			<li><?php echo wp_kses_post(__('Click <strong>Submit URLs to Google & Bing</strong>', 'siteseo')); ?></li>
		</ol>
	</div>
</div>

<?php

$indexing_plugins = [
	'indexnow/indexnow-url-submission.php' => 'IndexNow',
	'bing-webmaster-tools/bing-url-submission.php' => 'Bing Webmaster Url Submission',
	'fast-indexing-api/instant-indexing.php' => 'Instant Indexing',
];

foreach ($indexing_plugins as $key => $value) {
	if (is_plugin_active($key)) { ?>
		<div class="siteseo-notice is-warning">
			<span class="dashicons dashicons-warning"></span>
			<div>
				<h3><?php printf(wp_kses_post(__('We noticed that you use <strong>%s</strong> plugin.', 'siteseo')), esc_html($value)); ?></h3>
				<p><?php printf(esc_html__('To prevent any conflicts with our Indexing feature, please disable it.', 'siteseo')); ?></p>
				<a class="btn btnPrimary" href="<?php echo esc_url(admin_url('plugins.php')); ?>"><?php esc_html_e('Fix this!', 'siteseo'); ?></a>
			</div>
		</div>
		<?php
	}
}

}

function siteseo_print_section_instant_indexing_settings() { ?>
<div class="siteseo-section-header">
	<h2>
		<?php esc_html_e('Settings', 'siteseo'); ?>
	</h2>
</div>
<p>
	<?php esc_html_e('Edit your Instant Indexing settings for Google and Bing.', 'siteseo'); ?>
</p>

<?php
}


//Instant Indexing SECTION=========================================================================
add_settings_section(
	'siteseo_setting_section_instant_indexing', // ID
	'',
	//__("Instant Indexing","siteseo"), // Title
	'siteseo_print_section_instant_indexing_general', // Callback
	'siteseo-settings-admin-instant-indexing' // Page
);

add_settings_field(
	'siteseo_instant_indexing_google_engine', // ID
	__('Select search engines', 'siteseo'), // Title
	'siteseo_instant_indexing_google_engine_callback', // Callback
	'siteseo-settings-admin-instant-indexing', // Page
	'siteseo_setting_section_instant_indexing' // Section
);

add_settings_field(
	'siteseo_instant_indexing_google_action', // ID
	__('Which action to run for Google?', 'siteseo'), // Title
	'siteseo_instant_indexing_google_action_callback', // Callback
	'siteseo-settings-admin-instant-indexing', // Page
	'siteseo_setting_section_instant_indexing' // Section
);

add_settings_field(
	'siteseo_instant_indexing_manual_batch', // ID
	__('Submit URLs for indexing', 'siteseo'), // Title
	'siteseo_instant_indexing_manual_batch_callback', // Callback
	'siteseo-settings-admin-instant-indexing', // Page
	'siteseo_setting_section_instant_indexing' // Section
);

add_settings_section(
	'siteseo_setting_section_instant_indexing_settings', // ID
	'',
	//__("Settings","siteseo"), // Title
	'siteseo_print_section_instant_indexing_settings', // Callback
	'siteseo-settings-admin-instant-indexing-settings' // Page
);

add_settings_field(
	'siteseo_instant_indexing_google_api_key', // ID
	__('Google Indexing API key', 'siteseo'), // Title
	'siteseo_instant_indexing_google_api_key_callback', // Callback
	'siteseo-settings-admin-instant-indexing-settings', // Page
	'siteseo_setting_section_instant_indexing_settings' // Section
);

add_settings_field(
	'siteseo_instant_indexing_bing_api_key', // ID
	__('Bing Indexing API key', 'siteseo'), // Title
	'siteseo_instant_indexing_bing_api_key_callback', // Callback
	'siteseo-settings-admin-instant-indexing-settings', // Page
	'siteseo_setting_section_instant_indexing_settings' // Section
);

add_settings_field(
	'siteseo_instant_indexing_automate_submission', // ID
	__('Automatically notify search engines', 'siteseo'), // Title
	'siteseo_instant_indexing_automate_submission_callback', // Callback
	'siteseo-settings-admin-instant-indexing-settings', // Page
	'siteseo_setting_section_instant_indexing_settings' // Section
);

$this->options = get_option('siteseo_pro_option_name');


if (function_exists('siteseo_admin_header')) {
	siteseo_admin_header();
}
?>
<form method="post"
	action="<?php echo esc_url(admin_url('options.php')); ?>"
	class="siteseo-option">
	<?php
		$current_tab = '';

		settings_fields('siteseo_instant_indexing_option_group');
	?>

	<div id="siteseo-tabs" class="wrap">
		<?php
		echo wp_kses($this->siteseo_feature_title('instant-indexing'), ['h1' => true, 'input' => ['type' => true, 'name' => true, 'id' => true, 'class' => true, 'data-*' => true], 'label' => ['for' => true], 'span' => ['id' => true, 'class' => true], 'div' => ['id' => true, 'class' => true]]);
		$plugin_settings_tabs = [
			'tab_siteseo_instant_indexing_general' => esc_html__('General', 'siteseo'),
			'tab_siteseo_instant_indexing_settings'	=> esc_html__('Settings', 'siteseo')
		];

	echo '<div class="nav-tab-wrapper">';
	foreach ($plugin_settings_tabs as $tab_key => $tab_caption) {
		echo '<a id="' . esc_attr($tab_key) . '-tab" class="nav-tab" href="?page=siteseo-instant-indexing-page#tab=' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';
	}
	echo '</div>'; ?>

	<!-- General -->
	<div class="siteseo-tab <?php if ('tab_siteseo_instant_indexing_general' == $current_tab) {
	echo 'active';
	} ?>" id="tab_siteseo_instant_indexing_general">
		<?php do_settings_sections('siteseo-settings-admin-instant-indexing'); ?>
	</div>

	<!-- Settings -->
	<div class="siteseo-tab <?php if ('tab_siteseo_instant_indexing_settings' == $current_tab) {
		echo 'active';
	} ?>" id="tab_siteseo_instant_indexing_settings">
		<?php do_settings_sections('siteseo-settings-admin-instant-indexing-settings'); ?>
	</div>

	</div>
	<!--siteseo-tabs-->
	<?php echo wp_kses_post($this->siteseo_feature_save()); ?>
	<?php siteseo_submit_button(__('Save changes', 'siteseo')); ?>
</form>
<?php
