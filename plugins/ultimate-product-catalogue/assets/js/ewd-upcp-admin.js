/* Used to show and hide the admin tabs for UPCP */

function Reload_PP_Page(Value) {
	var Layout = jQuery('#PP-type-select').val();
	window.location.href = "admin.php?page=UPCP-options&DisplayPage=ProductPage&CPP_Mobile=" + Layout;
}

function ShowToolTip(ToolTipID) {
	jQuery('#'+ToolTipID).css('display', 'block');
}

function HideToolTip(ToolTipID) {
	jQuery('#'+ToolTipID).css('display', 'none');
}

jQuery(document).ready(function() {
	SetTabDeleteHandlers();

	jQuery('.upcp-add-tab').on('click', function(event) {
		var ID = jQuery(this).data('nextid');

		var HTML = "<tr id='upcp-tab-" + ID + "'>";
		HTML += "<td><input type='text' name='Tab_" + ID + "_Name'></td>";
		HTML += "<td><textarea name='Tab_" + ID + "_Content'></textarea></td>";
		HTML += "<td><a class='upcp-delete-tab' data-tabnumber='" + ID + "'>Delete</a></td>";
		HTML += "</tr>";

		//jQuery('table > tr#ewd-uasp-add-reminder').before(HTML);
		jQuery('#upcp-tabs-table tr:last').before(HTML);

		ID++;
		jQuery(this).data('nextid', ID); //updates but doesn't show in DOM

		SetTabDeleteHandlers();

		event.preventDefault();
	});
});

function SetTabDeleteHandlers() {
	jQuery('.upcp-delete-tab').on('click', function(event) {
		var ID = jQuery(this).data('tabnumber');
		var tr = jQuery('#upcp-tab-'+ID);

		tr.fadeOut(400, function(){
            tr.remove();
        });

		event.preventDefault();
	});
}

//NEW DASHBOARD MOBILE MENU AND WIDGET TOGGLING
jQuery(document).ready(function($){
	$('#ewd-upcp-dash-mobile-menu-open').click(function(){
		$('.ewd-upcp-admin-header-menu .nav-tab:nth-of-type(1n+2)').toggle();
		$('#ewd-upcp-dash-mobile-menu-up-caret').toggle();
		$('#ewd-upcp-dash-mobile-menu-down-caret').toggle();
		return false;
	});
	$(function(){
		$(window).resize(function(){
			if($(window).width() > 785){
				$('.ewd-upcp-admin-header-menu .nav-tab:nth-of-type(1n+2)').show();
			}
			else{
				$('.ewd-upcp-admin-header-menu .nav-tab:nth-of-type(1n+2)').hide();
				$('#ewd-upcp-dash-mobile-menu-up-caret').hide();
				$('#ewd-upcp-dash-mobile-menu-down-caret').show();
			}
		}).resize();
	});	
	$('#ewd-upcp-dashboard-support-widget-box .ewd-upcp-dashboard-new-widget-box-top').click(function(){
		$('#ewd-upcp-dashboard-support-widget-box .ewd-upcp-dashboard-new-widget-box-bottom').toggle();
		$('#ewd-upcp-dash-mobile-support-up-caret').toggle();
		$('#ewd-upcp-dash-mobile-support-down-caret').toggle();
	});
	$('#ewd-upcp-dashboard-optional-table .ewd-upcp-dashboard-new-widget-box-top').click(function(){
		$('#ewd-upcp-dashboard-optional-table .ewd-upcp-dashboard-new-widget-box-bottom').toggle();
		$('#ewd-upcp-dash-optional-table-up-caret').toggle();
		$('#ewd-upcp-dash-optional-table-down-caret').toggle();
	});
});

/***********************************************
* CUSTOM FIELDS TABLE 
***********************************************/

