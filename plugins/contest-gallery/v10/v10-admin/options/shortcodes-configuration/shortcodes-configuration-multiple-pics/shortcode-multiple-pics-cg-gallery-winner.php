<?php


echo <<<HEREDOC
<div class="cg_view cgMultiplePicsOptions cg_short_code_multiple_pics_configuration_cg_gallery_winner_container cg_short_code_multiple_pics_configuration_container cg_hide cgViewHelper1">
<div class='cg_view_container'>

HEREDOC;

if(!empty($jsonOptions[$GalleryID.'-w']['visual']['AllowSortOptions'])){
    $AllowSortOptionsArrayCgGalleryWinner = explode(',',$jsonOptions[$GalleryID.'-w']['visual']['AllowSortOptions']);
}else{
    $AllowSortOptionsArrayCgGalleryWinner = array();
}

echo <<<HEREDOC
<div class='cg_view_options_row'>
    <div class='cg_view_option'>
        <div class='cg_view_option_title'>
        <p>Number of files per screen<br><span class="cg_view_option_title_note">Pagination</span></p>
        </div>
        <div class='cg_view_option_input'>
        <input type="text" name="multiple-pics[cg_gallery_winner][general][PicsPerSite]" class="PicsPerSite" maxlength="3" value="{$jsonOptions["$GalleryID-w"]["general"]["PicsPerSite"]}">
        </div>
    </div>

    <div  class='cg_view_option cg_border_left_right_none'>
        <div class='cg_view_option_title'>
        <p>Enable full window button</p>
        </div>
        <div class='cg_view_option_checkbox'>
        <input type="checkbox" name="multiple-pics[cg_gallery_winner][general][FullSizeGallery]" class="cg_shortcode_checkbox FullSizeGallery" checked="{$jsonOptions["$GalleryID-w"]["general"]["FullSizeGallery"]}"><br/>
        </div>
    </div>

    <div  class='cg_view_option'>
        <div  class='cg_view_option_title'>
        <p>Enable full screen button<br><span class="cg_view_option_title_note">Will appear when joining full window</span></p>
        </div>
        <div class='cg_view_option_checkbox'>
        <input type="checkbox" name="multiple-pics[cg_gallery_winner][general][FullSize]" class="cg_shortcode_checkbox FullSize" checked="{$jsonOptions["$GalleryID-w"]["general"]["FullSize"]}"><br/>
        </div>
    </div>
</div>

HEREDOC;

echo <<<HEREDOC


<div class='cg_view_options_row'>
    <div  class='cg_view_option cg_view_option_50_percent cg_border_top_right_bottom_none'>
        <div class='cg_view_option_title'>
        <p>Allow search for files<br/><span class="cg_view_option_title_note">Search by fields content, categories, file name or EXIF data - if available</span></p>
        </div>
        <div  class='cg_view_option_checkbox'>
        <input type="checkbox" name="multiple-pics[cg_gallery_winner][pro][Search]" class="cg_shortcode_checkbox Search" checked="{$jsonOptions["$GalleryID-w"]["pro"]["Search"]}">
        </div>
    </div>

    <div  class='cg_view_option cg_view_option_50_percent cg_border_top_bottom_none AllowSortContainer'>
        <div class='cg_view_option_title'>
        <p>Allow sort<br/><span class="cg_view_option_title_note">Order by rating is not available if <br>"Show only user votes" or <br>"Hide voting until user vote" is activated</span></p>
        </div>
        <div  class='cg_view_option_checkbox'>
                <input type="checkbox" name="multiple-pics[cg_gallery_winner][general][AllowSort]" class="cg_shortcode_checkbox AllowSort" checked="{$jsonOptions["$GalleryID-w"]["general"]["AllowSort"]}">
        </div>
    </div>
</div>

<div class='cg_view_options_row'>
    <div class='cg_view_option cg_view_option_full_width cgAllowSortOptionsContainerMain'>
        <div class='cg_view_option_title'>
        <p>Allow sort options<br><span class="cgAllowSortDependsOnMessage cg_hide" >Allow sort has to be activated</span></p>
        </div>
        <div>
HEREDOC;

$cgCustomSortCheck = (in_array('custom',$AllowSortOptionsArrayCgGalleryWinner)) ? '' : 'cg_unchecked';
$cgDateDescSortCheck = (in_array('date-desc',$AllowSortOptionsArrayCgGalleryWinner)) ? '' : 'cg_unchecked';
$cgDateAscSortCheck = (in_array('date-asc',$AllowSortOptionsArrayCgGalleryWinner)) ? '' : 'cg_unchecked';
$cgRateDescSortCheck = (in_array('rate-desc',$AllowSortOptionsArrayCgGalleryWinner)) ? '' : 'cg_unchecked';
$cgRateAscSortCheck = (in_array('rate-asc',$AllowSortOptionsArrayCgGalleryWinner)) ? '' : 'cg_unchecked';
$cgRateDescAverageSortCheck = (in_array('rate-average-desc',$AllowSortOptionsArrayCgGalleryWinner)) ? '' : 'cg_unchecked';
$cgRateAscAverageSortCheck = (in_array('rate-average-asc',$AllowSortOptionsArrayCgGalleryWinner)) ? '' : 'cg_unchecked';
$cgRateDescSumSortCheck = (in_array('rate-sum-desc', $AllowSortOptionsArrayCgGalleryWinner)) ? '' : 'cg_unchecked';
$cgRateAscSumSortCheck = (in_array('rate-sum-asc', $AllowSortOptionsArrayCgGalleryWinner)) ? '' : 'cg_unchecked';
$cgCommentDescSortCheck = (in_array('comment-desc',$AllowSortOptionsArrayCgGalleryWinner)) ? '' : 'cg_unchecked';
$cgCommentAscSortCheck = (in_array('comment-asc',$AllowSortOptionsArrayCgGalleryWinner)) ? '' : 'cg_unchecked';
$cgRandomSortCheck = (in_array('random',$AllowSortOptionsArrayCgGalleryWinner)) ? '' : 'cg_unchecked';


