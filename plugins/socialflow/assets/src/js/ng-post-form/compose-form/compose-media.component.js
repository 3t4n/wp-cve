export default {
	bindings: {
		global: '<'
	},
	template: sfPostFormTmpls.messageComposeMedia,
	controller: function( WPMediaFrameService, postAttachments ) {
		this.post  = this.global.post;
		this.media = this.global.messageComposeMedia.media;
		this._loadingImg = false;

		this.$onInit = () => {
			angular.element( document ).on( 'sf-set-post-thumbnail', this._onSetPostThumbnail.bind( this ) );
		}

		this.setImage = ( e ) => {
			e.preventDefault();

			WPMediaFrameService
				.setAjaxHandler({
					request: this._imageRequest.bind( this ),
					success: this._setImageData.bind( this ),
				})
				.open();
		}

		this.setLoadingClass = () => {
			return ( true === this._loadingImg ) ? 'loading' : '';
		}

		this._imageRequest = ( imageId ) => {
			this._loadingImg = true;

			return postAttachments.attachMedia( imageId );
		}

		this._setImageData = ( data ) => {
			this.media = data;

			this._loadingImg = false;
		}

		this._onSetPostThumbnail = ( e, thumbId ) => {
			if ( this.media && this.media.medium_thumbnail_url ) 
				return;

			postAttachments.attachMedia( thumbId )
				.then( ( data ) => {

					this._setImageData( data );
				});
		}
	}
}