<?php
if (!defined('ABSPATH'))
{
    exit; // Exit if accessed directly.
}
$plugin_name = $this->data->get('plugin_name');
$plugin_alias = $this->data->get('plugin_alias');
$current_version = $this->data->get('current_version');
$last_updated = $this->data->get('last_updated');
$version = $this->data->get('version');
?>
<div class="fpf-update-notice-wrapper">
	<div class="fpf-update-notice-wrapper--info">
		<svg width="40" height="41" viewBox="0 0 40 41" fill="none" xmlns="http://www.w3.org/2000/svg">
			<mask id="mask0_975_1957" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="40" height="41">
				<rect y="0.5" width="40" height="40" fill="#D9D9D9"/>
			</mask>
			<g mask="url(#mask0_975_1957)">
				<path d="M17.6347 27.589L28.8398 16.3839L27.0834 14.6276L17.6347 24.0763L12.8847 19.3263L11.1283 21.0826L17.6347 27.589ZM20.0028 36.3326C17.8129 36.3326 15.7546 35.917 13.8277 35.0859C11.9007 34.2548 10.2246 33.1269 8.79925 31.7022C7.37386 30.2774 6.24543 28.602 5.41396 26.676C4.58248 24.7499 4.16675 22.692 4.16675 20.5021C4.16675 18.3122 4.5823 16.2538 5.41342 14.3269C6.24453 12.4 7.37244 10.7239 8.79716 9.29852C10.2219 7.87313 11.8973 6.7447 13.8234 5.91323C15.7494 5.08175 17.8074 4.66602 19.9972 4.66602C22.1871 4.66602 24.2455 5.08157 26.1724 5.91268C28.0993 6.7438 29.7755 7.87171 31.2008 9.29643C32.6262 10.7212 33.7547 12.3966 34.5861 14.3226C35.4176 16.2487 35.8333 18.3066 35.8333 20.4965C35.8333 22.6864 35.4178 24.7448 34.5867 26.6717C33.7556 28.5986 32.6276 30.2747 31.2029 31.7001C29.7782 33.1255 28.1028 34.2539 26.1767 35.0854C24.2507 35.9169 22.1927 36.3326 20.0028 36.3326ZM20 33.8326C23.7223 33.8326 26.875 32.541 29.4584 29.9576C32.0417 27.3743 33.3334 24.2215 33.3334 20.4993C33.3334 16.7771 32.0417 13.6243 29.4584 11.041C26.875 8.45764 23.7223 7.16598 20 7.16598C16.2778 7.16598 13.125 8.45764 10.5417 11.041C7.95837 13.6243 6.66671 16.7771 6.66671 20.4993C6.66671 24.2215 7.95837 27.3743 10.5417 29.9576C13.125 32.541 16.2778 33.8326 20 33.8326Z" fill="#0F9D58"/>
			</g>
		</svg>
		<div class="fpf-update-notice-wrapper--info--details">
			<div class="fpf-update-notice-wrapper--info--details--title"><?php esc_html_e(sprintf(fpframework()->_('FPF_X_VERSION_IS_AVAILABLE'), $plugin_name . ' ' . $version)); ?></div>
			<div class="fpf-update-notice-wrapper--info--details--subtitle"><?php esc_html_e(sprintf(fpframework()->_('FPF_AN_UPDATED_VERSION_IS_AVAILABLE'), $plugin_name, $last_updated)); ?> <a href="<?php echo esc_url(sprintf(FPF_PLUGIN_CHANGELOG_URL, $plugin_alias)); ?>"></a></div>
		</div>
	</div>
	<div class="fpf-update-notice-wrapper--actions">
		<a href="<?php echo admin_url('plugins.php'); ?>" class="fpf-update-notice-wrapper--actions--button"><?php esc_html_e(fpframework()->_('FPF_UPDATE_NOW')); ?></a>
		<a href="#" class="fpf-update-notice-wrapper--actions--notice-close fpf-update-notice-wrapper--actions--link">
			<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
				<mask id="mask0_975_1969" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="25">
					<rect y="0.5" width="24" height="24" fill="#D9D9D9"/>
				</mask>
				<g mask="url(#mask0_975_1969)">
					<path d="M6.40002 19.1534L5.34619 18.0995L10.9462 12.4995L5.34619 6.89953L6.40002 5.8457L12 11.4457L17.6 5.8457L18.6538 6.89953L13.0538 12.4995L18.6538 18.0995L17.6 19.1534L12 13.5534L6.40002 19.1534Z" fill="currentColor"/>
				</g>
			</svg>
		</a>
	</div>
</div>