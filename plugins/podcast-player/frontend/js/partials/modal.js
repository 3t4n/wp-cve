class Modal {

	/**
	 * Currently clicked list item.
	 */
	modal;

	/**
	 * Create and manager podcast player modal window.
	 * 
	 * @since 2.0 
	 */
	constructor() {

		this.settings = window.ppmejsSettings || {};
		this.mediaObj = false;
		this.msgMediaObj = false;

		// Scrolling specific
		this.bodyScrollDisabled = false;
		this.scrollPosition = 0;
		this.scrollingElem = document.scrollingElement || document.documentElement || document.body;

		// Create modal markup.
		this.setup();

		// Run methods.
		this.events();

		this.pauseMedia = this.mediaPause.bind(this);
		this.playMedia = this.mediaPlay.bind(this);
	}

	// Setup modal markup.
	setup() {
		const { ppClose, ppArrowUp, ppCloseBtnText, ppAuxModal } = this.settings;
		const close = jQuery('<button />', { class: 'pp-modal-close' }).html( ppCloseBtnText + ppArrowUp + ppClose );
		const modal = `
		<div id="pp-modal-window" class="pp-modal-window">
			<div class="pp-modal-wrapper"></div>
			${ppAuxModal}
			${close[0].outerHTML}
		</div>`;
		jQuery('body').append(modal);
		this.modal = jQuery('#pp-modal-window');
	}

	// Event handling.
	events() {
		const _this   = this;
		const wrap    = _this.modal.find('.pp-modal-wrapper');
		const auxWrap = _this.modal.find('.pp-modal-aux-wrapper');
		const auxList = auxWrap.find('.pp-modal-tabs-list');
		const auxContent = auxWrap.find('.pp-modal-tabs-content');
		this.modal.on('click', '.pp-modal-close', function() {
			const $this = jQuery(this);

			if (_this.modal.hasClass('modal-view')) {
				if (_this.mediaObj.isVideo) {
					_this.modalClose();
				} else {
					_this.modal.removeClass('modal-view').addClass('inline-view');
					_this.scrollEnable();
				}
				return;
			}
			if (!$this.hasClass('modal-closed')) {
				$this.addClass('modal-closed');
				wrap.animate({height: 'toggle'}, 400);
			} else {
				$this.removeClass('modal-closed');
				wrap.animate({height: 'toggle'}, 400);
			}
			if (auxWrap.hasClass('aux-open')) {
				_this.modal.find('.ppjs__secondary-controls').find('.toggled').removeClass('toggled');
				auxWrap.find('.selected').removeClass('selected');
				auxList.hide();
				auxContent.empty();
				auxWrap.hide().removeClass('aux-open');
			}
		});

		this.modal.on('click', '.pp-text-aux-btn', function() {
			const $this = jQuery(this);
			if ($this.hasClass('toggled')) {
				$this.removeClass('toggled');
				auxList.hide();
				auxWrap.find('.selected').removeClass('selected');
				auxContent.empty();
				auxWrap.animate({height: 'toggle'}, 200).removeClass('aux-open');
			} else {
				const html = wrap.find('.episode-single__wrapper').html();
				$this.addClass('toggled');
				auxList.hide();
				auxWrap.find('.selected').removeClass('selected');
				auxWrap.find('.content-tab').addClass('selected');
				auxContent.empty().html(html).show();
				auxWrap.addClass('aux-open').animate({height: 'toggle'}, 200);
			}
		});

		this.modal.on('click', '.pp-modal-tabs-item', function() {
			const $this = jQuery(this);
			if (!$this.hasClass('selected')) {
				$this.parent().find('.selected').removeClass('selected');
				$this.addClass('selected');
				if ($this.hasClass('lists-tab')) {
					const actEpisode = auxList.find('.activeEpisode');
					auxContent.hide();
					auxList.fadeIn();
					if (actEpisode.length) {
						auxWrap.animate({ scrollTop: auxWrap.scrollTop() + actEpisode.position().top }, 400 );
					}
				} else if ($this.hasClass('content-tab')) {
					auxList.hide();
					auxContent.fadeIn();
				}
			}
		});

		jQuery(document).on('keyup', function(event) {
			if ('Escape' === event.key) {
				if ( !_this.modal.hasClass('media-paused') ) {
					if (_this.modal.hasClass('modal-view') && !_this.mediaObj.isVideo) {
						_this.modal.removeClass('modal-view').addClass('inline-view');
						_this.scrollEnable();
					}
				} else {
					_this.modalClose();
				}
			}
		});

		this.modal.on('click', function(e) {
			const elem = jQuery(e.target);
			if (!_this.modal.hasClass('modal-view')) return;
			if (elem.closest('.pp-modal-wrapper').length || elem.closest('.pp-modal-close').length) return;
			if (_this.modal.hasClass('media-paused')) {
				_this.modalClose();
			} else {
				if (_this.mediaObj.isVideo) {
					_this.modalClose();
				} else {
					_this.modal.removeClass('modal-view').addClass('inline-view');
					_this.scrollEnable();
				}
			}
		});
	}

	// Create & display modal markup.
	create(elem, mediaObj, msgMediaObj, isModalView, mediaPause) {
		const placeHolder = jQuery('<div />', { id: 'pp-modal-placeholder' });
		const wrapper = this.modal.find('.pp-modal-wrapper');
		const podcast = elem.closest('.pp-podcast');
		const id = podcast.attr('id');
		const inst = id.replace( 'pp-podcast-', '' );
		const auxWrap = this.modal.find('.pp-modal-aux-wrapper');
		const auxList = auxWrap.find('.pp-modal-tabs-list');
		const _this = this;
		const clsTrasnfer = [
			'light-accent',
			'light-color',
			'hide-share',
			'hide-download',
			'hide-social',
			'single-episode',
			'single-audio',
			'modern'
		];
		let list;
		mediaPause = 'undefined' === typeof mediaPause ? true : mediaPause; 

		// Do not show aux list if single audio player OR discription hideen. 
		if (!podcast.is('.single-audio, .hide-content')) {
			list = podcast.find('.pod-content__list').clone();
			list.find('.pod-entry').each(function() {
				const _this = jQuery(this);
				_this.show().attr('data-pid', _this.attr('id')).
				removeAttr('id').
				removeClass('media-playing activeEpisode');
			});
	
			// If on a search request.
			list.find('.episode-list__load-more').show();
			list.find('.episode-list__search-results').hide();
	
			auxList.empty().append(list).addClass('lv3 postview');
	
			// Hide the search field unless we implement this feature.
			auxList.find('.episode-list__filters').hide();
		}

		if (podcast.hasClass('modern')) {
			const pptime = podcast.find('.ppjs__time');
			podcast.find('.ppjs__audio-timer').append(pptime);
		}

		// Add unique instance identifier class to aux Wrapper.
		auxWrap.addClass('aux-modal-' + inst);

		placeHolder.insertBefore(elem);
		if (! podcast.hasClass('postview')) {
			const loading = jQuery('<div />', { class: 'episode-search__loading'}).html(this.settings.ppVidLoading);
			placeHolder.height(elem.height()).html(loading);
		}
		wrapper.empty().append(elem);
		wrapper.children().wrapAll('<div class="modal-' + inst +'">');
		if (isModalView) {
			this.modal.addClass('modal-view pp-modal-open');
			this.scrollDisable();
		} else {
			this.modal.addClass('inline-view pp-modal-open');
		}

		// Transfer required styling classes from main player to sticky player.
		jQuery.each(
			clsTrasnfer,
			function( index, value ) {
				if (podcast.hasClass(value)) _this.modal.addClass(value);
			}
		);

		if (this.mediaObj && mediaPause) {
			this.mediaObj.media.pause();
		}
		this.mediaObj = mediaObj;
		this.msgMediaObj = msgMediaObj;
		this.mediaObj.media.addEventListener('ended', this.pauseMedia);
		this.mediaObj.media.addEventListener('pause', this.pauseMedia);
		this.mediaObj.media.addEventListener('play', this.playMedia);
		this.mediaObj.media.addEventListener('playing', this.playMedia);
		if (this.msgMediaObj) {
			this.msgMediaObj.media.addEventListener('ended', this.pauseMedia);
			this.msgMediaObj.media.addEventListener('pause', this.pauseMedia);
			this.msgMediaObj.media.addEventListener('play', this.playMedia);
			this.msgMediaObj.media.addEventListener('playing', this.playMedia);
		}
	}

	// Setup modal markup.
	mediaPause() {
		this.modal.addClass('media-paused');
		jQuery('#pp-modal-placeholder').parent().find('.activeEpisode').removeClass('media-playing');
	}

	// Setup modal markup.
	mediaPlay() {
		this.modal.removeClass('media-paused');
		jQuery('#pp-modal-placeholder').parent().find('.activeEpisode').addClass('media-playing');
	}

	// Close or minimize modal window.
	modalClose() {
		this.returnElem();
		this.modal.removeClass().addClass('pp-modal-window');
		this.scrollEnable();
		if (this.mediaObj) {
			this.mediaObj.media.pause();
			this.mediaObj = false;
		}
		if (this.msgMediaObj) {
			this.msgMediaObj.media.pause();
			this.msgMediaObj.media.currentTime = 0;
			this.msgMediaObj = false;
		}
	} 

	// Return element to its original position.
	returnElem() {
		const wrapper = this.modal.find('.pp-modal-wrapper');
		const auxWrapper = this.modal.find('.pp-modal-aux-wrapper');
		const elem = wrapper.find('.pp-podcast__single');
		const placeHolder = jQuery('#pp-modal-placeholder');
		const closeBtn = this.modal.find('.pp-modal-close');

		this.mediaObj.media.removeEventListener('ended', this.pauseMedia);
		this.mediaObj.media.removeEventListener('pause', this.pauseMedia);
		this.mediaObj.media.removeEventListener('play', this.pauseMedia);
		this.mediaObj.media.removeEventListener('playing', this.pauseMedia);
		if (this.msgMediaObj) {
			this.msgMediaObj.media.removeEventListener('ended', this.pauseMedia);
			this.msgMediaObj.media.removeEventListener('pause', this.pauseMedia);
			this.msgMediaObj.media.removeEventListener('play', this.playMedia);
			this.msgMediaObj.media.removeEventListener('playing', this.playMedia);
		}

		if (auxWrapper.length && auxWrapper.hasClass('aux-open')) {
			auxWrapper.removeClass('aux-open').hide();
			this.modal.find('.ppjs__secondary-controls').find('.toggled').removeClass('toggled');
		}

		auxWrapper.removeClass().addClass('pp-modal-aux-wrapper');

		if (this.modal.hasClass('modern')) {
			const pptime = this.modal.find('.ppjs__time');
			this.modal.find('.ppjs__atime-container').append(pptime);
		}

		if (elem.length) {
			// Remove active class from the element.
			elem.removeClass('activePodcast');
			if (placeHolder.length) {
				//Returning elem to its original position.
				elem.insertAfter(placeHolder);
			}
		}

		if (placeHolder.length) {
			// remove activeEpisode.
			placeHolder.parent().find('.activeEpisode').removeClass('activeEpisode media-playing');
			placeHolder.remove();
		}

		// Reset close button classes.
		closeBtn.removeClass('modal-closed');

		// Reset modal class.
		this.modal.removeClass().addClass('pp-modal-window');

		// Removing temporary items.
		wrapper.empty().removeAttr('style');
	}

	/**
	 * Disable scroll on the element that scrolls the document.
	 * 
	 * @since 1.3.5
	 */
	scrollDisable() {

		// Return if scroll is already disabled.
		if (this.bodyScrollDisabled) {
			return;
		}

		this.scrollPosition = this.scrollingElem.scrollTop;
		this.bodyScrollDisabled = true;
		setTimeout(() => {
			this.scrollingElem.scrollTop = 0;
			this.scrollingElem.classList.add('no-scroll');
		}, 250);
	}

	/**
	 * Enable scroll on the element that scrolls the document.
	 * 
	 * @since 1.3.5
	 */
	scrollEnable() {

		// Return if scroll is already Enabled.
		if (! this.bodyScrollDisabled) {
			return;
		}

		this.scrollingElem.classList.remove('no-scroll');
		this.scrollingElem.scrollTop = this.scrollPosition;
		this.bodyScrollDisabled = false;
	}
}

export default Modal;
