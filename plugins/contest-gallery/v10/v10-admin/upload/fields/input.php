<?php

if(!$isOnlyPlaceHolder){
    $Show_Slider = $wpdb->get_var("SELECT Show_Slider FROM $tablename_form_input WHERE id = '$id'");

    if($Show_Slider==1){$checkedShow_Slider='checked';}
    else{$checkedShow_Slider='';}

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

$valueFieldTitle = 'Title';

// Anfang des Formularteils
echo "<div id='$nfCount'  class='formField inputField'><input type='hidden' class='cg_type' name='upload[$id][type]' value='nf'>";
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

    $IsForWpPageTitle = $inputRow->IsForWpPageTitle;

    if($IsForWpPageTitle==1){$checkedIsForWpPageTitle='checked';}
    else{$checkedIsForWpPageTitle='';}

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
        }

    }


    $nfCount++;
    $nfHiddenCount++;

}

echo <<<HEREDOC
<div class="cg_view_options_row cg_view_options_row_title cg_view_options_row_collapse" title="Collapse">
        <div class="cg_view_options_row_marker cg_hide"><div class="cg_view_options_row_marker_title">Field title</div><div class="cg_view_options_row_marker_content"></div></div>
        <div class="cg_view_option cg_view_option_header cg_view_option_not_disable cg_border_bottom_none cg_view_option_100_percent">
            <div class="cg_view_option_title cg_view_option_title_header">
                <p>Input</p>
            </div>
        </div>
</div>
HEREDOC;

$useForEntry = '';
if(floatval($dbGalleryVersion)>=21){
$useForEntry = <<<HEREDOC
<div class='cg_view_options_row'>
    <div  class='cg_view_option cg_view_option_50_percent cg_border_right_none cg_border_bottom_none cg_entry_page_title' >
        <div class='cg_view_option_title cg_view_option_title_full_width'>
            <p>
                Use as entry page title (only 1 allowed)<br>
                <span class="cg_view_option_title_note"><b>NOTE:</b> will be used for page title, page link slug and og page title<br>(og = open graph tag for social media share)</span>
                <br>if not set file name or the word "entry" will be taken
            </p>
        </div>
        <div class="cg_view_option_checkbox">
              <input type="checkbox" name="upload[$id][IsForWpPageTitle]" $checkedIsForWpPageTitle>
        </div>
    </div>
    <div  class='cg_view_option cg_view_option_50_percent cg_border_bottom_none cg_entry_page_description'>
        <div class='cg_view_option_title cg_view_option_title_full_width'>
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
     <div class='$cgProFalse cg_pro_false_unset cg_view_option $cg_disabled_sub_and_third_title cg_view_option_25_percent cg_border_bottom_none cg_border_right_none cg_info_show_gallery_sub_title'>
        <div class='cg_view_option_title cg_view_option_title_full_width'>
            <p>Show as sub title in gallery view<br>(only 1 allowed)<br><span class="cg_view_option_title_note"><b>NOTE:</b> modern design, sub title will be displayed above main title in smaller font size</span>$cg_disabled_sub_and_third_title_note</p>
        </div>
        <div class="cg_view_option_checkbox">
              <input type="checkbox" name="upload[$id][SubTitle]" $checkedSubTitle>
        </div>
    </div>    
     <div class='$cgProFalse cg_view_option $cg_disabled_sub_and_third_title cg_view_option_25_percent cg_border_bottom_none cg_border_right_none cg_info_show_gallery_third_title'>
        <div class='cg_view_option_title cg_view_option_title_full_width'>
            <p>Show as third title in gallery view<br>(only 1 allowed)<br><span class="cg_view_option_title_note"><b>NOTE:</b> modern design, will be displayed under the main title in italic font</span>$cg_disabled_sub_and_third_title_note</p>
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
    <div  class='cg_view_option cg_border_right_none cg_border_bottom_none '>
        <div class='cg_view_option_title cg_view_option_title_full_width '>
            <p>Show as info in single entry view</p>
        </div>
        <div class="cg_view_option_checkbox">
              <input type="checkbox" name="upload[$id][infoInSlider]" $checkedShow_Slider>
        </div>
    </div> 
     <div class='cg_view_option cg_view_option_watermark cg_border_bottom_none cg_border_right_none '>
        <div class='cg_view_option_title '>
            <p>Use as watermark for gallery images: (only 1 allowed)<br>
                <span class="cg_view_option_title_note"><b>NOTE:</b> CSS based, original image source will be not watermarked</span>
            </p>
        </div>
        <div class="cg_view_option_checkbox">
              <input type="checkbox" name="upload[$id][watermarkChecked]" $checkedWatermark>
        </div>
    </div>
     <div  class='cg_view_option  cg_view_option_not_disable  cg_border_bottom_none cg_view_option_watermark_position cg_view_option_flex_flow_column $watermarkPositionDisabled'>
        <div class='cg_view_option_title cg_border_left_none  cg_view_option_title_full_width '>
            <p>Watermark position</p>
        </div>
          <div class="cg_view_option_select cg_view_option_input_full_width">
                <select class='cg_watermark_position' name='upload[$id][watermarkPosition]'>
                    <option value='top-left' $watermarkPositionTopLeftChecked>Top Left</option>
                    <option value='top-right' $watermarkPositionTopRightChecked>Top Right</option>
                    <option value='bottom-left' $watermarkPositionBottomLeftChecked>Bottom Left</option>
                    <option value='bottom-right' $watermarkPositionBottomRightChecked>Bottom Right</option>
                    <option value='center' $watermarkPositionCenterChecked>Center</option>
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
            <input  class="cg_view_option_input_field_title" type="text" name="upload[$id][title]" value='$valueFieldTitle' size="30">
        </div>
    </div>
     <div class='cg_view_option cg_border_bottom_none cg_view_option_flex_flow_column'>
        <div class='cg_view_option_title cg_view_option_title_full_width '>
            <p>Input placeholder</p>
        </div>
        <div class="cg_view_option_input cg_view_option_input_full_width" >
            <input type="text" name="upload[$id][content]" value='$valueFieldPlaceholder' size="30">
        </div>
    </div>
     <div class='$cgProFalse cg_view_option cg_border_bottom_none cg_view_option_flex_flow_column'>
        <div class='cg_view_option_title cg_view_option_title_full_width'>
            <p>Use to add to WordPress post field</p>
        </div>
          <div class="cg_view_option_select cg_view_option_input_full_width" style="margin-bottom: 6px;">
                <select  name='upload[$id][WpAttachmentDetailsType]'>
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

