(function($) {
	var methods = {
		init: function(options) {
			var o = $.extend({
				items: 1,
				itemsOnPage: 1,
				pages: 0,
				displayedPages: 5,
				edges: 2,
				currentPage: 0,
				useAnchors: true,
				hrefTextPrefix: '',
				hrefTextSuffix: '',
				prevText: false,
				nextText: false,
				ellipseText: '&hellip;',
				ellipsePageSet: true,
				cssStyle: 'light-theme',
				listStyle: '',
				labelMap: [],
				selectOnClick: true,
				nextAtFront: false,
				invertPageOrder: false,
				useStartEdge: true,
				useEndEdge: true,
				funcv: false,
				onPageClick: function(pageNumber, event) {
				},
				onInit: function() {
				}
			}, options || {});
			var self = this;
			o.pages = o.pages ? o.pages : Math.ceil(o.items / o.itemsOnPage) ? Math.ceil(o.items / o.itemsOnPage) : 1;
			if (o.currentPage) o.currentPage = o.currentPage - 1;
			else o.currentPage = !o.invertPageOrder ? 0 : o.pages - 1;
			o.halfDisplayed = o.displayedPages / 2;
			this.each(function() {
				self.addClass(o.cssStyle + ' simple-pagination').data('pagination', o);
				methods._draw.call(self);
			});
			o.onInit();
			return this;
		},
		selectPage: function(page) {
			methods._selectPage.call(this, page - 1);
			return this;
		},
		prevPage: function() {
			var o = this.data('pagination');
			if (!o.invertPageOrder) {
				if (o.currentPage > 0) {
					methods._selectPage.call(this, o.currentPage - 1);
				}
			} else {
				if (o.currentPage < o.pages - 1) {
					methods._selectPage.call(this, o.currentPage + 1);
				}
			}
			return this;
		},
		nextPage: function() {
			var o = this.data('pagination');
			if (!o.invertPageOrder) {
				if (o.currentPage < o.pages - 1) {
					methods._selectPage.call(this, o.currentPage + 1);
				}
			} else {
				if (o.currentPage > 0) {
					methods._selectPage.call(this, o.currentPage - 1);
				}
			}
			return this;
		},
		getPagesCount: function() {
			return this.data('pagination').pages;
		},
		setPagesCount: function(count) {
			this.data('pagination').pages = count;
		},
		getCurrentPage: function() {
			return this.data('pagination').currentPage + 1;
		},
		destroy: function() {
			this.empty();
			return this;
		},
		drawPage: function(page) {
			var o = this.data('pagination');
			o.currentPage = page - 1;
			this.data('pagination', o);
			methods._draw.call(this);
			return this;
		},
		redraw: function() {
			methods._draw.call(this);
			return this;
		},
		disable: function() {
			var o = this.data('pagination');
			o.disabled = true;
			this.data('pagination', o);
			methods._draw.call(this);
			return this;
		},
		enable: function() {
			var o = this.data('pagination');
			o.disabled = false;
			this.data('pagination', o);
			methods._draw.call(this);
			return this;
		},
		updateItems: function(newItems) {
			var o = this.data('pagination');
			o.items = newItems;
			o.pages = methods._getPages(o);
			this.data('pagination', o);
			methods._draw.call(this);
		},
		updateItemsOnPage: function(itemsOnPage) {
			var o = this.data('pagination');
			o.itemsOnPage = itemsOnPage;
			o.pages = methods._getPages(o);
			this.data('pagination', o);
			methods._selectPage.call(this, 0);
			return this;
		},
		romanize(num) {
			if (isNaN(num)) return NaN;
			var digits = String(+num).split(""),
				key = ["", "C", "CC", "CCC", "CD", "D", "DC", "DCC", "DCCC", "CM", "", "X", "XX", "XXX", "XL", "L", "LX", "LXX", "LXXX", "XC", "", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX"],
				roman = "",
				i = 3;
			while (i--) roman = (key[+digits.pop() + (i * 10)] || "") + roman;
			return Array(+digits.join("") + 1).join("M") + roman;
		},
		pagerom2() {
			$('.sing_index').click(function() {
				var indx_page = $(this).find('.indx_page').text();
				 indx_page= indx_page.split(',');
				indx_page =  indx_page[0]
				var page = $('.pages div.page[data-page-number="' + indx_page + '"]');
				if (page[1]) {
					if ($(this).parent().hasClass('rom')) {
						var pagey = $(page[0]).data('index');
					} else {
						var pagey = $(page[1]).data('index');
					}
				} else {
					var pagey = $(page[0]).data('index')
				}
				if (single_page) {
					var paget = parseInt(pagey) - 1;
				} else {
					var paget = Math.floor(pagey / 2) - 1;
				}
				$('.clickcount').val(paget).change();
				var current_clickcount = parseInt($('.clickcount').val()) - 1;
				if (current_clickcount > paget) {
					$('.navigation .prev').trigger('click')
				} else {
					$('.navigation .next').trigger('click')
				}
			});
			$('.pager-both ul li span').click(function() {
				var target_pag = $(this).data('page');
				var page = $('.pages div.page[data-page-number="' + target_pag + '"]');
				if (page[1]) {
					if ($(this).parent().hasClass('rom')) {
						var pagey = $(page[0]).data('index');
					} else {
						var pagey = $(page[1]).data('index');
					}
				} else {
					var pagey = $(page[0]).data('index')
				}
				if (single_page) {
					var paget = parseInt(pagey) - 1;
				} else {
					var paget = Math.floor(pagey / 2) - 1;
				}
				$('.clickcount').val(paget).change();
				var current_clickcount = parseInt($('.clickcount').val()) - 1;
				if (current_clickcount > paget) {
					$('.navigation .prev').trigger('click')
				} else {
					$('.navigation .next').trigger('click')
				}
			});
		},
		getItemsOnPage: function() {
			return this.data('pagination').itemsOnPage;
		},
		_draw: function() {
			var o = this.data('pagination'),
				interval = methods._getInterval(o),
				i,
				tagName;
			methods.destroy.call(this);
			tagName = (typeof this.prop === 'function') ? this.prop('tagName') : this.attr('tagName');
			var $panel = tagName === 'UL' ? this : $('<ul' + (o.listStyle ? ' class="' + o.listStyle + '"' : '') + '></ul>').appendTo(this);
			if (o.prevText) {
				methods._appendItem.call(this, !o.invertPageOrder ? o.currentPage - 1 : o.currentPage + 1, {
					text: o.prevText,
					classes: 'prev'
				});
			}
			if (o.nextText && o.nextAtFront) {
				methods._appendItem.call(this, !o.invertPageOrder ? o.currentPage + 1 : o.currentPage - 1, {
					text: o.nextText,
					classes: 'next'
				});
			}
			if (!o.invertPageOrder) {
				if (interval.start > 0 && o.edges > 0) {
					if (o.useStartEdge) {
						var end = Math.min(o.edges, interval.start);
						for (i = 0; i < end; i++) {
							methods._appendItem.call(this, i);
						}
					}
					if (o.edges < interval.start && (interval.start - o.edges != 1)) {
						$panel.append('<li class="disabled"><span class="ellipse">' + o.ellipseText + '</span></li>');
					} else if (interval.start - o.edges == 1) {
						methods._appendItem.call(this, o.edges);
					}
				}
			} else {
				if (interval.end < o.pages && o.edges > 0) {
					if (o.useStartEdge) {
						var begin = Math.max(o.pages - o.edges, interval.end);
						for (i = o.pages - 1; i >= begin; i--) {
							methods._appendItem.call(this, i);
						}
					}
					if (o.pages - o.edges > interval.end && (o.pages - o.edges - interval.end != 1)) {
						$panel.append('<li class="disabled"><span class="ellipse">' + o.ellipseText + '</span></li>');
					} else if (o.pages - o.edges - interval.end == 1) {
						methods._appendItem.call(this, interval.end);
					}
				}
			}
			if (!o.invertPageOrder) {
				for (i = interval.start; i < interval.end; i++) {
					methods._appendItem.call(this, i);
				}
			} else {
				for (i = interval.end - 1; i >= interval.start; i--) {
					methods._appendItem.call(this, i);
				}
			}
			if (!o.invertPageOrder) {
				if (interval.end < o.pages && o.edges > 0) {
					if (o.pages - o.edges > interval.end && (o.pages - o.edges - interval.end != 1)) {
						$panel.append('<li class="disabled"><span class="ellipse">' + o.ellipseText + '</span></li>');
					} else if (o.pages - o.edges - interval.end == 1) {
						methods._appendItem.call(this, interval.end);
					}
					if (o.useEndEdge) {
						var begin = Math.max(o.pages - o.edges, interval.end);
						for (i = begin; i < o.pages; i++) {
							methods._appendItem.call(this, i);
						}
					}
				}
			} else {
				if (interval.start > 0 && o.edges > 0) {
					if (o.edges < interval.start && (interval.start - o.edges != 1)) {
						$panel.append('<li class="disabled"><span class="ellipse">' + o.ellipseText + '</span></li>');
					} else if (interval.start - o.edges == 1) {
						methods._appendItem.call(this, o.edges);
					}
					if (o.useEndEdge) {
						var end = Math.min(o.edges, interval.start);
						for (i = end - 1; i >= 0; i--) {
							methods._appendItem.call(this, i);
						}
					}
				}
			}
			if (o.nextText && !o.nextAtFront) {
				methods._appendItem.call(this, !o.invertPageOrder ? o.currentPage + 1 : o.currentPage - 1, {
					text: o.nextText,
					classes: 'next'
				});
			}
			if (o.ellipsePageSet && !o.disabled) {
				methods._ellipseClick.call(this, $panel);
			}
		},
		_getPages: function(o) {
			var pages = Math.ceil(o.items / o.itemsOnPage);
			return pages || 1;
		},
		_getInterval: function(o) {
			return {
				start: Math.ceil(o.currentPage > o.halfDisplayed ? Math.max(Math.min(o.currentPage - o.halfDisplayed, (o.pages - o.displayedPages)), 0) : 0),
				end: Math.ceil(o.currentPage > o.halfDisplayed ? Math.min(o.currentPage + o.halfDisplayed, o.pages) : Math.min(o.displayedPages, o.pages))
			};
		},
		_appendItem: function(pageIndex, opts) {
			var self = this,
				options, $link, o = self.data('pagination'),
				$linkWrapper = $('<li class=""></li>'),
				$ul = self.find('ul');
			pageIndex = pageIndex < 0 ? 0 : (pageIndex < o.pages ? pageIndex : o.pages - 1);
			options = {
				text: pageIndex + 1,
				classes: ''
			};
			if (o.labelMap.length && o.labelMap[pageIndex]) {
				options.text = o.labelMap[pageIndex];
			}
			options = $.extend(options, opts || {});
			if (o.funcv === 'roman') {
				$linkWrapper.addClass('rom')
			} 
			if (pageIndex == o.currentPage || o.disabled) {
				if (o.disabled || options.classes === 'prev' || options.classes === 'next') {
					$linkWrapper.addClass('disabled');
				} else {
					$linkWrapper.addClass('active');
				}
				if (o.funcv === 'roman') {
					var optex = methods.romanize(options.text);
				} else {
					var optex = options.text;
				}
				$link = $('<span class="current" data-page="' + (pageIndex + 1) + '">' + optex + '</span>');
			} else {
				if (o.funcv === 'roman') {
					var optex = methods.romanize(options.text);
				} else {
					var optex = options.text;
				}
				if (o.useAnchors) {
					$link = $('<span class="" data-page="' + (pageIndex + 1) + '">' + optex + '</span>');
				} else {
					$link = $('<span class="" >' + optex + '</span>');
				}
				$link.click(function(event) {
					return methods._selectPage.call(self, pageIndex, event);
				});
			}
			if (options.classes) {
				$link.addClass(options.classes);
			}
			$linkWrapper.append($link);
			if ($ul.length) {
				$ul.append($linkWrapper);
			} else {
				self.append($linkWrapper);
			}
		},
		_selectPage: function(pageIndex, event) {
			var o = this.data('pagination');
			o.currentPage = pageIndex;
			if (o.selectOnClick) {
				methods._draw.call(this);
			}
			return o.onPageClick(pageIndex + 1, event);
		},
		_ellipseClick: function($panel) {
			var self = this,
				o = this.data('pagination'),
				$ellip = $panel.find('.ellipse');
			$ellip.addClass('clickable').parent().removeClass('disabled');
			$ellip.click(function(event) {
				if (!o.disable) {
					var $this = $(this),
						val = (parseInt($this.parent().prev().text(), 10) || 0) + 1;
					$this.html('<input type="number" min="1" max="' + o.pages + '" step="1" value="' + val + '">').find('input').focus().click(function(event) {
						event.stopPropagation();
					}).keyup(function(event) {
						var val = $(this).val();
						if (event.which === 13 && val !== '') {
							if ((val > 0) && (val <= o.pages)) methods._selectPage.call(self, val - 1);
						} else if (event.which === 27) {
							$ellip.empty().html(o.ellipseText);
						}
					}).bind('blur', function(event) {
						var val = $(this).val();
						if (val !== '') {
							methods._selectPage.call(self, val - 1);
						}
						$ellip.empty().html(o.ellipseText);
						return false;
					});
				}
				return false;
			});
			methods.pagerom2()
		}
	};
	$.fn.pagination = function(method) {
		if (methods[method] && method.charAt(0) != '_') {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('Method ' + method + ' does not exist on jQuery.pagination');
		}
	};
})(jQuery);
(function($) {
	'use strict';
	$('a[href*="#"]').not('[href="#"]').not('[href="#0"]').click(function(event) {
		if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
			if (target.length) {
				event.preventDefault();
				$('html, body').animate({
					scrollTop: target.offset().top
				}, 1000, function() {
					var $target = $(target);
					$target.focus();
					if ($target.is(":focus")) { 
						return false;
					} else {
						$target.attr('tabindex', '-1'); 
						$target.focus(); 
					};
				});
			}
		}
	});
	$(window).on('load', function() {
		jQuery.expr[':'].contains = function(a, i, m) {
			return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
		};
		if(typeof sections !== 'undefined'){
			var pageCount = 0;
			var indexc = 0;
			Object.keys(sections).map(function(objectKeySs, index) {
				var section = sections[objectKeySs];
				Object.keys(section).map(function(objectKeyS, index) {
					var elements = section[objectKeyS].Elements;
					

					Object.keys(elements).map(function(objectKeyE, index) {
						var element = elements[objectKeyE];
						if (element.ID){
						if (element.Meta.web_print[0] === 'true' || element.Meta.web_print[0] === 'on') {
							var innerHtml = element.Content[0]; 
							var rx = new RegExp('<(div|/div)[^>]{0,}>','g');
							innerHtml = innerHtml.replace(rx ,'');
							var textArray = innerHtml.match(/(<ul[^>]*>(?:.|\n)*?<\/ul>)|(<table[^>]*>(?:.|\n)*?<\/table>)|<figure\s+[^>]*>|<img\s+[^>]*>|<p[^>]*>(?:.|\n)*?<\/p>|<h.*?>[^<>]*<\/h.*?>|(([^\s]+)(?![^<]*>|[^<>]*<\/))/g);
							
							console.log(textArray)
							
							var elPage = [];
							createPage(section[objectKeyS], element, indexc);
							indexc++;
							for (var i = 0; i < textArray.length; i++) {
								var success = appendToLastPage(textArray[i]);
								if (!elPage[pageCount]) {
									elPage[pageCount] = '';
								}
								elPage[pageCount] += textArray[i] + " ";
								if (!success) {
									if ($(textArray[i]).prop('tagName') === 'P') {
										var textArrayp = [$(textArray[i]).html()];
									} else {
										var textArrayp = [textArray[i]];
									}
									if (textArrayp) {
										var textArrayp = textArrayp[0].match(/(<li[^>]*>(?:.|\n)*?<\/li>)|(<i[^>]*>(?:.|\n)*?<\/i>)|(<table[^>]*>(?:.|\n)*?<\/table>)|<img\s+[^>]*>|(<a[^>]*>(?:.|\n)*?<\/a>)|<h.*?>[^<>]*<\/h.*?>|(<span[^>]*>(?:.|\n)*?<\/span>)|(([^\s]+)(?![^<]*>|[^<>]*<\/))/g);
										appendToLastPage('<p>');
										for (var j = 0; j < textArrayp.length; j++) {
											var success2 = appendToLastPage(textArrayp[j]);
											if (!success2) {
												appendToLastPage('<p>');
												createPage(section[objectKeyS], element, indexc);
												pageCount++;
												indexc++;
												if (!elPage[pageCount]) {
													elPage[pageCount] = '';
												}
												elPage[pageCount] += textArrayp[j] + " ";
												appendToLastPage(textArrayp[j]);
											}
										}
										appendToLastPage('<p>');
									}
								}
							}
							element.Pages = elPage
						}
					}
					});
				});
			});
		}
		function createPage(section, element, indexc) {
			var page = document.createElement("div");
			page.setAttribute("data-index", indexc);
			page.setAttribute("class", "page");
			page.setAttribute("id", element.Name.replace(" ", "-").toLowerCase());
			page.setAttribute("data-section-name", section.Name);
			page.setAttribute("data-id", element.ID);
			page.setAttribute("data-element-name", element.Name);
			page.setAttribute("data-pagination-location-x", element.Meta.pagination_location_x ? element.Meta.pagination_location_x[0] : 'left');
			page.setAttribute("data-pagination-location-y", element.Meta.pagination_location_y ? element.Meta.pagination_location_y[0] : 'top');
			page.setAttribute("data-page-numbering", element.Meta.numbering[0]);
			var page_inner = document.createElement("div");
			page_inner.setAttribute("class", "page-inner");
			page.appendChild(page_inner);
			console.log(page[0])
			document.getElementsByClassName("pages")[0].appendChild(page);
		}
		function appendToLastPage(word) {
			var page = document.getElementsByClassName("page")[document.getElementsByClassName("page").length - 1]; // gets the last page
			var page = page.getElementsByClassName('page-inner')[0];
			var pageText = page.innerHTML;
			page.innerHTML += word + " ";
			if (page.offsetHeight < page.scrollHeight) {
				page.innerHTML = pageText;
				return false;
			} else {
				return true;
			}
		}
		var vv = 1;
		$('div.page[data-section-name="Body Matter"], div.page[data-section-name="End Matter"]').each(function(index, ele) {
			if ($(this).attr('data-page-numbering') === 'on' || $(this).attr('data-page-numbering') === 'true') {
				$(this).attr('data-page-number', vv);
				vv++;
			}
		})
		var vx = 1;
		$('div.page[data-section-name="Front Matter"], div.page[data-section-name="Cover Matter"]').each(function(index, ele) {
			if ($(this).attr('data-page-numbering') === 'on' || $(this).attr('data-page-numbering') === 'true') {
				$(this).attr('data-page-number', vx);
				vx++;
			}
		})
		setTimeout(function() {
			var arr = [];
			$('a[data-target-page]').each(function(indextoc, eleindextoc) {
				var get_tar = $(this).data('target-page');
				var base = $(this);
				$('div.page[data-element-name="' + get_tar + '"]').each(function(indexpag, elepage) {
					var leng = $('div.page[data-element-name="' + get_tar + '"]').length - 1;
					var start = $('div.page[data-element-name="' + get_tar + '"]').eq(0).attr('data-page-number');
					var end = $('div.page[data-element-name="' + get_tar + '"]').eq(leng).attr('data-page-number');
					if (start && end) {
						var section = $('div.page[data-element-name="' + get_tar + '"]').eq(0).attr('data-section-name');
						if (section === 'Front Matter' || section ==='Cover Matter') {
							var start = romanize(start);
							var end = romanize(end);
						}
						base.find('.toc_page_n').html('  <span class="start">' + start + '</span>-<span class="end">' + end + '</span>');
					} else {
						base.find('.toc_page_n').html('');
					}
				});
				$.post(bp_Vars.ajaxurl, {
					action: 'update_element_page_unmber',
					'id': base.data('elid'),
					'start': base.find('.toc_page_n .start').text(),
					'end': base.find('.toc_page_n .end').text(),
				}).done(function(data) {}).fail(function(data) {});
			})
			$('div.page').each(function(ind,el) {
				$(this).prepend('<div class="book-page-header"></div>');
				$(this).append('<div class="book-page-footer"></div>');
				if ($(this).attr('data-page-number')) {
					var page_number = $(this).attr('data-page-number');
					var section = $(this).attr('data-section-name');
					if (section === 'Front Matter' || section ==='Cover Matter') {
						var page_number = romanize(page_number);
					}
					if(book_meta.pagination_location_x){
						var pagination_x = book_meta.pagination_location_x[0];
					} else {
						var pagination_x = '';
					}
					if(book_meta.pagination_location_y && book_meta.pagination_location_y[0]=='top'){
						$(this).find('.book-page-header').prepend('<div class="page_number page_number_'+pagination_x+'">' + page_number + '</div>')
					} else {
						$(this).find('.book-page-footer').append('<div class="page_number page_number_'+pagination_x+'">' + page_number + '</div>')
					}
				}
			})
			if (single_page) {
				var pgWid = 6;
				var pageLeng = $('div.page').length;
			} else {
				var pgWid = 13;
				var pageLeng = $('div.page').length / 2;
			}
			$('.pages_cont .pages').css({
				'width': pageLeng * parseInt(pgWid) + 'In'
			})
			$('.navigation .prev').click(function() {
				var clickcount = $('.clickcount').val();
				if (clickcount > 0) {
					$('.clickcount').val(parseInt(clickcount) - parseInt(1))
					$('.pages_cont .pages').css({
						'left': '-' + (parseInt(clickcount) - parseInt(1)) * parseInt(pgWid) + 'In'
					})
				}
			})
			$('.navigation .next').click(function() {
				var clickcount = $('.clickcount').val();
				if (clickcount < pageLeng - 1) {
					$('.clickcount').val(parseInt(clickcount) + parseInt(1))
					$('.pages_cont .pages').css({
						'left': '-' + (parseInt(clickcount) + parseInt(1)) * parseInt(pgWid) + 'In'
					})
				}
			})
			$('.pages_cont .pages').on('swiperight',function(){
				var clickcount = $('.clickcount').val();
				if (clickcount > 0 || clickcount==0 || clickcount==-1) {
					$('.clickcount').val(parseInt(clickcount) - parseInt(1))
					$('.pages_cont .pages').css({
						'left': '-' + (parseInt(clickcount) - parseInt(1)) * parseInt(pgWid) + 'In'
					})
				}
			})
			$('.pages_cont .pages').on('swipeleft',function(){
				var clickcount = $('.clickcount').val();
				if (clickcount > 0 || clickcount==0 || clickcount==-1)  {
					$('.clickcount').val(parseInt(clickcount) + parseInt(1))
					$('.pages_cont .pages').css({
						'left': '-' + (parseInt(clickcount) + parseInt(1)) * parseInt(pgWid) + 'In'
					})
				}
			})
		}, 2000)
		setTimeout(function() {
			$('div.page').each(function(index, ele) {
				var html = $(this).html();
				$(this).html(wpautop(html))
				var footnote = html.match(/<span class="footnote">(.*?)<\/span>/g);
				if (footnote) {
					var html = '';
					for (var i = 1; i < (footnote.length + 1); i++) {
						html += i + '. ' + footnote[i - 1];
					}
					$(this).append('<p class="foo_footnote" style="font-size:70%">' + html + '</p>');
					$(this).find('.footnote_i').each(function(indxf, elef) {
						$(this).html('<sup>' + (indxf + 1) + '</sup>')
					})
				}
			});
		}, 2500)
		setTimeout(function() {
			$('.page#index .index_index').each((function(index, ele) {
				var text = $(this).text();
				var indexs = $('.index:contains(' + text + ')');
				var pagearr = [];
				for (var i = 0; i < indexs.length; i++) {
					var page = $(indexs[i]).parents('.page').data('page-number');
					if(page){
						pagearr.push(page)
					}
				}
				var pagearr_unique = pagearr.filter(onlyUnique);
				var pagenum = pagearr_unique.join();
				$('.index_index:contains(' + text + ')').parent().find('.indx_page').text(pagenum);
			}))
		}, 3000)
		setTimeout(function() {
			$('[data-element-name="Table of Contents"] a').click(function() {
				var target_pag = $(this).attr('data-target-page');
				var page = $('.pages div.page[data-element-name="' + target_pag + '"]');
				var page = page.index()
				var current_clickcount = $('.clickcount').val();
				if (single_page) {
					var paget = parseInt(page) - 1;
				} else {
					var paget = Math.floor(page / 2) - 1;
				}
				$('.clickcount').val(paget).change();
				if (current_clickcount > paget) {
					$('.navigation .prev').trigger('click')
				} else {
					$('.navigation .next').trigger('click')
				}
			})
			$(function() {
				var pagefront = $('.pages div.page[data-section-name="Front Matter"][data-page-number]').length;
				$('.navigation .pager-front').pagination({
					items: pagefront,
					itemsOnPage: 1,
					cssStyle: 'light-theme',
					funcv: 'roman'
				});
				var pagebody = $('.pages div.page[data-section-name="Body Matter"][data-page-number], .pages div.page[data-section-name="End Matter"][data-page-number]').length;
				$('.navigation .pager-body').pagination({
					items: pagebody,
					itemsOnPage: 1,
					cssStyle: 'light-theme',
				});
			});
		}, 3000)
		function romanize(num) {
			if (isNaN(num)) return NaN;
			var digits = String(+num).split(""),
				key = ["", "C", "CC", "CCC", "CD", "D", "DC", "DCC", "DCCC", "CM", "", "X", "XX", "XXX", "XL", "L", "LX", "LXX", "LXXX", "XC", "", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX"],
				roman = "",
				i = 3;
			while (i--) roman = (key[+digits.pop() + (i * 10)] || "") + roman;
			return Array(+digits.join("") + 1).join("M") + roman;
		}
		function onlyUnique(value, index, self) {
			return self.indexOf(value) === index;
		}
		function _autop_newline_preservation_helper(matches) {
			return matches[0].replace("\n", "<WPPreserveNewline />");
		}
		function wpautop(pee, br) {
			if (typeof(br) === 'undefined') {
				br = false;
			}
			var pre_tags = {};
			if (pee.trim() === '') {
				return '';
			}
			pee = pee + "\n"; 
			if (pee.indexOf('<pre') > -1) {
				var pee_parts = pee.split('</pre>');
				var last_pee = pee_parts.pop();
				pee = '';
				pee_parts.forEach(function(pee_part, index) {
					var start = pee_part.indexOf('<pre');
					if (start === -1) {
						pee += pee_part;
						return;
					}
					var name = "<pre wp-pre-tag-" + index + "></pre>";
					pre_tags[name] = pee_part.substr(start) + '</pre>';
					pee += pee_part.substr(0, start) + name;
				});
				pee += last_pee;
			}
			pee = pee.replace(/<br \/>\s*<br \/>/, "\n\n");
			var allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';
			pee = pee.replace(new RegExp('(<' + allblocks + '[^>]*>)', 'gmi'), "\n$1");
			pee = pee.replace(new RegExp('(</' + allblocks + '>)', 'gmi'), "$1\n\n");
			pee = pee.replace(/\r\n|\r/, "\n"); 
			if (pee.indexOf('<option') > -1) {
				pee = pee.replace(/\s*<option'/gmi, '<option');
				pee = pee.replace(/<\/option>\s*/gmi, '</option>');
			}
			if (pee.indexOf('</object>') > -1) {
				pee = pee.replace(/(<object[^>]*>)\s*/gmi, '$1');
				pee = pee.replace(/\s*<\/object>/gmi, '</object>');
				pee = pee.replace(/\s*(<\/?(?:param|embed)[^>]*>)\s*/gmi, '$1');
			}
			if (pee.indexOf('<source') > -1 || pee.indexOf('<track') > -1) {
				pee = pee.replace(/([<\[](?:audio|video)[^>\]]*[>\]])\s*/gmi, '$1');
				pee = pee.replace(/\s*([<\[]\/(?:audio|video)[>\]])/gmi, '$1');
				pee = pee.replace(/\s*(<(?:source|track)[^>]*>)\s*/gmi, '$1');
			}
			pee = pee.replace(/\n\n+/gmi, "\n\n"); 
			var pees = pee.split(/\n\s*\n/);
			pee = '';
			pees.forEach(function(tinkle) {
				pee += '<p>' + tinkle.replace(/^\s+|\s+$/g, '') + "</p>\n";
			});
			pee = pee.replace(/<p>\s*<\/p>/gmi, ''); 
			pee = pee.replace(/<p>([^<]+)<\/(div|address|form)>/gmi, "<p>$1</p></$2>");
			pee = pee.replace(new RegExp('<p>\s*(</?' + allblocks + '[^>]*>)\s*</p>', 'gmi'), "$1", pee); 
			pee = pee.replace(/<p>(<li.+?)<\/p>/gmi, "$1"); 
			pee = pee.replace(/<p><blockquote([^>]*)>/gmi, "<blockquote$1><p>");
			pee = pee.replace(/<\/blockquote><\/p>/gmi, '</p></blockquote>');
			pee = pee.replace(new RegExp('<p>\s*(</?' + allblocks + '[^>]*>)', 'gmi'), "$1");
			pee = pee.replace(new RegExp('(</?' + allblocks + '[^>]*>)\s*</p>', 'gmi'), "$1");
			if (br) {
				pee = pee.replace(/<(script|style)(?:.|\n)*?<\/\\1>/gmi, _autop_newline_preservation_helper); 
				pee = pee.replace(/(<br \/>)?\s*\n/gmi, "<br />\n"); 
				pee = pee.replace('<WPPreserveNewline />', "\n");
			}
			pee = pee.replace(new RegExp('(</?' + allblocks + '[^>]*>)\s*<br />', 'gmi'), "$1");
			pee = pee.replace(/<br \/>(\s*<\/?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)/gmi, '$1');
			pee = pee.replace(/\n<\/p>$/gmi, '</p>');
			if (Object.keys(pre_tags).length) {
				pee = pee.replace(new RegExp(Object.keys(pre_tags).join('|'), "gi"), function(matched) {
					return pre_tags[matched];
				});
			}
			return pee;
		}
		$('#cover-image, #back-cover').find('img').each(function() {
			var src = $(this).attr('src');
			$(this).parents('.page[data-id]').css({
				'background-image': 'url(' + src + ')',
				'background-size': 'contain',
				'background-position': 'center center',
				'background-repeat': 'no-repeat'
			});
		})
	})
})(jQuery);