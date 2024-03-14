<?php
if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }                                            
 ?>
<div  class="vx_div">
   <div class="vx_head">
<div class="crm_head_div"> <?php esc_html_e('4. Map Form Fields to Infusionsoft Fields.', 'gravity-forms-infusionsoft-crm'); ?></div>
<div class="crm_btn_div" title="<?php esc_html_e('Expand / Collapse','gravity-forms-infusionsoft-crm') ?>"><i class="fa crm_toggle_btn vx_action_btn fa-minus"></i></div>
<div class="crm_clear"></div> 
  </div>
  <div class="vx_group" style="padding: 10px 0px; border-width: 0px; background-color: transparent;">


  <div id="vx_fields_div">
  <?php 
   $req_span=" <span class='vx_red vx_required'>(".__('Required','gravity-forms-infusionsoft-crm').")</span>";
 $req_span2=" <span class='vx_red vx_required vx_req_parent'>(".__('Required','gravity-forms-infusionsoft-crm').")</span>";
  foreach($map_fields as $k=>$v){
        if(isset($v['name_c'])){
  $v['name']=$v['name_c'];      
  $v['label']=__('Custom Field','gravity-forms-infusionsoft-crm');      
  } 
  if( in_array($v['name'] , array("OwnerId","AccountId","ContractId") )){
    continue;
}
if($module == "Order" && in_array($v['name'] , array("Status" ))){
    continue;
} 
  $sel_val=isset($map[$k]['field']) ? $map[$k]['field'] : ""; 
  $val_type=isset($map[$k]['type']) && !empty($map[$k]['type']) ? $map[$k]['type'] : "field"; 

  $options=$this->gf_fields_options($form_id,$sel_val); 
    $display="none"; $btn_icon="fa-plus";
 // if(isset($map[$k][$val_type]) && !empty($map[$k][$val_type])){
    $display="block"; 
    $btn_icon="fa-minus";   
  //}
  
  $required=isset($v['req']) && $v['req'] == "true" ? true : false;
   $req_html=$required ? $req_span : ""; $k=esc_attr($k);
  ?>
<div class="crm_panel crm_panel_100">
<div class="crm_panel_head2">
<div class="crm_head_div"><span class="crm_head_text crm_text_label">  <?php echo esc_html($v['label']);?></span> <?php echo wp_kses_post($req_html) ?></div>
<div class="crm_btn_div">
<?php
 if(! $required){   
?>
<i class="vx_remove_btn vx_remove_btn vx_action_btn fa fa-trash-o" title="<?php esc_html_e('Delete','gravity-forms-infusionsoft-crm'); ?>"></i>
<?php } ?>
<i class="fa crm_toggle_btn vx_action_btn vx_btn_inner <?php echo $btn_icon ?>" title="<?php esc_html_e('Expand / Collapse','gravity-forms-infusionsoft-crm') ?>"></i>
</div>
<div class="crm_clear"></div> </div>
<div class="more_options crm_panel_content" style="display: <?php echo $display ?>;">
  <?php if(!isset($v['name_c'])){ ?>

  <div class="crm-panel-description">
  <span class="crm-desc-name-div"><?php echo esc_html__('Name:','gravity-forms-infusionsoft-crm')." ";?><span class="crm-desc-name"><?php echo esc_html($v['name']); ?></span> </span>
  <?php if($this->post('type',$v) !=""){ ?>
    <span class="crm-desc-type-div">, <?php echo esc_html__('Type:','gravity-forms-infusionsoft-crm')." ";?><span class="crm-desc-type"><?php echo esc_html($v['type']) ?></span> </span>
<?php
   }
  if($this->post('maxlength',$v) !=""){ 
   ?>
   <span class="crm-desc-len-div">, <?php echo esc_html__('Max Length:','gravity-forms-infusionsoft-crm')." ";?><span class="crm-desc-len"><?php echo esc_html($v['maxlength']); ?></span> </span>
  <?php 
  }
   if($this->post('eg',$v) !=""){ 
   ?>
   <span class="crm-eg-div">, <?php echo esc_html__('e.g:','gravity-forms-infusionsoft-crm')." ";?><span class="crm-eg"><?php echo $v['eg']; ?></span> </span>
  <?php 
  }
  ?>
   </div> 
  <?php
  }
  ?>

<div class="vx_margin">

<?php
    if(isset($v['name_c'])){
?>
<div class="entry_row">
<div class="entry_col1 vx_label"><?php esc_html_e('Field API Name','gravity-forms-infusionsoft-crm') ?></div>
<div class="entry_col2">
<input type="text" name="meta[map][<?php echo $k ?>][name_c]" value="<?php echo esc_attr($v['name_c']) ?>" placeholder="<?php esc_html_e('Field API Name','gravity-forms-infusionsoft-crm') ?>" class="vx_input_100">
</div>
<div class="crm_clear"></div>
</div> 
<?php             
    }
?>
<div class="entry_row">
<div class="entry_col1 vx_label"><label  for="vx_type_<?php echo $k ?>"><?php esc_html_e('Field Type','gravity-forms-infusionsoft-crm') ?></label></div>
<div class="entry_col2">
<select name='meta[map][<?php echo $k ?>][type]'  id="vx_type_<?php echo $k ?>" class='vxc_field_type vx_input_100'>
<?php
  foreach($sel_fields as $f_key=>$f_val){
  $select="";
  if($this->post2($k,'type',$map) == $f_key)
  $select='selected="selected"';
  ?>
  <option value="<?php echo esc_attr($f_key) ?>" <?php echo $select ?>><?php echo esc_html($f_val)?></option>   
  <?php } ?> 
</select>
</div>
<div class="crm_clear"></div>
</div>  
<div class="entry_row entry_row2">
<div class="entry_col1 vx_label">
<label for="vx_field_<?php echo $k ?>" style="<?php if($this->post2($k,'type',$map) != ''){echo 'display:none';} ?>" class="vxc_fields vxc_field_"><?php esc_html_e('Select Field','%dd%') ?></label>

<label for="vx_value_<?php echo $k ?>" style="<?php if($this->post2($k,'type',$map) != 'value'){echo 'display:none';} ?>" class="vxc_fields vxc_field_value"> <?php esc_html_e('Custom Value','%dd%') ?></label>
</div>
<div class="entry_col2">
<div class="vxc_fields vxc_field_value" style="<?php if($this->post2($k,'type',$map) != 'value'){echo 'display:none';} ?>">
<input type="text" name='meta[map][<?php echo $k?>][value]'  id="vx_value_<?php echo $k ?>" value='<?php echo $this->post2($k,'value',$map)?>' placeholder='<?php esc_html_e("Custom Value",'%dd%')?>' class='vx_input_100 vxc_field_input'>
<div class="howto"><?php echo sprintf(__('You can add a form field %s in custom value from following form fields','%dd%'),'<code>{field_id}</code>')?></div>
</div>


<select name="meta[map][<?php echo $k ?>][field]"  id="vx_field_<?php echo $k ?>" class="vxc_field_option vx_input_100">
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
<div class="crm_head_div"><span class="crm_head_text crm_text_label">  <?php esc_html_e('Custom Field', 'gravity-forms-infusionsoft-crm');?></span> </div>
<div class="crm_btn_div">
<i class="vx_remove_btn vx_action_btn fa fa-trash-o" title="<?php esc_html_e('Delete','gravity-forms-infusionsoft-crm'); ?>"></i>
<i class="fa crm_toggle_btn vx_action_btn vx_btn_inner fa-minus" title="<?php esc_html_e('Expand / Collapse','gravity-forms-infusionsoft-crm') ?>"></i>
</div>
<div class="crm_clear"></div> </div>
<div class="more_options crm_panel_content" style="display: block;">

<?php
    if($api_type  != 'web'){
?>

  <div class="crm-panel-description">
  <span class="crm-desc-name-div"><?php echo esc_html__('Name:','gravity-forms-infusionsoft-crm')." ";?><span class="crm-desc-name"></span> </span>
  <span class="crm-desc-type-div">, <?php echo esc_html__('Type:','gravity-forms-infusionsoft-crm')." ";?><span class="crm-desc-type"></span> </span>
  <span class="crm-desc-len-div">, <?php echo esc_html__('Max Length:','gravity-forms-infusionsoft-crm')." ";?><span class="crm-desc-len"></span> </span>
   <span class="crm-eg-div">, <?php echo esc_html__('e.g:','gravity-forms-infusionsoft-crm')." ";?><span class="crm-eg"></span> </span>
   </div> 

<?php
    }
?>
<div class="vx_margin">

<?php
    if($api_type  == 'web'){
?>
<div class="entry_row">
<div class="entry_col1 vx_label"><?php esc_html_e('Field API Name','gravity-forms-infusionsoft-crm') ?></div>
<div class="entry_col2">
<input type="text" name="name_c" placeholder="<?php esc_html_e('Field API Name','gravity-forms-infusionsoft-crm') ?>" class="vx_input_100">
</div>
<div class="crm_clear"></div>
</div> 
<?php
    }
?>
<div class="entry_row">
<div class="entry_col1 vx_label"><label  for="vx_type"><?php esc_html_e('Field Type','gravity-forms-infusionsoft-crm') ?></label></div>
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
<label for="vx_field" class="vxc_fields vxc_field_"><?php esc_html_e('Select Field','%dd%') ?></label>

<label for="vx_value" class="vxc_fields vxc_field_value" style="display: none;"> <?php esc_html_e('Custom Value','%dd%') ?></label>
</div>
<div class="entry_col2">
<div class="vxc_fields vxc_field_value" style="display: none;">
<input type="text" name='value'  placeholder='<?php esc_html_e("Custom Value",'%dd%')?>' class='vx_input_100 vxc_field_input'>
<div class="howto"><?php echo sprintf(__('You can add a form field %s in custom value from following form fields','%dd%'),'<code>{field_id}</code>')?></div>
</div>

<select name="field"  class="vxc_field_option vx_input_100">
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
<?php if($api_type =="web"){ ?>
  <div class="vx_fields_footer">
  <div class="vx_row">
  <div class="vx_col1"> &nbsp;</div><div class="vx_col2">
  <button type="button" class="button button-default" id="xv_add_custom_field"><i class=" fa fa-plus-circle" ></i> <?php esc_html_e('Add Custom Field','gravity-forms-infusionsoft-crm')?></button></div>
  <div class="clear"></div></div>
   </div>
 <?php }else{ ?> 
<div class="crm_panel crm_panel_100">
<div class="crm_panel_head2">
<div class="crm_head_div"><span class="crm_head_text ">  <?php esc_html_e("Add New Field", 'gravity-forms-infusionsoft-crm');?></span> </div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn vx_btn_inner fa-minus" style="display: none;" title="<?php esc_html_e('Expand / Collapse','gravity-forms-infusionsoft-crm') ?>"></i></div>
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
    $ops[$op['name']]=$op['value'];   
   }
   $v['options']=$ops; 
}
     $json_fields[$k]=$v;
   $disable='';
   if(isset($map_fields[$k])){
    $disable='disabled="disabled"';   
   } 
