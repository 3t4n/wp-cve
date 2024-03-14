/*
 * CSMM
 * Backend GUI pointers
 * (c) Web factory Ltd, 2016 - 2021
 */

jQuery(document).ready(function($){
  if (typeof csmm_pointers  == 'undefined') {
    return;
  }

  $.each(csmm_pointers, function(index, pointer) {
    if (index.charAt(0) == '_') {
      return true;
    }
    $(pointer.target).pointer({
        content: '<h3>Minimal Coming Soon &amp; Maintenance Mode</h3><p>' + pointer.content + '</p>',
        pointerWidth: 380,
        position: {
            edge: pointer.edge,
            align: pointer.align
        },
        close: function() {
                $.post(ajaxurl, {
                    pointer: index,
                    _ajax_nonce: csmm_pointers._nonce_dismiss_pointer,
                    action: 'csmm_dismiss_pointer'
                });
        }
      }).pointer('open');
  });
});
