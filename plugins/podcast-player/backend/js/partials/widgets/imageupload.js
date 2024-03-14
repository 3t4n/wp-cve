class ImageUpload {

	/**
	 * Manage Feed editor options.
	 * 
	 * @since 3.3
	 * 
	 * @param {string} id Podcast player ID. 
	 */
	constructor() {

		// Define variables.
		this.uploadText = window.podcastplayerImageUploadText || {};
		this.fileFrame  = wp.media.frames.fileFrame = wp.media({
			title: this.uploadText.title,
			button: { text: this.uploadText.btn_text },
			multiple: false,
		});

		// Run methods.
		this.events();
	}

	// Event handling.
	events() {
		const _this = this;
		const doc   = jQuery(document);

		doc.on('click', '.podcast-player-widget-img-uploader', function(e) {
			e.preventDefault();
			_this.addImage(jQuery(this));
		});

		doc.on('click', '.podcast-player-widget-img-remover', function(e) {
			e.preventDefault();
			_this.removeImage(jQuery(this));
		});
	}

	/**
	 * Set an image for the widget.
	 * 
	 * @since 3.3.0
	 * 
	 * @param Obj obj
	 */
	addImage(obj) {
		const _this = this;
		// When an image is selected, run a callback.
		this.fileFrame.on( 'select', function() {
			const attachment = _this.fileFrame.state().get( 'selection' ).first().toJSON();
			const imgUrl = attachment.url;
			const imgId = attachment.id;
			const featuredImg = document.createElement( 'img' );

			featuredImg.src = imgUrl;
			featuredImg.className = 'custom-widget-thumbnail';
			obj.html( featuredImg );
			obj.addClass( 'has-image' );
			obj.nextAll( '.podcast-player-widget-img-id' ).val( imgId ).trigger( 'change' );
			obj.nextAll( '.podcast-player-widget-img-instruct, .podcast-player-widget-img-remover' ).removeClass( 'podcast-player-hidden' );
		});

		// Finally, open the modal
		this.fileFrame.open();
	}

	/**
	 * Remove an image for the widget.
	 * 
	 * @since 3.3.0
	 * 
	 * @param Obj obj
	 */
	removeImage(obj) {
		obj.prevAll( '.podcast-player-widget-img-uploader' ).html( this.uploadText.img_text ).removeClass( 'has-image' );
		obj.prev( '.podcast-player-widget-img-instruct' ).addClass( 'podcast-player-hidden' );
		obj.next( '.podcast-player-widget-img-id' ).val( '' ).trigger( 'change' );
		obj.addClass( 'podcast-player-hidden' );
	}
}

export default ImageUpload;
