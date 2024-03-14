<?php

add_action('cg_json_upload_form','cg_json_upload_form');

if(!function_exists('cg_json_upload_form')){

    function cg_json_upload_form($GalleryID){

        global $wpdb;

        $tablename_form_input = $wpdb->prefix . "contest_gal1ery_f_input";

        $wp_upload_dir = wp_upload_dir();
        $jsonUpload = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json';

        if(!is_dir($jsonUpload)){
            mkdir($jsonUpload,0755,true);
        }


        $formDataForJson = $wpdb->get_results("SELECT * FROM $tablename_form_input WHERE GalleryID = $GalleryID ORDER BY id DESC");
        //   $singleViewOrderDataJson = $wpdb->get_results("SELECT id, Field_Type, Field_Order, Field_Content FROM $tablename_form_input WHERE GalleryID = $GalleryID AND Show_Slider = 1 ORDER BY Field_Order DESC");

        $formDataArray = array();

        foreach($formDataForJson as $object){

            $jsonKey = $object->id;

            $formDataArray[$jsonKey] = array();
            $formDataArray[$jsonKey]['GalleryID'] = $object->GalleryID;
            $formDataArray[$jsonKey]['Field_Type'] = $object->Field_Type;
            $formDataArray[$jsonKey]['Field_Order'] = $object->Field_Order;
            $formDataArray[$jsonKey]['Version'] = $object->Version;

            if(!empty($object->ReCaKey)){
                $formDataArray[$jsonKey]['ReCaKey'] = $object->ReCaKey;
            }

            if(!empty($object->ReCaLang)){
                $formDataArray[$jsonKey]['ReCaLang'] = $object->ReCaLang;
            }

            $formDataArray[$jsonKey]['Field_Content'] = array();
            $fieldContent = unserialize($object->Field_Content);

            foreach($fieldContent as $key => $value){
                $formDataArray[$jsonKey]['Field_Content'][$key] = $value;
            }

            $formDataArray[$jsonKey]['Show_Slider'] = $object->Show_Slider;
            $formDataArray[$jsonKey]['Use_as_URL'] = $object->Use_as_URL;
            $formDataArray[$jsonKey]['WatermarkPosition'] = $object->WatermarkPosition;
            $formDataArray[$jsonKey]['Active'] = $object->Active;

        }

        /*    foreach($singleViewOrderDataJson as $data){

                $data->Field_Content = unserialize($data->Field_Content);
                $data->Field_Content = $data->Field_Content["titel"];

            }*/


        $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-form-upload.json';
        $fp = fopen($jsonFile, 'w');
        fwrite($fp, json_encode($formDataArray));
        fclose($fp);

        /*    $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-single-view-order.json';
            $fp = fopen($jsonFile, 'w');
            fwrite($fp, json_encode($singleViewOrderDataJson));
            fclose($fp);*/

    }
}