jQuery( document ).ready( function() {

	ewd_upcp_custom_field_delete_handlers();

	jQuery( '.ewd-upcp-custom-fields-add' ).on( 'click', function() {
	
		var max_id = 1;
	
		jQuery( 'input[name="ewd_upcp_custom_field_id"]' ).each( function() {
	
			max_id = Math.max( max_id, jQuery( this ).val() );
		});
	
		max_id += 1;
	
		let _template = jQuery( '.ewd-upcp-custom-field-template' ).clone();
	
	    _template.hide()
	      .removeClass()
	      .addClass( 'ewd-upcp-custom-field' );
	
	    _template.find( 'input[name="ewd_upcp_custom_field_id"]' ).val( max_id );

	    jQuery( this ).before( _template );
	
	    _template.fadeIn( 'fast' );
	
		ewd_upcp_custom_field_delete_handlers();
	});

	jQuery( '#ewd-upcp-custom-fields-table' ).on( 'submit', function() {

		var custom_fields = [];

		jQuery( '.ewd-upcp-custom-field' ).each( function() {

			if ( jQuery( this ).find( 'input[name="ewd_upcp_custom_field_id"]' ).val() == 0 ) { return; }

			var custom_field = {};

			custom_field.id 					= jQuery( this ).find( 'input[name="ewd_upcp_custom_field_id"]' ).val();
			custom_field.name 					= jQuery( this ).find( 'input[name="ewd_upcp_custom_field_name"]' ).val();
			custom_field.slug 					= jQuery( this ).find( 'input[name="ewd_upcp_custom_field_slug"]' ).val();
			custom_field.type 					= jQuery( this ).find( 'select[name="ewd_upcp_custom_field_type"]' ).val();
			custom_field.options 				= jQuery( this ).find( 'input[name="ewd_upcp_custom_field_options"]' ).val();
			custom_field.displays 				= jQuery.map( jQuery( this ).find(':checkbox[name=ewd_upcp_custom_field_displays\\[\\]]:checked'), function(n, i) { return n.value; } );
			custom_field.searchable 			= jQuery( this ).find( 'input[name="ewd_upcp_custom_field_searchable"]' ).is( ':checked' );
			custom_field.filter_control_type 	= jQuery( this ).find( 'select[name="ewd_upcp_custom_field_filter_control_type"]' ).val();
			custom_field.tabbed_display			= jQuery( this ).find( 'input[name="ewd_upcp_custom_field_tabbed_display"]' ).is( ':checked' );
			custom_field.comparison_display		= jQuery( this ).find( 'input[name="ewd_upcp_custom_field_comparison_display"]' ).is( ':checked' );
			custom_field.woocommerce_id			= jQuery( this ).find( 'input[name="ewd_upcp_custom_field_woocommerce_id"]' ).val();
			
			custom_fields.push( custom_field ); 
		});

		jQuery( 'input[name="ewd-upcp-custom-field-save-values"]' ).val( JSON.stringify( custom_fields ) );
	});

	if ( jQuery( '#ewd-upcp-custom-fields-table' ).length ) {

		jQuery( '#ewd-upcp-custom-fields-table' ).sortable({
			items: 'div.ewd-upcp-custom-field',
			cursor: 'move'
		});
	}
});

function ewd_upcp_custom_field_delete_handlers() {

	jQuery( '.ewd-upcp-custom-field-delete' ).off( 'click' ).on( 'click', function() {

		jQuery( this ).parent().remove();

	});
}

jQuery( document ).ready( function() {

	jQuery( 'input[name="ewd_upcp_custom_field_searchable"]' ).click( function() {

		if ( jQuery( this ).is( ':checked' ) ) {

			jQuery( this ).parent().parent().parent().parent().find( 'select[name="ewd_upcp_custom_field_filter_control_type"]' ).prop( 'disabled', false );
		}
		else {

			jQuery( this ).parent().parent().parent().parent().find( 'select[name="ewd_upcp_custom_field_filter_control_type"]' ).prop( 'disabled', true );
		}
	});
});


/************************************************************************
* CATEGORIES & TAGS
************************************************************************/

