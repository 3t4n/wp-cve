<?php
/**
* Plugin Name: Contact Form 7 Zendesk
* Description: Integrates Contact Form 7, <a href="https://wordpress.org/plugins/contact-form-entries/">Contact Form Entries Plugin</a> and many other forms with Zendesk allowing form submissions to be automatically sent to your Zendesk account 
* Version: 1.1.3
* Requires at least: 3.8
* Tested up to: 6.4
* Author URI: https://www.crmperks.com
* Plugin URI: https://www.crmperks.com/plugins/contact-form-plugins/contact-form-zendesk-plugin/
* Author: CRM Perks
* Text Domain: contact-form-zendesk
* Domain Path: /languages/
*/
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


if( !class_exists( 'vxcf_zendesk' ) ):

class vxcf_zendesk {

  
 public  $url = "https://www.crmperks.com";

  public  $crm_name = "zendesk";
  public  $id = "vxcf_zendesk";
  public  $domain = "vxcf-zendesk";
  public  $version = "1.1.3";
  public  $update_id = "6000015";
  public  $min_cf_version = "1.0";
  public $type = "vxcf_zendesk";
  public  $fields = null;
  public  $data = null;

  private $filter_condition;
  private $plugin_dir= "";
  private $temp;
  private $crm_arr= false;
  public $notice_js= false;
  public static $title='Contact Form Zendesk Plugin';  
  public static $path = ''; 
  public static $slug = "";
  public static $debug_html = '';
  public static $save_key='';  
  public static  $lic_msg = "";
  public static $db_version='';  
  public static $vx_plugins;  
  public static $note;
  public static $feeds_res;    
  public static $cf_status='';    
  public static $cf_status_msg='';    
  public static $tooltips;    
  public static $entry_created=false;
  public static $plugin;       
  public static $api_timeout;        
  public static $is_pr;        

 public function instance(){ 
      add_action( 'plugins_loaded', array( $this, 'setup_main' ) );
register_deactivation_hook(__FILE__,array($this,'deactivate'));
register_activation_hook(__FILE__,(array($this,'activate')));

 }   
public  function init(){

/*      self::$cf_status= $this->cf_status();
    if(self::$cf_status !== 1){
  add_action( 'admin_notices', array( $this, 'install_cf_notice' ) );
  add_action( 'after_plugin_row_'.self::$slug, array( $this, 'install_cf_notice_plugin_row' ) );    
  return;
  } 
  */
  $pro_file=self::$path . 'wp/crmperks-notices.php';
if(file_exists($pro_file)){ 
   include_once($pro_file); 
}else{
//plugin api
$this->plugin_api(true);    
self::$is_pr=true;
 $pro_file=self::$path . 'pro/add-ons.php';
if(file_exists($pro_file)){
include_once($pro_file);
}

}

include_once(self::$path . "includes/crmperks-cf.php"); 

include_once(self::$path . "includes/plugin-pages.php");    
}
public function setup_main(){
  

 add_action('cfx_form_submitted', array($this, 'entry_created_crmperks'),10,3);
 add_action('vxcf_entry_created', array($this, 'entry_created'),10,3);
 add_action('vx_contact_created', array($this, 'entry_created_contacts'),10,3);
 add_action('vx_callcenter_entry_created', array($this, 'entry_created_callcenter'),10,3);
 
  add_filter('wpcf7_before_send_mail', array($this, 'create_entry_cf'),99);
   //formidable
  add_action('frm_after_create_entry', array($this, 'create_entry_fd'), 99, 2);
  add_action('ninja_forms_after_submission', array($this, 'create_entry_na'),99);
  add_action( 'wpforms_process_entry_save',array(&$this,'create_entry_wp'), 99, 4 );
//elemntor form
 add_action( 'elementor_pro/forms/new_record', array($this,'create_entry_el'), 99 );
      
      if(is_admin()){
  self::$path=$this->get_base_path(); 
  add_action('init', array($this,'init'));
       //loading translations
load_plugin_textdomain('contact-form-zendesk-crm', FALSE,  $this->plugin_dir_name(). '/languages/' );
  
  self::$db_version=get_option($this->type."_version");
  if(self::$db_version != $this->version && current_user_can( 'manage_options' )){
  $data=$this->get_data_object();
  $data->update_table();
  update_option($this->type."_version", $this->version);
  //add post permissions
  require_once(self::$path . "includes/install.php"); 
  $install=new vxcf_zendesk_install();
  $install->create_roles();   

  }
}
  
  }

public  function plugin_api($start_instance=false){
    if(empty(self::$path)){   self::$path=$this->get_base_path(); }
   $api_file=self::$path . "pro/plugin-api.php";
    if(!class_exists('vxcf_plugin_api') && file_exists( $api_file)){   
require_once( $api_file );
}
if(class_exists('vxcf_plugin_api')){
 $slug=self::get_slug();
 $settings_link=$this->link_to_settings();
 $is_plugin_page=$this->is_crm_page(); 
self::$plugin=new vxcf_plugin_api($this->id,$this->version,$this->type,$this->domain,$this->update_id,self::$title,$slug,self::$path,$settings_link,$is_plugin_page);
if($start_instance){
self::$plugin->instance();
}
} }

public function form_submitted($form){ 

    //entries plugin exists , do not use this hook
    if(class_exists('vxcf_form')){ return; }
    $form_id=$form->id();
     $submission = WPCF7_Submission::get_instance();  
    
     $lead = $submission->uploaded_files();
if(!is_array($lead)){  $lead=array(); }
$form_title=$form->title();
$tags=array();

if(method_exists('WPCF7_ShortcodeManager','get_instance') || method_exists('WPCF7_FormTagsManager','get_instance')){

         $form_text=get_post_meta($form_id ,'_form',true); 
         
if(method_exists('WPCF7_FormTagsManager','get_instance')){
    $manager=WPCF7_FormTagsManager::get_instance(); 
$contents=$manager->scan($form_text); 
$tags=$manager->get_scanned_tags();   

}else if(method_exists('WPCF7_ShortcodeManager','get_instance')){ //
 $manager = WPCF7_ShortcodeManager::get_instance();
$contents=$manager->do_shortcode($form_text);
$tags=$manager->get_scanned_tags();    
} }


if(is_array($tags)){
  foreach($tags as $k=>$v){
      if(!empty($v['name'])){
      $name=$v['name'];
$val=$submission->get_posted_data($name);
if(!isset($lead[$name])){  $lead[$name]=$val;  }
         
  }  }
}

$form_arr=array('id'=>'cf_'.$form_id,'name'=>$form_title,'fields'=>$tags);
$this->entry_created($lead,'0',$form_arr); 

}


public function create_entry_cf($form){ 
 if(class_exists('vxcf_form')){ return; }   
$form_id=$form->id();

$submission = WPCF7_Submission::get_instance();      
$uploaded_files = $submission->uploaded_files();

$form_title=$form->title();
$tags=$this->get_form_fields('cf_'.$form_id); 
$post_data=$submission->get_posted_data();
//var_dump($post_data); die();
 $lead=array();
if(is_array($post_data)){
  foreach($post_data as $k=>$val){
    if(in_array($k,array('vx_width','vx_height','vx_url','g-recaptcha-response'))){ continue; } 
       if(isset($tags[$k])){
          $v=$tags[$k];  //$v is empty for non form fields 
      }
     $name=$k;  //$v['name'] //if empty then $v is old
//var_dump($v);
 if(isset($uploaded_files[$name])){
  $val=$uploaded_files[$name];
   }

   if( !empty($val) && isset($v['basetype']) && $v['basetype'] == 'mfile' && function_exists('dnd_get_upload_dir') ){
      $dir=dnd_get_upload_dir(); 
     $f_arr=array();
      foreach($val as $file){
     $file_name=explode('/',$file);
     if(count($file_name)>1){
      $f_arr[]=$dir['upload_url'].'/'.$file_name[1];    
     }
      }
        
   $val=$f_arr;   
   }  
    if(!isset($uploaded_files[$name])){
     $val=wp_unslash($val);   
    }        
  $lead[$k]=$val;          
  }  
}
//var_dump($lead,$post_data); die('-----------');
$form_arr=array('id'=>'cf_'.$form_id,'name'=>$form_title,'fields'=>$tags);
$this->entry_created($lead,'0',$form_arr); 

}
public function create_entry_na($data){ 
        if(class_exists('vxcf_form')){ return; }
    $form_id=$data['form_id'];

    
if(empty($data['form_id'])){
    return;
}

$form_title=$data['settings']['title'];
$lead=$upload_files=array();
if(!empty($data['fields'])){
  foreach($data['fields'] as $v){
      $field_id=$v['id'];
     if(!empty($v['value'])){
         if($v['type'] == 'file_upload'){
        $upload_files[$field_id]=$v['value'];     
         }else{
         $lead[$field_id]=$v['value']; 
         }
     } 
  }
 
       if(is_array($upload_files)){
       foreach($upload_files as $k=>$v){
       $lead[$k]=$v;    
       } 
       }  
$form_arr=array('id'=>'na_'.$form_id,'name'=>$form_title,'fields'=>$data['fields']);
$this->entry_created($lead,'0',$form_arr);  
    
}
}

public function create_entry_wp($fields, $entry, $form_id, $form_data){
    if(class_exists('vxcf_form')){ return; }


$upload_files=$lead=array();
if(!empty($fields)){
    foreach($fields as $v){
if($v['type'] == 'file-upload'){
    $v['value']=array_map('trim',explode("\n",$v['value'])); 
  $upload_files[$v['id']]=$v['value'];  
}else{
$val=$v['value'];
if(in_array($v['type'],array('payment-select','payment-multiple'))){
 $val=$v['amount'];   
}else if($v['type'] == 'checkbox'){
  $val=array_map('trim',explode("\n",$val));     
}
$lead[$v['id']]=$val;
}    } 
 

       if(is_array($upload_files)){
       foreach($upload_files as $k=>$v){
       $lead[$k]=$v;    
       } }
         
$form_arr=array('id'=>'wp_'.$form_data['id'],'name'=>'WP Forms','fields'=>$form_data['fields']);
if(!empty($form_data['fields']['settings']['form_title'])){
    $form_arr['name']=$form_data['fields']['settings']['form_title'];
}
$this->entry_created($lead,'0',$form_arr);  
}
//var_dump($fields); die();
}
public function create_entry_el( $record){
   if(class_exists('vxcf_form')){ return; }
    $data=$record->get_formatted_data();
    $form_id_p=$this->post('form_id');
    $post_id_p=$this->post('post_id');
    
    $form_id=$form_id_p.'_'.$post_id_p;

    $fields=$this->get_form_fields('el_'.$form_id);
$upload_files=$lead=array();
if(!empty($fields)){
    foreach($fields as $v){
    if(isset($data[$v['label']])){    
$val=$data[$v['label']];
if($v['type'] == 'upload'){
  $upload_files[$v['id']]=$val;  
}else{

 if(in_array($v['type'],array('checkbox','multiselect'))){
  $val=array_map('trim',explode(',',$val));     
}
$lead[$v['id']]=$val;
}    } }
  
       if(is_array($upload_files)){
       foreach($upload_files as $k=>$v){
       $lead[$k]=$v;    
       } }
 //var_dump($lead,$data);  die();        
$form_arr=array('id'=>'el_'.$form_id,'name'=>'Elementor Forms','fields'=>$fields);
$this->entry_created($lead,'0',$form_arr);   

}
//var_dump($fields); die();
}
public function create_entry_fd($entry_id,$form_id){ 
    if(class_exists('vxcf_form')){ return; }

$fields=$this->get_form_fields('fd_'.$form_id);    
global $wpdb;
$table=$wpdb->prefix.'frm_item_metas';
$sql=$wpdb->prepare("Select * from $table where item_id=%d",$entry_id);
$entry=$wpdb->get_results($sql,ARRAY_A);
 $detail=array();
if(is_array($entry) && count($entry)>0){
    foreach($entry as $v){
   $detail[$v['field_id']]=$v['meta_value'];     
    }
} 
//var_dump($tags); die();
 $lead=array();
if(is_array($fields)){
    $uploaded_files_form=array();
  foreach($fields as $k=>$v){
      
      $name=$v['name'];
     if(isset($detail[$name])){
         $val=$detail[$name];
     if($v['type'] == 'file'){
          $val= wp_get_attachment_url($val) ;
             $base_url=get_site_url();
              $val=str_replace($base_url,trim(ABSPATH,'/'),$val);
    $uploaded_files_form[$name]=$val;   
     }     
  $lead[$name]=$detail[$name];          
     }
  }  
//

   if(is_array($uploaded_files_form)){
       foreach($uploaded_files_form as $k=>$v){
       $lead[$k]=$v;    
       }  
   } 
}
global $wpdb;
$table=$wpdb->prefix.'frm_forms';
$sql=$wpdb->prepare("Select name from $table where id=%d",$form_id);
$form_name=$wpdb->get_var($sql);
$form_arr=array('id'=>'fd_'.$form_id,'name'=>$form_name,'fields'=>$fields);
$this->entry_created($lead,'0',$form_arr);  
}
   /**
  * contact form entry created
  * 
  * @param mixed $entry
  * @param mixed $form
  */
  public function entry_created($entry,$entry_id,$form){

      self::$entry_created=true;
      
       if($this->do_actions()){ 
     do_action('vx_addons_save_entry',$entry_id,$entry,'cf',$form);
       }  

     $entry['__vx_id']=$entry_id; 
      $this->push($entry,$form,'submit',false);  
  } 
public function entry_created_crmperks($entry_id,$entry,$form){ 
    self::$entry_created=true;
       if($this->do_actions()){ 
     do_action('vx_addons_save_entry',$entry_id,$entry,'vf',$form);
       } 

$form['id']='vf_'.$form['id'];
$form['cfx_type']='vf';
$entry['__vx_id']=$entry_id;   
$this->push($entry,$form,'',false);    
}
  public function entry_created_contacts($entry,$entry_id,$form){

       if($this->do_actions()){ 
     do_action('vx_addons_save_entry',$entry_id,$entry,'cc',$form);
       }  

     $entry['__vx_id']=$entry_id; 
      $this->push($entry,$form,'',false);  
  } 
  public function entry_created_callcenter($entry,$entry_id,$form){ 
      $this->push($entry,$form,'',false); 
    }

