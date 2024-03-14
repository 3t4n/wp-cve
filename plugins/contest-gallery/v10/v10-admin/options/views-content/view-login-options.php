<?php

echo <<<HEREDOC
<div class='cg_view_container'>
HEREDOC;

$cgNoteLoginFormNotVisible = <<<HEREDOC
<p class="cg_view_options_rows_container_title">
        <strong><span class="cg_color_red">NOTE:</span> Login form is not visible If user is already logged in.</strong><br>
Use another browser where you are not logged in to test login form.<br>
</p>
HEREDOC;

if($cgBeforeSinceV14ExplanationRequired){
    echo <<<HEREDOC
    <div class='cg_view_options_rows_container'>
        <p class="cg_view_options_rows_container_title ">
                <strong>NOTE:</strong> For galleries created or copied in plugin version 14 or higher "Login options" are general<br>and valid for all galleries  
                created or copied in plugin version 14 or higher.<br>
        </p>
        $cgNoteLoginFormNotVisible
    </div>
HEREDOC;
}else{
    if(intval($galleryDbVersion)>=14){// only if higher then 14 then this explanation required!
        echo <<<HEREDOC
        <div class='cg_view_options_rows_container' id="cgGoogleOptionsRowsContainer">
            <p class="cg_view_options_rows_container_title ">
                    <strong>NOTE:</strong> Login options are general and valid for all galleries.<br>
            </p>
            $cgNoteLoginFormNotVisible
        </div>
HEREDOC;
    }
}

echo <<<HEREDOC
    <div class='cg_view_options_rows_container cg_go_to_target'  data-cg-go-to-target="LoginFormShortcodeVisualConfiguration">
        <p class='cg_view_options_rows_container_title'>Login form shortcode <b>visual</b> configuration</p>
        <div class="cg_view_options_row">
            <div class="cg_view_option cg_view_option_100_percent" id="BorderRadiusContainer">
                <div class="cg_view_option_title">
                    <p style="margin-right: -30px;">Round borders form container and field inputs</p>
                </div>
                <div class="cg_view_option_checkbox cg_view_option_checked">
                    <input type="checkbox" name="BorderRadiusLogin" id="BorderRadiusLogin" $BorderRadiusLogin>
                </div>
            </div>
    </div>
     <div class='cg_view_options_row '  >
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none'>
                    <div class='cg_view_option_title'>
                        <p>Background, fields and font color style</p>
                    </div>
                    <div class='cg_view_option_radio_multiple'>
                        <div class='cg_view_option_radio_multiple_container'>
                            <div class='cg_view_option_radio_multiple_title'>
                                Bright style
                            </div>
                            <div class='cg_view_option_radio_multiple_input'>
                                <input type="radio" name="FeControlsStyleWhiteLogin" class="FeControlsStyleWhiteLogin cg_view_option_radio_multiple_input_field" $FeControlsStyleWhiteLogin value="white"/>
                            </div>
                        </div>
                        <div class='cg_view_option_radio_multiple_container'>
                            <div class='cg_view_option_radio_multiple_title'>
                                Dark style
                            </div>
                            <div class='cg_view_option_radio_multiple_input'>
                                <input type="radio" name="FeControlsStyleBlackLogin" class="FeControlsStyleBlackLogin cg_view_option_radio_multiple_input_field" $FeControlsStyleBlackLogin value="black">
                            </div>
                        </div>
                </div>
            </div>
</div>
    </div>
HEREDOC;


echo <<<HEREDOC
    <div class='cg_view_options_rows_container cg_go_to_target'  data-cg-go-to-target="LoginFormShortcodeForwardingConfirmationConfiguration">
        <p class='cg_view_options_rows_container_title'>Login options</p>         
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_view_option_full_width $cgProFalse" id="wp-TextBeforeLoginForm-wrap-Container">
                <div class="cg_view_option_title">
                    <p>Text before login form before logged in<br><span class="cg_view_option_title_note">After user is logged in text is not visible anymore</span></p>
                </div>
                <div class="cg_view_option_html">
                    <textarea class='cg-wp-editor-template' id='TextBeforeLoginForm'  name='TextBeforeLoginForm'>$TextBeforeLoginForm</textarea>
                </div>
            </div>
        </div>
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_view_option_100_percent cg_border_top_none $cgProFalse" id="ForwardAfterLoginUrlCheckContainer">
                <div class="cg_view_option_title">
                    <p>Forward to another page after login</p>
                </div>
                <div class="cg_view_option_radio">
                    <input id='ForwardAfterLoginUrlCheck' type='radio' name='ForwardAfterLoginUrlCheck' $ForwardAfterLoginUrlCheck >
                </div>
            </div>
        </div>
