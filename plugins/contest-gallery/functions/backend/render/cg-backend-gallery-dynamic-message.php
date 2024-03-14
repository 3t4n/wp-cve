<?php

if(!function_exists('cg_backend_gallery_dynamic_message')){
    function cg_backend_gallery_dynamic_message(){
        echo "<div id='cgBackendGalleryDynamicMessage' class='cg_do_not_remove_when_ajax_load cg_do_not_remove_when_main_empty cg_hide cg_notification_message_dynamic cg_background_drop_content'><span class='cg_message_close'></span><p class='cg_notification_message_dynamic_content'>There were changes done without saving</p></div>";
    }
}

?>