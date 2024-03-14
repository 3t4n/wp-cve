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

/*Ajax for Empty data & Options Conf*/
if (!function_exists('pfmsz_emptyOptions_AjaxConf')){
    function pfmsz_emptyOptions_AjaxConf(){

        $tmp = sanitize_text_field($_POST['tmp']);
        $res = array();

        if ($tmp == '1'){
            $res[0] = delete_option('pfmdz_isactive');
            $res[1] = delete_option('pfmdz_empty_onDeactivate');
            $res[2] = delete_option('pfmdz_redirect_onActivate');
            $res[3] = delete_option('pfmdz_user_customcss');
            $res[4] = delete_option('pfmdz_currentFont');
            $res[5] = delete_option('pfmdz_elementorfonts');
            $res[6] = delete_option('pfmdz_nightmode');
            $res[7] = delete_option('pfmdz_istalic');
            $res[8] = delete_option('pfmdz_customcss');
            $res[9] = delete_option('pfmdz_compact');
            $res[10] = delete_option('pfmdz_loginfonts');
            $res[11] = delete_option('pfmdz_tinymce');
            $res[12] = delete_option('pfmdz_user_customelemcss');
            $res[13] = delete_option('pfmdz_customelemcss');
            $res[14] = delete_option('pfmdz_elemtheme');
            $res[15] = delete_option('pfmdz_frontfonts');
            $res[16] = delete_option('pfmdz_general_frontfont');
            $res[17] = delete_option('pfmdz_headers_frontfont');
            $res[18] = delete_option('pfmdz_text_frontfont');
            $res[19] = delete_option('pfmdz_isfrontcss');
            $res[20] = delete_option('pfmdz_user_customfrontcss');
            $res[21] = delete_option('pfmdz_isfront_important');
            $res[22] = delete_option('pfmdz_wptheme'); 
            $res[23] = delete_option('pfmdz_isfront_nocache'); 
            $res[24] = delete_option('pfmdz_useadmincustomfonts');
            $res[25] = delete_option('pfmdz_admincustomfont1');
            $res[26] = delete_option('pfmdz_admincustomfont2');
            $res[27] = delete_option('pfmdz_usefrontcustomfonts1'); 
            $res[28] = delete_option('pfmdz_frontcustomfont11');
            $res[29] = delete_option('pfmdz_frontcustomfont12');
            $res[30] = delete_option('pfmdz_usefrontcustomfonts2');
            $res[31] = delete_option('pfmdz_frontcustomfont21');
            $res[32] = delete_option('pfmdz_frontcustomfont22');
            $res[33] = delete_option('pfmdz_usefrontcustomfonts3');
            $res[34] = delete_option('pfmdz_frontcustomfont31');
            $res[35] = delete_option('pfmdz_frontcustomfont32');
            $res[36] = delete_option('pfmdz_plugin_version');
            $res[37] = delete_option('pfmdz_admincss_file');
            $res[38] = delete_option('pfmdz_frontcss_file'); 
            $res[39] = delete_option('pfmdz_removeelemfonts');
            $res[40] = delete_option('pfmdz_removegooglefonts');  
            $res[41] = delete_option('pfmdz_googfontstoremove'); 
            $res[42] = delete_option('pfmdz_admin_noimportant');  
            $res[43] = delete_option('pfmdz_allowfontsupload');  
            $res[44] = delete_option('pfmdz_nofonts_clases'); 
            $res[45] = delete_option('pfmdz_admincustomfamily'); 
            $res[46] = delete_option('pfmdz_admincustomweight');
            $res[47] = delete_option('pfmdz_frontcustomfamily12');
            $res[48] = delete_option('pfmdz_frontcustomweight12');
            $res[49] = delete_option('pfmdz_frontcustomfamily22');
            $res[50] = delete_option('pfmdz_frontcustomweight22'); 
            $res[51] = delete_option('pfmdz_frontcustomfamily32');
            $res[52] = delete_option('pfmdz_frontcustomweight32'); 
            $res[53] = delete_option('pfmdz_tmpversion'); 
        }
        
        $count = count($res);
        $counter = 0;

        foreach($res as $re){
            if($re === true){ $counter++; }
        }

        if ($counter == $count){
            echo "Persian Admin Fonts plugin datas and Options Deleted!";
        }
        wp_die();
    }
}
add_action('wp_ajax_pfmsz_emptyOptions_AjaxConf', 'pfmsz_emptyOptions_AjaxConf' );
/**/

/*Ajax to change Test-Text*/
if (!function_exists('pfmdz_writetocssfile_ajax')){
    function pfmdz_writetocssfile_ajax(){

        $fontfamily = sanitize_text_field($_POST['fontfamily']);
        $fontname = sanitize_text_field($_POST['fontname']);
        $fontweight = sanitize_text_field($_POST['fontweight']);
        $fontstyle = get_option('pfmdz_istalic');

        if ($fontstyle == '1') { $fontstyle = 'italic'; } else $fontstyle = 'normal';

        $css_path = persianfontsmdez_PATH . "libs".DIRECTORY_SEPARATOR ."fonts".DIRECTORY_SEPARATOR ."css".DIRECTORY_SEPARATOR ."testtext.css";

        $new_content = "@font-face {
            font-family: '".$fontfamily."';
            font-style: normal;
            font-weight: ".$fontweight.";
            src: url('../fonts/".$fontfamily."/eot/".$fontname.".eot');
            src: url('../fonts/".$fontfamily."/eot/".$fontname.".eot?#iefix') format('embedded-opentype'),
                 url('../fonts/".$fontfamily."/woff2/".$fontname.".woff2') format('woff2'),
                 url('../fonts/".$fontfamily."/woff/".$fontname.".woff') format('woff'),
                 url('../fonts/".$fontfamily."/ttf/".$fontname.".ttf') format('truetype');
        }
        #pfmdz-testtext {font-family: '".$fontfamily."' !important; font-weight: ".$fontweight."; font-style:".$fontstyle." !important;}";

        $res = pfmdz_admin::writeToFile($css_path, $new_content); //use the func inside core admin class

        if($res == 1){

            $tmp_arr = array($fontfamily, $fontweight);
            echo json_encode($tmp_arr);
            wp_die();

        }else wp_die();
        
    }
}
add_action('wp_ajax_pfmdz_writetocssfile_ajax', 'pfmdz_writetocssfile_ajax' );
/**/

/*NightMode Ajax*/
if (!function_exists('pfmdz_nightMode_ajax')){
    function pfmdz_nightMode_ajax(){

        $is_night = get_option('pfmdz_nightmode');
        if ($is_night == '0' || $is_night == '' || $is_night == 'null' || $is_night == NULL){
            update_option('pfmdz_nightmode', '1');
            echo "1";
        }else if ($is_night == '1'){
            update_option('pfmdz_nightmode', '0');
            echo "0";
        }
        wp_die();
    }
}
add_action('wp_ajax_pfmdz_nightMode_ajax', 'pfmdz_nightMode_ajax' );
/**/
/*Save Google fonts to DB*/
if (!function_exists('pfmdz_addgoog_fonts')){
    function pfmdz_addgoog_fonts(){

        $array = $_REQUEST['goog_fonts_arr'];

        delete_option("pfmdz_googfontstoremove");
        update_option("pfmdz_googfontstoremove", $array);

        echo "Done!";
        wp_die();
    }
}
add_action('wp_ajax_pfmdz_addgoog_fonts', 'pfmdz_addgoog_fonts' );
/**/
//close the PHP tag to reduce the blank spaces ?>