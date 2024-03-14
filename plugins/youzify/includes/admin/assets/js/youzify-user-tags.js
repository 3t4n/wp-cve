( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		/**
		 * Add New Tag.
		 */
		$( document ).on( 'click', '#youzify-add-user-tag' , function( e ) {


			e.preventDefault();

			// Get Data.
			var	name_selector = $( '.youzify-user-tag-name span' ),
				user_tags_form = $( '#youzify-user-tags-form' ),
				fieldName	  = 'youzify_user_tags[youzify_user_tag_' + youzify_nextUTag + ']',
				tag 	  	  = $.youzify_getAddData( user_tags_form, 'youzify_user_tag' ),
				user_tag_args = {
					type	 : 'text',
					value	 : tag['name'],
					tag_name : tag['name'],
					form 	 : user_tags_form,
					selector : name_selector,
				};

			// Validate Data.
			if ( ! $.validate_user_tags_data( user_tag_args ) ) {
				return false;
			}

			// Add Item.
			$( '#youzify_user_tags' ).prepend(
				'<li class="youzify-user-tag-item" data-user-tag-name="youzify_user_tag_'+ youzify_nextUTag +'">'+
				'<h2 class="youzify-user-tag-name">'+
				'<i class="fa youzify-user-tag-icon '+ tag['icon'] +'"></i>'+
				'<span>' + tag['name'] + '</span>'+
				'</h2>' +
				'<input type="hidden" name="' + fieldName +'[icon]" value="' + tag['icon'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[name]" value="' + tag['name'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[field]" value="' + tag['field'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[description]" value="' + tag['description'] + '" >'+
				'<a class="youzify-edit-item youzify-edit-user-tag"></a>' +
				'<a class="youzify-delete-item youzify-delete-user-tag"></a>' +
				'</li>'
			);

			// Hide Modal
			$.youzify_HideModal( user_tags_form );

			// Increase ID Number
			youzify_nextUTag++;

		});

		/**
		 * Edit User Tag.
		 */
		$( document ).on( 'click', '.youzify-edit-user-tag' , function( e )	{

			// Get Data.
			var user_tag_item  = $( this ).closest( '.youzify-user-tag-item' ),
				user_tags_form = $( '#youzify-user-tags-form' );

			// Get Form Values
			$.youzify_EditForm( {
				button_id	: 'youzify-update-user-tag',
				form_title	: Youzify_User_Tags.update_user_tag,
				form 		: user_tags_form,
				item 		: user_tag_item
			});

		});

		/**
		 * Save User Tag.
		 */
		$( document ).on( 'click', '#youzify-update-user-tag' , function( e )	{

			e.preventDefault();

			// Set Up Variables.
			var tag_name = '.youzify-user-tag-name span',
				user_tags_form = $( '#youzify-user-tags-form' ),
				user_tag_item  = $.youzify_getItemObject( user_tags_form ),
				tag = $.youzify_getNewData( user_tags_form, 'keyToVal' ),
				user_tag_args = {
					old_name 	: user_tag_item.find( tag_name ).text(),
					value		: tag['name'],
					form 		: user_tags_form,
					selector 	: $( tag_name ),
					type		: 'text',
					tag_icon   : tag['icon'],
					tag_name   : tag['name'],
					tag_field  : tag['field'],
					tag_description : tag['description'],
				};

			// Validate Tab Data.
			if ( ! $.validate_user_tags_data( user_tag_args ) ) {
				return false;
			}

			// Update Data.
			$.youzify_updateFieldsData( user_tags_form );

		});

		/**
		 * Validate User Tag Data.
		 */
		$.validate_user_tags_data = function( options ) {

			// O = Options
			var o = $.extend( {}, options );

			if ( o.tag_name == null || $.trim( o.tag_name ) == '' ) {
				// Show Error Message
                $.ShowPanelMessage( {
                    msg  : Youzify_User_Tags.utag_name_empty,
                    type : 'error'
                } );
                return false;
			}

			// Check if type Exist or not
			var nameAlreadyeExist = $.youzify_isAlreadyExist( {
				old_title 	: o.old_name,
				selector 	: o.selector,
				value		: o.value,
				type		: 'text'
			});

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
		$( document ).on( 'click', '.youzify-delete-user-tag', function() {

			// Remove item
			$( this ).closest( 'li' ).remove();

			if ( ! $( '.youzify-user-tag-item' )[0] ) {
				$( '#youzify_user_tags' ).append( '<p class="youzify-no-content youzify-no-user-tags">' + Youzify_User_Tags.no_user_tags + '</p>' );
			}

		});

	});

})( jQuery );