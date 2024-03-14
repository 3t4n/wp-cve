<?php

echo <<<HEREDOC

<div class='cg_view_container'>

HEREDOC;

echo <<<HEREDOC
<div class='cg_view_options_rows_container'>
    <p class='cg_view_options_rows_container_title'>For using <span class="cg_font_weight_bold">cg_users_contact</span> shortcode<br>or <span class="cg_font_weight_bold">"In gallery contact form"</span></p>
</div>
HEREDOC;

echo <<<HEREDOC
        <div class='cg_view_options_row cg_margin_bottom_30'>
            <div class='cg_view_option cg_view_option_100_percent cg_border_radius_8_px' id="ActivateUploadContainer">
                <div class='cg_view_option_title' >
                    <p>Automatically activate users entries in frontend after frontend contact</p>
                </div>
                <div class='cg_view_option_checkbox'>
                    <input type="checkbox" name="ActivateUpload" id="ActivateUpload" $ActivateUpload>
                </div>
            </div>
        </div>
    <div class='cg_view_options_rows_container' id="cgInGalleryUploadFormConfiguration">
        <p class='cg_view_options_rows_container_title'><span class="cg_font_weight_bold">"In gallery contact form"</span> text configuration
        <br><span class="cg_view_options_rows_container_title_note"><span class="cg_font_weight_bold">NOTE:</span> to place text before and after <span class="cg_font_weight_bold">cg_users_contact</span> shortcode simply use common WordPress editor blocks</span>
        </p>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width' >
                    <div class='cg_view_option_title'>
                        <p>In gallery contact form button<br/><span class="cg_view_option_title_note"><a class="cg_no_outline_and_shadow_on_focus" href="#cgInGalleryUploadFormButton"  style="padding-top: 10px; display: block;">Can be activated here...</a></span></p>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' id="wp-GalleryUploadConfirmationText-wrap-Container">
                    <div class='cg_view_option_title'>
                        <p>Confirmation text after contact</p>
                    </div>
                    <div class='cg_view_option_html'>
                        <textarea class='cg-wp-editor-template' id='GalleryUploadConfirmationText'  name='GalleryUploadConfirmationText'>$GalleryUploadConfirmationText</textarea>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' id="wp-GalleryUploadTextBefore-wrap-Container">
                    <div class='cg_view_option_title'>
                        <p>Text before contact form</p>
                    </div>
                    <div class='cg_view_option_html'>
                        <textarea class='cg-wp-editor-template' id='GalleryUploadTextBefore'  name='GalleryUploadTextBefore'>$GalleryUploadTextBefore</textarea>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' id="wp-GalleryUploadTextAfter-wrap-Container">
                    <div class='cg_view_option_title'>
                        <p>Text after contact form</p>
                    </div>
                    <div class='cg_view_option_html'>
                        <textarea class='cg-wp-editor-template' id='GalleryUploadTextAfter'  name='GalleryUploadTextAfter'>$GalleryUploadTextAfter</textarea>
                    </div>
                </div>
            </div>
    </div>
HEREDOC;

echo <<<HEREDOC
    <div class='cg_view_options_rows_container'>
        <p class='cg_view_options_rows_container_title'>Contact form shortcode <b>visual</b> configuration<br><span class="cg_view_options_rows_container_title_note">Is not for "In gallery contact form". It is for contact form shortcode: [cg_users_contact id="$galeryNR"]</span></p>
        <div class="cg_view_options_row">
            <div class="cg_view_option cg_view_option_100_percent" id="BorderRadiusContainer">
                <div class="cg_view_option_title" >
                    <p style="margin-right: -30px;">Round borders form container and field inputs</p>
                </div>
                <div class="cg_view_option_checkbox cg_view_option_checked">
                    <input type="checkbox" name="BorderRadiusUpload" id="BorderRadiusUpload" $BorderRadiusUpload>
                </div>
            </div>
    </div>
     <div class='cg_view_options_row '  >
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none'>
                    <div class='cg_view_option_title'>
                        <p>Background, fields and font color style</p>
                    </div>
                    <div class='cg_view_option_radio_multiple'>
                        <div class='cg_view_option_radio_multiple_container'>
                            <div class='cg_view_option_radio_multiple_title'>
                                Bright style
                            </div>
                            <div class='cg_view_option_radio_multiple_input'>
                                <input type="radio" name="FeControlsStyleWhiteUpload" class="FeControlsStyleWhiteUpload cg_view_option_radio_multiple_input_field" $FeControlsStyleWhiteUpload value="white"/>
                            </div>
                        </div>
                        <div class='cg_view_option_radio_multiple_container'>
                            <div class='cg_view_option_radio_multiple_title'>
                                Dark style
                            </div>
                            <div class='cg_view_option_radio_multiple_input'>
                                <input type="radio" name="FeControlsStyleBlackUpload" class="FeControlsStyleBlackUpload cg_view_option_radio_multiple_input_field" $FeControlsStyleBlackUpload value="black">
                            </div>
                        </div>
                </div>
            </div>
</div>
    </div>
HEREDOC;



