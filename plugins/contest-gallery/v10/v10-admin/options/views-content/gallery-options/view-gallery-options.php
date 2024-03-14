<?php

echo <<<HEREDOC
<div class='cg_view_container'>
HEREDOC;

$cgHideStartEndTime = '';
$cgStartAndEndTimeNote = '';
if(floatval($galleryDbVersion)>=21.1){
    $cgHideStartEndTime = 'cg_hide';
    $cgStartAndEndTimeNote = <<<HEREDOC
        <div class='cg_view_options_rows_container' >
                <p class="cg_view_options_rows_container_title">Contest start and end time</p>
            <div class='cg_view_options_row cg_margin_bottom_30' >
                <div id="cgGoToShortcodesIntervalConfigurationOptions" class='cg_view_option cg_border_radius_8_px cg_view_option_full_width' style="height: 80px;" title="Go to shortcodes  interval configuration options" >
                    <div class='cg_view_option_title'>
                        <p>Contest start and end time<br>
                            <span class="cg_view_option_title_note">For contest start and end time please check <span class="cg_shortcode_interval_conf_hint_container">"Shortcode interval configuration"<span class="cg_shortcode_interval_conf_hint_icon" ></span></span> for each shortcode type at the top</span>
                        </p>
                    </div>                
                </div>
            </div>
        </div>
HEREDOC;
}

echo <<<HEREDOC
        <div class='cg_view_options_row cg_margin_bottom_30' id="cgEditGalleryNameRow">
            <div class='cg_view_option cg_view_option_full_width cg_border_radius_8_px '>
                <div class='cg_view_option_title'>
                    <p>Gallery name</p>
                </div>
                <div class='cg_view_option_input'>
                    <input type="text" placeholder="Your gallery name" class="cg-long-input" id="GalleryName" name="GalleryName" maxlength="100" value="$GalleryName1">
                </div>
            </div>
        </div>
        $cgStartAndEndTimeNote
    <div class='cg_view_options_rows_container $cgHideStartEndTime'>
        <p class='cg_view_options_rows_container_title'>Contest start options for voting and uploading</p>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_border_right_none $cgProFalse' id="ContestStartContainer">
                    <div class='cg_view_option_title'>
                        <p>Activate contest start time<br/><span class="cg_view_option_title_note">To rate and upload files will be not possible before contest starts.</span></p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" name="ContestStart" id="ContestStart"  $ContestStart  >
                    </div>
                </div>
                <div class='cg_view_option cg_view_option_two_third_width $cgProFalse' id="cg_datepicker_start_container">
                    <div class='cg_view_option_title'>
                        <p>
                                Select start day and time of contest (Unix time)<br/><span class="cg_view_option_title_note"><b>Unix time</b> is most reliable way that contest ends as configured.
                                <br>You select Unix time (UTC+0) here. <br> 
                                Current Unix time is: $dateCurrent<br>
                                Current time based on your WordPress Timezone settings: $dateCurrentWpConf<br>
                                To refresh current times above reload this window.</span>
                                </p>
                    </div>
                    <div class='cg_view_option_select'>
                        <div id='cg_datepicker_table'>
                        <input type="text" autocomplete="off" id="cg_datepicker_start"  name="ContestStartTime" value="$ContestStartTime" >
                        <input type="hidden" id="cg_datepicker_start_value_to_set" value="$ContestStartTime" >
                        <input type="number" id="cg_date_hours_contest_start" class="cg_date_hours" name="ContestStartTimeHours" placeholder="00" 
                               min="-1" max="25" value="$ContestStartTimeHours" > : 
                        <input type="number" id="cg_date_mins_contest_start" class="cg_date_mins" name="ContestStartTimeMins" placeholder="00" 
                               min="-1" max="60" value="$ContestStartTimeMins" >
                        </div>
                    </div>
                   <div class='cg_view_option_title'>
                        <p>
                            <span class="cg_view_option_title_note"><b>Manually start recommendation:</b><br><b>cg_gallery_no_voting</b> shortcode can simply be used to display gallery without voting and then replaced with <b>cg_gallery</b> when contest should start.
                            </span>
                        </p>
                    </div>
                </div>
            </div>
    </div>

    <div class='cg_view_options_rows_container $cgHideStartEndTime'>
        <p class='cg_view_options_rows_container_title'>Contest end options for voting and uploading</p>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_100_percent $cgProFalse' id="ContestEndInstantContainer">
                    <div class='cg_view_option_title'>
                        <p>End contest immediately</p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" name="ContestEndInstant" id="ContestEndInstant"   $ContestEndInstant  > 
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_border_top_right_none $cgProFalse' id="ContestEndContainer">
                    <div class='cg_view_option_title'>
                        <p>Activate contest end time<br/><span class="cg_view_option_title_note">To rate and upload files will be not possible when contest ends.</span></p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" name="ContestEnd" id="ContestEnd"   $ContestEnd  >
                    </div>
                </div>
                <div class='cg_view_option cg_view_option_two_third_width cg_border_top_none $cgProFalse' id="cg_datepicker_container">
                    <div class='cg_view_option_title'>
                        <p>Select last day and time of contest (Unix time)<br/><span class="cg_view_option_title_note">You select Unix time (UTC+0) here. <br><b>Unix time</b> is most reliable way that contest ends as configured.<br>Current Unix time is: $dateCurrent<br>
                                Current time based on your WordPress Timezone settings: $dateCurrentWpConf<br>
                                To refresh current times above reload this window.</span></p>
                    </div>
                    <div class='cg_view_option_select'>
