<?php

echo <<<HEREDOC
<div class='cg_view_container'>
HEREDOC;

echo <<<HEREDOC
        <div class='cg_view_options_rows_container' id="cgGoogleOptionsRowsContainer">

<p class="cg_view_options_rows_container_title ">
        <strong>* NOTE:</strong> Login via Google options are general and valid for all galleries.<br>
</p>

HEREDOC;

echo <<<HEREDOC
<p class="cg_view_options_rows_container_title">
        <strong><span class="cg_color_red">NOTE:</span> Google sign in button is not visible If user is already logged in.</strong><br>
Use another browser where you are not logged in to test Google sign in button.<br>
</p>
HEREDOC;

$CheckMethodGoogleNameClass = 'CheckMethod';
$CheckMethodUploadGoogleClass = 'CheckMethodUpload';
$CheckMethodUploadGoogleName = 'RegUserUploadOnly';

$CheckGoogleDisabledClassBecauseOfPHPversion = '';
$cgGoogleSignInTestingFile = '';
$cgGoogleSignInTestingFilePath = '';
$cgIsGoogleSignLibraryMissingNote = '';
$cgIsGoogleSignLibraryMissingClass = '';
$cgIsGoogleSignLibraryMissingStyle = '';

echo <<<HEREDOC

<p class="cg_view_options_rows_container_title" style="font-size: 16px;line-height: 22px;">
        <span class="cg_color_red">NOTE:</span> Please check Google sign in documentation<br>
        to connect your domain and get the required client id.<br>
    <a href="https://www.contest-gallery.com/google-sign-in-documentation/" target="_blank">
        Google sign in documentation
    </a> 
</p>
<p class="cg_view_options_rows_container_title" style="font-size: 16px;line-height: 22px;">
        <span class="cg_color_red">NOTE:</span> Via Google sign in button logged in users are <strong>handled like WordPress users.</strong>
        <br>They will be <strong>logged in</strong> after using Google sign in button. <strong>If the Google signed in user is not a WordPress user</strong>
        <br><strong>a WordPress user with <a href="" class="cg_go_to_link" data-cg-go-to-link="RegOptionsUserGroupRolesContainer">configured user group role in "Registration options"</a> will be created.</strong>
        <br>The official <strong>@googlemail.com</strong> as e-mail will be used when the WordPress user will be created.
        <br>If the <strong>Google signed in user has already a WordPress account with @googlemail.com</strong> ending<br><strong>then NO new WordPress account will be created.</strong>
        <br>So if you as <strong>administrator use a @googlemail.com e-mail</strong> you <strong>would be directly logged in as administrator</strong>.
</p>
HEREDOC;

$siteUrl = get_site_url();

$siteUrlExploded = explode('//',$siteUrl);

if(strpos($siteUrlExploded[1],'/') !== false){
    $siteUrlExplodedPartTwo = explode('/',$siteUrlExploded[1]);
    $siteUrl = $siteUrlExploded[0].'//'.$siteUrlExplodedPartTwo[0];
}

echo <<<HEREDOC
<div id='cgGoogleSignInTestClientIdForDomainMessage' style="text-align: center;" class="cg_hide">
        <div style="font-size: 20px;margin: 15px 0 10px;"><b>Test google client id in combination with your domain</b></div>
        <div style="border:thin solid black;padding: 5px;"><b>Your client id</b><br><span id="cgGoogleClientIdDomainMessage"></span></div>
        <div style="border:thin solid black;border-top: none;margin-bottom: 20px;padding: 5px;"><b>Your domain</b><br><span id="cgGoogleSignInDomainMessage"></span></div>
         <div style="font-size: 20px;color: red;margin-bottom: 10px;"><b>Possible error messages</b><br></div>
         <div style="border:thin solid black;padding: 5px;"><b>invalid_client</b><br>the client id does not exists</div>
         <div style="border:thin solid black;border-top: none;margin-bottom: 20px;padding: 5px;"><b>redirect_uri_mismatch</b><br>the domain is not configured for the client id</div>
         <div style="font-size: 20px;color: green;margin-bottom: 10px;">Success screen</div>
         <div style="border:thin solid black;margin-bottom: 20px;padding: 5px;"><b>The login will be visible and possible. Everything ok then. You can close the pop up window then and go using Google sign in option of Contest Gallery.</b></div>
          <div style="font-size: 20px;color: green;margin-bottom: 10px;cursor: pointer;text-decoration: underline;">
            <a id="cgGoogleSignInTestingFileButton" target="_blank">Test Google sign in</a>
          </div>
</div>
HEREDOC;

