<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


if( !class_exists( 'vxc_sales_install' ) ):

class vxc_sales_install extends vxc_sales{

public function create_tables(){ 
    global $wpdb;
    require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
  //    $wpdb->show_errors();
  $table_name =  $this->get_table_name();
  
  if ( ! empty($wpdb->charset) )
  $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
  if ( ! empty($wpdb->collate) )
  $charset_collate .= " COLLATE $wpdb->collate";
  
  $sql= "CREATE TABLE $table_name (
  id int(11) unsigned not null auto_increment,
  order_id int(11) not null,
  feed_id int(11) not null,
  parent_id int(11) not null,
  crm_id varchar(250) not null,
  link varchar(250) not null,
  object varchar(250) not null,
  event varchar(200) not null,
  meta varchar(250) not null,
  data text,
  response text,
  extra text,
  `status` tinyint(1) not null default 1,
  `time` datetime null,
  PRIMARY KEY  (id),
  KEY entry_id (order_id)
  )$charset_collate;";
  
  
  

    
    $table_name =  $this->get_table_name('accounts');
    
      $sql.= "CREATE TABLE $table_name (
   id int(11) unsigned not null auto_increment,
   name varchar(250) not null,
   data longtext,
   meta longtext,
  `status` int(1) not null default 0,
  `time` datetime null,
  `updated` datetime null,
  PRIMARY KEY  (id)
  )$charset_collate;";
  
    dbDelta($sql);

}
public function get_roles(){
      $roles=array(
      "read_".$this->id,"edit_".$this->id,
      "edit_".$this->id."s",
      "publish_".$this->id."s",
      "edit_private_".$this->id."s",
      "edit_published_".$this->id."s",
      "edit_others_".$this->id."s",
      "delete_".$this->id."s",
      "delete_private_".$this->id."s", 
      "delete_others_".$this->id."s",
      "delete_published_".$this->id."s",
      $this->id."_read_logs",
      $this->id."_export_logs",
      $this->id."_read_settings" , 
      $this->id."_edit_settings" , 
      $this->id."_send_to_crm" , 
      $this->id."_read_license", 
      $this->id."_uninstall"
      );
      return $roles;
}
public function create_roles(){
      global $wp_roles;
      if ( ! class_exists( 'WP_Roles' ) ) {
            return;
        }
$roles=$this->get_roles();
foreach($roles as $role){
  $wp_roles->add_cap( 'administrator', $role );
}
$wp_roles->add_cap( 'administrator', 'vx_crmperks_view_addons' );
$wp_roles->add_cap( 'administrator', 'vx_crmperks_edit_addons' );
}

public function remove_roles(){
      global $wp_roles;
      if ( ! class_exists( 'WP_Roles' ) ) {
            return;
        }
$roles=$this->get_roles();
foreach($roles as $role){
  $wp_roles->remove_cap( 'administrator', $role );
}
}
public function remove_data(){
      delete_option($this->type."_version");
  delete_option($this->type."_updates");
  delete_option($this->type."_settings");
  $other_version=$this->other_plugin_version();

    global $wpdb;
   if(empty($other_version)){ //do not remove data if other version exists
    // Delete feeds
$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type ='{$this->id}';" );
  //drop logs table
  $wpdb->query("DROP TABLE IF EXISTS " . $this->get_table_name());
    //drop logs table
  $wpdb->query("DROP TABLE IF EXISTS " . $this->get_table_name('accounts'));
  //delete options 
  delete_option($this->id."_meta");
   } 

  if($other_version !=1){
  $this->deactivate("uninstall");
  }
return true;  
  }

public function deactivate_plugin(){
        $slug=$this->get_slug();
          //deactivate 
  deactivate_plugins($slug); 
    update_option('recently_activated', array($slug => time()) + (array)get_option('recently_activated'));
}
 
}

endif;
