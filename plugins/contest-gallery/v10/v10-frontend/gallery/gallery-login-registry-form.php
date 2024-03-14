<?php

$cgHideUserAjaxLoginForm = 'cg_hide';

$BorderRadiusLogin = $BorderRadiusClass;

$isForAjax = true;

if(!isset($GalleryID)){
    $GalleryID = $galeryID;
}

include (__DIR__.'/../../v10-admin/users/frontend/users-login-form.php');

global $wpdb;
$tablenameCreateUserForm = $wpdb->prefix . "contest_gal1ery_create_user_form";
$tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";

$ForwardAfterRegText = $wpdb->get_var( "SELECT ForwardAfterRegText FROM $tablename_pro_options WHERE GalleryID = '$galeryID'" );
$ForwardAfterRegText = contest_gal1ery_convert_for_html_output($ForwardAfterRegText);

$selectUserForm = $wpdb->get_results("SELECT * FROM $tablenameCreateUserForm WHERE GalleryID = '$galeryID' && Active = '1' ORDER BY Field_Order ASC");

include (__DIR__.'/../../v10-admin/users/frontend/users-registry-form.php');

/*echo "<div id='mainCGdivLoginFormContainer$galeryIDuser' class='mainCGdivUploadFormContainer mainCGdivLoginFormContainer $cgFeControlsStyle' data-cg-gid='$galeryIDuserForJs'>";

echo "</div>";*/

?>