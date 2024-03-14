var dummy = 'really';
/*Scrool on load - Prevent Display of iframe white spaces*/
jQuery(document).ready(function(){
    var lang = getCookie('googtrans');
    if(lang){
        jQuery('.hq-list-image-wrapper a').on('click',function(e){
            e.preventDefault();
        });
        jQuery('.hq-grid-button-wrapper-left h3').each(function(index){
            jQuery(this).html('From ' + jQuery(this).html());
        });
    }
});

function getCookie(name) {
    // Split cookie string and get all individual name=value pairs in an array
    var cookieArr = document.cookie.split(";");        // Loop through the array elements
    for(var i = 0; i < cookieArr.length; i++) {
        var cookiePair = cookieArr[i].split("=");            /* Removing whitespace at the beginning of the cookie name
            and compare it with the given string */
        if(name == cookiePair[0].trim()) {
            // Decode the cookie value and return
            return decodeURIComponent(cookiePair[1]);
        }
    }
    // Return null if not found
    return null;
}