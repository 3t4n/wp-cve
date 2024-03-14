
document.addEventListener( 'wpcf7mailsent', function( data ) {
    if ( atccf7_options_form.atc_on == '1' ){
        render_button( data.detail.inputs );
    }



}, false );


var formatDate = function( date ){
    var tempDate = date.getFullYear() + '' + ( ( date.getMonth() + 1 ) < 10 ? '0' + ( date.getMonth() + 1 ) : ( date.getMonth() + 1 )  ) + '' + date.getDate();
    return tempDate;

}
var oneMoreDay = function( date ){
    date = new Date ( date );
    date.setDate( date.getDate() + 1 ); 
    return formatDate( date );
};
var render_button = function( inputs ){
        
    var date1 = 0,
        date2 = 0,
        dates = '',
        google_button = '',
        specific_date = atccf7_options_form.specific_date,
        btn_name = ( atccf7_options_form.event_btn != '' ? atccf7_options_form.event_btn : 'Add to Calendar' );

    // if we're using a specific date
    if ( typeof specific_date !== 'undefined'  && specific_date !== '' ) {
        date1 = specific_date;
        date2 = oneMoreDay( specific_date );
    } else{
        for( i = 0; i < inputs.length; i++ ){
            if( inputs[i]['name'] == atccf7_options_form.date_1 ){
                date1 = inputs[i]['value'];
                continue;
            }

            if( inputs[i]['name'] == atccf7_options_form.date_2 ){
                date2 = inputs[i]['value'];
                date2 = oneMoreDay( date2 );
                continue;
            }
        }
    }
    //just numbers 
    date1 = date1.replace(/\D/g,'');

    if( date2 != 0 ){
        date2 = date2.replace(/\D/g,'');
    } else{
        date2 = oneMoreDay( date1 );
    }
     
    dates = 'dates=' + date1 + '/' + date2;
    

    //validate if there's one date at least
    if ( date1 != 0 ) {
        // construct button url
        google_button = '<a class="atc-link-cf7" href="http://www.google.com/calendar/event?' +
        'action=TEMPLATE' +
        '&text=' + atccf7_options_form.event_name + 
        '&' + dates +
        '&details=' + atccf7_options_form.event_description +
        '&location=' + atccf7_options_form.event_location + 
        '&trp=false' + 
        '&sprop=' +
        '&sprop=name:"' +
        'target="_blank" rel="nofollow"><div style="color:' + atccf7_options_form.color_text + '; ' + 'background-color:' + atccf7_options_form.color_btn  + '"; class="atc-button-cf7">' + btn_name +
        '</div></a>';

        // prevent more than one submission
        jQuery('.atc-link-cf7').remove();

        var check_sent_element = setInterval(function() {
            if ( jQuery('.wpcf7-mail-sent-ok').length ) {
                jQuery('.wpcf7-mail-sent-ok').after( google_button );
            clearInterval( check_sent_element );
            }
        }, 100); 
    }
};