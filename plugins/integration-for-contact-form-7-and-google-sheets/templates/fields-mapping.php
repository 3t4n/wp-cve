<?php
if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }                                 
 ?>
 <div  class="vx_div">
   <div class="vx_head">
<div class="crm_head_div"> <?php esc_html_e('6. Map Form Fields to Google Sheets Fields.', 'integration-for-contact-form-7-and-spreadsheets'); ?></div>
<div class="crm_btn_div" title="<?php esc_html_e('Expand / Collapse','integration-for-contact-form-7-and-spreadsheets') ?>"><i class="fa crm_toggle_btn vx_action_btn fa-minus"></i></div>
<div class="crm_clear"></div> 
  </div>
  <div class="vx_group">
  <div class="vx_col1">
  <label>
  <?php esc_html_e("Fields Mapping", 'integration-for-contact-form-7-and-spreadsheets'); ?>
  <?php $this->tooltip("vx_map_fields") ?>
  </label>
  </div>
  <div class="vx_col2">
  <div id="vx_fields_div">
  <?php 
   $req_span=" <span class='vx_red vx_required'>(".esc_html__('Required','integration-for-contact-form-7-and-spreadsheets').")</span>";
 $req_span2=" <span class='vx_red vx_required vx_req_parent'>(".esc_html__('Required','integration-for-contact-form-7-and-spreadsheets').")</span>";
  foreach($map_fields as $k=>$v){
        if(isset($v['name_c'])){
  $v['name']=$v['name_c'];      
  $v['label']=__('Custom Field','integration-for-contact-form-7-and-spreadsheets');      
  } 
  if( in_array($v['name'] , array("OwnerId","AccountId","ContractId") )){
  //  continue;
}
if($module == "Order" && in_array($v['name'] , array("Status" ))){
    continue;
} 
  $sel_val=isset($map[$k]['field']) ? $map[$k]['field'] : ""; 
  $val_type=isset($map[$k]['type']) && !empty($map[$k]['type']) ? $map[$k]['type'] : "field"; 

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
<div class="crm_head_div"><span class="crm_head_text crm_text_label">  <?php echo esc_html($v['label']);?></span> <?php echo wp_kses_post($req_html) ?></div>
<div class="crm_btn_div">
<?php
 if(! $required){   
?>
<i class="vx_remove_btn vx_remove_btn vx_action_btn fa fa-trash-o" title="<?php esc_html_e('Delete','integration-for-contact-form-7-and-spreadsheets'); ?>"></i>
<?php } ?>
<i class="fa crm_toggle_btn vx_action_btn vx_btn_inner <?php echo esc_attr($btn_icon) ?>" title="<?php esc_html_e('Expand / Collapse','integration-for-contact-form-7-and-spreadsheets') ?>"></i>
</div>
<div class="crm_clear"></div> </div>
<div class="more_options crm_panel_content" style="display: <?php echo esc_attr($display) ?>;">
  <?php if(!isset($v['name_c'])){ ?>

  <div class="crm-panel-description">
  <span class="crm-desc-name-div"><?php echo esc_html__('Name:','integration-for-contact-form-7-and-spreadsheets')." ";?><span class="crm-desc-name"><?php echo esc_html($v['name']); ?></span> </span>
  <?php if($this->post('type',$v) !=""){ ?>
    <span class="crm-desc-type-div">, <?php echo esc_html__('Type:','integration-for-contact-form-7-and-spreadsheets')." ";?><span class="crm-desc-type"><?php echo esc_html($v['type']); ?></span> </span>
<?php
   }
  if($this->post('maxlength',$v) !=""){ 
   ?>
   <span class="crm-desc-len-div">, <?php echo esc_html__('Max Length:','integration-for-contact-form-7-and-spreadsheets')." ";?><span class="crm-desc-len"><?php echo esc_html($v['maxlength']); ?></span> </span>
  <?php 
  }
   if($this->post('eg',$v) !=""){ 
   ?>
   <span class="crm-eg-div">, <?php echo esc_html__('e.g:','integration-for-contact-form-7-and-spreadsheets')." ";?><span class="crm-eg"><?php echo esc_html($v['eg']); ?></span> </span>
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
<div class="entry_col1 vx_label"><?php esc_html_e('Field API Name','integration-for-contact-form-7-and-spreadsheets') ?></div>
<div class="entry_col2">
<input type="text" name="meta[map][<?php echo esc_attr($k) ?>][name_c]" value="<?php echo esc_attr($v['name_c']) ?>" placeholder="<?php esc_html_e('Field API Name','integration-for-contact-form-7-and-spreadsheets') ?>" class="vx_input_100">
<div class="howto"><?php echo esc_html__('In Google Sheets, go to setup -> customize -> leads -> fields and copy API name of a field','integration-for-contact-form-7-and-spreadsheets')?></div>
</div>
<div class="crm_clear"></div>
</div> 
<?php             
    }
?>
<div class="entry_row">
<div class="entry_col1 vx_label"><label  for="vx_type_<?php echo esc_attr($k) ?>"><?php esc_html_e('Field Type','integration-for-contact-form-7-and-spreadsheets') ?></label></div>
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
<label for="vx_field_<?php echo esc_attr($k) ?>" style="<?php if($this->post2($k,'type',$map) != ''){echo 'display:none';} ?>" class="vxc_fields vxc_field_"><?php esc_html_e('Select Field','integration-for-contact-form-7-and-spreadsheets') ?></label>

<label for="vx_value_<?php echo esc_attr($k) ?>" style="<?php if($this->post2($k,'type',$map) != 'value'){echo 'display:none';} ?>" class="vxc_fields vxc_field_value"> <?php esc_html_e('Custom Value','integration-for-contact-form-7-and-spreadsheets') ?></label>
</div>
<div class="entry_col2">
<div class="vxc_fields vxc_field_value" style="<?php if($this->post2($k,'type',$map) != 'value'){echo 'display:none';} ?>">

<textarea name='meta[map][<?php echo esc_attr($k)?>][value]'  id="vx_value_<?php echo esc_attr($k) ?>" placeholder='<?php esc_html_e("Custom Value",'integration-for-contact-form-7-and-spreadsheets')?>' class='vx_input_100 vxc_field_input'><?php if(!empty($map[$k]['value'])){ echo htmlentities($map[$k]['value']); } ?></textarea>

<div class="howto"><?php echo sprintf(esc_html__('You can add a form field %s in custom value from following form fields','integration-for-contact-form-7-and-spreadsheets'),'<code>{field_id}</code>')?></div>
</div>


<select name="meta[map][<?php echo esc_attr($k) ?>][field]"  id="vx_field_<?php echo esc_attr($k) ?>" class="vxc_field_option vx_input_100">
<?php echo $this->form_fields_options($form_id,$sel_val,$this->account,$feed['id']); ?>
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
<div class="crm_head_div"><span class="crm_head_text crm_text_label">  <?php esc_html_e('Custom Field', 'integration-for-contact-form-7-and-spreadsheets');?></span> </div>
<div class="crm_btn_div">
<i class="vx_remove_btn vx_action_btn fa fa-trash-o" title="<?php esc_html_e('Delete','integration-for-contact-form-7-and-spreadsheets'); ?>"></i>
<i class="fa crm_toggle_btn vx_action_btn vx_btn_inner fa-minus" title="<?php esc_html_e('Expand / Collapse','integration-for-contact-form-7-and-spreadsheets') ?>"></i>
</div>
<div class="crm_clear"></div> </div>
<div class="more_options crm_panel_content" style="display: block;">

<?php
    if($api_type  != 'web'){
?>

  <div class="crm-panel-description">
  <span class="crm-desc-name-div"><?php echo esc_html__('Name:','integration-for-contact-form-7-and-spreadsheets')." ";?><span class="crm-desc-name"></span> </span>
  <span class="crm-desc-type-div">, <?php echo esc_html__('Type:','integration-for-contact-form-7-and-spreadsheets')." ";?><span class="crm-desc-type"></span> </span>
  <span class="crm-desc-len-div">, <?php echo esc_html__('Max Length:','integration-for-contact-form-7-and-spreadsheets')." ";?><span class="crm-desc-len"></span> </span>
 <span class="crm-eg-div">, <?php echo esc_html__('e.g:','integration-for-contact-form-7-and-spreadsheets')." ";?><span class="crm-eg"></span> </span>
   </div> 

<?php
    }
?>
<div class="vx_margin">

<?php
    if($api_type  == 'web'){
?>
<div class="entry_row">
<div class="entry_col1 vx_label"><?php esc_html_e('Field API Name','integration-for-contact-form-7-and-spreadsheets') ?></div>
<div class="entry_col2">
<input type="text" name="name_c" placeholder="<?php esc_html_e('Field API Name','integration-for-contact-form-7-and-spreadsheets') ?>" class="vx_input_100">
<div class="howto"><?php echo esc_html__('In Google Sheets, go to setup -> customize -> leads -> fields and copy API name of a field','integration-for-contact-form-7-and-spreadsheets')?></div>
</div>
<div class="crm_clear"></div>
</div> 
<?php
    }
?>
<div class="entry_row">
<div class="entry_col1 vx_label"><label  for="vx_type"><?php esc_html_e('Field Type','integration-for-contact-form-7-and-spreadsheets') ?></label></div>
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
<label for="vx_field" class="vxc_fields vxc_field_"><?php esc_html_e('Select Field','integration-for-contact-form-7-and-spreadsheets') ?></label>

<label for="vx_value" style="display:none" class="vxc_fields vxc_field_value"> 
<?php esc_html_e('Custom Value','integration-for-contact-form-7-and-spreadsheets') ?></label>
</div>
<div class="entry_col2">
<div class="vxc_fields vxc_field_value" style="display:none">
<textarea name='value'  placeholder='<?php esc_html_e("Custom Value",'integration-for-contact-form-7-and-spreadsheets')?>' class="vx_input_100 vxc_field_input"></textarea>
<div class="howto"><?php echo sprintf(esc_html__('You can add a form field %s in custom value from following form fields','integration-for-contact-form-7-and-spreadsheets'),'<code>{field_id}</code>')?></div>
</div>

<select name="field"  id="vx_field" class="vxc_field_option vx_input_100">
<?php echo  $this->form_fields_options($form_id,'',$this->account,$id); ?>
</select>


</div>
<div class="crm_clear"></div>
</div> 

  </div></div>
  <div class="clear"></div>
  </div>
   </div>
   <!--end field box template--->
     <?php
  if($api_type =="web"){
  if(vxcf_googlesheets::$is_pr){
   ?>
  <div class="vx_fields_footer">
  <div class="vx_row">
  <div class="vx_col1"> &nbsp;</div><div class="vx_col2">
  <button type="button" class="button button-default" id="xv_add_custom_field"><i class=" fa fa-plus-circle" ></i> <?php esc_html_e('Add Custom Field','integration-for-contact-form-7-and-spreadsheets')?></button></div>
  <div class="clear"></div></div>
   </div>
 <?php } }else{ ?> 
   <div class="crm_panel crm_panel_100">
<div class="crm_panel_head2">
<div class="crm_head_div"><span class="crm_head_text ">  <?php esc_html_e("Add New Field", 'integration-for-contact-form-7-and-spreadsheets');?></span> </div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn vx_btn_inner fa-minus" style="display: none;" title="<?php esc_html_e('Expand / Collapse','integration-for-contact-form-7-and-spreadsheets'); ?>"></i></div>
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
     $ops=array();
     if(!empty($v['options'])){
    foreach($v['options'] as $op){
        $ops[$op['value']]=$op['label'];
        
    }     
   $v['options']=$ops;  

     }
     $json_fields[$k]=$v;
   $disable='';
   if(isset($map_fields[$k])){
    $disable='disabled="disabled"';   
   } 
echo '<option value="'.esc_html($k).'" '.$disable.' >'.esc_html($v['label']).'</option>';   
} ?>
</select>
  </div><div style="display: table-cell;">
 <button type="button" class="button button-default" style="vertical-align: middle;" id="xv_add_custom_field"><i class="fa fa-plus-circle" ></i> <?php esc_html_e('Add Field','integration-for-contact-form-7-and-spreadsheets')?></button>
  
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
  </div>
  <div class="clear"></div>
  </div>
  </div>
  <div class="vx_div">
   <div class="vx_head">
<div class="crm_head_div"> <?php esc_html_e('7. When to Send Entry to Google Sheets.', 'integration-for-contact-form-7-and-spreadsheets'); ?></div>
<div class="crm_btn_div" title="<?php esc_html_e('Expand / Collapse','integration-for-contact-form-7-and-spreadsheets') ?>"><i class="fa crm_toggle_btn vx_action_btn fa-minus"></i></div>
<div class="crm_clear"></div> 
  </div>
 
  <div class="vx_group">
  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_manual_export">
  <?php esc_html_e('Disable Automatic Export', 'integration-for-contact-form-7-and-spreadsheets'); ?>
  <?php $this->tooltip("vx_manual_export") ?>
  </label>
  </div>
  <div class="vx_col2">
  <fieldset>
  <legend class="screen-reader-text"><span>
  <?php esc_html_e('Disable Automatic Export', 'integration-for-contact-form-7-and-spreadsheets'); ?>
  </span></legend>
  <label for="crm_manual_export">
  <input name="meta[manual_export]" id="crm_manual_export" type="checkbox" value="1" <?php echo isset($meta['manual_export'] ) ? 'checked="checked"' : ''; ?>>
  <?php esc_html_e( 'Manually send the entries to Google Sheets.', 'integration-for-contact-form-7-and-spreadsheets'); ?> </label>
  </fieldset>
  </div>
  <div style="clear: both;"></div>
  </div>
  <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_optin">
  <?php esc_html_e("Opt-In Condition", 'integration-for-contact-form-7-and-spreadsheets'); ?>
  <?php $this->tooltip("vx_optin_condition") ?>
  </label>
  </div>
  <div class="vx_col2">
  <div>
  <input type="checkbox" style="margin-top: 0px;" id="crm_optin" class="crm_toggle_check" name="meta[optin_enabled]" value="1" <?php echo !empty($meta["optin_enabled"]) ? "checked='checked'" : ""?>/>
  <label for="crm_optin">
  <?php esc_html_e("Enable", 'integration-for-contact-form-7-and-spreadsheets'); ?>
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
  <?php esc_html_e('OR','integration-for-contact-form-7-and-spreadsheets') ?>
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
  <?php esc_html_e('AND','integration-for-contact-form-7-and-spreadsheets') ?>
  </div>
  <?php } ?>
  <div class="vx_filter_field vx_filter_field1">
  <select id="crm_optin_field" name="meta[filters][<?php echo esc_attr($filter_k) ?>][<?php echo esc_attr($s_k) ?>][field]">
  <?php 
  echo $this->form_fields_options($form_id,$this->post('field',$s_v),$this->account,$id);
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
  <input type="text" class="vxc_filter_text" placeholder="<?php esc_html_e('Value','integration-for-contact-form-7-and-spreadsheets') ?>" value="<?php echo $this->post('value',$s_v) ?>" name="meta[filters][<?php echo esc_attr($filter_k) ?>][<?php echo esc_attr($s_k) ?>][value]">
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
  <button class="button button-default button-small vx_add_and" title="<?php esc_html_e('Add AND Filter','integration-for-contact-form-7-and-spreadsheets'); ?>"><i class="vx_icons-s vx_trash_and fa fa-hand-o-right"></i>
  <?php esc_html_e('Add AND Filter','integration-for-contact-form-7-and-spreadsheets') ?>
  </button>
  <?php if($sno>1){ ?>
  <a href="#" class="vx_trash_or">
  <?php esc_html_e('Trash','integration-for-contact-form-7-and-spreadsheets') ?>
  </a>
  <?php } ?>
  </div>
  </div>
  </div>
  <?php
                          }
                      ?>
  <div class="vx_btn_div">
  <button class="button button-default  vx_add_or" title="<?php esc_html_e('Add OR Filter','integration-for-contact-form-7-and-spreadsheets'); ?>"><i class="vx_icons vx_trash_and fa fa-check"></i>
  <?php esc_html_e('Add OR Filter','integration-for-contact-form-7-and-spreadsheets') ?>
  </button>
  </div>
  </div>
  <!--------- template------------>
  <div style="display: none;" id="vx_filter_temp">
  <div class="vx_filter_or">
  <div class="vx_filter_label">
  <?php esc_html_e('OR','integration-for-contact-form-7-and-spreadsheets') ?>
  </div>
  <div class="vx_filter_div">
  <div class="vx_filter_and">
  <div class="vx_filter_label vx_filter_label_and">
  <?php esc_html_e('AND','integration-for-contact-form-7-and-spreadsheets') ?>
  </div>
  <div class="vx_filter_field vx_filter_field1">
  <select id="crm_optin_field" name="field">
  <?php 
  echo $this->form_fields_options($form_id,'',$this->account,$id);
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
  <input type="text" class="vxc_filter_text" placeholder="<?php esc_html_e('Value','integration-for-contact-form-7-and-spreadsheets') ?>" name="value">
  </div>
  <div class="vx_filter_field vx_filter_field4"><i class="vx_icons vx_trash_and vxc_tips fa fa-trash-o"></i></div>
  <div style="clear: both;"></div>
  </div>
  <div class="vx_btn_div">
  <button class="button button-default button-small vx_add_and" title="<?php esc_html_e('Add AND Filter','integration-for-contact-form-7-and-spreadsheets'); ?>"><i class="vx_icons vx_trash_and  fa fa-hand-o-right"></i>
  <?php esc_html_e('Add AND Filter','integration-for-contact-form-7-and-spreadsheets') ?>
  </button>
  <a href="#" class="vx_trash_or">
  <?php esc_html_e('Trash','integration-for-contact-form-7-and-spreadsheets') ?>
  </a> </div>
  </div>
  </div>
  </div>
  <!--------- template end ------------>
  </div>
  </div>
  <div style="clear: both;"></div>
  </div>

      <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_primary_field3"><?php esc_html_e('Insert Row ','integration-for-contact-form-7-and-spreadsheets') ?></label>
  </div><div class="vx_col2">
  <select id="crm_primary_field3" name="meta[row_type]" class="vx_sel vx_input_100" autocomplete="off">
  <?php
  $key_fields=array(''=>'Add at end - Works if first cell of row is not empty','INSERT_ROWS'=>'Add row at top of sheet - Works if first column of sheet is empty');
  if(!isset($meta['row_type'])){$meta['row_type']='';}
   echo $this->gen_select($key_fields,$meta['row_type'],false); ?>
  </select> 
  </div>
  <div class="clear"></div>
  </div>
  

    <div class="vx_row">
  <div class="vx_col1">
  <label for="crm_primary_field2"><?php esc_html_e('Data Type','integration-for-contact-form-7-and-spreadsheets') ?></label>
  </div><div class="vx_col2">
  <select id="crm_primary_field2" name="meta[data_type]" class="vx_sel vx_input_100" autocomplete="off">
  <?php
  $key_fields=array(''=>'USER_ENTERED - Convert date string to Date','RAW'=>'RAW - Do not process data');
  if(!isset($meta['data_type'])){ $meta['data_type']=''; }
   echo $this->gen_select($key_fields,$meta['data_type'],false); ?>
  </select> 
  </div>
  <div class="clear"></div>
  </div>

  </div> 
  </div>

  <div class="button-controls submit" style="padding-left: 5px;">
  <input type="hidden" name="form_id" value="<?php echo esc_attr($form_id) ?>">
  <button type="submit" title="<?php esc_html_e('Save Feed','integration-for-contact-form-7-and-spreadsheets'); ?>" name="<?php echo esc_attr($this->id) ?>_submit" class="button button-primary button-hero"> <i class="vx_icons vx vx-arrow-50"></i> <?php echo empty($fid) ? esc_html__("Save Feed", 'integration-for-contact-form-7-and-spreadsheets') : esc_html__("Update Feed", 'integration-for-contact-form-7-and-spreadsheets'); ?> </button>
  </div>

  <?php
 do_action('vx_plugin_upgrade_notice_plugin_'.$this->type);
  ?>

