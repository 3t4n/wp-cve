<?php

echo <<<HEREDOC

<div class='cg_view_container'>

HEREDOC;

echo <<<HEREDOC
        <div class='cg_view_options_row cg_margin_bottom_30'>
            <div class='cg_view_option cg_view_option_100_percent cg_border_radius_8_px'>
                <div class='cg_view_option_title'>
                    <p>Allow manipulate rating by administrator (you)<br>
                    <span class="cg_view_option_title_note">After activating and saving this option
                    just go "Back to gallery" and you will
                    be able to change rating of each file</span></p>
                </div>
                <div class='cg_view_option_checkbox'>
                    <input type="checkbox" name="Manipulate" id="Manipulate" $Manipulate>
                </div>
            </div>
        </div>
        
    <div class='cg_view_options_rows_container' id="CheckMethodsContainer">
        <p class='cg_view_options_rows_container_title'>User recognition methods<br><span class="cg_view_options_rows_container_title_note"><b style="font-weight: 700;">Every vote is tracked and is visible in files area for every file in "Show votes"</b></span></p>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_100_percent CheckMethodContainer' id="CheckIpContainer" >
                    <div class='cg_view_option_title'>
                        <p>Check by IP<br/><span class="cg_view_option_title_note">IP will be tracked always.$userIPunknown<br>A user can manipulate by switching IP.<br>A network of users, like an office for example, can have same IP.</span></p>
                    </div>
                    <div class='cg_view_option_radio'>
                        <input type="radio" name="CheckMethod" class="CheckMethod" id="CheckIp" value="ip" $CheckIp>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_100_percent CheckMethodContainer cg_border_top_none $cgProFalse'  id="CheckCookieContainer"  >
                    <div class='cg_view_option_title'>
                        <p>Check by Cookie<br/><span class="cg_view_option_title_note">Cookie with unique cookie ID will be set and tracked if this option is activated.<br>A user can manpulate by deleting site cookies.</span></p>
                    </div>
                    <div class='cg_view_option_radio'>
                        <input type="radio" name="CheckMethod" class="CheckMethod" id="CheckCookie" value="cookie" $CheckCookie>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse' id="CheckCookieAlertMessageContainer">
                    <div class='cg_view_option_title'>
                        <p>Check Cookie alert message if user browser does not allow cookies</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" class="cg-long-input" placeholder="Please allow cookies to vote" id="CheckCookieAlertMessage" name="CheckCookieAlertMessage" maxlength="1000" value="$CheckCookieAlertMessage">
                    </div>
                </div>
            </div>
             <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_100_percent cg_border_top_none CheckMethodContainer $cgProFalse' id="CheckIpAndCookieContainer" >
                    <div class='cg_view_option_title'>
                        <p>Check by IP and Cookie<br/><span class="cg_view_option_title_note">IP will be tracked and cookie with unique cookie ID will be set and tracked<br>if this option is activated.<br>A user have to switch IP and to delete cookies to manipulate.</span></p>
                    </div>
                    <div class='cg_view_option_radio'>
                        <input type="radio" name="CheckMethod" class="CheckMethod" id="CheckIpAndCookie" value="ip-and-cookie" $CheckIpAndCookie>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_100_percent CheckMethodContainer cg_border_top_none $cgProFalse'   id="CheckLoginContainer"   >
                    <div class='cg_view_option_title'>
                        <p>Check if is registered user<br/><span class="cg_view_option_title_note">User have to be registered and logged in to be able to vote.<br>User WordPress ID based voting  – uncheatable, can not be manipulated.<br>User WordPress ID will be always tracked if user is logged in.
                        <br><strong>NEW!</strong> WordPress account can be easy created via Google sign in button now!<br>Check "Login via Google" options.</span>
                        </p>
                    </div>
                    <div class='cg_view_option_radio'>
                        <input type="radio" name="CheckMethod" class="CheckMethod" id="CheckLogin" value="login" $CheckLogin>
                    </div>
                </div>
            </div>
    </div>
