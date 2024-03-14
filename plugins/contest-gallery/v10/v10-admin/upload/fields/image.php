<?php

// Feldtyp
// 1 = Feldtitel

//ermitteln der Feldnummer
$fieldOrder = $value->Field_Order;
$fieldOrderKey = "$fieldOrder";
$id = $value->id; // Unique ID des Form Feldes

// because of older versions before 20.0, Active = 2 for hidden, before always visible
if($value->Active==2){
    $hideChecked = "checked='checked'";
}
else{
    $hideChecked = "";
}

$idKey = "$id";

// Anfang des Formularteils
echo "<div id='$bhCount' class='formField imageUploadField' ><input type='hidden' name='upload[$id][type]' value='bh'>";

echo "<input type='hidden' class='fieldOrder' name='upload[$id][order]' value='$fieldOrder'>";

echo "<div class='formFieldInnerDiv' >";

// Formularfelder unserializen
$fieldContent = unserialize($value->Field_Content);

// Aktuelle Feld ID mitschicken
echo "<input type='hidden' name='actualID[]' value='$id' >";

$cgAlternativeFileTypeHideContainer = 'cg_hide';// 05.05.2023
$cgAlternativeFilePreviewRequiredChecked = '';
$cgAlternativeFilePreviewHideChecked = '';
$cgAlternativeFileTitle = 'Preview image';
$cgAlternativeFileType = '';
$cgFileTypeIMG = '';
$cgAlternativeFileTypePDF = '';
$cgAlternativeFileTypeZIP = '';
$cgAlternativeFileTypeTXT = '';
$cgAlternativeFileTypeDOC = '';
$cgAlternativeFileTypeDOCX = '';
$cgAlternativeFileTypeXLS = '';
$cgAlternativeFileTypeXLSX = '';
$cgAlternativeFileTypeCSV = '';
$cgAlternativeFileTypeMP3 = '';
$cgAlternativeFileTypeM4A = '';
$cgAlternativeFileTypeOGG = '';
$cgAlternativeFileTypeWAV = '';
$cgAlternativeFileTypeMP4 = '';
$cgAlternativeFileTypeMOV = '';
$cgAlternativeFileTypeWEBM = '';
$cgAlternativeFileTypePPT = '';
$cgAlternativeFileTypePPTX = '';
$cgAlternativeFileTitleDisabled = '';
/*var_dump('343434');die;
var_dump($fieldContent['file-type-img']);die;*/
// then first time usage, after update!
if(!isset($fieldContent['file-type-img'])){// <<< normal version
    $cgFileTypeIMG = 'img';
}
if(!isset($fieldContent['alternative-file-type-pdf']) && !$cgProFalse && intval($dbGalleryVersion)>=17){ // <<< pro version
    $cgAlternativeFileTypePDF = 'pdf';
}
if(!isset($fieldContent['alternative-file-type-zip']) && !$cgProFalse && intval($dbGalleryVersion)>=17){
    $cgAlternativeFileTypeZIP = 'zip';
}
if(!isset($fieldContent['alternative-file-type-txt']) && intval($dbGalleryVersion)>=17){
    $cgAlternativeFileTypeTXT = 'txt';
}
if(!isset($fieldContent['alternative-file-type-doc']) && intval($dbGalleryVersion)>=17){
    $cgAlternativeFileTypeDOC = 'doc';
}
if(!isset($fieldContent['alternative-file-type-docx']) && !$cgProFalse && intval($dbGalleryVersion)>=17){
    $cgAlternativeFileTypeDOCX = 'docx';
}
if(!isset($fieldContent['alternative-file-type-xls']) && intval($dbGalleryVersion)>=17){
    $cgAlternativeFileTypeXLS = 'xls';
}
if(!isset($fieldContent['alternative-file-type-xlsx']) && !$cgProFalse && intval($dbGalleryVersion)>=17){
    $cgAlternativeFileTypeXLSX = 'xlsx';
}
if(!isset($fieldContent['alternative-file-type-csv']) && intval($dbGalleryVersion)>=17){
    $cgAlternativeFileTypeCSV = 'csv';
}
if(!isset($fieldContent['alternative-file-type-mp3']) && !$cgProFalse && intval($dbGalleryVersion)>=17){
    $cgAlternativeFileTypeMP3 = 'mp3';
}
if(!isset($fieldContent['alternative-file-type-m4a']) && !$cgProFalse && intval($dbGalleryVersion)>=17){
    $cgAlternativeFileTypeM4A = 'm4a';
}
if(!isset($fieldContent['alternative-file-type-ogg']) && !$cgProFalse && intval($dbGalleryVersion)>=17){
    $cgAlternativeFileTypeOGG = 'ogg';
}
if(!isset($fieldContent['alternative-file-type-wav']) && !$cgProFalse && intval($dbGalleryVersion)>=17){
    $cgAlternativeFileTypeWAV = 'wav';
}
if(!isset($fieldContent['alternative-file-type-mp4']) && !$cgProFalse && intval($dbGalleryVersion)>=17){
    $cgAlternativeFileTypeMP4 = 'mp4';
}
/*                    if(!isset($fieldContent['alternative-file-type-avi']) && !$cgProFalse && intval($dbGalleryVersion)>=17){
                        $cgAlternativeFileTypeWAV = 'avi';
                    }*/
