/**
 *  for Admin
 */

jQuery( document ).ready( function( $ ) {
    $('input.shortcode').focus( function(){
        $( this ).select();
        document.execCommand('copy');
        $('.tooltip').show();
    })
})
