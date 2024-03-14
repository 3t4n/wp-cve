<?php
$global_options = $options['global'];
$payments = $options['payments'];
$payments_settings = $options['payments_settings'];
$emailers = $options['emailers'];
$emailers_settings = $options['emailers_settings'];?>

<div class="ep-emails-tab-content">
    <h2><?php esc_html_e( 'Emails', 'eventprime-event-calendar-management' );?></h2>
    <input type="hidden" name="em_setting_type" value="email_settings">
</div>
<?php if( isset( $_GET['section'] ) ):?>
    <div class="ep-emailer-settings">
        <p class="ep-global-back-btn">
            <?php $back_url = remove_query_arg( 'section' ) ;?>
            <a href="<?php echo esc_url( $back_url );?>" class="ep-back-btn ep-di-flex ep-align-items-center ep-text-decoration-none">
                <span class="material-icons">navigate_before</span> <?php esc_html_e( 'Back', 'eventprime-event-calendar-management' );?>
            </a>
        </p><?php
        $active_emailer_setting = strtolower( sanitize_text_field( $_GET['section'] ) );
        if( count( $payments_settings ) ) {?>
            <div class="ep-emailer-setting-page" id="ep-emailer-setting-<?php echo esc_attr( $active_emailer_setting );?>">
                <h4 class="ep-emailer-setting-title"><?php echo esc_attr( $emailers[$active_emailer_setting]['title'] );?></h4>
                <p class="ep-emailer-setting-description"><?php echo esc_attr( $emailers[$active_emailer_setting]['description'] );?></p>
                <?php echo $emailers_settings[$active_emailer_setting];?>
                <input type="hidden" value="<?php echo esc_attr( $active_emailer_setting );?>" name="em_emailer_type">
            </div><?php
        }?>
    </div><?php
else:?>
    <div class="ep-emailer-list">
        <table class="form-table">
            <tbody>
                <tr valign="top" >
                    <td class="ep-form-table-wrapper" colspan="2">
                        <table class="ep-form-table-setting ep-setting-table widefat">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e( 'Status', 'eventprime-event-calendar-management' ); ?></th>
                                    <th><?php esc_html_e( 'Title', 'eventprime-event-calendar-management' ); ?></th>
                                    <th><?php esc_html_e( 'Description', 'eventprime-event-calendar-management' ); ?></th>
                                    <th><?php esc_html_e( 'Recipient(s)', 'eventprime-event-calendar-management' ); ?></th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody id="ep-emailer-sortable"><?php
                                if ( count( $emailers ) ) {
                                    foreach( $emailers as $key => $emailer ) {
                                        $tab_url = esc_url( add_query_arg( array( 'tab' => 'emails', 'section' => $key ) ) );?>
                                        <tr class="ep-emailers" id="ep-emailer-<?php echo esc_html( $key ) ?>">
                                            <td class="ep-emailer-status"><?php
                                                if ( isset( $emailer['enable_key'] ) && ! empty( $emailer['enable_key'] ) ) {
                                                    $enable_key = $emailer['enable_key'];
                                                    if ( isset( $global_options->$enable_key ) && $global_options->$enable_key == 1 ) {
                                                        esc_html_e( 'Enabled', 'eventprime-event-calendar-management' );
                                                    } else {
                                                        esc_html_e( 'Disabled', 'eventprime-event-calendar-management' );
                                                    }
                                                } else {?>
                                                    <span><?php esc_html_e( 'Enabled', 'eventprime-event-calendar-management' );?></span><?php
                                                }?>
                                            </td>
                                            <td class="ep-emailer-label">
                                                <?php echo esc_html( $emailer['title'] );?>
                                            </td>

                                            <td class="ep-emailer-description">
                                                <?php echo esc_html( $emailer['description'] );?>
                                            </td>
                                            <td class="ep-emailer-recipient">
                                                <?php echo esc_html( $emailer['recipient'] );?>
                                            </td>
                                            <td class="ep-emailer-setting">
                                                <a href="<?php echo esc_url( $tab_url ); ?>" class="button alignright">
                                                    <?php esc_html_e( 'Manage', 'eventprime-event-calendar-management' ); ?>
                                                </a>
                                            </td>
                                        </tr><?php
                                    }
                                }?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <table class="form-table">
            <input type="hidden" name="em_emailer_type" value="basic">
            <tbody>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="disable_admin_email">
                            <?php esc_html_e( 'Disable All Admin Emails ', 'eventprime-event-calendar-management' );?>
                        </label>
                    </th>
                    <td class="forminp forminp-text">
                        <label class="ep-toggle-btn">
                            <input name="disable_admin_email" id="disable_admin_email" type="checkbox" value="1" <?php echo isset( $global_options->disable_admin_email ) && $global_options->disable_admin_email == 1 ? 'checked' : '';?>>
                            <span class="ep-toogle-slider round"></span>
                        </label>
                        <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, all admin emails will be toggled off simultaneously. Useful when you wish to stop receiving all emails from EventPrime.', 'eventprime-event-calendar-management' );?></div>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="disable_frontend_email">
                            <?php esc_html_e( 'Disable All User Emails', 'eventprime-event-calendar-management' );?>
                        </label>
                    </th>
                    <td class="forminp forminp-text">
                        <label class="ep-toggle-btn">
                            <input name="disable_frontend_email" id="disable_frontend_email" type="checkbox" value="1" <?php echo isset($global_options->disable_frontend_email ) && $global_options->disable_frontend_email == 1 ? 'checked' : '';?>>
                            <span class="ep-toogle-slider round"></span>
                        </label>
                        <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, all outgoing user emails will be toggled off simultaneously. Useful when you wish to stop sending out all emails from EventPrime to the users.', 'eventprime-event-calendar-management' );?></div>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="ep_admin_email_to">
                            <?php esc_html_e( 'Admin Recipient', 'eventprime-event-calendar-management' );?>
                        </label>
                    </th>
                    <td class="forminp forminp-text">
                        <input type="text" class="regular-text" name="ep_admin_email_to" id="ep_admin_email_to"  value="<?php echo ( isset( $global_options->ep_admin_email_to ) && ! empty( $global_options->ep_admin_email_to ) ) ? $global_options->ep_admin_email_to : '';?>">
                        <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'The email address where the admin notification emails will be sent.', 'eventprime-event-calendar-management' );?></div>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="ep_admin_email_from">
                            <?php esc_html_e( "Sender's Email", 'eventprime-event-calendar-management' );?>
                        </label>
                    </th>
                    <td class="forminp forminp-text">
                        <input type="text" class="regular-text" name="ep_admin_email_from" id="ep_admin_email_from"  value="<?php echo ( isset( $global_options->ep_admin_email_from ) && ! empty( $global_options->ep_admin_email_from ) ) ? $global_options->ep_admin_email_from : '';?>">
                        <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'The email address from which the emails will be sent to the users. Make sure that your web server is allowed to send emails from this address.', 'eventprime-event-calendar-management' );?></div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
<?php endif;?>