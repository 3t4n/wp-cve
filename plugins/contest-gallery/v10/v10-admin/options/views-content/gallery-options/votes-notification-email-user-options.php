<?php


if(strpos($mailExceptions,'E-mail to user when votes were done') !== false){
    $mailExceptionUserVoteMail = "<div style=\"width:330px;margin: -8px auto 15px;\"><a href=\"?page=".cg_get_version()."/index.php&amp;corrections_and_improvements=true&amp;option_id=$galeryNR\" class='cg_load_backend_link'><input class=\"cg_backend_button cg_backend_button_back cg_backend_button_warning\" type=\"button\" value=\"There were mail exceptions for this mailing type\" style=\"width:330px;\"></a>
</div>";
}else{
    $mailExceptionUserVoteMail = "<div style=\"width:280px;margin: -8px auto 15px;\"><a href=\"?page=".cg_get_version()."/index.php&amp;corrections_and_improvements=true&amp;option_id=$galeryNR\" class='cg_load_backend_link'><input class=\"cg_backend_button cg_backend_button_back cg_backend_button_success\" type=\"button\" value=\"No mail exceptions for this mailing type\" style=\"width:280px;\"></a>
</div>";
}

$InformUserVote = ($selectSQLemailUserVote->InformUserVote==1) ? 'checked' : '';
$InformUserVoteHeader = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLemailUserVote->Header);
$InformUserVoteMailInterval = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLemailUserVote->MailInterval);
$InformUserVoteSubject = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLemailUserVote->Subject);
$InformUserVoteReply = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLemailUserVote->Reply);
$InformUserVoteCC = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLemailUserVote->CC);
$InformUserVoteBCC = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLemailUserVote->BCC);
$InformUserVoteURL = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLemailUserVote->URL);
$ContentUserVoteContent = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLemailUserVote->Content);

echo <<<HEREDOC
    <div class='cg_view_options_rows_container'>
        <p class='cg_view_options_rows_container_title'>Votes notification e-mail options for frontend user
        <br><span class="cg_view_options_rows_container_title_note"><span class="cg_color_red">NOTE:</span> relating testing - e-mail where is send to should not contain $cgYourDomainName.<br>Many servers can not send to own domain.</span></span></p>
        $mailExceptionUserVoteMail
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_view_option_100_percent $cgProFalse  cg_border_border_top_left_radius_8_px cg_border_border_top_right_radius_8_px" id="cgInformUserVoteContainer">
                <div class="cg_view_option_title">
                    <p>Inform frontend user when votes were done for his files
                        <br><span class="cg_view_option_title_note">Files has to be added by a registered user</span><br>
                                               <span class="cg_view_option_title_note" style="font-weight: bold;">Will be send depending on mail interval option</span>
                    </p>
                </div>
                <div class="cg_view_option_checkbox">
                    <input id='InformUserVote' type='checkbox' name='InformUserVote' $InformUserVote >
                </div>
            </div>
        </div>
HEREDOC;

echo <<<HEREDOC
<div class='cg_view_options_row'>
    <div  class='cg_view_option cg_border_top_none cg_inform_user_vote  cg_view_option_full_width $cgProFalse  cg_view_option_flex_flow_column InformUserVoteMailInterval'>
        <div class='cg_view_option_title cg_view_option_title_full_width'>
        <p>Mail Intervall<br>
        <span class="cg_view_option_title_note">Higher interval recommended.<br>
        <span  style="font-weight: bold;">Most hosting packages limit how many mails can be sent in a certain interval.</span>
        </span>
        </p>
        </div>
        <div class="cg_view_option_select">
        <select name="InformUserVoteMailInterval">
HEREDOC;
foreach($MailIntervalArray as $InformUserMailIntervalKey => $InformUserMailIntervalValue){
    $InformUserMailIntervalValueSelected = '';
    if($InformUserMailIntervalValue==$InformUserVoteMailInterval){
        $InformUserMailIntervalValueSelected = 'selected';
    }
    echo "<option value='$InformUserMailIntervalValue' $InformUserMailIntervalValueSelected >$InformUserMailIntervalKey</option>";
}

