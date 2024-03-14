<?php
if(!defined('ABSPATH')){exit;}

$galeryID = intval(sanitize_text_field($_REQUEST['gid']));
$galeryIDuser = sanitize_text_field($_REQUEST['galeryIDuser']);
$galleryHash = sanitize_text_field($_REQUEST['galleryHash']);
$cgPageUrl = sanitize_text_field($_REQUEST['cgPageUrl']);
$galleryHashDecoded = wp_salt( 'auth').'---cngl1---'.$galeryIDuser;
$galleryHashToCompare = cg_hash_function('---cngl1---'.$galeryIDuser, $galleryHash);

if ($galleryHash != $galleryHashToCompare){
    return;
}

if(strpos($galeryIDuser,'-')!==false){
    $galeryIDuserArray = explode('-',$galeryIDuser);
    if($galeryIDuserArray[0]!=$galeryID){
        return;
    }
}else{
    if($galeryIDuser!=$galeryID){
        return;
    }
}

// open and write file
$wp_upload_dir = wp_upload_dir();

$options = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/'.$galeryID.'-options.json';
$fp = fopen($options, 'r');
$options =json_decode(fread($fp,filesize($options)),true);

$optionsSource = $options;
$intervalConf = cg_shortcode_interval_check($galeryID,$optionsSource,'cg_gallery');
if(!$intervalConf['shortcodeIsActive']){
    ?>
    <script data-cg-processing="true">
        cgJsClass.gallery.comment.removeLastComment();
    </script>
    <?php
    cg_shortcode_interval_check_show_ajax_message($intervalConf,$galeryIDuser);
    return;
}


if(!empty($options[$galeryIDuser])){
    $options = $options[$galeryIDuser];
}
fclose($fp);

if($options['general']['AllowComments']!=1){
    return;
}

// check script execution to avoid flooding via reload by developer tools
$scriptCheckFolderDay = __DIR__.'/script-exec-check/'.date('d');
$scriptCheckFolderDay = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/image-comments/script-exec-check/'.date('d');

if(!is_dir($scriptCheckFolderDay)){
    mkdir($scriptCheckFolderDay, 0755, true);
}

$scriptCheckFolder = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/image-comments/script-exec-check';

$userIP = sanitize_text_field(cg_get_user_ip());
$userIPforFile = str_replace(':','_',$userIP);

$userIpFileWithTime = $scriptCheckFolderDay.'/'.$userIPforFile;

if(is_file($userIpFileWithTime)){
    $fileTime = intval(file_get_contents($userIpFileWithTime));
    if(time()-9<$fileTime){
        ?>
        <script data-cg-processing="true">// if this exists then everything is fine. Will check if this exits or not
            var gid = <?php echo json_encode($galeryIDuser); ?>;
            cgJsClass.gallery.function.message.show(gid,'Stop flooding');
        </script>
        <?php
        echo "code 617 - stop flodding";
        return;
    }
    file_put_contents($userIpFileWithTime,time());
}else{
    file_put_contents($userIpFileWithTime,time());
}

// remove folders of not current day if exists
foreach(glob($scriptCheckFolder.'/*') as $dayFolder){
    if(is_dir($dayFolder) AND $dayFolder!=$scriptCheckFolderDay){
        cg_remove_folder_recursively($dayFolder);
    }
}
// check script execution to avoid flooding via reload by developer tools --- END

// set already here maybe required for not logged in message
$pictureID = absint(sanitize_text_field($_REQUEST['pid']));

if(isset($options['pro']['CheckLoginComment']) && $options['pro']['CheckLoginComment']==1 AND !is_user_logged_in()){
    ?>
    <script data-cg-processing="true">// if this exists then everything is fine. Will check if this exits or not

        var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
        var pictureID = <?php echo json_encode($pictureID);?>;
        jQuery(".cg-center-image-comments-div-recently-added").remove();

        cgJsClass.gallery.comment.setComment(pictureID,-1,galeryIDuser);
        cgJsClass.gallery.function.message.show(galeryIDuser,cgJsClass.gallery.language[galeryIDuser].YouHaveToBeLoggedInToComment);

    </script>
    <?php
    return;
}

//$explodeHash = explode('---cngl1---',$galleryHashDecoded);
//if($explodeHash[1]==$galeryID.'-u'){
    // show message will be shown in javascript when trying to comment
   // return;
//}

