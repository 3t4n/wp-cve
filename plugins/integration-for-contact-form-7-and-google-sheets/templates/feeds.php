<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }  ?>  <style type="text/css">
  .user-list tr {
  cursor: move;
  }
  .user-list tr td a {
  cursor: pointer;
  }
  .user-list tr:nth-child(even) {
  background-color: #f5f5f5;
  }
  .vx_col{
  width: 35px; padding-top: 12px !important; text-align: center; cursor: auto;
  }
  .vx_date{
  width: 18%;
  }
  .ui-sortable-helper {
  display: table;
  background: #eee;
  }

  </style>
  <div class="vx_wrap"> 
  <h2  class="vx_img_head"><img alt="<?php esc_html_e("Google Sheets Feeds", 'integration-for-contact-form-7-and-spreadsheets') ?>" title="<?php esc_html_e("Google Sheets Feeds", 'integration-for-contact-form-7-and-spreadsheets') ?>" src="<?php echo $this->get_base_url()?>images/googlesheets-crm-logo.png?ver=1" /> <?php esc_html_e("Google Sheets Feeds", 'integration-for-contact-form-7-and-spreadsheets'); ?> <a class="page-title-action vx_new_feed" href="<?php echo $new_feed_link?>">
  <?php esc_html_e("Add New", 'integration-for-contact-form-7-and-spreadsheets') ?>
  </a> </h2>
  <div class="clear"></div>
  <?php
  if(!$valid_accounts){
  ?>
  <div class="error below-h2" id="message" style="margin-top:20px;">
  <p><?php echo  sprintf( esc_html__("To get started, please configure your %s Google Sheets Settings %s.", 'integration-for-contact-form-7-and-spreadsheets'), '<a href="'.esc_url($page_link).'">', "</a>") ?></p>
  </div>
  <?php
  } 
  ?>

  <form id="feed_form" method="post">
  <?php wp_nonce_field('vx_crm_ajax') ?>
  <input type="hidden" id="action" name="action"/>
  <input type="hidden" id="action_argument" name="action_argument"/>
  <div class="tablenav">
  <div class="alignleft actions" style="padding:8px 0 7px; ">
  <label class="hidden" for="bulk_action">
  <?php esc_html_e("Bulk action", 'integration-for-contact-form-7-and-spreadsheets') ?>
  </label>
  <select name="bulk_action" id="bulk_action" style="width: 200px">
  <option value=''>
  <?php esc_html_e("Bulk action", 'integration-for-contact-form-7-and-spreadsheets') ?>
  </option>
  <option value='delete'>
  <?php esc_html_e("Delete", 'integration-for-contact-form-7-and-spreadsheets') ?>
  </option>
  </select>
  <button type="submit" title="<?php  esc_html_e("Apply Action", 'integration-for-contact-form-7-and-spreadsheets') ?>" class="button" id="vx_bulk_actions_submit">
  <?php  esc_html_e("Apply", 'integration-for-contact-form-7-and-spreadsheets') ?>
  </button>
  </div>
  </div>
  <table class="widefat fixed sort" cellspacing="0">
  <thead>
  <tr>
  <td id="cb" class="column-cb check-column" style=""><input type="checkbox" /></td>
  <th id="active" class="vx_col"></th>
  <th><?php esc_html_e("Name", 'integration-for-contact-form-7-and-spreadsheets') ?></th>
  <th><?php esc_html_e("Google Sheets Object", 'integration-for-contact-form-7-and-spreadsheets') ?></th>
  <th class="vx_date"><?php esc_html_e("Created", 'integration-for-contact-form-7-and-spreadsheets') ?></th>
  </tr>
  </thead>
  <tfoot>
  <tr>
  <td id="cb" class="column-cb check-column" style=""><input type="checkbox" /></td>
  <th id="active" class="vx_col"></th>
  <th><?php esc_html_e("Name", 'integration-for-contact-form-7-and-spreadsheets') ?></th>
  <th><?php esc_html_e("Google Sheets Object", 'integration-for-contact-form-7-and-spreadsheets') ?></th>
  <th class="vx_date"><?php esc_html_e("Created", 'integration-for-contact-form-7-and-spreadsheets') ?></th>
  </tr>
  </tfoot>
  <tbody class="list:user user-list">
  <?php
  
  if(is_array($feeds) && !empty($feeds)){
  
      foreach($feeds as $feed){
          $data=$this->post('data',$feed);
          $meta=$this->post('meta',$feed);
          $data=json_decode($data,true);
          $fields=json_decode($meta,true);
          $fields=$this->post('fields',$fields);
  $primary_key=!empty($data['primary_key']) && isset($fields[$data['primary_key']]['label']) ? $fields[$data['primary_key']]['label'] : esc_html__('N/A','integration-for-contact-form-7-and-spreadsheets');
  $edit_link=$this->get_feed_link($feed['id']);
          ?>
  <tr class='author-self status-inherit' data-id="<?php echo esc_attr($feed['id']) ?>">
  <th scope="row" class="check-column"><input type="checkbox" class="vx_check" name="feed[]" value="<?php echo esc_attr($feed["id"]) ?>"/></th>
  <td class="vx_col"><img src="<?php echo $this->get_base_url() ?>images/active<?php echo intval($feed["is_active"]) ?>.png" alt="<?php echo $feed["is_active"] ? esc_html__("Active", 'integration-for-contact-form-7-and-spreadsheets') : esc_html__("Inactive", 'integration-for-contact-form-7-and-spreadsheets');?>" title="<?php echo $feed["is_active"] ? esc_html__("Active", 'integration-for-contact-form-7-and-spreadsheets') : esc_html__("Inactive", 'integration-for-contact-form-7-and-spreadsheets');?>" class="vx_toggle_status" /></td>
  <td><a href="<?php echo esc_url($edit_link); ?>" title="<?php echo esc_html( $feed["name"] ) ?>"><?php echo esc_attr($feed["name"])  ?></a> 
  
  <div class="row-actions"> <span class="edit"> 
  <a title="<?php esc_attr_e("Edit Settings", 'integration-for-contact-form-7-and-spreadsheets') ?>" href="<?php echo esc_url($edit_link); ?>">
  <?php esc_html_e("Edit", 'integration-for-contact-form-7-and-spreadsheets') ?>
  </a> | 
  </span> 
  <span class="edit"> 
  <a title="<?php esc_html_e("Delete", 'integration-for-contact-form-7-and-spreadsheets') ?>" href="#" class="vx_del_feed">
  <?php esc_html_e("Delete", 'integration-for-contact-form-7-and-spreadsheets')?>
  </a>  
  </span> 

  </div></td>
  <td><p><?php echo isset($objects[$feed["object"]]) ? esc_html($objects[$feed["object"]]) : esc_html($feed["object"]); ?></p></td>
  <td><p><?php echo date('M-d-Y H:i:s', strtotime($feed['time'])+$offset); ?></p></td>
  </tr>
  <?php
      }
  }
  else {
      if($valid_accounts){
          ?>
  <tr>
  <td colspan="4" style="padding:20px;"><?php echo sprintf(esc_html__("You don't have any Google Sheets feeds configured. Let's go %s create one %s!", 'integration-for-contact-form-7-and-spreadsheets'), '<a href="'.esc_url($new_feed_link).'">', "</a>"); ?></td>
  </tr>
  <?php
      }
      else{
          ?>
  <tr>
  <td colspan="4" style="padding:20px;"><?php echo sprintf(esc_html__("To get started, please configure your %s Google Sheets Settings%s.", 'integration-for-contact-form-7-and-spreadsheets'), '<a href="'.esc_url($page_link).'">', "</a>"); ?></td>
  </tr>
  <?php
      }
  }
  ?>
  </tbody>
  </table>
  </form>
  </div>
  <?php
     // do_action('add_section_'.$this->id);
  ?>
  <script type="text/javascript">
  var vx_crm_nonce='<?php echo wp_create_nonce("vx_crm_ajax") ?>';

  (function( $ ) {
  
  $(document).ready( function($) {
      
             $(".vx_del_feed").click(function(e){
           e.preventDefault();
      if(!confirm("<?php esc_html_e("Delete this feed? 'Cancel' to stop, 'OK' to delete.", 'integration-for-contact-form-7-and-spreadsheets') ?>")){
          return;
      }
      var id=$(this).closest('tr').data('id');
     jQuery("#action_argument").val(id);
  jQuery("#action").val("delete");
  jQuery("#feed_form")[0].submit(); 
  });
  $(".vx_toggle_status").click(function(e){
      e.preventDefault();
    var feed_id;
    var img=this;
  var is_active = img.src.indexOf("active1.png") >=0
  var $img=$(this);
  
  if(is_active){
  img.src=img.src.replace("active1.png", "active0.png");
  $img.attr('title','<?php esc_html_e("Inactive", 'integration-for-contact-form-7-and-spreadsheets') ?>').attr('alt', '<?php esc_html_e("Inactive", 'integration-for-contact-form-7-and-spreadsheets') ?>');
  }
  else{
  img.src = img.src.replace("active0.png", "active1.png");
  $img.attr('title','<?php esc_html_e("Active", 'integration-for-contact-form-7-and-spreadsheets') ?>').attr('alt', '<?php esc_html_e("Active", 'integration-for-contact-form-7-and-spreadsheets') ?>');
  }
  
  if(feed_id = $img.closest('tr').attr('data-id')) {
      $.post(ajaxurl,{action:"update_feed_<?php echo esc_attr($this->id) ?>",vx_crm_ajax:vx_crm_nonce,feed_id:feed_id,is_active:is_active ? 0 : 1})
  }
  });
  
  $("#vx_bulk_actions_submit").click(function(e){
       if($("#bulk_action").val() == ""){
  alert('<?php esc_html_e('Please Select Action','integration-for-contact-form-7-and-spreadsheets') ?>');
  return false;
  }
  if($(".vx_check:checked").length == 0){
  alert('<?php esc_html_e('Please Select Feed','integration-for-contact-form-7-and-spreadsheets') ?>');
  return false;
  }
  if(!confirm('<?php esc_html_e("Are you sure to Delete selected feeds?",'integration-for-contact-form-7-and-spreadsheets'); ?>' )){
  return false;
  }    
  })
  $('.sort tbody').sortable({
  axis: 'y',
  helper: "clone",
  helper: function(e, tr)
  {
    var $originals = tr.children();
    var $helper = tr.clone();
    $helper.children().each(function(index)
    {
      // Set helper cell sizes to match the original sizes
      $(this).width($originals.eq(index).width());
    });
    return $helper;
  },
  update: function(event, ui){
  var data = {
  'action': 'update_feed_sort_<?php echo esc_attr($this->id) ?>',
  'sort': [],
  'vx_crm_ajax': vx_crm_nonce,
  };
  
  $(this).children().each(function(index, element) {
  var id = $(element).attr('data-id')
  data.sort.push(id);
  })
  
  $.post( ajaxurl, data );
  
  }
  });
  
  });
  
  }(jQuery));
  </script>
  
    <?php
 do_action('vx_plugin_upgrade_notice_plugin_'.$this->type);
  ?>