<?php

defined('ABSPATH') or exit('Please don&rsquo;t call the plugin directly. Thanks :)');

$this->options = get_option('siteseo_import_export_option_name');

$docs = siteseo_get_docs_links();

	if (function_exists('siteseo_admin_header')) {
		siteseo_admin_header();
	} ?>
<div class="siteseo-option">
	<?php
		$current_tab = '';
	?>
	<div id="siteseo-tabs" class="wrap">
	<?php
	echo wp_kses($this->siteseo_feature_title(null), ['h1' => true, 'input' => ['type' => true, 'name' => true, 'id' => true, 'class' => true, 'data-*' => true], 'label' => ['for' => true], 'span' => ['id' => true, 'class' => true], 'div' => ['id' => true, 'class' => true]]);
	$plugin_settings_tabs = [
		'tab_siteseo_tool_settings'		=> __('Settings', 'siteseo'),
		'tab_siteseo_tool_plugins'		=> __('Plugins', 'siteseo'),
		'tab_siteseo_tool_reset'		=> __('Reset', 'siteseo'),
	];

	$plugin_settings_tabs = apply_filters('siteseo_tools_tabs', $plugin_settings_tabs);
	echo '<div class="nav-tab-wrapper">';
	foreach ($plugin_settings_tabs as $tab_key => $tab_caption) {
		echo '<a id="' . esc_attr($tab_key) . '-tab" class="nav-tab" href="?page=siteseo-import-export#tab=' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';
	}
	echo '</div>';

		do_action('siteseo_tools_before', $current_tab, $docs);
	?>
		<div class="siteseo-tab <?php if ('tab_siteseo_tool_settings' == $current_tab) {
		echo 'active';
	} ?>" id="tab_siteseo_tool_settings">
			<div class="postbox section-tool">
				<div class="siteseo-section-header">
					<h2>
						<?php esc_html_e('Settings', 'siteseo'); ?>
					</h2>
				</div>
				<div class="inside">
					<h3><span><?php esc_html_e('Export plugin settings', 'siteseo'); ?></span>
					</h3>

					<p><?php esc_html_e('Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'siteseo'); ?>
					</p>

					<form method="post">
						<input type="hidden" name="siteseo_action" value="export_settings" />
						<?php wp_nonce_field('siteseo_export_nonce', 'siteseo_export_nonce'); ?>

						<button id="siteseo-export" type="submit" class="btn btnSecondary">
							<?php esc_html_e('Export', 'siteseo'); ?>
						</button>
					</form>
				</div><!-- .inside -->
			</div><!-- .postbox -->

			<div class="postbox section-tool">
				<div class="inside">
					<h3><span><?php esc_html_e('Import plugin settings', 'siteseo'); ?></span>
					</h3>

					<p><?php esc_html_e('Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'siteseo'); ?>
					</p>

					<form method="post" enctype="multipart/form-data">
						<p>
							<input type="file" name="import_file" />
						</p>
						<input type="hidden" name="siteseo_action" value="import_settings" />

						<?php wp_nonce_field('siteseo_import_nonce', 'siteseo_import_nonce'); ?>

						<button id="siteseo-import-settings" type="submit" class="btn btnSecondary">
							<?php esc_html_e('Import', 'siteseo'); ?>
						</button>

						<?php if(!empty($_GET['success']) && 'true' == siteseo_opt_get('success')) {
		echo '<div class="log" style="display:block"><div class="siteseo-notice is-success"><p>' . esc_html__('Import completed!', 'siteseo') . '</p></div></div>';
	} ?>
					</form>
				</div><!-- .inside -->
			</div><!-- .postbox -->
		</div>
		<div class="siteseo-tab <?php if ('tab_siteseo_tool_plugins' == $current_tab) {
		echo 'active';
	} ?>" id="tab_siteseo_tool_plugins">
			<div class="siteseo-section-header">
				<h2>
					<?php esc_html_e('Plugins', 'siteseo'); ?>
				</h2>
			</div>
			<h3><span><?php esc_html_e('Import posts and terms metadata from', 'siteseo'); ?></span>
			</h3>

			<?php
					$plugins = [
						'yoast'			=> 'Yoast SEO',
						'aio'			  => 'All In One SEO',
						'seo-framework'	=> 'The SEO Framework',
						'rk'			   => 'Rank Math',
						'squirrly'		 => 'Squirrly SEO',
						'seo-ultimate'	 => 'SEO Ultimate',
						'wp-meta-seo'	  => 'WP Meta SEO',
						'premium-seo-pack' => 'Premium SEO Pack',
						'wpseo'			=> 'wpSEO',
						'platinum-seo'	 => 'Platinum SEO Pack',
						'smart-crawl'	  => 'SmartCrawl',
						'seopressor'	   => 'SeoPressor',
						'slim-seo'		 => 'Slim SEO'
					];

	echo '<p>
							<select id="select-wizard-import" name="select-wizard-import">
								<option value="none">' . esc_html__('Select an option', 'siteseo') . '</option>';

	foreach ($plugins as $plugin => $name) {
		echo '<option value="' . esc_attr($plugin) . '-migration-tool">' . esc_html($name) . '</option>';
	}
	echo '</select>
						</p>

					<p class="description">' . esc_html__('You don\'t have to enable the selected SEO plugin to run the import.', 'siteseo') . '</p>';

	foreach ($plugins as $plugin => $name) {
		echo wp_kses_post(siteseo_migration_tool($plugin, $name));
	} ?>
		</div>
	   <?php do_action('siteseo_tools_migration', $current_tab); ?>
		<div class="siteseo-tab <?php if ('tab_siteseo_tool_reset' == $current_tab) {
		echo 'active';
	} ?>" id="tab_siteseo_tool_reset">
			<div class="postbox section-tool">
				<div class="siteseo-section-header">
					<h2>
						<?php esc_html_e('Reset', 'siteseo'); ?>
					</h2>
				</div>
				<div class="inside">
					<h3><span><?php esc_html_e('Reset All Notices From Notifications Center', 'siteseo'); ?></span>
					</h3>

					<p><?php esc_html_e('By clicking Reset Notices, all notices in the notifications center will be set to their initial status.', 'siteseo'); ?>
					</p>

					<form method="post" enctype="multipart/form-data">
						<input type="hidden" name="siteseo_action" value="reset_notices_settings" />
						<?php wp_nonce_field('siteseo_reset_notices_nonce', 'siteseo_reset_notices_nonce'); ?>
						<?php siteseo_submit_button(__('Reset notices', 'siteseo'), 'btn btnSecondary'); ?>
					</form>
				</div><!-- .inside -->
			</div><!-- .postbox -->

			<div class="postbox section-tool">
				<div class="inside">
					<h3><?php esc_html_e('Reset All Settings', 'siteseo'); ?>
					</h3>

					<div class="siteseo-notice is-warning">
						<span class="dashicons dashicons-warning"></span>
						<div>
							<p><?php echo wp_kses_post(__('<strong>WARNING:</strong> Delete all options related to this plugin in your database.', 'siteseo')); ?></p>
						</div>
					</div>

					<form method="post" enctype="multipart/form-data">
						<input type="hidden" name="siteseo_action" value="reset_settings" />
						<?php wp_nonce_field('siteseo_reset_nonce', 'siteseo_reset_nonce'); ?>
						<?php siteseo_submit_button(__('Reset settings', 'siteseo'), 'btn btnSecondary is-deletable'); ?>
					</form>
				</div><!-- .inside -->
			</div><!-- .postbox -->
		</div>
	</div>
</div>
<?php
