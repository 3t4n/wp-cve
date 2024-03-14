jQuery(document).ready(function($) {

	jQuery( '#ewd-uwcf-filtering-form' ).on( 'change', function() {
		
		Reload_Page_With_Filters();
	});

	if ( jQuery('.ewd-uwcf-min-price').length ) {
		jQuery('#ewd-uwcf-filtering-form input[name="min_price"]').val( jQuery('.ewd-uwcf-min-price').val() );
	}

	if ( jQuery('.ewd-uwcf-max-price').length ) {
		jQuery('#ewd-uwcf-filtering-form input[name="max_price"]').val( jQuery('.ewd-uwcf-max-price').val() );
	}

	Add_Reset_All_Handlers();
	Add_Product_Search_Handlers();
	Add_Price_Textbox_Handlers();
	Add_Ratings_Slider_Handlers();
	Add_Price_Slider_Handlers();
	Add_InStock_Handlers();
	Add_OnSale_Handlers();
	Add_Color_Click_Handlers();
	Add_Size_Click_Handlers();
	Add_Category_Click_Handlers();
	Add_Tag_Click_Handlers();
	Add_Attribute_Click_Handlers();
	Add_Sorting_Click_Handlers();
});

function Reload_Page_With_Filters() {
	var URL = jQuery('#ewd-uwcf-filtering-form input[name="shop_url"]').val() + '?';

	var Connector = '';

	if ( jQuery('.ewd-uwcf-orderby').val() != '' && jQuery('.ewd-uwcf-orderby').val() != undefined ) {
		URL += Connector + 'orderby=' + jQuery('.ewd-uwcf-orderby').val();
		Connector = '&';
	}

	var min_price = jQuery('#ewd-uwcf-filtering-form input[name="min_price"]').val();
	var max_price = jQuery('#ewd-uwcf-filtering-form input[name="max_price"]').val();

	if (jQuery('.ewd-uwcf-min-price').length) {min_price = jQuery('.ewd-uwcf-min-price').val();}
	if (jQuery('.ewd-uwcf-max-price').length) {max_price = jQuery('.ewd-uwcf-max-price').val();}

	if (jQuery("#ewd-uwcf-price-slider").length) {min_price = jQuery("#ewd-uwcf-price-slider").data('min_price');}
	if (jQuery("#ewd-uwcf-price-slider").length) {max_price = jQuery("#ewd-uwcf-price-slider").data('max_price');}

	if (min_price != 0 && min_price != undefined) {URL += Connector + 'min_price=' + min_price; Connector = '&';}
	if (max_price != 0 && max_price != undefined) {URL += Connector + 'max_price=' + max_price; Connector = '&';}

	if (jQuery('.ewd-uwcf-instock-checkbox').is(':checked')) {URL += Connector + 'instock=true'; Connector = '&';}
	if (jQuery('.ewd-uwcf-onsale-checkbox').is(':checked')) {URL += Connector + 'onsale=true'; Connector = '&';}

	var Colors = '';
	jQuery('.ewd-uwcf-color').each(function(index, el) {
		if (jQuery(this).is(':checked')) {Colors += jQuery(this).val() + ',';}
	});
	if (Colors != '') {URL += Connector + 'product_color=' + Colors.slice(0,-1); Connector = '&'}

	var Sizes = '';
	jQuery('.ewd-uwcf-size').each(function(index, el) {
		if (jQuery(this).is(':checked')) {Sizes += jQuery(this).val() + ',';}
	});
	if (Sizes != '') {URL += Connector + 'product_size=' + Sizes.slice(0,-1); Connector = '&'}

	var Categories = '';
	jQuery('.ewd-uwcf-category').each(function(index, el) {
		if (jQuery(this).is(':checked')) {Categories += jQuery(this).val() + ',';}
	});
	if (Categories != '') {URL += Connector + 'product_cat=' + Categories.slice(0,-1); Connector = '&';}

	var Tags = '';
	jQuery('.ewd-uwcf-tag').each(function(index, el) {
		if (jQuery(this).is(':checked')) {Tags += jQuery(this).val() + ',';}
	});
	if (Tags != '') {URL += Connector + 'product_tag=' + Tags.slice(0,-1); Connector = '&';}

	jQuery('.ewd-uwcf-attribute-filters-wrap').each(function(index, el) {
		var attribute_name = jQuery(this).data('attribute_name');
		var Attributes = '';
		jQuery(this).find('.ewd-uwcf-attribute').each(function(index, el) {
			if (jQuery(this).is(':checked')) {Attributes += jQuery(this).val() + ',';}
		});
		if (Attributes != '') {URL += Connector + attribute_name + '=' + Attributes.slice(0,-1); Connector = '&';}
	});

	var Search_String = '';
	if (jQuery('.ewd-uwcf-text-search-input').val() != '' && jQuery('.ewd-uwcf-text-search-input').val() != undefined) { Search_String = jQuery('.ewd-uwcf-text-search-input').val(); }
	if (Search_String != '') {URL += Connector + 's=' + Search_String; Connector = '&';}

	var selected_min = jQuery("#ewd-uwcf-ratings-slider").data('min_rating');
    var selected_max = jQuery("#ewd-uwcf-ratings-slider").data('max_rating');
    if (selected_min > 1) {URL += Connector + 'min_rating=' + selected_min; Connector = '&';}
    if (selected_max < 5) {URL += Connector + 'max_rating=' + selected_max; Connector = '&';}

	window.location.href = URL;
}

