<?php


function nd_spt_get_opening_hour(){

  //get qnt timing
  $nd_spt_timing_qnt = get_option('nd_spt_timing_qnt');

  $nd_spt_get_opening_hour = '23:00';
  $nd_spt_get_opening_hour_time = strtotime($nd_spt_get_opening_hour);

  for ( $nd_spt_time_i = 1; $nd_spt_time_i <= $nd_spt_timing_qnt; $nd_spt_time_i++) {

    $nd_spt_stringg_option = 'nd_spt_timing_start_'.$nd_spt_time_i; 
    $nd_spt_stringg_option_time = strtotime(get_option($nd_spt_stringg_option));

    if ( $nd_spt_stringg_option_time < $nd_spt_get_opening_hour_time ) {

      $nd_spt_get_opening_hour = $nd_spt_stringg_option_time;
      $nd_spt_get_opening_hour_time = $nd_spt_stringg_option_time;

    }
    
  }

  return date("H:i", $nd_spt_get_opening_hour);

}




function nd_spt_get_closing_hour(){

  //get qnt timing
  $nd_spt_timing_qnt = get_option('nd_spt_timing_qnt');

  $nd_spt_get_opening_hour = '01:00';
  $nd_spt_get_opening_hour_time = strtotime($nd_spt_get_opening_hour);

  for ( $nd_spt_time_i = 1; $nd_spt_time_i <= $nd_spt_timing_qnt; $nd_spt_time_i++) {

    $nd_spt_stringg_option = 'nd_spt_timing_end_'.$nd_spt_time_i; 
    $nd_spt_stringg_option_time = strtotime(get_option($nd_spt_stringg_option));

    if ( $nd_spt_stringg_option_time > $nd_spt_get_opening_hour_time ) {

      $nd_spt_get_opening_hour = $nd_spt_stringg_option_time;
      $nd_spt_get_opening_hour_time = $nd_spt_stringg_option_time;

    }
    
  }

  return date("H:i", $nd_spt_get_opening_hour);

}




function nd_spt_get_qnt_players_on_local($nd_spt_time_slot,$nd_spt_date,$nd_spt_sport){

  $nd_spt_time_slott = new DateTime($nd_spt_time_slot);
  $nd_spt_time_slot_normal_format = date_format($nd_spt_time_slott, 'H:i:s');

  $nd_spt_get_qnt_players_on_local = 0;

  //START db query
  global $wpdb;
  $nd_spt_table_name = $wpdb->prefix . 'nd_spt_booking';

  $nd_spt_reservations = $wpdb->get_results( "SELECT * FROM $nd_spt_table_name WHERE nd_spt_date = '$nd_spt_date' AND nd_spt_sport = '$nd_spt_sport' AND '$nd_spt_time_slot_normal_format' >= nd_spt_time_start AND '$nd_spt_time_slot_normal_format' <= nd_spt_time_end AND nd_spt_order_status = 'confirmed'");

  if ( empty($nd_spt_reservations) ) { 

    $nd_spt_get_qnt_players_on_local = 0;

  }
  else{

    foreach ( $nd_spt_reservations as $nd_spt_reservation ) {

      //order datas
      $nd_spt_time_start = $nd_spt_reservation->nd_spt_time_start;
      $nd_spt_time_end = $nd_spt_reservation->nd_spt_time_end;
      $nd_spt_players = $nd_spt_reservation->nd_spt_players;
      
      $nd_spt_get_qnt_players_on_local = $nd_spt_get_qnt_players_on_local+$nd_spt_players;

    }

  }


  return $nd_spt_get_qnt_players_on_local;

}





