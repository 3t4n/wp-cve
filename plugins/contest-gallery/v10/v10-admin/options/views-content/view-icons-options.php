<?php

echo <<<HEREDOC
<div class='cg_view_container'>
HEREDOC;


echo <<<HEREDOC
<div class='cg_view_options_rows_container'>
         <p class='cg_view_options_rows_container_title'>Generally all frontend icons are base64 encoded and placed via CSS. Most important can be changed here. Others can be changed via CSS. CSS support service will be not provided.
<br><span class="cg_view_options_rows_container_title_note" style="padding-top: 10px; display: inline-block;"><span class="cg_font_weight_bold">NOTE:</span> Maximum 64px width and height and less then 10kb</span></p>
HEREDOC;

$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconVoteUndoneGalleryViewBase64'])){
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconVoteUndoneGalleryViewBase64'].'") no-repeat center;background-size:cover;';
    $base64Value = $jsonOptions['icons']['iconVoteUndoneGalleryViewBase64'];
    $hideBase64Remove = '';
}

echo <<<HEREDOC
<div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width' >
                    <div class='cg_view_option_title'>
                        <p>Vote undone gallery view</p>
                    </div>
                    <div class='cg_view_option_upload'>
                        <div class='cg_icon_upload_input_container'>                        
                            <div>
                                <div>
                                normal
                                </div>
                                 <div>
                                yours
                                </div>
                                 <div>
                                </div>
                            </div>
                            <div>
                               <div class='cg_icon_upload_input_normal' id='iconVoteUndoneGalleryViewNormal'>
                                    <div>
                                        <div class='cg_icon_upload_input_image'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_yours' id='iconVoteUndoneGalleryViewYours'>
                                    <div>
                                        <div class='cg_icon_upload_input_image' style='$styleBackground'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_button_container' >
                                    <input type="file" name="iconVoteUndoneGalleryView" class="cg_icon_upload_input_button" >
                                    <input type="hidden" name="iconVoteUndoneGalleryViewBase64" class="cg_icon_upload_input_button_base_64" value="$base64Value" >
                                </div>
                            </div>
                            <div>
                                <div>
                                </div>
                                <div>
                                    <div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>
                                        remove
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
         </div>
HEREDOC;


$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconVoteDoneGalleryViewBase64'])){
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconVoteDoneGalleryViewBase64'].'") no-repeat center;background-size:cover;';
    $base64Value = $jsonOptions['icons']['iconVoteDoneGalleryViewBase64'];
    $hideBase64Remove = '';
}

echo <<<HEREDOC
<div class='cg_view_options_row'>
                <div class='cg_view_option cg_border_top_none cg_view_option_full_width' >
                    <div class='cg_view_option_title'>
                        <p>Vote done gallery view</p>
                    </div>
                    <div class='cg_view_option_upload'>
                        <div class='cg_icon_upload_input_container'>                        
                            <div>
                                <div>
                                normal
                                </div>
                                 <div>
                                yours
                                </div>
                                 <div>
                                </div>
                            </div>
                            <div>
                               <div class='cg_icon_upload_input_normal' id='iconVoteDoneGalleryViewNormal'>
                                    <div>
                                        <div class='cg_icon_upload_input_image'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_yours' id='iconVoteUndoneGalleryViewYours'>
                                    <div>
                                        <div class='cg_icon_upload_input_image' style='$styleBackground'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_button_container' >
                                    <input type="file" name="iconVoteDoneGalleryView" class="cg_icon_upload_input_button" >
                                    <input type="hidden" name="iconVoteDoneGalleryViewBase64" class="cg_icon_upload_input_button_base_64" value="$base64Value" >
                                </div>
                            </div>
                            <div>
                                <div>
                                </div>
                                <div>
                                    <div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>
                                        remove
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
         </div>
HEREDOC;


$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconVoteHalfStarGalleryViewBase64'])){
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconVoteHalfStarGalleryViewBase64'].'") no-repeat center;background-size:cover;';
    $base64Value = $jsonOptions['icons']['iconVoteHalfStarGalleryViewBase64'];
    $hideBase64Remove = '';
}