$Name = sanitize_text_field($_REQUEST['name']);
$Name = trim(stripslashes($Name));
$Name = htmlentities($Name, ENT_QUOTES);
$Name = substr($Name,0,300);// 100 is max as message in frontend but because of smylies it can be some more

$Comment = sanitize_textarea_field($_REQUEST['comment']);
$Comment = trim(stripslashes($Comment));
$Comment = nl2br(htmlspecialchars($Comment, ENT_QUOTES));
$Comment = substr($Comment,0,3000);// 1000 is max as message in frontend but because of smilies it can be some more

$unix = time();
$date = date("Y-m-d H:i",$unix);

// write database
global $wpdb;
$tablename = $wpdb->prefix . "contest_gal1ery";
$tablenameComments = $wpdb->prefix . "contest_gal1ery_comments";
$tablename_comments_notification_options = $wpdb->prefix . "contest_gal1ery_comments_notification_options";
$tablename_mail_user_comment = $wpdb->prefix . "contest_gal1ery_mail_user_comment";
$tablename_user_comment_mails = $wpdb->prefix . "contest_gal1ery_user_comment_mails";
$wp_users = $wpdb->prefix . "users";

$userIP = sanitize_text_field(cg_get_user_ip());

$WpUserId=0;
if(is_user_logged_in()){
    $WpUserId = get_current_user_id();
}

/*$wpdb->query( $wpdb->prepare(
    "
				INSERT INTO $tablenameComments
				( id, pid, GalleryID, Name, Date, Comment, Timestamp,IP,WpUserId)
				VALUES ( %s,%d,%d,%s,%s,%s,%d,%s,%d)
			",
    '',$pictureID,$galeryID,$Name,$date,$Comment,$unix,$userIP,$WpUserId
) );*/

//$lastCommentId = $wpdb->get_var("SELECT id FROM $tablenameComments WHERE pid = '$pictureID' ORDER BY id DESC LIMIT 0, 1");

$randomAdder = md5(uniqid('cg-comment'));
$lastCommentId = $unix.'-'.substr($randomAdder,0,6);

// process comments File
$commentsFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/image-comments/image-comments-'.$pictureID.'.json';
$fp = fopen($commentsFile, 'r');
$commentsFileData =json_decode(fread($fp,filesize($commentsFile)),true);
fclose($fp);

if(empty($commentsFileData)){
    $commentsFileData = array();
}

$Active = 0;
if(!empty($options['pro']['ReviewComm'])){
    $Active = 2;
}

$commentsFileData[$lastCommentId] = array();
$commentsFileData[$lastCommentId]['date'] = $date;
$commentsFileData[$lastCommentId]['timestamp'] = $unix;
$commentsFileData[$lastCommentId]['name'] = $Name;
$commentsFileData[$lastCommentId]['comment'] = $Comment;
$commentsFileData[$lastCommentId]['WpUserId'] = $WpUserId;
$commentsFileData[$lastCommentId]['ReviewTstamp'] = '';
$commentsFileData[$lastCommentId]['Active'] = $Active;
$commentsFileData[$lastCommentId]['userIP'] = $userIP;

$commentsFileDataTheOnlyOneComment = array();;
$commentsFileDataTheOnlyOneComment[$lastCommentId] = array();
$commentsFileDataTheOnlyOneComment[$lastCommentId]['date'] = $date;
$commentsFileDataTheOnlyOneComment[$lastCommentId]['timestamp'] = $unix;
$commentsFileDataTheOnlyOneComment[$lastCommentId]['name'] = $Name;
$commentsFileDataTheOnlyOneComment[$lastCommentId]['comment'] = $Comment;
$commentsFileDataTheOnlyOneComment[$lastCommentId]['WpUserId'] = $WpUserId;
$commentsFileDataTheOnlyOneComment[$lastCommentId]['ReviewTstamp'] = '';
$commentsFileDataTheOnlyOneComment[$lastCommentId]['Active'] = $Active;
$commentsFileDataTheOnlyOneComment[$lastCommentId]['userIP'] = $userIP;

$fp = fopen($commentsFile, 'w');
fwrite($fp,json_encode($commentsFileData));
fclose($fp);

// process comments File --- ENDE

// process rating comments data file
$dataFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/image-data/image-data-'.$pictureID.'.json';
$fp = fopen($dataFile, 'r');
$ratingCommentsData =json_decode(fread($fp,filesize($dataFile)),true);
fclose($fp);

