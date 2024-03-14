<?php
defined('ABSPATH') or die('No script kiddies please!');


class Utils{

  protected static $headers;

  public static function getFormatedFileSize($bytes, $decimal = 2, $bytesInMM = 1000){
    $sign = ($bytes >= 0) ? '' : '-';
    $bytes = abs($bytes);

    if (is_numeric($bytes)) {
      $position = 0;
      $units = array(" Bytes", " KB", " MB", " GB", " TB");
      while ($bytes >= $bytesInMM && ($bytes / $bytesInMM) >= 1) {
        $bytes /= $bytesInMM;
        $position++;
      }
      return ($bytes == 0) ? '-' : $sign . round($bytes, $decimal) . $units[$position];
    } else {
      return "-";
    }
  }


  public static function startsWith($haystack, $needle){
    //func str_starts_with exists only in php8
    if (!function_exists('str_starts_with')) {
      return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
    } else {
      return str_starts_with($haystack, $needle);
    }
  }


  public static function endsWith($haystack, $needle){
    if (!function_exists('str_ends_with')) {
      return $needle !== '' && substr($haystack, -strlen($needle)) === (string)$needle;
    } else {
      return str_ends_with($haystack, $needle);
    }
  }


  public static function get_file_extensions(){
    return array(
      "image" => array("tif", "tiff", "bmp", "jpg", "jpeg", "gif", "png", "apng", "svg", "webp", "heif", "avif", "ico"),
      "video" => array("mp4", "mpg", "mpeg", "mov", "qt", "webm", "avi", "mp2", "mpe", "mpv", "ogg", "m4p", "m4v", "wmv"),
      "model" => array("glb", "gltf"),
      "spin" => array("spin"),
      "audio" => array("mp3", "wav", "ogg", "flac", "aac", "wma", "m4a"),
    );
  }


  public static function get_sirv_type_by_ext($ext){
    $extensions_by_type = self::get_file_extensions();
    foreach ($extensions_by_type as $type => $extensions) {
      if(in_array($ext, $extensions)){
        return $type;
      }
    }

    return false;
  }


  public static function clean_uri_params($url){
    return preg_replace('/\?.*/i', '', $url);
  }


  public static function get_head_request($url, $protocol_version = 1){
    self::$headers = array();
    $error = NULL;

    $ch = curl_init();
    curl_setopt_array($ch, array(
      CURLOPT_URL => $url,
      CURLOPT_HTTP_VERSION => $protocol_version === 1 ? CURL_HTTP_VERSION_1_1 : CURL_HTTP_VERSION_2_0,
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_HEADERFUNCTION => [Utils::class, 'header_callback'],
      //CURLOPT_HEADER => 1,
      //CURLOPT_NOBODY => 0,
      //CURLOPT_CONNECTTIMEOUT => 1,
      //CURLOPT_TIMEOUT => 1,
      //CURLOPT_CUSTOMREQUEST => 'HEAD',
      //CURLOPT_ENCODING => "",
      //CURLOPT_MAXREDIRS => 10,
      //CURLOPT_USERAGENT => $userAgent,
      //CURLOPT_POSTFIELDS => $data,
      //CURLOPT_HTTPHEADER => $headers,
      //CURLOPT_SSL_VERIFYPEER => false,
      //CURLOPT_VERBOSE => true,
      //CURLOPT_STDERR => $fp,
    ));

    $result = curl_exec($ch);
    $error = curl_error($ch);

    curl_close($ch);

    if( $error ) self::$headers['error'] = $error;

    return self::$headers;
  }


  protected static function header_callback($ch, $header){
    $len = strlen($header);

    if(self::startsWith($header, 'HTTP')){
      $header_data = explode(' ', $header, 3);
      self::$headers['HTTP_protocol'] = $header_data[0];
      self::$headers['HTTP_code'] = $header_data[1];
      self::$headers['HTTP_code_text'] = trim($header_data[2]);

      return $len;
    }

    $header = explode(':', $header, 2);
    if( count($header) < 2 ) return $len;

    list($h_name, $h_value) = $header;
    $h_name = trim($h_name);
    $h_value = trim($h_value);


    if( isset(self::$headers[$h_name]) ){
      if( is_array(self::$headers[$h_name]) ){
        self::$headers[$h_name][] = $h_value;
      }else {
        self::$headers[$h_name] = array(
          self::$headers[$h_name],
          $h_value,
        );
      }
      return $len;
    }

    self::$headers[$h_name] = $h_value;

    return $len;
  }

}
?>
