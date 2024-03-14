function fcpfwUpdateRefreshFragments( response ) {

    if( response.fragments ) {

        //Set fragments
        jQuery.each( response.fragments, function( key, value ) {
            jQuery( key ).replaceWith( value );
        });

        if( ( 'sessionStorage' in window && window.sessionStorage !== null ) ) {

            sessionStorage.setItem( wc_cart_fragments_params.fragment_name, JSON.stringify( response.fragments ) );
            localStorage.setItem( wc_cart_fragments_params.cart_hash_key, response.cart_hash );
            sessionStorage.setItem( wc_cart_fragments_params.cart_hash_key, response.cart_hash );

            if ( response.cart_hash ) {
                sessionStorage.setItem( 'wc_cart_created', ( new Date() ).getTime() );
            }
        }

        jQuery( document.body ).trigger( 'wc_fragments_refreshed' );
    }
}


function fcpfwGetRefreshedFragments(){

    jQuery.ajax({
        url: ajax_postajax.ajaxurl,
        type: 'POST',
        data: {
            action: 'fcpfw_get_refresh_fragments',
        },
        success: function( response ) {
            fcpfwUpdateRefreshFragments(response);
        }
    })

}


jQuery(document).ready(function() {

	jQuery( document.body ).trigger( 'wc_fragment_refresh' );

    if(fcpfw_sidebar_width.fcpfw_trigger_class == "yes"){
        jQuery("body").on("click",".fcpfw_trigger",function() {

            jQuery(".fcpfw_container").css("opacity", "1");
            if(fcpfw_sidebar_width.fcpfw_cart_open_from == "left"){
                jQuery(".fcpfw_container").animate({width: fcpfw_sidecart_width, left: '0px'});
            }else{
                jQuery(".fcpfw_container").animate({width: fcpfw_sidecart_width, right: '0px'});
            }
            jQuery("body").addClass("fcpfw_overlay");
            jQuery(".fcpfw_container_overlay").addClass('active');
            
        });
    }

    setTimeout(function() {
        fcpfwGetRefreshedFragments();
    }, 100);

    fcpfw_sidecart_width = fcpfw_sidebar_width.fcpfw_width;

 



    jQuery('body').on( 'added_to_cart', function() {
       
        
        if(fcpfw_sidebar_width.fcpfw_auto_open =='yes'){
           jQuery(".fcpfw_container").css("opacity", "1");
           if(fcpfw_sidebar_width.fcpfw_cart_open_from == "left"){
                jQuery(".fcpfw_container").animate({width: fcpfw_sidecart_width, left: '0px'});
           }else{
             jQuery(".fcpfw_container").animate({width: fcpfw_sidecart_width, right: '0px'});
           }
    	   jQuery("body").addClass("fcpfw_overlay");
    	   jQuery(".fcpfw_container_overlay").addClass('active');
         }
    });


    jQuery(".fcpfw_close_cart").click(function() {
	  	var boxWidth = jQuery(".fcpfw_container").width();
        if(fcpfw_sidebar_width.fcpfw_cart_open_from == "left"){
    	   	jQuery(".fcpfw_container").animate({
                left: '-'+fcpfw_sidecart_width
            });
        }else{
            jQuery(".fcpfw_container").animate({
                right: '-'+fcpfw_sidecart_width
            });
        }
        jQuery("body").removeClass("fcpfw_overlay");
        jQuery(".fcpfw_container_overlay").removeClass('active');
	});

	jQuery(".fcpfw_container_overlay").click(function() {
		jQuery(".fcpfw_close_cart").click();
	});

	jQuery(".fcpfw_cart_basket").click(function() {

		jQuery(".fcpfw_container").css("opacity", "1");

        if(fcpfw_sidebar_width.fcpfw_cart_open_from == "left"){
                jQuery(".fcpfw_container").animate({width: fcpfw_sidecart_width , left: '0px'});
        }else{
             jQuery(".fcpfw_container").animate({width: fcpfw_sidecart_width , right: '0px'});
        }
		
		jQuery("body").addClass("fcpfw_overlay");

		jQuery(".fcpfw_container_overlay").addClass('active');
	});


	jQuery('body').on('click', '#fcpfw_apply_coupon', function() { 
		jQuery(".fcpfw_apply_coupon_link").css("display","none");
		jQuery(".fcpfw_coupon_field").css("display","block");
		return false;
	});

    jQuery('body').on('click', '.fcpfw_coupon_submit', function() { 

        var couponCode = jQuery("#fcpfw_coupon_code").val();

        jQuery.ajax({
            url:ajax_postajax.ajaxurl,
            type:'POST',
            data:'action=coupon_ajax_call&coupon_code='+couponCode,
            success : function(response) {
                jQuery("#fcpfw_cpn_resp").html(response.message);
                if(response.result == 'not valid' || response.result == 'already applied') {
                	jQuery("#fcpfw_cpn_resp").css('background-color', '#e2401c');
                } else {
                	jQuery("#fcpfw_cpn_resp").css('background-color', '#0f834d');
                }
                jQuery(".fcpfw_coupon_response").fadeIn().delay(2000).fadeOut();
                jQuery( document.body ).trigger( 'wc_fragment_refresh' );
            }
        });
    });

    jQuery('body').on('click', '.fcpfw_remove_cpn', function() {

        var removeCoupon = jQuery(this).attr('cpcode');

        jQuery.ajax({
            url:ajax_postajax.ajaxurl,
            type:'POST',
            data:'action=remove_applied_coupon_ajax_call&remove_code='+removeCoupon,
            success : function(response) {
                jQuery("#fcpfw_cpn_resp").html(response);
                jQuery(".fcpfw_coupon_response").fadeIn().delay(2000).fadeOut();
                jQuery( document.body ).trigger( 'wc_fragment_refresh' );
            }
        });

    });

	jQuery('body').on('change', 'input[name="update_qty"]', function() {
	    var pro_id = jQuery(this).closest('.fcpfw_cart_prods').attr('product_id');
	    var qty = jQuery(this).val();
	    var c_key = jQuery(this).closest('.fcpfw_cart_prods').attr('c_key');
		var pro_ida = jQuery(this);
		pro_ida.prop('disabled', true);
	    
        jQuery.ajax({
	        url:ajax_postajax.ajaxurl,
	        type:'POST',
	        data:'action=change_qty&c_key='+c_key+'&qty='+qty,
	        success : function(response) {
	        	pro_ida.prop('disabled', false);
	            jQuery( document.body ).trigger( 'wc_fragment_refresh' );
	        }
	    });
	});


    var leftArrow = fcpfw_urls.pluginsUrl + '/assets/images/left-arrow.svg';
    var rightArrow = fcpfw_urls.pluginsUrl + '/assets/images/right-arrow.svg';

    jQuery('.fcpfw_slider_inn').owlCarousel({
        loop:true,
        margin:10,
        nav:true,
        navText:["<img src='"+ leftArrow +"'>", "<img src='"+ rightArrow +"'>"],
        navClass:['owl-prev', 'owl-next'],
        dots: false,
        autoplay:true,
        autoplayTimeout:3000,
        autoplayHoverPause:true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:1
            }
        }
    })


    jQuery('body').on( 'click', 'button.fcpfw_plus, button.fcpfw_minus', function() {
        
        jQuery('.fcpfw_body').addClass('fcpfw_loader');
        // return false;
        // Get current quantity values
        var qty  = jQuery( this ).closest( '.fcpfw_cart_prods' ).find( '.fcpfw_update_qty' );
        var val  = parseFloat(qty.val());
        var max  = 100000000000000;
        var min  = 1;
        var step = 1;

        // Change the value if plus or minus
        if ( jQuery( this ).is( '.fcpfw_plus' ) ) {
           if ( max && ( max <= val ) ) {
              qty.val( max );
           } else {
              qty.val( val + step );
           }
        } else {
           if ( min && ( min >= val ) ) {
              qty.val( min );
           } else if ( val > 1 ) {
              qty.val( val - step );
           }
        }

        var updateQty  = jQuery( this ).closest( '.fcpfw_cart_prods' ).find( '.fcpfw_update_qty' );
        var updateVal  = parseFloat(updateQty.val());
        var pro_id = jQuery(this).closest('.fcpfw_cart_prods').attr('product_id');
        var c_key = jQuery(this).closest('.fcpfw_cart_prods').attr('c_key');
        var pro_ida = jQuery(this);
        pro_ida.prop('disabled', true);
        
        jQuery.ajax({
            url:ajax_postajax.ajaxurl,
            type:'POST',
            data:'action=change_qty&c_key='+c_key+'&qty='+updateVal,
            success : function(response) {
                pro_ida.prop('disabled', false);
                jQuery( document.body ).trigger( 'wc_fragment_refresh' );
                jQuery('.fcpfw_body').removeClass('fcpfw_loader');
            }
        });
    });
})


