<?php

if(!function_exists('cg_preview_images_to_delete_container')){
    function cg_preview_images_to_delete_container($GalleryID){

        echo "<div id='cgPreviewImagesToDeleteContainer' class='cg_backend_action_container cg_hide'>
<span class='cg_message_close'></span>

<div  class='cg_preview_files_container' >

</div>

<div id='cgPreviewImagesToDeleteContainerExplanation' class='cg_files_to_delete_available' >
You are about to delete the files shown above from Contest Gallery database.<br>You can delete the original file source from storage also if you like.<br>Take care of doing this. When deleted from storage it can not be restored.<br>If  from storage deleted files are in another Contest Gallery galleries<br>their database entries will be deleted there also.
<br><div id='cgPreviewImagesToDeleteContainerExplanationFrontendOptionNote'><b>note:</b> \"Delete by frontend user deleted files from storage also\" option<br>
for [cg_gallery_user id=\"".$GalleryID."'\"] shortcode can be configured in \"Contact options\"</div>
</div>

<div class='cg_preview_images_to_delete_button_container cg_files_to_delete_available' id='cgPreviewImagesToDeleteButtonContinue'>
<span class='cg_image_action_span'>Continue without deleting original file source from storage</span>
</div>

<div  class='cg_preview_images_to_delete_button_container cg_files_to_delete_available' id='cgPreviewImagesToDeleteButtonGoBackToEdit'>
<span class='cg_image_action_span'>Forgot something, let me edit again</span>
</div>

<div  class='cg_preview_images_to_delete_button_container cg_files_to_delete_available'>
<input type='checkbox' id='cgPreviewImagesToDeleteOriginalSourceDeleteConfirmCheckbox' /><label for='cgPreviewImagesToDeleteOriginalSourceDeleteConfirmCheckbox' id='cgPreviewImagesToDeleteOriginalSourceDeleteConfirmCheckboxLabel'>Please delete original source from storage also</label>
<p id='cgPreviewImagesToDeleteOriginalSourceDeleteConfirmCheckboxMessage' class='cg_hide'>Please confirm that you want to delete orignal source also (deleted files can not be restored)
<span class='cg_note cg_hide'><br><b>NOTE:</b> additional files added to a file will be also deleted and can not be restored</span>
</p>
</div>

<div  class='cg_preview_images_to_delete_button_container cg_files_to_delete_available' id='cgPreviewImagesToDeleteButtonContinueWithDeletingOriginalSource'>
<span class='cg_image_action_span'>Yes, delete original source from storage also and continue</span>
</div>

<div  class='cg_preview_images_to_delete_button_container cg_files_to_delete_not_available' id='cgPreviewImagesToDeleteButtonGoBackToEdit'  style='padding-top:20px;'>
<span class='cg_image_action_span'>Forgot something, let me edit again</span>
</div>

<div class='cg_preview_images_to_delete_button_container cg_files_to_delete_not_available' id='cgPreviewImagesToDeleteButtonContinue' style='padding-top:25px;'>
<span class='cg_image_action_span'>Delete</span>
</div>

</div>";

        echo "<div id='cgPreviewImagesToDeleteContainerFadeBackground' class='cg_hide'></div>";

    }
}

?>