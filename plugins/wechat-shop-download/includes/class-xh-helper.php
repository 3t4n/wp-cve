<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 公共方法
 *
 * @class    WShop_Helper
 * @since    1.0.0
 * @author   ranj
 */
class WShop_Helper{ 
    public static function upload_image($file_id,$post_ID=0){
        if(!isset( $_FILES[$file_id]['tmp_name'])||!file_exists($_FILES[$file_id]['tmp_name'])){
            return new WP_Error('error','图片上传异常!');
        }
        $file = $_FILES[$file_id]['tmp_name'];
        $mime = false;
        try {
            if ( is_callable( 'exif_imagetype' ) ) {
                $imagetype = @exif_imagetype( $file );
                $mime = ( $imagetype ) ? image_type_to_mime_type( $imagetype ) : false;
            } elseif ( function_exists( 'getimagesize' ) ) {
                $imagesize = @getimagesize( $file );
                $mime = ( isset( $imagesize['mime'] ) ) ? $imagesize['mime'] : false;
            } else {
                $mime = false;
            }
        } catch ( Exception $e ) {
            $mime = false;
        }
        
        if(!$mime){
            return new WP_Error('error','图片内容解析错误，请重新选择图片!');
        }
        
        return self::upload_file($file_id,$post_ID,'image');
    }
    
    /**
     * 调用此接口前，请判断权限
     * current_user_can( 'upload_files' )
     *
     * @param string $file_id $_FILE的字段名
     * @param string $wildcard_mime_types  Mime types, e.g. audio/mpeg or image (same as image/*)
     */
    public static function upload_file($file_id,$post_ID=0,$wildcard_mime_types='image'){
        //         if(!current_user_can( 'upload_files' )){
        //             return new WP_Error('error',__( 'Sorry, you are not allowed to upload files.' ));
        //         }
    
        if(!isset( $_FILES[$file_id]['tmp_name'])||!file_exists($_FILES[$file_id]['tmp_name'])){
            return new WP_Error('error','图片上传异常!');
        }
        /** WordPress Administration File API */
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    
        /** WordPress Image Administration API */
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        if(isset($_REQUEST['filename'])&&!empty($_REQUEST['filename'])){
            $_FILES[$file_id]['name'] = wc_clean($_REQUEST['filename']);
        }
        $wp_filetype = wp_check_filetype_and_ext( $_FILES[$file_id]['tmp_name'],$_FILES[$file_id]['name'] );
         
        if ( ! wp_match_mime_types($wildcard_mime_types, $wp_filetype['type'] ) ) {
            return new WP_Error('error',__( 'The uploaded file is not a valid image. Please try again.' ));
        }
    
        return media_handle_upload($file_id, $post_ID);
    }
    
    
    public static function generate_qrimg($data){
        //缺失GD库
        if(!function_exists('ImageCreate')){
            return null;
        }
        if(!class_exists('QRcode')){require_once 'phpqrcode/phpqrcode.php';}
        $errorCorrectionLevel = 'L'; // 容错级别
        $matrixPointSize = 9; // 生成图片大小
        ob_start();
        QRcode::png($data,false,$errorCorrectionLevel,$matrixPointSize);
        return "data:image/png;base64,".base64_encode(ob_get_clean());
    }
    
    public static function generate_unique_id(){
        static $_unique_id;
        if(!$_unique_id){
            $_unique_id = 0;
        }
        
        return strtolower(WShop_Helper_String::guid().($_unique_id++));
    }
    public static function is_mobile($mobile){
        if(empty($mobile)){
            return false;
        }
        
        return preg_match('/^\+?[\d\-\+]+$/', $mobile);
    }
    
    public static function generate_hash(array $datas,$hashkey){
        ksort($datas);
        reset($datas);
       
        $arg  = '';
        $index=0;
        foreach ($datas as $key=>$val){
            if($key=='hash'){
                continue;
            }
           
            if(is_null($val)||$val===''){
                continue;
            }

            if(!is_string($val)&&!is_numeric($val)){
                continue;
            }
            
            if($index++!=0){
                $arg.="&";
            }
            
            $arg.="$key=$val";
        }
     
        return md5($arg.$hashkey);
    }
}

