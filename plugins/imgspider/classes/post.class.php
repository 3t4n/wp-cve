<?php


class WB_IMGSPY_Post
{

    public static $last_err = null;

    protected static $mime_to_ext = array (
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/bmp' => 'bmp',
        'image/tiff' => 'tif'
    );

    protected static $ext_to_mime = array (
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'tif' => 'image/tiff',
    );


    public static function update_post($post_ID,$post,$update,$image_list,$img_html_list){

        $content = $post->post_content;
        $cnf = WB_IMGSPY_Conf::opt();
        $idx = -1;
        foreach($img_html_list as $r){

            if(!isset($image_list[$r['key']])){
                continue;
            }
            $idx++;
            $ret = $image_list[$r['key']];
            $new_html = self::img_html($ret,$idx,$post->post_title,$cnf);
            $content = str_replace($r['html'],$new_html,$content);

            if(!$idx){
                self::update_post_thumb($post_ID,$ret['id']);
            }

        }


        if($idx>-1){
            wp_update_post(array('ID'=>$post_ID,'post_content'=>$content));
        }

    }

    public static function update_post_thumb($post_ID,$thumb_id){

        $thumbnail = WB_IMGSPY_Conf::cnf('thumbnail');
        if(!$thumbnail){
            return;
        }

        $_thumbnail_id = get_post_meta($post_ID,'_thumbnail_id',true);
        if($_thumbnail_id){
            return;
        }

        update_post_meta($post_ID,'_thumbnail_id',$thumb_id);


    }

    public static function upload_image_name($image_url){

        $image_path = parse_url($image_url, PHP_URL_PATH);
        $filename =  basename($image_path);
        if(!preg_match('#\.(jpg|jpeg|gif|png)$#i',$filename)){

            if(preg_match('#wx_fmt=([a-z]+)#',$image_url,$m)){
                $filename .= '.'.$m[1];
            }else{
                $filename .= '.jpg';
            }
        }

        $filename = urldecode($filename);
        $filename = str_replace(array('%20',' '), '_', $filename);
        $filename =  str_replace('*','',$filename);


        $config = WB_IMGSPY_Conf::opt();

        $rule = $config['rule'];
        if($rule['file_name']=='2' && $rule['custom_name']){
            $pos = strrpos($filename,'.');
            $ext = substr($filename,$pos);
            $name = substr($filename,0,$pos);

            $ymd = explode('-',current_time('Y-m-d'));
            $random = self::random(5);
            $search = array('%filename%','%date%','%year%','%month%','%day%','%random%');
            $replace = array($name,implode('',$ymd),$ymd[0],$ymd[1],$ymd[2],$random);
            $filename = str_replace($search,$replace,$rule['custom_name']).$ext;

        }

        return $filename;
    }


    public static function random($num=5){
        $str = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $len = strlen($str);
        $a = array();
        for($i=0;$i<$num;$i++){
            $j = mt_rand(0,$len-1);
            $a[] = $str[$j];
        }
        return implode('',$a);

    }

    public static function upload_img_file($file_id,$post_id,$post_date,&$error=null)
    {
        if(!isset($_FILES[$file_id]) || empty($_FILES[$file_id])){
            $error = 'empty file';
            return false;
        }
        $file = $_FILES[$file_id];
        if(isset($file['error']) && $file['error'] > 0 ){
            $error = 'error ['.$file['error'].']';
            return false;
        }
        if(!isset($file['tmp_name']) || !$file['tmp_name']){
            $error = 'empty tmp file';
            return false;
        }
        if(isset($_POST['filename']) && $_POST['filename']){
            $file['name'] = sanitize_text_field($_POST['filename']);
        }

        //print_r($file);

        if(!$file['name']){
            $error = 'empty file name';
            return false;
        }
        if(!preg_match('#(jpg|jpeg|gif|png)$#i',$file['type'])){
            $error = 'not image file';
            return false;
        }

        $image_url = 'file://fackpath/'.$file['name'];

        return self::save_image_data($file['tmp_name'],$image_url,$post_id,$post_date,true);


    }

