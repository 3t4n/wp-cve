function prestashop_integration_insert_products(products, del_href) {
	if (products) {
		for (i = 0; i < products.length; i++) {
			jQuery('#prestashop_integration_products').append('<p><span><a href="'+del_href+'&product_id='+products[i].id+'" class="del_link">X</a>&nbsp;<b>'+products[i].id+'</b>: '+products[i].name+'</span></p>');
		}
	}
}

jQuery(document).ready(function($){
	if ($("#prestashop_integration_products").length) {
		var post_id = $("input#post_ID").val();
		var nonce = $("input#prestashop_integration_nonce").val();
		var data = {
			action: 'prestashop_integration_list_product',
			post_id: post_id
		}
		$.post( prestashop_integration.ajaxurl, data, function(data) {
			var status = $(data).find('response_data').text();
			var json_products = eval('(' + $(data).find('supplemental json_products').text() + ')');
			if ( status == 'success' && json_products.products ) {
				prestashop_integration_insert_products(json_products.products, 'post.php?action=edit&message=1&post='+post_id+'&nonce='+nonce);
			}
		});

		$("input#prestashop_integration_add_product_button").click(function(){
			var post_id = $("input#post_ID").val();
			var product_id = $("input#prestashop_integration_product_id").val();
			var nonce = $("input#prestashop_integration_nonce").val();
			var data = {
				action: 'prestashop_integration_add_product',
				post_id: post_id,
				product_id: product_id,
				nonce: nonce
			}
			$.post( prestashop_integration.ajaxurl, data, function(data) {
				var status = $(data).find('response_data').text();
				var json_products = eval('(' + $(data).find('supplemental json_products').text() + ')');
				if ( status == 'success' && json_products.products ) {
					prestashop_integration_insert_products(json_products.products, 'post.php?action=edit&message=1&post='+post_id+'&nonce='+nonce);
				}
			});
		});
	}
});

jQuery(document).on("click", "#prestashop_integration_products a.del_link", function(){
	var link = this;
	var href = jQuery(link).attr('href');
	var post_id = href.replace(/^.*post=(\d+).*$/, '$1');
	var product_id = href.replace(/^.*product_id=(\d+).*$/, '$1');
	var nonce = href.replace(/^.*nonce=([a-z0-9]+).*$/, '$1');
	var data = {
		action: 'prestashop_integration_del_product',
		post_id: post_id,
		product_id: product_id,
		nonce: nonce
	}
	jQuery.post( prestashop_integration.ajaxurl, data, function(data) {
		var status = jQuery(data).find('response_data').text();
		if ( status == 'success' ) {
			jQuery(link).parent().remove();
		}
	});

	return false;
});
