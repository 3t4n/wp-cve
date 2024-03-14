'use strict';

( function ( $ ) {
	let doc = $( document );

	doc.ready( function () {
		if ( typeof FSPObject.metabox_js_loaded === 'undefined' )
		{
			doc.on( 'change', '#fspMetaboxShare', function () {
				if ( $( this ).is( ':checked' ) )
				{
					$( '#fspMetaboxShareContainer' ).slideDown( 200 );
				}
				else
				{
					$( '#fspMetaboxShareContainer' ).slideUp( 200 );
				}

				saveMetabox();
			} ).on( 'click', '.fsp-metabox-tab', function () {
				let _this = $( this );

				let driver = _this.data( 'tab' );

				if ( driver == 'all' )
				{
					$( '.fsp-metabox-tab.fsp-is-active' ).removeClass( 'fsp-is-active' );
					_this.addClass( 'fsp-is-active' );
					$( '.fsp-metabox-accounts > .fsp-metabox-account' ).slideDown( 200 );
					$( '#fspMetaboxCustomMessages > div' ).slideUp( 200 );
				}
				else if(!['fb', 'ok', 'vk', 'linkedin', 'tumblr', 'reddit', 'plurk', 'telegram'].includes(driver)){
					FSPoster.upgrade("Purchase premium version to access all features.", true);
				}
				else{
					$( '.fsp-metabox-tab.fsp-is-active' ).removeClass( 'fsp-is-active' );
					_this.addClass( 'fsp-is-active' );
					$( `.fsp-metabox-accounts > .fsp-metabox-account[data-driver!="${ driver }"]` ).slideUp( 200 );
					$( `.fsp-metabox-accounts > .fsp-metabox-account[data-driver="${ driver }"]` ).slideDown( 200 );
					$( `#fspMetaboxCustomMessages > div[data-driver!="${ driver }"]` ).slideUp( 200 );
					$( `#fspMetaboxCustomMessages > div[data-driver="${ driver }"]` ).slideDown( 200 );
				}
			} ).on( 'click', '.fsp-metabox-custom-message-label', function () {
				$( this ).next().slideToggle( 200 );
			} ).on('focus', '.fsp-form-textarea', function ( event ) {
				event.preventDefault();
				FSPoster.upgrade("Purchase premium version to access all features.", true);
			});

			FSPObject.metabox_js_loaded = true;
		}

		$( '.fsp-metabox-tab' ).eq( 0 ).click();
		$( '.fsp-metabox-custom-message-label' ).click();
		$( '#fspMetaboxShare' ).trigger( 'change' );
	} );
} )( jQuery );

function saveMetabox ()
{
	if ( typeof jQuery !== 'undefined' )
	{
		$ = jQuery;
	}

	if ( $( '#fs_poster_meta_box' ).length )
	{
		$( '#fspSavingMetabox' ).removeClass( 'fsp-hide' );

		let id = FSPObject.id;
		let share_checked = $( '#fspMetaboxShare' ).is( ':checked' ) ? 1 : 0;

		FSPoster.ajax( 'save_metabox', {
			id, share_checked
		}, function () {
			$( '#fspSavingMetabox' ).addClass( 'fsp-hide' );
		}, true, function () {
			$( '#fspSavingMetabox' ).addClass( 'fsp-hide' );
		} );
	}
}