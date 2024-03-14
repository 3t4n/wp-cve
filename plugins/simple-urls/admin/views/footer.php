<?php
/**
 * Footer HTML
 *
 * @package Footer
 */
use LassoLite\Admin\Constant;

use LassoLite\Classes\Enum;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Page;
use LassoLite\Classes\Setting;

$current_page     = $_GET['page'] ?? '';
$import_page_slug = Helper::add_prefix_page( Enum::PAGE_IMPORT );
$ajax_url         = admin_url( 'admin-ajax.php' );

$user_email = get_option( 'admin_email' ); // phpcs:ignore
$user_email = get_option( 'lasso_license_email', $user_email );

$settings                     = Setting::get_settings();
$support_enable               = $settings[Enum::SUPPORT_ENABLED] ?? 0;
$general_disable_notification = (bool) $settings['general_disable_notification'];
$customer_flow_enabled        = (int) $settings[Enum::CUSTOMER_FLOW_ENABLED];

$lasso_lite_setting = new Setting();
$plugins_for_import = $lasso_lite_setting->check_plugins_for_import();
$import_page_link   = Page::get_lite_page_url( Enum::PAGE_IMPORT );

?>

<h6 class="text-center pt-4 pb-4" style="margin-bottom: 20px;">
	<span class="badge rounded purple-bg white font-weight-normal py-2 px-3">
		<?php print 'Version ' . LASSO_LITE_VERSION; // phpcs:ignore ?>
	</span>
</h6>

<div class="modal fade" id="modal-save-animation" data-backdrop="static" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content p-5 shadow text-center">
			<h3></h3>
			<p>Saving your changes now.</p>
			<div class="progress">
				<div class="progress-bar progress-bar-striped progress-bar-animated green-bg" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
			</div>
		</div>
	</div>
</div>

<div id="up-sell-modal">
	<p class="large">This is a premium feature.</p>
	<a href="https://getlasso.co/upgrade/" target="_blank">Upgrade to Lasso Pro</a>
</div>

<?php if ( ! $support_enable && ! $lasso_lite_setting->is_setting_onboarding_page() ) { ?>
<div id="fake-intercom-bubble-chat">
	<div class="fake-intercom-bubble-chat-app-launcher-icon">
		<i class="fas fa-question"></i>
	</div>
</div>
<?php } else { ?>
	<div id="customer-flow" class="support-wrap white-bg" data-customer-flow-enabled="<?php echo $customer_flow_enabled; ?>">
		<div class="support-option">
			<div class="p-2">
				<label>Suggest a feature</label>
			</div>
		</div>
		<div class="support-option">
			<div class="p-2">
				<label>Chat with us</label>
			</div>
		</div>
	</div>

	<div id="support-launcher">
		<i class="fas fa-question icon-default"></i>
		<i class="fas fa-times icon-close"></i>
	</div>
<?php } ?>

<?php
	echo Helper::wrapper_js_render( 'import-suggestion-jsrender', Helper::get_path_views_folder() . 'notifications/import-suggestion-jsrender.html' );
?>

<!-- MODALS -->
<?php
if ( ! $customer_flow_enabled ) {
	require_once SIMPLE_URLS_DIR . '/admin/views/modals/customer-flow-confirm.php';
}
?>

<?php
	// ? Show notification for import plugin
	if ( '' !== $plugins_for_import && ! $general_disable_notification && $import_page_slug !== $current_page && Helper::is_importable() ) :
?>
	<script>
		let json_data = [{ import_page_link: '<?php echo $import_page_link; ?>' }];
		lasso_lite_helper.inject_to_template(jQuery("#lasso_lite_notifications"), 'import-suggestion-jsrender', json_data);
	</script>
<?php endif; ?>

<?php if ( 0 !== intval( Helper::get_option( Constant::LASSO_OPTION_PERFORMANCE, '1' ) ) ): ?>
<script>
	var html = `<?php include SIMPLE_URLS_DIR . '/admin/views/notifications/performance-jsrender.html'; ?>`;
	jQuery("#lasso_lite_notifications").append(html);
	jQuery('#lasso-performance button.close').click(function() {
		let btn = jQuery(this);
		jQuery.ajax({
			url: '<?php echo $ajax_url; // phpcs:ignore ?>',
			type: 'post',
			data: {
				action: 'lasso_lite_disable_performance',
				nonce: lassoLiteOptionsData.optionsNonce,
			},
		}).done(function(res) {
			jQuery('#lasso-performance').collapse('hide');
		});
	});
