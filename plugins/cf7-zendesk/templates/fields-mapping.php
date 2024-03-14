<?php
if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }                                            
 ?>
 <div  class="vx_div">
   <div class="vx_head">
<div class="crm_head_div"> <?php esc_html_e('5. Map Form Fields to Zendesk Fields.', 'contact-form-zendesk-crm'); ?></div>
<div class="crm_btn_div" title="<?php esc_html_e('Expand / Collapse','contact-form-zendesk-crm') ?>"><i class="fa crm_toggle_btn vx_action_btn fa-minus"></i></div>
<div class="crm_clear"></div> 
  </div>
  <div class="vx_group">
  <div class="vx_col1">
  <label>
  <?php esc_html_e("Fields Mapping", 'contact-form-zendesk-crm'); ?>
  <?php $this->tooltip("vx_map_fields") ?>
  </label>
  </div>
  <div class="vx_col2">
  <div id="vx_fields_div">
  <?php 
   $req_span=" <span class='vx_red vx_required'>(".__('Required','contact-form-zendesk-crm').")</span>";
 $req_span2=" <span class='vx_red vx_required vx_req_parent'>(".__('Required','contact-form-zendesk-crm').")</span>";


  foreach($map_fields as $k=>$v){

  $sel_val=isset($map[$k]['field']) ? $map[$k]['field'] : ""; 
  $val_type=isset($map[$k]['type']) && !empty($map[$k]['type']) ? $map[$k]['type'] : "field"; 

      if(isset($skipped_fields[$k])){
        continue;
    }
    
  $options=$this->form_fields_options($form_id,$sel_val); 
    $display="none"; $btn_icon="fa-plus";
  if(isset($map[$k][$val_type]) && !empty($map[$k][$val_type])){
    $display="block"; 
    $btn_icon="fa-minus";   
  }
  $required=isset($v['req']) && $v['req'] == "true" ? true : false;
   $req_html=$required ? $req_span : ""; $k=esc_attr($k);
  ?>
<div class="crm_panel crm_panel_100">
<div class="crm_panel_head2">
<div class="crm_head_div"><span class="crm_head_text crm_text_label">  <?php echo $v['label'];?></span> <?php echo wp_kses_post($req_html) ?></div>
<div class="crm_btn_div">
<?php
 if(! $required){   
?>
<i class="vx_remove_btn vx_remove_btn vx_action_btn fa fa-trash-o" title="<?php esc_html_e('Delete','contact-form-zendesk-crm'); ?>"></i>
<?php } ?>
<i class="fa crm_toggle_btn vx_action_btn vx_btn_inner <?php echo esc_attr($btn_icon) ?>" title="<?php esc_html_e('Expand / Collapse','contact-form-zendesk-crm') ?>"></i>
</div>
<div class="crm_clear"></div> </div>
<div class="more_options crm_panel_content" style="display: <?php echo esc_attr($display) ?>;">
  <?php if(!isset($v['name_c'])){ ?>

  <div class="crm-panel-description">
  <span class="crm-desc-name-div"><?php echo esc_html__('Name:','contact-form-zendesk-crm')." ";?><span class="crm-desc-name"><?php echo $v['name']; ?></span> </span>
  <?php if($this->post('type',$v) !=""){ ?>
    <span class="crm-desc-type-div">, <?php echo esc_html__('Type:','contact-form-zendesk-crm')." ";?><span class="crm-desc-type"><?php echo $v['type'] ?></span> </span>
<?php
   }
  if($this->post('maxlength',$v) !=""){ 
   ?>
   <span class="crm-desc-len-div">, <?php echo esc_html__('Max Length:','contact-form-zendesk-crm')." ";?><span class="crm-desc-len"><?php echo $v['maxlength']; ?></span> </span>
  <?php 
  }
      if($this->post('eg',$v) !=""){ 
   ?>
   <span class="crm-eg-div">, <?php echo esc_html__('e.g:','contact-form-zendesk-crm')." ";?><span class="crm-eg"><?php echo $v['eg']; ?></span> </span>
  <?php 
  }
  ?>
   </div> 
  <?php
  }
  ?>

<div class="vx_margin">


<div class="entry_row">
<div class="entry_col1 vx_label"><label  for="vx_type_<?php echo esc_attr($k) ?>"><?php esc_html_e('Field Type','contact-form-zendesk-crm') ?></label></div>
<div class="entry_col2">
<select name='meta[map][<?php echo esc_attr($k) ?>][type]'  id="vx_type_<?php echo esc_attr($k) ?>" class='vxc_field_type vx_input_100'>
<?php
  foreach($sel_fields as $f_key=>$f_val){
  $select="";
  if($this->post2($k,'type',$map) == $f_key)
  $select='selected="selected"';
  ?>
  <option value="<?php echo esc_attr($f_key) ?>" <?php echo $select ?>><?php echo esc_attr($f_val); ?></option>     
  <?php } ?> 
</select>
</div>
<div class="crm_clear"></div>
</div>  
<div class="entry_row entry_row2">
<div class="entry_col1 vx_label">
<label for="vx_field_<?php echo esc_attr($k) ?>" style="<?php if($this->post2($k,'type',$map) != ''){echo 'display:none';} ?>" class="vxc_fields vxc_field_"><?php esc_html_e('Select Field','contact-form-zendesk-crm') ?></label>

<label for="vx_value_<?php echo esc_attr($k) ?>" style="<?php if($this->post2($k,'type',$map) != 'value'){echo 'display:none';} ?>" class="vxc_fields vxc_field_value"> <?php esc_html_e('Custom Value','contact-form-zendesk-crm') ?></label>
</div>
<div class="entry_col2">
<div class="vxc_fields vxc_field_value" style="<?php if($this->post2($k,'type',$map) != 'value'){echo 'display:none';} ?>">
<textarea name='meta[map][<?php echo esc_attr($k)?>][value]'  id="vx_value_<?php echo esc_attr($k) ?>" placeholder='<?php esc_html_e("Custom Value",'contact-form-zendesk-crm')?>' class='vx_input_100 vxc_field_input'><?php if(!empty($map[$k]['value'])){ echo htmlentities($map[$k]['value']); } ?></textarea>

<div class="howto"><?php echo sprintf(esc_html__('You can add a form field %s in custom value from following form fields','contact-form-zendesk-crm'),'<code>{field_id}</code>')?></div>

</div>


<select name="meta[map][<?php echo esc_attr($k) ?>][field]"  id="vx_field_<?php echo esc_attr($k) ?>" class="vxc_field_option vx_input_100">
<?php echo $options ?>
</select>


</div>
<div class="crm_clear"></div>
</div>  

  </div></div>
  <div class="clear"></div>
  </div>
<?php
  }
  ?> 
 
 <div id="vx_field_temp" style="display:none"> 
  <div class="crm_panel crm_panel_100 vx_fields">
