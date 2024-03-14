(function() {
  var widget_id = "themeidol-dataandtime";

  jQuery(document)
    .ready(function($) {
      // Initialize color pickers in all active and inactive widgets.
      $("#widgets-right .color-picker, .inactive-sidebar .color-picker").each(
        function() {
          $(this).wpColorPicker();
        }
      );
    })
    .ajaxComplete(function(e, xhr, settings) {
      // Re-initialize the color pickers when settings are saved.
      if (settings.data.search("action=save-widget") != -1 &&
        settings.data.search("id_base=" + widget_id) != -1) {
        jQuery("#widgets-right .color-picker, .inactive-sidebar .color-picker").each(
          function() {
            jQuery(this).wpColorPicker();
        });
      }
    });
})();