function Add_Color_Click_Handlers() {

	jQuery( '.ewd-uwcf-color-link, .ewd-uwcf-color-wrap' ).on( 'click', function() {

		var checkbox = jQuery( this ).parent().find( '.ewd-uwcf-filtering-checkbox' );

		if ( checkbox.is( ':checked' ) ) { checkbox.prop( 'checked', false ).trigger( 'change' ); }
		else { checkbox.prop( 'checked', true ).trigger( 'change' ); }

		jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
	});

	jQuery('.ewd-uwcf-all-color').on('click', function() {

		jQuery( '.ewd-uwcf-color' ).prop( 'checked', false );

		jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
	});

	jQuery( '.ewd-uwcf-color-dropdown' ).css( 'background', jQuery( '.ewd-uwcf-color-dropdown option[selected]' ).css( 'background' ) );
	jQuery( '.ewd-uwcf-color-dropdown' ).on( 'change', function() { 
		
		jQuery( '.ewd-uwcf-color' ).prop( 'checked', false );
		jQuery( '.ewd-uwcf-color[value="' + jQuery(this).val() + '"]' ).prop( 'checked', true );

		jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
	});
}

function Add_Size_Click_Handlers() {

	jQuery('.ewd-uwcf-size-link').on('click', function() {

		var checkbox = jQuery( this ).parent().find( '.ewd-uwcf-filtering-checkbox' );

		if ( checkbox.is( ':checked' ) ) { checkbox.prop( 'checked', false ).trigger( 'change' ); }
		else { checkbox.prop( 'checked', true ).trigger( 'change' ); }

		jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
	});

	jQuery( '.ewd-uwcf-all-size' ).on( 'click', function() {

		jQuery( '.ewd-uwcf-size' ).prop( 'checked', false );

		jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
	});

	jQuery( '.ewd-uwcf-size-dropdown' ).on( 'change', function() {

		jQuery( '.ewd-uwcf-size' ).prop( 'checked', false );
		jQuery( '.ewd-uwcf-size[value="' + jQuery(this).val() + '"]' ).prop( 'checked', true );

		jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
	});
}

function Add_Category_Click_Handlers() {

	jQuery( '.ewd-uwcf-category-link' ).on( 'click', function() {

		var checkbox = jQuery(this).parent().find( '.ewd-uwcf-filtering-checkbox' );

		if ( checkbox.is( ':checked' ) ) { checkbox.prop( 'checked', false ).trigger( 'change' ); }
		else { checkbox.prop( 'checked', true ).trigger( 'change' ); }

		jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
	});

	jQuery( '.ewd-uwcf-all-category' ).on( 'click', function() {

		jQuery( '.ewd-uwcf-category' ).prop( 'checked', false );

		jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
	});

	jQuery( '.ewd-uwcf-category-dropdown' ).on( 'change', function() {

		jQuery( '.ewd-uwcf-category' ).prop( 'checked', false );
		jQuery( '.ewd-uwcf-category[value="' + jQuery(this).val() + '"]' ).prop( 'checked', true );

		jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
	});
}

function Add_Tag_Click_Handlers() {

	jQuery('.ewd-uwcf-tag-link').on('click', function() {

		var checkbox = jQuery(this).parent().find( '.ewd-uwcf-filtering-checkbox' );

		if ( checkbox.is( ':checked' ) ) { checkbox.prop( 'checked', false ).trigger( 'change' ); }
		else { checkbox.prop( 'checked', true ).trigger( 'change' ); }

		jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
	});

	jQuery( '.ewd-uwcf-all-tags' ).on( 'click', function() {

		jQuery('.ewd-uwcf-tag').prop('checked', false);

		jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
	});

	jQuery( '.ewd-uwcf-tag-dropdown' ).on( 'change', function() {

		jQuery( '.ewd-uwcf-tag' ).prop( 'checked', false );
		jQuery( '.ewd-uwcf-tag[value="' + jQuery(this).val() + '"]' ).prop( 'checked', true );

		jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
	});
}

function Add_Attribute_Click_Handlers() {

	jQuery('.ewd-uwcf-attribute-link ').on('click', function() {

		var checkbox = jQuery( this ).parent().find( '.ewd-uwcf-filtering-checkbox' );

		if ( checkbox.is( ':checked') ) { checkbox.prop( 'checked', false ).trigger( 'change' ); }
		else { checkbox.prop( 'checked', true ).trigger( 'change' ); }

		jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
	});

	jQuery( '.ewd-uwcf-all-attributes' ).on( 'click', function() {

		jQuery( '.ewd-uwcf-attribute' ).prop( 'checked', false );

		jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
	});

	jQuery( '.ewd-uwcf-attribute-dropdown' ).on( 'change', function() {

		jQuery( '.ewd-uwcf-attribute' ).prop( 'checked', false );
		jQuery( '.ewd-uwcf-attribute[value="' + jQuery(this).val() + '"]' ).prop( 'checked', true );

		jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
	});
}

