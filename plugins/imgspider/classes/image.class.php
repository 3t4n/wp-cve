<?php


class WB_IMGSPY_Image
{

    public static $error = null;

    public static function resize_image($img_file,$mime_type){

        if(!preg_match('#(jpg|jpeg|png)#i',$mime_type)){
            return false;
        }
        $config = WB_IMGSPY_Conf::opt();
        $rule = $config['rule'];
        //print_r($rule);

        if(!$rule || !$rule['size']){
            return false;
        }
        $max_w = 0;
        if($rule['size']=='1'){
            $max_w = 1080;
        }else if($rule['size']=='2'){
            $max_w = 720;
        }else if($rule['size']=='3'){
            $max_w = intval($rule['custom_size']);
        }
        if(!$max_w){
            return false;
        }

        if(apply_filters('wb_imgspy_resize_image',false,$img_file,$mime_type,$rule)){
            return true;
        }
        $imagesize = wp_getimagesize( $img_file );
        if ( empty( $imagesize ) || !is_array($imagesize) ) {
            return false;
        }

        /*if('image/png' == $imagesize['mime'] ){
            return false;
        }*/
        $w = $imagesize[0];
        $h = $imagesize[1];
        if($w && $w < $max_w){
            return true;
        }

        $new_w = $max_w;
        $new_h = (int)($max_w / ($w / $h));


        $editor = wp_get_image_editor( $img_file );
        /*$size = $editor->get_size();
        if(!$size || !is_array($size) || !isset($size['width'])){
            return false;
        }*/

        $resized = $editor->resize($new_w,$new_h);
        if(is_wp_error($resized)){
            return false;
        }

        $saved = $editor->save( $img_file );
        if(is_wp_error($saved)){
            return false;
        }
        return true;


        if(function_exists('getimagesize')){
            $size = getimagesize($img_file);
            if($size && $size[0]<=$max_w){//未超额
                return true;
            }
        }


        if(preg_match('#png#',$mime_type)){
            $fun = 'imagepng';
            if(!function_exists('imagecreatefrompng')){
                return false;
            }
            $img = imagecreatefrompng($img_file);
        }else{
            if(!function_exists('imagecreatefromjpeg')){
                return false;
            }
            $fun = 'imagejpeg';
            $img = imagecreatefromjpeg($img_file);
        }

        $s_w = imagesx($img);
        $s_h = imagesy($img);
        $rate = $s_w / $s_h;//100/100=1,100/200<1,200/100>1

        if($s_w<=$max_w){
            return true;
        }

        $w = $max_w;
        $h = (int)($w / $rate);


        $res = imagecreatetruecolor($w, $h);

        if($fun == 'imagepng'){
            $alpha = imagecolorallocatealpha($res, 0, 0, 0, 127);
            imagefill($res, 0, 0, $alpha);

        }

        imagecopyresampled($res, $img, 0, 0, 0, 0, $w, $h, $s_w, $s_h);

        if($fun == 'imagepng')
        {
            imagesavealpha($res, true);
        }


        if ($fun == 'imagejpeg') {
            $fun($res, $img_file, 80);
        } else {
            $fun($res, $img_file);
        }

        return true;

    }

    public static function get_image_editor($path,$mime = null)
    {
        $args['path'] = $path;

        if($mime){
            $args['mime_type'] = $mime;
        }else if($path){
            $file_info = wp_check_filetype( $path );
            // If $file_info['type'] is false, then we let the editor attempt to
            // figure out the file type, rather than forcing a failure based on extension.
            if ( isset( $file_info ) && $file_info['type'] ) {
                $args['mime_type'] = $file_info['type'];
            }
        }


        return  _wp_image_editor_choose( $args );
    }

