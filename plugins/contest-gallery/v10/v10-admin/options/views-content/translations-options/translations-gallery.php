<?php

echo <<<HEREDOC
<div class='cg_view_options_rows_container'>
         <p class='cg_view_options_rows_container_title'>Gallery</p>
HEREDOC;

echo <<<HEREDOC
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width' >
                    <div class='cg_view_option_title'>
                        <p>$language_of$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_of]" maxlength="100" value="$translations[$l_of]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_NoImagesFound$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_NoImagesFound]" maxlength="100" value="$translations[$l_NoImagesFound]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_NoEntriesFound$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_NoEntriesFound]" maxlength="100" value="$translations[$l_NoEntriesFound]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_RandomSortSorting$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_RandomSortSorting]" maxlength="100" value="$translations[$l_RandomSortSorting]">
                    </div>
                </div>
        </div>         
HEREDOC;

echo <<<HEREDOC
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_Custom$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_Custom]" maxlength="100" value="$translations[$l_Custom]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_DateDescend$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_DateDescend]" maxlength="100" value="$translations[$l_DateDescend]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_DateAscend$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_DateAscend]" maxlength="100" value="$translations[$l_DateAscend]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_CommentsDescend$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_CommentsDescend]" maxlength="100" value="$translations[$l_CommentsDescend]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_CommentsAscend$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_CommentsAscend]" maxlength="100" value="$translations[$l_CommentsAscend]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_RatingDescend$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">For one star rating</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_RatingDescend]" maxlength="100" value="$translations[$l_RatingDescend]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_RatingAscend$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">For one star rating</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_RatingAscend]" maxlength="100" value="$translations[$l_RatingAscend]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_RatingQuantityDescend$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">For multiple stars rating</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_RatingQuantityDescend]" maxlength="100" value="$translations[$l_RatingQuantityDescend]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_RatingQuantityAscend$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">For multiple stars rating</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_RatingQuantityAscend]" maxlength="100" value="$translations[$l_RatingQuantityAscend]">
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_RatingSumDescend$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">For multiple stars rating</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_RatingSumDescend]" maxlength="100" value="$translations[$l_RatingSumDescend]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_RatingSumAscend$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">For multiple stars rating</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_RatingSumAscend]" maxlength="100" value="$translations[$l_RatingSumAscend]">
                    </div>
                </div>
        </div>
HEREDOC;

echo <<<HEREDOC
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_YouHaveAlreadyVotedThisPicture$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_YouHaveAlreadyVotedThisPicture]" maxlength="100" value="$translations[$l_YouHaveAlreadyVotedThisPicture]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_YouHaveAlreadyVotedThisCategory$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_YouHaveAlreadyVotedThisCategory]" maxlength="100" value="$translations[$l_YouHaveAlreadyVotedThisCategory]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_YouHaveNoMoreVotesInThisCategory$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_YouHaveNoMoreVotesInThisCategory]" maxlength="100" value="$translations[$l_YouHaveNoMoreVotesInThisCategory]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row cg_go_to_target' data-cg-go-to-target="l_AllVotesUsed">
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_AllVotesUsed$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_AllVotesUsed]" maxlength="100" value="$translations[$l_AllVotesUsed]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_ItIsNotAllowedToVoteForYourOwnPicture$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_ItIsNotAllowedToVoteForYourOwnPicture]" maxlength="100" value="$translations[$l_ItIsNotAllowedToVoteForYourOwnPicture]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_OnlyRegisteredUsersCanVote$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_OnlyRegisteredUsersCanVote]" maxlength="100" value="$translations[$l_OnlyRegisteredUsersCanVote]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_ThisEntryIsNotAWinner$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_ThisEntryIsNotAWinner]" maxlength="100" value="$translations[$l_ThisEntryIsNotAWinner]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_FullSize$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_FullSize]" maxlength="100" value="$translations[$l_FullSize]">
                    </div>
                </div>
        </div>
HEREDOC;

echo <<<HEREDOC
         <div class='cg_view_options_row' id="cgTranslationOther">
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_Other$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_Other]" maxlength="100" value="$translations[$l_Other]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_YourComment$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_YourComment]" maxlength="100" value="$translations[$l_YourComment]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row' >
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_YourVote$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_YourVote]" maxlength="100" value="$translations[$l_YourVote]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row' >
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_Sum$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_Sum]" maxlength="100" value="$translations[$l_Sum]">
                    </div>
                </div>
        </div>
HEREDOC;

echo <<<HEREDOC
</div>
HEREDOC;
// take care next row has to be after HEREDOC in file end
