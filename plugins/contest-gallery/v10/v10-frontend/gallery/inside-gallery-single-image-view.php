<?php

include(__DIR__.'/../../../vars/general/emojis.php');

$emojisDiv = '<div class="cg-emojis-div cg_hide">';

foreach($emojis as $emoji){
    $emojiSplitted = str_split($emoji);
    $emojiSplitted = implode('.',$emojiSplitted);

    $emojiSpan = "<span data-emoji='&#$emoji;' data-emoji-splitted='&.#.$emojiSplitted;' class='cg-emoji-select'>&#$emoji;</span>";
    $emojisDiv .= $emojiSpan;
}
$emojisDiv .= '</div>';

$switchStyleGalleryButton = '';

if(!empty($options['visual']['EnableSwitchStyleImageViewButton'])){
    $switchColorsTooltipStyleText = $language_BrightStyle;
    if($cgFeControlsStyle == 'cg_fe_controls_style_white'){
        $switchColorsTooltipStyleText = $language_DarkStyle;
    }
    $switchStyleGalleryButton = '<div class="cg_switch_colors" data-cg-gid="'.$galeryIDuser.'" data-cg-tooltip="'.$switchColorsTooltipStyleText.'"></div>';
}

echo <<<HEREDOC
<div id="cgCenterDiv$galeryIDuserForJs" class="cgCenterDiv" data-cg-gid="$galeryIDuserForJs">
    <div id="cgCenterDivChild$galeryIDuserForJs" class="cgCenterDivChild" data-cg-gid="$galeryIDuserForJs">
            <div id="cgCenterOrientation$galeryIDuserForJs" class="cg-center-orientation cg_hide"></div>
         <div id="cgCenterDivHelper$galeryIDuserForJs" class="cg-center-div-helper" data-cg-gid="$galeryIDuserForJs">
             <div id="cgCenterSliderPositionInfo$galeryIDuserForJs" class="cg-center-slider-position-info">
                    1 of 100
             </div>
             <div id="cgCenterImageDivButtons$galeryIDuserForJs" class="cg-center-image-div-buttons">
                    <div  class="cg-center-image-div-buttons-first-controls">
                        <div class="cg-center-go-up-button cg_hide" id="cgCenterGoUpButton$galeryIDuserForJs" data-cg-gid="$galeryIDuserForJs" data-cg-tooltip="$language_GoToTopOfTheGallery" ></div>
                        <div class="cg_hover_effect cg-fullwindow-configuration-button cg-header-controls-show-only-full-window cg_hide" id="cgCenterDivCenterImageFullWindowConfiguration$galeryIDuser" data-cg-gid="$galeryIDuserForJs" data-cg-tooltip="$language_SearchOrSort"></div>
                         <div  class="cg-center-image-div-buttons-first-controls-arrows">
                            <div class="cg-center-arrow cg-center-arrow-left" id="cgCenterArrowLeft$galeryIDuserForJs" data-cg-tooltip="$language_PreviousImage" ></div>
                            <div class="cg-center-arrow cg-center-arrow-right" id="cgCenterArrowRight$galeryIDuserForJs" data-cg-tooltip="$language_NextImage" ></div>
                        </div>
                    </div>
                   <div class="cg-center-image-div-buttons-second-controls">
                   <div class="cg_delete_user_image cg_hide" data-cg-gid="$galeryIDuserForJs" ></div>
                   $switchStyleGalleryButton
                          <div class="cg-image-image-href-to-copy cg_gallery_control_element" data-cg-gid="$galeryIDuserForJs" data-cg-tooltip="$language_CopyGalleryEntryLink"></div>
                         <div class="cg-center-image-download cg_hide"  data-cg-tooltip="$language_Download"></div>
                         <div class="cg-gallery-upload cg_gallery_control_element" data-cg-gid="$galeryIDuserForJs" data-cg-tooltip="$language_ImageUpload"></div>
                        <div class="cg-fullscreen-button cg-header-controls-show-only-full-window cg_hide" id="cgCenterImageFullScreenButton$galeryIDuser" data-cg-gid="$galeryIDuserForJs" data-cg-tooltip="$language_FullScreen"></div>
                    <div id="cgCenterImageClose$galeryIDuserForJs" class="cg-center-image-close cg_hide"></div>
                        <div class="cg-center-image-fullwindow cg-inside-center-image-div" id="cgCenterImageFullwindow$galeryIDuserForJs" data-cg-gid="$galeryIDuserForJs" data-cg-tooltip="$language_FullWindow"></div>
                    </div>
            </div>
HEREDOC;

