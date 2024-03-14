/**
* Backend JS file
*
*/

var WRSL_Backend = new function() {

	var t = this,
		media_frame;

	/* Buttons
	---------------------------------------------------------- */	

	t.loadingBtn = function( event ) {
		jQuery(this).html( WRSLB_AJAX.loadingBtn );
	}

	/* Trigger main modal
	---------------------------------------------------------- */	
	t.triggerModal = function( event ) {

		event.preventDefault();

		var modal = jQuery("#wrslb-main-modal");

		openModal( modal );
	}

	t.closeModal = function( event ) {

		event.preventDefault();
		var modal = jQuery("#wrslb-main-modal");
		closeModal( modal );
	}

	t.openSelectedModal = function( event ) {
		
		event.preventDefault();
		var btn = jQuery(this),
			modal_id = btn.attr("data-modal-id"),
			modal_type = btn.attr("data-modal-type"),
			modal = jQuery( modal_id );

		if ( modal_type !== undefined ) {
			if ( modal_type == 'small' ) {
				openSmallModal( modal );
			} else {
				openModal( modal );
			}
		} else { 
			openModal( modal );
		}
	}

	t.closeSelectedModal = function( event ) {
		
		event.preventDefault();
		var btn = jQuery(this),
			modal_id = btn.attr("data-modal-id"),
			modal = jQuery( modal_id );

		closeModal( modal );
	}

	var closeModal = function( modal ) {
		jQuery.magnificPopup.close({
		  	items: {
		    	src: modal,
		    	type: 'inline',
		  	},
		});
	}

	var openModal = function( modal ) {

		modal.removeClass(".mfp-hide");
		jQuery.magnificPopup.open({
		  	items: {
		    	src: modal,
		    	type: 'inline',
		  	},
          	fixedContentPos: true,
          	fixedBgPos: true,
          	overflowY: "auto",
          	closeBtnInside: false,
          	showCloseBtn: false,
          	preloader: false, 
          	midClick: true,
          	removalDelay: 300,
          	mainClass: "wrslb-modal"
		});

	}

	var openSmallModal = function( modal ) {

		modal.removeClass(".mfp-hide");
		jQuery.magnificPopup.open({
		  	items: {
		    	src: modal,
		    	type: 'inline',
		  	},
          	fixedContentPos: false,
          	fixedBgPos: true,
          	overflowY: "auto",
          	closeBtnInside: false,
          	showCloseBtn: false,
          	preloader: false, 
          	midClick: true,
          	removalDelay: 300,
          	mainClass: "wrslb-sm-modal"
		});

	}

	var openMsgModal = function( modal ) {

		modal.removeClass(".mfp-hide");
		jQuery.magnificPopup.open({
		  	items: {
		    	src: modal,
		    	type: 'inline',
		  	},
          	fixedContentPos: true,
          	fixedBgPos: true,
          	overflowY: "auto",
          	closeBtnInside: false,
          	showCloseBtn: false,
          	closeOnBgClick: false,
          	enableEscapeKey: false,
          	preloader: false, 
          	removalDelay: 300,
          	mainClass: "wrslb-sm-modal"
		});

	}

	/* Create carousel
	---------------------------------------------------------- */

	t.addNewCarousel = function( event ) {

		event.preventDefault();

		var modal = jQuery("#wrslb-main-modal");

		modal.html( WRSLB_AJAX.loadingModal );
		openMsgModal( modal );
		
		jQuery.post( WRSLB_AJAX.ajaxUrl, { action: 'wrslb-add-new' }, function(data) {
			console.log(data);
			modal.find(".wrslb-modal-container").slideUp( 350 , function() {
				jQuery(this)
					.html(data)
					.slideDown( 350 );

			});

		});

	}

	t.createCarousel = function( event ) {

		event.preventDefault();

		var btn = jQuery(this),
			modal = btn.closest(".wrslb-modal-container"),
			modalHTML = modal.html(),
			container = btn.closest(".wrslb-addnew-container"),
			form = container.find("form.wrslb-addnew-form"),
			formSerialize = form.serialize();

		modal.html( WRSLB_AJAX.processingModal );
		jQuery.post( WRSLB_AJAX.ajaxUrl, formSerialize , function(data) {
			
			var result = data.split("_|_");

			if ( result[0] == 'error' ) {
				alert( result[1] );
				modal.html( modalHTML );
			} else {
				modal.html( result[1] );
				jQuery(document).trigger( "wrslb_create_product/success" , data );
			}

		});
		
	}

	/* Update carousel
	---------------------------------------------------------- */

	t.updateCarousel = function( event ) {

		event.preventDefault();

		var modal = jQuery("#wrslb-main-modal"),
			btn = jQuery(this),
			form = btn.closest("form.wrslb-edit-form"),
			formSerialize = form.serialize();

		modal.html( WRSLB_AJAX.savingModal );

		// open modal
		openMsgModal( modal );
		
		jQuery.post( WRSLB_AJAX.ajaxUrl, formSerialize , function(data) {
			
			var result = data.split("_|_");

			if ( result[0] == 'error' ) {
				alert( result[1] );
				closeModal( modal );
			} else {
				
				jQuery(".wrslb-modal-saving").fadeOut( 250 , function() {
					jQuery(this).html( result[1] ).fadeIn(250);
				});
				jQuery(document).trigger( "wrslb_update_carousel/success" , data );

				setTimeout( function(){
					closeModal( modal );
				}, 1000 );
			}

		});

	}

	/* Delete carousel
	---------------------------------------------------------- */	

	t.deleteCarousel = function( event ) {

		event.preventDefault();

		var btn = jQuery(this),
			cID = btn.attr("data-carousel-id"),
			container = btn.closest(".wrslb-modal-container");

		container.html( WRSLB_AJAX.processingModal );

		jQuery.post( WRSLB_AJAX.ajaxUrl, { action: 'wrslb-delete-carousel' , id: cID } , function(data) {
			if ( data == 'success' ) {
				window.location.href = window.location.href;
			}
		});

	}

	/* Content Boxes
	---------------------------------------------------------- */

	var initAccordion = function( obj ) {
		obj.accordion({
		    transitionSpeed: 400,
            controlElement: '[data-accord-control]',
            contentElement: '[data-accord-content]',
            groupElement: '[data-accord-group]',
            singleOpen: true,
		});
	}

	var sortableAccordion = function( obj ) {

		if ( obj.length > 0 ) {
			obj.sortable({
					items: ".wrslb-single-accord",
					handle: ".wrslb-sortable-handle",
					placeholder: "ui-state-highlight",
					connectWith: "div.wrslb-accord-container",
				});
		}

	}

	t.addContent = function( event ) {

		event.preventDefault();

		var btn = jQuery(this),
			btnHTML = btn.html(),
			section = btn.closest("#wrslb-form-contents_type"),
			container = section.find(".wrslb-accord-container");

		btn.addClass("adding").html( WRSLB_AJAX.loadingBtn );

		jQuery.post( WRSLB_AJAX.ajaxUrl, { action: 'wrslb-add-content' }, function(data) {

			var result = data.split("_|_");

			btn.removeClass("adding").html( btnHTML );
			container.find(".wrslb-single-accord").each(function(){
				var accord = jQuery(this);
				if ( accord.hasClass("open") ) {
					jQuery(this).removeClass("open");
					//jQuery(this).find(".wrslb-accord-content").slideUp(150);
				}
			});
			container.append( result[1] );

			/* re-trigger accordion & sortable */
			initAccordion( container.find(".wrslb-single-accord") );
			sortableAccordion( container );

		});

	}

	t.removeContent = function( event ) {

		event.preventDefault();

		var btn = jQuery(this),
			container = btn.closest(".wrslb-single-accord");

		var answer = confirm( WRSLB_AJAX.deleteContent );
			if (answer) {
				container.slideUp( 250 , function() {
					container.remove();
				});
			}

	}

	/* Colorpicker
	---------------------------------------------------------- */

	var initColorPicker = function() {
		jQuery('#wrsl-builder .wrslb-input-colorpicker').each(function(){
			var input	= jQuery(this),
				parent	= input.parent();
				
			input.wpColorPicker();
		});
	}

	/* Show if selector
	---------------------------------------------------------- */

	var showIfSelector = function( obj , selector ) {

		var sValue = obj.attr("data-show-if-value"),
			sOperator = obj.attr("data-show-if-operator"),
			selectorObj = jQuery( "#" + selector );

		if ( sValue !== undefined && sOperator !== undefined ) {

			if ( sOperator == '==' ) {
				var inputValue = selectorObj.val();

				if ( inputValue === undefined || sValue != inputValue )
					obj.hide();

			} else if ( sOperator == '!=' ) {
				var inputValue = selectorObj.val();

				if ( inputValue === undefined || sValue == inputValue )
					obj.hide();

			} else if ( sOperator == 'checked' ) {
				var inputValue = selectorObj.attr('checked');
				if ( inputValue === undefined )
					obj.hide();
			} else if ( sOperator == 'opt_selected' ) {
				var inputValue = selectorObj.val();
				if ( inputValue === undefined || sValue == '' || sValue != inputValue )
					obj.hide();
			} else if ( sOperator == 'contains' ) {
				var inputValue = selectorObj.val();
				if ( inputValue === undefined || sValue.indexOf( inputValue ) < 0 )
					obj.hide();
			}

		}

	}

	/* If option selector is selected
	---------------------------------------------------------- */
	t.optSelected = function( event ) {

		event.preventDefault();

		var btn = jQuery(this),
			value = btn.attr("data-optselector-value"),
			container = btn.closest(".wrslb-input-type-optselector"),
			input = container.find("input.wrslb-optselector-input");

		container.find(".wrslb-optselector-options > button").each( function() {
			jQuery(this).removeClass("wrslb-optselector-active");
		});

		btn.addClass("wrslb-optselector-active");
		if ( input !== undefined ) {
			input.val(value);

			// trigger show if changes
			var input_id = input.attr("id");
			jQuery("#wrsl-builder").find('[data-show-if="'+input_id+'"]').each( function() {
				var obj = jQuery(this),
					sValue = obj.attr("data-show-if-value"),
					selectorObj = jQuery( '#' + input_id ),
					sOperator = obj.attr("data-show-if-operator"),
					inputValue = selectorObj.val();

				if ( sOperator == 'contains' ) {
					if ( inputValue === undefined || sValue == '' || sValue.indexOf( inputValue ) < 0 )
						obj.slideUp( 250 );
					else
						obj.slideDown( 250 );
				} else {
					if ( inputValue === undefined || sValue == '' || sValue != inputValue || ( sOperator == 'contains' && sValue.indexOf( inputValue ) < 0 ) )
						obj.slideUp( 250 );
					else
						obj.slideDown( 250 );
				} // end - 	sOperator
				
			});

		}

	}

	t.catFilterSelected = function( event ) {

		event.preventDefault();

		var btn = jQuery(this),
			value = btn.attr("data-catfilter-value"),
			container = btn.closest(".wrslb-input-type-catfilter"),
			input = container.find("input.wrslb-catfilter-input"),
			multiselector = container.find(".wrslb-catfilter-multiselector");

		container.find(".wrslb-catfilter-options > button").each( function() {
			jQuery(this).removeClass("wrslb-optselector-active");
		});

		btn.addClass("wrslb-optselector-active");
		if ( input !== undefined ) {
			if ( value == 0 || value == '0' ) {
				input.val(value);
				multiselector.slideUp( 250, function() {
					multiselector.removeClass("wrslb-optselector-active");
				});
				
			} else {

				// trigger multiselector
				multiselector.slideDown( 250, function() {
					multiselector.addClass("wrslb-optselector-active");
					
				});

			} // end - value
		} // end - input

	}

	t.catFilterChanged = function( event ) {

		event.preventDefault();

		var selector = jQuery(this),
			value = selector.val(),
			container = selector.closest(".wrslb-input-type-catfilter"),
			input = container.find("input.wrslb-catfilter-input"),
			multiselector = container.find(".wrslb-catfilter-multiselector");

		if ( value && value != null ) {
			var string = '',
				count = 1;
			jQuery.each( value, function(index,id) {
				string += ( count > 1 ? ',' : '' )+id
				count++;
			});
			input.val(string);
		} // end - value
	}

	/* Upload single image
	---------------------------------------------------------- */

	t.uploadImage = function( event ) {

		event.preventDefault();

		var btn = jQuery(this),
			container = btn.closest(".wrslb-input-image-wrapper"),
			upload = true;

		// If the media frame already exists, reopen it.
		if ( media_frame ) {
			media_frame.close();
		}

		// Create the media frame.
		media_frame = wp.media.frames.media_frame = wp.media({
			title: btn.data( 'title' ),
			button: {
				text: btn.data( 'button_text' ),
			},
		});

		// When image(s) selected, run a callback.
		media_frame.on( 'select' , function() {
			var attachment = media_frame.state().get('selection').first(),
				inputTarget = btn.data( 'input_id' );
			
			// insert url into input field
			jQuery( "#" + inputTarget ).val(attachment.attributes.id);
			container.find(".wrslb-input-image-field > img")
				.attr('src', attachment.attributes.url)
				.removeClass("wrslb-hide-image");
		} );

		// Finally, open the modal
		media_frame.open();

	}

	/* Run the init function
	---------------------------------------------------------- */
	jQuery(document).ready(function(){

		// trigger loading btn
		jQuery(document).on( "click" , ".wrslb-loading-btn", WRSL_Backend.loadingBtn );

		/// modal trigger
		jQuery(document).on( "click" , ".wrslb-modal-trigger", WRSL_Backend.triggerModal );
		jQuery(document).on( "click" , ".wrslb-close-modal", WRSL_Backend.closeModal );
		jQuery(document).on( "click" , ".wrslb-close-selectedmodal", WRSL_Backend.closeSelectedModal );
		jQuery(document).on( "click" , ".wrslb-open-selectedmodal", WRSL_Backend.openSelectedModal );

		// create new carousel
		jQuery(document).on( "click" , "button.wrslb-new-carousel" , WRSL_Backend.addNewCarousel );
		jQuery(document).on( "click" , "button.wrslb-create-carousel" , WRSL_Backend.createCarousel );

		// Update carousel
		jQuery(document).on( "click" , "button.wrslb-save-changes" , WRSL_Backend.updateCarousel );

		// Delete carousel
		jQuery(document).on( "click" , "button.wrslb-delete-carousel" , WRSL_Backend.deleteCarousel );

		// init Accordions
		initAccordion( jQuery(".wrslb-accord-container .wrslb-single-accord") );
		sortableAccordion( jQuery(".wrslb-accord-container") );
		jQuery(document).on( "click" , "button.wrslb-add-content", WRSL_Backend.addContent );
		jQuery(document).on( "click" , "button.wrslb-delete-content", WRSL_Backend.removeContent );

		// color picker 
		initColorPicker();

		// upload image
		jQuery(document).on( "click" , ".wrslb-input-image-btn", WRSL_Backend.uploadImage );

		// option selector listener
		jQuery(document).on( "click" , ".wrslb-optselector-btn", WRSL_Backend.optSelected );

		// cat filter listener
		jQuery(document).on( "click" , ".wrslb-catfilter-btn", WRSL_Backend.catFilterSelected );
		jQuery(document).on( "change" , "#wrslb-catfilter-multiselect", WRSL_Backend.catFilterChanged );

		// Show if Selector
		jQuery("#wrsl-builder [data-show-if]").each( function() {
			var obj = jQuery(this),
				selector = obj.attr("data-show-if");

			if ( selector !== undefined ) {

				showIfSelector( obj , selector );

				jQuery(document).on( "change" , "#" + selector , function() {
					var sValue = obj.attr("data-show-if-value"),
						sOperator = obj.attr("data-show-if-operator"),
						selectorObj = jQuery( this );

					if ( sValue !== undefined && sOperator !== undefined ) {

						if ( sOperator == '==' ) {
							var inputValue = selectorObj.val();

							if ( inputValue === undefined || sValue != inputValue )
								obj.slideUp( 250 );
							else
								obj.slideDown( 250 );

						} else if ( sOperator == '!=' ) {
							var inputValue = selectorObj.val();

							if ( inputValue === undefined || sValue == inputValue )
								obj.slideUp( 250 );
							else
								obj.slideDown( 250 );

						} else if ( sOperator == 'checked' ) {
							var inputValue = selectorObj.attr('checked');
							if ( inputValue === undefined )
								obj.slideUp( 250 );
							else
								obj.slideDown( 250 );
						} else if ( sOperator == 'contains' ) {
							var inputValue = selectorObj.val();

							if ( inputValue === undefined || sValue.indexOf( inputValue ) < 0 )
								obj.slideUp( 250 );
							else
								obj.slideDown( 250 );
						}

					}
				});

			}
			
		});	

	});


} // end - WRSL_Backend