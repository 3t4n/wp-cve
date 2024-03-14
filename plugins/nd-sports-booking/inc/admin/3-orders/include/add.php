<?php


add_action('admin_menu','nd_spt_add_settings_menu_add_orders');
function nd_spt_add_settings_menu_add_orders(){

  add_submenu_page( 'nd-sports-booking-settings','Add Bookings', __('Add New Booking','nd-sports-booking'), 'manage_options', 'nd-sports-booking-settings-add-orders', 'nd_spt_settings_menu_add_orders' );

}


function nd_spt_settings_menu_add_orders() { ?>


  <?php if ( isset($_POST['nd_spt_add_order_page']) ) { ?>

    <?php

    //get datas
    $nd_spt_sport = sanitize_text_field($_POST['nd_spt_sport']);
    $nd_spt_players = sanitize_text_field($_POST['nd_spt_players']);
    $nd_spt_date = sanitize_text_field($_POST['nd_spt_date']);
    $nd_spt_time_start = sanitize_text_field($_POST['nd_spt_time_start']);
    $nd_spt_occasion = sanitize_text_field($_POST['nd_spt_occasion']);
    $nd_spt_booking_form_name = sanitize_text_field($_POST['nd_spt_booking_form_name']);
    $nd_spt_booking_form_surname = sanitize_text_field($_POST['nd_spt_booking_form_surname']);
    $nd_spt_booking_form_email = sanitize_email($_POST['nd_spt_booking_form_email']);
    $nd_spt_booking_form_phone = sanitize_text_field($_POST['nd_spt_booking_form_phone']);
    $nd_spt_booking_form_requests = sanitize_text_field($_POST['nd_spt_booking_form_requests']);
    $nd_spt_order_type = sanitize_text_field($_POST['nd_spt_order_type']);
    $nd_spt_order_status = sanitize_text_field($_POST['nd_spt_order_status']);
    $nd_spt_deposit = sanitize_text_field($_POST['nd_spt_deposit']);
    $nd_spt_tx = rand(100000000,999999999);
    $nd_spt_currency = sanitize_text_field($_POST['nd_spt_currency']);

    //calculate end time
    $nd_spt_booking_duration = get_option('nd_spt_booking_duration');
    $nd_spt_booking_duration_insert = $nd_spt_booking_duration-1;
    $nd_spt_time_end = date("G:i:s", strtotime('+'.$nd_spt_booking_duration_insert.' minutes', strtotime($_POST['nd_spt_time_start'])));

    
    //insert order in db
    nd_spt_add_booking_in_db(
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
    );

    ?>


    <style>
      .update-nag { display:none; } 
    </style>


    <div style="margin-top:20px;" id="setting-error-settings_updated" class="updated settings-error notice is-dismissible nd_spt_margin_left_0_important nd_spt_margin_bottom_20_important"> 
      <p>
        <strong><?php _e('Booking Added','nd-sports-booking'); ?></strong>
      </p>
      <button type="button" class="notice-dismiss">
        <span class="screen-reader-text"><?php _e('Dismiss this notice.','nd-sports-booking'); ?></span>
      </button>
    </div>




  <?php }else{ ?>


    <?php

      //ajax results
      $nd_spt_add_order_val_params = array(
        'nd_spt_ajaxurl_add_order_val' => admin_url('admin-ajax.php'),
        'nd_spt_ajaxnonce_add_order_val' => wp_create_nonce('nd_spt_add_order_val_nonce'),
      );

      wp_enqueue_script( 'nd_spt_add_order_val', esc_url( plugins_url( 'js/nd_spt_add_order_validation.js', __FILE__ ) ), array( 'jquery' ) ); 
      wp_localize_script( 'nd_spt_add_order_val', 'nd_spt_my_vars_add_order_val', $nd_spt_add_order_val_params ); 

    ?>


    <style>
    .nd_spt_validation_errors{
      background-color: #cb4a21;
      float: left;
      color: #fff;
    }
    .nd_spt_validation_errors span{
      padding: 2px 5px;
      display: inline-block;
    }

    #nd_spt_add_order_check_availability_btn{
      background: #32373d;
      border-color: #24282e #24282e #24282e;
      box-shadow: 0 1px 0 #32373d;
      text-shadow: 0 -1px 1px #24282e, 1px 0 1px #24282e, 0 1px 1px #32373d, -1px 0 1px #24282e;  
    }

    </style>


    <div class="nd_spt_section nd_spt_padding_right_20 nd_spt_padding_left_2 nd_spt_box_sizing_border_box nd_spt_margin_top_25 ">

      <form style="max-width: 800px;" class="nd_spt_float_left" method="POST">

        <div class="nd_spt_section">

          <input type="hidden" name="nd_spt_add_order_page" value="1">

          <!--1-->
          <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Main Informations','nd-sports-booking') ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Booking datas','nd-sports-booking') ?></p>
          </div>
          <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            
            <select id="nd_spt_sport" class="nd_spt_width_100_percentage" name="nd_spt_sport" id="">
              <?php 

                $nd_spt_sports_args = array( 'posts_per_page' => -1, 'post_type'=> 'nd_spt_cpt_1' );
                $nd_spt_sports = get_posts($nd_spt_sports_args); 

                ?>
              <?php foreach ($nd_spt_sports as $nd_spt_sport) : ?>
                  <option value="<?php echo esc_attr($nd_spt_sport->ID); ?>">
                      <?php echo esc_html($nd_spt_sport->post_title); ?>
                  </option>
              <?php endforeach; ?>
            </select>

            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><strong><?php _e('Sport','nd-sports-booking') ?></strong></p>
            <div class="nd_spt_section nd_spt_height_20"></div>
            <div class="nd_spt_section nd_spt_height_10"></div>

            
            <div style="padding-right:10px;" class="nd_spt_float_left nd_spt_width_50_percentage nd_spt_box_sizing_border_box nd_spt_date">
              <input id="nd_spt_date" class="nd_spt_width_100_percentage" type="text" name="nd_spt_date" value="">
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><strong><?php _e('Date ( YYYY-MM-DD )','nd-sports-booking') ?> *</strong></p>
              <div class="nd_spt_validation_errors"></div>
            </div>
            <div style="padding-left:10px;" class="nd_spt_float_left nd_spt_width_50_percentage nd_spt_box_sizing_border_box nd_spt_players">
              <input id="nd_spt_players" class="nd_spt_width_100_percentage" type="number" name="nd_spt_players" value="">
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><strong><?php _e('Players','nd-sports-booking') ?> *</strong></p>
              <div class="nd_spt_validation_errors"></div>
            </div>

            <div class="nd_spt_section nd_spt_height_20"></div>
            <div class="nd_spt_section nd_spt_height_10"></div>

            <div class="nd_spt_float_left nd_spt_width_100_percentage nd_spt_box_sizing_border_box nd_spt_time_start">
              

              <select class="nd_spt_width_100_percentage" name="nd_spt_time_start">
                
                  <?php

                    $nd_spt_exception_sols = array('00:00:00','00:30:00','01:00:00','01:30:00','02:00:00','02:30:00','03:00:00','03:30:00','04:00:00','04:30:00','05:00:00','05:30:00','06:00:00','06:30:00','07:00:00','07:30:00','08:00:00','08:30:00','09:00:00','09:30:00','10:00:00','10:30:00','11:00:00','11:30:00','12:00:00','12:30:00','13:00:00','13:30:00','14:00:00','14:30:00','15:00:00','15:30:00','16:00:00','16:30:00','17:00:00','17:30:00','18:00:00','18:30:00','19:00:00','19:30:00','20:00:00','20:30:00','21:00:00','21:30:00','22:00:00','22:30:00','23:00:00','23:30:00');

                    foreach ($nd_spt_exception_sols as $nd_spt_exceptions_sol) : ?>

                      <option value="<?php echo esc_attr($nd_spt_exceptions_sol); ?>"><?php echo esc_html($nd_spt_exceptions_sol); ?></option>

                    <?php endforeach; ?>
              
              </select>


              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><strong><?php _e('Time','nd-sports-booking') ?></strong></p>
            
            </div>


            
            


            


        </div>
        <!--END 1-->

        <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>

        
        <!--2-->
          <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Player Datas','nd-sports-booking') ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Main details','nd-sports-booking') ?></p>
          </div>
          <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            

            <div style="padding-right:10px;" class="nd_spt_float_left nd_spt_width_50_percentage nd_spt_box_sizing_border_box nd_spt_booking_form_name">
              <input id="nd_spt_booking_form_name" class="nd_spt_width_100_percentage" type="text" name="nd_spt_booking_form_name" value="">
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><strong><?php _e('Name','nd-sports-booking') ?> *</strong></p>
              <div class="nd_spt_validation_errors"></div>
            </div>
            <div style="padding-left:10px;" class="nd_spt_float_left nd_spt_width_50_percentage nd_spt_box_sizing_border_box nd_spt_booking_form_surname">
              <input id="nd_spt_booking_form_surname" class="nd_spt_width_100_percentage" type="text" name="nd_spt_booking_form_surname" value="">
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><strong><?php _e('Surname','nd-sports-booking') ?> *</strong></p>
              <div class="nd_spt_validation_errors"></div>
            </div>

            <div class="nd_spt_section nd_spt_height_20"></div>
            <div class="nd_spt_section nd_spt_height_10"></div>

            <div style="padding-right:10px;" class="nd_spt_float_left nd_spt_width_50_percentage nd_spt_box_sizing_border_box nd_spt_booking_form_email">
              <input id="nd_spt_booking_form_email" class="nd_spt_width_100_percentage" type="text" name="nd_spt_booking_form_email" value="">
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><strong><?php _e('Email','nd-sports-booking') ?> *</strong></p>
              <div class="nd_spt_validation_errors"></div>
            </div>
            <div style="padding-left:10px;" class="nd_spt_float_left nd_spt_width_50_percentage nd_spt_box_sizing_border_box">
              <input class="nd_spt_width_100_percentage" type="number" name="nd_spt_booking_form_phone" value="">
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><strong><?php _e('Phone','nd-sports-booking') ?></strong></p>
            </div>

        </div>
        <!--END 2-->


        <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>


        <!--3-->
          <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Booking Details','nd-sports-booking') ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Additional Informations','nd-sports-booking') ?></p>
          </div>
          <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
              
              
              <select class="nd_spt_width_100_percentage" name="nd_spt_occasion" id="">

                <?php 
                  
                  $nd_spt_occasions = get_option('nd_spt_occasions');
                  
                  if ( $nd_spt_occasions == '' ) {
                    $nd_spt_occasion_value = 0;  
                  }else { 
                    $nd_spt_occasions_array = explode(',', $nd_spt_occasions ); 
                  }

                  for ( $nd_spt_occasions_array_i = 0; $nd_spt_occasions_array_i < count($nd_spt_occasions_array); $nd_spt_occasions_array_i++) { ?>

                    <option <?php if ( $nd_spt_occasions_array_i == $nd_spt_order->nd_spt_occasion ) { ?> 'selected="selected"' <?php } ?> value="<?php echo esc_attr($nd_spt_occasions_array_i); ?>"><?php echo esc_html($nd_spt_occasions_array[$nd_spt_occasions_array_i]); ?></option>

                  <?php } ?>

              </select>


              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><strong><?php _e('Service','nd-sports-booking') ?></strong></p>
              <div class="nd_spt_section nd_spt_height_20"></div>
              <div class="nd_spt_section nd_spt_height_10"></div>

              <textarea id="nd_spt_booking_form_requests" rows="5" class="nd_spt_width_100_percentage" name="nd_spt_booking_form_requests"></textarea>
              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><strong><?php _e('Message','nd-sports-booking') ?></strong></p>
              <div class="nd_spt_section nd_spt_height_20"></div>
              <div class="nd_spt_section nd_spt_height_10"></div>

         
        </div>
        <!--END 3-->

        <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>

        <!--4-->
          <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Request Details','nd-sports-booking') ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Settings','nd-sports-booking') ?></p>
          </div>
          <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
          
              <select class="nd_spt_width_100_percentage" name="nd_spt_order_type" id="">
                <option value="request"><?php _e('Request','nd-sports-booking'); ?></option>
              </select>

              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><strong><?php _e('Booking Type','nd-sports-booking') ?></strong></p>
              <div class="nd_spt_section nd_spt_height_20"></div>
              <div class="nd_spt_section nd_spt_height_10"></div>


              <select class="nd_spt_width_100_percentage" name="nd_spt_order_status" id="">
                <option value="pending"><?php _e('Pending','nd-sports-booking'); ?></option>
                <option value="confirmed"><?php _e('Confirmed','nd-sports-booking'); ?></option>
              </select>

              <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><strong><?php _e('Booking Status','nd-sports-booking') ?></strong></p>
              <div class="nd_spt_section nd_spt_height_20"></div>
              <div class="nd_spt_section nd_spt_height_10"></div>

         
        </div>
        <!--END 4-->



        <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>

        <!--5-->
          <div class="nd_spt_width_40_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
            <h2 class="nd_spt_section nd_spt_margin_0"><?php _e('Deposit Details','nd-sports-booking') ?></h2>
            <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><?php _e('Settings','nd-sports-booking') ?></p>
          </div>
          <div class="nd_spt_width_60_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">
          
              <div style="padding-right:10px;" class="nd_spt_float_left nd_spt_width_50_percentage nd_spt_box_sizing_border_box nd_spt_deposit">
                <input id="nd_spt_deposit" class="nd_spt_width_100_percentage" type="number" name="nd_spt_deposit" value="">
                <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><strong><?php _e('Amount ( Only numbers )','nd-sports-booking') ?></strong></p>
              </div>

              <div style="padding-left:10px;" class="nd_spt_float_left nd_spt_width_50_percentage nd_spt_box_sizing_border_box nd_spt_currency">
                <input id="nd_spt_currency" class="nd_spt_width_100_percentage" type="text" name="nd_spt_currency" value="">
                <p class="nd_spt_color_666666 nd_spt_section nd_spt_margin_0 nd_spt_margin_top_10"><strong><?php _e('Currency','nd-sports-booking') ?></strong></p>
              </div>

        </div>
        <!--END 5-->


        <div class="nd_spt_section nd_spt_height_1 nd_spt_background_color_E7E7E7 nd_spt_margin_top_10 nd_spt_margin_bottom_10"></div>

        <div class="nd_spt_width_100_percentage nd_spt_padding_20 nd_spt_box_sizing_border_box nd_spt_float_left">

          <a id="nd_spt_add_order_check_availability_btn" onclick="nd_spt_check_order_val()" class="button button-primary"><?php _e('CHECK BOOKING','nd-sports-booking'); ?></a>

          <input id="nd_spt_add_order_add_reservation_btn" class="button button-primary nd_spt_display_none_important" type="submit" name="" value="<?php _e('ADD BOOKING','nd-sports-booking') ?>">

        </div>

        </div>

      </form>

    </div>

  <?php } ?>


