<?php
if ( ! defined( 'ABSPATH' ) ) { exit; } 
global $foxtool_options;
# bộ lọc ngăn crop hình ảnh tải lên
if(isset($foxtool_options['media-up1'])){
function foxtool_remove_all_image_sizes($sizes) {
    return array();
}
add_filter('intermediate_image_sizes_advanced', 'foxtool_remove_all_image_sizes');
}
# han che tai len file 
if (isset($foxtool_options['media-up2']) && !empty($foxtool_options['media-up21'])){ 
function foxtool_change_upload_size(){
    global $foxtool_options;
    $mlimit_mb = !empty($foxtool_options['media-up21']) ? $foxtool_options['media-up21'] : 1; // Giới hạn mặc định 1MB
    $mlimit_kb = $mlimit_mb * 1024 * 1024; 
    return $mlimit_kb;
}
add_filter('upload_size_limit', 'foxtool_change_upload_size');
}
# Cho phép tải lên file SVG
if(isset($foxtool_options['media-up3'])){
function foxtool_allow_svg_upload( $mimes ) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'foxtool_allow_svg_upload' );
function foxtool_fix_svg_thumb_display() {
    echo '
        <style type="text/css">
            td.media-icon img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail {
                width: 100% !important;
                height: auto !important;
            }
        </style>
    ';
}
add_action( 'admin_head', 'foxtool_fix_svg_thumb_display' );
}
# cho phep tai len anh jfif
if(isset($foxtool_options['media-up4'])){
function foxtool_allow_jfif_upload( $mime_types ) {
    $mime_types['jfif'] = 'image/jfif';
    $mime_types['jpe'] = 'image/jpe';
    return $mime_types;
}
add_filter( 'upload_mimes', 'foxtool_allow_jfif_upload' );
}
# nén hình anh jpg khi tai len
if(isset($foxtool_options['media-zip1']) && !isset($foxtool_options['media-webp1'])){
function foxtool_image_compression($file) {
    global $foxtool_options;
    $image_type = exif_imagetype($file['tmp_name']);
    if ($image_type === IMAGETYPE_JPEG) {
        if (!empty($foxtool_options['media-zip11'])) {
            $compression_quality = $foxtool_options['media-zip11'];
        } else {
            $compression_quality = 60; // Mức độ nén mặc định
        }
        $image = imagecreatefromjpeg($file['tmp_name']);
        imagejpeg($image, $file['tmp_name'], $compression_quality);
        imagedestroy($image);
    } 
	// chuyển png sang jpg
	else if ($image_type === IMAGETYPE_PNG && isset($foxtool_options['media-zip12'])) {
        $image = imagecreatefrompng($file['tmp_name']);
        if (!empty($foxtool_options['media-zip11'])) {
            $compression_quality = $foxtool_options['media-zip11'];
        } else {
            $compression_quality = 60; // Mức độ nén mặc định
        }
        imagejpeg($image, $file['tmp_name'], $compression_quality);
        imagedestroy($image);
    }
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'foxtool_image_compression');
}
# chuyen anh jpg, png sang webp
function foxtool_convert_to_webp($upload) {
    $image_path = $upload['file'];
	global $foxtool_options;
	if (!empty($foxtool_options['media-webp11'])) {
            $compression_quality = $foxtool_options['media-webp11'];
        } else {
            $compression_quality = 60; // Mức độ nén mặc định
        }
    $supported_mime_types = array(
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
    );
    $image_info = getimagesize($image_path);
    if ($image_info !== false && array_key_exists($image_info['mime'], $supported_mime_types)) {
        $image = imagecreatefromstring(file_get_contents($image_path));
        if ($image) {
            // Kiểm tra xem ảnh có phải là truecolor hay không
            if (imageistruecolor($image)) {
                $webp_path = preg_replace('/\.(jpg|jpeg|png)$/', '.webp', $image_path);
                imagewebp($image, $webp_path, $compression_quality);
                $upload['file'] = $webp_path;
                $upload['type'] = 'image/webp';
				// xóa ảnh góc
				unlink($image_path);
            } else {
            // Nếu là ảnh 8-bit, bỏ qua không nén
                $upload['file'] = $image_path;
                $upload['type'] = $image_info['mime'];
            }
        }
    }
    return $upload;
}
if(isset($foxtool_options['media-webp1'])){
function foxtool_convert_to_webp_upload($upload) {
    $upload = foxtool_convert_to_webp($upload); 
    return $upload;
}
add_filter('wp_handle_upload', 'foxtool_convert_to_webp_upload');
}
# chuyen anh jpg, png sang avif
// cho phep avif wp
if(isset($foxtool_options['media-avif1'])){
function foxtool_add_avif_support( $mime_types ) {
    $mime_types['avif'] = 'image/avif';
    $mime_types['avifs'] = 'image/avif-sequence';
    return $mime_types;
}
add_filter( 'upload_mimes', 'foxtool_add_avif_support' );
}
function foxtool_convert_to_avif($upload) {
	if (!function_exists('imageavif')) {
	  return $upload; 
	}
    $image_path = $upload['file'];
	global $foxtool_options;
    if (!empty($foxtool_options['media-avif21'])) {
            $compression_quality = $foxtool_options['media-avif21'];
        } else {
            $compression_quality = 60; // Mức độ nén mặc định
        }
    $supported_mime_types = array(
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
    );
    $image_info = getimagesize($image_path);
    if ($image_info !== false && array_key_exists($image_info['mime'], $supported_mime_types)) {
        if (function_exists('imagecreatefromavif')) {
            $image = imagecreatefromstring(file_get_contents($image_path));
            if ($image) {
                if (imageistruecolor($image)) {
                    $avif_path = preg_replace('/\.(jpg|jpeg|png)$/', '.avif', $image_path);
                    imageavif($image, $avif_path, $compression_quality);
                    $upload['file'] = $avif_path;
                    $upload['type'] = 'image/avif';
                    // Xóa ảnh gốc
                    unlink($image_path);
                } else {
                    // Nếu là ảnh 8-bit, bỏ qua không nén
                    $upload['file'] = $image_path;
                    $upload['type'] = $image_info['mime'];
                }
            }
        } 
    }
    return $upload;
}
if(isset($foxtool_options['media-avif2'])){
function foxtool_convert_to_avif_upload($upload) {
    $upload = foxtool_convert_to_avif($upload); 
    return $upload;
}
add_filter('wp_handle_upload', 'foxtool_convert_to_avif_upload');
}
# giới hạn kích thước ảnh png khi tải lên
if(isset($foxtool_options['media-zip2'])){
function foxtool_image_resize($file) {
    global $foxtool_options;
    $image_type = exif_imagetype($file['tmp_name']);
    $allowed_formats = array(
        IMAGETYPE_PNG,
        IMAGETYPE_JPEG,
    );
    if (in_array($image_type, $allowed_formats)) {
        $image = null;
        if ($image_type === IMAGETYPE_PNG) {
            $image = imagecreatefrompng($file['tmp_name']);
        } elseif ($image_type === IMAGETYPE_JPEG) {
            $image = imagecreatefromjpeg($file['tmp_name']);
        }
        if ($image) {
            $width = imagesx($image);
            $height = imagesy($image);
            $max_width = !empty($foxtool_options['media-zip21']) ? $foxtool_options['media-zip21'] : $width;
            $max_height = !empty($foxtool_options['media-zip22']) ? $foxtool_options['media-zip22'] : $height;
            $new_width = $width;
            $new_height = $height;

            if ($width > $max_width || $height > $max_height) {
                $ratio = min($max_width / $width, $max_height / $height);
                $new_width = intval($width * $ratio);
                $new_height = intval($height * $ratio);
            }
            $new_image = imagecreatetruecolor($new_width, $new_height);
            if ($image_type === IMAGETYPE_PNG) {
                imagealphablending($new_image, false);
                imagesavealpha($new_image, true);
                $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
                imagefilledrectangle($new_image, 0, 0, $new_width, $new_height, $transparent);
                imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagepng($new_image, $file['tmp_name']);
            } elseif ($image_type === IMAGETYPE_JPEG) {
                imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagejpeg($new_image, $file['tmp_name']);
            }

            imagedestroy($image);
            imagedestroy($new_image);
        }
    }
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'foxtool_image_resize');
}
# Hàm thêm watermark cho hinh anh tai len
if(isset($foxtool_options['media-logo1'])){
function foxtool_add_watermark($attachment_id) {
	global $foxtool_options;
	if(!empty($foxtool_options['media-logo11'])){$logo = $foxtool_options['media-logo11'];}else{$logo = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOQAAAA7BAMAAACZETtEAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAeUExURUdwTIqKioqKioqKioqKioqKioqKioqKioqKioqKiuML4sEAAAAJdFJOUwC1DlHs1HculwpflmMAAASSSURBVFjD7Zi7c9NAEIdl2ZZMFygAdSoSGHcGQwZ3GYdH3JnHDOPODZm4M68J6hLe6nBkg++/xdbd7u3eQxqYoWFyRSaWdPfd7eN3excEl+2y1bd4//zFo5tf586Xjatl+37cP03Zi/Aqaz359PX2/yn5zHqwbR9GomyrT6kD2RXYltfprELBmhp1tv1/SD7Ltw/GfLL3dbfHaSVyA/1WhywJBelfrucHI36m/RzMLh/5Yw0y2f6/0N2b5ctfdMR7vONhHXI1r0ZKJ+mZd4S57I4Qzp5epPhZiYzNUXZ5p80XExO5SGuQ4lkVUtpR7GD3gTAsPRBWe1mHXFQhI2H4LpFhpxdpE8UqrUGC1ZzIhsf6OOg79eCo/+Z0PzEMR5FPTt/2T8AJBRlt2Ycmw6ql5g291aoFBF2oBvnGEnThQMpUfqCSc66RK48bANE2orJBiZummGc+ZHBLfjD2IyE4YIzM+D0zUjHM7bxlyOA9sawTOVOIAyp3eoDQyG2w/MKPlK5Y+JE597dSBpyCBAztSfa8SJXZcy8SQmxN5U5bbpfEAhOjsR8Za8+4kJg4y5SLW0FcXfA+ifWMI+UHQx9SJ3qPGEUn6sTeyWSErSuQGc7JhURDKskbcMlq2n5TllhWIBs4J2nEh9Cm2H21/XOBNllOcMjI5f6QJbIDGeGcXYJXTqhAS5afrHNUvI6xqxBrT/3IGCfqQpZ6Nx7BJ+X8ihmuom2nPaTJmR8pM82HLD19lgOjRBwM+Ix+mMiMb3c2cgJmciHl6Bn8ljPo4iq6VJg4clyBTGAJLqS0YRu6yJ8tHCFz5EgQXDEn4kT2PMjyZRqBy6RTGzjkwDRh4DT3HyElI1aJFMsA7WDQ/OUqJxzJ8jJUGZTITOzIBG2iemROX3brfDlivlzZ4vNT+bAn5W4HFgvr+eOIDQWLWI6M1Hq6cpCBMgiqR8uflzs1UiA8GqssKQNmKO2bSl+sUAos9UlqpCBCEXYg28pX0nshyDEqXoXG9vzIFpf1lR17GxOFW4cvwMwBKl5sKTguIvUjB1WbVwYmKte1B/GZsSQyE3PXKn5cW/SFBwnxIiE5TEArXu6oCvKaqiDS+68DiXreQoXAX1rxmBHVtj30IzPtawcygdCK8LiBu+iBroWe2ZXv1IuMR5rjQOJGGbOaBzVXxhUr8ZojexiGzCtL51gXTlBcXqDtCme1rh4UPmT8ipZSNrKpM31Gqy6teMGefPwc70vM8ygiD/v9/ZPzETvj2CcvbUA8DvXQ4AviGSFuMKJxwLTPl4XvfNnQpV2DVdATbQ84Fz399Ob22y/qx/Cvj7Rt7YMmOyfoGo9UuuRqZ16DXHsP7l3i6BE1yIzonOOu4LDurmDqRWbmlRNsx/QFeNN1r+NBPvFfwszIljCgJ9ku3Z72TOTHmnufdepHEpcpyZuTTW1snkHNiyQP8mhecaE2IeeZDs3aBqt5YsY8qr7De/Q1rbo2pLtQTBcQ8QIkTlxWs+5jb34/Pr5bfR9bPrgOr883P66BLG3f3CDKeEfF0NJ5H/uPbp1PKm6dL9v/0H4DWgne9M4FXUcAAAAASUVORK5CYII=";}
    $watermark_path = $logo; 
    $attachment = get_post($attachment_id);
    $file = get_attached_file($attachment_id);
    $mime_type = get_post_mime_type($attachment);
    
    if (strpos($mime_type, 'image') !== false) { 
		if (strpos($mime_type, 'image/svg') !== false || strpos($mime_type, 'image/gif') !== false) {
            return;
        }
		if (strpos($mime_type, 'webp') !== false) {
            $image = @imagecreatefromwebp($file);
		} elseif ($mime_type == 'image/jpeg') {
            $image = @imagecreatefromjpeg($file); 
        } elseif ($mime_type == 'image/png') {
            $image = @imagecreatefrompng($file); 
        }
        if ($image === false) {
            return;
        }
		// Kiểm tra xem ảnh có dưới 12-bit không
        if (function_exists('imageistruecolor') && !imageistruecolor($image)) {
            return;
        }
		
        $watermark = imagecreatefrompng($watermark_path);
        $image_width = imagesx($image);
        $image_height = imagesy($image);
		// neu anh cao nho hon 200 thi khong them logo
		if ($image_height < 200) {
            return;
        }
        $watermark_width = imagesx($watermark);
        $watermark_height = imagesy($watermark);
		
		if(isset($foxtool_options['media-logo12']) && $foxtool_options['media-logo12'] == 'Center'){
		// chinh giua khung hình
		$watermark_pos_x =  intval(($image_width - $watermark_width) / 2);
		$watermark_pos_y =  intval(($image_height - $watermark_height) / 2);
		}
		elseif (isset($foxtool_options['media-logo12']) && $foxtool_options['media-logo12'] == 'Top Left'){
		// goc tren trai
		$watermark_pos_x = 10;
		$watermark_pos_y = 10;
		}
		elseif (isset($foxtool_options['media-logo12']) && $foxtool_options['media-logo12'] == 'Top Right'){
		// goc tren phai
		$watermark_pos_x = $image_width - $watermark_width - 10; 
		$watermark_pos_y = 10; 
		}
		elseif (isset($foxtool_options['media-logo12']) && $foxtool_options['media-logo12'] == 'Bottom Left'){
		// goc duoi trai
		$watermark_pos_x = 10; 
		$watermark_pos_y = $image_height - $watermark_height - 10; 
		}
		elseif (isset($foxtool_options['media-logo12']) && $foxtool_options['media-logo12'] == 'Bottom Right'){
		// goc duoi phai
		$watermark_pos_x = $image_width - $watermark_width - 10; 
		$watermark_pos_y = $image_height - $watermark_height - 10; 
		}
		elseif (isset($foxtool_options['media-logo12']) && $foxtool_options['media-logo12'] == 'Top Center'){
		$watermark_pos_x = intval(($image_width - $watermark_width) / 2);
		$watermark_pos_y = 0; 
		}
		elseif (isset($foxtool_options['media-logo12']) && $foxtool_options['media-logo12'] == 'Bottom Center'){
		$watermark_pos_x = intval(($image_width - $watermark_width) / 2);
		$watermark_pos_y = intval($image_height - $watermark_height);
		}
		else {
		// chinh giua khung hình
		$watermark_pos_x =  intval(($image_width - $watermark_width) / 2);
		$watermark_pos_y =  intval(($image_height - $watermark_height) / 2);	
		}
        // Thêm watermark vào hình ảnh
        imagecopy($image, $watermark, $watermark_pos_x, $watermark_pos_y, 0, 0, $watermark_width, $watermark_height);
        
        // Lưu hình ảnh mới đã thêm watermark
		if (strpos($mime_type, 'webp') !== false) {
            imagewebp($image, $file);
        }
        elseif ($mime_type == 'image/jpeg') {
            imagejpeg($image, $file);
        } elseif ($mime_type == 'image/png') {
            imagepng($image, $file);
        }
    }
}
add_action('add_attachment', 'foxtool_add_watermark');
}
# xóa ảnh 404 trong media
function foxtool_delete_404_attachments() {
	check_ajax_referer('foxtool_media_del', 'security');
    if (!current_user_can('manage_options')){
        wp_die(__('Insufficient permissions', 'foxtool'));
    }
    $attachments = get_posts(array(
        'post_type' => 'attachment',
        'numberposts' => -1,
        'fields' => 'ids'
    ));
    if ($attachments) {
        foreach ($attachments as $attachmentID) {
            $file_path = get_attached_file($attachmentID);
            if ($file_path && !file_exists($file_path)) {
				$deleted = wp_delete_attachment($attachmentID, true);
            }
        }
    }
}
add_action('wp_ajax_foxtool_delete_media', 'foxtool_delete_404_attachments');
add_action('wp_ajax_nopriv_foxtool_delete_media', 'foxtool_delete_404_attachments');