<div class="crm_panel_head2">
<div class="crm_head_div"><span class="crm_head_text crm_text_label">  <?php esc_html_e('Custom Field', 'contact-form-zendesk-crm');?></span> </div>
<div class="crm_btn_div">
<i class="vx_remove_btn vx_action_btn fa fa-trash-o" title="<?php esc_html_e('Delete','contact-form-zendesk-crm'); ?>"></i>
<i class="fa crm_toggle_btn vx_action_btn vx_btn_inner fa-minus" title="<?php esc_html_e('Expand / Collapse','contact-form-zendesk-crm') ?>"></i>
</div>
<div class="crm_clear"></div> </div>
<div class="more_options crm_panel_content" style="display: block;">


  <div class="crm-panel-description">
  <span class="crm-desc-name-div"><?php echo esc_html__('Name:','contact-form-zendesk-crm')." ";?><span class="crm-desc-name"></span> </span>
  <span class="crm-desc-type-div">, <?php echo esc_html__('Type:','contact-form-zendesk-crm')." ";?><span class="crm-desc-type"></span> </span>
  <span class="crm-desc-len-div">, <?php echo esc_html__('Max Length:','contact-form-zendesk-crm')." ";?><span class="crm-desc-len"></span> </span>
   <span class="crm-eg-div">, <?php echo esc_html__('e.g:','contact-form-zendesk-crm')." ";?><span class="crm-eg"></span> </span>
  
   </div> 


