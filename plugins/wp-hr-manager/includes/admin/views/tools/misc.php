<div class="postbox">
    <div class="inside">
        <h3><?php _e( 'Send Test Email', 'wphr' ); ?></h3>

        <?php
        $email_settings = get_option( 'wphr_settings_wphr-email_general', [] );

        if ( isset( $_GET['sent'] ) ) {
            wphr_html_show_notice(  __( 'The test email has been sent by WordPress. Please note this does NOT mean it has been delivered.', 'wphr' ) );
        }
        ?>

        <form method="post" action="<?php echo admin_url( 'admin.php?page=wphr-tools&tab=misc' ); ?>" id="wphr-test-email-form">

            <table class="form-table">
                <tbody>
                    <tr>
                        <th>
                            <label for="to"><?php _e( 'To', 'wphr' ); ?> <span class="required">*</span></label>
                        </th>
                        <td>
                            <?php wphr_html_form_input([
                                'type'        => 'email',
                                'name'        => 'to',
                                'value'       => wp_get_current_user()->user_email,
                                'placeholder' => 'recipient@domain.com',
                                'custom_attr' => [
                                    'size' => 40
                                ]
                            ]); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="from"><?php _e( 'From', 'wphr' ); ?></label>
                        </th>
                        <td>
                            <?php
                                global $current_user;

                                $from_name  = ( ! empty( $email_settings['from_name'] ) ) ? $email_settings['from_name'] : $current_user->display_name;
                                $from_email = ( ! empty( $email_settings['from_email'] ) ) ? $email_settings['from_email'] : get_option( 'admin_email' );

                                wphr_html_form_input([
                                    'type'        => 'text',
                                    'name'        => 'from',
                                    'value'       => sprintf( '%s <%s>', $from_name, $from_email ),
                                    'custom_attr' => [
                                        'readonly' => 'readonly',
                                        'size'     => 40
                                    ]
                                ]);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="body"><?php _e( 'Message', 'wphr' ); ?></label>
                        </th>
                        <td>
                            <?php wphr_html_form_input([
                                'type'        => 'textarea',
                                'name'        => 'body',
                                'placeholder' => __( 'Leave blank to send default texts', 'wphr' ),
                                'custom_attr' => [
                                    'cols' => 45,
                                    'rows' => 6
                                ]
                            ]); ?>
                        </td>
                    </tr>
                </tbody>
            </table>

            <?php wp_nonce_field( 'wphr-test-email-nonce' ); ?>
            <?php submit_button( __( 'Send Email', 'wphr' ), 'primary', 'wphr_send_test_email' ); ?>
        </form>
    </div><!-- .inside -->
</div><!-- .postbox -->