echo <<<HEREDOC
        <div id='cgGoogleSignInTestClientIdForDomainMessage' style="text-align: center;" class="cg_hide">
                <div style="font-size: 20px;"><b>Test google client id in combination with your domain</b></div>
                <div style="border:thin solid black;"><b>Your client id</b><br><span id="cgGoogleClientIdDomainMessage"></span></div>
                <div style="border:thin solid black;border-top: none;"><b>Your domain</b><br><span id="cgGoogleSignInDomainMessage"></span></div>
                 <div style="font-size: 20px;color: red;"><b>Possible error messages</b><br></div>
                 <div style="border:thin solid black;"><b>invalid_client</b><br>the client id does not exists</div>
                 <div style="border:thin solid black;border-top: none;"><b>redirect_uri_mismatch</b><br>the domain is not configured for the client id</div>
                 <div style="font-size: 20px;color: green;">Success screen</div>
                 <div style="border:thin solid black;"><b>The login will be visible and possible. Everything ok then. You can close the pop up window then and continue using Google sign in option</b></div>
        </div>
        <div class='cg_view_options_row'>
            <div class="cg_view_option cg_border_border_top_left_radius_8_px cg_border_border_top_right_radius_8_px cg_view_option_full_width $cgProFalse" id="wp-TextBeforeGoogleSignInButton-wrap-Container">
                <div class="cg_view_option_title">
                    <p>Text before Google sign in button before logged in<br><span class="cg_view_option_title_note">After user is logged in text is not visible anymore</span></p>
                </div>
                <div class="cg_view_option_html">
                    <textarea class='cg-wp-editor-template' id='TextBeforeGoogleSignInButton'  name='TextBeforeGoogleSignInButton'>$TextBeforeGoogleSignInButton</textarea>
                </div>
            </div>
        </div>
        <div class='cg_view_options_row '> 
            <div class='cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse'>
                <div class='cg_view_option_title'>
                    <p>Google client id$cgIsGoogleSignLibraryMissingNote<br/><span class="cg_view_option_title_note"><a id="cgGoogleSignInTestClientIdForDomain" href="$cgGoogleSignInTestingFilePath#$ClientId" target="_blank" class="$cgIsGoogleSignLibraryMissingClass" style="$cgIsGoogleSignLibraryMissingStyle">Test google client id in combination with your domain</a><span id="cgGoogleSignInDomain" class="cg_hide" >$siteUrl</span></span></p>
                </div>
                <div class='cg_view_option_input'>
                    <input type="text" placeholder="" class="cg-long-input" id="GoogleClientId" name="GoogleClientId" maxlength="100" value="$ClientId">
                </div>
            </div>
         </div>
        <div class='cg_view_options_row cg_hide '> 
            <div class='cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse'>
                <div class='cg_view_option_title'>
                    <p>Google button text on page load<br/><span class="cg_view_option_title_note">Take care of <a href="https://developers.google.com/identity/branding-guidelines" target="_blank"  class="">Google branding guidlines</a><br><strong>It should contains the word "Google"<br>Keep it short, line breaks are not allowed</strong></span></p>
                </div>
                <div class='cg_view_option_input'>
                    <input type="text" class="cg-long-input" id="GoogleButtonTextOnLoad" name="GoogleButtonTextOnLoad" maxlength="100" value="$ButtonTextOnLoad">
                </div>
            </div>
            </div>
HEREDOC;

$btn_google_signin_light_normal_web = plugins_url('/../../../../v10/v10-css/btn_google_signin_light_normal_web.png', __FILE__);
$btn_google_signin_dark_normal_web = plugins_url('/../../../../v10/v10-css/btn_google_signin_dark_normal_web.png', __FILE__);

echo <<<HEREDOC
        <div class='cg_view_options_row '> 

    <div class='cg_view_option cg_view_option_full_width cg_border_top_none $cgProFalse'>
        <div class='cg_view_option_title'>
            <p>Google button theme style<br/><span class="cg_view_option_title_note">Relating <a href="https://developers.google.com/identity/branding-guidelines" target="_blank"  class="">Google branding guidlines</a> it can be only this two styles</span></p>
        </div>
        <div class='cg_view_option_radio_multiple'>
            <div class='cg_view_option_radio_multiple_container GoogleButtonStyleBrightContainer'>
                <div class='cg_view_option_radio_multiple_title'>
                    Bright style
                </div>
                <div class='cg_view_option_radio_multiple_input'>
                     <input type="radio" name="GoogleButtonStyle" class="GoogleButtonStyle cg_view_option_radio_multiple_input_field"  $ButtonStyleBrightChecked  value="bright" />
                </div>
                <div style="flex-basis: 100%;margin-top: 5px;">
                     <img src="$btn_google_signin_light_normal_web" style="margin: 0 auto;"/>
                </div>
            </div>
            <div class='cg_view_option_radio_multiple_container GoogleButtonStyleDarkContainer'>
                <div class='cg_view_option_radio_multiple_title'>
                    Dark style
                </div>
                <div class='cg_view_option_radio_multiple_input'>
                    <input type="radio" name="GoogleButtonStyle" class="GoogleButtonStyle cg_view_option_radio_multiple_input_field"  $ButtonStyleDarkChecked  value="dark" >
                </div>
                <div style="flex-basis: 100%;margin-top: 5px;">
                     <img src="$btn_google_signin_dark_normal_web" style="margin: 0 auto;"/>
                </div>
            </div>
        </div>
    </div>
    </div>
HEREDOC;

echo <<<HEREDOC
   <div class='cg_view_options_row'> 
            <div class='cg_view_option cg_view_option_full_width cg_border_top_none'>
                <div class='cg_view_option_title'>
                    <p>Messages background color depends on configurations in<br>
                        <a class="cg_go_to_link cg_no_outline_and_shadow_on_focus" href="#" data-cg-go-to-link="LoginFormShortcodeVisualConfiguration">Login options visual</a>
                    </p>
                </div>
            </div>
    </div>
   <div class='cg_view_options_row'> 
            <div class='cg_view_option cg_view_option_full_width cg_border_top_none'>
                <div class='cg_view_option_title'>
                    <p>Forwarding after sign or text after sign in can be configured in<br>
                        <a class="cg_go_to_link cg_no_outline_and_shadow_on_focus" href="#" data-cg-go-to-link="LoginFormShortcodeForwardingConfirmationConfiguration">Login options</a>
                    </p>
                </div>
            </div>
    </div>
HEREDOC;

echo <<<HEREDOC
</div>
HEREDOC;

echo <<<HEREDOC
</div>
HEREDOC;