echo <<<HEREDOC

        <input type='hidden' name='multiple-pics[cg_gallery_winner][visual][AllowSortOptionsArray][]' value='custom' class='cg-allow-sort-input' />
        <input type='hidden' name='multiple-pics[cg_gallery_winner][visual][AllowSortOptionsArray][]' value='date-desc' class='cg-allow-sort-input' />
        <input type='hidden' name='multiple-pics[cg_gallery_winner][visual][AllowSortOptionsArray][]' value='date-asc' class='cg-allow-sort-input' />
        <input type='hidden' name='multiple-pics[cg_gallery_winner][visual][AllowSortOptionsArray][]' value='rate-desc' class='cg-allow-sort-input' />
        <input type='hidden' name='multiple-pics[cg_gallery_winner][visual][AllowSortOptionsArray][]' value='rate-asc' class='cg-allow-sort-input' />
        <input type='hidden' name='multiple-pics[cg_gallery_winner][visual][AllowSortOptionsArray][]' value='rate-average-desc' class='cg-allow-sort-input' />
        <input type='hidden' name='multiple-pics[cg_gallery_winner][visual][AllowSortOptionsArray][]' value='rate-average-asc' class='cg-allow-sort-input' />
        <input type='hidden' name='multiple-pics[cg_gallery_winner][visual][AllowSortOptionsArray][]' value='rate-sum-desc' class='cg-allow-sort-input' />
        <input type='hidden' name='multiple-pics[cg_gallery_winner][visual][AllowSortOptionsArray][]' value='rate-sum-asc' class='cg-allow-sort-input' />
        <input type='hidden' name='multiple-pics[cg_gallery_winner][visual][AllowSortOptionsArray][]' value='comment-desc' class='cg-allow-sort-input' />
        <input type='hidden' name='multiple-pics[cg_gallery_winner][visual][AllowSortOptionsArray][]' value='comment-asc' class='cg-allow-sort-input' />
        <input type='hidden' name='multiple-pics[cg_gallery_winner][visual][AllowSortOptionsArray][]' value='random' class='cg-allow-sort-input' />

        <div class="cgAllowSortOptionsContainer">
        <label class="cg-allow-sort-option $cgCustomSortCheck" data-cg-target="custom"><span class="cg-allow-sort-option-cat">Custom</span><span class="cg-allow-sort-option-icon"></span></label>
        <label class="cg-allow-sort-option $cgDateDescSortCheck" data-cg-target="date-desc"><span class="cg-allow-sort-option-cat">Date desc</span><span class="cg-allow-sort-option-icon"></span></label>
        <label class="cg-allow-sort-option $cgDateAscSortCheck" data-cg-target="date-asc"><span class="cg-allow-sort-option-cat">Date asc</span><span class="cg-allow-sort-option-icon"></span></label>
        <label class="cg-allow-sort-option $cgRateDescSortCheck" data-cg-target="rate-desc"><span class="cg-allow-sort-option-cat">Rating desc<br><small><strong>for one star voting</strong></small></span><span class="cg-allow-sort-option-icon"></span></label>
        <label class="cg-allow-sort-option $cgRateAscSortCheck" data-cg-target="rate-asc"><span class="cg-allow-sort-option-cat">Rating asc<br><small><strong>for one star voting</strong></small></span><span class="cg-allow-sort-option-icon"></span></label>
        <label class="cg-allow-sort-option $cgRateDescAverageSortCheck cg_disabled" data-cg-target="rate-average-desc"><span class="cg-allow-sort-option-cat">Rating average desc<br><strong>for multiple stars<br>deprecated</strong></span><span class="cg-allow-sort-option-icon cg_hide"></span></label>
        <label class="cg-allow-sort-option $cgRateAscAverageSortCheck cg_disabled" data-cg-target="rate-average-asc"><span class="cg-allow-sort-option-cat">Rating average asc<br><strong>for multiple stars<br>deprecated</strong></span><span class="cg-allow-sort-option-icon cg_hide"></span></label>
        <label class="cg-allow-sort-option $cgRateDescSumSortCheck" data-cg-target="rate-sum-desc"><span  style="margin-left: 3px;" class="cg-allow-sort-option-cat">Rating sum desc<br><small><strong>for multiple stars voting</strong></small></span><span class="cg-allow-sort-option-icon"></span></label>
        <label class="cg-allow-sort-option $cgRateAscSumSortCheck" data-cg-target="rate-sum-asc"><span  style="margin-left: 3px;" class="cg-allow-sort-option-cat">Rating sum asc<br><small><strong>for multiple stars voting</strong></small></span><span class="cg-allow-sort-option-icon"></span></label>
        <label class="cg-allow-sort-option $cgCommentDescSortCheck" data-cg-target="comment-desc"><span class="cg-allow-sort-option-cat">Comments desc</span><span class="cg-allow-sort-option-icon"></span></label>
        <label class="cg-allow-sort-option $cgCommentAscSortCheck" data-cg-target="comment-asc"><span class="cg-allow-sort-option-cat">Comments asc</span><span class="cg-allow-sort-option-icon"></span></label>
        <label class="cg-allow-sort-option $cgRandomSortCheck" data-cg-target="random"><span class="cg-allow-sort-option-cat">Random</span><span class="cg-allow-sort-option-icon"></span></label>
        </div>
        </div>
    </div>
