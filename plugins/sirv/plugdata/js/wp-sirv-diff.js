/* This JavaScript is used for different helper functions, such as Sirv object updates with Ajax. */

jQuery( document ).ajaxComplete(function() {
    if (arguments[1].responseText && arguments[1].responseText.match(/class *= *"[^"]*Sirv/gm)) {
        setTimeout(function(){Sirv.start();},100);
    }
});


jQuery(document).ready(function(){
    let count = 0;
    let timerId = setInterval(fixLinks, 200);
    function fixLinks(){
        let $images = jQuery('.svi-img');
        count++;

        if($images.length > 0){
            jQuery.each($images, function (indexInArray, val) {
                jQuery(val).attr('src', val.src.replace(/amp;/g, ''));
            });
            clearInterval(timerId);
        }else{
            if (count == 10) clearInterval(timerId);
        }
        
    }

    jQuery('.svi-img').on('click', function(){
        jQuery('.preview-img-item').attr('src', jQuery(this).attr('src').replace('amp;', ''));
    });
});
