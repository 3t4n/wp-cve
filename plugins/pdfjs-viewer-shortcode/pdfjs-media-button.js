jQuery( function( $ ) {
	$('.js-insert-pdfjs').click(PDFjs_openMediaWindow);	

	if (typeof acf !== 'undefined') {
		acf.addAction( 'load', function() {
			$( '.js-insert-pdfjs' ).on( 'click', function() {
				PDFjs_openMediaWindow();
			} );
		});
	}

	function PDFjs_openMediaWindow() {
		const frame = wp.media( {
			title: 'Insert a PDF',
			library: { type: 'application/pdf' },
			multiple: false,
			button: { text: 'Insert' },
		} );

		frame.on( 'select', function() {
			let selectionID = frame.state().get('selection').first().toJSON().id;
			let selectionURL = frame.state().get('selection').first().toJSON().url;
			selectionURL = selectionURL.replace(/(<([^>]+)>)/gi, "")

			let fullscreenLink = "fullscreen=false";
			if (typeof window.pdfjs_options.pdfjs_fullscreen_link !== 'undefined' && window.pdfjs_options.pdfjs_fullscreen_link !== '') {
				fullscreenLink = "fullscreen=true";
			}

			let downloadLink = "download=false";
			if (typeof window.pdfjs_options.pdfjs_download_button !== 'undefined' && window.pdfjs_options.pdfjs_download_button !== '') {
				downloadLink = "download=true";
			}

			let printLink = "print=false";
			if (typeof window.pdfjs_options.pdfjs_print_button !== 'undefined' && window.pdfjs_options.pdfjs_print_button !== '') {
				printLink = "print=true";
			}

			let viewerWidth = "100%";
			if (typeof window.pdfjs_options.pdfjs_embed_width !== 'undefined' && window.pdfjs_options.pdfjs_embed_width !== '0' && window.pdfjs_options.pdfjs_embed_width !== '') {
				viewerWidth = window.pdfjs_options.pdfjs_embed_width + "px";
			}

			let viewerHeight = "800px";
			if (typeof window.pdfjs_options.pdfjs_embed_height !== 'undefined' && window.pdfjs_options.pdfjs_embed_height !== '0' && window.pdfjs_options.pdfjs_embed_height !== '') {
				viewerHeight = window.pdfjs_options.pdfjs_embed_height + "px";
			}

			wp.media.editor.insert('[pdfjs-viewer url="' + selectionURL + '" attachment_id="' + selectionID + '" viewer_width=' + viewerWidth + ' viewer_height=' + viewerHeight + ' ' + fullscreenLink + ' ' + downloadLink + ' ' + printLink + ']');
		} );

		frame.open();
	}
} );
