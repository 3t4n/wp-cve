<?php if ( $active_tab == 'general' ): ?>
  <form class="form-table" name="sendgrid_form" id="sendgrid_general_settings_form" method="POST" action="<?php echo esc_attr( Sendgrid_Tools::get_form_action() ); ?>">
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <td colspan="2">
            <h3 class="sendgrid-settings-top-header"><?php echo esc_html_e('SendGrid Credentials', 'connect-sendgrid-for-emails') ?></h3>
          </td>
        </tr>
        <tr valign="top" class="apikey">
          <th scope="row"><?php esc_html_e("API Key:", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <input type="password" id="sendgrid_general_apikey" name="sendgrid_apikey" class="sendgrid-settings-key" value="<?php echo esc_attr( $is_env_api_key ? "************" : $api_key );  ?>" <?php disabled( $is_env_api_key ); ?>>
          </td>
        </tr>
        <tr valign="top" class="send_method">
          <th scope="row"><?php esc_html_e("Send Mail with:", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <select name="send_method" class="sendgrid-settings-select" id="send_method" <?php disabled( defined('SENDGRID_SEND_METHOD') ); ?>>
              <?php foreach ( $allowed_send_methods as $method ): ?>
                <option value="<?php echo esc_attr( strtolower( $method ) ); ?>" <?php echo ( strtolower( $method ) == $send_method ) ? 'selected' : '' ?>><?php echo( esc_html( $method ) ) ?></option>
              <?php endforeach; ?>
            </select>
            <?php if ( ! in_array( "SMTP", $allowed_send_methods ) ): ?>
              <p>
                <?php esc_html_e('Swift is required in order to be able to send via SMTP.', 'connect-sendgrid-for-emails'); ?>
              </p>
            <?php endif; ?>
          </td>
        </tr>
        <tr valign="top" class="port" style="display: none;">
          <th scope="row"><?php esc_html_e("Port:", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <select name="sendgrid_port" id="sendgrid_port" <?php disabled( $is_env_port ); ?>>
              <option value="<?php echo esc_attr(SendGrid_SMTP::TLS) ?>" id="tls" <?php echo ( ( SendGrid_SMTP::TLS == $port ) or (! $port ) ) ? 'selected' : '' ?>><?php echo( esc_html( SendGrid_SMTP::TLS ) ) ?></option>
              <option value="<?php echo esc_attr(SendGrid_SMTP::TLS_ALTERNATIVE) ?>" id="tls_alt" <?php echo ( SendGrid_SMTP::TLS_ALTERNATIVE == $port ) ? 'selected' : '' ?>><?php echo( esc_html( SendGrid_SMTP::TLS_ALTERNATIVE ) ) ?></option>
              <option value="<?php echo esc_attr(SendGrid_SMTP::TLS_ALTERNATIVE_2) ?>" id="tls_alt_2" <?php echo ( SendGrid_SMTP::TLS_ALTERNATIVE_2 == $port ) ? 'selected' : '' ?>><?php echo( esc_html( SendGrid_SMTP::TLS_ALTERNATIVE_2 ) ) ?></option>
              <option value="<?php echo esc_attr(SendGrid_SMTP::SSL) ?>" id="ssl" <?php echo ( SendGrid_SMTP::SSL == $port ) ? 'selected' : '' ?>><?php echo( esc_html( SendGrid_SMTP::SSL ) ) ?></option>
            </select>
          </td>
        </tr>
        <?php if ( $is_env_send_method or $is_env_api_key or $is_env_port ) : ?>
          <tr valign="top">
            <td colspan="2">
              <p>
                <?php esc_html_e('Disabled fields are already configured in the config file.', 'connect-sendgrid-for-emails'); ?>
              </p>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
    <br />
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <td colspan="2">
            <h3><?php echo esc_html_e('Mail settings', 'connect-sendgrid-for-emails') ?></h3>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php esc_html_e("Name:", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <input type="text" name="sendgrid_name" value="<?php echo esc_attr($name); ?>" size="20" class="regular-text" <?php disabled( defined('SENDGRID_FROM_NAME') ); ?>>
            <p class="description"><?php esc_html_e('Name as it will appear in recipient clients.', 'connect-sendgrid-for-emails') ?></p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php esc_html_e("Sending Address:", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <input type="text" name="sendgrid_email" value="<?php echo esc_attr($email); ?>" size="20" class="regular-text" <?php disabled( defined('SENDGRID_FROM_EMAIL') ); ?>>
            <p class="description"><?php esc_html_e('Email address from which the message will be sent.', 'connect-sendgrid-for-emails') ?></p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php esc_html_e("Reply Address:", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <input type="text" name="sendgrid_reply_to" value="<?php echo esc_attr($reply_to); ?>" size="20" class="regular-text" <?php disabled( defined('SENDGRID_REPLY_TO') ); ?>>
            <span><small><em><?php esc_html_e('Leave blank to use Sending Address.', 'connect-sendgrid-for-emails') ?></em></small></span>
            <p class="description"><?php esc_html_e('Email address where replies will be returned.', 'connect-sendgrid-for-emails') ?></p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php esc_html_e("Categories:", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <input type="text" name="sendgrid_categories" value="<?php echo esc_attr($categories); ?>" size="20" class="regular-text" <?php disabled( defined('SENDGRID_CATEGORIES') ); ?>>
            <span><small><em><?php esc_html_e('Leave blank to send without categories.', 'connect-sendgrid-for-emails') ?></em></small></span>
            <p class="description"><?php
				// translators: %s = line break
				printf(esc_html__('Associates the category of the email this should be logged as. %sCategories must be separated by commas (Example: category1,category2).', 'connect-sendgrid-for-emails'), '<br>')
			?></p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php esc_html_e("Template:", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <input type="text" name="sendgrid_template" value="<?php echo esc_attr($template); ?>" size="20" class="regular-text" <?php disabled( defined('SENDGRID_TEMPLATE') ); ?>>
            <span><small><em><?php esc_html_e('Leave blank to send without template.', 'connect-sendgrid-for-emails') ?></em></small></span>
            <p class="description"><?php
				// translators: %s = line break
				printf(esc_html__('The template ID used to send emails. %sExample: 0b1240a5-188d-4ea7-93c1-19a7a89466b2.', 'connect-sendgrid-for-emails'), '<br>')
			?></p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php esc_html_e("Content-type:", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <select name="content_type" class="sendgrid-settings-select" id="content_type" <?php disabled( $is_env_content_type ); ?> >
              <option value="plaintext" id="plaintext" <?php echo ( 'plaintext' == $content_type ) ? 'selected' : '' ?>>text/plain</option>
              <option value="html" id="html" <?php echo ( 'html' == $content_type ) ? 'selected' : '' ?>>text/html</option>
            </select>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><?php esc_html_e("Unsubscribe Group:", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <select id="select_unsubscribe_group" class="sendgrid-settings-select" name="unsubscribe_group" <?php disabled( $is_env_unsubscribe_group ); ?> <?php disabled( $no_permission_on_unsubscribe_groups ); ?>>
              <option value="0"><?php esc_html_e("Global Unsubscribe", 'connect-sendgrid-for-emails'); ?></option>
              <?php
                if ( false != $unsubscribe_groups ) {
                  foreach ( $unsubscribe_groups as $key => $group ) {
                    if ( $unsubscribe_group_id == $group['id'] ) {
                      echo '<option value="' . esc_attr($group['id']) . '" selected="selected">' . esc_html($group['name']) . '</option>';
                    } else {
                      echo '<option value="' . esc_attr($group['id']) . '">' . esc_html($group['name']) . '</option>';
                    }
                  }
                }
              ?>
            </select>
            <p class="description"><?php
				// translators: %s = <br>
				printf(esc_html__("User will have the option to unsubscribe from the selected group. %sThe API Key needs to have 'Unsubscribe Groups' permissions to be able to select a group.", 'connect-sendgrid-for-emails'), '<br>')
			?></p>
          </td>
        </tr>
      </tbody>
    </table>
  <br />
  <table class="form-table">
    <tbody>
      <tr valign="top">
            <td colspan="2">
              <h3><?php echo esc_html_e('Statistics settings', 'connect-sendgrid-for-emails') ?></h3>
            </td>
        </tr>
      <tr valign="top">
        <th scope="row"><?php esc_html_e("Categories:", 'connect-sendgrid-for-emails'); ?></th>
        <td>
          <input type="text" name="sendgrid_stats_categories" value="<?php echo esc_attr($stats_categories); ?>" size="20" class="regular-text" <?php disabled( defined('SENDGRID_STATS_CATEGORIES') ); ?>>
          <span><small><em><?php esc_html_e('Leave blank for not showing category stats.', 'connect-sendgrid-for-emails') ?></em></small></span>
          <p class="description"><?php
			  // translators: %s = line break
			  printf(esc_html__('Add some categories for which you would like to see your stats. %sCategories must be separated by commas (Example: category1,category2).', 'connect-sendgrid-for-emails'), '<br>')
		  ?></p>
        </td>
      </tr>
      <tr valign="top">
        <td colspan="2">
          <p>
            <?php esc_html_e('Disabled fields in this form means that they are already configured in the config file.', 'connect-sendgrid-for-emails'); ?>
          </p>
        </td>
      </tr>
    </tbody>
  </table>
  <p class="submit">
    <input class="button button-primary" type="submit" name="Submit" value="<?php esc_html_e('Update Settings', 'connect-sendgrid-for-emails') ?>" />
  </p>
  <input type="hidden" name="general_settings" value="true"/>
  <input type="hidden" name="sgnonce" value="<?php echo esc_attr( wp_create_nonce('sgnonce') ); ?>"/>
</form>
<br />
<?php endif; ?>