if(!isset($fieldContent['alternative-file-type-webm']) && !$cgProFalse && intval($dbGalleryVersion)>=17){
    $cgAlternativeFileTypeWEBM = 'webm';
}
if(!isset($fieldContent['alternative-file-type-mov']) && !$cgProFalse && intval($dbGalleryVersion)>=17){
    $cgAlternativeFileTypeMOV = 'mov';
}

// since version 17.0.3 no auto set for PRO version of new file types
/*                    if(!isset($fieldContent['alternative-file-type-ppt']) && !$cgProFalse && intval($dbGalleryVersion)>=17){
                        $cgAlternativeFileTypePPT = 'ppt';
                    }
                    if(!isset($fieldContent['alternative-file-type-pptx']) && !$cgProFalse && intval($dbGalleryVersion)>=17){
                        $cgAlternativeFileTypePPTX = 'pptx';
                    }*/
/*                    if(!isset($fieldContent['alternative-file-type-wmv']) && !$cgProFalse && intval($dbGalleryVersion)>=17){
                        $cgAlternativeFileTypeWAV = 'wmv';
                    }*/

foreach($fieldContent as $key => $valueFieldContent){
    if($key=='file-type-img' && !empty($valueFieldContent)){
        $cgFileTypeIMG = html_entity_decode(stripslashes($valueFieldContent));
    }
    if($key=='alternative-file-type-pdf' && !empty($valueFieldContent)){
        $cgAlternativeFileTypePDF = html_entity_decode(stripslashes($valueFieldContent));
    }
    if($key=='alternative-file-type-zip' && !empty($valueFieldContent)){
        $cgAlternativeFileTypeZIP = html_entity_decode(stripslashes($valueFieldContent));
    }
    if($key=='alternative-file-type-txt' && !empty($valueFieldContent)){
        $cgAlternativeFileTypeTXT = html_entity_decode(stripslashes($valueFieldContent));
    }
    if($key=='alternative-file-type-doc' && !empty($valueFieldContent)){
        $cgAlternativeFileTypeDOC = html_entity_decode(stripslashes($valueFieldContent));
    }
    if($key=='alternative-file-type-docx' && !empty($valueFieldContent)){
        $cgAlternativeFileTypeDOCX = html_entity_decode(stripslashes($valueFieldContent));
    }
    if($key=='alternative-file-type-xls' && !empty($valueFieldContent)){
        $cgAlternativeFileTypeXLS = html_entity_decode(stripslashes($valueFieldContent));
    }
    if($key=='alternative-file-type-xlsx' && !empty($valueFieldContent)){
        $cgAlternativeFileTypeXLSX = html_entity_decode(stripslashes($valueFieldContent));
    }
    if($key=='alternative-file-type-csv' && !empty($valueFieldContent)){
        $cgAlternativeFileTypeCSV = html_entity_decode(stripslashes($valueFieldContent));
    }
    if($key=='alternative-file-type-mp3' && !empty($valueFieldContent)){
        $cgAlternativeFileTypeMP3 = html_entity_decode(stripslashes($valueFieldContent));
    }
    if($key=='alternative-file-type-m4a' && !empty($valueFieldContent)){
        $cgAlternativeFileTypeM4A = html_entity_decode(stripslashes($valueFieldContent));
    }
    if($key=='alternative-file-type-ogg' && !empty($valueFieldContent)){
        $cgAlternativeFileTypeOGG = html_entity_decode(stripslashes($valueFieldContent));
    }
    if($key=='alternative-file-type-wav' && !empty($valueFieldContent)){
        $cgAlternativeFileTypeWAV = html_entity_decode(stripslashes($valueFieldContent));
    }
    if($key=='alternative-file-type-mp4' && !empty($valueFieldContent)){
        $cgAlternativeFileTypeMP4 = html_entity_decode(stripslashes($valueFieldContent));
    }
    /*                        if($key=='alternative-file-type-avi' && !empty($valueFieldContent)){
                                $cgAlternativeFileTypeWAV = html_entity_decode(stripslashes($valueFieldContent));
                            }*/
    /*                        if($key=='alternative-file-type-wmv' && !empty($valueFieldContent)){
                                $cgAlternativeFileTypeWAV = html_entity_decode(stripslashes($valueFieldContent));
                            }*/
    if($key=='alternative-file-type-mov' && !empty($valueFieldContent)){
        $cgAlternativeFileTypeMOV = html_entity_decode(stripslashes($valueFieldContent));
    }
    if($key=='alternative-file-type-webm' && !empty($valueFieldContent)){
        $cgAlternativeFileTypeWEBM = html_entity_decode(stripslashes($valueFieldContent));
    }
    if($key=='alternative-file-type-ppt' && !empty($valueFieldContent)){
        $cgAlternativeFileTypePPT = html_entity_decode(stripslashes($valueFieldContent));
    }
    if($key=='alternative-file-type-pptx' && !empty($valueFieldContent)){
        $cgAlternativeFileTypePPTX = html_entity_decode(stripslashes($valueFieldContent));
    }
    if($key=='alternative-file-preview-required' && !empty($valueFieldContent)){
        $cgAlternativeFilePreviewRequiredChecked = 'checked';
    }
    if($key=='alternative-file-preview-hide' && !empty($valueFieldContent)){
        $cgAlternativeFilePreviewHideChecked = 'checked';
        $cgAlternativeFileTitleDisabled = 'cg_disabled_background_color_e0e0e0';
    }
    if($key=='alternative-file-title' && !empty($valueFieldContent)){
        $cgAlternativeFileTitle = html_entity_decode(stripslashes($valueFieldContent));
    }
}

