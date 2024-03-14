<?php
if(!function_exists('cg_create_no_script_html')){
    function cg_create_no_script_html($imageArray,$GalleryID){

        $wp_upload_dir = wp_upload_dir();

        ob_start();

        foreach($imageArray as $imageId => $imageData){


            if(!empty($imageData['large'])){
                $imageDataLargeSrc = $imageData['large'];
            }else if(!empty($imageData['guid'])){
                $imageDataLargeSrc = $imageData['guid'];
            }else{
                $imageDataLargeSrc = '';
            }

            $imageDataCaption = '';

            if(!empty($imageData['post_caption'])){
                $imageDataCaption = $imageData['post_caption'];
            }

            $imageDataAlt = '';

            if(!empty($imageData['post_alt'])){
                $imageDataAlt = $imageData['post_alt'];
            }

            $imageDataTitle = '';

            if(!empty($imageData['post_title'])){
                $imageDataTitle = $imageData['post_title'];
            }

            echo "<figure class='cg_noscript_figure'>\n";

            echo "<div class='cg_noscript_image' style='background:url($imageDataLargeSrc) no-repeat center center;'  role='img' aria-label='$imageDataAlt' title='$imageDataTitle' ></div>\n";
            echo "<figurecaption class='cg_noscript_figurecaption'>$imageDataCaption</figurecaption>\n";

            echo "</figure>\n";

        }

        $htmlContent = ob_get_clean();

// set image data, das ganze gesammelte
        $jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-noscript.html';
        $fp = fopen($jsonFile, 'w');
        fwrite($fp, $htmlContent);
        fclose($fp);

    }
}