echo <<<HEREDOC
    <div class='cg_view_options_rows_container'>
        <p class='cg_view_options_rows_container_title'>Contact form shortcode configuration<br><span class="cg_view_options_rows_container_title_note">Is not for "In gallery contact form". It is for contact form shortcode: [cg_users_contact id="$galeryNR"]</span></p>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_100_percent' id="forwardContainer" >
                    <div class='cg_view_option_title'>
                        <p>Forward to another page after contact</p>
                    </div>
                    <div class='cg_view_option_radio'>
                        <input type="radio" name="forward"  id="forward" $ForwardUploadURL>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' id="forward_urlContainer" >
                    <div class='cg_view_option_title'>
                        <p>Forward to URL<br><span class="cg_view_option_title_note"><span class="cg_font_weight_500">NOTE: </span> has to start with <span class="cg_font_weight_500">http://</span> or <span class="cg_font_weight_500">https://</span>, like https://www.example.com</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input id="forward_url" type="text" name="forward_url" maxlength="999" value="$Forward_URL" />
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_100_percent cg_border_top_none' id="cg_confirm_textContainer" >
                    <div class='cg_view_option_title'>
                        <p>Confirmation text on same page after contact instead of forwarding</p>
                    </div>
                    <div class='cg_view_option_radio'>
                        <input type="radio" name="cg_confirm_text"  id="cg_confirm_text" $ForwardUploadConf>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_100_percent cg_border_top_none' id="ShowFormAfterUploadContainer" >
                    <div class='cg_view_option_title'>
                        <p>Show contact form again after contact<br><span class="cg_view_option_title_note">Form will appear under the confirmation text</span></p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" name="ShowFormAfterUpload"  id="ShowFormAfterUpload" $ShowFormAfterUpload>
                    </div>
                </div>
            </div>
             <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' id="wp-confirmation_text-wrap-Container">
                        <div class='cg_view_option_title'>
                            <p>Confirmation text after contact</p>
                        </div>
                        <div class='cg_view_option_html'>
                            <textarea class='cg-wp-editor-template' id='confirmation_text'  name='confirmation_text'>$Confirmation_Text</textarea>
                        </div>
                </div>
            </div>
     
    </div>
HEREDOC;

// Add additional files released in v18 and available for all galleries copied or created since v17
if(intval($galleryDbVersion)>=17){
    echo <<<HEREDOC
    <div class='cg_view_options_rows_container'>
        <p class='cg_view_options_rows_container_title'>Add additional files to a file upload</p>
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_view_option_50_percent cg_border_right_none cg_border_border_top_right_radius_unset cg_border_border_bottom_left_radius_8_px" id="AdditionalFilesContainer" >
                <div class="cg_view_option_title">
                    <p>Add additional files to a file upload</p>
                </div>
                <div class="cg_view_option_checkbox">
                    <input id='AdditionalFiles' type='checkbox' name='AdditionalFiles' $AdditionalFiles >
                </div>
            </div>
            <div class="cg_view_option cg_view_option_50_percent cg_view_option_flex_flow_column cg_border_border_bottom_left_radius_unset cg_border_border_top_right_radius_8_px" id="AdditionalFilesCountContainer">
                <div class="cg_view_option_title cg_view_option_title_full_width">
                    <p>Number of additional files</p>
                </div>
                <div class="cg_view_option_select">
                    <select name='AdditionalFilesCount' id='AdditionalFilesCount' >
HEREDOC;

$i = 1;
while($i<=9){
    $AdditionalFilesSelectDisabled = '';
    $AdditionalFilesCountSelected = '';
    $AdditionalFilesCountProFalse = '';
    $AdditionalFilesCountProFalseText = '';
    if($i==$AdditionalFilesCount){
        $AdditionalFilesCountSelected = 'selected';
    }
    if($i>2 && !empty($cgProFalse)){
        $AdditionalFilesCountProFalse = $cgProFalse;
        $AdditionalFilesCountProFalseText = ' (PRO)';
        $AdditionalFilesSelectDisabled = 'disabled';
    }
    echo "<option value='$i' $AdditionalFilesCountSelected class='$AdditionalFilesCountProFalse' $AdditionalFilesSelectDisabled>$i$AdditionalFilesCountProFalseText</option>";
    $i++;
}

echo <<<HEREDOC
                    </select>
                </div>
              <div class="cg_view_option_title cg_view_option_title_full_width">
                    <p><br><span class="cg_view_option_title_note">
                        <span class="cg-info-position-relative">Maximum <b>upload_max_filesize</b> in your PHP configuration: <b>$upload_max_filesize MB</b><br>
<span class="cg-info-icon">more info</span>
 <span class="cg-info-container" style="top: 52px;left: 148px;display: none;">Maximum upload size per file<br><br>To increase in .htaccess file use:<br><b>php_value upload_max_filesize 10MB</b> (example, no equal to sign!)
 <br>To increase in php.ini file use:<br><b>upload_max_filesize = 10MB</b> (example, equal to sign required!)<br><br><b>Some server providers does not allow manually increase in files.<br>It has to be done in providers backend or they have to be contacted.</b></span>
 </span>
 <span class="cg-info-position-relative">Maximum <b>post_max_size</b> in your PHP configuration: <b>$post_max_size MB</b><br>
<span class="cg-info-icon">more info</span>
 <span class="cg-info-container" style="top: 52px;left: 130px;display: none;">Describes the maximum size of a post which can be done when a form submits.<br>
 Example: you try to upload 3 files with each 3MB and post_max_size is 6MB, then it will not work.<br><br>To increase in htaccess file use:<br><b>php_value post_max_size 10MB</b> (example, no equal to sign!)
 <br>To increase in php.ini file use:<br><b>post_max_size = 10MB</b> (example, equal to sign required!)<br><br><b>Some server providers does not allow manually increase in files.<br>It has to be done in providers backend or they have to be contacted.</b></span>
 </span>
 </span>
 </p>
                </div>
            </div>
        </div>
</div>
HEREDOC;
}

$cgProFalseActivateBulkUpload = '';

// if $ActivateBulkUpload == 1, user has to be able to uncheck on own
if(cg_get_version()=='contest-gallery' && $ActivateBulkUpload !='checked'){
    $cgProFalseActivateBulkUpload = 'cg-pro-false';
}

echo <<<HEREDOC
    <div class='cg_view_options_rows_container'>