echo <<<HEREDOC
<div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>Vote half star gallery view</p>
                    </div>
                    <div class='cg_view_option_upload'>
                        <div class='cg_icon_upload_input_container'>                        
                            <div>
                                <div>
                                normal
                                </div>
                                 <div>
                                yours
                                </div>
                                 <div>
                                </div>
                            </div>
                            <div>
                               <div class='cg_icon_upload_input_normal' id='iconVoteHalfStarGalleryViewNormal'>
                                    <div>
                                        <div class='cg_icon_upload_input_image'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_yours' id='iconVoteHalfStarGalleryViewYours'>
                                    <div>
                                        <div class='cg_icon_upload_input_image' style='$styleBackground'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_button_container' >
                                    <input type="file" name="iconVoteHalfStarGalleryView" class="cg_icon_upload_input_button" >
                                    <input type="hidden" name="iconVoteHalfStarGalleryViewBase64" class="cg_icon_upload_input_button_base_64" value="$base64Value" >
                                </div>
                            </div>
                            <div>
                                <div>
                                </div>
                                <div>
                                    <div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>
                                        remove
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
         </div>
HEREDOC;


$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconVoteUndoneImageViewBase64'])){
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconVoteUndoneImageViewBase64'].'") no-repeat center;background-size:cover;';
    $base64Value = $jsonOptions['icons']['iconVoteUndoneImageViewBase64'];
    $hideBase64Remove = '';
}

echo <<<HEREDOC
<div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>Vote undone entry view</p>
                    </div>
                    <div class='cg_view_option_upload'>
                        <div class='cg_icon_upload_input_container'>                        
                            <div>
                                <div>
                                normal
                                </div>
                                 <div>
                                yours
                                </div>
                                 <div>
                                </div>
                            </div>
                            <div>
                               <div class='cg_icon_upload_input_normal' id='iconVoteUndoneImageViewNormal'>
                                    <div>
                                        <div class='cg_icon_upload_input_image'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_yours' id='iconVoteUndoneImageViewYours'>
                                    <div>
                                        <div class='cg_icon_upload_input_image' style='$styleBackground'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_button_container' >
                                    <input type="file" name="iconVoteUndoneImageView" class="cg_icon_upload_input_button" >
                                    <input type="hidden" name="iconVoteUndoneImageViewBase64" class="cg_icon_upload_input_button_base_64" value="$base64Value" >
                                </div>
                            </div>
                            <div>
                                <div>
                                </div>
                                <div>
                                    <div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>
                                        remove
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
         </div>
HEREDOC;


$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconVoteDoneImageViewBase64'])){
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconVoteDoneImageViewBase64'].'") no-repeat center;background-size:cover;';
    $base64Value = $jsonOptions['icons']['iconVoteDoneImageViewBase64'];
    $hideBase64Remove = '';
}

echo <<<HEREDOC
<div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>Vote done entry view</p>
                    </div>
                    <div class='cg_view_option_upload'>
                        <div class='cg_icon_upload_input_container'>                        
                            <div>
                                <div>
                                normal
                                </div>
                                 <div>
                                yours
                                </div>
                                 <div>
                                </div>
                            </div>
                            <div>
                               <div class='cg_icon_upload_input_normal' id='iconVoteDoneImageViewNormal'>
                                    <div>
                                        <div class='cg_icon_upload_input_image'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_yours' id='iconVoteDoneImageViewYours'>
                                    <div>
                                        <div class='cg_icon_upload_input_image' style='$styleBackground'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_button_container' >
                                    <input type="file" name="iconVoteDoneImageView" class="cg_icon_upload_input_button" >
                                    <input type="hidden" name="iconVoteDoneImageViewBase64" class="cg_icon_upload_input_button_base_64" value="$base64Value" >
                                </div>
                            </div>
                            <div>
                                <div>
                                </div>
                                <div>
                                    <div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>
                                        remove
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
         </div>
HEREDOC;

$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconVoteHalfStarImageViewBase64'])){
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconVoteHalfStarImageViewBase64'].'") no-repeat center;background-size:cover;';
    $base64Value = $jsonOptions['icons']['iconVoteHalfStarImageViewBase64'];
    $hideBase64Remove = '';
}

echo <<<HEREDOC
<div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>Vote half star entry view</p>
                    </div>
                    <div class='cg_view_option_upload'>
                        <div class='cg_icon_upload_input_container'>                        
                            <div>
                                <div>
                                normal
                                </div>
                                 <div>
                                yours
                                </div>
                                 <div>
                                </div>
                            </div>
                            <div>
                               <div class='cg_icon_upload_input_normal' id='iconVoteHalfStarImageViewNormal'>
                                    <div>
                                        <div class='cg_icon_upload_input_image'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_yours' id='iconVoteHalfStarImageViewYours'>
                                    <div>
                                        <div class='cg_icon_upload_input_image' style='$styleBackground'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_button_container' >
                                    <input type="file" name="iconVoteHalfStarImageView" class="cg_icon_upload_input_button" >
                                    <input type="hidden" name="iconVoteHalfStarImageViewBase64" class="cg_icon_upload_input_button_base_64" value="$base64Value" >
                                </div>
                            </div>
                            <div>
                                <div>
                                </div>
                                <div>
                                    <div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>
                                        remove
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
         </div>
