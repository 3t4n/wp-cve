<?php

global $cgGlobalGoogleRecaptchaRendered;

include ('gallery-upload-form-options.php');

echo "<div   id='mainCGdivUploadForm$galeryIDuserForJs' class='mainCGdivUploadForm $mainCGdivShowUncollapsed $mainCGdivContactEntriesOnly $mainCGdivUploadNotRequired $mainCGdivUploadFormBulk $mainCGdivUploadFormAdditionalFiles mainCGdivUploadFormAjax cg_hide $cgFeControlsStyle $BorderRadiusClassUploadForm' data-cg-gid='$galeryIDuserForJs' data-cg-real-gid='$galeryID'>";

$jsonUploadFormSortedByFieldOrder = array();

foreach($jsonContactForm as $fieldId => $field){
    $jsonUploadFormSortedByFieldOrder[$field['Field_Order']] = $field;
    $jsonUploadFormSortedByFieldOrder[$field['Field_Order']]['id'] = $fieldId;
}
ksort($jsonUploadFormSortedByFieldOrder);

echo "<div id='cgCloseUploadForm$galeryIDuserForJs' class='cg_hover_effect cg-close-upload-form $cgFeControlsStyle' data-cg-gid='$galeryIDuserForJs' data-cg-tooltip='$language_Close'>";
    echo "</div>";

echo "<div id='cgMinimizeUploadForm$galeryIDuserForJs' class='cg-minimize-upload-form cg_hide $cgFeControlsStyle' data-cg-gid='$galeryIDuserForJs' data-cg-tooltip='$language_MinimizeUploadForm'>";
    echo "</div>";

echo "<div id='cgRefreshUploadForm$galeryIDuserForJs' class='cg_hover_effect cg-refresh-upload-form cg_hide $cgFeControlsStyle' data-cg-gid='$galeryIDuserForJs' data-cg-tooltip='$language_FurtherUpload'>";
echo "</div>";

echo "<div id='mainCGdivUploadFormLdsDualRing$galeryIDuserForJs' class='mainCGdivUploadFormLdsDualRing cg-lds-dual-ring-div-gallery-hide $cgFeControlsStyle cg_hide'><div class='cg-lds-dual-ring-gallery-hide $cgFeControlsStyle'></div></div>";

echo "<div class='cg_skeleton_loader_on_page_load_div cg_skeleton_loader_on_page_load_div_form_submit cg_hide'>";
    echo "<div class='cg_skeleton_loader_on_page_load_container' style='margin-bottom: 10px;'>";
        echo "<div class='cg_skeleton_loader_on_page_load' style='height:30px;width:25%;'></div>";
    echo "</div>";
    echo "<div class='cg_skeleton_loader_on_page_load_container' style='margin-bottom: 10px;' >";
        echo "<div class='cg_skeleton_loader_on_page_load' style='height:30px;width:50%;'></div>";
    echo "</div>";
    echo "<div class='cg_skeleton_loader_on_page_load_container' style='margin-bottom: 10px;' >";
        echo "<div class='cg_skeleton_loader_on_page_load' style='height:30px;width:75%;'></div>";
    echo "</div>";
    echo "<div class='cg_skeleton_loader_on_page_load_container' style='margin-bottom: 10px;'  >";
        echo "<div class='cg_skeleton_loader_on_page_load' style='height:30px;width:100%;'></div>";
    echo "</div>";
    echo "<div class='cg_skeleton_loader_on_page_load_container' >";
        echo "<div class='cg_skeleton_loader_on_page_load' style='height:30px;width:60%;'></div>";
    echo "</div>";
echo "</div>";

echo "<div id='mainCGdivUploadProgress$galeryIDuserForJs' class='cg_hide cg-div-upload-progress $cgFeControlsStyle'></div>";

echo "<div id='mainCGdivUploadFormResult$galeryIDuserForJs' class='mainCGdivUploadFormResult cg_hide' data-cg-gid='$galeryIDuserForJs'>";

echo "<div id='cgGalleryUploadConfirmationText$galeryIDuserForJs' class='cgGalleryUploadConfirmationText' data-cg-gid='$galeryIDuserForJs'>";

if(!empty($isOnlyContactForm)){
    $inputOptionsSQL = $wpdb->get_row( "SELECT * FROM $contest_gal1ery_options_input WHERE GalleryID='$galeryID'");
    // important! without_nl2br here!
    echo contest_gal1ery_convert_for_html_output_without_nl2br( $inputOptionsSQL->Confirmation_Text);
}/*else if(!empty($isOnlyContactForm)){
    $ConOptConfirmTextAfterContact = $wpdb->get_var( "SELECT ConOptConfirmTextAfterContact FROM $tablename_contact_options WHERE GalleryID='$galeryID'");
    // important! without_nl2br here!
    echo contest_gal1ery_convert_for_html_output_without_nl2br($ConOptConfirmTextAfterContact);
}*/else{
    // important! without_nl2br here!
    echo contest_gal1ery_convert_for_html_output_without_nl2br($options['pro']['GalleryUploadConfirmationText']);
}

echo "</div>";
echo "</div>";