    public static function get_xy($rule,$src_w,$src_h,$water_w,$water_h)
    {
        //{1:'右下方',2:'左下方',3:'右上方',4:'左上方',5:'正中心',6:'自定义'}
        $border = 20;
        $x = $y = $border;
        $pos = intval($rule['pos']);
        switch ($pos){
            case 1:
                $x = ($src_w - $water_w) - $border;
                $y = ($src_h - $water_h) - $border;

                break;
            case 2:
                $y = ($src_h - $water_h) - $border;
                break;
            case 3:
                $x = ($src_w - $water_w) - $border;
                break;
            case 4:

                break;
            case 5:
                $x = round(($src_w - $water_w)/ 2);
                $y = round(($src_h - $water_h) / 2);
                break;
            case 6:
                $x1 = isset($rule['x']) && $rule['x']?absint($rule['x']):5;
                $y1 = isset($rule['y']) && $rule['y']?absint($rule['y']):5;
                $x = round($src_w *  $x1 / 100);
                $y = round($src_h *  $y1 / 100);

                break;
        }
        /*$x = max($x,0);
        $y = max($y,0);
        $x = min($x,$src_w);
        $y = min($y,$src_h);*/
        //error_log(print_r([$x,$y,$src_w,$src_h,$water_w,$water_h,$rule],true)."\n",3,__DIR__.'/log.log');
        return [$x,$y];
    }


    public static function image_file($file, $attachment_id)
    {

        $name = basename($file);
        if(!preg_match('#\.(jpg|png|jpeg)$#i',$name,$ext)){
            return $file;
        }
        $meta_file = str_replace(ABSPATH,'',$file);
        $dir = dirname($meta_file);
        $new_name = md5($meta_file).$ext[0];

        $new_file = ABSPATH.$dir.'/'.$new_name;
        if(file_exists($new_file)){
            return $new_file;
        }

        /*do{
            $mark = get_post_meta($attachment_id,'_wb_img_meta',true);
            if($mark){
                return $file;
            }

            if(!file_exists($file)){
                break;
            }
        }while(0);*/

        return $file;

    }

