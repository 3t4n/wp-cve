<?php
if(!defined('ABSPATH')){exit;}

		// 1 = Mail or Username
		// 2 = Password
		// 3 = Check
		// 4 = GalleryID

		if(session_id() == '') {
			session_start();
		}

		/*if(@$_SESSION["cg_login_count"]==false){
			echo "Plz don't manipulate the registry Code:117";return false;
		}*/

/*		if(empty($_SESSION["cg_login_count"])){
			//Achtung! Mit 1 anfangen ansonsten wird als false gezählt wenn es mit 0 anfängt.
			$_SESSION["cg_login_count"]=1;
		}
		else{
			$_SESSION["cg_login_count"]++;
		}

		if($_SESSION["cg_login_count"]>15){
			echo "To many invalid atempts. Please try few minutes later again";return false;
		}*/

        $GalleryID = sanitize_text_field($_REQUEST['action4']);

        $wp_upload_dir = wp_upload_dir();
        $optionsPath = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-options.json';
        $optionsSource =json_decode(file_get_contents($optionsPath),true);
        $intervalConf = cg_shortcode_interval_check($GalleryID,$optionsSource,'cg_users_login');
        if(!$intervalConf['shortcodeIsActive']){
            ?>
            <script data-cg-processing="true">
                var gid = <?php echo json_encode($GalleryID);?>;
                cgIsShortcodeIntervalOverForLogin = true;
            </script>
            <?php
            cg_shortcode_interval_check_show_ajax_message($intervalConf,$GalleryID);
            return;
        }

        $cg_check = sanitize_text_field($_REQUEST['action3']);
        $galleryHashToCompare = cg_hash_function('---cglogin---'.$GalleryID, $cg_check);

        // Hier geht die Validierung los
		if($cg_check==$galleryHashToCompare){

            global $wpdb;

            if(!empty($_REQUEST['cgLostPasswordEmail'])){
                include (__DIR__.'/ajax/users-login-check-ajax-lost-password.php');
                return;
            }

            if(!empty($_REQUEST['cgLostPasswordNew'])){
                include (__DIR__.'/ajax/users-login-check-ajax-password-reset.php');
                return;
            }

		$tablenameWpUsers = $wpdb->base_prefix . "users";
        $tablename_options = $wpdb->prefix."contest_gal1ery_options";
        $tablename_pro_options = $wpdb->prefix."contest_gal1ery_pro_options";

		$cg_login_name_mail = sanitize_text_field($_REQUEST['action1']);

		$cg_user_email = false;
		$cg_user_login = false;
        $cgPwHash = false;

		//Check name or email
			if(is_email($cg_login_name_mail)){
                $cgWpData = $wpdb->get_row("SELECT ID, user_login, user_pass FROM $tablenameWpUsers WHERE user_email = '".$cg_login_name_mail."'");
			}else{
                $cgWpData = $wpdb->get_row("SELECT ID, user_email, user_pass FROM $tablenameWpUsers WHERE user_login = '".$cg_login_name_mail."'");
            }

		if(empty($cgWpData)){

?>
<script data-cg-processing="true">
var cg_language_LoginAndPasswordDoNotMatch = document.getElementById("cg_language_LoginAndPasswordDoNotMatch").value;

var cg_check_mail_name_value_for_login = document.getElementById('cg_check_mail_name_value_for_login');
cg_check_mail_name_value_for_login.value = 1;

var cg_append_login_and_password_do_not_match = document.getElementById('cg_append_login_and_password_do_not_match');
cg_append_login_and_password_do_not_match.innerHTML = cg_append_login_and_password_do_not_match.innerHTML + cg_language_LoginAndPasswordDoNotMatch;
cg_append_login_and_password_do_not_match.classList.remove('cg_hide');

// Password Feld leer machen
//var cg_login_password = document.getElementById('cg_login_password');
//cg_login_password.value = '';

</script>
<?php
		return false;

		}
		else{

			$cg_login_password = sanitize_text_field($_REQUEST['action2']);

			require_once(ABSPATH ."wp-load.php");
			$cgCheckPw = (wp_check_password($cg_login_password, $cgWpData->user_pass));

			if($cgCheckPw==false){

?>
<script data-cg-processing="true">
var cg_language_LoginAndPasswordDoNotMatch = document.getElementById("cg_language_LoginAndPasswordDoNotMatch").value;

var cg_check_mail_name_value_for_login = document.getElementById('cg_check_mail_name_value_for_login');
cg_check_mail_name_value_for_login.value = 1;

var cg_append_login_and_password_do_not_match = document.getElementById('cg_append_login_and_password_do_not_match');
cg_append_login_and_password_do_not_match.innerHTML = cg_append_login_and_password_do_not_match.innerHTML + cg_language_LoginAndPasswordDoNotMatch;
cg_append_login_and_password_do_not_match.classList.remove('cg_hide');

// Password Feld leer machen
//var cg_login_password = document.getElementById('cg_login_password');
//cg_login_password.value = '';

</script>
<?php
			}
			else{
					// Anzahl Login Versuche beginnt von Vorne
					//$_SESSION["cg_login_count"]=1;
/*					$creds = array();
					$creds['user_login'] = $cgWpData->user_login;
					$creds['user_password'] = $cg_login_password;
					$creds['remember'] = true;
					$user = wp_signon( $creds, true );*/
                // works better (more reliable on different systems and cases) then wp_signon!
                wp_set_auth_cookie( $cgWpData->ID,true );

                $galleryDbVersion = $wpdb->get_var( "SELECT Version FROM $tablename_options WHERE id='$GalleryID'");

                if(intval($galleryDbVersion)>=14){
                    $ForwardAfterLoginUrlCheck = intval($wpdb->get_var("SELECT ForwardAfterLoginUrlCheck FROM $tablename_pro_options WHERE GeneralID = '1'"));
                    $ForwardAfterLoginUrl = html_entity_decode(stripslashes(nl2br($wpdb->get_var("SELECT ForwardAfterLoginUrl FROM $tablename_pro_options WHERE GeneralID = '1'"))));
                }else{
                    $ForwardAfterLoginUrlCheck = intval($wpdb->get_var("SELECT ForwardAfterLoginUrlCheck FROM $tablename_pro_options WHERE GalleryID = '$GalleryID'"));
                    $ForwardAfterLoginUrl = html_entity_decode(stripslashes(nl2br($wpdb->get_var("SELECT ForwardAfterLoginUrl FROM $tablename_pro_options WHERE GalleryID = '$GalleryID'"))));
                }

                    ?>
                    <script data-cg-processing="true" data-cg-processing-successfully="true">
                        cgJsClass.gallery.vars.isSuccessFullySignedIn = true;
                        cgJsClass.gallery.vars.ForwardAfterLoginUrlCheck = <?php echo json_encode($ForwardAfterLoginUrlCheck); ?>;
                        cgJsClass.gallery.vars.ForwardAfterLoginUrl = null;
                        if(cgJsClass.gallery.vars.ForwardAfterLoginUrlCheck){
                            cgJsClass.gallery.vars.ForwardAfterLoginUrl = <?php echo json_encode($ForwardAfterLoginUrl); ?>;
                        }
                    </script>
                    <?php
                    die();
			}

		}

		}
		else{

            ?>
            <script data-cg-processing="true">
                console.log("Login manipulation prevention code 341. Please contact Administrator if you have questions.");
            </script>
            <?php
            die();

		}


?>