//START function
function nd_spt_update_timing(nd_spt_date_select){

  //add layer for avoid double click
  jQuery( "#nd_spt_component_container" ).append( "<div id='nd_spt_all_time_slots_single_layer'></div>" );

  var nd_spt_player_select = jQuery( "#nd_spt_players").val();
  var nd_spt_sport = jQuery( "#nd_spt_sport").val();

  //START post method
  jQuery.get(
    
    //ajax
    nd_spt_my_vars_calendar.nd_spt_ajaxurl,
    {
      action : 'nd_spt_get_timing_php',
      nd_spt_date_select: nd_spt_date_select,
      nd_spt_player_select: nd_spt_player_select,
      nd_spt_sport: nd_spt_sport,
      nd_spt_update_timing_security : nd_spt_my_vars_calendar.nd_spt_ajaxnonce,
    },
    //end ajax

    //START success
    function( nd_spt_get_timing_result ) {

      jQuery( ".nd_spt_all_time_slots_single" ).remove();
      jQuery( "#nd_spt_all_time_slots_container" ).append(nd_spt_get_timing_result);
      jQuery( ".nd_spt_all_time_slots_single li:first-child p" ).trigger( "click" );

      //remove layer
      jQuery( "#nd_spt_all_time_slots_single_layer" ).remove();  

      //update time select on click
      jQuery(".nd_spt_time").click(function() {

        jQuery(".nd_spt_time").removeClass("nd_spt_bg_color_blue");
        var nd_spt_calendar_time_select = jQuery(this).attr("data-time");
        jQuery(this).addClass("nd_spt_bg_color_blue");

        jQuery("#nd_spt_time").val(nd_spt_calendar_time_select);

      });

    }
    //END

    

  );
  //END


}




function nd_spt_add_to_db(){

  //add layer for avoid double click
  jQuery( "#nd_spt_component_container" ).append( "<div id='nd_spt_component_container_layer'></div>" );

  //add loader
  var nd_spt_sorting_result_loader = jQuery('<div id="nd_spt_sorting_result_loader"></div>').hide();
  jQuery( "#nd_spt_component_container" ).append(nd_spt_sorting_result_loader);
  nd_spt_sorting_result_loader.fadeIn('slow');


  var nd_spt_sport = jQuery( "#nd_spt_sport").val();
  var nd_spt_players = jQuery( "#nd_spt_players").val();
  var nd_spt_date = jQuery( "#nd_spt_date").val();
  var nd_spt_time = jQuery( "#nd_spt_time").val();
  var nd_spt_occasion = jQuery( "#nd_spt_occasion").val();
  var nd_spt_booking_form_name = jQuery( "#nd_spt_booking_form_name").val();
  var nd_spt_booking_form_surname = jQuery( "#nd_spt_booking_form_surname").val();
  var nd_spt_booking_form_email = jQuery( "#nd_spt_booking_form_email").val();
  var nd_spt_booking_form_phone = jQuery( "#nd_spt_booking_form_phone").val();
  var nd_spt_booking_form_requests = jQuery( "#nd_spt_booking_form_requests").val();
  var nd_spt_order_type = jQuery( "#nd_spt_order_type").val();
  var nd_spt_order_status = jQuery( "#nd_spt_order_status").val();


  //START post method
  jQuery.get(
    
    //ajax
    nd_spt_my_vars_calendar.nd_spt_ajaxurl,
    {
      action : 'nd_spt_add_to_db_php',
      nd_spt_sport: nd_spt_sport,
      nd_spt_players: nd_spt_players,
      nd_spt_date: nd_spt_date,
      nd_spt_time: nd_spt_time,
      nd_spt_occasion: nd_spt_occasion,
      nd_spt_booking_form_name: nd_spt_booking_form_name,
      nd_spt_booking_form_surname: nd_spt_booking_form_surname,
      nd_spt_booking_form_email: nd_spt_booking_form_email,
      nd_spt_booking_form_phone: nd_spt_booking_form_phone,
      nd_spt_booking_form_requests: nd_spt_booking_form_requests,
      nd_spt_order_type: nd_spt_order_type,
      nd_spt_order_status: nd_spt_order_status,
      nd_spt_add_to_db_security : nd_spt_my_vars_calendar.nd_spt_ajaxnonce,
    },
    //end ajax

    //START success
    function( nd_spt_add_to_db_result ) {

      jQuery( ".nd_spt_booking_container_3" ).remove();
      jQuery( ".nd_spt_booking_container_all" ).append(nd_spt_add_to_db_result);


      //remove loader
      jQuery( "#nd_spt_sorting_result_loader" ).fadeOut( "slow", function() {
        jQuery( "#nd_spt_sorting_result_loader" ).remove();  
      });

      //remove layer
      jQuery( "#nd_spt_component_container_layer" ).remove();

    }
    //END

    

  );
  //END

}
















