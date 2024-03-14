; (function ($, window, document, undefined) {
	'use strict';

	//
	// Constants
	//
	var SPF_WPSP = SPF_WPSP || {};

	SPF_WPSP.funcs = {};

	SPF_WPSP.vars = {
		onloaded: false,
		$body: $('body'),
		$window: $(window),
		$document: $(document),
		$form_warning: null,
		is_confirm: false,
		form_modified: false,
		code_themes: [],
		is_rtl: $('body').hasClass('rtl'),
	};

	//
	// Helper Functions
	//
	SPF_WPSP.helper = {

		//
		// Generate UID
		//
		uid: function (prefix) {
			return (prefix || '') + Math.random().toString(36).substr(2, 9);
		},

		// Quote regular expression characters
		//
		preg_quote: function (str) {
			return (str + '').replace(/(\[|\])/g, "\\$1");
		},

		//
		// Reneme input names
		//
		name_nested_replace: function ($selector, field_id) {

			var checks = [];
			var regex = new RegExp(SPF_WPSP.helper.preg_quote(field_id + '[\\d+]'), 'g');

			$selector.find(':radio').each(function () {
				if (this.checked || this.orginal_checked) {
					this.orginal_checked = true;
				}
			});

			$selector.each(function (index) {
				$(this).find(':input').each(function () {
					this.name = this.name.replace(regex, field_id + '[' + index + ']');
					if (this.orginal_checked) {
						this.checked = true;
					}
				});
			});

		},

		//
		// Debounce
		//
		debounce: function (callback, threshold, immediate) {
			var timeout;
			return function () {
				var context = this, args = arguments;
				var later = function () {
					timeout = null;
					if (!immediate) {
						callback.apply(context, args);
					}
				};
				var callNow = (immediate && !timeout);
				clearTimeout(timeout);
				timeout = setTimeout(later, threshold);
				if (callNow) {
					callback.apply(context, args);
				}
			};
		},
		//
		// Get a cookie
		//
		get_cookie: function (name) {

			var e, b, cookie = document.cookie, p = name + '=';

			if (!cookie) {
				return;
			}

			b = cookie.indexOf('; ' + p);

			if (b === -1) {
				b = cookie.indexOf(p);

				if (b !== 0) {
					return null;
				}
			} else {
				b += 2;
			}

			e = cookie.indexOf(';', b);

			if (e === -1) {
				e = cookie.length;
			}

			return decodeURIComponent(cookie.substring(b + p.length, e));

		},

		//
		// Set a cookie
		//
		set_cookie: function (name, value, expires, path, domain, secure) {

			var d = new Date();

			if (typeof (expires) === 'object' && expires.toGMTString) {
				expires = expires.toGMTString();
			} else if (parseInt(expires, 10)) {
				d.setTime(d.getTime() + (parseInt(expires, 10) * 1000));
				expires = d.toGMTString();
			} else {
				expires = '';
			}

			document.cookie = name + '=' + encodeURIComponent(value) +
				(expires ? '; expires=' + expires : '') +
				(path ? '; path=' + path : '') +
				(domain ? '; domain=' + domain : '') +
				(secure ? '; secure' : '');

		},
		//
		// Remove a cookie
		//
		remove_cookie: function (name, path, domain, secure) {
			SPF_WPSP.helper.set_cookie(name, '', -1000, path, domain, secure);
		},
	};

	//
	// Custom clone for textarea and select clone() bug
	//
	$.fn.spwps_clone = function () {

		var base = $.fn.clone.apply(this, arguments),
			clone = this.find('select').add(this.filter('select')),
			cloned = base.find('select').add(base.filter('select'));

		for (var i = 0; i < clone.length; ++i) {
			for (var j = 0; j < clone[i].options.length; ++j) {

				if (clone[i].options[j].selected === true) {
					cloned[i].options[j].selected = true;
				}

			}
		}

		this.find(':radio').each(function () {
			this.orginal_checked = this.checked;
		});

		return base;

	};

	//
	// Expand All Options
	//
	$.fn.spwps_expand_all = function () {
		return this.each(function () {
			$(this).on('click', function (e) {

				e.preventDefault();
				$('.spwps-wrapper').toggleClass('spwps-show-all');
				$('.spwps-section').spwps_reload_script();
				$(this).find('.fa').toggleClass('fa-indent').toggleClass('fa-outdent');

			});
		});
	};

	//
	// Options Navigation
	//
	$.fn.spwps_nav_options = function () {
		return this.each(function () {

			var $nav = $(this),
				$window = $(window),
				$wpwrap = $('#wpwrap'),
				$links = $nav.find('a'),
				$last;

			$window.on('hashchange spwps.hashchange', function () {

				var hash = window.location.hash.replace('#tab=', '');
				var slug = hash ? hash : $links.first().attr('href').replace('#tab=', '');
				var $link = $('[data-tab-id="' + slug + '"]');

				if ($link.length) {

					$link.closest('.spwps-tab-item').addClass('spwps-tab-expanded').siblings().removeClass('spwps-tab-expanded');

					if ($link.next().is('ul')) {

						$link = $link.next().find('li').first().find('a');
						slug = $link.data('tab-id');

					}

					$links.removeClass('spwps-active');
					$link.addClass('spwps-active');

					if ($last) {
						$last.addClass('hidden');
					}

					var $section = $('[data-section-id="' + slug + '"]');

					$section.removeClass('hidden');
					$section.spwps_reload_script();

					$('.spwps-section-id').val($section.index() + 1);

					$last = $section;

					if ($wpwrap.hasClass('wp-responsive-open')) {
						$('html, body').animate({ scrollTop: ($section.offset().top - 50) }, 200);
						$wpwrap.removeClass('wp-responsive-open');
					}

				}

			}).trigger('spwps.hashchange');

		});
	};

	//
	// Metabox Tabs
	//
	$.fn.spwps_nav_metabox = function () {
		return this.each(function () {

			var $nav = $(this),
				$links = $nav.find('a'),
				$sections = $nav.parent().find('.spwps-section'),
				unique_id = $nav.data('unique'),
				post_id = $('#post_ID').val() || 'global',
				$last;

			$links.each(function (index) {

				$(this).on('click', function (e) {

					e.preventDefault();

					var $link = $(this);
					var section_id = $link.data('section');
					$links.removeClass('spwps-active');
					$link.addClass('spwps-active');
					if ($last !== undefined) {
						$last.addClass('hidden');
					}

					var $section = $sections.eq(index);

					$section.removeClass('hidden');
					$section.spwps_reload_script();
					SPF_WPSP.helper.set_cookie('spwps-last-metabox-tab-' + post_id + '-' + unique_id, section_id);

					$last = $section;

				});

			});

			var get_cookie = SPF_WPSP.helper.get_cookie('spwps-last-metabox-tab-' + post_id + '-' + unique_id);
			if (get_cookie) {
				$nav.find('a[data-section="' + get_cookie + '"]').trigger('click');
			} else {
				$links.first('a').trigger('click');
			}

		});
	};
	//
	// Search
	//
	$.fn.spwps_search = function () {
		return this.each(function () {

			var $this = $(this),
				$input = $this.find('input');

			$input.on('change keyup', function () {

				var value = $(this).val(),
					$wrapper = $('.spwps-wrapper'),
					$section = $wrapper.find('.spwps-section'),
					$fields = $section.find('> .spwps-field:not(.spwps-depend-on)'),
					$titles = $fields.find('> .spwps-title, .spwps-search-tags');

				if (value.length > 3) {

					$fields.addClass('spwps-metabox-hide');
					$wrapper.addClass('spwps-search-all');

					$titles.each(function () {

						var $title = $(this);

						if ($title.text().match(new RegExp('.*?' + value + '.*?', 'i'))) {

							var $field = $title.closest('.spwps-field');

							$field.removeClass('spwps-metabox-hide');
							$field.parent().spwps_reload_script();

						}

					});

				} else {

					$fields.removeClass('spwps-metabox-hide');
					$wrapper.removeClass('spwps-search-all');

				}

			});

		});
	};

	//
	// Sticky Header
	//
	$.fn.spwps_sticky = function () {
		return this.each(function () {

			var $this = $(this),
				$window = $(window),
				$inner = $this.find('.spwps-header-inner'),
				padding = parseInt($inner.css('padding-left')) + parseInt($inner.css('padding-right')),
				offset = 32,
				scrollTop = 0,
				lastTop = 0,
				ticking = false,
				stickyUpdate = function () {

					var offsetTop = $this.offset().top,
						stickyTop = Math.max(offset, offsetTop - scrollTop),
						winWidth = $window.innerWidth();

					if (stickyTop <= offset && winWidth > 782) {
						$inner.css({ width: $this.outerWidth() - padding });
						$this.css({ height: $this.outerHeight() }).addClass('spwps-sticky');
					} else {
						$inner.removeAttr('style');
						$this.removeAttr('style').removeClass('spwps-sticky');
					}

				},
				requestTick = function () {

					if (!ticking) {
						requestAnimationFrame(function () {
							stickyUpdate();
							ticking = false;
						});
					}

					ticking = true;

				},
				onSticky = function () {

					scrollTop = $window.scrollTop();
					requestTick();

				};

			$window.on('scroll resize', onSticky);

			onSticky();

		});
	};

	//
	// Dependency System
	//
	$.fn.spwps_dependency = function () {
		return this.each(function () {

			var $this = $(this),
				$fields = $this.children('[data-controller]');

			if ($fields.length) {

				var normal_ruleset = $.spwps_deps.createRuleset(),
					global_ruleset = $.spwps_deps.createRuleset(),
					normal_depends = [],
					global_depends = [];

				$fields.each(function () {

					var $field = $(this),
						controllers = $field.data('controller').split('|'),
						conditions = $field.data('condition').split('|'),
						values = $field.data('value').toString().split('|'),
						is_global = $field.data('depend-global') ? true : false,
						ruleset = (is_global) ? global_ruleset : normal_ruleset;

					$.each(controllers, function (index, depend_id) {

						var value = values[index] || '',
							condition = conditions[index] || conditions[0];

						ruleset = ruleset.createRule('[data-depend-id="' + depend_id + '"]', condition, value);

						ruleset.include($field);

						if (is_global) {
							global_depends.push(depend_id);
						} else {
							normal_depends.push(depend_id);
						}

					});

				});

				if (normal_depends.length) {
					$.spwps_deps.enable($this, normal_ruleset, normal_depends);
				}

				if (global_depends.length) {
					$.spwps_deps.enable(SPF_WPSP.vars.$body, global_ruleset, global_depends);
				}

			}

		});
	};

	//
	// Field: code_editor
	//
	$.fn.spwps_field_code_editor = function () {
		return this.each(function () {

			if (typeof CodeMirror !== 'function') { return; }

			var $this = $(this),
				$textarea = $this.find('textarea'),
				$inited = $this.find('.CodeMirror'),
				data_editor = $textarea.data('editor');

			if ($inited.length) {
				$inited.remove();
			}

			var interval = setInterval(function () {
				if ($this.is(':visible')) {

					var code_editor = CodeMirror.fromTextArea($textarea[0], data_editor);

					// load code-mirror theme css.
					if (data_editor.theme !== 'default' && SPF_WPSP.vars.code_themes.indexOf(data_editor.theme) === -1) {

						var $cssLink = $('<link>');

						$('#spwps-codemirror-css').after($cssLink);

						$cssLink.attr({
							rel: 'stylesheet',
							id: 'spwps-codemirror-' + data_editor.theme + '-css',
							href: data_editor.cdnURL + '/theme/' + data_editor.theme + '.min.css',
							type: 'text/css',
							media: 'all'
						});

						SPF_WPSP.vars.code_themes.push(data_editor.theme);

					}

					CodeMirror.modeURL = data_editor.cdnURL + '/mode/%N/%N.min.js';
					CodeMirror.autoLoadMode(code_editor, data_editor.mode);

					code_editor.on('change', function (editor, event) {
						$textarea.val(code_editor.getValue()).trigger('change');
					});

					clearInterval(interval);

				}
			});

		});
	};

	//
	// Field: fieldset
	//
	$.fn.spwps_field_fieldset = function () {
		return this.each(function () {
			$(this).find('.spwps-fieldset-content').spwps_reload_script();
		});
	};

	//
	// Field: slider
	//
	$.fn.spwps_field_slider = function () {
		return this.each(function () {

			var $this = $(this),
				$input = $this.find('input'),
				$slider = $this.find('.spwps-slider-ui'),
				data = $input.data(),
				value = $input.val() || 0;

			if ($slider.hasClass('ui-slider')) {
				$slider.empty();
			}

			$slider.slider({
				range: 'min',
				value: value,
				min: data.min || 0,
				max: data.max || 100,
				step: data.step || 1,
				slide: function (e, o) {
					$input.val(o.value).trigger('change');
				}
			});

			$input.on('keyup', function () {
				$slider.slider('value', $input.val());
			});

		});
	};

	//
	// Field: spinner
	//
	$.fn.spwps_field_spinner = function () {
		return this.each(function () {

			var $this = $(this),
				$input = $this.find('input'),
				$inited = $this.find('.ui-button'),
				data = $input.data();

			if ($inited.length) {
				$inited.remove();
			}

			$input.spinner({
				min: data.min || 0,
				max: data.max || 100,
				step: data.step || 1,
				create: function (event, ui) {
					if (data.unit) {
						$input.after('<span class="ui-button spwps--unit">' + data.unit + '</span>');
					}
				},
				spin: function (event, ui) {
					$input.val(ui.value).trigger('change');
				}
			});

		});
	};

	//
	// Field: switcher
	//
	$.fn.spwps_field_switcher = function () {
		return this.each(function () {

			var $switcher = $(this).find('.spwps--switcher');

			$switcher.on('click', function () {

				var value = 0;
				var $input = $switcher.find('input');

				if ($switcher.hasClass('spwps--active')) {
					$switcher.removeClass('spwps--active');
				} else {
					value = 1;
					$switcher.addClass('spwps--active');
				}

				$input.val(value).trigger('change');

			});

		});
	};

	//
	// Field: tabbed
	//
	$.fn.spwps_field_tabbed = function () {
		return this.each(function () {
			var $this = $(this),
				$links = $this.find('.spwps-tabbed-nav a'),
				$sections = $this.find('.spwps-tabbed-section');

			$links.on('click', function (e) {
				e.preventDefault();

				var $link = $(this),
					index = $link.index(),
					$section = $sections.eq(index);

				// Store the active tab index in a cookie
				SPF_WPSP.helper.set_cookie('activeTabIndex', index);

				$link.addClass('spwps-tabbed-active').siblings().removeClass('spwps-tabbed-active');
				$section.spwps_reload_script();
				$section.removeClass('hidden').siblings().addClass('hidden');
			});
			// Check if there's a stored active tab index in the cookie
			var activeTabIndex = SPF_WPSP.helper.get_cookie('activeTabIndex');
			// Check if the cookie exists
			if (activeTabIndex !== null) {
				$links.eq(activeTabIndex).trigger('click');
			} else {
				$links.first().trigger('click');
			}

		});
	};
	//
	// Field: typography
	//
	$.fn.spwps_field_typography = function () {
		return this.each(function () {

			var base = this;
			var $this = $(this);
			var loaded_fonts = [];
			var webfonts = spwps_typography_json.webfonts;
			var googlestyles = spwps_typography_json.googlestyles;
			var defaultstyles = spwps_typography_json.defaultstyles;

			//
			//
			// Sanitize google font subset
			base.sanitize_subset = function (subset) {
				subset = subset.replace('-ext', ' Extended');
				subset = subset.charAt(0).toUpperCase() + subset.slice(1);
				return subset;
			};

			//
			//
			// Sanitize google font styles (weight and style)
			base.sanitize_style = function (style) {
				return googlestyles[style] ? googlestyles[style] : style;
			};

			//
			//
			// Load google font
			base.load_google_font = function (font_family, weight, style) {

				if (font_family && typeof WebFont === 'object') {

					weight = weight ? weight.replace('normal', '') : '';
					style = style ? style.replace('normal', '') : '';

					if (weight || style) {
						font_family = font_family + ':' + weight + style;
					}

					if (loaded_fonts.indexOf(font_family) === -1) {
						WebFont.load({ google: { families: [font_family] } });
					}

					loaded_fonts.push(font_family);

				}

			};

			//
			//
			// Append select options
			base.append_select_options = function ($select, options, condition, type, is_multi) {

				$select.find('option').not(':first').remove();

				var opts = '';

				$.each(options, function (key, value) {

					var selected;
					var name = value;

					// is_multi
					if (is_multi) {
						selected = (condition && condition.indexOf(value) !== -1) ? ' selected' : '';
					} else {
						selected = (condition && condition === value) ? ' selected' : '';
					}

					if (type === 'subset') {
						name = base.sanitize_subset(value);
					} else if (type === 'style') {
						name = base.sanitize_style(value);
					}

					opts += '<option value="' + value + '"' + selected + '>' + name + '</option>';

				});

				$select.append(opts).trigger('spwps.change').trigger('chosen:updated');

			};

			base.init = function () {

				//
				//
				// Constants
				var selected_styles = [];
				var $typography = $this.find('.spwps--typography');
				var $type = $this.find('.spwps--type');
				var $styles = $this.find('.spwps--block-font-style');
				var unit = $typography.data('unit');
				var line_height_unit = $typography.data('line-height-unit');
				var exclude_fonts = $typography.data('exclude') ? $typography.data('exclude').split(',') : [];

				//
				//
				// Chosen init
				if ($this.find('.spwps--chosen').length) {

					var $chosen_selects = $this.find('select');

					$chosen_selects.each(function () {

						var $chosen_select = $(this),
							$chosen_inited = $chosen_select.parent().find('.chosen-container');

						if ($chosen_inited.length) {
							$chosen_inited.remove();
						}

						$chosen_select.chosen({
							allow_single_deselect: true,
							disable_search_threshold: 15,
							width: '100%'
						});

					});

				}

				//
				//
				// Font family select
				var $font_family_select = $this.find('.spwps--font-family');
				var first_font_family = $font_family_select.val();

				// Clear default font family select options
				$font_family_select.find('option').not(':first-child').remove();

				var opts = '';

				$.each(webfonts, function (type, group) {

					// Check for exclude fonts
					if (exclude_fonts && exclude_fonts.indexOf(type) !== -1) { return; }

					opts += '<optgroup label="' + group.label + '">';

					$.each(group.fonts, function (key, value) {

						// use key if value is object
						value = (typeof value === 'object') ? key : value;
						var selected = (value === first_font_family) ? ' selected' : '';
						opts += '<option value="' + value + '" data-type="' + type + '"' + selected + '>' + value + '</option>';

					});

					opts += '</optgroup>';

				});

				// Append google font select options
				$font_family_select.append(opts).trigger('chosen:updated');

				//
				//
				// Font style select
				var $font_style_block = $this.find('.spwps--block-font-style');

				if ($font_style_block.length) {

					var $font_style_select = $this.find('.spwps--font-style-select');
					var first_style_value = $font_style_select.val() ? $font_style_select.val().replace(/normal/g, '') : '';

					//
					// Font Style on on change listener
					$font_style_select.on('change spwps.change', function (event) {

						var style_value = $font_style_select.val();

						// set a default value
						if (!style_value && selected_styles && selected_styles.indexOf('normal') === -1) {
							style_value = selected_styles[0];
						}

						// set font weight, for eg. replacing 800italic to 800
						var font_normal = (style_value && style_value !== 'italic' && style_value === 'normal') ? 'normal' : '';
						var font_weight = (style_value && style_value !== 'italic' && style_value !== 'normal') ? style_value.replace('italic', '') : font_normal;
						var font_style = (style_value && style_value.substr(-6) === 'italic') ? 'italic' : '';

						$this.find('.spwps--font-weight').val(font_weight);
						$this.find('.spwps--font-style').val(font_style);

					});

					//
					//
					// Extra font style select
					var $extra_font_style_block = $this.find('.spwps--block-extra-styles');

					if ($extra_font_style_block.length) {
						var $extra_font_style_select = $this.find('.spwps--extra-styles');
						var first_extra_style_value = $extra_font_style_select.val();
					}

				}

				//
				//
				// Subsets select
				var $subset_block = $this.find('.spwps--block-subset');
				if ($subset_block.length) {
					var $subset_select = $this.find('.spwps--subset');
					var first_subset_select_value = $subset_select.val();
					var subset_multi_select = $subset_select.data('multiple') || false;
				}

				//
				//
				// Backup font family
				var $backup_font_family_block = $this.find('.spwps--block-backup-font-family');

				//
				//
				// Font Family on Change Listener
				$font_family_select.on('change spwps.change', function (event) {

					// Hide subsets on change
					if ($subset_block.length) {
						$subset_block.addClass('hidden');
					}

					// Hide extra font style on change
					if ($extra_font_style_block.length) {
						$extra_font_style_block.addClass('hidden');
					}

					// Hide backup font family on change
					if ($backup_font_family_block.length) {
						$backup_font_family_block.addClass('hidden');
					}

					var $selected = $font_family_select.find(':selected');
					var value = $selected.val();
					var type = $selected.data('type');

					if (type && value) {

						// Show backup fonts if font type google or custom
						if ((type === 'google' || type === 'custom') && $backup_font_family_block.length) {
							$backup_font_family_block.removeClass('hidden');
						}

						// Appending font style select options
						if ($font_style_block.length) {

							// set styles for multi and normal style selectors
							var styles = defaultstyles;

							// Custom or gogle font styles
							if (type === 'google' && webfonts[type].fonts[value][0]) {
								styles = webfonts[type].fonts[value][0];
							} else if (type === 'custom' && webfonts[type].fonts[value]) {
								styles = webfonts[type].fonts[value];
							}

							selected_styles = styles;

							// Set selected style value for avoid load errors
							var set_auto_style = (styles.indexOf('normal') !== -1) ? 'normal' : styles[0];
							var set_style_value = (first_style_value && styles.indexOf(first_style_value) !== -1) ? first_style_value : set_auto_style;

							// Append style select options
							base.append_select_options($font_style_select, styles, set_style_value, 'style');

							// Clear first value
							first_style_value = false;

							// Show style select after appended
							$font_style_block.removeClass('hidden');

							// Appending extra font style select options
							if (type === 'google' && $extra_font_style_block.length && styles.length > 1) {

								// Append extra-style select options
								base.append_select_options($extra_font_style_select, styles, first_extra_style_value, 'style', true);

								// Clear first value
								first_extra_style_value = false;

								// Show style select after appended
								$extra_font_style_block.removeClass('hidden');

							}

						}

						// Appending google fonts subsets select options
						if (type === 'google' && $subset_block.length && webfonts[type].fonts[value][1]) {

							var subsets = webfonts[type].fonts[value][1];
							var set_auto_subset = (subsets.length < 2 && subsets[0] !== 'latin') ? subsets[0] : '';
							var set_subset_value = (first_subset_select_value && subsets.indexOf(first_subset_select_value) !== -1) ? first_subset_select_value : set_auto_subset;

							// check for multiple subset select
							set_subset_value = (subset_multi_select && first_subset_select_value) ? first_subset_select_value : set_subset_value;

							base.append_select_options($subset_select, subsets, set_subset_value, 'subset', subset_multi_select);

							first_subset_select_value = false;

							$subset_block.removeClass('hidden');

						}

					} else {

						// Clear Styles
						$styles.find(':input').val('');

						// Clear subsets options if type and value empty
						if ($subset_block.length) {
							$subset_select.find('option').not(':first-child').remove();
							$subset_select.trigger('chosen:updated');
						}

						// Clear font styles options if type and value empty
						if ($font_style_block.length) {
							$font_style_select.find('option').not(':first-child').remove();
							$font_style_select.trigger('chosen:updated');
						}

					}

					// Update font type input value
					$type.val(type);

				}).trigger('spwps.change');

				//
				//
				// Preview
				var $preview_block = $this.find('.spwps--block-preview');

				if ($preview_block.length) {

					var $preview = $this.find('.spwps--preview');

					// Set preview styles on change
					$this.on('change', SPF_WPSP.helper.debounce(function (event) {

						$preview_block.removeClass('hidden');

						var font_family = $font_family_select.val(),
							font_weight = $this.find('.spwps--font-weight').val(),
							font_style = $this.find('.spwps--font-style').val(),
							font_size = $this.find('.spwps--font-size').val(),
							font_variant = $this.find('.spwps--font-variant').val(),
							line_height = $this.find('.spwps--line-height').val(),
							text_align = $this.find('.spwps--text-align').val(),
							text_transform = $this.find('.spwps--text-transform').val(),
							text_decoration = $this.find('.spwps--text-decoration').val(),
							text_color = $this.find('.spwps--color').val(),
							word_spacing = $this.find('.spwps--word-spacing').val(),
							letter_spacing = $this.find('.spwps--letter-spacing').val(),
							custom_style = $this.find('.spwps--custom-style').val(),
							type = $this.find('.spwps--type').val();

						if (type === 'google') {
							base.load_google_font(font_family, font_weight, font_style);
						}

						var properties = {};

						if (font_family) { properties.fontFamily = font_family; }
						if (font_weight) { properties.fontWeight = font_weight; }
						if (font_style) { properties.fontStyle = font_style; }
						if (font_variant) { properties.fontVariant = font_variant; }
						if (font_size) { properties.fontSize = font_size + unit; }
						if (line_height) { properties.lineHeight = line_height + line_height_unit; }
						if (letter_spacing) { properties.letterSpacing = letter_spacing + unit; }
						if (word_spacing) { properties.wordSpacing = word_spacing + unit; }
						if (text_align) { properties.textAlign = text_align; }
						if (text_transform) { properties.textTransform = text_transform; }
						if (text_decoration) { properties.textDecoration = text_decoration; }
						if (text_color) { properties.color = text_color; }

						$preview.removeAttr('style');

						// Customs style attribute
						if (custom_style) { $preview.attr('style', custom_style); }

						$preview.css(properties);

					}, 100));

					// Preview black and white backgrounds trigger
					$preview_block.on('click', function () {

						$preview.toggleClass('spwps--black-background');

						var $toggle = $preview_block.find('.spwps--toggle');

						if ($toggle.hasClass('fa-toggle-off')) {
							$toggle.removeClass('fa-toggle-off').addClass('fa-toggle-on');
						} else {
							$toggle.removeClass('fa-toggle-on').addClass('fa-toggle-off');
						}

					});

					if (!$preview_block.hasClass('hidden')) {
						$this.trigger('change');
					}

				}

			};

			base.init();

		});
	};

	//
	// Confirm
	//
	$.fn.spwps_confirm = function () {
		return this.each(function () {
			$(this).on('click', function (e) {

				var confirm_text = $(this).data('confirm') || window.spwps_vars.i18n.confirm;
				var confirm_answer = confirm(confirm_text);

				if (confirm_answer) {
					SPF_WPSP.vars.is_confirm = true;
					SPF_WPSP.vars.form_modified = false;
				} else {
					e.preventDefault();
					return false;
				}

			});
		});
	};

	$.fn.serializeObject = function () {

		var obj = {};

		$.each(this.serializeArray(), function (i, o) {
			var n = o.name,
				v = o.value;

			obj[n] = obj[n] === undefined ? v
				: $.isArray(obj[n]) ? obj[n].concat(v)
					: [obj[n], v];
		});

		return obj;

	};

	//
	// Options Save
	//
	$.fn.spwps_save = function () {
		return this.each(function () {

			var $this = $(this),
				$buttons = $('.spwps-save'),
				$panel = $('.spwps-options'),
				flooding = false,
				timeout;

			$this.on('click', function (e) {

				if (!flooding) {

					var $text = $this.data('save'),
						$value = $this.val();

					$buttons.attr('value', $text);

					if ($this.hasClass('spwps-save-ajax')) {

						e.preventDefault();

						$panel.addClass('spwps-saving');
						$buttons.prop('disabled', true);

						window.wp.ajax.post('spwps_' + $panel.data('unique') + '_ajax_save', {
							data: $('#spwps-form').serializeJSONSPF_WPSP()
						})
							.done(function (response) {

								// clear errors
								$('.spwps-error').remove();

								if (Object.keys(response.errors).length) {

									var error_icon = '<i class="spwps-label-error spwps-error">!</i>';

									$.each(response.errors, function (key, error_message) {

										var $field = $('[data-depend-id="' + key + '"]'),
											$link = $('a[href="#tab=' + $field.closest('.spwps-section').data('section-id') + '"]'),
											$tab = $link.closest('.spwps-tab-item');

										$field.closest('.spwps-fieldset').append('<p class="spwps-error spwps-error-text">' + error_message + '</p>');

										if (!$link.find('.spwps-error').length) {
											$link.append(error_icon);
										}

										if (!$tab.find('.spwps-arrow .spwps-error').length) {
											$tab.find('.spwps-arrow').append(error_icon);
										}
										//  $('.spwps-options .spwps-save.spwps-save-ajax').attr('disabled', true);
									});

								}

								$panel.removeClass('spwps-saving');
								$buttons.prop('disabled', true).attr('value', 'Changes Saved');
								flooding = false;

								SPF_WPSP.vars.form_modified = false;
								SPF_WPSP.vars.$form_warning.hide();

								clearTimeout(timeout);

								var $result_success = $('.spwps-form-success');
								$result_success.empty().append(response.notice).fadeIn('fast', function () {
									timeout = setTimeout(function () {
										$result_success.fadeOut('fast');
									}, 1000);
								});

							})
							.fail(function (response) {
								alert(response.error);
							});

					} else {

						SPF_WPSP.vars.form_modified = false;

					}

				}

				flooding = true;

			});

		});
	};

	//
	// Option Framework
	//
	$.fn.spwps_options = function () {
		return this.each(function () {

			var $this = $(this),
				$content = $this.find('.spwps-content'),
				$form_success = $this.find('.spwps-form-success'),
				$form_warning = $this.find('.spwps-form-warning'),
				$save_button = $this.find('.spwps-header .spwps-save');

			SPF_WPSP.vars.$form_warning = $form_warning;

			// Shows a message white leaving theme options without saving
			if ($form_warning.length) {

				window.onbeforeunload = function () {
					return (SPF_WPSP.vars.form_modified) ? true : undefined;
				};

				$content.on('change keypress', ':input', function () {
					if (!SPF_WPSP.vars.form_modified) {
						$form_success.hide();
						$form_warning.fadeIn('fast');
						SPF_WPSP.vars.form_modified = true;
					}
				});

			}

			if ($form_success.hasClass('spwps-form-show')) {
				setTimeout(function () {
					$form_success.fadeOut('fast');
				}, 1000);
			}

			$(document).keydown(function (event) {
				if ((event.ctrlKey || event.metaKey) && event.which === 83) {
					$save_button.trigger('click');
					event.preventDefault();
					return false;
				}
			});

		});
	};

	//
	// WP Color Picker
	//
	if (typeof Color === 'function') {

		Color.prototype.toString = function () {

			if (this._alpha < 1) {
				return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
			}

			var hex = parseInt(this._color, 10).toString(16);

			if (this.error) { return ''; }

			if (hex.length < 6) {
				for (var i = 6 - hex.length - 1; i >= 0; i--) {
					hex = '0' + hex;
				}
			}

			return '#' + hex;

		};

	}

	SPF_WPSP.funcs.parse_color = function (color) {

		var value = color.replace(/\s+/g, ''),
			trans = (value.indexOf('rgba') !== -1) ? parseFloat(value.replace(/^.*,(.+)\)/, '$1') * 100) : 100,
			rgba = (trans < 100) ? true : false;

		return { value: value, transparent: trans, rgba: rgba };

	};

	$.fn.spwps_color = function () {
		return this.each(function () {

			var $input = $(this),
				picker_color = SPF_WPSP.funcs.parse_color($input.val()),
				palette_color = window.spwps_vars.color_palette.length ? window.spwps_vars.color_palette : true,
				$container;

			// Destroy and Reinit
			if ($input.hasClass('wp-color-picker')) {
				$input.closest('.wp-picker-container').after($input).remove();
			}

			$input.wpColorPicker({
				palettes: palette_color,
				change: function (event, ui) {

					var ui_color_value = ui.color.toString();

					$container.removeClass('spwps--transparent-active');
					$container.find('.spwps--transparent-offset').css('background-color', ui_color_value);
					$input.val(ui_color_value).trigger('change');

				},
				create: function () {

					$container = $input.closest('.wp-picker-container');

					var a8cIris = $input.data('a8cIris'),
						$transparent_wrap = $('<div class="spwps--transparent-wrap">' +
							'<div class="spwps--transparent-slider"></div>' +
							'<div class="spwps--transparent-offset"></div>' +
							'<div class="spwps--transparent-text"></div>' +
							'<div class="spwps--transparent-button">transparent <i class="fa fa-toggle-off"></i></div>' +
							'</div>').appendTo($container.find('.wp-picker-holder')),
						$transparent_slider = $transparent_wrap.find('.spwps--transparent-slider'),
						$transparent_text = $transparent_wrap.find('.spwps--transparent-text'),
						$transparent_offset = $transparent_wrap.find('.spwps--transparent-offset'),
						$transparent_button = $transparent_wrap.find('.spwps--transparent-button');

					if ($input.val() === 'transparent') {
						$container.addClass('spwps--transparent-active');
					}

					$transparent_button.on('click', function () {
						if ($input.val() !== 'transparent') {
							$input.val('transparent').trigger('change').removeClass('iris-error');
							$container.addClass('spwps--transparent-active');
						} else {
							$input.val(a8cIris._color.toString()).trigger('change');
							$container.removeClass('spwps--transparent-active');
						}
					});

					$transparent_slider.slider({
						value: picker_color.transparent,
						step: 1,
						min: 0,
						max: 100,
						slide: function (event, ui) {

							var slide_value = parseFloat(ui.value / 100);
							a8cIris._color._alpha = slide_value;
							$input.wpColorPicker('color', a8cIris._color.toString());
							$transparent_text.text((slide_value === 1 || slide_value === 0 ? '' : slide_value));

						},
						create: function () {

							var slide_value = parseFloat(picker_color.transparent / 100),
								text_value = slide_value < 1 ? slide_value : '';

							$transparent_text.text(text_value);
							$transparent_offset.css('background-color', picker_color.value);

							$container.on('click', '.wp-picker-clear', function () {

								a8cIris._color._alpha = 1;
								$transparent_text.text('');
								$transparent_slider.slider('option', 'value', 100);
								$container.removeClass('spwps--transparent-active');
								$input.trigger('change');

							});

							$container.on('click', '.wp-picker-default', function () {

								var default_color = SPF_WPSP.funcs.parse_color($input.data('default-color')),
									default_value = parseFloat(default_color.transparent / 100),
									default_text = default_value < 1 ? default_value : '';

								a8cIris._color._alpha = default_value;
								$transparent_text.text(default_text);
								$transparent_slider.slider('option', 'value', default_color.transparent);

								if (default_color.value === 'transparent') {
									$input.removeClass('iris-error');
									$container.addClass('spwps--transparent-active');
								}

							});

						}
					});
				}
			});

		});
	};

	//
	// ChosenJS
	//
	$.fn.spwps_chosen = function () {
		return this.each(function () {

			var $this = $(this),
				$inited = $this.parent().find('.chosen-container'),
				is_sortable = $this.hasClass('spwps-chosen-sortable') || false,
				is_ajax = $this.hasClass('spwps-chosen-ajax') || false,
				is_multiple = $this.attr('multiple') || false,
				set_width = is_multiple ? '100%' : 'auto',
				set_options = $.extend({
					allow_single_deselect: true,
					disable_search_threshold: 10,
					width: set_width,
					no_results_text: window.spwps_vars.i18n.no_results_text,
				}, $this.data('chosen-settings'));

			if ($inited.length) {
				$inited.remove();
			}

			// Chosen ajax
			if (is_ajax) {

				var set_ajax_options = $.extend({
					data: {
						type: 'post',
						nonce: '',
					},
					allow_single_deselect: true,
					disable_search_threshold: -1,
					width: '100%',
					min_length: 3,
					type_delay: 500,
					typing_text: window.spwps_vars.i18n.typing_text,
					searching_text: window.spwps_vars.i18n.searching_text,
					no_results_text: window.spwps_vars.i18n.no_results_text,
				}, $this.data('chosen-settings'));

				$this.SPF_WPSPAjaxChosen(set_ajax_options);

			} else {

				$this.chosen(set_options);

			}

			// Chosen keep options order
			if (is_multiple) {

				var $hidden_select = $this.parent().find('.spwps-hide-select');
				var $hidden_value = $hidden_select.val() || [];

				$this.on('change', function (obj, result) {

					if (result && result.selected) {
						$hidden_select.append('<option value="' + result.selected + '" selected="selected">' + result.selected + '</option>');
					} else if (result && result.deselected) {
						$hidden_select.find('option[value="' + result.deselected + '"]').remove();
					}

					// Force customize refresh
					if (window.wp.customize !== undefined && $hidden_select.children().length === 0 && $hidden_select.data('customize-setting-link')) {
						window.wp.customize.control($hidden_select.data('customize-setting-link')).setting.set('');
					}

					$hidden_select.trigger('change');

				});

				// Chosen order abstract
				$this.SPF_WPSPChosenOrder($hidden_value, true);

			}

			// Chosen sortable
			if (is_sortable) {

				var $chosen_container = $this.parent().find('.chosen-container');
				var $chosen_choices = $chosen_container.find('.chosen-choices');

				$chosen_choices.bind('mousedown', function (event) {
					if ($(event.target).is('span')) {
						event.stopPropagation();
					}
				});

				$chosen_choices.sortable({
					items: 'li:not(.search-field)',
					helper: 'orginal',
					cursor: 'move',
					placeholder: 'search-choice-placeholder',
					start: function (e, ui) {
						ui.placeholder.width(ui.item.innerWidth());
						ui.placeholder.height(ui.item.innerHeight());
					},
					update: function (e, ui) {

						var select_options = '';
						var chosen_object = $this.data('chosen');
						var $prev_select = $this.parent().find('.spwps-hide-select');

						$chosen_choices.find('.search-choice-close').each(function () {
							var option_array_index = $(this).data('option-array-index');
							$.each(chosen_object.results_data, function (index, data) {
								if (data.array_index === option_array_index) {
									select_options += '<option value="' + data.value + '" selected>' + data.value + '</option>';
								}
							});
						});

						$prev_select.children().remove();
						$prev_select.append(select_options);
						$prev_select.trigger('change');

					}
				});

			}

		});
	};

	//
	// Helper Checkbox Checker
	//
	$.fn.spwps_checkbox = function () {
		return this.each(function () {

			var $this = $(this),
				$input = $this.find('.spwps--input'),
				$checkbox = $this.find('.spwps--checkbox');

			$checkbox.on('click', function () {
				$input.val(Number($checkbox.prop('checked'))).trigger('change');
			});

		});
	};

	//
	// Siblings
	//
	$.fn.spwps_siblings = function () {
		return this.each(function () {

			var $this = $(this),
				$siblings = $this.find('.spwps--sibling'),
				multiple = $this.data('multiple') || false;

			$siblings.on('click', function () {

				var $sibling = $(this);

				if (multiple) {

					if ($sibling.hasClass('spwps--active')) {
						$sibling.removeClass('spwps--active');
						$sibling.find('input').prop('checked', false).trigger('change');
					} else {
						$sibling.addClass('spwps--active');
						$sibling.find('input').prop('checked', true).trigger('change');
					}

				} else {

					$this.find('input').prop('checked', false);
					$sibling.find('input').prop('checked', true).trigger('change');
					$sibling.addClass('spwps--active').siblings().removeClass('spwps--active');

				}

			});

		});
	};

	//
	// Help Tooltip
	//
	//   $.fn.spwps_help = function () {
	//     return this.each(function () {

	//       var $this = $(this),
	//         $tooltip,
	//         offset_left;

	//       $this.on({
	//         mouseenter: function () {

	//           $tooltip = $('<div class="spwps-tooltip"></div>').html($this.find('.spwps-help-text').html()).appendTo('body');
	//           offset_left = (SPF_WPSP.vars.is_rtl) ? ($this.offset().left - $tooltip.outerWidth()) : ($this.offset().left + 24);

	//           $tooltip.css({
	//             top: $this.offset().top - (($tooltip.outerHeight() / 2) - 14),
	//             left: offset_left,
	//           });

	//         },
	//         mouseleave: function () {

	//           if ($tooltip !== undefined) {
	//             $tooltip.remove();
	//           }

	//         }

	//       });

	//     });
	//   };

	//
	// Help Tooltip
	//
	$.fn.spwps_help = function () {
		return this.each(function () {

			var $this = $(this),
				$tooltip,
				$class = '';
			$this.on({
				mouseenter: function () {
					// this class add with the support tooltip.
					if ($this.find('.spwps-support').length > 0) {
						$class = 'support-tooltip';
					}
					$tooltip = $('<div class="spwps-tooltip ' + $class + '"></div>').html($this.find('.spwps-help-text').html()).appendTo('body');
					var offset_left = SPF_WPSP.vars.is_rtl
						? $this.offset().left - $tooltip.outerWidth()
						: $this.offset().left + 24;
					var $top = $this.offset().top - ($tooltip.outerHeight() / 2 - 14);

					// this block used for support tooltip.
					if ($this.find('.spwps-support').length > 0) {
						$top = $this.offset().top + 54;
						offset_left = $this.offset().left - 245;
					}
					$tooltip.css({
						top: $top,
						left: offset_left,
					});
				},
				mouseleave: function () {
					if ($tooltip !== undefined) {
						// Check if the cursor is still over the tooltip
						if (!$tooltip.is(':hover')) {
							$tooltip.remove();
						}
					}
				}
			});
			// Event delegation to handle tooltip removal when the cursor leaves the tooltip itself.
			$('body').on('mouseleave', '.spwps-tooltip', function () {
				if ($tooltip !== undefined) {
					$tooltip.remove();
				}
			});
		});
	};

	//
	// Window on resize
	//
	SPF_WPSP.vars.$window.on('resize spwps.resize', SPF_WPSP.helper.debounce(function (event) {

		var window_width = navigator.userAgent.indexOf('AppleWebKit/') > -1 ? SPF_WPSP.vars.$window.width() : window.innerWidth;

		if (window_width <= 782 && !SPF_WPSP.vars.onloaded) {
			$('.spwps-section').spwps_reload_script();
			SPF_WPSP.vars.onloaded = true;
		}

	}, 200)).trigger('spwps.resize');



	//
	// Reload Plugins
	//
	$.fn.spwps_reload_script = function (options) {

		var settings = $.extend({
			dependency: true,
		}, options);

		return this.each(function () {

			var $this = $(this);

			// Avoid for conflicts
			if (!$this.data('inited')) {

				// Field plugins
				$this.children('.spwps-field-code_editor').spwps_field_code_editor();
				$this.children('.spwps-field-fieldset').spwps_field_fieldset();
				$this.children('.spwps-field-slider').spwps_field_slider();

				$this.children('.spwps-field-spinner').spwps_field_spinner();
				$this.children('.spwps-field-switcher').spwps_field_switcher();
				$this.children('.spwps-field-typography').spwps_field_typography();



				// Field colors
				$this.children('.spwps-field-border').find('.spwps-color').spwps_color();
				$this.children('.spwps-field-background').find('.spwps-color').spwps_color();
				$this.children('.spwps-field-color').find('.spwps-color').spwps_color();
				$this.children('.spwps-field-color_group').find('.spwps-color').spwps_color();
				$this.children('.spwps-field-typography').find('.spwps-color').spwps_color();

				// Field chosenjs
				$this.children('.spwps-field-select').find('.spwps-chosen').spwps_chosen();

				// Field Checkbox
				$this.children('.spwps-field-checkbox').find('.spwps-checkbox').spwps_checkbox();
				$this.children('.spwps-field-tabbed').spwps_field_tabbed();
				// Field Siblings
				$this.children('.spwps-field-button_set').find('.spwps-siblings').spwps_siblings();
				$this.children('.spwps-field-image_select, .spwps-field-icon_select').find('.spwps-siblings').spwps_siblings();

				// Help Tooptip
				$this.children('.spwps-field').find('.spwps-help').spwps_help();
				$('.sp-wpsp-mbf-banner').find('.sp-wpsp-submit-options').spwps_help();


				if (settings.dependency) {
					$this.spwps_dependency();
				}

				$this.data('inited', true);

				$(document).trigger('spwps-reload-script', $this);

			}

		});
	};

	//
	// Document ready and run scripts
	//
	$(document).ready(function () {

		$('.spwps-save').spwps_save();
		$('.spwps-options').spwps_options();
		$('.spwps-sticky-header').spwps_sticky();
		$('.spwps-nav-options').spwps_nav_options();
		$('.spwps-nav-metabox').spwps_nav_metabox();
		$('.spwps-search').spwps_search();
		$('.spwps-confirm').spwps_confirm();
		$('.spwps-expand-all').spwps_expand_all();
		$('.spwps-onload').spwps_reload_script();

	});

	// Disabled save button.
	$(document).on('keyup change', '.spwps-options #spwps-form', function (e) {
		e.preventDefault();
		$(this).find('.spwps-save.spwps-save-ajax').attr('value', 'Save Settings').attr('disabled', false);
	});
})(jQuery, window, document);

