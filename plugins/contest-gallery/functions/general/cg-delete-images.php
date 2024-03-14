<?php

add_action('cg_delete_images','cg_delete_images');
if(!function_exists('cg_delete_images')){
    function cg_delete_images($GalleryID,$deleteValuesArray,$deletedWpUploads = array(),$DeleteFromStorageIfDeletedInFrontend = false,$isConsecutiveDeletionOfDeletedWpUploads = false, $MultipleFilesToDelete = []){

        global $wpdb;

// Set table names
        $tablename = $wpdb->prefix . "contest_gal1ery";
        $tablenameEntries = $wpdb->prefix . "contest_gal1ery_entries";
        $tablenameComments = $wpdb->prefix . "contest_gal1ery_comments";
        $tablenameIp = $wpdb->prefix . "contest_gal1ery_ip";

// Set table names --- END

        /*	$imageUnlinkOrigin = @$_POST['imageUnlinkOrigin'];
            $imageUnlink300 = @$_POST['imageUnlink300'];
            $imageUnlink624 = @$_POST['imageUnlink624'];
            $imageUnlink1024 = @$_POST['imageUnlink1024'];
            $imageUnlink1600 = @$_POST['imageUnlink1600'];
            $imageUnlink1920 = @$_POST['imageUnlink1920'];
            $imageFbHTMLUnlink = @$_POST['imageFbHTMLUnlink'];*/

// Pics vom Server l�schen

// Wordpress Uploadordner bestimmen. Array wird zur�ck gegeben.
        $upload_dir = wp_upload_dir();
	    $imageArray = [];

	    // images.json was used in older versions before 22.0.0, irrelevant now, but still will checked in many paces, has to be removed in the fture
        $jsonFile = $upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-images.json";
	    if(file_exists($jsonFile)){
        $fp = fopen($jsonFile, 'r');
        $imageArray = json_decode(fread($fp, filesize($jsonFile)),true);
        fclose($fp);
	    }


        if(file_exists($upload_dir['basedir'] . "/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-images-info-values.json")){

            $jsonFile = $upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-images-info-values.json";
            $fp = fopen($jsonFile, 'r');
            $imagesInfoValuesArray = json_decode(fread($fp, filesize($jsonFile)),true);
            fclose($fp);

        }

        if(file_exists($upload_dir['basedir'] . "/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-images-sort-values.json")){
            $jsonFile = $upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-images-sort-values.json";
            $fp = fopen($jsonFile, 'r');
            $imagesSortValuesArray = json_decode(fread($fp, filesize($jsonFile)),true);
            fclose($fp);
        }

        if(!is_dir($upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/frontend-added-or-removed-images')){
            mkdir($upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/frontend-added-or-removed-images',0755,true);
        }

        foreach($deleteValuesArray as $key => $value){
            // important!!! do not remove!!! might come from $_POST['cg_delete'] and has to be absint for sure!!!
            // found by patchstack.com
            $value = absint($value);
            /*echo '<input type="hidden" disabled name="imageUnlinkOrigin[]" value="/contest-gallery/gallery-id-'.$GalleryID.'/'.$value->Timestamp.'_'.$value->NamePic.'.'.$value->ImgType.'" class="image-delete">';
            echo '<input type="hidden" disabled name="imageUnlink300[]" value="/contest-gallery/gallery-id-'.$GalleryID.'/'.$value->Timestamp.'_'.$value->NamePic.'-300width.'.$value->ImgType.'" class="image-delete">';
            echo '<input type="hidden" disabled name="imageUnlink624[]" value="/contest-gallery/gallery-id-'.$GalleryID.'/'.$value->Timestamp.'_'.$value->NamePic.'-624width.'.$value->ImgType.'" class="image-delete">';
            echo '<input type="hidden" disabled name="imageUnlink1024[]" value="/contest-gallery/gallery-id-'.$GalleryID.'/'.$value->Timestamp.'_'.$value->NamePic.'-1024width.'.$value->ImgType.'" class="image-delete">';
            echo '<input type="hidden" disabled name="imageUnlink1600[]" value="/contest-gallery/gallery-id-'.$GalleryID.'/'.$value->Timestamp.'_'.$value->NamePic.'-1600width.'.$value->ImgType.'" class="image-delete">';
            echo '<input type="hidden" disabled name="imageUnlink1920[]" value="/contest-gallery/gallery-id-'.$GalleryID.'/'.$value->Timestamp.'_'.$value->NamePic.'-1920width.'.$value->ImgType.'" class="image-delete">';
            echo '<input type="hidden" disabled name="imageFbHTMLUnlink[]" value="/contest-gallery/gallery-id-'.$GalleryID.'/'.$value->Timestamp.'_'.$value->NamePic.'.html" class="image-delete">';*/

            // simply create empty file for later check
            $jsonFile = $upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/frontend-added-or-removed-images/'.$value.'.txt';
            $fp = fopen($jsonFile, 'w');
            fwrite($fp, '');
            fclose($fp);

            $imageData = $wpdb->get_row( "SELECT * FROM $tablename WHERE id = '$value' ");

            if(file_exists($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/".$imageData->Timestamp."_".$imageData->NamePic.".".$imageData->ImgType."")){
                @unlink($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/".$imageData->Timestamp."_".$imageData->NamePic.".".$imageData->ImgType."");
            }

            if(file_exists($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/".$imageData->Timestamp."_".$imageData->NamePic."-300width.".$imageData->ImgType."")){
                @unlink($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/".$imageData->Timestamp."_".$imageData->NamePic."-300width.".$imageData->ImgType."");
            }

            if(file_exists($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/".$imageData->Timestamp."_".$imageData->NamePic."-624width.".$imageData->ImgType."")){
                @unlink($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/".$imageData->Timestamp."_".$imageData->NamePic."-624width.".$imageData->ImgType."");
            }

            if(file_exists($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/".$imageData->Timestamp."_".$imageData->NamePic."-1024width.".$imageData->ImgType."")){
                @unlink($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/".$imageData->Timestamp."_".$imageData->NamePic."-1024width.".$imageData->ImgType."");
            }

            if(file_exists($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/".$imageData->Timestamp."_".$imageData->NamePic."-1600width.".$imageData->ImgType."")){
                @unlink($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/".$imageData->Timestamp."_".$imageData->NamePic."-1600width.".$imageData->ImgType."");
            }

            if(file_exists($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/".$imageData->Timestamp."_".$imageData->NamePic."-1920width.".$imageData->ImgType."")){
                @unlink($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/".$imageData->Timestamp."_".$imageData->NamePic."-1920width.".$imageData->ImgType."");
            }

            /*			if(file_exists($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/".$imageData->Timestamp."_".$imageData->NamePic.".html")){
                            @unlink($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/".$imageData->Timestamp."_".$imageData->NamePic.".html");
                        }*/

            if(file_exists($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/".$imageData->Timestamp."_".$imageData->NamePic."413.html")){
                @unlink($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/".$imageData->Timestamp."_".$imageData->NamePic."413.html");
            }

            if(file_exists($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-data/image-data-".$value.".json")){
                unlink($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-data/image-data-".$value.".json");
            }
            if(file_exists($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-comments/image-comments-".$value.".json")){
                unlink($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-comments/image-comments-".$value.".json");
            }

            // remove possible comment files and comment folder
            $commentsFolder = $upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-comments/ids/".$value;
            if(is_dir($commentsFolder)){
                $commentsFiles = glob($commentsFolder.'/*');//  get all files for sure, als might not json files be there
                if(count($commentsFiles)){
                    foreach ($commentsFiles as $commentsFile) {
                        unlink($commentsFile);
                    }
                }
                rmdir($commentsFolder);
            }

            if(file_exists($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-info/image-info-".$value.".json")){
                unlink($upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-info/image-info-".$value.".json");
            }

            if(!empty($imageArray[$value])){
                unset($imageArray[$value]);
            }

            if(!empty($imagesInfoValuesArray)){
                if(!empty($imagesInfoValuesArray[$value])){
                    unset($imagesInfoValuesArray[$value]);
                }
            }

            if(!empty($imagesSortValuesArray)){
                if(!empty($imagesSortValuesArray[$value])){
                    unset($imagesSortValuesArray[$value]);
                }
            }

            if(!empty($imageData->WpPage)){
                wp_delete_post($imageData->WpPage,true);
            }
            if(!empty($imageData->WpPageUser)){
                wp_delete_post($imageData->WpPageUser,true);
            }
            if(!empty($imageData->WpPageNoVoting)){
                wp_delete_post($imageData->WpPageNoVoting,true);
            }
            if(!empty($imageData->WpPageWinner)){
                wp_delete_post($imageData->WpPageWinner,true);
            }

            $deleteQuery = 'DELETE FROM ' . $tablename . ' WHERE';
            $deleteQuery .= ' id = %d';

            $deleteEntries = 'DELETE FROM ' . $tablenameEntries . ' WHERE';
            $deleteEntries .= ' pid = %d';

            $deleteComments = 'DELETE FROM ' . $tablenameComments . ' WHERE';
            $deleteComments .= ' pid = %d';

            $deleteRating = 'DELETE FROM ' . $tablenameIp . ' WHERE';
            $deleteRating .= ' pid = %d';

            $deleteParameters = '';
            $deleteParameters .= $value;

            $wpdb->query( $wpdb->prepare(
                "
                    $deleteQuery
                ",
                $deleteParameters
            ));

            $wpdb->query( $wpdb->prepare(
                "
                    $deleteEntries
                ",
                $deleteParameters
            ));

            $wpdb->query( $wpdb->prepare(
                "
                    $deleteComments
                ",
                $deleteParameters
            ));

            $wpdb->query( $wpdb->prepare(
                "
                    $deleteRating
                ",
                $deleteParameters
            ));

            if(((!empty($_POST['cgDeleteOriginalImageSourceAlso']) OR $DeleteFromStorageIfDeletedInFrontend) AND !$isConsecutiveDeletionOfDeletedWpUploads) && !empty($imageData->WpUpload)){
                wp_delete_attachment($imageData->WpUpload);
                $deletedWpUploads[] = $imageData->WpUpload;
            }

        }

        if((!empty($MultipleFilesToDelete)) && ((!empty($_POST['cgDeleteOriginalImageSourceAlso']) OR $DeleteFromStorageIfDeletedInFrontend) AND !$isConsecutiveDeletionOfDeletedWpUploads)){
            foreach ($MultipleFilesToDelete as $id => $fileDataForPost){
                foreach ($fileDataForPost as $order => $fileData){
                    if(in_array($fileData['WpUpload'],$deletedWpUploads)===false){
                        wp_delete_attachment($fileData['WpUpload']);
                        $deletedWpUploads[] = $fileData['WpUpload'];
                    }
                }
            }
        }

        // korrekturskript wegen update 10.9.5.0.0 wo galery id nicht mitgechickt wurde und deswegen images nicht gelöscht worden sind

        // korrekturskript ENDE

	    // images.json was used in older versions before 22.0.0, irrelevant now, but still will checked in many paces, has to be removed in the fture
        if(empty($imageArray) || !is_array($imageArray)){// then data was corrected without having activated images
            $imageArray = [];
        }

        $jsonFile = $upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-images.json";
        file_put_contents($jsonFile, json_encode($imageArray));

        // !IMPORTANT otherwise indexed db will be not reloaded
        $jsonFile = $upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-gallery-tstamp.json";
        file_put_contents($jsonFile, json_encode(time()));

        if(file_exists($upload_dir['basedir'] . "/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-images-info-values.json")){
            $jsonFile = $upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-images-info-values.json";
            file_put_contents($jsonFile,json_encode($imagesInfoValuesArray));
        }

        if(file_exists($upload_dir['basedir'] . "/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-images-sort-values.json")){
            $jsonFile = $upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-images-sort-values.json";
            file_put_contents($jsonFile,json_encode($imagesSortValuesArray));
        }

        return $deletedWpUploads;

    }
}

add_action('cg_delete_images_of_deleted_wp_uploads','cg_delete_images_of_deleted_wp_uploads');
if(!function_exists('cg_delete_images_of_deleted_wp_uploads')){
    function cg_delete_images_of_deleted_wp_uploads($deletedWpUploads){

        global $wpdb;

        $tablename = $wpdb->prefix . "contest_gal1ery";

        // first delete multiple files and there if exists!!!!
        // if is realIdSource then will be not completly deleted!
        // replace query will be done additionally
        // since v18.0.0 has to be done
        $collect = "";

        /*        var_dump('$deletedWpUploads 1');

                echo "<pre>";
                print_r($deletedWpUploads);
                echo "</pre>";*/

        // realIdSource can not be anymore, because must be deleted before
        foreach ($deletedWpUploads as $value){
            if(empty($collect)){
                $collect .= "$tablename.MultipleFiles LIKE '%WpUpload\";i:$value;%'";
            }else{
                $collect .= " OR $tablename.MultipleFiles LIKE '%WpUpload\";i:$value;%'";
            }
        }

        $filesWithDeletedWpUploadsInMultipleFiles = $wpdb->get_results( "SELECT * FROM $tablename WHERE ($collect) ORDER BY GalleryID DESC, id DESC");

        $correctedRowObjects=[];

        /*var_dump($filesWithDeletedWpUploadsInMultipleFiles);
                echo "<pre>";
                print_r($filesWithDeletedWpUploadsInMultipleFiles);
                echo "</pre>";*/

        if(count($filesWithDeletedWpUploadsInMultipleFiles)){

            $queryInsertNewMultipleFiles = 'INSERT INTO  '.$tablename.' (id, MultipleFiles) VALUES ';
            $queryHasRealIdDeleted = 'INSERT INTO '.$tablename.' (id, NamePic, ImgType, WpUpload, Width, Height, rThumb, Exif) VALUES ';
            $queryHasRealIdDeletedCenterStringPart = '';

            foreach ($filesWithDeletedWpUploadsInMultipleFiles as $rowObject){

                $hasRealIdDeleted = false;

                $MultipleFilesNewArray =[];
                $MultipleFilesArray = unserialize($rowObject->MultipleFiles);
                $newOrder = 1;
                
                if(!empty($MultipleFilesArray)){//check for sure if really exists and unserialize went right, because might happen that "" was in database from earlier versions
                    foreach ($MultipleFilesArray as $order => $array){// als with realIdSource must be already deleted before
                        if(!empty($array['isRealIdSource']) && in_array($array['WpUpload'],$deletedWpUploads)){
                            $hasRealIdDeleted = true;
                        }
                        if(in_array($array['WpUpload'],$deletedWpUploads)){continue;}
                        $MultipleFilesNewArray[$newOrder] = $array;
                        $newOrder++;
                    }
                }

                /*                var_dump($MultipleFilesNewArray);

                                echo "<pre>";
                                print_r($MultipleFilesNewArray);
                                echo "</pre>";*/

                if(!empty($MultipleFilesNewArray)){
                    if($hasRealIdDeleted){
                        $rowObject->NamePic = $MultipleFilesNewArray[1]['NamePic'];
                        $rowObject->ImgType = $MultipleFilesNewArray[1]['ImgType'];
                        $rowObject->WpUpload = $MultipleFilesNewArray[1]['WpUpload'];
                        $rowObject->Width = $MultipleFilesNewArray[1]['Width'];
                        $rowObject->Height = $MultipleFilesNewArray[1]['Height'];
                        $rowObject->rThumb = $MultipleFilesNewArray[1]['rThumb'];
                        $rowObject->Exif = $MultipleFilesNewArray[1]['Exif'];
                        $rowObject->post_alt = $MultipleFilesNewArray[1]['post_alt'];
                        $rowObject->post_title = $MultipleFilesNewArray[1]['post_title'];
                        $rowObject->post_title = $MultipleFilesNewArray[1]['post_title'];
                        $rowObject->post_name = $MultipleFilesNewArray[1]['post_name'];
                        $rowObject->post_content = $MultipleFilesNewArray[1]['post_content'];
                        $rowObject->post_excerpt = $MultipleFilesNewArray[1]['post_excerpt'];
                        $rowObject->post_date = $MultipleFilesNewArray[1]['post_date'];

                        $correctedRowObjects[] = $rowObject;
                        $queryHasRealIdDeletedCenterStringPart .= "('$rowObject->id', '".($MultipleFilesNewArray[1]['NamePic'])."', '".($MultipleFilesNewArray[1]['ImgType'])."','".($MultipleFilesNewArray[1]['WpUpload'])."','".(intval($MultipleFilesNewArray[1]['Width']))."','".(intval($MultipleFilesNewArray[1]['Height']))."','".(intval($MultipleFilesNewArray[1]['rThumb']))."','".($MultipleFilesNewArray[1]['Exif'])."'),";
                    }

                    if(count($MultipleFilesNewArray)>1){
                        $MultipleFilesNewSerialized = serialize($MultipleFilesNewArray);
                        $queryInsertNewMultipleFiles .= "('$rowObject->id', '".($MultipleFilesNewSerialized)."'),";
                    }else{// then count($MultipleFilesNewArray) must be == 1
                        $queryInsertNewMultipleFiles .= "('$rowObject->id', ''),";
                    }
                }else{// then must be completely deleted, realIdSource and all additional files
                    $deletedWpUploads[] = $rowObject->id;
                }

            }


            $queryInsertNewMultipleFiles = substr($queryInsertNewMultipleFiles, 0, -1);
            $queryInsertNewMultipleFiles .= " ON DUPLICATE KEY UPDATE MultipleFiles = VALUES(MultipleFiles)";
            /*            echo "<br>";
                        var_dump('$queryInsertNewMultipleFiles');
                        var_dump($queryInsertNewMultipleFiles);*/
            $wpdb->query($queryInsertNewMultipleFiles);

            if(!empty($queryHasRealIdDeletedCenterStringPart)){
                $queryHasRealIdDeleted = substr($queryHasRealIdDeleted.$queryHasRealIdDeletedCenterStringPart, 0, -1);
                $queryHasRealIdDeleted .= " ON DUPLICATE KEY UPDATE NamePic = VALUES(NamePic), ImgType = VALUES(ImgType), WpUpload = VALUES(WpUpload), Width = VALUES(Width), Height = VALUES(Height),  rThumb = VALUES(rThumb), Exif = VALUES(Exif)";
                /*                echo "<br>";
                                var_dump('$queryHasRealIdDeleted');
                                var_dump($queryHasRealIdDeleted);*/
                $wpdb->query($queryHasRealIdDeleted);
            }

        }

        /*        var_dump('$deletedWpUploads 2');

                echo "<pre>";
                print_r($deletedWpUploads);
                echo "</pre>";*/

        // then delete rest wp uploads

        $collect = '';

        foreach ($deletedWpUploads as $value){
            if(empty($collect)){
                $collect .= 'WpUpload='.$value;
            }else{
                $collect .= ' OR WpUpload='.$value;
            }
        }

        // in case $_POST['cgDeleteOriginalImageSourceAlso'] is sent or $DeleteFromStorageIfDeletedInFrontend and frontend delete will be done, deleted wpuploads be returned,
        // so all entries in all galleries will be deleted from the image, after wp uploads were deleted from space
        $deletedImages = $wpdb->get_results( "SELECT id, GalleryID FROM $tablename WHERE ($collect) ORDER BY GalleryID DESC, id DESC");

        $deletedImagesSortedByGalleryIdArrayWithObjects = array();

        /*        var_dump('$deletedImages');
                echo "<pre>";
                print_r($deletedImages);
                echo "<pre>";*/

        if(count($deletedImages)){

            foreach ($deletedImages as $rowObject){
                if(empty($deletedImagesSortedByGalleryIdArrayWithObjects[$rowObject->GalleryID])){
                    $deletedImagesSortedByGalleryIdArrayWithObjects[$rowObject->GalleryID] = array();
                }
                $deletedImagesSortedByGalleryIdArrayWithObjects[$rowObject->GalleryID][$rowObject->id] = $rowObject->id;// $rowObject->id as key because same system as always
            }

            /*            var_dump('$deletedImagesSortedByGalleryIdArrayWithObjects');
                        echo "<pre>";
                        print_r($deletedImagesSortedByGalleryIdArrayWithObjects);
                        echo "<pre>";*/

            foreach ($deletedImagesSortedByGalleryIdArrayWithObjects as $GalleryID => $deleteValuesArray){
                cg_delete_images($GalleryID,$deleteValuesArray,array(),false,true);
            }

        }

        if(count($correctedRowObjects)){
            /*            var_dump('$correctedRowObjects');
                        echo "<pre>";
                        print_r($correctedRowObjects);
                        echo "<pre>";*/

            $correctedRowObjectsSortedByGalleryID = [];
            $ExifDataByRealIds = [];
            foreach ($correctedRowObjects as $rowObject){
                if(empty($rowObject->Active)){continue;}// get only active to set array in json files
                $ExifDataByRealIds[$rowObject->id] = $rowObject->Exif;
                if(empty($correctedRowObjectsSortedByGalleryID[$rowObject->GalleryID])){
                    $correctedRowObjectsSortedByGalleryID[$rowObject->GalleryID] = [];
                }
                $correctedRowObjectsSortedByGalleryID[$rowObject->GalleryID][] = $rowObject;
            }
            $thumbSizesWp = array();
            $thumbSizesWp['thumbnail_size_w'] = get_option("thumbnail_size_w");
            $thumbSizesWp['medium_size_w'] = get_option("medium_size_w");
            $thumbSizesWp['large_size_w'] = get_option("large_size_w");
            $wp_upload_dir = wp_upload_dir();

            /*            var_dump('$correctedRowObjectsSortedByGalleryID');
                        echo "<pre>";
                        print_r($correctedRowObjectsSortedByGalleryID);
                        echo "<pre>";*/

            foreach ($correctedRowObjectsSortedByGalleryID as $GalleryID => $rowObjectsArray){
                cg_set_json_data_of_row_objects($rowObjectsArray,$GalleryID,$wp_upload_dir,$thumbSizesWp,$ExifDataByRealIds);
            }

        }

    }
}



?>