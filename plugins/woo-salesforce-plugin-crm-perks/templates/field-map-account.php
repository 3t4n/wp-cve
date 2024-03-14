<?php
     if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }                        
?> <style type="text/css">
  label span.howto { cursor: default; }
  
  .vx_required{color:red;}
  .vx_contents *{
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  }
    .vx_div{
  padding: 10px 0px 0px 0px;
  }
  .vx_head{
  font-size: 14px;
  font-weight: bold;
  background: #f4f4f4;
   border: 1px solid #e5e5e5;
  }
    .vx_head:hover , .crm_panel_head2:hover{

  background: #f0f0f0;
  }
  .vx_group{
      border: 1px dashed #ccc;
      border-top-width: 0px ;
      padding: 12px 14px;
  }
  .vx_col1{float:left; width: 30%; padding-right: 20px; font-weight: bold;}
  .vx_col2{float:left; width: 70%; padding-right: 20px;}
  @media screen and (max-width: 782px) {
  .vx_col1{float:none; width: 100%;}
  .vx_col2{float:none; width: 100%}    
  }
  
  .alert_danger {
  background: #ca5952;
  padding: 5px;
  font-size: 12px;
  font-weight: bold;
  color: #fff ;
  text-align: center;
  margin-top: 10px;
  }
   .alert_danger a{
     color: #fff ;    
   }
   .alert_danger a:hover , .alert_danger a:active , .alert_danger a:visited{
     color: #f5f5f5;  
   }
  .vx_sel{
  min-width: 220px;
  width: 100%;
  }

  .vx_wrapper{
  border: 0px solid #e5e5e5;
  margin: 20px auto;
  width: 100%;
  background: #fff;
  -webkit-box-shadow:0 1px 1px rgba(0,0,0,.04);box-shadow:0 1px 1px rgba(0,0,0,.04);
  }

  .vx_heading{
  font-size: 18px;
  padding: 10px 20px;
  border-bottom: 1px dashed #ccc;
  }
  /*********custom fields***************/
  .vx_filter_div{
  border: 1px solid #eee;
  padding: 10px;
  background: #f3f3f3; 
  border-radius: 4px;  
  }
  .vx_filter_field{
  float: left; 
  }
  .vx_filter_field1{
  width: 32%;
  }
  .vx_filter_field2{
  width: 30%;
  }
  .vx_filter_field3{
  width: 30%;
  }
  .vx_filter_field4{
  width: 8%;
  }
  .vx_filter_field select{
  width:90%; display: block; 
  }
  .vx_btn_div{
  padding: 10px 0px;
  }
  .vx_filter_label{
  padding: 3px; 
  }
  .vxc_filter_text{
  max-width: 98%;
  width: 96%;
  }
  .vx_trash_or{
  color: #D20000;
  margin-left: 10px;
  }
  
  .vx_trash_or:hover{
  color: #C24B4B;
  }
  .vx_icons{
color: #999;
  }
  .vx_icons-s{
  font-size: 12px;
  vertical-align: middle;  
  }
  .vx_icons-h{
  font-size: 16px;
  line-height: 28px;
  vertical-align: middle; 
  cursor: pointer; 
  }
  .vx_icons:hover , .vx_icons-h:hover{
  color: #555;
  }
  .vxc_tips{
      font-size: 14px;
      font-weight: normal;
      vertical-align: baseline;
  }
  .reg_proc{
      display: none;
  }
    .vx_fields_footer{
  padding: 10px 0px;
  background: #f1f1f1;
  }
  .vx_remove_custom{
      margin-right: 8px;
  }