// Shortcode tabe show/hide.
jQuery(function ($) {
	// Shortcode copy to clipboard.
	$('.wpspro_shortcode .wpspro-col-lg-3 .selectable').on('click',function (e) {
		e.preventDefault();
		wpspro_copyToClipboard($(this));
		wpspro_SelectText($(this));
		$(this).focus().select();
		jQuery(".wpspro-after-copy-text:not(.wpspro-pagination-not-work)").animate({
			opacity: 1,
			bottom: 25,
		}, 300);
		setTimeout(function () {
			jQuery(".wpspro-after-copy-text:not(.wpspro-pagination-not-work)").animate({
				opacity: 0,
			}, 200);
			jQuery(".wpspro-after-copy-text:not(.wpspro-pagination-not-work)").animate({
				bottom: 0
			}, 0);
		}, 2000);
	});
	$('.post-type-sp_wps_shortcodes .column-shortcode input').on('click', function (e) {
		e.preventDefault();
		/* Get the text field */
		var copyText = $(this);
		/* Select the text field */
		copyText.select();
		document.execCommand("copy");
		jQuery(".wpspro-after-copy-text").animate({
			opacity: 1,
			bottom: 25,
		}, 300);
		setTimeout(function () {
			jQuery(".wpspro-after-copy-text").animate({
				opacity: 0,
			}, 200);
			jQuery(".wpspro-after-copy-text").animate({
				bottom: 0
			}, 0);
		}, 2000);
	});
	function wpspro_copyToClipboard(element) {
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val($(element).text()).select();
		document.execCommand("copy");
		$temp.remove();
	}
	function wpspro_SelectText(element) {
		var r = document.createRange();
		var w = element.get(0);
		r.selectNodeContents(w);
		var sel = window.getSelection();
		sel.removeAllRanges();
		sel.addRange(r);
	}

	// Woo-Product-Slider export.
	var $export_type = $('.wpsp_what_export').find('input:checked').val();
	$('.wpsp_what_export').on('change', function () {
		$export_type = $(this).find('input:checked').val();
	});
	// Check is valid JSON string.
	function isValidJSONString(str) {
		try {
			JSON.parse(str);
		} catch (e) {
			return false;
		}
		return true;
	}
	$('.wpsp_export .spwps--button').on('click', function (event) {
		event.preventDefault();
		var $shortcode_ids = $('.wpsp_post_ids select').val();
		var $ex_nonce = $('#spwps_options_noncesp_woo_product_slider_tools').val();
		var selected_shortcode = $export_type === 'selected_shortcodes' ? $shortcode_ids : 'all_shortcodes';
		if ($export_type === 'all_shortcodes' || $export_type === 'selected_shortcodes') {
			var data = {
				action: 'wpsp_export_shortcodes',
				wpsp_ids: selected_shortcode,
				nonce: $ex_nonce,
			}
		} else {
			$('.spwps-form-result.spwps-form-success').text('No carousel selected.').show();
			setTimeout(function () {
				$('.spwps-form-result.spwps-form-success').hide().text('');
			}, 3000);
		}
		$.post(ajaxurl, data, function (resp) {
			if (resp) {
				// Convert JSON Array to string.
				if (isValidJSONString(resp)) {
					var json = JSON.stringify(JSON.parse(resp));
				} else {
					var json = JSON.stringify(resp);
				}
				// Convert JSON string to BLOB.
				json = [json];
				var blob = new Blob(json);
				var link = document.createElement('a');
				var wpsp_time = $.now();
				link.href = window.URL.createObjectURL(blob);
				link.download = "woo-product-slider-pro-export-" + wpsp_time + ".json";
				link.click();
				$('.spwps-form-result.spwps-form-success').text('Exported successfully!').show();
				setTimeout(function () {
					$('.spwps-form-result.spwps-form-success').hide().text('');
					$('.wpsp_post_ids select').val('').trigger('chosen:updated');
				}, 3000);
			}
		});
	});

	// Product Slider import.
	$('.wpsp_import button.import').on('click',function (event) {
		event.preventDefault();
		var $this = $(this),
			this_text = $(this).text(),
			wpsp_shortcodes = $('#import').prop('files')[0];

		if ($('#import').val() != '') {
			$this.attr("disabled", true).css({ 'opacity': '0.7' })
				.html(this_text + ' <i class="fa fa-spinner"></i>');
			var $im_nonce = $('#spwps_options_noncesp_woo_product_slider_tools').val();
			var reader = new FileReader();
			reader.readAsText(wpsp_shortcodes);
			reader.onload = function (event) {
				var jsonObj = JSON.stringify(event.target.result);
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						shortcode: jsonObj,
						action: 'wpsp_import_shortcodes',
						nonce: $im_nonce,
					},
					success: function (resp) {
						$('.spwps-form-result.spwps-form-success').text('Imported successfully!').show();
						$this.html(this_text);
						setTimeout(function () {
							$('.spwps-form-result.spwps-form-success').hide().text('');
							$('#import').val('');
							$this.removeAttr("disabled").css({ 'opacity': '1' });
							window.location.replace($('#wpsp_shortcode_link_redirect').attr('href'));
						}, 2000);
					},
					error: function (resp) {
						// console.log(resp);
						alert('Error: something is wrong!');
						$('#import').val('');
						$this.html(this_text).removeAttr("disabled")
							.css({ 'opacity': '1' }).html(this_text);
					}
				});
			}
		} else {
			$('.spwps-form-result.spwps-form-success').text('No exported json file chosen.').show();
			setTimeout(function () {
				$('.spwps-form-result.spwps-form-success').hide().text('');
			}, 3000);
		}
	});
	// Theme template preview scripts.
	$('.template_style').on('change', function () {
		var selected_style = $(".template_style input:checked").val();
		if ('custom' == selected_style) {
			$(".theme_style option").eq(0).prop('selected', true);
		}
	});
	$('.theme_style').on('change', function () {
		var str = "";
		$(".theme_style option:selected").each(function () {
			str = $(this).val();
		});
		var src = $(".theme_style img").attr('src');
		//var pattern = (?<=This is)(.*)(?=sentence);
		var result = src.match(/theme\/(.+)\.png/);
		//src.replace(/(?<theme"\w+)(\d+)(\w+".*)/, str);
		src = src.replace(result[1], str);
		$(".theme_style img").attr("src", src);
	});
	// Live Preview script.
	var preview_box = $('#spwps-preview-box');
	var preview_display = $('#spwps_live_preview').hide();
	$(document).on('click', '#spwps-show-preview:contains(Hide)', function (e) {
		e.preventDefault();
		var _this = $(this);
		_this.html('<i class="fa fa-eye" aria-hidden="true"></i> Show Preview');
		preview_box.html('');
		preview_display.hide();
	});

	$(document).on('click', '#spwps-show-preview:not(:contains(Hide))', function (e) {
		e.preventDefault();
		var previewJS = window.spwps_vars.previewJS;
		var _data = $('form#post').serialize();
		var _this = $(this);
		var data = {
			action: 'spwps_preview_meta_box',
			data: _data,
			ajax_nonce: $('#spwps_metabox_noncesp_wps_shortcode_options').val()
		};
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			error: function (response) {
				console.log(response)
			},
			success: function (response) {
				preview_display.show();
				preview_box.html(response);
				$.getScript(previewJS, function () {
					_this.html('<i class="fa fa-eye-slash" aria-hidden="true"></i> Hide Preview');
					$(document).on('keyup change', function (e) {
						e.preventDefault();
						_this.html('<i class="fa fa-refresh" aria-hidden="true"></i> Update Preview');
					});
					$("html, body").animate({ scrollTop: preview_display.offset().top - 50 }, "slow");
				})

				$('.wps-pagination').on('click', function (e) {
					e.preventDefault();
					e.stopPropagation();
					e.stopImmediatePropagation();
					$('.wpspro-pagination-not-work').animate({
						opacity: 1,
						bottom: 25,
					}, 300);
					setTimeout(function () {
						jQuery(".wpspro-pagination-not-work").animate({
							opacity: 0,
						}, 200);
						jQuery(".wpspro-pagination-not-work").animate({
							bottom: 0
						}, 0);
					}, 2500);
				});
			}
		})
	});

	// Function to update icon type
	function updateIconType(selector, regex, type) {
		var str = "";
		$(selector + ' option:selected').each(function () {
			str = $(this).val();
		});
		var src = $(selector + ' .spwps-fieldset img').attr('src');
		var result = src.match(regex);
		if (result && result[1]) {
			src = src.replace(result[1], str);
			$(selector + ' .spwps-fieldset img').attr('src', src);
		}
		if (type.includes(str)) {
			$(selector + ' .wps-pro-notice').hide();
		} else {
			var noticeText = "This is a <a href='https://wooproductslider.io/pricing/?ref=1' target='_blank'>Pro Feature!</a>";
			$(selector + ' .wps-pro-notice').html(noticeText).show();
		}
	}
	if ($('.wps-navigation-position').length > 0) {
		updateIconType(".wps-navigation-position", /navigation-preview\/(.+)\.svg/, 'top_right');
	}
	$('.wps-navigation-position').on('change', function () {
		updateIconType(".wps-navigation-position", /navigation-preview\/(.+)\.svg/, 'top_right');
	});


	// Function to update UI based on selected tab
	function updateUI(selectValue) {
		if (selectValue === "slider") {
			$(".spwps-metabox .spwps-nav-metabox li a[data-section='sp_wps_shortcode_options_4']").show();
			$('#sp_wps_shortcode_options .wps_item_margin_between .spwps--space:nth-child(2)').hide();
		} else {
			$(".spwps-metabox .spwps-nav-metabox li a[data-section='sp_wps_shortcode_options_4']").hide();
			$('#sp_wps_shortcode_options .wps_item_margin_between .spwps--space:nth-child(2)').show();
		}
	}

	// Initial setup
	var selectTab = $('.spwps-field-image_select.layout_preset .spwps--image-group .spwps--image').find("input:checked").val();
	updateUI(selectTab);

	// Handle click event on image
	$(document).on("click", ".spwps-field-image_select.layout_preset .spwps--image-group .spwps--image", function (event) {
		event.stopPropagation();
		var selectValue = $(this).find("input:checked").val();
		updateUI(selectValue);
	});
});