echo "<div id='mainCGdivUploadFormResultFailed$galeryIDuserForJs' class='mainCGdivUploadFormResultFailed cg_hide' data-cg-gid='$galeryIDuserForJs'>";

echo "</div>";

echo "<div  style='visibility: hidden;'  id='mainCGdivUploadFormContainer$galeryIDuserForJs' class='mainCGdivUploadFormContainer $cgFeControlsStyle' data-cg-gid='$galeryIDuserForJs'>";

if((time()>=$ContestEndTime && $ContestEnd==1) OR $ContestEnd==2){
    echo "<div class='cg_photo_contest_is_over'>";
    echo "<p>$language_ThePhotoContestIsOver</p>";
    echo "</div>";
} else if(time() < $ContestStartTime && $ContestStart==1){
    echo "<div class='cg_photo_contest_has_not_started_yet'>";
    echo "<p>$language_ThePhotoContestHasNotStartedYet</p>";
    echo "</div>";
}else{
    $cgShowGoogleSignInAnyCase = true;

    if($options['pro']['RegUserUploadOnly']==1 && is_user_logged_in()==false){
        echo contest_gal1ery_convert_for_html_output($options['pro']['RegUserUploadOnlyText']);
    } else{

        if($options['pro']['RegUserUploadOnly']==2 && !isset($_COOKIE['contest-gal1ery-'.$galeryID.'-upload'])){
            cg_set_cookie($galeryID,'upload'); // since 19.1.4 will be set via PHP if not exists
        }

        if($isNormalVersion){
            echo "<div id='cgGalleryUploadNormalVersionBulkNote$galeryIDuserForJs' class='cgGalleryUploadNormalVersionBulkNote' data-cg-gid='$galeryIDuserForJs'>";
            echo "<span style='color:red;'><b>NOTE:</b></span> since plugin version 16.0.0 bulk upload is only available in PRO version";
            echo "</div>";
        }

        if(empty($isOnlyContactForm)){// for in gallery upload form
            echo "<div id='cgGalleryUploadFormTextBefore$galeryIDuserForJs' class='cg_gallery_upload_text_before_and_after' data-cg-gid='$galeryIDuserForJs'>";
                echo contest_gal1ery_convert_for_html_output($options['pro']['GalleryUploadTextBefore']);
            echo "</div>";
        }

        echo "<form action='' method='post' class='cgGalleryUploadForm' id='cgGalleryUploadForm$galeryIDuserForJs' data-cg-gid='$galeryIDuserForJs' enctype='multipart/form-data' name='cgGalleryUpload' novalidate >";
        echo "<input type='hidden' name='galeryIDuser' value='$galeryIDuser'>";

        $isBulkUploadDivRendered = false;
        $isBulkUploadCaptchaDivRendered = false;
        $fieldSimpleRecaptcha = [];
        $fieldGoogleRecaptcha = [];
        $imageFieldFieldTitle = '';

/*        echo "<pre>";
        print_r($jsonUploadFormSortedByFieldOrder);
        echo "</pre>";*/

        for($i = 0; $i < $maxUpload; $i++){

            // just to get $imageFieldFieldTitle
            foreach($jsonUploadFormSortedByFieldOrder as $fieldOrder => $field){
                if ($field['Field_Type'] == 'image-f'){
                    $Field_Content = $field['Field_Content'];
                    $imageFieldFieldTitle = contest_gal1ery_convert_for_html_output_without_nl2br($Field_Content['titel']);
                    break;
                }
            }

            // for unique ids when multiple shortcodes are inserted on same page
            $uniqueIdAddition = substr(md5(uniqid(rand().$galeryIDuser, true)),0,5).$i;

            if($ActivateBulkUpload==1 && $maxUpload >= 1 && !$isBulkUploadDivRendered){
                $uniqueIdAdditionBulk = substr(md5(uniqid(rand().$galeryIDuser, true)),0,5).$i;
                echo "<div class='cg_form_div_image_step cg_form_div_image_step_upload_bulk' data-cg-gid='$galeryIDuserForJs'  data-cg-additional-files-count='".$AdditionalFilesCount."' >";
                    echo "<div class='$fileUploadHide  cg_form_div_image_container'>";
                        echo "<div class='cg_form_div cg_form_div_image_upload cg_form_div_image_upload_bulk'
                            ondragover='cgJsClass.gallery.upload.events.ondragover(event)' ondragleave='cgJsClass.gallery.upload.events.ondragleave(event)' ondrop='cgJsClass.gallery.upload.events.ondrop(event)'
                        >";
                        echo "<p class='cg_input_error cg_hide cg_input_error cg_hide_image_upload'></p>";// Fehlermeldung erscheint hier
                        echo "<label class='$fileUploadHide cg_input_image_upload_label' for='cg_input_image_upload_id_in_gallery$galeryIDuser$uniqueIdAdditionBulk' data-cg-gid='$galeryIDuserForJs' >$imageFieldFieldTitle</label>";
                echo "<input type='file' class='cg_input_image_upload_id_in_gallery_upload_bulk cg_input_image_upload_input' data-cg-gid='$galeryIDuserForJs' id='cg_input_image_upload_id_in_gallery$galeryIDuser$uniqueIdAdditionBulk' name='data[]' multiple />";// Content Feld
                        echo "<div class='cg_form_div_image_upload_preview cg_hide' data-cg-gid='$galeryIDuserForJs' ></div>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
                $isBulkUploadDivRendered = true;
            }

            echo "<div class='cg_form_div_image_step cg_form_div_image_step_single_image $cg_form_div_image_step_single_image_multiple $ActivateBulkUploadCgHide'  data-cg-index='$i'  data-cg-gid='$galeryIDuserForJs'  data-cg-additional-files-count='".$AdditionalFilesCount."' >";

                echo "<div class='$fileUploadHide  cg_form_div_image_container' data-cg-index='$i'>";

                foreach($jsonUploadFormSortedByFieldOrder as $fieldOrder => $field){

                    if ($field['Field_Type'] == 'image-f'){

                        $Field_Order = $field['Field_Order'];
                        $Field_Content = $field['Field_Content'];

                $cgFormDivAlternativeFileUpload = '';
                if(!empty($Field_Content['alternative-file-type']) && $Field_Content['alternative-file-type']!='img'){
                    $cgFormDivAlternativeFileUpload = 'cg_form_div_alternative_file_upload';
                }

                 echo "<div class='cg_form_div cg_form_div_image_upload $cgFormDivAlternativeFileUpload' 
                            $ondragSingleUpload 
                        data-cg-gid='$galeryIDuserForJs'>";
                        if(!empty($options['pro']['AdditionalFiles'])){
                            echo "<input type='file' name='data[$Field_Order][]' class='$fileUploadHide cg_input_image_upload_id_in_gallery cg_input_image_upload_input' data-cg-gid='$galeryIDuserForJs' id='cg_input_image_upload_id_in_gallery$galeryIDuserForJs$uniqueIdAddition' name='data[]' multiple />";// Content Feld
                            echo "<label class='$fileUploadHide  cg_input_image_upload_label' data-cg-gid='$galeryIDuserForJs'  for='cg_input_image_upload_id_in_gallery$galeryIDuserForJs$uniqueIdAddition'>".$imageFieldFieldTitle."</label>";
                            echo "<p class='cg_input_error cg_hide cg_hide_image_upload'></p>";// Fehlermeldung erscheint hier
                            echo "<div class='cg_form_div_additional_file_container cg_form_div_additional_file_container_to_clone cg_hide' data-cg-additional-files-order='1'>";
                            echo "<div class='cg_form_div_image_drag cg_hide' data-cg-gid='$galeryIDuserForJs'></div>";
                            echo "<div class='cg_form_div_image_position cg_hide' data-cg-gid='$galeryIDuserForJs'>1</div>";
                        }
                        echo "<div class='cg_hover_effect cg_form_div_image_remove cg_hide' data-cg-gid='$galeryIDuserForJs' >";

                        echo "</div>";

                        echo "<p class='cg_input_error cg_hide cg_hide_image_upload'></p>";// Fehlermeldung erscheint hier
                        if(empty($options['pro']['AdditionalFiles'])){
                            echo "<input type='file' name='data[$Field_Order][]' class='$fileUploadHide cg_input_image_upload_id_in_gallery cg_input_image_upload_input' data-cg-gid='$galeryIDuserForJs' id='cg_input_image_upload_id_in_gallery$galeryIDuserForJs$uniqueIdAddition' name='data[]' />";// Content Feld
                            echo "<label class='$fileUploadHide  cg_input_image_upload_label' data-cg-gid='$galeryIDuserForJs'  for='cg_input_image_upload_id_in_gallery$galeryIDuserForJs$uniqueIdAddition'>".$imageFieldFieldTitle."</label>";
                        }
                        echo "<div class='cg_form_div_image_upload_preview cg_hide' data-cg-gid='$galeryIDuserForJs' ></div>";
                        echo "</div>";

                if(!empty($Field_Content['alternative-file-type']) && $Field_Content['alternative-file-type']!='img' && empty($Field_Content['alternative-file-preview-hide'])){
                    // not used in the moment 04.05.2022
/*                    echo "<div class='cg_form_div cg_form_div_image_upload cg_form_div_image_alternative_file_preview_upload cg_hide'
                            $ondragSingleUpload
                        >";
                        echo "<p class='cg_input_error cg_hide cg_hide_image_upload'></p>";// Fehlermeldung erscheint hier
                        echo "<label class='cg_input_image_upload_label' for='cg_input_image_upload_id_in_gallery$galeryIDuser$uniqueIdAddition'>".$Field_Content['alternative-file-title']."</label>";
                        echo "<input type='file' name='data[$Field_Order][]' class='cg_input_image_upload_id_in_gallery cg_input_image_upload_input' data-cg-gid='$galeryIDuserForJs' id='cg_input_image_upload_id_in_gallery$galeryIDuser$uniqueIdAddition' name='data[]' />";// Content Feld
                        echo "<div class='cg_form_div_image_upload_preview cg_hide'></div>";
                    echo "</div>";*/
                }

                        break;

                    }

                }

            if(!empty($options['pro']['AdditionalFiles'])){
                    echo "<div class='cg_form_div_additional_file_container cg_form_div_additional_file_container_add cg_hide'>";
                    $uniqueIdAdditionAdditionalFiles = substr(md5(uniqid(rand().$galeryIDuser."1", true)),0,5).$i;
                    echo "<input type='file' name='data[$Field_Order][]' class='$fileUploadHide cg_input_image_upload_id_in_gallery cg_input_image_upload_input cg_input_image_upload_input_additional_files cg_input_image_upload_input_additional_files_multiple' data-cg-gid='$galeryIDuserForJs' id='cg_input_image_upload_id_in_gallery$galeryIDuserForJs$uniqueIdAdditionAdditionalFiles' name='data[]' multiple/>";// Content Feld
                    echo "<input type='file' name='data[$Field_Order][]' class='$fileUploadHide cg_input_image_upload_id_in_gallery cg_input_image_upload_input cg_input_image_upload_input_additional_files cg_input_image_upload_input_additional_files_single' data-cg-gid='$galeryIDuserForJs' name='data[]' />";// Content Feld
                    echo "<label class='$fileUploadHide  cg_input_image_upload_label' data-cg-gid='$galeryIDuserForJs'  for='cg_input_image_upload_id_in_gallery$galeryIDuserForJs$uniqueIdAdditionAdditionalFiles'>".$imageFieldFieldTitle."</label>";
                    echo "</div>";
                echo "</div>";
            }

            echo "</div>";

            $cg_hidden_element = 'cg_hidden_element';

            if(!$isShowCollapsed){
                $cg_hidden_element = '';
            }

            echo "<div class='cg_form_div_inputs_container $cg_hidden_element'  data-cg-index='$i'>";

            foreach($jsonUploadFormSortedByFieldOrder as $fieldOrder => $field){

                    if ($field['Field_Type']=='fbt-f' && $field['Active'] == '1'){

                        $fieldId = $field['id'];
                        $Field_Order = $field['Field_Order'];
                        $Field_Content = $field['Field_Content'];
                        $checkIfNeed = $Field_Content['mandatory'];

                        $necessary = ($Field_Content['mandatory']=='on') ? '*' : '' ;
                        $checkIfNeed = ($Field_Content['mandatory']=='on') ? 'on' : '' ;

                        $minLength = $Field_Content['min-char'];
                        $maxLength = $Field_Content['max-char'];

                        echo "<div class='cg_form_div'>";
                        echo "<label for='cg_upload_form_field_in_gallery$fieldId$uniqueIdAddition'>".sanitize_text_field(contest_gal1ery_convert_for_html_output_without_nl2br($Field_Content['titel']))." $necessary</label>";
                        echo "<input type='hidden' name='form_input[$Field_Order][]' value='fbt'><input type='hidden' name='form_input[$Field_Order][]' value='$fieldId'>";// Formart und FormfeldID hidden
                        echo "<input type='hidden' name='form_input[$Field_Order][]'  value='$Field_Order'>";// Fieldorder will be provided
                        echo "<input type='text' placeholder='".contest_gal1ery_convert_for_html_output_without_nl2br($Field_Content['content'])."'  maxlength='$maxLength' class='cg_input_text_class cg_upload_form_field_in_gallery' data-cg-gid='$galeryIDuserForJs' id='cg_upload_form_field_in_gallery$fieldId$uniqueIdAddition' value='' name='form_input[$Field_Order][]'>";// Content Feld, l�nge wird �berpr�ft
                        echo "<input type='hidden' class='minsize' value='$minLength'>"; // Pr�fen minimale Anzahl zeichen
                        echo "<input type='hidden' class='maxsize' value='$maxLength'>"; // Pr�fen maximale Anzahl zeichen
                        echo "<input type='hidden' class='cg_form_required' value='$checkIfNeed'>";// Pr�fen ob Pflichteingabe
                        echo "<p class='cg_input_error cg_hide'></p>";// Fehlermeldung erscheint hier
                        echo "</div>";
                    }

                    if ($field['Field_Type']=='text-f' && $field['Active'] == '1'){

                        $fieldId = $field['id'];
                        $Field_Order = $field['Field_Order'];
                        $Field_Content = $field['Field_Content'];
                        $checkIfNeed = $Field_Content['mandatory'];

                        $necessary = ($Field_Content['mandatory']=='on') ? '*' : '' ;
                        $checkIfNeed = ($Field_Content['mandatory']=='on') ? 'on' : '' ;

                        $minLength = $Field_Content['min-char'];
                        $maxLength = $Field_Content['max-char'];

                        echo "<div class='cg_form_div'>";
                        echo "<label for='cg_upload_form_field_in_gallery$fieldId$uniqueIdAddition'>".sanitize_text_field(contest_gal1ery_convert_for_html_output_without_nl2br($Field_Content['titel']))." $necessary</label>";
                        echo "<input type='hidden' name='form_input[$Field_Order][]' value='nf'><input type='hidden' name='form_input[$Field_Order][]' value='$fieldId'>";// Formart und FormfeldID hidden
                        echo "<input type='hidden' name='form_input[$Field_Order][]'  value='$Field_Order'>";// Fieldorder will be provided
                        echo "<input type='text' placeholder='".contest_gal1ery_convert_for_html_output_without_nl2br($Field_Content['content'])."'  maxlength='$maxLength' class='cg_input_text_class cg_upload_form_field_in_gallery' data-cg-gid='$galeryIDuserForJs' id='cg_upload_form_field_in_gallery$fieldId$uniqueIdAddition' value='' name='form_input[$Field_Order][]'>";// Content Feld, l�nge wird �berpr�ft
                        echo "<input type='hidden' class='minsize' value='$minLength'>"; // Pr�fen minimale Anzahl zeichen
                        echo "<input type='hidden' class='maxsize' value='$maxLength'>"; // Pr�fen maximale Anzahl zeichen
                        echo "<input type='hidden' class='cg_form_required' value='$checkIfNeed' >";// Pr�fen ob Pflichteingabe
                        echo "<p class='cg_input_error cg_hide'></p>";// Fehlermeldung erscheint hier
                        echo "</div>";
                    }

                    if ($field['Field_Type']=='date-f' && $field['Active'] == '1'){

                        $fieldId = $field['id'];
                        $Field_Order = $field['Field_Order'];
                        $Field_Content = $field['Field_Content'];
                        $format = $field['Field_Content']['format'];
                        $checkIfNeed = $Field_Content['mandatory'];

                        $necessary = ($Field_Content['mandatory']=='on') ? '*' : '' ;
                        $checkIfNeed = ($Field_Content['mandatory']=='on') ? 'on' : '' ;

                        echo "<div class='cg_form_div'>";
                        echo "<label for='cg_upload_form_field_in_gallery$fieldId$uniqueIdAddition'>".sanitize_text_field(contest_gal1ery_convert_for_html_output_without_nl2br($Field_Content['titel']))." $necessary</label>";
                        echo "<input type='hidden' name='form_input[$Field_Order][]' value='dt'><input type='hidden' name='form_input[$Field_Order][]' value='$fieldId'>";// Formart und FormfeldID hidden
                        echo "<input type='hidden' name='form_input[$Field_Order][]'  value='$Field_Order'>";// Fieldorder will be provided
                        echo "<input type='text' autocomplete='off' class='cg_upload_form_field_in_gallery cg_input_date_class' data-cg-gid='$galeryIDuserForJs' id='cg_upload_form_field_in_gallery$fieldId$uniqueIdAddition' value='' name='form_input[$Field_Order][]'>";// Content Feld, l�nge wird �berpr�ft
                        echo "<input type='hidden' class='cg_date_format' value='$format'>";// Fieldorder will be provided
                        echo "<input type='hidden' class='cg_form_required' value='$checkIfNeed' >";// Pr�fen ob Pflichteingabe
                        echo "<p class='cg_input_error cg_hide'></p>";// Fehlermeldung erscheint hier
                        echo "</div>";
                    }

                    if ($field['Field_Type']=='url-f' && $field['Active'] == '1'){

                        $fieldId = $field['id'];
                        $Field_Order = $field['Field_Order'];
                        $Field_Content = $field['Field_Content'];
                        $checkIfNeed = $Field_Content['mandatory'];

                        $necessary = ($Field_Content['mandatory']=='on') ? '*' : '' ;
                        $checkIfNeed = ($Field_Content['mandatory']=='on') ? 'on' : '' ;


                        echo "<div class='cg_form_div'>";
                        echo "<label for='cg_upload_form_field_in_gallery$fieldId$uniqueIdAddition'>".sanitize_text_field(contest_gal1ery_convert_for_html_output_without_nl2br($Field_Content['titel']))." $necessary</label>";
                        echo "<input type='hidden' name='form_input[$Field_Order][]' value='url'><input type='hidden' name='form_input[$Field_Order][]' value='$fieldId'>";// Formart und FormfeldID hidden
                        echo "<input type='hidden' name='form_input[$Field_Order][]'  value='$Field_Order'>";// Fieldorder will be provided
                        echo "<input type='text' placeholder='".contest_gal1ery_convert_for_html_output_without_nl2br($Field_Content['content'])."' class='cg_input_url_class cg_upload_form_field_in_gallery' data-cg-gid='$galeryIDuserForJs'  id='cg_upload_form_field_in_gallery$fieldId$uniqueIdAddition' value='' name='form_input[$Field_Order][]'>";// Content Feld, l�nge wird �berpr�ft
                        echo "<input type='hidden' class='cg_form_required' value='$checkIfNeed'>";// Pr�fen ob Pflichteingabe
                        echo "<p class='cg_input_error cg_hide'></p>";// Fehlermeldung erscheint hier
                        echo "</div>";

                    }

                    if ($field['Field_Type']=='email-f' && $field['Active'] == '1'){

                        if(is_user_logged_in()==false){
                            $fieldId = $field['id'];
                            $Field_Order = $field['Field_Order'];
                            $Field_Content = $field['Field_Content'];
                            $checkIfNeed = $Field_Content['mandatory'];

                            $necessary = ($Field_Content['mandatory']=='on') ? '*' : '' ;
                            $checkIfNeed = ($Field_Content['mandatory']=='on') ? 'on' : '' ;

                            echo "<div class='cg_form_div'>";
                            echo "<label for='cg_upload_form_field_in_gallery$fieldId$uniqueIdAddition'>".sanitize_text_field(contest_gal1ery_convert_for_html_output_without_nl2br($Field_Content['titel']))." $necessary</label>";
                            echo "<input type='hidden' name='form_input[$Field_Order][]'  value='ef'><input type='hidden' name='form_input[$Field_Order][]' value='$fieldId'>";//Formart und FormfeldID hidden
                            echo "<input type='hidden' name='form_input[$Field_Order][]'  value='$Field_Order'>";// Fieldorder will be provided
                            echo "<input type='text' placeholder='".contest_gal1ery_convert_for_html_output_without_nl2br($Field_Content['content'])."' value='' class='cg_input_email_class cg_upload_form_field_in_gallery' data-cg-gid='$galeryIDuserForJs'  id='cg_upload_form_field_in_gallery$fieldId$uniqueIdAddition' name='form_input[$Field_Order][]'>";// Content Feld, l�nge wird �berpr�ft
                            echo "<input type='hidden' class='cg_form_required' value='$checkIfNeed'>";// Pr�fen ob Pflichteingabe
                            echo "<p class='cg_input_error cg_hide'></p>";// Fehlermeldung erscheint hier
                            echo "</div>";
                        }

                    }

                    if ($field['Field_Type']=='fbd-f' && $field['Active'] == '1'){
                        $fieldId = $field['id'];
                        $Field_Order = $field['Field_Order'];
                        $Field_Content = $field['Field_Content'];
                        $checkIfNeed = $Field_Content['mandatory'];

                        $necessary = ($Field_Content['mandatory']=='on') ? '*' : '' ;
                        $checkIfNeed = ($Field_Content['mandatory']=='on') ? 'on' : '' ;

                        echo "<div class='cg_form_div'>";
                        echo "<label for='cg_upload_form_field_in_gallery$fieldId$uniqueIdAddition'>".sanitize_text_field(contest_gal1ery_convert_for_html_output_without_nl2br($Field_Content['titel']))." $necessary</label>";
                        echo "<input type='hidden' name='form_input[$Field_Order][]'  value='fbd'><input type='hidden' name='form_input[$Field_Order][]' value='$fieldId'>";// Formart und FormfeldID hidden
                        echo "<input type='hidden' name='form_input[$Field_Order][]'  value='$Field_Order'>";// Fieldorder will be provided
                        echo "<textarea maxlength='".$Field_Content['max-char']."' class='cg_textarea_class cg_upload_form_field_in_gallery' data-cg-gid='$galeryIDuserForJs' id='cg_upload_form_field_in_gallery$fieldId$uniqueIdAddition' placeholder='".contest_gal1ery_convert_for_html_output_without_nl2br($Field_Content['content'])."' name='form_input[$Field_Order][]'  rows='6' ></textarea>";// Content Feld, l�nge wird �berpr�ft
                        echo "<input type='hidden' class='minsize' value='".$Field_Content['min-char']."'>"; // Pr�fen minimale Anzahl zeichen
                        echo "<input type='hidden' class='maxsize' value='".$Field_Content['max-char']."'>"; // Pr�fen maximale Anzahl zeichen
                        echo "<input type='hidden' class='cg_form_required' value='$checkIfNeed'>";// Pr�fen ob Pflichteingabe
                        echo "<p class='cg_input_error cg_hide'></p>";// Fehlermeldung erscheint hier
                        echo "</div>";

                    }

                    if ($field['Field_Type']=='comment-f' && $field['Active'] == '1'){
                        $fieldId = $field['id'];
                        $Field_Order = $field['Field_Order'];
                        $Field_Content = $field['Field_Content'];
                        $checkIfNeed = $Field_Content['mandatory'];

                        $necessary = ($Field_Content['mandatory']=='on') ? '*' : '' ;
                        $checkIfNeed = ($Field_Content['mandatory']=='on') ? 'on' : '' ;

                        echo "<div class='cg_form_div'>";
                        echo "<label for='cg_upload_form_field_in_gallery$fieldId$uniqueIdAddition'>".sanitize_text_field(contest_gal1ery_convert_for_html_output_without_nl2br($Field_Content['titel']))." $necessary</label>";
                        echo "<input type='hidden' name='form_input[$Field_Order][]'  value='kf'><input type='hidden' name='form_input[$Field_Order][]' value='$fieldId'>";// Formart und FormfeldID hidden
                        echo "<input type='hidden' name='form_input[$Field_Order][]'  value='$Field_Order'>";// Fieldorder will be provided
                        echo "<textarea maxlength='".$Field_Content['max-char']."' class='cg_textarea_class cg_upload_form_field_in_gallery' data-cg-gid='$galeryIDuserForJs' id='cg_upload_form_field_in_gallery$fieldId$uniqueIdAddition' placeholder='".contest_gal1ery_convert_for_html_output_without_nl2br($Field_Content['content'])."' name='form_input[$Field_Order][]'  rows='6' ></textarea>";// Content Feld, l�nge wird �berpr�ft
                        echo "<input type='hidden' class='minsize' value='".$Field_Content['min-char']."'>"; // Pr�fen minimale Anzahl zeichen
                        echo "<input type='hidden' class='maxsize' value='".$Field_Content['max-char']."'>"; // Pr�fen maximale Anzahl zeichen
                        echo "<input type='hidden' class='cg_form_required' value='$checkIfNeed'>";// Pr�fen ob Pflichteingabe
                        echo "<p class='cg_input_error cg_hide'></p>";// Fehlermeldung erscheint hier
                        echo "</div>";
                    }

                    if ($field['Field_Type']=='check-f' && $field['Active'] == '1'){

                        $fieldId = $field['id'];
                        $Field_Order = $field['Field_Order'];
                        $Field_Content = $field['Field_Content'];
                        $Field_Version = (!empty($field['Version'])) ? $field['Version'] : '';
                        $checkIfNeed = $Field_Content['mandatory'];

                        $necessary = ($Field_Content['mandatory']=='on') ? '*' : '' ;
                        $checkIfNeed = ($Field_Content['mandatory']=='on') ? 'on' : '' ;
                     //   $checkIfNeed = $Field_Content['mandatory'];

                   //     $checkIfNeed = ($Field_Content['mandatory']=='on') ? 'on' : '' ;

                        if(empty($Field_Version)){// then must be old form and always required
                            $necessary = '*';
                            $checkIfNeed = 'on';
                        }

                        echo "<div class='cg_form_div'>";
                        echo "<label for='cg_upload_form_field_in_gallery$fieldId$uniqueIdAddition'>".sanitize_text_field(contest_gal1ery_convert_for_html_output_without_nl2br($Field_Content['titel']))." $necessary</label>";
                        echo "<input type='hidden' name='form_input[$Field_Order][]'  value='cb'><input type='hidden' name='form_input[$Field_Order][]' value='$fieldId'>";// Formart und FormfeldID hidden
                        echo "<input type='hidden' name='form_input[$Field_Order][]'  value='$Field_Order'>";// Fieldorder will be provided
                        echo "<div class='cg-check-agreement-container'>";
                        echo "<div class='cg-check-agreement-checkbox'>";
                        echo "<input type='checkbox' class='cg_check_agreement_class cg_upload_form_field_in_gallery' data-cg-gid='$galeryIDuserForJs' id='cg_upload_form_field_in_gallery$fieldId$uniqueIdAddition' name='form_input[$Field_Order][]' value='checked' >";
                        echo "<input type='hidden' class='cg_form_required' value='$checkIfNeed'>";// Pr�fen ob Pflichteingabe
                        echo "</div>";
                        echo "<div class='cg-check-agreement-html'>".contest_gal1ery_convert_for_html_output($Field_Content['content']);
                        echo "</div>";
                        echo "</div>";
                        echo "<p class='cg_input_error cg_hide'></p>";// Fehlermeldung erscheint hier
                        echo "</div>";

                    }

                    if ($field['Field_Type']=='select-f' && $field['Active'] == '1'){
                        $fieldId = $field['id'];
                        $Field_Order = $field['Field_Order'];
                        $Field_Content = $field['Field_Content'];
                        $checkIfNeed = $Field_Content['mandatory'];

                        $necessary = ($Field_Content['mandatory']=='on') ? '*' : '' ;
                        $checkIfNeed = ($Field_Content['mandatory']=='on') ? 'on' : '' ;

                        echo "<div class='cg_form_div'>";
                        echo "<label for='cg_upload_form_field_in_gallery$fieldId$uniqueIdAddition'>".sanitize_text_field(contest_gal1ery_convert_for_html_output_without_nl2br($Field_Content['titel']))." $necessary</label>";
                        echo "<input type='hidden' name='form_input[$Field_Order][]'  value='se'><input type='hidden' name='form_input[$Field_Order][]' value='$fieldId'>";// Formart und FormfeldID hidden
                        echo "<input type='hidden' name='form_input[$Field_Order][]'  value='$Field_Order'>";// Fieldorder will be provided

                        $textAr = explode("\n", $Field_Content['content']);

                        echo "<select name='form_input[$Field_Order][]' class='cg_input_select_class cg_upload_form_field_in_gallery'  id='cg_upload_form_field_in_gallery$fieldId$uniqueIdAddition' data-cg-gid='$galeryIDuserForJs' >";

                        echo "<option value='0'>$language_pleaseSelect</option>";

                        foreach($textAr as $key => $value){

                            $value = sanitize_text_field(contest_gal1ery_convert_for_html_output_without_nl2br($value));
                            echo "<option value='$value'>$value</option>";

                        }

                        echo "</select>";

                        echo "<input type='hidden' class='cg_form_required' value='$checkIfNeed'>";// Pr�fen ob Pflichteingabe
                        echo "<p class='cg_input_error cg_hide'></p>";// Fehlermeldung erscheint hier
                        echo "</div>";

                    }

                    if ($field['Field_Type']=='selectc-f' && $field['Active'] == '1'){
                        $fieldId = $field['id'];
                        $Field_Order = $field['Field_Order'];
                        $Field_Content = $field['Field_Content'];
                        $checkIfNeed = $Field_Content['mandatory'];

                        $necessary = ($Field_Content['mandatory']=='on') ? '*' : '' ;
                        $checkIfNeed = ($Field_Content['mandatory']=='on') ? 'on' : '' ;

                        echo "<div class='cg_form_div'>";
                        echo "<label for='cg_upload_form_field_in_gallery$fieldId$uniqueIdAddition'>".sanitize_text_field(contest_gal1ery_convert_for_html_output_without_nl2br($Field_Content['titel']))." $necessary</label>";
                        echo "<input type='hidden' name='form_input[$Field_Order][]'  value='sec'><input type='hidden' name='form_input[$Field_Order][]' value='$fieldId'>";// Formart und FormfeldID hidden
                        echo "<input type='hidden' name='form_input[$Field_Order][]'  value='$Field_Order'>";// Fieldorder will be provided


                        echo "<select name='form_input[$Field_Order][]' class='cg_input_select_class cg_upload_form_field_in_gallery cg_select_category ' id='cg_upload_form_field_in_gallery$fieldId$uniqueIdAddition' data-cg-gid='$galeryIDuserForJs' >";

                        echo "<option value='0'>$language_pleaseSelect</option>";

                        foreach($jsonCategories as $categoryKey => $category){

                            echo "<option value='".$categoryKey."' >".sanitize_text_field($category['Name'])."</option>";

                        }

                        echo "</select>";

                        echo "<input type='hidden' class='cg_form_required' value='$checkIfNeed'>";// Pr�fen ob Pflichteingabe
                        echo "<p class='cg_input_error cg_hide'></p>";// Fehlermeldung erscheint hier
                        echo "</div>";

                    }

                    if ($field['Field_Type']=='html-f' && $field['Active'] == '1'){
                        $Field_Order = $field['Field_Order'];
                        $Field_Content = $field['Field_Content'];

                        echo "<div class='cg_form_div cg_html_field_class'>";
                        echo contest_gal1ery_convert_for_html_output_without_nl2br($Field_Content['content']);
                        echo "</div>";

                    }

                    if ($field['Field_Type']=='caRo-f' && $field['Active'] == '1'){

                        $fieldSimpleRecaptcha = $field;

                        if(!$isDefinitelyBulkUpload){
                            include ('gallery-upload-form-simple-recaptcha.php');
                        }

                    }

                    if ($field['Field_Type']=='caRoRe-f' && $field['Active'] == '1'){

                        $fieldGoogleRecaptcha = $field;

                        if(!$isDefinitelyBulkUpload && !$isGoogleRecaptchaAlreadyRendered){
                            
                            if(empty($cgGlobalGoogleRecaptchaRendered)){
                                $cgGlobalGoogleRecaptchaRendered = true;
                                include ('gallery-upload-form-google-recaptcha.php');
                                $isGoogleRecaptchaAlreadyRendered = true;
                            }
                        }

                    }

                }

                echo "</div>";


            echo "</div>";

            if($ActivateBulkUpload==1 && $BulkUploadQuantity > 1 && $i != $maxUpload - 1){
                echo "<hr class='cg_form_div_multiple_hr $ActivateBulkUploadCgHide'>";
            }

            if($isDefinitelyBulkUpload && !$isBulkUploadCaptchaDivRendered){
                if(!empty($fieldSimpleRecaptcha) || !empty($fieldGoogleRecaptcha)){
                    $uniqueIdAdditionBulk = substr(md5(uniqid(rand().$galeryIDuser, true)),0,5).$i;
                    echo "<div class='cg_form_div_image_step cg_form_div_image_step_upload_bulk_captcha cg_hide'  data-cg-gid='$galeryIDuserForJs'>";

                        if(!empty($fieldSimpleRecaptcha)){
                            include ('gallery-upload-form-simple-recaptcha.php');
                        }

                        if(!empty($fieldGoogleRecaptcha)){
                            if(empty($cgGlobalGoogleRecaptchaRendered)){
                                $cgGlobalGoogleRecaptchaRendered = true;
                                include ('gallery-upload-form-google-recaptcha.php');
                            }
                        }

                    echo "</div>";
                    $isBulkUploadCaptchaDivRendered = true;
                }
            }

        }

        $cg_hide = 'cg_hide';

        if($UploadFormAppearance==1){
            $cg_hide = '';
        }

        if(!$isShowCollapsed){
            $cg_hide = '';
        }

        echo "<div class='cg_form_div cg_form_upload_submit_div $cg_hide' >";
        if($ActivateBulkUpload==1 && $BulkUploadQuantity){
            echo "<hr class='cg_form_div_multiple_hr cg_hide'>";
        }

        $buttonText = $language_sendButton;

        echo "<input type='submit' name='cg_form_submit' class='cg_users_upload_submit $cg_form_submit_bulk_upload' data-cg-gid='$galeryIDuserForJs' value='$buttonText'>";
            echo "<p class='cg_input_error cg_hide'></p>";
        echo "</div>";

        echo "</form>";

        if( empty($isOnlyContactForm)){// then in gallery upload form
            echo "<div id='cgGalleryUploadFormTextAfter$galeryIDuserForJs' class='cg_gallery_upload_text_before_and_after' data-cg-gid='$galeryIDuserForJs'>";
            echo contest_gal1ery_convert_for_html_output($options['pro']['GalleryUploadTextAfter']);
            echo "</div>";
        }

    }
}

echo "</div>";

echo "</div>";

?>