function Add_Product_Search_Handlers() {

	jQuery( '.ewd-uwcf-text-search-submit' ).on( 'click', function() {

		jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
	});

	if ( jQuery( '.ewd-uwcf-text-search-autocomplete' ).length ) {

		var product_names = [];

		jQuery( ewd_uwcf_php_data.products ).each( function( index, el ) {

			product_names.push( el.post_title );
		});
		
		jQuery( '.ewd-uwcf-text-search-input' ).on( 'keyup', function() {

			jQuery('.ewd-uwcf-text-search-input').autocomplete({
				source: product_names,
				minLength: 3,
				appendTo: "#ewd-ufaq-jquery-ajax-search",
				select: function( event, ui ) {
					jQuery( this ).val( ui.item.value );
					jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
				}
			});

			jQuery('#ufaq-ajax-text-input').autocomplete( "enable" );
		}); 
	}
}

function Add_Price_Textbox_Handlers() {

	jQuery( '.ewd-uwcf-min-price, .ewd-uwcf-min-price' ).on( 'change', function() { 

		Reload_Page_With_Filters();
	});
}

function Add_Reset_All_Handlers() {

	jQuery( '.ewd-uwcf-reset-all' ).on( 'click', function() {

		var URL = jQuery( '#ewd-uwcf-filtering-form' ).find( 'input[name="shop_url"]' ).val();

		window.location.href = URL;
	});
}

function Add_Ratings_Slider_Handlers() {
	var min_rating = jQuery("#ewd-uwcf-ratings-slider").data('min_rating');
	var max_rating = jQuery("#ewd-uwcf-ratings-slider").data('max_rating');

	jQuery("#ewd-uwcf-ratings-slider").slider({
    	range: true,
    	min: 1,
    	max: 5,
    	values: [ min_rating, max_rating ],
        change: function( event, ui ) {
        	jQuery("#ewd-uwcf-ratings-slider").data('min_rating', ui.values[ 0 ]);
        	jQuery("#ewd-uwcf-ratings-slider").data('max_rating', ui.values[ 1 ]);

        	jQuery(".ewd-uwcf-ratings-slider-min").html(ui.values[ 0 ]);
        	jQuery(".ewd-uwcf-ratings-slider-max").html(ui.values[ 1 ]);

        	Reload_Page_With_Filters();
        }
    });
}

function Add_Price_Slider_Handlers() {
	var min_price = jQuery("#ewd-uwcf-price-slider").data('min_price');
	var max_price = jQuery("#ewd-uwcf-price-slider").data('max_price');
	var wc_max_price = jQuery('#ewd-uwcf-price-slider').data('wc_max_price');

	jQuery("#ewd-uwcf-price-slider").slider({
    	range: true,
    	min: 0,
    	max: wc_max_price,
    	values: [ min_price, max_price ],
        change: function( event, ui ) {
        	jQuery("#ewd-uwcf-price-slider").data('min_price', ui.values[ 0 ]);
        	jQuery("#ewd-uwcf-price-slider").data('max_price', ui.values[ 1 ]);

        	jQuery(".ewd-uwcf-price-slider-min").html(ui.values[ 0 ]);
        	jQuery(".ewd-uwcf-price-slider-max").html(ui.values[ 1 ]);

        	Reload_Page_With_Filters();
        }
    });
}

function Add_InStock_Handlers() {

	jQuery( '.ewd-uwcf-instock-text' ).on( 'click', function() {
		var checkbox = jQuery( this ).parent().find( '.ewd-uwcf-instock-checkbox' );

		if ( checkbox.is( ':checked' ) ) { checkbox.prop( 'checked', false ).trigger( 'change' ); }
		else { checkbox.prop( 'checked', true ).trigger( 'change' ); }

		jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
	});

	jQuery( '.ewd-uwcf-instock-checkbox' ).on( 'click', function() {

		jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
	});
}

function Add_OnSale_Handlers() {

	jQuery('.ewd-uwcf-onsale-text').on('click', function() {

		var checkbox = jQuery( this ).parent().find('.ewd-uwcf-onsale-checkbox');

		if ( checkbox.is( ':checked' ) ) { checkbox.prop( 'checked', false ).trigger( 'change' ); }
		else { checkbox.prop( 'checked', true ).trigger( 'change' ); }

		jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
	});

	jQuery( '.ewd-uwcf-onsale-checkbox' ).on( 'click', function() {

		jQuery( '#ewd-uwcf-filtering-form' ).trigger( 'change' );
	});
}

function Add_Sorting_Click_Handlers() {
	jQuery('.uwcf-table-format-header').on('click', function() {
		if (jQuery('.ewd-uwcf-orderby').val() == jQuery(this).data('orderby')) {
			jQuery('.ewd-uwcf-orderby').val(jQuery(this).data('orderby') + '-desc');
		}
		else {jQuery('.ewd-uwcf-orderby').val(jQuery(this).data('orderby'));}

		Reload_Page_With_Filters();
	})
}