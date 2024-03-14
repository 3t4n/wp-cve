/*
 * Custom Script file
 */
(function($, window, document) {
	'use strict';
	var searchTimer;
	var sizeChartScripts = {
		init: function() {
			sizeChartScripts.sizeChartTableCellValidation();
			sizeChartScripts.loadSizeChartMenuScript();
			sizeChartScripts.loadChartCategorySelect2();
			sizeChartScripts.loadProductChartSelect2();
			sizeChartScripts.loadColorPicker();
			sizeChartScripts.loadPreviewSizeChart();
			sizeChartScripts.loadSizeChartProductMetaColumn();
			sizeChartScripts.loadSizeChartProductMetaAjax();
			sizeChartScripts.closeSizeChartModal();
			sizeChartScripts.requiredSizeChartTitle();
			sizeChartScripts.deleteAssignedProducts();
            sizeChartScripts.multiRowColumnModule();
            sizeChartScripts.sizeChartImportExportModule();
		},
		sizeChartTableCellValidation: function() {
		    $('#post').submit(function(e){
		        var isValid = true;

		        $('#size-chart-meta-fields .inputtable input').each(function(){
		            let tableFieldVal = $(this).val();
		            let regexPattern = /^[^"\u201C\u201D\\]*$/;
					if(regexPattern.test(tableFieldVal) === false ) {
						isValid = false;
						$(this).parent().css('border', '2px solid red');
					}
		        });

		        if(!isValid) {
		            e.preventDefault();
		            alert(sizeChartScriptObject.size_chart_field_validation);
		        }
		    });
		},
		loadSizeChartMenuScript: function() {
			var dotStoreMenu = $('#toplevel_page_dots_store');
			if ((
				'admin_page_size-chart-setting-page' === sizeChartScriptObject.size_chart_current_screen_id ||
				'dotstore-plugins_page_size-chart-information' === sizeChartScriptObject.size_chart_current_screen_id ||
				'dotstore-plugins_page_size-chart-import-export' === sizeChartScriptObject.size_chart_current_screen_id
			)) {
				dotStoreMenu.addClass('wp-has-current-submenu wp-menu-open menu-top menu-top-first').removeClass('wp-not-current-submenu');
				$('#toplevel_page_dots_store > a').addClass('wp-has-current-submenu current').removeClass('wp-not-current-submenu');
			}

			if (
                'edit-size-chart' === sizeChartScriptObject.size_chart_current_screen_id ||
				'size-chart' === sizeChartScriptObject.size_chart_current_screen_id
			) {
				dotStoreMenu.addClass('wp-has-current-submenu wp-menu-open menu-top menu-top-first').removeClass('wp-not-current-submenu');
				$('#toplevel_page_dots_store > a').addClass('wp-has-current-submenu current').removeClass('wp-not-current-submenu');
			}

			$('#toplevel_page_dots_store ul li').each(function() {
				if ('undefined' !== typeof sizeChartScriptObject.size_chart_plugin_menu_url) {
					if (sizeChartScriptObject.size_chart_plugin_name === $(this).text()) {
						$(this).find('a').attr('href', sizeChartScriptObject.size_chart_plugin_menu_url);
					}
					if (sizeChartScriptObject.size_chart_plugin_menu_url === $(this).find('a').attr('href')) {
						$(this).find('a').attr('href', sizeChartScriptObject.size_chart_plugin_menu_url);
					}
				}
			});

			if ('admin.php?page=' + sizeChartScriptObject.size_chart_get_started_page_slug === dotStoreMenu.find('a').attr('href')) {
				if ('undefined' !== typeof sizeChartScriptObject.size_chart_plugin_menu_url) {
					dotStoreMenu.find('a').attr('href', sizeChartScriptObject.size_chart_plugin_menu_url);
				}
			}
		},
		loadChartCategorySelect2: function() {

			/**
			 * Chart category select2.
			 * @type {{escapeMarkup: (function(*): *), maximumSelectionLength: number}}
			 */
			var sccSelectWoo = {
				escapeMarkup: function(m) {
					return m;
				},
				maximumSelectionLength: 100,
				placeholder: sizeChartScriptObject.select_category,
			};
			$('#chart-categories').selectWoo(sccSelectWoo).addClass('enhanced');

			/**
			 * Chart Tags select2.
			 * @type {{escapeMarkup: (function(*): *), maximumSelectionLength: number}}
			 */
			 var sctSelectWoo = {
				escapeMarkup: function(m) {
					return m;
				},
				maximumSelectionLength: 100,
				placeholder: sizeChartScriptObject.select_tag,
			};
			$('#chart-tags').selectWoo(sctSelectWoo).addClass('enhanced');

			/**
			 * Chart Attributes select2.
			 * @type {{escapeMarkup: (function(*): *), maximumSelectionLength: number}}
			 */
			 var scaSelectWoo = {
				escapeMarkup: function(m) {
					return m;
				},
				maximumSelectionLength: 100,
				placeholder: sizeChartScriptObject.select_attribute,
			};
			$('#chart-attributes').selectWoo(scaSelectWoo);

            /**
			 * Country select2.
			 * @type {{escapeMarkup: (function(*): *), maximumSelectionLength: number}}
			 */
			 var sccSelectWoo2 = {
				escapeMarkup: function(m) {
					return m;
				},
				maximumSelectionLength: 100,
				placeholder: sizeChartScriptObject.select_country,
			};
			$('#chart-country').selectWoo(sccSelectWoo2);
		},
		loadProductChartSelect2: function() {

			/**
			 * Ajax customer search boxes.
			 */
			$(':input#prod-chart').filter(':not(.enhanced)').each(function() {
				var terms = [];
				var select2Args = {
					allowClear: $(this).data('allow_clear') ? true : false,
					placeholder: $(this).data('placeholder'),
					minimumInputLength: $(this).data('minimum_input_length') ? $(this).data('minimum_input_length') : '1',
					escapeMarkup: function(m) {
						return m;
					},
					ajax: {
						url: sizeChartScriptObject.size_chart_admin_url,
						dataType: 'json',
						delay: 1000,
						data: function(params) {
							return {
								'searchQueryParameter': params.term,
								action: 'size_chart_search_chart',
								security: $(this).data('nonce'),
								exclude: $(this).data('exclude'),
							};
						},
						processResults: function(data) {
							terms = [];
							if (data) {
								$.each(data, function(id, text) {
									terms.push({
										id: id,
										text: text,
									});
								});
							}
							return {
								results: terms,
							};
						},
						cache: true,
					},
				};

				$(this).selectWoo(select2Args).addClass('enhanced');

			});
		},
		loadColorPicker: function() {

			/**
			 * Load color picker.
			 */
			$('#color-picker1,#color-picker2,#color-picker3,#color-picker4,#color-picker5,#color-picker6').wpColorPicker();
		},
		loadPreviewSizeChart: function() {

			/**
			 * Preview size chart.
			 */
			$('a.preview_chart').click(function() {
				var dataObj = {},
					chartID = $(this).attr('id'),
					modal = '',
					cssSelector = sizeChartScriptObject.size_chart_plugin_dash_name + '-inline-css';
				$('.size-chart-model').css('padding', '0');
				$('#wait').show();
				$('[data-remodal-id=modal]').html('');
                var farr = [];
                var chart_color = {};
                var chart_border = {};
                var popup_style = $('#table-style').val();
                var table_font_size = $('#chart-table-font-size').val();
                var size_chart_style = $('#size-chart-style').val();
                var popup_position = $('#popup-position').val();
                
				dataObj = {
					'action': 'size_chart_preview_post',
					chartID: chartID,
                    data: farr,
                    chart_color:chart_color,
                    chart_border:chart_border,
                    popup_style: popup_style,
                    table_font_size: table_font_size,
                    size_chart_style: size_chart_style,
                    popup_position: popup_position,
					'security': sizeChartScriptObject.size_chart_nonce,
				};

				$.ajax({
					type: 'GET',
					url: sizeChartScriptObject.size_chart_admin_url,
					data: dataObj,
					dataType: 'json',
					beforeSend: function() {
						$('#wait').show().css('position', 'fixed');
					}, complete: function() {
						$('#wait').hide().css('position', '');
					}, success: function(response) {
						if (1 === response.success) {
							$('.size-chart-model').css('padding', '35px');
							modal = document.getElementById('scfw-size-chart-preview-modal');
							modal.style.display = 'block';
							$('#scfw-size-chart-preview-modal').append(response.html);

							if ( 'center' !== popup_position ) {
								setTimeout( function() {
									$('#md-size-chart-modal').removeClass('md-size-chart-hide');
									$('#md-size-chart-modal').addClass('md-size-chart-show');
								}, 100 );
							} else {
								$('#md-size-chart-modal').removeClass('md-size-chart-hide');
								$('#md-size-chart-modal').addClass('md-size-chart-show');
							}

							$('#' + cssSelector).text(response.css);

							// Size chart tabbing script
							if ( $('.scfw_size-chart-details-tab').length !== 0 ) {
								setTimeout(function() {
						        	// Set tab wdith and position on tab change
									var actTabPosition = $('.scfw_size-chart-details-tab span.active-tab').position();
									var actTabWidth = $('.scfw_size-chart-details-tab span.active-tab').outerWidth();
								    $('.scfw_size-chart-details-tab .scfw_tab_underline').css({'left':+ actTabPosition.left, 'width':actTabWidth});
						        }, 400);
							}
							$('.scfw_size-chart-details-tab span').click(function() {
								var tab_id = $(this).attr('data-tab');
								$('.scfw_size-chart-details-tab span').removeClass('active-tab');
								$('.scfw_tab_style .chart-container > div').removeClass('active-tab');

								$(this).addClass('active-tab');
								$('.scfw_tab_style #' + tab_id).addClass('active-tab');

								// Set tab wdith and position on tab change
								var tabPosition = $(this).position();
								var tabWidth = $(this).outerWidth();
								$('.scfw_size-chart-details-tab span').css('border-color','transparent');
							    $('.scfw_size-chart-details-tab .scfw_tab_underline').css({'visibility': 'visible', 'left':+ tabPosition.left, 'width':tabWidth});
							});

							
						} else {
							alert('size-chart-for-woocommerce-premium==>' + response.msg);
						}
					},
				});
			});
		},
		loadSizeChartProductMetaColumn: function() {

			/**
			 * Size chart metabox setting columns.
			 */
			$('#size-chart-menu-settings-column').bind('click', function(e) {
				var panelId, wrapper,
					target = $(e.target);
				if (target.hasClass('nav-tab-link')) {
					panelId = target.data('type');
					wrapper = target.parents('.size-chart-accordion-section-content').first();

					// upon changing tabs, we want to uncheck all checkboxes
					$('input', wrapper).removeAttr('checked');
					$('.tabs-panel-active', wrapper).removeClass('tabs-panel-active').addClass('tabs-panel-inactive');
					$('#' + panelId, wrapper).removeClass('tabs-panel-inactive').addClass('tabs-panel-active');
					$('.tabs', wrapper).removeClass('tabs');
					target.parent().addClass('tabs');

					// select the search bar.
					$('.quick-search', wrapper).focus();

					// Hide controls in the search tab if no items found.
					if ( !wrapper.find('.tabs-panel-active .menu-item-title').length) {
						wrapper.addClass('has-no-menu-item');
					} else {
						wrapper.removeClass('has-no-menu-item');
					}
					e.preventDefault();
				}
			});
		},
		loadSizeChartProductMetaAjax: function() {

			/**
			 * Size chart meta product and product pagination.
			 */
			$('div#tabs-panel-posttype-size-chart-all').on('click', 'ul.pagination li a.page-numbers', function(e) {
				var pageNumber, postID, postPerPage, data, subLiTag, subSpanTag, subATag, paginationSubLiTag, paginationSubTag, paginationClass;
				e.preventDefault();
				pageNumber = $(this).data('page-number');
				postID = $(this).data('post-id');
				postPerPage = $(this).data('post-per-page');
				data = {
					'action': 'size_chart_product_assign',
					'pageNumber': pageNumber,
					'postID': postID,
					'postPerPage': postPerPage,
					'security': $(this).parent().parent().data('nonce'),
				};

				$.ajax({
					type: 'GET',
					url: sizeChartScriptObject.size_chart_admin_url,
					data: data,
					dataType: 'json',
					beforeSend: function() {
						$('div#tabs-panel-posttype-size-chart-all .spinner').addClass('is-active');
					}, complete: function() {
						$('div#tabs-panel-posttype-size-chart-all .spinner').removeClass('is-active');
					}, success: function(response) {

						if (true === response.success) {
							$('ul#size-chart-checklist-all').empty();
							$.each(response.found_products, function(loopKey, loopValue) {
								subLiTag = $('<li/>');
								subATag = $('<a />', {'href': loopValue.href.replace('&#038;', '&'), text: loopValue.title});
								subATag.appendTo(subLiTag);
								subSpanTag = $('<span />', {'class': 'remove-product-icon', text: '×', 'data-id':loopKey});
								subSpanTag.appendTo(subLiTag);
								subLiTag.appendTo('ul#size-chart-checklist-all');
							});

							$('nav.pagination-box ul.pagination').empty();
							$.each(response.load_pagination, function(paginationKey, paginationValue) {
								paginationSubLiTag = $('<li/>');
								if ('number' === paginationValue.pagination_mode) {
									if ('span' === paginationValue.pagination_tag) {
										paginationSubTag = $('<span />', {
											class: 'page-numbers ' + paginationValue.pagination_class,
											text: paginationValue.page_text,
										});

									} else {
										paginationClass = 'page-numbers ';
										if ('' !== paginationValue.pagination_class) {
											paginationClass += paginationValue.pagination_class;
										}

										paginationSubTag = $('<a />', {
											href: 'javascript:void(0);',
											class: paginationClass,
											text: paginationValue.page_text,
											'data-post-id': paginationValue.post_id,
											'data-post-per-page': paginationValue.post_per_page,
											'data-page-number': paginationValue.page_number,
										});
									}
								} else if ('dots' === paginationValue.pagination_mode) {
									paginationSubTag = $('<span />', {
										class: 'page-numbers ' + paginationValue.pagination_class,
										text: paginationValue.page_text,
									});
								}
								paginationSubTag.appendTo(paginationSubLiTag);
								paginationSubLiTag.appendTo('nav.pagination-box ul.pagination');
							});

						}
					},
				});
			});

			/**
			 * Size chart meta search product.
			 */
			$('div#tabs-panel-posttype-size-chart-search').on('input', '.quick-search', function() {
				var $this = $(this);
				$this.attr('autocomplete', 'off');
				if (searchTimer) {
					clearTimeout(searchTimer);
				}
				searchTimer = setTimeout(function() {
					var panel, params,
						minSearchLength = 2,
						searchQueryParameter = $this.val(),
						subLiTag, subLabel, inputCheckbox;

					if (searchQueryParameter.length < minSearchLength) {
						return;
					}

					panel = $this.parents('.tabs-panel');
					params = {
						'action': 'size_chart_quick_search_products',
						'security': $this.data('nonce'),
						'postType': $this.data('post_type'),
						'searchQueryParameter': searchQueryParameter,
						'type': $this.attr('name'),
					};

					$.ajax({
						type: 'GET',
						url: sizeChartScriptObject.size_chart_admin_url,
						data: params,
						dataType: 'json',
						beforeSend: function() {
							$('.quick-search-wrap .spinner', panel).addClass('is-active');
						}, complete: function() {
							$('.quick-search-wrap .spinner', panel).removeClass('is-active');
						}, success: function(response) {
							$('ul#size-chart-search-checklist').empty();
							if (true === response.success) {
								$.each(response.found_products, function(loopKey, loopValue) {
									subLiTag = $('<li/>').appendTo('ul#size-chart-search-checklist');
									subLabel = $('<label />', {'for': 'size-chart-product-' + loopKey, text: loopValue.title});
									inputCheckbox = $('<input />', {type: 'checkbox', id: 'size-chart-product-' + loopKey, value: loopValue.id, class: 'product-item-checkbox', name: 'product-item[' + loopValue.id + ']'});
									inputCheckbox.prependTo(subLabel);
									subLabel.appendTo(subLiTag);
								});
							} else {
								subLiTag = $('<li/>').appendTo('ul#size-chart-search-checklist');
								subLabel = $('<p />', {text: response.msg});
								subLabel.appendTo(subLiTag);
							}
						},
					});

				}, 500);
			});

		},
		closeSizeChartModal: function() {

			/**
			 * Close popup.
			 */
            $(document).on('click', 'div#md-size-chart-modal .remodal-close', function() {
            	$('#md-size-chart-modal').removeClass('md-size-chart-show');
            	$('#md-size-chart-modal').addClass('md-size-chart-hide');
            	setTimeout( function() {
            		$('.md-size-chart-modal').remove();
            	}, 200);
			});

			/**
			 * Close popup.
			 */
            $(document).on('click', 'div.md-size-chart-overlay', function() {
            	$('#md-size-chart-modal').removeClass('md-size-chart-show');
            	$('#md-size-chart-modal').addClass('md-size-chart-hide');
				setTimeout( function() {
            		$('.md-size-chart-modal').remove();
            	}, 200);
			});
		},
		requiredSizeChartTitle: function() {

			/**
			 * Required the size chart.
			 */
			$('body').on('submit.edit-post', '#post', function() {
				var getPostType, sizeChartTitleSelector, sizeChartPostTitleRequiredMsg;
				getPostType = $('input#post_type').val();
				if (sizeChartScriptObject.size_chart_post_type_name === getPostType) {
					sizeChartTitleSelector = $('#title');
					if (0 === sizeChartTitleSelector.val().replace(/ /g, '').length) {
						if ( !$('#size-chart-title-required-msg').length) {
							sizeChartPostTitleRequiredMsg = sizeChartScriptObject.size_chart_post_title_required;

							$('<div/>', {
								'id': 'size-chart-title-required-msg',
							}).appendTo('div#titlewrap');

							$('<em/>', {
								text: sizeChartPostTitleRequiredMsg,
							}).appendTo('#size-chart-title-required-msg');

							$('input#title').css({
								'border': '1px solid #c00',
								'box-shadow': '0 0 2px rgb(204, 0, 0, 0.8)',
							});

						}
						$('#major-publishing-actions .spinner').hide();
						$('#major-publishing-actions').find(':button, :submit, a.submitdelete, #post-preview').removeClass('disabled');
						sizeChartTitleSelector.focus();
						return false;
					}
				}
			});
			$('input#title').on('change', function() {
				$('#size-chart-title-required-msg').remove();
				$('input#title').css({
					'border': '1px solid #ddd',
				});
			});

		},
		deleteAssignedProducts: function() {
			/**
			 * Ajax for assigning the product from chart
			 */
			 $('span.remove-product-icon').click(function(e) {
				var prompt_ask = confirm(sizeChartScriptObject.remove_product_confirm);
				if ( ! prompt_ask ) {
					return false;
				}
				var postID  = $(this).data('id');
				var chartID = $(this).data('chart');
				var data = {
					'action': 'size_chart_unassign_product',
					'postID': postID,
					'chartID': chartID,
					'security': sizeChartScriptObject.size_chart_nonce,
				};
                
                $.ajax({
					type: 'POST',
					url: sizeChartScriptObject.size_chart_admin_url,
					data: data,
					success: function(response) {
						var result = $.parseJSON(response);
						if ( 1 === result.success ) {
							$(e.target).parent().remove();
							if ( $('#size-chart-checklist-all li').length === 0 ) {
								$('#size-chart-checklist-all').text(sizeChartScriptObject.size_chart_no_product_assigned);
							}
						} else {
							alert(result.msg);
						}
					},
				});
			});
		},
        multiRowColumnModule: function(){

            var stored_style =$('#table-style').val();
            if( $('#table-style').length > 0 ) {
                sync_setting(stored_style);
                border_setting(stored_style);
            }
            if( 'advance-style' !== stored_style ){
                $('.multiple_action_wrap tbody tr').not('.row_wrap').not('.column_wrap').hide();
            } else {
                $('.multiple_action_wrap tbody tr').not('.row_wrap').not('.column_wrap').show();
            }
            $('#table-style').change(function(){
                var style_val = $(this).val();
                if( 'advance-style' !== style_val ){
                    $('.multiple_action_wrap tbody tr').not('.row_wrap').not('.column_wrap').hide();
                } else {
                    $('.multiple_action_wrap tbody tr').not('.row_wrap').not('.column_wrap').show();
                }
                sync_setting(style_val);
                border_setting(style_val);
            });

            //Row action
            $('#scfw_add_multi_row_action').click(function(){
                var count = $('#scfw_add_multi_row').val();
                for ( var i = 0; i < count; i++ ) {
                    $('.addrow').last().trigger('click');
                }
                sync_setting();
                border_setting();
            });
            $('#scfw_delete_multi_row_action').click(function(){
                var count = $('#scfw_add_multi_row').val();
                for ( var i = 0; i < count; i++ ) {
                    $('.delrow').last().trigger('click');
                }
            });

            //Column action
            $('#scfw_add_multi_column_action').click(function(){
                var count = $('#scfw_delete_multi_column').val();
                for (var index = 0; index < count; index++) {
                    $('.addcol').last().trigger('click');
                }
                sync_setting();
                border_setting();
            });
            $('table').on('click', '.addcol, .addrow, .delrow, .delcol', function(){
                setTimeout(function () {
                    sync_setting();
                    border_setting();
                }, 20);
            });
            $('#scfw_delete_multi_column_action').click(function(){
                var count = $('#scfw_delete_multi_column').val();
                for ( var j = 0; j < count; j++ ) {
                    $('.delcol').last().trigger('click');
                }
            });

            $('#scfw_table_hover_bg_color').wpColorPicker();
            $('#scfw_header_bg_color').wpColorPicker({
                change: function (event, ui) {
                    $('table.inputtable tbody tr:first-child td:not(td:last-child)').css( 'background-color', ui.color.toString() );
                }
            });
            $('#scfw_even_row_bg_color').wpColorPicker({
                change: function (event, ui) {
                    $('table.inputtable tr:odd:not(:first-child) td:not(td:last-child)').css( 'background-color', ui.color.toString() );
                }
            });
            $('#scfw_odd_row_bg_color').wpColorPicker({
                change: function (event, ui) {
                    $('table.inputtable tr:even:not(:first-child) td:not(td:last-child)').css( 'background-color', ui.color.toString() );
                }
            });

            $('#scfw_text_color').wpColorPicker({
                change: function (event, ui) {
                    $('table.inputtable tbody tr:first-child td:not(td:last-child) input').css( 'color', ui.color.toString() );
                }
            });
            $('#scfw_even_text_color').wpColorPicker({
                change: function (event, ui) {
                    $('table.inputtable tr:odd:not(:first-child) td:not(td:last-child) input').css( 'color', ui.color.toString() );
                }
            });
            $('#scfw_odd_text_color').wpColorPicker({
                change: function (event, ui) {
                    $('table.inputtable tr:even:not(:first-child) td:not(td:last-child) input').css( 'color', ui.color.toString() );
                }
            });
            //Border Color
            $('#scfw_border_color').wpColorPicker({
                change: function () {
                    border_setting('advance-style');
                }
            });
            $('#scfw_border_hb_style, #scfw_border_hw, #scfw_border_vb_style, #scfw_border_vw').on( 'input', function(){
                border_setting('advance-style');
            });
            
            function border_setting( ){
                var table_style =$('#table-style').val(); 
                var scfw_border_color, scfw_border_hb_style,scfw_border_hw,scfw_border_vb_style, scfw_border_vw;

                if( 'advance-style' === table_style ){
                    scfw_border_color = $('#scfw_border_color').val();
                    scfw_border_hb_style = $('#scfw_border_hb_style').val();
                    scfw_border_hw = $('#scfw_border_hw').val();
                    scfw_border_vb_style = $('#scfw_border_vb_style').val();
                    scfw_border_vw = $('#scfw_border_vw').val();
                } else {
                    table_style = $('#table-style').val();
                    var table_style_data = sizeChartScriptObject.size_chart_chart_table_style[table_style];
                    scfw_border_color = table_style_data.border_color;
                    scfw_border_hb_style = table_style_data.border_hb_style;
                    scfw_border_hw = table_style_data.border_hw;
                    scfw_border_vb_style = table_style_data.border_vb_style;
                    scfw_border_vw = table_style_data.border_vw;
                }

                $('table.inputtable tbody tr td:not(td:last-child)').css( 'border-top', scfw_border_hw + 'px ' + scfw_border_hb_style + ' ' +scfw_border_color );
                $('table.inputtable tbody tr td:not(td:last-child)').css( 'border-bottom', scfw_border_hw + 'px ' + scfw_border_hb_style + ' ' +scfw_border_color );

                $('table.inputtable tbody tr td:not(td:last-child)').css( 'border-left', scfw_border_vw + 'px ' + scfw_border_vb_style + ' ' +scfw_border_color );
                $('table.inputtable tbody tr td:not(td:last-child)').css( 'border-right', scfw_border_vw + 'px ' + scfw_border_vb_style + ' ' +scfw_border_color );
            }

            function sync_setting() {
                var table_style =$('#table-style').val();
                var scfw_header_bg_color, scfw_even_row_bg_color, scfw_odd_row_bg_color, scfw_text_color, scfw_even_text_color, scfw_odd_text_color;

                if( 'advance-style' === table_style ){
                    scfw_header_bg_color = $('#scfw_header_bg_color').val();
                    scfw_even_row_bg_color = $('#scfw_even_row_bg_color').val();
                    scfw_odd_row_bg_color = $('#scfw_odd_row_bg_color').val();
                    scfw_text_color = $('#scfw_text_color').val();
                    scfw_even_text_color = $('#scfw_even_text_color').val();
                    scfw_odd_text_color = $('#scfw_odd_text_color').val();
                } else {
                    // table_style = $('#table-style').val();
                    var table_style_data = sizeChartScriptObject.size_chart_chart_table_style[table_style];
                    scfw_header_bg_color = table_style_data.header_bg_color;
                    scfw_even_row_bg_color = table_style_data.even_row_bg_color;
                    scfw_odd_row_bg_color = table_style_data.odd_row_bg_color;
                    scfw_text_color = table_style_data.text_color;
                    scfw_even_text_color = table_style_data.even_text_color;
                    scfw_odd_text_color = table_style_data.odd_text_color;
                }
                
                $('table.inputtable tbody tr:first-child td:not(td:last-child)').css('background-color', scfw_header_bg_color);

                //For our table even rows
                $('table.inputtable tr:odd:not(:first-child) td:not(td:last-child)').css('background-color', scfw_even_row_bg_color);

                //For our table odd rows
                $('table.inputtable tr:even:not(:first-child) td:not(td:last-child)').css('background-color', scfw_odd_row_bg_color);
 
                $('table.inputtable tbody tr:first-child td:not(td:last-child) input').css('color', scfw_text_color);

                //For our table even rows 
                $('table.inputtable tr:odd:not(:first-child) td:not(td:last-child) input').css('color', scfw_even_text_color);

                //For our table odd rows 
                $('table.inputtable tr:even:not(:first-child) td:not(td:last-child) input').css('color', scfw_odd_text_color);
            }
        },
        sizeChartImportExportModule: function(){
            /**
			 * Ajax for export size chart table data
			 */
			 $('.export_chart').click(function(e) {
                e.preventDefault();
				var prompt_ask = confirm(sizeChartScriptObject.export_chart_confirm);
				if ( ! prompt_ask ) {
					return false;
				}
                $('.inputtable').parent().parent().block({
                    message: null,
                    overlayCSS: {
                        background: 'rgb(255, 255, 255)',
                        opacity: 0.6,
                    },
                });
				var chartID  = $(this).attr('id');
				var data = {
					'action': 'size_chart_export_data',
					'chartID': chartID,
					'security': sizeChartScriptObject.size_chart_nonce,
				};
                
                $.ajax({
					type: 'POST',
					url: sizeChartScriptObject.size_chart_admin_url,
					data: data,
					success: function(response) {
                        if( response.data.download_path ){
                            var link = document.createElement('a');
                            document.body.appendChild(link);
                            link.href = response.data.download_path;
                            link.download = '';
                            link.click();
                        }
                        $('.inputtable').parent().parent().unblock();
					},
				});
			});

            /**
			 * Ajax for import size chart table data
			 */
            $('.import_chart').click(function(e) {
                e.preventDefault();
                $('.scfw_import_file').trigger('click');
            });
            $('.scfw_import_file').change(function(e) {
                e.preventDefault();
                //Get reference of FileUpload.
                var fileUpload = $(this);
                var p = $('<p>');
                var msg = '';
                //Check whether the file is valid Image.
                var regex = new RegExp('([a-zA-Z0-9\s_\\.\-:])+(.json)$');
                if (regex.test(fileUpload.val().toLowerCase())) {
                    $('.thedotstore-main-table table').block({
                        message: null,
                        overlayCSS: {
                            background: 'rgb(255, 255, 255)',
                            opacity: 0.6,
                        },
                    });
                    var chartID  = $('.import_chart').attr('id');
                    var fd = new FormData();
                    fd.append('import_file', fileUpload[0].files[0]);  
                    fd.append('action', 'size_chart_import_data');
                    fd.append('chartID', chartID);
                    fd.append('security', sizeChartScriptObject.size_chart_nonce);
                    
                    $.ajax({
                        type: 'POST',
                        url: sizeChartScriptObject.size_chart_admin_url,
                        data: fd,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if(response.success){
                                msg = response.data.message;
                                p.css('color', 'green');
                                setTimeout(function() {
                                    window.location.reload();
                                }, 4000);
                            } else {
                                msg = response.data.message;
                                p.css('color', 'red');
                            }
                            
                            $('.inputtable').parent().parent().unblock();
                            p.text(msg);
                            fileUpload.parent().append(p);
                        },
                    });
                } else {
                    msg = 'Please upload JSON file';
                    p.css('color', 'red');
                }
                p.text(msg);
                fileUpload.parent().append(p);
            });

            
        }
	};
	
	// Add currunt menu class in main manu
    $(window).load(function () {
        $('a[href="edit.php?post_type=size-chart"]').parents().addClass('current wp-has-current-submenu');
        $('a[href="edit.php?post_type=size-chart"]').addClass('current');
    });
    
	$(document).ready(function(){
		$('#scsf_user_role').select2({
			placeholder: sizeChartScriptObject.select_user_role
	    });
		function scfw_size_chart_position_options() {
	        $('select#position').on('change', function () {
	        	var optionSelected = $(this).val();
	        	if ( 'tab' === optionSelected ) {
		            $('.chart-tab-field').css('display', 'flex');
		            $('.chart-popup-field').hide();
		        } else {
		            $('.chart-popup-field').css('display', 'flex');
		            $('.chart-tab-field').hide();
		        }
			});
	    }
	    scfw_size_chart_position_options();

        //Default icon JS
        $('input[name="default-icons"]').change(function(){
            var value = $(this).val();
            if( value !== 'dashicons-none' ){
                $('#chart-popup-icon').val(value); 
            } else { 
                $('#chart-popup-icon').val('');
            }
        });
        $('#chart-popup-icon').on('input', function(){
            var value = $(this).val();
            $('input[name="default-icons"]').prop('checked', false);
            if( '' !== value ){
                $('input[name="default-icons"]').each(function( e, val ){
                    if( $(val).val() === value ){
                        $('input[name="default-icons"][value="'+value+'"]').prop('checked', true);
                    }
                });
            } else {
                $('input[name="default-icons"][value="dashicons-none"]').prop('checked', true);
            }
        });

        

        // Tablecell validation js
	    $(document).on( 'keyup', '#size-chart-meta-fields .inputtable input', function () {
            let tableFieldVal = $(this).val();
			let regexPattern = /^[^"\u201C\u201D\\]*$/;
			if(regexPattern.test(tableFieldVal) === false ) {
				$(this).parent().addClass('invalid-character');
			} else {
				$(this).parent().removeClass('invalid-character');
			}
	    });

	    // Tablecell js for multiple table
	    function disableTableInputs($input, $tr, $onload) {
	    	let fieldValue = $input.val();
	    	let asteriskPattern = /^\*{3}.*\*{3}$/;

		  	if( $onload ) {
		  		if( ! $tr.hasClass('has-disabled') ) {
				  	if (asteriskPattern.test(fieldValue) === true) {
				  		$tr.addClass('has-disabled');
			    		$tr.find('input[type="text"]').not($input).prop('disabled', true);
			    		$tr.find('td').not($input.closest('td')).css('opacity', '0.5');
				  	} else {
			    		$tr.find('input[type="text"]').prop('disabled', false);
			    		$tr.find('td').css('opacity', '1');
				  	}
				}
		  	} else {
		  		if (asteriskPattern.test(fieldValue) === true) {
		    		$tr.find('input[type="text"]').not($input).prop('disabled', true);
		    		$tr.find('td').not($input.closest('td')).css('opacity', '0.5');
			  	} else {
		    		$tr.find('input[type="text"]').prop('disabled', false);
		    		$tr.find('td').css('opacity', '1');
			  	}
		  	}
	    }

	    $('#size-chart-meta-fields .inputtable input').each(function() {
	    	disableTableInputs($(this), $(this).closest('tr'), true);
	    });	    

	    $(document).on('keyup', '#size-chart-meta-fields .inputtable input', function () {
		  	disableTableInputs($(this), $(this).closest('tr'), false);
		});

		/** Dynamic Promotional Bar START */
	    //set cookies
		function setCookie(name, value, minutes) {
			var expires = '';
			if (minutes) {
				var date = new Date();
				date.setTime(date.getTime() + (minutes * 60 * 1000));
				expires = '; expires=' + date.toUTCString();
			}
			document.cookie = name + '=' + (value || '') + expires + '; path=/';
		}
		
        $(document).on('click', '.dpbpop-close', function () {
            var popupName 		= $(this).attr('data-popup-name');
            setCookie( 'banner_' + popupName, 'yes', 60 * 24 * 7);
            $('.' + popupName).hide();
        });

		$(document).on('click', '.dpb-popup', function () {
			var promotional_id 	= $(this).find('.dpbpop-close').attr('data-bar-id');

			//Create a new Student object using the values from the textfields
			var apiData = {
				'bar_id' : promotional_id
			};

			$.ajax({
				type: 'POST',
				url: sizeChartScriptObject.dpb_api_url + 'wp-content/plugins/dots-dynamic-promotional-banner/bar-response.php',
				data: JSON.stringify(apiData),// now data come in this function
		        dataType: 'json',
		        cors: true,
		        contentType:'application/json',
				success: function (data) {
					console.log(data);
				}
			 });
        });
        /** Dynamic Promotional Bar END */

        /** Plugin Setup Wizard Script START */
		// Hide & show wizard steps based on the url params 
	  	var urlParams = new URLSearchParams(window.location.search);
	  	if (urlParams.has('require_license')) {
	    	$('.ds-plugin-setup-wizard-main .tab-panel').hide();
	    	$( '.ds-plugin-setup-wizard-main #step5' ).show();
	  	} else {
	  		$( '.ds-plugin-setup-wizard-main #step1' ).show();
	  	}
	  	
        // Plugin setup wizard steps script
        $(document).on('click', '.ds-plugin-setup-wizard-main .tab-panel .btn-primary:not(.ds-wizard-complete)', function () {
	        var curruntStep = $(this).closest('.tab-panel').attr('id');
	        var nextStep = 'step' + ( parseInt( curruntStep.slice(4,5) ) + 1 ); // Masteringjs.io

	        if( 'step5' !== curruntStep ) {
	         	$( '#' + curruntStep ).hide();
	            $( '#' + nextStep ).show();   
	        }
	    });

	    // Get allow for marketing or not
	    if ( $( '.ds-plugin-setup-wizard-main .ds_count_me_in' ).is( ':checked' ) ) {
	    	$('#fs_marketing_optin input[name="allow-marketing"][value="true"]').prop('checked', true);
	    } else {
	    	$('#fs_marketing_optin input[name="allow-marketing"][value="false"]').prop('checked', true);
	    }

		// Get allow for marketing or not on change	    
	    $(document).on( 'change', '.ds-plugin-setup-wizard-main .ds_count_me_in', function() {
			if ( this.checked ) {
				$('#fs_marketing_optin input[name="allow-marketing"][value="true"]').prop('checked', true);
			} else {
		    	$('#fs_marketing_optin input[name="allow-marketing"][value="false"]').prop('checked', true);
		    }
		});

	    // Complete setup wizard
	    $(document).on( 'click', '.ds-plugin-setup-wizard-main .tab-panel .ds-wizard-complete', function() {
			if ( $( '.ds-plugin-setup-wizard-main .ds_count_me_in' ).is( ':checked' ) ) {
				$( '.fs-actions button'  ).trigger('click');
			} else {
		    	$('.fs-actions #skip_activation')[0].click();
		    }
		});

	    // Send setup wizard data on Ajax callback
		$(document).on( 'click', '.ds-plugin-setup-wizard-main .fs-actions button', function() {
			var wizardData = {
                'action': 'scfw_plugin_setup_wizard_submit',
                'survey_list': $('.ds-plugin-setup-wizard-main .ds-wizard-where-hear-select').val(),
                'nonce': sizeChartScriptObject.setup_wizard_ajax_nonce
            };

            $.ajax({
                url: sizeChartScriptObject.size_chart_admin_url,
                data: wizardData,
                success: function ( success ) {
                    console.log(success);
                }
            });
		});
		/** Plugin Setup Wizard Script End */

		/** Upgrade Dashboard Script START */
	    // Dashboard features popup script
	    $(document).on('click', '.dotstore-upgrade-dashboard .unlock-premium-features .feature-box', function (event) {
	    	let $trigger = $('.feature-explanation-popup, .feature-explanation-popup *');
			if(!$trigger.is(event.target) && $trigger.has(event.target).length === 0){
	    		$('.feature-explanation-popup-main').not($(this).find('.feature-explanation-popup-main')).hide();
	        	$(this).find('.feature-explanation-popup-main').show();
	        	$('body').addClass('feature-explanation-popup-visible');
	    	}
	    });
	    $(document).on('click', '.dotstore-upgrade-dashboard .popup-close-btn', function () {
	    	$(this).parents('.feature-explanation-popup-main').hide();
	    	$('body').removeClass('feature-explanation-popup-visible');
	    });
	    /** Upgrade Dashboard Script End */

	    // Toggle chart table actions visibility script start
	    // Wrap tbody with a container div
  		$('tbody.scfw-table-actions-tbody').wrap('<div class="scfw-table-actions-body"></div>');
	    var show_table_actions = localStorage.getItem('scfw-table-action-display');
	    if( ( null !== show_table_actions || undefined !== show_table_actions ) && ( 'hide' === show_table_actions ) ) {
	        $('.scfw-table-action-toggle').addClass('scfw-table-action-hide');
	        $('.scfw-table-actions-body').css('display', 'none');
	    } else {
	        $('.scfw-table-action-toggle').removeClass('scfw-table-action-hide');
	        $('.scfw-table-actions-body').css('display', 'block');
	    }

	    $(document).on( 'click', '.scfw-table-action-toggle', function(){
	        $(this).toggleClass('scfw-table-action-hide');
	        $('.scfw-table-actions-body').slideToggle();
	        if( $(this).hasClass('scfw-table-action-hide') ){
	            localStorage.setItem('scfw-table-action-display', 'hide');
	        } else {
	            localStorage.setItem('scfw-table-action-display', 'show');
	        }
	    });
	    // Toggle chart table actions visibility script end
	});

	$(document).ready(sizeChartScripts.init);

    
})(jQuery, window, document);
