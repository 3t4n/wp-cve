<?php

if(!function_exists('cg_add_fields_pressed_after_content_modification')){
    function cg_add_fields_pressed_after_content_modification($GalleryID){
        echo "<div id='cgAddFieldsPressedAfterContentModification' class='cg_hide cg_height_auto cg_backend_action_container  cg_overflow_y_hidden'><span class='cg_message_close'></span><p>There were changes done without saving</p><a class='cg_image_action_href' href=\"?page=".cg_get_version()."/index.php&define_upload=true&option_id=$GalleryID\"><span class='cg_image_action_span'>Continue without saving?</span>
</a></div>";
    }
}

?>