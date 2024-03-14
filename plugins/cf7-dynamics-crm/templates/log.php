<?php
if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }                                            
 ?> 
 <div class="vxa_entry">
  <div>
<div class="crm_panel crm_panel_50">
<div class="crm_panel_head">
<div class="crm_head_div"><span class="crm_head_text"><?php _e('Data Sent','contact-form-dynamics-crm') ?></span></div>
<div class="crm_btn_div" title="<?php _e('Expand / Collapse','contact-form-dynamics-crm'); ?>"><i class="fa crm_toggle_btn fa-minus"></i></div>
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
<div class="entry_col1 vx_label"><span title="<?php echo esc_attr($k) ?>"><?php echo $label ?></span></div>
<div class="entry_col2"><?php echo is_array($value) ? json_encode($value) : $value ?></div>
<div class="crm_clear"></div>
</div>
<?php
    }
  }else{
      ?>
  <div class="vx_error"><i class="fa fa-warning"></i> <?php _e('Nothing Posted to Dynamics','contact-form-dynamics-crm');?></div>      
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
<div class="crm_head_div"><span class="crm_head_text"><?php _e('Dynamics Response','contact-form-dynamics-crm') ?></span></div>
<div class="crm_btn_div" title="<?php _e('Expand / Collapse','contact-form-dynamics-crm'); ?>"><i class="fa crm_toggle_btn fa-minus"></i></div>
<div class="crm_clear"></div>
</div>
<div class="crm_panel_content crm-block-content">
<?php
    foreach($response as $k=>$v){
?>
<div class="entry_row">
<div class="entry_col1 vx_label"><?php echo esc_attr($k) ?></div>
<div class="entry_col2"><?php echo is_array($v) ? json_encode($v) : $v ?></div>
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
        <div class="crm_panel <?php echo $detail_class ?>">
<div class="crm_panel_head">
<div class="crm_head_div"><span class="crm_head_text"><?php _e('More Detail','contact-form-dynamics-crm') ?></span></div>
<div class="crm_btn_div" title="<?php _e('Expand / Collapse','contact-form-dynamics-crm'); ?>"><i class="fa crm_toggle_btn fa-minus"></i></div>
<div class="crm_clear"></div>
</div>
<div class="crm_panel_content crm-block-content">
<?php
    foreach($extra as $k=>$v){
?>
<div class="entry_row">
<div class="entry_col1 vx_label"><?php echo isset($labels[$k]) ? $labels[$k] : $k ?></div>
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
              $con['field']=$this->get_gf_field_label($form_id,$con['field']);   
             $icon="times"; $color="vx_red";
            if($con['check'] == "true"){
             $icon="check"; $color="vx_green";   
            }
            $op=isset($vx_ops[$con['op']]) ? $vx_ops[$con['op']] : $con['op'];
              if($and_count>0){ echo "<div class='vx_or'>AND</div>";}
            echo "<i class='fa fa-".$icon."  vx_left_2'></i> ".$con['field']." ( <span class='vx_val'>".$con['input']."</span> ) <code>".$op."</code> <span class='vx_val'>".$con['field_val']."</span>";
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
 if($error){
  ?>
<div class="vx_error"><i class="fa fa-warning"></i> <?php _e('Detail Not Found','contact-form-dynamics-crm');?></div>  
  <?php   
 } ?>
 <div style="clear: both"></div>
 </div>
 <div class="vx_log_detail_footer">
<?php
    if($log['feed_id']!=""){
        $feed_link=$this->get_feed_link($log['feed_id'],$log['form_id']);
?>
<a href="<?php echo $feed_link ?>" title="<?php _e('Edit Feed','contact-form-dynamics-crm') ?>" class="button"><i class="fa fa-edit"></i> <?php _e('Edit Feed','contact-form-dynamics-crm') ?></a>
<?php
    }
?>
<a href="<?php echo $log_link ?>" title="<?php _e('Open in New Window','contact-form-dynamics-crm') ?>" target="_blank" class="button"><i class="fa fa-external-link"></i> <?php _e('Open in New Window','contact-form-dynamics-crm') ?></a>
<a href="#" class="button vx_close_detail" title="<?php _e('Close','contact-form-dynamics-crm') ?>"><i class="fa fa-times"></i> <?php _e('Close','contact-form-dynamics-crm') ?></a>

</div> 
</div>