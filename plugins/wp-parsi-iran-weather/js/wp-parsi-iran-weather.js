function submit_wppiw(){
    jQuery.post(wppiw_ajax_script.ajaxurl, jQuery("#whtrform").serialize()
    ,
    function(response_from_wppiw_action_function){
        var zero = response_from_wppiw_action_function.substr(0,response_from_wppiw_action_function.length-1);
        jQuery("#whtr").html(zero);
    });
}

jQuery(document).ready(function(){
   jQuery("#whtrcities option").click(function(){
      submit_wppiw();
   });
});