</script>
<?php endif; ?>

<!-- JS errors detection -->
<script
	src="https://browser.sentry-cdn.com/7.9.0/bundle.tracing.min.js"
	integrity="sha384-a80B6QRSQ+pPpoX+H79BVaE52KTvYkQDL+lD8+TajwMxswO+ywB3p99gWNraTNrt"
	crossorigin="anonymous"
></script>

<script>
	let lasso_path = '<?php echo SIMPLE_URLS_URL; // phpcs:ignore ?>';
	let post_type = 'post_type=<?php echo SIMPLE_URLS_SLUG; // phpcs:ignore ?>';

	Sentry.init({
		dsn: '<?php echo Constant::SENTRY_DSN; // phpcs:ignore ?>',
		release: '<?php echo LASSO_LITE_VERSION; // phpcs:ignore ?>',
		ignoreErrors: [
			'ResizeObserver loop limit exceeded',
			'ResizeObserver loop completed with undelivered notifications',
			'__ez is not defined',
			'_ezaq is not defined',
			'Can\'t find variable: _ezaq',
			'wpColorPickerL10n is not defined',
			'window.jQuery(...).wpColorPicker is not a function',
		],
		integrations: [new Sentry.Integrations.BrowserTracing()],
		tracesSampleRate: 1.0,
		beforeSend(event, hint) {
			try {
				let is_lasso_lite_error = false;
				let event_id = event.event_id;
				let frames = event.exception.values[0].stacktrace.frames;
				for(let i = 0; i < frames.length; i++) {
					if(frames[i].filename.includes(lasso_path) || frames[i].filename.includes(post_type)) {
						is_lasso_lite_error = true;
						break;
					}
				}

				if(is_lasso_lite_error) {
					return event;
				}
			} catch (error) {
				console.log(error);
			}
		}
	});

	Sentry.configureScope(function(scope) {
		scope.setUser({
			email: '<?php echo $user_email; ?>',
		});
		scope.setTag('site_id', '<?php echo Helper::get_option( Constant::SITE_ID_KEY ); ?>');
        scope.setTag('wp_version', '<?php global $wp_version; echo $wp_version ?>');
	});

</script>
<!-- PHP errors detection -->
<?php
echo Helper::wrapper_js_render( 'setup-pregress-jsrender', Helper::get_path_views_folder() . 'components/setup-progress-jsrender.html' );
?>
<?php if ( $support_enable && ! $lasso_lite_setting->is_setting_onboarding_page() ) {
	$user            = get_user_by( 'email', $user_email );
	$user_name       = isset( $user->display_name ) ? $user->display_name : get_bloginfo( 'name' );
	$classic_editor  = Helper::is_classic_editor() ? 1 : 0;
	$email_support   = $settings[Enum::EMAIL_SUPPORT] ?? $user_email;
	$user_hash       = $settings[Enum::USER_HASH] ?? '';
	$lasso_lite_user = 1;
	if ( Helper::is_lasso_pro_plugin_active()) {
		$lasso_lite_user = 0;
	}
	?>

<script>
	var APP_ID = '<?php echo Constant::LASSO_INTERCOM_APP_ID; // phpcs:ignore ?>';
	var isClassicEditor = '<?php echo $classic_editor; // phpcs:ignore ?>' == 1 ? true : false;
	var lasso_lite_user = '<?php echo $lasso_lite_user; // phpcs:ignore ?>' == 1 ? true : false;
	var intercomParams = {
		app_id: APP_ID,
		name: '<?php echo addslashes( $user_name ); // phpcs:ignore ?>',
		email: '<?php echo $email_support; // phpcs:ignore ?>',
		lasso_version: parseInt('<?php echo LASSO_LITE_VERSION; // phpcs:ignore ?>'),
		classic_editor: isClassicEditor,
		wp_admin_url: '<?php echo admin_url(); // phpcs:ignore ?>',
		lasso_lite_user: lasso_lite_user,
		user_hash: '<?php echo $user_hash ?>'
	};
	window.intercomSettings = intercomParams;
</script>
<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',w.intercomSettings);}else{var d=document;var i=function(){i.c(arguments);};i.q=[];i.c=function(args){i.q.push(args);};w.Intercom=i;var l=function(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/' + APP_ID;var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);};if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>
<?php } ?>
