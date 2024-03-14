<?php


$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconVoteUndoneGalleryViewBase64'])){
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconVoteUndoneGalleryViewBase64'].'") no-repeat center;background-size:cover;';
    $base64Value = $jsonOptions['icons']['iconVoteUndoneGalleryViewBase64'];
    $hideBase64Remove = '';
}

echo "<div class='cg_icon_upload_input_container'>";
echo "<div>";
echo 'Vote undone gallery view<br/>';
echo '<input type="file" name="iconVoteUndoneGalleryView" class="cg_icon_upload_input_button" >';
echo '<input type="hidden" name="iconVoteUndoneGalleryViewBase64" class="cg_icon_upload_input_button_base_64" value="'.$base64Value.'" >';
echo "</div>";
    echo "<div class='cg_icon_upload_input_normal' id='iconVoteUndoneGalleryViewNormal'>";
        echo "<div>";
        echo "normal";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
    echo "<div class='cg_icon_upload_input_yours' id='iconVoteUndoneGalleryViewYours'>";
        echo "<div>";
        echo "yours";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image' style='$styleBackground'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
    echo "<div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>";
        echo "<<< remove";
    echo "</div>";
echo "</div>";

$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconVoteDoneGalleryViewBase64'])){
    $base64Value = $jsonOptions['icons']['iconVoteDoneGalleryViewBase64'];
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconVoteDoneGalleryViewBase64'].'") no-repeat center;background-size:cover;';
    $hideBase64Remove = '';
}

echo "<div class='cg_icon_upload_input_container cg_margin-top-10'>";
echo "<div>";
echo 'Vote done gallery view<br/>';
echo '<input type="file" name="iconVoteDoneGalleryView"  class="cg_icon_upload_input_button">';
echo '<input type="hidden" name="iconVoteDoneGalleryViewBase64" class="cg_icon_upload_input_button_base_64" value="'.$base64Value.'" >';
echo "</div>";
    echo "<div class='cg_icon_upload_input_normal' id='iconVoteDoneGalleryViewNormal'>";
        echo "<div>";
        echo "normal";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
    echo "<div class='cg_icon_upload_input_yours' id='iconVoteDoneGalleryViewYours'>";
        echo "<div>";
        echo "yours";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image' style='$styleBackground'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
echo "<div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>";
echo "<<< remove";
echo "</div>";
echo "</div>";

$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconVoteHalfStarGalleryViewBase64'])){
    $base64Value = $jsonOptions['icons']['iconVoteHalfStarGalleryViewBase64'];
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconVoteHalfStarGalleryViewBase64'].'") no-repeat center;background-size:cover;';
    $hideBase64Remove = '';
}

echo "<div class='cg_icon_upload_input_container cg_margin-top-10'>";
echo "<div>";
echo 'Vote half star gallery view<br/>';
echo '<input type="file" name="iconVoteHalfStarGalleryView"  class="cg_icon_upload_input_button">';
echo '<input type="hidden" name="iconVoteHalfStarGalleryViewBase64" class="cg_icon_upload_input_button_base_64" value="'.$base64Value.'" >';
echo "</div>";
    echo "<div class='cg_icon_upload_input_normal' id='iconVoteHalfStarGalleryViewNormal'>";
        echo "<div>";
        echo "normal";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
    echo "<div class='cg_icon_upload_input_yours' id='iconVoteHalfStarGalleryViewYours'>";
        echo "<div>";
        echo "yours";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image' style='$styleBackground'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
echo "<div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>";
echo "<<< remove";
echo "</div>";
echo "</div>";


$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconVoteUndoneImageViewBase64'])){
    $base64Value = $jsonOptions['icons']['iconVoteUndoneImageViewBase64'];
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconVoteUndoneImageViewBase64'].'") no-repeat center;background-size:cover;';
    $hideBase64Remove = '';
}

echo "<div class='cg_icon_upload_input_container cg_margin-top-10'>";
echo "<div>";
echo 'Vote undone image view<br/>';
echo '<input type="file" name="iconVoteUndoneImageView"  class="cg_icon_upload_input_button">';
echo '<input type="hidden" name="iconVoteUndoneImageViewBase64" class="cg_icon_upload_input_button_base_64" value="'.$base64Value.'" >';
echo "</div>";
    echo "<div class='cg_icon_upload_input_normal' id='iconVoteUndoneImageViewNormal'>";
        echo "<div>";
        echo "normal";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
    echo "<div class='cg_icon_upload_input_yours' id='iconVoteUndoneImageViewYours'>";
        echo "<div>";
        echo "yours";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image' style='$styleBackground'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
