(function($){

    $(document).ready(function(){
        var row_head = $('.fl-row .bb-er-row');
            if ( row_head.length ) {
                 row_head.each(function() {
                        if( ! $(this).hasClass('bber-aligned') ) {
                             var append_element = $(this).closest('.fl-row');
                             append_element.prepend($(this));
                             $(this).addClass('bber-aligned');
                        }
                  });
            }
    });

      $( '.fl-builder-content' ).on( 'fl-builder.layout-rendered', function(){
            var row_head = $('.fl-row .bb-er-row');
            if ( row_head.length ) {
                 row_head.each(function() {
                        if( ! $(this).hasClass('bber-aligned') ) {
                             var append_element = $(this).closest('.fl-row');
                             append_element.prepend($(this));
                             $(this).addClass('bber-aligned');
                        }
                  });
            }                
      });

})(jQuery);