echo <<<HEREDOC
            <div id="cgCenterDivHelperHelper$galeryIDuserForJs" class="cg-center-div-helper-helper" data-cg-gid="$galeryIDuserForJs">
            
                <div id="cgCenterDivHelperHelperHelper$galeryIDuserForJs" class="cg-center-div-helper-helper-helper" data-cg-gid="$galeryIDuserForJs">
                
                        <div id="cgCenterImageDiv$galeryIDuserForJs" class="cg-center-image-div">
            
                            <div id="cgCenterShowNicknameParent$galeryIDuserForJs" class="cg-center-show-nickname-parent cg_hide" >
                                <div id="cgCenterShowNickname$galeryIDuserForJs" class="cg-center-show-nickname" >
                                        <span id="cgCenterShowProfileImage$galeryIDuserForJs" class="cg-center-show-profile-image cg_hide" ></span>
                                        <span id="cgCenterShowNicknameAvatar$galeryIDuserForJs" class="cg-center-show-nickname-avatar"></span>
                                        <span id="cgCenterShowNicknameText$galeryIDuserForJs" class="cg-center-show-nickname-text"></span>
                                </div>
                            </div>
                            <div id="cgCenterShowSocialShareMobileButton$galeryIDuserForJs" class="cg_center_show_social_share_mobile_button cg_hide" data-cg-tooltip="$language_ShareTo...">
                            </div>
                            <div id="cgCenterShowSocialShareParent$galeryIDuserForJs" class="cg_center_show_social_share_parent cg_hide" >
                            </div>
                            <div id="cgCenterImageParent$galeryIDuserForJs" class="cg-center-image-parent" >
                                <div class="cg-center-image-additional-file-button cg-center-image-prev-file cg_hide" data-cg-gid="$galeryIDuserForJs" data-cg-tooltip="$language_PreviousFileInEntry" ></div>
                                <div class="cg-center-image-additional-file-button cg-center-image-next-file cg_hide" data-cg-gid="$galeryIDuserForJs" data-cg-tooltip="$language_NextFileInEntry"  ></div>
                                <div id="cgCenterImage$galeryIDuserForJs" class="cg-center-image" >
                                    <div id="cgCenterImageRotated$galeryIDuserForJs" class="cg-center-image-rotated" >
                        
                                    </div>
                                </div>
                            </div>
                            
                             <div id="cgCenterImageExifData$galeryIDuserForJs" class="cg-center-image-exif-data cg_hide" >
                                 <div id="cgCenterImageExifDataSub$galeryIDuserForJs" class="cg-center-image-exif-data-sub" >
                                    <div class="cg-exif cg-exif-date-time-original cg_hide"><span class="cg-exif-date-time-original-img"></span><span class="cg-exif-date-time-original-text"></span></div>
                                    <div class="cg-exif cg-exif-model cg_hide"><span class="cg-exif-model-img"></span><span class="cg-exif-model-text"></span></div>
                                    <div class="cg-exif cg-exif-aperturefnumber cg_hide cg-exif"><span class="cg-exif-aperturefnumber-img"></span><span class="cg-exif-aperturefnumber-text"></span></div>
                                    <div class="cg-exif cg-exif-exposuretime cg_hide cg-exif"><span class="cg-exif-exposuretime-img"></span><span class="cg-exif-exposuretime-text"></span></div>
                                    <div class="cg-exif cg-exif-isospeedratings cg_hide cg-exif"><span class="cg-exif-isospeedratings-img"></span><span class="cg-exif-isospeedratings-text"></span></div>
                                    <div class="cg-exif cg-exif-focallength cg_hide cg-exif"><span class="cg-exif-focallength-img"></span><span class="cg-exif-focallength-text"></span></div>
                                 </div>
                             </div>
HEREDOC;

echo <<<HEREDOC
                            <div id="cgCenterImageRatingAndButtonsDiv$galeryIDuserForJs" class="cg-center-image-rating-and-buttons-div">
                             <div id="cgCenterImageDivButtonsLeft$galeryIDuserForJs" class="cg-center-image-div-buttons-left">
                                   <div class="cg-center-image-div-buttons-second-controls">
                                        <div class="cg_delete_user_image cg_hide" data-cg-gid="$galeryIDuserForJs" ></div>
                                         <div class="cg-copy-original-file-link cg_hide" data-cg-tooltip="$language_CopyOriginalFileSourceLink"></div>
                                         <div class="cg-forward-original-file cg_hide" data-cg-tooltip="$language_OpenOriginalFileInNewTab"></div>
                                    </div>
                                </div>
                                <div id="cgCenterImageRatingDiv$galeryIDuserForJs" class="cg-center-image-rating-div">
                                </div>
                                <div id="cgCenterImageDivButtons$galeryIDuserForJs" class="cg-center-image-div-buttons">
                                   <div class="cg-center-image-div-buttons-second-controls">
                                         <div class="cg-center-image-download cg_hide" data-cg-tooltip="$language_DownloadOriginalFile"></div>
                                          <div class="cg-image-image-href-to-copy cg_gallery_control_element" data-cg-gid="$galeryIDuserForJs" data-cg-tooltip="$language_CopyGalleryEntryLink"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                
                </div> 
