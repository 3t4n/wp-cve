<?php
$cg_v14_note_caret = '';
$cg_v14_general = '';
$cg_v14_email_confirmation_disabled_style = '';
$cg_v14_general_options_title_string = '';

if($galleryDbVersion>=14){
    $cg_v14_note_caret = 'cg_v14_note_caret';
    $cg_v14_general = 'cg_v14_general';
    $cg_v14_email_confirmation_disabled_style = 'opacity: 0.5;';
    $cg_v14_general_options_title_string = ' (general)';
}

echo <<<HEREDOC
    <div id="cg_main_options" class="cg_main_options cg_hidden">
        <div id="cg_main_options_tab">
             <div id="cg_tabs_container" >
            <div class="tabs" data-persist="true">
                  <div id="cg_main_options_tab_first_row" class="cg_main_options_tab_row" style="margin-bottom: 7px;border-bottom: thin solid #dedede;padding-bottom: 10px;">
                        <div class='cg_view_select cg_selected' cg-data-view="#view1" data-count="1" ><span class="cg_view_select_link" cg-data-view="#view1" cg-data-href="cgViewHelper1">Gallery view</span></div>
                        <div class='cg_view_select' cg-data-view="#view2" data-count="2"><span class="cg_view_select_link" cg-data-view="#view2" cg-data-href="cgViewHelper2">Entry view</span></div>
                        <div class='cg_view_select' cg-data-view="#view3" data-count="3"><span class="cg_view_select_link" cg-data-view="#view3" cg-data-href="cgViewHelper3">Gallery</span></div>
                        <div class='cg_view_select' cg-data-view="#view4" data-count="4"><span class="cg_view_select_link" cg-data-view="#view4" cg-data-href="cgViewHelper4">Voting</span></div>
                        <div class='cg_view_select' cg-data-view="#view5" data-count="5"><span class="cg_view_select_link" cg-data-view="#view5" cg-data-href="cgViewHelper5">Contact</span></div>
                         <div class='cg_view_select' cg-data-view="#view6" data-count="6"><span class="cg_view_select_link" cg-data-view="#view6" cg-data-href="cgViewHelper6">Admin mail</span></div>
                        <div class='cg_view_select' cg-data-view="#view7" data-count="7"><span class="cg_view_select_link" cg-data-view="#view7" cg-data-href="cgViewHelper7">Activation mail</span></div>
                          <div class='cg_view_select' cg-data-view="#view8" data-count="8"><span class="cg_view_select_link" cg-data-view="#view8" cg-data-href="cgViewHelper8">Icons</span></div>
                </div>
HEREDOC;

