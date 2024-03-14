<?php

//pagination
$nd_spt_qnt_orders_pag = 10;
$nd_spt_pag_from = sanitize_text_field($_GET["nd_spt_pag_from"]);
$nd_spt_pag_to = sanitize_text_field($_GET["nd_spt_pag_to"]);
$nd_spt_order_status = sanitize_text_field($_GET["nd_spt_order_status"]);
if ( $nd_spt_pag_from == '' ) { $nd_spt_pag_from = 0; }
if ( $nd_spt_pag_to == '' ) { $nd_spt_pag_to = $nd_spt_qnt_orders_pag; }

//START show all orders
global $wpdb;

$nd_spt_result = '';
$nd_spt_order_id = get_the_ID();
$nd_spt_table_name = $wpdb->prefix . 'nd_spt_booking';

//START select for items
if ( $nd_spt_order_status == '' ) { 
  $nd_spt_orders = $wpdb->get_results( "SELECT * FROM $nd_spt_table_name ORDER BY id DESC LIMIT $nd_spt_pag_from, $nd_spt_pag_to");  
}else{
  $nd_spt_orders = $wpdb->get_results( "SELECT * FROM $nd_spt_table_name WHERE nd_spt_order_status = '$nd_spt_order_status' ORDER BY id DESC LIMIT $nd_spt_pag_from, $nd_spt_pag_to");
}

$nd_spt_all_orders = $wpdb->get_results( "SELECT * FROM $nd_spt_table_name");

$nd_spt_all_orders_pending = $wpdb->get_results( "SELECT * FROM $nd_spt_table_name WHERE nd_spt_order_status = 'pending'");
$nd_spt_all_orders_confirmed = $wpdb->get_results( "SELECT * FROM $nd_spt_table_name WHERE nd_spt_order_status = 'confirmed'");


