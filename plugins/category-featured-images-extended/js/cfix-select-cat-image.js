/**
 * @package: Category Featured Images Extended
 * @Version: 1.3.2
 * @Date: 30 November 2016
 * @Author: CK MacLeod
 * @Author: URI: http://ckmacleod.com
 * @License: GPL3
 */

(function( $ ) {
    
    $( '.cfix-cat-image-link' ).live( 'click', function( event ) {
        
        event.preventDefault();
        
        var newname = $( this ).attr( 'data' );
        var newimg = $( this ).find( 'img' ).attr( 'src' );
        var savechanges = select_cat_strings.save_changes ;
        var yournew = select_cat_strings.new_fallback ;
        var orcancel = select_cat_strings.or_cancel ;    
        
        $( '#cks_cfix_fallback_category' ).val(
                $( this ).attr( 'data' )
                ).text() ;
        $( '.cfix-thumbnail' ).empty();
        $( '.cfix-thumbnail' ).append( 
                '<img src="' + newimg + '" id="cfix-new-image">' 
                + '<p>' + savechanges + ' </br><b>"' + newname + '"</b><br> ' + yournew + 
                '<br><a href"#" id="or-cancel">' + orcancel + '</a></p>'
                );
        $( '.cfix-thumbnail' ).addClass( 'cfix-new-appended' ) ;
        
        //reset from newly created div
        $( '#or-cancel' ).click(function() { 
            location.reload(); 
        });
        
    });
        
})( jQuery );
