<?php

if(!function_exists('cg_sort_gallery_files_container')){
    function cg_sort_gallery_files_container($GalleryID, $galleryDBversion){

        echo "<div id='cgSortGalleryFilesContainer' class='cg_backend_action_container cg_hide'>
<span class='cg_message_close'></span>";

            echo "<div id='cgSortGalleryFilesExplanation'>For <b>\"Custom\"</b> sorting. Available here in backend<br>and can be activated for frontend in  <b>\"Gallery view options\"</b><br>
for <b>cg_gallery, cg_gallery_no_voting</b> and <b>cg_gallery_winner</b> shortcodes.".(((float)($galleryDBversion)<19) ? "<br><b>Please consider \"Preselect order on page load option\" also.</b>" : '')."
<br>New added entries appears at the top.</div>";
            echo "<div class='cg-lds-dual-ring-gallery-hide cg_hide'></div>";

// sort files hidden form
        ?>
        <form enctype="multipart/form-data" id="cg_sort_files_form" class="cg_hide" action='<?php echo '?page="'.cg_get_version().'"/index.php'; ?>' method='POST'>
            <input type='hidden' name='cgGalleryHash' value='<?php echo md5(wp_salt( 'auth').'---cngl1---'.$GalleryID);?>'>
            <input type='hidden' name='GalleryID' value='<?php echo $GalleryID;?>'>
            <input type='hidden' name='action' value='post_cg_gallery_sort_files'>
        </form>

        <div  id="cg_sort_files_form_submit_button_container" class="cg_hide">
            <div  id="cg_sort_files_form_submit_button" class="cg_backend_button_gallery_action">Save sorting</div>
        </div>
        <?php
        echo "</div>";

    }
}

?>