  /**
  * Install Contact Form Notice
  * 
  */
  public function install_cf_notice(){
        $message=self::$cf_status_msg;
  if(!empty($message)){
  $this->display_msg('admin',$message,'gravity'); 
     $this->notice_js=true; 
  
  }
  }
   public function submit(){
       $entry='{"your-name":"Your Name (required)","your-email":"admin@localhost.com","your-subject":"subject test","your-message":"sdfsdfsdfsdf","your-country":["India"],"your-sports":["Tennis"],"your-fruit":["Banana"],"your-browser":"Safari","your-file":""}';
       $form='{"id":69,"name":"Contact form 1"}';
       $entry=json_decode($entry,true);
       $form=json_decode($form,true);
         apply_filters('vx_addons_save_entry',false,$entry_id,$entry,'cf',$form);   
         $entry['__vx_id']="1"; 
      $p=$this->push($entry,$form);  
  // var_dump($p); die();  
 } 

  /**
  * Install Contact Form Notice (plugin row)
  * 
  */
  public function install_cf_notice_plugin_row(){
  $message=self::$cf_status_msg;
  if(!empty($message)){
   $this->display_msg('',$message,'gravity');
  } 
  }
  /**
  * display admin notice
  * 
  * @param mixed $type
  * @param mixed $message
  * @param mixed $id
  */
  public function display_msg($type,$message,$id=""){
  //exp 
  global $wp_version;
  $ver=floatval($wp_version);
  if($type == "admin"){
     if($ver<4.2){
  ?>
    <div class="error vx_notice notice" data-id="<?php echo $id ?>"><p style="display: table"><span style="display: table-cell; width: 98%"><span class="dashicons dashicons-megaphone"></span> <b><?php esc_html_e('Contact Form Zendesk Plugin','contact-form-zendesk-crm') ?>. </b><?php echo wp_kses_post($message);?> </span>
<span style="display: table-cell; padding-left: 10px; vertical-align: middle;"><a href="#" class="notice-dismiss" title="<?php esc_html_e('Dismiss Notice','contact-form-zendesk-crm') ?>">dismiss</a></span> </p></div>
  <?php
     }else{
  ?>
  <div class="error vx_notice notice below-h2 is-dismissible" data-id="<?php echo $id ?>"><p><span class="dashicons dashicons-megaphone"></span> <b><?php esc_html_e('Contact Form Zendesk Plugin','contact-form-zendesk-crm') ?>. </b> <?php echo wp_kses_post($message);?> </p>
  </div>    
  <?php
     }
  }else{
  ?>
  <tr class="plugin-update-tr"><td colspan="5" class="plugin-update">
  <style type="text/css"> .vx_msg a{color: #fff; text-decoration: underline;} .vx_msg a:hover{color: #eee} </style>
  <div style="background-color: rgba(224, 224, 224, 0.5);  padding: 9px; margin: 0px 10px 10px 28px "><div style="background-color: #d54d21; padding: 5px 10px; color: #fff" class="vx_msg"> <span class="dashicons dashicons-info"></span> <?php echo wp_kses_post($message) ?>
</div></div></td></tr>
  <?php
  }   
  }
   /**
  * admin_screen_message function.
  * 
  * @param mixed $message
  * @param mixed $level
  */
  public  function screen_msg( $message, $level = 'updated') {
  echo '<div class="'. esc_attr( $level ) .' fade notice below-h2 is-dismissible"><p>';
  echo $message ;
  echo '</p></div>';
  } 


  /**
  * create tables and roles
  * 
  */
  public function install(){
      
  if(current_user_can( 'manage_options' )){
  self::$db_version=get_option($this->type."_version");
  if(self::$db_version != $this->version){
  $data=$this->get_data_object();
  $data->update_table();
  update_option($this->type."_version", $this->version);
  //add post permissions
  require_once(self::$path . "includes/install.php"); 
  $install=new vxcf_zendesk_install();
  $install->create_roles();   

  }

  } 
  }
/**
* Contact Form status
* 
*/
  public  function cf_status() {
  
  $installed = 0;
  if(!class_exists('vxcf_form')) {
  if(file_exists(WP_PLUGIN_DIR.'/contact-form-entries-pro/contact-form-entries-pro.php')) {
  $installed=2;   
  }
  }else{
  $installed=1;
  if(!$this->is_cf_supported()){
  $installed=3;   
  }      
  }
  if($installed !=1){
    if($installed === 0){ // not found
  $message = sprintf(__("%sContact Form Entries%s is required. %s it today!%s", 'contact-form-zendesk-crm'), "<a href='https://www.crmperks.com/'>", "</a>", "<a href='https://www.crmperks.com/'>", "</a>");   
  }else if($installed === 2){ // not active
  $message = sprintf(__('Contact Form Entries is installed but not active. %sActivate Contact Form Entries%s to use the Contact Form Zendesk Plugin','contact-form-zendesk-crm'), '<strong><a href="'.wp_nonce_url(admin_url('plugins.php?action=activate&plugin=contact-form-entries-pro/contact-form-entries-pro.php'), 'activate-plugin_contact-form-entries-pro/contact-form-entries-pro.php').'">', '</a></strong>');  
  } else if($installed === 3){ // not supported
  $message = sprintf(__("A higher version of %sContact Form Entries%s is required. %sPurchase it today!%s", 'contact-form-zendesk-crm'), "<a href='https://www.crmperks.com/'>", "</a>", "<a href='https://www.crmperks.com/'>", "</a>");
  }  
  self::$cf_status_msg=$message;
  }
  return $installed;   
  }

  
  /**
  * Returns true if the current page is an Feed pages. Returns false if not
  * 
  * @param mixed $page
  */
  public  function is_crm_page($page=""){
  if(empty($page)) {
  $page = $this->post("page");
  }
  return $page == $this->id;
  } 

    
    /**
  * form fields
  * 
  * @param mixed $form_id
  */
  public  function get_form_fields($form_id){
            $fields=array();
            
  $fields=apply_filters('vx_add_crm_form_fields',$fields,$form_id);
 if(empty($fields)){
      global $vxcf_form;

if(is_object($vxcf_form) && method_exists($vxcf_form,'get_form_fields')){  
    $fields=$vxcf_form->get_form_fields($form_id);   
}else{
    
$form_arr=explode('_',$form_id);
$type=$id='';
$fields = array();
if(isset($form_arr[0])){
$type=$form_arr[0];
}
if(isset($form_arr[1])){
$id=$form_arr[1];
}
switch($type){
    case'cf':
        if(method_exists('WPCF7_ShortcodeManager','get_instance') || method_exists('WPCF7_FormTagsManager','get_instance')){
$id=substr($form_id,3);
         $form_text=get_post_meta($id,'_form',true); 
         
if(method_exists('WPCF7_FormTagsManager','get_instance')){
    $manager=WPCF7_FormTagsManager::get_instance(); 
$contents=$manager->scan($form_text); 
$tags=$manager->get_scanned_tags();   

}else if(method_exists('WPCF7_ShortcodeManager','get_instance')){ //
 $manager = WPCF7_ShortcodeManager::get_instance();
$contents=$manager->do_shortcode($form_text);
$tags=$manager->get_scanned_tags();    
}

if(is_array($tags)){
  foreach($tags as $tag){
     if(is_object($tag)){ $tag=(array)$tag; }
   if(!empty($tag['name'])){
       $id=str_replace(' ','',$tag['name']);
       $tag['label']=ucwords(str_replace(array('-','_')," ",$tag['name']));
       $tag['type_']=$tag['type'];
       $tag['type']=$tag['basetype'];
       $tag['req']=strpos($tag['type'],'*') !==false ? 'true' : '';
           if($tag['type'] == 'select' && !empty($tag['options']) && array_search('multiple',$tag['options'])!== false){
          $tag['type']='multiselect'; 
       }
   $fields[$id]=$tag;    
   }   
  }  
}
    }
    break;
  case'na':
if(class_exists('Ninja_Forms')){

$form_fields = Ninja_Forms()->form( $id )->get_fields(); //var_dump($form_fields); die('----------');
foreach ($form_fields as $obj) {
$field=array();
if( is_object( $obj ) ) {
$field = $obj->get_settings();
$field['id']= $obj->get_id();
}

$arr=array('name'=>$field['id']);
 $type=$field['type']; 
 if($type == 'textbox'){ $type='text'; }
 if($type == 'starrating'){ $type='text'; }
 if($type == 'file_upload'){ $type='file'; }
 if(in_array($type,array('spam','confirm','submit','repeater','save','html','hr'))  ){ continue; } //|| !isset($field['required'])  // it is not set for hidden fields that is why removed it
  if($type == 'checkbox'){
 $arr['values']=array(array('text'=>$field['label'],'value'=>'1'));     
 }
 if(in_array($type,array('listmultiselect','listcheckbox','listradio','listselect'))){
     $type=ltrim($type,'list');
     $vals=array();
   if(!empty($field['options'])){
    foreach($field['options'] as $v){
  $vals[]=array('text'=>$v['label'],'value'=>$v['value']);      
    }   
   }
$arr['values']=$vals;     
 }

 $arr['type']=$type;
 $arr['label']=$field['label'];
$arr['req']=!empty($field['required']) ? 'true' : 'false';
 $fields[$field['id']]=$arr; 
 }     
}   
break;
case'el':
if(isset($form_arr[2])){
$post_id=$form_arr[2];
$forms=get_post_meta($post_id,'_elementor_data',true);
$forms=json_decode($forms,true);
if(!empty($forms)){
$form=self::find_el_form($forms,$id); 

if(!empty($form['form_fields'])){
  foreach($form['form_fields'] as $tag){ 
   if(!empty($tag['custom_id']) ){
       if(empty($tag['field_type'])){ $tag['field_type']=$tag['custom_id']; }
       if(!in_array($tag['field_type'],array('html','step','honeypot','recaptcha','recaptcha_v3'))){
       $field=array('id'=>$tag['custom_id']);
       $field['name']=$tag['custom_id'];
       $field['label']=$tag['field_label'];
       $field['type']=$tag['field_type'];
       $field['req']=!empty($tag['required']) ? 'true' : '';
  if(!empty($tag['allow_multiple']) ){
  $field['type']='multiselect';   
  }
  if($field['type'] == 'acceptance'){ 
      $field['type']='checkbox';
      $field['values']=array(array('label'=>$tag['acceptance_text'],'value'=>'on'));
  }
  if($field['type'] == 'upload'){
      $field['type']='file';
  }
if(!empty($tag['field_options'])){
$opts_array=explode("\n",$tag['field_options']);
$ops=array();
foreach($opts_array as $v){
$v_arr=explode('|',$v); 
if(!isset($v_arr[1])){ $v_arr[1]=$v_arr[0]; }
$ops[]=array('label'=>$v_arr[0],'value'=>$v_arr[1]);  
}
$field['values']=$ops;  
   }
   $fields[$tag['custom_id']]=$field;    
   }   }
  }  
} 
}

}
break;
case'fd':
global $wpdb;
$table=$wpdb->prefix.'frm_fields';
$sql=$wpdb->prepare("Select * from $table where form_id=%d",$id);
$fields_arr=$wpdb->get_results($sql,ARRAY_A);
if(count($fields_arr)>0){
    foreach($fields_arr as $field){
        $field['label']=$field['name'];
        $field['name']=$field['id'];
        if(!empty($field['options'])){
           $field['values']=maybe_unserialize($field['options']); 
        }
        $fields[$field['id']]=$field;
    }
}
break;
case'wp':
if(function_exists('wpforms') && method_exists(wpforms()->form,'get')){
$forms_arr=wpforms()->form->get( $id ); 
if(!empty($forms_arr)){
$form=json_decode($forms_arr->post_content,true);
$fields=array();
foreach($form['fields'] as $v){ 
    $type=$v['type'];
    if($type == 'name'){ $type='text'; }
    if($type == 'payment-select'){ $type='select'; }
    if($type == 'payment-multiple'){ $type='radio'; }
    if($type == 'payment-single'){ $type='text'; }
    if($type == 'file-upload'){ $type='file'; }
    if($type == 'date-time'){ $type='date'; }
    if($type == 'address'){ $type='textarea'; }
    if($type == 'phone'){ $type='tel'; }
$label=isset($v['label']) ? $v['label'] : $type;
  //  if(in_array($type,array('text','textarea','email','number','hidden','select','checkbox','radio','url','password','tel','date','file','number-slider'))){
          $field=array('id'=>$v['id'],'name'=>$v['id'],'label'=>$label,'type'=>$type); 
  $field['req']=!empty($v['required']) ? true : false; 
        if(in_array($type,array('radio','checkbox','select'))){
        $is_val=false;
        if(in_array($v['type'],array('payment-select','payment-multiple'))){ $is_val=true; }
    $choices=array();
    if(!empty($v['choices'])){
     foreach($v['choices'] as $c){
         $c_val=$is_val ? $c['value'] : $c['label'];
     $choices[]=array('text'=>$c['label'],'value'=>$c_val);    
     }   
    }   
  $field['values']=$choices;   
        }
        $fields[$v['id']]=$field; 
  //  }
    
}
} } //var_dump($form['fields']);
break;  
    }
}    
           } 
  return $fields;


  }
 public static function find_el_form($var,$key=''){

if(is_array($var) && isset($var[0]) ){        
    foreach($var as $v){
     if (!empty($v['elements']) &&  is_array( $v['elements'] ) ) {
  $se=self::find_el_form($v['elements'],$key);
  if(!empty($se)){ return $se; }
    } 
         if($v['id'] == $key){  // var_dump($v);   echo '----<hr>';
          return  $v['settings'];
        } 
    }
    
} 
}
public static function find_el_forms($var,&$forms=array()){

if(is_array($var) && isset($var[0]) ){        
    foreach($var as $v){ 
    if(isset($v['widgetType']) && $v['widgetType'] == 'form'){  
          $forms[]= $v;  
        }  
     if (!empty($v['elements']) &&  is_array( $v['elements'] ) ) { 
  self::find_el_forms($v['elements'],$forms); 
    } 
         
    }
    
}
return $forms; 
}

  public function get_form($form_id){
      
      $title='Contacts Addon';
      if($form_id !='vx_contacts'){
      global $vxcf_form;
      if(method_exists($vxcf_form,'get_forms')){
          $forms=$vxcf_form->get_forms();
          if(is_array($forms) && count($forms)>0){
              foreach($forms as $key=>$vals){
             $found=false;
               if(!empty($vals['forms']) && is_array($vals['forms'])){
                   foreach($vals['forms'] as $k=>$v){
                    $f_id=$key.'_'.$k;
                    if($f_id == $form_id){
                        $found=true;  
                        if(!empty($v)){
                     $title=$v;  
                        }
                      break;  
                    }   
                   }
               }   
              if($found){
                  break;
              }
              }
          }
      }
      }
        $form=array('id'=>$form_id,'title'=>$title,'name'=>$title);

  return $form;
  }
   

  /**
  * settings link
  * 
  * @param mixed $escaped
  */
  public  function link_to_settings( $tab='' ) {
  $q=array('page'=>$this->id);
  if(!empty($tab)){
   $q['tab']=$tab;   
  }
  $url = admin_url('admin.php?'.http_build_query($q));
  
  return  $url;
  }


    /**
  * Get CRM info
  * 
  */
  public function get_info($id){
$data=$this->get_data_object();
      $info=$data->get_account($id);
 $data=array();  $meta=$info_arr=array(); 
if(is_array($info)){
if(!empty($info['data'])){ 
        $info['data']=trim($info['data']);  
    if(strpos($info['data'],'{') !== 0){
        $info['data']=$this->de_crypt($info['data']);
    } 
  $info_arr=json_decode($info['data'],true);     
if(!is_array($info_arr)){
    $info_arr=array();
}
}

$info_arr['time']=$info['time']; 
$info_arr['id']=$info['id']; 
$info['data']=$info_arr; 
if(!empty($info['meta'])){ 
  $meta=json_decode($info['meta'],true); 
}
$info['meta']=is_array($meta) ? $meta : array();   
 
}
  return $info;    
  }
  /**
  * update account
  * 
  * @param mixed $data
  * @param mixed $id
  */
  public function update_info($data,$id) {

if(empty($id)){
    return;
}

 $time = current_time( 'mysql' ,1);

  $sql=array('updated'=>$time);
  if(is_array($data)){

  
    if(isset($data['meta'])){
  $sql['meta']= json_encode($data['meta']);    
  }
  if( isset($data['data']) && is_array($data['data'])){
      $_data=$this->get_data_object();
     $acount=$_data->get_account($id);
       if(array_key_exists('time' , $data['data']) && empty($data['data']['time'])){
  $sql['time']= $time;    
  $sql['status']= '2';    
  } 
  if(isset($data['data']['class'])){
  $sql['status']= $data['data']['class'] == 'updated' ? '1' : '2'; 
  }
  if(isset($data['data']['meta'])){
      unset($data['data']['meta']);
  }
  if(isset($data['data']['status'])){
      unset($data['data']['status']);
  }
  if(isset($data['data']['name'])){
     $sql['name']=$data['data']['name']; 
     // unset($data['data']['name']);
  }else if(isset($_GET['id'])){
       $sql['name']="Account #".$this->post('id'); 
  }
  
    $enc_str=json_encode($data['data']);
  //$enc_str=$this->en_crypt($enc_str);
  $sql['data']=$enc_str;
  }
  } 

 $data=$this->get_data_object();
$result = $data->update_info_data($sql,$id);

  
return $result;
}

  /**
  * contact form field values, modify check boxes etc
  * 
  * @param mixed $entry
  * @param mixed $form
  * @param mixed $gf_field_id
  * @param mixed $crm_field_id
  * @param mixed $custom
  */
  public  function verify_field_val($entry,$field_id,$sf_id=''){
  $value=false;
 
  if(isset($entry[$field_id])){
      $value=$entry[$field_id];
     if(is_array($value) && isset($value['value'])){
      $value=$value['value'];   
     }
     if(!is_array($value)){
          $value=maybe_unserialize($value);
     }
  
  }
 $fields=$this->form_fields;  
 $type=isset($fields[$field_id]['type']) ? $fields[$field_id]['type'] : '';
if( $type == 'file' && !empty($value)){
    if(class_exists('vxcf_form')){
$upload=vxcf_form::get_upload_dir(); 
$temp_files=array();
      if(!is_array($value)){ $value=array($value); }
foreach($value as $f){
     if(filter_var($f,FILTER_VALIDATE_URL) === false){
      if(strpos($sf_id,'vx_list_files') !== false){
       $f=$upload['dir'].$f;   
      }else{   
    $f=$upload['url'].$f; //url , dir
     } }
  $temp_files[]=$f;   
}  $value=$temp_files;   
    }
$value=trim(implode(',',$value));
 }else if( in_array($type, array('select','radio')) && is_array($value) ){
   $value=trim(implode(' ',$value));  
 }else if($type == 'checkbox' && is_array($value) && count($value) == 1){
   $value=trim(implode(' ',$value));   
 }
 
  return $value;        
  }
  /**
  * filter enteries
  * 
  * @param mixed $feed
  * @param mixed $entry
  * @param mixed $form
  */
  public  function check_filter($feed,$entry){
    $filters=$this->post('filters',$feed);
  $final=$this->filter_condition=null;
  if(is_array($filters)){
   $time=current_time('timestamp'); 
   foreach($filters as $filter_s){
  $check=null; $and=null;  
  if(is_array($filter_s)){
  foreach($filter_s as $filter){
  $field=$filter['field'];
  $fval=$filter['value'];
  $val=$this->verify_field_val($entry,$field);
     if(is_array($val)){ $val=trim(implode(' ',$val)); }
  switch($filter['op']){
  case"is": $check=$fval == $val;     break;
  case"is_not": $check=$fval != $val;     break;
  case"contains": $check=strpos($val,$fval) !==false;     break;
  case"not_contains": $check=strpos($val,$fval) ===false;     break;
  case"is_in": $check=strpos($fval,$val) !==false;     break;
  case"not_in": $check=strpos($fval,$val) ===false;     break;
  case"starts": $check=strpos($val,$fval) === 0;     break;
  case"not_starts": $check=strpos($val,$fval) !== 0;     break;
  case"ends": $check=(strpos($val,$fval)+strlen($fval)) == strlen($val);  break;
  case"not_ends": $check=(strpos($val,$fval)+strlen($fval)) != strlen($val);  break;
  case"less": $check=(float)$val<(float)$fval; break;
  case"greater": $check=(float)$val>(float)$fval;  break;
  case"less_date": $check=strtotime($val,$time) < strtotime($fval,$time);  break;
  case"greater_date": $check=strtotime($val,$time) > strtotime($fval,$time);  break;
  case"equal_date": $check=strtotime($val,$time) == strtotime($fval,$time);  break;
  case"empty": $check=$val == "";  break;
  case"not_empty": $check=$val != "";  break;
  }
  $and_c[]=array("check"=>$check,"field_val"=>$fval,"input"=>$val,"field"=>$field,"op"=>$filter['op']);
  if($check !== null){
  if($and !== null){
  $and=$and && $check;    
  }else{
  $and=$check;    
  }   
  }  
  } //end and loop filter
  }
  if($and !== null){
  if($final !== null){
  $final=$final || $and;  
  }else{
  $final=$and;
  }    
  }
    $this->filter_condition[]=$and_c;
  } // end or loop
  }
  return $final === null ? true : $final;
  }
      /**
  * Logs page row
  * 
  * @param mixed $feed
  */
  public  function verify_log($feed){
  $link="N/A"; $desc=__("Added to ",'contact-form-zendesk-crm');
    $status_imgs=array("1"=>"created","2"=>"updated","4"=>"filtered",'5'=>'deleted');
    $feed['status_img']=isset($status_imgs[$feed["status"]]) ? $status_imgs[$feed["status"]] : 'failed';
  if(!empty($feed['crm_id'])&& !empty($feed['object']) && !empty($feed['status'])){
      $link=$feed['crm_id'];
  if($feed['link'] !=""){
  $link='<a href="'.$feed['link'].'" title="'.$feed['crm_id'].'" target="_blank">'.$feed['crm_id'].'</a>';

  }  
  if($feed['status'] == 2){
  $desc="Updated to ";    
  }
  $desc.=$feed['object'];
  if($feed['status'] == 5){
   $desc=sprintf(__("%s Deleted",'contact-form-zendesk-crm'),$feed['object']);   
  }
  }else if($feed['status'] == 4){
      $desc=sprintf(__("%s Filtered",'contact-form-zendesk-crm'),$feed['object']); 
  }else{
  $desc= !empty($feed['error']) ? $feed['error'] : esc_html__("Unknown Error",'contact-form-zendesk-crm');
  }
  $title=__("Failed",'contact-form-zendesk-crm');   
  if($feed['status'] == 1){
  $title=__("Created",'contact-form-zendesk-crm');   
  }else if($feed['status'] == 2){
  $title=__("Updated",'contact-form-zendesk-crm');   
  }else if($feed['status'] == 4){
  $title=__("Filtered",'contact-form-zendesk-crm');   
  }else if($feed['status'] == 5){
  $title=__("Deleted",'contact-form-zendesk-crm');   
  }
  $feed['_crm_id']= !empty($feed['crm_id']) ? $feed['crm_id'] : "N/A";
  $feed['a_link']=$link;
  $feed['desc']=$desc;
  $feed['title']=$title;
  return $feed;
  }   
  /**
  * get address components
  *  
  * @param mixed $entry
  * @param mixed $field_id
  * @param mixed $type
  */
  private  function get_address($entry, $field_id,$type=""){
  $street_value = str_replace("  ", " ", trim($entry[$field_id . ".1"]));
  $street2_value = str_replace("  ", " ", trim($entry[$field_id . ".2"]));
  $city_value = str_replace("  ", " ", trim($entry[$field_id . ".3"]));
  $state_value = str_replace("  ", " ", trim($entry[$field_id . ".4"]));
  $zip_value = trim($entry[$field_id . ".5"]);
  if(method_exists('GF_Field_Address','get_country_code')){
  $field_c=new GF_Field_Address();
  $country_value=$field_c->get_country_code(trim($entry[$field_id . ".6"]));
  }else{
  $country_value = GFCommon::get_country_code(trim($entry[$field_id . ".6"]));       
  }
  $country =trim($entry[$field_id . ".6"]);
  $address = $street_value;
  $address .= !empty($address) && !empty($street2_value) ? "  $street2_value" : $street2_value;
  if($type =="json"){
  $arr=array("street"=>$address,"city"=>$city_value,"state"=>$state_value,"zip"=>$zip_value,"country"=>$country);
  return json_encode($arr);
  }
  $address .= !empty($address) && (!empty($city_value) || !empty($state_value)) ? "  $city_value" : $city_value;
  $address .= !empty($address) && !empty($city_value) && !empty($state_value) ? "  $state_value" : $state_value;
  $address .= !empty($address) && !empty($zip_value) ? "  $zip_value" : $zip_value;
  $address .= !empty($address) && !empty($country_value) ? "  $country_value" : $country_value;
  
  return $address;
  }

  /**
  * Add checkbox to entry info - option to send entry to crm
  * 
  * @param mixed $form_id
  * @param mixed $lead
  */
  public  function entry_info_send_checkbox( $form_id, $lead ) {
  
  // If this entry's form isn't connected to crm, don't show the checkbox
  if(!$this->show_send_to_crm_button() ) { return; }
  
  // If this is not the Edit screen, get outta here.
  if(empty($_POST["screen_mode"]) || $_POST["screen_mode"] === 'view') { return; }
  
   if(!current_user_can($this->id."_send_to_crm")){return; }
  
  if( apply_filters( $this->id.'_show_manual_export_button', true ) ) {
  printf('<input type="checkbox" name="'.esc_attr($this->id).'_update" id="'.esc_attr($this->id).'_update" value="1" /><label for="'.esc_attr($this->id).'_update" title="%s">%s</label><br /><br />', esc_html__('Create or update this entry in Zendesk. The fields will be mapped according to the form feed settings.', 'contact-form-zendesk-crm'), esc_html__('Send to Zendesk', 'contact-form-zendesk-crm'));
  } else {
  echo '<input type="hidden" name="'.esc_attr($this->id).'_update" id="'.esc_attr($this->id).'_update" value="1" />';
  }
  }
  /**
  * Add button to entry info - option to send entry to crm
  * 
  * @param mixed $button
  */
  public  function entry_info_send_button( $button = '' ) {
  // If this entry's form isn't connected to crm, don't show the button
  if(!$this->show_send_to_crm_button()) { return $button; }
if(!current_user_can($this->id."_send_to_crm")){return; }
  // Is this the view or the edit screen?
  $mode = empty($_POST["screen_mode"]) ? "view" : $this->post("screen_mode");
  if($mode === 'view') {
            $margin="";
      if(defined("vx_btn")){
      $margin="margin-top: 5px;";    
      }else{define('vx_btn','true');}
  $button.= '<input type="submit" class="button button-large button-secondary alignright" name="'.esc_attr($this->id).'_send" style="margin-left:5px; '.$margin.'" title="'.__('Create or update this entry in Zendesk. The fields will be mapped according to the form feed settings.','contact-form-zendesk-crm').'" value="'.__('Send to Zendesk', 'contact-form-zendesk-crm').'" onclick="jQuery(\'#action\').val(\'send_to_crm\')" />';
  //logs button

      $entry_id=$this->post('lid');
      $form_id = rgget('id');
      if(empty($entry_id)){
          $entry_id=$this->get_entry_id($form_id);
      }
      $id=$this->post('id');
      $log_url=admin_url( 'admin.php?page=gf_edit_forms&view=settings&subview='.$this->id.'&tab=log&id='.$id.'&entry_id='.$entry_id);  
    $button.= '<a class="button button-large button-secondary alignright" style="margin-left:5px; margin-top:5px; " title="'.__('Go to Zendesk Logs','contact-form-zendesk-crm').'" href="'.esc_url($log_url).'">'.__('Zendesk Logs','contact-form-zendesk-crm').'</a>';
  
  } 
  return $button;
  }
  /**
  * Whether to show the Entry "Send to CRM" button or not
  *
  * If the entry's form has been mapped to CRM feed, show the Send to CRM button. Otherwise, don't.
  *
  * @return boolean True: Show the button; False: don't show the button.
  */
  private  function show_send_to_crm_button() {
  
  $form_id = rgget('id');
  
  return $this->has_feed($form_id);
  }
  /**
  * Does the current form have a feed assigned to it?
  * @param  INT      $form_id Form ID
  * @return boolean
  */
  function has_feed($form_id) {
  $data=$this->get_data_object();
  $feeds = $data->get_feed_by_form( $form_id , true);
  
  return !empty($feeds);
  }
  

  
  /**
  * if contact form installed and supported
  * 
  */
  private  function is_cf_supported(){
  if(class_exists("vxcf_form")){
 global $vxcf_form;
 $version='1.0';
 if($vxcf_form->version){
  $version=$vxcf_form->version;   
 }
  $is_correct_version = version_compare($version, $this->min_cf_version, ">=");
  return $is_correct_version;
  }
  else{
  return false;
  }
  }
  /**
  * uninstall plugin
  * 
  */
  public  function uninstall(){
  //droping all tables
 require_once(self::$path . "includes/install.php"); 
  $install=new vxcf_zendesk_install();
    do_action('uninstall_vx_plugin_'.$install->id);
  $install->remove_data();
  $install->remove_roles();
  }
    /**
  * email validation
  * 
  * @param mixed $email
  */
  public function is_valid_email($email){
         if(function_exists('filter_var')){
      if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      return true;    
      }
       }else{
       if(strpos($email,"@")>1){
      return true;       
       }    
       }
   return false;    
  }
  /**
  * deactivate
  * 
  * @param mixed $action
  */
  public function deactivate($action="deactivate"){ 
  do_action('plugin_status_'.$this->type,$action);
  }
  /**
  * activate plugin
  * 
  */
  public function activate(){ 
$this->plugin_api(true);
do_action('plugin_status_'.$this->type,'activate');  
  }
    /**
  * Send CURL Request
  * 
  * @param mixed $body
  * @param mixed $path
  * @param mixed $method
  */
  public function request($path="",$method='POST',$body="",$head=array()) {

        $args = array(
            'body' => $body,
            'headers'=> $head,
            'method' => strtoupper($method), // GET, POST, PUT, DELETE, etc.
            'sslverify' => false,
            'timeout' => 20,
        );

       $response = wp_remote_request($path, $args);

        if(is_wp_error($response)) { 
            $this->error_msg= $response->get_error_message();
            return false;
        }
   $result=wp_remote_retrieve_body($response);
        return $result;
    }

