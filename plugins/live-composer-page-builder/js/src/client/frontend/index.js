/**
 * Table of Contents
 *
 * - dslc_responsive_classes
 * - dslc_init_accordion
 * - dslc_init_lightbox
 * - dslc_carousel ( Initializes carousels )
 * - dslc_bg_video ( Initiate row BG video )
 * - dslc_parallax
 * - dslc_masonry
 * - dslc_center
 * - dslc_tabs_generate_code
 * - dslc_accordion_generate_code
 * - dslc_tabs
 * - dslc_download_count_increment ( Increment download count )
 * - dslc_check_viewport ( Check if element in viewport )
 * - dslc_validate_comment_form
 * - dslc_social_share
 */

/**
 * Responsive Classes
 *
 * We use it only in Responsive preview panel and for compatibility.
 */
window.dslc_responsive_classes = ( force ) => {

	if ( force === undefined) force = false;

	var windowWidth = jQuery(window).width();
	var body = jQuery('body');

	if ( force == true || ( ! body.hasClass('dslc-res-disabled') && ! jQuery('.dslca-module-edit-options-tab-hook.dslca-active[data-section="responsive"]').length ) ) {

		body.removeClass( 'dslc-res-phone dslc-res-tablet dslc-res-smaller-monitor dslc-res-big' );

		if ( windowWidth >= 1024 && windowWidth < 1280 ) {

			body.addClass( 'dslc-res-smaller-monitor' );
		} else if ( windowWidth >= 768 && windowWidth < 1024 ) {

			body.addClass( 'dslc-res-tablet' );
		} else if ( windowWidth < 768 ) {

			body.addClass( 'dslc-res-phone' );
		} else {

			body.addClass( 'dslc-res-big' );
		}
	}
}

/**
 * Init Accordion
 */
window.dslc_init_accordion = () => {

	jQuery('.dslc-accordion').each(function(){

		var dslcAccordion = jQuery(this),
		dslcActiveIndex = dslcAccordion.data('open') - 1,
		dslcActive = jQuery( '.dslc-accordion-item:eq(' + dslcActiveIndex + ')', dslcAccordion ),
		dslcInactive = dslcActive.siblings('.dslc-accordion-item'),
		dslcAll = jQuery('.dslc-accordion-item', dslcAccordion);

		if ( dslcActiveIndex >= 0 ) {

			dslcActive.addClass('dslc-active');
			dslcInactive.addClass('dslc-inactive');
			jQuery('.dslc-accordion-content', dslcInactive).hide();

			if ( ! jQuery("body").hasClass('dslca-enabled') ) {
				dslcInactive.find('.dslca-icon').removeClass('dslc-icon-minus').addClass('dslc-icon-plus');
			}
		} else {

			dslcAll.addClass('dslc-inactive');
			jQuery('.dslc-accordion-content', dslcAll).hide();

			if ( ! jQuery("body").hasClass('dslca-enabled') ) {
				dslcAll.find('.dslca-icon').removeClass('dslc-icon-minus').addClass('dslc-icon-plus');
			}
		}
	});
}

/**
 * Init Lightbox
 */
window.dslc_init_lightbox = () => {

	var type;

	jQuery( '.dslc-lightbox-image' ).each(function(){

		// Default type
		type = 'image';

		// Check if video
		if ( jQuery(this).attr('href').indexOf('youtube.com') >= 0 || jQuery(this).attr('href').indexOf('vimeo.com') >= 0 ) {

			type = 'iframe';
		}

		jQuery(this).magnificPopup({ type: type });
	});

	jQuery( '.dslc-lightbox-gallery' ).each(function(){
		jQuery(this).magnificPopup({
			delegate : 'a',
			type:'image',
			gallery:{
				enabled: true
			},
			image: {
				titleSrc: function(item) {
					var caption = '';

					if ( typeof item.el.attr('data-caption') !== "undefined" ) {
						caption = '<small>' + item.el.attr('data-caption') +'</small>';
					}

					return item.el.attr('title') + caption;
				}
			}
		});
	});
}

/**
 * Init carousels
 */
