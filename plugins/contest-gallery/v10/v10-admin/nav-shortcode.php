<?php


echo "<div class='td_gallery_info_content_row'>";

echo "<div class='td_gallery_info_content' style='width: 71.5%;box-sizing: border-box;display:flex;flex-flow:row;align-items:center;'>";

echo "<div class='td_gallery_info_shortcode td_gallery_info_shortcode_pro' style='border: unset; padding: 0;height: 100%;width: 32%;border-right: thin solid #dedede;border-width:0.5px;' >
$cgProVersionLink
</div>";

echo "<div class='td_gallery_info_shortcode' style='flex-grow:1;border-right: unset;'>
<div class='td_gallery_info_name' style='border-bottom: none;'><div>Gallery name<br><div class='td_gallery_info_name_span'><span class='td_gallery_info_name_span_bold'>$GalleryName</span></div></div><a class='td_gallery_info_name_edit_link cg_load_backend_link' href=\"?page=".cg_get_version()."/index.php&edit_options=true&option_id=".$galeryNR."&cg_go_to=cgEditGalleryNameLink\" ><div class='td_gallery_info_name_edit cg_shortcode_copy cg_shortcode_copy_gallery cg_tooltip'></div></a></div>
</div>";

echo "</div>";

echo "<div class='td_gallery_info_content' style='width: 23.5%;
    border-left: thin solid #dedede;
    border-right: unset;
    box-sizing: border-box;
    border-bottom: thin solid #dedede;
    margin-left: 0.5px;'>";

