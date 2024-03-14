<?php
require_once('get-data-create-registry.php');
require_once(dirname(__FILE__) . "/../../../nav-menu.php");

$iconsURL = plugins_url().'/'.cg_get_version().'/v10/v10-css';

$cgRecaptchaIconUrl = $iconsURL.'/backend/re-captcha.png';
$cgDragIcon = $iconsURL.'/backend/cg-drag-icon.png';


echo "<input type='hidden' id='cgDragIcon' value='$cgDragIcon'/>";
echo "<input type='hidden' id='cgRecaptchaIconUrl' value='$cgRecaptchaIconUrl'/>";
echo "<input type='hidden' id='cgRecaptchaKey' value='6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI'/>";

if(!function_exists('cg_cg_set_default_editor')){

    function cg_cg_set_default_editor() {
        $r = 'html';
        return $r;
    }

}

$cgBeforeSinceV14ExplanationRequired = false;

if(cg_check_if_new_registry_logic_explanation_note_required($galleryDbVersion)){
    $cgBeforeSinceV14ExplanationRequired = true;
}

$cgRegistrationGeneralForm = '';

if($cgBeforeSinceV14ExplanationRequired){
    $cgRegistrationGeneralForm = ' (general) ';
    echo "<div id='cgNewRegistryLogicNote'><span class='cg_color_red'>NOTE:</span> Since plugin version 14 the \"Registration form\" is general<br>and valid for all new created or copied galleries since plugin version 14.</div>";
}else{
    if(intval($galleryDbVersion)>=14){// only if higher then 14 then this explanation required!
        $cgRegistrationGeneralForm = ' (general) ';
        echo "<div id='cgNewRegistryLogicNote'><span class='cg_color_red'>NOTE:</span> \"Registration form\" is general and valid for all galleries.</div>";
    }
}

add_filter( 'wp_default_editor', 'cg_cg_set_default_editor' );

// recaptcha-lang-options.php
$langOptions = include(__DIR__.'/../../../data/recaptcha-lang-options.php');

echo '<select name="ReCaLang" id="cgReCaLangToCopy" class="cg_hide">';

echo "<option value='' >Please select language</option>";

foreach($langOptions as $langKey => $lang){

    echo "<option value='$langKey' >$lang</option>";

}

echo '</select>';

echo '<div id="cgRegFormSelect">';

$optGroupWpFields = '';

if(intval($galleryDbVersion)>=14){
    $optGroupWpFields = '<optgroup label="WP fields">
			<option value="wpfn">WP First Name</option>
			<option value="wpln">WP Last Name</option>
		</optgroup>';
}



if(intval($galleryDbVersion)>=14){
    $optGroupWpFields = '<optgroup label="WP fields">
			<option value="wpfn">WP First Name</option>
			<option value="wpln">WP Last Name</option>
		</optgroup>';
}


$beforeSinceV14Explanation = '';
$beforeSinceV14Disabled = '';

if(intval($galleryDbVersion)<14 AND empty($cgProFalse)){
    $beforeSinceV14Disabled = 'disabled';
    $beforeSinceV14Explanation = '- available only for galleries created or copied in plugin version 14 or higher';
}




$heredoc = <<<HEREDOC
	<select name="dauswahl" id="dauswahl" >
		$optGroupWpFields
		<optgroup label="User fields">
			<option value="nf">Input</option>
			<option value="kf">Textarea</option>
			<option value="se">Select</option>
			<option value="cb" class="$cgProFalse">Check agreement $cgProFalseText</option>
			<option value="pi" class="$cgProFalse" $beforeSinceV14Disabled>Profile image $cgProFalseText$beforeSinceV14Explanation</option>
		</optgroup>
		<optgroup label="Admin fields">
			<option class="$cgProFalse" value="ht">HTML $cgProFalseText</option>
			<option  value="caRo">Simple Captcha - I am not a robot</option>
			<option  value="caRoRe">Google reCAPTCHA - I am not a robot</option>
		 </optgroup>
	</select>
	<input id="cg_create_upload_add_field" class="cg_registry_dauswahl" type="button" name="plus" value="Add field" >
	<select id="cgPlace" style="margin-left:5px;margin-right: 5px;">
        <option  value="place-top">Place top</option>
        <option  value="place-bottom">Place bottom</option>
    </select>
    <span id="cgCollapse" class="cg_uncollapsed" >Collapse all</span>
    	<span class="cg_save_form_button_parent" >
	Registration$cgRegistrationGeneralForm form 
            <span id="cgSaveRegistryFormNavButton" class="cg_save_form_button cg_backend_button_gallery_action cg_hide" >Save form</span>
	    </span>
	</div>
