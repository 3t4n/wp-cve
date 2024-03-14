<?php
	if ( ! defined( 'ABSPATH' ) ) exit; /* Prevent direct access */


	require_once(IFSO_PLUGIN_BASE_DIR . 'services/plugin-settings-service/plugin-settings-service.class.php');

	use IfSo\Services\PluginSettingsService;

	$settingsServiceInstance
	    = PluginSettingsService\PluginSettingsService::get_instance();

	$pagesVisitedOption = 
		$settingsServiceInstance->pagesVisitedOption->get();
	$durationValue = $pagesVisitedOption->get_duration_value();
	$durationType = $pagesVisitedOption->get_duration_type();

	$removePluginDataOption = 
		$settingsServiceInstance->removePluginDataOption->get();

	$applyTheContentFilterOption = 
		$settingsServiceInstance->applyTheContentFilterOption->get();

	$removeAutoPTagOption = 
		$settingsServiceInstance->removeAutoPTagOption->get();

	$removePageVisitsCookie =
		$settingsServiceInstance->removePageVisitsCookie->get();

	$allowFragmentedCacheOption = 
		$settingsServiceInstance->allowFragmentedCacheOption->get();

	$allowShortcodesInTitle = 
		$settingsServiceInstance->allowShortcodesInTitle->get();

	$disableCache = 
		$settingsServiceInstance->disableCache->get();

	$ajaxAnalytics = $settingsServiceInstance->ajaxAnalytics->get();

	$disableAnalytics = $settingsServiceInstance->disableAnalytics->get();

	$userGroupLimit = $settingsServiceInstance->userGroupLimit->get();

	$groupsCookieLifespan = $settingsServiceInstance->groupsCookieLifespan->get();

	$renderTriggersViaAjax = $settingsServiceInstance->renderTriggersViaAjax->get();

	$preventNocacheHeaders = $settingsServiceInstance->preventNocacheHeaders->get();

	$forceDoShortcode = $settingsServiceInstance->forceDoShortcode->get();

	$disableSessions = $settingsServiceInstance->disableSessions->get();

	$scheduleInterval = $settingsServiceInstance->scheduleInterval->get();

	$triggersVisitedOn = $settingsServiceInstance->triggersVisitedOn->get();

    $triggersVisitedNumber = $settingsServiceInstance->triggersVisitedNumber->get();

    $ajaxLoaderType = $settingsServiceInstance->ajaxLoaderAnimationType->get();
    $ajaxLoaderType = is_numeric($ajaxLoaderType) ? (int) $ajaxLoaderType : $ajaxLoaderType; //compat
    $ajax_loaders = \IfSo\PublicFace\Services\AjaxTriggersService\AjaxTriggersService::get_instance()->get_ajax_loader_list('prettynames');

    $tmce_force_wrapper = $settingsServiceInstance->tmceForceWrapper->get();

    $enable_visit_count  = $settingsServiceInstance->enableVisitCount->get();
?>
<style>
    .ifso-settings-form .form-table tbody tr[valign] td+td{
        max-width:750px;
    }
</style>

