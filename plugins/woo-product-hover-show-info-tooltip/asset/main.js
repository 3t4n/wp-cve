(function($){
    $(document).ready(function(){
        // console.log('cuongdc ready! ');
        if( $(window).width() > 600 && $(".cdc_shop_loop_box").length > 0  ){

            var thewidth =  $(window).width();
            var theheight =  $(window).height();
            $(".cdc_shop_loop_box").hover(function() {
                $(this).mousemove(function(e)
                {             var theX = thewidth - e.clientX ;
                    var theY = theheight - e.clientY ;
                    if (theX > 520 ) {
                        theX = e.clientX;
                    } else {
                        theX = thewidth - 520;
                    }
                    if (theY > $(this).children('.cdc_thetip').height()) {
                        theY = e.clientY;
                    } else {
                        theY = theheight - $(this).children('.cdc_thetip').height() - 30;
                    }

                    $(this).children('.cdc_thetip').css('left', theX + 10 ).css('top', theY + 10 ).css('display', 'block');
                });
            }, function() {
                $(this).children('.cdc_thetip').css('display', 'none');
            });

        }//check

    });//dom ready
})(jQuery);