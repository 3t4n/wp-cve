jQuery(document).ready(function($) {


	$('.proto_masonry_container').on('click', '.proto_image_pro_nav_previous',  function(event) {
		proto_ajax_nav_function($(this), 'prev', event);
	})
	$('.proto_masonry_container').on('click', '.proto_image_pro_nav_next',  function(event) {
		proto_ajax_nav_function($(this), 'next', event);
	});
	$('.proto_masonry_container').on('click', '.proto_page_nav',  function(event) {
		proto_ajax_nav_function($(this), 'page', event);
	});
	$('.proto_masonry_container').on('click', '.proto_page_nav_dots',  function(event) {
		proto_ajax_nav_function($(this), 'page', event);
	});

	//$('.proto_nav_more').click(function(event) {
	$(document).on("click", '.proto_nav_more' ,function() {

		proto_ajax_nav_function($(this), 'more', event);
	});


	function proto_ajax_nav_function(item, direction, event) {
		var container = item.closest('.proto_masonry_container'); //the grid container
		var widget = container.find('.proto_masonry_gallery');
		var pagingdiv = container.find('.proto_paging_items'); // the paging nav container
		pspm_item_id = pagingdiv.data('itemid');
		var ajaxpage = pagingdiv.data('ajaxpage');
		var max_pages = pagingdiv.data('max_pages');
		var navtype = pagingdiv.data('navtype')
		if (!ajaxpage) return;
		event.preventDefault();
		var page = pagingdiv.data('page');
		var uniqueid = pagingdiv.data('id');
		if (direction == 'page') var nextpage = item.data('page'); //get the next page number
		else nextpage = '';
		var atts = pagingdiv.children('#atts').first().val(); //get the query attributes for new grid
		var options = pagingdiv.children('#options').first().val(); //get the plugin options for the new grid
		var parent = item.parent();
		var parentid = parent.attr('id');
		jQuery.ajax({
			type: "POST",
			url: ajax_proto_posts.ajaxurl,
			dataType: 'json',
			data: {
				'action': 'proto_get_post_masonry',
				'direction': direction,
				'page': page,
				'nextpage': nextpage,
				'atts': atts,
				'options': options,
				'security':ajax_proto_posts.ajax_nonce,
			}
		}).done(function(response) {
			if (direction == 'more') {
				pagingdiv.data('page', page + 1);
				if (page + 1 >= max_pages) item.remove();
				var elems = [];
				elems.push(response);
				widget.append(response).masonry('reload');
				widget.masonry('appended', elems);
			} else container.html(response);
			container.trigger('updatemasonry'); //trigger the update event
			var parent = container.find('#'.parentid);
			$(document).find('parentid').scrollTop(parent.position); //this k
		}).fail(function(jqXHR, textStatus, errorThrown) {
			//alert('An error occurred... Look at the console (F12 or Ctrl+Shift+I, Console tab) for more information!');
			console.log('<p>status code: ' + jqXHR.status + '</p><p>errorThrown: ' + errorThrown);
			console.log('</p><p>jqXHR.responseText:</p><div>' + jqXHR.responseText + '</div>');
			console.log('jqXHR:');
			console.log(jqXHR);
			console.log('textStatus:');
			console.log(textStatus);
			console.log('errorThrown:');
			console.log(errorThrown);
		}).always(function(msg) {

		})
	};
})