// count active comments correctly
$countActiveComments = 0;
$countCountCtoReview = 0;

// process rating comments data file --- ENDE

// check if there were some database entries of before version 16
$countCommentsSQL = $wpdb->get_var( $wpdb->prepare(
    "
                SELECT COUNT(1)
                FROM $tablenameComments 
                WHERE pid = %d
            ",
    $pictureID
) );

// save comments for future repair eventually
$dirImageComments = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/image-comments/ids/'.$pictureID;
if(!is_dir($dirImageComments)){
    mkdir($dirImageComments, 0755, true);
}
// file can be used for future repair
file_put_contents($dirImageComments.'/'.$lastCommentId.'.json',json_encode($commentsFileDataTheOnlyOneComment));
$dirImageCommentsFiles = glob($dirImageComments.'/*.json');

$fileImageCommentsDirCount = count($dirImageCommentsFiles);

foreach ($dirImageCommentsFiles as $dirImageCommentsFile){
    $dirImageCommentsFileData = json_decode(file_get_contents($dirImageCommentsFile),true);
    if(!empty($dirImageCommentsFileData[key($dirImageCommentsFileData)]['Active']) && $dirImageCommentsFileData[key($dirImageCommentsFileData)]['Active']==2 && empty($dirImageCommentsFileData[key($dirImageCommentsFileData)]['ReviewTstamp'])){
        $countCountCtoReview++;
    }
}

$countCommentsTotal = $countCommentsSQL + $fileImageCommentsDirCount;

$ratingCommentsData['CountC'] = $countCommentsTotal;

// the rest will be done in cg_actualize_all_images_data_sort_values_file
$wpdb->update(
    "$tablename",
    array('CountC' => $countCommentsTotal, 'CountCtoReview' => $countCountCtoReview),
    array('id' => $pictureID),
    array('%d'),
    array('%d')
);

$ratingCommentsData = cg_check_and_repair_image_file_data($galeryID,$pictureID,$ratingCommentsData,false);

$fp = fopen($dataFile, 'w');
fwrite($fp,json_encode($ratingCommentsData));
fclose($fp);