HEREDOC;

echo <<<HEREDOC
                        <div id="cgCenterInfoDiv$galeryIDuserForJs" class="cg-center-info-div">
                        
                            <div id="cgCenterImageInfoDivParent$galeryIDuserForJs" class="cg-center-image-info-div-parent">
                                   <div id="cgCenterImageInfoEditIconContainer$galeryIDuserForJs" class="cg-center-image-info-edit-icon-container">
                                    <div id="cgCenterImageInfoEditIcon$galeryIDuserForJs" class="cg-center-image-info-edit-icon">
                                    </div>
                                    <div id="cgCenterImageInfoSaveText$galeryIDuserForJs" class="cg-center-image-info-edit-text">
                                           $language_Edit
                                    </div>
                                </div>
                                <span id="cgCenterImageInfoDivTitle$galeryIDuserForJs" class="cg-center-image-info-div-title cg-center-image-info-info-separator">
                                    <div class="cg-center-image-info-icon">
                                    
                                    </div>
                                    <hr>
                                </span>
                                

                                <div class="cg-scroll-info-single-image-view cg-center-image-info-div-parent-parent">
                                    <span class="cg-top-bottom-arrow cg_hide cg_no_scroll" data-cg-gid="$galeryIDuserForJs">
                                        
                                    </span>
                                    <div id="cgCenterImageInfoDiv$galeryIDuserForJs" class="cg-center-image-info-div-container"  style="clear:both;">
                                        
                                    </div>
                                    <span class="cg-top-bottom-arrow cg_hide" data-cg-gid="$galeryIDuserForJs">
                                        
                                    </span> 
                                </div>
                                
                                <div id="cgCenterImageInfoSaveIconTextContainer$galeryIDuserForJs" class="cg-center-image-info-save-icon-text-container">
                                    <div id="cgCenterImageInfoSaveIcon$galeryIDuserForJs" class="cg-center-image-info-save-icon">                                        
                                    </div>
                                    <div id="cgCenterImageInfoSaveText$galeryIDuserForJs" class="cg-center-image-info-save-text">
                                        $language_Save                                        
                                    </div>
                                </div>
                                
                             <div id="cgCenterImageCommentsDivShowMoreInfo$galeryIDuserForJs" class="cg_hover_effect cg-center-image-comments-div-show-more cg-center-image-comments-div-show-more-info cg_hide">
                             </div>
                                
                            </div>
                            
                             <div id="cgCenterImageCommentsDivParent$galeryIDuserForJs" class="cg-center-image-comments-div-parent">

                                <span id="cgCenterImageCommentsDivTitle$galeryIDuserForJs" class="cg-center-image-info-div-title">
                                    <hr>
                                </span>                        
                                                
                                <div id="cgCenterImageCommentsDivParentParent$galeryIDuserForJs" class="cg-scroll-info-single-image-view cg-center-image-comments-div-parent-parent cg_hide">
                                   <span class="cg-top-bottom-arrow cg_hide cg_no_scroll" data-cg-gid="$galeryIDuserForJs">
                                   
                                    </span> 
                                    <div id="cgCenterImageCommentsDiv$galeryIDuserForJs" class="cg-center-image-comments-div-parent">
                                    
                                    </div>
                                    <span class="cg-top-bottom-arrow cg_hide" data-cg-gid="$galeryIDuserForJs">
                                   
                                    </span> 
                                </div>
                                            
                             <div id="cgCenterImageCommentsDivShowMoreComments$galeryIDuserForJs" class="cg_hover_effect cg-center-image-comments-div-show-more cg-center-image-comments-div-show-more-comments cg_hide">
                             </div>
                                            
                                <span class="cg-center-image-info-div-title cg-center-image-info-comments-separator cg_hide"></span>
                                                                                <div class="cg-center-image-comments-div-add-comment cg_hide" data-cg-tooltip="$language_AddComment"></div>

                                <div id="cgCenterImageCommentsDivEnter$galeryIDuserForJs" class="cg-scroll-info-single-image-view cg-center-image-comments-div-enter cg_hide" >
                                    <div class="cg-center-image-comments-user-data cg_hide">
                                        <div class="cg-center-image-comments-profile-image cg-center-show-profile-image-full cg_hide">
                                        </div>
                                        <div class="cg-center-image-comments-nickname-avatar cg_hide">
                                        </div>
                                        <div class="cg-center-image-comments-nickname-text cg_hide">
                                        </div>
                                    </div>
                                   <div id="cgCenterImageCommentsDivEnterTitleDiv$galeryIDuserForJs" data-cg-gid="$galeryIDuserForJs" class="cg-center-image-comments-div-enter-container cg-center-image-comments-div-enter-title-div cg_hide">
                                      <div class="cg-center-image-comments-div-enter-title-label-container">
                                        <label for="cgCenterImageCommentsDivEnterTitle$galeryIDuserForJs">
                                            $language_Name
                                        </label>
                                            <span class="cg-emojis-add cg-emojis-add-title" data-cg-tooltip="$language_AddAnEmoji">
                                            </span>
                                            <span class="cg-center-image-comments-div-enter-counter">100
                                            </span>
                                        </div>
                                        $emojisDiv
                                        <input type="text" maxlength="100" id="cgCenterImageCommentsDivEnterTitle$galeryIDuserForJs" class="cg-center-image-comments-div-enter-title cg-center-image-comments-div-enter-contenteditable" />
                                        <p id="cgCenterImageCommentsDivEnterTitleError$galeryIDuserForJs" class="cg_hide cg-center-image-comments-div-enter-title-error"></p>
                                    </div>
                                    <div id="cgCenterImageCommentsDivEnterTextareaDiv$galeryIDuserForJs" data-cg-gid="$galeryIDuserForJs" class="cg-center-image-comments-div-enter-container cg-center-image-comments-div-enter-textarea-div">
                                        <div class="cg-center-image-comments-div-enter-textarea-label-container">
                                        <label for="cgCenterImageCommentsDivEnterTextarea$galeryIDuserForJs">
                                            $language_Comment
                                        </label>
                                            <span class="cg-emojis-add cg-emojis-add-textarea" data-cg-tooltip="$language_AddAnEmoji">
                                            </span>
                                           <span class="cg-center-image-comments-div-enter-counter">1000
                                            </span>
                                        </div>
                                       $emojisDiv
                                        <textarea id="cgCenterImageCommentsDivEnterTextarea$galeryIDuserForJs" maxlength="1000" class="cg-center-image-comments-div-enter-textarea cg-center-image-comments-div-enter-contenteditable" ></textarea>
                                        <p id="cgCenterImageCommentsDivEnterTextareaError$galeryIDuserForJs" class="cg_hide cg-center-image-comments-div-enter-textarea-error"></p>
                                    </div>
                                    <div id="cgCenterImageCommentsDivEnterSubmitDiv$galeryIDuserForJs" class="cg-center-image-comments-div-enter-submit-div">
                                        <button id="cgCenterImageCommentsDivEnterSubmit$galeryIDuserForJs" class="cg_hover_effect cg-center-image-comments-div-enter-submit" data-cg-gid="$galeryIDuserForJs">
                                                $language_Send
                                        </button>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
