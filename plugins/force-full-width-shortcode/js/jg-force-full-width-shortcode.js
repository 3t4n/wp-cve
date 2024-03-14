//replace all $( with jQuery( for wordpress to use
jQuery(function(){
    //get the content area's width limit
    var bodyWidth = jQuery(".jg-force-full-width").parent().width();
    //get the window's width
    var windowWidth =jQuery(window).width();

    //set the full width div's width to the body's width, which is always full screen width
    jQuery(".jg-force-full-width").css({"width": jQuery("body").width() + "px"});
    //set all full width div's children's width to 100%
    jQuery(".jg-force-full-width").children().css({"width":"100%"});

    //setting margin for aligning full width div to the left
    //only needed when content area width is smaller than screen width
    if(windowWidth>bodyWidth){
        var marginLeft = -(windowWidth - bodyWidth)/2;

        jQuery(".jg-force-full-width").css({"margin-left": marginLeft+"px"});
    }

    // handling changing screen size
    jQuery(window).resize( function(){
        jQuery(".jg-force-full-width").css({"width": jQuery("body").width() + "px"});
        if(windowWidth>bodyWidth){
            jQuery(".jg-force-full-width").css({"margin-left": (-(jQuery(window).width() - jQuery(".jg-force-full-width").parent().width())/2)+"px"});
        } else{
            jQuery(".jg-force-full-width").css({"margin-left": "0px"});
        }
    });

});