</div>

HEREDOC;

echo <<<HEREDOC
<div class='cg_view_options_row'>

    <div class='cg_view_option cg_view_option_flex_flow_column cg_border_top_none PreselectSortContainer'>
        <div class='cg_view_option_title cg_view_option_title_full_width'>
            <p>Preselect order<br>on page load<br><span class="cgPreselectSortMessage cg_view_option_title_note">Random sort has to be deactivated</span></p>
        </div>
        <div class='cg_view_option_select'>
        <select name='multiple-pics[cg_gallery_winner][pro][PreselectSort]' class='PreselectSort'>
HEREDOC;

$PreselectSort_custom_selected = ($jsonOptions[$GalleryID.'-w']['pro']['PreselectSort']=='custom') ? 'selected' : '';
$PreselectSort_date_descend_selected = ($jsonOptions[$GalleryID.'-w']['pro']['PreselectSort']=='date_descend') ? 'selected' : '';
$PreselectSort_date_ascend_selected = ($jsonOptions[$GalleryID.'-w']['pro']['PreselectSort']=='date_ascend') ? 'selected' : '';
$PreselectSort_rating_descend_selected = ($jsonOptions[$GalleryID.'-w']['pro']['PreselectSort']=='rating_descend') ? 'selected' : '';
$PreselectSort_rating_ascend_selected = ($jsonOptions[$GalleryID.'-w']['pro']['PreselectSort']=='rating_ascend') ? 'selected' : '';
$PreselectSort_rating_descend_average_selected = ($jsonOptions[$GalleryID.'-w']['pro']['PreselectSort']=='rating_descend_average') ? 'selected' : '';
$PreselectSort_rating_ascend_average_selected = ($jsonOptions[$GalleryID.'-w']['pro']['PreselectSort']=='rating_ascend_average') ? 'selected' : '';
$PreselectSort_rating_sum_descend_selected = ($jsonOptions[$GalleryID.'-w']['pro']['PreselectSort']=='rating_sum_descend') ? 'selected' : '';
$PreselectSort_rating_sum_ascend_selected = ($jsonOptions[$GalleryID.'-w']['pro']['PreselectSort']=='rating_sum_ascend') ? 'selected' : '';
$PreselectSort_comments_descend_selected = ($jsonOptions[$GalleryID.'-w']['pro']['PreselectSort']=='comments_descend') ? 'selected' : '';
$PreselectSort_comments_ascend_selected = ($jsonOptions[$GalleryID.'-w']['pro']['PreselectSort']=='comments_ascend') ? 'selected' : '';

echo <<<HEREDOC
        <option value='custom' $PreselectSort_custom_selected>Custom</option>
        <option value='date_descend' $PreselectSort_date_descend_selected>Date descending</option>
        <option value='date_ascend' $PreselectSort_date_ascend_selected>Date ascending</option>
        <option value='rating_descend' $PreselectSort_rating_descend_selected>Rating descending (for one star voting)</option>
        <option value='rating_ascend' $PreselectSort_rating_ascend_selected>Rating ascending (for one star voting)</option>
        <option value='rating_sum_descend' $PreselectSort_rating_sum_descend_selected>Rating sum descending (for multiple stars voting)</option>
        <option value='rating_sum_ascend' $PreselectSort_rating_sum_ascend_selected>Rating sum ascending (for multiple stars voting)</option>
        <option value='comments_descend' $PreselectSort_comments_descend_selected>Comments descending</option>
        <option value='comments_ascend' $PreselectSort_comments_ascend_selected>Comments ascending</option>
        </select>
        </div>

    </div>

    <div class='cg_view_option cg_border_top_right_left_none RandomSortContainer'>
        <div class='cg_view_option_title'>
            <p>Random sort<br><span class="cg_view_option_title_note">Each page load.<br>Random sort option<br>will be preselected<br>if allow sort is activated.</span></p>
        </div>
        <div  class='cg_view_option_checkbox'>
            <input type="checkbox" name="multiple-pics[cg_gallery_winner][general][RandomSort]" class="cg_shortcode_checkbox RandomSort" checked="{$jsonOptions["$GalleryID-w"]["general"]["RandomSort"]}"><br/>
        </div>
    </div>

    <div class='cg_view_option cg_border_top_none'>
        <div class='cg_view_option_title'>
            <p>Random sort button</p>
        </div>
        <div  class='cg_view_option_checkbox'>
            <input type="checkbox" name="multiple-pics[cg_gallery_winner][general][RandomSortButton]" class="cg_shortcode_checkbox RandomSortButton"  checked="{$jsonOptions["$GalleryID-w"]["general"]["RandomSortButton"]}"><br/>
        </div>
    </div>

</div>
HEREDOC;

if(empty($jsonOptions[$GalleryID.'-w'])){
    $FeControlsStyleWhiteChecked = 'checked';
}else{
    $FeControlsStyleWhiteChecked = ($jsonOptions[$GalleryID.'-w']['visual']['FeControlsStyle']=='white') ? 'checked' : '';
}

if(empty($jsonOptions[$GalleryID.'-w'])){
    $FeControlsStyleBlackChecked = '';
}else{
    $FeControlsStyleBlackChecked = ($jsonOptions[$GalleryID.'-w']['visual']['FeControlsStyle']=='black') ? 'checked' : '0';
}
// add BorderRadius here
if (!isset($jsonOptions[$GalleryID.'-w']['visual']['BorderRadius'])) {
    if(!empty($BorderRadius)){
        $jsonOptions[$GalleryID.'-w']['visual']['BorderRadius'] = 1;
    }else{
        $jsonOptions[$GalleryID.'-w']['visual']['BorderRadius'] = 0;
    }
}