HEREDOC;

echo <<<HEREDOC

    <div class='cg_view_options_rows_container'>
        <p class='cg_view_options_rows_container_title'>Limit votes</p>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_50_percent cg_border_right_none cg_border_border_top_right_radius_unset' id="VotePerCategoryContainer" >
                    <div class='cg_view_option_title'>
                        <p>One vote per category<br/><span class="cg_view_option_title_note">For every category can be voted only one time by a user. Categories field with categories has to be added in "Edit contact form".</span></p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" name="VotePerCategory"  id="VotePerCategory" $VotePerCategory>
                    </div>
                </div>
                <div class='cg_view_option cg_view_option_50_percent $cgProFalse cg_border_border_top_right_radius_8_px' id="VotesPerCategoryContainer" >
                    <div class='cg_view_option_title'>
                        <p>Votes per category<br/><span class="cg_view_option_title_note">0 or empty = no limit</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="VotesPerCategory" id="VotesPerCategory" maxlength="3" value="$VotesPerCategory">
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_50_percent cg_border_top_right_none $cgProFalse' id="IpBlockContainer" >
                    <div class='cg_view_option_title'>
                        <p>One vote per entry<br/><span class="cg_view_option_title_note">Every file can be voted only one time by a user</span></p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" name="IpBlock"  id="IpBlock" $selectedCheckIp>
                    </div>
                </div>
                <div class='cg_view_option cg_view_option_50_percent cg_border_top_none $cgProFalse' id="VotesPerUserContainer" >
                    <div class='cg_view_option_title'>
                        <p>Votes per user<br/><span class="cg_view_option_title_note">0 or empty = no limit</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="VotesPerUser" id="VotesPerUser" maxlength="3" value="$VotesPerUser">
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse' id="VotesPerUserAllVotesUsedHtmlMessageContainer" >
                    <div class='cg_view_option_title'>
                        <p>Show this HTML content instead of translation message when all "Votes per user" are used<br/><span class="cg_view_option_title_note">If empty then 
<a class="cg_go_to_link cg_no_outline_and_shadow_on_focus" href="#" data-cg-go-to-link="l_AllVotesUsed">translation</a> will be shown</span></p>
                    </div>
                    <div class='cg_view_option_html'>
                        <textarea class='cg-wp-editor-template' id='VotesPerUserAllVotesUsedHtmlMessage'  name='VotesPerUserAllVotesUsedHtmlMessage'>$VotesPerUserAllVotesUsedHtmlMessage</textarea>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_100_percent cg_border_top_none $cgProFalse' id="VoteMessageSuccessActiveContainer" >
                    <div class='cg_view_option_title'>
                        <p>Show HTML message after every successful vote<span class="cg_view_option_title_note"><br>If <strong>voting limitation is not reached</strong> like "vote/votes per category, one vote per entry, votes per user" then HTML message appears.<br>"Show thank you for voting message after voting" message will be not used then.</span></p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" name="VoteMessageSuccessActive"  id="VoteMessageSuccessActive" $VoteMessageSuccessActive>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse' id="VoteMessageSuccessTextContainer" >
                    <div class='cg_view_option_title'>
                        <p>HTML message after every successful vote</p>
                    </div>
                    <div class='cg_view_option_html'>
                        <textarea class='cg-wp-editor-template' id='VoteMessageSuccessText'  name='VoteMessageSuccessText'>$VoteMessageSuccessText</textarea>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_100_percent cg_border_top_none $cgProFalse' id="VoteMessageWarningActiveContainer" >
                    <div class='cg_view_option_title'>
                        <p>Show HTML message if voting limitation reached<span class="cg_view_option_title_note"><br>If <strong>voting limitation is reached</strong> like "vote/votes per category, one vote per entry, votes per user" then HTML message appears.<br><strong>If deactivated corresponding translation messages for each case will be shown.</strong></span></p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" name="VoteMessageWarningActive"  id="VoteMessageWarningActive" $VoteMessageWarningActive>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse' id="VoteMessageWarningTextContainer" >
                    <div class='cg_view_option_title'>
                        <p>HTML message if voting limitation reached</p>
                    </div>
                    <div class='cg_view_option_html'>
                        <textarea class='cg-wp-editor-template' id='VoteMessageWarningText'  name='VoteMessageWarningText'>$VoteMessageWarningText</textarea>
                    </div>
                </div>
            </div>
    </div>