HEREDOC;
echo <<<HEREDOC
        <p class='cg_view_options_rows_container_title'>File types, size and bulk contact options</span></p>
            <div class='cg_view_options_row'>
                <div class='cg_view_option  cg_border_border_top_right_radius_unset  cg_border_right_none cg_border_bottom_none $cgProFalseActivateBulkUpload' id="ActivateBulkUploadContainer">
                    <div class='cg_view_option_title'>
                        <p>Activate bulk (multiple files) upload in frontend
                        <br><span class="cg_view_option_title_note"><span class="cg_color_red">NOTE:</span> adding additional files per file upload is not available for bulk upload</span>
                        <br><span class="cg_view_option_title_note"><span class="cg_color_red">NOTE:</span> multiple files upload is always required and collapsed at the beginning</span>
                        </p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" id="ActivateBulkUpload" name="ActivateBulkUpload" $ActivateBulkUpload>
                    </div>
                </div>
                <div class='cg_view_option  cg_border_left_none cg_border_right_none cg_border_bottom_none cg-pro-false-no-label $cgProFalse' id="BulkUploadMinQuantityContainer">
                    <div class='cg_view_option_title'>
                        <p>Minimum number of files<br>for bulk upload<br><span class="cg_view_option_title_note">If empty then no restrictions</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input id="BulkUploadMinQuantity" type="text" name="BulkUploadMinQuantity" value="$BulkUploadMinQuantity" maxlength="20" >
                    </div>
                </div>
                <div class='cg_view_option cg_border_border_top_right_radius_8_px cg_border_left_none cg_border_bottom_none $cgProFalse' id="BulkUploadQuantityContainer">
                    <div class='cg_view_option_title'>
                        <p>Maximum number of files<br>for bulk upload<br><span class="cg_view_option_title_note">If empty then no restrictions</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input id="BulkUploadQuantity" type="text" name="BulkUploadQuantity" value="$BulkUploadQuantity" maxlength="20" >
                    </div>
                </div>
            </div>
            <div id="cgConfigureFileSizesUploadContainer">
            <div class='cg_view_options_row' id="cgActivatePostMaxMBfileContainerRow">
                <div class='cg_view_option cg_border_radius_unset cg_border_bottom_none cg_border_right_none cg_view_option_33_percent $cgProFalse' id="ActivatePostMaxMBfileContainer">
                    <div class='cg_view_option_title'>
                        <p>Restrict frontend upload size file<br>(pdf, zip, txt, doc,<br>docx, xls, xlsx, ppt, pptx, csv,<br>mp3, wav, ogg, m4a, mp4,<br>webm, mp4, mov)</p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" id="ActivatePostMaxMBfile" name="ActivatePostMaxMBfile" $ActivatePostMaxMBfile>
                    </div>
                </div>
                <div class='cg_view_option cg_border_bottom_none cg_view_option_67_percent cg_border_left_none $cgProFalse' id="PostMaxMBfileContainer">
                    <div class='cg_view_option_title' >
                        <p>Maximum upload size in MB per file<br><span class="cg_view_option_title_note">If empty then no restrictions<br><br>
                        <span class="cg-info-position-relative">Maximum <b>upload_max_filesize</b> in your PHP configuration: <b>$upload_max_filesize MB</b><br>
<span class="cg-info-icon">more info</span>
 <span class="cg-info-container" style="top: 52px;left: 148px;display: none;">Maximum upload size per file<br><br>To increase in .htaccess file use:<br><b>php_value upload_max_filesize 10MB</b> (example, no equal to sign!)
 <br>To increase in php.ini file use:<br><b>upload_max_filesize = 10MB</b> (example, equal to sign required!)<br><br><b>Some server providers does not allow manually increase in files.<br>It has to be done in providers backend or they have to be contacted.</b></span>
 </span>
 <span class="cg-info-position-relative">Maximum <b>post_max_size</b> in your PHP configuration: <b>$post_max_size MB</b><br>
<span class="cg-info-icon">more info</span>
 <span class="cg-info-container" style="top: 52px;left: 130px;display: none;">Describes the maximum size of a post which can be done when a form submits.<br>
 Example: you try to upload 3 files with each 3MB and post_max_size is 6MB, then it will not work.<br><br>To increase in htaccess file use:<br><b>php_value post_max_size 10MB</b> (example, no equal to sign!)
 <br>To increase in php.ini file use:<br><b>post_max_size = 10MB</b> (example, equal to sign required!)<br><br><b>Some server providers does not allow manually increase in files.<br>It has to be done in providers backend or they have to be contacted.</b></span>
 </span>
 </span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input id="PostMaxMBfile" type="text" name="PostMaxMBfile" value="$PostMaxMBfile" maxlength="20" style="width:width:300px;" >
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row' id="cgActivatePostMaxMBContainerRow">
                <div class='cg_view_option cg_border_bottom_none cg_border_right_none cg_view_option_33_percent' id="ActivatePostMaxMBContainer">
                    <div class='cg_view_option_title'>
                        <p>Restrict frontend upload size image<br>(jpg, png, gif, ico)</p>
                    </div>
                    <div class='cg_view_option_checkbox'>
                        <input type="checkbox" id="ActivatePostMaxMB" name="ActivatePostMaxMB" $ActivatePostMaxMB>
                    </div>
                </div>
                <div class='cg_view_option cg_border_bottom_none cg_view_option_67_percent cg_border_left_none cg_border_radius_unset' id="PostMaxMBContainer">
                    <div class='cg_view_option_title' >
                        <p>Maximum upload size in MB per image<br><span class="cg_view_option_title_note">If empty then no restrictions<br><br>
                        <span class="cg-info-position-relative">Maximum <b>upload_max_filesize</b> in your PHP configuration: <b>$upload_max_filesize MB</b><br>
<span class="cg-info-icon">more info</span>
 <span class="cg-info-container" style="top: 52px;left: 148px;display: none;">Maximum upload size per file<br><br>To increase in .htaccess file use:<br><b>php_value upload_max_filesize 10MB</b> (example, no equal to sign!)
 <br>To increase in php.ini file use:<br><b>upload_max_filesize = 10MB</b> (example, equal to sign required!)<br><br><b>Some server providers does not allow manually increase in files.<br>It has to be done in providers backend or they have to be contacted.</b></span>
 </span>
 <span class="cg-info-position-relative">Maximum <b>post_max_size</b> in your PHP configuration: <b>$post_max_size MB</b><br>
