<?php

echo <<<HEREDOC
<div class='cg_view_options_rows_container'>
         <p class='cg_view_options_rows_container_title'>Gallery/Upload</p>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width' >
                    <div class='cg_view_option_title'>
                        <p>$language_ThePhotoContestHasNotStartedYet$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_ThePhotoContestHasNotStartedYet]" maxlength="100" value="$translations[$l_ThePhotoContestHasNotStartedYet]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_ThePhotoContestIsOver$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_ThePhotoContestIsOver]" maxlength="100" value="$translations[$l_ThePhotoContestIsOver]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_Yes$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_Yes]" maxlength="100" value="$translations[$l_Yes]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_No$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_No]" maxlength="100" value="$translations[$l_No]">
                    </div>
                </div>
        </div>
</div>
HEREDOC;
// take care next row has to be after HEREDOC in file end
