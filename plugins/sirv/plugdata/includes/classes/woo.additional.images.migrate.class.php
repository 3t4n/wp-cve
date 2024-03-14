<?php
defined('ABSPATH') or die('No script kiddies please!');

class WooAdditionalImagesMigrate{
  protected static $table_name = 'wp_postmeta';
  protected static $db_wai_metakey = '_wc_additional_variation_images';
  protected static $db_sirv_gallery_metakey = "_sirv_woo_gallery_data";
  protected static $db_marked_metakey = '_sirv_parsed_wai_images';
  protected static $is_muted = false;

  public static function migrate($operations_per_time = 10){
    //TODO: made possible to store all item attributes in the html and save to db

    $wae_data = self::get_wai_unsynced_data($operations_per_time);
    foreach ($wae_data as $wae_variation) {
      $variation_id = $wae_variation['post_id'];
      $attachment_ids = self::parse_attachment_ids($wae_variation['meta_value']);

      if( !empty($attachment_ids) ){
        $items = self::generate_items($variation_id, $attachment_ids);

        if( !self::$is_muted ){
          $result = self::store_data($variation_id, $items);
        }
      }

      if ( !self::$is_muted ) {
        self::mark_migrated_row($variation_id, $attachment_ids);
      }
    }

    return (array) self::get_wai_data_info();
  }


  protected static function get_wai_unsynced_data($limit = 10){
    global $wpdb;
    $table_postmeta = self::$table_name;
    $marked_variation = self::$db_marked_metakey;
    $db_wai_metakey = self::$db_wai_metakey;

    $query = "SELECT DISTINCT post_id, meta_value
      FROM $table_postmeta AS t
      WHERE meta_key = '$db_wai_metakey'
      AND meta_value != ''
      AND NOT EXISTS ( SELECT * FROM $table_postmeta WHERE meta_key = '$marked_variation' AND post_id = t.post_id ) LIMIT $limit";

    return $wpdb->get_results($query, ARRAY_A);
  }


  protected static function get_wai_unsynced_count(){
    global $wpdb;

    $table_postmeta = self::$table_name;
    $marked_variation = self::$db_marked_metakey;
    $db_wai_metakey = self::$db_wai_metakey;

    $query = "SELECT DISTINCT count(*)
      FROM $table_postmeta AS t
      WHERE meta_key = '$db_wai_metakey'
      AND meta_value != ''
      AND NOT EXISTS ( SELECT * FROM $table_postmeta WHERE meta_key = '$marked_variation' AND post_id = t.post_id )";

    return $wpdb->get_var($query);
  }


  protected static function get_wai_count(){
    global $wpdb;

    $table_postmeta = self::$table_name;
    $db_wai_metakey = self::$db_wai_metakey;

    $query = "SELECT count(*)
      FROM $table_postmeta
      WHERE meta_key = '$db_wai_metakey'
      AND meta_value != ''";

    return $wpdb->get_var($query);
  }


  public static function get_wai_data_info(){
    $all = (int) self::get_wai_count();
    $unsynced = (int) self::get_wai_unsynced_count();
    $synced = $all - $unsynced;
    $synced_percent = $synced ? round(($synced / $all) * 100) : 0;
    $error = '';

    if(self::$is_muted){
      $sirvAPIClient = sirv_getAPIClient();
      $error = $sirvAPIClient->getMuteError();
    }

    return (object) array(
      "all" => $all,
      "unsynced" => $unsynced,
      'synced' => $synced,
      'synced_percent' => $synced_percent,
      'synced_percent_text' => $synced_percent . '%',
      'error' => $error,
    );
  }


  protected static function store_data($variation_id, $wai_items){
    //TODO: optimization - get and save data by one request for 10 variations

    $items = array();
    $gallery_data = array("items" => $items, "id" => $variation_id);

    $stored_data_json = self::get_sirv_gallery_data($variation_id);

    if($stored_data_json){
      $stored_data = json_decode($stored_data_json, true);

      if( !empty($stored_data["items"]) ){
        $items = self::merge_items($stored_data["items"], $wai_items);
      }else{
        $items = $wai_items;
      }
    }else{
      $items = $wai_items;
    }

    $gallery_data["items"] = self::fix_order($items);

    $result = update_post_meta($variation_id, self::$db_sirv_gallery_metakey, json_encode($gallery_data));
    return $result;
  }


  protected static function merge_items($stored_items, $wai_items){
    $items = array();

    $items = array_merge($stored_items, $wai_items);

    return $items;
  }

  protected static function fix_order($items){
    foreach ($items as $key => $item) {
      if(is_object($item)){
        $item->order = $key;
      }else{
        $item["order"] = $key;
      }
    }

    return $items;
  }


  protected static function mark_migrated_row($variation_id, $attachment_ids){
    global $wpdb;

    $data = array(
      'post_id' => $variation_id,
      'meta_key' => self::$db_marked_metakey,
      'meta_value' => implode(",", $attachment_ids),
    );

    $wpdb->replace(self::$table_name, $data, array('%d', '%s', '%s'));
  }


  protected static function generate_items($variation_id, $attachment_ids){
    $items = array();

    if( empty($attachment_ids) ) return $items;

    foreach ($attachment_ids as $attachment_id) {
      $items[] = self::generate_item($variation_id, $attachment_id);
    }

    return $items;
  }


  protected static function generate_item($variation_id, $attachment_id, $order = 0){
    /* {"items":[{"url":"https://test.sirv.com/4.webp","type":"image","provider":"sirv","order":0,"viewId":"434","caption":""},{"url":"https://test.sirv.com/2332.png","type":"image","provider":"sirv","order":1,"viewId":"434","caption":""}],"id":434} */
    $url = sirv_get_cdn_image($attachment_id, true, true);
    $provider = "sirv";

    if( !$url ) {
      if( sirv_isMuted() ){
        self::$is_muted = true;
      }

      $url = wp_get_attachment_url($attachment_id);
      $provider = "woocommerce";
    }

    return (object) array(
      "url" => $url,
      "type" => "image",
      "provider" => $provider,
      "order" => $order,
      "viewId" => $variation_id,
      "caption" => "",
      "itemId" => "wai",
      "attachmentId" => $attachment_id
      );
  }


  protected static function parse_attachment_ids($attachment_ids_str){
    if( empty($attachment_ids_str) ) return array();

    return explode(",", $attachment_ids_str);
  }


  protected static function get_sirv_gallery_data($variation_id){
    return get_post_meta($variation_id, self::$db_sirv_gallery_metakey, true);
  }
}

?>
