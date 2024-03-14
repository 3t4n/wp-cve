<?php
    add_action('init', 'wpcloud_handler');
    
    function wpcloud_handler() {
    
        //Formats:
        // /?cloud=upload&redirect=$url
        // /?cloud=delete&file=$name&redirect=$url
        if (!isset($_GET['cloud'])) {
            return;
        } else if ($_GET['cloud'] == 'upload') {
            wpcloud_upload();
            wpcloud_redirect($_GET['redirect']);
        } else if ($_GET['cloud'] == 'delete') {
            wpcloud_delete($_GET['file']);
            wpcloud_redirect($_GET['redirect']);
	} else if ($_GET['cloud'] == 'send') {
            wpcloud_send($_GET['username']);
            wpcloud_redirect($_GET['redirect']);
        } else {
            wpcloud_log( 'fatal error in handler at line 21. Abort', true);
            die('Plugin error - WPCLOUD');
        }
        
    }

    //Test if a user exist
    function username_exists_by_id($user_ID){
        return get_user_by( 'id', $user_ID );
    }
    
    function wpcloud_upload() {
        $upload_directory = ABSPATH . 'cloud/' . get_current_user_id() . '/';
        
        if (!(file_exists($upload_directory))) {
            mkdir(ABSPATH . 'cloud/' . get_current_user_id(), 0775, true);
	}
		
	//Check for allowed extension
	$temp1 = explode(".", $_FILES["file"]["name"]);
	$extension = end($temp1);
	if (!in_array($extension, getAllowedExtensions())) {
            die('File extension not supported');
	}
        
        $temp = explode(".", $_FILES["file"]["name"]);

	$size_MB = $_FILES["file"]["size"] / 1000000;
	$size_MB = substr($size_MB, 0, 4);  
	$can_UPLOAD = wpcloud_can_upload($size_MB, get_current_user_id());
		
	if ($_FILES["file"]["error"] > 0) {
        echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
        wpcloud_log( 'upload file "'.$_FILES["file"]["name"].'" with error ' . $_FILES["file"]["error"], true);
	} else if ($can_UPLOAD==false) {
        echo 'Allowed space terminated for this account';
        wpcloud_log( 'upload file "'.$_FILES["file"]["name"].'" but space terminated', true);
	} else {
            if (file_exists($upload_directory . $_FILES["file"]["name"])) {
                wpcloud_log( 'upload file "'.$_FILES["file"]["name"].' but file already exist', true);
                echo $_FILES["file"]["name"] . " already exists.";
            } else {
		      move_uploaded_file($_FILES["file"]["tmp_name"], $upload_directory . $_FILES["file"]["name"]);
                 wpcloud_log( 'upload file "'.$_FILES["file"]["name"], false);
	       }
	   }
    }

function wpcloud_send() {
    if (username_exists_by_id(get_user_by('email',$_POST['username'])->ID)) {
        $user_ID =  get_user_by('email',$_POST['username'])->ID;
    } else if (username_exists_by_id(get_user_by('login',$_POST['username'])->ID)) {
        $user_ID =  get_user_by('login',$_POST['username'])->ID;
    } else {
        wpcloud_log( 'send file "'.$_FILES["file"]["name"].'" to "'. $_POST['username'] . '" but no user match found', true);
        wp_die('This e-mail address doesn\'t exist.<br>Please be sure you enter the correct address.<br><a href="javascript: window.history.go(-1)">&laquo; Back</a>');
    }
        
    $upload_directory = ABSPATH . 'cloud/' . $user_ID . '/';

    if (!(file_exists($upload_directory))) {
        mkdir(ABSPATH . 'cloud/' . $user_ID, 0775, true);
    }
		
	//Check for allowed extension
	$temp1 = explode(".", $_FILES["file"]["name"]);
	$extension = end($temp1);
	if (!in_array($extension, getAllowedExtensions())) {
        wpcloud_log( 'send file "'.$_FILES["file"]["name"].'" to ID '. $user_ID . ' but extension not supported', true);
        die('File extension not supported');
	}
        
        $temp = explode(".", $_FILES["file"]["name"]);

	$size_MB = $_FILES["file"]["size"] / 1000000;
	$size_MB = substr($size_MB, 0, 4);  
	$can_UPLOAD = wpcloud_can_upload($size_MB, $user_ID);
		
	if ($_FILES["file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
            wpcloud_log( 'send file "'.$_FILES["file"]["name"].'" to ID '. $user_ID . ' with error ' . $_FILES["file"]["error"], true);
	} else if ($can_UPLOAD==false) {
            echo 'Allowed space terminated for this account';
            wpcloud_log( 'send file "'.$_FILES["file"]["name"].'" to ID '. $user_ID . ' but space terminated', true);
	} else {
            if (file_exists($upload_directory . $_FILES["file"]["name"])) {
                echo $_FILES["file"]["name"] . " already exists.";
                wpcloud_log( 'send file "'.$_FILES["file"]["name"].'" to ID '. $user_ID . ' but file already exist', true);
            } else {
                move_uploaded_file($_FILES["file"]["tmp_name"], $upload_directory . $_FILES["file"]["name"]);
                wpcloud_log( 'send file "'.$_FILES["file"]["name"].'" to ID '. $user_ID, false);
	    }
	}
}    

    function wpcloud_delete($fileNameToDelete) {
        unlink(ABSPATH . 'cloud/' . get_current_user_id() . '/' . $fileNameToDelete);
        wpcloud_log( 'delete file '.$fileNameToDelete. ' ('.ABSPATH . 'cloud/' . get_current_user_id() . '/' . $fileNameToDelete, false);
    }
    
    function wpcloud_redirect($redirectTo) {
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$redirectTo.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>'; exit;
    }
?>