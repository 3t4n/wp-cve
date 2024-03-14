<?php
/**
 * URL links
 *
 * @package Lasso URL links
 */

use LassoLite\Classes\Config;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Setting;

$lasso_options = Setting::get_settings();

$enable_support                       = Setting::get_setting('support_enabled');
$enable_support_checked               = $enable_support ? 'checked' : '';
$general_disable_amazon_notifications = $lasso_options['general_disable_amazon_notifications'] ? 'checked' : '';
$general_disable_tooltip_checked      = $lasso_options['general_disable_tooltip'] ? 'checked' : '';
$general_disable_notification         = $lasso_options['general_disable_notification'] ? 'checked' : '';
$general_enable_new_ui                = Helper::is_lite_using_new_ui() ? 'checked' : '';
?>

<?php Config::get_header(); ?>

<!-- GENERAL SETTINGS -->
<section class="px-3 py-5">
	<div class="container lite-container">
		<!-- HEADER -->
		<?php require_once 'header.php'; ?>  
		<form class="lasso-admin-settings-form" autocomplete="off" onsubmit="event.preventDefault();">
			<!-- SETTINGS -->
			<div class="row mb-5">
				<div class="col-lg">
					<div class="white-bg rounded shadow p-4 mb-lg-0 mb-5">
						<input type="hidden" name="count_all_pages_posts" value="" />

						<!-- GOOGLE ANALYTICS -->
						<section class="mb-5">
							<h3 class="lasso-lite-disabled no-hint">Google Analytics</h3>
							<p><span class="lasso-lite-disabled no-hint">Send click tracking data to Google Analytics.</span> <a data-tooltip="We support a Tracking ID or Product ID (Google Analytics v4). You can find them in Google Analytics > Admin > Property Settings."><i class="far fa-info-circle light-purple"></i></a></p>
							<div class="form-group lasso-lite-disabled">
								<div class="form-row align-items-center mb-3">
									<div class="col mb-lg-0 mb-3">
										<input disabled type="text" class="form-control" placeholder="Tracking ID or Property ID" value="">
									</div>
								</div>
								<div class="form-group align-items-center">
									<div class="" id="ga-tracking-toggle">
										<label class="toggle m-0 mb-3 mr-1">
											<input type="checkbox" disabled>
											<span class="slider"></span>
										</label>
										<label class="m-0" data-tooltip="Enable to begin tracking Lasso Link clicks in Google Analytics.">
											<span>Click Tracking</span> <i class="far fa-info-circle light-purple"></i></label>
									</div>
									<div class="" id="ga-tracking-toggle">
										<label class="toggle m-0 mb-3 mr-1">
											<input type="checkbox" disabled>
											<span class="slider"></span>
										</label>
										<label class="m-0" data-tooltip="If you have Google Analytics installed outside of Lasso, keep this disabled.">
											<span>Pageview</span> <i class="far fa-info-circle light-purple"></i></label>
									</div>
								</div>
							</div>
						</section>

						<!-- LASSO URL: REWRITE SLUG -->
						<section class="mb-5">
							<h3 class="lasso-lite-disabled no-hint">Cloaked Link Prefix</h3>
							<p ><span class="lasso-lite-disabled no-hint">Add subdirectory to cloaked links. Most leave this empty.</span> <a data-tooltip="For no prefix leave this field empty."><i class="far fa-info-circle light-purple"></i></a>
							<br/><i class="lasso-lite-disabled no-hint">Example: https://domain.com/<strong>recommends</strong>/link-name/</i></p>
							<div class="form-group lasso-lite-disabled">
								<div class="input-group">
									<input class="form-control form-control" type="text" value="" aria-label="" disabled>
								</div>
							</div>
						</section>

						<!-- PERMISSIONS -->
						<section class="mb-5 lasso-lite-disabled no-hint">
							<h3>Permissions</h3>
							<p>Select the minimum user role that can access Lasso.</p>
							<select disabled class="form-control">
								<option value="Administrator" disabled selected>Administrator</option>
							</select>
						</section>

						<!-- User Interface -->
						<section class="mb-5">
							<h3>User Interface</h3>
							<p>Toggle which UI you want enabled.</p>

							<div class="form-group">
								<label class="toggle m-0 mr-1">
									<input id="general_enable_new_ui"
										type="checkbox"
										name="general_enable_new_ui"
										<?php echo esc_html( $general_enable_new_ui ); ?>
									>
									<span class="slider"></span>
								</label>
								<label class="m-0" data-tooltip="Enable new UI for Lasso Lite link management and displays. Disable to revert to Simple URLs.">
									<span>Enable New UI</span> <i class="far fa-info-circle light-purple"></i>
								</label>
							</div>
						</section>

						<?php if ( isset( $_GET['support'] ) ): ?>
						<!-- SUPPORT -->
						<section class="mb-5">
							<h3>Support</h3>
							<p>Select the minimum user role that can access Lasso.</p>
							<div class="form-group">
								<label class="toggle m-0 mr-1">
									<input type="checkbox" <?php echo $enable_support_checked ?> name="enable_support">
									<span class="slider"></span>
								</label>
								<label class="m-0">Enable support</label>
							</div>
						</section>
						<?php endif; ?>
					</div>
				</div>

				<div class="col-lg">
					<div class="white-bg rounded shadow p-4">
						<!-- LINK DATABASE -->
						<section class="mb-4 lasso-lite-disabled no-hint">
							<h3>Link Index</h3>
							<p>Lasso tracks every link on your site.</p>

							<div class="row lasso-stats">
								<div class="col-lg mb-3">
									<div class="border rounded p-3">
										<h4 class="h6 purple font-weight-bold">Last Updated</h4>
										-
									</div>
								</div>
								<div class="col-lg mb-3">
									<div class="border rounded p-3">
										<h4 class="h6 purple font-weight-bold">Links Indexed</h4>
										-
									</div>
								</div>
							</div>
						</section>

						<!-- Custom post type support -->
						<section class="mb-5">
							<h3 class="lasso-lite-disabled no-hint">Custom Link Detection</h3>
							<p><span class="lasso-lite-disabled no-hint">Scan for links and shortcodes in custom locations.</span> <a data-tooltip="Additional locations where Lasso will discovering links."><i class="far fa-info-circle light-purple"></i></a></p>
							<div class="form-group lasso-lite-disabled no-hint">
								<label>Custom Post Types:</label>
								<select disabled class="form-control" data-placeholder="Select post types">
									<option value="post" disabled selected>Post</option>
								</select>
							</div>
						</section>

						<!-- CPU THRESHOLD -->
						<section class="mb-5">
							<h3 class="lasso-lite-disabled no-hint">Performance</h3>
							<p><span class="lasso-lite-disabled no-hint">Set the maximum CPU level that Lasso will run at.</span> <a data-tooltip="Lasso will wait until your CPU drops below this level to continue with its updates."><i class="far fa-info-circle light-purple"></i></a></p>
							<div class="form-group lasso-lite-disabled">
								<div class="input-group">
									<input disabled class="form-control form-control-append" type="text" value="80" aria-label="">
									<div class="input-group-append lasso-lite-disabled no-hint">
										<span class="input-group-text">%</span>
									</div>
								</div>
							</div>
						</section>

						<!-- NEW LINK DEFAULT OPTIONS -->
						<section class="mb-5">
							<div class="lasso-lite-disabled no-hint">
								<h3>Link Defaults</h3>
								<p>Set the default attributes for new links.</p>
							</div>
							<div class="form-group">
								<label class="toggle m-0 mr-1 lasso-lite-disabled no-hint">
									<input type="checkbox" checked disabled>
									<span class="slider"></span>
								</label>
								<label class="m-0" data-tooltip="When enabled, users who click this link will have it loaded in a new tab.">
									<span class="lasso-lite-disabled no-hint">New Window / Tab</span> <i class="far fa-info-circle light-purple"></i>
								</label>
							</div>

							<div class="form-group">
								<label class="toggle m-0 mr-1 lasso-lite-disabled no-hint">
									<input type="checkbox" checked disabled>
									<span class="slider"></span>
								</label>
								<label class="m-0" data-tooltip="When enabled, this link will be set to nofollow. This indicates to Google that it's an affiliate link.">
									<span class="lasso-lite-disabled no-hint">NoFollow / NoIndex</span> <i class="far fa-info-circle light-purple"></i></label>
							</div>

							<div class="form-group">
								<label class="toggle m-0 mr-1 lasso-lite-disabled no-hint">
									<input type="checkbox" checked disabled>
									<span class="slider"></span>
								</label>
								<label class="m-0" data-tooltip="When enabled, this link will be set to sponsored.">
									<span class="lasso-lite-disabled no-hint">Sponsored</span> <i class="far fa-info-circle light-purple"></i></label>
							</div>

							<div class="form-group">
								<label class="toggle m-0 mr-1 lasso-lite-disabled no-hint">
									<input type="checkbox" checked disabled>
									<span class="slider"></span>
								</label>
								<label class="m-0" data-tooltip="When enabled, this link will show the disclosure.">
									<span class="lasso-lite-disabled no-hint">Show Disclosure</span> <i class="far fa-info-circle light-purple"></i></label>
							</div>
						</section>

						<!-- NOTIFICATIONS -->
						<section class="mb-5">
							<h3>Notifications</h3>
							<p>Toggle which notifications you want enabled.</p>

							<div class="form-group">
								<label class="toggle m-0 mr-1">
									<input id="general_disable_amazon_notifications"
										type="checkbox"
										name="general_disable_amazon_notifications" <?php echo esc_html( $general_disable_amazon_notifications ); ?>>
									<span class="slider"></span>
								</label>
								<label class="m-0">Disable Configure Amazon Notification</label>
							</div>

							<div class="form-group">
								<label class="toggle m-0 mr-1">
									<input id="general_disable_tooltip"
										type="checkbox"
										name="general_disable_tooltip" <?php echo esc_html( $general_disable_tooltip_checked ); ?>>
									<span class="slider"></span>
								</label>
								<label class="m-0">Disable Help Tooltips</label>
							</div>

							<div class="form-group mb-1">
								<label class="toggle m-0 mr-1">
									<input id="general_disable_notification"
										type="checkbox"
										name="general_disable_notification" <?php echo esc_html( $general_disable_notification ); ?>>
									<span class="slider"></span>
								</label>
								<label class="m-0">Disable Import Notifications</label>
							</div>
						</section>
					</div>
				</div>

			</div>

			<!-- SAVE CHANGES -->
			<div class="row align-items-center">
				<div class="col-lg text-lg-right text-center">
					<button id="btn-save-settings-general" type="button" class="btn">Save Changes</button>
				</div>
			</div>
		</form>
	</div>
</section>

<?php echo Helper::wrapper_js_render( 'default-template-notification', Helper::get_path_views_folder() . '/notifications/default-template-jsrender.html' )?>
<?php Config::get_footer(); ?>
