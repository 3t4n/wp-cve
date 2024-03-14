<?php
if(!defined('ABSPATH')){exit;}

$galeryID = intval(sanitize_text_field($_REQUEST['gid']));
$galeryIDuser = sanitize_text_field($_REQUEST['galeryIDuser']);
$galleryHash = sanitize_text_field($_REQUEST['galleryHash']);
$galleryHashDecoded = wp_salt( 'auth').'---cngl1---'.$galeryIDuser;
$galleryHashToCompare = cg_hash_function('---cngl1---'.$galeryIDuser, $galleryHash);

if (!is_numeric($galeryID) or ($galleryHash != $galleryHashToCompare)){
    ?>
<pre>
    <script>

        var message = <?php echo json_encode('Please do not manipulate!');?>;
        var gid = <?php echo json_encode($galeryID); ?>;
        cgJsClass.gallery.function.message.show(gid,message);

    </script>
</pre>
    <?php

    return;
}
else {

    $wp_upload_dir = wp_upload_dir();
    $fp = fopen($wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-'.$galeryID.'/json/cg-change-top-controls-style-option-recognized.txt', 'w');
    fwrite($fp, 'cg-change-top-controls-style-option-recognized');
    fclose($fp);

    $galleryJsonCommentsDir = $wp_upload_dir['basedir'].'/contest-gallery/changes-messages-frontend';
    $countFile = $galleryJsonCommentsDir.'/cg-change-top-controls-style-option-recognized-count.txt';

    if(!file_exists($countFile)){
        $count = 0;
    }else{

        $fp = fopen($countFile, 'r');
        $count = intval(trim(fread($fp,filesize($countFile))));
        fclose($fp);

    }

    $count++;

    if($count>4){

        // general recognized file
        $fp = fopen($galleryJsonCommentsDir.'/cg-change-top-controls-style-option-recognized.txt', 'w');
        fwrite($fp, 'cg-change-top-controls-style-option-recognized');
        fclose($fp);

    }else{

        $fp = fopen($countFile, 'w');
        fwrite($fp, $count);
        fclose($fp);

    }



}

?>



