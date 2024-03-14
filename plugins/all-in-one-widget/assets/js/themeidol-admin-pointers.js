/*
 * All-in-one Widget
 * (c) Themeidol, 2016 - 2017
 */

 
jQuery(document).ready(function($){
  if (typeof themeidol_pointers  == 'undefined') {
    return;
  }

  $.each(themeidol_pointers, function(index, pointer) {
    if (index.charAt(0) == '_') {
      return true;
    }
    $(pointer.target).pointer({
        content: '<h3>All-in-one Widget</h3><p>' + pointer.content + '</p>',
        position: {
            edge: pointer.edge,
            align: pointer.align
        },
        width: 320,
        close: function() {
                $.post(ajaxurl, {
                    pointer: index,
                    _ajax_nonce: themeidol_pointers._nonce_dismiss_pointer,
                    action: 'themeidol_dismiss_pointer'
                });
        }
      }).pointer('open');
  });
});
