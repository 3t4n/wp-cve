<?php

add_action('cg_actualize_all_images_data_info_file','cg_actualize_all_images_data_info_file');

if(!function_exists('cg_actualize_all_images_data_info_file')){
    function cg_actualize_all_images_data_info_file($GalleryID){
        $wp_upload_dir = wp_upload_dir();

        $actualizingFilePath = $wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-'.$GalleryID.'/json/cg-actualizing-all-images-info-json-data-file.txt';

        if(!file_exists($actualizingFilePath)){

            // actualize timestamp here every 20 seconds!
            if(file_exists($wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-gallery-image-info-tstamp.json')){
                $tstampFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-gallery-image-info-tstamp.json';
                $fp = fopen($tstampFile, 'r');
                $tstamp = json_decode(fread($fp, filesize($tstampFile)));
                fclose($fp);
            }else{
                $tstamp = time()-61;//then file has to be created or modified anyway!!!!!
            }

            $timeCheck = $tstamp + 60;

            if($timeCheck<time()){

                if(!file_exists($actualizingFilePath)){

                    // go for sure that not actualized in that time
                    sleep(2);

                    // go for sure that not actualized in that time
                    if(file_exists($wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-gallery-image-info-tstamp.json')){
                        // go for sure that not actualized in that time
                        $tstampFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-gallery-image-info-tstamp.json';
                        $fp = fopen($tstampFile, 'r');
                        $tstamp = json_decode(fread($fp, filesize($tstampFile)));
                        fclose($fp);
                        $timeCheck = $tstamp + 60;
                    }else{
                        $timeCheck = 0;
                    }

                    if($timeCheck<time()){
                        // go for sure that not actualized in the moment
                        if(!file_exists($wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-'.$GalleryID.'/json/cg-actualizing-all-images-info-json-data-file.txt')){
                            //var_dump('real actualize');

                            // actualize
                            $fp = fopen($wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-'.$GalleryID.'/json/cg-actualizing-all-images-info-json-data-file.txt', 'w');
                            // in old versions before in 109900 or so, string 'cg-actualizing-all-images-info-json-data' was put in
                            fwrite($fp, time());
                            fclose($fp);

                            $imageInfoJsonFiles = glob($wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-info/*.json');

                            $allImagesInfoArray = array();

                            // do it by file system because it is faster that main function which do it over big query cg_actualize_all_images_data_sort_values_file
                            foreach ($imageInfoJsonFiles as $jsonFile) {

                                $hasEmailField = false;
                                $fieldIdToUnset = 0;

                                $fp = fopen($jsonFile, 'r');
                                $imageInfoDataArray = json_decode(fread($fp, filesize($jsonFile)),true);
                                fclose($fp);

                                if(count($imageInfoDataArray)){
                                    foreach($imageInfoDataArray as $fieldId => $imageInfo){
                                        if(!empty($imageInfo['field-type'])){
                                            if($imageInfo['field-type']=='email-f'){// unset e-mail field for sure, if exists
                                                $hasEmailField = true;
                                                $fieldIdToUnset = $fieldId;
                                            }
                                        }
                                    }
                                }
                                // get image id
                                $stringArray= explode('/image-info-',$jsonFile);
                                $subString = end($stringArray);
                                $imageId = substr($subString,0, -5);

                                if($hasEmailField){// unset e-mail field for sure, if exists
                                    unset($imageInfoDataArray[$fieldIdToUnset]);
                                    $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-info/image-info-'.$imageId.'.json';
                                    $fp = fopen($jsonFile, 'w');
                                    fwrite($fp, json_encode($imageInfoDataArray));
                                    fclose($fp);
                                }

                                $allImagesInfoArray[$imageId] = $imageInfoDataArray;
                            }


                            $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-images-info-values.json';
                            $fp = fopen($jsonFile, 'w');
                            fwrite($fp, json_encode($allImagesInfoArray));
                            fclose($fp);

                            $tstampFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-gallery-image-info-tstamp.json';
                            $fp = fopen($tstampFile, 'w');
                            fwrite($fp, json_encode(time()));
                            fclose($fp);

                            if(file_exists($wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-'.$GalleryID.'/json/cg-actualizing-all-images-info-json-data-file.txt')){
                                unlink($wp_upload_dir['basedir'] . '/contest-gallery/gallery-id-'.$GalleryID.'/json/cg-actualizing-all-images-info-json-data-file.txt');
                            };

                            $frontendAddedImagesDir = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/frontend-added-or-removed-images';

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

            // then string was put in old versions before in 109900 or so, have to be checked
            if(!is_numeric($tstamp)){
                $fp = fopen($actualizingFilePath, 'w');
                fwrite($fp, time());
                fclose($fp);
            }else{
                if(time()>$tstamp+60){// then file can be deleted that processing can be done again!
                    unlink($actualizingFilePath);
                }
            }

        }

    }
}

