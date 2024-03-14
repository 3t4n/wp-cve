(function($) {
	"use strict";
	$( document ).ready(function() {
		
		if ( document.querySelector('.woocommerce-MyAccount-orders') ) {
			
			$( '.fortnox-my-account-send-invoice' ).on( 'click', function( event ) {
				
				if (this.busy == true) 
					return;
				
				this.busy = true;
				
				event.preventDefault();
				
				var orderId = $( this ).parent().find('.hidden_order_number').html();
				
				var instance = this;
				
				$(instance).block({
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
				});
				
				$.ajax( {
					url: '/wp-admin/admin-ajax.php',
					data: {
						action: "fortnox_action",
						fortnox_action: "send_invoice",
						order_id: orderId
					},
					type: "POST",
					dataType: "json",
					success: function( response ) {
						
						if ( response.error ) {
							errorMessage(response.message);
						} else {
							$(instance).hide();
							$(instance).after( '<span class="indicator-fortnox-invoice-sent"></span>' );
							message(response.message)
						}
						$(instance).unblock();
						this.busy = false;
					}
				} );
			});
			
			const WC_NOTICE_SELECTOR = '.row .woocommerce > .woocommerce-NoticeGroup.woocommerce-NoticeGroup-checkout';
			
			function message(str) {
				if ( document.querySelector(WC_NOTICE_SELECTOR) ) {
					$(WC_NOTICE_SELECTOR).append(
						'<ul class="woocommerce-message">'+
							'<li>'+str+'</li>'+
						'</ul>'
					);
				} else {
					$('.row .woocommerce').prepend(
						'<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">'+
							'<ul class="woocommerce-message">'+
								'<li>'+str+'</li>'+
							'</ul>'+
						'</div>'
					);
				}
			}
			
			function errorMessage(str) {
				if ( document.querySelector(WC_NOTICE_SELECTOR) ) {
					$(WC_NOTICE_SELECTOR).append(
						'<ul class="woocommerce-error">'+
							'<li>'+str+'</li>'+
						'</ul>'
					);
				} else {
					$('.row .woocommerce').prepend(
						'<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">'+
							'<ul class="woocommerce-error">'+
								'<li>'+str+'</li>'+
							'</ul>'+
						'</div>'
					);
				}
			}
				
		}
		
	});
})(jQuery);