<div class="vx_margin">


<div class="entry_row">
<div class="entry_col1 vx_label"><label  for="vx_type"><?php esc_html_e('Field Type','contact-form-zendesk-crm') ?></label></div>
<div class="entry_col2">
<select name='type' class='vxc_field_type vx_input_100'>
<?php
  foreach($sel_fields as $f_key=>$f_val){
  ?>
  <option value="<?php echo esc_attr($f_key) ?>"><?php echo esc_html($f_val)?></option>   
  <?php } ?> 
</select>
</div>
<div class="crm_clear"></div>
</div>  


<div class="entry_row entry_row2">
<div class="entry_col1 vx_label">
<label for="vx_field_" class="vxc_fields vxc_field_"><?php esc_html_e('Select Field','contact-form-zendesk-crm') ?></label>

<label for="vx_value_" style="display:none" class="vxc_fields vxc_field_value"> <?php esc_html_e('Custom Value','contact-form-zendesk-crm') ?></label>
</div>
<div class="entry_col2">
<div class="vxc_fields vxc_field_value" style="display:none">
<textarea name='value'  placeholder='<?php esc_html_e("Custom Value",'contact-form-zendesk-crm')?>' class="vx_input_100 vxc_field_input"></textarea>
<div class="howto"><?php echo sprintf(esc_html__('You can add a form field %s in custom value from following form fields','contact-form-zendesk-crm'),'<code>{field_id}</code>')?></div>
</div>


<select name="field"  id="vx_field_" class="vxc_field_option vx_input_100">
<?php echo $this->form_fields_options($form_id); ?>
</select>


</div>
<div class="crm_clear"></div>
</div>    

  </div></div>
  <div class="clear"></div>
  </div>
   </div>
   <!--end field box template--->

   <div class="crm_panel crm_panel_100">
<div class="crm_panel_head2">
<div class="crm_head_div"><span class="crm_head_text ">  <?php esc_html_e("Add New Field", 'contact-form-zendesk-crm');?></span> </div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn vx_btn_inner fa-minus" style="display: none;" title="<?php esc_html_e('Expand / Collapse','contact-form-zendesk-crm'); ?>"></i></div>
<div class="crm_clear"></div> </div>
<div class="more_options crm_panel_content" style="display: block;">

<div class="vx_margin">
<div style="display: table">
  <div style="display: table-cell; width: 85%; padding-right: 14px;">
<select id="vx_add_fields_select" class="vx_input_100" autocomplete="off">
<option value=""></option>
<?php
$json_fields=array();
foreach($fields as $k=>$v){
 $v['type']=ucfirst($v['type']);
     
if(!empty($v['options'])){
   $ops=array();
   foreach($v['options'] as $op){
    $ops[$op['value']]=$op['name'];   
   }
   $v['options']=$ops; 
}
     $json_fields[$k]=$v;
   $disable='';
   if(isset($map_fields[$k]) || isset($skip_fields[$k])){
    $disable='disabled="disabled"';   
   } 
echo '<option value="'.esc_html($k).'" '.$disable.' >'.esc_html($v['label']).'</option>';    
} ?>
</select>
  </div><div style="display: table-cell;">
 <button type="button" class="button button-default" style="vertical-align: middle;" id="xv_add_custom_field"><i class="fa fa-plus-circle" ></i> <?php esc_html_e('Add Field','contact-form-zendesk-crm')?></button>
  
  </div></div>
 

  </div></div>
  <div class="clear"></div>
  </div>
  <!--add new field box template--->
  <script type="text/javascript">
var crm_fields=<?php echo json_encode($json_fields); ?>;

</script> 

  </div>
  </div>
  <div class="clear"></div>
  </div>
  </div>
  <div class="vx_div">
   <div class="vx_head">
