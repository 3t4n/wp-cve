<?php 

/**
* @name ehb_check_table_existance
* @param $table_name string
**/
function ehb_check_table_existance($table_name)
{
  global $wpdb;
  foreach ($wpdb->get_col("SHOW TABLES",0) as $table )
  {
    if ($table == $table_name)
    {
      return true;
    }
  }
  return false;
}


// =================
// = EHU DATA BASE =
// =================
function ehu_old_bars_data() 
{
  global $wpdb;
  $old_plugin_prefix  =   'ehu_';
  $ehu_data       =   $wpdb->prefix . $old_plugin_prefix . "data";
  $sql            =  "SELECT * FROM $ehu_data";
  
  $result         =   $wpdb->get_results($sql, ARRAY_A);
  if ($result == null OR $result == "") return false;
  foreach ($result as $data) {
    $yolo = ehu_migrate_data($data);
  }
  
}

 
  //==== ADD BAR ====//
function ehu_migrate_data($data)
{
  global $user_ID;
  global $ehb_meta_prefix;
  global $wpdb;
  // Start with an underscore to hide fields from custom fields list
  $prefix = $ehb_meta_prefix;

  if(!is_array($data)) return false;
  //extract($data);
  $ehu_id         =   $data['ehu_id'];
  $title          =   $data['title'];
  $message        =   $data['message'];
  $start_date     =   $data['start_date'];
    $start_date   = ( $start_date=='' OR $start_date==null ) ? $start_date : date('m/d/Y', strtotime($start_date) ) ;
  $end_date       =   $data['end_date'];
    $end_date     = ( $end_date==''   OR $end_date==null   ) ? $end_date   : date('m/d/Y', strtotime($end_date) ) ;
  $show_where     =   $data['show_where'];
  $link_url       =   $data['link_url'];
  $link_text      =   $data['link_text'];
  $options        =   $data['options'];
  $active         =   $data['active'];
  $post_status    =  ($active==1) ? 'publish' : 'pending' ;

  $post_name      =   sanitize_title("{$title}-{$ehu_id}");
  $link = ($link_url !== '') ? "<a href='{$link_url}'>{$link_text}</a>" : $link_text ;
  $post_content   =   " ";
  $checkpost_sql  = "SELECT `post_title` FROM `{$wpdb->prefix}posts` WHERE `post_type` =  'heads_up_bar' AND  `post_name` = '{$post_name}'";
  $checkpost=$wpdb->get_row($checkpost_sql, 'ARRAY_A');
  if(null == $checkpost ) {
    // add the bar data to the custom post type
    $ehu_bar_args = array(
      'post_title'    => $title,
      'post_content'  => $post_content,
      'post_status'   => $post_status,
      'post_author'   => $user_ID,
      'post_type'     => 'heads_up_bar',
      'post_name'     => $post_name,
    );
    $add_bar = wp_insert_post($ehu_bar_args);
    if( $add_bar!==0 ) 
    {
      // add the meta data for the bar here
      $meta_data    =  json_decode( $options, true );
      $bg_color     =  $meta_data['bgColor'];
      $text_color   =  $meta_data['textColor'];
      $link_color   =  $meta_data['linkColor'];
      $bar_content  =  "{$message} {$link}";

      $meta_start_date         = update_post_meta( $add_bar, "{$prefix}start_date",           $start_date );
      $meta_end_date           = update_post_meta( $add_bar, "{$prefix}end_date",             $end_date );
      $meta_bar_content        = update_post_meta( $add_bar, "{$prefix}bar_content",          $bar_content );
      $meta_bar_bg_color       = update_post_meta( $add_bar, "{$prefix}bar_bg_color",         $bg_color );
      $meta_bar_border_color   = update_post_meta( $add_bar, "{$prefix}bar_border_color",     $text_color );
      $meta_text_color         = update_post_meta( $add_bar, "{$prefix}text_color",           $text_color );
      $meta_link_color         = update_post_meta( $add_bar, "{$prefix}link_color",           $link_color );
      $meta_show_where         = update_post_meta( $add_bar, "{$prefix}show_where",           $show_where );
      $meta_bar_content_width  = update_post_meta( $add_bar, "{$prefix}bar_content_width",    '100' );
      $meta_bar_location       = update_post_meta( $add_bar, "{$prefix}bar_location",         'top' );
      $meta_hide_bar           = update_post_meta( $add_bar, "{$prefix}hide_bar",             'yes' );

    }
  }
  // if nothing fails return true
  return true;
}

function ehu_drop_old_data_tables()
{
  global $wpdb;
  $old_plugin_prefix  =   'ehu_';
  $ehu_data     =   $wpdb->prefix . $old_plugin_prefix . "data";
  $ehu_stats    =   $wpdb->prefix . $old_plugin_prefix . "stats";
  $a            =  "DROP TABLE $ehu_data";
  $b            =  "DROP TABLE $ehu_stats";
  $wpdb->get_results($a);
  $wpdb->get_results($b);
}


add_action('wp_loaded','ehb_migrate');

function ehb_migrate()
{
 if( get_option( 'ehb_old_db') === 'no') return;
  // check for old data
  global $wpdb;
  $old_plugin_prefix  =   'ehu_';
  $ehu_data           =   $wpdb->prefix . $old_plugin_prefix . "data";
  $has_data = ehb_check_table_existance($ehu_data);
  if (!$has_data) {
    add_option( 'ehb_old_db', 'no' );
    return;
  }
  // migrate and drop old table
  $data = ehu_old_bars_data();
  ehu_drop_old_data_tables();
  add_option( 'ehb_old_db', 'no' );

}