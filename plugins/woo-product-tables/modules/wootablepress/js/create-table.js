(function($) {
	"use strict";
	$(document).ready(function () {
		$('a[href$="admin.php?page=wtbp-table-press&tab=wootablepress#wtbpadd"]').attr('href', '#wtbpadd');
		var wtbpAddDialog = $('#wtbpAddDialog'),
			wtbpCreateTableLoaded = false;

		$.fn.dataTable.ext.classes.sPageButton = 'button button-small woobewoo-paginate';
		$.fn.dataTable.ext.classes.sLengthSelect = 'woobewoo-flat-input';

		if(wtbpAddDialog.length) {
			var $createBtn = $('.create-table'),
				$error = $('#formError'),
				$input = $('#addDialog_title'),
				$selectAll = $('#wtbpCreateTableSelectAll'),
				$selectExclude = $('#wtbpCreateTableSelectExclude'),
				url = typeof(ajaxurl) == 'undefined' || typeof(ajaxurl) !== 'string' ? url = WTBP_DATA.ajaxurl : url = WTBP_DATA.ajaxurl;

			var createTable = $('#wtbpCreateTable').css('width', '100%').DataTable({
				serverSide: true,
				processing: true,
				deferLoading: 0,
				ajax: {
					"url": url + '?mod=wootablepress&action=getSearchProducts&pl=wtbp&reqType=ajax',
					"type": "POST",
					data: function (d) {
						var filters = $('#wtbpCreateTable_wrapper .dt-buttons');
						d.filter_author = filters.find('select[name="filter_author"]').val();
						d.filter_category = filters.find('select[name="filter_category"]').val();
						d.filter_tag = filters.find('select[name="filter_tag"]').val();
						d.filter_attribute = filters.find('select[name="filter_attribute"]').val();
						d.filter_attribute_exactly = filters.find('input[name="filter_attribute_exactly"]').is(':checked') ? 1 : 0;
						d.show_variations = filters.find('input[name="show_variations"]').is(':checked') ? 1 : 0;
						d.filter_private = filters.find('input[name="filter_private"]').is(':checked') ? 1 : 0;
					},
					beforeSend: function() {
						$('#wtbpCreateTable').DataTable().column(4).visible($('#wtbpCreateTable_wrapper .dt-buttons input[name="show_variations"]').is(':checked'));
					}
				},
				dom: 'Bfrtip',
				buttons: [
					{
						text: 'Select all',
						className: 'button',
						action: function (e, dt, node, config) {
							$('#wtbpCreateTable input[type="checkbox"]').prop('checked', true).closest('tr').addClass('selected');
							$selectAll.val(1);
							$selectExclude.val('');
						}
					},
					{
						text: 'Select none',
						className: 'button',
						action: function (e, dt, node, config) {
							$('#wtbpCreateTable input[type="checkbox"]').prop('checked', false).closest('tr').removeClass('selected');
							$selectAll.val(0);
							$selectExclude.val('');
						}
					}
				],
				columnDefs: [{
						"targets": 'no-sort',
						"orderable": false
					},
					{
						"targets": [1],
						"visible": false,
						"searchable": false
					},
					{
						"targets": [2],
						"className": 'dt-center',
						"width": "80px"
					},
					{
						"targets": [0],
						"className": 'checkbox-column dt-left',
						"width": "20px"
					}
				],
				order: [],
				//responsive: true,
				language: {
					emptyTable: "There\'re no products in the WooCommerce store",
					paginate: {
      					next: '<i class="fa fa-fw fa-angle-right">',
      					previous: '<i class="fa fa-fw fa-angle-left">'  
    				},
    				lengthMenu: 'Show entries _MENU_',
    				search: '_INPUT_',
    				searchPlaceholder: 'Search'
				},
				fnDrawCallback : function(){
					var wrapper = $('#wtbpCreateTable_wrapper');
					wrapper.find('.dataTables_paginate')[0].style.display = wrapper.find('.dataTables_paginate span .woobewoo-paginate').length > 1 ? 'block' : 'none';
					wtbpSetSelectAllExclude(false, true, true, 'Create');
				}
			});
			var tableWrapper = $('#wtbpCreateTable_wrapper');

			//click to "check all rows" checkbox
			$('body').on('click', '.wtbpCheckAll', function () {
				var el = $(this),
					wrapper = el.closest('.dataTable'),
					rows = wrapper.find('tbody tr'),
					checked = el.is(':checked');
				if(checked) {
					rows.addClass('selected');
					rows.find('input').prop("checked", true);
				} else {
					rows.removeClass('selected');
					rows.find('input').prop("checked", false);
				}
				if(wrapper.is('.wtbpSearchTable')) {
					wtbpSetSelectAllExclude(true, !checked, false, wrapper.is('#wtbpSearchTable') ? 'Search' : 'Create');
				} else if(wrapper.is('.wtbpContentAdmTable')) {
					wtbpSetSelectAllExclude(true, !checked, false, 'Content');
				}
			});

			//click on "check one row" checkbox
			$('body').on('click', '.wtbpAdminTableWrapp table tbody input[type="checkbox"]', function () {
				var el = $(this),
					wrapper = el.closest('.dataTable'),
					checked = el.is(':checked');
				if(checked) {
					el.closest('tr').addClass('selected');
				} else {
					el.closest('tr').removeClass('selected');
				}
				if(wrapper.is('.wtbpSearchTable')) {
					wtbpSetSelectAllExclude([el.attr('data-id')], !checked, false, wrapper.is('#wtbpSearchTable') ? 'Search' : 'Create');
				} else if(wrapper.is('.wtbpContentAdmTable')) {
					wtbpSetSelectAllExclude([el.attr('data-id')], !checked, false, 'Content');
				}
			});

			//createTable.buttons().container().append($('#wtbpCreateTableFilters').find('select, div'));
			createTable.buttons().container().prepend($('#wtbpCreateTableFilters'));
			createTable.buttons().container().append($('#wtbpCreateTable_filter'));
			tableWrapper.find('.dt-buttons').find('select, input').on('change', function (e) {
				createTable.ajax.reload();
			});

			var $dialog = wtbpAddDialog.dialog({
				width: $(window).width() * 0.95,
				height: $(window).height() * 0.95,
				modal: true,
				autoOpen: false,
				open: function () {
					if(!wtbpCreateTableLoaded) {
						createTable.ajax.reload();
						wtbpCreateTableLoaded = true;
					}
					$('#wtbpAddDialog').keypress(function(e) {
						if (e.keyCode == $.ui.keyCode.ENTER) {
							e.preventDefault();
							$('.wtbpDialogSave').click();
						}
					});
				},
				close: function () {
					window.location.hash = '';
				},
				buttons: {
					Cancel: function () {
						$dialog.dialog('close');
					},
					'Create Table': function (event) {
						$('.wtbpDialogSave').prop('disabled', true);

						var productIdSelected = [],
							productIdExcluded = [],
						filters = {
							search: {value: $('#wtbpCreateTable_filter input').val()},
							filter_author: tableWrapper.find('.dt-buttons select[name="filter_author"]').val(),
							filter_category: tableWrapper.find('.dt-buttons select[name="filter_category"]').val(),
							filter_tag: tableWrapper.find('.dt-buttons select[name="filter_tag"]').val(),
							filter_attribute: tableWrapper.find('.dt-buttons select[name="filter_attribute"]').val(),
							filter_attribute_exactly: tableWrapper.find('.dt-buttons input[name="filter_attribute_exactly"]').is(':checked') ? 1 : 0,
							show_variations: tableWrapper.find('.dt-buttons input[name="show_variations"]').is(':checked') ? 1 : 0,
							filter_private: tableWrapper.find('.dt-buttons input[name="filter_private"]').is(':checked') ? 1 : 0
						};
						if($selectAll.val() == '1') {
							productIdSelected = 'all',
							productIdExcluded = $selectExclude.val().split(',');
						} else {
							productIdSelected = $selectExclude.val().split(',');
						}
						$.sendFormWtbp({
							data: {
								mod: 'wootablepress',
								title: $input.val(),
								action: 'save',
								productIdSelected: productIdSelected,
								productIdExcluded: productIdExcluded,
								filters: filters,
							},
							appendData: {wtbpNonce: window.wtbpNonce},
							onSuccess: function(res) {
								if(!res.error) {
									var currentUrl = window.location.href;
									if (res.data.edit_link && currentUrl !== res.data.edit_link) {
										toeRedirect(res.data.edit_link);
									}
								}else{
									$error.find('p').text(res.errors.title);
									$error.removeClass('woobewoo-hidden');
								}
								$dialog.dialog('close');
							}
						});
					}
				},
				create:function () {
					var $thisDialog = $(this).closest(".ui-dialog");
					$thisDialog.addClass('woobewoo-plugin').find(".ui-dialog-buttonset button").last().addClass('wtbpDialogSave');
					$thisDialog.find('.ui-dialog-title').append($thisDialog.find('#addDialog_title').addClass('ui-state-focus'));
				}
			});

			$input.on('focus', function (e) {
				$error.addClass('woobewoo-hidden');
			});
			$input.on('click', function (e) {
				e.preventDefault();
				$(this).focus();
			});

			$createBtn.on('click', function () {
				$dialog.dialog('open');
			});
		}

		$( document ).ready(function(){
			if (window.location.hash === '#wtbpadd') {
				// To prevent error if data not loaded completely
				setTimeout(function() {
					if(typeof $dialog !== 'undefined'){
						$dialog.dialog('open');
					}
				}, 500);
			};
		})


		$( window ).on( 'hashchange', function( e ) {
			if (window.location.hash === '#wtbpadd') {
				// To prevent error if data not loaded completely
				setTimeout(function() {
					if(typeof $dialog !== 'undefined'){
						$dialog.dialog('open');
					}
				}, 500);
			}
		} );

		$('#toplevel_page_wtbp-table-press .wp-submenu-wrap li:has(a[href$="admin.php?page=wtbp-table-press"])').on('click', function(e){
			e.preventDefault();
			showAddDialog();
		});

		$(document.body).off('click', '.woobewoo-navigation li:has(a[href$="admin.php?page=wtbp-table-press&tab=wootablepress#wtbpadd"])');
		$(document.body).on('click', '.woobewoo-navigation li:has(a[href$="admin.php?page=wtbp-table-press&tab=wootablepress#wtbpadd"])', function(e){
			e.preventDefault();
			showAddDialog();
		});

		function showAddDialog(){
			setTimeout(function() {
				$dialog.dialog('open');
			}, 500);
		}
	});
})(jQuery);
function wtbpSetSelectAllExclude(ids, add, check, name) {
	var isAll = jQuery('#wtbp'+name+'TableSelectAll').val() == '1',
		excludeList = jQuery('#wtbp'+name+'TableSelectExclude').val(),
		excludeArr = excludeList.length ? excludeList.split(',') : [],
		table = jQuery('#wtbp'+name+'Table_wrapper table');

	if(ids === false){
		ids = excludeArr;
		if(isAll) table.find('td input[type="checkbox"]').prop('checked', true).closest('tr').addClass('selected');
	} else if(ids === true){
		ids = [];
		table.find('td input[type="checkbox"]').each(function() {
			ids.push(jQuery(this).attr('data-id'));
		});
	}
	if(!isAll) add = !add;
	ids.forEach(function (id, i) {
		var in_exclude = excludeArr.indexOf(id);
		if(add) {
			if(check) table.find('input[data-id="'+id+'"]').prop('checked', false).closest('tr').removeClass('selected');
			else if(in_exclude == -1) excludeArr.push(id);
		} else {
			if(check) table.find('input[data-id="'+id+'"]').prop('checked', true).closest('tr').addClass('selected');
			else if(in_exclude != -1) excludeArr.splice(in_exclude, 1);
		}
	});
	jQuery('#wtbp'+name+'TableSelectExclude').val(excludeArr);
}