function nd_spt_get_availability($nd_spt_date,$nd_spt_time_slot,$nd_spt_players,$nd_spt_sport_id){

  $nd_spt_time_slott = new DateTime($nd_spt_time_slot);
  $nd_spt_time_slot = date_format($nd_spt_time_slott, 'H:i:s');

  //recover options
  $nd_spt_max_players = get_option('nd_spt_max_players'); if ( $nd_spt_max_players == '' ) { $nd_spt_max_players = 10; }
  $nd_spt_booking_duration = get_option('nd_spt_booking_duration'); if ( $nd_spt_booking_duration == '' ) { $nd_spt_booking_duration = 30; }
  $nd_spt_slot_interval = get_option('nd_spt_slot_interval'); if ( $nd_spt_slot_interval == '' ) { $nd_spt_slot_interval = 30; }
  
  //recover datas
  $nd_spt_dateee = new DateTime($nd_spt_date); //declare the string as time()
  $nd_spt_date_normal_format = date_format($nd_spt_dateee, 'Y-m-d');

  //deafult availability
  $nd_spt_get_availability = 0;


  //START db query
  global $wpdb;
  $nd_spt_table_name = $wpdb->prefix . 'nd_spt_booking';

  $nd_spt_reservations = $wpdb->get_results( "SELECT * FROM $nd_spt_table_name WHERE nd_spt_date = '$nd_spt_date_normal_format' AND nd_spt_sport = '$nd_spt_sport_id' AND nd_spt_order_status = 'confirmed'");

  if ( empty($nd_spt_reservations) ) { 

  }
  else{

    
    //for each order check if
    foreach ( $nd_spt_reservations as $nd_spt_reservation ) {

      $nd_spt_time_start = $nd_spt_reservation->nd_spt_time_start;
      $nd_spt_time_end = $nd_spt_reservation->nd_spt_time_end;

      //calculate end time of slot
      $nd_spt_booking_duration_insert = $nd_spt_booking_duration-1;
      $nd_spt_time_slot_end = date("H:i:s", strtotime('+'.$nd_spt_booking_duration_insert.' minutes', strtotime($nd_spt_time_slot))); //add minutes slot to start time

      //cicle for every slot ( check the integer result )
      $nd_spt_qnt_cicle = $nd_spt_booking_duration/$nd_spt_slot_interval;
      $nd_spt_time_slot_incr = $nd_spt_time_slot;

      for ($nd_spt_availability_i = 1; $nd_spt_availability_i <= $nd_spt_qnt_cicle; $nd_spt_availability_i++) {
           
        //analyze all slots 
        if ( $nd_spt_time_slot_incr >= $nd_spt_time_start && $nd_spt_time_slot_incr <= $nd_spt_time_end ){

          $nd_spt_get_availability = nd_spt_get_qnt_players_on_local($nd_spt_time_slot_incr,$nd_spt_date_normal_format,$nd_spt_sport_id);

          if ( $nd_spt_get_availability+$nd_spt_players > $nd_spt_max_players ){
            return 1;
          }else{
            $nd_spt_get_availability = 0;  
          }
 
        }

        $nd_spt_time_slot_incr = date("H:i:s", strtotime('+'.$nd_spt_slot_interval.' minutes', strtotime($nd_spt_time_slot_incr)));
             
      }
      //end cicle for


    }
    //end for each


  }
  //END query


  return $nd_spt_get_availability;

}



function nd_spt_close_day($nd_spt_date_to_check){

  $nd_spt_close_day_result = 0;

  //recover datas from options
  $nd_spt_exceptions_qnt = get_option('nd_spt_exceptions_qnt');

  for ( $nd_spt_close_i = 0; $nd_spt_close_i <= $nd_spt_exceptions_qnt; $nd_spt_close_i++) {

    $nd_spt_stringg_option = 'nd_spt_exception_date_'.$nd_spt_close_i;  

    if ( $nd_spt_date_to_check == get_option($nd_spt_stringg_option) ) {
      
      $nd_spt_close_value = get_option('nd_spt_exception_close_'.$nd_spt_close_i);

      if ( $nd_spt_close_value == 1 ){
        $nd_spt_close_day_result = 1;
      }else{
        $nd_spt_close_day_result = 2;  
      }

    }

  }

  return $nd_spt_close_day_result;

}








