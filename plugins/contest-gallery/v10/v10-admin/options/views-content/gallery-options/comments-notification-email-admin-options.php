<?php

echo <<<HEREDOC
    <div class='cg_view_options_rows_container cg_go_to_target' data-cg-go-to-target="CommentNotificationArea">
        <p class='cg_view_options_rows_container_title'>Comments notification e-mail options for admin
            <br><span class='cg_view_options_rows_container_title_note'><span class="cg_color_red">NOTE:</span> relating testing - e-mail where is send to should not contain $cgYourDomainName.<br>Many servers can not send to own domain.</span>
        </p>
HEREDOC;


if(strpos($mailExceptions,'User comment notification e-mail') !== false){
    echo "<div style=\"width:330px;margin: -8px auto 15px;\"><a href=\"?page=".cg_get_version()."/index.php&amp;corrections_and_improvements=true&amp;option_id=$galeryNR\" class='cg_load_backend_link'><input class=\"cg_backend_button cg_backend_button_back cg_backend_button_warning\" type=\"button\" value=\"There were mail exceptions for this mailing type\" style=\"width:330px;\"></a>
</div>";
}else{
    echo "<div style=\"width:280px;margin: -8px auto 15px;\"><a href=\"?page=".cg_get_version()."/index.php&amp;corrections_and_improvements=true&amp;option_id=$galeryNR\" class='cg_load_backend_link'><input class=\"cg_backend_button cg_backend_button_back cg_backend_button_success\" type=\"button\" value=\"No mail exceptions for this mailing type\" style=\"width:280px;\"></a>
</div>";
}

echo <<<HEREDOC
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_view_option_100_percent $cgProFalse cg_border_border_top_left_radius_8_px cg_border_border_top_right_radius_8_px" id="CommNoteActiveContainer">
                <div class="cg_view_option_title">
                    <p>Activate new comment notification e-mail for admin<br>
                    <span class="cg_view_option_title_note" style="font-weight: bold;">Will be send after every comment done in frontend</span><br>
                    <span class="cg_view_option_title_note"><span class="cg_color_red">Pay attention!</span> It depends on your server provider how fast e-mails will be sent. So if there are many comments done, mails might be sent with delay.</span></p>
                </div>
                <div class="cg_view_option_checkbox">
                    <input type="checkbox" name="CommNoteActive" id="CommNoteActive" value="1" $CommNoteActive >
                </div>
            </div>
        </div>
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_comm_note_option cg_view_option_full_width cg_border_top_none $cgProFalse" id="CommNoteAddressor">
                <div class="cg_view_option_title">
                    <p>Header (like your company name or something like that, not an e-mail)</p>
                </div>
                <div class="cg_view_option_input">
                    <input type="text" name="CommNoteAddressor" value="$CommNoteAddressor" maxlength="200" >
                </div>
            </div>
       </div>
       <div class='cg_view_options_row'>
                <div class='cg_view_option cg_comm_note_option  cg_view_option_full_width cg_border_top_none $cgProFalse' id="CommNoteAdminMail" >
                    <div class='cg_view_option_title'>
                        <p>Admin e-mail (To)<br><span class="cg_view_option_title_note">
                            <span class="cg_color_red">NOTE:</span> relating testing - e-mail where is send to should not contain $cgYourDomainName.<br>Many servers can not send to own domain.</span>
                            </span>
                        </p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="CommNoteAdminMail" value="$CommNoteAdminMail"  maxlength="200" >
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_comm_note_option  cg_view_option_full_width cg_border_top_none $cgProFalse" id="CommNoteReply">
                <div class="cg_view_option_title">
                    <p>Reply e-mail (address From)<br><span class="cg_view_option_title_note">Should not be empty</span></p>
                </div>
                <div class="cg_view_option_input">
                    <input type="text" name="CommNoteReply" value="$CommNoteReply" maxlength="1000" >
                </div>
            </div>
       </div>
       <div class='cg_view_options_row'>
                <div class='cg_view_option cg_comm_note_option  cg_view_option_full_width cg_border_top_none $cgProFalse' id="CommNoteCC" >
                    <div class='cg_view_option_title'>
                        <p>CC e-mail<br><span class="cg_view_option_title_note">Should not be the same as "Reply e-mail"<br>Sending to multiple recipients example (mail1@example.com; mail2@example.com; mail3@example.com</span>
                        </p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="CommNoteCC" value="$CommNoteCC"  maxlength="200" >
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_comm_note_option  cg_view_option_full_width cg_border_top_none $cgProFalse' id="CommNoteBCC" >
                    <div class='cg_view_option_title'>
                        <p>BCC e-mail<br><span class="cg_view_option_title_note">Should not be the same as "Reply e-mail"<br>Sending to multiple recipients example (mail1@example.com; mail2@example.com; mail3@example.com</span>
                        </p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="CommNoteBCC" value="$CommNoteBCC"  maxlength="200" >
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_comm_note_option  cg_view_option_full_width cg_border_top_none $cgProFalse" id="CommNoteSubject">
                <div class="cg_view_option_title">
                    <p>Subject</p>
                </div>
                <div class="cg_view_option_input">
                    <input type="text" name="CommNoteSubject" class="cg-long-input" value="$CommNoteSubject"  maxlength="1000">
                </div>
            </div>
       </div>
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_comm_note_option  cg_view_option_full_width cg_border_top_none $cgProFalse" id="wp-CommNoteContent-wrap-Container">
                <div class="cg_view_option_title cg_copyable">
                        <p>Mail content<br><span class="cg_view_option_title_note">Use <span style="font-weight:bold;">\$comment$</span> in the editor if you want to see the comment and link to comment backend and frontend.<br>
                        <span class="cg_color_red">Pay attention!</span> E-mail provider can not display all types of smileys (emojis) which might be added to a comment.<br>They might be shown as cryptic code or question marks in e-mail.</span></p>
                </div>
                <div class="cg_view_option_html">
                    <textarea class='cg-wp-editor-template' id='CommNoteContent'  name='CommNoteContent'>$CommNoteContent</textarea
                </div>
            </div>
       </div>
    </div>
    </div>
HEREDOC;
