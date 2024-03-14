<?php    

$nd_spt_result = '';
$nd_spt_order_id = sanitize_text_field($_POST['delete_order_id']);

if ( isset($_POST['nd_spt_delete_order_id']) ) {

  global $wpdb;
  $nd_spt_table_name = $wpdb->prefix . 'nd_spt_booking';

  $nd_spt_delete_record = $wpdb->delete( 
        
    $nd_spt_table_name, 
    array( 'ID' => sanitize_text_field($_POST['nd_spt_delete_order_id']) )

  );


  if ($nd_spt_delete_record){

    $nd_spt_result .= '

      <style>
        .update-nag { display:none; } 
      </style>


      <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible nd_spt_margin_left_0_important nd_spt_margin_bottom_20_important"> 
        <p>
          <strong>'.__('Deleted','nd-sports-booking').'</strong>
        </p>
        <button type="button" class="notice-dismiss">
          <span class="screen-reader-text">'.__('Dismiss this notice.','nd-sports-booking').'</span>
        </button>
      </div>

    ';

  }else{

    #$wpdb->show_errors();
    #$wpdb->print_error();

  }


}else{

  $nd_spt_result .= '

    <style>
        .update-nag { display:none; } 
      </style>

    <h1>'.__('Delete Booking','nd-sports-booking').' : '.$nd_spt_order_id.'</h1>
    <p>'.__('Please confirm delete by clicking on the button below','nd-sports-booking').'</p>
    <form method="POST">
      <input type="hidden" name="nd_spt_delete_order_id" value="'.$nd_spt_order_id.'">
      <input class="button button-primary" type="submit" value="'.__('Delete','nd-sports-booking').'">
    </form>
  ';

}