HEREDOC;

echo <<<HEREDOC
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse" id="ForwardAfterLoginUrlContainer">
                <div class="cg_view_option_title">
                    <p>Forward to URL</p>
                </div>
                <div class="cg_view_option_input">
                    <input id="ForwardAfterLoginUrl" type="text" name="ForwardAfterLoginUrl" maxlength="999" value="$ForwardAfterLoginUrl"/>
                </div>
            </div>
        </div>
HEREDOC;


echo <<<HEREDOC
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_view_option_100_percent cg_border_top_none $cgProFalse" id="ForwardAfterLoginTextCheckContainer">
                <div class="cg_view_option_title">
                    <p>Confirmation text on same site after login</p>
                </div>
                <div class="cg_view_option_radio">
                    <input id='ForwardAfterLoginTextCheck' type='radio' class='$cgProFalse' name='ForwardAfterLoginTextCheck' $ForwardAfterLoginTextCheck >
                </div>
            </div>
        </div>
HEREDOC;
/*
$cgLogoutLinkNote = '';
$cgLogoutLinkClass = '';

if(intval($galleryDbVersion)<14){
    $cgLogoutLinkNote = '<span class="cg_color_red">NOTE:</span> This option is only available for galleries created or copied after plugin version  14.<br>';
    $cgLogoutLinkClass = 'cg_disabled';
}*/

$beforeSinceV14Explanation = '';
$beforeSinceV14Disabled = '';

if(intval($galleryDbVersion)<14){
    $beforeSinceV14Disabled = 'cg_disabled';
    $beforeSinceV14Explanation = '<span class="cg_color_red">NOTE: </span> Available only for galleries created or copied in plugin version 14 or higher<br>';
}

echo <<<HEREDOC
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse" id="wp-ForwardAfterLoginText-wrap-Container">
                <div class="cg_view_option_title">
                    <p>Confirmation text after login</p>
                </div>
                <div class="cg_view_option_html">
                    <textarea class='cg-wp-editor-template' id='ForwardAfterLoginText'  name='ForwardAfterLoginText'>$ForwardAfterLoginText</textarea>
                </div>
            </div>
        </div>
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse" id="wp-PermanentTextWhenLoggedIn-wrap-Container">
                <div class="cg_view_option_title">
                    <p>Permanent text when logged in</p>
                </div>
                <div class="cg_view_option_html">
                    <textarea class='cg-wp-editor-template' id='PermanentTextWhenLoggedIn'  name='PermanentTextWhenLoggedIn'>$PermanentTextWhenLoggedIn</textarea>
                </div>
            </div>
        </div>
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse $beforeSinceV14Disabled">
                <div class="cg_view_option_title">
                    <p>Logout URL<br>works only for "Contest Gallery User since v14" roles<br>$beforeSinceV14Explanation<span class="cg_view_option_title_note">URL where will be redirected when logged in user click on logout<br>If empty then standard WordPress logout URL will be used</span></p>
                </div>
                <div class="cg_view_option_input">
                    <input type="text" name="LogoutLink" maxlength="999" value="$LogoutLink"/>
                </div>
            </div>
        </div>
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse $beforeSinceV14Disabled">
                <div class="cg_view_option_title">
                    <p>Back to gallery URL<br>works only for "Contest Gallery User since v14" roles<br>$beforeSinceV14Explanation<span class="cg_view_option_title_note">URL where where you user can be redirected if "Back to gallery" is clicked in "Edit profile" area<br>If empty "Back to gallery" does not appear in "Edit profile"</span></p>
                </div>
                <div class="cg_view_option_input">
                    <input type="text" name="BackToGalleryLink" maxlength="999" value="$BackToGalleryLink"/>
                </div>
            </div>
        </div>
HEREDOC;


echo "</div>";

/*
$cgBeforeV14NotWorkNoteRequiredLostPasswordOptionsText = '';
if($cgBeforeV14NotWorkNoteRequired && empty($cgProFalse)){
    $cgBeforeV14NotWorkNoteRequiredLostPasswordOptionsText =  <<<HEREDOC
<br><span class='cg_view_options_rows_container_title_note'><span class="cg_color_red">NOTE:</span> Lost Password e-mail options are only available for galleries
 created or copied after plugin version 14</span>
HEREDOC;
}*/

