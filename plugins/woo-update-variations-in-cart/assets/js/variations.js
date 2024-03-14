var cancel_update_variations;

jQuery(document).on('click', '.btnshow', function(){

	var cart_item_html;

	var target_item_row;

	target_item_row = jQuery(this).closest('tr');

	target_item_row.fadeTo(300, 0.3);

	var cart_item_key = jQuery(this).attr('id');

	jQuery.ajax({                                   

	    url: update_variation_params.ajax_url,               

	    type: 'POST',

	    dataType: 'html',

	    data: {

	        form_value: cart_item_key,

	        action: 'show_update_variations_in_cart_form'

	    },

	    success: function( result ) { 

	    	var form_present = jQuery('tr.new_item_'+cart_item_key).length;         

            if ( form_present == 0 ) {	        

		        target_item_row.after('<tr class="new_item_'+cart_item_key+'" id="new_item"><td colspan="6"><table class="update_variation_form"><tr><td id="thumbnail_'+cart_item_key+'" class="uvc_thumbnail"></td><td class="variations">'+result+'</td></tr></table></td></tr>').hide();

	            jQuery('tr.new_item_'+cart_item_key+' .variations_form').attr('id', 'uvc_form_'+cart_item_key);

		        target_item_row.addClass('old_item_'+cart_item_key).fadeOut(300);

		        //add thumbail to the form

		        var thumbnail = jQuery('tr.new_item_'+cart_item_key+' input#product_thumbnail').val();

		        jQuery('tr.new_item_'+cart_item_key+' td#thumbnail_'+cart_item_key).append(thumbnail);

	            //align the add to cart button

	            jQuery('tr.new_item_'+cart_item_key+ ' div.quantity').css('float','left');

		        var add_to_cart_button = jQuery('tr.new_item_'+cart_item_key+' button.single_add_to_cart_button');

	            add_to_cart_button.wrap('<div class="uvc_update_and_cancel_button"></div>');	        

		        jQuery('tr.new_item_'+cart_item_key+' button.single_add_to_cart_button').text(update_variation_params.update_text);

		        //align the cancel button

		        var cancel_button = jQuery('tr.new_item_'+cart_item_key+' span#cancel').detach(); 

		        jQuery('tr.new_item_'+cart_item_key+' div.uvc_update_and_cancel_button').append(cancel_button);

		        jQuery('tr.new_item_'+cart_item_key).fadeIn(300);

            }

	    }

	});

}); 		

cancel_update_variations = function( cart_item_key ){

	jQuery('tr.new_item_'+cart_item_key).remove();

	jQuery('tr.old_item_'+cart_item_key).fadeIn(150).fadeTo(150,1);

};

jQuery(document).on('click', ' #new_item .single_add_to_cart_button', function( e ){

	e.preventDefault();

	jQuery('#loder_img_btn').css('display','block');

	var form_id = this.form.id;

	var uvc_is_wc_gte_26 = jQuery('.variations_form input#uvc_is_wc_gte_26').val();

	jQuery.ajax({

		url: update_variation_params.ajax_url,

		type: 'post',

		dataType: 'html',

		data: {

			action: 'update_variations_in_cart',

			form_data: jQuery('#'+form_id).serialize()

		},

		success: function( response ) {

			jQuery('#loder_img_btn').css('display','none');

			var html       = jQuery.parseHTML( response );

			var new_form   = jQuery( 'table.shop_table.cart', html ).closest( 'form' );

			var new_totals = jQuery( '.cart_totals', html );

			jQuery( 'table.shop_table.cart' ).closest( 'form' ).replaceWith( new_form );

			if( uvc_is_wc_gte_26 == 'yes' ) {

			    jQuery( 'table.shop_table.cart' ).closest( 'form' ).find( 'input[name="update_cart"]' ).prop( 'disabled', true );

	 	    }

		    jQuery( '.cart_totals' ).replaceWith( new_totals );

		    if( jQuery('div.woocommerce-message').length == 0 ){

		        jQuery('div.entry-content div.woocommerce').prepend('<div class="woocommerce-message">'+update_variation_params.cart_updated_text+'</div>');

		    }

		}

	});

});