<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'vx_crmperks_cf' )):
class vx_crmperks_cf{
               public $plugin_url="https://www.crmperks.com/";
public function __construct(){
  //Add meta boxes
add_action( 'vx_cf_add_meta_box', array($this,'add_meta_box'),10,2 );  
}
public function addons_key(){
       $key='';
    if(class_exists('vxcf_addons')){
        $key=vxcf_addons::addons_key();
    }
   return $key;  
}
   public function get_pro_domain(){
     global $vx_wc,$vx_cf,$vx_gf,$vx_all;
    $domain=''; $class='';
     if(!empty($vx_cf)  && is_array($vx_cf)){
    $class=key($vx_cf);     
     }else if(!empty($vx_gf) && is_array($vx_gf)){
    $class=key($vx_gf);     
     }else if(!empty($vx_wc) && is_array($vx_wc)){
    $class=key($vx_wc);     
     }else if(!empty($vx_all) && is_array($vx_all)){
    $class=key($vx_all);     
     }
     global ${$class}; 
  return   ${$class}->domain;
 }

/**
* Add Customer information box
*   
*/
public function add_meta_box($lead,$detail){
    $lead_id=isset($lead['id']) ? $lead['id'] : ""; 
?>
  <div class="vx_div">
        <div class="vx_head">
<div class="crm_head_div"><i class="fa fa-bullhorn"></i> <?php esc_html_e('Marketing Data', 'cf7-pipedrive'); ?></div>
<div class="crm_btn_div" title="<?php esc_html_e('Expand / Collapse','cf7-pipedrive') ?>"><i class="fa crm_toggle_btn vx_action_btn fa-minus"></i></div>
<div class="crm_clear"></div> 
  </div>

  <div class="vx_group">
<?php
 $access=$this->addons_key(); 
 if(empty($access) ){
     $plugin_url=$this->plugin_url.'?vx_product='.$this->get_pro_domain(); 
 ?>
<div class="vx_panel" style="text-align: center; font-size: 16px; color: #888; font-weight: bold;">
<p><?php esc_html_e('Need Marketing Insight? ,','cf7-pipedrive')?> <a href="<?php echo esc_url($plugin_url) ?>&section=vxc_premium"><?php esc_html_e('Go Pro!','cf7-pipedrive')?></a></p>
</div>
 <?php
 }else{
 $html_added=apply_filters('vx_addons_meta_box',false,$lead_id,'cf');

if(!$html_added){
   ?> 
   <h3 style="text-align: center;"><?php esc_html_e('No Information Available','cf7-pipedrive')?></h3>
   <?php
}
 }
?>

  </div>
  </div>
<?php  
}
  
}
$addons=new vx_crmperks_cf();
///$addons->init_premium();
endif;
