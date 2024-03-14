<?php

if(!function_exists('cg_multiple_files_for_post_container')){
    function cg_multiple_files_for_post_container(){

        echo "<div id='cgMultipleFilesForPostContainer' class='cg_backend_action_container cg_hide'>
<span class='cg_message_close'></span>";
        echo "<div class='cg_preview_files_container'>";
        echo "</div>";
        echo '<div  id="cg_multiple_files_file_for_post_submit_button_container" >
            <div  id="cg_multiple_files_file_for_post_submit_button" class="cg_backend_button_gallery_action">Close and go save</div>
        </div>';
        echo "</div>";

    }
}

?>