var bookmarkedUrl = readCookie('toocheke-comic-bookmark');
var cookieLifeSpan = 31;


(function( $ ) {
    'use strict';
    if(bookmarkedUrl) {
        $('#toocheke-go-to-bookmark').show();
        var currentUrl = window.location.href;
        if(bookmarkedUrl == currentUrl) {
            $('#comic-bookmark').find('i').toggleClass('far fas').css('color', '#dc3545');
        }
       
    }
    else{
        $('#toocheke-go-to-bookmark').hide();
    }
	$(document).on('click', '#comic-bookmark', function() {
        
        var bookmarkButton = $(this).find('i');
        bookmarkButton.toggleClass('far fas');
        if(bookmarkButton.hasClass('fas')){
            createCookie("toocheke-comic-bookmark", window.location.href, cookieLifeSpan);
            bookmarkButton.css('color', '#dc3545');
        }
        else{
            createCookie("toocheke-comic-bookmark","",-1);
            bookmarkButton.css('color', 'initial');
        }
        
	
    });
    $(document).on('click', '#toocheke-go-to-bookmark', function() {
        var bookmarkedUrl = readCookie('toocheke-comic-bookmark');
        if(bookmarkedUrl) {
            window.location = bookmarkedUrl;
        }
    });
})( jQuery );


/* The follow functions have been borrowed from Peter-Paul Koch. Please find them here: http://www.quirksmode.org */

function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    } else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
