<?php

/**
 * Email settings view for the plugin
 *
 * @link       http://www.webfactoryltd.com
 * @since      1.0
 */

if (!defined('WPINC')) {
	die;
}


if (!isset($signals_csmm_options['mail_system_to_use'])) {
  $signals_csmm_options['mail_system_to_use'] = 'mc';
}

?>

<div class="signals-tile" id="email">
	<div class="signals-tile-body">
		<div class="signals-tile-title"><?php esc_attr_e( 'EMAIL', 'minimal-coming-soon-maintenance-mode' ); ?></div>
		<p><?php esc_attr_e( 'Optin settings for the plugin. You can configure various services to store collected emails.', 'minimal-coming-soon-maintenance-mode' ); ?></p>

		<div class="signals-section-content">

     <div class="signals-double-group signals-clearfix">
      <div class="signals-form-group">
          <label for="mail_system_to_use" class="signals-strong">Select Emailing / Integration System</label>
          <select id="mail_system_to_use" name="mail_system_to_use" class="signals-form-control pro-option">
            <option value="mc" <?php echo $signals_csmm_options['mail_system_to_use']=='mc'?'selected':''; ?>><?php esc_attr_e( 'MailChimp', 'minimal-coming-soon-maintenance-mode' ); ?></option>
            <option value="-1"><?php esc_attr_e( 'Zapier - PRO option', 'minimal-coming-soon-maintenance-mode' ); ?></option>
          </select>
          <p class="signals-form-help-block">If you use any other autoresponder services apart from Mailchimp such as Aweber or Constant Contact, or if you need Zapier get the <a href="#pro" class="csmm-change-tab">PRO version</a>.</p>
        </div>
      </div>

		<div id="mailchimp-wrapper" <?php echo $signals_csmm_options['mail_system_to_use']=='mo'?'style="display:none;"':''; ?>>

      <div class="signals-double-group signals-clearfix">
      <div class="signals-form-group">
				<label for="signals_csmm_api" class="signals-strong"><?php esc_attr_e( 'MailChimp API', 'minimal-coming-soon-maintenance-mode' ); ?></label>
				<input type="text" name="signals_csmm_api" id="signals_csmm_api" value="<?php esc_attr_e( $signals_csmm_options['mailchimp_api'] ); ?>" placeholder="<?php esc_attr_e( 'MailChimp API key', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control">

				<p class="signals-form-help-block"><?php esc_attr_e( 'Enter your MailChimp API key.', 'minimal-coming-soon-maintenance-mode' ); ?> Open your <a href="https://us2.admin.mailchimp.com/account/api/" target="_blank"><?php esc_attr_e( 'MailChimp profile', 'minimal-coming-soon-maintenance-mode' ); ?></a> <?php esc_attr_e( 'to get the API key. If you don\'t want to enable the subscription option, leave this field blank.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
        <p><button type="submit" name="signals_csmm_submit" class="signals-btn"><?php esc_attr_e( 'Save API key &amp; refresh mailing lists', 'minimal-coming-soon-maintenance-mode' ); ?></button></p>
      </div>
			</div>

      <div class="signals-double-group signals-clearfix">
			<div class="signals-form-group">
				<label for="signals_csmm_list" class="signals-strong"><?php esc_attr_e( 'MailChimp List', 'minimal-coming-soon-maintenance-mode' ); ?></label>

				<?php

					// Checking if the API key is present in the database
					if ( ! empty( $signals_csmm_options['mailchimp_api'] ) ) {
						// Grabbing lists using the MailChimp API
					                      
                        $signals_api 	= new Signals_MailChimp( $signals_csmm_options['mailchimp_api'] );

                        $signals_lists = array();
                        $raw_lists = $signals_api->get('lists', array('count' => 99));
                        if ($signals_api->success()) {
                            foreach ($raw_lists['lists'] as $list) {
                                $signals_lists[] = array('val' => $list['id'], 'label' => $list['name']);
                            } // foreach list
                            //usort($signals_lists, 'csmm_sort_select_options');
                        } else {
                            $signals_lists = false;
                        } // if success

						if ( ! $signals_lists ) {
							echo '<p class="signals-form-help-block">' . __( '<b>Error</b> fetching mailing lists. Please make sure that the API key you entered is correct and try again.', 'minimal-coming-soon-maintenance-mode' ) . '</p>';
						} else if ( count($signals_lists) == 0 ) {
							echo '<p class="signals-form-help-block">' . __( 'It seems that there is no list created for this account. Create one on the MailChimp website and then try again.', 'minimal-coming-soon-maintenance-mode' ) . '</p>';
						} else {
							echo '<select name="signals_csmm_list" id="signals_csmm_list">';
              echo '<option value="">- select a mailing list -</option>';
							foreach ( $signals_lists as $signals_single_list ) {
								echo '<option value="' . esc_attr($signals_single_list['val']) . '"' . selected( $signals_single_list['val'], $signals_csmm_options['mailchimp_list'] ) . '>' . esc_html($signals_single_list['label']) .'</option>';
							}

							echo '</select>';
							echo '<p class="signals-form-help-block">' . __( 'Select the MailChimp list in which you want to store the subscriber data.', 'minimal-coming-soon-maintenance-mode' ) . '</p>';
						}
					} else {
						echo '<p class="signals-form-help-block">' . __( 'Enter your MailChimp API key in the field above and click "Save API key". Your lists will refresh and appear here.', 'minimal-coming-soon-maintenance-mode' ) . '</p>';
					}

				?>
      </div>

      <div class="signals-form-group">
        <label for="signals_csmm_double_optin" class="signals-strong pro-option"><?php esc_attr_e( 'Double Opt-In', 'minimal-coming-soon-maintenance-mode' ); ?><sup>PRO</sup></label>
        <input type="checkbox" disabled="disabled" class="signals-form-ios pro-option skip-save" name="signals_csmm_double_optin" id="signals_csmm_double_optin" value="1" checked="checked">
        <p class="signals-form-help-block"><?php esc_attr_e( 'The double opt-in process includes two steps. First the potential subscriber fills out and submits your signup form. Then, they\'ll receive a confirmation email and click a link to verify their email, which is then added to your MailChimp list. To add subscribers to the list without requiring a confirmation email - disable the option. This is a <a href="#pro" class="csmm-change-tab">PRO feature</a>.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
      </div>

			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_message_noemail" class="signals-strong"><?php esc_attr_e( 'Message: No Email', 'minimal-coming-soon-maintenance-mode' ); ?></label>
					<input type="text" name="signals_csmm_message_noemail" id="signals_csmm_message_noemail" value="<?php echo esc_attr_e( $signals_csmm_options['message_noemail'] ); ?>" placeholder="<?php esc_attr_e( 'Message when email is not provided', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control">

					<p class="signals-form-help-block"><?php esc_attr_e( 'Provide error message to show if the user forgets to provide email address.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_message_subscribed" class="signals-strong"><?php esc_attr_e( 'Message: Already Subscribed', 'minimal-coming-soon-maintenance-mode' ); ?></label>
					<input type="text" name="signals_csmm_message_subscribed" id="signals_csmm_message_subscribed" value="<?php echo esc_attr_e( $signals_csmm_options['message_subscribed'] ); ?>" placeholder="<?php esc_attr_e( 'Message when user is already subscribed', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control">

					<p class="signals-form-help-block"><?php esc_attr_e( 'Provide message to show if the user is already subscribed to the mailing list.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_message_wrong" class="signals-strong"><?php esc_attr_e( 'Message: General Error', 'minimal-coming-soon-maintenance-mode' ); ?></label>
					<input type="text" name="signals_csmm_message_wrong" id="signals_csmm_message_wrong" value="<?php echo esc_attr( $signals_csmm_options['message_wrong'] ); ?>" placeholder="<?php esc_attr_e( 'Message when anything goes wrong while subscribing', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control">

					<p class="signals-form-help-block"><?php esc_attr_e( 'Provide general error message to show if anything goes wrong while subscribing.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_message_done" class="signals-strong"><?php esc_attr_e( 'Message: Successfully Subscribed', 'minimal-coming-soon-maintenance-mode' ); ?></label>
					<input type="text" name="signals_csmm_message_done" id="signals_csmm_message_done" value="<?php echo esc_attr( $signals_csmm_options['message_done'] ); ?>" placeholder="<?php esc_attr_e( 'Success message when the user gets subscribed', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control">

					<p class="signals-form-help-block"><?php esc_attr_e( 'Provide message to show when the user gets subscribed successfully.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>
			</div>
		</div>
	</div>

	</div>
</div><!-- #email -->
