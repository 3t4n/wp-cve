<?php
/**
 * Functions used by plugins
 */
if (!class_exists('A2CBC_Dependencies')) {
  require_once 'class-a2c-bridge-connector-dependencies.php';
}

/**
 * WC Detection
 */
if (!function_exists('is_a2cbc_required_plugins_active')) {
  function is_a2cbc_required_plugins_active()
  {
    return A2CBC_Dependencies::required_plugins_active_check();
  }
}

function woocommerce_version_error()
{
  ?>
    <div class="error notice">
      <p><?php printf(__('Requires WooCommerce version %s or later or WP-E-Commerce.'), A2CBC_MIN_WOO_VERSION); ?></p>
    </div>
  <?php
}