window.dslc_carousel = () => {

	// Loop through each carousel
	jQuery( '.dslc-carousel, .dslc-slider' ).each( function(el) {

		// Variables
		var carousel, container, defSettings, usrSettings, settings;

		// Elements
		carousel = jQuery( this );
		container = carousel.closest( '.dslc-module-front' );

		container.imagesLoaded( function(){

			if ( container.closest('.dslc-modules-section').hasClass('dslc-no-columns-spacing') ) {

				var margin = 0;
			} else {

				var margin = ( container.width() / 100 * 2.12766 ) / 2;
			}

			if ( carousel.hasClass('dslc-carousel') ) {

				carousel.find('.dslc-col').css({ 'margin-left' : margin, 'margin-right' : margin });
				carousel.css({ 'margin-left' : margin * -1, 'width' : carousel.width() + margin * 2 });
			}

			// Default settings
			defSettings = {
				items : 4,
				pagination : true,
				singleItem : false,
				itemsScaleUp : false,
				slideSpeed : 200,
				paginationSpeed : 800,
				rewindSpeed : 1000,
				autoPlay : false,
				stopOnHover : false,
				lazyLoad : false,
				lazyFollow : true,
				autoHeight : false,
				mouseDrag : true,
				touchDrag : true,
				addClassActive : true,
				transitionStyle : 'fade',
				scrollPerPage : true
			};

			// Custom Settings
			usrSettings = {
				items : carousel.data( 'columns' ),
				pagination : carousel.data( 'pagination' ),
				itemsScaleUp : carousel.data( 'scale-up' ),
				slideSpeed : carousel.data( 'slide-speed' ),
				paginationSpeed : carousel.data( 'pagination-speed' ),
				rewindSpeed : carousel.data( 'rewind-speed' ),
				autoPlay : carousel.data( 'autoplay' ),
				stopOnHover : carousel.data( 'stop-on-hover' ),
				lazyLoad : carousel.data( 'lazy-load' ),
				lazyFollow : carousel.data( 'lazy-follow' ),
				autoHeight : carousel.data( 'flexible-height' ),
				mouseDrag : carousel.data( 'mouse-drag' ),
				touchDrag : carousel.data( 'touch-drag' ),
				addClassActive : carousel.data( 'active-class' ),
				transitionStyle : carousel.data( 'animation' ),
				scrollPerPage : carousel.data( 'scroll-per-page' )
			};

			// Merge default and custom settings
			settings = jQuery.extend( {}, defSettings, usrSettings );

			// If it's a slider set singleItem to true
			if ( carousel.hasClass( 'dslc-slider' ) || settings.items == 1 ) {

				settings.singleItem = true;
			}

			// If autoplay is 0 set to false
			if ( settings.autoPlay == 0 ) {

				settings.autoPlay = false;
			}

			// Initialize
			carousel.owlCarousel({

				items : settings.items,
				pagination : settings.pagination,
				singleItem : settings.singleItem,
				itemsScaleUp : settings.itemsScaleUp,
				slideSpeed : settings.slideSpeed,
				paginationSpeed : settings.paginationSpeed,
				rewindSpeed : settings.rewindSpeed,
				autoPlay : settings.autoPlay,
				stopOnHover : settings.stopOnHover,
				lazyLoad : settings.lazyLoad,
				lazyFollow : settings.lazyFollow,
				mouseDrag : settings.mouseDrag,
				touchDrag : settings.touchDrag,
				scrollPerPage : settings.scrollPerPage,
				transitionStyle : settings.transitionStyle,
				autoHeight : settings.autoHeight,
				itemsDesktop : false,
				itemsDesktopSmall : false,
				itemsTablet : false,
				itemsMobile : [766,1],
				afterInit: function() {

					carousel.prev( '.dslc-loader' ).remove();
					carousel.css({
						opacity : 1,
						maxHeight : 'none'
					});
				},
				afterAction : function(){

					var visible_items = this.owl.visibleItems;
					carousel.find('.dslc-carousel-item-visible').removeClass('dslc-carousel-item-visible');
					carousel.find('.owl-item').filter(function(index) {
						return visible_items.indexOf(index) > -1;
					}).addClass('dslc-carousel-item-visible');
				}
			});

			// Previous
			jQuery( '.dslc-carousel-nav-next', container ).click( function(e) {
				e.preventDefault();
				carousel.data( 'owlCarousel' ).next();
			});

			// Next
			jQuery( '.dslc-carousel-nav-prev', container ).click( function(e) {
				e.preventDefault();
				carousel.data( 'owlCarousel' ).prev();
			});
		}); // End .imagesLoaded
	});
}

/**
 * Fix responsivness of carousel
 */
window.dslc_carousel_responsive = () => {

	// Loop through each carousel
	jQuery( '.dslc-carousel' ).each( function() {

		// Variables
		var carousel, container;

		// Elements
		carousel = jQuery( this );
		container = carousel.closest( '.dslc-module-front' );

		carousel.css({ 'margin-left' : 0, 'width' : 'auto' });

		if ( container.closest('.dslc-modules-section').hasClass('dslc-no-columns-spacing') ) {

			var margin = 0;
		} else {

			var margin = ( container.width() / 100 * 2.12766 ) / 2;
		}

		if ( carousel.hasClass('dslc-carousel') ) {

			carousel.find('.dslc-col').css({ 'margin-left' : margin, 'margin-right' : margin });
			carousel.css({ 'margin-left' : margin * -1, 'width' : carousel.width() + margin * 2 });
		}
	});
}

/**
 * Initiate Video
 */
window.dslc_bg_video = () => {

	jQuery('.dslc-bg-video').each(function(){

		if ( ! jQuery(this).find( 'video' ).length ) {

			jQuery(this).css({ opacity : 1 });
		}
	});

	jQuery('.dslc-bg-video video').mediaelementplayer({
		loop: true,
		pauseOtherPlayers: false,
		success: function(mediaElement, domObject) {

			mediaElement.addEventListener('loadeddata', function (e) {
				jQuery(domObject).closest('.dslc-bg-video').animate({ opacity : 1 }, 400);
			});

			mediaElement.play();
		}
	});
}

/**
 * Initiate Parallax
 */
window.dslc_parallax = () => {

	jQuery('.dslc-init-parallax').each(function(){
		var $paralaxEl = jQuery(this);
		$paralaxEl.imagesLoaded( { background: true }, function(){
			var dslcSpeed = 4,
			bgPosition = $paralaxEl.css( 'background-position' ).split( ' ' ),
			bgPositionHor = bgPosition[0],
			dslcPos = bgPositionHor + " " + ( -1 * ( window.pageYOffset - $paralaxEl.offset().top ) / dslcSpeed ) + "px";

			$paralaxEl.css({ backgroundPosition : dslcPos });
		});
	});

	window.onscroll = function() {

		jQuery('.dslc-init-parallax').each(function(){

			var dslcSpeed = 4,
			bgPosition = jQuery(this).css( 'background-position' ).split( ' ' ),
			bgPositionHor = bgPosition[0],
			dslcPos = bgPositionHor + " " + ( -1 * ( window.pageYOffset - jQuery(this).offset().top ) / dslcSpeed ) + "px";

			jQuery(this).css({ backgroundPosition : dslcPos });
		});
	}
}

/**
 * Initiate Masonry
 */