jQuery( document ).ready(function( $ ) {
	var custom_uploader;
 
    jQuery( '#category_image_button' ).click(function(e) {
 
        e.preventDefault();
 
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
 
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on( 'select', function() {
            attachment = custom_uploader.state().get( 'selection' ).first().toJSON();
            jQuery( '#category_image' ).val(attachment.url);
        });
 
        //Open the uploader dialog
        custom_uploader.open();
 
    });

    if ( jQuery( 'input[name="taxonomy"]' ).length && jQuery( 'input[name="taxonomy"]' ).val() == 'upcp-product-category' ) {

    	jQuery( 'table.wp-list-table tbody' ).sortable({
    		cursor: 'move',
    		update: function() {

				var params = {
					nonce: ewd_upcp_php_admin_data.nonce,
					action: 'ewd_upcp_update_category_order'
				};

				var query_string = jQuery( 'table.wp-list-table tbody' ).sortable('serialize');
				var data = jQuery.param( params ) + '&' + query_string;
				jQuery.post( ajaxurl, data, function( response ) {});
    		}
    	})
    }

    if ( jQuery( 'input[name="taxonomy"]' ).length && jQuery( 'input[name="taxonomy"]' ).val() == 'upcp-product-tag' ) {

    	jQuery( 'table.wp-list-table tbody' ).sortable({
    		cursor: 'move',
    		update: function() {

				var params = {
					nonce: ewd_upcp_php_admin_data.nonce,
					action: 'ewd_upcp_update_tag_order'
				};

				var query_string = jQuery( 'table.wp-list-table tbody' ).sortable('serialize');
				var data = jQuery.param( params ) + '&' + query_string;
				jQuery.post( ajaxurl, data, function( response ) {});
    		}
    	})
    }
});

/*************************\
|****** PRODUCT PAGE *****|
\*************************/

jQuery( document ).ready( function() {

	if ( ! jQuery( 'input[name="taxonomy"]' ).length && jQuery( 'input[name="post_type"]' ).length && jQuery( 'input[name="post_type"]' ).val() == 'upcp_product' ) {

    	jQuery( 'table.wp-list-table tbody' ).sortable({
    		cursor: 'move',
    		update: function() {

				var params = {
					nonce: ewd_upcp_php_admin_data.nonce,
					action: 'ewd_upcp_update_product_order'
				};

				var query_string = jQuery( 'table.wp-list-table tbody' ).sortable('serialize');
				var data = jQuery.param( params ) + '&' + query_string;
				jQuery.post( ajaxurl, data, function( response ) {});
    		}
    	})
    }

	if ( typeof ewd_upcp_php_admin_data == 'undefined' || ewd_upcp_php_admin_data.product_add ) { return; }

	jQuery( 'h1.wp-heading-inline' ).after( '<p>Please <a href="https://www.etoilewebdesign.com/license-payment/?Selected=UPCP&Quantity=1"> upgrade to premium </a> to add additional products.</p>' );
} );

/*************************\
|*** PRODUCT EDIT META ***|
\*************************/

