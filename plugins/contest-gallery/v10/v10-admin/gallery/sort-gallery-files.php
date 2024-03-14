<?php

$GalleryID = absint($_POST['GalleryID']);

global $wpdb;
$tablename = $wpdb->base_prefix . "contest_gal1ery";
$tablenamePostMeta = $wpdb->base_prefix . "postmeta";
$table_posts = $wpdb->prefix."posts";
$tablenameOptions = $wpdb->prefix . "contest_gal1ery_options";
$wp_upload_dir = wp_upload_dir();

// order was done order before
if(!empty($_POST['cg_position'])){

    $querySETrowPosition = 'UPDATE ' . $tablename . ' SET PositionNumber = CASE';
    $querySETaddRowPosition = ' ELSE PositionNumber END WHERE (id) IN (';

    foreach ($_POST['cg_position'] as $id => $position) {

        $id = absint($id);
        $position = absint($position);

        $querySETrowPosition .= " WHEN (id = $id) THEN $position";
        $querySETaddRowPosition .= "($id), ";

    }

    $querySETaddRowPosition = substr($querySETaddRowPosition, 0, -2);
    $querySETaddRowPosition .= ")";

    $querySETrowPosition .= $querySETaddRowPosition;
    $wpdb->query($querySETrowPosition);

    $picsSQL = $wpdb->get_results( "SELECT DISTINCT $table_posts.*, $tablename.* FROM $table_posts, $tablename WHERE 
            ($tablename.GalleryID='$GalleryID' AND $tablename.Active='1' and $table_posts.ID = $tablename.WpUpload) OR 
            ($tablename.GalleryID='$GalleryID' AND $tablename.Active='1' AND $tablename.WpUpload = 0) 
              GROUP BY $tablename.id  ORDER BY $tablename.id DESC");
    $imageArray = array();
    $galleryDBversion = $wpdb->get_var("SELECT Version FROM $tablenameOptions WHERE id='$GalleryID'");

    // add all json files and generate images array
    foreach($picsSQL as $object){
        $imageArray = cg_create_json_files_when_activating($GalleryID,$object,[],$wp_upload_dir,$imageArray,$galleryDBversion);
    }
    //cg_set_data_in_images_files_with_all_data($GalleryID,$imageArray);
}

$galleryFiles = $wpdb->get_results( "SELECT id, WpUpload, NamePic, ImgType, rThumb, MultipleFiles FROM $tablename WHERE GalleryID = '$GalleryID' ORDER BY PositionNumber ASC, id DESC");
$galleryFilesArray = [];
foreach ($galleryFiles as $galleryFile){
    $galleryFilesArray[$galleryFile->id] = [];
    $galleryFilesArray[$galleryFile->id]['id'] = $galleryFile->id;
/*    if($galleryFile->id==713){
        var_dump('898989');
        var_dump($galleryFile);
        var_dump(empty($galleryFile->MultipleFiles));
        var_dump($galleryFile->MultipleFiles);
    }*/
    if(empty($galleryFile->MultipleFiles) || $galleryFile->MultipleFiles=='""'){//check for sure if really exists and unserialize went right, because might happen that "" was in database from earlier versions
/*        if($galleryFile->id==713){
            var_dump('11112222');
            //var_dump($galleryFile);die;
        }*/
       $galleryFilesArray[$galleryFile->id]['ImgTypeToShow'] = $galleryFile->ImgType;
        $galleryFilesArray[$galleryFile->id]['ImgType'] = $galleryFile->ImgType;
        $galleryFilesArray[$galleryFile->id]['NamePic']  = $galleryFile->NamePic;
        $galleryFilesArray[$galleryFile->id]['rThumb']  = $galleryFile->rThumb;
        $galleryFilesArray[$galleryFile->id]['WpUploadToShow']  = $galleryFile->WpUpload;
    }else{
        $MultipleFilesUnserialized = unserialize($galleryFile->MultipleFiles);
        if(!empty($MultipleFilesUnserialized)){//check for sure if really exists and unserialize went right, because might happen that "" was in database from earlier versions
            $isAnotherMultipleSource = false;
            foreach($MultipleFilesUnserialized as $order => $MultipleFile){
                if($order==1 && empty($MultipleFile['isRealIdSource'])){
                    $galleryFilesArray[$galleryFile->id]['ImgTypeToShow'] = $MultipleFile['ImgType'];
                    $galleryFilesArray[$galleryFile->id]['ImgType'] = $galleryFile->ImgType;
                    $galleryFilesArray[$galleryFile->id]['NamePic']  = $galleryFile->NamePic;
                    $galleryFilesArray[$galleryFile->id]['rThumb']  = $galleryFile->rThumb;
                    $galleryFilesArray[$galleryFile->id]['WpUploadToShow']  = $MultipleFile['WpUpload'];
                    $isAnotherMultipleSource = true;
                    break;
                }
            }
            if(!$isAnotherMultipleSource){
                $galleryFilesArray[$galleryFile->id]['ImgTypeToShow'] = $galleryFile->ImgType;
                $galleryFilesArray[$galleryFile->id]['ImgType'] = $galleryFile->ImgType;
                $galleryFilesArray[$galleryFile->id]['NamePic']  = $galleryFile->NamePic;
                $galleryFilesArray[$galleryFile->id]['rThumb']  = $galleryFile->rThumb;
                $galleryFilesArray[$galleryFile->id]['WpUploadToShow']  = $galleryFile->WpUpload;
            }
        }
    }
}

// to select large sources for sorting
$collectForImages = '';
$collectForAltFiles = '';
foreach ($galleryFilesArray as $id => $galleryFile){
    if(cg_is_is_image($galleryFile['ImgTypeToShow'])){
/*        var_dump($galleryFile['id']);
        if($galleryFile['id']==713){
            var_dump('3333898111222989');
            var_dump($galleryFile);
        }*/
        if(empty($collectForImages)){
            $collectForImages .= "(meta_key = '_wp_attachment_metadata' AND post_id = '".$galleryFile['WpUploadToShow']."')";
        }else{
            $collectForImages .= " OR (meta_key = '_wp_attachment_metadata' AND post_id = '".$galleryFile['WpUploadToShow']."')";
        }
    }else{
        if(empty($collectForAltFiles)){
            $collectForAltFiles .= "(meta_key = '_wp_attached_file' AND post_id = '".$galleryFile['WpUploadToShow']."')";
        }else{
            $collectForAltFiles .= " OR (meta_key = '_wp_attached_file' AND post_id = '".$galleryFile['WpUploadToShow']."')";
        }
    }
}

/*echo "<br>";
echo "<br>";
echo $collectForImages;
echo "<br>";
echo "<br>";*/


$postMetaImageFiles = $wpdb->get_results( "SELECT post_id, meta_value FROM $tablenamePostMeta WHERE $collectForImages");

foreach ($postMetaImageFiles as $postMetaImageFile){
    $postMetaImageFileUnserialized = unserialize($postMetaImageFile->meta_value);
    $wp_upload_dir_file_dir = substr($postMetaImageFileUnserialized['file'],0,(int) strrpos($postMetaImageFileUnserialized['file'],'/'));
    if(!$wp_upload_dir_file_dir!=''){$wp_upload_dir_file_dir .='/';}
    foreach ($galleryFilesArray as $id => $galleryFileArray){
         if($galleryFileArray['WpUploadToShow']==$postMetaImageFile->post_id){
/*             if($postMetaImageFile->post_id=='3341'){
                 var_dump('77777');
                 echo "<pre>";
                 print_r($postMetaImageFileUnserialized);
                 echo "</pre>";
             }*/
            $galleryFilesArray[$id]['large'] = '/'.$wp_upload_dir_file_dir.'/'.(
                (!empty($postMetaImageFileUnserialized['sizes']) && !empty($postMetaImageFileUnserialized['sizes']['large']))
                    ? $postMetaImageFileUnserialized['sizes']['large']['file'] : substr($postMetaImageFileUnserialized['file'],strrpos($postMetaImageFileUnserialized['file'],'/'),strlen($postMetaImageFileUnserialized['file']))
                );
/*             if($postMetaImageFile->post_id=='3341'){
                var_dump($wp_upload_dir_file_dir );
                var_dump($galleryFilesArray[$id] );
                var_dump($galleryFilesArray[$id]['large'] );
                 die;
             }*/
        }
    }
}

$postAttachedFileNameFiles = $wpdb->get_results( "SELECT post_id, meta_value FROM $tablenamePostMeta WHERE $collectForAltFiles");

foreach ($postAttachedFileNameFiles as $postFile){
    foreach ($galleryFilesArray as $id => $galleryFileArray){
        if($galleryFileArray['WpUploadToShow']==$postFile->post_id){
            $galleryFilesArray[$id]['file'] = '/'.$postFile->meta_value;
            break;
        }
    }
}

echo "<div id='cgSortGalleryFilesContent'>";

echo "<div class='cg_preview_files_container '>";
$order = 1;
foreach ($galleryFilesArray as $WpUpload => $galleryFile){
/*    var_dump($galleryFile['id']);
    if($galleryFile['id']==713){
        var_dump('4444');
        var_dump($galleryFile);
    }*/
    if(cg_is_is_image($galleryFile['ImgTypeToShow'])){
        if(empty($galleryFile['large'])){
            $galleryFile['large'] = '';// kurzfristige Lösung  12.10.2022
        }
        echo "<div class='cg_backend_image_full_size_target_container'>";
        //echo $galleryFile['id'];
        echo '<div class="cg_backend_image_full_size_target_container_drag"></div>';
        echo '<div class="cg'.$galleryFile['rThumb'].'degree cg_backend_image_full_size_target" 
                    style="background: url('.$wp_upload_dir['baseurl'].$galleryFile['large'].') center center no-repeat;">
                    </div>';
        echo "<input type='hidden' class='cg_position' data-cg-real-id=".$galleryFile['id']."  name='cg_position[".$galleryFile['id']."]' value='$order' >";
        echo "</div>";
    }else{
        if(cg_is_alternative_file_type_video($galleryFile['ImgTypeToShow'])){
            echo "<div class='cg_backend_image_full_size_target_container cg_backend_image_full_size_target_container_video 
             cg_backend_image_full_size_target_container_video_".$galleryFile['ImgTypeToShow']."'>";
            //echo $galleryFile['id'];
            echo '<div class="cg_backend_image_full_size_target_container_drag"></div>';
            echo '<div class="cg_backend_image_full_size_target cg_backend_image_full_size_target_video" 
                    style="background: url('.$wp_upload_dir['baseurl'].((!empty($galleryFile['file'])) ? $galleryFile['file'] : '').') center center no-repeat;">
                    </div>';
            echo '<div class="cg_video_container"><video width="160">
                        <source src="'.$wp_upload_dir['baseurl'].((!empty($galleryFile['file'])) ? $galleryFile['file'] : '').'#t=0.001" type="video/'.$galleryFile['ImgTypeToShow'].'">
                        <source src="'.$wp_upload_dir['baseurl'].((!empty($galleryFile['file'])) ? $galleryFile['file'] : '').'#t=0.001" type="video/webm">
                        </video></div>';
            echo "<input type='hidden' class='cg_position' data-cg-real-id=".$galleryFile['id']."  name='cg_position[".$galleryFile['id']."]' value='$order' >";
            echo "</div>";
        }else if($galleryFile['ImgTypeToShow']=='con'){
            echo "<div class='cg_backend_image_full_size_target_container'>";
            //echo $galleryFile['id'];
            echo '<div class="cg_backend_image_full_size_target_container_drag"></div>';
            echo '<div class="cg_backend_image_full_size_target cg_backend_image_full_size_target_container_'.$galleryFile['ImgTypeToShow'].'" style="display: flex;align-items: center;margin-top: 4px;" >';
                        echo '<div class="cg_backend_image cg_backend_image_con_entry"><div style="font-size:12px;line-height:16px;font-weight:500;">Contact form entry<br>ID: '.$galleryFile['id'].'</div></div>';
                    echo '</div>';
                echo '<div class="cg_backend_image_full_size_target_name" >'.$galleryFile['NamePic'].'</div>';
                echo "<input type='hidden' class='cg_position' data-cg-real-id=".$galleryFile['id']." name='cg_position[".$galleryFile['id']."]' value='$order' >";
            echo "</div>";
        }else{
            echo "<div class='cg_backend_image_full_size_target_container'>";
            //echo $galleryFile['id'];
            echo '<div class="cg_backend_image_full_size_target_container_drag"></div>';
            echo '<div class="cg_backend_image_full_size_target cg_backend_image_full_size_target_container_'.$galleryFile['ImgTypeToShow'].'" ></div>';
            echo '<div class="cg_backend_image_full_size_target_name" >'.$galleryFile['NamePic'].'</div>';
            echo "<input type='hidden' class='cg_position' data-cg-real-id=".$galleryFile['id']." name='cg_position[".$galleryFile['id']."]' value='$order' >";
            echo "</div>";
        }
    }
    $order++;
}
echo "</div>";

echo "</div>";