<div class="crm_head_div"> <?php esc_html_e('6. When to Send Entry to Zendesk.', 'contact-form-zendesk-crm'); ?></div>
<div class="crm_btn_div" title="<?php esc_html_e('Expand / Collapse','contact-form-zendesk-crm') ?>"><i class="fa crm_toggle_btn vx_action_btn fa-minus"></i></div>
<div class="crm_clear"></div> 
  </div>
 
  <div class="vx_group">
  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_manual_export">
  <?php esc_html_e('Disable Automatic Export', 'contact-form-zendesk-crm'); ?>
  <?php $this->tooltip("vx_manual_export") ?>
  </label>
  </div>
  <div class="vx_col2">
  <fieldset>
  <legend class="screen-reader-text"><span>
  <?php esc_html_e('Disable Automatic Export', 'contact-form-zendesk-crm'); ?>
  </span></legend>
  <label for="crm_manual_export">
  <input name="meta[manual_export]" id="crm_manual_export" type="checkbox" value="1" <?php echo isset($meta['manual_export'] ) ? 'checked="checked"' : ''; ?>>
  <?php esc_html_e( 'Manually send the entries to Zendesk.', 'contact-form-zendesk-crm'); ?> </label>
  </fieldset>
  </div>
  <div style="clear: both;"></div>
  </div>
  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_optin">
  <?php esc_html_e("Opt-In Condition", 'contact-form-zendesk-crm'); ?>
  <?php $this->tooltip("vx_optin_condition") ?>
  </label>
  </div>
  <div class="vx_col2">
  <div>
  <input type="checkbox" style="margin-top: 0px;" id="crm_optin" class="crm_toggle_check" name="meta[optin_enabled]" value="1" <?php echo !empty($meta["optin_enabled"]) ? "checked='checked'" : ""?>/>
  <label for="crm_optin">
  <?php esc_html_e("Enable", 'contact-form-zendesk-crm'); ?>
  </label>
  </div>
  <div style="clear: both;"></div>
  <div id="crm_optin_div"  style="margin-top: 16px; <?php echo empty($meta["optin_enabled"]) ? "display:none" : ""?>">
  <div>
  <?php
  $sno=0;
  foreach($filters as $filter_k=>$filter_v){ $filter_k=esc_attr($filter_k);
  $sno++;
                              ?>
  <div class="vx_filter_or" data-id="<?php echo esc_attr($filter_k) ?>">
  <?php if($sno>1){ ?>
  <div class="vx_filter_label">
  <?php esc_html_e('OR','contact-form-zendesk-crm') ?>
  </div>
  <?php } ?>
  <div class="vx_filter_div">
  <?php
  if(is_array($filter_v)){
  $sno_i=0;
  foreach($filter_v as $s_k=>$s_v){   $s_k=esc_attr($s_k);   
  $sno_i++;
  
  ?>
  <div class="vx_filter_and">
  <?php if($sno_i>1){ ?>
  <div class="vx_filter_label">
  <?php esc_html_e('AND','contact-form-zendesk-crm') ?>
  </div>
  <?php } ?>
  <div class="vx_filter_field vx_filter_field1">
  <select id="crm_optin_field" name="meta[filters][<?php echo esc_attr($filter_k) ?>][<?php echo esc_attr($s_k) ?>][field]">
  <?php 
  echo $this->form_fields_options($form_id,$this->post('field',$s_v));
                ?>
  </select>
  </div>
  <div class="vx_filter_field vx_filter_field2">
  <select name="meta[filters][<?php echo esc_attr($filter_k) ?>][<?php echo esc_attr($s_k) ?>][op]" >
  <?php
                 foreach($vx_op as $k=>$v){
  $sel="";
  if($this->post('op',$s_v) == $k)
  $sel='selected="selected"';
                   echo "<option value='".esc_attr($k)."' $sel >".esc_html($v)."</option>";
               } 
              ?>
  </select>
  </div>
  <div class="vx_filter_field vx_filter_field3">
  <input type="text" class="vxc_filter_text" placeholder="<?php esc_html_e('Value','contact-form-zendesk-crm') ?>" value="<?php echo $this->post('value',$s_v) ?>" name="meta[filters][<?php echo esc_attr($filter_k) ?>][<?php echo esc_attr($s_k) ?>][value]">
  </div>
  <?php if( $sno_i>1){ ?>
  <div class="vx_filter_field vx_filter_field4"><i class="vx_icons-h vx_trash_and vxc_tips fa fa-trash-o" data-tip="Delete"></i></div>
  <?php } ?>
  <div style="clear: both;"></div>
  </div>
  <?php
  } }
                     ?>
  <div class="vx_btn_div">
  <button class="button button-default button-small vx_add_and" title="<?php esc_html_e('Add AND Filter','contact-form-zendesk-crm'); ?>"><i class="vx_icons-s vx_trash_and fa fa-hand-o-right"></i>
  <?php esc_html_e('Add AND Filter','contact-form-zendesk-crm') ?>
  </button>
  <?php if($sno>1){ ?>
  <a href="#" class="vx_trash_or">
  <?php esc_html_e('Trash','contact-form-zendesk-crm') ?>
  </a>
  <?php } ?>
  </div>
  </div>
  </div>
  <?php
                          }
                      ?>
  <div class="vx_btn_div">
  <button class="button button-default  vx_add_or" title="<?php esc_html_e('Add OR Filter','contact-form-zendesk-crm'); ?>"><i class="vx_icons vx_trash_and fa fa-check"></i>
  <?php esc_html_e('Add OR Filter','contact-form-zendesk-crm') ?>
  </button>
  </div>
  </div>
  <!--------- template------------>
  <div style="display: none;" id="vx_filter_temp">
  <div class="vx_filter_or">
  <div class="vx_filter_label">
  <?php esc_html_e('OR','contact-form-zendesk-crm') ?>
  </div>
  <div class="vx_filter_div">
  <div class="vx_filter_and">
  <div class="vx_filter_label vx_filter_label_and">
  <?php esc_html_e('AND','contact-form-zendesk-crm') ?>
  </div>
  <div class="vx_filter_field vx_filter_field1">
  <select id="crm_optin_field" name="field">
  <?php 
  echo $this->form_fields_options($form_id);
                ?>
  </select>
  </div>
  <div class="vx_filter_field vx_filter_field2">
  <select name="op" >
  <?php
                 foreach($vx_op as $k=>$v){
  
                   echo "<option value='".esc_attr($k)."' >".esc_html($v)."</option>";
               } 
              ?>
  </select>
  </div>
  <div class="vx_filter_field vx_filter_field3">
  <input type="text" class="vxc_filter_text" placeholder="<?php esc_html_e('Value','contact-form-zendesk-crm') ?>" name="value">
  </div>
  <div class="vx_filter_field vx_filter_field4"><i class="vx_icons vx_trash_and vxc_tips fa fa-trash-o"></i></div>
  <div style="clear: both;"></div>
  </div>
  <div class="vx_btn_div">
  <button class="button button-default button-small vx_add_and" title="<?php esc_html_e('Add AND Filter','contact-form-zendesk-crm'); ?>"><i class="vx_icons vx_trash_and  fa fa-hand-o-right"></i>
  <?php esc_html_e('Add AND Filter','contact-form-zendesk-crm') ?>
  </button>
  <a href="#" class="vx_trash_or">
  <?php esc_html_e('Trash','contact-form-zendesk-crm') ?>
  </a> </div>
  </div>
  </div>
  </div>
  <!--------- template end ------------>
  </div>
  </div>
  <div style="clear: both;"></div>
  </div>
