jQuery(document).ready(function($) {
    
    $('.mgt-flipbox .mgt-flipbox-front').each(function( index ) {
        $(this).attr('style', ($(this).attr('data-style')));
    });
    $('.mgt-flipbox .mgt-flipbox-back').each(function( index ) {
        $(this).attr('style', ($(this).attr('data-style')));
    });
                
                
});