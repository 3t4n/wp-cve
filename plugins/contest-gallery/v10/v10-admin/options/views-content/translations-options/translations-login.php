<?php

echo <<<HEREDOC
<div class='cg_view_options_rows_container'>
         <p class='cg_view_options_rows_container_title'>Login form</p>
HEREDOC;


$cgGoToEmailInputField = '<a 
                          href="?page='.cg_get_version().'/index.php&create_user_form=true&option_id='.$GalleryID.'&cg_go_to=cgGoToEmailInputField" target="_blank">Registration form</a>';

echo <<<HEREDOC
         <div class='cg_view_options_row'>
            <div class='cg_view_option cg_view_option_full_width' >
                <div class='cg_view_option_title'>
                    <p>$language_GoogleSignSuccessfull$cgShortcodeCopy</p>
                </div>
                <div class='cg_view_option_input'>
                    <input type="text" name="translations[$l_GoogleSignSuccessfull]" maxlength="100" value="$translations[$l_GoogleSignSuccessfull]">
                    </div>
                </div>
         </div>
         <div class='cg_view_options_row' >
            <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                <div class='cg_view_option_title'>
                    <p>$language_UsernameOrEmail$cgShortcodeCopy</p>
                </div>
                <div class='cg_view_option_input'>
                    <input type="text" name="translations[$l_UsernameOrEmail]" maxlength="100" value="$translations[$l_UsernameOrEmail]">
                    </div>
                </div>
         </div>
         <div class='cg_view_options_row' id="cgTranslationLanguageEmail">
            <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                <div class='cg_view_option_title'>
                    <p>$language_Email$cgShortcodeCopy</p>
                </div>
                <div class='cg_view_option_input'>
                    <input type="text" name="translations[$l_Email]" maxlength="100" value="$translations[$l_Email]">
                    </div>
                </div>
         </div>
         <div class='cg_view_options_row' id="cgTranslationLanguagePassword">
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_Password$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_Password]" maxlength="100" value="$translations[$l_Password]">
                    </div>
                </div>
         </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_EmailRequired$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_EmailRequired]" maxlength="100" value="$translations[$l_EmailRequired]">
                    </div>
                </div>
         </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_PasswordRequired$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_PasswordRequired]" maxlength="100" value="$translations[$l_PasswordRequired]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_EmailAndPasswordDoNotMatch$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_EmailAndPasswordDoNotMatch]" maxlength="100" value="$translations[$l_EmailAndPasswordDoNotMatch]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_LoginAndPasswordDoNotMatch$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_LoginAndPasswordDoNotMatch]" maxlength="100" value="$translations[$l_LoginAndPasswordDoNotMatch]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_sendLogin$cgShortcodeCopy<br><span style='font-weight:normal;'>(Submit login form button text)</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_sendLogin]" maxlength="100" value="$translations[$l_sendLogin]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_ResetPasswordSuccessfully$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_ResetPasswordSuccessfully]" maxlength="100" value="$translations[$l_ResetPasswordSuccessfully]">
                    </div>
                </div>
        </div>
HEREDOC;

echo <<<HEREDOC
</div>
HEREDOC;
// take care next row has to be after HEREDOC in file end