$requiredChecked = "";

// because of older versions before 20.0, before always required
if(!isset($fieldContent['mandatory'])){
    $requiredChecked = "checked";
}

foreach($fieldContent as $key => $valueFieldContent){
    if($key=='mandatory'){
        $requiredChecked = ($valueFieldContent=='on') ? "checked" : "";
    }
}


foreach($fieldContent as $key => $valueFieldContent){

    $valueFieldContent = contest_gal1ery_convert_for_html_output($valueFieldContent);// because of possible textarea values do not use ..._without_nl2br

    // 1 = Feldtitel
    if($key=='titel'){

        $availableFileTypes = [
            'img' => 'Image',
            'pdf' => 'PDF',
            'zip' => 'ZIP',
            'txt' => 'TXT',
            'doc' => 'DOC',
            'docx' => 'DOCX',
            'xls' => 'XLS',
            'xlsx' => 'XLSX',
            'csv' => 'CSV',
            'mp3' => 'MP3',
            'm4a' => 'M4A',
            'ogg' => 'OGG',
            'wav' => 'WAV',
            'mp4' => 'MP4',
            //'avi' => 'AVI',
            'mov' => 'MOV',
            'webm' => 'WEBM',
            'ppt' => 'PPT',
            'pptx' => 'PPTX',
            // 'wmv' => 'WMV',
        ];

        echo <<<HEREDOC
<div class="cg_view_options_row cg_view_options_row_title cg_view_options_row_collapse" title="Collapse">
        <div class="cg_view_options_row_marker cg_hide"><div class="cg_view_options_row_marker_title">Field title</div><div class="cg_view_options_row_marker_content"></div></div>
            <div class="cg_view_option cg_view_option_not_disable cg_border_bottom_none cg_view_option_100_percent">
                <div class="cg_view_option_title cg_view_option_title_header">
                    <p>File upload field<br><span class="cg_view_option_title_note"><span class="cg_font_weight_500">NOTE:</span> file upload field always at the top the form</span></p>
                </div>
            </div>
</div>
HEREDOC;

        $cg_get_version = cg_get_version();

        echo <<<HEREDOC
<div class='cg_view_options_row'>
    <div  class='cg_view_option cg_border_right_none cg_view_option_flex_flow_column'>
        <div class='cg_view_option_title cg_view_option_title_full_width'>
        <p>Allowed file types</p>
        </div>
        <div class='cg_view_option_select' id="cgSelectFileTypeRealContainer">
            
        </div>
        <div class="cg_view_option_title cg_view_option_input_full_width" style="flex-flow: column;">
                <div  style='margin-top: 6px;'>Hold <b>SHIFT</b> to select multiple types<br>Hold <b>STRG/CMD</b> to add remove single type</div>
HEREDOC;
        if($cgProFalse){
            echo "<div style='margin-top: 5px;'>
                                         <span style='font-weight: bold;'>
                                             Only JPG allowed for frontend<br>\"Image upload\" in normal version.<br><a style='font-size: 16px !important;text-decoration: underline !important;' href=\"https://www.contest-gallery.com/pro-version/\" class=\"cg-get-pro-link\" target=\"_blank\">Get PRO</a>
                                                    </span>
</div>";
        }
        echo <<<HEREDOC
        </div>
    </div>    
     <div class='cg_view_option cg_view_option_flex_flow_column cg_border_right_none'>
        <div class='cg_view_option_title cg_view_option_title_full_width'>
        <p>Field title</p>
        </div>
        <div class='cg_view_option_input cg_view_option_input_full_width'>
        <input  class="cg_view_option_input_field_title" type="text" name="upload[$id][title]" value="$valueFieldContent" size='30'>
        </div>
    </div>
    <div  class='cg_view_option cg_view_option_flex_flow_column'>
        <div  class='cg_view_option_title cg_view_option_input_full_width cg_view_option_title_flex_flow_column' >
        <p>
            Max file size and<br>allowed image  types
        </p>
        <div class='cg-config' style='margin-top:-5px;'><a href='?page=$cg_get_version/index.php&edit_options=true&option_id=$GalleryID&cg_go_to=cgActivatePostMaxMBfileContainerRow' target='_blank'>Config</a></div>
        </div>
    </div>
</div>
HEREDOC;

        echo <<<HEREDOC
<div class='cg_view_options_row'>
    <div  class='cg_view_option cg_border_top_none cg_view_option_50_percent cg_border_right_none '>
        <div class='cg_view_option_title cg_view_option_title_full_width '>
            <p>Required<br><span class="cg_view_option_title_note"><b>NOTE:</b> if required then form appears collapsed,<br>only upload form button is visible first,<br>after adding/dropping file,<br>form will be uncollapsed </span></p>
        </div>
        <div class="cg_view_option_checkbox">
              <input type="checkbox" name="upload[$id][required]" $requiredChecked>
        </div>
    </div>
    <div class='cg_view_option cg_border_top_none cg_view_option_hide_upload_field cg_view_option_50_percent '>
        <div class='cg_view_option_title cg_view_option_title_full_width'>
            <p>Hide<br><span class="cg_view_option_title_note"><b>NOTE:</b> Will not be visible in contact form</span></p>
        </div>
        <div class="cg_view_option_checkbox">
              <input type="checkbox" name="upload[$id][hide]" $hideChecked>
        </div>
    </div>
</div>
HEREDOC;

        echo "<select id='cgSelectFileType' multiple size='7'>";
        foreach($availableFileTypes as $fileTypeKey => $fileTypeValue){
            $fileTypeSelected = '';
            // $cgFileTypeIMG would be img as saved value
            if(
                $fileTypeKey==$cgFileTypeIMG ||
                $fileTypeKey==$cgAlternativeFileTypePDF ||
                $fileTypeKey==$cgAlternativeFileTypeZIP ||
                $fileTypeKey==$cgAlternativeFileTypeTXT ||
                $fileTypeKey==$cgAlternativeFileTypeDOC ||
                $fileTypeKey==$cgAlternativeFileTypeDOCX ||
                $fileTypeKey==$cgAlternativeFileTypeXLS ||
                $fileTypeKey==$cgAlternativeFileTypeXLSX ||
                $fileTypeKey==$cgAlternativeFileTypeCSV ||
                $fileTypeKey==$cgAlternativeFileTypeMP3 ||
                $fileTypeKey==$cgAlternativeFileTypeM4A ||
                $fileTypeKey==$cgAlternativeFileTypeOGG ||
                $fileTypeKey==$cgAlternativeFileTypeWAV ||
                $fileTypeKey==$cgAlternativeFileTypeMP4 ||
                $fileTypeKey==$cgAlternativeFileTypeMOV ||
                $fileTypeKey==$cgAlternativeFileTypeWEBM ||
                $fileTypeKey==$cgAlternativeFileTypePPT ||
                $fileTypeKey==$cgAlternativeFileTypePPTX
            ){
                $fileTypeSelected = 'selected';
            }

            if($fileTypeKey == 'img'){
                echo "<option value='$fileTypeKey' $fileTypeSelected>$fileTypeValue upload</option>";
            }else{
                if($fileTypeKey == 'txt' OR $fileTypeKey == 'doc' OR $fileTypeKey == 'xls' OR $fileTypeKey == 'csv' OR $fileTypeKey == 'ppt'){
                    echo "<option value='$fileTypeKey' $fileTypeSelected>$fileTypeValue upload</option>";
                }else{
                    echo "<option class='$cgProFalse' value='$fileTypeKey' $fileTypeSelected>$fileTypeValue upload $cgProFalseText</option>";
                }
            }

        }
        echo "</select>";

        echo "<input id='cgFileTypeIMG' type='hidden' name='upload[$id][file-type-img]' value='$cgFileTypeIMG'>";
        echo "<input id='cgAlternativeFileTypePDF' type='hidden' name='upload[$id][alternative-file-type-pdf]' value='$cgAlternativeFileTypePDF'>";
        echo "<input id='cgAlternativeFileTypeZIP' type='hidden' name='upload[$id][alternative-file-type-zip]' value='$cgAlternativeFileTypeZIP'>";
        echo "<input id='cgAlternativeFileTypeTXT' type='hidden' name='upload[$id][alternative-file-type-txt]' value='$cgAlternativeFileTypeTXT'>";
        echo "<input id='cgAlternativeFileTypeDOC' type='hidden' name='upload[$id][alternative-file-type-doc]' value='$cgAlternativeFileTypeDOC'>";
        echo "<input id='cgAlternativeFileTypeDOCX' type='hidden' name='upload[$id][alternative-file-type-docx]' value='$cgAlternativeFileTypeDOCX'>";
        echo "<input id='cgAlternativeFileTypeXLS' type='hidden' name='upload[$id][alternative-file-type-xls]' value='$cgAlternativeFileTypeXLS'>";
        echo "<input id='cgAlternativeFileTypeXLSX' type='hidden' name='upload[$id][alternative-file-type-xlsx]' value='$cgAlternativeFileTypeXLSX'>";
        echo "<input id='cgAlternativeFileTypeCSV' type='hidden' name='upload[$id][alternative-file-type-csv]' value='$cgAlternativeFileTypeCSV'>";
        echo "<input id='cgAlternativeFileTypeMP3' type='hidden' name='upload[$id][alternative-file-type-mp3]' value='$cgAlternativeFileTypeMP3'>";
        echo "<input id='cgAlternativeFileTypeM4A' type='hidden' name='upload[$id][alternative-file-type-m4a]' value='$cgAlternativeFileTypeM4A'>";
        echo "<input id='cgAlternativeFileTypeOGG' type='hidden' name='upload[$id][alternative-file-type-ogg]' value='$cgAlternativeFileTypeOGG'>";
        echo "<input id='cgAlternativeFileTypeWAV' type='hidden' name='upload[$id][alternative-file-type-wav]' value='$cgAlternativeFileTypeWAV'>";
        echo "<input id='cgAlternativeFileTypeMP4' type='hidden' name='upload[$id][alternative-file-type-mp4]' value='$cgAlternativeFileTypeMP4'>";
        //echo "<input id='cgAlternativeFileTypeAVI' type='hidden' name='upload[$id][alternative-file-type-avi]' value='$cgAlternativeFileTypeAVI'>";
        echo "<input id='cgAlternativeFileTypeMOV' type='hidden' name='upload[$id][alternative-file-type-mov]' value='$cgAlternativeFileTypeMOV'>";
        echo "<input id='cgAlternativeFileTypeWEBM' type='hidden' name='upload[$id][alternative-file-type-webm]' value='$cgAlternativeFileTypeWEBM'>";
        echo "<input id='cgAlternativeFileTypePPT' type='hidden' name='upload[$id][alternative-file-type-ppt]' value='$cgAlternativeFileTypePPT'>";
        echo "<input id='cgAlternativeFileTypePPTX' type='hidden' name='upload[$id][alternative-file-type-pptx]' value='$cgAlternativeFileTypePPTX'>";

    }

}

echo "</div>";
echo "</div>";