if(!is_dir($wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/frontend-added-votes')){
    mkdir($wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/frontend-added-votes',0755,true);
}

// simply create empty file for later check
$jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/frontend-added-votes/'.$pictureID.'-'.$unix.'.txt';
$fp = fopen($jsonFile, 'w');
fwrite($fp, '');
fclose($fp);

// cg_actualize_all_images_data_sort_values_file will be done regullary when sites reload.
//do_action('cg_actualize_all_images_data_sort_values_file',$galeryID);

$commentsDataJsonFiles = glob($wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/image-comments/*.json');
$jsonCommentsData = [];
foreach ($commentsDataJsonFiles as $jsonFile) {
    $jsonFileData = json_decode(file_get_contents($jsonFile),true);
    if(!empty($jsonFileData)){
        $stringArray= explode('/image-comments-',$jsonFile);
        $imageId = substr(substr($jsonFile,strrpos($jsonFile,'-')+1, 30),0,-5);
     //   if(empty($jsonImagesData[$imageId])){// then must be from some old installation and uses some old json files, logic will be only used in v10-get-data.php
    //        continue;
       // }else{
            $jsonCommentsData[$imageId] = $jsonFileData;
     //   }
    }
}

?>

    <script data-cg-processing="true">// if this exists then everything is fine. Will check if this exits or not

        var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;
        var pictureID = <?php echo json_encode($pictureID);?>;
        var Active = <?php echo json_encode($Active);?>;
        var ratingCommentsDataFromJustCommented = <?php echo json_encode($ratingCommentsData);?>;

        cgJsData[galeryIDuser].jsonCommentsData = <?php echo json_encode($jsonCommentsData); ?>;
        cgJsClass.gallery.comment.setComments(galeryIDuser);
        if(Active!=2){
            cgJsClass.gallery.comment.setComment(pictureID,0,galeryIDuser,false,false,false,ratingCommentsDataFromJustCommented);
        }

    </script>

<?php

if(!empty($options['pro']['CommNoteActive'])){

    include(__DIR__ ."/../../../../check-language.php");

    $checkCommentsNotificationOptions = $wpdb->get_row("SELECT * FROM $tablename_comments_notification_options WHERE GalleryID = '$galeryID'");

    $CommNoteAddressor = contest_gal1ery_convert_for_html_output_without_nl2br($checkCommentsNotificationOptions->CommNoteAddressor);
    $CommNoteAdminMail = contest_gal1ery_convert_for_html_output_without_nl2br($checkCommentsNotificationOptions->CommNoteAdminMail);
    $CommNoteCC = contest_gal1ery_convert_for_html_output_without_nl2br($checkCommentsNotificationOptions->CommNoteCC);
    $CommNoteBCC = contest_gal1ery_convert_for_html_output_without_nl2br($checkCommentsNotificationOptions->CommNoteBCC);
    $CommNoteReply = contest_gal1ery_convert_for_html_output_without_nl2br($checkCommentsNotificationOptions->CommNoteReply);
    $CommNoteSubject = contest_gal1ery_convert_for_html_output_without_nl2br($checkCommentsNotificationOptions->CommNoteSubject);
    $CommNoteContent = contest_gal1ery_convert_for_html_output($checkCommentsNotificationOptions->CommNoteContent);

    $headers = array();
    $headers[] = "From: " . html_entity_decode(strip_tags($CommNoteAddressor)) . " <" . strip_tags($CommNoteReply) . ">";
    $headers[] = "Reply-To: " . strip_tags($CommNoteReply) . "";
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "Content-Type: text/html; charset=utf-8";

    $NameForMail = contest_gal1ery_convert_for_html_output($Name);
    $NameForMail = preg_replace("/&amp;amp;#x/","&#x",$NameForMail);// do both to go sure
    $NameForMail = preg_replace("/&amp;#x/","&#x",$NameForMail);// do both to go sure

    $CommentForMail = contest_gal1ery_convert_for_html_output($Comment);
    $CommentForMail = preg_replace("/&amp;amp;#x/","&#x",$CommentForMail);// do both to go sure
    $CommentForMail = preg_replace("/&amp;#x/","&#x",$CommentForMail);// do both to go sure

    if(empty($galeryIDuser)){
        $galeryIDuser = $galeryID;// because might have be send from cg_gallery_user or cg_gallery_no_voting shortcode
    }

    // open again because might be reqpired
    $dataFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/image-data/image-data-'.$pictureID.'.json';
    $fp = fopen($dataFile, 'r');
    $imageData =json_decode(fread($fp,filesize($dataFile)),true);
    fclose($fp);

    // #toDo check if multisite working
    $adminUrl = get_site_url()."/wp-admin/admin.php";
    $post_title = $imageData['post_title'];

    $WpPage = $wpdb->get_var("SELECT WpPage FROM $tablename WHERE id = '$pictureID'  ORDER BY id DESC LIMIT 1");

    if(!empty($WpPage)){
        $WpPagePermalink = get_permalink($WpPage);
        $urlFrontend = '<a href="'.$WpPagePermalink.'" >'.$WpPagePermalink.'</a>';
    }else{
    $urlFrontend = $cgPageUrl."#!gallery/$galeryIDuser/image/$pictureID/$post_title";
    $urlFrontend = '<a href="'.$urlFrontend.'" >'.$urlFrontend.'</a>';
    }

    $urlBackend = $adminUrl."?page=".cg_get_version()."/index.php#option_id=$galeryID&show_comments=true&id=$pictureID";
    $urlBackend = '<a href="'.$urlBackend.'" >'.$urlBackend.'</a>';

    $posComment = '$comment$';
    $commentComplete = '<br><br>'.$language_Name.':<br>'.$NameForMail.'<br>'.$language_Comment.':<br>'.$CommentForMail.'<br><br><br>URL backend: '.$urlBackend.'<br><br>URL frontend: '.$urlFrontend.' 
    <br><br><br><b>NOTE:</b> if you see question marks or cryptic code in this e-mail then this are smileys (emoticons) which can not be displayed by e-mail provider';

    if (stripos($CommNoteContent, $posComment) !== false) {
        $CommNoteContent = str_ireplace($posComment, $commentComplete, $CommNoteContent);
    }

    global $cgMailAction;
    global $cgMailGalleryId;
    $cgMailAction = "User comment notification e-mail";
    $cgMailGalleryId = $galeryID;
    add_action('wp_mail_failed', 'cg_on_wp_mail_error', 10, 1);
    if (!wp_mail($CommNoteAdminMail, $CommNoteSubject, $CommNoteContent, $headers)) {
        echo "Failed sending user comment mail, please contact administrator";
        die;
    }


}


?>