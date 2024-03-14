<?php
add_action('cg_create_fb_html','cg_create_fb_html');
if(!function_exists('cg_create_fb_html')){
    function cg_create_fb_html($object,$GalleryID,$DataShare,$DataClass,$DataLayout){

       include(__DIR__.'/../../v10/v10-admin/gallery/change-gallery/4_2_fb-creation.php');

    }
}