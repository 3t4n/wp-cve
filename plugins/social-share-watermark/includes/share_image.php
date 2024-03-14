<?php


function SynthiaSoft_MakeWatermark($post_id)
{
    $setting          = get_option('fb_watermark_options');
    $attachments_id = get_post_thumbnail_id($post_id);
    $main_image = wp_get_original_image_path($attachments_id);
 
        if (!empty($main_image)) {                
                if ($image = SynthiaSoft_CreateImage($main_image)) {
                    
                    $thumb_width  = intval(1200);
                    $thumb_height = intval(630);
                    
                    $width  = imagesx($image);
                    $height = imagesy($image);
                    
                    $original_aspect = $width / $height;
                    $thumb_aspect    = $thumb_width / $thumb_height;
                    
                    if ($original_aspect >= $thumb_aspect) {
                        // If image is wider than thumbnail (in aspect ratio sense)
                        $new_height = $thumb_height;
                        $new_width  = $width / ($height / $thumb_height);
                    } else {
                        // If the thumbnail is wider than the image
                        $new_width  = $thumb_width;
                        $new_height = $height / ($width / $thumb_width);
                    }
                    
                    $thumb            = imagecreatetruecolor($thumb_width, $thumb_height);
                    //Fill with white because the source image can be a transparent PNG
                    $thumb_fill_color = apply_filters('fb_og_thumb_fill_color', array(
                        255,
                        255,
                        255
                    ));
                    imagefill($thumb, 0, 0, imagecolorallocate($thumb, $thumb_fill_color[0], $thumb_fill_color[1], $thumb_fill_color[2]));
                    
                    imagecopyresampled($thumb, $image, 0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
                        0 - ($new_height - $thumb_height) / 2, // Center the image vertically
                        0, 0, $new_width, $new_height, $width, $height);
                    
                    //Allow developers to change the thumb
                    $thumb = apply_filters('fb_og_thumb', $thumb);
                    
                    //Barra
                    if (trim($setting['fb_overlay']) != '') {
                        $watermark_url  = parse_url(apply_filters('fb_og_thumb_image', trim($setting['fb_overlay']), intval($post_id)));
                        $watermark_path = $_SERVER['DOCUMENT_ROOT'] . $watermark_url['path'];
                        $watermark      = SynthiaSoft_CreateImage($watermark_path);
                        $image_info     = getimagesize($watermark_path);
                        $offset         = 630 - $image_info['1'];
                        
                        imagecopy($thumb, $watermark, 0, $offset, 0, 0, intval($thumb_width), intval($thumb_height));
                    }
                    
                    if (has_action('fb_og_alternate_output')) {
                        
                        do_action('fb_og_alternate_output', $thumb, urldecode($featured_img_url));
                        
                    } else {
                        
                        @header('HTTP/1.0 200 OK');
                        switch (apply_filters('fb_og_overlayed_image_format', 'jpg')) {
                            case 'png':
                                header('Content-Type: image/png');
                                imagepng($thumb);
                                break;
                            case 'jpg':
                            default:
                                header('Content-Type: image/jpeg');
                                $path = ABSPATH . 'wp-content/uploads/social-share-watermark/';
                                imagejpeg($thumb, $path.$post_id.'_social-share-watermark.jpg', apply_filters('fb_og_overlayed_image_format_jpg_quality', 100));

                                break;
                        }
                        
                    }
                    
                    imagedestroy($image);
                    imagedestroy($thumb);
                    imagedestroy($watermark);
                    
                } else {
                    
                }
                
            
        
    }
}



function SynthiaSoft_CreateImage($filename)
{
    try {
        if (!file_exists($filename)) {
            throw new InvalidArgumentException('File "' . htmlentities($filename) . '" not found.');
        }
        switch (strtolower(pathinfo($filename, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                return imagecreatefromjpeg($filename);
                break;
            
            case 'png':
                return imagecreatefrompng($filename);
                break;
            
            case 'gif':
                return imagecreatefromgif($filename);
                break;
            
            default:
                throw new InvalidArgumentException('File "' . htmlentities($filename) . '" is not valid jpg, png or gif image.');
                break;
        }
    }
    catch (Exception $e) {
        die('Caught exception: ' . $e->getMessage());
        return false;
    }
}