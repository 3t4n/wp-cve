<?php
add_action('cg_copy_pre7_gallery_images','cg_copy_pre7_gallery_images');
if(!function_exists('cg_copy_pre7_gallery_images')){
    function cg_copy_pre7_gallery_images($galleryToCopy){ // return $galleryToCopy WpUploads if there were no one before

        echo "<h2>Copying galleries created before version 7 can take a while. Please wait...</h2>";

        // wenn WpUpload, dann prüfen ob die WpUpload guid version existiert, diese nehmen, wenn nicht dann normale version des Bildes prüfen, wenn nicht dann die 1920er nehmen.
        // wenn wpUpload da ist, dann kein copy notwendig. // Nur als attachment nochmal hinzufügen damit sich die kleinen Bilder produzieren

        $wpUploadStandardDir = wp_upload_dir();

        foreach($galleryToCopy as $image){
            $galleryId = $image->GalleryID;break;
        }

        $uploadFolder = date('Y/m');

        $wpUploadDir = wp_upload_dir($uploadFolder,true);

        $time = date("Y-m-d H:i:s");
        $postContent = "Contest Gallery ID-$galleryId $time";

        foreach($galleryToCopy as $image){

            if(empty($image->WpUpload)){
                $originalSource = $wpUploadStandardDir['basedir']."/contest-gallery/gallery-id-$galleryId/".$image->Timestamp.'_'.$image->NamePic.'.'.$image->ImgType;
                if(file_exists($originalSource)){

                    if(empty($image->Width)){
                        list($widthOriginalImg, $heightOriginalImg) = getimagesize($originalSource); // Breite und H�he von original Image
                        $image->Width = $widthOriginalImg;
                        $image->Height = $heightOriginalImg;
                    }

                    $targetPath = $wpUploadDir['basedir']."/$uploadFolder/".$image->NamePic.'.'.$image->ImgType;
                    copy($originalSource,$targetPath);

                    $attachment = array(
                        'guid' => $wpUploadDir['baseurl']."/$uploadFolder/".$image->NamePic.'.'.$image->ImgType,
                        'post_mime_type' => 'image/'.$image->ImgType,
                        'post_title' => $image->NamePic,
                        'post_content' => $postContent,
                        'post_status' => 'inherit'
                    );

                    $attach_id = wp_insert_attachment( $attachment, $targetPath );
                    $imagenew = get_post( $attach_id );
                    $fullsizepath = get_attached_file( $imagenew->ID );
                    $attach_data = wp_generate_attachment_metadata( $attach_id, $fullsizepath );
                    wp_update_attachment_metadata( $attach_id, $attach_data );

                    $image->WpUpload = $attach_id;

                }
                else{

                    $largeWidthSource = $wpUploadStandardDir['basedir']."/contest-gallery/gallery-id-$galleryId/".$image->Timestamp.'_'.$image->NamePic.'-1920width.'.$image->ImgType;
                    $targetPath = $wpUploadDir['basedir']."/$uploadFolder/".$image->NamePic.'-1920width.'.$image->ImgType;
                    $targetUrl = $wpUploadDir['baseurl']."/$uploadFolder/".$image->NamePic.'-1920width.'.$image->ImgType;

                    if(!file_exists($largeWidthSource)){
                        $largeWidthSource = $wpUploadStandardDir['basedir']."/contest-gallery/gallery-id-$galleryId/".$image->Timestamp.'_'.$image->NamePic.'-1600width.'.$image->ImgType;
                        $targetPath = $wpUploadDir['basedir']."/$uploadFolder/".$image->NamePic.'-1600width.'.$image->ImgType;
                        $targetUrl= $wpUploadDir['baseurl']."/$uploadFolder/".$image->NamePic.'-1600width.'.$image->ImgType;
                        if(!file_exists($largeWidthSource)){
                            $largeWidthSource = $wpUploadStandardDir['basedir']."/contest-gallery/gallery-id-$galleryId/".$image->Timestamp.'_'.$image->NamePic.'-1024width.'.$image->ImgType;
                            $targetPath = $wpUploadDir['basedir']."/$uploadFolder/".$image->NamePic.'-1024width.'.$image->ImgType;
                            $targetUrl= $wpUploadDir['baseurl']."/$uploadFolder/".$image->NamePic.'-1024width.'.$image->ImgType;
                            if(!file_exists($largeWidthSource)){
                                $largeWidthSource = $wpUploadStandardDir['basedir']."/contest-gallery/gallery-id-$galleryId/".$image->Timestamp.'_'.$image->NamePic.'-624width.'.$image->ImgType;
                                $targetPath = $wpUploadDir['basedir']."/$uploadFolder/".$image->NamePic.'-624width.'.$image->ImgType;
                                $targetUrl= $wpUploadDir['baseurl']."/$uploadFolder/".$image->NamePic.'-624width.'.$image->ImgType;
                                if(!file_exists($largeWidthSource)){
                                    $largeWidthSource = $wpUploadStandardDir['basedir']."/contest-gallery/gallery-id-$galleryId/".$image->Timestamp.'_'.$image->NamePic.'-300width.'.$image->ImgType;
                                    $targetPath = $wpUploadDir['basedir']."/$uploadFolder/".$image->NamePic.'-300width.'.$image->ImgType;
                                    $targetUrl= $wpUploadDir['baseurl']."/$uploadFolder/".$image->NamePic.'-300width.'.$image->ImgType;
                                }
                            }
                        }
                    }

                    if(empty($image->Width)){
                        list($widthOriginalImg, $heightOriginalImg) = getimagesize($largeWidthSource); // Breite und H�he von original Image
                        $image->Width = $widthOriginalImg;
                        $image->Height = $heightOriginalImg;
                    }

                    copy($largeWidthSource,$targetPath);

                    $attachment = array(
                        'guid' =>$targetUrl,
                        'post_mime_type' => 'image/'.$image->ImgType,
                        'post_title' => $image->NamePic,
                        'post_content' => $postContent,
                        'post_status' => 'inherit'
                    );

                    $attach_id = wp_insert_attachment( $attachment, $targetPath );
                    $imagenew = get_post( $attach_id );
                    $fullsizepath = get_attached_file( $imagenew->ID );
                    $attach_data = wp_generate_attachment_metadata( $attach_id, $fullsizepath );
                    wp_update_attachment_metadata( $attach_id, $attach_data );

                    $image->WpUpload = $attach_id;

                }

            }

        }

        return $galleryToCopy;

    }
}