echo '<option value="'.esc_attr($k).'" '.$disable.'>'.esc_html($v['label']).'</option>';  
} ?>
</select>
  </div><div style="display: table-cell;">
 <button type="button" class="button button-default" style="vertical-align: middle;" id="xv_add_custom_field"><i class="fa fa-plus-circle" ></i> <?php esc_html_e('Add Field','gravity-forms-infusionsoft-crm')?></button>
  
  </div></div>
 

  </div></div>
  <div class="clear"></div>
  </div>
<!--add new field box template--->
<script type="text/javascript">
var crm_fields=<?php echo json_encode($json_fields); ?>;
</script> 
  <?php
 }
 ?>
  </div>

  <div class="clear"></div>
  </div>
  </div>
<div class="vx_div">
   <div class="vx_head">
<div class="crm_head_div"> <?php esc_html_e('5. When to Send Entry to Infusionsoft.', 'gravity-forms-infusionsoft-crm'); ?></div>
<div class="crm_btn_div" title="<?php esc_html_e('Expand / Collapse','gravity-forms-infusionsoft-crm') ?>"><i class="fa crm_toggle_btn vx_action_btn fa-minus"></i></div>
<div class="crm_clear"></div> 
  </div>

  <div class="vx_group">
  
   <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_manual_export">
  <?php esc_html_e('Send Entry to Infusionsoft/Keap', 'gravity-forms-infusionsoft-crm'); ?>
  <?php gform_tooltip("vx_manual_export") ?>
  </label>
  </div>
  <div class="vx_col2">
  <fieldset>
  <legend class="screen-reader-text"><span>
  <?php esc_html_e('Send Entry', 'gravity-forms-infusionsoft-crm'); ?>
  </span></legend>
  <label for="crm_manual_export">
  <select name="meta[manual_export]" >
  <?php
  $events_arr=array(''=>__( 'When anyone submits form.', 'gravity-forms-infusionsoft-crm'),'1'=>__( 'Manually send the entries to Infusionsoft/Keap.', 'gravity-forms-infusionsoft-crm'));
  if(self::$is_pr){
      $events_arr['2']=__( 'When payment is complete.', 'gravity-forms-infusionsoft-crm');
      //$events_arr['3']=__( 'When subscription payment is complete.', 'gravity-forms-infusionsoft-crm');
      $events_arr['4']=__( 'When form submission is Complete.', 'gravity-forms-infusionsoft-crm');
  }
  foreach($events_arr as $k=>$v){
  $sel="";
  if($this->post('manual_export',$meta) == $k)
  $sel='selected="selected"';
                   echo "<option value='".esc_attr($k)."' $sel >".esc_html($v)."</option>";
               } 
              ?>
  </select>
 </label>
  </fieldset>
  </div>
  <div style="clear: both;"></div>
  </div>
  
  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_optin">
  <?php esc_html_e("Opt-In Condition", 'gravity-forms-infusionsoft-crm'); ?>
  <?php gform_tooltip("vx_optin_condition") ?>
  </label>
  </div>
  <div class="vx_col2">
  <div>
  <input type="checkbox" style="margin-top: 0px;" id="crm_optin" class="crm_toggle_check" name="meta[optin_enabled]" value="1" <?php echo !empty($meta["optin_enabled"]) ? "checked='checked'" : ""?>/>
  <label for="crm_optin">
  <?php esc_html_e("Enable", 'gravity-forms-infusionsoft-crm'); ?>
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
  <div class="vx_filter_or" data-id="<?php echo $filter_k ?>">
  <?php if($sno>1){ ?>
  <div class="vx_filter_label">
  <?php esc_html_e('OR','gravity-forms-infusionsoft-crm') ?>
  </div>
  <?php } ?>
  <div class="vx_filter_div">
  <?php
  if(is_array($filter_v)){
  $sno_i=0;
  foreach($filter_v as $s_k=>$s_v){ $s_k=esc_attr($s_k);  
  $sno_i++;
  ?>
  <div class="vx_filter_and">
  <?php if($sno_i>1){ ?>
  <div class="vx_filter_label">
  <?php esc_html_e('AND','gravity-forms-infusionsoft-crm') ?>
  </div>
  <?php } ?>
  <div class="vx_filter_field vx_filter_field1">
  <select id="crm_optin_field" name="meta[filters][<?php echo $filter_k ?>][<?php echo $s_k ?>][field]" class='optin_select_filter'>
  <?php 
  echo $this->gf_fields_options($form_id,$this->post('field',$s_v));
                ?>
  </select>
  </div>
  <div class="vx_filter_field vx_filter_field2">
  <select name="meta[filters][<?php echo $filter_k ?>][<?php echo $s_k ?>][op]" >
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
  <input type="text" class="vxc_filter_text" placeholder="<?php esc_html_e('Value','gravity-forms-infusionsoft-crm') ?>" value="<?php echo $this->post('value',$s_v) ?>" name="meta[filters][<?php echo $filter_k ?>][<?php echo $s_k ?>][value]">
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
  <button class="button button-default button-small vx_add_and" title="<?php esc_html_e('Add AND Filter','gravity-forms-infusionsoft-crm'); ?>"><i class="vx_icons-s vx_trash_and fa fa-hand-o-right"></i>
  <?php esc_html_e('Add AND Filter','gravity-forms-infusionsoft-crm') ?>
  </button>
  <?php if($sno>1){ ?>
  <a href="#" class="vx_trash_or">
  <?php esc_html_e('Trash','gravity-forms-infusionsoft-crm') ?>
  </a>
  <?php } ?>
  </div>
  </div>
  </div>
  <?php    } ?>
  <div class="vx_btn_div">
  <button class="button button-default  vx_add_or" title="<?php esc_html_e('Add OR Filter','gravity-forms-infusionsoft-crm'); ?>"><i class="vx_icons vx_trash_and fa fa-check"></i>
  <?php esc_html_e('Add OR Filter','gravity-forms-infusionsoft-crm') ?>
  </button>
  </div>
  </div>
  
<!--------- template------------>
<div style="display: none;" id="vx_filter_temp">
  <div class="vx_filter_or">
  <div class="vx_filter_label">
  <?php esc_html_e('OR','gravity-forms-infusionsoft-crm') ?>
  </div>
  <div class="vx_filter_div">
  <div class="vx_filter_and">
  <div class="vx_filter_label vx_filter_label_and">
  <?php esc_html_e('AND','gravity-forms-infusionsoft-crm') ?>
  </div>
  <div class="vx_filter_field vx_filter_field1">
  <select class="optin_select_filter" name="field">
  <?php 
  echo $this->gf_fields_options($form_id);
                ?>
  </select>
  </div>
  <div class="vx_filter_field vx_filter_field2">
  <select name="op" >
  <?php
                 foreach($vx_op as $k=>$v){
  
                   echo '<option value="'.esc_attr($k).'" >'.esc_html($v)."</option>";
               } 
              ?>
  </select>
  </div>
  <div class="vx_filter_field vx_filter_field3">
  <input type="text" class="vxc_filter_text" placeholder="<?php esc_html_e('Value','gravity-forms-infusionsoft-crm') ?>" name="value">
  </div>
  <div class="vx_filter_field vx_filter_field4"><i class="vx_icons vx_trash_and vxc_tips fa fa-trash-o"></i></div>
  <div style="clear: both;"></div>
  </div>
  <div class="vx_btn_div">
  <button class="button button-default button-small vx_add_and" title="<?php esc_html_e('Add AND Filter','gravity-forms-infusionsoft-crm'); ?>"><i class="vx_icons vx_trash_and  fa fa-hand-o-right"></i>
  <?php esc_html_e('Add AND Filter','gravity-forms-infusionsoft-crm') ?>
  </button>
  <a href="#" class="vx_trash_or">
  <?php esc_html_e('Trash','gravity-forms-infusionsoft-crm') ?>
  </a> </div>
  </div>
  </div>
  </div>
  <div style="display: none;">
<?php 
$form = RGFormsModel::get_form_meta($form_id);
if(!empty($form['fields'])){
 $temp_field=new stdClass();
 $temp_field->id='status';  
 $ops=array();
 $ops[]=array('value'=>'active','text'=>'Active');    
$ops[]=array('value'=>'trash','text'=>'Trash');    
$ops[]=array('value'=>'spam','text'=>'Spam'); 
 $temp_field->choices=$ops;   
 $form['fields'][]=$temp_field;
foreach($form['fields'] as $k=>$v){
$ops=array();
 if(!empty($v->choices)){
 $ops=$v->choices;   
} 
    if(!empty($ops)){ 
?>
  <select id="cfx_op_<?php echo esc_attr($v->id) ?>" class="cfx_op">
  <?php
foreach($ops as $v_op){
?>
<option value="<?php echo esc_attr($v_op['value']); ?>"><?php echo esc_attr($v_op['text']); ?></option>
<?php } ?>
  </select> 
<?php        
    }
} }
   ?>
  </div>
  <!--------- template end ------------>
  </div>
  </div>
  <div style="clear: both;"></div>
  </div>
<?php
  if($module != 'Order'){ 
         $settings=get_option($this->type.'_settings',array());
         if(!empty($settings['notes'])){
?>

  <div class="vx_row">
  <div class="vx_col1">
  <label for="vx_notes"><?php esc_html_e('Entry Notes ', 'gravity-forms-infusionsoft-crm');  gform_tooltip('vx_entry_notes');?></label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="vx_notes" class="crm_toggle_check" name="meta[entry_notes]" value="1" <?php echo !empty($meta['entry_notes']) ? 'checked="checked"' : ''?> autocomplete="off"/>
    <label for="vx_notes"><?php esc_html_e('Add / delete notes to Infusionsoft when added / deleted in Gravity Forms', 'gravity-forms-infusionsoft-crm'); ?></label>
  
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
   $panel_count=5;
   $s_fields=array();
   if(is_array($fields)){
       foreach($fields as $k=>$v){
        if(!empty($v['search'])){
      $s_fields[$k]=$v;      
        }   
       }   
   }
if(!empty($s_fields)){
      $panel_count++;
  ?>     
  <div class="vx_div"> 
  <div class="vx_head ">
<div class="crm_head_div"> <?php  echo sprintf(__('%s. Choose Primary Key.',  'gravity-forms-infusionsoft-crm' ),$panel_count); ?></div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn fa-minus" title="<?php esc_html_e('Expand / Collapse','gravity-forms-infusionsoft-crm') ?>"></i></div>
<div class="crm_clear"></div> 
  </div>                    
    <div class="vx_group">
  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_primary_field"><?php esc_html_e('Select Primary Key','%dd%') ?></label>
  </div><div class="vx_col2">
  <select id="crm_primary_field" name="meta[primary_key]" class="vx_sel vx_input_100" autocomplete="off">
  <?php echo $this->crm_select($s_fields,$this->post('primary_key',$meta)); ?>
  </select> 
  <div class="description" style="float: none; width: 90%"><?php esc_html_e('If you want to update a pre-existing object, select what should be used as a unique identifier ("Primary Key"). For example, this may be an email address, lead ID, or address. When a new entry comes in with the same "Primary Key" you select, a new object will not be created, instead the pre-existing object will be updated.', '%dd%'); ?></div>
  </div>
  <div class="clear"></div>
  </div>
 <div class="vx_row">
  <div class="vx_col1">
  <label for="vx_update"><?php esc_html_e('Update Entry ', '%dd%');?></label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="vx_update" class="crm_toggle_check" name="meta[update]" value="1" <?php echo !empty($meta['update']) ? 'checked="checked"' : ''?> autocomplete="off"/>
    <label for="vx_update"><?php esc_html_e('Do not update entry, if already exists', '%dd%'); ?></label>
  
  </div>
  <div class="clear"></div>
  </div>
  
   <div class="vx_row">
  <div class="vx_col1">
  <label for="vx_new_entry"><?php esc_html_e('Create New Entry ', 'gravity-forms-infusionsoft-crm');?></label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="vx_new_entry" class="crm_toggle_check" name="meta[new_entry]" value="1" <?php echo !empty($meta['new_entry']) ? 'checked="checked"' : ''?> autocomplete="off"/>
    <label for="vx_new_entry"><?php esc_html_e('Do not create new entry in Infusionsoft', 'gravity-forms-infusionsoft-crm'); ?></label>
  
  </div>
  <div class="clear"></div>
  </div>
    
  </div>

  </div>
  <!-------------------------- lead owner -------------------->
  <?php
}

if($module != 'Order'){
 $users=$this->post('users',$info_meta);
 $tags_list=$this->post('tags',$info_meta);  
if($module != 'Opportunity'){
  ?>
<div class="vx_div">
     <div class="vx_head">
<div class="crm_head_div"> <?php echo sprintf(__('%s. Add Note.', 'gravity-forms-infusionsoft-crm'),$panel_count+=1); ?></div>
<div class="crm_btn_div" title="<?php esc_html_e('Expand / Collapse','gravity-forms-infusionsoft-crm') ?>"><i class="fa crm_toggle_btn fa-minus"></i></div>
<div class="crm_clear"></div> 
  </div>


  <div class="vx_group">

    <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_note">
  <?php esc_html_e("Add Note ", 'gravity-forms-infusionsoft-crm'); ?>
  <?php gform_tooltip("vx_entry_note") ?>
  </label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="crm_note" class="crm_toggle_check" name="meta[note_check]" value="1" <?php echo !empty($meta['note_check']) ? "checked='checked'" : ""?>/>
  <label for="crm_note_div">
  <?php esc_html_e("Enable", 'gravity-forms-infusionsoft-crm'); ?>
  </label>
  </div>
  <div style="clear: both;"></div>
  </div>
  <div id="crm_note_div" style="margin-top: 16px; <?php echo empty($meta["note_check"]) ? "display:none" : ""?>">
  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_note_fields">
  <?php esc_html_e( 'Note Fields ', 'gravity-forms-infusionsoft-crm' ); gform_tooltip("vx_note_fields") ?>
  </label>
  </div>
   <div class="vx_col2 entry_col2">
  <textarea name="meta[note_val]"  placeholder="<?php esc_html_e("{field-id} text",'gravity-forms-infusionsoft-crm')?>" class="vx_input_100 vxc_field_input" style="height: 60px"><?php
  if(!empty($meta['note_fields']) && is_array($meta['note_fields'])){
          $meta['note_val']='{'.implode("}\n{",$meta['note_fields'])."}";
}
   echo $this->post('note_val',$meta); ?></textarea>
<div class="howto"><?php echo sprintf(__('You can add a form field %s in custom value from following form fields. You can use %s for separating note title from body','gravity-forms-infusionsoft-crm'),'<code>{field_id}</code>','<code>?</code>')?></div>

<select name="field"  class="vxc_field_option vx_input_100">
<?php echo $this->gf_fields_options($form_id); ?>
</select>
   </div>
 
  <div style="clear: both;"></div>
  </div>
<?php
      if(in_array($module,array('Contact','Company'))){
  ?>  
    <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_type_note">
  <?php esc_html_e( 'Add Note to ', 'gravity-forms-infusionsoft-crm' ); ?>
  </label>
  </div>
  <div class="vx_col2">
  
  <select id="crm_type_note" class="vx_input_100" name="meta[send_note_to]">
<?php
$ops=array(''=>'Notes List','field'=>'Note Field');
foreach($ops as $k=>$v){
  $sel="";
  if($this->post('send_note_to',$meta) == $k){
  $sel='selected="selected"';
  }
echo "<option value='".esc_attr($k)."' $sel >".esc_html($v)."</option>";
} 
?>
</select>
    
   </div>
  <div style="clear: both;"></div>
  </div>
<?php
      }
  ?>  
  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_disable_note">
  <?php esc_html_e( 'Disable Note ', 'gravity-forms-infusionsoft-crm' ); gform_tooltip("vx_disable_note") ?>
  </label>
  </div>
  <div class="vx_col2">
  
  <input type="checkbox" style="margin-top: 0px;" id="crm_disable_note" class="crm_toggle_check" name="meta[disable_entry_note]" value="1" <?php echo !empty($meta['disable_entry_note']) ? "checked='checked'" : ""?>/>
  <label for="crm_disable_note">
  <?php esc_html_e('Do not Add Note to Notes List if entry already exists in Infusionsoft', 'gravity-forms-infusionsoft-crm'); ?>
  </label>
    
   </div>
  <div style="clear: both;"></div>
  </div>
  
  </div>
  
  </div>
  </div>  
<?php
}
   if(in_array($module,array('Contact','Opportunity'))){ 
  ?>  
  <!-------------------------- lead owner -------------------->
<div class="vx_div vx_refresh_panel ">    
      <div class="vx_head ">
<div class="crm_head_div"> <?php echo sprintf(__('%s. Object Owner',  'gravity-forms-infusionsoft-crm' ),++$panel_count); ?></div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn fa-minus" title="<?php esc_html_e('Expand / Collapse','gravity-forms-infusionsoft-crm') ?>"></i></div>
<div class="crm_clear"></div> 
  </div>                 
    <div class="vx_group ">
   <div class="vx_row"> 
   <div class="vx_col1"> 
  <label><?php esc_html_e("Assign Owner ", 'gravity-forms-infusionsoft-crm'); gform_tooltip('vx_owner_check');?></label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="crm_owner" class="crm_toggle_check <?php if(empty($users)){echo 'vx_refresh_btn';} ?>" name="meta[owner]" value="1" <?php echo !empty($meta["owner"]) ? "checked='checked'" : ""?> autocomplete="off"/>
    <label for="crm_owner"><?php esc_html_e("Enable", 'gravity-forms-infusionsoft-crm'); ?></label>
  </div>
<div class="clear"></div>
</div>
    <div id="crm_owner_div" style="<?php echo empty($meta["owner"]) ? "display:none" : ""?>">
  <div class="vx_row">
  <div class="vx_col1">
  <label><?php esc_html_e('Users List ','gravity-forms-infusionsoft-crm'); gform_tooltip('vx_owners'); ?></label>
  </div>
  <div class="vx_col2">
  <button class="button vx_refresh_data" data-id="refresh_users" type="button" autocomplete="off" style="vertical-align: baseline;">
  <span class="reg_ok"><i class="fa fa-refresh"></i> <?php esc_html_e('Refresh Data','gravity-forms-infusionsoft-crm') ?></span>
  <span class="reg_proc"><i class="fa fa-refresh fa-spin"></i> <?php esc_html_e('Refreshing...','gravity-forms-infusionsoft-crm') ?></span>
  </button>
  </div> 
   <div class="clear"></div>
  </div> 

  <div class="vx_row">
   <div class="vx_col1">
  <label for="crm_sel_user"><?php esc_html_e('Select User ','gravity-forms-infusionsoft-crm'); gform_tooltip('vx_sel_owner'); ?></label>
</div> 
<div class="vx_col2">

  <select id="crm_sel_user" name="meta[user]" style="width: 100%;" autocomplete="off">
  <?php echo $this->gen_select($users,$meta['user'],__('Select User','gravity-forms-infusionsoft-crm')); ?>
  </select>

   </div>

   <div class="clear"></div>
   </div>
 
  
  </div>
  

  </div>
  </div>
<?php  
   }
$file=self::$path.'pro/pro-mapping.php';
if(self::$is_pr && file_exists($file)){
include_once($file);
}
 }

if($module == 'Contact' ){
$company_feeds=$this->get_object_feeds($form_id,$account,'Company');
?>
    <div class="vx_div vx_refresh_panel ">    
      <div class="vx_head ">
<div class="crm_head_div"> <?php echo sprintf(__('%s. Assign Company',  'gravity-forms-infusionsoft-crm' ),++$panel_count); 
?></div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn fa-minus" title="<?php esc_html_e('Expand / Collapse','gravity-forms-infusionsoft-crm') ?>"></i></div>
<div class="crm_clear"></div> 
  </div>                 
    <div class="vx_group ">

        <div class="vx_row"> 
   <div class="vx_col1"> 
  <label for="company_check"><?php esc_html_e("Assign Company ", 'gravity-forms-infusionsoft-crm'); gform_tooltip('vx_assign_company');?></label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="company_check" class="crm_toggle_check" name="meta[company_check]" value="1" <?php echo !empty($meta["company_check"]) ? "checked='checked'" : ""?> autocomplete="off"/>
    <label for="contact_check"><?php esc_html_e("Enable", 'gravity-forms-infusionsoft-crm'); ?></label>
  </div>
<div class="clear"></div>
</div>
    <div id="company_check_div" style="<?php echo empty($meta["company_check"]) ? "display:none" : ""?>">
         <div class="vx_row">
   <div class="vx_col1">
  <label for="object_company"><?php esc_html_e('Select Company Feed ','gravity-forms-infusionsoft-crm'); gform_tooltip('vx_sel_company'); ?></label>
</div> 
<div class="vx_col2">

  <select id="object_company" name="meta[object_company]" style="width: 100%;" autocomplete="off">
  <?php echo $this->gen_select($company_feeds ,$meta['object_company'],__('Select Company Feed','gravity-forms-infusionsoft-crm')); ?>
  </select>

   </div>

   <div class="clear"></div>
   </div>
    </div>

  </div>
  </div>
  <?php
  }
  if(in_array($module,array('Order','Opportunity'))){
      $contact_feeds=$this->get_object_feeds($form_id,$account,'Contact');
      
  ?>
  <div class="vx_div vx_refresh_panel ">    
      <div class="vx_head ">
<div class="crm_head_div"> <?php  echo sprintf(__('%s. Assign Contact',  'gravity-forms-infusionsoft-crm' ),++$panel_count); echo $req_span2; ?></div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn fa-minus" title="<?php esc_html_e('Expand / Collapse','gravity-forms-infusionsoft-crm') ?>"></i></div>
<div class="crm_clear"></div> 
  </div>                 
    <div class="vx_group ">

        <div class="vx_row"> 
   <div class="vx_col1"> 
  <label for="contact_check"><?php esc_html_e("Assign Contact ", 'gravity-forms-infusionsoft-crm'); gform_tooltip('vx_assign_contact');?></label>
  </div>
  <div class="vx_col2">
  <input type="checkbox" style="margin-top: 0px;" id="contact_check" class="crm_toggle_check" name="meta[contact_check]" value="1" <?php echo !empty($meta["contact_check"]) ? "checked='checked'" : ""?> autocomplete="off"/>
    <label for="contact_check"><?php esc_html_e("Enable", 'gravity-forms-infusionsoft-crm'); ?></label>
  </div>
<div class="clear"></div>
</div>
    <div id="contact_check_div" style="<?php echo empty($meta["contact_check"]) ? "display:none" : ""?>">
         <div class="vx_row">
   <div class="vx_col1">
  <label for="crm_sel_contact"><?php esc_html_e('Select Contact Feed ','gravity-forms-infusionsoft-crm'); gform_tooltip('vx_sel_contact'); ?></label>
</div> 
<div class="vx_col2">

  <select id="crm_sel_contact" name="meta[object_contact]" style="width: 100%;" autocomplete="off">
  <?php echo $this->gen_select($contact_feeds ,$meta['object_contact'],__('Select Contact Feed','gravity-forms-infusionsoft-crm')); ?>
  </select>

   </div>

   <div class="clear"></div>
   </div>
    </div>

  </div>
  </div>
  
    <?php
  }  ?>

  <div class="button-controls submit" style="padding-left: 5px;">
  <input type="hidden" name="form_id" value="<?php echo esc_attr($form_id) ?>">
  <button type="submit" title="<?php esc_html_e('Save Feed','gravity-forms-infusionsoft-crm'); ?>" name="<?php echo esc_attr($this->id) ?>_submit" class="button button-primary button-hero"> <i class="vx_icons vx vx-arrow-50"></i> <?php echo empty($fid) ? esc_html__("Save Feed", 'gravity-forms-infusionsoft-crm') : esc_html__("Update Feed", 'gravity-forms-infusionsoft-crm'); ?> </button>
  </div>

    <?php
      do_action('add_section_mapping_'.$this->id);
  ?>

