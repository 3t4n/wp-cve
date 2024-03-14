<?php

add_action('cg_json_upload_form_info_data_files_new','cg_json_upload_form_info_data_files_new');
if(!function_exists('cg_json_upload_form_info_data_files_new')){
    function cg_json_upload_form_info_data_files_new($GalleryID,$pidsArray=[]){

        global $wpdb;

        $tablename = $wpdb->prefix . "contest_gal1ery";
        $tablename_form_input = $wpdb->prefix . "contest_gal1ery_f_input";
        $tablename_options_visual = $wpdb->prefix . "contest_gal1ery_options_visual";
        $tablename_entries = $wpdb->prefix . "contest_gal1ery_entries";

        $wp_upload_dir = wp_upload_dir();
        $jsonUpload = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json';

        if(!is_dir($jsonUpload)){
            mkdir($jsonUpload,0755,true);
        }

        // collect all input ids which can be visible somehow in frontend
        $inputIdsArray = [];
        $optionsVisual = $wpdb->get_row("SELECT * FROM $tablename_options_visual WHERE GalleryID = $GalleryID");
        if(!empty($optionsVisual->Field1IdGalleryView)){$inputIdsArray[]=$optionsVisual->Field1IdGalleryView;}
        if(!empty($optionsVisual->Field2IdGalleryView)){$inputIdsArray[]=$optionsVisual->Field2IdGalleryView;}
        if(!empty($optionsVisual->Field3IdGalleryView)){$inputIdsArray[]=$optionsVisual->Field3IdGalleryView;}

        $inputs = $wpdb->get_results("SELECT * FROM $tablename_form_input WHERE GalleryID = $GalleryID");
        $frontendInputProperties = ['Show_Slider','WatermarkPosition','SubTitle','ThirdTitle','EcommerceTitle','EcommerceDescription'];
        $fieldTitlesArray = [];
        $dateFieldsIdsAndFormatArray = array();

        foreach($inputs as $input){
            foreach ($frontendInputProperties as $property){
                if(!empty($input->$property)){
                    $inputIdsArray[]=$input->id;
                    $Field_Content = unserialize($input->Field_Content);
                    $fieldTitlesArray[$input->id] = $Field_Content["titel"];
                    if($input->Field_Type=='date-f'){
                        $dateFieldsIdsAndFormatArray[$input->id] = $Field_Content["format"];
                    }
                }
            }
        }

	    if(!empty($pidsArray)){
		    $collect = '';
		    foreach ($pidsArray as $pid){
			    if(!$collect){
				    $collect .= "pid = $pid";
			    }else{
				    $collect .= " or pid = $pid";
			    }
		    }
		    $entries = $wpdb->get_results("SELECT * FROM $tablename_entries WHERE GalleryID = $GalleryID && ($collect)");
	    }else{
            $entries = $wpdb->get_results("SELECT * FROM $tablename_entries WHERE GalleryID = $GalleryID");
	    }

        $arrayDataForImage = array();

        foreach($entries as $row){

            if(in_array($row->f_input_id,$inputIdsArray)!==false){
                if(empty($arrayDataForImage[$row->pid])){
                    $arrayDataForImage[$row->pid] = array();
                }

                $arrayDataForImage[$row->pid][$row->f_input_id] = array();

                $arrayDataForImage[$row->pid][$row->f_input_id]['field-type'] = $row->Field_Type;
                $arrayDataForImage[$row->pid][$row->f_input_id]['field-title'] = isset($fieldTitlesArray[$row->f_input_id]) ? $fieldTitlesArray[$row->f_input_id] : '';

                if(!empty($row->Field_Type == 'comment-f')){// <<< check field type here!!!

                    //var_dump($row->Long_Text);
                    $arrayDataForImage[$row->pid][$row->f_input_id]['field-content'] = $row->Long_Text;

                }else if(!empty($row->InputDate) && $row->InputDate!='0000-00-00 00:00:00'){

                    $newDateTimeString = '';

                    try {

                        if(!empty($dateFieldsIdsAndFormatArray[$row->f_input_id])){// might be hidden or deactivated this why check here

                            $dtFormat = $dateFieldsIdsAndFormatArray[$row->f_input_id];

                            $dtFormat = str_replace('YYYY','Y',$dtFormat);
                            $dtFormat = str_replace('MM','m',$dtFormat);
                            $dtFormat = str_replace('DD','d',$dtFormat);

                            $newDateTimeObject = DateTime::createFromFormat("Y-m-d H:i:s",$row->InputDate);

                            if(is_object($newDateTimeObject)){
                                $newDateTimeString = $newDateTimeObject->format($dtFormat);
                            }

                        }

                    }catch (Exception $e) {

                        $newDateTimeString = '';

                    }

                    $arrayDataForImage[$row->pid][$row->f_input_id]['field-content'] = $newDateTimeString;

                }else{
                    $arrayDataForImage[$row->pid][$row->f_input_id]['field-content'] = $row->Short_Text;
                }

                if(!is_dir($wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-info')){
                    mkdir($wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-info',0755,true);
                }

                $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-info/image-info-'.$row->pid.'.json';
                file_put_contents($jsonFile, json_encode($arrayDataForImage[$row->pid]));

            }else{
                $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-info/image-info-'.$row->pid.'.json';
                if(file_exists($jsonFile)){
                    unlink($jsonFile);
                }
            }
        }

    }
}