<span class="cg-info-icon">more info</span>
 <span class="cg-info-container" style="top: 52px;left: 130px;display: none;">Describes the maximum size of a post which can be done when a form submits.<br>
 Example: you try to upload 3 files with each 3MB and post_max_size is 6MB, then it will not work.<br><br>To increase in htaccess file use:<br><b>php_value post_max_size 10MB</b> (example, no equal to sign!)
 <br>To increase in php.ini file use:<br><b>post_max_size = 10MB</b> (example, equal to sign required!)<br><br><b>Some server providers does not allow manually increase in files.<br>It has to be done in providers backend or they have to be contacted.</b></span>
 </span>
 </span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input id="PostMaxMB" type="text" name="PostMaxMB" value="$PostMaxMB" maxlength="20" style="width:width:300px;" >
                    </div>
                </div>
            </div>
            </div>
HEREDOC;

echo <<<HEREDOC
<div class="cg_view_options_row" id="AllowUploadJPGContainerRow">
        <div class="cg_view_option cg_view_option_100_percent cg_border_bottom_none" id="AllowUploadJPGContainer">
            <div class="cg_view_option_title" >
                <p style="margin-right: -30px;">Allow upload JPG</p>
            </div>
            <div class="cg_view_option_checkbox cg_view_option_checked">
                <input type="checkbox" name="AllowUploadJPG" id="AllowUploadJPG" $AllowUploadJPG>
            </div>
        </div>
</div>
<div class='cg_view_options_row'>
        <div class='cg_view_option cg_border_top_right_bottom_none cg-allow-res' id="MaxResJPGonContainer">
            <div class='cg_view_option_title'>
                <p>Max resolution for uploading<br>of JPG pics<span class="cg_view_option_title_note">
                <span class="cg-info-position-relative"><br>
<span class="cg-info-icon">more info</span>
 <span class="cg-info-container" style="top: 52px; left: 10px; display: none;">This allows you to restrict the resolution of the pictures which will be uploaded in frontend. It depends on your web hosting provider how big resolution ca be be for uploaded pics. If your webhosting service is not so powerfull then you should use this restriction.
 </span>
 </span>
 </span>
  <span class="cg_allow_res_note cg_hide">
  <span class="cg_allow_res_note_title">Pay attention:</span>If resolution of an image is to high some servers have not enough power to convert that to lower
resoultions. Official WordPress Api which is used by plugin converts every uploaded image
to lower resolution. Then image with required resolution can be taken to reduce traffic and image load in frontend.
You have to find out by testing on yourself what resolution your server can handle. If your server can not handle the resolution it will lead in some timeout or overload error when uploading.
</span>
 </p>
             </div>
            <div class='cg_view_option_checkbox'>
                <input id='MaxResJPGon' type='checkbox' class="cg-allow-res-checkbox" name='MaxResJPGon' $MaxResJPGon >
            </div>
        </div>
        <div class='cg_view_option cg_border_top_bottom_none cg_border_right_none cg_border_left_none' id="MaxResJPGwidthContainer">
            <div class='cg_view_option_title'>
                <p>Maximum resolution width<br>for JPGs in pixel<br><span class="cg_view_option_title_note">If empty then no restrictions</span></p>
            </div>
            <div class='cg_view_option_input'>
                <input id="MaxResJPGwidth" class="cg_font_size_14" type="text" name="MaxResJPGwidth" value="$MaxResJPGwidth" maxlength="20"  >
            </div>
        </div>
        <div class='cg_view_option cg_border_left_none cg_border_top_bottom_none' id="MaxResJPGheightContainer">
            <div class='cg_view_option_title'>
                <p>Maximum resolution height<br>for JPGs in pixel<br><span class="cg_view_option_title_note">If empty then no restrictions</span></p>
            </div>
            <div class='cg_view_option_input'>
                <input id="MaxResJPGheight" class="cg_font_size_14" type="text" name="MaxResJPGheight" value="$MaxResJPGheight" maxlength="20" >
            </div>
        </div>
</div>
<div class='cg_view_options_row'>
        <div class='cg_view_option cg_border_top_none cg_border_top_right_none cg-allow-res' id="MinResJPGonContainer">
            <div class='cg_view_option_title'>
                <p>Min resolution for uploading<br>of JPG pics </p>
             </div>
            <div class='cg_view_option_checkbox'>
                <input id='MinResJPGon' type='checkbox' class="cg-allow-res-checkbox" name='MinResJPGon' $MinResJPGon >
            </div>
        </div>
        <div class='cg_view_option cg_border_top_none cg_border_top_right_none cg_border_left_none' id="MinResJPGwidthContainer">
            <div class='cg_view_option_title'>
                <p>Minimum resolution width<br>for JPGs in pixel<br><span class="cg_view_option_title_note">If empty then no restrictions</span></p>
            </div>
            <div class='cg_view_option_input'>
                <input id="MinResJPGwidth" class="cg_font_size_14" type="text" name="MinResJPGwidth" value="$MinResJPGwidth" maxlength="20"  >
            </div>
        </div>
        <div class='cg_view_option cg_border_top_none cg_border_left_none cg_border_top_none' id="MinResJPGheightContainer">
            <div class='cg_view_option_title'>
                <p>Minimum resolution height<br>for JPGs in pixel<br><span class="cg_view_option_title_note">If empty then no restrictions</span></p>
            </div>
            <div class='cg_view_option_input'>
                <input id="MinResJPGheight" class="cg_font_size_14" type="text" name="MinResJPGheight" value="$MinResJPGheight" maxlength="20" >
            </div>
        </div>
</div>
HEREDOC;

$AllowUploadPNGproVersionRequiredMessage = '';
if(!empty($cgProFalse)){
    $AllowUploadPNGproVersionRequiredMessage = '<br><span class="cg_view_option_title_note"><b>NOTE:</b> since version 16.1.0 PNG file type only available in PRO version</span>';
}

