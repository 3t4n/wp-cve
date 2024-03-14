jQuery(document).ready(function () {

  keep_theme_tab();

  jQuery(".general_bg_color, .general_border_color, .general_font_color").wpColorPicker();
  jQuery(".input_bg_color, .input_color, .input_border_color").wpColorPicker();
  jQuery(".textarea_bg_color, .textarea_color, .textarea_border_color").wpColorPicker();
  jQuery(".drodown_bg_color, .drodown_color, .drodown_border_color").wpColorPicker();
  jQuery(".radio_bg_color, .radio_checked_bg_color, .radio_border_color").wpColorPicker();
  jQuery(".checkbox_bg_color, .checkbox_checked_bg_color, .checkbox_border_color").wpColorPicker();
  jQuery(".button_color, .button_bg_color, .button_border_color, .button_hover_bg_color, .button_hover_color").wpColorPicker();
  jQuery(".pagination_color, .pagination_bg_color, .pagination_border_color, .pagination_hover_bg_color, .pagination_hover_color").wpColorPicker();

  jQuery("#cf7b-save-button").on("click", function() {
    jQuery("#cf7b-theme-form").submit();
  })
});

function keep_theme_tab() {
  var theme_id = jQuery("#cf7b-theme-id").val();
  var index = 'cf7b-theme'+theme_id+'-active-tab';
  //  Define friendly data store name
  var dataStore = window.sessionStorage;
  var oldIndex = 0;
  //  Start magic!
  try {
    // getter: Fetch previous value
    oldIndex = dataStore.getItem(index);
  } catch(e) {}

  jQuery( "#tabs" ).tabs({
    active: oldIndex,
    activate: function(event, ui) {
      //  Get future value
      var newIndex = ui.newTab.parent().children().index(ui.newTab);
      //  Set future value
      try {
        dataStore.setItem( index, newIndex );
      } catch(e) {}
    }
  });
}