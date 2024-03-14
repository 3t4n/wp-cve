<?php
if(!defined('ABSPATH')){exit;}
require_once('get-data-create-upload-v10.php');

$iconsURL = plugins_url().'/'.cg_get_version().'/v10/v10-css';

$cgRecaptchaIconUrl = $iconsURL.'/backend/re-captcha.png';
$cgDragIcon = $iconsURL.'/backend/cg-drag-icon.png';


echo "<input type='hidden' id='cgDragIcon' value='$cgDragIcon'/>";
echo "<input type='hidden' id='cgRecaptchaIconUrl' value='$cgRecaptchaIconUrl'/>";
echo "<input type='hidden' id='cgRecaptchaKey' value='6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI'/>";

require_once(dirname(__FILE__) . "/../nav-menu.php");

if(!function_exists('cg_cg_set_default_editor')){
    function cg_cg_set_default_editor() {
        $r = 'html';
        return $r;
    }
}

add_filter( 'wp_default_editor', 'cg_cg_set_default_editor' );

// recaptcha-lang-options.php
$langOptions = include(__DIR__.'/../data/recaptcha-lang-options.php');

echo '<select name="ReCaLang" id="cgReCaLangToCopy" class="cg_hide">';

echo "<option value='' >Please select language</option>";

foreach($langOptions as $langKey => $lang){

    echo "<option value='$langKey' >$lang</option>";

}

echo '</select>';


echo '<div id="cg_main_options" style="margin-top: 0;box-shadow: unset;" class="cg_main_options">';


echo '<div id="cgUploadFieldsSelect">';

//echo "<form name='defineUpload' enctype='multipart/form-data' action='?page='.cg_get_version().'/index.php&optionID=$GalleryID&defineUpload=true' id='form' method='post'>";

$fbLikeTitleAndDesc = '';

if($FbLike==1){
    $fbLikeTitleAndDesc = "<option class=\"$cgProFalse\" value=\"fbt\">Facebook share button title $cgProFalseText</option>
			<option class=\"$cgProFalse\" value=\"fbd\">Facebook share button description $cgProFalseText</option>";
}

echo "<input type='hidden' id='cgProFalseCheck' value='$cgProFalse' >";

$heredoc = <<<HEREDOC
	<select name="dauswahl" id="dauswahl" >
		<optgroup label="User fields">
			<option  value="nf">Input</option>
			<option value="kf">Textarea</option>
			<option value="se">Select</option>
			<option value="sec">Select Categories</option>
			<option class="$cgProFalse" value="dt">Date $cgProFalseText</option>
			<option class="$cgProFalse" value="ef">Email $cgProFalseText</option>
			<option value="url">URL</option>
			<option class="$cgProFalse" value="cb">Check agreement $cgProFalseText</option>
			$fbLikeTitleAndDesc
		 </optgroup>
		<optgroup label="Admin fields">
			<option class="$cgProFalse" value="ht">HTML $cgProFalseText</option>
			<option  value="caRo">Simple Captcha - I am not a robot</option>
			<option  value="caRoRe">Google reCAPTCHA - I am not a robot</option>
		 </optgroup>
	</select>
	<input id="cg_create_upload_add_field" class="cg_upload_dauswahl" type="button" name="plus" value="Add field" >
	<select id="cgPlace" style="margin-left:5px;margin-right: 5px;">
        <option  value="place-top">Place top</option>
        <option  value="place-bottom">Place bottom</option>
    </select>
    <span id="cgCollapse" class="cg_uncollapsed" >Collapse all</span>
	<span class="cg_save_form_button_parent" >
	Contact form
            <span id="cgSaveContactFormNavButton"  class="cg_save_form_button cg_backend_button_gallery_action cg_hide" >Save form</span>
	</span>
	<div style="flex-basis:100%;height:0;"></div>
	</div>
HEREDOC;

echo $heredoc;

if(!empty($_POST['upload'])){
 //   echo "<p id='cg_changes_saved' style='font-size:18px;'><strong>Changes saved</strong></p>";
}

