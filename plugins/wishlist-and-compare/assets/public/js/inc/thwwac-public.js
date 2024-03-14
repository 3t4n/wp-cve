var j = jQuery.noConflict();

function openpopup(product_id,logged_in,redirect_login,success_notice,ajax_loading,shop){
	j('.thwwac-add-btn').addClass('thwwac-disable-button');
	j('.thwwac-browse-btn').addClass('thwwac-disable-button');
	if(logged_in == 'yes'){
		j('#preloader'+product_id).css('display','inline-flex');
	}
	if(!shop && j('input[name="variation_id"]').val()){
		variation = j('input[name="variation_id"]').val();
	} else {
		variation = null;
	}
	j.ajax({
		type: "POST",
		url: thwwac_var.ajaxurl,
		data: {
			action: 'add_wishlist',
			product_id: product_id,
			logged_in: logged_in,
			variation_id: variation,
			addwishnonce: thwwac_var.addwishnonce
		},
		success: function(res){
			j('.thwwac-add-btn').removeClass('thwwac-disable-button');
			j('.thwwac-browse-btn').removeClass('thwwac-disable-button');
			if (logged_in == 'yes') {
				j('#thwwac_count').empty();
				j('#preloader'+product_id).css('display','none');
				if(res.show_count == 'true'){
					if (j('#thwwac_count').length) {
						j('#thwwac_count').append(res.count);
					} else {
						j('.thwwac-counter-btn').append(res.count_with_element);
					}
				}
				if (ajax_loading == 'true') {
					localStorage.setItem("th_process","wishlist");
					if (shop == 'shop') {
						if (success_notice == 'true') {
							show_popup(res.product_name);
						}
						added_success(product_id);
					} else {
						if (success_notice == 'true') {
							show_popup(res.product_name);
						}
						added_success_pdct(product_id);
					}
				} else {
					location.reload();
				}
			} else {
				if (redirect_login != 'false') {
					window.location.href = redirect_login;
				} else {
					j('#loginmodal'+product_id).css('display','block');
				}
			}
		}
	});
}
function added_success(product_id) {
    j('#browse'+product_id).show();
    j('#add'+product_id).hide();
}

function added_success_pdct(product_id) {
	j('.browse-btn-single'+product_id).show();
    j('.th_add_btn_single'+product_id).hide();
}

function show_popup(product_name) {
	j('#thwwc_modal').css('display','block');
	j('#thwwc_product_name').empty();
	j('#thwwc_product_name').append(product_name);
}

function closepopup(product_id){
	j('#thwwc_modal').css('display','none');
}

function closelogin(product_id){
	j('#preloader').css('display','none');
	j('#loginmodal'+product_id).css('display','none');
}

function remove_success(product_id) {
    j('#browse'+product_id).hide();
    j('#add'+product_id).show();
    j('.browse-btn-single'+product_id).hide();
    j('.th_add_btn_single'+product_id).show();
}

function redirect_to_page(redirect_link){
	window.location.href = redirect_link;
}
function thwwc_browse_action(btn_data){
	if (thwwac_var.remove_on_second_click) {
		var product_id = j(btn_data).data('product_id');
		remove_from_wishlist_second_click(product_id);
	} else {
		var redirect_link = j(btn_data).data('redirect_link');
		redirect_to_page(redirect_link);
	}
}

function delete_confirmation(product_id){
	j.ajax({
		type: "POST",
		url: thwwac_var.ajaxurl,
		data: {
			action: 'get_product_details',
			pdctdetailsnonce: thwwac_var.pdctdetailsnonce,
			product_id: product_id
		},
		success: function(res){
			j('#thwwc-pdct-details').empty();
			j('#thwwc-delete-confirm').show();
	    	j('#thwwac-confirm-txt').show();
	    	j('#thwwac-confirm-txt-all').hide();
			j('#thwwc-pdct-details').append(res);
			j('#thwwc-delete-productid').val(product_id);
		}
	});
}