window.dslc_masonry = ( dslcWrapper, dslcAnimate ) => {

	dslcWrapper = typeof dslcWrapper !== 'undefined' ? dslcWrapper : jQuery('body');
	dslcAnimate = typeof dslcAnimate !== 'undefined' ? dslcAnimate : false;

	jQuery('.dslc-init-masonry', dslcWrapper).each(function(){

			var dslcContainer, dslcSelector, dslcItems, dslcItemWidth, dslcContainerWidth, dslcGutterWidth;

			if ( jQuery(this).find('.dslc-posts-inner').length ) {

				dslcContainer = jQuery(this).find('.dslc-posts-inner');
			} else {

				dslcContainer = jQuery(this);
			}

			dslcSelector = '.dslc-masonry-item';
			dslcItemWidth = jQuery(dslcSelector, dslcContainer).width();
			dslcContainerWidth = jQuery(dslcContainer).width();

			if ( jQuery(this).closest('.dslc-modules-section').hasClass('dslc-no-columns-spacing') ) {

				dslcGutterWidth = 0;
			} else {

				dslcGutterWidth = dslcContainerWidth / 100 * 2.05;
			}

			if ( dslcContainer.data('masonry') ) {

				jQuery(dslcContainer).imagesLoaded(function() {

					jQuery(dslcContainer).masonry('destroy').masonry({
						gutter : dslcGutterWidth,
						itemSelector : dslcSelector
					});

					jQuery( dslcContainer ).find( '.dslc-post:not(.dslc-masonry-item)' ).hide();

					if ( dslcAnimate ) {

						jQuery(dslcSelector, dslcContainer).css({ 'scale' : '0.2'}).animate({
							'scale' : '1'
						}, 500);
					}
				});

			} else {

				jQuery(dslcSelector).css({ marginRight : 0 });

				jQuery(dslcContainer).imagesLoaded(function() {

					jQuery(dslcContainer).masonry({
						gutter : dslcGutterWidth,
						itemSelector : dslcSelector
					});
				});
			}

	}); // End each().
}

/**
 * Generate Tabs Code
 */
window.dslc_tabs_generate_code = ( dslcTabs ) => {
	var dslcTabsContainer = dslcTabs.closest('.dslc-module-front');

	dslcTabsNav = jQuery('.dslc-tabs-nav', dslcTabs);
	dslcTabsContent = jQuery('.dslc-tabs-content', dslcTabs);
	dslcTabContent = jQuery('.dslc-tabs-tab-content', dslcTabs);

	var dslcTabsNavVal = '';
	var dslcTabsContentVal = '';
	var dslcTabsNavValCount = 0;
	var dslcTabsContentValCount = 0;

	jQuery( '.dslc-tabs-nav-hook', dslcTabsNav ).each(function(){

		dslcTabsNavValCount++;

		if ( dslcTabsNavValCount > 1 ) {

			dslcTabsNavVal += ' (dslc_sep) ';
		}

		dslcTabsNavVal += jQuery(this).find('.dslc-tabs-nav-hook-title').text();
	});

	dslcTabContent.each(function(){

		dslcTabsContentValCount++;

		if ( dslcTabsContentValCount > 1 ) {

			dslcTabsContentVal += ' (dslc_sep) ';
		}

		dslcTabsContentVal += jQuery(this).find('.dslca-tab-plain-content').val();
	});

	dslcTabsContentVal = dslcTabsContentVal.replace(/<textarea/g, '<lctextarea').replace(/<\/textarea/g, '</lctextarea');

	jQuery('.dslca-module-option-front[data-id="tabs_nav"]', dslcTabsContainer).val( dslcTabsNavVal );
	jQuery('.dslca-module-option-front[data-id="tabs_content"]', dslcTabsContainer).val( dslcTabsContentVal );
	parent.LiveComposer.Builder.Actions.optionsChanged();
}

/**
 * Generate Code for the New Accordion Tab
 */
window.dslc_accordion_generate_code = ( dslcAccordion ) => {
	var dslcModule = dslcAccordion.closest('.dslc-module-front'),
	dslcAccordionCount = 0,
	dslcAccordionTitleVal = '',
	dslcAccordionContentVal = '';

	jQuery( '.dslc-accordion-item', dslcAccordion ).each(function(){
		dslcAccordionCount++;
		if ( dslcAccordionCount > 1 ) {

			dslcAccordionTitleVal += ' (dslc_sep) ';
			dslcAccordionContentVal += ' (dslc_sep) ';
		}
		dslcAccordionTitleVal += jQuery(this).find('.dslc-accordion-title').text();
		dslcAccordionContentVal += jQuery(this).find('.dslc-accordion-content').find('.dslca-accordion-plain-content').val();
	});

	dslcAccordionContentVal = dslcAccordionContentVal.replace(/<textarea/g, '<lctextarea').replace(/<\/textarea/g, '</lctextarea');

	jQuery('.dslca-module-option-front[data-id="accordion_nav"]', dslcModule).val( dslcAccordionTitleVal );
	jQuery('.dslca-module-option-front[data-id="accordion_content"]', dslcModule).val( dslcAccordionContentVal );

	parent.LiveComposer.Builder.Actions.optionsChanged();
}

