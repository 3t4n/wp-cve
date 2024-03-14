<?php

if(!function_exists('cg_create_json_files_when_activating')){
    function cg_create_json_files_when_activating($GalleryID,$rowObject,$thumbSizesWp = [],$uploadFolder = [],$imagesDataArray=null,$galleryDBversion = 0, $RatingOverviewArray = [], $ExifDataAlreadySet = []){

        if($imagesDataArray!=null){
            $imagesDataArray[$rowObject->id] = array();
        }else{

            $jsonFile = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-images.json';

            if(file_exists($jsonFile)){
                $fp = fopen($jsonFile, 'r');
                $imagesDataArray = json_decode(fread($fp, filesize($jsonFile)),true);
                $imagesDataArray[$rowObject->id] = array();
                fclose($fp);
            }else{
                $imagesDataArray = array();
                $imagesDataArray[$rowObject->id] = array();
            }

        }

        $dirImageComments = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-comments/ids/'.$rowObject->id;

        if(cg_is_alternative_file_type($rowObject->ImgType)){
            $guid = $rowObject->guid;
            $imageWidth = 0;
            $imageHeight = 0;
            $imgSrcThumb = '';
            $imgSrcMedium = '';
            $imgSrcLarge = '';
            $imgSrcFull = $guid;
            $post_alt = get_post_meta($rowObject->WpUpload,'_wp_attachment_image_alt',true);
            // added since version 20.0.0
            if(cg_is_alternative_file_type_video($rowObject->ImgType)){
                $fileData = wp_get_attachment_metadata($rowObject->WpUpload);
                $imageHeight = (!empty($fileData['height'])) ? $fileData['height'] : 0;
                $imageWidth = (!empty($fileData['width'])) ? $fileData['width'] : 0;
            }
        }else if($rowObject->ImgType=='con'){// then must be contact form entry
            $guid = '';
            $imageWidth = 300;
            $imageHeight = 200;
            $imgSrcThumb = '';
            $imgSrcMedium = '';
            $imgSrcLarge = '';
            $imgSrcFull = '';
            $rowObject->post_date = '';
            $rowObject->post_content = '';
            $rowObject->post_title = '';
            $rowObject->post_name = '';
            $rowObject->post_excerpt = '';
            $post_alt = '';
        }else{
            $post_alt = get_post_meta($rowObject->WpUpload,'_wp_attachment_image_alt',true);
            // posts fields
            $imgSrcThumb=wp_get_attachment_image_src($rowObject->WpUpload, 'thumbnail');
            $imgSrcThumb=$imgSrcThumb[0];
            $imgSrcMedium=wp_get_attachment_image_src($rowObject->WpUpload, 'medium');
            $imgSrcMedium=$imgSrcMedium[0];
            $imgSrcLarge=wp_get_attachment_image_src($rowObject->WpUpload, 'large');
            $imgSrcLarge=$imgSrcLarge[0];
            $imgSrcFull=wp_get_attachment_image_src($rowObject->WpUpload, 'full');
            $imgSrcFull=$imgSrcFull[0];
            if(!empty($rowObject->Width)){
                $imageWidth = $rowObject->Width;
                $imageHeight = $rowObject->Height;
            }else{
                $imageWidth = $imgSrcFull[1];
                $imageHeight = $imgSrcFull[2];
            }
            $guid = $imgSrcFull;
        }

/*        $imagesDataArray[$rowObject->id]['thumbnail_size_w'] = $thumbSizesWp['thumbnail_size_w'];
        $imagesDataArray[$rowObject->id]['medium_size_w'] = $thumbSizesWp['medium_size_w'];
        $imagesDataArray[$rowObject->id]['large_size_w'] = $thumbSizesWp['large_size_w'];*/

        $imagesDataArray[$rowObject->id]['id'] = $rowObject->id;
        $imagesDataArray[$rowObject->id]['thumbnail'] = $imgSrcThumb;
        $imagesDataArray[$rowObject->id]['medium'] = $imgSrcMedium;
        $imagesDataArray[$rowObject->id]['large'] = $imgSrcLarge;
        $imagesDataArray[$rowObject->id]['full'] = $imgSrcFull;

        $imagesDataArray[$rowObject->id]['post_date'] = $rowObject->post_date;
        $imagesDataArray[$rowObject->id]['post_content'] = $rowObject->post_content;
        $imagesDataArray[$rowObject->id]['post_title'] = $rowObject->post_title;
        $imagesDataArray[$rowObject->id]['post_name'] = $rowObject->post_name;
        $imagesDataArray[$rowObject->id]['post_caption'] = $rowObject->post_excerpt;
        $imagesDataArray[$rowObject->id]['post_alt'] = $post_alt;
        $imagesDataArray[$rowObject->id]['guid'] = $guid;

        // hier pauschal setzen
        $imagesDataArray[$rowObject->id]['display_name'] = '';

        $imageRatingArray = array();

/*        $imageRatingArray['thumbnail_size_w'] = $thumbSizesWp['thumbnail_size_w'];
        $imageRatingArray['medium_size_w'] = $thumbSizesWp['medium_size_w'];
        $imageRatingArray['large_size_w'] = $thumbSizesWp['large_size_w'];*/

        $imageRatingArray['id'] = $rowObject->id;
        $imageRatingArray['thumbnail'] = $imgSrcThumb;
        $imageRatingArray['medium'] = $imgSrcMedium;
        $imageRatingArray['large'] = $imgSrcLarge;
        $imageRatingArray['full'] = $imgSrcFull;

        $imageRatingArray['post_date'] = $rowObject->post_date;
        $imageRatingArray['post_content'] = $rowObject->post_content;
        $imageRatingArray['post_title'] = $rowObject->post_title;
        $imageRatingArray['post_name'] = $rowObject->post_name;
        $imageRatingArray['post_caption'] = $rowObject->post_excerpt;
        $imageRatingArray['post_alt'] = $post_alt;
        $imageRatingArray['guid'] = $guid;

        // tablename fields
        $imagesDataArray[$rowObject->id]['rowid'] = intval($rowObject->rowid);
        $imagesDataArray[$rowObject->id]['PositionNumber'] = intval($rowObject->PositionNumber);
        $imagesDataArray[$rowObject->id]['Timestamp'] = intval($rowObject->Timestamp);
        $imagesDataArray[$rowObject->id]['NamePic'] = $rowObject->NamePic;
        $imagesDataArray[$rowObject->id]['ImgType'] = $rowObject->ImgType;
        $imagesDataArray[$rowObject->id]['GalleryID'] = intval($rowObject->GalleryID);
        $imagesDataArray[$rowObject->id]['Active'] = intval($rowObject->Active);
        $imagesDataArray[$rowObject->id]['Winner'] = intval($rowObject->Winner);
        $imagesDataArray[$rowObject->id]['Informed'] = intval($rowObject->Informed);
        $imagesDataArray[$rowObject->id]['WpUpload'] = intval($rowObject->WpUpload);
        $imagesDataArray[$rowObject->id]['Width'] = intval($imageWidth);
        $imagesDataArray[$rowObject->id]['Height'] = intval($imageHeight);
        $imagesDataArray[$rowObject->id]['rSource'] = intval($rowObject->rSource);
        $imagesDataArray[$rowObject->id]['rThumb'] = intval($rowObject->rThumb);
        $imagesDataArray[$rowObject->id]['Category'] = intval($rowObject->Category);
        $imagesDataArray[$rowObject->id]['WpUserId'] = intval($rowObject->WpUserId);
        $imagesDataArray[$rowObject->id]['WpPage'] = intval($rowObject->WpPage);
        $imagesDataArray[$rowObject->id]['WpPageUser'] = intval($rowObject->WpPageUser);
        $imagesDataArray[$rowObject->id]['WpPageNoVoting'] = intval($rowObject->WpPageNoVoting);
        $imagesDataArray[$rowObject->id]['WpPageWinner'] = intval($rowObject->WpPageWinner);

        $imageRatingArray['rowid'] = intval($rowObject->rowid);
        $imageRatingArray['PositionNumber'] = intval($rowObject->PositionNumber);
        $imageRatingArray['Timestamp'] = intval($rowObject->Timestamp);
        $imageRatingArray['NamePic'] = $rowObject->NamePic;
        $imageRatingArray['ImgType'] = $rowObject->ImgType;
        $imageRatingArray['Rating'] = intval($rowObject->Rating);
        $imageRatingArray['GalleryID'] = intval($rowObject->GalleryID);
        $imageRatingArray['Active'] = intval($rowObject->Active);
        $imageRatingArray['Winner'] = intval($rowObject->Winner);
        $imageRatingArray['Informed'] = intval($rowObject->Informed);
        $imageRatingArray['WpUpload'] = intval($rowObject->WpUpload);
        $imageRatingArray['Width'] = intval($imageWidth);
        $imageRatingArray['Height'] = intval($imageHeight);
        $imageRatingArray['rSource'] = intval($rowObject->rSource);
        $imageRatingArray['rThumb'] = intval($rowObject->rThumb);
        $imageRatingArray['Category'] = intval($rowObject->Category);
        $imageRatingArray['WpUserId']= intval($rowObject->WpUserId);
        $imageRatingArray['WpPage']= intval($rowObject->WpPage);
        $imageRatingArray['WpPageUser']= intval($rowObject->WpPageUser);
        $imageRatingArray['WpPageNoVoting']= intval($rowObject->WpPageNoVoting);
        $imageRatingArray['WpPageWinner']= intval($rowObject->WpPageWinner);

        // rating comment save here
        $imageRatingArray['CountC'] = intval($rowObject->CountC);
        // since 21.0.0
        $countCtoReview = 0;
        if(is_dir($dirImageComments)){
            $dirImageCommentsFiles = glob($dirImageComments.'/*.json');
            $countCtotal = count($dirImageCommentsFiles);
            foreach ($dirImageCommentsFiles as $dirImageCommentsFile){
                $dirImageCommentsFileData = json_decode(file_get_contents($dirImageCommentsFile),true);
                if(!empty($dirImageCommentsFileData[key($dirImageCommentsFileData)]['Active']) && $dirImageCommentsFileData[key($dirImageCommentsFileData)]['Active']==2 && empty($dirImageCommentsFileData[key($dirImageCommentsFileData)]['ReviewTstamp'])){
                    $countCtoReview++;
                }
            }
            if($countCtotal){
                $imageRatingArray['CountC'] = $countCtotal - $countCtoReview;
            }else{
                $imageRatingArray['CountC'] = 0;
            }
        }

        //  $imageRatingArray['CountC'] =intval($rowObject->CountCtoReview);
        $imageRatingArray['CountR'] = intval($rowObject->CountR);
        $imageRatingArray['CountS'] = intval($rowObject->CountS);
        $imageRatingArray['Rating'] = intval($rowObject->Rating);
        $imageRatingArray['addCountS'] = intval($rowObject->addCountS);
        $imageRatingArray['addCountR1'] = intval($rowObject->addCountR1);
        $imageRatingArray['addCountR2'] = intval($rowObject->addCountR2);
        $imageRatingArray['addCountR3'] = intval($rowObject->addCountR3);
        $imageRatingArray['addCountR4'] = intval($rowObject->addCountR4);
        $imageRatingArray['addCountR5'] = intval($rowObject->addCountR5);
        $imageRatingArray['addCountR6'] = intval($rowObject->addCountR6);
        $imageRatingArray['addCountR7'] = intval($rowObject->addCountR7);
        $imageRatingArray['addCountR8'] = intval($rowObject->addCountR8);
        $imageRatingArray['addCountR9'] = intval($rowObject->addCountR9);
        $imageRatingArray['addCountR10'] = intval($rowObject->addCountR10);
        $imageRatingArray['CountR1'] = intval($rowObject->CountR1);
        $imageRatingArray['CountR2'] = intval($rowObject->CountR2);
        $imageRatingArray['CountR3'] = intval($rowObject->CountR3);
        $imageRatingArray['CountR4'] = intval($rowObject->CountR4);
        $imageRatingArray['CountR5'] = intval($rowObject->CountR5);
        $imageRatingArray['CountR6'] = intval($rowObject->CountR6);
        $imageRatingArray['CountR7'] = intval($rowObject->CountR7);
        $imageRatingArray['CountR8'] = intval($rowObject->CountR8);
        $imageRatingArray['CountR9'] = intval($rowObject->CountR9);
        $imageRatingArray['CountR10'] = intval($rowObject->CountR10);

        if(!empty($RatingOverviewArray)){
            if(!empty($RatingOverviewArray[$rowObject->id])){
                $imageRatingArray['CountC'] = (!empty($RatingOverviewArray[$rowObject->id]['CountC']) ? $RatingOverviewArray[$rowObject->id]['CountC'] : 0);
                //$imageRatingArray['CountCtoReview'] = (!empty($RatingOverviewArray[$rowObject->id]['CountCtoReview']) ? $RatingOverviewArray[$rowObject->id]['CountCtoReview'] : 0);// added since 21.0.0
                $imageRatingArray['CountS'] = (!empty($RatingOverviewArray[$rowObject->id]['CountS']) ? $RatingOverviewArray[$rowObject->id]['CountS'] : 0);
                $imageRatingArray['CountR'] = (!empty($RatingOverviewArray[$rowObject->id]['CountR']) ? $RatingOverviewArray[$rowObject->id]['CountR'] : 0);
                $imageRatingArray['Rating'] = (!empty($RatingOverviewArray[$rowObject->id]['Rating']) ? $RatingOverviewArray[$rowObject->id]['Rating'] : 0);
                $imageRatingArray['CountR1'] = (!empty($RatingOverviewArray[$rowObject->id]['CountR1']) ? $RatingOverviewArray[$rowObject->id]['CountR1'] : 0);
                $imageRatingArray['CountR2'] = (!empty($RatingOverviewArray[$rowObject->id]['CountR2']) ? $RatingOverviewArray[$rowObject->id]['CountR2'] : 0);
                $imageRatingArray['CountR3'] = (!empty($RatingOverviewArray[$rowObject->id]['CountR3']) ? $RatingOverviewArray[$rowObject->id]['CountR3'] : 0);
                $imageRatingArray['CountR4'] = (!empty($RatingOverviewArray[$rowObject->id]['CountR4']) ? $RatingOverviewArray[$rowObject->id]['CountR4'] : 0);
                $imageRatingArray['CountR5'] = (!empty($RatingOverviewArray[$rowObject->id]['CountR5']) ? $RatingOverviewArray[$rowObject->id]['CountR5'] : 0);
                $imageRatingArray['CountR6'] = (!empty($RatingOverviewArray[$rowObject->id]['CountR6']) ? $RatingOverviewArray[$rowObject->id]['CountR6'] : 0);
                $imageRatingArray['CountR7'] = (!empty($RatingOverviewArray[$rowObject->id]['CountR7']) ? $RatingOverviewArray[$rowObject->id]['CountR7'] : 0);
                $imageRatingArray['CountR8'] = (!empty($RatingOverviewArray[$rowObject->id]['CountR8']) ? $RatingOverviewArray[$rowObject->id]['CountR8'] : 0);
                $imageRatingArray['CountR9'] = (!empty($RatingOverviewArray[$rowObject->id]['CountR9']) ? $RatingOverviewArray[$rowObject->id]['CountR9'] : 0);
                $imageRatingArray['CountR10'] = (!empty($RatingOverviewArray[$rowObject->id]['CountR10']) ? $RatingOverviewArray[$rowObject->id]['CountR10'] : 0);
            }
        }

       // var_dump($imageRatingArray['addCountR5']);

        // images rating data array will be build here!!!
        if(empty($imagesDataArray['imagesDataSortValuesArray'])){

            $imagesDataArray['imagesDataSortValuesArray'] = array();

        }
        $imagesDataArray['imagesDataSortValuesArray'][$rowObject->id] = array();

        $imagesDataArray['imagesDataSortValuesArray'] = cg_actualize_all_images_data_sort_values_file_set_array($imagesDataArray['imagesDataSortValuesArray'],$imageRatingArray,$rowObject->id,true);


        $correctDateTimeOriginal = false;
        $possibleCorrectDateTimeOriginal = false;
        if(!empty($galleryDBversion) && floatval($galleryDBversion)<12.23){
            $possibleCorrectDateTimeOriginal = true;
        }
        $hasExif = true;
        $exifDataArray = array();
        if($rowObject->Exif == '' or $rowObject->Exif == NULL){
            $hasExif = false;
        }else if($rowObject->Exif != '' && $rowObject->Exif != '0'){
            $exifDataArray = unserialize($rowObject->Exif);
            if(empty($exifDataArray['DateTimeOriginal']) && $possibleCorrectDateTimeOriginal){// this EXIF value DateTimeOriginal comes later, is for possible correction required
                $correctDateTimeOriginal = true;
            }
        }

        if(!empty($ExifDataAlreadySet) && isset($ExifDataAlreadySet[$rowObject->id])){
            // since 18.0.0 exif data in image-data... files will be not really used, can be removed later completely
            $imageRatingArray['Exif'] = $ExifDataAlreadySet[$rowObject->id];
        }else{
            // set exif data
            if((($hasExif==false && empty($exifDataArray)) OR $correctDateTimeOriginal) && !empty($rowObject->WpUpload)){
                // Exif data will be set in image-data-.json only in version before 18
                if(!empty($galleryDBversion) && floatval($galleryDBversion)<18){
                    // since 18.0.0 exif data in image-data... files will be not really used, can be removed later completely
                    $imageRatingArray['Exif'] = cg_create_exif_data_and_add_to_database($rowObject->id,$rowObject->WpUpload);
                }else{// but here will still be created and added to database
                    // since 18.0.0 exif data in image-data... files will be not really used, can be removed later completely
                    cg_create_exif_data_and_add_to_database($rowObject->id,$rowObject->WpUpload);
                }
            }else{
                $imageRatingArray['Exif'] = $exifDataArray;
            }
        }

        // set rating data
        $jsonFile = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-data/image-data-'.$rowObject->id.'.json';
        $fp = fopen($jsonFile, 'w');
        fwrite($fp, json_encode($imageRatingArray));
        fclose($fp);

        cg_create_comments_json_file_when_activating_image($uploadFolder,$GalleryID,$rowObject->id);

        // leeres Info file wird kreiert falls noch nicht existiert
        if(!is_file($uploadFolder['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-info/image-info-".$rowObject->id.".json")){

            $jsonFile = $uploadFolder['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-info/image-info-".$rowObject->id.".json";
            $fp = fopen($jsonFile, 'w');
            fwrite($fp, json_encode(array()));
            fclose($fp);

        }

        return $imagesDataArray;


    }
}

if(!function_exists('cg_create_comments_json_file_when_activating_image')){
    function cg_create_comments_json_file_when_activating_image($uploadFolder,$GalleryID,$imageId){

        $imageCommentsArray = array();
        $dirImageComments = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-comments/ids/'.$imageId;
        if(is_dir($dirImageComments)){
            // file can be used for future repair
            $dirImageCommentsFiles = glob($dirImageComments.'/*.json');
            if(count($dirImageCommentsFiles)){
                // important, so top down in json
                $dirImageCommentsFiles = array_reverse($dirImageCommentsFiles, true);
                foreach ($dirImageCommentsFiles as $dirImageCommentsFile){
                    $comment = json_decode(file_get_contents($dirImageCommentsFile));
                    // is json file, this why this foreach
                    foreach ($comment as $commentId => $commentData){
                        $imageCommentsArray[$commentId] = array();
                        $imageCommentsArray[$commentId]['date'] = date("Y/m/d, G:i",$commentData->timestamp);
                        $imageCommentsArray[$commentId]['timestamp'] = $commentData->timestamp;
                        $imageCommentsArray[$commentId]['name'] = $commentData->name;
                        $imageCommentsArray[$commentId]['comment'] = $commentData->comment;
                        $imageCommentsArray[$commentId]['Active'] = (!empty($commentData->Active)) ? $commentData->Active : '';
                        $imageCommentsArray[$commentId]['ReviewTstamp'] = (!empty($commentData->ReviewTstamp)) ? $commentData->ReviewTstamp : '';
                        if(!empty($commentData->WpUserId)){// better to check here for sure
                            $imageCommentsArray[$commentId]['WpUserId'] = $commentData->WpUserId;
                        }
                    }
                }
            }
        }

        global $wpdb;
        $tablename_comments = $wpdb->prefix . "contest_gal1ery_comments";

        // desc important so top down in json
        $imageComments = $wpdb->get_results("SELECT * FROM $tablename_comments WHERE pid = $imageId ORDER BY id DESC");

        if(count($imageComments)){

            foreach($imageComments as $comment){
                $imageCommentsArray[$comment->id] = array();
                $imageCommentsArray[$comment->id]['date'] = date("Y/m/d, G:i",$comment->Timestamp);
                $imageCommentsArray[$comment->id]['timestamp'] = $comment->Timestamp;
                $imageCommentsArray[$comment->id]['name'] = $comment->Name;
                $imageCommentsArray[$comment->id]['comment'] = $comment->Comment;
                $imageCommentsArray[$comment->id]['Active'] = $comment->Active;
                $imageCommentsArray[$comment->id]['ReviewTstamp'] = $comment->ReviewTstamp;
                $imageCommentsArray[$comment->id]['WpUserId'] = $comment->WpUserId;
            }

        }

        $jsonFile = $uploadFolder['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-comments/image-comments-".$imageId.".json";
        $fp = fopen($jsonFile, 'w');
        fwrite($fp, json_encode($imageCommentsArray));
        fclose($fp);

    }
}