echo "<div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>";
echo "<<< remove";
echo "</div>";
echo "</div>";

$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconVoteDoneImageViewBase64'])){
    $base64Value = $jsonOptions['icons']['iconVoteDoneImageViewBase64'];
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconVoteDoneImageViewBase64'].'") no-repeat center;background-size:cover;';
    $hideBase64Remove = '';
}

echo "<div class='cg_icon_upload_input_container cg_margin-top-10'>";
echo "<div>";
echo 'Vote done image view<br/>';
echo '<input type="file" name="iconVoteDoneImageView"  class="cg_icon_upload_input_button">';
echo '<input type="hidden" name="iconVoteDoneImageViewBase64" class="cg_icon_upload_input_button_base_64" value="'.$base64Value.'" >';

echo "</div>";
    echo "<div class='cg_icon_upload_input_normal' id='iconVoteDoneImageViewNormal'>";
        echo "<div>";
        echo "normal";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
    echo "<div class='cg_icon_upload_input_yours' id='iconVoteDoneImageViewYours'>";
        echo "<div>";
        echo "yours";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image' style='$styleBackground'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
echo "<div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>";
echo "<<< remove";
echo "</div>";
echo "</div>";

$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconVoteHalfStarImageViewBase64'])){
    $base64Value = $jsonOptions['icons']['iconVoteHalfStarImageViewBase64'];
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconVoteHalfStarImageViewBase64'].'") no-repeat center;background-size:cover;';
    $hideBase64Remove = '';
}

echo "<div class='cg_icon_upload_input_container cg_margin-top-10'>";
echo "<div>";
echo 'Vote half star image view<br/>';
echo '<input type="file" name="iconVoteHalfStarImageView"  class="cg_icon_upload_input_button">';
echo '<input type="hidden" name="iconVoteHalfStarImageViewBase64" class="cg_icon_upload_input_button_base_64" value="'.$base64Value.'" >';

echo "</div>";
    echo "<div class='cg_icon_upload_input_normal' id='iconVoteHalfStarImageViewNormal'>";
        echo "<div>";
        echo "normal";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
    echo "<div class='cg_icon_upload_input_yours' id='iconVoteHalfStarImageViewYours'>";
        echo "<div>";
        echo "yours";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image' style='$styleBackground'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
echo "<div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>";
echo "<<< remove";
echo "</div>";
echo "</div>";


$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconVoteFiveStarsPercentageOverviewDoneImageViewBase64'])){
    $base64Value = $jsonOptions['icons']['iconVoteFiveStarsPercentageOverviewDoneImageViewBase64'];
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconVoteFiveStarsPercentageOverviewDoneImageViewBase64'].'") no-repeat center;background-size:cover;';
    $hideBase64Remove = '';
}

echo "<div class='cg_icon_upload_input_container cg_margin-top-10'>";
echo "<div>";
echo 'Multiple stars sum overview<br/>';
echo '<input type="file" name="iconVoteFiveStarsPercentageOverviewDoneImageView"  class="cg_icon_upload_input_button">';
echo '<input type="hidden" name="iconVoteFiveStarsPercentageOverviewDoneImageViewBase64" class="cg_icon_upload_input_button_base_64" value="'.$base64Value.'" >';

echo "</div>";
    echo "<div class='cg_icon_upload_input_normal' id='iconVoteFiveStarsPercentageOverviewDoneImageViewNormal'>";
        echo "<div>";
        echo "normal";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
    echo "<div class='cg_icon_upload_input_yours' id='iconVoteFiveStarsPercentageOverviewDoneImageViewYours'>";
        echo "<div>";
        echo "yours";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image' style='$styleBackground'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
echo "<div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>";
echo "<<< remove";
echo "</div>";
echo "</div>";

$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconVoteRemoveImageViewBase64'])){
    $base64Value = $jsonOptions['icons']['iconVoteRemoveImageViewBase64'];
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconVoteRemoveImageViewBase64'].'") no-repeat center;background-size:cover;';
    $hideBase64Remove = '';
}

echo "<div class='cg_icon_upload_input_container cg_margin-top-10'>";
echo "<div>";
echo 'Delete vote<br/>';
echo '<input type="file" name="iconVoteRemoveImageView"  class="cg_icon_upload_input_button">';
echo '<input type="hidden" name="iconVoteRemoveImageViewBase64" class="cg_icon_upload_input_button_base_64" value="'.$base64Value.'" >';