function nd_spt_go_to_checkout(){

  //add layer for avoid double click
  jQuery( "#nd_spt_component_container" ).append( "<div id='nd_spt_component_container_layer'></div>" );

  //add loader
  var nd_spt_sorting_result_loader = jQuery('<div id="nd_spt_sorting_result_loader"></div>').hide();
  jQuery( "#nd_spt_component_container" ).append(nd_spt_sorting_result_loader);
  nd_spt_sorting_result_loader.fadeIn('slow');

  var nd_spt_sport = jQuery( "#nd_spt_sport").val();
  var nd_spt_players = jQuery( "#nd_spt_players").val();
  var nd_spt_date = jQuery( "#nd_spt_date").val();
  var nd_spt_time = jQuery( "#nd_spt_time").val();
  var nd_spt_occasion = jQuery( "#nd_spt_occasion").val();
  var nd_spt_booking_form_name = jQuery( "#nd_spt_booking_form_name").val();
  var nd_spt_booking_form_surname = jQuery( "#nd_spt_booking_form_surname").val();
  var nd_spt_booking_form_email = jQuery( "#nd_spt_booking_form_email").val();
  var nd_spt_booking_form_phone = jQuery( "#nd_spt_booking_form_phone").val();
  var nd_spt_booking_form_requests = jQuery( "#nd_spt_booking_form_requests").val();
  var nd_spt_action_return = jQuery( "#nd_spt_action_return").val();


  //START post method
  jQuery.get(
    
    //ajax
    nd_spt_my_vars_calendar.nd_spt_ajaxurl,
    {
      action : 'nd_spt_checkout_php',
      nd_spt_sport: nd_spt_sport,
      nd_spt_players: nd_spt_players,
      nd_spt_date: nd_spt_date,
      nd_spt_time: nd_spt_time,
      nd_spt_occasion: nd_spt_occasion,
      nd_spt_booking_form_name: nd_spt_booking_form_name,
      nd_spt_booking_form_surname: nd_spt_booking_form_surname,
      nd_spt_booking_form_email: nd_spt_booking_form_email,
      nd_spt_booking_form_phone: nd_spt_booking_form_phone,
      nd_spt_booking_form_requests: nd_spt_booking_form_requests,
      nd_spt_action_return: nd_spt_action_return,
      nd_spt_go_to_checkout_security : nd_spt_my_vars_calendar.nd_spt_ajaxnonce,
    },
    //end ajax

    //START success
    function( nd_spt_checkout_result ) {

      jQuery( ".nd_spt_booking_container_2" ).remove();
      jQuery( ".nd_spt_booking_container_all" ).append(nd_spt_checkout_result);


      //remove loader
      jQuery( "#nd_spt_sorting_result_loader" ).fadeOut( "slow", function() {
        jQuery( "#nd_spt_sorting_result_loader" ).remove();  
      });

      //remove layer
      jQuery( "#nd_spt_component_container_layer" ).remove();
      
      //steps
      jQuery("#nd_spt_steps_container .nd_spt_single_step").removeClass("nd_spt_step_active");
      jQuery("#nd_spt_steps_container .nd_spt_step_checkout").addClass("nd_spt_step_active");


      //toogle 3
      jQuery( ".nd_spt_toogle_title_open_3" ).click(function() {
        jQuery( ".nd_spt_toogle_content_3" ).show( "slow", function() {
          jQuery( ".nd_spt_toogle_title_open_3" ).css("display","none");
          jQuery( ".nd_spt_toogle_title_close_3" ).css("display","block");
        });
      });
      jQuery( ".nd_spt_toogle_title_close_3" ).click(function() {
        jQuery( ".nd_spt_toogle_content_3" ).hide( "slow", function() {
          jQuery( ".nd_spt_toogle_title_close_3" ).css("display","none");
          jQuery( ".nd_spt_toogle_title_open_3" ).css("display","block");  
        }); 
      });

      //toogle 1
      jQuery( ".nd_spt_toogle_title_open_1" ).click(function() {
        jQuery( ".nd_spt_toogle_content_1" ).show( "slow", function() {
          jQuery( ".nd_spt_toogle_title_open_1" ).css("display","none");
          jQuery( ".nd_spt_toogle_title_close_1" ).css("display","block");
        });
      });
      jQuery( ".nd_spt_toogle_title_close_1" ).click(function() {
        jQuery( ".nd_spt_toogle_content_1" ).hide( "slow", function() {
          jQuery( ".nd_spt_toogle_title_close_1" ).css("display","none");
          jQuery( ".nd_spt_toogle_title_open_1" ).css("display","block");  
        }); 
      });


      //toogle 2
      jQuery( ".nd_spt_toogle_title_open_2" ).click(function() {
        jQuery( ".nd_spt_toogle_content_2" ).show( "slow", function() {
          jQuery( ".nd_spt_toogle_title_open_2" ).css("display","none");
          jQuery( ".nd_spt_toogle_title_close_2" ).css("display","block");
        });
      });
      jQuery( ".nd_spt_toogle_title_close_2" ).click(function() {
        jQuery( ".nd_spt_toogle_content_2" ).hide( "slow", function() {
          jQuery( ".nd_spt_toogle_title_close_2" ).css("display","none");
          jQuery( ".nd_spt_toogle_title_open_2" ).css("display","block");  
        }); 
      });

    }
    //END

    

  );
  //END

}














