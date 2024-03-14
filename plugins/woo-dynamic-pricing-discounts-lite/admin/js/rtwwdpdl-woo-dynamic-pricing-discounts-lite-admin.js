(function ($) {
	'use strict';
	$(function () {
		// / hide offer banner /
		$(".rtw_sb_close_popup").on("click", function () {
			$(".rtw_sb_popup").fadeOut();
		});
		// / hide offer banner /
		$(".rtw_close_popup").on("click", function () {
			$(".rtw_popup").hide();
		});
		$(document).find(".rtwwdpdl_prod_table_edit").hide();
		$(document).find(".rtwwdpdl_prod_c_table_edit").show();
		$(document).find(".rtwwdpdl_prod_table").show();
		$(document).find(".rtwwdpdl_prod_c_table").hide();
		$(document).find(".rtwwdpdl_cat_table").show();
		$(document).find(".rtwwdpdl_cat_c_table").hide();
		$(document).find('.rtwtable').DataTable({
			"order": [],
			"columnDefs": [{ orderable: false, targets: [0] }],
		});
		jQuery('.woocommerce-help-tip').tipTip({
			'attribute': 'data-tip',
			'fadeIn': 50,
			'fadeOut': 50,
			'delay': 200
		});
		$(document).on('change', '#rtwwdpdl_check_for_cat', function () {
			var val = $(this).find("option:selected").text();
			$(document).find("[for=rtwwdpdl_min_cat]").html('Minimum ' + val);
			$(document).find("[for=rtwwdpdl_max_cat]").html('Maximum ' + val);
		});
		$(document).on('change', '#rtwwdpdl_dscnt_cat_type', function () {
			var val = $(this).find("option:selected").text();
			$(document).find("[for=rtwwdpdl_dscnt_cat_val]").html(val);
		});
		$(document).on('change', '#rtwwdpdl_rule_on', function () {
			var val = $(this).val();
			if (val == 'rtwwdpdl_products') {
				$(this).closest('tr').next('tr').show();
			}
			else if (val == 'rtwwdpdl_cart') {
				$(this).closest('tr').next('tr').hide().val('');
			}
		});
		$(document).on('change', '#rtwwdpdl_rule_on', function () {
			var val = $(this).val();
			if (val == 'rtwwdpdl_products') {
				// $(this).closest('tr').next('tr').show();
				$(document).find('.multiple_product_ids').hide();
				$(document).find('#product_id').show();
			} else if (val == 'rtwwdpdl_cart') {
				// $(this).closest('tr').next('tr').hide().val('');
				$(document).find('.multiple_product_ids').hide();
				$(document).find('#product_id').hide();
			} else if (val == 'rtwwdpd_multiple_products') {
				// $(this).closest('tr').next('tr').next('tr').hide().val('');
				$(document).find('.multiple_product_ids').show();
				$(document).find('#product_id').hide();
			}
		});
		var selected_val = $(document).find('#rtwwdpdl_rule_on').val();
		// if (selected_val == 'rtwwdpd_cart') {
		//     $(document).find('#rtwwdpd_rule_on').closest('tr').next('tr').hide().val('');
		// }
		if (selected_val == 'rtwwdpdl_products') {
			// $(this).closest('tr').next('tr').show();
			$(document).find('.multiple_product_ids').hide();
			$(document).find('#product_id').show();
		} else if (selected_val == 'rtwwdpdl_cart') {
			// $(this).closest('tr').next('tr').hide().val('');
			$(document).find('.multiple_product_ids').hide();
			$(document).find('#product_id').hide();
		} else if (selected_val == 'rtwwdpd_multiple_products') {
			// $(this).closest('tr').next('tr').next('tr').hide().val('');
			$(document).find('.multiple_product_ids').show();
			$(document).find('.rtwwdpdl_products').hide();
		}
		$(document).on('click', '.rtwwdpdl_cancel_rule', function () {
			$(document).find(".rtwwdpdl_add_single_rule").hide();
			$(document).find(".rtwwdpdl_add_combi_rule").hide();
			$(document).find(".rtwwdpdl_add_combi_rule_tab").hide();
			$(document).find(".rtwwdpdl_bogo_combi_tab").hide();
			$(document).find(".rtwwdpdl_single_bogo_rule_tab").hide();
			$(document).find(".rtwwdpdl_combi_cat_tab").hide();
			$(document).find(".rtwwdpdl_single_cat_rule").hide();
			$(document).find(".rtwwdpdl_add_tier_cat_rule_tab").hide();
			$(document).find("#rtwwdpdl_tiered_rule_tab").hide();
		});
		$(document).find(".date-picker").datepicker({
			dateFormat: "dd-mm-yy",
			minDate: 0
		});
		$(document).find('#category_id').select2();
		$(document).find('#rtwwdpdl_category_id').select2();
		$(document).find('#category_id_free').select2();
		$(document).find(".rtwwdpdl_payment_method").select2();
		$(document).find(".rtwwdpdl_ship_method").select2();
		$(document).find('#category_combi_id').select2();
		$(document).find('.rtwwdpdl_select_roles').select2();
		$(document).find('.rtwwdpdl_select_roles_field').select2();
	});
	$(document).on('click', '#rtwinsertbtnbogo', function () {
		var row_no = (jQuery('#rtwproduct_table >tbody >tr').length) + 1;
		var select = '<select id="rtwproduct' + row_no + '" class="wc-product-search rtwwdpdl_prod_tbl_class"  name="product_id[]" data-placeholder="Search for a product" data-action="woocommerce_json_search_products_and_variations" data-multiple="false" ></select>';
		var quant = '<input type="number" min="0" name="combi_quant[]" value=""  />';
		var remove = '<a class="button insert remove" name="deletebtn" >Remove</a>';
		$('#product_list_body').append('<tr><td>' + row_no + '</td><td>' + select + '</td><td>' + quant + '</td><td>' + remove + '</td></tr>');
		jQuery('#rtwproduct' + row_no).trigger('wc-enhanced-select-init');
	});
	$(document).on('click', '#rtwinsert_product', function () {
		var row_no = (jQuery('#rtw_for_product >tbody >tr').length) + 1;
		var select = '<select id="rtwproduct' + row_no + '" class="wc-product-search rtwwdpdl_prod_tbl_class"  name="product_id[]" data-placeholder="Search for a product" data-action="woocommerce_json_search_products_and_variations" data-multiple="false" ></select>';
		var quant = '<input type="number" min="0" name="quant_pro[]" value=""  />';
		var remove = '<a class="button insert remove" name="deletebtn" >Remove</a>';
		$('#rtw_product_body').append('<tr><td>' + row_no + '</td><td>' + select + '</td><td>' + quant + '</td><td>' + remove + '</td></tr>');
		jQuery('#rtwproduct' + row_no).trigger('wc-enhanced-select-init');
	});
	$(document).on('click', '.remove', function () {
		var row_no = 1;
		$(document).find('#rtwproduct_table tbody tr').each(function () {
			$(this).find('td:first-child').text(row_no);
			row_no = row_no + 1;
		});
		$(this).closest('tr').remove();
	});
	////////// for insertion of search product field for bogo rule /////////////
	$(document).on('click', '#rtwinsert_bogo_pro', function () {
		var row_no = (jQuery('#rtwbogo_table_pro >tbody >tr').length) + 1;
		var select = '<select id="rtwproduct_' + row_no + '" class="wc-product-search rtwwdpdl_prod_tbl_class"  name="rtwbogo[]" data-placeholder="Search for a product" data-action="woocommerce_json_search_products_and_variations" data-multiple="false" ></select>';
		var quant = '<input type="number" min="0" name="bogo_quant_free[]" value=""  />';
		var remove = '<a class="button insert remove" name="deletebtn" >Remove</a>';
		$('#rtw_bogo_row').append('<tr><td>' + row_no + '</td><td>' + select + '</td><td>' + quant + '</td><td>' + remove + '</td></tr>');
		jQuery('#rtwproduct_' + row_no).trigger('wc-enhanced-select-init');
	});
	$(document).on('click', '.remove_pro_bogo', function () {
		var row_no = 1;
		$(document).find('#rtwbogo_table_pro tbody tr').each(function () {
			$(this).find('td:first-child').text(row_no);
			row_no = row_no + 1;
		});
		$(this).closest('tr').remove();
	});
	////////// for insertion of search categorie field in upcoming sale setting /////////////
	$(document).on('click', '#rtwinsert_category', function () {
		var row_no = (jQuery('#rtw_for_category >tbody >tr').length) + 1;
		var clone = $("#rtw_tbltr").clone().prop('id', 'rtw_tbltr' + row_no);
		clone.find(".select2-container").remove();
		clone.find("#rtw_tbltr").attr("id", "rtw_tbltr-" + row_no);
		clone.find("#td_row_no").text(row_no);
		clone.find("#td_remove").html('<a class="button insert remove_cat" name="deletebtn" >Remove</a>');
		clone.find("#td_row_no").attr("id", "td_row_no-" + row_no);
		clone.find("#category_id").val('');
		clone.find(".rtwtd_quant").val('');
		clone.find("#category_id").attr("id", "category_id-" + row_no);
		$("#rtw_category_body").append(clone);
		$(document).find("#category_id-" + row_no).select2();
	});
	$(document).on('click', '.remove_cat', function () {
		var row_nos = (jQuery('#rtwcat_table >tbody >tr').length);
		var text = $(this).parent().siblings(":first").text();
		if (row_nos != 1 && text != 1) {
			var row_no = 1;
			$(document).find('#rtwcat_table tbody tr').each(function () {
				$(this).find('td:first-child').text(row_no);
				row_no = row_no + 1;
			});
			$(this).closest('tr').remove();
		}
		else {
			alert('Minimum One category required.');
		}
	});
	$(document).on('click', '#rtwmsg_set', function () {
		rtwwdpdl_select(this, '#rtwmsg_set_tab');
	});
	$(document).on('click', '#rtwproduct_rule', function () {
		rtwwdpdl_select(this, '#rtwwdpdl_rule_tab');
	});
	$(document).on('click', '#rtwproduct_restrict', function () {
		rtwwdpdl_select(this, '#rtwwdpdl_restriction_tab');
	});
	$(document).on('click', '#rtwproduct_validity', function () {
		rtwwdpdl_select(this, '#rtwwdpdl_time_tab');
	});
	$(document).on('click', '#rtwbogo_rule', function () {
		rtwwdpdl_select(this, '#rtwbogo_rule_tab');
	});
	$(document).on('click', '#rtwbogo_restrict', function () {
		rtwwdpdl_select(this, '#rtwbogo_restrict_tab');
	});
	$(document).on('click', '#rtwbogo_validity', function () {
		rtwwdpdl_select(this, '#rtwbogo_validity_tab');
	});
	$(document).on('click', '#rtwproduct_rule_combi', function () {
		rtwwdpdl_select(this, '#rtwwdpdl_rule_tab_combi');
		$(document).find('.rtwwdpdl_rule_tab_combi').addClass('active');
		$(document).find('.rtwwdpdl_restriction_tab_combi').removeClass('active');
		$(document).find('.rtwwdpdl_time_tab_combi').removeClass('active');
	});
	$(document).on('click', '#rtwproduct_restrict_combi', function () {
		rtwwdpdl_select(this, '#rtwwdpdl_restriction_tab_combi');
		$(document).find('.rtwwdpdl_restriction_tab_combi').addClass('active');
		$(document).find('.rtwwdpdl_rule_tab_combi').removeClass('active');
		$(document).find('.rtwwdpdl_time_tab_combi').removeClass('active');
	});
	$(document).on('click', '#rtwproduct_validity_combi', function () {
		rtwwdpdl_select(this, '#rtwwdpdl_time_tab_combi');
		$(document).find('.rtwwdpdl_time_tab_combi').addClass('active');
		$(document).find('.rtwwdpdl_restriction_tab_combi').removeClass('active');
		$(document).find('.rtwwdpdl_rule_tab_combi').removeClass('active');
	});
	$(document).on('click', '#rtwgnrl_set', function () {
		rtwwdpdl_select(this, '#rtwgnrl_set_tab');
	});
	$(document).on('click', '#rtwprice_set', function () {
		rtwwdpdl_select(this, '#rtwprice_set_tab');
	});
	$(document).on('click', '#rtwoffer_set', function () {
		rtwwdpdl_select(this, '#rtwoffer_set_tab');
	});
	$(document).on('click', '#rtwbogo_set', function () {
		rtwwdpdl_select(this, '#rtwbogo_set_tab');
	});
	$(document).on('click', '#rtwtier_rule', function () {
		rtwwdpdl_select(this, '#rtwwdpdl_tiered_rule_tab');
	});
	$(document).on('click', '#rtwtier_restrict', function () {
		rtwwdpdl_select(this, '#rtwwdpdl_tiered_restr_tab');
	});
	$(document).on('click', '#rtwtier_validity', function () {
		rtwwdpdl_select(this, '#rtwwdpdl_tiered_time_tab');
	});
	$(document).on('click', '#rtwcat_rule', function () {
		rtwwdpdl_select(this, '#rtwcat_rule_tab');
	});
	$(document).on('click', '#rtwcat_restrict', function () {
		rtwwdpdl_select(this, '#rtwcat_restriction_tab');
	});
	$(document).on('click', '#rtwcat_validity', function () {
		rtwwdpdl_select(this, '#rtwcat_time_tab');
	});
	$(document).on('click', '#rtwcat_com_rule', function () {
		rtwwdpdl_select(this, '#rtwcat_com_rule_tab');
	});
	$(document).on('click', '#rtwcat_com_rest', function () {
		rtwwdpdl_select(this, '#rtwcat_com_rest_tab');
	});
	$(document).on('click', '#rtwcat_com_time', function () {
		rtwwdpdl_select(this, '#rtwcat_com_time_tab');
	});
	$(document).on('click', '#rtwwdpdl_adv', function () {
		rtwwdpdl_select(this, '#rtwwdpdl_adv_tab');
	});
	$(document).on('click', '#rtw_speci', function () {
		$(document).find('#edit_chk').val('save');
		$(document).find('.rtwwdpdl_save_rule').val('Save Rule');
	});
	////////////////////////////////////////////////////////////////////////////
	var rtw_datatable;
	////////////////////for editing cart rule table data////////////////////
	$(document).on('click', '#rtwwdpdl_single_cart_rule', function () {
		$(document).find('#edit_chk_cart').val('save');
		$(document).find('.rtwwdpdl_save_rule').val('Save Rule');
	});
	//////////////////for editing single category rule table data///////////////
	$(document).on('click', '#rtwwdpdl_single_cat', function () {
		$(document).find('#rtw_save_single_cat').val('save');
		$(document).find('.rtwwdpdl_save_cat').val('Save Rule');
	});
	////////////////////for editing variation rule table data////////////
	$(document).ready(function () {
		$(document).find('#rtw_min_quant').hide();
		$(document).find('#rtw_min_price').hide();
		$(document).find('.rtwwdpdl_combi_cat_tab').hide();
		$(document).find('#rtwwdpdl_rule_tab_combi').hide();
		$(document).find('.rtwwdpdl_bogo_c_table').hide();
		$(document).find('.rtwwdpdl_prod_c_table').hide();
		$(document).find('.rtwwdpdl_cat_c_table').hide();
		$(document).find('.rtwwdpdl_add_combi_rule_tab').hide();
		$(document).find('.rtwwdpdl_combi_cat_tab').hide();
		$(document).find('.rtwwdpdl_bogo_combi_tab').hide();
		$(document).find('.rtwwdpdl_add_single_rule').hide();
		$(document).find('.rtwwdpdl_single_cat_rule').hide();
		$(document).find('.rtwwdpdl_add_tier_cat_rule_tab').hide();
		$(document).find('.rtwwdpdl_tier_c_table').hide();
		$(document).find('.rtwwdpdl_bogo_edit_table').show();
		$(document).find('.rtwwdpdl_bogo_c_edit_table').show();
		$(document).find('.rtwwdpdl_add_tier_pro_rule_tab').hide();
		$(document).find('#rtwwdpdl_attribute_val_size').hide();
		$(document).find('#rtwwdpdl_attribute_val_col').hide();
		$(document).find('.rtwwdpdl_single_bogo_rule_tab').hide();
		$(document).find('#rtw_for_product').show();
		$(document).find('#rtw_for_category').hide();
		$(document).find('.rtw_if_prod').hide();
		$(document).find('.rtw_if_cat').hide();
		$(document).find('.rtwwdpdl_active').show();
		$(document).find('#rtwoffer_set_tab').hide();
		$(document).find('#rtwbogo_set_tab').hide();
		$(document).find('#rtwwdpdl_restriction_tab_combi').hide();
		$(document).find('#rtwwdpdl_time_tab_combi').hide();
		$(document).find('#rtwbogo_restrict_tab').hide();
		$(document).find('#rtwbogo_validity_tab').hide();
		$(document).find('.rtwwdpdl_prod_c_table_edit').show();
		if ($(document).find('#rtwwdpdl_edit_combi_prod').hasClass('rtwwdpdl_prod_c_table_edit')) {
			if (!rtw_datatable) {
				rtw_datatable = $(document).find('.rtwtables').DataTable({
					"order": [],
					"columnDefs": [{ orderable: false, targets: [0] }],
				});
			}
			$(document).find('.rtwwdpdl_tier_pro_table').hide();
			$(document).find('.rtwwdpdl_bogo_table').hide();
			$(document).find('.rtwwdpdl_prod_table').hide();
		}
		$("#rtw_setting_tbl tbody").sortable({
			handle: 'td.rtwupdwn',
			stop: function (event, ui) {
			}
		});
		$("#rtw_setting_tbl tbody").disableSelection();
		$('#rtw_setting_tbl tbody > tr').click(function () {
			var row = 1;
			$('#rtw_setting_tbl tbody  > tr').each(function () {
				$(this).find('.rtwrow_no').val(row);
				row = row + 1;
			});
		});
		$(".rtwtable tbody").sortable({
			handle: 'td.rtw_drag',
			stop: function (event, ui) {
			}
		});
		$(".rtwtable tbody").disableSelection();
		$(".rtwtables tbody").sortable({
			handle: 'td.rtw_drag',
			stop: function (event, ui) {
			}
		});
		$(".rtwtables tbody").disableSelection();
		$(document).on('click', '.rtw_drag', function () {
			var data_val = $(this).closest('table').data('value');
			var rtw_arry = [];
			if (data_val == 'categor') {
				$('.rtwtable tbody > tr').each(function () {
					var val = $(this).data('val');
					rtw_arry.push(val);
				});
				var data = {
					action: 'rtw_cat_tbl',
					table: 'category_tbl',
					rtwarray: rtw_arry,
					security_check: rtwwdpdl_ajax.rtwwdpdl_nonce
				};
				$.ajax({
					url: rtwwdpdl_ajax.ajax_url,
					type: "POST",
					data: data,
					dataType: 'json',
					success: function (response) {
					}
				});
			}
			else if (data_val == 'prodct') {
				$('.rtwtable tbody > tr').each(function () {
					var val = $(this).data('val');
					rtw_arry.push(val);
				});
				var data = {
					action: 'rtw_cat_tbl',
					table: 'prodct_tbl',
					rtwarray: rtw_arry,
					security_check: rtwwdpdl_ajax.rtwwdpdl_nonce
				};
				$.ajax({
					url: rtwwdpdl_ajax.ajax_url,
					type: "POST",
					data: data,
					dataType: 'json',
					success: function (response) {
					}
				});
			}
			else if (data_val == 'tier_pro_tbl') {
				$('.rtwtable tbody > tr').each(function () {
					var val = $(this).data('val');
					rtw_arry.push(val);
				});
				var data = {
					action: 'rtw_cat_tbl',
					table: 'tier_pro_tbl',
					rtwarray: rtw_arry,
					security_check: rtwwdpdl_ajax.rtwwdpdl_nonce
				};
				$.ajax({
					url: rtwwdpdl_ajax.ajax_url,
					type: "POST",
					data: data,
					dataType: 'json',
					success: function (response) {
					}
				});
			}
			else if (data_val == 'pay_tbl') {
				$('.rtwtable tbody > tr').each(function () {
					var val = $(this).data('val');
					rtw_arry.push(val);
				});
				var data = {
					action: 'rtw_cat_tbl',
					table: 'pay_tbl',
					rtwarray: rtw_arry,
					security_check: rtwwdpdl_ajax.rtwwdpdl_nonce
				};
				$.ajax({
					url: rtwwdpdl_ajax.ajax_url,
					type: "POST",
					data: data,
					dataType: 'json',
					success: function (response) {
					}
				});
			}
			else if (data_val == 'cart_tbl') {
				$('.rtwtable tbody > tr').each(function () {
					var val = $(this).data('val');
					rtw_arry.push(val);
				});
				var data = {
					action: 'rtw_cat_tbl',
					table: 'cart_tbl',
					rtwarray: rtw_arry,
					security_check: rtwwdpdl_ajax.rtwwdpdl_nonce
				};
				$.ajax({
					url: rtwwdpdl_ajax.ajax_url,
					type: "POST",
					data: data,
					dataType: 'json',
					success: function (response) {
					}
				});
			}
			else if (data_val == 'bogo_tbl') {
				$('.rtwtable tbody > tr').each(function () {
					var val = $(this).data('val');
					rtw_arry.push(val);
				});
				var data = {
					action: 'rtw_cat_tbl',
					table: 'bogo_tbl',
					rtwarray: rtw_arry,
					security_check: rtwwdpdl_ajax.rtwwdpdl_nonce
				};
				$.ajax({
					url: rtwwdpdl_ajax.ajax_url,
					type: "POST",
					data: data,
					dataType: 'json',
					success: function (response) {
					}
				});
			}
		});
	});
	$(document).on('change', '.rtwwdpdl_rule_on', function () {
		var val = $(this).val();
		if (val == 'rtw_amt') {
			$(document).find('#rtw_min_quant').hide();
			$(document).find('#rtw_min_price').show();
		}
		if (val == 'rtw_quant') {
			$(document).find('#rtw_min_price').hide();
			$(document).find('#rtw_min_quant').show();
		}
		if (val == 'rtw_both') {
			$(document).find('#rtw_min_price').show();
			$(document).find('#rtw_min_quant').show();
		}
	});
	$(document).on('change', '#rtwwdpdl_rule_on_plus', function () {
		var val = $(this).val();
		if (val == 'rtw_amt') {
			$(document).find('#rtw_min_quant').hide();
			$(document).find('#rtw_min_price').show();
		}
		if (val == 'rtw_quant') {
			$(document).find('#rtw_min_price').hide();
			$(document).find('#rtw_min_quant').show();
		}
		if (val == 'rtw_both') {
			$(document).find('#rtw_min_price').show();
			$(document).find('#rtw_min_quant').show();
		}
	});
	$(document).on('click', '.rtwwdpdl_single_prod_rule', function () {
		$(document).find('#edit_chk_single').val('save');
		$(document).find('.rtwwdpdl_prod_rule_tab').addClass('active');
		$(document).find('.rtwwdpdl_restriction_tab').removeClass('active');
		$(document).find('.rtwwdpdl_time_tab').removeClass('active');
		$(document).find('.rtwwdpdl_save_rule').val('Save Rule');
		$(document).find('.rtwwdpdl_add_single_rule').show();
		$(document).find('#rtwwdpdl_rule_tab').show();
		$(document).find('.rtwwdpdl_prod_rule_tab').show();
		$(document).find('.rtwwdpdl_add_combi_rule_tab').hide();
		$(document).find('#rtwwdpdl_restriction_tab_combi').hide();
		$(document).find('#rtwwdpdl_time_tab_combi').hide();
		$(document).find('#rtwwdpdl_restriction_tab').hide();
		$(document).find('#rtwwdpdl_time_tab').hide();
		$(document).find('.rtwwdpdl_prod_table').show();
		$(document).find('.rtwwdpdl_prod_c_table').hide();
	});
	$(document).on('click', '.rtwwdpdl_combi_prod_rule', function () {
		$(document).find('.rtwwdpdl_add_combi_rule_tab').show();
		$(document).find('.rtwwdpdl_rule_tab_combi').addClass('active');
		$(document).find('.rtwproduct_restrict_combi').removeClass('active');
		$(document).find('.rtwproduct_validity_combi').removeClass('active');
		$(document).find('#rtwwdpdl_rule_tab_combi').show();
		$(document).find('.rtwwdpdl_add_single_rule').hide();
		$(document).find('#rtwwdpdl_restriction_tab_combi').hide();
		$(document).find('#rtwwdpdl_time_tab_combi').hide();
		$(document).find('.rtwwdpdl_prod_c_table').show();
		$(document).find('.rtwwdpdl_prod_c_table_edit').show();
		$(document).find('.rtwwdpdl_prod_table').hide();
		if (!rtw_datatable) {
			rtw_datatable = $(document).find('.rtwtables').DataTable({
				"order": [],
				"columnDefs": [{ orderable: false, targets: [0] }],
			});
		}
	});
	$(document).on('click', '#rtwwdpdl_plus_rule', function () {
		$(document).find('#rtwwdpdl_plus').show();
	});
	$(document).on('click', '.rtwwdpdl_single_cat', function () {
		$(document).find('.rtwwdpdl_single_cat_rule_tab').show();
		$(document).find('.rtwwdpdl_single_cat_rule_tab').addClass('active');
		$(document).find('.rtwwdpdl_restriction_tab').removeClass('active');
		$(document).find('.rtwwdpdl_time_tab').removeClass('active');
		$(document).find('#rtwcat_rule_tab').show();
		$(document).find('.rtwwdpdl_single_cat_rule').show();
		$(document).find('.rtwwdpdl_combi_cat_tab').hide();
		$(document).find('#rtwcat_restriction_tab').hide();
		$(document).find('#rtwcat_time_tab').hide();
		$(document).find('.rtwwdpdl_cat_table').show();
		$(document).find('.rtwwdpdl_cat_c_table').hide();
	});
	$(document).on('click', '.rtwwdpdl_combi_cat', function () {
		$(document).find('.rtwwdpdl_combi_cat_tab').show();
		$(document).find('.rtwwdpdl_cat_rule_tab_combi').addClass('active');
		$(document).find('.rtwwdpdl_restriction_tab_combi').removeClass('active');
		$(document).find('.rtwwdpdl_time_tab_combi').removeClass('active');
		$(document).find('#rtwcat_com_rule_tab').show();
		$(document).find('.rtwwdpdl_cat_rule_tab_combi').show();
		$(document).find('.rtwwdpdl_single_cat_rule').hide();
		$(document).find('#rtwcat_com_rest_tab').hide();
		$(document).find('#rtwcat_com_time_tab').hide();
		$(document).find('.rtwwdpdl_cat_c_table').show();
		$(document).find('.rtwwdpdl_cat_table').hide();
		if (!rtw_datatable) {
			rtw_datatable = $(document).find('.rtwtables').DataTable({
				"order": [],
				"columnDefs": [{ orderable: false, targets: [0] }],
			});
		}
	});
	$(document).on('click', '.rtwwdpdl_single_bogo_rule', function () {
		$(document).find('.rtwwdpdl_single_bogo_rule_tab').show();
		$(document).find('.rtwwdpdl_bogo_rule_tab').addClass('active');
		$(document).find('.rtwwdpdl_restriction_tab').removeClass('active');
		$(document).find('.rtwwdpdl_time_tab').removeClass('active');
		$(document).find('.rtwwdpdl_bogo_rule_tab').show();
		$(document).find('#rtwbogo_rule_tab').show();
		$(document).find('.rtwwdpdl_bogo_combi_tab').hide();
		$(document).find('#rtwbogo_restrict_tab').hide();
		$(document).find('#rtwbogo_validity_tab').hide();
		$(document).find('.rtwwdpdl_bogo_table').show();
		$(document).find('.rtwwdpdl_bogo_c_table').hide();
	});
	$(document).on('click', '.rtwwdpdl_cat_bogo_rule', function () {
		$(document).find('.rtwwdpdl_bogo_combi_tab').show();
		$(document).find('.rtwwdpdl_bogo_c_rule_tab').addClass('active');
		$(document).find('.rtwwdpdl_restriction_tab_combi').removeClass('active');
		$(document).find('.rtwwdpdl_time_tab_combi').removeClass('active');
		$(document).find('.rtwwdpdl_bogo_c_rule_tab').show();
		$(document).find('#rtwwdpdl_rule_tab_combi').show();
		$(document).find('.rtwwdpdl_single_bogo_rule_tab').hide();
		$(document).find('#rtwwdpdl_restriction_tab_combi').hide();
		$(document).find('#rtwwdpdl_time_tab_combi').hide();
		$(document).find('.rtwwdpdl_bogo_c_table').show();
		$(document).find('.rtwwdpdl_bogo_table').hide();
		if (!rtw_datatable) {
			rtw_datatable = $(document).find('.rtwtables').DataTable({
				"order": [],
				"columnDefs": [{ orderable: false, targets: [0] }],
			});
		}
	});
	$(document).on('click', '.rtwwdpdl_tier_pro_rule', function () {
		$(document).find('.rtwwdpdl_add_tier_pro_rule_tab').show();
		$(document).find('.rtwwdpdl_rule_tab').addClass('active');
		$(document).find('.rtwwdpdl_restriction_tab').removeClass('active');
		$(document).find('.rtwwdpdl_time_tab').removeClass('active');
		$(document).find('#rtwwdpdl_tiered_rule_tab').show();
		$(document).find('#rtwwdpdl_tiered_restr_tab').hide();
		$(document).find('#rtwwdpdl_tiered_time_tab').hide();
		$(document).find('.rtwwdpdl_add_tier_cat_rule_tab').hide();
		$(document).find('.rtwwdpdl_tier_pro_table').show();
		$(document).find('.rtwwdpdl_tier_c_table').hide();
	});
	$(document).on('click', '.rtwwdpdl_tier_cat_rule', function () {
		$(document).find('.rtwwdpdl_add_tier_cat_rule_tab').show();
		$(document).find('.rtwwdpdl_rule_tab_combi').addClass('active');
		$(document).find('.rtwwdpdl_restriction_tab_combi').removeClass('active');
		$(document).find('.rtwwdpdl_time_tab_combi').removeClass('active');
		$(document).find('#rtwwdpdl_rule_tab_combi').show();
		$(document).find('#rtwwdpdl_restriction_tab_combi').hide();
		$(document).find('#rtwwdpdl_time_tab_combi').hide();
		$(document).find('.rtwwdpdl_add_tier_pro_rule_tab').hide();
		$(document).find('.rtwwdpdl_tier_c_table').show();
		$(document).find('.rtwwdpdl_tier_pro_table').hide();
		if (!rtw_datatable) {
			rtw_datatable = $(document).find('.rtwtables').DataTable({
				"order": [],
				"columnDefs": [{ orderable: false, targets: [0] }],
			});
		}
	});
	$(document).on('change', '#rtwwdpdl_check_for', function () {
		var val = $(this).find("option:selected").text();
		$(document).find("[for=rtwwdpdl_min]").html('Minimum ' + val);
		$(document).find("[for=rtwwdpdl_max]").html('Maximum ' + val);
		$(document).find(".rtwtiered_chk_for").text(val + ' ');
	});
	$(document).find('#product_id').select2();
	$(document).on('change', '#rtwwdpdl_discount_type', function () {
		var val = $(this).find("option:selected").text();
		$(document).find("[for=rtwwdpdl_discount_value]").html(val);
		$(document).find('#rtw_header').text(val + ' ');
	});
	$(document).on('change', '#rtwwdpdl_dsnt_type', function () {
		var val = $(this).find("option:selected").text();
		$(document).find('#rtwwdpdl_dsnt_value').text(val);
	});
	$(document).on('change', '#rtwwdpdl_rule_for', function () {
		var val = $(this).find("option:selected").text();
		$(document).find('#rtw_min').text('Minimum ' + val);
	});
	$(document).on('change', '#rtwwdpdl_sale_of', function () {
		var val = $(this).find("option:selected").text();
		val = $.trim(val);
		if (val == 'Products') {
			$(document).find('#rtw_for_product').show();
			$(document).find('#rtw_for_category').hide();
			$(document).find('#category_id').val('');
		}
		else if (val == 'Category') {
			$(document).find('#rtw_for_product').hide();
			$(document).find('#rtwproduct').val('');
			$(document).find('#rtw_for_category').show();
		}
	});
	////////// for insertion of search tiered field /////////////
	$(document).on('click', '#rtwadd_tiered', function () {
		var text = $(document).find('#rtwtiered').text();
		text = $.trim(text);
		if (text == '') {
			alert('Please select a product on which the rule should be applied.');
			return false;
		}
		var row_no = (jQuery('#rtwtiered_table >tbody >tr').length) + 1;
		var max = parseInt($(document).find('.quant_max').val()) + 1;
		var max_m = max + 1;
		$(document).find('.quant_max').removeClass('quant_max');
		var quant_min = '<input type="number" min="1" name="quant_min[]" value="' + max + '"  />';
		var quant_max = '<input type="number" class="quant_max max" min="1" name="quant_max[]" value="' + max_m + '"  />';
		var dis = '<input type="number" min="0" step="0.1" name="discount_val[]" value="1"  />';
		var remove = '<a class="button insert rtw_remov_tiered" name="deletebtn" >Remove</a>';
		$('#product_list_body').append('<tr><td>Tier ' + row_no + '</td><td>' + quant_min + '</td><td>' + quant_max + '</td><td>' + dis + '</td><td>' + remove + '</td></tr>');
		jQuery('#rtwtiered' + row_no).trigger('wc-enhanced-select-init');
	});
	$(document).on('click', '.rtw_remov_tiered', function () {
		var row_no = 1;
		$(document).find('#rtwproduct_table tbody tr').each(function () {
			$(this).find('td:first-child').text(row_no);
			row_no = row_no + 1;
		});
		$(this).closest('tr').prev().find('.max').addClass('quant_max');
		$(this).closest('tr').remove();
	});
	$(document).on('click', '.rtw_remov_tier_cat', function () {
		var row_no = 1;
		$(document).find('#rtwproduct_table tbody tr').each(function () {
			$(this).find('td:first-child').text(row_no);
			row_no = row_no + 1;
		});
		$(this).closest('tr').prev().find('.max').addClass('quant_c_max');
		$(this).closest('tr').remove();
	});
	$(document).on('change', '#rtwwdpdl_sale_check_for', function () {
		var val = $(this).find("option:selected").text();
		$(document).find('.rtw_sale_quant').text(val);
	});
	$(document).on('change', '#rtwwdpdl_attributes', function () {
		var val = $(this).find("option:selected").val();
		if (val == 'pa_size') {
			$(document).find('#rtwwdpdl_attribute_val_size').show();
			$(document).find('#rtwwdpdl_attribute_val_col').hide();
		}
		else if (val == 'pa_color') {
			$(document).find('#rtwwdpdl_attribute_val_col').show();
			$(document).find('#rtwwdpdl_attribute_val_size').hide();
		}
	});
	$(document).on('change', '#rtwwdpdl_rule_for_plus', function () {
		var val = $(this).find("option:selected").val();
		if (val == 'rtwwdpdl_product') {
			$(document).find('.rtw_if_prod').show();
			$(document).find('.rtw_if_cat').hide();
		}
		else if (val == 'rtwwdpdl_category') {
			$(document).find('.rtw_if_cat').show();
			$(document).find('.rtw_if_prod').hide();
		}
	});
	$(document).on('change', '.rtw_plus_mem', function () {
		var user_id = $(this).val();
		var checked = '';
		if ($(this).is(":checked")) {
			checked = 'checked';
		}
		else {
			checked = 'unchecked';
		}
		var data = {
			action: 'rtwwdpdl_plus_member',
			'user_id': user_id,
			'checked': checked,
			security_check: rtwwdpdl_ajax.rtwwdpdl_nonce
		};
		$.ajax({
			url: rtwwdpdl_ajax.ajax_url,
			type: "POST",
			data: data,
			dataType: 'json',
			success: function (response) {
			}
		});
	});
	$(document).on('change', '.rtw_enable_plus', function () {
		var rtw_val = $(this).val();
		var data = {
			action: 'rtw_enable_plus',
			'enable': rtw_val,
			security_check: rtwwdpdl_ajax.rtwwdpdl_nonce
		};
		$.ajax({
			url: rtwwdpdl_ajax.ajax_url,
			type: "POST",
			data: data,
			dataType: 'json',
			success: function (response) {
			}
		});
	});
	$(document).on('change', '.rtw_enable_specific', function () {
		var rtw_val = $(this).val();
		var data = {
			action: 'rtwwdpdl_specific_enable',
			'enable': rtw_val,
			security_check: rtwwdpdl_ajax.rtwwdpdl_nonce
		};
		$.ajax({
			url: rtwwdpdl_ajax.ajax_url,
			type: "POST",
			data: data,
			dataType: 'json',
			success: function (response) {
			}
		});
	});
})(jQuery);
function rtwwdpdl_select(obj, selector) {
	let all_links = document.querySelectorAll('.active');
	for (let dv of all_links) {
		dv.classList.remove('active');
	}
	obj.parentElement.className += ' active';
	let div = document.querySelector(selector);
	let alldiv = document.querySelectorAll('.options_group');
	for (let dv of alldiv) {
		dv.style.display = 'none';
	}
	if (div.style.display == "none") {
		div.style.display = "block";
	} else {
		div.style.display = "none";
	}
}