<?php
if($module == 'ticket'){
         $settings=get_option($this->type.'_settings',array());
         if(!empty($settings['notes'])){
?>

  <div class="vx_row">
  <div class="vx_col1">
  <label for="vx_notes"><?php esc_html_e('Entry Notes ', 'contact-form-zendesk-crm');  $this->tooltip('vx_entry_notes');?></label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="vx_notes" class="crm_toggle_check" name="meta[entry_notes]" value="1" <?php echo !empty($meta['entry_notes']) ? 'checked="checked"' : ''?> autocomplete="off"/>
    <label for="vx_notes"><?php esc_html_e('Add notes to Zendesk when added in Contact Form Entries', 'contact-form-zendesk-crm'); ?></label>
  
  </div>
  <div class="clear"></div>
  </div>
<?php
         }
    }
?>

  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_mark_read">
  <?php esc_html_e('Mark entries as "Read"', 'contact-form-zendesk-crm'); ?>
  </label>
  </div>
  <div class="vx_col2">
  <fieldset>

  <label for="crm_mark_read">
  <input name="meta[mark_read]" id="crm_mark_read" type="checkbox" value="1" <?php echo isset($meta['mark_read'] ) ? 'checked="checked"' : ''; ?>>
  <?php esc_html_e('Mark successfully sent entries as "Read"', 'contact-form-zendesk-crm'); ?> </label>
  </fieldset>
  </div>
  <div style="clear: both;"></div>
  </div>
  
  </div> 
  </div>
  <?php

   $panel_count=6;

      $panel_count++;
  ?>     
  <div class="vx_div "> 
  <div class="vx_head ">
