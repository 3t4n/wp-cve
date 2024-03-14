//START function
function nd_spt_check_order_val(){

  //variables
  var nd_spt_date = jQuery( "#nd_spt_date").val();
  var nd_spt_players = jQuery( "#nd_spt_players").val();
  var nd_spt_booking_form_name = jQuery( "#nd_spt_booking_form_name").val();
  var nd_spt_booking_form_surname = jQuery( "#nd_spt_booking_form_surname").val();
  var nd_spt_booking_form_email = jQuery( "#nd_spt_booking_form_email").val();

  //empty result div
  jQuery( "#nd_spt_import_settings_result_container").empty();

  //START post method
  jQuery.get(
    
  
    //ajax
    nd_spt_my_vars_add_order_val.nd_spt_ajaxurl_add_order_val,
    {
      action : 'nd_spt_add_order_validation_php_function',         
      nd_spt_date: nd_spt_date,
      nd_spt_players: nd_spt_players,
      nd_spt_booking_form_name: nd_spt_booking_form_name,
      nd_spt_booking_form_surname: nd_spt_booking_form_surname,
      nd_spt_booking_form_email: nd_spt_booking_form_email,
      nd_spt_add_order_val_security : nd_spt_my_vars_add_order_val.nd_spt_ajaxnonce_add_order_val,
    },
    //end ajax


    //START success
    function( nd_spt_add_order_val_result ) {
    

      if ( nd_spt_add_order_val_result == 1 ){

          jQuery( ".nd_spt_validation_errors").empty();

          jQuery("#nd_spt_add_order_check_availability_btn").addClass("nd_spt_display_none_important");
          jQuery("#nd_spt_add_order_add_reservation_btn").removeClass("nd_spt_display_none_important");
          
       }else{
          
          jQuery( ".nd_spt_validation_errors").empty();

          //split all result
          var nd_spt_errors_validation = nd_spt_add_order_val_result.split("[divider]");
          
          //declare variables
          var nd_spt_error_validation_date = nd_spt_errors_validation[0];
          var nd_spt_error_validation_players = nd_spt_errors_validation[1];
          var nd_spt_error_validation_name = nd_spt_errors_validation[2];
          var nd_spt_error_validation_surname = nd_spt_errors_validation[3];
          var nd_spt_error_validation_email = nd_spt_errors_validation[4];

          jQuery( ".nd_spt_date .nd_spt_validation_errors").append(nd_spt_error_validation_date);
          jQuery( ".nd_spt_players .nd_spt_validation_errors").append(nd_spt_error_validation_players);
          jQuery( ".nd_spt_booking_form_name .nd_spt_validation_errors").append(nd_spt_error_validation_name);
          jQuery( ".nd_spt_booking_form_surname .nd_spt_validation_errors").append(nd_spt_error_validation_surname);
          jQuery( ".nd_spt_booking_form_email .nd_spt_validation_errors").append(nd_spt_error_validation_email);

       }


    }
    //END
  

  );
  //END

  
}
//END function