function close_confirm(product_id){
	j('#thwwc-delete-confirm').hide();
}
function addallcart(){
	j('#thwwac-wishlist-table button').attr("disabled", true);
	j('#message').empty();
	j.ajax({
		type: "POST",
		url: thwwac_var.ajaxurl,
		data: {
			action: 'add_all_to_cart',
			allcartnonce:thwwac_var.allcartnonce
		},
		success: function(res){
			if(res.redirect_checkout == 'true' &&  res.added == true){
				window.location.href = res.checkouturl;
			}else{
				window.scrollTo(0, 200);
				location.reload();
			}
		},complete: function(){
    		j('#thwwac-wishlist-table button').attr("disabled", false);
    	}
	});
}

function wishlist_remove(){
	var product_id = j('#thwwc-delete-productid').val();
	j.ajax({
		type: "POST",
		url: thwwac_var.ajaxurl,
		data: {
			action: 'remove_wishlist',
			product_id: product_id,
			wishlist_page: true,
			removewishnonce: thwwac_var.removewishnonce
		},
		success: function(response){
			location.reload();
			window.scrollTo(0, 200);
		}
	});
}

function remove_from_wishlist_second_click(product_id){
	j.ajax({
		type: "POST",
		url: thwwac_var.ajaxurl,
		data: {
			action: 'remove_wishlist',
			product_id: product_id,
			wishlist_page: false,
			removewishnonce: thwwac_var.removewishnonce
		},
		success: function(response){
			remove_success(product_id);
			if(response.show_count == 'true'){
				j('#thwwac_count').empty();
				if (response.hide_zero == 'true' && response.count == 0){

				} else{
					j('#thwwac_count').append(response.count); 
				}
	                               
	        }
		}
	});
}

function addtocart_remove(product_id,remove){
	j('#thwwac-wishlist-table button').attr("disabled", true);
	j.ajax({
		type: "POST",
		url: thwwac_var.ajaxurl,
		data: {
			action: 'add_to_cart_remove',
			product_id: product_id,
			remove: remove,
			cartremovenonce: thwwac_var.cartremovenonce
		},
		success: function(res){
			window.scrollTo(0, 200);
			if(res.extra_option == true){
				location.reload();
			}else if(res.redirect_checkout == 'true'){
				window.location.href = res.checkouturl;
			}else{
				location.reload();
			}
		},complete: function(){
    		j('#thwwac-wishlist-table button').attr("disabled", false);
    	}		
	});
}

function select_all(){
	var checked = j("#select_all").prop("checked");
	j(".thwwac_case").each(function(){
		this.checked = checked;
	})
}
function unselect_all(){
	if(j(".thwwac_case").length == j(".thwwac_case:checked").length) {
        j("#select_all").prop('checked', true);
    } else {
        j("#select_all").removeAttr("checked");
    }
}
function action(){
    var select = j('#action-type').val();
    if(select == "add to cart"){
    	selected_to_cart(select);
    }else{
		var products = product_length();
		if( products.length > 0){
	    	j('#thwwc-delete-confirm').show();
	    	j('#thwwc-pdct-details').empty();
	    	j('#thwwac-confirm-txt').hide();
	    	j('#thwwac-confirm-txt-all').show();
	    } else {
	    	location.reload();
	    }
    }
}
function selecttocart(){
	var select = "add to cart";
	selected_to_cart(select);
}
function selected_to_cart(select){
	j('#thwwac-wishlist-table button').attr("disabled", true);
	var products = product_length();
    if( products.length > 0){
	    j.ajax({
	    	type: "POST",
	    	url: thwwac_var.ajaxurl,
	    	data: {
	    		action: 'multiple_action',
	    		products: products,
	    		select: select,
	  			multiactionnonce: thwwac_var.multiactionnonce
	    	},
	    	success: function(res){
	    		if( res.redirect_checkout == 'true' &&  res.added == true ){
					window.location.href = res.checkouturl;
				}else{
					window.scrollTo(0, 200);
					location.reload();
				}
	    	},complete: function(){
	    		j('#thwwac-wishlist-table button').attr("disabled", false);
	    	}
	    })
	}else{
		location.reload();
	}
}
function product_length(){
	var products = [];
    j.each(j("input[name='thwwac_case']:checked"), function(){
        products.push(j(this).val());
    });
    var filtered = products.filter(function (el) {
	  return el != null;
	});
	return filtered;
}
function show_icons(){
	j('#thwwc_share_icons').toggle("slide");
}
function copyclipboard(){
	var $body = document.getElementsByTagName('body')[0];
	var $btnCopy = document.getElementById('btnCopy');
	var secretInfo = document.getElementById('secretInfo').textContent;
	url = secretInfo.replace(/\&amp;/g,'&');

	var copyToClipboard = function(secretInfo) {
		var $tempInput = document.createElement('INPUT');
		$body.appendChild($tempInput);
		$tempInput.setAttribute('value', url)
		$tempInput.select();
		document.execCommand('copy');
		$body.removeChild($tempInput);
	}

	copyToClipboard(url);
	var tooltip = document.getElementById("myTooltip");
		tooltip.innerHTML = "Copied";
}

