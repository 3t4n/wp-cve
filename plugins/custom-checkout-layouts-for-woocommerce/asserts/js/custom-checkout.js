jQuery( function() {
	
	
	/*popup section styling*/
	jQuery( "#cclw_coupon_box" ).dialog({
	                   autoOpen: false,	 
                       dialogClass: "no-titlebar",
					   classes: {
					   "ui-dialog": "cclw_dialog_wrraper"
					   }
					  
                       
                      
	});
	jQuery(".cclw_showcoupon").click(function () {
		       
				jQuery(".ui-dialog-titlebar").hide();
                jQuery("#cclw_coupon_box").dialog("open");
				jQuery("form.checkout").css("-webkit-filter","blur(1px)");
            });
	jQuery("#cclw_coupon_box .coupn_close").click(function () {
                jQuery("#cclw_coupon_box").dialog("close");
				jQuery("form.checkout").css("-webkit-filter","blur(0px)");
            });		
	
	/*popup section closed*/

	jQuery('form.checkout').on("click", "button.cclwminus", function(){
							var inputqty = jQuery(this).next('input.qty');
							var val = parseInt(inputqty.val());
							var max = parseFloat(inputqty.attr( 'max' ));
							var min = parseFloat(inputqty.attr( 'min' ));
							var step = parseFloat(inputqty.attr( 'step' ));
						  
						   if(val > min )
						   {
							inputqty.val( val - step );
                            jQuery(this).next('input.qty').trigger( "change" ); 							
							
						   }
					
					    });
						
						jQuery('form.checkout').on("click", "button.cclwplus", function(){
							
							var inputqty = jQuery(this).prev('input.qty');
							var val = parseInt(inputqty.val());
							var max = inputqty.attr( 'max' );
							var min = parseFloat(inputqty.attr( 'min' ));
							var step = parseFloat(inputqty.attr( 'step' ));
						  
						    if(val < max || max == ''  )
							   {
							    inputqty.val( val + step );  
                                 jQuery(this).prev('input.qty').trigger( "change" ); 								
							   }
					
					  
					    });
					   
					
					jQuery("form.checkout #order_review_table").on("change", "input.qty", function(){
						                   
                        var data = {
                    		action: 'cclw_update_order_review',
                    		security: wc_checkout_params.update_order_review_nonce,
                    		post_data: jQuery( 'form.checkout' ).serialize()
                    	};
						
                    	jQuery.post( cclw_front_ajax.ajax_url, data, function( response )
                		{
						    jQuery( 'body' ).trigger( 'update_checkout' );
						}); 
                    });
					
					jQuery( document.body ).on( 'added_to_cart', function(){
                        jQuery( 'body' ).trigger( 'update_checkout' );
                    });	  


} );	
					