function dslc_on_tab_add( event ) {

	var dslcTabs = jQuery(event.target).closest('.dslc-tabs'),
	dslcTabsNavLast = jQuery('.dslc-tabs-nav .dslc-tabs-nav-hook:last', dslcTabs),
	dslcTabContentLast = jQuery('.dslc-tabs-tab-content:last', dslcTabs),
	numberOfTabs = jQuery('.dslc-tabs-nav .dslc-tabs-nav-hook', dslcTabs).length;

	dslcTabsNavLast.after('<span class="dslc-tabs-nav-hook"><span class="dslc-tabs-nav-hook-title" contenteditable="true">Click to edit title</span><span class="dslca-delete-tab-hook"><span class="dslca-icon dslc-icon-remove"></span></span></span>');
	dslcTabContentLast.after('<div class="dslc-tabs-tab-content"><div class="dslca-editable-content" data-edit-id="' + numberOfTabs +'" data-id="' + numberOfTabs +'">This is just placeholder text.</div><textarea class="dslca-tab-plain-content" data-edit-id="' + numberOfTabs +'" data-id="' + numberOfTabs +'">This is just placeholder text.</textarea><div class="dslca-wysiwyg-actions-edit"><span class="dslca-wysiwyg-actions-edit-hook" data-event="wysiwyg-edit" data-id-to-edit="' + numberOfTabs +'">Open in WP Editor</span></div></div>');

	jQuery('.dslc-tabs-nav-hook:last', dslcTabs).click();

	dslc_tabs_generate_code( dslcTabs );

	// openModuleForEditing( this[0] );
	if ( ! jQuery(dslcTabs[0]).closest('.dslc-module-front').hasClass('dslca-module-being-edited') ) {
		jQuery(dslcTabs[0]).closest('.dslc-module-front').find('.dslca-module-edit-hook').trigger('click');
	}

	// dslc_tabs()
}

function dslc_on_tab_delete( event ) {
	var dslcTabs = jQuery(event.target).closest('.dslc-tabs');
	var dslcTabHook = jQuery(event.target).closest('.dslc-tabs-nav-hook');
	var dslcTabIndex = dslcTabHook.index();
	var dslcTabContent = jQuery('.dslc-tabs-tab-content', dslcTabs).eq( dslcTabIndex );

	if ( jQuery( '.dslc-tabs-nav .dslc-tabs-nav-hook', dslcTabs ).length > 1 ) {

		dslcTabHook.remove();
		dslcTabContent.remove();

		if ( ! jQuery( '.dslc-tabs-tab-content.dslc-active', dslcTabs ).length ) {
			jQuery( '.dslc-tabs-nav-hook:first', dslcTabs ).trigger('click');
		}

		dslc_tabs_generate_code( dslcTabs );
	}

	// openModuleForEditing( this[0] );
	// Otherwise won't save changes.
	if ( ! jQuery(dslcTabs[0]).closest('.dslc-module-front').hasClass('dslca-module-being-edited') ) {
		jQuery(dslcTabs[0]).closest('.dslc-module-front').find('.dslca-module-edit-hook').trigger('click');
	}

	event.stopPropagation()
}


function dslc_on_tab_nav( event ) {
	dslcTabs = jQuery(event.target).closest('.dslc-tabs');
	dslcTabsNav = jQuery('.dslc-tabs-nav', dslcTabs);
	dslcTabsContent = jQuery('.dslc-tabs-content', dslcTabs);
	dslcTabContent = jQuery('.dslc-tabs-tab-content', dslcTabs);
	dslcTabIndex = jQuery(event.currentTarget).index();

	// Tabs nav
	jQuery('.dslc-tabs-nav-hook.dslc-active', dslcTabs).removeClass('dslc-active');
	jQuery(event.currentTarget).addClass('dslc-active');

	// Tabs content

	if ( jQuery( '.dslc-tabs-tab-content.dslc-active', dslcTabs ).length ) {
		jQuery('.dslc-tabs-tab-content.dslc-active', dslcTabs).animate({
			opacity : 0
		}, 250, function(){
			jQuery(this).removeClass('dslc-active');
			dslcTabContent.eq(dslcTabIndex).css({ opacity : 0 }).addClass('dslc-active').show().animate({
				opacity : 1
			}, 250);
		});
	} else {
		dslcTabContent.eq(dslcTabIndex).css({ opacity : 0 }).addClass('dslc-active').show().animate({
			opacity : 1
		}, 250);
	}

	// event.stopPropagation()
}

/**
 * Initiate Tabs
 */
window.dslc_tabs = () => {
	var dslcTabs, dslcTabsNav, dslcTabsContent, dslcTabContent;

	jQuery('.dslc-tabs').each(function(){

		dslcTabs = jQuery(this);
		dslcTabsNav = jQuery('.dslc-tabs-nav', dslcTabs);
		dslcTabsContent = jQuery('.dslc-tabs-content', dslcTabs);
		dslcTabContent = jQuery('.dslc-tabs-tab-content', dslcTabs);

		dslcTabContent.eq(0).addClass('dslc-active');
		jQuery('.dslc-tabs-nav-hook', dslcTabsNav ).eq(0).addClass('dslc-active');

		
	});
}

// $(staticAncestors).on(eventName, dynamicChild, function() {
// jQuery(document).ready(function($) {
		/**
		* Tabs – Tabs editing controls
		*/
		jQuery(document).on( 'click', '.dslca-add-new-tab-hook', (event) => dslc_on_tab_add(event));
		jQuery(document).on( 'click', '.dslca-delete-tab-hook', (event) => dslc_on_tab_delete(event));
		jQuery(document).on( 'click', '.dslc-tabs-nav-hook', (event) => dslc_on_tab_nav( event ));

		jQuery(document).on('blur paste', '.dslc-tabs-nav-hook-title[contenteditable], .dslc-tabs-tab-content[contenteditable]', function(event) {
			dslc_tabs_generate_code( jQuery(this).closest('.dslc-tabs') );
		}).on('focus', '.dslc-tabs-nav-hook-title[contenteditable], .dslc-tabs-tab-content[contenteditable]', function(event) {
			if ( ! jQuery(this).closest('.dslc-module-front').hasClass('dslca-module-being-edited') ) {
				jQuery(this).closest('.dslc-module-front').find('.dslca-module-edit-hook').trigger('click');
			}
		});

// });
/**
 * Increment download count
 */

