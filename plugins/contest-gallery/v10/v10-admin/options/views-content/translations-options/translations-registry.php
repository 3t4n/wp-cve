<?php

echo <<<HEREDOC
<div class='cg_view_options_rows_container'>
         <p class='cg_view_options_rows_container_title'>Registration form</p>
HEREDOC;


$cgGoToEmailInputField = '<a 
                          href="?page='.cg_get_version().'/index.php&create_user_form=true&option_id='.$GalleryID.'&cg_go_to=cgGoToEmailInputField" target="_blank">Registration form</a>';

echo <<<HEREDOC
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width' >
                    <div class='cg_view_option_title'>
                        <p>$language_ThisNicknameAlreadyExists$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_ThisNicknameAlreadyExists]" maxlength="100" value="$translations[$l_ThisNicknameAlreadyExists]">
                    </div>
                </div>
         </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_ThisMailAlreadyExists$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_ThisMailAlreadyExists]" maxlength="100" value="$translations[$l_ThisMailAlreadyExists]">
                    </div>
                </div>
            </div>
HEREDOC;

$cgGoToPasswordInputField = '<a 
                          href="?page='.cg_get_version().'/index.php&create_user_form=true&option_id='.$GalleryID.'&cg_go_to=cgGoToPasswordInputField" target="_blank">Registration form</a>';

echo <<<HEREDOC
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_PasswordsDoNotMatch$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_PasswordsDoNotMatch]" maxlength="100" value="$translations[$l_PasswordsDoNotMatch]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_ConfirmationWentWrong<br><span style="font-weight:normal;">(Will be displayed if e-mail confirmation fails when clicking from registration mail)</span>$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_ConfirmationWentWrong]" maxlength="100" value="$translations[$l_ConfirmationWentWrong]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_sendRegistry<br><span style="font-weight:normal;">(Submit registration form button text)</span>$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_sendRegistry]" maxlength="100" value="$translations[$l_sendRegistry]">
                    </div>
                </div>
        </div>
HEREDOC;

echo <<<HEREDOC
</div>
HEREDOC;
// take care next row has to be after HEREDOC in file end
