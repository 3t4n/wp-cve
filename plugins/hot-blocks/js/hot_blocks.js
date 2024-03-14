jQuery(document).ready(function(){

	// Parallax scroll

	$window = jQuery(window);
                
	jQuery('div.wp-block-hotblocks-parallax').each(function(){
		var $bgobj = jQuery(this); // assigning the object
                    
		jQuery(window).scroll(function() {
                    
			// Scroll the background at var speed
			// the yPos is a negative value because we're scrolling it UP!

			// Distance of obj from top:  $bgobj.offset().top;
			// Current scroll position:   $window.scrollTop());
			// Object height:             $bgobj.innerHeight());
			var yPos = -( ($window.scrollTop() - $bgobj.offset().top - $bgobj.innerHeight() ) / 5 + jQuery(window).innerHeight() );
		
			// Put together our final background position
			var coords = '50% '+ yPos + 'px';

			// Move the background
			$bgobj.css({ backgroundPosition: coords });
		
		}); // window scroll Ends
	});

	// Accordion
	
	jQuery('.wp-block-hotblocks-accordion .accordion-heading').on('click', function(){

		if ( ! jQuery(this).hasClass('active_tab') ) {

			jQuery('.accordion-heading.active_tab').next().slideToggle(100);
			jQuery('.accordion-heading.active_tab').removeClass('active_tab');

			jQuery(this).next().slideToggle(100);
			jQuery(this).toggleClass('active_tab');

		} else {

			jQuery(this).next().slideToggle(100);
			jQuery(this).toggleClass('active_tab');
			
		}

	});

	// Carousel

	jQuery('.is-style-hot-carousel, ul.is-style-hot-posts-carousel').bxSlider({
		mode: 'horizontal',
		speed: 500,
		slideMargin: 0,
		randomStart: false,
		infiniteLoop: true,
		hideControlOnEnd: true,
		easing: 'linear',
		pager: true,
		pagerType: 'full',
		controls: true,
		auto: false,
		autoControls: false,
		autoControlsCombine: true,
		pause: 4000,
		autoDirection: 'next',
		autoHover: true,
		slideWidth: 0
	});

});