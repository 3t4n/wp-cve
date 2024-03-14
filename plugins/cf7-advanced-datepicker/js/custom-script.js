jQuery(document).ready(function($) {
  $('.cf7-adv-datepicker').datepicker({
     autoclose: true,
     showAnim: setting.effect,
     changeMonth: setting.monyearmenu,
     changeYear: setting.monyearmenu,
     showWeek: setting.showWeek,	 	 minDate: setting.date,
  });


  //verion 1.0 fail-safe
  $('#cf7-adv-datepicker').datepicker({
     autoclose: true,
     showAnim: setting.effect,
     changeMonth: setting.monyearmenu,
     changeYear: setting.monyearmenu,
     showWeek: setting.showWeek,	 minDate: setting.date,
  });

});