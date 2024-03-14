/* 
	Banhammer Tower.js v1.1
	Jeff Starr @ Monzilla Media
	https://monzillamedia.com/
	https://plugin-planet.com/
*/

(function($) {
	
	$(document).ready(function() {
		
		banhammer_tower();
		
		$(document).on('click', '.banhammer-reload-link, .banhammer-reload-current', function(e) {
			e.preventDefault();
			banhammer_reset();
			banhammer_tower();
			$(this).blur();
		});
		
		$(document).on('click', '.banhammer-example-link', function(e) {
			e.preventDefault();
			banhammer.vars.demo = 1;
			banhammer_tower();
			banhammer_reset();
			$(this).blur();
		});
		
		$(document).on('change', '.banhammer-select-all', function() {
			$('.banhammer-tower-id').prop('checked', $(this).prop('checked'));
		});
		$(document).on('change', '.banhammer-tower-id', function() {
			if ($('.banhammer-tower-id:checkbox:not(:checked)').length == 0) {
				var checked = true;
			} else {
				var checked = false;
			}
			$('.banhammer-select-all').prop('checked', checked);
		});
		
		$('.banhammer-select-bulk').on('change', function(e) {
			e.preventDefault();
			var bulk  = $('.banhammer-select-bulk').val();
			var items = [];
			$('.banhammer-tower-id:checked').each(function() {
				items.push($(this).val());
			});
			banhammer.vars.bulk  = bulk;
			banhammer.vars.items = items;
			if (bulk == 'delete') banhammer_delete();
			else banhammer_tower();
			$(this).blur();
		});
		
		$(document).on('click', '.banhammer-action', function(e) {
			e.preventDefault();
			$('.jBox-Tooltip').hide();
			var fx = banhammer.vars.fx;
			var id = $(this).parent().siblings('.banhammer-checkbox').children('.banhammer-tower-id').val();
			var bulk = $(this).data('action');
			var items = [];
			items.push(id);
			banhammer.vars.bulk  = bulk;
			banhammer.vars.items = items;
			var ban     = $('.banhammer-fx-ban')[0];
			var warn    = $('.banhammer-fx-warn')[0];
			var restore = $('.banhammer-fx-restore')[0];
			if (fx === 1) {
				if      (bulk == 'ban')     ban.play();
				else if (bulk == 'warn')    warn.play();
				else if (bulk == 'restore') restore.play();
			}
			if (bulk == 'delete') banhammer_delete();
			else banhammer_tower();
			$(this).blur();
		});
		
		$('.banhammer-select-sort').on('change', function(e) {
			e.preventDefault();
			var sort = $(this).val();
			if (!sort) sort = 'all';
			banhammer.vars.sort = sort;
			banhammer_tower();
			$(this).blur();
		});
		
		$('.banhammer-select-type').on('change', function(e) {
			e.preventDefault();
			var type = $(this).val();
			banhammer.vars.type = type;
			banhammer_tower();
			$(this).blur();
		});
		
	});
	
	function banhammer_tower() {
		
		$('.banhammer-tower').show();
		$('.banhammer-response').empty();
		$('.banhammer-loading').show();
		
		$.post(ajaxurl, {
			action: 'banhammer_tower',
			nonce:  banhammer.vars.nonce,
			sort:   banhammer.vars.sort,
			type:   banhammer.vars.type,
			bulk:   banhammer.vars.bulk,
			items:  banhammer.vars.items,
			demo:   banhammer.vars.demo
		}, function(data) {
			
			var temp  = $(data).filter('.banhammer-count-data');
			var count = temp.data('count');
			var total = temp.data('total');
			var text1 = temp.data('text1');
			var text2 = temp.data('text2');
			
			$('.banhammer-count').html(text1 +' '+ count +' '+ text2 +' '+ total);
			
			$('.banhammer-loading').hide();
			$('.banhammer-response').empty().html(data).fadeIn();
			$('.banhammer-select-all').prop('checked', false);
			$('.banhammer-select-bulk').val('');
			
			var height = '80';
			if (total > 0) height = $('.banhammer-response').height();
			$('.banhammer-loading').css('min-height', height +'px');
			
			new jBox('Tooltip', {
				getTitle: 'data-title',
				attach: '.banhammer-tower *[title]',
				animation: { open: 'move:bottom', close: 'move:bottom' },
				adjustPosition: true,
				adjustTracker: true,
				fade: 100
			});
		});
	}
	
	function banhammer_reset() {
		banhammer.vars.sort  = '',
		banhammer.vars.type  = '',
		banhammer.vars.bulk  = '',
		banhammer.vars.items = [];
		banhammer.vars.demo  = 0;
		
		$('.banhammer-select-all').prop('checked', false);
		$('.banhammer-select-bulk').val('');
		$('.banhammer-select-sort').val('');
		$('.banhammer-select-type').val('');
	}
	
	function banhammer_delete() {
		$('.banhammer-dialog').dialog('destroy');
		var link = this;
		var button_names = {};
		button_names[banhammer_delete_items_true]  = function() { 
			$(this).dialog('close'); 
			var del = $('.banhammer-fx-delete')[0];
			var fx = banhammer.vars.fx;
			if (fx === 1) del.play();
			banhammer_tower(); 
		}
		button_names[banhammer_delete_items_false] = function() { 
			$(this).dialog('close'); 
			$('.banhammer-select-bulk').val(''); 
		}
		$('<div class="banhammer-dialog">'+ banhammer_delete_item_message +'</div>').dialog({
			title: banhammer_delete_item_title,
			buttons: button_names,
			modal: true,
			width: 350
		});
	}
	
})(jQuery);