  /**
  * Formates User Informations and submitted form to string
  * This string is sent to email and zendesk
  * @param  array $info User informations 
  * @param  bool $is_html If HTML needed or not 
  * @return string formated string
  */
  public  function format_user_info($info,$is_html=false){
  $str=""; $file="";
  if($is_html){
      self::$path=$this->get_base_path();
  if(file_exists(self::$path."templates/email.php")){    
  ob_start();
  include_once(self::$path."templates/email.php");
  $file= ob_get_contents(); // data is now in here
  ob_end_clean();
  } 
  if(trim($file) == "")
  $is_html=false;
  }
  if(isset($info['info']) && is_array($info['info'])){
  if($is_html){
  if(isset($info['info_title'])){
  $str.='<tr><td style="font-family: Helvetica, Arial, sans-serif;background-color: #C35050; height: 36px; color: #fff; font-size: 24px; padding: 0px 10px">'.$info['info_title'].'</td></tr>'."\n";
  }
  if(is_array($info['info']) && count($info['info'])>0){
  $str.='<tr><td style="padding: 10px;"><table border="0" cellpadding="0" cellspacing="0" width="100%;"><tbody>';      
  foreach($info['info'] as $f_k=>$f_val){
  $str.='<tr><td style="padding-top: 10px;color: #303030;font-family: Helvetica;font-size: 13px;line-height: 150%;text-align: right; font-weight: bold; width: 28%; padding-right: 10px;">'.$f_k.'</td><td style="padding-top: 10px;color: #303030;font-family: Helvetica;font-size: 13px;line-height: 150%;text-align: left; word-break:break-all;">'.$f_val.'</td></tr>'."\n";      
  }
  $str.="</table></td></tr>";             
  }
  }else{
  if(isset($info['title']))
  $str.="\n".$info['title']."\n";    
  foreach($info['info'] as $f_k=>$f_val){
  $str.=$f_k." : ".$f_val."\n";      
  }
  }
  }
  if($is_html){
  $str=str_replace(array("{title}","{msg}","{sf_contents}"),array($info['title'],$info['msg'],$str),$file);
  }
  return $str;   
  }
 

