export default class AttachmentsSlider
{
	constructor( $parent, message, postAttachments, cacheService, socialType, $scope ) {
		this.posSl = 0;
		this.contentImages = cacheService.get( 'messageAttachments' );
		if(cacheService.get( 'content_' +socialType)) {
            this.contentImages.push(cacheService.get( 'content_' + socialType));
		}

		this.contentImagesForSocials = cacheService.get( 'messageAttachmentsForSocials' );
		this.slides   = [];
		this.slidesForSocials   = [];
		this._message = message;
		this._current = cacheService.get( 'current_url_' +socialType);
		this._postAttachments = postAttachments;
		this.cacheRemoveKeyThubn = false;
		this._cache = cacheService;
        this.socialType = '';
		if(socialType) {
			this.socialType = socialType;
		}
		this.scope = $scope;
		this._post = this._cache.get( 'post' );
        this.pos = 0;
		this._customImage   = this._current;
		this._customImageForSocials   = '';
		this.contentImagesForSocialsBody   = [];
		this.mediaUrl   = '';
		this._postThumbnail = this._post.thumbnail;

		this.$parent = $parent;

		this.stopLoading();
        if(this.socialType == 'facebook') {
            this._cache.set(this.contentImages, 'thumbnail_old_facebook');
            this._cache.set(this._current, 'current_old_facebook');
            this._cache.set(this._customImage, 'custom_image_old_facebook');
        }
		angular.element( document ).on( 'sf-get-slides', this._onGetContentImages.bind( this ) );
        angular.element( document ).on( 'sf-update-post-thumbnail', this._onUpdatePostThumbnail.bind( this ) );
        this.init();
	}

    _onUpdatePostThumbnail( e, thumbUrl ) {
        if (thumbUrl) {
            this.addPostThumbnail(thumbUrl);
            this.setThumbnailCurrentPosition();
            this.init();
            this.initForSocials(this.socialType);
        }
    }
	addCustomImage( url ) {
		this._customImage = url;
	}
	addCustomImageForSocials( url ) {
		this._customImageForSocials = url;
	}
	addPostThumbnail( url ) {
		this._post.thumbnail = url;
		this._postThumbnail  = url;
		if(this.contentImagesForSocialsBody.indexOf(this.mediaUrl) != -1){
            this.mediaUrl = url;
        }


	}
	addPostThumbnailForSocials( url, socialType ) {
		this._cache.set(this._postThumbnail, 'thumbnail_for_socials_'+socialType);
		var posLoad = this._cache.get( 'compose_media_pos_' + socialType);
		if(socialType != 'linkedin') {


			if (posLoad !== '') {
				posLoad = posLoad + 1;
				this._cache.set(posLoad, 'compose_media_pos_' + socialType);
			}
			;
			this._cache.get('message_attachments_for_socials_' + socialType).push(this._postThumbnail);
			this.slidesForSocials.push(this._postThumbnail);
		}
	}

	removePostThumbnail() {
        if ( ! this._postThumbnail )
            return;

        let index = this.slides.indexOf( this._postThumbnail );

        if ( -1 === index )
            return;

        this.slides.splice( index, 1 );

        this.addPostThumbnail( '' );
	}

	removePostThumbnailForSocials(socialType) {
		if ( ! this._postThumbnail )
			return;

		let index =this._cache.get('message_attachments_for_socials_' + socialType).indexOf( this._postThumbnail );
        this._cache.get('message_attachments_for_socials_' + socialType).splice( index, 1 );
        var slides =   this._cache.get('message_attachments_for_socials_' + socialType);
        if ( -1 === index )
            return;

        if(slides.length )
		{
			if(slides[index]) {
                this.mediaUrl = slides[index];
			} else{
                this.mediaUrl = slides[index -1]
			}
		} else {
			this.mediaUrl = '';
		}

	}

	setThumbnailCurrentPosition() {
		this._current = this._postThumbnail;
	}

