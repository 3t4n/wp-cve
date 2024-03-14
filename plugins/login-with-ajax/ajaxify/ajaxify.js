
var LWA_Ajaxify = {
	ajaxifiables : {},
	is_ajaxifiable : function( $form ){
		// we can pass a jQuery object or a DOM element, but we ideally want DOM
		let form = $form instanceof jQuery ? $form[0] : $form;
		let type = false;
		Object.keys(LWA_Ajaxify.ajaxifiables).forEach( function( selector ){
			if( form.matches( selector ) ){
				type = selector;
			}
		});
		return type;
	},
	init : function() {
		document.dispatchEvent( new CustomEvent( 'lwa_ajaxify_init' ) );
		jQuery( Object.keys( LWA_Ajaxify.ajaxifiables ).join(',') ).each( function( el ){
			let $form = jQuery(this);
			$form.wrap('<div class="lwa-wrapper"></div>')
				.wrap('<div class="lwa"></div>')
				.addClass('lwa-form');
			let selector = LWA_Ajaxify.is_ajaxifiable( this );
			let ajaxify = LWA_Ajaxify.ajaxifiables[selector]; // we assume in this case it comes up positive since it's a matched selector already
			if ( ajaxify.type === 'login' ) {
				$form.append( jQuery('<input type="hidden" name="login-with-ajax" value="login">') );
			} else if ( ajaxify.type === 'register' ) {
				$form.append( jQuery('<input type="hidden" name="login-with-ajax" value="register">') );
			} else if ( ajaxify.type === 'remember' ) {
				$form.append( jQuery('<input type="hidden" name="login-with-ajax" value="remember">') );
			}
			if( ajaxify && typeof ajaxify.init == 'function' ) {
				ajaxify.init( this );
			}
		});
		jQuery(document).on('lwa_addStatusElement', function(e, form, statusElement){
			let selector = LWA_Ajaxify.is_ajaxifiable( form );
			if( selector !== false ) {
				let ajaxify = LWA_Ajaxify.ajaxifiables[selector];
				if( ajaxify && typeof ajaxify.addStatusElement == 'function' ) {
					ajaxify.addStatusElement( form, statusElement );
				}
			}
		});
		jQuery(document).on('lwa_handleStatus', function(e, response, statusElement){
			if( statusElement.hasClass('lwa-ajaxify-status') ) {
				let form = statusElement.closest('.lwa').find( Object.keys(LWA_Ajaxify.ajaxifiables).join(',') );
				if ( form.length > 0 ) {
					statusElement.show();
					let selector = LWA_Ajaxify.is_ajaxifiable( form );
					let ajaxify = LWA_Ajaxify.ajaxifiables[selector];
					if( ajaxify && typeof ajaxify.handleStatus == 'function' ) {
						ajaxify.handleStatus( response, statusElement, form );
					}
				}
			}
		});
		jQuery(document).on('lwa_pre_ajax', function(e, response, form, statusElement){
			let selector = LWA_Ajaxify.is_ajaxifiable( form );
			if( selector !== false ) {
				statusElement.hide();
				let ajaxify = LWA_Ajaxify.ajaxifiables[selector];
				if( ajaxify && typeof ajaxify.pre_ajax == 'function' ) {
					ajaxify.pre_ajax( form );
				}
			}
		});
		jQuery(document).on('lwa_register lwa_remember', function(e, response, form, statusElement){
			let selector = LWA_Ajaxify.is_ajaxifiable( form );
			let ajaxify = LWA_Ajaxify.ajaxifiables[selector]; // we assume in this case it comes up positive since it's a matched selector already
			if( ['register','remember'].includes( ajaxify.type ) ) {
				if( response.result ){
					form.hide();
					form.find('input').val('');
				}
				if( ajaxify && typeof ajaxify[e.type] == 'function' ) {
					ajaxify[e.type]( form );
				}
			}
		});
		document.dispatchEvent( new CustomEvent( 'lwa_ajaxify_loaded' ) );
	}
};
jQuery(document).ready( LWA_Ajaxify.init );