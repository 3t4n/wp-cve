<?php

//$GalleryID = @$_GET['option_id'];

echo "<div id='cgGalleryBackendContainer'>";
echo "<table id='cgGalleryBackendDataManagement'>";
echo "<tr>";
echo "<td style='padding-left:20px;width:353px;position:relative;padding-bottom: 20px;padding-right: 15px;' colspan='2' id='cgGalleryBackendAllowedFileTypes'>";
echo "<br/><b>Allowed file types to add via backend:</b>";
echo "<div style='display: flex;'><div>Images:</div><div><b>&nbsp;JPG, PNG, GIF</b></div></div>";
echo "<div style='display: flex;'><div>Files:</div><div><b>&nbsp;TXT, DOC, DOCX, XLS, XLSX, PPT, PPTX, CSV,&nbsp;<span class='$cgProFalse'>PDF, ZIP</span></b></div></div>";
echo "<div style='display: flex;'><div>Audio:</div><div><b>&nbsp;M4A, OGG,&nbsp;<span class='$cgProFalse'>WAV, MP3</span></b></div></div>";
echo "<div style='display: flex;'><div>Video:</div><div><b>&nbsp;WEBM,&nbsp;<span class='$cgProFalse'>MP4, MOV</span></b></div></div>";
if(is_multisite()){
    echo "<div style='width: fit-content;border: thin solid #dedede; padding: 5px;border-radius:8px;'><span style='font-weight: bold;color:red;'>Multisite note:</span> Some of the file types above which are allowed<br>
 by default for a WordPress Single Site installation<br>might not be allowed by default for a Multisite.<br><b>Allowed Multisite file types can be configured in:</b><br>
 Network Admin >>> Settings >>> Upload file types</div>";
}
echo "<b>Allowed file types frontend:</b> configurable in <b>\"Edit contact form\"</b><br/>";

echo "<span style='position: relative;'>Maximum <b>upload_max_filesize</b> in your PHP configuration: <b>$upload_max_filesize MB</b> 
<span class=\"cg-info-icon\"><b><u>info</b></u></span>
 <span class=\"cg-info-container\" style=\"top: 22px;left: 365px;display: none;\">Maximum upload size per file<br><br>To increase in .htaccess file use:<br><b>php_value upload_max_filesize 10MB</b> (example, no equal to sign!)
 <br>To increase in php.ini file use:<br><b>upload_max_filesize = 10MB</b> (example, equal to sign required!)<br><br><b>Some server providers does not allow manually increase in files.<br>It has to be done in providers backend or they have to be contacted.</b></span>
 </span>";

echo "<span style='position: relative;'>Maximum <b>post_max_size</b> in your PHP configuration: <b>$post_max_size MB</b> 
<span class=\"cg-info-icon\"><b><u>info</b></u></span>
 <span class=\"cg-info-container\" style=\"top: 23px;left: -130px;display: none;\"><br>Describes the maximum size of a post which can be done when form submits.<br>
 Example: you try to upload 3 files with each 3MB and post_max_size is 6MB, then it will not work.<br><br>To increase in htaccess file use:<br><b>php_value post_max_size 10MB</b> (example, no equal to sign!)
 <br>To increase in php.ini file use:<br><b>post_max_size = 10MB</b> (example, equal to sign required!)<br><br><b>Some server providers does not allow manually increase in files.<br>It has to be done in providers backend or they have to be contacted.</b></span>
 </span>";


echo "<br/>Memory limit provided from your server provider: ";
if($memory_limit>=250){echo "<span style='color:green;font-weight:bold;'>$memory_limit MB</span>";}
if($memory_limit<250 && $memory_limit>=120){echo "<span style='color:orange;font-weight:bold;'>$memory_limit MB</span>";}
if($memory_limit<120 && $memory_limit!='-1'){echo "<span style='color:red;font-weight:bold;'>$memory_limit MB</span>";}
if($memory_limit=='-1'){echo "<span style='font-weight:bold;'>No memory limit set from server. Real memory limit unrecognizable.</span>";}
echo "<br>";

echo "<span style='position: relative;'>Maximum <b>max_input_vars</b> in your PHP configuration: ";
if($max_input_vars>=3000){echo "<span style='color:green;font-weight:bold;'>$max_input_vars</span>";}
if($max_input_vars<3000 && $max_input_vars>=1000){echo "<span style='color:orange;font-weight:bold;'>$max_input_vars</span>";}
if($max_input_vars<1000){echo "<span style='color:red;font-weight:bold;'>$max_input_vars</span>";}
echo " <span class=\"cg-info-icon\"><b><u>info</b></u></span>
 <span class=\"cg-info-container\" style=\"top: 22px;left: 320px;display: none;\">Important for how many information can be processed in backend<br><b>If 2000 and higher 50 files per site can be shown in backend</b><br><br>To increase in htaccess file use:<br><b>php_value max_input_vars 2000</b> (example, no equal to sign!)
 <br>To increase in php.ini file use:<br><b>max_input_vars = 2000</b> (example, equal to sign required!)<br><br><b>Some server providers does not allow manually increase in files.<br>It has to be done in providers backend or they have to be contacted.</b></span>
 </span>";


