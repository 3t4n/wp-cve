//jquery tab
jQuery(document).ready(function(){
	jQuery('.add_to_cart_button').on('click', function(e){ 
		
		var pro_qty = jQuery(this).closest('.product').find(".qty_select").val();
		var product_id = jQuery(this).closest('.product').find(".product_id").val();
        jQuery.ajax({
	        url: ajax_postajax.ajaxurl,
	        type: 'POST',
	        data: {
	        	quantity : pro_qty,
	        	product : product_id,
	            action: 'pqdfw_get_refresh_fragments',

	        }
	    })
	    //return false;
    });	

    jQuery('.qty_select').change(function(){
    	jQuery(this).closest('li.product').find('a.ajax_add_to_cart').attr('data-quantity',jQuery(this).val());
    });
});

function extendons_selectbox(){
    jQuery('.qty').val(jQuery('#ss').val());
}