HEREDOC;

echo $heredoc;

if (!empty($_POST['submit'])) {

//    echo "<p id='cg_changes_saved' style='font-size:18px;'><strong>Changes saved</strong></p>";

}

echo '<div id="cg_main_options" class="cg_main_options" style="box-shadow: unset;border-radius: unset;">';

echo "<form name='create_user_form' enctype='multipart/form-data'  data-cg-submit-message='Changes Saved'  class='cg_load_backend_submit' action='?page=".cg_get_version()."/index.php&option_id=$GalleryID&create_user_form=true' id='cg_create_user_form' method='post'>";
wp_nonce_field( 'cg_admin');
echo "<input type='hidden' name='option_id' value='$GalleryID'>";

// IDs of the div boxes   
$nfCount = 10;
$kfCount = 20;
$efCount = 30;
$bhCount = 40;
$htCount = 50;
$cbCount = 60;
$seCount = 70;
$caRoCount = 80;
$caRoReCount = 90;
$wpfnCount = 100;
$wplnCount = 110;
$piCount = 120;

// Further IDs of the div boxes
$nfHiddenCount = 100;
$kfHiddenCount = 200;
$efHiddenCount = 300;
$bhHiddenCount = 400;
$htHiddenCount = 500;
$cbHiddenCount = 600;
$seHiddenCount = 700;
$caRoHiddenCount = 800;
$caRoReHiddenCount = 900;
$wpfnHiddenCount = 1000;
$wplnHiddenCount = 1100;
$piHiddenCount = 1200;

// FELDBENENNUNGEN

// 1 = Feldtyp
// 2 = Feldnummer
// 3 = Feldtitel
// 4 = Feldinhalt
// 5 = Feldkrieterium1
// 6 = Feldkrieterium2
// 7 = Felderfordernis

//print_r($selectFormInput);

// Zum z�hlen von Feld Reihenfolge
$i = 1;

$isOnlyPlaceHolder = false;

if(true){

    echo "<div id='cgFieldsToCloneAndAppend' class='cg_hide'>";

        $isOnlyPlaceHolder = true;

        // just as placeholder for all kind of inputs simply
        $value = new stdClass();
        $value->id = 'new-0';
        $value->Field_Order = 0;
        $value->Active = 1;
        $value->Min_Char = 3;
        $value->Max_Char = 100;
        $value->Field_Name = 0;
        $value->Field_Content = '';
        $value->ReCaLang = '';
        $value->ReCaKey = '';
        $value->Field_Type = '';
        $value->Required = 0;
        $value->GalleryID = $GalleryID;
        $value->Use_as_URL = 0;
        $value->ReCaLang = 'en';
        $value->WatermarkPosition = '';
        $id = $value->id; // Unique ID des Form Feldes

        $Field_Name = '';
        $Field_Content = '';
        $fieldOrder = $value->Field_Order;
        $Min_Char = $value->Min_Char;
        $Max_Char = $value->Max_Char;
        $Field_Order = $value->Field_Order;
        $Field_Type = $value->Field_Type;

        $id = $value->id; // Unique ID des Form Feldes
        $idKey = "$id";
        $cg_Necessary = $value->Required;
        if($cg_Necessary==1){$cg_Necessary_checked="checked";}
        else{$cg_Necessary_checked="";}
        if($value->Active==1){
            $hideChecked = "";
        }
        else{
            $hideChecked = "checked='checked'";
        }

        $enterKey = '';

        $pleaseSelectLanguage = '';
        $pleaseSelectLanguage .= '<select id="cgReCaLang" name="ReCaLang">';
        $pleaseSelectLanguage .= "<option value='' >Please select language</option>";
        foreach($langOptions as $langKey => $lang){
            $pleaseSelectLanguage .= "<option value='$langKey' >$lang</option>";
        }
        $pleaseSelectLanguage .= '</select>';

        $enterKey = '';
        $ReCaKey = '';
        $enterKey .= "<div style='display:flex;align-items:center;flex-wrap: wrap;'><input type='text' name='ReCaKey' class='cg_reca_key' placeholder='Example Key: 6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI' size='30' maxlength='1000' value='$ReCaKey'/>";// Titel und Delete M�glichkeit die oben bestimmt wurde
        $enterKey .=  "<span  class='cg_recaptcha_icon' style='width: 100%;margin-left: 0;margin-top: 15px;margin-bottom: 10px;'>Insert Google reCAPTCHA test key</span>";
        $enterKey .=  "</div>";

        $captchaNote = "<span class='cg_recaptcha_test_note' ><span>NOTE:</span><br><b>Google reCAPTCHA test key</b> is provided from Google for testing purpose.
                                        <br><b>Create your own \"Site key\"</b> here <a href='https://www.google.com/recaptcha/admin' target='_blank'>www.google.com/recaptcha/admin</a><br>Register your site, create a <b>V2 \"I am not a robot\"</b>  key.</span>";

        include (__DIR__.'/fields/reg-check-agreement.php');

        include (__DIR__.'/fields/reg-input.php');

        include (__DIR__.'/fields/reg-profile-image.php');

        include (__DIR__.'/fields/reg-select.php');

        include (__DIR__.'/fields/reg-textarea.php');

        include (__DIR__.'/fields/wp-email.php');

        include (__DIR__.'/fields/wp-first-name.php');

        include (__DIR__.'/fields/wp-last-name.php');

        include (__DIR__.'/fields/wp-password.php');

        include (__DIR__.'/fields/wp-password-confirm.php');

        include (__DIR__.'/fields/wp-username.php');

        include (__DIR__.'/fields/reg-html.php');

        include (__DIR__.'/fields/reg-simple-captcha.php');

        include (__DIR__.'/fields/reg-google-captcha.php');

        $isOnlyPlaceHolder = false;

    echo "</div>";

}

