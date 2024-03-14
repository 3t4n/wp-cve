<?php 
$options                = get_option('ultimate_subscribe_options');
$admin_mail_from_name   = isset($options['from_name'])? $options['from_name']:'Admin';
$admin_mail_from_email  = isset($options['from_email'])? $options['from_email']:get_bloginfo('admin_email');
$send_mail_type         = isset($options['send_mail_type'])? $options['send_mail_type']:'html';
$confirm_mail_subject   = isset($options['confirm_mail_subject'])? $options['confirm_mail_subject']: sprintf(__('%s confirm subscription', 'ultimate-subscribe'), get_bloginfo('name'));
$confirm_mail_content   = isset($options['confirm_mail_content'])? $options['confirm_mail_content']:'Hi ###NAME###,

A newsletter subscription request for this email address was received. Please confirm it by <a href="###LINK###">clicking here</a>. 

If you still cannot subscribe, please click this link : 
###LINK### 

Thank You
Plugin Dev';

$is_send_welcome_mail   = isset($options['is_send_welcome_mail'])? $options['is_send_welcome_mail']:'yes';
$welcome_mail_subject   = isset($options['welcome_mail_subject'])? $options['welcome_mail_subject']:sprintf(__('%s Welcome to our newsletter', 'ultimate-subscribe'), get_bloginfo('name'));
$welcome_mail_content   = isset($options['welcome_mail_content'])? $options['welcome_mail_content']:'Hi ###NAME###, 

We have received a request to subscribe this email address to receive newsletter from our website. 

Thank You
Plugin Dev 

No longer interested in emails from Plugin Dev?. Please <a href="###LINK###">click here</a> to unsubscribe';

$is_send_admin_mail     = isset($options['is_send_admin_mail'])? $options['is_send_admin_mail']:'yes';
$admin_email_address    = isset($options['admin_email_address'])? $options['admin_email_address']:get_bloginfo('admin_email');
$admin_mail_subject     = isset($options['admin_mail_subject'])? $options['admin_mail_subject']:sprintf(__('%s New subscriber', 'ultimate-subscribe'), get_bloginfo('name'));
$admin_mail_content     = isset($options['admin_mail_content'])? $options['admin_mail_content']:'Hi Admin, 

We have received a request to subscribe new email address to receive emails from our website. 

Email: ###EMAIL### 
Name : ###NAME### 

Thank You
Plugin Dev';

