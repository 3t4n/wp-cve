<?php
echo <<<HEREDOC
<div class='cg_view_container'>
HEREDOC;

$cgInformAdminActivationURLhide = '';

if(intval($galleryDbVersion)<21){
    $cgInformAdminActivationURLhide = 'cg_hide';
}


if(strpos($mailExceptions,'E-mail to administrator after upload') !== false OR strpos($mailExceptions,'E-mail to administrator after contact') !== false){
    $mailExceptionAdminMail = "<div style=\"width:330px;margin: -8px auto 15px;\"><a href=\"?page=".cg_get_version()."/index.php&amp;corrections_and_improvements=true&amp;option_id=$galeryNR\" class='cg_load_backend_link'><input class=\"cg_backend_button cg_backend_button_back cg_backend_button_warning\" type=\"button\" value=\"There were mail exceptions for this mailing type\" style=\"width:330px;\"></a>
</div>";
}else{
    $mailExceptionAdminMail = "<div style=\"width:280px;margin: -8px auto 15px;\"><a href=\"?page=".cg_get_version()."/index.php&amp;corrections_and_improvements=true&amp;option_id=$galeryNR\" class='cg_load_backend_link'><input class=\"cg_backend_button cg_backend_button_back cg_backend_button_success\" type=\"button\" value=\"No mail exceptions for this mailing type\" style=\"width:280px;\"></a>
</div>";
}

$informAdminFrom = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLemailAdmin->Admin);
$informAdminMail = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLemailAdmin->AdminMail);
$informAdminReply = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLemailAdmin->Reply);
$informAdminCC = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLemailAdmin->CC);
$informAdminBCC = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLemailAdmin->BCC);
$informAdminHeader = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLemailAdmin->Header);

echo <<<HEREDOC
    <div class='cg_view_options_rows_container'>
        $mailExceptionAdminMail
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_border_border_top_left_radius_8_px cg_border_border_top_right_radius_8_px cg_view_option_100_percent $cgProFalse" id="InformAdminContainer">
                <div class="cg_view_option_title">
                    <p>Inform admin when new entry in frontend</p>
                </div>
                <div class="cg_view_option_checkbox">
                    <input id='InformAdmin' type='checkbox' name='InformAdmin' $InformAdmin >
                </div>
            </div>
        </div>
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_inform_admin cg_view_option_full_width cg_border_top_none $cgProFalse' id="cgInformAdminHeaderContainer" >
                    <div class='cg_view_option_title'>
                        <p>Header<br><span class="cg_view_option_title_note">Like your company name or something like that, not an e-mail</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="from" id="cgInformAdminFrom" value="$informAdminFrom"  maxlength="200" >
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_inform_admin  cg_view_option_full_width cg_border_top_none $cgProFalse' id="cgInformAdminMailContainer" >
                    <div class='cg_view_option_title'>
                        <p>Admin e-mail (To)<br><span class="cg_view_option_title_note">
                            <span class="cg_color_red">NOTE:</span> relating testing - e-mail where is send to should not contain $cgYourDomainName.<br>Many servers can not send to own domain.</span>
                            </span>
                        </p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="AdminMail" id="cgInformAdminAdminMail" value="$informAdminMail"  maxlength="200" >
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_inform_admin  cg_view_option_full_width cg_border_top_none $cgProFalse' id="cgInformAdminReplyContainer" >
                    <div class='cg_view_option_title'>
                        <p>Reply e-mail (address From)<br><span class="cg_view_option_title_note">Should not be empty<br>Should not be the same as "Admin e-mail"</span>
                        </p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="reply" id="cgInformAdminReply" value="$informAdminReply"  maxlength="200" >
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_inform_admin  cg_view_option_full_width cg_border_top_none $cgProFalse' id="cgInformAdminCContainer" >
                    <div class='cg_view_option_title'>
                        <p>CC e-mail<br><span class="cg_view_option_title_note">Should not be the same as "Reply e-mail"<br>Sending to multiple recipients example (mail1@example.com; mail2@example.com; mail3@example.com</span>
                        </p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="cc" id="cgInformAdminCC" value="$informAdminCC"  maxlength="200" >
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_inform_admin  cg_view_option_full_width cg_border_top_none $cgProFalse' id="cgInformAdminBCContainer" >
                    <div class='cg_view_option_title'>
                        <p>BCC e-mail<br><span class="cg_view_option_title_note">Should not be the same as "Reply e-mail"<br>Sending to multiple recipients example (mail1@example.com; mail2@example.com; mail3@example.com</span>
                        </p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="bcc" id="cgInformAdminBCC" value="$informAdminBCC"  maxlength="200" >
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_inform_admin  cg_view_option_full_width cg_border_top_none $cgProFalse' id="cgInformAdminHeaderContainer" >
                    <div class='cg_view_option_title'>
                        <p>Subject</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="header" id="cgInformAdminHeader" value="$informAdminHeader"  maxlength="200" >
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_inform_admin  cg_view_option_full_width cg_border_top_none $cgProFalse'  id="wp-InformAdminText-wrap-Container" >
                    <div class='cg_view_option_title cg_copyable'>
                        <p>Mail content<br><span class="cg_view_option_title_note">Use <span style="font-weight:bold;">\$info$</span> in the editor if you like to attach user info</span></p>
                    </div>
                    <div class='cg_view_option_html'>
                        <textarea class='cg-wp-editor-template' id='InformAdminText'  name='InformAdminText'>$ContentAdminMail</textarea>
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row $cgInformAdminActivationURLhide'>
            <div class="cg_view_option cg_border_top_none cg_view_option_100_percent $cgProFalse cg_inform_admin" id="cgInformAdminAllowActivateDeactivateContainer">
                <div class="cg_view_option_title">
                    <p>Allow to activate deactivate new frontend entry direct from admin email without being logged in
                        <br><span class="cg_view_option_title_note">
                        <span style="font-weight:bold;">Note: </span> you have to place the cg_entry_on_off shortcode at the configured page URL, see next option
                        </span>
                    </p>
                </div>
                <div class="cg_view_option_checkbox">
                    <input id='InformAdminAllowActivateDeactivate' type='checkbox' name='InformAdminAllowActivateDeactivate' $InformAdminAllowActivateDeactivate >
                </div>
            </div>
        </div>
        <div class='cg_view_options_row $cgInformAdminActivationURLhide'>
                <div class='cg_view_option cg_inform_admin  cg_view_option_full_width cg_border_top_none $cgProFalse $InformAdminActivationURLDisabled' id="cgInformAdminActivationURLContainer" >
                    <div class='cg_view_option_title'>
                        <p>Page URL for activation deactivation of entry
                            <br><span class="cg_view_option_title_note">
                        <span style="font-weight:bold;">NOTE: </span> place following shortcode at the page which you use for activation deactivation of entry<br>
                        <span class='cg_shortcode_parent'><span class='cg_shortcode_copy cg_shortcode_copy_mail_confirm cg_tooltip'></span><span class='cg_shortcode_copy_text'>[cg_entry_on_off id="$GalleryID"]</span></span>
                        </span>
                        </p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="InformAdminActivationURL" id="InformAdminActivationURL" value="$InformAdminActivationURL"  maxlength="200" placeholder="Example: $get_site_url/contest-entry-on-off" >
                    </div>
                </div>
        </div>
</div>
HEREDOC;

echo <<<HEREDOC
</div>
HEREDOC;

