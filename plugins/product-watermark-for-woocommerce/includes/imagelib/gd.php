<?php
class berocket_watermark_gd {
    public static $main = array();
    public function __construct() {
        self::$main = BeRocket_image_watermark::getInstance();
        add_filter('berocket_watermark_apply', array(__CLASS__, 'apply_image'), 10, 3);
        $elements = array(
            'text',
            'image'
        );
        foreach($elements as $element) {
            add_filter('berocket_apply_content_to_image_'.$element, array($this, $element), 10, 2);
        }
    }
    public static function apply_image($applied, $post_id, $additional = array()) {
        extract($additional);
        $get_prepared_image = self::get_prepared_image($all_sizes[$base_type]['fullpath']);
        if( $get_prepared_image === FALSE ) {
            echo '<span class="error">Incorrect MIME type('.$all_sizes[$base_type]['path'].')</span>';
            BeRocket_error_notices::add_plugin_error(self::$main->info['id'], 'Incorrect MIME type', array(
                'type'          => $base_type,
                'path'          => $all_sizes[$base_type]['path'],
                'attachment_id' => $post_id
            ));
            return false;
        }
        list($mime_type, $image_content, $image_width, $image_height) = $get_prepared_image;
        if( ($error_message = self::image_size_validation($image_width, $image_height, $all_sizes[$base_type]['path'], $all_sizes[$base_type]['fullpath'])) !== FALSE ) {
            echo '<span class="error">'.$error_message.'('.$all_sizes[$base_type]['path'].')</span>';
            return false;
        }
        $image_content = apply_filters('berocket_apply_all_content_to_image', 
            $image_content,
            $watermark,
            array(
                'image_width'  => $image_width,
                'image_height' => $image_height,
            )
        );
        self::save_ready_image($image_content, $mime_type, $all_sizes[$base_type]['path']);
        return true;
    }
    public static function get_prepared_image($path) {
        list($image_width, $image_height) = getimagesize($path);
        $mime_type = pathinfo($path, PATHINFO_EXTENSION);
        $mime_type = strtolower($mime_type);
        if( $mime_type == 'jpg' ) {
            $mime_type = 'jpeg';
        }
        $create_function_data = 'imagecreatefrom' . $mime_type;
        if( !function_exists($create_function_data) ) {
            return false;
        }
        $image_content = $create_function_data($path);
        if( $image_content === false ) {
            return false;
        }
        imagealphablending($image_content, false);
        imagesavealpha($image_content, true);
        $truecolor = imagecreatetruecolor($image_width, $image_height);
        $transparent = imagecolorallocatealpha($truecolor, 0, 0, 0, 127);
        imagefill($truecolor, 0, 0, $transparent);
        imagecopyresampled($truecolor,$image_content,0,0,0,0, $image_width,$image_height,$image_width,$image_height);
        imagedestroy($image_content);
        return array($mime_type, $truecolor, $image_width, $image_height);
    }
    public static function image_size_validation($image_width, $image_height, $url, $path) {
        $options = self::$main->get_option();
        $max_img_width = intval($options['max_img_width']);
        $max_img_height = intval($options['max_img_height']);
        $memory_needed = self::calculate_image_memory_usage($image_width, $image_height);
        $memory_needed = $memory_needed * 2;
        $memory_left = berocket_get_memory_data($memory_needed, br_get_value_from_array($options, 'php_memory_limit'));
        if( $image_width > $max_img_width || $image_height > $max_img_height) {
            BeRocket_error_notices::add_plugin_error(self::$main->info['id'], 'Watermark adding. Image size is more then settings limit', array(
                'img_url'  => $url,
                'img_path' => $path,
                'image'    => array(
                    'width'  => $image_width,
                    'height' => $image_height
                ),
                'limit'    => array(
                    'width'  => $max_img_width,
                    'height' => $max_img_height
                ),
            ));
            return 'Watermark adding. Image size is more then settings limit';
        }
        if( $memory_left['memory_check'] <= 0 ) {
            BeRocket_error_notices::add_plugin_error(self::$main->info['id'], 'Watermark adding. Image use a lot of memory', array(
                'img_url'       => $url,
                'img_path'      => $path,
                'memory_needed' => $memory_needed,
                'memory_left'   => $memory_left
            ));
            return 'Watermark adding. Image use a lot of memory';
        }
        return false;
    }
    public static function save_ready_image($image_content, $mime_type, $path, $destroy = true) {
        $upload_dir = wp_upload_dir();
        $options = self::$main->get_option();
        imagealphablending($image_content, false);
        imagesavealpha($image_content, true);
        $function_save = 'image' . $mime_type;
        if ( $mime_type=='jpeg' ) {
            $jpeg_quality = max(0, min(100, intval($options['jpeg_quantity'])));
            $function_save( $image_content, $upload_dir['basedir'].'/'.$path, $jpeg_quality );
        } else {
            $function_save( $image_content, $upload_dir['basedir'].'/'.$path );
        }
        if( $destroy ) {
            imagedestroy($image_content);
        }
    }
    public static function calculate_image_memory_usage($width, $height) {
        $width = (int)$width;
        $height = (int)$height;
        $memory_usage = $width * $height * 5;
        return $memory_usage;
    }
    function text($image_content, $args = array()) {
        $args = array_merge(array(
            'text'          => '',
            'text_alpha'    => 0,
            'font_color'    => '#000000',
            'text_angle'    => '',
            'font_size'     => '',
            'text_repeat'   => false,
            'image_data'    => array()
        ), $args);
        extract($args['image_data']);
        if( strlen($args['text']) ) {
            $text       = $args['text'];
            $font       = plugin_dir_path( BeRocket_image_watermark_file ).'fonts/arial.ttf';
            $alpha      = min(100, max(0, intval($args['text_alpha']))) / 100 * 127;
            $text_angle = min(360, max(0, floatval($args['text_angle'])));
            $font_size  = min(72, max(8, intval($args['font_size'])));
            $font_color = $args['font_color'];
            if( strlen($font_color) == 7 ) {
                $font_color = sscanf($font_color, "#%02x%02x%02x");
            } else {
                $font_color = array(0, 0, 0);
            }
            $black      = imagecolorallocatealpha($image_content, (int)$font_color[0], (int)$font_color[1], (int)$font_color[2], $alpha);
            $type_space = imagettfbbox($font_size, $text_angle, $font, $text);
            if( ! empty($args['text_repeat']) ) {
                $text_width = abs($type_space[4] - $type_space[0]);
                $text_height = abs($type_space[5] - $type_space[1]);
                $text_x_start = -$text_width;
                $text_x_end = $image_width + $text_width;
                $text_y_end = $image_height + $text_height;
                while($text_x_start < $text_x_end) {
                    $text_y_start = -$text_height;
                    while($text_y_start < $text_y_end) {
                        imagettftext($image_content, $font_size, $text_angle, $text_x_start, $text_y_start, $black, $font, $text);
                        $text_y_start += $text_height + 20;
                    }
                    $text_x_start += $text_width + 20;
                }
            } else {
                $text_x = $image_width / 2 - ($type_space[4] - $type_space[0]) / 2;
                $text_y = $image_height / 2 - ($type_space[5] - $type_space[1]) / 2;
                imagettftext($image_content, $font_size, $text_angle, $text_x, $text_y, $black, $font, $text);
            }
        }
        return $image_content;
    }
    function image($image_content, $args = array()) {
        $options = self::$main->get_option();
        $upload_dir = wp_upload_dir();
        $args = array_merge(array(
            'image'         => '',
            'width'         => '',
            'height'        => '',
            'left'          => '',
            'top'           => '',
            'ratio'         => '',
            'image_data'    => array()
        ), $args);
        extract($args['image_data']);
        if( ! empty($args['image']) ) {
			$image_url = str_replace('https:', 'http:', $args['image']);
			$upload_url = str_replace('https:', 'http:', $upload_dir['baseurl']);
			$image_url = str_replace('http://www.', 'http://', $image_url);
			$upload_url = str_replace('http://www.', 'http://', $upload_url);
            $watermark_image_i = str_replace($upload_url, '', $image_url);
            $watermark_image = $upload_dir['basedir'].( (! empty($data['path']) && $watermark_image_i[0] != '/' && substr($upload_dir['basedir'], -1) != '/') ? '/' : '' ).$watermark_image_i;
            if( ! file_exists($watermark_image) ) {
                return $image_content;
            }
            $watermark_type = pathinfo($watermark_image, PATHINFO_EXTENSION);
            $watermark_type = strtolower($watermark_type);
            if( $watermark_type == 'jpg' ) {
                $watermark_type = 'jpeg';
            }
            $create_function_watermark = 'imagecreatefrom' . $watermark_type;
            if( !function_exists($create_function_watermark) ) {
                return $image_content;
            }
            $watermark_content = $create_function_watermark($watermark_image);
            $watermark_width = imagesx($watermark_content);
            $watermark_height = imagesy($watermark_content);
            $ratio_w = $watermark_width / ($image_width / 100 * $args['width']);
            $ratio_h = $watermark_height / ($image_height / 100 * $args['height']);
            $ratio = max( $ratio_w, $ratio_h );
            if ( $args['ratio'] ) {
                $weight_dif = $watermark_width / $ratio_w - $watermark_width / $ratio;
                $height_dif = $watermark_height / $ratio_h - $watermark_height / $ratio;
                if( $args['width'] < 100 && $args['height'] < 100) {
                    $weight_dif = $weight_dif * ( 1 - ( ( 100 - $args['width'] ) - $args['left'] ) / ( 100 - $args['width'] ) );
                    $height_dif = $height_dif * ( 1 - ( ( 100 - $args['height'] ) - $args['top'] ) / ( 100 - $args['height'] ) );
                } else {
                    $weight_dif = $weight_dif / 2;
                    $height_dif = $height_dif / 2;
                }
                $width = $watermark_width / $ratio;
                $height = $watermark_height / $ratio;
            } else {
                $weight_dif = 0;
                $height_dif = 0;
                $width = $watermark_width / $ratio_w;
                $height = $watermark_height / $ratio_h;
            }
            $top = $image_height / 100 * $args['top'] + $height_dif;
            $left = $image_width / 100 * $args['left'] + $weight_dif;
            $width = intval($width);
            $height = intval($height);
            $top = intval($top);
            $left = intval($left);
            $watermark_width = intval($watermark_width);
            $watermark_height = intval($watermark_height);
            if( empty($options['fix_gd']) ) {
                imagesavealpha($watermark_content, true);
                imagealphablending($watermark_content, true);
                imagecopyresampled( $image_content, $watermark_content, $left, $top, 0, 0, $width, $height, $watermark_width, $watermark_height );
            } else {
                $temp_width = $width * 2;
                $temp_height = $height * 2;
                if( $width < 75 || $height < 75 ) {
                    $temp_width = $width * 4;
                    $temp_height = $height * 4;
                } elseif( $width < 150 || $height < 150 ) {
                    $temp_width = $width * 3;
                    $temp_height = $height * 3;
                }
                if( ( $temp_width > $watermark_width || $temp_height > $watermark_height ) 
                && $width*1.5 < $watermark_width/0.9 && $height*1.5 < $watermark_height/0.9 ) {
                    $ratio_temp = min( ( ($watermark_width/0.9) / $width ), ( ($watermark_height/0.9) / $height ) );
                    $temp_width = $width * $ratio_temp;
                    $temp_height = $height * $ratio_temp;
                }
                imagealphablending($watermark_content, false);
                imagesavealpha($watermark_content, true);
                //generate temp image. Fix GD bug with transparent images
                $temp_watermark = imagecreatetruecolor($temp_width, $temp_height);
                $transparent = imagecolorallocatealpha($temp_watermark, 0, 0, 0, 127);
                imagefill($temp_watermark, 0, 0, $transparent);
                imagealphablending($temp_watermark, false);
                imagecopyresized( $temp_watermark, $watermark_content, 0, 0, 0, 0, $temp_width, $temp_height, $watermark_width, $watermark_height );
                imagesavealpha($temp_watermark, true);

                imagecopyresampled( $image_content, $temp_watermark, $left, $top, 0, 0, $width, $height, $temp_width, $temp_height );
                imagedestroy($temp_watermark);
            }
            imagedestroy($watermark_content);
        }
        return $image_content;
    }
}
new berocket_watermark_gd();