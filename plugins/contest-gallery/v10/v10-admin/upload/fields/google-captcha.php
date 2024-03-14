<?php

if(!$isOnlyPlaceHolder){
    $ReCaKey = $value->ReCaKey;
    $ReCaLang = $value->ReCaLang;
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

$valueFieldTitle = 'I am not a robot';

// Anfang des Formularteils
echo "<div id='$caRoReCount'  class='formField captchaRoReField'><input type='hidden' name='upload[$id][type]' value='caRoRe'>";
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

    foreach($fieldContent as $key => $valueFieldContent){

        $valueFieldContent = contest_gal1ery_convert_for_html_output($valueFieldContent);// because of possible textarea values do not use ..._without_nl2br

        if($key=='titel'){

            $valueFieldTitle = $valueFieldContent;

            $pleaseSelectLanguage = '';

            $pleaseSelectLanguage .= '<select id="cgReCaLang" name="upload['.$id.'][ReCaLang]">';

            $pleaseSelectLanguage .= "<option value='' >Please select language</option>";

            foreach($langOptions as $langKey => $lang){

                if($ReCaLang==$langKey){
                    $pleaseSelectLanguage .= "<option value='$langKey' selected>$lang</option>";
                }else{
                    $pleaseSelectLanguage .= "<option value='$langKey' >$lang</option>";
                }
            }

            $pleaseSelectLanguage .= '</select>';

            $enterKey = '';
            $enterKey .= "<div style='display:flex;align-items:center;flex-wrap: wrap;'><input type='text' name='upload[$id][ReCaKey]' class='cg_reca_key' placeholder='Example Key: 6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI' size='30' maxlength='1000' value='$ReCaKey'/>";// Titel und Delete M�glichkeit die oben bestimmt wurde
            $enterKey .=  "<span  class='cg_recaptcha_icon' style='width: 100%;margin-left: 0;margin-top: 15px;margin-bottom: 10px;'>Insert Google reCAPTCHA test key</span>";
            $enterKey .=  "</div>";

            $captchaNote = "<span class='cg_recaptcha_test_note' ><span>NOTE:</span><br><b>Google reCAPTCHA test key</b> is provided from Google for testing purpose.
                                    <br><b>Create your own \"Site key\"</b> here <a href='https://www.google.com/recaptcha/admin' target='_blank'>www.google.com/recaptcha/admin</a><br>Register your site, create a <b>V2 \"I am not a robot\"</b>  key.</span>";

        }

        if($key=='content'){
            $valueFieldPlaceholder = $valueFieldContent;
        }

        if($key=='mandatory'){
            $requiredChecked = ($valueFieldContent=='on') ? "checked" : "";


        }

    }
    $caRoReCount++;
    $caRoReHiddenCount++;

}else{

    $ReCaKey = '';
    $ReCaLang = '';

    $pleaseSelectLanguage = '';

    $pleaseSelectLanguage .= '<select id="cgReCaLang" name="upload['.$id.'][ReCaLang]">';

    $pleaseSelectLanguage .= "<option value='' >Please select language</option>";

    foreach($langOptions as $langKey => $lang){

        if($ReCaLang==$langKey){
            $pleaseSelectLanguage .= "<option value='$langKey' selected>$lang</option>";
        }else{
            $pleaseSelectLanguage .= "<option value='$langKey' >$lang</option>";
        }
    }

    $pleaseSelectLanguage .= '</select>';

    $enterKey = '';
    $enterKey .= "<div style='display:flex;align-items:center;flex-wrap: wrap;'><input type='text' name='upload[$id][ReCaKey]' class='cg_reca_key' placeholder='Example Key: 6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI' size='30' maxlength='1000' value='$ReCaKey'/>";// Titel und Delete M�glichkeit die oben bestimmt wurde
    $enterKey .=  "<span  class='cg_recaptcha_icon' style='width: 100%;margin-left: 0;margin-top: 15px;margin-bottom: 10px;'>Insert Google reCAPTCHA test key</span>";
    $enterKey .=  "</div>";

    $captchaNote = "<span class='cg_recaptcha_test_note' ><span>NOTE:</span><br><b>Google reCAPTCHA test key</b> is provided from Google for testing purpose.
                                    <br><b>Create your own \"Site key\"</b> here <a href='https://www.google.com/recaptcha/admin' target='_blank'>www.google.com/recaptcha/admin</a><br>Register your site, create a <b>V2 \"I am not a robot\"</b>  key.</span>";

}


echo <<<HEREDOC
<div class="cg_view_options_row cg_view_options_row_title cg_view_options_row_collapse" title="Collapse">
        <div class="cg_view_option cg_view_option_not_disable cg_border_bottom_none cg_view_option_full_width cg_view_option_flex_flow_column">
            <div class="cg_view_option_title cg_view_option_title_header">
                <p>Google reCAPTCHA - I am not a robot</p>
            </div>
            <div class="cg_view_option_title" style="justify-content: center;padding-bottom: 0;">
                  <span class="cg_view_option_title_note"><b>NOTE:</b> (can be rendered only 1 time on a page)</span>
            </div>
        </div>
</div>
HEREDOC;

echo <<<HEREDOC
<div class='cg_view_options_row'>
     <div class='cg_view_option cg_view_option_33_percent  cg_border_right_none cg_border_bottom_none cg_view_option_flex_flow_column'>
        <div class='cg_view_option_title cg_view_option_title_full_width '>
            <p>Select Captacha language</p>
        </div>
        <div class="cg_view_option_select cg_view_option_input_full_width" >
            $pleaseSelectLanguage
        </div>
    </div>
     <div class='cg_view_option cg_view_option_67_percent  cg_border_bottom_none cg_view_option_flex_flow_column'>
        <div class='cg_view_option_title cg_view_option_title_full_width '>
            <p>Your site key</p>
        </div>
        <div class="cg_view_option_input cg_view_option_input_full_width" >
            $enterKey
        </div>
         <div class='cg_view_option_title cg_view_option_title_full_width' style="justify-content: center; margin-bottom: 5px;">
            $captchaNote
        </div>
    </div>
</div>
HEREDOC;

echo <<<HEREDOC
<div class='cg_view_options_row'>
    <div  class='cg_view_option cg_pointer_events_none cg_view_option_50_percent cg_border_right_none'>
        <div class='cg_view_option_title cg_view_option_title_full_width '>
            <p>Required<br>
                  <span class="cg_view_option_title_note"><b>NOTE:</b> Google reCAPTCHA is always required</span>
            </p>
        </div>
    </div>
     <div class='cg_view_option cg_view_option_hide_upload_field cg_view_option_50_percent '>
        <div class='cg_view_option_title cg_view_option_title_full_width'>
            <p>Hide<br><span class="cg_view_option_title_note"><b>NOTE:</b> Will not be visible in contact form</span></p>
        </div>
        <div class="cg_view_option_checkbox" >
              <input type="checkbox" name="upload[$id][hide]" $hideChecked>
        </div>
    </div>
</div>
HEREDOC;

echo "</div>";
echo "</div>";


            