function nd_spt_set_day($nd_spt_date_to_check){

  $nd_spt_n = date("N",strtotime($nd_spt_date_to_check));

  $nd_spt_set_day = 0;

  //recover datas from options
  $nd_spt_exceptions_qnt = get_option('nd_spt_timing_qnt');

  for ( $nd_spt_close_i = 0; $nd_spt_close_i <= $nd_spt_exceptions_qnt; $nd_spt_close_i++) {

    $nd_spt_stringg_option = 'nd_spt_timing_'.$nd_spt_n.'_'.$nd_spt_close_i;  

    if ( get_option($nd_spt_stringg_option) == 1 ) {
      
      return 1;

    }

  }

  return get_option($nd_spt_stringg_option);

}









function nd_spt_get_timing($nd_spt_datee,$nd_spt_players,$nd_spt_sport_id){

  $nd_spt_dateee = new DateTime($nd_spt_datee); //declare the string as time()
  $nd_spt_number_week_day = date_format($nd_spt_dateee, 'N');
  $nd_spt_date_normal_format = date_format($nd_spt_dateee, 'Y-m-d');

  //set format time
  $nd_spt_format_time = 'G:i';

  //recover datas from options
  $nd_spt_timing_qnt = get_option('nd_spt_timing_qnt');
  $nd_spt_slot_interval = get_option('nd_spt_slot_interval');
  if ( $nd_spt_slot_interval == '' ){ $nd_spt_slot_interval = 60; }

  $nd_spt_get_slot_times = '';

  //cicle for get the hours of the day
  $nd_spt_get_day_hours = '';
  for ( $nd_spt_timing_i = 1; $nd_spt_timing_i <= $nd_spt_timing_qnt; $nd_spt_timing_i++) {

    $nd_spt_string_option = 'nd_spt_timing_'.$nd_spt_number_week_day.'_'.$nd_spt_timing_i;

    if ( get_option($nd_spt_string_option) == 1 ) {
      $nd_spt_timing_start = get_option('nd_spt_timing_start_'.$nd_spt_timing_i);
      $nd_spt_timing_end = get_option('nd_spt_timing_end_'.$nd_spt_timing_i);
      $nd_spt_get_day_hours = $nd_spt_timing_start.'-'.$nd_spt_timing_end;
    }

  } 
  //end cicle



  //recover datas from options
  $nd_spt_exceptions_qnt = get_option('nd_spt_exceptions_qnt');

  for ( $nd_spt_ext_i = 0; $nd_spt_ext_i <= $nd_spt_exceptions_qnt; $nd_spt_ext_i++) {

    $nd_spt_stringg_option = 'nd_spt_exception_date_'.$nd_spt_ext_i;  

    if ( $nd_spt_date_normal_format == get_option($nd_spt_stringg_option) ) {
      
      $nd_spt_close_value = get_option('nd_spt_exception_close_'.$nd_spt_ext_i);

      if ( $nd_spt_close_value == 0 ){

          //the date is an exception time
          $nd_spt_exception_start_option = get_option('nd_spt_exception_start_'.$nd_spt_ext_i);
          $nd_spt_exception_end_option = get_option('nd_spt_exception_end_'.$nd_spt_ext_i);
          $nd_spt_get_day_hours = $nd_spt_exception_start_option.'-'.$nd_spt_exception_end_option;

      }

    }

  }



  //explode for insert the two hours in array
  $nd_spt_hours = explode("-", $nd_spt_get_day_hours);
  
  //start hour
  $nd_spt_hour_start = $nd_spt_hours[0]; //recover hour from array
  $nd_spt_strtotime_hour_start = strtotime($nd_spt_hour_start); //convert to strtotime
  $nd_spt_time_hour_start = new DateTime($nd_spt_hour_start); //declare the string as time()
  $nd_spt_time_hour_start_format = date_format($nd_spt_time_hour_start, $nd_spt_format_time); //set the format
  
  //end hour
  $nd_spt_hour_end = $nd_spt_hours[1]; //recover hour from array
  $nd_spt_strtotime_hour_end = strtotime($nd_spt_hour_end); //convert to strtotime
  $nd_spt_time_hour_end = new DateTime($nd_spt_hour_end); //declare the string as time()
  $nd_spt_time_hour_end_format = date_format($nd_spt_time_hour_end, $nd_spt_format_time); //set the format


  //get qnt slots to create
  $nd_spt_slots_times_qnt = ($nd_spt_strtotime_hour_end-$nd_spt_strtotime_hour_start)/60/$nd_spt_slot_interval;
  $nd_spt_strtotime_hour_new_time = $nd_spt_time_hour_start_format;
  //start cicle




  //check if default date is closed
  if ( nd_spt_close_day($nd_spt_date_normal_format) == 1 OR $nd_spt_get_day_hours == '' ) {
      
    //close
    $nd_spt_get_slot_times .= '
    <div class="nd_spt_section nd_spt_all_time_slots_single">
      <p>'.__('Our structure is closed, please change the date to select an available time and proceed with the reservation.','nd-sports-booking').'</p>
      <input readonly class="nd_spt_display_none_important" type="text" name="nd_spt_time" id="nd_spt_time" value="">
    </div>


    <script type="text/javascript">
     
      jQuery(document).ready(function() {

        jQuery( function ( $ ) {

             $("#nd_spt_btn_go_to_booking").addClass("nd_spt_display_none_important"); 
          
        });

      });

    </script>


    ';


  }else{
      
    //open
    $nd_spt_get_slot_times .= '
    <div class="nd_spt_section nd_spt_all_time_slots_single">';

      $nd_spt_ava_all = 0;
      for ($i = 0; $i <= $nd_spt_slots_times_qnt-1; $i++) {

        //first slot
        if ($i == 0) { 

          if ( nd_spt_get_availability($nd_spt_date_normal_format,$nd_spt_time_hour_start_format,$nd_spt_players,$nd_spt_sport_id) == 0 ){
            $nd_spt_ava_all = 1;

            //convert only for visual
            $nd_spt_time_hour_start_format_new = new DateTime($nd_spt_time_hour_start_format);
            $nd_spt_time_hour_start_format_visual = date_format($nd_spt_time_hour_start_format_new, get_option('time_format'));

            $nd_spt_get_slot_times .= '<li class="nd_spt_display_inline_block"><p class="nd_spt_margin_0 nd_spt_padding_0 nd_spt_bg_color_ccc nd_spt_margin_right_10 nd_spt_time nd_spt_bg_color_blue" data-time="'.$nd_spt_time_hour_start_format.'">'.$nd_spt_time_hour_start_format_visual.'</p></li>';
          }

        }

        //increment
        $nd_spt_strtotime_hour_new_time = date($nd_spt_format_time, strtotime('+'.$nd_spt_slot_interval.' minutes', strtotime($nd_spt_strtotime_hour_new_time))); //add minutes slot to start time

        //convert only for visual
        $nd_spt_strtotime_hour_new_time_new = new DateTime($nd_spt_strtotime_hour_new_time);
        $nd_spt_strtotime_hour_new_time_visual = date_format($nd_spt_strtotime_hour_new_time_new, get_option('time_format'));

        //other slots
        if ( nd_spt_get_availability($nd_spt_date_normal_format,$nd_spt_strtotime_hour_new_time,$nd_spt_players,$nd_spt_sport_id) == 0 ) {
          $nd_spt_ava_all = 1;
          $nd_spt_get_slot_times .= '<li class="nd_spt_display_inline_block"><p class="nd_spt_margin_0 nd_spt_padding_0 nd_spt_bg_color_ccc nd_spt_margin_right_10 nd_spt_time" data-time="'.$nd_spt_strtotime_hour_new_time.'">'.$nd_spt_strtotime_hour_new_time_visual.'</p></li>';
        }

      }


      //START if
      if ( $nd_spt_ava_all == 0 ){
        $nd_spt_get_slot_times .= '
        <p>'.__('Our structure is full, please change the date to select an available time and proceed with the reservation.','nd-sports-booking').'</p>

        <div class="nd_spt_section nd_spt_height_20"></div>
        <input readonly class="nd_spt_display_none_important" type="text" name="nd_spt_time" id="nd_spt_time" value="">

      </div>


      <script>
        jQuery(document).ready(function() {

          jQuery( function ( $ ) {

               $("#nd_spt_btn_go_to_booking").addClass("nd_spt_display_none_important"); 
            
          });

        });
      </script>

      '; 


      //START inline script
      $nd_spt_bat_shortcode_full_code = '

        jQuery(document).ready(function() {

          jQuery( function ( $ ) {

               $("#nd_spt_btn_go_to_booking").addClass("nd_spt_display_none_important"); 
            
          });

        });
        
      ';
      wp_add_inline_script( 'nd_spt_calendar_script', $nd_spt_bat_shortcode_full_code );
      //END inline script


      }else{

        $nd_spt_get_slot_times .= '
       <div class="nd_spt_section nd_spt_height_20"></div>
        <input readonly class="nd_spt_display_none_important" type="text" name="nd_spt_time" id="nd_spt_time" value="'.$nd_spt_time_hour_start_format.'">
        </div>


        <script>
          jQuery(document).ready(function() {

            jQuery( function ( $ ) {

                 $("#nd_spt_btn_go_to_booking").removeClass("nd_spt_display_none_important"); 

                 $(".nd_spt_all_time_slots_single li:first-child p").trigger( "click" );
              
            });

          });
        </script>

        ';

        //START inline script
        $nd_spt_bat_shortcode_slots_code = '

          jQuery(document).ready(function() {

            jQuery( function ( $ ) {

                 $("#nd_spt_btn_go_to_booking").removeClass("nd_spt_display_none_important"); 

                 $(".nd_spt_all_time_slots_single li:first-child p").trigger( "click" );
              
            });

          });
          
        ';
        wp_add_inline_script( 'nd_spt_calendar_script', $nd_spt_bat_shortcode_slots_code );
        //END inline script


      }
      //END if
    

  }



  return $nd_spt_get_slot_times;

}






