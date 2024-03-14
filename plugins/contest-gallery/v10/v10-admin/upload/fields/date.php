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

    $inputRow = $wpdb->get_row("SELECT * FROM $tablename_form_input WHERE id = '$id'");
    $Show_Slider = $inputRow->Show_Slider;

    if($Show_Slider==1){$checkedShow_Slider='checked';}
    else{$checkedShow_Slider='';}

    $SubTitle = $inputRow->SubTitle;

    if($SubTitle==1){$checkedSubTitle='checked';}
    else{$checkedSubTitle='';}

    $ThirdTitle = $inputRow->ThirdTitle;

    if($ThirdTitle==1){$checkedThirdTitle='checked';}
    else{$checkedThirdTitle='';}


}

$valueFieldTitle = 'Date';

// Anfang des Formularteils
echo "<div id='$dtCount'  class='formField dateTimeField'><input type='hidden' name='upload[$id][type]' value='dt'>";
echo "<div class='cg_remove' title='Remove field' data-cg-id='$id'></div>";
echo "<div class='cg_drag_area' ><img class='cg_drag_area_icon' src='$cgDragIcon'></div>";

echo "<input type='hidden' class='fieldOrder' name='upload[$id][order]' value='$fieldOrder'>";
echo "<div class='formFieldInnerDiv'>";

echo "<input type='hidden' value='$fieldOrder' class='fieldnumber'>";

if(!$isOnlyPlaceHolder){
    if($id==$Field1IdGalleryView){$checked='checked';}
    else{$checked='';}

    if($id==$Field2IdGalleryView){$checkedShowTag='checked';}
    else{$checkedShowTag='';}

// Formularfelder unserializen
    $fieldContent = unserialize($value->Field_Content);

    $valueFieldTitle = '';
    $valueFieldPlaceholder = '';
    $minChar = '';
    $maxChar = '';
    $requiredChecked = '';
}

if(!$isOnlyPlaceHolder){

    foreach($fieldContent as $key => $valueFieldContent){

        $valueFieldContent = contest_gal1ery_convert_for_html_output($valueFieldContent);// because of possible textarea values do not use ..._without_nl2br

            if($key=='titel'){
                $valueFieldTitle = $valueFieldContent;
            }

            if($key=='content'){
                $valueFieldPlaceholder = $valueFieldContent;
            }

            if($key=='mandatory'){

                $requiredChecked = ($valueFieldContent=='on') ? "checked" : "";


            }

        if($key=='format'){

            $selected1 = ($valueFieldContent == 'YYYY-MM-DD') ? 'selected' : '';
            $selected2 = ($valueFieldContent == 'DD-MM-YYYY') ? 'selected' : '';
            $selected3 = ($valueFieldContent == 'MM-DD-YYYY') ? 'selected' : '';
            $selected4 = ($valueFieldContent == 'YYYY/MM/DD') ? 'selected' : '';
            $selected5 = ($valueFieldContent == 'DD/MM/YYYY') ? 'selected' : '';
            $selected6 = ($valueFieldContent == 'MM/DD/YYYY') ? 'selected' : '';
            $selected7 = ($valueFieldContent == 'YYYY.MM.DD') ? 'selected' : '';
            $selected8 = ($valueFieldContent == 'DD.MM.YYYY') ? 'selected' : '';
            $selected9 = ($valueFieldContent == 'MM.DD.YYYY') ? 'selected' : '';

            $dateSelect = <<<HEREDOC
    <select name='upload[$id][format]'>
                            <option value='YYYY-MM-DD' $selected1>YYYY-MM-DD</option>
                            <option value='DD-MM-YYYY' $selected2>DD-MM-YYYY</option>
                            <option value='MM-DD-YYYY' $selected3>MM-DD-YYYY</option>
                            <option value='YYYY/MM/DD' $selected4>YYYY/MM/DD</option>
                            <option value='DD/MM/YYYY' $selected5>DD/MM/YYYY</option>
                            <option value='MM/DD/YYYY' $selected6>MM/DD/YYYY</option>
                            <option value='YYYY.MM.DD' $selected7>YYYY.MM.DD</option>
                            <option value='DD.MM.YYYY' $selected8>DD.MM.YYYY</option>
                            <option value='MM.DD.YYYY' $selected9>MM.DD.YYYY</option>
                            </select><br/>
    HEREDOC;

        }

    }

    $dtCount++;
    $dtHiddenCount++;
}

echo <<<HEREDOC
<div class="cg_view_options_row cg_view_options_row_title cg_view_options_row_collapse" title="Collapse">
            <div class="cg_view_options_row_marker cg_hide"><div class="cg_view_options_row_marker_title">Field title</div><div class="cg_view_options_row_marker_content"></div></div>
        <div class="cg_view_option cg_view_option_not_disable cg_border_bottom_none cg_view_option_100_percent">
            <div class="cg_view_option_title cg_view_option_title_header">
                <p>Date</p>
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
    <div  class='cg_view_option cg_entry_page_description cg_view_option_100_percent cg_border_bottom_none '>
        <div class='cg_view_option_title  cg_view_option_title_full_width'>
            <p>Show as info in single entry view</p>
        </div>
        <div class="cg_view_option_checkbox">
              <input type="checkbox" name="upload[$id][infoInSlider]" $checkedShow_Slider>
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
            <p>Select format<br></p>
        </div>
        <div class="cg_view_option_select cg_view_option_input_full_width" >
            $dateSelect
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
