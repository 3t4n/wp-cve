<?php

add_action('cg_check_and_repair_image_file_data','cg_check_and_repair_image_file_data');

if(!function_exists('cg_check_and_repair_image_file_data')){
    function cg_check_and_repair_image_file_data($GalleryID,$imageId,$ratingFileData,$IsModernFiveStar,$isFromPageLoad = false,$jsonImagesDataReserve=[]){
        $wp_upload_dir = wp_upload_dir();

        $isRepair = false;
        if(!isset($ratingFileData['id'])){$isRepair = true;}
        if(!isset($ratingFileData['Rating'])){$isRepair = true;}
        if(!isset($ratingFileData['CountC'])){$isRepair = true;}
        if(!isset($ratingFileData['CountR'])){$isRepair = true;}
        if(!isset($ratingFileData['CountS'])){$isRepair = true;}
        if(!isset($ratingFileData['addCountS'])){$isRepair = true;}
        if(!isset($ratingFileData['addCountR1'])){$isRepair = true;}
        if(!isset($ratingFileData['addCountR2'])){$isRepair = true;}
        if(!isset($ratingFileData['addCountR3'])){$isRepair = true;}
        if(!isset($ratingFileData['addCountR4'])){$isRepair = true;}
        if(!isset($ratingFileData['addCountR5'])){$isRepair = true;}
        if(!isset($ratingFileData['addCountR6'])){$isRepair = true;}
        if(!isset($ratingFileData['addCountR7'])){$isRepair = true;}
        if(!isset($ratingFileData['addCountR8'])){$isRepair = true;}
        if(!isset($ratingFileData['addCountR9'])){$isRepair = true;}
        if(!isset($ratingFileData['addCountR10'])){$isRepair = true;}
        if(!isset($ratingFileData['CountR1'])){$isRepair = true;}
        if(!isset($ratingFileData['CountR2'])){$isRepair = true;}
        if(!isset($ratingFileData['CountR3'])){$isRepair = true;}
        if(!isset($ratingFileData['CountR4'])){$isRepair = true;}
        if(!isset($ratingFileData['CountR5'])){$isRepair = true;}
        if(!isset($ratingFileData['CountR6'])){$isRepair = true;}
        if(!isset($ratingFileData['CountR7'])){$isRepair = true;}
        if(!isset($ratingFileData['CountR8'])){$isRepair = true;}
        if(!isset($ratingFileData['CountR9'])){$isRepair = true;}
        if(!isset($ratingFileData['CountR10'])){$isRepair = true;}
        if(!isset($ratingFileData['Category'])){$isRepair = true;}

        $checkForPageOnLoadArray = ['id','thumbnail','medium','large','full','post_date','post_content','post_title','post_name','post_caption','post_alt','guid','display_name','PositionNumber','Timestamp','NamePic','ImgType','GalleryID','Active','Winner','Informed','WpUpload','Width','Height','thumbnail','rSource','rThumb','Category','WpUserId','WpPage','WpPageUser','WpPageNoVoting','WpPageWinner'];

        if($isFromPageLoad){
            foreach ($checkForPageOnLoadArray as $value){
                if(!isset($ratingFileData[$value])){$isRepair = true;}
            }
        }

        if(!$isRepair){
            return $ratingFileData;
        }else{

            global $wpdb;

            $tablename = $wpdb->prefix . "contest_gal1ery";
            $tablename_ip = $wpdb->prefix . "contest_gal1ery_ip";
            $tablename_comments = $wpdb->prefix . "contest_gal1ery_comments";

            $data = $wpdb->get_row( "SELECT addCountS, addCountR1, addCountR2, addCountR3, addCountR4, addCountR5, addCountR5, addCountR6, addCountR7, addCountR8, addCountR9, addCountR10, CountC, Category FROM $tablename WHERE id = $imageId");

            // can only happen if database was cleared but json files were not deleted and old files from old "installation" are still there
            if(empty($data)){
                return $ratingFileData;
            }

            $votingData = $wpdb->get_row("
          SELECT SUM(RatingS = 1) AS RatingSCount, SUM(Rating = 1) AS RatingR1Count, SUM(Rating = 2) AS RatingR2Count, SUM(Rating = 3) AS RatingR3Count, SUM(Rating = 4) AS RatingR4Count, SUM(Rating = 5) AS RatingR5Count, SUM(Rating = 6) AS RatingR6Count, SUM(Rating = 7) AS RatingR7Count, SUM(Rating = 8) AS RatingR8Count, SUM(Rating = 9) AS RatingR9Count, SUM(Rating = 10) AS RatingR10Count
          FROM $tablename_ip WHERE (RatingS = 1 OR Rating = 1 OR Rating = 2 OR Rating = 3 OR Rating = 4 OR Rating = 5 OR Rating = 6 OR Rating = 7 OR Rating = 8 OR Rating = 9 OR Rating = 10)  AND pid IN ($imageId) 
          ");

            // in case some data is NULL then it has to be repaired
            if(!isset($data->addCountS)){$data->addCountS = 0;}
            if(!isset($data->addCountR1)){$data->addCountR1 = 0;}
            if(!isset($data->addCountR2)){$data->addCountR2 = 0;}
            if(!isset($data->addCountR3)){$data->addCountR3 = 0;}
            if(!isset($data->addCountR4)){$data->addCountR4 = 0;}
            if(!isset($data->addCountR5)){$data->addCountR5 = 0;}
            if(!isset($data->addCountR6)){$data->addCountR6 = 0;}
            if(!isset($data->addCountR7)){$data->addCountR7 = 0;}
            if(!isset($data->addCountR8)){$data->addCountR8 = 0;}
            if(!isset($data->addCountR9)){$data->addCountR9 = 0;}
            if(!isset($data->addCountR10)){$data->addCountR10 = 0;}
            //if(!isset($data->CountC)){
            if(true){// simply repair always CountC (29.04.2022)
                $countCdatabase = $wpdb->get_var("SELECT COUNT(*) as NumberOfRows FROM $tablename_comments WHERE pid = '$imageId' LIMIT 1");
                $countCdirCount = 0;
                $countCdir = $wp_upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-comments/ids/$imageId";
                if(is_dir($countCdir)){
                    $countCdirCount = count(glob($countCdir.'/*.json'));
                }
                $data->CountC = $countCdatabase+$countCdirCount;
            }
            if(empty($data->Category)){$data->Category = 0;}
            if(!isset($votingData->RatingSCount)){
                // for the moment do not remove outcommented  (29.04.2022)
                // $RatingSCount = $wpdb->get_var("SELECT COUNT(*) as NumberOfRows FROM $tablename_ip WHERE pid = '$imageId' AND RatingS = 1 LIMIT 1");
                $votingData->RatingSCount = 0;
            }
            if(!isset($votingData->RatingR1Count)){
                // for the moment do not remove outcommented  (29.04.2022)
                // $RatingR1Count = $wpdb->get_var("SELECT COUNT(*) as NumberOfRows FROM $tablename_ip WHERE pid = '$imageId' AND Rating = 1 LIMIT 1");
                $votingData->RatingR1Count = 0;
            }
            if(!isset($votingData->RatingR2Count)){
                // for the moment do not remove outcommented  (29.04.2022)
                // $RatingR2Count = $wpdb->get_var("SELECT COUNT(*) as NumberOfRows FROM $tablename_ip WHERE pid = '$imageId' AND Rating = 2 LIMIT 1");
                $votingData->RatingR2Count = 0;
            }
            if(!isset($votingData->RatingR3Count)){
                // for the moment do not remove outcommented  (29.04.2022)
                // $RatingR3Count = $wpdb->get_var("SELECT COUNT(*) as NumberOfRows FROM $tablename_ip WHERE pid = '$imageId' AND Rating = 3 LIMIT 1");
                $votingData->RatingR3Count = 0;
            }
            if(!isset($votingData->RatingR4Count)){
                // for the moment do not remove outcommented  (29.04.2022)
                // $RatingR4Count = $wpdb->get_var("SELECT COUNT(*) as NumberOfRows FROM $tablename_ip WHERE pid = '$imageId' AND Rating = 4 LIMIT 1");
                $votingData->RatingR4Count = 0;
            }
            if(!isset($votingData->RatingR5Count)){
                // for the moment do not remove outcommented  (29.04.2022)
                //$RatingR5Count = $wpdb->get_var("SELECT COUNT(*) as NumberOfRows FROM $tablename_ip WHERE pid = '$imageId' AND Rating = 5 LIMIT 1");
                $votingData->RatingR5Count = 0;
            }
            if(!isset($votingData->RatingR6Count)){
                // for the moment do not remove outcommented  (29.04.2022)
                // $RatingR6Count = $wpdb->get_var("SELECT COUNT(*) as NumberOfRows FROM $tablename_ip WHERE pid = '$imageId' AND Rating = 6 LIMIT 1");
                $votingData->RatingR6Count = 0;
            }
            if(!isset($votingData->RatingR7Count)){
                // $RatingR7Count = $wpdb->get_var("SELECT COUNT(*) as NumberOfRows FROM $tablename_ip WHERE pid = '$imageId' AND Rating = 7 LIMIT 1");
                $votingData->RatingR7Count = 0;
            }
            if(!isset($votingData->RatingR8Count)){
                // for the moment do not remove outcommented  (29.04.2022)
                // $RatingR8Count = $wpdb->get_var("SELECT COUNT(*) as NumberOfRows FROM $tablename_ip WHERE pid = '$imageId' AND Rating = 8 LIMIT 1");
                $votingData->RatingR8Count = 0;
            }
            if(!isset($votingData->RatingR9Count)){
                // for the moment do not remove outcommented  (29.04.2022)
                // $RatingR9Count = $wpdb->get_var("SELECT COUNT(*) as NumberOfRows FROM $tablename_ip WHERE pid = '$imageId' AND Rating = 9 LIMIT 1");
                $votingData->RatingR9Count = 0;
            }
            if(!isset($votingData->RatingR10Count)){
                // for the moment do not remove outcommented  (29.04.2022)
                // $RatingR10Count = $wpdb->get_var("SELECT COUNT(*) as NumberOfRows FROM $tablename_ip WHERE pid = '$imageId' AND Rating = 10 LIMIT 1");
                $votingData->RatingR10Count = 0;
            }

            $ratingFileData['id'] = $imageId;
            $ratingFileData['Rating'] = $votingData->RatingR1Count*1+$votingData->RatingR2Count*2+$votingData->RatingR3Count*3+$votingData->RatingR4Count*4+$votingData->RatingR5Count*5+$votingData->RatingR6Count*6+$votingData->RatingR7Count*7+$votingData->RatingR8Count*8+$votingData->RatingR9Count*9+$votingData->RatingR10Count*10;
            $ratingFileData['CountR'] = $votingData->RatingR1Count+$votingData->RatingR2Count+$votingData->RatingR3Count+$votingData->RatingR4Count+$votingData->RatingR5Count+$votingData->RatingR6Count+$votingData->RatingR7Count+$votingData->RatingR8Count+$votingData->RatingR9Count+$votingData->RatingR10Count;
            $ratingFileData['CountS'] = intval($votingData->RatingSCount);

            $ratingFileData['CountC'] = $data->CountC;

            // since 21.0.0
            $countCtoReview = 0;
            $dirImageComments = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-comments/ids/'.$imageId;
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
                    $ratingFileData['CountC'] = $countCtotal - $countCtoReview;
                }else{
                    $ratingFileData['CountC'] = 0;
                }
            }

            $ratingFileData['addCountS'] = intval($data->addCountS);
            $ratingFileData['addCountR1'] = intval($data->addCountR1);
            $ratingFileData['addCountR2'] = intval($data->addCountR2);
            $ratingFileData['addCountR3'] = intval($data->addCountR3);
            $ratingFileData['addCountR4'] = intval($data->addCountR4);
            $ratingFileData['addCountR5'] = intval($data->addCountR5);
            $ratingFileData['addCountR6'] = intval($data->addCountR6);
            $ratingFileData['addCountR7'] = intval($data->addCountR7);
            $ratingFileData['addCountR8'] = intval($data->addCountR8);
            $ratingFileData['addCountR9'] = intval($data->addCountR9);
            $ratingFileData['addCountR10'] = intval($data->addCountR10);

            if($IsModernFiveStar){
                $ratingFileData['CountR1'] = intval($votingData->RatingR1Count);
                $ratingFileData['CountR2'] = intval($votingData->RatingR2Count);
                $ratingFileData['CountR3'] = intval($votingData->RatingR3Count);
                $ratingFileData['CountR4'] = intval($votingData->RatingR4Count);
                $ratingFileData['CountR5'] = intval($votingData->RatingR5Count);
                $ratingFileData['CountR6'] = intval($votingData->RatingR6Count);
                $ratingFileData['CountR7'] = intval($votingData->RatingR7Count);
                $ratingFileData['CountR8'] = intval($votingData->RatingR8Count);
                $ratingFileData['CountR9'] = intval($votingData->RatingR9Count);
                $ratingFileData['CountR10'] = intval($votingData->RatingR10Count);
            }else{
                $ratingFileData['CountR1'] = 0;
                $ratingFileData['CountR2'] = 0;
                $ratingFileData['CountR3'] = 0;
                $ratingFileData['CountR4'] = 0;
                $ratingFileData['CountR5'] = 0;
                $ratingFileData['CountR6'] = 0;
                $ratingFileData['CountR7'] = 0;
                $ratingFileData['CountR8'] = 0;
                $ratingFileData['CountR9'] = 0;
                $ratingFileData['CountR10'] = 0;
            }
            $ratingFileData['id'] = $imageId;// to go sure if missing
            $ratingFileData['Category'] = intval($data->Category);// to go sure, intval this

            if($isFromPageLoad){
                if(!empty($jsonImagesDataReserve) && !empty($jsonImagesDataReserve[$imageId])){
                    foreach ($checkForPageOnLoadArray as $value){
                        if(isset($jsonImagesDataReserve[$imageId][$value])){
                            $ratingFileData[$value] = $jsonImagesDataReserve[$imageId][$value];
                        }
                    }
                }
                $jsonFile = $wp_upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/image-data/image-data-$imageId.json";
                file_put_contents($jsonFile,json_encode($ratingFileData));
            }

            return $ratingFileData;
        }



    }
}
