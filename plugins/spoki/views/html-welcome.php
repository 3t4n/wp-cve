<?php
$has_wc = spoki_has_woocommerce();
$is_current_tab = $GLOBALS['current_tab'] == 'welcome';
$has_spoki_settings = isset($this->options['secret']) && $this->options['secret'] != '' && isset($this->options['delivery_url']) && $this->options['delivery_url'] != '';

if ($is_current_tab) {
	Spoki()->fetch_secret_status();
	if (!$has_spoki_settings) include_once 'html-onboarding.php';
	if ($has_spoki_settings) include_once 'html-account-overview.php';
	if (!$has_wc || !$has_spoki_settings) include_once 'html-spoki-overview.php';
}