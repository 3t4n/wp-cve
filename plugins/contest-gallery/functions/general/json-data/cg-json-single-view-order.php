<?php

add_action('cg_json_single_view_order','cg_json_single_view_order');

if(!function_exists('cg_json_single_view_order')){
    function cg_json_single_view_order($GalleryID){

        global $wpdb;

        $tablename_form_input = $wpdb->prefix . "contest_gal1ery_f_input";
        $tablename_options_visual = $wpdb->prefix . "contest_gal1ery_options_visual";

        $Field1IdGalleryView = $wpdb->get_var("SELECT Field1IdGalleryView FROM $tablename_options_visual WHERE GalleryID = '$GalleryID'");

        $query = "GalleryID = '$GalleryID' AND Show_Slider = 1";

        if(!empty($Field1IdGalleryView)){
            $query = "(GalleryID = '$GalleryID' AND Show_Slider = 1) OR (id = '$Field1IdGalleryView')";
        }

        $wp_upload_dir = wp_upload_dir();

        // Formular Input für User wird ermittelt
        $selectFormInput = $wpdb->get_results( "SELECT id, Field_Type, Field_Order, Field_Content FROM $tablename_form_input WHERE $query ORDER BY Field_Order ASC" );

        foreach($selectFormInput as $row){

            $row->Field_Content = unserialize($row->Field_Content);
            $row->Field_Content = $row->Field_Content["titel"];

        }


        $file = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-single-view-order.json';
        $fp = fopen($file, 'w');
        fwrite($fp, json_encode($selectFormInput));
        fclose($fp);

    }
}