HEREDOC;

$AllowRating3_disabled_class = '';

if($AllowRating==2 || $AllowRating==0){
    $AllowRating3_disabled_class = ' cg_disabled ';
}

if($AllowRating==2 || $AllowRating==0){
    $AllowRating3_disabled_class = ' cg_disabled ';
}

$AllowRatingOptions = '';

/**
 <option value="12">2 stars</option>
<option value="13">3 stars</option>
<option value="14">4 stars</option>
<option value="15">5 stars</option>
<option value="16">6 stars</option>
<option value="17">7 stars</option>
<option value="18">8 stars</option>
<option value="19">9 stars</option>
<option value="20">10 stars</option>
*/

for($i = 12;$i<=20;$i++){
    $iToShow = $i-10;
    $AllowRating3_selected = '';
    if($AllowRating==$i){
        $AllowRating3_selected = 'selected';
    }
    $AllowRatingOptions .= '<option value="'.$i.'" '.$AllowRating3_selected.'>'.$iToShow.' stars</option>';
}

$AllowRating3Select = <<<HEREDOC
    <select name='AllowRating3' id="AllowRating3" class="$AllowRating3_disabled_class" data-cg-gid="$GalleryID">
        $AllowRatingOptions
    </select>
HEREDOC;

echo <<<HEREDOC

    <div class='cg_view_options_rows_container'>
        <p class='cg_view_options_rows_container_title'>Voting configuration</p>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_50_percent cg_border_right_none cg_border_border_top_right_radius_unset' id="AllowRating2Container" >
                    <div class='cg_view_option_title'>
                        <p>Allow vote via 1 star</p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" name="AllowRating2" id="AllowRating2" $selectedCheckRating2>
                    </div>
                </div>
                <div class='cg_view_option cg_view_option_50_percent cg_border_border_top_right_radius_8_px' >
                    <div class='cg_view_option_content'>
                      <div>
                        <a class="cg-rating-reset" href="?page=$cg_get_version/index.php&edit_options=true&option_id=$galeryNR&reset_votes2=true" id="cg_reset_votes2" >
                        <button type="button">Reset votes completely (1 star)</button></a></div>
                        <div>
                        <a class="cg-rating-reset cg-rating-reset-administrator-votes"
                         href="?page=$cg_get_version/index.php&edit_options=true&option_id=$galeryNR&reset_admin_votes2=true" id="cg_reset_admin_votes2">
                        <button type="button">Reset manually added votes only (1 star)</button></a></div>
                        <span class='cg-info-container' id='cg_reset_votes2_explanation' style='display: none;'>
                        - 1 star votes counter will be deleted (starts with 0 again)<br>- All tracked users 1 star voting data for every file will be also deleted<br>- By Administrator manually (via manipulation) added votes will be not deleted
                        </span>
                        <span class='cg-info-container' id='cg_reset_admin_votes2_explanation' style='display: none;'>
                        - 1 star votes counter will be not deleted<br>- All tracked users 1 star voting data for every file will be not deleted<br>- By administrator manually (via manipulation) added votes will be deleted
                        </span>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_50_percent cg_border_top_right_none cg_border_bottom_none' id="AllowRatingContainer" >
                    <div class='cg_view_option_title' style="flex-flow: column;justify-content: center;">
                        <p>Allow vote via multiple stars</p>
                        <p style="margin-top: 10px;">$AllowRating3Select</p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" name="AllowRating" id="AllowRating" $selectedCheckRating>
                    </div>
                </div>
                <div class='cg_view_option cg_view_option_50_percent cg_border_top_none cg_border_bottom_none' >
                    <div class='cg_view_option_content'>
                      <div>
                        <a class="cg-rating-reset"
                         href="?page=$cg_get_version/index.php&edit_options=true&option_id=$galeryNR&reset_votes=true" id="cg_reset_votes">
                        <button type="button">Reset votes completely (multiple stars)</button></a></div>
                        <div>
                        <a class="cg-rating-reset cg-rating-reset-administrator-votes" 
                          href="?page=$cg_get_version/index.php&edit_options=true&option_id=$galeryNR&reset_admin_votes=true" id="cg_reset_admin_votes">
                        <button type="button">Reset manually added votes only (multiple stars)</button></a></div>
                        <span class='cg-info-container' id='cg_reset_votes_explanation' style='display: none;'>
                        - Multiple stars votes counter will be deleted (starts with 0 again)<br>- All tracked users multiple stars voting data for every file will be also deleted<br>- By Administrator manually (via manipulation) added votes will be not deleted
                        </span>
                        <span class='cg-info-container' id='cg_reset_admin_votes_explanation' style='display: none;'>
                        - Multiple stars votes counter will be not deleted<br>- All tracked users multiple stars voting data for every file will be not deleted<br>- By administrator manually (via manipulation) added votes will be deleted
                        </span>
                    </div>
                </div>
            </div>
