<div class="af2_custom_builder_wrapper">
    <h3 id="af2_contact_form_backend_heading" class="af2_builder_editable_object" data-editcontentid="backend_heading"></h3>    
    <div class="custom_builder_content split">
        <div class="custom_builder_content_split_section">
            <div class="custom_builder_content_card">
                <div class="custom_builder_content_card_heading af2_icon_text">
                    <div class="af2_icon_wrapper colorPink"><i class="fas fa-bell"></i></div>
                    <h5><?php _e('Notification e-mail', 'funnelforms-free'); ?></h5>
                </div>

                <div class="custom_builder_content_card_box_wrapper">
                    <div class="custom_builder_content_card_box">
                        <div class="custom_builder_content_card_box_heading">
                            <i class="fas fa-user"></i>
                            <p><?php _e('Sender name', 'funnelforms-free'); ?> *</p>
                        </div>
                        <div class="custom_builder_content_card_box_content">
                            <input type="text" placeholder="..." class="af2_edit_content_input" data-saveobjectid="mailfrom_name">
                        </div>
                    </div>
                    <div class="custom_builder_content_card_box">
                        <div class="custom_builder_content_card_box_heading">
                            <i class="fas fa-envelope"></i>
                            <p><?php _e('Sender e-mail', 'funnelforms-free'); ?> *</p>
                        </div>
                        <div class="custom_builder_content_card_box_description">
                            <p style="color: red; font-weight: 600"><?=__('Note: The sender e-mail should be from the same domain on which this form is active. If you want to use a different domain for sending emails, we recommend using the SMTP function.', 'funnelforms-free')?></p>
                        </div>
                        <div class="custom_builder_content_card_box_content">
                            <input type="text" placeholder="..." class="af2_edit_content_input" data-saveobjectid="mailfrom">
                        </div>
                    </div>
                    <div class="custom_builder_content_card_box">
                        <div class="custom_builder_content_card_box_heading">
                            <i class="fas fa-envelope"></i>
                            <p><?php _e('Recipient e-mail', 'funnelforms-free'); ?> *</p>
                        </div>
                        <div class="custom_builder_content_card_box_content">
                            <input type="text" placeholder="..." class="af2_edit_content_input" data-saveobjectid="mailto">
                        </div>
                    </div>

                    <div class="af2_gap"></div>

                    <div class="custom_builder_content_card_box">
                        <div class="custom_builder_content_card_box_heading">
                            <i class="fas fa-envelope"></i>
                            <p><?php _e('OPTIONAL: Carbon copy (CC) e-mail - comma separated', 'funnelforms-free'); ?></p>
                        </div>
                        <div class="custom_builder_content_card_box_content">
                            <input type="text" placeholder="..." class="af2_edit_content_input" data-saveobjectid="mailcc">
                        </div>
                    </div>
                    <div class="custom_builder_content_card_box">
                        <div class="custom_builder_content_card_box_heading">
                            <i class="fas fa-envelope"></i>
                            <p><?php _e('OPTIONAL: Blind carbon copy (BCC) e-mail - comma separated', 'funnelforms-free'); ?></p>
                        </div>
                        <div class="custom_builder_content_card_box_content">
                            <input type="text" placeholder="..." class="af2_edit_content_input" data-saveobjectid="mailbcc">
                        </div>
                    </div>
                    <div class="custom_builder_content_card_box">
                        <div class="custom_builder_content_card_box_heading">
                            <i class="fas fa-envelope"></i>
                            <p id="af2_mailreplyto"><?php _e('OPTIONAL: Reply-To e-mail - the following placeholders can be inserted:', 'funnelforms-free'); ?></p>
                        </div>
                        <div class="custom_builder_content_card_box_content">
                            <input type="text" placeholder="..." class="af2_edit_content_input" data-saveobjectid="mail_replyto">
                        </div>
                    </div>

                    <div class="af2_gap"></div>

                    <div class="custom_builder_content_card_box">
                        <div class="custom_builder_content_card_box_heading">
                            <i class="fas fa-align-left"></i>
                            <p id="af2_mailsubject"><?php _e('Subject - the following placeholders can be inserted:', 'funnelforms-free'); ?> *</p>
                        </div>
                        <div class="custom_builder_content_card_box_content">
                            <input type="text" placeholder="..." class="af2_edit_content_input" data-saveobjectid="mailsubject">
                        </div>
                    </div>
                    <div class="custom_builder_content_card_box">
                        <div class="custom_builder_content_card_box_heading">
                            <i class="fas fa-comments"></i>
                            <p id="af2_mailtext"><?php _e('Message - the following placeholders can be inserted:', 'funnelforms-free'); ?> *</p>
                        </div>
                        <div class="custom_builder_content_card_box_content">
                            <textarea placeholder="..." class="af2_edit_content_textarea" data-saveobjectid="mailtext">
                            </textarea>
                        </div>
                    </div>
                </div>
                

                <div class="af2_btn af2_btn_primary" id="af2_send_test_message"><i class="fas fa-paper-plane"></i><?php _e('Send test message', 'funnelforms-free'); ?>
                    <span class="af2_hide loading">&nbsp;<i class="fas fa-circle-notch fa-spin"></i></span>
                </div>
            </div>
        </div>
        <div class="custom_builder_content_split_section">
            <div class="custom_builder_content_card af2_disabled_custom_builder_content_card">
                <div class="af2_pro_sign"><i class="fas fa-star"></i><?php _e('PRO', 'funnelforms-free'); ?></div>
                <div class="custom_builder_content_card_heading af2_icon_text">
                    <div class="af2_icon_wrapper colorOrange"><i class="fas fa-cog"></i></div>
                    <h5><?php _e('Contact form settings', 'funnelforms-free'); ?></h5>
                </div>
                <div class="custom_builder_content_card_box_wrapper">
                    <div class="custom_builder_content_card_box">
                        <div class="custom_builder_content_card_box_heading">
                            <input type="checkbox"  id="af2_show_bottombar" placeholder="..." class="af2_edit_content_checkbox" data-saveobjectid="show_bottombar">
                            <p><label for="af2_show_bottombar"><?php _e('Activate this checkbox to display the progress bar on the contact form', 'funnelforms-free'); ?></label></p>
                        </div>
                    </div>
                    <div class="custom_builder_content_card_box">
                        <div class="custom_builder_content_card_box_heading">
                            <i class="fas fa-directions"></i>
                            <p id="af2_contactform_redirect_params"><?php _e('Redirect parameters (GET parameters) - the following placeholders can be inserted:', 'funnelforms-free'); ?></p>
                        </div>
                        <div id="af2_redirect_params_container" class="af2_redirect_params_container custom_builder_content_card_box_content">
                            <div id="af2_redirect_param_wrapper_add" class="af2_btn af2_btn_primary"><i class="fas fa-plus"></i><?php _e('Add GET parameters', 'funnelforms-free'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="custom_builder_content_card af2_disabled_custom_builder_content_card">
                <div class="af2_pro_sign"><i class="fas fa-star"></i><?php _e('PRO', 'funnelforms-free'); ?></div>
                <div id="af2_autoresponder_heading" class="custom_builder_content_card_heading af2_icon_text">
                    <div class="af2_icon_wrapper colorGreen"><i class="fas fa-envelope"></i></div>
                    <label for="af2_use_autorespond"><h5><?php _e('Automatic e-mail reply', 'funnelforms-free'); ?></h5></label>
                    <input type="checkbox"  id="af2_use_autorespond" placeholder="..." class="af2_edit_content_checkbox ml15" 
                        data-saveobjectid="use_autorespond" data-nomarginid="af2_autoresponder_heading" data-togglecontentid="af2_autoresponder_content">
                </div>
                <div id="af2_autoresponder_content" class="custom_builder_content_card_box_wrapper">
                    
                </div>
            </div>
            <div class="custom_builder_content_card">
                <div id="af2_smtp_heading" class="custom_builder_content_card_heading af2_icon_text">
                    <div class="af2_icon_wrapper colorBlue"><i class="fas fa-reply"></i></div>
                    <label for="af2_use_smtp"><h5><?php _e('SMTP Server (enter manually)', 'funnelforms-free'); ?></h5></label>
                    <input type="checkbox"  id="af2_use_smtp" placeholder="..." class="af2_edit_content_checkbox ml15" 
                        data-saveobjectid="use_smtp" data-nomarginid="af2_smtp_heading" data-togglecontentid="af2_smtp_content" data-radioclickgroupa="mail_type">
                    <label for="af2_use_wp_mail"><h5 class="ml15"><?php _e('WP Mail', 'funnelforms-free'); ?></h5></label>
                    <input type="checkbox"  id="af2_use_wp_mail" placeholder="..." class="af2_edit_content_checkbox ml15" 
                        data-saveobjectid="use_wp_mail" data-radioclickgroupa="mail_type">
                </div>
                <div id="af2_smtp_content" class="custom_builder_content_card_box_wrapper">
                    <div class="custom_builder_content_card_box">
                        <div class="custom_builder_content_card_box_heading">
                            <i class="fas fa-network-wired"></i>
                            <p id="af2_smtp_server"><?php _e('SMTP server', 'funnelforms-free'); ?> *</p>
                        </div>
                        <div class="custom_builder_content_card_box_content">
                            <input type="text" placeholder="..." class="af2_edit_content_input" data-saveobjectid="smtp_host">
                        </div>
                    </div>
                    <div class="custom_builder_content_card_box">
                        <div class="custom_builder_content_card_box_heading">
                            <i class="fas fa-user"></i>
                            <p id="af2_smtp_username"><?php _e('SMTP username', 'funnelforms-free'); ?> *</p>
                        </div>
                        <div class="custom_builder_content_card_box_content">
                            <input type="text" placeholder="..." class="af2_edit_content_input" data-saveobjectid="smtp_username">
                        </div>
                    </div>
                    <div class="custom_builder_content_card_box">
                        <div class="custom_builder_content_card_box_heading">
                            <i class="fas fa-lock"></i>
                            <p id="af2_smtp_password"><?php _e('SMTP password', 'funnelforms-free'); ?> *</p>
                        </div>
                        <div class="custom_builder_content_card_box_content">
                            <input type="password" placeholder="..." class="af2_edit_content_input" data-saveobjectid="smtp_password">
                        </div>
                    </div>
                    <div class="custom_builder_content_card_box">
                        <div class="custom_builder_content_card_box_heading">
                            <i class="fas fa-arrows-alt"></i>
                            <p id="af2_smtp_port"><?php _e('SMTP port', 'funnelforms-free'); ?> *</p>
                        </div>
                        <div class="custom_builder_content_card_box_content">
                            <input type="text" placeholder="..." class="af2_edit_content_input" data-saveobjectid="smtp_port">
                        </div>
                    </div>
                    <div class="custom_builder_content_card_box">
                        <div class="custom_builder_content_card_box_heading">
                            <i class="fas fa-key"></i>
                            <label for="af2_use_ssl"><p><?php _e('SSL', 'funnelforms-free'); ?></p></label>
                            <input type="checkbox"  id="af2_use_ssl" placeholder="..." class="af2_edit_content_checkbox ml15" 
                                data-saveobjectid="smtp_type" data-saveobjectidvalue="ssl" data-radioclickgroup="smtp_type">
                            <label for="af2_use_tls"><p class="ml15"><?php _e('TLS', 'funnelforms-free'); ?></p></label>
                            <input type="checkbox"  id="af2_use_tls" placeholder="..." class="af2_edit_content_checkbox ml15" 
                                data-saveobjectid="smtp_type" data-saveobjectidvalue="tls" data-radioclickgroup="smtp_type">
                        </div>
                    </div>
                </div>
            </div>
            <div class="custom_builder_content_card af2_disabled_custom_builder_content_card">
                <div class="af2_pro_sign"><i class="fas fa-star"></i><?php _e('PRO', 'funnelforms-free'); ?></div>
                <div class="custom_builder_content_card_heading af2_icon_text">
                    <div class="af2_icon_wrapper colorPrimary"><i class="fas fa-code"></i></div>
                    <h5><?php _e('JavaScript code (optional) - execute on load', 'funnelforms-free')?></h5>
                </div>
                <div class="custom_builder_content_card_box_wrapper">
                    <div class="custom_builder_content_card_box">
                        <div class="custom_builder_content_card_box_content">
                            <textarea placeholder="<?php _e('e.g. <script> Facebook Event Tracking </script>', 'funnelforms-free'); ?>" class="af2_edit_content_textarea" data-saveobjectid="tracking_code">
                            </textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="custom_builder_content_card af2_disabled_custom_builder_content_card">
                <div class="af2_pro_sign"><i class="fas fa-star"></i><?php _e('PRO', 'funnelforms-free'); ?></div>
                <div class="custom_builder_content_card_heading af2_icon_text">
                    <div class="af2_icon_wrapper colorPrimary"><i class="fas fa-code"></i></div>
                    <h5><?php _e('JavaScript code (optional) - execute after submit', 'funnelforms-free')?></h5>
                </div>
                <div class="custom_builder_content_card_box_wrapper">
                    <div class="custom_builder_content_card_box">
                        <div class="custom_builder_content_card_box_content">
                            <textarea placeholder="<?php _e('e.g. <script> Facebook Event Tracking </script>', 'funnelforms-free'); ?>" class="af2_edit_content_textarea" data-saveobjectid="tracking_code_after">
                            </textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="af2_testnachricht_modal" class="af2_modal"
    data-class="af2_testnachricht_modal"
    data-target="af2_testnachricht_modal"
    data-sizeclass="full_size"
    data-bottombar="false"
    data-heading="<?php _e('Log', 'funnelforms-free'); ?>"
    data-close="<?php _e('Close', 'funnelforms-free'); ?>">

  <!-- Modal content -->
  <div class="af2_modal_content">
    
  </div>
</div>
