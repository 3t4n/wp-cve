<?php
$config = array();

$config['plugin_screen'] = 'settings_page_maintenance_mode_options';
$config['icon_border'] = '1px solid #00000099';
$config['icon_right'] = '25px';
$config['icon_bottom'] = '75px';
$config['icon_image'] = 'csmm.png';
$config['icon_padding'] = '6px';
$config['icon_size'] = '55px';
$config['menu_accent_color'] = '#fe2929';
$config['custom_css'] = '#wf-flyout .csmm-icon .wff-icon img { max-width: 70%; } #wf-flyout .csmm-icon .wff-icon { line-height: 57px; }';

$config['menu_items'] = array(
  array('href' => '#pro', 'label' => 'Get Coming Soon PRO with 50% off', 'icon' => 'csmm.png', 'class' => 'csmm-icon accent csmm-change-tab'),
  array('href' => 'https://wpforcessl.com/?ref=wff-csmm', 'label' => 'Fix all SSL problems &amp; monitor site in real-time', 'icon' => 'wp-ssl.png', 'class' => 'wpfssl-icon'),
  array('href' => 'https://wp301redirects.com/?ref=wff-csmm&coupon=50off', 'label' => 'Fix 2 most common SEO issues on WordPress', 'icon' => '301-logo.png', 'class' => 'wp301-icon'),
  array('href' => 'https://wpreset.com/?ref=wff-csmm&coupon=50off', 'target' => '_blank', 'label' => 'Get WP Reset PRO with 50% off', 'icon' => 'wp-reset.png'),
  array('href' => 'https://wpsticky.com/?ref=wff-csmm', 'target' => '_blank', 'label' => 'Make a menu sticky with WP Sticky', 'icon' => 'dashicons-admin-post'),
  array('href' => 'https://wordpress.org/support/plugin/minimal-coming-soon-maintenance-mode/reviews/#new-post', 'target' => '_blank', 'label' => 'Rate the Plugin', 'icon' => 'dashicons-thumbs-up'),
  array('href' => 'https://wordpress.org/support/plugin/minimal-coming-soon-maintenance-mode/#new-post', 'target' => '_blank', 'label' => 'Get Support', 'icon' => 'dashicons-sos'),
);