    public static function image_metadata($data,$attachment_id)
    {
        static $parse_list = [];
        if(isset($parse_list[$attachment_id])){
            return $data;
        }
        if(count($parse_list)>5){
            return $data;
        }
        /*function ($data, $attachment_id){
            //w=700,h=400
            //print_r($data);


            return $data;
        }*/
        //print_r($data);
        //$new_data = $data;
        do{
            if(!isset($data['file']) || !$data['file']){
                break;
            }
            $file = $data['file'];
            if(!preg_match('#\.(jpg|jpeg|png)$#i',$file,$ext)){
                break;
            }
            $mark = get_post_meta($attachment_id,'_wb_watermark',true);
            if($mark){
                break;
            }

            $config = WB_IMGSPY_Conf::opt();
            $rule = $config['watermark'];
            $min_width = intval($rule['min_width'] ?? 700);
            $min_height = intval($rule['min_height'] ?? 700);

            if(isset($data['width']) && $min_width && $min_width > $data['width']){
                break;
            }
            if(isset($data['height']) && $min_height && $min_height > $data['height']){
                break;
            }

            $parse_list[$attachment_id] = 1;

            //$data['original_image'] = '';

            //original
            //$new_base_name = md5($file);
            //$new_name = $new_base_name.$ext[0];
            //$src_name = basename($file);
            $dir = dirname($file);
            //$new_file = $dir.'/'.$new_name;
            $upload_dir = WP_CONTENT_DIR . '/uploads/';
            $src_img = $upload_dir.$file;
            //$water_img = $upload_dir.$new_file;
            if(!file_exists($src_img)){
                break;
            }
            $backup_base = WP_CONTENT_DIR . '/uploads/#original/';
            $backup_dir = $backup_base.dirname($file);
            if(!is_dir($backup_dir)){
                //wp_mkdir_p($backup_dir);
                if(!mkdir($backup_dir,0755,true)){
                    break;
                }
            }
            $backup_file = $backup_base.$file;

            //backup fail
            if(!copy($src_img,$backup_file)){
                break;
            }
            //$new_data['file'] = $new_file;



            if($rule['type'] == 1){
                self::watermark_image($src_img,$rule);
            }else if($rule['type'] == 2){
                if(!$rule['text']){
                    if(preg_match('#://([^/])#',home_url('/'),$m)){
                        $rule['text'] = str_replace('www.','',$m[1]);
                    }else{
                        $rule['text'] = bloginfo('name');
                    }
                }
                self::watermark_text($src_img,$rule);
            }
            //备份wp-原图
            if(isset($data['original_image']) && $data['original_image']){
                $orig_src = $upload_dir.$dir.'/'.$data['original_image'];
                $backup_file = $backup_base.$dir.'/'.$data['original_image'];
                if(copy($orig_src,$backup_file)){
                    copy($src_img,$orig_src);
                }
            }

            //$src_base = substr($src_name,0,-strlen($ext[0]));
            if(isset($data['sizes']) && is_array($data['sizes']))foreach($data['sizes'] as $s=>$row){
                $src_img = $upload_dir.$dir.'/'.$row['file'];
                //$thumb_name = $new_base_name.(str_replace($src_base,'',$row['file']));
                //$thumb_file = $upload_dir.$dir.'/'.$thumb_name;
                $backup_thumb = $backup_base.$dir.'/'.$row['file'];
                if(copy($src_img,$backup_thumb)){
                    //$row['file'] = $thumb_name;
                    $w = isset($row['width']) ? $row['width'] : 0;
                    $h = isset($row['height']) ? $row['height'] : 0;
                    //error_log(print_r([$min_height,$h,$min_width,$w],true)."\n",3,__DIR__.'/img.txt');
                    do{
                        if($min_width && $min_width > $w){
                            //没有加水印
                            unlink($backup_thumb);
                            break;
                        }
                        if($min_height && $min_height > $h){
                            //没有加水印
                            unlink($backup_thumb);
                            break;
                        }
                        if($rule['type'] == 1){
                            self::watermark_image($src_img,$rule);
                        }else if($rule['type'] == 2){
                            self::watermark_text($src_img,$rule);
                        }
                    }while(0);
                }
                //$new_data['sizes'][$s] = $row;
            }
            //update_post_meta($attachment_id,'_wb_img_meta',$new_data);
            update_post_meta($attachment_id,'_wb_watermark',1);

            //return $new_data;

        }while(0);

        return $data;
    }

    public static function generate_attachment_metadata($metadata, $attachment_id, $state)
    {
        if($state != 'create'){
            return $metadata;
        }

        self::image_metadata($metadata,$attachment_id);

        return $metadata;
    }

    public static function init_watermark()
    {



        if(!get_option('wb_imgspider_ver',0)){
            return;
        }
        $config = WB_IMGSPY_Conf::opt();
        if(!isset($config['watermark'])){
            return;
        }
        $rule = $config['watermark'];
        if(!$rule || !is_array($rule)){
            return;
        }
        $type = isset($rule['type'])?intval($rule['type']):0;
        if(!$type){
            return;
        }
        $editor = self::get_image_editor(null,null);
        if(!preg_match('#Imagick#i',$editor)){
            return;
        }

        if($type == 1){
            if(!isset($rule['image']) || !$rule['image']){
                return;
            }
        }else if($type == 2){
            if(!isset($rule['text']) || !$rule['text']){
                return;
            }
        }else{
            return;
        }

        if($rule['apply'] == 'all'){
            add_action('wb_imgspy_watermark_image',array(__CLASS__,'wb_imgspy_watermark_image'));
            if(!wp_next_scheduled('wb_imgspy_watermark_image')){
                wp_schedule_event(strtotime(current_time('mysql',1)), 'hourly', 'wb_imgspy_watermark_image');
            }
        }

        add_action('init',array(__CLASS__,'parse_watermark'));
    }