  /**
  * if plugin user is valid
  * 
  * @param mixed $update
  */
  
  public function is_valid_user($update){
  return is_array($update) && isset($update['user']['user']) && $update['user']['user']!=""&& isset($update['user']['expires']);
  }     
/**
  * Get variable from array
  *  
  * @param mixed $key
  * @param mixed $arr
  */
public static function post($key, $arr="") {
  if($arr!=""){
  return isset($arr[$key])  ? $arr[$key] : "";
  }
  return isset($_REQUEST[$key]) ? self::clean($_REQUEST[$key]) : "";
}
public static function clean($var,$key=''){
    if ( is_array( $var ) ) {
$a=array();
    foreach($var as $k=>$v){
  $a[$k]=self::clean($v,$k);    
    }
  return $a;  
    }else {
     $var=wp_unslash($var);  
  if(in_array($key,array('note_val','value'))){
 $var=sanitize_textarea_field($var);    
  }else{
  $var=sanitize_text_field($var);    
  }      
return  $var;
    }
}
  /**
  * Get WP Encryption key
  * @return string Encryption key
  */
  public static  function get_key(){
  $k='Wezj%+l-x.4fNzx%hJ]FORKT5Ay1w,iczS=DZrp~H+ve2@1YnS;;g?_VTTWX~-|t';
  if(defined('AUTH_KEY')){
  $k=AUTH_KEY;
  }
  return substr($k,0,30);        
  }
  /**
  * check if other version of this plugin exists
  * 
  */
  public function other_plugin_version(){ 
  $status=0;
  if(class_exists('vxcf_zendesk_wp')){
      $status=1;
  }else if( file_exists(WP_PLUGIN_DIR.'/contact-form-zendesk-crm/contact-form-zendesk-crm.php')) {
  $status=2;
  } 
  return $status;
  }
    /**
  * Get time Offset 
  * 
  */
  public function time_offset(){
 $offset = (int) get_option('gmt_offset');
  return $offset*3600;
  } 
  /**
  * Decrypts Values
  * @param array $info Zendesk encrypted API info 
  * @return array API settings
  */
  public static function de_crypt($info){
  $info=trim($info);
  if($info == "")
  return '';
  $str=base64_decode($info);
  $key=self::get_key();
      $decrypted_string='';
     if(function_exists("openssl_encrypt") && strpos($str,':')!==false ) {
$method='AES-256-CBC';
$arr = explode(':', $str);
 if(isset($arr[1]) && $arr[1]!=""){
 $decrypted_string=openssl_decrypt($arr[0],$method,$key,false, base64_decode($arr[1]));     
 }
 }else{
     $decrypted_string=$str;
 }
  return $decrypted_string;
  }   
  /**
  * Encrypts Values
  * @param  string $str 
  * @return string Encrypted Value
  */
  public static function en_crypt($str){
  $str=trim($str);
  if($str == "")
  return '';
  $key=self::get_key();
if(function_exists("openssl_encrypt")) {
$method='AES-256-CBC';
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
$enc_str=openssl_encrypt($str,$method, $key,false,$iv);
$enc_str.=":".base64_encode($iv);
  }else{
      $enc_str=$str;
  }
  $enc_str=base64_encode($enc_str);
  return $enc_str;
  }
  