function nd_spt_get_next_prev_month_year($nd_spt_date,$nd_spt_month_year,$nd_spt_next_prev){

    //YYYY-mm-dd
    $nd_spt_year = substr($nd_spt_date,0,4);
    $nd_spt_month = substr($nd_spt_date,5,2);
    $nd_spt_day = substr($nd_spt_date,8,2);

    


    //START month calculate
    if ( $nd_spt_month_year == 'month' ){


      if ($nd_spt_next_prev == 'next') {

        //calculate next
        if ( $nd_spt_month == 12 ) { $nd_spt_ris = '01'; }
        else{ 
            $nd_spt_ris = $nd_spt_month + 1;
            if ( strlen($nd_spt_ris) == 1 ) {
                $nd_spt_ris = '0'.$nd_spt_ris;   
            }
        }

        return $nd_spt_ris;

      }else{

        //calculate prev
        if ( $nd_spt_month == 01 ) {
          $nd_spt_ris = 12;
        }else{
          $nd_spt_ris = $nd_spt_month - 1;
          if ( strlen($nd_spt_ris) == 1 ) {
            $nd_spt_ris = '0'.$nd_spt_ris;   
          }
        }

        return $nd_spt_ris;

      }


    }
    //END MONTH Calculate







    //START YEAR CALCULATE
    if ( $nd_spt_month_year == 'year' ){

      if ($nd_spt_next_prev == 'next') {
      
        //calculate next
        if ( $nd_spt_month == 12 ) { 
          $nd_spt_ris = $nd_spt_year + 1;
        }
        else{ 
          $nd_spt_ris = $nd_spt_year;    
        }

        return $nd_spt_ris;

      }else{

        //calculate prev
        if ( $nd_spt_month == 01 ) { 
          $nd_spt_ris = $nd_spt_year - 1;
        }
        else{ 
          $nd_spt_ris = $nd_spt_year;    
        }

        return $nd_spt_ris;

      } 

    }
    //END YEAR CALCULATE


}