jQuery( document ).ready( function() {

	var custom_uploader;

	jQuery( '.ewd-upcp-product-meta-menu-tab:first-of-type' ).addClass( 'ewd-upcp-product-meta-menu-tab-selected' );

	jQuery( '.ewd-upcp-product-meta-tab' ).hide();

	jQuery( '.ewd-upcp-product-meta-tab[data-tab_id="details"]' ).show();

	jQuery( '.ewd-upcp-product-meta-menu-tab' ).on( 'click', function(event) {

		jQuery( '.ewd-upcp-product-meta-menu-tab' ).removeClass( 'ewd-upcp-product-meta-menu-tab-selected' );

		jQuery( this ).addClass( 'ewd-upcp-product-meta-menu-tab-selected' );

		jQuery( '.ewd-upcp-product-meta-tab' ).hide();
		jQuery( '.ewd-upcp-product-meta-tab[data-tab_id="' + jQuery( this ).data( 'tab_id' ) + '"]' ).show();
	});

	jQuery( '.ewd-upcp-add-related-product' ).on( 'click', function() {

		let _template = jQuery( '.ewd-upcp-related-product-template' ).clone();
	
	    _template.hide()
	      .removeClass()
	      .addClass( 'ewd-upcp-related-product' );

	    jQuery( this ).before( _template );
	
	    _template.fadeIn( 'fast' );

	    ewd_upcp_related_product_delete_handlers();
	});

	jQuery( '.ewd-upcp-add-product-image' ).on( 'click', function(e) {

		e.preventDefault();
 
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
 
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on( 'select', function() {

            attachment = custom_uploader.state().get( 'selection' ).first().toJSON();

             var image_html = '<div class="ewd-upcp-product-image">' +
				
				'<div class="ewd-upcp-product-image-image">' +
					'<img src="' + attachment.url + '" />' +
				'</div>' +

				'<input type="hidden" name="product_image_url[]" value="' + attachment.url + '" />' +

				'<div class="ewd-upcp-product-image-description">' +
					'<div class="ewd-upcp-product-image-description-label">' +
						'Image Description' +
					'</div>' +
					'<input type="text" name="product_image_description[]"" value="" />' +
				'</div>' +

				'<div class="ewd-upcp-product-image-delete">' +
					'<div class="ewd-upcp-delete-related-product">' +
						'Delete' +
					'</div>' +
				'</div>' +

			'</div>';

            jQuery( '.ewd-upcp-add-product-image' ).before( image_html );

            ewd_upcp_product_image_delete_handlers();
        });
 
        //Open the uploader dialog
        custom_uploader.open();
	});

	jQuery( '.ewd-upcp-add-product-video' ).on( 'click', function() {

		let _template = jQuery( '.ewd-upcp-product-video-template' ).clone();
	
	    _template.hide()
	      .removeClass()
	      .addClass( 'ewd-upcp-product-video' );

	    jQuery( this ).before( _template );
	
	    _template.fadeIn( 'fast' );

	    ewd_upcp_product_video_delete_handlers();
	});

	// TOGGLE SWITCHES
	jQuery('.ewd-upcp-admin-option-toggle').on('change', function() {
		var Input_Name = jQuery(this).data('inputname');
		if (jQuery(this).is(':checked')) {
			jQuery('input[name="' + Input_Name + '"][value="1"]').prop('checked', true).trigger('change');
			jQuery('input[name="' + Input_Name + '"][value=""]').prop('checked', false);
		}
		else {
			jQuery('input[name="' + Input_Name + '"][value="1"]').prop('checked', false).trigger('change');
			jQuery('input[name="' + Input_Name + '"][value=""]').prop('checked', true);
		}
	});
	
	ewd_upcp_related_product_delete_handlers();
	ewd_upcp_product_image_delete_handlers();
	ewd_upcp_product_video_delete_handlers();
});

function ewd_upcp_related_product_delete_handlers() {

	jQuery( '.ewd-upcp-delete-related-product' ).off( 'click' ).on( 'click', function() {

		jQuery( this ).parent().remove();

	});
}

function ewd_upcp_product_image_delete_handlers() {

	jQuery( '.ewd-upcp-product-image-delete' ).off( 'click' ).on( 'click', function() {

		jQuery( this ).parent().remove();

	});
}

function ewd_upcp_product_video_delete_handlers() {

	jQuery( '.ewd-upcp-product-video-delete' ).off( 'click' ).on( 'click', function() {

		jQuery( this ).parent().remove();

	});
}

/*************************\
|*** CATALOG EDIT META ***|
\*************************/

