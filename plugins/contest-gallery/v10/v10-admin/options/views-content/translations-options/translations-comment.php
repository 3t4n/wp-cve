<?php

echo <<<HEREDOC
<div class='cg_view_options_rows_container cg_go_to_target' data-cg-go-to-target="TranslationsCommentFormArea">
         <p class='cg_view_options_rows_container_title'>Comment area</p>
HEREDOC;
echo <<<HEREDOC
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width' >
                    <div class='cg_view_option_title'>
                        <p>$language_Name$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_Name]" maxlength="100" value="$translations[$l_Name]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_Comment$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_Comment]" maxlength="100" value="$translations[$l_Comment]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_Send$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_Send]" maxlength="100" value="$translations[$l_Send]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_ThankYouForYourComment$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_ThankYouForYourComment]" maxlength="100" value="$translations[$l_ThankYouForYourComment]">
                    </div>
                </div>
         </div>
         <div class='cg_view_options_row cg_go_to_target' data-cg-go-to-target="YourCommentWillBeReviewedRow">
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_YourCommentWillBeReviewed$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_YourCommentWillBeReviewed]" maxlength="100" value="$translations[$l_YourCommentWillBeReviewed]">
                    </div>
                </div>
         </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_TheNameFieldMustContainTwoCharactersOrMore$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_TheNameFieldMustContainTwoCharactersOrMore]" maxlength="100" value="$translations[$l_TheNameFieldMustContainTwoCharactersOrMore]">
                    </div>
                </div>
         </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_TheCommentFieldMustContainThreeCharactersOrMore$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_TheCommentFieldMustContainThreeCharactersOrMore]" maxlength="100" value="$translations[$l_TheCommentFieldMustContainThreeCharactersOrMore]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_YouHaveToBeLoggedInToComment$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_YouHaveToBeLoggedInToComment]" maxlength="100" value="$translations[$l_YouHaveToBeLoggedInToComment]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_PleaseUseAvailableEmojis$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_PleaseUseAvailableEmojis]" maxlength="100" value="$translations[$l_PleaseUseAvailableEmojis]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_EmojisAreNotAllowed$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_EmojisAreNotAllowed]" maxlength="100" value="$translations[$l_EmojisAreNotAllowed]">
                    </div>
                </div>
        </div>
HEREDOC;

echo <<<HEREDOC
</div>
HEREDOC;
// take care next row has to be after HEREDOC in file end