    public static function wb_imgspy_watermark_image()
    {
        global $wpdb;
        $max= 10;
        //error_log("wb_imgspy_watermark_image\n",3,__DIR__.'/img.txt');

        $sub_sql = " AND NOT EXISTS(SELECT post_id FROM $wpdb->postmeta m WHERE m.post_id=a.ID AND m.meta_key='_wb_watermark' )";
        $sub_sql .= " AND EXISTS(SELECT post_id FROM $wpdb->postmeta m2 WHERE m2.post_id=a.ID AND m2.meta_key='_wp_attachment_metadata' )";

        $sql = "SELECT a.ID FROM $wpdb->posts a WHERE post_type='attachment' AND post_mime_type REGEXP '^image' $sub_sql ORDER BY ID DESC LIMIT $max";
        //error_log($sql."\n",3,__DIR__.'/img.txt');
        $list = $wpdb->get_col($sql);
        add_filter('wp_get_attachment_metadata',array(__CLASS__,'image_metadata'),900,2);
        foreach($list as $post_id){
            wp_get_attachment_metadata($post_id);
            //get_attached_file();
            //self::image_metadata($data,$post_id);
        }

    }

    public static function parse_watermark()
    {
        $config = WB_IMGSPY_Conf::opt();
        if(!isset($config['watermark'])){
            return;
        }
        $rule = $config['watermark'];
        if(!$rule || !is_array($rule)){
            return;
        }

        if($rule['apply'] == 'new'){
            add_filter('wp_generate_attachment_metadata',array(__CLASS__,'generate_attachment_metadata'),100,3);
        }else{
            add_filter('wp_generate_attachment_metadata',array(__CLASS__,'generate_attachment_metadata'),100,3);
            //add_filter('wp_get_attachment_metadata',array(__CLASS__,'image_metadata'),900,2);
        }
        //'wp_delete_file', $file
        //'wp_generate_attachment_metadata'

        //add_filter('get_attached_file',array(__CLASS__,'image_file'),900,2);




    }


    public static function watermark_preview($src_img)
    {
        do{
            $rule = isset($_POST['watermark'])?$_POST['watermark']:array();
            if(empty($rule) || !is_array($rule)){
                break;
            }

            $type = isset($rule['type'])?intval($rule['type']):0;
            if(!$type){
                break;
            }
            foreach($rule as $k=>$v){
                if(in_array($k,array('image','text','color'))){
                    $rule[$k] = sanitize_text_field($v);
                }else{
                    $rule[$k] = absint($v);
                }
            }

            if($type == 1){
                $id = self::watermark_image($src_img,$rule);
                //error_log('id'.$id."\n",3,IMGSPY_PATH.'/#log/watermark.log');
            }else if($type == 2){
                self::watermark_text($src_img,$rule);
            }
        }while(0);

        echo base64_encode(file_get_contents($src_img));
        //header('Content-type: image/jpeg;');
        //readfile($src_img);
        exit();
    }

