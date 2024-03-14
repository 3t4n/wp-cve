<div class="wrap">
<h2 style="display:none;"><?php _e('PopupAlly Settings'); ?></h2>
<?php settings_errors(PopupAlly::SETTING_KEY_ALL); ?>
<div id="popupally-import-wait-overlay">
	<img src="<?php echo PopupAlly::$PLUGIN_URI; ?>resource/backend/img/wait.gif" alt="Importing" width="128" height="128" />
</div>
<div id="popupally-loading-overlay">
	<div>
		<img src="<?php echo PopupAlly::$PLUGIN_URI; ?>resource/backend/img/wait.gif" alt="Importing" width="128" height="128" />
		<span>Initializing Polite Opt-In Sequence...</span>
	</div>
</div>
<table class="popupally-setting-container">
	<tbody>
		<tr>
			<td class="popupally-setting-left-col"/>
			<td class="popupally-setting-title-cell popupally-setting-right-col">
				<div class="popupally-title-block">
					<div class="popupally-setting-title">PopupAlly</div>

					<div class="popupally-setting-section-help-text"><div class="popupally-info-icon"></div>Need extra help? View our documentation and tutorials <a class="underline" target="_blank" href="<?php echo PopupAlly::HELP_URL; ?>">here</a>!</div>
					<?php if ($show_opt_in) { ?>
					<form target="_blank" action="" id="popupally-free-optin" method="POST">
						<img id="popupally-free-optin-img" src="" />
						<div id="popupally-free-optin-text"></div>
						<input id="popupally-free-optin-name" type="text" name="" required="required" placeholder="Name" value="<?php echo $admin_name; ?>" />
						<input id="popupally-free-optin-email" type="text" name="" required="required" placeholder="Email" value="<?php echo $admin_email; ?>" />
						<input id="popupally-free-optin-submit" type="submit" value="" />
					</form>
					<?php } ?>
				</div>
			</td>
		</tr>
		<tr>
			<td class="popupally-setting-left-col popupally-setting-tab-label-col <?php echo $setting['selected-tab']==='display'?'popupally-setting-tab-active':''; ?>" click-target=".selected-tab" click-value="display" tab-group="popup-tab-group-1" target="display" active-class="popupally-setting-tab-active">
				<div style="background-image: url('<?php echo PopupAlly::$PLUGIN_URI; ?>resource/backend/img/display-icon.png');" class="popupally-tab-label">
					Display Settings
				</div>
			</td>
			<td rowspan="7" class="popupally-setting-content-cell popupally-setting-right-col">
				<div class="popupally-setting-content-container" style="display:<?php echo $setting['selected-tab']==='display'?'block':'none'; ?>;" popup-tab-group-1="display">
					<div class="popupally-option-setting-form" serialize-target="<?php echo PopupAllyDisplaySettings::SETTING_KEY_DISPLAY; ?>">
					<?php PopupAllyDisplaySettings::show_display_settings(); ?>
					</div>
				</div>
				<div class="popupally-setting-content-container" style="display:<?php echo $setting['selected-tab']==='style'?'block':'none'; ?>;" popup-tab-group-1="style">
					<div class="popupally-option-setting-form" serialize-target="<?php echo PopupAllyStyleSettings::SETTING_KEY_STYLE; ?>">
					<?php PopupAllyStyleSettings::show_style_settings(); ?>
					</div>
				</div>
				<div class="popupally-setting-content-container" style="display:<?php echo $setting['selected-tab']==='stats'?'block':'none'; ?>;" popup-tab-group-1="stats">
					<div class="popupally-setting-section">
						<div class="popupally-setting-section-header">Want to track stats?</div>
						<a class="popupally-trial-button" href="https://popupally.com/upgrading-to-popupally-pro/" target="_blank"><span class="popupally-click-arrow"></span>Get PopupAlly Pro now!</a>
						<div class="popupally-setting-configure-block">
							<a href="https://popupally.com/upgrading-to-popupally-pro/" target="_blank">
								<img class="popupally-sample-image" src="<?php echo PopupAlly::$PLUGIN_URI; ?>resource/backend/img/stats-sample.png" />
							</a>
						</div>
					</div>
				</div>
				<div class="popupally-setting-content-container" style="display:<?php echo $setting['selected-tab']==='split'?'block':'none'; ?>;" popup-tab-group-1="split">
					<div class="popupally-setting-section">
						<div class="popupally-setting-section-header">Want to split test?</div>
						<a class="popupally-trial-button" href="https://popupally.com/upgrading-to-popupally-pro/" target="_blank"><span class="popupally-click-arrow"></span>Get PopupAlly Pro now!</a>
						<div class="popupally-setting-configure-block">
							<a href="https://popupally.com/upgrading-to-popupally-pro/" target="_blank">
								<img class="popupally-sample-image" src="<?php echo PopupAlly::$PLUGIN_URI; ?>resource/backend/img/split-test-sample.png" />
							</a>
						</div>
					</div>
				</div>
				<div class="popupally-setting-content-container" style="display:<?php echo $setting['selected-tab']==='advanced'?'block':'none'; ?>;" popup-tab-group-1="advanced">
					<div class="popupally-option-setting-form" serialize-target="<?php echo PopupAllyAdvancedSettings::SETTING_KEY_ADVANCED; ?>">
					<?php PopupAllyAdvancedSettings::show_advanced_settings(); ?>
					</div>
				</div>
				<div class="popupally-setting-content-container" style="display:<?php echo $setting['selected-tab']==='toolkit'?'block':'none'; ?>;" popup-tab-group-1="toolkit">
					<?php include dirname(__FILE__) . '/setting-toolkit.php'; ?>
				</div>
			</td>
		</tr>
		<tr>
			<td class="popupally-setting-left-col popupally-setting-tab-label-col <?php echo $setting['selected-tab']==='style'?'popupally-setting-tab-active':''; ?>" click-target=".selected-tab" click-value="style" tab-group="popup-tab-group-1" target="style" active-class="popupally-setting-tab-active">
				<div style="background-image: url('<?php echo PopupAlly::$PLUGIN_URI; ?>resource/backend/img/style-icon.png');" class="popupally-tab-label">
					Style Settings
				</div>
			</td>
		</tr>
		<tr>
			<td class="popupally-setting-left-col popupally-setting-tab-label-col <?php echo $setting['selected-tab']==='stats'?'popupally-setting-tab-active':''; ?>" click-target=".selected-tab" click-value="stats" tab-group="popup-tab-group-1" target="stats" active-class="popupally-setting-tab-active">
				<div style="background-image: url('<?php echo PopupAlly::$PLUGIN_URI; ?>resource/backend/img/stats-icon.png');" class="popupally-tab-label">
					Statistics
				</div>
			</td>
		</tr>
		<tr>
			<td class="popupally-setting-left-col popupally-setting-tab-label-col <?php echo $setting['selected-tab']==='split'?'popupally-setting-tab-active':''; ?>" click-target=".selected-tab" click-value="split" tab-group="popup-tab-group-1" target="split" active-class="popupally-setting-tab-active">
				<div style="background-image: url('<?php echo PopupAlly::$PLUGIN_URI; ?>resource/backend/img/split-test-icon.png');" class="popupally-tab-label">
					Split Test
				</div>
			</td>
		</tr>
		<tr>
			<td class="popupally-setting-left-col popupally-setting-tab-label-col <?php echo $setting['selected-tab']==='advanced'?'popupally-setting-tab-active':''; ?>" click-target=".selected-tab" click-value="advanced" tab-group="popup-tab-group-1" target="advanced" active-class="popupally-setting-tab-active">
				<div style="background-image: url('<?php echo PopupAlly::$PLUGIN_URI; ?>resource/backend/img/advanced-icon.png');" class="popupally-tab-label">
					Advanced Settings
				</div>
			</td>
		</tr>
		<tr>
			<td class="popupally-setting-left-col popupally-setting-tab-label-col <?php echo $setting['selected-tab']==='toolkit'?'popupally-setting-tab-active':''; ?>" click-target=".selected-tab" click-value="toolkit" tab-group="popup-tab-group-1" target="toolkit" active-class="popupally-setting-tab-active">
				<div style="background-image: url('<?php echo PopupAlly::$PLUGIN_URI; ?>resource/backend/img/toolbox-icon.png');" class="popupally-tab-label">
					Toolkit
				</div>
			</td>
		</tr>
		<tr class="popupally-setting-filler-row">
			<td class="popupally-setting-left-col"><br/></td>
		</tr>
		<tr class="popupally-setting-last-row">
			<td class="popupally-setting-left-col" />
			<td class="popupally-setting-right-col">
				<form class="popupally-option-submit-form" enctype="multipart/form-data" method="post" action="options.php">
					<?php settings_fields(PopupAlly::SETTING_KEY_ALL); ?>
					<input type="hidden" name="<?php echo PopupAlly::SETTING_KEY_ALL; ?>[selected][selected-tab]" id="selected-tab" class="selected-tab popupally-update-follow-scroll" value="<?php echo $setting['selected-tab']; ?>" />
					<input type="hidden" name="<?php echo PopupAlly::SETTING_KEY_ALL; ?>[<?php echo PopupAllyDisplaySettings::SETTING_KEY_DISPLAY; ?>]" class="<?php echo PopupAllyDisplaySettings::SETTING_KEY_DISPLAY; ?>" value="" />
					<input type="hidden" name="<?php echo PopupAlly::SETTING_KEY_ALL; ?>[<?php echo PopupAllyStyleSettings::SETTING_KEY_STYLE; ?>]" class="<?php echo PopupAllyStyleSettings::SETTING_KEY_STYLE; ?>" value="" />
					<input type="hidden" name="<?php echo PopupAlly::SETTING_KEY_ALL; ?>[<?php echo PopupAllyAdvancedSettings::SETTING_KEY_ADVANCED; ?>]" class="<?php echo PopupAllyAdvancedSettings::SETTING_KEY_ADVANCED; ?>" value="" />
					<input class="popupally-setting-submit-button" type="submit" value="Save Changes" />
				</form>
			</td>
		</tr>
	</tbody>
</table>
<div id="popupally-bottom-banner" class="popupally-banner">
	<a id="popupally-bottom-banner-link" target="_blank" href=""><img id="popupally-bottom-banner-img" /></a>
</div>
</div>