window.dslc_download_count_increment = ( post_id ) => {

	jQuery.post(

		DSLCAjax.ajaxurl,
		{
			action : 'dslc-download-count-increment',
			dslc_post_id : post_id
		},
		function( response ) { }
	);
}

/**
 * Check if element in viewport
 */
window.dslc_check_viewport = () => {

	var isIE = /*@cc_on!@*/false || !!document.documentMode;

	if ( !isIE ) {

		jQuery('.dslc-in-viewport-check:in-viewport:not(.dslc-in-viewport)').each(function(){

			var _this = jQuery(this);
			var anim = _this.data('dslc-anim');
			var animDuration = parseInt( _this.data('dslc-anim-duration') ) / 1000;

			var anim_speed = animDuration + 's';
			if ( jQuery(window).width() < 768 ) {

				anim_speed = '0s';
			}

			var anim_delay = parseInt( _this.data('dslc-anim-delay') );
			var anim_easing =  _this.data('dslc-anim-easing');
			var anim_params = anim + ' ' + anim_speed + ' ' + anim_easing + ' forwards';

			jQuery(this).addClass('dslc-in-viewport');

			if ( anim_delay > 0 ) {

				setTimeout( function(){
					_this.css({
						'-webkit-animation': anim_params,
						'-moz-animation': anim_params,
						'animation': anim_params
					});
				}, anim_delay );
			} else {

				jQuery(this).css({
					'-webkit-animation': anim_params,
					'-moz-animation': anim_params,
					'animation': anim_params
				});
			}
		});
	} else {

		jQuery('.dslc-in-viewport-check').css( 'opacity', 1 );
	}
}

/**
 * Animation of elements on hover ( posts modules )
 */
window.dslc_el_anim_hover = () => {

	jQuery('.dslc-on-hover-anim-target').each(function(){

		var anim_speed = parseInt( jQuery(this).data('dslc-anim-speed') ) / 1000;
		var anim_value = 'all ' + anim_speed + 's ease-out';

		jQuery(this).css({
			'-webkit-transition' : anim_value,
			'-moz-transition' : anim_value,
			'transition' : anim_value
		});
	});
}

/**
 * Progress Bar - Check viewport and animate
 */
window.dslc_check_progress_bar_viewport = () => {

	jQuery('.dslc-progress-bar-animated:in-viewport:not(.dslc-progress-bar-in-viewport)').each(function(){

		var dslcWrapper = jQuery(this),
		dslcEl = dslcWrapper.find('.dslc-progress-bar-loader-inner'),
		dslcVal = dslcEl.data('amount') + '%',
		dslcSpeed = dslcEl.data('speed');

		dslcWrapper.addClass('dslc-progress-bar-in-viewport');

		dslcEl.css({ width : 0, opacity : 1 }).animate({ width : dslcVal }, dslcSpeed );
	});
}

/**
 * Validate Comment Form
 */
window.dslc_validate_comment_form = ( commentForm ) => {

	var commentName = commentForm.find('#author'),
	commentEmail = commentForm.find('#email'),
	commentWebsite = commentForm.find('#url'),
	commentMessage = commentForm.find('#comment'),
	commentStatus = true;

	// Name
	if ( commentName.length && commentName.val().length == 0 ) {

		commentName.css({ borderColor : '#e55f5f' });
		commentStatus = false;
	} else {

		commentName.attr( 'style', '' );
	}

	// Email
	if ( commentEmail.length && ( commentEmail.val().length == 0 || commentEmail.val().indexOf('@') === -1 ) ) {

		commentEmail.css({ borderColor : '#e55f5f' });
		commentStatus = false;
	} else {

		commentEmail.attr( 'style', '' );
	}

	// Message
	if ( commentMessage.val().length == 0 ) {

		commentMessage.css({ borderColor : '#e55f5f' });
		commentStatus = false;
	} else {

		commentMessage.attr( 'style', '' );
	}

	return commentStatus;
}

/**
 * Social Sharing
 */
window.dslc_social_share = ( width, height, url ) => {

	var leftPosition, topPosition, u, t;
	//Allow for borders.
	leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
	//Allow for title and status bars.
	topPosition = (window.screen.height / 2) - ((height / 2) + 50);
	var windowFeatures = "status=no,height=" + height + ",width=" + width + ",resizable=yes,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no";
	u=location.href;
	t=document.title;
	window.open(url,'sharer', windowFeatures);
	return false;
}

/**
 * Sticky Row
 */
