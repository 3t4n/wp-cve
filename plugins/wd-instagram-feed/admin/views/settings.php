<?php

class Settings_view_wdi {

  public function display( $args = array() ) {
    $options = $args['options'];
    $authenticated_users_list = $args['authenticated_users_list'];
    $min_capability = $args['min_capability'];
    require_once(WDI_DIR . '/framework/WDI_admin_view.php');
    if ( !empty( WDILibrary::get('wdi_access_token', '', 'sanitize_text_field', 'REQUEST') ) ) {
      /*dismiss api update notice*/
      $admin_notices_option = get_option('wdi_admin_notice', array());
      $admin_notices_option['api_update_token_reset'] = array(
        'start' => current_time("n/j/Y"),
        'int' => 0,
        'dismissed' => 1,
      );
      update_option('wdi_admin_notice', $admin_notices_option);
      ?>
      <script>
        wdi_controller.instagram = new WDIInstagram();
        if (wdi_controller.getCookie('wdi_autofill') != 'false') {
          wdi_controller.apiRedirected();
          document.cookie = "wdi_autofill=false"; // @ToDo What is this part for?
          jQuery(document).ready(function () {
            jQuery(document).on('wdi_settings_filled', function () {
              jQuery('#submit').trigger('click');
            });
          });
        }
      </script>
      <?php
    }
    ?>
    <div class="wdi-settings-page">
      <?php if(!WDI_IS_FREE){?>
        <div class="update-nag wdi_help_bar_wrap">
          <p class="wdi_help_bar_text">
            <?php _e('This section allows you to set API parameters.', 'wd-instagram-feed'); ?>
            <a style="color: #5CAEBD; text-decoration: none;border-bottom: 1px dotted;" class="wdi_hb_t_link" target="_blank"
               href="https://help.10web.io/hc/en-us/articles/360016277532-Configuring-Instagram-Access-Token?utm_source=instagram_feed&utm_medium=free_plugin"><?php _e('Read More in User Guide', 'wd-instagram-feed'); ?></a>
          </p>
        </div>
      <?php }else{
        WDILibrary::topbar();
      }?>
      <form method="post" action="<?php echo esc_url(add_query_arg(array( 'page' => 'wdi_settings' ), 'admin.php')); ?>" class="wdi_settings_form">
        <h2 class="wdi-page-title"><?php _e('Settings', 'wd-instagram-feed'); ?></h2>
        <div class="wdi-connect-instagram" onclick="wdi_popup_open()"></div>
        <div class="wdi-access-token-missing">
          <p><?php _e('You need Access Token for using Instagram API. Click sign in with Instagram button above to get yours.', 'wd-instagram-feed'); ?></p>
          <p><?php _e('This will not show your Instagram media. After that you may create your feed.', 'wd-instagram-feed'); ?></p>
        </div>
        <?php if ( !empty($authenticated_users_list) ) { ?>
          <h2 class="wdi-page-title"><?php _e('Accounts', 'wd-instagram-feed'); ?></h2>
          <ul class="wdi-accounts-list">
            <?php foreach ( $authenticated_users_list as $user_name => $user ) { ?>
              <li class="wdi-account-list-<?php echo esc_attr($user['user_id']) ?>">
                <div class="wdi-account-block">
                  <div>
                    <div class="wdi-account-user-info">
                      <h4 class="wdi-account-name"><?php echo esc_html($user['user_name']); ?></h4>
                      <p class="wdi-account-type"><?php echo esc_html($user['type']); ?> <?php _e('(New API)', 'wd-instagram-feed'); ?></p>
                    </div>
                    <div class="wdi-account-show-token">
                      <i class="dashicons dashicons-arrow-down-alt2"></i>
                    </div>
                  </div>
                  <div>
                    <span class="button wdi-account-remove" onclick="wdi_account_remove('<?php echo esc_attr($user_name); ?>','<?php echo esc_attr($user['user_id']); ?>')"><?php _e('Remove', 'wd-instagram-feed'); ?></span>
                  </div>
                </div>
                <div class="wdi-account-accesstoken">
                  <div>
                    <p class="wdi-input-group">
                      <label><?php _e('User ID:', 'wd-instagram-feed'); ?></label>
                      <input type="text" value="<?php echo esc_attr($user['user_id']); ?>" readonly="readonly"
                             onclick="this.focus();this.select()"
                             title="<?php _e('To copy, click the field then press Ctrl + C (PC) or Cmd + C (Mac).', 'wd-instagram-feed'); ?>">
                    </p>
                    <p class="wdi-input-group">
                      <label><?php _e('Access Token:', 'wd-instagram-feed'); ?></label>
                      <input class="wdi_user_token" type="text" value="<?php echo esc_attr($user['access_token']); ?>" readonly="readonly"
                             onclick="this.focus();this.select()"
                             title="<?php _e('To copy, click the field then press Ctrl + C (PC) or Cmd + C (Mac).', 'wd-instagram-feed'); ?>">
                    </p>
                    <p class="wdi-input-group">
                      <label><?php _e('Refresh Access Token', 'wd-instagram-feed'); ?></label>
                      <span class="button wdi_account_refresh" data-wdi_account="<?php echo esc_attr($user_name); ?>"><?php _e('Refresh', 'wd-instagram-feed'); ?></span>
                    </p>
                  </div>
                  <span class="button wdi-account-remove"
                        onclick="wdi_account_remove('<?php echo esc_attr($user_name); ?>', '<?php echo esc_attr($user['user_id']); ?>')"><?php _e('Remove', 'wd-instagram-feed'); ?></span>
                  <div class="wdi_clear"></div>
                </div>
              </li>
            <?php } ?>
          </ul>
        <?php } ?>
        <div class="wdi-advanced-options">
          <div class="wdi-advanced-headline">
            <h2 class="wdi-page-title"><?php _e('Advanced Options', 'wd-instagram-feed'); ?></h2>
            <i class="dashicons dashicons-arrow-down-alt2"></i>
            <span class="wdi_clear"></span>
          </div>
          <div class="wdi-advanced-body">
            <p class="wdi-input-group">
              <label><?php _e('Set Maximum Count of Cache Requests', 'wd-instagram-feed'); ?></label>
              <input type="number" name="<?php echo esc_attr(WDI_OPT . '[wdi_cache_request_count]') ?>" value="<?php echo (isset($options['wdi_cache_request_count']) && $options['wdi_cache_request_count'] !== '') ? intval($options['wdi_cache_request_count']) : 10; ?>">
            </p>
            <p class="wdi-input-group">
              <label><?php _e('Check for new posts every (min)', 'wd-instagram-feed'); ?></label>
              <input type="number" min="10" name="<?php echo esc_attr(WDI_OPT . '[wdi_transient_time]') ?>" value="<?php echo intval($options['wdi_transient_time']); ?>">
            </p>
            <?php /* @ToDo It must be separate for each user */ ?>
            <p class="wdi-input-group">
              <label><?php _e('Reset cache with Instagram data', 'wd-instagram-feed'); ?></label>
              <a href="#" id="wdi_reset_cache" class="button"><?php _e('Reset cache', 'wd-instagram-feed'); ?></a>
            </p>
            <p class="wdi-input-group">
              <label><?php _e('Minimal role to add and manage Feeds or Themes', 'wd-instagram-feed'); ?></label>
              <select name="<?php echo esc_attr(WDI_OPT . '[wdi_feeds_min_capability]') ?>">
                <?php
                foreach ( $min_capability as $capability_key => $capability_name ) {
                  $selected = ( $options['wdi_feeds_min_capability'] == $capability_key ) ? 'selected' : '';
                  ?>
                  <option value="<?php echo esc_attr($capability_key); ?>" <?php echo esc_attr($selected); ?>><?php echo esc_html($capability_name); ?></option>
                <?php } ?>
              </select>
            <p class="wdi-input-group">
              <label><?php _e('Custom CSS', 'wd-instagram-feed'); ?></label>
              <textarea name="<?php echo esc_attr(WDI_OPT . '[wdi_custom_css]') ?>"><?php echo esc_html($options['wdi_custom_css']); ?></textarea>
            </p>
            <p class="wdi-input-group">
              <label><?php _e('Custom JavaScript', 'wd-instagram-feed'); ?></label>
              <textarea name="<?php echo esc_attr(WDI_OPT . '[wdi_custom_js]') ?>"><?php echo esc_js($options['wdi_custom_js']); ?></textarea>
            </p>
            <div class="wdi-input-group">
              <label><?php _e('Uninstall', 'wd-instagram-feed'); ?></label>
              <a href="<?php echo esc_url(admin_url('admin.php?page=wdi_uninstall')); ?>"
                 class="button"><?php _e('Uninstall', 'wd-instagram-feed'); ?></a>
              <p class="wdi-description"><?php _e('Note, that uninstalling Instagram Feed will completely remove all feeds and other data on the plugin. Please make sure you don\'t have any important information before you proceed.', 'wd-instagram-feed'); ?></p>
            </div>
          </div>
        </div>
        <input type="hidden" name="<?php echo esc_attr(WDI_OPT . '[wdi_user_id]') ?>" value="<?php echo esc_attr($options['wdi_user_id']); ?>">
        <input type="hidden" name="<?php echo esc_attr(WDI_OPT . '[wdi_user_name]') ?>" value="<?php echo esc_attr($options['wdi_user_name']); ?>">
        <input type="hidden" name="<?php echo esc_attr(WDI_OPT . '[wdi_access_token]') ?>" value="<?php echo esc_attr($options['wdi_access_token']); ?>">
        <input type="hidden" name="task" value="save">
        <?php
        wp_nonce_field('wdi_nonce', 'wdi_nonce');
        submit_button();
        ?>
      </form>
    </div>
    <div id="wdi_save_loading" class="wdi_hidden">
      <img src="<?php echo esc_url(WDI_URL) . '/images/ajax_loader.png'; ?>" class="wdi_spider_ajax_loading" style="width:50px;">
      <span class="caching-process-message">
        <?php
        _e("Please don't close this window. We are caching Instagram media.", "wdi");
        echo "<br>";
        _e("This may take a few minutes.", "wdi");
        ?>
        </span>
    </div>
    <script>
      jQuery(document).ready(function () {
        jQuery('#wdi-personal-business-popup input[name="wdi-connect-type"]').on('click', function () {
          var href = jQuery(this).val();
          jQuery('#wdi-personal-business-popup .wdi-connect').attr('href', href);
        });
        jQuery('#wdi-personal-business-popup .wdi-radio-button .dashicons-info').hover(function () {
          jQuery(this).parents('p').find('.wdi-radio-info-text').addClass('active');
        }, function () {
          jQuery('.wdi-radio-info-text').removeClass('active');
        });
        jQuery(".wdi_settings_form").submit(function () {
          jQuery.ajax({
            type: "POST",
            url: wdi_ajax.ajax_url,
            dataType: "json",
            data: {
              wdi_nonce: wdi_ajax.wdi_nonce,
              action: "wdi_set_reset_cache"
            },
            success: function ( data ) {
            }
          });
        });

        <?php if(!WDI_IS_FREE):?>
        jQuery('input[name ="wdi-connect-type"]').change(function() {
          if(this.checked) {
            jQuery(".wdi_input_group").removeClass("wdi_active");
            jQuery(this).closest(".wdi_input_group").addClass("wdi_active");
          }
        });
        <?php else:?>
        jQuery(".wdi-label-business").click(function () {
          window.open("https://10web.io/plugins/wordpress-instagram-feed/");
        });
        jQuery(".wdi-label-business").css({"cursor":"pointer"});
        <?php endif;?>
      });
    </script>
    <?php
    echo esc_html($this->personal_business_popup());
  }

