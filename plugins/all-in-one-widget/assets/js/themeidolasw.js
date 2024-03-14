jQuery(document).ready(function($) {

	var themeidolaswxhr;
	var $form;
	var $button = null;
	var buttontype;
	var buttoncontent;
	var themeidolaswcount = 1;
	var themeidolaswcounter;

	jQuery('.themeidol-ajaxsearch-widget form').on('submit', function(e) {

		e.preventDefault();

		var $results = jQuery(this).parent().find('.themeidolasw-results');
		$form = jQuery(this);

		// cancel previous requests
		if (themeidolaswxhr) themeidolaswxhr.abort();

		themeidolaswxhr = jQuery.ajax({
			type: "POST",
			url: themeidolasw.ajax_url,
			data: jQuery(this).serialize(),
			success: function(data) {
				//alert(data)

				if ($button.length) {
					themeidolaswupdatebutton(buttoncontent);
					clearInterval(themeidolaswcounter);
				}

				$results.html(data);
			},
			beforeSend: function() {

				$button = $form.find(':submit');

				if ($button.length) {
					buttontype = $button.prop("tagName").toLowerCase();
					buttoncontent =  themeidolaswgetbutton();
					themeidolaswupdatebutton('...');
					themeidolaswcounter = setInterval(themeidolaswloading, 333);
				}
			}
		});

		return false;
	});

	function themeidolaswloading() {

		if (themeidolaswcount == 3) {
			themeidolaswupdatebutton('...');
		} else {
			themeidolaswupdatebutton( themeidolaswgetbutton() + '.' );
		}

		themeidolaswcount++;
		if (themeidolaswcount == 4) themeidolaswcount = 0;
	}

	function themeidolaswupdatebutton(text) {
		if (buttontype == 'button') {
			$button.html(text);
		} else {
			$button.val(text);
		}
	}

	function themeidolaswgetbutton() {
		if (buttontype == 'button') {
			return $button.html();
		} else {
			return $button.val();
		}
	}
});