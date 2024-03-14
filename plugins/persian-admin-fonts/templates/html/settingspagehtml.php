<?php
if (!function_exists('add_action'))
{
    echo "an error occured! You may not be able to access this plugin via direct URL...";
    exit();
}
else if (!defined('ABSPATH'))
{
    echo "an error occured! You may not be able to access this plugin via direct URL...";
    exit();
}
/**/
?>
<script>let pfmdz_chech_google_fonts = false;</script>
<?php

/*Night Mode*/
$is_nightmode = get_option('pfmdz_nightmode');
if ($is_nightmode == '1'){$is_nightmode = 'on';}
/**/

/*Def Font-Weights Const*/
$define_font_Weights = array(
    'black' => 900,
    'extrablack' => 'bolder',
    'bold' => 'bold',
    'boldfd' => 'bold',
    'medium' => 500,
    'light' => 300,
    'ultralight' => 200,
    '' => 'normal',
    'normal' => 'normal',
    'fd' => 'normal',
    'web' => 'normal',
    'thin' => 10,
    'extralight' => 200,
    'demibold' => 600,
    'semibold' => 650,
    'ultrabold' => 750,
    'extrabold' => 800,
    'regular' => 'normal',
    'heavy' => 'bolder',
    'superheavy' => 1000,
);
$css_path = persianfontsmdez_PATH . "libs".DIRECTORY_SEPARATOR ."fonts".DIRECTORY_SEPARATOR ."css".DIRECTORY_SEPARATOR ."dynamicAdminFont.css"; //admin css dynamic file path

$css_front_path = persianfontsmdez_PATH . "libs".DIRECTORY_SEPARATOR ."fonts".DIRECTORY_SEPARATOR ."css".DIRECTORY_SEPARATOR ."dynamic-front-fonts.css"; //front css dynamic file path
/**/