if($cgVersion<7){
    echo "&nbsp;&nbsp;<a id='cg_server_power_info'><b><u>INFO</u></b></a></b>";
    ?>
    <div id="cg_answerPNG" style="position: absolute; margin-left: 135px; margin-top: 10px;width: 460px; background-color: white; border: 1px solid; padding: 5px; display: none;">
        Higher memory allows you to upload bigger images with higher resolution.<br>
        If you receive an error during upload like "Allowed memory size of ... exhausted",
        then try to upload same image in minor resolution.<br>
        ≈256 MB: good <br>
        ≈128 MB: average <br>
        ≈64 MB: poor <br></div>

    <?php
}


//add_action( 'admin_enqueue_scripts', 'load_wp_media_files' );
$admin_url = admin_url();

echo '<input type="hidden" id="cg_gallery_id" value="'. $GalleryID .'">';
echo '<input type="hidden" id="cg_admin_url" value="'. $admin_url .'">';
echo "<div style='margin-top: 7px; height:35px;'>";

?>

    <!--<input type="number" value="" class="regular-text process_custom_images" id="process_custom_images" name="" max="10" min="1" step="10">-->
    <div style="display:flex;float:left;" id="cgAddImagesWpUploader">
        <button data-cg-gid="<?php echo $GalleryID; ?>" class="cg_upload_wp_images_button button cg_backend_button_gallery_action" style="margin-right: 15px;">Add files</button>
        <br>
    </div>

<?php

$plugins_url = plugins_url();
echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src='".$plugins_url."/".cg_get_version()."/v10/v10-css/loading.gif' width='25px' style='display:none;margin-left: 70px !important;margin-top: -17px;' id='cg_uploading_gif'/>
      <div style='position:absolute;display:none;vertical-align:middle;height:28px !important;line-height:28px !important;margin-left: 0 !important;margin-bottom: 5px;' id='cg_uploading_div'>
      &nbsp;&nbsp;(adding files please wait)</div>";


echo "</div>";

echo "<div style='display:none;' id='cg_wp_upload_ids'></div>";
echo "<div id='cg_wp_upload_div'></div>";

if($cgVersion<7){
    echo "<div style='margin-bottom:15px;margin-top:15px;clear:both;'>What happens when adding images?&nbsp;<a id='cg_adding_images_info'><u>Read here...</u></a></b>";
    ?>
    <div id="cg_adding_images_answer" style="position: absolute; margin-left: 40px; margin-top: 10px;width: 510px; background-color: white; border: 1px solid; padding: 5px; display: none;z-index:500;">
        Every image will be converted to five different resolutions. From 300pixel to 1920pixel width.
        <br>Depending on screen width a suitable image will be selected by algorithm.
        <br>It brings faster loading performance for frontend users viewing your gallery.
        <br><br>Converting images can take some time, especially for images higher then 3MB.
        <br>In general it is recommended not to add more then 10 images at one go. </div>

    <?php
}

echo "</div>";


echo "</td>";

echo "<td align='center'><div>";

