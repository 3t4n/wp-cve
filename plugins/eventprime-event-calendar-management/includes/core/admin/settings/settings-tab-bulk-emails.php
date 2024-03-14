<div class="eventprime">
    <div class="wrap">
        <div class="ep-title-section">
            <h2>
                <?php esc_html_e('Email Attendees','eventprime-event-calendar-management')?>
            </h2>
        </div>
        
        <div class="ep-form-section">
            <form id="ep-email-attendies" action="<?php echo admin_url( 'admin-post.php' ); ?>" class="ep-email-attendies-form" enctype="multipart/form-data">
                <table class="form-table">
                    <tr>
                        <th scope="row" class="titledesc">
                            <label for="em_booking_details_page">
                                <?php esc_html_e('To (Email Address)','eventprime-event-calendar-management');?>
                            </label>
                        </th>
                        <td>
                            <textarea id="ep-email-address-lists" name="email_address" rows="4" class="ep-form-control"></textarea>
                            <div class="em-bulkemail-btn dbfl"> 
                                <a href="javascript:void(0)" onClick="ep_email_attendies_hide_show()">
                                    <?php esc_html_e('Auto-populate attendee email addresses from an event','eventprime-event-calendar-management');?>
                                </a>
                            </div>
                            <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Multiple email addresses supported. You can auto-populate this field with email addresses of attendees from an event by using the link below.', 'eventprime-event-calendar-management' );?></div>
                        </td>
                    </tr>
                    <tr id="ep-autopopulate" style="display:none;">
                        <th scope="row" class="titledesc">
                            <label for="em_booking_details_page">
                                <?php esc_html_e('Select Event','eventprime-event-calendar-management');?>
                            </label>
                        </th>
                        <td>
                            <select name="ep_event_id" id="ep_populate_email" class="ep-form-control">
                                <option value=""><?php esc_html_e( 'Select Event','eventprime-event-calendar-management' ); ?></option>
                                <?php foreach( $events as $event ) {?>
                                    <option value="<?php echo esc_attr( $event['id'] ); ?>" ><?php echo esc_attr( $event['name'] ); ?></option>
                                <?php } ?>
                            </select>
                            <div id="ep-email-not-found" style="display:none;"></div>
                            <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Add CC email address.', 'eventprime-event-calendar-management' );?></div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="titledesc">
                            <label for="em_booking_details_page">
                                <?php esc_html_e('CC Email Address','eventprime-event-calendar-management');?>
                            </label>
                        </th>
                        <td>
                            <input type="text" name="cc_email_address" id="ep_email_cc" class="ep-form-control">
                            <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Add CC email address.', 'eventprime-event-calendar-management' );?></div>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row" class="titledesc">
                            <label for="em_booking_details_page">
                                <?php esc_html_e('Subject*','eventprime-event-calendar-management');?>
                            </label>
                        </th>
                        <td>
                            <input type="text" name="email_subject" id="ep_email_subject" class="ep-form-control">
                            <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Subject of your email.', 'eventprime-event-calendar-management' );?></div>
                        </td>
                    </tr>
                    
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="booking_pending_email">
                                <?php esc_html_e( 'Contents*', 'eventprime-event-calendar-management' );?><span class="ep-required">*</span>
                            </label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php 
                            $settings = array( 
                                'editor_height' => 200,
                                'textarea_rows' => 20,
                                'id'=>'ep_email_content'
                             );
                            wp_editor( '', 'content', $settings );?>
                            <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Body of your email. Rich text supported.', 'eventprime-event-calendar-management' );?></div>
                        
                        </td>
                    </tr>
                </table>
                <div class="ep-email-attendies-submit-area">
                    <button name="save" class="button-primary ep-save-button" type="button" id="ep_send_email_attendies" value="<?php esc_attr_e( 'Send', 'eventprime-event-calendar-management' ); ?>">
                        <?php esc_html_e( 'Send', 'eventprime-event-calendar-management' ); ?>
                    </button>
                    <?php wp_nonce_field( 'ep_email_attendies' ); ?>
                </div>
                <div class="ep-attendies-error" style="display:none;"></div>
            </form>
        </div>
    </div>
</div>