/*Save Data*/
if (isset($_POST['submit'])) {

    /*verify fonts*/
    if (isset($_POST['selectFont-pfmdz'])){
        $current_font_sanitized = sanitize_text_field($_POST['selectFont-pfmdz']);
        $current_font_sanitized = explode('/', $current_font_sanitized);
        if (count($current_font_sanitized) !== 3){
            echo "<script> alert('Error: invalid font value!'); </script>";
            exit();
        }
    }

    if (isset($_POST['general-front-font'])){
        $current_font_sanitized = sanitize_text_field($_POST['general-front-font']);
        $current_font_sanitized = explode('/', $current_font_sanitized);
        if (count($current_font_sanitized) !== 3){
            echo "<script> alert('Error: invalid font value!'); </script>";
            exit();
        }
    }

    if (isset($_POST['headers-front-font'])){
        $current_font_sanitized = sanitize_text_field($_POST['headers-front-font']);
        $current_font_sanitized = explode('/', $current_font_sanitized);
        if (count($current_font_sanitized) !== 3){
            echo "<script> alert('Error: invalid font value!'); </script>";
            exit();
        }
    }

    if (isset($_POST['text-front-font'])){
        $current_font_sanitized = sanitize_text_field($_POST['text-front-font']);
        $current_font_sanitized = explode('/', $current_font_sanitized);
        if (count($current_font_sanitized) !== 3){
            echo "<script> alert('Error: invalid font value!'); </script>";
            exit();
        }
    }

    if(isset($_POST['catchgooglefonts-pfmdz'])){//Time to check Google fonts
        ?>
        <script>pfmdz_chech_google_fonts = true;</script>
        <?php
    }
    
    //set options
    if(isset($_POST['selectFont-pfmdz'])){
        $current_font_sanitized = sanitize_text_field($_POST['selectFont-pfmdz']);
        update_option('pfmdz_currentFont', $current_font_sanitized);
    }


    if(isset($_POST['active-pfmdz'])){
        $is_active = $this->sanitize_checkbox($_POST['active-pfmdz']) === true ? '1' : '0';
        update_option('pfmdz_isactive', $is_active);
    }else update_option('pfmdz_isactive', '0'); 

    if(isset($_POST['no-admin-important-pfmdz'])){
        $is_admin_no_important = $this->sanitize_checkbox($_POST['no-admin-important-pfmdz']) === true ? '1' : '0';
        update_option('pfmdz_admin_noimportant', $is_admin_no_important);
    }else update_option('pfmdz_admin_noimportant', '0'); 

    if(isset($_POST['italic-pfmdz'])){
        $is_italic = $this->sanitize_checkbox($_POST['italic-pfmdz']) === true ? '1' : '0';
        update_option('pfmdz_istalic', $is_italic);
    }else update_option('pfmdz_istalic', '0'); 

    if(isset($_POST['tinymceEffect-pfmdz'])){
        $is_tinymce = $this->sanitize_checkbox($_POST['tinymceEffect-pfmdz']) === true ? '1' : '0';
        update_option('pfmdz_tinymce', $is_tinymce);
    }else update_option('pfmdz_tinymce', '0'); 

    if(isset($_POST['compactfonts-pfmdz'])){
        $is_compact = $this->sanitize_checkbox($_POST['compactfonts-pfmdz']) === true ? '1' : '0';
        update_option('pfmdz_compact', $is_compact);
    }else update_option('pfmdz_compact', '0'); 

    if(isset($_POST['loginfonts-pfmdz'])){
        $is_login = $this->sanitize_checkbox($_POST['loginfonts-pfmdz']) === true ? '1' : '0';
        update_option('pfmdz_loginfonts', $is_login);
    }else update_option('pfmdz_loginfonts', '0'); 

    if(isset($_POST['elementor-pfmdz'])){
        $is_elementor = $this->sanitize_checkbox($_POST['elementor-pfmdz']) === true ? '1' : '0';
        update_option('pfmdz_elementorfonts', $is_elementor);
    }else update_option('pfmdz_elementorfonts', '0'); 

    if(isset($_POST['customcss-activate-pfmdz'])){
        $is_customcss = $this->sanitize_checkbox($_POST['customcss-activate-pfmdz']) === true ? '1' : '0';
        update_option('pfmdz_customcss', $is_customcss);
    }else update_option('pfmdz_customcss', '0'); 

    if(isset($_POST['customcsselem-activate-pfmdz'])){
        $is_elemcustomcss = $this->sanitize_checkbox($_POST['customcsselem-activate-pfmdz']) === true ? '1' : '0';
        update_option('pfmdz_customelemcss', $is_elemcustomcss);
    }else update_option('pfmdz_customelemcss', '0'); 

    if(isset($_POST['pfmdz-elem-theme'])){
        $elem_theme = $this->sanitize_checkbox($_POST['pfmdz-elem-theme']) === true ? $_POST['pfmdz-elem-theme'] : '0';
        update_option('pfmdz_elemtheme', $elem_theme);
    }

    if(isset($_POST['pfmdz-wp-theme'])){
        $wp_theme = $this->sanitize_checkbox($_POST['pfmdz-wp-theme']) === true ? $_POST['pfmdz-wp-theme'] : '0';
        update_option('pfmdz_wptheme', $wp_theme);
    }

    if(isset($_POST['pfmdz-allowfontsupload'])){
        $allow_fonts_upload = $this->sanitize_checkbox($_POST['pfmdz-allowfontsupload']) === true ? '1' : '0';
        update_option('pfmdz_allowfontsupload', $allow_fonts_upload);
    }else update_option('pfmdz_allowfontsupload', '0'); 


    //Custom admin fonts
    if(isset($_POST['pfmdz-admin-usecustomfonts'])){
        $use_admin_customfonts = $this->sanitize_checkbox($_POST['pfmdz-admin-usecustomfonts']) === true ? '1' : '0';
        update_option('pfmdz_useadmincustomfonts', $use_admin_customfonts);
    }else update_option('pfmdz_useadmincustomfonts', '0'); 

    if(isset($_POST['pfmdz-admin-customfont1'])){
        $admin_customfont1 = sanitize_text_field($_POST['pfmdz-admin-customfont1']);
        update_option('pfmdz_admincustomfont1', $admin_customfont1);
    }

    if(isset($_POST['pfmdz-admin-customfont2'])){
        $admin_customfont2 = sanitize_text_field($_POST['pfmdz-admin-customfont2']);
        update_option('pfmdz_admincustomfont2', $admin_customfont2);
    }

    if(isset($_POST['pfmdz-admin-customfamily'])){
        $admin_customfamily = sanitize_text_field($_POST['pfmdz-admin-customfamily']);
        update_option('pfmdz_admincustomfamily', $admin_customfamily);
    }

    if(isset($_POST['pfmdz-admin-customweight'])){
        $admin_customweight = sanitize_text_field($_POST['pfmdz-admin-customweight']);
        update_option('pfmdz_admincustomweight', $admin_customweight);
    }
    
    //front fonts
    if(isset($_POST['active-pfmdz-front'])){
        $is_front_fonts = $this->sanitize_checkbox($_POST['active-pfmdz-front']) === true ? '1' : '0';
        update_option('pfmdz_frontfonts', $is_front_fonts);
    }else update_option('pfmdz_frontfonts', '0'); 

    if(isset($_POST['general-front-font'])){
        $generalfront_font_sanitized = sanitize_text_field($_POST['general-front-font']);
        update_option('pfmdz_general_frontfont', $generalfront_font_sanitized);
    }

    if(isset($_POST['headers-front-font'])){
        $headersfront_font_sanitized = sanitize_text_field($_POST['headers-front-font']);
        update_option('pfmdz_headers_frontfont', $headersfront_font_sanitized);
    }

    if(isset($_POST['text-front-font'])){
        $textfront_font_sanitized = sanitize_text_field($_POST['text-front-font']);
        update_option('pfmdz_text_frontfont', $textfront_font_sanitized);
    }

    if(isset($_POST['customfrontcss-activate-pfmdz'])){
        $is_frontcustomcss = $this->sanitize_checkbox($_POST['customfrontcss-activate-pfmdz']) === true ? '1' : '0';
        update_option('pfmdz_isfrontcss', $is_frontcustomcss);
    }else update_option('pfmdz_isfrontcss', '0'); 

    if(isset($_POST['frontfonts-important'])){
        $is_frontimportant = $this->sanitize_checkbox($_POST['frontfonts-important']) === true ? '1' : '0';
        update_option('pfmdz_isfront_important', $is_frontimportant);
    }else update_option('pfmdz_isfront_important', '0'); 

    //Custom Front Fonts
    if(isset($_POST['pfmdz-front-usecustomfonts1'])){
        $use_front_customfonts1 = $this->sanitize_checkbox($_POST['pfmdz-front-usecustomfonts1']) === true ? '1' : '0';
        update_option('pfmdz_usefrontcustomfonts1', $use_front_customfonts1);
    }else update_option('pfmdz_usefrontcustomfonts1', '0'); 

    if(isset($_POST['pfmdz-front-customfont11'])){
        $front_customfont11 = sanitize_text_field($_POST['pfmdz-front-customfont11']);
        update_option('pfmdz_frontcustomfont11', $front_customfont11);
    }

    if(isset($_POST['pfmdz-front-customfont12'])){
        $front_customfont12 = sanitize_text_field($_POST['pfmdz-front-customfont12']);
        update_option('pfmdz_frontcustomfont12', $front_customfont12);
    }

    if(isset($_POST['pfmdz-front-customfamily12'])){
        $front_customfamily12 = sanitize_text_field($_POST['pfmdz-front-customfamily12']);
        update_option('pfmdz_frontcustomfamily12', $front_customfamily12);
    }

    if(isset($_POST['pfmdz-front-customweight12'])){
        $front_customweight12 = sanitize_text_field($_POST['pfmdz-front-customweight12']);
        update_option('pfmdz_frontcustomweight12', $front_customweight12);
    }

    
    

    if(isset($_POST['pfmdz-front-usecustomfonts2'])){
        $use_front_customfonts2 = $this->sanitize_checkbox($_POST['pfmdz-front-usecustomfonts2']) === true ? '1' : '0';
        update_option('pfmdz_usefrontcustomfonts2', $use_front_customfonts2);
    }else update_option('pfmdz_usefrontcustomfonts2', '0'); 

    if(isset($_POST['pfmdz-front-customfont21'])){
        $front_customfont21 = sanitize_text_field($_POST['pfmdz-front-customfont21']);
        update_option('pfmdz_frontcustomfont21', $front_customfont21);
    }

    if(isset($_POST['pfmdz-front-customfont22'])){
        $front_customfont22 = sanitize_text_field($_POST['pfmdz-front-customfont22']);
        update_option('pfmdz_frontcustomfont22', $front_customfont22);
    }

    if(isset($_POST['pfmdz-front-customfamily22'])){
        $front_customfamily22 = sanitize_text_field($_POST['pfmdz-front-customfamily22']);
        update_option('pfmdz_frontcustomfamily22', $front_customfamily22);
    }

    if(isset($_POST['pfmdz-front-customweight22'])){
        $front_customweight22 = sanitize_text_field($_POST['pfmdz-front-customweight22']);
        update_option('pfmdz_frontcustomweight22', $front_customweight22);
    }

    if(isset($_POST['pfmdz-front-usecustomfonts3'])){
        $use_front_customfonts3 = $this->sanitize_checkbox($_POST['pfmdz-front-usecustomfonts3']) === true ? '1' : '0';
        update_option('pfmdz_usefrontcustomfonts3', $use_front_customfonts3);
    }else update_option('pfmdz_usefrontcustomfonts3', '0'); 

    if(isset($_POST['pfmdz-front-customfont31'])){
        $front_customfont31 = sanitize_text_field($_POST['pfmdz-front-customfont31']);
        update_option('pfmdz_frontcustomfont31', $front_customfont31);
    }

    if(isset($_POST['pfmdz-front-customfont32'])){
        $front_customfont32 = sanitize_text_field($_POST['pfmdz-front-customfont32']);
        update_option('pfmdz_frontcustomfont32', $front_customfont32);
    }

    if(isset($_POST['pfmdz-front-customfamily32'])){
        $front_customfamily32 = sanitize_text_field($_POST['pfmdz-front-customfamily32']);
        update_option('pfmdz_frontcustomfamily32', $front_customfamily32);
    }

    if(isset($_POST['pfmdz-front-customweight32'])){
        $front_customweight32 = sanitize_text_field($_POST['pfmdz-front-customweight32']);
        update_option('pfmdz_frontcustomweight32', $front_customweight32);
    }
    
    /*save custom css*/
    if(isset($_POST['csscode-pfmdz'])){
        $sanitized_css = sanitize_textarea_field($_POST['csscode-pfmdz']);
        $sanitized_css = wp_unslash($sanitized_css);
        update_option('pfmdz_user_customcss', $sanitized_css);
    }

    /*save elem custom css*/
    if(isset($_POST['csscodeelem-pfmdz'])){
        $sanitized_elemcss = sanitize_textarea_field($_POST['csscodeelem-pfmdz']);
        $sanitized_elemcss = wp_unslash($sanitized_elemcss);
        update_option('pfmdz_user_customelemcss', $sanitized_elemcss);
    }

    if(isset($_POST['dontaddfont-pfmdz'])){
        $sanitized_nofont_classes = sanitize_textarea_field($_POST['dontaddfont-pfmdz']);
        update_option('pfmdz_nofonts_clases', $sanitized_nofont_classes);
    }
    
    /*save custom front css*/
    if(isset($_POST['csscode-pfmdz-front'])){
        $sanitized_frontcss = sanitize_textarea_field($_POST['csscode-pfmdz-front']);
        $sanitized_frontcss = wp_unslash($sanitized_frontcss);
        update_option('pfmdz_user_customfrontcss', $sanitized_frontcss);
    }
    /**/

    /*Google Fonts*/
    if(isset($_POST['removeelemfonts-pfmdz'])){
        $remove_elem_fonts = $this->sanitize_checkbox($_POST['removeelemfonts-pfmdz']) === true ? '1' : '0';
        update_option('pfmdz_removeelemfonts', $remove_elem_fonts);
    }else update_option('pfmdz_removeelemfonts', '0'); 

    if(isset($_POST['removeallgooglefonts-pfmdz'])){
        $remove_all_google_fonts = $this->sanitize_checkbox($_POST['removeallgooglefonts-pfmdz']) === true ? '1' : '0';
        update_option('pfmdz_removegooglefonts', $remove_all_google_fonts);
    }else update_option('pfmdz_removegooglefonts', '0'); 

    
    
    update_option('pfmdz_plugin_version', PFMDZ_VERSION );//Define DB Version
    
    /*update ADMIN CSS File*/
    $font_arr = sanitize_text_field($_POST['selectFont-pfmdz']);
    $font_arr = explode('/', $font_arr);

    if(isset($is_italic)){
    if ($is_italic == '1') {/*check the italic*/
        $italic = 'italic';
    }else $italic = 'normal';
    }else $italic = 'normal';

    if(isset($is_compact)){
    if ($is_compact == '1') {/*check the compact*/
        $letterspacing = 'letter-spacing: -1.1px !important;';
    }else $letterspacing = '';
    }else $letterspacing = '';

    if(isset($is_tinymce)){
    if ($is_tinymce == '1') {/*check the TinyMce*/
        $is_tinymce = 'textarea.wp-editor-area {font-family:'.$font_arr[0].' !important; font-style: '.$italic.' !important;}';
    }else $is_tinymce = '';
    }else $is_tinymce = '';

    if(isset($is_admin_no_important)){
    if($is_admin_no_important == '1') {
        $admin_importnat = "";
    }else $admin_importnat = "!important";
    }else $admin_importnat = "!important";

    if(isset($is_customcss)){
    if ($is_customcss == '1'){/*check the user custom css*/
        $tmp_css = get_option('pfmdz_user_customcss');
    }else $tmp_css = '';
    }else $tmp_css = '';

    if ($sanitized_nofont_classes != ''){ //exclude user entered CSS classes

        $sanitized_nofont_classes = explode('|', $sanitized_nofont_classes);
        $nofonts_classe = '';

        foreach($sanitized_nofont_classes as $sanitized_nofont_classe){
            $nofonts_classe .= ':not('.$sanitized_nofont_classe.')';
        }

    }else $nofonts_classe = '';

    if(!isset($use_admin_customfonts)){
        $use_admin_customfonts = 0;
    }
    if($use_admin_customfonts != '1'){

        $font_urls = "
        @font-face {
            font-family: '".$font_arr[0]."';
            font-style: normal;
            font-weight: ".$font_arr[2].";
            src: url('../fonts/".$font_arr[0]."/eot/".$font_arr[1].".eot');
            src: url('../fonts/".$font_arr[0]."/eot/".$font_arr[1].".eot?#iefix') format('embedded-opentype'),
                 url('../fonts/".$font_arr[0]."/woff2/".$font_arr[1].".woff2') format('woff2'),
                 url('../fonts/".$font_arr[0]."/woff/".$font_arr[1].".woff') format('woff'),
                 url('../fonts/".$font_arr[0]."/ttf/".$font_arr[1].".ttf') format('truetype');
        }";//Get ready fonts

    }else if ($use_admin_customfonts == '1') {

        $admin_woff = get_option("pfmdz_admincustomfont1");
        $admin_woff2 = get_option("pfmdz_admincustomfont2");
        $admin_family = get_option("pfmdz_admincustomfamily");
        $admin_weight = get_option("pfmdz_admincustomweight");

        $font_arr = explode("/", $admin_woff);
        $font_arr = end($font_arr);
        $font_arr2 = explode("-", $font_arr);
        $font_arr = array();
        $font_arr[0] = $font_arr2[0];//Set Font Family

        $font_weight = explode(".", $font_arr2[1]);
        $font_weight[0] = strtolower($font_weight[0]);

        $font_arr[2] = $define_font_Weights[$font_weight[0]];
        if($font_arr[2] == "NULL" || $font_arr[2] === NULL){
            $font_arr[2] = "normal";
        }

        if($admin_family != "" && $admin_family != " " && $admin_family != false && $admin_family != NULL){
            $font_arr[0] = $admin_family;
        }

        if($admin_weight != "" && $admin_weight != " " && $admin_weight != false && $admin_weight != NULL){
            $font_arr[2] = $admin_weight;
        }

        $font_urls = "
        @font-face {
            font-family: '".$font_arr[0]."';
            font-style: normal;
            font-weight: ".$font_arr[2].";
            src: url('".$admin_woff2."') format('woff2'),
                 url('".$admin_woff."') format('woff'),
        }";//Get ready user fonts

    }


    $new_content = "
    ".$font_urls."
    body,#wpadminbar *:not(.ab-icon):not(.dashicons):not([class*='icon'])".$nofonts_classe.",.wp-core-ui,.media-menu,.media-frame *:not(.dashicons),.media-modal *:not(.dashicons),.code, code, input".$nofonts_classe.", select".$nofonts_classe.", textarea *:not([class='wp-editor-area']), button:not(.dashicons):not(.ed_button):not([class*='icon']):not(.mce-ico) {font-family:'".$font_arr[0]."' ".$admin_importnat."; font-style: ".$italic." !important;}
    h1".$nofonts_classe.", h2".$nofonts_classe.", h3".$nofonts_classe.", h4".$nofonts_classe.", h5".$nofonts_classe.", h6".$nofonts_classe.",input".$nofonts_classe.", textarea".$nofonts_classe.", div:not(.star):not(.dashicons):not([class*='icon']):not(.mce-ico)".$nofonts_classe.", p".$nofonts_classe.", a:not(.dashicons):not([class*='icon'])".$nofonts_classe.", span:not(.ab-icon):not(.dashicons):not(.kHuXxx):not([class*='icon']):not(.mce-ico)".$nofonts_classe.", i:not(.dashicons):not([class*='icon']):not(.mce-ico) {font-family: '".$font_arr[0]."' ".$admin_importnat."; font-style: ".$italic." !important; ".$letterspacing."}
    h1 {font-weight: bold;} div.sc-gIBoTZ.sc-fyrnIy.iMHKcG.ikOLpJ{font-family: Arial, Roboto-Regular, HelveticaNeue, sans-serif !important;} ".$is_tinymce." ".$tmp_css."";//Final CSS

    $this->writeToFile($css_path, $new_content);

    /*update FRONT css file*/
    if(isset($_POST['active-pfmdz-front'])){

        $font_general_arr = sanitize_text_field($_POST['general-front-font']);
        $font_general_arr = explode('/', $font_general_arr);
        $is_frontimportant = get_option('pfmdz_isfront_important');

        if(isset($is_frontimportant)){
        if ($is_frontimportant == "1"){//add !important css attr
            $important = '!important';
        }else $important = '';
        }else $important = '';

        $font_header_arr = sanitize_text_field($_POST['headers-front-font']);
        if(!isset($use_front_customfonts2)){
            $use_front_customfonts2 = 0;
        }

        if ($font_header_arr != 'Arial/Arial/400' || $use_front_customfonts2 == '1' ){

            if($use_front_customfonts2 != '1'){//Use custom Fonts?

            $font_header_arr = explode('/', $font_header_arr);
            $css_headerfonts_face = "
            @font-face {
            font-family: '".$font_header_arr[0]."';
            font-style: normal;
            font-weight: ".$font_header_arr[2].";
            src: url('../fonts/".$font_header_arr[0]."/woff2/".$font_header_arr[1].".woff2') format('woff2'),
            url('../fonts/".$font_header_arr[0]."/woff/".$font_general_arr[1].".woff') format('woff'),
            url('../fonts/".$font_header_arr[0]."/ttf/".$font_header_arr[1].".ttf') format('truetype');
            }
            ";

            }else if($use_front_customfonts2 == '1') {

            $front_woff = get_option("pfmdz_frontcustomfont21");
            $front_woff2 = get_option("pfmdz_frontcustomfont22");

            $font_arr = explode("/", $front_woff);
            $font_arr = end($font_arr);
            $font_arr2 = explode("-", $font_arr);
            $font_arr = array();
            $font_arr[0] = $font_arr2[0];//Set Font Family

            $font_weight = explode(".", $font_arr2[1]);
            $font_weight[0] = strtolower($font_weight[0]);

            $font_arr[2] = $define_font_Weights[$font_weight[0]];
            if($font_arr[2] == "NULL" || $font_arr[2] === NULL){
                $font_arr[2] = "normal";
            }

            $cust_family = get_option("pfmdz_frontcustomfamily22");
            $cust_weight = get_option("pfmdz_frontcustomweight22");

            if($cust_family != "" && $cust_family != " " && $cust_family != false && $cust_family != NULL){

                $font_arr[0] = $cust_family;
            }
        
            if($cust_weight != "" && $cust_weight != " " && $cust_weight != false && $cust_weight != NULL){

                $font_arr[2] = $cust_weight;
            }

            $css_headerfonts_face = "
            @font-face {
            font-family: '".$font_arr[0]."';
            font-style: normal;
            font-weight: ".$font_arr[2].";
            src: url('".$front_woff2."') format('woff2'),
            url('".$front_woff."') format('woff');
            }
            ";
            $font_header_arr = array();
            $font_header_arr[0] = $font_arr[0];
            $font_header_arr[2] = $font_arr[2];

            }

            
            $css_fonts_header = "
            h1:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), h2:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), h3:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), h4:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), h5:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), h6:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab) {font-family: '".$font_header_arr[0]."' ".$important."; font-weight: ".$font_header_arr[2]." ".$important.";}
            ";
        }else {
            $css_headerfonts_face = '';
            $css_fonts_header = '';
        }
        

        $font_text_arr = sanitize_text_field($_POST['text-front-font']);
        if(!isset($use_front_customfonts3)){
            $use_front_customfonts3 = 0;
        }

        if ($font_text_arr != 'Arial/Arial/400' || $use_front_customfonts3 == '1'){

            if($use_front_customfonts3 != '1'){//Use custom Fonts?
            $font_text_arr = explode('/', $font_text_arr);
            $css_textfonts_face = "
            @font-face {
            font-family: '".$font_text_arr[0]."';
            font-style: normal;
            font-weight: ".$font_text_arr[2].";
            src: url('../fonts/".$font_text_arr[0]."/woff2/".$font_text_arr[1].".woff2') format('woff2'),
            url('../fonts/".$font_text_arr[0]."/woff/".$font_text_arr[1].".woff') format('woff'),
            url('../fonts/".$font_text_arr[0]."/ttf/".$font_text_arr[1].".ttf') format('truetype');
            }
            ";

            }else if ($use_front_customfonts3 == '1'){

            $front_woff = get_option("pfmdz_frontcustomfont31");
            $front_woff2 = get_option("pfmdz_frontcustomfont32");

            $font_arr = explode("/", $front_woff);
            $font_arr = end($font_arr);
            $font_arr2 = explode("-", $font_arr);
            $font_arr = array();
            $font_arr[0] = $font_arr2[0];//Set Font Family

            $font_weight = explode(".", $font_arr2[1]);
            $font_weight[0] = strtolower($font_weight[0]);

            $font_arr[2] = $define_font_Weights[$font_weight[0]];
            if($font_arr[2] == "NULL" || $font_arr[2] === NULL){
                $font_arr[2] = "normal";
            }

            $cust_family = get_option("pfmdz_frontcustomfamily32");
            $cust_weight = get_option("pfmdz_frontcustomweight32");

            if($cust_family != "" && $cust_family != " " && $cust_family != false && $cust_family != NULL){

                $font_arr[0] = $cust_family;
            }
        
            if($cust_weight != "" && $cust_weight != " " && $cust_weight != false && $cust_weight != NULL){

                $font_arr[2] = $cust_weight;
            }

            $css_textfonts_face = "
            @font-face {
            font-family: '".$font_arr[0]."';
            font-style: normal;
            font-weight: ".$font_arr[2].";
            src: url('".$front_woff2."') format('woff2'),
            url('".$front_woff."') format('woff');
            }
            ";
            $font_text_arr = array();
            $font_text_arr[0] = $font_arr[0];
            $font_text_arr[2] = $font_arr[2];

            }
            
            $css_fonts_text = "
            a:not(.dashicons):not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), span:not(.ab-icon):not(.dashicons):not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), div:not(.dashicons):not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), p:not(.dashicons):not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), sub:not(.dashicons):not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), label:not(.dashicons):not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), legend:not(.dashicons):not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), small:not(.dashicons):not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), strong:not(.dashicons):not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), table, ul:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), li:not(.dashicons):not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), form, sup:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), caption:not(.dashicons):not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab) {font-family: '".$font_text_arr[0]."' ".$important."; font-weight: ".$font_text_arr[2]." ".$important.";}
            ";
        }else {
            $css_textfonts_face = '';
            $css_fonts_text = '';
        }

        if(isset($is_frontcustomcss)){
        if ($is_frontcustomcss == '1'){/*check the user custom css for front*/
            $tmp_css = get_option('pfmdz_user_customfrontcss');
        }else $tmp_css = '';
        }else $tmp_css = '';

        if(!isset($use_front_customfonts1)){
            $use_front_customfonts1 = 0;
        }
        if($use_front_customfonts1 != '1'){//Use custom Fonts?

        $css_general_face = "
        @font-face {
        font-family: '".$font_general_arr[0]."';
        font-style: normal;
        font-weight: ".$font_general_arr[2].";
        src: url('../fonts/".$font_general_arr[0]."/woff2/".$font_general_arr[1].".woff2') format('woff2'),
        url('../fonts/".$font_general_arr[0]."/woff/".$font_general_arr[1].".woff') format('woff'),
        url('../fonts/".$font_general_arr[0]."/ttf/".$font_general_arr[1].".ttf') format('truetype');
        }
        ";

        }else if ($use_front_customfonts1 == '1'){

        $front_woff = get_option("pfmdz_frontcustomfont11");
        $front_woff2 = get_option("pfmdz_frontcustomfont12");

        $font_arr = explode("/", $front_woff);
        $font_arr = end($font_arr);
        $font_arr2 = explode("-", $font_arr);
        $font_arr = array();
        $font_arr[0] = $font_arr2[0];//Set Font Family

        $font_weight = explode(".", $font_arr2[1]);
        $font_weight[0] = strtolower($font_weight[0]);

        $font_arr[2] = $define_font_Weights[$font_weight[0]];
        if($font_arr[2] == "NULL" || $font_arr[2] === NULL){
            $font_arr[2] = "normal";
        }

        $cust_family = get_option("pfmdz_frontcustomfamily12");
        $cust_weight = get_option("pfmdz_frontcustomweight12");

        if($cust_family != "" && $cust_family != " " && $cust_family != false && $cust_family != NULL){

            $font_arr[0] = $cust_family;
        }
        
        if($cust_weight != "" && $cust_weight != " " && $cust_weight != false && $cust_weight != NULL){

            $font_arr[2] = $cust_weight;
        }

        $css_general_face = "
        @font-face {
        font-family: '".$font_arr[0]."';
        font-style: normal;
        font-weight: ".$font_arr[2].";
        src: url('".$front_woff2."') format('woff2'),
        url('".$front_woff."') format('woff');
        }
        ";
        $font_general_arr = array();
        $font_general_arr[0] = $font_arr[0];
        $font_general_arr[2] = $font_arr[2];

        }

        $new_content = "
        ".$css_general_face."
        ".$css_headerfonts_face."
        ".$css_textfonts_face."
        html, body, div:not(.dashicons):not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), span:not(.ab-icon):not(.dashicons):not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), applet:not([class*='icon']), object:not([class*='icon']), iframe, h1:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), h2:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), h3:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), h4:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), h5:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), h6:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), p:not(.dashicons):not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), blockquote:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), pre:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), a:not(.dashicons):not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), abbr:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), acronym:not([class*='icon']), address:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), big:not([class*='icon']), cite:not([class*='icon']), code:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), del:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), dfn:not([class*='icon']), em:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), img:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), ins:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), kbd:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), q:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), s:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), samp:not([class*='icon']), small:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), strike:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), strong:not(.dashicons):not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), sub:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), sup:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), tt:not([class*='icon']), var:not([class*='icon']), b:not([class*='icon']), u:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), i:not(.dashicons):not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), center:not([class*='icon']), dl:not([class*='icon']), dt:not([class*='icon']), dd:not([class*='icon']), ol:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), ul:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), li:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), fieldset:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), form:not([class*='icon']), label:not(.dashicons):not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), legend:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), table:not([class*='icon']), caption:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), tbody:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), tfoot:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), thead:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), tr:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), th:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), td:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), article:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), aside:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), canvas:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), details:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), embed:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), figure:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), figcaption:not([class*='icon']), footer:not([class*='icon']), header:not([class*='icon']), hgroup:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), menu:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), nav:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), output:not([class*='icon']), ruby:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), section:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), summary:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), time:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), mark:not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab), audio, video, button:not(.dashicons):not([class*='icon']):not(.fa):not(.fas):not(.far):not(.fab) {font-family: '".$font_general_arr[0]."' ".$important."; font-weight: ".$font_general_arr[2]." ".$important.";}
        ".$css_fonts_header."
        ".$css_fonts_text."
        ".$tmp_css."
        ";

        $this->writeToFile($css_front_path, $new_content);
    }

    /*Solve update issue*/
    $admin_css_file = file_get_contents($css_path);
    $front_css_file = file_get_contents($css_front_path);
    
    update_option("pfmdz_admincss_file", $admin_css_file);
    update_option("pfmdz_frontcss_file", $front_css_file);

    //Solve browser's cache issue
    $pfmdz_tmp_ver = get_option('pfmdz_tmpversion');
    if(!$pfmdz_tmp_ver){
        update_option("pfmdz_tmpversion", "0.0.0.1");
    }else {
        $new_ver = $this->version_increaser($pfmdz_tmp_ver);
        update_option("pfmdz_tmpversion", $new_ver);
    }

    ?>
    <script>
    let trans0 = "<?php echo __('If the font does not change in the admin or front of your site, refresh the page using the control buttons + F5.', 'pfmdz') ?>";
    if(!pfmdz_chech_google_fonts){
        alert(trans0);
        window.location.reload();
    }
    </script>
    <?php
}
/**/

