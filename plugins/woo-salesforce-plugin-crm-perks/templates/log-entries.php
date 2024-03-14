<?php
if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }                                            
 ?>
<style type="text/css">
  .vx_col{
  width: 16px; 
  }
  .widefat tr td.vx_icon_col{
      padding-left: 0px;
  }
   .widefat tr td.vx_icon_col img{
      margin-top: 2px;
  }
    .widefat tr th input{
margin-left: 0px;
  }
  .crm_status_img{
  width:18px;  display: block; margin: 1px auto; 
  }
  
  .crm_actions{
  padding: 12px 0px 10px 0px; clear: both;
  }
  .crm_input_inline{
  float: left; height: 28px; margin-right:5px; 
  }
  .vx_sort{
  cursor: pointer;
  }
  
  .vx_sort .vx_hide_sort{
  display: none;   
  }
  table .vx_icons{
      color: #888;
      font-size: 18px;
      cursor: pointer;
  }
  .vx_icons:hover{
      color: #333;
  }
  .vx_sort_icon{
  vertical-align: middle; margin-left: 5px;
  }
.wrap form  .vx_left_10{
    margin-left: 8px;
}
.entry_detail{
    border-top: 0px solid #ddd;
    border-bottom: 0px solid #ddd;
}
  @media screen and (max-width: 782px) {
  .crm_input_inline{
  float: left; height: 36px !important;
  }    
  }
 .vx_yellow{
    background-color: #F9ECBE;
} 
.vx_log_detail_footer{
    padding: 2px 10px;
    text-align: right;
}
.wrap .crm_actions a.button{
    display:inline-block;
}
/******log detail**************/
.vx_val{
text-decoration: underline;
}
.vx_or{
font-style: italic;
}.vx_op{
font-style: italic;
}
   .crm_panel_content{
    border: 1px solid #ddd;
    border-top: 0px;
    display: none;
    padding: 16px;
    background: #fff;
}
.crm_panel * {
  -webkit-box-sizing: border-box; /* Safari 3.0 - 5.0, Chrome 1 - 9, Android 2.1 - 3.x */
  -moz-box-sizing: border-box;    /* Firefox 1 - 28 */
  box-sizing: border-box;  
}
.crm_panel_100{
margin: 1%;
clear: both;
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
  -moz-user-select: none;
  -webkit-user-select: none;
  -ms-user-select: none;
}
.crm_panel_head2{
    background: #f6f6f6;
    border: 1px solid #ddd;  
}
.crm_panel_head , .crm_head_text{
  font-size: 14px;  color:#666; font-weight: bold;
}
.crm_head_div{
 float: left;
 width: 80%;  padding: 8px 20px;   
}
.crm_panel_content{
    border: 1px solid #ddd;
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
.crm_btn_div{
 float: right;
 font-size: 18px;
 width:20%;  padding: 8px 20px; 
 text-align: right;
}
.crm_toggle_btn:hover{
    color: #333;
}
 .crm_toggle_btn{

     color: #999; cursor: pointer;
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
.vx_margin{
margin-top: 10px;
}
.vx_red{
color: #E31230;
}
.vx_label{
    font-weight: bold;
}
.tablenav .tablenav-pages a:focus,.tablenav .tablenav-pages a:hover{border-color:#5b9dd9;color:#fff;background:#00a0d2;box-shadow:none;outline:0}

.tablenav .tablenav-pages a,.tablenav-pages span.current{text-decoration:none;padding:3px 6px}

.tablenav .tablenav-pages a,.tablenav-pages-navspan{display:inline-block;min-width:17px;border:1px solid #ccc;padding:3px 5px 7px;background:#e5e5e5;font-size:16px;line-height:1;font-weight:400;text-align:center}
  </style>
  <div class="wrap">

  <h2 style="padding-bottom: 12px; line-height: 36px">  <img alt="<?php esc_html_e("Salesforce Feeds", 'woocommerce-salesforce-crm') ?>" title="<?php esc_html_e("Salesforce Feeds", 'woocommerce-salesforce-crm') ?>" src="<?php echo esc_attr(self::$base_url)?>images/<?php echo esc_attr($this->crm_name) ?>-crm-logo.png" style="float:left; margin:0 7px 0px 0;" width="70" /> <?php esc_html_e("Salesforce Log", 'woocommerce-salesforce-crm'); ?></h2>
  
    <div style="float: left;">   
  <ul class="subsubsub" style="margin-top:0;">
  <?php
      foreach($menu_links as $k=>$link){
          ?>
       <li>
       <?php
       if($k>0){
          echo " | ";   
        }
       ?>
       <a href="<?php echo $link['link'] ?>" title="<?php echo sanitize_text_field($link['title'])?>"><?php echo $link['title']?></a> 
       </li>     
          <?php
      }
  ?>
  </ul>
  </div>
   <div style="float: right; ">
<form id="vx_form" class="crm_form" method="get"><div>
<input type="hidden" name="page" value="<?php echo esc_attr($this->post('page')) ?>" />
<input type="text" placeholder="<?php esc_html_e('Search','woocommerce-salesforce-crm') ?>" value="<?php echo esc_attr($this->post('search')); ?>" name="search" class="crm_input_inline">
  <?php
       if($this->post('order_id') !=""){
   ?> 
    <input type="hidden" name="order_id" value="<?php echo esc_attr($this->post('order_id')) ?>" />
<?php
       }
?>
    <input type="hidden" name="order" value="<?php echo esc_attr($this->post('order')) ?>" />
  <input type="hidden" name="orderby" value="<?php echo esc_attr($this->post('orderby')) ?>" />
  <input type="hidden" name="vx_tab_action_<?php echo esc_attr($this->id) ?>" id="vx_export_log" value="" autocomplete="off" />
   <input type="hidden" id="vx_nonce_field" value="<?php echo wp_create_nonce('vx_nonce'); ?>">
  <select name="object" class="crm_input_inline" style="max-width: 100px;">
  <option value=""><?php esc_html_e('All Objects','woocommerce-salesforce-crm') ?></option>
  <?php
  if(isset($objects['objects']) && is_array($objects['objects'])){  
  foreach($objects['objects'] as $f_key=>$f_val){
  $sel="";
  if(isset($_REQUEST['object']) && $_REQUEST['object'] == $f_key)
  $sel="selected='selected'";
  echo "<option value='".esc_attr($f_key)."' $sel>".esc_html($f_val)."</option>";       
  } }
  ?>
  </select>
  <select name="status" class="crm_input_inline">
  <option value=""><?php esc_html_e('All Status','woocommerce-salesforce-crm') ?></option>
  <?php    
  foreach($statuses as $f_key=>$f_val){
  $sel="";
  if(isset($_REQUEST['status']) && $_REQUEST['status'] == $f_key)
  $sel="selected='selected'";
  echo "<option value='".esc_attr($f_key)."' $sel>".esc_html($f_val)."</option>";       
  }
  ?>
  </select>
  <select name="time" class="crm_time_select crm_input_inline" style="max-width: 100px;">
  <option value=""><?php esc_html_e('All Times','woocommerce-salesforce-crm') ?></option>
  <?php
  foreach($times as $f_key=>$f_val){
  $sel="";
  if(isset($_REQUEST['time']) && $_REQUEST['time'] == $f_key)
  $sel="selected='selected'";
  echo "<option value='".esc_attr($f_key)."' $sel>".esc_html($f_val)."</option>";       
  }
  ?>
  </select>
  <span style="<?php if($this->post('time') != "custom"){echo "display:none";} ?>" class="crm_custom_range"> 
  <input type="text" name="start_date" placeholder="<?php esc_html_e('From Date','woocommerce-salesforce-crm') ?>" value="<?php echo esc_attr($this->post('start_date'));?>" class="vxc_date crm_input_inline" style="width: 100px">
  <input type="text" class="vxc_date crm_input_inline" value="<?php echo esc_attr($this->post('end_date'));?>" placeholder="<?php esc_html_e('To Date','woocommerce-salesforce-crm') ?>" name="end_date"  style="width: 100px">
  </span>
 
  <button type="submit" class="button-secondary button crm_input_inline" title="<?php esc_html_e('Search','woocommerce-salesforce-crm') ?>"><i class="fa fa-search"></i> <?php esc_html_e('Search','woocommerce-salesforce-crm') ?></button> 
     
  </div>
  </form> 
     <div style="clear: both;"></div> 
  </div>
  <form method="post">
  
  <div class="crm_actions tablenav">
  <select name="bulk_action" id="vx_bulk_action" class="crm_input_inline" style="min-width: 100px; max-width: 250px;">
  <?php
   foreach($bulk_actions as $k=>$v){
   echo '<option value="'.esc_attr($k).'">'.esc_html($v).'</option>';    
   }   
  ?>
  </select>
    <input type="hidden" name="vx_nonce" value="<?php echo wp_create_nonce('vx_nonce'); ?>">   
  <button type="submit" class="button-secondary button crm_input_inline" title="<?php esc_html_e('Apply','woocommerce-salesforce-crm') ?>" id="vx_apply_bulk"><i class="fa fa-check"></i> <?php esc_html_e('Apply','woocommerce-salesforce-crm') ?></button>

  <?php          if(count($results)>0){
  ?>
  <button type="button" name="tab_action" id="vx_export" title="<?php esc_html_e('Export as CSV','woocommerce-salesforce-crm') ?>" class="button-secondary button crm_input_inline vx_left_10"><i class="fa fa-download"></i> <?php esc_html_e('Export as CSV','woocommerce-salesforce-crm') ?></button> 
  <?php 
        if($this->post('id') !="" && $items>0){
  if(isset($results[0]['order_id']) && $results[0]['order_id']!=""){
     $order_id=esc_attr($results[0]['order_id']);
     $parent_id=(int)$results[0]['parent_id'];
      ?>
  <a href="<?php echo esc_url(admin_url( 'admin.php?page='.$this->id.'_log&order_id='.$order_id));?>" title="<?php echo sprintf(__('View Order# %s Logs','woocommerce-salesforce-crm'),esc_attr($order_id)); ?>" class="button vx_left_10"><i class="fa fa-hand-o-right"></i> <?php 
  if($parent_id<0){
  echo sprintf(__('View Visitor# %s Logs','woocommerce-salesforce-crm'),esc_attr($order_id)); 
  }else{
  echo sprintf(__('View Order# %s Logs','woocommerce-salesforce-crm'),esc_attr($order_id)); 
  }
   ?></a><?php
  }}
 if($this->post('order_id') !="" || $this->post('id') !=""){
          ?><a href="<?php echo esc_url(admin_url( 'admin.php?page='.$this->id.'_log'));?>" title="<?php esc_html_e('View All Logs','woocommerce-salesforce-crm') ?>" class="button vx_left_10"><i class="fa fa-external-link"></i> <?php esc_html_e('View All Logs','woocommerce-salesforce-crm') ?></a>        
          <?php
      }
  ?>
  <div class="tablenav-pages">
  <span id="paging_header" class="displaying-num"><?php esc_html_e('Displaying','woocommerce-salesforce-crm') ?> <span id="paging_range_min_header"><?php echo esc_attr($range_min)?></span> - <span id="paging_range_max_header"><?php echo esc_attr($range_max); ?></span> of <span id="paging_total_header"><?php echo esc_attr($items);?></span></span>
  <?php  echo wp_kses_post($page_links);?></div>
  <?php
  }
  ?>        
  </div>
  
  <table class="widefat fixed sort" cellspacing="0">
  
  <thead>
  <tr>
  <th scope="col" id="active" class="manage-column vx_col"><input type="checkbox" class="crm_head_check"> </th>
  <th scope="col" class="manage-column vx_col"> </th>
  <th scope="col" class="manage-column vx_sort"  data-name="crm_id"><?php esc_html_e('Salesforce ID', 'woocommerce-salesforce-crm') ?>
  <i class="fa fa-caret-<?php echo esc_attr($crm_order) ?> vx_sort_icon <?php echo esc_attr($crm_class) ?>"></i>                          
  </th>
  <th scope="col" class="manage-column vx_sort"  data-name="order_id"><?php esc_html_e('Order ID', 'woocommerce-salesforce-crm') ?>
  <i class="fa fa-caret-<?php echo esc_attr($entry_order) ?> vx_sort_icon <?php echo esc_attr($entry_class) ?>"></i>                      
  </th>
  <th scope="col" class="manage-column"  data-name="object"><?php esc_html_e('Description', 'woocommerce-salesforce-crm') ?>
  </th>
  <th scope="col" class="manage-column vx_sort"  data-name="time"><?php esc_html_e('Time', 'woocommerce-salesforce-crm') ?>
  <i class="fa fa-caret-<?php echo esc_attr($time_order) ?> vx_sort_icon <?php echo esc_attr($time_class) ?>"></i>
  </th>
  <th style="width: 40px"><?php esc_html_e('Detail','woocommerce-salesforce-crm') ?></th>
  </tr>
  </thead>
  
  <tfoot>
  <tr>
  <th scope="col" id="active" class="manage-column vx_col"><input type="checkbox" class="crm_head_check"> </th>
  <th scope="col" class="manage-column vx_col"> </th>
  <th scope="col" class="manage-column vx_sort"  data-name="crm_id"><?php esc_html_e('Salesforce ID', 'woocommerce-salesforce-crm'); ?>
  <i class="fa fa-caret-<?php echo esc_attr($crm_order) ?> vx_sort_icon <?php echo esc_attr($crm_class) ?>"></i>                          
  </th>
  <th scope="col" class="manage-column vx_sort"  data-name="order_id"><?php esc_html_e('Order ID', 'woocommerce-salesforce-crm') ?>
  <i class="fa fa-caret-<?php echo esc_attr($entry_order) ?> vx_sort_icon <?php echo esc_attr($entry_class) ?>"></i>                      
  </th>
  <th scope="col" class="manage-column"  data-name="object"><?php esc_html_e('Description', 'woocommerce-salesforce-crm') ?>
  </th>
  <th scope="col" class="manage-column vx_sort"  data-name="time"><?php esc_html_e('Time', 'woocommerce-salesforce-crm') ?>
  <i class="fa fa-caret-<?php echo esc_attr($time_order) ?> vx_sort_icon <?php echo esc_attr($time_class) ?>"></i>
  </th>
  <th><?php esc_html_e('Detail','woocommerce-salesforce-crm') ?></th>  
  </tr>
  
  </tfoot>
  <tbody class="list:user user-list">
  <?php
   $analytics_addon=class_exists('vx_track_pages') ? true : false ;
  if(count($results)){
  $sno=0;
  foreach($results as $row){
  $sno++;
  $row=$this->verify_log($row,$objects['objects']);
    $e_id=(int)$row['order_id'];
  $p_id=(int)$row['parent_id'];
  ?>
  <tr class='author-self status-inherit <?php if(in_array($row['id'],$log_ids)){echo 'vx_yellow ';} echo $sno%2 == 0 ? 'alternate' :'' ?>' id="tr_<?php echo esc_attr($row['id']) ?>" data-id="<?php echo esc_attr($row['id']) ?>" >
  <td class="vx_check_col"><input type="checkbox" name="log_id[]" value="<?php echo esc_attr($row['id']) ?>" class="crm_input_check"></td>
    <td class="vx_icon_col"><img src="<?php echo esc_attr(self::$base_url) ?>images/<?php echo esc_attr($row["status_img"]) ?>.png" alt="<?php echo $row['status'] ? __('Active', 'woocommerce-salesforce-crm') : __('Inactive', 'woocommerce-salesforce-crm');?>" title="<?php echo esc_html($row['title']);?>" class="crm_status_img" /></td>
  <td class="column-name" style="width:40%"><?php echo wp_kses_post($row['a_link']) ?></td>
      <td class="column-title">
      <?php
      $entry_link='';
             if($p_id < 0){
          if($analytics_addon){
      $entry_link=add_query_arg(array('page'=>'vx_analytics','entrty_id'=>$e_id), admin_url('admin.php'));  
         $e_id='#'.$e_id;
          }
             }else{
             $entry_link=add_query_arg(array('post'=>$row['order_id'],'action'=>'edit'), admin_url('post.php'));     
             }
      if(!empty($entry_link)){
      ?>
        <a href="<?php echo esc_url($entry_link); ?>" title="<?php echo esc_attr($row['order_id']); ?>" target="_blank" ><?php echo esc_attr($e_id); ?></a>
        <?php
      }else{
          echo esc_attr($e_id);
      }
        ?>
    </td>
    
               <td scope="col" class="manage-column"><?php echo esc_html($row['desc']); ?></td>
    <td scope="col" class="manage-column"><?php echo date('M-d-Y H:i:s', strtotime($row['time'])+$offset); ?></td>
    <td><i class="vx_icons vx_detail fa fa-th-list" title="<?php esc_html_e('Expand Detail','woocommerce-salesforce-crm'); ?>"></i></td>  
  </tr>
  <tr style="display: none;"><td colspan="7" class="entry_detail"></td></tr>
  <?php
  }
  }
  else {
  ?>
  <tr>
    <td colspan="4" style="padding:20px;">
        <?php esc_html_e('No Record(s) Found', 'woocommerce-salesforce-crm'); ?>
    </td>
  </tr>
  <?php
  }
  ?>
  </tbody>
  </table>

      <?php
  if($items>0){
  ?>
    <div class="crm_actions tablenav">
   <a id="vx_clear_logs" class="button" title="<?php esc_html_e('Clear Salesforce Log','woocommerce-salesforce-crm') ?>" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page='.$this->post('page')."&view=log&vx_tab_action_".$this->id."=clear_logs"),'vx_nonce','vx_nonce')); ?>"><?php esc_html_e('Clear Salesforce Log','woocommerce-salesforce-crm') ?></a>
  <div class="tablenav-pages">   <span id="paging_header" class="displaying-num"><?php esc_html_e('Displaying','woocommerce-salesforce-crm') ?> <span id="paging_range_min_header"><?php echo esc_attr($range_min)?></span> - <span id="paging_range_max_header"><?php echo esc_attr($range_max); ?></span> of <span id="paging_total_header"><?php echo esc_attr($items);?></span></span>
  <?php  echo wp_kses_post($page_links);?></div>
    </div>
  <?php
  }
  ?>
  </form>
  </div>
  <script type="text/javascript">
    var vx_crm_ajax='<?php echo wp_create_nonce('vx_crm_ajax') ?>';
  (function( $ ) {
  
  $(document).ready( function($) {
  $(".vx_sort").click(function(){
  var orby=$(this).attr('data-name');  
  if(!orby || orby =="")
  return;
  var form=$("#vx_form");
  var order=form.find("input[name=order]");
  var orderby=form.find("input[name=orderby]");
  var or="asc";
  if(orderby.val() == orby && order.val() == "asc"){
  or="desc";   
  }
  order.val(or);   
  orderby.val(orby);
  form.submit();   
  });
  $(".crm_head_check").click(function(e){
if($(this).is(":checked")){
    $(".crm_input_check,.crm_head_check").attr('checked','checked');
}else{
    $(".crm_input_check,.crm_head_check").removeAttr('checked');
    }
});
  $(".crm_input_check").click(function(e){
var head_checked=$(".crm_head_check").eq(0).is(':checked');
      if(!head_checked && $(".crm_input_check:checked").length == $(".crm_input_check").length){
$(".crm_head_check").attr('checked','checked');
}else if(head_checked){
$(".crm_head_check").removeAttr('checked');
}
});
  $("#vx_export").click(function(e){
     e.preventDefault();   
  $("#vx_export_log").val('export_log');  
  $("#vx_nonce_field").attr('name','vx_nonce');  
  var form=$("#vx_form");
  form.attr({method:'post'}); 
  form.submit();  
  form.attr({method:'get'});
    $("#vx_export_log").val('');  
  $("#vx_nonce_field").removeAttr('name');  
// form[0].reset();  
  });
    $("#vx_apply_bulk").click(function(e){
        var sel=$("#vx_bulk_action");
if(sel.val() == ""){
    alert('<?php esc_html_e('Please Select Action','woocommerce-salesforce-crm') ?>');
      return false;
}
if($(".crm_input_check:checked").length == 0){ 
    alert('<?php esc_html_e('Please select at least one entry','woocommerce-salesforce-crm') ?>');
    return false;
}
var action=sel.val();
if( $.inArray(action,["send_to_crm_bulk_force","send_to_crm_bulk"]) !=-1 && $(".crm_input_check:checked").length>4){
 if(!confirm('<?php esc_html_e('Exporting more than 4 entries may take too long. Are you sure you want to continue?','woocommerce-salesforce-crm') ?>')){
  e.preventDefault();    
 }   
}
  })
   $("#vx_clear_logs").click(function(e){
      if(!confirm('<?php esc_html_e('Salesforce Logs will be deleted permanently. Do you want to continue?','woocommerce-salesforce-crm') ?>')){
          e.preventDefault();
      }  
  })    
  $(".vx_sort").hover(function(){
  $(this).find(".vx_hide_sort").show();
  },function(){
  $(this).find(".vx_hide_sort").hide();   
  })    
  $(".vxc_date").datepicker({ changeMonth: true,
  changeYear: true,
  showButtonPanel: true,
  yearRange: "-100:+10",
  dateFormat: 'dd-M-yy'  });
  $(document).on("change",".crm_time_select",function(){
  var form=$(this).parents(".crm_form");
  var input=form.find(".crm_custom_range");
  if($(this).val() != "custom"){
  form.find(".vxc_date").val("");
  }
  if($(this).val() == "custom"){
  input.show();
  }else{
  input.hide();
  }   
  });
    $(".vx_log_link").click(function(e){
     e.preventDefault();
     if($(this).data('id')){
       $('html, body').animate({
        scrollTop: $("#tr_"+$(this).data('id')).offset().top-35
    }, 500);
    var tr=$("#tr_"+$(this).data('id'));  
    var next_tr=tr.next("tr");
    if(!next_tr.find("td").is(":visible")){
     tr.find(".vx_detail").click();   
    }
     }
  })
  ////////////
  $(document).on("click",".vx_detail,.vx_close_detail",function(e){
    e.preventDefault();
    var next_tr=tr=$(this).parents("tr");
    var is_main=false;
    if(!$(this).hasClass("vx_close_detail")){   
    next_tr=tr.next(tr);
    is_main=true;
    }
    var icon=$(this);
    var td=next_tr.find("td");
    if(td.is(":visible"))
    {
      next_tr.find('.vxa_entry').slideUp('fast',function(){
        next_tr.hide();     
        })
        
    if(is_main){
    icon.attr('title','<?php esc_html_e('Expand Detail','woocommerce-salesforce-crm'); ?>');    
    }        
     return;   
    }else{
        next_tr.show('fast'); 
    next_tr.find('.vxa_entry').slideDown('fast');
       if(is_main){
    icon.attr('title','<?php esc_html_e('Collapse Detail','woocommerce-salesforce-crm'); ?>');    
    }    
    }
    if(!td.find("div").length){
      var id=$.trim(tr.attr("data-id"));
    td.html("<div style='text-align:center'><i class='fa fa-circle-o-notch fa-spin' style='margin: 20px auto'></i></div>");
    $.post(ajaxurl,{action:'log_detail_<?php echo esc_attr($this->id) ?>',id:id,vx_crm_ajax:vx_crm_ajax},function(res){
     td.html(res);
    });
    }
});
//logs detail boxes
$(document).on("dblclick",".crm_panel_head",function(e){
    e.preventDefault();
 var elem=jQuery(this);
    vx_toggle_log_panel(elem);   
});
$(document).on("click",".crm_toggle_btn",function(e){
    e.preventDefault();
var elem=jQuery(this);
    vx_toggle_log_panel(elem);
});

  });
  
  }(jQuery));
function vx_toggle_log_panel(elem){
    var panel=elem.parents(".crm_panel");
 var div=panel.find(".crm_panel_content");
 var btn=panel.find(".crm_toggle_btn");
 div.slideToggle('fast',function(){
  if(jQuery(this).is(":visible")){
 btn.removeClass('fa-plus');     
 btn.addClass('fa-minus');     
  }else{
      btn.addClass('fa-plus');     
 btn.removeClass('fa-minus');     
  }   
 });
}  
  </script>