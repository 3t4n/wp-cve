<div class="inside">
  <table class="form-table">

    <?php if ($this->util__is_min_wp('2.7')) { ?>
      <!--<tr>
              <td>
                <label for="antispam_bee_dashboard_count">
                <input type="checkbox" name="antispam_bee_dashboard_count" id="antispam_bee_dashboard_count" value="1" <?php checked($this->func__get_plugin_option('dashboard_count'), 1) ?> />
                <?php _e('Display blocked comments count on the dashboard', 'antispam_bee') ?> <?php $this->admin__show_help_link('dashboard_count') ?>
                </label>
              </td>
            </tr>-->
    <?php } ?>

    <!--<tr>
            <td>
              <label for="antispam_bee_already_commented">
              <input type="checkbox" name="antispam_bee_already_commented" id="antispam_bee_already_commented" value="1" <?php checked($this->func__get_plugin_option('already_commented'), 1) ?> />
              <?php _e('Do not check for spam if the author has already commented and approved', 'antispam_bee') ?> <?php $this->admin__show_help_link('already_commented') ?>
              </label>
            </td>
          </tr>-->
    <!--<tr>
            <td>
              <label for="antispam_bee_always_allowed">
              <input type="checkbox" name="antispam_bee_always_allowed" id="antispam_bee_always_allowed" value="1" <?php checked($this->func__get_plugin_option('always_allowed'), 1) ?> />
              <?php _e('Comments are also used outside of posts and pages', 'antispam_bee') ?> <?php $this->admin__show_help_link('always_allowed') ?>
              </label>
            </td>
          </tr>-->
    <tr>
      <td>
        <label for="trash_banned">
          <input type="checkbox" name="trash_banned" id="trash_banned" value="1" <?php checked($this->func__get_plugin_option('trash_banned'), 1) ?> />
          <?php _e('Trash banned (blacklisted) comments, don\'t just mark them as spam', 'antispam_bee') ?>
        </label>
      </td>
    </tr>
    <tr>
      <td>
        <label for="spam_registrations">
          <input <?php if( $this->func__check_s2member() ) : ?>onclick="return false"<?php endif; ?> type="checkbox" name="spam_registrations" id="spam_registrations" value="1" <?php checked($this->func__get_plugin_option('spam_registrations'), 1) ?> />
          <?php _e('Protect the registration form', 'antispam_bee') ?> <?php if( $this->func__check_s2member() ) : ?>(<abbr title="Not available for s2Member!">?</abbr>)<?php endif; ?>
        </label>
      </td>
    </tr>
    <tr>
      <td>
        <label for="protect_bbpress">
          <input type="checkbox" name="protect_bbpress" id="protect_bbpress" value="1" <?php checked($this->func__get_plugin_option('protect_bbpress'), 1) ?> />
          <?php _e('Protect bbPress', 'antispam_bee') ?>
        </label>
      </td>
    </tr>
        <tr>
      <td>
        <label for="protect_gravity_forms">
          <input type="checkbox" name="protect_gravity_forms" id="protect_gravity_forms" value="1" <?php checked($this->func__get_plugin_option('protect_gravity_forms'), 1) ?> />
          <?php _e('Protect Gravity Forms', 'antispam_bee') ?>
        </label>
      </td>
    </tr>
    <tr>
      <td>
        <label for="cronjob_enable">
          <input type="checkbox" name="cronjob_enable" id="cronjob_enable" value="1" <?php checked($this->func__get_plugin_option('cronjob_enable'), 1) ?> />
          Keep maximum of 20,000 trash spam comments (<abbr title="This will keep comments trashed by hand - having _wp_trash_meta_time meta">?</abbr>)
        </label>
      </td>
    </tr>
    <tr>
      <td>
        <label for="cleantalk_api_key"><?php _e('Cleantalk API key', 'antispam_bee') ?>: </label>
        <input type="text" name="cleantalk_api_key" id="cleantalk_api_key" value="<?php echo $this->func__get_plugin_option('cleantalk_api_key') ?>" />
        <?php
        if( !empty( $this->func__get_plugin_option('cleantalk_api_key') ) ) {
          if( $this->func__get_plugin_option('cleantalk_api_status') )
            echo "<span class='cleantalk-api-valid'>Valid</span>";
          else
            echo "<span class='cleantalk-api-error'><strong>Error:</strong> ".$this->func__get_plugin_option('cleantalk_error')."</span>";
        }
        ?>
      </td>
    </tr>

  </table>
</div>