/*get options from wpdb */
$is_active = get_option('pfmdz_isactive') == "1" ? "checked" : "";
$is_italic = get_option('pfmdz_istalic') == "1" ? "checked" : "";
$is_tinymce = get_option('pfmdz_tinymce') == "1" ? "checked" : "";
$is_compact = get_option('pfmdz_compact') == "1" ? "checked" : "";
$is_login = get_option('pfmdz_loginfonts') == "1" ? "checked" : "";
$is_elementor = get_option('pfmdz_elementorfonts') == "1" ? "checked" : "";
$is_customcss = get_option('pfmdz_customcss') == "1" ? "checked" : "";
$is_elemcustomcss = get_option('pfmdz_customelemcss') == "1" ? "checked" : "";
$is_front_fonts = get_option('pfmdz_frontfonts') == "1" ? "checked" : "";
$is_frontcustomcss = get_option('pfmdz_isfrontcss') == "1" ? "checked" : "";
$is_frontimportant = get_option('pfmdz_isfront_important') == "1" ? "checked" : "";
$elem_theme = get_option('pfmdz_elemtheme');
$wp_theme = get_option('pfmdz_wptheme');
$allow_fonts_upload = get_option('pfmdz_allowfontsupload') == "1" ? "checked" : "";
$remove_elem_fonts = get_option('pfmdz_removeelemfonts') == "1" ? "checked" : "";
$remove_all_google_fonts = get_option('pfmdz_removegooglefonts') == "1" ? "checked" : "";
$is_admin_no_important = get_option('pfmdz_admin_noimportant') == "1" ? "checked" : "";
$admin_customfamily = get_option('pfmdz_admincustomfamily');
$admin_customweight = get_option('pfmdz_admincustomweight');
$front_customfamily12 = get_option('pfmdz_frontcustomfamily12');
$front_customweight12 = get_option('pfmdz_frontcustomweight12');
$front_customfamily22 = get_option('pfmdz_frontcustomfamily22');
$front_customweight22 = get_option('pfmdz_frontcustomweight22');
$front_customfamily32 = get_option('pfmdz_frontcustomfamily32');
$front_customweight32 = get_option('pfmdz_frontcustomweight32');

