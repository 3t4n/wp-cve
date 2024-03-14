'use strict';

jQuery(document).ready(function($) {
	
	otw_form_init_fields();
});

function otw_form_init_fields(){
	
	jQuery( '.otw-form-select' ).on( 'change',  function(){
		jQuery( this ).parent().find( 'span' ).html( this.options[ this.selectedIndex ].text );
	} );
	
	var startingColour = '000000';
	jQuery( '.otw-color-selector' ).each( function(){ 
		
		var colourPicker = jQuery(this).ColorPicker({
		
		color: startingColour,
			onShow: function (colpkr) {
				jQuery(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				jQuery(colpkr).fadeOut(500);
				jQuery(colourPicker).next( 'input').change();
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				jQuery(colourPicker).children( 'div').css( 'backgroundColor', '#' + hex);
				jQuery(colourPicker).next( 'input').attr( 'value','#' + hex);
				
			}
		
		});
	});
	jQuery( '.otw-form-datetimepicker' ).each( function(){
		jQuery( this ).find( 'input' ).datetimepicker( {format: 'd.m.Y H:i' } );
	} );
	jQuery( '.otw-form-color-picker' ).on( 'change',  function(){
		jQuery( this ).parent( 'div' ).children( 'div' ).children( 'div' ).css( 'backgroundColor', this.value );
	});
	jQuery(  '.otw-form-uploader' ).on( 'change',  function(){
		otw_form_set_upload_preview_image( this.id );
	});
	jQuery(  '.otw-form-uploader-control' ).on( 'click',  function( event ){
	
		var $this = jQuery(this),
		editor = $this.data('editor'),
		
		options = {
			frame:    'post',
			state:    'insert',
			title:    wp.media.view.l10n.addMedia,
			multiple: true
		};
		
		event.preventDefault();
		$this.blur();
		if ( $this.hasClass( 'gallery' ) ) {
			options.state = 'gallery';
			options.title = wp.media.view.l10n.createGalleryTitle;
		}
		wp.media.editor.insert = function( params ){
		
			var matches = null;
			
			if( matches = params.match( /src="([^\"]*)"/ ) ){
				
				jQuery( '#' + editor ).val( matches[1] );
			}else{
				jQuery( '#' + editor ).val( '' );
			}
			jQuery( '#' + editor ).change();
			
			otw_form_set_upload_preview_image( editor );
		}
		
		wp.media.editor.open( editor, options );
	} );
	jQuery(  '.otw-form-uploader-control' ).each( function(){
		otw_form_set_upload_preview_image( jQuery( this ).data( 'editor' ) );
	});
	
	otw_form_init_dynamic_select_fields();
	
	otw_form_init_select_subfields();
	
	otw_form_init_active_items();
};

function otw_form_set_upload_preview_image( element_id ){

	var previewNode = jQuery( '#' + element_id + '-preview' );
	var previewURL  = jQuery( '#' + element_id ).val();
	
	previewNode.css('background-image', 'url("' + previewURL + '")');
	previewNode.css('background-repeat', 'no-repeat');
	
};

function otw_form_init_dynamic_select_fields(){
	
	jQuery(  '.otw-form-dynamic-select' ).each( function(){
	
		if( typeof( wp_url) == 'undefined' ){
			window.wp_url = 'admin-ajax';
		}
		
		var req_url = window.wp_url + '.php?action=otw_item_options_' + jQuery( this ).attr( 'id' ).replace( /\-/, '_' );
		
		var select_params = {};
		
		if( jQuery( this ).hasClass( 'drop_mask' ) ){
			select_params.allowClear = true;
			select_params.multiple = false;
			if( jQuery( '#' + jQuery( this ).attr( 'id' ) + '_json' ).size() && jQuery( '#' + jQuery( this ).attr( 'id' ) + '_json' ).val().length ){
				select_params.data = JSON.parse( jQuery( '#' + jQuery( this ).attr( 'id' ) + '_json' ).val() ).results;
			}
		}else{
			select_params.multiple = true;
			select_params.ajax = {
				url: req_url,
				dataType: 'json',
				data: function ( params ) {
					return {
						otw_search_term: params.term, //search term
						otw_options_limit: 10 // page size
					};
				},
				processResults: function (data, params) {
					return {
						results: data.results
					}
				}
			}
		}
		select_params.templateSelection = function( item ){
			return item.text;
		};
		select_params.templateResult = function( item ){ 
			return item.text;
		};
		
		var otw_form_select2_object = jQuery( this ).select2( select_params );
		
		otw_form_select2_object.on("select2:unselecting", function(e) {
			jQuery(this).data('state', 'unselected');
		} );
		
		otw_form_select2_object.on("select2:open", function(e) {
			
		if( jQuery(this).data('state') === 'unselected') {
				jQuery(this).removeData('state');
				var self = jQuery(this);
				setTimeout(function() {
					self.select2('close');
				}, 1);
			};
		});
		
		var initial_value = otw_form_select2_object.attr( 'data-value' );
		
		if( typeof( select_params.ajax ) != 'undefined' ){
			
			if( ( typeof( initial_value ) == 'string' ) && ( initial_value.length ) ){
				
				jQuery.ajax( select_params.ajax.url , {
					data: {
						otw_options_ids: initial_value
					},
					method: 'get',
					dataType: "json"
				}).done(function(data) {
					
					if( typeof( data.results ) == 'object' ){
						
						for( var cD = 0; cD < data.results.length; cD++ ){
							
							otw_form_select2_object.append( '<option value="' + data.results[ cD ].id + '" selected="selected">' + data.results[ cD ].text + '</option>');
						};
					};
				});
			};
		};
	
	});
	
	jQuery(  '.otw-dynamic-select-wrapper .otw-all-list' ).on( 'change', function(){
	    
		var input_node = jQuery( '#' + this.id.replace( /_allitems$/, '' ) );
		
		if( input_node.size() ){
			
			if( this.checked ){
				input_node.attr( 'disabled', 'disabled' );
			}else{
				input_node.removeAttr( 'disabled' );
			}
		}
	} );
	jQuery(  '.otw-dynamic-select-wrapper .otw-all-list' ).each( function(){
		var input_node = jQuery( '#' + this.id.replace( /_allitems$/, '' ) );
		
		if( input_node.size() ){
			
			if( this.checked ){
				input_node.attr( 'disabled', 'disabled' );
			}else{
				input_node.removeAttr( 'disabled' );
			}
		}
	} );
};

function otw_form_init_select_subfields(){
	
	jQuery( 'select.otw_with_subfield' ).on( 'change',  function(){
		otw_form_init_select_option_field( jQuery( this ) );
	});
	
	jQuery( 'select.otw_with_subfield' ).each( function(){
		otw_form_init_select_option_field( jQuery( this ) );
	});
};

function otw_form_init_active_items(){
	
	var otw_ac_elements = jQuery( '.otw-ac-elements' );
	
	if( otw_ac_elements.size() ){
		
		for( var cE = 0; cE < otw_ac_elements.size(); cE++ ){
			
			var itemElements = jQuery( otw_ac_elements[cE] ).find( '.otw-ac-items' ).val();
			
			if( typeof itemElements !== 'undefined' ){
				
				var splitedItems = itemElements.split(',');
				
				jQuery( splitedItems ).each( function( item, value ){
					
					jQuery( otw_ac_elements[cE] ).find( '.js-ac-items-inactive  > .otw-form-ac-item' ).each( function( miItem, miValue ){
					
						if( jQuery(miValue).data('value') === value ){
							jQuery( otw_ac_elements[cE] ).find( '.js-ac-items-active' ).append( miValue );
						};
					});
				});
			}
			
			jQuery( otw_ac_elements[cE] ).find( '.js-ac-items-active, .js-ac-items-inactive' ).sortable( {
				
				connectWith: ".otw-form-items-box",
				update: function( event, ui ) {
					
					var elementsArray = new Array();
					ui.item.parents( '.otw-ac-elements' ).first().find( '.js-ac-items-active > .otw-form-ac-item' ).each( function( item, value ){
						
						elementsArray.push( jQuery(value).data('value') );
						
						var value_container = ui.item.parents( '.otw-ac-elements' ).first().find( '.otw-ac-items' );
						
						if( value_container.size() ){
							value_container.val( elementsArray );
							value_container.change();
						};
					});
				},
				stop: function( event, ui ) {
					jQuery.event.trigger({
						type: "metaEvent"
					});
				}
			} );
		};
	};
};

function otw_form_init_select_option_field( select ){
	
	var parent = select.closest( 'div.otw-form-control' );
	parent.find( '.otw-form-subfield' ).fadeOut();
	
	var element_name = parent.attr( 'data-name' );
	
	var selected_node = jQuery( '#' + element_name + '_' + select.val() );
	
	if( selected_node.size() ){
		selected_node.fadeIn();
	};
};

function otw_form_set_upload_preview_image( element_id ){

	var previewNode = jQuery( '#' + element_id + '-preview' );
	var previewURL  = jQuery( '#' + element_id ).val();
	
	previewNode.css('background-image', 'url("' + previewURL + '")');
	previewNode.css('background-repeat', 'no-repeat');
	
};