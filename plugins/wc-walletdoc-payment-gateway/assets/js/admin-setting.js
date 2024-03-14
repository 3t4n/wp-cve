/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery( function( $ ) {
	'use strict';

	/**
	 * Object to handle Walletdoc admin functions.
	 */
	var wc_walletdoc_admin = {
		isTestMode: function() {
			return $( '#woocommerce_walletdoc_testmode' ).is( ':checked' );
		},
		isSavedMode: function() {
			return $( '#woocommerce_walletdoc_saved_cards' ).is( ':checked' );
		},

		getSecretKey: function() {
			if ( wc_walletdoc_admin.isTestMode() ) {
				return $( '#woocommerce_walletdoc_client_secret' ).val();
			} else {
				return $( '#woocommerce_walletdoc_production_secret' ).val();
			}
		},

		/**
		 * Initialize.
		 */
		init: function() {
			$( document.body ).on( 'change', '#woocommerce_walletdoc_testmode', function() {
				var sandbox_secret_key = $( '#woocommerce_walletdoc_client_secret' ).parents( 'tr' ).eq( 0 );
				var	production_secret_key = $( '#woocommerce_walletdoc_production_secret' ).parents( 'tr' ).eq( 0 );
				var sandbox_public_key = $( '#woocommerce_walletdoc_sandbox_public' ).parents( 'tr' ).eq( 0 );
				var	production_public_key = $( '#woocommerce_walletdoc_production_public' ).parents( 'tr' ).eq( 0 );

				if ( $( this ).is( ':checked' ) ) {
					sandbox_secret_key.show();
					production_secret_key.hide();
					sandbox_public_key.show();
					production_public_key.hide()
					
				} else {
					sandbox_secret_key.hide();
					production_secret_key.show();
					sandbox_public_key.hide();
					production_public_key.show()
				}
			} );

			$( '#woocommerce_walletdoc_testmode' ).change();

			$( document.body ).on( 'change', '#woocommerce_walletdoc_saved_cards', function(event) {
		
				if ( wc_walletdoc_admin.isSavedMode() == false ) {
					alert("Saved cards must be enabled for subscription payments");
				}
				
			} );

			

		}
	};

	wc_walletdoc_admin.init();
} );


