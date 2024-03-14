var intel_map_init = intel_map_init || [];

(function ($) {
  $( document ).ready(function() {
    return;
    for(var i = 0; i < intel_map_init.length; i++) {
      google.maps.event.addDomListener(window, 'load', intel_map_init[i]);
    }
  });
})(jQuery);