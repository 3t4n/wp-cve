<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $pdckl_plugin_version;

$active = get_option('pdckl_active');
$purchase = get_option('pdckl_purchase');
$jquery = get_option('pdckl_jquery');
$banned_cats = get_option('pdckl_banned_cats');
$title = get_option('pdckl_title');
$links = get_option('pdckl_links');
$price = get_option('pdckl_price');
$price_extra = explode(" ", get_option('pdckl_price_extra'));
$auto = get_option('pdckl_auto');
$showform = get_option('pdckl_showform');
$type = get_option('pdckl_type');
$pixel = get_option('pdckl_pixel');
$api_username = get_option('pdckl_api_username');
$api_password = get_option('pdckl_api_password');
$api_signature = get_option('pdckl_api_signature');
$paypal_mode = get_option('pdckl_paypal_mode');
$wd_token = get_option('pdckl_wd_token');
?>

<div style="min-width: 320px;">
<form name="pdckl_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <h3><?php echo $pdckl_lang['settings_plugin_global_title']; ?></h3>
    <p class="desc"><?php echo $pdckl_lang['settings_plugin_desc']; ?></p>
    <table class="form-table padding-top-0 margin-top-5" style="width: 100%;">
       <tbody>
           <tr>
              <th><label for="pdckl_active"><?php echo $pdckl_lang['settings_plugin_enable'][0]; ?></label></th>
              <td id="pdckl_admin_settings_help" width="32"><?php echo pdckl_show_help('h_settings_status'); ?></td>
              <td>
                  <label style="margin-right:15px;"><input type="radio" name="pdckl_active" value="1" <?php if($active == 1){_e('checked="checked"');} ?>><?php echo $pdckl_lang['settings_plugin_enable'][1]; ?></label>
                  <label><input type="radio" name="pdckl_active" value="0" <?php if($active == 0){_e('checked="checked"');} ?>><?php echo $pdckl_lang['settings_plugin_enable'][2]; ?></label>
              </td>
           </tr>
           <tr>
              <th><label for="pdckl_purchase"><?php echo $pdckl_lang['settings_plugin_purchase'][0]; ?></label></th>
              <td id="pdckl_admin_settings_help"><?php echo pdckl_show_help('h_settings_purchase'); ?></td>
              <td>
                  <label style="margin-right:15px;"><input type="radio" name="pdckl_purchase" value="1" <?php if($purchase == 1){_e('checked="checked"');} ?>><?php echo $pdckl_lang['settings_plugin_purchase'][1]; ?></label>
                  <label><input type="radio" name="pdckl_purchase" value="0" <?php if($purchase == 0){_e('checked="checked"');} ?>><?php echo $pdckl_lang['settings_plugin_purchase'][2]; ?></label>
              </td>
           </tr>
           <tr>
              <th><label for="pdckl_jquery"><?php echo $pdckl_lang['settings_plugin_jquery'][0]; ?></label></th>
              <td id="pdckl_admin_settings_help"><?php echo pdckl_show_help('h_settings_jquery'); ?></td>
              <td>
                  <label style="margin-right:15px;"><input type="radio" name="pdckl_jquery" value="1" <?php if($jquery == 1){_e('checked="checked"');} ?>><?php echo $pdckl_lang['settings_plugin_jquery'][1]; ?></label>
                  <label><input type="radio" name="pdckl_jquery" value="0" <?php if($jquery == 0){_e('checked="checked"');} ?>><?php echo $pdckl_lang['settings_plugin_jquery'][2]; ?></label>
              </td>
           </tr>
           <tr>
              <th><label for="pdckl_auto"><?php echo $pdckl_lang['settings_plugin_auto'][0]; ?></label></th>
              <td id="pdckl_admin_settings_help"><?php echo pdckl_show_help('h_settings_auto'); ?></td>
              <td>
                  <label style="margin-right:15px;"><input type="radio" name="pdckl_auto" class="autofunction" value="1" <?php if($auto == 1){_e('checked="checked"');} ?>><?php echo $pdckl_lang['settings_plugin_auto'][1]; ?></label>
                  <label><input type="radio" name="pdckl_auto" class="autofunction" value="0" <?php if($auto == 0){_e('checked="checked"');} ?>><?php echo $pdckl_lang['settings_plugin_auto'][2]; ?></label>
              </td>
           </tr>
           <tr id="manual" <?php if($auto == 1) { _e('style="display: none;"'); } ?>>
              <th><label for="pdckl_auto_function"><?php echo $pdckl_lang['settings_plugin_auto_function']; ?></label></th>
              <td></td>
              <td>
                <code style="margin-left: 10px;">&lt;?php podclankova_inzerce_box();?&gt;</code>
              </td>
           </tr>
           <tr>
              <th><label for="pdckl_showform"><?php echo $pdckl_lang['settings_plugin_showform'][0]; ?></label></th>
              <td id="pdckl_admin_settings_help"><?php echo pdckl_show_help('h_settings_showform'); ?></td>
              <td>
                  <label style="margin-right:15px;"><input type="radio" name="pdckl_showform" value="1" <?php if($showform == 1){_e('checked="checked"');} ?>><?php echo $pdckl_lang['settings_plugin_showform'][1]; ?></label>
                  <label><input type="radio" name="pdckl_showform" value="0" <?php if($showform == 0){_e('checked="checked"');} ?>><?php echo $pdckl_lang['settings_plugin_showform'][2]; ?></label>
              </td>
           </tr>
           <tr>
              <th><label for="pdckl_title"><?php echo $pdckl_lang['settings_plugin_title']; ?></label></th>
              <td id="pdckl_admin_settings_help"><?php echo pdckl_show_help('h_settings_title'); ?></td>
              <td><input type="text" name="pdckl_title" value="<?php _e($title); ?>" style="width: 320px;" maxlength="64"></td>
           </tr>
           <tr>
              <th><label for="pdckl_banned_cats"><?php echo $pdckl_lang['settings_plugin_banned_cats']; ?></label></th>
              <td id="pdckl_admin_settings_help"><?php echo pdckl_show_help('h_settings_banned_cats'); ?></td>
              <td><input type="text" name="pdckl_banned_cats" value="<?php _e($banned_cats); ?>"></td>
           </tr>
           <tr>
              <th><label for="pdckl_links"><?php echo $pdckl_lang['settings_plugin_links']; ?></label></th>
              <td id="pdckl_admin_settings_help"><?php echo pdckl_show_help('h_settings_max_links'); ?></td>
              <td><input type="text" name="pdckl_links" value="<?php _e($links); ?>" style="width: 50px;"></td>
           </tr>
           <tr>
              <th><label for="pdckl_price"><?php echo $pdckl_lang['settings_plugin_price']; ?></label></th>
              <td id="pdckl_admin_settings_help"><?php echo pdckl_show_help('h_settings_link_price'); ?></td>
              <td><input type="text" name="pdckl_price" value="<?php _e($price); ?>" style="width: 50px;"> <?php echo $pdckl_lang['settings_currency']; ?></td>
           </tr>
           <tr>
              <th><label for="pdckl_price_extra"><?php echo $pdckl_lang['settings_plugin_price_ext'][0]; ?></label></th>
              <td id="pdckl_admin_settings_help"><?php echo pdckl_show_help('h_settings_link_price_ext'); ?></td>
              <td><input type="text" name="pdckl_price_extra" value="<?php _e($price_extra[0]); ?>" style="width: 50px;"> <?php echo $pdckl_lang['settings_currency'] . ' ' . $pdckl_lang['settings_plugin_price_ext'][1]; ?> <input type="text" style="width:50px;" name="pdckl_price_extra_days" value="<?php _e($price_extra[1]); ?>"> <?php echo $pdckl_lang['settings_plugin_price_ext'][2]; ?></td>
           </tr>
           <tr>
              <th><label><?php echo $pdckl_lang['settings_type']; ?></label></th>
              <td id="pdckl_admin_settings_help"><?php echo pdckl_show_help('h_settings_type'); ?></td>
              <td>
                <select name="pdckl_type">
                  <option value="both" <?php echo $type == 'both' ? 'selected="selected"' : '' ?>><?php echo $pdckl_lang['settings_type_both']; ?></option>
                  <option value="follow" <?php echo $type == 'follow' ? 'selected="selected"' : '' ?>><?php echo $pdckl_lang['settings_type_follow']; ?></option>
                  <option value="nofollow" <?php echo $type == 'nofollow' ? 'selected="selected"' : '' ?>><?php echo $pdckl_lang['settings_type_nofollow']; ?></option>
                </select>
           </tr>
           <tr>
              <th><label for="pdckl_pixel"><?php echo $pdckl_lang['settings_pixel']; ?></label></th>
              <td id="pdckl_admin_settings_help"><?php echo pdckl_show_help('h_settings_pixel'); ?></td>
              <td><input type="text" name="pdckl_pixel" value="<?php _e($pixel); ?>" maxlength="250" style="width: 320px;"></td>
           </tr>
       </tbody>
    </table>

    <h3><?php echo $pdckl_lang['settings_copywriting_title']; ?></h3>
    <p class="desc"><?php echo $pdckl_lang['settings_copywriting_desc']; ?></p>
    <table class="form-table padding-top-0 margin-top-5" style="width: 100%;">
      <tbody>
        <tr>
          <td colspan="2">
            <?php echo str_replace('www.', '', sprintf($pdckl_lang['h_settings_copywriting'], $_SERVER['HTTP_HOST'])); ?>
          </td>
        </tr>
        <tr>
          <th style="width: 160px;"><label for="pdckl_wd_token"><?php echo $pdckl_lang['settings_wd_api_token']; ?></label></th>
          <td><input type="text" name="pdckl_wd_token" value="<?php _e($wd_token); ?>" autocomplete="off" class="large-text"></td>
        </tr>
      </tbody>
    </table>

    <h3><?php echo $pdckl_lang['settings_paypal_title']; ?></h3>
    <table class="form-table padding-top-0 margin-top-5" style="width: 100%;">
      <tbody>
        <tr>
          <td colspan="2">
            <?php echo $pdckl_lang['settings_paypal_desc']; ?>
          </td>
        </tr>
        <tr>
          <th style="width: 160px;"><label for="pdckl_api_username"><?php echo $pdckl_lang['settings_paypal_api_user']; ?></label></th>
          <td><input type="text" name="pdckl_api_username" value="<?php _e($api_username); ?>" class="regular-text"></td>
        </tr>
        <tr>
          <th><label for="pdckl_api_password"><?php echo $pdckl_lang['settings_paypal_api_pwd']; ?></label></th>
          <td><input type="text" name="pdckl_api_password" value="<?php _e($api_password); ?>" class="regular-text"></td>
        </tr>
        <tr>
          <th><label for="pdckl_api_signature"><?php echo $pdckl_lang['settings_paypal_api_sign']; ?></label></th>
          <td><input type="text" name="pdckl_api_signature" value="<?php _e($api_signature); ?>" class="regular-text"></td>
        </tr>
        <tr>
          <th><label for="pdckl_paypal_mode"><?php echo $pdckl_lang['settings_paypal_mode']; ?></label></th>
          <td>
            <select name="paypal_mode">
              <option value="LIVE" <?php echo ($paypal_mode == 'LIVE' ? 'selected="selected"' : ''); ?>>Live - ostré</option>
              <option value="SANDBOX" <?php echo ($paypal_mode == 'SANDBOX' ? 'selected="selected"' : ''); ?>>Sandbox - testovací</option>
            </select>
          </td>
        </tr>
      </tbody>
    </table>

    <input type="hidden" name="pdckl_hidden" value="settings">
    <?php echo wp_nonce_field( 'save-settings' ); ?>
    <div align="center">
      <input type="submit" name="Submit" value="<?php echo $pdckl_lang['btn_save']; ?>" class="button button-primary button-hero" />
    </div>
</form>
</div>
