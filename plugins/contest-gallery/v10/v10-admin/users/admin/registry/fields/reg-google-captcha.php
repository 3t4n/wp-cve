<?php

$Field_Name = '';
$ReCaLang = $value->ReCaLang;
$ReCaKey = $value->ReCaKey;

if(!$isOnlyPlaceHolder){
    $fieldOrder = $value->Field_Order;
    $Min_Char = $value->Min_Char;
    $Max_Char = $value->Max_Char;
    $ReCaLang = $value->ReCaLang;
    $ReCaKey = $value->ReCaKey;
    $Field_Name = contest_gal1ery_convert_for_html_output($value->Field_Name);
    $Field_Content = contest_gal1ery_convert_for_html_output($value->Field_Content);
    $Field_Order = $value->Field_Order;
    $Field_Type = $value->Field_Type;
    $cg_Necessary = $value->Required;
    $id = $value->id; // Unique ID des Form Feldes
    $idKey = "$id";
    if($cg_Necessary==1){$cg_Necessary_checked="checked";}
    else{$cg_Necessary_checked="";}
    if($value->Active==0){
        $hideChecked = "checked='checked'";
    }
    else{
        $hideChecked = "";
    }
}
$pleaseSelectLanguage = '';
$pleaseSelectLanguage .= '<select id="cgReCaLang" name="ReCaLang">';
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
$enterKey .= "<div style='display:flex;align-items:center;flex-wrap: wrap;'><input type='text' name='ReCaKey' class='cg_reca_key' placeholder='Example Key: 6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI' size='30' maxlength='1000' value='$ReCaKey'/>";// Titel und Delete M�glichkeit die oben bestimmt wurde
$enterKey .=  "<span  class='cg_recaptcha_icon' style='width: 100%;margin-left: 0;margin-top: 15px;margin-bottom: 10px;'>Insert Google reCAPTCHA</span>";
$enterKey .=  "</div>";

$captchaNote = "<span class='cg_recaptcha_test_note' ><span>NOTE:</span><br><b>Google reCAPTCHA test key</b> is provided from Google for testing purpose.
                                        <br><b>Create your own \"Site key\"</b> here <a href='https://www.google.com/recaptcha/admin' target='_blank'>www.google.com/recaptcha/admin</a><br>Register your site, create a <b>V2 \"I am not a robot\"</b>  key.</span>";

// Anfang des Formularteils
echo "<div id='cg$caRoReCount'  class='formField regCaptchaRoReField'>";
echo "<div class='cg_remove' title='Remove field' data-cg-id='$id'></div>";
echo "<div class='cg_drag_area' ><img class='cg_drag_area_icon' src='$cgDragIcon'></div>";

echo "<input type='hidden' class='Field_Type' name='Field_Type[$i]' value='user-robot-recaptcha-field'>";
echo "<input type='hidden' class='Field_Order' value='$Field_Order' >";
echo "<input type='hidden' class='Field_Id' name='Field_Id[$i]' value='$id' >";

echo "<div class='formFieldInnerDiv'>";

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
            <p>Hide<br><span class="cg_view_option_title_note"><b>NOTE:</b> Will not be visible in registration form</span></p>
        </div>
        <div class="cg_view_option_checkbox" >
                    <input type="checkbox" class="necessary-hide" name="Hide[$i]" $hideChecked>
        </div>
    </div>
</div>
HEREDOC;

echo "</div>";
echo "</div>";


            