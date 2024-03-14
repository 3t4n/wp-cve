<?php
/*if(isset($_POST['Save_Changes'])){
    if(isset($_POST['click5_history_log_critical_error'])){
        if(esc_attr(get_option("click5_history_log_critical_error")) != "1"){
            update_option("click5_history_log_critical_error",true);
        }
    }else{
        if(esc_attr(get_option("click5_history_log_critical_error")) == "1"){
            update_option("click5_history_log_critical_error",false);
        }
    }

    if(isset($_POST['click5_history_log_technical_issue'])){
        if(esc_attr(get_option("click5_history_log_technical_issue")) != "1"){
            update_option("click5_history_log_technical_issue",true);
        }
    }else{
        if(esc_attr(get_option("click5_history_log_technical_issue")) == "1"){
            update_option("click5_history_log_technical_issue",false);
        }
    }
}*/



    $alert_email_data = get_option("click5_history_log_alert_email");
    /*if(!empty($alert_email_data))
        $alert_email_data = (array)json_decode($alert_email_data);*/
    $critical_error_enabled = "";
    $technical_issue_enabled = "";
    $error_404_enabled = "";
    if(esc_attr(get_option("click5_history_log_critical_error")) == "1"){
        $critical_error_enabled = "checked";
    }

    if(esc_attr(get_option("click5_history_log_technical_issue")) == "1"){
        $technical_issue_enabled = "checked";
    }

    if(esc_attr(get_option("click5_history_log_404")) == "1"){
        $error_404_enabled = "checked";
    }

    function c5_wpb_admin_notice_warn($emails) {
        
        if(empty($emails)){
            echo '<div class="notice inline notice-success is-dismissible" style="">
            <p>Alerts settings has been saved.</p>
            </div>'; 
        }
        else{
            echo '<div class="notice inline notice-error is-dismissible" style="">
            <p>The email address could not be saved because it is incorrect.</p>
            </div>'; 
        }
    }
        
?>
<div id="poststuff">
      <div id="post-body-content" >
      <form action="" method="post">
          <?php wp_nonce_field( 'click5_history_log_nonce','click5_history_log_nonce' ); ?>
          <div class="postbox" style="margin-right: 20px!important">
            <h3 class="hndle"><span><?php _e('Email Alerts Settings', 'sitemap-by-click5'); ?></span></h3>
            <div class="inside">
            <div class="wrap" style="margin-left: 15px;">
                <?php if(isset($_SESSION['click5_history_log_emails_invalid'])){
                        c5_wpb_admin_notice_warn($_SESSION['click5_history_log_emails_invalid']);
                        $_SESSION['click5_history_log_emails_invalid'] = NULL;
                        unset($_SESSION['click5_history_log_emails_invalid']);
                    } ?>
              </div>
              <p><strong style="margin-left: 15px">Notify me about:</strong></p>
              <table class="form-table">
                <tbody>
                  <tr>
                    <div class="alerts_input_box">
                        <div class="alert_input_content">
                            <input type="checkbox" id="click5_history_log_critical_error" name="click5_history_log_critical_error" value="1" style="margin-left: 15px" <?php echo $critical_error_enabled; ?>/>
                            <label for="click5_history_log_critical_error">Critical Errors (Site Health)</label>
                        </div>

                        <div class="alert_input_content">
                            <input type="checkbox" id="click5_history_log_technical_issue" name="click5_history_log_technical_issue" value="1" style="margin-left: 15px" <?php echo $technical_issue_enabled; ?>/>
                            <label for="click5_history_log_technical_issue">Your Site is Experiencing a Technical Issue (WordPress Error)</label>
                        </div>

                        <div class="alert_input_content">
                            <input type="checkbox" id="click5_history_log_404" name="click5_history_log_404" value="1" style="margin-left: 15px" <?php echo $error_404_enabled; ?>/>
                            <label for="click5_history_log_404">404 Error Page</label>
                        </div>
                    </div>
                  </tr>
                </tbody>
              </table>

              <p><strong style="margin-left: 15px">Email format:</strong></p>
              <table class="form-table">
                <tbody>
                  <tr>
                    <div class="alerts_input_box">
                    <div class="alert_input_content">
                            <input type="radio" id="click5_history_log_email_template_plain" name="click5_history_log_email_template" value="plain" style="margin-left: 15px" <?php echo esc_attr(get_option("click5_history_log_email_template")) == "plain" ? "checked" : ""; ?>/>
                            <label for="click5_history_log_email_template_plain">Plain Text</label>
                        </div>

                        <div class="alert_input_content">
                            <input type="radio" id="click5_history_log_email_template_html" name="click5_history_log_email_template" value="html" style="margin-left: 15px" <?php echo esc_attr(get_option("click5_history_log_email_template")) == "html" ? "checked" : ""; ?>/>
                            <label for="click5_history_log_email_template_html">HTML</label>
                        </div>
                    </div>
                  </tr>
                </tbody>
              </table>
              <div class="alert_email_box">
                <div class="alert_email_container">
                    <span>Send alerts to: </span>

                    <div class="alert_email_inputs">
                    <?php
                        if(empty($alert_email_data)):
                            $userData = wp_get_current_user();
                            $userEmail = "";
                            if(isset($userData->ID)){
                                $userEmail = $userData->data->user_email;
                            }

                            if(empty($userEmail))
                                $userEmail = esc_attr(get_option("admin_email"));
                        ?>
                        <input type="text" name="click5_history_log_alert_email" placeholder="<?php echo $userEmail ?>"/>
                        <?php elseif(!empty($alert_email_data)): ?>
                         <input type="text" name="click5_history_log_alert_email" value="<?php echo $alert_email_data ?>"/>
                         <?php endif; ?>
                         <p><small><em>Enter additional email addresses separated by commas.</em></small></p>
                        <?php
                        /*if(empty($alert_email_data)):
                            $userData = wp_get_current_user();
                            $userEmail = "";
                            if(isset($userData->ID)){
                                $userEmail = $userData->data->user_email;
                            }*/
                        ?>
                        <!--<div class="alert_email_input_box">
                            <input type="text" class="click5_history_log_alert_email" name="click5_history_log_alert_email[]" value = "<?php //echo $userEmail; ?>" onchange="validateEmail()"/>
                            <span class="add_email_input" onclick="addEmailInput()">+</span>
                            <span class="user_email_check"></span>
                        </div>

                        <?php //elseif(!empty($alert_email_data)):
                            //foreach($alert_email_data as $key => $value){ ?>
                            <div class="alert_email_input_box">
                                <input type="text" class="click5_history_log_alert_email" name="click5_history_log_alert_email[]" value="<?php //echo esc_attr($value) ?>" onchange="validateEmail()"/>
                                <span class="add_email_input" onclick="addEmailInput()">+</span>
                                <span class="user_email_check"></span>
                            </div>
                            <?php //} endif; ?>-->

                    </div>    
                </div>
              </div>
              <div style="clear:both"></div>
              <input value="Save Changes" style="margin-left: 15px" class="button button-primary" type="submit" name="save_alerts"/>
            </div>
          </div>
        </form>
      </div>
    </div>