(function( $ ) { 'use strict';

    var LD_CS_Widget = {
        init: function() {
            if( $("#ld_cs_listing").length ) {
                $('#ld_cs_listing').accordion();
            }
        },
    };

    $( document ).ready( function() {
        LD_CS_Widget.init();
    });

})( jQuery );