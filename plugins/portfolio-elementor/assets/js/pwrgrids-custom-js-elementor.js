function startPwrgridsIsotope(){   

    jQuery('#elementor-preview-iframe').contents().find('.pwgd-grid-content-isotope').imagesLoaded( function() {
        //Masonry
        var $grid = jQuery('#elementor-preview-iframe').contents().find('.pwgd-grid-content-isotope').isotope({
            itemSelector: '.pwgd-post-grid-item-wrapper',
            layoutMode: 'masonry',     
        });         

        //Packery
        var $packery = jQuery('#elementor-preview-iframe').contents().find('.elpt-portfolio-content-packery').isotope({
            layoutMode: 'packery',            
            itemSelector: '.portfolio-item-wrapper'
        });        

        jQuery('#elementor-preview-iframe').contents().find('.pwgd-grid-content-equalheights').each(function(){          
            // Cache the highest
            var highestBox = 0;
            
            // Select and loop the elements you want to equalise
            jQuery('#elementor-preview-iframe').contents().find('.pwgd-post-grid-item-wrapper', this).each(function(){
            
                // If this box is higher than the cached highest then store it
                if(jQuery(this).height() > highestBox) {
                    highestBox = jQuery(this).height(); 
                }
            
            });  
                
            // Set the height of all those children to whichever was highest 
            jQuery('#elementor-preview-iframe').contents().find('.pwgd-post-grid-item-wrapper',this).height(highestBox);
        });
    });                
}


jQuery(window).on('load', function(){
    elementorFrontend.hooks.addAction('frontend/element_ready/widget', function($scope){
        startPwrgridsIsotope();        
    });

    setInterval(function() {	
		startPwrgridsIsotope(); 
	}, 1000);
});