function nd_spt_validate_fields(){

  //variables
  var nd_spt_email = jQuery( "#nd_spt_booking_form_email").val();
  var nd_spt_name = jQuery( "#nd_spt_booking_form_name").val();
  var nd_spt_surname = jQuery( "#nd_spt_booking_form_surname").val();
  var nd_spt_message = jQuery( "#nd_spt_booking_form_requests").val();
  var nd_spt_phone = jQuery( "#nd_spt_booking_form_phone").val();

  //term
  if ( jQuery( "#nd_spt_booking_form_term").is(':checked') ) { 
    var nd_spt_term = 1;
  }else{ 
    var nd_spt_term = 0;
  }

  //START post method
  jQuery.get(
    
  
    //ajax
    nd_spt_my_vars_calendar.nd_spt_ajaxurl,
    {
      action : 'nd_spt_validate_fields_php_function',   
      nd_spt_email: nd_spt_email,
      nd_spt_name: nd_spt_name,
      nd_spt_surname: nd_spt_surname,
      nd_spt_message: nd_spt_message,
      nd_spt_phone: nd_spt_phone,
      nd_spt_term: nd_spt_term,
      nd_spt_validate_fields_security : nd_spt_my_vars_calendar.nd_spt_ajaxnonce,
    },
    //end ajax


    //START success
    function( nd_spt_validate_fields_result ) {

      //add layer for avoid double click
      jQuery( "#nd_spt_component_container" ).append( "<div id='nd_spt_all_time_slots_single_layer'></div>" );

     if ( nd_spt_validate_fields_result == 1 ){

        jQuery( ".nd_spt_validation_errors").remove();
        jQuery("#nd_spt_submit_go_to_checkout").trigger("click");
        
     }else{
        
        jQuery( ".nd_spt_validation_errors").remove();

        //split all result
        var nd_spt_errors_validation = nd_spt_validate_fields_result.split("[divider]");
        
        //declare variables
        var nd_spt_error_validation_name = nd_spt_errors_validation[0];
        var nd_spt_error_validation_surname = nd_spt_errors_validation[1];
        var nd_spt_error_validation_email = nd_spt_errors_validation[2];
        var nd_spt_error_validation_phone = nd_spt_errors_validation[3];
        var nd_spt_error_validation_message = nd_spt_errors_validation[4];
        var nd_spt_error_validation_term = nd_spt_errors_validation[5]

        jQuery( "#nd_spt_booking_form_name_container label").append(nd_spt_error_validation_name);
        jQuery( "#nd_spt_booking_form_surname_container label").append(nd_spt_error_validation_surname);
        jQuery( "#nd_spt_booking_form_email_container label").append(nd_spt_error_validation_email);
        jQuery( "#nd_spt_booking_form_phone_container label").append(nd_spt_error_validation_phone);
        jQuery( "#nd_spt_booking_form_requests_container label").append(nd_spt_error_validation_message);
        jQuery( "#nd_spt_booking_form_term_container label").append(nd_spt_error_validation_term);
     
     }

     //remove layer
      jQuery( "#nd_spt_all_time_slots_single_layer" ).remove();  

     

    }
    //END

  
  );
  //END

  
}
//END function








