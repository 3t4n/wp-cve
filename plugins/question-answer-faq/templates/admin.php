<div class="wrap mideal-css">
<h1>Mideal Faq</h1>
<h2 class="nav-tab-wrapper">
    <a class="nav-tab" id="setting-tab" href="#setting"><?php _e( "Settings", "question-answer-faq" );?></a>
    <a class="nav-tab" id="fronted-tab" href="#fronted"><?php _e( "Display", "question-answer-faq" );?></a>
</h2>
<form method="post" action="options.php">
    <?php settings_fields( 'mideal-faq-settings-group' ); ?>
    <?php do_settings_sections( 'mideal-faq-settings-group' ); ?>
<div id="setting" class="midealfaqtab">
    <table class="form-table">
        <tr valign="top">
            <th scope="row">
                <?php _e( "The E-mail address for notifications about new question", "question-answer-faq" );?>
            </th>
            <td>
                <input type="text" name="mideal_faq_setting_email" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_email',get_option( 'admin_email' )) ); ?>" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e( "Second E-mail address for notifications about new question", "question-answer-faq" );?>
            </th>
            <td>
                <input type="text" name="mideal_faq_setting_email2" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_email2') ); ?>" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e( "Name of answer", "question-answer-faq" );?> 
            </th>
            <td>
                <input type="text" name="mideal_faq_setting_answer_name" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_answer_name', __("Answer", "question-answer-faq")) ); ?>" />
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                Google reCAPTCHA
            </th>
            <td>
                <input type="checkbox" name="mideal_faq_setting_recaptcha" data-hide="input-google-recaptcha" class="qa-checkbox-show-el" value="1" <?php echo checked( 1, get_option( 'mideal_faq_setting_recaptcha' ), false ) ;?> />
            </td>
        </tr>
        <tr valign="top" class="input-google-recaptcha">
            <th>
                
            </th>
            <td>
                <a target="_blank" href="https://www.google.com/recaptcha/admin" rel="nofollow"><?php _e( "Add your site in google reCaptcha, and write your key and secret key", "question-answer-faq" );?></a>
            </td>
        </tr>
        <tr valign="top" class="input-google-recaptcha">
            <th scope="row">
                <?php _e( "Google recaptcha key", "question-answer-faq" );?>
            </th>
            <td>
                <input type="text" name="mideal_faq_setting_recaptcha_key" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_recaptcha_key') ); ?>" />
            </td>
        </tr>
        <tr valign="top" class="input-google-recaptcha">
            <th scope="row">
                <?php _e( "Google recaptcha secret key", "question-answer-faq" );?>
            </th>
            <td>
                <input type="text" name="mideal_faq_setting_recaptcha_key_secret" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_recaptcha_key_secret') ); ?>" />
            </td>
        </tr>

    </table>

</div>
<div id="fronted" class="midealfaqtab">
    <table class="form-table">
         <tr valign="top">
            <th scope="row">
                <?php _e( "Avatar of answer", "question-answer-faq" );?> 
           </th>
            <td>
                <img style="display: block;width: 80px; height: 80px;border-radius: 50%;" src="<?php if(get_option("mideal_faq_setting_answer_image")){echo get_option("mideal_faq_setting_answer_image");}else{echo MQA_PLUGIN_URL."/img/avatar-default.png";}?>"><br>
                <input type="text" name="mideal_faq_setting_answer_image" value='<?php if(get_option("mideal_faq_setting_answer_image")){echo get_option("mideal_faq_setting_answer_image");}else{echo MQA_PLUGIN_URL."/img/avatar-default.png";}?>' />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e( "Small size avatar", "question-answer-faq" );?> 
            </th>
            <td>
                <input type="checkbox" name="mideal_faq_setting_avatar_smallsize" value="1" <?php echo checked( 1, get_option( 'mideal_faq_setting_avatar_smallsize' ), false ) ;?> />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e( "Don`t show a label in form", "question-answer-faq" );?> 
            </th>
            <td>
                <input type="checkbox" name="mideal_faq_setting_dont_show_label" value="1" <?php echo checked( 1, get_option( 'mideal_faq_setting_dont_show_label' ), false ) ;?> />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e( "Don`t connect bootstrap css (just style for button and form)", "question-answer-faq" );?> 
            </th>
            <td>
                <input type="checkbox" name="mideal_faq_setting_dont_connect_bootstrap" value="1" <?php echo checked( 1, get_option( 'mideal_faq_setting_dont_connect_bootstrap' ), false ) ;?> />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e( "Question pages show at most", "question-answer-faq" );?> 
            </th>
            <td>
                <input type="text" name="mideal_faq_setting_pagination_number" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_pagination_number', 5) ); ?>" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e( "Color question background", "question-answer-faq" );?> 
            </th>
            <td>
                <div class="input-group colorpicker-component">
                    <input type="text" name="mideal_faq_setting_question_background" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_question_background',"#eef1f5") ); ?>" class="form-control" />
                    <span class="input-group-addon"><i></i></span>
                </div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e( "Color question text", "question-answer-faq" );?> 
            </th>
            <td>
                <div class="input-group colorpicker-component">
                    <input type="text" name="mideal_faq_setting_question_color_text" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_question_color_text',"#444") ); ?>" class="form-control" />
                    <span class="input-group-addon"><i></i></span>
                </div>
            </td>
        </tr>



        <tr valign="top">
            <th scope="row">
                <?php _e( "Color answer background", "question-answer-faq" );?> 
            </th>
            <td>
                <div class="input-group colorpicker-component">
                    <input type="text" name="mideal_faq_setting_answer_background" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_answer_background',"#3cb868") ); ?>" class="form-control" />
                    <span class="input-group-addon"><i></i></span>
                </div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e( "Color answer text", "question-answer-faq" );?> 
            </th>
            <td>
                <div class="input-group colorpicker-component">
                    <input type="text" name="mideal_faq_setting_answer_color_text" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_answer_color_text',"#FFFFFF") ); ?>" class="form-control" />
                    <span class="input-group-addon"><i></i></span>
                </div>
            </td>
        </tr>



        <tr valign="top">
            <th scope="row">
                <?php _e( "Color button background", "question-answer-faq" );?> 
            </th>
            <td>
                <div class="input-group colorpicker-component">
                    <input type="text" name="mideal_faq_setting_button_background" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_button_background',"#3cb868") ); ?>" class="form-control" />
                    <span class="input-group-addon"><i></i></span>
                </div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e( "Color button text", "question-answer-faq" );?> 
            </th>
            <td>
                <div class="input-group colorpicker-component">
                    <input type="text" name="mideal_faq_setting_button_color_text" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_button_color_text',"#FFFFFF") ); ?>" class="form-control" />
                    <span class="input-group-addon"><i></i></span>
                </div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e( "Big button in form", "question-answer-faq" );?> 
            </th>
            <td>
                <input type="checkbox" name="mideal_faq_setting_button_big_size" value="1" <?php echo checked( 1, get_option( 'mideal_faq_setting_button_big_size' ), false ) ;?> />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <?php _e( "Color of pagination", "question-answer-faq" );?> 
            </th>
            <td>
                <div class="input-group colorpicker-component">
                    <input type="text" name="mideal_faq_setting_pagination_color" value="<?php echo esc_attr( get_option( 'mideal_faq_setting_pagination_color',"#3cb868") ); ?>" class="form-control" />
                    <span class="input-group-addon"><i></i></span>
                </div>
            </td>
        </tr>
          </table>
</div>
<?php submit_button(); ?>
</form>

</div>