HEREDOC;

$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconVoteFiveStarsPercentageOverviewDoneImageViewBase64'])){
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconVoteFiveStarsPercentageOverviewDoneImageViewBase64'].'") no-repeat center;background-size:cover;';
    $base64Value = $jsonOptions['icons']['iconVoteFiveStarsPercentageOverviewDoneImageViewBase64'];
    $hideBase64Remove = '';
}

echo <<<HEREDOC
<div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>Multiple stars sum overview</p>
                    </div>
                    <div class='cg_view_option_upload'>
                        <div class='cg_icon_upload_input_container'>                        
                            <div>
                                <div>
                                normal
                                </div>
                                 <div>
                                yours
                                </div>
                                 <div>
                                </div>
                            </div>
                            <div>
                               <div class='cg_icon_upload_input_normal' id='iconVoteFiveStarsPercentageOverviewDoneImageViewNormal'>
                                    <div>
                                        <div class='cg_icon_upload_input_image'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_yours' id='iconVoteFiveStarsPercentageOverviewDoneImageViewYours'>
                                    <div>
                                        <div class='cg_icon_upload_input_image' style='$styleBackground'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_button_container' >
                                    <input type="file" name="iconVoteFiveStarsPercentageOverviewDoneImageView" class="cg_icon_upload_input_button" >
                                    <input type="hidden" name="iconVoteFiveStarsPercentageOverviewDoneImageViewBase64" class="cg_icon_upload_input_button_base_64" value="$base64Value" >
                                </div>
                            </div>
                            <div>
                                <div>
                                </div>
                                <div>
                                    <div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>
                                        remove
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
         </div>
HEREDOC;

$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconVoteRemoveImageViewBase64'])){
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconVoteRemoveImageViewBase64'].'") no-repeat center;background-size:cover;';
    $base64Value = $jsonOptions['icons']['iconVoteRemoveImageViewBase64'];
    $hideBase64Remove = '';
}

echo <<<HEREDOC
<div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>Delete vote</p>
                    </div>
                    <div class='cg_view_option_upload'>
                        <div class='cg_icon_upload_input_container'>                        
                            <div>
                                <div>
                                normal
                                </div>
                                 <div>
                                yours
                                </div>
                                 <div>
                                </div>
                            </div>
                            <div>
                               <div class='cg_icon_upload_input_normal' id='iconVoteRemoveImageViewNormal'>
                                    <div>
                                        <div class='cg_icon_upload_input_image'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_yours' id='iconVoteRemoveImageViewYours'>
                                    <div>
                                        <div class='cg_icon_upload_input_image' style='$styleBackground'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_button_container' >
                                    <input type="file" name="iconVoteRemoveImageView" class="cg_icon_upload_input_button" >
                                    <input type="hidden" name="iconVoteRemoveImageViewBase64" class="cg_icon_upload_input_button_base_64" value="$base64Value" >
                                </div>
                            </div>
                            <div>
                                <div>
                                </div>
                                <div>
                                    <div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>
                                        remove
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
         </div>
HEREDOC;

$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconVoteRemoveGalleryOnlyViewBase64'])){
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconVoteRemoveGalleryOnlyViewBase64'].'") no-repeat center;background-size:cover;';
    $base64Value = $jsonOptions['icons']['iconVoteRemoveGalleryOnlyViewBase64'];
    $hideBase64Remove = '';
}

echo <<<HEREDOC
<div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>Delete vote if only gallery view is activated</p>
                    </div>
                    <div class='cg_view_option_upload'>
                        <div class='cg_icon_upload_input_container'>                        
                            <div>
                                <div>
                                normal
                                </div>
                                 <div>
                                yours
                                </div>
                                 <div>
                                </div>
                            </div>
                            <div>
                               <div class='cg_icon_upload_input_normal' id='iconVoteRemoveGalleryOnlyViewNormal'>
                                    <div>
                                        <div class='cg_icon_upload_input_image'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_yours' id='iconVoteRemoveGalleryOnlyViewYours'>
                                    <div>
                                        <div class='cg_icon_upload_input_image' style='$styleBackground'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_button_container' >
                                    <input type="file" name="iconVoteRemoveGalleryOnlyView" class="cg_icon_upload_input_button" >
                                    <input type="hidden" name="iconVoteRemoveGalleryOnlyViewBase64" class="cg_icon_upload_input_button_base_64" value="$base64Value" >
                                </div>
                            </div>
                            <div>
                                <div>
                                </div>
                                <div>
                                    <div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>
                                        remove
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
         </div>
