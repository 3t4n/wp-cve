<div class="wrap">
<form method="post" action="options.php" id="it_epoll_options_fields">
    <h1 class="epoll_admin_options-header">
        <?php esc_attr_e('Options','it_epoll');?>
        <button type="submit" class="page-title-action button button-primary right" role="submit"><span class="upload"><?php esc_attr_e('Save Changes','it_epoll');?></span></button>
        <a target="_blank" href="<?php echo esc_url('https://infotheme.net/item/epoll-pro/','it_epoll');?>" class="button right" style="margin:0 25px;position:relative;bottom:3px;" role="button"><span class="upload"><?php esc_attr_e('Buy ePoll PRO','it_epoll');?></span></a>
   </h1>
    <?php
         settings_fields( 'it_epoll_opt_settings' );
         do_settings_sections( 'it_epoll_opt_settings' );
         ?>
        <div class="epoll_admin_options-container">
            <div class="epoll_admin_options-tabs">
                <ul class="epoll_admin_options-tabs-container">
                    <li>
                        <a href="#general" class="epoll_admin_option-item current">
                        <i class="dashicons dashicons-admin-settings"></i> <?php esc_attr_e('General','it_epoll');?>
                        </a>
                    </li>
                    <li>
                        <a href="#sharing" class="epoll_admin_option-item">
                        <i class="dashicons dashicons-share"></i> <?php esc_attr_e('Sharing','it_epoll');?>
                        </a>
                    </li>
                    <li>
                        <a href="#advanced" class="epoll_admin_option-item">
                        <i class="dashicons dashicons-admin-generic"></i> <?php esc_attr_e('Advanced','it_epoll');?>
                        </a>
                    </li>
                    <li>
                        <a href="#notifications" class="epoll_admin_option-item">
                        <i class="dashicons dashicons-email"></i> <?php esc_attr_e('Email & Notifications','it_epoll');?>
                        </a>
                    </li>
                    <li>
                        <a href="#translate" class="epoll_admin_option-item">
                        <i class="dashicons dashicons-admin-site"></i> <?php esc_attr_e('Localize / Translation','it_epoll');?>
                        </a>
                    </li>
                    <?php do_action('it_epoll_options_extra_tabs_title');?>
                </ul>
            </div>
            <div class="epoll_admin_options_tab-content">
                <div class="epoll_admin_options-tabs-content current" id="general">
                    <table class="widefat no-border-table">
                        <tbody>
                          
                            <tr>
                                <td>   
                                    <label>
                                        <input class="it_epoll_has_oncheck_div" type="checkbox" name="it_epoll_settings_hcaptcha_voting" value="1"<?php if(get_option('it_epoll_settings_hcaptcha_voting')) echo esc_attr(' checked','it_epoll');?>/> <?php esc_attr_e('Enable hCaptch on Voting','it_epoll');?>
                                    </label>
                                    <div class="it_epoll_oncheck_div <?php if(get_option('it_epoll_settings_hcaptcha_voting')) echo esc_attr(' it_epoll_oncheck_div_show','it_epoll');?>">
                                        <label><?php esc_attr_e('hCaptcha Key','it_epoll');?></label>
                                        <input type="text" class="widefat" name="it_epoll_settings_hcaptcha_key" value="<?php echo esc_attr(get_option('it_epoll_settings_hcaptcha_key'),'it_epoll');?>"/>
                                        <hr>
                                        <label><?php esc_attr_e('hCaptcha Security Salt','it_epoll');?></label>
                                        <input type="text" class="widefat" name="it_epoll_settings_hcaptcha_salt" value="<?php echo esc_attr(get_option('it_epoll_settings_hcaptcha_salt'),'it_epoll');?>"/>
                                    
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>   
                                    <label>
                                        <input type="checkbox" name="it_epoll_settings_enable_comments"  value="1" <?php if(get_option('it_epoll_settings_enable_comments') == 1) echo esc_attr(' checked','it_epoll');?>/> <?php esc_attr_e('Enable Comments on Vote','it_epoll');?>
                                    </label>
                                </td>
                            </tr>

                            <tr>
                                <td> 
                                    <label>
                                        <input type="checkbox" name="it_epoll_settings_ip_based_voting" disabled/> <?php esc_attr_e('Enable IP Based Voting','it_epoll');?>  <span class="it_epolladmin_pro_badge"><?php esc_attr_e('Pro','it_epoll');?></span>  
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td> 
                                    <label>
                                        <input type="checkbox" name="it_epoll_settings_otp_based_voting" disabled/> <?php esc_attr_e('Enable OTP Based Voting','it_epoll');?>  <span class="it_epolladmin_pro_badge"><?php esc_attr_e('Pro','it_epoll');?></span>  
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>   
                                    <label>
                                        <input type="checkbox" name="it_epoll_settings_disable_branding" disabled/> <?php esc_attr_e('Remove Poll Footer Branding Link','it_epoll');?>  <span class="it_epolladmin_pro_badge"><?php esc_attr_e('Pro','it_epoll');?></span>  
                                    </label>
                                </td>
                            </tr>

                         
                          
                        </tbody>
                    </table>
                    <?php do_action('it_epoll_options_general_fields');?>
                </div>
                <div class="epoll_admin_options-tabs-content" id="sharing">
                <table class="widefat no-border-table">
                        <tbody>
                            <tr>
                                <td>
                                    <label>
                                        <input type="checkbox" name="it_epoll_settings_voting_social_sharing" value="1"<?php if(get_option('it_epoll_settings_voting_social_sharing')) echo esc_attr(' checked','it_epoll');?>/> <?php esc_attr_e('Enable Social Sharing on Voting','it_epoll');?>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                        <input type="checkbox" name="it_epoll_settings_poll_social_sharing" value="1"<?php if(get_option('it_epoll_settings_poll_social_sharing')) echo esc_attr(' checked','it_epoll');?>/> <?php esc_attr_e('Enable Social Sharing on Poll','it_epoll');?>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                        <input type="checkbox" name="it_epoll_settings_social_option_facebook" value="1"<?php if(get_option('it_epoll_settings_social_option_facebook')) echo esc_attr(' checked','it_epoll');?>/> <?php esc_attr_e('Facebook','it_epoll');?>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                        <input type="checkbox" name="it_epoll_settings_social_option_twitter"  value="1"<?php if(get_option('it_epoll_settings_social_option_twitter')) echo esc_attr(' checked','it_epoll');?>/> <?php esc_attr_e('Twitter','it_epoll');?>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                        <input type="checkbox" name="it_epoll_settings_social_option_whatsapp" value="1"<?php if(get_option('it_epoll_settings_social_option_whatsapp')) echo esc_attr(' checked','it_epoll');?>/> <?php esc_attr_e('WhatsApp','it_epoll');?>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                        <?php esc_attr_e('More Social Links Available on Social Sharing Pro Addon','it_epoll');?> <span class="it_epolladmin_pro_badge"><?php esc_attr_e('Pro','it_epoll');?></span>
                                    </label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php do_action('it_epoll_options_sharing_fields');?>
                </div>
                <div class="epoll_admin_options-tabs-content" id="advanced">
                    <table class="widefat no-border-table">
                        <tbody>
                            <tr>
                                <td>   
                                    <label>
                                        <input type="checkbox" name="it_epoll_settings_hide_voting_result" value="1"<?php if(get_option('it_epoll_settings_hide_voting_result')) echo esc_attr(' checked','it_epoll');?>/> <?php esc_attr_e('Hide All Voting Results','it_epoll');?>
                                    </label>
                                </td>
                            </tr> 
                            <tr>
                                <td>  
                                    <label>
                                        <input type="checkbox" name="it_epoll_settings_hide_poll_result" value="1"<?php if(get_option('it_epoll_settings_hide_poll_result')) echo esc_attr(' checked','it_epoll');?>/> <?php esc_attr_e('Hide All Poll Results','it_epoll');?>
                                    </label> 
                                </td>
                            </tr> 
                            <tr>
                                <td>  
                                    <label>
                                        <input type="checkbox" name="it_epoll_settings_collect_email" disabled/> <?php esc_attr_e('Collect Email on Vote Submission','it_epoll');?> <span class="it_epolladmin_pro_badge"><?php esc_attr_e('Pro','it_epoll');?></span>
                                    </label> 
                                </td>
                            </tr>  
                            <tr>
                                <td>   
                                    <label><?php esc_attr_e('Enable OTP Based Voting','it_epoll');?> <span class="it_epolladmin_pro_badge"><?php esc_attr_e('Pro','it_epoll');?></span></label>       
                                    <select name="it_epoll_settings_uniqe_vote" class="widefat" disabled>
                                        <option value="0"<?php if(get_option('it_epoll_settings_uniqe_vote') == 0) echo esc_attr(' selected','it_epoll');?>><?php  esc_attr_e('No','it_epoll');?></option>
                                        <option value="1"<?php if(get_option('it_epoll_settings_uniqe_vote') == 1) echo esc_attr(' selected','it_epoll');?>><?php  esc_attr_e('Yes','it_epoll');?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>   
                                    <label><?php esc_attr_e('Enable Social Login','it_epoll');?> <span class="it_epolladmin_pro_badge"><?php esc_attr_e('Pro','it_epoll');?></span></label>       
                                    <select name="it_epoll_settings_uniqe_vote" class="widefat" disabled>
                                        <option value="0"<?php if(get_option('it_epoll_settings_uniqe_vote') == 0) echo esc_attr(' selected','it_epoll');?>><?php  esc_attr_e('No','it_epoll');?></option>
                                        <option value="1"<?php if(get_option('it_epoll_settings_uniqe_vote') == 1) echo esc_attr(' selected','it_epoll');?>><?php  esc_attr_e('Yes','it_epoll');?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>   
                                    <label><?php esc_attr_e('Enable Voter Login','it_epoll');?> <span class="it_epolladmin_pro_badge"><?php esc_attr_e('Pro','it_epoll');?></span></label>       
                                    <select name="it_epoll_settings_uniqe_vote" class="widefat" disabled>
                                        <option value="0"<?php if(get_option('it_epoll_settings_uniqe_vote') == 0) echo esc_attr(' selected','it_epoll');?>><?php  esc_attr_e('No','it_epoll');?></option>
                                        <option value="1"<?php if(get_option('it_epoll_settings_uniqe_vote') == 1) echo esc_attr(' selected','it_epoll');?>><?php  esc_attr_e('Yes','it_epoll');?></option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>   
                    <?php do_action('it_epoll_options_advanced_fields');?>
                </div>
                <div class="epoll_admin_options-tabs-content" id="notifications">
                    <table class="widefat border-table">
                        <thead>
                            <tr>
                                <th>
                                    <?php esc_attr_e('Vote Submission  / Thank You Email','it_epoll');?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr> 
                                <td>     
                                    <label><?php esc_attr_e('Email Subject','it_epoll');?> <span class="it_epolladmin_pro_badge"><?php  esc_attr_e('Pro','it_epoll');?></span></label>     
                                    <input type="text" class="widefat" name="it_epoll_settings_thanks_email_subject" disabled/>
                                </td>
                            </tr> 
                            <tr> 
                                <td>     
                                    <label><?php esc_attr_e('Email Content','it_epoll');?> <span class="it_epolladmin_pro_badge"><?php  esc_attr_e('Pro','it_epoll');?></span></label>     
                                    <textarea class="widefat" name="it_epoll_settings_thanks_email" disabled></textarea>
                                </td>
                            </tr> 
                        </tbody>
                    </table>

                    <table class="widefat border-table">
                        <thead>
                            <tr>
                                <th>
                                    <?php esc_attr_e('OTP Based Voting Email','it_epoll');?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr> 
                                <td>     
                                    <label><?php esc_attr_e('OTP Email Subject','it_epoll');?> <span class="it_epolladmin_pro_badge"><?php  esc_attr_e('Pro','it_epoll');?></span></label>     
                                    <input type="text" class="widefat" name="it_epoll_settings_otp_email_subject" disabled/>
                                </td>
                            </tr> 
                            <tr> 
                                <td>     
                                    <label><?php esc_attr_e('OTP Email Content','it_epoll');?> <span class="it_epolladmin_pro_badge"><?php  esc_attr_e('Pro','it_epoll');?></span></label>     
                                    <textarea class="widefat" name="it_epoll_settings_otp_email" disabled></textarea>
                                </td>
                            </tr> 
                        </tbody>
                    </table>


                    <table class="widefat border-table">
                        <thead>
                            <tr>
                                <th>
                                    <?php esc_attr_e('OTP / WhatsApp OTP Based Voting SMS Text','it_epoll');?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr> 
                                <td>     
                                    <label><?php esc_attr_e('OTP SMS Text','it_epoll');?> <span class="it_epolladmin_pro_badge"><?php  esc_attr_e('Pro','it_epoll');?></span></label>     
                                    
                                    <textarea class="widefat" name="it_epoll_settings_sms_text" disabled></textarea>
                                </td>
                            </tr> 
                        </tbody>
                    </table>
                    
                    <?php do_action('it_epoll_options_notification_fields');?>
                </div>
                <div class="epoll_admin_options-tabs-content" id="translate">
                   
                    <?php do_action('it_epoll_options_translate_fields');?>
                </div>
                <?php do_action('it_epoll_options_extra_tabs_content');?>
            </div>
        </div>
    </form>
    <p class="epoll_admin_options-footer"><?php echo esc_attr('ePoll Version '.IT_EPOLL_VERSION,'it_epoll');?></p>