if(intval($galleryDbVersion)>=14){
    $styleTabContents="style='border-radius:none !important;position:relative;'";
    echo <<<HEREDOC
                <div id="cg_main_options_tab_second_row">
                    <div id="cg_main_options_tab_second_row_inner" class="cg_main_options_tab_row">
                        <div class='cg_view_select' cg-data-view="#view9" data-count="9"><span class="cg_view_select_link" cg-data-view="#view9" cg-data-href="cgViewHelper9">Translations</span></div>
                        <div class='cg_view_select cg_after_v14 cg_v14_note_caret' cg-data-view="#view10" data-count="10"><span class="cg_view_select_link" cg-data-view="#view10" cg-data-href="cgViewHelper10">General</span></div>
                      <div class='cg_view_select cg_after_v14 $cg_v14_note_caret' cg-data-view="#view11" data-count="11"><span class="cg_view_select_link" cg-data-view="#view11" cg-data-href="cgViewHelper11">Registration</span></div>
                      <div class='cg_view_select cg_after_v14 $cg_v14_note_caret' cg-data-view="#view12" data-count="12"><span class="cg_view_select_link" cg-data-view="#view12" cg-data-href="cgViewHelper12">Login</span></div>
                       <div class='cg_view_select cg_after_v14 cg_v14_note_caret' cg-data-view="#view13" data-count="13"><span class="cg_view_select_link" cg-data-view="#view13" cg-data-href="cgViewHelper13" id="cgSignInOptionsTabLink">Login via Google</span></div>
                      <div class='cg_view_select cg_after_v14 cg_v14_note_caret' cg-data-view="#view14" data-count="14"><span class="cg_view_select_link" cg-data-view="#view14" cg-data-href="cgViewHelper14">Translations</span></div>
                      <div cg-data-view="#view15" class="" data-count="15" id="cgSaveOptionsNavButton">
                        <span cg-data-view="#view15" cg-data-href="cgViewHelper15" class="cg_save_form_button cg_backend_button_gallery_action" >Save all options</span>
                      </div>
                    </div>
                </div>
HEREDOC;
}else {
    $styleTabContents = "style='border-radius:none !important;position:relative;'";
    echo <<<HEREDOC
                <div id="cg_main_options_tab_second_row">
                    <div id="cg_main_options_tab_second_row_inner" class="cg_main_options_tab_row">
                        <div class='cg_view_select' cg-data-view="#view9" data-count="9"><span class="cg_view_select_link" cg-data-view="#view9" cg-data-href="cgViewHelper9">Translations</span></div>
                      <div class='cg_view_select $cg_v14_note_caret' cg-data-view="#view10" data-count="10"><span class="cg_view_select_link" cg-data-view="#view10" cg-data-href="cgViewHelper10" style="$cg_v14_email_confirmation_disabled_style">E-mail confirmation e-mail</span></div>
                      <div class='cg_view_select $cg_v14_note_caret' cg-data-view="#view11" data-count="11"><span class="cg_view_select_link" cg-data-view="#view11" cg-data-href="cgViewHelper11">Registration</span></div>
                      <div class='cg_view_select $cg_v14_note_caret' cg-data-view="#view12" data-count="12"><span class="cg_view_select_link" cg-data-view="#view12" cg-data-href="cgViewHelper12">Login</span></div>
                                           <div class='cg_view_select cg_v14_note_caret' cg-data-view="#view13" data-count="13"><span class="cg_view_select_link" cg-data-view="#view13" cg-data-href="cgViewHelper13" id="cgSignInOptionsTabLink">Login via Google</span></div>
                      <div class='cg_view_select cg_v14_note_caret' cg-data-view="#view14" data-count="14"><span class="cg_view_select_link" cg-data-view="#view14" cg-data-href="cgViewHelper14">Translations</span></div>
                      <div cg-data-view="#view15"  class=""  data-count="15" id="cgSaveOptionsNavButton">
                        <span cg-data-view="#view15" cg-data-href="cgViewHelper15" class="cg_save_form_button cg_backend_button_gallery_action" >Save all options</span>
                      </div>
                    </div>
                </div>
HEREDOC;
}

echo <<<HEREDOC
            </div>
        </div>

        </div>
        <div id="cg_main_options_content" class="tabcontents" $styleTabContents>
            <h4 id="view1" class="cg_view_header">Gallery view options</h4>
<div class="cg_short_code_multiple_pics_configuration_buttons">
    <div class="cg_short_code_multiple_pics_configuration_buttons_container">
        <div class="cg_short_code_multiple_pics_configuration cg_short_code_multiple_pics_configuration_cg_gallery cg_active"> cg_gallery</div>
        <div class="cg_short_code_multiple_pics_configuration cg_short_code_multiple_pics_configuration_cg_gallery_user" >cg_gallery_user</div>
        <div class="cg_short_code_multiple_pics_configuration cg_short_code_multiple_pics_configuration_cg_gallery_no_voting">cg_gallery_no_voting</div>
        <div class="cg_short_code_multiple_pics_configuration cg_short_code_multiple_pics_configuration_cg_gallery_winner">cg_gallery_winner</div>
    </div>
</div>

<div class="cg_short_code_multiple_pics_configuration_note" >
<div class="cg_arrow_up"></div>
<b>NOTE:</b> "Gallery view options" can be configured for every gallery shortcode
</div>
            
            
HEREDOC;

include(__DIR__.'/shortcodes-configuration/shortcodes-configuration-multiple-pics/shortcode-multiple-pics-cg-gallery.php');
include(__DIR__.'/shortcodes-configuration/shortcodes-configuration-multiple-pics/shortcode-multiple-pics-cg-gallery-user.php');
include(__DIR__.'/shortcodes-configuration/shortcodes-configuration-multiple-pics/shortcode-multiple-pics-cg-gallery-no-voting.php');
include(__DIR__.'/shortcodes-configuration/shortcodes-configuration-multiple-pics/shortcode-multiple-pics-cg-gallery-winner.php');