HEREDOC;



echo <<<HEREDOC
            <div class='cg_view_options_row'>
               <div class='cg_view_option cg_view_option_100_percent' id="ThankVoteContainer" >
                    <div class='cg_view_option_title'>
                        <p>Show thank you for voting message after voting<br/><span class="cg_view_option_title_note">After vote is done thank you for voting message appears.<br>Translation for the message can be found  <a class="cg_go_to_link cg_no_outline_and_shadow_on_focus" href="#" data-cg-go-to-link="l_ThankVote">here</a></span></span></p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" name="ThankVote" id="ThankVote" $ThankVote>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_50_percent cg_border_top_right_none' id="RatingOutGalleryContainer" >
                    <div class='cg_view_option_title'>
                        <p>Allow vote out of gallery<br/><span class="cg_view_option_title_note">It is not necessary to open entry for voting, just vote out of gallery</span></p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" name="RatingOutGallery" id="RatingOutGallery" $selectedRatingOutGallery>
                    </div>
                </div>
                <div class='cg_view_option cg_view_option_half_width cg_border_top_none $deprecatedGalleryHoverDisabledForever' id="RatingPositionGalleryContainer" >
                    <div class='cg_view_option_title'>
                        <p>Rating star position on gallery image file</p>
                    </div>
                    <div class='cg_view_option_radio_multiple'>
                        <div class='cg_view_option_radio_multiple_container'>
                            <div class='cg_view_option_radio_multiple_title'>
                                Left
                            </div>
                            <div class='cg_view_option_radio_multiple_input'>
                                <input type="radio" name="RatingPositionGallery" class="RatingPositionGallery cg_view_option_radio_multiple_input_field" $selectedRatingPositionGalleryLeft value="1" >
                            </div>
                        </div>
                        <div class='cg_view_option_radio_multiple_container'>
                            <div class='cg_view_option_radio_multiple_title'>
                                Center
                            </div>
                            <div class='cg_view_option_radio_multiple_input'>
                                <input type="radio" name="RatingPositionGallery" class="RatingPositionGallery cg_view_option_radio_multiple_input_field" $selectedRatingPositionGalleryCenter value="2" >
                            </div>
                        </div>
                        <div class='cg_view_option_radio_multiple_container'>
                            <div class='cg_view_option_radio_multiple_title'>
                                Right
                            </div>
                            <div class='cg_view_option_radio_multiple_input'>
                                <input type="radio" name="RatingPositionGallery" class="RatingPositionGallery cg_view_option_radio_multiple_input_field" $selectedRatingPositionGalleryRight value="3" >
                            </div>
                        </div>
                </div>
                $deprecatedGalleryHoverDivText
                </div>
            </div>