function compare_add(product_id,popup){
	event.preventDefault();
	j('.thwwc-compare-btn').addClass('thwwac-disable-button');
	j.ajax({
		type: "POST",
		url: thwwac_var.ajaxurl,
		data: {
			action: 'add_compare',
			product_id: product_id,
			addcmpnonce:thwwac_var.addcmpnonce
		},
		success:function(res){
			j('.thwwc-compare-btn').removeClass('thwwac-disable-button');
			j('#compare-popup').empty();
			j('#compare-popup').append(res.items_html);
			j('#thwwac_is_page').val(0);
			localStorage.setItem("th_process_compare","compare");
			if (popup == true) {
				j('#main-header').css('z-index','0');
				j('.fusion-header-wrapper').css('z-index','0');
				j('#comparemodal').css('display','block');
				j('body').css('overflow','hidden');
				thwwac_compare_btn(product_id);
			} else {
				thwwac_compare_btn(product_id);
			}
			var length = res.hide_default.fields.length;
			for (var i=0; i<length; i++) {
				j('.'+res.hide_default.fields[i]).css('display','none');
			}
		}
    })
}
function thwwac_compare_btn(product_id) {
	j('#compare'+product_id).css('display','none');
    j('#compare-btn'+product_id).css('display','none');
    j('#compare-added'+product_id).css('display','block');
}
function openmodal(permalink){
	j('#added-msg').empty();
	if( permalink == 'false' ){
		event.preventDefault();
		j('.fusion-header-wrapper').css('z-index','0');
		j('#main-header').css('z-index','0');
		j('#comparemodal').css('display','block');
		var marginTop = j(".thwwac-fixed-head h3").css('marginTop');
		var marginBottom = j(".thwwac-fixed-head h3").css('marginBottom');
		if(parseInt(marginTop) > 16.38 || parseInt(marginBottom) > 16.38){
			j(".thwwac-fixed-head h3").css('margin','auto');
		}
		j('body').css('overflow','hidden');
	}else{
		event.preventDefault();
		window.location.href = permalink;
	}
}
function close_comparemodal(){
	var added_to_cart = j('input[name="check_added_to_cart"]').val();
	if(added_to_cart == 'yes'){
		location.reload();
	}else{
		j('#comparemodal').css('display','none');
		j('body').css('overflow-y','scroll');
		j('.fusion-header-wrapper').css('z-index','10010');
		j('#main-header').css('z-index','99999');
	}
}
function add_to_cart(product_id,current_page){
	event.preventDefault();
	var current_page = j("#thwwac_is_page").val();
	j.ajax({
		type: "POST",
		url: thwwac_var.ajaxurl,
		data: {
			action: 'compare_addtocart',
			product_id: product_id,
			current_page: current_page,
			cmpcartnonce:thwwac_var.cmpcartnonce
		},
		success:function(res){
			window.scrollTo(0, 200);
			if(current_page == true){
				location.reload();
			}else{
				j('#added-msg').empty();
				j('#added-msg').css('height','0');
				if( res.stock_status == 'out_of_stock' ){
				    j('#added-msg').css('height','40px');
					j('#added-msg').append('<div class="woocommerce-error"><span>You cannot add that amount to the cart — we have '+res.cart_quantity+' in stock and you already have '+res.cart_quantity+' in your cart. </span><a href="'+res.carturl+'" class="button wc-forward">View cart</a></div>');
				}else{
				    j('#thwwc-view-cart'+product_id).show();
					j('#compare-addcart-btn'+product_id).hide();
					j('#check_added_to_cart').val('yes');
					j('.woocommerce-mini-cart__total').remove();
					j('.woocommerce-mini-cart__buttons').remove();
					j('#site-header-cart .count').empty();
					j('#site-header-cart .count').append(res.cart_count);
					j('a span.woocommerce-Price-amount').empty();
					j('a span.woocommerce-Price-amount').append(res.cart_total);
					if( res.quantity == 0 ){
						j('.woocommerce-mini-cart__empty-message').replaceWith(res.cart_contents);
					}
					else{
						j('.woocommerce-mini-cart').empty();
						j('.woocommerce-mini-cart').append(res.cart_contents);
					}
				}
			}
		}
	})
}
function compare_remove(product_id){
	var hide = j('input[name="differences"]:checked').val();
	if( hide == 'hide' ){
		hide = 'hide';
	}else{
		hide = 'show';
	}
	j.ajax({
		type: "POST",
		url: thwwac_var.ajaxurl,
		data: {
			action: 'remove_compare',
			product_id: product_id,
			remcmpnonce:thwwac_var.remcmpnonce
		},
		success:function(response){
			var obj = response;
			j('#compare-btn'+product_id).css('display','block');
			j('#added'+product_id).css('display','none');
			j('#compare-added'+product_id).css('display','none');
			if( obj.count == 0 ){
				j('.thwwac_hide_show').css('display','none');
				j('.thwwac_hide_show_page').css('display','none');
				j('#compare-popup').empty();
				j('#compare-popup').append(obj.empty_compare);
				j('#compare_widget').empty();
				j('#compare_widget').append(obj.empty_compare);
				j('#thwwc-compare-page').empty();
				j('#thwwc-compare-page').append(obj.empty_compare);
			}else{
				j('#thc_row'+product_id).css('display','none');
				j('#compare_column'+product_id).css('display','none');
				j('.thwwc_col_'+product_id).css('display','none');
			}
			var length = obj.fields.length;
			if (obj.hide_by_default == true) {
				for( var i=0; i<length; i++ ){
					j('.'+obj.fields[i]).css('display','none');
				}
			}
			else if( obj.count > 0 ){
				if( hide == 'hide' ){
					for( var i=0; i<length; i++ ){
						j('.'+obj.fields[i]).css('display','none');
					}
				}else{
					for( var i=0; i<length; i++ ){
						j('.'+obj.fields[i]).css('display','inline-flex');
					}
				}
			}
		}
    })
}
function hide_show(){
	var hide = j('input[name="differences"]:checked').val();
	if( hide == 'hide' ){
		hide = 'hide';
	}else{
		hide = 'show';
	}
	j.ajax({
		type: "POST",
		url: thwwac_var.ajaxurl,
		data: {
			action: 'hide_show',
			cmphsnonce:thwwac_var.cmphsnonce
		},
		success:function(response){
			var obj = response;
			var length = obj.fields.length;
			if( obj.count > 0 ){
				if( hide == 'hide' ){
					for( var i=0; i<length; i++ ){
						j('.'+obj.fields[i]).css('display','none');
					}
				}else{
					for( var i=0; i<length; i++ ){
						j('.'+obj.fields[i]).css('display','inline-flex');
					}
				}
			}
		}
	})
}
function grid_view(data){
	grid_list_active_class(data);
	j('#thwwac-wishlist').addClass('thwwc-wishlist-grid-view');
	window.history.replaceState(null, null, "?thwwc_view=grid");
}
function list_view(data){
	grid_list_active_class(data);
	j('#thwwac-wishlist').removeClass('thwwc-wishlist-grid-view');
	window.history.replaceState(null, null, "?thwwc_view=list");
}

