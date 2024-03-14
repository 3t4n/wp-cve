import AttachmentsSlider from './attachments-slider.class';
class MessageAttachments {
	/* @ngInject */
	constructor( $element, WPMediaFrameService, postAttachments, cacheService, fieldService, $scope ) {

		this._WPMediaFrameService = WPMediaFrameService;
		this._fieldService = fieldService;
		this._$scope = $scope;
		this._postAttachments = postAttachments;
        this.cache = cacheService;
		this.showHideImageForm = function  (socialType) {
			if(socialType == 'linkedin') {
				return 1;
			}
			if(this.cache.get('globalSettings')['show_'+socialType])
			return this.cache.get('globalSettings')['show_'+socialType];
		};
		this.slider = new AttachmentsSlider( $element, this.message, postAttachments, cacheService );

	}
    facebookMetaDisabled (socialType) {

        if(socialType != 'facebook' || this.cache.get('facebook_change_meta'))
        {
            return false;
        }

        if(this.cache.get('thumbnail_old_facebook').length > 0) {
           this.slider.contentImages =  this.cache.get('thumbnail_old_facebook')
		   this.slider._current =  this.cache.get('current_old_facebook');
           this.slider.init();

        }
        if (this.cache.get('custom_image_old_facebook')) {
            this.slider._customImage = this.cache.get('custom_image_old_facebook');
            this.slider._current =  this.cache.get('current_old_facebook');
            this.slider.init();
		} else if (this.cache.get('current_old_facebook') ) {
        	console.log(this.cache.get('current_old_facebook'));
            this.slider._current =  this.cache.get('current_old_facebook');
            this.slider._customImage =  this.slider._current;
            this.slider.init();
		}
        return true;
    }
	$onInit() {
		if ( this.message.fields['custom_image'] ) {
			this.slider.addCustomImage( this.message.fields['custom_image'] );
		}

		this.slider.init();
		angular.element( document ).on( 'sf-update-post-thumbnail', this._onUpdatePostThumbnail.bind( this ) );
	}
	setCustomImage( e ) {
		e.preventDefault();
		this._WPMediaFrameService
			.setHandler( this._attachMediaImage.bind( this ) )
			.open();
	}
	_attachMediaImage( data ) {
		this.message.fields['custom_image'] = data.url;
		this.slider.addCustomImage( data.url );
        this.setCustomImageData(data);
		this.slider.setCustomImageCurrentPosition();
		this.slider.init();
		this.slider.initForSocials();
		this._$scope.$apply();
	}
	getName( name ) {
		return this._fieldService.getMessageName( this.social, this.index, name );
	}

	getFieldId( name ) {
		return this._fieldService.getMessageId( this.social, this.index, name );
	}
	_onUpdatePostThumbnail( e, thumbUrl ) {
		if ( !thumbUrl ) {
            this.slider.removePostThumbnailForSocials(this.social.type);
            this.slider.removePostThumbnail();
            this.slider.init();
            this.slider.initForSocials(this.social.type);
            this._$scope.$apply();
        }
	}

	setCustomImageData( data ) {
		this.message.fields['custom_image'] = data.medium_thumbnail_url;
		this.message.fields['custom_image_filename'] = data.filename;
		this.message.fields['is_custom_image'] = 1;
		this.message.fields['image'] = data.medium_thumbnail_url;
	}
}

export default {
	bindings: {
		message: '<',
		index: '<',
		social: '<',
	},
	template:   sfPostFormTmpls.messageAttachments,
	controller: MessageAttachments
}