HEREDOC;

echo <<<HEREDOC
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_50_percent cg_border_top_right_none $cgProFalse' id="ShowOnlyUsersVotesContainer" >
                    <div class='cg_view_option_title'>
                        <p>Show only user votes<br/>(for cg_gallery and cg_gallery_user shortcodes)<br/><span class="cg_view_option_title_note">User see only his votes not the whole rating</span></p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" name="ShowOnlyUsersVotes" id="ShowOnlyUsersVotes" $ShowOnlyUsersVotes>
                    </div>
                </div>
               <div class='cg_view_option cg_view_option_50_percent cg_border_top_none $cgProFalse' id="HideUntilVoteContainer" >
                    <div class='cg_view_option_title'>
                        <p>Hide rating of an entry<br/>until user voted for this entry<br/>(for cg_gallery and cg_gallery_user shortcodes)<br/><span class="cg_view_option_title_note">Sort by rating and preselect by rating<br>on page load is not possible then</span></p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" name="HideUntilVote" id="HideUntilVote" $HideUntilVote>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_100_percent cg_border_top_none $cgProFalse' id="VoteNotOwnImageContainer" >
                    <div class='cg_view_option_title'>
                        <p>Voting of self-added file is not allowed<br/><span class="cg_view_option_title_note">User can not vote own uploaded file. Works only for voting recognition methods:<br>- Check by IP (files added since version 10.9.3.7)<br> - Check by registration</span></p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" name="VoteNotOwnImage"  id="VoteNotOwnImage" $VoteNotOwnImage>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
               <div class='cg_view_option cg_view_option_100_percent cg_border_top_none $cgProFalse' id="MinusVoteContainer" >
                    <div class='cg_view_option_title'>
                        <p>Delete (undo) votes<br/><span class="cg_view_option_title_note">Frontend users can delete (take back / undo) their votes<br>and vote again or give their taken back vote to another file</span></p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" name="MinusVote" id="MinusVote" $MinusVote>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_50_percent cg_border_top_right_none $cgProFalse' id="VotesInTimeContainer" >
                    <div class='cg_view_option_title'>
                        <p>Votes in time interval per user</p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" name="VotesInTime" id="VotesInTime" $VotesInTime>
                    </div>
                </div>
               <div class='cg_view_option cg_view_option_50_percent cg_border_top_none $cgProFalse' id="VotesInTimeQuantityContainer" >
                    <div class='cg_view_option_title'>
                        <p>Votes in time interval per user amount<br/>(empty = 1)</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" placeholder="1" name="VotesInTimeQuantity" id="VotesInTimeQuantity" maxlength="3" value="$VotesInTimeQuantity">
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_border_border_bottom_left_radius_8_px cg_view_option_half_width cg_border_top_right_none $cgProFalse' id="VotesInTimeHoursMinutesContainer" >
                    <div class='cg_view_option_title'>
                        <p>Votes in time interval per user interval<br/><span class="cg_view_option_title_note">(Hours:Minutes)</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="number" id="cg_date_hours_vote_interval" class="cg_date_hours_unlimited" name="cg_date_hours_vote_interval" placeholder="0" min="-1" max="1000" value="$cg_date_hours_vote_interval" maxlength="3" style="width:60px;" >
                        <input type="number" id="cg_date_mins_vote_interval" class="cg_date_mins" name="cg_date_mins_vote_interval" placeholder="00" 
       min="-1" max="60" value="$cg_date_mins_vote_interval" style="width:60px;" >
                    </div>
                </div>
               <div class='cg_view_option cg_border_border_bottom_left_radius_unset cg_border_border_bottom_right_radius_8_px cg_view_option_half_width cg_border_top_none $cgProFalse' id="VotesInTimeIntervalAlertMessageContainer" >
                    <div class='cg_view_option_title'>
                        <p>Votes in time interval per user<br/>alert message</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" placeholder="You can vote only 1 time per day" name="VotesInTimeIntervalAlertMessage" id="VotesInTimeIntervalAlertMessage" maxlength="200" value="$VotesInTimeIntervalAlertMessage">
                    </div>
                </div>
            </div>
    </div>

