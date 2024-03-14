<?php
if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }                                            
 ?> 
 <div class="vxa_entry">
  <div>
<div class="crm_panel crm_panel_50">
<div class="crm_panel_head">
<div class="crm_head_div"><span class="crm_head_text"><?php esc_html_e('Data Sent','woocommerce-salesforce-crm') ?></span></div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn fa-minus" title="<?php esc_html_e('Expand / Collapse','woocommerce-salesforce-crm'); ?>"></i></div>
<div class="crm_clear"></div>
</div>
<div class="crm_panel_content crm-block-content">
<?php

  if(is_array($data) && count($data)>0){    
    foreach($data as $k=>$v){ 
 $label=isset($v['label']) ? $v['label'] : $k; 
 $value=$v;
 if(is_array($v) && isset($v['value'])){
  $value=$v['value'];   
 }
 if(is_array($value)){
   $value=json_encode($v);  
 }     
?>
<div class="entry_row">
<div class="entry_col1 vx_label"><span title="<?php echo esc_attr($k) ?>"><?php echo esc_html($label) ?></span></div>
<div class="entry_col2"><?php echo is_array($value) ? esc_html(json_encode($value)) : esc_html($value) ?></div>
<div class="crm_clear"></div>
</div>
<?php
    }
  }else{
      ?>
  <div class="vx_error"><i class="fa fa-warning"></i> <?php esc_html_e('Nothing Posted to Salesforce','woocommerce-salesforce-crm');?></div>      
      <?php
  }
?>
</div></div>
      <?php
if(is_array($response) && count($response)>0){
           $error=false;
      ?>
        <div class="crm_panel crm_panel_50">
<div class="crm_panel_head">
<div class="crm_head_div"><span class="crm_head_text"><?php esc_html_e('Salesforce Response','woocommerce-salesforce-crm'); ?></span></div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn fa-minus" title="<?php esc_html_e('Expand / Collapse','woocommerce-salesforce-crm'); ?>"></i></div>
<div class="crm_clear"></div>
</div>
<div class="crm_panel_content crm-block-content">
<?php
    foreach($response as $k=>$v){
?>
<div class="entry_row">
<div class="entry_col1 vx_label"><?php echo esc_html($k) ?></div>
<div class="entry_col2"><?php echo is_array($v) ? esc_html(json_encode($v)) : esc_html($v) ?></div>
<div class="crm_clear"></div>
</div>
<?php
    }
?>
</div></div>
      <?php
  } 
if(is_array($extra) && count($extra)>0){
    $detail_class=!$error ? 'crm_panel_100' : 'crm_panel_50'; 
           $error=false;
      ?>
        <div class="crm_panel <?php echo esc_attr($detail_class) ?>">
<div class="crm_panel_head">
<div class="crm_head_div"><span class="crm_head_text"><?php esc_html_e('More Detail','woocommerce-salesforce-crm') ?></span></div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn fa-minus" title="<?php esc_html_e('Expand / Collapse','woocommerce-salesforce-crm'); ?>"></i></div>
<div class="crm_clear"></div>
</div>
<div class="crm_panel_content crm-block-content">
<?php
    foreach($extra as $k=>$v){
?>
<div class="entry_row">
<div class="entry_col1 vx_label"><?php echo isset($labels[$k]) ? esc_html($labels[$k]) : esc_html($k) ?></div>
<div class="entry_col2"><?php
if($k == "filter"){
 if(is_array($v)){
     $or_count=0;
     foreach($v as $or){
         if(is_array($or)){
             if($or_count>0){ echo "<div class='vx_or '>OR</div>";}
             $or_count++;
             $and_count=0;
             foreach($or as $con){
              $con['field']=$this->get_wc_field_label($con['field']);   
             $icon="times"; $color="vx_red";
            if($con['check'] == "true"){
             $icon="check"; $color="vx_green";   
            }
            $op=isset($vx_ops[$con['op']]) ? $vx_ops[$con['op']] : $con['op'];
              if($and_count>0){ echo "<div class='vx_or'>AND</div>";}
            echo "<i class='fa fa-".esc_attr($icon)."  vx_left_2'></i> ".esc_attr($con['field'])." ( <span class='vx_val'>".esc_attr($con['input'])."</span> ) <code>".esc_attr($op)."</code> <span class='vx_val'>".esc_attr($con['field_val'])."</span>";
             $and_count++;
             }
         }
     }
 }   
}else{
 echo is_array($v) ? esc_html(json_encode($v)) : esc_html($v);
} ?></div>
<div class="crm_clear"></div>
</div>
<?php
    }
?>
</div></div>
      <?php
  }
 ?> 
<div style="clear: both"></div>
 </div>
<div class="vx_log_detail_footer">
<?php
    if($log['feed_id']!=""){
?>
<a href="<?php echo esc_url(add_query_arg(array('post'=>$log['feed_id'],'action'=>'edit'), admin_url('post.php'))); ?>" title="<?php esc_html_e('Edit Feed','woocommerce-salesforce-crm') ?>" class="button"><i class="fa fa-edit"></i> <?php esc_html_e('Edit Feed','woocommerce-salesforce-crm') ?></a>
<?php
    }
?>
<a href="<?php echo esc_url(admin_url( 'admin.php?page='.$this->id.'_log&id='.$log['id'])); ?>" title="<?php esc_html_e('Open in New Window','woocommerce-salesforce-crm') ?>" target="_blank" class="button"><i class="fa fa-external-link"></i> <?php esc_html_e('Open in New Window','woocommerce-salesforce-crm') ?></a>
<a href="#" class="button vx_close_detail" title="<?php esc_html_e('Close','woocommerce-salesforce-crm') ?>"><i class="fa fa-times"></i> <?php esc_html_e('Close','woocommerce-salesforce-crm') ?></a>

</div> 
</div>