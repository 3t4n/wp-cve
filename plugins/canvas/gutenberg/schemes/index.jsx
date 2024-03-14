/**
 * Internal dependencies
 */
import './style.scss';

/**
 * WordPress dependencies
 */
const {
	jQuery: $,
	canvasLocalize,
} = window;

const {
	__,
} = wp.i18n;

/**
 * Add schemes button to Gutenberg settings
 */
$( document ).on( 'DOMContentLoaded', () => {
	wp.data.subscribe(function () {
		setTimeout(function () {
			if (!document.getElementById('canvas-settings-toogle-scheme')) {
				const $settings = $( '.edit-post-header__settings' );

				if ( $settings.length ) {
					$settings.find( '.edit-post-more-menu' ).before( `<div id="canvas-settings-toogle-scheme" class="canvas-settings-toogle-scheme">
							<button class="components-button components-icon-button" aria-label="${ __( 'Scheme', 'canvas' ) }">
								<svg class="canvas-default" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>

								<svg class="canvas-dark" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
							</button>
						</div>` );
				}
			}
		}, 1)
	});

	// Subscribe change scheme.
	wp.data.subscribe( function() {
		var scheme = wp.data.select( 'canvas/scheme' ).getScheme();

		$('.canvas-settings-toogle-scheme').attr( 'scheme', scheme );

		// Alt version.
		$('.block-editor-writing-flow').attr( 'site-data-scheme', scheme );

		// New version.
		$('.block-editor-writing-flow').attr( 'data-site-scheme', scheme );

		if ( 'dark' === scheme ) {
			$('.block-editor-writing-flow').attr( 'data-scheme', canvasLocalize.schemeDarkSlug );
		} else {
			$('.block-editor-writing-flow').attr( 'data-scheme', canvasLocalize.schemeDefaultSlug );
		}
	} );

	// Change global scheme.
	$( document ).on( 'click', '.canvas-settings-toogle-scheme', ( e ) => {
		e.preventDefault();

		$('.block-editor-writing-flow').addClass( 'canvas-sceme-toggled' );

		var scheme = wp.data.select( 'canvas/scheme' ).getScheme();

		if ( 'dark' === scheme ) {
			wp.data.dispatch('canvas/scheme').updateScheme('');
		} else {
			wp.data.dispatch('canvas/scheme').updateScheme('dark');
		}

		setTimeout( () => {
			$('.block-editor-writing-flow').removeClass( 'canvas-sceme-toggled' );
		}, 100 );
	} );

} );