echo <<<HEREDOC
<div class="cg_view_options_row" id="AllowUploadPNGContainerRow">
        <div class="cg_view_option cg_view_option_100_percent cg_border_bottom_none cg_border_top_none $cgProFalse" id="AllowUploadPNGContainer">
            <div class="cg_view_option_title" >
                <p style="margin-right: -30px;">Allow upload PNG$AllowUploadPNGproVersionRequiredMessage</p>
            </div>
            <div class="cg_view_option_checkbox cg_view_option_checked">
                <input type="checkbox" name="AllowUploadPNG" id="AllowUploadPNG" $AllowUploadPNG>
            </div>
        </div>
</div>
<div class='cg_view_options_row'>
        <div class='cg_view_option cg_border_top_right_bottom_none cg-allow-res cg-pro-false-no-label $cgProFalse' id="MaxResPNGonContainer">
            <div class='cg_view_option_title'>
                <p>Max resolution for uploading<br>of PNG pics<span class="cg_view_option_title_note">
                <span class="cg-info-position-relative"><br>
<span class="cg-info-icon">more info</span>
 <span class="cg-info-container" style="top: 52px; left: 10px; display: none;">This allows you to restrict the resolution of the pictures which will be uploaded in frontend. It depends on your web hosting provider how big resolution ca be be for uploaded pics. If your webhosting service is not so powerfull then you should use this restriction.
 </span>
 </span>
 </span>
  <span class="cg_allow_res_note cg_hide">
  <span class="cg_allow_res_note_title">Pay attention:</span>If resolution of an image is to high some servers have not enough power to convert that to lower
resoultions. Official WordPress Api which is used by plugin converts every uploaded image
to lower resolution. Then image with required resolution can be taken to reduce traffic and image load in frontend.
You have to find out by testing on yourself what resolution your server can handle. If your server can not handle the resolution it will lead in some timeout or overload error when uploading.
</span>
 </p>
            </div>
            <div class='cg_view_option_checkbox'>
                <input id='MaxResPNGon' type='checkbox' class="cg-allow-res-checkbox" name='MaxResPNGon' $MaxResPNGon >
            </div>
        </div>
        <div class='cg_view_option cg_border_top_right_bottom_none cg_border_left_none cg-pro-false-no-label $cgProFalse' id="MaxResPNGwidthContainer">
            <div class='cg_view_option_title'>
                <p>Maximum resolution width<br>for PNGs in pixel<br><span class="cg_view_option_title_note">If empty then no restrictions</span></p>
            </div>
            <div class='cg_view_option_input'>
                <input id="MaxResPNGwidth" class="cg_font_size_14" type="text" name="MaxResPNGwidth" value="$MaxResPNGwidth" maxlength="20"  >
            </div>
        </div>
        <div class='cg_view_option cg_border_top_bottom_none cg_border_left_none cg-pro-false-no-label $cgProFalse' id="MaxResPNGheightContainer">
            <div class='cg_view_option_title'>
                <p>Maximum resolution height<br>for PNGs in pixel<br><span class="cg_view_option_title_note">If empty then no restrictions</span></p>
            </div>
            <div class='cg_view_option_input'>
                <input id="MaxResPNGheight" class="cg_font_size_14" type="text" name="MaxResPNGheight" value="$MaxResPNGheight" maxlength="20" >
            </div>
        </div>
</div>
<div class='cg_view_options_row'>
        <div class='cg_view_option cg_border_top_none cg_border_top_right_none cg-allow-res' id="MinResPNGonContainer">
            <div class='cg_view_option_title'>
                <p>Min resolution for uploading<br>of PNG pics </p>
             </div>
            <div class='cg_view_option_checkbox'>
                <input id='MinResPNGon' type='checkbox' class="cg-allow-res-checkbox" name='MinResPNGon' $MinResPNGon >
            </div>
        </div>
        <div class='cg_view_option cg_border_top_none cg_border_top_right_none cg_border_left_none' id="MinResPNGwidthContainer">
            <div class='cg_view_option_title'>
                <p>Minimum resolution width<br>for PNGs in pixel<br><span class="cg_view_option_title_note">If empty then no restrictions</span></p>
            </div>
            <div class='cg_view_option_input'>
                <input id="MinResPNGwidth" class="cg_font_size_14" type="text" name="MinResPNGwidth" value="$MinResPNGwidth" maxlength="20"  >
            </div>
        </div>
        <div class='cg_view_option cg_border_top_none cg_border_left_none cg_border_top_none' id="MinResPNGheightContainer">
            <div class='cg_view_option_title'>
                <p>Minimum resolution height<br>for PNGs in pixel<br><span class="cg_view_option_title_note">If empty then no restrictions</span></p>
            </div>
            <div class='cg_view_option_input'>
                <input id="MinResPNGheight" class="cg_font_size_14" type="text" name="MinResPNGheight" value="$MinResPNGheight" maxlength="20" >
            </div>
        </div>
</div>
HEREDOC;

$AllowUploadGIFproVersionRequiredMessage = '';
if(!empty($cgProFalse)){
    $AllowUploadGIFproVersionRequiredMessage = '<br><span class="cg_view_option_title_note"><b>NOTE:</b> since version 16.1.0 GIF file type only available in PRO version</span>';
}

echo <<<HEREDOC
<div class="cg_view_options_row" id="AllowUploadGIFContainerRow">
        <div class="cg_view_option cg_view_option_100_percent cg_border_bottom_none cg_border_top_none $cgProFalse" id="AllowUploadGIFContainer">
            <div class="cg_view_option_title" >
                <p style="margin-right: -30px;">Allow upload GIF$AllowUploadGIFproVersionRequiredMessage</p>
            </div>
            <div class="cg_view_option_checkbox cg_view_option_checked">
                <input type="checkbox" name="AllowUploadGIF" id="AllowUploadGIF" $AllowUploadGIF>
            </div>
        </div>
