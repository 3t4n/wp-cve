(function( $ ) {
    $( ".wpcf7" )
        .on( 'wpcf7mailsent', function( e ) {
            /*var fac_cf7formid = '(not set)';
            if ( e && e.detail && e.detail.contactFormId ) {
                fac_cf7formid = e.detail.contactFormId;
            } else if ( e && e.originalEvent && e.originalEvent.detail && e.originalEvent.detail.contactFormId ) {
                fac_cf7formid = e.originalEvent.detail.contactFormId;
            }*/

            var fac_cf7forminputs = [];
            if ( e && e.detail && e.detail.inputs ) {
                fac_cf7forminputs = e.detail.inputs;
            } else if ( e && e.originalEvent && e.originalEvent.detail && e.originalEvent.detail.inputs ) {
                fac_cf7forminputs = e.originalEvent.detail.inputs;
            }
            //console.log(fac_cf7forminputs);

            if(fac_cf7forminputs.constructor === Array) {
                fac_cf7forminputs.forEach(function (e) {
                    //console.log(e);
                    if(e.name && e.value) {
                        if(e.name === 'fac_cf7_event_id') {
                            //console.log(e.name + ' ' + e.value);
                            fathom.trackGoal(e.value, 0);
                        }
                    }
                });
            }

        });
})( jQuery );