echo <<<HEREDOC

                       <h4 id="view2" class="cg_view_header">Entry view options</h4>
                       
<div class="cg_short_code_single_pic_configuration_buttons">
    <div class="cg_short_code_single_pic_configuration_buttons_container">
        <div class="cg_short_code_single_pic_configuration cg_short_code_single_pic_configuration_cg_gallery cg_active">cg_gallery</div>
        <div class="cg_short_code_single_pic_configuration cg_short_code_single_pic_configuration_cg_gallery_user" >cg_gallery_user</div>
        <div class="cg_short_code_single_pic_configuration cg_short_code_single_pic_configuration_cg_gallery_no_voting">cg_gallery_no_voting</div>
        <div class="cg_short_code_single_pic_configuration cg_short_code_single_pic_configuration_cg_gallery_winner">cg_gallery_winner</div>
    </div>
</div>
                    
<div class="cg_short_code_single_pic_configuration_note">
    <div class="cg_arrow_up"></div>
<b>NOTE:</b> "Entry view options" can be configured for every gallery shortcode</div>               

HEREDOC;

// old code
echo '<input type="hidden" name="ScaleSizesGalery"  '.$ScaleAndCut.'  class="ScaleSizesGalery">';
echo '<input type="hidden" name="ScaleWidthGalery"  '.$ScaleOnly.'  class="ScaleWidthGalery">';

include(__DIR__.'/shortcodes-configuration/shortcodes-configuration-single-pic/shortcode-single-pic-cg-gallery.php');
include(__DIR__.'/shortcodes-configuration/shortcodes-configuration-single-pic/shortcode-single-pic-cg-gallery-user.php');
include(__DIR__.'/shortcodes-configuration/shortcodes-configuration-single-pic/shortcode-single-pic-cg-gallery-no-voting.php');
include(__DIR__.'/shortcodes-configuration/shortcodes-configuration-single-pic/shortcode-single-pic-cg-gallery-winner.php');


echo <<<HEREDOC
            <h4 id="view3" class="cg_view_header">Gallery options</h4>
            <div class="cg_view cgGalleryOptions cgViewHelper3">
HEREDOC;

$dateCurrent = date('Y-m-d H:i');
$dateCurrentWpConf = cg_get_time_based_on_wp_timezone_conf(time(),'Y-m-d H:i');

include(__DIR__ . '/views-content/gallery-options/view-gallery-options.php');


echo <<<HEREDOC
</div>
HEREDOC;


echo <<<HEREDOC
 </div>
             <h4 id="view4" class="cg_view_header">Voting options</h4>

<div class="cg_view cgVotingOptions cgViewHelper4" id="cgVotingOptions">
HEREDOC;

$userIP = sanitize_text_field(cg_get_user_ip());

$userIPunknown = '';

if($userIP=='unknown'){
    $userIPunknown = "<br><span style='color:red;'>Users IP can not be tracked because of your server system.<br>Your server provider track the IP in very unusual way.<br>
This recognition method would not work for you.<br>Please contact support@contest-gallery.com<br> and tell the name of your server provider<br>so it can be researched.</span>";
}

$FbLikeGoToGalleryLinkPlaceholder = site_url().'/';

include(__DIR__.'/views-content/view-voting-options.php');

echo <<<HEREDOC
 </div>
             <h4 id="view5" class="cg_view_header">Contact options</h4>

			   <div class="cg_view cgUploadOptions cgViewHelper5">
HEREDOC;


// Maximal möglich eingestellter Upload wird ermittelt
$upload_max_filesize = contest_gal1ery_return_mega_byte(ini_get('upload_max_filesize'));
$post_max_size = contest_gal1ery_return_mega_byte(ini_get('post_max_size'));

include(__DIR__.'/views-content/view-upload-options.php');

echo "</div>";

/*echo <<<HEREDOC
            <h4 id="view6" class="cg_view_header">Contact options</h4>
	<div class="cg_view  cgViewHelper6">
HEREDOC;

include(__DIR__.'/views-content/view-contact-options.php');

echo "</div>";*/

echo <<<HEREDOC
            <h4 id="view6" class="cg_view_header">Admin mail</h4>
	<div class="cg_view  cgViewHelper6">
HEREDOC;

include(__DIR__.'/views-content/view-admin-email-options.php');

