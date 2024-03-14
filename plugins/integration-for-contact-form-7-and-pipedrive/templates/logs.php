<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }
 $feeds = $this->data->get_feeds(); 
   ?><style type="text/css">
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
    @media screen and (max-width: 1028px) {

  table .crm_panel_50{
      width: 98%;
  }   
  }
  /*********************crm panel******************/
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
.crm_toggle_btn:hover{
    color: #333;
}
 .crm_toggle_btn{

     color: #999; cursor: pointer;
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
.vx_margin{
margin-top: 10px;
}
.vx_red{
color: #E31230;
}
.vx_label{
    font-weight: bold;
}
.vx_blue{
color: #1874CD;
}
.vx_val{
text-decoration: underline;
}
.vx_or{
font-style: italic;
}.vx_op{
font-style: italic;
}
.vx_u{
text-decoration: underline;
}
.vx_left_20{
margin-left: 8px;
}
.vx_error{
    background: #ca5952;
    padding: 10px;
    font-size: 14px;
    margin: 1% 2%;
    color: #fff;
}
.vx_yellow{
    background-color: #F9ECBE;
}
.vx_log_detail_footer{
    padding: 2px 10px;
    text-align: right;
}
.vx_wrap .crm_actions a.button{
    display:inline-block;
    margin-top: 0px;
}
.tablenav .tablenav-pages a:focus,.tablenav .tablenav-pages a:hover{border-color:#5b9dd9;color:#fff;background:#00a0d2;box-shadow:none;outline:0}

.tablenav .tablenav-pages a,.tablenav-pages span.current{text-decoration:none;padding:3px 6px}

.tablenav .tablenav-pages a,.tablenav-pages-navspan{display:inline-block;min-width:17px;border:1px solid #ccc;padding:3px 5px 7px;background:#e5e5e5;font-size:16px;line-height:1;font-weight:400;text-align:center}
  </style>
  <div class="vx_wrap"> 
  <div>
  <h2  class="vx_img_head"><img alt="<?php esc_html_e("Pipedrive Feeds", 'cf7-pipedrive') ?>" title="<?php esc_html_e("Pipedrive Feeds", 'cf7-pipedrive') ?>" src="<?php echo $this->get_base_url()?>images/pipedrive-crm-logo.png?ver=1" /> <?php esc_html_e("Pipedrive Log", 'cf7-pipedrive'); ?>
  
   <select style="min-width: 120px; max-width: 200px; margin-left: 12px; font-weight: normal;" id="vx_sel_feed">
  <option value=""><?php esc_html_e('All Feeds','cf7-pipedrive') ?></option>
  <?php    
  foreach($feeds as $val){
  $sel="";
  if(isset($_REQUEST['feed_id']) && $_REQUEST['feed_id'] == $val['id'])
  $sel="selected='selected'";
  echo "<option value='".esc_attr($val['id'])."' $sel>".esc_html($val['name'])."</option>";       
  }
  ?>
  </select> 
  </h2>
  <div class="clear"></div>
</div>

  <div>

     <div style="float: right;">
<form id="vx_form" class="crm_form" method="get"><div>

    <input type="hidden" name="page" value="<?php echo esc_attr($this->post('page')) ?>" />
  <input type="hidden" name="id" value="<?php echo esc_attr($this->post('id')) ?>" />
  <input type="hidden" name="tab" value="<?php echo esc_attr($this->post('tab')) ?>" />
  <input type="text" placeholder="<?php esc_html_e('Search','cf7-pipedrive') ?>" value="<?php echo esc_attr($this->post('search')) ?>" name="search" class="crm_input_inline">
  <?php
       if($this->post('entry_id') !=""){
   ?> 
    <input type="hidden" name="entry_id" value="<?php echo esc_attr($_REQUEST['entry_id']) ?>" />
<?php
       }
?>
    <input type="hidden" name="order" value="<?php echo esc_attr($this->post('order')) ?>" />
  <input type="hidden" name="orderby" value="<?php echo esc_attr($this->post('orderby')) ?>" />
  <input type="hidden" name="vx_tab_action_<?php echo esc_attr($this->id) ?>" id="vx_export_log" value="" autocomplete="off" />
   <input type="hidden" id="vx_nonce_field" value="<?php echo wp_create_nonce('vx_nonce'); ?>">
  <select name="object" class="crm_input_inline" style="max-width: 100px;">
  <option value=""><?php esc_html_e('All Objects','cf7-pipedrive') ?></option>
  <?php    
  foreach($objects as $f_key=>$f_val){
  $sel="";
  if(isset($_REQUEST['object']) && $_REQUEST['object'] == $f_key)
  $sel="selected='selected'";
  echo "<option value='".esc_attr($f_key)."' $sel>".esc_attr($f_val)."</option>";       
  }
  ?>
  </select>
  <select name="status" class="crm_input_inline">
  <option value=""><?php esc_html_e('All Status','cf7-pipedrive') ?></option>
  <?php
   
  foreach($statuses as $f_key=>$f_val){
  $sel="";
  if(isset($_REQUEST['status']) && $_REQUEST['status'] == $f_key)
  $sel="selected='selected'";
  echo "<option value='".esc_attr($f_key)."' $sel>".esc_attr($f_val)."</option>";       
  }
  ?>
  </select>
  <select name="time" class="crm_time_select crm_input_inline" style="max-width: 100px;">
  <option value=""><?php esc_html_e('All Times','cf7-pipedrive') ?></option>
  <?php
  foreach($times as $f_key=>$f_val){
  $sel="";
  if(isset($_REQUEST['time']) && $_REQUEST['time'] == $f_key)
  $sel="selected='selected'";
  echo "<option value='".esc_attr($f_key)."' $sel>".esc_attr($f_val)."</option>";       
  }
  ?>
  </select>
  <span style="<?php if($this->post('time') != "custom"){echo "display:none";} ?>" class="crm_custom_range"> 
  <input type="text" name="start_date" placeholder="<?php esc_html_e('From Date','cf7-pipedrive') ?>" value="<?php if(isset($_REQUEST['start_date'])){echo esc_attr($_REQUEST['start_date']);}?>" class="vxc_date crm_input_inline" style="width: 100px">
  <input type="text" class="vxc_date crm_input_inline" value="<?php if(isset($_REQUEST['end_date'])){echo esc_attr($_REQUEST['end_date']);}?>" placeholder="<?php esc_html_e('To Date','cf7-pipedrive') ?>" name="end_date"  style="width: 100px">
  </span>
 
  <button type="submit" title="<?php esc_html_e('Search','cf7-pipedrive') ?>" name="search" class="button-secondary button crm_input_inline"><i class="fa fa-search"></i> <?php esc_html_e('Search','cf7-pipedrive') ?></button> 
     
  </div>   </form> 
     <div style="clear: both;"></div> 
  </div>
  <form method="post">
  
  <div class="crm_actions tablenav">
  <div class="alignleft actions">
  <select name="bulk_action" id="vx_bulk_action" class="crm_input_inline" style="min-width: 100px; max-width: 250px;">
  <?php
   foreach($bulk_actions as $k=>$v){
  echo '<option value="'.esc_attr($k).'">'.esc_html($v).'</option>';   
   }   
  ?>
  </select>
    <input type="hidden" name="vx_nonce" value="<?php echo wp_create_nonce('vx_nonce'); ?>">   
  <button type="submit" class="button-secondary button crm_input_inline" title="<?php esc_html_e('Apply','cf7-pipedrive') ?>" id="vx_apply_bulk"><i class="fa fa-check"></i> <?php esc_html_e('Apply','cf7-pipedrive') ?></button>

  <?php   
  $log_link= admin_url("admin.php?page={$this->id}&tab=logs");

         if($items>0){
        
  ?>
  <button type="button" name="tab_action" title="<?php esc_html_e('Export as CSV','cf7-pipedrive') ?>" id="vx_export" class="button-secondary button crm_input_inline vx_left_10"><i class="fa fa-download"></i> <?php esc_html_e('Export as CSV','cf7-pipedrive') ?></button> 
  <?php
  }
 
  $_log_id=isset($_GET['log_id']) ? $this->post('log_id') : '';
        if($_log_id !="" ){
  if(isset($data['feeds'][0]['entry_id']) && $data['feeds'][0]['entry_id']!=""){
     $entry_id=$data['feeds'][0]['entry_id'];
      ?>
  <a href="<?php echo  esc_url( $log_link.'&entry_id='.$entry_id);?>" title="<?php echo sprintf(esc_html__('View Entry# %s Logs','cf7-pipedrive'),$entry_id); ?>" class="button vx_left_10"><i class="fa fa-hand-o-right"></i> <?php echo sprintf(esc_html__('View Entry# %s Logs','cf7-pipedrive'),$entry_id); ?></a><?php
  }}
 if($this->post('entry_id') !="" || $_log_id !=""){
          ?><a href="<?php echo esc_url($log_link);?>" title="<?php esc_html_e('View All Logs','cf7-pipedrive') ?>" class="button vx_left_10"><i class="fa fa-external-link"></i> <?php esc_html_e('View All Logs','cf7-pipedrive') ?></a>        
          <?php
      }
  
  ?>
  </div>
  <?php
if($items>0){
  ?>
  <div class="tablenav-pages"> <span id="paging_header" class="displaying-num"><?php esc_html_e('Displaying','cf7-pipedrive') ?> <span id="paging_range_min_header"><?php echo esc_html($data['min']); ?></span> - <span id="paging_range_max_header"><?php echo esc_html($data['max']) ?></span> of <span id="paging_total_header"><?php echo esc_html($data['items']) ?></span></span><?php echo wp_kses_post($data['links']) ?></div>
 <?php
}
        ?>       
  </div>
  
  <table class="widefat fixed sort" cellspacing="0">
  
  <thead>
  <tr>
  <th scope="col" id="active" class="manage-column vx_col"><input type="checkbox" class="crm_head_check"> </th>
  <th scope="col" class="manage-column vx_col"> </th>
  <th scope="col" class="manage-column vx_sort"  data-name="crm_id"><?php esc_html_e("Pipedrive ID", 'cf7-pipedrive') ?>
   <i class="fa fa-caret-<?php echo esc_attr($crm_order) ?> vx_sort_icon <?php echo esc_attr($crm_class) ?>"></i>                          
  </th>
  <th scope="col" class="manage-column vx_sort"  data-name="entry_id"><?php esc_html_e("Entry ID", 'cf7-pipedrive') ?>
  <i class="fa fa-caret-<?php echo esc_attr($entry_order) ?> vx_sort_icon <?php echo esc_attr($entry_class) ?>"></i>                      
  </th>
  <th scope="col"><?php esc_html_e('Feed ID', 'cf7-pipedrive') ?> </th>
  <th scope="col" class="manage-column"  data-name="object"><?php esc_html_e("Description", 'cf7-pipedrive') ?>
  </th>
  <th scope="col" class="manage-column vx_sort"  data-name="time"><?php esc_html_e("Time", 'cf7-pipedrive') ?>
  <i class="fa fa-caret-<?php echo esc_attr($time_order) ?> vx_sort_icon <?php echo esc_attr($time_class) ?>"></i>
  </th>
  <th style="width: 40px"><?php esc_html_e('Detail','cf7-pipedrive') ?></th>
  </tr>
  </thead>
  
  <tfoot>
  <tr>
  <th scope="col" id="active" class="manage-column vx_col"><input type="checkbox" class="crm_head_check"> </th>
  <th scope="col" class="manage-column vx_col"> </th>
  <th scope="col" class="manage-column vx_sort"  data-name="crm_id"><?php esc_html_e("Pipedrive ID", 'cf7-pipedrive') ?>
   <i class="fa fa-caret-<?php echo esc_attr($crm_order) ?> vx_sort_icon <?php echo esc_attr($crm_class) ?>"></i>                          
  </th>
  <th scope="col" class="manage-column vx_sort"  data-name="entry_id"><?php esc_html_e("Entry ID", 'cf7-pipedrive') ?>
  <i class="fa fa-caret-<?php echo esc_attr($entry_order) ?> vx_sort_icon <?php echo esc_attr($entry_class) ?>"></i>                      
  </th>
    <th scope="col"><?php esc_html_e('Feed ID', 'cf7-pipedrive') ?> </th>
  <th scope="col" class="manage-column"  data-name="object"><?php esc_html_e("Description", 'cf7-pipedrive') ?>
  </th>
  <th scope="col" class="manage-column vx_sort"  data-name="time"><?php esc_html_e("Time", 'cf7-pipedrive') ?>
  <i class="fa fa-caret-<?php echo esc_attr($time_order) ?> vx_sort_icon <?php echo esc_attr($time_class) ?>"></i>
  </th>
  <th><?php esc_html_e('Detail','cf7-pipedrive') ?></th>  
  </tr>
  
  </tfoot>
  <tbody class="list:user user-list">
  <?php
  if(is_array($data['feeds']) && !empty($data['feeds'])){
      $entries_plugin=class_exists('vxcf_form') ? true : false ;
      $analytics_addon=class_exists('vx_track_pages') ? true : false ;
  $sno=0;
      foreach($data['feeds'] as $feed){
  $sno++;
  $row=$this->verify_log($feed,$objects);
  $e_id=(int)$row['entry_id'];
  $p_id=(int)$row['parent_id'];
  ?>
  <tr class='author-self status-inherit <?php if(in_array($row['id'],$log_ids)){echo 'vx_yellow ';} echo $sno%2 == 0 ? 'alternate' :'' ?>' id="tr_<?php echo esc_attr($row['id']) ?>" data-id="<?php echo esc_attr($row['id']); ?>" >
  <td class="vx_check_col"><input type="checkbox" name="log_id[]" value="<?php echo esc_attr($row['id']); ?>" class="crm_input_check"></td>
    <td class="vx_icon_col"><img src="<?php echo esc_url($base_url) ?>images/<?php echo esc_attr($row["status_img"]); ?>.png" alt="<?php echo $row["status"] ? esc_html__("Active", 'cf7-pipedrive') : esc_html__("Inactive", 'cf7-pipedrive');?>" title="<?php echo esc_html($row['title']);?>" class="crm_status_img" /></td>
  <td class="column-name" style="width:40%"><?php echo wp_kses_post($row['a_link']) ?></td>
      <td class="column-title">
      <?php 
      $entry_link='';
     if(!empty($e_id)){
      if($p_id < 0){
          if($analytics_addon){
         $entry_link=add_query_arg(array('page'=>'vx_analytics','entrty_id'=>$e_id), admin_url('admin.php'));
          } 
         $e_id='#'.$e_id;
          
      }else if($entries_plugin){
         if(in_array($row['form_id'],array('vx_calls','vx_sms'))){
             $link_tab=substr($row['form_id'],3);
$link_tab=$link_tab == 'calls' ? 'Call' : 'SMS';
     $e_id=ucfirst($link_tab).' #'.$e_id;    
                 
          }else{
          $link_tab='entries';
          if($row['form_id'] == 'vx_contacts'){
          $link_tab='contacts';    
          }
            $entry_link=add_query_arg(array('page'=>'vxcf_leads','tab'=> $link_tab,'id'=>$e_id), admin_url('admin.php'));
          }
      }}
      
          if(! empty($entry_link) ){
      ?>
        <a href="<?php echo esc_url($entry_link)  ?>" title="<?php echo esc_attr($row["entry_id"]); ?>" target="_blank" ><?php echo esc_html($e_id); ?></a>
        <?php
          }else{
echo esc_html($e_id);
          }
        ?>
    </td>
    <td scope="col" class="manage-column"><?php 
if(!empty($row['feed_id'])){
$feed_link=$this->get_feed_link($row['feed_id'],$row['form_id']);
 ?><a href="<?php echo esc_url($feed_link) ?>" title="<?php esc_html_e('Edit Feed','cf7-pipedrive') ?>">#<?php echo $row['feed_id'] ?></a><?php  } ?>
 </td>
    <td scope="col" class="manage-column"><?php echo esc_html($row['desc']); ?></td>
    <td scope="col" class="manage-column"><?php echo    date('M-d-Y H:i:s', strtotime($row['time'])+$offset);?></td>
    <td><i class="vx_icons vx_detail fa fa-th-list" title="<?php esc_html_e('Expand Details','cf7-pipedrive') ?>"></i></td>  
  </tr>
  <tr style="display: none;"><td colspan="8" class="entry_detail"></td></tr>
  <?php
  }
  }
  else {
  ?>
  <tr>
    <td colspan="4" style="padding:20px;">
        <?php esc_html_e("No Record(s) Found", 'cf7-pipedrive'); ?>
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
   <a id="vx_clear_logs" class="button" title="<?php esc_html_e('Clear Pipedrive Log','cf7-pipedrive') ?>" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page='.$this->post('page')."&view=log&vx_tab_action_".$this->id."=clear_logs"),'vx_nonce','vx_nonce')); ?>"><?php esc_html_e('Clear Pipedrive Log','cf7-pipedrive') ?></a>
  <div class="tablenav-pages"> <span id="paging_header" class="displaying-num"><?php esc_html_e('Displaying','cf7-pipedrive') ?> <span id="paging_range_min_header"><?php echo esc_html($data['min']); ?></span> - <span id="paging_range_max_header"><?php echo esc_html($data['max']) ?></span> of <span id="paging_total_header"><?php echo esc_html($data['items']) ?></span></span><?php echo wp_kses_post($data['links']) ?></div>
    </div>
  <?php
  }
  ?>
  </form>

 
  </div>
 




  </div>
 <script type="text/javascript">
    var vx_crm_ajax='<?php echo wp_create_nonce("vx_crm_ajax") ?>';
(function( $ ) {  
$(document).ready( function($) {
      
  $("#vx_sel_feed").change(function(){
  var link='<?php echo $this->link_to_settings('logs'); ?>';
  link+='&feed_id='+$(this).val();
  window.location.href=link;
  }); 
    
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
    alert('<?php esc_html_e('Please Select Action','cf7-pipedrive') ?>');
      return false;
}
if($(".crm_input_check:checked").length == 0){ 
    alert('<?php esc_html_e('Please select at least one entry','cf7-pipedrive') ?>');
    return false;
}
var action=sel.val();
if( $.inArray(action,["send_to_crm_bulk_force","send_to_crm_bulk"]) !=-1 && $(".crm_input_check:checked").length>4){
 if(!confirm('<?php esc_html_e('Exporting more than 4 entries may take too long.\\n Are you sure you want to continue?','cf7-pipedrive') ?>')){
  e.preventDefault();    
 }   
}
  })
   $("#vx_clear_logs").click(function(e){
      if(!confirm('<?php esc_html_e('Pipedrive Logs will be deleted permanently. Do you want to continue?','cf7-pipedrive') ?>')){
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
    icon.attr('title','<?php esc_html_e('Expand Detail','cf7-pipedrive'); ?>');    
    }        
     return;   
    }else{
     next_tr.show('fast'); 
    next_tr.find('.vxa_entry').slideDown('fast'); 
    if(is_main){
    icon.attr('title','<?php esc_html_e('Collapse Detail','cf7-pipedrive'); ?>');    
    }    
    }
    if(!td.find("div").length){
      var id=$.trim(tr.attr("data-id"));
    td.html("<div style='text-align:center'><i class='fa fa-spinner fa-spin' style='margin: 20px auto'></i></div>");
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