echo <<<HEREDOC
                               </select>
        </div>
    </div>
</div>
HEREDOC;

echo <<<HEREDOC
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none cg_inform_user_vote $cgProFalse' id="cgInformUserVoteHeaderContainer" >
                    <div class='cg_view_option_title'>
                        <p>Header<br><span class="cg_view_option_title_note">Like your company name or something like that, not an e-mail</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="InformUserVoteHeader" id="cgInformUserVoteFrom" value="$InformUserVoteHeader"  maxlength="200" >
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_inform_user_vote cg_border_top_none $cgProFalse' id="cgInformUserVoteReplyContainer" >
                    <div class='cg_view_option_title'>
                        <p>Reply e-mail (address From)<br><span class="cg_view_option_title_note">Should not be empty</span>
                        </p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="InformUserVoteReply" id="cgInformUserVoteReply" value="$InformUserVoteReply"  maxlength="200" >
                    </div>
                </div>
        </div>
HEREDOC;

echo <<<HEREDOC
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_inform_user_vote cg_border_top_none $cgProFalse' id="cgInformUserVoteCContainer" >
                    <div class='cg_view_option_title'>
                        <p>CC e-mail<br><span class="cg_view_option_title_note">Should not be the same as "Reply e-mail"<br>Sending to multiple recipients example (mail1@example.com; mail2@example.com; mail3@example.com</span>
                        </p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="InformUserVoteCC" id="cgInformUserVoteCC" value="$InformUserVoteCC"  maxlength="200" >
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_inform_user_vote cg_border_top_none $cgProFalse' id="cgInformUserVoteBCContainer" >
                    <div class='cg_view_option_title'>
                        <p>BCC e-mail<br><span class="cg_view_option_title_note">Should not be the same as "Reply e-mail"<br>Sending to multiple recipients example (mail1@example.com; mail2@example.com; mail3@example.com</span>
                        </p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="InformUserVoteBCC" id="cgInformUserVoteBCC" value="$InformUserVoteBCC"  maxlength="200" >
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_inform_user_vote cg_border_top_none $cgProFalse' id="cgInformUserVoteHeaderContainer" >
                    <div class='cg_view_option_title'>
                        <p>Subject</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="InformUserVoteSubject" id="cgInformUserVoteUbject" value="$InformUserVoteSubject"  maxlength="200" >
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row $cgHideUrlEntryFieldsForMails'>
                <div class='cg_view_option cg_view_option_full_width cg_inform_user_vote cg_border_top_none $cgProFalse' id="InformUsersURLContainer" >
                    <div class='cg_view_option_title'>
                        <p>URL<br><span class="cg_view_option_title_note">URL of same page where a cg_gallery... shortcode of this gallery is inserted, so where votes will be done</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="InformUserVoteURL" id="InformUserVoteURL" value="$InformUserVoteURL"  placeholder="Example: $get_site_url/contest-gallery" maxlength="1000">
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_inform_user_vote cg_border_top_none $cgProFalse'  id="wp-InformUserVoteContent-wrap-Container" >
                    <div class='cg_view_option_title cg_copyable'>
                        <p>Mail content<br><span class="cg_view_option_title_note">Use <span style="font-weight:bold;">\$info$</span>
                         in the editor if you like to display added number of votes in the interval and to attach URLs to files where votes were done.<br>Maximum 10 URLs will be displayed in a mail.<br>
                        <span class="$cgHideUrlEntryFieldsForMails"><span style="font-weight: bold;">NOTE:</span> Do not forget to setup URL where gallery shortcode is inserted (above).</span></span></p>
                    </div>
                    <div class='cg_view_option_html'>
                        <textarea class='cg-wp-editor-template' id='InformUserVoteContent'  name='InformUserVoteContent'>$ContentUserVoteContent</textarea>
                    </div>
                </div>
        </div>
</div>
HEREDOC;