echo "<form class='cg_load_backend_submit'  data-cg-submit-message='Changes Saved'  name='defineUpload' enctype='multipart/form-data' action='?page=".cg_get_version()."/index.php&option_id=$GalleryID&define_upload=true' id='cgCreateUploadForm' method='post'>";
wp_nonce_field( 'cg_admin');

echo "<input type='hidden' name='option_id' value='$GalleryID'>";
?>



<div id="cgCreateUploadContainer" >
    <div id="ausgabe1" class="cg_create_upload" >

        <?php

        // IDs of the div boxes
        $nfCount = 10;
        $kfCount = 20;
        $efCount = 30;
        $bhCount = 40;
        $htCount = 50;
        $cbCount = 60;
        $seCount = 70;
        $caRoCount = 80;
        $secCount = 90;
        $urlCount = 100;
        $caRoReCount = 110;
        $fbtCount = 120;
        $fbdCount = 130;
        $dtCount = 140;


        // Further IDs of the div boxes
        $nfHiddenCount = 100;
        $kfHiddenCount = 200;
        $efHiddenCount = 300;
        $bhHiddenCount = 400;
        $htHiddenCount = 500;
        $cbHiddenCount = 600;
        $seHiddenCount = 700;
        $caRoHiddenCount = 800;
        $urlHiddenCount = 1000;
        $caRoReHiddenCount = 1100;
        $fbtHiddenCount = 1200;
        $fbdHiddenCount = 1300;
        $dtHiddenCount = 1400;

        // FELDBENENNUNGEN

        // 1 = Feldtyp
        // 2 = Feldnummer
        // 3 = Feldtitel
        // 4 = Feldinhalt
        // 5 = Feldkrieterium1
        // 6 = Feldkrieterium2
        // 7 = Felderfordernis

        //print_r($selectFormInput);

        $cg_info_show_slider_title = 'Show as info in single entry view';
        $cg_info_show_gallery_title = 'Show as title in gallery view (only 1 allowed)';
        $cg_tag_show_gallery_title = 'Show as HTML title attribute in gallery (only 1 allowed)';

        $cg_disabled_sub_and_third_title = '';
        $cg_disabled_sub_and_third_title_note = '';
        if(floatval($dbGalleryVersion)<21){
            $cg_disabled_sub_and_third_title = 'cg_disabled_background_color_e0e0e0';
            $cg_disabled_sub_and_third_title_note = '<br><span class="cg_view_option_title_note"><span class="cg_font_weight_bold cg_color_red">NOTE:</span> Only available for galleries copied or created in version 21.0.0 and higher</span>';
        }

        // simply for bracket
        if(true){

            echo "<div id='cgFieldsToCloneAndAppend' class='cg_hide'>";

            // just as placeholder for all kind of inputs simply
            $value = new stdClass();
            $value->Field_Content = serialize([
                'titel' => 'Title',
                'content' => '',
                'min-char' => 3,
                'max-char' => 100,
                'mandatory' => 'off',
                'format' => 'YYYY-MM-DD',
            ]);
            $value->id = 'new-0';
            $value->Active = 0;
            $value->Field_Order = 0;
            $value->Field_Type = '';
            $value->GalleryID = $GalleryID;
            $value->Use_as_URL = 0;
            $value->ReCaKey = '';
            $value->ReCaLang = 'en';
            $value->Version = $dbGalleryVersion;
            $value->WatermarkPosition = '';
            $value->WpAttachmentDetailsType = '';
                $id = $value->id; // Unique ID des Form Feldes

            $dateSelect = <<<HEREDOC
    <select name='upload[$id][format]'>
                            <option value='YYYY-MM-DD' >YYYY-MM-DD</option>
                            <option value='DD-MM-YYYY' >DD-MM-YYYY</option>
                            <option value='MM-DD-YYYY' >MM-DD-YYYY</option>
                            <option value='YYYY/MM/DD' >YYYY/MM/DD</option>
                            <option value='DD/MM/YYYY' >DD/MM/YYYY</option>
                            <option value='MM/DD/YYYY' >MM/DD/YYYY</option>
                            <option value='YYYY.MM.DD' >YYYY.MM.DD</option>
                            <option value='DD.MM.YYYY' >DD.MM.YYYY</option>
                            <option value='MM.DD.YYYY' >MM.DD.YYYY</option>
                            </select><br/>
    HEREDOC;

            $enterKey = '';

            $pleaseSelectLanguage = '';
            $pleaseSelectLanguage .= '<select id="cgReCaLang" name="upload['.$id.'][ReCaLang]">';
            $pleaseSelectLanguage .= "<option value='' >Please select language</option>";
            foreach($langOptions as $langKey => $lang){
                    $pleaseSelectLanguage .= "<option value='$langKey' >$lang</option>";
                    }
            $pleaseSelectLanguage .= '</select>';

            $captchaNote = "<span class='cg_recaptcha_test_note' ><span>NOTE:</span><br><b>Google reCAPTCHA test key</b> is provided from Google for testing purpose.
                                    <br><b>Create your own \"Site key\"</b> here <a href='https://www.google.com/recaptcha/admin' target='_blank'>www.google.com/recaptcha/admin</a><br>Register your site, create a <b>V2 \"I am not a robot\"</b>  key.</span>";

            $valueFieldTextareaContent = '';
            $editor_id = '';

            $fieldOrder = 0;

                $fieldOrder = $value->Field_Order;
                $fieldOrderKey = "$fieldOrder";
                $idKey = "$id";
                    $hideChecked = "";

                    if($id==$Field1IdGalleryView){$checked='checked';}
                else{$checked='';}

                $checkedSubTitle = false;
                $checkedThirdTitle = false;

            $Show_Slider = 0;

                    if($Show_Slider==1){$checkedShow_Slider='checked';}
                else{$checkedShow_Slider='';}

            $IsForWpPageTitle = 0;

            if($IsForWpPageTitle==1){$checkedIsForWpPageTitle='checked';}
            else{$checkedIsForWpPageTitle='';}

            $IsForWpPageDescription = 0;

            if($IsForWpPageDescription==1){$checkedIsForWpPageDescription='checked';}
            else{$checkedIsForWpPageDescription='';}

                if($id==$Field2IdGalleryView){$checkedShowTag='checked';}
                else{$checkedShowTag='';}

                $checkedWatermark = "";
                $hiddenWatermarkSelect = "cg_hidden";
                $watermarkPosition = "top-left";
                $watermarkPositionTopLeftChecked = "";
                $watermarkPositionTopRightChecked = "";
                $watermarkPositionBottomLeftChecked = "";
                $watermarkPositionBottomRightChecked = "";
                $watermarkPositionCenterChecked = "";
            $watermarkPositionDisabled = "cg_disabled_watermark";
            if(!empty(trim($value->WatermarkPosition))){
                $watermarkPositionDisabled = "";
                    $hiddenWatermarkSelect = "";
                    $checkedWatermark = "checked='checked'";
                    $watermarkPosition = $value->WatermarkPosition;
                    if($watermarkPosition=='top-left'){
                        $watermarkPositionTopLeftChecked = "selected";
                    }else if($watermarkPosition=='top-right'){
                        $watermarkPositionTopRightChecked = "selected";
                    }else if($watermarkPosition=='bottom-left'){
                        $watermarkPositionBottomLeftChecked = "selected";
                    }else if($watermarkPosition=='bottom-right'){
                        $watermarkPositionBottomRightChecked = "selected";
                    }else if($watermarkPosition=='center'){
                        $watermarkPositionCenterChecked = "selected";
                    }
                }

            $WpAttachmentDetailsType = '';
            $WpAttachmentDetailsTypeAltChecked = '';
            $WpAttachmentDetailsTypeTitleChecked = '';
            $WpAttachmentDetailsTypeCaptionChecked = '';
            $WpAttachmentDetailsTypeDescriptionChecked = '';
            if(!empty(trim($value->WpAttachmentDetailsType))){
                $WpAttachmentDetailsType = $value->WpAttachmentDetailsType;
                if($WpAttachmentDetailsType=='alt'){
                    $WpAttachmentDetailsTypeAltChecked = "selected";
                }else if($WpAttachmentDetailsType=='title'){
                    $WpAttachmentDetailsTypeTitleChecked = "selected";
                }else if($WpAttachmentDetailsType=='caption'){
                    $WpAttachmentDetailsTypeCaptionChecked = "selected";
                }else if($WpAttachmentDetailsType=='description'){
                    $WpAttachmentDetailsTypeDescriptionChecked = "selected";
                }
            }

                // Formularfelder unserializen
                $fieldContent = unserialize($value->Field_Content);

            $valueFieldTitle = '';
            $valueFieldPlaceholder = '';
            $minChar = '';
            $maxChar = '';
            $requiredChecked = '';

                foreach($fieldContent as $key => $valueFieldContent){

                    $valueFieldContent = contest_gal1ery_convert_for_html_output($valueFieldContent);// because of possible textarea values do not use ..._without_nl2br

                    if($key=='titel'){
                    $valueFieldTitle = $valueFieldContent;
                    }

                    if($key=='content'){
                    $valueFieldPlaceholder = $valueFieldContent;
                    }

                    if($key=='min-char'){
                    $minChar = $valueFieldContent;
                    }

                    if($key=='max-char'){
                    $maxChar = $valueFieldContent;
                    }

                    if($key=='mandatory'){

                    $requiredChecked = ($valueFieldContent=='on') ? "checked" : "";

                        $nfCount++;
                        $nfHiddenCount++;

            }

                }

            $isOnlyPlaceHolder = true;

            include (__DIR__.'/fields/check-agreement.php');

            include (__DIR__.'/fields/date.php');

            include (__DIR__.'/fields/input.php');

            include (__DIR__.'/fields/url.php');

            include (__DIR__.'/fields/email.php');

            include (__DIR__.'/fields/textarea.php');

            include (__DIR__.'/fields/html.php');

            include (__DIR__.'/fields/simple-captcha.php');

            include (__DIR__.'/fields/google-captcha.php');

            include (__DIR__.'/fields/select.php');

            include (__DIR__.'/fields/select-categories.php');

            $isOnlyPlaceHolder = false;

                        echo "</div>";

        }

        echo '<div id="cgUploadFormDescription" ><b>NOTE:</b> added fields will be available as content fields for all file entries, or entries without files</div>';

        foreach ($selectFormInput as $value) {
            if($value->Field_Type == 'image-f'){
                include (__DIR__.'/fields/image.php');
            }
        }

        echo "<div id='cgCreateUploadSortableArea' class='cg_sortable_area'>";

        foreach ($selectFormInput as $value) {

            if($value->Field_Type == 'check-f'){// AGREEMENT FIELD
                include (__DIR__.'/fields/check-agreement.php');
                    }

            if($value->Field_Type == 'date-f'){
                include (__DIR__.'/fields/date.php');
                }

            if($value->Field_Type == 'text-f'){
                include (__DIR__.'/fields/input.php');
            }

            if($value->Field_Type == 'url-f'){
                include (__DIR__.'/fields/url.php');
            }

            if($value->Field_Type == 'email-f'){
                include (__DIR__.'/fields/email.php');
                    }

            if($value->Field_Type == 'comment-f'){
                include (__DIR__.'/fields/textarea.php');
                }

            if($value->Field_Type == 'html-f'){
                include (__DIR__.'/fields/html.php');
                }

            if($value->Field_Type == 'caRo-f'){
                include (__DIR__.'/fields/simple-captcha.php');
            }

            if($value->Field_Type == 'caRoRe-f'){
                include (__DIR__.'/fields/google-captcha.php');
                }

            if($value->Field_Type == 'select-f'){
                include (__DIR__.'/fields/select.php');
            }

            if($value->Field_Type == 'selectc-f'){
                include (__DIR__.'/fields/select-categories.php');
                    }

                }

                echo "</div>";

        ?>
    </div>

</div>

<div id="submitUploadRegFormContainer" >
    <input id="submitForm" type="submit" name="submit" class="cg_backend_button_gallery_action" value="Save form" style="font-weight:bold;text-align:center;width:180px;float:right;margin-right:10px;margin-bottom:10px;">
</div>
<br/>



<?php


// ---------------- AUSGABE des gespeicherten Formulares  --------------------------- ENDE

echo "<br/>";
?>
</form>
</div>