HEREDOC;

echo <<<HEREDOC
                    </div>
                
        </div>
    </div>
</div>
HEREDOC;

$heredoc = <<<HEREDOC
<div id="cgCenterDivLoaderContainerBackdrop$galeryIDuserForJs" class="cgCenterDivLoaderContainerBackdrop cg_hide" data-cg-gid="$galeryIDuserForJs">
</div>
HEREDOC;
echo $heredoc;

$heredoc = <<<HEREDOC
<div id="cgCenterDivLoaderContainer$galeryIDuserForJs" class="cgCenterDivLoaderContainer cg_hide" data-cg-gid="$galeryIDuserForJs">
    <div class="cgCenterDiv cgCenterDivLoader" data-cg-gid="$galeryIDuserForJs">
        <div id="cgCenterDivChildLoader$galeryIDuserForJs" class="cgCenterDivChild" data-cg-gid="$galeryIDuserForJs">
             <div id="cgCenterDivHelperLoader$galeryIDuserForJs" class="cg-center-div-helper" data-cg-gid="$galeryIDuserForJs">
                <div id="cgCenterDivHelperHelperLoader$galeryIDuserForJs" class="cg-center-div-helper-helper" data-cg-gid="$galeryIDuserForJs">
                    <div id="cgCenterDivHelperHelperHelperLoader$galeryIDuserForJs" class="cg-center-div-helper-helper-helper" data-cg-gid="$galeryIDuserForJs">
                            <div id="cgCenterImageDivLoader$galeryIDuserForJs" class="cg-center-image-div cgHundertPercentWidth">
                                <div id="cgCenterImageParentLoader$galeryIDuserForJs" class="cg-center-image-parent" >
                                    <div id="cgCenterImageLoader$galeryIDuserForJs" class="cg-center-image cg-center-div-skeleton-box" >
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div id="cgCenterInfoDivLoader$galeryIDuserForJs" class="cg-center-info-div cg-center-div-skeleton-box-container cgHundertPercentWidth">
                        <span class="cg-center-div-skeleton-box" style="width:90%;"></span>
                        <span class="cg-center-div-skeleton-box" style="width:100%;"></span>
                        <span class="cg-center-div-skeleton-box" style="width:80%;"></span>
                        <span class="cg-center-div-skeleton-box" style="width:60%;"></span>
                        <span class="cg-center-div-skeleton-box" style="width:40%;"></span>
                    </div>
               </div>
               </div>
            </div>
        </div>
    <div  class="cgCenterDiv cgCenterDivLoader" data-cg-gid="$galeryIDuserForJs">
        <div id="cgCenterDivChildLoader$galeryIDuserForJs" class="cgCenterDivChild" data-cg-gid="$galeryIDuserForJs">
             <div id="cgCenterDivHelperLoader$galeryIDuserForJs" class="cg-center-div-helper" data-cg-gid="$galeryIDuserForJs">
                <div id="cgCenterDivHelperHelperLoader$galeryIDuserForJs" class="cg-center-div-helper-helper" data-cg-gid="$galeryIDuserForJs">
                    <div id="cgCenterDivHelperHelperHelperLoader$galeryIDuserForJs" class="cg-center-div-helper-helper-helper" data-cg-gid="$galeryIDuserForJs">
                            <div id="cgCenterImageDivLoader$galeryIDuserForJs" class="cg-center-image-div cgHundertPercentWidth">
                                <div id="cgCenterImageParentLoader$galeryIDuserForJs" class="cg-center-image-parent" >
                                    <div id="cgCenterImageLoader$galeryIDuserForJs" class="cg-center-image cg-center-div-skeleton-box" >
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div id="cgCenterInfoDivLoader$galeryIDuserForJs" class="cg-center-info-div cg-center-div-skeleton-box-container cgHundertPercentWidth">
                        <span class="cg-center-div-skeleton-box" style="width:90%;"></span>
                        <span class="cg-center-div-skeleton-box" style="width:100%;"></span>
                        <span class="cg-center-div-skeleton-box" style="width:80%;"></span>
                        <span class="cg-center-div-skeleton-box" style="width:60%;"></span>
                        <span class="cg-center-div-skeleton-box" style="width:40%;"></span>
                    </div>
               </div>
               </div>
            </div>
        </div>
    <div  class="cgCenterDiv cgCenterDivLoader" data-cg-gid="$galeryIDuserForJs">
        <div id="cgCenterDivChildLoader$galeryIDuserForJs" class="cgCenterDivChild" data-cg-gid="$galeryIDuserForJs">
             <div id="cgCenterDivHelperLoader$galeryIDuserForJs" class="cg-center-div-helper" data-cg-gid="$galeryIDuserForJs">
                <div id="cgCenterDivHelperHelperLoader$galeryIDuserForJs" class="cg-center-div-helper-helper" data-cg-gid="$galeryIDuserForJs">
                    <div id="cgCenterDivHelperHelperHelperLoader$galeryIDuserForJs" class="cg-center-div-helper-helper-helper" data-cg-gid="$galeryIDuserForJs">
                            <div id="cgCenterImageDivLoader$galeryIDuserForJs" class="cg-center-image-div cgHundertPercentWidth">
                                <div id="cgCenterImageParentLoader$galeryIDuserForJs" class="cg-center-image-parent" >
                                    <div id="cgCenterImageLoader$galeryIDuserForJs" class="cg-center-image cg-center-div-skeleton-box" >
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div id="cgCenterInfoDivLoader$galeryIDuserForJs" class="cg-center-info-div cg-center-div-skeleton-box-container cgHundertPercentWidth">
                        <span class="cg-center-div-skeleton-box" style="width:90%;"></span>
                        <span class="cg-center-div-skeleton-box" style="width:100%;"></span>
                        <span class="cg-center-div-skeleton-box" style="width:80%;"></span>
                        <span class="cg-center-div-skeleton-box" style="width:60%;"></span>
                        <span class="cg-center-div-skeleton-box" style="width:40%;"></span>
                    </div>
               </div>
               </div>
            </div>
        </div>
</div>


HEREDOC;

echo $heredoc;


?>