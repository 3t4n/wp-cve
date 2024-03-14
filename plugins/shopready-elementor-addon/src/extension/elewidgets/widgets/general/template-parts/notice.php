<?php
if (!defined('ABSPATH')) {
  exit;
}
/**
 * User Notice
 */
if (!function_exists('wc_print_notices')) {
  return;
}

if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
  \wc_add_notice(esc_html__('Editor Display only', 'shopready-elementor-addon'));
  \wc_print_notices();
} else {

  echo wp_kses_post('<div class="woocommerce-product-page-notice-wrapper width:100%">');
  wc_print_notices();
  echo wp_kses_post('</div>');


}