<div class="inside">
  <table class="form-table">
    <tr>
      <td>
        <label for="my_own_styling">
          <input type="checkbox" name="my_own_styling" id="my_own_styling" value="1" <?php checked($this->func__get_plugin_option('my_own_styling'), 1) ?> />
          <?php _e('I\'ll put in my own styling', 'antispam_bee') ?> <span class="description">(Make sure that <code>form .message-textarea</code> is hidden in your CSS!)</span>
        </label>
      </td>
    </tr>
    <tr>
      <td>
        <label for="antispam_bee_ignore_pings">
          <input type="checkbox" name="antispam_bee_ignore_pings" id="antispam_bee_ignore_pings" value="1" <?php checked($this->func__get_plugin_option('ignore_pings'), 1) ?> />
          <?php _e('Do not check trackbacks / pingbacks', 'antispam_bee') ?>
        </label>
      </td>
    </tr>
    <tr>
      <td>
        Enter alternative email address for pingback and trackback notifications<br />
        <label for="pingback_notify_email">
          <input type="text" class="regular-text" name="pingback_notify_email" id="pingback_notify_email" value="<?php if( function_exists( 'esc_attr' ) ) echo esc_attr( $this->func__get_plugin_option('pingback_notify_email') ); else echo ( $this->func__get_plugin_option('pingback_notify_email') ); ?>" />
          <span class="description"><?php _e('Leave empty if you want to use the default address from General Settings', 'antispam_bee') ?> <?php $this->admin__show_help_link('disable_pingback_notify') ?></span>
        </label>
      </td>
    </tr>
    <tr>
      <td>
        <label for="disable_pingback_notify">
          <input type="checkbox" name="disable_pingback_notify" id="disable_pingback_notify" value="1" <?php checked($this->func__get_plugin_option('disable_pingback_notify'), 1) ?> />
          <?php _e('Disable notifications for pingbacks and trackbacks', 'antispam_bee') ?>
        </label>
      </td>
    </tr>
    <tr>
      <td>
        <label for="comment_status_links">
          <input type="checkbox" name="comment_status_links" id="comment_status_links" value="1" <?php checked($this->func__get_plugin_option('comment_status_links'), 1) ?> />
          <?php _e('Enhance Wordpress Admin Comments section', 'antispam_bee') ?> <span class="description">Hides trackbacks and shows separate counts for comments and trackbacks</span>
        </label>
      </td>
    </tr>
    <tr>
      <td>
        <label for="cronjob_writeout">
          <input type="checkbox" name="cronjob_writeout" id="cronjob_writeout" value="1" <?php checked($this->func__get_plugin_option('cronjob_writeout'), 1) ?> />
          Write out removed comments into files (<abbr title="For debug and troubleshooting">?</abbr>)
        </label>
      </td>
    </tr>
    <tr>
      <td>
        <label for="debug_enabled">
          <input type="checkbox" name="debug_enabled" id="debug_enabled" value="1" <?php checked($this->func__get_plugin_option('debug_enabled'), 1) ?> />
          Enable debug (<abbr title="Write celantalk reponse into file">?</abbr>)
        </label>
      </td>
    </tr>
  </table>
</div>