function nd_spt_get_month_name($nd_spt_date){

    $nd_spt_get_month_name = date('Y-m-d', strtotime($nd_spt_date));    
    $nd_spt_get_month_name_new = new DateTime($nd_spt_get_month_name);
    $nd_spt_get_month = date_format($nd_spt_get_month_name_new,'F');
    
    return $nd_spt_get_month;

}





/* **************************************** START WORDPRESS INFORMATION **************************************** */

//function for get color profile admin
function nd_spt_get_profile_bg_color($nd_spt_color){
  
  global $_wp_admin_css_colors;
  $nd_spt_admin_color = get_user_option( 'admin_color' );
  
  $nd_spt_profile_bg_colors = $_wp_admin_css_colors[$nd_spt_admin_color]->colors; 


  if ( $nd_spt_profile_bg_colors[$nd_spt_color] == '#e5e5e5' ) {

    return '#6b6b6b';

  }else{

    return $nd_spt_profile_bg_colors[$nd_spt_color];
    
  }

  
}

/* **************************************** END WORDPRESS INFORMATION **************************************** */







/* **************************************** START DATABASE **************************************** */



//function for check if the order is already present
function nd_spt_check_if_order_is_present($nd_spt_tx){

  global $wpdb;

  $nd_spt_table_name = $wpdb->prefix . 'nd_spt_booking';

  
  //START query
  $nd_spt_order_ids = $wpdb->get_results( "SELECT id FROM $nd_spt_table_name WHERE nd_spt_tx = '$nd_spt_tx'" );

  //no results
  if ( empty($nd_spt_order_ids) ) { 

  return 0;

  }else{

  return 1;

  }

}


