<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


if( !class_exists( 'vxcf_googlesheets_pages' ) ) {

/**
* Main class
*
* @since       1.0.0
*/
class vxcf_googlesheets_pages   extends vxcf_googlesheets{
    public $ajax=false;
    
/**
* initialize plugin hooks
*  
*/
  public function __construct() {
  
  $this->data=$this->get_data_object();
global $pagenow; 
  if(in_array($pagenow, array("admin-ajax.php"))){
  add_action('wp_ajax_update_feed_'.$this->id, array($this, 'update_feed'));
  add_action('wp_ajax_update_feed_sort_'.$this->id, array($this, 'update_feed_sort'));
  add_action('wp_ajax_get_field_map_'.$this->id, array($this, 'get_field_map_ajax'));
  add_action('wp_ajax_get_field_map_object_'.$this->id, array($this, 'get_field_map_object_ajax'));
  add_action('wp_ajax_get_objects_'.$this->id, array($this, 'get_objects_ajax'));
  add_action('wp_ajax_log_detail_'.$this->id, array($this, 'log_detail'));
   add_action('wp_ajax_refresh_data_'.$this->id, array($this, 'refresh_data')); 
  add_action('wp_ajax_send_to_crm_'.$this->id, array($this, 'send_to_crm')); 
  }
  //crmperks forms
  add_action( 'cfx_add_meta_box', array($this,'add_meta_box_crmperks_form'),10,2 );
  add_action('cfx_form_entry_updated', array($this, 'update_entry_crm_perks_forms'),10,3);
  add_action('cfx_form_post_note_added', array($this, 'create_note_crm_perks_forms'),10,3);
  add_action('cfx_form_pre_note_deleted', array($this, 'delete_note_crm_perks_forms'),10,2);
  add_action('cfx_form_pre_trash_leads', array($this, 'trash_leads_crm_perks_forms'),10,2);
  add_action('cfx_form_pre_restore_leads', array($this, 'restore_leads_crm_perks_forms'),10,2); 
  if($this->is_crm_page()){
  $base_url=$this->get_base_url();
  wp_register_script( 'vxc-tooltip',$base_url. 'js/jquery.tipTip.js', array( 'jquery' ), $this->version, true );
  wp_register_style('vxc-tooltip', $base_url. 'css/tooltip.css');
  wp_register_style('vx-fonts', $base_url. 'css/font-awesome.min.css');
  wp_register_style('vx-datepicker', $base_url. 'css/jquery-ui.min.css');
  wp_register_script( 'vxg-select2',$base_url. 'js/select2.min.js', array( 'jquery' ), $this->version, true );
  wp_register_style('vxg-select2', $base_url. 'css/select2.min.css',array(),array('ver'=>'1.0'));
  wp_register_script( 'vx-sorter',$base_url. 'js/jquery.tablesorter.min.js', array( 'jquery' ), $this->version, true );
  }

  //creates the subnav left menu
 add_filter("admin_menu", array($this, 'create_menu'), 55);
 add_filter( 'vx_cf_meta_boxes_right', array($this,'add_meta_box'),10,3 );
 add_action( 'admin_notices', array( $this, 'admin_notices' ) );  
   add_filter('plugin_action_links', array($this, 'plugin_action_links'), 10, 2); 
    add_action('vxcf_entry_submit_btn', array($this, 'entry_checkbox'));  

  add_action('vx_cf7_post_note_added', array($this, 'create_note_e'),10,3);
  add_action('vx_cf7_pre_note_deleted', array($this, 'delete_note_e'),10,2);
  add_action('vx_cf7_pre_trash_leads', array($this, 'trash_leads_e'));
  add_action('vx_cf7_pre_restore_leads', array($this, 'restore_leads_e'));
  add_action('vx_cf7_entry_updated', array($this, 'update_entry_e'),10,3);
//
  add_action('vx_contact_post_note_added', array($this, 'create_note_c'),10,3);
  add_action('vx_contact_pre_note_deleted', array($this, 'delete_note_c'),10,2);
  add_action('vx_contact_pre_trash_leads', array($this, 'trash_leads_c'));
  add_action('vx_contact_pre_restore_leads', array($this, 'restore_leads_c'));
  add_action('vx_contact_entry_updated', array($this, 'update_entry_c'),10,3);
 
 add_filter('vx_callcenter_entries_action',array($this,'bulk_send_crm_callcenter'),10,4);
 add_filter('vx_callcenter_bulk_actions',array($this,'add_bulk_send_crm_callcenter'));
 $this->setup_plugin();
   
  }


 
public function update_entry_crm_perks_forms($entry_id,$lead,$form){ 
    $option=get_option($this->type.'_settings',array());

if(   !empty($option['update'])  ){ 
    $event= 'update';
  $lead['__vx_id']=$entry_id; 
  $form['id']='vf_'.$form['id'];
  $form['cfx_type']='vf'; 
  $push=$this->push($lead,$form,$event);
        if(!empty($push['msg'])){
  $this->screen_msg($push['msg'],$push['class']);  
  }
  }
}
public function create_note_crm_perks_forms($id, $entry, $note){
$option=get_option($this->type.'_settings',array());
if(!empty($option['notes']) ){
  if(!empty($entry['form_id'])){
  $form=array('id'=>'vf_'.$entry['form_id']);
  $entry['__vx_id']=$entry['id']; 
  $title=substr($note,0,100);
self::$note=array('id'=>$id,'body'=>$note,'title'=>$title);
$push=$this->push($entry,$form,'add_note');

  }
}
}

public function delete_note_crm_perks_forms($note_id,$entry){
$option=get_option($this->type.'_settings',array());      
if(!empty($option['notes'])){
if(!empty($entry['form_id'])){
$form=array('id'=>'vf_'.$entry['form_id']);
self::$note=array('id'=>$note_id);
$entry['__vx_id']=$entry['id']; 
$push=$this->push($entry,$form,'delete_note');
} 
      }
}
public function trash_leads_crm_perks_forms($leads,$form_id){
$option=get_option($this->type.'_settings',array());

      if(!empty($option['delete'])){
       if(is_array($leads)){   
       $updated=$error='';
        foreach($leads as $lead){
        if(!empty($form_id)){
$entry=array('__vx_id'=>$lead);
$push=$this->push($entry,array('id'=>$form_id),'delete');
              if(!empty($push['msg'])){
                  if($push['class'] == 'updated'){
                   $updated.=$push['msg'].'<br>';   
                  }else{
                    $error.=$push['msg'].'<br>';    
                  }
  }
        } 
        }
     if(!empty($updated)){
        $this->add_msg($updated,'updated');  
     }   
     
     if(!empty($error)){
        $this->add_msg($error,'error');  
     }
       }
      }

}

public function restore_leads_crm_perks_forms($leads,$form_id){
     $option=get_option($this->type.'_settings',array());

      if(!empty($option['restore'])){
           
       if(is_array($leads)){   
       $updated=$error=''; 
        foreach($leads as $lead){
   if( !empty($lead['id'])){
$lead['__vx_id']=$lead['id']; 
$push=$this->push($lead,array('id'=>$form_id),'restore');

              if(!empty($push['msg'])){
                  if($push['class'] == 'updated'){
                   $updated.=$push['msg'].'<br>';   
                  }else{
                    $error.=$push['msg'].'<br>';    
                  }
  }
        } 
        }
     if(!empty($updated)){
        $this->add_msg($updated,'updated');  
     }   
     
     if(!empty($error)){
        $this->add_msg($error,'error');  
     }
       }
      }
    //  var_dump($leads); die();  
}

 
public function add_bulk_send_crm_callcenter($list){ 
 $list['send_googlesheets']=__('Send to Google Sheets','integration-for-contact-form-7-and-spreadsheets');
  return $list;  
}
public function bulk_send_crm_callcenter($msg,$action,$ids,$type){ 
    if($action == 'send_googlesheets'){
        $entry=array(); $notice=''; $class='updated';

        if(class_exists('vx_dialpad')){
            $pad=new vx_dialpad();
            $form=$pad->get_form($type);
        if(is_array($ids)){
            foreach($ids as $id){
               $entry=$pad->get_entry($type,$id);       
         if(!empty($entry['id'])){
$entry['__vx_id']=$entry['id'];
          $temp=$this->push($entry,$form,'',false);

          if(!empty($temp['msg'])){
         if(!empty($notice)){
          $notice.='<br/>';   
         }
          $notice.=$temp['msg'];
        if($temp['class'] !='updated'){
         $class=$temp['class'];   
        }
          }    
         }   }
        }
        }
        if(empty($notice)){
            $notice='Nothing Sent to Google Sheets';
        }
    $msg=array('msg'=>$notice,'class'=>$class);   
    }

return $msg;
}
public function update_entry_e($entry,$entry_id,$lead){ 
 $this->update_entry($entry,$entry_id,$lead);   
}
public function update_entry_c($entry,$entry_id,$lead){ 
  $this->update_entry($entry,$entry_id,$lead,'addon');     
}
    /**
    * Send entry to crm on update
    * 
    * @param mixed $form
    * @param mixed $lead_id
    */
public function update_entry($entry,$entry_id,$lead,$type=''){ 
  $option=get_option($this->type.'_settings',array());

  //checkbox checked || auto send to crm on update
if( !empty($_POST[$this->id.'_send']) || (  !empty($option['update']) ) ){ 
    // only update , if already sent to crm
    //will in case of auto update option or send to crm checkbox
    $event= 'update';
  $entry['__vx_id']=$entry_id; 
  $entry['__vx_type']=$type; 

  $form=$this->get_form($lead['form_id']);
    $push=$this->push($entry,$form,$event);
        if(!empty($push['msg'])){
  $this->screen_msg($push['msg'],$push['class']);  
  }
  }

}
public function trash_leads_e($leads){
 $this->trash_leads($leads);   
}
public function trash_leads_c($leads){
 $this->trash_leads($leads,'addon');   
}
/**
* Delete entry from crm on deletion
* 
* @param mixed $lead_id
*/
public function trash_leads($leads,$type=''){
     $option=get_option($this->type.'_settings',array());

      if(!empty($option['delete'])){
       if(is_array($leads)){   
       $updated=$error='';
        foreach($leads as $lead){
           $entry=$this->get_cf_entry($lead,$type);
        if(!empty($entry['form_id'])){
$form=$this->get_form($entry['form_id']);
$entry['__vx_id']=$lead;
$push=$this->push($entry,$form,'delete');
              if(!empty($push['msg'])){
                  if($push['class'] == 'updated'){
                   $updated.=$push['msg'].'<br>';   
                  }else{
                    $error.=$push['msg'].'<br>';    
                  }
  }
        } 
        }
     if(!empty($updated)){
        $this->add_msg($updated,'updated');  
     }   
     
     if(!empty($error)){
        $this->add_msg($error,'error');  
     }
       }
      }
     //  var_dump($leads); die();  
  }
  
public function restore_leads_e($leads){
    $this->restore_leads($leads);
}  
public function restore_leads_c($leads){
    $this->restore_leads($leads,'addon');
} 
  /**
* Send entry to crm on restore
* 
* @param mixed $lead_id
*/
public function restore_leads($leads,$type=''){
     $option=get_option($this->type.'_settings',array());

      if(!empty($option['restore'])){
           
       if(is_array($leads)){   
       $updated=$error='';
        foreach($leads as $lead){
           $entry=$this->get_cf_entry($lead,$type);
          
        if(!empty($entry['form_id'])){
$form=$this->get_form($entry['form_id']);
if($type == 'addon'){
  $detail=$entry;  
}else{
 $detail=$this->get_cf_entry_detail($lead);
}
 $detail['__vx_id']=$lead;
$push=$this->push($detail,$form,'restore');
              if(!empty($push['msg'])){
                  if($push['class'] == 'updated'){
                   $updated.=$push['msg'].'<br>';   
                  }else{
                    $error.=$push['msg'].'<br>';    
                  }
  }
        } 
        }
     if(!empty($updated)){
        $this->add_msg($updated,'updated');  
     }   
     
     if(!empty($error)){
        $this->add_msg($error,'error');  
     }
       }
      }
    //  var_dump($leads); die();  
  }
public function delete_note_e($note_id,$lead_id){
$this->delete_note($note_id,$lead_id);   
}
public function delete_note_c($note_id,$lead_id){
$this->delete_note($note_id,$lead_id,'addon');   
}  
  /**
  * delete note from crm when deleted from GF entry
  * 
  * @param mixed $note_id
  * @param mixed $lead_id
  */
public function delete_note($note_id,$lead_id,$type=''){
$option=get_option($this->type.'_settings',array());
       
      if(!empty($option['notes'])){
          
$entry=$this->get_cf_entry($lead_id,$type);
if(!empty($entry['form_id'])){
$form=$this->get_form($entry['form_id']);
    self::$note=array('id'=>$note_id);
    $entry['__vx_id']=$entry['id']; 
$push=$this->push($entry,$form,'delete_note');

} 
      }
}
public function create_note_e($id, $lead_id, $note){
$this->create_note($id, $lead_id, $note);
}
public function create_note_c($id, $lead_id, $note){

$this->create_note($id, $lead_id, $note,'addon');
}
    /**
* send entry note to crm
*   
* @param mixed $id
* @param mixed $lead_id
* @param mixed $user_id
* @param mixed $user_name
* @param mixed $note
* @param mixed $note_type
*/
public function create_note($id, $lead_id, $note,$type=''){

        $option=get_option($this->type.'_settings',array());

      if(!empty($option['notes'])){
  $entry=$this->get_cf_entry($lead_id,$type);
  if(!empty($entry['form_id'])){
  $form=$this->get_form($entry['form_id']);
  if($type == 'addon'){
   $entry_detail=$entry;   
  }else{
  $entry_detail=$this->get_cf_entry_detail($lead_id);
  }
  $entry_detail['__vx_id']=$lead_id; 

  $title=substr($note,0,100);
self::$note=array('id'=>$id,'body'=>$note,'title'=>$title);

$push=$this->push($entry_detail,$form,'add_note');

  }
}

  }
  public function entry_checkbox($lead){
  ?>
  <div class="vx_row">
  <label><input type="checkbox" name="<?php echo esc_attr($this->id) ?>_send" value="yes"> <?php esc_html_e('Send to Google Sheets','integration-for-contact-form-7-and-spreadsheets') ?></label>
  </div>
  <?php  
}
    /**
  * Display custom notices
  * show googlesheets response
  * 
  */
  public function admin_notices(){

  $debug = !empty(self::$debug_html) && current_user_can($this->id.'_edit_settings');
  if($debug){ 
  echo "<div class='error'><p>".wp_kese_post(self::$debug_html)."</p></div>"; 
  self::$debug_html='';
  }
  if(!empty($_POST[$this->id.'_send_btn']) && !empty($_REQUEST['id'])){
$tab=$this->post('tab');
$id=$this->post('id');
$form=array('title'=>'Contact Form');
$lead=array();
if($tab == 'contacts'){
  global $vxcf_crm;
  if(method_exists($vxcf_crm,'get_entry')){
    $lead=$vxcf_crm->get_entry($id);
$form['id']='vx_contacts';
  }  
}else{
    $info=$this->get_cf_entry($id);
    $lead=$this->get_cf_entry_detail($id);
    $lead['__vx_entry']=$info;
 if(!empty($info['form_id'])){
  $form['id']=$info['form_id'];   
 }   
}
$lead['__vx_id']=$id;
    $push=$this->push($lead,$form);
    if(!empty($push['msg'])){
  $this->screen_msg($push['msg'],$push['class']);  
  }
  }
  //send to crm in order page message
  $msgs=get_option($this->id.'_msgs');

  if(is_array($msgs)){
    foreach($msgs as $msg){
     $this->screen_msg($msg['msg'],$msg['class']);    
    }  
  update_option($this->id.'_msgs','');
  }
  }
    /**
  * Add settings and support link
  * 
  * @param mixed $links
  * @param mixed $file
  */
  public function plugin_action_links( $links, $file ) {
   $slug=$this->get_slug();
      if ( $file == $slug ) {
          $settings_link=$this->link_to_settings();
            array_unshift( $links, '<a href="' .$settings_link. '">' . esc_html__('Settings', 'integration-for-contact-form-7-and-spreadsheets') . '</a>' );
        }
        return $links;
   }
  /**
  * Creates left nav menu under Forms
  * 
  * @param mixed $menus
  */
  public  function create_menu(){
  // Adding submenu if user has access
        $page_title =__('Google Sheets for Contact Form','integration-for-contact-form-7-and-spreadsheets');
        $menu_title =__('Google Sheets','integration-for-contact-form-7-and-spreadsheets');
        $capability = $this->id."_read_feeds"; 
        $menu_id='vxcf_leads';  
if(empty($GLOBALS['admin_page_hooks'][$menu_id])){
add_menu_page($page_title,$menu_title,$capability,$this->id,array( $this,'mapping_page'));
}else{
add_submenu_page('vxcf_leads',$page_title,$menu_title,$capability,$this->id,array( $this,'mapping_page'));
}
  } 
  /**
  * plugin admin features
  * 
  */
  public function setup_plugin(){
        global $wpdb;
if(isset($_REQUEST[$this->id.'_tab_action']) && $_REQUEST[$this->id.'_tab_action']=="get_code" && current_user_can($this->id."_edit_settings")){
$state=urldecode($this->post('state'));
//$state=str_replace("-__-","&",$state);

if(isset($_REQUEST['code'])){
$state.='&code='.$this->post('code');   
}
if(isset($_REQUEST['error'])){
$state.='&error='.$this->post('error');   
}
//$link.'&'.$this->id."_tab_action=get_token&vx_action=redirect&id=".$id."&vx_nonce=".$nonce
wp_safe_redirect($state);
die();
}
     
      if(isset($_REQUEST[$this->id.'_tab_action']) && $_REQUEST[$this->id.'_tab_action']=="del_account"){
 check_admin_referer('vx_nonce','vx_nonce');
 if( current_user_can($this->id."_edit_settings")){ 
$id=$this->post('id');
$data=$this->get_data_object();
$res=$data->del_account($id);
$class='';
 if($res){
       $msg=__('Account Deleted Successfully','integration-for-contact-form-7-and-spreadsheets');
  $class='updated';   
 }else{
       $msg=__('Error While Removing Account','integration-for-contact-form-7-and-spreadsheets');
  $class='error';      
 }
  $this->add_msg($msg,$class);
 }
  $redir=$this->link_to_settings('accounts');
wp_redirect($redir.'&'.$this->id.'_msg=1');
die();
  }
  
  if(isset($_REQUEST[$this->id.'_tab_action']) && $_REQUEST[$this->id.'_tab_action']=="get_token"){
  check_admin_referer('vx_nonce','vx_nonce');
  if(!current_user_can($this->id."_edit_settings")){ 
  $msg=__('You do not have permissions to add token','integration-for-contact-form-7-and-spreadsheets');
  $this->display_msg('admin',$msg);
  return;   
  }
 
   $id=$this->post('id');
  $info=$this->get_info($id);
  $api=$this->get_api($info);
  $info_data=$api->handle_code();
//var_dump($info_data); die();
  $redir=$this->link_to_settings('accounts');
wp_redirect($redir.'&id='.$id);
die(); 
 
  }
  
  if($this->post('vx_tab_action_'.$this->id)=="export_log"){
  check_admin_referer('vx_nonce','vx_nonce');
  if(!current_user_can($this->id."_export_logs")){ 
  $msg=__('You do not have permissions to export logs','integration-for-contact-form-7-and-spreadsheets');
  $this->display_msg('admin',$msg);
  return;   
  }
  header('Content-disposition: attachment; filename='.date("Y-m-d",current_time('timestamp')).'.csv');
  header('Content-Type: application/excel');
  $data=$this->get_data_object();
  $sql_end=$data->get_log_query();
  $forms=array();
  $sql="select * $sql_end limit 3000";
  $results = $wpdb->get_results($sql , ARRAY_A );
  $fields=array(); $field_titles=array("#",__('Status','integration-for-contact-form-7-and-spreadsheets'),__('Google Sheets ID','integration-for-contact-form-7-and-spreadsheets') ,__('Entry ID','integration-for-contact-form-7-and-spreadsheets'),__('Description','integration-for-contact-form-7-and-spreadsheets'),__('Time','integration-for-contact-form-7-and-spreadsheets'));
  $fp = fopen('php://output', 'w');
  fputcsv($fp, $field_titles);
  $sno=0;
  foreach($results as $row){
  $sno++;
  $row=$this->verify_log($row);
  fputcsv($fp, array($sno,$row['title'],$row['_crm_id'],$row['entry_id'],$row['desc'],$row['time']));    
  }
  fclose($fp);
  die();
  }
  
  if($this->post('vx_tab_action_'.$this->id)=="clear_logs" ){
  check_admin_referer('vx_nonce','vx_nonce');
  if(!current_user_can($this->id."_edit_settings")){ 
  $msg=__('You do not have permissions to clear logs','integration-for-contact-form-7-and-spreadsheets');
  $this->display_msg('admin',$msg);
  return;   
  }
  $data=$this->get_data_object();
  $clear=$data->clear_logs();

  
       $msg=__('Error While Clearing Google Sheets Logs','integration-for-contact-form-7-and-spreadsheets');
      $level="error";
      if(!empty($clear)){
      $msg=__('Google Sheets Logs Cleared Successfully','integration-for-contact-form-7-and-spreadsheets');   
      $level="updated";
      }
      $this->add_msg($msg,$level);
      $link=$this->link_to_settings('logs').$this->id.'_msg=1';
  wp_redirect($link);
  die();
  }  
  //
  self::$tooltips = array(
 'vx_feed_name' =>  esc_html__('Enter feed name of your choice.', 'integration-for-contact-form-7-and-spreadsheets'),
  'vx_sel_object' => esc_html__('Select the Object to Create when a Form is Submitted.', 'integration-for-contact-form-7-and-spreadsheets'),
   'vx_sel_account' =>__('Select the Google Sheets account you would like to export entries to.', 'integration-for-contact-form-7-and-spreadsheets'),
  'vx_sel_form' => esc_html__('Select the Contact Form you would like to integrate with Google Sheets. Contacts generated by this form will be automatically added to your Google Sheets account.', 'integration-for-contact-form-7-and-spreadsheets'),
  
  'vx_map_fields' => esc_html__('Associate your Google Sheets fields to the appropriate Contact Form fields.', 'integration-for-contact-form-7-and-spreadsheets'),
  
  'vx_optin_condition' =>__('When the opt-in condition is enabled, form submissions will only be exported to Google Sheets when the condition is met. When disabled all form submissions will be exported.', 'integration-for-contact-form-7-and-spreadsheets'),
  
  'vx_manual_export' => esc_html__('If you do not want all entries sent to Google Sheets, but only specific, approved entries, check this box. To manually send an entry to Google Sheets, go to Entries, choose the entry you would like to send to Google Sheets, and then click the "Send to Google Sheets" button.', 'integration-for-contact-form-7-and-spreadsheets'),
  
    'vx_entry_notes' => esc_html__('Enable this option if you want to synchronize Contact Form entry notes to Google Sheets Object notes. For example , when you add a note to a Contact Form entry, it will be added to the Google Sheets Object selected in the feed.', 'integration-for-contact-form-7-and-spreadsheets'),
    
      'vx_primary_key' => esc_html__('Which field should be used to update existing objects?', 'integration-for-contact-form-7-and-spreadsheets'),
      
  'vx_web' => sprintf(__('Web-to-Lead is available for all Google Sheets Editions. If you are not sure if your Google Sheets Edition supports the API, you should use Web-to-Lead. Editions that do not support the Google Sheets API: %s 1: Personal Edition %s 2: Group Edition %s 3: Professional Edition %s Note: You can purchase API access for a Professional Edition', 'integration-for-contact-form-7-and-spreadsheets'),'<br/>','<br/>','<br/>','<br/>'),
  'vx_api' => sprintf(__('The API features are more powerful than Web-to-Lead. You can create different object types, as well as other advanced features. If you have any of the following Google Sheets Editions, you can use the included API Add-on: %s 1: Enterprise Edition %s 2: Unlimited Edition %s 3: Developer Edition %s 4: Professional Edition - Requires API Upgrade', 'integration-for-contact-form-7-and-spreadsheets'),'<br/>','<br/>','<br/>','<br/>','<br/>'),
  
  'vx_custom_app'=>__('This option is for advanced users who want to override default Google Sheets App.','integration-for-contact-form-7-and-spreadsheets'),
  
  'vx_disable_logs'=>__('When an order is sent to Google Sheets we store that order information in the database and show it in the Google Sheets Log. Check this box if you do not want to save the exported order information in the logs.','integration-for-contact-form-7-and-spreadsheets'),
  
  'vx_line_items'=>__('Create a Google Sheets Order product for each Woocommrce Cart item.','integration-for-contact-form-7-and-spreadsheets'),
  
  'vx_price_books'=>__('Get PriceBooks list from googlesheets.','integration-for-contact-form-7-and-spreadsheets'),
  'vx_camps'=>__('Get Campaigns and Status list from googlesheets.','integration-for-contact-form-7-and-spreadsheets'),
  
  'vx_sel_price_book'=>__('Which Pricebook should be searched for product','integration-for-contact-form-7-and-spreadsheets'),
  'vx_sel_camp'=>__('Which Campaign should be assigned to this object.','integration-for-contact-form-7-and-spreadsheets'),
  'vx_sel_status'=>__('What should be Member Status.','integration-for-contact-form-7-and-spreadsheets'),
  
  'vx_pro_desc'=>__('A new product will be created in selected Pricebook. You can add a description for new products created by this plugin.','integration-for-contact-form-7-and-spreadsheets'),
  
   'vx_assign_account'=>__('Enable this option if you want to assign an account this object.','integration-for-contact-form-7-and-spreadsheets'),
   'vx_sel_account'=>__('Object created by this feed will be assigned to the selected Account.','integration-for-contact-form-7-and-spreadsheets'),
   
      'vx_assign_contract'=>__('Enable this option , if you want to assign a Contract to this object','integration-for-contact-form-7-and-spreadsheets'),
   'vx_sel_contract'=>__('Select Contract feed. Contract created by this feed will be assigned to this object','integration-for-contact-form-7-and-spreadsheets'),
   
   'vx_camp_check'=>__('If enabled, Lead/Contact will be added to selected Campaign','integration-for-contact-form-7-and-spreadsheets'),
   'vx_owner_check'=>__('Enable this option if you want to assign another object owner.','integration-for-contact-form-7-and-spreadsheets'),
   'vx_owners'=>__('Get Users list from Google Sheets','integration-for-contact-form-7-and-spreadsheets'),
   'vx_order_notes'=>__('Enable this option if you want to synchronize WooCommerce Order notes to Google Sheets Object notes. For example, when you add a note to a WooCommerce Order, it will be added to the Google Sheets Object selected in the feed.','integration-for-contact-form-7-and-spreadsheets'),
   'vx_sel_owner'=>__('Select a user as a owner of this object','integration-for-contact-form-7-and-spreadsheets'),
   
         'vx_entry_note'=>__('Check this option if you want to send more data as CRM entry note.', 'integration-for-contact-form-7-and-spreadsheets'),
   'vx_note_fields'=>__('Select fields which you want to send as a note', 'integration-for-contact-form-7-and-spreadsheets'),
   'vx_disable_note'=>__('Enable this option if you want to add note only for new CRM entry', 'integration-for-contact-form-7-and-spreadsheets'),
   
       'vx_entry_note'=>__('Check this option if you want to send more data as CRM entry note.', 'integration-for-contact-form-7-and-spreadsheets'),
   'vx_note_fields'=>__('Select fields which you want to send as a note', 'integration-for-contact-form-7-and-spreadsheets'),
   'vx_disable_note'=>__('Enable this option if you want to add note only for new CRM entry', 'integration-for-contact-form-7-and-spreadsheets')
   
   
  );
  
  }
public function add_meta_box_crmperks_form($lead,$form){
 $lead_id=isset($lead['id']) ? $lead['id'] : ""; 
$form_id=isset($lead['form_id']) ? 'vf_'.$lead['form_id'] : ""; 

if(! $this->has_feed($form_id)) { return ''; }
$data=$this->get_data_object();
$log_entry=$data->get_log_by_lead($lead['id'],$form_id);
$log_url=$this->link_to_settings('logs').'&entry_id='.$lead['id'];
?>
<div class="vx_div" style="margin-top: 20px;">
<div class="table_head_i"><?php esc_html_e('Capsule', 'integration-for-contact-form-7-and-spreadsheets'); ?></div>  
<div class="vx_group">
<div class="vx_send_crm_msg">
<?php
$comments=false;
if( !empty($log_entry) ){
    $comments=true;
$log=$this->verify_log($log_entry);
echo wp_kses_post($this->format_log_msg($log));
}
?></div>
<p style="margin-top: 12px;">
<button class="button vx_send_crm_btn" data-crm="<?php echo esc_attr($this->crm_name); ?>" type="button" data-action="send_to_crm_<?php echo esc_attr($this->id) ?>" value="yes">
<span class="reg_ok"><i class="fa fa-send"></i> Send to <?php echo esc_attr($this->crm_name); ?></span> 
<span class="reg_proc" style="display: none;"><i class="fa fa-circle-o-notch fa-spin"></i> Sending ...</span>
</button>
  <?php
      if($comments ){
  ?>
  <a href="<?php echo esc_url($log_url) ?>" target="_blank" class="button"><i class="fa fa-external-link"></i> Go to Logs</a>
  <?php
      }
  ?>
  </p>
</div>
</div>
<?php
   
} 
public function send_to_crm(){

check_ajax_referer('vx_nonce','vx_nonce');  
if(current_user_can($this->id."_send_to_crm")){   
$id=(int)$this->post('id'); 
 $log=array('meta'=>'Unknow Error');
if(class_exists('cfx_form')){
$entry=cfx_form::get_entry($id);  
if(!empty($entry)){
$form=cfx_form::get_form($entry['form_id']);
if(!empty($form['fields'])){    
$detail=cfx_form::get_entries_detail($id,$form['fields']); 
if(!empty($detail[0])){
$detail=$detail[0];
$lead=array();
foreach($detail as $k=>$v){
    $field_id=substr($k,0,strpos($k,'_'));
    if(is_numeric($field_id)){
$lead[$field_id]=$v;    
    }
}
$lead['__vx_id']=$entry['id']; 
$form['id']='vf_'.$form['id']; 
$form['cfx_type']='vf';
$push=$this->push($lead,$form);
$data=$this->get_data_object();
$log_entry=$data->get_log_by_lead($entry['id'],$form['id']);
$log=$this->verify_log($log_entry);
} } } 
echo $this->format_log_msg($log);
} }else{
 $msg=__('You do not have permissions for this action','integration-for-contact-form-7-and-spreadsheets');
$this->screen_msg($msg,'error');    
}
die();
}    
public function format_log_msg($log){
    $msg=!empty($log['meta']) ? $log['meta'] : $log['desc'];
if(!empty($log['status']) && !empty($log['a_link']) && !empty($log['crm_id'])){
    $msg.=' '.$log['a_link'];
}
$st=empty($log['status']) ? '0' : $log['status'];
//$this->screen_msg($msg,$class);
$icons=array('0'=>array('color'=>'#DC513B','icon'=>'fa-warning'),'4'=>array('color'=>'#3897C3','icon'=>'fa-filter'),
'2'=>array('color'=>'#d5962c','icon'=>'fa-edit'),'5'=>array('color'=>'#DC513B','icon'=>'fa-times'));

$bg='#83B131'; $icon='fa-check';
if(isset($icons[$st])){
  $bg=$icons[$st]['color'];  
  $icon=$icons[$st]['icon'];  
}
return '<div style="background-color: '.$bg.';" class="vx_msg_div"><i class="fa '.$icon.'"></i> '.$msg.'</div>';
} 
public function add_meta_box($boxes,$lead,$detail){
$form_id=isset($lead['form_id']) ? $lead['form_id'] : ""; 
if( $this->has_feed($form_id)) { 
 $boxes['googlesheets_crm']=array('title'=>'<i class="fa fa-plug"></i> '.__('Google Sheets', 'integration-for-contact-form-7-and-spreadsheets'),'callback'=>array($this,'meta_box_html'));  
} return $boxes; 
}
public function meta_box_html($lead,$detail){
     $lead_id=isset($lead['id']) ? $lead['id'] : ""; 
    $form_id=isset($lead['form_id']) ? $lead['form_id'] : "";
     $data=$this->get_data_object();
$log_entry=$data->get_log_by_lead($lead['id'],$form_id);

$log_url=$this->link_to_settings('logs').'&entry_id='.$lead['id'];
include_once(self::$path."templates/crm-entry-box.php");
 
} 
  /**
  * Whether to show the Entry "Send to CRM" button or not
  *
  * If the entry's form has been mapped to CRM feed, show the Send to CRM button. Otherwise, don't.
  *
  * @return boolean True: Show the button; False: don't show the button.
  */
  public  function show_send_to_crm_button() {
  
  $form_id = rgget('id');
  
  return $this->has_feed($form_id);
  }
public function has_feed($form_id) {
  $data=$this->get_data_object();
  $feeds = $data->get_feed_by_form( $form_id , true);
  
  return !empty($feeds);
  }
    /**
  * refresh data , ajax method
  * 
  */
  public function refresh_data(){
      check_ajax_referer("vx_crm_ajax","vx_crm_ajax"); 
  if(!current_user_can($this->id."_read_settings")){ 
   die();  
 }   
  $res=array();
  $action=$this->post('vx_action');
  $camp_id_sel=$this->post('camp_id');

  $account=$this->post('account');
  $status_sel=$this->post('status');
  $owner_sel=$this->post('owner');

 $info=array(); $meta=array();
  if(!empty($account)){
 $info=$this->get_info($account);
 if(!empty($info['meta']) ){
   $meta=$info['meta'];  
 }
  }

    $api=$this->get_api($info);
  switch($action){
      case"refresh_campaigns":
    $camps=$api->get_campaigns(); 
    $status_list=$api->get_member_status(); //var_dump($status_list); die();

    $data=array();
    if(is_array($status_list)){
    $res['status']="ok";
    $data['crm_sel_camp']=$this->gen_select($camps,$status_sel,__('Select Campaign','integration-for-contact-form-7-and-spreadsheets'));
    $meta['member_status']=$status_list;   
    }else{
     $res['error']=$status_list;   
    }
    if(is_array($camps)){
    $res['status']="ok";
    $data['crm_sel_status']=$this->gen_select($status_list,$camp_id_sel,__('Select Status','integration-for-contact-form-7-and-spreadsheets'));
    $meta['campaigns']=$camps;   
    }else{
     $res['error']=$camps;   
    }

  $res['data']=$data;   
      break;   
  case"refresh_users":
    $users=$api->get_users(); 
    
    $data=array();
    if(is_array($users)){
    $res['status']="ok";
    $data['crm_sel_user']=$this->gen_select($users,$owner_sel,__('Select User','integration-for-contact-form-7-and-spreadsheets'));
    $meta['users']=$users;   
    }else{
     $res['error']=$users;   
    }

  $res['data']=$data;   
      break;

  }
     
  if(isset($info['id'])){
    $this->update_info( array("meta"=>$meta) , $info['id'] );
}
if(isset($res['error'])){
    $res['status']='error';
    if(empty($res['error'])){
    $res['error']=__('Unknown Error','integration-for-contact-form-7-and-spreadsheets');
    }
}
  die(json_encode($res));    
  }
  /**
  * CRM menu page
  * 
  */
  public  function mapping_page(){
       wp_enqueue_style('vx-fonts');
      wp_enqueue_script('vxc-tooltip');
      wp_enqueue_style('vxc-tooltip');
         $tabs=array('feeds'=>__('Google Sheets Feeds','integration-for-contact-form-7-and-spreadsheets') , 'logs'=>__('Google Sheets Log','integration-for-contact-form-7-and-spreadsheets') , 'accounts'=>__('Google Sheets Accounts','integration-for-contact-form-7-and-spreadsheets'), 'settings'=>__('Settings','integration-for-contact-form-7-and-spreadsheets'));
         
         $tabs=apply_filters('vx_plugin_tabs_'.$this->id,$tabs);
      $view = isset($_GET["tab"]) ? $this->post("tab") : 'feeds';
      $tab=$view;
      if(!isset($tabs[$view])){
       $tab='feeds';   
      }
          ?>
                <style type="text/css">
        .vx_img_head{
            line-height: 44px;
            margin-bottom: 12px;
        }
        .vx_img_head img{
        height: 44px;
        margin-right: 10px;
        vertical-align: middle;
        }    
            </style>
    <div class="wrap">      
    <h2 class="nav-tab-wrapper">
    <?php
    $link=$this->link_to_settings();
        foreach($tabs as  $k=>$v){
            $v=is_array($v) && isset($v['label']) ? $v['label'] : $v; 
    ?>
        <a href="<?php echo esc_url($link.'&tab='.$k); ?>" class="nav-tab <?php if($k == $tab){echo 'nav-tab-active';} ?>"><?php echo esc_html($v); ?></a>
            
    <?php
        }
        ?>
        </h2>
  
    <div style="padding-top: 10px;">    
        <?php
  
  if($view == 'edit') {
  $this->edit_page($this->post("id"));
  }else if($view == "logs") {
  $this->log_page();
  }else if($view == "accounts") {
  $this->accounts_page();
  } else if($view == "settings") {
  $this->settings_page();
  }else if(isset($tabs[$tab]) && is_array($tabs[$tab])) {
  call_user_func($tabs[$tab]['function']);    
  }   else {
  $this->list_page();
  }
  ?>
  </div>
  </div>
              <script type="text/javascript">
  jQuery(document).ready(function($){

                        var unsaved=false;

      $('#mainform :input').change(function(){ 
        if(!$(this).attr('data-save')){ 
        unsaved=true;
      }
      });
       $('#mainform').submit(function(){ 
        unsaved=false;
      });
      
      $(window).bind("beforeunload",function(event) { 
    if(unsaved) return 'Changes you made may not be saved';
});

    $(document).on('click','.vx_toggle_key',function(e){
  e.preventDefault();  
  var key=$(this).parents(".vx_tr").find(".crm_text"); 

  if($(this).hasClass('vx_hidden')){ 
  $(this).text('<?php esc_html_e('Show Key','integration-for-contact-form-7-and-spreadsheets') ?>');  
  $(this).removeClass('vx_hidden');
  key.attr('type','password');  
  }else{
  $(this).text('<?php esc_html_e('Hide Key','integration-for-contact-form-7-and-spreadsheets') ?>');  
  $(this).addClass('vx_hidden');
  key.attr('type','text');  
  }
  });
start_tooltip(); 
  $(document).on("click",".vx_new_feed",function(e){ 
        if($(this).attr('href') == '#'){
              e.preventDefault();
        alert('You can not create more feeds in free version');    
        }
    }); 
  });
    function start_tooltip(){
      // Tooltips
  var tiptip_args = {
  'attribute' : 'data-tip',
  'fadeIn' : 50,
  'fadeOut' : 50,
  'defaultPosition': 'top',
  'delay' : 200
  };
  jQuery(".vxc_tips").tipTip( tiptip_args );

  }
  
  </script>
  <?php
  }

  
  /**
  * Displays the crm feeds list page
  * 
  */
  private  function list_page(){
  if(!current_user_can($this->id.'_read_feeds')){
  esc_html_e('You do not have permissions to access this page','integration-for-contact-form-7-and-spreadsheets');    
  return;
  }
  $is_section=apply_filters('add_page_html_'.$this->id,false);

  if($is_section === true){
    return;
} 
  $config = $this->data->get_feed('new_form');
  $offset=$this->time_offset();
  if(isset($_POST["action"]) && $_POST["action"] == "delete"){
  check_admin_referer("vx_crm_ajax");
  
  $id = absint($_POST["action_argument"]);
  $this->data->delete_feed($id);
  ?>
  <div class="updated fade" style="margin:10px 0;">
  <p>
  <?php esc_html_e("Feed deleted.", 'integration-for-contact-form-7-and-spreadsheets') ?>
  </p>
  </div>
  <?php
  }
  else if (!empty($_POST["bulk_action"])){
  check_admin_referer("vx_crm_ajax");
  $selected_feeds = $this->post("feed");
  if(is_array($selected_feeds)){
  foreach($selected_feeds as $feed_id)
  $this->data->delete_feed($feed_id);
  }
  ?>
  <div class="updated fade" style="margin:10px 0;">
  <p>
  <?php esc_html_e("Feeds deleted.", 'integration-for-contact-form-7-and-spreadsheets') ?>
  </p>
  </div>
  <?php
  }
  $feeds = $this->data->get_feeds(); 

  wp_enqueue_script('jquery-ui-sortable');
$page_link=$this->link_to_settings('accounts');
$objects=$this->get_objects(); 
  $data=$this->get_data_object();
  $accounts=$data->get_accounts(true);
  //
   $new_feed_link=$this->get_feed_link($config['id']);
   
     if(!self::$is_pr){
       $total=$this->data->get_feeds_total();
       if($total > 1){
      $new_feed_link='#';     
       }
   }
  
  $valid_accounts= is_array($accounts) && count($accounts) > 0 ? true : false;
  
include_once(self::$path . "templates/feeds.php");
  }
  /**
  * Displays the crm feeds list page
  * 
  */
  public  function log_page(){
  
  if(!current_user_can($this->id.'_read_logs')){
  esc_html_e('You do not have permissions to access this page','integration-for-contact-form-7-and-spreadsheets');    
  return;
  }
  $is_section=apply_filters('add_page_html_'.$this->id,false);

  if($is_section === true){
    return;
} 
  $log_ids=array();
   $bulk_action=$this->post('bulk_action');
      $offset=$this->time_offset();
  if($bulk_action!=""){
   $log_id=$this->post('log_id');  
   if(is_array($log_id) && count($log_id)>0){
    foreach($log_id as $id){
     if(is_numeric($id)){
    $log_ids[]=(int)$id;     
     }   
    }
    if($bulk_action == "delete"){
$count=$this->data->delete_log($log_ids);
  $this->screen_msg(sprintf(__('Successfully Deleted %d Item(s)','integration-for-contact-form-7-and-spreadsheets'),$count));  
    }
    else if(in_array($bulk_action,array("send_to_crm_bulk","send_to_crm_bulk_force"))){
         self::$api_timeout='1000';
         
       foreach($log_ids  as $id){
  $log = $this->data->get_log_by_id($id); 
  
  $form_id=$this->post('form_id',$log);
  $entry_id=$this->post('entry_id',$log);
    $entry['__vx_id']=$entry_id;
  $form=$this->get_form($form_id);
  
  $entry['__vx_data']=json_decode($log['data'],true);    

  if(!empty($entry['__vx_data'])){ 
    $push=$this->push($entry,$form,$log['event'],true,$log);
  }else{
    $push=array('class'=>'error','msg'=>__('Entry Not Found','integration-for-contact-form-7-and-spreadsheets'));  
  }
    if(is_array($push) && isset($push['class'])){
    $this->screen_msg($push['msg'],$push['class']); 
    }
   ///var_dump($log_ids,$log); die();  
    }
   
   }
   }
    unset($_GET['bulk_action']);
    unset($_GET['vx_nonce']);
    //$logs_link=admin_url('admin.php?'.http_build_query($_GET));
    //wp_redirect($logs_link);
    // die();
  }
  wp_enqueue_script('jquery-ui-datepicker' );
     wp_enqueue_style('vx-datepicker');
  $times=array("today"=>"Today","yesterday"=>"Yesterday","this_week"=>"This Week","last_7"=>"Last 7 Days","last_30"=>"Last 30 Days","this_month"=>"This Month","last_month"=>"Last Month","custom"=>"Select Range"); 
  $data= $this->data->get_log(); $items=count($data['feeds']);
  
  $crm_order=$entry_order=$desc_order=$time_order="up"; 
  $crm_class=$entry_class=$desc_class=$time_class="vx_hide_sort";
  $order=$this->post('order');
    $order_icon= $order == "desc" ? "down" : "up"; 
  if(isset($_REQUEST['orderby'])){
  switch($_REQUEST['orderby']){
  case"crm_id": $crm_order=$order_icon;  $crm_class="";   break;    
  case"entry_id": $entry_order=$order_icon; $entry_class="";    break;    
  case"object": $desc_order=$order_icon; $desc_class="";   break;    
  case"time": $time_order=$order_icon; $time_class="";   break;    
  }          
  }
    $bulk_actions=array(""=>__('Bulk Action','integration-for-contact-form-7-and-spreadsheets'),"delete"=>__('Delete','integration-for-contact-form-7-and-spreadsheets'),
  'send_to_crm_bulk'=>__('Send to Google Sheets','integration-for-contact-form-7-and-spreadsheets'),'send_to_crm_bulk_force'=>__('Force Send to Google Sheets - Ignore filters','integration-for-contact-form-7-and-spreadsheets'));
  $base_url=$this->get_base_url();
   $objects=get_option($this->id.'_meta',array()); 

   $objects=$this->post('objects',$objects);
      $statuses=array("1"=>__("Created",'integration-for-contact-form-7-and-spreadsheets'),"2"=>__("Updated",'integration-for-contact-form-7-and-spreadsheets'),"error"=>__("Failed",'integration-for-contact-form-7-and-spreadsheets'),"4"=>__("Filtered",'integration-for-contact-form-7-and-spreadsheets'),"5"=>__("Deleted",'integration-for-contact-form-7-and-spreadsheets')); 

include_once(self::$path . "templates/logs.php");
  }



/**
* feed link
* 
* @param mixed $id
*/
public function get_feed_link($id=""){
    $tab='feeds';
    if(!empty($id)){
        $tab='edit';
    }
    $str="admin.php?page={$this->id}&tab={$tab}&id={$id}" ;
  return  admin_url( $str );
}  

  /**
  * Field mapping HTML
  * 
  * @param mixed $feed
  * @param mixed $settings
  * @param mixed $refresh
  * @return mixed
  */
  private  function get_field_mapping($feed,$info="",$refresh=false){
  $fields=array(); 
   if($info == ""){
       $account=$this->post('account',$feed);
  $info=$this->get_info($account);
  }

  if(empty($feed['form_id']) || empty($feed['object']))
  return ''; 
  $module=''; $form_id=0;
  if(isset($feed['object']))
  $module=$feed['object'];
  if(isset($feed['form_id']))
  $form_id=$feed['form_id'];
  //
$api_type=isset($info['data']['api']) ? $info['data']['api'] : '';
$info_meta= isset($info['meta']) && is_array($info['meta']) ? $info['meta'] : array(); 
$feed_meta= isset($feed['meta']) && is_array($feed['meta']) ? $feed['meta'] : array(); 
$info_data= isset($info['data']) && is_array($info['data']) ? $info['data'] : array(); 
$id= isset($feed['id']) ? $feed['id'] : ''; 
//
  $meta=isset($feed['data']) && is_array($feed['data']) ? $feed['data'] : array();

  $map=isset($meta['map']) && is_array($meta['map']) ? $meta['map'] : array(); 

  $tab=isset($meta['tab']) ? $meta['tab'] : ''; 
  $optin_field=isset($meta['optin_field']) ?$meta['optin_field'] : ''; 
  
      if(!isset($meta['row_type'])){
        $meta['row_type']=''; 
      if(empty($feed['account'])){
    $meta['row_type']='INSERT_ROWS';  
  } }
  
  if($this->ajax){ 
  $api=$this->get_api($info);
  $fields=$api->get_crm_fields($module,$tab);  

if(is_array($fields) && is_array($fields['fields'])){
  $info_meta['fields']=$fields;     
  $info_meta['object']=$module;     
  $info_meta['feed_id']=$this->post('id');   
  $this->update_info( array('meta'=>$info_meta),$info['id']);        
  }   
  }else{
 $fields=$this->post('fields',$feed_meta); 
  }
 

  if(!is_array($fields)){
  $fields= $fields == "" ? "Error while getting fields" : $fields;   
  ?>
  <div class="error below-h2">
  <p><?php echo wp_kses_post($fields)?></p>
  </div>
  <?php
  return;
  }
  $meta=isset($feed['data']) && is_array($feed['data']) ? $feed['data'] : array();
  
  $map=isset($meta['map']) && is_array($meta['map']) ? $meta['map'] : array(); 

  $optin_field=isset($meta['optin_field']) ?$meta['optin_field'] : ''; 
 
 $tabs= is_array($fields) && !empty($fields['tabs']) ? $fields['tabs'] : array();
$fields= is_array($fields) && !empty($fields['fields']) ? $fields['fields'] : array();
 
  $vx_op=$this->get_filter_ops(); 
  if(isset($meta['filters']) && is_array($meta['filters'])&& count($meta['filters'])>0){
  $filters=$meta['filters'];    
  }else{
  $filters=array("1"=>array("1"=>array("field"=>"")));   
  }
  $map_fields=array();
  $has_contact=false;

  $n=0;
  foreach($map as $field_k=>$field_v){
  if(isset($fields[$field_k])){  $n++;
       if($n == 1){ $fields[$field_k]['req']='true'; }
  $map_fields[$field_k]=$fields[$field_k];    
  }  
}

$sel_fields=array(""=>__("Standard Field",'integration-for-contact-form-7-and-spreadsheets'),"value"=>__("Custom Value",'integration-for-contact-form-7-and-spreadsheets'));
?>
   <div class="vx_div">
        <div class="vx_head">
<div class="crm_head_div"> <?php esc_html_e('5. Select Google Sheet Tab.', 'integration-for-contact-form-7-and-spreadsheets'); ?></div>
<div class="crm_btn_div" title="<?php esc_html_e('Expand / Collapse','integration-for-contact-form-7-and-spreadsheets') ?>"><i class="fa crm_toggle_btn vx_action_btn fa-minus"></i></div>
<div class="crm_clear"></div> 
  </div>

  <div class="vx_group">
    <div class="vx_row">
  <div class="vx_col1">
  <label for="vx_module" class="left_header"><?php esc_html_e("Google Sheets Tab", 'integration-for-contact-form-7-and-spreadsheets'); ?>
 </label>
  </div>
  <div class="vx_col2">
  <select id="vx_tab" class="load_form crm_sel" name="meta[tab]" autocomplete="off">
  <?php
  foreach ($tabs as $k=>$v){
  $sel=$meta['tab'] == $v ? 'selected="selected"' : "";
  ?>
  <option value="<?php echo esc_attr($v) ?>" <?php echo esc_attr($sel); ?>><?php echo esc_attr($v) ?></option>
  <?php
  }
  ?>
  </select>
  </div>
  <div class="clear"></div>
  </div>
  </div>
  </div>
<?php
include_once(self::$path . "templates/fields-mapping.php"); 
  }

 
  /**
  * Updates feed
  * 
  */
  public  function update_feed(){
  check_ajax_referer('vx_crm_ajax','vx_crm_ajax');
  if(!current_user_can($this->id."_edit_feeds")){ 
  return;   
  }
  $id = $this->post("feed_id");
  $feed = $this->data->get_feed($id);
  $this->data->update_feed(array("is_active"=>$this->post("is_active")),$id);
  } 
  
  /**
  * Update the feed sort order
  *
  * @since  3.1
  * @return void
  */
  public  function update_feed_sort(){
  check_ajax_referer('vx_crm_ajax','vx_crm_ajax');
    if(!current_user_can($this->id."_edit_feeds")){ 
  return;   
  }
  if( empty( $_POST['sort'] ))
  {
  exit(false);
  }
  
  $this->data->update_feed_order($this->post('sort'));
  }
  public function set_logging_supported($plugins) {
      $slug=$this->plugin_dir_name(); 
        $plugins[$slug] = esc_html__('Google Sheets','integration-for-contact-form-7-and-spreadsheets');
        return $plugins;
    }
  /**
  * Field map ajax method
  * 
  */
  public  function get_field_map_ajax(){
        check_ajax_referer('vx_crm_ajax','vx_crm_ajax');
  if(!current_user_can($this->id."_read_feeds")){ 
  return;   
  }
$this->ajax=true;
  $msg="";
  if(empty($_REQUEST['module'])){
  $msg=__("Please Choose Object",'integration-for-contact-form-7-and-spreadsheets');
  }else  if(empty($_REQUEST['form_id'])){
  $msg=__("Please Choose Form",'integration-for-contact-form-7-and-spreadsheets');
  }
  if($msg !=""){
  ?>
  <div class="error below-h2" style="background: #f3f3f3">
  <p><?php echo esc_html($msg); ?></p>
  </div>
  <?php
  die();
  }     
  $module=$this->post('module');
  $form_id=$this->post('form_id');
    $tab=$this->post('tab');
  $refresh=$_REQUEST['refresh'] == "true" ? true: false;
    $id=$this->post('id');
  $feed=$this->data->get_feed($id);
    $this->account=$account=$this->post('account');

  $info=$this->get_info($account); 
/*  $object=$this->post('object',$feed);
  if(!$refresh && $object != $module){
   $refresh=true;   
  } */
  $feed['form_id']=$form_id;  
  $feed['object']=$module;  
  $feed['data']['tab']=$tab;   
  
  $this->get_field_mapping($feed,$info,true); 
  die();
  } 
  public  function get_field_map_object_ajax(){
        check_ajax_referer('vx_crm_ajax','vx_crm_ajax');
  if(!current_user_can($this->id."_read_feeds")){ 
  return;   
  }
   $this->ajax=true;
  $msg="";
  if(empty($_REQUEST['account'])){
  $msg=__("Please Choose Account",'integration-for-contact-form-7-and-spreadsheets');
  }else  if(empty($_REQUEST['form_id'])){
  $msg=__("Please Choose Form",'integration-for-contact-form-7-and-spreadsheets');
  }
  if($msg !=""){
  ?>
  <div class="error below-h2" style="margin-top: 20px;">
  <p><?php echo esc_html($msg); ?></p>
  </div>
  <?php
  die();
  }     
  $this->account=$account=$this->post('account');
  $form_id=$this->post('form_id');
    $id=$this->post('id');
    $feed= $this->data->get_feed($id);
      $feed['form_id']=$form_id;
  $info=$this->get_info($account); 
/*  $object=$this->post('object',$feed);
  if(!$refresh && $object != $module){
   $refresh=true;   
  } */
$this->field_map_object($account,$form_id,$feed,$info);
  die();
  }
    /**
  * available operators for custom filters
  * 
  */
  public function get_filter_ops(){
           return array("is"=>"Exactly Matches","is_not"=>"Does Not Exactly Match","contains"=>"(Text) Contains","not_contains"=>"(Text) Does Not Contain","is_in"=>"(Text) Is In","not_in"=>"(Text) Is Not In","starts"=>"(Text) Starts With","not_starts"=>"(Text) Does Not Start With","ends"=>"(Text) Ends With","not_ends"=>"(Text) Does Not End With","less"=>"(Number) Less Than","greater"=>"(Number) Greater Than","less_date"=>"(Date/Time) Less Than","greater_date"=>"(Date/Time) Greater Than","equal_date"=>"(Date/Time) Equals","empty"=>"Is Empty","not_empty"=>"Is Not Empty"); 
  }
  /**
  * crm fields select options
  * 
  * @param mixed $fields
  * @param mixed $selected
  */
  public function crm_select($fields,$selected,$first_empty=true){
  $field_options=""; 
    if($first_empty){ 
  $field_options="<option value=''></option>";
  }
    if(is_array($fields)){
        foreach($fields as $k=>$v){
              if(isset($v['label'])){
  $sel=$selected == $k ? 'selected="selected"' : "";
  $field_options.="<option value='".esc_attr($k)."' ".$sel.">".esc_html($v['label'])."</option>";      
  }
        }
    }
  return $field_options;    
  }
        /**
  * general(key/val) select options
  * 
  * @param mixed $fields
  * @param mixed $selected
  */
  public function gen_select($fields,$selected,$placeholder=""){
  $field_options="<option value=''>".esc_html($placeholder)."</option>"; 
    if(is_array($fields)){
        foreach($fields as $k=>$v){
  $sel=$selected == $k ? 'selected="selected"' : "";
  $field_options.="<option value='".esc_attr($k)."' ".$sel.">".esc_html($v)."</option>";      
        }
    }
  return $field_options;    
  }
  public function get_object_feeds($form_id,$account,$object,$skip=''){ 
      
$feeds=$this->data->get_object_feeds($form_id,$account,$object,$skip);
$arr=array();
if(is_array($feeds) && count($feeds)>0){
    foreach($feeds as $k=>$feed){
      if(isset($feed['id'])){
      $arr[$feed['id']]=$feed['name'];    
      }  
    }
}
return $arr;
}
  /**
  * Log detail
  * 
  */
public function log_detail(){
$log_id=$this->post('id');
$log=$this->data->get_log_by_id($log_id); 

  $data=json_decode($log['data'],true); 
  $response=json_decode($log['response'],true);
    $triggers=array('manual'=>'Submitted Manually','submit'=>'Form Submission','update'=>'Entry Update'
  ,'delete'=>'Entry Deletion','add_note'=>'Entry Note Created','delete_note'=>'Entry Note Deleted');
  $event= empty($log['event']) ? 'manual' : $log['event'];
  $extra=array('Object'=>$log['object']);
  if(isset($triggers[$event])){
    $extra['Trigger']=$triggers[$event];  
  }
  $extra_log=json_decode($log['extra'],true);
  if(is_array($extra_log)){
      $extra=array_merge($extra,$extra_log);
  }
  $error=true; 
  $vx_ops=$this->get_filter_ops();
  $form_id=$this->post('form_id',$log);
  $labels=array("url"=>"URL","body"=>"Search Body","response"=>"Search Response","filter"=>"Filter",'note_object_link'=>'Note Object ID');
  $log_link=$this->link_to_settings('logs').'&log_id='.$log['id']; 
include_once(self::$path . "templates/log.php");
die();
}

        /**
     * Get Objects , AJAX method
     * @return null
     */
public function get_objects_ajax(){
    check_ajax_referer('vx_crm_ajax','vx_crm_ajax');
    

    $object=$this->post('object');
    $account=$this->post('account');
    $crm=$this->get_info($account); 

  $objects=$this->get_objects($crm,true); 

echo "<option>".esc_html__("Select Object",'integration-for-contact-form-7-and-spreadsheets')."</option>"; 
  if(is_array($objects)){
  foreach($objects as $k=>$v){
      $sel="";
      if($k == $object){
          $sel='selected="selected"';
      }
  echo "<option value='".esc_attr($k)."' ".esc_attr($sel).">".esc_attr($v)."</option>";      
  }  
  }

die();
}
  /**
  * Settings page
  * 
  */
  public  function settings_page(){ 
  if(!current_user_can($this->id.'_read_settings')){
  $msg_text=__('You do not have permissions to access this page','integration-for-contact-form-7-and-spreadsheets');   
  $this->display_msg('admin',$msg_text); 
  return;
  }
  

  $is_section=apply_filters('add_page_html_'.$this->id,false);

  if($is_section === true){
    return;
}  
  $msgs=array(); $lic_key=false;
  $message=$force_check= false;
   $id=$this->post('id');
   $tooltips=self::$tooltips;
  
   
  if(!empty($_POST[$this->id."_uninstall"])){
  check_admin_referer("vx_nonce");
  if(!current_user_can($this->id."_uninstall")){
  return;
  }    
  $this->uninstall();
  $uninstall_msg=sprintf(__("Contact Form Google Sheets Plugin has been successfully uninstalled. It can be re-activated from the %s plugins page %s.", 'integration-for-contact-form-7-and-spreadsheets'),"<a href='plugins.php'>","</a>");
$this->screen_msg($uninstall_msg);
  return;
  }
                


          $meta=get_option($this->type.'_settings',array());

       if(!empty($_POST['save'])){ 
             if(current_user_can($this->id."_edit_settings")){ 

  $meta=isset($_POST['meta']) ? $this->post('meta') : array();
  
   $msgs['submit']=array('class'=>'updated','msg'=>__('Settings Changed Successfully','integration-for-contact-form-7-and-spreadsheets'));
  update_option($this->type.'_settings',$meta);
  }      
      } 
     $this->show_msgs($msgs);
    $nonce=wp_create_nonce("vx_nonce"); 
include_once(self::$path . "templates/settings.php");
  } 
  /**
  * Accounts page
  * 
  */
  public  function accounts_page(){ 
  if(!current_user_can($this->id.'_read_settings')){
  $msg_text=__('You do not have permissions to access this page','integration-for-contact-form-7-and-spreadsheets');   
  $this->display_msg('admin',$msg_text); 
  return;
  }
  $is_section=apply_filters('add_page_html_'.$this->id,false);

  if($is_section === true){
    return;
}  
  $msgs=array(); $lic_key=false;
  $message=$force_check= false;
   $id=$this->post('id');
   $tooltips=self::$tooltips;
 $offset=$this->time_offset();
 
  if(!empty($_POST["save"])){ //var_dump($_REQUEST); die(); 
  check_admin_referer("vx_nonce");
  if(!current_user_can($this->id."_edit_settings")){ 
  esc_html_e('You do not have permissions to save settings','integration-for-contact-form-7-and-spreadsheets');
  return;   
  }
  $crm_post=$this->post('crm');
  $msgs['submit']=array('class'=>'updated','msg'=>__('Settings Changed Successfully','integration-for-contact-form-7-and-spreadsheets'));
  $valid_email=true;
  if( !empty($crm_post['error_email'])){
   $emails=explode(",",$crm_post['error_email']);
  foreach($emails as $email){
      $email=trim($email);
    if($email !="" && !$this->is_valid_email($email)){
  $valid_email=false; 
    }  
  }   
  }
  if(!$valid_email){
      $msgs['submit']=array("class"=>"error","msg"=>__('Invalid Email(s)','integration-for-contact-form-7-and-spreadsheets'));
  }
   $info=$this->get_info($id); 
   $crm=$info['data'];
   if(isset($info['data']) && is_array($info['data']) && is_array($crm_post)){
       $crm= array_merge($info['data'],$crm_post);
   }
 $crm['custom_app']=$this->post('custom_app',$crm_post);
  /////////////                          
  $this->update_info(array('data'=> $crm),$id);
  $force_check=true;
  ////////////////////
  }                

  $data=$this->get_data_object();
  $new_account_id=$data->get_new_account();
 $page_link=$this->link_to_settings('accounts');
 $new_account=$page_link."&id=".$new_account_id;
  if(!empty($id)){
  $info=$this->get_info($id);  

  if(empty($info)){
   $id="";   
  } }
  if(!empty($id)){   
  $valid_user=false;
  
  
  $api=$this->get_api($info);
  if(empty($_POST)){
   $api->timeout="5";   
  }
  $client=$api->client_info();
  $link=$page_link.'&id='.$id;
  if(!$force_check && isset($_POST['vx_test_connection'])){
    $force_check=true;  
  }
//  $force_check=true;
  $info=$this->validate_api($info,$force_check);
   
    $con_class=$this->post('class',$info);
  if(!empty($con_class)){
      
   $msgs['connection']=array('class'=>$con_class,'msg'=>$info['msg']);
   }
                if(isset($_POST['vx_test_connection'])){  
  if($con_class != "updated" ){
      $msg=__('Connection to Google Sheets is NOT Working','integration-for-contact-form-7-and-spreadsheets');  
  }else{
     $msg=__('Connection to Google Sheets is Working','integration-for-contact-form-7-and-spreadsheets');   
  }
  $title=__('Test Connection: ','integration-for-contact-form-7-and-spreadsheets');
  $msgs['test']=array('class'=>$con_class,'msg'=>'<b>'.$title.'</b>'.$msg);
  }
if(!empty($_GET['vx_debug'])){
  $msgs['debug']=array('class'=>'error','msg'=>json_encode($info));  
}
  }else{
      $accounts=$data->get_accounts();

  }

        $nonce=wp_create_nonce("vx_nonce"); 
include_once(self::$path . "templates/accounts.php");
  } 

    /**
  * Create or edit crm feed page
  * 
  */
  private  function edit_page($fid=""){ 
  if(!current_user_can($this->id.'_read_feeds')){
  esc_html_e('You do not have permissions to access this page','integration-for-contact-form-7-and-spreadsheets');    
  return;
  }
  wp_enqueue_style('vx-fonts');
    wp_enqueue_script('vxg-select2' );
  wp_enqueue_style('vxg-select2');
  wp_enqueue_script( 'jquery-ui-sortable');
  $is_section=apply_filters('add_page_html_'.$this->id,false);

  if($is_section === true){
    return;
} 
$msgs=array();
   $feed= $this->data->get_feed($fid);
           //updating meta information
  if(isset($_POST[$this->id."_submit"])){ 
  check_admin_referer("vx_nonce");
  if(!current_user_can($this->id.'_edit_feeds')){
  esc_html_e('You do not have permissions to edit/save feed','integration-for-contact-form-7-and-spreadsheets'); 
  return;
  }
  //
  $time = current_time( 'mysql' ,1);
  $feed_update=array("data"=>$this->post("meta"),"name"=>$this->post('name'),"account"=>$this->post('account'),"object"=>$this->post('object'),"form_id"=>$this->post('form_id'),"time"=>$time);
if(!empty($_POST['account'])){
  $info=$this->get_info($this->post('account'));

  if(isset($info['meta']['feed_id']) && isset($info['meta']['fields']) && !empty($info['meta']['feed_id']) && $info['meta']['feed_id'] == $fid ){
 $meta=isset($feed['meta']) && is_array($feed['meta']) ? $feed['meta'] : array();
 $info_meta=$info['meta'];
 $meta['fields']=$info_meta['fields'];
 $feed_update['meta']=$meta;
 unset($info_meta['feed_id']);

 $this->update_info(array('meta'=>$info_meta),$info['id']);
} }
if(is_array($feed_update) && is_array($feed)){
    $feed=array_merge($feed,$feed_update);
} 
  $is_valid=$this->data->update_feed($feed_update,$fid);

  if($is_valid){
      $feed_link=$this->link_to_settings('feeds');
      $msgs['save']=array('class'=>'updated','msg'=>sprintf(__("Feed Updated. %sback to list%s", 'integration-for-contact-form-7-and-spreadsheets'), '<a href="'.$feed_link.'">', "</a>"));
  }
  else{
  $msgs['save']=array('class'=>'error','msg'=>__("Feed could not be updated. Please enter all required information below.", 'integration-for-contact-form-7-and-spreadsheets'));

  }
  } 
    //getting  API
  $_data=$this->get_data_object();
  $accounts=$_data->get_accounts(true); 
  $forms=$this->get_forms(); 
 
  $account=$this->post('account',$feed);
  $form_id=$this->post('form_id',$feed);
  $info=$this->get_info($account); 
  $config = $this->data->get_feed('new_form');
  $feeds_link=$this->link_to_settings('feeds');  
  $feed_link=$this->link_to_settings('edit');
  $new_feed_link=$feed_link.'&id='.$config['id'];  
// $form_id=$this->post('id');

   if(!self::$is_pr){
       $total=$this->data->get_feeds_total();
       if($total > 1){
      $new_feed_link='#';     
       }
   }

include_once(self::$path . "templates/feed-account.php");
  
  }
    /**
  * all form fields + addon fields
  * 
  * @param mixed $form_id
  */
  public  function get_all_fields($form_id,$account='',$feed_id=''){
      
      if($this->fields ){
     return $this->fields;     
      }

$tags=$this->get_form_fields($form_id); 
 
if(is_array($tags)){
  foreach($tags as $id=>$tag){
   $fields[$id]=array('id'=>$id,'label'=>$tag['label']);    
   }   
    
}  
  $fields['__vx_id']=array('id'=>'__vx_id','label'=>__('Entry ID','integration-for-contact-form-7-and-spreadsheets')); 
$fields['_vx_form_id']=array('id'=>'_vx_form_id','label'=>__('Form ID','integration-for-contact-form-7-and-spreadsheets')); 
$fields['_vx_form_name']=array('id'=>'_vx_form_title','label'=>__('Form Title','integration-for-contact-form-7-and-spreadsheets')); 
$fields['_vx_url']=array('id'=>'_vx_url','label'=>__('Page URL','integration-for-contact-form-7-and-spreadsheets')); 
$fields['_vx_created']=array('id'=>'_vx_created','label'=>__('Entry Created','integration-for-contact-form-7-and-spreadsheets')); 
$fields['_vx_updated']=array('id'=>'_vx_updated','label'=>__('Entry Updated','integration-for-contact-form-7-and-spreadsheets')); 

  $this->fields=$fields=array('cf'=>array("title"=>__('Contact Form Fields','integration-for-contact-form-7-and-spreadsheets'),"fields"=>$fields));

  if($this->do_actions()){ 
  $fields=apply_filters('vx_mapping_standard_fields',$this->fields);
  if(!empty($account)){
  $contact_feeds=$this->get_object_feeds($form_id,$account,'',$feed_id);
  $feeds=array();
  if(!empty($contact_feeds)){
      foreach($contact_feeds as $k=>$v){
      $feeds['_vx_feed-'.$k]=array('id'=>'_vx_feed-'.$k,'label'=>$v);    
      }
  $fields['feeds']=array("title"=>__('ID from other Feeds','integration-for-contact-form-7-and-spreadsheets'),"fields"=>$feeds);
  } }
  $this->fields=$fields;
  }
  return $fields;
  }
    /**
  * contact form fields label
  * 
  * @param mixed $form_id
  * @param mixed $key
  */
  public function get_gf_field_label($form_id,$key){
  $fields=$this->get_all_fields($form_id);  
  $label=$key;
  if(is_array($fields)){
  foreach($fields as $ke=>$field){
      if(isset($field['fields']) && is_array($field['fields']) ){
          foreach($field['fields'] as $k=>$v){     
                if($ke == "gf"){
   $k=$v[0];   
  }
  if($k == $key && isset($field['fields'][$k])){
    if($ke == "gf"){
   $label=$v[1];     
    }else if(isset($field['fields'][$k]['label'])){
   $label= $field['fields'][$k]['label'];     
    }  
  if(!empty($label)){
      return $label;
  }
  }
  
          }
      }
      
  }}
  return $label;
  }
  /**
  * contact form field select options
  * 
  * @param mixed $form_id
  * @param mixed $selected_val
  */
  public  function  form_fields_options($form_id,$sel_val="",$account='',$feed_id=''){
  if($this->fields == null){
  $this->fields=$this->get_all_fields($form_id,$account,$feed_id);
  }  //var_dump($this->fields);// die(); 
      if(!is_array($sel_val)){
$sel_val=array($sel_val);
      }
  $sel="<option value=''></option>";
  $fields=$this->fields; 
  if(is_array($fields)){
  foreach($fields as $key=>$fields_arr){
if(is_array($fields_arr['fields'])){
    $sel.="<optgroup label='".esc_html($fields_arr['title'])."'>";
      foreach($fields_arr['fields'] as $k=>$v){
          $option_k=$k;
          $option_name=$v;

    $option_name=$v['label'];  

          $select="";
if( in_array($option_k,$sel_val)){
  $select='selected="selected"';

  }
  $sel.='<option value="'.esc_attr($option_k).'" '.$select.'>'.esc_html($option_name).'</option>';    
  }    }
  }}  
  return $sel;    
  }  
  /**
  * field mapping box's Contents
  * 
  */
  public function field_map_object($account,$form_id,$feed,$info) { 
  
  //get objects from crm
  $objects=$this->get_objects($info); 
  
  if(empty($feed['object'])){
      $feed['object']="";
  }
  if(!empty($feed['object']) && is_array($objects) && !isset($objects[$feed['object']])){
  $feed['object']="";     
  }  
  $modules=array(""=>__("Select Object",'integration-for-contact-form-7-and-spreadsheets'));
  if(isset($objects) && is_array($objects)){
  foreach($objects as $k=>$v){
  $modules[$k]=$v;     
  }   
  } 
  $meta=$this->post('meta',$info);
  $object=$this->post('object',$feed); 
 include_once(self::$path."templates/feed-object.php");  
  }
  /**
  * validate API
  * 
  * @param mixed $info
  * @param mixed $force_check
  */
  /**
  * validate API
  * 
  * @param mixed $info
  * @param mixed $force_check
  */
  public function validate_api($row,$check=false){
  $info=$this->post('data',$row);
$api_check=(int)$this->post('valid_api',$info);

  if($check){
  $api=$this->get_api($row); 
    $res=$api->get_crm_objects(); 
  if(!empty($res) && is_string($res)){
  $info['error']=$res;   
  unset($info['access_token']); 
  }
  } 
  if(!empty($info['access_token']) && !empty($info['refresh_token'])) { 
  $msg=__( 'Successfully Connected to Google Sheets','integration-for-contact-form-7-and-spreadsheets' );
     if(!empty($info['time'])){
         if(!is_numeric($info['time'])){
         $info['time']=strtotime($info['time']);    
         }
       $msg.=" - ".date('F d, Y h:i:s A',$info['time']);
   }
      $info['msg']=$msg; 
  $info['class']="updated";     
  
  }else{
  $info['class']="";  
  if(isset($info['error']) && isset($info['expires']) && $info['error'] !=""){
  $info['msg']=$info['error']; 
  $info['class']="error"; 
  }       }
  return $info;
  }
public function get_forms(){

      $all_forms=array();
      global $vxcf_form;
if(is_object($vxcf_form) && method_exists($vxcf_form,'get_forms')){
    $all_forms=$vxcf_form->get_forms();  
}else{
     if(class_exists('WPCF7_ContactForm')){
    if( !function_exists('wpcf7_contact_forms') ) {
        $cf_forms = get_posts( array(
            'numberposts' => -1,
            'orderby' => 'ID',
            'order' => 'ASC',
            'post_type' => 'wpcf7_contact_form' ) );
    }
    else {
        $forms = wpcf7_contact_forms();
        $cf_forms=array();
        if(count($forms)>0){
            foreach($forms as $k=>$f){
             $v=new stdClass();
               if( isset( $f->id ) ) {
                    $v->ID = $f->id;    // as serialized option data
                } 
                 if( isset( $f->title ) ) {
                    $v->post_title = $f->title;    // as serialized option data
                }   
            $cf_forms[]=$v;
            }
        }
    }

  $forms_arr=isset($all_forms['cf']['forms']) && is_array($all_forms['cf']['forms']) ? $all_forms['cf']['forms'] :  array(); //do not show deleted forms

    if(is_array($cf_forms) && count($cf_forms)>0){
        $forms_arr=array();
 foreach($cf_forms as $form){
     if(!empty($form->post_title)){
  $forms_arr[$form->ID]=$form->post_title;       
     }
 } 
        $all_forms['cf']=array('label'=>'Contact Form 7','forms'=>$forms_arr); 
    } 
 ///////   
    }
if(defined('ELEMENTOR_PRO_VERSION') ){  //&& class_exists('ElementorPro\\Plugin')
    global $wpdb;
$data = $wpdb->get_results( "SELECT m.post_id, m.meta_value,p.post_title FROM $wpdb->postmeta m inner join $wpdb->posts p on(m.post_id=p.ID) WHERE p.post_status='publish' and m.meta_key = '_elementor_data' limit 100" , ARRAY_A  ); //__elementor_forms_snapshot
  $forms_arr=array();  
  
foreach($data as $v){
    $elforms=json_decode($v['meta_value'],true); 
    $elforms=self::find_el_forms($elforms);   
    foreach($elforms as $form){
        $id=$form['id'].'_'.$v['post_id'];
   
    $forms_arr[$id]=$form['settings']['form_name'].' - '.substr($v['post_title'],0,200); 
         
    }
}
if(!empty($all_forms_db['el']['forms'])){ 
 foreach($all_forms_db['el']['forms'] as $k=>$v){
   if(!isset($forms_arr[$k])){ $forms_arr[$k]=$v; }
 }   
    
}  
if(!empty($forms_arr)){
$all_forms['el']=array('label'=>'Elementor Forms','forms'=>$forms_arr); }
//   
}  
if(class_exists('Ninja_Forms') && method_exists(Ninja_Forms(),'form')){
//$forms = Ninja_Forms()->forms()->get_all();
 $forms_arr=isset($all_forms['na']['forms']) && is_array($all_forms['na']['forms']) ? $all_forms['na']['forms'] :  array();
  global $wpdb;
  $sql = "SELECT `id`, `title`, `created_at` FROM `{$wpdb->prefix}nf3_forms` ORDER BY `title`";
  $nf_forms = $wpdb->get_results($sql, ARRAY_A);    
        //  die();
//$nf_forms = nf_get_objects_by_type( 'form' );
  if(is_array($nf_forms) && count($nf_forms)>0){
    foreach($nf_forms as $form){
     if(!empty($form['id'])){
     // $title = Ninja_Forms()->form( $form['id'] )->get_setting( 'form_title' );
      $forms_arr[$form['id']]=$form['title'];   
     }   
    }
     $all_forms['na']=array('label'=>'Ninja Forms','forms'=>$forms_arr); 
  }
 
    }  
if(function_exists('wpforms') && method_exists(wpforms()->form,'get')){
$forms_arr=wpforms()->form->get( '' );
if(!empty($forms_arr)){
$forms=array();
foreach($forms_arr as $v){
    $forms[$v->ID]=$v->post_title;
}
$all_forms['wp']=array('label'=>'WP Forms','forms'=>$forms);
//$forms=json_decode($forms->post_content,true);
}
}
 //formidable
        if(class_exists('FrmForm')){
     $gf_forms=FrmForm::getAll(array('status'=>'published','is_template'=>'0'));  
      $forms_arr=isset($all_forms['fd']['forms']) && is_array($all_forms['fd']['forms']) ? $all_forms['fd']['forms'] :  array();
    if(is_array($gf_forms) && count($gf_forms)>0){
 foreach($gf_forms as $form){
     if(!empty($form->id)){
  $forms_arr[$form->id]=$form->name;       
     }
 } 
        $all_forms['fd']=array('label'=>'Formidable Forms','forms'=>$forms_arr); 
    } 
    }
}
$all_forms=apply_filters('vx_add_crm_form',$all_forms);

   return $all_forms;
  }
   public function add_msg($msg,$level='updated'){
   $option=get_option($this->id.'_msgs',array());
   if(!is_array($option)){
   $option=array();    
   }
   $option[]=array('msg'=>$msg,'class'=>$level);
 update_option($this->id.'_msgs',$option);  
 }
    public function show_msgs($msgs=""){ 
/* $option=get_option($this->id.'_msgs',array());
 if(is_array($option) && count($msgs)>0){
//     $msgs=array_merge($msgs,$option);
 } */
   if(is_array($msgs) && count($msgs)>0){
   foreach($msgs as $msg){
     $this->screen_msg($msg['msg'],$msg['class']);  
   }
 /* if(empty($option)){ 
//  update_option($this->id.'_msgs',array());
  } */ 
   }  
 } 
    /**
  * Tooltip image
  * 
  * @param mixed $str
  */
  public function tooltip($str){
   
  if(!isset(self::$tooltips[$str])){return;}
  ?>
  <i class="vx_icons vxc_tips fa fa-question-circle" data-tip="<?php echo esc_attr(self::$tooltips[$str]) ?>"></i> 
  <?php  
  }
}
}
new vxcf_googlesheets_pages();