$use_admin_customfonts = get_option('pfmdz_useadmincustomfonts') == "1" ? "checked" : "";
$admin_customfont1 = get_option("pfmdz_admincustomfont1");
$admin_customfont2 = get_option("pfmdz_admincustomfont2");
$current_font = get_option('pfmdz_currentFont');
$current_font = explode("/", $current_font);
/*get the front fonts*/
$current_front_general = get_option('pfmdz_general_frontfont');
$current_front_general = explode("/", $current_front_general);
$current_font_header = get_option('pfmdz_headers_frontfont');
$current_font_header = explode("/", $current_font_header);
$current_font_text = get_option('pfmdz_text_frontfont');
$current_font_text = explode("/", $current_font_text);

$use_front_customfonts1 = get_option('pfmdz_usefrontcustomfonts1') == "1" ? "checked" : "";
$front_customfont11 = get_option("pfmdz_frontcustomfont11");
$front_customfont12 = get_option("pfmdz_frontcustomfont12");

$use_front_customfonts2 = get_option('pfmdz_usefrontcustomfonts2') == "1" ? "checked" : "";
$front_customfont21 = get_option("pfmdz_frontcustomfont21");
$front_customfont22 = get_option("pfmdz_frontcustomfont22");

$use_front_customfonts3 = get_option('pfmdz_usefrontcustomfonts3') == "1" ? "checked" : "";
$front_customfont31 = get_option("pfmdz_frontcustomfont31");
$front_customfont32 = get_option("pfmdz_frontcustomfont32");
?>

<?php //JS global-Functions ?>
<script>
function mdzopensecandmove(id) {

    var all_fieldsets = document.querySelectorAll(".persianfont-con fieldset");
    for(var z = 0; z < all_fieldsets.length; z++){

        let tmp_fieldset = all_fieldsets[z];
        var id_to_open = tmp_fieldset.getAttribute("id-to-open");
        if(id_to_open == id){

            let tmp_topoffset = tmp_fieldset.offsetTop;
            tmp_fieldset.setAttribute("closed", "no");
            

            setTimeout(function(){
                window.scrollTo(0, tmp_topoffset);
            },100);
            
        }
    }
}
</script>

<?php /*HTML Begins!*/ ?>
<div class="persianfont-con <?php if ($is_nightmode == 'on'){echo esc_attr('nightmodeon');} ?>" rtl="<?php echo is_rtl(); ?>">

<div onclick="hide_lightboxbg(this)" class="lightbox-bg"></div>

<div id="commercial1" class="commercials-con">
    <a href="https://www.rtl-theme.com/auto-registration-plugin/" target="_blank"><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/commercials/wooautor-cover.webp'); ?>" style="width: 64%;border-radius: 75px;"></a>
    <div style="margin: 20px auto;"><a href="https://www.rtl-theme.com/author/delbarbash/products/" target="_blank"><strong><?php echo __('View other plugins', 'pfmdz') ?></strong></a></div>
    <span class="dashicons dashicons-arrow-up"></span>
</div><!--Endof Commercial-->

<div class="pfmdz-notice" style="display: none;">
    <p>اطلاعیه: از ورژن جدید این افزونه (ورژن 1.5) کلیه فونت های غیر رایگان به درخواست سایت فونت ایران و برای رعایت حقوق و دست رنج طراحان عزیز فونت های پارسی، حذف شده اند و استفاده از ورژن های قبلی این افزونه و فونت های لایسنس دار ایرانی متوجه خود شما می باشد و در صورت ایجاد مشکل برای سایت شما مسئولیت این امر بر عهده شماست.<br>بنده به عنوان طراح این افزونه تمامی سعی و کوششم را در راستای اضافه کردن فونت های رایگان میکنم و شرایط را برای خرید و استفاده از فونت های غیر رایگان برای شما کاربران عزیز در بهترین حالت ممکن فراهم میکنم.</p>
    <strong>برای خرید لایسنس فونت ها به سایت فونت ایران مراجعه فرمایید</strong>
    <div>
        <span style="background: #8ff565;box-shadow: 0px 0px 8px 10px #8ff565;"><a href="https://fontiran.com/?ref=533" target="_blank">خرید و حمایت از طراحان فونت</a></span>
        <span style="background-color: #b5aaff;box-shadow: 0px 0px 8px 10px #b5aaff;cursor: pointer;" id="dontshowmore1">اوکی متوجه شدم، فعلا نمایش نده</span>
    </div>
</div><!--Endof Fonts-Notice-->

<div style="display: flex;flex-direction: row;"><h2 style="margin: 55px 5px;"><?php echo __('Welcome to Settings-Page', 'pfmdz') ?></h2><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/pfmdz-logo.svg'); ?>" style="width: 95px;margin-right: 35px;" class="pfmdzlogo" alt="فونت فارسی وردپرس"></div>

<div style="display: flex; justify-content: space-between;">
<div class="nightmode-btn" style="width: 125px;margin-top: 10px;margin-right: 35px;"><span><img class="nightmode-btn-img1" src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/daytinight.svg'); ?>" width="99px"></span><span class="nightbtn-arrow"><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/arrow-btn.svg'); ?>" width="23px" class="nightmode-btn-img2"></span></div>

<div style="display: flex;align-items: center;">

    <a href="https://fontiran.com/?ref=533" target="_blank"><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/fontiran-logo.jpg'); ?>" style="width: 34px;margin-top: 10px;margin-left: 5px;border-radius: 6px;" class="social-logo" alt="فونت ایران"></a>

    <a href="https://www.aparat.com/M.Design" target="_blank"><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/aparat-logo-svg.svg'); ?>" style="width: 34px;margin-top: 10px;margin-left: 5px;" class="social-logo" alt="فونت فارسی وردپرس"></a>

</div>

</div>


<div class="warning-text-con"><h4><?php echo __('Note: After each update of the plugin, refer to the plugin settings page (this page) and click the save settings button', 'pfmdz') ?></h4></div>

<div class="warning-text-con"><h4><?php echo __('Note: If you have a speed problem in the admin area, remove Google Fonts API requests from the Google Fonts added settings section on this page.', 'pfmdz') ?></h4></div>

<div style="text-align: center;margin-bottom: 35px;"><span class="pfmdz-btn" onclick="mdzopensecandmove('google')"><?php echo __('Remove Admin Google-Fonts', 'pfmdz'); ?><span style="margin: 0px 3px;" class="dashicons dashicons-google"></span></span></div>