function grid_list_active_class(data){
	j(".thwwc-listing").each(function(){
		j(this).addClass('thwwc-listing-inactive');
	})
	j(data).removeClass('thwwc-listing-inactive');
}

// j(document).ready(function(){
// 	var product_id_arr = [];
// 	j('#thwwc-resp-table-body').sortable({
// 		axis: 'y',
// 		update: function (event, ui) {
// 			var count = j('.thwwac_case').length - 1;
// 			order = count;
// 			j('.thwwac_case').each(function(value){
// 				value = j(this).val();
// 				product_id_arr.push({'order' : order, 'product_id' : value});
// 				order = order - 1;						
// 			}),
// 			settings = {
// 				value = product_id_arr,
// 				action: 'thwwac_drag_and_drop',
// 			}
// 			// POST to server using $.post or $.ajax
// 			j.ajax({
// 	  			data: settings,
// 	  			type: 'POST',
// 	  			url: thwwac_var.ajaxurl,
// 	  		});
// 	    },
// 	});
// });

j(document).ready(function(){
	j('#thwcc-filter-select').change(function(){
		var filter = j('#thwcc-filter-select');
		var settings = {
			value = filter.val(),
			action: 'filter_wishlisted_products',
			filternonce: thwwac_var.filternonce,
		}
		j.ajax({
			url: thwwac_var.ajaxurl,
			data: settings,
			type:'POST', 
			success:function(data){
				if(data.success){
					j('#thwwc-resp-table-body').html(data.html);
				}else{
					j('#thwwc-resp-table-body').html(data.html);
				}				
			},
			error: function(){
				alert('error');
			}
		});
		return false;
	});
});

