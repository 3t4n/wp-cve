<?php
if(!defined('ABSPATH')){exit;}

if(!empty($_FILES) AND !empty($_FILES['cg_input_image_upload_file']) AND !empty($_FILES['cg_input_image_upload_file']['tmp_name']) AND !empty($_FILES['cg_input_image_upload_file']['tmp_name'][0])){
    $_FILES = cg1l_sanitize_files($_FILES,'cg_input_image_upload_file',2100000);
}

$GalleryID = absint(sanitize_text_field($_POST['cg_gallery_id_registry']));

$wp_upload_dir = wp_upload_dir();
$optionsPath = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-options.json';
$optionsSource =json_decode(file_get_contents($optionsPath),true);
$intervalConf = cg_shortcode_interval_check($GalleryID,$optionsSource,'cg_users_reg');
if(!$intervalConf['shortcodeIsActive']){
    ?>
    <script data-cg-processing="true">
        var gid = <?php echo json_encode($GalleryID);?>;
        cgIsShortcodeIntervalOverForReg = true;
    </script>
    <?php
    cg_shortcode_interval_check_show_ajax_message($intervalConf,$GalleryID);
    return;
}

$_POST = cg1l_sanitize_post($_POST);

$cg_current_page_id = intval(sanitize_text_field($_POST['cg_current_page_id']));
$currentPageUrl = get_permalink($cg_current_page_id);

if(empty($currentPageUrl)){
    ?>
    <script  data-cg-processing="true">

        var cg_error = "Please do not manipulate page id code 332. Please contact Administrator if you have questions.";

        var cg_registry_manipulation_error = document.getElementById('cg_registry_manipulation_error');
        cg_registry_manipulation_error.innerHTML = cg_registry_manipulation_error.innerHTML + cg_error;
        cg_registry_manipulation_error.classList.remove("cg_hide");

        console.log(cg_error);

    </script>
    <?php
    die;
}

		// 1 = Mail
		// 2 = Name
		// 3 = Check

        $GalleryID = absint(sanitize_text_field($_POST['cg_gallery_id_registry']));
        $cg_check = sanitize_text_field($_POST['cg_check']);
        $galleryHashToCompare = cg_hash_function('---cgreg---'.$GalleryID, $cg_check);


/*		var cg_check = $("#cg_user_registry_form #cg_check").val();
    var cg_main_mail = $( ".cg-main-mail" ).val();
    var cg_main_user_name = $( ".cg-main-user-name" ).val();
    var cg_gallery_id_registry = $( "#cg_gallery_id_registry" ).val();*/
