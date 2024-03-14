<?php

add_action('cg_actualize_all_images_data_deleted_images','cg_actualize_all_images_data_deleted_images');

if(!function_exists('cg_actualize_all_images_data_deleted_images')){
    function cg_actualize_all_images_data_deleted_images($GalleryID){

        $wp_upload_dir = wp_upload_dir();
        $jsonFileDeleteImageIds = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-deleted-image-ids.json';

        if(file_exists($jsonFileDeleteImageIds)){

            $fp = fopen($jsonFileDeleteImageIds, 'r');
            $imageIds = json_decode(fread($fp, filesize($jsonFileDeleteImageIds)),true);
            fclose($fp);

            if(!empty($imageIds)){

                $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-images.json';
                $fp = fopen($jsonFile, 'r');
                $imagesArray = json_decode(fread($fp, filesize($jsonFile)),true);
                fclose($fp);

                $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-images-info-values.json';
                $fp = fopen($jsonFile, 'r');
                $imagesInfosArray = json_decode(fread($fp, filesize($jsonFile)),true);
                fclose($fp);

                $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-images-sort-values.json';
                $fp = fopen($jsonFile, 'r');
                $sortValuesArray = json_decode(fread($fp, filesize($jsonFile)),true);
                fclose($fp);

                foreach ($imageIds as $imageId){

                    $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-comments/image-comments-'.$imageId.'.json';
                    if(file_exists($jsonFile)){
                        unlink($jsonFile);
                    }
                    $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-data/image-data-'.$imageId.'.json';
                    if(file_exists($jsonFile)){
                        unlink($jsonFile);
                    }
                    $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/image-data/image-info-'.$imageId.'.json';
                    if(file_exists($jsonFile)){
                        unlink($jsonFile);
                    }

                    if(!empty($imagesArray)){
                        if(!empty($imagesArray[$imageId])){
                            unset($imagesArray[$imageId]);
                        }
                    }

                    if(!empty($imagesInfosArray)){
                        if(!empty($imagesInfosArray[$imageId])){
                            unset($imagesInfosArray[$imageId]);
                        }
                    }

                    if(!empty($sortValuesArray)){
                        if(!empty($sortValuesArray[$imageId])){
                            unset($sortValuesArray[$imageId]);
                        }
                    }

                }

                $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-images.json';
                $fp = fopen($jsonFile, 'w');
                fwrite($fp, json_encode($imagesArray));
                fclose($fp);

                $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-images-info-values.json';
                $fp = fopen($jsonFile, 'w');
                fwrite($fp, json_encode($imagesInfosArray));
                fclose($fp);

                $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-images-sort-values.json';
                $fp = fopen($jsonFile, 'w');
                fwrite($fp, json_encode($sortValuesArray));
                fclose($fp);


                $time = time();

                $tstampFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-gallery-image-info-tstamp.json';
                $fp = fopen($tstampFile, 'w');
                fwrite($fp, json_encode($time));
                fclose($fp);

                $tstampFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-gallery-sort-values-tstamp.json';
                $fp = fopen($tstampFile, 'w');
                fwrite($fp, json_encode($time));
                fclose($fp);

                $tstampFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-gallery-tstamp.json';
                $fp = fopen($tstampFile, 'w');
                fwrite($fp, json_encode($time));
                fclose($fp);

            }

            unlink($jsonFileDeleteImageIds);

        }

    }
}

