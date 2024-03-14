(function ($) {
	$('body').addClass('wizard__drawer--open');

	// select the demo to import on click and fix the next step url
	$('#demo-content-list li').on('click', function () {
		var t = $(this),
			chosen_demo = t.attr('data-demo'),
			selected_demo = $('.js-wizard-demo-import-select');
		t.addClass('selected').siblings().removeClass('selected');
		selected_demo.val(chosen_demo);
		var button_next = $('.wizard__button--next'),
		button_next_url = button_next.attr('href'),
		href = new URL(button_next_url, location);
		href.searchParams.set('selected_skin', chosen_demo);
		button_next.attr('href', href);

		var selected_category = t.attr('data-category');
		$('ul.skin-categories').find('.' + selected_category).find('a').trigger('click');
	});

	// select the first demo if none selected
	$('#demo-content-list li:first-child').addClass('selected').trigger('click');

	// retrieve url parameters
	function yith_proteo_wizard_get_query_string_params(){
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++)
		{
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
		return vars;
	}

	// select the demo to import from url
	function yith_proteo_set_demo_to_import_from_url() {
		var current_wizard_url = new URL(window.location.href),
		selected_skin = yith_proteo_wizard_get_query_string_params()["selected_skin"];
		if (undefined != selected_skin) {
			$('#demo-content-list li[data-demo="'+selected_skin+'"]').trigger('click');
			var selected_category = $('#demo-content-list li.selected').attr('data-category');
			$('ul.skin-categories').find('li').removeClass('active');
			$('ul.skin-categories').find('.' + selected_category).find('a').trigger('click').parent().addClass('active');
		}
	}
	yith_proteo_set_demo_to_import_from_url();


	$('.yith-proteo-toolkit-wizard-nav ul li.active').prevAll('li').addClass('step-done');

	// Filter demo list click on category element.
	$('ul.skin-categories').find('a').on('click', function(ev) {
		ev.preventDefault();
		var category_li_elements = $('ul.skin-categories').find('li'),
			chosen_category_li_element = $(this).parent(),
			chosen_category = chosen_category_li_element.attr('class').split(' ')[0];
		category_li_elements.removeClass('active');
		chosen_category_li_element.addClass('active');
		yith_proteo_toolkit_filter_listed_demos(chosen_category);
	})

	function yith_proteo_toolkit_filter_listed_demos( chosen_category ) {
		if ( undefined === chosen_category ) {
			chosen_category = $('ul.skin-categories').find('li.active').attr('class').split(' ')[0];
		}
		$("#demo-content-list li").each(function () {
			if ($(this).attr('data-category').search(new RegExp(chosen_category, "i")) < 0) {
				$(this).fadeOut(0);
			} else {
				$(this).fadeIn(0);
			}
		})
	}

	$('ul.skin-categories li.active').find('a').trigger('click');
})(jQuery);