if ($_POST['contest_gal1ery_create_zip']==true or ($_POST['chooseAction1'] == 4 and ($_POST['informId']==true or $_POST['resetInformId']==true))) {


    $allPics=array();
    //$pfad = $_SERVER['DOCUMENT_ROOT'];
    $uploadFolder = wp_upload_dir();

    $pfad = $uploadFolder['basedir'];
    $baseurl = $uploadFolder['baseurl'];

    $is_ssl = false;
    if(is_ssl()){
        $is_ssl = true;
    }

    if($is_ssl){
        if(strpos($baseurl,'http://')===0){
            $baseurl = str_replace( 'http://', 'https://', $baseurl );
        }
    }else{
        if(strpos($baseurl,'https://')===0){
            $baseurl = str_replace( 'https://', 'http://', $baseurl );
        }
    }

    if(!empty($_POST['contest_gal1ery_create_zip'])){

        $selectSQLall = $wpdb->get_results( "SELECT * FROM $tablename WHERE GalleryID = '$GalleryID' AND WpUpload>0");
        foreach($selectSQLall as $value){
                if(!empty($value->MultipleFiles) && $value->MultipleFiles!='""'){
                    $MultipleFilesUnserialized = unserialize($value->MultipleFiles);
                    if(!empty($MultipleFilesUnserialized)){//check for sure if really exists and unserialize went right, because might happen that "" was in database from earlier versions
                        foreach($MultipleFilesUnserialized as $order => $MultipleFile){
                            if($order==1 && empty($MultipleFile['isRealIdSource'])){
                                $image_url = $MultipleFile['guid'];
                            }else{
                                if(!empty($MultipleFile['isRealIdSource'])){
                                    $image_url = $wpdb->get_var("SELECT guid FROM $table_posts WHERE ID = '".$value->WpUpload."'");
                                }else{
                                    $image_url = $MultipleFile['guid'];
                                }
                            }
                            if($is_ssl){
                                if(strpos($image_url,'http://')===0){
                                    $image_url = str_replace( 'http://', 'https://', $image_url );
                                }
                            }else{
                                if(strpos($image_url,'https://')===0){
                                    $image_url = str_replace( 'https://', 'http://', $image_url );
                                }
                            }

                            $check = explode($baseurl,$image_url);
                            $dl_image_original = $pfad.$check[1];

                            $allPics[] = $dl_image_original;
                        }
                    }
                }else{

                    $image_url = $wpdb->get_var("SELECT guid FROM $table_posts WHERE ID = '".$value->WpUpload."'");

                    if($is_ssl){
                        if(strpos($image_url,'http://')===0){
                            $image_url = str_replace( 'http://', 'https://', $image_url );
                        }
                    }else{
                        if(strpos($image_url,'https://')===0){
                            $image_url = str_replace( 'https://', 'http://', $image_url );
                        }
                    }

                    $check = explode($baseurl,$image_url);
                    $dl_image_original = $pfad.$check[1];

                    $allPics[] = $dl_image_original;
                }

        }
    }


/*    if(@$_POST['chooseAction1'] == 4 and (@$_POST['informId']==true or @$_POST['resetInformId'])){

        //echo "2131242131243";

        $informId = @$_POST['informId'];
        $resetInformId = @$_POST['resetInformId'];

        $selectPICS = "SELECT * FROM $tablename WHERE ";

        //$wpdb->get_results( );

        foreach(@$informId as $key => $value){

            $selectPICS .= "id=$value or ";

        }

        foreach(@$resetInformId as $key => $value){

            $selectPICS .= "id=$value or ";

        }

        $selectPICS = substr($selectPICS,0,-4);

        //print_r($selectPICS);

        $selectPICSzip = $wpdb->get_results("$selectPICS");

    }*/


    $admin_email = get_option('admin_email');
    $adminHashedPass = $wpdb->get_var("SELECT user_pass FROM $wpUsers WHERE user_email = '$admin_email'");

    $code = $wpdb->base_prefix; // database prefix
    $code = md5($code.$adminHashedPass);


    if (file_exists(''.$pfad.'/contest-gallery/gallery-id-'.$GalleryID.'/'.$code.'_images_download.zip')) {
        unlink(''.$pfad.'/contest-gallery/gallery-id-'.$GalleryID.'/'.$code.'_images_download.zip');
    }
    if(cg_action_create_zip($allPics,''.$pfad.'/contest-gallery/gallery-id-'.$GalleryID.'/'.$code.'_images_download.zip')==false){
        die;
    }
    else{
        cg_action_create_zip($allPics,''.$pfad.'/contest-gallery/gallery-id-'.$GalleryID.'/'.$code.'_images_download.zip');
    }

    $downloadZipFileLink = $baseurl.'/contest-gallery/gallery-id-'.$GalleryID.'/'.$code.'_images_download.zip';
    echo '<div class="cg_shortcode_parent" id="cgDeleteZipFileHintContainer" style="margin-top: -18px;">
<div class="cg_shortcode_copy cg_shortcode_copy_gallery cg_tooltip" style="margin-top: 50px;"></div>
<input type="hidden" class="cg_shortcode_copy_text" value="'.$downloadZipFileLink.'">

<p style="text-align:center;width:180px;margin-top:23px;" id="cgDeleteZipFileHint">
<span class="cg-info-icon">READ INFO<br>BEFORE DOWNLOAD</span>
    <span class="cg-info-container cg-info-container-gallery-user" style="display: none;"><strong>Info Windows users!!!</strong><br>A <strong>ZIP file</strong> can not be opened by standard Windows Software.<br>You have to download for example WinRAR (which is free)<br>to be able to open a <strong>ZIP file</strong> in Windows.<br><br><strong>The generated zip file link is unique coded for your page</strong><br>
    You can use the zip file link for sharing<br>or<br>You can delete the zip file from server space</span>
<a href="'.$downloadZipFileLink.'">
<input type="submit" class="cg_backend_button cg_backend_button_general" value="Download zip file">
</a></p>';
    echo '<p style="text-align:center;width:180px;" ><form action="?page='.cg_get_version().'/index.php&option_id='.$GalleryID.'&edit_gallery=true" style="text-align: left;" method="POST" class="cg_load_backend_submit" >
<input type="hidden" name="cg_delete_zip" value="true">
<input class="cg_backend_button cg_backend_button_back" type="submit" value="Delete zip file">
</form>
</p></div>';

}
else {

    if(!empty($_POST['cg_delete_zip'])){
        $admin_email = get_option('admin_email');
        $adminHashedPass = $wpdb->get_var("SELECT user_pass FROM $wpUsers WHERE user_email = '$admin_email'");

        $code = $wpdb->base_prefix; // database prefix
        $code = md5($code.$adminHashedPass);
        $uploadFolder = wp_upload_dir();
        $pfad = $uploadFolder['basedir'];
        if(file_exists(''.$pfad.'/contest-gallery/gallery-id-'.$GalleryID.'/'.$code.'_images_download.zip')){
            unlink(''.$pfad.'/contest-gallery/gallery-id-'.$GalleryID.'/'.$code.'_images_download.zip');
        ?><script>alert('Zip file deleted');</script><?php
        }
    }

    if(!empty(['delete_data_csv'])){
        $admin_email = get_option('admin_email');
        $adminHashedPass = $wpdb->get_var("SELECT user_pass FROM $wpUsers WHERE user_email = '$admin_email'");
        $code = $wpdb->base_prefix; // database prefix
        $code = md5($code.$adminHashedPass);
        $dir = plugin_dir_path( __FILE__ );
        $dir = $dir.$code."_userdata.csv";
        if(file_exists($dir)){
            unlink($dir);
            ?><script>alert('CSV data file deleted.');</script><?php
        }
    }

    echo "<div style='margin-left:-10px;'><form method='POST' action='?page=".cg_get_version()."/index.php&option_id=$GalleryID&edit_gallery=true'    class='cg_load_backend_submit'><input type='hidden' name='contest_gal1ery_create_zip' value='true' /><input class='cg_backend_button_gallery_action' type='submit' value='Zip all files' style='width:100%;margin-top: 10px;' /></form></a>";

    echo "<form method='POST' action='?page=".cg_get_version()."/index.php&option_id=$GalleryID&cg_export_votes=true'><input type='hidden' name='cg_export_votes' value='true' /><input type='hidden' name='cg_export_votes_all' value='true' /><input type='hidden' name='cg_option_id' value='$GalleryID' /><input class='cg_backend_button_gallery_action' type='submit' value='Export all votes' style='width:100%; margin-top: 22px;' /></form></a>";

    echo "<div style='padding-top:2px;position: relative;'><span class=\"cg-info-icon\" style='font-weight:bold;'>info</span>
    <span class=\"cg-info-container cg-info-container-gallery-user\" style=\"top: 25px; margin-left: -125px; display: none;\">CSV file will be exported separated by semicolon ( ; )<br>For every image votes are also visible under \"Show votes\" in this area.<br>Votes can be also removed in \"Show votes\".</span>
    </div>";

    echo "<form method='POST' action='?page=".cg_get_version()."/index.php&option_id=$GalleryID&edit_gallery=true'><input type='hidden' name='contest_gal1ery_post_create_data_csv' value='true' /><input class='cg_backend_button_gallery_action' type='submit' value='Export all fields and total rating'' style='width:100%;margin-top: 10px;' /></form></a>";

    echo "<div style='padding-top:2px;position: relative;'><span class=\"cg-info-icon\" style='font-weight:bold;'>info</span>
    <span class=\"cg-info-container cg-info-container-gallery-user\" style=\"top: 25px; margin-left: -125px; display: none;\">CSV file will be exported separated by semicolon ( ; )<br></span>
    </div>";

echo "</div>";

}
echo "</div></td>";

echo "<td align='center'>
<div id='cgResetAllInformed'>";

echo "<form method='POST' action='?page=".cg_get_version()."/index.php&option_id=$GalleryID&edit_gallery=true'  class='cg_load_backend_submit cg_load_backend_submit_form_submit cg_reset_all_informed'>
<input type='submit' class='cg_backend_button_gallery_action' value='Reset all informed' />";
echo "<input type='hidden'  name='reset_all' value='true'>";
echo "</form></a>";
echo "<div style='padding-top:2px;'><span class=\"cg-info-icon\">info</span>
    <span class=\"cg-info-container cg-info-container-gallery-user\" style=\"top: 60px; margin-left: -235px; display: none;\">If \"Send this activation e-mail when activating users files\" is activated<br>Then users will be informed<br>All informed users can be reseted here<br>They will be informed again if entry will be activated again<br>Entry has to be deactivated before</span>
    </div>";
echo "</div></td>";


echo "</tr>";

echo "</table>";

///////////// SHOW Pictures of certain galery





?>