if(!isset($jsonOptions[$GalleryID.'-w']['visual']['EnableSwitchStyleGalleryButton'])){
    $jsonOptions[$GalleryID.'-w']['visual']['EnableSwitchStyleGalleryButton'] = 0;
}

if(!isset($jsonOptions[$GalleryID.'-w']['visual']['SwitchStyleGalleryButtonOnlyTopControls'])){
    $jsonOptions[$GalleryID.'-w']['visual']['SwitchStyleGalleryButtonOnlyTopControls'] = 0;
}

echo <<<HEREDOC
<div class="cg_view_options_row">
        <div class="cg_view_option cg_view_option_100_percent cg_border_top_none" id="BorderRadiusContainer">
            <div class="cg_view_option_title">
                <p>Round borders for all control elements and containers</p>
            </div>
            <div class="cg_view_option_checkbox cg_view_option_checked">
                <input type="checkbox" name="multiple-pics[cg_gallery_winner][visual][BorderRadius]" class="cg_shortcode_checkbox BorderRadius" checked="{$jsonOptions[$GalleryID.'-w']['visual']['BorderRadius']}">
            </div>
        </div>
</div>

<div class='cg_go_to_target' data-cg-go-to-target="TopControlsStyleContainer" >
<div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none cg_border_top_bottom_none'>
                    <div class='cg_view_option_title'>
                    <p>Gallery color style</p>
                    </div>
                    <div class='cg_view_option_radio_multiple'>
                        <div class='cg_view_option_radio_multiple_container'>
                            <div class='cg_view_option_radio_multiple_title'>
                                Bright style
                            </div>
                            <div class='cg_view_option_radio_multiple_input'>
                                <input type="radio" name="multiple-pics[cg_gallery_winner][visual][FeControlsStyle]" class="FeControlsStyleWhite cg_view_option_radio_multiple_input_field" $FeControlsStyleWhiteChecked value="white"/>
                            </div>
                        </div>
                        <div class='cg_view_option_radio_multiple_container'>
                            <div class='cg_view_option_radio_multiple_title'>
                                Dark style
                            </div>
                            <div class='cg_view_option_radio_multiple_input'>
                                <input type="radio" name="multiple-pics[cg_gallery_winner][visual][FeControlsStyle]" class="FeControlsStyleBlack cg_view_option_radio_multiple_input_field" $FeControlsStyleBlackChecked value="black">
                            </div>
                        </div>
                </div>
            </div>
</div>

 <div class='cg_view_options_row'>
            <div class='cg_view_option  cg_view_option_100_percent  cg_border_top_none EnableSwitchStyleGalleryButtonContainer'>
                <div class='cg_view_option_title'>
                    <p>Enable switch color style button<br><span class="cg_view_option_title_note">Will also switch style of opened entry view</span></p>
                </div>
                <div class='cg_view_option_checkbox'>
                    <input type="checkbox" name="multiple-pics[cg_gallery_winner][visual][EnableSwitchStyleGalleryButton]" checked="{$jsonOptions[$GalleryID.'-w']['visual']['EnableSwitchStyleGalleryButton']}" class="cg_shortcode_checkbox EnableSwitchStyleGalleryButton">
                </div>
            </div>
</div>
</div>

<div class='cg_view_options_row'>
    <div class='cg_view_option cg_view_option_full_width cg_border_top_none cg_padding_bottom_0 GalleryUploadContainer'>
        <div class='cg_view_option_title'>
            <p>In gallery contact form button<br><span class="cg_view_option_title_note">Is not available for cg_gallery_winner shortcode</span></p>
        </div>
HEREDOC;

if($isModernOptionsNew){
    $jsonOptions[$GalleryID.'-w']['pro']['GalleryUpload'] = 0;
}


if(!empty($jsonOptions[$GalleryID.'-w']['general']['ShowTextUntilAnImageAdded'])){
    $ShowTextUntilAnImageAdded = contest_gal1ery_convert_for_html_output_without_nl2br($jsonOptions[$GalleryID.'-w']['general']['ShowTextUntilAnImageAdded']);
}else{
    $ShowTextUntilAnImageAdded = '';
}

if(!isset($jsonOptions[$GalleryID.'-w']['general']['ShowAlways']) && $ShowAlways == 'checked') {
    $jsonOptions[$GalleryID.'-w']['general']['ShowAlways'] = 1;
} else if(!isset($jsonOptions[$GalleryID.'-w']['general']['ShowAlways']) && $ShowAlways != 'checked') {
    $jsonOptions[$GalleryID.'-w']['general']['ShowAlways'] = 0;
}

if(!isset($jsonOptions[$GalleryID.'-w']['pro']['RegUserGalleryOnly']) && $RegUserGalleryOnly == 'checked') {
    $jsonOptions[$GalleryID.'-w']['pro']['RegUserGalleryOnly'] = 1;
} else if(!isset($jsonOptions[$GalleryID.'-w']['pro']['RegUserGalleryOnly']) && $RegUserGalleryOnly != 'checked') {
    $jsonOptions[$GalleryID.'-w']['pro']['RegUserGalleryOnly'] = 0;
}

if(!isset($jsonOptions[$GalleryID.'-w']['pro']['RegUserGalleryOnlyText'])){
    $jsonOptions[$GalleryID.'-w']['pro']['RegUserGalleryOnlyText'] = $RegUserGalleryOnlyText;
}else{
    $jsonOptions[$GalleryID.'-w']['pro']['RegUserGalleryOnlyText'] = contest_gal1ery_convert_for_html_output($jsonOptions[$GalleryID.'-w']['pro']['RegUserGalleryOnlyText']);
}


