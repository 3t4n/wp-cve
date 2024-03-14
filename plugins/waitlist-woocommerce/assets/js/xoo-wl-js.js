jQuery(document).ready(function( $ ){



	var Popup = {

		$self: $('.xoo-wl-popup'),

		init: function(){

			Popup.$noticeCont 	= Popup.$self.find('.xoo-wl-notices');
			Popup.$header 		= Popup.$self.find('.xoo-wl-header');
			Popup.$form 		= Popup.$self.find('.xoo-wl-form');

			$('body').on( 'click', '.xoo-wl-btn-popup', this.open );
			$('.xoo-wl-modal').on( 'click', this.close );
		},

		open: function(e){
			Popup.$self.add( $('html, body') ).addClass('xoo-wl-popup-active');
			Popup.$self.find( 'input[name="_xoo_wl_product_id"]' ).val( $(this).attr('data-product_id') );
			Popup.$form.add(Popup.$header).show();
			Popup.$noticeCont.hide();
		},

		close: function( event ){
			$.each( event.target.classList, function( key, value ){
				if( value == 'xoo-wl-modal' || value == 'xoo-wl-close' ){
					$('html, body').add( Popup.$self ).removeClass('xoo-wl-popup-active');
					$('body').trigger('xoo_wl_popup_closed');
					return false;
				}
			})
		}
	}

	Popup.init();


	var Form = function( $form ){

		var self 				= this;
		self.$form 				= $form;
		self.$productIDInput 	= self.$form.find( 'input[name="_xoo_wl_product_id"]' );
		self.productID 			= self.$productIDInput.val();
		self.$noticeCont 		= self.$form.siblings( '.xoo-wl-notices' );
		self.$header 			= self.$form.siblings( '.xoo-wl-header' );

		self.validationPassed 	= self.validationPassed.bind(this);
		self.showNotice 		= self.showNotice.bind(this);

		self.$form.on( 'submit', { form: self }, self.submit );

	}

	Form.prototype.submit = function( event ){

		event.preventDefault();
		var form = event.data.form;
		if( !form.validationPassed() ) return;

		var formData = form.$form.serialize()+'&action=xoo_wl_form_submit';

		$.ajax({
			url: xoo_wl_localize.adminurl,
			type: 'POST',
			data: formData,
			success: function(response){
				if( response.notice ){
					form.showNotice(response.notice);
				}
				else{
					console.log(response);
				}

				if( response.error === 0){
					form.$form.add(form.$header).hide();
				}
			}
		});

	}


	Form.prototype.validationPassed = function(){

		var form = this,
			errors = [],
			errorHTML = '';

		if( !form.productID ){
			errors.push( xoo_wl_localize.notices.empty_id );
		}

		$.each( errors, function( index, error ){
			errorHTML += error;
		} )

		form.showNotice(errorHTML);

		return errors.length ? false : true;
	}


	Form.prototype.showNotice = function( notice ){
		var form = this
		form.$noticeCont.html(notice).show();
	}



	$.each( $( 'body' ).find( '.xoo-wl-form' ), function( index, el ){
		new Form( $(el) );
	} );


		//WooCommerce Product Variation on select
	$('body').on( 'change', 'form.variations_form .variation_id', function(){

		var $atcForm 			= $(this).parents('form.variations_form'),
			variationsData 		= $atcForm.data('product_variations'),
			selectedVariation 	= $(this).val(),
			$waitlistContainer 	= $atcForm .siblings('.xoo-wl-btn-container');

			if( !$waitlistContainer.length ) return;

		var $waitlistBtn = $waitlistContainer.find( 'button.xoo-wl-open-form-btn' ),
			$productIDinput = $waitlistContainer.find('input[name="_xoo_wl_product_id"]');

			$waitlistContainer.hide();


		if( !selectedVariation || selectedVariation == 0 ){
			selectedVariation = $atcForm.find('input[name="product_id"]').val();
		}

		$.each( variationsData, function( index, variation ){
			
			if( variation.variation_id != selectedVariation ) return;
		
			if( variation.is_in_stock && ( xoo_wl_localize.showOnBackorders !== "yes" || !variation.backorders_allowed  ) ) return;
			
			$waitlistContainer.show();

			return false;
		} );


		//Set product IDS
		if( $waitlistBtn.length ){
			$waitlistBtn.attr( 'data-product_id', selectedVariation )
		}
		if( $productIDinput.length ){
			$productIDinput.val( selectedVariation );
		}
		
	})

	$('body .variation_id').trigger('change');

	$('body').on( 'click', 'button.xoo-wl-btn-toggle', function(){

		var toggleClass = 'xoo-wl-active',
			$container 	= $(this).parents('.xoo-wl-btn-container');

		if( $container.hasClass( toggleClass ) ){
			$container.removeClass( toggleClass )
		}
		else{
			$container.addClass( toggleClass ).
			$container.find( 'input[name="_xoo_wl_product_id"]' ).val( $(this).data('product_id') );
		}

	} );


})