<?php if ( $active_tab == 'marketing' ): ?>
  <form class="form-table" name="sendgrid_form" id="sendgrid_form_mc" method="POST" action="<?php echo esc_attr(Sendgrid_Tools::get_form_action()); ?>">
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <td colspan="2">
            <h3 class="sendgrid-settings-top-header"><?php echo esc_html_e('SendGrid Credentials', 'connect-sendgrid-for-emails') ?></h3>
          </td>
        </tr>
        <tr valign="top" class="mc_apikey">
          <th scope="row"><?php esc_html_e("API Key:", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <input type="password" id="mc_apikey" name="sendgrid_mc_apikey" value="<?php echo esc_attr( $is_env_mc_api_key ? "************" : $mc_api_key );  ?>" size="50" <?php disabled( $is_env_mc_api_key ); ?>>
            <p class="description"><?php esc_html_e('An API Key to use for uploading contacts to SendGrid. This API Key needs to have full Marketing Campaigns permissions.', 'connect-sendgrid-for-emails') ?></p>
          </td>
        </tr>
        <tr valign="top" class="use_transactional">
          <th scope="row"><?php esc_html_e("Use same authentication as transactional:", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <input type="checkbox" id="use_transactional" name="sendgrid_mc_use_transactional" value="true" <?php echo $checked_use_transactional === 'checked' ? 'checked' : ''; disabled( $is_env_mc_opt_use_transactional ); ?>>
            <p class="description"><?php esc_html_e('If checked, the contacts will be uploaded using the same credentials that are used for sending emails.', 'connect-sendgrid-for-emails') ?></p>
          </td>
        </tr>

        <tr valign="top">
          <td colspan="2">
            <h3><?php echo esc_html_e('Subscription Options', 'connect-sendgrid-for-emails') ?></h3>
          </td>
        </tr>
        <tr valign="top" class="select_contact_list">
          <th scope="row"><?php esc_html_e("Contact list to upload to:", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <select id="select_contact_list" class="sengrid-settings-select" name="sendgrid_mc_contact_list" <?php disabled( $is_env_mc_list_id ); ?>>
            <?php
              if ( false != $contact_lists && $is_mc_api_key_valid ) {
                foreach ( $contact_lists as $key => $list ) {
                  if ( $mc_list_id == $list['id'] ) {
                    echo '<option value="' . esc_attr($list['id']) . '" selected="selected">' . esc_html($list['name']) . '</option>';
                  } else {
                    echo '<option value="' . esc_attr($list['id']) . '">' . esc_html($list['name']) . '</option>';
                  }
                }
              }
            ?>
            </select>
            <p class="description"><?php esc_html_e('The contact details of a subscriber will be uploaded to the selected list.', 'connect-sendgrid-for-emails') ?></p>
          </td>
        </tr>

        <tr valign="top" class="include_fname_lname">
          <th scope="row"> <?php esc_html_e("Include First and Last Name fields:", 'connect-sendgrid-for-emails'); ?> </th>
          <td>
            <input type="checkbox" id="include_fname_lname" name="sendgrid_mc_incl_fname_lname" value="true" <?php echo $checked_incl_fname_lname === 'checked' ? 'checked' : ''; disabled( $is_env_mc_opt_incl_fname_lname ); ?>>
            <p class="description"><?php esc_html_e('If checked, the first and last name fields will be displayed in the widget.', 'connect-sendgrid-for-emails') ?></p>
          </td>
        </tr>

        <tr valign="top" class="req_fname_lname">
          <th scope="row"> <?php esc_html_e("First and Last Name are required:", 'connect-sendgrid-for-emails'); ?> </th>
          <td>
            <input type="checkbox" id="req_fname_lname" name="sendgrid_mc_req_fname_lname" value="true" <?php echo $checked_req_fname_lname === 'checked' ? 'checked' : ''; disabled( $is_env_mc_opt_req_fname_lname ); ?>>
            <p class="description"><?php esc_html_e('If checked, empty values for the first and last name fields will be rejected.', 'connect-sendgrid-for-emails') ?></p>
          </td>
        </tr>

        <tr valign="top" class="signup_email_subject">
          <th scope="row"> <?php esc_html_e("Signup email subject:", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <input type="text" id="signup_email_subject" name="sendgrid_mc_email_subject" size="50" value="<?php echo esc_attr($mc_signup_email_subject); ?>" <?php disabled( $is_env_mc_signup_email_subject ); ?>>
            <p class="description"><?php esc_html_e('The subject for the confirmation email.', 'connect-sendgrid-for-emails') ?></p>
          </td>
        </tr>

        <tr valign="top" class="signup_email_content">
          <th scope="row"> <?php esc_html_e("Signup email content (HTML):", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <textarea rows="8" cols="48"  id="signup_email_content" name="sendgrid_mc_email_content" class="regular-text"  <?php disabled( $is_env_mc_signup_email_content ); ?>><?php echo esc_textarea(html_entity_decode($mc_signup_email_content, ENT_QUOTES)); ?></textarea>
            <p class="description"><?php
				// translators: %s = line break
				printf(esc_html__('Confirmation emails must contain a verification link to confirm the email address being added. %sYou can control the placement of this link by inserting a <a href="%%confirmation_link%%"> </a> tag in your email content. This tag is required.', 'connect-sendgrid-for-emails'), '<br>') ?>
			</p>
          </td>
        </tr>

        <tr valign="top" class="signup_email_content_text">
          <th scope="row"> <?php esc_html_e("Signup email content (Plain Text):", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <textarea rows="8" cols="48" id="signup_email_content_text" name="sendgrid_mc_email_content_text" class="regular-text"  <?php disabled( $is_env_mc_signup_email_content_text ); ?>><?php echo esc_textarea(html_entity_decode($mc_signup_email_content_text, ENT_QUOTES)); ?></textarea>
            <p class="description"><?php
				// translators: %s = line break
				printf(esc_html__('Confirmation emails must contain a verification link to confirm the email address being added. %sYou can control the placement of this link by inserting a %%confirmation_link%% tag in your email content. This tag is required.', 'connect-sendgrid-for-emails'), '<br>') ?></p>
          </td>
        </tr>

        <tr valign="top" class="signup_select_page">
          <th scope="row"> <?php esc_html_e("Signup confirmation page:", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <select id="signup_select_page" class="sengrid-settings-select" name="sendgrid_mc_signup_page" <?php disabled( $is_env_mc_signup_confirmation_page ); ?>>
            <?php
              if ( 'default' == $mc_signup_confirmation_page ) {
                echo '<option value="default" selected>Default Confirmation Page</option>';
              } else {
                echo '<option value="default">Default Confirmation Page</option>';
              }

              if ( false != $confirmation_pages ) {
                foreach ($confirmation_pages as $key => $sg_page) {
                  if ( $mc_signup_confirmation_page == $sg_page->ID ) {
                    echo '<option value="' . (int) $sg_page->ID . '" selected="selected">' . esc_html($sg_page->post_title) . '</option>';
                  } else {
                    echo '<option value="' . (int) $sg_page->ID . '">' . esc_html($sg_page->post_title) . '</option>';
                  }
                }
              }
            ?>
            </select>
            <p class="description"><?php esc_html_e('If the user clicks the confirmation link received in the email, he will be redirected to this page after the contact details are uploaded successfully to SendGrid.', 'connect-sendgrid-for-emails') ?></p>
          </td>
        </tr>

        <tr valign="top">
          <td colspan="2">
            <h3><?php echo esc_html_e('Form Customization', 'connect-sendgrid-for-emails') ?></h3>
          </td>
        </tr>
        <tr valign="top" class="signup_email_label">
          <th scope="row"> <?php esc_html_e("Email Label:", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <input type="text" id="signup_email_label" name="sendgrid_mc_email_label" size="50" value="<?php echo esc_attr(html_entity_decode($mc_signup_email_label, ENT_QUOTES)); ?>" <?php disabled( $is_env_mc_email_label ); ?>>
            <p class="description"><?php esc_html_e('The label for \'Email\' field on the subscription form.', 'connect-sendgrid-for-emails') ?></p>
          </td>
        </tr>
        <tr valign="top" class="signup_first_name_label">
          <th scope="row"> <?php esc_html_e("First Name Label:", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <input type="text" id="signup_first_name_label" name="sendgrid_mc_first_name_label" size="50" value="<?php echo esc_attr(html_entity_decode($mc_signup_first_name_label, ENT_QUOTES)); ?>" <?php disabled( $is_env_mc_first_name_label ); ?>>
            <p class="description"><?php esc_html_e('The label for \'First Name\' field on the subscription form.', 'connect-sendgrid-for-emails') ?></p>
          </td>
        </tr>
        <tr valign="top" class="signup_last_name_label">
          <th scope="row"> <?php esc_html_e("Last Name Label:", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <input type="text" id="signup_last_name_label" name="sendgrid_mc_last_name_label" size="50" value="<?php echo esc_attr(html_entity_decode($mc_signup_last_name_label, ENT_QUOTES)); ?>" <?php disabled( $is_env_mc_last_name_label ); ?>>
            <p class="description"><?php esc_html_e('The label for \'Last Name\' field on the subscription form.', 'connect-sendgrid-for-emails') ?></p>
          </td>
        </tr>
        <tr valign="top" class="signup_subscribe_label">
          <th scope="row"> <?php esc_html_e("Subscribe Label:", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <input type="text" id="signup_subscribe_label" name="sendgrid_mc_subscribe_label" size="50" value="<?php echo esc_attr(html_entity_decode($mc_signup_subscribe_label, ENT_QUOTES)); ?>" <?php disabled( $is_env_mc_subscribe_label ); ?>>
            <p class="description"><?php esc_html_e('The label for \'Subscribe\' button on the subscription form.', 'connect-sendgrid-for-emails') ?></p>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"> <?php esc_html_e("Input Padding (in px):", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <label><?php esc_html_e("Top:", 'connect-sendgrid-for-emails'); ?></label>
            <input type="text" name="sendgrid_mc_input_padding_top" size="4" value="<?php echo esc_attr($mc_signup_input_padding_top); ?>" />

            <label class="sendgrid_settings_mc_input_padding_label"><?php esc_html_e("Right:", 'connect-sendgrid-for-emails'); ?></label>
            <input type="text" name="sendgrid_mc_input_padding_right" size="4" value="<?php echo esc_attr($mc_signup_input_padding_right); ?>" />

            <label class="sendgrid_settings_mc_input_padding_label"><?php esc_html_e("Bottom:", 'connect-sendgrid-for-emails'); ?></label>
            <input type="text" name="sendgrid_mc_input_padding_bottom" size="4" value="<?php echo esc_attr($mc_signup_input_padding_bottom); ?>" />

            <label class="sendgrid_settings_mc_input_padding_label"><?php esc_html_e("Left:", 'connect-sendgrid-for-emails'); ?></label>
            <input type="text" name="sendgrid_mc_input_padding_left" size="4" value="<?php echo esc_attr($mc_signup_input_padding_left); ?>" />
            <p class="description"><?php esc_html_e('The padding values for the input fields on the subscription form.', 'connect-sendgrid-for-emails') ?></p>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"> <?php esc_html_e("Button Padding (in px):", 'connect-sendgrid-for-emails'); ?></th>
          <td>
            <label><?php esc_html_e("Top:", 'connect-sendgrid-for-emails'); ?></label>
            <input type="text" name="sendgrid_mc_button_padding_top" size="4" value="<?php echo esc_attr($mc_signup_button_padding_top); ?>" />

            <label class="sendgrid_settings_mc_input_padding_label"><?php esc_html_e("Right:", 'connect-sendgrid-for-emails'); ?></label>
            <input type="text" name="sendgrid_mc_button_padding_right" size="4" value="<?php echo esc_attr($mc_signup_button_padding_right); ?>" />

            <label class="sendgrid_settings_mc_input_padding_label"><?php esc_html_e("Bottom:", 'connect-sendgrid-for-emails'); ?></label>
            <input type="text" name="sendgrid_mc_button_padding_bottom" size="4" value="<?php echo esc_attr($mc_signup_button_padding_bottom); ?>" />

            <label class="sendgrid_settings_mc_input_padding_label"><?php esc_html_e("Left:", 'connect-sendgrid-for-emails'); ?></label>
            <input type="text" name="sendgrid_mc_button_padding_left" size="4" value="<?php echo esc_attr($mc_signup_button_padding_left); ?>" />
            <p class="description"><?php esc_html_e('The padding values for the button on the subscription form.', 'connect-sendgrid-for-emails') ?></p>
          </td>
        </tr>

        <?php if ( $is_env_mc_api_key or $is_env_mc_opt_use_transactional or $is_env_mc_opt_incl_fname_lname or
                   $is_env_mc_opt_req_fname_lname or $is_env_mc_signup_email_subject or $is_env_mc_signup_email_content or  
                   $is_env_mc_signup_confirmation_page or $is_env_mc_email_label or $is_env_mc_first_name_label or
                   $is_env_mc_last_name_label or $is_env_mc_subscribe_label) : ?>
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
    <p class="submit">
      <input class="button button-primary" type="submit" name="Submit" value="<?php esc_html_e('Update Settings', 'connect-sendgrid-for-emails') ?>" />
    </p>
    <input type="hidden" name="mc_settings" value="true"/>
    <input type="hidden" name="sgnonce" value="<?php echo esc_attr( wp_create_nonce('sgnonce') ); ?>"/>
    <?php
      if ( $is_env_mc_api_key ) {
        echo '<input type="hidden" name="mc_api_key_defined_in_env" id="mc_api_key_defined_in_env" value="true"/>';
      }

      if ( $is_env_mc_list_id ) {
        echo '<input type="hidden" name="mc_list_id_defined_in_env" id="mc_list_id_defined_in_env" value="true"/>';
      }

      if ( $is_env_mc_signup_confirmation_page ) {
        echo '<input type="hidden" name="mc_signup_page_defined_in_env" id="mc_signup_page_defined_in_env" value="true"/>';
      }

      if ( $is_mc_api_key_valid ) {
        echo '<input type="hidden" name="mc_api_key_is_valid" id="mc_api_key_is_valid" value="true"/>';
      }
    ?>
  </form>
  <br />
<?php endif; ?>