HEREDOC;

$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconCommentUndoneGalleryViewBase64'])){
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconCommentUndoneGalleryViewBase64'].'") no-repeat center;background-size:cover;';
    $base64Value = $jsonOptions['icons']['iconCommentUndoneGalleryViewBase64'];
    $hideBase64Remove = '';
}

echo <<<HEREDOC
<div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>Comment undone gallery view</p>
                    </div>
                    <div class='cg_view_option_upload'>
                        <div class='cg_icon_upload_input_container'>                        
                            <div>
                                <div>
                                normal
                                </div>
                                 <div>
                                yours
                                </div>
                                 <div>
                                </div>
                            </div>
                            <div>
                               <div class='cg_icon_upload_input_normal' id='iconCommentUndoneGalleryViewNormal'>
                                    <div>
                                        <div class='cg_icon_upload_input_image'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_yours' id='iconCommentUndoneGalleryViewYours'>
                                    <div>
                                        <div class='cg_icon_upload_input_image' style='$styleBackground'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_button_container' >
                                    <input type="file" name="iconCommentUndoneGalleryView" class="cg_icon_upload_input_button" >
                                    <input type="hidden" name="iconCommentUndoneGalleryViewBase64" class="cg_icon_upload_input_button_base_64" value="$base64Value" >
                                </div>
                            </div>
                            <div>
                                <div>
                                </div>
                                <div>
                                    <div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>
                                        remove
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
         </div>
HEREDOC;

$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconCommentDoneGalleryViewBase64'])){
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconCommentDoneGalleryViewBase64'].'") no-repeat center;background-size:cover;';
    $base64Value = $jsonOptions['icons']['iconCommentDoneGalleryViewBase64'];
    $hideBase64Remove = '';
}

echo <<<HEREDOC
<div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>Comment done gallery view</p>
                    </div>
                    <div class='cg_view_option_upload'>
                        <div class='cg_icon_upload_input_container'>                        
                            <div>
                                <div>
                                normal
                                </div>
                                 <div>
                                yours
                                </div>
                                 <div>
                                </div>
                            </div>
                            <div>
                               <div class='cg_icon_upload_input_normal' id='iconCommentDoneGalleryViewNormal'>
                                    <div>
                                        <div class='cg_icon_upload_input_image'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_yours' id='iconCommentDoneGalleryViewYours'>
                                    <div>
                                        <div class='cg_icon_upload_input_image' style='$styleBackground'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_button_container' >
                                    <input type="file" name="iconCommentDoneGalleryView" class="cg_icon_upload_input_button" >
                                    <input type="hidden" name="iconCommentDoneGalleryViewBase64" class="cg_icon_upload_input_button_base_64" value="$base64Value" >
                                </div>
                            </div>
                            <div>
                                <div>
                                </div>
                                <div>
                                    <div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>
                                        remove
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
         </div>
HEREDOC;

$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconCommentUndoneImageViewBase64'])){
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconCommentUndoneImageViewBase64'].'") no-repeat center;background-size:cover;';
    $base64Value = $jsonOptions['icons']['iconCommentUndoneImageViewBase64'];
    $hideBase64Remove = '';
}

echo <<<HEREDOC
<div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>Comment undone entry view</p>
                    </div>
                    <div class='cg_view_option_upload'>
                        <div class='cg_icon_upload_input_container'>                        
                            <div>
                                <div>
                                normal
                                </div>
                                 <div>
                                yours
                                </div>
                                 <div>
                                </div>
                            </div>
                            <div>
                               <div class='cg_icon_upload_input_normal' id='iconCommentUndoneImageViewNormal'>
                                    <div>
                                        <div class='cg_icon_upload_input_image'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_yours' id='iconCommentUndoneImageViewYours'>
                                    <div>
                                        <div class='cg_icon_upload_input_image' style='$styleBackground'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_button_container' >
                                    <input type="file" name="iconCommentUndoneImageView" class="cg_icon_upload_input_button" >
                                    <input type="hidden" name="iconCommentUndoneImageViewBase64" class="cg_icon_upload_input_button_base_64" value="$base64Value" >
                                </div>
                            </div>
                            <div>
                                <div>
                                </div>
                                <div>
                                    <div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>
                                        remove
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
         </div>
