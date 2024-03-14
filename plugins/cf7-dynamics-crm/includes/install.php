<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


if( !class_exists( 'vxcf_dynamics_install' ) ):

class vxcf_dynamics_install{
      public static $sending_req=false;
public function get_roles(){
      $roles=array(
      vxcf_dynamics::$id."_read_feeds",
      vxcf_dynamics::$id."_edit_feeds",
      vxcf_dynamics::$id."_read_logs" , 
      vxcf_dynamics::$id."_read_settings" , 
      vxcf_dynamics::$id."_edit_settings" , 
      vxcf_dynamics::$id."_send_to_crm" ,
      vxcf_dynamics::$id."_export_logs", 
      vxcf_dynamics::$id."_read_license", 
      vxcf_dynamics::$id."_uninstall"
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
    global $wpdb;

  //delete options
  delete_option(vxcf_dynamics::$type."_version"); 
  delete_option(vxcf_dynamics::$type."_updates");
  delete_option(vxcf_dynamics::$type."_settings");
     $other_version=$this->other_plugin_version(); 
    if(empty($other_version)){ //if other version not found
  delete_option(vxcf_dynamics::$id."_crm");
  delete_option(vxcf_dynamics::$id."_meta");
  $this->deactivate('uninstall'); 
    $data=vxcf_dynamics::get_data_object();
  $data->drop_tables();
  $this->remove_roles();
  }

  $this->deactivate_plugin();
}
public function deactivate_plugin(){
        $slug=vxcf_dynamics::get_slug();
          //deactivate 
  deactivate_plugins($slug); 
    update_option('recently_activated', array($slug => time()) + (array)get_option('recently_activated'));
}

}

endif;