echo "</div>";

echo <<<HEREDOC
            <h4 id="view7" class="cg_view_header">Activation mail</h4>
	<div class="cg_view  cgViewHelper7">
HEREDOC;

include(__DIR__.'/views-content/view-activation-email-options.php');

echo "</div>";

echo <<<HEREDOC

            <h4 id="view8" class="cg_view_header">Icons</h4>
            <div class="cg_view cgViewHelper8" >
HEREDOC;

include(__DIR__.'/views-content/view-icons-options.php');

echo "</div>";


echo <<<HEREDOC
            <h4 id="view9" class="cg_view_header">Translations</h4>
	<div class="cg_view  cgViewHelper9">
HEREDOC;

include(__DIR__.'/views-content/view-translations-options.php');

echo "</div>";

$cgV14disabled = '';

if(intval($galleryDbVersion)>=14){
    $cgV14disabled = 'cg_disabled';
}

if(intval($galleryDbVersion)<14){
echo <<<HEREDOC
            <h4 id="view10" class="cg_view_header">E-mail confirmation e-mail</h4>
	<div class='cg_view cgEmailConfirmationEmail cgViewHelper10'>
HEREDOC;
include(__DIR__.'/views-content/view-email-confirmation-email-options.php');
echo "</div>";

echo <<<HEREDOC
            <h4 id="view11" class="cg_view_header">Registration$cg_v14_general_options_title_string options</h4>
	<div class="cg_view cgRegistrationOptions cgViewHelper11">
HEREDOC;
include(__DIR__.'/views-content/view-registration-options.php');
echo "</div>";

echo <<<HEREDOC
            <h4 id="view12" class="cg_view_header">Login$cg_v14_general_options_title_string options</h4>
<div class="cg_view cgLoginOptions cgViewHelper12">
HEREDOC;
include(__DIR__.'/views-content/view-login-options.php');
echo "</div>";

echo <<<HEREDOC
            <h4 id="view13" class="cg_view_header">Login via Google (general) options</h4>
<div class="cg_view cgGoogleSignInOptions cgViewHelper13">
HEREDOC;
include(__DIR__.'/views-content/view-google-sign-in-options.php');
echo "</div>";

echo <<<HEREDOC
            <h4 id="view14" class="cg_view_header">Translations (general) options</h4>
	<div class="cg_view cgLoginOptions cgViewHelper14">
HEREDOC;
include(__DIR__.'/views-content/view-translations-general-options.php');
echo "</div>";

}else{

echo <<<HEREDOC
        <h4 id="view10" class="cg_view_header">General options</h4>
<div class="cg_view cgGeneralOptions cgViewHelper10">
HEREDOC;
include(__DIR__.'/views-content/view-general-options.php');
echo "</div>";

echo <<<HEREDOC
        <h4 id="view11" class="cg_view_header">Registration$cg_v14_general_options_title_string options</h4>
<div class="cg_view cgRegistrationOptions cgViewHelper11">
HEREDOC;
include(__DIR__.'/views-content/view-registration-options.php');
echo "</div>";

echo <<<HEREDOC
        <h4 id="view12" class="cg_view_header">Login$cg_v14_general_options_title_string options</h4>
<div class="cg_view cgLoginOptions cgViewHelper12">
HEREDOC;
include(__DIR__.'/views-content/view-login-options.php');
echo "</div>";

echo <<<HEREDOC
        <h4 id="view13" class="cg_view_header">Login via Google (general) options</h4>
<div class="cg_view cgGoogleSignInOptions cgViewHelper13">
HEREDOC;
include(__DIR__.'/views-content/view-google-sign-in-options.php');
echo "</div>";

echo <<<HEREDOC
        <h4 id="view14" class="cg_view_header">Translations (general) options</h4>
<div class="cg_view cgLoginOptions cgViewHelper14">
HEREDOC;
include(__DIR__.'/views-content/view-translations-general-options.php');
echo "</div>";

}



echo <<<HEREDOC
 </div>
<input type="hidden" name="changeSize" value="true" />
<div style="" id="cg_save_all_options" class="cg_hidden"><input  class="cg_backend_button_gallery_action" type="submit" value="Save all options" id="cgSaveOptionsButton" /></div>
            </div>
HEREDOC;



echo "</form>";



?>