echo '<div id="cg_registry_form_container_parent" >';
echo '<div id="ausgabe1" class="cg_registry_form_container" >';

foreach ($selectFormInput as $key => $value) {

    if($value->Field_Type == 'main-mail'){
        include (__DIR__.'/fields/wp-email.php');
    }

    if($value->Field_Type == 'password'){
        include (__DIR__.'/fields/wp-password.php');
    }

    if($value->Field_Type == 'password-confirm'){

        include (__DIR__.'/fields/wp-password-confirm.php');
}

    if($value->Field_Type == 'main-user-name'){
        include (__DIR__.'/fields/wp-username.php');
    }

    if(intval($galleryDbVersion)>=14){
        if($value->Field_Type == 'main-nick-name'){
            include (__DIR__.'/fields/wp-nickname.php');
        }
    }

    if($value->Field_Type == 'profile-image'){
        include (__DIR__.'/fields/reg-profile-image.php');
    }

    if($value->Field_Type == 'wpfn'){
        include (__DIR__.'/fields/wp-first-name.php');
    }

    if($value->Field_Type == 'wpln'){
        include (__DIR__.'/fields/wp-last-name.php');
    }

    if($value->Field_Type == 'user-text-field'){
        include (__DIR__.'/fields/reg-input.php');
    }

    if($value->Field_Type == 'user-comment-field'){
        include (__DIR__.'/fields/reg-textarea.php');
    }

    if($value->Field_Type == 'user-select-field'){
        include (__DIR__.'/fields/reg-select.php');
    }

    if($value->Field_Type == 'user-check-agreement-field'){
        include (__DIR__.'/fields/reg-check-agreement.php');
    }

    if($value->Field_Type == 'user-robot-field'){
        include (__DIR__.'/fields/reg-simple-captcha.php');
    }

    if($value->Field_Type == 'user-robot-recaptcha-field'){
        include (__DIR__.'/fields/reg-google-captcha.php');
   }

    if($value->Field_Type == 'user-html-field'){
        include (__DIR__.'/fields/reg-html.php');
    }



    // Zum z�hlen von Feld Reihenfolge
    $i++;

}


?>
</div>

</div>

<div id="submitUploadRegFormContainer"  >
    <input type="hidden" name="submit" value="true"/>
<input id="submitForm" class="cg_backend_button_gallery_action" type="submit" value="Save form" style="font-weight:bold;text-align:center;width:180px;float:right;margin-right:10px;margin-bottom:10px;">
</div>
<br/>



<?php


// ---------------- AUSGABE des gespeicherten Formulares  --------------------------- ENDE

echo "<br/>";
?>
</form>
</div>