$ShowAlwaysContainer = '';
if(floatval($galleryDbVersion)<21){
    $ShowAlwaysContainer = <<<HEREDOC
<div class='cg_view_options_row cg_go_to_target' data-cg-go-to-target="ShowAlwaysContainer">
        <div class='cg_view_option cg_view_option_100_percent cg_border_top_none'>
            <div class='cg_view_option_title'>
                <p>Show constantly (without hovering)<br>vote, comments and file title in gallery view<br><span class="cg_view_option_title_note">You see it by hovering if not activated.<br>File title can be configured in "Edit contact form" >>> "Show as title in gallery view".</span></p>
            </div>
            <div class='cg_view_option_checkbox'>
             <input type="checkbox" name="multiple-pics[cg_gallery_winner][general][ShowAlways]" checked="{$jsonOptions[$GalleryID.'-w']['general']['ShowAlways']}" class="cg_shortcode_checkbox">
            </div>
        </div>
    </div>
HEREDOC;
}



echo <<<HEREDOC
    </div>
</div>


$ShowAlwaysContainer

<div class='cg_view_options_row'>
    <div class='cg_view_option cg_view_option_full_width cg_border_top_none' id="wp-ShowTextUntilAnImageAddedGalleryWinner-wrap-Container">
        <div class='cg_view_option_title'>
            <p>This text is visible until first entry appears in the gallery<br><span class="cg_view_option_title_note">This text is visible until first entry is added, activated and so is visible in frontend.</span></p>
        </div>
        <div class='cg_view_option_html'>
            <textarea class='cg-wp-editor-template' id='ShowTextUntilAnImageAddedGalleryWinner'  name='multiple-pics[cg_gallery_winner][general][ShowTextUntilAnImageAdded]'>$ShowTextUntilAnImageAdded</textarea>
        </div>
    </div>
</div>

    <div class='cg_view_options_row cg_go_to_target RegUserGalleryOnlyContainer' data-cg-go-to-target="RegUserGalleryOnlyContainer">
        <div class='cg_view_option cg_view_option_100_percent cg_border_top_none $cgProFalse' >
            <div class='cg_view_option_title'>
                <p>Allow only registered users to see the gallery<br><span class="cg_view_option_title_note">User have to be registered and logged in to be able to see the gallery</span></p>
            </div>
            <div class='cg_view_option_checkbox'>
                <input type="checkbox" name="multiple-pics[cg_gallery_winner][pro][RegUserGalleryOnly]" checked="{$jsonOptions[$GalleryID.'-w']['pro']['RegUserGalleryOnly']}" class="cg_shortcode_checkbox RegUserGalleryOnly">
            </div>
        </div>
    </div>
    
    <div class='cg_view_options_row'>
        <div class='cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse RegUserGalleryOnlyTextContainer' id="wp-RegUserGalleryOnlyWinnerText-wrap-Container">
            <div class='cg_view_option_title'>
                <p>Show text instead of gallery</p>
            </div>
            <div class='cg_view_option_html'>
                <textarea class='cg-wp-editor-template' id='RegUserGalleryOnlyTextWinner'  name='multiple-pics[cg_gallery_winner][pro][RegUserGalleryOnlyText]'>{$jsonOptions[$GalleryID.'-w']['pro']['RegUserGalleryOnlyText']}</textarea>
            </div>
        </div>
    </div>
HEREDOC;

if(floatval($galleryDbVersion)>=21){
    if(!isset($jsonOptions[$GalleryID.'-w']['pro']['WpPageParentRedirectURL'])){
        $jsonOptions[$GalleryID.'-w']['pro']['WpPageParentRedirectURL'] = contest_gal1ery_convert_for_html_output_without_nl2br($WpPageParentRedirectURL);
    }else{
        $jsonOptions[$GalleryID.'-w']['pro']['WpPageParentRedirectURL'] = contest_gal1ery_convert_for_html_output_without_nl2br($jsonOptions[$GalleryID.'-w']['pro']['WpPageParentRedirectURL']);
    }
    echo <<<HEREDOC
    <div class='cg_view_options_row'>
        <div class='cg_view_option cg_view_option_full_width  cg_border_top_none  '>
            <div class='cg_view_option_title '>
                <p>Redirect URL for parent site of Contest Gallery entries<br><span class="cg_view_option_title_note">The gallery page for cg_gallery_winner id="$GalleryID" shortcode is<br><a target="_blank"  href="$WpPageParentWinnerPermalink">$WpPageParentWinnerPermalink</a><br>(But you can place the shortcode also on any other page)<br>If Redirect URL is set, then HTTP 301 redirect will be executed if the above URL gets called<br><span class="cg_font_weight_500">NOTE: </span> has to start with <span class="cg_font_weight_500">http://</span> or <span class="cg_font_weight_500">https://</span>, like https://www.example.com</span></p>
            </div>
            <div class='cg_view_option_input '>
                <input type="text" name='multiple-pics[cg_gallery_winner][pro][WpPageParentRedirectURL]' class="WpPageParentRedirectURL"  value="{$jsonOptions[$GalleryID.'-w']['pro']['WpPageParentRedirectURL']}"  >
            </div>
        </div>
    </div>
HEREDOC;

// only json option, not in database available
    if(!isset($jsonOptions[$GalleryID.'-w']['visual']['AdditionalCssGalleryPage'])){
        $AdditionalCssGalleryPage = "body {\r\n&nbsp;&nbsp;font-family: sans-serif;\r\n&nbsp;&nbsp;font-size: 16px;\r\n&nbsp;&nbsp;background-color: white;\r\n&nbsp;&nbsp;color: black;\r\n}";
    }else{
        $AdditionalCssGalleryPage = cg_stripslashes_recursively($jsonOptions[$GalleryID.'-w']['visual']['AdditionalCssGalleryPage']);
    }

    echo <<<HEREDOC
    <div class='cg_view_options_row'>
        <div class='cg_view_option cg_view_option_full_width  cg_border_top_none '>
            <div class='cg_view_option_title '>
                <p>Additional CSS cg_gallery page<br><span class="cg_view_option_title_note"><a target="_blank"  href="$WpPageParentWinnerPermalink">$WpPageParentWinnerPermalink</a></span></p>
            </div>
            <div class='cg_view_option_textarea' >
                <textarea type="text" name="multiple-pics[cg_gallery_winner][visual][AdditionalCssGalleryPage]" rows="7" style="width:100%;" class="AdditionalCssGalleryPage"  >$AdditionalCssGalleryPage</textarea>
            </div>
</div>
    </div>
HEREDOC;

}