</div>
<div class='cg_view_options_row'>
        <div class='cg_view_option  cg_border_top_right_bottom_none cg-allow-res cg-pro-false-no-label $cgProFalse' id="MaxResGIFonContainer">
            <div class='cg_view_option_title'>
                <p>Max resolution for uploading<br>of GIF pics<span class="cg_view_option_title_note">
                <span class="cg-info-position-relative"><br>
<span class="cg-info-icon">more info</span>
 <span class="cg-info-container" style="top: 52px; left: 10px; display: none;">This allows you to restrict the resolution of the pictures which will be uploaded in frontend. It depends on your web hosting provider how big resolution ca be be for uploaded pics. If your webhosting service is not so powerfull then you should use this restriction.
 </span>
 </span>
 </span>
  <span class="cg_allow_res_note cg_hide">
  <span class="cg_allow_res_note_title">Pay attention:</span>If resolution of an image is to high some servers have not enough power to convert that to lower
resoultions. Official WordPress Api which is used by plugin converts every uploaded image
to lower resolution. Then image with required resolution can be taken to reduce traffic and image load in frontend.
You have to find out by testing on yourself what resolution your server can handle. If your server can not handle the resolution it will lead in some timeout or overload error when uploading.
</span>
 </p>
            </div>
            <div class='cg_view_option_checkbox'>
                <input id='MaxResGIFon' type='checkbox'  class="cg-allow-res-checkbox" name='MaxResGIFon' $MaxResGIFon >
            </div>
        </div>
        <div class='cg_view_option cg_border_top_right_bottom_none cg_border_left_none cg-pro-false-no-label $cgProFalse' id="MaxResGIFwidthContainer">
            <div class='cg_view_option_title'>
                <p>Maximum resolution width<br>for GIFs in pixel<br><span class="cg_view_option_title_note">If empty then no restrictions</span></p>
            </div>
            <div class='cg_view_option_input'>
                <input id="MaxResGIFwidth" class="cg_font_size_14" type="text" name="MaxResGIFwidth" value="$MaxResGIFwidth" maxlength="20"  >
            </div>
        </div>
        <div class='cg_view_option cg_border_top_bottom_none cg_border_left_none cg-pro-false-no-label $cgProFalse' id="MaxResGIFheightContainer">
            <div class='cg_view_option_title'>
                <p>Maximum resolution height<br>for GIFs in pixel<br><span class="cg_view_option_title_note">If empty then no restrictions</span></p>
            </div>
            <div class='cg_view_option_input'>
                <input id="MaxResGIFheight" class="cg_font_size_14" type="text" name="MaxResGIFheight" value="$MaxResGIFheight" maxlength="20" >
            </div>
        </div>
</div>
<div class='cg_view_options_row'>
        <div class='cg_view_option cg_border_top_none cg_border_top_right_none cg-allow-res' id="MinResGIFonContainer">
            <div class='cg_view_option_title'>
                <p>Min resolution for uploading<br>of GIF pics </p>
             </div>
            <div class='cg_view_option_checkbox'>
                <input id='MinResGIFon' type='checkbox' class="cg-allow-res-checkbox" name='MinResGIFon' $MinResGIFon >
            </div>
        </div>
        <div class='cg_view_option cg_border_top_none cg_border_top_right_none cg_border_left_none' id="MinResGIFwidthContainer">
            <div class='cg_view_option_title'>
                <p>Minimum resolution width<br>for GIFs in pixel<br><span class="cg_view_option_title_note">If empty then no restrictions</span></p>
            </div>
            <div class='cg_view_option_input'>
                <input id="MinResGIFwidth" class="cg_font_size_14" type="text" name="MinResGIFwidth" value="$MinResGIFwidth" maxlength="20"  >
            </div>
        </div>
        <div class='cg_view_option cg_border_top_none cg_border_left_none cg_border_top_none' id="MinResGIFheightContainer">
            <div class='cg_view_option_title'>
                <p>Minimum resolution height<br>for GIFs in pixel<br><span class="cg_view_option_title_note">If empty then no restrictions</span></p>
            </div>
            <div class='cg_view_option_input'>
                <input id="MinResGIFheight" class="cg_font_size_14" type="text" name="MinResGIFheight" value="$MinResGIFheight" maxlength="20" >
            </div>
        </div>
</div>
HEREDOC;

echo <<<HEREDOC
</div>
HEREDOC;

