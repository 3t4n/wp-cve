<?php
if(!defined( 'ABSPATH' )) exit; // Exit if accessed directly

switch(htmlspecialchars(sanitize_text_field($_POST['pdckl_hidden'] ?? '')))
{
    case 'settings':
        if(!check_admin_referer('save-settings')) {
          _e('<div class="error"><p><strong>' . $pdckl_lang['n_settings_update_error'][0] . '</strong></p></div>');
        } else {
          $active = (int) sanitize_text_field($_POST['pdckl_active']);
          update_option('pdckl_active', $active);
          $purchase = (int) sanitize_text_field($_POST['pdckl_purchase']);
          update_option('pdckl_purchase', $purchase);
          $jquery = (int) sanitize_text_field($_POST['pdckl_jquery']);
          update_option('pdckl_jquery', $jquery);
          $auto = (int) sanitize_text_field($_POST['pdckl_auto']);
          update_option('pdckl_auto', $auto);
          $showform = (int) sanitize_text_field($_POST['pdckl_showform']);
          update_option('pdckl_showform', $showform);
          $title = sanitize_text_field($_POST['pdckl_title']);
          update_option('pdckl_title', $title);
          $type = sanitize_text_field($_POST['pdckl_type']);
          update_option('pdckl_type', $type);
          $banned_cats = sanitize_text_field(str_replace(' ', '', $_POST['pdckl_banned_cats']));
          update_option('pdckl_banned_cats', $banned_cats);
          $links = (int) sanitize_text_field($_POST['pdckl_links']);
          update_option('pdckl_links', $links);
          $price = (int) sanitize_text_field(str_replace(',', '.', $_POST['pdckl_price']));
          $paypal_mode = sanitize_text_field($_POST['paypal_mode']);
          update_option('pdckl_paypal_mode', $paypal_mode);

          if($price <= 0) {
              _e('<div class="error"><p><strong>' . $pdckl_lang['n_settings_update_error'][0] . '</strong></p></div>');
              $settings_error = 1;
          } else {
              update_option('pdckl_price', $price);
          }

          $price_extra = sanitize_text_field(str_replace(',', '.', $_POST['pdckl_price_extra'])) . ' ' . sanitize_text_field($_POST['pdckl_price_extra_days']);
          update_option('pdckl_price_extra', $price_extra);
          $api_username = sanitize_text_field($_POST['pdckl_api_username']);
          $api_password = sanitize_text_field($_POST['pdckl_api_password']);
          $api_signature = sanitize_text_field($_POST['pdckl_api_signature']);

          $wd_token = sanitize_text_field($_POST['pdckl_wd_token']);
          update_option('pdckl_wd_token', $wd_token);

          // Pokud je vyplněn token, ověříme správnost
          if($wd_token != '') {
            $handle = file_get_contents('https://api.copywriting.cz/podclankova-inzerce/v3/checkout.php?mode=checkToken&token='.$wd_token);
            $json = json_decode($handle);

            if($json->status == false) {
              _e('<div class="error"><p><strong>' . $pdckl_lang['n_settings_token_error'] . '</strong></p></div>');
            }
          }

          if($api_username == '' || $api_password == '' || $api_signature == '') {
              if($wd_token == '') {
                  _e('<div class="error"><p><strong>' . $pdckl_lang['n_settings_update_error'][1] . '</strong></p></div>');
                  $settings_error = 1;
              } else {
                  update_option('pdckl_api_username', esc_sql($api_username));
                  update_option('pdckl_api_password', esc_sql($api_password));
                  update_option('pdckl_api_signature', esc_sql($api_signature));
              }
          } else {
              update_option('pdckl_api_username', esc_sql($api_username));
              update_option('pdckl_api_password', esc_sql($api_password));
              update_option('pdckl_api_signature', esc_sql($api_signature));
          }

          if($settings_error == 1) {
              update_option('pdckl_active', 0);
          }

          pdckl_active_check();
          if($settings_error != 1) {
              _e('<div class="updated"><p><strong>' . $pdckl_lang['n_settings_updated'] . '</strong></p></div>');
          }
        }
    break;

    case 'order_add':
        $table_name = $wpdb->prefix . "pdckl_links";
        $pdckl_type = sanitize_text_field($_POST['pdckl_type']) == 'nofollow' ? 'rel="nofollow"' : '';
        $pdckl_flink = '<a href="' . sanitize_text_field($_POST['pdckl_link']) . '" ' . $pdckl_type . '>' . sanitize_text_field($_POST['pdckl_link_name']) . '</a> - ' . sanitize_text_field($_POST['pdckl_link_description']);

        $wpdb->insert($table_name, [
            'id' => 0,
            'id_post' => (int) $_POST['pdckl_link_pid'],
            'time' => time(),
            'link' => $pdckl_flink,
            'active' => 1
        ]);

        $link = $wpdb->get_row("SELECT MAX(id) FROM " . $wpdb->prefix . "pdckl_links", ARRAY_A);

        _e('<div class="updated"><p><strong>' . sprintf($pdckl_lang['n_orders_added'], ($link['id'] ?? 0)) . '</strong></p></div>');
    break;

    case 'order_edit':
        $id_order = (int) ($_POST['pdckl_id_order'] ?? 0);
        $link = sanitize_text_field($_POST['pdckl_link']);
        $wpdb->query("
            UPDATE " . $wpdb->prefix . "pdckl_links
            SET link = '$link'
            WHERE id = '$id_order'
        ");

        _e('<div class="updated"><p><strong>' . sprintf($pdckl_lang['n_orders_edited'], $id_order) . '</strong></p></div>');
    break;

    case 'css_save':
        if(!check_admin_referer( 'save-css' )) {
          _e('<div class="error"><p><strong>Bad access</strong></p></div>');
        } else {
          file_put_contents(PDCKL_PLUGIN_DIR . 'assets/css/podclankova-inzerce.css', $_POST['pdckl_css']);
          if(file_get_contents(plugins_url('assets/css/podclankova-inzerce.css', __FILE__)) == $_POST['pdckl_css']) {
              _e('<div class="updated"><p><strong>' . $pdckl_lang['n_css_update_done'] . '</strong></p></div>');
          } else {
              _e('<div class="error"><p><strong>' . $pdckl_lang['n_css_update_error'] . '</strong></p></div>');
          }
        }
    break;

    case 'css_reset':
        if(!check_admin_referer( 'reset-css' )) {
          _e('<div class="error"><p><strong>Bad access</strong></p></div>');
        } else {
          $css_backup = file_get_contents(plugins_url('assets/css/podclankova-inzerce_original.css', __FILE__));
          file_put_contents(PDCKL_PLUGIN_DIR . 'assets/css/podclankova-inzerce.css', $css_backup);
          if(file_get_contents(plugins_url('assets/css/podclankova-inzerce_original.css', __FILE__)) == file_get_contents(plugins_url('assets/css/podclankova-inzerce.css', __FILE__)))
          {
              _e('<div class="updated"><p><strong>' . $pdckl_lang['n_css_reset_done'] . '</strong></p></div>');
          }
          else
          {
              _e('<div class="error"><p><strong>' . $pdckl_lang['n_css_reset_error'] . '</strong></p></div>');
          }
        }
    break;

    default: pdckl_active_check(); break;
}

switch(htmlspecialchars($_GET['a'] ?? ''))
{
    case 'wd_disconnect':
        update_option("pdckl_wd_userid", "");
        _e('<div class="updated"><p><strong>' . sprintf($pdckl_lang['n_settings_wd_disconnected'], $id_order) . '</strong></p></div>');
        $api_username = get_option('pdckl_api_username');
        $api_password = get_option('pdckl_api_password');
        $api_signature = get_option('pdckl_api_signature');
        if($api_username == '' || $api_password == '' || $api_signature == '')
        {
            _e('<div class="error"><p><strong>' . $pdckl_lang['n_settings_update_error'][1] . '</strong></p></div>');
            update_option('pdckl_active', 0);
            pdckl_active_check();
        }
    break;

    case 'hide':
        $id_order = intval($_GET['order']);
        $wpdb->query("
            UPDATE " . $wpdb->prefix . "pdckl_links
            SET active = 0
            WHERE id = '$id_order'
        ");

        _e('<div class="updated"><p><strong>' . sprintf($pdckl_lang['n_orders_hiden'], $id_order) . '</strong></p></div>');
    break;

    case 'show':
        $id_order = intval($_GET['order']);
        $wpdb->query("
            UPDATE " . $wpdb->prefix . "pdckl_links
            SET active = 1
            WHERE id = '$id_order'
        ");

        _e('<div class="updated"><p><strong>' . sprintf($pdckl_lang['n_orders_shown'], $id_order) . '</strong></p></div>');
    break;

    case 'delete':
        $id_order = (int) $_GET['order'];
        ?><div class="notice">
              <p><?php echo sprintf($pdckl_lang['n_orders_delete'], $id_order); ?></p>
              <p><a href="<?php _e(PDCKL_ADMIN_LINK); ?>&s=orders&order=<?php _e($id_order); ?>&a=delete_confirm"><b><?php echo $pdckl_lang['n_orders_delete_link']; ?></b></a></p>
          </div><?php
    break;

    case 'delete_confirm':
        $id_order = (int) $_GET['order'];
        $wpdb->query("
            DELETE FROM " . $wpdb->prefix . "pdckl_links
            WHERE id = '$id_order'
        ");

        _e('<div class="updated"><p><strong>' . sprintf($pdckl_lang['n_orders_deleted'], $id_order) . '</strong></p></div>');
    break;
}
?>
