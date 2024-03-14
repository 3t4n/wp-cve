<?php
if (!empty($_POST['ipId'])) {

    $collect = '';
    $collectIds = array();

    $collectCountS = 0;
    $collectCountR = 0;
    $collectRating = 0; // sum of multiple stars ratingsecho

    $isRemoveFiveStar = false;
    $isRemoveOneStar = false;


    foreach ($_POST['ipId'] as $ipId => $ratingHeight) {

        if ($collect == '') {
            $collect .= "id = %d";
            $collectIds[] = $ipId;
        } else {
            $collect .= " OR id = %d";
            $collectIds[] = $ipId;
        }

    }

    $wpdb->query($wpdb->prepare(
        "DELETE FROM $tablename_ip WHERE $collect", $collectIds
    ));

}

if($imageData->Active==1){
    $objectRow = $wpdb->get_row( "SELECT DISTINCT $table_posts.*, $tablename.* FROM $table_posts, $tablename WHERE 
                                              ($tablename.GalleryID='$GalleryID' AND $tablename.id=$imageId and $table_posts.ID = $tablename.WpUpload)  OR 
                                              ($tablename.GalleryID='$GalleryID' AND $tablename.id=$imageId AND $tablename.WpUpload = 0) 
                                          GROUP BY $tablename.id ORDER BY $tablename.id DESC");
    $uploadFolder = wp_upload_dir();
    $thumbSizesWp = array();
    $thumbSizesWp['thumbnail_size_w'] = get_option("thumbnail_size_w");
    $thumbSizesWp['medium_size_w'] = get_option("medium_size_w");
    $thumbSizesWp['large_size_w'] = get_option("large_size_w");
    $imageArray = array();

    $imageArray = cg_create_json_files_when_activating($GalleryID,$objectRow,$thumbSizesWp,$uploadFolder,$imageArray);

// take care of order!
    //cg_set_data_in_images_files_with_all_data($GalleryID,$imageArray);
    //cg_json_upload_form_info_data_files($GalleryID,null);
}