//START functions
function nd_spt_go_to_booking(){

  //add layer for avoid double click
  jQuery( "#nd_spt_component_container" ).append( "<div id='nd_spt_component_container_layer'></div>" );

  //add loader
  var nd_spt_sorting_result_loader = jQuery('<div id="nd_spt_sorting_result_loader"></div>').hide();
  jQuery( "#nd_spt_component_container" ).append(nd_spt_sorting_result_loader);
  nd_spt_sorting_result_loader.fadeIn('slow');

  //get all variables
  var nd_spt_sport = jQuery( "#nd_spt_sport").val();
  var nd_spt_players = jQuery( "#nd_spt_players").val();
  var nd_spt_date = jQuery( "#nd_spt_date").val();
  var nd_spt_time = jQuery( "#nd_spt_time").val();
  var nd_spt_occasion = jQuery( "#nd_spt_occasion").val();
  var nd_spt_action_return = jQuery( "#nd_spt_action_return").val();


  //START post method
  jQuery.get(
    
    //ajax
    nd_spt_my_vars_calendar.nd_spt_ajaxurl,
    {
      action : 'nd_spt_booking_php',
      nd_spt_sport : nd_spt_sport,
      nd_spt_players : nd_spt_players,
      nd_spt_date : nd_spt_date,
      nd_spt_time : nd_spt_time,
      nd_spt_occasion : nd_spt_occasion,
      nd_spt_action_return : nd_spt_action_return,
      nd_spt_go_to_booking_security : nd_spt_my_vars_calendar.nd_spt_ajaxnonce,
    },
    //end ajax

    //START success
    function( nd_spt_booking_result ) {

      jQuery( ".nd_spt_booking_container_1" ).remove();
      jQuery( ".nd_spt_booking_container_all" ).append(nd_spt_booking_result);

      //remove loader
      jQuery( "#nd_spt_sorting_result_loader" ).fadeOut( "slow", function() {
        jQuery( "#nd_spt_sorting_result_loader" ).remove();  
      });

      //remove layer
      jQuery( "#nd_spt_component_container_layer" ).remove();

      jQuery("#nd_spt_steps_container .nd_spt_single_step").removeClass("nd_spt_step_active");
      jQuery("#nd_spt_steps_container .nd_spt_step_booking").addClass("nd_spt_step_active");

    }
    //END

    

  );
  //END




}





function nd_spt_calendar(nd_spt_action){

  //add layer for avoid double click
  jQuery( "#nd_spt_component_container" ).append( "<div id='nd_spt_all_time_slots_single_layer'></div>" );

  var nd_spt_prev_month = jQuery( "#nd_spt_prev_month").val();
  var nd_spt_prev_year = jQuery( "#nd_spt_prev_year").val();
  var nd_spt_next_month = jQuery( "#nd_spt_next_month").val();
  var nd_spt_next_year = jQuery( "#nd_spt_next_year").val();
  var nd_spt_selected_date = jQuery( "#nd_spt_date").val();

  //variables passed on function
  if( nd_spt_action === 1){
    var nd_spt_month = nd_spt_prev_month;
    var nd_spt_year = nd_spt_prev_year;
  }else{
    var nd_spt_month = nd_spt_next_month;
    var nd_spt_year = nd_spt_next_year;
  }

  //START post method
  jQuery.get(
    
    //ajax
    nd_spt_my_vars_calendar.nd_spt_ajaxurl,
    {
      action : 'nd_spt_calendar_php',
      nd_spt_month : nd_spt_month,
      nd_spt_year : nd_spt_year,
      nd_spt_selected_date : nd_spt_selected_date,
      nd_spt_calendar_security : nd_spt_my_vars_calendar.nd_spt_ajaxnonce,
    },
    //end ajax

    //START success
    function( nd_spt_calendar_result ) {

      jQuery( "#nd_spt_calendar_content" ).remove();
      jQuery( "#nd_spt_calendar_container" ).append(nd_spt_calendar_result);

      //remove layer
      jQuery( "#nd_spt_all_time_slots_single_layer" ).remove();  


      //update date selection on not default month
      jQuery(".nd_spt_calendar_date").click(function() {

        jQuery(".nd_spt_calendar_date").removeClass("nd_spt_cal_active");
        var nd_spt_calendar_date_select = jQuery(this).attr("data-date");
        jQuery(this).addClass("nd_spt_cal_active");

        jQuery("#nd_spt_date").val(nd_spt_calendar_date_select);

        nd_spt_update_timing(nd_spt_calendar_date_select);
        
      });

    }
    //END

    

  );
  //END

  
}
//END function