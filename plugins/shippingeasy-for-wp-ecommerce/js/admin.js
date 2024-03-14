jQuery(document).ready(function($)
{
  if(($("#se-rate-settings").length != 0))
  {
    //set title for shippingeasy setings since wpec does not seem to do it
    if($(".gateway_settings .postbox .hndle").length != 0)
    {
      $(".gateway_settings .postbox .hndle").html('ShippingEasy Quote Settings');
    }
    // in WPec version 3.8.8 class 'gateway_settings' removed
    if($("#wpsc-shipping-module-settings .postbox .hndle").length != 0)
    {
      $("#wpsc-shipping-module-settings .postbox .hndle").html('ShippingEasy Quote Settings');
    }

    if($(".gateway_settings").length != 0)
    {
      //add reset to defaults button and set width of settings panel WPec version < 3.8.8
      $('.gateway_settings .postbox .submit input').after('<input id="reset-rate-settings" name="reset_to_defaults" type="submit" value="Reset to Defaults" />');
      $('.gateway_settings').css('width', '580px');
    }
    if($("#wpsc-shipping-module-settings").length != 0)
    {
      //add reset to defaults button and set width of settings panel
      $('#wpsc-shipping-module-settings .postbox .submit input').after('<input id="reset-rate-settings" name="reset_to_defaults" type="submit" value="Reset to Defaults" />');
      $('#wpsc-shipping-module-settings').css('width', '580px');
    }

    //select all services in subsections
    $("#se-rate-settings .se_select_all").live("click", function()
    {
      checkboxes = $(this).parent().parent().next('.shippingeasy-rate-settings').children('label').children('input');
      $.each(checkboxes, function()
      {
        $(this).attr('checked', true);
      });
    });
    //deselect all services in subsections
    $("#se-rate-settings .se_select_none").live("click", function()
    {
      checkboxes = $(this).parent().parent().next('.shippingeasy-rate-settings').children('label').children('input');
      $.each(checkboxes, function()
      {
        $(this).attr('checked', false);
      });
    });
  }

});