jQuery(document).on('click', '.fcpfw_body a.fcpfw_remove', function (e) {
    e.preventDefault();

    jQuery('.fcpfw_body').addClass('fcpfw_loader');

    var product_id = jQuery(this).attr("data-product_id"),
        cart_item_key = jQuery(this).attr("data-cart_item_key"),
        product_container = jQuery(this).parents('.fcpfw_body');	

    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: ajax_postajax.ajaxurl,
        data: {
            action: "product_remove",
            product_id: product_id,
            cart_item_key: cart_item_key
        },
        success: function(response) {

            if ( ! response || response.error )
                return;

            var fragments = response.fragments;

            // Replace fragments
            if ( fragments ) {
                jQuery.each( fragments, function( key, value ) {
                    jQuery( key ).replaceWith( value );
                });
            }

            jQuery('.fcpfw_body').removeClass('fcpfw_loader');
        }
    });
});


jQuery(document).on('click', '.product_type_simple.add_to_cart_button', function () {
   
        
    
        var cart = jQuery('.fcpfw_cart_basket');
        var imgtodrag = jQuery(this).parent('.product').find("img").eq(0);
        if (imgtodrag) {
            var imgclone = imgtodrag.clone()
                .offset({
                top: imgtodrag.offset().top,
                left: imgtodrag.offset().left
            })
                .css({
                'opacity': '0.8',
                'position': 'absolute',
                'height': '150px',
                'width': '150px',
                'z-index': '100'
            })
                .appendTo(jQuery('body'))
                .animate({
                'top': cart.offset().top + 10,
                'left': cart.offset().left + 10,
                'width': 75,
                'height': 75
            }, 1000, 'easeInOutExpo');
            
            setTimeout(function () {
                cart.effect("shake", {
                times: 2
                }, 200);
            }, 1500);

            imgclone.animate({
                'width': 0,
                'height': 0
            }, function () {
                jQuery(this).detach()
            });
        } 

});


(function ($) {

    $(document).on('click', '.fcpfw_pslide_atc', function (e) {
        e.preventDefault();

        var $thisbutton = $(this),
            product_id = $thisbutton.attr('data-product_id'),
            product_qty =  $thisbutton.attr('data-quantity'),
            variation_id = $thisbutton.attr('variation-id'),
            product_container = $(this).parents('.fcpfw_body');

        var data = {
            action: 'fcpfw_prod_slider_ajax_atc',
            product_id: product_id,
            product_sku: '',
            quantity: product_qty,
            variation_id: variation_id,
        };

        $(document.body).trigger('adding_to_cart', [$thisbutton, data]);

        $.ajax({
            type: 'post',
            url: ajax_postajax.ajaxurl,
            data: data,
            beforeSend: function (response) {
                $('.fcpfw_body').addClass('fcpfw_loader');
            },
            complete: function (response) {
            },
            success: function (response) {
                if (response.error & response.product_url) {
                    window.location = response.product_url;
                    return;
                } else {
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                }
                $('.fcpfw_body').removeClass('fcpfw_loader');
            },
        });

        return false;
    });
})(jQuery);