<div class="wrap">
	<h2>
		<?php _e('If-So Dynamic Content | Settings', 'if-so'); ?>
	</h2>

	<div class="ifso-settings-page-wrapper">

		<form method="post" action="options.php" class="ifso-settings-form">

			<table class="form-table ifso-settings-tbl">
				<tbody>
					<tr class="ifso-settings-title" valign="top">
						<th class="ifso-settings-td" scope="row" valign="top">
							<?php _e('GENERAL', 'if-so'); ?>
						</th>
					</tr>
					<tr valign="top">
						<td class="ifso-settings-td" scope="row" valign="baseline">
							<b><?php _e('Page caching compatibility', 'if-so'); ?></b>
						</td>
						<td valign="baseline" style="vertical-align:baseline;">
							<input
								type="checkbox"
								<?php echo ($renderTriggersViaAjax ? "CHECKED" : ""); ?>
								name="ifso_settings_page_render_triggers_via_ajax"
								type="text"
								class="ifso_settings_page_option"
                                value="render_triggers_via_ajax" /><i><?php _e('Check this box to set Ajax as the default way to render triggers. Dynamic content will be loaded in a separate request after the cached content is loaded.', 'if-so');?> <a target="_blank" href="https://www.if-so.com/help/documentation/ajax-loading/?utm_source=Plugin&utm_medium=settings&utm_campaign=AjaxLoading-learnMore"><?php _e('Learn more.','if-so') ?></a> </i>
						</td>
					</tr>
                    <tr valign="top">
                        <td class="ifso-settings-td" scope="row" valign="baseline">
                            <b><?php _e('Ajax Loading Placeholder', 'if-so'); ?></b>
                        </td>
                        <td valign="baseline">
                            <select
                                    name="ifso_settings_ajax_loader_animation_type"
                                    class="ifso_settings_page_option"
                                    style="width:20%;">
                                <?php
                                    $loader_iter = 0;//compat stuff
                                    foreach ($ajax_loaders as $key=>$name){
                                        $selected = ($ajaxLoaderType===$key || $ajaxLoaderType===$loader_iter) ? 'SELECTED' : '';
                                        echo "<option value='{$key}' {$selected}>{$name}</option>";
                                        $loader_iter++;
                                    }
                                ?>
                            </select>
                            <i><?php _e('Select one of the options if you want the default content or a loader animation to be displayed before dynamic content is loaded via Ajax.','if-so'); ?></i>
                        </td>
                    </tr>
					<tr valign="top">
						<td class="ifso-settings-td" scope="row" valign="baseline">
							<b><?php _e('Allow shortcodes in titles and menus', 'if-so'); ?></b>
						</td>
						<td valign="baseline" style="vertical-align:baseline;">
							<input
								type="checkbox"
								<?php echo ($allowShortcodesInTitle ? "CHECKED" : ""); ?>
								name="ifso_settings_pages_allow_shortcodes"
								type="text"
								class="ifso_settings_page_option"
								value="allow_shortcodes" /><i><?php _e('Check this box to allow shortcode usage in pages and post titles and menu items. Note: In some cases, shortcodes in titles and menus can not be loaded using Ajax.', 'if-so'); ?></i>
						</td>
					</tr>
					<tr valign="top">
						<td class="ifso-settings-td" scope="row" valign="top">
							<b><?php _e('Apply “the_content” filter', 'if-so'); ?></b>
						</td>
						<td>
							<input
								type="checkbox"
								<?php echo ($applyTheContentFilterOption ? "CHECKED" : ""); ?>
								name="ifso_settings_pages_apply_the_content_filter"
								type="text"
								class="ifso_settings_page_option"
								value="apply_the_content_filter" />
							<i><?php _e('Check this box if you are using a third party content editor and encounter bugs.', 'if-so'); ?></i>
						</td>
					</tr>
					<tr valign="top">
						<td class="ifso-settings-td" scope="row" valign="baseline">
							<b><?php _e('Remove wrapping Paragraph Tags', 'if-so'); ?></b>
						</td>
						<td valign="baseline" style="vertical-align:baseline;">
							<input
								type="checkbox"
								<?php echo ($removeAutoPTagOption ? "CHECKED" : ""); ?>
								name="ifso_settings_pages_remove_auto_p_tag"
								type="text"
								class="ifso_settings_page_option"
								value="remove_auto_p_tag" /><i><?php _e('Check this box to prevent WordPress from wrapping If-So shortcodes with &lt;p&gt; tags.', 'if-so'); ?></i>
						</td>
					</tr>

					<tr valign="top">
						<td class="ifso-settings-td" scope="row" valign="baseline">
							<b><?php _e('Remove data on uninstall', 'if-so'); ?></b>
						</td>
						<td valign="baseline">
							<input 
								type="checkbox"
								<?php echo ($removePluginDataOption ? "CHECKED" : ""); ?>
								name="ifso_settings_pages_remove_data_uninstall"
								type="text"
								class="ifso_settings_page_option"
								value="remove_data_on_uninstall" />
								<i><?php _e('Check this box if you want to delete your triggers and settings when you uninstall the plugin.', 'if-so'); ?></i>
						</td>
					</tr>

                    <tr valign="top">
                        <td class="ifso-settings-td" scope="row" valign="baseline">
                            <b><?php _e('TinyMCE &lt;p&gt; tag wrapping', 'if-so'); ?></b>
                        </td>
                        <td valign="baseline">
                            <input type="checkbox"
                                <?php echo ($tmce_force_wrapper ? "CHECKED" : ""); ?>
                                   name="ifso_settings_tmce_force_wrapper"
                                   type="text"
                                   class="ifso_settings_page_option"/>
                            <i><?php _e("Check to allow the TinyMCE editor to wrap text nodes in &lt;p&gt; tags",'if-so'); ?></i>
                        </td>
                    </tr>

					<tr class="ifso-settings-title" valign="top">
						<th class="ifso-settings-td" scope="row" valign="top">
							<?php _e('ANALYTICS', 'if-so'); ?>
						</th>
					</tr>
					<tr valign="top">
						<td class="ifso-settings-td" scope="row" valign="baseline">
							<b><?php _e('Disable analytics', 'if-so'); ?></b>
						</td>
						<td valign="baseline">
							<input
								type="checkbox"
								<?php echo ($disableAnalytics ? "CHECKED" : ""); ?>
								name="ifso_settings_pages_analytics_disabled"
								type="text"
								class="ifso_settings_page_option"
								value="analytics_disabled" />
							<i><?php _e('Check this box to disable statistics collection.', 'if-so'); ?></i>
						</td>
					</tr>
					<tr valign="top">
						<td class="ifso-settings-td" scope="row" valign="baseline">
							<b><?php _e('Use Ajax for analytics calls', 'if-so'); ?></b>
						</td>
						<td valign="baseline">
							<input
								type="checkbox"
								<?php echo ($ajaxAnalytics ? "CHECKED" : ""); ?>
								name="ifso_settings_pages_analytics_with_ajax"
								type="text"
								class="ifso_settings_page_option"
								value="analytics_with_ajax" />
							<i><?php _e('When this box is checked data collection will be performed using Ajax. Uncheck the box to perform collection during the rendering of the page. Keep this box checked if you are using the Gutenberg editor.', 'if-so'); ?> <a href="https://www.if-so.com/help/documentation/analytics/?utm_source=Plugin&utm_medium=settings&utm_campaign=analyticsAjax-learnMore#anc_ajax-vs-rendering" target="_blank"><?php _e('Learn more.', 'if-so');?></a></i>
						</td>
					</tr>
					<tr valign="top">
						<td  class="ifso-settings-td" scope="row" valign="baseline">
							<b><?php _e('Reset all analytics data', 'if-so'); ?></b>
						</td>
						<td valign="baseline">
							<i>
								<a class="resetAllAnalyticsCounters" href="javascript:resetAllAnalyticsDataAction();"><?php _e('Click here'); ?></a>
								<?php _e("to reset all of If-So's analytics data.", 'if-so'); ?>
							</i>

						</td>
					</tr>

                    <tr class="ifso-settings-title" valign="top">
                        <th class="ifso-settings-td" scope="row" valign="top">
                            <?php _e('GEOLOCATION', 'if-so'); ?>
                        </th>
                    </tr>
                    <tr valign="top">
                        <td class="ifso-settings-td" scope="row" valign="baseline">
                            <b><?php _e('Disable use of PHP sessions', 'if-so'); ?></b>
                        </td>
                        <td valign="baseline">
                            <input
                                    type="checkbox"
                                <?php echo ($disableSessions ? "CHECKED" : ""); ?>
                                    name="ifso_settings_disable_sessions"
                                    type="text"
                                    class="ifso_settings_page_option"
                                    value="disable_sessions" />
                            <i><?php _e('Use cookies instead of php sessions to cache geolocation data. Enable this option if you want to avoid the use of PHP sessions. The cookie will not be set unless the user encounters a usage of the geolocation functionality.', 'if-so'); ?></i>
                        </td>
                    </tr>
                    <?php do_action('ifso_extra_settings_display_ui_geolocation'); ?>

					<tr class="ifso-settings-title" valign="top">
						<th class="ifso-settings-td" scope="row" valign="top">
							<?php _e('PAGES VISITED CONDITION', 'if-so'); ?>
						</th>
					</tr>
					<tr valign="top">
						<td class="ifso-settings-td" scope="row" valign="baseline">
							<b><?php _e('Deactivate "Pages Visited" Cookie', 'if-so'); ?></b>
						</td>
						<td valign="baseline" style="vertical-align:baseline;">
							<input
								type="checkbox"
								<?php echo ($removePageVisitsCookie ? "CHECKED" : ""); ?>
								name="ifso_settings_pages_remove_visits_cookie"
								type="text"
								class="ifso_settings_page_option"
								value="remove_visits_cookie" /><i><?php _e('The “Pages Visited” condition relies on this cookie. Check this box if you are not using or planning to use the condition.', 'if-so'); ?></i>
						</td>
					</tr>
					<tr valign="top">
						<td class="ifso-settings-td" scope="row" valign="baseline">
							<b><?php _e('“Pages visited” tracking time', 'if-so'); ?></b>
						</td>
						<td valign="baseline">
							<input
								name="ifso_settings_pages_visited_value"
								type="text"
								class="ifso_settings_page_option ifso_setting_page_option_number_select"
								value="<?php echo $durationValue ?>" />
							<select name="ifso_settings_pages_visited_type">
								<option value="minutes" <?php echo ($durationType == "minutes" ? "SELECTED" : ""); ?>>
									<?php _e('Minutes', 'if-so'); ?>
								</option>
								<option value="hours" <?php echo ($durationType == "hours" ? "SELECTED" : ""); ?>>
									<?php _e('Hours', 'if-so'); ?>
								</option>
								<option value="days" <?php echo ($durationType == "days" ? "SELECTED" : ""); ?>>
									<?php _e('Days', 'if-so'); ?>
								</option>
								<option value="weeks" <?php echo ($durationType == "weeks" ? "SELECTED" : ""); ?>>
									<?php _e('Weeks', 'if-so'); ?>
								</option>
								<option value="months" <?php echo ($durationType == "months" ? "SELECTED" : ""); ?>>
									<?php _e('Months', 'if-so'); ?>
								</option>
							</select>

							<i><?php _e("The lifespan of the 'Pages Visited' condition cookie. Dynamic content will be displayed if a visitor has previously visited the selected pages during this time period.", 'if-so'); ?></i>
						</td>
					</tr>
					<!--<tr style="display:none" valign="top">
						<td class="ifso-settings-td" scope="row" valign="baseline">
							<b><?php _e('Disable Cache On...', 'if-so'); ?></b>
						</td>
						<td valign="baseline" style="vertical-align:baseline;">
							<input 
								type="checkbox"
								<?php echo ($disableCache ? "CHECKED" : ""); ?>
								name="ifso_settings_pages_disable_cache"
								type="text"
								class="ifso_settings_page_option"
								value="disable_cache" /><i><?php _e('Check this box to disable cache on...', 'if-so'); ?></i>
						</td>
					</tr>

					 <tr valign="baseline">
						<td class="ifso-settings-td" scope="row" valign="baseline">
							<b><?php _e('Allow fragmented cache');?></b>
						</td>
						<td valign="baseline" style="vertical-align:baseline;">
							<input 
								type="checkbox"
								<?php echo ($allowFragmentedCacheOption ? "CHECKED" : ""); ?>
								name="ifso_settings_pages_allow_fragmented_cache"
								type="text"
								class="ifso_settings_page_option"
								value="allow_fragmented_cache" /><?php _e('FILL BY ASAF'); ?>
						</td>
					</tr> -->

                    <tr class="ifso-settings-title" valign="top">
                        <th class="ifso-settings-td" scope="row" valign="top">
                            <?php _e('TRIGGERS VISITED CONDITION', 'if-so'); ?>
                        </th>
                    </tr>

                    <tr valign="top">
                        <td class="ifso-settings-td" scope="row" valign="baseline">
                            <b><?php _e('Enable "Triggers Visited" Cookie', 'if-so'); ?></b>
                        </td>
                        <td valign="baseline">
                            <input
                                    type="checkbox"
                                <?php echo ($triggersVisitedOn ? "CHECKED" : ""); ?>
                                    name="ifso_settings_triggers_visited_on"
                                    type="text"
                                    class="ifso_settings_page_option"
                                    value="triggers_visited_On" />
                            <i><?php _e('Check this box if you would like to use the "Triggers Visited" condition. A cookie with the cookie name "ifso_viewed_triggers" will be used for the tracking.', 'if-so'); ?></i>
                        </td>
                    </tr>

                    <tr valign="top">
                        <td class="ifso-settings-td" scope="row" valign="baseline">
                            <b><?php _e('Number of visited triggers to store', 'if-so'); ?></b>
                        </td>
                        <td valign="baseline">
                            <input
                                    name="ifso_settings_triggers_visited_number"
                                    type="number"
                                    class="ifso_settings_page_option"
                                    value="<?php echo $triggersVisitedNumber; ?>" />
                            <i><?php _e('Storing too much data in cookies can cause errors','if-so'); ?></i>
                        </td>
                    </tr>

                    <tr class="ifso-settings-title" valign="top">
                        <th class="ifso-settings-td" scope="row" valign="top">
                            <?php _e('RETURNING VISITOR CONDITION', 'if-so'); ?>
                        </th>
                    </tr>
                    <tr valign="top">
                        <td class="ifso-settings-td" scope="row" valign="baseline">
                            <b><?php _e('Enable visit count', 'if-so'); ?></b>
                        </td>
                        <td valign="baseline">
                            <input
                                    type="checkbox"
                                <?php echo ($enable_visit_count ? "CHECKED" : ""); ?>
                                    name="ifso_settings_enable_visit_count"
                                    type="text"
                                    class="ifso_settings_page_option"
                                    value="enable_visit_counts" />
                            <i><?php _e('Check this box to track the number of total page visits per user. This option must be checked in order to use the Returning Visitor condition. A cookie with the cookie name "ifso_visit_counts" will be used for the tracking.', 'if-so'); ?></i>
                        </td>
                    </tr>


                    <tr class="ifso-settings-title" valign="top">
                        <th class="ifso-settings-td" scope="row" valign="top">
                            <?php _e('TIME AND DATE CONDITIONS', 'if-so'); ?>
                        </th>
                    </tr>

                    <tr valign="top">
                        <td class="ifso-settings-td" scope="row" valign="baseline">
                            <b><?php _e('Schedule interval', 'if-so'); ?></b>
                        </td>
                        <td valign="baseline">
                            <input
                                    name="ifso_settings_schedule_interval"
                                    type="number"
                                    class="ifso_settings_page_option"
                                    value="<?php echo $scheduleInterval; ?>" /><span> Minutes.</span>
                            <i><?php _e('Select the duration for each timeslot in the schedule condition table.','if-so'); ?></i>
                        </td>
                    </tr>

					<tr class="ifso-settings-title" valign="top">
						<th class="ifso-settings-td" scope="row" valign="top">
							<?php _e('AUDIENCES', 'if-so'); ?>
						</th>
					</tr>
					<tr valign="top">
						<td class="ifso-settings-td" scope="row" valign="baseline">
							<b><?php _e('Max. Audiences per user', 'if-so'); ?></b>
						</td>
						<td valign="baseline">
							<input
								type="number"
								name="ifso_settings_pages_user_group_limit"
								class="ifso_settings_page_option"
								value="<?php echo $userGroupLimit; ?>" />
							<i><?php _e('The maximum number of audiences a user can belong to. Adding a user to an audience beyond this limit will remove them from the earliest audience they were added to.', 'if-so'); ?></i>
						</td>
					</tr>
					<tr valign="top">
						<td class="ifso-settings-td" scope="row" valign="baseline">
							<b><?php _e('Audiences cookie lifespan', 'if-so'); ?></b>
						</td>
						<td valign="baseline">
							<input
								<?php echo ($ajaxAnalytics ? "CHECKED" : ""); ?>
								name="ifso_settings_pages_groups_cookie_lifespan"
								type="number"
								class="ifso_settings_page_option"
								value="<?php echo $groupsCookieLifespan; ?>" /><span> Days.</span>
							<i><?php _e('The lifespan of the cookie responsible for associating the user with Audiences. The lifespan resets every time the user is added or removed from a group.','if-so'); ?></i>
						</td>
					</tr>

                    <tr class="ifso-settings-title" valign="top">
                        <th class="ifso-settings-td" scope="row" valign="top">
                            <?php _e('ADVANCED', 'if-so'); ?>
                        </th>
                    </tr>

                    <tr valign="top">
                        <td class="ifso-settings-td" scope="row" valign="baseline">
                            <b><?php _e('Prevent no-cache headers', 'if-so'); ?></b>
                        </td>
                        <td valign="baseline">
                            <input
                                    type="checkbox"
                                <?php echo ($preventNocacheHeaders ? "CHECKED" : ""); ?>
                                    name="ifso_settings_prevent_nocache_headers"
                                    type="text"
                                    class="ifso_settings_page_option"
                                    value="prevent_nocache" />
                            <i><?php _e('Enable this option to prevent no-cache headers from being sent as a result of If-So using PHP sessions. Check this box if you are using a CDN and pages are not being served from the cache.', 'if-so'); ?></i>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td class="ifso-settings-td" scope="row" valign="baseline">
                            <b><?php _e('Force extra do_shortcode', 'if-so'); ?></b>
                        </td>
                        <td valign="baseline">
                            <input
                                    type="checkbox"
                                <?php echo ($forceDoShortcode ? "CHECKED" : ""); ?>
                                    name="ifso_settings_force_do_shortcode"
                                    type="text"
                                    class="ifso_settings_page_option"
                                    value="force_do_shortcode" />
                            <i><?php _e('Check this box to enable the usage of shortcodes inside triggers content fields without the use of the "the_content" filter.', 'if-so'); ?></i>
                        </td>
                    </tr>



                    <?php do_action('ifso_extra_settings_display_ui'); ?>

					<tr valign="top">
						<td>
							<?php
								wp_nonce_field( 
									'ifso_settings_nonce',
									'ifso_settings_nonce' 
								);
							?>
							<input
								type="submit"
								class="button-primary"
								name="ifso_settings_page_update"
								value="<?php _e('Save', 'if-so'); ?>"/>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>