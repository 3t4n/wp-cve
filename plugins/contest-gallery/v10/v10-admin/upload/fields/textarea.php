<?php

if(!$isOnlyPlaceHolder){
    $Show_Slider = $wpdb->get_var("SELECT Show_Slider FROM $tablename_form_input WHERE id = '$id'");

    if($Show_Slider==1){$checkedShow_Slider='checked';}
    else{$checkedShow_Slider='';}

//ermitteln der Feldnummer
    $fieldOrder = $value->Field_Order;
    $fieldOrderKey = "$fieldOrder";
    $id = $value->id; // Unique ID des Form Feldes
    $idKey = "$id";
    if($value->Active==0){
        $hideChecked = "checked='checked'";
    }
    else{
        $hideChecked = "";
    }
}

$valueFieldTitle = 'Description';

// Anfang des Formularteils
echo "<div id='$kfCount'  class='formField textareaField'><input type='hidden' name='upload[$id][type]' value='kf'>";
echo "<div class='cg_remove' title='Remove field' data-cg-id='$id'></div>";
echo "<div class='cg_drag_area' ><img class='cg_drag_area_icon' src='$cgDragIcon'></div>";

echo "<input type='hidden' class='fieldOrder' name='upload[$id][order]' value='$fieldOrder'>";
echo "<div class='formFieldInnerDiv'>";

echo "<input type='hidden' value='$fieldOrder' class='fieldnumber'>";

if(!$isOnlyPlaceHolder){
    if($id==$Field1IdGalleryView){$checked='checked';}
    else{$checked='';}

    $inputRow = $wpdb->get_row("SELECT * FROM $tablename_form_input WHERE id = '$id'");

    $Show_Slider = $inputRow->Show_Slider;

    if($Show_Slider==1){$checkedShow_Slider='checked';}
    else{$checkedShow_Slider='';}

    if($id==$Field2IdGalleryView){$checkedShowTag='checked';}
    else{$checkedShowTag='';}

    $IsForWpPageDescription = $inputRow->IsForWpPageDescription;

    if($IsForWpPageDescription==1){$checkedIsForWpPageDescription='checked';}
    else{$checkedIsForWpPageDescription='';}

    $SubTitle = $inputRow->SubTitle;

    if($SubTitle==1){$checkedSubTitle='checked';}
    else{$checkedSubTitle='';}

    $ThirdTitle = $inputRow->ThirdTitle;

    if($ThirdTitle==1){$checkedThirdTitle='checked';}
    else{$checkedThirdTitle='';}

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

        if($key=='content'){
            $valueFieldPlaceholder = contest_gal1ery_convert_for_html_output_without_nl2br($valueFieldContent);// because of possible textarea values do not use ..._without_nl2br
        }else{
            $valueFieldContent = contest_gal1ery_convert_for_html_output($valueFieldContent);// because of possible textarea values do not use ..._without_nl2br
        }

        if($key=='titel'){
            $valueFieldTitle = $valueFieldContent;
        }



        if($key=='min-char'){
            $minChar = $valueFieldContent;
        }

        if($key=='max-char'){
            $maxChar = $valueFieldContent;
        }

        if($key=='mandatory'){

            $requiredChecked = ($valueFieldContent=='on') ? "checked" : "";


        }

    }


    $kfCount++;
    $kfHiddenCount++;
}

echo <<<HEREDOC
<div class="cg_view_options_row cg_view_options_row_title cg_view_options_row_collapse" title="Collapse">
            <div class="cg_view_options_row_marker cg_hide"><div class="cg_view_options_row_marker_title">Field title</div><div class="cg_view_options_row_marker_content"></div></div>
        <div class="cg_view_option cg_view_option_not_disable cg_border_bottom_none cg_view_option_100_percent">
            <div class="cg_view_option_title cg_view_option_title_header">
                <p>Textarea</p>
            </div>
        </div>
</div>
HEREDOC;


