!function($) {
	/**
	 * SpeedUpLazyLoad plugin
	 * This plugin is based on unveil.js plugin by Mika Tuupola: https://github.com/luis-almeida/unveil
	 */
    $.fn.SpeedUpLazyLoad = function(attrib, threshold) {
    
    	var loaded    = null, 
    	    $w        = $(window),
    	    threshold = threshold || 0,
    	    attrib    = attrib || "data-lazy-src",
            lazyWord  = "lazyLoad",
            els       = this;
    
    	/**
    	 * lazyLoad event.
    	 * Check if element has lazy attr and if exists
         * change scr value with lazy attr value and load the element.
         * 
         * @return void
    	 */
    	els.one(lazyWord, function() {
    	
    	    var $el = $(this),
                src = $el.attr(attrib);
        
    	    $el.removeAttr(attrib);
    	    
            if( src ){
            	if ( $el.is("img") || $el.is("iframe") ) {
        	        $el.attr("src", src);
            	} else {
            		$el.css('background-image', 'url(' + src + ')');
            	}
            }
        
        });
    
        /**
         * lazyLoad function.
         * For each lazy elements check if the element is visible (in the screen area).
         * 
         * @return void
         */
        var lazyLoad = function() {
        
    	    var inview = els.filter(function() {
        
        	    var $el = $(this);
        
        	    // check if the element is visible
                if ( !$el.is(":hidden") ) {
        	
        	        var winTop    = $w.scrollTop(),
            	        winBottom = winTop + $w.height(),
            	        elTop     = $el.offset().top,
            	        elBottom  = elTop + $el.height();

        	        // check if the element is in the screen area
        	        return ((elBottom >= (winTop - threshold)) && (elTop <= (winBottom + threshold)));
                }
        
            });
    	
    	    // trigger lazyLoad event
            loaded = inview.trigger(lazyWord);
            
            // update the elements list
            els = els.not(loaded);
        };
     
        // attach lazyLoad event on some window events
        $w.on("scroll." + lazyWord + " resize." + lazyWord + " lookup." + lazyWord + " touchend." + lazyWord, lazyLoad); 
    
        // init 
        lazyLoad();
    
        return this;
    }
}(jQuery);

jQuery(document).ready(function($) {
	
	var attrib = 'data-lazy-src';
	
    $("img["+attrib+"],iframe["+attrib+"],div["+attrib+"]").SpeedUpLazyLoad(attrib, 150);
    
});