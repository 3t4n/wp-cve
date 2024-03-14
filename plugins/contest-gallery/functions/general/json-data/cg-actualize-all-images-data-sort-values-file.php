<?php

if(!function_exists('cg_get_correct_rating_overview')){
    function cg_get_correct_rating_overview($GalleryID){

        $wp_upload_dir = wp_upload_dir();

        global $wpdb;
        $tablenameIP = $wpdb->prefix ."contest_gal1ery_ip";
        $tablenameComments = $wpdb->prefix . "contest_gal1ery_comments";

        $RatingOverview = $wpdb->get_results( $wpdb->prepare(
            "
                                        SELECT pid, RatingS, Rating
                                        FROM $tablenameIP
                                        WHERE GalleryID = %d AND (RatingS >=1 OR (Rating >= %d AND Rating <= %d))
                                        ORDER By pid DESC
                                    ",
            $GalleryID,1,10
        ) );

        $RatingOverviewArray = [];

        foreach($RatingOverview as $row){

            if(!isset($RatingOverviewArray[$row->pid])){
                $CountR = 0;
                $CountR1 = 0;
                $CountR2 = 0;
                $CountR3 = 0;
                $CountR4 = 0;
                $CountR5 = 0;
                $CountR6 = 0;
                $CountR7 = 0;
                $CountR8 = 0;
                $CountR9 = 0;
                $CountR10 = 0;
                $Rating = 0;
                $CountS = 0;
                $RatingOverviewArray[$row->pid] = [];
                if(!empty($row->RatingS)){
                    $CountS++;
                    $RatingOverviewArray[$row->pid]['CountS'] = $CountS;
                }

                if(!empty($row->Rating)){
                    $CountR++;
                    $RatingOverviewArray[$row->pid]['CountR'] = $CountR;
                    $Rating = $Rating + $row->Rating;
                    $RatingOverviewArray[$row->pid]['Rating'] = $Rating;
                    if($row->Rating==1){$CountR1++;$RatingOverviewArray[$row->pid]['CountR1']=$CountR1;}
                    if($row->Rating==2){$CountR2++;$RatingOverviewArray[$row->pid]['CountR2']=$CountR2;}
                    if($row->Rating==3){$CountR3++;$RatingOverviewArray[$row->pid]['CountR3']=$CountR3;}
                    if($row->Rating==4){$CountR4++;$RatingOverviewArray[$row->pid]['CountR4']=$CountR4;}
                    if($row->Rating==5){$CountR5++;$RatingOverviewArray[$row->pid]['CountR5']=$CountR5;}
                    if($row->Rating==6){$CountR6++;$RatingOverviewArray[$row->pid]['CountR6']=$CountR6;}
                    if($row->Rating==7){$CountR7++;$RatingOverviewArray[$row->pid]['CountR7']=$CountR7;}
                    if($row->Rating==8){$CountR8++;$RatingOverviewArray[$row->pid]['CountR8']=$CountR8;}
                    if($row->Rating==9){$CountR9++;$RatingOverviewArray[$row->pid]['CountR9']=$CountR9;}
                    if($row->Rating==10){$CountR10++;$RatingOverviewArray[$row->pid]['CountR10']=$CountR10;}
                }
            }else{
                if(!empty($row->RatingS)){
                    $CountS++;
                    $RatingOverviewArray[$row->pid]['CountS'] = $CountS;
                }
                if(!empty($row->Rating)){
                    $CountR++;
                    $RatingOverviewArray[$row->pid]['CountR'] = $CountR;
                    $Rating = $Rating + $row->Rating;
                    $RatingOverviewArray[$row->pid]['Rating'] = $Rating;
                    if($row->Rating==1){$CountR1++;$RatingOverviewArray[$row->pid]['CountR1']=$CountR1;}
                    if($row->Rating==2){$CountR2++;$RatingOverviewArray[$row->pid]['CountR2']=$CountR2;}
                    if($row->Rating==3){$CountR3++;$RatingOverviewArray[$row->pid]['CountR3']=$CountR3;}
                    if($row->Rating==4){$CountR4++;$RatingOverviewArray[$row->pid]['CountR4']=$CountR4;}
                    if($row->Rating==5){$CountR5++;$RatingOverviewArray[$row->pid]['CountR5']=$CountR5;}
                    if($row->Rating==6){$CountR6++;$RatingOverviewArray[$row->pid]['CountR6']=$CountR6;}
                    if($row->Rating==7){$CountR7++;$RatingOverviewArray[$row->pid]['CountR7']=$CountR7;}
                    if($row->Rating==8){$CountR8++;$RatingOverviewArray[$row->pid]['CountR8']=$CountR8;}
                    if($row->Rating==9){$CountR9++;$RatingOverviewArray[$row->pid]['CountR9']=$CountR9;}
                    if($row->Rating==10){$CountR10++;$RatingOverviewArray[$row->pid]['CountR10']=$CountR10;}
                }
            }

        }

        $CommentsOverview = $wpdb->get_results( $wpdb->prepare(
            "
                                        SELECT pid, COUNT(*) as NumberOfRows
                                        FROM $tablenameComments
                                        WHERE GalleryID = %d
                                        GROUP By pid 
                                        ORDER By pid DESC
                                    ",
            $GalleryID
        ) );

        // before 16.0.0 data was saved in database
        $processedDatabaseComments = [];

        foreach($CommentsOverview as $row){
            if(!empty($RatingOverviewArray[$row->pid])){
                $processedDatabaseComments[$row->pid] = $row->NumberOfRows;
                $RatingOverviewArray[$row->pid]['CountC'] = $row->NumberOfRows;
            }else{
                $RatingOverviewArray[$row->pid] = [];
                $RatingOverviewArray[$row->pid]['CountC'] = $row->NumberOfRows;
                $processedDatabaseComments[$row->pid] = $row->NumberOfRows;
            }
        }

        // process now comments data since 16.0.0
        //$fileImageComment = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-comments/ids/'.$row->pid.'/'.$commentId.'.json';
        if(is_dir($wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-comments/ids')){
            $fileImageCommentDirs = glob($wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-comments/ids/*');
            if(count($fileImageCommentDirs)){
                foreach ($fileImageCommentDirs as $fileImageCommentDir){
                    $fileImageCommentDirFiles = glob($fileImageCommentDir.'/*.json');
                    $fileImageCommentDirCount = count($fileImageCommentDirFiles);

                    $fileImageCommentDirExploded = explode('/',$fileImageCommentDir);
                    $imageId = end($fileImageCommentDirExploded);

                    if(!empty($RatingOverviewArray[$imageId])){
                        $RatingOverviewArray[$imageId]['CountC'] = $fileImageCommentDirCount;
                    }else{
                        $RatingOverviewArray[$imageId] = [];
                        $RatingOverviewArray[$imageId]['CountC'] = $fileImageCommentDirCount;
                    }

                    // add database entries if existed
                    if(!empty($processedDatabaseComments[$imageId])){
                        $RatingOverviewArray[$imageId]['CountC'] = $fileImageCommentDirCount+$processedDatabaseComments[$imageId];
                    }

                    $countCtoReview = 0;

                    // added since 18.0.1 CommReview
                    foreach ($fileImageCommentDirFiles as $fileImageCommentDirFile){
                        $fileImageCommentDirFileArray = json_decode(file_get_contents($fileImageCommentDirFile),true);
                        if(!empty($fileImageCommentDirFileArray[key($fileImageCommentDirFileArray)]['Active']) && $fileImageCommentDirFileArray[key($fileImageCommentDirFileArray)]['Active']==2){
                            $countCtoReview++;
                        }
                    }

                    $RatingOverviewArray[$imageId]['CountCtoReview'] = $countCtoReview;

               }
            }
        }

        return $RatingOverviewArray;

    }
}

add_action('cg_actualize_all_images_data_sort_values_file','cg_actualize_all_images_data_sort_values_file');


if(!function_exists('cg_actualize_all_images_data_sort_values_file')){

    function cg_actualize_all_images_data_sort_values_file($GalleryID,$isActualizeInstant = false,$IsModernFiveStar=false, $isResetVotesViaManipulationOneStar = false, $isResetVotesViaManipulationMultipleStars = false){
        // actualize timestamp here every 20 seconds!
        $wp_upload_dir = wp_upload_dir();

        $actualizingFilePath = $wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-'.$GalleryID.'/json/cg-actualizing-all-images-sort-values-json-data-file.txt';

        if(!file_exists($actualizingFilePath) OR $isActualizeInstant OR filesize($actualizingFilePath) == 0) {

            if(file_exists($wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-gallery-sort-values-tstamp.json')){
                $tstampFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-gallery-sort-values-tstamp.json';
                $fp = fopen($tstampFile, 'r');
                $tstamp = json_decode(fread($fp, filesize($tstampFile)));
                fclose($fp);
            }else{
                $tstamp = time()-31;//then file has to be created or modified anyway!!!!!
            }

            $timeCheck = $tstamp + 30;

            if($timeCheck<time() OR $isActualizeInstant){

                if(!file_exists($actualizingFilePath) OR $isActualizeInstant){

                    // go for sure that not actualized in that time
                    if(!$isActualizeInstant){
                        sleep(1);
                    }

                    if(file_exists($wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-gallery-sort-values-tstamp.json')){
                        // go for sure that not actualized in that time
                        $tstampFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-gallery-sort-values-tstamp.json';
                        $fp = fopen($tstampFile, 'r');
                        $tstamp = json_decode(fread($fp, filesize($tstampFile)));
                        fclose($fp);
                        $timeCheck = $tstamp + 30;
                    }else{
                        $timeCheck = 0;
                    }

                    $RatingOverviewArray = cg_get_correct_rating_overview($GalleryID);

                    if($timeCheck<time() OR $isActualizeInstant){

                        // go for sure that not actualized in the moment
                        if(!file_exists($wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-'.$GalleryID.'/json/cg-actualizing-all-images-sort-values-json-data-file.txt') OR $isActualizeInstant){

                            // actualize
                            $fp = fopen($wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-'.$GalleryID.'/json/cg-actualizing-all-images-sort-values-json-data-file.txt', 'w');
                            // in old versions before in "some version" string 'cg-actualizing-all-images-sort-values-json-data' was put in
                            fwrite($fp, time());
                            fclose($fp);

                            $imageDataJsonFiles = glob($wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-data/*.json');

                            if(file_exists($wp_upload_dir['basedir'] . "/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-images-sort-values.json")){
                                $jsonFile = $wp_upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-images-sort-values.json";
                                $fp = fopen($jsonFile, 'r');
                                $allImagesArray = json_decode(fread($fp, filesize($jsonFile)),true);
                                fclose($fp);
                            }else{
                                $allImagesArray = array();
                            }

                            $collectExistingImageIDsArray = array();
                            foreach ($imageDataJsonFiles as $jsonFile) {

                                $fp = fopen($jsonFile, 'r');
                                $imageDataArray = json_decode(fread($fp, filesize($jsonFile)),true);
                                fclose($fp);

                                // get image id
                                $stringArray= explode('/image-data-',$jsonFile);
                                $subString = end($stringArray);
                                $imageId = substr($subString,0, -5);

                                if(!empty($RatingOverviewArray[$imageId])){
                                        $imageDataArray['CountC'] = (!empty($RatingOverviewArray[$imageId]['CountC']) ? $RatingOverviewArray[$imageId]['CountC'] : 0);
                                        $imageDataArray['CountCtoReview'] = (!empty($RatingOverviewArray[$imageId]['CountCtoReview']) ? $RatingOverviewArray[$imageId]['CountCtoReview'] : 0);
                                        $imageDataArray['CountS'] = (!empty($RatingOverviewArray[$imageId]['CountS']) ? $RatingOverviewArray[$imageId]['CountS'] : 0);
                                        $imageDataArray['CountR'] = (!empty($RatingOverviewArray[$imageId]['CountR']) ? $RatingOverviewArray[$imageId]['CountR'] : 0);
                                        $imageDataArray['Rating'] = (!empty($RatingOverviewArray[$imageId]['Rating']) ? $RatingOverviewArray[$imageId]['Rating'] : 0);
                                        $imageDataArray['CountR1'] = (!empty($RatingOverviewArray[$imageId]['CountR1']) ? $RatingOverviewArray[$imageId]['CountR1'] : 0);
                                        $imageDataArray['CountR2'] = (!empty($RatingOverviewArray[$imageId]['CountR2']) ? $RatingOverviewArray[$imageId]['CountR2'] : 0);
                                        $imageDataArray['CountR3'] = (!empty($RatingOverviewArray[$imageId]['CountR3']) ? $RatingOverviewArray[$imageId]['CountR3'] : 0);
                                        $imageDataArray['CountR4'] = (!empty($RatingOverviewArray[$imageId]['CountR4']) ? $RatingOverviewArray[$imageId]['CountR4'] : 0);
                                        $imageDataArray['CountR5'] = (!empty($RatingOverviewArray[$imageId]['CountR5']) ? $RatingOverviewArray[$imageId]['CountR5'] : 0);
                                        $imageDataArray['CountR6'] = (!empty($RatingOverviewArray[$imageId]['CountR6']) ? $RatingOverviewArray[$imageId]['CountR6'] : 0);
                                        $imageDataArray['CountR7'] = (!empty($RatingOverviewArray[$imageId]['CountR7']) ? $RatingOverviewArray[$imageId]['CountR7'] : 0);
                                        $imageDataArray['CountR8'] = (!empty($RatingOverviewArray[$imageId]['CountR8']) ? $RatingOverviewArray[$imageId]['CountR8'] : 0);
                                        $imageDataArray['CountR9'] = (!empty($RatingOverviewArray[$imageId]['CountR9']) ? $RatingOverviewArray[$imageId]['CountR9'] : 0);
                                        $imageDataArray['CountR10'] = (!empty($RatingOverviewArray[$imageId]['CountR10']) ? $RatingOverviewArray[$imageId]['CountR10'] : 0);
                                }

                                $allImagesArray = cg_actualize_all_images_data_sort_values_file_set_array($allImagesArray,$imageDataArray,$imageId,$IsModernFiveStar,$isResetVotesViaManipulationOneStar,$isResetVotesViaManipulationMultipleStars);
                                $collectExistingImageIDsArray[] = $imageId;

                            }

                            //remove not existing image ids
                            foreach($allImagesArray as $key => $imageDataArray){
                                if(!in_array($key,$collectExistingImageIDsArray)){
                                    unset($allImagesArray[$key]);
                                }
                            }


                            $fp = fopen($wp_upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-images-sort-values.json", 'w');
                            fwrite($fp, json_encode($allImagesArray));
                            fclose($fp);

                            $tstampFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-gallery-sort-values-tstamp.json';
                            $fp = fopen($tstampFile, 'w');
                            fwrite($fp, json_encode(time()));
                            fclose($fp);

                            if(file_exists($wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-'.$GalleryID.'/json/cg-actualizing-all-images-sort-values-json-data-file.txt')){
                                unlink($wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-'.$GalleryID.'/json/cg-actualizing-all-images-sort-values-json-data-file.txt');
                            };

                            $frontendAddedImagesDir = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/frontend-added-votes';

                            if(is_dir($frontendAddedImagesDir)){
                                if(count(scandir($frontendAddedImagesDir)) != 2){
                                    cg_delete_files_and_folder($frontendAddedImagesDir,true);
                                }
                            }

                        };

                    }

                }
            }

        }else{// if something went wrong then file can be unlinked

            $fp = fopen($actualizingFilePath, 'r');
            $tstamp = json_decode(fread($fp, filesize($actualizingFilePath)));

            // then string was put in old versions before in "some version"
            if(!is_numeric($tstamp)){
                $fp = fopen($actualizingFilePath, 'w');
                fwrite($fp, time());
                fclose($fp);
            }else{
                if(time()>$tstamp+75){// then file can be deleted that processing can be done again!
                    unlink($actualizingFilePath);
                }
            }

        }

    }
}

