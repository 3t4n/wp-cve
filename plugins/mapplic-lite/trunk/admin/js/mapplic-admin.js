(function($) {

	var Mapplic = function() {
		var self = this;

		self.o = {
			id: 0,
			selector: '[id^=MLOC] > *, [id^=landmark] > *, svg > #items > *',
			mapfill: false,
			height: 420,
			animate: false
		};

		self.init = function(el, params) {
			// Extend options
			self.o = $.extend(self.o, params);

			self.x = 0;
			self.y = 0;
			self.scale = 1;

			self.el = el.addClass('mapplic-element mapplic-loading').height(self.o.height);

			// Process map data
			mapData = self.el.data('mapdata');
			if (mapData) {
				self.el.removeAttr('data-mapdata');
				processData(mapData);
				self.el.removeClass('mapplic-loading');
			}

			return self;
		}

		// Sidebar
		function Sidebar() {
			this.el = null;
			this.list = null;
			this.listContainer = null;

			this.init = function() {
				var s = this;

				this.el = $('<div></div>').addClass('mapplic-sidebar').appendTo(self.el);

				if (self.data.search) {
					var form = $('<form></form>').addClass('mapplic-search-form').submit(function() {
						return false;
					}).appendTo(this.el);
					self.clear = $('<button></button>').addClass('mapplic-search-clear').click(function() {
						input.val('');
						input.keyup();
					}).appendTo(form);
					var input = $('<input>').attr({'type': 'text', 'spellcheck': 'false', 'placeholder': mapplic_localization.search}).addClass('mapplic-search-input').keyup(function() {
						var keyword = $(this).val();
						s.search(keyword);
					}).prependTo(form);
				}

				this.listContainer = $('<div></div>').addClass('mapplic-list-container').appendTo(this.el);
				this.notfound = $('<p></p>').addClass('mapplic-not-found').text(mapplic_localization.not_found).appendTo(this.listContainer);

				if (!self.data.search) this.listContainer.css('padding-top', '0');
			}

			this.addLevel = function(level) {
				this.list = $('<ol></ol>').text(level.title).addClass('mapplic-list sortable-list').appendTo(this.listContainer);
			}

			this.addCategories = function(categories) {
				var list = this.list;

				$.each(categories, function(index, category) {
					var item = $('<li></li>').addClass('mapplic-list-category').attr('data-category', category.id);
					var ol = $('<ol></ol>').css('border-color', category.color).appendTo(item);
					if (category.show == 'false') ol.hide();
					var link = $('<a></a>').attr('href', '#').attr('title', category.title).css('background-color', category.color).text(category.title).click(function(e) {
						ol.slideToggle(200);
						return false;
					}).prependTo(item);
					if (category.icon) $('<img>').attr('src', category.icon).addClass('mapplic-list-thumbnail').prependTo(link);
					$('<span></span>').text('0').addClass('mapplic-list-count').prependTo(link);
					list.append(item);
				});
			}

			this.addLocation = function(data) {
				var item = $('<li></li>').addClass('mapplic-list-location list-item').addClass('mapplic-list-shown').attr('data-location', data.id);
				var link = $('<a></a>').addClass('list-item-handle').attr('href', '#' + data.id).appendTo(item).click(function(e) {
					e.preventDefault();
					showLocation(data.id, 800);
					self.pindrag.update(data);
					$('.selected-pin').removeClass('selected-pin');
					$('.mapplic-pin[data-location="' + data.id + '"]', self.map).addClass('selected-pin');
				});
				$('<h4></h4>').text(data.title).appendTo(link);
				
				var category = $('.mapplic-list-category[data-category="' + data.category + '"]');

				if (category.length) $('ol', category).append(item);
				else this.list.append(item);

				// Count
				$('.mapplic-list-count', category).text($('.mapplic-list-shown', category).length);
			}

			this.search = function(keyword) {
				if (keyword) self.clear.fadeIn(100);
				else self.clear.fadeOut(100);

				$('.mapplic-list li', self.el).each(function() {
					if ($(this).text().search(new RegExp(keyword, "i")) < 0) {
						$(this).removeClass('mapplic-list-shown');
						$(this).slideUp(200);
					} else {
						$(this).addClass('mapplic-list-shown');
						$(this).show();
					}
				});

				$('.mapplic-list > li', self.el).each(function() {
					var count = $('.mapplic-list-shown', this).length;
					$('.mapplic-list-count', this).text(count);
				});

				// Show not-found text
				if ($('.mapplic-list > li.mapplic-list-shown').length > 0) this.notfound.fadeOut(200);
				else this.notfound.fadeIn(200);
			}
		}

		// Clear Button
		function ClearButton() {
			this.el = null;
			
			this.init = function() {
				this.el = $('<a></a>').attr('href', '#').addClass('mapplic-clear-button').click(function(e) {
					e.preventDefault();
					$('#landmark-settings').hide();
					$('.selected-pin').removeClass('selected-pin');
					zoomTo(0.5, 0.5, 1);
				}).appendTo(self.container);
			}
		}

		// Zoom Buttons
		function ZoomButtons() {
			this.el = null;
		
			this.init = function() {
				this.el = $('<div></div>').addClass('mapplic-zoom-buttons').appendTo(self.container);

				this.zoomin = $('<a></ha>').attr('href', '#').addClass('mapplic-zoomin-button').appendTo(this.el);

				this.zoomin.on('click touchstart', function(e) {
					e.preventDefault();

					var scale = self.scale;
					self.scale = normalizeScale(scale + scale * 0.8);

					self.x = normalizeX(self.x - (self.container.width()/2 - self.x) * (self.scale/scale - 1));
					self.y = normalizeY(self.y - (self.container.height()/2 - self.y) * (self.scale/scale - 1));

					moveTo(self.x, self.y, self.scale, 400, 'easeInOutCubic');
				});

				this.zoomout = $('<a></ha>').attr('href', '#').addClass('mapplic-zoomout-button').appendTo(this.el);

				this.zoomout.on('click touchstart', function(e) {
					e.preventDefault();

					var scale = self.scale;
					self.scale = normalizeScale(scale - scale * 0.4);

					self.x = normalizeX(self.x - (self.container.width()/2 - self.x) * (self.scale/scale - 1));
					self.y = normalizeY(self.y - (self.container.height()/2 - self.y) * (self.scale/scale - 1));

					moveTo(self.x, self.y, self.scale, 400, 'easeInOutCubic');
				});
			}

			this.update = function(scale) {
				this.zoomin.removeClass('mapplic-disabled');
				this.zoomout.removeClass('mapplic-disabled');
				if (scale == self.fitscale) this.zoomout.addClass('mapplic-disabled');
				else if (scale == self.o.maxscale) this.zoomin.addClass('mapplic-disabled');
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

				// basecolor
				if (self.o.basecolor) css += this.rule('.mapplic-fullscreen, .mapplic-legend', 'background-color', self.o.basecolor);

				// bgcolor
				if (self.o.bgcolor) {
					css += this.rule(
						'.mapplic-button, .mapplic-tooltip-close .mapplic-icon, .mapplic-levels-select, .mapplic-levels button, .mapplic-filter, .mapplic-list-container:before, .mapplic-list-expandable, .mapplic-list-category > a, .mapplic-list-location > a, .mapplic-tooltip-wrap, .mapplic-lightbox, .mapplic-toggle:before',
						'background-color',
						self.o.bgcolor
					);
					css += this.rule('.mapplic-legend-key', 'border-color', self.o.bgcolor);
					css += this.rule('.mapplic-tooltip:after', 'border-color', self.o.bgcolor + ' transparent transparent transparent !important');
					css += this.rule('.mapplic-tooltip-bottom.mapplic-tooltip:after', 'border-color', 'transparent transparent ' + self.o.bgcolor + ' transparent !important');
				}

				// bgcolor2
				if (self.o.bgcolor2) {
					css += this.rule(
						'.mapplic-thumbnail-placeholder, .mapplic-list-location > a:hover, .mapplic-list-location > a:focus, .mapplic-list-location.mapplic-focus > a, .mapplic-list-location.mapplic-active > a, .mapplic-list-category > a:hover, .mapplic-zoom-buttons button.mapplic-disabled, .mapplic-levels button.mapplic-disabled',
						'background-color',
						self.o.bgcolor2
					);
					css += this.rule('a.mapplic-zoomin-button', 'border-color', self.o.bgcolor2);
				}

				// headingcolor
				if (self.o.headingcolor) {
					css += this.rule('.mapplic-search-input, .mapplic-list-category > a, .mapplic-tooltip-title, .mapplic-lightbox-title, .mapplic-list-location h4, .mapplic-element strong, .mapplic-levels-select, .mapplic-list-category h4', 'color', self.o.headingcolor);
					css += this.rule('.mapplic-icon', 'fill', self.o.headingcolor);
				}

				// textcolor
				if (self.o.textcolor) css += this.rule('.mapplic-element, .mapplic-element a, .mapplic-about, .mapplic-list-category > a .mapplic-list-count, .mapplic-search-input::placeholder, .mapplic-lightbox-description', 'color', self.o.textcolor);

				// accentcolor
				if (self.o.accentcolor) css += this.rule('.mapplic-popup-link, .mapplic-accentcolor', 'background-color', self.o.accentcolor);

				if (styles) {
					styles.forEach(function(s) {
						if (s.base) {
							css += `.${s.class}.mapplic-clickable:not(g), g.${s.class}.mapplic-clickable > * {\n`;
							$.each(s.base, function(prop, val) { css += `	${prop}: ${val};\n` });
							css += '}\n\n';
						}

						if (s.hover) {
							css += `.${s.class}.mapplic-highlight:not(g), g.${s.class}.mapplic-highlight > *, .${s.class}.mapplic-clickable:not(g):hover, g.${s.class}.mapplic-clickable:hover > * {\n`;
							$.each(s.hover, function(prop, val) { css += `	${prop}: ${val};\n` });
							css += '}\n\n';
						}

						if (s.active) {
							css += `.${s.class}.mapplic-active:not(g), g.${s.class}.mapplic-active > * {\n`;
							$.each(s.active, function(prop, val) { css += `	${prop}: ${val} !important;\n` });
							css += '}\n\n';
						}
					});
				}

				if (self.o.customcss) css += self.o.customcss;
				
				$('<style></style>').html(css).appendTo('body');
			}

			this.rule = function(selector, attribute, value) {
				var css = `${selector} {\n`;
				css += `	${attribute}: ${value};\n`;
				css += '}\n\n';

				return css;
			}
		}

		// Draggable pins
		function PinDrag() {
			this.init = function() {
				var s = this;

				// Pin drag
				$(document).on('mousedown', '.mapplic-element .mapplic-pin', function(event) {
					var pin = $(this);
					event.preventDefault();

					$('.selected-pin').removeClass('selected-pin');
					pin.addClass('selected-pin');

					$(document).on('mousemove', function(event) {
						var x = event.pageX - self.map.offset().left,
							y = event.pageY - self.map.offset().top;
						pin.css({
							left: x + 'px',
							top: y + 'px'
						});
					});

					$(document).on('mouseup', function() {
						$(document).off('mousemove');
						$(document).off('mouseup');

						var x = ((pin.offset().left - self.map.offset().left - parseInt(pin.css('margin-left')))/self.map.width()).toFixed(4),
							y = ((pin.offset().top - self.map.offset().top - parseInt(pin.css('margin-top')))/self.map.height()).toFixed(4);
						
						pin.css({
							left: (x*100) + '%',
							top: (y*100) + '%'
						});

						var value = pin.data('landmarkData');

						value.x = x;
						value.y = y;

						s.update(value);
					});
				});

				// Pin touch
				$(document).on('touchstart', '.mapplic-element .mapplic-pin', function(e) {
					var pin = $(this);
					e.preventDefault();

					$('.selected-pin').removeClass('selected-pin');
					pin.addClass('selected-pin');

					$(document).on('touchmove', function(event) {
						var orig = event.originalEvent,
							x = orig.changedTouches[0].pageX - self.map.offset().left,
							y = orig.changedTouches[0].pageY - self.map.offset().top;
						pin.css({
							left: x + 'px',
							top: y + 'px'
						});
					});

					$(document).on('touchend', function() {
						$(document).off('touchmove');
						$(document).off('touchend');

						var x = ((pin.offset().left - self.map.offset().left - parseInt(pin.css('margin-left')))/self.map.width()).toFixed(4),
							y = ((pin.offset().top - self.map.offset().top - parseInt(pin.css('margin-top')))/self.map.height()).toFixed(4);
						
						pin.css({
							left: (x*100) + '%',
							top: (y*100) + '%'
						});

						var value = pin.data('landmarkData');

						value.x = x;
						value.y = y;

						s.update(value);
					});
				});
			}

			this.update = function(location) {
				$('#landmark-settings .title-input').val(location.title);
				$('#landmark-settings .id-input').val(location.id);
				
				if ($('#wp-descriptioninput-wrap').hasClass('html-active')) $('#descriptioninput').val(location.description);
				else {
					if (location.description) tinymce.get('descriptioninput').setContent(location.description);
					else tinymce.get('descriptioninput').setContent('');
				}

				$('#landmark-settings .landmark-lat').val(location.lat);
				$('#landmark-settings .landmark-lng').val(location.lng);

				$('#pins-input .selected').removeClass('selected');
				if (location.pin) {
					$('#pins-input div[data-pin="' + location.pin + '"]').parent('li').addClass('selected');
					// Show label field only when it's available
					if (location.pin.indexOf('pin-label') > -1) $('#landmark-settings .label-input').show();
					else $('#landmark-settings .label-input').hide();
				}
				
				$('#landmark-settings .label-input').val(location.label);
				$('#landmark-settings .link-input').val(location.link);

				$('#landmark-settings .fill-input').val(location.fill);
				if (location.fill) $('#landmark-settings .fill-input').wpColorPicker('color', location.fill);
				else $('#landmark-settings .wp-color-result').css('background-color', '');

				if (location.style) $('#landmark-settings .style-select').val(location.style);
				else $('#landmark-settings .style-select').val('false');

				if (location.category) $('#landmark-settings .category-select').val(location.category);
				else $('#landmark-settings .category-select').val('false');

				$('#landmark-settings .thumbnail-input').val(location.thumbnail);
				$('#landmark-settings .image-input').val(location.image);

				if (location.action) $('#landmark-settings .action-select').val(location.action);
				else $('#landmark-settings .action-select').val('tooltip');

				$('#landmark-settings .zoom-input').val(location.zoom);
				$('#landmark-settings .reveal-input').val(location.reveal);
				if (location.hide) $('#landmark-settings .hide-input').prop('checked', true);
				else $('#landmark-settings .hide-input').prop('checked', false);
				
				$('#landmark-settings .about-input').val(location.about);

				// Custom fields
				$('#landmark-settings .mapplic-landmark-field').each(function() {
					var field = $(this).data('field');
					$(this).val(location[field]);
				});

				// Update UI
				$('#landmark-settings').show(); // show fields
				$('.save-landmark').val(mapplic_localization.save); // button text
				$('.duplicate-landmark').show(); // duplicate
			}

			this.newLocation = function(id) {
				var s = 'There\'s no location associated with "' + id + '" yet.\nWould you like to create a new one?',
					c = confirm(s);

				if (c) self.admin.newLocation(id);
			}
		}

		// Functions
		var processData = function(data) {
			self.data = data;
			mapData = data;
			self.o = $.extend(self.o, data);
			self.data.maxscale = parseFloat(self.data.maxscale);
			var nrlevels = 0;
			shownLevel = null;

			// Show the Geolocation fields
			if (data.bottomLat && data.leftLng && data.topLat && data.rightLng) $('.landmark-geolocation').show();

			self.container = $('<div></div>').addClass('mapplic-container').appendTo(self.el);
			self.map = $('<div></div>').addClass('mapplic-map').appendTo(self.container);
			self.map.addClass('mapplic-zoomable');

			self.levelselect = $('<select></select>').addClass('mapplic-levels-select');

			if (!self.data.sidebar) self.container.css('width', '100%');

			self.contentWidth = data.mapwidth;
			self.contentHeight = data.mapheight;

			self.hw_ratio = data.mapheight / data.mapwidth;

			self.map.css({
				'width': data.mapwidth,
				'height': data.mapheight
			});

			// styles
			self.styles = new Styles().init(self.o.styles);

			// groups
			var groups = data.groups || data.categories;
			self.g = {};
			$.each(groups, function(index, group) {
				self.g[group.id] = group;
			});

			// Create sidebar
			if (self.data.sidebar) {
				self.sidebar = new Sidebar();
				self.sidebar.init();
			}

			self.locationid = $('<div>id</div>').addClass('mapplic-locationid').appendTo(self.container);

			// Iterate through levels
			if (data.levels) {
				$.each(data.levels, function(index, value) {
					var source = value.map;
					var extension = source.substr((source.lastIndexOf('.') + 1)).toLowerCase();

					// Create new map layer
					var layer = $('<div></div>').addClass('mapplic-layer').attr('data-floor', value.id).hide().appendTo(self.map);
					switch (extension) {

						// Image formats
						case 'jpg': case 'jpeg': case 'png': case 'gif':
							$('<img>').attr('src', source).addClass('mapplic-map-image').appendTo(layer);
							break;

						// Vector format
						case 'svg':							
							var mapimage = $('<div></div>').addClass('mapplic-map-image').appendTo(layer);

							$('<div></div>').load(source, function() {

								// sanitize svg - XSS protection
								$('script', this).remove();
								mapimage.html($(this).html());

								// setting up the location on the map
								$(self.o.selector, mapimage).each(function() {
									var location = getLocationData($(this).attr('id')); 
									if (location) {
										//$(this).attr('class', 'mapplic-clickable');

										var elem = $('[id^=MLOC] > *[id="' + location.id + '"], [id^=landmark] > *[id="' + location.id + '"]', self.map);
										if (elem.length > 0) {
											elem.addClass('mapplic-clickable');
											if (location.style) elem.addClass(location.style);
											else if (self.g[location.category] && self.g[location.category].style) elem.addClass(self.g[location.category].style);
											else if (location.category && self.g[location.category[0]] && self.g[location.category[0]].style) elem.addClass(self.g[location.category[0]].style);
											else if (self.o.defaultstyle) elem.addClass(self.o.defaultstyle);
											location.el = elem;
										}

										var fill = null;
										if (location.fill) fill = location.fill;
										else if (self.o.fillcolor) fill = self.o.fillcolor

										if (fill) {
											$(this).css('fill', fill);
											$('> *', this).css('fill', fill);
										}
									}
								});

								$(self.o.selector).css('cursor', 'pointer');
								$(self.o.selector, mapimage).on('click touchend', function() {
									if (!self.dragging) {
										var id = $(this).attr('id'),
											location = getLocationData(id);
										
										self.locationid.text(id);
										if (location) {
											if ($('#landmark-settings .save-landmark:visible').val() == mapplic_localization.add) $('#landmark-settings .id-input').val(id);
											self.pindrag.update(location);
											$('.selected-pin').removeClass('selected-pin');
											$('.mapplic-pin[data-location="' + id + '"]', self.map).addClass('selected-pin');
										}
										else self.pindrag.newLocation(id);
									}
								});
							});
							break;

						// Other 
						default:
							alert('File type ' + extension + ' is not supported!');
					}

					// Create sidebar group
					if (self.sidebar) self.sidebar.addLevel(value);

					// Build layer control
					self.levelselect.prepend($('<option></option>').attr('value', value.id).text(value.title));

					if (!shownLevel || value.show) {
						shownLevel = value.id;
					}
					
					// Iterate through locations
					$.each(value.locations, function(index, location) {
						// Geolocation
						if (location.lat) {
							var pos = latlngToPos(location.lat, location.lng);
							location.x = pos.x;
							location.y = pos.y;
						}

						var top = location.y * 100,
							left = location.x * 100;

						var pin = $('<a></a>').attr('href', '#').addClass('mapplic-pin').css({'top': top + '%', 'left': left + '%'}).appendTo(layer);
						pin.on('click touchend', function(e) {
							e.preventDefault();
						});
						if (location.label) pin.html(location.label);
						if (location.fill) pin.css({'background-color': location.fill, 'border-color': location.fill});
						if (location.pin && (location.pin.indexOf('pin-image') > -1 || location.pin.indexOf('pin-icon') > -1)) pin.css('background-image', 'url(' + location.label + ')');
						pin.attr('data-location', location.id);
						pin.addClass(location.pin);
						pin.data('landmarkData', location);
						location.el = pin;

						if (self.sidebar) self.sidebar.addLocation(location);
					});

					nrlevels++;
				});
			}

			
			// Location sorting
			var oi;
			$('.sortable-list').sortable({
				placeholder: 'list-item-placeholder',
				forcePlaceholderSize: true,
				handle: '.list-item-handle'
			});

			$('.sortable-list').on('sortstart', function(e, ui ) {
				oi = ui.item.index();
			});

			$('.sortable-list').on('sortupdate', function(e, ui) {
				if (typeof mapData.levels !== 'undefined') {
					$.each(mapData.levels, function(index, level) {
						$.each(level.locations, function(index, location) {
							if (location.id == ui.item.data('location')) {
								var l = level.locations;
								l.splice(ui.item.index(), 0, l.splice(oi, 1)[0]);
								return false;
							}
						});
					});
				}
			});

			// Alphabetic sort
			/*
			if (self.o.alphabetic) {
				$('.mapplic-list-container ol', self.el).each(function() {
					var mylist = $(this);
					var listitems = mylist.children('.mapplic-list-location').get();
					listitems.sort(function(a, b) {
						var compA = $(a).text().toUpperCase();
						var compB = $(b).text().toUpperCase();
						return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
					})
					$.each(listitems, function(idx, itm) { mylist.append(itm); });
				});
			}*/

			// Pin drag
			self.pindrag = new PinDrag();
			self.pindrag.init();

			// Clear button
			self.clearbutton = new ClearButton().init();

			// Zoom buttons
			if (self.o.zoombuttons) {
				self.zoombuttons = new ZoomButtons();
				self.zoombuttons.init();
			}

			// Levels
			if (nrlevels > 1) {
				self.levels = $('<div></div>').addClass('mapplic-levels');
				var up = $('<a href="#"></a>').addClass('mapplic-levels-up').appendTo(self.levels);
				self.levelselect.appendTo(self.levels);
				var down = $('<a href="#"></a>').addClass('mapplic-levels-down').appendTo(self.levels);
				self.container.append(self.levels);
			
				self.levelselect.change(function() {
					var value = $(this).val();
					switchLevel(value);
				});
			
				up.click(function(e) {
					e.preventDefault();
					if (!$(this).hasClass('mapplic-disabled')) switchLevel('+');
				});

				down.click(function(e) {
					e.preventDefault();
					if (!$(this).hasClass('mapplic-disabled')) switchLevel('-');
				});
			}
			switchLevel(shownLevel);

			// Browser resize
			$(window).resize(function() {
				// Mobile
				if ($(window).width() < 668) {
					self.container.height($(window).height() - 66);
				}
				else self.container.height('100%');

				var wr = self.container.width() / self.contentWidth,
					hr = self.container.height() / self.contentHeight;

				if (self.o.mapfill) {
					if (wr > hr) self.fitscale = wr;
					else self.fitscale = hr;
				}
				else {
					if (wr < hr) self.fitscale = wr;
					else self.fitscale = hr;
				}

				self.scale = normalizeScale(self.scale);
				self.x = normalizeX(self.x);
				self.y = normalizeY(self.y);

				moveTo(self.x, self.y, self.scale, 100);
			}).resize();

			// Init
			zoomTo(0.5, 0.5, 1, 0);
			addControls();
		}

		var addControls = function() {
			var map = self.map,
				mapbody = $('.mapplic-map-image', self.map);

			// Drag & drop
			mapbody.on('mousedown', function(event) {
				event.preventDefault();
				self.dragging = false;
				map.stop();

				map.data('mouseX', event.pageX);
				map.data('mouseY', event.pageY);
				map.data('lastX', self.x);
				map.data('lastY', self.y);

				map.addClass('mapplic-dragging');

				self.map.on('mousemove', function(event) {
					self.dragging = true;
					var x = event.pageX - map.data('mouseX') + self.x;
						y = event.pageY - map.data('mouseY') + self.y;

					x = normalizeX(x);
					y = normalizeY(y);

					moveTo(x, y);
					map.data('lastX', x);
					map.data('lastY', y);
				});
			
				$(document).on('mouseup', function(event) {
					self.x = map.data('lastX');
					self.y = map.data('lastY');

					self.map.off('mousemove');
					$(document).off('mouseup');

					map.removeClass('mapplic-dragging');
				});
			});

			// Double click
			$(document).on('dblclick', '.mapplic-map-image', function(event) {
				event.preventDefault();

				var scale = self.scale;
				self.scale = normalizeScale(scale * 2);

				self.x = normalizeX(self.x - (event.pageX - self.container.offset().left - self.x) * (self.scale/scale - 1));
				self.y = normalizeY(self.y - (event.pageY - self.container.offset().top - self.y) * (self.scale/scale - 1));

				moveTo(self.x, self.y, self.scale, 400, 'easeInOutCubic');
			});

			// Mousewheel
			$('.mapplic-layer', self.el).bind('mousewheel DOMMouseScroll', function(event, delta) {
				event.preventDefault();

				var scale = self.scale;
				self.scale = normalizeScale(scale + scale * delta/5);

				self.x = normalizeX(self.x - (event.pageX - self.container.offset().left - self.x) * (self.scale/scale - 1));
				self.y = normalizeY(self.y - (event.pageY - self.container.offset().top - self.y) * (self.scale/scale - 1));

				moveTo(self.x, self.y, self.scale, 200, 'easeOutCubic');
			});

			// Touch support
			if (!('ontouchstart' in window || 'onmsgesturechange' in window)) return true;

			mapbody.on('touchstart', function(e) {
				var orig = e.originalEvent,
					pos = map.position();

				map.data('touchY', orig.changedTouches[0].pageY - pos.top);
				map.data('touchX', orig.changedTouches[0].pageX - pos.left);

				mapbody.on('touchmove', function(e) {
					e.preventDefault();
					var orig = e.originalEvent;
					var touches = orig.touches.length;

					if (touches == 1) {
						self.x = normalizeX(orig.changedTouches[0].pageX - map.data('touchX'));
						self.y = normalizeY(orig.changedTouches[0].pageY - map.data('touchY'));

						moveTo(self.x, self.y, self.scale, 100);
					}
					else {
						mapbody.off('touchmove');
					}
				});

				mapbody.on('touchend', function(e) {
					mapbody.off('touchmove touchend');
				});
			});
			
			// Pinch zoom
			var hammer = new Hammer(self.map[0], {
				transform_always_block: true,
				drag_block_horizontal: true,
				drag_block_vertical: true
			});

			/* hammer fix */
			self.map.on('touchstart', function(e) {
				if (e.originalEvent.touches.length > 1) hammer.get('pinch').set({ enable: true });
			});

			self.map.on('touchend', function(e) {
				hammer.get('pinch').set({ enable: false });
			});
			/* hammer fix ends */

			var scale=1, last_scale;
			hammer.on('pinchstart', function(e) {
				self.dragging = false;

				scale = self.scale / self.fitscale;
				last_scale = scale;
			});

			hammer.on('pinch', function(e) {
				self.dragging = true;

				if (e.scale != 1) scale = Math.max(1, Math.min(last_scale * e.scale, 100));
				
				var oldscale = self.scale;
				self.scale = normalizeScale(scale * self.fitscale);

				self.x = normalizeX(self.x - (e.center.x - self.container.offset().left - self.x) * (self.scale/oldscale - 1));
				self.y = normalizeY(self.y - (e.center.y - self.y) * (self.scale/oldscale - 1)); // - self.container.offset().top

				moveTo(self.x, self.y, self.scale, 100);
			});
		}

		var switchLevel = function(target) {
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

			shownLevel = target;
			var layer = $('.mapplic-layer[data-floor="' + target + '"]', self.map);

			// Target layer is active
			if (layer.is(':visible')) return;

			// Show target layer
			$('.mapplic-layer:visible', self.map).hide();
			layer.show();

			// Update control
			var index = self.levelselect.get(0).selectedIndex,
				up = $('.mapplic-levels-up', self.levels),
				down = $('.mapplic-levels-down', self.levels);

			up.removeClass('mapplic-disabled');
			down.removeClass('mapplic-disabled');
			if (index == 0) {
				up.addClass('mapplic-disabled');
			}
			else if (index == self.levelselect.get(0).length - 1) {
				down.addClass('mapplic-disabled');
			}
		}

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

		var getLocationData = function(id) {
			var data = null;
			$.each(self.data.levels, function(index, layer) {
				$.each(layer.locations, function(index, value) {
					if (value.id == id) {
						data = value;
					}
				});
			});
			return data;
		}

		var showLocation = function(id, duration) {
			$.each(self.data.levels, function(index, layer) {
				$.each(layer.locations, function(index, value) {
					if (value.id == id) {
						var zoom = (!value.zoom) ? 4 : value.zoom;

						switchLevel(layer.id);

						zoomTo(value.x, parseFloat(value.y), zoom, duration, 'easeInOutCubic');
					}
				});
			});
		};

		var normalizeX = function(x) {
			var minX = self.container.width() - self.contentWidth * self.scale;

			if (minX < 0) {
				if (x > 0) x = 0;
				else if (x < minX) x = minX;
			}
			else x = minX/2;

			return x;
		}

		var normalizeY = function(y) {
			var minY = self.container.height() - self.contentHeight * self.scale;

			if (minY < 0) {
				if (y >= 0) y = 0;
				else if (y < minY) y = minY;
			}
			else y = minY/2;

			return y;
		}

		var normalizeScale = function(scale) {
			if (scale < self.fitscale) scale = self.fitscale;
			else if (scale >= self.data.maxscale) scale = self.data.maxscale;

			return scale;
		}

		var zoomTo = function(x, y, s, duration, easing) {
			duration = typeof duration !== 'undefined' ? duration : 400;

			self.scale = normalizeScale(self.fitscale * s);
			var scale = self.contentWidth * self.scale;

			self.x = normalizeX(self.container.width() * 0.5 - self.scale * self.contentWidth * x);
			self.y = normalizeY(self.container.height() * 0.5 - self.scale * self.contentHeight * y);

			moveTo(self.x, self.y, self.scale, duration, easing);
		}

		var moveTo = function(x, y, scale, d, easing) {
			if (scale !== undefined) {
				self.map.stop().animate({
					'left': x,
					'top': y,
					'width': self.contentWidth * scale,
					'height': self.contentHeight * scale
				}, d, easing);
			}
			else {
				self.map.css({
					'left': x,
					'top': y
				});
			}
		}
	};

	// Easing functions used by default
	// For the full list of easing functions use jQuery Easing Plugin
	$.extend($.easing,
	{
		def: 'easeOutQuad',
		swing: function (x, t, b, c, d) {
			//alert(jQuery.easing.default);
			return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
		},
		easeOutQuad: function (x, t, b, c, d) {
			return -c *(t/=d)*(t-2) + b;
		},
		easeOutCubic: function (x, t, b, c, d) {
			return c*((t=t/d-1)*t*t + 1) + b;
		},
		easeInOutCubic: function (x, t, b, c, d) {
			if ((t/=d/2) < 1) return c/2*t*t*t + b;
			return c/2*((t-=2)*t*t + 2) + b;
		}
	});


	//  Create a jQuery plugin
	$.fn.mapplic = function(params) {
		var len = this.length;

		return this.each(function(index) {
			var me = $(this),
				key = 'mapplic' + (len > 1 ? '-' + ++index : ''),
				instance = (new Mapplic).init(me, params);

			me.data('mapplic', instance);
		});
	};
})(jQuery);