jQuery( document ).ready( function() {

	jQuery( '.ewd-upcp-catalog-meta-add-items-button' ).on( 'click', function(event) {

		jQuery( '.ewd-upcp-add-items-background-div' ).removeClass( 'ewd-upcp-hidden' );
	});

	jQuery( '.ewd-upcp-add-items-close-button' ).on( 'click', function(event) {

		jQuery( '.ewd-upcp-add-items-background-div' ).addClass( 'ewd-upcp-hidden' );
	});

	jQuery( '.ewd-upcp-meta-add-items-products' ).on( 'click', function(event) {

		jQuery( '.ewd-upcp-add-items-product-checkbox:checked' ).each( function() {

			var item_html = '<tr data-item_name="' + jQuery( this ).nextAll().eq(1).html() + '">' +

				'<td class="ewd-upcp-catalog-meta-item-delete">Delete</td>' +

				'<td>' + 

					'<input type="hidden" name="catalog_item_id[]" value="' + jQuery( this ).val() + '" />' +

					'<input type="hidden" name="catalog_item_type[]" value="product" />' +

					jQuery( this ).nextAll().eq(1).html() + 

				'</td>' +

				'<td>Product</td>' +

			'</tr>';

			jQuery( '.ewd-upcp-catalog-meta-items tbody' ).append( item_html );
		} );

		jQuery( '.ewd-upcp-add-items-background-div' ).addClass( 'ewd-upcp-hidden' );

		ewd_upcp_catalog_item_delete_handlers();
	});

	jQuery( '.ewd-upcp-meta-add-items-categories' ).on( 'click', function(event) {

		jQuery( '.ewd-upcp-add-items-category-checkbox:checked' ).each( function() {

			var item_html = '<tr data-item_name="' + jQuery( this ).nextAll().eq(1).html() + '">' +

				'<td class="ewd-upcp-catalog-meta-item-delete">Delete</td>' +

				'<td>' + 

					'<input type="hidden" name="catalog_item_id[]" value="' + jQuery( this ).val() + '" />' +

					'<input type="hidden" name="catalog_item_type[]" value="category" />' +

					jQuery( this ).nextAll().eq(1).html() + 

				'</td>' +

				'<td>Category</td>' +

			'</tr>';

			jQuery( '.ewd-upcp-catalog-meta-items tbody' ).append( item_html );
		} );

		jQuery( '.ewd-upcp-add-items-background-div' ).addClass( 'ewd-upcp-hidden' );
		
		ewd_upcp_catalog_item_delete_handlers();
	});

	jQuery( '.ewd-upcp-catalog-meta-add-items-label' ).on( 'click', function(event) {

		jQuery( '.ewd-upcp-catalog-meta-add-items-label' ).removeClass( 'ewd-upcp-meta-add-items-selected-label' );

		jQuery( this ).addClass( 'ewd-upcp-meta-add-items-selected-label' );

		jQuery( '.ewd-upcp-catalog-meta-add-items-selection' ).addClass( 'ewd-upcp-hidden' );

		jQuery( '.ewd-upcp-catalog-meta-add-items-selection[data-selected="' + jQuery( this ).data( 'selected' ) + '"]' ).removeClass( 'ewd-upcp-hidden' );
	});

	jQuery( 'table.ewd-upcp-catalog-meta-items tbody' ).sortable({
		cursor: 'move'
	});

	jQuery( '.ewd-upcp-catalog-sort-items-alphabetically' ).on( 'click', function( event ) { console.log( "Called" );

		jQuery( '.ewd-upcp-catalog-meta-items tbody' ).find( 'tr' ).sort( function( a, b ) {

			return jQuery( a ).data( 'item_name' ).localeCompare( jQuery( b ).data( 'item_name' ) );

		} ).appendTo( '.ewd-upcp-catalog-meta-items tbody' );
	});

	jQuery( '.ewd-upcp-catalog-sort-items-reverse-alphabetically' ).on( 'click', function( event ) { console.log( "Called" );

		jQuery( '.ewd-upcp-catalog-meta-items tbody' ).find( 'tr' ).sort( function( a, b ) {

			return jQuery( b ).data( 'item_name' ).localeCompare( jQuery( a ).data( 'item_name' ) );

		} ).appendTo( '.ewd-upcp-catalog-meta-items tbody' );
	});

	ewd_upcp_catalog_item_delete_handlers();
});

function ewd_upcp_catalog_item_delete_handlers() {

	jQuery( '.ewd-upcp-catalog-meta-item-delete' ).off( 'click' ).on( 'click', function() {

		jQuery( this ).parent().remove();

	});
}

/*************************\
|*** PRODUCT PAGE ***|
\*************************/

jQuery( document ).ready( function() {

	jQuery( 'select[name="product-page-selector"]' ).on( 'change', function() {

		jQuery( '.ewd-upcp-product-page-type' ).addClass( 'ewd-upcp-hidden' );

		jQuery( '.ewd-upcp-product-page-type[data-page="' + jQuery( this ).val() + '"]' ).removeClass( 'ewd-upcp-hidden' );
	} );

	jQuery( '.ewd-upcp-additional-tab-add' ).on( 'click', function() {

		let _template = jQuery( '.ewd-upcp-additional-tab-template' ).clone();
	
	    _template.hide()
	      .removeClass();

	    jQuery( this ).before( _template );
	
	    _template.fadeIn( 'fast' );
	} );

	ewd_upcp_product_tab_delete_handlers();
} );

function ewd_upcp_product_tab_delete_handlers() {

	jQuery( '.ewd-upcp-delete-custom-tab' ).off( 'click' ).on( 'click', function() {

		jQuery( this ).parent().remove();

	});
}