/*******fields boxes****************/
.crm_panel * {
  -webkit-box-sizing: border-box; /* Safari 3.0 - 5.0, Chrome 1 - 9, Android 2.1 - 3.x */
  -moz-box-sizing: border-box;    /* Firefox 1 - 28 */
  box-sizing: border-box;  
}
.crm_panel_100{
margin: 10px 0;
}
.crm_panel_50{
    width: 48%;
    margin: 1%;
    min-width: 300px;
    float: left;
}
.crm_panel_head{
    background: linear-gradient(to bottom, rgba(255, 255, 255, 1) 0%, rgba(229, 229, 229, 1) 100%) repeat scroll 0 0 rgba(0, 0, 0, 0);
    border: 1px solid #ddd;  
}
.crm_panel_head2{
    background: #f6f6f6;
    border: 1px solid #e8e8e8; 
    font-weight: bold;
      -moz-user-select: none;
  -webkit-user-select: none;
  -ms-user-select: none; 
}
.crm_panel_head {
  font-size: 14px;  color:#666; font-weight: bold;
}
.crm_head_div{
 float: left;
 width: 80%;  padding: 8px 20px; 
   -moz-user-select: none;
  -webkit-user-select: none;
  -ms-user-select: none;  
}
.crm_panel_content{
    border: 1px solid #e8e8e8;
    border-top: 0px;
    display: block;
    padding: 12px;
    background: #fff;
    overflow: auto;
}
.crm-block-content{
height: 200px;
overflow: auto;
}
.crm_btn_div{
 float: right;
 font-size: 18px;
 width:20%;  padding: 8px 20px; 
 text-align: right;
}
.vx_input_100{
width: 100%;
}
.crm_clear{
    clear: both;
}
 .entry_row {
 margin: 7px auto;   
}
.entry_col1 {
    float: left;
    width: 25%;
    padding: 0px 7px;
    text-align: left;
}
 .entry_col2 {
    float: left;
    width: 75%;
    padding-left: 7px;
}
.vx_row{
    padding: 10px 0px;
    clear: both;
}
.crm-panel-description{
margin-bottom: 10px;
}
.vx_req_parent{
    font-weight: normal;

}
.vx_red{
color: #E31230;
}
.vx_label{
    font-weight: bold;
}
.crm_panel .vx_remove_btn{
    font-size: 18px;
    cursor: pointer;
}
.vx_error{
    background: #ca5952;
    padding: 10px;
    font-size: 14px;
    margin: 1% 2%;
    color: #fff;
}
.crm_toggle_btn{
    cursor: pointer;
}
.crm_panel .vx_error{
    margin: 0;
}
  .vx_tr{
      display: table; width: 100%;
  }
  .vx_td{
      display: table-cell; width: 90%; padding-right: 20px;
  }
  .vx_td2{
      display: table-cell; 
  }
  </style>
  <div id="crm_fields_map" class="vx_contents">
    <div class="vx_div ">
  <div class="vx_head ">
<div class="crm_head_div"> <?php esc_html_e('1. Select Salesforce Account',  'woocommerce-salesforce-crm' ); ?></div>
<div class="crm_btn_div"><i class="fa crm_toggle_btn fa-minus" title="<?php esc_html_e('Expand / Collapse','woocommerce-salesforce-crm') ?>"></i></div>
<div class="crm_clear"></div> 
  </div>
  <div class="vx_group">
  <div class="vx_row">
  <div class="vx_col1">
  <label for="vxc_account"><?php esc_html_e('Select Salesforce Account.', 'woocommerce-salesforce-crm');  $this->tooltip($tooltips['sel_object']); ?></label>
  </div>
  <div class="vx_col2">
  <div>
  <select id="vxc_account" name="meta[account]" class="vx_sel" autocomplete="off">
  <option value=""></option>
  <?php
  
  foreach($accounts as $f_key=>$f_val){
  $select="";
  if($feed['account'] == $f_val['id'])
  $select='selected="selected"';
  echo '<option value="'.esc_attr($f_val['id']).'" '.$select.'>'.esc_html($f_val['name']).'</option>';    
  }    
  ?>
  </select>
  </div>
  </div>
  <div class="clear"></div>
   </div>
   </div>
