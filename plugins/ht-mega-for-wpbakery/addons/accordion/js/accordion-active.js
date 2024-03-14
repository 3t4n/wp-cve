jQuery(window).load(function() {
   if(jQuery('.htmegavc-accordion--4').length){

	   jQuery('.htmegavc-accordion--4 > li').hover(
	       function () {
	       	   var accordionOptions = jQuery(this).parent().data('htmegavc-accordion');
	       	   var item_width_expand = accordionOptions.item_width_expand ? accordionOptions.item_width_expand : '430px';

	           var $this = jQuery(this);
	           $this.stop().animate({
	               'width': item_width_expand
	           }, 500);
	           jQuery('.horizontal_accor_heading', $this).stop(true, true).fadeOut();
	           jQuery('.horizontal_accor_bgDescription', $this).stop(true, true).slideDown(500);
	           jQuery('.horizontal_accor_description', $this).stop(true, true).fadeIn();
	       },
	       function () {
	       	   var accordionOptions = jQuery(this).parent().data('htmegavc-accordion');
	       	   var item_width = accordionOptions.item_width ? accordionOptions.item_width : '130px';

	           var $this = jQuery(this);
	           $this.stop().animate({
	               'width': item_width
	           }, 1000);
	           jQuery('.horizontal_accor_heading', $this).stop(true, true).fadeIn();
	           jQuery('.horizontal_accor_description', $this).stop(true, true).fadeOut(500);
	           jQuery('.horizontal_accor_bgDescription', $this).stop(true, true).slideUp(700);
	       }
	   );

   }

  	if(jQuery('.htmegavc-accordion--5').length){
		jQuery('.htmegavc-accordion--5').each(function () {
			var accordionOptions = jQuery(this).data('htmegavc-accordion');

   	    var h_wrapper_height = accordionOptions.h_wrapper_height ? accordionOptions.h_wrapper_height : '450';
   	    var h_item_expand_height = accordionOptions.h_item_expand_height ? accordionOptions.h_item_expand_height : '450';
   	    var h_items_to_show = accordionOptions.h_items_to_show ? accordionOptions.h_items_to_show : 3

		    jQuery(this).vaccordion({
		        accordionH: h_wrapper_height,
		        expandedHeight: h_item_expand_height,
		        visibleSlices: h_items_to_show,
		        animSpeed: 500,
		        animEasing: 'easeInOutBack',
		        animOpacity: 1
		    });
		});
 	}

});