echo <<<HEREDOC
</div>
HEREDOC;


//print_r($order);

$showSliderViewOption = false;
$showSliderViewOptionSet = false;

if(!in_array("SliderLookOrder",$order)){
    $showSliderViewOption = true;
}

$showBlogViewOption = false;
$showBlogViewOptionSet = false;

if(!in_array("BlogLookOrder",$order)){
    $showBlogViewOption = true;
}

$i = 0;

if(!empty($jsonOptions[$GalleryID.'-w']['general']['ThumbLookOrder'])){
    $order = array();
    $order[$jsonOptions[$GalleryID.'-w']['general']['ThumbLookOrder']] = 'ThumbLookOrder';
    $order[$jsonOptions[$GalleryID.'-w']['general']['SliderLookOrder']] = 'SliderLookOrder';
    $order[$jsonOptions[$GalleryID.'-w']['general']['HeightLookOrder']] = 'HeightLookOrder';
    $order[$jsonOptions[$GalleryID.'-w']['general']['RowLookOrder']] = 'RowLookOrder';

    if(empty($jsonOptions[$GalleryID.'-w']['visual']['BlogLookOrder'])){
        $jsonOptions[$GalleryID.'-w']['visual']['BlogLookOrder'] = 5;
    }

    $order[$jsonOptions[$GalleryID.'-w']['visual']['BlogLookOrder']] = 'BlogLookOrder';

    ksort($order);

}else{
    $order = $order;
}


// add BlogLook here
$jsonOptions[$GalleryID.'-w']['visual']['BlogLook'] = (!empty($jsonOptions[$GalleryID.'-w']['visual']['BlogLook'])) ? $jsonOptions[$GalleryID.'-w']['visual']['BlogLook'] : 0;

echo <<<HEREDOC


<div class='cg_options_sortable'>

<p class='cg_options_sortable_title'>View options and order</p>

HEREDOC;

if($jsonOptions[$GalleryID.'-w']['general']['RowLook']==1){
    $jsonOptions[$GalleryID.'-w']['general']['RowLook'] = 0;
    $jsonOptions[$GalleryID.'-w']['general']['HeightLook'] = 1;
}

$i = 0;

