/*
 * 	jalbum-projector.js - main jalbum bridge javascript library
 * 
 *	(c) Laszlo Molnar, 2020
 *	Licensed under Creative Commons Attribution-NonCommercial-ShareAlike 
 *	<http://creativecommons.org/licenses/by-nc-sa/3.0/>
 *
 *	requires: jQuery, laza-util, jalbum-album
 */

;(function($, $window, $document, $body, undefined) {	
	'use strict';
	
	/*
	 *	Auxillary routines for jalbum projector
	 */
	 
	var isElementInView = function(el) {
				var st = $window.scrollTop(),
					t = el.offset().top;

				return !((t >= st + $window.height()) || (t + el.outerHeight() <= t));
			},
			
		scriptName = 'jalbum-projector.js',
	
		log = function(txt) {
				if (console && txt && DEBUG) {
					if (txt.match(/^Error\:/i)) {
						console.error(scriptName + ' ' + txt);
					} else if (txt.match(/^Warning\:/i)) {
						console.warn(scriptName + ' ' + txt);
					} else if (txt.match(/^Info\:/i)) {
						console.info(scriptName + ' ' + txt);
					} else {
						console.log(scriptName + ' ' + txt);
					}
				}
			},
		
		// Random number between -base and +base 
		random = function(base) {
				return base * (1 - 2 * Math.random());
			};
			
		
	/*******************************
	 *		jalbum function
	 */
			
	$.fn.jalbum = function(options) {
		
			options = $.extend({}, $.fn.jalbum.defaults, options);
			
			var id = {
						projector:		'projector',
						prev:			'jprev',
						next:			'jnext',
						jalbum:			'jalbum',
						cards:			'jcards',
						card:			'jcard',
						caption:		'jcaption',
						title:			'jtitle',
						hasCaption:		'jhascaption',
						imageReady:		'jimageready',
						prevPage:		'jprevpg',
						nextPage:		'jnextpg',
						currPage:		'jcurrpg',
						paging:			'jpaging',
						shade:			'jshade',
						threedee:		'threedee',
						error:			'jerror'
					};
				
				
			
			return $(this).each(function() {
					var self 			= 	$(this),						// root element
						
																			// In the Block editor
						editorMode 		= 	self.parents('.jalbum-preview').length,
						
						settings		= 	self.attr('data-jalbum')? 
												$.extend({}, options, JSON.parse(self.attr('data-jalbum'))) 
												: 
												options,
						
						texts			=	self.attr('data-jalbum-texts')?
												$.extend({}, $.fn.jalbum.text, JSON.parse(self.attr('data-jalbum-texts')))
												:
												$.fn.jalbum.text,
												
						album,												// The album instance
						
						pw, 												// Projector width
						ph, 												// ... and height
						
						// Album
						tree,												// The container for the entire tree
						defer,												// Collection of the JSON promises
						topFolder,											// Pointer to the start folder
						ready,												// Album ready state
						ns				=	self.data('jalbum-ns'),			// Namespace
						albumlink,											// Link to album (or the start folder within)
						albumWindow,										// New window (tab) for album if opennew = true
						
						// Projector
						title			=	$(),							// Album title
						menu			=	$(),							// Menu options
						cards			= 	$(),							// All cards container
						items			= 	[],								// Selected items
						cdims			= 	[],								// Card target dimensions (masonry)
						curr			=	-1,								// Current item's index
						nxt				=	-1,								// Next number (e.g. random ordering)
						max 			= 	0,								// Maximum number of items
						sortBy,												// Sort by criteria
						sortOrder,											// Sort order 0: ascending, 1: descending, -1: random
						caption			=	$(),							// Current item's caption
						gridLayout,											// is grid type layout?
						hasSlideshow,										// has automatic lsideshow?
						threedee,											// using 3D transforms?
						to,													// slideshow timeout
						layoutTo		= null,								// layout refresh timeout
						fitTo			= null,								// Fit image timeout
						loaded			= false,							// has been loaded?
						
						// Opening link in same or new window
						
						openLink = function(link) {
							
								if (!settings.opennew) {
									window.location.href = link;
								} else if (albumWindow) {
									albumWindow.location = link;
									albumWindow.focus();
								} else {
									albumWindow = window.open(link, '_blank');
								}
							},
							
						// Settings projector dimensions
						
						setDims = function() {						
								var pb = self.css('paddingBottom');
								
								pw = self.outerWidth();
								ph = self.outerHeight() || pw * .75;
								
								if (pb.endsWith('%')) {
									ph += pw * parseInt(pb) / 100;
								}
								
								self.removeClass('landscape portrait')
									.addClass((pw > ph)? 'landscape' : 'portrait');
							},
													
						// Returns a random direction for animations that use direction
						
						randomDirection = function(d) {
							
								if (d.length > 0) {
									var a = [];
									for (var i = 0; i < d.length; i++) {
										switch(d[i].toLowerCase()) {
											case 'e': a.push([1,0]); break;
											case 's': a.push([0,1]); break;
											case 'w': a.push([-1,0]); break;
											case 'n': a.push([0,-1]); break;
										}
									}
									if (a.length === 1) {
										return a[0];
									} else if (a.length > 1) {
										return a[Math.floor(Math.random() * a.length)] || [1, 0];
									}
									// No valid direction: fall back to East
									return [1, 0];
								}
								
								// Random
								return (Math.random() >= 0.5)? [ (Math.random() >= 0.5)? 1 : -1, 0 ] : [ 0, (Math.random() >= 0.5)? 1 : -1];
							},
							
						// Slideshow next stage
						
						changeCard = function() {
								var n;
								
								clearTimeout(to);
								
								if (max < 2) {
									return;
								}
								
								if (sortBy === 'random') {
									do {
										n = Math.floor(Math.random() * items.length);
									} while (n === curr);
								} else {
									n = (curr + 1) % max;
								}
								
								if (settings.transition === 'coverflow') {
									loadCard(n, 1);
								} else {
									loadCard(n);
								}
								
								to = setTimeout(changeCard, settings.slideshowdelay + settings.transitionspeed);
							},
							
						// Fits all images into cards (that were not fitted previously)
						
						fitImages = function() {
								
								var loadcnt = 0;
								
								clearTimeout(fitTo);
								
								cards.find('.' + id.card).each(function(i) {
										var t = $(this);
										
										if (t.data('fitted')) {
											loadcnt++;
											return true;
										}
										
										var img = t.children('img');
										
										if (!img.length) {
											return true;
										}
										
										var	cw = t.outerWidth() || cards.outerWidth(),
											ch = t.outerHeight() || cards.outerHeight(),
											w = img[0].naturalWidth || img.width(),
											h = img[0].naturalHeight || img.height(),
											sc;
											
										if (!w || !h || !cw || !ch) {
											return true;
										}
										
										// Fit image: cover | fit
										if ((cw / w > ch / h) !== (settings.fit === 'fit')) {
											// Scale for width
											sc = cw / w;
											img.css({
													left:		0,
													top:		(ch - h * sc) / 2,
													width:		'100%'
												});
										} else {
											sc = ch / h;
											img.css({
													top:		0,
													left:		(cw - w * sc) / 2,
													height:		'100%'
												});
										}
										
										t.data('fitted', true);
											
										loadcnt++;
									});
								
									if (cards.find('.' + id.card).length > loadcnt) {
										// Still some cards left to be fitted
										fitTo = setTimeout(fitImages, 100);
									}
							},
							
						// Card ready to be displayed
						
						cardReady = function(e) {
							
								var // Direction is not yet used: used for Projector skin compatibility
									dir = 1,
									// Next card to show
									card = e? ((e instanceof jQuery)? e : $(e.target)).closest('.' + id.card) : cards.find('#c' + curr),
									// Dimensions
									getDim = function() {
											return [ (card.outerWidth() || cards.outerWidth()), (card.outerHeight() || cards.outerHeight()) ];
										},
									// Forcing layout
									flushTransitions = function(c) {
											if (c && c.length) {
												var img = c.children('img');
												window.getComputedStyle(c[0], null);
												if (img.length) {
													window.getComputedStyle(img[0], null);
												}
											}
										};
									
								card.addClass(id.imageReady);
								fitImages();
								
								if (hasSlideshow) {
									
									// Animate the scene
									
									var prev = card.prevAll(),
										
										removeTo = null,
										
										removePrev = function(e) {
												
												clearTimeout(removeTo);
												
												var tn = new Date(),
													t = e? $(this).closest('.' + id.card) : card.prevAll(),
													ts = t.data('ts') || t.children('.' + id.card).data('ts');
												
												if (!t.length) {
													//log('Already removed. callee = ' + (e? 'transitionEnd' : 'timeout'));
													return;
												}
												
												if (isNaN(ts) || (tn - ts) < (settings.transitionspeed * 0.9)) {
													//log('Premature remove attempt on "' + t.attr('id') + '"! (' + (tn - ts) + 'ms < ' + settings.transitionspeed + 'ms) callee = ' + (e? ('transitionEnd(' + e.originalEvent.propertyName + ')') : 'timeout'));
													return;
												}
												
												//log('Normal remove on "' + t.attr('id') + '" (' + (tn - ts) + 'ms) callee = ' + (e? ('transitionEnd(' + e.originalEvent.propertyName + ')') : 'timeout'));
												if (t.parent().hasClass(id.prevPage)) {
													t.parent().remove();
												} else if (settings.transition === 'coverflow') {
													// Keep one
													t.not(card.prev()).remove();
												} else {
													t.remove();
												}
											};
									
									if (dir > 0) {
										cards.removeClass('bwd').addClass('fwd');
									} else if (dir < 0) {
										cards.removeClass('fwd').addClass('bwd');
									}
									
									if (settings.transition !== 'carousel') {
										if (settings.transition === 'coverflow') {
											card.prev().prev().prevAll().remove();
										} else {
											card.prev().prevAll().remove();
										}
										prev = card.prevAll();
									}
									
									// Saving transition start date
									prev.data('ts', new Date());
									
									// Flushing CSS transitions
									flushTransitions(card);
									
									// Transition								
									switch (settings.transition) {
										
										/********************************************
										 *				Cross fade
										 */
										 
										case 'crossfade':
											
											if (prev.length) {
												var init = function() {
													
															card.css({
																	opacity:		0,
																	transition:		'none'
																});
															
															window.requestAnimationFrame(start);
														},
														
													start = function() {
														
															card.css({
																	opacity:		1,
																	transition:		'opacity ' + settings.transitionspeed + 'ms ease-in-out'
																});
															
															prev.one('transitionend', removePrev)
																.css({
																	opacity:		0
																});
																
															removeTo = setTimeout(removePrev, settings.transitionspeed + 20);
														};
														
												window.requestAnimationFrame(init);
											
											} else {
											
												window.requestAnimationFrame(function() {
														
														card.show().css({
																opacity:		1,
																transition:		'opacity ' + settings.transitionspeed + 'ms ease-in-out'
															});
													});
											}
											
											break;
											
										/********************************************
										 *					Zoom
										 */
										 
										case 'zoom':
											
											if (prev.length) {
												
												var init = function() {
													
															card.show().css({
																	opacity:		0,
																	transform:		'scale(' + ((dir > 0)? 0.9 : 1.11) + ')',
																	transition:		'none'
																});
															
															window.requestAnimationFrame(start);
														},
														
													start = function() {
														
															card.css({
																	opacity:		1,
																	transform:		'scale(1)',
																	transition:		'transform ' + settings.transitionspeed + 'ms ease-out, opacity ' + settings.transitionspeed + 'ms ease-in-out'
																});
																
															prev.one('transitionend', removePrev)
																.css({
																	opacity:		0,
																	transform:		'scale(' + ((dir > 0)? 1.11 : 0.9) + ')',
																	transition:		'transform ' + settings.transitionspeed + 'ms ease-out, opacity ' + settings.transitionspeed + 'ms ease-in-out'
																});
																														
															removeTo = setTimeout(removePrev, settings.transitionspeed + 20);
														};
																												
												window.requestAnimationFrame(init);
												
											} else {
												
												window.requestAnimationFrame(function() {
														
														card.show().css({
																	transform:		'scale(1)',
																	opacity:		1
																});
													});
											}
											
											break;
											
										/********************************************
										 *					Stack
										 */
										 
										case 'stack':
											
											
											if (prev.length) {
												
												var dim = getDim(),
													a = random(60),
													r = (a + 90) / 180 * Math.PI,
													d = Math.min(dim[0], dim[1]) / 4,
													
													init = function() {
														
															card.css({
																	opacity:		0,
																	transform:		'scale(0.92)',
																	transition:		'none'
																});
																
															//log('a = ' + a.toFixed(2) + 'o (' + r.toFixed(2) + 'rad) ' + ' d = ' + d);  
																
															window.requestAnimationFrame(start);
														},
														
													start = function() {
														
															card.css({
																	opacity:		1,
																	transform:		'scale(1)',
																	transition:		'transform ' + settings.transitionspeed + 'ms ease-out, opacity ' + (settings.transitionspeed / 2) + 'ms linear'
																});
															
															prev.one('transitionend', removePrev)
																.css({
																	opacity:		0,
																	transform:		'rotate(' + (0 - a / 10) + 'deg) translate(' + Math.round(d * Math.cos(r)) + 'px,' + dir * Math.round(0 - d * Math.sin(r)) + 'px)',
																	transition:		'transform ' + settings.transitionspeed + 'ms ease-out, opacity ' + (settings.transitionspeed / 2) + 'ms linear'
																});
															
															removeTo = setTimeout(removePrev, settings.transitionspeed + 20);
														};
													
												window.requestAnimationFrame(init);
											
											} else {
												
												window.requestAnimationFrame(function() {
														
														card.show().css({
																opacity:		1,
																transform:		'scale(1)',
																transition:		'opacity ' + Math.min(settings.transitionspeed / 2, 500) + 'ms linear'
															});
													});
											}
											
											break;
											
										/********************************************
										 *				Ken Burns
										 */
										 
										case 'kenburns':
											
											var dim = getDim(),
												img = card.children('img').eq(0),
												sc = 1.14 + random(0.2),
												a = random(1) * Math.PI,
												d = (Math.random() + 0.2) * Math.min(dim[0], dim[1]) * 0.08,
												dx = Math.round(d * Math.cos(a)),
												dy = Math.round(d * Math.sin(a)),
												
												init = function() {
											
														card.data({
																'sc': 			(sc > 1.14)? (sc - 0.2) : (sc + 0.2),
																'dx':			-dx,
																'dy':			-dy
															});
														
														img.css({
																transform: 		'translate(' + dx + 'px,' + dy + 'px) scale(' + sc + ')',
																transition:		'none'
															}).show();
															
												
											
														window.requestAnimationFrame(start);
															
													},
													
												start = function() {
													
														card.css({
																opacity:		1,
																transition:		'opacity ' + settings.transitionspeed + 'ms linear'
															});
														
														img.css({
																transform:		'scale(' + ((sc > 1.14)? (sc - 0.1) : (sc + 0.1)) + ') translate(0,0)',
																transition:		'transform ' + settings.transitionspeed + 'ms linear'
															});
														
														if (prev.length) {
															
															img = prev.children('img').eq(0);
															
															prev.css({
																	opacity: 		0,
																	transition:		'opacity ' + settings.transitionspeed + 'ms linear'
																});
															
															img.one('transitionend', removePrev)
																.css({
																	transform: 		'scale(' + prev.data('sc') + ') translate(' + prev.data('dx') + 'px,' + prev.data('dy') + 'px)'
																});
																
															removeTo = setTimeout(removePrev, settings.transitionspeed + 20);
														}
													};
													
											window.requestAnimationFrame(init);
											
											break;
											
										/********************************************
										 *					Slide
										 */
										 
										case 'slide':
											
											if (prev.length) {
												
												var dr = randomDirection(settings.direction || ''),
													dim = getDim(),
												
													init = function() {
													
															card.show().css({
																	opacity:		0.7,
																	transform: 		'translate(' + (dr[0] * dim[0]) + 'px,' + (dr[1] * dim[1]) + 'px)',
																	transition:		'none'
																});
															
															prev.css({
																	transition:		'none'
																});
																
															window.requestAnimationFrame(start);
																
														},
														
													start = function() {
														
															card.css({
																	opacity:		1,
																	transform:		'translate(0,0)',
																	transition:		'transform ' + settings.transitionspeed + 'ms ease-in-out, opacity ' + settings.transitionspeed + 'ms ease-out'
																});
														
															prev.one('transitionend', removePrev)
																.css({
																	opacity:		0.7,
																	transform: 		'translate(' + (0 - dr[0] * dim[0]) + 'px,' + (0 - dr[1] * dim[1]) + 'px)',
																	transition:		'transform ' + settings.transitionspeed + 'ms ease-in-out, opacity ' + settings.transitionspeed + 'ms ease-out'
																});
																
															removeTo = setTimeout(removePrev, settings.transitionspeed + 20);
														};
														
												window.requestAnimationFrame(init);
												
											} else {
												
												window.requestAnimationFrame(function() {
														
														card.show()
															.css({
																opacity:		1,
																transition:		'opacity ' + Math.min(settings.transitionspeed, 1000) + 'ms ease-out'
															});
													});
											}
											
											break;
											
										/********************************************
										 *					Swap
										 */
										 
										case 'swap':
											
											if (prev.length) {
												
												var dim = getDim(),
												
													init = function() {
												
															card.css({
																	zIndex:				0,
																	opacity:			0,
																	transform:			'scale(0.7) translate(0,-40px)',
																	transition:			'none'
																}).show();
																
															prev.css({
																	zIndex:				1,
																	opacity:			1,
																	transition:			'none'
																});
																
															window.requestAnimationFrame(start);
														},
														
													start = function() {
														
															card.css({
																	opacity:			1,
																	transform:			'scale(0.85) translate(' + (dim[0] / 2) + 'px,-40px)',
																	transition:			'transform ' + (settings.transitionspeed / 2) + 'ms ease-in-out, opacity ' + (settings.transitionspeed / 2) + 'ms linear'
																})
																.one('transitionend', function() {
																		window.requestAnimationFrame(half);
																	});

															prev.css({
																	transform:			'scale(0.7) translate(' + (0 - dim[0] * .55) + 'px,20px)',
																	transition:			'transform ' + (settings.transitionspeed / 2) + 'ms ease-in-out, opacity ' + (settings.transitionspeed / 2) + 'ms linear'
																});
																
															removeTo = setTimeout(removePrev, settings.transitionspeed + 30);
														},
														
													half = function() {
														
															card.css({
																	zIndex:				1,
																	transform:			'scale(1) translate(0,0)'
																});
															
															prev.css({
																	zIndex:				0,
																	opacity: 			0,
																	transform:			'scale(0.49) translate(0,40px)'
																})
																.one('transitionend', removePrev);
														};
											
												window.requestAnimationFrame(init);
												
											} else  {
												
												window.requestAnimationFrame(function() {
														
														card.css({
																zIndex:				1,
																opacity:			0,
																transform:			'scale(0.85)',
																transition:			'none'
															}).show();
															
														window.requestAnimationFrame(function() {
																
																card.css({
																		opacity:			1,
																		transform:			'scale(1)',
																		transition:			'transform ' + Math.min(settings.transitionspeed / 2, 500) + 'ms ease-in-out, opacity ' + Math.min(settings.transitionspeed / 2, 500) + 'ms linear'
																	});
														});
													});
											}
											
											break;
											
										/********************************************
										 *				Carousel
										 */
										 
										case 'carousel':
											
											var dim = getDim(),
												n = cards.children().index(card),
												d = Math.round(dim[0] / (2 * Math.tan(Math.PI / max))),
												r = Math.round(dim[0] / (2 * Math.sin(Math.PI / max))),
												a = 360 / max;
											
											if (cards.data('initialized')) {
												
												var start = function() {
													
															n = Math.floor((cards.data('num') || 0) / max) * max + (n || max);
															
															cards.data('num', n)
																.css({
																	transform:			'translateZ(-' + r + 'px) rotateY(-' + (n * a) + 'deg)',
																	transition:			'transform ' + settings.transitionspeed + 'ms'
																})
																.children().css({
																	opacity:			BACKFACEBUG? 0.9 : 0.6
																});
															
															card.css({
																	opacity:			1
																});
														};
													
												window.requestAnimationFrame(start);
												
											} else {
												
												var init = function() {
														
															cards.css({
																	transform:			'translateZ(-' + r + 'px) rotateY(0)',
																	transition:			'none'
																});
															
															cards.children().each(function(i) {
																	$(this).css({
																			// Trick to trigger rendering of the backside surfaces 
																			backfaceVisibility: 'visible',
																			opacity:			(i === n)? 1 : (BACKFACEBUG? 0.9 : 0.6),
																			transform:			'rotateY(' + (i * a) + 'deg) translateZ(' + d + 'px)',
																			transition:			'none'
																		})
																		.show();
																});
															
															cards.data({
																'num':				0,
																'initialized': 		true
															});
															
															window.requestAnimationFrame(function() {
																	cards.css({
																			transition:			'transform ' + settings.transitionspeed + 'ms'
																		});
																	
																	cards.children().css({
																			backfaceVisibility: 'hidden',
																			transition:			'opacity ' + settings.transitionspeed + 'ms ease-out'
																		});
																});
														};
											
												window.requestAnimationFrame(init);	
											}
											
											break;
											
										/********************************************
										 *					Flip
										 */
										 
										case 'flip':
											
											if (prev.length) {
												
												var init = function() {
													
															card.css({
																	opacity:			0
																})
																.show()
																.css({
																	transition:			'opacity ' + settings.transitionspeed + 'ms'
																});
																
															window.requestAnimationFrame(start);	
														},
													
													start = function() {
														
															prev.css({
																	opacity:			0
																});
															
															card.css({
																	opacity:			1
																});
															
															cards.css({
																	transform:			'rotateY(' + ((dir < 0)? '180' :  '-180') + 'deg)',
																	transition:			'transform ' + settings.transitionspeed + 'ms'
																}).on('transitionend', function(e) {
																	window.requestAnimationFrame(stop);	
																});
																
															removeTo = setTimeout(stop, settings.transitionspeed + 20);
														},
														
													stop = function() {
															
															removePrev();
															
															cards.css({
																	transition:			'none',
																	transform:			'rotateY(0)'
																});
														};
												
												window.requestAnimationFrame(init);	
												
											} else {
												
												window.requestAnimationFrame(function() {
														
														card.show()
															.css({
																opacity:		1,
																transition:		'opacity ' + Math.min(settings.transitionspeed, 1000) + 'ms'
															});
													});
											}
											
											break;

										/********************************************
										 *					Book
										 */
										 
										case 'book':
											
											cards.children('.' + id.paging).remove();
											
											var w 	= cards.width(),
												w2 	= w / 2,
												wrp,						// Wrap for prev
												wrn,						// Wrap for next
												paging,						// Paging (rotating)
												pgp,						// :	wrap for prev
												pgn,						// :	wrap for next
												shp,						// :	shade prev
												shn,						// :	shade next
												
												init = function() {
													
														if (cards.children('.' + id.prevPage).length) {
															cards.find('.' + id.prevPage + ' .' + id.card).unwrap();
														}
														
														prev.css({
																width: 			w,
																left:			(dir > 0)? 0 : -w2
															}).wrap(wrp = $('<div>', {
																'class':		id.prevPage	
															}));
													
														card.css({
																opacity:		1,
																width: 			w,
																left:			(dir > 0)? -w2 : 0
															}).wrap(wrn = $('<div>', {
																'class':		id.nextPage	
															})).show();
															
														// Paging wrap
														
														paging = $('<div>', {
																'class':		id.paging
															}).appendTo(cards);
														
														// Previous page on paging
														pgp = (prev.clone()).css({
																left:			(dir > 0)? '-100%' : 0
															}).appendTo(paging);
															
														// Next page on paging
														pgn = (card.show().clone()).css({
																opacity:		1
															}).appendTo(paging);
														
														shp = $('<div>', {
																	'class':		id.shade
																}).appendTo(pgp);
															
														shn = $('<div>', {
																	'class':		id.shade
																}).hide().appendTo(pgn);
																
														paging.show()
															.css({
																transition:			'transform ' + (settings.transitionspeed / 2) + 'ms linear'
															})
															.data('ts', new Date());
															
														window.requestAnimationFrame(start);	
													},
													
												start = function() {

													shp.css({
																transition:			'opacity ' + (settings.transitionspeed / 2) + 'ms ease-in',
																opacity:			.75
															});
														
														paging.one('transitionend', function() {
																window.requestAnimationFrame(half);
															}).css({
																transform:			'rotateY(' + ((dir > 0)? '-90' : '90') + 'deg)'
															});
															
														removeTo = setTimeout(stop, settings.transitionspeed + 30);
													},
													
												half = function() {
													
														pgp.hide();
														
														pgn.css('backface-visibility', 'visible').show();
														
														paging.one('transitionend', function(e) {
																window.requestAnimationFrame(stop);
															}).css({
																transform:			'rotateY(' + ((dir > 0)? '-180' : '180') + 'deg)'
															});
														
														shn.show().css({
																transition:			'opacity ' + (settings.transitionspeed / 2) + 'ms ease-out',
																opacity:			0
															});
														
													},
													
												stop = function() {
													
														if (paging) {
															paging.remove();
														}
														
														if (card.parent().hasClass(id.nextPage)) {
															card.unwrap('.' + id.nextPage).css('left', 0);
														}
														
														removePrev();
													};
													
											if (prev.length) {
												
												window.requestAnimationFrame(init);	
												
											} else {
												
												window.requestAnimationFrame(function() {
														
														card.show()
															.css({
																opacity:		1,
																transition:		'opacity ' + Math.min(settings.transitionspeed, 500) + 'ms'
															});
													});
											}
											
											break;
											
										/********************************************
										 *					Cube
										 */
										 
										case 'cube':
											
											var dim = getDim(),
												d = Math.round(dim[1] / 2),
												
												init = function() {
													
														card.css({
																opacity:			1,
																zIndex:				9999,
																backgroundColor:	'#666'
															})
															.show();
													
														window.requestAnimationFrame(start);
													},
											
												start = function() {
															
														card.css({
																transform:			'rotateX(' + ((dir < 0)? '90' : '270') + 'deg) translateZ(' + d + 'px)'
															});
			
														cards.css({
																transform:			'translateZ(' + d + 'px) rotateX(0)'
															})
															.on('transitionend', function(e) {
																window.requestAnimationFrame(stop);
															})
															.css({
																transition:			'transform ' + settings.transitionspeed + 'ms',
																transform:			'translateZ(-' + d + 'px) rotateX(' + (dir * 90) + 'deg)'
															});
															
														removeTo = setTimeout(stop, settings.transitionspeed + 30);
													},
													
												stop = function() {
													
														prev.remove();
														
														cards.css({
																transition:			'none',
																transform:			'translateZ(-' + d + 'px) rotateX(0)'
															});
														
														card.css({
																zIndex:				0,
																transform:			'rotateX(0) translateZ(' + d + 'px)'
															});
													};
												
											window.requestAnimationFrame(init);

											break;
										
										/********************************************
										 *				Coverflow
										 */
										 
										case 'coverflow':
											
											var next = card.next(),
												prev0 = card.prev(),
												prev1 = prev0.prev(),
												d = getDim()[0] / 8,
												halfTo = null;
												
												init = function() {
													
														// Preset
														prev1.css({
																zIndex:				0
															});
														
														card.css({
																zIndex:				2
															});
															
														if (!prev0.length) {
															// First card
															card.css({
																	opacity:			1,
																	transform:			'scale(0.5) translate3d(' + (d * settings.coverflowFactor[0]) + 'px,0,-' + (d * 2) + 'px) rotateY(-86deg)'
																})
																.show();
														}
													
														next.css({
																zIndex:				1,
																opacity:			0,
																transform:			'scale(0.4) translate3d(' + (d * settings.coverflowFactor[1]) + 'px,0,-' + (d * 3) + 'px) rotateY(-120deg)'
															})
															.show()
															.css({
																willChange:			'transform, opacity'
															});
														
														card.add(prev0).css({
																opacity:			1,
																transition:			'transform ' + settings.transitionspeed + 'ms ease-in-out'
															});
															
														next.add(prev1).css({
																transition:			'transform ' + settings.transitionspeed + 'ms ease-in-out, opacity ' + settings.transitionspeed + 'ms linear'
															});
														
														window.requestAnimationFrame(start);
													},
													
												start = function() {
														
														// Animations
														prev0.on('transitionend', function() {
																prev1.remove();
															})
															.css({
																transform:			'scale(0.5) translate3d(-' + (d * settings.coverflowFactor[0]) + 'px,0,-' + (d * 2) + 'px) rotateY(86deg)'
															});
																						
														next.css({
																opacity:			1,
																transform:			'scale(0.5) translate3d(' + (d * settings.coverflowFactor[0]) + 'px,0,-' + (d * 2) + 'px) rotateY(-86deg)'
															});
														
														card.css({
																opacity:			1,
																transform:			'scale(0.75) translate3d(0,0,0) rotateY(0)'
															});
													
														prev1.css({
																opacity:			0,
																transform:			'scale(0.4) translate3d(-' + (d * settings.coverflowFactor[1]) + 'px,0,-' + (d * 3) + 'px) rotateY(120deg)'
															});
														
														halfTo = setTimeout(function() {
																clearTimeout(halfTo);
																window.requestAnimationFrame(half);
															}, settings.transitionspeed * 0.5);
															
													},
													
												half = function() {
														card.css({
																zIndex:				3
															});
															prev0.css({
																zIndex:				1
															});
													};
												
											window.requestAnimationFrame(init);
											
											break;

										default:
									}
									
									// Next image
									to = setTimeout(changeCard, settings.slideshowdelay + settings.transitionspeed);
									
								} else {
									
									// Static layout
									
									flushTransitions(card);
									
									// Drop in animation
									
									card.css({
											opacity:		0,
											transform:		'translateY(-20px) scale(1.2)'
										})
										.show()
										.css({
											transition:		'transform 300ms cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 300ms linear'
										})
										.css({
											opacity:		1,
											transform:		'translateY(0) scale(1)'
										});
								}
							},
							
						// Load nth card (with ahead)
						
						loadCard = function(n, ahead) {
								
								if (sortBy === 'random') {
									curr = n;
								} else {
									curr = n % max;
									ahead = ahead? Math.min(ahead, max - curr) : 0;
								}
								
								var loadOne = function(n, doneFn) {	
										
											if (n >= items.length) {
												// Out of range :: error
												return $();
											}
											
											var // Create image container
												card = $('<div>', {
																id:				'c' + n,
																'class':		id.card + ' ' + (items[n][J.CATEGORY]||'folder') + ' ' + (hasSlideshow? settings.transition : '')
															}).appendTo(cards),
													
												img = $('<img>').appendTo(card),
												cw,
												ch;
										
											if (settings.type === 'masonry') {
												card.css(cdims[n]);
											}
											
											card.css('opacity', 0);
											
											cw = card.outerWidth() || cards.outerWidth();
											ch = card.outerHeight() || cards.outerHeight();
											
											// Create caption
											if (settings.captiontemplate) {
												var caption = $('<div>', {
														'class':	id.caption + ' ' + settings.captionplacement + ' ' + settings.captionstyle,
														html:		album.processTemplate(settings.captiontemplate, items[n], settings.removeEmptyTags)
														//html:		album.processTemplate(items[n], settings.captiontemplate, Math.round((cw + ch) / 6))
													}).appendTo(card);
													
												if (caption.is(':empty') || !caption.text()) {
													caption.remove();
												} else {
													if (settings.removeEmptyTags) {
														caption.children(':empty').remove();
													} else {
														card.addClass(id.hasCaption);
													}
												}
											}
										
											// Click handler
											card.on('click.' + ns, function(e) {
													var n = parseInt($(this).attr('id').substring(1)); 
													
													if (!gridLayout && !isNaN(n) && curr !== n) {
														loadCard(n);
													} else if (!settings.disablelinks) {
														if (e.target.nodeName === 'A' && $(e.target).parents('.' + id.caption).length) {
															// Avoid handling links in the caption
															return true;
														}
														
														openLink(settings.linktoindex? 
																albumlink 
																: 
																album.getLink(items[n])
															);
													} 
												});
											
											// Calling done function
											if ($.isFunction(doneFn)) {
												img.one('load', doneFn);
											}
											
											// Load image
											img.attr('src', ((cw > items[n][J.THUMB][J.WIDTH]) || 
												(ch > items[n][J.THUMB][J.HEIGHT]))? 
													album.getImagePath(items[n])
													:
													album.getThumbPath(items[n])
												);
											
											return img;
										},
										
									preloadNext = function(n) {
											
											if (items && items.length > n) {
												var img = $('<img>').appendTo($body);
												
												img.hide()
													.attr('src', 
														(items[n][J.IMAGE] && 
															((cards.outerWidth() > items[n][J.THUMB][J.WIDTH]) || 
															(cards.outerHeight() > items[n][J.THUMB][J.HEIGHT])))?
																album.getImagePath(items[n])
																:
																album.getThumbPath(items[n])
														)
													.one('load', function() {
															$(this).remove();
														});
											}
										};
										
								// Stop slideshow
								to = clearTimeout(to);
								
								if (ahead) {
									// More than 1 to load
									for (var i = 0; i <= ahead; i++) {
										if (!cards.children('#c' + (n + i)).length) {
											loadOne((n + i) % max);
										}
									}
									
									// Trigger only when all images loaded successfully
									cards.waitAllImg(null, cardReady);
									
									preloadNext((n + i) % max);
									
								} else {
									var card = cards.children('#c' + n);
									
									if (!card.length) {
										// Card not yet loaded
										loadOne(n, cardReady);
										
									} else {
										// Already there
										var img = card.find('img'); 
										
										if (img.length && img[0].naturalWidth !== 0 && img[0].complete) {
											// Images has already loaded
											//log('img(' + n + ') ready: ' + img[0].naturalWidth + ' complete: ' + img[0].complete);
											cardReady(img);
											
										} else {
											// Call when ready
											img.one('load', cardReady);
										}
									}
									
									if (!gridLayout) {
										// Preloading the next image
										preloadNext((n + 1) % max);
									}
								}
							},
														
						// Reads the max items number (or fall back to defaults)
							
						getMax = function() {
								if (settings.hasOwnProperty('layout')) {
									if (settings.type === 'grid') {
										var m = settings.layout.match(/grid-(\d)-(\d)/);
										if (m && m.length > 2) {
											return parseInt(m[1]) * parseInt(m[2]);
										}
									} else if (settings.type === 'mosaic') {
										var m = settings.layout.match(/mos-(\d)-(\d)(-(\d))?/);
										if (m && m.length > 2) {
											return parseInt(m[1]) + parseInt(m[2]) + (m[4]? parseInt(m[4]) : 0);
										}
									}
									return parseInt(settings.layout) || $.fn.jalbum.defaults.numberofcards[settings.type];
								}
									
								return $.fn.jalbum.defaults.numberofcards[settings.type];
							},
							
						// Preset masonry card dimensions
						
						masonryCardDimensions = function() {
							
								if (max < 2) {
									return;
								}
								
								var tar,					// total width (as aspect ratio)
									par = pw / ph,			// projector's aspect ratio
									rows,					// rows array
									ars = [],				// aspect ratios
									
									// Get aspect ratio
									getAr = function(i) {
											var it = items[i];
											if (!it) {
												log('Item ' + i + ' is missing! ' + '(max ' + items.length + ')');
												return 1;
											}
											return (it.hasOwnProperty(J.IMAGE))?
												it[J.IMAGE][J.WIDTH] / it[J.IMAGE][J.HEIGHT]
												:
												it[J.THUMB][J.WIDTH] / it[J.THUMB][J.HEIGHT];
										};
									
								// Total width of all items (in ar)
								for (var i = 0, tar = 0; i < max; i++) {
									tar += (ars[i] = getAr(i));
								}
								
								
								// More than 1 row?
								if (tar > 1.5 * par) {
									
									// Calculating row count - assuming eqaul areas 
									var rc = Math.ceil(1 / Math.sqrt(par / tar));
									// Rows array
									rows = new Array(rc);
									
									// Sorting into rows
									for (var i = 0, x = 0, r; i < max; i++) {
										// Position of picture center relative to total width
										r = Math.floor(rc * (x + ars[i] / 2) / tar);
										if (typeof rows[r] === 'undefined') {
											rows[r] = new Array();
										}
										// Put in the matching row
										rows[r].push(i);
										x += ars[i];
									}
									
									// Removing empty rows (shouldn't happen)
									for (var r = 0; r < rc; r++) {
										if (!rows[r]) {
											rows.splice(r, 1);
											rc--;
										}
									}
									
									// Horizontal sizing per row
									for (var r = 0, cf, i, l, n; r < rc; r++) {
										cf = 0;
										l = rows[r].length;
										// Total width of row
										for (i = 0; i < l; i++) {
											cf += ars[rows[r][i]];
										}
										// Contsraining pictures in this row 
										for (i = 0; i < l; i++) {
											n = rows[r][i];
											cdims[n] = {
												width:		((100 * ars[n] / cf) + '%'),
												height:		(ph / rc / cf)
											};
										}
									}
									
									// Vertical correction
									var cf = 0;
									// Total height of all rows
									for (var r = 0; r < rc; r++) {
										cf += cdims[rows[r][0]].height;
									}
									// Correction factor to fit in precisely
									cf = ph / cf;
									// Fixing heights by row
									for (var r = 0, h; r < rc; r++) {
										h = Math.round(cdims[rows[r][0]].height * cf);
										for (var i = 0, l = rows[r].length; i < l; 	i++) {
											cdims[rows[r][i]].height = h;
										}
									}
									
								} else {
									
									// 1 row :: correcting widths
									var cf = par / tar;
									for (i = 0; i < max; i++) {
										items[i]['targetWidth'] = (100 * ars[i] * cf) + '%';
										items[i]['targetHeight'] = ph;
									}
								}
							},
							
						// Updating layout after window size change
						
						layoutRefresh = function() {
						
								if (settings.type === 'masonry') {
									
									masonryCardDimensions();
									
									cards.find('.' + id.card).each(function(n) {
											var card = $(this);
											
											card.css(cdims[n]);
											var cw = card.outerWidth(),
												ch = card.outerHeight(),
												img = card.find('img').eq(0);
												
											img.attr('src', ((cw > items[n][J.THUMB][J.WIDTH]) || 
													(ch > items[n][J.THUMB][J.HEIGHT]))? 
														album.getImagePath(items[n])
														:
														album.getThumbPath(items[n])
													);
										});
								}
							},

						// Create grid type layouts
						
						loadCards = function() {
								var loadTo = null,
								
									loadOneAfterOther = function(n) {
											
											clearTimeout(loadTo);
											loadCard(n);
											
											if (n < (max - 1)) {
												loadTo = setTimeout(loadOneAfterOther, 100, n + 1);
											}
										};
								
								if (items.length < max) {
									max = items.length;
								}
								
								cards.addClass('max-' + max);
								
								if (gridLayout) {
									//&& items.length > 1) {
									if (settings.type === 'masonry') {
										masonryCardDimensions();	
									}
									loadOneAfterOther(0);
								} else if (settings.transition === 'carousel') {
									loadCard(0, max);	
								} else if (settings.transition === 'coverflow') {
									loadCard(0, 1);
								} else {
									loadCard(0);
								}
								
								loaded = true;
							},
							
						// Load images only when projector gets into view
							
						loadIfInView = function() {
								
								if (!loaded) {
									if (isElementInView(self)) {
										// In view!
										loadCards();
									} else {
										// Waits until the projector gets into viewport
										setTimeout(loadIfInView, 200);
									}
								}
							},
							
						// Returns the first N item, filters duplicates
						
						getFirstNItem = function(it, max) {
								var mx = Math.min(it.length, max);
								
								if (settings.skipduplicates) {
									var a = [],
										names = [],
										nm;
										
										for (var i = 0; i < it.length; i++) {
											// Thumb filename
											nm = it[i][J.THUMB][J.PATH].getFile();
											
											if (nm && names.indexOf(nm) === -1) {
												// No such yet -> add
												a.push(it[i]);
												
												if (a.length >= mx) {
													// Enough
													return a;
												}
												// Save name
												names.push(nm); 
											}
										}
										
										return a;
										
								}
								
								return it.splice(0, mx);
							},
						
						sortFn = function(a, b) {
										
								switch (sortBy) {
									
									case 'name':
										return (b[J.TITLE] || b[J.NAME] || '').localeCompare((a[J.TITLE] || a[J.NAME] || ''));
									
									case 'date-reverse':
										return 	(b.hasOwnProperty(J.DATES)?
													b[J.DATES][J.TAKENDATE] :  b[J.FILEDATE]) || 0 -
												(a.hasOwnProperty(J.DATES)?
													a[J.DATES][J.TAKENDATE] :  a[J.FILEDATE]) || 0;
									
									case 'date':
										return (a.hasOwnProperty(J.DATES)?
													a[J.DATES][J.TAKENDATE] :  a[J.FILEDATE]) || 0 -
												(b.hasOwnProperty(J.DATES)?
													b[J.DATES][J.TAKENDATE] :  b[J.FILEDATE]) || 0;
								}
							},
							
						// Ordering items
						
						orderItems = function(it, max) {
							
								var mx = Math.min(it.length, max);
								
								if (it.length < 2) {
									return it;
								}
									
								it.sort(sortFn);
								
								return getFirstNItem(it, mx);
							},
								
						// Select random items
						
						selectRandomItems = function(it, max) {
								var a = [],
									mx = Math.min(it.length, max);
									
								if (settings.skipduplicates) {
									
									for (var names = '', n, nm, j, i = 0; i < mx; i++) {
										if (a.length >= mx || !it.length) {
											return a;
										}
										// Finding unique image :: try max item.length times
										for (j = 0; j < it.length; j++) {
											n = Math.floor(Math.random() * it.length * 0.999);
											nm = it[n][J.THUMB][J.PATH].getFile();
											if (nm && names.indexOf(':' + nm + ':') === -1) {
												a.push(it.splice(n, 1)[0]);
												names += ':' + nm + ':';
												break;
											}
										}
									}
									
								} else {
									for (var n, i = 0; i < mx; i++) {
										n = Math.floor(Math.random() * it.length * 0.999);
										a.push(it.splice(n, 1)[0]);
									}
								}
									
								return a;	
							},
							
						// Items loaded - continue initializing the projector
						
						itemsReady = function() {
								
								if (options.ordering === 'random') {
									
									items = selectRandomItems(this, max);
									
								} else {
									
									if (options.ordering !== 'original') {
										items = orderItems(this, max);
									} else {
										items = getFirstNItem(this, max);
									}
								}
								
								if (items.length) {
									loadIfInView();
								}
								
								if (settings.titletemplate) {
									// Title
									title = $('<div>', {
											'class':	id.title + ' ' + settings.titleplacement + ' ' + settings.titlestyle + (settings.disablelinks? '' : ' linked'),
											html:		album.processTemplate(settings.titletemplate, tree, settings.removeEmptyTags)
										})
										.appendTo(self);
										
									if (settings.removeEmptyCaption) {
										title.children(':empty').remove();
									}
									
									// Link title	
									if (!settings.disablelinks) {
										title.find('h1,h2,h3,h4,h5,h6').on('click', function() {
												openLink(albumlink);
											});
									}
								}
								
								// Changing projector dimensions 
								$window.on('resize.' + ns + ' orientationchange.' + ns, function() {
										
										clearTimeout(layoutTo);
										setDims();
										layoutTo = setTimeout(layoutRefresh, 100);
									});
								
								// Start slideshow
								if (hasSlideshow) {
									to = setTimeout(changeCard, settings.slideshowdelay + settings.transitionspeed);
								}
								
							},
							
						// Album has loaded
						
						albumReady = function() {
							
								// folder = start folder
								// levels = depth below the start folder
								// max = maximum number
								// sortBy = sort criteria ( dateTaken|fileDate|dateAdded|fileSize|name )
								// sortOrder = 1: ascending 0: descending
								// quick
								
								if (!settings.folder || album.getFolder(settings.folder)) {
									items = album.collectNItem({
											folder:		settings.folder, 
											levels:		settings.depth,
											include:	settings.include,
											max:		max,
											sortBy:		sortBy,
											sortOrder:	sortOrder,
											ready:		itemsReady
										});
								} else {
									log('Invlaid folder: "' + settings.folder + '"');
								}
							},
								
						// Showing error popup	
						
						showError = function(err, param) {
							
								var el = ($('<div>')
											.css({
												position:			'absolute',
												width:				'80%',
												maxWidth:			'600px',
												top:				'50%',
												left:				'50%',
												transform:			'translate(-50%, -50%)',
												textAlign:			'center',
												padding:			'.5rem 1rem',
												backgroundColor:	'#900',
												color:				'#eee'
											})
											.append($('<h4>', {
													text:			texts.error
												}))
											.append($('<p>', {
													html:			(texts[err || 'unknownError'] || texts['unknownError']).template(param || '?')
												}))
										).appendTo(self.empty());
										
								/*
								el.find('a').css({
										color: 				'#fff',
										textDecoration:		'underline'
									});
								*/
								
								if (!editorMode) {
									// Removing error in production
									setTimeout(function() {
											el.fadeOut(function() {
													el.remove();
												});
										}, 6000);
								}
							},
			
						// Initializing album
						
						initAlbum = function() {
										
								// Max number
								max = getMax();
								
								// Determining type
								gridLayout = 'masonry.grid.mosaic.strip'.indexOf(settings.type) >= 0;
								hasSlideshow = 'slideshow.slider'.indexOf(settings.type) >= 0;
								threedee = !gridLayout && 'carousel.flip.book.cube.coverflow'.indexOf(settings.transition) >= 0;
								
								if (editorMode) {
									// No links in Editor mode
									settings.disablelinks = true;
								}
								
								setDims();
								
								// Storing namespace, adding id, classes
								var classes = [ id.jalbum, settings.type ];
								if (gridLayout) {
									classes.push('grid-like');
									if (settings.gap) {
										classes.push('gap-' + settings.gap);
									}
								} else {
									classes.push('slider-like');
									classes.push(settings.transition);
								}
								if (threedee) {
									classes.push(id.threedee);
								}
								
								// Name space
								ns = 'jalbum-' + Math.floor(Math.random() * 10000);
								
								self.data('jalbum-ns', ns)
									.attr('id', ns)
									.addClass(classes.join(' '));
								
								// Creating container object
								cards = $('<div>').appendTo(self);
								// Adding classes
								classes = [ id.cards ];
								if (hasSlideshow) {
									classes.push(settings.transition);
								}
								if ((settings.type === 'grid' || settings.type === 'mosaic') && 
									settings.hasOwnProperty('layout')) {
									classes.push(settings.layout);
								}
								cards.addClass(classes.join(' '));
										
								if (!settings.hasOwnProperty('slideshowdelay')) {
									settings.slideshowdelay = $.fn.jalbum.defaults.timings[settings.transition || 'slide'][0];
								} else if (typeof settings.slideshowdelay !== 'number') {
									settings.slideshowdelay = parseInt(settings.slideshowdelay);
								}
								
								if (!settings.hasOwnProperty('transitionspeed')) {
									settings.transitionspeed = $.fn.jalbum.defaults.timings[settings.transition || 'slide'][1];
								} else if (typeof settings.transitionspeed !== 'number') {
									settings.transitionspeed = parseInt(settings.transitionspeed);
								}
								
								if (!settings.hasOwnProperty('layout')) {
									settings.layout = $.fn.jalbum.defaults.layouts[settings.type || 'grid'];
								} else if (settings.type !== 'grid' && settings.type !== 'mosaic' &&
									typeof settings.layout !== 'number') {
									settings.layout = parseInt(settings.layout);
								}
								
								if (typeof settings.depth !== 'number') {
									settings.depth = parseInt(settings.depth) || 2;
								}
								
								if (settings.hasOwnProperty('skipduplicates')) {
									settings.skipduplicates = settings.skipduplicates !== 'false' || settings.skipduplicates == true;
								}
								
								if (settings.hasOwnProperty('ordering')) {
									var o = settings.ordering.split('-');
									
									sortOrder = (o[1] === 'reverse')? 1 : 0;
									
									if (o[0] === 'date') {
										sortBy = J.TAKENDATE;
									} else if (o[0] === 'name') {
										sortBy = J.NAME;
									} else if (o[0] === 'size') {
										sortBy = J.FILESIZE;
									} else {
										if (settings.ordering === 'random') {
											sortOrder = -1;
										} else {
											sortOrder = 0;
										}
									}
								}
								
								// Album URL
								settings.albumurl = settings.albumurl.replace(/\\\//g, '/').replace(/\/$/, '');
								if (!settings.albumurl.endsWith('/')) {
									// Adding '/' at the end
									settings.albumurl += '/';
								}
								
								if (location.protocol === 'https:' && settings.albumurl.startsWith('http:')) {
									// Choosing the appropriate protocol
									settings.albumurl = settings.albumurl.replace(/^http/, 'https');
								}
								
								// Subfolder
								if (settings.hasOwnProperty('folder')) {
									// Ensure no automatic type conversion happens e.g. '2018'
									settings.folder = settings.folder + '';
									// Remove leading '/'
									if (settings.folder.startsWith('/')) {
										settings.folder = settings.folder.substring(1);
									}
									// Increase depth with initial folder depth
									settings.depth += settings.folder.split('/').length;
								} else {
									settings.folder = '';
								}
								
								// Album link
								albumlink = settings.albumurl + settings.folder;
							
								album = new Album($, {
										albumPath:			settings.albumurl,
										relPath:			settings.folder,
										ready: 				albumReady,
										fatalError:			showError																	
									});
					
							};
							
						
					// Execution starts here
					
					if (typeof Album === UNDEF) {
						showError('missingLibrary', 'jalbum-album.js');
						log('Critical Error: Missing jalbum-album.js library!');
						return;
					}
					
					if (!settings.hasOwnProperty('albumurl')) {
						// No albumUrl, no joy
						log('Error: no album URL provided!');
						return;
					}
					
					if (typeof ns !== UNDEF) {
						// Recycling
						self.off('.' + ns);
						self.empty();
						self.removeClass();
					}
					
					initAlbum();
					
				}
			);
		};

	// Default settings	
	
	$.fn.jalbum.defaults = {
			type:							'slideshow',							// 'slideshow', 'slider', 'masonry', 'grid', 'mosaic', 'strip'
			transition:						'slide',								// 'crossfade', 'zoom', 'kenburns', 'swap', 'slide', 'stack'
			folder:							'',										// path to folder
			depth:							2,										// Dig this deep
			quickdiscovery:					false,									// Loads only the minimum number of folders						
			include:						'images',								// types to include
			exclude:						'webPage,webLocation',					// types to exclude
			skipduplicates:					true,									// Remove duplicate items with same filename?
			disablelinks:					false,									// disables linking to album
			linktoindex:					false,									// Always link to the main page instead of individual images
			fit:							'cover',								// 'cover', 'fill'
			maxzoom:						1.25,									// maximum allowed zoom
			autoplay:						true,
			numberofcards:					{										// Number of cards
												'slideshow':		12,				// Slideshow
												'slider':			3,				// Slider
												'masonry':			17,				// Masonry grid
												'grid':				12,				// Even sized thumbs 4x3 (landscape) or 3x4 (portrait)
												'mosaic':			4,				// Obe big / 3 small images
												'strip':			6				// A strip of thumbs
											},
			timings: 						{										// Slideshow timings
												'crossfade':		[ 1000, 2000 ],
												'zoom':				[ 1000, 2000 ],
												'kenburns':			[    0, 4000 ],
												'stack':			[ 1500, 1500 ],
												'slide':			[ 1500, 1500 ],
												'swap':				[ 2000, 1000 ],
												'carousel':			[ 2000, 1000 ],
												'flip':				[ 2000, 1000 ],
												'book':				[ 2000, 1000 ],
												'cube':				[ 2000, 1000 ],
												'coverflow':		[ 2000, 1000 ]
											},
			layouts:						{										// Default layouts
												'slideshow':		12,
												'grid':				'grid-4-3',
												'masonry':			17,
												'mosaic':			'mos-1-3',
												'strip':			6
											},
			coverflowFactor:				[ 6.4, 12.4 ],							//[ 5.9, 11.6 ],
			gap:							'',
			selecteditems:					false,									// or [ 1, 2, 3, 4 ]
			ordering:						'original',								// 'random', 'original', 'date', 'date-reverse', 'name'
			showDots:						false,									// Show dots in 'slider'
			titletemplate:					'',
			titleplacement:					'center top',
			titlestyle:						'white',
			captiontemplate:				'',
			captionplacement:				'center bottom',
			captionstyle:					'dark',
			removeEmptyTags:				true									// Remove empty HTML tags in captions?
		};
		
	// Texts
	$.fn.jalbum.text = {
			error:							'Error',
			databaseAccessDenied:			'The album\'s database file "{0}" is missing or access is denied. If the album comes from an external site ensure Cross Origin Resource Sharing (CORS) is enabled!',
			noSuchFolder:					'The given folder "{0}" does not exists, or its database file is missing!',
			missingLibrary:					'Missing "{0}" library!',
			unknownError:					'Unknown error: {0}'
		};
		
	// Starting on document ready
	$document.ready(function() {
			$('[data-jalbum]').jalbum();
		});
		
})(jQuery, jQuery(window), jQuery(document), jQuery('body'));
