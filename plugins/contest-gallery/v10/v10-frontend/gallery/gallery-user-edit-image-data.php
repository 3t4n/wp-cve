<?php
if(!defined('ABSPATH')){exit;}

$galeryIDuser = sanitize_text_field($_REQUEST['galeryIDuser']);

if(!is_user_logged_in()){
    ?>
    <script data-cg-processing="true">

        var message = <?php echo json_encode('Please do not manipulate image data edit! (0)');?>;
        var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;

        cgJsClass.gallery.function.message.show(galeryIDuser,message);

    </script>
    <?php

    return;
}
else{
    $wp_get_current_user = wp_get_current_user();
    $WpUserIdLoggedIn = $wp_get_current_user->data->ID;
}


$_REQUEST = cg1l_sanitize_post($_REQUEST);
$_POST = cg1l_sanitize_post($_POST);

$galeryID = intval(sanitize_text_field($_REQUEST['gid']));
$GalleryID = $galeryID;
$pictureID = intval(sanitize_text_field($_REQUEST['pid']));
$userId = intval(sanitize_text_field($_REQUEST['uid']));
$galeryIDuser = sanitize_text_field($_REQUEST['galeryIDuser']);
$galleryHash = sanitize_text_field($_REQUEST['galleryHash']);
$galleryHashDecoded = wp_salt( 'auth').'---cngl1---'.$galeryIDuser;
$galleryHashToCompare = cg_hash_function('---cngl1---'.$galeryIDuser, $galleryHash);

