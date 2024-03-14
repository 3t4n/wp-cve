<?php
if (!defined('ABSPATH')) {
  exit;
}
return array(
  'currency_api_key' =>
  array(
    'demo_link' => esc_url('https://v6.exchangerate-api.com'),
    'title' => esc_html__('Currency api key', 'shopready-elementor-addon'),
    'default' => '',
    'type' => 'text',
    'is_pro' => 1,
  ),
);