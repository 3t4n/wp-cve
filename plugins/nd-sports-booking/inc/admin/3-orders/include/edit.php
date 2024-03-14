<?php    

$nd_spt_result = '';
$nd_spt_order_id = sanitize_text_field($_POST['edit_order_id']);

global $wpdb;
$nd_spt_table_name = $wpdb->prefix . 'nd_spt_booking';


//START UPDATE RECORD
if ( isset($_POST['nd_spt_order_id']) ){

  $nd_spt_order_id = sanitize_text_field($_POST['nd_spt_order_id']);

  //calculate end time
  $nd_spt_booking_duration = get_option('nd_spt_booking_duration');
  $nd_spt_booking_duration_insert = $nd_spt_booking_duration-1;
  $nd_spt_time_end = date("G:i:s", strtotime('+'.$nd_spt_booking_duration_insert.' minutes', strtotime($_POST['nd_spt_time_start'])));


  $nd_spt_edit_record = $wpdb->update( 
        
    $nd_spt_table_name, 
    
    array( 
      'id' => sanitize_text_field($_POST['nd_spt_order_id']),
      'nd_spt_sport' => sanitize_text_field($_POST['nd_spt_sport']),
      'nd_spt_players' => sanitize_text_field($_POST['nd_spt_players']),
      'nd_spt_date' => sanitize_text_field($_POST['nd_spt_date']),
      'nd_spt_time_start' => sanitize_text_field($_POST['nd_spt_time_start']),
      'nd_spt_time_end' => $nd_spt_time_end,
      'nd_spt_occasion' => sanitize_text_field($_POST['nd_spt_occasion']),
      'nd_spt_booking_form_name' => sanitize_text_field($_POST['nd_spt_booking_form_name']),
      'nd_spt_booking_form_surname' => sanitize_text_field($_POST['nd_spt_booking_form_surname']),
      'nd_spt_booking_form_email' => sanitize_email($_POST['nd_spt_booking_form_email']),
      'nd_spt_booking_form_phone' => sanitize_text_field($_POST['nd_spt_booking_form_phone']),
      'nd_spt_booking_form_requests' => sanitize_text_field($_POST['nd_spt_booking_form_requests']),
      'nd_spt_order_type' => sanitize_text_field($_POST['nd_spt_order_type']),
      'nd_spt_order_status' => sanitize_text_field($_POST['nd_spt_order_status']),
      'nd_spt_deposit' => sanitize_text_field($_POST['nd_spt_deposit']),
      'nd_spt_tx' => sanitize_text_field($_POST['nd_spt_tx']),
      'nd_spt_currency' => sanitize_text_field($_POST['nd_spt_currency'])
    ),
    array( 'ID' => sanitize_text_field($_POST['nd_spt_order_id']) )

  );


  if ($nd_spt_edit_record){

    $nd_spt_result .= '

      <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible nd_spt_margin_left_0_important nd_spt_margin_bottom_20_important"> 
        <p>
          <strong>'.__('Settings saved.','nd-sports-booking').'</strong>
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



}
//END UPDATE RECORD


//START select order
$nd_spt_orders = $wpdb->get_results( "SELECT * FROM $nd_spt_table_name WHERE id = $nd_spt_order_id");


if ( empty($nd_spt_orders) ) { 

  $nd_spt_result .= '
  <div class="nd_spt_position_relative  nd_spt_width_100_percentage nd_spt_box_sizing_border_box nd_spt_display_inline_block">           
    <p class=" nd_spt_margin_0 nd_spt_padding_0">'.__('There was some db problem','nd-sports-booking').'</p>
  </div>';              


}else{


  foreach ( $nd_spt_orders as $nd_spt_order ) 
  {
     
    //get avatar
    $nd_spt_account_avatar_url_args = array( 'size'   => 100 );
    $nd_spt_account_avatar_url = get_avatar_url($nd_spt_order->nd_spt_booking_form_email, $nd_spt_account_avatar_url_args);


    //decide status color
    if ( $nd_spt_order->nd_spt_order_status == 'pending' ){
      $nd_spt_color_bg_status = '#e68843';
    }else{
      $nd_spt_color_bg_status = '#54ce59'; 
    }

    $nd_spt_result .= '


    <style>
    .update-nag { display:none; }

    .nd_spt_custom_tables table td p {
        margin-bottom: 10px !important;
        margin-top: 10px !important;
        padding-bottom: 0px;
        padding-top: 0px;
    }

    </style>


  
    <form method="POST">

      <div class="nd_spt_section">


        <div style="width:80%;" class="nd_spt_float_left  nd_spt_padding_right_20 nd_spt_box_sizing_border_box">
          
          <div style="border: 1px solid #e5e5e5; box-shadow: 0 1px 1px rgba(0,0,0,.04);" class="nd_spt_section nd_spt_background_color_ffffff nd_spt_padding_10 nd_spt_box_sizing_border_box">

            <div style="padding-bottom:0px;" class="nd_spt_section nd_spt_box_sizing_border_box nd_spt_padding_20">
              


              <div style="display:table;" class="nd_spt_section">
                
                <div style="width:80px; display:table-cell; vertical-align:middle; ">
                  <img class="nd_spt_float_left" width="60" src="'.$nd_spt_account_avatar_url.'">
                </div>

                <div style="display:table-cell; vertical-align:middle;" class="nd_spt_box_sizing_border_box">
                  
                  <div class="nd_spt_section nd_spt_height_5"></div>
                  <div class="nd_spt_section">
                    <h1 class="nd_spt_margin_0 nd_spt_display_inline_block nd_spt_float_left">'.__('Booking','nd-sports-booking').' #'.$nd_spt_order->id.' '.__('details','nd-sports-booking').' </h1>
                    <span style="background-color:'.$nd_spt_color_bg_status.'; margin-left:15px; margin-top:-5px;" class="nd_spt_padding_5 nd_spt_display_block nd_spt_float_left nd_spt_color_ffffff nd_spt_font_size_12 nd_spt_text_transform_uppercase">'.$nd_spt_order->nd_spt_order_status.'</span>
                  </div>
                  
                  <div class="nd_spt_section nd_spt_height_10"></div>
                  <p class="nd_spt_margin_0">'.get_the_title($nd_spt_order->nd_spt_sport).' #'.$nd_spt_order->nd_spt_sport.' '.__('for','nd-sports-booking').' <u>'.$nd_spt_order->nd_spt_players.' '.__('Players','nd-sports-booking').'</u></p>

                </div>

              </div>


              


            </div>

            <div style="width:33.33%;" class="nd_spt_float_left nd_spt_box_sizing_border_box nd_spt_padding_20">
              

                <h3>'.__('General Details','nd-sports-booking').'</h3>

                <input readonly name="nd_spt_order_id" class="nd_spt_display_none nd_spt_display_block regular-text" type="text" value="'.$nd_spt_order->id.'"> 


                <label class="nd_spt_section">'.__('Sport','nd-sports-booking').'</label>
                <div class="nd_spt_section nd_spt_height_5"></div>
                <select class="nd_spt_width_100_percentage" name="nd_spt_sport" id="">';
                

                  $nd_spt_rooms_args = array( 'posts_per_page' => -1, 'post_type'=> 'nd_spt_cpt_1' );
                  $nd_spt_rooms = get_posts($nd_spt_rooms_args); 

                  foreach ($nd_spt_rooms as $nd_spt_room) { 
                      $nd_spt_result .= '<option '; if ( $nd_spt_order->nd_spt_sport == $nd_spt_room->ID ){ $nd_spt_result .= 'selected="selected"'; } $nd_spt_result .= ' value="'.$nd_spt_room->ID.'">'.$nd_spt_room->post_title.'</option>';
                  }

                $nd_spt_result .= '  
                </select>
                <div class="nd_spt_section nd_spt_height_20"></div>


                <label class="nd_spt_section">'.__('Date ( YYYY-MM-DD )','nd-sports-booking').'</label>
                <div class="nd_spt_section nd_spt_height_5"></div>
                <input name="nd_spt_date" class="nd_spt_section nd_spt_display_block regular-text" type="text" value="'.$nd_spt_order->nd_spt_date.'"> 

                <div class="nd_spt_section nd_spt_height_20"></div>

                <label class="nd_spt_section">'.__('Time Start/End','nd-sports-booking').'</label>
                <div class="nd_spt_section nd_spt_height_5"></div>

                <div class="nd_spt_width_50_percentage nd_spt_float_left nd_spt_box_sizing_border_box nd_spt_padding_right_10">

                  <select class="nd_spt_width_100_percentage" name="nd_spt_time_start">';
                
                  $nd_spt_exception_sols = array('00:00:00','00:30:00','01:00:00','01:30:00','02:00:00','02:30:00','03:00:00','03:30:00','04:00:00','04:30:00','05:00:00','05:30:00','06:00:00','06:30:00','07:00:00','07:30:00','08:00:00','08:30:00','09:00:00','09:30:00','10:00:00','10:30:00','11:00:00','11:30:00','12:00:00','12:30:00','13:00:00','13:30:00','14:00:00','14:30:00','15:00:00','15:30:00','16:00:00','16:30:00','17:00:00','17:30:00','18:00:00','18:30:00','19:00:00','19:30:00','20:00:00','20:30:00','21:00:00','21:30:00','22:00:00','22:30:00','23:00:00','23:30:00');

                  foreach ($nd_spt_exception_sols as $nd_spt_exceptions_sol) :

                    $nd_spt_result .= '<option '; if ( $nd_spt_exceptions_sol == $nd_spt_order->nd_spt_time_start ) { $nd_spt_result .= 'selected="selected"'; } $nd_spt_result .= ' value="'.$nd_spt_exceptions_sol.'">'.$nd_spt_exceptions_sol.'</option>';

                  endforeach;

                  $nd_spt_result .= ' 
                  </select>
                </div>
                <div class="nd_spt_width_50_percentage nd_spt_float_left nd_spt_box_sizing_border_box nd_spt_padding_left_10">
                  <input readonly name="nd_spt_time_end" class="nd_spt_section nd_spt_display_block regular-text" type="text" value="'.$nd_spt_order->nd_spt_time_end.'"> 
                </div>

                <div class="nd_spt_section nd_spt_height_20"></div>

                <label class="nd_spt_section">'.__('Players and Services','nd-sports-booking').'</label>
                <div class="nd_spt_section nd_spt_height_5"></div>

                <div class="nd_spt_width_50_percentage nd_spt_float_left nd_spt_box_sizing_border_box nd_spt_padding_right_10">
                  <input name="nd_spt_players" class="nd_spt_section nd_spt_display_block regular-text" type="text" value="'.$nd_spt_order->nd_spt_players.'">
                </div>
                <div class="nd_spt_width_50_percentage nd_spt_float_left nd_spt_box_sizing_border_box nd_spt_padding_left_10">

                  <select class="nd_spt_width_100_percentage" name="nd_spt_occasion" id="">';

                    $nd_spt_occasions = get_option('nd_spt_occasions');
                    if ( $nd_spt_occasions == '' ) {
                        $nd_spt_occasion_value = 0;  
                    }else { 
                        $nd_spt_occasions_array = explode(',', $nd_spt_occasions ); 
                    }

                    for ( $nd_spt_occasions_array_i = 0; $nd_spt_occasions_array_i < count($nd_spt_occasions_array); $nd_spt_occasions_array_i++) { 

                      $nd_spt_result .= '<option '; if ( $nd_spt_occasions_array_i == $nd_spt_order->nd_spt_occasion ) { $nd_spt_result .= 'selected="selected"'; }  $nd_spt_result .= ' value="'.$nd_spt_occasions_array_i.'">'.$nd_spt_occasions_array[$nd_spt_occasions_array_i].'</option>';

                    }

                  $nd_spt_result .= '  
                  </select>

                </div>



            </div>

            <div style="width:33.33%;" class="nd_spt_float_left nd_spt_box_sizing_border_box nd_spt_padding_20">


                <h3>'.__('Player Details','nd-sports-booking').'</h3>

                <label class="nd_spt_section">'.__('Name','nd-sports-booking').'</label>
                <div class="nd_spt_section nd_spt_height_5"></div>
                <div class="nd_spt_width_100_percentage nd_spt_float_left nd_spt_box_sizing_border_box">
                  <input name="nd_spt_booking_form_name" class="nd_spt_section nd_spt_display_block regular-text" type="text" value="'.$nd_spt_order->nd_spt_booking_form_name.'">
                </div>

                <div class="nd_spt_section nd_spt_height_20"></div>

                <label class="nd_spt_section">'.__('Surname','nd-sports-booking').'</label>
                <div class="nd_spt_section nd_spt_height_5"></div>
                <div class="nd_spt_width_100_percentage nd_spt_float_left nd_spt_box_sizing_border_box">
                  <input name="nd_spt_booking_form_surname" class="nd_spt_section nd_spt_display_block regular-text" type="text" value="'.$nd_spt_order->nd_spt_booking_form_surname.'"> 
                </div>
                
                <div class="nd_spt_section nd_spt_height_20"></div>

                <label class="nd_spt_section">'.__('Email','nd-sports-booking').'</label>
                <div class="nd_spt_section nd_spt_height_5"></div>
                <input name="nd_spt_booking_form_email" class="nd_spt_section nd_spt_display_block regular-text" type="text" value="'.$nd_spt_order->nd_spt_booking_form_email.'"> 

                <div class="nd_spt_section nd_spt_height_20"></div>

                <label class="nd_spt_section">'.__('Phone','nd-sports-booking').'</label>
                <div class="nd_spt_section nd_spt_height_5"></div>
                <input name="nd_spt_booking_form_phone" class="nd_spt_section nd_spt_display_block regular-text" type="text" value="'.$nd_spt_order->nd_spt_booking_form_phone.'"> 

            </div>

            <div style="width:33.33%;" class="nd_spt_float_left nd_spt_box_sizing_border_box nd_spt_padding_20">

                <label style="margin-top:50px;" class="nd_spt_section">'.__('Message','nd-sports-booking').'</label>
                <div class="nd_spt_section nd_spt_height_5"></div>
                <textarea rows="12" name="nd_spt_booking_form_requests" class="nd_spt_section nd_spt_display_block regular-text">'.$nd_spt_order->nd_spt_booking_form_requests.'</textarea>

            </div>
            
          
          </div>';






        $nd_spt_result .= '
        </div>

        <div style="width:20%; border: 1px solid #e5e5e5; box-shadow: 0 1px 1px rgba(0,0,0,.04);" class="nd_spt_float_left nd_spt_background_color_ffffff nd_spt_box_sizing_border_box">
          
        
          <h4 class="nd_spt_margin_0 nd_spt_padding_10_20 nd_spt_border_bottom_1_solid_eee">'.__('Booking Options','nd-sports-booking').'</h4>

          <div class="nd_spt_section nd_spt_box_sizing_border_box nd_spt_padding_20">

            <label class="nd_spt_section">'.__('Booking Type','nd-sports-booking').'</label>
            <div class="nd_spt_section nd_spt_height_5"></div>
            <input readonly name="nd_spt_order_type" class="nd_spt_section  nd_spt_display_block regular-text" type="text" value="'.$nd_spt_order->nd_spt_order_type.'">

            <div class="nd_spt_section nd_spt_height_20"></div>

            <label class="nd_spt_section">'.__('Booking Status','nd-sports-booking').'</label>
            <div class="nd_spt_section nd_spt_height_5"></div>
            <select name="nd_spt_order_status" class="nd_spt_section nd_spt_display_block">
              <option '; if ( $nd_spt_order->nd_spt_order_status == 'pending' ){ $nd_spt_result .= 'selected="selected"'; }  $nd_spt_result .= 'value="pending">'.__('Pending','nd-sports-booking').'</option>
              <option '; if ( $nd_spt_order->nd_spt_order_status == 'confirmed' ){ $nd_spt_result .= 'selected="selected"'; }  $nd_spt_result .= 'value="confirmed">'.__('Confirmed','nd-sports-booking').'</option>
            </select>

            <div class="nd_spt_section nd_spt_height_20"></div>

            <label class="nd_spt_section">'.__('Booking Tx','nd-sports-booking').'</label>
            <div class="nd_spt_section nd_spt_height_5"></div>
            <input readonly name="nd_spt_tx" class="nd_spt_section  nd_spt_display_block regular-text" type="text" value="'.$nd_spt_order->nd_spt_tx.'">

            <div class="nd_spt_section nd_spt_height_20"></div>

            <label class="nd_spt_section">'.__('Deposit already Paid','nd-sports-booking').' ( '.$nd_spt_order->nd_spt_currency.' )</label>
            <div class="nd_spt_section nd_spt_height_5"></div>
            <input readonly name="nd_spt_deposit" class="nd_spt_section  nd_spt_display_block regular-text" type="text" value="'.$nd_spt_order->nd_spt_deposit.'">
            <input readonly name="nd_spt_currency" class="nd_spt_section nd_spt_display_none nd_spt_display_block regular-text" type="text" value="'.$nd_spt_order->nd_spt_currency.'">

          </div>


          <div class="nd_spt_background_color_f5f5f5 nd_spt_section nd_spt_box_sizing_border_box nd_spt_padding_20 nd_spt_border_top_1_solid_eee">
            <input class="button button-primary" type="submit" value="'.__('Update Record','nd-sports-booking').'"> 
          </div>


        </div>


      </div>


      

    </form>
    ';

  }


} 