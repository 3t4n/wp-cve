/**
* Plugin Name: PE Panels
* Author: artur.kaczmarek@pixelemu.com
* Version: 1.09
*/

(function($) {

	"use strict";

	$( document ).ready(function() {

		function pe_panels_admin( widget ) {

			var tabWidget = widget.find('.pe-panels-widget-container').length; //just to make sure

			if ( !widget.length && tabWidget ) return;

			$( widget ).each(function(i,e) {

				//tabs or accordion options
				if( $(e).hasClass('tabs-view') ) {
					$(e).find('.acco').hide();
				}

				if( $(e).hasClass('acco-view') ) {
					$(e).find('.tabs').hide();
				}

				$(e).find('.input-tabs').on('click', function() {
					$(e).removeClass('acco-view');
					$(e).addClass('tabs-view');
					$(e).find('.acco').hide();
					$(e).find('.tabs').show();
				});

				$(e).find('.input-acco').on('click', function() {
					$(e).removeClass('tabs-view');
					$(e).addClass('acco-view');
					$(e).find('.tabs').hide();
					$(e).find('.acco').show();
				});

				//post thumbnail options
				var showThumbnail = $(e).find('.pe-panels-show-thumbnail select');
				var showThumbnailSelected = showThumbnail.find('option:selected');

				var thumbnailAlign = $(e).find('.pe-panels-img-align');
				var thumbnailSize = $(e).find('.pe-panels-img-size');

				var imgSizeField = $(e).find('.pe-panels-img-size select');
				var imgWidthField = $(e).find('.pe-panels-img-width');
				var imgHeightField = $(e).find('.pe-panels-img-height');
				var imgCropField = $(e).find('.pe-panels-img-crop');

				var selectedOption = imgSizeField.find('option:selected');

				if( showThumbnailSelected.val() == '0' ) {
					thumbnailAlign.hide();
					thumbnailSize.hide();
					imgWidthField.hide();
					imgHeightField.hide();
					imgCropField.hide();
				}

				showThumbnail.change(function() {
					if( $(this).val() == '1') {
						thumbnailAlign.show();
						thumbnailSize.show();
					} else {
						thumbnailAlign.hide();
						thumbnailSize.hide();
						imgWidthField.hide();
						imgHeightField.hide();
						imgCropField.hide();
					}
				} );

				if( selectedOption.val() != 'pe_panels_custom_size' ) {
					imgWidthField.hide();
					imgHeightField.hide();
					imgCropField.hide();
				}

				imgSizeField.change(function() {
					if( $(this).val() == 'pe_panels_custom_size') {
						imgWidthField.show();
						imgHeightField.show();
						imgCropField.show();
					} else {
						imgWidthField.hide();
						imgHeightField.hide();
						imgCropField.hide();
					}
				} );

				//checkbox
				var wrapper = $(e).find('.pe-panels-id-list li');
				var orderField = $(e).find('.pe-panels-order-result input.order');
				var orderValue = '';
				var chValue = '';
				var orderArray;
				var orderString = '';

				orderValue = orderField.val(); //get current field value (string)

				//console.log('Order value (onload) : ' + orderValue);

					wrapper.find('input[type="checkbox"]').change(function() {

						//console.log('--- click ---');

						if( orderValue.length ) {
							orderArray = orderValue.split(',').map(function(v) {
								if( parseInt(v) ) { //only int values
									return v;
								}
							});
						} else {
							orderArray = [];
						}

						chValue = parseInt($(this).val()); //get current checkbox value

						//check if clicked checkbox is already in array
						var is_in_array = false;
						$.map( orderArray, function(element, index) {
						 if ( element == chValue ) {
							 is_in_array = true;
							 //console.log('value is in array: ' + element);
						 }
						} );

						if( is_in_array ) { // REMOVE

							//console.log('Current order value: ' + orderValue);
							//console.log('Remove value: ' + chValue);

							orderArray = orderArray.filter(function(v){ return v != chValue}); //remove selected value
							orderString = orderArray.join(',');

							orderField.val(orderString); //set value to order field
							orderValue = orderField.val(); //update value var

							//console.log('Order value after update: ' + orderValue);

						} else { // ADD

							//console.log('Current order value: ' + orderValue);
							//console.log('Add value: ' + chValue);

							orderArray.push(chValue); //add value to array
							orderString = orderArray.join(',');


							orderField.val(orderString); //set value to order field
							orderValue = orderField.val(); //update value var

							//console.log('Order value after update: ' + orderValue);

						}

					});

			});

		}
		

		$( document ).on( 'widget-added widget-updated', function ( e, widget ) {
			if( widget.find('.pe-panels-widget-container').length ) {
				pe_panels_admin(widget);
			}
		});
	
		$( document ).on( 'click', '.widgets-holder-wrap .widget > .widget-top', function ( e ) {
			var widget = $(this).parent();
			if( widget.find('.pe-panels-widget-container').length ) {
				pe_panels_admin(widget);
			}
		});

	});

})(jQuery);
