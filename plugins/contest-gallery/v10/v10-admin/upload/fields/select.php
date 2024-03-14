<?php

if(!$isOnlyPlaceHolder){
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

$valueFieldTitle = 'Select';

// Anfang des Formularteils
echo "<div id='$seCount'  class='formField selectField'><input type='hidden' name='upload[$id][type]' value='se'>";
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

// Formularfelder unserializen
    $fieldContent = unserialize($value->Field_Content);

    $valueFieldTitle = '';
    $valueFieldPlaceholder = '';
    $minChar = '';
    $maxChar = '';
    $requiredChecked = '';

    foreach($fieldContent as $key => $valueFieldContent){

        if($key=='content'){
            $valueFieldPlaceholder = $valueFieldContent;
            $valueFieldContent = contest_gal1ery_convert_for_html_output_without_nl2br($valueFieldContent);// for textarea select without_nl2br
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
        $seCount++;
        $seHiddenCount++;


    }
}

echo <<<HEREDOC
<div class="cg_view_options_row cg_view_options_row_title cg_view_options_row_collapse" title="Collapse">
            <div class="cg_view_options_row_marker cg_hide"><div class="cg_view_options_row_marker_title">Field title</div><div class="cg_view_options_row_marker_content"></div></div>
        <div class="cg_view_option cg_view_option_not_disable cg_border_bottom_none cg_view_option_100_percent">
            <div class="cg_view_option_title cg_view_option_title_header">
                <p>Select</p>
            </div>
        </div>
</div>
HEREDOC;

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
        <div class='cg_view_option_title cg_border_left_none cg_view_option_title_full_width '>
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
     <div class='cg_view_option cg_view_option_67_percent  cg_border_bottom_none cg_view_option_flex_flow_column'>
        <div class='cg_view_option_title cg_view_option_title_full_width '>
            <p>Select<br>
                <span class="cg_view_option_title_note"><b>NOTE:</b> Each row one value</span></p>
        </div>
        <div class="cg_view_option_input_full_width" >
            <textarea name='upload[$id][content]' maxlength='10000' style='width:100%;' placeholder='Each row one value - Example: &#10;value1&#10;value2&#10;value3&#10;value4&#10;value5&#10;value6'  rows='6'>$valueFieldPlaceholder</textarea>
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