	setCustomImageCurrentPosition() {
		this._current = this._customImage;
	}
	setCustomImageCurrentPositionForSocials(url) {
		this.mediaUrl = url;
	}
	_compileSlides() {
		let slides = angular.copy( this.contentImages );
		if ( this._customImage && -1 === slides.indexOf( this._customImage ) ) {
			slides.push( this._customImage );
		}

		if ( this._postThumbnail && -1 === slides.indexOf( this._postThumbnail ) ) {
			slides.push( this._postThumbnail );
		}

		this.slides = slides;
	}

    _compileSlidesForSocials(socialType) {
		let slides  = angular.copy(this._cache.get('message_attachments_for_socials_'+socialType));
		let posLoad;
		if(typeof slides =='undefined'){
			slides =[];
			this._cache.set([], 'message_attachments_for_socials_'+socialType);
		}
		if ( this._postThumbnail && -1 === slides.indexOf( this._postThumbnail )) {
			this._cache.set(this._postThumbnail, 'thumbnail_for_socials_'+socialType);
			if(socialType == 'linkedin') {
				posLoad = '';
			}
			if(posLoad !==''){
				posLoad = posLoad +1;
				this._cache.set( posLoad , 'compose_media_pos_'+socialType);
			}
            slides.push(this._postThumbnail);
			this._cache.set(slides,'message_attachments_for_socials_'+socialType);
			this.slidesForSocials = slides;
            posLoad = slides.indexOf(this.mediaUrl);
            if( posLoad === -1) {
                posLoad = 0;
            }
			if (this.contentImagesForSocialsBody.indexOf(this.mediaUrl) != -1) {
                this.mediaUrl = this._postThumbnail;
                posLoad = slides.indexOf(this._postThumbnail)
            } else {
                posLoad = slides.indexOf(this.mediaUrl)
            }
			this.pos = posLoad;
            this.setCustomImageCurrentPositionForSocials(this.mediaUrl);

		}

		if(socialType != 'linkedin') {
            let noRemovedElement = [];
			if (this.cacheRemoveKeyThubn !== false) {
                slides.splice(this.cacheRemoveKeyThubn,1);
		    	this._cache.set(slides,'message_attachments_for_socials_' + socialType);
				if (this.cacheRemoveKeyThubn - 1 > -1) {
					this.mediaUrl = slides[this.cacheRemoveKeyThubn - 1]
					this.mediaUrl = slides[this.cacheRemoveKeyThubn - 1]
					this._cache.set( this.cacheRemoveKeyThubn -1 , 'compose_media_pos_'+socialType);
					this.pos = posLoad = this.cacheRemoveKeyThubn -1;
				}
				else{
					this.mediaUrl = slides[0]
					this._cache.set( 0 , 'compose_media_pos_'+socialType);
					this.pos = posLoad = 0;
				}
				this.cacheRemoveKeyThubn = false;
                this.slidesForSocials.splice(this.cacheRemoveKeyThubn,1);
                this._cache.set(slides,'message_attachments_for_socials_' + socialType);
			}
		}
		this.pos = slides.length -1;
		if (this.pos === -1) {
			this.pos = 0;
		}

        if(posLoad !== false && posLoad!='' && typeof posLoad != 'undefined'){
			this.pos = parseInt(posLoad);
			this._cache.set(false, 'sf_position_'+socialType)
		}
        this.slidesForSocials = slides;
		this.pos = slides.indexOf(this.mediaUrl);
		if(this.pos == -1) {
			this.pos = 0;
		}
		this.mediaUrl = slides[slides.indexOf(this.mediaUrl)]
    }

	init() {
		this._compileSlides();

		if ( 0 == this.slides.length )
			return;

		let start = this._getCurrentPosition();

		this._setCurrentByPosition( start );
	}

	initForSocials(socialType) {
		this._compileSlidesForSocials(socialType);
		if ( 0 == this.slidesForSocials.length )
			return;
		let start = this._getCurrentPositionForSocials(socialType);
        if(!start){
        	if(!this._cache.get('compose_media_pos_'+socialType))
        	start =  this.pos;
			else
				start =  this._cache.get( 'compose_media_pos_'+socialType);
		}
		this._setCurrentByPositionForSocials( start );
	}
	