HEREDOC;

if(intval($galleryDbVersion)<17){// since 19.1.4 Facebook message not visible anymore

    $FbLikeNoShareChecked = '';
    if(!empty($FbLikeNoShare)){
        $FbLikeNoShareChecked = '';
    }

    $fbLikeButtonDisabled = '';
    if(intval($galleryDbVersion)>=17){
        $fbLikeButtonDisabled = 'cg_disabled';
        $fbLikeButtonNote = '<br><span class="cg_color_red">NOTE:</span> Since plugin version 17 where new file types were introduced Facebook share button is deprecated and can not be used in new or copied galleries anymore. File/Image link can be simply copied and posted on Facebook if required.';
    }else{
        $fbLikeButtonNote = '<br>Share button still works if "Facebook share button only" is activated.';
    }

    echo <<<HEREDOC
    <div class='cg_view_options_rows_container'>
            <p class='cg_view_options_rows_container_title'>Facebook like and share button options
            <br><span class="cg_view_options_rows_container_title_note"><b style="font-weight: 700;">Facebook removed like button functionality during 2021.$fbLikeButtonNote</b></span>
            </p>
HEREDOC;

    $fbDeprecated = '';

    if(floatval($galleryDbVersion)>=13.00){
        $fbDeprecated = 'cg_disabled';
    }

    echo <<<HEREDOC
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_100_percent $fbDeprecated' id="FbLikeContainer" >
                    <div class='cg_view_option_title'>
                        <p>Vote via Facebook like button<br/><span class="cg_view_option_title_note">Deprecated</span></p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" name="FbLike" id="FbLike" $selectedCheckFbLike>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
               <div class='cg_view_option cg_view_option_100_percent cg_border_top_none $cgProFalse $fbLikeButtonDisabled' id="FbLikeOnlyShareContainer" >
                    <div class='cg_view_option_title'>
                        <p>Show Facebook share button only<br><span class="cg_view_option_title_note">New fields will be added to files backend area and also new field types will be added to upload form if activated. Upload form field types are connected to images backend area fields.</span></p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" name="FbLikeOnlyShare" id="FbLikeOnlyShare" $FbLikeOnlyShare>
                    </div>
                    </div>
                </div>
            <div class='cg_view_options_row'>
               <div class='cg_view_option cg_view_option_100_percent cg_border_top_none $cgProFalse' id="FbLikeGalleryContainer" >
                    <div class='cg_view_option_title'>
                        <p>Show Facebook share button out of gallery<br><span class="cg_view_option_title_note">(Activate "Show Facebook share button only first")<br>Slower browser loading of gallery. Needs more computing power. Pagination with not so many images at one step is better in that case. Will be not shown in small images overview of slider.</span></p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" name="FbLikeGallery" id="FbLikeGallery" $selectedCheckFbLikeGallery>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse' id="FbLikeGoToGalleryLinkContainer" >
                    <div class='cg_view_option_title'>
                        <p>Gallery shortcode URL for Facebook share button<br><span class="cg_view_option_title_note">(Activate "Show Facebook share button only first")<br>It will be forwarded to this page when Facebook share button is used</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type='text' class='' name='FbLikeGoToGalleryLink' id='FbLikeGoToGalleryLink' maxlength='1000' placeholder='$FbLikeGoToGalleryLinkPlaceholder' value='$FbLikeGoToGalleryLink'>
                    </div>
                </div>
            </div>
    </div>
HEREDOC;

}


echo <<<HEREDOC
</div>
HEREDOC;