<?php } 




//START nd_spt_import_settings_php_function for AJAX
function nd_spt_add_order_validation_php_function() {

  check_ajax_referer( 'nd_spt_add_order_val_nonce', 'nd_spt_add_order_val_security' );

  //validate if email is valid
  function nd_spt_is_email($nd_spt_email){

    if (filter_var($nd_spt_email, FILTER_VALIDATE_EMAIL)) {
      return 1;  
    } else {
      return 0;
    }


  }


  //declare
  $nd_spt_string_result = '';

  //recover datas
  $nd_spt_date = sanitize_text_field($_GET['nd_spt_date']);
  $nd_spt_players = sanitize_text_field($_GET['nd_spt_players']);
  $nd_spt_booking_form_name = sanitize_text_field($_GET['nd_spt_booking_form_name']);
  $nd_spt_booking_form_surname = sanitize_text_field($_GET['nd_spt_booking_form_surname']);
  $nd_spt_booking_form_email = sanitize_email($_GET['nd_spt_booking_form_email']);
  
  
  //date
  if ( $nd_spt_date == '' ){ 
    
    $nd_spt_result_date = 0; 
    $nd_spt_string_result .= '<span>'.__('Date is mandatory','nd-sports-booking').'</span>[divider]'; 
  
  }else{

    $nd_spt_result_date = 1; 
    $nd_spt_string_result .= ' [divider]'; 

  }


  //players
  if ( $nd_spt_players == '' ){ 
    
    $nd_spt_result_players = 0; 
    $nd_spt_string_result .= '<span>'.__('players is mandatory','nd-sports-booking').'</span>[divider]'; 
  
  }else{

    $nd_spt_result_players = 1; 
    $nd_spt_string_result .= ' [divider]'; 

  }


  //name
  if ( $nd_spt_booking_form_name == '' ){ 
    
    $nd_spt_result_name = 0; 
    $nd_spt_string_result .= '<span>'.__('Name is mandatory','nd-sports-booking').'</span>[divider]'; 
  
  }else{

    $nd_spt_result_name = 1; 
    $nd_spt_string_result .= ' [divider]'; 

  }

  //surname
  if ( $nd_spt_booking_form_surname == '' ){ 
    
    $nd_spt_result_surname = 0; 
    $nd_spt_string_result .= '<span>'.__('Surname is mandatory','nd-sports-booking').'</span>[divider]'; 
  
  }else{

    $nd_spt_result_surname = 1; 
    $nd_spt_string_result .= ' [divider]'; 

  }


  //email
  if ( $nd_spt_booking_form_email == '' ) {

    $nd_spt_result_email = 0; 

    $nd_spt_string_result .= '<span>'.__('Email is mandatory','nd-sports-booking').'</span>[divider]';    

  }elseif ( nd_spt_is_email($nd_spt_booking_form_email) == 0 ) {

    $nd_spt_result_email = 0; 

    $nd_spt_string_result .= '<span>'.__('Email not valid','nd-sports-booking').'</span>[divider]';  

  }else{

    $nd_spt_result_email = 1;

    $nd_spt_string_result .= ' [divider]'; 

  }





  //Determiante the final result
  if ( $nd_spt_result_date == 1 AND $nd_spt_result_players == 1 AND $nd_spt_result_name == 1 AND $nd_spt_result_surname == 1 AND $nd_spt_result_email == 1 )
  { echo esc_attr(1); }else{

    $nd_spt_allowed_html = [
        'span'      => [
          'class' => [],
        ],
    ];

    echo wp_kses( $nd_spt_string_result, $nd_spt_allowed_html );

  }


  die();


}
add_action( 'wp_ajax_nd_spt_add_order_validation_php_function', 'nd_spt_add_order_validation_php_function' );
//END