if($WpUserIdLoggedIn!=$userId){
    ?>
    <script data-cg-processing="true">

        var message = <?php echo json_encode('Please do not manipulate image data edit! (1)');?>;
        var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;

        cgJsClass.gallery.function.message.show(galeryIDuser,message);

    </script>
    <?php

    return;
}
if (!is_numeric($pictureID) or !is_numeric($galeryID) or !is_numeric($userId) or ($galleryHash != $galleryHashToCompare)){
    ?>
    <script data-cg-processing="true">

        var message = <?php echo json_encode('Please do not manipulate image data edit! (2)');?>;
        var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;

        cgJsClass.gallery.function.message.show(galeryIDuser,message);

    </script>
    <?php

    return;
}
else {

    global $wpdb;

    $tablename = $wpdb->prefix ."contest_gal1ery";

    $isUserImage = $wpdb->get_var( $wpdb->prepare(
        "
        SELECT COUNT(*) AS UserImages
        FROM $tablename 
        WHERE id = %d and GalleryID = %d and WpUserId = %d and Active = %d
    ",
        $pictureID,$galeryID,$userId,1
    ) );

    if(empty($isUserImage)){

        ?>
        <script data-cg-processing="true">

            var message = <?php echo json_encode('Please do not manipulate image edit! (3)');?>;
            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;

            cgJsClass.gallery.function.message.show(galeryIDuser,message);

        </script>
        <?php

        return;

    }else{
        global $wp_version;
        $sanitize_textarea_field = ($wp_version<4.7) ? 'sanitize_text_field' : 'sanitize_textarea_field';

        $upload_dir = wp_upload_dir();
        $wp_upload_dir = wp_upload_dir();
        $jsonUpload = $wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-' . $galeryID . '/json';
        $jsonUploadImageData = $wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-' . $galeryID . '/json/image-data';
        $jsonUploadImageInfoDir = $wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-' . $galeryID . '/json/image-info';
        $jsonUploadImageCommentsDir = $wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-' . $galeryID . '/json/image-comments';

        $table_posts = $wpdb->prefix . "posts";
        $table_users = $wpdb->base_prefix . "users";
        $tablename = $wpdb->prefix . "contest_gal1ery";
        $tablenameOptions = $wpdb->prefix . "contest_gal1ery_options";
        $tablenameentries = $wpdb->prefix . "contest_gal1ery_entries";
        $tablename_categories = $wpdb->prefix . "contest_gal1ery_categories";
        $tablename_pro_options = $wpdb->prefix . "contest_gal1ery_pro_options";
        $tablename_comments = $wpdb->prefix . "contest_gal1ery_comments";
        $tablename_options_visual = $wpdb->prefix . "contest_gal1ery_options_visual";
        $tablename_form_input = $wpdb->prefix . "contest_gal1ery_f_input";

        $Field1IdGalleryView = $wpdb->get_var("SELECT Field1IdGalleryView FROM $tablename_options_visual WHERE GalleryID = $galeryID");

        $Field1IdGalleryViewToSelect = '';

        if(!empty($Field1IdGalleryView)){
            $Field1IdGalleryViewToSelect = " OR (GalleryID=$galeryID AND id=$Field1IdGalleryView)";
        }

        $uploadFormFields = $wpdb->get_results("SELECT * FROM $tablename_form_input WHERE GalleryID = $galeryID");

        $fieldsForSaveContentArray = array();

        foreach ($uploadFormFields as $field) {
            if (empty($fieldsForSaveContentArray[$field->id])) {
                $fieldsForSaveContentArray[$field->id] = array();
            }
            $fieldsForSaveContentArray[$field->id]['Field_Type'] = $field->Field_Type;
            $fieldsForSaveContentArray[$field->id]['Field_Order'] = $field->Field_Order;
            $fieldContent = unserialize($field->Field_Content);
            $fieldsForSaveContentArray[$field->id]['Field_Title'] = $fieldContent['titel'];
            if ($field->Field_Type == 'date-f') {
                $fieldsForSaveContentArray[$field->id]['Field_Format'] = $fieldContent['format'];
            }
        }

        $collectedFieldIds = '';
/*
        echo "<pre>";
        print_r($_REQUEST['cg-cat-id']);
        echo "</pre>";

        echo "<pre>";
        print_r($_REQUEST['cg-field-id']);
        echo "</pre>";*/

        if(!empty($_REQUEST['cg-field-id'])){
            foreach ($_REQUEST['cg-field-id'] as $fieldId => $value){
                $fieldId = intval(sanitize_text_field($fieldId));
                if(empty($collectedFieldIds)){
                    $collectedFieldIds .= "id = $fieldId";
                }else{
                    $collectedFieldIds .= " OR id = $fieldId";
                }
            }
        }

        $selectSQL = $wpdb->get_results( "SELECT * FROM $tablename_form_input WHERE (GalleryID = $galeryID AND ($collectedFieldIds)
                    AND (Field_Type = 'text-f' OR Field_Type = 'url-f' OR Field_Type = 'comment-f' OR Field_Type = 'select-f' OR Field_Type = 'date-f') AND Show_Slider = 1)$Field1IdGalleryViewToSelect" );

        $content = array();
        $content[$pictureID] = array();

        $isFromFrontendGalleryImageEdit = true;
        $isSetContent = false;
        $isSetCategory = false;

        if(count($selectSQL)){
            foreach ($selectSQL as $fieldObject){
                $content[$pictureID][$fieldObject->id] = array();
                if($fieldObject->Field_Type == 'text-f' OR $fieldObject->Field_Type == 'url-f' OR $fieldObject->Field_Type == 'select-f'){
                    $isSetContent=true;
                    if($fieldObject->Field_Type == 'select-f'){
                        if(sanitize_text_field($_REQUEST['cg-field-id'][$fieldObject->id])==='0'){
                            $content[$pictureID][$fieldObject->id]['short-text'] = '';
                        }else{
                            $content[$pictureID][$fieldObject->id]['short-text'] = sanitize_text_field($_REQUEST['cg-field-id'][$fieldObject->id]);
                        }
                    }else{
                        $content[$pictureID][$fieldObject->id]['short-text'] = sanitize_text_field($_REQUEST['cg-field-id'][$fieldObject->id]);
                    }
                }
                if($fieldObject->Field_Type == 'comment-f'){
                    $isSetContent=true;
                    // take care sanitize_textarea_field if long text
                    $content[$pictureID][$fieldObject->id]['long-text'] = sanitize_textarea_field($_REQUEST['cg-field-id'][$fieldObject->id]);
                }
                if($fieldObject->Field_Type == 'date-f'){
                    $isSetContent=true;
                    $content[$pictureID][$fieldObject->id]['date-field'] = sanitize_text_field($_REQUEST['cg-field-id'][$fieldObject->id]);
                }
            }
        }

/*
        echo "<pre>";
        print_r($content);
        echo "</pre>";*/

        if($isSetContent){
	        $infoPidsArray = [];
            include(__DIR__.'/../../v10-admin/gallery/change-gallery/1_content.php');
	        cg_json_upload_form_info_data_files_new($GalleryID,$infoPidsArray);
        }

        $jsonFile = $upload_dir['basedir']."/contest-gallery/gallery-id-".$galeryID."/json/image-data/image-data-".$pictureID.".json";
        $fp = fopen($jsonFile, 'r');
        $imageDataArray = json_decode(fread($fp, filesize($jsonFile)),true);
        fclose($fp);

        $categoryId = null;

        if($_REQUEST['cg-cat-id']===0 OR $_REQUEST['cg-cat-id']==='0' OR !empty($_REQUEST['cg-cat-id'])){
            $categoryId = intval(sanitize_text_field($_REQUEST['cg-cat-id']));
            // then must be category changes
            if($categoryId != $imageDataArray['Category']){
                $wpdb->query($wpdb->prepare(
                    "
				UPDATE $tablename SET Category = %d WHERE GalleryID = %d AND id = %d
			",
                    $categoryId,$galeryID,$pictureID
                ));
                $imageDataArray['Category'] = $categoryId;
                $isSetCategory = true;
            }
        }

        if($isSetCategory){
            cg_edit_images($galeryID,$pictureID,$imageDataArray,$isSetCategory);
        }

	    $uploadFolder = wp_upload_dir();
	    $filePath = $uploadFolder['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-info/image-info-".$pictureID.".json";

        if(file_exists($filePath)){
	        $imageInfoFileDataArray = json_decode(file_get_contents($filePath),true);
        }
        if(empty($imageInfoFileDataArray)){
            $imageInfoFileDataArray = null;
        }

        ?>
        <script data-cg-processing="true">
            //  alert(1);
            var gid = <?php echo json_encode($galeryIDuser);?>;
            var realId = <?php echo json_encode($pictureID);?>;
            var data = <?php echo json_encode($imageInfoFileDataArray);?>;
            var catId = <?php echo json_encode($categoryId);?>;

            cgJsClass.gallery.info.setInfoFromEditImageData(gid,realId,data,catId);
            var galeryIDuser = <?php echo json_encode($galeryIDuser);?>;

            cgJsClass.gallery.function.message.show(galeryIDuser,cgJsClass.gallery.language[gid].DataSaved);

        </script>
        <?php

    }

}

?>



