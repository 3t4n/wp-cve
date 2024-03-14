<?php

$ShowTextUntilAnImageAdded = '';
if(!empty($options['general']['ShowTextUntilAnImageAdded'])){
    $ShowTextUntilAnImageAdded = contest_gal1ery_convert_for_html_output($options['general']['ShowTextUntilAnImageAdded']);
}

$heredoc = <<<HEREDOC
<div id="cgNoImagesFound$galeryIDuserForJs" class='cg_hide $cgFeControlsStyle cg-no-images-found-container'>
    <div class='cg-no-images-found'>
    $language_NoEntriesFound
    </div>
</div>
HEREDOC;
echo $heredoc;

$heredoc = <<<HEREDOC
<div id="cgShowTextUntilAnImageAdded$galeryIDuserForJs" class='cg_hide $cgFeControlsStyle cg-show-text-until-an-image-added'>
$ShowTextUntilAnImageAdded
</div>
HEREDOC;

if($isUserGallery == true && $is_user_logged_in){// then show definetly for user gallery
    echo $heredoc;
} else if(!empty($isShowGallery) && $isUserGallery == false){// if not logged in not user gallery and Reg
echo $heredoc;
}

?>