var gridster;
var gridster_mobile;
jQuery(function(){ //DOM Ready

	if ( ! jQuery( '.gridster ul' ).length ) { return; }
 		
	if (typeof grid_type === 'undefined' || grid_type === null) {grid_type = 'regular';}

	if (typeof pp_top_bottom_padding === 'undefined' || pp_top_bottom_padding === null) {pp_top_bottom_padding = 10;}
	if (typeof pp_left_right_padding === 'undefined' || pp_left_right_padding === null) {pp_left_right_padding = 10;}
	if (typeof pp_grid_width === 'undefined' || pp_grid_width === null) {pp_grid_width = 90;}
	if (typeof pp_grid_height === 'undefined' || pp_grid_height === null) {pp_grid_height = 35;}
		
	gridster_mobile = jQuery( '.gridster-mobile ul' ).gridster( {
        
        widget_margins: [pp_top_bottom_padding, pp_left_right_padding],
        widget_base_dimensions: [pp_grid_width, pp_grid_height],
		
		helper: 'clone',
		
		autogrow_cols: true,

        resize: {
          	enabled: true
        },
		
		serialize_params: function ($w, wgd) {
			
			return {

				element_type: $w.html(),
				element_class: $w.attr( 'data-elementclass' ),
				element_id: $w.attr( 'data-elementid' ),
				col: wgd.col,
              	row: wgd.row,
              	size_x: wgd.size_x,
              	size_y: wgd.size_y
			}
		}
   	} ).data( 'gridster' );
		
	jQuery( '.gridster-mobile-save' ).on( 'click', function( event ) {

		event.preventDefault();

		var params = {};

		params.nonce  = ewd_upcp_php_admin_data.nonce;
		params.type   = 'mobile';
		params.action = 'ewd_upcp_save_serialized_product_page';
		params.serialized_product_page = JSON.stringify( gridster_mobile.serialize() );

		var data = jQuery.param( params );
		jQuery.post( ajaxurl, data, function( response ) {

			jQuery( '.gridster-mobile-save' ).after( '<div class="ewd-upcp-gridster-response">Layout Saved!</div>' );

			setTimeout( function() { jQuery( '.ewd-upcp-gridster-response').remove(); }, 3000 );
		});
	});

	jQuery( '.ewd-upcp-custom-product-page-mobile-element-selector ul li a' ).on( 'click', function( event ) {

		event.preventDefault();

		if ( jQuery( this ).data( 'class' ) == 'text' ) {

			gridster_mobile.add_widget.apply( gridster_mobile, [ '<li data-elementclass="' + jQuery( this ).data( 'class' ) + '" data-elementid="' + jQuery( this ).data( 'id' ) + '">' + jQuery( this ).data( 'name' ) + '<div class="gs-delete-handle"></div><textarea class="ewd-upcp-pb-textarea"></textarea></li>', jQuery( this ).data( 'x_size' ), jQuery( this ).data( 'y_size' ) ] );
		}
		else {

			gridster_mobile.add_widget.apply( gridster_mobile, [ '<li data-elementclass="' + jQuery( this ).data( 'class' ) + '" data-elementid="' + jQuery( this ).data( 'id' ) + '">' + jQuery( this ).data( 'name' ) + '<div class="gs-delete-handle"></div></li>', jQuery( this ).data( 'x_size' ), jQuery( this ).data( 'y_size' ) ] );
		}
	});
	
	gridster = jQuery( '.gridster-large ul' ).gridster( {
        
        widget_margins: [ pp_top_bottom_padding, pp_left_right_padding ],
        widget_base_dimensions: [ pp_grid_width, pp_grid_height ],
		
		helper: 'clone',

		autogrow_cols: true,
        
        resize: {
          	enabled: true
        },
		
		serialize_params: function ($w, wgd) {

			return {

				element_type: $w.html(),
				element_class: $w.attr( 'data-elementclass' ),
				element_id: $w.attr( 'data-elementid' ),
				col: wgd.col,
              	row: wgd.row,
              	size_x: wgd.size_x,
              	size_y: wgd.size_y
			}
		}
   	}).data( 'gridster' );
		
	jQuery( '.gridster-large-save' ).on( 'click', function( event ) {

		event.preventDefault();

		var params = {};

		params.nonce  = ewd_upcp_php_admin_data.nonce;
		params.type   = 'large';
		params.action = 'ewd_upcp_save_serialized_product_page';
		params.serialized_product_page = JSON.stringify( gridster.serialize() );

		var data = jQuery.param( params );
		jQuery.post( ajaxurl, data, function( response ) {

			jQuery( '.gridster-large-save' ).after( '<div class="ewd-upcp-gridster-response">Layout Saved!</div>' );

			setTimeout( function() { jQuery( '.ewd-upcp-gridster-response').remove(); }, 3000 );
		});
	});

	jQuery( '.ewd-upcp-custom-product-page-large-element-selector ul li a' ).on( 'click', function( event ) {

		event.preventDefault();

		if ( jQuery( this ).data( 'class' ) == 'text' ) {

			gridster.add_widget.apply( gridster, [ '<li data-elementclass="' + jQuery( this ).data( 'class' ) + '" data-elementid="' + jQuery( this ).data( 'id' ) + '">' + jQuery( this ).data( 'name' ) + '<div class="gs-delete-handle"></div><textarea class="ewd-upcp-pb-textarea"></textarea></li>', jQuery( this ).data( 'x_size' ), jQuery( this ).data( 'y_size' ) ] );
		}
		else {

			gridster.add_widget.apply( gridster, [ '<li data-elementclass="' + jQuery( this ).data( 'class' ) + '" data-elementid="' + jQuery( this ).data( 'id' ) + '">' + jQuery( this ).data( 'name' ) + '<div class="gs-delete-handle"></div></li>', jQuery( this ).data( 'x_size' ), jQuery( this ).data( 'y_size' ) ] );
		}
	});

	ewd_upcp_gridster_handlers();
});