    public static function upload_img_base64($dataurl,$post_id,$post_date){

        list($data,$image)=explode(';',$dataurl);
        list($field,$type)=explode(':',$data);
        list($encoding,$content)=explode(',',$image);
        $extension = '';
        if ($type=='image/png') {
            $extension='png';
        } else if ($type == 'image/jpeg'){
            $extension = 'jpg';
        }else{
            return false;
        }
        $name = md5($dataurl);
        if(isset($_POST['filename']) && $_POST['filename']){
            $name = sanitize_text_field($_POST['filename']);
        }
        if(!preg_match('#\.(jpg|jpeg|png|gif)$#i',$name)){
            $name = $name .'.'.$extension;
        }

        $image_url = 'file://fackpath/'.$name;

        return self::save_image_data(base64_decode($content),$image_url,$post_id,$post_date);

    }

    public static function upload($image_url,$post_id,$post_date){




        $arg = array();
        if($_SERVER && isset($_SERVER['HTTP_USER_AGENT'])){
            $arg['user-agent'] = $_SERVER['HTTP_USER_AGENT'];
        }

        @ini_set('memory_limit', '50M');

        $image_data = WB_IMGSPY_Down::down($image_url,$arg);


        if(!$image_data){
            self::$last_err = WB_IMGSPY_Down::$last_err;
            return $image_data;
        }

        return self::save_image_data($image_data,$image_url,$post_id,$post_date);


    }

    private static function save_image_data($image_data,$image_url,$post_id,$post_date,$upload=false){
        do{
            $filename = self::upload_image_name($image_url);
            $time = false;
            if($post_date) {
                $time = date('Y/m',strtotime($post_date));
            }
            $uploads = wp_upload_dir ( $time );

            $filename = urldecode($filename);
            $filename = str_replace(array('%20',' '), '_', $filename);
            //图片文件名称
            $unique_filename_callback = null;
            $filename = wp_unique_filename ( $uploads ['path'], $filename, $unique_filename_callback );

            $new_file = $uploads ['path'] . '/' . $filename;

            if($upload){
                $move_new_file = @move_uploaded_file( $image_data, $new_file );
                if(!$move_new_file){
                    self::$last_err = 'move uploaded file fail';
                    break;
                }
            }else{
                //下载图片
                if (!file_put_contents($new_file,$image_data)){
                    self::$last_err = 'save new file fail';
                    break;
                }
            }


            // Compute the URL
            $url = $uploads ['url'] . '/'.$filename;

            $name_parts = pathinfo ( $filename );

            $name = $name_parts['filename'];

            $title = $name;
            $content = '';

            $ret = array(
                'title'=>$title,
                'url'=>$url,
            );

            $mine_type = wp_get_image_mime($new_file);

            if(!$mine_type && isset(self::$ext_to_mime[$name_parts['extension']])){
                $mine_type = self::$ext_to_mime[$name_parts['extension']];
            }

            WB_IMGSPY_Image::resize_image($new_file,$mine_type);

            // Construct the attachment array
            $attachment = array (
                'post_mime_type' => $mine_type,
                'guid' => $url,
                'post_parent' => $post_id,
                'post_title' => $title,
                'post_content' => $content
            );


            $id = wp_insert_attachment ( $attachment, $new_file, $post_id );

            if (is_wp_error ( $id )) {
                self::$last_err = 'insert attachment fail ['.$id->get_error_message().']';
                return $ret;
            }

            $ret['id'] = $id;

            $metadata = wp_generate_attachment_metadata( $id, $new_file );
            if(!$metadata){
                self::$last_err = 'generate attachment meta data fail ';
                return $ret;
            }

            if (is_wp_error ( $metadata )) {
                self::$last_err = 'generate attachment meta data fail ['.$metadata->get_error_message().']';
                return $ret;
            }else if(!isset ( $metadata ['file'] )) {
                self::$last_err = 'generate attachment meta data fail [empty file]';
                return $ret;
            }else{
                wp_update_attachment_metadata ( $id, $metadata );
                $ret['width'] = $metadata['width'];
                $ret['height'] = $metadata['height'];
            }
            return $ret;

        }while(false);

        return false;
    }