class  WShop_Helper_Http{
    public static function http_get($url,$require_ssl=false,$ch = null){
        if (! function_exists('curl_init')) {
            throw new Exception('php libs not found!', 500);
        }
        if(!$ch){
            $ch = curl_init();
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_REFERER, home_url('/'));
        if($require_ssl){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt( $ch, CURLOPT_CAINFO, ABSPATH . WPINC . '/certificates/ca-bundle.crt');
        }else{
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
    
        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        if (apply_filters('xunhu_http_post_errcode', $httpStatusCode != 200,$httpStatusCode,$ch)) {
            throw new Exception("status:{$httpStatusCode},response:$response,error:" . $error, $httpStatusCode);
        }
    
        return $response;
    }
    
    public static function http_x($url,$data=null,$method,$require_ssl=false,$ch = null,$post_field_is_array=false){
        if (! function_exists('curl_init')) {
            throw new Exception('php libs not found!', 500);
        }
    
        if(!$ch){
            $ch = curl_init();
        }
    
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);//DELETE,PUT,
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
        if($require_ssl){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt( $ch, CURLOPT_CAINFO, ABSPATH . WPINC . '/certificates/ca-bundle.crt');
        }else{
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
    
        if(!empty($data)){
            if(!$post_field_is_array){
                if(is_array($data)){
                    $data = http_build_query($data);
                }
            }
             
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
    
        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        if (apply_filters('xunhu_http_post_errcode', $httpStatusCode != 200,$httpStatusCode,$ch)) {
            throw new Exception("status:{$httpStatusCode},response:$response,error:" . $error, $httpStatusCode);
        }
    
        return $response;
    }
    public static function http_post($url,$data=null,$require_ssl=false,$ch = null,$post_field_is_array=false){
        if (! function_exists('curl_init')) {
            throw new Exception('php libs not found!', 500);
        }
        if(!$ch){
            $ch = curl_init();
        }
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_REFERER, home_url('/'));
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
        if($require_ssl){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt( $ch, CURLOPT_CAINFO, ABSPATH . WPINC . '/certificates/ca-bundle.crt');
        }else{
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
    
        if(!empty($data)){
            if(!$post_field_is_array){
                if(is_array($data)){
                    $data = http_build_query($data);
                }
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        
        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        if (apply_filters('xunhu_http_post_errcode', $httpStatusCode != 200,$httpStatusCode,$ch)) {
            throw new Exception("status:{$httpStatusCode},response:$response,error:" . $error, $httpStatusCode);
        }

        return $response;
    }
    
    public static function get_client_ip()
    {
        $ip = getenv('HTTP_CLIENT_IP');
        if ($ip && strcasecmp($ip, 'unknown')) {
            return preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches[0] : null;
        }
    
        $ip = getenv('HTTP_X_FORWARDED_FOR');
        if ($ip && strcasecmp($ip, 'unknown')) {
            return preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches[0] : null;
        }
    
        $ip = getenv('REMOTE_ADDR');
        if ($ip && strcasecmp($ip, 'unknown')) {
            return preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches[0] : null;
        }
    
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
        if ($ip && strcasecmp($ip, 'unknown')) {
            return preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches[0] : null;
        }
    
        return null;
    }
}
/**
 * 字符串扩展方法
 * 
 * @author rain
 * @since    1.0.0
 */
class WShop_Helper_String{
    public static function is_null_or_empty($string){
        return is_null($string)||$string==='';
    }
    
    /**
     * 价格格式化
     * @param string $decimal
     * @since 1.0.0
     */
    public static function get_format_price($decimal){
        return sprintf(apply_filters('wshop_format_price', '%01.2f'),$decimal);
    }
    
    /**
     * xml转换成object
     * @param string $xml
     * @param string $return_array
     * @since 1.0.0
     */
    public static function xml_to_obj($xml,$return_array = true){
        $xml_parser = xml_parser_create();
        if(!xml_parse($xml_parser,$xml,true)){
            xml_parser_free($xml_parser);
            return false;
        }else{
            libxml_disable_entity_loader(true);
            return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), $return_array);
        }
    }
    
    /**
     * object|array 转换成xml
     * @param array|object $parameter
     * @return string|NULL
     * @since 1.0.0
     */
    public static function obj_to_xml($parameter){
        if(!$parameter){
            return null;
        }
        
        if(is_object($parameter)){
            $parameter = get_object_vars($parameter);
        }
        
        if(!is_array($parameter)){
            return null;
        }
        $xml = "<xml>";
        foreach ($parameter as $key=>$val){
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
    
        return $xml;
    }
    
    public static function sanitize_key_ignorecase( $key ) {
        $raw_key = $key;
        $key = preg_replace( '/[^a-z0-9_\-A-Z]/', '', $key );
    
        /**
         * Filter a sanitized key string.
         *
         * @since 3.0.0
         *
         * @param string $key     Sanitized key.
         * @param string $raw_key The key prior to sanitization.
         */
        return apply_filters( 'sanitize_key_ignorecase', $key, $raw_key );
    }

    public static function guid(){
        $guid = '';
        //extension=php_com_dotnet.dll
        if (function_exists('com_create_guid')) {
            $guid = com_create_guid();
        } else {
            mt_srand((double) microtime() * 10000); // optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45); // "-"
            $uuid = chr(123) . // "{"
            substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12) . chr(125); // "}"
            $guid = $uuid;
        }
        
        return str_replace('-', '', trim($guid, '{}'));
    }
    
    public static function remove_emoji($source) {
        return preg_replace_callback( '/./u',function (array $match) {
            return strlen($match[0]) >= 4 ? '' : $match[0];
        },
        $source);
    }
}

/**
 * 链接扩展方法
 * 
 * @author rain
 * @since    1.0.0
 */
class WShop_Helper_Uri{
    /**
     * 获取当前文件的url路径(wp-content/wp-plugins)
     * @param string $file
     * @return string
     * @since 1.0.0
     */
    public static function wp_url($file)
    {
        if(!function_exists('wp_normalize_path')){
            function wp_normalize_path( $path ) {
                $path = str_replace( '\\', '/', $path );
                $path = preg_replace( '|/+|','/', $path );
                if ( ':' === substr( $path, 1, 1 ) ) {
                    $path = ucfirst( $path );
                }
                return $path;
            }
        }
        $file_path = wp_normalize_path($file);
        $file_folder=str_replace("\\", "/",dirname($file_path));
        
        if(strpos($file_folder, str_replace("\\", "/",WP_CONTENT_DIR))===0){
            return set_url_scheme(WP_CONTENT_URL).substr($file_folder, strlen(WP_CONTENT_DIR));
        }
        
        if(strpos($file_folder, str_replace("\\", "/",WP_PLUGIN_DIR))===0){
            return set_url_scheme(WP_PLUGIN_URL).substr($file_folder, strlen(WP_PLUGIN_DIR));
        }
        
        return null;
    }
    
    /**
     * 获取当前文件的目录
     * @param string $file
     * @return string
     * @since 1.0.0
     */
    public static function wp_dir($file)
    {
        $dir = trailingslashit( dirname( $file ));
        return rtrim (str_replace('\\', '/',  $dir), '/' );
    }
    
    public static function is_wechat_app(){
    	return isset($_SERVER['HTTP_USER_AGENT'])&& (strripos($_SERVER['HTTP_USER_AGENT'],'micromessenger')!=false);
    }
    
    public static function is_qq_app(){
        return strripos(strtolower($_SERVER['HTTP_USER_AGENT']),'qq')!=false;
    }
   
    public static function is_ios() {
        $ua = strtolower($_SERVER ['HTTP_USER_AGENT']);
        return strripos ( $ua, 'iphone' ) != false || strripos ( $ua, 'ipad' ) != false;
    }
    public static function is_android() {
        return strripos ( strtolower($_SERVER ['HTTP_USER_AGENT']), 'android' ) != false;
    }
    /**
     * 判断是否是移动浏览器
     * @since 1.0.0
     */
    public static function is_app_client(){
        if(!isset($_SERVER['HTTP_USER_AGENT'])){
            return false;
        }
    
        $u=strtolower($_SERVER['HTTP_USER_AGENT']);
        if($u==null||strlen($u)==0){
            return false;
        }
    
        preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/',$u,$res);
    
        if($res&&count($res)>0){
            return true;
        }
    
        if(strlen($u)<4){
            return false;
        }
    
        preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/',substr($u,0,4),$res);
        if($res&&count($res)>0){
            return true;
        }
    
        $ipadchar = "/(ipad|ipad2)/i";
        preg_match($ipadchar,$u,$res);
        if($res&&count($res)>0){
            return true;
        }
    
        return false;
    }
    /**
     * 获取当前链接
     *
     * @return string
     * @since 1.0.0
     */
    public static function get_location_uri(){
        $protocol = (! empty ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] !== 'off' || $_SERVER ['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }
    
    public static function get_new_uri($uri,$params=array()){
        $_params = array();
        $uri = WShop_Helper_Uri::get_uri_without_params($uri,$_params);
        $_params = array_merge($_params,$params);
        
        return $uri.(count($_params)>0?("?".http_build_query($_params)):"");
    }
    
    /**
     * 获取链接不带参数
     * @param string $uri
     * @param array $params
     * @return string
     */
    public static function get_uri_without_params($uri,&$params=array(),$urldecode=true){
        $urls = explode('?', $uri);
        $qty =count($urls);
        if($qty<=1){
            return $uri;
        }
        
        $paramcs = explode('&', $urls[1]);
        foreach ($paramcs as $paramc){
            $ps = explode('=', $paramc);
            if(count($ps)!=2){
                continue;
            }
        
            if($urldecode){
                $params[$ps[0]]=urldecode($ps[1]);
            }else{
                $params[$ps[0]]=$ps[1];
            }
            
        }
        
        return $urls[0];
    }
}

/**
 * 数组扩展方法
 * @author   ranj
 * @since    1.0.0
 */
class WShop_Helper_Array{
    public static function is_null_or_empty($source){
        return is_null($source)||!is_array($source)||count($source)==0;
    }
    
    /**
     * 判断数组中是否包含
     * 
     * @param array $source
     * @param function $where
     * @return bool
     */
    public static function any($source,$where=null){
        if(self::is_null_or_empty($source)){
            return false;
        }
        
        $args_qty = func_num_args();
        $params = array();
        $params[]=null;
        if($args_qty>2){
            for ($i=2;$i<$args_qty;$i++){
                $params[]=func_get_arg($i);
            }
        }
        
        foreach ($source as $item){
            if(is_null($where)){
                return true;
            }
        
            $params[0]=$item;
            if(call_user_func_array($where, $params)){
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * 返回一个新的筛选后的数组
     * 
     * @param array $source
     * @param function $where
     * @return array
     * @since 1.0.0
     */
    public static function where($source,$where){
        if(self::is_null_or_empty($source)){
            return null;
        }
        
        $args_qty = func_num_args();
        $params = array();
        $params[]=null;
        if($args_qty>2){
            for ($i=2;$i<$args_qty;$i++){
                $params[]=func_get_arg($i);
            }
        }
        if(is_null($where)){
           throw new Exception('Parameter "where" is required!');
        }
        
        $results = array();
        foreach ($source as $item){
            $params[0]=$item;
            if(call_user_func_array($where, $params)){
               $results[]=$item;
            }
        }
        
        return $results;
    }
    
    /**
     * 获取数组第一项
     * 
     * @param array $source
     * @param function $where
     * @return mixed
     * @since 1.0.0
     */
    public static function first_or_default($source ,$where=null){
        if(self::is_null_or_empty($source)){
            return null;
        }
        
        $args_qty = func_num_args();
        $params = array();
        $params[]=null;
        if($args_qty>2){
            for ($i=2;$i<$args_qty;$i++){
                $params[]=func_get_arg($i);
            }
        }
        
        foreach ($source as $item){
            if(is_null($where)){
                return $item;
            }
            
            $params[0]=$item;
            if(call_user_func_array($where, $params)){
                return $item;
            }
        }
        
        return null;
    }
}

class WShop_Helper_Html_Form{
    public static function generate_submit_data($form_id,$data_name){
        ?>
       $(document).trigger('wshop_form_<?php echo esc_attr($form_id);?>_submit',<?php echo $data_name;?>);
       <?php 
   }
    
   /**
    * 
    * @param string $form_id
    * @param string $data_name
    * @since 1.1.1
    */
   public static function generate_field_scripts($form_id,$data_name,$html_id=null){
       $form_name = $data_name;
       $html_id = $html_id?$html_id:$form_id."_".$data_name;
       
       ?>
      <script type="text/javascript">
      	(function($){
			$(document).bind('wshop_form_<?php echo esc_attr($form_id);?>_submit',function(e,m){
				m.<?php echo esc_attr($form_name)?>=$('#<?php echo esc_attr($html_id)?>').val();
			});

		})(jQuery);
		</script>
      <?php 
   }
   
    /**
     * 
     * @param array $fields
     */
    public static function generate_html($form_id,$fields){
        if(!$fields||!is_array($fields)){
            return '';
        }
        
        $defaults = array (
            'title' => '',
            'disabled' => false,
            'required'=>false,
            'class' => '',
            'css' => '',
            'placeholder' => '',
            'type' => 'text',//You can set func
            'default'=>null,
            'custom_attributes' => array (),
            //自定义输出
            'generate_html'=>null
        );
        
        $html='';
        
        foreach ($fields as $name=>$settings){
            $settings = wp_parse_args ( $settings, $defaults );

            if(!is_string($settings['type'])&&is_callable($settings['type'])){
                $html.= call_user_func_array($settings['type'],array($form_id,$name,$settings));
            }else{
                $html.=call_user_func_array(__CLASS__."::generate_{$settings['type']}_html",array($form_id,$name,$settings));
            }
        }
        
        return $html;
    } 
    public static function generate_hidden_html($form_id,$data_name,$settings){
        $html_name = $data_name;
        $name = $form_id."_".$data_name;
        
        $html_id = isset($settings['id'])&&!empty($settings['id'])?$settings['id']:$name;
       
        ob_start();
        ?>
        <input type="hidden" id="<?php echo esc_attr($html_id)?>" name="<?php echo esc_attr($html_name)?>" value="<?php echo esc_attr(isset($settings['default'])?$settings['default']:'')?>"  />
        <?php
        self::generate_field_scripts($form_id, $html_name,$html_id);
        return ob_get_clean();
    }
    
    public static function generate_text_html($form_id,$data_name,$settings){
        $html_name = $data_name;
        $name = $form_id."_".$data_name;
        $html_id = isset($settings['id'])&&!empty($settings['id'])?$settings['id']:$name;
        ob_start();
        ?>
        <div class="xunhu-form-group">
            <div class="font-16 xunhu-form-lable <?php echo isset($settings['required'])&&$settings['required']?'xunhu-required':'';?>"><?php echo isset($settings['title'])?esc_html($settings['title']):null?></div>
            <input type="text" id="<?php echo esc_attr($html_id)?>" name="<?php echo esc_attr($html_name)?>" value="<?php echo esc_attr(isset($settings['default'])?$settings['default']:'')?>" placeholder="<?php echo isset($settings['placeholder'])?esc_attr($settings['placeholder']):null?>" class="xunhu-input <?php echo isset($settings['class'])?esc_attr($settings['class']):null?>" style="<?php echo isset($settings['css'])?esc_attr($settings['css']):null?>" <?php disabled( isset($settings['disabled'])?$settings['disabled']:'', true ); ?> <?php echo self::get_custom_attribute_html( $settings ); ?> />
            <?php if(isset($settings['description'])&&!empty($settings['description'])):?>
                <div class="font-16 xunhu-form-lable"><?php echo isset($settings['description'])?$settings['description']:null;?></div>
            <?php endif;?>
        </div>
        <?php 
        self::generate_field_scripts($form_id, $html_name,$html_id);
        return ob_get_clean();
    }

    public static function generate_email_html($form_id,$data_name,$settings){
         $html_name = $data_name;
        $name = $form_id."_".$data_name;
        
        $html_id = isset($settings['id'])&&!empty($settings['id'])?$settings['id']:$name;
        
        ob_start();
        ?>
        <div class="xunhu-form-group">
            <div class="font-16 xunhu-form-lable <?php echo isset($settings['required'])&&$settings['required']?'xunhu-required':'';?>"><?php echo isset($settings['title'])?esc_html($settings['title']):null?></div>
            <input type="text" id="<?php echo esc_attr($html_id)?>" name="<?php echo esc_attr($html_name)?>" value="<?php echo esc_attr(isset($settings['default'])?$settings['default']:'')?>" placeholder="<?php echo isset($settings['placeholder'])?esc_attr($settings['placeholder']):null?>" class="xunhu-input <?php echo isset($settings['class'])?esc_attr($settings['class']):null?>" style="<?php echo isset($settings['css'])?esc_attr($settings['css']):null?>" <?php disabled( isset($settings['disabled'])?$settings['disabled']:'', true ); ?> <?php echo self::get_custom_attribute_html( $settings ); ?> />
            <?php if(isset($settings['description'])&&!empty($settings['description'])):?>
                <div class="font-16 xunhu-form-lable"><?php echo isset($settings['description'])?$settings['description']:null;?></div>
            <?php endif;?>
        </div>
        <?php 
        self::generate_field_scripts($form_id, $html_name,$html_id);
        return ob_get_clean();
    }
    public static function generate_password_html($form_id,$data_name,$settings){
        $html_name = $data_name;
        $name = $form_id."_".$data_name; 
        $html_id = isset($settings['id'])&&!empty($settings['id'])?$settings['id']:$name;
        
        ob_start();
        ?>
        <div class="xunhu-form-group">
            <div class="font-16 xunhu-form-lable <?php echo isset($settings['required'])&&$settings['required']?'xunhu-required':'';?>"><?php echo isset($settings['title'])?esc_html($settings['title']):null?></div>
            <input type="password" id="<?php echo esc_attr($html_id)?>" name="<?php echo esc_attr($html_name)?>" value="<?php echo esc_attr(isset($settings['default'])?$settings['default']:'')?>" placeholder="<?php echo isset($settings['placeholder'])?esc_attr($settings['placeholder']):null?>" class="xunhu-input <?php echo isset($settings['class'])?esc_attr($settings['class']):null?>" style="<?php echo isset($settings['css'])?esc_attr($settings['css']):null?>" <?php disabled( isset($settings['disabled'])?$settings['disabled']:'', true ); ?> <?php echo self::get_custom_attribute_html( $settings ); ?> />
            <?php if(isset($settings['description'])&&!empty($settings['description'])):?>
                <div class="font-16 xunhu-form-lable"><?php echo isset($settings['description'])?$settings['description']:null;?></div>
            <?php endif;?>
        </div>
        <?php 
        self::generate_field_scripts($form_id, $html_name,$html_id);
        return ob_get_clean();
    }
    
    public static function generate_select_html($form_id,$data_name,$settings){
        $html_name = $data_name;
        $name = $form_id."_".$data_name;
        $html_id = isset($settings['id'])&&!empty($settings['id'])?$settings['id']:$name;
        ob_start();
        ?>
        <div class="xunhu-form-group">
            <div class="font-16 xunhu-form-lable <?php echo isset($settings['required'])&&$settings['required']?'xunhu-required':'';?>"><?php echo isset($settings['title'])?esc_html($settings['title']):null?></div>
            <select id="<?php echo esc_attr($html_id)?>" name="<?php echo esc_attr($html_name)?>" class="xunhu-input <?php echo isset($settings['class'])?esc_attr($settings['class']):null?>" style="<?php echo isset($settings['css'])?esc_attr($settings['css']):null?>" <?php disabled( isset($settings['disabled'])?$settings['disabled']:'', true ); ?> <?php echo self::get_custom_attribute_html( $settings ); ?> >
                <?php
                if(isset($settings['options'])):
                    foreach ($settings['options'] as $key=>$val):?>
                        <option <?php selected( $key, esc_attr( isset($settings['default'])?$settings['default']:'') ); ?> value="<?php echo esc_html($key);?>"><?php echo esc_html($val);?></option>
                    <?php
                    endforeach;
                endif;?>
                <option>123</option>
            </select>
            <?php if(isset($settings['description'])&&!empty($settings['description'])):?>
                <div class="font-16 xunhu-form-lable"><?php echo isset($settings['description'])?$settings['description']:null;?></div>
            <?php endif;?>
        </div>
        <?php 
        self::generate_field_scripts($form_id, $html_name,$html_id);
        return ob_get_clean();
    }
     
    public static function generate_textarea_html($form_id,$data_name,$settings){
        $html_name = $data_name;
        $name = $form_id."_".$data_name;
        $html_id = isset($settings['id'])&&!empty($settings['id'])?$settings['id']:$name;
        
        ob_start();
        ?>
        <div class="xunhu-form-group">
            <div class="font-16 xunhu-form-lable <?php echo isset($settings['required'])&&$settings['required']?'xunhu-required':'';?>"><?php echo isset($settings['title'])?esc_html($settings['title']):null?></div>
            <textarea id="<?php echo esc_attr($html_id)?>" name="<?php echo esc_attr($html_name)?>" placeholder="<?php echo isset($settings['placeholder'])?esc_attr($settings['placeholder']):null?>" class="xunhu-input <?php echo isset($settings['class'])?esc_attr($settings['class']):null?>" style="<?php echo isset($settings['css'])?esc_attr($settings['css']):null?>" <?php disabled( isset($settings['disabled'])?$settings['disabled']:'', true ); ?> <?php echo self::get_custom_attribute_html( $settings ); ?> ><?php echo esc_textarea(isset($settings['default'])?$settings['default']:'')?></textarea>
            <?php if(isset($settings['description'])&&!empty($settings['description'])):?>
                <div class="font-16 xunhu-form-lable"><?php echo isset($settings['description'])?$settings['description']:null;?></div>
            <?php endif;?>
        </div>
        <?php 
        self::generate_field_scripts($form_id, $html_name,$html_id);
        return ob_get_clean();
    }
    
    public static function generate_checkbox_html($form_id,$data_name,$settings){
        $html_name = $data_name;
        $name = $form_id."_".$data_name;
        $html_id = isset($settings['id'])&&!empty($settings['id'])?$settings['id']:$name;
        
        ob_start();
        ?>
        <div class="xunhu-form-group">
            <div class="font-16 xunhu-form-lable <?php echo isset($settings['required'])&&$settings['required']?'xunhu-required':'';?>"><?php echo isset($settings['title'])?esc_html($settings['title']):null?></div>
            <div class="radio">
                <input type="checkbox" id="<?php echo esc_attr($html_id)?>" name="<?php echo esc_attr($html_name)?>" placeholder="<?php echo isset($settings['placeholder'])?esc_attr($settings['placeholder']):null?>" class="<?php echo isset($settings['class'])?esc_attr($settings['class']):null?>" style="<?php echo isset($settings['css'])?esc_attr($settings['css']):null?>" <?php disabled( isset($settings['disabled'])?$settings['disabled']:'', true ); ?> <?php echo self::get_custom_attribute_html( $settings ); ?>  value="yes" <?php checked( isset($settings['default'])?$settings['default']:'', 'yes' ); ?>  />
            </div>
            <?php if(isset($settings['description'])&&!empty($settings['description'])):?>
                <div class="font-16 xunhu-form-lable"><?php echo isset($settings['description'])?$settings['description']:null;?></div>
            <?php endif;?>
        </div>
        <?php 
        self::generate_field_scripts($form_id, $html_name,$html_id);
        return ob_get_clean();
    }
    
    private static function get_custom_attribute_html($data) {
        $custom_attributes = array ();
    
        if (! empty ( $data ['custom_attributes'] ) && is_array ( $data ['custom_attributes'] )) {
            	
            foreach ( $data ['custom_attributes'] as $attribute => $attribute_value ) {
                $custom_attributes [] = esc_attr ( $attribute ) . '="' . esc_attr ( $attribute_value ) . '"';
            }
        }
    
        return implode ( ' ', $custom_attributes );
    }
}