echo <<<HEREDOC
    <div class='cg_view_options_rows_container'>
        <p class='cg_view_options_rows_container_title'>Lost password e-mail options
            <br><span class='cg_view_options_rows_container_title_note'><span class="cg_color_red">NOTE:</span> relating testing - e-mail where is send to should not contain $cgYourDomainName.<br>Many servers can not send to own domain.</span>
        </p>
HEREDOC;


if(strpos($mailExceptions,'Lost password e-mail') !== false){
    echo "<div style=\"width:330px;margin: -8px auto 15px;\"><a href=\"?page=".cg_get_version()."/index.php&amp;corrections_and_improvements=true&amp;option_id=$galeryNR\" class='cg_load_backend_link'><input class=\"cg_backend_button cg_backend_button_back cg_backend_button_warning\" type=\"button\" value=\"There were mail exceptions for this mailing type\" style=\"width:330px;\"></a>
</div>";
}else{
    echo "<div style=\"width:280px;margin: -8px auto 15px;\"><a href=\"?page=".cg_get_version()."/index.php&amp;corrections_and_improvements=true&amp;option_id=$galeryNR\" class='cg_load_backend_link'><input class=\"cg_backend_button cg_backend_button_back cg_backend_button_success\" type=\"button\" value=\"No mail exceptions for this mailing type\" style=\"width:280px;\"></a>
</div>";
}

echo <<<HEREDOC
        <div class='cg_view_options_row'>
                <div class="cg_view_option  cg_border_border_top_left_radius_8_px cg_border_border_top_right_radius_8_px cg_view_option_100_percent $cgProFalse" id="LostPasswordMailActiveContainer">
                    <div class="cg_view_option_title">
                        <p>Lost password reset option in login form<br><span class="cg_view_option_title_note"><span class="cg_color_red">NOTE:</span> relating testing - e-mail where is send to should not contain $cgYourDomainName.<br>Many servers can not send to own domain.</span></span></p>
                    </div>
                    <div class="cg_view_option_checkbox">
                        <input type="checkbox" name="LostPasswordMailActive" id="LostPasswordMailActive" value="1" $LostPasswordMailActive >
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse" id="LostPasswordMailAddressorContainer">
                <div class="cg_view_option_title">
                    <p>Header (like your company name or something like that, not an e-mail)</p>
                </div>
                <div class="cg_view_option_input">
                    <input type="text" name="LostPasswordMailAddressor" value="$LostPasswordMailAddressor"  maxlength="200" >
                </div>
            </div>
       </div>
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse" id="LostPasswordMailReplyContainer">
                <div class="cg_view_option_title">
                    <p>Reply e-mail (address From)<br><span class="cg_view_option_title_note">Should not be empty</span></p>
                </div>
                <div class="cg_view_option_input">
                    <input type="text" name="LostPasswordMailReply" value="$LostPasswordMailReply"  maxlength="200" >
                </div>
            </div>
       </div>
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse" id="LostPasswordMailSubjectContainer">
                <div class="cg_view_option_title">
                    <p>Subject</p>
                </div>
                <div class="cg_view_option_input">
                    <input type="text" name="LostPasswordMailSubject" class="cg-long-input" value="$LostPasswordMailSubject"  maxlength="200">
                </div>
            </div>
       </div>
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse" id="wp-LostPasswordMailConfirmation-wrap-Container">
                <div class="cg_view_option_title cg_copyable">
                    <p>Mail content<br><span class="cg_view_option_title_note">Put this variable in the mail content editor: <span style="font-weight:bold;">\$resetpasswordurl$</span><br>(Reset password link will appear in the e-mail<br>It will be the same page where your login shortcode is inserted)
<br><a href="https://www.contest-gallery.com/documentation/#cgDisplayConfirmationURL" target="_blank" class="cg-documentation-link">Documentation: How to make the link clickable in e-mail</a></span></p>
                </div>
                <div class="cg_view_option_html">
                    <textarea class='cg-wp-editor-template' id='LostPasswordMailConfirmation'  name='LostPasswordMailConfirmation'>$LostPasswordMailConfirmation</textarea
                </div>
            </div>
       </div>
    </div>
    </div>
HEREDOC;


echo <<<HEREDOC
</div>
HEREDOC;


