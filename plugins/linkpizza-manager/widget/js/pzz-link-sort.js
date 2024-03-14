jQuery(document).ready(function($) {
	if(!$('body').hasClass('widgets_access')){
		pzzSetupList($);
		$('.pzz-edit-item').addClass('toggled-off');
		pzzSetupHandlers($);
	}
	
	$(document).ajaxSuccess(function() {
		pzzSetupList($);
		$('.pzz-edit-item').addClass('toggled-off');
	});
});

function pzzSetupList($){
	$( ".pzz-list" ).sortable({
		items: '.list-item',
		opacity: 0.6,
		cursor: 'n-resize',
		axis: 'y',
		handle: '.moving-handle',
		placeholder: 'sortable-placeholder',
		start: function (event, ui) {
			ui.placeholder.height(ui.helper.height());
		},
		update: function() {
			updateOrder($(this));
		}
	});
	
	$( ".pzz-list .moving-handle" ).disableSelection();
}


// All Event handlers
function pzzSetupHandlers($){
	$("body").on('click.pzz','.pzz-delete',function() {
		$(this).parent().parent().fadeOut(500,function(){
			var pzz = $(this).parents(".widget-content");
			$(this).remove();
			pzz.find('.order').val(pzz.find('.pzz-list').sortable('toArray'));
			var num = pzz.find(".pzz-list .list-item").length;
			var amount = pzz.find(".amount");
			amount.val(num);
		});
	});
	
	$("body").on('click.pzz','.pzz-add',function() {
		var pzz = $(this).parent().parent();
		var num = pzz.find('.pzz-list .list-item').length + 1;
		
		pzz.find('.amount').val(num);
		
		var item = pzz.find('.pzz-list .list-item:last-child').clone();
		var item_id = item.attr('id');
		item.attr('id',increment_last_num(item_id));

		$('.toggled-off',item).removeClass('toggled-off');
		$('.number',item).html(num);
		$('.item-title',item).html('');
		
		$('label',item).each(function() {
			var for_val = $(this).attr('for');
			$(this).attr('for',increment_last_num(for_val));
		});
		
		$('input',item).each(function() {
			var id_val = $(this).attr('id');
			var name_val = $(this).attr('name');
			$(this).attr('id',increment_last_num(id_val));
			$(this).attr('name',increment_last_num(name_val));
			if($(':checked',this)){
			   $(this).removeAttr('checked');
			}
			$(this).val('');
		});
		
		pzz.find('.pzz-list').append(item);
		pzz.find('.order').val(pzz.find('.pzz-list').sortable('toArray'));
	});
	
	$('body').on('click.pzz','.moving-handle', function() {
		$(this).parent().find('.pzz-edit-item').slideToggle(200);
	} );
}

function increment_last_num(v) {
    return v.replace(/[0-9]+(?!.*[0-9])/, function(match) {
        return parseInt(match, 10)+1;
    });
}

function updateOrder(self){
	var pzz = self.parents(".widget-content");
	pzz.find('.order').val(pzz.find('.pzz-list').sortable('toArray'));
}