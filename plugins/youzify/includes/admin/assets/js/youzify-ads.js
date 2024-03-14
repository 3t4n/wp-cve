( function( $ ) {

	'use strict';

	$( document ).ready( function () {

		/**
		 * Add New Ad.
		 */
		$( document ).on( 'click', '#youzify-add-ad' , function( e )	{

			e.preventDefault();

			// Get Data.
			var	ads_form = $( '#youzify-ads-form' ),
				data 	 = $.youzify_getAddData( ads_form, 'youzify_ad' ),
				ad_args	 = {
					selector 	: $( '.youzify-ad-title' ),
					AD_banner 	: data['banner'],
					AD_title 	: data['title'],
					AD_type 	: data['type'],
					AD_code 	: data['code'],
					AD_url 		: data['url']
				};

			// Validate AD Data
			if ( ! $.validate_ad_data( ad_args ) ) {
				return false;
			}

			// Prepare item Data.
			var fieldName = 'youzify_ads[youzify_ad_' + youzify_nextAD + ']',
				show_icon = ( data['type'] == 'adsense' ) ? 'youzify_show_icon' : 'youzify_hide_icon',
				bannerImg = ( data['type'] == 'banner' ) ? "style='background-image: url( " + data['banner'] + " );'" : '';

			// Add Widget item
			$( '#youzify_ads' ).prepend(
				'<li class="youzify-ad-item" data-ad-name="youzify_ad_'+ youzify_nextAD +'">' +
                    '<div class="youzify-ad-img ' + show_icon + '" ' + bannerImg + '>' +
                    '<i class="fas fa-code"></i>' + '</div>' +
                    '<div class="youzify-ad-data">' +
                        '<h2 class="youzify-ad-title">' + data['title'] + '</h2>' +
                        '<div class="youzify-ad-actions">' +
                        	'<a class="youzify-edit-item youzify-edit-ad"></a>' +
                        	'<a class="youzify-delete-item youzify-delete-ad"></a>' +
                        '</div>' +
                    '</div>' +
                    '<input type="hidden" name="' + fieldName + '[title]" value="' + data['title'] + '">' +
                    '<input type="hidden" name="' + fieldName + '[is_sponsored]" value="' + data['is_sponsored']  + '">' +
                    '<input type="hidden" name="' + fieldName + '[url]" value="' + data['url'] + '">' +
                    '<input type="hidden" name="' + fieldName + '[type]" value="' + data['type'] + '">' +
                    '<input type="hidden" name="' + fieldName + '[code]" value="' + encodeURIComponent( data['code'] ) + '">' +
                    '<input type="hidden" name="' + fieldName + '[banner]" value="' + data['banner'] + '">' +
                '</li>'
			);

			// Hide Modal
			$.youzify_HideModal( ads_form );

			// Increase Ad Number.
			youzify_nextAD++;

		});

		/**
		 * Edit AD Form.
		 */
		$( document ).on( 'click', '.youzify-edit-ad', function() {

			// Get Data.
			var ad_item = $( this ).closest( '.youzify-ad-item' );

			// Get Form Values
			$.youzify_EditForm( {
				item		: ad_item,
				form_title	: Youzify_Ads.update_ad,
				button_id	: 'youzify-update-ad',
				form 		: $( '#youzify-ads-form' )
			} );

			// CallBack Functions
			$.enable_live_preview();

		});

		/**
		 * Update Ad Data.
		 */
		$( document ).on( 'click', '#youzify-update-ad', function( e ) {

			e.preventDefault();

			// Declare Variables.
			var ads_form = $( '#youzify-ads-form' ),
				ad_item  = $.youzify_getItemObject( ads_form ),
				ad_data  = $.youzify_getNewData( ads_form, 'keyToVal' ),
				ad_args  = {
					old_title 	: ad_item.find( '.youzify-ad-title' ).text(),
					selector 	: $( '.youzify-ad-title' ),
					AD_banner 	: ad_data['banner'],
					AD_title 	: ad_data['title'],
					AD_code 	: ad_data['code'],
					AD_type 	: ad_data['type'],
					AD_url 		: ad_data['url']
				};

			// Validate AD Data
			if ( ! $.validate_ad_data( ad_args ) ) {
				return false;
			}

			// Update Data
			$.youzify_updateFieldsData( ads_form );

		});

		/**
		 * Get fields by AD type .
		 */
		$( document ).on( 'change', 'input[name=youzify_ad_type]', function() {

			var code 	= '.youzify-adcode-item',
				banner 	= '.youzify-adbanner-items',
				form 	= $( this ).closest( '.youzify-ads-form' );

	        if ( this.value == 'adsense' ) {
	        	form.find( banner ).fadeToggle( 400, function() {
	        		form.find( code ).fadeToggle( 400);
	        	} );
	        } else {
	        	form.find( code ).fadeToggle( 400, function() {
	        		form.find( banner ).fadeToggle( 400);
	        	} );
        	}

    	});

		/**
		 * Validate AD Data .
		 */
		$.validate_ad_data = function( options ) {

			// o = Options .
			var o = $.extend( {}, options ),
				titleAlreadyeExist = $.youzify_isAlreadyExist( {
					selector: o.selector,
					value: o.AD_title,
					old_title: o.old_title,
					type: 'text'
				} );

			if (  ! o.AD_title || titleAlreadyeExist ) {
				// Show Error Message
                $.ShowPanelMessage( {
                    msg  : Youzify_Ads.empty_ad,
                    type : 'error'
                } );
                return false;
			}

			// Validate Banner Process.
			if ( o.AD_type == 'banner' ) {
				if ( ! youzify_validateBanner( o.AD_banner ) ) {
					return false;
				}
			} else if ( o.AD_type == 'adsense' ) {
				if ( o.AD_code == null || $.trim( o.AD_code ) == '' ) {
					// Show Error Message
					$.ShowPanelMessage( {
						msg  : Youzify_Ads.code_empty,
						type : 'error'
					} );
					return false;
				}
			}

			return true;
		}

		/**
		 * Validate Banner .
		 */
		function youzify_validateBanner( AD_banner ) {

			// Validate Banner Image
			if ( ! AD_banner ) {
				// Show Error Message
                $.ShowPanelMessage( {
                    msg  : Youzify_Ads.empty_banner,
                    type : 'error'
                } );
                return false;
			}

			// Checl if Banner Exist
			// if ( ! $.youzify_isImgExist( AD_banner, 'banner' ) ) {
			// 	return false;
			// }

			return true;
		}

		/**
		 * Remove Item.
		 */
		$( document ).on( 'click', '.youzify-delete-ad', function() {

			// Remove item
			$( this ).closest( 'li' ).remove();

			if ( ! $( '.youzify-ad-item' )[0] ) {
				$( '#youzify_ads' )
				.append( '<p class="youzify-no-content youzify-no-ads">' + Youzify_Ads.no_ads + '</p>' );
			}

		});

	});

})( jQuery );