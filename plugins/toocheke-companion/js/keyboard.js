jQuery(function () {
        jQuery(document).keydown(function(e) {
            var url = false;
    
            if(document.querySelector('#comment:focus,#author:focus,#email:focus,#url:focus,#mcspvalue:focus')) return;
    
            if (e.which == 37) {  // Left arrow key code
                url = jQuery('a.previous-comic').attr('href');
            } else if (e.which == 39) {  // Right arrow key code
                url = jQuery('a.next-comic').attr('href');
            }
            if (url) {
                window.location = url;
            }
        });
    
 
});