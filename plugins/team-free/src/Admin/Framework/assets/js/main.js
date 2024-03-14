; (function ($, window, document, undefined) {
	'use strict'

	//
	// Constants
	//
	var SPF = SPF || {}

	SPF.funcs = {}

	SPF.vars = {
		onloaded: false,
		$body: $('body'),
		$window: $(window),
		$document: $(document),
		$form_warning: null,
		is_confirm: false,
		form_modified: false,
		code_themes: [],
		is_rtl: $('body').hasClass('rtl')
	}

	//
	// Helper Functions
	//
	SPF.helper = {
		//
		// Generate UID
		//
		uid: function (prefix) {
			return (
				(prefix || '') +
				Math.random()
					.toString(36)
					.substr(2, 9)
			)
		},

		// Quote regular expression characters
		//
		preg_quote: function (str) {
			return (str + '').replace(/(\[|\])/g, '\\$1')
		},

		//
		// Rename input names

		//
		name_nested_replace: function ($selector, field_id) {
			var checks = []
			var regex = new RegExp(SPF.helper.preg_quote(field_id + '[\\d+]'), 'g')

			$selector.find(':radio').each(function () {
				if (this.checked || this.original_checked) {
					this.original_checked = true
				}
			})

			$selector.each(function (index) {
				$(this)
					.find(':input')
					.each(function () {
						this.name = this.name.replace(regex, field_id + '[' + index + ']')
						if (this.original_checked) {
							this.checked = true
						}
					})
			})
		},

		//
		// Debounce
		//
		debounce: function (callback, threshold, immediate) {
			var timeout
			return function () {
				var context = this,
					args = arguments
				var later = function () {
					timeout = null
					if (!immediate) {
						callback.apply(context, args)
					}
				}
				var callNow = immediate && !timeout
				clearTimeout(timeout)
				timeout = setTimeout(later, threshold)
				if (callNow) {
					callback.apply(context, args)
				}
			}
		},

		//
		// Get a cookie
		//
		get_cookie: function (name) {
			var e,
				b,
				cookie = document.cookie,
				p = name + '='

			if (!cookie) {
				return
			}

			b = cookie.indexOf('; ' + p)

			if (b === -1) {
				b = cookie.indexOf(p)

				if (b !== 0) {
					return null
				}
			} else {
				b += 2
			}

			e = cookie.indexOf(';', b)

			if (e === -1) {
				e = cookie.length
			}

			return decodeURIComponent(cookie.substring(b + p.length, e))
		},

		//
		// Set a cookie
		//
		set_cookie: function (name, value, expires, path, domain, secure) {
			var d = new Date()

			if (typeof expires === 'object' && expires.toGMTString) {
				expires = expires.toGMTString()
			} else if (parseInt(expires, 10)) {
				d.setTime(d.getTime() + parseInt(expires, 10) * 1000)
				expires = d.toGMTString()
			} else {
				expires = ''
			}

			document.cookie =
				name +
				'=' +
				encodeURIComponent(value) +
				(expires ? '; expires=' + expires : '') +
				(path ? '; path=' + path : '') +
				(domain ? '; domain=' + domain : '') +
				(secure ? '; secure' : '')
		},

		//
		// Remove a cookie
		//
		remove_cookie: function (name, path, domain, secure) {
			SPF.helper.set_cookie(name, '', -1000, path, domain, secure)
		}
	}

	//
	// Custom clone for textarea and select clone() bug
	//
	$.fn.spf_clone = function () {
		var base = $.fn.clone.apply(this, arguments),
			clone = this.find('select').add(this.filter('select')),
			cloned = base.find('select').add(base.filter('select'))

		for (var i = 0; i < clone.length; ++i) {
			for (var j = 0; j < clone[i].options.length; ++j) {
				if (clone[i].options[j].selected === true) {
					cloned[i].options[j].selected = true
				}
			}
		}

		this.find(':radio').each(function () {
			this.original_checked = this.checked
		})

		return base
	}

	//
	// Expand All Options
	//
	$.fn.spf_expand_all = function () {
		return this.each(function () {
			$(this).on('click', function (e) {
				e.preventDefault()
				$('.spf-wrapper').toggleClass('spf-show-all')
				$('.spf-section').spf_reload_script()
				$(this)
					.find('.fa')
					.toggleClass('fa-indent')
					.toggleClass('fa-outdent')
			})
		})
	}

	//
	// Options Navigation
	//
	$.fn.spf_nav_options = function () {
		return this.each(function () {
			var $nav = $(this),
				$links = $nav.find('a'),
				$last

			$(window)
				.on('hashchange spf.hashchange', function () {
					var hash = window.location.hash.replace('#tab=', '')
					var slug = hash
						? hash
						: $links
							.first()
							.attr('href')
							.replace('#tab=', '')
					var $link = $('[data-tab-id="' + slug + '"]')

					if ($link.length) {
						$link
							.closest('.spf-tab-item')
							.addClass('spf-tab-expanded')
							.siblings()
							.removeClass('spf-tab-expanded')

						if ($link.next().is('ul')) {
							$link = $link
								.next()
								.find('li')
								.first()
								.find('a')
							slug = $link.data('tab-id')
						}

						$links.removeClass('spf-active')
						$link.addClass('spf-active')

						if ($last) {
							$last.addClass('hidden')
						}

						var $section = $('[data-section-id="' + slug + '"]')

						$section.removeClass('hidden')
						$section.spf_reload_script()

						$('.spf-section-id').val($section.index() + 1)

						$last = $section
					}
				})
				.trigger('spf.hashchange')
		})
	}

	//
	// Metabox Tabs
	//
	$.fn.spf_nav_metabox = function () {
		return this.each(function () {
			var $nav = $(this),
				$links = $nav.find('a'),
				unique_id = $nav.data('unique'),
				post_id = $('#post_ID').val() || 'global',
				$sections = $nav.parent().find('.spf-section'),
				$last

			$links.each(function (index) {
				$(this).on('click', function (e) {
					e.preventDefault()

					var $link = $(this)
					var section_id = $link.data('section')

					$links.removeClass('spf-active')
					$link.addClass('spf-active')

					if ($last !== undefined) {
						$last.addClass('hidden')
					}

					var $section = $sections.eq(index)

					$section.removeClass('hidden')
					$section.spf_reload_script()

					SPF.helper.set_cookie(
						'spf-last-metabox-tab-' + post_id + '-' + unique_id,
						section_id
					)

					$last = $section
				})
				var get_cookie = SPF.helper.get_cookie(
					'spf-last-metabox-tab-' + post_id + '-' + unique_id
				)

				if (get_cookie) {
					$nav.find('a[data-section="' + get_cookie + '"]').trigger('click')
				} else {
					$links.first('a').trigger('click')
				}
			})

			// $links.first().trigger('click')
		})
	}

	//
	// Metabox Page Templates Listener
	//
	$.fn.spf_page_templates = function () {
		if (this.length) {
			$(document).on(
				'change',
				'.editor-page-attributes__template select, #page_template',
				function () {
					var maybe_value = $(this).val() || 'default'

					$('.spf-page-templates')
						.removeClass('spf-metabox-show')
						.addClass('spf-metabox-hide')
					$(
						'.spf-page-' +
						maybe_value.toLowerCase().replace(/[^a-zA-Z0-9]+/g, '-')
					)
						.removeClass('spf-metabox-hide')
						.addClass('spf-metabox-show')
				}
			)
		}
	}

	//
	// Metabox Post Formats Listener
	//
	$.fn.spf_post_formats = function () {
		if (this.length) {
			$(document).on(
				'change',
				'.editor-post-format select, #formatdiv input[name="post_format"]',
				function () {
					var maybe_value = $(this).val() || 'default'

					// Fallback for classic editor version
					maybe_value = maybe_value === '0' ? 'default' : maybe_value

					$('.spf-post-formats')
						.removeClass('spf-metabox-show')
						.addClass('spf-metabox-hide')
					$('.spf-post-format-' + maybe_value)
						.removeClass('spf-metabox-hide')
						.addClass('spf-metabox-show')
				}
			)
		}
	}

	//
	// Search
	//
	$.fn.spf_search = function () {
		return this.each(function () {
			var $this = $(this),
				$input = $this.find('input')

			$input.on('change keyup', function () {
				var value = $(this).val(),
					$wrapper = $('.spf-wrapper'),
					$section = $wrapper.find('.spf-section'),
					$fields = $section.find('> .spf-field:not(.spf-depend-on)'),
					$titles = $fields.find('> .spf-title, .spf-search-tags')

				if (value.length > 3) {
					$fields.addClass('spf-metabox-hide')
					$wrapper.addClass('spf-search-all')

					$titles.each(function () {
						var $title = $(this)

						if ($title.text().match(new RegExp('.*?' + value + '.*?', 'i'))) {
							var $field = $title.closest('.spf-field')

							$field.removeClass('spf-metabox-hide')
							$field.parent().spf_reload_script()
						}
					})
				} else {
					$fields.removeClass('spf-metabox-hide')
					$wrapper.removeClass('spf-search-all')
				}
			})
		})
	}

	//
	// Sticky Header
	//
	$.fn.spf_sticky = function () {
		return this.each(function () {
			var $this = $(this),
				$window = $(window),
				$inner = $this.find('.spf-header-inner'),
				padding =
					parseInt($inner.css('padding-left')) +
					parseInt($inner.css('padding-right')),
				offset = 32,
				scrollTop = 0,
				lastTop = 0,
				ticking = false,
				stickyUpdate = function () {
					var offsetTop = $this.offset().top,
						stickyTop = Math.max(offset, offsetTop - scrollTop),
						winWidth = Math.max(
							document.documentElement.clientWidth,
							window.innerWidth || 0
						)

					if (stickyTop <= offset && winWidth > 782) {
						$inner.css({ width: $this.outerWidth() - padding })
						$this.css({ height: $this.outerHeight() }).addClass('spf-sticky')
					} else {
						$inner.removeAttr('style')
						$this.removeAttr('style').removeClass('spf-sticky')
					}
				},
				requestTick = function () {
					if (!ticking) {
						requestAnimationFrame(function () {
							stickyUpdate()
							ticking = false
						})
					}

					ticking = true
				},
				onSticky = function () {
					scrollTop = $window.scrollTop()
					requestTick()
				}

			$window.on('scroll resize', onSticky)

			onSticky()
		})
	}

	//
	// Dependency System
	//
	$.fn.spf_dependency = function () {
		return this.each(function () {
			var $this = $(this),
				$fields = $this.children('[data-controller]')

			if ($fields.length) {
				var normal_ruleset = $.spf_deps.createRuleset(),
					global_ruleset = $.spf_deps.createRuleset(),
					normal_depends = [],
					global_depends = []

				$fields.each(function () {
					var $field = $(this),
						controllers = $field.data('controller').split('|'),
						conditions = $field.data('condition').split('|'),
						values = $field
							.data('value')
							.toString()
							.split('|'),
						is_global = $field.data('depend-global') ? true : false,
						ruleset = is_global ? global_ruleset : normal_ruleset

					$.each(controllers, function (index, depend_id) {
						var value = values[index] || '',
							condition = conditions[index] || conditions[0]

						ruleset = ruleset.createRule(
							'[data-depend-id="' + depend_id + '"]',
							condition,
							value
						)

						ruleset.include($field)

						if (is_global) {
							global_depends.push(depend_id)
						} else {
							normal_depends.push(depend_id)
						}
					})
				})

				if (normal_depends.length) {
					$.spf_deps.enable($this, normal_ruleset, normal_depends)
				}

				if (global_depends.length) {
					$.spf_deps.enable(SPF.vars.$body, global_ruleset, global_depends)
				}
			}
		})
	}

	//
	// Field: code_editor
	//
	$.fn.spf_field_code_editor = function () {
		return this.each(function () {
			if (typeof CodeMirror !== 'function') {
				return
			}

			var $this = $(this),
				$textarea = $this.find('textarea'),
				$inited = $this.find('.CodeMirror'),
				data_editor = $textarea.data('editor')

			if ($inited.length) {
				$inited.remove()
			}

			var interval = setInterval(function () {
				if ($this.is(':visible')) {
					var code_editor = CodeMirror.fromTextArea($textarea[0], data_editor)

					// load code-mirror theme css.
					if (
						data_editor.theme !== 'default' &&
						SPF.vars.code_themes.indexOf(data_editor.theme) === -1
					) {
						var $cssLink = $('<link>')

						$('#spf-codemirror-css').after($cssLink)

						$cssLink.attr({
							rel: 'stylesheet',
							id: 'spf-codemirror-' + data_editor.theme + '-css',
							href:
								data_editor.cdnURL + '/theme/' + data_editor.theme + '.min.css',
							type: 'text/css',
							media: 'all'
						})

						SPF.vars.code_themes.push(data_editor.theme)
					}

					CodeMirror.modeURL = data_editor.cdnURL + '/mode/%N/%N.min.js'
					CodeMirror.autoLoadMode(code_editor, data_editor.mode)

					code_editor.on('change', function (editor, event) {
						$textarea.val(code_editor.getValue()).trigger('change')
					})

					clearInterval(interval)
				}
			})
		})
	}

	//
	// Field: fieldset
	//
	$.fn.spf_field_fieldset = function () {
		return this.each(function () {
			$(this)
				.find('.spf-fieldset-content')
				.spf_reload_script()
		})
	}

	//
	// Field: group
	//
	$.fn.spf_field_group = function () {
		return this.each(function () {
			var $this = $(this),
				$fieldset = $this.children('.spf-fieldset'),
				$group = $fieldset.length ? $fieldset : $this,
				$wrapper = $group.children('.spf-cloneable-wrapper'),
				$hidden = $group.children('.spf-cloneable-hidden'),
				$max = $group.children('.spf-cloneable-max'),
				$min = $group.children('.spf-cloneable-min'),
				field_id = $wrapper.data('field-id'),
				is_number = Boolean(Number($wrapper.data('title-number'))),
				max = parseInt($wrapper.data('max')),
				min = parseInt($wrapper.data('min'))

			// clear accordion arrows if multi-instance
			if ($wrapper.hasClass('ui-accordion')) {
				$wrapper.find('.ui-accordion-header-icon').remove()
			}

			var update_title_numbers = function ($selector) {
				$selector.find('.spf-cloneable-title-number').each(function (index) {
					$(this).html(
						$(this)
							.closest('.spf-cloneable-item')
							.index() +
						1 +
						'.'
					)
				})
			}

			$wrapper.accordion({
				header: '> .spf-cloneable-item > .spf-cloneable-title',
				collapsible: true,
				active: false,
				animate: false,
				heightStyle: 'content',
				icons: {
					header: 'spf-cloneable-header-icon fa fa-angle-right',
					activeHeader: 'spf-cloneable-header-icon fa fa-angle-down'
				},
				activate: function (event, ui) {
					var $panel = ui.newPanel
					var $header = ui.newHeader

					if ($panel.length && !$panel.data('opened')) {
						var $fields = $panel.children()
						var $first = $fields
							.first()
							.find(':input')
							.first()
						var $title = $header.find('.spf-cloneable-value')

						$first.on('change keyup', function (event) {
							$title.text($first.val())
						})

						$panel.spf_reload_script()
						$panel.data('opened', true)
						$panel.data('retry', false)
					} else if ($panel.data('retry')) {
						$panel.spf_reload_script_retry()
						$panel.data('retry', false)
					}
				}
			})

			$wrapper.sortable({
				axis: 'y',
				handle: '.spf-cloneable-title,.spf-cloneable-sort',
				helper: 'original',
				cursor: 'move',
				placeholder: 'widget-placeholder',
				start: function (event, ui) {
					$wrapper.accordion({ active: false })
					$wrapper.sortable('refreshPositions')
					ui.item.children('.spf-cloneable-content').data('retry', true)
				},
				update: function (event, ui) {
					SPF.helper.name_nested_replace(
						$wrapper.children('.spf-cloneable-item'),
						field_id
					)
					//  $wrapper.spf_customizer_refresh()

					if (is_number) {
						update_title_numbers($wrapper)
					}
				}
			})

			$group.children('.spf-cloneable-add').on('click', function (e) {
				e.preventDefault()

				var count = $wrapper.children('.spf-cloneable-item').length

				$min.hide()

				if (max && count + 1 > max) {
					$max.show()
					return
				}

				var $cloned_item = $hidden.spf_clone(true)

				$cloned_item.removeClass('spf-cloneable-hidden')

				$cloned_item.find(':input[name!="_pseudo"]').each(function () {
					this.name = this.name
						.replace('___', '')
						.replace(field_id + '[0]', field_id + '[' + count + ']')
				})

				$wrapper.append($cloned_item)
				$wrapper.accordion('refresh')
				$wrapper.accordion({ active: count })
				// $wrapper.spf_customizer_refresh()
				//  $wrapper.spf_customizer_listen({ closest: true })

				if (is_number) {
					update_title_numbers($wrapper)
				}
			})

			var event_clone = function (e) {
				e.preventDefault()

				var count = $wrapper.children('.spf-cloneable-item').length

				$min.hide()

				if (max && count + 1 > max) {
					$max.show()
					return
				}

				var $this = $(this),
					$parent = $this.parent().parent(),
					$cloned_helper = $parent
						.children('.spf-cloneable-helper')
						.spf_clone(true),
					$cloned_title = $parent.children('.spf-cloneable-title').spf_clone(),
					$cloned_content = $parent
						.children('.spf-cloneable-content')
						.spf_clone(),
					$cloned_item = $('<div class="spf-cloneable-item" />')

				$cloned_item.append($cloned_helper)
				$cloned_item.append($cloned_title)
				$cloned_item.append($cloned_content)

				$wrapper
					.children()
					.eq($parent.index())
					.after($cloned_item)

				SPF.helper.name_nested_replace(
					$wrapper.children('.spf-cloneable-item'),
					field_id
				)

				$wrapper.accordion('refresh')
				// $wrapper.spf_customizer_refresh()
				// $wrapper.spf_customizer_listen({ closest: true })

				if (is_number) {
					update_title_numbers($wrapper)
				}
			}

			$wrapper
				.children('.spf-cloneable-item')
				.children('.spf-cloneable-helper')
				.on('click', '.spf-cloneable-clone', event_clone)
			$group
				.children('.spf-cloneable-hidden')
				.children('.spf-cloneable-helper')
				.on('click', '.spf-cloneable-clone', event_clone)

			var event_remove = function (e) {
				e.preventDefault()

				var count = $wrapper.children('.spf-cloneable-item').length

				$max.hide()
				$min.hide()

				if (min && count - 1 < min) {
					$min.show()
					return
				}

				$(this)
					.closest('.spf-cloneable-item')
					.remove()

				SPF.helper.name_nested_replace(
					$wrapper.children('.spf-cloneable-item'),
					field_id
				)

				//$wrapper.spf_customizer_refresh()

				if (is_number) {
					update_title_numbers($wrapper)
				}
			}

			$wrapper
				.children('.spf-cloneable-item')
				.children('.spf-cloneable-helper')
				.on('click', '.spf-cloneable-remove', event_remove)
			$group
				.children('.spf-cloneable-hidden')
				.children('.spf-cloneable-helper')
				.on('click', '.spf-cloneable-remove', event_remove)
		})
	}

	//
	// Field: icon
	//
	$.fn.spf_field_icon = function () {
		return this.each(function () {
			var $this = $(this)

			$this.on('click', '.spf-icon-add', function (e) {
				e.preventDefault()

				var $button = $(this)
				var $modal = $('#spf-modal-icon')

				$modal.removeClass('hidden')

				SPF.vars.$icon_target = $this

				if (!SPF.vars.icon_modal_loaded) {
					$modal.find('.spf-modal-loading').show()

					window.wp.ajax
						.post('spf-get-icons', {
							nonce: $button.data('nonce')
						})
						.done(function (response) {
							$modal.find('.spf-modal-loading').hide()

							SPF.vars.icon_modal_loaded = true

							var $load = $modal.find('.spf-modal-load').html(response.content)

							$load.on('click', 'i', function (e) {
								e.preventDefault()

								var icon = $(this).attr('title')

								SPF.vars.$icon_target
									.find('.spf-icon-preview i')
									.removeAttr('class')
									.addClass(icon)
								SPF.vars.$icon_target
									.find('.spf-icon-preview')
									.removeClass('hidden')
								SPF.vars.$icon_target
									.find('.spf-icon-remove')
									.removeClass('hidden')
								SPF.vars.$icon_target
									.find('input')
									.val(icon)
									.trigger('change')

								$modal.addClass('hidden')
							})

							$modal.on('change keyup', '.spf-icon-search', function () {
								var value = $(this).val(),
									$icons = $load.find('i')

								$icons.each(function () {
									var $elem = $(this)

									if ($elem.attr('title').search(new RegExp(value, 'i')) < 0) {
										$elem.hide()
									} else {
										$elem.show()
									}
								})
							})

							$modal.on(
								'click',
								'.spf-modal-close, .spf-modal-overlay',
								function () {
									$modal.addClass('hidden')
								}
							)
						})
						.fail(function (response) {
							$modal.find('.spf-modal-loading').hide()
							$modal.find('.spf-modal-load').html(response.error)
							$modal.on('click', function () {
								$modal.addClass('hidden')
							})
						})
				}
			})

			$this.on('click', '.spf-icon-remove', function (e) {
				e.preventDefault()
				$this.find('.spf-icon-preview').addClass('hidden')
				$this
					.find('input')
					.val('')
					.trigger('change')
				$(this).addClass('hidden')
			})
		})
	}

	//
	// Field: repeater
	//
	$.fn.spf_field_repeater = function () {
		return this.each(function () {
			var $this = $(this),
				$fieldset = $this.children('.spf-fieldset'),
				$repeater = $fieldset.length ? $fieldset : $this,
				$wrapper = $repeater.children('.spf-repeater-wrapper'),
				$hidden = $repeater.children('.spf-repeater-hidden'),
				$max = $repeater.children('.spf-repeater-max'),
				$min = $repeater.children('.spf-repeater-min'),
				field_id = $wrapper.data('field-id'),
				max = parseInt($wrapper.data('max')),
				min = parseInt($wrapper.data('min'))

			$wrapper
				.children('.spf-repeater-item')
				.children('.spf-repeater-content')
				.spf_reload_script()

			$wrapper.sortable({
				axis: 'y',
				handle: '.spf-repeater-sort',
				helper: 'original',
				cursor: 'move',
				placeholder: 'widget-placeholder',
				update: function (event, ui) {
					SPF.helper.name_nested_replace(
						$wrapper.children('.spf-repeater-item'),
						field_id
					)
					//  $wrapper.spf_customizer_refresh()
					ui.item.spf_reload_script_retry()
				}
			})

			$repeater.children('.spf-repeater-add').on('click', function (e) {
				e.preventDefault()

				var count = $wrapper.children('.spf-repeater-item').length

				$min.hide()

				if (max && count + 1 > max) {
					$max.show()
					return
				}

				var $cloned_item = $hidden.spf_clone(true)

				$cloned_item.removeClass('spf-repeater-hidden')

				$cloned_item.find(':input[name!="_pseudo"]').each(function () {
					this.name = this.name
						.replace('___', '')
						.replace(field_id + '[0]', field_id + '[' + count + ']')
				})

				$wrapper.append($cloned_item)
				$cloned_item.children('.spf-repeater-content').spf_reload_script()
				// $wrapper.spf_customizer_refresh()
				// $wrapper.spf_customizer_listen({ closest: true })
			})

			var event_clone = function (e) {
				e.preventDefault()

				var count = $wrapper.children('.spf-repeater-item').length

				$min.hide()

				if (max && count + 1 > max) {
					$max.show()
					return
				}

				var $this = $(this),
					$parent = $this
						.parent()
						.parent()
						.parent(),
					$cloned_content = $parent
						.children('.spf-repeater-content')
						.spf_clone(),
					$cloned_helper = $parent
						.children('.spf-repeater-helper')
						.spf_clone(true),
					$cloned_item = $('<div class="spf-repeater-item" />')

				$cloned_item.append($cloned_content)
				$cloned_item.append($cloned_helper)

				$wrapper
					.children()
					.eq($parent.index())
					.after($cloned_item)

				$cloned_item.children('.spf-repeater-content').spf_reload_script()

				SPF.helper.name_nested_replace(
					$wrapper.children('.spf-repeater-item'),
					field_id
				)

				// $wrapper.spf_customizer_refresh()
				// $wrapper.spf_customizer_listen({ closest: true })
			}

			$wrapper
				.children('.spf-repeater-item')
				.children('.spf-repeater-helper')
				.on('click', '.spf-repeater-clone', event_clone)
			$repeater
				.children('.spf-repeater-hidden')
				.children('.spf-repeater-helper')
				.on('click', '.spf-repeater-clone', event_clone)

			var event_remove = function (e) {
				e.preventDefault()

				var count = $wrapper.children('.spf-repeater-item').length

				$max.hide()
				$min.hide()

				if (min && count - 1 < min) {
					$min.show()
					return
				}

				$(this)
					.closest('.spf-repeater-item')
					.remove()

				SPF.helper.name_nested_replace(
					$wrapper.children('.spf-repeater-item'),
					field_id
				)

				//  $wrapper.spf_customizer_refresh()
			}

			$wrapper
				.children('.spf-repeater-item')
				.children('.spf-repeater-helper')
				.on('click', '.spf-repeater-remove', event_remove)
			$repeater
				.children('.spf-repeater-hidden')
				.children('.spf-repeater-helper')
				.on('click', '.spf-repeater-remove', event_remove)
		})
	}

	//
	// Field: sortable
	//
	$.fn.spf_field_sortable = function () {
		return this.each(function () {
			var $sortable = $(this).find('.spf-sortable')

			$sortable.sortable({
				axis: 'y',
				helper: 'original',
				cursor: 'move',
				placeholder: 'widget-placeholder',
				update: function (event, ui) {
					// $sortable.spf_customizer_refresh()
				}
			})

			$sortable.find('.spf-sortable-content').spf_reload_script()
		})
	}

	//
	// Field: sorter
	//
	$.fn.spf_field_sorter = function () {
		return this.each(function () {
			var $this = $(this),
				$enabled = $this.find('.spf-enabled'),
				$has_disabled = $this.find('.spf-disabled'),
				$disabled = $has_disabled.length ? $has_disabled : false

			$enabled.sortable({
				connectWith: $disabled,
				placeholder: 'ui-sortable-placeholder',
				update: function (event, ui) {
					var $el = ui.item.find('input')

					if (ui.item.parent().hasClass('spf-enabled')) {
						$el.attr('name', $el.attr('name').replace('disabled', 'enabled'))
					} else {
						$el.attr('name', $el.attr('name').replace('enabled', 'disabled'))
					}

					//  $this.spf_customizer_refresh()
				}
			})

			if ($disabled) {
				$disabled.sortable({
					connectWith: $enabled,
					placeholder: 'ui-sortable-placeholder',
					update: function (event, ui) {
						// $this.spf_customizer_refresh()
					}
				})
			}
		})
	}

	//
	// Field: spinner
	//
	$.fn.spf_field_spinner = function () {
		return this.each(function () {
			var $this = $(this),
				$input = $this.find('input'),
				$inited = $this.find('.ui-button'),
				// $inited = $this.find('.ui-spinner-button'),
				data = $input.data()

			if ($inited.length) {
				$inited.remove()
			}

			$input.spinner({
				min: data.min || 0,
				max: data.max || 100,
				step: data.step || 1,
				create: function (event, ui) {
					if (data.unit) {
						$input.after(
							'<span class="ui-button spf--unit">' + data.unit + '</span>'
						)
					}
				},
				spin: function (event, ui) {
					$input.val(ui.value).trigger('change')
				}
			})
		})
	}

	//
	// Field: slider
	//
	$.fn.spf_field_slider = function () {
		return this.each(function () {

			var $this = $(this),
				$input = $this.find('input'),
				$slider = $this.find('.spf-slider-ui'),
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
	// Field: switcher
	//
	$.fn.spf_field_switcher = function () {
		return this.each(function () {
			var $switcher = $(this).find('.spf--switcher')

			$switcher.on('click', function () {
				var value = 0
				var $input = $switcher.find('input')

				if ($switcher.hasClass('spf--active')) {
					$switcher.removeClass('spf--active')
				} else {
					value = 1
					$switcher.addClass('spf--active')
				}

				$input.val(value).trigger('change')
			})
		})
	}

	//
	// Field: tabbed
	//
	$.fn.spf_field_tabbed = function () {
		return this.each(function () {
			var $this = $(this),
				$links = $this.find('.spf-tabbed-nav a'),
				$sections = $this.find('.spf-tabbed-section');

			$links.on('click', function (e) {
				e.preventDefault();

				var $link = $(this),
					index = $link.index(),
					$section = $sections.eq(index);

				// Store the active tab index in a cookie
				SPF.helper.set_cookie('activeTabIndex', index);

				$link.addClass('spf-tabbed-active').siblings().removeClass('spf-tabbed-active');
				$section.spf_reload_script();
				$section.removeClass('hidden').siblings().addClass('hidden');
			});
			// Check if there's a stored active tab index in the cookie
			var activeTabIndex = SPF.helper.get_cookie('activeTabIndex');
			// Check if the cookie exists
			if (activeTabIndex !== null) {
				$links.eq(activeTabIndex).trigger('click');
			} else {
				$links.first().trigger('click');
			}

		});
	};

	//
	// Field: upload
	//
	$.fn.spf_field_upload = function () {
		return this.each(function () {
			var $this = $(this),
				$input = $this.find('input'),
				$upload_button = $this.find('.spf--button'),
				$remove_button = $this.find('.spf--remove'),
				$library =
					($upload_button.data('library') &&
						$upload_button.data('library').split(',')) ||
					'',
				wp_media_frame

			$input.on('change', function (e) {
				if ($input.val()) {
					$remove_button.removeClass('hidden')
				} else {
					$remove_button.addClass('hidden')
				}
			})

			$upload_button.on('click', function (e) {
				e.preventDefault()

				if (
					typeof window.wp === 'undefined' ||
					!window.wp.media ||
					!window.wp.media.gallery
				) {
					return
				}

				if (wp_media_frame) {
					wp_media_frame.open()
					return
				}

				wp_media_frame = window.wp.media({
					library: {
						type: $library
					}
				})

				wp_media_frame.on('select', function () {
					var attributes = wp_media_frame
						.state()
						.get('selection')
						.first().attributes

					if (
						$library.length &&
						$library.indexOf(attributes.subtype) === -1 &&
						$library.indexOf(attributes.type) === -1
					) {
						return
					}

					$input.val(attributes.url).trigger('change')
				})

				wp_media_frame.open()
			})

			$remove_button.on('click', function (e) {
				e.preventDefault()
				$input.val('').trigger('change')
			})
		})
	}

	//
	// Confirm
	//
	$.fn.spf_confirm = function () {
		return this.each(function () {
			$(this).on('click', function (e) {
				var confirm_text =
					$(this).data('confirm') || window.spf_vars.i18n.confirm
				var confirm_answer = confirm(confirm_text)

				if (confirm_answer) {
					SPF.vars.is_confirm = true
					SPF.vars.form_modified = false
				} else {
					e.preventDefault()
					return false
				}
			})
		})
	}

	$.fn.serializeObject = function () {
		var obj = {}

		$.each(this.serializeArray(), function (i, o) {
			var n = o.name,
				v = o.value

			obj[n] =
				obj[n] === undefined
					? v
					: $.isArray(obj[n])
						? obj[n].concat(v)
						: [obj[n], v]
		})

		return obj
	}

	//
	// Options Save
	//
	$.fn.spf_save = function () {
		return this.each(function () {
			var $this = $(this),
				$buttons = $('.spf-save'),
				$panel = $('.spf-options'),
				flooding = false,
				timeout

			$this.on('click', function (e) {
				if (!flooding) {
					var $text = $this.data('save'),
						$value = $this.val()

					$buttons.attr('value', $text)

					if ($this.hasClass('spf-save-ajax')) {
						e.preventDefault()

						$panel.addClass('spf-saving')
						$buttons.prop('disabled', true)

						window.wp.ajax
							.post('spf_' + $panel.data('unique') + '_ajax_save', {
								data: $('#spf-form').serializeJSONSPF()
							})
							.done(function (response) {
								// clear errors
								$('.spf-error').remove()

								if (Object.keys(response.errors).length) {
									var error_icon = '<i class="spf-label-error spf-error">!</i>'

									$.each(response.errors, function (key, error_message) {
										var $field = $('[data-depend-id="' + key + '"]'),
											$link = $(
												'#spf-tab-link-' +
												($field.closest('.spf-section').index() + 1)
											),
											$tab = $link.closest('.spf-tab-depth-0')

										$field
											.closest('.spf-fieldset')
											.append(
												'<p class="spf-error spf-error-text">' +
												error_message +
												'</p>'
											)

										if (!$link.find('.spf-error').length) {
											$link.append(error_icon)
										}

										if (!$tab.find('.spf-arrow .spf-error').length) {
											$tab.find('.spf-arrow').append(error_icon)
										}
									})
								}

								$panel.removeClass('spf-saving')
								$buttons.prop('disabled', false).attr('value', $value)
								flooding = false

								SPF.vars.form_modified = false
								SPF.vars.$form_warning.hide()

								clearTimeout(timeout)

								var $result_success = $('.spf-form-success')
								$result_success
									.empty()
									.append(response.notice)
									.fadeIn('fast', function () {
										timeout = setTimeout(function () {
											$result_success.fadeOut('fast')
										}, 1000)
									})
							})
							.fail(function (response) {
								alert(response.error)
							})
					} else {
						SPF.vars.form_modified = false
					}
				}

				flooding = true
			})
		})
	}

	//
	// Option Framework
	//
	$.fn.spf_options = function () {
		return this.each(function () {
			var $this = $(this),
				$content = $this.find('.spf-content'),
				$form_success = $this.find('.spf-form-success'),
				$form_warning = $this.find('.spf-form-warning'),
				$save_button = $this.find('.spf-header .spf-save')

			SPF.vars.$form_warning = $form_warning

			// Shows a message white leaving theme options without saving
			if ($form_warning.length) {
				window.onbeforeunload = function () {
					return SPF.vars.form_modified ? true : undefined
				}

				$content.on('change keypress', ':input', function () {
					if (!SPF.vars.form_modified) {
						$form_success.hide()
						$form_warning.fadeIn('fast')
						SPF.vars.form_modified = true
					}
				})
			}

			if ($form_success.hasClass('spf-form-show')) {
				setTimeout(function () {
					$form_success.fadeOut('fast')
				}, 1000)
			}

			$(document).on('keydown', function (event) {
				if ((event.ctrlKey || event.metaKey) && event.which === 83) {
					$save_button.trigger('click')
					event.preventDefault()
					return false
				}
			})
		})
	}

	//
	// Taxonomy Framework
	//
	$.fn.spf_taxonomy = function () {
		return this.each(function () {
			var $this = $(this),
				$form = $this.parents('form')

			if ($form.attr('id') === 'addtag') {
				var $submit = $form.find('#submit'),
					$cloned = $this.find('.spf-field').spf_clone()

				$submit.on('click', function () {
					if (!$form.find('.form-required').hasClass('form-invalid')) {
						$this.data('inited', false)

						$this.empty()

						$this.html($cloned)

						$cloned = $cloned.spf_clone()

						$this.spf_reload_script()
					}
				})
			}
		})
	}

	//
	// Shortcode Framework
	//
	$.fn.spf_shortcode = function () {
		var base = this

		base.shortcode_parse = function (serialize, key) {
			var shortcode = ''

			$.each(serialize, function (shortcode_key, shortcode_values) {
				key = key ? key : shortcode_key

				shortcode += '[' + key

				$.each(shortcode_values, function (shortcode_tag, shortcode_value) {
					if (shortcode_tag === 'content') {
						shortcode += ']'
						shortcode += shortcode_value
						shortcode += '[/' + key + ''
					} else {
						shortcode += base.shortcode_tags(shortcode_tag, shortcode_value)
					}
				})

				shortcode += ']'
			})

			return shortcode
		}

		base.shortcode_tags = function (shortcode_tag, shortcode_value) {
			var shortcode = ''

			if (shortcode_value !== '') {
				if (
					typeof shortcode_value === 'object' &&
					!$.isArray(shortcode_value)
				) {
					$.each(shortcode_value, function (
						sub_shortcode_tag,
						sub_shortcode_value
					) {
						// sanitize spesific key/value
						switch (sub_shortcode_tag) {
							case 'background-image':
								sub_shortcode_value = sub_shortcode_value.url
									? sub_shortcode_value.url
									: ''
								break
						}

						if (sub_shortcode_value !== '') {
							shortcode +=
								' ' +
								sub_shortcode_tag.replace('-', '_') +
								'="' +
								sub_shortcode_value.toString() +
								'"'
						}
					})
				} else {
					shortcode +=
						' ' +
						shortcode_tag.replace('-', '_') +
						'="' +
						shortcode_value.toString() +
						'"'
				}
			}

			return shortcode
		}

		base.insertAtChars = function (_this, currentValue) {
			var obj = typeof _this[0].name !== 'undefined' ? _this[0] : _this

			if (obj.value.length && typeof obj.selectionStart !== 'undefined') {
				obj.focus()
				return (
					obj.value.substring(0, obj.selectionStart) +
					currentValue +
					obj.value.substring(obj.selectionEnd, obj.value.length)
				)
			} else {
				obj.focus()
				return currentValue
			}
		}

		base.send_to_editor = function (html, editor_id) {
			var tinymce_editor

			if (typeof tinymce !== 'undefined') {
				tinymce_editor = tinymce.get(editor_id)
			}

			if (tinymce_editor && !tinymce_editor.isHidden()) {
				tinymce_editor.execCommand('mceInsertContent', false, html)
			} else {
				var $editor = $('#' + editor_id)
				$editor.val(base.insertAtChars($editor, html)).trigger('change')
			}
		}

		return this.each(function () {
			var $modal = $(this),
				$load = $modal.find('.spf-modal-load'),
				$content = $modal.find('.spf-modal-content'),
				$insert = $modal.find('.spf-modal-insert'),
				$loading = $modal.find('.spf-modal-loading'),
				$select = $modal.find('select'),
				modal_id = $modal.data('modal-id'),
				nonce = $modal.data('nonce'),
				editor_id,
				target_id,
				sc_key,
				sc_name,
				sc_view,
				sc_group,
				$cloned,
				$button

			$(document).on(
				'click',
				'.spf-shortcode-button[data-modal-id="' + modal_id + '"]',
				function (e) {
					e.preventDefault()

					$button = $(this)
					editor_id = $button.data('editor-id') || false
					target_id = $button.data('target-id') || false

					$modal.removeClass('hidden')

					// single usage trigger first shortcode
					if (
						$modal.hasClass('spf-shortcode-single') &&
						sc_name === undefined
					) {
						$select.trigger('change')
					}
				}
			)

			$select.on('change', function () {
				var $option = $(this)
				var $selected = $option.find(':selected')

				sc_key = $option.val()
				sc_name = $selected.data('shortcode')
				sc_view = $selected.data('view') || 'normal'
				sc_group = $selected.data('group') || sc_name

				$load.empty()

				if (sc_key) {
					$loading.show()

					window.wp.ajax
						.post('spf-get-shortcode-' + modal_id, {
							shortcode_key: sc_key,
							nonce: nonce
						})
						.done(function (response) {
							$loading.hide()

							var $appended = $(response.content).appendTo($load)

							$insert.parent().removeClass('hidden')

							$cloned = $appended.find('.spf--repeat-shortcode').spf_clone()

							$appended.spf_reload_script()
							$appended.find('.spf-fields').spf_reload_script()
						})
				} else {
					$insert.parent().addClass('hidden')
				}
			})

			$insert.on('click', function (e) {
				e.preventDefault()

				if ($insert.prop('disabled') || $insert.attr('disabled')) {
					return
				}

				var shortcode = ''
				var serialize = $modal
					.find('.spf-field:not(.spf-depend-on)')
					.find(':input:not(.ignore)')
					.serializeObjectSPF()

				switch (sc_view) {
					case 'contents':
						var contentsObj = sc_name ? serialize[sc_name] : serialize
						$.each(contentsObj, function (sc_key, sc_value) {
							var sc_tag = sc_name ? sc_name : sc_key
							shortcode += '[' + sc_tag + ']' + sc_value + '[/' + sc_tag + ']'
						})
						break

					case 'group':
						shortcode += '[' + sc_name
						$.each(serialize[sc_name], function (sc_key, sc_value) {
							shortcode += base.shortcode_tags(sc_key, sc_value)
						})
						shortcode += ']'
						shortcode += base.shortcode_parse(serialize[sc_group], sc_group)
						shortcode += '[/' + sc_name + ']'

						break

					case 'repeater':
						shortcode += base.shortcode_parse(serialize[sc_group], sc_group)
						break

					default:
						shortcode += base.shortcode_parse(serialize)
						break
				}

				shortcode = shortcode === '' ? '[' + sc_name + ']' : shortcode

				if (editor_id) {
					base.send_to_editor(shortcode, editor_id)
				} else {
					var $textarea = target_id
						? $(target_id)
						: $button.parent().find('textarea')
					$textarea
						.val(base.insertAtChars($textarea, shortcode))
						.trigger('change')
				}

				$modal.addClass('hidden')
			})

			$modal.on('click', '.spf--repeat-button', function (e) {
				e.preventDefault()

				var $repeatable = $modal.find('.spf--repeatable')
				var $new_clone = $cloned.spf_clone()
				var $remove_btn = $new_clone.find('.spf-repeat-remove')

				var $appended = $new_clone.appendTo($repeatable)

				$new_clone.find('.spf-fields').spf_reload_script()

				SPF.helper.name_nested_replace(
					$modal.find('.spf--repeat-shortcode'),
					sc_group
				)

				$remove_btn.on('click', function () {
					$new_clone.remove()

					SPF.helper.name_nested_replace(
						$modal.find('.spf--repeat-shortcode'),
						sc_group
					)
				})
			})

			$modal.on('click', '.spf-modal-close, .spf-modal-overlay', function () {
				$modal.addClass('hidden')
			})
		})
	}

	//
	// WP Color Picker
	//
	if (typeof Color === 'function') {
		Color.prototype.toString = function () {
			if (this._alpha < 1) {
				return this.toCSS('rgba', this._alpha).replace(/\s+/g, '')
			}

			var hex = parseInt(this._color, 10).toString(16)

			if (this.error) {
				return ''
			}

			if (hex.length < 6) {
				for (var i = 6 - hex.length - 1; i >= 0; i--) {
					hex = '0' + hex
				}
			}

			return '#' + hex
		}
	}

	SPF.funcs.parse_color = function (color) {
		var value = color.replace(/\s+/g, ''),
			trans =
				value.indexOf('rgba') !== -1
					? parseFloat(value.replace(/^.*,(.+)\)/, '$1') * 100)
					: 100,
			rgba = trans < 100 ? true : false

		return { value: value, transparent: trans, rgba: rgba }
	}

	$.fn.spf_color = function () {
		return this.each(function () {
			var $input = $(this),
				picker_color = SPF.funcs.parse_color($input.val()),
				palette_color = window.spf_vars.color_palette.length
					? window.spf_vars.color_palette
					: true,
				$container

			// Destroy and Reinit
			if ($input.hasClass('wp-color-picker')) {
				$input
					.closest('.wp-picker-container')
					.after($input)
					.remove()
			}

			$input.wpColorPicker({
				palettes: palette_color,
				change: function (event, ui) {
					var ui_color_value = ui.color.toString()

					$container.removeClass('spf--transparent-active')
					$container
						.find('.spf--transparent-offset')
						.css('background-color', ui_color_value)
					$input.val(ui_color_value).trigger('change')
				},
				create: function () {
					$container = $input.closest('.wp-picker-container')

					var a8cIris = $input.data('a8cIris'),
						$transparent_wrap = $(
							'<div class="spf--transparent-wrap">' +
							'<div class="spf--transparent-slider"></div>' +
							'<div class="spf--transparent-offset"></div>' +
							'<div class="spf--transparent-text"></div>' +
							'<div class="spf--transparent-button">transparent <i class="fa fa-toggle-off"></i></div>' +
							'</div>'
						).appendTo($container.find('.wp-picker-holder')),
						$transparent_slider = $transparent_wrap.find(
							'.spf--transparent-slider'
						),
						$transparent_text = $transparent_wrap.find(
							'.spf--transparent-text'
						),
						$transparent_offset = $transparent_wrap.find(
							'.spf--transparent-offset'
						),
						$transparent_button = $transparent_wrap.find(
							'.spf--transparent-button'
						)

					if ($input.val() === 'transparent') {
						$container.addClass('spf--transparent-active')
					}

					$transparent_button.on('click', function () {
						if ($input.val() !== 'transparent') {
							$input
								.val('transparent')
								.trigger('change')
								.removeClass('iris-error')
							$container.addClass('spf--transparent-active')
						} else {
							$input.val(a8cIris._color.toString()).trigger('change')
							$container.removeClass('spf--transparent-active')
						}
					})

					$transparent_slider.slider({
						value: picker_color.transparent,
						step: 1,
						min: 0,
						max: 100,
						slide: function (event, ui) {
							var slide_value = parseFloat(ui.value / 100)
							a8cIris._color._alpha = slide_value
							$input.wpColorPicker('color', a8cIris._color.toString())
							$transparent_text.text(
								slide_value === 1 || slide_value === 0 ? '' : slide_value
							)
						},
						create: function () {
							var slide_value = parseFloat(picker_color.transparent / 100),
								text_value = slide_value < 1 ? slide_value : ''

							$transparent_text.text(text_value)
							$transparent_offset.css('background-color', picker_color.value)

							$container.on('click', '.wp-picker-clear', function () {
								a8cIris._color._alpha = 1
								$transparent_text.text('')
								$transparent_slider.slider('option', 'value', 100)
								$container.removeClass('spf--transparent-active')
								$input.trigger('change')
							})

							$container.on('click', '.wp-picker-default', function () {
								var default_color = SPF.funcs.parse_color(
									$input.data('default-color')
								),
									default_value = parseFloat(default_color.transparent / 100),
									default_text = default_value < 1 ? default_value : ''

								a8cIris._color._alpha = default_value
								$transparent_text.text(default_text)
								$transparent_slider.slider(
									'option',
									'value',
									default_color.transparent
								)

								if (default_color.value === 'transparent') {
									$input.removeClass('iris-error')
									$container.addClass('spf--transparent-active')
								}
							})
						}
					})
				}
			})
		})
	}

	//
	// ChosenJS
	//
	$.fn.spf_chosen = function () {
		return this.each(function () {
			var $this = $(this),
				$inited = $this.parent().find('.chosen-container'),
				is_sortable = $this.hasClass('spf-chosen-sortable') || false,
				is_ajax = $this.hasClass('spf-chosen-ajax') || false,
				is_multiple = $this.attr('multiple') || false,
				set_width = is_multiple ? '100%' : 'auto',
				set_options = $.extend(
					{
						allow_single_deselect: true,
						disable_search_threshold: 10,
						width: set_width,
						no_results_text: window.spf_vars.i18n.no_results_text
					},
					$this.data('chosen-settings')
				)

			if ($inited.length) {
				$inited.remove()
			}

			// Chosen ajax
			if (is_ajax) {
				var set_ajax_options = $.extend(
					{
						data: {
							type: 'post',
							nonce: ''
						},
						allow_single_deselect: true,
						disable_search_threshold: -1,
						width: '100%',
						min_length: 3,
						type_delay: 500,
						typing_text: window.spf_vars.i18n.typing_text,
						searching_text: window.spf_vars.i18n.searching_text,
						no_results_text: window.spf_vars.i18n.no_results_text
					},
					$this.data('chosen-settings')
				)

				$this.SPFAjaxChosen(set_ajax_options)
			} else {
				$this.chosen(set_options)
			}

			// Chosen keep options order
			if (is_multiple) {
				var $hidden_select = $this.parent().find('.spf-hide-select')
				var $hidden_value = $hidden_select.val() || []

				$this.on('change', function (obj, result) {
					if (result && result.selected) {
						$hidden_select.append(
							'<option value="' +
							result.selected +
							'" selected="selected">' +
							result.selected +
							'</option>'
						)
					} else if (result && result.deselected) {
						$hidden_select
							.find('option[value="' + result.deselected + '"]')
							.remove()
					}

					// Force customize refresh
					if (
						window.wp.customize !== undefined &&
						$hidden_select.children().length === 0 &&
						$hidden_select.data('customize-setting-link')
					) {
						window.wp.customize
							.control($hidden_select.data('customize-setting-link'))
							.setting.set('')
					}

					$hidden_select.trigger('change')
				})
				SPF

				// Chosen order abstract
				$this.CSFChosenOrder($hidden_value, true)
			}

			// Chosen sortable
			if (is_sortable) {
				var $chosen_container = $this.parent().find('.chosen-container')
				var $chosen_choices = $chosen_container.find('.chosen-choices')

				$chosen_choices.bind('mousedown', function (event) {
					if ($(event.target).is('span')) {
						event.stopPropagation()
					}
				})

				$chosen_choices.sortable({
					items: 'li:not(.search-field)',
					helper: 'orginal',
					cursor: 'move',
					placeholder: 'search-choice-placeholder',
					start: function (e, ui) {
						ui.placeholder.width(ui.item.innerWidth())
						ui.placeholder.height(ui.item.innerHeight())
					},
					update: function (e, ui) {
						var select_options = ''
						var chosen_object = $this.data('chosen')
						var $prev_select = $this.parent().find('.spf-hide-select')

						$chosen_choices.find('.search-choice-close').each(function () {
							var option_array_index = $(this).data('option-array-index')
							$.each(chosen_object.results_data, function (index, data) {
								if (data.array_index === option_array_index) {
									select_options +=
										'<option value="' +
										data.value +
										'" selected>' +
										data.value +
										'</option>'
								}
							})
						})

						$prev_select.children().remove()
						$prev_select.append(select_options)
						$prev_select.trigger('change')
					}
				})
			}
		})
	}

	//
	// Helper Checkbox Checker
	//
	$.fn.spf_checkbox = function () {
		return this.each(function () {
			var $this = $(this),
				$input = $this.find('.spf--input'),
				$checkbox = $this.find('.spf--checkbox')

			$checkbox.on('click', function () {
				$input.val(Number($checkbox.prop('checked'))).trigger('change')
			})
		})
	}

	//
	// Siblings
	//
	$.fn.spf_siblings = function () {
		return this.each(function () {
			var $this = $(this),
				$siblings = $this.find('.spf--sibling:not(.spf-pro-only)'),
				multiple = $this.data('multiple') || false

			$siblings.on('click', function () {
				var $sibling = $(this)

				if (multiple) {
					if ($sibling.hasClass('spf--active')) {
						$sibling.removeClass('spf--active')
						$sibling
							.find('input')
							.prop('checked', false)
							.trigger('change')
					} else {
						$sibling.addClass('spf--active')
						$sibling
							.find('input')
							.prop('checked', true)
							.trigger('change')
					}
				} else {
					$this.find('input').prop('checked', false)
					$sibling
						.find('input')
						.prop('checked', true)
						.trigger('change')
					$sibling
						.addClass('spf--active')
						.siblings()
						.removeClass('spf--active')
				}
			})
		})
	}

	//
	// Help Tooltip
	//
	$.fn.spf_help = function () {
		return this.each(function () {
			var $this = $(this);
			var $tooltip;
			var $class = '';
			$this.on({
				mouseenter: function () {
					// this class add with the support tooltip.
					if ($this.find('.spf-support').length > 0) {
						$class = 'support-tooltip';
					}
					$tooltip = $('<div class="spf-tooltip ' + $class + '"></div>')
						.html($this.find('.spf-help-text').html())
						.appendTo('body');

					var offset_left = SPF.vars.is_rtl
						? $this.offset().left - $tooltip.outerWidth()
						: $this.offset().left + 24;
					var $top = $this.offset().top - ($tooltip.outerHeight() / 2 - 14);
					// this block used for support tooltip.
					if ($this.find('.spf-support').length > 0) {
						$top = $this.offset().top + 48;
						offset_left = $this.offset().left - 242;
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
				},
			});
			// Event delegation to handle tooltip removal when the cursor leaves the tooltip itself.
			$('body').on('mouseleave', '.spf-tooltip', function () {
				if ($tooltip !== undefined) {
					$tooltip.remove();
				}
			});
		});
	}

	//
	// Window on resize
	//
	SPF.vars.$window
		.on(
			'resize spf.resize',
			SPF.helper.debounce(function (event) {
				var window_width =
					navigator.userAgent.indexOf('AppleWebKit/') > -1
						? SPF.vars.$window.width()
						: window.innerWidth

				if (window_width <= 782 && !SPF.vars.onloaded) {
					$('.spf-section').spf_reload_script()
					SPF.vars.onloaded = true
				}
			}, 200)
		)
		.trigger('spf.resize')

	//
	// Widgets Framework
	//
	$.fn.spf_widgets = function () {
		if (this.length) {
			$(document).on('widget-added widget-updated', function (event, $widget) {
				$widget.find('.spf-fields').spf_reload_script()
			})

			$('.widgets-sortables, .control-section-sidebar').on(
				'sortstop',
				function (event, ui) {
					ui.item.find('.spf-fields').spf_reload_script_retry()
				}
			)

			$(document).on('click', '.widget-top', function (event) {
				$(this)
					.parent()
					.find('.spf-fields')
					.spf_reload_script()
			})
		}
	}

	//
	// Nav Menu Options Framework
	//
	$.fn.spf_nav_menu = function () {
		return this.each(function () {
			var $navmenu = $(this)

			$navmenu.on('click', 'a.item-edit', function () {
				$(this)
					.closest('li.menu-item')
					.find('.spf-fields')
					.spf_reload_script()
			})

			$navmenu.on('sortstop', function (event, ui) {
				ui.item.find('.spf-fields').spf_reload_script_retry()
			})
		})
	}

	//
	// Retry Plugins
	//
	$.fn.spf_reload_script_retry = function () {
		return this.each(function () {
			var $this = $(this)

			if ($this.data('inited')) {
			}
		})
	}

	//
	// Reload Plugins
	//
	$.fn.spf_reload_script = function (options) {
		var settings = $.extend(
			{
				dependency: true
			},
			options
		)

		return this.each(function () {
			var $this = $(this)

			// Avoid for conflicts
			if (!$this.data('inited')) {
				// Field plugins
				$this.children('.spf-field-code_editor').spf_field_code_editor()
				$this.children('.spf-field-fieldset').spf_field_fieldset()
				$this.children('.spf-field-group').spf_field_group()
				$this.children('.spf-field-icon').spf_field_icon()
			
				$this.children('.spf-field-repeater').spf_field_repeater()
				$this.children('.spf-field-slider').spf_field_slider()
				$this.children('.spf-field-sortable').spf_field_sortable()
				$this.children('.spf-field-sorter').spf_field_sorter()
				$this.children('.spf-field-spinner').spf_field_spinner()
				$this.children('.spf-field-switcher').spf_field_switcher()
				$this.children('.spf-field-tabbed').spf_field_tabbed()
				$this.children('.spf-field-upload').spf_field_upload()

				// Field colors
				$this
					.children('.spf-field-box_shadow')
					.find('.spf-color')
					.spf_color() // ShapedPlugin.
				$this
					.children('.spf-field-border')
					.find('.spf-color')
					.spf_color()
				$this
					.children('.spf-field-background')
					.find('.spf-color')
					.spf_color()
				$this
					.children('.spf-field-color')
					.find('.spf-color')
					.spf_color()
				$this
					.children('.spf-field-color_group')
					.find('.spf-color')
					.spf_color()
				$this
					.children('.spf-field-link_color')
					.find('.spf-color')
					.spf_color()
				$this
					.children('.spf-field-typography')
					.find('.spf-color')
					.spf_color()

				// Field chosenjs
				$this
					.children('.spf-field-select')
					.find('.spf-chosen')
					.spf_chosen()

				// Field Checkbox
				$this
					.children('.spf-field-checkbox')
					.find('.spf-checkbox')
					.spf_checkbox()

				// Field Siblings
				$this
					.children('.spf-field-button_set')
					.find('.spf-siblings')
					.spf_siblings()
				$this
					.children('.spf-field-image_select')
					.find('.spf-siblings')
					.spf_siblings()
				$this
					.children('.spf-field-palette')
					.find('.spf-siblings')
					.spf_siblings()

				// Help Tooptip
				$this
					.children('.spf-field')
					.find('.spf-help')
					.spf_help()
				// Help Tooptip
				$('.sptp-admin-bg').find('.spf-support-area').spf_help();

				if (settings.dependency) {
					$this.spf_dependency()
				}

				$this.data('inited', true)

				$(document).trigger('spf-reload-script', $this)
			}
		})
	}
	$('.spf-clean-cache').on('click', function (e) {
		e.preventDefault()
		if (SPF.vars.is_confirm) {
			//base.notificationOverlay()

			window.wp.ajax
				.post('spf_clean_transient', {
					nonce: $('#spf_options_nonce_sptp_settings').val()
				})
				.done(function (response) {
					//  window.location.reload(true)
					alert('data cleaned')
				})
				.fail(function (response) {
					alert('data failed to clean')
					alert(response.error)
				})
		}
	})
	//
	// Document ready and run scripts
	//
	$(document).ready(function () {
		$('.spf-save').spf_save()
		$('.spf-options').spf_options()
		$('.spf-sticky-header').spf_sticky()
		$('.spf-nav-options').spf_nav_options()
		$('.spf-nav-metabox').spf_nav_metabox()
		$('.spf-taxonomy').spf_taxonomy()
		$('.spf-page-templates').spf_page_templates()
		$('.spf-post-formats').spf_post_formats()
		$('.spf-shortcode').spf_shortcode()
		$('.spf-search').spf_search()
		$('.spf-confirm').spf_confirm()
		$('.spf-expand-all').spf_expand_all()
		$('.spf-onload').spf_reload_script()
		$('.widget').spf_widgets()
		$('#menu-to-edit').spf_nav_menu()
		$(".spf-field-button_clean.cache_remove .spf--sibling.spf--button").on("click", function (e) {
			e.preventDefault();
			if (SPF.vars.is_confirm) {
				window.wp.ajax
					.post("sptp_clean_transient", {
						nonce: $("#spf_options_nonce_sptp_settings").val(),
					})
					.done(function (response) {
						alert("Cache cleaned");
					})
					.fail(function (response) {
						alert("Cache failed to clean");
						alert(response.error);
					});
			}
		});
	})
	function isValidJSONString(str) {
		try {
			JSON.parse(str);
		} catch (e) {
			return false;
		}
		return true;
	}
	// Wp Team export.
	var $export_type = $('.sptp_what_export').find('input:checked').val();
	$('.sptp_what_export').on('change', function () {
		$export_type = $(this).find('input:checked').val();
	});

	$('.sptp_export .spf--button').on('click', function (event) {
		event.preventDefault();

		var $shortcode_ids = $('.sptp_post_ids select').val();
		var $ex_nonce = $('#spf_options_nonce_sptp_tools').val();
		if ($export_type === 'selected_shortcodes' && $shortcode_ids !== undefined && $shortcode_ids.length) {
			var data = {
				action: 'SPT_export_shortcodes',
				sptp_ids: $shortcode_ids,
				nonce: $ex_nonce,
			}
		} else if ($export_type === 'all_shortcodes') {
			var data = {
				action: 'SPT_export_shortcodes',
				sptp_ids: 'all_shortcodes',
				nonce: $ex_nonce,
			}
		} else if ($export_type === 'all_members') {
			var data = {
				action: 'SPT_export_shortcodes',
				sptp_ids: 'all_members',
				nonce: $ex_nonce,
			}
		} else {
			$('.spf-form-result.spf-form-success').text('No group selected.').show();
			setTimeout(function () {
				$('.spf-form-result.spf-form-success').hide().text('');
			}, 3000);

			return;
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
				var blob = new Blob([json], { type: 'application/json' });
				var link = document.createElement('a');
				var current_time = $.now();
				link.href = window.URL.createObjectURL(blob);
				link.download = "wp-team-export-" + current_time + ".json";
				link.click();
				$('.spf-form-result.spf-form-success').text('Exported successfully!').show();
				setTimeout(function () {
					$('.spf-form-result.spf-form-success').hide().text('');
					$('.sptp_post_ids select').val('').trigger('chosen:updated');
				}, 3000);
			}
		});
	});
	// Wp Team import.
	$('.sptp_import button.import').on('click', function (event) {
		var $this = $(this),
			button_label = $(this).text();
		event.preventDefault();
		var sptp_shortcodes = $('#import').prop('files')[0];

		if ($('#import').val() != '') {
			$this.append('<span class="sptp-page-loading-spinner"><i class="fa fa-spinner" aria-hidden="true"></i></span>');
			$this.css('opacity', '0.7');
			var $im_nonce = $('#spf_options_nonce_sptp_tools').val();
			var reader = new FileReader();
			reader.readAsText(sptp_shortcodes);
			reader.onload = function (event) {
				var jsonObj = JSON.stringify(event.target.result);
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						shortcode: jsonObj,
						action: 'SPT_import_shortcodes',
						nonce: $im_nonce,
					},
					success: function (resp) {
						$this.html(button_label);
						$('.spf-form-result.spf-form-success').text('Imported successfully!').show();
						setTimeout(function () {
							$('.spf-form-result.spf-form-success').hide().text('');
							$('#import').val('');
							if (resp.data === 'sptp_member') {
								window.location.replace($('#sptp_member_link_redirect').attr('href'));
							} else {
								window.location.replace($('#sptp_shortcode_link_redirect').attr('href'));
							}
						}, 2000);
					},
					error: function (error) {
						$('#import').val('');
						$this.html(button_label).css('opacity', '1');
						$('.spf-form-result.spf-form-success').addClass('error')
							.text('Something went wrong, please try again!').show();
						setTimeout(function () {
							$('.spf-form-result.spf-form-success').hide().text('').removeClass('error');
						}, 2000);
					}
				});
			}
		} else {
			$('.spf-form-result.spf-form-success').text('No exported json file chosen.').show();
			setTimeout(function () {
				$('.spf-form-result.spf-form-success').hide().text('');
			}, 3000);
		}
	});

	// Live Preview script for Wp-Team.
	var preview_box = $('#sp__team-preview-box');
	var preview_display = $('#sptp_preview_display').hide();
	$(document).on('click', '#sp__team-show-preview:contains(Hide)', function (e) {
		e.preventDefault();
		var _this = $(this);
		_this.html('<i class="fa fa-eye" aria-hidden="true"></i> Show Preview');
		preview_box.html('');
		preview_display.hide();
	});
	$(document).on('click', '#sp__team-show-preview:not(:contains(Hide))', function (e) {
		e.preventDefault();
		var previewJS = sptp_admin.previewJS;
		var _data = $('form#post').serialize();
		var _this = $(this);
		var data = {
			action: 'sptp_preview_meta_box',
			data: _data,
			ajax_nonce: $('#spf_metabox_noncesptp_preview_display').val()
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
					$(document).on('keyup change', '.post-type-sptp_generator', function (e) {
						e.preventDefault();
						_this.html('<i class="fa fa-refresh" aria-hidden="true"></i> Update Preview');
					});
					$("html, body").animate({ scrollTop: preview_display.offset().top - 50 }, "slow");
				});
			}
		})
	});


	$(document).on('keyup change', '.sptp_member_page_team_settings #spf-form', function (e) {
		e.preventDefault();
		var $button = $(this).find('.spf-save');
		$button.css({ "background-color": "#00C263", "pointer-events": "initial" }).val('Save Settings');
	});
	$('.sptp_member_page_team_settings .spf-save').on('click', function (e) {
		e.preventDefault();
		$(this).css({ "background-color": "#C5C5C6", "pointer-events": "none" }).val('Changes Saved');
	})

})(jQuery, window, document)
