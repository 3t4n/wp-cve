<?php
$config = array();

$config['plugin_screen'] = 'settings_page_easy-hide-login';
$config['icon_border'] = 'none';
$config['icon_right'] = '35px';
$config['icon_bottom'] = '35px';
$config['icon_image'] = 'lockdown.png';
$config['icon_padding'] = '7px';
$config['icon_size'] = '65px';
$config['menu_accent_color'] = '#1c3aa9';
$config['custom_css'] = '#wf-flyout .wff-menu-item .dashicons.dashicons-universal-access { font-size: 30px; padding: 0px 10px 0px 0; } #wf-flyout .ucp-icon .wff-icon img { max-width: 66%; } #wf-flyout .ucp-icon .wff-icon { line-height: 57px; } #wf-flyout .wpr-icon .wff-icon { line-height: 62px; } #wf-flyout .wp301-icon .wff-icon img { max-width: 66%; } #wf-flyout .wp301-icon .wff-icon { line-height: 57px; } #wf-flyout .wpfssl-icon .wff-icon img { max-width: 66%; } #wf-flyout .wpfssl-icon .wff-icon { line-height: 57px; } #wf-flyout #wff-button img { margin-left: 2px; }';

$config['menu_items'] = array(
  array('href' => '#', 'data' => 'data-pro-feature="ehl-flyout"', 'label' => 'Get Login Lockdown PRO with a special discount', 'icon' => 'lockdown.png', 'class' => 'lockdown-icon open-pro-dialog'),
  array('href' => 'https://wpforcessl.com/?ref=wff-ehl', 'label' => 'Fix all SSL problems &amp; monitor site in real-time', 'icon' => 'wp-ssl.png', 'class' => 'wpfssl-icon'),
  array('href' => 'https://wp301redirects.com/?ref=wff-ehl&coupon=50off', 'label' => 'Fix 2 most common SEO issues on WordPress that most people ignore', 'icon' => '301-logo.png', 'class' => 'wp301-icon'),
  array('href' => 'https://wpreset.com/?ref=wff-ehl&coupon=50off', 'target' => '_blank', 'label' => 'Get WP Reset PRO with 50% off', 'icon' => 'wp-reset.png', 'class' => 'wpr-icon'),
  array('href' => 'https://underconstructionpage.com/?ref=wff-ehl&coupon=welcome', 'target' => '_blank', 'label' => 'Create the perfect Under Construction Page', 'icon' => 'ucp.png', 'class' => 'ucp-icon'),
  array('href' => 'https://wpsticky.com/?ref=wff-ehl', 'target' => '_blank', 'label' => 'Make any element (header, widget, menu) sticky with WP Sticky', 'icon' => 'dashicons-admin-post'),
  array('href' => 'https://wordpress.org/support/plugin/easy-hide-login/reviews/#new-post', 'target' => '_blank', 'label' => 'Rate the Plugin', 'icon' => 'dashicons-thumbs-up'),
  array('href' => 'https://wordpress.org/support/plugin/easy-hide-login/', 'target' => '_blank', 'label' => 'Get Support', 'icon' => 'dashicons-sos'),
);