window.dslc_sticky_row = () => {

	var row = jQuery('body .dslc-modules-section');

	if ( row.hasClass('dslc-sticky-row') && ! jQuery('body').hasClass('dslca-enabled') ) {

		var rowSticky = jQuery('body .dslc-modules-section.dslc-sticky-row');
		var $headers = jQuery(".dslc-sticky-row");

		$headers.each(function(index, el) {
			var $curHeader = jQuery($headers).eq(index);
			var curHeight = $curHeader.outerHeight();

			jQuery(el).wrap('<div class="dslc-modules-section-placeholder"></div>').parent().height(jQuery(el).outerHeight());
		});

		var rowSticky = jQuery('body .dslc-modules-section.dslc-sticky-row');
		var rowStickyPos = rowSticky.offset().top;

		// Account for Admin bar.
		if ( jQuery( '#wpadminbar' ).length ) {
			rowStickyPos -= jQuery( '#wpadminbar' ).height();
		}

		var headers = jQuery(".dslc-sticky-row");

		document.addEventListener( 'scroll', (evt) => {
			var scrollTop = window.scrollY;

			if ( scrollTop < 50 || scrollTop % 10 === 0 ) {
				if ( scrollTop >= 0 ) {
					headers.each(function(index, el) {
						var curHeader = jQuery(headers).eq(index);
						var curTop = curHeader.offset().top;
						var curHeight = curHeader.outerHeight();

						// Account for Admin bar.
						if ( jQuery( '#wpadminbar' ).length ) {
							curTop -= jQuery( '#wpadminbar' ).height();
						}

						// scroll up
						// If windown scoling pos is bellow the element top coordinates.
						var isRelative = ( scrollTop <= el.exTop );

						// scroll down
						var isFixed = ( curTop <= scrollTop );

						var position = "fixed";
						var top = 0;

						if ( isRelative ) {
							position = "relative";
							top = 0;

							el.isFixed = false;

							el.classList.remove("dslc-sticky-section-fixed");
							jQuery(el).closest('.dslc-modules-section-placeholder').height(jQuery(el).outerHeight());
						} else if ( isFixed ) {
							position = "fixed";
							if ( 0 < index ) {
								for ( var i = 0; i < index; i++ ) {
									top += jQuery(headers).eq(i).outerHeight();
								}
							}
							scrollTop += curHeight;

							if ( ! el.isFixed ) {
								el.isFixed = true;
								el.exTop = curTop;
							}

							// Account for Admin bar.
							if ( jQuery( '#wpadminbar' ).length ) {
								top += 32;
							}

							el.classList.add("dslc-sticky-section-fixed");
							jQuery(el).closest('.dslc-modules-section-placeholder').height(jQuery(el).outerHeight());
						}

						jQuery(el).css({
							position: position,
							top: top + "px"
						});
					});
				} else {
					rowSticky.removeClass('dslc-sticky-section-fixed');

					// reset all
					headers.css({
						position: "relative",
						top: "0px"
					});
				}
			}
		}, { capture: false, passive: true})
	}
}