<div>
    <form action="" method="POST">
        <fieldset style="display: block !important;">
            <legend><?php echo __('Plugin Activity', 'pfmdz'); ?></legend>
            <p class="justflex">
            <input type="checkbox" id="active-pfmdz" name="active-pfmdz"<?php echo esc_attr($is_active); ?>>
            <label for="active-pfmdz"><?php echo __('Add fonts to Admin: ', 'pfmdz'); ?><sub><?php echo __('Tick ​​and save this option to activate the plugin', 'pfmdz') ?></sub></label>

            <p class='helptip'><span></span><?php echo __('If you do not see the desired changes in the WordPress admin area, be sure to press the ctrl + F5 keys to clear your browser cache.', 'pfmdz') ?></p>
            </p>
            
            <p class="justflex">
            <input type="checkbox" id="no-admin-important-pfmdz" name="no-admin-important-pfmdz"<?php echo esc_attr($is_admin_no_important); ?>>
            <label for="no-admin-important-pfmdz"><?php echo __('Admin-Fonts without CSS !important: ', 'pfmdz') ?><sub><?php echo __('Without this feature, the font may not be applied correctly', 'pfmdz') ?></sub></label>

            <p class='helptip'><span></span><?php echo __('Without the !important CSS identifier, the admin fonts may not be applied to all the admin-area elements of your site, which you have to modify with additional CSS codes.', 'pfmdz') ?></p>
            </p> 
            
            <hr>

            <p class="justflex">
            <input type="checkbox" id="active-pfmdz-front" name="active-pfmdz-front"<?php echo esc_attr($is_front_fonts); ?>>
            <label for="active-pfmdz-front"><?php echo __('Add Fonts to Fron-End: ', 'pfmdz') ?><sub><?php echo __('It can be useful if your Wordpress-Theme does not support Persian fonts', 'pfmdz') ?></sub></label>

            <p class='helptip'><span></span><?php echo __('By activating this option, a CSS file with the ID pfmdz-frontfonts-css will be added to the front part of your site globally.', 'pfmdz') ?></p>

            <p class='helptip'><span></span><?php echo __('If you dont see the desired changes on the front-end of the site, be sure to press the ctrl + F5 keys to clear your browsers cache.', 'pfmdz') ?></p>
            </p> 
        </fieldset>

        <fieldset style="display: block !important;" class="front-fonts-fieldsethidden front-fonts-fieldset" id="front-fonts">
            <legend><?php echo __('Front Fonts', 'pfmdz') ?></legend>

            <p style="margin-bottom: 77px;" class="justflex">
            <input type="checkbox" id="frontfonts-important" name="frontfonts-important"<?php echo esc_attr($is_frontimportant); ?>>
            <label for="frontfonts-important"><?php echo __('Front-Fonts without CSS !important: ', 'pfmdz') ?><sub><?php echo __('This option applies the fonts 100%, but it can cause some display-bugs in the display of some icons.', 'pfmdz') ?></sub></label>
            </p> 
            
            <p class='helptip'><span></span><?php echo __('Support font designers by buying the legal license of Persian fonts from fontiran.com', 'pfmdz') ?></p>
            
            <div style="margin-bottom: 35px;">
            <a style="color: #ffffff;" href="https://fontiran.com/?ref=533" target="_blank">
            <div class="pfmdz-btn"><?php echo __('Buy a legal font license', 'pfmdz') ?><span style="margin: 0px 3px;" class="dashicons dashicons-awards"></span></div>
            </a>
            </div>

            <hr>

            <p>

            <div class="custom-select div-inline pfmdz-front-fontspicker-con" id="div-front-selfplugin-fontpicker1">
            <label for="general-front-font"><?php echo __('General Font: ', 'pfmdz') ?></label>
            <select id="general-front-font" name="general-front-font" style="flex: 100%; margin: -7px 10px">
            <option value="Arial/Arial/400"><?php echo __('Choose a font...', 'pfmdz') ?></option>
            <?php $this->loadFonts(); ?>
            </select>
            <sub><?php echo __('Save after choosing the desired font', 'pfmdz') ?></sub>
            </div>

            <p class="justflex" style="margin-top: 55px;">
            <input class="pfmdz-checkbox-disabler" type="checkbox" id="pfmdz-front-usecustomfonts1" name="pfmdz-front-usecustomfonts1"<?php echo esc_attr($use_front_customfonts1); ?> targetid-to-disable="div-front-selfplugin-fontpicker1" targetid-to-show="pfmdz-front-customfonts-hiddendiv1">
            <label for="pfmdz-front-usecustomfonts1"><?php echo __('Use your Custom-Font: ', 'pfmdz') ?><sub><?php echo __('Upload the fonts to the WordPress media or somewhere on your Host file-manager', 'pfmdz') ?></sub></label>

            <div class="pfmdz-custom-fonts-con front-fonts-fieldsethidden" id="pfmdz-front-customfonts-hiddendiv1">

                <label for="pfmdz-front-customfont11"><?php echo __('URL of woff font-file (Required)', 'pfmdz') ?></label><input style="direction: ltr;" id="pfmdz-front-customfont11" name="pfmdz-front-customfont11" type="text" value="<?php echo esc_attr($front_customfont11); ?>">

                <label for="pfmdz-front-customfont12"><?php echo __('URL of woff2 font-file (Required)', 'pfmdz') ?></label><input style="direction: ltr;" id="pfmdz-front-customfont12" name="pfmdz-front-customfont12" type="text" value="<?php echo esc_attr($front_customfont12); ?>">

                <label for="pfmdz-front-customfamily12"><?php echo __('Font-Family (Optional)', 'pfmdz') ?></label><input style="direction: ltr;" id="pfmdz-front-customfamily12" name="pfmdz-front-customfamily12" type="text" value="<?php echo esc_attr($front_customfamily12); ?>">

                <label for="pfmdz-front-customweight12"><?php echo __('Font-Weight (Optional)', 'pfmdz') ?></label><input style="direction: ltr;" id="pfmdz-front-customweight12" name="pfmdz-front-customweight12" type="number" min="100" max="900" value="<?php echo esc_attr($front_customweight12); ?>" placeholder="<?php echo __('Number between 100 & 900', 'pfmdz') ?>">

                <span class="pfmdz-btn" onclick="mdzopensecandmove('learn')"><?php echo __('Learn how to use the purchased font', 'pfmdz') ?><span style="margin: 0px 3px;" class="dashicons dashicons-editor-help"></span></span>
                
                <p class='helptip'><span></span><?php echo __('To buy a legal font license, please refer to the fontiran.com site', 'pfmdz') ?></p>
                <p style="margin-top: 0px;margin-bottom: 0px;" class='helptip'><span></span><?php echo __('If you are not allowed to upload fonts in WordPress media, enable the option to allow font uploads in WordPress media from the advanced section of this page.', 'pfmdz') ?></p>

                <span class="pfmdz-btn" onclick="mdzopensecandmove('advanced')"><?php echo __('Visit Advanced Section', 'pfmdz') ?><span style="margin: 0px 3px;" class="dashicons dashicons-editor-help"></span></span>
            </div>

            </p>

            <p class='helptip'><span></span><?php echo __('This option adds the desired font to all front-end tags', 'pfmdz') ?></p>

            <hr style="margin-bottom: 35px;">

            <div class="custom-select div-inline pfmdz-front-fontspicker-con" id="div-front-selfplugin-fontpicker2">
            <label for="headers-front-font"><?php echo __('Heading Font: ', 'pfmdz') ?></label>
            <select id="headers-front-font" name="headers-front-font" style="flex: 100%; margin: -7px 10px">
            <option value="Arial/Arial/400"><?php echo __('Choose a font...', 'pfmdz') ?></option>
            <?php $this->loadFonts(); ?>
            </select>
            <sub><?php echo __('Save after choosing the desired font', 'pfmdz') ?></sub>
            </div>

            <p class="justflex" style="margin-top: 55px;">
            <input class="pfmdz-checkbox-disabler" type="checkbox" id="pfmdz-front-usecustomfonts2" name="pfmdz-front-usecustomfonts2"<?php echo esc_attr($use_front_customfonts2); ?> targetid-to-disable="div-front-selfplugin-fontpicker2" targetid-to-show="pfmdz-front-customfonts-hiddendiv2">
            <label for="pfmdz-front-usecustomfonts2"><?php echo __('Use your Custom-Font: ', 'pfmdz') ?><sub><?php echo __('Upload the fonts to the WordPress media or somewhere on your Host file-manager', 'pfmdz') ?></sub></label>

            <div class="pfmdz-custom-fonts-con front-fonts-fieldsethidden" id="pfmdz-front-customfonts-hiddendiv2">

                <label for="pfmdz-front-customfont21"><?php echo __('URL of woff font-file (Required)', 'pfmdz') ?></label><input style="direction: ltr;" id="pfmdz-front-customfont21" name="pfmdz-front-customfont21" type="text" value="<?php echo esc_attr($front_customfont21); ?>">

                <label for="pfmdz-front-customfont22"><?php echo __('URL of woff2 font-file (Required)', 'pfmdz') ?></label><input style="direction: ltr;" id="pfmdz-front-customfont22" name="pfmdz-front-customfont22" type="text" value="<?php echo esc_attr($front_customfont22); ?>">

                <label for="pfmdz-front-customfamily22"><?php echo __('Font-Family (Optional)', 'pfmdz') ?></label><input style="direction: ltr;" id="pfmdz-front-customfamily22" name="pfmdz-front-customfamily22" type="text" value="<?php echo esc_attr($front_customfamily22); ?>">

                <label for="pfmdz-front-customweight22"><?php echo __('Font-Weight (Optional)', 'pfmdz') ?></label><input style="direction: ltr;" id="pfmdz-front-customweight22" name="pfmdz-front-customweight22" type="number" min="100" max="900" value="<?php echo esc_attr($front_customweight22); ?>" placeholder="<?php echo __('Number between 100 & 900', 'pfmdz') ?>">

                <span class="pfmdz-btn" onclick="mdzopensecandmove('learn')"><?php echo __('Learn how to use the purchased font', 'pfmdz') ?><span style="margin: 0px 3px;" class="dashicons dashicons-editor-help"></span></span>
                
                <p class='helptip'><span></span><?php echo __('To buy a legal font license, please refer to the fontiran.com site', 'pfmdz') ?></p>
                <p style="margin-top: 0px;margin-bottom: 0px;" class='helptip'><span></span><?php echo __('If you are not allowed to upload fonts in WordPress media, enable the option to allow font uploads in WordPress media from the advanced section of this page.', 'pfmdz') ?></p>

                <span class="pfmdz-btn" onclick="mdzopensecandmove('advanced')"><?php echo __('Visit Advanced Section', 'pfmdz') ?><span style="margin: 0px 3px;" class="dashicons dashicons-editor-help"></span></span>
            </div>

            </p>

            <p class='helptip'><span></span><?php echo __('Adds font to H1,H2,H3,H4,H5,H6 Tags', 'pfmdz') ?></p>

            <hr style="margin-bottom: 35px;">

            <div class="custom-select div-inline pfmdz-front-fontspicker-con" id="div-front-selfplugin-fontpicker3">
            <label for="text-front-font"><?php echo __('Text Font: ', 'pfmdz') ?></label>
            <select id="text-front-font" name="text-front-font" style="flex: 100%; margin: -7px 10px">
            <option value="Arial/Arial/400"><?php echo __('Choose a font..', 'pfmdz') ?></option>
            <?php $this->loadFonts(); ?>
            </select>
            <sub><?php echo __('Save after choosing the desired font', 'pfmdz') ?></sub>
            </div>

            <p class="justflex" style="margin-top: 55px;">
            <input class="pfmdz-checkbox-disabler" type="checkbox" id="pfmdz-front-usecustomfonts3" name="pfmdz-front-usecustomfonts3"<?php echo esc_attr($use_front_customfonts3); ?> targetid-to-disable="div-front-selfplugin-fontpicker3" targetid-to-show="pfmdz-front-customfonts-hiddendiv3">
            <label for="pfmdz-front-usecustomfonts3"><?php echo __('Use your Custom-Font: ', 'pfmdz') ?><sub><?php echo __('Upload the fonts to the WordPress media or somewhere on your Host file-manager', 'pfmdz') ?></sub></label>

            <div class="pfmdz-custom-fonts-con front-fonts-fieldsethidden" id="pfmdz-front-customfonts-hiddendiv3">

                <label for="pfmdz-front-customfont31"><?php echo __('URL of woff font-file (Required)', 'pfmdz') ?></label><input style="direction: ltr;" id="pfmdz-front-customfont31" name="pfmdz-front-customfont31" type="text" value="<?php echo esc_attr($front_customfont31); ?>">

                <label for="pfmdz-front-customfont32"><?php echo __('URL of woff2 font-file (Required)', 'pfmdz') ?></label><input style="direction: ltr;" id="pfmdz-front-customfont32" name="pfmdz-front-customfont32" type="text" value="<?php echo esc_attr($front_customfont32); ?>">

                <label for="pfmdz-front-customfamily32"><?php echo __('Font-Family (Optional)', 'pfmdz') ?></label><input style="direction: ltr;" id="pfmdz-front-customfamily32" name="pfmdz-front-customfamily32" type="text" value="<?php echo esc_attr($front_customfamily32); ?>">

                <label for="pfmdz-front-customweight32"><?php echo __('Font-Weight (Optional)', 'pfmdz') ?></label><input style="direction: ltr;" id="pfmdz-front-customweight32" name="pfmdz-front-customweight32" type="number" min="100" max="900" value="<?php echo esc_attr($front_customweight32); ?>" placeholder="<?php echo __('Number between 100 & 900', 'pfmdz') ?>">

                <span class="pfmdz-btn" onclick="mdzopensecandmove('learn')"><?php echo __('Learn how to use the purchased font', 'pfmdz') ?><span style="margin: 0px 3px;" class="dashicons dashicons-editor-help"></span></span>
                
                <p class='helptip'><span></span><?php echo __('To buy a legal font license, please refer to the fontiran.com site', 'pfmdz') ?></p>
                <p style="margin-top: 0px;margin-bottom: 0px;" class='helptip'><span></span><?php echo __('If you are not allowed to upload fonts in WordPress media, enable the option to allow font uploads in WordPress media from the advanced section of this page.', 'pfmdz') ?></p>

                <span class="pfmdz-btn" onclick="mdzopensecandmove('advanced')"><?php echo __('Visit Advanced Section', 'pfmdz') ?><span style="margin: 0px 3px;" class="dashicons dashicons-editor-help"></span></span>
            </div>

            </p>

            <p class='helptip'><span></span><?php echo __('Adds font to p,a,span,div,etc Tags', 'pfmdz') ?></p>

            <hr>

            <p class="justflex">
            <input type="checkbox" id="customfrontcss-activate-pfmdz" name="customfrontcss-activate-pfmdz" <?php echo esc_attr($is_frontcustomcss); ?>>
            <label for="customfrontcss-activate-pfmdz"><?php echo __('Activate Front Custom-CSS: ', 'pfmdz') ?><sub><?php echo __('Activate so that all your additional css-codes are applied to all pages of your front-end side', 'pfmdz') ?></sub></label>
            </p>
            <div class="css-code-con"><textarea name="csscode-pfmdz-front" id="csscode-pfmdz-front"><?php echo get_option('pfmdz_user_customfrontcss'); ?></textarea></div>

            <div class="div-inline" style="justify-content: center;"> 
            <div class="nightmode-btn" style="margin-top: 15px;"><span><img class="nightmode-btn-img1" src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/daytinight.svg'); ?>" width="99px"></span><span class="nightbtn-arrow"><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/arrow-btn.svg'); ?>" width="23px" class="nightmode-btn-img2"></span></div></div>
            </p>

            <p class='helptip'><span></span><?php echo __('If you do not see the desired changes, be sure to press the ctrl + F5 keys to clear your browser cache.', 'pfmdz') ?></p>

        </fieldset>

        <fieldset style="display: block !important;">
            <legend><?php echo __('Admin Fonts', 'pfmdz') ?></legend>
            <p>
            <div class="custom-select div-inline" id="div-admin-selfplugin-fontpicker">
            <label for="selectFont-pfmdz"><?php echo __('Choose a Font: ', 'pfmdz') ?></label>
            <select id="selectFont-pfmdz" name="selectFont-pfmdz" style="flex: 100%; margin: -7px 10px">
            <option value="Arial/Arial/400"><?php echo __('Choose a font...', 'pfmdz') ?></option>
            <?php $this->loadFonts(); ?>
            </select>

            <sub><?php echo __('Save after choosing the desired font', 'pfmdz') ?></sub>
            </div>

            <div style="margin-top: 46px;" class="div-inline testText-con"><H4 style="margin-top: 45px;font-size: 20px;margin-left: 100px;"><?php echo __('Test Text', 'pfmdz') ?></H4><p style="max-width: 310px;overflow-y: auto;max-height: 200px;" id="pfmdz-testtext" class="testtext"><?php echo __('Test text only for the internal fonts of the plugin 7/6/2023', 'pfmdz') ?></p><span style="margin-top: 45px;margin-right: 25px;" class='pfmdz-loader'></span><span style="margin-top: 43px;" class="pfmdz-btn"><?php echo __('Change Text', 'pfmdz') ?></span></div>

            <p class='helptip'><span></span><?php echo __('If you dont see the font changes, be sure to press the ctrl + F5 keys to clear your browsers cache.', 'pfmdz') ?></p>
            <p class='helptip'><span></span><?php echo __('Our recommended free fonts is Vazir & Shabnam-bold fonts', 'pfmdz') ?></p>
            </p>  

            <hr>

            <p class="justflex" style="margin-top: 55px;">
            <input class="pfmdz-checkbox-disabler" type="checkbox" id="pfmdz-admin-usecustomfonts" name="pfmdz-admin-usecustomfonts"<?php echo esc_attr($use_admin_customfonts); ?> targetid-to-disable="div-admin-selfplugin-fontpicker" targetid-to-show="pfmdz-admin-customfonts-hiddendiv">
            <label for="pfmdz-admin-usecustomfonts"><?php echo __('Use your Custom-Font: ', 'pfmdz') ?><sub><?php echo __('Upload the fonts to the WordPress media or somewhere on your Host file-manager', 'pfmdz') ?></sub></label>

            <div class="pfmdz-custom-fonts-con front-fonts-fieldsethidden" id="pfmdz-admin-customfonts-hiddendiv">

                <label for="pfmdz-admin-customfont1"><?php echo __('URL of woff font-file (Required)', 'pfmdz') ?></label><input style="direction: ltr;" id="pfmdz-admin-customfont1" name="pfmdz-admin-customfont1" type="text" value="<?php echo esc_attr($admin_customfont1); ?>">

                <label for="pfmdz-admin-customfont2"><?php echo __('URL of woff2 font-file (Required)', 'pfmdz') ?></label><input style="direction: ltr;" id="pfmdz-admin-customfont2" name="pfmdz-admin-customfont2" type="text" value="<?php echo esc_attr($admin_customfont2); ?>">

                <label for="pfmdz-admin-customfamily"><?php echo __('Font-Family (Optional)', 'pfmdz') ?></label><input style="direction: ltr;" id="pfmdz-admin-customfamily" name="pfmdz-admin-customfamily" type="text" value="<?php echo esc_attr($admin_customfamily); ?>">

                <label for="pfmdz-admin-customweight"><?php echo __('Font-Weight (Optional)', 'pfmdz') ?></label><input style="direction: ltr;" id="pfmdz-admin-customweight" name="pfmdz-admin-customweight" type="number" min="100" max="900" value="<?php echo esc_attr($admin_customweight); ?>" placeholder="<?php echo __('Number between 100 & 900', 'pfmdz') ?>">

                <span class="pfmdz-btn" onclick="mdzopensecandmove('learn')"><?php echo __('Learn how to use the purchased font', 'pfmdz') ?><span style="margin: 0px 3px;" class="dashicons dashicons-editor-help"></span></span>
                
                <p class='helptip'><span></span><?php echo __('To buy a legal font license, please refer to the fontiran.com site', 'pfmdz') ?></p>
                <p style="margin-top: 0px;margin-bottom: 0px;" class='helptip'><span></span><?php echo __('If you are not allowed to upload fonts in WordPress media, enable the option to allow font uploads in WordPress media from the advanced section of this page.', 'pfmdz') ?></p>

                <span class="pfmdz-btn" onclick="mdzopensecandmove('advanced')"><?php echo __('Visit Advanced Section', 'pfmdz') ?><span style="margin: 0px 3px;" class="dashicons dashicons-editor-help"></span></span>
            </div>

            </p>

            <p class='helptip'><span></span><?php echo __('If you dont see the font changes, be sure to press the ctrl + F5 keys to clear your browsers cache.', 'pfmdz') ?></p>

            <p class='helptip'><span></span><?php echo __('Support font designers by buying the legal license of Persian fonts from fontiran.com', 'pfmdz') ?></p>
            
            <div style="margin-bottom: 35px;">
                <a style="color: #ffffff;" href="https://fontiran.com/?ref=533" target="_blank">
                <div class="pfmdz-btn"><?php echo __('Buy a legal font license', 'pfmdz') ?><span style="margin: 0px 3px;" class="dashicons dashicons-awards"></span></div>
                </a>
                
            </div>

            </p>   
        </fieldset>

        <fieldset style="display: block !important;" closed="yes" id-to-open="google" id="google">
            <legend><?php echo __('Google Fonts', 'pfmdz') ?></legend>

            <div class="acordion2-btn"><span class="dashicons dashicons-plus" title="<?php echo __('Open Section', 'pfmdz') ?>"></span></div>
            <div class="acordion2">

            <div class="justflex">
            <input type="checkbox" id="removeelemfonts-pfmdz" name="removeelemfonts-pfmdz" <?php echo esc_attr($remove_elem_fonts); ?>>
            <label for="removeelemfonts-pfmdz"><?php echo __('Disable Elementor Default Fonts: ', 'pfmdz') ?><sub><?php echo __('Disables Elementors default fonts in general from the front-end of your site', 'pfmdz') ?></sub></label>
            </div>
            <p class='helptip'><span></span><?php echo __('If you do not use Elementor page builder, do not activate this option', 'pfmdz') ?></p>
            <p class='helptip'><span></span><?php echo __('including the Google fonts that Elementor loads on the front of your site', 'pfmdz') ?></p>

            <hr>

            <div class="justflex options-con">
            <input type="checkbox" id="removeallgooglefonts-pfmdz" name="removeallgooglefonts-pfmdz" <?php echo esc_attr($remove_all_google_fonts); ?>>
            <label for="removeallgooglefonts-pfmdz"><?php echo __('Remove all google-fonts from admin-area: ', 'pfmdz') ?><sub><?php echo __('Detects and then removes CSS style requests to Google servers', 'pfmdz') ?></sub></label>
            </div>

            <p class='helptip'><span></span><?php echo __('Activate the option below to find and save Google fonts well inside your database', 'pfmdz') ?></p>

            <div class="justflex">
            <input type="checkbox" id="catchgooglefonts-pfmdz" name="catchgooglefonts-pfmdz">
            <label for="catchgooglefonts-pfmdz"><?php echo __('Find & Save google-fonts: ', 'pfmdz') ?><sub><?php echo __('If this option is active, after saving the settings, a list of Google fonts available in the admin of your site will be found and saved in the database.', 'pfmdz') ?></sub></label>
            </div>

            <p class='helptip'><span></span><?php echo __('If this is the first time you are using this option, be sure to activate the option (find & save Google fonts) so that the plugin automatically detects Google fonts in the admin side.', 'pfmdz') ?></p>
            <p class='helptip'><span></span><?php echo __('It can be very useful if you are having speed issues with your WordPress dashboard', 'pfmdz') ?></p> 

            <p class='helptip'><span></span><?php echo __('If you still have a speed problem in the WordPress dashboard, we suggest you use the help from the section below', 'pfmdz') ?></p>

            <div style="text-align: center;margin-bottom: 45px;margin-top: 33px;">
            <span class="acordion"><span style="margin-left: 10px;transform: translateY(-2px);" class="dashicons dashicons-smiley"></span><?php echo __('More Help', 'pfmdz') ?></span>
            </div>

            <div class="acordion-panel"><div class="helptexts-con">

            <div style="padding: 10px 30px;" class="helptexts-con">
                <?php include_once persianfontsmdez_PATH . "templates".DIRECTORY_SEPARATOR ."html".DIRECTORY_SEPARATOR ."help4html.php"; ?>
            </div>

            </div>
            </div><!-- Endof Learn text -->

            </div>
        </fieldset>
        
        <fieldset style="display: block !important;" closed="yes">
            <legend><?php echo __('Styles', 'pfmdz') ?></legend>

            <div class="acordion2-btn"><span class="dashicons dashicons-plus" title="<?php echo __('Open Section', 'pfmdz') ?>"></span></div>
            <div class="acordion2">

            <div class="justflex options-con">
            <input type="checkbox" id="italic-pfmdz" name="italic-pfmdz" <?php echo esc_attr($is_italic); ?>>
            <label for="italic-pfmdz"><?php echo __('Italic Fonts: ', 'pfmdz') ?><sub><?php echo __('Applies italic style to the current admin-font', 'pfmdz') ?></sub></label><div onclick="exp_imgs_lightbox(this)"><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/italic-font-example.webp'); ?>" style=" width: 85px;margin-right: 45px;" class="exampleimgs"  alt="فونت فارسی وردپرس"></div>
            </div>

            <div class="justflex options-con">
            <input type="checkbox" id="compactfonts-pfmdz" name="compactfonts-pfmdz" <?php echo esc_attr($is_compact); ?>>
            <label for="compactfonts-pfmdz"><?php echo __('Compact Fonts: ', 'pfmdz') ?><sub><?php echo __('Compact style for admin-fonts', 'pfmdz') ?></sub></label><div onclick="exp_imgs_lightbox(this)"><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/compressed-font-example.webp'); ?>" style=" width: 85px;margin-right: 45px;" class="exampleimgs"  alt="فونت فارسی وردپرس"></div>
            </div>  

            </div>
        </fieldset>

        <fieldset style="display: block !important;" closed="yes" id-to-open="advanced" id="advanced">
            <legend><?php echo __('Advanced', 'pfmdz') ?></legend>

            <div class="acordion2-btn"><span class="dashicons dashicons-plus" title="<?php echo __('Open Section', 'pfmdz') ?>"></span></div>
            <div class="acordion2">

            <div class="justflex options-con">
            <input type="checkbox" id="tinymceEffect-pfmdz" name="tinymceEffect-pfmdz" <?php echo esc_attr($is_tinymce); ?>>
            <label for="tinymceEffect-pfmdz"><?php echo __('Applying fonts to wp content editing boxes (tinymce): ', 'pfmdz') ?><sub><?php echo __('For example, the place to edit product descriptions or posts', 'pfmdz') ?></sub></label><div onclick="exp_imgs_lightbox(this)"><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/tinymce-font-example.webp'); ?>" style=" width: 85px;margin-right: 45px;" class="exampleimgs"  alt="فونت فارسی وردپرس"></div>
            </div> 
            
            <div class="justflex options-con">
            <input type="checkbox" id="loginfonts-pfmdz" name="loginfonts-pfmdz" <?php echo esc_attr($is_login); ?>>
            <label for="loginfonts-pfmdz"><?php echo __('Add fonts to wp-login page: ', 'pfmdz') ?><sub>wp-admin.php</sub></label><div onclick="exp_imgs_lightbox(this)"><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/login-fonts-example.webp'); ?>" style=" width: 85px;margin-right: 45px;" class="exampleimgs"  alt="فونت فارسی وردپرس"></div>
            </div>
            
            <div class="justflex options-con">
            <input type="checkbox" id="elementor-pfmdz" name="elementor-pfmdz" <?php echo esc_attr($is_elementor); ?>>
            <label for="elementor-pfmdz"><?php echo __('Applying fonts to the Elementor page editor: ', 'pfmdz') ?><sub>Elementor integrator</sub></label><div onclick="exp_imgs_lightbox(this)"><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/elementor-font-example.webp'); ?>" style=" width: 85px;margin-right: 45px;" class="exampleimgs"  alt="فونت فارسی وردپرس"></div>
            </div> 

            <p class='helptip'><span></span><?php echo __('If you are not using Elementor page builder, do not enable this option', 'pfmdz') ?></p>

            <hr>

            <div class="justflex options-con">
            <input type="checkbox" id="pfmdz-allowfontsupload" name="pfmdz-allowfontsupload" <?php echo esc_attr($allow_fonts_upload); ?>>
            <label for="pfmdz-allowfontsupload"><?php echo __('Allow font uploads in WordPress media:', 'pfmdz') ?><sub><?php echo __('If you cant upload your fonts to the WordPress media section, enable this option', 'pfmdz') ?></sub></label><div onclick="exp_imgs_lightbox(this)"><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/allow-upload-fonts.webp'); ?>" style=" width: 85px;margin-right: 45px;" class="exampleimgs"  alt="فونت فارسی وردپرس"></div>
            </div>  

            <p class='helptip'><span></span><?php echo __('If you are unable to load fonts by activating this option, you should upload and use fonts directly from your Host file-manager.', 'pfmdz') ?></p>

            <p class="justflex">
            <label for="dontaddfont-pfmdz"><?php echo __('Do not apply font to these classes:', 'pfmdz') ?><sub><?php echo __('Seperate Each css-class with: ', 'pfmdz') ?><span style="color: red;margin: 0px 10px;font-weight:bolder;font-size: 20px;">|</span><?php echo __(' identifier', 'pfmdz') ?></sub></label>
            </p>  
            <div style="min-height: 110px;" class="css-code-con">
            <textarea style="min-height: 110px;" name="dontaddfont-pfmdz" id="dontaddfont-pfmdz" placeholder=".ab-item|.digits-icon|.elementor-icon"><?php echo get_option('pfmdz_nofonts_clases'); ?></textarea>
            </div>

            <div class="div-inline" style="justify-content: center;"> 
            <div class="nightmode-btn" style="margin-top: 15px;"><span><img class="nightmode-btn-img1" src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/daytinight.svg'); ?>" width="99px"></span><span class="nightbtn-arrow"><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/arrow-btn.svg'); ?>" width="23px" class="nightmode-btn-img2"></span></div></div>
            <p class='helptip'><span></span><?php echo __('If you do not see the desired changes, be sure to press the ctrl + F5 keys to clear your browser cache.', 'pfmdz') ?></p>

            </div>
        </fieldset>

        <fieldset style="display: block !important;" closed="yes">

            <legend><?php echo __('Admin Extra CSS', 'pfmdz') ?></legend>

            <div class="acordion2-btn"><span class="dashicons dashicons-plus" title="<?php echo __('Open Section', 'pfmdz') ?>"></span></div>
            <div class="acordion2">

            <p class="justflex">
            <input type="checkbox" id="customcss-activate-pfmdz" name="customcss-activate-pfmdz" <?php echo esc_attr($is_customcss); ?>>
            <label for="customcss-activate-pfmdz"><?php echo __('Activate Extra admin css: ', 'pfmdz') ?><sub><?php echo __('Activate so that all your additional css-codes are applied to all admin pages', 'pfmdz') ?></sub></label>
            </p>  
            <div class="css-code-con"><textarea name="csscode-pfmdz" id="csscode-pfmdz"><?php echo get_option('pfmdz_user_customcss'); ?></textarea></div>
            <div class="div-inline" style="justify-content: center;"> 
            <div class="nightmode-btn" style="margin-top: 15px;"><span><img class="nightmode-btn-img1" src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/daytinight.svg'); ?>" width="99px"></span><span class="nightbtn-arrow"><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/arrow-btn.svg'); ?>" width="23px" class="nightmode-btn-img2"></span></div></div>
            <p class='helptip'><span></span><?php echo __('If you do not see the desired changes, be sure to press the ctrl + F5 keys to clear your browser cache.', 'pfmdz') ?></p>

            <hr>

            <p class="justflex">
            <input type="checkbox" id="customcsselem-activate-pfmdz" name="customcsselem-activate-pfmdz" <?php echo esc_attr($is_elemcustomcss); ?>>
            <label for="customcsselem-activate-pfmdz"><?php echo __('Enable additional CSS codes for Elementor-editor: ', 'pfmdz') ?><sub><?php echo __('Activate to apply all your additional css-codes on the Elementor editor page', 'pfmdz') ?></sub></label>
            </p>
            <div class="css-code-con"><textarea name="csscodeelem-pfmdz" id="csscodeelem-pfmdz"><?php echo get_option('pfmdz_user_customelemcss'); ?></textarea></div>
            <div class="div-inline" style="justify-content: center;"> 
            <div class="nightmode-btn" style="margin-top: 15px;"><span><img class="nightmode-btn-img1" src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/daytinight.svg'); ?>" width="99px"></span><span class="nightbtn-arrow"><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/arrow-btn.svg'); ?>" width="23px" class="nightmode-btn-img2"></span></div></div>
            <p class='helptip'><span></span><?php echo __('To use this option, the option of applying fonts to the Elementor page builder must be enabled in the advanced section', 'pfmdz') ?></p>

            </div>
        </fieldset>

        <fieldset style="display: block !important;" closed="yes">
            <legend><?php echo __('Admin Themes', 'pfmdz') ?></legend>

            <div class="acordion2-btn"><span class="dashicons dashicons-plus" title="<?php echo __('Open Section', 'pfmdz') ?>"></span></div>
            <div class="acordion2">

            <div style="text-align: center;margin-bottom: 45px;margin-top: 33px;">
            <span class="acordion"><span style="margin-left: 10px;transform: translateY(-2px);" class="dashicons dashicons-admin-customizer"></span><?php echo __('Elementor Themes', 'pfmdz') ?></span>
            </div>

            <div class="acordion-panel"><div class="helptexts-con">

            <div style="padding: 10px 2px;" class="justflex">
            <div class="radios-con">
            <div class="radios-con2"><div class="themes-inputs-con"><input type="radio" id="pfmdz-elem-theme1" name="pfmdz-elem-theme" value="1" <?php if ($elem_theme == '1') { echo esc_attr('checked'); } ?>>
            <label for="pfmdz-elem-theme1"><?php echo __('Theme-1 (free)', 'pfmdz') ?></label></div><div onclick="exp_imgs_lightbox(this)"><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/elem-theme1-example.webp'); ?>" style=" width: 85px;margin-right: 45px;" class="exampleimgs"  alt="فونت فارسی وردپرس"></div>
            </div>

            <br>

            <div class="radios-con2"><div class="themes-inputs-con"><input type="radio" id="pfmdz-elem-theme2" name="pfmdz-elem-theme" value="2" <?php if ($elem_theme == '2') { echo esc_attr('checked'); } ?>>
            <label for="pfmdz-elem-theme2"><?php echo __('Theme-2 (not-free)', 'pfmdz') ?></label></div><div onclick="exp_imgs_lightbox(this)"><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/Netrox-elemTheme-example.webp'); ?>" style=" width: 85px;margin-right: 45px;" class="exampleimgs"  alt="فونت فارسی وردپرس"><div style="margin-top: 20px;"><span class="go-aparat"><a href="https://www.aparat.com/v/mE0gM" target="_blank"><?php echo __('Link to watch the video in Aparat.com', 'pfmdz') ?></a></span></div></div>
            </div>

            <br>

            <div class="radios-con2"><div class="themes-inputs-con"><input type="radio" id="pfmdz-elem-theme3" name="pfmdz-elem-theme" value="3" <?php if ($elem_theme == '3') { echo esc_attr('checked'); } ?>>
            <label for="pfmdz-elem-theme3"><?php echo __('Theme-3 (soon...)', 'pfmdz') ?></label></div><div onclick="exp_imgs_lightbox(this)"><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/elem-soontheme-example.webp'); ?>" style=" width: 85px;margin-right: 45px;" class="exampleimgs"  alt="فونت فارسی وردپرس"></div>
            </div>

            <br>

            <div class="radios-con2"><div class="themes-inputs-con"><input type="radio" id="pfmdz-elem-notheme" name="pfmdz-elem-theme" value="none">
            <label for="pfmdz-elem-notheme"><?php echo __('None (default-theme)', 'pfmdz') ?></label></div><div onclick="exp_imgs_lightbox(this)"><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/elem-notheme-example.webp'); ?>" style=" width: 85px;margin-right: 45px;" class="exampleimgs"  alt="فونت فارسی وردپرس"></div>
            </div>

            <p class='helptip' style="margin-right: 20px;"><span></span><?php echo __('To buy themes, contact my email: mdesign.fa@gmail.com', 'pfmdz') ?></p>
            <p class='helptip' style="margin-right: 20px;"><span></span><?php echo __('To use Elementor themes, the option to apply fonts to the Elementor page builder must be enabled in the advanced section', 'pfmdz') ?></p>
            </div> 
            </div>
            </div>
            </div><!-- End of Elementor Themes -->

            <div style="text-align: center;margin-bottom: 20px;">
            <span class="acordion"><span style="margin-left: 10px;transform: translateY(-2px);" class="dashicons dashicons-wordpress-alt"></span><?php echo __('Dashboard Themes', 'pfmdz') ?></span>
            </div>

            <div class="acordion-panel"><div class="helptexts-con">

            <div style="padding: 10px 2px;" class="justflex">
            <div class="radios-con">
            <div class="radios-con2"><div class="themes-inputs-con"><input type="radio" id="pfmdz-wp-theme1" name="pfmdz-wp-theme" value="1" <?php if ($wp_theme == '1') { echo esc_attr('checked'); } ?>>
            <label for="pfmdz-wp-theme1"><?php echo __('Theme-1 (not-free)', 'pfmdz') ?></label></div><div onclick="exp_imgs_lightbox(this)"><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/wp-theme-1.webp'); ?>" style=" width: 85px;margin-right: 45px;" class="exampleimgs"  alt="فونت فارسی وردپرس"></div>
            </div>

            <br>

            <div class="radios-con2"><div class="themes-inputs-con"><input type="radio" id="pfmdz-wp-notheme" name="pfmdz-wp-theme" value="none">
            <label for="pfmdz-wp-notheme"><?php echo __('None (dashboard default theme)', 'pfmdz') ?></label></div><div onclick="exp_imgs_lightbox(this)"><img src="<?php echo esc_url(persianfontsmdez_URL . 'admin/img/wp-default-theme.webp'); ?>" style=" width: 85px;margin-right: 45px;" class="exampleimgs"  alt="فونت فارسی وردپرس"></div>
            </div>

            <p class='helptip' style="margin-right: 20px;"><span></span><?php echo __('To buy themes, contact my email: mdesign.fa@gmail.com', 'pfmdz') ?></p>
            <p class='helptip' style="margin-right: 20px;"><span></span><?php echo __('To access more color themes for WordPress, we recommend installing the free `Admin Color Schemes` plugin from the repository', 'pfmdz') ?></p>
            </div> 
            </div>
            </div>
            </div><!-- End of WP Themes -->

            </div>
        </fieldset>

    <?php submit_button(__('Save Settings', 'pfmdz')); ?>
    </form>
    
    <fieldset style="display: block !important;" closed="yes" id-to-open="learn" id="learn">
            <legend><?php echo __('Tutorials', 'pfmdz') ?></legend>

            <div class="acordion2-btn"><span class="dashicons dashicons-plus" title="<?php echo __('Open Section', 'pfmdz') ?>"></span></div>
            <div class="acordion2">

            <div class="justflex options-con fontupload-con">

            <label style="margin-top: 6px;"><?php echo __('Download the internal fonts of the plugin:', 'pfmdz') ?></label>

            <a style="margin: 0px 18px;text-align: center;" href="<?php echo esc_url('https://mdezign.ir/pfmdz-fonts/pfmdz-fonts.zip'); ?>" target="_blank" class="button button-primary"><?php echo __('Download Now', 'pfmdz') ?></a>

            </div>

            <p class='helptip'><span></span><?php echo __('By pressing the download button, you will receive a compressed file containing all the free fonts of the plugin', 'pfmdz') ?></p>
            <p class='helptip'><span></span><?php echo __('You can use the ttf format of these fonts on your system', 'pfmdz') ?></p>
            <p class='helptip'><span></span><?php echo __('To support digital businesses and font designers, support us by respecting copyright', 'pfmdz') ?></p>
            <div style="text-align: center;margin-bottom: 20px;"><span class="acordion"><span style="margin-left: 10px;transform: translateY(-2px);" class="dashicons dashicons-sos"></span><?php echo __('how to upload fonts?', 'pfmdz') ?></span></div>
            <div class="acordion-panel"><div class="helptexts-con"><?php include_once persianfontsmdez_PATH . "templates".DIRECTORY_SEPARATOR ."html".DIRECTORY_SEPARATOR ."help1html.php"; ?></div></div>

            </div>
    </fieldset>


    <fieldset style="padding: 100px 25px;display: block !important;" closed="yes">
            <legend><?php echo __('About the Plugin', 'pfmdz') ?></legend>

            <div class="acordion2-btn"><span class="dashicons dashicons-plus" title="<?php echo __('Open Section', 'pfmdz') ?>"></span></div>
            <div class="acordion2">

            <div style="text-align: center;margin-top: 33px;margin-bottom: 22px;"><span class="acordion"><span style="margin-left: 10px;transform: translateY(-2px);" class="dashicons dashicons-format-gallery"></span><?php echo __('Gallery', 'pfmdz') ?></span></div>
            <div class="acordion-panel"><div class="helptexts-con"><?php include_once persianfontsmdez_PATH . "templates".DIRECTORY_SEPARATOR ."html".DIRECTORY_SEPARATOR ."help3html.php"; ?></div></div>

            <div style="text-align: center;margin-top: 33px;margin-bottom: 22px;"><span class="acordion"><span style="margin-left: 10px;transform: translateY(-2px);" class="dashicons dashicons-yes-alt"></span><?php echo __('Our Services', 'pfmdz') ?></span></div>
            <div class="acordion-panel"><div class="helptexts-con"><?php include_once persianfontsmdez_PATH . "templates".DIRECTORY_SEPARATOR ."html".DIRECTORY_SEPARATOR ."our-services.php"; ?></div></div>

            <div style="text-align: center;margin-top: 33px;margin-bottom: 22px;"><span class="acordion"><span style="margin-left: 10px;transform: translateY(-2px);" class="dashicons dashicons-editor-spellcheck"></span><?php echo __('Our recommended fonts', 'pfmdz') ?></span></div>
            <div class="acordion-panel"><div class="helptexts-con"><?php include_once persianfontsmdez_PATH . "templates".DIRECTORY_SEPARATOR ."html".DIRECTORY_SEPARATOR ."our-fav-fonts.php"; ?></div></div>

            <div style="text-align: center;margin-top: 33px;"><span class="acordion"><span style="margin-left: 10px;transform: translateY(-2px);" class="dashicons dashicons-plugins-checked"></span><?php echo __('Designed and developed by MDesign (MDZ)', 'pfmdz') ?></span></div>
            <div class="acordion-panel"><div class="helptexts-con"><?php include_once persianfontsmdez_PATH . "templates".DIRECTORY_SEPARATOR ."html".DIRECTORY_SEPARATOR ."help2html.php"; ?></div></div>

            </div>
    </fieldset>
    