<div class="crm_head_div"> <?php  echo sprintf(esc_html__('%s. Choose Primary Key.',  'contact-form-zendesk-crm' ),$panel_count); ?></div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn fa-minus" title="<?php esc_html_e('Expand / Collapse','contact-form-zendesk-crm') ?>"></i></div>
<div class="crm_clear"></div> 
  </div>                    
    <div class="vx_group">
  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_primary_field"><?php esc_html_e('Select Primary Key','contact-form-zendesk-crm') ?></label>
  </div><div class="vx_col2">
  <select id="crm_primary_field" name="meta[primary_key]" class="vx_sel vx_input_100" autocomplete="off">
  <?php echo $this->crm_select($search_fields,$this->post('primary_key',$meta)); ?>
  </select> 
  <div class="description" style="float: none; width: 90%"><?php esc_html_e('If you want to update a pre-existing object, select what should be used as a unique identifier ("Primary Key"). For example, this may be an email address, lead ID, or address. When a new entry comes in with the same "Primary Key" you select, a new object will not be created, instead the pre-existing object will be updated.', 'contact-form-zendesk-crm'); ?></div>
  </div>
  <div class="clear"></div>
  </div>
 <div class="vx_row">
  <div class="vx_col1">
  <label for="vx_update"><?php esc_html_e('Update Entry ', '%dd%');?></label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="vx_update" class="crm_toggle_check" name="meta[update]" value="1" <?php echo !empty($meta['update']) ? 'checked="checked"' : ''?> autocomplete="off"/>
    <label for="vx_update"><?php esc_html_e('Do not update entry, if already exists', 'contact-form-zendesk-crm'); ?></label>
  
  </div>
  <div class="clear"></div>
  </div>

<?php
  if($module == 'ticket'){
      ?>
    <div class="vx_row">
  <div class="vx_col1">
  <label for="vx_reply">
  <?php esc_html_e('Ticket reply', 'contact-form-zendesk-crm'); ?>
  </label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="vx_reply" class="crm_toggle_check" name="meta[no_reply]" value="1" <?php echo !empty($feed['no_reply']) ? "checked='checked'" : ""?>/>
  <label for="vx_reply">
  <?php esc_html_e('Do not add description as ticket reply, if ticket already exists', 'contact-form-zendesk-crm'); ?>
  </label>
  </div>
  <div style="clear: both;"></div>
  </div>
 
     <div class="vx_row">
  <div class="vx_col1">
  <label for="vx_private_ticket">
  <?php esc_html_e('Ticket Description', 'contact-form-zendesk-crm'); ?>
  </label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="vx_private_ticket" class="crm_toggle_check" name="meta[private_ticket]" value="1" <?php echo !empty($feed['private_ticket']) ? "checked='checked'" : ""?>/>
  <label for="vx_private_ticket">
  <?php esc_html_e('Add Ticket Description as Private Note', 'contact-form-zendesk-crm'); ?>
  </label>
  </div>
  <div style="clear: both;"></div>
  </div> 
       
      <?php
  }
?>
    
  </div>

  </div>
  <!-------------------------- lead owner -------------------->
           <?php   
           $id=$this->post('id',$feed);
