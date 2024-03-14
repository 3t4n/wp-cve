//START function
function nd_spt_import_settings(){

  //variables
  var nd_spt_value_import_settings = jQuery( "#nd_spt_import_settings").val();

  //empty result div
  jQuery( "#nd_spt_import_settings_result_container").empty();

  //START post method
  jQuery.get(
    
  
    //ajax
    nd_spt_my_vars_import_settings.nd_spt_ajaxurl_import_settings,
    {
      action : 'nd_spt_import_settings_php_function',         
      nd_spt_value_import_settings: nd_spt_value_import_settings,
      nd_spt_import_settings_security : nd_spt_my_vars_import_settings.nd_spt_ajaxnonce_import_settings
    },
    //end ajax


    //START success
    function( nd_spt_import_settings_result ) {
    
      jQuery( "#nd_spt_import_settings").val('');
      jQuery( "#nd_spt_import_settings_result_container").append(nd_spt_import_settings_result);

    }
    //END
  

  );
  //END

  
}
//END function
