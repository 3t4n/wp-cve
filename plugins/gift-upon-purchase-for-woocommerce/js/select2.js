jQuery(function($){
	// simple multiple select
	// $('#rudr_select2_tags').select2();
	// multiple select with AJAX search
	$('#gupfw_gift_for_any_product_arr').select2({
  		ajax: {
    			url: ajaxurl, // AJAX URL is predefined in WordPress admin
    			dataType: 'json',
    			delay: 250, // delay in ms while typing when to perform a AJAX search
    			data: function (params) {
      				return {
        				q: params.term, // search query
        				action: 'gupfwselect2' // AJAX action for admin-ajax.php
      				};
    			},
    			processResults: function(data) {
				var options = [];
				if (data) {
 
					// data is the array of arrays, and each of them contains ID and the Label of the option
					$.each(data, function(index, text) { // do not forget that "index" is just auto incremented value
						options.push({id: text[0], text: text[1]});
					});
 
				}
				return {
					results: options
				};
			},
			cache: true
		},
		minimumInputLength: 3 // the minimum of symbols to input before perform a search
	});
});