</div>
</div>
<script type="text/javascript">
var active_font = '<?php if(isset($current_font[1])){echo $current_font[1];}else echo "";?>';
var cssFile_path = '<?php echo persianfontsmdez_URL . "libs/fonts/css/testtext.css"; ?>';
var is_front_fonts = '<?php echo $is_front_fonts; ?>';
var active_general_frontfont = '<?php if(isset($current_front_general[1])){echo $current_front_general[1];}else echo ""; ?>';
var active_header_frontfont = '<?php if(isset($current_font_header[1])){echo $current_font_header[1];}else echo "";?> ';
var active_text_frontfont = '<?php  if(isset($current_font_text[1])){echo $current_font_text[1];}else echo ""; ?>';
let trans1 = "<?php echo __('Active', 'pfmdz') ?>";
let trans2 = "<?php echo __('Close Section', 'pfmdz') ?>";
let trans3 = "<?php echo __('Open Section', 'pfmdz') ?>";
</script>
<script type="text/javascript" src="<?php echo esc_url(persianfontsmdez_URL.'admin/js/admin.js?ver='.PFMDZ_VERSION); ?>"></script>
<?php

/*SET This PAGE's OPTIONS AUTOLOAD FALSE*/

//$this->set_autoload_off('pfmdz_nightmode'); //due to use on the other PHP file

