import MessageCommon from './message.class';
import AttachmentsSlider from './attachments/attachments-slider.class';

class MessageTwitter extends MessageCommon {
	/* @ngInject */
	constructor( $element, $timeout, $scope, fieldService, WPMediaFrameService, postAttachments, cacheService ) {
		super();
        this.slideAttach = 0;
		this._$scope       = $scope;
		this._$timeout     = $timeout;
        this.cache = cacheService;
		this._fieldService = fieldService;
		this.socialsButtonShow =[];
		this._$element = $element;
		this.prevValue = '';
		this.wpService = WPMediaFrameService;
		this._loadingImg = false;
		this.post  = this.global.post;
        this.composeSocial = [];
        this.messageAutocomplite = '';
        this.slider = new AttachmentsSlider( $element, this.message, postAttachments, cacheService, 'twitter' );
        this.slider.initForSocials(this.social.type);
        this.slider.init();
        this.slider.setCustomImageCurrentPosition();
        this.slider.setCustomImageCurrentPositionForSocials(this.cache.get('current_url_'+this.social.type));
        this.numbertweetLink = 257;
        this.numbertweet = 280;
        this.tweet = new this.tweetFactory(this.numbertweet);
        this.max = 0
        this.thisValueD = 0;
        this.tweetLink = new this.tweetFactory(this.numbertweetLink);
      if(this.global.globalSettings['compose_media_'+'twitter']){
          this.composeSocial['twitter'] = this.global.globalSettings['compose_media_'+'twitter']
          this.showButton('twitter');
      }
		if( this.post &&  this.post.formId) {
			this.contextShow = function () {
				return false;
			}
		} else {
			this.contextShow = function () {
				return true;
			}
		}
		this.media = this.global.messageComposeMedia.media;
		this.socialType = '';
		this.postAttachments = postAttachments;
		this.editableAdditional = ( 'google_plus' != 'twitter' );
		this.initScopeWatch();
	}
	$onInit() {
	    let self = this;
        this._$scope.messageAutocomplite = this.message;
        this._$scope.$watch( "messageAutocomplite.fields['message']", () => {
            var d;
            if(self.socialsButtonShow['twitter']){
                d = self.tweetCount();
                self.thisValueD =d.tweetLength;
                self.max = self.numbertweet - self.thisValueD;
            self.message.fields['message'] = d.newstr;
             self.message.fields['message_postfix'] = d.posfixTextStr;
        } else {
                d = self.tweetCountLink()
                self.thisValueD =d.tweetLength;
                self.max = self.numbertweetLink - self.thisValueD;
                self.message.fields['message'] = d.newstr;
                 self.message.fields['message_postfix'] = d.posfixTextStr;
        }
    });

        this._$scope.$watch( "messageAutocomplite.fields['message_postfix']", () => {
            var d;
        if(self.socialsButtonShow['twitter']){
            d = self.tweetCount();
            self.thisValueD =d.tweetLength;
            self.max = self.numbertweet - self.thisValueD;
            self.message.fields['message'] = d.newstr;
             self.message.fields['message_postfix'] = d.posfixTextStr;
        } else {
            d = self.tweetCountLink()
            self.thisValueD =d.tweetLength;
            self.max = self.numbertweetLink - self.thisValueD;
            self.message.fields['message'] = d.newstr;
            self.message.fields['message_postfix'] = d.posfixTextStr;
        }
    });

	}
	tweetFactory(maxWeiht){

        function TwitterCharacterUtil(maxWeiht){
            var twitter_conf = {
                "version": 2,
                "maxWeightedTweetLength": maxWeiht,
                "scale": 100,
                "defaultWeight": 200,
                "transformedURLLength": 23,
                "ranges": [
                    {
                        "start": 0,
                        "end": 4351,
                        "weight": 100
                    },
                    {
                        "start": 8192,
                        "end": 8205,
                        "weight": 100
                    },
                    {
                        "start": 8208,
                        "end": 8223,
                        "weight": 100
                    },
                    {
                        "start": 8242,
                        "end": 8247,
                        "weight": 100
                    }
                ]
            };
            var NORMALIZE = false;
            if(typeof twitter_conf !== 'object'){
                console.error('Twitter config is undefined');
            };
            if(typeof String.prototype.normalize === 'function'){
                NORMALIZE = true;
            };
            var scale = twitter_conf.scale;
            var ranges = twitter_conf.ranges;
            var urlLength = twitter_conf.transformedURLLength;
            var maxWeightedTweetLength = twitter_conf.maxWeightedTweetLength;

            return {
                getTweetData: getTweetData
            }

            function getTweetData(str, postfix){
                var d = {links: [],tweetLength: 0, newstr: '' , postfixLength:0, posfixTextStr: ''}
                if(!str && !postfix) return d;
                if(NORMALIZE){
                    if(str){
                        str = str.normalize('NFC');
                    }
                    if(postfix){
                        postfix = postfix.normalize('NFC');
                    }

                }
                if( typeof postfix != 'undefined') {
                    postfix.split('').forEach(function (chr) {
                        d.tweetLength += _getCharWeight(chr);
                        if (d.tweetLength <= maxWeightedTweetLength) {
                            d.posfixTextStr = d.posfixTextStr + chr;
                        }
                    });
                    var arrMpostf = postfix.match(/(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|[\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|[\ud83c[\ude32-\ude3a]|[\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g);
                    if (null != arrMpostf  && arrMpostf  instanceof Array) {
                        if (arrMpostf.length != 0) {
                            d.tweetLength = d.tweetLength - 2 * arrMpostf .length;
                        }
                    }
                }
                if(str) {

                    var links = twttr.txt.extractUrls(str);

                    var filterLinksRegex = new RegExp(links.join('|'), 'g');
                    var newStr = str;
                    newStr.split('').forEach(function (chr) {
                        d.tweetLength += _getCharWeight(chr);
                        if (d.tweetLength <= maxWeightedTweetLength) {
                            d.newstr = d.newstr + chr;
                        }

                    });
                }
                if(str) {

                    d.links = links;

                    var arrM = str.match(/(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|[\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|[\ud83c[\ude32-\ude3a]|[\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g);

                    if (null != arrM && arrM instanceof Array) {
                        if (arrM.length != 0) {
                            d.tweetLength = d.tweetLength - 2 * arrM.length;
                        }
                    }
                }
                return d;
            };
            function _getCharWeight(chr){
                var weight = twitter_conf.defaultWeight;
                var n = chr.charCodeAt(0);
                for(var i = 0; i < ranges.length; i++){
                    var range = ranges[i];
                    if(n >= range.start && n <= range.end){
                        weight = range.weight;
                        break;
                    };
                }
                return weight/scale;
            };

        }

        return new TwitterCharacterUtil(maxWeiht);
	}
    tweetCount(){
        return  this.tweet.getTweetData(this.message.fields['message'], this.message.fields['message_postfix']);
    }

    tweetCountLink() {
        return  this.tweetLink.getTweetData(this.message.fields['message'], this.message.fields['message_postfix']);
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
	_attachMediaImage( data ) {
		this.message.fields['custom_image'] = data.url;
		this.slider.addCustomImage( data.url );
		this.slider.setCustomImageCurrentPosition();
		this.slider.initForSocials('twitter');

		this._$scope.$apply();
	}
	_imageRequest(imageId) {
		this._loadingImg = true;
		if(this.post.type == 'attachment' || this.slideAttach == 0){
			return this.postAttachments.attachMedia( imageId, 'twitter' );
		}
		this.slideAttach = 0;
		return this.postAttachments.attachMediaForSlides( imageId, 'twitter' );
	}

	setLoadingClass() {
		return ( true === this._loadingImg ) ? 'loading' : '';
	}

	_setImageData(data) {
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

		this.postAttachments.attachMedia(thumbId, 'twitter')
			.then(function (data) {
				this._setImageData(data);
			});
	}

    changeModel () {
        let countSymbolObject = this.tweetCount(this.message.fields['message']);
        this.message.fields['message'] = countSymbolObject.newstr;

    }
	inputAutocomplete () {
        this.message.fields['message'];
	}
	init() {
		this._$msg   = this._$element.find( 'textarea' );
		let $postfix = this._$element.find( 'input' );
		this._$msg.sfMaxlength({
			maxCharacters : 280,
			events : [ 'change' ],
			statusText : '<span>characters left</span>',
			twitterText : true,
			testArg: 125,
			secondField: $postfix
		});

		this._$scope.globalSettings = this.global.globalSettings;
		this._$scope.$watch( 'globalSettings.compose_media', () => {
			this._$msg.trigger( 'keyup' );
		});
	}
	showButton(socialType, ctrl) {
		this.socialType = 'twitter';
		if(this.socialsButtonShow[socialType]){
            this.thisValueD = this.tweetCountLink(this.message.fields['message']).tweetLength;
            this.max = this.numbertweetLink - this.thisValueD;
            this.message.fields['message'] = this.tweetCountLink(this.message.fields['message']).newstr;
            this.socialsButtonShow[socialType] = false;
		}else {
            this.thisValueD =this.tweetCount(this.message.fields['message']).tweetLength;
            this.max = this.numbertweet - this.thisValueD;
            this.message.fields['message'] = this.tweetCount(this.message.fields['message']).newstr;
			this.socialsButtonShow[socialType] = true;
		}
	}
	doAutocomplete() {
        return;
	}
}

export default {
	bindings: {
		index: '<',
		message: '<',
		social: '<',
		global: '='
	},
	template: sfPostFormTmpls.messageTwitter,
	controller: MessageTwitter
}