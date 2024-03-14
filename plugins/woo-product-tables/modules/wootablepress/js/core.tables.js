(function (vendor, $, window) {
	"use strict";
	var wooTableInstances = [];

	var appName = 'WooTablepress';
	if (!(appName in vendor)) {
		vendor = vendor[appName] = {};
	}

	vendor.initializeTable = (function initializeTable(tableWrapper, finalCallback, settingsIn) {
		var table = tableWrapper.find('.wtbpContentTable'),
			__this = this,
			viewId = tableWrapper.data('table-id'),
			isPreview = viewId == 'wtbpPreviewTable',
			settings = this.getSetting(settingsIn, table),
			printCaps = settings.print_captions,
			printLogo = settings.print_logo,
			printLogoUrl = settings.print_logo_url,
			isSSP = this.checkSettings(settings, 'pagination', false) && this.checkSettings(settings, 'pagination_ssp', false),
			description = settings.description_text,
			adminPage = isPreview && isSSP && window.woobewoo && window.woobewoo.WtbpAdminPage ? window.woobewoo.WtbpAdminPage : false;

		if(adminPage) adminPage.tableSSPReloading = true;

		this.removeTableInstanceById(viewId);
		
		if ( printCaps !== '1' || settings.description_enable !== '1' ) {
			description = '';
		}
		if ( printLogo && printLogoUrl ) {
			description = '<img style="max-width:200px;height:auto;display:block;margin:10px auto;" src="'+printLogoUrl+'" alt="">' + description;
		}

		/**
		 * Theme compatibility
		 *
		 * @link https://www.elegantthemes.com/gallery/divi
		 */
		if (jQuery('body').hasClass('theme-Divi')) {
			table.css('margin', '0');
			table.find('tr').css('padding', '10px 0 10px 0');
			table.find('th').css('padding', '10px 0 10px 0');
			table.find('th').css('box-sizing', 'content-box');
			table.find('tr').css('box-sizing', 'content-box');
			table.find('td').css('box-sizing', 'content-box');
		}

		var objAttr = {
			dom: 'B<"wtbpFilters">lftrip',
			columnDefs: [ {
				"targets": 'no-sort',
				"orderable": false
			} ],
			order: [],
			ColReorder: false,
			buttons: [{
				extend: 'print',
				className: 'button',
				exportOptions: {
					stripHtml: false,
					columns: []
				},
				text: this.checkSettings(settings, 'print_text', 'Print'),
				title: printCaps === '1' && settings.caption_enable === '1' ? settings.caption_text : '*',
				repeatingHead: {
					title: printCaps === '1' && settings.caption_enable === '1' ? settings.caption_text : null,
					description: description,
				},
				repeatingFoot: {
					signature: printCaps === '1' && settings.signature_enable === '1' ? settings.signature_text : null
				},
				customize: function (win) {
					var printTable = $(win.document.body).find('table').css('font-size', '80%'),
						tableStyles = jQuery('#'+tableWrapper.attr('id')+'-css'),
						lazyloads = $(win.document.body).find('img.lazyload,img.jetpack-lazy-image');
					printTable.removeClass('wtbpScrollTop');
					printTable.find('a').removeAttr('href');
					printTable.find('.woocommerce-Price-amount').css('white-space', 'nowrap').closest('td').css('white-space', 'nowrap');
					printTable.find('.wtbpNoBreak').css('white-space', 'nowrap');
					printTable.find('td, th').css('word-break', 'normal');
					printTable.find('th').each(function(index){
						if ($(this).text().indexOf('colAttrHide') > -1) {
							$(this).remove();
							var num = index + 1;
							printTable.find('td').filter(':nth-child('+num+')').remove();
						}
					});

					if (typeof lazyloads.removeClass === "function" && typeof lazyloads.removeAttribute === "function") {
						lazyloads.removeClass('jetpack-lazy-image');
						lazyloads.removeAttribute('srcset');
					}

					if (lazyloads.length) {
						lazyloads.each(function(){
							var img = jQuery(this),
								src = img.attr('data-src') ? img.attr('data-src') : img.attr('data-lazy-src');
							img.removeAttribute('srcset');
							if (src) {
								img.attr('src', src);
								img.show();
							}
						});
					}
					$(win.document.body).find('h1').remove();
					
					$(win.document.body).find('.table-title').css({textAlign:'center',fontSize:'20px',fontWeight:'bold',padding:'10px',border:'none'});
					$(win.document.body).find('.table-description').css({textAlign:'center',fontSize:'14px',paddingBottom:'10px',border:'none'});
					$(win.document.body).find('.table-signature').css({textAlign:'center',fontSize:'14px',paddingBottom:'10px',paddingTop:'5px',border:'none'});
					if(tableStyles.length) {
						$(win.document.head).append('<style type="text/css" media="print">body {-webkit-print-color-adjust: exact;}'+tableStyles.html()+'</style>');
						printTable.attr('id', viewId).wrap('<div id="'+viewId+'_wrapper"></div>');
					}
				}
			},],
			fnDrawCallback :function(){
				if (adminPage) adminPage.tableSSPReloading = false;
				if ( jQuery(this).closest('.wtbpTableWrapper').find('.dataTables_paginate span .paginate_button').length <= 1) {
					jQuery(this).closest('.wtbpTableWrapper').find('.dataTables_paginate').hide();
				} else {
					jQuery(this).closest('.wtbpTableWrapper').find('.dataTables_paginate').show();
				}
				__this.controlScrollPosition(tableWrapper, table);
				tableWrapper.find('.star-rating .star-rating-width').each(function() {
					var rating = $(this);
					rating.css('width', rating.attr('data-width'));
				});
				tableWrapper.find('.wtbp-color-picker').each(function() {
					var color = jQuery(this).data('color-picker'),
						td = jQuery(this).closest('td');

						if (jQuery(this).parent().hasClass('acf-child-wrapper')) {
							td.attr('style', 'background-color: ' + color + ' !important;');
						} else {
							jQuery(this).parent().attr('style', 'background-color: ' + color + ' !important; height: 50px;');
						}
				});
				var pageInfo = this.api().page.info();
				jQuery(this).closest('.wtbpTableWrapper').attr('data-start', pageInfo.start);

				/**
				 * Theme compatibility
				 *
				 * @link https://avada.theme-fusion.com
				 */
				if (typeof window.avadaAddQuantityBoxes !== 'undefined') {
					window.avadaAddQuantityBoxes();
				}
				/**
				 * Plugin compatibility
				 *
				 * @link https://wordpress.org/plugins/jetpack/
				 */
				if (typeof window.jetpackLazyImagesModule !== 'undefined') {
					window.jetpackLazyImagesModule();
				}
			}
		};
		if(typeof(this.setSettingsPro) == 'function') {
			objAttr = this.setSettingsPro(settings, objAttr, tableWrapper);
		}
		var indexColMultipleAdd = tableWrapper.find('[data-key=check_multy]').index();
		if (indexColMultipleAdd == 0 && tableWrapper.find('[data-key=check_multy] [data-position="last"]').length) {
			objAttr.colReorder = true;
			objAttr.ColReorder = true;
		}
		objAttr = this.setColumnsSettings(objAttr, settings);
		objAttr = this.addPagination(objAttr, settings, viewId);
		objAttr = this.addLanguage(objAttr, settings);
		objAttr = this.enableFixedHeader(objAttr, settings);
		objAttr = this.initResponsiveMode(objAttr, settings, tableWrapper);
		objAttr = this.initFinalCallBack(objAttr, finalCallback);
		this.addColumnSearching(table, settings);
		var tableObj = table.DataTable(objAttr);
		tableObj.table_id = viewId;
		tableObj.isSSP = isSSP;

		if (indexColMultipleAdd != -1 && tableObj.column(indexColMultipleAdd).visible()) {
				var dataKey = tableWrapper.find('th').eq(indexColMultipleAdd).attr('data-key');
				if (dataKey === 'check_multy') {
					tableObj.column(indexColMultipleAdd).visible(false);
					//tableObj.hide_table_column = tableObj.column( tableWrapper.find('[data-key=check_multy]').attr('data-column') );
					tableObj.hide_table_column = indexColMultipleAdd;
					tableObj.hide_check_multy = true;
				}
		}

		var varPriceColumn = this.checkSettings(settings, 'var_price_column', false) == '1';
		tableWrapper.off( 'change', '.wtbpVarAttribute').on( 'change', '.wtbpVarAttribute', function() {
			var select = jQuery(this),
				wrapper = select.closest('.wtbpVarAttributes'),
				variations = JSON.parse(wrapper.attr('data-variations')),
				td = select.closest('td'),
				curAttr = select.attr('data-attribute'),
				curValue = select.val(),
				otherAttrs = wrapper.find('select:not([data-attribute="'+curAttr+'"])'),
				tr = td.closest('tr'),
				multy = tr.find('.wtbpAddMulty'),
				thumbnail = tr.hasClass('child') ? tr.prev().find('td.thumbnail') : tr.find('td.thumbnail'),
				sku = tr.hasClass('child') ? tr.prev().find('td.sku') : tr.find('td.sku'),
				addButton = td.find('.button.product_type_variable, .button.product_type_variable-subscription'),
				inputQty = td.find('input[name=quantity]'),
				tdPrice = varPriceColumn ? tr.find('span.wtbpPrice') : tr.find('td.price'),
				replacePrice = varPriceColumn && tdPrice.length == 1,
				wrapperButton = addButton.closest('.wtbpAddToCartWrapper'),
				isPopup = wrapperButton.hasClass('wtbpHasPopupVariations'),
				addButtonText = wrapperButton.data('cart-text'),
				addButtonTextDefault = wrapperButton.data('default-cart-text'),
				attributes = [];

			if(varPriceColumn && !replacePrice) {
				if(tr.hasClass('child')) {
					tdPrice = tr.prev().find('span.wtbpPrice');
				} else if (tr.next().hasClass('child')) {
					tdPrice = tr.next().find('span.wtbpPrice');
				}
				if(tdPrice.length == 1) replacePrice = true;
			}

			otherAttrs.each(function () {
				var current = $(this);
				attributes.push({name: current.attr('data-attribute'), value: current.val()});
				current.find('option:not([value=""])').css('display', 'none');
			});

			if(replacePrice) {
				if(td.find('.wtbpVarPriceDefault').length == 0) {
					$('<div class="wtbpVarPriceDefault"></div>').html(tdPrice.html()).appendTo(td);
				}
			}

			td.find('.wtbpVarPrice').addClass('wtbpHidden');

			if (typeof td.parent().find('.stock-count') !== 'undefined') {
				var stockItem = td.parent().find('.stock-count[data-quantity]').parent();
				if (curValue === '') {
					stockItem.removeClass('wtbpHidden');
					stockItem.prev('br').removeClass('wtbpHidden');
				} else {
					stockItem.prev('br').addClass('wtbpHidden');
					stockItem.addClass('wtbpHidden');
					//let stockItemCurrent = td.parent().find('.stock-count[data-attribute="' + curValue + '"][data-quantity]').parent();
					let stockItemCurrent = td.parent().find('.stock-count[data-attribute][data-quantity]').filter(function() {
						return $(this).attr('data-attribute') == curValue;
					}).parent();
					stockItemCurrent.removeClass('wtbpHidden');
					stockItemCurrent.prev('br').removeClass('wtbpHidden');
				}
			}

			if(thumbnail.length) {
				thumbnail.find('a:not(.wtbpMainImage)').remove();
				let thumbnailNotGallery = thumbnail.find('div:not(.wtbpGalleryImage)>a.wtbpMainImage');
				let thumbnailHasGallery = td.parent().find('td.thumbnail>a.wtbpMainImage');
				thumbnailNotGallery.removeClass('wtbpHidden');
				thumbnailHasGallery.removeClass('wtbpHidden');
			}

			if(sku.length) {
				sku.find('[data-variation-id]').addClass('wtbpHidden');
				sku.find('[data-default]').removeClass('wtbpHidden');
			}
			if (curValue == '' && isPopup) {
				addButton.text(addButtonTextDefault);
			}
			addButton.attr('data-variation_id', 0).closest('.wtbpAddToCartWrapper').addClass('wtbpDisabledLink');
			addButton.attr('data-attribute_'+curAttr, '');
			multy.prop('disabled', true).attr('data-variation_id', 0);
			var attrLen = attributes.length,
				found = false;

			for(var id in variations) {
				var attrs = variations[id],
					match = (!(curAttr in attrs) || attrs[curAttr] == '' || attrs[curAttr] == curValue);

				if(match || curValue == '') {
					for(var i = 0; i < attrLen; i++) {
						var name = attributes[i]['name'],
							value = attributes[i]['value'],
							selValue = attrs[name];
						if(selValue.indexOf('"') == -1) selValue = '"' + selValue + '"';
						else selValue = '\'' + selValue + '\'';
						if(name in attrs) {
							wrapper.find('select[data-attribute="'+name+'"] option'+(attrs[name] == '' ? '' : '[value='+selValue+']')).css('display', 'block');
							if(attrs[name] != '' && attrs[name] != value) {
								match = false;
							}
						}
					}
					if(!found && match) {
						var divPrice = td.find('.wtbpVarPrice[data-variation_id="'+id+'"]');
						if(replacePrice) {
							tdPrice.html(divPrice.html());
						} else {
							divPrice.removeClass('wtbpHidden');
						}

						if(divPrice.attr('data-instock') == '1') {
							addButton.attr({'data-product_id': id, 'data-variation_id': id}).closest('.wtbpAddToCartWrapper').removeClass('wtbpDisabledLink');
							addButton.data('product_id', id);
							addButton.data('variation_id', id);
							addButton.attr('data-attribute_'+curAttr, curValue);
							if (isPopup) addButton.text(addButtonText);
							multy.prop('disabled', false).attr({'data-product_id': id, 'data-variation_id': id});
							var stepQty = divPrice.data('step');
							if (stepQty) {
								inputQty.attr('step', stepQty);
							}
							inputQty.attr( 'min', divPrice.data('min-qty')).attr('max', divPrice.data('max-qty'));
							if (inputQty.val() < divPrice.data('min-qty')) {
								inputQty.val(divPrice.data('min-qty')).change();
							}
							
						}

						if(thumbnail.length) {
							var varImg = td.find('.wtbpVarImage[data-variation_id="'+id+'"]');
							if(varImg.length) {
								thumbnail.find('a.wtbpMainImage').addClass('wtbpHidden');
								thumbnail.append(varImg.clone().removeClass('wtbpHidden'))
							}
						}
						
						if(sku.length) {
							sku.find('span').addClass('wtbpHidden');
							if (sku.find('[data-variation-id="' + id + '"]').length) {
								sku.find('[data-variation-id="' + id + '"]').removeClass('wtbpHidden');
							} else {
								sku.find('[data-default]').removeClass('wtbpHidden');
							}
						}

						found = true;
					}
				}
			}
			if(!found && replacePrice) {
				tdPrice.html(td.find('.wtbpVarPriceDefault').html());
			}
			if(multy.length > 0 && multy.prop('disabled')) {
				multy.prop('checked', false).change();
			}
			otherAttrs.each(function () {
				var current = $(this);
				if(current.find('option:selected').css('display') == 'none') {
					current.val('');
				}
				addButton.attr('data-attribute_'+current.attr('data-attribute'), current.val());
			});
		});

		var methodName = [
			'enableHeader', 'enableFooter', 'addDescription',
			'addCaption', 'addSignature', 'enableTableInfo',
			'viewCartHide', 'setColumnWidth',/*'setDefaultVariation',*/
			'enablePrintBtn', 'addCustomCss'];

		methodName.forEach(function(item) {
			__this[item](tableWrapper, settings);
		});
		
		table.on('lazy-load.dt', function() {
			if(typeof(__this.setLazyLoadDrawCallback) == 'function') {
				__this.setLazyLoadDrawCallback(tableWrapper, table, settings, objAttr);
				var tmpTableObj = table.DataTable();
				tmpTableObj.columns.adjust();
			}
		}).trigger('lazy-load.dt');
		
		table.on('responsive-resize.dt responsive-display.dt draw.dt', function() {
			__this.setDefaultVariation(tableWrapper, settings);
			tableWrapper.find('.quantity input.qty[name="quantity"]').each(function() {
				var qtyInput = $(this),
					minAttr = qtyInput.attr('min');
				if(typeof minAttr != 'undefined') {
					var minValue = parseInt(minAttr),
						curValue = parseInt(qtyInput.val());
					if(!isNaN(curValue) && !isNaN(minValue) && minValue > 1 && curValue < minValue) {
						qtyInput.val(minValue).trigger('change');
					} else if(curValue > 1) {
						qtyInput.trigger('change');
					}
				}
			});
			tableWrapper.find('tr.child').each(function() {
				var tr = $(this),
					prevTr = tr.prev();
				if (!tr.is('[class*="wtbp-attr-"]') && prevTr.is('[class*="wtbp-attr-"]')) {
					var classes = prevTr.attr('class').split(' ');
					for(var c = 0; c<classes.length; c++) {
						if (classes[c].indexOf('wtbp-attr-') !=-1) tr.addClass(classes[c]);
					}
				}
			});
			setTimeout(function() {
				table.DataTable().columns.adjust();
			}, 100);
		}).trigger('draw.dt');

		var methodName2 = [
			'enableSearching', 'enableHighlighForColumn', 'enableRowStriping', 'setTableWidth',
			'enablehighlightRowByHover', 'enableBorders', 'adjustBorders', 'setScrollPosition', 'runCustomJs'];

		methodName2.forEach(function(item) {
			__this[item](tableWrapper, settings, table);
		});

		if(typeof(this.setMethodsPro) == 'function') {
			this.setMethodsPro(tableWrapper, tableObj, settings);
		}

		var isFixedHeader = this.checkSettings(settings, 'header_fixed', false),
			scrollBody = table.closest('.dataTables_scrollBody'),
			fakeContainer = tableWrapper.find('.wtbpFakeScrollBody');
		if(scrollBody.length == 0) {
			scrollBody = table;
		}
		if(isFixedHeader) {
			var adminBar = $('#wpadminbar'),
				tableIdInfo = viewId+'_info',
				tableWidth, tableLeft, floatingTop;

			setVariablesForFixedHeader();

			$(document).off('scroll').on('scroll',function(){
				// add_specific_theme_fixed_overflow()
				if ( $('.theme-sydney header.float-header').length ) {
					var height = $('.theme-sydney header.float-header').height();
					height = parseInt(height) + 10;
					$('.wtbpContentTable.fixedHeader-floating').css('margin-top', height);
				}

				var floatingHeader = $('table.fixedHeader-floating');
				if(floatingHeader.length) {
					floatingHeader.removeClass('wtbpScrollTop');
					if(floatingHeader.attr('aria-describedby') == tableIdInfo) {
						floatingHeader.css({'width': tableWidth, 'max-width': tableWidth, 'left': tableLeft, 'display': 'block'}).scrollLeft(scrollBody.scrollLeft());

						floatingHeader.css('top', floatingTop);

						if(!floatingHeader.hasClass('wtbpMultyCheckAdded')) {

							if (settings['styles']) {
								var headerBorderWidth = vendor.checkSettings(settings['styles'], 'header_border_width', false);
								if (headerBorderWidth) {
									var tableId = table.attr('data-table-id'),
										responsiveMode = vendor.checkSettings(settings, 'responsive_mode');
									headerBorderWidth = parseInt(headerBorderWidth);

									// if we has borders settings with css before initialization we need additional border adjust for some cases
									if ( responsiveMode == 'horizontal' ) {
										var firstThFixedBody = table.find('thead th').first(),
											firstThFloat = floatingHeader.find('th').first(),
											firstThWidth = parseInt(firstThFixedBody.width()) + headerBorderWidth;

											firstThFloat.css('min-width', firstThWidth);
											firstThFloat.width(firstThWidth);
									} else {
										var firstThFixed = table.find('thead th').first(),
											firstThFloat = floatingHeader.find('th').first(),
											firstThWidth = parseInt(firstThFixed.width()) + headerBorderWidth * 2;

											firstThFloat.width(firstThWidth);
									}
								}
							}

							var wrapper = $('.wtbpTableWrapper[data-table-id="'+floatingHeader.attr('aria-describedby').replace('_info', '')+'"]');
							floatingHeader.on('change', '.wtbpAddMultyAll', function(e){
								e.preventDefault();
								__this.checkMultyAll($(this), wrapper);
							});
							floatingHeader.addClass('wtbpMultyCheckAdded');
						}
					}
				}
			});
		}
		if(isFixedHeader || fakeContainer.length) {
			scrollBody.on('scroll',function(){
				var left = $(this).scrollLeft();
				$('table.fixedHeader-floating').scrollLeft(left);
				fakeContainer.scrollLeft(left);
			});
		}
		
		if (indexColMultipleAdd != -1 && tableObj.column(indexColMultipleAdd).visible() && !isSSP) {
			if (tableWrapper.find('[data-position="last"]').length) {
				if (indexColMultipleAdd == 0) {
					var visibleColumns = tableObj.columns().visible().toArray(),
						toColumn = 0;
					for (var iterColumn = 0; iterColumn < visibleColumns.length; iterColumn++) {
						if (visibleColumns[iterColumn]) {
							toColumn = iterColumn;
						}
					}
					
					if (toColumn) {
						tableObj.colReorder.move( indexColMultipleAdd, toColumn );
					}
				}
			}
		}

		$(window).on('load resize', tableObj, function(event) {
			event.preventDefault();
			setVariablesForFixedHeader();
			event.handleObj.data.columns.adjust();
			var ee = event.handleObj;
			setTimeout(function() {
				ee.data.columns.adjust();
			}, 100);
			setTimeout(function() {
				try{
					var activeEl = $(document.activeElement);
					setVariablesForFixedHeader();
					if(fakeContainer.length) {
						fakeContainer.find('div').width(table.width());
						fakeContainer.css('display', scrollBody.get(0).scrollWidth > scrollBody.innerWidth() ? 'block' : 'none');
					}
					if(activeEl && activeEl.is('input')) {
						activeEl.focus();
					}
				}catch (err){
				}
			}, 350);

		});

		function setVariablesForFixedHeader() {
			if(!isFixedHeader) return;
			tableWidth = scrollBody.width()+'px';
			tableLeft = scrollBody.offset().left+'px';

			var isMobile = window.matchMedia("only screen and (max-width: 760px)").matches,
				mobile = isMobile ? '_mobile' : '',
				floatingTopMargin = vendor.checkSettings(settings, 'header_fixed_top_margin' + mobile, false);
			if (floatingTopMargin && floatingTopMargin != '0') {
				var floatingTopMarginUnit = vendor.checkSettings(settings, 'header_fixed_top_margin_unit' + mobile, 'px')
					if (floatingTopMarginUnit == 'pixels') {
						floatingTopMarginUnit = 'px';
					} else if(floatingTopMarginUnit == 'percents') {
						floatingTopMarginUnit = '%';
					}

				floatingTop = floatingTopMargin + floatingTopMarginUnit;
			} else {
				floatingTop = (adminBar.length && adminBar.css('position') == 'fixed' ? adminBar.height() : '0') + 'px';
			}
		}

		this.setTableInstance(tableObj);

		return tableObj;
	});

	vendor.getSetting = (function(settingsIn, table) {
		if(!settingsIn){
			var settings = table.attr('data-settings');
			try {
				settings = JSON.parse(settings);
			} catch(e){
				settings = {};
			}
		}else{
			settings = settingsIn.settings;
		}
		return settings;
	});

	vendor.setTableInstance = (function(instance) {
		wooTableInstances.push(instance);
	});

	vendor.getAllTableInstances = (function() {
		return wooTableInstances;
	});

	vendor.getTableInstanceById = (function(id) {
		var allTables = this.getAllTableInstances();

		for(var i = 0; i < allTables.length; i++) {
			if(allTables[i].table_id == id) {
				return allTables[i];
			}
		}
		return false;
	});
	vendor.removeTableInstanceById = (function(id) {
		var allTables = this.getAllTableInstances();

		for(var i = 0; i < allTables.length; i++) {
			if(allTables[i].table_id == id) {
				wooTableInstances.splice(i, 1);
			}
		}
		return;
	});


	vendor.checkSettings = (function(settings, settingName, settingDefault) {
		return ( settings[settingName] ) ? settings[settingName] : settingDefault;
	});

	vendor.setColumnsSettings = (function(objAttr, settings) {
		var mobileWidth = settings.mobile_width ? settings.mobile_width : 768,
			needHide = $(window).width() <= mobileWidth,
			disableSort = ['thumbnail', 'add_to_cart'],
			disableSearch = ['thumbnail', 'add_to_cart'],
			isMulty = typeof this.multyAddButtonEnable != 'undefined' ? this.multyAddButtonEnable : false,
			responsive = this.checkSettings(settings, 'responsive_mode', ''),
			firstColumn = (isMulty && (this.checkSettings(settings, 'multiple_add_cart_position', 'first') == 'first' || responsive == 'responsive' || responsive == 'hiding') ) ? 1 : 0,
			printEnabled = this.checkSettings(settings, 'print', false),
			sorting = this.checkSettings(settings, 'sorting', false),
			sortingDefault = this.checkSettings(settings, 'sorting_default', ''),
			sortingDesc = this.checkSettings(settings, 'sorting_desc', false),
			searching = this.checkSettings(settings, 'searching', false),
			disablePrint = ['add_to_cart'],
			responsiveHiding = settings.responsive_mode == 'hiding',
			showPrint = [],
			cntColumns = 0;
		if(this.checkSettings(settings, 'sorting_custom', false)) {
			sortingDefault = '';
		}
		if(this.checkSettings(settings, 'pagination', false) && this.checkSettings(settings, 'pagination_ssp', false)) {
			disableSort.push('description');
			disableSort.push('short_description');
			disableSort.push('product_link');
			disableSort.push('attribute');
		}
		try {
			var columns = JSON.parse(settings.order);
		} catch(e)  {
			var columns = [];
		}
		if(firstColumn) {
			var firstColumnInit = {"sortable": false, "className": "dt-center", "targets": 0, "responsivePriority": -1 }
			var responciveChildHide = this.checkSettings(settings, 'responcive_child_hide');
			if (responsive == 'responsive' && responciveChildHide == 'disable') {
				firstColumnInit['visible'] = false;
			}
			objAttr['columnDefs'].push(firstColumnInit);
		}
		objAttr['ordering'] = sorting ? true : false;

		for(var i = 0; i < columns.length; i++) {
			var slug = columns[i].slug;
			if (columns[i].original_name != 'colAttrHide') cntColumns++;

			objAttr['columnDefs'].push({ "name": slug, "targets": i + firstColumn});

			if(disablePrint.indexOf(slug) == -1) {
				showPrint.push(i + firstColumn);
			}

			//hide anyway
			if(columns[i].always_hide) {
				objAttr['columnDefs'].push({ "visible": false, "targets": i + firstColumn});
			} else {
				//hide column on mobile
				if(needHide) {
					if(columns[i].hide_on_mobile) {
						if (responsiveHiding) objAttr['columnDefs'].push({ "className": 'none', "targets": i + firstColumn});
						else objAttr['columnDefs'].push({ "visible": false, "targets": i + firstColumn});
					}
				} else {
					// hide when in desktop mode
					if(columns[i].show_only_on_mobile) {
						if (responsiveHiding) objAttr['columnDefs'].push({ "className": 'none', "targets": i + firstColumn});
						else objAttr['columnDefs'].push({ "visible": false, "targets": i + firstColumn});
					}
				}
			}

			if (sorting) {
				//disable sorting for some columns
				if(disableSort.indexOf(slug) != -1) {
					objAttr['columnDefs'].push({"sortable": false, "targets": i + firstColumn});
				} else if(slug == 'price') {
					objAttr['columnDefs'].push({"sortable": true, "type": 'num', "targets": i + firstColumn});
				}
				if(sortingDefault == slug) {
					objAttr['order'] = [[ i + firstColumn, sortingDesc ? 'desc' : 'asc' ]];
				}
			}

			if(searching) {
				if(disableSearch.indexOf(slug) != -1 || columns[i].disable_search == '1') {
					objAttr['columnDefs'].push({"searchable": false, "targets": i + firstColumn});
				}
			}
		}

		if(isMulty && !firstColumn) {
			objAttr['columnDefs'].push({ "sortable": false, "className": "dt-center", "targets": cntColumns });
		}
		if(printEnabled) {
			objAttr['buttons'][0]['exportOptions']['columns'] = showPrint;
		}
		return objAttr;
	});

	vendor.addPagination = (function(objAttr, settings, tableId) {
		var paginationEnable = this.checkSettings(settings, 'pagination', false);
		if(!paginationEnable){
			objAttr['paging'] = false;
		} else {
			if(this.checkSettings(settings, 'pagination_menu', false)) {
				var list = this.checkSettings(settings, 'pagination_menu_content', '10,50,All').split(','),
					arr = [[],[]];
				list.forEach(function(len) {
					var lenNum = (len == 'All' ? -1 : Number(len));
					if(!isNaN(lenNum) && (lenNum > 0 || lenNum == -1)) {
						arr[1].push(len);
						arr[0].push(lenNum);
					}
				});

				if(arr.length) {
					objAttr['lengthChange'] = true;
					objAttr['lengthMenu'] = arr;
				}
			} else {
				objAttr['lengthChange'] = false;
				objAttr['pageLength'] = Number(this.checkSettings(settings, 'page_length', 10));
				if(isNaN(objAttr['pageLength'])) {
					objAttr['pageLength'] = 10;
				}

				// if url has get param with activated table page set this page for a starting view
				var tableNumber = tableId.split('_'),
					getParam = window.location.search;

				tableNumber = tableNumber[0];
				getParam = getParam.substr(1);

				var getParamList = getParam.split('&');
				for (var i = 0; i < getParamList.length; i++) {
					var getParamPair = getParamList[i].split('=');

					if (getParamPair[0] == tableNumber) {
						objAttr['displayStart'] = parseInt(getParamPair[1]);
					}
				}
			}
		}

		return objAttr;
	});

	vendor.addLanguage = (function(objAttr, settings) {
		var emptyTable = this.checkSettings(settings, 'empty_table', 'There\'re no products in the table')
		,   tableInfoText = this.checkSettings(settings, 'table_info', 'Showing _START_ to _END_ of _TOTAL_ entries')
		,   emptyInfoText = this.checkSettings(settings, 'table_info_empty', 'Showing 0 to 0 of 0 entries')
		,   filteredInfoText = this.checkSettings(settings, 'filtered_info_text', '(filtered from _MAX_ total entries)')
		,   lengthText = this.checkSettings(settings, 'length_text', 'Show: _MENU_')
		,   searchLabel = this.checkSettings(settings, 'search_label', 'Search:')
		,   processingText = this.checkSettings(settings, 'processing_text', 'Processing...')
		,   zeroRecords = this.checkSettings(settings, 'zero_records', 'No matching records are found')
		,   lang_previous = this.checkSettings(settings, 'lang_previous', 'Previous')
		,   lang_next = this.checkSettings(settings, 'lang_next', 'Next');

		var languageObj = {};

		if(emptyTable){
			languageObj['emptyTable'] = emptyTable;
		}
		if(tableInfoText){
			languageObj['info'] = tableInfoText;
		}
		if(emptyInfoText){
			languageObj['infoEmpty'] = emptyInfoText;
		}
		if(filteredInfoText){
			languageObj['infoFiltered'] = filteredInfoText;
		}
		if(lengthText){
			languageObj['lengthMenu'] = lengthText;
		}
		if(searchLabel){
			languageObj['search'] = searchLabel;
		}
		if(processingText){
			languageObj['processing'] = processingText;
		}
		if(zeroRecords){
			languageObj['zeroRecords'] = zeroRecords;
		}
		if(lang_previous){
			languageObj['paginate'] = {};
			languageObj['paginate']['previous'] = lang_previous;
		}
		if(lang_next){
			if (languageObj['paginate']) {
				languageObj['paginate']['next'] = lang_next;
			} else {
				languageObj['paginate'] = {};
				languageObj['paginate']['next'] = lang_next;
			}
		}
		objAttr['language'] = languageObj;

		return objAttr;
	});

	vendor.enableFixedHeader = (function(objAttr, settings) {
		var headerEnable = this.checkSettings(settings, 'header_show', false)
		,   headerFixed = this.checkSettings(settings, 'header_fixed', false);

		if(headerEnable && headerFixed){
			objAttr['fixedHeader'] = true;
		}

		return objAttr;
	});

	vendor.initResponsiveMode = (function(objAttr, settings, tableWrapper) {
		var mode = this.checkSettings(settings, 'responsive_mode', 'horizontal'),
			mobileWidth = this.checkSettings(settings, 'mobile_width', 768),
			responsiveColumnHidingForce = this.checkSettings(settings, 'responsive_column_hiding_force', false),
			needHide = $(window).width() <= mobileWidth || tableWrapper.find('.wtbpContentTable').width() >= tableWrapper.width() || responsiveColumnHidingForce;
		switch(mode) {
			case 'responsive':
				objAttr['scrollX'] = false;
				objAttr['responsive'] = needHide ? {details: {display: $.fn.dataTable.Responsive.display.childRowImmediate, type: ''}} : false;
				break;
			case 'hiding':
				objAttr['scrollX'] = false;
				if (responsiveColumnHidingForce) {
					objAttr['responsive'] = true;
				} else {
					objAttr['responsive'] = needHide;
				}
				break;
			case 'horizontal':
				objAttr['scrollX'] = true;
				objAttr['responsive'] = false;
				break;
			case 'disable':
				objAttr['scrollX'] = false;
				objAttr['responsive'] = false;
				break;
		}
		return objAttr;
	});

	vendor.addColumnSearching = (function(table, settings) {
		var columnSearchEnable = this.checkSettings(settings, 'column_searching', false);

		if(columnSearchEnable) {
			var tPosition = this.checkSettings(settings, 'column_searching_position', 'tfoot'),
				isNodeTextarea = typeof(this.setSettingsPro) == 'function' && this.checkSettings(settings, 'column_searching_textarea', false),
				nodeTextareaRows = this.checkSettings(settings, 'column_searching_textarea_rows', 1),
				disableSearch = ['check_multy', 'thumbnail', 'add_to_cart'];
			if(this.checkSettings(settings, 'pagination', false) && this.checkSettings(settings, 'pagination_ssp', false)) {
				disableSearch.push('attribute');
				disableSearch.push('featured');
			}
			try {
				var columns = JSON.parse(settings.order);
			} catch(e)  {
				var columns = [];
			}
			for(var i = 0; i < columns.length; i++) {
				if(columns[i].disable_search == '1') {
					disableSearch.push(columns[i].slug);
				}
			}
			nodeTextareaRows = nodeTextareaRows < 1 ? 1 : nodeTextareaRows;
			nodeTextareaRows = nodeTextareaRows > 10 ? 10 : nodeTextareaRows;
			var nodeHtml = !isNodeTextarea ? '<input class="search-column" type="text" />' : '<textarea class="search-column" rows="'+nodeTextareaRows+'"></textarea>';

			if(!table.find('.wtbpColumnsSearchWrapper').length) {
				var headerRow = table.find('thead tr:first').find('th');
				if(headerRow.length) {
					var searchRow = '<tr class="wtbpColumnsSearchWrapper">';
					for (var i = 0; i < headerRow.length; i++) {
						var key = headerRow.eq(i).attr('data-key');
						searchRow += '<th data-key="'+key+'">'+(disableSearch.indexOf(key) == -1 ? nodeHtml : '')+'</th>';
					}
					searchRow += '</tr>';
					if(table.find(tPosition).length == 0) {
						table.append($('<' + tPosition + '>'));
					}
					switch (tPosition) {
						case 'thead':
							table.find(tPosition).prepend(searchRow);
							break;
						case 'tfoot':
						default:
							table.find(tPosition).append(searchRow);
							break;
					}
				}
			}
			table.on('responsive-resize.dt', function(event, api, columns) {
				$(this).find(tPosition + ' tr.wtbpColumnsSearchWrapper th').each(function(i, th) {
					$(th).css('display', (columns[i] ? 'table-cell' : 'none'));
				});
			});
		}
	});

	vendor.initFinalCallBack = (function(objAttr, finalCallback) {
		objAttr['initComplete'] = function(settings, json) {
			if(typeof finalCallback  == "function") {
				finalCallback();
			}
		};
		return objAttr;
	});

	vendor.enableHeader = (function(tableWrapper, settings) {
		var headerEnable = ( settings.header_show === '1' ) ? settings.header_show : false;
		if(!headerEnable){
			tableWrapper.find("thead").remove();
		}
	});

	vendor.enableFooter = (function(tableWrapper, settings) {
		var footerEnable = this.checkSettings(settings, 'footer_show', false);
		if(!footerEnable){
			tableWrapper.find('tfoot tr:not(.wtbpColumnsSearchWrapper)').css('display', 'none');
		}
	});

	vendor.addDescription = (function(tableWrapper, settings) {
		var description = ( settings.description_enable === '1' && undefined !== settings.description_text && settings.description_text.length > 0 ) ? settings.description_text : false;
		if(description){
			var descriptionHtml = '<div class="wtbpDescription">'+description+'</div>';
			tableWrapper.prepend(descriptionHtml);
		}
	});

	vendor.addCaption = (function(tableWrapper, settings) {
		var caption = ( settings.caption_enable === '1' && undefined !== settings.caption_text && settings.caption_text.length > 0 ) ? settings.caption_text : false;
		if(caption){
			var captionHtml = '<div class="wtbpTitle">'+caption+'</div>';
			tableWrapper.prepend(captionHtml);
		}
	});

	vendor.addSignature = (function(tableWrapper, settings) {
		var signature = ( settings.signature_enable === '1' && undefined !== settings.signature_text && settings.signature_text.length > 0 ) ? settings.signature_text : false;
		if(signature){
			var signatureHtml = '<div class="wtbpSignature">'+signature+'</div>';
			tableWrapper.append(signatureHtml);
		}
	});

	vendor.enableTableInfo = (function(tableWrapper, settings) {
		var tableInformationEnable = this.checkSettings(settings, 'table_information', false);
		if(!tableInformationEnable){
			tableWrapper.find('.dataTables_info').remove();
		}
	});

	vendor.viewCartHide = (function(tableWrapper, settings) {
		var viewCartHide = ( settings.view_cart_hide === '1' ) ? settings.view_cart_hide : false;
		if(viewCartHide){
			tableWrapper.find(".wtbpAddToCartButWrapp .added_to_cart").remove();
			var hideViewCart = '<style>.added_to_cart.wc-forward,.dataTables_wrapper .added_to_cart.wc-forward {display:none!important}</style>';
			$('body').append(hideViewCart);
		}
	});

	vendor.setDefaultVariation = (function(tableWrapper, settings) {
		var defaultVariation = this.checkSettings(settings, 'set_def_var', false) == '1';
		if(defaultVariation){
			tableWrapper.find('.wtbpVarAttributes').each(function() {
				var attrWrap = $(this),
					td = attrWrap.closest('td');
				if(td.find('.wtbpAddToCartWrapper').hasClass('wtbpDisabledLink')) {
					var	variations = JSON.parse(attrWrap.attr('data-variations')),
						varId = attrWrap.attr('data-default-id');
					if(varId && (varId in variations)) {
						var setAttr = '';
						for(var attr in variations[varId]) {
							var value = variations[varId][attr];
							if(value.length) {
								setAttr = attrWrap.find('select.wtbpVarAttribute[data-attribute="'+attr+'"]');
								if(setAttr.length) setAttr.val(value);
							}
						}
						if(setAttr.length) setAttr.trigger('change');
					}
				}
			});
		}
	});

	vendor.setColumnWidth = (function(tableWrapper, settings) {
		try {
			var columns = JSON.parse(settings.order);
		} catch(e)  {
			var columns = [];
		}
		var tableWidth = tableWrapper.width();
		for(var i = 0; i < columns.length; i++) {
			if(columns[i].width && columns[i].width_unit && columns[i].width != '') {
				var slug = columns[i].slug;
				tableWrapper.find('th[data-key="'+slug+'"], td.'+slug).css('min-width', (columns[i].width_unit == '%' ? (columns[i].width * tableWidth / 100) : columns[i].width) + 'px');
				tableWrapper.find('th[data-key="'+slug+'"], td.'+slug).css('max-width', (columns[i].width_unit == '%' ? (columns[i].width * tableWidth / 100) : columns[i].width) + 'px');
			}
		}
	});

	vendor.enableSearching = (function(tableWrapper, settings, table) {
		var searchingEnable = this.checkSettings(settings, 'searching', false);
		if(!searchingEnable){
			tableWrapper.find('.dataTables_filter').remove();
		}

		var inputs = table.parents('.dataTables_wrapper:first').find('.wtbpColumnsSearchWrapper .search-column');
		if(inputs.length) {
			var self = this;
			inputs.off('keyup.dtg change.dtg').on('keyup.dtg change.dtg',function () {
				var input = $(this),
					position = input.parents('th:first').index(),
					value = this.value,
					tableObj = self.getTableInstanceById(table.attr('id')),
					column = tableObj.column(position);
				if (column.search() !== value) {
					var values = value.split(/;|[\r\n]/g).filter(String).join('|');
					column.search(values, true, false).draw();
					if(!tableObj.isSSP) {
						setTimeout(function() {
							column.draw();
						}, 50);
					}
				}
			});
		}
	});

	vendor.addCustomCss = (function(tableWrapper, settings) {
		var styleText = tableWrapper.find('.wtbpCustomCssWrapper');
		if(styleText.length) {
			var wrapperId = tableWrapper.attr('id'),
				styleId = wrapperId+'-css';
			$('#'+styleId).remove();

			var styleTag = $('<style/>', {id: styleId});
			styleTag.html(styleText.text());
			$('head').append(styleTag);
			setTimeout(function() {
				var sheet = document.getElementById(styleId).sheet,
					rules = sheet.cssRules || sheet.rules,
					selectorId = '#'+wrapperId,
					selectors, newSelectors;

				for(var r = 0; r < rules.length; r++) {
					var rule = rules[r],
						value = rule.cssText ? rule.cssText : rule.style.cssText;
					if(
						typeof(value) == 'undefined' ||
						value.length == 0 ||
						value.indexOf('#') >= 0 ||
						value.indexOf('@import') >= 0 ||
						value.indexOf('@media') >= 0 ||
						value.indexOf('fixedHeader-floating') >= 0
					) continue;

					selectors = rule.selectorText.split(',');
					newSelectors = '';
					for(var c = 0; c < selectors.length; c++) {
						newSelectors += (newSelectors.length ? ',' : '')+selectorId+' '+selectors[c];
					}
					rule.selectorText = newSelectors;
				}
			}, 100);
		}
	});

	vendor.enablePrintBtn = (function(tableWrapper, settings) {
		var printButtonEnable = this.checkSettings(settings, 'print', false);
		if(!printButtonEnable){
			tableWrapper.find('.buttons-print').remove();
		}
	});

	vendor.setTableWidth = (function(tableWrapper, settings, table) {
		var fixedTableWidth = ( settings.width ) ? settings.width['fixed_width'] : '100'
		,   fixedTableMeasure = ( settings.width) ? settings.width['width_unit'] : 'percents';
		if(fixedTableMeasure === 'percents'){
			tableWrapper.css('width', fixedTableWidth + '%');
		}else if(fixedTableMeasure === 'pixels'){
			tableWrapper.css('width', fixedTableWidth + 'px');
		}
		table.css('width','100%');
	});

	vendor.enableHighlighForColumn = (function(tableWrapper, settings, table) {
		var highlightOrderColumn = this.checkSettings(settings, 'highlighting_order_column', false);
		if(highlightOrderColumn){
			table.addClass('order-column');
		}
	});

	vendor.enableRowStriping = (function(tableWrapper, settings, table) {
		var rowStriping = this.checkSettings(settings, 'row_striping', false);
		if(rowStriping){
			table.addClass('stripe');
		}
	});

	vendor.enablehighlightRowByHover = (function(tableWrapper, settings, table) {
		var highlightRowByHover = this.checkSettings(settings, 'highlighting_mousehover', false);
		if(highlightRowByHover){
			table.addClass('hover');
		}
	});

	// if we has borders settings with css before initialization we need additional border adjust for some cases
	vendor.adjustBorders = (function(tableWrapper, settings, table) {
		if (settings['styles']) {
			var headerBorderWidth = vendor.checkSettings(settings['styles'], 'header_border_width', false),
				responsiveMode = vendor.checkSettings(settings, 'responsive_mode'),
				tableId = table.attr('data-table-id');

			setTimeout(function() {
				if (headerBorderWidth) {
					if (responsiveMode == 'horizontal') {
						var tableHead = jQuery('.dataTables_scrollHead table[data-table-id=' + tableId + ']'),
							tableBody = jQuery('.dataTables_scrollBody table[data-table-id=' + tableId + ']'),
							firstThHead = tableHead.find('thead th').first(),
							firstThBody = tableBody.find('thead th').first(),
							firstThTrueWidth = parseInt(firstThBody.width()) + parseInt(headerBorderWidth);

						firstThHead.width(firstThTrueWidth)
					}
				}
			}, 300);
		}
	});

	vendor.enableBorders = (function(tableWrapper, settings, table) {
		var borders = this.checkSettings(settings, 'borders', false),
			styleId = 'wpf-fix-css' + tableWrapper.attr('id'),
			styleText = '',
			wrapperId = '#' + tableWrapper.attr('id');
		$('#'+styleId).remove();
		if(borders){
			if(borders === 'cell'){
				table.addClass('cell-border dataTable');
			}else if(borders === 'rows'){
				table.addClass('row-border dataTable');
				styleText = wrapperId + ' td,' + wrapperId +' th {border-left:none !important; border-right:none !important;} ' + wrapperId +' th {border-top:none !important;}';
			}else if(borders === 'none'){
				table.addClass('no-border dataTable');
				styleText = wrapperId + ' td {border:none !important;} ' + wrapperId +' th {border-top:none !important;border-left:none !important;border-right:none !important;}';
			}
			if(styleText.length) {
				var styleTag = $('<style/>', {id: styleId});
				styleTag.html(styleText);
				$('head').append(styleTag);
			}
		}
	});

	vendor.setScrollPosition = (function(tableWrapper, settings, table) {
		tableWrapper.find('table').removeClass('wtbpScrollTop');
		if(this.checkSettings(settings, 'responsive_mode', 'horizontal') != 'horizontal') return;

		var position = this.checkSettings(settings, 'horizontal_scroll', 'footer'),
			scrollBody = table.closest('.dataTables_scrollBody');
		if(scrollBody.length == 0) return;
		if(position == 'header') {
			scrollBody.addClass('wtbpScrollTop');
			table.addClass('wtbpScrollTop');
		} else if(position == 'two' && tableWrapper.find('.wtbpFakeScrollBody').length == 0) {
			scrollBody.before('<div class="wtbpFakeScrollBody"><div>&nbsp;</div></div>');
			var fakeContainer = tableWrapper.find('.wtbpFakeScrollBody');
			this.controlScrollPosition(tableWrapper, table);
			fakeContainer.scroll(function() {
				scrollBody.scrollLeft(fakeContainer.scrollLeft());
			});
		}
	});
	vendor.controlScrollPosition = (function(tableWrapper, table) {
		var fakeContainer = tableWrapper.find('.wtbpFakeScrollBody');
		if(fakeContainer.length) {
			setTimeout(function() {
				fakeContainer.find('div').width(table.width());
				var scrollBody = table.closest('.dataTables_scrollBody');
				if(scrollBody.length) {
					if(scrollBody.get(0).scrollWidth <= scrollBody.innerWidth()) {
						fakeContainer.css('display', 'none');
					} else {
						fakeContainer.css('display', 'block');
					}
				}
			}, 10);
		}
	});
	vendor.runCustomJs = (function(tableWrapper, settings, table) {
		var jsCodeStr = this.checkSettings(settings, 'custom_js', '');
		if(jsCodeStr.length > 0){
			try {
				eval(jsCodeStr);
			}catch(e) {
				console.log(e);
			}
		}
	});

}(window.woobewoo = window.woobewoo || {}, window.jQuery, window));
// callback function for a google map initialization
function wtbpInitMap() {
	setTimeout(function() {
		jQuery('.wtbp-map').each(function() {
			var wrapper = jQuery(this);
			var id = wrapper.attr('id');

			wrapper.css('height', wrapper.data('google-map-height'));
			wrapper.css('width', '100%');

			var uluru = {lat: wrapper.data('google-map-lat'), lng: wrapper.data('google-map-lng')};
			var map = new google.maps.Map(
				document.getElementById(id), {zoom: wrapper.data('google-map-zoom'), center: uluru});
		});
	}, 1000);
}