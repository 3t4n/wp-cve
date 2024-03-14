<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


if( !class_exists( 'vxc_pages_sales' ) ) {

/**
* @since       1.0.0
*/
class vxc_pages_sales   extends vxc_sales{  
public $objects=false;
public $fields=false;
public $feeds=array();
public $post_id='';
public $ajax=false;
public $account='';
 /**
 * initialize plugin pages
 * 
 */
  public function __construct() {
  global $pagenow; 

  $this->setup(); 
  self::$base_url=$this->get_base_url();
  self::$slug=$this->get_slug();
add_action('save_post',array($this,'save_feed'),20,2);

  add_filter('woocommerce_settings_tabs_array',array($this,'add_settings_tab'),50);
  add_action('woocommerce_settings_'.$this->id,array($this,'settings_tab'));
  add_action( 'woocommerce_sections_'.$this->id, array( $this, 'output_sections' ) );
  
 
  add_action('woocommerce_update_options_'.$this->id, array($this,'update_settings'));
  add_action( 'woocommerce_update_order', array($this,'update_order'), 10, 1 );   //woocommerce_after_order_object_save 

  add_action( 'manage_'.$this->id.'_posts_custom_column', array($this,'table_columns'), 2 );
  add_action( 'add_meta_boxes', array($this,'fields_map_box') );
  add_action( 'add_meta_boxes', array($this,'send_to_crm_box') );

   add_action( 'admin_notices', array( $this, 'admin_notices' ) );
  
  add_filter( 'manage_edit-'.$this->id.'_columns', array($this,'table_head') );
  add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
  //logs page
  add_action( 'admin_menu', array( $this, 'create_menu' ),50 );
 
  add_filter( 'admin_menu', array($this,'remove_post_meta') ); 
  add_filter('plugin_action_links', array($this, 'plugin_action_links'), 10, 2);

  wp_register_style('vxc-css', self::$base_url. 'css/font-awesome.min.css',array(),array('ver'=>'1.0'));
  if($this->is_crm_page($this->id.'_log')){ 
  //enqueueing datepicker on logs page
  wp_enqueue_script('jquery-ui-datepicker' );
  wp_enqueue_style('vxc-ui',self::$base_url.'css/jquery-ui.min.css');

  } 
  if(in_array($pagenow,array("admin.php"))){
   wp_register_script( 'vxc-sorter',self::$base_url. 'js/jquery.tablesorter.min.js', array( 'jquery' ), $this->version, true );    
  }
  
  if(in_array($pagenow,array("post-new.php","post.php"))){
  wp_register_script( 'vxc-tooltip',self::$base_url. 'js/jquery.tipTip.js', array( 'jquery' ), $this->version, true );
  wp_register_script( 'vxc-select2',self::$base_url. 'js/select2.min.js', array( 'jquery' ), $this->version, true );
 
  wp_register_style('vxc-select2', self::$base_url. 'css/select2.min.css',array(),array('ver'=>'1.0'));
  }
              
       if(in_array($pagenow,array('post.php','edit.php'))){
  add_action( 'wp_trash_post', array( $this, 'trash_order' ) );      
  add_action( 'untrash_post', array( $this, 'untrash_order' ) );      
       }
       if(in_array($pagenow,array("admin-ajax.php"))){
 
    add_action( 'wp_insert_comment', array( $this, 'insert_comment' ),50,2 );  
    add_action( 'trash_comment', array( $this, 'trash_comment' ) ); 

       }
  $this->vxc_create_post_type();
  if(in_array($pagenow, array("admin-ajax.php"))){
  add_action('wp_ajax_fields_map_'.$this->id, array($this, 'get_fields_map'));
  add_action('wp_ajax_field_account_'.$this->id, array($this, 'field_map_object_ajax'));
  add_action('wp_ajax_get_objects_'.$this->id, array($this, 'get_objects_list'));
  add_action('wp_ajax_log_detail_'.$this->id, array($this, 'log_detail')); 
  add_action('wp_ajax_refresh_data_'.$this->id, array($this, 'refresh_data')); 
  }
  }
  /**
  * post comment to crm
  * 
  * @param mixed $id
  * @param mixed $comment
  */
  public function insert_comment($id,$comment){

   if(isset($comment->comment_type) && $comment->comment_type == 'order_note' && isset($_REQUEST['action']) && $_REQUEST['action'] == 'woocommerce_add_order_note'){
        $meta=get_option($this->type.'_settings',array());
if(isset($meta['notes']) && $meta['notes'] == 'yes'){
   self::$note=array('order_id'=>$comment->comment_post_ID,'id'=>$comment->comment_ID,'body'=>$comment->comment_content,'title'=>substr($comment->comment_content,0,40));  
 $this->push($comment->comment_post_ID,'add_note');
}
   }  
  }
/**
* remove comment from crm
* 
* @param mixed $id
*/
  public function trash_comment($id){
   $comment=get_comment( $id); 
if(isset($comment->comment_type) && $comment->comment_type == 'order_note'){
    $meta=get_option($this->type.'_settings',array());
    if(isset($meta['notes']) && $meta['notes'] == 'yes'){
      self::$note=array('order_id'=>$comment->comment_post_ID,'id'=>$comment->comment_ID);
$this->push($comment->comment_post_ID,'delete_note');
    }
} 
  }
  /**
  * delete order data from crm
  * 
  * @param mixed $id
  */
  public function trash_order($id){ 
$post_type = get_post_type( $id );
if($post_type == 'shop_order'){
        $meta=get_option($this->type.'_settings',array());
    if(isset($meta['delete']) && $meta['delete'] == 'yes'){
 $this->push($id,'delete');   
    }
}
  }
  /**
  * restore order in crm
  * 
  * @param mixed $id
  */
public function untrash_order($id){ 
$post_type = get_post_type( $id );
if($post_type == 'shop_order'){
        $meta=get_option($this->type.'_settings',array());
    if(isset($meta['restore']) && $meta['restore'] == 'yes'){ 
 $this->push($id,'restore');   
    }
}
  }

  /**
  * actions before headers
  * 
  */
  public function setup(){
  global $wpdb;
  
  if($this->post('vx_tab_action_'.$this->id)=="export_log"){
 
  check_admin_referer('vx_nonce','vx_nonce');
  if(!current_user_can($this->id."_export_logs")){ 
  $msg=__('You do not have permissions to export logs','woocommerce-salesforce-crm');
  $this->display_msg('admin',$msg);
  return;   
  }
 header('Content-disposition: attachment; filename='.date("Y-m-d",current_time('timestamp')).'.csv');  
 header('Content-Type: application/excel');
 $objects=$this->get_objects();
  $sql_end=$this->get_log_query();
  $forms=array();
 $sql="select * $sql_end limit 3000";
  $results = $wpdb->get_results($sql , ARRAY_A );  
  $fields=array(); $field_titles=array(__("SNO",'woocommerce-salesforce-crm'),__("Status",'woocommerce-salesforce-crm'),__("Salesforce ID",'woocommerce-salesforce-crm') ,__("Order ID",'woocommerce-salesforce-crm'),__("Description",'woocommerce-salesforce-crm'),__("Salesforce Link",'woocommerce-salesforce-crm'),__("Time",'woocommerce-salesforce-crm'));
  $fp = fopen('php://output', 'w');
  fputcsv($fp, $field_titles);
  $sno=0;
  foreach($results as $row){
  $sno++;
  $row=$this->verify_log($row,$objects);   
  fputcsv($fp, array($sno,$row['title'],$row['_crm_id'],$row['order_id'],$row['desc'],$row['link'],$row['time']));    
  }
  fclose($fp);
  die();
  }
    if($this->post('vx_tab_action_'.$this->id)=="clear_logs" ){
  check_admin_referer('vx_nonce','vx_nonce');
  if(!current_user_can($this->id."_edit_settings")){ 
  $msg=__('You do not have permissions to clear logs','woocommerce-salesforce-crm');
  $this->display_msg('admin',$msg);
  return;   
  }
  global $wpdb;
  $table_name =  $this->get_table_name('log');
  $clear=$wpdb->query("truncate table `".$table_name."`");
  $log_str="Logs cleared";
  $this->log_msg($log_str);
  wp_redirect(admin_url("admin.php?page=".$this->post('page')."&".$this->id."_logs=".$clear));
  die();
  } 

  $this->setup_plugin();        
  }

     /**
  * Display custom notices
  * show salesforce response
  * 
  */
  public function admin_notices(){  
   
  if((isset($_REQUEST['vx_debug']) || isset($_GET[$this->id.'_msg'])) && current_user_can($this->id.'_edit_settings')){ 
  $contents=get_option($this->id."_debug");
  if($contents!=""){
  echo "<div class='error'><p>".wp_kses_post($contents)."</p></div>"; 
  update_option($this->id."_debug",'');  
  }
   
  }
  if(isset($_REQUEST[$this->id.'_msg']) || isset($_REQUEST['message'])){ //send to crm in order page message
  $msg=get_option($this->id.'_msg');    
  
  update_option($this->id.'_msg','');
  if(isset($msg['class'])){
      $this->screen_msg($msg['class'],$msg['msg']);
  }  
  }
  if(isset($_GET[$this->id."_logs"])){
      $msg=__('Error While Clearing Salesforce Logs','woocommerce-salesforce-crm');
      $level="error";
      if(!empty($_GET[$this->id."_logs"])){
      $msg=__('Salesforce Logs Cleared Successfully','woocommerce-salesforce-crm');   
      $level="updated";
      }
        $this->screen_msg($level,$msg);  
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
            array_unshift( $links, '<a href="' .$settings_link. '">' . __('Settings', 'woocommerce-salesforce-crm') . '</a>' );
        }
        return $links;
    } 
    /**
  * removes default wp post metaboxes
  * 
  */
  public function remove_post_meta(){
  remove_meta_box( 'commentstatusdiv', $this->id , 'normal' );
  remove_meta_box( 'commentsdiv', $this->id , 'normal' );
  remove_meta_box( 'postcustom', $this->id , 'normal' );
  remove_meta_box( 'woothemes-settings', $this->id , 'normal' );
  remove_meta_box( 'slugdiv', $this->id , 'normal' );  
  }   
  /**
  * Output sections
  */
  public function output_sections() {

  global $current_section;
  $sections=array(""=>__('Salesforce Settings','woocommerce-salesforce-crm'),'vxc_uninstall'=>__('Uninstall','woocommerce-salesforce-crm'));
  $sections=apply_filters('add_section_tab_wc_'.$this->id,$sections);
  echo '<ul class="subsubsub">';
  
  $array_keys = array_keys( $sections );
  
  foreach ( $sections as $id => $label ) {
  echo '<li><a href="' . esc_url(admin_url( 'admin.php?page=wc-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) )) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '" title="'.sanitize_text_field($label).'">' .esc_html($label). '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
  }
  
  echo '</ul><br class="clear" />';
  }
  /**
  * Add Settings tab.
  *       
  * @param mixed $settings_tabs
  */
  public function add_settings_tab($settings_tabs){
        if(current_user_can($this->id."_read_settings")){ 
  $settings_tabs[$this->id] = __( 'Salesforce', 'woocommerce-salesforce-crm' );
        }
  return $settings_tabs;
  }
  /**
  * add option pages
  * 
  */
  public function create_menu(){

  add_submenu_page( 'woocommerce', __( 'Salesforce Log','woocommerce-salesforce-crm'),  __( 'Salesforce Log','woocommerce-salesforce-crm') , $this->id."_read_logs", $this->id.'_log', array( $this, 'log_page' ) );
  }
  
  /**
  * Update the settings values.
  * 
  */
  public function update_settings(){
       if(!current_user_can($this->id."_edit_settings")){ 
        return;   
       }
  if(isset($_POST[$this->id.'_uninstall'])){ 
  self::$path=$this->get_base_path();
 include_once(self::$path . "includes/install.php"); 
  do_action('uninstall_vx_plugin_'.$this->type);
   $install=new vxc_install_sales();
  $install->remove_data();
 $install->remove_roles();  
  $install->deactivate_plugin();  
  return;   
  } 

  $this->update_settings_plugin();       
  }
  /**
  * Create salesforce feed
  * 
  */
  private function vxc_create_post_type() {
  $show_in_menu = current_user_can( 'manage_woocommerce' ) ? 'woocommerce' : true;
  
  register_post_type( $this->id,
  array(
  'labels' => array(
  'name'                  => __( 'Salesforce', 'woocommerce-salesforce-crm' ),
  'singular_name'         => __( 'Salesforce Feed', 'woocommerce-salesforce-crm' ),
  'menu_name'             => _x( 'Salesforce Feeds', 'Admin menu name', 'woocommerce-salesforce-crm' ),
  'add_new'               => __( 'New Salesforce Feed', 'woocommerce-salesforce-crm' ),
  'add_new_item'          => __( 'Add New Salesforce Feed', 'woocommerce-salesforce-crm' ),
  'edit'                  => __( 'Edit', 'woocommerce-salesforce-crm' ),
  'edit_item'             => __( 'Edit Salesforce Feed', 'woocommerce-salesforce-crm' ),
  'new_item'              => __( 'New Salesforce Feed', 'woocommerce-salesforce-crm' ),
  'view'                  => __( 'View Salesforce Feed', 'woocommerce-salesforce-crm' ),
  'view_item'             => __( 'View Salesforce Feed', 'woocommerce-salesforce-crm' ),
  'search_items'          => __( 'Search Salesforce Feeds', 'woocommerce-salesforce-crm' ),
  'not_found'             => __( 'No Salesforce Feeds found', 'woocommerce-salesforce-crm' ),
  'not_found_in_trash'    => __( 'No Salesforce Feeds found in trash', 'woocommerce-salesforce-crm' ),
  'parent'                => __( 'Parent Salesforce Feed', 'woocommerce-salesforce-crm' )
  ),
  'description'         => __( 'This is where feeds are stored.', 'woocommerce-salesforce-crm' ),
  'public'              => false,
  'show_ui'             => true,
  'capability_type'     => $this->id,
  //  'capabilities'=>array('read_vxc_sales1s'),
  'map_meta_cap'        => true,
  'publicly_queryable'  => false,
  'exclude_from_search' => true,
  'has_archive'           => false,
  'publicly_queryable'    => false,
  'exclude_from_search'   => false,
  'show_in_menu'          => $show_in_menu,
  'hierarchical'        => false,
  'show_in_nav_menus'   => false,
  'rewrite'             => false,
  'query_var'           => false,
  'supports'              => array( 'title' )
  )
  );
  
  
  
  }
  /**
  * Override wp post messages for crm feed
  * 
  * @param mixed $messages
  */
  public function post_updated_messages( $messages ) {
  ///   global $post, $post_ID;
  $messages[$this->id] = array(
  0 => '', // Unused. Messages start at index 1.
  1 => __( 'Salesforce Feed updated.', 'woocommerce-salesforce-crm' ),
  6 => __( 'Salesforce Feed updated.', 'woocommerce-salesforce-crm' )
  );
  return $messages;
  } 
  /**
  * Add the crm meta box on the single order page
  * 
  */
  public function send_to_crm_box() {
  if(current_user_can($this->id."_send_to_crm")){ 
            $screen='shop_order';
      if ( class_exists( 'Automattic\WooCommerce\Utilities\OrderUtil' ) && Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled() ) {
              $screen=wc_get_page_screen_id( 'shop-order' );      //woocommerce_page_wc-orders
                }
  add_meta_box(
  $this->id."_crm_box", //$id
  __( 'Salesforce', 'woocommerce-salesforce-crm' ), //$title
  array( $this, 'crm_order_box' ), //$callback
  $screen, //$post_type
  'side', //$context
  'default' //$priority
  ); 
  if(self::$is_pr){ 
   add_meta_box(
  $this->id."_crm_box", //$id
  __( 'Salesforce', 'woocommerce-salesforce-crm' ), //$title
  array( $this, 'crm_order_box' ), //$callback
  'shop_subscription', //$post_type
  'side', //$context
  'default' //$priority
  );
  }
  }
  } 
  /**
  * Add the fields mapping meta box on the single  feed page
  * 
  */
  public function fields_map_box() {
  add_meta_box(
  $this->id."_fields_map", //$id
  __( 'Salesforce Fields Mapping', 'woocommerce-salesforce-crm' ), //$title
  array($this, 'fields_map_contents' ), //$callback
  $this->id, //$post_type
  'normal', //$context
  'high' //$priority
  );
  }
  
  /**
  * Send to crm box's Contents
  * 
  */
  public function crm_order_box($object){
  if(is_object($object)){
          if(method_exists($object,'get_id')){ 
          $post_id=$object->get_id();    
          }else if(isset($object->ID)){
            $post_id=$object->ID;     
          }
      }   
$notes=$this->get_order_logs($post_id);
include_once(self::$path."templates/crm-order-box.php");
  }
  /**
  * Get fields map. AJAX method
  * 
  */
  public function get_fields_map(){

  check_ajax_referer("vx_crm_ajax","vx_crm_ajax"); 

  if(!current_user_can($this->id."_edit_settings")){ 
   die('-1');  
 }

 $this->ajax=true;
  $object=$this->post('object');
  $this->post_id=$id=intval($this->post('id')); 
   $feed=get_post_meta($id,$this->id.'_meta',true);
        $this->account=$account=$this->post('account');
      $info=$this->get_info($account);
  $arr=$this->get_field_mapping($feed,$info,$object); 

  die($arr);  
  }
  /**
  * woocommerce fields selectbox in fields mapping
  * 
  * @param mixed $sel_val
  * @param mixed $type
  */
  public function wc_select($sel_val=""){
                      if(!is_array($sel_val)){
$sel_val=array($sel_val);
      }
  $wc_fields=$this->get_wc_fields();     
  $options="<option value=''></option>";
  if(is_array($wc_fields)){
  foreach($wc_fields as $arr_key=>$arr_val){
  if(is_array($arr_val)){
  $val="";
  if(in_array($arr_key,array("billing","shipping"))){
 $val=ucfirst($arr_key)." ";
  } 
  //$arr_key=ucfirst($arr_key);
  $options.="<optgroup label='".esc_html($arr_val['title'])."'>";
 if(isset($arr_val['fields']) && is_array($arr_val['fields'])){
  foreach($arr_val['fields'] as $f_key=>$f_val){
  if(isset($f_val['label'])){ 
  $select="";
  if( in_array($f_key,$sel_val)){
  $select='selected="selected"';
  }
  $options.='<option value="'.esc_attr($f_key).'" '.$select.'>'.esc_html($val.$f_val['label']).'</option>';    
  } 
  } }
  $options.="</optgroup>"; 
  }
  }    
  }
  return $options; 
  }
  /**
  * Get field label from field key
  * 
  * @param mixed $field_key
  */
  public function get_wc_field_label($field_key){
  $wc_fields=$this->get_wc_fields();
  $field_label="";
  if(is_array($wc_fields)){
  foreach($wc_fields as $fields){
      if(is_array($fields['fields']) && isset($fields['fields'][$field_key]['label'])){
   $field_label=$fields['fields'][$field_key]['label'];      
     break;
      }
  }        
  }
  return $field_label;
  }
  /**
  * get wc fields
  * 
  * @param mixed $type
  */
  public function get_wc_fields(){
      if( $this->fields){
          return  $this->fields;
      }
        $bill_fields_wc= WC()->countries->get_address_fields('us','billing_' );
          $bill_fields=array();
         if(is_array($bill_fields_wc) && count($bill_fields_wc)>0){
             foreach($bill_fields_wc as $k=>$v){
$label='';
if(isset($v['label'])){ $label=$v['label']; }
if(!empty($v['placeholder'])){ $label=$v['placeholder']; }else{
   $label=$k; 
}

            $bill_fields["_".$k]=array('label'=>$label);   
               if($k == 'billing_state'){ 
             $bill_fields["_vxst_billing_state"]=array('label'=>'State Label');      
            }  if($k == 'billing_country'){ 
             $bill_fields["_vxst_billing_country"]=array('label'=>'Country Label');      
            }  
             }
         $bill_fields['_email_domain']=array('label'=>'Email Domain');
         }
  $ship_fields_wc= WC()->countries->get_address_fields('us','shipping_' );
  
     $ship_fields=array();
         if(is_array($ship_fields_wc) && count($ship_fields_wc)>0){
             foreach($ship_fields_wc as $k=>$v){
$label='';
if(isset($v['label'])){ $label=$v['label']; }
if(!empty($v['placeholder'])){ $label=$v['placeholder']; }

            $ship_fields["_".$k]=array('label'=>$label); 
                if($k == 'shipping_state'){ 
             $ship_fields["_vxst_shipping_state"]=array('label'=>'State Label');      
            }   if($k == 'shipping_country'){ 
             $ship_fields["_vxst_shipping_country"]=array('label'=>'Country Label');      
            }    
             }
         }
  $gen_fields=array(
  '_order_date'=>array('label'=>'Order Date'),
  '_order_id'=>array('label'=>'Order ID'),
  '_completed_date'=>array('label'=>'Order Completed Date'),
  '_order_status'=>array('label'=>'Order Status'),
  '_order_discount_total'=>array('label'=>'Order Discount Total'),
  '_order_discount_total_refunded'=>array('label'=>'Order Discount Total + Refunded Total'),
  '_order_tax_total'=>array('label'=>'Order Tax Total'),
  '_order_shipping_total'=>array('label'=>'Order Shipping Total'),
  '_order_shipping_total_tax'=>array('label'=>'Order Shipping Total + Shipping Tax'),
  '_order_shipping_tax'=>array('label'=>'Order Shipping Tax'),
  '_order_total'=>array('label'=>'Order Total'),
  '_order_total_refunded'=>array('label'=>'Order Total - Total Refunded'),  
  '_order_fees_total'=>array('label'=>'Order Fees Total'),
  '_order_fees_total_tax'=>array('label'=>'Order Fees Total + Fees Tax'),
  '_order_fees_total_shipping'=>array('label'=>'Order Fees Total + Shipping Total'),
  '_order_subtotal'=>array('label'=>'Order SubTotal'),
  '_order_key'=>array('label'=>'Order Key'),
  '__vxo_order_total'=>array('label'=>'Total value of customer Orders'),
  '__vxo_order_count'=>array('label'=>'Total customer Orders'),
  '__vxo_last_order_date'=>array('label'=>'Last Order Date'),
  '__vxo_last_order_number'=>array('label'=>'Last Order Number'),
  '__vxo_first_order_date'=>array('label'=>'First Order Date'),
  '__vxo_first_order_value'=>array('label'=>'First Order Value'),
  '__vxo_last_order_value'=>array('label'=>'Last Order Value'),
  '__vxo_last_order_status'=>array('label'=>'Last Order Status'),
  '_customer_ip_address'=>array('label'=>'Customer IP Address'),
  '_customer_user_agent'=>array('label'=>'Customer User Agent'),
  '_customer_notes'=>array('label'=>'Customer Order Note'),
  '_order_notes'=>array('label'=>'Order Notes - All'),
  '_payment_method'=>array('label'=>'Payment Method'),
  '_payment_method_title'=>array('label'=>'Payment method Title'),
  '_shipping_method_title'=>array('label'=>'Shipping method Title'),
  '_order_currency'=>array('label'=>'Order Currency'),
  '_total_refunded'=>array('label'=>'Total Refunded'),
  '_refund_reason'=>array('label'=>'Refund Reason'),
  '_total_refunded_tax'=>array('label'=>'Total Refunded Tax'),
  '_total_shipping_refunded'=>array('label'=>'Total Shipping Refunded'),
  '_total_qty_refunded'=>array('label'=>'Total Quantity Refunded'),
  '_used_coupns'=>array('label'=>'Used Coupons'),
  '_items_count'=>array('label'=>'Order Items Count'),
  '_order_fees'=>array('label'=>'Order Fees Detail (textrea)'),
  '_order_items'=>array('label'=>'Order Items Detail (textrea)'),
  '_order_items_skus'=>array('label'=>'Order Items SKUs'),
  '_order_items_titles'=>array('label'=>'Order Items Titles'),
  '_order_items_qty'=>array('label'=>'Order Items Titles and Quantity'),
  '_download_permissions_granted'=>array('label'=>'Download permissions Granted'),
  'parent_post_id'=>array('label'=>'Parent Post Id'),
  '_subscription_renewal'=>array('label'=>'Subscription Renewal'),
  '_transaction_id'=>array('label'=>'Transaction id')
  );
 $user=array('__vx_wp-user_login'=>array('label'=>'user_login'),'__vx_wp-ID'=>array('label'=>'user_id')
  ,'__vx_wp-user_email'=>array('label'=>'user_email'),'__vx_wp-first_name'=>array('label'=>'first_name')
  ,'__vx_wp-last_name'=>array('label'=>'last_name'),'__vx_wp-user_registered'=>array('label'=>'user_registered')
  ,'__vx_wp-user_nicename'=>array('label'=>'user_nicename'),'__vx_wp-display_name'=>array('label'=>'display_name')
  ,'__vx_wp-user_url'=>array('label'=>'user_url'),'__vx_wp-roles'=>array('label'=>'user_roles')
  ,'__vx_wp-caps'=>array('label'=>'user_caps')
  );
    $txs=wc_get_attribute_taxonomies();
  $tx_arr=array();
 $product_attrs=array('title'=>'Product Title','description'=>'Product Description','short_description'=>'Short Description','sku'=>'Product SKU','price'=>'Product Price','regular_price'=>'Product Regular Price','sale_price'=>'Product Sales Price','total_sales'=>'Product Total Sales','stock_quantity'=>'Stock Quantity','weight'=>'Product Weight','length'=>'Product Length','width'=>'Product Width','height'=>'Product Height','get_category_ids'=>'Product Categories','get_category'=>'Product First Category','product_img'=>'Product Image URL');
  
  foreach($product_attrs as $k=>$v){
      $tx_arr['__vxp_fun-'.$k]=array('label'=>$v);
  }

  $variation_attrs=array();
  foreach($txs as $v){
      $variation_attrs['pa_'.$v->attribute_name]=$v->attribute_label;
     // $key= $v->get_variation( ) ? 'vtr' : 'atr';
      $tx_arr['__vxp_atr-'.$v->attribute_name]=array('label'=>$v->attribute_label);
  }
  $last_id=$this->get_last_order_id();
  $order_meta=get_post_meta($last_id); 
 $skip=array('_edit_lock','_edit_last'); 
 if(is_array($order_meta)){
  foreach($order_meta as $k=>$v){
   if(!isset($gen_fields[$k]) && !isset($bill_fields[$k]) && !isset($ship_fields[$k]) && !in_array($k,$skip)){
      $gen_fields[$k]=array('label'=>$k);   
   }
  }
 }
    $item_labels=array('_qty'=>'Quantity','_line_total'=>'Line Total');
  //item fields from db
  global $wpdb;
  $table=$wpdb->prefix.'woocommerce_order_itemmeta';
  $sql="SELECT meta_key FROM `$table` group by meta_key";
  $res=$wpdb->get_results($sql,ARRAY_A);
  $item_fields=array('__vxp_iun-name'=>array('label'=>'Item Name'),'__vxp_iun-type'=>array('label'=>'Item Type'),'__vxp_iun-id'=>array('label'=>'Item ID'));
  foreach($res as $v){
      $label=isset($item_labels[$v['meta_key']]) ? $item_labels[$v['meta_key']] : $v['meta_key']; 
      if(!isset($variation_attrs[$v['meta_key']])){
   $item_fields['__vx_pa-'.$v['meta_key']]=array('label'=>$label);
      }else{
    $item_fields['__vxp_vtr-'.$v['meta_key']]=array('label'=>$variation_attrs[$v['meta_key']]);      
      }   
  }
  $item_fields['__vx_sh-custom_meta']=array('label'=>'Shipping Item Meta Field');

 $this->fields=$fields=array(
  'billing'=>array(
  'title'=>__('Billing Fields', 'woocommerce-salesforce-crm'),
  'fields' => $bill_fields),
  
  'shipping'=>array(
  'title'=>__('Shipping Fields', 'woocommerce-salesforce-crm'),
  'fields'=>$ship_fields),
  
  'general'=>array(
  'title'=>__('General Fields', 'woocommerce-salesforce-crm'),
  'fields'=>$gen_fields),
    'attrs'=>array(
  'title'=>__('Product Attributes', 'woocommerce-salesforce-crm'),
  'fields'=>$tx_arr),
   
  'items'=>array(
  'title'=>__('Line Items Data', 'woocommerce-salesforce-crm'),
  'fields'=>$item_fields),
  'user'=>array(
  'title'=>__('WP User Fields', 'woocommerce-salesforce-crm'),
  'fields'=>$user)
   ); 
   
      if(class_exists('WC_Bookings')){
     $booking_fields=array();
  $booking_field=array('get_end_date'=>'End Date','get_start_date'=>'Start Date','get_persons_total'=>'Total Persons');
  foreach($booking_field as $k=>$v){
  $booking_fields['__vxp_vtr-'.$k]=array('label'=>$v);     
  }    
   $fields['booking']=array(
  'title'=>__('WooCommerce Booking', 'woo-zoho'),
  'fields'=>$booking_fields);  
   } 
   if(class_exists('WC_Subscriptions')){
       $subs_fields=array();
       $subs_field=array('end'=>'End Date','start'=>'Start Date','trial_end'=>'Trial End Date','last_order_date_created'=>'Last Order Date','date_created'=>'Order Date');
  foreach($subs_field as $k=>$v){
  $subs_fields['__vxs_dat-'.$k]=array('label'=>$v);     
  }
   $fields['subscription']=array(
  'title'=>__('WooCommerce Subscription', 'woo-zoho'),
  'fields'=>$subs_fields);  
   }

  //$wc['less']=array("billing"=>$bill_fields,"shipping"=>$ship_fields,""=>$gen_fields);

  if($this->do_actions()){ 
  $fields=apply_filters('vx_mapping_standard_fields', $fields);
      $contact_feeds=$this->get_object_feeds('',$this->account,$this->post_id);  
    $feeds=array();
  if(!empty($contact_feeds)){
      foreach($contact_feeds as $k=>$v){
      $feeds['_vx_feed-'.$k]=array('id'=>'_vx_feed-'.$k,'label'=>$v);    
      }
  $fields['feeds']=array("title"=>__('ID from other Feeds','woocommerce-salesforce-crm'),"fields"=>$feeds);
  }
  }
  $this->fields=$fields;
  return  $this->fields;
  }
      public function get_last_order_id(){
    global $wpdb;
 return $wpdb->get_var( "SELECT ID FROM {$wpdb->prefix}posts
        WHERE post_type LIKE 'shop_order' order by ID desc limit 1" );
}
  /**
  * crm fields select options
  * 
  * @param mixed $fields
  * @param mixed $selected
  */
  public function crm_select($fields,$selected,$first_empty=true){
  $field_options='';
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
  /**
  * available operators for custom filters
  * 
  */
  public function get_filter_ops(){
       return array("is"=>"Exactly Matches","is_not"=>"Does Not Exactly Match","contains"=>"(Text) Contains","not_contains"=>"(Text) Does Not Contain","is_in"=>"(Text) Is In","not_in"=>"(Text) Is Not In","starts"=>"(Text) Starts With","not_starts"=>"(Text) Does Not Start With","ends"=>"(Text) Ends With","not_ends"=>"(Text) Does Not End With","less"=>"(Number) Less Than","greater"=>"(Number) Greater Than","less_date"=>"(Date/Time) Less Than","greater_date"=>"(Date/Time) Greater Than","equal_date"=>"(Date/Time) Equals","empty"=>"Is Empty","not_empty"=>"Is Not Empty"); 
  }
  /**
  * Field mapping HTML
  * 
  * @param mixed $post_id
  * @param mixed $feed
  * @param mixed $crm
  */
  private function get_field_mapping($feed,$info="",$object=""){ ///update_post_meta($post_id,'vxc_sales_meta',''); 


  $fields=array();
     $account=$this->account;
  if(!is_array($feed)){ $feed=array(); }
  if($object != ""){
   $module=$object;   
  }else{
   $module=$this->post('object',$feed);   
  }
   if(empty($info)){ //ajax error
   $link=$this->link_to_settings();
  ?>
  <div class="alert_danger"><?php echo sprintf(__('Salesforce Settings are not Valid. Go to %sSettings%s','woocommerce-salesforce-crm'),'<a href="'.$link.'">','</a>')?></div>
  <?php
  return;
  }

  if($module == ""){ //ajax error
  ?>
  <div class="alert_danger"><?php esc_html_e('Please Select Object','woocommerce-salesforce-crm')?></div>
  <?php
  return;
  }
  //refresh if field mapping obtained by ajax
  $meta=array();
  if(isset($info['meta'])){
$meta=$info['meta'];
  }
    $data=array();
  if(isset($info['data'])){
$data=$info['data'];
  }

  $map=isset($feed['map']) && is_array($feed['map']) ? $feed['map'] : array();
  // $map_c=isset($feed['custom']) && is_array($feed['custom']) ? $feed['custom'] : array();
  $api_type=$this->post('api',$data);   
  if($api_type == "web"){    
  $fields=$this->web_fields($module,$map);
  }else{
  if($this->ajax){ 
      
  $api=$this->get_api($info);
  $fields=$api->get_crm_fields($module); 

$meta= is_array($meta) ? $meta : array(); 
  if(is_array($fields)){ 
           if(!self::$is_pr){
     $temp_fields=array();
       $phone=array('AssistantPhone','Phone','MobilePhone','Fax','HomePhone','OtherPhone');
       
    foreach($fields as $k=>$v){
        if(isset($v['custom']) && $v['custom'] == false && !in_array($k,$phone)){ 
       $temp_fields[$k]=$v;     
        }
    }
   $fields= $temp_fields;
 }
  $meta['fields']=$fields;     
  $meta['object']=$module;     
  $meta['post_id']=$this->post_id;   
  $this->update_info( array('meta'=>$meta),$info['id']);        
  }  
 // echo json_encode($fields); 
  }else{
 $fields=$this->post('fields',$feed); 
  }
  }

  if(!is_array($fields)|| count($fields)<1){
  
  $fields=empty($fields) ? __("No Fields Found",'woocommerce-salesforce-crm') : $fields.' - Try Re-Connecting your Salesforce Account';   
   ?>
  <div class="vx_error"><?php echo wp_kses_post($fields) ?></div>
  <?php
  return;
  }
  $sel_fields=array(""=>__("Standard Field",'woocommerce-salesforce-crm'),"custom"=>__("Custom Field",'woocommerce-salesforce-crm'),"value"=>__("Custom Value",'woocommerce-salesforce-crm'));

    if(isset($feed['filters']) && is_array($feed['filters'])&& count($feed['filters'])>0){
  $filters=$feed['filters'];    
  }else{
  $filters=array("1"=>array("1"=>array("field"=>"")));   
  }
    $tooltips=self::$tooltips ; 
  $vx_op=$this->get_filter_ops(); 
  $options_empty=$this->wc_select();

  $events=array("submit"=>"When user submits the order");
  //"completed"=>__("When order status changes to complete",'woocommerce-salesforce-crm'),"processing"=>__("When order status changes to processing",'woocommerce-salesforce-crm')
  
  $status_list=wc_get_order_statuses();
  if(!empty($status_list)){
      foreach($status_list as $k=>$v){
          $k=substr($k,3);
          if(!in_array($k,array('pending'))){
       $events[$k]='When order status changes to '.$v;   
      } }
  }
  $events["manual"]=__("Manually send order to Salesforce",'woocommerce-salesforce-crm');  
  //,"user_created"=>__("When user creates account on checkout page",'woocommerce-salesforce-crm')
  if(self::$is_pr){
  $events['save_product']=__('Woocommerce Product Updated/Created','woocommerce-salesforce-crm');
  $events['save_user']=__('Wordpress User Updated/Created','woocommerce-salesforce-crm');
   if(class_exists('WC_Subscriptions')){
 $statuses = wcs_get_subscription_statuses();
 foreach ( $statuses as $status => $status_name ) {
 $events['vxs'.$status]='When Subscription Status Changes to '.$status_name; 
 }
}

  }
   
  $map_fields=array();
if($api_type != 'web'){
  foreach($fields as $k=>$v){
      $req=$this->post('req',$v);
      if($req == 'true'){
   $map_fields[$k]=$v;       
      }  
  }
  
 // echo json_encode($map).'---'.$feed['primary_key'].'----'.$module;

$feeds_arr=array('Account'=>array('fields'=>'{"Name":{"type":"value","custom":"","value":"{_billing_first_name} {_billing_last_name}","field":"_billing_last_name"},"Phone":{"type":"","custom":"","value":"","field":"_billing_phone"},"BillingCity":{"type":"","custom":"","value":"","field":"_billing_city"},"BillingStreet":{"type":"value","custom":"","value":"{_billing_address_1} {_billing_address_2}","field":"_billing_address_2"},"BillingState":{"type":"","custom":"","value":"","field":"_billing_state"},"BillingPostalCode":{"type":"","custom":"","value":"","field":"_billing_postcode"},"BillingCountry":{"type":"","custom":"","value":"","field":"_billing_country"},"ShippingStreet":{"type":"value","custom":"","value":"{_shipping_address_1} {_shipping_address_2}","field":"_shipping_address_2"},"ShippingCity":{"type":"","custom":"","value":"","field":"_shipping_city"},"ShippingState":{"type":"","custom":"","value":"","field":"_shipping_state"},"ShippingPostalCode":{"type":"","custom":"","value":"","field":"_shipping_postcode"},"ShippingCountry":{"type":"","custom":"","value":"","field":"_shipping_country"}}','feed'=>array('primary_key'=>'Name','update'=>'1')) );
  
  $feeds_arr['Lead']=array('fields'=>'{"LastName":{"type":"","custom":"","value":"","field":"_billing_last_name"},"Company":{"type":"","custom":"","value":"","field":"_billing_company"},"FirstName":{"type":"","custom":"","value":"","field":"_billing_first_name"},"Email":{"type":"","custom":"","value":"","field":"_billing_email"},"Title":{"type":"value","custom":"","value":"woo lead {_billing_last_name}","field":"_billing_last_name"},"Status":{"type":"value","custom":"","value":"Working - Contacted","field":""},"LeadSource":{"type":"value","custom":"","value":"Phone Inquiry","field":""},"Street":{"type":"value","custom":"","value":"{_billing_address_1} {_billing_address_2}","field":"_billing_address_2"},"City":{"type":"","custom":"","value":"","field":"_billing_city"},"State":{"type":"","custom":"","value":"","field":"_billing_state"},"PostalCode":{"type":"","custom":"","value":"","field":"_billing_postcode"},"Country":{"type":"","custom":"","value":"","field":"_billing_country"}}','feed'=>array());
  
    $feeds_arr['Contact']=array('fields'=>'{"LastName":{"type":"","custom":"","value":"","field":"_billing_last_name"},"FirstName":{"type":"","custom":"","value":"","field":"_billing_first_name"},"Email":{"type":"","custom":"","value":"","field":"_billing_email"},"Phone":{"type":"","custom":"","value":"","field":"_billing_phone"},"OtherStreet":{"type":"value","custom":"","value":"{_billing_address_1} {_billing_address_2}","field":"_billing_address_2"},"OtherCity":{"type":"","custom":"","value":"","field":"_billing_city"},"OtherState":{"type":"","custom":"","value":"","field":"_billing_state"},"OtherPostalCode":{"type":"","custom":"","value":"","field":"_billing_postcode"},"OtherCountry":{"type":"","custom":"","value":"","field":"_billing_country"},"MailingStreet":{"type":"value","custom":"","value":"{_shipping_address_1} {_shipping_address_2}","field":"_shipping_address_2"},"MailingCity":{"type":"","custom":"","value":"","field":"_shipping_city"},"MailingState":{"type":"","custom":"","value":"","field":"_shipping_state"},"MailingPostalCode":{"type":"","custom":"","value":"","field":"_shipping_postcode"},"MailingCountry":{"type":"","custom":"","value":"","field":"_shipping_country"}}','feed'=>array('primary_key'=>'Email','update'=>'1'));

         
    $feeds_arr['Order']=array('fields'=>'{"EffectiveDate":{"type":"","custom":"","value":"{_order_date}+00:00","field":"_order_date"},"Status":{"type":"value","custom":"","value":"Draft","field":""},"BillingCity":{"type":"","custom":"","value":"","field":"_billing_city"},"BillingStreet":{"type":"value","custom":"","value":"{_billing_address_1} {_billing_address_2}","field":"_billing_address_2"},"BillingState":{"type":"","custom":"","value":"","field":"_billing_state"},"BillingCountry":{"type":"","custom":"","value":"","field":"_billing_country"},"ShippingStreet":{"type":"value","custom":"","value":"{_shipping_address_1} {_shipping_address_2}","field":"_shipping_address_2"},"ShippingCity":{"type":"","custom":"","value":"","field":"_shipping_city"},"ShippingState":{"type":"","custom":"","value":"","field":"_shipping_state"},"ShippingCountry":{"type":"","custom":"","value":"","field":"_shipping_state"},"Name":{"type":"value","custom":"","value":"woo-{_order_id}","field":"_order_id"},"BillingPostalCode":{"type":"","custom":"","value":"","field":"_shipping_postcode"},"ShippingPostalCode":{"type":"","custom":"","value":"","field":"_billing_postcode"}}','feed'=>array('primary_key'=>'Name')); 

  $module_json=$module;
    if( in_array($object,array('Quote'))){
    $module_json='Order';     
  }
   
  if(empty($map) && !empty($feeds_arr[$module_json])){
      $map=json_decode($feeds_arr[$module_json]['fields'],1); 
     if(!empty($feeds_arr[$module_json]['feed'])){
         foreach($feeds_arr[$module_json]['feed'] as $kk=>$vv){ 
     $feed[$kk]=$vv;  
         }
     }
  } 
//mapping fields
foreach($map as $field_k=>$field_v){
  if(isset($fields[$field_k])){
  $map_fields[$field_k]=$fields[$field_k];    
  }  
}

//
}else{
    $map_fields=$fields;
}
 
  //  $account_support=array('Contact','Opportunity','Contract','Order');       
    $camp_support=array("Lead","Contact");       
  //  $contract_support=array('Order');       
include_once(self::$path."templates/field-mapping.php");

  }
 /**
 * get object feeds
 *  
 * @param mixed $object
 */
public function get_object_feeds($object="",$account="",$skip_id=''){
        //get feeds of a form
if(empty($this->feeds)){
  $this->feeds= get_posts( array(
  'post_type'           => $this->id,
  'ignore_sticky_posts' => true,
  'nopaging'            => true,
  //'fields'              => 'ids',
  'post_status'         => 'any'
  ) );
}
  $object_feeds=array();

  if(is_array($this->feeds)){
    foreach($this->feeds as $post){
        $post_id=$post->ID;
    
 $meta=get_post_meta($post_id,$this->id.'_meta',true);
 $object_match=empty($object)  || $this->post('object',$meta) == $object;
 $account_match=empty($account) || $this->post('account',$meta) == $account;
 $feed_match=empty($skip_id) || $skip_id != $post_id;
if($object_match && $account_match && $feed_match ){    
        $object_feeds[$post_id]=$post->post_title;
 
}
    }  
  }

 return $object_feeds; 
  }
    /**
  * Get Objects list , Ajax method
  * 
  */
  public function get_objects_list(){
  check_ajax_referer("vx_crm_ajax","vx_crm_ajax"); 
  if(!current_user_can($this->id."_read_settings")){ 
   die(-1);  
 }
    $account=$this->post('account');
      $crm=$this->get_info($account); 

  $modules=$this->get_objects( $crm,true); 
  $html="<option value=''>".esc_html__("Select Object",'woocommerce-salesforce-crm')."</option>";
  $res=array();
  if(is_array($modules) && count($modules)>0){
  foreach($modules as $key=>$label){
  $html.="<option value='".esc_attr($key)."'>".esc_html($label)."</option>";     
  } 
  $res['html']=$html;
  }else{
      if(empty($modules)){
          $modules=__('No Objects Found','woocommerce-salesforce-crm');
      }
  $res['error']=$modules;   
  }
  echo json_encode($res);  die();
  }
  
  /**
  * Save crm feed
  * Send Order to crm from single order page
  * 
  * @param mixed $post
  */
  public function save_feed($post_id,$post){ 
 // global $post_id;
  $post_type=get_post_type($post);
  
  switch($post_type){
  
  case $this->id: 
  $this->save_feed_plugin();
  break;
  default: 
  if(in_array($post_type,array('shop_order','shop_subscription'))){
    /*$action=$this->post('action');
  if(in_array($action,array('trash','untrash'))){ //handle trash and untrash in related hook
      return;
  }*/
  $this->send_order_admin($post_id,$post_type);
  }
  break;
  }
  
  }
 public function send_order_admin($post_id,$post_type=''){
      if(self::$processing_feed){
    return;  
  }
  //if send to crm on updating order
 $send_to_sf_button=isset($_POST[$this->id.'_send']) && $_POST[$this->id.'_send'] == "yes";
 $admin_send_to_sf=false;
 if(!$send_to_sf_button){
 $meta=get_option($this->type.'_settings',array()); 
 if(isset($meta['update']) && $meta['update'] == 'yes' && !empty($_POST['save'])){
 $admin_send_to_sf=true;
 }
 }

 //var_dump($post_id,$post); die(); 
 $action= $send_to_sf_button ? '' : 'update';
  if($admin_send_to_sf || $send_to_sf_button){
      if($post_type == 'shop_subscription'){
          $sub=wcs_get_subscription($post);
       $res=$this->push($sub,'admin_sub');   
      }else{
           
          $res=$this->push($post_id,$action);
      }
  
  if($send_to_sf_button && !is_array($res)){
  $res=array("class"=>"error","msg"=>__("Nothing Posted to Salesforce",'woocommerce-salesforce-crm'));      
  }

  if(is_array($res)){
  update_option($this->id.'_msg',$res);

//  add_filter( 'redirect_post_location', array( $this, 'add_notice_query_var' ), 99 );
  }
//  
  } 
}
public function update_order($id){ 
$this->send_order_admin($id);
}
  /**
  * Define our custom columns shown in admin.
  * @param  string $column
  *
  */
  public function table_columns( $column ) {
  global $post, $woocommerce;
  $primary_key="";
  if(in_array($column,array("vxc_object","vxc_key"))){
  $meta=get_post_meta( $post->ID, $this->id.'_meta', true );  
  if($column == "vxc_key"){
  $meta=get_post_meta( $post->ID, $this->id.'_meta', true );   
 $primary_key=$this->post('primary_key',$meta);
 $fields=$this->post('fields',$meta);
  if(!empty($primary_key) && is_array($fields) && isset($fields[$primary_key]['label'])){
   $primary_key=$fields[$primary_key]['label'];   
  }
  if(empty($primary_key)){
    $primary_key= __("N/A",'woocommerce-salesforce-crm'); 
  }
  }
  if($column == "vxc_object"){
  if(!$this->objects){
  $this->objects=$this->get_objects("");
  } 
  $object=$this->post('object',$meta);
  if(isset($this->objects[$object])){
  $object=$this->objects[$object];
  }
  if(empty($object)){
      $object=__("No Object",'woocommerce-salesforce-crm');
  }    
  }
  }
  switch ( $column ) {
  case "vxc_object" :
  echo '<strong>' .esc_html($object). '</strong>';
  break;
  case "vxc_key" :
  echo esc_html($primary_key);
  break;
  }
  }
  /**
  * Define custom columns
  * @param  array $existing_columns
  * @return array
  */
  public function table_head( $existing_columns ) {
  $columns['cb']          = $existing_columns['cb'];
  $columns['title']          = $existing_columns['title'];
  $columns['vxc_object'] = __( 'Salesforce Object', 'woocommerce-salesforce-crm' );
  $columns['vxc_key'] = __( 'Primary Key', 'woocommerce-salesforce-crm' );
  $columns['date'] = $existing_columns['date'];
  
  return   $columns;
  }
  /**
  * Displays the crm feeds list page
  * 
  */
  public function log_page(){ 

   wp_enqueue_style('vxc-css');

    global $wpdb;
   $bulk_action=$this->post('bulk_action');
    $offset=$this->time_offset();
   $log_ids=array();
  if($bulk_action!=""){
   $log_id=$this->post('log_id');  
   $table=$this->get_table_name(); 
   if(is_array($log_id) && count($log_id)>0){
    foreach($log_id as $id){
     if(is_numeric($id)){
    $log_ids[]=(int)$id;     
     }   
    }
    if($bulk_action == "delete"){
       $count=0; 
  foreach($log_ids  as $id){
  $del=$wpdb->delete($table,array('id'=>$id),array( '%d' ));  
  if($del){$count++;}
  }
  $this->screen_msg('updated',sprintf(__('Successfully Deleted %d Item(s)','woocommerce-salesforce-crm'),$count));  
    }
    else if(in_array($bulk_action,array("send_to_crm_bulk","send_to_crm_bulk_force"))){
     self::$api_timeout='1000'; 
       foreach($log_ids  as $id){
  $sql = $wpdb->prepare("SELECT * FROM $table WHERE id=%d limit 1", $id);
  $log = $wpdb->get_row($sql, ARRAY_A); 
    if(is_array($log) && $log['order_id'] !=""){
        if(!empty($log['parent_id']) && in_array($log['event'],array('delete_note','add_note'))){
         $note=json_decode($log['data'],true);
         if(!empty($note['Body']['value'])){
         self::$note=array('id'=>$log['parent_id'],'title'=>$note['Title']['value'],'body'=>$note['Body']['value']); 
         }  
        }
        
    $push=$this->push($log['order_id'],$log['event'],$log); 
    
    if(is_array($push) && isset($push['class'])){
    $this->screen_msg($push['class'],$push['msg']); 
    }  }
  }   
    }
   
   }
    unset($_GET['bulk_action']);
    unset($_POST['log_id']);
    unset($_GET['vx_nonce']);
    $logs_link=admin_url('admin.php?'.http_build_query($this->clean($_GET)));
    //wp_redirect($logs_link);
    // die();
  }
  $times=array("today"=>"Today","yesterday"=>"Yesterday","this_week"=>"This Week","last_7"=>"Last 7 Days","last_30"=>"Last 30 Days","this_month"=>"This Month","last_month"=>"Last Month","custom"=>"Select Range"); 
  //links
  $settings_link=$this->link_to_settings();
  $feeds_link=admin_url('edit.php?post_type='.$this->id);
 
  $sql_end=$this->get_log_query();
  $sql_t="select count(s.id) as total $sql_end";
  $result= $wpdb->get_results($sql_t); 
  $items=$result[0]->total;    
  $per_page = 20;
  $start = 0;
  $pages = ceil($items/$per_page);
  if(isset($_GET['page_id']))
  {
  $page=$this->post('page_id');
  $start = $page-1;
  $start = $start*$per_page;
  }
  $start=max($start,0);   
  $sql = "SELECT s.id, s.status,s.object,s.parent_id, s.meta as meta,s.order_id,s.crm_id,s.link,s.time
  $sql_end
  limit $start , $per_page";
  $results = $wpdb->get_results($sql, ARRAY_A);                    
  $page_id=isset($_REQUEST['page_id'])&& $_REQUEST['page_id'] !="" ? $this->post('page_id') : "1";
  $range_min=(int)($per_page*($page_id-1))+1;
  $range_max=(int)($per_page*($page_id-1))+count($results);
  unset($_GET['page_id']);
  $query_h=$this->clean($_GET);$query_h=http_build_query($query_h);
  $page_links = paginate_links( array(
  'base' =>  admin_url("admin.php")."?".$query_h."&%_%" ,
  'format' => 'page_id=%#%',
  'prev_text' =>'&laquo;',
  'next_text' =>'&raquo;',
  'total' => $pages,
  'current' => $page_id,
  'show_all' => false
  ));
  //////////////
  $crm_order=$entry_order=$desc_order=$time_order="up"; 
  $crm_class=$entry_class=$desc_class=$time_class="vx_hide_sort";
  $order=$this->post('order');
  $order_icon= $order == "desc" ? "down" : "up";
  if(isset($_REQUEST['orderby'])){
  switch($_REQUEST['orderby']){
  case"crm_id": $crm_order=$order_icon;  $crm_class="";   break;    
  case"order_id": $entry_order=$order_icon; $entry_class="";    break;    
  case"object": $desc_order=$order_icon; $desc_class="";   break;    
  case"time": $time_order=$order_icon; $time_class="";   break;    
  }          
  }
 
  $bulk_actions=array(""=>__('Bulk Action','woocommerce-salesforce-crm'),"delete"=>__('Delete','woocommerce-salesforce-crm'),
  'send_to_crm_bulk'=>__('Send to Salesforce','woocommerce-salesforce-crm'),'send_to_crm_bulk_force'=>__('Force Send to Salesforce - Ignore Filters','woocommerce-salesforce-crm'));
    $statuses=array(
    "1"=>__('Created','woocommerce-salesforce-crm'),
    "2"=>__('Updated','woocommerce-salesforce-crm'),
    "error"=>__('Failed','woocommerce-salesforce-crm'),
    "4"=>__('Filtered','woocommerce-salesforce-crm'),
    "5"=>__('Deleted','woocommerce-salesforce-crm'),
    "3"=>__('Web2Lead','woocommerce-salesforce-crm')
    );
    $menu_links=array(
    array("title"=>__("Salesforce Settings",'woocommerce-salesforce-crm'),
    "link"=>$settings_link
    ),
    array("title"=>__("Salesforce Feeds",'woocommerce-salesforce-crm'),
    "link"=>$feeds_link
    ) );
  $menu_links=apply_filters('menu_links_'.$this->id,$menu_links);  
  $objects['objects']=$this->get_objects();

include_once(self::$path."templates/log-entries.php");
  }
  /**
  * Formats Log table row
  * 
  * @param mixed $row
  */
  public function verify_log($row,$objects=''){
  $crm_id=$link="N/A"; $desc="Added to ";
  $status_imgs=array("1"=>"created","5"=>"deleted","2"=>"updated","4"=>"filtered");
 if($objects == ''){
  $objects=$this->get_objects("");
 }
  if(isset($objects[$row['object']])){
      $row['object']=$objects[$row['object']];
  }
  if( !empty($row['status'])){
  $link="N/A"; 
  if($row['link'] !=""){
  $link='<a href="'.esc_url($row['link']).'" title="'.esc_html($row['crm_id']).'" target="_blank">'.esc_html($row['crm_id']).'</a>';
  $crm_id=$row['crm_id'];
  }   
  if($row['status'] == 2){
  $desc="Updated to ";    
  }
  if($row['status'] == 3){
  $row['status']=1; 
  $desc.=" Web2".$row['object'];
  }else   if($row['status'] == 4){
   $desc=sprintf(__('%s filtered','woocommerce-salesforce-crm'),$row['object']);   
  }else   if($row['status'] == 5){
   $desc=sprintf(__('%s deleted','woocommerce-salesforce-crm'),$row['object']);  
  }else{
  $desc.=$row['object'];
  }
  }else{
  $desc= !empty($row['meta']) ? $row['meta'] : "Unknown Error";
  }
  $row['status_img']=isset($status_imgs[$row["status"]]) ? $status_imgs[$row["status"]] : 'failed';
  $title=__("Failed",'woocommerce-salesforce-crm');   
  if( $row['status'] == 1){
  $title=__("Created",'woocommerce-salesforce-crm');   
  }else if($row['status'] == 2){
  $title=__("Updated",'woocommerce-salesforce-crm');   
  }else if($row['status'] == 4){
  $title=__("Filtered",'woocommerce-salesforce-crm');   
  }else if($row['status'] == 5){
  $title=__("Deleted",'woocommerce-salesforce-crm');   
  }else if($row['status'] == 6){
  $row['status_img']='created';  $title='Done';
  $desc= !empty($row['meta']) ? $row['meta'] : "Unknown Error";
  }
  $row['_crm_id']= $crm_id;
  $row['a_link']=$link;
  $row['desc']=$desc;
  $row['title']=$title;
  return $row;
  }
 /**
 * get order logs
 * 
 * @param mixed $order_id
 * @param mixed $limit
 */
  public function get_order_logs($order_id,$parent_logs=true,$limit=1){
      global $wpdb;
       $table_name = $this->get_table_name();
       $sql="Select * from  $table_name where ";
      if($parent_logs){
    $sql.='parent_id=0 and ';
}
$sql.=' order_id=%d order by id desc limit %d';
 $sql=$wpdb->prepare($sql,$order_id,$limit);
  return $wpdb->get_results($sql,ARRAY_A);
  }
  /**
  * Creates Log Query
  * 
  */
  public function get_log_query(){
  $search="";
  $table_name = $this->get_table_name();
  $sql_end="FROM $table_name s";
  // handle search
  $time_key=$this->post('time');
  $time=current_time('timestamp');
  
  $offset = $this->time_offset();
  $start_date=""; $end_date="";
  switch($time_key){
  case"today": $start_date=strtotime('today',$time);  break;
  case"this_week": $start_date=strtotime('last sunday',$time);  break;
  case"last_7": $start_date=strtotime('-7 days',$time);  break;
  case"last_30": $start_date=strtotime('-30 days',$time); break;
  case"this_month": $start_date=strtotime('first day of 0 month',$time);  break;
  case"yesterday": 
  $start_date=strtotime('yesterday',$time);
  $end_date=strtotime('today',$time);  

  break;
  case"last_month": 
  $start_date=strtotime('first day of -1 month',$time); 
  $end_date=strtotime('last day of -1 month',$time); 

  break;
  case"custom":
   
  if(!empty($_GET['start_date'])){
  $start_date=strtotime($this->post('start_date').' 00:00:00');
  }
   if(!empty($_GET['end_date'])){
  $end_date=strtotime($this->post('end_date').' 23:59:59');
   } 
  break;
  }
  
  if($start_date!=""){
      $start_date-=$offset;
  $search.=' and s.time >="'.date('Y-m-d H:i:s',$start_date).'"';   
  }
  if($end_date!=""){
        $end_date-=$offset;
      if($time_key == "yesterday"){
  $search.=' and s.time <"'.date('Y-m-d H:i:s',$end_date).'"';
      }else{
  $search.=' and s.time <="'.date('Y-m-d H:i:s',$end_date).'"';
      }   
  }
  if($this->post('object')!=""){
  $search.=' and object ="'.esc_sql($this->post('object')).'"';   
  }
  if($this->post('status')!=""){
  $status=$this->post('status');
  if($status == "all"){$status="0";}
  $search.=' and status ="'.esc_sql($status).'"';   
  }
  if($this->post('id')!=""){
  $search.=' and id="'.esc_sql($this->post('id')).'"';    
  }
  if($this->post('order_id')!=""){
  $search.=' and order_id="'.esc_sql($this->post('order_id')).'"';    
  }
  if($this->post('search')!=""){
  $search_s=esc_sql($this->post('search'));
  if(is_numeric($search_s)){
  $search.=' and (order_id="'.$search_s.'")';    
  }else{
  $search.=' and (object like "'.$search_s.'%" or crm_id="'.$search_s.'")';      
  }  
  }
  if($search!=""){
  $sql_end.=" where ".substr($search,4);
  }
  if($this->post('orderby')!=""){
  $sql_end.=' order by '.esc_sql($this->post('orderby'));   
  if($this->post('order')!="" && in_array($this->post('order'),array("asc","desc"))){
  $sql_end.=' '.$this->post('order'); 
  }
  }else{
  $sql_end.=" order by s.id desc";   
  }
  return $sql_end;
  }
  /**
  * validate API
  * 
  * @param mixed $info
  * @param mixed $force_check
  */
  public function validate_api($row,$check=false){
  $info=$this->post('data',$row);

  $time=current_time('timestamp'); 
 /* $check=$force_check; $auto_check=false;
  if(!$force_check && $api_check<$time){ //check validity period in settings tab
  $check=true; $auto_check=true;
  }//$check=true;  
  $check=true;*/
  if($check){
  $api=$this->get_api($row);
 /* if($auto_check){
  $api->timeout="5";
  } */
  if($this->post('api',$info) == "web"){
  $res=$api->post_web(array(),$info,'Lead',true);
  $info["_time"]=$time; //api validity check
  //////////////////
  if($res === NULL){
  $info['msg']=__('Invalid Org ID. Please check your Org ID and try again.','woocommerce-salesforce-crm'); 
  $info['class']="error";        
  }else{
  $info['msg']=__('Success: Your Org ID is properly configured','woocommerce-salesforce-crm'); 
  $info['class']="updated";
  }  
   // save web2lead settings 
  $this->update_info(array( 'data' => $info),$info['id']);     
     
  }
  }
  if($this->post('api',$info) != "web"){
  if($check){
     $res=$api->get_crm_objects(); 
  if(!empty($res) && is_string($res)){
  $info['error']=$res;   
  unset($info['access_token']); 
  }
  } 
  if(isset($info['access_token'])  && $info['access_token']!="") { 
  $msg=__( 'Successfully Connected to Salesforce','woocommerce-salesforce-crm' );
     if(isset($info['_time'])){
       $msg.=" - ".date('F d, Y h:i:s A',$info['_time']);
   }
      $info['msg']=$msg; 
  $info['class']="updated";     
  
  }else{
  $info['class']="";  
  if(isset($info['error']) && isset($info['sales_token_time']) && $info['error'] !=""){
  $info['msg']=$info['error']; 
  $info['class']="error"; 
  }       }
  }
  return $info;
  }
  /**
  * Log detail
  * 
  */
  public function log_detail(){
      check_ajax_referer("vx_crm_ajax","vx_crm_ajax"); 
  if(!current_user_can($this->id."_read_settings")){ 
   die();  
 }
        global $wpdb;
  $table= $this->get_table_name();
  $log_id=$this->post('id');
  $sql = $wpdb->prepare("SELECT * FROM $table WHERE id=%d limit 1", $log_id);
  $log = $wpdb->get_row($sql, ARRAY_A); 
  $data=json_decode($log['data'],true); 
  $response=json_decode($log['response'],true);
  $triggers=array('manual'=>'Submitted Manually'
  ,'submit'=>'Order Submission'
  ,'update'=>'Order Update'
  ,'restore'=>'Order Restore'
  ,'delete'=>'Order Deletion'
  ,'add_note'=>'Order Note Created'
  ,'delete_note'=>'Order Note Deleted'
  ,'processing'=>'Order Status Changed to Processing'
  ,'completed'=>'Order Status Changed to Complete'
  ,'user_created'=>'Account created on checkout page'
  );
  $event= empty($log['event']) ? 'manual' : $log['event'];
  $extra=array('Object'=>$log['object']);
  if(isset($triggers[$event])){
    $extra['Trigger']=$triggers[$event];  
  }else{
  $extra['Trigger']='Order Status Changed to '.$event;    
  }
  $extra_log=json_decode($log['extra'],true);
  if(is_array($extra_log)){
      $extra=array_merge($extra,$extra_log);
  }
  $error=true; 
  $vx_ops=$this->get_filter_ops();
  $labels=array("url"=>"URL","body"=>"Search Body","response"=>"Search Response","filter"=>"Filter",
  "camp_post"=>"Campaign Post","camp_res"=>"Campaign Response",'note_object_link'=>'Note Object Id');
  include_once(self::$path."templates/log-entry.php");
      die();
  }
  
  public function field_map_object_ajax(){
     check_ajax_referer("vx_crm_ajax","vx_crm_ajax"); 

  if(!current_user_can($this->id."_edit_settings")){ 
   die('-1');  
 }
  $this->ajax=true;
  $this->account=$account=$this->post('account');
     if(empty($account)){ //ajax error
  ?>
  <div class="alert_danger"><?php esc_html_e('Please Select a Salesforce Account','woocommerce-salesforce-crm')?></div>
  <?php
  die();
  }
  $this->post_id=$id=$this->post('id'); 
   $feed=get_post_meta($id,$this->id.'_meta',true);
   $info=$this->get_info($account);  
    
  $arr=$this->field_map_object($feed,$info);

  die($arr);    
  }

  //********************************plugin custom functions******************************************//
  /**
  * field mapping box's Contents
  * 
  */
  public function field_map_object($feed,$info) {
     
         $data=array();
  if(isset($info['data'])){
$data=$info['data'];
  }
  $api_type=$this->post('api',$data);

  //get objects from crm
  $objects=$this->get_objects($info); 
if(!is_array($feed)){ $feed=array(); }
  if(empty($feed['object'])){
      $feed['object']="";
  }
  if(!empty($feed['object']) && is_array($objects) && !isset($objects[$feed['object']])){
  $feed['object']="";     
  }  
  $modules=array(""=>__("Select Object",'woocommerce-salesforce-crm'));
  if(isset($objects) && is_array($objects)){
  foreach($objects as $k=>$v){
  $modules[$k]=$v;     
  }   
  } 
  $meta=$this->post('meta',$info);
   $tooltips=self::$tooltips ; 
 include_once(self::$path."templates/field-map-object.php");  
  } 
  /**
  * fields mapping meta box
  * 
  */
  public function fields_map_contents() { 

  wp_enqueue_script('vxc-tooltip');
  wp_enqueue_style('vxc-css');
  wp_enqueue_script('vxc-select2' );
  wp_enqueue_style('vxc-select2');
  global $post;
  
  $this->post_id=$post_id=$post->ID;
  $feed=get_post_meta($post->ID,$this->id.'_meta',true);
$accounts=$this->get_accounts(true);
    if(empty($feed)){ $feed=array('account'=>''); }
 if(!empty($feed['account'])){
 $this->account=$account=$feed['account'];
  $info=$this->get_info($feed['account']);   
 }
  $tooltips=self::$tooltips ; 

include_once(self::$path."templates/field-map-account.php");           
  }
  /**
  * Settings tab HTML.
  * 
  */
public function settings_tab(){

  if(!current_user_can($this->id."_read_settings")){ 
        return;   
       }
       global $current_section; 
  wp_enqueue_style('vxc-css');
  $offset=$this->time_offset();
  include_once(self::$path."templates/settings-common.php");
    
  $is_section=apply_filters('add_section_html_'.$this->id,false);

  if($is_section === true){
    return;
} 

  if($current_section == "vxc_uninstall"){
  if(!current_user_can($this->id."_uninstall")){ 
  $msg=__('You do not have permissions to uninstall','woocommerce-salesforce-crm');
  $this->display_msg('admin',$msg);
  return;   
  }
  
include_once(self::$path."templates/uninstall.php");
  return;  
  }
    
 $new_account_id=$this->get_new_account();
 $link=$this->link_to_settings(); 
 $new_account=$link."&id=".$new_account_id;
 $id=$this->post('id');
  if(!empty($id)){
  $info=$this->get_info($id);   
  
  if(!is_array($info) || !isset($info['id'])){
   $id="";   
  } }
  if(!empty($id)){
  $api=$this->get_api($info); 
  $client=$api->client_info();
  
  $force_check=false;
  if(isset($_POST['vx_test_connection']) || isset($_POST['crm'])){
    $force_check=true;  
  } 
  //verify connection

  $info=$this->validate_api($info,$force_check); 
  $nonce=wp_create_nonce("vx_nonce");
  $tooltips=self::$tooltips ; 
 
  $conn_class=$this->post('class',$info);
  if(!empty($conn_class)){
  $this->screen_msg($info['class'],$info['msg']);
  }
  if(isset($_POST['vx_test_connection'])){
  $msg=__('Connection to Salesforce is Working','woocommerce-salesforce-crm');
  
  if($conn_class != "updated" ){
      $msg=__('Connection to Salesforce is NOT Working','woocommerce-salesforce-crm');  
  }
  $title=__('Test Connection: ','woocommerce-salesforce-crm');
  $this->screen_msg($conn_class,'<b>'.$title.'</b>'.$msg);
  } 
    if(!empty($_GET['vx_debug'])){
  $this->screen_msg('error',json_encode($info)); 
} 

include_once(self::$path."templates/setting.php");
  }else{
        wp_enqueue_script('vxc-sorter');
      $accounts=$this->get_accounts();
      $meta=get_option($this->type.'_settings',array());

       if(!empty($_POST['save'])){ 
             if(current_user_can($this->id."_edit_settings")){ 

    $meta=isset($_POST['meta']) ? $this->post('meta') : array();

  update_option($this->type.'_settings',$meta);
  }      
      }
    
include_once(self::$path."templates/settings.php");
  }
  do_action('vx_plugin_upgrade_notice_'.$this->type);
  }
      /**
     * Get New Settings Id
     * @return int Settings id
     */
public function get_new_account() {
global $wpdb;
 $table= $this->get_table_name('accounts');
$results = $wpdb->get_results( 'SELECT * FROM '.$table.' where status=9 limit 1',ARRAY_A );
$id=0; 
if(count($results) == 0){
    $wpdb->insert($table,array("status"=>"9"));
    $id=$wpdb->insert_id;
}else{
$id=$results[0]['id'];   
}     
return $id;
}
/**
* delete account
* 
* @param mixed $id
*/
public function del_account($id) {
global $wpdb;
 $table= $this->get_table_name('accounts');
$res=$wpdb->delete( $table, array('id'=>$id) , array('%d'));
return $res;
}
      /**
     * Get all accounts
     */
public function get_accounts($verified=false) {
global $wpdb;
 $table= $this->get_table_name('accounts');
 $sql='SELECT * FROM '.$table.' where';
 if($verified){
 $sql.=' status =1';
 }else{
     $sql.=' status !=9';
 }
 $sql.=' limit 100';
$results = $wpdb->get_results( $sql ,ARRAY_A );
  return $results;   
}


  /**
  * Creates or updates database tables. Will only run when version changes
  * 
  */
  public function setup_plugin(){
  global $wpdb;
  if(isset($_REQUEST[$this->id.'_action']) && $_REQUEST[$this->id.'_action']=="get_code"){
    
   $part=array('code'=>'');
if(isset($_REQUEST['code'])){
$part['code']=$this->post('code');   
}
if(isset($_REQUEST['error'])){
$part['error']=$this->post('error');   
$part['error_description']=$this->post('error_description');   
}
$state=urldecode($this->post('state'));
$redir= html_entity_decode($state)."&".http_build_query($part);
wp_redirect($redir);
die();
  }
    if(isset($_REQUEST[$this->id.'_tab_action']) && $_REQUEST[$this->id.'_tab_action']=="del_account"){
 check_admin_referer('vx_nonce','vx_nonce');
 if( current_user_can($this->id."_edit_settings")){ 
$id=$this->post('id');
$res=$this->del_account($id);
 if($res){
       $msg=__('Account Deleted Successfully','woocommerce-salesforce-crm');
  $msg_arr=array('msg'=>$msg,'class'=>'updated');   
 }else{
       $msg=__('Error While Removing Account','woocommerce-salesforce-crm');
  $msg_arr=array('msg'=>$msg,'class'=>'error');      
 }
  update_option($this->id.'_msg',$msg_arr);
 }
  $redir=$this->link_to_settings();
wp_redirect($redir.'&'.$this->id.'_msg=1');
die();
  }
  if(isset($_REQUEST[$this->id.'_tab_action']) && $_REQUEST[$this->id.'_tab_action']=="get_token"){
  check_admin_referer('vx_nonce','vx_nonce');
  if(!current_user_can($this->id."_edit_settings")){ 
  $msg=__('You do not have permissions to add token','woocommerce-salesforce-crm');
  $this->display_msg('admin',$msg);
  return;   
  }
  $id=$this->post('id');
    $info=$this->get_info($id);
  $api=$this->get_api($info);
$info_data=$api->handle_code();

    //get objects after saving acces token
  $token=$this->post('access_token',$info_data);
  if(!empty($token)){
      $info['data']=$info_data;
    $res=$this->get_objects($info,true); 

    if(!empty($res) && is_string($res)){
    $info_data['error']=$res;
    unset($info_data['access_token']);
    $this->update_info(array('data'=>$info_data),$id);    
    } 
  }
  wp_redirect($this->link_to_settings('&id='.$id)); 
  die();  
  }
  self::$tooltips = array(
  'sel_object' =>  __('Select the Salesforce object you would like to add your contacts to.', 'woocommerce-salesforce-crm'),
  'map_fields' =>  __('Associate your Salesforce fields to the appropriate order form fields by selecting.', 'woocommerce-salesforce-crm'),
  'optin_condition' =>__('When custom filter is enabled, orders will only be exported to Salesforce when all conditions match. When disabled all orders will be exported.', 'woocommerce-salesforce-crm'),
  'manual_export' => __('Select which WooCommerce event will automatically export the orders into Salesforce. To manually send an order into Salesforce, go to Orders, select the order, and click on the "Send to Salesforce" button.','woocommerce-salesforce-crm'),
  'vx_web' => sprintf(__('Web-to-Lead is available for all Salesforce Editions. If you are not sure if your Salesforce Edition supports the API, you should use Web-to-Lead. Editions that do not support the Salesforce API: %s 1: Personal Edition %s 2: Group Edition %s 3: Professional Edition %s Note: You can purchase API access for a Professional Edition', 'woocommerce-salesforce-crm'),'<br/>','<br/>','<br/>','<br/>'),
  'vx_api' => sprintf(__('The API features are more powerful than Web-to-Lead. You can create different object types, as well as other advanced features. If you have any of the following Salesforce Editions %s 1: Enterprise Edition %s 2: Unlimited Edition %s 3: Developer Edition %s 4: Professional Edition - Requires API Upgrade', 'woocommerce-salesforce-crm'),'<br/>','<br/>','<br/>','<br/>','<br/>'),
  
  'vx_custom_app'=>__('This option is for advanced users who want to override default Salesforce App.','woocommerce-salesforce-crm'),
  
  'vx_disable_logs'=>__('When an order is sent to Salesforce we store that order information in the database and show it in the Salesforce Log. Check this box if you do not want to save the exported order information in the logs.','woocommerce-salesforce-crm'),
  
  'vx_line_items'=>__('Create a Salesforce Order product for each Woocommrce Cart item.','woocommerce-salesforce-crm'),
  
  'vx_price_books'=>__('Get PriceBooks list from salesforce.','woocommerce-salesforce-crm'),
  'vx_camps'=>__('Get Campaigns and Status list from salesforce.','woocommerce-salesforce-crm'),
  
  'vx_sel_price_book'=>__('Which Pricebook should be searched for product','woocommerce-salesforce-crm'),
  'vx_sel_camp'=>__('Which Campaign should be assigned to this object.','woocommerce-salesforce-crm'),
  'vx_sel_status'=>__('What should be Member Status.','woocommerce-salesforce-crm'),
  
  'vx_pro_desc'=>__('A new product will be created in selected Pricebook. You can add a description for new products created by this plugin.','woocommerce-salesforce-crm'),
  
   'vx_assign_account'=>__('Enable this option if you want to assign an account this object.','woocommerce-salesforce-crm'),
   'vx_sel_account'=>__('Object created by this feed will be assigned to the selected Account.','woocommerce-salesforce-crm'),
   
      'vx_assign_contract'=>__('Enable this option , if you want to assign a Contract to this object','woocommerce-salesforce-crm'),
   'vx_sel_contract'=>__('Select Contract feed. Contract created by this feed will be assigned to this object','woocommerce-salesforce-crm'),
   
   'vx_camp_check'=>__('If enabled, Lead/Contact will be added to selected Campaign','woocommerce-salesforce-crm'),
   'vx_owner_check'=>__('Enable this option if you want to assign another object owner.','woocommerce-salesforce-crm'),
   'vx_owners'=>__('Get Users list from Salesforce','woocommerce-salesforce-crm'),
   'vx_order_notes'=>__('Enable this option if you want to synchronize WooCommerce Order notes to Salesforce Object notes. For example, when you add a note to a WooCommerce Order, it will be added to the Salesforce Object selected in the feed. To turn this on, you must also turn this on in the Salesforce tab under Settings','woocommerce-salesforce-crm'),
   'vx_sel_owner'=>__('Select a user as a owner of this object','woocommerce-salesforce-crm'),
   'vx_entry_note'=>__('Check this option if you want to send more data as CRM entry note','woocommerce-salesforce-crm'),
   'vx_note_fields'=>__('Select fields which you want to send as a note','woocommerce-salesforce-crm'),
   'vx_disable_note'=>__('Enable this option if you want to add note only for new CRM entry','woocommerce-salesforce-crm')
   
  );

  
  }
  
  /**
  * Save crm feed
  * Send Order to crm from single order page
  * 
  * @param mixed $post
  */
  public function save_feed_plugin(){
  
  global $post_id,$post_type;
  
  
  if(isset($_POST['meta'])){  
  $post=$this->post('meta'); 
  $account=$this->post('account',$post);
 $meta_post=get_post_meta($post_id,$this->id.'_meta',true);
$fields=array("fields"=>$this->post('fields',$meta_post));
  if(isset($_POST['meta']['object']) && $_POST['meta']['object']!=""){ //if saving new post and object selected
   $info=$this->get_info($account);
  $meta=$this->post('meta',$info);
  if(!empty($meta['post_id']) && isset($meta['fields'])  && $meta['post_id'] == $post_id && $_POST['meta']['object'] == $meta['object']){

  $fields["fields"]=$meta['fields'];
  unset($meta["post_id"]);
  $this->update_info(array("meta"=>$meta),$account);  

  }         }
  $post=array_merge($fields,$post);     
  update_post_meta($post_id,$this->id.'_meta',$post);
  
  }
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
  $post_id=$this->post('post_id');
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
    if(is_array($camps)){
    $res['status']="ok";
    $data['crm_sel_camp']=$this->gen_select($camps,$status_sel,__('Select Campaign','woocommerce-salesforce-crm'));
    $meta['campaigns']=$camps;  
    
    }else{
         $res['error']=$camps; 
    }
    if(is_array($status_list)){
    $res['status']="ok";
    $data['crm_sel_status']=$this->gen_select($status_list,$camp_id_sel,__('Select Status','woocommerce-salesforce-crm'));
    $meta['member_status']=$status_list; 
    }else{
  
       $res['error']=$status_list;  
    }

  $res['data']=$data;   
      break;   
  case"refresh_users":
    $users=$api->get_users(); 
    
    $data=array();
    if(is_array($users)){
    $res['status']="ok";
    $data['crm_sel_user']=$this->gen_select($users,$owner_sel,__('Select User','woocommerce-salesforce-crm'));
    $meta['users']=$users;   
    }else{
     $res['error']=$users;   
    }

  $res['data']=$data;   
      break;
      case"refresh_books":
    $books=$api->get_price_books(); 
      $data=array();
    if(is_array($books)){
    $res['status']="ok";
    $data['crm_sel_book']=$this->gen_select($books,$owner_sel,__('Select Price Book','woocommerce-salesforce-crm'));
    $meta['price_books']=$books;   
    $meta['standard_book']=key($books);   
    }else{
     $res['error']=$books;   
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
    $res['error']=__('Unknown Error','woocommerce-salesforce-crm');
    }
}
  die(json_encode($res));    
  }
  /**
  * update plugin settings
  * 
  */
  public function update_settings_plugin(){

  if(isset($_POST['crm']) && isset($_POST['save'])){ 
      $id=$this->post('id');
  $info=$this->get_info($id);
  $crm=isset($info['data']) ? $info['data'] :array();
  $crm_p=is_array($_POST['crm']) ? $this->post('crm') : array();
  $crm=array_merge($crm,$crm_p);
  $crm['disable_log']=$this->post('disable_log',$crm_p);
  $crm['custom_app']=$this->post('custom_app',$crm_p);
  if($this->post('api',$crm)!="web"){
    $valid_email=true;
  if($this->post('error_email',$crm_p) !=""){
   $emails=explode(",",$this->post('error_email',$crm_p));
  foreach($emails as $email){
      $email=trim($email);
    if($email !="" && !$this->is_valid_email($email)){
  $valid_email=false; 
    }  
  }   
  }
  if(!$valid_email){
      $this->screen_msg('error',__('Invalid Email(s)','woocommerce-salesforce-crm'));
  }
  $custom_app=$this->post('custom_app',$crm);
  if(!empty($custom_app)){
   $app_id=$this->post('app_id',$crm);   
   $app_secret=$this->post('app_secret',$crm);   
   $app_url=$this->post('app_url',$crm);   
  $msg="";
  if(empty($app_id)){
  $msg=__('Please Enter APP ID','woocommerce-salesforce-crm');    
  }
  if(empty($app_secret)){
  $msg=__('Please Enter APP Secret','woocommerce-salesforce-crm');    
  }
    if(empty($app_url)){
  $msg=__('Please Enter APP URL','woocommerce-salesforce-crm');    
  }
    if(!empty($msg)){
      $this->screen_msg('error',$msg);
  }
  $client_id=$this->post('client_id',$crm);
  if( !empty($client_id) && $client_id!= $this->post('app_id',$crm)){
   $crm['access_token']="";   
   $crm['refresh_token']="";   
  }
  }
  }    
  //WC_Admin_Settings::add_message($message); 
  //verify connection
 // $crm=$this->validate_api($crm,true);
 // $name=$this->post('name',$crm);

  $sql=array('data'=>$crm,'time'=>''); 
   $this->update_info($sql,$id,true); 
  
  }
}
}
}
new vxc_pages_sales();