<?php

class FFWDViewOptions_ffwd {
  private $model;

  public function __construct( $model ) {
    $this->model = $model;
  }

  public function display( $reset = FALSE ) {
    global $WD_BWG_UPLOAD_DIR;
    $row = $this->model->get_row_data($reset);
    if ( WD_FB_IS_FREE ) {
      WDW_FFWD_Library::topbar();
    }
    else {
      ?>
      <div class="ffwd_upgrade wd-clear">
        <div class="ffwd-left">
          <div style="font-size: 14px; ">
            <?php _e('This section allows you to change settings for different views and general options.', 'ffwd'); ?>
            <a style="color: #5CAEBD; text-decoration: none;border-bottom: 1px dotted;" target="_blank"
               href="https://help.10web.io/hc/en-us/articles/360017960312-Other-Settings?utm_source=facebook_feed&utm_medium=free_plugin"><?php _e("Read More in User Manual.", "ffwd"); ?></a>
          </div>
        </div>
      </div>
      <?php
    }
    ?>
    <script>wd_fb_log_in = false;</script>
    <form method="post" class="wrap" action="admin.php?page=options_ffwd" style="width:99%;">
      <?php wp_nonce_field('options_ffwd', 'ffwd_nonce'); ?>
      <h2></h2>
      <div class="ffwd_plugin_header">
        <span class="option-icon"></span>
        <h2 class="ffwd_page_name">Edit options</h2>
      </div>
      <div style="display: inline-block; width: 100%;">
        <div style="float: right;padding-top: 10px;">
          <input class="ffwd-button-primary  ffwd-button-reset" type="submit" onclick="if (confirm('Do you want to reset to default?')) {
                                                                 spider_set_input_value('task', 'reset');
                                                               } else {
                                                                 return false;
                                                               }" value="Reset all options"/>
          <input class="ffwd-button-primary ffwd-button-save" type="submit"
                 onclick="check_app('<?php echo WD_FB_PREFIX; ?>','save'); spider_set_input_value('task', 'save')"
                 value="Save"/>

        </div>
      </div>
      <div style=" width: 100%;" id="display_panel">
        <?php
        $pages = get_option('ffwd_pages_list');
        ?>
        <div class="ffwd-access-token-missing">
          <a id="ffwd_login_button" class="ffwd_login_button" href="#">
            <?php
            echo (empty($pages)) ? "Log in and get my Access Token" : "Reauthenticate"
            ?>
          </a>
          <?php
          if ( empty($pages) ) {
            ?>
            <p>
              <?php _e("To use Facebook API, you need an Access Token.", "ffwd"); ?>
            </p>
            <p>
              <?php _e("Click on the Log in and get my Access Token button, to generate your token.", "ffwd"); ?>
            </p>
            <?php
          }
          else { ?>
            <p>
              <?php _e("If there is an issue with the Access Token, please click on Reauthenticate button.", "ffwd"); ?>
            </p>
            <?php
          }
          ?>
        </div>
        <div id="ffwd_login_popup" style="display: none;">
          <div class="ffwd_login_popup_content">
            <p>Log into your Facebook account using the button below and approve the plugin to connect your account.</p>
            <p>
              <span id="ffwd_login_popup_cancle_btn">Cancel</span>
              <a id="ffwd_login_popup_continue_btn" href="<?php echo WDFacebookFeed::get_auth_url(); ?>">Continue</a>
            </p>
            <p id="ffwd_login_popup_notice"><b>Please note:</b> this does not give us permission to manage your Facebook
              pages, it simply allows the plugin to see a list of the pages you manage and retrieve an Access Token.</p>
          </div>
        </div>
        <!--User options-->
        <div class="spider_div_options" id="div_content_1" style="">
          <table style="width: 100%;">
            <tbody>
              <tr>
              <td class="spider_label_options">
                <label><?php _e('Date format for posts:', 'ffwd'); ?></label>
              </td>
              <td>
                <select name="<?php echo WD_FB_PREFIX; ?>_post_date_format">
                  <?php
                  foreach ( $this->model->date_formats as $key => $date_format ) {
                    ?>
                    <option
                      value="<?php echo $key; ?>" <?php if ( $row->post_date_format == $key ) {
                      echo 'selected="selected"';
                    } ?>><?php echo $date_format; ?></option>
                    <?php
                  }
                  ?>
                </select>
                <div class="spider_description"><?php _e('Choose a date type.', 'ffwd'); ?></div>
              </td>
            </tr>
            <tr style="display: none">
              <td class="spider_label_options">
                <label><?php _e('Date format for events:', 'ffwd'); ?></label>
              </td>
              <td>
                <select name="<?php echo WD_FB_PREFIX; ?>_event_date_format">
                  <?php
                  foreach ( array_slice($this->model->date_formats, 1) as $key => $date_format ) {
                    ?>
                    <option
                      value="<?php echo $key; ?>" <?php if ( $row->event_date_format == $key ) {
                      echo 'selected="selected"';
                    } ?>><?php echo $date_format; ?></option>
                    <?php
                  }
                  ?>
                </select>
                <div class="spider_description"><?php _e('Choose a date type.', 'ffwd'); ?></div>
              </td>
            </tr>
            <tr>
              <td class="spider_label_options">
                <label><?php _e('Reset cache:', 'ffwd'); ?></label>
              </td>
              <td>
                <a href="#" class="ffwd_reset_cache button"><?php _e('Reset cache', 'ffwd'); ?></a>
                <span class="ffwd_reset_cache_res"></span>
                <div class="spider_description"><?php _e('Click to get new data from Facebook', 'ffwd'); ?></div>
              </td>
            </tr>
            <tr>
            <tr>
              <td class="spider_label_options">
                <label><?php _e('Uninstall:', 'ffwd'); ?></label>
              </td>
              <td>
                <a href="admin.php?page=uninstall_ffwd" class="button"><?php _e('Uninstall', 'ffwd'); ?></a>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div id="opacity_div" style="display: none; background-color: rgba(0, 0, 0, 0.2); position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99998;"></div>
      <div id="loading_div" style="display:none; text-align: center; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99999;">
        <img src="<?php echo WD_FFWD_URL . '/images/ajax_loader.png'; ?>" class="spider_ajax_loading" style="margin-top: 200px; width:50px;">
      </div>
      <input id="task" name="task" type="hidden" value=""/>
      <input id="current_id" name="current_id" type="hidden" value="<?php /* echo $row->id; */ ?>"/>
      <script>
        // "State" global get var is for checking redirect from facebook
        function ffwd_show_hide_options( id, display ) {
          jQuery("#" + id).css("display", display);
        }
        function ffwd_validate_app() {
          if ( jQuery('#ffwd_app_id').val() == '' || jQuery('#ffwd_app_secret').val() == '' ) {
            return false;
          }
          else {
            return true;
          }
        }
      </script>
    </form>
    <?php
  }
}