echo <<<HEREDOC
    <div class='cg_view_options_rows_container'>
        <p class='cg_view_options_rows_container_title'>Limit contact entries and user recognition methods</span></p>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_border_right_none cg_view_option_50_percent $cgProFalse cg_border_radius_unset' id="RegUserMaxUploadContainer" >
                    <div class='cg_view_option_title'>
                        <p>Contact entries total per user<br><span class="cg_view_option_title_note">0 or empty = no limit</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input id="RegUserMaxUpload" type="text" name="RegUserMaxUpload" value="$RegUserMaxUpload" maxlength="20" >
                    </div>
                </div>
                <div class='cg_view_option cg_view_option_50_percent $cgProFalse cg_border_top_right_radius_8_px' id="RegUserMaxUploadPerCategoryContainer" >
                    <div class='cg_view_option_title'>
                        <p>Contact entries for a user per category<br><span class="cg_view_option_title_note">0 or empty = no limit</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input id="RegUserMaxUploadPerCategory" type="text" name="RegUserMaxUploadPerCategory" value="$RegUserMaxUploadPerCategory" maxlength="20" >
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_100_percent cg_border_top_none CheckMethodUploadContainer $cgProFalse' id="CheckIpUploadContainer" >
                    <div class='cg_view_option_title'>
                        <p>Check by IP<br/><span class="cg_view_option_title_note">IP will be tracked always$userIPunknown</span></p>
                    </div>
                    <div class='cg_view_option_radio'>
                        <input type="radio" name="RegUserUploadOnly" class="CheckMethodUpload" id="CheckIpUpload" value="3" $CheckIpUpload $checkIpCheckUpload>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_100_percent CheckMethodUploadContainer cg_border_top_none $cgProFalse'  id="CheckCookieUploadContainer"  >
                    <div class='cg_view_option_title'>
                        <p>Check by Cookie<br/><span class="cg_view_option_title_note">Cookie will be only set and tracked if this option is activated. Will be not set if administrator uploads files in WordPress backend area.</span></p>
                    </div>
                    <div class='cg_view_option_radio'>
                        <input type="radio" name="RegUserUploadOnly" class="CheckMethodUpload" id="CheckCookieUpload" value="2" $CheckCookieUpload>
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse' id="UploadRequiresCookieMessageContainer">
                    <div class='cg_view_option_title'>
                        <p>Check Cookie alert message if user browser does not allow cookies</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" class="cg-long-input" placeholder="Please allow cookies to use contact form" id="UploadRequiresCookieMessage" name="UploadRequiresCookieMessage" maxlength="1000" value="$UploadRequiresCookieMessage" >
                    </div>
                </div>
            </div>
            <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_100_percent CheckMethodUploadContainer cg_border_top_none $cgProFalse'   id="CheckLoginUploadContainer"   >
                    <div class='cg_view_option_title'>
                        <p>Check if is registered user<br/><span class="cg_view_option_title_note">User have to be registered and logged in to be able to contact.<br>User WordPress ID will be always tracked if user is logged in.
                        <br><strong>NEW!</strong> WordPress account can be easy created via Google sign in button now!<br>Check "Login via Google" options.</span>
                        </span>
                        </p>
                    </div>
                    <div class='cg_view_option_radio'>
                        <input type="radio" name="RegUserUploadOnly" class="CheckMethodUpload" id="CheckLoginUpload" value="1" $CheckLoginUpload>
                    </div>
                </div>
            </div>
             <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse' id="RegUserUploadOnlyTextContainer" >
                    <div class='cg_view_option_title'>
                        <p>Show text instead of contact form<br/><span class="cg_view_option_title_note">if user is not logged in</span></p>
                    </div>
                    <div class='cg_view_option_html'>
                        <textarea class='cg-wp-editor-template' id='RegUserUploadOnlyText'  name='RegUserUploadOnlyText'>$RegUserUploadOnlyText</textarea>
                    </div>
                </div>
            </div>
    </div>
HEREDOC;



echo <<<HEREDOC
    <div class='cg_view_options_rows_container'>
        <p class='cg_view_options_rows_container_title'>Modify file name frontend upload</p>
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_border_border_top_right_radius_unset cg_border_border_bottom_left_radius_8_px cg_view_option_50_percent cg_border_right_none $cgProFalse" id="CustomImageNameContainer">
                <div class="cg_view_option_title">
                    <p>Modify file name frontend upload</p>
                </div>
                <div class="cg_view_option_checkbox">
                    <input id='CustomImageName' type='checkbox' name='CustomImageName' $CustomImageName >
                </div>
            </div>
            <div class="cg_view_option cg_view_option_50_percent cg_border_border_bottom_left_radius_unset cg_border_border_top_right_radius_8_px cg_view_option_flex_flow_column $cgProFalse" id="CustomImageNamePathContainer">
                <div class="cg_view_option_title cg_view_option_title_full_width">
                    <p>Add parameters to file name<br><span class="cgPreselectSortMessage cg_view_option_title_note cg_hide">(Random sort has to be deactivated)</span>
                    </p>
                </div>
                <div class="cg_view_option_select">
                    <select name='CustomImageNamePath' id='CustomImageNamePath' class='$cgProFalse'>
HEREDOC;


$CustomImageNamePathSelectedValuesArray = array(
    'GalleryId-ImageName','GalleryName-ImageName','WpUserId-ImageName','WpUserName-ImageName',
    'GalleryId-WpUserId-ImageName','GalleryId-WpUserName-ImageName',
    'GalleryName-WpUserId-ImageName','GalleryName-WpUserName-ImageName',
    'WpUserId-GalleryId-ImageName','WpUserId-GalleryName-ImageName',
    'WpUserName-GalleryId-ImageName','WpUserName-GalleryName-ImageName'
);

foreach($CustomImageNamePathSelectedValuesArray as $CustomImageNamePathArrayValue){
    $CustomImageNamePathArrayValueSelected = '';
    if($CustomImageNamePathArrayValue==$CustomImageNamePath){
        $CustomImageNamePathArrayValueSelected = 'selected';
    }
    echo "<option value='$CustomImageNamePathArrayValue' $CustomImageNamePathArrayValueSelected >$CustomImageNamePathArrayValue</option>";
}

echo <<<HEREDOC
                    </select>
                </div>
            </div>
        </div>
</div>
HEREDOC;

echo <<<HEREDOC
    <div class='cg_view_options_rows_container'>
        <p class='cg_view_options_rows_container_title'>Delete by frontend user deleted files from storage also</p>
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_view_option_100_percent cg_border" id="DeleteFromStorageIfDeletedInFrontendContainer">
                <div class="cg_view_option_title">
                    <p>When [cg_gallery_user id="$GalleryID"] shortcode is used<br>and user delete files in frontend<br>files should be deleted from storage also.<br><span class="cg_view_option_title_note">From storage deleted files can not be restored<br>they are permanently deleted.</span></p>
                </div>
                <div class="cg_view_option_checkbox">
                    <input id='DeleteFromStorageIfDeletedInFrontend' type='checkbox' name='DeleteFromStorageIfDeletedInFrontend' $DeleteFromStorageIfDeletedInFrontend >
                </div>
            </div>
        </div>
</div>
HEREDOC;