    public static function img_html($ret,$idx,$post_title,$config){

        $align = 'none';
        $rule = $config['rule'];
        if( $rule['align']){
            $align = $rule['align'];
        }
        $img_html = '<img class="align'.$align.' size-full';
        if($ret['id']){
            $img_html .= ' wp-image-'.$ret['id'];
        }
        $img_html .= '" src="'.$ret['url'].'"';
        if($ret['width']){
            $img_html .= ' width="'.$ret['width'].'"';
        }
        if($ret['width']){
            $img_html .= ' height="'.$ret['height'].'"';
        }

        if( $rule['title_alt'] == '1'){
            $alt_title = str_replace(array('%filename%','%postname%'),array($ret['title'],$post_title),$rule['custom_title']).'-'.($idx+1);
        }else{
            $alt_title = $ret['title'];

        }


        $img_html .= ' alt="'.$alt_title.'" title="'.$alt_title.'" />';

        return $img_html;

    }


    public static function find_img_src($post,&$find_img_html=array()){

        $cnf = WB_IMGSPY_Conf::opt();


        $content = $post->post_content;


        if(!preg_match_all('#<img[^>]+>#is',$content,$match)){
            return false;
        }

        $host_name = parse_url(home_url(),PHP_URL_HOST);
        $host_name = str_replace('www.','',$host_name);


        $allow_domain = array();
        if(!isset($cnf['filter'])){
            $cnf['filter'] = array();
        }
        $filter = $cnf['filter'];
        //域名
        if(isset($filter['domain']) && $filter['domain'] && is_array($filter['domain'])){
            $allow_domain = $filter['domain'];
        }
        array_push($allow_domain,$host_name);

        //类型
        $file_ext = array();
        if(isset($filter['type']) && $filter['type']){
            if(is_array($filter['type']))foreach($filter['type'] as $type=>$active){
                if($active){
                    $file_ext[] = $type;
                }
            }
        }

        //指定顺序不采集
        $except_index = array();
        if(isset($filter['except_index']) && $filter['except_index']){
            $except_index = explode(',',$filter['except_index']);
        }
        if(in_array('z',$except_index)){
            $except_index[] = count($match[0]);
        }

        //像素低于 不采集
        $min_width = intval($filter['min_width']);



        $img_list = array();

        foreach($match[0] as $k=>$img_html){


            if($except_index && in_array(($k+1),$except_index)){
                continue;
            }


            if(preg_match('#data-src=([^\s]+)#is',$img_html,$img_match)){

            }else if(preg_match('#src=([^\s]+)#is',$img_html,$img_match)){

            }else{
                continue;
            }
            $img_src = trim(preg_replace('#/?>$#','',$img_match[1]),'\'"');
            if(!preg_match('#^https?://#is',$img_src)){
                continue;
            }
            $find_id = false;
            foreach($allow_domain as $domain){
                if(strpos($img_src,$domain)){
                    $find_id = true;
                    break;
                }
            }
            if($find_id){
                continue;
            }

            $img =  rawurldecode($img_src);
            $img = str_replace('&amp;','&',$img);


            $img_name = self::upload_image_name($img);
            if($file_ext)foreach($file_ext as $ext){
                if(preg_match('#\.'.$ext.'#i',$img_name)){
                    $find_id = true;
                    break;
                }
            }
            if($find_id){
                continue;
            }


            if($min_width && preg_match('#width=[^\s]+#i',$img_html,$width_match)){
                $width = intval(trim($width_match[1],"\"'"));
                if($width<$min_width){
                    continue;
                }
            }



            $key = md5($img);
            $img_list[$key] = $img;

            $find_img_html[] = array('key'=>$key,'html'=>$img_html);
        }


        return $img_list;
    }



}