<input type="text" autocomplete="off" id="cg_datepicker"  name="ContestEndTime" value="$ContestEndTime" >
<input type="hidden" id="cg_datepicker_value_to_set" value="$ContestEndTime" >
<input type="number" id="cg_date_hours_contest_end" class="cg_date_hours" name="ContestEndTimeHours" placeholder="00" 
                               min="-1" max="25" value="$ContestEndTimeHours" > : 
<input type="number" id="cg_date_mins_contest_end" class="cg_date_mins" name="ContestEndTimeMins" placeholder="00" 
                               min="-1" max="60" value="$ContestEndTimeMins" >
                        </div>
                    <div class='cg_view_option_title'>
                        <p>
                            <span class="cg_view_option_title_note"><b>Manually end  recommendation:</b><br>
                                <b>cg_gallery</b> shortcode can simply be used and then replaced with <b>cg_gallery_no_voting</b> 
                                 when you decide that contest ends. In gallery contact form can be activated deactivated when required or cg_users_contact shortcode can be added and then removed.
                            </span>
                        </p>
                    </div>
                    </div>
                </div>
     </div>
HEREDOC;

if(floatval($galleryDbVersion)<12.10){

    echo <<<HEREDOC
    <div class='cg_view_options_rows_container'>
        <p class='cg_view_options_rows_container_title'>General gallery view options for all views<br>
            </p>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_100_percent'>
                    <div class='cg_view_option_title'>
                        <p>Show constantly (without hovering)<br>vote, comments and file title in gallery view<br><span class="cg_view_option_title_note">You see it by hovering if not activated.<br>File title can be configured in "Edit contact form" >>> "Show as title in gallery view".<br>Can be configured <a class="cg_go_to_link cg_no_outline_and_shadow_on_focus" href="#" data-cg-go-to-link="ShowAlwaysContainer">here</a> now for each cg_gallery type of shortcode</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none $deprecatedGalleryHoverDisabledForever'>
                    <div class='cg_view_option_title'>
                        <p>Title position on gallery file image<br><span class="cg_view_option_title_note">If "Show as title in gallery view" in "Edit contact form" is activated </span></p>
                    </div>
                    <div class='cg_view_option_radio_multiple'>
                        <div class='cg_view_option_radio_multiple_container'>
                            <div class='cg_view_option_radio_multiple_title'>
                                Left
                            </div>
                            <div class='cg_view_option_radio_multiple_input'>
                                <input type="radio" name="TitlePositionGallery" class="TitlePositionGallery cg_view_option_radio_multiple_input_field"  $selectedTitlePositionGalleryLeft  value="1">
                            </div>
                        </div>
                        <div class='cg_view_option_radio_multiple_container'>
                            <div class='cg_view_option_radio_multiple_title'>
                                Center
                            </div>
                            <div class='cg_view_option_radio_multiple_input'>
                                <input type="radio" name="TitlePositionGallery" class="TitlePositionGallery cg_view_option_radio_multiple_input_field" $selectedTitlePositionGalleryCenter  value="2">
                            </div>
                        </div>
                        <div class='cg_view_option_radio_multiple_container'>
                            <div class='cg_view_option_radio_multiple_title'>
                                Right
                            </div>
                            <div class='cg_view_option_radio_multiple_input'>
                                <input type="radio" name="TitlePositionGallery" class="TitlePositionGallery cg_view_option_radio_multiple_input_field"  $selectedTitlePositionGalleryRight  value="3">
                            </div>
                        </div>
                </div>
                $deprecatedGalleryHoverDivText
            </div>
        </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_100_percent cg_border_top_none $cgProFalse' >
                    <div class='cg_view_option_title'>
                        <p>Allow only registered users to see the gallery<br><span class="cg_view_option_title_note">User have to be registered and logged in to be able to see the gallery
                        <br>Can be configured <a class="cg_go_to_link cg_no_outline_and_shadow_on_focus" href="#" data-cg-go-to-link="RegUserGalleryOnlyContainer">here</a> now for each cg_gallery type of shortcode 
                        </span></p>
                    </div>
                </div>
            </div>
            
 </div>
HEREDOC;
}


echo <<<HEREDOC
    <div class='cg_view_options_rows_container'>
        <p class='cg_view_options_rows_container_title'>Review comments and activate them manually</p>
        <div class='cg_view_options_row'>
            <div class='cg_view_option cg_border_radius_8_px cg_view_option_100_percent $cgProFalse' id="ContestEndInstantContainer">
                <div class='cg_view_option_title'>
                    <p>Review comments<br><span class="cg_view_option_title_note">Comments does not appear immediately after commenting.<br>"Your comment will be reviewed" <a class="cg_go_to_link cg_no_outline_and_shadow_on_focus" href="#" data-cg-go-to-link="YourCommentWillBeReviewedRow">translation</a> is visible instead.<br> Comments can be then manually activated in backend images area and then getting visibile in frontend.</span></p>
                </div>
                <div class='cg_view_option_checkbox'>
                    <input type="checkbox" name="ReviewComm" id="ReviewComm"   $ReviewComm  > 
                </div>
            </div>
        </div>
   </div>
HEREDOC;

include('comments-notification-email-admin-options.php');

include('comments-notification-email-user-options.php');

include('votes-notification-email-user-options.php');
