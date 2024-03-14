<?php if ( $active_tab == 'marketing' ): ?>
  <?php if (
            // Both api keys are set and the contact list id is set
            ( $is_mc_api_key_valid and $is_api_key_valid and $contact_list_id_is_valid ) or
            // There was an error sending the subscription email for contact upload
            ( 'error' == $status and isset( $error_type ) and 'upload' == $error_type )
           ) :
  ?>
    <form class="form-table" name="sendgrid_form" method="POST" action="<?php echo esc_attr( Sendgrid_Tools::get_form_action() ); ?>">
      <table class="form-table">
        <tbody>
          <tr valign="top">
            <td colspan="2">
              <h2><?php esc_html_e('SendGrid Test - Subscription', 'connect-sendgrid-for-emails') ?></h2>
            </td>
          </tr>
          <tr valign="top" class="mc_test_email">
            <th scope="row"><?php esc_html_e("Email:", 'connect-sendgrid-for-emails'); ?></th>
            <td>
              <input type="text" id="mc_test_email" name="sendgrid_test_email" value="" size="50">
              <p class="description"><?php esc_html_e('An email will be send to this address to confirm the subscription as it does for users that subscribe using the widget.', 'connect-sendgrid-for-emails') ?></p>
            </td>
          </tr>
          <input type="hidden" name="contact_upload_test" value="true"/>
          <input type="hidden" name="sgnonce" value="<?php echo esc_attr( wp_create_nonce('sgnonce') ); ?>"/>
          <tr valign="top" class="mc_test_email">
            <th scope="row" colspan="2">
              <input class="button button-primary" type="submit" name="Submit" value="<?php esc_html_e('Test', 'connect-sendgrid-for-emails') ?>" />
            </th>
          </tr>
        </tbody>
      </table>
    </form>
  <?php endif; ?>
<?php endif; ?>