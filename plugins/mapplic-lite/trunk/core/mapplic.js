/*
 * Mapplic Lite - Custom Interactive Map Plugin by @sekler
 * Version 1.0.1
 * https://www.mapplic.com/
 */

;(function($) {
	"use strict";

	var Mapplic = function(element) {

		var self = this;

		self.o = {
			source: 'locations.json',
			selector: '[id^=MLOC] > *, [id^=landmark] > *, svg > #items > *',
			iconfile: null,
			height: 'auto',
			landmark: false,
			portrait: 668,
			hidenofilter: false,
			hovertip: true,
			defaultstyle: null,
			moretext: null,
			action: 'default',
			marker: '',
			developer: false,

			// sidebar
			sidebar: true,
			search: true,
			searchfields: ['title'],

			// zoom
			zoom: true,
			clearbutton: true,
			zoombuttons: true,
			mousewheel: true,
			mapfill: false,
			zoommargin: 200,
			maxscale: 3,
		};

		self.loc = {
			more: 'More',
			search: 'Search',
			iconfile: 'mapplic/images/icons.svg'
		}

		self.el = element;

		self.init = function(options) {

			// merging options with defaults
			self.o = $.extend(self.o, options);
			if (typeof mapplic_localization !== 'undefined') self.loc = $.extend(self.loc, mapplic_localization);
			if (self.o.iconfile) self.loc.iconfile = self.o.iconfile;

			self.el.addClass('mapplic-element mapplic-loading');

			// process map data
			var data = self.el.data('mapdata');
			if (self.el.data('mapdata')) {
				self.el.removeAttr('data-mapdata');
				processData(self.el.data('mapdata'));
				self.el.removeClass('mapplic-loading');
			}
			else if (typeof self.o.source === 'string') {
				// loading .json file with AJAX
				$.getJSON(self.o.source, function(data) {
					processData(data);
					self.el.removeClass('mapplic-loading');
				}).fail(function() { // Failure: couldn't load JSON file or it is invalid.
					console.error('Couldn\'t load map data. (Make sure to run the script through web server)');
					self.el.removeClass('mapplic-loading').addClass('mapplic-error');
					alert('Data file missing or invalid!\nMake sure to run the script through web server.');
				});
			}
			else {
				// inline json object
				processData(self.o.source);
				self.el.removeClass('mapplic-loading');
			}

			return self;
		}

		// tooltip
		function Tooltip() {
			this.el = null;
			this.pin = null;
			this.shift = 6;
			this.drop = 0;
			this.location = null;

			this.init = function(location) {
				var s = this;

				// markup
				this.el = $('<div></div>').addClass('mapplic-tooltip');
				this.wrap = $('<div></div>').addClass('mapplic-tooltip-wrap').appendTo(this.el);
				this.close = $('<button></button>').append(getIcon('icon-cross')).addClass('mapplic-tooltip-close').on('click touchend', function(e) {
					e.preventDefault();
					self.hideLocation();
					if (!self.o.zoom) self.moveTo(0.5, 0.5, self.fitscale, 400);
				}).appendTo(this.wrap);
				this.image = $('<img>').addClass('mapplic-image').hide().appendTo(this.wrap);
				this.body = $('<div></div>').addClass('mapplic-tooltip-body').appendTo(this.wrap);
				this.title = $('<h4></h4>').addClass('mapplic-tooltip-title').appendTo(this.body);
				this.content = $('<div></div>').addClass('mapplic-tooltip-content').appendTo(this.body);
				this.desc = $('<div></div>').addClass('mapplic-tooltip-description').appendTo(this.content);
				this.link = $('<a>' + self.loc.more + '</a>').addClass('mapplic-popup-link').attr('href', '#').hide().appendTo(this.body);
				this.triangle = $('<div></div>').addClass('mapplic-tooltip-triangle').prependTo(this.wrap);

				$('.mapplic-layer.mapplic-visible', self.map).append(this.el);

				this.el.css({'transform': 'scale(' + 1/self.scale + ')'});

				if (location) this.show(location);

				// close tooltip
				$(document).on('keyup.mapplic', function(e) {
					e.stopImmediatePropagation();
					if ((e.keyCode === 27)) {
						self.hideLocation();
						if (!self.o.zoom) self.moveTo(0.5, 0.5, self.fitscale, 400);
					}
				});

				return this;
			}

			this.show = function(location) {
				if (location) {
					var s = this;

					this.location = location;
					if (self.hovertip) self.hovertip.hide();

					if (location.image) {
						this.image.attr('src', '');
						this.image.attr('src', location.image).show();
					}
					else this.image.hide();

					if (location.link) {
						this.link.attr('href', location.link).css('background-color', '').show();
						var color = getColor(location);
						if (color) this.link.css('background-color', color);
					}
					else this.link.hide();

					this.title.text(location.title);
					if (location.description) this.desc.html(location.description);
					else this.desc.empty();
					this.content[0].scrollTop = 0;

					// shift
					this.pin = $('.mapplic-pin[data-location="' + location.id + '"]');
					if (this.pin.length == 0) {
						this.shift = 20;
					}
					else this.shift = Math.abs(parseFloat(this.pin.css('margin-top'))) + 20;
				
					// making it visible
					this.el.stop().css({opacity: 1}).show();
					this.position();
					if (self.o.zoom) this.zoom(location);

					// loading & positioning
					$('img', this.el).off('load').on('load', function() {
						s.position();
						if (self.o.zoom) s.zoom(location);
					});
				}
			}

			this.position = function() {
				if (this.location) {
					this.el.css({
						left: (this.location.x * 100) + '%',
						top: (this.location.y * 100) + '%'
					});
					this.drop = this.el.outerHeight() + this.shift;
				}
			}

			this.zoom = function(location) {
				var ry = 0.5,
					zoom = location.zoom ? parseFloat(location.zoom)/self.o.maxscale : 1;

				ry = (self.container.el.height()/2 + this.drop/2) / self.container.el.height();
				self.moveTo(location.x, location.y, zoom, 600, ry);
			}

			this.hide = function() {
				var s = this;

				this.location = null;
				this.el.stop().fadeOut(200, function() { $(this).remove(); });
			}
		}

		// hover tooltip
		function HoverTooltip() {
			this.el = null;
			this.pin = null;
			this.shift = 6;

			this.init = function() {
				var s = this;

				// construct
				this.el = $('<div></div>').addClass('mapplic-tooltip mapplic-hovertip');
				this.wrap = $('<div></div>').addClass('mapplic-tooltip-wrap').appendTo(this.el);
				this.title = $('<h4></h4>').addClass('mapplic-tooltip-title').appendTo(this.wrap);
				if (self.o.hovertipdesc) this.desc = $('<div></div>').addClass('mapplic-tooltip-description').appendTo(this.wrap);
				this.triangle = $('<div></div>').addClass('mapplic-tooltip-triangle').appendTo(this.wrap);

				// events 
				// markers + old svg
				$(self.map).on('mouseover', '.mapplic-pin', function() {
					var id = $(this).data('location');

					s.pin = $('.mapplic-pin[data-location="' + id + '"]');
					s.shift = Math.abs(parseFloat(s.pin.css('margin-top'))) + 20;

					var location = self.l[id];
					if (location && location.title) s.show(location);
				}).on('mouseout', function() {
					s.hide();
				});

				// new svg
				if (self.o.selector) {
					$(self.map).on('mouseover', self.o.selector, function() {
						var location = self.l[$(this).attr('id')];
						s.shift = 20;
						if (location && location.title) s.show(location);
					}).on('mouseout', function() {
						s.hide();
					});
				}

				self.el.on('positionchanged', {s: s},  this.repos);

				self.map.append(this.el);

				return this;
			}

			this.repos = function(e) {
				var s = e.data.s,
					tx = '-50%',
					ty = '-100%';

				// vertical
				if (s.el.position().top < s.wrap.outerHeight() + 36) {
					s.el.addClass('mapplic-tooltip-bottom');
					ty = "0%";
				}
				else {
					s.el.removeClass('mapplic-tooltip-bottom');
					ty = "-100%";
				}
			
				// horizontal
				if (s.el.position().left < s.wrap.outerWidth()/2) {
					if (s.el.position().left > 12) tx = -(100+(s.el.position().left)/s.wrap.outerWidth()*100)+100 + "%";
					else tx = "-10%";
				}
				else if (s.el.position().left > self.container.el.outerWidth() - s.wrap.outerWidth()/2) {
					if (s.el.position().left < self.container.el.outerWidth() - 12) tx = (self.container.el.outerWidth() - s.el.position().left)/s.wrap.outerWidth()*100-100 + "%";
					else tx = "-90%";
				}
				else tx = "-50%"

				s.wrap.css({ 'transform': 'translate(' + tx + ', ' + ty + ')' });
			}

			this.show = function(location) {
				if (self.location != location) {
					this.title.text(location.title);
					if (self.o.hovertipdesc) this.desc.html(location.description);
					this.position(location);

					this.el.stop().fadeIn(100);
				}
				this.repos({ data: { s: this}});
			}

			this.position = function(location) {
				if (location) {
					this.el.css({
						left: (location.x * 100) + '%',
						top: (location.y * 100) + '%'
					});

					this.drop = this.el.outerHeight() + this.shift;
				}
			}

			this.hide = function() {
				this.el.stop().fadeOut(200);
			}
		}

		// groups
		function Groups() {

			this.init = function() {}

			this.addGroups = function(groups) {
				if (groups) {
					$.each(groups, function(index, group) {
						self.g[group.id] = group;
					});
				}

				if (self.o.sidebar) self.sidebar.addCategories(groups);
			}
		}

		// sidebar
		function Sidebar() {
			this.el = null;
			this.g = {};
			this.filter = null;
			this.input = null;
			this.list = null;
			this.tags = null;
			this.taglist = {};

			this.init = function() {
				var s = this;

				this.el = $('<div></div>').addClass('mapplic-sidebar').appendTo(self.el);

				this.filter = $('<div></div>').addClass('mapplic-filter').append(getIcon('icon-magnifier')).appendTo(this.el);
				this.tags = $('<div></div>').addClass('mapplic-filter-tags').appendTo(this.filter);

				this.input = $('<input>').attr({'type': 'text', 'spellcheck': 'false', 'placeholder': self.loc.search}).addClass('mapplic-search-input').keyup(function(e) {
					s.search();
					if (e.keyCode == 13) $('li > a', s.el).filter(':visible:first').click();
				});
				if (self.o.search) this.input.prependTo(this.filter);

				self.clear = $('<button></button>').append(getIcon('icon-cross')).addClass('mapplic-search-clear').click(function(e) {
					e.preventDefault();
					s.input.val('');
					s.search();
				}).appendTo(this.filter);

				var container = $('<div></div>').addClass('mapplic-list-container').appendTo(this.el);
				this.list = $('<ol></ol>').addClass('mapplic-list').appendTo(container);

				if (!self.o.search) this.el.addClass('mapplic-sidebar-nosearch');
			}

			this.addTag = function(item) {
				var s = this;
				if (s.taglist[item.id]) return false;

				var tag = $('<div></div>').addClass('mapplic-tag').text(item.title).appendTo(this.tags);
				$('<span></span>').appendTo(tag);
				if (item.color) tag.css('background-color', item.color);

				tag.click(function() {
					tag.remove();
					delete s.taglist[item.id];
					s.search();
				}).appendTo(tag);

				s.taglist[item.id] = true;
				s.search();
			}

			this.addCategories = function(categories) {
				var s = this;
				var expandable = $('<li></li>').addClass('mapplic-list-expandable'),
					expandablelist = $('<ol></ol>').appendTo(expandable);

				this.list.append(expandable);
			}

			this.countCategory = function() {
				$.each(self.g, function(i, group) {
					if (group.count) {
						group.count.text('(' + group.nr + ')');
						if (group.nr < 1) group.count.hide();
						else group.count.show();
					}
				});
			}

			this.addLocation = function(location) {
				var item = $('<li></li>').addClass('mapplic-list-location').attr('data-location', location.id);
				var link = $('<a></a>').attr('href', '#').click(function(e) {
					e.preventDefault();
					self.showLocation(location.id, 600);

					// scroll back to map on mobile
					if (($(window).width() < 668) && (location.action || self.o.action) != 'lightbox') {
						$('html, body').animate({
							scrollTop: self.container.el.offset().top
						}, 400);
					}
				}).appendTo(item);
				var color = getColor(location);
				if (color) item.css('border-color', color);
				if (self.o.hidenofilter) item.hide();

				if (location.thumbnail) $('<img>').attr('src', location.thumbnail).addClass('mapplic-thumbnail').appendTo(link);
				$('<h4></h4>').text(location.title).appendTo(link);
			
				// groups
				if (location.category) {
					var groups = location.category.toString().split(',');
					groups.forEach(function(group) { if (self.g[group]) self.g[group].nr++; });
				}

				this.list.append(item);

				return item;
			}

			// search
			this.search = function() {
				var keyword = this.input.val(),
					s = this;

				if (keyword) self.clear.fadeIn(100);
				else self.clear.fadeOut(100);

				// groups
				$.each(self.g, function(i, group) {
					if (group.list) {
						var shown = false;
						if (!$.isEmptyObject(s.taglist)) shown = false;
						else $.each(self.o.searchfields, function(i, field) { if (group[field] && !shown) shown = !(group[field].toLowerCase().indexOf(keyword.toLowerCase()) == -1); });

						if (shown) group.list.slideDown(200);
						else group.list.slideUp(200);
					}
				});

				// locations
				$.each(self.l, function(i, location) {
					if (location.list) {
						var shown = false;
						if (!self.o.hidenofilter || (!$.isEmptyObject(s.taglist) || keyword)) {
							$.each(self.o.searchfields, function(i, field) { if (location[field] && !shown) shown = !(location[field].toLowerCase().indexOf(keyword.toLowerCase()) == -1); });
							$.each(s.taglist, function(i, tag) { if (!location.category || location.category.indexOf(i) == -1) shown = false; });
						}

						if (shown) location.list.slideDown(200);
						else location.list.slideUp(200);
					}
				});
			}
		}

		// developer tools
		function DevTools() {
			this.el = null;

			this.init = function() {
				this.el = $('<div></div>').addClass('mapplic-coordinates').appendTo(self.container.el);
				this.el.append('x: ');
				$('<code></code>').addClass('mapplic-coordinates-x').appendTo(this.el);
				this.el.append(' y: ');
				$('<code></code>').addClass('mapplic-coordinates-y').appendTo(this.el);

				$('.mapplic-layer', self.map).on('mousemove', function(e) {
					var x = (e.pageX - self.map.offset().left) / self.map.width(),
						y = (e.pageY - self.map.offset().top) / self.map.height();
					$('.mapplic-coordinates-x').text(parseFloat(x).toFixed(4));
					$('.mapplic-coordinates-y').text(parseFloat(y).toFixed(4));
				});

				return this;
			}
		}		

		// clear button
		function ClearButton() {
			this.el = null;
			
			this.init = function() {
				this.el = $('<button></button>').append(getIcon('icon-reset')).addClass('mapplic-button mapplic-clear-button').appendTo(self.container.el);

				if (!self.o.zoombuttons) this.el.css('bottom', '0');

				this.el.on('click touchstart', function(e) {
					e.preventDefault();
					self.hideLocation();
					self.moveTo(0.5, 0.5, self.fitscale, 400);
				});

				return this;
			}

			this.update = function(scale) {
				if (scale == self.fitscale) this.el.stop().fadeOut(200);
				else this.el.stop().fadeIn(200);
			}
		}

		// zoom buttons
		function ZoomButtons() {
			this.el = null;
		
			this.init = function() {
				this.el = $('<div></div>').addClass('mapplic-zoom-buttons').appendTo(self.container.el);

				// zoom-in button
				this.zoomin = $('<button></button>').append(getIcon('icon-plus')).addClass('mapplic-button mapplic-zoomin-button').appendTo(this.el);
				this.zoomin.on('click touchstart', function() {
					self.container.stopMomentum();

					var scale = self.scale;
					self.scale = normalizeScale(scale + scale * 0.8);

					self.x = normalizeX(self.x - (self.container.el.width() / 2 - self.x) * (self.scale / scale - 1));
					self.y = normalizeY(self.y - (self.container.el.height() / 2 - self.y) * (self.scale / scale - 1));

					zoomTo(self.x, self.y, self.scale, 400, 'ease');
				});

				// zoom-out button
				this.zoomout = $('<button></button>').append(getIcon('icon-minus')).addClass('mapplic-button mapplic-zoomout-button').appendTo(this.el);
				this.zoomout.on('click touchstart', function() {
					self.container.stopMomentum();

					var scale = self.scale;
					self.scale = normalizeScale(scale - scale * 0.4);

					self.x = normalizeX(self.x - (self.container.el.width() / 2 - self.x) * (self.scale / scale - 1));
					self.y = normalizeY(self.y - (self.container.el.height() / 2 - self.y) * (self.scale / scale - 1));

					zoomTo(self.x, self.y, self.scale, 400, 'ease');
				});

				return this;
			}

			this.update = function(scale) {
				this.zoomin.removeClass('mapplic-disabled');
				this.zoomout.removeClass('mapplic-disabled');
				if (scale == self.fitscale) this.zoomout.addClass('mapplic-disabled');
				else if (scale == 1) this.zoomin.addClass('mapplic-disabled');
			}
		}

		// styles
		function Styles() {
			this.data = null;

			this.init = function(data) {
				this.data = data;
				this.process(data);
				return this;
			}

			this.process = function(styles) {
				var css = '';

				if (styles) {
					styles.forEach(function(s) {
						if (s.base) {
							css += '.' + s.class + '.mapplic-clickable:not(g), g.' + s.class + '.mapplic-clickable > * {\n';
							$.each(s.base, function(prop, val) { css += '	' + prop + ': ' + val + ';\n' });
							css += '}\n\n';
						}

						if (s.hover) {
							css += '.' + s.class + '.mapplic-highlight:not(g), g.' + s.class + '.mapplic-highlight > *, .' + s.class + '.mapplic-clickable:not(g):hover, g.' + s.class + '.mapplic-clickable:hover > * {\n';
							$.each(s.hover, function(prop, val) { css += '	' + prop + ': ' + val + ';\n' });
							css += '}\n\n';
						}

						if (s.active) {
							css += '.'+ s.class + '.mapplic-active:not(g), g.' + s.class + '.mapplic-active > * {\n';
							$.each(s.active, function(prop, val) { css += '	' + prop + ': ' + val + ' !important;\n' });
							css += '}\n\n';
						}
					});
				}
				
				$('<style></style>').html(css).appendTo('body');
			}

			this.rule = function(selector, attribute, value) {
				var css = selector + ' {\n';
				css += '	' + attribute + ': ' + value + ';\n';
				css += '}\n\n';

				return css;
			}
		}

		// container
		function Container() {
			this.el = null;
			this.oldW = 0;
			this.oldH = 0;
			this.position = {x: 0, y: 0},
			this.momentum = null;

			this.init = function() {
				this.el = $('<div></div>').addClass('mapplic-container').appendTo(self.el);
				self.map = $('<div></div>').addClass('mapplic-map').appendTo(this.el);

				self.map.css({
					'width': self.contentWidth,
					'height': self.contentHeight
				});

				return this;
			}

			// returns container height (px)
			this.calcHeight = function(x) {
				var val = x.toString().replace('px', '');

				if ((val == 'auto') && (self.container.el))  val = self.container.el.width() * self.contentHeight / self.contentWidth; 
				else if (val.slice(-1) == '%') val = $(window).height() * val.replace('%', '') / 100;

				if ($.isNumeric(val)) return val;
				else return false;
			}

			this.resetZoom = function() {
				var init = self.l['init'];
				if (init) {
					var zoom = init.zoom ? parseFloat(init.zoom)/self.o.maxscale : 1;
					self.switchLevel(init.level);
					self.moveTo(init.x, init.y, zoom, 0);
				}
				else self.moveTo(0.5, 0.5, self.fitscale, 0);
			}

			this.revealChild = function(parent) {
				$('.mapplic-pin[data-location^=' + parent.id + '-]', self.map).addClass('mapplic-revealed');
				$('.mapplic-map-image [id^=' + parent.id + '-]', self.map).addClass('mapplic-revealed');
			}

			this.revealZoom = function(zoom) {
				$('.mapplic-pin[data-reveal]', self.map).each(function() {
					var reveal = $(this).data('reveal');
					if (zoom * self.o.maxscale >= reveal) $(this).addClass('mapplic-revealed');
					else $(this).removeClass('mapplic-revealed');
				});
			}

			this.coverAll = function() {
				$('.mapplic-revealed', self.map).removeClass('mapplic-revealed');
			}

			this.stopMomentum = function() {
				cancelAnimationFrame(this.momentum);
				if (this.momentum != null) {
					self.x = this.position.x;
					self.y = this.position.y;
				}
				this.momentum = null;
			}

			this.addControls = function() {
				self.map.addClass('mapplic-zoomable');

				document.ondragstart = function() { return false; } // IE drag fix

				// momentum
				var friction = 0.85,
					mouse = {x: 0, y: 0},
					pre = {x: 0, y: 0},
					previous = {x: this.position.x, y: this.position.y},
					velocity = {x: 0, y: 0};

				var s = this;
				var momentumStep = function() {
					s.momentum = requestAnimationFrame(momentumStep);

					if (self.map.hasClass('mapplic-dragging')) {
						pre.x = previous.x;
						pre.y = previous.y;

						previous.x = s.position.x;
						previous.y = s.position.y;

						s.position.x = mouse.x;
						s.position.y = mouse.y;
						
						velocity.x = previous.x - pre.x;
						velocity.y = previous.y - pre.y;
					}
					else {
						s.position.x += velocity.x;
						s.position.y += velocity.y;
						
						velocity.x *= friction;
						velocity.y *= friction;

						if (Math.abs(velocity.x) + Math.abs(velocity.y) < 0.1) {
							s.stopMomentum();
							self.x = s.position.x;
							self.y = s.position.y;
						}
					}
					s.position.x = normalizeX(s.position.x);
					s.position.y = normalizeY(s.position.y);

					zoomTo(s.position.x, s.position.y);
				}

				// drag & drop
				$('.mapplic-map-image', self.map).on('mousedown', function(e) {
					self.dragging = false;
					self.map.addClass('mapplic-dragging');
					
					s.stopMomentum();
					var initial = {
						x: e.pageX - self.x,
						y: e.pageY - self.y
					};

					mouse.x = normalizeX(e.pageX - initial.x);
					mouse.y = normalizeY(e.pageY - initial.y);
					momentumStep();

					self.map.on('mousemove', function(e) {
						self.dragging = true;

						mouse.x = normalizeX(e.pageX - initial.x);
						mouse.y = normalizeY(e.pageY - initial.y);
					});
				
					$(document).on('mouseup.mapplic', function() {
						$(document).off('mouseup.mapplic');
						self.map.off('mousemove');
						self.map.removeClass('mapplic-dragging');
					});
				});

				// mousewheel
				if (self.o.mousewheel) {
					$('.mapplic-map-image', self.el).bind('mousewheel DOMMouseScroll', function(e, delta) {
						e.preventDefault();
						s.stopMomentum();

						var scale = self.scale;

						self.scale = normalizeScale(scale + scale * delta / 5);

						self.x = normalizeX(self.x - (e.pageX - self.container.el.offset().left - self.x) * (self.scale/scale - 1));
						self.y = normalizeY(self.y - (e.pageY - self.container.el.offset().top - self.y) * (self.scale/scale - 1));

						zoomTo(self.x, self.y, self.scale, 200, 'ease');
					});
				}

				// touch
				var init1 = null,
					init2 = null,
					initD = 0,
					initScale = null;

				$('.mapplic-map-image', self.map).on('touchstart', function(e) {
					e.preventDefault();
					var eo = e.originalEvent;

					if (eo.touches.length == 1) {
						self.map.addClass('mapplic-dragging');
						self.dragging = false;

						s.stopMomentum();
						
						init1 = {
							x: eo.touches[0].pageX - self.x,
							y: eo.touches[0].pageY - self.y
						};

						self.firstcoord = { x: eo.touches[0].pageX, y: eo.touches[0].pageY };

						mouse = {
							x: normalizeX(eo.touches[0].pageX - init1.x),
							y: normalizeY(eo.touches[0].pageY - init1.y)
						};
						momentumStep();

						self.map.on('touchmove', function(e) {
							e.preventDefault();
							self.dragging = true;
							var eo = e.originalEvent;

							if (eo.touches.length == 1) {
								mouse.x = normalizeX(eo.touches[0].pageX - init1.x);
								mouse.y = normalizeY(eo.touches[0].pageY - init1.y);

								self.lastcoord = { x: eo.touches[0].pageX, y: eo.touches[0].pageY };
							}
							else if (eo.touches.length > 1) {
								var pos = {
									x: (eo.touches[0].pageX + eo.touches[1].pageX)/2,
									y: (eo.touches[0].pageY + eo.touches[1].pageY)/2
								}

								var dist = Math.sqrt(Math.pow(eo.touches[0].pageX - eo.touches[1].pageX, 2) + Math.pow(eo.touches[0].pageY - eo.touches[1].pageY, 2)) / initD;

								var scale = self.scale;
								self.scale = normalizeScale(initScale * dist);

								self.x = normalizeX(self.x - (pos.x - self.container.el.offset().left - self.x) * (self.scale/scale - 1));
								self.y = normalizeY(self.y - (pos.y - self.container.el.offset().top - self.y) * (self.scale/scale - 1));

								zoomTo(self.x, self.y, self.scale, 100, 'ease');
							}
						});

						$(document).on('touchend.mapplic', function(e) {
							e.preventDefault();
							var dragback = null,
								eo = e.originalEvent;

							if (eo.touches.length == 0) {
								clearTimeout(dragback);
								$(document).off('touchend.mapplic');
								self.map.off('touchmove');
								self.map.removeClass('mapplic-dragging');
								self.dragging = false;
							}
							else if (eo.touches.length == 1) {
								dragback = setTimeout(function() {
									self.map.addClass('mapplic-dragging');
									self.dragging = true;

									s.stopMomentum();
									init1 = {
										x: eo.touches[0].pageX - self.x,
										y: eo.touches[0].pageY - self.y
									};

									mouse = {
										x: normalizeX(eo.touches[0].pageX - init1.x),
										y: normalizeY(eo.touches[0].pageY - init1.y)
									};

									momentumStep();
								}, 60);
							}
						});
					}


					// pinch
					else if (eo.touches.length == 2) {
						self.dragging = true;
						self.map.addClass('mapplic-dragging');

						s.stopMomentum();

						init2 = { x: eo.touches[1].pageX - self.x, y: eo.touches[1].pageY - self.y };
						initD = Math.sqrt(Math.pow(init1.x - init2.x, 2) + Math.pow(init1.y - init2.y, 2));
						initScale = self.scale;

					}
				});
			}
		}

		// functions
		var processData = function(data) {
			self.data = data;
			self.g = {};
			self.l = {};
			var shownLevel = null;

			// extend options
			self.o = $.extend(self.o, data);
			self.o.zoommargin = parseFloat(self.o.zoommargin);
			self.o.maxscale = parseFloat(self.o.maxscale);

			// more text
			if (self.o.moretext) self.loc.more = self.o.moretext;

			// height of container
			if (self.el.data('height')) self.o.height = self.el.data('height');
			self.contentWidth = parseFloat(data.mapwidth);
			self.contentHeight = parseFloat(data.mapheight);

			// limiting to scale 1
			self.contentWidth = self.contentWidth * self.o.maxscale;
			self.contentHeight = self.contentHeight * self.o.maxscale;

			// create container
			self.container = new Container().init();
			self.levelselect = $('<select></select>').addClass('mapplic-levels-select');

			// styles
			self.styles = new Styles().init(self.o.styles);

			// create sidebar
			if (self.o.sidebar) {
				self.sidebar = new Sidebar();
				self.sidebar.init();
			}
			else self.container.el.css('width', '100%');

			// groups
			self.groups = new Groups();
			self.groups.addGroups(data.groups || data.categories);

			// trigger event
			self.el.trigger('mapstart', self);

			// iterate through levels
			var levelnr = 0,
				toload = 0;

			if (data.levels[0]) {
				var level = data.levels[0];
				var source = level.map,
					extension = source.substr((source.lastIndexOf('.') + 1)).toLowerCase();

				// new map layer
				var layer = $('<div></div>').addClass('mapplic-layer').attr('data-floor', level.id).appendTo(self.map);
				switch (extension) {

					// image formats
					case 'jpg': case 'jpeg': case 'png': case 'gif':
						$('<img>').attr('src', source).addClass('mapplic-map-image').appendTo(layer);
						self.addLocations(level.locations, level.id);
						break;

					// vector format
					case 'svg':
						toload++;
						var mapimage = $('<div></div>').addClass('mapplic-map-image').appendTo(layer);

						$('<div></div>').load(source, function() {

							// sanitize svg - XSS protection
							$('script', this).remove();
							mapimage.html($(this).html());

							// illustrator duplicate id fix
							$(self.o.selector, mapimage).each(function() {
								var id = $(this).attr('id');
								if (id) $(this).attr('id', id.replace(/_[1-9]+_$/g, ''));
							});

							// add locations
							self.addLocations(level.locations, level.id);

							// click event
							$(self.o.selector, mapimage).on('click touchend', function(e) {
								var shift = Math.abs(self.firstcoord.x - self.lastcoord.x) + Math.abs(self.firstcoord.y - self.lastcoord.y);
								if (!self.dragging || shift < 4) self.showLocation($(this).attr('id'), 600);
							});

							// trigger event(s)
							self.el.trigger('svgloaded', [mapimage, level.id]);
							toload--;
							if (toload == 0) mapReady();
						});
						break;

					// others 
					default:
						alert('File type ' + extension + ' is not supported!');
				}

				// build layer control
				self.levelselect.prepend($('<option></option>').attr('value', level.id).text(level.title));

				// shown level
				if (!shownLevel || level.show)	shownLevel = level.id;

				levelnr++;
			}

			// COMPONENTS
			self.tooltips = Array();
			if (self.o.hovertip) self.hovertip = new HoverTooltip().init();
			if (self.o.clearbutton) self.clearbutton = new ClearButton().init();
			if (self.o.zoombuttons) self.zoombuttons = new ZoomButtons().init();
			if (self.o.developer) self.devtools = new DevTools().init();

			// level switcher
			if (levelnr > 1) {
				self.levels = $('<div></div>').addClass('mapplic-levels');
				var up = $('<button></button>').append(getIcon('icon-arrow-up')).addClass('mapplic-levels-up').appendTo(self.levels);
				self.levelselect.appendTo(self.levels);
				var down = $('<button></button>').append(getIcon('icon-arrow-down')).addClass('mapplic-levels-down').appendTo(self.levels);
				self.container.el.append(self.levels);
			
				self.levelselect.change(function() {
					var value = $(this).val();
					self.switchLevel(value);
				});
			
				up.click(function(e) {
					e.preventDefault();
					if (!$(this).hasClass('mapplic-disabled')) self.switchLevel('+');
				});

				down.click(function(e) {
					e.preventDefault();
					if (!$(this).hasClass('mapplic-disabled')) self.switchLevel('-');
				});
			}
			self.switchLevel(shownLevel);

			if (self.o.portrait === 'true') self.o.portrait = true;

			// browser resize
			$(window).resize(function() {

				if (self.o.portrait == true || $.isNumeric(self.o.portrait) && $(window).width() < parseFloat(self.o.portrait)) {
					self.el.addClass('mapplic-portrait');
					if (self.el.hasClass('mapplic-fullscreen')) self.container.el.height($(window).height());
					else {
						var height = Math.min(Math.max(self.container.el.width() * self.contentHeight / self.contentWidth, $(window).height() * 2/3), $(window).height() - 66); 
						self.container.el.height(height);
					}
				}
				else {
					self.el.removeClass('mapplic-portrait');
					self.container.el.height('100%');
					self.el.height(self.container.calcHeight(self.o.height));
				}

				var wr = self.container.el.width() / self.contentWidth,
					hr = self.container.el.height() / self.contentHeight;

				if (self.o.mapfill) {
					if (wr > hr) self.fitscale = wr;
					else self.fitscale = hr;
				}
				else {
					if (wr < hr) self.fitscale = wr;
					else self.fitscale = hr;
				}

				if (self.container.oldW != self.container.el.width() || self.container.oldH != self.container.el.height()) {

					self.container.oldW = self.container.el.width();
					self.container.oldH = self.container.el.height();

					self.container.resetZoom();
				}
			}).resize();

			// trigger event
			if (toload == 0) mapReady();

			// controls
			if (self.o.zoom) self.container.addControls();
			self.firstcoord = self.lastcoord = {};

			// link to locations
			$(document).on('click', '.mapplic-location', function(e) {
				e.preventDefault();
				self.showLocation($(this).attr('href').substr(1), 400);
				$('html, body').animate({ scrollTop: self.container.el.offset().top }, 400);
			});
		}

		var mapReady = function() {
			// trigger event
			self.el.trigger('mapready', self);

			self.container.resetZoom();

			// landmark mode
			if (self.el.data('landmark')) self.o.landmark = self.el.data('landmark');
			if (self.o.landmark) {
				// Custom settings
				self.o.sidebar = false;
				self.o.zoombuttons = false;
				self.showLocation(self.o.landmark, 0);
			}
		}

		/* PRIVATE METHODS */

		// Web Mercator (EPSG:3857) lat/lng projection
		var latlngToPos = function(lat, lng) {
			var deltaLng = self.data.rightLng - self.data.leftLng,
				bottomLatDegree = self.data.bottomLat * Math.PI / 180,
				mapWidth = ((self.data.mapwidth / deltaLng) * 360) / (2 * Math.PI),
				mapOffsetY = (mapWidth / 2 * Math.log((1 + Math.sin(bottomLatDegree)) / (1 - Math.sin(bottomLatDegree))));

			lat = lat * Math.PI / 180;

			return {
				x: ((lng - self.data.leftLng) * (self.data.mapwidth / deltaLng)) / self.data.mapwidth,
				y: (self.data.mapheight - ((mapWidth / 2 * Math.log((1 + Math.sin(lat)) / (1 - Math.sin(lat)))) - mapOffsetY)) / self.data.mapheight
			};
		}

		var estimatedPosition = function(element) {
			if (!element || !(element[0] instanceof SVGElement)) return false;

			var	bbox = element[0].getBBox();
			var	padding = 40,
				wr = self.container.el.width() / (bbox.width + padding),
				hr = self.container.el.height() / (bbox.height + padding);

			return {
				x: (bbox.x + bbox.width/2) / self.contentWidth * self.o.maxscale,
				y: (bbox.y + bbox.height/2) / self.contentHeight * self.o.maxscale,
				scale: Math.min(wr, hr) / self.o.maxscale
			}
		}

		var getColor = function(location) {
			var groups = false;
			if (location.category) groups = location.category.toString().split(',');

			if (location.fill) return location.fill;
			else if (self.g[groups[0]] && self.g[groups[0]].color) return self.g[groups[0]].color;
			else return false;
		}

		var getIcon = function(name) {
			return '<svg class="mapplic-icon mapplic-' + name + '"><use xlink:href="' + self.loc.iconfile + '#' + name + '"></use></svg>';
		}

		// normalizing x, y and scale
		var normalizeX = function(x) {
			var minX = (self.container.el.width() - self.contentWidth * self.scale).toFixed(4);
			if (minX < 0) {
				if (x > self.o.zoommargin) x = self.o.zoommargin;
				else if (x < minX - self.o.zoommargin) x = minX - self.o.zoommargin;
			}
			else x = minX/2;

			return x;
		}

		var normalizeY = function(y) {
			var minY = (self.container.el.height() - self.contentHeight * self.scale).toFixed(4);
			if (minY < 0) {
				if (y > self.o.zoommargin) y = self.o.zoommargin;
				else if (y < minY - self.o.zoommargin) y = minY - self.o.zoommargin;
			}
			else y = minY/2;

			return y;
		}

		var normalizeScale = function(scale) {
			if (scale <= self.fitscale) scale = self.fitscale;
			else if (scale > 1) scale = 1;

			// zoom timeout
			clearTimeout(self.zoomTimeout);
			self.zoomTimeout = setTimeout(function() {
				if (self.zoombuttons) self.zoombuttons.update(scale);
				if (self.clearbutton) self.clearbutton.update(scale);
				if (scale == self.fitscale) {
					self.container.coverAll();
				}
				self.container.revealZoom(scale);
			}, 200);

			return scale;
		}

		var moveTimeout = null;

		var zoomTo = function(x, y, scale, d) {
			d = typeof d !== 'undefined' ? d/1000 : 0;

			// move class
			self.el.addClass('mapplic-move');
			clearTimeout(moveTimeout);
			moveTimeout = setTimeout(function() {
				self.el.removeClass('mapplic-move');
				self.el.trigger('positionchanged', location);
			}, 400);

			// transforms
			self.map.css({
				'transition': 'transform ' + d + 's',
				'transform': 'translate(' + x.toFixed(3) + 'px ,' + y.toFixed(3) + 'px) scale(' + self.scale.toFixed(3) + ')'
			});

			if (scale) {
				$('.mapplic-pin, .mapplic-tooltip', self.map).css({
					'transition': 'transform ' + d + 's',
					'transform': 'scale(' + 1/scale + ')'
				});
			}

			// trigger event
			self.el.trigger('positionchanged', location);
		}



		/* PUBLIC METHODS */
		self.switchLevel = function(target) {
			switch (target) {
				case '+':
					target = $('option:selected', self.levelselect).removeAttr('selected').prev().prop('selected', 'selected').val();
					break;
				case '-':
					target = $('option:selected', self.levelselect).removeAttr('selected').next().prop('selected', 'selected').val();
					break;
				default:
					$('option[value="' + target + '"]', self.levelselect).prop('selected', 'selected');
			}

			// no such layer
			if (!target) return;

			var layer = $('.mapplic-layer[data-floor="' + target + '"]', self.el);

			// target layer is already active
			if (layer.hasClass('mapplic-visible')) return;

			// show target layer
			$('.mapplic-layer.mapplic-visible', self.map).removeClass('mapplic-visible');
			layer.addClass('mapplic-visible');

			// update control
			var index = self.levelselect.get(0).selectedIndex,
				up = $('.mapplic-levels-up', self.el),
				down = $('.mapplic-levels-down', self.el);

			up.removeClass('mapplic-disabled');
			down.removeClass('mapplic-disabled');
			if (index == 0) up.addClass('mapplic-disabled');
			else if (index == self.levelselect.get(0).length - 1) down.addClass('mapplic-disabled');

			// trigger event
			self.el.trigger('levelswitched', target);
		}

		self.addTooltip = function(location) {
			var tooltip = new Tooltip().init(location);
			self.tooltips.push(tooltip);

			return tooltip.wrap[0];
		}

		self.closeTooltips = function() {
			self.tooltips.forEach(function(t, i) {
				t.hide();
				self.tooltips.splice(i, 1);
			});
		}

		self.moveTo = function(x, y, s, duration, ry) {
			duration = typeof duration !== 'undefined' ? duration : 400;
			ry = typeof ry !== 'undefined' ? ry : 0.5;
			s = typeof s !== 'undefined' ? s : self.scale/self.fitscale;

			self.container.stopMomentum();

			self.scale = normalizeScale(s);
			self.x = normalizeX(self.container.el.width() * 0.5 - self.scale * self.contentWidth * x);
			self.y = normalizeY(self.container.el.height() * ry - self.scale * self.contentHeight * y);

			zoomTo(self.x, self.y, self.scale, duration);
		}

		self.bboxZoom = function(element) {
			var pos = estimatedPosition(element);
			if (!pos) return false;

			self.moveTo(pos.x, pos.y, pos.scale, 600);
			return true;
		}

		self.addLocations = function(locations, levelid) {
			$.each(locations, function(index, location) {

				// first level if not set
				if (!location.level) {
					if (levelid) location.level = levelid;
					else location.level = self.data.levels[0].id;
				}

				// building the location object
				self.l[location.id] = location;

				// interactive element
				var elem = $('[id^=MLOC] > *[id="' + location.id + '"], [id^=landmark] > *[id="' + location.id + '"]', self.map);
				if (elem.length > 0) {
					elem.addClass('mapplic-clickable');
					if (location.style) elem.addClass(location.style);
					else if (self.g[location.category] && self.g[location.category].style) elem.addClass(self.g[location.category].style);
					else if (location.category && self.g[location.category[0]] && self.g[location.category[0]].style) elem.addClass(self.g[location.category[0]].style);
					else if (self.o.defaultstyle) elem.addClass(self.o.defaultstyle);
					location.el = elem;

					var fill = null;
					if (location.fill) fill = location.fill;

					if (fill) {
						elem.css('fill', fill);
						$('> *', elem).css('fill', fill);
					}
				}

				// geolocation
				if (location.lat && location.lng) {
					var pos = latlngToPos(location.lat, location.lng);
					location.x = pos.x;
					location.y = pos.y;
				}

				// estimated position
				if ((!location.x || !location.y) && elem) {
					var pos = estimatedPosition(location.el);
					location.x = pos.x;
					location.y = pos.y;
				}

				// marker
				if (!location.pin) location.pin = self.o.marker;
				if (location.pin.indexOf('hidden') == -1) {
					var level = $('.mapplic-layer[data-floor=' + location.level + ']', self.el);
					var pin = $('<a></a>').attr('href', '#').addClass('mapplic-pin').css({'top': (location.y * 100) + '%', 'left': (location.x * 100) + '%'}).appendTo(level);
					pin.on('click touchend', function(e) {
						e.preventDefault();
						self.showLocation(location.id, 600);
					});
					if (location.label) $('<span><span>' + location.label + '</span></span>').appendTo(pin);
					if (location.fill) pin.css({'background-color': location.fill, 'border-color': location.fill});
					if (location.pin.indexOf('pin-image') > -1 || location.pin.indexOf('pin-icon') > -1) pin.css('background-image', 'url(' + location.label + ')');
					if (location.reveal) pin.attr('data-reveal', location.reveal).css('visibility', 'hidden');
					if (location.category) {
						location.category = location.category.toString();
						pin.attr('data-category', location.category);
					}
					pin.attr('data-location', location.id);

					location.el = pin;
				}
				if (location.el) location.el.addClass(location.pin.replace('hidden', ''));

				// reveal mode
				if (location.action == 'reveal') $('.mapplic-pin[data-location^=' + location.id + ']', self.map).css('visibility', 'hidden');

				// add to sidebar
				if (self.sidebar && location.action != 'disabled' && !(location.hide == 'true' || location.hide == true)) location.list = self.sidebar.addLocation(location);
			});

			if (self.sidebar) self.sidebar.countCategory();
		}

		self.getLocationData = function(id) {
			return self.l[id];
		}

		self.showLocation = function(id, duration, check) {
			var location = self.location = self.l[id];
			if (!location) return false;

			var action = (location.action && location.action != 'default') ? location.action : self.o.action;
			if (action == 'disabled') return false;

			// trigger event
			self.el.trigger('locationopen', location);

			var content = null;
			self.closeTooltips();

			switch (action) {
				case 'open-link':
					window.location.href = location.link;
					return false;
				case 'open-link-new-tab':
					window.open(location.link);
					self.location = null;
					return false;
				default:
					self.switchLevel(location.level);
					content = self.addTooltip(location);
			}

			// active state
			$('.mapplic-active', self.el).removeClass('mapplic-active');
			if (location.el) location.el.addClass('mapplic-active');
			if (location.list) location.list.addClass('mapplic-active');

			// trigger event
			self.el.trigger('locationopened', [location, content]);
		}

		self.zoomLocation = function(loc) {
			var zoom = loc.zoom ? parseFloat(loc.zoom)/self.o.maxscale : 1;
			if (self.o.zoom) self.moveTo(loc.x, loc.y, zoom, 600);
		}

		self.hideLocation = function() {
			$('.mapplic-active', self.el).removeClass('mapplic-active');
			self.closeTooltips();
			self.location = null;

			// trigger event
			self.el.trigger('locationclosed');
		}

		self.updateLocation = function(id) {
			var location = self.l[id];

			if ((location.id == id) && (location.el.is('a')))  {
				// Geolocation
				if (location.lat && location.lng) {
					var pos = latlngToPos(location.lat, location.lng);
					location.x = pos.x;
					location.y = pos.y;
				}
				
				var top = location.y * 100,
					left = location.x * 100;
				location.el.css({'top': top + '%', 'left': left + '%'});
			}
		}

	};

	// jQuery plugin
	$.fn.mapplic = function(options) {

		return this.each(function() {
			var element = $(this);

			// plugin already initiated on element
			if (element.data('mapplic')) return;

			var instance = (new Mapplic(element)).init(options);

			// store plugin object in element's data
			element.data('mapplic', instance);
		});
	};

})(jQuery);

// call plugin on map instances
jQuery(document).ready(function($) {
	$('[id^=mapplic-id]').mapplic();
});