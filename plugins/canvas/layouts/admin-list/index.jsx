/**
 * WordPress dependencies
 */
const {
	jQuery: $,
} = window;

const { render } = wp.element;

const { __ } = wp.i18n;

/**
 * Internal dependencies
 */
import exportLayoutBlock from './utils/export';
import ImportDropdown from './components/import-dropdown';

$( document ).on( 'DOMContentLoaded', () => {
	// Setup Export Links
	$( '.type-canvas_layout.status-publish > .column-title > .row-actions' ).each( function() {
		const $this = $( this );
		const $parent = $this.closest( '.type-canvas_layout.status-publish' );
		let id = '';

		if ( $parent.attr('id') ) {
			id = $parent.attr('id').replace(/^post-/g, '');
		}

		$this.append( `
			<span class="export">
				 |
				<button type="button" class="wp-list-layout-blocks__export button-link" data-id="${ id }">${ __( 'Export as JSON', 'canvas' ) }</button>
			</span>
		` );
	} );
	$( document ).on( 'click', '.wp-list-layout-blocks__export', ( e ) => {
		e.preventDefault();
		exportLayoutBlock( e.target.dataset.id );
	} );

	// Setup Import Form
	const button = document.querySelector( '.page-title-action' );
	if ( ! button ) {
		return;
	}

	const showNotice = () => {
		const notice = document.createElement( 'div' );
		notice.className = 'notice notice-success is-dismissible';
		notice.innerHTML = `<p>${ __( 'Layout block imported successfully!', 'canvas' ) }</p>`;

		const headerEnd = document.querySelector( '.wp-header-end' );
		if ( ! headerEnd ) {
			return;
		}
		headerEnd.parentNode.insertBefore( notice, headerEnd );
	};

	const container = document.createElement( 'div' );
	container.className = 'list-layout-blocks__container';
	button.parentNode.insertBefore( container, button );
	render( <ImportDropdown onUpload={ showNotice } />, container );
} );
