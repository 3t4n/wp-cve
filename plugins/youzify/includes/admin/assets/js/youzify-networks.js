( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		/**
		 * Add New Network.
		 */
		$( document ).on( 'click', '#youzify-add-network' , function( e ) {

			e.preventDefault();

			// Get Data.
			var	name_selector = $( '.youzify-network-name span' ),
				networks_form = $( '#youzify-networks-form' ),
				fieldName	  = 'youzify_networks[youzify_sn_' + youzify_nextSN + ']',
				network 	  = $.youzify_getAddData( networks_form, 'youzify_network' ),
				network_args  = {
					value	: network['name'],
					form 	: networks_form,
					selector: name_selector,
					type	: 'text'
				};

			// Validate Network Data
			if ( ! $.validate_networks_data( network_args ) ) {
				return false;
			}

			// Add Network item
			$( '#youzify_networks' ).prepend(
				'<li class="youzify-network-item" data-network-name="youzify_sn_'+ youzify_nextSN +'">'+
				'<h2 class="youzify-network-name" style="border-color:' + network['color'] + ';">'+
				'<i class="youzify-network-icon '+ network['icon'] +'"></i>'+
				'<span>' + network['name'] + '</span>'+
				'</h2>' +
				'<input type="hidden" name="' + fieldName +'[name]" value="' + network['name'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[icon]" value="' + network['icon'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[color]" value="' + network['color'] + '" >'+
				'<a class="youzify-edit-item youzify-edit-network"></a>'+
				'<a class="youzify-delete-item youzify-delete-network"></a>'+
				'</li>'
			);

			// Hide Modal
			$.youzify_HideModal( networks_form );

			// Increase Social Network Number
			youzify_nextSN++;

		});

		/**
		 * Edit Network.
		 */
		$( document ).on( 'click', '.youzify-edit-network' , function( e )	{

			// Get Data.
			var network_item  = $( this ).closest( '.youzify-network-item' ),
				networks_form = $( '#youzify-networks-form' );

			// Get Form Values
			$.youzify_EditForm( {
				button_id	: 'youzify-update-network',
				form_title	: Youzify_Networks.update_network,
				form 		: networks_form,
				item 		: network_item
			});

		});

		/**
		 * Save Network.
		 */
		$( document ).on( 'click', '#youzify-update-network' , function( e )	{

			e.preventDefault();

			// Set Up Variables.
			var network_name 	= '.youzify-network-name span',
				networks_form 	= $( '#youzify-networks-form' ),
				network_item 	= $.youzify_getItemObject( networks_form ),
				network			= $.youzify_getNewData( networks_form, 'keyToVal' ),
				networks_args	= {
					old_title 	: network_item.find( network_name ).text(),
					value		: network['name'],
					form 		: networks_form,
					selector 	: $( network_name ),
					type		: 'text'
				};

			// Validate Network Data
			if ( ! $.validate_networks_data( networks_args ) ) {
				return false;
			}

			// Update Data
			$.youzify_updateFieldsData( networks_form );

		});

		/**
		 * Validate Network Data.
		 */
		$.validate_networks_data = function( options ) {

			// O = Options
			var o = $.extend( {}, options );

			// Check if Data is Empty.
			if ( $.isDataEmpty( o.form ) ) {
				return false;
			}

			// Check if widget Exist or not
			var nameAlreadyeExist = $.youzify_isAlreadyExist( {
				old_title 	: o.old_title,
				selector 	: o.selector,
				value		: o.value,
				type		: 'text'
			} );

			if ( nameAlreadyeExist ) {
				// Show Error Message
                $.ShowPanelMessage( {
                    msg  : Youzify.name_exist,
                    type : 'error'
                });
                return false;
			}

			return true;
		}

		/**
		 * Remove Item.
		 */
		$( document ).on( 'click', '.youzify-delete-network', function() {

			// Remove item
			$( this ).closest( 'li' ).remove();

			if ( ! $( '.youzify-network-item' )[0] ) {
				$( '#youzify_networks' )
				.append( '<p class="youzify-no-content youzify-no-networks">' + Youzify_Networks.no_networks + '</p>' );
			}

		});

	});

})( jQuery );