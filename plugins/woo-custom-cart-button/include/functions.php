<?php
/*
** Start Setting Section
** Cart setting in setting menu
*/
add_action('admin_menu', 'wcatcbll_cart_setting_menu');
function wcatcbll_cart_setting_menu()
{
  add_options_page(__('CATCBNL', 'catcbll'), __('Custom Cart Button', 'catcbll'), 'manage_options', 'hwx-wccb', 'wcatcbll_wccb_options_page');
}

/* 
** Insert all setting in option table
** @use insert key is _woo_cstmbtn_all_settings
**/
add_action('wp_ajax_catcbll_save_option', 'catcbll_save_option');
function catcbll_save_option()
{
  $final_data = array();
  parse_str($_POST['form_data'], $final_data);
  $btn_action =  sanitize_text_field($_POST['action']);

  if (isset($btn_action) && $btn_action == "catcbll_save_option" && isset($_POST['security_nonce']) && wp_verify_nonce($_POST['security_nonce'], 'ajax_public_nonce')) {
    foreach ($final_data as $final_data_key => $final_data_val) {
      $btn_clr_key = array('catcbll_btn_bg', 'catcbll_btn_fclr', 'catcbll_btn_border', 'catcbll_btn_hvrclr');
      if (in_array($final_data_key, $btn_clr_key, true)) {
        $cbtn_setting = $final_data_val;
      } else {
        $cbtn_setting  = sanitize_text_field($final_data_val);
      }
      $save_data[sanitize_key($final_data_key)] = $cbtn_setting;
    }
    update_option('_woo_catcbll_all_settings', $save_data);
    echo json_encode("Success");
    die;
  }
}
add_action('admin_head', 'catcbll_disable_notice');
function catcbll_disable_notice()
{
    if (isset($_REQUEST['page']) && $_REQUEST['page'] == 'hwx-wccb') { ?>
     <style>
      .notice,
      .updated {
          display: none;
      }
      </style> <?php
    }
}

?>