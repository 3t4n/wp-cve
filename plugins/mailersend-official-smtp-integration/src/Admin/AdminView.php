<?php

namespace MailerSend\Admin;

use MailerSend\ConfigData;

class AdminView {

	/**
	 * Constructor
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public function __construct() {

		$this->getView();
	}

	/**
	 * Render Admin View
	 *
	 * @access      private
	 * @return      void
	 * @since       1.0.0
	 */
	private function getView() {

		?>

        <!-- Notice placeholder -->
        <h1></h1>

        <div id="mailersend-section">

            <h1>
				<?php _e( 'Send transactional emails with MailerSend', 'mailersend-official-smtp-integration' ); ?>
            </h1>

            <p>
				<?php
				echo sprintf(
					__( 'Connect your <a href="%1$s" target="_blank">SMTP credentials</a> with your website so you can send all your emails using our SMTP server.', 'mailersend-official-smtp-integration' ),
					esc_url( 'https://app.mailersend.com/domains' )
				);
				?>
            </p>

            <div class="mailersend-wrapper">

                <div class="ms-left_content">

                    <div class="mailersend-container">
                        <h2>
							<?php _e( 'SMTP username and password', 'mailersend-official-smtp-integration' ); ?>
                        </h2>

                        <form method="POST" action="" autocomplete="off">
                            <div class="mailersend-form">
                                <div class="mailersend-field">
                                    <div class="mailersend-label">
                                        <label for="smtp_user"><?php esc_html_e( 'Username (*)', 'mailersend-official-smtp-integration' ); ?></label>
                                    </div>
                                    <div class="mailersend-input">
                                        <input type="text" id="smtp_user" name="smtp_user" autocomplete="off"
                                               placeholder="SMTP Username"
                                               value="<?php echo esc_attr( ConfigData::get( 'mailersend_smtp_user' ) ); ?>">
                                    </div>
                                </div>
                                <div class="mailersend-field">
                                    <div class="mailersend-label">
                                        <label for="smtp_pass"><?php esc_html_e( 'Password (*)', 'mailersend-official-smtp-integration' ); ?></label>
                                        <div class="mailersend_input_box">
                                            <!--suppress HtmlFormInputWithoutLabel -->
                                            <input type="checkbox" id="smtp_config_file" name="smtp_config_file"
                                                   value="1" <?php if ( ConfigData::configMode() !== 'default' ) {
												echo 'checked';
											} ?>> Store in config files
                                        </div>
                                    </div>
                                    <div class="mailersend-input">
                                        <input type="password" id="smtp_pass" name="smtp_pass"
                                               class="<?php if ( ConfigData::configMode() !== 'default' ) {
											       echo 'is_hidden';
										       } ?>" autocomplete="off" placeholder="SMTP Password"
                                               value="<?php echo esc_attr( ConfigData::get( 'mailersend_smtp_pwd' ) ); ?>">
                                        <div id="smtp_config_def"
                                             class="mailersend_code_def <?php if ( ConfigData::configMode() === 'default' ) {
											     echo 'is_hidden';
										     } ?>">
											<?php if ( ! ConfigData::hasConfigCredentials() ): ?>
                                                <span>Copy the following code, paste it in your <code>wp-config.php</code> file and replace the PASSWORD with your credentials.</span>
                                                <!--suppress HtmlFormInputWithoutLabel -->
                                                <textarea readonly="readonly">define( 'MAILERSEND_SMTP_PASSWORD', 'PASSWORD' );
                                                </textarea>
											<?php else : ?>
                                                <span class="mailersend_defined_text">MAILERSEND_SMTP_PASSWORD found in config file</span>
											<?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="mailersend-field submit">
                                    <div class="mailersend-label"></div>
                                    <div class="mailersend-submit">
										<?php wp_nonce_field( 'update_credentials', 'mailersend_update_nonce' ); ?>
                                        <input type="hidden" name="action" value="update_credentials">
                                        <input type="submit" value="Save" class="button button-primary">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="mailersend-container">
                        <h2>
							<?php _e( 'Sender details', 'mailersend-official-smtp-integration' ); ?>
                        </h2>

                        <form method="POST" action="" autocomplete="off">
                            <div class="mailersend-form">
                                <div class="mailersend-field">
                                    <div class="mailersend-label">
                                        <label for="sender_name"><?php esc_html_e( 'Sender name', 'mailersend-official-smtp-integration' ); ?></label>
                                    </div>
                                    <div class="mailersend-input">
                                        <input type="text" id="sender_name" name="sender_name" autocomplete="off"
                                               placeholder="Sender name"
                                               value="<?php echo esc_attr( ConfigData::get( 'mailersend_sender_name' ) ); ?>">
                                    </div>
                                </div>
                                <div class="mailersend-field">
                                    <div class="mailersend-label">
                                        <label for="sender_email"><?php esc_html_e( 'Sender address', 'mailersend-official-smtp-integration' ); ?></label>
                                    </div>
                                    <div class="mailersend-input">
                                        <input type="text" id="sender_email" name="sender_email" autocomplete="off"
                                               placeholder="Sender address"
                                               value="<?php echo esc_attr( ConfigData::get( 'mailersend_sender_email' ) ); ?>">
                                    </div>
                                </div>
                                <div class="mailersend-field">
                                    <div class="mailersend-label">
                                        <label for="cc_recipient"><?php esc_html_e( 'CC recipients', 'mailersend-official-smtp-integration' ); ?></label>
                                    </div>
                                    <div class="mailersend-input">
                                        <input type="text" id="cc_recipient" name="cc_recipient" autocomplete="off"
                                               placeholder="Recipients separated by ;"
                                               value="<?php echo esc_attr( ConfigData::get( 'mailersend_recipient_cc' ) ); ?>">
                                    </div>
                                </div>
                                <div class="mailersend-field">
                                    <div class="mailersend-label">
                                        <label for="bcc_recipient"><?php esc_html_e( 'BCC recipients', 'mailersend-official-smtp-integration' ); ?></label>
                                    </div>
                                    <div class="mailersend-input">
                                        <input type="text" id="bcc_recipient" name="bcc_recipient" autocomplete="off"
                                               placeholder="Recipients separated by ;"
                                               value="<?php echo esc_attr( ConfigData::get( 'mailersend_recipient_bcc' ) ); ?>">
                                    </div>
                                </div>
                                <div class="mailersend-field">
                                    <div class="mailersend-label">
                                        <label for="reply_to"><?php esc_html_e( 'Reply-to address', 'mailersend-official-smtp-integration' ); ?></label>
                                    </div>
                                    <div class="mailersend-input">
                                        <input type="text" id="reply_to" name="reply_to" autocomplete="off"
                                               placeholder="Reply-to Email Address"
                                               value="<?php echo esc_attr( ConfigData::get( 'mailersend_reply_to' ) ); ?>">
                                    </div>
                                </div>
                                <div class="mailersend-field">
                                    <div class="mailersend-label">
                                        <label for="sender_tags"><?php esc_html_e( 'Tags', 'mailersend-official-smtp-integration' ); ?></label>
                                    </div>
                                    <div class="mailersend-input">
                                        <input type="text" id="sender_tags" name="sender_tags" autocomplete="off"
                                               placeholder="Maximum 5 custom tags separated by ,"
                                               value="<?php echo esc_attr( ConfigData::get( 'mailersend_sender_tags' ) ); ?>">
                                    </div>
                                </div>
                                <div class="mailersend-field submit">
                                    <div class="mailersend-label"></div>
                                    <div class="mailersend-submit">
										<?php wp_nonce_field( 'update_settings', 'mailersend_update_nonce' ); ?>
                                        <input type="hidden" name="action" value="update_settings">
                                        <input type="submit" value="Save" class="button button-primary">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="mailersend-container">
                        <h2>
							<?php _e( 'Send a test email', 'mailersend-official-smtp-integration' ); ?>
                        </h2>

                        <form method="POST" action="" autocomplete="off">
                            <div class="mailersend-form">
								<?php
								$error_list = get_transient( 'mailersend_error' );

								if ( $error_list !== false ):
									?>
                                    <div class="mailersend-field">
                                        <div class="mailersend-label">
                                            <label for="mailersend_log"
                                                   class="danger"><?php esc_html_e( 'Error log', 'mailersend-official-smtp-integration' ); ?></label>
                                        </div>
                                        <div class="mailersend-input">
                                            <strong><?php esc_html_e( 'The following error has occurred: ', 'mailersend-official-smtp-integration' ); ?></strong>
                                            <textarea
                                                    id="mailersend_log"><?php foreach ( $error_list as $error ) : echo esc_textarea( $error ) . PHP_EOL; endforeach; ?></textarea>
                                        </div>
                                    </div>
								<?php endif; ?>
                                <div class="mailersend-field">
                                    <div class="mailersend-label">
                                        <label for="test_recipient"><?php esc_html_e( 'Recipient (*)', 'mailersend-official-smtp-integration' ); ?></label>
                                    </div>
                                    <div class="mailersend-input">
                                        <input type="text" id="test_recipient" name="test_recipient"
                                               placeholder="Email address"
                                               value="<?php echo esc_attr( wp_get_current_user()->user_email ); ?>" <?php if ( ConfigData::hasCredentials() === false ) {
											echo 'disabled';
										} ?>>
                                    </div>
                                </div>
                                <div class="mailersend-field submit">
                                    <div class="mailersend-label"></div>
                                    <div class="mailersend-submit">
										<?php wp_nonce_field( 'mailer_test', 'mailersend_test_nonce' ); ?>
                                        <input type="hidden" name="action" value="mailer_test">
                                        <input type="submit" value="Test"
                                               class="button button-primary" <?php if ( ! ConfigData::hasCredentials() ) {
											echo 'disabled';
										} ?>>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="mailersend-container mailersend-danger-zone">

                        <p class="danger">
							<?php _e( 'Danger Zone', 'mailersend-official-smtp-integration' ); ?>
                        </p>

                        <p>
							<?php
							echo sprintf( __( 'Click %1sDelete information%2$s if you want to uninstall the plugin and remove all crucial data stored in the database. This will remove all your settings and deactivate the plugin. Warning! This action can’t be undone.' ),
								'<strong>',
								'</strong>',
								'mailersend-official-smtp-integration'
							);
							?>
                        </p>

                        <form method="POST" action="" autocomplete="off">
                            <div class="mailersend-form">
                                <div class="mailersend-field submit">
                                    <div class="mailersend-submit">
										<?php wp_nonce_field( 'mailer_delete', 'mailersend_delete_nonce' ); ?>
                                        <input type="hidden" name="action" value="mailer_delete">
                                        <input type="submit" id="sub_danger" name="sub_danger"
                                               onclick="if (!confirm('Are you sure you want to delete all your MailerSend account information and deactivate the plugin?')) return false;"
                                               class="mailersend-delete button" value="Delete information"/>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <p>
						<?php
						echo sprintf(
							__( '<a href="%1$s">Made by MailerSend Version %2$s</a>', 'mailersend-official-smtp-integration' ),
							esc_url( 'https://www.mailersend.com' ),
							esc_attr( MAILERSEND_SMTP_VER )
						);
						?>
                    </p>

                </div>

                <div class="ms-right_content">
					<?php new SidebarView(); ?>
                </div>
            </div>
        </div>
		<?php
	}
}
