<?php
add_action('cg_create_exif_data_and_add_to_database','cg_create_exif_data_and_add_to_database');
if(!function_exists('cg_create_exif_data_and_add_to_database')){
    function cg_create_exif_data_and_add_to_database($imageId,$wpImageId){

        global $wpdb;

        $exifArray = cg_create_exif_data($wpImageId);

        if(empty($exifArray)){
            $imageDataExifSerialized = 0;
            $exifArray = 0;
        }else{
            $imageDataExifSerialized = serialize($exifArray);
        }

        // Set table names
        $tablename = $wpdb->prefix . "contest_gal1ery";
        $wpdb->update(
            "$tablename",
            array('Exif' => $imageDataExifSerialized),
            array('id' => $imageId),
            array('%s'),
            array('%d')
        );

        return $exifArray;

    }
}

add_action('cg_create_exif_data','cg_create_exif_data');
if(!function_exists('cg_create_exif_data')){
    function cg_create_exif_data($wpImageId){

        $exifDataForImage = array();

        if(function_exists('exif_read_data')){

            try {

                // simply the fastest processing way, to check the string end, instead do some database request again
                $fullFilePath = get_attached_file($wpImageId);
                $fullFilePathExploded = explode('.',$fullFilePath);
                $fullFilePathEnd = end($fullFilePathExploded);

                if(cg_is_alternative_file_type(trim(strtolower($fullFilePathEnd)))){
                    return $exifDataForImage;
                }

                $wpImageExifData = exif_read_data($fullFilePath,0,1);

                if(!empty($wpImageExifData['IFD0'])){

                    if(!empty($wpImageExifData['IFD0']['Model']) && !empty($wpImageExifData['IFD0']['Make'])){
                        $exifDataForImage['MakeAndModel'] = $wpImageExifData['IFD0']['Make'].' '.$wpImageExifData['IFD0']['Model'];
                    }

                    if(!empty($wpImageExifData['IFD0']['Model'])){
                        $exifDataForImage['Model'] = $wpImageExifData['IFD0']['Model'];
                    }

                    // future update eventually
                    /*if(!empty($wpImageExifData['IFD0']['DateTime'])){
                        $exifDataForImage['DateTime'] = $wpImageExifData['IFD0']['DateTime'];
                    }*/

                }

                if(!empty($wpImageExifData['COMPUTED'])){
                    if(!empty($wpImageExifData['COMPUTED']['ApertureFNumber'])){
                        $exifDataForImage['ApertureFNumber'] = $wpImageExifData['COMPUTED']['ApertureFNumber'];
                    }
                }

                if(!empty($wpImageExifData['EXIF'])){
                    if(!empty($wpImageExifData['EXIF']['ExposureTime'])){
                        $exifDataForImage['ExposureTime'] = $wpImageExifData['EXIF']['ExposureTime'];
                    }
                }

                if(!empty($wpImageExifData['EXIF'])){
                    if(!empty($wpImageExifData['EXIF']['ISOSpeedRatings'])){
                        if(!empty($wpImageExifData['EXIF']['ISOSpeedRatings'][0])){
                            $exifDataForImage['ISOSpeedRatings'] = $wpImageExifData['EXIF']['ISOSpeedRatings'][0];
                        }else if(!empty($wpImageExifData['EXIF']['ISOSpeedRatings'][1])){
                            $exifDataForImage['ISOSpeedRatings'] = $wpImageExifData['EXIF']['ISOSpeedRatings'][1];
                        }else{
                            $exifDataForImage['ISOSpeedRatings'] = $wpImageExifData['EXIF']['ISOSpeedRatings'];
                        }
                    }
                }

                if(!empty($wpImageExifData['EXIF'])){
                    if(!empty($wpImageExifData['EXIF']['FocalLength'])){

                        $focal_length = explode('/',$wpImageExifData["EXIF"]["FocalLength"]);

                        if(count($focal_length)){
                            $focal_length = intval($focal_length[0]/intval($focal_length[1]));
                            $exifDataForImage['FocalLength'] = $focal_length.'mm';
                        }
                    }
                }

                if(!empty($wpImageExifData['EXIF'])){
                    if(!empty($wpImageExifData['EXIF']['DateTimeOriginal'])){
                        $exifDataForImage['DateTimeOriginal'] = $wpImageExifData['EXIF']['DateTimeOriginal'];
                    }
                }

            }catch (Exception $e) {

                echo $e->getMessage();

            }

        }

        return $exifDataForImage;

    }
}


//Notice: Undefined offset: 1 in /home3/ralphpayne/public_html/wp-content/plugins/contest-gallery/v10/v10-admin/gallery/wp-uploader.php on line 45

//Notice: getimagesize(): Read error! in /home3/ralphpayne/public_html/wp-content/plugins/contest-gallery/v10/v10-admin/gallery/wp-uploader.php on line 64