function ewd_upcp_gridster_handlers() {

	jQuery( '.ewd-upcp-pb-textarea' ).off( 'keyup' ).on( 'keyup', function() {

		jQuery( this ).parent().data( 'elementid', jQuery( this ).val() );
	} );

	jQuery( '.gs-delete-handle' ).off( 'click' ).on( 'click', function() {

		gridster.remove_widget( jQuery( this ).parent() );
	});

	jQuery( '.gs-delete-mobile-handle' ).off( 'click' ).on( 'click', function() {

		gridster_mobile.remove_widget( jQuery( this ).parent() );
	});
}


// About Us Page
jQuery( document ).ready( function( $ ) {

	jQuery( '.ewd-upcp-about-us-tab-menu-item' ).on( 'click', function() {

		jQuery( '.ewd-upcp-about-us-tab-menu-item' ).removeClass( 'ewd-upcp-tab-selected' );
		jQuery( '.ewd-upcp-about-us-tab' ).addClass( 'ewd-upcp-hidden' );

		var tab = jQuery( this ).data( 'tab' );

		jQuery( this ).addClass( 'ewd-upcp-tab-selected' );
		jQuery( '.ewd-upcp-about-us-tab[data-tab="' + tab + '"]' ).removeClass( 'ewd-upcp-hidden' );
	} );

	jQuery( '.ewd-upcp-about-us-send-feature-suggestion' ).on( 'click', function() {

		var feature_suggestion = jQuery( '.ewd-upcp-about-us-feature-suggestion textarea' ).val();
		var email_address = jQuery( '.ewd-upcp-about-us-feature-suggestion input[name="feature_suggestion_email_address"]' ).val();
	
		var params = {};

		params.nonce  				= ewd_upcp_php_admin_data.nonce;
		params.action 				= 'ewd_upcp_send_feature_suggestion';
		params.feature_suggestion	= feature_suggestion;
		params.email_address 		= email_address;

		var data = jQuery.param( params );
		jQuery.post( ajaxurl, data, function() {} );

		jQuery( '.ewd-upcp-about-us-feature-suggestion' ).prepend( '<p>Thank you, your feature suggestion has been submitted.' );
	} );
} );


//SETTINGS PREVIEW SCREENS

jQuery( document ).ready( function() {

	jQuery( '.ewd-upcp-settings-preview' ).prevAll( 'h2' ).hide();
	jQuery( '.ewd-upcp-settings-preview' ).prevAll( '.sap-tutorial-toggle' ).hide();
	jQuery( '.ewd-upcp-settings-preview .sap-tutorial-toggle' ).hide();
});
