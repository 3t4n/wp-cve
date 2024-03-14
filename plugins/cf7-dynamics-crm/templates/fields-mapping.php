<?php
if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }                                            
 ?>
 <div  class="vx_div">
   <div class="vx_head">
<div class="crm_head_div"> <?php _e('5. Map Form Fields to Dynamics Fields.', 'contact-form-dynamics-crm'); ?></div>
<div class="crm_btn_div" title="<?php _e('Expand / Collapse','contact-form-dynamics-crm') ?>"><i class="fa crm_toggle_btn vx_action_btn fa-minus"></i></div>
<div class="crm_clear"></div> 
  </div>
  <div class="vx_group">
  <div class="vx_col1">
  <label>
  <?php _e("Fields Mapping", 'contact-form-dynamics-crm'); ?>
  <?php $this->tooltip("vx_map_fields") ?>
  </label>
  </div>
  <div class="vx_col2">
  <div id="vx_fields_div">
  <?php 
   $req_span=" <span class='vx_red vx_required'>(".__('Required','contact-form-dynamics-crm').")</span>";
 $req_span2=" <span class='vx_red vx_required vx_req_parent'>(".__('Required','contact-form-dynamics-crm').")</span>";

  foreach($map_fields as $k=>$v){

  $sel_val=isset($map[$k]['field']) ? $map[$k]['field'] : ""; 
  $val_type=isset($map[$k]['type']) && !empty($map[$k]['type']) ? $map[$k]['type'] : "field"; 

      if(isset($skipped_fields[$k])){
        continue;
    }
    
  $options=$this->form_fields_options($form_id,$sel_val,$this->account,$feed['id']); 
    $display="none"; $btn_icon="fa-plus";
  if(isset($map[$k][$val_type]) && !empty($map[$k][$val_type])){
    $display="block"; 
    $btn_icon="fa-minus";   
  }
  $required=isset($v['req']) && $v['req'] == "true" ? true : false;
   $req_html=$required ? $req_span : "";
 ?>
<div class="crm_panel crm_panel_100">
<div class="crm_panel_head2">
<div class="crm_head_div"><span class="crm_head_text crm_text_label">  <?php echo $v['label'];?></span> <?php echo $req_html ?></div>
<div class="crm_btn_div">
<?php
 if(! $required){   
?>
<i class="vx_remove_btn vx_remove_btn vx_action_btn fa fa-trash-o" title="<?php _e('Delete','contact-form-dynamics-crm'); ?>"></i>
<?php } ?>
<i class="fa crm_toggle_btn vx_action_btn vx_btn_inner <?php echo $btn_icon ?>" title="<?php _e('Expand / Collapse','contact-form-dynamics-crm') ?>"></i>
</div>
<div class="crm_clear"></div> </div>
<div class="more_options crm_panel_content" style="display: <?php echo $display ?>;">
  <?php if(!isset($v['name_c'])){ ?>

  <div class="crm-panel-description">
  <span class="crm-desc-name-div"><?php echo __('Name:','contact-form-dynamics-crm')." ";?><span class="crm-desc-name"><?php echo $v['name']; ?></span> </span>
  <?php if(vxcf_dynamics::post('type',$v) !=""){ ?>
    <span class="crm-desc-type-div">, <?php echo __('Type:','contact-form-dynamics-crm')." ";?><span class="crm-desc-type"><?php echo $v['type'] ?></span> </span>
<?php
   }
  if(vxcf_dynamics::post('maxlength',$v) !=""){ 
   ?>
   <span class="crm-desc-len-div">, <?php echo __('Max Length:','contact-form-dynamics-crm')." ";?><span class="crm-desc-len"><?php echo $v['maxlength']; ?></span> </span>
  <?php 
  }     if(vxcf_dynamics::post('eg',$v) !=""){ 
   ?>
   <span class="crm-desc-eg-div">, <?php echo __('e.g:','contact-form-dynamics-crm')." ";?><span class="crm-desc-eg"><?php echo $v['eg']; ?></span> </span>
  <?php 
  }
  ?>
   </div> 
  <?php
  }
  ?>

<div class="vx_margin">


<div class="entry_row">
<div class="entry_col1 vx_label"><label  for="vx_type_<?php echo esc_attr($k) ?>"><?php _e('Field Type','contact-form-dynamics-crm') ?></label></div>
<div class="entry_col2">
<select name='meta[map][<?php echo esc_attr($k) ?>][type]'  id="vx_type_<?php echo esc_attr($k) ?>" class='vxc_field_type vx_input_100'>
<?php
  foreach($sel_fields as $f_key=>$f_val){
  $select="";
  if(vxcf_dynamics::post2($k,'type',$map) == $f_key)
  $select='selected="selected"';
  ?>
  <option value="<?php echo $f_key ?>" <?php echo $select ?>><?php echo $f_val?></option>    
  <?php } ?> 
</select>
</div>
<div class="crm_clear"></div>
</div>  
<div class="entry_row entry_row2">
<div class="entry_col1 vx_label">
<label for="vx_field_<?php echo esc_attr($k) ?>" style="<?php if(vxcf_dynamics::post2($k,'type',$map) != ''){echo 'display:none';} ?>" class="vxc_fields vxc_field_"><?php _e('Select Field','contact-form-dynamics-crm') ?></label>

<label for="vx_value_<?php echo esc_attr($k) ?>" style="<?php if(vxcf_dynamics::post2($k,'type',$map) != 'value'){echo 'display:none';} ?>" class="vxc_fields vxc_field_value"> <?php _e('Custom Value','contact-form-dynamics-crm') ?></label>
</div>
<div class="entry_col2">
<div class="vxc_fields vxc_field_value" style="<?php if(vxcf_dynamics::post2($k,'type',$map) != 'value'){echo 'display:none';} ?>">
<input type="text" name='meta[map][<?php echo esc_attr($k)?>][value]'  id="vx_value_<?php echo esc_attr($k) ?>" value='<?php echo vxcf_dynamics::post2($k,'value',$map)?>' placeholder='<?php _e("Custom Value",'contact-form-dynamics-crm')?>' class='vx_input_100 vxc_field_input'>
<div class="howto"><?php echo sprintf(__('You can add a form field %s in custom value from following form fields','contact-form-dynamics-crm'),'<code>{field_id}</code>')?></div>
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
<div class="crm_head_div"><span class="crm_head_text crm_text_label">  <?php _e('Custom Field', 'contact-form-dynamics-crm');?></span> </div>
<div class="crm_btn_div">
<i class="vx_remove_btn vx_action_btn fa fa-trash-o" title="<?php _e('Delete','contact-form-dynamics-crm'); ?>"></i>
<i class="fa crm_toggle_btn vx_action_btn vx_btn_inner fa-minus" title="<?php _e('Expand / Collapse','contact-form-dynamics-crm') ?>"></i>
</div>
<div class="crm_clear"></div> </div>
<div class="more_options crm_panel_content" style="display: block;">


  <div class="crm-panel-description">
  <span class="crm-desc-name-div"><?php echo __('Name:','contact-form-dynamics-crm')." ";?><span class="crm-desc-name"></span> </span>
  <span class="crm-desc-type-div">, <?php echo __('Type:','contact-form-dynamics-crm')." ";?><span class="crm-desc-type"></span> </span>
  <span class="crm-desc-len-div">, <?php echo __('Max Length:','contact-form-dynamics-crm')." ";?><span class="crm-desc-len"></span> </span>

    <span class="crm-eg-div">, <?php echo __('e.g:','contact-form-dynamics-crm')." ";?><span class="crm-eg"></span> </span>
   </div> 


<div class="vx_margin">
<div class="entry_row">
<div class="entry_col1 vx_label"><label  for="vx_type"><?php _e('Field Type','contact-form-dynamics-crm') ?></label></div>
<div class="entry_col2">
<select name='type' class='vxc_field_type vx_input_100'>
<?php
  foreach($sel_fields as $f_key=>$f_val){
  ?>
  <option value="<?php echo $f_key ?>"><?php echo $f_val?></option>    
  <?php } ?> 
</select>
</div>
<div class="crm_clear"></div>
</div>  

<div class="entry_row entry_row2">
<div class="entry_col1 vx_label">
<label  class="vxc_fields vxc_field_"><?php _e('Select Field','contact-form-dynamics-crm') ?></label>

<label  style="display:none" class="vxc_fields vxc_field_value"> <?php _e('Custom Value','contact-form-dynamics-crm') ?></label>
</div>
<div class="entry_col2">
<div class="vxc_fields vxc_field_value" style="display: none;">
<input type="text" name='value'   value='' placeholder='<?php _e("Custom Value",'contact-form-dynamics-crm')?>' class='vx_input_100 vxc_field_input'>
<div class="howto"><?php echo sprintf(__('You can add a form field %s in custom value from following form fields','contact-form-dynamics-crm'),'<code>{field_id}</code>')?></div>
</div>
<select name="field" class="vxc_field_option vx_input_100">
<?php echo $options ?>
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
<div class="crm_head_div"><span class="crm_head_text ">  <?php _e("Add New Field", 'contact-form-dynamics-crm');?></span> </div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn vx_btn_inner fa-minus" style="display: none;" title="<?php _e('Expand / Collapse','contact-form-dynamics-crm'); ?>"></i></div>
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
     $json_fields[$k]=$v;
   $disable='';
   if(isset($map_fields[$k]) || isset($skip_fields[$k])){
    $disable='disabled="disabled"';   
   } 
echo "<option value='{$k}' {$disable} >{$v['label']}</option>";   
} ?>
</select>
  </div><div style="display: table-cell;">
 <button type="button" class="button button-default" style="vertical-align: middle;" id="xv_add_custom_field"><i class="fa fa-plus-circle" ></i> <?php _e('Add Field','contact-form-dynamics-crm')?></button>
  
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
<div class="crm_head_div"> <?php _e('6. When to Send Entry to Dynamics.', 'contact-form-dynamics-crm'); ?></div>
<div class="crm_btn_div" title="<?php _e('Expand / Collapse','contact-form-dynamics-crm') ?>"><i class="fa crm_toggle_btn vx_action_btn fa-minus"></i></div>
<div class="crm_clear"></div> 
  </div>
 
  <div class="vx_group">
  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_manual_export">
  <?php esc_html_e('Disable Automatic Export', 'contact-form-dynamics-crm'); ?>
  <?php $this->tooltip("vx_manual_export") ?>
  </label>
  </div>
  <div class="vx_col2">
  <fieldset>
  <legend class="screen-reader-text"><span>
  <?php _e('Disable Automatic Export', 'contact-form-dynamics-crm'); ?>
  </span></legend>
  <label for="crm_manual_export">
  <input name="meta[manual_export]" id="crm_manual_export" type="checkbox" value="1" <?php echo isset($meta['manual_export'] ) ? 'checked="checked"' : ''; ?>>
  <?php _e( 'Manually send the entries to Dynamics.', 'contact-form-dynamics-crm'); ?> </label>
  </fieldset>
  </div>
  <div style="clear: both;"></div>
  </div>
  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_optin">
  <?php _e("Opt-In Condition", 'contact-form-dynamics-crm'); ?>
  <?php $this->tooltip("vx_optin_condition") ?>
  </label>
  </div>
  <div class="vx_col2">
  <div>
  <input type="checkbox" style="margin-top: 0px;" id="crm_optin" class="crm_toggle_check" name="meta[optin_enabled]" value="1" <?php echo !empty($meta["optin_enabled"]) ? "checked='checked'" : ""?>/>
  <label for="crm_optin">
  <?php _e("Enable", 'contact-form-dynamics-crm'); ?>
  </label>
  </div>
  <div style="clear: both;"></div>
  <div id="crm_optin_div"  style="margin-top: 16px; <?php echo empty($meta["optin_enabled"]) ? "display:none" : ""?>">
  <div>
  <?php
  $sno=0;
  foreach($filters as $filter_k=>$filter_v){
  $sno++;
                              ?>
  <div class="vx_filter_or" data-id="<?php echo esc_attr($filter_k) ?>">
  <?php if($sno>1){ ?>
  <div class="vx_filter_label">
  <?php _e('OR','contact-form-dynamics-crm') ?>
  </div>
  <?php } ?>
  <div class="vx_filter_div">
  <?php
  if(is_array($filter_v)){
  $sno_i=0;
  foreach($filter_v as $s_k=>$s_v){   
  $sno_i++;
  
  ?>
  <div class="vx_filter_and">
  <?php if($sno_i>1){ ?>
  <div class="vx_filter_label">
  <?php _e('AND','contact-form-dynamics-crm') ?>
  </div>
  <?php } ?>
  <div class="vx_filter_field vx_filter_field1">
  <select id="crm_optin_field" name="meta[filters][<?php echo esc_attr($filter_k) ?>][<?php echo esc_attr($s_k) ?>][field]">
  <?php 
  echo $this->form_fields_options($form_id,vxcf_dynamics::post('field',$s_v),$this->account,$feed['id']);
                ?>
  </select>
  </div>
  <div class="vx_filter_field vx_filter_field2">
  <select name="meta[filters][<?php echo esc_attr($filter_k) ?>][<?php echo esc_attr($s_k) ?>][op]" >
  <?php
                 foreach($vx_op as $k=>$v){
  $sel="";
  if(vxcf_dynamics::post('op',$s_v) == $k)
  $sel='selected="selected"';
                   echo "<option value='".$k."' $sel >".$v."</option>";
               } 
              ?>
  </select>
  </div>
  <div class="vx_filter_field vx_filter_field3">
  <input type="text" class="vxc_filter_text" placeholder="<?php _e('Value','contact-form-dynamics-crm') ?>" value="<?php echo vxcf_dynamics::post('value',$s_v) ?>" name="meta[filters][<?php echo esc_attr($filter_k) ?>][<?php echo esc_attr($s_k) ?>][value]">
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
  <button class="button button-default button-small vx_add_and" title="<?php _e('Add AND Filter','contact-form-dynamics-crm'); ?>"><i class="vx_icons-s vx_trash_and fa fa-hand-o-right"></i>
  <?php _e('Add AND Filter','contact-form-dynamics-crm') ?>
  </button>
  <?php if($sno>1){ ?>
  <a href="#" class="vx_trash_or">
  <?php _e('Trash','contact-form-dynamics-crm') ?>
  </a>
  <?php } ?>
  </div>
  </div>
  </div>
  <?php
                          }
                      ?>
  <div class="vx_btn_div">
  <button class="button button-default  vx_add_or" title="<?php _e('Add OR Filter','contact-form-dynamics-crm'); ?>"><i class="vx_icons vx_trash_and fa fa-check"></i>
  <?php _e('Add OR Filter','contact-form-dynamics-crm') ?>
  </button>
  </div>
  </div>
  <!--------- template------------>
  <div style="display: none;" id="vx_filter_temp">
  <div class="vx_filter_or">
  <div class="vx_filter_label">
  <?php _e('OR','contact-form-dynamics-crm') ?>
  </div>
  <div class="vx_filter_div">
  <div class="vx_filter_and">
  <div class="vx_filter_label vx_filter_label_and">
  <?php _e('AND','contact-form-dynamics-crm') ?>
  </div>
  <div class="vx_filter_field vx_filter_field1">
  <select id="crm_optin_field" name="field">
  <?php 
  echo $this->form_fields_options($form_id,'',$this->account,$feed['id']);
                ?>
  </select>
  </div>
  <div class="vx_filter_field vx_filter_field2">
  <select name="op" >
  <?php
                 foreach($vx_op as $k=>$v){
  
                   echo "<option value='".$k."' >".$v."</option>";
               } 
              ?>
  </select>
  </div>
  <div class="vx_filter_field vx_filter_field3">
  <input type="text" class="vxc_filter_text" placeholder="<?php _e('Value','contact-form-dynamics-crm') ?>" name="value">
  </div>
  <div class="vx_filter_field vx_filter_field4"><i class="vx_icons vx_trash_and vxc_tips fa fa-trash-o"></i></div>
  <div style="clear: both;"></div>
  </div>
  <div class="vx_btn_div">
  <button class="button button-default button-small vx_add_and" title="<?php _e('Add AND Filter','contact-form-dynamics-crm'); ?>"><i class="vx_icons vx_trash_and  fa fa-hand-o-right"></i>
  <?php _e('Add AND Filter','contact-form-dynamics-crm') ?>
  </button>
  <a href="#" class="vx_trash_or">
  <?php _e('Trash','contact-form-dynamics-crm') ?>
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
   if($api_type != "web"){ 
         $settings=get_option(vxcf_dynamics::$type.'_settings',array());
         if(!empty($settings['notes'])){
?>

  <div class="vx_row">
  <div class="vx_col1">
  <label for="vx_notes"><?php _e('Entry Notes ', 'contact-form-dynamics-crm');  $this->tooltip('vx_entry_notes');?></label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="vx_notes" class="crm_toggle_check" name="meta[entry_notes]" value="1" <?php echo !empty($meta['entry_notes']) ? 'checked="checked"' : ''?> autocomplete="off"/>
    <label for="vx_notes"><?php _e('Add / delete notes to Dynamics when added / deleted in Contact Form Entries Plugin', 'contact-form-dynamics-crm'); ?></label>
  
  </div>
  <div class="clear"></div>
  </div>
<?php
         }
    }
?>

  </div> 
  </div>
  <?php

   $panel_count=6;

      $panel_count++;
  ?>     
  <div class="vx_div "> 
  <div class="vx_head ">
<div class="crm_head_div"> <?php  echo sprintf(__('%s. Choose Primary Key.',  'contact-form-dynamics-crm' ),$panel_count); ?></div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn fa-minus" title="<?php _e('Expand / Collapse','contact-form-dynamics-crm') ?>"></i></div>
<div class="crm_clear"></div> 
  </div>                    
    <div class="vx_group">
  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_primary_field"><?php _e('Select Primary Key','%dd%') ?></label>
  </div><div class="vx_col2">
  <select id="crm_primary_field" name="meta[primary_key]" class="vx_sel vx_input_100" autocomplete="off">
  <?php echo $this->crm_select($search_fields,vxcf_dynamics::post('primary_key',$meta)); ?>
  </select> 
  <div class="description" style="float: none; width: 90%"><?php _e('If you want to update a pre-existing object, select what should be used as a unique identifier ("Primary Key"). For example, this may be an email address, lead ID, or address. When a new entry comes in with the same "Primary Key" you select, a new object will not be created, instead the pre-existing object will be updated.', '%dd%'); ?></div>
  </div>
  <div class="clear"></div>
  </div>
 <div class="vx_row">
  <div class="vx_col1">
  <label for="vx_update"><?php _e('Update Entry ', '%dd%');?></label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="vx_update" class="crm_toggle_check" name="meta[update]" value="1" <?php echo !empty($meta['update']) ? 'checked="checked"' : ''?> autocomplete="off"/>
    <label for="vx_update"><?php _e('Do not update entry, if already exists', '%dd%'); ?></label>
  
  </div>
  <div class="clear"></div>
  </div>
    
  </div>

  </div>
  <!-------------------------- lead owner -------------------->

<div class="vx_div">
     <div class="vx_head">
<div class="crm_head_div"> <?php echo sprintf(__('%s. Add Note.', 'contact-form-dynamics-crm'),$panel_count+=1); ?></div>
<div class="crm_btn_div" title="<?php _e('Expand / Collapse','contact-form-dynamics-crm') ?>"><i class="fa crm_toggle_btn fa-minus"></i></div>
<div class="crm_clear"></div> 
  </div>


  <div class="vx_group">

    <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_note">
  <?php _e("Add Note ", 'contact-form-dynamics-crm'); ?>
  <?php $this->tooltip('vx_entry_note') ?>
  </label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="crm_note" class="crm_toggle_check" name="meta[note_check]" value="1" <?php echo !empty($meta['note_check']) ? "checked='checked'" : ""?>/>
  <label for="crm_note_div">
  <?php _e("Enable", 'contact-form-dynamics-crm'); ?>
  </label>
  </div>
  <div style="clear: both;"></div>
  </div>
  <div id="crm_note_div" style="margin-top: 16px; <?php echo empty($meta["note_check"]) ? "display:none" : ""?>">
  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_note_fields">
  <?php _e( 'Note Fields ', 'contact-form-dynamics-crm' ); $this->tooltip("vx_note_fields") ?>
  </label>
  </div>
  <div class="vx_col2">
  <select name="meta[note_fields][]" id="crm_note_fields" multiple="multiple" class="crm_sel crm_note_sel crm_sel2 vx_input_100" style="width: 100%"  autocomplete="off">

  <?php echo $this->form_fields_options($form_id,vxcf_dynamics::post('note_fields',$meta),$this->account,$feed['id']); ?>
  </select>
    <span class="howto">
  <?php _e('You can select multiple fields.', 'contact-form-dynamics-crm'); ?>
  </span>
   </div>
  <div style="clear: both;"></div>
  </div>
  
  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_disable_note">
  <?php _e( 'Disable Note ', 'contact-form-dynamics-crm' ); $this->tooltip("vx_disable_note") ?>
  </label>
  </div>
  <div class="vx_col2">
  
  <input type="checkbox" style="margin-top: 0px;" id="crm_disable_note" class="crm_toggle_check" name="meta[disable_entry_note]" value="1" <?php echo !empty($meta['disable_entry_note']) ? "checked='checked'" : ""?>/>
  <label for="crm_disable_note">
  <?php _e('Do not Add Note if entry already exists in Dynamics', 'contact-form-dynamics-crm'); ?>
  </label>
    
   </div>
  <div style="clear: both;"></div>
  </div>
  
  </div>

  </div>
  </div>
  
      <?php 
$file=vxcf_dynamics::$path.'pro/pro-mapping.php';
if(vxcf_dynamics::$is_pr && file_exists($file)){
include_once($file);
}
?>
  <div class="button-controls submit" style="padding-left: 5px;">
  <input type="hidden" name="form_id" value="<?php echo esc_attr($form_id) ?>">
  <button type="submit" title="<?php _e('Save Feed','contact-form-dynamics-crm'); ?>" name="<?php echo vxcf_dynamics::$id ?>_submit" class="button button-primary button-hero"> <i class="vx_icons vx vx-arrow-50"></i> <?php echo empty($fid) ? __("Save Feed", 'contact-form-dynamics-crm') : __("Update Feed", 'contact-form-dynamics-crm'); ?> </button>
  </div>


<?php
   do_action('vx_plugin_upgrade_notice_'.vxcf_dynamics::$type);   
  ?>