echo "</div>";
    echo "<div class='cg_icon_upload_input_normal' id='iconVoteRemoveImageViewNormal'>";
        echo "<div>";
        echo "normal";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
    echo "<div class='cg_icon_upload_input_yours' id='iconVoteRemoveImageViewYours'>";
        echo "<div>";
        echo "yours";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image' style='$styleBackground'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
echo "<div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>";
echo "<<< remove";
echo "</div>";
echo "</div>";

$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconVoteRemoveGalleryOnlyViewBase64'])){
    $base64Value = $jsonOptions['icons']['iconVoteRemoveGalleryOnlyViewBase64'];
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconVoteRemoveGalleryOnlyViewBase64'].'") no-repeat center;background-size:cover;';
    $hideBase64Remove = '';
}

echo "<div class='cg_icon_upload_input_container cg_margin-top-10'>";
echo "<div>";
echo 'Delete vote if only gallery view is activated<br/>';
echo '<input type="file" name="iconVoteRemoveGalleryOnlyView"  class="cg_icon_upload_input_button">';
echo '<input type="hidden" name="iconVoteRemoveGalleryOnlyViewBase64" class="cg_icon_upload_input_button_base_64" value="'.$base64Value.'" >';

echo "</div>";
    echo "<div class='cg_icon_upload_input_normal' id='iconVoteRemoveGalleryOnlyViewNormal'>";
        echo "<div>";
        echo "normal";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
    echo "<div class='cg_icon_upload_input_yours' id='iconVoteRemoveGalleryOnlyViewYours'>";
        echo "<div>";
        echo "yours";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image' style='$styleBackground'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
echo "<div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>";
echo "<<< remove";
echo "</div>";
echo "</div>";


$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconCommentUndoneGalleryViewBase64'])){
    $base64Value = $jsonOptions['icons']['iconCommentUndoneGalleryViewBase64'];
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconCommentUndoneGalleryViewBase64'].'") no-repeat center;background-size:cover;';
    $hideBase64Remove = '';
}

echo "<div class='cg_icon_upload_input_container cg_margin-top-10'>";
echo "<div>";
echo 'Comment undone gallery view<br/>';
echo '<input type="file" name="iconCommentUndoneGalleryView"  class="cg_icon_upload_input_button">';
echo '<input type="hidden" name="iconCommentUndoneGalleryViewBase64" class="cg_icon_upload_input_button_base_64" value="'.$base64Value.'" >';

echo "</div>";
    echo "<div class='cg_icon_upload_input_normal' id='iconCommentUndoneGalleryViewNormal'>";
        echo "<div>";
        echo "normal";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
    echo "<div class='cg_icon_upload_input_yours' id='iconCommentUndoneGalleryViewYours'>";
        echo "<div>";
        echo "yours";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image' style='$styleBackground'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
echo "<div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>";
echo "<<< remove";
echo "</div>";
echo "</div>";


$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconCommentDoneGalleryViewBase64'])){
    $base64Value = $jsonOptions['icons']['iconCommentDoneGalleryViewBase64'];
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconCommentDoneGalleryViewBase64'].'") no-repeat center;background-size:cover;';
    $hideBase64Remove = '';
}

echo "<div class='cg_icon_upload_input_container cg_margin-top-10'>";
echo "<div>";
echo 'Comment done gallery view<br/>';
echo '<input type="file" name="iconCommentDoneGalleryView"  class="cg_icon_upload_input_button">';
echo '<input type="hidden" name="iconCommentDoneGalleryViewBase64" class="cg_icon_upload_input_button_base_64" value="'.$base64Value.'" >';

echo "</div>";
    echo "<div class='cg_icon_upload_input_normal' id='iconCommentDoneGalleryViewNormal'>";
        echo "<div>";
        echo "normal";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
    echo "<div class='cg_icon_upload_input_yours' id='iconCommentDoneGalleryViewYours'>";
        echo "<div>";
        echo "yours";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image' style='$styleBackground'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
echo "<div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>";
echo "<<< remove";
echo "</div>";
echo "</div>";

$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconCommentUndoneImageViewBase64'])){
    $base64Value = $jsonOptions['icons']['iconCommentUndoneImageViewBase64'];
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconCommentUndoneImageViewBase64'].'") no-repeat center;background-size:cover;';
    $hideBase64Remove = '';
}

