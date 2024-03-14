<?php
add_action('cg_copy_fb_sites','cg_copy_fb_sites');
if(!function_exists('cg_copy_fb_sites')){
    function cg_copy_fb_sites($galleryId,$nextGalleryId){

        $wp_upload_dir = wp_upload_dir();
        $directoryPrev = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galleryId;
        $directoryNext = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$nextGalleryId;
        $galleryFiles = scandir($directoryPrev);

        // TAKE CARE! IMAGE ID WILL BE NOT CONSIDERE HERE! BUT IS REQUIRED FOR SITE FORWARDING!

        foreach ($galleryFiles as $item) {
            if(strpos($item,'.html')){

                copy($directoryPrev.'/'.$item,$directoryNext.'/'.$item);

                // change og:url value

                $handle = fopen($directoryNext.'/'.$item, "r");

                if ($handle) {

                    $newFileContent = '';
                    $isOgUrlPassed = false;
                    $isDataHrefPassed = false;

                    while (($line = fgets($handle)) !== false) {

                        if($isOgUrlPassed){
                            if(!$isDataHrefPassed){
                                if(strpos($line,'data-href')!==false){
                                    $isDataHrefPassed = true;
                                    $line = str_replace('gallery-id-'.$galleryId,'gallery-id-'.$nextGalleryId,$line);
                                }
                            }
                        }

                        if(!$isOgUrlPassed){
                            if(strpos($line,'og:url')!==false){
                                $isOgUrlPassed = true;
                                $line = str_replace('gallery-id-'.$galleryId,'gallery-id-'.$nextGalleryId,$line);
                            }
                        }

                        $newFileContent .= $line;

                    }

                    fclose($handle);

                    $fp = fopen($directoryNext.'/'.$item, 'w');
                    fwrite($fp, $newFileContent);
                    fclose($fp);

                }

            }
        }

    }
}