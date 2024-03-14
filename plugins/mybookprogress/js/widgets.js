
/*---------------------------------------------------------*/
/* Widget Editor                                           */
/*---------------------------------------------------------*/

jQuery(document).ready(function() {
	function mbp_update_book_selector(element) {
		element = jQuery(element);
		var selector = element.parents('.widget-content').find('.mbp-widget-book-selector');
		if(element.val() == 'manual') {
			selector.show();
		} else {
			selector.hide();
		}
	}
	jQuery('body').on('change', '.mbp-widget-editor .mbp-widget-book-display', function() { mbp_update_book_selector(this); });
});

function mbp_update_books(parent) {
	data = [];
	parent.find('.mbp-book-list .mbp-book').each(function(i, e) {
		data.push(parseInt(jQuery(e).attr('data-id'), 10));
	});
	parent.find('.mbp-manual-books').val(JSON.stringify(data));
	return true;
}

function mbp_init_book_remover(elem) {
	elem = jQuery(elem)
	elem.click(function(e) {
		parent = elem.parents('.mbp-widget-book-selector');
		console.log(elem);
		console.log(parent);
		elem.parent().remove();
		mbp_update_books(parent);
		return false;
	});
}

function mbp_initialize_widget_editor(elem) {
	parent = jQuery(elem);
	if(parent.attr('data-initialized') === 'true') { return false; }
	parent.attr('data-initialized', 'true');

	parent.find('.mbp-book-adder').click(function(e) {
		selector = parent.find('.mbp-book-selector');
		if(!selector.val()) { return false; }
		element = jQuery('<li data-id="'+selector.val()+'" class="mbp-book">'+selector.find(":selected").text()+'<a class="mbp-book-remover">X</a></li>');
		mbp_init_book_remover(element.find('.mbp-book-remover'));
		parent.find('.mbp-book-list').prepend(element);
		mbp_update_books(parent);
		selector.val('');
		return false;
	});

	parent.find('.mbp-book-remover').each(function(i, e) { mbp_init_book_remover(e); });

	parent.find('.mbp-book-list').sortable({stop: function() { mbp_update_books(parent); } });
}