  /**
  * Get variable from array
  *  
  * @param mixed $key
  * @param mixed $key2
  * @param mixed $arr
  */
  public static function post2($key,$key2, $arr="") {
  if(is_array($arr)){
  return isset($arr[$key][$key2])  ? $arr[$key][$key2] : "";
  }
  return isset($_REQUEST[$key][$key2]) ? self::clean($_REQUEST[$key][$key2]) : "";
  }
  /**
  * Get variable from array
  *  
  * @param mixed $key
  * @param mixed $key2
  * @param mixed $arr
  */
  public static function post3($key,$key2,$key3, $arr="") {
  if(is_array($arr)){
  return isset($arr[$key][$key2][$key3])  ? $arr[$key][$key2][$key3] : "";
  }
  return isset($_REQUEST[$key][$key2][$key3]) ? self::clean($_REQUEST[$key][$key2][$key3]) : "";
  }
  /**
  * get base url
  * 
  */
  public function get_base_url(){
  return plugin_dir_url(__FILE__);
  }
    /**
  * get plugin direcotry name
  * 
  */
  public function plugin_dir_name(){
  if(!empty($this->plugin_dir)){
  return $this->plugin_dir;
  }
  if(empty(self::$path)){
  self::$path=$this->get_base_path(); 
  }
  $this->plugin_dir=basename(self::$path);
  return $this->plugin_dir;
  }
  /**
  * get plugin slug
  *  
  */
  public function get_slug(){
  return plugin_basename(__FILE__);
  }
public function do_actions(){
     if(!is_object(self::$plugin) ){ $this->plugin_api(); }
      if(is_object(self::$plugin) && method_exists(self::$plugin,'valid_addons')){
       return self::$plugin->valid_addons();  
      }
    
   return false;   
  }
  /**
  * Returns the physical path of the plugin's root folder
  * 
  */
  public function get_base_path(){
  return plugin_dir_path(__FILE__);
  }

