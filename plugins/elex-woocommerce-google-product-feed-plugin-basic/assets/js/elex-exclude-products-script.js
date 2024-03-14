function elex_exclude_search_name_fun() {
  var search = jQuery('#elex_exclude_search_name').val();
  window.location.replace(window.location.href+"&search="+search+"&feed_page=1");
}