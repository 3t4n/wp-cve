export default class PostAttachmentsService {
	/* @ngInject */
	constructor( httpService, commonService, cacheService ) {
		this._httpService   = httpService;
		this._commonService = commonService;
		this._cacheService  = cacheService;

		this.postId    = cacheService.get( 'post' ).ID;
		this.$document = angular.element( document );

		this.$document.on( 'ajaxComplete', this._onGlobalAjaxComplete.bind( this ) );
	}

	_onGlobalAjaxComplete( event, xhr, settings ) {
		if ( !settings.hasOwnProperty( 'data' ) )
			return;

		let data = this._httpService.parseQueryString( settings.data );

		if ( 'get-post-thumbnail-html' == data.action ) {
			this._updatePostThumbnail( data.thumbnail_id, 1 );
		}
		if ( 'set-post-thumbnail' == data.action  || 'send-attachment-to-editor' == data.action ) {
			this.findImagesFromPostContent();
		};
	}

	_updatePostThumbnail( thumbId , feature) {
		let post = this._cacheService.get( 'post' );

		if ( '-1' == thumbId ) {
			post.thumbnail = '';
			this._triggerUpdatePostThumbnail( post.thumbnail );
		}
		else {
			if (feature){
				this.attachSingleImage( thumbId, feature )
					.then( ( data ) => {
					post.thumbnail = data.medium_thumbnail_url;
				this._triggerUpdatePostThumbnail( post.thumbnail );
			});
			} else {
				this.attachSingleImage( thumbId )
					.then( ( data ) => {
					post.thumbnail = data.medium_thumbnail_url;
				this._triggerUpdatePostThumbnail( post.thumbnail );
			});
			}

		}
	}

	_triggerUpdatePostThumbnail( thumbUrl ) {
		this.$document.trigger( 'sf-update-post-thumbnail', [ thumbUrl ] );
	}

	findImagesFromPostContent( $el ) {
		let content;
			content = this._commonService.getPostContentValue();

		if ( !$el )
			$el = this.$document; 

		this._httpService.post({
			action: 'sf_attachments', 
			ID: this.postId, 
			content: content,
		})
		.then( ( slides ) => {
			$el.trigger( 'sf-get-slides', [ slides ] );
		});
	}
	attachSingleImage( imageId, feature ) {
		if(feature) {
			return this._httpService.post({
				action: 'sf_get_custom_message_image',
				attachment_id: imageId,
				feature: feature,
				attach_to_post: this.postId,
			});
		} else {
			return this._httpService.post({
				action: 'sf_get_custom_message_image',
				attachment_id: imageId
			});
		}
	}

	attachMedia( imageId, socialTtype ) {
		return this._httpService.post({
			attach_to_post: this.postId,
            social_id: socialTtype,
			action: 'sf_get_custom_message_image',
			attachment_id: imageId
		});
	}

	attachMediaForSlides(imageId, socialTtype){
		return this._httpService.post({
			attach_to_post: this.postId,
			social_id: socialTtype,
			action: 'sf_get_custom_message_image_atacments_slide',
			attachment_id: imageId
		});
	}

}