foreach ($order as $key => $value) {

    if ($value == "BlogLookOrder" or ($showBlogViewOption == true && $showBlogViewOptionSet == false)) {

        $i++;

        $showSliderViewOptionSet = true;

        echo <<<HEREDOC
        <div class='cg_options_sortableContainer'>
            <div class='cg_options_sortableDiv'>
             <div class="cg_options_order">$i.</div>
              <div class="cg_options_order_change_order cg_move_view_to_bottom"><i></i></div>
               <div class="cg_options_order_change_order cg_move_view_to_top"><i></i></div>
                <div class='cg_view_options_row'>
                    <div class='cg_view_option cg_view_options_and_order_checkbox_container cg_view_option_100_percent BlogLookContainer cg_border_radius_8_px'>
                        <div class='cg_view_option_title'>
                                <input type="hidden" name="multiple-pics[cg_gallery_winner][general][order][]" value="b" >
                                <p>Activate <u>Blog View</u></p>
                         </div>
                         <div  class='cg_view_option_checkbox cg_view_options_and_order_checkbox'>
                            <input type="checkbox" name="multiple-pics[cg_gallery_winner][visual][BlogLook]" class="cg_shortcode_checkbox BlogLook" checked="{$jsonOptions[$GalleryID.'-w']['visual']['BlogLook']}">
                         </div>
                    </div>
                </div>
            </div>
        </div>
        
HEREDOC;

    }

    if ($value == "SliderLookOrder" or ($showSliderViewOption == true && $showSliderViewOptionSet == false)) {

        $i++;

        $showSliderViewOptionSet = true;

        $jsonOptions[$GalleryID.'-w']['visual']['SliderThumbNav'] = (!isset($jsonOptions[$GalleryID.'-w']['visual']['SliderThumbNav'])) ? 1 : $jsonOptions[$GalleryID.'-w']['visual']['SliderThumbNav'];

        echo <<<HEREDOC
        <div class='cg_options_sortableContainer'>
            <div class='cg_options_sortableDiv'>
            <div class="cg_options_order">$i.</div>
            <div class="cg_options_order_change_order cg_move_view_to_bottom"><i></i></div>
                <div class="cg_options_order_change_order cg_move_view_to_top"><i></i></div>
                <div class='cg_view_options_row'>
                    <div class='cg_view_option cg_view_options_and_order_checkbox_container cg_border_right_none cg_view_option_50_percent SliderLookContainer cg_border_border_bottom_left_radius_8_px'>
                        <div class='cg_view_option_title'>
                                <input type="hidden" name="multiple-pics[cg_gallery_winner][general][order][]" value="s" >
                                <p>Activate <u>Slider View</u></p>
                         </div>
                         <div  class='cg_view_option_checkbox cg_view_options_and_order_checkbox'>
                            <input type="checkbox" name="multiple-pics[cg_gallery_winner][general][SliderLook]" class="cg_shortcode_checkbox SliderLook" checked="{$jsonOptions[$GalleryID.'-w']['general']['SliderLook']}">
                         </div>
                    </div>
                    <div class='cg_view_option cg_view_option_50_percent SliderThumbNavContainer cg_border_border_top_right_radius_8_px cg_border_border_bottom_right_radius_8_px'>
                        <div class='cg_view_option_title'>
                                <p>Enable thumbnail navigation</p>
                         </div>
                         <div  class='cg_view_option_checkbox'>
                            <input type="checkbox" name="multiple-pics[cg_gallery_winner][visual][SliderThumbNav]" class="cg_shortcode_checkbox SliderThumbNav" checked="{$jsonOptions[$GalleryID.'-w']['visual']['SliderThumbNav']}" >
                         </div>
                    </div>
                </div>
        </div>
    </div>
    
HEREDOC;

    }

    if ($value == "ThumbLookOrder") {

        $i++;

        echo <<<HEREDOC
        <div class='cg_options_sortableContainer'>
            <div class='cg_options_sortableDiv'>
            <div class="cg_options_order">$i.</div>
            <div class="cg_options_order_change_order cg_move_view_to_bottom"><i></i></div>
                <div class="cg_options_order_change_order cg_move_view_to_top"><i></i></div>
                <div class='cg_view_options_row'>
                    <div class='cg_view_option cg_view_options_and_order_checkbox_container cg_border_right_none ThumbLookContainer cg_border_border_top_left_radius_8_px'>
                        <div class='cg_view_option_title'>
                                <input type="hidden" name="multiple-pics[cg_gallery_winner][general][order][]" value="t" >
                                <p>Activate <u>Thumb View</u></p>
                         </div>
                         <div  class='cg_view_option_checkbox cg_view_options_and_order_checkbox'>
                            <input type="checkbox" name="multiple-pics[cg_gallery_winner][general][ThumbLook]" class="cg_shortcode_checkbox ThumbLook" checked="{$jsonOptions[$GalleryID.'-w']['general']['ThumbLook']}">
                         </div>
                    </div>
                    <div class='cg_view_option cg_border_right_none WidthThumbContainer'>
                        <div class='cg_view_option_title'>
                                <p>Width thumbs (px)</p>
                         </div>
                         <div  class='cg_view_option_input'>
                            <input type="text" maxlength="3" name="multiple-pics[cg_gallery_winner][general][WidthThumb]" class="WidthThumb" value="{$jsonOptions[$GalleryID.'-w']['general']['WidthThumb']}" >
                         </div>
                    </div>
                    <div class='cg_view_option HeightThumbContainer cg_border_border_top_right_radius_8_px'>
                        <div class='cg_view_option_title'>
                                <p>Height thumbs (px)</p>
                         </div>
                         <div  class='cg_view_option_input'>
                            <input type="text" maxlength="3" name="multiple-pics[cg_gallery_winner][general][HeightThumb]" class="HeightThumb" value="{$jsonOptions[$GalleryID.'-w']['general']['HeightThumb']}" >
                         </div>
                    </div>
                </div>
                <div class='cg_view_options_row'>
                    <div class='cg_view_option cg_view_option_50_percent cg_border_top_right_none DistancePicsContainer cg_border_border_bottom_left_radius_8_px'>
                        <div class='cg_view_option_title'>
                                <p>Distance between thumbs horizontal (px)</p>
                         </div>
                         <div  class='cg_view_option_input'>
                            <input type="text" maxlength="2" name="multiple-pics[cg_gallery_winner][general][DistancePics]" class="DistancePics" value="{$jsonOptions[$GalleryID.'-w']['general']['DistancePics']}">
                         </div>
                    </div>
                    <div class='cg_view_option cg_view_option_50_percent cg_border_top_none DistancePicsVContainer cg_border_border_bottom_right_radius_8_px'>
                        <div class='cg_view_option_title'>
                                <p>Distance between thumbs vertical (px)</p>
                         </div>
                         <div  class='cg_view_option_input'>
                            <input type="text" maxlength="2" name="multiple-pics[cg_gallery_winner][general][DistancePicsV]" class="DistancePicsV" value="{$jsonOptions[$GalleryID.'-w']['general']['DistancePicsV']}">
                         </div>
                    </div>
                </div>
        </div>
    </div>

HEREDOC;
    }

    if ($value == "HeightLookOrder") {
        // since 21.2.0 no HeightLook anymore
        continue;

        echo <<<HEREDOC
        <div class='cg_options_sortableContainer'>
            <div class='cg_options_sortableDiv'>
            <div class="cg_options_order">$i.</div>
            <div class="cg_options_order_change_order cg_move_view_to_bottom"><i></i></div>
                <div class="cg_options_order_change_order cg_move_view_to_top"><i></i></div>
                <div class='cg_view_options_row'>
                    <div class='cg_view_option cg_border_right_none cg_view_option_50_percent HeightLookContainer cg_border_border_top_left_radius_8_px'>
                        <div class='cg_view_option_title'>
                                <input type="hidden" name="multiple-pics[cg_gallery_winner][general][order][]" value="h" >
                                <p>Activate <u>Height View</u></p>
                         </div>
                         <div  class='cg_view_option_checkbox'>
                            <input type="checkbox" name="multiple-pics[cg_gallery_winner][general][HeightLook]" class="cg_shortcode_checkbox HeightLook" checked="{$jsonOptions[$GalleryID.'-w']['general']['HeightLook']}">
                         </div>
                    </div>
                    <div class='cg_view_option cg_view_option_50_percent HeightLookHeightContainer cg_border_border_top_right_radius_8_px'>
                        <div class='cg_view_option_title'>
                                <p>Height of pics in a row (px)</p>
                         </div>
                         <div  class='cg_view_option_input'>
                            <input type="text" maxlength="3" name="multiple-pics[cg_gallery_winner][general][HeightLookHeight]" class="HeightLookHeight" value="{$jsonOptions[$GalleryID.'-w']['general']['HeightLookHeight']}" >
                         </div>
                    </div>
                </div>
                <div class='cg_view_options_row'>
                    <div class='cg_view_option cg_view_option_50_percent cg_border_top_right_none HeightViewSpaceWidthContainer cg_border_border_bottom_left_radius_8_px'>
                        <div class='cg_view_option_title'>
                                <p>Horizontal distance between files (px)</p>
                         </div>
                         <div  class='cg_view_option_input'>
                            <input type="text" maxlength="2" name="multiple-pics[cg_gallery_winner][visual][HeightViewSpaceWidth]" class="HeightViewSpaceWidth" value="{$jsonOptions[$GalleryID.'-w']['visual']['HeightViewSpaceWidth']}">
                         </div>
                    </div>
                    <div class='cg_view_option cg_view_option_50_percent cg_border_top_none HeightViewSpaceHeightContainer cg_border_border_bottom_right_radius_8_px'>
                        <div class='cg_view_option_title'>
                                <p>Vertical distance between files (px)</p>
                         </div>
                         <div  class='cg_view_option_input'>
                            <input type="text" maxlength="2" name="multiple-pics[cg_gallery_winner][visual][HeightViewSpaceHeight]" class="HeightViewSpaceHeight" value="{$jsonOptions[$GalleryID.'-w']['visual']['HeightViewSpaceHeight']}">
                         </div>
                    </div>
                </div>
        </div>
    </div>
    
HEREDOC;

    }

    if ($value == "RowLookOrder") {

        $rowViewDeprecatedText = '';
        $rowViewDeprecatedDisabled = '';

        if(intval($galleryDbVersion)<15){
            $rowViewDeprecatedText = '<br><span class="cg_color_red">NOTE:</span> since plugin version 15.0.0<br>"Row View" is deprecated<br>can\'t fulfill mobile requirements<br>if "Row View" was activated before<br>"Height View" will be activated instead';
            $rowViewDeprecatedDisabled = 'cg_disabled';
        }else{
            if(intval($galleryDbVersion)>=15){
                continue;
            }
        }

        $i++;

        echo <<<HEREDOC
        <div class='cg_options_sortableContainer'>
            <div class='cg_options_sortableDiv'>
                <div class="cg_options_order">$i.</div>
                <div class="cg_options_order_change_order cg_move_view_to_bottom"><i></i></div>
                <div class="cg_options_order_change_order cg_move_view_to_top"><i></i></div>
                <div class='cg_view_options_row'>
                    <div class='cg_view_option cg_border_right_none cg_view_option_50_percent RowLookContainer $rowViewDeprecatedDisabled'>
                        <div class='cg_view_option_title'>
                                <input type="hidden" name="multiple-pics[cg_gallery_winner][general][order][]" value="r" >
                                <p>Activate <u>Row View</u><br><span class="cg_font_weight_normal">(Same amount of files in each row)$rowViewDeprecatedText</span></p>
                         </div>
                         <div  class='cg_view_option_checkbox'>
                            <input type="checkbox" name="multiple-pics[cg_gallery_winner][general][RowLook]" class="cg_shortcode_checkbox RowLook" checked="{$jsonOptions[$GalleryID.'-w']['general']['RowLook']}">
                         </div>
                    </div>
                    <div class='cg_view_option cg_view_option_50_percent PicsInRowContainer'>
                        <div class='cg_view_option_title'>
                                <p>Number of pics in a row</p>
                         </div>
                         <div  class='cg_view_option_input'>
                            <input type="text" maxlength="2" name="multiple-pics[cg_gallery_winner][general][PicsInRow]" class="PicsInRow" value="{$jsonOptions[$GalleryID.'-w']['general']['PicsInRow']}" >
                         </div>
                    </div>
                </div>
                <div class='cg_view_options_row'>
                    <div class='cg_view_option cg_view_option_50_percent cg_border_top_right_none RowViewSpaceWidthContainer'>
                        <div class='cg_view_option_title'>
                                <p>Horizontal distance between files (px)</p>
                         </div>
                         <div  class='cg_view_option_input'>
                            <input type="text" maxlength="2" name="multiple-pics[cg_gallery_winner][visual][RowViewSpaceWidth]" class="RowViewSpaceWidth" value="{$jsonOptions[$GalleryID.'-w']['visual']['RowViewSpaceWidth']}">
                         </div>
                    </div>
                    <div class='cg_view_option cg_view_option_50_percent cg_border_top_none RowViewSpaceHeightContainer'>
                        <div class='cg_view_option_title'>
                                <p>Vertical distance between files (px)</p>
                         </div>
                         <div  class='cg_view_option_input'>
                            <input type="text" maxlength="2" name="multiple-pics[cg_gallery_winner][visual][RowViewSpaceHeight]" class="RowViewSpaceHeight" value="{$jsonOptions[$GalleryID.'-w']['visual']['RowViewSpaceHeight']}">
                         </div>
                    </div>
                </div>
        </div>
    </div>
HEREDOC;

    }

}

echo <<<HEREDOC

</div>
</div>

HEREDOC;
