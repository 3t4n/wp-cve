var $j = jQuery.noConflict();

$j( function() {

    $j( document ).on( 'change', '#smtp_config_file', function( e ) {

        if ( this.checked ) {
            $j('#smtp_pass').hide();
            $j('#smtp_config_def').show();
        }else{
            $j('#smtp_config_def').hide();
            $j('#smtp_pass').show();
        }

    });
});