    /**
  * get api object
  * 
  * @param mixed $settings
  * @return vxg_api_zendesk
  */
  public  function get_api($crm=""){
   $api = false;
  $api_class=$this->id."_api";
  if(!class_exists($api_class))
  require_once(self::$path."api/api.php");
  
  $api = new $api_class($crm);
  return $api;
  }
  /**
  * get contact form entry
  * 
  */
  public function get_cf_entry($entry_id,$type=''){
      $entry=array();
      
  if($type == 'addon'){

  if( class_exists( 'vxcf_crm' ) && !empty( $entry_id ) ) {
  $entry = vxcf_crm::get_entry( $entry_id );
  $entry['__vx_id']=$entry_id;
  $entry['form_id']='vx_contacts';
  }   
  }
  else{
  if( class_exists( 'vxcf_form' ) && !empty( $entry_id ) ) {
  $entry = vxcf_form::get_entry( $entry_id );
  $entry['__vx_id']=$entry_id;
  } 
  }
  return $entry;
  }
   /**
  * get contact form entry
  * 
  */
  public function get_cf_entry_detail($entry_id){
      $entry=array();
  // Fetch entry (use new GF API from version 1.8)
  if( class_exists( 'vxcf_form' ) && !empty( $entry_id ) ) {
  $entry = vxcf_form::get_entry_detail( $entry_id );
  } 
  return $entry;
  }
    /**
  * get data object
  * 
  */
  public function get_data_object(){
  require_once(self::$path . "includes/data.php");     
  if(!is_object($this->data))
  $this->data=new vxcf_zendesk_data();
  return $this->data;
  }