if($module == 'ticket'){
    
                      $users=$this->post('users',$info_meta); 
                      $owners=array(''=>'Customer');
                      if(is_array($users) && count($users)>0){
                      $owners=array_merge($owners,$users);    
                      }
           $meta_user=isset($meta['user']) ? $meta['user'] : '';
           $status_list=array('new'=>'New', 'open'=>'Open', 'pending'=>'Pending', 'hold'=>'Hold', 'solved'=>'Solved', 'closed'=>'Closed');
$priority_list=array('urgent'=>'Urgent', 'high'=>'High', 'normal'=>'Normal', 'low'=>'Low');
$type_list=array('problem'=>'Problem', 'incident'=>'Incident', 'question'=>'Question', 'task'=>'Task');
               ?>
<div class="vx_div">
     <div class="vx_head">
<div class="crm_head_div"> <?php echo sprintf(esc_html__('%s. Add Note.', 'contact-form-zendesk-crm'),$panel_count+=1); ?></div>
<div class="crm_btn_div" title="<?php esc_html_e('Expand / Collapse','contact-form-zendesk-crm') ?>"><i class="fa crm_toggle_btn fa-minus"></i></div>
<div class="crm_clear"></div> 
  </div>


  <div class="vx_group">

    <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_note">
  <?php esc_html_e("Add Note ", 'contact-form-zendesk-crm'); ?>
  <?php $this->tooltip('vx_entry_note') ?>
  </label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="crm_note" class="crm_toggle_check" name="meta[note_check]" value="1" <?php echo !empty($meta['note_check']) ? "checked='checked'" : ""?>/>
  <label for="crm_note_div">
  <?php esc_html_e('Enable', 'contact-form-zendesk-crm'); ?>
  </label>
  </div>
  <div style="clear: both;"></div>
  </div>
  <div id="crm_note_div" style="margin-top: 16px; <?php echo empty($meta["note_check"]) ? "display:none" : ""?>">
  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_note_fields">
  <?php esc_html_e( 'Note Fields ', 'contact-form-zendesk-crm' ); $this->tooltip("vx_note_fields") ?>
  </label>
  </div>
  <div class="vx_col2">
  <select name="meta[note_fields][]" id="crm_note_fields" multiple="multiple" class="crm_sel crm_note_sel crm_sel2 vx_input_100" style="width: 100%"  autocomplete="off">

  <?php echo $this->form_fields_options($form_id,$this->post('note_fields',$meta)); ?>
  </select>
    <span class="howto">
  <?php esc_html_e('You can select multiple fields.', 'contact-form-zendesk-crm'); ?>
  </span>
   </div>
  <div style="clear: both;"></div>
  </div>
  
  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_disable_note">
  <?php esc_html_e( 'Disable Note ', 'contact-form-zendesk-crm' ); $this->tooltip("vx_disable_note") ?>
  </label>
  </div>
  <div class="vx_col2">
  
  <input type="checkbox" style="margin-top: 0px;" id="crm_disable_note" class="crm_toggle_check" name="meta[disable_entry_note]" value="1" <?php echo !empty($meta['disable_entry_note']) ? "checked='checked'" : ""?>/>
  <label for="crm_disable_note">
  <?php esc_html_e('Do not Add Note if entry already exists in Zendesk', 'contact-form-zendesk-crm'); ?>
  </label>
    
   </div>
  <div style="clear: both;"></div>
  </div>
  
  </div>

  </div>
  </div>               

<div class="vx_div vx_refresh_panel ">    
      <div class="vx_head ">
<div class="crm_head_div"> <?php $panel_count++;  echo sprintf(esc_html__('%s. Ticket Status',  'contact-form-zendesk-crm' ),$panel_count); ?></div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn fa-minus" title="<?php esc_html_e('Expand / Collapse','contact-form-zendesk-crm') ?>"></i></div>
<div class="crm_clear"></div> 
  </div>                 
    <div class="vx_group ">

<div class="vx_row">
   <div class="vx_col1">
  <label for="crm_sel_status"><?php esc_html_e('Select Status ','contact-form-zendesk-crm'); $this->tooltip('vx_status'); ?></label>
</div> <div class="vx_col2">

  <select id="crm_sel_status" class="vx_input_100" name="meta[status]" style="width: 100%;" autocomplete="off">
  <?php echo $this->gen_select($status_list,$this->post('status',$meta),__('Select Status','contact-form-zendesk-crm')); ?>
  </select>

  </div>
   <div class="clear"></div>
   </div>  

     <div class="vx_row">
  <div class="vx_col1">
  <label for="vx_notes"><?php esc_html_e('Is Private ', 'contact-form-zendesk-crm');  ?></label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="vx_priv"  name="meta[is_private]" value="1" <?php echo !empty($meta['is_private']) ? 'checked="checked"' : ''?> autocomplete="off"/>
    <label for="vx_priv"><?php esc_html_e('Add Ticket Description as Internal Note', 'contact-form-zendesk-crm'); ?></label>
  
  </div>
  <div class="clear"></div>
  </div>
  
  </div>
  </div>
  
<div class="vx_div vx_refresh_panel ">    
      <div class="vx_head ">
<div class="crm_head_div"> <?php $panel_count++;  echo sprintf(esc_html__('%s. Ticket Priority',  'contact-form-zendesk-crm' ),$panel_count); ?></div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn fa-minus" title="<?php esc_html_e('Expand / Collapse','contact-form-zendesk-crm') ?>"></i></div>
<div class="crm_clear"></div> 
  </div>                 
    <div class="vx_group ">

<div class="vx_row">
   <div class="vx_col1">
  <label for="crm_sel_priority"><?php esc_html_e('Select Priority ','contact-form-zendesk-crm'); $this->tooltip('vx_priority'); ?></label>
</div> <div class="vx_col2">

  <select id="crm_sel_priority" class="vx_input_100" name="meta[priority]" style="width: 100%;" autocomplete="off">
  <?php echo $this->gen_select($priority_list,$this->post('priority',$meta),__('Select Priority','contact-form-zendesk-crm')); ?>
  </select>

  </div>
   <div class="clear"></div>
   </div>  

  </div>
  </div>
<div class="vx_div vx_refresh_panel ">    
      <div class="vx_head ">
<div class="crm_head_div"> <?php $panel_count++;  echo sprintf(esc_html__('%s. Ticket Type',  'contact-form-zendesk-crm' ),$panel_count); ?></div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn fa-minus" title="<?php esc_html_e('Expand / Collapse','contact-form-zendesk-crm') ?>"></i></div>
<div class="crm_clear"></div> 
  </div>                 
    <div class="vx_group ">

<div class="vx_row">
   <div class="vx_col1">
  <label for="crm_sel_type"><?php esc_html_e('Select Type ','contact-form-zendesk-crm'); $this->tooltip('vx_type'); ?></label>
</div> <div class="vx_col2">

  <select id="crm_sel_type" class="vx_input_100" name="meta[type]" style="width: 100%;" autocomplete="off">
  <?php echo $this->gen_select($type_list,$this->post('type',$meta),__('Select Type','contact-form-zendesk-crm')); ?>
  </select>

  </div>
   <div class="clear"></div>
   </div>  

  </div>
  </div> 
<?php
$file=self::$path.'pro/pro-mapping.php';
if(self::$is_pr && file_exists($file)){
include_once($file);
}
}
   ?>   
  <div class="button-controls submit" style="padding-left: 5px;">
  <input type="hidden" name="form_id" value="<?php echo esc_attr($form_id) ?>">
  <button type="submit" title="<?php esc_html_e('Save Feed','contact-form-zendesk-crm'); ?>" name="<?php echo esc_attr($this->id) ?>_submit" class="button button-primary button-hero"> <i class="vx_icons vx vx-arrow-50"></i> <?php echo empty($fid) ? esc_html__("Save Feed", 'contact-form-zendesk-crm') : esc_html__("Update Feed", 'contact-form-zendesk-crm'); ?> </button>
  </div>

<?php 
do_action('vx_plugin_upgrade_notice_'.$this->type);
?>