</div>
  <div id="crm_ajax_account" style="display:none; text-align: center; line-height: 100px;"><i><?php esc_html_e('Loading, Please Wait...', 'woocommerce-salesforce-crm'); ?></i></div>
    <?php 
    $valid=true;
    if(count($accounts) ==0 ){ //new feed and no accounts available
  echo  $this->verify_settings_msg();
  $valid=false; 
  }
    ?>
  <div id="vx_account_div">
  <?php
  if($valid && !empty($feed['account'])){
      $this->field_map_object($feed,$info); 
  }
  ?>

  </div>      

  
  </div>
  <script type="text/javascript">
    var vx_ajax=false;
    var post_id='<?php echo esc_attr($post_id) ?>';
  jQuery(document).ready(function($){

  var vx_crm_ajax='<?php echo wp_create_nonce("vx_crm_ajax") ?>';
 // $("#submitdiv").css('position','fixed'); 
              $('.meta-box-sortables').sortable({
        disabled: true
    });

    $('.postbox .hndle').css('cursor', 'default'); 
  $(document).on("click",".crm_toggle_check",function(e){
  var div=$("#"+this.id+"_div");   
  if(this.checked){
  div.slideDown();
  if(this.id == 'crm_note'){
      add_note_sel();
  }
  }else{
  div.slideUp();
  }  
  });
  //toggle boxes
  $(document).on("click",".crm_toggle_btn",function(e){
    e.preventDefault();
    var btn=jQuery(this);
    if(btn.hasClass("vx_btn_inner")){
    var panel=btn.parents(".crm_panel");
    var div=panel.find(".crm_panel_content");    
    }else{
    var panel=btn.parents(".vx_div");
    var div=panel.find(".vx_group");      
    }
   
 div.slideToggle('fast',function(){
  if(div.is(":visible")){
 btn.removeClass('fa-plus');     
 btn.addClass('fa-minus');     
  }else{
      btn.addClass('fa-plus');     
 btn.removeClass('fa-minus');     
  }   
 });
});
$(document).on("dblclick",".vx_head,.crm_panel_head2",function(e){
    e.preventDefault();
    $(this).find('.crm_toggle_btn').trigger('click');
});
//post validation
  $("form").submit(function(e){
 if($(".vx_required").length){

   $(".vx_requiredaaaa").each(function(){ 
   var value=""; 
       if($(this).hasClass('vx_req_parent')){
       var panel=$(this).parents(".vx_div"); 
    var attach_error=field_div=group=panel.find('.vx_group'); 
    var head=panel.find('.vx_head'); 
    var input=group.find(".crm_toggle_check");
    if(input.is(":checked")){
             var input=group.find("select");
          value=input.val();  
    }

       }else{
    var panel=$(this).parents(".crm_panel");
    var parent=panel.parents(".vx_div");
    var field_div=panel.find(".crm_panel_content");
    var group=parent.find('.vx_group'); 
    var head=parent.find('.vx_head'); 
    var attach_error=panel.find(".vx_margin");
    
        var field=panel.find(".vxc_field_type").val();
    var row=panel.find(".vxc_field_"+field);
    var input=row.find(":input"); 
        value=input.val(); 
       }
    
    
        panel.find(".vx_entry_error").remove();
    if(value == ""){
     e.preventDefault();

     if(!group.is(":visible")){ 
         head.find(".crm_toggle_btn").trigger('click');
         setTimeout(function(){ input.focus();},500);
     }else{
       input.focus();   
     }  
          if(field_div.is(":hidden")){
       panel.find(".crm_toggle_btn").trigger('click');
     }
    attach_error.append('<div class="entry_row vx_entry_error"><div class="vx_error"><i class="fa fa-warning"></i> <?php esc_html_e('This is a required field','woocommerce-salesforce-crm') ?></div></div>');
    return false;
    }  
   })  
 }
  })

  
  $(document).on("click",".vx_refresh_btn",function(e){
   var check=$(this);
      if(check.is(':checked')){
       var box=check.parents('.vx_group');
       box.find('.vx_refresh_data').trigger('click');   
      }   
  });
  
  $(document).on("click","#toggle_camp",function(e){
      e.preventDefault();  
  var btn=$(this);
  var ok=btn.find(".reg_ok");
  var proc=btn.find(".reg_proc");
  var sel=$("#crm_sel_camp");
  var input=$("#crm_camp_id");
  var camp_type=$("#crm_camp_type");
  if(ok.is(":visible")){
      //
  ok.hide();
  proc.show();
   input.show();   
   sel.hide();   
   camp_type.val('input');   
  }else{
        button_state_vx("ok",btn);
     input.hide();   
   sel.show();   
   camp_type.val('');  
     ok.show();
  proc.hide();  
  }  
  });

  $(document).on("click",".vx_refresh_data",function(e){
  e.preventDefault();  
  var btn=$(this);
  var action=$(this).data('id');
  var account=$("#vxc_account").val();
  button_state_vx("ajax",btn);
  $.post(ajaxurl,{action:'refresh_data_<?php echo esc_attr($this->id) ?>',vx_crm_ajax:vx_crm_ajax,vx_action:action,account:account,post_id:post_id},function(res){
  var re=$.parseJSON(res);
  button_state_vx("ok",btn);  
  if(re.status){
 if(re.status == "ok"){
  $.each(re.data,function(k,v){
   if($("#"+k).length){
   $("#"+k).html(v);    
   }   
  })   
 }else{
  if(re.error && re.error!=""){
      alert(re.error);
  }   
 }
  }   

  });   
  });
  
   $(document).on("change",".vxc_field_type",function(e){ 
  e.preventDefault(); 
  var div=$(this).parents('.crm_panel');
  var val=$(this).val();
    if(val){
      var input=div.find('.vxc_field_input');
      if(!input.val()){
    var option=div.find('.vxc_field_option').val();
    if(option){    input.val("{"+option+"}");  }     
      }
  }
    div.find('.vxc_fields').hide();
  div.find('.vxc_field_'+val).show();
    if(val == 'value' && !div.find('.vxcf_options_row').length){ div.find('.vxc_field_standard').show(); }

  
  });
  
    $(document).on("change",".vxc_field_option",function(e){
    var col=$(this).parents('.entry_col2');
    var val=$(this).val(); 
    var input=col.find('.vxc_field_input');
    if(input.is(':visible')){ 
     var input_val=input.val();
 
     input_val+=' {'+val+'}';
     input_val=$.trim(input_val);
     input.val(input_val);
    }    
 }); 
  
  $(document).on('click',"#vx_refresh_objects",function(e){
  e.preventDefault();  
  var btn=$(this);
  button_state_vx("ajax",btn);
   var account=$("#vxc_account").val();
  $.post(ajaxurl,{action:'get_objects_<?php echo esc_attr($this->id) ?>',account:account,vx_crm_ajax:vx_crm_ajax},function(res){
  var re=$.parseJSON(res);
  button_state_vx("ok",btn);  
  if(re.html){
  $("#vxc_object").html(re.html);
  }   
  $("#vxc_error").hide();
  if(re.error && typeof re.error  == "string" && re.error !="" ){
  alert(re.error);        
  }
  });   
  });

  $(document).on("change","#crm_optin_field",function(e){ 
  var val=$(this).val();
  if( val == ""){val="billing";}
  $(".vx_optin_val").hide();
  $("."+val+"_vx").show();
  }); 
  //
  $(document).on("click","#xv_add_custom_field",function(e){ 
  var temp=$("#vx_field_temp .vx_fields").clone();
  var field_name_select=$('#vx_add_fields_select');
  if(field_name_select.length){
    var field_name=id=field_name_select.val();
    if(field_name == '' || crm_fields[field_name] == ''){
     alert('<?php esc_html_e('Please Select Field Name','woocommerce-salesforce-crm') ?>');
     return;   
    }  
 var field=crm_fields[field_name];
if(field.type){
 temp.find('.crm-desc-type').text(field.type);
}else{
    temp.find('.crm-desc-type-div').remove();
}
//
if(field.name){
 temp.find('.crm-desc-name').text(field.name);
}else{
    temp.find('.crm-desc-name-div').remove();
}
//
if(field.maxlength){
 temp.find('.crm-desc-len').text(field.maxlength);
}else{
    temp.find('.crm-desc-len-div').remove();
}
//
if(field.label){
 temp.find('.crm_text_label').text(field.label);
}
  }else{
  
  var id=rand();
  }
  temp.find(":input").each(function(){
  var name=$(this).attr('name');
  if(name){
  $(this).attr('name','meta[map]['+id+']['+name+']'); 
  }  
  });
  verify_options(temp);
  $("#vx_field_temp").before(temp);
  update_fields_sel_vx();
  $(this).blur();
  });
      jQuery('.crm_panel').each(function(){
var panel=$(this);
 verify_options(panel);   
});
function verify_options(panel){
    var name=$.trim(panel.find('.crm-desc-name').text());
if(name && typeof crm_fields != 'undefined' && crm_fields[name].options){
var row=panel.find('.entry_row2');
var type=panel.find('.vxc_field_type').val();
var input=row.find('.vxc_field_input');
var val=input.val();
var str='<select name="'+input.attr('name')+'" id="'+input.attr('id')+'" class="vxc_field_input vx_input_100">';
str+='<option value="">Select any option</option>';

jQuery.each(crm_fields[name].options,function(k,v){
    if(v.hasOwnProperty('value')){
        k=v.value;    
     if(v.hasOwnProperty('name')){
      v=v.name;   
     }
     if(v.hasOwnProperty('label')){
      v=v.label;   
     }
    
    }
str+='<option value="'+k+'">'+v+'</option>';    
});
str+='</select>';    
input.replaceWith(str);
input=row.find('.vxc_field_input');
input.val(val);

row.addClass('vxcf_options_row');
if(type){ row.find('.vxc_field_option').hide(); }
}
}
 
  $(document).on("click",".vx_remove_btn",function(e){ 
  e.preventDefault(); 
  if(!confirm('<?php esc_html_e('Are you sure to remove ?','woocommerce-salesforce-crm') ?>')){
      return;
  }
  var temp=$(this).parents(".crm_panel");
  temp.find('.crm-desc-name').removeClass('crm-desc-name');

  mark_del(temp);
  update_fields_sel_vx();
  });     
  $(document).on("click",".vx_add_or",function(e){ 
  e.preventDefault(); 
  var par=$(this).parent(".vx_btn_div");   
  var div=$("#vx_filter_temp");
  var temp=div.find(".vx_filter_or").clone();
  var par_id=rand();
  temp.attr('data-id',par_id);
  var id=rand();
  temp.find(":input").each(function(){
  var name=$(this).attr('name');
  if(name)
  $(this).attr('name','meta[filters]['+par_id+']['+id+']['+name+']');   
  });
  temp.find(".vx_filter_label_and").remove();
  temp.find(".vx_filter_field4").remove();
  par.before(temp);
  });
  $(document).on("click",".vx_trash_or",function(e){ 
  e.preventDefault(); 
  var temp=$(this).parents(".vx_filter_or");
  mark_del(temp);
  });
  $(document).on("click",".vx_trash_and",function(e){ 
  e.preventDefault(); 
  var temp=$(this).parents(".vx_filter_and");
  mark_del(temp);
  });
  $(document).on("click",".vx_add_and",function(e){ 
  e.preventDefault(); 
  var par=$(this).parent(".vx_btn_div");   
  var div=$("#vx_filter_temp");
  var temp=div.find(".vx_filter_and").clone();
  var par_id=$(this).parents(".vx_filter_or").attr('data-id');
  var id=rand();
  temp.find(":input").each(function(){
  var name=$(this).attr('name');
  if(name)
  $(this).attr('name','meta[filters]['+par_id+']['+id+']['+name+']');   
  })
  par.before(temp);
  });
  function mark_del(obj){
  obj.css({'opacity':'.5'});
  obj.fadeOut(500,function(){
  $(this).remove();
  });
  }
  function rand(){
  return Math.round(Math.random()*1000000000000);
  }
  $("#vxc_account").change(function(e){  
         var account=$(this).val();
  var ajax=$("#crm_ajax_account");
  var fields_div=$("#vx_account_div");

  if(vx_ajax && vx_ajax.abort){
    vx_ajax.abort();  
  }
  ajax.show();
  fields_div.hide();
  vx_ajax=$.post(ajaxurl,{action:'field_account_<?php echo esc_attr($this->id) ?>',account:account,id:'<?php echo esc_attr($post_id) ?>',vx_crm_ajax:vx_crm_ajax},function(res){
  fields_div.html(res);
  fields_div.slideDown();    

  ajax.hide(); 
  $(".crm_alert").hide();
start_tooltip();
  })
  }); 
  $(document).on('change',"#vxc_object",function(e){
  e.preventDefault();    
   load_fields_vx(); 
  }); 
    $(document).on('click',"#vx_refresh_fields",function(e){
  e.preventDefault();    
   load_fields_vx(); 
  });
  function load_fields_vx(){
        var object=$("#vxc_object").val();
        var account=$("#vxc_account").val();
  var ajax=$("#crm_ajax_div");
  var fields_div=$("#crm_field_group");
  if(object == ""){
  alert("<?php esc_html_e('Please Select Object','woocommerce-salesforce-crm') ?>");
  return;
  }
  if(vx_ajax && vx_ajax.abort){
    vx_ajax.abort();  
  }
  ajax.show();
  fields_div.hide();
  var btn=$("#vx_refresh_fields");
    button_state_vx("ajax",btn);
  vx_ajax=$.post(ajaxurl,{action:'fields_map_<?php echo esc_attr($this->id) ?>',account:account,object:object,id:'<?php echo esc_attr($post_id) ?>',vx_crm_ajax:vx_crm_ajax},function(res){
  fields_div.html(res);
  fields_div.slideDown();    

  ajax.hide();   button_state_vx("ok",btn);
  $("#vxc_error").hide();
start_tooltip();
      jQuery('.crm_panel').each(function(){
var panel=$(this);
 verify_options(panel);   
});
  })
  }
  start_tooltip();
  $(document).on('click','.vxc_tips',function(e){
      e.preventDefault();
  });
  function start_tooltip(){
      // Tooltips
  var tiptip_args = {
  'attribute' : 'data-tip',
  'fadeIn' : 50,
  'fadeOut' : 50,
  'defaultPosition': 'top',
  'delay' : 200
  };
  $(".vxc_tips").tipTip( tiptip_args );
$('#vx_add_fields_select').select2({ placeholder: '<?php esc_html_e('Select Field','woocommerce-salesforce-crm') ?>'});
add_note_sel();
} 
  });
