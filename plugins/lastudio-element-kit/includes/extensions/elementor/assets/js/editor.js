(function( $ ) {

	'use strict';

	var LaStudioQueryControl = {
		init: function() {

			var QueryControlItemView = elementor.modules.controls.Select2.extend({

				hasTitles: false,

				getSelect2DefaultOptions: function getSelect2DefaultOptions() {
					var self = this;

					return jQuery.extend(elementor.modules.controls.Select2.prototype.getSelect2DefaultOptions.apply(this, arguments), {
						ajax: {
							transport: function transport(params, success, failure) {
								var data = {
									q: params.data.q,
									filter_type: self.model.get('filter_type'),
									object_type: self.model.get('object_type'),
									include_type: self.model.get('include_type'),
									query: self.model.get('query')
								};
								if( "undefined" !== self.model.get('autocomplete')){
									data.autocomplete = self.model.get('autocomplete');
								}

								return elementor.ajax.addRequest('lastudiokit_query_control_filter_autocomplete', {
									data: data,
									success: success,
									error: failure
								});
							},
							cache: true
						},
						escapeMarkup: function escapeMarkup(markup) {
							return markup;
						},
						minimumInputLength: 1
					});
				},

				getControlValueByName: function getControlValueByName(controlName) {
					var name = this.model.get('group_prefix') + controlName;
					return this.elementSettingsModel.attributes[name];
				},

				getQueryData: function getQueryData() {
					// Use a clone to keep model data unchanged:
					var autocomplete = elementorCommon.helpers.cloneObject(this.model.get('autocomplete'));

					if (_.isEmpty(autocomplete.query)) {
						autocomplete.query = {};
					} // Specific for Group_Control_Query


					if ('cpt_tax' === autocomplete.object) {
						autocomplete.object = 'tax';

						if (_.isEmpty(autocomplete.query) || _.isEmpty(autocomplete.query.post_type)) {
							autocomplete.query.post_type = this.getControlValueByName('post_type');
						}
					}

					return {
						autocomplete: autocomplete
					};
				},

				getOptionsTitles: function getOptionsTitles() {
					var self = this,
						data = {},
						bcFormat = !_.isEmpty(this.model.get('filter_type'));
					var ids = this.getControlValue(),
						action = 'lastudiokit_query_control_value_titles',
						filterTypeName = 'autocomplete',
						filterType = {};

					if (bcFormat) {
						filterTypeName = 'filter_type';
						filterType = this.model.get(filterTypeName);
						data.filter_type = filterType;
						data.object_type = self.model.get('object_type');
						data.include_type = self.model.get('include_type');
						data.unique_id = '' + self.cid + filterType;
						action = 'lastudiokit_query_control_value_titles';
					} else {
						filterType = this.model.get(filterTypeName).object;
						data.get_titles = self.getQueryData().autocomplete;
						data.unique_id = '' + self.cid + filterType;
					}

					if (!ids || !filterType) {
						return;
					}

					if (!_.isArray(ids)) {
						ids = [ids];
					}

					elementorCommon.ajax.loadObjects({
						action: action,
						ids: ids,
						data: data,
						before: function before() {
							self.addControlSpinner();
						},
						success: function success(ajaxData) {
							self.hasTitles = true;
							self.model.set('options', ajaxData);
							self.render();
						}
					});
				},

				addControlSpinner: function addControlSpinner() {
					this.ui.select.prop('disabled', true);
					this.$el.find('.elementor-control-title').after('<span class="elementor-control-spinner">&nbsp;<i class="fa fa-spinner fa-spin"></i>&nbsp;</span>');
				},

				onReady: function onReady() {

					this.ui.select.select2( this.getSelect2Options() );

					if ( !this.hasTitles ) {
						this.getOptionsTitles();
					}
				}
			});

			var LakitSearchView = window.elementor.modules.controls.BaseData.extend( {

				hasTitles: false,

				getAjaxUrl: function( action, queryParams ) {
					var query = '';

					if ( queryParams.length > 0 ) {
						$.each( queryParams, function( index, param ) {

							if ( window.elementor.settings.page.model.attributes[ param ] ) {
								query += '&' + param + '=' + window.elementor.settings.page.model.attributes[ param ];
							}
						});
					}

					return ajaxurl + '?action=' + action + query;
				},

				onReady: function() {
					var self        = this,
						action      = this.model.attributes.action,
						queryParams = this.model.attributes.query_params;

					this.ui.select.find( 'option' ).each(function(index, el) {
						$( this ).attr( 'selected', true );
					});

					this.ui.select.select2( {
						ajax: {
							url: function(){
								return self.getAjaxUrl( action, queryParams );
							},
							dataType: 'json'
						},
						placeholder: 'Please enter 3 or more characters',
						minimumInputLength: 3,
						allowClear: true
					} );

					if ( !this.hasTitles ) {
						this.getOptionsTitles();
					}

				},

				getOptionsTitles: function getOptionsTitles() {
					var self        = this,
						action      = this.model.attributes.action,
						queryParams = this.model.attributes.query_params,
						queryIds    = this.getControlValue();

					if ( !queryIds ) {
						return;
					}

					if ( $.isArray( queryIds ) ) {
						queryIds = queryIds.join();
					}

					var url = self.getAjaxUrl( action, queryParams ) + '&ids' + '=' + queryIds;

					$.ajax( {
						url: url,
						dataType: 'json',
						beforeSend: function() {
							self.ui.select.prop( 'disabled', true );
						},
						success: function( response ) {
							self.hasTitles = true;

							self.model.set( 'saved',  self.prepareOptions( response.results ) );
							self.render();
						}
					} );
				},

				prepareOptions: function prepareOptions( options ) {
					var result = {};

					$.each( options, function( index, item ) {
						result[ item.id ] = item.text;
					} );

					return result;
				},

				onBeforeDestroy: function() {

					if ( this.ui.select.data( 'select2' ) ) {
						this.ui.select.select2( 'destroy' );
					}

					this.$el.remove();
				}

			} );

			// Add controls views
			elementor.addControlView( 'lastudiokit-query',    	QueryControlItemView );
			elementor.addControlView( 'lakit_search',    		LakitSearchView );
		}
	};

	var CustomCSS = {
		init: function (){
			if(typeof window.elementorPro === "undefined"){
				elementor.hooks.addFilter('editor/style/styleText', CustomCSS.addCustomCss);
				elementor.hooks.addFilter('editor/style/styleText', CustomCSS.addCustomAttr);
			}

			// elementor.hooks.addFilter('editor/style/styleText', function ( content, context ){
			// 	if (!context) {
			// 		return content;
			// 	}
			// 	if(context.el && 'container' == context.el.getAttribute('data-element_type') && context.nestingLevel !== undefined){
			// 		console.log(context.nestingLevel);
			// 		if(context.nestingLevel){
			// 			context.el.classList.remove('e-root-container');
			// 			context.el.classList.remove('elementor-top-section');
			// 		}
			// 		else{
			// 			context.el.classList.add('e-root-container');
			// 			context.el.classList.add('elementor-top-section');
			// 		}
			// 	}
			// 	return content;
			// });

			// Page Layout Options
			window.elementor.settings.page.addChangeCallback( 'lakit_header_vertical', function( newValue ) {
				var $elementView = window.elementor.previewView.$el.closest('.lakit-site-wrapper');
				var $firstSection = $elementView.find('> .elementor-location-header > .elementor-element:first-child');
				if(newValue === 'yes'){
					$elementView.addClass('lakit--is-vheader lakit-vheader-pleft lakit-vheader--hidetablet');
					if( $('> .e-con-inner', $firstSection).length === 0 ){
						$firstSection.wrapInner('<div class="e-con-inner" data-wrap="yes"/>')
					}
				}
				else{
					$elementView.removeClass('lakit--is-vheader lakit-vheader-pleft lakit-vheader-pright lakit-vheader--hidetablet lakit-vheader--hidelaptop lakit-vheader--hidemobile lakit-vheader--hidemobile_extra');
					// $('>.elementor-container > .elementor-element', $firstSection).unwrap();
				}
			} );
			window.elementor.settings.page.addChangeCallback( 'lakit_header_vertical_alignment', function( newValue ) {
				var $elementView = window.elementor.previewView.$el.closest('.lakit-site-wrapper');
				var _old_class = $elementView.attr('class');
				$elementView.attr('class', _old_class.replace(/lakit-vheader-p(\w+)/, 'lakit-vheader-p' + newValue));
			} );
			window.elementor.settings.page.addChangeCallback( 'lakit_header_vertical_disable_on', function( newValue ) {
				var $elementView = window.elementor.previewView.$el.closest('.lakit-site-wrapper');
				var _old_class = $elementView.attr('class');
				$elementView.attr('class', _old_class.replace(/lakit-vheader--hide(\w+)/, 'lakit-vheader--hide' + newValue));
			} );


			// Vertical Document Settings
			window.elementor.settings.page.addChangeCallback( 'lakit_doc_enable_header_transparency', function( newValue ) {
				var $elementView = window.elementor.previewView.$el.closest('body');
				if(newValue === 'yes'){
					$elementView.addClass('lakitdoc-enable-header-transparency');
				}
				else{
					$elementView.removeClass('lakitdoc-enable-header-transparency');
				}
			} );
			window.elementor.settings.page.addChangeCallback( 'lakit_doc_swap_logo', function( newValue ) {
				var $elementView = window.elementor.previewView.$el.closest('body');
				if(newValue === 'yes'){
					$elementView.addClass('lakitdoc-swap-logo');
				}
				else{
					$elementView.removeClass('lakitdoc-swap-logo');
				}
			} );
		},

		addCustomAttr: function ( content, context ){
			if (!context) {
				return content;
			}
			const model = context.model,
				customAttr = model.get('settings').get('_attributes') || '';

			if(customAttr !== ''){
				const tmpAttr = customAttr.split('\n');
				const black_list = [ 'id', 'class', 'data-id', 'data-settings', 'data-element_type', 'data-widget_type', 'data-model-cid' ];
				Array.from(context.el.attributes).forEach(({ name }) => {
					if(!black_list.includes(name)){
						context.el.removeAttribute(name);
					}
				})
				tmpAttr.forEach( _item => {
					const _attr = _item.split('|').map( _el => _el.replace(/[^a-zA-Z0-9_-]/g, '') )
					if(!black_list.includes(_attr[0])){
						context.el.setAttribute(_attr[0], _attr[1] ?? '');
					}
				} )
			}
			return content;
		},

		addCustomCss: function (css, context) {

			if (!context) {
				return css;
			}

			var model = context.model,
				customCSS = model.get('settings').get('custom_css'),
				selector = '.elementor-element.elementor-element-' + model.get('id');

			if ('document' === model.get('elType')) {
				selector = elementor.config.document.settings.cssWrapperSelector;
			}

			if (customCSS) {
				css += customCSS.replace(/selector/g, selector);
			}

			return css;
		}
	}

	$( window ).on( 'elementor:init', LaStudioQueryControl.init );
	$( window ).on( 'elementor:init', CustomCSS.init );

}( jQuery ));