if ( empty($nd_spt_orders) ) { 

  $nd_spt_result .= '

  <style>
    .update-nag { display:none; } 
  </style>

  <h1 class="nd_spt_margin_0" style="font-size: 23px; font-weight: 400;">'.__('Bookings','nd-sports-booking').'</h1>
  <div class="nd_spt_section nd_spt_height_20"></div>
  <div class="nd_spt_position_relative  nd_spt_width_100_percentage nd_spt_box_sizing_border_box nd_spt_display_inline_block">           
    <p class=" nd_spt_margin_0 nd_spt_padding_0">'.__('Still no bookings','nd-sports-booking').'</p>
  </div>';              


}else{


  $nd_spt_result .= '
  <h1 class="nd_spt_margin_0" style="font-size: 23px; font-weight: 400;">'.__('Bookings','nd-sports-booking').'</h1>

  <ul class="subsubsub">
    <li class=""><a href="admin.php?page=nd-sports-booking-settings-orders&nd_spt_pag_from=0&nd_spt_pag_to='.$nd_spt_qnt_orders_pag.'&nd_spt_order_status=" class="current">'.__('All','nd-sports-booking').' <span class="count">('.count($nd_spt_all_orders).')</span></a> |</li>
    <li class=""><a href="admin.php?page=nd-sports-booking-settings-orders&nd_spt_pag_from=0&nd_spt_pag_to='.$nd_spt_qnt_orders_pag.'&nd_spt_order_status=pending">'.__('Pending','nd-sports-booking').' <span class="count">('.count($nd_spt_all_orders_pending).')</span></a> |</li>
    <li class=""><a href="admin.php?page=nd-sports-booking-settings-orders&nd_spt_pag_from=0&nd_spt_pag_to='.$nd_spt_qnt_orders_pag.'&nd_spt_order_status=confirmed">'.__('Confirmed','nd-sports-booking').' <span class="count">('.count($nd_spt_all_orders_confirmed).')</span></a></li>
  </ul>

  <div class="nd_spt_section nd_spt_height_10"></div>

  ';


  //pagination
  $nd_spt_orders_limit = 0;

  if ( $nd_spt_order_status == '' ) { 
    $nd_spt_number_pages = ceil(count($nd_spt_all_orders)/$nd_spt_qnt_orders_pag); 
  }else{
    
    if ( $nd_spt_order_status == 'pending' ){
      $nd_spt_number_pages = ceil(count($nd_spt_all_orders_pending)/$nd_spt_qnt_orders_pag); 
    }else{
      $nd_spt_number_pages = ceil(count($nd_spt_all_orders_confirmed)/$nd_spt_qnt_orders_pag);  
    }

  }
  
  $nd_spt_result_pag = '';
  $nd_spt_result_pag .= '<div style="margin-top:-37px; float:right; width:50%;" class="nd_spt_section nd_spt_text_align_right">';

  for ($nd_spt_number_page = 1; $nd_spt_number_page <= $nd_spt_number_pages; ++$nd_spt_number_page) {
    
    if ( ceil($nd_spt_pag_from/$nd_spt_qnt_orders_pag)+1 == $nd_spt_number_page ) { $nd_spt_pag_active = 'nd_spt_pag_active'; }else{ $nd_spt_pag_active = ''; }

    $nd_spt_result_pag .= '
      
      <span style="line-height:16px; padding:5px;" class="tablenav-pages-navspan '.$nd_spt_pag_active.' " aria-hidden="true">
        <a style="text-decoration: none; color: #a0a5aa;" href="admin.php?page=nd-sports-booking-settings-orders&nd_spt_pag_from='.$nd_spt_orders_limit.'&nd_spt_pag_to='.$nd_spt_qnt_orders_pag.'&nd_spt_order_status='.$nd_spt_order_status.'">'.$nd_spt_number_page.'</a>
      </span>

    ';  
    
    $nd_spt_orders_limit = $nd_spt_orders_limit + $nd_spt_qnt_orders_pag;

  } 

  $nd_spt_result_pag .= '</div>';


  $nd_spt_result .= '

  '.$nd_spt_result_pag.'

  <style>
  .nd_spt_table{
    float:left;
    width:100%;
    background-color:#ccc;
    border-collapse: collapse;
    font-size: 14px;
    line-height: 20px;
    border: 1px solid #e5e5e5;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    box-sizing:border-box;
  }
  .nd_spt_table tr td{
    padding:12px; 
  }


  .nd_spt_table_thead{
    width:100%;
    background-color:#fff;
    border-bottom:1px solid #e1e1e1;
  }
  .nd_spt_table_thead td, .nd_spt_table_tfoot td{
    /*color:#0073aa;*/
  }

  .nd_spt_table_tfoot{
    border-top:1px solid #e1e1e1;
    border-bottom:0px solid #e1e1e1;
    background-color:#fff;
  }
  
  .nd_spt_table tbody{
    width:100%;
  }
  .nd_spt_table_tbody{
    width: 100%;
    background-color: #777;
  }

  .nd_spt_tr_light { background-color:#fff; }
  .nd_spt_tr_dark { background-color:#f9f9f9; }

  .nd_spt_table_tbody td .nd_spt_edit {
    color: #0073aa;
    cursor: pointer;
    background: none;
    border: 0px;
    font-size: 13px;
    padding: 0px; 
  }
  .nd_spt_table_tbody td .nd_spt_edit:hover {
    color:#00a0d2;  
  }
  .nd_spt_table_tbody td .nd_spt_delete {
    color: #a00;
    cursor: pointer;
    background: none;
    border: 0px;
    font-size: 13px;
    padding: 0px; 
  }

  .update-nag { display:none; } 

  .nd_spt_pag_active { background-color:#00a0d2; border-color:#5b9dd9; }
  .nd_spt_pag_active a { color:#fff !important; }
  
  </style>

  <table class="nd_spt_table">
    <tbody>
      <tr class="nd_spt_table_thead">
        <td width="30%">'.__('Player','nd-sports-booking').'</td>
        <td width="20%">'.__('Player Contacts','nd-sports-booking').'</td>
        <td width="30%">'.__('Date','nd-sports-booking').'</td>
        <td width="20%">'.__('Status','nd-sports-booking').'</td>

      </tr>
    ';


  $nd_spt_i = 0;
  foreach ( $nd_spt_orders as $nd_spt_order ) 
  {
    
    //decide status color
    if ( $nd_spt_order->nd_spt_order_status == 'pending' ){
      $nd_spt_color_bg_status = '#e68843';
    }else{
      $nd_spt_color_bg_status = '#54ce59'; 
    }

    //define action type
    $nd_spt_new_action_type = str_replace("_"," ",$nd_spt_order->action_type);

    //get room image
    $nd_spt_id = $nd_spt_order->id_post;
    $nd_spt_image_id = get_post_thumbnail_id($nd_spt_id);
    $nd_spt_image_attributes = wp_get_attachment_image_src( $nd_spt_image_id, 'thumbnail' );
    $nd_spt_room_img_src = $nd_spt_image_attributes[0];

    //get avatar
    $nd_spt_account_avatar_url_args = array( 'size'   => 100 );
    $nd_spt_account_avatar_url = get_avatar_url($nd_spt_order->nd_spt_booking_form_email, $nd_spt_account_avatar_url_args);

    
    if ( $nd_spt_i & 1 ) { $nd_spt_tr_class = 'nd_spt_tr_light'; } else { $nd_spt_tr_class = 'nd_spt_tr_dark'; } 

    $nd_spt_order_id = $nd_spt_order->id_user;

    $nd_spt_result .= '
                               
        <tr class="nd_spt_table_tbody '.$nd_spt_tr_class.'">

          <td>
            <div style="width:50px;" class="nd_spt_float_left">
              <img width="40" src="'.$nd_spt_account_avatar_url.'">
            </div>
            <div class="nd_spt_float_left">
              <span class="nd_spt_section">'.$nd_spt_order->nd_spt_booking_form_name.' '.$nd_spt_order->nd_spt_booking_form_surname.'</span>
              <form class="nd_spt_float_left" method="POST">
                <input type="hidden" name="edit_order_id" value="'.$nd_spt_order->id.'">
                <input type="submit" class="nd_spt_edit" value="'.__('View','nd-sports-booking').'">
              </form>
              <form class="nd_spt_float_left nd_spt_padding_left_10" method="POST">
                <input type="hidden" name="delete_order_id" value="'.$nd_spt_order->id.'">
                <input type="submit" class="nd_spt_delete" value="'.__('Delete','nd-sports-booking').'">
              </form>
            </div>
          </td>
        
          <td>
            <div style="display:table;" class="nd_spt_section">
              <div style="display:table-cell; vertical-align:middle;" class="nd_spt_box_sizing_border_box">
                <span class="nd_spt_section">
                  <a style="background-color: #23282d;color: #fff; text-decoration:none; font-size: 10px;padding: 3px;float: left;line-height: 10px;margin-top: 2px;" href="mailto:'.$nd_spt_order->nd_spt_booking_form_email.'">'.__('EMAIL ME','nd-sports-booking').'</a><br/>
                  <a style="background-color: #0076b3;color: #fff; text-decoration:none; font-size: 10px;padding: 3px;float: left;line-height: 10px;margin-top: 2px;" href="#">'.$nd_spt_order->nd_spt_booking_form_phone.'</a>
                </span>
              </div>
            </div>
          </td>

          <td>
            <span class=""><u>'.__('Date','nd-sports-booking').'</u> : '.$nd_spt_order->nd_spt_date.'</span><bR/>
            <span class=""><u>'.__('From','nd-sports-booking').'</u> : '.$nd_spt_order->nd_spt_time_start.'</span>
            <span class=""><u>'.__('To','nd-sports-booking').'</u> : '.$nd_spt_order->nd_spt_time_end.'</span>
          </td>

          <td><span style="background-color:'.$nd_spt_color_bg_status.';" class="nd_spt_padding_5 nd_spt_color_ffffff nd_spt_font_size_12 nd_spt_text_transform_uppercase">'.$nd_spt_order->nd_spt_order_status.'</span></td>
        
        </tr>

    ';

    $nd_spt_i = $nd_spt_i + 1;


  }


  $nd_spt_result .= '
    <tr class="nd_spt_table_tfoot">
      <td>'.__('Player','nd-sports-booking').'</td>
      <td>'.__('Player Contacts','nd-sports-booking').'</td>
      <td>'.__('Date','nd-sports-booking').'</td>
      <td>'.__('Status','nd-sports-booking').'</td>
    </tr>
    </tbody>
  </table>

  <div class="nd_spt_section nd_spt_height_50"></div>

  '.$nd_spt_result_pag.'

  ';





}
//END show all orders
  
  
  