//function for add order in db
function nd_spt_add_booking_in_db(
  
  $nd_spt_sport,
  $nd_spt_players,
  $nd_spt_date,
  $nd_spt_time_start,
  $nd_spt_time_end,
  $nd_spt_occasion,
  $nd_spt_booking_form_name,
  $nd_spt_booking_form_surname,
  $nd_spt_booking_form_email,
  $nd_spt_booking_form_phone,
  $nd_spt_booking_form_requests,
  $nd_spt_order_type,
  $nd_spt_order_status,
  $nd_spt_deposit,
  $nd_spt_tx,
  $nd_spt_currency

) {




    //START add order if the plugin is not in dev mode
    if ( get_option('nd_spt_dev_mode') == 1 ){

      //dev mode active not insert in db

    }else{



      if ( nd_spt_check_if_order_is_present($nd_spt_tx) == 0 ) {


        global $wpdb;
        $nd_spt_table_name = $wpdb->prefix . 'nd_spt_booking';


        //START INSERT DB
        $nd_spt_add_booking = $wpdb->insert( 

        $nd_spt_table_name, 

        array( 

          'nd_spt_sport' => $nd_spt_sport,
          'nd_spt_players' => $nd_spt_players,
          'nd_spt_date' => $nd_spt_date,
          'nd_spt_time_start' => $nd_spt_time_start,
          'nd_spt_time_end' => $nd_spt_time_end,
          'nd_spt_occasion' => $nd_spt_occasion,
          'nd_spt_booking_form_name' => $nd_spt_booking_form_name,
          'nd_spt_booking_form_surname' => $nd_spt_booking_form_surname,
          'nd_spt_booking_form_email' => $nd_spt_booking_form_email,
          'nd_spt_booking_form_phone' => $nd_spt_booking_form_phone,
          'nd_spt_booking_form_requests' => $nd_spt_booking_form_requests,
          'nd_spt_order_type' => $nd_spt_order_type,
          'nd_spt_order_status' => $nd_spt_order_status,
          'nd_spt_deposit' => $nd_spt_deposit,
          'nd_spt_tx' => $nd_spt_tx,
          'nd_spt_currency' => $nd_spt_currency
        )

        );

        if ($nd_spt_add_booking){

            //order added in db
            do_action('nd_spt_reservation_added_in_db',$nd_spt_sport,$nd_spt_players,$nd_spt_date,$nd_spt_time_start,$nd_spt_time_end,$nd_spt_occasion,$nd_spt_booking_form_name,$nd_spt_booking_form_surname,$nd_spt_booking_form_email,$nd_spt_booking_form_phone,$nd_spt_booking_form_requests,$nd_spt_order_type,$nd_spt_order_status,$nd_spt_deposit,$nd_spt_tx,$nd_spt_currency);

        }else{

            $wpdb->show_errors();
            $wpdb->print_error();

        }
        //END INSERT DB

      }


    }
    //END add order if the plugin is not in dev mode


}
//end function
/* **************************************** END DATABASE **************************************** */




