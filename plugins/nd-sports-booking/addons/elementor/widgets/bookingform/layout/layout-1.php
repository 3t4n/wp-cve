<?php

//get color customizer
$nd_spt_customizer_color_dark_1 = get_option( 'nd_spt_customizer_color_dark_1', '#2d2d2d' );
$nd_spt_customizer_color_1 = get_option( 'nd_spt_customizer_color_1', '#c0a58a' );
$nd_spt_customizer_color_2 = get_option( 'nd_spt_customizer_color_2', '#b66565' );

//add style based on color
$nd_spt_search1_datep_style = '.nd_spt_bookingform_component_cal_l1.ui-datepicker {  background-color: '.$nd_spt_customizer_color_dark_1.'; }';
wp_add_inline_style( 'jquery-ui-datepicker-layout-1-css', $nd_spt_search1_datep_style );


$nd_spt_result .= '

  
  <div class="nd_spt_section nd_spt_bookingform_component">
    

    <form action="'.$bookingform_action.'" method="get">

      <div id="nd_spt_open_calendar_from" class="nd_spt_width_30_percentage nd_spt_width_100_percentage_all_iphone nd_spt_float_left nd_spt_position_relative">
        <p class="nd_spt_line_height_1 nd_spt_letter_spacing_1">'.__('DATE','nd-sports-booking').' :</p>
        <div class="nd_spt_section nd_spt_height_10"></div>
        <h4 class="nd_spt_line_height_1 nd_spt_letter_spacing_1 nd_spt_font_weight_bold">
          <span id="nd_spt_date_number_from_front">'.$nd_spt_date_number_from_front.'</span> <span id="nd_spt_date_month_from_front">'.$nd_spt_date_month_from_front.'</span>
        </h4>
        <img style="transform: rotate(90deg);" class="nd_spt_position_absolute nd_spt_top_29 nd_spt_left_100 nd_spt_cursor_pointer" alt="" width="8" src="'.$nd_spt_image.'">

        <input type="hidden" id="nd_spt_date_month_from" class="nd_spt_section" value="'.$nd_spt_date_month_from_front.'">
        <input type="hidden" id="nd_spt_date_number_from" class="nd_spt_section " value="'.date('d').'">
        
        <input class="nd_spt_section nd_spt_border_width_0_important nd_spt_padding_0_important nd_spt_height_0_important" type="text" name="nd_spt_send_date" id="nd_spt_send_date" value="'.date('Y-m-d').'" />


      </div>

      <div class="nd_spt_width_30_percentage nd_spt_width_100_percentage_all_iphone nd_spt_margin_top_20_all_iphone nd_spt_float_left nd_spt_position_relative">
        <p class="nd_spt_line_height_1 nd_spt_letter_spacing_1">'.__('PLAYERS','nd-sports-booking').' :</p>
        <div class="nd_spt_section nd_spt_height_10"></div>
        <h4 class="nd_spt_line_height_1 nd_spt_float_left nd_spt_letter_spacing_1 nd_spt_font_weight_bold nd_spt_guests_number">1</h4>
  
        
        <img style="transform: rotate(180deg);" class="nd_spt_position_absolute nd_spt_top_29 nd_spt_left_40 nd_spt_guests_decrease nd_spt_cursor_pointer" alt="" width="8" src="'.$nd_spt_image.'">
        <img class="nd_spt_position_absolute nd_spt_top_29 nd_spt_left_60 nd_spt_guests_increase nd_spt_cursor_pointer" alt="" width="8" src="'.$nd_spt_image.'">
        

        <input class="nd_spt_section nd_spt_display_none" type="number" name="nd_spt_send_players" id="nd_spt_send_players" min="1" value="'.$nd_spt_send_players.'" />
        
      </div>


      <div class="nd_spt_width_40_percentage nd_spt_width_100_percentage_all_iphone nd_spt_margin_top_20_all_iphone nd_spt_float_left">
        <input style="box-shadow: 5px 5px 0px 1px '.$nd_spt_customizer_color_2.'; background-color: '.$nd_spt_customizer_color_1.';" class="nd_spt_white_space_normal nd_spt_font_weight_bold nd_spt_letter_spacing_2 nd_spt_font_size_15 nd_spt_line_height_1_5 nd_spt_padding_10_30_important" type="submit" value="'.__('RESERVE A COURT','nd-sports-booking').'">
      </div>

    </form>

  </div>';
  