  private function personal_business_popup() {
    $auth_urls = wdi_get_auth_urls();
    $personal_href = $auth_urls['personal'];
    $business_href = $auth_urls['business'];
    ?>
    <div id="wdi-personal-business-popup" class="wdi-popup">
      <div class="wdi-popup-container">
        <i class="wdi-popup-close" onclick="wdi_popup_close()"></i>
        <h3><?php _e('Are you connecting a Personal or<br class="wdi_desktop"> Business Instagram Profile?', 'wd-instagram-feed'); ?></h3>
        <div class="wdi-radio-button">
          <p class="wdi-label-personal wdi_input_group wdi_active <?php echo (WDI_IS_FREE)?"wdi_free":"wdi_pro"; ?>">
            <label for="wdi-personal">
              <input type="radio" id="wdi-personal" name="wdi-connect-type" value="<?php echo esc_attr($personal_href); ?>"
                     checked="checked">
              <span class="wdi_account_type"><?php _e('Personal', 'wd-instagram-feed'); ?></span>
            </label>
            <span class="wdi_info_text"><?php _e('Used for displaying user feeds from a "Personal"<br class="wdi_desktop"> Instagram account.', 'wd-instagram-feed'); ?></span>
          </p>
          <p class="wdi-label-business wdi_input_group <?php echo (WDI_IS_FREE) ? "wdi_free" : "wdi_pro"; ?>">
            <label for="wdi-business">
              <input type="radio" id="wdi-business" <?php echo (WDI_IS_FREE)?"disabled":""; ?> name="wdi-connect-type" value="<?php echo esc_url($business_href); ?>">
              <span class="wdi_account_type"><?php _e('Business', 'wd-instagram-feed'); ?></span>
            </label>
            <span class="wdi_info_text"><?php _e('Used for displaying user feeds and <span>hashtags</span><br class="wdi_desktop"> from a “Business” Instagram account.', 'wd-instagram-feed'); ?></span>
            <?php if ( WDI_IS_FREE ) { ?>
              <a href="#" class="wdi_paid">This option is available in Premium version</a>
            <?php } ?>
          </p>
        </div>
        <a href="<?php echo esc_url($personal_href); ?>" class="button button-primary wdi-connect"><?php _e('Connect', 'wd-instagram-feed'); ?></a>
      </div>
    </div>
    <?php
  }
}

