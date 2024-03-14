<?php

add_action('cg_set_data_in_images_files_with_all_data','cg_set_data_in_images_files_with_all_data');

if(!function_exists('cg_set_data_in_images_files_with_all_data')){

    function cg_set_data_in_images_files_with_all_data($galleryID,&$imagesDataArray,$isFromFrontendUpload = false){

        $uploadFolder = wp_upload_dir();

        if(!empty($imagesDataArray['imagesDataSortValuesArray'])){

            if(file_exists($uploadFolder['basedir'] . '/contest-gallery/gallery-id-'.$galleryID.'/json/cg-actualizing-all-images-sort-values-json-data-file.txt')){
                sleep(2);
            };

            if(file_exists($uploadFolder['basedir'] . '/contest-gallery/gallery-id-'.$galleryID.'/json/cg-actualizing-all-images-sort-values-json-data-file.txt')){
                sleep(3);
            };

            if(file_exists($uploadFolder['basedir'] . '/contest-gallery/gallery-id-' . $galleryID . '/json/' . $galleryID . '-images-sort-values.json')){
                $jsonFile = $uploadFolder['basedir'] . '/contest-gallery/gallery-id-' . $galleryID . '/json/' . $galleryID . '-images-sort-values.json';
                $fp = fopen($jsonFile, 'r');
                $allImagesDataSortValuesArray = json_decode(fread($fp, filesize($jsonFile)),true);
                fclose($fp);

                foreach($imagesDataArray['imagesDataSortValuesArray'] as $imageId => $imageSortValuesArray){

                    $allImagesDataSortValuesArray[$imageId] = $imageSortValuesArray;

                }

            }else{
                $allImagesDataSortValuesArray = $imagesDataArray['imagesDataSortValuesArray'];
            }

            $jsonFile = $uploadFolder['basedir'] . '/contest-gallery/gallery-id-' . $galleryID . '/json/' . $galleryID . '-images-sort-values.json';
            $fp = fopen($jsonFile, 'w');
            fwrite($fp, json_encode($allImagesDataSortValuesArray));
            fclose($fp);

            if(!$isFromFrontendUpload){// if from frontend upload then it is better it does not actualize this because some voting or commenting might done and it is better it will be reloaded on frontend page load
                $jsonFile = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$galleryID.'/json/'.$galleryID.'-gallery-sort-values-tstamp.json';
                $fp = fopen($jsonFile, 'w');
                fwrite($fp, time());
                fclose($fp);
            }

            // !IMPORTANT otherwise all values will be saved in one file
            unset($imagesDataArray['imagesDataSortValuesArray']);

        }

        if(empty($imagesDataArray) || !is_array($imagesDataArray)){// then data was corrected without having activated images
            $imagesDataArray = [];
        }

        $jsonFile = $uploadFolder['basedir'] . '/contest-gallery/gallery-id-' . $galleryID . '/json/' . $galleryID . '-images.json';
        file_put_contents($jsonFile,json_encode($imagesDataArray));

        $tstampFile = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$galleryID.'/json/'.$galleryID.'-gallery-tstamp.json';
        file_put_contents($tstampFile,json_encode(time()));

    }
}

