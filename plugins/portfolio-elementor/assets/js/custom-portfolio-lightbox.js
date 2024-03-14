jQuery(window).on('load', function () {
    if ( jQuery( ".elpt-portfolio-content" ).length ) {
             
        //Lightbox
        /*jQuery('.elpt-portfolio-lightbox').each(function(){
            jQuery(this).simpleLightbox({
                captions: true,
                disableScroll: false,
                rel: true,
            }); 
        });*/
        
        jQuery('a.elpt-portfolio-lightbox').simpleLightbox({
            captions: true,
            disableScroll: false,
            rel: true,
        });

        jQuery('a.elpt-portfolio-lightbox').on('click', function () { 
            setTimeout(() => {
                jQuery('#elementor-lightbox-slideshow-single-img').css( "z-index", "888" );  
              }, "10");                      
        });
    }

});