HEREDOC;

$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconCommentDoneImageViewBase64'])){
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconCommentDoneImageViewBase64'].'") no-repeat center;background-size:cover;';
    $base64Value = $jsonOptions['icons']['iconCommentDoneImageViewBase64'];
    $hideBase64Remove = '';
}

echo <<<HEREDOC
<div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>Comment done entry view</p>
                    </div>
                    <div class='cg_view_option_upload'>
                        <div class='cg_icon_upload_input_container'>                        
                            <div>
                                <div>
                                normal
                                </div>
                                 <div>
                                yours
                                </div>
                                 <div>
                                </div>
                            </div>
                            <div>
                               <div class='cg_icon_upload_input_normal' id='iconCommentDoneImageViewNormal'>
                                    <div>
                                        <div class='cg_icon_upload_input_image'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_yours' id='iconCommentDoneImageViewYours'>
                                    <div>
                                        <div class='cg_icon_upload_input_image' style='$styleBackground'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_button_container' >
                                    <input type="file" name="iconCommentDoneImageView" class="cg_icon_upload_input_button" >
                                    <input type="hidden" name="iconCommentDoneImageViewBase64" class="cg_icon_upload_input_button_base_64" value="$base64Value" >
                                </div>
                            </div>
                            <div>
                                <div>
                                </div>
                                <div>
                                    <div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>
                                        remove
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
         </div>
HEREDOC;

$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconCommentAddImageViewBase64'])){
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconCommentAddImageViewBase64'].'") no-repeat center;background-size:cover;';
    $base64Value = $jsonOptions['icons']['iconCommentAddImageViewBase64'];
    $hideBase64Remove = '';
}

echo <<<HEREDOC
<div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>Comment add entry view</p>
                    </div>
                    <div class='cg_view_option_upload'>
                        <div class='cg_icon_upload_input_container'>                        
                            <div>
                                <div>
                                normal
                                </div>
                                 <div>
                                yours
                                </div>
                                 <div>
                                </div>
                            </div>
                            <div>
                               <div class='cg_icon_upload_input_normal' id='iconCommentAddImageViewNormal'>
                                    <div>
                                        <div class='cg_icon_upload_input_image'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_yours' id='iconCommentAddImageViewYours'>
                                    <div>
                                        <div class='cg_icon_upload_input_image' style='$styleBackground'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_button_container' >
                                    <input type="file" name="iconCommentAddImageView" class="cg_icon_upload_input_button" >
                                    <input type="hidden" name="iconCommentAddImageViewBase64" class="cg_icon_upload_input_button_base_64" value="$base64Value" >
                                </div>
                            </div>
                            <div>
                                <div>
                                </div>
                                <div>
                                    <div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>
                                        remove
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
         </div>
HEREDOC;

$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconInfoImageViewBase64'])){
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconInfoImageViewBase64'].'") no-repeat center;background-size:cover;';
    $base64Value = $jsonOptions['icons']['iconInfoImageViewBase64'];
    $hideBase64Remove = '';
}

echo <<<HEREDOC
<div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>Info icon entry view</p>
                    </div>
                    <div class='cg_view_option_upload'>
                        <div class='cg_icon_upload_input_container'>                        
                            <div>
                                <div>
                                normal
                                </div>
                                 <div>
                                yours
                                </div>
                                 <div>
                                </div>
                            </div>
                            <div>
                               <div class='cg_icon_upload_input_normal' id='iconInfoImageViewNormal'>
                                    <div>
                                        <div class='cg_icon_upload_input_image'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_yours' id='iconInfoImageViewYours'>
                                    <div>
                                        <div class='cg_icon_upload_input_image' style='$styleBackground'>
                                        </div>
                                    </div>
                                </div>
                                <div class='cg_icon_upload_input_button_container' >
                                    <input type="file" name="iconInfoImageView" class="cg_icon_upload_input_button" >
                                    <input type="hidden" name="iconInfoImageViewBase64" class="cg_icon_upload_input_button_base_64" value="$base64Value" >
                                </div>
                            </div>
                            <div>
                                <div>
                                </div>
                                <div>
                                    <div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>
                                        remove
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
         </div>
HEREDOC;

echo <<<HEREDOC
</div>
HEREDOC;

echo <<<HEREDOC
</div>
HEREDOC;


