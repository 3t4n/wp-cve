(function($) { 
	'use strict';

  	/**
	 * WooCommerce Category Slider
	 */
	
	$(".wpb-woo-cat-slider").each(function() {
	    var t = $(this),
	        auto 			= t.data("autoplay") ? !0 : !1,
	        rtl 			= t.data("direction") ? !0 : !1,
	        items 			= t.data("items") ? parseInt(t.data("items")) : '',
	        desktopsmall 	= t.data("desktopsmall") ? parseInt(t.data("desktopsmall")) : '',
	        tablet 			= t.data("tablet") ? parseInt(t.data("tablet")) : '',
	        mobile 			= t.data("mobile") ? parseInt(t.data("mobile")) : '',
	        nav 			= t.data("navigation") ? !0 : !1,
	        pag 			= t.data("pagination") ? !0 : !1,
	        loop 			= t.data("loop") ? !0 : !1,
	        navTextLeft 	= t.data("direction") ? 'right' : 'left',
	        navTextRight 	= t.data("direction") ? 'left' : 'right';
	        
	    $(this).owlCarousel({
	        autoplay: auto,
	        autoHeight: true,
	        rtl: rtl,
	        items : items,
	        responsiveClass:true,
		    responsive:{
		    	0:{
		            items: mobile,
		        },
		        480:{
		            items: mobile,
		        },
		        768:{
		            items: tablet,
		        },
		        1170:{
		            1024: desktopsmall,
		        },
		        1200:{
		            items: items,
		        }
		    },
            nav: nav,
            navText : ['<i class="fa fa-angle-'+navTextLeft+'" aria-hidden="true"></i>','<i class="fa fa-angle-'+navTextRight+'" aria-hidden="true"></i>'],
            dots: pag,
            loop: loop,
            margin: 10,
	    });
	});

})(jQuery);  