$this->set_autoload_off('pfmdz_currentFont');

$this->set_autoload_off('pfmdz_isactive');

//$this->set_autoload_off('pfmdz_istalic'); //due to use on the other PHP file

$this->set_autoload_off('pfmdz_tinymce');

$this->set_autoload_off('pfmdz_compact');

$this->set_autoload_off('pfmdz_loginfonts');

$this->set_autoload_off('pfmdz_elementorfonts');

$this->set_autoload_off('pfmdz_customcss');

$this->set_autoload_off('pfmdz_customelemcss');

$this->set_autoload_off('pfmdz_elemtheme');

$this->set_autoload_off('pfmdz_wptheme');

$this->set_autoload_off('pfmdz_allowfontsupload');

$this->set_autoload_off('pfmdz_useadmincustomfonts');

$this->set_autoload_off('pfmdz_admincustomfont1');

$this->set_autoload_off('pfmdz_admincustomfont2');

$this->set_autoload_off('pfmdz_frontfonts');

$this->set_autoload_off('pfmdz_general_frontfont');

$this->set_autoload_off('pfmdz_headers_frontfont');

$this->set_autoload_off('pfmdz_text_frontfont');

$this->set_autoload_off('pfmdz_isfrontcss');

$this->set_autoload_off('pfmdz_isfront_important');

$this->set_autoload_off('pfmdz_isfront_nocache');

$this->set_autoload_off('pfmdz_usefrontcustomfonts1');

$this->set_autoload_off('pfmdz_frontcustomfont11');

$this->set_autoload_off('pfmdz_frontcustomfont12');

$this->set_autoload_off('pfmdz_usefrontcustomfonts2');

$this->set_autoload_off('pfmdz_frontcustomfont21');

$this->set_autoload_off('pfmdz_frontcustomfont22');

$this->set_autoload_off('pfmdz_usefrontcustomfonts3');

$this->set_autoload_off('pfmdz_frontcustomfont31');

$this->set_autoload_off('pfmdz_frontcustomfont32');

$this->set_autoload_off('pfmdz_user_customcss');

$this->set_autoload_off('pfmdz_user_customelemcss');

$this->set_autoload_off('pfmdz_nofonts_clases');

$this->set_autoload_off('pfmdz_user_customfrontcss');

$this->set_autoload_off('pfmdz_admincss_file');

$this->set_autoload_off('pfmdz_frontcss_file');

$this->set_autoload_off('pfmdz_admin_noimportant');
//close the PHP tag to reduce the blank spaces ?>