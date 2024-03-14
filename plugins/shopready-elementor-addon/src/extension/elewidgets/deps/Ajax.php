<?php

namespace Shop_Ready\extension\elewidgets\deps;

use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;

/**
 * Ajax Actions
 * @since 2.0
 */
class Ajax
{

  public function register()
  {
    // not used any where . 
    add_action("wp_ajax_shop_ready_options_update", [$this, "shop_ready_options_update"]);
  }

  public function shop_ready_options_update()
  {
    if (!check_admin_referer()) {
      wp_die();
    }
    if (!current_user_can('read')) {
      wp_die();
    }
    if (!isset($_POST['shop_ready_option_key'])) {
      return;
    }

    if (!isset($_POST['shop_ready_option_value'])) {
      return;
    }

    $option_key = sanitize_text_field($_POST['shop_ready_option_key']);
    $option_value = sanitize_text_field($_POST['shop_ready_option_value']);

    update_option($option_key, $option_value);

    wp_die();

  }

}