$useForEntry = '';
if(floatval($dbGalleryVersion)>=21){
    $useForEntry = <<<HEREDOC
<div class='cg_view_options_row'>
    <div  class='cg_view_option cg_entry_page_description cg_view_option_100_percent cg_border_bottom_none '>
        <div class='cg_view_option_title  cg_view_option_title_full_width'>
            <p>
                Use as entry page description (only 1 allowed)<br>
                <span class="cg_view_option_title_note"><b>NOTE:</b> will be used for page description and og page description<br>(og = open graph tag for social media share)</span>
            </p>
        </div>
        <div class="cg_view_option_checkbox">
              <input class="cg_entry_page_description"  type="checkbox" name="upload[$id][IsForWpPageDescription]" $checkedIsForWpPageDescription>
        </div>
    </div>
</div>
HEREDOC;
}

echo $useForEntry;

echo <<<HEREDOC
<div class='cg_view_options_row'>
     <div class='cg_view_option cg_view_option_25_percent cg_border_bottom_none cg_border_right_none cg_info_show_gallery'>
        <div class='cg_view_option_title cg_view_option_title_full_width '>
            <p>Show as main title in gallery view<br>(only 1 allowed)<br><span class="cg_view_option_title_note"><b>NOTE:</b> will be also displayed in single entry view instead of file if entry was done without file upload</span></p>
        </div>
        <div class="cg_view_option_checkbox">
              <input type="checkbox" name="upload[$id][infoInGallery]" $checked>
        </div>
    </div>
     <div class='$cgProFalse cg_pro_false_unset $cg_disabled_sub_and_third_title cg_view_option cg_view_option_25_percent cg_border_bottom_none cg_border_right_none cg_info_show_gallery_sub_title'>
        <div class='cg_view_option_title cg_view_option_title_full_width'>
            <p>Show as sub title in gallery view<br>(only 1 allowed)<br><span class="cg_view_option_title_note"><b>NOTE:</b> modern design, sub title will be displayed above main title in smaller font size</span></p>
        </div>
        <div class="cg_view_option_checkbox">
              <input type="checkbox" name="upload[$id][SubTitle]" $checkedSubTitle>
        </div>
    </div>    
     <div class='$cgProFalse cg_view_option $cg_disabled_sub_and_third_title cg_view_option_25_percent cg_border_bottom_none cg_border_right_none cg_info_show_gallery_third_title'>
        <div class='cg_view_option_title cg_view_option_title_full_width'>
            <p>Show as third title in gallery view<br>(only 1 allowed)<br><span class="cg_view_option_title_note"><b>NOTE:</b> modern design, will be displayed under the main title in italic font</span></p>
        </div>
        <div class="cg_view_option_checkbox">
              <input type="checkbox" name="upload[$id][ThirdTitle]" $checkedThirdTitle>
        </div>
    </div>
     <div  class='cg_view_option cg_view_option_25_percent cg_border_bottom_none cg_tag_in_gallery  '>
        <div class='cg_view_option_title cg_view_option_title_full_width'>
            <p>Show as HTML title attribute in gallery<br>(only 1 allowed)<br><span class="cg_view_option_title_note"><b>NOTE:</b> appears when on hover with mouse</span></p>
        </div>
        <div class="cg_view_option_checkbox">
              <input type="checkbox" name="upload[$id][tagInGallery]" $checkedShowTag>
        </div>
    </div>
</div>
HEREDOC;