echo "<div class='td_gallery_info_shortcode $cgProFalse' >
    <div>
      <div class='td_gallery_info_name_title'>Google sign in button</div>
      <div class='td_gallery_info_name_span'>[cg_google_sign_in id=\"".$galeryNR."\"]</div>
        <div>
            <span class=\"cg-info-icon\">read info</span>
            <span class=\"cg-info-container cg-info-container-gallery-user\" style=\"display: none;top:67px;\">
            Displays Google sign in button<br>
            <b>Can only be added once on a page<br>
            <br>Google sign in button options <br>have to be configured<br><br>[cg_google_sign_in id=\"".$galeryNR."\"] shortcode still requires id because of possible translations for a gallery or shortcode interval configuration.</b></span>
        </div>
    </div>
    <div class='td_gallery_info_shortcode_edit cg_shortcode_copy cg_shortcode_copy_gallery cg_tooltip'></div>
    <div class='td_gallery_info_shortcode_conf cg_tooltip' data-cg-shortcode='cg_google_sign_in' data-cg-title-main='Google sign in button' data-cg-title-sub='[cg_google_sign_in id=\"".$galeryNR."\"]'></div>
    <div class='td_gallery_info_shortcode_conf_status td_gallery_info_shortcode_conf_status_on   cg_tooltip cg_hide'   data-cg-shortcode='cg_google_sign_in'  ></div>
    <div class='td_gallery_info_shortcode_conf_status td_gallery_info_shortcode_conf_status_off   cg_tooltip cg_hide'   data-cg-shortcode='cg_google_sign_in'  ></div>
</div>";

echo "</div>";
echo "</div>";

echo "<div class='td_gallery_info_content_row'>";

echo "<div class='td_gallery_info_content'>";

echo "<div class='td_gallery_info_shortcode' style='min-height: 62px; '>
    <div>
     <div class='td_gallery_info_name_title'>&nbsp;</div>
    <div class='td_gallery_info_name_span'>&nbsp;</div>
        <div>
            <span class=\"cg-info-icon\">&nbsp;</span>
    <span class=\"cg-info-container cg-info-container-gallery-user\" style=\"display: none;top:67px;\">&nbsp;</span>
        </div>
    </div>
    <div class='td_gallery_info_shortcode_edit cg_shortcode_copy cg_shortcode_copy_gallery cg_tooltip cg_hide'></div>
</div>";

echo "<div class='td_gallery_info_shortcode'>
    <div>
      <div class='td_gallery_info_name_title'>Main gallery</div>
      <div class='td_gallery_info_name_span'>[cg_gallery id=\"".$galeryNR."\"]</div>
        <div>
            <span class=\"cg-info-icon\">read info</span>
            <span class=\"cg-info-container cg-info-container-gallery-user\" style=\"display: none;top:67px;\">All files are visible<br>All configured options are active<br>Voting is possible
            <br>Can be added multiple times on a page with different id’s</span>
        </div>
    </div>
    <div class='td_gallery_info_shortcode_edit cg_shortcode_copy cg_shortcode_copy_gallery cg_tooltip'></div>
    <div class='td_gallery_info_shortcode_conf   cg_tooltip'  data-cg-shortcode='cg_gallery'  data-cg-title-main='Main gallery' data-cg-title-sub='[cg_gallery id=\"".$galeryNR."\"]'></div>
    <div class='td_gallery_info_shortcode_conf_status td_gallery_info_shortcode_conf_status_on   cg_tooltip cg_hide'   data-cg-shortcode='cg_gallery'  ></div>
    <div class='td_gallery_info_shortcode_conf_status td_gallery_info_shortcode_conf_status_off   cg_tooltip cg_hide'   data-cg-shortcode='cg_gallery'  ></div>
</div>";

echo "</div>";

echo "<div class='td_gallery_info_content'>";

echo "<div class='td_gallery_info_shortcode' >
    <div>
     <div class='td_gallery_info_name_title'>Contact form</div>
    <div class='td_gallery_info_name_span'>[cg_users_contact id=\"".$galeryNR."\"]</div>
        <div>
            <span class=\"cg-info-icon\">read info</span>
    <span class=\"cg-info-container cg-info-container-gallery-user\" style=\"display: none;top:67px;\">Displays contact form</span>
        </div>
    </div>
    <div class='td_gallery_info_shortcode_edit cg_shortcode_copy cg_shortcode_copy_gallery cg_tooltip'></div>
    <div class='td_gallery_info_shortcode_conf   cg_tooltip'  data-cg-shortcode='cg_users_contact'  data-cg-title-main='Contact form' data-cg-title-sub='[cg_users_contact id=\"".$galeryNR."\"]'></div>
    <div class='td_gallery_info_shortcode_conf_status td_gallery_info_shortcode_conf_status_on   cg_tooltip cg_hide ' data-cg-shortcode='cg_users_contact' ></div>
    <div class='td_gallery_info_shortcode_conf_status td_gallery_info_shortcode_conf_status_off   cg_tooltip cg_hide' data-cg-shortcode='cg_users_contact' ></div>
</div>";

echo "<div class='td_gallery_info_shortcode'>
    <div>
     <div class='td_gallery_info_name_title'>Logged in user files only</div>
    <div class='td_gallery_info_name_span'>[cg_gallery_user id=\"".$galeryNR."\"]</div>
        <div>
            <span class=\"cg-info-icon\">read info</span>
            <span class=\"cg-info-container cg-info-container-gallery-user\" style=\"display: none;top:67px;\">Display only uploaded files of logged in user
            <br>Voting is not possible<br>Show always all votes<br>\"Hide until vote\" and \"Show only user votes\" options are disabled<br>\"Delete votes\" is not possible<br>User can delete own files if they are activated
            <br><strong>User can edit entry fields information if<br>\"Show as info in single entry view\" or \"Show as title in gallery view\"<br>for a field is activated.</strong>
            <br>Can be added multiple times on a page with different id’s
            <br><b>\"Delete by frontend user deleted files from storage also\"</b> option<br>can be configured in \"Contact options\"
            </span>
        </div>
    </div>
    <div class='td_gallery_info_shortcode_edit cg_shortcode_copy cg_shortcode_copy_gallery cg_tooltip'></div>
    <div class='td_gallery_info_shortcode_conf   cg_tooltip'  data-cg-shortcode='cg_gallery_user'  data-cg-title-main='Logged in user files only' data-cg-title-sub='[cg_gallery_user id=\"".$galeryNR."\"]'></div>
    <div class='td_gallery_info_shortcode_conf_status td_gallery_info_shortcode_conf_status_on   cg_tooltip cg_hide '   data-cg-shortcode='cg_gallery_user'  ></div>
    <div class='td_gallery_info_shortcode_conf_status td_gallery_info_shortcode_conf_status_off   cg_tooltip cg_hide'   data-cg-shortcode='cg_gallery_user'  ></div>
</div>";


echo "</div>";

echo "<div class='td_gallery_info_content'>";


//if(intval($galleryDbVersion)<14){
    $cgGalleryIdToShowForShortcode  = <<<HEREDOC
 id="$galeryNR"
HEREDOC;
//}

$cg_v14_note_caret = '';
$cg_v14_note_caret_text = '';

if(empty($galleryDbVersion)){
    global $wpdb;
    $tablename_options = $wpdb->prefix . "contest_gal1ery";
    $galleryDbVersion = $wpdb->get_var( "SELECT Version FROM $tablename_options WHERE id='$GalleryID'");
}

$cgBeforeSinceV14ExplanationRequired = false;

if(cg_check_if_new_registry_logic_explanation_note_required($galleryDbVersion)){
    $cgBeforeSinceV14ExplanationRequired = true;
}

if($cgBeforeSinceV14ExplanationRequired){
    $cg_v14_note_caret = 'cg_v14_note_caret';
    $cg_v14_note_caret_text = <<<HEREDOC
    <br><br><strong><span class="cg_color_red cg_v14_note_caret">NOTE:</span> For galleries created or copied in plugin version 14 or higher
                 "Registration form" and "Registration options"  are general and valid for all galleries created or copied in plugin version 14 or higher.<br><br>[cg_users_reg $cgGalleryIdToShowForShortcode] shortcode still requires id because of possible form translations for a gallery or shortcode interval configuration</strong><br>
HEREDOC;
}else{// then automatically must be higher then version 14 if this is the case
    if(intval($galleryDbVersion)>=14){
        $cg_v14_note_caret = 'cg_v14_note_caret';
        $cg_v14_note_caret_text = <<<HEREDOC
    <br><br><strong><span class="cg_color_red cg_v14_note_caret">NOTE:</span> "Registration form" and "Registration options" are general and valid for all galleries.<br><br>[cg_users_reg $cgGalleryIdToShowForShortcode] shortcode still requires id because of possible form translations for a gallery or shortcode interval configuration</strong><br>
HEREDOC;
    }
}

echo "<div class='td_gallery_info_shortcode'>
    <div>
         <div class='td_gallery_info_name_title'><span class='td_gallery_info_name_title_span $cg_v14_note_caret'>Registration form</span></div>
    <div class='td_gallery_info_name_span'>[cg_users_reg$cgGalleryIdToShowForShortcode]</div>
        <div>
            <span class=\"cg-info-icon $cg_v14_note_caret\">read info</span>
<span class=\"cg-info-container cg-info-container-gallery-user\" style=\"display: none;top:67px;\">Displays registration form<br><strong>Can only be added once on a page</strong>$cg_v14_note_caret_text</span>
        </div>
    </div>
    <div class='td_gallery_info_shortcode_edit cg_shortcode_copy cg_shortcode_copy_gallery cg_tooltip'></div>
    <div class='td_gallery_info_shortcode_conf   cg_tooltip'  data-cg-shortcode='cg_users_reg'  data-cg-title-main='Registration form' data-cg-title-sub='[cg_users_reg id=\"".$galeryNR."\"]'></div>
    <div class='td_gallery_info_shortcode_conf_status td_gallery_info_shortcode_conf_status_on   cg_tooltip cg_hide'    data-cg-shortcode='cg_users_reg'  ></div>
    <div class='td_gallery_info_shortcode_conf_status td_gallery_info_shortcode_conf_status_off   cg_tooltip cg_hide'   data-cg-shortcode='cg_users_reg'  ></div>
</div>";


echo "<div class='td_gallery_info_shortcode'>
    <div>
     <div class='td_gallery_info_name_title'>Gallery without voting</div>
    <div class='td_gallery_info_name_span'>[cg_gallery_no_voting id=\"".$galeryNR."\"]</div>
        <div>
            <span class=\"cg-info-icon\">read info</span>
            <span class=\"cg-info-container cg-info-container-gallery-user\" style=\"display: none;top:67px;\">All files are visible<br>Voting, sort by voting and preselect by voting is not possible and not visible<br>Not visible by default but can be make visible in \"Gallery view options\"<br>Can be used as normal gallery without voting
            <br>Can be added multiple times on a page with different id’s</span>
        </div>
    </div>
    <div class='td_gallery_info_shortcode_edit cg_shortcode_copy cg_shortcode_copy_gallery cg_tooltip'></div>
    <div class='td_gallery_info_shortcode_conf   cg_tooltip'  data-cg-shortcode='cg_gallery_no_voting'  data-cg-title-main='Gallery without voting' data-cg-title-sub='[cg_gallery_no_voting id=\"".$galeryNR."\"]'></div>
    <div class='td_gallery_info_shortcode_conf_status td_gallery_info_shortcode_conf_status_on   cg_tooltip cg_hide '   data-cg-shortcode='cg_gallery_no_voting'  ></div>
    <div class='td_gallery_info_shortcode_conf_status td_gallery_info_shortcode_conf_status_off   cg_tooltip cg_hide'   data-cg-shortcode='cg_gallery_no_voting'  ></div>
</div>";


echo "</div>";

echo "<div class='td_gallery_info_content' style='border-right: none;'>";

$cg_v14_note_caret = '';
$cg_v14_note_caret_text = '';

if($cgBeforeSinceV14ExplanationRequired){
    $cg_v14_note_caret = 'cg_v14_note_caret';
    $cg_v14_note_caret_text = <<<HEREDOC
    <br><br><strong><span class="cg_color_red cg_v14_note_caret">NOTE:</span> For galleries created or copied in plugin version 14 or higher "Login form" and "Login options" are general and valid for all galleries created or copied in plugin version 14 or higher.<br><br>[cg_users_login $cgGalleryIdToShowForShortcode] shortcode still requires id because of possible form translations for a gallery or shortcode interval configuration</strong><br>
HEREDOC;
}else{// then automatically must be higher then version 14 if this is the case
    if(intval($galleryDbVersion)>=14){
        $cg_v14_note_caret_text = <<<HEREDOC
    <br><br><strong><span class="cg_color_red cg_v14_note_caret">NOTE:</span> "Login form" and "Login options" are valid for all galleries.<br><br>[cg_users_login $cgGalleryIdToShowForShortcode] shortcode still requires id because of possible form translations for a gallery or shortcode interval configuration</strong><br>
HEREDOC;
    }
}

echo "<div class='td_gallery_info_shortcode'  >
    <div>
        <div class='td_gallery_info_name_title'><span class='td_gallery_info_name_title_span $cg_v14_note_caret'>User login</span></div>
    <div class='td_gallery_info_name_span'>[cg_users_login$cgGalleryIdToShowForShortcode]</div>
        <div>
            <span class=\"cg-info-icon $cg_v14_note_caret\">read info</span>
<span class=\"cg-info-container cg-info-container-gallery-user\" style=\"display: none;top:67px;\">Displays login form<br><br><strong>Login form is invisible if logged in</strong><br><br><strong>Can only be added once on a page</strong>$cg_v14_note_caret_text</span>
        </div>
    </div>
    <div class='td_gallery_info_shortcode_edit cg_shortcode_copy cg_shortcode_copy_gallery cg_tooltip'></div>
    <div class='td_gallery_info_shortcode_conf   cg_tooltip'  data-cg-shortcode='cg_users_login'  data-cg-title-main='User login' data-cg-title-sub='[cg_users_login id=\"".$galeryNR."\"]'></div>
    <div class='td_gallery_info_shortcode_conf_status td_gallery_info_shortcode_conf_status_on   cg_tooltip cg_hide'  data-cg-shortcode='cg_users_login'  ></div>
    <div class='td_gallery_info_shortcode_conf_status td_gallery_info_shortcode_conf_status_off   cg_tooltip cg_hide'  data-cg-shortcode='cg_users_login'   ></div>
</div>";


echo "<div class='td_gallery_info_shortcode' >
    <div>
         <div class='td_gallery_info_name_title'>Gallery of selected winners</div>
    <div class='td_gallery_info_name_span'>[cg_gallery_winner id=\"".$galeryNR."\"]</div>
        <div>
            <span class=\"cg-info-icon\">read info</span>
            <span class=\"cg-info-container cg-info-container-gallery-user\" style=\"display: none;top:67px;\">Only files which are marked as winner will be displayed<br>Total voting is visible<br>Star voting is not possible<br>\"Hide until vote\" and \"Show only user votes\" options are disabled<br>\"Delete votes\" is not possible<br>\"In gallery contact form button\" is not available</span>
        </div>
    </div>
    <div class='td_gallery_info_shortcode_edit cg_shortcode_copy cg_shortcode_copy_gallery cg_tooltip'></div>
    <div class='td_gallery_info_shortcode_conf   cg_tooltip'  data-cg-shortcode='cg_gallery_winner' data-cg-title-main='Gallery of selected winners' data-cg-title-sub='[cg_gallery_winner id=\"".$galeryNR."\"]'></div>
    <div class='td_gallery_info_shortcode_conf_status td_gallery_info_shortcode_conf_status_on   cg_tooltip cg_hide'  data-cg-shortcode='cg_gallery_winner' ></div>
    <div class='td_gallery_info_shortcode_conf_status td_gallery_info_shortcode_conf_status_off   cg_tooltip cg_hide'   data-cg-shortcode='cg_gallery_winner' ></div>
</div>";

echo "</div>";
echo "</div>";

?>