  /**
  * push form data to crm
  * 
  * @param mixed $entry
  * @param mixed $form
  * @param mixed $is_admin
  */
  public  function push($entry, $form,$event="",$is_admin=true,$log=""){  

     $data_db=$this->get_data_object(); 
     $log_id='';   $feeds_meta=array();
$form_id=$form['id'];
//if(!class_exists('vxcf_form')){
 //   $form_id='cf_'.$form_id;
//}
   if(!empty($log)){
       if(isset($log['id'])){
       $log_id=$log['id'];
       }
       $log_feed=$data_db->get_feed($log['feed_id']);
       
   if(!empty($log_feed)){
       $feeds_meta=array($log_feed);
   }
   }else{   
  //get feeds of a form 
  $feeds=$data_db->get_feed_by_form($form_id,true);

  if(is_array($feeds) && count($feeds)>0){
  $e=1000; $k=1;
    foreach($feeds as $feed){
          $data=isset($feed['data']) ? json_decode($feed['data'],true) : array(); 
             $meta=isset($feed['meta']) ? json_decode($feed['meta'],true) : array();
           $feed['meta']=$meta;
           $feed['data']=$data;
 if(!empty($data['deal_check']) || !empty($data['person_check']) || !empty($data['company_check'])){
     $feeds_meta[$e++]=$feed; 
  }else{
  $feeds_meta[$k++]=$feed; 
  }
    }  
      ksort($feeds_meta); 
  }

   } 

$entry_id=$this->post('__vx_id',$entry);

$type='cf';
if($form['id'] == 'vx_contacts'){
$type='cc';    
}
  
if(empty($form['__vx_addons']) && ($event == '' || $event == 'update' || $event == 'submit')){
$entry=apply_filters('vx_crm_post_fields',$entry,$entry_id,$type,$form);
} 

$screen_msg_class="updated"; $notice=""; $log_link=''; $mark_read_id='';
  if(is_array($feeds_meta) && count($feeds_meta)>0){
  foreach($feeds_meta as $feed){
        $temp=array();
  $force_send=false;
      $post_comment=true;
      $screen_msg="";
      $parent_id=0;
      if(isset($entry['__vx_parent_id'])){
  $parent_id=$entry['__vx_parent_id'];  
}
  $object=$this->post('object',$feed);
  if(empty($object)){
      continue;
  } 
  //
    $data=$meta=array();

  if(is_array($feed)){
  if(isset($feed['data']) && is_array($feed['data'])){
      $data=$feed['data'];
    $feed=array_merge($feed,$data);  
  }
 //
   if(isset($feed['meta']) && is_array($feed['meta'])){
       $meta=$feed['meta'];
    $feed=array_merge($feed,$meta);  
  }     
  }

if( in_array($event,array('restore','update','delete','add_note','delete_note'))){
$is_admin=true;
$search_object=$object;
if(in_array($event,array('add_note','delete_note')) && !empty($log)){
   
   if($event == 'add_note'){
        $note=json_decode($log['data'],true);
        if(!empty($note['title']['value'])){
            self::$note=array('id'=>$log['parent_id']);
      self::$note['title']=$note['title']['value'];
      self::$note['body']=$note['body']['value'];
        }
   } 
}
   if($event == 'delete_note' && !empty(self::$note)){
         $parent_id=self::$note['id'];
   }
 
    if(in_array($event,array('delete_note','add_note'))){
        //check feed
    $order_notes=$this->post('entry_notes',$feed); //if notes sync not enabled in feed return

    if( empty($order_notes)){
        continue;
    }
         //change main object to Note
         $feed['related_object']=$object;
        $object=$feed['object']='Note';   
 }
 if($event == 'delete_note'){
//when deleting note search note object 
     $search_object='Note';
 }
 $_data=$this->get_data_object();
$feed_log=$_data->get_feed_log($feed['id'],$entry_id,$search_object,$parent_id); 
//var_dump($feed_log); die();
 if($event == 'restore' && $feed_log['status'] != 5) { // only allow successfully deleted records
     continue;
 }
  if( in_array($event,array('update','delete') ) && !in_array($feed_log['status'],array(1,2) )  ){ // only allow successfully sent records
     continue;
 }
if(empty($feed_log['crm_id']) || empty($feed_log['object']) || $feed_log['object'] != $search_object){
    
   continue; 
}
if($event !='restore'){
 $feed['crm_id']=$feed_log['crm_id'];
    unset($feed['primary_key']);
}
   $feed['event']=$event;  
// add note and save related extra info
 if( $event == 'add_note' && !empty(self::$note)){
    $temp=array('title'=>array('value'=>self::$note['title']),'body'=>array('value'=>self::$note['body']),'parent_id'=>array('value'=> $feed['crm_id']),'object'=>array('value'=> $search_object));  
$parent_id=self::$note['id']; 
 $feed['note_object_link']='<a href="'.$feed_log['link'].'" target="_blank">'.$feed_log['crm_id'].'</a>';
 } 
 // delete not and save extra info
 if( $event == 'delete_note'){
     
     $feed_log_arr= json_decode($feed_log['extra'],true);
     if(isset($feed_log_arr['note_object_link'])){
         $feed['note_object_link']=$feed_log_arr['note_object_link'];
     }
$temp=array('ParentId'=>array('value'=> $feed['crm_id']));   
 }
 //delete object
 if( $event == 'delete'){
    $temp=array('Id'=>array('value'=> $feed['crm_id']));     
 }
//
  if(!in_array($event , array('update','restore') )){ 
     //do not apply filters when adding note , deleting note , entry etc
      $force_send=true;   
  }  
        //do not post comment in al other cases 
     $post_comment=false; 

 } 
// var_dump(self::$note,$object,$feed['note_object'],$feed['object'],$feed['crm_id'],$feed['event'],$temp,$force_send); 

if(isset($entry['__vx_data'])){
$force_send=true;  
$temp=$entry['__vx_data'];  
}
if(!$force_send && isset($data['map']) && is_array($data['map']) && count($data['map'])>0){

$custom= isset($meta['fields']) && is_array($meta['fields']) ? $meta['fields'] : array();

     if(empty($this->form_fields)){
  $this->form_fields=$this->get_form_fields($form['id']);
 }
  foreach($data['map'] as $k=>$v){ 
  $value=false;  
  if(!empty($v)){ //if value not empty
      if($this->post('type',$v) == "value"){ //custom value
      $value=trim($this->post('value',$v));  
  //starts with { and ends } , any char in brackets except {
  preg_match_all('/\{[^\{]+\}/',$value,$matches);
  if(!empty($matches[0])){
      $vals=array();
   foreach($matches[0] as $m){
       $m=trim($m,'{}'); 
    $vals['{'.$m.'}']=$this->verify_field_val($entry,$m);    
   }
  $value=str_replace(array_keys($vals),array_values($vals),$value);    
  }   

  }else{ //general field
  $field=$this->post('field',$v); 

  if($field !=""){
  $value=$this->verify_field_val($entry,$field); 
 // if($value == ''){ $value=null;  }
 if($field == '_vx_form_id' && isset($form['id'])){
  $value=$form['id'];   
 }else if($field == '_vx_form_name' && isset($form['title'])){
  $value=$form['title'];   
 }
  }}

if($value !== false ){
  if(isset($custom[$k])){

  $temp[$k]=array("value"=>$value,"label"=>$custom[$k]['label']);  
      }
      }
  }
  } 

if(!empty($data['owner']) && !empty($data['user'])){
  $feed['user']=apply_filters('vx_assigned_user_id',$data['user'],$this->id,$feed['id'],$entry);  
} 

  //add note 
   if(!empty($data['note_check']) && !empty($data['note_fields']) && is_array($data['note_fields'])){
          $entry_note=''; $entry_note_title='';
          foreach($data['note_fields'] as $e_note){ 
              $value=$this->verify_field_val($entry,$e_note); 
           if(!empty($value)){ 
               if(!empty($entry_note)){
                   $entry_note.="\n";
               }
           $entry_note.=$value;    
           }   
           if(empty($entry_note_title)){
            $entry_note_title=substr($entry_note,0,100);   
           }
          }
          if(!empty($entry_note)){
     $feed['__vx_entry_note']=array('title'=>$entry_note_title,'body'=>$entry_note);      
          }

  }
   
  }

$no_filter=true;    
  //not submitted by admin
  if(!$is_admin  && $this->post('manual_export',$data) == "1"){ //if manual export is yes
  continue;   
  }         
    if(isset($_REQUEST['bulk_action']) && $_REQUEST['bulk_action'] =="send_to_crm_bulk_force" && !empty($log_id)){
  $force_send=true;
  }
  if(!$force_send && $this->post('optin_enabled',$data) == "1"){ //apply filters if not sending by force and optin is enabled
  $no_filter=$this->check_filter($data,$entry); 
  $res=array("status"=>"4","extra"=>array("filter"=>$this->filter_condition),"data"=>$temp);  
  } 
$account=$this->post('account',$feed);

  $info=$this->get_info($account); 

  if($no_filter){ //get $res if no filter , other wise use filtered $res
  $api=$this->get_api($info);

  $res=$api->push_object($feed['object'],$temp,$feed);

  }
  
  $feed_id=$this->post('id',$feed);
  self::$feeds_res[$feed_id]=$res;
  $status=$res['status'];  $error=""; $id="";
  if($this->post('id',$res)!=""){ 
      $id=$res['id'];
      $action=$this->post('action',$res);
      if($action == "Added"){
          if(empty($res['link'])){
  $msg=sprintf(__('Successfully Added to Zendesk (%s) with ID # %s .', 'contact-form-zendesk-crm'),$feed['object'],$res['id']);
          }else{
  $msg=sprintf(__('Successfully Added to Zendesk (%s) with ID # %s . View entry at %s', 'contact-form-zendesk-crm'),$feed['object'],$res['id'],$res['link']);
          }
  $screen_msg=__( 'Entry added in Zendesk', 'contact-form-zendesk-crm');
      }else{
            if(empty($res['link'])){
  $msg=sprintf(__('Successfully Updated to Zendesk (%s) with ID # %s . View entry at %s', 'contact-form-zendesk-crm'),$feed['object'],$res['id'],$res['link']);   
            }else{
  $msg=sprintf(__('Successfully Updated to Zendesk (%s) with ID # %s .', 'contact-form-zendesk-crm'),$feed['object'],$res['id']);   
            }
          if($event == 'delete'){  
     $screen_msg=__( 'Entry deleted from Zendesk', 'contact-form-zendesk-crm');
          }else{
     $screen_msg=__( 'Entry updated in Zendesk', 'contact-form-zendesk-crm');
          }
          }
   
  
  }else if($this->post('status',$res) == 4){
  $screen_msg=$msg=__( 'Entry filtered', 'contact-form-zendesk-crm');    
  }else{
  $status=0; $screen_msg_class="error";
  $screen_msg=__('Errors when adding to Zendesk. Entry not sent! Check the Entry Notes below for more details.' , 'contact-form-zendesk-crm' );
  if($log_id!=""){
      //message for  bulk actions in logs
  $screen_msg=__('Errors when adding to Zendesk. Entry not sent' , 'contact-form-zendesk-crm' );    
  }
  $msg=sprintf(__('Error while creating %s', 'contact-form-zendesk-crm'),$feed['object']);
  if($this->post('error',$res)!=""){
      $error= is_array($res['error']) ? json_encode($res['error']) : $res['error'];
  $msg.=" ($error)";
  
  $_REQUEST['VXGZendeskError']=$msg; //front end form error for admin only
  }   
  if(!$is_admin){
      $info['msg']=$msg;
$this->send_error_email($info,$entry,$form);
  }
  
  } 

  //insert log
  $arr=array("object"=>$feed["object"],"form_id"=>$form['id'],"status"=>$status,"entry_id"=>$entry_id,"crm_id"=>$id,"meta"=>substr($error,0,245),"time"=>date('Y-m-d H:i:s'),"data"=>$this->post('data',$res),"response"=>$this->post('response',$res),"extra"=>$this->post('extra',$res),"feed_id"=>$this->post('id',$feed),'parent_id'=>$parent_id,'event'=>$event,"link"=>$this->post('link',$res));
  $settings=get_option($this->type.'_settings',array());
  if($this->post('disable_log',$settings) !="yes"){  
   $insert_id=$data_db->insert_log($arr,$log_id); 
  } 
  $log_link='';
    if(!empty($insert_id)){ //   
   $log_url=admin_url( 'admin.php?page='.$this->id.'&tab=logs&log_id='.$insert_id);  
  $log_link=' <a href="'.esc_url($log_url).'" class="vx_log_link" data-id="'.$insert_id.'">'.__('View Detail','contact-form-zendesk-crm')."</a>";
 $screen_msg.=$log_link;
    }
    if($post_comment){
  //insert entry comment
//$this->add_note($entry["id"], $msg);
    } 
    if($notice!=""){
  $notice.='<br/>';
  } 
  $notice.='<b>'.$object.': </b>'.$screen_msg;  
  
  if(!empty($data['mark_read']) && !empty($res['status']) && in_array($res['status'],array(1,2))){
      $mark_read_id=$entry_id;
  }
   
  }
  }
if(!empty($mark_read_id) && class_exists('vxcf_form')){
    $data=vxcf_form::get_data_object();
    $data->lead_actions(array('is_read'=>'1'),array($mark_read_id));
}
  return array("msg"=>$notice,"class"=>$screen_msg_class);
}

