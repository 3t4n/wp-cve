<?php

if(!function_exists('cg_total_images_shown_in_frontend_zero')){
    function cg_total_images_shown_in_frontend_zero(){

        echo "<div id='cgTotalActivatedImagesShownInFrontendZero' class='cg_hide cg_backend_action_container cg_overflow_y_hidden cg_height_auto'><span class='cg_message_close'></span>
<p><strong>There are no checked categories with activated files.</strong><br><br>
There will be no files displayed in frontend<br>unless files for checked categories will be added and activated.<br>
</p>";
        //echo "<div style='display:flex;margin-bottom:30px;justify-content: center;'><span style='margin-right:5px;font-weight:bold;font-size:14px;'>Currently total activated images shown in frontend:</span><span style='font-weight:bold;color:red;font-size:18px;'>0</span></div>";
        echo "<span class='cg_image_action_href cg_save_categories_form cg_save_categories_form_continue_saving'><span class='cg_image_action_span' style='font-weight:bold;'>Save changes anyway</span></span></div>";

    }
}

?>