echo "<div class='cg_icon_upload_input_container cg_margin-top-10'>";
echo "<div>";
echo 'Comment undone image view<br/>';
echo '<input type="file" name="iconCommentUndoneImageView"  class="cg_icon_upload_input_button">';
echo '<input type="hidden" name="iconCommentUndoneImageViewBase64" class="cg_icon_upload_input_button_base_64" value="'.$base64Value.'" >';

echo "</div>";
    echo "<div class='cg_icon_upload_input_normal' id='iconCommentUndoneImageViewNormal'>";
        echo "<div>";
        echo "normal";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
    echo "<div class='cg_icon_upload_input_yours' id='iconCommentUndoneImageViewYours'>";
        echo "<div>";
        echo "yours";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image' style='$styleBackground'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
echo "<div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>";
echo "<<< remove";
echo "</div>";
echo "</div>";


$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconCommentDoneImageViewBase64'])){
    $base64Value = $jsonOptions['icons']['iconCommentDoneImageViewBase64'];
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconCommentDoneImageViewBase64'].'") no-repeat center;background-size:cover;';
    $hideBase64Remove = '';
}

echo "<div class='cg_icon_upload_input_container cg_margin-top-10'>";
echo "<div>";
echo 'Comment done image view<br/>';
echo '<input type="file" name="iconCommentDoneImageView"  class="cg_icon_upload_input_button">';
echo '<input type="hidden" name="iconCommentDoneImageViewBase64" class="cg_icon_upload_input_button_base_64" value="'.$base64Value.'" >';

echo "</div>";
    echo "<div class='cg_icon_upload_input_normal' id='iconCommentDoneImageViewNormal'>";
        echo "<div>";
        echo "normal";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
    echo "<div class='cg_icon_upload_input_yours' id='iconCommentDoneImageViewYours'>";
        echo "<div>";
        echo "yours";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image' style='$styleBackground'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
echo "<div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>";
echo "<<< remove";
echo "</div>";
echo "</div>";

$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconCommentAddImageViewBase64'])){
    $base64Value = $jsonOptions['icons']['iconCommentAddImageViewBase64'];
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconCommentAddImageViewBase64'].'") no-repeat center;background-size:cover;';
    $hideBase64Remove = '';
}

echo "<div class='cg_icon_upload_input_container cg_margin-top-10'>";
echo "<div>";
echo 'Comment add image view<br/>';
echo '<input type="file" name="iconCommentAddImageView"  class="cg_icon_upload_input_button">';
echo '<input type="hidden" name="iconCommentAddImageViewBase64" class="cg_icon_upload_input_button_base_64" value="'.$base64Value.'" >';

echo "</div>";
    echo "<div class='cg_icon_upload_input_normal' id='iconCommentAddImageViewNormal'>";
        echo "<div>";
        echo "normal";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
    echo "<div class='cg_icon_upload_input_yours' id='iconCommentAddImageViewYours'>";
        echo "<div>";
        echo "yours";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image' style='$styleBackground'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
echo "<div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>";
echo "<<< remove";
echo "</div>";
echo "</div>";

$base64Value = '';
$styleBackground = '';
$hideBase64Remove = 'cg_hide';
if(!empty($jsonOptions['icons']['iconInfoImageViewBase64'])){
    $base64Value = $jsonOptions['icons']['iconInfoImageViewBase64'];
    $styleBackground = 'background: url("'.$jsonOptions['icons']['iconInfoImageViewBase64'].'") no-repeat center;background-size:cover;';
    $hideBase64Remove = '';
}

echo "<div class='cg_icon_upload_input_container cg_margin-top-10'>";
echo "<div>";
echo 'Info icon image view<br/>';
echo '<input type="file" name="iconInfoImageView"  class="cg_icon_upload_input_button">';
echo '<input type="hidden" name="iconInfoImageViewBase64" class="cg_icon_upload_input_button_base_64" value="'.$base64Value.'" >';

echo "</div>";
    echo "<div class='cg_icon_upload_input_normal' id='iconInfoImageViewNormal'>";
        echo "<div>";
        echo "normal";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
    echo "<div class='cg_icon_upload_input_yours' id='iconInfoImageViewYours'>";
        echo "<div>";
        echo "yours";
        echo "</div>";
        echo "<div>";
            echo "<div class='cg_icon_upload_input_image' style='$styleBackground'>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
echo "<div class='cg_icon_upload_input_yours_remove $hideBase64Remove'>";
echo "<<< remove";
echo "</div>";
echo "</div>";


