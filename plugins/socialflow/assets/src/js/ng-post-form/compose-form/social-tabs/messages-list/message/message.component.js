import MessageCommon from './message.class';
import AttachmentsSlider from './attachments/attachments-slider.class';

class Message extends MessageCommon {
    /* @ngInject */
    constructor($element ,$scope, fieldService, $timeout, WPMediaFrameService, postAttachments, cacheService) {
        super();
        this.socialsSettings = {
            pos:[]
        };
        this.showContent = true;
        this.slideAttach = 0;
        this.socialsButtonShow = [];
        this._$scope = $scope;
        this.imageData=[];
        this.postAttachments = postAttachments;
        this._$timeout = $timeout;
        this.post = this.global.post;
        this.socialsSettings.pos[this.social.type] = 0;
        this._fieldService = fieldService;
        this.cache= cacheService;
        this.media = this.global.messageComposeMedia.media;
        this.mediaUrl='';
        this.wpService = WPMediaFrameService;
        this._loadingImg = false;
        this.editableAdditional = ( 'google_plus' != this.social.type );
        this.initScopeWatch();
        this.socialsButtonShow = [];
        this.global.globalSettings['show_google_plus'] = (this.global.globalSettings['compose_media_google_plus']) ? 0: 1   ;
        this.global.globalSettings['show_facebook'] = (this.global.globalSettings['compose_media_facebook']) ? 0: 1   ;
        this.global.globalSettings['show_twitter'] = 0;
        this.global.globalSettings['show_linkedin'] = 0;
        this.global.globalSettings['cache'] = [];
        this.composeSocial = [];
        this.slider = new AttachmentsSlider( $element, this.message, postAttachments, cacheService, this.social.type );
        this.slider.initForSocials(this.social.type);
        this.slider.init();
        this.slider.setCustomImageCurrentPosition();
        this.slider.setCustomImageCurrentPositionForSocials(this.cache.get('current_url_'+this.social.type));
        if(this.global.globalSettings['compose_media_'+this.social.type]){
            this.composeSocial[this.social.type] = this.global.globalSettings['compose_media_'+this.social.type]
            this.showButton(this.social.type);
        }

        if (this.social.type == 'facebook') {
            this.cache.set(this.message.fields['description'], 'description_old_facebook');
            this.cache.set(this.message.fields['title'] ,'title_old_facebook');
        }
    }

    $onInit() {

    }

    facebookMetaDisabled (socialType) {

        if(socialType != 'facebook' || this.cache.get('facebook_change_meta'))
        {
            return false;
        }

        if( this.message.fields['description']) {
            this.message.fields['description'] =  this.cache.get('description_old_facebook');
        } else {
            this.message.fields['description'] = '';
        }

        if( this.message.fields['title']) {
            this.message.fields['title'] =  this.cache.get('title_old_facebook');
        } else {
            this.message.fields['title'] = '';
        }
       return true;
    }
    setImage(e) {

        this.slideAttach = 1;
        e.preventDefault();
        this.wpService
            .setAjaxHandler({
                request: this._imageRequest.bind(this),
                success: this._setImageData.bind(this),
            })
            .open();
    }

    _imageRequest(imageId) {
        this._loadingImg = true;
        if(this.post.type == 'attachment' || this.slideAttach == 0){
            return this.postAttachments.attachMedia( imageId, this.social.type );
        }
        this.slideAttach = 0;
        return this.postAttachments.attachMediaForSlides( imageId, this.social.type );
}

    setLoadingClass() {
        return ( true === this._loadingImg ) ? 'loading' : '';
    }

    _setImageData(data) {
        if(this.social.type =='linkedin') return;
        if(this.post.type == 'attachment') {
            this.media = data;
            this._loadingImg = false;
        } else {
            var cacheMessAtach = [];
            var bool = false;
            if(this.cache.get('message_attachments_for_socials_'+this.social.type).length > 0) {
                bool =true;
            }
            var arrAtach = angular.copy(this.cache.get('message_attachments_for_socials_'+this.social.type));
            for(var i=0; i < data.length; i++){
                if(this.cache.get('message_attachments_for_socials_'+this.social.type).indexOf(data[i][this.social.type][this.social.type]['medium_thumbnail_url']) == -1){
                    bool = true;
                    this.slider.mediaUrl = data[i][this.social.type][this.social.type]['medium_thumbnail_url'];
                    arrAtach.unshift(data[i][this.social.type][this.social.type]['medium_thumbnail_url'])
                    this.cache.set(data[i][this.social.type][this.social.type]['medium_thumbnail_url'], 'current_url_'+this.social.type);
                    this.slider.slidesForSocials = arrAtach;

                }


            }
            this.cache.set(arrAtach,'message_attachments_for_socials_'+this.social.type);
            if(bool)
            this.slider.setCustomImageCurrentPosition();
            this.slider.initForSocials(this.social.type);

            this._loadingImg = false;
        }
    }

    _onSetPostThumbnail(e, thumbId) {
        if (this.media && this.media.medium_thumbnail_url)
            return;

        this.postAttachments.attachMedia(thumbId, this.social.type)
            .then(function (data) {
                this._setImageData(data);
            });
    }
    afterLoad() {
        if ('google_plus' == this.type)
            this.doAutocomplete();
    }

    doAutocomplete() {
        this.setLoacalValueFromGlobal('description', 'content');
        this.setLoacalValueFromGlobal('title', 'title');
    }

    showButton(socialType) {
        this.social.type = socialType;
        if (this.socialsButtonShow[socialType]) {
            this.global.globalSettings['show_' + socialType] = 1;
            this.socialsButtonShow[socialType] = false;
        } else {
            this.global.globalSettings['show_' + socialType] = 0;
            this.socialsButtonShow[socialType] = true;
        }
    }
    setCustomImage( e ) {
        e.preventDefault();

        this.wpService
            .setHandler( this._attachMediaImage.bind( this ) )
            .open();
    }
    _attachMediaImage( data ) {
        this.message.fields['custom_image'] = data.url;
        this.slider.addCustomImage( data.url );
        this.slider.setCustomImageCurrentPosition();
        this.slider.initForSocials(this.social.type);

        this._$scope.$apply();
    }
    getName( name ) {
        return this._fieldService.getMessageName( this.social, this.index, name );
    }

    getFieldId( name ) {
        return this._fieldService.getMessageId( this.social, this.index, name );
    }
    _onUpdatePostThumbnail( e, thumbUrl ) {
        if ( thumbUrl ) {
            this.slider.addPostThumbnail( thumbUrl );
            this.slider.setThumbnailCurrentPosition();
            this.slider.init();
        } else {
            this.slider.removePostThumbnail();
            this.slider.init();
            this._$scope.$apply();
        }
    }

    setCustomImageData( data ) {
        this.message.fields['custom_image'] = data.medium_thumbnail_url;
        this.message.fields['custom_image_filename'] = data.filename;
    }
}

export default {
    bindings: {
        index: '<',
        message: '<',
        social: '<',
        global: '='
    },
    template: sfPostFormTmpls.message,
    controller: Message
}