if(strpos($mailExceptions,'E-mail to user after upload') !== false || strpos($mailExceptions,'E-mail to user after contact') !== false){
    $mailExceptionUserUploadMail = "<div style=\"width:330px;margin: -8px auto 15px;\"><a href=\"?page=".cg_get_version()."/index.php&amp;corrections_and_improvements=true&amp;option_id=$galeryNR\" class='cg_load_backend_link'><input class=\"cg_backend_button cg_backend_button_back cg_backend_button_warning\" type=\"button\" value=\"There were mail exceptions for this mailing type\" style=\"width:330px;\"></a>
</div>";
}else{
    $mailExceptionUserUploadMail = "<div style=\"width:280px;margin: -8px auto 15px;\"><a href=\"?page=".cg_get_version()."/index.php&amp;corrections_and_improvements=true&amp;option_id=$galeryNR\" class='cg_load_backend_link'><input class=\"cg_backend_button cg_backend_button_back cg_backend_button_success\" type=\"button\" value=\"No mail exceptions for this mailing type\" style=\"width:280px;\"></a>
</div>";
}

$InformUserUpload = ($selectSQLemailUserUpload->InformUserUpload==1) ? 'checked' : '';
$InformUserUploadHeader = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLemailUserUpload->Header);
$InformUserUploadSubject = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLemailUserUpload->Subject);
$InformUserUploadReply = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLemailUserUpload->Reply);
$InformUserUploadCC = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLemailUserUpload->CC);
$InformUserUploadBCC = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLemailUserUpload->BCC);
$ContentUserUploadContent = contest_gal1ery_convert_for_html_output_without_nl2br($selectSQLemailUserUpload->Content);
$InformUserUploadContentInfoWithoutFileSource = ($selectSQLemailUserUpload->ContentInfoWithoutFileSource==1) ? 'checked' : '';

echo <<<HEREDOC
    <div class='cg_view_options_rows_container'>
        <p class='cg_view_options_rows_container_title'>E-mail to frontend user after frontend contact
        <br><span class="cg_view_options_rows_container_title_note"><span class="cg_color_red">NOTE:</span> relating testing - e-mail where is send to should not contain $cgYourDomainName.<br>Many servers can not send to own domain.</span></p>
        $mailExceptionUserUploadMail
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_border_border_top_left_radius_8_px cg_border_border_top_right_radius_8_px cg_view_option_100_percent $cgProFalse" id="cgInformUserUploadContainer">
                <div class="cg_view_option_title">
                    <p>Inform user after successfull contact in frontend
                        <br><span class="cg_view_option_title_note">If user e-mail exists in form or user is registered and logged in</span>
                    </p>
                </div>
                <div class="cg_view_option_checkbox">
                    <input id='InformUserUpload' type='checkbox' name='InformUserUpload' $InformUserUpload >
                </div>
            </div>
        </div>
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse cg_inform_user_upload' id="cgInformUserUploadFromContainer" >
                    <div class='cg_view_option_title'>
                        <p>Header<br><span class="cg_view_option_title_note">Like your company name or something like that, not an e-mail</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="InformUserUploadHeader" id="cgInformUserUploadFrom" value="$InformUserUploadHeader"  maxlength="200" >
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse cg_inform_user_upload' id="cgInformUserUploadReplyContainer" >
                    <div class='cg_view_option_title'>
                        <p>Reply e-mail (address From)<br><span class="cg_view_option_title_note">Should not be empty</span>
                        </p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="InformUserUploadReply" id="cgInformUserUploadReply" value="$InformUserUploadReply"  maxlength="200" >
                    </div>
                </div>
        </div>
HEREDOC;

echo <<<HEREDOC
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse cg_inform_user_upload' id="cgInformUserUploadCCContainer" >
                    <div class='cg_view_option_title'>
                        <p>CC e-mail<br><span class="cg_view_option_title_note">Should not be the same as "Reply e-mail"<br>Sending to multiple recipients example (mail1@example.com; mail2@example.com; mail3@example.com</span>
                        </p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="InformUserUploadCC" id="cgInformUserUploadCC cg_inform_user_upload" value="$InformUserUploadCC"  maxlength="200" >
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse cg_inform_user_upload' id="cgInformUserUploadBCCContainer" >
                    <div class='cg_view_option_title'>
                        <p>BCC e-mail<br><span class="cg_view_option_title_note">Should not be the same as "Reply e-mail"<br>Sending to multiple recipients example (mail1@example.com; mail2@example.com; mail3@example.com</span>
                        </p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="InformUserUploadBCC" id="cgInformUserUploadBCC" value="$InformUserUploadBCC"  maxlength="200" >
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse cg_inform_user_upload' id="cgInformUserUploadHeaderContainer" >
                    <div class='cg_view_option_title'>
                        <p>Subject</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="InformUserUploadSubject" id="cgInformUserUploadUbject" value="$InformUserUploadSubject"  maxlength="200" >
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse cg_inform_user_upload'  id="wp-InformUserUploadContent-wrap-Container" >
                    <div class='cg_view_option_title cg_copyable'>
                        <p>Mail content<br><span class="cg_view_option_title_note">Use <span style="font-weight:bold;">\$info$</span> 
                        in the editor if you like to attach user info like contact fields and original file sources</span></p>
                    </div>
                    <div class='cg_view_option_html'>
                        <textarea class='cg-wp-editor-template' id='InformUserUploadContent'  name='InformUserUploadContent'>$ContentUserUploadContent</textarea>
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_border_top_none cg_view_option_100_percent $cgProFalse cg_inform_user_upload" id="cgInformUserContentInfoWithoutFileSourceContainer">
                <div class="cg_view_option_title">
                    <p>Display \$info$ without original file source
                        <br><span class="cg_view_option_title_note">
                        Upload file source URLs will be not visible in <span style="font-weight:bold;">\$info$</span> data
                        </span>
                    </p>
                </div>
                <div class="cg_view_option_checkbox">
                    <input id='InformUserContentInfoWithoutFileSource' type='checkbox' name='InformUserContentInfoWithoutFileSource' $InformUserUploadContentInfoWithoutFileSource >
                </div>
            </div>
        </div>
</div>
HEREDOC;

echo <<<HEREDOC
</div>
HEREDOC;


