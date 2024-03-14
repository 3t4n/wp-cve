<?php

echo <<<HEREDOC
<div class='cg_view_options_rows_container'>
         <p class='cg_view_options_rows_container_title'>Login form lost password</p>
HEREDOC;

echo <<<HEREDOC
      <div class='cg_view_options_row' >
            <div class='cg_view_option cg_view_option_full_width' >
                <div class='cg_view_option_title'>
                    <p>$language_LostPasswordExplanation$cgShortcodeCopy<br><span style='font-weight:normal;'>(login field)</span></p>
                </div>
                <div class='cg_view_option_input'>
                    <input type="text" name="translations[$l_LostPasswordExplanation]" maxlength="100" value="$translations[$l_LostPasswordExplanation]">
                    </div>
                </div>
      </div>
         <div class='cg_view_options_row'>
            <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                <div class='cg_view_option_title'>
                        <p>$language_LostPassword$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_LostPassword]" maxlength="100" value="$translations[$l_LostPassword]">
                    </div>
                </div>
        </div>
      <div class='cg_view_options_row' >
            <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                <div class='cg_view_option_title'>
                    <p>$language_NewPassword$cgShortcodeCopy<br><span style='font-weight:normal;'>(login field)</span></p>
                </div>
                <div class='cg_view_option_input'>
                    <input type="text" name="translations[$l_NewPassword]" maxlength="100" value="$translations[$l_NewPassword]">
                    </div>
                </div>
      </div>
      <div class='cg_view_options_row' >
            <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                <div class='cg_view_option_title'>
                    <p>$language_NewPasswordRepeat$cgShortcodeCopy<br><span style='font-weight:normal;'>(login field)</span></p>
                </div>
                <div class='cg_view_option_input'>
                    <input type="text" name="translations[$l_NewPasswordRepeat]" maxlength="100" value="$translations[$l_NewPasswordRepeat]">
                    </div>
                </div>
         </div>
      <div class='cg_view_options_row' >
            <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                <div class='cg_view_option_title'>
                    <p>$language_EmailLostPasswordSent$cgShortcodeCopy<br><span style='font-weight:normal;'>(login field)</span></p>
                </div>
                <div class='cg_view_option_input'>
                    <input type="text" name="translations[$l_EmailLostPasswordSent]" maxlength="100" value="$translations[$l_EmailLostPasswordSent]">
                    </div>
                </div>
         </div>
      <div class='cg_view_options_row' >
            <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                <div class='cg_view_option_title'>
                    <p>$language_LostPasswordUrlIsNotValidAnymore$cgShortcodeCopy<br><span style='font-weight:normal;'>(login field)</span></p>
                </div>
                <div class='cg_view_option_input'>
                    <input type="text" name="translations[$l_LostPasswordUrlIsNotValidAnymore]" maxlength="100" value="$translations[$l_LostPasswordUrlIsNotValidAnymore]">
                    </div>
                </div>
         </div>
      <div class='cg_view_options_row' >
            <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                <div class='cg_view_option_title'>
                    <p>$language_BackToLoginForm$cgShortcodeCopy<br><span style='font-weight:normal;'>(login field)</span></p>
                </div>
                <div class='cg_view_option_input'>
                    <input type="text" name="translations[$l_BackToLoginForm]" maxlength="100" value="$translations[$l_BackToLoginForm]">
                    </div>
                </div>
         </div>
HEREDOC;

echo <<<HEREDOC
</div>
HEREDOC;
// take care next row has to be after HEREDOC in file end
