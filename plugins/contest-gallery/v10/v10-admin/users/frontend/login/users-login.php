<noscript>
<div style="border: 1px solid purple; padding: 10px">
<span style="color:red">Enable JavaScript to use the form</span>
</div>
</noscript>
<?php
if(!defined('ABSPATH')){exit;}

	extract( shortcode_atts( array(
			'id' => ''
		), $atts ) );
	$GalleryID = trim($atts['id']);

/*
session_start();
//echo @$_SESSION["cg_login_count"];
if(@$_SESSION["cg_login_count"]==false){
	//Achtung! Mit 1 anfangen ansonsten wird als false gez�hlt wenn es mit 0 anf�ngt.
	$_SESSION["cg_login_count"]=1;
}
//unset($_SESSION["cg_login_count"]);
if(!@$_SESSION["cg_start_time"]){
	$_SESSION["cg_start_time"]=time();
}

// Nach 10 Minuten wird die Session von der Zeit und von den Counts neu gesetzt
if($_SESSION["cg_start_time"]){
	if(time()-600 > $_SESSION["cg_start_time"]){
		$_SESSION["cg_start_time"]=time();
		$_SESSION["cg_login_count"]=0;
	}
}

if(@$_SESSION["cg_login_count"]>15){
	echo "To many invalid atempts. Please try few minutes later again";return false;
}*/

if(empty($_SESSION["cg_start_time"])){
	$_SESSION["cg_start_time"]=time();
}

// Nach 10 Minuten wird die Session von der Zeit und von den Counts neu gesetzt
/*if(!empty($_SESSION["cg_start_time"])){
	if(time()-600 > $_SESSION["cg_start_time"]){
		$_SESSION["cg_start_time"]=time();
		$_SESSION["cg_login_count"]=0;
	}
}*/

$is_frontend = true;

include(__DIR__ ."/../../../../../check-language.php");

global $wpdb;
$tablename_options = $wpdb->prefix . "contest_gal1ery_options";
$tablenameCreateUserForm = $wpdb->prefix . "contest_gal1ery_create_user_form";
$tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";
$tablename_options_visual = $wpdb->prefix . "contest_gal1ery_options_visual";
$tablename_registry_and_login_options = $wpdb->prefix . "contest_gal1ery_registry_and_login_options";
$tablenameWpUsers = $wpdb->base_prefix . "users";

$galleryDbVersion = $wpdb->get_var( "SELECT Version FROM $tablename_options WHERE id='$GalleryID'");

$LostPasswordMailActive = 0;

if(intval($galleryDbVersion)>=14){
    $registry_and_login_options = $wpdb->get_row("SELECT * FROM $tablename_registry_and_login_options WHERE GeneralID='1'");//get row here to not rewrite RegistryUserRole if not exists
    $LostPasswordMailActive = $registry_and_login_options->LostPasswordMailActive;
    $TextBeforeLoginForm = contest_gal1ery_convert_for_html_output($registry_and_login_options->TextBeforeLoginForm);
    $PermanentTextWhenLoggedIn = contest_gal1ery_convert_for_html_output($registry_and_login_options->PermanentTextWhenLoggedIn);
    $optionsVisual = $wpdb->get_row( "SELECT * FROM $tablename_options_visual WHERE GeneralID='1'");
}else{
    $registry_and_login_options = $wpdb->get_row("SELECT * FROM $tablename_registry_and_login_options WHERE GalleryID='$GalleryID'");//get row here to not rewrite RegistryUserRole if not exists
    $LostPasswordMailActive = $registry_and_login_options->LostPasswordMailActive;
    $TextBeforeLoginForm = contest_gal1ery_convert_for_html_output($registry_and_login_options->TextBeforeLoginForm);
    $PermanentTextWhenLoggedIn = contest_gal1ery_convert_for_html_output($registry_and_login_options->PermanentTextWhenLoggedIn);
    $optionsVisual = $wpdb->get_row( "SELECT * FROM $tablename_options_visual WHERE GalleryID='$GalleryID'");
}

$FeControlsStyleLogin = ($optionsVisual->FeControlsStyleLogin=='white' || empty($optionsVisual->FeControlsStyleLogin)) ?  'cg_fe_controls_style_white' : 'cg_fe_controls_style_black';
$BorderRadiusLogin = ($optionsVisual->BorderRadiusLogin=='1' || empty($optionsVisual->FeControlsStyleLogin)) ? 'cg_border_radius_controls_and_containers' : '';

if(!empty($_GET['cg_login_check'])){
    include('users-login-text-after-login.php');
}
else{
	
	if(!is_user_logged_in()){
		
		ob_start();

	/*	$resultsFieldNames = $wpdb->get_results("SELECT Field_Name,Field_Type FROM $tablenameCreateUserForm WHERE GalleryID = '$GalleryID' && (Field_Type='main-mail' || Field_Type='password')");

        $mailName = '';
        $passwordName = '';

		foreach ($resultsFieldNames as $resultFieldNames) {
		    if($resultFieldNames->Field_Type=='main-mail'){
                $mailName = $resultFieldNames->Field_Name;
            }
		    if($resultFieldNames->Field_Type=='password'){
                $passwordName = $resultFieldNames->Field_Name;
            }
        }*/

        if(intval($galleryDbVersion)>=14){
            $ForwardAfterLoginUrlValues = $wpdb->get_row("SELECT ForwardAfterLoginUrlCheck, ForwardAfterLoginUrl FROM $tablename_pro_options WHERE GeneralID = '1'");
        }else{
            $ForwardAfterLoginUrlValues = $wpdb->get_row("SELECT ForwardAfterLoginUrlCheck, ForwardAfterLoginUrl FROM $tablename_pro_options WHERE GalleryID = '$GalleryID'");
        }

		$ForwardAfterLoginUrlCheck = $ForwardAfterLoginUrlValues->ForwardAfterLoginUrlCheck;
		$ForwardAfterLoginUrl = html_entity_decode(stripslashes(nl2br($ForwardAfterLoginUrlValues->ForwardAfterLoginUrl)));

		$i=1;

        echo "<input type='hidden' id='cg_gallery_id' value='$GalleryID'/>";

        $isFromStaticLoginForm = true;

        include('users-login-forms.php');
		
		$formOutput = ob_get_clean();

        echo $formOutput;

	}
	else{

        echo $PermanentTextWhenLoggedIn;

	}

}




?>