if($cg_check==$galleryHashToCompare){
	global $wpdb;

	$tablenameWpUsers = $wpdb->base_prefix . "users";
    $tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";
    $tablename_options = $wpdb->prefix . "contest_gal1ery_options";
    $table_usermeta = $wpdb->prefix . "usermeta";

    $galleryDbVersion = $wpdb->get_var("SELECT Version FROM $tablename_options WHERE id='$GalleryID'");

    $cg_main_mail = sanitize_text_field($_POST['cg-main-mail']);

	$cg_main_user_name = sanitize_text_field($_POST['cg-main-user-name']);

    $cg_main_nick_name = '';

    if(intval($galleryDbVersion)>=14){
        $cg_main_nick_name = sanitize_text_field($_POST['cg-main-nick-name']);
    }

    $checkWpIdViaMail = false;

    if(!empty($cg_main_mail)){
        $checkWpIdViaMail = $wpdb->get_var("SELECT ID FROM $tablenameWpUsers WHERE user_email = '".$cg_main_mail."'  LIMIT 1");
    }

    $checkWpIdViaUserName = false;
    if(!empty($cg_main_user_name)){
        $checkWpIdViaUserName = $wpdb->get_var("SELECT ID FROM $tablenameWpUsers WHERE user_login = '".$cg_main_user_name."' LIMIT 1");
    }

    $checkWpIdViaNickName = false;
    $checkWpIdViaNickNameUsermeta = false;
    if(intval($galleryDbVersion)>=14 && !empty($cg_main_nick_name)){
        $checkWpIdViaNickName = $wpdb->get_var("SELECT ID FROM $tablenameWpUsers WHERE user_nicename = '".$cg_main_nick_name."' LIMIT 1");
        $checkWpIdViaNickNameUsermeta = $wpdb->get_var("SELECT user_id FROM $table_usermeta WHERE meta_key = 'nickname' AND meta_value = '$cg_main_nick_name'");
    }

	if($checkWpIdViaMail==true){

?>
<script  data-cg-processing="true">
var cg_language_ThisMailAlreadyExists = document.getElementById("cg_language_ThisMailAlreadyExists").value;

var cg_check_mail_name_value = document.getElementById('cg_check_mail_name_value');
cg_check_mail_name_value.value = 1;// blocks form from beeing submitted

//var div = document.getElementById('divID');
var cg_mail_check_alert = document.getElementById('cg_mail_check_alert');
cg_mail_check_alert.innerHTML = cg_mail_check_alert.innerHTML + cg_language_ThisMailAlreadyExists;
cg_mail_check_alert.classList.remove("cg_hide");

//alert(cg_language_ThisMailAlreadyExists);
</script>
<?php
		}
		if($checkWpIdViaUserName==true){

?>
<script data-cg-processing="true">
var cg_language_ThisUsernameAlreadyExists = document.getElementById("cg_language_ThisUsernameAlreadyExists").value;

var cg_check_mail_name_value = document.getElementById('cg_check_mail_name_value');
cg_check_mail_name_value.value = 1;// blocks form from beeing submitted

var cg_user_name_check_alert = document.getElementById('cg_user_name_check_alert');
cg_user_name_check_alert.innerHTML = cg_user_name_check_alert.innerHTML + cg_language_ThisUsernameAlreadyExists;
cg_user_name_check_alert.classList.remove("cg_hide");

</script>
<?php
		}
if((intval($galleryDbVersion)>=14 && $checkWpIdViaNickName==true) || (intval($galleryDbVersion)>=14 && !empty($checkWpIdViaNickNameUsermeta))){
?>
<script data-cg-processing="true">
var cg_language_ThisNicknameAlreadyExists = document.getElementById("cg_language_ThisNicknameAlreadyExists").value;

var cg_check_mail_name_value = document.getElementById('cg_check_mail_name_value');
cg_check_mail_name_value.value = 1;// blocks form from beeing submitted

var cg_nick_name_check_alert = document.getElementById('cg_nick_name_check_alert');
cg_nick_name_check_alert.innerHTML = cg_nick_name_check_alert.innerHTML + cg_language_ThisNicknameAlreadyExists;
cg_nick_name_check_alert.classList.remove("cg_hide");

//alert(cg_language_ThisUsernameAlreadyExists);
</script>
<?php
		}
        if($checkWpIdViaUserName!=true && $checkWpIdViaMail!=true && $checkWpIdViaNickName!=true && $checkWpIdViaNickNameUsermeta!=true){
            if(intval($galleryDbVersion)>=14){
             //   $RegMailOptional = $wpdb->get_var( "SELECT RegMailOptional FROM $tablename_pro_options WHERE GeneralID = '1'" );
            }else{
                $GalleryID = absint($GalleryID);
              //  $RegMailOptional = $wpdb->get_var( "SELECT RegMailOptional FROM $tablename_pro_options WHERE GalleryID = '$GalleryID'" );
            }
            include('users-registry-check-registering-and-login.php');
            // <<< registration and forwarding processing will be done here
            return;
        }

}
else{

    ?>
    <script  data-cg-processing="true">

        var cg_error = "Registration manipulation prevention code 331. Please contact Administrator if you have questions.";

        var cg_registry_manipulation_error = document.getElementById('cg_registry_manipulation_error');
        cg_registry_manipulation_error.innerHTML = cg_registry_manipulation_error.innerHTML + cg_error;
        cg_registry_manipulation_error.classList.remove("cg_hide");

        console.log(cg_error);

    </script>
    <?php
    die;

}


?>