function add_note_sel(){
    jQuery('#crm_note_fields').select2({ placeholder: '<?php esc_html_e('Select Field','woocommerce-salesforce-crm') ?>'});
}  
function update_fields_sel_vx(){
    if(!jQuery('#vx_add_fields_select').length){
        return;
    }
var fields_boxes=[];
    jQuery('.crm-desc-name').each(function(){
   var val= jQuery.trim(jQuery(this).text());
   if(val){
       fields_boxes.push(val);
   } 
}); 
var str='';
if(crm_fields){
jQuery.each(crm_fields , function(k,v){
var disable='';
    if(jQuery.inArray(k,fields_boxes) > -1){
disable='disabled="disabled"';
    }
 str+='<option value="'+k+'" '+disable+'>'+v.label+'</option>';
//}   
})
}
jQuery('#vx_add_fields_select').html(str);
jQuery('#vx_add_fields_select').val('');
jQuery('#vx_add_fields_select').trigger('change');
}    
    function button_state_vx(state,button){
var ok=button.find('.reg_ok');
var proc=button.find('.reg_proc');
     if(state == "ajax"){
button.attr({'disabled':'disabled'});
ok.hide();
proc.show();
     }else{
         button.removeAttr('disabled');
   ok.show();
proc.hide();      
     }
}
  </script>