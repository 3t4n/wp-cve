jQuery(document).on('click', '.WOO_CK_WUVIC_buttom', function(){

	jQuery(this).parent().find('#loder_img').css('display','block');

	var cart_item_html;

	var current_item_product;

	current_item_product = jQuery(this).closest('tr');

	current_item_product.fadeTo(300,0);

	var cart_item_key = jQuery(this).attr('id');

	jQuery.ajax({                                   

	    async : true,

	    url: update_variation_params.ajax_url,               

	    type: 'POST',

	    dataType: 'html',

	    data: {

	        current_key_value: cart_item_key,

	        action: 'get_variation_form'

	    },

	    success: function( result ) { 

	    	jQuery('.product-name #loder_img').css('display','none');

	    	var form_present = jQuery('tr.new_row_'+cart_item_key).length;       

            if ( form_present == 0 ) {	        

		        current_item_product.after('<tr class="new_row_'+cart_item_key+'" id="new_row"><td colspan="6"><table class="update_variation_form"><tr><td id="thumbnail_'+cart_item_key+'" class="WOO_CK_WUVIC_thumbnail"></td><td class="variations">'+result+'</td></tr></table></td></tr>').hide();

	            jQuery('tr.new_row_'+cart_item_key+' .variations_form').attr('id', 'uvc_form_'+cart_item_key);

		        current_item_product.addClass('old_row_'+cart_item_key).fadeOut(300);

		        //add thumbail to the form

		        var thumbnail = jQuery('tr.new_row_'+cart_item_key+' input#product_thumbnail').val();

		        jQuery('tr.new_row_'+cart_item_key+' td#thumbnail_'+cart_item_key).append(thumbnail);

				jQuery('tr.new_row_'+cart_item_key).fadeIn(300);

            }

	    }

	});

}); 		

function cancel_update_variations( cart_item_key ){

	jQuery('tr.new_row_'+cart_item_key).remove();

	jQuery('tr.old_row_'+cart_item_key).fadeIn(150).fadeTo(150,1);

};

jQuery(document).on('click', ' #new_row .single_add_to_cart_button', function( e ){

	e.preventDefault();

	jQuery(this).parent().find('#loder_img_btn').css('display','block');

	var form_id = this.form.id;

	var old_key =jQuery("#"+form_id+' .old_key').val();

	jQuery.ajax({

		async : true,

		url: update_variation_params.ajax_url,

		type: 'post',

		dataType: 'html',

		data: {

			action: 'update_product_in_cart',

			form_data: jQuery('#'+form_id).serialize(),

			old_key : old_key

		},

		success: function( response ) {

			jQuery('#loder_img_btn').css('display','none');

			var html       = jQuery.parseHTML( response );

			var new_form   = jQuery( 'table.shop_table.cart', html ).closest( 'form' );

			var new_totals = jQuery( '.cart_totals', html );

			jQuery( 'table.shop_table.cart' ).closest( 'form' ).replaceWith( new_form );			

		    jQuery( '.cart_totals' ).replaceWith( new_totals );

		    if( jQuery('div.woocommerce-message').length == 0 ){

		        jQuery('div.entry-content div.woocommerce').prepend('<div class="woocommerce-message">'+update_variation_params.cart_updated_text+'</div>');

		    }

		}

	});

});