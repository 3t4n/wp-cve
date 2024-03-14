<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'vx_crmperks_notice_vxcf_googlesheets' )):

class vx_crmperks_notice_vxcf_googlesheets extends vxcf_googlesheets{
    
public $plugin_url="https://www.crmperks.com";
public $review_link='https://wordpress.org/support/plugin/integration-for-contact-form-7-and-google-sheets/reviews/?filter=5#new-post';
public $option='vxcf_form';

public function __construct(){

add_filter('vx_plugin_tabs_'.$this->id, array($this, 'add_section_cf'),99);
add_filter( 'plugin_row_meta', array( $this , 'pro_link' ), 10, 2 );
$slug=$this->get_slug();
//add_action( 'after_plugin_row_'.$slug, array( $this, 'plugin_msgs' ),99,3 );
add_action( 'wp_ajax_review_dismiss_'.$this->id, array( $this, 'review_dismiss' ) );
//license - settings tab
add_action('add_section_'.$this->id, array($this, 'notice'),99);
//accounts section
add_action('vx_plugin_upgrade_notice_'.$this->type, array($this, 'notice'),99);
//edit feed
add_action('vx_plugin_upgrade_notice_plugin_'.$this->type, array($this, 'notice'),99);
//free plugin notice in accounts page
add_action( 'vx_plugin_upgrade_notice_'.$this->type, array( $this, 'install_forms_notice_accounts' ),30 );

 if(isset($_GET['page']) && $_GET['page'] == $this->id ){
add_filter( 'admin_footer_text', array( $this, 'admin_footer' ), 1, 2 );
//install forms
add_action( 'admin_notices', array( $this , 'install_forms_notice' ) );
 }
add_filter( 'plugins_api', array( $this, 'forms_info' ), 11, 3 ); 
}
public function install_forms_notice_accounts(){

if(!empty($_GET['page']) && $_GET['page'] == $this->id){

$plugin_file='contact-form-entries/contact-form-entries.php';      
if(!class_exists('vxcf_form') && !file_exists(WP_PLUGIN_DIR.'/'.$plugin_file)) {
$plugin_msg='';
$link=wp_nonce_url($this->link_to_settings().'&cfx_form_dissmiss_notice=true','vx_nonce');

  if(file_exists(WP_PLUGIN_DIR.'/'.$plugin_file)) {
$url=admin_url("plugins.php?action=activate&plugin=$plugin_file");      
$url=wp_nonce_url( $url , "activate-plugin_{$plugin_file}"); 
$plugin_msg=__('Activate','crm-perks-forms');
}else{
$url=admin_url("update.php?action=install-plugin&plugin=$plugin_file");
$url=wp_nonce_url( $url, "install-plugin_$plugin_file");  
$plugin_msg=__('Install','crm-perks-forms');  
} 
$msg =sprintf(__('Want to save contact form 7 submissions? Manage contact form 7 entries, View and Resend any entry to Google Sheets with free %sConatct Form Entries Plugin%s','crm-perks-forms'),'<a href="https://wordpress.org/plugins/contact-form-entries/" target="_blank">','</a>');
?>
<style type="text/css">
.vx_pro_version .fa{
color: #727f30; font-size: 18px; vertical-align: middle;   
}  
</style>
<div class="notice-warning notice is-dismissible below-h2 vx_pro_version">
<p><i class="fa fa-check"></i> Save Contact Form 7 Submissions in Wordpress.</p>
<p><i class="fa fa-check"></i> View Google Sheets status(sent or not sent) of any entry.</p>
<p><i class="fa fa-check"></i> Easily Resend any entry to Google Sheets.</p>
<p><a href="<?php echo esc_url($url) ?>" style="font-weight: bold;"><?php esc_html_e('Install 100% free Contact Form Entries Plugin','crm-perks-forms') ?></a> </p>
</div>
<?php
}
  } 
  
}
public function install_forms_notice(){

if(!empty($_GET['page']) && $_GET['page'] == $this->id){

 if(!empty($_GET['cfx_form_dissmiss_notice'])){
        check_admin_referer('vx_nonce');
   update_option('cfx_form_install_entries_notice','true',false);
    }
    
$show=get_option('cfx_form_install_entries_notice');
if(empty($show)){        
//var_dump($link);
if(!class_exists('vxcf_form')) {
    $plugin_file='contact-form-entries/contact-form-entries.php';
$plugin_msg='';
$link=wp_nonce_url($this->link_to_settings().'&cfx_form_dissmiss_notice=true','vx_nonce');

  if(file_exists(WP_PLUGIN_DIR.'/'.$plugin_file)) {
$url=admin_url("plugins.php?action=activate&plugin=$plugin_file");      
$url=wp_nonce_url( $url , "activate-plugin_{$plugin_file}"); 
$plugin_msg=__('Activate Plugin','crm-perks-forms');
}else{
$url=admin_url("update.php?action=install-plugin&plugin=$plugin_file");
$url=wp_nonce_url( $url, "install-plugin_$plugin_file");  
$plugin_msg=__('Install Plugin','crm-perks-forms');  
} 
$msg =sprintf(__('Want to save contact form 7 submissions? Manage contact form 7 entries, View and Resend any entry to Google Sheets with free %sConatct Form Entries Plugin%s','crm-perks-forms'),'<a href="https://wordpress.org/plugins/contact-form-entries/" target="_blank">','</a>');
?>
<div class="notice-warning settings-error notice is-dismissible below-h2" style="font-weight: bold">
<p><?php echo wp_kses_post($msg); ?></p>
<p><a href="<?php echo esc_url($url) ?>"><?php echo esc_html($plugin_msg); ?></a> | <a href="<?php echo esc_url($link); ?>"><?php esc_html_e('Dismiss this notice','crm-perks-forms'); ?></a></p>
</div>
<?php
}
  } }
  
} 

public function forms_info( $data, $action = '', $args = null ) {
 $plugin=isset($_REQUEST['plugin']) ? sanitize_text_field($_REQUEST['plugin']) : '';
$slug = isset( $args->slug ) ? $args->slug : $plugin;   
if($slug == 'contact-form-entries/contact-form-entries.php'){
   $arr=new stdClass();
   $arr->download_link='https://downloads.wordpress.org/plugin/contact-form-entries.zip';  
return $arr;
} 
return $data;
}