</div>
<script type="text/javascript">
    jQuery.noConflict();
    jQuery(document).ready(function($) {

        var tabsUi = jQuery('.epoll_admin_options-tabs');
      
        tabsUi.find('> ul li a').click(function() {
            var hash = jQuery(this).attr('href');
            
            jQuery('.epoll_admin_options-tabs ul li').each(function(){
                jQuery(this).find('a').removeClass('current');
            });
            jQuery(this).addClass('current');
        });
        jQuery(window).bind('hashchange', function() {
            if (location.hash !== '') {
                var tabNum = location.hash;

                jQuery('.epoll_admin_options_tab-content .epoll_admin_options-tabs-content').each(function(){
                    jQuery(this).removeClass('current');
                });

                jQuery('.epoll_admin_options_tab-content '+tabNum).addClass('current');
            } else {
                jQuery('.epoll_admin_options_tab-content #general').addClass('current');
            }
        });
    });

    jQuery('.epoll_admin_options_tab-content .epoll_admin_options-tabs-content').each(function(){
        jQuery(this).find('.it_epoll_has_oncheck_div').on('change',function(){
            //alert("dsdsd");
           // console.log(jQuery(this).parent().parent().find('.it_epoll_oncheck_div'));
            if(jQuery(this).is(":checked")){
                jQuery(this).parent().parent().find('.it_epoll_oncheck_div').addClass('it_epoll_oncheck_div_show');
            }else{
                jQuery(this).parent().parent().find('.it_epoll_oncheck_div').removeClass('it_epoll_oncheck_div_show');
            }
           
        });
    });
    </script>