echo <<<HEREDOC
<div class='cg_view_options_row'>
</div>
HEREDOC;
echo <<<HEREDOC
<div class='cg_view_options_row'>
    <div  class='cg_view_option cg_view_option_50_percent  cg_border_bottom_none cg_border_right_none '>
        <div class='cg_view_option_title cg_view_option_title_full_width '>
            <p>Show as info in single entry view</p>
        </div>
        <div class="cg_view_option_checkbox">
              <input type="checkbox" name="upload[$id][infoInSlider]" $checkedShow_Slider>
        </div>
    </div> 
    <div  class='$cgProFalse cg_view_option cg_view_option_50_percent  cg_border_bottom_none cg_view_option_flex_flow_column '>
        <div class='cg_view_option_title cg_view_option_title_full_width'>
            <p>Use to add to WordPress post field</p>
        </div>
          <div class="cg_view_option_select cg_view_option_input_full_width" style="margin-bottom: 6px;">
                <select name='upload[$id][WpAttachmentDetailsType]'>
                    <option value='' >Please select</option>
                    <option value='alt' $WpAttachmentDetailsTypeAltChecked>Alternative text</option>
                    <option value='title' $WpAttachmentDetailsTypeTitleChecked>Title</option>
                    <option value='caption' $WpAttachmentDetailsTypeCaptionChecked>Caption</option>
                    <option value='description' $WpAttachmentDetailsTypeDescriptionChecked>Description</option>
                </select>
        </div>
    </div> 
</div>
HEREDOC;

echo <<<HEREDOC
<div class='cg_view_options_row'>
    <div  class='cg_view_option cg_view_option_33_percent   cg_border_right_none cg_border_bottom_none cg_view_option_flex_flow_column'>
        <div class='cg_view_option_title cg_view_option_title_full_width '>
            <p>Field title</p>
        </div>
        <div class="cg_view_option_input cg_view_option_input_full_width" >
            <input  class="cg_view_option_input_field_title"  type="text" name="upload[$id][title]" value='$valueFieldTitle' size="30">
        </div>
    </div>
     <div class='cg_view_option cg_view_option_67_percent  cg_border_bottom_none cg_view_option_flex_flow_column'>
        <div class='cg_view_option_title cg_view_option_title_full_width '>
            <p>Textarea placeholder</p>
        </div>
        <div class="cg_view_option_input_full_width" >
            <textarea name='upload[$id][content]' maxlength='10000' style='width:100%;' placeholder='Placeholder'  rows='6'>$valueFieldPlaceholder</textarea>
        </div>
    </div>
</div>
HEREDOC;

echo <<<HEREDOC
<div class='cg_view_options_row'>
    <div  class='cg_view_option cg_view_option_50_percent  cg_border_right_none cg_border_bottom_none cg_view_option_flex_flow_column'>
        <div class='cg_view_option_title cg_view_option_title_full_width '>
            <p>Min char</p>
        </div>
        <div class="cg_view_option_input cg_view_option_input_full_width" >
            <input type="text" name="upload[$id][min-char]" value="$minChar" size="30">
        </div>
    </div>
     <div class='cg_view_option cg_view_option_50_percent  cg_border_bottom_none cg_view_option_flex_flow_column'>
        <div class='cg_view_option_title cg_view_option_title_full_width '>
            <p>Max char</p>
        </div>
        <div class="cg_view_option_input cg_view_option_input_full_width" >
            <input type="text" name="upload[$id][max-char]" value="$maxChar" size="30">
        </div>
    </div>
</div>
HEREDOC;

echo <<<HEREDOC
<div class='cg_view_options_row'>
    <div  class='cg_view_option cg_view_option_50_percent cg_border_right_none '>
        <div class='cg_view_option_title cg_view_option_title_full_width '>
            <p>Required</p>
        </div>
        <div class="cg_view_option_checkbox">
              <input type="checkbox" name="upload[$id][required]" $requiredChecked>
        </div>
    </div>
     <div class='cg_view_option cg_view_option_hide_upload_field cg_view_option_50_percent '>
        <div class='cg_view_option_title cg_view_option_title_full_width'>
            <p>Hide<br><span class="cg_view_option_title_note"><b>NOTE:</b> Will not be visible in contact form</span></p>
        </div>
        <div class="cg_view_option_checkbox">
              <input type="checkbox" name="upload[$id][hide]" $hideChecked>
        </div>
    </div>
</div>
HEREDOC;

echo "</div>";
echo "</div>";