	refreshSlides( e ) {
		e.preventDefault();

		this.startLoading();
		this._postAttachments.findImagesFromPostContent( );
	}
	
	showSlide( index, slides, type, currentUrl ) {
		if(this._cache.get('current_url_'+type)) {
			this._current = this._cache.get('current_url_'+type);
            this._message.fields['image']  = this._cache.get('current_url_'+type);
		}
		return ( index == this._getCurrentPosition() );
	}
    showSlideForSicials( index, socialType, currentUrl) {
        return ( index === parseInt(this._getCurrentPositionForSocials(socialType)) );
    }
	prev(type) {
		let pos = this._getCurrentPosition();

		if ( 0 == pos )
			pos = this.slides.length;

		pos--;
        if(type !='twitter') {
        	var t = this.slides[pos];
            console.log(this._current);
            this._cache.set( this.slides[pos], 'current_url_'+type);
        }
		this._setCurrentByPosition( pos );
	}
	
	next(type) {
		let pos = this._getCurrentPosition();

		pos++;

		if ( pos == this.slides.length )
			pos = 0;

        if(type !='twitter') {
            this._cache.set( this.slides[pos], 'current_url_'+type);
        }

		this._setCurrentByPosition( pos );
	}
	
	prevForSocial(socialType) {

		let pos = this._getCurrentPositionForSocials(socialType);

		if ( 0 == pos )
			pos = this.getsLiderForSocial().length;
		pos--;
		this._setCurrentByPositionForSocials( pos );
	}
	
	nextForSocial(socialType) {
		let pos = this._getCurrentPositionForSocials(socialType);
        pos++;
		if(this.slidesForSocials.length == pos) {
			pos = 0;
        }
		this._setCurrentByPositionForSocials( pos );
	}

	setLoadingClass() {
		return this._loading == true ? 'loading' : '';
	}
	
	_setCurrentByPosition( pos ) {
		let src = this.slides[ pos ];
		this._message.fields['image'] = src;
		this._current = src;
	}
    getsLiderForSocial(){
      return  this._cache.get('message_attachments_for_socials_'+this.socialType)
	}
    _setCurrentByPositionForSocials( pos ) {
        let src = this.slidesForSocials[ pos ];
        this._message.fields['image'] = src;
        this.mediaUrl = src;
		this.pos = pos;
    }
	_getCurrentPosition() {
		if(this.slides.length === 0 && this._current){
			this.slides.push(this)
		}
		let pos = this.slides.indexOf( this._current );
        this.posSl = ( -1 === pos ) ? 0 : pos;
		return ( -1 === pos ) ? 0 : pos;
	}
    _getCurrentPositionForSocials(socialType) {
        if(this.slidesForSocials.length === 0 && this.mediaUrl){
            this.slidesForSocials.push(this)
        }
        let pos = this.slidesForSocials.indexOf( this.mediaUrl );

        return ( -1 === pos ) ? 0 : pos;
    }
	_onGetContentImages( e, images ) {
		 e.stopPropagation();
		this.contentImages = images;
		console.log(images);
		if(this.socialType && this.socialType !='linkedin'){

			this.pos = images.length -1;
			var thisImage = this._cache.get('message_attachments_for_socials_'+this.socialType);
            thisImage = thisImage.concat(images[images.length - 1]);
            if(typeof this.mediaUrl == 'undefined' || this.mediaUrl.length === 0) {
               this.mediaUrl = thisImage[thisImage.length - 1];
            } else if (images.indexOf(this.mediaUrl)!=-1) {
                this.mediaUrl = thisImage[thisImage.length - 1];
			}
            this._cache.set(thisImage.indexOf(this.mediaUrl),'compose_media_pos_'+this.socialType);
            this._cache.set(thisImage, 'message_attachments_for_socials_'+this.socialType);
            this.contentImagesForSocialsBody = images;
			this.initForSocials(this.socialType);
		}
		this._cache.set( images, 'messageAttachments' );
		if(images.length!=0){
                this._current = images[images.length - 1];
		}
        this.init();
		this.stopLoading();
	}

	startLoading() {
		this._loading = true;
	}

	stopLoading() {
		this._loading = false;

	}
}