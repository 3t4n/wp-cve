jQuery(document).ready(function($){
	
	/*Set up any tooltips in the modal*/
	$('.mb-tooltip').tooltipster({
		position: 'top',//top-right or top-left
		theme: 'tooltipster-mb',
		delay: 0,
		speed: 300
	});
		
	MBModal = function(save_function) {
		var self = this;
		
		//do this async of initializing MBModal object - otherwise every page load is delayed
		self.koBindingsApplied = false;
		self.applyBindings = function(){
			ko.applyBindings(self, $('#mb-modal')[0]);
			self.koBindingsApplied = true;
		}
		
		self.saveFunction = save_function;
		self.isVisible = ko.observable(false);
		self.isUpdatingExistingButton = ko.observable(false);
		self.isHidingColorPicker = ko.observable(false);
		
		self.selectingStyle = ko.observable(false);
		self.addingIcon = ko.observable(false);
		
		self.viewingMoreOptions = ko.observable(false);
		
		self.gettingMoreStyles = ko.observable(false);
		
		self.faSearchText = ko.observable('');
		self.faSearchText.subscribe(function(searchText){
			
			var iconListHtml = '<ul>';
			
			var filteredIcons = _.filter(self.faIcons, function(icon){
				return icon.indexOf(searchText) > -1;
			});

			_.each(filteredIcons, function(icon){
				iconListHtml += '<li data-icon="' + icon + '"><i class="fa fa-fw fa-' + icon + '"></i></li>';
			});

			iconListHtml += '</ul>';

			$(".mb-modal-icon-selector").html(iconListHtml);

			$(".mb-modal-icon-selector li").on('click', function(e){
				var iconElem = $(this);
				
				self.addIcon(iconElem.attr('data-icon'));
				
				return false;
			});
		});
		
		self.addingIcon.subscribe(function(){
			self.faSearchText('');
			
			//fire this so search results (icons) repopulate
			self.faSearchText.valueHasMutated();
			
			setTimeout(function(){
				$('#mb-icon-search-input').focus();
			}, 50);
		});
		
		self.settings = {
			text: ko.observable(''),
			link: ko.observable(''),
			style: ko.observable(''),
			
			new_tab: ko.observable(false),
			
			color: ko.observable('#2bc470'),
			
			size: ko.observable('default'),
			corners: ko.observable('default'),
			text_style: ko.observable('default')
		};
		
		self.settingsAreValid = ko.computed(function(){
			if(self.settings.text() != '' && self.settings.link() != ''){
				return true;
			}
			else{
				return false;
			}
		});
		
		self.fixLinkTextIfNecessary = function(){
			var linkText = self.settings.link();
			
			//if no link text, don't modify
			if(linkText == ''){
				return;
			}
			
			/*If a mailto, tel, relative, or # link, don't add http or https at the front of the link*/
			if(	linkText.indexOf('mailto:') == 0 ||
					linkText.indexOf('tel:')  == 0 ||
					linkText.indexOf('call:') == 0 ||
					linkText.indexOf('skype:') == 0 ||
					linkText.indexOf('sms:') == 0 ||
					linkText.indexOf('#') == 0 ||
					linkText.indexOf('//') == 0 ||
					linkText.indexOf('/') == 0
			){
				//do nothing - leave the link the way the user created it
			}
			else{
				//make sure the link is an absolute link...
				//if the link is an absolute link, check for http:// or https:// - if we don't have either...add http:// at beginning of link
				if(!(linkText.indexOf('http://') > -1 || linkText.indexOf('https://') > -1 )){
					self.settings.link('http://' + self.settings.link());
				}
			}
			
		}
		
		self.buttonTextAsHtml = ko.computed(function(){
			if(!self.settings.text() || self.settings.text() == ''){
				return '<i class=\'fa fa-rocket\'></i>&nbsp; Button Text';
			}
			else{
				return self.settings.text().replace(/{{(.*?)}}/g, '&nbsp;<i class="fa fa-$1"></i>&nbsp;');
			}
		});
		
		self.userHasSelectedStyle = ko.computed(function(){
			return self.settings.style() != '';
		});
		
		self.previewStyle = ko.observable('');
		self.previewStyleClasses = ko.computed(function(){
			//TODO write this method
		});
		self.previewingButtonStyle = ko.computed(function(){
			if(self.previewStyle() != ''){
				return self.previewStyle();
			}
			else{
				return self.settings.style();
			}
		});
		self.showStyleSelection = function(){
			if(self.isHidingColorPicker()){
				return;
			}
			
			self.selectingStyle(true);
		}
		self.setPreviewStyle = function(style){
			self.previewStyle(style);
		}
		self.clearStylePreview = function(){
			self.previewStyle('');
		}
		
		self.selectStyle = function(style){
			self.settings.style(style);
			self.selectingStyle(false);
		}
		
		self.addIcon = function(iconName){
			
			//insert at beginning unless iconName contains the word arrow (presumably for right arow)
			if(iconName.indexOf('right') > -1){
				
				//insert space before icon if didn't exist prior
				if(self.settings.text().replace(/\s*$/,'').length == self.settings.text().length){
					self.settings.text(self.settings.text() + ' ');
				}
				
				//insert icon by appending to end of settings.text string
				self.settings.text(self.settings.text() + '{{' + iconName + '}}');
			}
			else{
				
				//insert space after icon if didn't exist prior
				if(self.settings.text().replace(/\s*$/,'').length == self.settings.text().length){
					self.settings.text(' ' + self.settings.text());
				}
				
				//insert icon by appending to beginning of settings.text string
				self.settings.text('{{' + iconName + '}}' + self.settings.text());
			}
			
			//add icon to text
			self.addingIcon(false);
		}
		
		self.show = function(settings){
			
			//if bindings not applied yet, apply bindings to modal
			if(!self.koBindingsApplied){
				self.applyBindings();
			}
			
			if(settings){
				self.isUpdatingExistingButton(true);
				self.viewingMoreOptions(true);
				
				self.settings.text(settings.text);
				self.settings.link(settings.link);
				self.settings.style(settings.style);
				
				self.settings.new_tab(settings.new_tab);
				
				//convert rgb to hex before saving if required
				if(settings.color.indexOf('rgb') > -1){
					
					rgb = settings.color.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
					
					settings.color = '#' + $.colpick.rgbToHex({
						r: parseInt(rgb[1]),
						g: parseInt(rgb[2]),
						b: parseInt(rgb[3])
					});
					
				}
				self.settings.color(settings.color);
				
				self.settings.size(settings.size);
				self.settings.corners(settings.corners);
				self.settings.text_style(settings.text_style);
			}
			else{
				self.settings.text('');
				self.settings.link('');
				self.settings.new_tab(false);
				self.viewingMoreOptions(false);
				
				self.isUpdatingExistingButton(false);
			}
			
			self.isVisible(true);
			
			self.bindColorPickers();
		}
		
		self.bindColorPickers = function(){
			
			var colpickOptions = {
				showEvent: 'focus',
				layout: 'hex',
				submit: 0,
				livePreview: false,
				colorScheme: 'dark',
				onBeforeShow: function(colorpicker){
					//hide all currently visible colorpickers
					$(".mb-color-picker-input").colpickHide();
					
					//add class to color picker so we can adjust styling of just our own color pickers
					$(colorpicker).addClass('mb-color-picker');
					
					//make sure we have the most up to date color
					$(this).colpickSetColor($(this).val(), true);
				},
				onChange: function(hsb,hex,rgb,el,bySetColor){
					if(!bySetColor){
						$(el).val('#' + hex.toUpperCase()).change();//need to fire .change() for knockout to register changes
					}
				},
				onHide: function(){
					
					/*Freeze UI for 100 ms while colpicker is hidden - preventing the dismiss from causing other actions*/
					self.isHidingColorPicker(true);
					setTimeout(function(){
						self.isHidingColorPicker(false);
					}, 100);
					
				}
			}
			
			//bind colpick (color picker) to input field for each color option
			$("#mb-primary-color-input").colpick(colpickOptions).keyup(function(){
				$(this).colpickSetColor(this.value);
			});
			
		}
		
		self.purchasePro = function(){
			window.open('https://mangobuttons.com/pricing', '_blank');
		}
		
		self.hide = function(){
			
			if(self.isHidingColorPicker()){
				return;
			}
			
			self.isVisible(false);
			
			self.selectingStyle(false);
			self.gettingMoreStyles(false);
			self.addingIcon(false);
		}
		
		self.insertButton = function(){
			
			if(!self.settingsAreValid()){
				return;
			}
			
			self.fixLinkTextIfNecessary();
			
			self.saveFunction(ko.toJS(self.settings));
			
			self.hide();
		}
		
		self.destroy = function(){
			
		}
		
		self.faIcons = [
		'angle-double-down', 'angle-double-left', 'angle-double-right', 'angle-double-up', 
		'angle-down', 'angle-left', 'angle-right', 'angle-up', 'arrow-circle-down', 
		'arrow-circle-left', 'arrow-circle-o-down', 'arrow-circle-o-left', 'arrow-circle-o-right', 'arrow-circle-o-up', 
		'arrow-circle-right', 'arrow-circle-up', 'arrow-down', 'arrow-left', 'arrow-right', 
		'arrow-up', 'arrows-alt', 'caret-down', 'caret-left', 'caret-right', 
		'caret-up', 'chevron-circle-down', 'chevron-circle-left', 'chevron-circle-right', 'chevron-circle-up', 
		'chevron-down', 'chevron-left', 'chevron-right', 'chevron-up', 'hand-o-down', 
		'hand-o-left', 'hand-o-right', 'hand-o-up', 'long-arrow-down', 'long-arrow-left', 
		'long-arrow-right', 'long-arrow-up', 'backward', 'compress', 'eject', 
		'paper-plane', 'paper-plane-o', 'paw', 'pencil', 'pencil-square', 
		'pencil-square-o', 'phone', 'phone-square', 'photo', 'picture-o', 
		'plane', 'plus', 'plus-circle', 'plus-square', 'plus-square-o', 
		'power-off', 'print', 'puzzle-piece', 'qrcode', 'question', 
		'question-circle', 'quote-left', 'quote-right', 'random', 'recycle', 
		'refresh', 'remove', 'reorder', 'reply', 'reply-all',
		'expand', 'fast-backward', 'fast-forward', 'forward', 'pause', 
		'play', 'play-circle', 'play-circle-o', 'step-backward', 'step-forward',
		'angellist', 'area-chart', 'at', 'bell-slash', 'bell-slash-o', 'bicycle', 
		'binoculars', 'birthday-cake', 'bus', 'calculator', 'cc', 
		'cc-amex', 'cc-discover', 'cc-mastercard', 'cc-paypal', 'cc-stripe', 
		'cc-visa', 'copyright', 'eyedropper', 'futbol-o', 'google-wallet', 
		'ils', 'ioxhost', 'lastfm', 'lastfm-square', 'line-chart', 
		'meanpath', 'newspaper-o', 'paint-brush', 'paypal', 'pie-chart', 
		'plug', 'shekel', 'sheqel', 'slideshare', 'soccer-ball-o', 
		'toggle-off', 'toggle-on', 'trash', 'tty', 'twitch', 
		'wifi', 'yelp', 'adjust', 'anchor', 'archive', 'unlink', 
		'arrows', 'arrows-h', 'arrows-v', 'asterisk', 'automobile', 
		'ban', 'bank', 'bar-chart', 'bar-chart-o', 'barcode', 
		'bars', 'beer', 'bell', 'bell-o', 'bolt', 
		'bomb', 'book', 'bookmark', 'bookmark-o', 'briefcase', 
		'bug', 'building', 'building-o', 'bullhorn', 'bullseye', 
		'cab', 'calendar', 'calendar-o', 'camera', 'camera-retro', 
		'car', 'caret-square-o-down', 'caret-square-o-left', 'caret-square-o-right', 'caret-square-o-up', 
		'certificate', 'check', 'check-circle', 'check-circle-o', 'check-square', 
		'check-square-o', 'child', 'circle', 'circle-o', 'circle-o-notch', 
		'circle-thin', 'clock-o', 'close', 'cloud', 'cloud-download', 
		'cloud-upload', 'code', 'code-fork', 'coffee', 'cog', 
		'cogs', 'comment', 'comment-o', 'comments', 'comments-o', 
		'compass', 'credit-card', 'crop', 'crosshairs', 'cube', 
		'cubes', 'cutlery', 'dashboard', 'database', 'desktop', 
		'dot-circle-o', 'download', 'edit', 'ellipsis-h', 'ellipsis-v', 
		'envelope', 'envelope-o', 'envelope-square', 'eraser', 'exchange', 
		'exclamation', 'exclamation-circle', 'exclamation-triangle', 'external-link', 'external-link-square', 
		'eye', 'eye-slash', 'fax', 'female', 'fighter-jet', 
		'file-archive-o', 'file-audio-o', 'file-code-o', 'file-excel-o', 'file-image-o', 
		'file-movie-o', 'file-pdf-o', 'file-photo-o', 'file-picture-o', 'file-powerpoint-o', 
		'file-sound-o', 'file-video-o', 'file-word-o', 'file-zip-o', 'film', 
		'filter', 'fire', 'fire-extinguisher', 'flag', 'flag-checkered', 
		'flag-o', 'flash', 'flask', 'folder', 'folder-o', 
		'folder-open', 'folder-open-o', 'frown-o', 'gamepad', 'gavel', 
		'gear', 'gears', 'gift', 'glass', 'globe', 
		'graduation-cap', 'group', 'hdd-o', 'headphones', 'heart', 
		'heart-o', 'history', 'home', 'image', 'inbox', 
		'info', 'info-circle', 'institution', 'key', 'keyboard-o', 
		'language', 'laptop', 'leaf', 'legal', 'lemon-o', 
		'level-down', 'level-up', 'life-bouy', 'life-buoy', 'life-ring', 
		'life-saver', 'lightbulb-o', 'location-arrow', 'lock', 'magic', 
		'magnet', 'mail-forward', 'mail-reply', 'mail-reply-all', 'male', 
		'map-marker', 'meh-o', 'microphone', 'microphone-slash', 'minus', 
		'minus-circle', 'minus-square', 'minus-square-o', 'mobile', 'mobile-phone', 
		'money', 'moon-o', 'mortar-board', 'music', 'navicon', 
		'retweet', 'road', 'rocket', 'rss', 'rss-square', 
		'search', 'search-minus', 'search-plus', 'send', 'send-o', 
		'share', 'share-alt', 'share-alt-square', 'share-square', 'share-square-o', 
		'shield', 'shopping-cart', 'sign-in', 'sign-out', 'signal', 
		'sitemap', 'sliders', 'smile-o', 'sort', 'sort-alpha-asc', 
		'sort-alpha-desc', 'sort-amount-asc', 'sort-amount-desc', 'sort-asc', 'sort-desc', 
		'sort-down', 'sort-numeric-asc', 'sort-numeric-desc', 'sort-up', 'space-shuttle', 
		'spinner', 'spoon', 'square', 'square-o', 'star', 
		'star-half', 'star-half-empty', 'star-half-full', 'star-half-o', 'star-o', 
		'suitcase', 'sun-o', 'support', 'tablet', 'tachometer', 
		'tag', 'tags', 'tasks', 'taxi', 'terminal', 
		'thumb-tack', 'thumbs-down', 'thumbs-o-down', 'thumbs-o-up', 'thumbs-up', 
		'ticket', 'times', 'times-circle', 'times-circle-o', 'tint', 
		'toggle-down', 'toggle-left', 'toggle-right', 'toggle-up', 'trash-o', 
		'tree', 'trophy', 'truck', 'umbrella', 'university', 
		'unlock', 'unlock-alt', 'unsorted', 'upload', 'user', 
		'users', 'video-camera', 'volume-down', 'volume-off', 'volume-up', 
		'warning', 'wheelchair', 'wrench', 'file', 'file-o', 
		'file-text', 'file-text-o', 'bitcoin', 'btc', 'cny', 
		'dollar', 'eur', 'euro', 'gbp', 'inr', 
		'jpy', 'krw', 'rmb', 'rouble', 'rub', 
		'ruble', 'rupee', 'try', 'turkish-lira', 'usd', 
		'won', 'yen', 'align-center', 'align-justify', 'align-left', 
		'align-right', 'bold', 'chain', 'chain-broken', 'clipboard', 
		'columns', 'copy', 'cut', 'dedent', 'files-o', 
		'floppy-o', 'font', 'header', 'indent', 'italic', 
		'link', 'list', 'list-alt', 'list-ol', 'list-ul', 
		'outdent', 'paperclip', 'paragraph', 'paste', 'repeat', 
		'rotate-left', 'rotate-right', 'save', 'scissors', 'strikethrough', 
		'subscript', 'superscript', 'table', 'text-height', 'text-width', 
		'th', 'th-large', 'th-list', 'underline', 'undo', 
		'stop', 'youtube-play', 'adn', 'android', 'apple', 
		'behance', 'behance-square', 'bitbucket', 'bitbucket-square', 'codepen', 
		'css3', 'delicious', 'deviantart', 'digg', 'dribbble', 
		'dropbox', 'drupal', 'empire', 'facebook', 'facebook-square', 
		'flickr', 'foursquare', 'ge', 'git', 'git-square', 
		'github', 'github-alt', 'github-square', 'gittip', 'google', 
		'google-plus', 'google-plus-square', 'hacker-news', 'html5', 'instagram', 
		'joomla', 'jsfiddle', 'linkedin', 'linkedin-square', 'linux', 
		'maxcdn', 'openid', 'pagelines', 'pied-piper', 'pied-piper-alt', 
		'pinterest', 'pinterest-square', 'qq', 'ra', 'rebel', 
		'reddit', 'reddit-square', 'renren', 'skype', 'slack', 
		'soundcloud', 'spotify', 'stack-exchange', 'stack-overflow', 'steam', 
		'steam-square', 'stumbleupon', 'stumbleupon-circle', 'tencent-weibo', 'trello', 
		'tumblr', 'tumblr-square', 'twitter', 'twitter-square', 'vimeo-square', 
		'vine', 'vk', 'wechat', 'weibo', 'weixin', 
		'windows', 'wordpress', 'xing', 'xing-square', 'yahoo', 
		'youtube', 'youtube-square', 'ambulance', 'h-square', 'hospital-o', 
		'medkit', 'stethoscope', 'user-md'
		];
		
		return self;
	}
	
});