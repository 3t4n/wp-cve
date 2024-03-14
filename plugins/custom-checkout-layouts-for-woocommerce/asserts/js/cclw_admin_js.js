(function($) {
	$(document).ready(function(){	
	   $('.cclw_rating_wrap').delay(2000).slideDown('slow');
	   
	   /*customize checkout settings */
	   $('#billing_details_group_wrap').on("click",function(){
			 $('.shipping_details_group_wrap').hide();	
			 $('.billing_details_group_wrap').show();	
		
		});
		$('#shipping_details_group_wrap').on("click",function(){
			 $('.shipping_details_group_wrap').show();	
			 $('.billing_details_group_wrap').hide();
					 
		});
    });
	
	$(".cclw_rating_links .button2").on("click",function(){
                        var data = {
                    		action: 'cclw_update_rating',
                    		cclw_ratings: 'no',
						};
						
						
                    	$.post(cclw_ajax.ajax_url, data, function( response )
                		{
							$('.cclw_rating_wrap').slideUp('slow');
						}); 
                    });
	$(".cclw_rating_links .button3").on("click",function(){
	$('.cclw_rating_wrap').slideUp('slow');
	
	});		
	
	})(jQuery);