    public static function watermark_image($img_file,$rule)
    {
        //print_r($rule);
        if(!$rule || !$rule['type']){
            return 1;
        }
        if($rule['type'] != 1 || !$rule['image']){
            return 2;
        }
        $water_src = str_replace(home_url('/'),ABSPATH,$rule['image']);
        if(preg_match('#^https?://#',$water_src)){
            $water_src = preg_replace('#^https?://[^/]+/#',ABSPATH,$water_src);
        }

        if(!file_exists($water_src)){
            do{
                $local_src_name = basename($water_src);
                $local_src = IMGSPY_PATH.'/assets/'.md5($water_src).'-w-'.substr($local_src_name,'-4');
                if(file_exists($local_src)){
                    $water_src = $local_src;
                    break;
                }
                $http = wp_remote_get($rule['image']);
                if(is_wp_error($http)){
                   return 3;
                }
                $data = wp_remote_retrieve_body($http);
                if(!$data){
                    return 4;
                }
                if(file_put_contents($local_src,$data)){
                    $water_src = $local_src;
                    break;
                }
                return 5;
            }while(0);
        }

        $water_image = $water_src;

        $imagesize = wp_getimagesize( $img_file );
        if ( empty( $imagesize ) || !is_array($imagesize) ) {
            return 6;
        }

        if(preg_match('#/gif#i',$imagesize['mime'])){
            return 7;
        }

        //$args
        $editor = self::get_image_editor($img_file,$imagesize['mime']);
        if(!$editor || !preg_match('#Imagick#i',$editor)){
            return 8;
        }

        $alpha = isset($rule['alpha']) && $rule['alpha']?absint($rule['alpha']):30;

        $pos = isset($rule['pos']) && $rule['pos']?absint($rule['pos']):1;
        $rule['pos'] = $pos;
        $image = $watermark = $mask = $draw = null;
        try {
            $image = new Imagick($img_file);

            $watermark = new Imagick($water_image);
            /*if($pos == 6){
                $x = isset($rule['x']) && $rule['x']?absint($rule['x']):20;
                $y = isset($rule['y']) && $rule['x']?absint($rule['x']):20;
            }else{

            }*/
            $src_size = $image->getImageGeometry();
            $water_size = $watermark->getImageGeometry();
            //print_r($src_size);
            //print_r($water_size);
            list($x,$y) = self::get_xy($rule,$src_size['width'],$src_size['height'],$water_size['width'],$water_size['height']);

            //print_r([$x,$y]);
            $mask_color = sprintf('rgba(0, 0, 0, %.2F)', $alpha / 100);
            $mask = new Imagick();
            $mask->newImage($watermark->getImageWidth(), $watermark->getimageheight(), new \ImagickPixel(
                $mask_color
            ), 'png');
            $mask->setType(\Imagick::IMGTYPE_UNDEFINED);
            $mask->setImageType(\Imagick::IMGTYPE_UNDEFINED);
            $mask->setColorspace(\Imagick::COLORSPACE_UNDEFINED);
            $watermark->setImageMatte(true);
            $watermark->compositeImage($mask, \Imagick::COMPOSITE_DSTIN, 0, 0);


            //$watermark->setImageOpacity($alpha);
            $draw = new ImagickDraw();
            //$draw->setFillOpacity($alpha);
            //$draw->setOpacity($alpha);
            $draw->composite($watermark->getImageCompose(), $x, $y, $watermark->getImageWidth(), $watermark->getimageheight(), $watermark);
            $image->drawImage($draw);
            $image->writeImage($img_file);

            $draw->destroy();
            $draw = null;
            $mask->destroy();
            $mask = null;
            $watermark->destroy();
            $watermark = null;
            $image->destroy();
            $image = null;

        } catch ( Exception $e ) {

            if($draw)$draw->destroy();
            if($mask)$mask->destroy();
            if($watermark)$watermark->destroy();
            if($image)$image->destroy();

            //error_log($e->getMessage()."\n",3,IMGSPY_PATH.'/#log/watermark.log');

            return 100;
        }

        return 0;
    }

