<?php
defined('ABSPATH') || exit;

$page = sanitize_key(filter_input( INPUT_GET, 'page', FILTER_DEFAULT ));
?>
<div class="wrap imagelinks">
	<?php require('page-info.php'); ?>
	<div class="imagelinks-page-header">
		<div class="imagelinks-title"><?php esc_html_e('ImageLinks Settings', 'imagelinks'); ?></div>
	</div>
	<div class="imagelinks-messages" id="imagelinks-messages">
	</div>
	<!-- imagelinks app -->
	<div id="imagelinks-app-settings" class="imagelinks-app" style="display:none;">
		<div class="imagelinks-loader-wrap">
			<div class="imagelinks-loader">
				<div class="imagelinks-loader-bar"></div>
				<div class="imagelinks-loader-bar"></div>
				<div class="imagelinks-loader-bar"></div>
				<div class="imagelinks-loader-bar"></div>
			</div>
		</div>
		<div class="imagelinks-wrap">
			<div class="imagelinks-workplace">
				<div class="imagelinks-main-tabs imagelinks-clear-fix">
					<div class="imagelinks-tab" al-attr.class.imagelinks-active="appData.ui.tabs.general" al-on.click="appData.fn.onTab(appData, 'general')"><?php esc_html_e('General', 'imagelinks'); ?></div>
					<div class="imagelinks-tab" al-attr.class.imagelinks-active="appData.ui.tabs.actions" al-on.click="appData.fn.onTab(appData, 'actions')"><?php esc_html_e('Actions', 'imagelinks'); ?></div>
					<div class="imagelinks-tab">
						<div class="imagelinks-button imagelinks-blue" al-on.click="appData.fn.saveConfig(appData);"><?php esc_html_e('Save', 'imagelinks'); ?></div>
					</div>
				</div>
				<div class="imagelinks-main-data">
					<div al-if="appData.ui.tabs.general">
						<div class="imagelinks-stage">
							<div class="imagelinks-main-panel">
								<div class="imagelinks-data imagelinks-active">
									<div class="imagelinks-control">
										<div class="imagelinks-helper" title="<?php esc_html_e('Choose a default theme for your custom javascript editor', 'imagelinks'); ?>"></div>
										<div class="imagelinks-label"><?php esc_html_e('JavaScript editor theme', 'imagelinks'); ?></div>
										<select class="imagelinks-select" al-select="appData.config.themeJavaScript">
											<option al-option="null"><?php esc_html_e('default', 'imagelinks'); ?></option>
											<option al-repeat="theme in appData.themes" al-option="theme.id">{{theme.title}}</option>
										</select>
									</div>
									<div class="imagelinks-control">
										<div class="imagelinks-helper" title="<?php esc_html_e('Choose a default theme for your custom css editor', 'imagelinks'); ?>"></div>
										<div class="imagelinks-label"><?php esc_html_e('CSS editor theme', 'imagelinks'); ?></div>
										<select class="imagelinks-select" al-select="appData.config.themeCSS">
											<option al-option="null"><?php esc_html_e('default', 'imagelinks'); ?></option>
											<option al-repeat="theme in appData.themes" al-option="theme.id">{{theme.title}}</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div al-if="appData.ui.tabs.actions">
						<div class="imagelinks-stage">
							<div class="imagelinks-main-panel">
								<div class="imagelinks-data imagelinks-active">
									<div class="imagelinks-control">
										<div class="imagelinks-helper" title="<?php esc_html_e('Delete all items from database', 'imagelinks'); ?>"></div>
										<div class="imagelinks-button imagelinks-red imagelinks-long" al-on.click="appData.fn.deleteAllData(appData, '. <?php esc_html_e('Do you really want to delete all data?', 'imagelinks'); ?> . ');"><?php esc_html_e('Delete all data', 'imagelinks'); ?></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /end imagelinks app -->
</div>