jQuery(document).ready(function($){

	// Fix issue - https://github.com/live-composer/live-composer-page-builder/issues/592
	var viewport = document.querySelector("meta[name=viewport]");
	viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0');

	dslc_el_anim_hover();
	dslc_bg_video();
	dslc_tabs();

	// Load More Posts
	jQuery(document).on( 'click', '.dslc-pagination-load-more a', function(e){

		e.preventDefault();

		if ( jQuery(this).parent().hasClass('dslc-active') ) {

			var _this = jQuery(this),
			module = jQuery(this).closest('.dslc-module-front'),
			pagination = module.find('.dslc-pagination'),
			postsContainer = module.find('.dslc-posts-inner'),
			moduleID = module.attr('id'),
			pagLink = _this.attr('href'),
			tempHolder = module.find('.dslc-load-more-temp');

			_this.find('.dslc-icon').addClass('dslc-icon-spin');

			tempHolder.load( pagLink + ' #' + moduleID, function(){

				postsContainer.append( '<div class="dslc-post-separator"></div>' );
				postsContainer.append( tempHolder.find('.dslc-posts-inner').html() );

				module.find('.dslc-pagination').html( tempHolder.find('.dslc-pagination').html() );

				pagination.replaceWith( tempHolder.find('.dslc-pagination') );

				tempHolder.html('');

				postsContainer.imagesLoaded( function(){
					if ( module.find('.dslc-init-masonry').length ) {
						module.find('.dslc-init-masonry .dslc-posts-inner').masonry("reloadItems").masonry();
					}
				});
			});
		}
	});

	// Comment Form Validation
	jQuery('.dslc-tp-comment-form form').submit(function(e){

		if ( ! dslc_validate_comment_form( jQuery(this) ) ) {
			e.preventDefault();
		}
	});

	/**
	 * Mobile Nav
	 */
	jQuery('.dslc-mobile-navigation select').change(function() {
		window.location = jQuery(this).val();
	});

	// Close Notification
	jQuery(document).on( 'click', '.dslc-notification-close', function(e){

		jQuery(this).closest('.dslc-notification').slideUp(200, function(){

			jQuery(this).remove();
		});
	});

	/**
	 * Filter
	 */
	jQuery(document).on( 'click', '.dslc-post-filter', function(){

		// Get info.
		var selectedFilterEl = jQuery(this);
		var dslcContainer    = selectedFilterEl.closest('.dslc-module-front').find('.dslc-posts');
		var dslcWrapper      = selectedFilterEl.closest('.dslc-module-front');

		// Filter posts according to selected filter.
		var dslcCat = selectedFilterEl.data('filter-id');
		var dslcFilterPosts    = jQuery(); // Empty jQuery object.
		var dslcNotFilterPosts = jQuery();

		if ( dslcCat === 'show-all' ) {

			dslcFilterPosts    = dslcContainer.closest('.dslc-module-front').find('.dslc-post');
			dslcNotFilterPosts = jQuery(); // Empty jQuery object.

		} else {

			dslcFilterPosts    = dslcContainer.closest('.dslc-module-front').find('.dslc-post[data-cats*="' + dslcCat + '"]');
			dslcNotFilterPosts = dslcContainer.closest('.dslc-module-front').find('.dslc-post:not([data-cats*="' + dslcCat + '"])');
		}

		// Set active
		selectedFilterEl.removeClass('dslc-inactive').addClass('dslc-active').siblings('.dslc-active').removeClass('dslc-active').addClass('dslc-inactive');

		if ( dslcContainer.hasClass('dslc-init-grid' ) ) {

			dslcFilterPosts.stop().animate({
				opacity : 1
			}, 300);

			dslcNotFilterPosts.stop().animate({
				opacity : 0.3
			}, 300);
		} else {

			// Hide posts
			dslcNotFilterPosts.removeClass('dslc-masonry-item dslc-masonry-item-animate').css({ visibility : 'hidden' });
			dslcFilterPosts.addClass('dslc-masonry-item dslc-masonry-item-animate').css({ visibility : 'visible' }).show();

			dslc_masonry( dslcWrapper, true );
		}
	});

	/**
	 * Download Count Hook
	 */
	jQuery(document).on( 'click', '.dslc-download-count-hook', function(e) {

		dslc_download_count_increment( jQuery(this).data('post-id') );
	});

	/**
	 * Notification Close
	 */
	jQuery('.dslc-notification-box-has-timeout').each(function(){

		var nBox = jQuery(this);
		nTimeout = 'none',
		moduleID = nBox.closest('.dslc-module-front').data('module-id'),
		cookieID = 'nBox' + moduleID;

		// Check timeout
		if ( nBox.data('notification-timeout') ) {

			if ( Cookies.get(cookieID) == undefined ) {
				nBox.show();
			}
		}
	});

	jQuery(document).on( 'click', '.dslc-notification-box-close', function() {

		var nBox = jQuery(this).closest('.dslc-notification-box'),
		nTimeout = 'none',
		moduleID = nBox.closest('.dslc-module-front').data('module-id'),
		cookieID = 'nBox' + moduleID;

		// Check timeout
		if ( nBox.data('notification-timeout') ) {

			nTimeout = nBox.data('notification-timeout');
		}

		// Set cookie if timeout exists
		if ( nTimeout !== 'none' ) {

			Cookies.set( cookieID, 'closed', { expires: nTimeout } );
		}

		// Close with animation
		nBox.animate({
			opacity : 0
		}, 400, function(){

			jQuery(this).remove();
		});
	});

	/**
	 * Lightbox
	 */
	dslc_init_lightbox();

	/**
	 * Accordion
	 */
	dslc_init_accordion();

	jQuery(document).on( 'click', '.dslc-accordion-hook', function(){

		var dslcActive = jQuery(this).closest('.dslc-accordion-item'),
		dslcInactive = dslcActive.siblings('.dslc-accordion-item');

		if ( dslcActive.hasClass('dslc-active') ) {

			if ( ! jQuery("body").hasClass('dslca-enabled') ) {
				if ( dslcActive.find('.dslca-icon').hasClass('dslc-icon-minus') ) {
					dslcActive.find('.dslca-icon').removeClass('dslc-icon-minus').addClass('dslc-icon-plus');
				} else {
					dslcActive.find('.dslca-icon').removeClass('dslc-icon-plus').addClass('dslc-icon-minus');
				}
			}

			dslcInactive = dslcActive;
		} else {

			dslcActive.removeClass('dslc-inactive').addClass('dslc-active');

			if ( ! jQuery("body").hasClass('dslca-enabled') ) {
				if ( dslcActive.find('.dslca-icon').hasClass('dslc-icon-minus') ) {
					dslcActive.find('.dslca-icon').removeClass('dslc-icon-minus').addClass('dslc-icon-plus');
				} else {
					dslcActive.find('.dslca-icon').removeClass('dslc-icon-plus').addClass('dslc-icon-minus');
					dslcInactive.find('.dslca-icon').removeClass('dslc-icon-minus').addClass('dslc-icon-plus');
				}
			}
		}

		dslcInactive.removeClass('dslc-active').addClass('dslc-inactive');

		jQuery('.dslc-accordion-content', dslcActive).slideDown(300);
		jQuery('.dslc-accordion-content', dslcInactive).slideUp(300);
	});

	jQuery(document).on( 'click', '.dslca-add-accordion-hook', function(){

		var dslcAccordion = jQuery(this).closest('.dslc-accordion'),
		dslcAccordionLast = jQuery('.dslc-accordion-item:last', dslcAccordion),
		dslcAccordionNew = dslcAccordionLast.clone().insertAfter(dslcAccordionLast);
		numberOfTabs = jQuery('.dslc-accordion-item', dslcAccordion).length;

		jQuery('.dslc-accordion-title', dslcAccordionNew).html('CLICK TO EDIT');
		jQuery('.dslc-accordion-content', dslcAccordionNew).html('<div class="dslca-editable-content" data-edit-id="' + numberOfTabs +'">Placeholder content, click to edit. Lorem ipsum dolor sit amet, consectetur tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div><textarea class="dslca-accordion-plain-content"></textarea><div class="dslca-wysiwyg-actions-edit"><span class="dslca-wysiwyg-actions-edit-hook" data-event="wysiwyg-edit" data-id-to-edit="' + numberOfTabs +'">Edit Content</span></div>');
		jQuery('.dslc-accordion-hook', dslcAccordionNew).click();

		dslc_accordion_generate_code( dslcAccordion );

		if ( ! jQuery(this).closest('.dslc-module-front').hasClass('dslca-module-being-edited') ) {

			jQuery(this).closest('.dslc-module-front').find('.dslca-module-edit-hook').trigger('click');
		}
	});

	jQuery(document).on( 'click', '.dslca-delete-accordion-hook', function(e){

		var dslcAccordion = jQuery(this).closest('.dslc-accordion'),
		dslcAccordionItem = jQuery(this).closest('.dslc-accordion-item');

		if ( ! jQuery(this).closest('.dslc-module-front').hasClass('dslca-module-being-edited') ) {

			jQuery(this).closest('.dslc-module-front').find('.dslca-module-edit-hook').trigger('click');
		}

		if ( jQuery( '.dslc-accordion-item', dslcAccordion ).length > 1 ) {

			dslcAccordionItem.remove();

			if ( ! jQuery( '.dslc-accordion-item.dslc-active', dslcAccordion ).length ) {
				jQuery( '.dslc-accordion-hook:first', dslcAccordion ).trigger('click');
			}

			dslc_accordion_generate_code( dslcAccordion );
		} else {

			alert( 'You can not delete the last remaining accordion item.' );
		}

		e.stopPropagation()
	});

	jQuery(document).on( 'click', '.dslca-move-up-accordion-hook, .dslca-move-down-accordion-hook', function(e){

		var dslcAccordion = jQuery(this).closest('.dslc-accordion'),
		dslcAccordionItem = jQuery(this).closest('.dslc-accordion-item'),
		dslcAccordionItemNext = dslcAccordionItem.next( '.dslc-accordion-item' ),
		dslcAccordionItemPrev = dslcAccordionItem.prev( '.dslc-accordion-item' );

		if ( ! jQuery(this).closest('.dslc-module-front').hasClass('dslca-module-being-edited') ) {

			jQuery(this).closest('.dslc-module-front').find('.dslca-module-edit-hook').trigger('click');
		}

		if ( jQuery(this).hasClass('dslca-move-down-accordion-hook') ) {

			dslcAccordionItem.insertAfter( dslcAccordionItemNext );
			dslc_accordion_generate_code( dslcAccordion );
		} else {

			dslcAccordionItem.insertBefore( dslcAccordionItemPrev );
			dslc_accordion_generate_code( dslcAccordion );
		}

		e.stopPropagation()
	});

	jQuery(document).on('blur paste keyup', '.dslc-accordion-title[contenteditable], .dslc-accordion-content[contenteditable]', function() {

		dslc_accordion_generate_code( jQuery(this).closest('.dslc-accordion') );
	}).on('focus', '.dslc-accordion-title[contenteditable], .dslc-accordion-content[contenteditable]', function() {

		if ( ! jQuery(this).closest('.dslc-module-front').hasClass('dslca-module-being-edited') ) {

			jQuery(this).closest('.dslc-module-front').find('.dslca-module-edit-hook').trigger('click');
		}
	});

	jQuery(document).on( 'click', '.dslc-trigger-lightbox-gallery', function(e){

		e.preventDefault();

		if ( jQuery(this).closest('.dslc-post').length ) {

			jQuery(this).closest('.dslc-post').find('.dslc-lightbox-gallery a:first-child').trigger('click');
		} else if ( jQuery(this).closest('.dslc-col') ) {

			var imageIndex = jQuery(this).closest('.dslc-col').index();
			jQuery(this).closest('.dslc-module-front').find('.dslc-lightbox-gallery a:eq(' + imageIndex + ')').trigger('click');
		} else {

			jQuery(this).closest('.dslc-module-front').find('.dslc-lightbox-gallery a:first-child').trigger('click');
		}
	});

	/**
	 * Header Fixed/Absolute - https://github.com/live-composer/live-composer-page-builder/issues/787
	 */

	if ( jQuery( "#dslc-header" ).hasClass( "dslc-header-extra-padding" ) && !jQuery( "body" ).hasClass( "dslca-enabled" ) ) {

		var headerHeight = jQuery( "#dslc-header" ).height();
		jQuery( "#dslc-main .dslc-modules-section:first-child" ).css({ paddingTop : headerHeight });
	}


	/**
	 * Navigation Module
	 */
	/* Disabled in favour of CSS hover.
	jQuery( '.dslc-navigation li' ).mouseenter(function(){

		var subnav = jQuery(this).children('ul');

		if ( subnav.length ) {

			if ( jQuery(this).closest('.dslc-navigation').hasClass('dslc-navigation-sub-position-center') ) {

				var parentWidth = jQuery(this).closest('li').width(),
				subnavWidth = subnav.outerWidth(),
				offsetToCenter = parseInt( parentWidth ) / 2 - parseInt( subnavWidth ) / 2 + 'px';

				subnav.css({ 'left' : offsetToCenter });
			}

			// Display child menu
			subnav.css({ 'display' : 'block' });

			var elOffsetLeft = subnav.offset().left;
			var elWidth = subnav.outerWidth();
			var docWidth = jQuery('body').width();

			if ( docWidth < ( elOffsetLeft + elWidth ) ) {
				subnav.addClass('dslc-navigation-invert-subnav');
			}

			// Show child menu
			jQuery(this).children('ul').stop().animate({ opacity : 1 }, 300 );
		}
	}).mouseleave(function(){

		jQuery(this).children('ul').stop().animate({ opacity : 0 }, 300, function(){

			jQuery(this).css({ 'display' : 'none' }).children('ul').removeClass('dslc-navigation-invert-subnav');
		});
	});
	*/
	dslc_check_viewport();
	dslc_check_progress_bar_viewport();

	jQuery(document).on( 'scroll', function(){

		dslc_check_viewport();
		dslc_check_progress_bar_viewport();
	});

	/*
	 * Keep the footer at the bottom of the page.
	 */

	if ( jQuery("#dslc-footer").hasClass("dslc-footer-pos-relative") ) {
		var pageHeight, footer, footerHeight, content, contentMinHeight;

		pageHeight = jQuery('body').height();
		footer = jQuery('#dslc-footer');
		footerHeight = jQuery(footer).height();
		content = jQuery('#dslc-content');
		contentMinHeight = '100vh';

		// Account for Admin bar.
		if ( jQuery( '#wpadminbar' ).length ) {
			contentMinHeight = '96.7vh';
		}

		if (pageHeight < jQuery(window).height()) {

			jQuery(footer).css({
				'position': 'absolute',
				'bottom': '0',
				'width': '100%'
			});
			jQuery(content).css({
				'min-height': contentMinHeight,
				'padding-bottom': footerHeight + 'px'
			});
		}
	}
});

jQuery(document).ready(function($){
	dslc_responsive_classes();
	dslc_carousel();
	dslc_masonry();
	dslc_parallax();
	dslc_init_lightbox();
	window.dslc_sticky_row();

	// No need to wait for jQuery(window).load.
	// These functions will check if images loaded by itself.
});

jQuery(window).resize(function(){
	dslc_responsive_classes();
	dslc_carousel_responsive();
});