    public static function watermark_text($img_file,$rule)
    {
        if(!$rule || !$rule['type']){
            return false;
        }
        if($rule['type'] != 2 || !$rule['text']){
            return false;
        }
        $imagesize = wp_getimagesize( $img_file );
        if ( empty( $imagesize ) || !is_array($imagesize) ) {
            return false;
        }

        if(preg_match('#/gif#i',$imagesize['mime'])){
            return false;
        }

        //$args
        $editor = self::get_image_editor($img_file,$imagesize['mime']);
        if(!$editor || !preg_match('#Imagick#i',$editor)){
            return false;
        }
        //print_r($rule);

        $waterText   = $rule['text'];
        $textColor   = isset($rule['color']) && $rule['color'] ? $rule['color'] :'#000000';
        $letterSpace = 1;
        $fontSize    = isset($rule['size']) && $rule['size'] ? intval($rule['size']) :20;
        $fontSize = absint($fontSize) * 1;
        //$shadowColor = 'black';

        $alpha = isset($rule['alpha']) && $rule['alpha']?absint($rule['alpha']):30;
        $opacity = round($alpha / 100,2);
        $pos = isset($rule['pos']) && $rule['pos']?absint($rule['pos']):1;
        $rule['pos'] = $pos;
        $font_id = isset($rule['font']) && $rule['font']?absint($rule['font']):0;
        $font_list = [
            0=>'consolas-webfont.ttf',
            1=>'Arial.ttf',
            2=>'Arial_Black.ttf',
            3=>'Comic_Sans_MS.ttf',
            4=>'Courier_New.ttf',
            5=>'Georgia.ttf',
            6=>'Impact.ttf',
            7=>'Tahoma.ttf',
            8=>'Times_New_Roman.ttf',
            9=>'Trebuchet_MS.ttf',
            10=>'Verdana.ttf',
        ];


        //字体文字地址
        $font = dirname(__DIR__).'/assets/font/'.(isset($font_list[$font_id])?$font_list[$font_id]:$font_list[0]);
        $image = $draw = $text = null;
        try{
            $image = new Imagick($img_file);

            $draw = new ImagickDraw();

            //设置水印字体文字
            $draw->setFont($font);
            $draw->setFillColor($textColor);
            $draw->setFillOpacity($opacity);
            $draw->setTextKerning($letterSpace);
            $draw->setTextEncoding('UTF-8');
            $draw->setGravity(Imagick::GRAVITY_CENTER);
            if($fontSize > 0){
                $draw->setFontSize($fontSize);
            }
            // $draw->setFontWeight(100);
            $draw->annotation(0, 0, $waterText);
            $text   = new Imagick();
            $metrix = $text->queryFontMetrics($draw, $waterText);
            $text->newImage($metrix['textWidth'], $metrix['textHeight'], 'none');
            $text->setImageFormat('png');
            $text->drawImage($draw);

            /*$shadow = clone $text;
            $shadow->setImageBackgroundColor(new ImagickPixel($shadowColor));
            $shadow->shadowImage(50, 0.5, 0, 0);
            $shadow->compositeImage($text, Imagick::COMPOSITE_OVER, 0, 0);*/

            //设置文字水印位置
            /*if($pos == 6){
                $x = isset($rule['x']) && $rule['x']?absint($rule['x']):20;
                $y = isset($rule['y']) && $rule['x']?absint($rule['x']):20;
            }else{

            }*/
            $src_size     = $image->getImageGeometry();

            list($x,$y) = self::get_xy($rule,$src_size['width'],$src_size['height'],$metrix['textWidth'],$metrix['textHeight']);

            //$image->compositeImage($shadow, $shadow->getImageCompose(), $x, $y);
            $image->compositeImage($text, $text->getImageCompose(), $x, $y);
            $image->writeImage($img_file);

            $draw->destroy();
            $text->destroy();
            $image->destroy();
            $draw = null;
            $text = null;
            $image = null;

        }catch (Exception $ex)
        {
            //print_r($ex->getMessage());
            if($draw)$draw->destroy();
            if($text)$text->destroy();
            if($image)$image->destroy();
        }

    }


    public static function remove_backup()
    {
        global $wp_filesystem;
        $backup_base = WP_CONTENT_DIR . '/uploads/#original/';
        if(!is_dir($backup_base)){
            self::$error = '备份目录不存在';
            return false;
        }
        if(!function_exists('WP_Filesystem')){
            self::$error = '文件操作无效';
            return false;
        }
        WP_Filesystem();
        $wp_filesystem->rmdir($backup_base,true);
        return true;
    }

    public static function recover_backup()
    {
        global $wp_filesystem,$wpdb;
        $backup_base = WP_CONTENT_DIR . '/uploads/#original/';
        if(!is_dir($backup_base)){
            self::$error = '备份目录不存在';
            return false;
        }
        if(!function_exists('WP_Filesystem')){
            self::$error = '文件操作无效';
            return false;
        }
        WP_Filesystem();
        $ret = copy_dir($backup_base,WP_CONTENT_DIR.'/uploads/');
        if(is_wp_error($ret)){
            self::$error = $ret->get_error_message();
            return false;
        }
        $wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key='_wb_watermark'");
        $wp_filesystem->rmdir($backup_base,true);

        return true;
        //wp_delete_file();
    }

}