?>
<div id="mail-options" class="tab-pane">
    <h3> <?php esc_html_e('Mail Options', 'ultimate-subscribe'); ?> </h3>
    <div class="form-fieldset">
        <div class="field-row">
            <div class="field-label"> <?php _e('Sender of notifications', 'ultimate-subscribe'); ?> </div>
            <div class="field-data"> 
                <?php _e('Name:', 'ultimate-subscribe'); ?> <input type="text" class="input-field" name="ultimate_subscribe_options[from_name]" value="<?php echo esc_attr($admin_mail_from_name); ?>" />
                <?php _e('Email:', 'ultimate-subscribe'); ?> <input type="text" class="input-field" name="ultimate_subscribe_options[from_email]" value="<?php echo esc_attr($admin_mail_from_email); ?>" />
                <br>
                <span class="description"><?php _e('Choose a FROM name and FROM email address for all notifications emails from this plugin.','ultimate-subscribe'); ?></span>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label"> <?php _e('Mail Type', 'ultimate-subscribe'); ?> </div>
            <div class="field-data"> 
                <select name="ultimate_subscribe_options[send_mail_type]">
                    <option value="html" <?php selected($send_mail_type, 'html', true) ?>><?php _e('HTML', 'ultimate-subscribe'); ?></option>
                    <option value="plain" <?php selected($send_mail_type, 'plain', true) ?>><?php _e('PLAIN TEXT', 'ultimate-subscribe'); ?></option>
                </select>
                <br>
                <span class="description"><?php _e('Choose a sent email type HTML or PLAIN TEXT','ultimate-subscribe'); ?></span>
            </div>
        </div>
        <div class="field-group">
            <div class="field-row">
                <div class="field-label"> <?php _e('Confirmation mail subject ', 'ultimate-subscribe'); ?> </div>
                <div class="field-data"> 
                    <input type="text" class="input-field widefat email-notice" name="ultimate_subscribe_options[confirm_mail_subject]" value="<?php echo esc_attr($confirm_mail_subject); ?>" />
                    <br>
                    <span class="description"><?php _e('Enter Confirmation mail subject','ultimate-subscribe'); ?></span>
                </div>
            </div>
            <div class="field-row">
                <div class="field-label"> <?php _e('Confirmation mail content', 'ultimate-subscribe'); ?> </div>
                <div class="field-data"> 
                    <textarea class="input-field widefat email-notice" name="ultimate_subscribe_options[confirm_mail_content]"><?php echo esc_textarea($confirm_mail_content); ?></textarea> 
                    <br>
                    <span class="description"><?php _e('Enter Confirmation mail content','ultimate-subscribe'); ?></span>
                </div>
            </div>
        </div>
        <div class="field-group">
            <div class="field-row">
                <div class="field-label"> <?php _e('Subscriber welcome email', 'ultimate-subscribe'); ?> </div>
                <div class="field-data"> 
                    <select name="ultimate_subscribe_options[is_send_welcome_mail]">
                        <option value="yes" <?php selected($is_send_welcome_mail, 'yes', true); ?>><?php _e('YES', 'ultimate-subscribe') ?></option>
                        <option value="no" <?php selected($is_send_welcome_mail, 'no', true); ?>><?php _e('NO', 'ultimate-subscribe') ?></option>
                    </select>
                    <br>
                    <span class="description"><?php _e('Choose a FROM name and FROM email address for all notifications emails from this plugin.','ultimate-subscribe'); ?></span>
                </div>
            </div>
            <div class="field-row">
                <div class="field-label"> <?php _e('Welcome mail subject', 'ultimate-subscribe'); ?> </div>
                <div class="field-data"> 
                    <input type="text" class="input-field widefat email-notice" name="ultimate_subscribe_options[welcome_mail_subject]" value="<?php echo esc_attr($welcome_mail_subject); ?>" />
                    <br>
                    <span class="description"><?php _e('Enter Welcome mail subject','ultimate-subscribe'); ?></span>
                </div>
            </div>
            <div class="field-row">
                <div class="field-label"> <?php _e('Welcome mail content', 'ultimate-subscribe'); ?> </div>
                <div class="field-data"> 
                    <textarea class="input-field widefat email-notice" name="ultimate_subscribe_options[welcome_mail_content]"><?php echo esc_textarea($welcome_mail_content); ?></textarea> 
                    <br>
                    <span class="description"><?php _e('Enter Welcome mail content','ultimate-subscribe'); ?></span>
                </div>

            </div>
        </div>
        <div class="field-group">
            <div class="field-row">
                <div class="field-label"> <?php _e('Mail to admin', 'ultimate-subscribe'); ?> </div>
                <div class="field-data"> 
                    <select name="ultimate_subscribe_options[is_send_admin_mail]">
                        <option value="yes" <?php selected($is_send_admin_mail, 'yes', true); ?>><?php _e('YES', 'ultimate-subscribe') ?></option>
                        <option value="no" <?php selected($is_send_admin_mail, 'no', true); ?>><?php _e('NO', 'ultimate-subscribe') ?></option>
                    </select>
                    <br>
                    <span class="description"><?php _e('To send admin notifications for new subscriber, This option must be set to YES.','ultimate-subscribe'); ?></span>
                </div>
            </div>
            <div class="field-row">
                <div class="field-label"> <?php _e('Admin email addresses', 'ultimate-subscribe'); ?> </div>
                <div class="field-data"> 
                    <input type="text" class="input-field widefat email-notice" name="ultimate_subscribe_options[admin_email_address]" value="<?php echo esc_attr($admin_email_address); ?>" />
                    <br>
                    <span class="description"><?php _e('Enter the admin email addresses that should receive notifications (separate by comma).','ultimate-subscribe'); ?></span>
                </div>
            </div>
            <div class="field-row">
                <div class="field-label"> <?php _e('Admin mail subject ', 'ultimate-subscribe'); ?> </div>
                <div class="field-data"> 
                    <input type="text" class="input-field widefat email-notice" name="ultimate_subscribe_options[admin_mail_subject]" value="<?php echo esc_attr($admin_mail_subject); ?>" />
                    <br>
                    <span class="description"><?php _e('Enter the subject for admin mail.','ultimate-subscribe'); ?></span>
                </div>
            </div>
            <div class="field-row">
                <div class="field-label"> <?php _e('Admin mail content', 'ultimate-subscribe'); ?> </div>
                <div class="field-data"> 
                    <textarea class="input-field widefat email-notice" name="ultimate_subscribe_options[admin_mail_content]"><?php echo esc_textarea($admin_mail_content); ?></textarea>
                    <br>
                    <span class="description"><?php _e('Enter the mail content for admin. ','ultimate-subscribe'); ?></span>
                </div>                
            </div>
        </div>
    </div>
</div>