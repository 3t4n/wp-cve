<?php

defined('ABSPATH') or die('No script kiddies please!');

class resizeHelper{

  public static function addPreventWPResizeOnUploadFilter(){
    add_filter('intermediate_image_sizes_advanced', array(get_called_class(), 'preventResizeOnUpload'), 10, 3);
  }


  public static function preventResizeOnUpload($new_sizes, $image_meta, $attachment_id){
    return self::getFilteredSizes($new_sizes);
  }


  protected static function getFilteredSizes($sizes){
    $preventedSizes = self::getPreventSizes();

    if(empty($preventedSizes)) return $sizes;

    if(count($preventedSizes) == count($sizes)) return array();

    foreach ($preventedSizes as $size_name => $size) {
      unset($sizes[$size_name]);
    }
    return $sizes;
  }


  public static function deleteThumbs($attachment_id){

    $fullFilePath = get_attached_file($attachment_id);
    $fileMeta = wp_get_attachment_metadata($attachment_id);
    $pathToImg = pathinfo($fullFilePath, PATHINFO_DIRNAME);
    $preventedSizes = self::getPreventSizes();
    $files = self::getImagesToDelete($fileMeta['sizes'], $preventedSizes, $pathToImg);
    $count = 0;
    $filesize = 0;

    //need check if diff sizes has the same size like 100x100 than WP creates only one image with that size.
    $removed_sizes = array();

    if(!empty($files)){
      foreach ($files as $size_name => $file_data) {
        $tmp_size = @filesize($file_data['file']);
        if(@unlink($file_data['file'])){
          $removed_sizes[] = $file_data['size'];
          unset($fileMeta['sizes'][$size_name]);

          $count++;
          $filesize += $tmp_size;
        }else{
          if(in_array($file_data['size'], $removed_sizes)) unset($fileMeta['sizes'][$size_name]);
        }
      }
    }

    wp_update_attachment_metadata($attachment_id, $fileMeta);

    return array($count, $filesize);
  }


  public static function regenerateThumbs($attachment_id){
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . "wp-includes/pluggable.php");

    $count = 0;

    $new_sizes = self::getNewSizes($attachment_id);
    $created_sizes = self::makeWpSubsizes($attachment_id, $new_sizes);
    $count = count(self::getUniqueSizes($created_sizes));

    return $count;
  }


  protected static function getNewSizes($attachment_id){
    $new_sizes = wp_get_registered_image_subsizes();
    return apply_filters('intermediate_image_sizes_advanced', $new_sizes, array(), $attachment_id);
  }


  protected static function makeWpSubsizes( $attachment_id, $new_sizes){
    $image_meta = wp_get_attachment_metadata($attachment_id);

    if (empty($image_meta) || !is_array($image_meta) || empty($new_sizes)) {
      return array();
    }

    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $file = wp_get_original_image_path($attachment_id);

    if (false === $file || !file_exists($file)) return array();

    $pathToFile = pathinfo($file, PATHINFO_DIRNAME);

    if (isset($image_meta['sizes']) && is_array($image_meta['sizes'])) {
      foreach ($image_meta['sizes'] as $size_name => $size_meta) {
        if ( array_key_exists($size_name, $new_sizes) && self::isThumbExists($pathToFile . DIRECTORY_SEPARATOR . $size_meta['file']) ) {
          unset($new_sizes[$size_name]);
        }
      }
    } else {
      $image_meta['sizes'] = array();
    }

    $editor = wp_get_image_editor($file);

    if (is_wp_error($editor)) {
      sirv_debug_msg(__FILE__ . ' editor error: image '. $file .' cannot be edited');
      return array();
    }

    $new_meta_sizes = array();

    if (method_exists($editor, 'make_subsize')) {
      foreach ($new_sizes as $new_size_name => $new_size_data) {
        $new_size_meta = $editor->make_subsize($new_size_data);

        if (is_wp_error($new_size_meta)) {
          // TODO: Log errors.
        } else {
          $new_meta_sizes[$new_size_name] = $new_size_meta;
        }
      }
    } else {
      $created_sizes = $editor->multi_resize($new_sizes);

      if (!empty($created_sizes)) {
        $new_meta_sizes = $created_sizes;
      }
    }

    $image_meta['sizes'] = array_merge($image_meta['sizes'], $new_meta_sizes);
    wp_update_attachment_metadata($attachment_id, $image_meta);

    return $new_meta_sizes;

  }


  public static function getUniqueSizes($data){
    $unique_sizes = array();

    foreach ($data as $size_data) {
      $size = $size_data['width'] . 'x' . $size_data['height'];
      if(!in_array($size, $unique_sizes)){
        $unique_sizes[] = $size;
      }
    }

    return $unique_sizes;
  }


  protected static function isScaled($url, $search_substr){
    return stripos($url, $search_substr) !== false;
  }


  protected static function isThumbExists($thumbPath){
    return file_exists($thumbPath) && is_file($thumbPath);
  }


  protected static function getPreventSizes(){
    return json_decode(get_option('SIRV_PREVENTED_SIZES'), true);
  }


  protected static function getImagesToDelete($meta, $preventedSizes, $pathToImg){
    $imagesToDelete = array();

    if(!empty($preventedSizes)){
      foreach ($preventedSizes as $size_name => $size) {
        if(isset($meta[$size_name])){
          $imagesToDelete[$size_name] = array(
            'file' => wp_normalize_path($pathToImg . DIRECTORY_SEPARATOR . $meta[$size_name]['file']),
            'size' => $meta[$size_name]['width'] . 'x' . $meta[$size_name]['height']
          );
        }
      }
    }

    return $imagesToDelete;
  }

}

?>
