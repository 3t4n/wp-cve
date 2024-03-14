(function ($, app) {
	"use strict";
	function AdminPage() {
		this.$obj = this;
		//this.tableSearchWrap = $('#wtbpSearchContentTbl');
		this.tableSearch = '';
		this.tablePropertiesWrap = $('#wtbpSetPropertiesContent');
		this.tableContent = false;
		this.tablePreview = '';
		this.tableContentReloading = false;
		this.tablePreviewReloading = false;
		this.tableSSPReloading = false;
		this.tablePreviewEnabled = false;
		this.tablePreviewTimeout = 0;
		this.tableSaving = false;
		this.tableNeedSave = false;
		this.tableNeedPreview = true;

		return this.$obj;

	}

	AdminPage.prototype.init = (function () {
		var _thisObj = this.$obj;
		_thisObj.eventsAdminPage();
		_thisObj.eventsTables();
		_thisObj.eventsProperties();
		_thisObj.previewShowEvent();
		_thisObj.loadProductsSearchTbl();
		_thisObj.loadProductsContentTbl();
		if(!WTBP_DATA.isPro) _thisObj.tablePreviewEnabled = true;
	});

	AdminPage.prototype.initTable = (function (tablename, totalRows) {
		var _thisObj = this.$obj,
			url = typeof(ajaxurl) == 'undefined' || typeof(ajaxurl) !== 'string' ? url = WTBP_DATA.ajaxurl : url = WTBP_DATA.ajaxurl;

		$.fn.dataTable.ext.classes.sPageButton = 'button button-small woobewoo-paginate';
		$.fn.dataTable.ext.classes.sLengthSelect = 'woobewoo-flat-input';
		switch (tablename) {
			case 'tableSearch':
				_thisObj.tableSearch = $('#wtbpSearchTable').css('width', '100%').DataTable(
					{
						serverSide: true,
						processing: true,
						ajax: {
							"url": url + '?mod=wootablepress&action=getSearchProducts&pl=wtbp&reqType=ajax',
							"type": "POST",
							data: function (d) {
								d.productids = $('input[name="settings[productids]"]').val();
								d.filter_in_table = $('#wtbpSearchTable_wrapper .dt-buttons select[name="filter_in_table"]').val();
								d.filter_author = $('#wtbpSearchTable_wrapper .dt-buttons select[name="filter_author"]').val();
								d.filter_category = $('#wtbpSearchTable_wrapper .dt-buttons select[name="filter_category"]').val();
								d.filter_tag = $('#wtbpSearchTable_wrapper .dt-buttons select[name="filter_tag"]').val();
								d.filter_attribute = $('#wtbpSearchTable_wrapper .dt-buttons select[name="filter_attribute"]').val();
								d.filter_attribute_exactly = $('#wtbpSearchTable_wrapper .dt-buttons input[name="filter_attribute_exactly"]').is(':checked') ? 1 : 0;
								d.show_variations = $('#wtbpSearchTable_wrapper .dt-buttons input[name="show_variations"]').is(':checked') ? 1 : 0;
								d.filter_private = $('#wtbpSearchTable_wrapper .dt-buttons input[name="filter_private"]').is(':checked') ? 1 : 0;
							},
							beforeSend: function() {
								$('#wtbpSearchTable').DataTable().column(4).visible($('#wtbpSearchTable_wrapper .dt-buttons input[name="show_variations"]').is(':checked'));
							}
						},
						//lengthChange: true,
						//lengthMenu: [ [10, 20, 40, -1], [10, 20, 40, "All"] ],
						//paging: 10,
						//dom: 'B<"pull-right"fl>rtip',
						dom: 'Bfrtip',
						buttons: [
							{
								text: 'Select all',
								className: 'button',
								action: function (e, dt, node, config) {
									$('.wtbpSearchTableWrapp table input[type="checkbox"]').prop('checked', true).closest('tr').addClass('selected');
									$('#wtbpSearchTableSelectAll').val(1);
									$('#wtbpSearchTableSelectExclude').val('');
								}
							},
							{
								text: 'Select none',
								className: 'button',
								action: function (e, dt, node, config) {
									$('.wtbpSearchTableWrapp table input[type="checkbox"]').prop('checked', false).closest('tr').removeClass('selected');
									$('#wtbpSearchTableSelectAll').val(0);
									$('#wtbpSearchTableSelectExclude').val('');
								}
							}
						],
						columnDefs: [{
							"targets": 'no-sort',
							"orderable": false
						},
						{
							"targets": [2],
							"className": 'dt-center',
							"width": "80px"
						},
						{
							"targets": [1],
							"className": 'dt-center',
							"width": "50px"
						},
						{
							"targets": [0],
							"className": 'checkbox-column dt-left',
							"width": "20px"
						}],
						order: [],
						responsive: true,
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
							if ( jQuery('#wtbpSearchTable_wrapper .dataTables_paginate  span .woobewoo-paginate').length > 1) {
								jQuery('#wtbpSearchTable_wrapper .dataTables_paginate ')[0].style.display = "block";
							} else {
								jQuery('#wtbpSearchTable_wrapper .dataTables_paginate ')[0].style.display = "none";
							}
							jQuery('#wtbpSearchTable_wrapper label.wtbpPropuctInTable ').closest('tr').css('background-color', '#E5F7FF');
							wtbpSetSelectAllExclude(false, true, true, 'Search');
						}
					}
				);
				//_thisObj.tableSearch.buttons().container().append($('#wtbpSearchTableFilters select, #wtbpSearchTableFilters div'));
				_thisObj.tableSearch.buttons().container().prepend($('#wtbpSearchTableFilters'));
				_thisObj.tableSearch.buttons().container().append($('#wtbpSearchTable_filter'));
				jQuery('#wtbpSearchTable_wrapper .dt-buttons select, #wtbpSearchTable_wrapper .dt-buttons input').on('change', function (e) {
					_thisObj.tableSearch.ajax.reload();
				});
				break;
			case 'tableContent':
				var loadedRows = [],
					loadedCells = [];
				_thisObj.tableContent = $('.wtbpContentAdmTable').DataTable(
					{
						serverSide: true,
						processing: true,
						deferLoading: totalRows,
						ajax: {
							"url": url + '?mod=wootablepress&action=getProductContentPage&pl=wtbp&reqType=ajax',
							"type": "POST",
							data: function (d) {
								var wtbpForm = $('#wtbpTablePressEditForm');
								d.productids = wtbpForm.find('input[name="settings[productids]"]').val();
								d.orders = wtbpForm.find('input[name="settings[order]"]').val();
								d.id = wtbpForm.attr('data-table-id') || 0;
								if(d['order'].length) {
									var col = d['order'][0]['column'];
									if($('#wtbpContentTable thead th').eq(0).is('.wtbpSortableIconThead')) {
										col++;
									}
									d.sortCol = $('#wtbpContentTable thead th').eq(col).attr('data-key');
								}
								d.admin = 1;
								d.sortCustom = $('[name="settings[sorting_custom]"]').is(':checked') && $('[name="settings[pre_sorting]"] option:selected').val() == '' ? 1 : 0;
								d.returnIds = (d['sortCustom'] == 1 && d['search']['value'].length == 0) ? 1 : 0;
							},
							dataFilter: function(data){
								var json = jQuery.parseJSON(data),
									rows = $(json.html).find('tr'),
									aData = [];

								loadedRows = [];
								loadedCells = [];
								for(var i = 0; i < rows.length; i++) {
									var row = rows[i];
									loadedRows.push(row.attributes);
									var cells = $(row).find('td'),
										attrs = [],
										vals = [];
									for(var j = 0; j < cells.length; j++) {
										var cell = cells[j];
										attrs.push(cell.attributes);
										vals.push(cell.innerHTML);
									}
									loadedCells.push(attrs);
									aData.push(vals);
								}
								json.html = '';
								json.data = aData;
								if(typeof json.ids != 'undefined') {
									$('input[name="settings[productids]"]').val(json.ids);
								}
								return JSON.stringify(json);
							}
						},
						createdRow: function (row, data, dataIndex) {
							if(typeof(loadedRows[dataIndex]) != 'undefined') {
								$(loadedRows[dataIndex]).each(function () {
									$(row).attr(this.name, this.value);
								});
							}
						},
						lengthChange: true,
						lengthMenu: [ [10, 20, 40, -1], [10, 20, 40, "All"] ],
						paging: 10,
						dom: 'B<"pull-right"fl>rtip',
						buttons: [
							{
								text: 'Select all',
								className: 'button',
								action: function (e, dt, node, config) {
									$('#wtbpSortTableContent input[type="checkbox"]').prop('checked', true).closest('tr').addClass('selected');
									$('#wtbpContentTableSelectAll').val(1);
									$('#wtbpContentTableSelectExclude').val('');
								}
							},
							{
								text: 'Select none',
								className: 'button',
								action: function (e, dt, node, config) {
									$('#wtbpSortTableContent input[type="checkbox"]').prop('checked', false).closest('tr').removeClass('selected');
									$('#wtbpContentTableSelectAll').val(0);
									$('#wtbpContentTableSelectExclude').val('');
								}
							},
							{
								text: '<i class="fa fa-minus"></i> Remove Selected Products',
								className: 'button',
								action: function (e, dt, node, config) {
									$(document.body).trigger('removeSelected');
								}
							}
						],
						columnDefs: [{
							"targets": WTBP_DATA.isPro ? 'no-sort' : '_all',
							"orderable": false,

						},
						{
							"className": "dt-left",
							"targets": [0]
						},
						{
							targets: '_all',
							cellType: 'td',
							createdCell: function (td, cellData, rowData, row, col) {
								if(typeof(loadedCells[row]) != 'undefined' && typeof(loadedCells[row][col]) != 'undefined') {
									$(loadedCells[row][col]).each(function () {
										$(td).attr(this.name, this.value);
									});
								}
							}
						}],
						order: [],
						language: {
							emptyTable: "There\'re no products in the WooCommerce store",
							paginate: {
								next: '<i class="fa fa-fw fa-angle-right">',
								previous: '<i class="fa fa-fw fa-angle-left">'  
							},
							lengthMenu: 'Show entries _MENU_',
							search: '_INPUT_'
						},
						fnDrawCallback : function(){
							if ( jQuery('#wtbpContentTable_wrapper .dataTables_paginate  span .woobewoo-paginate').length > 1) {
								jQuery('#wtbpContentTable_wrapper .dataTables_paginate ').show();
							} else {
								jQuery('#wtbpContentTable_wrapper .dataTables_paginate ').hide();
							}
							wtbSortableIconAdd();
							wtbpSetSelectAllExclude(false, true, true, 'Content');
							jQuery('.star-rating .star-rating-width').each(function() {
								var rating = $(this);
								rating.css('width', rating.attr('data-width'));
							});
						}
					}
				);


				//Custom Drag and Drop sorting functional START
				var customSortIsChecked = true;
				if ( $('[name="settings[sorting_custom]"]' ).is(':checked') && $('[name="settings[pre_sorting]"] option:selected').val() == '') {
					customSortIsChecked = false;
				}
				var startData, startIndex, endIndex, dataTablePage, dataTableLength;
				var dataTable = $("#wtbpContentTable").dataTable();
				//We need update DataTable - data when drag and drop element
				$('.wtbpContentAdmTable').find("tbody").sortable({
					disabled: customSortIsChecked,
					start: function(event, ui) {
						dataTableLength =  dataTable.api().data().page.info().length;
						dataTablePage = Number(dataTable.api().page() * dataTableLength);
						startIndex = ui.item.index();
						startIndex = Number(startIndex + dataTablePage);
					},
					stop: function(event, ui) {
						dataTableLength =  dataTable.api().data().page.info().length;
						dataTablePage = Number(dataTable.api().page() * dataTableLength);
						endIndex = ui.item.index();
						endIndex = Number(endIndex + dataTablePage);
						var productIds = $('input[name="settings[productids]"]').val().split(',');
						productIds.splice(endIndex, 0, productIds.splice(startIndex, 1)[0]);
						$('input[name="settings[productids]"]').val(productIds.join(','));
						dataTable.api().order([]);dataTable.api().order([]);
					},
					containment: "parent",
					cursor: "move",
				});
				//Custom Drag and Drop sorting functional END

				break;
		}
		return;

	});

	AdminPage.prototype.eventsAdminPage = (function () {
		var _thisObj = this.$obj;
		// Initialize Main Tabs
		var $mainTabsContent = $('#wtbpTablePressEditForm > .row-tab'),
			$mainTabs = $('.wtbpSub.tabs-wrapper.wtbpMainTabs .button'),
			$currentTab = $mainTabs.filter('.current').attr('href');

		$mainTabsContent.filter($currentTab).addClass('active');

		$mainTabs.on('click', function (e) {
			e.preventDefault();
			var $this = $(this),
				$curTab = $this.attr('href');

			$mainTabsContent.removeClass('active');
			$mainTabs.filter('.current').removeClass('current');
			$this.addClass('current');
			$mainTabsContent.filter($curTab).addClass('active');
		});

		var windowHeight = $(window).width() > 810 ? $(window).height() * 0.7 : $(window).height(),		
			settingsSection = $('.wtbp-settings-section'),
			linksOyPositions = [];

		$('.wtbpSettingTab.button').on('click', function (e) {
			e.preventDefault();
			$(window).trigger('resize');
			if(linksOyPositions.length) return;

			var offsetTop2 = Math.floor($('#wtpb-tab-settings-main').offset().top);

			linksOyPositions.push({
				'id': '#wtpb-tab-settings-main',
				'offset': 0,
			});
			linksOyPositions.push({
				'id': '#wtpb-tab-settings-features',
				'offset': Math.abs(Math.floor($('#wtpb-tab-settings-features').offset().top) - offsetTop2 - 40),
			});
			linksOyPositions.push({
				'id': '#wtpb-tab-settings-appearance',
				'offset': Math.abs(Math.floor($('#wtpb-tab-settings-appearance').offset().top) - offsetTop2 - 40),
			});
			linksOyPositions.push({
				'id': '#wtpb-tab-settings-text',
				'offset': Math.abs(Math.floor($('#wtpb-tab-settings-text').offset().top) - offsetTop2 - 40),
			});
		});

		$('.wtbp-settings-wrap').slimScroll({'height': windowHeight+'px'}).off('slimscrolling')
			.on('slimscrolling', null, { 'oy': linksOyPositions }, function(e, pos){
				if(e && e.data && e.data.oy) {
					var ind1 = 0,
						$activeItem = settingsSection.find('.wtbp-sub-tab a:not(.disabled)'),
						isFind = false;
					while(ind1 < (e.data.oy.length - 1) && !isFind) {
						if(e.data.oy[ind1].offset <= pos && e.data.oy[ind1+1].offset > pos) {
							isFind = ind1;
							ind1 = e.data.oy.length;
						}
						ind1++;
					}
					if(isFind == false && ind1 == 3) {
						isFind = ind1;
					}
					var activeId = $activeItem.attr('href');
					if(e.data.oy[isFind] && activeId != e.data.oy[isFind].id) {
						if($activeItem.length) {
							$activeItem.addClass('disabled');
						}
						settingsSection.find('.wtbp-sub-tab a[href="' + e.data.oy[isFind].id + '"]').removeClass('disabled');
					}
				}
			});
		settingsSection.find('.wtbp-sub-tab a').on('click', function(e, funcParams) {
			e.preventDefault();
			var $settingsWrap = $('.wtbp-settings-wrap')
			,	urlLink = $(this).attr('href')
			,	$linkItem = $(urlLink)
			,	$topItem = $("#wtpb-tab-settings-main");
			if($linkItem.length) {
				var offsetLink = $linkItem.offset().top
				,	offsetTop = $topItem.offset().top
				,	offsetAbs = Math.abs(offsetLink -offsetTop);
				// if need to set start position
				if(funcParams && funcParams.offsetScTop) {
					offsetAbs = funcParams.offsetScTop;
				}
				if(!isNaN(offsetAbs)) {
					$settingsWrap.slimScroll({ scrollTo: offsetAbs + 'px' });
				}
			}
		});

		var previewContainer = $('#wtbp-table-preview');
		$('#preview-container').css({
			'max-height': windowHeight,
			'min-height': windowHeight,
			'height': windowHeight
		});

		$('.wtbp-preview-section .wtbp-sub-tab a').on('click', function(e, funcParams) {
			e.preventDefault();
			var href = $(this).attr('href');
			
			$('.preview-styling a').addClass('disabled');
			$(this).removeClass('disabled');
			switch(href) {
				case '#wtbp-style-desktop':
					previewContainer.css('max-width', 'none');
					break;
				case '#wtbp-style-tablet':
					previewContainer.css('max-width', '768px');
					break;
				case '#wtbp-style-mobile':
					previewContainer.css('max-width', '380px');
					break;
				default:
					break;
			}
			_thisObj.tableNeedPreview = true;
			$('#wtbpPreviewTable').hide();
			_thisObj.previewShow();
		});
		settingsSection.find('input[type="checkbox"]').on('change wtbp-change', function () {
			var $this = $(this),
				check = $this.is(':checked'),
				hidden = $this.closest('.setting-wrapper').hasClass('wtbpHidden'),
				subOptions = $('.setting-suboption[data-main="'+$this.attr('name')+'"]'),
				subOptionsR = $('.setting-suboption[data-main-reverse="'+$this.attr('name')+'"]');
			if(subOptions.length) {
				if(check && !hidden) subOptions.removeClass('wtbpHidden');
				else subOptions.addClass('wtbpHidden');
				subOptions.find('select[multiple]').trigger('chosen:updated');
				subOptions.find('input[type="checkbox"], select').trigger('wtbp-change');
			}
			if(subOptionsR.length) {
				if(check || hidden) subOptionsR.addClass('wtbpHidden');
				else subOptionsR.removeClass('wtbpHidden');
				subOptionsR.find('select[multiple]').trigger('chosen:updated');
				subOptionsR.find('input[type="checkbox"], select').trigger('wtbp-change');
			}
		});
		settingsSection.find('select').on('change wtbp-change', function () {
			var $this = $(this),
				value = $this.val(),
				hidden = $this.closest('.setting-wrapper').hasClass('wtbpHidden'),
				subOptions = $('.setting-suboption[data-main="'+$this.attr('name')+'"]');
			if(subOptions.length) {
				subOptions.addClass('wtbpHidden');
				if(!hidden) {
					subOptions.filter('[data-main-value*="'+value+'"]').removeClass('wtbpHidden');
					subOptions.filter('[data-main-notvalue!="'+value+'"]').removeClass('wtbpHidden');
				}
				subOptions.find('input[type="checkbox"]').trigger('wtbp-change');
			}
		});

		settingsSection.find('.setting-wrapper input, .setting-input select, .setting-wrapper textarea').on('change', function(e) {
			if(!_thisObj.tablePreviewEnabled) return;

			var $this = $(this);
			if($this.attr('data-not-redraw') == '1' || $this.closest('.setting-wrapper').attr('data-not-redraw') == '1') {
				return;
			}
			if($this.attr('data-need-save') == '1') {
				_thisObj.tableNeedSave = true;
			}
			_thisObj.tableNeedPreview = true;

			var table = jQuery('.wtbpContentTable');
			table.off('lazy-load.dt');
			setTimeout(function() {
				_thisObj.previewShow();
			}, 1000);
		});

		$('.wtbpCssTab.button').on('click', function (e) {
			var cssText = $('#wtbpCssEditor').get(0);
			if(typeof(cssText.CodeMirrorEditor) === 'undefined') {
				if(typeof(CodeMirror) !== 'undefined') {
					var cssEditor = CodeMirror.fromTextArea(cssText, {
						mode: 'css',
						lineWrapping: true,
						lineNumbers: true,
						matchBrackets: true,
						autoCloseBrackets: true
					});
					cssEditor.on('change', function() {
						_thisObj.tableNeedSave = true;
						_thisObj.tableNeedPreview = true;
					});
					cssText.CodeMirrorEditor = cssEditor;
				}
			} else {
				cssText.CodeMirrorEditor.refresh();
			}
		});

		$('.wtbpJsTab.button').on('click', function (e) {
			var jsText = $('#wtbpJsEditor').get(0);
			if(typeof(jsText.CodeMirrorEditor) === 'undefined') {
				if(typeof(CodeMirror) !== 'undefined') {
					var jsEditor = CodeMirror.fromTextArea(jsText, {
						mode: 'JavaScript',
						lineWrapping: true,
						lineNumbers: true,
						matchBrackets: true,
						autoCloseBrackets: true
					});
					jsEditor.on('change', function() {
						_thisObj.tableNeedSave = true;
						_thisObj.tableNeedPreview = true;
					});
					jsText.CodeMirrorEditor = jsEditor;
				}
			} else {
				jsText.CodeMirrorEditor.refresh();
			}
		});

		// Initialize Content Tabs
		var $contentTabsContent = $('.row-content-tab .row'),
			$contentTabs = $('.wtbpContentTabs .button'),
			$currentContentTab = $contentTabs.filter('.current').attr('href');

		$contentTabsContent.filter($currentContentTab).addClass('active');

		$contentTabs.on('click', function (e) {
			e.preventDefault();

			var $this = $(this),
				$curTab = $this.attr('href');

			$contentTabsContent.removeClass('active');
			$contentTabs.filter('.current').removeClass('current');
			$this.addClass('current');
			$contentTabsContent.filter($curTab).addClass('active');
		});

		$("#wtbpAddButton").prop('disabled', false);
		_thisObj.redrawColumnList();
	});

	AdminPage.prototype.redrawColumnList = (function () {
		var found = false;
		$("#chooseColumns option").each(function(){
			var option = $(this);
			if(option.hasClass('wtbpHidden')){
				option.removeAttr('selected').prop('disabled', true);
			} else {
				option.prop('disabled', false);
				if(found) {
					option.removeAttr('selected');
				} else {
					$("#chooseColumns").val(option.val());
					option.attr('selected', 'selected');
					found = true;
				}
			}
		});
		if(!found){
			$("#chooseColumns").val('');
			$("#chooseColumns").css('disabled','disabled');
		}
	});

	AdminPage.prototype.eventsTables = (function () {
		var _thisObj = this.$obj;

		$('body').on('changeOrderPosition', function (e) {
			// help disabled multiple request for server
			if (_thisObj.tableContentReloading) {
				clearTimeout(_thisObj.tableContentReloading);
			}

			_thisObj.tableContentReloading = setTimeout(function () {
				_thisObj.loadProductsContentTbl();
				_thisObj.tableContentReloading = false;
			}, 2000);
		});

		$('body').on('addSelected', function (e) {
			e.preventDefault();
			if($('#wtbpSearchTable_wrapper .dt-buttons select[name="filter_in_table"]').val() == 'yes') return true;
			$('#wtbpAddProducts').prop('disabled', true);

			var productIdExist = $('#wtbpTablePressEditForm input[name="settings[productids]"]').val();
			var productIdSelected = [],
				productIdExcluded = [],
				filters = {
					search: {value: $('#wtbpSearchTable_filter input').val()},
					filter_author: $('#wtbpSearchTable_wrapper .dt-buttons select[name="filter_author"]').val(),
					filter_category: $('#wtbpSearchTable_wrapper .dt-buttons select[name="filter_category"]').val(),
					filter_tag: $('#wtbpSearchTable_wrapper .dt-buttons select[name="filter_tag"]').val(),
					filter_attribute: $('#wtbpSearchTable_wrapper .dt-buttons select[name="filter_attribute"]').val(),
					show_variations: $('#wtbpSearchTable_wrapper .dt-buttons input[name="show_variations"]').is(':checked') ? 1 : 0,
					filter_private: $('#wtbpSearchTable_wrapper .dt-buttons input[name="filter_private"]').is(':checked') ? 1 : 0
				},
				orders = $('#wtbpTablePressEditForm input[name="settings[order]"]').val();

			if($('#wtbpSearchTableSelectAll').val() == '1') {
				productIdSelected = 'all',
				productIdExcluded = $('#wtbpSearchTableSelectExclude').val().split(',');
			} else {
				productIdSelected = $('#wtbpSearchTableSelectExclude').val().split(',');
			}

			$('#wtbpSearchTable')
				.find('.selected')
				.removeClass('selected')
				.find('input[type="checkbox"]')
				.prop("checked", false);

			$('#wtbpSearchTable')
				.find('th input[type="checkbox"]')
				.prop("checked", false);
			$('#wtbpSearchTableSelectAll').val(0);
			$('#wtbpSearchTableSelectExclude').val('');

			var tableId = $("#wtbpTablePressEditForm").attr('data-table-id') || 0,
				pageLength = $('#wtbpContentTable_wrapper .wtbpContentTable_length');

			jQuery.sendFormWtbp({
				data: {
					mod: 'wootablepress',
					action: 'getProductContent',
					productIdSelected: productIdSelected,
					productIdExcluded: productIdExcluded,
					productIdExist: productIdExist,
					filters: filters,
					order: orders,
					tableid: tableId,
					start: 0,
					length: pageLength.length ? pageLength.val() : 10,
					returnIds: 1,
					sortCustom: $('[name="settings[sorting_custom]"]').is(':checked') && $('[name="settings[pre_sorting]"] option:selected').val() == '' ? 1 : 0
				},
				onSuccess: function(res) {
					if (res.errors.length === 0 && res.html.length > 0) {
						var el = $('#wtbpContentTable'),
							total = res.data['total'];
						if ($.fn.dataTable.isDataTable('#wtbpContentTable')) {
							_thisObj.tableContent.destroy();
						}
						el.html(res.html);
						_thisObj.initTable('tableContent', total);
						$('input[name="settings[productids]"]').val(res.data['ids']);
						_thisObj.tableSearch.ajax.reload();
						$('#wtbpSortTableContent').show();
						wtbSortableIconAdd();
						_thisObj.showTableNotices(res.data['notices']);
						_thisObj.tableNeedPreview = true;
					}
					$('#wtbpAddProducts').prop('disabled', false);
					$.sNotify({
						'icon': 'fa fa-check',
						'content': '<span>Product(s) added to the table</span>',
						'delay' : 1500
					});
				}
			});

		});

		$('body').on('removeSelected', function (e) {
			e.preventDefault();
			var removeAll = $('#wtbpContentTableSelectAll').val() == '1',
				removeExcluded = $('#wtbpContentTableSelectExclude').val(),
				productIdsInput = $('#wtbpTablePressEditForm input[name="settings[productids]"]'),
				reload = false;
			if(removeAll) {
				productIdsInput.val(removeExcluded);
				reload = true;
			} else if(removeExcluded.length > 0) {
				var removeIds = removeExcluded.split(','),
					productIds = productIdsInput.val().split(','),
					newIds = [];
				productIds.forEach(function (id, i) {
					if(removeIds.indexOf(id) == -1) {
						newIds.push(id);
					}
				});
				productIdsInput.val(newIds.join(','));
				reload = true;
			}
			$('#wtbpContentTableSelectAll').val(0);
			$('#wtbpContentTableSelectExclude').val('');
			if(reload) {
				_thisObj.loadProductsContentTbl(false, true);
			}
			$.sNotify({
				'icon': 'fa fa-check',
				'content': '<span>Product(s) removed from the table</span>',
				'delay': 1500,
			});
			$('#wtbpSearchTableSelectAll').val(0);
			$('#wtbpSearchTableSelectExclude').val('');
		});

		$('body').on('click', '#buttonDelete', function (e) {
			e.preventDefault();
			if(confirm('Are you sure want to delete table?')) {
				var id = $('#wtbpTablePressEditForm').attr('data-table-id');

				if (id) {
					jQuery.sendFormWtbp({
						data: {
							mod: 'wootablepress',
							action: 'deleteByID',
							id: id,
						},
						appendData: {wtbpNonce: window.wtbpNonce},
						onSuccess: function(res) {
							var redirectUrl = $('#wtbpTablePressEditForm').attr('data-href');
							if (!res.error) {
								toeRedirect(redirectUrl);
							}
						}
					});
				}
			}
		});

		$('body').on('click', '#buttonSave', function (e) {
			e.preventDefault();
			$('#wtbpTablePressEditForm').trigger('submit');
		});

		$('body').on('click', '#buttonClone', function (e) {
			e.preventDefault();
			var cloneDialog = jQuery('.wtbpCloneTableWrapp');
			if(cloneDialog.length) {
				var $error = cloneDialog.find('.wtbpCloneError'),
					$input = cloneDialog.find('input').val(jQuery('#wtbpTableTitleLabel').text()),
					tableid = $('#wtbpTablePressEditForm').attr('data-table-id'),
					$dialog = cloneDialog.removeClass('wtbpHidden').dialog({
						width: 480,
						modal: true,
						autoOpen: false,
						buttons: {
							Save: function (event) {
								$error.fadeOut();
								$dialog.dialog('close');
								jQuery.sendFormWtbp({
									btn: jQuery('#buttonClone'),
									data: {
										mod: 'wootablepress',
										action: 'cloneTable',
										id: tableid,
										title: $input.val()
									},
									appendData: {wtbpNonce: window.wtbpNonce},
									onSuccess: function(res) {
										if(!res.error) {
											if (res.data.edit_link) {
												toeRedirect(res.data.edit_link, true);
											}
										} else {
											$error.find('p').text(res.errors.title);
											$error.fadeIn();
										}
									}
								});
							},
							Cancel: function () {
								$dialog.dialog('close');
							}
						},
						create:function () {
							$(this).closest('.ui-dialog').addClass('woobewoo-plugin');
						}
					});

				$input.on('focus', function () {
					$error.fadeOut();
				});

				$dialog.dialog('open');
			}
		});
		var editForm = $('#wtbpTablePressEditForm');

		editForm.submit(function (e) {
			e.preventDefault();
			if(_thisObj.tableSaving) return false;

			_thisObj.tableSaving = true;
			_thisObj.tableNeedSave = false;
			_thisObj.prepareFormData();

			jQuery(this).sendFormWtbp({
				btn: jQuery('#buttonSave'),
				appendData: {wtbpNonce: window.wtbpNonce},
				onSuccess: function (res) {
					var currentUrl = window.location.href;
					if (!res.error && res.data.edit_link && currentUrl !== res.data.edit_link) {
						toeRedirect(res.data.edit_link);
					}
					jQuery('.icsComparisonSaveBtn i').attr('class', 'fa fa-check');
					_thisObj.tableSaving = false;
					_thisObj.tableNeedPreview = true;
				}
			});
			return false;

		});

		// Work with shortcode copy text
		$('#wtbpCopyTextCodeExamples').on('change', function (e) {
			var optName = $(this).val();
			switch (optName) {
				case 'shortcode' :
					$('.wtbpCopyTextCodeShowBlock').addClass('wtbpHidden');
					$('.wtbpCopyTextCodeShowBlock.shortcode').removeClass('wtbpHidden');
					break;
				case 'phpcode' :
					$('.wtbpCopyTextCodeShowBlock').addClass('wtbpHidden');
					$('.wtbpCopyTextCodeShowBlock.phpcode').removeClass('wtbpHidden');
					break;
			}
		});

		//Work with title
		$('#wtbpTableTitleShell').on('click', function () {
			$('#wtbpTableTitleLabel').addClass('wtbpHidden');
			$('#wtbpTableTitleTxt').removeClass('wtbpHidden');
		});

		//Work with title
		$('#wtbpTableTitleTxt').on('focusout', function () {
			var tableTitle = $(this).val();
			$('#wtbpTableTitleLabel').text(tableTitle);
			$('#wtbpTableTitleTxt').addClass('wtbpHidden');
			$('#wtbpTableTitleLabel').removeClass('wtbpHidden');
			$('#buttonSave').trigger('click');
		});
		
		$('.wtbp-only-numbers').on('input', function(e) {
			this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');
		});
	});

	AdminPage.prototype.prepareFormData = (function () {
		var cssText = jQuery('#wtbpCssEditor');
		if(typeof(cssText.get(0).CodeMirrorEditor) !== 'undefined') {
			cssText.val(cssText.get(0).CodeMirrorEditor.getValue());
		}
		var jssText = jQuery('#wtbpJsEditor');
		if(typeof(jssText.get(0).CodeMirrorEditor) !== 'undefined') {
			jssText.val(jssText.get(0).CodeMirrorEditor.getValue());
		}
		$('#wtbpTablePressEditForm select').each(function(){
			var $this = $(this),
				value = $(this).val();

			$this.find('option[value="'+value+'"]').prop('selected', false);
			$this.find('option').removeAttr('selected');
			$this.find('option[value="'+value+'"]').prop('selected', true);
			$this.find('option[value="'+value+'"]').attr('selected', 'selected');
			$(this).val(value);
		});
	});

	AdminPage.prototype.showTableNotices = (function (notices) {
		if (notices) {
			notices.forEach(function(notice){
				$('#wtbpSortTableContent').before(notice);
			});
		}
	});

	AdminPage.prototype.eventsProperties = (function () {
		var _thisObj = this.$obj;

		jQuery('#wtbpSetProperties').on('click', function (e) {
			e.preventDefault();
			var button = $(this)
				, butWrapper = button.parent();

			if (!button.hasClass('active')) {
				butWrapper.find('button').removeClass('active');
				button.addClass('active');
				$(_thisObj.tableSearchWrap).css('display', 'none');
				$(_thisObj.tablePropertiesWrap).css('display', 'block');
			}
		});

		//make properties sortable
		$(".wtbpPropertiesWrapp").sortable({
			containment: "parent",
			cursor: "move",
			stop: _thisObj.saveProperties,
			handle: ".wtbpOptionDragHandler"
		});
		//make properties sortable end

		$('body').on('click', '.wtbpOptionEditHandler', function (e) {
			e.preventDefault();
			var el = $(this),
				wrapper = el.closest('.wtbpOptions'),
				slug = wrapper.attr('data-slug'),
				plugin = wrapper.attr('data-plugin'),
				isPluginDisplay = wrapper.attr('data-plugin-display'),
				propType = $('#chooseColumns option[value="'+slug+'"]').attr('data-type'),
				options = [];
			try{
				var columns = JSON.parse($('#wtbpTablePressEditForm input[name="settings[order]"]').val());
				for(var i = 0; i < columns.length; i++) {
					if(columns[i]['slug'] == slug) {
						options = columns[i];
						break;
					}
				}
			}catch(e){
				options = [{'slug': slug}];
			}
			var columnNameHtml = wrapper.find('.content'),
				dialogHtml = $('.wtbpPropertiesChangeNameWrapp').removeClass('wtbpHidden');

			dialogHtml.find('span.originalName').text('original_name' in options ? options['original_name'] : columnNameHtml.text());
			dialogHtml.find('.wtbpOptionContainer').each(function() {
				var container = $(this),
					forProperties = container.attr('data-properties'),
					forNotProperties = container.attr('data-not-properties'),
					forPlugin =  container.attr('data-plugin'),
					forType = container.attr('data-types');
				container.removeClass('wtbpHidden');

				if(
					(typeof forProperties !== 'undefined' && forProperties != slug) ||
					(typeof forType !== 'undefined' && forType != propType) || 
					(typeof forNotProperties !== 'undefined' && forNotProperties.indexOf(slug) != -1) || 
					(typeof forPlugin !== 'undefined' && !isPluginDisplay)
				) {
					container.addClass('wtbpHidden');
				}

			});
			dialogHtml.find('input[data-one-from]').prop('disabled', false);
			dialogHtml.find('input, select, label').each(function() {
				var element = $(this);

				if(!element.closest('.wtbpOptionContainer').hasClass('wtbpHidden')) {
					var elName = element.attr('name'),
						elType = element.attr('type'),
						value = elName in options ? options[elName] : '';
					if(elType == 'radio') {
						element.prop('checked', element.attr('value') == (value == '' ? '0' : value));
					} else if(elType == 'checkbox') {
						element.prop('checked', value == 1);
						if (element.hasClass("wtbpDefaultChecked")) {
							if (value.length === 0) {
								element.prop('checked', true);
							}
						}
						if(element.prop('checked')) element.trigger('change');
					} else {
						if(element.is('select') && value == '') {
							element.find('option').prop('selected', false);
							element.find('option:first').prop('selected', true);
						} else {
							element.val(value);
						}
					}
					if(element.hasClass("wtbpHideByParent")) {
						var parentName = element.attr("data-parent"),
							parent = jQuery("body").find("[name="+parentName+"]"),
							hideBlock = element.closest('.wtbpHideByParentBlock');
						if(hideBlock.length == 0) {
							hideBlock = element;
						}
						if(parent.is('select')) {
							if(element.attr("data-parent-value") == parent.val()) {
								hideBlock.show();
							} else {
								hideBlock.hide();
							}

						} else {
							if(parent.prop("checked")) {
								hideBlock.show();
							} else {
								hideBlock.hide();
							}
						}
					}
					var fileWrapper = element.closest('.wtbpSelectFile');
					if(fileWrapper.length) {
						var img = fileWrapper.find('img');
						if(img.length) {
							img.attr('src', value.length ? value : img.attr('data-src'));
						}
					}
				}
			});
			var $dialog = dialogHtml.dialog({
				width: 370,
				modal: true,
				autoOpen: false,
				close: function () {
					$dialog.dialog('close');
				},
				buttons: {
					Change: function (event) {
						$dialog.find('input, select').each(function() {
							var element = $(this),
								elName = element.attr('name');
							if(element.closest('.wtbpOptionContainer').hasClass('wtbpHidden')) {
								delete options[elName];
							} else {
								var elType = element.attr('type');
								if(elType == 'radio') {
									options[elName] = $dialog.find('input[name="'+elName+'"]:checked').attr('value');
								} else if(elType == 'checkbox') {
									options[elName] = element.prop('checked') ? 1 : 0;
								} else {
									options[elName] = element.val();
								}
							}
						});
						options['original_name'] = $dialog.find('span.originalName').text();
						columnNameHtml.text(options['show_display_name'] == '1' ?  options['display_name'] : options['original_name']);

						_thisObj.saveProperties('update', options);
						$dialog.dialog('close');
					},
					Cancel: function () {
						$dialog.dialog('close');
					}
				},
				create:function () {
					$(this).closest(".ui-dialog").addClass('woobewoo-plugin');
				}
			});

			$dialog.dialog('open');
		});
		$('body').on('click', '.wtbpOptionContainer input[type=checkbox]', function(e) {
			var element = jQuery(this),
				elName = element.attr("name"),
				elHiddenChildren = jQuery('body').find('[data-parent='+elName+']'),
				elIsChecked = element.prop('checked');

			if (elHiddenChildren.length > 0) {
				if (elIsChecked) {
					elHiddenChildren.show();
				} else {
					elHiddenChildren.hide();
				}
			}
		});
		$('body').on('change', '.wtbpOptionContainer input[data-one-from]', function(e) {
			var element = jQuery(this),
				elName = element.attr('name'),
				elGroup = element.attr('data-one-from');
			if(element.prop('checked')) {
				jQuery('.wtbpOptionContainer input[data-one-from="'+elGroup+'"]:not([name="'+elName+'"])').prop('checked', false).prop('disabled', true);
			} else {
				jQuery('.wtbpOptionContainer input[data-one-from="'+elGroup+'"]').prop('disabled', false);
			}
		});
		$('body').on('change', '.wtbpOptionContainer select', function(e) {
			var element = jQuery(this),
				container = element.closest('.wtbpOptionContainer'),
				elName = element.attr('name'),
				elValue = element.val(),
				children = container.find('[data-parent='+elName+']');
			if(children.length > 0) {
				children.closest('.wtbpHideByParentBlock').hide();
				container.find('[data-parent='+elName+'][data-parent-value='+elValue+']').closest('.wtbpHideByParentBlock').show();
			}
		});
		$('body').on('click', '.wtbpSelectFileButton', function(e){
			e.preventDefault();
			var $button = $(this),
				$input = $button.parent().find('input'),
				$img = $button.parent().find('img'),
				_custom_media = true;
			wp.media.editor.send.attachment = function(props, attachment){
				wp.media.editor._attachSent = true;
				if(_custom_media) {
					var selectedUrl = attachment.url;
					if(props && props.size && attachment.sizes && attachment.sizes[ props.size ] && attachment.sizes[ props.size ].url) {
						selectedUrl =  attachment.sizes[ props.size ].url;
					}
					$input.val(selectedUrl).trigger('change');
					if($img.length) $img.attr('src', selectedUrl);
				} else {
					return _orig_send_attachment.apply( this, [props, attachment] );
				}
			};
			wp.media.editor.insert = function(html) {
				if(_custom_media) {
					if(wp.media.editor._attachSent) {
						wp.media.editor._attachSent = false;
						return;
					}
					if(html && html != "") {
						var selectedUrl = $(html).attr('src');
						if(selectedUrl) {
							$input.val(selectedUrl).trigger('change');
							if($img.length) $img.attr('src', selectedUrl);
						}
					}
				}
			};
			wp.media.editor.open($button);
			$('.attachment-filters').val($button.data('type')).trigger('change');
			return false;
		});

		$('body').on('click', '.wtbpOptionRemoveHandler', function (e) {
			e.preventDefault();
			var wrapper = $(this).closest('.wtbpOptions'),
				slug = wrapper.attr('data-slug');
			wrapper.remove();
			$('#chooseColumns option[value="'+slug+'"]').removeClass('wtbpHidden').prop('disabled', false);
			_thisObj.saveProperties('delete', {'slug': slug});
		});

		$('#chooseColumns').on('change', function (e) {
			e.preventDefault();
			var selectedProperty = $('#chooseColumns option:selected');
			$('.wtbpProColumn, #wtbpAddButton').css('display','none');

			if ( selectedProperty.length == 0 || selectedProperty.attr('data-enabled') == '1' ) {
				$('#wtbpAddButton').css('display', 'inline-block')
			} else {
				$('.wtbpProColumn[data-type=' + selectedProperty.attr('data-type') + ']').css('display', 'inline-block')
			}
		}).trigger('change');

		$('#wtbpAddButton').on('click', function (e) {
			e.preventDefault();
			var selected = $('#chooseColumns').find(":selected")
				, slug = selected.attr('value')
				, name = selected.attr('data-name')
				, plugin = selected.attr('data-plugin')
				, isPluginDisplay = selected.attr('data-plugin-display')
				, template = $('.wtbpOptionsEmpty').clone()
				, propertiesWrapp = $('.wtbpPropertiesWrapp');

			if(!$('.wtbpPropertiesWrapp .wtbpOptions[data-slug="'+slug+'"]').length && slug ) {
				//make add button not active
				jQuery('#wtbpSearchTable_wrapper .dt-buttons button').attr('disabled', 'disabled');
				template.removeClass('wtbpOptionsEmpty wtbpHidden');
				template.attr('data-name', name);
				template.attr('data-slug', slug);
				template.attr('data-plugin', plugin);
				template.attr('data-plugin-display', isPluginDisplay);
				template.find('.content').html(name);
				propertiesWrapp.append(template);
				$('#chooseColumns option[value="'+slug+'"]').addClass('wtbpHidden').attr('disabled', 'disabled');
				_thisObj.saveProperties('add', {slug: slug, original_name: name});
			}
		});
	});

	AdminPage.prototype.saveProperties = (function (mode, options) {
		var orders = $('#wtbpTablePressEditForm input[name="settings[order]"]'),
			slug = options ? options.slug : false,
			_thisObj = this.$obj ? this.$obj : app.WtbpAdminPage.$obj;
		try {
			var properties = JSON.parse(orders.val());
		} catch(e) {
			var properties = [];
		}
		_thisObj.tableNeedSave = true;
		_thisObj.tableNeedPreview = true;

		if(typeof mode === 'object') {
			var currentOrder = {},
				i = 0;
			$('.wtbpPropertiesWrapp .wtbpOptions').not('.wtbpEmptyOptions').each(function (index) {
				i++;
				currentOrder[$(this).attr('data-slug')] = i;
			});
			properties.sort(function(a, b){
				return currentOrder[a.slug] - currentOrder[b.slug];
			});
		} else if(mode == 'add') {
			properties.push(options);
		} else {
			for(var i = 0; i < properties.length; i++) {
				if('slug' in properties[i] && properties[i]['slug'] == slug) {
					if(mode == 'update') {
						properties[i] = options;
					} else if(mode == 'delete') {
						properties.splice(i, 1);
					}
					break;
				}
			}
		}
		var uniq = [],
			forDelete = [];
		for(var i = 0; i < properties.length; i++) {
			if('slug' in properties[i]) {
				var slug = properties[i]['slug'];
				if(toeInArrayWtbp(slug, uniq)) {
					forDelete.push(i);
				} else {
					uniq.push(slug);
				}
			} else {
				forDelete.push(i);
			}
		}
		if(forDelete.length) {
			for (var i = forDelete.length - 1; i >= 0; i--) {
				properties.splice(forDelete[i], 1);
			}
		}
		if(mode == 'add' || mode == 'delete') {
			$("#wtbpAddButton").prop('disabled', false);
			_thisObj.redrawColumnList();
		}
		$('#chooseColumns').trigger('change');

		var propertiesJson = JSON.stringify(properties);
		$('input[name="settings[order]"]').val(propertiesJson);

		$(document.body).trigger('changeOrderPosition');
	});

	AdminPage.prototype.loadProductsSearchTbl = (function () {
		var _thisObj = this.$obj;
		_thisObj.initTable('tableSearch');

		jQuery('#wtbpAddProducts').on('click', function (e) {
			e.preventDefault();
			var w = $(window).width() * 0.95,
				h = $(window).height() * 0.95;

			var $dialog = $('.wtbpSearchTableWrapp').removeClass('wtbpHidden').dialog({
				width: w,
				height: h,
				modal: true,
				autoOpen: false,
				title: "Manage Table Contents",
				close: function () {
					$dialog.dialog('close');
				},
				buttons: {
					'Add selected products to the table': function (event) {
						$(document.body).trigger('addSelected');
						$dialog.dialog('close');
					},
					Cancel: function () {
						$dialog.dialog('close');
					}
				},
				create:function () {
					$(this).closest('.ui-dialog').addClass('woobewoo-plugin');
				}
			});

			$dialog.dialog('open');
		});
	});

	AdminPage.prototype.loadProductsContentTbl = (function (byCategories, searchReload, byVariable) {
		var _thisObj = this.$obj,
			byCategories = typeof(byCategories) == 'undefined' ? false : byCategories,
			searchReload = typeof(searchReload) == 'undefined' && !byCategories ? false : searchReload,
			byVariable = typeof(byVariable) == 'undefined' ? false : byVariable,
			wtbpForm = $('#wtbpTablePressEditForm'),
			productsInput = wtbpForm.find('input[name="settings[productids]"]'),
			autoEnabled = wtbpForm.find('input[name="settings[auto_categories_enable]"]'),
			autoCategories = byCategories && autoEnabled.length && autoEnabled.is(':checked') ? wtbpForm.find('input[name="settings[auto_categories_list]"]').val().split(',') : [],
			autoVariableEnabled = wtbpForm.find('input[name="settings[auto_variations_enable]"]'),
			autoVariables = byVariable && autoVariableEnabled.length && autoVariableEnabled.is(':checked') ? wtbpForm.find('input[name="settings[auto_variations_list]"]').val().split(',') : [];
			
			//if exist post id show them in second tables
		if(productsInput.val().length || autoCategories.length || autoVariables.length) {
			var productIdExist = productsInput.val();
			var orders = wtbpForm.find('input[name="settings[order]"]').val(),
				tableId = wtbpForm.attr('data-table-id') || 0,
				loading = wtbpForm.find('#wtbpContentTable_processing'),
				pageLength = $('#wtbpContentTable_wrapper .wtbpContentTable_length');
			loading.css('display', 'block');

			jQuery.sendFormWtbp({
				data: {
					mod: 'wootablepress',
					action: 'getProductContent',
					productIdExist: productIdExist,
					autoCategories: autoCategories,
					autoVariables: autoVariables,
					order: orders,
					tableid: tableId,
					start: 0,
					length: pageLength.length ? pageLength.val() : 10,
					returnIds: 1,
					sortCustom: $('[name="settings[sorting_custom]"]').is(':checked') && $('[name="settings[pre_sorting]"] option:selected').val() == '' ? 1 : 0
				},
				onSuccess: function(res) {
					if(!res.error) {
						if (res.errors.length === 0 && res.html.length > 0) {
							var el = $('#wtbpContentTable'),
								total = res.data['total'];
							if($.fn.dataTable.isDataTable('#wtbpContentTable')) {
								_thisObj.tableContent.destroy();
							}
							el.html(res.html);
							_thisObj.initTable('tableContent', total);

							$('#wtbpSortTableContent').show();
							productsInput.val(res.data['ids']);
							if(searchReload) {
								_thisObj.tableSearch.ajax.reload();
							}

							_thisObj.showTableNotices(res.data['notices']);
						}
						wtbSortableIconAdd();
					}
					loading.css('display', 'none');
				}
			});
		} else {
			$('#wtbpSortTableContent').hide();
		}
		//make add button active again
		jQuery('#wtbpSearchTable_wrapper .dt-buttons button').removeAttr('disabled');
		_thisObj.tableNeedSave = true;
		_thisObj.tableNeedPreview = true;
	});

	AdminPage.prototype.getSettingsFormData = (function () {
		this.$obj.prepareFormData();
		var $form = jQuery('#wtbpTablePressEditForm').clone(),
			multisel = jQuery('#wtbpTablePressEditForm').find('select[multiple]');
		$form.find('[name="shortcode_example"], [name="mod"], [name="action"], [name="id"], [name="settings[productids]"], input[name="settings[order]"]').remove();
		jQuery('#wtbpTablePressEditForm').find('select[multiple]').each(function() {
			var elem = jQuery(this);
			$form.find('select[name="' + elem.attr('name') + '"]').val(elem.val());
		});
		return $form.serialize();
	});

	AdminPage.prototype.previewShow = (function () {
		var _thisObj = this.$obj;
		if(!_thisObj.tablePreviewEnabled || !_thisObj.tableNeedPreview) return;
		
		if(_thisObj.tablePreviewTimeout && Date.now() - _thisObj.tablePreviewTimeout < 5000) {
			setTimeout(function() {	_thisObj.previewShow(); }, 50);
			return;
		}
		if(_thisObj.tablePreviewReloading || _thisObj.tableSSPReloading || _thisObj.tableSaving) {
			if(!_thisObj.tablePreviewTimeout) _thisObj.tablePreviewTimeout = Date.now();
			setTimeout(function() {	_thisObj.previewShow(); }, 500);
			return;
		}
		
		$('.wtbpAdminPreviewNotice').addClass('wtbpHidden');
		$('#loadingProgress').removeClass('wtbpHidden');
		if(_thisObj.tableNeedSave) $('#buttonSave').trigger('click');
		
		if(_thisObj.tableSaving) {
			setTimeout(function() {	_thisObj.previewShow(); }, 50);
			return;
		}

		_thisObj.tablePreviewTimeout = false;
		_thisObj.tablePreviewReloading = true;
		_thisObj.tableNeedPreview = false;
		var form = $('#wtbpTablePressEditForm'),
			productids = form.find('input[name="settings[productids]"]').val(),
			autoAll = form.find('input[name="settings[auto_categories_enable]"]').is(':checked') && form.find('input[name="settings[auto_categories_list]"]').val() == 'all';

		if(productids.length || autoAll) {
			var productIdExist = productids,
				orders = form.find('input[name="settings[order]"]').val(),
				tableId = form.attr('data-table-id') || 0,
				prewiewTab = $('.wtbPreviewTab').not('.disabled').data('preview-tab');

			jQuery.sendFormWtbp({
				data: {
					mod: 'wootablepress',
					action: 'getProductContent',
					productIdExist: productIdExist,
					order: orders,
					tableid: tableId,
					frontend: true,
					prewiew: true,
					prewiewTab: prewiewTab,
					settings: _thisObj.getSettingsFormData()
				},
				onSuccess: function(res) {
					if (!res.error && res.html.length > 0) {
						var el = $('#wtbpPreviewTable'),
							wrapper = $('#wtbp-table-preview'),
							tableWrapper = $('#preview-container .wtbpTableWrapper'),
							settings = res.data['settings'],
							filter = res.data['filter'],
							customCss = res.data['css'];

						if (_thisObj.tablePreview !== null && typeof _thisObj.tablePreview === 'object') {
							_thisObj.tablePreview.destroy();
							wrapper.find('*').not('#wtbpPreviewTable, #wtbpPreviewFilter, .wtbpCustomCssWrapper').remove();
						}
						el.replaceWith('<table id="wtbpPreviewTable" class="wtbpContentTable" data-table-id="'+tableId+'">'+res.html+'</table>');
						wrapper.find('#wtbpPreviewFilter').empty().append(filter);
						wrapper.find('.wtbpCustomCssWrapper').html(customCss);

						$.fn.dataTableExt.afnFiltering.length = 0;
						$.fn.dataTable.ext.classes.sPageButton = 'paginate_button';
						$.fn.dataTable.ext.classes.sLengthSelect = '';
						_thisObj.tablePreview = app.WooTablepress.initializeTable(tableWrapper, function () {}, settings);
						wrapper.find('.wtbpLoader').addClass('wtbpHidden');
						$('.wtbpAdminPreviewNotice').addClass('wtbpHidden');
						$('#loadingFinished').removeClass('wtbpHidden');
						_thisObj.tablePreview.columns.adjust().draw();
						_thisObj.tablePreview.fixedHeader.adjust();
						$('#wtbpPreviewTable').show();
						_thisObj.tablePreviewReloading = false;
						wtbpInitMap();
					}
				}
			});
		} else {
			$('.wtbpAdminPreviewNotice').addClass('wtbpHidden');
			$('#loadingEmpty').removeClass('wtbpHidden');
			$('#wtbpPreviewTable').hide();
			_thisObj.tablePreviewReloading = false;
		}
	});

	AdminPage.prototype.previewShowEvent = (function () {
		var _thisObj = this.$obj;

		$('body').on('click', '.wtbpSettingTab', function (e) {
			e.preventDefault();
			_thisObj.previewShow();
		});
	});

	AdminPage.prototype.saveContentIdsToInput = (function () {
		var _thisObj = this.$obj;
		if (_thisObj.tableContent) {
			var contentData = _thisObj.tableContent.rows().data().toArray();
			var postids = [];
			contentData.forEach(function (row, i) {
				row = Object.values(row);
				row.forEach(function (column, j) {
					//get only id value
					if (j === 0) {
						var html = $.parseHTML(column)
							, id = $(html).attr('data-id');

						postids.push(id);
					}
				});
			});
			$('input[name="settings[productids]"]').val(postids);
		}
	});

	$(document).ready(function () {
		app.WtbpAdminPage = new AdminPage();
		app.WtbpAdminPage.init();
	});

	//Custom Drag and Drop sorting functional START

	//Replace ROW position by input index
	$("body").on("click",".wtbpSortableIcon", function(){
		var dataTable = $("#wtbpContentTable").dataTable(),
			rowCount = $('input[name="settings[productids]"]').val().split(',').length,
			newIndex = prompt("Set a new position index for this product (from 1 to "+rowCount+")", ""),
			oldIndex = $(this).parent().index(),
			dataTableLength =  dataTable.api().data().page.info().length,
			dataTablePage = Number(dataTable.api().page() * dataTableLength);
		oldIndex = Number(oldIndex + dataTablePage);
		newIndex = Number(newIndex);
		if ( (newIndex > rowCount) || (newIndex <= 0) ) {
			return false;
		}
		wtbMoveSortable (oldIndex, newIndex);
	});
	function wtbExistsContentTable () {
		return app.WtbpAdminPage && app.WtbpAdminPage.tableContent !== false;
	}
	function wtbMoveSortable (oldIndex, newIndex) {
		if (!wtbExistsContentTable()) return;
		var dataTable = $("#wtbpContentTable").dataTable(),
			startData = dataTable.api().row(oldIndex).data(),
			productIds = $('input[name="settings[productids]"]').val().split(',');
		newIndex = Number(newIndex) - 1;
		if(newIndex == oldIndex) return false;
		productIds.splice(newIndex, 0, productIds.splice(oldIndex, 1)[0]);
		$('input[name="settings[productids]"]').val(productIds.join(','));
		dataTable.api().order([]);
		dataTable.api().draw(false);
	}
	//Add sortable icons to dataTable
	function wtbSortableIconAdd () {
		if (!wtbExistsContentTable()) return;
		var dataTable = $("#wtbpContentTable").dataTable();
		$("#wtbpContentTable").find("th.wtbpSortableIconThead, th.wtbpSortableIconTfoot, td.wtbpSortableIcon").remove();
		if($('[name="settings[sorting_custom]"]').is(':checked') && $('[name="settings[pre_sorting]"] option:selected').val() == '') {
			$("#wtbpContentTable").find("thead tr").prepend("<th class='wtbpSortableIconThead' style='width:10px;'></th>");
			$("#wtbpContentTable").find("tfoot tr").prepend("<th class='wtbpSortableIconTfoot' style='width:10px;'></th>");
			$(dataTable.fnGetNodes()).each(function(){
					$(this).prepend("<td class='wtbpSortableIcon'><i class='fa fa-arrows-v' aria-hidden='true'></i></td>");
			});
		}
	}
	//Remove sortable icons from dataTable
	function wtbSortableIconRemove () {
		if (!wtbExistsContentTable()) return;
		var dataTable = $("#wtbpContentTable").dataTable();
		$("#wtbpContentTable").find(".wtbpSortableIconThead").remove();
		$("#wtbpContentTable").find(".wtbpSortableIconTfoot").remove();
		$(dataTable.fnGetNodes()).each(function(){
			$(this).find(".wtbpSortableIcon").remove();
		});
	}

	function wtbUpdateProductIds (sortable) {
		if (!wtbExistsContentTable()) return;
		if(typeof sortable == 'undefined') sortable = false;
		var productIds = [];
		var dataTable = $("#wtbpContentTable").dataTable();
		$(dataTable.fnGetNodes()).each(function(){
			dataId = $(this).find("[data-id]").attr('data-id');
			productIds.push(dataId);
		})
		if (sortable == true) {
			productIds.reverse();
		}
		productIds.join();
		$('input[name="settings[productids]"]').val(productIds);
	}
	//Toggle checkbox, select input and toggle sortable icon by sorting_custom checked status
	var customSorting = $('[name="settings[sorting_custom]"]'),
		preType = $('[name="settings[pre_sorting]"]').closest('.setting-wrapper'),
		preDesc = $('[name="settings[pre_sorting_desc]"]').closest('.setting-wrapper'),
		frontSorting = $('[name="settings[sorting]"]'),
		frontDefault = $('[name="settings[sorting_default]"]').closest('.setting-wrapper'),
		frontDesc = $('[name="settings[sorting_desc]"]').closest('.setting-wrapper');
	customSorting.on('change', function() {
		if ($(this).is(':checked')) {
			preType.removeClass('wtbpHidden');
			if(frontSorting.is(':checked')) {
				frontDefault.addClass('wtbpHidden');
				frontDesc.addClass('wtbpHidden');
			}
			//$('body').find('.wtbpContentAdmTable tbody.ui-sortable').sortable("enable");
			//wtbSortableIconAdd();
		} else {
			preType.addClass('wtbpHidden');
			if(frontSorting.is(':checked')) {
				frontDefault.removeClass('wtbpHidden');
				frontDesc.removeClass('wtbpHidden');
			}
			//$('body').find('.wtbpContentAdmTable tbody.ui-sortable').sortable("disable");
			//wtbSortableIconRemove();
		}
		preType.trigger('change');
	});
	preType.on('change', function() {
		preDesc.addClass('wtbpHidden');
		if (customSorting.is(':checked') && $(this).find('option:selected').val() == '') {
			$('body').find('.wtbpContentAdmTable tbody.ui-sortable').sortable("enable");
			wtbSortableIconAdd();
		} else {
			$('body').find('.wtbpContentAdmTable tbody.ui-sortable').sortable("disable");
			wtbSortableIconRemove();
			preDesc.removeClass('wtbpHidden');
		}
	});
	$('[name="settings[sorting]"]').on('change', function() {
		if ($(this).is(':checked') && !customSorting.is(':checked')) {
			frontDefault.removeClass('wtbpHidden');
			frontDesc.removeClass('wtbpHidden');
		} else {
			frontDefault.addClass('wtbpHidden');
			frontDesc.addClass('wtbpHidden');
		}
	});

}(window.jQuery, window.woobewoo));
