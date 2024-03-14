( function( $ ) {

	'use strict';

	$( document ).ready( function() {

	/**
	 * Change Form Inputs Data
	 */
	$.youzify_EditForm = function( options ) {

		// Get Options & Data.
		var o 			= $.extend( {}, options ),
			dt 			= $.youzify_getDataName( o.item ), /*  Get Attribute 'Data'. */
			data 		= $.youzify_getItemByType( { form: o.item, type: 'data' } ),
			close_icon 	= '<i class="fas fa-times youzify-md-close-icon"></i>',
			field_value, is_true;

		// Add Data ID To The Main form.
		o.form.attr( 'data-' + dt['name'], dt['value'] );

		// Change Form Data.
		o.form.find( '.youzify-md-title' ).html( o.form_title + close_icon );
		o.form.find( '.youzify-md-save' ).text( Youzify.save_changes );
		o.form.find( '.youzify-md-save' ).attr( 'id', o.button_id );

		// Change Form Values
		o.form.find( ':input' ).not( '.uk-upload-button,:button,.ukai_tags_field' ).each( function() {

			// Get Data
			var field_name 	= $( this ).attr( 'name' ),
				field_type 	= $( this ).attr( 'type' ),
				real_value 	= $( this ).attr( 'value' ),
				elt 		= $( this ).prop( 'nodeName' ).toLowerCase(),
				field 	   	= $( elt + '[name="'+ field_name + '"]' );

			$.each( data, function( i, value ) {

				if ( ~field_name.indexOf( value['name'] ) ) {

					field_value = value['value'];

					if ( field_type === 'text' || elt === 'textarea' || field_type === 'hidden' || elt === 'select' ) {

						// Get Element Value
						if ( elt === 'input' ) {
							field.val( field_value );
						} else if ( elt === 'textarea' ) {
							field.val( decodeURIComponent( field_value ) );
						} else if ( elt === 'select' ) {
							$( 'select[name='+ field_name + '] option[value="' + field_value + '"]' ).prop( 'selected', true );
						}

						// Live Data Preview
						$.youzify_FormLivePreview( {
							selector: field.parent(),
							name 	: value['name'],
							value 	: field_value
						});

					} else if ( field_type === 'checkbox' ) {
						if ( ~field_name.indexOf( '[]' ) ) {
							var checkbox_values = field_value.split( ',' );
							$.each( checkbox_values, function( i, item_value ) {
								$( 'input[name="' + field_name + '"][value="' + item_value + '"]' ).prop( 'checked', true );
							});
						} else {

							// Get Checkbox Value
							is_true = ( field_value === 'false' ) ? false : true;
							field.prop( 'checked', is_true );

						}
					} else if ( field_type === 'radio' ) {
						// Get Radio Value
						$( 'input[name='+ field_name + '][value=' + field_value + ']' ).trigger( 'click' );
					}

				}
			});
		});
	}

	/**
	 * Update Fields.
	 */
	$.youzify_updateFieldsData = function( form, form_type ) {

		// Get form type.
		var form_type = typeof form_type !== 'undefined' ? form_type : null;

		// Get Data.
		var id 			= $.youzify_getDataName( form ),
			form_data 	= $.youzify_getNewData( form ),
			item 		= $.youzify_getItemObject( form ),
			field_name  = form.find( '.youzify-keys-name' ).attr( 'value' );

		// Change Input Values.
		$.each( form_data, function( i, v ) {
			// Encode Custom Widgets Text Area.
			if (
				( 'youzify_ads' == field_name && 'code' == v['key'] )
				||
				( 'youzify_custom_tabs' == field_name && 'content' == v['key'] )
				||
				( 'youzify_custom_widgets' == field_name && 'content' == v['key'] )
			) {
				v['value'] = encodeURIComponent( v['value'] );
			}

			// Set New Data.
			$( 'input[name="' + field_name  + '[' + id['value'] + '][' + v['key'] + ']"]' ).val( v['value'] );
		});

		// Live Data Preview
		$.youzify_ItemLivePreview( form );

		// Hide Modal
		$.youzify_HideModal( form );

	}

	/**
	 * Clear Form Data.
	 */
	$.youzify_ResetForm = function( form, form_type ) {

		// Get form type.
		var form_type = typeof form_type !== 'undefined' ? form_type : null;

		// Get Data.
		var field_names = $.youzify_getFieldsNames( form, 'namesOnly' ),
			close_icon 	= '<i class="fas fa-times youzify-md-close-icon"></i>', type;

		// Change Form Data Again.
		form.find( '.youzify-md-save' ).text( Youzify.done );
		form.find( '.youzify-md-title' ).html( form.find( '.youzify-md-title' ).data( 'title' ) + close_icon );
		form.find( '.youzify-md-save' ).attr( 'id', form.find( '.youzify-md-save' ).data( 'add' ) );

		// Change Input Values.
		$.each( field_names, function( i, field ) {
			type = field['type'];
			if ( type === 'text' ) {
				$( 'input[name=' + field['name'] + ']' ).val( '' );
			} else if ( type === 'textarea' ) {
				$( 'textarea[name=' + field['name'] + ']' ).val( '' );
			} else if ( type === 'radio' ) {
				$( 'input:radio[name=' + field['name'] + ']:first' ).trigger( 'click' );
			} else if ( type === 'select' ) {
				$( 'select[name=' + field['name'] + '] option:first' ).prop('selected', true);
			}

		});

		// Show Banner Default Image.
		if ( form_type === 'ads' ) {
			// Reset Ads Form.
			$( 'input[name=youzify_ad_is_sponsored]' ).attr( 'checked', false );
			$( 'input[name=youzify_ad_type][value=banner]' ).trigger( 'click' );
			form.find( '.uk-photo-preview' ).css( 'backgroundImage', 'url(' + Youzify.default_img + ')' );
		} else if ( form_type === 'reactions' ) {
			// Reset Reactions Form.
			$( 'input[name=youzify_emoji_visibility]' ).attr( 'checked', true );
			form.find( '.uk-photo-preview' ).css( 'backgroundImage', 'url(' + Youzify.default_img + ')' );
		} else if ( form_type === 'networks' ) {
			// Reset Networks Form.
			form.find( '.ukai-selected-icon' ).val( 'fas fa-share-alt' );
			form.find( '.wp-color-result' ).css( { 'background-color' : '' } );
			form.find( '.ukai_icon_selector > i' ).removeClass().addClass( 'fas fa-share-alt' );
		} else if ( form_type === 'custom-widgets' ) {
			// Reset Widgets Form.
			form.find( '.ukai-selected-icon' ).val( 'fas fa-globe-asia' );
			form.find( '.ukai_icon_selector > i' ).removeClass().addClass( 'fas fa-globe-asia' );
		} else if ( form_type === 'custom-tabs' ) {
			// Reset Custom Tabs Form.
			$( 'input[name=youzify_tab_display_sidebar]' ).attr( 'checked', true );
			$( 'input[name=youzify_tab_display_nonloggedin]' ).attr( 'checked', true );
			$( 'input[name=youzify_tab_type][value=link]' ).trigger( 'click' );
		} else if ( form_type === 'member-types' ) {
			// Reset Member Types Form.
			form.find( '.wp-color-result' ).css( { 'background-color' : ''} );
			$( 'input[name=youzify_member_type_active]' ).attr( 'checked', true );
			$( 'input[name="youzify_member_type_roles[]"]' ).attr( 'checked', false );
			$( 'input[name=youzify_member_type_register]' ).attr( 'checked', true );
			$( 'input[name=youzify_member_type_show_in_md]' ).attr( 'checked', true );
			form.find( '.ukai-selected-icon' ).val( 'fas fa-user' );
			form.find( '.ukai_icon_selector > i' ).removeClass().addClass( 'fas fa-user' );
		} else if ( form_type === 'user-tags' ) {
			// Reset Member Types Form.
			form.find( '.ukai-selected-icon' ).val( 'fas fa-globe-asia' );
			form.find( '.ukai_icon_selector > i' ).removeClass().addClass( 'fas fa-globe-asia' );
		} else if ( form_type == 'yzbpv-premium-types' ) {
			$( 'input[name="roles[]"]' ).prop( 'checked', false );
			form.find( '.wp-color-result' ).css( { 'background-color' : '' } );
			form.find( '.uk-photo-preview' ).css( 'backgroundImage', 'url(' + Youzify.default_img + ')' );
		}

		// Check Item List
		$.youzify_CheckItemsList( form_type );

	}

	/**
	 * Get Form Inputs Data
	 */
	$.youzify_getNewData = function( form, type ) {

		// Get form type.
		var type = typeof type !== 'undefined' ? type : null;

		// Get Options
		var item 	= $.youzify_getItemObject( form ),
			names 	= $.youzify_getFieldsNames( form ),
			keys  	= $.youzify_getItemByType( { form: item, type: 'keys' } ),
			data 	= [];

		// Get new Values From Form
		$.each( names, function( i, elt ) {
			$.each( keys, function( i, key ) {
				if ( elt['name'].indexOf( key ) >= 0 ) {
					if ( type === 'keyToVal' ) {
						data[ key ] = elt['value'];
					} else {
						data.push( { key: key, value: elt['value'] } );
					}
				}
			});
		});

		// Return the new data.
		return data;
	}

	$.isDataEmpty = function( form ) {

		// Declare Variables.
		var data = [],
			field_value,
			is_empty = false,
			input_names = $.youzify_getFieldsNames( form );

		// Check if values are empty
		$.each( input_names, function( i, field ) {
			field_value = field['value'];
			if ( field_value == null || $.trim( field_value ) == '' ) {
				// Show Error Message
                $.ShowPanelMessage( {
                    msg  : Youzify.required_fields,
                    type : 'error'
                });
                is_empty = true;
			}
		});

		if ( is_empty ) {
			return true;
		} else {
			return false;
		}
	}

	$.youzify_getAddData = function( form_data, field_id ) {

		// Get Data.
		var field_name,
			data = [],
			input_names = $.youzify_getFieldsNames( form_data );

		// Get Values
		$.each( input_names, function( i, field ) {
			if ( field['name'] != undefined ) {
				field_name = field['name'].replace( field_id + '_', '' );
				data[ field_name ] = field['value'];
			}
		});

		// Return Data
		return data;
	}

	/**
	 * Get Form Input Names.
	 */
	$.youzify_getFieldsNames = function( form, form_type ) {

		// Get form type.
		var form_type = typeof form_type !== 'undefined' ? form_type : null;

		// o = options.
		var input_names = [],
			temp_names 	= [],
			name, type, value;

		// Get Form Input Names.
		form.find( ':input' )
		.not( '.uk-upload-button, :button, .youzify-hidden-input, .ukai_tags_field' )
		.each( function() {

			// Get Data
    		name = $( this ).attr( 'name' );
    		type = $( this ).attr( 'type' );

    		// Get Input Type
    		if ( type == undefined ) {
    			type = $( this ).prop( 'nodeName' ).toLowerCase();
    		}

    		// Get Value
    		if ( type === 'checkbox' ) {

    			if ( ~name.indexOf( '[]' ) ) {
					value = $( 'input[name="' + name + '"]:checked' ).map( function( ) {
						return  this.value;
					}).get().join( ',' );
    			} else {
					value = $( 'input[name="' + name + '"]:checked' ).length > 0;
    			}

    		} else if ( type === 'radio' ) {
				value = $( 'input[name=' + name + ']:checked' ).val();
    		} else if ( type === 'select' ) {
    			value = $( this ).val();
    		} else if ( type === 'textarea' ) {
    			value = $( this ).val();
    		} else {
    			var field_values = $( "input[name='" + name + "']" ).map( function() {
					return $( this ).val();
				} ).get();
    			value = field_values.join( ',' );
    		}

			if ( temp_names.indexOf( name ) == -1 ) {
				temp_names.push( name );
				if ( form_type == 'keyToVal' ){
					input_names[ name ] = value;
				} else {
					input_names.push( { name :name, type: type, value: value } );
				}
			}

		});

		// Return List of Input Names.
		return input_names;

	}

	/**
	 * Get Item Data / Keys
	 */
	$.youzify_getItemByType = function( options ) {

		var opts = $.extend( {}, options ),
			data = [], keys = [],
			name, value, i, input_name, widget_name;

		opts.form.find( '> input' ).each( function() {

			// Get Data.
			name  = $( this ).attr( 'name' );
			value = $( this ).attr( 'value' );
			i 	  = 0;

			// Get Input Keys.
			name.replace( /\[.+?\]/g, function( match ) {

	    		i++;

	    		// Get Input Name.
	    		input_name = match.slice( 1, -1 );

	    		// Get widget_name.
	    		if (
	    			i == 1 &&
	    			input_name.indexOf( 'youzify_cwg' ) !== -1  &&
	    			input_name.indexOf( 'fields' ) !== -1
	    		) {
	    			value = input_name;
	    		}

	    		if ( i == 2 ) {
	    			// Change Input Name if name = fields to widget
	    			if ( input_name === 'fields' ) {
	    				input_name = 'widget';
	    			}
	    			// Fill Arrays
	    			keys.push( input_name );
	    			data.push( { name: input_name, value: value } );
	    		}

	    	});

		});

		// Return Data Or Keys.
		if ( opts.type === 'keys' ) {
			return keys;
		} else {
			return data;
		}

	}

	/**
	 * Get Item Live Preview
	 */
	$.youzify_FormLivePreview = function( options ) {

		// o = Options
		var o = $.extend( {}, options );
		// Live Preview.
		if ( o.name === 'banner' || o.name === 'image'  || o.name === 'image_url' ) {
			o.selector.next( '.uk-photo-preview' )
			.css( 'backgroundImage', 'url(' + o.value + ')' );
		} else if ( o.name === 'color' ||  o.name === 'button_bg_color' || o.name === 'button_text_color' || o.name == 'left_color' || o.name == 'right_color' ) {
			o.selector.closest( '.wp-picker-input-wrap' ).prev( '.wp-color-result' )
			.css( { 'background-color' : o.value } );
		} else if ( o.name === 'icon' ) {
			o.selector.find( '.ukai_icon_selector > i' )
			.removeClass().addClass( o.value );
			o.selector.find( '.ukai-selected-icon' ).val( o.value );
		}

	}

	/**
	 * Items Live Preview.
	 */
	$.youzify_ItemLivePreview = function( form ) {

		// Set Up Variables.
		var data = $.youzify_getNewData( form, 'keyToVal' ),
			item = $.youzify_getItemObject( form ),
			form_type = form.attr( 'id' );

		// Live Preview.
		if ( form_type === 'youzify-ads-form' ) {

			item.find( '.youzify-ad-title' ).text( data['title'] );

			if ( data['type'] === 'adsense' ) {
				item.find( '.youzify-ad-img' ).attr( 'style', '' );
				item.find( '.youzify-ad-img i' ).show();
			} else {
				item.find( '.youzify-ad-img' ).css( 'backgroundImage', 'url(' + data['banner'] + ')' );
				item.find( '.youzify-ad-img i' ).hide();
			}

		} else if ( form_type === 'youzify-reactions-form' ) {

			item.find( '.youzify-emoji-title' ).text( data['title'] );
			item.find( '.youzify-emoji-img' ).css( 'backgroundImage', 'url(' + data['image'] + ')' );

		} else if ( form_type === 'youzify-networks-form' || form_type === 'youzify-custom-widgets-form' ) {
			if ( form_type === 'youzify-networks-form' ) {
				item.find( 'h2' ).css( { 'border-color' : data['color'] } );
			}
			item.find( 'h2 span' ).text( data['name'] );
			item.find( 'h2 i' ).removeClass().addClass( 'fab youzify-network-icon ' + data['icon'] );
		} else if ( form_type === 'youzify-custom-tabs-form' ) {
			item.find( 'h2 span' ).text( data['title'] );
		} else if ( form_type === 'youzify-member-types-form' ) {
			item.find( 'h2 span' ).text( data['name'] );
			item.find( 'h2 i' ).removeClass().addClass( 'fab youzify-member-type-icon ' + data['icon'] );
		} else if ( form_type === 'youzify-user-tags-form' ) {
			item.find( 'h2 span' ).text( data['name'] );
			item.find( 'h2 i' ).removeClass().addClass( 'fab youzify-user-tag-icon ' + data['icon'] );
		} else if ( form_type === 'yzbpv-premium-types-form' ) {
			item.find( 'h2 span' ).text( data['name'] );
				item.find( '.youzify-type-image' ).css( 'backgroundImage', 'url(' + data['image_url'] + ')' );

		}

	}

	/**
	 * Get Data Name
	 */
	$.youzify_getDataName =  function( form ) {
		var data = [];
		$.each( form.data(), function( i, val ) {
			if ( i != "sortableItem" ) {
				data['name']  = i.replace(/([A-Z])/g, '-$1' ).trim().toLowerCase();
				data['value'] = form.attr( 'data-' + data['name'] );
			}
		});
		return data;
	}

	/**
	 * Get Item Element.
	 */
	$.youzify_getItemObject = function( form ) {
		var data = $.youzify_getDataName( form ),
			item = $( 'li[data-' + data['name'] + '=' + data['value'] + ']' );
		return item;
	}

	/**
	 * Check for Widget Existence.
	 */
	$.youzify_CheckItemsList = function( item ) {

		// Check Ads List
		if ( item === 'ads' ) {
			if ( $( '.youzify-ad-item' )[0] ) {
				$( '.youzify-no-ads' ).remove();
			}
		}

		// Check Reactions List
		if ( item === 'reactions' ) {
			if ( $( '.youzify-emoji-item' )[0] ) {
				$( '.youzify-no-emojis' ).remove();
			}
		}

		// Check Networks List
		if ( item === 'networks' ) {
			if ( $( '.youzify-network-item' )[0] ) {
				$( '.youzify-no-networks' ).remove();
			}
		}

		// Check Networks List
		if ( item === 'custom-widgets' ) {
			if ( $( '.youzify-custom-widget-item' )[0] ) {
				$( '.youzify-no-custom-widgets' ).remove();
			}
		}

		// Check Networks List
		if ( item === 'custom-tabs' ) {
			if ( $( '.youzify-custom-tab-item' )[0] ) {
				$( '.youzify-no-custom-tabs' ).remove();
			}
		}

		// Check Member Types List
		if ( item === 'member-types' ) {
			if ( $( '.youzify-member-type-item' )[0] ) {
				$( '.youzify-no-member-types' ).remove();
			}
		}

		// Check User Tags List
		if ( item === 'user-tags' ) {
			if ( $( '.youzify-user-tag-item' )[0] ) {
				$( '.youzify-no-user-tags' ).remove();
			}
		}

		// Check User Tags List
		if ( item === 'yzbpv-premium-types' ) {
			if ( $( '.youzify-type-item' )[0] ) {
				$( '.youzify-no-types' ).remove();
			}
		}

	}

	/**
	 * Show or Hide Options Field
	 */
	$.youzify_CheckFieldOptions = function() {

		$( '#youzify_field_type' ).on( 'change', function() {
			// Get Data.
			var field_type = $( this ).val(),
				options    = $( '.youzify-field-options' );
			// Display / Hide Options
			if ( field_type === 'text' || field_type === 'number' || field_type === 'textarea' ) {
				options.fadeOut();
			} else {
				options.fadeIn();
			}
		});

	}

	/**
	 * Prevent Submitting form by Hitting Enter.
	 */
	$( 'input' ).keypress( function( e ) {
		var keyCode = e.keyCode || e.which;
		  if ( keyCode === 13 ) {
		    e.preventDefault();
		    return false;
		 }
	});

	/**
	 * Make Widgets Draggable
	 */
	$( '#youzify_widgets, #youzify_networks, #youzify_custom_tabs, #youzify_user_tags, #youzify_member_types, #youzify_reactions' ).sortable({
		placeholder: "dashed-placeholder"
	});

	/**
	 * Make Fields Draggable
	 */
	$.MakeItemsSortable = function() {
		$( '.youzify-fields-content' ).sortable( {
			placeholder: 'ui-state-highlight',
			connectWith: '.youzify-fields-content',
			receive : function( event, ui ) {
				var widgte_data  = $( this ).parent().data( 'widgetName' ),
					field_id 	 = ui.item.context.attributes[1].nodeValue,
					widgte_class = $( this ).parent();

				widgte_class.find( "input[value='" + field_id + "']" ).remove();
				widgte_class.find( '.youzify-field-item' ).append(
					'<input type="hidden" name="youzify_widgets['+ widgte_data +'][fields][]" value="'+ field_id +'">'
				)
	        }
		});
	}

	$.MakeItemsSortable();

	/**
	 * Modal.
	 */
	$( document ).on( 'click', '.youzify-md-trigger' , function( e ) {

		e.preventDefault();

		// Get Button
		var button_id = '#' + $( this ).data( 'modal' );

	    // Display Modal
		$( '.youzify-md-overlay' ).fadeIn( 500, function() {
			$( button_id ).addClass( 'youzify-md-show' );
		});

	});

	/**
	 * Hide Modal if user clicked Close Button or Icon
	 */
	$( document ).on( 'click', '.youzify-md-close, .youzify-md-close-icon' , function( e ) {

		e.preventDefault();

		// Get Data.
		var modal = $( this ).closest( '.youzify-md-modal' );
		$.youzify_HideModal( modal );

	});

	// Hide Modal If User Clicked Escape Button
	$( document ).keyup( function( e ) {
		if ( $( '.youzify-md-show' )[0] ) {
		    if ( e.keyCode === 27 ) {
			    $( '.youzify-md-close' ).trigger( 'click' );
		    }
		}
		return false;
	});

	// # Hide Modal if User Clicked Outside
	$( document ).mouseup( function( e ) {
	    if ( $( '.youzify-md-overlay' ).is( e.target ) && $( '.youzify-md-show' )[0] ) {
			$( '.youzify-md-close' ).trigger( 'click' );
	    }
	    return false;
	});

	$.youzify_HideModal = function( form ) {

		// Get Form ID.
		var form_id = form.attr( 'id' );

		// Hide Form.
		$( '.youzify-md-modal' ).removeClass( 'youzify-md-show' );
        $( '.youzify-md-overlay' ).fadeOut( 600, function() {

        	// Reset Form.
	        if ( form_id === 'youzify-networks-form' ) {
				$.youzify_ResetForm( form, 'networks' );
			} else if ( form_id === 'youzify-ads-form' ) {
				$.youzify_ResetForm( form, 'ads' );
			}  else if ( form_id === 'youzify-reactions-form' ) {
				$.youzify_ResetForm( form, 'reactions' );
			} else if ( form_id === 'youzify-custom-widgets-form' ) {
				$.youzify_ResetForm( form, 'custom-widgets' );
			} else if ( form_id === 'youzify-custom-tabs-form' ) {
				$.youzify_ResetForm( form, 'custom-tabs' );
			} else if ( form_id === 'youzify-member-types-form' ) {
				$.youzify_ResetForm( form, 'member-types' );
			} else if ( form_id === 'youzify-user-tags-form' ) {
				$.youzify_ResetForm( form, 'user-tags' );
			} else if ( form_id === 'yzbpv-premium-types-form' ) {
				$.youzify_ResetForm( form, 'yzbpv-premium-types' );
			}

        });

	}

	/**
	 * Display Edit Modal
	 */
	$( document ).on( 'click', '.youzify-edit-item', function() {

		var modal;

		if ( $( this ).hasClass( 'youzify-edit-field' ) ) {
			modal = $( 'button[data-modal=youzify-fields-form]' );
		} else if ( $( this ).hasClass( 'youzify-edit-widget' ) ) {
			modal = $( 'button[data-modal=youzify-widgets-form]' );
		} else {
			modal = $( this ).closest( 'ul' ).prev( '.youzify-custom-section' ).find( '.youzify-md-trigger' );
		}

		// Display Modal
		modal.trigger( 'click' );
	});

	/**
	 * Live Scheme Preview
	 */
	$( document ).on( 'click', '.uk-panel-scheme .imgSelect label' , function( e ) {
		var panel_scheme = $( this ).prev().val();
		$( '#ukai-panel' ).removeClass().addClass( 'ukai-panel' ).addClass( panel_scheme );
	});

	});

})( jQuery );