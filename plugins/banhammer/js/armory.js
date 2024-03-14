/* 
	Banhammer Armory.js v1.1
	Jeff Starr @ Monzilla Media
	https://monzillamedia.com/
	https://plugin-planet.com/
*/

(function($) {
	
	$(document).ready(function() {
		
		banhammer_armory();
		
		document.ondblclick = function() {
			if (window.getSelection) window.getSelection().removeAllRanges();
			else if (document.selection) document.selection.empty();
		}
		
		$('.banhammer-tools').hide();
		$(document).on('click', '.banhammer-tools-link', function(e) {
			e.preventDefault();
			$('.banhammer-tools').slideToggle(300);
			$(this).blur();
		});
		
		$(document).on('click', '.banhammer-fx-link', function(e) {
			e.preventDefault();
			if (banhammer.vars.fx === 0) {
				banhammer.vars.fx = 1;
				$(this).text($(this).data('fx-on'));
			} else {
				banhammer.vars.fx = 0;
				$(this).text($(this).data('fx-off'));
			}
			banhammer.vars.type = 'items';
			banhammer_armory();
			$(this).blur();
		});
		
		$(document).on('mouseenter', '.banhammer-row', function(e) {
			$(this).find('.banhammer-select-target').addClass('banhammer-visible');
		}); 
		$(document).on('mouseleave', '.banhammer-row', function(e) {
			$(this).find('.banhammer-select-target').removeClass('banhammer-visible');
		});
		
		$(document).on('click', '.banhammer-toggle-link', function(e) {
			e.preventDefault();
			if (banhammer.vars.toggle == 2) {
				$(this).text($(this).data('view-adv'));
				$('.banhammer-data').slideUp();
				banhammer.vars.toggle = 1;
				$('.banhammer-request a').each(function() {
					var request = $(this).data('request');
					var req = request.substring(0, 50) +'...';
					if (request.length > 50) $(this).text(req).fadeIn(300);
				});
			} else {
				$(this).text($(this).data('view-bsc'));
				$('.banhammer-data').slideDown();
				banhammer.vars.toggle = 2;
				$('.banhammer-request a').each(function() {
					var request = $(this).data('request');
					$(this).text(request).fadeIn(300);
				});
			}
			banhammer.vars.type = 'items';
			banhammer_armory();
			$(this).blur();
		});
		
		$(document).on('dblclick', '.banhammer-row', function(e) {
			e.preventDefault();
			var data = $(this).find('.banhammer-data');
			var current = $(this).find('.banhammer-request a');
			var request = current.data('request');
			var req = request.substring(0, 50) +'...';
			if (data.is(':visible')) {
				if (request.length > 50) current.text(req).fadeIn(300);
			} else {
				current.text(request).fadeIn(300);
			}
			data.slideToggle(300);
		});
		
		$(document).on('click', '.banhammer-hostlookup-link', function(e) {
			e.preventDefault();
			var id = $(this).data('id');
			var ip = $(this).data('ip');
			$('.banhammer-hostlookup-id-'+ id).html(banhammer.vars.dots);
			banhammer_aux(id, ip);
			$(this).blur();
		});
		
		$(document).on('click', '.banhammer-addvisit-link, .banhammer-reload-link', function(e) {
			e.preventDefault();
			banhammer_clear();
			banhammer.vars.type = 'init';
			if ($(this).hasClass('banhammer-addvisit-link')) banhammer.vars.type = 'add';
			banhammer_armory();
			$(this).blur();
		});
		
		$(document).on('click', '.banhammer-reload-current', function(e) {
			e.preventDefault();
			banhammer.vars.type = 'init';
			banhammer_armory();
			$(this).blur();
		});
		
		$(document).on('click', '.banhammer-delete-link', function(e) {
			e.preventDefault();
			$(this).blur();
			var button_names = {};
			$('.banhammer-dialog').dialog('destroy');
			button_names[banhammer_delete_items_true] = function() {
				banhammer_clear();
				$(this).dialog('close');
				banhammer.vars.type = 'delete';
				var del = $('.banhammer-fx-delete')[0];
				var fx = banhammer.vars.fx;
				if (fx === 1) del.play();
				banhammer_armory();
			}
			button_names[banhammer_delete_items_false] = function() { 
				$(this).dialog('close');
			}
			var dialog = '<div class="banhammer-dialog">'+ banhammer_delete_items_message +'</div>';
			$(dialog).dialog({ 
				title:   banhammer_delete_items_title, 
				buttons: button_names, 
				modal:   true, 
				width:   350
			});
		});
		
		$(document).on('change', '.banhammer-select-all', function() {
			$('.banhammer-id').prop('checked', $(this).prop('checked'));
		});
		$(document).on('change', '.banhammer-id', function() {
			if ($('.banhammer-id:checkbox:not(:checked)').length == 0) {
				var checked = true;
			} else {
				var checked = false;
			}
			$('.banhammer-select-all').prop('checked', checked);
		});
		$('.banhammer-action-bulk').on('click', function(e) {
			e.preventDefault();
			var bulk = $('.banhammer-select-bulk').val();
			var items = [];
			$('.banhammer-id:checked').each(function() {
				if (bulk == 'delete') banhammer.vars.count = banhammer.vars.count - 1;
				items.push($(this).val());
			});
			if (banhammer.vars.offset == banhammer.vars.count) {
				banhammer.vars.offset = Math.abs(banhammer.vars.offset - banhammer.vars.limit);
			}
			var jump = Math.ceil(banhammer.vars.offset / banhammer.vars.limit) + 1;
			
			var del = $('.banhammer-fx-delete')[0];
			if (banhammer.vars.fx === 1 && bulk == 'delete') del.play();
			
			banhammer.vars.jump  = jump;
			banhammer.vars.bulk  = bulk;
			banhammer.vars.items = items;
			banhammer.vars.type  = 'bulk';
			banhammer_armory();
			$(this).blur();
		});
		
		$(document).on('click', '.banhammer-action-ban, .banhammer-action-warn', function(e) {
			e.preventDefault();
			$('.jBox-Tooltip').hide();
			var fx = banhammer.vars.fx;
			var target = $(this).siblings('.banhammer-select-target').val();
			var action = $(this).data('action');
			var bulk   = action +'-'+ target;
			var id     = $(this).data('id');
			var items = [];
			items.push(id);
			banhammer.vars.bulk   = bulk;
			banhammer.vars.items  = items;
			banhammer.vars.type   = 'bulk';
			var ban = $('.banhammer-fx-ban')[0];
			var warn = $('.banhammer-fx-warn')[0];
			if (fx === 1) {
				if      (action == 'ban')  ban.play();
				else if (action == 'warn') warn.play();
			}
			banhammer_armory();
			$(this).blur();
		});
		
		$('.banhammer-page-next').on('click', function(e) {
			e.preventDefault();
			if (banhammer.vars.offset < banhammer.vars.count) {
				banhammer.vars.offset = banhammer.vars.offset + banhammer.vars.limit;
				banhammer.vars.jump = banhammer.vars.jump + 1;
				banhammer.vars.type = 'next';
				banhammer_armory();
			}
			$(this).blur();
		});
		
		$('.banhammer-page-prev').on('click', function(e) {
			e.preventDefault();
			if (banhammer.vars.offset > 0) {
				banhammer.vars.offset = banhammer.vars.offset - banhammer.vars.limit;
				banhammer.vars.jump = banhammer.vars.jump - 1;
				banhammer.vars.type = 'prev';
				banhammer_armory();
			}
			$(this).blur();
		});
		
		$('.banhammer-page-jump').on('keypress', function(e) {
			var code = e.keyCode || e.which;
			if (code == 13) {
				e.preventDefault();
				var jump = parseInt($(this).val());
				if (jump <= 0) jump = 1;
				if (jump > banhammer.vars.pages) jump = banhammer.vars.pages;
				banhammer.vars.offset = (jump - 1) * banhammer.vars.limit;
				banhammer.vars.jump = jump;
				banhammer.vars.type = 'jump';
				banhammer_armory();
			}
		});
		
		$('.banhammer-hover-info').hide();
		$('.banhammer-page-items').hover(function() {
				$('.banhammer-hover-info').css('display', 'inline-block');
			}, function() {
				$('.banhammer-hover-info').css('display', 'none');
		});
		
		$('.banhammer-page-items').val(banhammer.vars.limit).on('keypress', function(e) {
			var code = e.keyCode || e.which;
			if (code == 13) {
				e.preventDefault();
				var limit_new = parseInt($(this).val());
				var limit_old = $(this).data('limit');
				if (limit_new <= 0) {
					limit_new = limit_old;
					$(this).val(limit_old);
				}
				if (limit_new > 10) {
					$('.banhammer-dialog').dialog('destroy');
					var button_names = {};
					button_names[banhammer_number_rows_true] = function() {
						banhammer.vars.limit  = 10;
						banhammer.vars.offset = 0;
						banhammer.vars.jump   = 1;
						banhammer.vars.type   = 'items';
						banhammer_armory();
						$(this).dialog('close');
						$('.banhammer-page-items').val(banhammer.vars.limit);
					}
					button_names[banhammer_number_rows_false] = function() { 
						$(this).dialog('close');
						$('.banhammer-page-items').val(limit_old);
					}
					$('<div class="banhammer-dialog">'+ banhammer_number_rows_message +'</div>').dialog({
						title: banhammer_number_rows_title,
						buttons: button_names,
						modal: true,
						width: 350
					});
				} else {
					banhammer.vars.limit  = limit_new;
					banhammer.vars.offset = 0;
					banhammer.vars.jump   = 1;
					banhammer.vars.type   = 'items';
					banhammer_armory();
				}
			}
		});
		
		$('.banhammer-action-search, .banhammer-select-filter').on('keypress change', function(e) {
			var go = false;
			var code = e.keyCode || e.which;
			if (code == 13 && e.type == 'keypress' && this.className == 'banhammer-action-search') {
				e.preventDefault();
				var search = $(this).val();
				var filter = $('.banhammer-select-filter').val();
				go = true;
			} else if (e.type == 'change' && this.className == 'banhammer-select-filter') {
				e.preventDefault();
				var search = $('.banhammer-action-search').val();
				var filter = $(this).val();
				if (search) go = true;
			}
			if (go == true) {
				if (!filter) filter = '';
				banhammer.vars.search = search;
				banhammer.vars.filter = filter;
				banhammer.vars.offset = 0;
				banhammer.vars.count  = 0;
				banhammer.vars.jump   = 1;
				banhammer.vars.type   = 'search';
				banhammer_armory();
			}
		});
		
		$('.banhammer-select-sort, .banhammer-select-order').on('change', function(e) {
			e.preventDefault();
			var sort = $('.banhammer-select-sort').val();
			var order = $('.banhammer-select-order').val();
			if (!sort) sort = 'id';
			if (!order) order = 'desc';
			banhammer.vars.sort  = sort;
			banhammer.vars.order = order;
			banhammer.vars.type  = 'sort';
			banhammer_armory();
			$(this).blur();
		});
		
		$('.banhammer-select-status').on('change', function(e) {
			e.preventDefault();
			var status = $(this).val();
			if (!status) status = 'all';
			banhammer.vars.offset = 0;
			banhammer.vars.count  = 0;
			banhammer.vars.jump   = 1;
			banhammer.vars.type   = 'status';
			banhammer.vars.status = status;
			banhammer_armory();
			$(this).blur();
		});
		
	});
	
	function banhammer_aux(id, ip) {
		if (banhammer.vars.xhr != null) {
			banhammer.vars.xhr.abort();
			banhammer.vars.xhr = null;
		}
		banhammer.vars.xhr = $.ajax({
			type: 'POST',
			url:   ajaxurl,
			data: {
				action: 'banhammer_aux',
				nonce:  banhammer.vars.nonce,
				id:     id,
				ip:     ip
			},
			success: function(data) {
				$('.banhammer-hostlookup-id-'+ id).html(data);
			}
		});
	}
	
	function banhammer_armory() {
		banhammer_prepare();
		if (banhammer.vars.xhr != null) {
			banhammer.vars.xhr.abort();
			banhammer.vars.xhr = null;
		}
		banhammer.vars.xhr = $.ajax({
			type: 'POST',
			url:   ajaxurl,
			data: {
				action: 'banhammer_armory',
				nonce:  banhammer.vars.nonce,
				items:  banhammer.vars.items,
				type:   banhammer.vars.type,
				bulk:   banhammer.vars.bulk,
				sort:   banhammer.vars.sort,
				order:  banhammer.vars.order,
				search: banhammer.vars.search,
				filter: banhammer.vars.filter,
				status: banhammer.vars.status,
				jump:   banhammer.vars.jump,
				count:  banhammer.vars.count,
				limit:  banhammer.vars.limit,
				offset: banhammer.vars.offset,
				toggle: banhammer.vars.toggle,
				fx:     banhammer.vars.fx
			},
			success: function(data) {
				banhammer_response(data);
				banhammer_ui();
			}
		});
	}
	
	function banhammer_prepare() {
		$('.banhammer-armory').show();
		$('.banhammer-response').empty();
		$('.banhammer-loading').show();
		var tools = $('.banhammer-tools');
		if (tools.is(':visible')) tools.show();
		else tools.hide();
		if (banhammer.vars.type != 'bulk' && banhammer.vars.search != '') banhammer.vars.type = 'search';
	}
	
	function banhammer_response(data) {
		var temp  = $(data);
		var div   = temp.filter('.banhammer-count-data');
		var count = parseInt(div.data('count'));
		banhammer.vars.count = count;
		$('.banhammer-loading').hide();
		if (banhammer.vars.type == 'delete') $('.banhammer-tools').hide();
		$('.banhammer-count').html(div.html());
		$('.banhammer-response').empty();
		if (count > 0) {
			response = temp.not('.banhammer-count-data');
			response.filter('.banhammer-row').each(function(i) {
				$(this).hide().appendTo($('.banhammer-response')).delay((i++) * 50).fadeTo(100, 1);
				if (banhammer.vars.toggle == 2) {
					$('.banhammer-toggle-link').text($('.banhammer-toggle-link').data('view-bsc'));
					$(this).find('.banhammer-data').show();
					$('.banhammer-request a').each(function() {
						var request = $(this).data('request');
						$(this).text(request).fadeIn(300);
					});
				} else {
					$('.banhammer-toggle-link').text($('.banhammer-toggle-link').data('view-adv'));
					$(this).find('.banhammer-data').hide();
					$('.banhammer-request a').each(function() {
						var request = $(this).data('request');
						var req = request.substring(0, 50) +'...';
						if (request.length > 50) $(this).text(req).fadeIn(300);
					});
				}
				$(this).find('.banhammer-select-target').removeClass('banhammer-visible');
				var date = $(this).find('.banhammer-date').html().replace(/@/gi, '<span class="banhammer-at">@</span>');
				$(this).find('.banhammer-date').html(date);
			});
			var height = $('.banhammer-response').height();
			$('.banhammer-loading').css('min-height', height +'px');
		} else {
			div.hide().appendTo($('.banhammer-response')).delay(50).fadeTo(100, 1);
			$('.banhammer-loading').css('min-height', '80px');
		}
		var fx = $('.banhammer-fx-link');
		if (banhammer.vars.fx === 0) fx.text(fx.data('fx-off'));
		else fx.text(fx.data('fx-on'));
		new jBox('Tooltip', {
			getTitle: 'data-title',
			attach: '.banhammer-armory *[title]',
			animation: { open: 'move:bottom', close: 'move:bottom' },
			adjustPosition: true,
			adjustTracker: true,
			fade: 100
		});
	}
	
	function banhammer_ui() {
		banhammer.vars.pages = Math.ceil(banhammer.vars.count / banhammer.vars.limit);
		if (banhammer.vars.pages === 0) banhammer.vars.pages = 1;
		if ((banhammer.vars.count - banhammer.vars.offset) <= banhammer.vars.limit) {
			$('.banhammer-page-next').prop('disabled', true);
		} else {
			$('.banhammer-page-next').prop('disabled', false);
		}
		if (banhammer.vars.offset > 0) {
			$('.banhammer-page-prev').prop('disabled', false);
		} else {
			$('.banhammer-page-prev').prop('disabled', true);
		}
		if (banhammer.vars.count === 0) {
			$('.banhammer-paging').hide();
		} else {
			$('.banhammer-paging').show();
		}
		$('.banhammer-page-items').data('limit', banhammer.vars.limit);
		$('.banhammer-select-bulk').val('');
		$('.banhammer-select-sort').val(banhammer.vars.sort);
		$('.banhammer-select-order').val(banhammer.vars.order);
		$('.banhammer-action-search').val(banhammer.vars.search);
		$('.banhammer-select-filter').val(banhammer.vars.filter);
		$('.banhammer-select-status').val(banhammer.vars.status);
		$('.banhammer-page-jump').val(banhammer.vars.jump);
		$('.banhammer-page-total').html(banhammer.vars.pages);
		$('.banhammer-select-all').prop('checked', false);
	}
	
	function banhammer_clear() {
		banhammer.vars.count  = 0;
		banhammer.vars.items  = [];
		banhammer.vars.type   = 'init';
		banhammer.vars.bulk   = '';
		banhammer.vars.sort   = 'id';
		banhammer.vars.order  = 'desc';
		banhammer.vars.search = '';
		banhammer.vars.filter = 'all';
		banhammer.vars.status = 'all';
		banhammer.vars.jump   = 1;
		banhammer.vars.offset = 0;
		$('.banhammer-action-search').val('');
	}
	
})(jQuery);