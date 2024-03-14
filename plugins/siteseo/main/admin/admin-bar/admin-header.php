<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

function siteseo_admin_header($context = '') {
	$docs = siteseo_get_docs_links();

echo '<div id="siteseo-header" class="siteseo-option">
	<div id="siteseo-navbar">
		<img src = "'. esc_url(SITESEO_ASSETS_DIR).'/img/logo-24.svg'.'">
		<ul>
			<li><a href="'.esc_url(admin_url('admin.php?page=siteseo')).'">'.esc_html__('Home', 'siteseo').'</a></li>';


			if (get_admin_page_title()) {
				echo '<li>'.esc_html(get_admin_page_title()).'</li>';
			}

		echo '</ul>
	</div>
	<aside id="siteseo-activity-panel" class="siteseo-activity-panel">
		<div role="tablist" aria-orientation="horizontal" class="siteseo-activity-panel-tabs">
			<button type="button" role="tab" aria-selected="true" id="activity-panel-tab-display" data-panel="display" class="btn hide-panel">
				<span class="dashicons dashicons-layout"></span>
				<span class = "floating-label">'.esc_html__('Display', 'siteseo').'</span>
			</button>
			<button type="button" role="tab" aria-selected="true" id="activity-panel-tab-help" data-panel="help"
				class="btn hide-panel">
				<span class="dashicons dashicons-editor-help"></span>
				<span class = "floating-label">'.esc_html__('Help', 'siteseo').'</span>
			</button>
			<button type="button" role="tab" aria-selected="true" id="activity-panel-tab-expand" data-panel="expand"
				class="btn">
				<span class="dashicons dashicons-plus-alt2"></span>
			</button>
		</div>
		<div id="siteseo-activity-panel-help" class="siteseo-activity-panel-wrapper" tabindex="0" role="tabpanel"
			aria-label="Help">
			<span class="dashicons dashicons-no-alt siteseo-close-panel" data-panel="help"></span>
			<div id="activity-panel-true">
				<div class="siteseo-activity-panel-header">
					<div class="siteseo-inbox-title">
						<p>'.esc_html__('Documentation', 'siteseo').'</p>
					</div>
				</div>
				<div>
					<form action="'.esc_attr($docs['website']).'" method="get" class="siteseo-search" target="_blank">
						<input class="adminbar-input" id="siteseo-search" name="s" type="text" value="" placeholder="'.esc_html__('Search our documentation', 'siteseo').'" maxlength="150">
						<label for="siteseo-search" class="screen-reader-text">'.esc_html__('Search', 'siteseo').'</label>
					</form>
					<ul class="siteseo-list-items" role="menu">';
					
						$docs_started = $docs['get_started'];
						foreach ($docs_started as $key => $value) {
							foreach ($value as $_key => $_value) {
								echo '<li class="siteseo-item">
									<a href="'.esc_url($_value).'"
										class="siteseo-item-inner has-action" aria-disabled="false" tabindex="0" role="menuitem" target="_blank" data-link-type="external">
										<div class="siteseo-item-before"></div>
										<div class="siteseo-item-text">
											<span class="siteseo-item-title">'.esc_html($_key).'</span>
										</div>
										<div class="siteseo-item-after"></div>
									</a>
								</li>';
							}
						}
					echo '</ul>
				</div>
			</div>
		</div>
		<div id="siteseo-activity-panel-display" class="siteseo-activity-panel-wrapper" tabindex="0" role="popover"
			aria-label="Display">			
			<span class="dashicons dashicons-no-alt siteseo-close-panel" data-panel="display"></span>
			<div id="activity-panel-true">
				<div class="siteseo-activity-panel-header">
					<div class="siteseo-inbox-title">
						<p>'.esc_html__('Choose the way it looks', 'siteseo').'</p>
					</div>
				</div>
				<div class="siteseo-activity-panel-content">';
			
					$options = get_option('siteseo_advanced_option_name');
					$check = isset($options['appearance_notifications']);

					echo '<p>
						<input id="notifications_center" class="toggle" data-toggle="'.(('1' == $check) ? '1' : '0').'" name="siteseo_advanced_option_name[appearance_notifications]" type="checkbox" '.(('1' == $check) ? 'checked="yes"' : '').'/>
						<label for="notifications_center"></label>
						<label for="siteseo_advanced_option_name[appearance_notifications]">'.esc_html__('Hide Notifications Center?', 'siteseo').'</label>
					</p>';

					$check = isset($options['appearance_news']);

					echo '<p>
						<input id="siteseo_news" class="toggle" data-toggle="'.(('1' == $check) ? '1' : '0').'" name="siteseo_advanced_option_name[appearance_news]" type="checkbox" '.(('1' == $check) ? 'checked="yes"' : '').'/>
						<label for="siteseo_news"></label>
						<label for="siteseo_advanced_option_name[appearance_news]">'.esc_html__('Hide SEO News?', 'siteseo').'</label>
					</p>';

			
					$check = isset($options['appearance_seo_tools']);

					echo '<p>
						<input id="siteseo_tools" class="toggle" data-toggle="'.(('1' == $check) ? '1' : '0'). '" name="siteseo_advanced_option_name[appearance_seo_tools]" type="checkbox" '.(('1' == $check) ? 'checked="yes"' : '').'/>
						<label for="siteseo_tools"></label>
						<label for="siteseo_advanced_option_name[appearance_seo_tools]">'.esc_html__('Hide Site Overview?', 'siteseo').'</label>
					</p>
				</div>
			</div>
		</div>
	</aside>
	<div class="siteseo-nav-link">
		<ul>
			<li><a href="'.esc_url(SITESEO_DOCS).'" target="_blank">Docs</a></li>
			<li><a href="'.esc_url(SITESEO_SUPPORT).'" target="_blank">Support</a></li>
		</ul>
	</div>
</div>';
}
