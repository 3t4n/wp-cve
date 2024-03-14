<?php

defined('ABSPATH') or die('No script kiddies please!');

//TODO: update data in table wp posts. For now in guid old link when file is updated


class SirvProdImageHelper{

  const SIRV_AUTHOR = 5197000;


  static function insert_attachment($sirv_url, $parent_post_id, $previous_attachment_id = null){

    $sirv_item_type_data = self::get_sirv_item_type($sirv_url);

    $attachment = array(
      'guid'           => $sirv_url,
      'post_author' => self::SIRV_AUTHOR,
      //'post_mime_type' => $sirv_item_type_data['type'],
      'post_mime_type' => "image/sirv",
      'post_title'     => preg_replace('/\.[^.]+$/', '', basename($sirv_url)),
      'post_content'   => '',
      'post_status'    => 'inherit',
      'meta_input'     => array(
        "_wp_attachment_image_alt" => preg_replace('/\.[^.]+$/', '', basename($sirv_url)),
        "sirv_woo_product_image_attachment" => $sirv_url,
        "_wp_attachment_metadata" => self::get_sirv_item_metadata($sirv_url, $sirv_item_type_data['sirv_type']),
      ),
    );

    if(! empty($previous_attachment_id)){
      $attachment['ID'] = $previous_attachment_id;
    }

    $attach_id = wp_insert_attachment($attachment, $sirv_url, $parent_post_id);

    return $attach_id;
  }


  public static function get_sirv_item_type($sirv_url){
    $sirv_type = array("sirv_type" => '');

    $filetype = wp_check_filetype(basename($sirv_url), null);

    if( !empty($filetype) && !empty($filetype['type'])){
      $sirv_type['ext'] = $filetype['ext'];
      $sirv_type['type'] = $filetype['type'];
      list($type, $ext) = explode('/', $filetype['type']);
      switch ($type) {
        case 'sirv':
          if($ext == 'spin') $sirv_type['sirv_type'] = 'spin';
          break;

        default:
          $sirv_type['sirv_type'] = $type;
          break;
      }
    }

    return $sirv_type;
  }


  protected static function get_sirv_item_metadata($sirv_url, $sirv_item_type){

    $sirv_metadata = array("sirv_type" => $sirv_item_type);

    $allow_dimensions_types = array('image', 'video');

    if( in_array($sirv_item_type, $allow_dimensions_types) ){
      $string_metadata = @file_get_contents($sirv_url . '?info');

      if( !empty($string_metadata)){
        $metadata = json_decode($string_metadata, true);


        if (isset($metadata['original']['width'])) $sirv_metadata['width'] = $metadata['original']['width'];
        if (isset($metadata['original']['height'])) $sirv_metadata['height'] = $metadata['original']['height'];
      }
    }else{
      $dimensions = @getimagesize($sirv_url . "?thumb");

      if( !empty($dimensions) && is_array($dimensions)){
        $sirv_metadata['width'] = $dimensions[0];
        $sirv_metadata['height'] = $dimensions[1];
      }
    }

    $filesize = self::get_filesize($sirv_url, $sirv_item_type);

    if( !empty($filesize) ) $sirv_metadata['filesize'] = $filesize;

    return $sirv_metadata;
  }


  protected static function get_filesize($sirv_url, $sirv_item_type){
  $size = null;

  if( $sirv_item_type == 'spin') $sirv_url .= "?image";

  $ch = curl_init($sirv_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_ENCODING, '');
  curl_setopt($ch, CURLINFO_HEADER_OUT, true);

  try {
    curl_exec($ch);

    $data = curl_exec($ch);
    if (extension_loaded('mbstring')) {
      $size = mb_strlen($data, 'utf-8');
    } else {
        $headers_data = get_headers($sirv_url, true);
        $size = (int) $headers_data['Content-Length'];
    }

  } catch (Exception $e) {
    //log
  }finally{
    curl_close($ch);
    return $size;
  }
}


  static function remove_attachment($attachment_id){
    //return WP_Post|false|null Post data on success, false or null on failure.
    wp_delete_attachment($attachment_id);
  }


  static function update_attachment($sirv_url, $parent_post_id, $previous_attachment_id){
    $attach_id = self::insert_attachment($sirv_url, $parent_post_id, $previous_attachment_id);
    return $attach_id;
  }


  static function insert_prod_image($post_id, $attachment_id){
    //set_post_thumbnail($post_id, $attachment_id);
    update_post_meta($post_id, '_thumbnail_id', $attachment_id);

  }


  static function update_prod_image(){

  }


  static function remove_prod_image($post_id){
    //return bool True on success, false on failure.
    delete_post_thumbnail($post_id);
  }

  static function remove_wc_prod_image_metabox(){

  }
}
