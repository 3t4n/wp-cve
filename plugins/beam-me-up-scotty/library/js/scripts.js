( function( $ ) {
	
    $( document ).ready( function() {
    	var back_to_top_button = $('.otb-beam-me-up-scotty');
    	
    	back_to_top_button.bind('click', function() {
			$('body').addClass('animating');
    		$('html, body').stop().animate({
    			scrollTop:0
    		},
    		'slow',
    		function () {
    			$('body').removeClass('animating');
    		});
    		return false;
    	});
    	
    	back_to_top_button.hover( function() {
			disable_scroll_timer.call( back_to_top_button );
    	}, function() {
			enable_scroll_timer.call( back_to_top_button );
    	});
    	
    	/*
		if ( !$('body').hasClass('mobile-device') ) {
		    var timer;
		    var fadeInBuffer = false;
		    $(document).mousemove(function () {
		        if (!fadeInBuffer) {
		            if (timer) {
		                clearTimeout(timer);
		                timer = 0;
		            }

		            hideBackToTopButton();
		        } else {
		        	showBackToTopButton();
		        	
		            fadeInBuffer = false;
		        }
		
		        timer = setTimeout(function () {
		        	hideBackToTopButton();
		         
		            fadeInBuffer = true;
		        }, 500)
		    });
		    
		    showBackToTopButton();
			
		    var idleMouseTimer;
		    var mouseOverCanvas = false
		    var mouseIsIdle = false;
		
		    $("body").mousemove(function(e) {
		    	if ( mouseOverCanvas ) {
		    		
		    		mouseIsIdle = false;
		    		clearTimeout(idleMouseTimer);
		    		showBackToTopButton();
		    		
		    		idleMouseTimer = setTimeout(function() {
		    			hideBackToTopButton();
				    	mouseIsIdle = true;
		    		}, 1500);
		
		    	}
		    })
		    
		    $('body').hover(function(){
		    	mouseOverCanvas = true;
		    },
		    function(){
		    	mouseOverCanvas = false;
		    });    
	    
		} else {
		    var idleMouseTimer;
			
		    $(document).on("touchstart", '.otb-beam-me-up-scotty', function(e) {
				
				clearTimeout( window.backToTopButtonDelay );
				
				// Use setTimeout to stop the code from running before the window has finished resizing
				window.backToTopButtonDelay = setTimeout(function() {
					//if ( ig.global.showFullscreenToggler ) {
						clearTimeout(idleMouseTimer);
						showBackToTopButton();
						
			    		idleMouseTimer = setTimeout(function() {
			    			hideBackToTopButton();
					    	mouseIsIdle = true;
			    		}, 1500);
					//}
				}, 0);
			});
		
		}
		*/
    	
    });

    $(window).on('load', function() {
		setBackToTopButtonVisibility();
    });
    
    $(window).scroll(function(e) {
    	var back_to_top_button = $( '.otb-beam-me-up-scotty' );
    	
    	if ( back_to_top_button.hasClass( 'hide-when-inactive' ) ) {
    		disable_scroll_timer.call( back_to_top_button );
    		enable_scroll_timer.call( back_to_top_button );
    	}
        
		setBackToTopButtonVisibility();
    });
    
    function disable_scroll_timer() {
    	clearTimeout( $.data( $(this)[0], 'scrollTimer' ) );
    }
    
    function enable_scroll_timer() {
        $.data( $(this)[0], 'scrollTimer', setTimeout(function() {
        	hideBackToTopButton();
        }, otb_beam_me_up_scotty.hide_delay ));
    }
    
    function showBackToTopButton() {
    	$( '.otb-beam-me-up-scotty' ).css('visibility', 'visible').removeClass( 'hidden' );
    }

    function hideBackToTopButton() {
    	$( '.otb-beam-me-up-scotty' ).addClass( 'hidden' ).css('visibility', 'hidden');
    }
    
    function setBackToTopButtonVisibility() {
    	if ( $(window).scrollTop() > $(window).height() / 2 ) {
			showBackToTopButton();
    	} else {
			hideBackToTopButton();
    	}
    }

} )( jQuery );