j(document).ready(function(){
	j(".single_variation_wrap").on("show_variation", function(event, variation) {
	    j('a.thwwc-wish-btn').removeClass('thwwc_btn_disable');
	    j('.thwwac-wishlist-single').removeClass('thwwc_cursor_na');
        j.ajax({
            type: "POST",
            url: thwwac_var.ajaxurl,
            data: {
                action: 'selected_variation_action',
                variationnonce :thwwac_var.variationnonce,
                variation: variation.variation_id
            },
            success: function(response){
                j('#'+response.show).show();
                j('#'+response.hide).hide();
            }
        });
	});

	j(".variations_form").on("woocommerce_variation_select_change", function() {
	    j('a.thwwc-wish-btn').addClass('thwwc_btn_disable');
	    j('.thwwac-wishlist-single').addClass('thwwc_cursor_na');
	});

	if(j('.theme-Avada .product-buttons-container:has(.wishlist-btn)').length > 0 || j('.theme-Avada .product-buttons-container:has(.thwwac-browse-btn)').length > 0 || j('.theme-Avada .product-buttons-container:has(.thwwc-compare-btn)').length > 0){
	    j('.product-buttons-container').addClass('th_grid_display');
    }

    if(j(".woocommerce-product-gallery").length !== 0){
    	j('.thwwc-compare-btn.thwwc-bottom-right-thumb-variable-pdct').detach().appendTo(".woocommerce-product-gallery");
    	j('.added_btn.thwwc-bottom-right-thumb-variable-pdct').detach().appendTo(".woocommerce-product-gallery");
    }

    var process_type = localStorage.getItem('th_process');
    var compare_process = localStorage.getItem('th_process_compare');
    // if (process_type === "wishlist") {
        j.ajax({
            type: "POST",
            url: thwwac_var.ajaxurl,
            data: {
                action: 'update_on_back_press',
                backclicknonce:thwwac_var.backclicknonce
            },
            success: function(response){
                var obj = response;
                var all_product = obj.all_products;
                j('#thwwac_count').empty();
                if (all_product !=null) {
	                for(var i = 0; i < obj.all_products.length; i++){
	                    added_success(obj.all_products[i]);
	                    added_success_pdct(obj.all_products[i]);
	                }
	                if(obj.show_count == 'true'){
	                    j('#thwwac_count').append(obj.count);                    
	                }
	            }else{
	            	if(obj.show_count == 'true'){
	                    j('#thwwac_count').append(obj.count);                    
	                }
	            }
            }
        });
    // }
    // if (compare_process === "compare") {
        j.ajax({
            type: "POST",
            url: thwwac_var.ajaxurl,
            data: {
                action: 'update_compare_on_back',
                upcmpnonce:thwwac_var.upcmpnonce
            },
            success:function(response){
            	var obj = response;
                var all_product = obj.all_products;
                j('#compare-popup').empty();
                j('#compare-popup').append(obj.items_html);
                if (all_product != null) {
	                for(var i = 0; i < obj.all_products.length; i++){
	                    thwwac_compare_btn(obj.all_products[i]);
	                }
	            }
            }
        });
    // }
})