  /**
  * Send error email
  * 
  * @param mixed $info
  * @param mixed $entry
  * @param mixed $form
  */
  public function send_error_email($info,$entry,$form){
        if(!empty($info['data']['error_email'])){
  $subject="Error While Posting to Zendesk";
    $entry_link=add_query_arg(array('page' => 'vxcf_leads','tab'=>'entries', 'id' => $entry['__vx_id']), admin_url('admin.php'));  
  $page_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 

  $detail=array(
  "Time"=>date('d/M/y H:i:s',current_time('timestamp')),
  "Page URL"=>'<a href="'.$page_url.'" style="word-break:break-all;">'.$page_url.'</a>',
  "Entry ID"=>'<a href="'.$entry_link.'" target="_blank" style="word-break:break-all;">'.$entry_link.'</a>'
  );
  if(isset($form['title'])){
    $detail["Form Name"]=$form['title'];
  $detail["Form Id"]=$form['id'];
  }
    $email_info=array("msg"=>$info['msg'],"title"=>__('Zendesk','contact-form-zendesk-crm')." Error","info_title"=>"More Detail","info"=>$detail);
  $email_body=$this->format_user_info($email_info,true);

  $error_emails=explode(",",$info['data']['error_email']); 
  $headers = array('Content-Type: text/html; charset=UTF-8');
  foreach($error_emails as $email)   
  wp_mail(trim($email),$subject, $email_body,$headers);

        }

  }


  /**
  * Get Objects from local options or from zendesk
  *     
  * @param mixed $check_option
  * @return array
  */
  public function get_objects($info="",$refresh=false){
    
$objects=array('ticket'=>'Ticket','user'=>'User');     
  return $objects;    
 }
 public function web_fields($object){
    $fields=array('firstName'=>'First Name','lastName'=>'Last Name','organization'=>'Organization','jobTitle'=>'Job Title','background'=>'Background','email'=>'Email','phone'=>'Phone','website'=>'Website','facebook'=>'Facebook','twitter'=>'Twitter'); 
    $address=array('line1'=>'Address line 1','line2'=>'Address line 2','city'=>'City','state'=>'State','postalCode'=>'Postal Code','country'=>'Country');
    $msg=array('subject'=>'Subject','body'=>'Body','tag'=>'Tag');
    $fields=array_merge($fields,$address);
    if($object=='conversation'){
    $fields=array_merge($fields,$msg);    
    }
    $arr=array();
  foreach($fields as $k=>$v){
  $type='Text';
      if(isset($address[$k])){
    $type='Address';      
      }    
   ///
   $arr[$k]=array('name'=>$k,'label'=>$v,'type'=>$type);
   if(in_array($k,array('email','body','subject') )){
   $arr[$k]['req']='true';    
   }   
  }  
  return $arr;
 }
    /**
  * check if user conected to crm
  *     
  * @param mixed $settings
  */
  public function api_is_valid($info="") {

  if(isset($info['data']['class']) && is_array($info['data']) && $info['data']['class'] =='updated'){ 
  return true;
  }else{
  return false;}       
  }
}

$vxcf_zendesk=new vxcf_zendesk();
$vxcf_zendesk->instance();
$vx_cf['vxcf_zendesk']='vxcf_zendesk';
endif;