  /**
  * display plgin messages
  * 
  * @param mixed $type
  */
public function plugin_msgs($file,$data,$status){
    $plugin_url=$this->plugin_url();
    $message=__('This plugin has Premium add-ons and many powerful features.','contact-form-entries');
    $message.=' <a href="'.esc_url($plugin_url).'" target="_blank" style="font-color: #fff; font-weight: bold;">'.esc_html__('Go Premium','contact-form-entries').'</a>';
?>
  <tr class="plugin-update-tr"><td colspan="5" class="plugin-update">
  <style type="text/css"> .vx_msg a{color: #fff; text-decoration: underline;} .vx_msg a:hover{color: #eee} </style>
  <div style="background-color: rgba(224, 224, 224, 0.5);  padding: 5px; margin: 0px 10px 10px 28px "><div style="background-color: #d54d21; padding: 5px 10px; color: #fff" class="vx_msg"> <span class="dashicons dashicons-info"></span> <?php echo wp_kses_post($message) ?>
</div></div></td></tr>
<?php 
}
  
public function review_dismiss(){
$install_time=get_option($this->id."_install_data");
if(!is_array($install_time)){ $install_time =array(); }
$install_time['review_closed']='true';
update_option($this->id."_install_data",$install_time,false);
die();
}
public function admin_footer($text) {
    if(isset($_GET['page']) && $_GET['page'] == $this->id ){
$text=sprintf(esc_html__( 'if you enjoy using %s, please %s leave us a %s rating%s. A %shuge%s thank you in advance.','contact-form-entries'),'<b>'.esc_html(self::$title).'</b>','<a href="'.esc_url($this->review_link).'" target="_blank" rel="noopener noreferrer">','&#9733;&#9733;&#9733;&#9733;&#9733;','</a>','<b>','</b>');
} return $text;
}
public function review_notice() { 
 $install_time=get_option($this->id."_install_data");
   if(!is_array($install_time)){ $install_time =array(); }
   if(empty($install_time['time'])){
       $install_time['time']=current_time( 'timestamp' , 1 );
      update_option($this->id."_install_data",$install_time,false); 
   }
 //  $install_time['review_closed']='';
    $time=current_time( 'timestamp' , 1 )-(DAY_IN_SECONDS*2);
 if(!empty($install_time) && is_array($install_time) && !empty($install_time['time']) && empty($install_time['review_closed'])){
   $time_i=(int)$install_time['time'];
    if($time > $time_i){ 
        ?>
        <div class="notice notice-info is-dismissible vxcf-review-notice" style="margin: 14px 0 -4px 0">
  <p><?php echo sprintf(esc_html__( 'You\'ve been using Contact Form 7 googlesheets Plugin for some time now; we hope you love it!.%s If you do, please %s leave us a %s rating on WordPress.org%s to help us spread the word and boost our motivation.','contact-form-entries'),'<br/>','<a href="'.esc_url($this->review_link).'" target="_blank" rel="noopener noreferrer">','&#9733;&#9733;&#9733;&#9733;&#9733;','</a>'); ?></p>
  <p><a href="<?php echo esc_url($this->review_link) ?>"  target="_blank" class="vxcf_close_notice_a" rel="noopener noreferrer"><?php esc_html_e('Yes, you deserve it','contact-form-entries') ?></a> | <a href="#" class="vxcf_close_notice_a" id="vxcf_close_notice_a"><?php esc_html_e('Dismiss this notice','contact-form-entries'); ?></a></p>
        </div>
        <script type="text/javascript">
            jQuery( document ).ready( function ( $ ) {
                $( document ).on( 'click', '.vxcf_close_notice_a', function ( e ) {
                      // e.preventDefault(); 
                       $('.vxcf-review-notice .notice-dismiss').click();
 //$.ajax({ type: "POST", url: ajaxurl, async : false, data: {action:"vxcf_form_review_dismiss"} });          
        $.post( ajaxurl, { action: 'review_dismiss_<?php echo esc_attr($this->id) ?>' } );
                } );
            } );
        </script>
        <?php
    } }
}    
  
public function add_section_cf($tabs){
$tabs["vxc_notice"]=array('label'=>__('Go Premium','contact-form-entries'),'function'=>array($this, 'free_plugins_notice'));
        

return $tabs;
}

public function notice(){
$url=$this->get_base_url();
$plugin_url=$this->plugin_url(); 
$this->review_notice(); 
?>
<style type="text/css">
.vx_pro_version .fa{
color: #727f30; font-size: 18px; vertical-align: middle;   
}    
</style>
<div class="updated below-h2 vx_pro_version" style="border-left-color: #1192C1; margin: 30px 20px 30px 0px">
<h2>Premium Version</h2>
<p><i class="fa fa-check" style="color: #727f30; font-size: 18px; vertical-align: middle;"></i> Contact Form 7 File upload fields.</p>
<p><i class="fa fa-check" style="color: #727f30; font-size: 18px; vertical-align: middle;"></i> Contact Form 7 Phone Number fields.</p>
<p><i class="fa fa-check" style="color: #727f30; font-size: 18px; vertical-align: middle;"></i> Create Unlimited Google Sheets feeds.</p>
<p><i class="fa fa-check"></i> 20+ addons.</p>

<p>By purchasing the premium version of the plugin you will get access to advanced marketing features and you will get one year of free updates & support</p>
<p>
<a href="<?php echo esc_url($plugin_url) ?>" target="_blank" class="button-primary button">Go Premium</a>
</p>
</div>

<?php  
return;
}
public function plugin_url() {
  return  $this->plugin_url.'?vx_product='.$this->domain;
} 
public function wp_id() { 
$id='';
if(function_exists('wp_get_theme')){  
$theme=wp_get_theme(); 
if(property_exists($theme,'stylesheet')){
$id=md5($theme->stylesheet);}
}
return $id;
}
public function pro_link($links,$file){
    $slug=$this->get_slug();
    if($file == $slug){
    $url=$this->plugin_url();
    $links[]='<a href="'.esc_url($url).'"><b>Go Premium</b></a>';
    }
   return $links; 
}
public function free_plugins_notice(){
    $this->notice();
?>
<div class="updated below-h2" style="border: 1px solid  #1192C1; border-left-width: 6px; padding: 5px 12px;">
<h3>Our Other Free Plugins</h3>

<p><b><a href="https://wordpress.org/plugins/contact-form-entries/" target="_blank">Contact Form Entries</a></b> saves contact form submissions from all popular contact forms(contact form 7 , crmperks forms, ninja forms, Gravity forms etc) into database.</p>

<p><b><a href="https://wordpress.org/plugins/crm-perks-forms/" target="_blank">CRM Perks Forms</a></b> is lightweight and highly optimized contact form builder with Poups and floating buttons.</p>

<p><b><a href="https://wordpress.org/plugins/support-x/" target="_blank">Support X - Wordpress Helpdesk</a></b> Shows user tickets from HelpScout, googlesheets, FreshDesk, Desk.com and Teamwork in wordpress. Users can create new tickets and reply to old tickets from wordpress.</p>

<p><b><a href="https://wordpress.org/plugins/wp-gravity-forms-spreadsheets/" target="_blank">Gravity Forms Google Sheets Plugin</a></b> allows you to quickly integrate Gravity Forms with Google Sheets.</p>

</div>
<?php    
}



}
new vx_crmperks_notice_vxcf_googlesheets();
endif;
