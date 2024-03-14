(function($){
    "use strict";
    
    var listenSubConfChange = function() {
        $('.check-subconf').on('change', function(){
            var subConfDiv = $(this).parent().parent().find('.mh-subconf');

            // when subconf div is empty, hide them
            if ( subConfDiv.length && subConfDiv.html().trim().length === 0 ) {
                subConfDiv.detach();
            }

            if ( $(this).is(':checked') ) {
                $( subConfDiv ).slideDown();
            }
            else {
                $( subConfDiv ).slideUp();
            }
        }).trigger('change');
    };


    jQuery(document).ready(function($){
        
        listenSubConfChange();

        // display active tab
        $('#settings-' + tab_current).show();
    });

})(jQuery);