//START inline script
$nd_spt_search_comp_l1_datepicker_code = '

  jQuery(document).ready(function() {

    jQuery( function ( $ ) {

        $( "#nd_spt_send_date" ).datepicker({
          defaultDate: "+1w",
          minDate: 0,
          altField: "#nd_spt_date_month_from",
          altFormat: "M",
          firstDay: 0,
          dateFormat: "yy-mm-dd",
          monthNames: ["'.__('January','nd-sports-booking').'","'.__('February','nd-sports-booking').'","'.__('March','nd-sports-booking').'","'.__('April','nd-sports-booking').'","'.__('May','nd-sports-booking').'","'.__('June','nd-sports-booking').'", "'.__('July','nd-sports-booking').'","'.__('August','nd-sports-booking').'","'.__('September','nd-sports-booking').'","'.__('October','nd-sports-booking').'","'.__('November','nd-sports-booking').'","'.__('December','nd-sports-booking').'"],
          monthNamesShort: [ "'.__('Jan','nd-sports-booking').'", "'.__('Feb','nd-sports-booking').'", "'.__('Mar','nd-sports-booking').'", "'.__('Apr','nd-sports-booking').'", "'.__('Maj','nd-sports-booking').'", "'.__('Jun','nd-sports-booking').'", "'.__('Jul','nd-sports-booking').'", "'.__('Aug','nd-sports-booking').'", "'.__('Sep','nd-sports-booking').'", "'.__('Oct','nd-sports-booking').'", "'.__('Nov','nd-sports-booking').'", "'.__('Dec','nd-sports-booking').'" ],
          dayNamesMin: ["'.__('S','nd-sports-booking').'","'.__('M','nd-sports-booking').'","'.__('T','nd-sports-booking').'","'.__('W','nd-sports-booking').'","'.__('T','nd-sports-booking').'","'.__('F','nd-sports-booking').'", "'.__('S','nd-sports-booking').'"],
          nextText: "'.__('NEXT','nd-sports-booking').'",
          prevText: "'.__('PREV','nd-sports-booking').'",
          changeMonth: false,
          numberOfMonths: 1,
          beforeShow : function() {
            $("#ui-datepicker-div").addClass( "nd_spt_bookingform_component_cal_l1" );
          },
          onClose: function() {   
            var nd_spt_input_date_from = $( "#nd_spt_send_date" ).val();
            var nd_spt_date_number_from = nd_spt_input_date_from.substring(8, 10);
            $( "#nd_spt_date_number_from" ).val(nd_spt_date_number_from);
            $( "#nd_spt_date_number_from_front" ).text(nd_spt_date_number_from);
            var nd_spt_date_month_from = $( "#nd_spt_date_month_from" ).val();
            $( "#nd_spt_date_month_from_front" ).text(nd_spt_date_month_from);
          }
        });
        
        $("#nd_spt_open_calendar_from").click(function () {
            $("#nd_spt_send_date").datepicker("show");
        });


    });

  });

';
wp_add_inline_script( 'jquery-ui-datepicker', $nd_spt_search_comp_l1_datepicker_code );




$nd_spt_search_comp_l1_guests_code = '

  jQuery(document).ready(function() {

    jQuery( function ( $ ) {

      $(".nd_spt_guests_increase").click(function() {
        var value = $(".nd_spt_guests_number").text();

        if ( value < '.$nd_spt_max_guests.' ){
          value++;
          $(".nd_spt_guests_number").text(value);
          $("#nd_spt_send_players").val(value);
        }
        
      }); 

      $(".nd_spt_guests_decrease").click(function() {
        var value = $(".nd_spt_guests_number").text();
        
        if ( value > 1 ) {
          value--;
          $(".nd_spt_guests_number").text(value);
          $("#nd_spt_send_players").val(value);
        }
        
      }); 
      
    });

  });

';
wp_add_inline_script( 'jquery-ui-datepicker', $nd_spt_search_comp_l1_guests_code );
//END inline script

